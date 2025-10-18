@php
	use App\Models\ReceivablePayment;
	use App\Models\CashBank;
	use App\Models\CashBankDetail;
	use App\Models\ProjectPay;
	use App\Models\Project;
	use App\Models\ProjectSaleReturn;
	use App\Models\ProjectDelivery;
	use App\Models\ProjectSale;
	
	$filter = $filter ? $filter : $filter_date;
	
	function getWhereRaw($param,$filter){
		$whereRaw = strlen($filter) == 7 ? "LEFT(DATE($param), 7) <= '$filter'" : "DATE($param) <= '$filter'";
		
		return $whereRaw;
	}
@endphp
<style>
	#datatable_serverside tbody tr.selected {
		background-color: green;
		color:white;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Outstanding A/R</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn bg-pink-400 btn-labeled mr-2 btn-labeled-left dropdown-toggle"  data-toggle="dropdown">
					<b><i class="icon-printer2"></i></b> Print</button>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" onclick="print()">Print All</a></li>
						<li><a class="dropdown-item" onclick="printSelected()">Selected Print</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Outstanding A/R</span>
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
						Periode {{ date('F Y', strtotime($filter)) }}</h6>
						<form method="GET" id="form_filter">
							@csrf
							<input type="hidden" name="filter_temp" id="filter_temp">
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
											<a href="{{ url('admin/report/finance/outstanding_a_r') }}" class="btn bg-danger btn-sm"><i class="icon-reset"></i> Reset</a>
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
							<li class="nav-item"><a href="#tab-project" class="nav-link active" data-toggle="tab">Project</a></li>
							<li class="nav-item"><a href="#tab-service" class="nav-link" data-toggle="tab">Service Cost (Outside Project)</a></li>
							<li class="nav-item"><a href="#tab-other" class="nav-link" data-toggle="tab">Other</a></li>
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane fade show active" id="tab-project">
								<div class="table-responsive mt-3">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="5%">No</th>
											<th>Customer</th>
											<th>Project</th>
											<th>Total</th>
											<th>View</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$totalproject = 0;
											$no = 1;
										@endphp
										@foreach($projectsale as $key => $row)
											@php
												$balance = 0;
											@endphp
											<tr data-id ={{$row->id}} class="pick">
												<td class="text-center">{{ $key+1 }}</td>
												<td>{{ $row->project->customer->name.' - '.$row->project->customer->phone }}</td>
												<td class="text-right">{{ $row->project->code.' - '.$row->project->name }}</td>
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
														<tbody>@foreach($row->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->whereRaw(getWhereRaw('received_date',$filter))->get() as $rd)
																@php
																	$balance += round($rd->grandtotal_product + $rd->grandtotal_service);
																@endphp
																<tr>
																	<td class="text-center">{{ $rd->project->code.' - '.$rd->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rd->received_date)) }}</td>
																	<td class="text-center">{{ number_format(round($rd->grandtotal_product + $rd->grandtotal_service),2,',','.') }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															@foreach($row->projectSaleReturn()->whereRaw(getWhereRaw('date_return',$filter))->get() as $rsr)
																@php
																	$balance -= round($rsr->getTotal());
																@endphp
																<tr>
																	<td class="text-center">{{ $rsr->project->code.' '.$rsr->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rsr->created_at)) }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format(round($rsr->getTotal()),0,',','.') }}</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															
															@foreach($row->projectSalePay()->whereRaw(getWhereRaw('date',$filter)." AND (project_bill_id IS NULL OR project_bill_id = 0)")->get() as $rsp)
																@php
																	$balance -= $rsp->nominal;
																@endphp
																<tr>
																	<td class="text-center">{{ $rsp->project->code.' - '.$rsp->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rsp->date)) }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($rsp->nominal,2,',','.') }}</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															
															@foreach($row->project->projectBill()->whereRaw(getWhereRaw('date',$filter))->get() as $pb)
																@php
																if($pb->journal()){
																	$balance -= ($pb->nominal + $pb->nominal_service);
																@endphp
																<tr>
																	<td class="text-center">{{ $pb->project->code.' - '.$pb->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($pb->date)) }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format(($pb->nominal + $pb->nominal_service),2,',','.') }}</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
																@php
																}
																@endphp
															@endforeach
															
															@php
																$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->project->id)->whereRaw(getWhereRaw('date',$filter))->get();
			
																if(count($cb) > 0){
																	foreach($cb as $rowcb){
																		foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
																			$balance -= $cbcb->nominal;
																			@endphp
																				<tr>
																					<td class="text-center">{{ $rowcb->code.' - '.$rowcb->description }}</td>
																					<td class="text-center">{{ date('d M Y',strtotime($rowcb->date)) }}</td>
																					<td class="text-center">0</td>
																					<td class="text-center">{{ number_format($cbcb->nominal,2,',','.') }}</td>
																					<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																				</tr>
																			@php
																		}
																	}
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
										
										@foreach($projectbill as $key => $row)
											@php
												$balance = 0;
												$balance += $row->nominal + $row->nominal_service;
											@endphp
											<tr>
												<td class="text-center">{{ $no }}</td>
												<td>{{ $row->project->customer->name.' - '.$row->project->customer->phone }} - PROJECT BILL</td>
												<td class="text-right">{{ $row->project->code.' - '.$row->project->name }}</td>
												<td class="text-right">{{ number_format($row->balancePeriod($filter),2,',','.') }}</td>
												<td class="text-center">
													<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapse-button-{{ str_replace('/','',$row->code) }}"><i class="icon-eye"></i></button>
												</td>
											</tr>
											<tr class="collapse" id="collapse-button-{{ str_replace('/','',$row->code) }}">
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
															<tr>
																<td class="text-center">{{ $row->project->code.' - '.$row->code }}</td>
																<td class="text-center">{{ date('d M Y',strtotime($row->date)) }}</td>
																<td class="text-center">{{ number_format($row->nominal + $row->nominal_service,2,',','.') }}</td>
																<td class="text-center">0</td>
																<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
															</tr>
															@foreach(ProjectPay::where('project_bill_id',$row->id)->whereRaw(getWhereRaw('date',$filter))->get() as $rowpay)
																@php
																	$balance -= $rowpay->nominal;
																@endphp
																<tr>
																	<td class="text-center">{{ $rowpay->project->code.' '.$rowpay->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rowpay->date)) }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($rowpay->nominal,0,',','.') }}</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
															
															@php
																$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->project->id)->whereRaw(getWhereRaw('date',$filter))->get();
			
																if(count($cb) > 0){
																	foreach($cb as $rowcb){
																		foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
																			if($cbcb->type == '2'){
																				$balance -= $cbcb->nominal;
																				@endphp
																					<tr>
																						<td class="text-center">{{ $rowcb->code.' - '.$rowcb->description }}</td>
																						<td class="text-center">{{ date('d M Y',strtotime($rowcb->date)) }}</td>
																						<td class="text-center">0</td>
																						<td class="text-center">{{ number_format($cbcb->nominal,2,',','.') }}</td>
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
												$totalproject += $row->balancePeriod($filter);
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

							<div class="tab-pane fade" id="tab-service">
								<div class="table-responsive mt-3">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="5%">No</th>
											<th>Customer</th>
											<th>Service</th>
											<th>Total</th>
											<th>View</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$totalservice = 0;
											$no = 1;
										@endphp
										@foreach($servicecharges as $key => $row)
											@php
												$balance = $row->grandtotal_service;
											@endphp
											<tr data-id ={{$row->id}} class="pick">
												<td class="text-center">{{ $key+1 }}</td>
												<td>{{ $row->customer->name.' - '.$row->customer->phone }}</td>
												<td class="text-right">{{ $row->code }}</td>
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
															<tr>
																<td class="text-center">{{ $row->code }}</td>
																<td class="text-center">{{ date('d M Y',strtotime($row->created_at)) }}</td>
																<td class="text-center">{{ number_format($row->grandtotal_service,2,',','.') }}</td>
																<td class="text-center">0</td>
																<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
															</tr>
															@foreach($row->serviceCostPayment()->whereRaw(getWhereRaw('date_paid',$filter))->get() as $rowpayment)
																@php
																	$balance -= $rowpayment->nominal;
																@endphp
																<tr>
																	<td class="text-center">{{ $rowpayment->serviceCost->code.' '.$rowpayment->code }}</td>
																	<td class="text-center">{{ date('d M Y',strtotime($rowpayment->date_paid)) }}</td>
																	<td class="text-center">0</td>
																	<td class="text-center">{{ number_format($rowpayment->nominal,0,',','.') }}</td>
																	<td class="text-center">{{ number_format($balance,0,',','.') }}</td>
																</tr>
															@endforeach
														</tbody>
											
													</table>
												</td>
											</tr>
											@php
												$totalservice += $row->totalbalance;
												$no++;
											@endphp
										@endforeach
									  </tbody>
									  <tfoot>
										<tr class="text-right">
											<th colspan="3">Total</th>
											<th>{{ number_format($totalservice,2,',','.') }}</th>
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
											<th>Customer</th>
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
											$projectReturnId = [];
										@endphp
										
										@foreach($other as $row)
											@php
												$total = $row->nominal;
											
												$data = ReceivablePayment::where('cash_bank_id',$row->cashBank->id)->whereRaw(getWhereRaw('date',$filter))->get();
												if($data){	
													
													foreach($data as $dataku){
														foreach(CashBank::where('lookable_type','receivable_payments')->where('lookable_id',$dataku->id)->whereRaw(getWhereRaw('date',$filter))->get() as $cb){
															foreach($cb->cashBankDetail()->where('coa_id',27)->where('type','2')->get() as $cbd){
																$total -= $cbd->nominal;
															}
														}
													}
												}
												
												if($row->cashBank->lookable_type == 'projects' || $row->cashBank->lookable_type == 'project_deliveries'){
													if($row->cashBank->lookable_type == 'projects'){
														$project = Project::find($row->cashBank->lookable_id);
														$sales = $project->projectSale()->first();
														if($sales){
															foreach($sales->projectSaleReturn()->whereRaw(getWhereRaw('date_return',$filter))->get() as $rowreturn){
																foreach(CashBank::where('lookable_type','project_sale_returns')->whereRaw(getWhereRaw('date',$filter))->where('lookable_id',$rowreturn->id)->get() as $cb){
																	if (!in_array($rowreturn->id, $projectReturnId)) {
																			array_push($projectReturnId, $rowreturn->id);
																			foreach($cb->cashBankDetail()->where('coa_id',27)->where('type','2')->where('branch','1')->get() as $cbd){
																				$total -= $cbd->nominal;
																		}
																	}
																}
															}
														}
													}elseif($row->cashBank->lookable_type == 'project_deliveries'){
														$delivery = ProjectDelivery::find($row->cashBank->lookable_id);
														$sales = $delivery->projectSale;
														if($sales){
															foreach($sales->projectSaleReturn()->whereRaw(getWhereRaw('date_return',$filter))->get() as $rowreturn){
																foreach(CashBank::where('lookable_type','project_sale_returns')->whereRaw(getWhereRaw('date',$filter))->where('lookable_id',$rowreturn->id)->get() as $cb){
																	if (!in_array($rowreturn->id, $projectReturnId)) {
																			array_push($projectReturnId, $rowreturn->id);
																			foreach($cb->cashBankDetail()->where('coa_id',27)->where('type','2')->where('branch','1')->get() as $cbd){
																				$total -= $cbd->nominal;
																		}
																	}
																}
															}
														}
													}
												}
												
												if(round($total,2) > 0){
												$balance += $total;
											@endphp
											<tr>
												<td class="text-center">{{ $no }}</td>
												<td>{{ $row->cashBank->customer ? $row->cashBank->customer->name : '' }}</td>
												<td>{{ date('d M Y',strtotime($row->cashBank->date)) }}</td>
												<td>{{ $row->cashBank->description }}</td>
												<td class="text-right">{{ number_format($total,2,',','.') }}</td>
												<td class="text-right">{{ number_format($balance,2,',','.') }}</td>
											</tr>
											@php
												$no++;
												}
											@endphp
										@endforeach
										
									  </tbody>
								   </table>
								</div>
							</div>
						</div>
						<h1>Total All : Rp {{ number_format($balance + $totalproject + $totalservice,2,',','.') }}</h1>
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

			$('#datatable_serverside tbody').on('click', 'tr', function () {
				if ($(this).hasClass("pick")) {
					$(this).toggleClass('selected');
				}else {
					notif('error', 'bg-danger', 'Bill cannot be selected.');
				}

			
				var arrId = [];
				
				$('#datatable_serverside tr.selected').each(function(){
					if($(this).data('id') != undefined){
						arrId.push($(this).data('id'));
					}
				});
				
				$('#filter_temp').val(arrId.join());

				console.log(arrId)
			});
		});
		
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
	
        function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }
		
		function print(){
			let url = '@php echo url("admin/report/finance/outstanding_a_r/print/oar?branch=".$branch."&monthyear=".$filter); @endphp';
			window.open(url, '_blank');
		}

		function printSelected(){
			var id = encodeURIComponent($('#filter_temp').val());
			let url = '@php echo url("admin/report/finance/outstanding_a_r/print/oar_selected?branch=".$branch."&monthyear=".$filter."&id=") @endphp' + id;

			return window.location.href = url;
		
		}

		function exportFile(){
			let url = '@php echo url("admin/report/finance/outstanding_a_r/export/ar?branch=".$branch."&monthyear=".$filter); @endphp';
			return window.location.href = url;
		}
    </script>
	