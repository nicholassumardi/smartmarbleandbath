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
<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Budgeting Project Detail</span>
                </h4>
            </div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/sales/budgeting_project') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
				</div>
			</div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Sales</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Budgeting</a>
                    <span class="breadcrumb-item active">Budgeting Project</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
				<div class="float-right">
					<a class="btn btn-warning btm-sm mr-2" data-toggle="collapse" href="#full-information"><i class="icon-newspaper"></i> Full Information</a>
					<button type="button" class="btn btn-primary btm-sm mr-2" id="editEstimation" onclick="editEstimation()"><i class="icon-pencil7"></i> Edit Estimation</button>
					<button type="button" class="btn btn-warning btm-sm d-none" id="cancelEditEstimation" onclick="cancelEditEstimation()"><i class="icon-cancel-square"></i> Cancel Edit</button>
				</div>
                <h6 class="text-uppercase font-weight-bold">
                    PT. PERWIRA TAMARAYA ABADI
				</h6>
				<div class="font-weight-bold">
                    BUDGET PLAN PROJECTION
				</div>
				<div class="font-weight-bold font-italic">
					Project Name : {{ $budget->name.' CUST. '.$budget->project->customer->name }}
				</div>
				<div class="font-weight-bold font-italic">
					Project No : {{ $budget->project->code }}
				</div>
				<div class="font-weight-bold">
					Branch : {{ $budget->branch() }}
				</div>
				<div class="row">
					<div class="col-md-12 mt-2">
						<div class="collapse p-2" id="full-information" style="background-color:#faf4b4 !important;">
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
														<td>: {{ date('M Y',strtotime($budget->month_start)).' - '.date('M Y',strtotime($budget->month_end)) }}</td>
													</tr>
													<tr>
														<td>Import Duty</td>
														<td>: {{ number_format($budget->percent_import,2,',','.') }}%</td>
													</tr>
													<tr>
														<td>Safe Guard</td>
														<td>: {{ number_format($budget->percent_safe,2,',','.') }}%</td>
													</tr>
													<tr>
														<td>PPN</td>
														<td>: {{ number_format($budget->percent_ppn,2,',','.') }}%</td>
													</tr>
													<tr>
														<td>PPH</td>
														<td>: {{ number_format($budget->percent_pph,2,',','.') }}%</td>
													</tr>
													<tr>
														<td>Fee MKJ</td>
														<td>: {{ number_format($budget->percent_mkj,2,',','.') }}%</td>
													</tr>
													<tr>
														<td>Fee PTA</td>
														<td>: {{ number_format($budget->percent_pta,2,',','.') }}%</td>
													</tr>
													<tr>
														<td>Fee Middleman</td>
														<td>: {{ number_format($budget->percent_mid,2,',','.') }}%</td>
													</tr>
													<tr>
														<td>Sales Commission</td>
														<td>: {{ number_format($budget->percent_scom,2,',','.') }}%</td>
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
														<td>: {{ $budget->currency->name }}</td>
													</tr>
													<tr>
														<td>Exchange Rate</td>
														<td>: {{ number_format($budget->help_exchange_rate,2,',','.') }}</td>
													</tr>
													<tr>
														<td>Number of Container</td>
														<td>: {{ number_format($budget->help_container_no,2,',','.') }}</td>
													</tr>
													<tr>
														<td>LS Cost (IDR)</td>
														<td>: {{ number_format($budget->help_ls_cost * $budget->help_exchange_rate,2,',','.') }}</td>
													</tr>
													<tr>
														<td>Product Cost (IDR)</td>
														<td>: {{ number_format($budget->help_product_cost * $budget->help_exchange_rate,2,',','.') }}</td>
													</tr>
													<tr>
														<td>Quantity of Container</td>
														<td>: {{ number_format($budget->help_container_qty,2,',','.') }}</td>
													</tr>
													<tr>
														<td>Freight Cost (IDR)</td>
														<td>: {{ number_format($budget->help_freight_cost * $budget->help_exchange_rate,2,',','.') }}</td>
													</tr>
													<tr>
														<td>EMKL (IDR)</td>
														<td>: {{ number_format($budget->help_emkl_cost,2,',','.') }}</td>
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
												@foreach($budget->budgetingProjectProduct as $row)
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
								<th width="25%">Remarks by {{ $budget->user->name }} :</th>
								<th class="font-italic">
									{{ $budget->remarks }} 
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
							
							foreach($budget->budgetingProjectDetail->where('group_count','1')->all() as $row){
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
							
							foreach($budget->budgetingProjectDetail->where('group_count','2')->all() as $row){
								$totalbuying += $row->nominal;
								$totalbuyingestimation += $row->estimation;
							}
							
							foreach($budget->budgetingProjectDetail->where('group_count','3')->all() as $row){
								$totalcogs += $row->nominal;
								$totalcogsestimation += $row->estimation;
							}
							
							foreach($budget->budgetingProjectDetail->where('group_count','4')->all() as $row){
								$totalmkt += $row->nominal;
								$totalmktestimation += $row->estimation;
							}
							
							foreach($budget->budgetingProjectDetail->where('group_count','5')->all() as $row){
								$totalcri += $row->nominal;
								$totalcriestimation += $row->estimation;
							}
							
							$totalcogs += $totalbuying;
							$totalcogsestimation += $totalbuyingestimation;
						@endphp
						<input id="percent_import" type="hidden" value="{{ $budget->percent_import }}">
						<input id="percent_safe" type="hidden" value="{{ $budget->percent_safe }}">
						<input id="percent_ppn" type="hidden" value="{{ $budget->percent_ppn }}">
						<input id="percent_pph" type="hidden" value="{{ $budget->percent_pph }}">
						<input id="percent_mkj" type="hidden" value="{{ $budget->percent_mkj }}">
						<input id="percent_pta" type="hidden" value="{{ $budget->percent_pta }}">
						<input id="percent_mid" type="hidden" value="{{ $budget->percent_mid }}">
						<input id="percent_scom" type="hidden" value="{{ $budget->percent_scom }}">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="text-right" colspan="3">Estimation Title : </th>
										<th class="font-italic" colspan="2">
											<span class="estimation-text">{{ $budget->estimation_name }}</span>
											<input type="text" class="form-control form-control-sm d-none" id="estimation-name" name="estimation-name" value="{{ $budget->estimation_name }}">
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
									@foreach($budget->budgetingProjectDetail->where('group_count','1')->all() as $row)
									<tr>
										<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
										<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($row->nominal/$totalrevenue)*100,2) }}%</td>
										<td class="text-right estimation">
											<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
											<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);safeGuard();">
										</td>
										<td class="text-right estimation">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
									</tr>
									@endforeach
									<tr class="font-italic parent">
										<td>Total Revenue</td>
										<td class="text-right">{{ number_format($totalrevenue,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalrevenue/$totalrevenue)*100,2) }}%</td>
										<td class="text-right estimation">{{ number_format($totalrevenueestimation,0,',','.') }}</td>
										<td class="text-right estimation">{{ $totalrevenueestimation == 0 ? 0 : round(($totalrevenueestimation/$totalrevenueestimation)*100,2) }}%</td>
									</tr>
									<tr>
										<td colspan="5">COGS</td>
									</tr>
									@foreach($budget->budgetingProjectDetail->where('group_count','2')->all() as $row)
										<tr>
											<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
											<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
											<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($row->nominal/($totalrevenue - $totalservice))*100,2) }}%</td>
											<td class="text-right estimation">
												<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);safeGuard();">
											</td>
											<td class="text-right estimation">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
										</tr>
									@endforeach
									<tr>
										<td colspan="5">Import Cost</td>
									</tr>
									@foreach($budget->budgetingProjectDetail->where('group_count','3')->all() as $row)
										@if($row->coa_id == 292)
											@php 
												$total = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290,288])->sum('nominal');
												$totalestimation = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290,288])->sum('estimation');
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
													<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
												</td>
												<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
											</tr>
										@elseif($row->coa_id == 293)
											@php 
												$total = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
												$totalestimation = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
													<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
												</td>
												<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
											</tr>
										@elseif($row->coa_id == 294)
											@php 
												$total = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
												$totalestimation = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
													<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
												</td>
												<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
											</tr>
										@elseif($row->coa_id == 295)
											@php 
												$total = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
												$totalestimation = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
													<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
												</td>
												<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
											</tr>
										@else
											@php
												$total = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290])->sum('nominal');
												$totalestimation = $budget->budgetingProjectDetail->whereIn('coa_id',[122,290])->sum('estimation');
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
													<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
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
									@foreach($budget->budgetingProjectDetail->where('group_count','4')->all() as $row)
										@if($row->coa_id == 139)
											@php
												$total = $budget->budgetingProjectDetail->whereIn('coa_id',[134])->sum('nominal') + $totalcogs;
												$totalestimation = $budget->budgetingProjectDetail->whereIn('coa_id',[134])->sum('estimation') + $totalcogsestimation;
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
													<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
												</td>
												<td class="text-right estimation">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
											</tr>
										@elseif($row->coa_id == 296 || $row->coa_id == 299)
											@php
												$total = $budget->budgetingProjectDetail->whereIn('coa_id',[284,285])->sum('nominal');
												$totalestimation = $budget->budgetingProjectDetail->whereIn('coa_id',[284,285])->sum('estimation');
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right estimation">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
													<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
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
													<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
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
									@foreach($budget->budgetingProjectDetail->where('group_count','5')->all() as $row)
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
											<input type="text" class="form-control form-control-sm d-none estimation-input" data-id="{{ $row->id }}" value="{{ number_format($row->estimation,0,',','.') }}" data-coa="{{ $row->coa_id }}" data-group="{{ $row->group_count }}" onkeyup="formatRupiah(this);">
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
								<tfoot class="d-none" id="tools">
									<tr>
										<td colspan="3"></td>
										<td><button type="button" class="btn btn-success btm-sm btn-block" id="saveEstimation" onclick="saveEstimation()"><i class="icon-floppy-disk"></i> Save</button></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
						</div>
                    </div>
                </div>	
			</div>
        </div>
    </div>
	
	<script>
		function editEstimation(){
			$('.estimation-text').addClass('d-none');
			$('#editEstimation').addClass('d-none');
			$('.estimation-input').removeClass('d-none');
			$('#estimation-name').removeClass('d-none');
			$('#tools').removeClass('d-none');
			$('#cancelEditEstimation').removeClass('d-none');
		}
		
		function cancelEditEstimation(){
			$('.estimation-text').removeClass('d-none');
			$('#editEstimation').removeClass('d-none');
			$('.estimation-input').addClass('d-none');
			$('#estimation-name').addClass('d-none');
			$('#tools').addClass('d-none');
			$('#cancelEditEstimation').addClass('d-none');
		}
		
		function safeGuard(){
			var total1 = 0, total2 = 0, total3 = parseFloat($('input[data-coa="288"]').val().replaceAll('.','').replaceAll(',','.'));
			var total4 = 0, total5 = 0;
			var percent_import = parseFloat($('#percent_import').val().replaceAll('.','').replaceAll(',','.')) / 100;
			var percent_safe = parseFloat($('#percent_safe').val().replaceAll('.','').replaceAll(',','.')) / 100;
			var percent_ppn = parseFloat($('#percent_ppn').val().replaceAll('.','').replaceAll(',','.')) / 100;
			var percent_pph = parseFloat($('#percent_pph').val().replaceAll('.','').replaceAll(',','.')) / 100;
			var percent_mkj = parseFloat($('#percent_mkj').val().replaceAll('.','').replaceAll(',','.')) / 100;
			var percent_pta = parseFloat($('#percent_pta').val().replaceAll('.','').replaceAll(',','.')) / 100;
			var percent_mid = parseFloat($('#percent_mid').val().replaceAll('.','').replaceAll(',','.')) / 100;
			var percent_scom = parseFloat($('#percent_scom').val().replaceAll('.','').replaceAll(',','.')) / 100;
			
			if($('input[data-coa="122"]').length > 0){
				total1 += parseFloat($('input[data-coa="122"]').val().replaceAll('.','').replaceAll(',','.'));
			}
			
			if($('input[data-coa="290"]').length > 0){
				total2 += parseFloat($('input[data-coa="290"]').val().replaceAll('.','').replaceAll(',','.'));
			}
			
			if($('input[data-coa="284"]').length > 0){
				total4 += parseFloat($('input[data-coa="284"]').val().replaceAll('.','').replaceAll(',','.'));
			}
			
			if($('input[data-coa="285"]').length > 0){
				total5 += parseFloat($('input[data-coa="285"]').val().replaceAll('.','').replaceAll(',','.'));
			}
			
			$('input[data-coa="292"]').val(formatHelper(Math.round((total1 + total2 + total3) * percent_import * 100 / 100).toString()));
			
			$('input[data-coa="293"]').val(formatHelper(Math.round(((total1 + total2 + total3 + parseFloat($('input[data-coa="292"]').val().replaceAll('.','').replaceAll(',','.'))) * percent_safe * 100 / 100).toString())));
			
			var pbidsgfc = total1 + total2 + total3 + parseFloat($('input[data-coa="292"]').val().replaceAll('.','').replaceAll(',','.'));
			
			$('input[data-coa="294"]').val(formatHelper(Math.round(pbidsgfc * percent_ppn * 100 / 100).toString()));
			$('input[data-coa="295"]').val(formatHelper(Math.round(pbidsgfc * percent_pph * 100 / 100).toString()));
			$('input[data-coa="134"]').val(formatHelper(Math.round((total1 + total2) * percent_mkj * 100 / 100).toString()));
			$('input[data-coa="139"]').val(formatHelper(Math.round((total1 + total2 + parseFloat($('input[data-coa="134"]').val().replaceAll('.','').replaceAll(',','.'))) * percent_pta * 100 / 100).toString()));
			$('input[data-coa="299"]').val(formatHelper(Math.round((total4 + total5) * percent_mid * 100 / 100).toString()));
			$('input[data-coa="296"]').val(formatHelper(Math.round((total4 + total5) * percent_scom * 100 / 100).toString()));
			
			var total_rental = 0, total_interest_pm = 0;
			
			$('input[data-group="1"]').each(function() {
				total_interest_pm += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			});
			
			$('input[data-group="2"]').each(function() {
				total_rental += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			});
			
			$('input[data-group="3"]').each(function() {
				total_rental += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			});
			
			total_rental += parseFloat($('input[data-coa="134"]').val().replaceAll('.','').replaceAll(',','.'));
			total_rental += parseFloat($('input[data-coa="139"]').val().replaceAll('.','').replaceAll(',','.'));
			
			$('input[data-coa="111111"]').val(formatHelper(Math.round(total_rental * 0.02).toString()));
			$('input[data-coa="222222"]').val(formatHelper(Math.round(total_rental * 0.02).toString()));
			$('input[data-coa="333333"]').val(formatHelper(Math.round(total_rental * 0.05).toString()));
			$('input[data-coa="444444"]').val(formatHelper(Math.round(total_interest_pm * 0.02).toString()));
			$('input[data-coa="555555"]').val(formatHelper(Math.round(total_rental * 0.03).toString()));
			
		}
		
		function saveEstimation(){
			var idDetail = [], estimationDetail = [];
			var estimationName = $('#estimation-name').val();
			var kosong = false;
			$( ".estimation-input" ).each(function() {
				if($(this).val() == ''){
					kosong = true;
				}
				
				idDetail.push($(this).data('id'));
				estimationDetail.push($(this).val());
			});
			
			if(kosong){
				swalInit.fire('Ooppsss!', 'Please entry all estimation', 'info');
			}else{
				$.ajax({
					 url: '{{ url("admin/sales/budgeting_project/updateEstimation") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: {estimationId : {{ $budget->id }}, estimationName : estimationName, idDetail : idDetail, estimationDetail : estimationDetail},
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						$('#validation_alert').hide();
						$('#validation_content').html('');
						loadingOpen('#reportData');
					 },
					 success: function(response) {
						loadingClose('#reportData');
						if(response.status == 200) {
						   notif('success', 'bg-success', response.message);
						   setTimeout(function(){ location.reload(); }, 2000);
						} else if(response.status == 422) {
						   $('#validation_alert').show();
						   notif('warning', 'bg-warning', 'Validation');
						   
						   $.each(response.error, function(i, val) {
							  $.each(val, function(i, val) {
								 $('#validation_content').append(`
									<li>` + val + `</li>
								 `);
							  });
						   });
						} else {
						   notif('error', 'bg-danger', response.message);
						}
					 },
					 error: function() {
						loadingClose('#reportData');
						swalInit.fire({
						   title: 'Server Error',
						   text: 'Please contact developer',
						   type: 'error'
						});
					 }
				});
			}
		}
		
		function formatHelper(angka){
			var number_string = angka.toString().replaceAll('.', ''),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
		 
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
		 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return rupiah;
		}
	</script>