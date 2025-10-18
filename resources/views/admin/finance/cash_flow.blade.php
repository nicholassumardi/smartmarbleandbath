<script src="{{ url('template/back-office/html_table_to_excel/dist/jquery.table2excel.js') }}"></script>
<style>
	.breaklines
	{
		max-width: 0px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	
	.selected
	{
		background-color:#e68a00 !important;
		color:white !important;
	}
	
	.fab-menu {
		position:fixed !important;
		z-index:999;
		bottom:0px !important;
		right:25px !important;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Budgeting Cash Flow</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn btn-success mr-2" onclick="exportToExcel();"><i class="icon-file-excel"></i> Export to Excel</button>
					<button class="btn btn-warning mr-2"><b id="countSelected">0</b> Selected</button>
					<button type="button" class="btn bg-info btn-labeled mr-2 btn-labeled-left d-none" id="btn-update" onclick="updateDate()">
						<b><i class="icon-calendar2"></i></b> Change Date
					</button>
					<span class="mt-1 mr-2">Filter : </span>
					<form action="#">
						<select class="form-control wmin-200" id="branch" name="branch" onchange="submitFilter()">
							<option value="1" {{ $branch == '1' ? 'selected' : '' }}>PTA</option>
							<option value="2" {{ $branch == '2' ? 'selected' : '' }}>SMB</option>
						</select>
					</form>
					<ul class="pagination pagination-flat pagination-sm justify-content-around">
						@if($month !== date('Y-m') || $branch == '2')
						<li class="page-item"><a href="{{ url('admin/finance/cash_flow') }}" class="page-link"><i class="icon-reset"></i></a></li>
						@endif
						<li class="page-item"><a href="{{ url('admin/finance/cash_flow').'?month='.$before.'&branch='.$branch }}" class="page-link">&larr;</a></li>
						<li class="page-item"><a href="{{ url('admin/finance/cash_flow').'?month='.$before.'&branch='.$branch }}" class="page-link">{{ date("M 'y",strtotime($before)) }}</a></li>
						<li class="page-item active"><a href="{{ url('admin/finance/cash_flow').'?month='.$month }}" class="page-link">{{ date("M 'y",strtotime($month)) }}</a></li>
						<li class="page-item"><a href="{{ url('admin/finance/cash_flow').'?month='.$after.'&branch='.$branch }}" class="page-link">{{ date("M 'y",strtotime($after)) }}</a></li>
						<li class="page-item"><a href="{{ url('admin/finance/cash_flow').'?month='.$after.'&branch='.$branch }}" class="page-link">&rarr;</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Budgeting Cash Flow</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Payment</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
							<span class="font-weight-semibold">Info!</span> Hover/Tap on description to see full information.
						</div>
					</div>
					<div class="col-md-6">
						<div class="alert alert-primary alert-styled-left alert-dismissible blink-notification"">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
							<span class="font-weight-semibold">Info!</span> Red data (Credit) is taken from pending Purchase Request that has <b>APPROVE</b> status.
						</div>
						<div class="alert alert-danger alert-styled-left alert-dismissible blink-notification"">
							<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
							<span class="font-weight-semibold">Important!</span> Press enter if you want to save in Credit Nominal Input.
						</div>
					</div>
				</div>
				<div class="row row-sortable justify-content-center" id="alltable">
					@php
						$noweek = 1;
						$balancedebitweek = 0;
						$balancecreditweek = 0;
					@endphp
					@foreach($weeks as $key => $row)
					@php
						$adadebit = 0;
						$adacredit = 0;
						$totaldebit = 0;
						$totalcredit = 0;
						$balance = 0;
					@endphp
					<div class="col-lg-12 weeklist">
						<div class="card border-primary" id="bagian{{ $noweek }}">
							<div class="card-header text-white header-elements-inline" style="background-color:#26a69a !important;">
								<h6 class="card-title">Week {{ $noweek.' - '.date('M Y',strtotime($month)) }} <i><b>({{ date('j M',strtotime($row[0])).' - '.date('j M',strtotime($row[count($row) - 1])) }})</b></i></h6>
								<div class="header-elements">
									<div class="list-icons">
				                		<a href="javascript:void(0);" class="list-icons-item" onclick="zoomData({{ $noweek }})"><i class="icon-screen-full"></i></a>
				                	</div>
			                	</div>
							</div>
							<div class="card-body" style="max-height:450px !important;overflow: auto;">
								<div class="table-responsive mt-1" id="sourceData{{ $noweek }}">
									<table class="table">
										<thead style="font-size:25px;">
											<tr>
												<th width="50%" style="padding: 0rem 0rem;">Debit</th>
												<th width="50%" style="padding: 0rem 0rem;" class="text-right">Credit</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="padding: 0rem 0rem !important;vertical-align:top;">
													<div class="table-responsive mt-3">
														<table class="table table-bordered table-striped table-data" id="tableDataDebit{{ $noweek }}">
															<thead>
																<tr class="text-center">
																	<th rowspan="2" width="5%">No</th>
																	<th rowspan="2">Description</th>
																	<th rowspan="2" width="5%">Date</th>
																	<th colspan="2">Amount</th>
																</tr>
																<tr class="text-center">
																	<th width="25%">Projected</th>
																	<th width="25%">Real</th>
																</tr>
															</thead>
															<tbody>
																@php
																	$no = 1;
																@endphp
																@if($noweek > 1)
																<tr class="exclude bg-primary">
																	<input type="hidden" data-week="{{ $noweek }}" data-group="1" value="{{ number_format($balancedebitweek,2,',','.') }}">
																	<td class="text-center">{{ $no }}.</td>
																	<td class="breaklines" data-popup="tooltip" title="Balance week before">Balance week before</td>
																	<td class="text-center">{{ date('d/m/y',strtotime($row[0])) }}</td>
																	<td class="text-right" id="balanceweek{{ $noweek }}">{{ number_format($balancedebitweek,2,',','.') }}</td>
																	<td class="text-right">0</td>
																</tr>
																	@php
																		$no++;
																		$totaldebit += $balancedebitweek;
																	@endphp
																@endif
																@foreach($balance_cash_bank as $key => $rowcb)
																	@if(in_array($rowcb['date'],$row))
																		<tr class="exclude">
																			<input type="hidden" value="{{ number_format($rowcb['total'],2,',','.') }}" data-week="{{ $noweek }}" data-group="1">
																			<td class="text-center">{{ $no }}.</td>
																			<td class="breaklines" data-popup="tooltip" title="{{ $rowcb['description'] }}">{{ $rowcb['description'] }}</td>
																			<td class="text-center">{{ date('d/m/y',strtotime($rowcb['date'])) }}</td>
																			<td class="text-right">0</td>
																			<td class="text-right">{{ number_format($rowcb['total'],2,',','.') }}</td>
																		</tr>
																		@php
																			$no++;
																			$adadebit++;
																			$totaldebit += $rowcb['total'];
																		@endphp
																	@endif
																@endforeach
																@foreach($datadebitbh as $key => $rowbh)
																	@if(in_array($rowbh['date'],$row))
																		<tr class="exclude">
																			<input type="hidden" data-type="{{ $rowbh['type'] }}" data-id="{{ $rowbh['id'] }}" data-description="{{ $rowbh['description'] }}" value="{{ number_format($rowbh['total'],2,',','.') }}" data-week="{{ $noweek }}" data-group="1">
																			<td class="text-center">{{ $no }}.</td>
																			<td class="breaklines" data-popup="tooltip" title="{{ $rowbh['description'] }}">{{ $rowbh['description'] }}</td>
																			<td class="text-center">{{ date('d/m/y',strtotime($rowbh['date'])) }}</td>
																			<td class="text-right">{{ number_format($rowbh['total'],2,',','.') }}</td>
																			<td class="text-right">{{ number_format($rowbh['totalreal'],2,',','.') }}</td>
																		</tr>
																		@php
																			$no++;
																			$adadebit++;
																			$totaldebit += $rowbh['totalreal'];
																		@endphp
																	@endif
																@endforeach
																@foreach($datadebit as $key => $rowar)
																	@if(!in_array($rowar['date'],$row))
																		@if($noweek == 1 && $rowar['date'] < $month.'-01')
																			<tr class="bg-danger" data-type="{{ $rowar['type'] }}" data-id="{{ $rowar['id'] }}" data-description="{{ $rowar['description'] }}" data-nominal="{{ number_format($rowar['total'],2,',','.') }}" data-group="1">
																				<input type="hidden" data-type="{{ $rowar['type'] }}" data-id="{{ $rowar['id'] }}" data-description="{{ $rowar['description'] }}" value="{{ number_format($rowar['total'],2,',','.') }}" data-week="{{ $noweek }}" data-group="1">
																				<td class="text-center">{{ $no }}.</td>
																				<td class="breaklines" data-popup="tooltip" title="{{ $rowar['description'] }}">{{ $rowar['description'] }}</td>
																				<td class="text-center"><input type="date" data-type="{{ $rowar['type'] }}" data-id="{{ $rowar['id'] }}" value="{{ $rowar['date'] }}" id="date_ar{{ $rowar['id'] }}" onchange="saveDebit('{{ $rowar['id'] }}','{{ $rowar['date'] }}',{{ $noweek }},this.value,this);"></td>
																				<td class="text-right">
																					<div class="form-group-feedback form-group-feedback-right">
																						<input type="text" data-type="{{ $rowar['type'] }}" data-id="{{ $rowar['id'] }}" value="{{ number_format($rowar['total'],2,',','.') }}" id="nominal_ar{{ $rowar['id'] }}" onkeyup="formatRupiah(this);" style="width:100% !important;" readonly>
																						<div class="form-control-feedback d-none" id="spinnerar{{ $rowar['id'] }}">
																							<i class="icon-spinner2 spinner"></i>
																						</div>
																					</div>
																				</td>
																				<td class="text-right">{{ number_format($rowar['totalreal'],2,',','.') }}</td>
																			</tr>
																			@php
																				$no++;
																				$adadebit++;
																				$totaldebit += $rowar['totalreal'] > 0 ? $rowar['total'] - $rowar['totalreal'] : $rowar['total'];
																			@endphp
																		@endif
																	@else
																		<tr data-type="{{ $rowar['type'] }}" data-id="{{ $rowar['id'] }}" data-description="{{ $rowar['description'] }}" data-nominal="{{ number_format($rowar['total'],2,',','.') }}" data-group="1">
																			<input type="hidden" data-type="{{ $rowar['type'] }}" data-id="{{ $rowar['id'] }}" data-description="{{ $rowar['description'] }}" value="{{ number_format($rowar['total'],2,',','.') }}" data-week="{{ $noweek }}" data-group="1">
																			<td class="text-center">{{ $no }}.</td>
																			<td class="breaklines" data-popup="tooltip" title="{{ $rowar['description'] }}">{{ $rowar['description'] }}</td>
																			<td class="text-center"><input type="date" data-type="{{ $rowar['type'] }}" data-id="{{ $rowar['id'] }}" value="{{ $rowar['date'] }}" id="date_ar{{ $rowar['id'] }}" onchange="saveDebit('{{ $rowar['id'] }}','{{ $rowar['date'] }}',{{ $noweek }},this.value,this);"></td>
																			<td class="text-right">
																				<div class="form-group-feedback form-group-feedback-right">
																					<input type="text" data-type="{{ $rowar['type'] }}" data-id="{{ $rowar['id'] }}" value="{{ number_format($rowar['total'],2,',','.') }}" id="nominal_ar{{ $rowar['id'] }}" onkeyup="formatRupiah(this);" style="width:100% !important;" readonly>
																					<div class="form-control-feedback d-none" id="spinnerar{{ $rowar['id'] }}">
																						<i class="icon-spinner2 spinner"></i>
																					</div>
																				</div>
																			</td>
																			<td class="text-right">{{ number_format($rowar['totalreal'],2,',','.') }}</td>
																		</tr>
																		@php
																			$no++;
																			$adadebit++;
																			$totaldebit += $rowar['total'];
																		@endphp
																	@endif
																@endforeach
																@if($adadebit == 0)
																	<tr class="exclude">
																		<td class="text-center" colspan="5">No data found.</td>
																	</tr>
																@endif
															</tbody>
														</table>
													</div>
												</td>
												<td style="padding: 0rem 0.2rem !important;vertical-align:top;">
													<div class="table-responsive mt-3">
														<table class="table table-bordered table-striped table-data" id="tableDataCredit{{ $noweek }}">
															<thead>
																<tr class="text-center">
																	<th rowspan="2">No</th>
																	<th rowspan="2">Description</th>
																	<th rowspan="2">Date</th>
																	<th colspan="2">Amount</th>
																</tr>
																<tr class="text-center">
																	<th width="25%">Projected</th>
																	<th width="25%">Real</th>
																</tr>
															</thead>
															<tbody id="data_week_{{ $noweek }}">
																@php
																	$no = 1;
																@endphp
																@foreach($datacreditbh as $key => $rowbh)
																	@if(in_array($rowbh['date'],$row))
																		<tr class="exclude">
																			<input type="hidden" data-type="{{ $rowbh['type'] }}" data-id="{{ $rowbh['id'] }}" data-description="{{ $rowbh['description'] }}" value="{{ number_format($rowbh['total'],2,',','.') }}" data-week="{{ $noweek }}" data-group="1">
																			<td class="text-center">{{ $no }}.</td>
																			<td class="breaklines" data-popup="tooltip" title="{{ $rowbh['description'] }}">{{ $rowbh['description'] }}</td>
																			<td class="text-center">{{ date('d/m/y',strtotime($rowbh['date'])) }}</td>
																			<td class="text-right">{{ number_format($rowbh['total'],2,',','.') }}</td>
																			<td class="text-right">{{ number_format($rowbh['totalreal'],2,',','.') }}</td>
																		</tr>
																		@php
																			$no++;
																			$adadebit++;
																			$totalcredit += $rowbh['totalreal'];
																		@endphp
																	@endif
																@endforeach
																@foreach($datacredit as $key => $rowap)
																	@if(in_array($rowap['date'],$row))
																		<tr data-type="{{ $rowap['type'] }}" data-id="{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" data-description="{{ $rowap['description'] }}" data-week="{{ $noweek }}" data-group="2" class="{{ $rowap['fixedcost'] == '1' ? 'bg-success' : '' }}" data-nominal="{{ number_format($rowap['total'],2,',','.') }}">
																			<td class="text-center">{{ $no }}.</td>
																			<td class="breaklines" data-popup="tooltip" title="{{ $rowap['description'] }}">{{ $rowap['description'] }}</td>
																			<td class="text-center" style="padding: 0 !important;"><input type="date" data-type="purchase_requests" data-id="{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" value="{{ $rowap['date'] }}" id="date_ap{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" onchange="save('{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}','{{ $rowap['date'] }}',{{ $noweek }},this.value,this);" {{ $rowap['type'] == 'coas' ? 'disabled' : '' }}></td>
																			<td class="text-right" style="padding: 0 !important;">
																				<div class="form-group-feedback form-group-feedback-right">
																					<input type="text" data-type="purchase_requests" data-id="{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" value="{{ number_format($rowap['total'],2,',','.') }}" id="nominal_ap{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" onkeyup="formatRupiah(this);" style="width:100% !important;" {{ $rowap['type'] == 'coas' ? 'disabled' : '' }} onkeypress="if(event.keyCode==13){ save('{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}'); }" class="cash-flow-input">
																					<div class="form-control-feedback d-none" id="spinner{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}">
																						<i class="icon-spinner2 spinner"></i>
																					</div>
																				</div>
																			</td>
																			<td class="text-right">{{ number_format($rowap['totalreal'],2,',','.') }}</td>
																		</tr>
																		@php
																			$no++;
																			$adacredit++;
																			$totalcredit += $rowap['totalreal'] > 0 ? $rowap['total'] - $rowap['totalreal'] : $rowap['total'];
																		@endphp
																	@else
																		@if($noweek == 1 && $rowap['date'] < $month.'-01')
																			<tr data-type="{{ $rowap['type'] }}" data-id="{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" data-description="{{ $rowap['description'] }}" data-week="{{ $noweek }}" data-group="2" class="{{ $rowap['fixedcost'] == '1' ? 'bg-success' : 'bg-danger' }}" data-nominal="{{ number_format($rowap['total'],2,',','.') }}">
																				<td class="text-center">{{ $no }}.</td>
																				<td class="breaklines" data-popup="tooltip" title="{{ $rowap['description'] }}">{{ $rowap['description'] }}</td>
																				<td class="text-center" style="padding: 0 !important;"><input type="date" data-type="purchase_requests" data-id="{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" value="{{ $rowap['date'] }}" id="date_ap{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" onchange="save('{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}','{{ $rowap['date'] }}',{{ $noweek }},this.value,this);"></td>
																				<td class="text-right" style="padding: 0 !important;">
																					<div class="form-group-feedback form-group-feedback-right">
																						<input type="text" data-type="purchase_requests" data-id="{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" value="{{ number_format($rowap['total'],2,',','.') }}" id="nominal_ap{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}" onkeyup="formatRupiah(this);" style="width:100% !important;" onkeypress="if(event.keyCode==13){ save('{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}'); }" class="cash-flow-input">
																						<div class="form-control-feedback d-none" id="spinner{{ $rowap['idcf'] ? $rowap['idcf'] : $rowap['id'] }}">
																							<i class="icon-spinner2 spinner"></i>
																						</div>
																					</div>
																				</td>
																				<td class="text-right">{{ number_format($rowap['totalreal'],2,',','.') }}</td>
																			</tr>
																			@php
																				$no++;
																				$adacredit++;
																				$totalcredit += $rowap['totalreal'] > 0 ? $rowap['total'] - $rowap['totalreal'] : $rowap['total'];
																			@endphp
																		@endif
																	@endif
																@endforeach
																@if($adacredit == 0)
																	<tr class="exclude">
																		<td class="text-center" colspan="5">No data found.</td>
																	</tr>
																@endif
															</tbody>
														</table>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
									@php
										$balance = $totaldebit - $totalcredit;
									@endphp
									
								</div>
							</div>
							<div class="card-footer" style="background-color:#e6e6e6 !important;">
								<div class="d-flex justify-content-between align-items-center" style="font-size:20px;">
									<div>
										Total Debit <span class="badge bg-info badge-pill" id="totaldebit{{ $noweek }}">{{ number_format($totaldebit,2,',','.') }}</span>
									</div>
									<div>
										Balance <span class="badge {{ $balance > 0 ? 'bg-success' : 'bg-danger' }} badge-pill mr-2"><i id="totalbalance{{ $noweek }}">{{ number_format($balance,2,',','.') }}</i></span>
									</div>
									<div>
										Total Credit <span class="badge bg-warning badge-pill" id="totalcredit{{ $noweek }}">{{ number_format($totalcredit,2,',','.') }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@php
						$balancedebitweek = $balance;
						$noweek++;
					@endphp
					@endforeach
				</div>
			</div>
		</div>
		<div class="fab-menu">
			<div class="mb-4">
				
				<div class="list-group text-center bg-teal">
					<a href="javascript:void(0);" class="list-group-item list-group-item-action disabled" style="padding: 0.25rem 0.75rem;">Week:</a>
					@for($i=1;$i<$noweek;$i++)
						<a href="javascript:void(0);" class="list-group-item list-group-item-action text-center" style="display:block;padding: 0.25rem 0.75rem;" onclick="goTo({{ $i }})">{{ $i }}</a>
					@endfor
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_zoom" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Details Weekly Cash Flow</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" id="zoomData">
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_date" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Change Dates</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="alert alert-info alert-styled-left alert-dismissible">
					<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
					<span class="font-weight-semibold">Info!</span> All due date will be changed to new date set.
				</div>
				<form id="form_data">
				   <div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
				   </div>
					<h5 class="card-title"><b>Main Information</b></h5>
				   <div class="row">
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Date :<sup class="text-danger">*</sup></label>
							<input type="date" name="date" id="date" class="form-control">
						 </div>
					  </div>
				   </div>
				   <div class="form-group"><hr></div>
				   <h5 class="card-title"><b>Details Coa</b></h5>
				   <div class="row">
						<div class="form-group col-md-12">
							<table class="table table-bordered">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th>Description</th>
								   <th>Nominal</th>
								</tr>
							 </thead>
							 <tbody id="data_content"></tbody>
							</table>
						</div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Debit : <span class="badge badge-success" id="total_debit">0</span>
					&nbsp;
					Credit : <span class="badge badge-danger" id="total_credit">0</span>
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_change_date" onclick="change_date()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
<script>
	var arrDate = {!! json_encode($weeks) !!};

	$(function() {
		$('.sidebar-main-toggle').click();
		
		$('#modal_zoom').on('hidden.bs.modal', function (e) {
			$('#zoomData').html('');
		});
		
		$('#modal_date').on('hidden.bs.modal', function (e) {
			$('#data_content').empty();
			$('#total_credit').val('0');
			$('#total_debit').val('0');
		});
		
		$('.table-data tbody').on('click', 'tr', function () {
			if($(this).hasClass('exclude')){
				notif('danger', 'bg-danger', 'You can not choose this row.');
			}else{
				if($(this).hasClass('selected')){
					$(this).removeClass('selected');
				}else{
					$(this).addClass('selected');
				}
			}
			countSelected();
		});
		
		$('input').click(function(event) {
			event.stopPropagation();
		});
	});
	
	function refreshTable(){
		$("#alltable").load(" #alltable > *");
	}

	function goTo(no){
		$('html, body').animate({
			scrollTop: $("#bagian" + no).offset().top - 175
		}, 1000);
	}
	
	function changeDate(id,prevDate,week,nextDate,element){
		var changedate = false, prevweek = week, nextweek = 0, thismonth = false;
		
		for(var i = 1; i <= 7;i++){
			if(arrDate['week-' + i]){
				for(var j = 0; j < arrDate['week-' + i].length; j++){
					if(arrDate['week-' + i][j] == nextDate){
						nextweek = i;
						thismonth = true;
					}
				}
			}
		}
		
		if(prevweek == nextweek){
			
		}else{
			if(nextweek > 0){
				var no = $('[data-week="' + nextweek + '"][data-group="2"]').length + 1;
				
				$(element).attr("onchange","save(" + id + ",'" + nextDate + "'," + nextweek + ",this.value,this)");
				$(element).parent().parent().children(':first-child').text(no + '.');
				$(element).parent().parent().attr("data-week",nextweek);
				$(element).parent().parent().appendTo('#data_week_' + nextweek);
				
			}else{
				$(element).parent().parent().remove();
			}
			
			updateTotalWeek(week);
			
			if(nextweek > 0){
				$('[data-week="' + nextweek + '"][data-group="1"]').first().val($('#totalbalance' + week).text());
				
				if($('#balanceweek' + nextweek)){
					$('#balanceweek' + nextweek).text($('#totalbalance' + week).text());
				}
				
				updateTotalWeek(nextweek);
			}
		}
		
		setTimeout(function() {
			reCountWeek();
		}, 1000);
	}
	
	function reCountWeek(){
		for(var z = 1; z <= $('.weeklist').length; z++){
			updateTotalWeek(z);
			
			var next = parseInt(z) + 1;
			
			if($('#balanceweek' + next)){
				$('[data-week="' + next + '"][data-group="1"]').first().val($('#totalbalance' + z).text());
				if($('#balanceweek' + next)){
					$('#balanceweek' + next).text($('#totalbalance' + z).text());
				}
			}
		}
	}
	
	function updateTotalWeek(week){
		
		var credit = 0, balance = 0, debit = 0;
		
		$('[data-week="' + week + '"]').each(function(){
			if($(this).data('group') == '1'){
				debit += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
			}
			if($(this).data('group') == '2'){
				credit += parseFloat($('#nominal_ap' + $(this).data('id')).val().replaceAll(".", "").replaceAll(",","."));
			}
		});
		
		balance = debit - credit;
		
		if(debit >= 0){
			$('#totaldebit' + week).text(formatRupiahIni(debit.toFixed(2).toString().replace('.',',')));
		}else{
			$('#totaldebit' + week).text('-' + formatRupiahIni(debit.toFixed(2).toString().replace('.',',')));
		}
		
		if(credit >= 0){
			$('#totalcredit' + week).text(formatRupiahIni(credit.toFixed(2).toString().replace('.',',')));
		}else{
			$('#totalcredit' + week).text('-' + formatRupiahIni(credit.toFixed(2).toString().replace('.',',')));
		}
		
		if(balance >= 0){
			$('#totalbalance' + week).parent().removeClass('bg-danger');
			$('#totalbalance' + week).parent().addClass('bg-success');
			$('#totalbalance' + week).text(formatRupiahIni(balance.toFixed(2).toString().replace('.',',')));
		}else{
			$('#totalbalance' + week).parent().addClass('bg-danger');
			$('#totalbalance' + week).parent().removeClass('bg-success');
			$('#totalbalance' + week).text('-' + formatRupiahIni(balance.toFixed(2).toString().replace('.',',')));
		}
	}
	
	var timer;
	
	function save(id,prevDate = null,week = null,nextDate = null,element = null){
		
		if($('#date_ap' + id).val() && $('#nominal_ap' + id).val()){
			/* clearTimeout(timer); */
			
			/* timer = setTimeout(function() { */
				$('#spinner' + id).removeClass('d-none');
				
				$.ajax({
				 url: '{{ url("admin/finance/cash_flow/save") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					 id : id,
					 date : $('#date_ap' + id).val(),
					 nominal : $('#nominal_ap' + id).val()
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					$('#spinner' + id).addClass('d-none');
					if(response.status == 200) {
					   notif('success', 'bg-success', response.message);
					   if(prevDate){
							changeDate(id,prevDate,week,nextDate,element);
					   }else{
							reCountWeek();
					   }
					   
					  refreshTable();
					} else {
					   notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					$('.modal-body').scrollTop(0);
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
				 }
			  });
			/* }, 1000); */
		}
	}
	
	function saveDebit(id,prevDate = null,week = null,nextDate = null,element = null){
		
		if($('#date_ar' + id).val()){
			
			/* clearTimeout(timer);
			
			timer = setTimeout(function() {	*/
				$('#spinner' + id).removeClass('d-none');
				
				$.ajax({
				 url: '{{ url("admin/finance/cash_flow/save_debit") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					 id : id,
					 date : $('#date_ar' + id).val(),
					 nominal : $('#nominal_ar' + id).val(),
					 type : $('#nominal_ar' + id).data('type')
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					$('#spinner' + id).addClass('d-none');
					if(response.status == 200) {
					   notif('success', 'bg-success', response.message);
					  refreshTable();
					} else {
					   notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					$('.modal-body').scrollTop(0);
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
				 }
			  });
			/* }, 1000); */
		}
	}
	
	function updateDate(){
		$('#modal_date').modal('toggle');
		
		var totaldebit = 0, totalcredit = 0;
		
		$('.table-data tr.selected').each(function(){
			$('#data_content').append(`
				<tr>
					<input type="hidden" name="data_id[]" value="` + $(this).data('id') + `">
					<input type="hidden" name="data_type[]" value="` + $(this).data('type') + `">
					<input type="hidden" name="data_nominal[]" value="` + $(this).data('nominal') + `">
					<td>` + $(this).data('description') + `</td>
					<td class="text-right">` + $(this).data('nominal') + `</td>
				</tr>
			`);
			
			if($(this).data('type') == 'project_deliveries' || $(this).data('type') == 'project_bills'){
				totaldebit += parseFloat($(this).data('nominal').replaceAll(".", "").replaceAll(",","."));
			}
			
			if($(this).data('type') == 'purchase_requests'){
				totalcredit += parseFloat($(this).data('nominal').replaceAll(".", "").replaceAll(",","."));
			}
		});
		
		$('#total_debit').text(formatRupiahIni(totaldebit.toFixed(2).toString().replace('.',',')));
		$('#total_credit').text(formatRupiahIni(totalcredit.toFixed(2).toString().replace('.',',')));
	}
	
	function countSelected(){
		var count = 0;
		$('.table-data tr.selected').each(function(){
			count += 1;
		});
		
		$('#countSelected').text(count);
		
		if($('.table-data tr.selected').length > 0){
			$('#btn-update').removeClass('d-none');
		}else{
			$('#btn-update').addClass('d-none');
		}
	}
	
	function zoomData(id){
		$('#zoomData').css("font-size", "14px");
		$('#zoomData').html($('#sourceData' + id).html());
		$('#modal_zoom').modal('toggle');
	}
	
	function submitFilter(){
		var branch = $('#branch').val(), month = '{{ $month }}';
		
		window.location.href = "{{ url('admin/finance/cash_flow') }}" + "?month=" + month + "&branch=" + branch;
	}
	
	function change_date(){
      $.ajax({
         url: '{{ url("admin/finance/cash_flow/change_date") }}',
         type: 'POST',
         dataType: 'JSON',
         data: new FormData($('#form_data')[0]),
         contentType: false,
         processData: false,
         cache: true,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert').hide();
            $('#validation_content').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
               success();
               notif('success', 'bg-success', response.message);
			  refreshTable();
            } else if(response.status == 422) {
               $('#validation_alert').show();
               $('.modal-body').scrollTop(0);
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
            $('.modal-body').scrollTop(0);
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
	}
	
	function success(){
		$('#modal_date').modal('toggle');
	}
	
	function formatRupiahIni(angka){
		var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
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
	
	function exportToExcel(){
		/* $("#tableDataCredit1").table2excel({
			exclude:".noExl",
			name:"Cash Flow",
			filename:"SomeFile",//do not include extension
			fileext:".xls"
		}); */
		
		let file = new Blob([$('#alltable').html()], {type:"application/vnd.ms-excel"});
		let url = URL.createObjectURL(file);
		let a = $("<a />", {
			href: url,
			download: "Budgeting Cash Flow.xls"}).appendTo("body").get(0).click();
			e.preventDefault();
	}
</script>