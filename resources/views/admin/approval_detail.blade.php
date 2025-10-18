@php
	use App\Models\CashBank;
	use App\Models\Coa;
@endphp
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">{{ $title }}</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/approval') }}" class="btn bg-secondary btn-labeled btn-labeled-left"><b><i class="icon-arrow-left7"></i></b> Back To All</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="{{ url('admin/approval') }}" class="breadcrumb-item">Approval</a>
					<span class="breadcrumb-item active">Detail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				@if($param == 'budgeting_projects')
					
				@else
					<h5 class="card-title">{{ $subtitle }}</h5>
				@endif
				<a href="javascript:void(0);" class="btn bg-primary btn-labeled btn-labeled-left float-right" style="position:absolute;right:15px;top:15px;" onclick="history.back()"><b><i class="icon-arrow-left7"></i></b> Back</a>
				<div class="header-elements">
				</div>
			</div>
			<div class="card-body pt-3">
				@if($param == 'budgeting_projects')
					
				@else
					@if($approval->approved_by)
						@if($approval->status == '1')
							<span class="badge badge-success rounded-0 mb-3">Approved by : {{ $approval->approvedBy->name }}</span> 
						@else
							<span class="badge badge-danger rounded-0 mb-3">Rejected by : {{ $approval->approvedBy->name }}</span> 
						@endif
						
					@else
							<button onclick="goApprove({{ $id_approval }})" class="btn bg-success btn-sm mb-3"><i class="icon-file-plus2"></i> Approve</button>
						@if($param == 'purchase_request')
							&nbsp;&nbsp;&nbsp;
							<button onclick="goReject({{ $id_approval }})" class="btn bg-danger btn-sm mb-3"><i class="icon-file-minus2"></i> Reject</button>
						@elseif($param == 'leave_request')
							&nbsp;&nbsp;&nbsp;
							<button onclick="goRejectLeave({{ $id_approval }})" class="btn bg-danger btn-sm mb-3"><i class="icon-file-minus2"></i> Reject</button>
						@elseif($param == 'report')
							&nbsp;&nbsp;&nbsp;
							<button onclick="goRejectReport({{ $id_approval }})" class="btn bg-danger btn-sm mb-3"><i class="icon-file-minus2"></i> Reject</button>
						@endif
					@endif
				@endif
				@if($data)
					@if($param == 'purchase_payments')
						<table class="table table-bordered">
							<thead class="table-secondary">
								<tr class="text-center">
									<th>PO No.</th>
									<th>SO No.</th>
									<th>Supplier</th>
									<th>Total</th>
									<th>Paid</th>
									<th>Balance</th>
								</tr>
							</thead>
							<tbody>
								@foreach($data->project->projectPurchase as $pp)
									@php
										$balance = floatval(str_replace(',','.',str_replace('.','',$pp->getTotal()))) - floatval(str_replace(',','.',str_replace('.','',$pp->getPaid())));
									@endphp
								<tr>
									<td class="text-center">{{ $pp->code }}</td>
									<td class="text-center">{{ $pp->projectSale->code }}</td>
									<td class="text-center">{{ $pp->supplier->name }}</td>
									<td class="text-right">{{ 'Rp '.$pp->getTotal() }}</td>
									<td class="text-right">{{ 'Rp '.$pp->getPaid() }}</td>
									<td class="text-right">{{ 'Rp '.number_format($balance,2,",",".") }}</td>
								</tr>
								@endforeach
								<tr>
									<th colspan="6" class="text-center bg-info">Detail Purchase Payment</th>
								</tr>
								<tr class="text-center">
									<th>Date Release</th>
									<th>Due Date Giro</th>
									<th>Giro No.</th>
									<th>Bank</th>
									<th>Nominal</th>
									<th>Proof</th>
								</tr>
								<tr>
									<td class="text-center">{{ date('d M Y',strtotime($data->date)) }}</td>
									<td class="text-center">{{ $data->giro == '1' ? date('d M Y',strtotime($data->giro_date)) : '-' }}</td>
									<td class="text-center">{{ $data->giro == '1' ? date('d M Y',strtotime($data->giro_code)) : '-' }}</td>
									<td class="text-center">{{ $data->coa->name }}</td>
									<td class="text-right">{{ $data->projectPurchase->currency->symbol.' '.number_format($data->nominal,2,',','.') }}</td>
									<td><a href="{{ $data->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>
								</tr>
							</tbody>
						</table>
						<div class="row mt-3">
							<div class="col-md-6">
								<div class="iframe-loading" style="background:url({{ url('website/loader.gif') }});">
									<iframe src="{{ $link }}" id="pdf_display_frame" width="100%" height="600px"></iframe>
								</div>
							</div>
							<div class="col-md-6">
								<img src="{{ $link2 }}" width="100%">
							</div>
						</div>
					@endif
				@else
					@if($param == 'quotation_order')
						<div class="row">
							<div class="col-md-12">
								<div class="iframe-loading" style="background:url({{ url('website/loader.gif') }});">
									<iframe src="{{ $link }}" id="pdf_display_frame" width="100%" height="500px"></iframe>
								</div>
							</div>
						</div>
					@elseif($param == 'report')
						<div class="row">
							<div class="col-md-12"><h2 class="text-center bg-primary">Project Information</h2></div>
							<div class="col-md-6">
								<div class="table-responsive">
									<table class="table table-bordered table-striped">
										<tbody>
											<tr>
												<td width="30%">Project Name</td>
												<td width="1%">:</td>
												<td>{{ $project->name }}</td>
											</tr>
											<tr>
												<td>Phone</td>
												<td width="1%">:</td>
												<td>{{ $project->customer->phone }}</td>
											</tr>
											<tr>
												<td>Email</td>
												<td width="1%">:</td>
												<td>{{ $project->customer->email }}</td>
											</tr>
											<tr>
												<td>Constructor Name</td>
												<td width="1%">:</td>
												<td>{{ $project->customer->constructor }}</td>
											</tr>
											<tr>
												<td>Country</td>
												<td width="1%">:</td>
												<td>{{ $project->country->name }}</td>
											</tr>
											<tr>
												<td>City</td>
												<td width="1%">:</td>
												<td>{{ $project->city->name }}</td>
											</tr>
											<tr>
												<td>Timeline</td>
												<td width="1%">:</td>
												<td>{{ date('d M Y', strtotime($project->timeline)) }}</td>
											</tr>
											<tr>
												<td>PIC</td>
												<td width="1%">:</td>
												<td>{{ $project->manager }}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-md-6">
								<div class="table-responsive">
									<table class="table table-bordered table-striped">
										<tbody>
											<tr>
												<td width="30%">Consultant Name</td>
												<td width="1%">:</td>
												<td>{{ $project->consultant }}</td>
											</tr>
											<tr>
												<td>Owner</td>
												<td width="1%">:</td>
												<td>{!! $project->owner !!}</td>
											</tr>
											<tr>
												<td>Bank Destination</td>
												<td width="1%">:</td>
												<td style="font-size:11px;">{!! $project->coa->name !!}</td>
											</tr>
											<tr>
												<td>Payment Method</td>
												<td width="1%">:</td>
												<td>{!! $project->paymentMethod() !!}</td>
											</tr>
											<tr>
												<td>Payment Term</td>
												<td width="1%">:</td>
												<td>{!! $project->paymentTerm() !!}</td>
											</tr>
											<tr>
												<td>Supply Method</td>
												<td width="1%">:</td>
												<td>{!! $project->supplyMethod() !!}</td>
											</tr>
											<tr>
												<td>PPN</td>
												<td width="1%">:</td>
												<td>{!! $project->ppn() !!}</td>
											</tr>
											<tr>
												<td>Budgeting Project Approved BY</td>
												<td width="1%">:</td>
												<td>{!! $project->budgetingProject()->exists() ? $project->budgetingProject()->latest()->first()->checked->name : '' !!}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<h2 class="text-center bg-primary">Budget vs Real Profit Comparison</h2>
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th>Nett Profit (Budget)</th>
											<th>Percent Nett (Budget)</th>
											<th>Nett Profit (Real)</th>
											<th>Percent Nett (Real)</th>
											<th>Variation</th>
										</tr>
									</thead>
									<tbody>
										@if($project->budgetingProject()->exists())
											@php
												$budgetprofit = $project->budgetingProject()->latest()->first()->getNettProfit();
												$percentprofitbudget = $project->budgetingProject()->latest()->first()->getNettProfitPercent();
												$realprofit = $project->getNettProfit();
												$percentprofitreal = $project->getRevenue() > 0 ? (round(($realprofit / $project->getRevenue()) * 100,2)) : 0;
												$percentvariation = $percentprofitbudget > 0 ? round((($percentprofitreal - $percentprofitbudget) / $percentprofitbudget) * 100,2) : 0;
											@endphp
											<tr class="text-center">
												<td>Rp{{ number_format($budgetprofit,2,',','.') }}</td>
												<td>
													{{ $percentprofitbudget }}%
												</td>
												<td>
													@if($realprofit > 0)
														Rp{{ number_format($realprofit,2,',','.') }}
													@else
														<span class="badge badge-warning">Paid full, not yet delivered.</span>
													@endif
												</td>
												<td>
													@if($realprofit > 0)
														{{ $percentprofitreal }}%
													@else
														<span class="badge badge-warning">Paid full, not yet delivered.</span>
													@endif
												</td>
												<td>
													@if($realprofit > 0)
														{!! $percentvariation > 0 ? '<span class="badge badge-success"><i class="icon-arrow-up5"></i></span>' : ($percentvariation < 0 ? '<span class="badge badge-danger"><i class="icon-arrow-down5"></i></span>' : '') !!}
													&nbsp;{{ $percentvariation }}%
													@else
														0
													@endif
													
												</td>
											</tr>
										@else
											<tr class="text-center">
												<td colspan="3"><span class="badge badge-warning">This project didn't have any budgeting, because this project was created before budgeting rules.</span></td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<h2 class="text-center bg-primary">Sales Transaction</h2>
									<table class="table table-bordered">
										<thead class="table-secondary">
											<tr class="text-center">
												<th>No.</th>
												<th>SO No.</th>
												<th>Sales Person</th>
												<th>Date SO</th>
												<th>Total SO</th>
												<th>-</th>
												<th>-</th>
											</tr>
										</thead>
										<tbody>
										@php
											if(count($project->projectSale) > 0){
												$no = 1;
												foreach($project->projectSale as $pp) {
													$rowspan = 1 + 2 + count($pp->projectSalePay) + 2 + count($pp->projectSaleProduct) + 2;
													
													$balance = floatval(str_replace(',','.',str_replace('.','',$pp->getTotal())));
										@endphp
											<tr>
												<td class="text-center" rowspan="{{ $rowspan }}">{{ $no }}.</td>
												<td class="text-center">{{ $pp->code }}</td>
												<td class="text-center">{{ $pp->sales->name }}</td>
												<td class="text-center">{{ date('d M Y',strtotime($pp->created_at)) }}</td>
												<td class="text-right">Rp{{ $pp->getTotal() }}</td>
												<td class="text-center">-</td>
												<td class="text-center">-</td>
											</tr>
										@php
											if(count($pp->projectSaleProduct) > 0){
										@endphp
												<tr>
													<td colspan="6" class="text-center">&nbsp;</td>
												</tr>
												<tr>
													<td colspan="6" class="text-center bg-info">Detail Sales Product</td>
												</tr>
												<tr class="text-center bg-grey-600">
													<td>Product Code</td>
													<td>Size</td>
													<td>Needed</td>
													<td>Sent</td>
													<td>Returned</td>
													<td class="text-center">-</td>
												</tr>
										@php
										}
										
										foreach($pp->projectSaleProduct as $ppp){
											if($ppp->unit == '2' || $ppp->unit == '3'){
												$m2 = (( $ppp->product->type->length * $ppp->product->type->width ) / 10000) * $ppp->product->carton_pcs;
												$countbox = $m2 ? ceil($ppp->qty / $m2) : 0;
											}else{
												$countbox = $ppp->qty;
											}
											$totalSent = $ppp->getCountSent();
											$totalReturn = $ppp->getCountReturn();
										@endphp
											<tr>
												<td class="text-center">{{ $ppp->product->type->code }}</td>
												<td class="text-center">{{ $ppp->product->type->length.'x'.$ppp->product->type->width }}</td>
												<td class="text-center">{{ $countbox }}</td>
												<td class="text-center">{{ $totalSent }}</td>
												<td class="text-center">{{ $totalReturn }}</td>
												<td class="text-center">-</td>
											</tr>
										@php
										}
										
										if(count($pp->projectSalePay) > 0){
										@endphp
												<tr>
													<td colspan="6" class="text-center">&nbsp;</td>
												</tr>
												<tr>
													<td colspan="6" class="text-center bg-info">Detail Sales Payment</td>
												</tr>
												<tr class="text-center bg-grey-600">
													<td>Invoice No.</td>
													<td>Date Paid</td>
													<td>Payment Term</th>
													<td>Nominal</td>
													<td>Balance</td>
													<td>Proof</td>
												</tr>
												
										@php
										}
										foreach($pp->projectSalePay as $ppp){
											$balance -= $ppp->nominal;
										@endphp
												<tr>
													<td class="text-center">{{ $ppp->code }}</td>
													<td class="text-center">{{ date('d M Y',strtotime($ppp->date)) }}</td>
													<td class="text-center">{!! '<b>'.$ppp->paymentMethod().'</b> : <b>'.$ppp->note.'</b>' !!}</td>
													<td class="text-right">Rp{{ number_format($ppp->nominal,0,',','.') }}</td>
													<td class="text-right">Rp{{ number_format(($balance > 0 ? $balance : 0) ,0,',','.') }}</td>
													<td class="text-center"><a href="{{ $ppp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>
												</tr>
										@php
										}
										$no++;
									}
								}else{
									@endphp
										<tr>
											<td colspan="6"><div class="alert bg-warning text-white alert-styled-left alert-dismissible">There is no SO data.</div></td>
										</tr>
								@php
								}
								@endphp
								</tbody></table>
							</div>
						</div>
					@elseif($param == 'budgeting_projects')
						<style>
							table.table-bordered {
								border:2px solid black;
							}
							table.table-bordered > thead{
								border:2px solid black;
							}
							table.table-bordered > thead > tr > th{
								border:2px solid black;
							}
							table.table-bordered > tbody > tr > td{
								border:2px solid black;
							}
							table.table-bordered > tfoot > tr > td{
								border:2px solid black;
							}
							
							.parent {
								background-color:#aeafb0;
								color:white;
							}
							
							.estimation {
								background-color:#cbcdcd;
							}
						</style>
						
						<div class="font-weight-bold">
							<h3>BUDGET PLAN PROJECTION</h3>
						</div>
						<div class="font-weight-bold font-italic">
							Project Name : {{ $project->name.' CUST. '.$project->project->customer->name }}
						</div>
						<div class="font-weight-bold font-italic">
							Project No : {{ $project->project->code }}
						</div>
						<div class="font-weight-bold">
							Branch : {{ $project->branch() }}
						</div>
						@if($project->revision_counter > 0)
						<div class="font-weight-bold">
							Revision : {{ $approval->column_name == 'checked_by' ? $project->revision_counter : ($project->revision_counter == 0 ? $project->revision_counter : $project->revision_counter - 1) }}
						</div>
						@endif
						<div style="position:absolute;top:65px;right:25px;z-index:1;">
							@if($param == 'budgeting_projects')
								@if($approval->approved_by)
									@if($approval->status == '1')
										<span class="badge badge-success rounded-0 mb-3" style="font-size:20px;">Approved by : {{ $approval->approvedBy->name }}</span> 
									@else
										<span class="badge badge-danger rounded-0 mb-3" style="font-size:20px;">Rejected by : {{ $approval->approvedBy->name }}</span> 
									@endif
									
								@else
									<button onclick="goApprove({{ $id_approval }})" class="btn btn-sm mb-3" style="width:200px;height:200px;font-size:30px;background-image: url({{ url('website/approved_stamp.png') }});background-position:center;background-repeat:no-repeat;background-size:cover;padding:5px;background-size: 99% 90%;"></button>
								@endif
							@endif
						</div>
						
						<div class="row">
							<div class="col-md-12 mt-2">
								<div class="p-2" id="full-information" style="background-color:#faf4b4 !important;">
									<div class="row">
										<div class="col-md-12">
											<hr>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<h6 class="text-uppercase font-weight-bold">
													Calculation Information
												</h6>
												<hr>
												<div class="table-responsive">
													<table class="table table-bordered table-striped">
														<tbody>
															<tr>
																<td width="40%">Period</td>
																<td>: {{ date('M Y',strtotime($project->month_start)).' - '.date('M Y',strtotime($project->month_end)) }}</td>
															</tr>
															<tr>
																<td>Import Duty</td>
																<td>: {{ number_format($project->percent_import,2,',','.') }}%</td>
															</tr>
															<tr>
																<td>Safe Guard</td>
																<td>: {{ number_format($project->percent_safe,2,',','.') }}%</td>
															</tr>
															<tr>
																<td>PPN</td>
																<td>: {{ number_format($project->percent_ppn,2,',','.') }}%</td>
															</tr>
															<tr>
																<td>PPH</td>
																<td>: {{ number_format($project->percent_pph,2,',','.') }}%</td>
															</tr>
															<tr>
																<td>Fee MKJ</td>
																<td>: {{ number_format($project->percent_mkj,2,',','.') }}%</td>
															</tr>
															<tr>
																<td>Fee PTA</td>
																<td>: {{ number_format($project->percent_pta,2,',','.') }}%</td>
															</tr>
															<tr>
																<td>Fee Middleman</td>
																<td>: {{ number_format($project->percent_mid,2,',','.') }}%</td>
															</tr>
															<tr>
																<td>Sales Commission</td>
																<td>: {{ number_format($project->percent_scom,2,',','.') }}%</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<h6 class="text-uppercase font-weight-bold">
													Price Information
												</h6>
												<hr>
												<div class="table-responsive">
													<table class="table table-bordered table-striped">
														<tbody>
															<tr>
																<td width="40%">Currency</td>
																<td>: {{ $project->currency->name }}</td>
															</tr>
															<tr>
																<td>Exchange Rate</td>
																<td>: {{ number_format($project->help_exchange_rate,2,',','.') }}</td>
															</tr>
															<tr>
																<td>Number of Container</td>
																<td>: {{ number_format($project->help_container_no,2,',','.') }}</td>
															</tr>
															<tr>
																<td>LS Cost (IDR)</td>
																<td>: {{ number_format($project->help_ls_cost * $project->help_exchange_rate,2,',','.') }}</td>
															</tr>
															<tr>
																<td>Product Cost (IDR)</td>
																<td>: {{ number_format($project->help_product_cost * $project->help_exchange_rate,2,',','.') }}</td>
															</tr>
															<tr>
																<td>Quantity of Container</td>
																<td>: {{ number_format($project->help_container_qty,2,',','.') }}</td>
															</tr>
															<tr>
																<td>Freight Cost (IDR)</td>
																<td>: {{ number_format($project->help_freight_cost * $project->help_exchange_rate,2,',','.') }}</td>
															</tr>
															<tr>
																<td>EMKL (IDR)</td>
																<td>: {{ number_format($project->help_emkl_cost,2,',','.') }}</td>
															</tr>
															<tr>
																<td>Status Stock</td>
																<td>: 
																	@if($project->percent_import > 0 && $project->percent_safe > 0)
																		Indent
																	@else
																		From Stock / Buy MKJ / Local
																	@endif
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<h6 class="text-uppercase font-weight-bold">
												Product Information
											</h6>
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead>
														<tr class="text-center">
															<th>Product</th>
															<th>Qty</th>
															<th>Unit</th>
															<th>Price/Unit</th>
															<th>Total</th>
														</tr>
													</thead>
													<tbody>
														@php
															$total = 0;
														@endphp
														@foreach($project->budgetingProjectProduct as $row)
															<tr>
																<td class="text-center">{{ $row->product->name() }}</td>
																<td class="text-center">{{ $row->qty }}</td>
																<td class="text-center">{{ $row->unit() }}</td>
																<td class="text-right">{{ number_format($row->price,2,',','.') }}</td>
																<td class="text-right">{{ number_format($row->total,2,',','.') }}</td>
															</tr>
															@php
																$total += $row->total;
															@endphp
														@endforeach
														<tr style="background-color:#0d9abd !important;font-size:15px !important;color:white;">
															<td colspan="4" class="text-right">TOTAL</td>
															<td class="text-right">{{ number_format($total,2,',','.') }}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<hr>
						</div>
						<h6 class="text-uppercase font-weight-bold">
							Budgeting Data
						</h6>
						<div class="table-responsive mb-2">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th width="25%">Remarks by {{ $project->user->name }} :</th>
										<th class="font-italic">
											{{ $project->remarks }} 
										</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="row justify-content-center" id="reportData">
							<div class="alert alert-danger" id="validation_alert" style="display:none;">
							  <ul id="validation_content"></ul>
						   </div>
							<div class="col-md-12">
								@php 
									$totalrevenue = 0;
									$totalservice = 0;
									$totalrevenueestimation = 0;
									
									foreach($project->budgetingProjectDetail->where('group_count','1')->all() as $row){
										$totalrevenue += $row->nominal;
										if($row->coa_id == '286' || $row->coa_id == '287'){
											$totalservice += $row->nominal;
										}
										$totalrevenueestimation += $row->estimation;
									}
									
									$totalbuying = 0;
									$totalcogs = 0;
									$totalmkt = 0;
									$totalcri = 0;
									$totalbuyingestimation = 0;
									$totalcogsestimation = 0;
									$totalmktestimation = 0;
									$totalcriestimation = 0;
									
									foreach($project->budgetingProjectDetail->where('group_count','2')->all() as $row){
										$totalbuying += $row->nominal;
										$totalbuyingestimation += $row->estimation;
									}
									
									foreach($project->budgetingProjectDetail->where('group_count','3')->all() as $row){
										$totalcogs += $row->nominal;
										$totalcogsestimation += $row->estimation;
									}
									
									foreach($project->budgetingProjectDetail->where('group_count','4')->all() as $row){
										$totalmkt += $row->nominal;
										$totalmktestimation += $row->estimation;
									}
									
									foreach($project->budgetingProjectDetail->where('group_count','5')->all() as $row){
										$totalcri += $row->nominal;
										$totalcriestimation += $row->estimation;
									}
									
									$totalcogs += $totalbuying;
									$totalcogsestimation += $totalbuyingestimation;
								@endphp
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th class="text-right" colspan="3">Estimation Title : </th>
												<th class="font-italic" colspan="2">
													<span class="estimation-text">{{ $project->estimation_name ? $project->estimation_name : 'Empty' }}</span>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr class="font-italic parent">
												<td>Description</td>
												<td class="text-right">Budget</td>
												<td class="text-right" width="5%">%</td>
												<td class="text-right">Estimation</td>
												<td class="text-right" width="5%">%</td>
											</tr>
											@foreach($project->budgetingProjectDetail->where('group_count','1')->all() as $row)
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($row->nominal/$totalrevenue)*100,2) }}%</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right estimation">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
											</tr>
											@endforeach
											<tr class="font-italic parent">
												<td>Total Revenue</td>
												<td class="text-right">{{ number_format($totalrevenue,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalrevenue/$totalrevenue)*100,2) }}%</td>
												<td class="text-right">{{ number_format($totalrevenueestimation,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($totalrevenueestimation/$totalrevenueestimation)*100,2) }}%</td>
											</tr>
											<tr>
												<td colspan="5">COGS</td>
											</tr>
											@foreach($project->budgetingProjectDetail->where('group_count','2')->all() as $row)
												<tr>
													<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
													<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
													<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($row->nominal/($totalrevenue - $totalservice))*100,2) }}%</td>
													<td class="text-right estimation">
														<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
													</td>
													<td class="text-right estimation">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
												</tr>
											@endforeach
											<tr>
												<td colspan="5">Import Cost</td>
											</tr>
											@foreach($project->budgetingProjectDetail->where('group_count','3')->all() as $row)
												@if($row->coa_id == 292)
													@php 
														$total = $project->budgetingProjectDetail->whereIn('coa_id',[122,290,288])->sum('nominal');
														$totalestimation = $project->budgetingProjectDetail->whereIn('coa_id',[122,290,288])->sum('estimation');
													@endphp
													<tr>
														<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
														<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
														<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
														<td class="text-right estimation">
															<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
														</td>
														<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
													</tr>
												@elseif($row->coa_id == 293)
													@php 
														$total = $project->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
														$totalestimation = $project->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
													@endphp
													<tr>
														<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
														<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
														<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
														<td class="text-right estimation">
															<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
														</td>
														<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
													</tr>
												@elseif($row->coa_id == 294)
													@php 
														$total = $project->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
														$totalestimation = $project->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
													@endphp
													<tr>
														<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
														<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
														<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
														<td class="text-right estimation">
															<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
														</td>
														<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
													</tr>
												@elseif($row->coa_id == 295)
													@php 
														$total = $project->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
														$totalestimation = $project->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
													@endphp
													<tr>
														<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
														<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
														<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
														<td class="text-right estimation">
															<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
														</td>
														<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
													</tr>
												@else
													@php
														$total = $project->budgetingProjectDetail->whereIn('coa_id',[122,290])->sum('nominal');
														$totalestimation = $project->budgetingProjectDetail->whereIn('coa_id',[122,290])->sum('estimation');
													@endphp
													<tr>
														<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
														<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
														<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
														<td class="text-right estimation">
															<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
														</td>
														<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
													</tr>
												@endif
											@endforeach
											<tr class="font-italic parent">
												<td>Total COGS</td>
												<td class="text-right">{{ number_format($totalcogs,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalcogs/$totalrevenue)*100,2) }}%</td>
												<td class="text-right">{{ number_format($totalcogsestimation,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($totalcogsestimation/$totalrevenueestimation)*100,2) }}%</td>
											</tr>
											<tr>
												<td colspan="5">Marketing Cost</td>
											</tr>
											@foreach($project->budgetingProjectDetail->where('group_count','4')->all() as $row)
												@if($row->coa_id == 139)
													@php
														$total = $project->budgetingProjectDetail->whereIn('coa_id',[134])->sum('nominal') + $totalcogs;
														$totalestimation = $project->budgetingProjectDetail->whereIn('coa_id',[134])->sum('estimation') + $totalcogsestimation;
													@endphp
													<tr>
														<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
														<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
														<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
														<td class="text-right estimation">
															<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
														</td>
														<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
													</tr>
												@elseif($row->coa_id == 296 || $row->coa_id == 299)
													@php
														$total = $project->budgetingProjectDetail->whereIn('coa_id',[284,285])->sum('nominal');
														$totalestimation = $project->budgetingProjectDetail->whereIn('coa_id',[284,285])->sum('estimation');
													@endphp
													<tr>
														<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
														<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
														<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
														<td class="text-right estimation">
															<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
														</td>
														<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
													</tr>
												@else
													<tr>
														<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
														<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
														<td class="text-right">{{ $totalcogs == 0 ? 0 : round(($row->nominal/$totalcogs)*100,2) }}%</td>
														<td class="text-right estimation">
															<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
														</td>
														<td class="text-right estimation">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
													</tr>
												@endif
											@endforeach
											<tr class="font-italic parent">
												<td>Total Marketing Cost</td>
												<td class="text-right">{{ number_format($totalmkt,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalmkt/$totalrevenue)*100,2) }}%</td>
												<td class="text-right">{{ number_format($totalmktestimation,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($totalmktestimation/$totalrevenueestimation)*100,2) }}%</td>
											</tr>
											<tr>
												<td colspan="5">Company's Rsv Income (CRI)</td>
											</tr>
											@foreach($project->budgetingProjectDetail->where('group_count','5')->all() as $row)
											<tr>
												<td style="padding-left:50px;">{{ $row->coa()['name'].' - '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">
													@php 
											
													if($row->coa_id == 444444){
														echo $totalrevenue == 0 ? 0 : round(($row->nominal/$totalrevenue)*100,2).'%';
													}else{
														echo $totalcogs == 0 ? 0 : round(($row->nominal/$totalcogs)*100,2).'%';
													}
													
													@endphp
												</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right estimation"></td>
											</tr>
											@endforeach
											<tr class="font-italic parent">
												<td>Total CRI</td>
												<td class="text-right">{{ number_format($totalcri,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalcri/$totalrevenue)*100,2) }}%</td>
												<td class="text-right">{{ number_format($totalcriestimation,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($totalcriestimation/$totalrevenueestimation)*100,2) }}%</td>
											</tr>
											<tr>
												<td colspan="5">Nett Income</td>
											</tr>
											<tr class="font-italic parent">
												<td>Revenue - (Cogs + Marketing + CRI)</td>
												<td class="text-right">{{ number_format($totalrevenue - ($totalcogs + $totalmkt + $totalcri),0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenue == 0 ? 0 : round((($totalrevenue - ($totalcogs + $totalmkt + $totalcri))/$totalrevenue)*100,2) }}%</td>
												<td class="text-right">{{ number_format($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation),0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round((($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation))/$totalrevenueestimation)*100,2) }}%</td>
											</tr>
											<tr class="font-italic parent" style="background-color:#0d9abd !important;font-size:15px !important;">
												<td>Nett Profit</td>
												<td class="text-right">{{ number_format(($totalrevenue - ($totalcogs + $totalmkt + $totalcri)) + $totalcri,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(((($totalrevenue - ($totalcogs + $totalmkt + $totalcri)) + $totalcri) / $totalrevenue) * 100,2) }}%</td>
												<td class="text-right">{{ number_format(($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation)) + $totalcriestimation,0,',','.') }}</td>
												<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(((($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation)) + $totalcriestimation) / $totalrevenueestimation) * 100,2) }}%</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					@elseif($param == 'purchase_request')
						<div class="row justify-content-center">
							<div class="col-md-12">
								<dl class="row mb-0">
									<dd class="col-sm-3">Date</dd>
									<dt class="col-sm-9">{{ date('d M Y',strtotime($project->date)) }}</dt>
									<dd class="col-sm-3">Title</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by || $approval->column_name == 'checked_by')
											{{ $project->title }}
										@else
											<input type="text" class="form-control" id="titlepr" name="titlepr" value="{{ $project->title }}" />
										@endif
									</dt>
									<dd class="col-sm-3">To</dd>
									<dt class="col-sm-9">
										{{ $project->bill_to }}
									</dt>
									<dd class="col-sm-3">Branch</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by || $approval->column_name == 'checked_by')
											{{ $project->branch() }}
										@else
											<select name="branch" id="branch" class="custom-select">
											@foreach (DB::table('company_entities')->get() as $company)
											   <option value="{{$company->id}}" {{$company->id== $project->branch ? 'selected' : ''}}>{{$company->name}}</option>
											@endforeach
											 </select>
										@endif
									</dt>
									@if($project->link_type !== 'project_purchases')
									<dd class="col-sm-3">Journal Credit</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by || $approval->column_name == 'checked_by' || $approval->approvalable->link_type == 'customer_deposit')
											{{ $project->coa ? $project->coa->name : '' }}
										@else
											<select name="coa_id" id="coa_id" class="custom-select">
												<option value="332" {{ $project->coa_id == 332 ? 'selected' : '' }}>Payable IDR</option>
												@foreach(Coa::where('status', 1)->where('parent_id',81)->oldest('code')->get() as $row)
												<option value="{{ $row->id }}" {{ $project->coa_id == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
												@endforeach
											</select>
										@endif
									</dt>
									@endif
									<dd class="col-sm-3">Note</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by || $approval->column_name == 'checked_by')
											{{ $project->item }}
										@else
											<textarea class="form-control" id="notepr" name="notepr" rows="10">{{ $project->item }}</textarea>
										@endif
									</dt>
									<dd class="col-sm-3">Nominal</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by || $approval->column_name == 'checked_by')
											{{ number_format($project->total_nominal,2,',','.') }}
										@else
											<input type="text" onkeyup="formatRupiah(this)" value="{{ number_format($project->total_nominal,2,',','.') }}" class="form-control" id="nominalpr" name="nominalpr" {{ $project->link_type == 'project_purchases' ? 'readonly' : '' }}/>
										@endif
									</dt>
									<dd class="col-sm-3">Proof</dd>
									<dt class="col-sm-9">
										@php
											if($project->image){
												if(explode('.',$project->image)[1] == 'pdf'){
													echo '<a href="' .$project->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
												}else{
													echo '<a data-magnify="gallery" data-src="" data-caption="'.$project->item.'" data-group="a" href="' .$project->attachment() . '"><img src="' . $project->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
												}
											}else{
												echo '<span class="badge badge-danger">Empty</span>';
											}
										@endphp
									</dt>
									<dd class="col-sm-3">Reference</dd>
									<dt class="col-sm-9">
										@if($project->link_type == 'project_sales')
											<div class="iframe-loading" style="background:url();">
												<iframe src="{{ url('admin/sales/project/print/sales_news/'.base64_encode($project->link_id)) }}" id="pdf_display_frame" width="100%" height="500px"></iframe>
											</div>
											<div class="iframe-loading" style="background:url();">
												<iframe src="{{ url('admin/sales/project/print/sales_order/'.base64_encode($project->link_id)) }}" id="pdf_display_frame" width="100%" height="500px"></iframe>
											</div>
										@elseif($project->link_type == 'fee_pta')
											@php
												$data = CashBank::find($project->link_id)
											@endphp
											<a href="{{ url('admin/finance/cash_bank?mode_edit=true&id='.$project->link_id) }}" class="btn btn-success" target="_blank"><i class="icon-zoomin3"></i></a>
										@else
											<span class="badge badge-warning">None</span>
										@endif
									</dt>
									@if(session('bo_branch') == '1')
									<dd class="col-sm-3">Checked By</dd>
									<dt class="col-sm-9">
										@if($project->checked_by)
											<span class="badge badge-warning">{{ $project->checked->name }}</span>
										@else
											<span class="badge badge-warning">Waiting</span>
										@endif
									</dt>
									@endif
								</dl>
							</div>
						</div>
					@elseif($param == 'receivable_payment')
						<div class="row justify-content-center">
							<div class="col-md-12">
								<dl class="row mb-0">
									<dd class="col-sm-3">Date</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by)
											{{ date('d M Y',strtotime($project->date)) }}
										@else
											<input type="date" class="form-control" id="date" name="date" value="{{ $project->date }}">
										@endif
									</dt>
									<dd class="col-sm-3">Customer</dd>
									<dt class="col-sm-9">
										{{ $project->customer->name }}
									</dt>
									<dd class="col-sm-3">Note</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by)
											{{ $project->note }}
										@else
											<textarea class="form-control" id="note" name="note" rows="5">{{ $project->note }}</textarea>
										@endif
									</dt>
									<dd class="col-sm-3">Nominal</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by)
											{{ number_format($project->nominal,2,',','.') }}
										@else
											<input type="text" onkeyup="formatRupiah(this)" value="{{ number_format($project->nominal,2,',','.') }}" class="form-control" id="nominal" name="nominal" />
										@endif
									</dt>
									<dd class="col-sm-3">Proof</dd>
									<dt class="col-sm-9">
										@php
											if($project->image){
												if(explode('.',$project->image)[1] == 'pdf'){
													echo '<a href="' .$project->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
												}else{
													echo '<a data-magnify="gallery" data-src="" data-caption="'.$project->item.'" data-group="a" href="' .$project->attachment() . '"><img src="' . $project->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
												}
											}else{
												echo '<span class="badge badge-danger">Empty</span>';
											}
										@endphp
									</dt>
									<dd class="col-sm-3">Branch</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by)
										{{ $project->branch == '1' ? 'PTA' : 'SMB' }}
										<input type="hidden" name="branch_rp" id="branch_rp" value="{{$project->branch}}">
										@else
										<select name="branch_rp" id="branch_rp" class="custom-select">
											<option value="1" {{ $project->branch == '1' ? 'selected' : '' }}>PTA</option>
											<option value="2" {{ $project->branch == '2' ? 'selected' : '' }}>SMB</option>
										 </select>
										@endif
									</dt>
									<dd class="col-sm-3">Bank/Cash Destination</dd>
									<dt class="col-sm-9">
									@if($approval->approved_by)
										{{ $project->coa->name }}
									@else
										<select name="coa" id="coa" class="custom-select">
										   <option value="">-- Choose --</option>
										   @foreach($coa->where('parent_id',0)->whereIn('code',['1.000.00']) as $c)
												@if(count($c->child()) == 0)
													<option value="{{ $c->id }}" {{ $c->id == $project->coa_id ? 'selected' : '' }}>{{ $c->name }}</option>
												@else
													<optgroup label="{{ $c->name }}">
													  @foreach($c->child() as $bc)
														@if(count($bc->child()) == 0)
															<option value="{{ $bc->id }}" {{ $bc->id == $project->coa_id ? 'selected' : '' }}>{{ $bc->name }}</option>
														@else
															<optgroup label="{{ $bc->name }}">
																@foreach($bc->child() as $bcc)
																	@if(count($bcc->child()) == 0)
																		<option value="{{ $bcc->id }}" {{ $bcc->id == $project->coa_id ? 'selected' : '' }}>{{ $bcc->name }}</option>
																	@else
																		<optgroup label="{{ $bcc->name }}">
																			@foreach($bcc->child() as $bccc)
																				@if(count($bccc->child()) == 0)
																					<option value="{{ $bccc->id }}" {{ $bccc->id == $project->coa_id ? 'selected' : '' }}>{{ $bccc->name }}</option>
																				@endif
																			@endforeach
																		</optgroup>
																	@endif
																@endforeach
															</optgroup>
														@endif
													  @endforeach
													</optgroup>
												@endif
										   @endforeach
										</select>
									@endif
									</dt>
								</dl>
							</div>
						</div>
					@elseif($param == 'project_main_payment')
					
						<div class="row justify-content-center">
							<div class="col-md-12">
								<dl class="row mb-0">
									<dd class="col-sm-3">Date</dd>
									<dt class="col-sm-9">
									@if($approval->approved_by)
										{{ date('d M Y',strtotime($project->date)) }}
									@else
										<input type="date" name="tanggal" id="tanggal" value="{{ $project->date }}" class="form-control" style="width:200px;">
									@endif
									</dt>
									<dd class="col-sm-3">Payment No.</dd>
									<dt class="col-sm-9">{{ $project->code }}</dt>
									<dd class="col-sm-3">Customer</dd>
									<dt class="col-sm-9">{{ $project->customer->name }}</dt>
									@if ($project->coa_id == 67 && $project->cashBank->customer_id && $project->cashBank->customer_id !=0)
										<dd class="col-sm-3">Impotant Note</dd>
										<dt class="col-sm-9"><b>This bills will use {{$project->cashBank->customer->name}} customer deposit as payment</b></dt>
									@endif
									<dd class="col-sm-3">Bank/Cash Destination</dd>
									<dt class="col-sm-9">
									@if($approval->approved_by)
										{{ $project->coa->name }}
									@else
										<select name="coa" id="coa" class="custom-select">
										   <option value="">-- Choose --</option>
										   <option value="67" {{ $project->coa_id == 67 ? 'selected' : '' }}>Customer Deposit</option>
										   @foreach($coa->where('parent_id',0)->whereIn('code',['1.000.00']) as $c)
												@if(count($c->child()) == 0)
													<option value="{{ $c->id }}" {{ $c->id == $project->coa_id ? 'selected' : '' }}>{{ $c->name }}</option>
												@else
													<optgroup label="{{ $c->name }}">
													  @foreach($c->child() as $bc)
														@if(count($bc->child()) == 0)
															<option value="{{ $bc->id }}" {{ $bc->id == $project->coa_id ? 'selected' : '' }}>{{ $bc->name }}</option>
														@else
															<optgroup label="{{ $bc->name }}">
																@foreach($bc->child() as $bcc)
																	@if(count($bcc->child()) == 0)
																		<option value="{{ $bcc->id }}" {{ $bcc->id == $project->coa_id ? 'selected' : '' }}>{{ $bcc->name }}</option>
																	@else
																		<optgroup label="{{ $bcc->name }}">
																			@foreach($bcc->child() as $bccc)
																				@if(count($bccc->child()) == 0)
																					<option value="{{ $bccc->id }}" {{ $bccc->id == $project->coa_id ? 'selected' : '' }}>{{ $bccc->name }}</option>
																				@endif
																			@endforeach
																		</optgroup>
																	@endif
																@endforeach
															</optgroup>
														@endif
													  @endforeach
													</optgroup>
												@endif
										   @endforeach
										</select>
									@endif
									</dt>
									<dd class="col-sm-3">Nominal</dd>
									<dt class="col-sm-9">
										@if($approval->approved_by)
											Rp {{ number_format($project->nominal,0,',','.') }}
										@else
											<input type="text" name="nominal" id="nominal" value="{{ number_format($project->nominal,0,',','.') }}" class="form-control" style="width:200px;" onkeyup="formatRupiah(this)">
										@endif
									</dt>
									<dd class="col-sm-3">Payment Method</dd>
									<dt class="col-sm-9">{{ $project->paymentMethod() }}</dt>
									<dd class="col-sm-3">Note</dd>
									<dt class="col-sm-9">{{ $project->note }}</dt>
									<dd class="col-sm-3">Requested By</dd>
									<dt class="col-sm-9">{{ $project->user->name }}</dt>
									<dd class="col-sm-3">Proof</dd>
									<dt class="col-sm-9">
										<a href="{{ $project->attachment() }}" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>
									</dt>
								</dl>
								<h5 class="card-title text-center"><b>List of All Sales & Delivery</b></h5>
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th>No.</th>
											<th>SO No.</th>
											<th>SO Document</th>
											<th>SO Service Document</th>
											<th>DO No.</th>
											<th>DO Document</th>
											<th>Invoice</th>
											<th>Nominal</th>
										</tr>
									</thead>
									<tbody>
										@php
											$no = 1;
											$totalrequest = 0;
										@endphp
										@foreach($project->projectPay as $row)
											<tr>
												<td class="text-center">{{ $no }}</td>
												<td class="text-center">{{ $row->projectSale->code }}</td>
												<td class="text-center">
													<a href="{{ url('admin/sales/project/print/sales_order/'.base64_encode($row->projectSale->id)) }}" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>
												</td>
												<td class="text-center">
													<a href="{{ url('admin/sales/project/print/sales_cost/'.base64_encode($row->projectSale->id)) }}" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>
												</td>
												<td class="text-center">{{ $row->projectDelivery ? $row->projectDelivery->code : '-' }}</td>
												<td class="text-center">
													@if($row->projectDelivery)
													<a href="{{ url('admin/sales/project/print/sales_proforma/'.base64_encode($row->projectDelivery->id)) }}" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>
													@endif
												</td>
												<td class="text-center">
													<a href="{{ url('admin/sales/project/print/sales_invoice/'.base64_encode($row->id)) }}" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>
												</td>
												<td class="text-right">IDR {{ number_format($row->nominal,2,',','.') }}</td>
											</tr>
											@php
												$totalrequest += $row->nominal;
												$no++;
											@endphp
										@endforeach
										<tr style="font-size:18px;font-weight:800;">
											<td class="text-right" colspan="7">GRANDTOTAL</td>
											<td class="text-right">IDR {{ number_format($totalrequest,2,',','.') }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					
					@elseif($param == 'payment_request')
						
						<div class="row justify-content-center">
							<div class="col-md-12">
								<dl class="row mb-0">
									<!-- <dd class="col-sm-3">Title</dd>
									<dt class="col-sm-9">{{ $project->title }}</dt> -->
									<dd class="col-sm-3">Date</dd>
									<dt class="col-sm-9">{{ date('d M Y',strtotime($project->date)) }}</dt>
									<dd class="col-sm-3">Note</dd>
									<dt class="col-sm-9">{{ $project->note }}</dt>
									<dd class="col-sm-3">Requested By</dd>
									<dt class="col-sm-9">{{ $project->user->name }}</dt>
								</dl>
								<h5 class="card-title text-center"><b>List of All Purchase Requests</b></h5>
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th>No.</th>
											<th>Purchase Request</th>
											<!-- <th width="15%">Branch</th>-->
											<th width="15%">User Proof</th>
											<!-- <th width="15%">Finance Proof</th> -->
											<th width="15%">Nominal</th>
										</tr>
									</thead>
									<tbody>
										@php
											$no = 1;
											$totalrequest = 0;
										@endphp
										@foreach($project->paymentDetail as $row)
											@php
												if($row->purchaseRequest->image){
													if(explode('.',$row->purchaseRequest->image)[1] == 'pdf'){
														$photo = '<a href="' .$row->purchaseRequest->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
													}else{
														$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$row->purchaseRequest->item.'" data-group="a" href="' .$row->purchaseRequest->attachment() . '"><img src="' . $row->purchaseRequest->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
													}
												}else{
													$photo = '<span class="badge badge-danger">Empty</span>';
												}
												
												if($row->purchaseRequest->image_paid){
													if(explode('.',$row->purchaseRequest->image_paid)[1] == 'pdf'){
														$photo_paid = '<a href="' .$row->purchaseRequest->attachmentPaid() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
													}else{
														$photo_paid = '<a data-magnify="gallery" data-src="" data-caption="'.$row->purchaseRequest->item.'" data-group="a" href="' .$row->purchaseRequest->attachmentPaid() . '"><img src="' . $row->purchaseRequest->attachmentPaid() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
													}
												}else{
													$photo_paid = '<span class="badge badge-danger">Empty</span>';
												}
												
												$totalrequest += $row->nominal;
											@endphp
											<tr>
												<td class="text-center">{{ $no }}</td>
												<td>{{ ucwords($row->purchaseRequest->item) }}</td>
												<!-- <td class="text-center">{{ ucwords($row->purchaseRequest->branch()) }}</td> -->
												<td class="text-center">{!! $photo !!}</td>
												<!-- <td class="text-center">{!! $photo_paid !!}</td> -->
												<td class="text-right">IDR {{ number_format($row->nominal,2,',','.') }}</td>
											</tr>
											@php
												$no++;
											@endphp
										@endforeach
									</tbody>
									<thead class="table-secondary">
										<tr class="text-center">
											<th colspan="3" class="text-right">Total</th>
											<th width="15%">IDR {{ number_format($totalrequest,2,',','.') }}</th>
										</tr>
									</thead>
								</table>
								<h5 class="card-title text-center mt-3"><b>List of All Source</b></h5>
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th>No.</th>
											<th>Cash & Bank From</th>
											<th width="15%">Branch</th>
											<th width="15%">Nominal</th>
											<th width="15%">BG Number</th>
											<th width="15%">Due Date</th>
										</tr>
									</thead>
									<tbody>
										@php
											$no = 1;
											$totalsource = 0;
										@endphp
										@foreach($project->paymentSource as $row)
											<tr>
												<td class="text-center">{{ $no }}</td>
												<td>{{ $row->coa->name }}</td>
												<td class="text-center">{{ $row->branch() }}</td>
												<td class="text-right">IDR {{ number_format($row->nominal,2,',','.') }}</td>
												<td class="text-center">{{ $row->code ? $row->code : '-' }}</td>
												<td class="text-center">{{ $row->due_date ? date('d M Y',strtotime($row->due_date)) : '-' }}</td>
											</tr>
											@php
												$no++;
												$totalsource += $row->nominal;
											@endphp
										@endforeach
									</tbody>
									<thead class="table-secondary">
										<tr class="text-center">
											<th colspan="5" class="text-right">Total</th>
											<th width="15%">IDR {{ number_format($totalsource,2,',','.') }}</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					
					@elseif($param == 'leave_request')
						<div class="row justify-content-center">
							<div class="col-md-12">
								<dl class="row mb-0">
									<dd class="col-sm-3">Type</dd>
									<dt class="col-sm-9">{{ $project->typeLeave->name.' '.$project->category() }}</dt>
									<dd class="col-sm-3">Note</dd>
									<dt class="col-sm-9">
										{{ $project->note }}
									</dt>
									<dd class="col-sm-3">Period</dd>
									<dt class="col-sm-9">
										@if($project->category == '1')
											{{ date('d M Y', strtotime($project->start_date)).' to '.date('d M Y', strtotime($project->finish_date)) }}
										@elseif($project->category == '2')
											{{ date('d M Y', strtotime($project->date_hour)).' '.$project->start_hour.' - '.$project->finish_hour }}
										@endif
									</dt>
									<dd class="col-sm-3">Proof</dd>
									<dt class="col-sm-9">
										@php
											if($project->proof){
												if(explode('.',$project->proof)[1] == 'pdf'){
													echo '<a href="' .$project->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
												}else{
													echo '<a data-magnify="gallery" data-src="" data-caption="'.$project->item.'" data-group="a" href="' .$project->attachment() . '"><img src="' . $project->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
												}
											}else{
												echo '<span class="badge badge-danger">Empty</span>';
											}
										@endphp
									</dt>
								</dl>
							</div>
						</div>
					@elseif($param == 'project_purchase_quotations')
					<div class="row justify-content-center">
						<div class="col-md-12">
							<dl class="row mb-0">
								<dd class="col-sm-3">Supplier</dd>
								<dt class="col-sm-9">
									{{$project->supplier->name}}
								</dt>
								<dd class="col-sm-3">Sales</dd>
								<dt class="col-sm-9">
									{{$project->sales->name}}
								</dt>
								<dd class="col-sm-3">Note</dd>
								<dt class="col-sm-9">
									{{ $project->note }}
								</dt>
							</dl>
						</div>
						<iframe src="{{$link}}" id="pdf_display_frame" width="80%" class="mt-5" height="700px"></iframe>
					</div>
					@elseif($param == 'service_costs')
					<div class="row justify-content-center">
						<div class="col-md-12">
							<dl class="row mb-0">
								<dd class="col-sm-3">Customer</dd>
								<dt class="col-sm-9">{{$project->customer->name}}</dt>
								<dd class="col-sm-3">Note</dd>
								<dt class="col-sm-9">
									{{ $project->note }}
								</dt>
								<dd class="col-sm-3">Proof</dd>
								<dt class="col-sm-9">
									@php
										if($project->image){
											if(explode('.',$project->image)[1] == 'pdf'){
												echo '<a href="' .$project->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
											}else{
												echo '<a data-magnify="gallery" data-src="" data-group="a" href="' .$project->attachment() . '"><img src="' . $project->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
											}
										}else{
											echo '<span class="badge badge-danger">Empty</span>';
										}
									@endphp
								</dt>
							</dl>
						</div>
						<iframe src="{{$link}}" id="pdf_display_frame" width="80%" class="mt-5" height="700px"></iframe>
					</div>
					@elseif($param == 'service_cost_payments')
					<div class="col-md-6">
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<span class="font-weight-semibold">Info!</span><b> green row on Detail Service Charge Payment History table means current payment approval.</b>
						</div>
					</div>
					<table class="table table-bordered">
						<thead class="table-secondary">
							<tr class="text-center">
								<th>Service Charge No.</th>
								<th>Customer</th>
								<th>Total</th>
								<th>Paid</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>
							@php
								$balance = $project->serviceCost->grandtotal_service - $project->serviceCost->getPaid();
							@endphp
							<tr>
								<td class="text-center">{{  $project->serviceCost->code }}</td>
								<td class="text-center">{{  $project->serviceCost->customer->name }}</td>
								<td class="text-right">{{ 'Rp '.number_format($project->serviceCost->grandtotal_service,2,',','.')  }}</td>
								<td class="text-right">{{ 'Rp '.number_format($project->serviceCost->getPaid(),2,',','.')}}</td>
								<td class="text-right">{{ 'Rp '.number_format($balance,2,",",".") }}</td>
							</tr>

							<tr class="mt-5">
								<th colspan="6" class="text-center bg-info">Detail Service Charge Payment History</th>
							</tr>
							<thead class="table-secondary">
								<tr class="text-center">
									<th>Date Release</th>
									<th>Code</th>
									<th>Bank</th>
									<th>Nominal</th>
									<th>Proof</th>
								</tr>
							</thead>
							@foreach ($project->serviceCost->serviceCostPayment as $row)
								{{-- Display Current Payment with green BG --}}
								@if ($row->id == $project->id)
								<tr class="bg-success">
									<td class="text-center">{{ date('d M Y',strtotime($row->date_paid)) }}</td>
									<td class="text-center">{{ date('d M Y',strtotime($row->code)) }}</td>
									<td class="text-center">{{ $row->coa->name }}</td>
									<td class="text-center">{{ 'Rp '.number_format($row->nominal,2,',','.') }}</td>
									<td><a href="{{ $row->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>
								</tr>
								@else
								<tr>
									<td class="text-center">{{ date('d M Y',strtotime($row->date_paid)) }}</td>
									<td class="text-center">{{ date('d M Y',strtotime($row->code)) }}</td>
									<td class="text-center">{{ $row->coa->name }}</td>
									<td class="text-center">{{ 'Rp '.number_format($row->nominal,2,',','.') }}</td>
									<td><a href="{{ $row->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>
								</tr>
								@endif
							@endforeach
						</tbody>
					</table>
					<div class="row mt-5 justify-content-center">
						<div class="col-md-6">
							<iframe src="{{ $link }}" id="pdf_display_frame" width="100%" height="600px"></iframe>
						</div>
					</div>
					@elseif($param == 'attendance')
						<div class="row justify-content-center">
							<div class="col-md-12">
								<dl class="row mb-0">
									<dd class="col-sm-3">Note</dd>
									<dt class="col-sm-9">
										@if($type == 'Check In')
											{{ $project->in_note }}
										@else
											{{ $project->out_note }}
										@endif
									</dt>
									<dd class="col-sm-3">Proof</dd>
									<dt class="col-sm-9">
										@if($type == 'Check In')
											<a data-magnify="gallery" data-src="" data-caption="{{ $project->in_note }}" data-group="a" href="{{ $project->inImage() }}"><img src="{{ $project->inImage() }}" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>
										@else
											<a data-magnify="gallery" data-src="" data-caption="{{ $project->out_note }}" data-group="a" href="{{ $project->outImage() }}"><img src="{{ $project->outImage() }}" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>
										@endif
									</dt>
									<dd class="col-sm-3">Time</dd>
									<dt class="col-sm-9">
										@if($type == 'Check In')
											{{ $project->in_time }}
										@else
											{{ $project->out_time }}
										@endif
									</dt>
									<dd class="col-sm-3">Location</dd>
									<dt class="col-sm-9">
										@if($type == 'Check In')
											<a href="https://maps.google.com/maps?q={{ $project->in_latitude .','. $project->in_longitude }}" class="btn btn-info btn-sm" target="_blank">See location</a>
										@else
											<a href="https://maps.google.com/maps?q={{ $project->out_latitude.','.$project->out_longitude }}" class="btn btn-info btn-sm" target="_blank">See location</a>
										@endif
									</dt>
								</dl>
							</div>
						</div>
					@elseif($param == 'sample')
					<div class="row justify-content-center">
						<div class="col-md-12">
							<dl class="row mb-0">
								<dd class="col-sm-3">Note</dd>
								<dt class="col-sm-9">
									{{$project->note}}
								</dt>
								<dd class="col-sm-3">Sent Date</dd>
								<dt class="col-sm-9">
									{{$project->sent_date}}
								</dt>
								<dd class="col-sm-3">Return Date</dd>
								<dt class="col-sm-9">
									{{$project->return_date}}
								</dt>
								<dd class="col-sm-3">Proof</dd>
								<dt class="col-sm-9">
									<a data-magnify="gallery" data-src="" data-caption="{{ $project->return_proof }}" data-group="a" href="{{ $project->attachment() }}"><img src="{{ $project->attachment() }}" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>
								</dt>
							</dl>
						</div>
						<table class="table table-bordered mt-5">
							<thead class="table-secondary">
								<tr class="text-center">
									<th>Product</th>
									<th>Qty</th>
									<th>Unit</th>
									<th>Size</th>
								</tr>
							</thead>
							<tbody>
							@foreach($project->sampleProduct as $sp)
								<tr class="text-center">
									<td>{{ $sp->product->name() }}</td>
									<td>{{ $sp->qty }}</td>
									<td>{{ $sp->unit() }}</td>
									<td>{{ $sp->size() }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
						
						<iframe src="{{$link}}" id="pdf_display_frame" width="80%" class="mt-5" height="700px"></iframe>
					
					</div>
					@elseif($param == 'sales_order_revision')
						<div class="row justify-content-center">
							<div class="col-md-12">
								<h5>Reason edit : <i>{{ $projectTemp->reason }}</i></h5>
								<div class="alert alert-warning alert-styled-left alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
									<span class="font-weight-semibold">IMPORTANT INFO!</span> Please check cash & bank / journal if this Sales Order had changes to it's product price and that product was on delivery by search using it's delivery number. Different prices between journal delivery and this Sales Order might bring up unmatch nominal in A/R Report.
							    </div>
							</div>
							<div class="col-md-6 mt-3">
								<h3>Old Sales Main Information</h3>
								<hr>
								<dl class="row mb-0">
									<dd class="col-sm-4">No.</dd>
									<dt class="col-sm-8">{{ $project->code }}</dt>
									<dd class="col-sm-4">User</dd>
									<dt class="col-sm-8">{{ $project->user->name }}</dt>
									<dd class="col-sm-4">Sales</dd>
									<dt class="col-sm-8">{{ $project->sales->name }}</dt>
									<dd class="col-sm-4">Address</dd>
									<dt class="col-sm-8">{{ $project->address }}</dt>
									<dd class="col-sm-4">Note</dd>
									<dt class="col-sm-8">{{ $project->note }}</dt>
									<dd class="col-sm-4">Marketing by</dd>
									<dt class="col-sm-8">{{ $project->marketing ? $project->marketing->name : '-' }}</dt>
									<dd class="col-sm-4">Approved by</dd>
									<dt class="col-sm-8">{{ $project->approved ? $project->approved->name : '-' }}</dt>
									<dd class="col-sm-4">Delivery Cost</dd>
									<dt class="col-sm-8">{{ number_format($project->delivery_cost,0,',','.') }}</dt>
									<dd class="col-sm-4">Cutting Cost</dd>
									<dt class="col-sm-8">{{ number_format($project->cutting_cost,0,',','.') }}</dt>
									<dd class="col-sm-4">Misc Cost</dd>
									<dt class="col-sm-8">{{ number_format($project->misc_cost,0,',','.') }}</dt>
									<dd class="col-sm-4">Misc Note</dd>
									<dt class="col-sm-8">{{ $project->misc_note }}</dt>
									<dd class="col-sm-4">Currency</dd>
									<dt class="col-sm-8">{{ $project->currency->name }}</dt>
									<dd class="col-sm-4">Currency Rate</dd>
									<dt class="col-sm-8">{{ number_format($project->currency_rate,0,',','.') }}</dt>
									<dd class="col-sm-4">PPN Cost</dd>
									<dt class="col-sm-8">{{ $project->ppn_cost() }}</dt>
									<dd class="col-sm-4">Middleman</dd>
									<dt class="col-sm-8">{{ $project->mid_yes_no() }}</dt>
									<dd class="col-sm-4">Middleman Type</dd>
									<dt class="col-sm-8">{{ $project->mid_type() }}</dt>
									<dd class="col-sm-4">Middleman in Number</dd>
									<dt class="col-sm-8">{{ $project->mid_fee }}</dt>
									<dd class="col-sm-4">Middleman Note</dd>
									<dt class="col-sm-8">{{ $project->mid_note }}</dt>
								</dl>
								<h3>Old Sales Details Products</h3>
								<hr>
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th>Product</th>
											<th>Qty</th>
											<th>Unit</th>
											<th>Price/Unit (Excld.PPN)</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
									@foreach($project->projectSaleProduct as $ps)
										@php
											$price = $ps->best_price ? $ps->best_price : ($ps->recommended_price ? $ps->recommended_price : $ps->price);
										@endphp
										<tr class="text-center">
											<td>{{ $ps->product->name() }}</td>
											<td>{{ $ps->qty }}</td>
											<td>{{ $ps->unit() }}</td>
											<td>{{ number_format($price,0,',','.') }}</td>
											<td>{{ number_format($price * $ps->qty,0,',','.') }}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							</div>
							<div class="col-md-6 mt-3">
								<h3>New Sales Main Information</h3>
								<hr>
								<dl class="row mb-0">
									<dd class="col-sm-4">No.</dd>
									<dt class="col-sm-8">{{ $projectTemp->code }}</dt>
									<dd class="col-sm-4">User</dd>
									<dt class="col-sm-8">{{ $projectTemp->user->name }}</dt>
									<dd class="col-sm-4">Sales</dd>
									<dt class="col-sm-8">{{ $projectTemp->sales->name }}</dt>
									<dd class="col-sm-4">Address</dd>
									<dt class="col-sm-8">{{ $projectTemp->address }}</dt>
									<dd class="col-sm-4">Note</dd>
									<dt class="col-sm-8">{{ $projectTemp->note }}</dt>
									<dd class="col-sm-4">Marketing by</dd>
									<dt class="col-sm-8">{{ $projectTemp->marketing ? $projectTemp->marketing->name : '-' }}</dt>
									<dd class="col-sm-4">Approved by</dd>
									<dt class="col-sm-8">{{ $projectTemp->approved ? $projectTemp->approved->name : '-' }}</dt>
									<dd class="col-sm-4">Delivery Cost</dd>
									<dt class="col-sm-8">{{ number_format($projectTemp->delivery_cost,0,',','.') }}</dt>
									<dd class="col-sm-4">Cutting Cost</dd>
									<dt class="col-sm-8">{{ number_format($projectTemp->cutting_cost,0,',','.') }}</dt>
									<dd class="col-sm-4">Misc Cost</dd>
									<dt class="col-sm-8">{{ number_format($projectTemp->misc_cost,0,',','.') }}</dt>
									<dd class="col-sm-4">Misc Note</dd>
									<dt class="col-sm-8">{{ $projectTemp->misc_note }}</dt>
									<dd class="col-sm-4">Currency</dd>
									<dt class="col-sm-8">{{ $projectTemp->currency->name }}</dt>
									<dd class="col-sm-4">Currency Rate</dd>
									<dt class="col-sm-8">{{ number_format($projectTemp->currency_rate,0,',','.') }}</dt>
									<dd class="col-sm-4">PPN Cost</dd>
									<dt class="col-sm-8">{{ $projectTemp->ppn_cost() }}</dt>
									<dd class="col-sm-4">Middleman</dd>
									<dt class="col-sm-8">{{ $projectTemp->mid_yes_no() }}</dt>
									<dd class="col-sm-4">Middleman Type</dd>
									<dt class="col-sm-8">{{ $projectTemp->mid_type() }}</dt>
									<dd class="col-sm-4">Middleman in Number</dd>
									<dt class="col-sm-8">{{ $projectTemp->mid_fee }}</dt>
									<dd class="col-sm-4">Middleman Note</dd>
									<dt class="col-sm-8">{{ $projectTemp->mid_note }}</dt>
								</dl>
								<h3>New Sales Details Products</h3>
								<hr>
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th>Product</th>
											<th>Qty</th>
											<th>Unit</th>
											<th>Price/Unit (Excld.PPN)</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
									@foreach($projectTemp->projectSaleProductTemp as $ps)
										@php
											$price = $ps->best_price ? $ps->best_price : ($ps->recommended_price ? $ps->recommended_price : $ps->price);
										@endphp
										<tr class="text-center">
											<td>{{ $ps->product->name() }}</td>
											<td>{{ $ps->qty }}</td>
											<td>{{ $ps->unit() }}</td>
											<td>{{ number_format($price,0,',','.') }}</td>
											<td>{{ number_format($price * $ps->qty,0,',','.') }}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					@elseif($param == 'project_from_stock')
						
						<div class="row justify-content-center">
							<div class="col-md-9 mt-3">
								<h2 class="text-center bg-primary">Sales From Stock No. {{ $project->projectSale->code }}</h2>
								<h4>Note : {{ $project->note }}</h4>
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th rowspan="2">No.</th>
											<th rowspan="2">Product</th>
											<th rowspan="2">Qty</th>
											<th colspan="2">Price (Excl.Tax)</th>
											<th colspan="2">Variation (%)</th>
										</tr>
										<tr class="text-center">
											<th>Sell</th>
											<th>Buy</th>
											<th>(%)</th>
											<th>(Nominal)</th>
										</tr>
									</thead>
									<tbody>
										@php
											$totalsell = 0;
											$totalbuy = 0;
										@endphp
										@foreach($project->projectFromStockProduct as $key => $ppp)
											@if($ppp->unit == '2' || $ppp->unit == '3')
												@php
													$buyprice = 0;
													$sellprice = 0;
													$m2 = (( $ppp->product->type->length * $ppp->product->type->width ) / 10000) * $ppp->product->carton_pcs;
													
													$sellprice = $ppp->qty * $ppp->sellPrice();
													
													if($m2 < 1.1 && $ppp->product->type->category->parent()->id !== 18){
														$buyprice = $ppp->buyprice() * $ppp->qty;
													}else{
														if($m2 < 1.1 && date('Y-m',strtotime($project->created_at)) < '2022-06' && $ppp->product->type->category->parent()->id == 18){
															$buyprice = $ppp->buyprice() * $ppp->qty;
														}else{
															$buyprice = $ppp->buyprice() * $ppp->qty * $m2;
														}
													}
													
													$totalsell += $sellprice;
													$totalbuy += $buyprice;
												@endphp
											<tr>
												<td>{{ $key + 1 }}</td>
												<td>{{ $ppp->product->name() }} </td>
												<td class="text-center">{{ $ppp->qty * $m2 }} M<sup>2</sup></td>
												<td class="text-right">
													{{ number_format($sellprice,0,',','.') }}
												</td>
												<td class="text-right">
													{{ number_format($buyprice,0,',','.') }}
												</td>
												<td class="text-right">
													{{ $sellprice ? number_format($buyprice / $sellprice * 100,2,',','.') : '0' }}
												</td>
												<td class="text-right">
													@if(($sellprice - $buyprice) > 0)
														<button class="btn btn-success">{{ number_format($sellprice - $buyprice,0,',','.') }}</button>
													@else
														<button class="btn btn-danger">{{ number_format($sellprice - $buyprice,0,',','.') }}</button>
													@endif
												</td>
											</tr>
											@else
												@php
													$buyprice = 0;
													$sellprice = 0;
													
													$buyprice = $ppp->buyPrice() * $ppp->qty;
													
													$sellprice = $ppp->sellPrice() * $ppp->qty;
													
													$totalsell += $sellprice;
													$totalbuy += $buyprice;
												@endphp
											<tr>
												<td>{{ $key + 1 }}</td>
												<td>{{ $ppp->product->name() }}</td>
												<td class="text-center">{{ $ppp->qty }} Pcs</td>
												<td class="text-right">
													{{ number_format($sellprice, 0, ',', '.') }}
												</td>
												<td class="text-right">
													{{ number_format($buyprice, 0, ',', '.') }}
												</td>
												<td class="text-right">
													{{ $sellprice ? number_format($buyprice / $sellprice * 100,2,',','.') : '0' }}
												</td>
												<td class="text-right">
													@if(($sellprice - $buyprice) > 0)
														<button class="btn btn-success">{{ number_format($sellprice - $buyprice,0,',','.') }}</button>
													@else
														<button class="btn btn-danger">{{ number_format($sellprice - $buyprice,0,',','.') }}</button>
													@endif
												</td>
											</tr>
											@endif
										@endforeach
										<tr>
											<td class="text-right" colspan="3">TOTAL</td>
											<td class="text-right">
												{{ number_format($totalsell, 0, ',', '.') }}
											</td>
											<td class="text-right">
												{{ number_format($totalbuy, 0, ',', '.') }}
											</td>
											<td class="text-right">
												{{ $totalsell ? number_format($totalbuy / $totalsell * 100,2,',','.') : '0' }}
											</td>
											<td class="text-right">
												@if(($totalsell - $totalbuy) > 0)
													<button class="btn btn-success">{{ number_format($totalsell - $totalbuy,0,',','.') }}</button>
												@else
													<button class="btn btn-danger">{{ number_format($totalsell - $totalbuy,0,',','.') }}</button>
												@endif
											</td>
										</tr>
									</tbody>
								</table>
								<div class="iframe-loading mt-3" style="background:url();">
									{{-- <iframe src="{{ $link }}" id="pdf_display_frame" width="100%" height="500px"></iframe> --}}
								</div>
							</div>
						</div>
					
					@else
						@if($param == 'sales_invoice')
							<div class="">
								<h3>To Bank {{ $project->coa->name }}</h3>
							</div>
						@endif
					@if($param == 'purchase_order' && in_array(4, session('bo_role')) || $param == 'purchase_order' && in_array(1, session('bo_role')))
							@php
								$ppnpembagi = 1;
								
								if($project->ppn == '1'){
									if(date('Y-m-d',strtotime($project->created_at)) < '2022-04-01'){
										$ppnpembagi = 1.1;
									}else{
										$ppnpembagi = 1.11;
									}
								}
								
							@endphp
						<div class="row justify-content-center">
							<div class="col-md-9 mt-3">
								<h2 class="text-center bg-primary">All Purchase Products No. {{ $project->code }}</h2>
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th rowspan="2">No.</th>
											<th rowspan="2">Product</th>
											<th rowspan="2">Qty</th>
											<th colspan="2">Price (Excl.Tax)</th>
											<th colspan="2">Variation (%)</th>
										</tr>
										<tr class="text-center">
											<th class="{{$project->samplePurchaseProduct ? 'd-none' : ''}}">Sell</th>
											<th>Buy ({{$project->ppn == '1' ? 'PPN' : 'Non PPN'}})</th>
											<th>(%)</th>
											<th>(Nominal)</th>
											<th>(%)</th>
										</tr>
									</thead>
									<tbody>
										@php
											$totalsell = 0;
											$totalbuy = 0;
										@endphp
										@if ($project->projectPurchaseProduct)
											@foreach($project->projectPurchaseProduct as $key => $ppp)
												@if($ppp->unit == '2' || $ppp->unit == '3')
													@php
														$buyprice = 0;
														$sellprice = 0;
														$m2 = (( $ppp->product->type->length * $ppp->product->type->width ) / 10000) * $ppp->product->carton_pcs;
														
														$sellprice = $ppp->qty * $ppp->sellPrice();
														
														if($m2 < 1.1 && $ppp->product->type->category->parent()->id !== 18){
															if($project->currency_id !== '5'){
																$buyprice = (($ppp->price * $project->currency_rate) / $ppnpembagi) * $ppp->qty;
															}else{
																$buyprice = ($ppp->price / $ppnpembagi) * $ppp->qty;
															}
														}else{
															if($m2 < 1.1 && date('Y-m',strtotime($project->created_at)) < '2022-06' && $ppp->product->type->category->parent()->id == 18){
																if($project->currency_id !== '5'){
																	$buyprice = (($ppp->price * $project->currency_rate) / $ppnpembagi) * $ppp->qty;
																}else{
																	$buyprice = ($ppp->price / $ppnpembagi) * $ppp->qty;
																}
															}else{
																if($project->currency_id !== '5'){
																	$buyprice = (($ppp->price * $project->currency_rate) / $ppnpembagi) * $ppp->qty * $m2;
																}else{
																	$buyprice = ($ppp->price / $ppnpembagi) * $ppp->qty * $m2;
																}
															}
														}
														
														$totalsell += $sellprice;
														$totalbuy += $buyprice;
													@endphp
												<tr>
													<td>{{ $key + 1 }}</td>
													<td>{{ $ppp->product->name() }}</td>
													<td class="text-center">{{ $ppp->qty * $m2 }} M<sup>2</sup></td>
													<td class="text-right">
														{{ number_format(isset($project->project) && $project->project->discount > 0 ? ($sellprice - $project->project->discount) : $sellprice ,2,',','.') }}
													</td>
													<td class="text-right">
														{{ number_format($buyprice,2,',','.') }}
													</td>
													<td class="text-right">
														{{ $sellprice ? number_format($buyprice / $sellprice * 100,2,',','.') : '0' }}
													</td>
													<td class="text-right">
														@if(($sellprice - $buyprice) > 0)
															<button class="btn btn-success">{{ number_format($sellprice - $buyprice,0,',','.') }}</button>
														@else
															<button class="btn btn-danger">{{ number_format($sellprice - $buyprice,0,',','.') }}</button>
														@endif
													</td>
												</tr>
												@else
													@php
														$buyprice = 0;
														$sellprice = 0;
														if($project->currency_id !== '5'){
															$buyprice = (($ppp->price * $project->currency_rate) / $ppnpembagi) * $ppp->qty;
														}else{
															$buyprice = ($ppp->price / $ppnpembagi) * $ppp->qty;
														}
														
														$sellprice = $ppp->sellPrice() * $ppp->qty;
														
														$totalsell += $sellprice;
														$totalbuy += $buyprice;
													@endphp
												<tr>
													<td>{{ $key + 1 }}</td>
													<td>{{ $ppp->product->name() }}</td>
													<td class="text-center">{{ $ppp->qty }} Pcs</td>
													<td class="text-right">
														{{ number_format(isset($project->project) && $project->project->discount > 0 ? ($sellprice - $project->project->discount) : $sellprice, 2, ',', '.') }}
													</td>
													<td class="text-right">
														{{ number_format($buyprice, 2, ',', '.') }}
													</td>
													<td class="text-right">
														{{ $sellprice ? number_format($buyprice / $sellprice * 100,2,',','.') : '0' }}
													</td>
													<td class="text-right">
														@if(($sellprice - $buyprice) > 0)
															<button class="btn btn-success">{{ number_format($sellprice - $buyprice,2,',','.') }}</button>
														@else
															<button class="btn btn-danger">{{ number_format($sellprice - $buyprice,2,',','.') }}</button>
														@endif
													</td>
												</tr>
												@endif
											@endforeach
										@else
											@foreach($project->samplePurchaseProduct as $key => $spp)
											@if($spp->unit == '2' || $spp->unit == '3')
												@php
													$buyprice = 0;
													$sellprice = 0;
													$m2 = (( $spp->product->type->length * $spp->product->type->width ) / 10000) * $spp->product->carton_pcs;
													
													$sellprice = $spp->qty * $spp->price;
													
													if($m2 < 1.1 && $spp->product->type->category->parent()->id !== 18){
														if($project->currency_id !== '5'){
															$buyprice = (($spp->price * $project->currency_rate) / $ppnpembagi) * $spp->qty;
														}else{
															$buyprice = ($spp->price / $ppnpembagi) * $spp->qty;
														}
													}else{
														if($m2 < 1.1 && date('Y-m',strtotime($project->created_at)) < '2022-06' && $spp->product->type->category->parent()->id == 18){
															if($project->currency_id !== '5'){
																$buyprice = (($spp->price * $project->currency_rate) / $ppnpembagi) * $spp->qty;
															}else{
																$buyprice = ($spp->price / $ppnpembagi) * $spp->qty;
															}
														}else{
															if($project->currency_id !== '5'){
																$buyprice = (($spp->price * $project->currency_rate) / $ppnpembagi) * $spp->qty * $m2;
															}else{
																$buyprice = ($spp->price / $ppnpembagi) * $spp->qty * $m2;
															}
														}
													}
													
													$totalsell += $sellprice;
													$totalbuy += $buyprice;
												@endphp
											<tr>
												<td>{{ $key + 1 }}</td>
												<td>{{ $spp->product->name() }}</td>
												<td class="text-center">{{ $spp->qty * $m2 }} M<sup>2</sup></td>
												<td class="text-right">
													{{ number_format($sellprice,2,',','.') }}
												</td>
												<td class="text-right">
													{{ number_format($buyprice,2,',','.') }}
												</td>
												<td class="text-right">
													{{ $sellprice ? number_format($buyprice / $sellprice * 100,2,',','.') : '0' }}
												</td>
												<td class="text-right">
													@if(($sellprice - $buyprice) > 0)
														<button class="btn btn-success">{{ number_format($sellprice - $buyprice,2,',','.') }}</button>
													@else
														<button class="btn btn-danger">{{ number_format($sellprice - $buyprice,2,',','.') }}</button>
													@endif
												</td>
											</tr>
											@else
												@php
													$buyprice = 0;
													$sellprice = 0;
													if($project->currency_id !== '5'){
														$buyprice = (($spp->price * $project->currency_rate) / $ppnpembagi) * $spp->qty;
													}else{
														$buyprice = ($spp->price / $ppnpembagi) * $spp->qty;
													}
													
													$sellprice = $spp->price * $spp->qty;
													
													$totalsell += $sellprice;
													$totalbuy += $buyprice;
												@endphp
											<tr>
												<td>{{ $key + 1 }}</td>
												<td>{{ $spp->product->name() }}</td>
												<td class="text-center">{{ $spp->qty }} Pcs</td>
												<td class="text-right {{$project->samplePurchaseProduct ? 'd-none' : ''}}">Sell</th>">
													{{ number_format($sellprice, 0, ',', '.') }}
												</td>
												<td class="text-right">
													{{ number_format($buyprice, 0, ',', '.') }}
												</td>
												<td class="text-right">
													{{ $sellprice ? number_format($buyprice / $sellprice * 100,2,',','.') : '0' }}
												</td>
												<td class="text-right">
													@if(($sellprice - $buyprice) > 0)
														<button class="btn btn-success">{{ number_format($sellprice - $buyprice,2,',','.') }}</button>
													@else
														<button class="btn btn-danger">{{ number_format($sellprice - $buyprice,2,',','.') }}</button>
													@endif
												</td>
											</tr>
											@endif
											@endforeach
										@endif
										
										<tr>
											<td class="text-right" colspan="4">TOTAL</td>
											<td class="text-right {{$project->samplePurchaseProduct ? 'd-none' : ''}}">
												{{ number_format($totalsell, 0, ',', '.') }}
											</td>
											<td class="text-right">
												{{ number_format($totalbuy, 0, ',', '.') }}
											</td>
											<td class="text-right">
												{{ $totalsell ? number_format($totalbuy / $totalsell * 100,2,',','.') : '0' }}
											</td>
											<td class="text-right">
												@if(($totalsell - $totalbuy) > 0)
													<button class="btn btn-success">{{ number_format($totalsell - $totalbuy,2,',','.') }}</button>
												@else
													<button class="btn btn-danger">{{ number_format($totalsell - $totalbuy,2,',','.') }}</button>
												@endif
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						@endif
						<div class="row justify-content-center">
							@if($param == 'sales_order')
								@if($project->is_closed)
								<div class="col-md-12 text-left" style="font-size:30px;">
									<dl class="row mb-0">
										<dd class="col-sm-3">Date</dd>
										<dt class="col-sm-9">{{ date('d M Y',strtotime($project->date_closed)) }}</dt>
										<dd class="col-sm-3">Reason</dd>
										<dt class="col-sm-9">{{ $project->reason_closed }}</dt>
									</dl>
								</div>
								@endif
							@endif
							<div class="col-md-{{ $link2 !== '' || $project !== '' ? '9' : '12' }} text-center">
								<hr>
								<!-- <div class="iframe-loading" style="background:url({{ url('website/loader.gif') }});"> -->
								<div class="iframe-loading" style="background:url();">
									<iframe src="{{ $link }}" id="pdf_display_frame" width="100%" height="500px"></iframe>
								</div>
							</div>
							@if($link2 !== '')
							<div class="col-md-9 mt-3 text-center">
								<hr>
								<!-- <div class="iframe-loading" style="background:url({{ url('website/loader.gif') }});"> -->
								<div class="iframe-loading" style="background:url();">
									<iframe src="{{ $link2 }}" id="pdf_display_frame" width="100%" height="500px"></iframe>
								</div>
							</div>
							@endif
							@if($link3 !== '')
							<div class="col-md-9 mt-3 text-center">
								<hr>
								<!-- <div class="iframe-loading" style="background:url({{ url('website/loader.gif') }});"> -->
								<div class="iframe-loading" style="background:url();">
									<iframe src="{{ $link3 }}" id="pdf_display_frame" width="100%" height="500px"></iframe>
								</div>
							</div>
							@endif
							@if($revisi && $param == 'purchase_order')
								<div class="col-md-9 mt-3 text-center">
									<h3>This PO is a revision with details information : {{ $revisi->description }}</h3>
								</div>
								@if($cb)
								<div class="col-md-9 mt-3 text-center">
									This PO has journal connection with details as follows;
									<ul>
										@foreach($cb as $row)
											<li>Cash & Bank Code <b>{{ $row->code }}</b>, description : <b>{{ $row->description }}</b></li>
										@endforeach
									</ul>
								</div>
								@endif
							@endif
							
							@if($project !== '' && $param !== 'purchase_order' && $param !== 'sample_order')
								@if($param == 'sales_bill')
								
								@elseif($param == 'attendance')
								
								@elseif($param == 'sample')

								@else
								<div class="col-md-9 mt-3 text-center">
									<hr>
									@if(explode('.',$project->attachment())[2] == 'pdf')
										<a href="{{ $project->attachment() }}" target="_blank" class="btn btn-primary" style="font-size:40px;"><i class="icon-search4 icon-3x"></i> SHOW PROOF</a>
									@else
										<img src="{{ $project->attachment() }}" width="100%">
									@endif
								</div>
								@endif
							@endif
						</div>
					@endif
				@endif
			</div>
		</div>
	</div>

<script>
	$(function() {
		@if($link2 !== '')
			$('.sidebar-main-toggle').click();
		@endif
	});
	@if($param == 'report')
		/* function goApprove(val){
			window.location.href = "{{ $link }}?step-19=1#step-19";
		  
			return false;
		} */
		
		function goApprove(val){
		   $.ajax({
			 url: '{{ url("admin/approval/project") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: { val:val },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.content');
			 },
			 success: function(response) {
				 if(response.status == '200'){
					 location.reload();
				 }
			 }
		  });
		  
		  return false;
		}
		
		function goRejectReport(val){
			
			var notyConfirm = new Noty({
				 theme: 'limitless',
				 text: '<h6 class="font-weight-bold mb-3">Are sure you want to reject?</h6><label>Rejected data can no longer be changed.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="reject_reason" id="reject_reason" class="form-control" placeholder="Enter why you reject this project?"></div></div>',
				 timeout: false,
				 modal: true,
				 layout: 'center',
				 closeWith: 'button',
				 type: 'confirm',
				 buttons: [
					Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
					   notyConfirm.close();
					}),
					Noty.button('<i class="icon-check"></i>', 'btn bg-success ml-1', function() {
						if($('#reject_reason').val() !== ''){
							$.ajax({
							 url: '{{ url("admin/approval/reject") }}',
							 type: 'POST',
							 dataType: 'JSON',
							 data: { val : val,reason : $('#reject_reason').val() },
							 headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							 },
							 beforeSend: function() {
								loadingOpen('.content');
							 },
							 success: function(response) {
								 if(response.status == '200'){
									 location.reload();
								 }
							 }
						  });
						}else{
							notif('error', 'bg-warning', 'Please explain why this project should be rejected?');
						}
					})
				 ]
			}).show();
		  
			return false;
		}
	@else
		@if($param == 'purchase_request')
			function goApprove(val){
				var coa_id = $('#coa_id').length > 0 ? $('#coa_id').val() : 332;
				$.ajax({
					 url: '{{ url("admin/approval/project") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: { val:val, titlepr : $('#titlepr').val(), branchpr : $('#branch').val(), coapr : coa_id, notepr : $('#notepr').val(), nominalpr : $('#nominalpr').val() },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('.content');
					 },
					 success: function(response) {
						 if(response.status == '200'){
							 if(response.cb){
								 window.location.href = "http://127.0.0.1:8000/admin/finance/cash_bank?mode_edit=true&id=" + response.cb;
							 }else{
								 location.reload();
							 }
						 }
					 }
				});
			  
				return false;
			}
		@elseif($param == 'project_main_payment')
			function goApprove(val){
			   $.ajax({
				 url: '{{ url("admin/approval/project") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { val:val, tanggal : $('#tanggal').val(), coa : $('#coa').val(), nominal : $('#nominal').val() },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.content');
				 },
				 success: function(response) {
					 if(response.status == '200'){
						 location.reload();
					 }
				 }
			  });
			  
			  return false;
			}
		@elseif($param == 'receivable_payment')
			function goApprove(val){
			   $.ajax({
				 url: '{{ url("admin/approval/project") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { val:val, date : $('#date').val(), coa : $('#coa').val(), nominal : $('#nominal').val(), note : $('#note').val(), branch_rp:$("#branch_rp").val() },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.content');
				 },
				 success: function(response) {
					 if(response.status == '200'){
						 location.reload();
					 }
				 }
			  });
			  
			  return false;
			}
		@else
			function goApprove(val){
			   $.ajax({
				 url: '{{ url("admin/approval/project") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { val:val },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.content');
				 },
				 success: function(response) {
					 if(response.status == '200'){
						 location.reload();
					 }
				 }
			  });
			  
			  return false;
			}
		@endif
	
		function goReject(val){
		   $.ajax({
			 url: '{{ url("admin/approval/reject") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: { val:val },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.content');
			 },
			 success: function(response) {
				 if(response.status == '200'){
					 location.reload();
				 }
			 }
		  });
		  
		  return false;
		}
		
		function goRejectLeave(val){
			
			var notyConfirm = new Noty({
				 theme: 'limitless',
				 text: '<h6 class="font-weight-bold mb-3">Are sure you want to reject?</h6><label>Rejected data can no longer be changed.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="reject_reason" id="reject_reason" class="form-control" placeholder="Enter why you reject this request?"></div></div>',
				 timeout: false,
				 modal: true,
				 layout: 'center',
				 closeWith: 'button',
				 type: 'confirm',
				 buttons: [
					Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
					   notyConfirm.close();
					}),
					Noty.button('<i class="icon-check"></i>', 'btn bg-success ml-1', function() {
						if($('#reject_reason').val() !== ''){
							$.ajax({
							 url: '{{ url("admin/approval/reject") }}',
							 type: 'POST',
							 dataType: 'JSON',
							 data: { val : val,reason : $('#reject_reason').val() },
							 headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							 },
							 beforeSend: function() {
								loadingOpen('.content');
							 },
							 success: function(response) {
								 if(response.status == '200'){
									 location.reload();
								 }
							 }
						  });
						}else{
							notif('error', 'bg-warning', 'Please explain why this project should be deleted?');
						}
					})
				 ]
			}).show();
		  
			return false;
		}
	@endif
	
</script>