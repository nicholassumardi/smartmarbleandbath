@php
	use App\Helper\SMB;
@endphp
<style>
#chartdiv2,#chartDivFinance {
  width: 100%;
  height: 400px;
}

#chartdiv3 {
  width: 100%;
  height: 400px;
}

#chartdiv, #chartdivprofitloss {
  width: 100%;
  height: 400px;
}

.font-size-xs {
	font-size:0.6rem !important;
}
</style>
<div class="content-wrapper">
	<!-- Page header -->
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Dashboard (Under maintenance)</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
					
				</div>
			</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				
			</div>
		</div>
	</div>
	<!-- /page header -->
	<div class="content">
		<!-- Main charts -->
		<div class="row">
			
			<div class="col-xl-6 col-md-12">
				<div class="card" id="salesReport">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">{{ date('F Y', strtotime($filter)) }} Sales Report (Monthly)</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="reload" onclick="reset()" data-popup="tooltip" title="Reset Filter"></a>
								<a href="javascript:void(0);" class="list-icons-item" onclick="fullScreenGas($('#detailSo'));"><i class="icon-screen-full"></i></a>
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<div class="row">
							<div class="col-md-12">
								<form method="GET" id="form_filter">
									<center class="d-block">
										<div class="row justify-content-center">
											@csrf
											@if($salesbranch == '1')
											<div class="col-md-3">
												<div class="form-group">
													<label>Branch</label>
													<select name="branch" id="branch" class="form-control" onchange="submitFilter()">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id}}" {{$company->id == $branch ? 'selected' : ''}}>{{$company->name}}</option>
													 @endforeach
													</select>
												</div>
											</div>
											@endif
											<div class="col-md-3">
												<div class="form-group">
													<label>Month Year</label>
													<input type="month" name="filter" id="filter" class="form-control" value="{{ $filter }}" onchange="submitFilter()">
												</div>
											</div>
										</div>
									</center>
								</form>
								<div class="row justify-content-center">
									<div class="col-sm-12">
										<!-- Members online -->
										<div class="row justify-content-center">
											<div class="col-sm-6">
												<div class="card" style="background-color:#3399ff !important;">
													<div class="card-body" style="background-color:#3399ff !important;color:white;">
														<div class="d-flex">
															<h3 class="font-weight-semibold mb-0" id="nominaltotal"></h3>
															<span class="badge bg-teal-800 badge-pill align-self-center ml-auto" id="percenttotal"></span>
														</div>
														<div>
															Sales Order (SO) <br>
															Pending sales Rp {{ number_format($projectsalebefore,0,',','.') }}<br>
															This month <span id="nominaltotal1"></span><br>
															Closed SO <span id="nominaltotal2"></span>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="card" style="background-color:#009933 !important;">
													<div class="card-body" style="background-color:#009933 !important;color:white;">
														<div class="d-flex">
															<h3 class="font-weight-semibold mb-0" id="nominalpaid"></h3>
															<span class="badge bg-teal-800 badge-pill align-self-center ml-auto" id="percentpaid"></span>
														</div>
														<div>
															Done Sales <br>
															Total Sales <span id="totalsale"></span><br>
															Total Return <span id="totalsalereturn"></span><br>
															&nbsp;
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12">
												@if(session('bo_branch') == '1')
												<div class="row justify-content-center">
													<div class="col-md-3 text-right">
														<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed">
															Show Details
														</a>
													</div>
													<div class="col-md-3 text-center">
														<a class="btn btn-warning" data-toggle="collapse" href="javascript:void(0);" onclick="fullScreenGas($('#detailSo'));">
															Show Full Screen
														</a>
													</div>
													<div class="col-md-3 text-start">
														<button type="button" class="btn bg-pink-400 btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_print_report">
															<b><i class="icon-file-pdf"></i></b> Print Sales
														</button>
													</div>
												</div>
												@else
												<div class="row justify-content-center">
													<div class="col-md-4 text-right">
														<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed">
															Show Details
														</a>
													</div>
													<div class="col-md-4 text-center">
														<a class="btn btn-warning" data-toggle="collapse" href="javascript:void(0);" onclick="fullScreenGas($('#detailSo'));">
															Show Full Screen
														</a>
													</div>
												</div>
												@endif
											</div>
										</div>
										<div class="row collapse mt-3" id="collapse-link-collapsed">
											<div class="col-sm-12" id="detailSo">
												
												<h5 class="card-title">{{ date('F Y', strtotime($filter)) }} Sales Report Lists</h5>
												<div class="card">
													<div class="card-body">
														<ul class="nav nav-tabs nav-tabs-solid bg-slate border-0 nav-justified rounded">
															<li class="nav-item"><a href="#bordered-justified-tab3" class="nav-link" data-toggle="tab">Pending</a></li>
															<li class="nav-item"><a href="#bordered-justified-tab1" class="nav-link active" data-toggle="tab">SO</a></li>
															<li class="nav-item"><a href="#bordered-justified-tab2" class="nav-link" data-toggle="tab">Done</a></li>
															<li class="nav-item"><a href="#bordered-justified-tab4" class="nav-link" data-toggle="tab">Return</a></li>
														</ul>
														
														<div class="tab-content">
															
															<div class="tab-pane fade" id="bordered-justified-tab3">
																<div class="table-responsive">
																   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
																	  <thead class="bg-dark">
																		 <tr class="text-center">
																			<th>No</th>
																			<th>No. PRJ</th>
																			<th>No. SO</th>
																			<th>Date</th>
																			<th>User</th>
																			<th>Sales</th>
																			<th>Customer</th>
																			<th>Project Name</th>
																			<th>Amount (B4 Tax)</th>
																		 </tr>
																	  </thead>
																	  <tbody>
																		@php
																			$totalbefore = 0;
																			$nomor = 1;
																		@endphp
																		@foreach($datasalesbefore as $val)
																			@php
																			$adadata = false;
																			
																			if($val->sales->branch == $branch){
																				$adadata = true;
																			}
																			
																			if($adadata == true){
																				if($val->is_closed && substr($val->date_closed,0,7) <= $filter){
																					
																				}else{
																					$totalbefore += $val->total_sisa;
																				}
																			@endphp
																			<tr class="{{ $val->is_closed && substr($val->date_closed,0,7) <= $filter ? 'bg-danger' : '' }}">
																				<td>{{ $nomor }}</td>
																				<td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
																				<td>{{ $val->code }}</td>
																				<td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
																				<td>{{ $val->project->user->name }}</td>
																				<td>{{ $val->sales->name }}</td>
																				<td>{{ $val->project->customer->name }}</td>
																				<td>{{ $val->project->name }}</td>
																				<td class="text-right">
																					@php
																						echo number_format($val->total_sisa,0,'.',',');
																					@endphp
																				</td>
																			</tr>
																		@php 
																				$nomor++; 
																			}
																		@endphp
																		@endforeach
																	  </tbody>
																	  <tfoot>
																		<tr class="bg-teal">
																			<th colspan="7" class="text-right">Total</th>
																			<th colspan="2" class="text-right">{{ number_format($totalbefore,0,'.',',') }}</th>
																		</tr>
																	  </tfoot>
																   </table>
																   @php
																		/* $totaltrial = 0;
																		foreach($deliverybefore as $rowpd){
																			echo $rowpd->projectSale->code.' '.number_format($rowpd->subtotal_product + $rowpd->subtotal_service,0,',','.').'<br>';
																			$totaltrial += $rowpd->subtotal_product + $rowpd->subtotal_service;
																		}
																		echo 'TOTAL : '.number_format($totaltrial,0,'.',','); */
																   @endphp
																</div>
															</div>
														
															<div class="tab-pane fade show active" id="bordered-justified-tab1">
																<div class="table-responsive">
																   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
																	  <thead class="bg-dark">
																		 <tr class="text-center">
																			<th>No</th>
																			<th>No. PRJ</th>
																			<th>No. SO</th>
																			<th>Date</th>
																			<th>User</th>
																			<th>Sales</th>
																			<th>Customer</th>
																			<th>Project Name</th>
																			<th>Amount (B4 Tax)</th>
																		 </tr>
																	  </thead>
																	  <tbody>
																		@php
																			$total = 0;
																			$nomor = 1;
																		@endphp
																		@foreach($projectsale as $val)
																			@php
																			$adadata = false;
																			
																			if($val->sales->branch == $branch){
																				$adadata = true;
																			}
																			
																			if($adadata == true){
																				$total += str_replace(',','.',str_replace('.','',$val->getTotalRawPlusService()));
																			@endphp
																			<tr>
																				<td>{{ $nomor }}</td>
																				<td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
																				<td>{{ $val->code }}</td>
																				<td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
																				<td>{{ $val->project->user->name }}</td>
																				<td>{{ $val->sales->name }}</td>
																				<td>{{ $val->project->customer->name }}</td>
																				<td>{{ $val->project->name }}</td>
																				<td class="text-right">
																					@php
																						echo $val->getTotalRawPlusService();
																					@endphp
																				</td>
																			</tr>
																		@php 
																				$nomor++; 
																			}
																		@endphp
																		@endforeach
																	  </tbody>
																	  <tfoot>
																		<tr class="bg-teal">
																			<th colspan="7" class="text-right">Total</th>
																			<th colspan="2" class="text-right">{{ number_format($total,0,'.',',') }}</th>
																		</tr>
																	  </tfoot>
																   </table>
																</div>
															</div>

															<div class="tab-pane fade" id="bordered-justified-tab2">
																<div class="table-responsive">
																   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
																	  <thead class="bg-dark">
																		 <tr class="text-center">
																			<th>No</th>
																			<th>No. PRJ</th>
																			<th>No. SO</th>
																			<th>No. INV</th>
																			<th>Date</th>
																			<th>User</th>
																			<th>Sales</th>
																			<th>Customer</th>
																			<th>Project Name</th>
																			<th>Amount (B4 Tax)</th>
																		 </tr>
																	  </thead>
																	  <tbody>
																		@php
																			$totalpaid = 0;
																			$totalraw = 0;
																			$totalservice = 0;
																			$nomor = 1;
																			$totalallreturn = 0;
																		@endphp
																		@foreach($projectpaid as $val)
																			@php
																			
																			$adadata = false;
																			
																			if($val->projectSale->sales->branch == $branch){
																				$adadata = true;
																			}
																			
																			if($adadata == true){
																				$totalpaid += $val->subtotal_product + $val->subtotal_service;
																				$totalraw += $val->subtotal_product;
																				$totalservice += $val->subtotal_service;
																				$totalreturn = 0;
																			@endphp
																			<tr>
																				<td>{{ $nomor }}</td>
																				<td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
																				<td>{{ $val->projectSale->code }}</td>
																				<td>{{ $val->proforma_code }}</td>
																				<td>{{ date('d M Y',strtotime($val->received_date)) }}</td>
																				<td>{{ $val->user->name }}</td>
																				<td>{{ $val->projectSale->sales->name }}</td>
																				<td>{{ $val->project->customer->name }}</td>
																				<td>{{ $val->project->name }}</td>
																				<td class="text-right">
																					{{ number_format($val->subtotal_product + $val->subtotal_service,0,'.',',') }}
																				</td>
																			</tr>
																		@php 
																				$nomor++; 
																			}
																		@endphp
																		@endforeach
																	  </tbody>
																	  <tfoot>
																		<tr class="bg-teal">
																			<th colspan="8" class="text-right">Total</th>
																			<th colspan="2" class="text-right">{{ number_format($totalpaid,0,'.',',') }}</th>
																		</tr>
																	  </tfoot>
																   </table>
																</div>
															</div>
															
															<div class="tab-pane fade" id="bordered-justified-tab4">
																<div class="table-responsive">
																   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
																	  <thead class="bg-dark">
																		 <tr class="text-center">
																			<th>No</th>
																			<th>No. PJ</th>
																			<th>No. Retur</th>
																			<th>Date</th>
																			<th>Sales</th>
																			<th>Customer</th>
																			<th>Project Name</th>
																			<th>Note</th>
																			<th>Amount (B4 Tax)</th>
																		 </tr>
																	  </thead>
																	  <tbody>
																		@php
																			$nomor = 1;
																			$totalallreturn = 0;
																		@endphp
																		@foreach($projectsalereturn as $val)
																			@php
																			
																			$adadata = false;
																			
																			if($val->projectSale->sales->branch == $branch){
																				$adadata = true;
																			}
																			
																			if($adadata == true){
																				
																				if(date('Y-m-d',strtotime($val->projectSale->created_at)) < '2022-04-01'){
																					$ppnpembagi = 1.1;
																				}else{
																					$ppnpembagi = 1.11;
																				}
																				
																				$totalreturn = 0;
																				
																				if($val->project->ppn == '1'){
																					$totalreturn = $val->grandtotal / $ppnpembagi;
																					$totalallreturn += $val->grandtotal / $ppnpembagi;
																				}else{
																					$totalreturn = $val->grandtotal;
																					$totalallreturn += $val->grandtotal;
																				}
																			@endphp
																			<tr>
																				<td>{{ $nomor }}</td>
																				<td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
																				<td>{{ $val->code }}</td>
																				<td>{{ date('d M Y',strtotime($val->date_return)) }}</td>
																				<td>{{ $val->projectSale->sales->name }}</td>
																				<td>{{ $val->project->customer->name }}</td>
																				<td>{{ $val->project->name }}</td>
																				<td>{{ $val->note }}</td>
																				<td class="text-right">
																					{{ number_format($totalreturn,0,'.',',') }}
																				</td>
																			</tr>
																		@php 
																				$nomor++; 
																			}
																		@endphp
																		@endforeach
																	  </tbody>
																	  <tfoot>
																		<tr class="bg-teal">
																			<th colspan="7" class="text-right">Total</th>
																			<th colspan="2" class="text-right">{{ number_format($totalallreturn,0,'.',',') }}</th>
																		</tr>
																	  </tfoot>
																   </table>
																</div>
															</div>
															
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div id="chartdiv2"></div>
									</div>
									<div class="col-sm-6">
										<div id="chartdiv3"></div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-xl-6 col-md-12">
				<div class="card" id="doneReport">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Done Sales Target (Yearly)</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="reload" onclick="resetDoneSalesTarget()" data-popup="tooltip" title="Reset Filter"></a>
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<div class="row">
							<div class="col-md-12">
								<center class="d-block">
									<div class="row justify-content-center">
										<div class="col-md-3 text-left">
											<div class="form-group">
												<label>Year</label>
												<select name="done_year" id="done_year" class="form-control" onchange="submitDoneSalesTarget()">
													@for($i=date('Y');$i > (date('Y')-5);$i--)
														<option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
													@endfor
												</select>
											</div>
										</div>
										@if(session('bo_branch') == '1')
										<div class="col-md-3 text-left">
											<div class="form-group">
												<label>Branch</label>
												<select name="done_branch" id="done_branch" class="form-control" onchange="submitDoneSalesTarget()">
													<option value="1">PTA</option>
													<option value="2">SMB</option>
												</select>
											</div>
										</div>
										@endif
									</div>
								</center>
								<div class="card card-body">
									<div class="row text-center">
										<div class="col-4 p-3" style="background-color:#00ace6;">
											<p><i class="icon-target icon-3x d-inline-block text-white"></i></p>
											<h5 class="font-weight-semibold mb-0 text-white" id="done_budget">0</h5>
											<span class="font-size-sm text-white">Budget</span>
										</div>

										<div class="col-4 p-3" style="background-color:#cccc00;">
											<p><i class="icon-truck icon-3x d-inline-block text-white"></i></p>
											<h5 class="font-weight-semibold mb-0 text-white" id="done_sales">0</h5>
											<span class="font-size-sm text-white">Actual Done Sales</span>
											<a href="javascript:void(0);" style="color:white;position:absolute;top:10px;right:10px;z-index:999;" class="badge badge-success badge-pill" data-popup="tooltip" title="Show Details" onclick="showDetailActualDoneSales();"><i class="icon-typewriter"></i></a>
										</div>

										<div class="col-4 p-3" style="background-color:#dd99ff;">
											<p id="done_status"><i class="icon-stats-decline2 icon-3x d-inline-block text-success"></i></p>
											<h5 class="font-weight-semibold mb-0 text-white" id="done_variance">0</h5>
											<span class="font-size-sm text-white">Variance</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			@if(in_array('1',session('bo_role')))
			<div class="col-xl-6" id="widgetOwner">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Owner Dashboard</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="reload" onclick="getOwnerDashboard()" data-popup="tooltip" title="Refresh Data"></a>
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<div class="row">
							<div class="col-sm-4 col-md-4">
								<div class="card card-body" style="background-color:#0395d7 !important;color:white !important;">
									<div class="media">
										<div class="media-body">
											<h5 class="mb-0" id="totalOngoingOrder">0</h5>
											<span class="text-uppercase font-size-xs">ongoing orders</span>
											<div class="text-italic">all branch</div>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-bag icon-2x opacity-75"></i>
										</div>
									</div>
									<div class="text-center mt-2">
										&nbsp; <a href="javascript:void(0);" style="color: white !important;" onclick="showOnGoingOrder();">Details</a>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="card card-body" style="background-color:#ff1ac6 !important;color:white !important;">
									<div class="media">
										<div class="media-body">
											<h5 class="mb-0" id="totalSaleThisMonth">0</h5>
											<span class="text-uppercase font-size-xs">sales this month</span>
											<div class="text-italic">all branch</div>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-price-tag icon-2x opacity-75"></i>
										</div>
									</div>
									<div class="text-center mt-2">
										&nbsp; <a href="javascript:void(0);" style="color: white !important;" onclick="showSalesMonth();">Details</a>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="card card-body" style="background-color:#009933 !important;color:white !important;">
									<div class="media">
										<div class="media-body">
											<h5 class="mb-0" id="totalPurchaseOrder">0</h5>
											<span class="text-uppercase font-size-xs">Purchase Order</span>
											<div class="text-italic">all branch</div>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-basket icon-2x opacity-75"></i>
										</div>
									</div>
									<div class="text-center mt-2">
										&nbsp; <a href="javascript:void(0);" style="color: white !important;" onclick="showPurchaseOrder();">Details</a>
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="card card-body" style="background-color:#99994d !important;color:white !important;">
									<div class="media">
										<div class="media-body">
											<h5 class="mb-0" id="totalAvailableCash">0</h5>
											<span class="text-uppercase font-size-xs">Available Cash</span>
											<div class="text-italic font-size-xs" id="totalAvailableCashPTA">PTA</div>
											<div class="text-italic font-size-xs" id="totalAvailableCashSMB">SMB</div>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-cash2 icon-2x opacity-75"></i>
										</div>
									</div>
									{{-- <div class="text-center mt-2">
										&nbsp; <a href="javascript:void(0);" style="color: white !important;" onclick="showAvailableCash();">Details</a>
									</div> --}}
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="card card-body" style="background-color:white !important;">
									<div class="media mb-3">
										<div class="media-body">
											<h6 class="font-weight-semibold mb-0">Project Payments</h6>
											<span class="text-muted">Per {{ date('d M Y') }}</span>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-pulse2 icon-2x text-danger-400 opacity-75"></i>
										</div>
									</div>

									<div class="progress mb-2" style="height: 0.125rem;">
										<div class="progress-bar bg-success-400" style="width: 0%" id="percentPaymentProgress">
										</div>
									</div>
									
									<div>
										<span class="float-right" id="percentPayment">0%</span>
										Percent paid
									</div>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="card card-body"
									style="background-color:purple !important;color:white !important;">
									<div class="media">
										<div class="media-body">
											<h5 class="mb-0" id="totalDailyVisit">0</h5>
											<span class="text-uppercase font-size-xs">Ongoing Daily visit</span>
											<div class="text-italic">All branch</div>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-calendar52 icon-2x opacity-75"></i>
										</div>
									</div>
									<div class="text-center mt-2">
										&nbsp; <a href="javascript:void(0);" style="color: white !important;"
										data-toggle="modal" data-target="#modal_recap">Recap</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			@if(in_array('1',session('bo_role')) || in_array('4',session('bo_role')) || in_array('5',session('bo_role')))
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Profit & Loss (Under construction)</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="reload" onclick="getProfitAndLoss()"
									data-popup="tooltip" title="Refresh Data"></a>
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						{{-- <div class="row">
							<!-- <div id="chartdivprofitloss"></div> -->
						</div> --}}
						<div class="row justify-content-center">
							<div class="col-md-3">
								<div class="form-group">
									<label>Month Year</label>
									<input type="month" name="filter_profit" id="filter_profit" class="form-control"
										value="" onchange="getProfitLossDashboard()">
								</div>
							</div>
						</div>
						<div class="row mt-3" id="infoProfitLoss">
							<div class="col-sm-6 col-md-6">
								<div class="card card-body"
									style="background-color:#24d464 !important;color:white !important;">
									<div class="text-center">
										<h2 class="text-white text-center">PTA</h2>
									</div>
									<div class="media">
										<div class="media-body">
											<h5 class="text-italic" id="revenue_pta">Penjualan :</h5>
											<h5 class="text-italic" id="cogs_pta">COGS : </h5>
											<h5 class="text-italic">Gross Profit : <span
												class="badge bg-teal-800 badge-pill align-self-center ml-auto"
												id="gross_profit_pta"></span></h5>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-stats-bars2 icon-3x opacity-75"></i>
										</div>
									</div>

								</div>
							</div>
							<div class="col-sm-6 col-md-6">
								<div class="card card-body"
									style="background-color:#128c7e !important;color:white !important;">
									<div class="text-center">
										<h2 class="text-white text-center">SMB</h2>
									</div>
									<div class="media">
										<div class="media-body">
											<h5 class="text-italic" id="revenue_smb">Penjualan : </h5>
											<h5 class="text-italic" id="cogs_smb">COGS : </h5>
											<h5 class="text-italic">Gross Profit : <span
												class="badge bg-teal-800 badge-pill align-self-center ml-auto"
												id="gross_profit_smb"></span></h5>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-stats-bars2 icon-3x opacity-75"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			@if(in_array('2',session('bo_role')))
			<div class="col-xl-8">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Secretary Dashboard</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						Coming soon...!
					</div>
				</div>
			</div>
			@endif
			@if(in_array('3',session('bo_role')) || in_array('1',session('bo_role')) || in_array('4',session('bo_role')))
			<div class="col-xl-6" id="widgetFinance">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Cash & Bank In & Out (Under maintenance)</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="reload" onclick="getCashBankInOut()" data-popup="tooltip" title="Refresh Data"></a>
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<div class="row">
							<div class="col-md-12">
								<form method="GET" id="form_filter">
									<center class="d-block">
										<div class="row justify-content-center">
											<div class="col-md-3">
												<div class="form-group">
													<label>Mode In</label>
													<select name="finance_mode_in" id="finance_mode_in" class="form-control" onchange="getCashBankInOut()">
														<option value="">All</option>
														<option value="1">Customer</option>
														<option value="2">Customer & Interest</option>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Mode Out</label>
													<select name="finance_mode_out" id="finance_mode_out" class="form-control" onchange="getCashBankInOut()">
														<option value="">All</option>
														<option value="1">Purchase Request</option>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Branch</label>
													<select name="finance_branch" id="finance_branch" class="form-control" onchange="getCashBankInOut()">
														<option value="">All</option>
														<option value="1">PTA</option>
														<option value="2">SMB</option>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>From Month</label>
													<input type="month" name="finance_from_month" id="finance_from_month" class="form-control" onchange="getCashBankInOut()" value="{{ date('Y-m') }}">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>To Month</label>
													<input type="month" name="finance_to_month" id="finance_to_month" class="form-control" onchange="getCashBankInOut()" value="{{ date('Y-m') }}">
												</div>
											</div>
										</div>
									</center>
								</form>
								<div id="chartDivFinance"></div>
								<div class="row justify-content-center mb-2">
									<div class="col-md-3">
										<a class="btn btn-secondary" href="javascript:void(0);" onclick="showDetailCashBank();">Show Details</a>
									</div>
								</div>
							</div>
							<!-- <div class="col-md-12">
								<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
									<span class="font-weight-semibold">Info!</span> 
									In data are taken from projects payment. Out data are taken from purchase request payments.
								</div>
							</div> -->
						</div>
					</div>
				</div>
			</div>
			@endif
			@if(in_array('4',session('bo_role')))
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Accounting Dashboard</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<div class="col-sm-4 col-md-4">
							<div class="card card-body"
								style="background-color:purple !important;color:white !important;">
								<div class="media">
									<div class="media-body">
										<h5 class="mb-0" id="totalDailyVisit">0</h5>
										<span class="text-uppercase font-size-xs">Ongoing Daily visit</span>
										<div class="text-italic">All branch</div>
									</div>

									<div class="ml-3 align-self-center">
										<i class="icon-calendar52 icon-2x opacity-75"></i>
									</div>
								</div>
								<div class="text-center mt-2">
									&nbsp; <a href="javascript:void(0);" style="color: white !important;"
									data-toggle="modal" data-target="#modal_recap">Recap</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			@if(in_array('5',session('bo_role')))
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Sales & Marketing Manager Dashboard</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<div class="col-sm-4 col-md-4">
							<div class="card card-body"
								style="background-color:purple !important;color:white !important;">
								<div class="media">
									<div class="media-body">
										<h5 class="mb-0" id="totalDailyVisit">0</h5>
										<span class="text-uppercase font-size-xs">Ongoing Daily visit</span>
										<div class="text-italic">All branch</div>
									</div>

									<div class="ml-3 align-self-center">
										<i class="icon-calendar52 icon-2x opacity-75"></i>
									</div>
								</div>
								<div class="text-center mt-2">
									&nbsp; <a href="javascript:void(0);" style="color: white !important;"
									data-toggle="modal" data-target="#modal_recap">Recap</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			@if(in_array('6',session('bo_role')))
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Sales Project Dashboard</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0">
						Coming soon...
					</div>
				</div>
			</div>
			@endif
			@if(in_array('7',session('bo_role')))
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Administration Dashboard</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0">
						Coming soon...!
					</div>
				</div>
			</div>
			@endif
			@if(in_array('9',session('bo_role')))
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Purchasing Dashboard</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0">
						Coming soon...!
					</div>
				</div>
			</div>
			@endif
			@if(in_array('10',session('bo_role')))
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Admin Sales & Stock Dashboard</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0">
						Coming soon...!
					</div>
				</div>
			</div>
			@endif
			@if(in_array('11',session('bo_role')))
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">AR & Delivery Dashboard</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0">
						Coming soon...!
					</div>
				</div>
			</div>
			@endif
			<div class="col-xl-6">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">{{ $year }} Sales Report {{ $branch == '1' ? 'PTA' : 'SMB' }} (Yearly)</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="reload" onclick="reset()" data-popup="tooltip" title="Reset Filter"></a>
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<div class="row">
							<div class="col-md-12">
								<form method="GET" id="form_filter_year">
									<center class="d-block">
										<div class="row justify-content-center">
											@csrf
											@if($salesbranch == '1')
											<div class="col-md-3 text-left">
												<div class="form-group">
													<label>Year</label>
													<select name="year" id="year" class="form-control" onchange="submitFilterYear()">
														@for($i=date('Y');$i > (date('Y')-5);$i--)
															<option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
														@endfor
													</select>
												</div>
											</div>
											<div class="col-md-3 text-left">
												<div class="form-group">
													<label>Branch</label>
													<select name="branch" id="branch" class="form-control" onchange="submitFilterYear()">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id}}" {{$company->id == $branch ? 'selected' : ''}}>{{$company->name}}</option>
													 @endforeach
													</select>
												</div>
											</div>
											@endif
										</div>
									</center>
								</form>
								<div id="chartdiv"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_full_screen" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Full Screen Mode</h5>
				<button type="button" class="close closeFullScreen" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" id="fullScreenDiv">
				
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i> Red rows mean that the SO were closed by Sales Person.
				</div>
				<button type="button" class="btn bg-secondary closeFullScreen" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_cash_bank_details" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Modal Cash & Bank Details</h5>
				<button type="button" class="close closeFullScreen" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" id="resultCashBank">
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_information" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" id="informationDiv">
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_recap" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Daily Visit Recap</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body content-calendar w-100">
						<div class="calendar-container">
						  <div class="calendar"> 
							<div class="year-header"> 
							  <span class="left-button fa fa-chevron-left" id="prev"> </span> 
							  <span class="year" id="label"></span> 
							  <span class="right-button fa fa-chevron-right" id="next"> </span>
							</div> 
							<table class="months-table w-100"> 
							  <tbody>
								<tr class="months-row">
								  <td class="month">Jan</td> 
								  <td class="month">Feb</td> 
								  <td class="month">Mar</td> 
								  <td class="month">Apr</td> 
								  <td class="month">May</td> 
								  <td class="month">Jun</td> 
								  <td class="month">Jul</td>
								  <td class="month">Aug</td> 
								  <td class="month">Sep</td> 
								  <td class="month">Oct</td>          
								  <td class="month">Nov</td>
								  <td class="month">Dec</td>
								</tr>
							  </tbody>
							</table> 
							
							<table class="days-table w-100"> 
							  <td class="day">Sun</td> 
							  <td class="day">Mon</td> 
							  <td class="day">Tue</td> 
							  <td class="day">Wed</td> 
							  <td class="day">Thu</td> 
							  <td class="day">Fri</td> 
							  <td class="day">Sat</td>
							</table> 
							<div class="frame"> 
							  <table class="dates-table w-100"> 
							  <tbody class="tbody">             
							  </tbody> 
							  </table>
							</div> 
							<!-- <button class="button" id="add-button">Add Event</button> -->
						  </div>
						</div>
						<div class="events-container">
						</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
				</div>
			</div>
		</div>
	</div>

	
	<div class="modal fade" id="modal_print_report" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-lg">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Report Sales</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form_report_sales">
						<div class="row justify-content-center">
							<div class="col-md-6">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Mode</label>
									<select name="mode" id="mode" class="form-control"
										onchange="changeMode(this.value)">
										<option value="1">Month</option>
										<option value="2">Date</option>
									</select>
								</div>
							</div>
							<div class="col-md-6 mode1">
								<label style="margin-bottom: 0rem;">Month</label>
								<input type="month" name="filter_month" id="filter_month" class="form-control"
									value="{{date('Y-m')}}">
							</div>
							<div class="col-md-6 mode2 d-none">
								<label style="margin-bottom: 0rem;">Date</label>
								<div class="input-group">
									<input type="date" name="start_date" id="start_date" class="form-control">
									<div class="input-group-prepend">
										<span class="input-group-text">To</span>
									</div>
									<input type="date" name="finish_date" id="finish_date" class="form-control">
								</div>
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col-md-6">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Sales</label>
									<select name="sales_id" id="sales_id"></select>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Customer</label>
									<select name="customer_id" id="customer_id"></select>
									</select>
								</div>
							</div>
						</div>
						<div class="row justify-content-center text-center">
							<div class="col-md-3">
								<div class="form-group">
									<button type="button" class="btn bg-teal-400 btn-block btn-labeled btn-labeled-left"
										data-toggle="modal" data-target="#modal_print_report"
										onclick="printReportSalesMonth()">
										<b><i class="icon-file-pdf"></i></b> Print Report
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="{{ asset('template/back-office/calendarjs/css/style.css') }}" rel="stylesheet">
	<script src="{{ asset('template/back-office/calendarjs/js/popper.js')}}"></script>
	<script src="{{ asset('template/back-office/calendarjs/js/main.js')}}"></script>
	<script>
		$(function() {
			select2ServerSide('#sales_id', '{{ url("admin/select2/user") }}');
			select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
            showAvailableCash()
			$('.btn-link').on('click', function() {
				cekNotif();
			});
			
			$("[opacity='0.3']").hide();
			
			$('.sidebar-main-toggle').click();
			
			am4core.options.autoDispose = true;
			
			submitDoneSalesTarget();
			getProjectTrip();
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
		
		
	function getProjectTrip(){
		$.ajax({
			url: '{{ url("admin/sales/project/get_project_trip") }}',
			type: 'GET',
			dataType: 'JSON',
			beforeSend: function() {
			loadingOpen('#modal_form');
			},
			success: function(response) {
			loadingClose('#modal_form');
			if(response){
				if(response.data.length > 0){
					$.each(response.data, function (i, val) { 
						var date = new Date(val.date);
						new_event_json(val.name, 1, date, val.day, val.note, val.proof, val.id);

						if(val.progress < 37 ){
							$("#totalDailyVisit").text(response.data.length);
							new_unfinished_event_json(val.name, date, val.day, val.note, val.proof, val.id);
						}
					});
				}
			}
			},
			error: function() {
			loadingClose('#modal_form');
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
		
		function resetDoneSalesTarget(){
			$('#done_year').val('{{ date("Y") }}');
			submitDoneSalesTarget();
		}
		
		function getToDoList(){
			$('#modal_todo').modal('toggle');
			$.ajax({
				url: '{{ url("admin/dashboard/get_todo") }}',
				type: 'GET',
				dataType: 'JSON',
				 data: {
					
				 },
				 beforeSend: function() {
					loadingOpen('#todo-body');
				 },
				 success: function(response) {
					loadingClose('#todo-body');
					if(response){
						
					}
				 },
				 error: function() {
					loadingClose('#todo-body');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				 }
			});
			
		}

		function reset(){
			window.location.href = "{{ URL::current() }}";
		}

		function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }
		
		function submitFilterYear() {
            loadingOpen('.content');
            $('#form_filter_year').submit();
        }
		
		am4core.ready(function() {

			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("chartdiv2", am4charts.RadarChart);

			chart.data = [{
			  "category": "Done",
			  "value": {{ $totalpaid }},
			  "full": {{ $budget }},
			  "config": { "fill": "#009933" }
			},{
			  "category": "Target",
			  "value": {{ $total }},
			  "full": {{ $budget }},
			  "config": { "fill": "#3399ff" }
			}];

			chart.startAngle = -90;
			chart.endAngle = 240;
			chart.fontSize = 12;
			chart.innerRadius = am4core.percent(20);

			chart.numberFormatter.numberFormat = "'Rp'#,###";

			var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "category";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.grid.template.strokeOpacity = 0;
			categoryAxis.renderer.labels.template.horizontalCenter = "right";
			categoryAxis.renderer.labels.template.fontWeight = 500;
			categoryAxis.renderer.labels.template.adapter.add("fill", function(fill, target) {
			  return (target.dataItem.index >= 0) ? chart.colors.getIndex(target.dataItem.index) : fill;
			});
			categoryAxis.renderer.minGridDistance = 10;

			var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
			valueAxis.renderer.grid.template.strokeOpacity = 0;
			valueAxis.min = 0;
			valueAxis.max = {{ $budget }};
			valueAxis.strictMinMax = true;

			var series1 = chart.series.push(new am4charts.RadarColumnSeries());
			series1.dataFields.valueX = "full";
			series1.dataFields.categoryY = "category";
			series1.clustered = false;
			series1.columns.template.fill = new am4core.InterfaceColorSet().getFor("alternativeBackground");
			series1.columns.template.fillOpacity = 0.08;
			series1.columns.template.cornerRadiusTopLeft = 20;
			series1.columns.template.strokeWidth = 0;
			series1.columns.template.radarColumn.cornerRadius = 0;

			var series2 = chart.series.push(new am4charts.RadarColumnSeries());
			series2.dataFields.valueX = "value";
			series2.dataFields.categoryY = "category";
			series2.clustered = false;
			series2.columns.template.strokeWidth = 0;
			series2.columns.template.tooltipText = "{category}: [bold]{value}[/]";
			series2.columns.template.radarColumn.cornerRadius = 0;
			series2.columns.template.radarColumn.configField = 'config';

			chart.cursor = new am4charts.RadarCursor();
			
			var chart1 = am4core.create("chartdiv", am4charts.XYChart);

			chart1.data = [{
			  "year": '{{ $filter }}',
			  "Products": {{ round($totalraw,0) }},
			  "Services": {{ round($totalservice,0) }}
			}];
			
			var categoryAxis = chart1.yAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "year";
			categoryAxis.numberFormatter.numberFormat = "'Rp'#,###";
			categoryAxis.renderer.inversed = true;
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.cellStartLocation = 0.1;
			categoryAxis.renderer.cellEndLocation = 0.9;

			var  valueAxis = chart1.xAxes.push(new am4charts.ValueAxis()); 
			valueAxis.renderer.opposite = true;

			function createSeries(field, name) {
			  var series = chart1.series.push(new am4charts.ColumnSeries());
			  series.dataFields.valueX = field;
			  series.dataFields.categoryY = "year";
			  series.name = name;
			  series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
			  series.columns.template.height = am4core.percent(100);
			  series.sequencedInterpolation = true;

			  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
			  valueLabel.label.text = "{valueX}";
			  valueLabel.label.horizontalCenter = "left";
			  valueLabel.label.dx = 10;
			  valueLabel.label.hideOversized = false;
			  valueLabel.label.truncate = false;

			  var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
			  categoryLabel.label.text = "{name}";
			  categoryLabel.label.horizontalCenter = "right";
			  categoryLabel.label.dx = -10;
			  categoryLabel.label.fill = am4core.color("#fff");
			  categoryLabel.label.hideOversized = false;
			  categoryLabel.label.truncate = false;
			}
			
			var title1 = chart1.titles.create();
			title1.text = "Sales by Products & Services";
			title1.fontSize = 20;
			title1.marginBottom = 30;

			createSeries("Products", "Products");
			createSeries("Services", "Services");

		});
		
		am4core.ready(function() {

			am4core.useTheme(am4themes_animated);

			var chart4 = am4core.create("chartdiv", am4charts.XYChart);
			
			chart4.legend = new am4charts.Legend();
			
			var data = [
			@php
				foreach($budgetYear as $row){
			@endphp
				{
				  "month": "{{ date('M',strtotime($row['month'])) }}",
				  "sales": {{ $row['totalsale'] }},
				  "done": {{ $row['totaldelivery'] }},
				  "target": {{ $row['totalbudget'] }}
				},
			@php
				}
			@endphp
			];

			var categoryAxis = chart4.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "month";
			categoryAxis.renderer.grid.template.disabled = true;
			categoryAxis.renderer.cellStartLocation = 0.1;
			categoryAxis.renderer.cellEndLocation = 0.9;
			categoryAxis.renderer.minGridDistance = 30;

			var valueAxis = chart4.yAxes.push(new am4charts.ValueAxis());
			valueAxis.renderer.grid.template.disabled = true;

			var columnSeries = chart4.series.push(new am4charts.ColumnSeries());
			columnSeries.name = "Sales ";
			columnSeries.dataFields.valueY = "sales";
			columnSeries.dataFields.categoryX = "month";

			columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
			columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
			columnSeries.columns.template.propertyFields.stroke = "stroke";
			columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
			columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
			columnSeries.tooltip.label.textAlign = "middle";
			columnSeries.fill = am4core.color("#3399ff");
			columnSeries.columns.template.width = am4core.percent(100);
			
			var columnSeries1 = chart4.series.push(new am4charts.ColumnSeries());
			columnSeries1.name = "Done ";
			columnSeries1.dataFields.valueY = "done";
			columnSeries1.dataFields.categoryX = "month";

			columnSeries1.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
			columnSeries1.columns.template.propertyFields.fillOpacity = "fillOpacity";
			columnSeries1.columns.template.propertyFields.stroke = "stroke";
			columnSeries1.columns.template.propertyFields.strokeWidth = "strokeWidth";
			columnSeries1.columns.template.propertyFields.strokeDasharray = "columnDash";
			columnSeries1.tooltip.label.textAlign = "middle";
			columnSeries1.fill = am4core.color("#00e600");
			columnSeries1.columns.template.width = am4core.percent(100);

			var lineSeries = chart4.series.push(new am4charts.LineSeries());
			lineSeries.name = "Target ";
			lineSeries.dataFields.valueY = "target";
			lineSeries.dataFields.categoryX = "month";

			lineSeries.stroke = am4core.color("#fdd400");
			lineSeries.strokeWidth = 3;
			lineSeries.propertyFields.strokeDasharray = "lineDash";
			lineSeries.tooltip.label.textAlign = "middle";

			var bullet = lineSeries.bullets.push(new am4charts.Bullet());
			bullet.fill = am4core.color("#fdd400");
			bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
			var circle = bullet.createChild(am4core.Circle);
			circle.radius = 4;
			circle.fill = am4core.color("#fff");
			circle.strokeWidth = 3;

			chart4.data = data;

		});
		
		am4core.ready(function() {

			am4core.useTheme(am4themes_animated);

			var chart3 = am4core.create("chartdiv3", am4charts.XYChart3D);

			chart3.data = [{
				"country": "Sales & Target",
				"salestotal": {{ $projectsalebefore + $total }},
				"targetbudget": {{ $budgetYearRemaining }}
			}];

			var categoryAxis3 = chart3.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis3.dataFields.category = "country";
			categoryAxis3.renderer.grid.template.location = 0;
			categoryAxis3.renderer.minGridDistance = 30;

			var valueAxis3 = chart3.yAxes.push(new am4charts.ValueAxis());
			valueAxis3.title.text = "Remaining Budgeting Target";
			valueAxis3.renderer.labels.template.adapter.add("text", function(text) {
			  return "Rp " + text;
			});

			var series3 = chart3.series.push(new am4charts.ColumnSeries3D());
			series3.dataFields.valueY = "salestotal";
			series3.dataFields.categoryX = "country";
			series3.name = "Sales";
			series3.clustered = false;
			series3.columns.template.tooltipText = "Sales: Rp [bold]{valueY}[/]";
			series3.columns.template.fillOpacity = 0.9;
			series3.fill = am4core.color("#009933");

			var series4 = chart3.series.push(new am4charts.ColumnSeries3D());
			series4.dataFields.valueY = "targetbudget";
			series4.dataFields.categoryX = "country";
			series4.name = "Target";
			series4.clustered = false;
			series4.columns.template.tooltipText = "Target: Rp [bold]{valueY}[/]";
			series4.fill = am4core.color("#fdd400");
			
			var title3 = chart3.titles.create();
			title3.text = "Period {{ date('F Y', strtotime($filter)) }} Until December {{ date('Y') }}";
			title3.fontSize = 20;
			title3.marginBottom = 30;

		});
		
		$('#nominaltotal').html('Rp {{ number_format($projectsalebefore + $total - $projectclosedbefore,0,',','.') }}');
		$('#nominaltotal1').html('Rp {{ number_format($total,0,',','.') }}');
		$('#nominaltotal2').html('Rp -{{ number_format($projectclosedbefore,0,',','.') }}');
		$('#percenttotal').html('{{ $budget !== 0 ? round((($total + $projectsalebefore - $projectclosedbefore)/$budget) * 100,2) : 0 }}%');
		$('#nominalpaid').html('Rp {{ number_format($totalpaid - $totalallreturn,0,',','.') }}');
		$('#percentpaid').html('{{ $budget !== 0 ? round((($totalpaid - $totalallreturn)/$budget) * 100,2) : 0 }}%');
		$('#nominalbefore').html('Rp {{ number_format($projectsalebefore,0,',','.') }}');
		$('#totalsale').html('Rp {{ number_format($totalpaid,0,',','.') }}');
		$('#totalsalereturn').html('Rp {{ number_format($totalallreturn,0,',','.') }}');
		
		$(function() {
			@if(in_array('1',session('bo_role')))
				getOwnerDashboard();
				getProfitAndLoss();
			@endif
			
			@if(in_array('1',session('bo_role')) || in_array('5',session('bo_role')))
				getProfitLossDashboard()
			@endif
			
			@if(in_array('3',session('bo_role')) || in_array('1',session('bo_role')) || in_array('4',session('bo_role')))
				getCashBankInOut();
			@endif
			
			window.onfocus = function () {
				@if(in_array('1',session('bo_role')))
					getOwnerDashboard();
				@endif
				@if(in_array('1',session('bo_role')) || in_array('5',session('bo_role')))
					getProfitLossDashboard()
				@endif
				@if(in_array('3',session('bo_role')) || in_array('1',session('bo_role')))
					getCashBankInOut();
				@endif
				submitDoneSalesTarget();
			};
			
			$('#modal_information').on('hidden.bs.modal', function (e) {
				$('#informationDiv').html('');
			});
			
			$('#modal_cash_bank_details').on('hidden.bs.modal', function (e){
				$('#resultCashBank').html('');
			});
		});
		
		@if(in_array('1',session('bo_role')))
			function getOwnerDashboard(){
				$('#percentPaymentProgress').width('0%');
				$('#percentPayment').text('0%');
				$.ajax({
					url: '{{ url("admin/dashboard/get_dashboard_data") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						param : '1'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('#widgetOwner');
					},
					success: function(response) {
						loadingClose('#widgetOwner');
						$('#totalOngoingOrder').text(response.totalOngoingOrder);
						$('#totalSaleThisMonth').text(response.totalSaleThisMonth);
						$('#totalPurchaseOrder').text(response.totalPurchaseOrder);
						$('#totalAvailableCash').text(response.totalAvailableCash);
						$('#percentPaymentProgress').width(response.percentPay + '%');
						$('#percentPayment').text(response.percentPay + '%');
					},
					error: function() {
						loadingClose('#widgetOwner');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
			}
			
			function showOnGoingOrder(){
				$.ajax({
					url: '{{ url("admin/dashboard/get_dashboard_detail") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						param : 'ongoingorder'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('#informationDiv');
					},
					success: function(response) {
						loadingClose('#informationDiv');
						$('#informationDiv').html(response);
						$('#modal_information').modal('toggle');
					},
					error: function() {
						loadingClose('#informationDiv');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
			}
			
			function showSalesMonth(){
				$.ajax({
					url: '{{ url("admin/dashboard/get_dashboard_detail") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						param : 'salesthismonth'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('#informationDiv');
					},
					success: function(response) {
						loadingClose('#informationDiv');
						$('#informationDiv').html(response);
						$('#modal_information').modal('toggle');
					},
					error: function() {
						loadingClose('#informationDiv');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
			}
			
			function showPurchaseOrder(){
				$.ajax({
					url: '{{ url("admin/dashboard/get_dashboard_detail") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						param : 'purchaseorder'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('#informationDiv');
					},
					success: function(response) {
						loadingClose('#informationDiv');
						$('#informationDiv').html(response);
						$('#modal_information').modal('toggle');
					},
					error: function() {
						loadingClose('#informationDiv');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
			}
			
			function showAvailableCash(){
				$.ajax({
					url: '{{ url("admin/dashboard/get_dashboard_detail") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						param : 'availablecash'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('#informationDiv');
					},
					success: function(response) {
						// loadingClose('#informationDiv');
						// $('#informationDiv').html(response);
						// $('#modal_information').modal('toggle');
                        $('#totalAvailableCashPTA').text(response.availableCashPTA);
						$('#totalAvailableCashSMB').text(response.availableCashSMB);
					},
					error: function() {
						loadingClose('#informationDiv');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
			}
			
			
			
			function getProfitAndLoss(){
				/*am4core.ready(function() {

					am4core.useTheme(am4themes_animated);

					var chart5 = am4core.create("chartdivprofitloss", am4charts.XYChart);
					
					chart5.legend = new am4charts.Legend();
					
					var data = [
					@php
						foreach($budgetYear as $row){
					@endphp
						{
						  "month": "{{ date('M',strtotime($row['month'])) }}",
						  "sales": {{ $row['totalsale'] }},
						  "done": {{ $row['totaldelivery'] }},
						},
					@php
						}
					@endphp
					];

					var categoryAxis = chart5.xAxes.push(new am4charts.CategoryAxis());
					categoryAxis.dataFields.category = "month";
					categoryAxis.renderer.grid.template.disabled = true;
					categoryAxis.renderer.cellStartLocation = 0.1;
					categoryAxis.renderer.cellEndLocation = 0.9;
					categoryAxis.renderer.minGridDistance = 30;

					var valueAxis = chart5.yAxes.push(new am4charts.ValueAxis());
					valueAxis.renderer.grid.template.disabled = true;

					var columnSeries = chart5.series.push(new am4charts.ColumnSeries());
					columnSeries.name = "Sales ";
					columnSeries.dataFields.valueY = "sales";
					columnSeries.dataFields.categoryX = "month";

					columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
					columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
					columnSeries.columns.template.propertyFields.stroke = "stroke";
					columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
					columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
					columnSeries.tooltip.label.textAlign = "middle";
					columnSeries.fill = am4core.color("#3399ff");
					columnSeries.columns.template.width = am4core.percent(100);
					
					var columnSeries1 = chart5.series.push(new am4charts.ColumnSeries());
					columnSeries1.name = "Done ";
					columnSeries1.dataFields.valueY = "done";
					columnSeries1.dataFields.categoryX = "month";

					columnSeries1.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
					columnSeries1.columns.template.propertyFields.fillOpacity = "fillOpacity";
					columnSeries1.columns.template.propertyFields.stroke = "stroke";
					columnSeries1.columns.template.propertyFields.strokeWidth = "strokeWidth";
					columnSeries1.columns.template.propertyFields.strokeDasharray = "columnDash";
					columnSeries1.tooltip.label.textAlign = "middle";
					columnSeries1.fill = am4core.color("#00e600");
					columnSeries1.columns.template.width = am4core.percent(100);

					chart5.data = data;

				}); */
			}
		@endif
		
		@if(in_array('3',session('bo_role')) || in_array('1',session('bo_role')) || in_array('4',session('bo_role')))
			var chartFinance;
		
			function getCashBankInOut(){
				$.ajax({
					url: '{{ url("admin/dashboard/get_dashboard_data") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						branch : $('#finance_branch').val(),
						from_month : $('#finance_from_month').val(),
						to_month : $('#finance_to_month').val(),
						mode_in : $('#finance_mode_in').val(),
						mode_out : $('#finance_mode_out').val(),
						param : '3'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('#widgetFinance');
					},
					success: function(response) {
						loadingClose('#widgetFinance');
						
						am4core.ready(function() {

							am4core.useTheme(am4themes_animated);

							chartFinance = am4core.create("chartDivFinance", am4charts.XYChart);

							chartFinance.colors.step = 2;

							chartFinance.data = generateChartData(response);

							var dateAxis = chartFinance.xAxes.push(new am4charts.DateAxis());
							dateAxis.renderer.minGridDistance = 50;

							createAxisAndSeries("valueIn", "In", true, "triangle");
							createAxisAndSeries("valueOut", "Out", true, "rectangle");

							chartFinance.legend = new am4charts.Legend();

							chartFinance.cursor = new am4charts.XYCursor();

						});
					},
					error: function() {
						loadingClose('#widgetFinance');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
			}
			
			function showDetailCashBank(){
				$('#resultCashBank').html('');
				$.ajax({
					url: '{{ url("admin/dashboard/get_dashboard_detail") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						param : 'cashbank',
						branch : $('#finance_branch').val(),
						from_month : $('#finance_from_month').val(),
						to_month : $('#finance_to_month').val(),
						mode_in : $('#finance_mode_in').val(),
						mode_out : $('#finance_mode_out').val(),
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('.modal-content');
					},
					success: function(response) {
						loadingClose('.modal-content');
						$('#resultCashBank').html(response);
						$('#modal_cash_bank_details').modal('toggle');
					},
					error: function() {
						loadingClose('#informationDiv');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
			}
			
			function createAxisAndSeries(field, name, opposite, bullet) {
			  var valueAxis = chartFinance.yAxes.push(new am4charts.ValueAxis());
			  if(chartFinance.yAxes.indexOf(valueAxis) != 0){
				valueAxis.syncWithAxis = chartFinance.yAxes.getIndex(0);
			  }
			  
			  var series = chartFinance.series.push(new am4charts.LineSeries());
			  series.dataFields.valueY = field;
			  series.dataFields.dateX = "month";
			  series.strokeWidth = 2;
			  series.yAxis = valueAxis;
			  series.name = name;
			  series.tooltipText = "{name}: [bold]{valueY}[/]";
			  series.tensionX = 0.8;
			  series.showOnInit = true;
			  
			  var interfaceColors = new am4core.InterfaceColorSet();
			  
			  switch(bullet) {
				case "triangle":
				  var bullet = series.bullets.push(new am4charts.Bullet());
				  bullet.width = 12;
				  bullet.height = 12;
				  bullet.horizontalCenter = "middle";
				  bullet.verticalCenter = "middle";
				  
				  var triangle = bullet.createChild(am4core.Triangle);
				  triangle.stroke = interfaceColors.getFor("background");
				  triangle.strokeWidth = 2;
				  triangle.direction = "top";
				  triangle.width = 12;
				  triangle.height = 12;
				  series.stroke = am4core.color("#009900");
				  
				  break;
				case "rectangle":
				  var bullet = series.bullets.push(new am4charts.Bullet());
				  bullet.width = 10;
				  bullet.height = 10;
				  bullet.horizontalCenter = "middle";
				  bullet.verticalCenter = "middle";
				  
				  var rectangle = bullet.createChild(am4core.Rectangle);
				  rectangle.stroke = interfaceColors.getFor("background");
				  rectangle.strokeWidth = 2;
				  rectangle.width = 10;
				  rectangle.height = 10;
				  series.stroke = am4core.color("#e62e00");
				  
				  break;
				default:
				  var bullet = series.bullets.push(new am4charts.CircleBullet());
				  bullet.circle.stroke = interfaceColors.getFor("background");
				  bullet.circle.strokeWidth = 2;
				  break;
			  }
			  
			  valueAxis.renderer.line.strokeOpacity = 1;
			  valueAxis.renderer.line.strokeWidth = 2;
			  valueAxis.renderer.line.stroke = series.stroke;
			  valueAxis.renderer.labels.template.fill = series.stroke;
			  valueAxis.renderer.opposite = opposite;
			}
			
			function generateChartData(response) {
			  var chartData = [];

			  $.each(response.data, function(i, val) {
				chartData.push({
					month:val.monthid, valueIn: val.total, valueOut: val.totalout
				});
			  });
			  
			  return chartData;
			}
		@endif
		
		function fullScreenGas(elementChild){
			$('#fullScreenDiv').html($(elementChild).html());
			$(elementChild).html('');
			$('#modal_full_screen').modal('toggle');
			$('.closeFullScreen').attr("onclick","fullScreenClose($('#" + elementChild.attr('id') + "'))");
		}
		
		function fullScreenClose(elementChild){
			$(elementChild).html($('#fullScreenDiv').html());
			$('#fullScreenDiv').html('');
		}
		
		function submitDoneSalesTarget(){
			$.ajax({
				url: '{{ url("admin/dashboard/get_dashboard_done_sales") }}',
				type: 'POST',
				dataType: 'JSON',
				data: {
					year : $('#done_year').val(),
					branch : $('#done_branch').length > 0 ? $('#done_branch').val() : '2'
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#doneReport');
					$('#done_budget').text('0');
					$('#done_sales').text('0');
					$('#done_variance').text('0');
				},
				success: function(response) {
					loadingClose('#doneReport');
					$('#done_budget').text(response.budget);
					$('#done_sales').text(response.sales);
					$('#done_variance').text(response.variance);
					if(response.status == '-'){
						$('#done_status').html(`<i class="icon-stats-decline2 icon-3x d-inline-block text-danger"></i>`);
					}else{
						$('#done_status').html(`<i class="icon-stats-growth2 icon-3x d-inline-block text-success"></i>`);
					}
				},
				error: function() {
					loadingClose('#doneReport');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}
		
		function showDetailActualDoneSales(){
			$.ajax({
				url: '{{ url("admin/dashboard/get_dashboard_detail") }}',
				type: 'POST',
				dataType: 'JSON',
				data: {
					param : 'actualdonesales',
					year : $('#done_year').val(),
					branch : $('#done_branch').val()
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#informationDiv');
				},
				success: function(response) {
					loadingClose('#informationDiv');
					$('#informationDiv').html(response);
					$('#modal_information').modal('toggle');
				},
				error: function() {
					loadingClose('#informationDiv');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}
		
		function getProfitLossDashboard(){
			$.ajax({
				url: '{{ url("admin/dashboard/get_dashboard_profit_loss") }}',
				type: 'POST',
				dataType: 'JSON',
				data: {
					filter : $('#filter_profit').val(),
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#infoProfitLoss');
				},
				success: function(response) {
					loadingClose('#infoProfitLoss');
					$('#revenue_pta').text(response.total_revenue_pta);
					$('#cogs_pta').text(response.total_cogs_pta);
					$('#revenue_smb').text(response.total_revenue_smb);
					$('#cogs_smb').text(response.total_cogs_smb);
					$('#filter_profit').val(response.filter);
					$('#gross_profit_pta').text(response.gross_profit_pta);
					$('#gross_profit_smb').text(response.gross_profit_smb);
					
				},
				error: function() {
					loadingClose('#infoProfitLoss');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}
		
		
		function printReportSalesMonth(){
		$.ajax({
			type : "GET",
			url  : "{{ url('admin/dashboard/print_sales_report') }}",
			data : {
				filter : $("#filter").val(),
				branch : $("#branch").val(),
				mode : $("#mode").val(),
				start_date : $("#start_date").val(),
				finish_date : $("#finish_date").val(),
				customer_id : $("#customer_id").val(),
				sales_id : $("#sales_id").val(),
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
			},
			error: function() {
			swalInit.fire({
				title: 'Ups, check your internet connection!',
				text: 'Ups. Sorry error.',
				type: 'error'
			});
			}
		});
	}

	function changeMode(val){
		if(val == '1'){
			$('.mode1').removeClass('d-none');
			$('#finish_date').val('');
			$('#start_date').val('');
			$('.mode2').addClass('d-none');
		}else if(val == '2'){
			$('.mode2').removeClass('d-none');
			$('#filter').val('');
			$('.mode1').addClass('d-none');
		}
	}

	$('#modal_print_report').on('hidden.bs.modal', function (e) {
		$("#form_report_sales").trigger('reset');
		$('#sales_id').val(null).trigger('change.select2');
		$('#customer_id').val(null).trigger('change.select2');
	})
	</script>