@php
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\PurchaseRequest;
use App\Models\Transfer;
use App\Models\PurchaseCost;
@endphp
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Aging Payable (UNDER CONSTRUCTION)</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Aging Payable</span>
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
							<div class="form-group">
								<center class="d-block">
									<div class="row justify-content-center no-gutters">
										<div class="col-md-3">
											<div class="form-group">
												<label>Branch</label>
												<select name="branch" id="branch" class="form-control"
													onchange="submitFilter()">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id == $branch ? 'selected' : '' }}">{{$company->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<label>Month Year</label>
											<input type="month" name="filter" id="filter" class="form-control"
												value="{{ $filter }}" onchange="submitFilter()">
										</div>
										@if (date('Y-m') != $filter)
										<div class="col-md-1">
											<a href="{{ url('admin/report/accounting/aging_payable') }}"
												class="btn bg-danger btn-sm">Reset</a>
										</div>
										@endif
									</div>
								</center>
							</div>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
							<li class="nav-item"><a href="#tab-project" class="nav-link active"
									data-toggle="tab">Project</a></li>
							<li class="nav-item"><a href="#tab-other" class="nav-link" data-toggle="tab">Other</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="tab-project">
								<div class="table-responsive">
									<table id="datatable_serverside" class="table table-bordered table-striped w-100">
										{{-- <thead class="bg-dark">
											<tr class="text-center">
												<th width="5%">No</th>
												<th>Supplier</th>
												<th>Information</th>
												<th>Project</th>
												<th>Term</th>
												<th>Date</th>
												<th>Total</th>
												<th>Balance</th>
											</tr>
										</thead> --}}
										<thead class="bg-dark">
											<tr class="text-center">
												<th rowspan="2" width="5%">No</th>
												<th rowspan="2">Supplier</th>
												<th colspan="4">Term (days)</th>
												<th rowspan="2">Total</th>
												<th rowspan="2" width="5%">A/P Card</th>
											</tr>
											<tr class="text-center">
												<th>0-30</th>
												<th>31-60</th>
												<th>61-90</th>
												<th>Over 90</th>
											</tr>
										</thead>
										{{-- <tbody>
											@php
											$balance = 0;
											$no = 1;
											@endphp
											@foreach($projectpurchase as $row)
											@php
											$totalreceived = 0;
											$totalreturn = 0;
											$totalpay = 0;
											$totaltransfer = 0;
											$received_date = 'Received Date <br>';

											foreach($row->projectWarehouse as $pw){
											$totalreceived += round($pw->getTotal()['totalpurchase']);

											$received_date .= date('d M Y',strtotime($pw->date_receive)).'<br>';
											}

											foreach($row->projectPurchaseReturn as $rsr){
											$totalreturn += round($rsr->getTotal());
											}

											foreach($row->projectPurchasePayment as $rsp){
											$totalpay += $rsp->nominal;
											}

											foreach($row->transfer()->where('status','1')->orWhere('status','3') as
											$rt){
											$totaltransfer += $rt->getTotal();
											}

											if(round(($totalreceived - $totalreturn - $totalpay - $totaltransfer)) > 0){
											$balance += ($totalreceived - $totalreturn - $totalpay - $totaltransfer);
											@endphp
											<tr class="">
												<td class="text-center">{{ $no }}</td>
												<td>{{ $row->supplier->name }}</td>
												<td>{{ $row->project ? $row->project->name : 'For Stock' }}</td>
												<td>{{ $row->project ? $row->project->code : 'For Stock' }}</td>
												<td class="text-center">{{ $row->payment_method.' days' }}</td>
												<td class="text-center">{!! $received_date !!}</td>
												<td class="text-right">{{ number_format($totalreceived - $totalreturn -
													$totalpay - $totaltransfer,2,',','.') }}</td>
												<td class="text-right">{{ number_format($balance,2,',','.') }}</td>
											</tr>
											@php
											$no++;
											}
											@endphp
											@endforeach
										</tbody> --}}
										<tbody>
											@php
											$totalproject = 0;
											$total30 = 0;
											$total60 = 0;
											$total90 = 0;
											$totalover = 0;
											@endphp
											@foreach($data as $key => $row)
											<tr>
												<td class="text-center">{{ ($key + 1) }}</td>
												<td>{{ $row['supplier_name'] }}</td>
												<td class="text-right" data-trigger="hover" data-popup="popover"
													data-placement="top" title="{{ $row['supplier_name'] }}"
													data-content="Coming soon..." data-html="true">{{
													number_format($row['total30'],2,',','.') }}</td>
												<td class="text-right" data-trigger="hover" data-popup="popover"
													data-placement="top" title="{{ $row['supplier_name'] }}"
													data-content="Coming soon..." data-html="true">{{
													number_format($row['total60'],2,',','.') }}</td>
												<td class="text-right" data-trigger="hover" data-popup="popover"
													data-placement="top" title="{{ $row['supplier_name'] }}"
													data-content="Coming soon..." data-html="true">{{
													number_format($row['total90'],2,',','.') }}</td>
												<td class="text-right" data-trigger="hover" data-popup="popover"
													data-placement="top" title="{{ $row['supplier_name'] }}"
													data-content="Coming soon..." data-html="true">{{
													number_format($row['totalover'],2,',','.') }}</td>
												<td class="text-right">{{ number_format($row['total'],2,',','.') }}</td>
												<td class="text-center">
													<a onclick="showAPCards({{ $row['supplier_id'] }});"
														href="javascript:void(0);" class="btn btn-sm bg-info"><i
															class="icon-calculator2"></i></a>
												</td>
											</tr>
											@php
											$totalproject += $row['total'];
											$total30 += $row['total30'];
											$total60 += $row['total60'];
											$total90 += $row['total90'];
											$totalover += $row['totalover'];
											@endphp
											@endforeach
											<tr>
												<td class="text-center" colspan="2">
													<h5>Grandtotal</h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($total30,2,',','.') }}</b></h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($total60,2,',','.') }}</b></h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($total90,2,',','.') }}</b></h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($totalover,2,',','.') }}</b></h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($totalproject,2,',','.') }}</b></h5>
												</td>
												<td class="text-right"></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane fade" id="tab-other">
								<div class="table-responsive">
									<table id="datatable_serverside" class="table table-bordered table-striped w-100">
										{{-- <thead class="bg-dark">
											<tr class="text-center">
												<th width="5%">No</th>
												<th>Supplier</th>
												<th>Detail</th>
												<th>Total</th>
												<th>Balance</th>
											</tr>
										</thead> --}}
										{{-- <tbody>
											@php
											$balance = 0;
											$no = 1;
											@endphp
											@foreach($other as $row)
											@php
											if($row['lookable_type'] == 'purchase_requests'){
											$totalpay = $row->lookable->totalPayment();
											$sisa = $row->nominal - $totalpay;
											if($sisa > 0){
											$balance += $sisa;
											@endphp
											<tr>
												<td class="text-center">{{ $no }}</td>
												<td>{{ $row->supplier->name }}</td>
												<td>{{ $row->description }}</td>
												<td class="text-right">{{ number_format($sisa,2,',','.') }}</td>
												<td class="text-right">{{ number_format($balance,2,',','.') }}</td>
											</tr>
											@php
											$no++;
											}
											}else{
											if($row['lookable_type'] !== 'project_warehouses'){
											$balance += $row->nominal;
											@endphp
											<tr>
												<td class="text-center">{{ $no }}</td>
												<td>{{ $row->supplier->name }}</td>
												<td>{{ $row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,2,',','.') }}</td>
												<td class="text-right">{{ number_format($balance,2,',','.') }}</td>
											</tr>
											@php
											$no++;
											}
											}
											@endphp
											@endforeach


										</tbody> --}}
										<thead class="bg-dark">
											<tr class="text-center">
												<th rowspan="2" width="5%">No</th>
												<th rowspan="2">Supplier</th>
												<th rowspan="2">Description</th>
												<th colspan="4">Term (days)</th>
												<th rowspan="2">Balance</th>
											</tr>
											<tr class="text-center">
												<th>0-30</th>
												<th>31-60</th>
												<th>61-90</th>
												<th>Over 90</th>
											</tr>
										</thead>
										<tbody>
											@php
											$no = 0;
											$totalother = 0;
											$total30ot = 0;
											$total60ot = 0;
											$total90ot = 0;
											$totaloverot = 0;
											$balance_real = 0;
											@endphp
											@foreach($dataother as $key => $row)
											@if($row['balance_real'] > 0)
												<tr>
													<td class="text-center">{{ ($no + 1) }}</td>
													<td>{{ $row['supplier_name'] }}</td>
													<td>{{ $row['description'] }}</td>
													<td class="text-right" data-trigger="hover" data-popup="popover"
														data-placement="top" title="{{ $row['supplier_name'] }}"
														data-content="Coming soon..." data-html="true">{{
														number_format($row['total30'],2,',','.') }}</td>
													<td class="text-right" data-trigger="hover" data-popup="popover"
														data-placement="top" title="{{ $row['supplier_name'] }}"
														data-content="Coming soon..." data-html="true">{{
														number_format($row['total60'],2,',','.') }}</td>
													<td class="text-right" data-trigger="hover" data-popup="popover"
														data-placement="top" title="{{ $row['supplier_name'] }}"
														data-content="Coming soon..." data-html="true">{{
														number_format($row['total90'],2,',','.') }}</td>
													<td class="text-right" data-trigger="hover" data-popup="popover"
														data-placement="top" title="{{ $row['supplier_name'] }}"
														data-content="Coming soon..." data-html="true">{{
														number_format($row['totalover'],2,',','.') }}</td>
													<td class="text-right">{{ number_format($row['balance_real'],2,',','.') }}</td>
												</tr>

												@php
												$no++;
												$totalother += $row['total'];
												$total30ot += $row['total30'];
												$total60ot += $row['total60'];
												$total90ot += $row['total90'];
												$totaloverot += $row['totalover'];
												$balance_real += $row['balance_real'];
												@endphp
												
											@endif
											@endforeach
											<tr>
												<td class="text-center" colspan="3">
													<h5>Grandtotal</h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($total30ot,2,',','.') }}</b></h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($total60ot,2,',','.') }}</b></h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($total90ot,2,',','.') }}</b></h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($totaloverot,2,',','.') }}</b></h5>
												</td>
												<td class="text-right">
													<h5><b>{{ number_format($balance_real,2,',','.') }}</b></h5>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<h1>Total All : Rp {{ number_format($totalproject + $balance_real,2,',','.') }}</h1>
					</div>
				</div>

			</div>
		</div>
	</div>
	<script>
		$(function(){
			$('.sidebar-main-toggle').click();
		});
	
        function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }

		function showAPCards(supp){
			$.ajax({
				type : "POST",
				url  : "{{ url('admin/report/accounting/aging_payable/card') }}",
				data : {
					supplier_id : supp,
					filter : $('#filter').val(),
					branch : $('#branch').val(),
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				cache: false,
				success: function(data){
					var w = window.open('about:blank');
					w.document.open();
					w.document.write(data);
					w.document.close();
				}
			});
		}


		showProjectPayableTimeline(){
			
		}
	</script>