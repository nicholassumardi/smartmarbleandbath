@php
	use App\Models\Transfer;
	use App\Models\CashBank;
	use App\Models\PurchaseRequest;
	use App\Models\PurchaseCost;
@endphp
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Outstanding A/P</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn bg-green-400 btn-labeled mr-2 btn-labeled-left" onclick="exportFile()">
					<b><i class="icon-file-excel"></i></b> Export</button>
				</div>
				<div class="d-flex justify-content-center">
					<button class="btn bg-pink-400 btn-labeled mr-2 btn-labeled-left" onclick="print()">
					<b><i class="icon-printer2"></i></b> Print</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Outstanding A/P</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
				<div class="row justify-content-center">
				   <div class="col-md-12">
					  <h6 class="text-muted text-uppercase text-center font-weight-bold">
						A/P REPORT PER {{ $mode == '1' ? date('F Y', strtotime($filter)) : date('d M Y', strtotime($filter_date)) }}</h6>
						<form method="GET" id="form_filter">
							@csrf
							<div class="form-group">
								<center class="d-block">
									<div class="row justify-content-center no-gutters">
										<div class="col-md-3">
											<div class="form-group">
												<label style="margin-bottom: 0rem;">Mode</label>
												<select name="mode" id="mode" class="form-control" onchange="changeMode(this.value)">
													<option value="1" {{ $mode == '1' ? 'selected' : '' }}>Month</option>
													<option value="2" {{ $mode == '2' ? 'selected' : '' }}>Date</option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label style="margin-bottom: 0rem;">Branch</label>
												<select name="branch" id="branch" class="form-control">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id}}" {{$company->id == $branch ? 'selected' : '' }}>{{$company->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3 mode1">
											<label style="margin-bottom: 0rem;">Month</label>
											<input type="month" name="filter" id="filter" class="form-control" value="{{ $filter }}">
										</div>
										<div class="col-md-3 mode2 d-none">
											<label style="margin-bottom: 0rem;">Date</label>
											<input type="date" name="filter_date" id="filter_date" class="form-control" value="{{ $filter_date }}">
										</div>
									</div>
									<div class="row justify-content-center mt-3">
										<div class="col-md-3">
											<button class="btn bg-success btn-sm mr-3" onclick="submitFilter()"><i class="icon-search4"></i> Process</button>
											<a href="{{ url('admin/report/finance/outstanding_a_p') }}" class="btn bg-danger btn-sm"><i class="icon-reset"></i> Reset</a>
										</div>
									</div>
								</center>
							</div>
						</form>
				   </div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
							<li class="nav-item"><a href="#tab-project" class="nav-link active" data-toggle="tab">Purchase Order</a></li>
							<li class="nav-item"><a href="#tab-sample" class="nav-link" data-toggle="tab">Sample Purchase Order</a></li>
							<li class="nav-item"><a href="#tab-other" class="nav-link" data-toggle="tab">Other</a></li>
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane fade show active" id="tab-project">
								<div class="table-responsive">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="5%">No</th>
											<th>Supplier</th>
											<th>Project</th>
											<th>Nominal</th>
											<th>View</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$totalproject = 0;
											$no = 1;
										@endphp
										@foreach($projectpurchase as $key => $row)
											@php
												$balance = 0;
											@endphp
											<tr>
												<td class="text-center">{{ $key+1 }}</td>
												<td>{{ $row->supplier->name }}</td>
												<td class="text-right">{{ $row->project ? $row->project->code.' - '.$row->project->name.' PO : '.$row->code : 'For Stock PO : '.$row->code }}</td>
												<td class="text-right">{{ number_format($row->totalbalance,2,',','.') }}</td>
												<td class="text-center">
													<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapse-button-{{ $no }}"><i class="icon-eye"></i></button>
												</td>
											</tr>
											<tr class="collapse" id="collapse-button-{{ $no }}">
												<td colspan="5">
													<table class="table table-bordered table-striped w-100">
														<thead class="bg-dark">
															 <tr class="text-center">
																<th>Information</th>
																<th>Date</th>
																<th>Debit</th>
																<th>Credit</th>
																<th>Balance</th>
															 </tr>
														</thead>
														<tbody>
															@php
																$whereRaw = $mode == '1' ? "LEFT(DATE(date), 7) <= '$filter'" : "DATE(date) <= '$filter_date'";
																$whereRaw2 = $mode == '1' ? "LEFT(DATE(date_receive), 7) <= '$filter'" : "DATE(date_receive) <= '$filter_date'";
															@endphp
															@foreach($row->projectWarehouse()->whereRaw($whereRaw2)->get() as $pw)
																@php
																	$balance += round($pw->getTotal()['totalpurchase']);
																@endphp
																<tr>
																	<td class="text-center">{{ $pw->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($pw->date_receive)) }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format(round($pw->getTotal()['totalpurchase']),2,',','.') }}</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															@foreach($row->projectPurchaseReturn()->whereRaw($whereRaw)->get() as $rsr)
																@php
																	$balance -= round($rsr->getTotal());
																@endphp
																<tr>
																	<td class="text-center">{{ $rsr->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rsr->date)) }}</td>
																	<td class="text-center">{{ number_format(round($rsr->getTotal()),2,',','.') }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															
															@foreach($row->projectPurchasePayment()->whereRaw($whereRaw)->get() as $rsp)
																@php
																	$balance -= $rsp->nominal;
																@endphp
																<tr>
																	<td class="text-center">{{ $rsp->projectPurchase->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rsp->date)) }}</td>
																	<td class="text-center">{{ number_format($rsp->nominal,2,',','.') }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															
															@php
															$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$row->id)->whereRaw($whereRaw)->get();
			
															if(count($cb) > 0){
																foreach($cb as $rowcb){
																	foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
																		if($cbcb->type == '1'){
																			$balance -= $cbcb->nominal;
															@endphp
																<tr>
																	<td class="text-center">{{ $rowcb->code.' - '.$rowcb->description }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rowcb->date)) }}</td>
																	<td class="text-center">{{ number_format($cbcb->nominal,2,',','.') }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@php
																			
																		}
																	}
																}
															}
															
															$pc = PurchaseCost::where('project_purchase_id',$row->id)->first();
				
															if($pc){
																$balance -= $pc->totalCost();
																@endphp
																	<tr>
																		<td class="text-center">Purchase Cost {{ $pc->coa->name }}</td>
																		<td class="text-center">-</td>
																		<td class="text-center">{{ number_format($pc->totalCost(),2,',','.') }}</td>
																		<td class="text-center">0</td>
																		<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																	</tr>
																@php
															}
															@endphp
														</tbody>
													</table>
												</td>
											</tr>
											@php
												$totalproject += $row->totalbalance;
												$no++;
											@endphp
										@endforeach
									  </tbody>
									  <tfoot>
										<tr class="text-right">
											<th colspan="3">Total</th>
											<th>{{ number_format($totalproject,2,',','.') }}</th>
											<th></th>
										</tr>
									  </tfoot>
								   </table>
								</div>
							</div>

							<div class="tab-pane fade show" id="tab-sample">
								<div class="table-responsive">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="5%">No</th>
											<th>Supplier</th>
											<th>Sample Project</th>
											<th>Nominal</th>
											<th>View</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$totalprojectsample = 0;
											$no = 1;
										@endphp
										@foreach($samplepurchase as $key => $row)
											@php
												$balance = 0;
											@endphp
											<tr>
												<td class="text-center">{{ $key+1 }}</td>
												<td>{{ $row->supplier->name }}</td>
												<td class="text-right">{{ $row->sample ? $row->sample->code.' - '.$row->sample->name.' PO : '.$row->code : 'For Stock PO : '.$row->code }}</td>
												<td class="text-right">{{ number_format($row->totalbalance,2,',','.') }}</td>
												<td class="text-center">
													<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapse-button-{{ $no }}"><i class="icon-eye"></i></button>
												</td>
											</tr>
											<tr class="collapse" id="collapse-button-{{ $no }}">
												<td colspan="5">
													<table class="table table-bordered table-striped w-100">
														<thead class="bg-dark">
															 <tr class="text-center">
																<th>Information</th>
																<th>Date</th>
																<th>Debit</th>
																<th>Credit</th>
																<th>Balance</th>
															 </tr>
														</thead>
														<tbody>
															@php
																$whereRaw = $mode == '1' ? "LEFT(DATE(date), 7) <= '$filter'" : "DATE(date) <= '$filter_date'";
																$whereRaw2 = $mode == '1' ? "LEFT(DATE(date_receive), 7) <= '$filter'" : "DATE(date_receive) <= '$filter_date'";
															@endphp
															@foreach($row->sampleWarehouse()->whereRaw($whereRaw2)->get() as $pw)
																@php
																	$balance += round($pw->getTotal()['totalpurchase']);
																@endphp
																<tr>
																	<td class="text-center">{{ $pw->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($pw->date_receive)) }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format(round($pw->getTotal()['totalpurchase']),2,',','.') }}</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															@foreach($row->samplePurchaseReturn()->whereRaw($whereRaw)->get() as $rsr)
																@php
																	$balance -= round($rsr->getTotal());
																@endphp
																<tr>
																	<td class="text-center">{{ $rsr->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rsr->date)) }}</td>
																	<td class="text-center">{{ number_format(round($rsr->getTotal()),2,',','.') }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															
															
															@php
															$cb = CashBank::where('lookable_type','sample_purchases')->where('lookable_id',$row->id)->whereRaw($whereRaw)->get();
			
															if(count($cb) > 0){
																foreach($cb as $rowcb){
																	foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
																		if($cbcb->type == '1'){
																			$balance -= $cbcb->nominal;
															@endphp
																<tr>
																	<td class="text-center">{{ $rowcb->code.' - '.$rowcb->description }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rowcb->date)) }}</td>
																	<td class="text-center">{{ number_format($cbcb->nominal,2,',','.') }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
																@php
																			
																		}
																	}
																}
															}
															@endphp
														</tbody>
													</table>
												</td>
											</tr>
											@php
												$totalprojectsample += $row->totalbalance;
												$no++;
											@endphp
										@endforeach
									  </tbody>
									  <tfoot>
										<tr class="text-right">
											<th colspan="3">Total</th>
											<th>{{ number_format($totalprojectsample,2,',','.') }}</th>
											<th></th>
										</tr>
									  </tfoot>
								   </table>
								</div>
							</div>

							<div class="tab-pane fade" id="tab-other">
								<div class="table-responsive">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="5%">No</th>
											<th>Supplier</th>
											<th>Date</th>
											<th>Detail</th>
											<th>Total</th>
											<th>Balance</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$balance = 0;
											$no = 1;
											$filter = $filter ? $filter : $filter_date;
										@endphp
										@foreach($other as $row)
											@php
												if($row->cashBank->lookable_type == 'purchase_requests' || $row->cashBank->lookable_type == 'projects' || str_contains($row->cashBank->code, 'RJCT')  ||  $row->cashBank->lookable_type == null ){
													$cek = NULL;
													$cek_rjct = NULL;


													if($row->cashBank->lookable_type == 'purchase_requests'){
														$cek = PurchaseRequest::find($row->cashBank->lookable_id);
													}elseif($row->cashBank->lookable_type == 'projects'){

														$cek = PurchaseRequest::find(explode('-',$row->cashBank->code)[1]);
														
													}elseif(str_contains($row->cashBank->code, 'RJCT')){
														// jika ada status reject maka hutang/ payable dijurnal balik dengan reject
														$whereRaw = strlen($filter) == 7 ? "LEFT(date, 7) <= '$filter'" : "date <= '$filter'";
														$isMultipleReject = explode("-",$row->cashBank->code);
														$length_of_last_code = isset($isMultipleReject[3]) ? strlen($isMultipleReject[3]) : 0;

														$cek_rjct = isset($isMultipleReject[3]) ? CashBank::where('code','like',substr($row->cashBank->code, 0, -$length_of_last_code).'CLOSE-%'.$isMultipleReject[3])->whereRaw($whereRaw)->first() : CashBank::where('code','like', $row->cashBank->code.'-CLOSE%')->whereRaw($whereRaw)->first() ;
													}elseif($row->cashBank->lookable_type == null){
														
													}
													
													$totalpay = $cek ? $cek->totalPaymentPeriod($filter) : ($cek_rjct ? $cek_rjct->getRejectedPurchaseRequestNominal($branch) : 0);

													$sisa = $row->nominal - $totalpay;
													if($sisa > 0){
														$balance += $sisa;
												@endphp
												<tr>
													<td class="text-center">{{ $no }}</td>
													<td>{{ (isset($row->cashBank->supplier->name) ? $row->cashBank->supplier->name  : '') .' '. $row->cashBank->code }}</td>
													<td>{{ date('d M Y',strtotime($row->cashBank->date)) }}</td>
													<td>{{ $row->cashBank->description.' - '.$row->note }}</td>
													<td class="text-right">{{ number_format($sisa,2,',','.') }}</td>
													<td class="text-right">{{ number_format($balance,2,',','.') }}</td>
												</tr>
												@php
													$no++;
													}
												}else{
													if($row->cashBank->lookable_type !== 'project_warehouses'){
													$balance += $row->nominal;
												@endphp
												<tr>
													<td class="text-center">{{ $no }}</td>
													<td>{{ (isset($row->cashBank->supplier->name) ? $row->cashBank->supplier->name  : '') .' '.$row->cashBank->code }}</td>
													<td>{{ date('d M Y',strtotime($row->cashBank->date)) }}</td>
													<td>{{ $row->cashBank->description.' - '.$row->note }}</td>
													<td class="text-right">{{ number_format($row->nominal,2,',','.') }}</td>
													<td class="text-right">{{ number_format($balance,2,',','.') }}</td>
												</tr>
												@php	
													$no++;
													}
												}
												@endphp
										@endforeach
										
									  </tbody>
								   </table>
								</div>
							</div>
						</div>
						<h1>Total All : Rp {{ number_format($balance + $totalproject + $totalprojectsample,2,',','.') }}</h1>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<script>
		$(function() {
			@if($mode == '1')
				$('.mode2').addClass('d-none');
				$('#filter_date').val('');
				$('.mode1').removeClass('d-none');
			@elseif($mode == '2')
				$('.mode1').addClass('d-none');
				$('#filter').val('');
				$('.mode2').removeClass('d-none');
			@endif
			
			$("html, body").animate({
				scrollTop: $(
				  'html, body').get(0).scrollHeight
			}, 500);
		});
	   
        function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }
		
		function changeMode(val){
			if(val == '1'){
				$('.mode1').removeClass('d-none');
				$('#filter_date').val('');
				$('.mode2').addClass('d-none');
			}else if(val == '2'){
				$('.mode2').removeClass('d-none');
				$('#filter').val('');
				$('.mode1').addClass('d-none');
			}
		}
		
		function print(){
			let url = '@php echo url("admin/report/finance/outstanding_a_p/print/oap?branch=".$branch."&monthyear=".$filter); @endphp';
			window.open(url, '_blank');
		}

		function exportFile(){
			let url = '@php echo url("admin/report/finance/outstanding_a_p/export/ap?branch=".$branch."&monthyear=".$filter); @endphp';
			return window.location.href = url;
		}
    </script>
	