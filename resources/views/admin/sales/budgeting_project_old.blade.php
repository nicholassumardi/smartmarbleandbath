<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Budget Plan Projection</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left"
						onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" onclick="cancel()"
						data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Sales</a>
					<span class="breadcrumb-item active">Budget Plan Projection</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Filter</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Branch :<span class="text-danger">*</span></label>
							<select name="filter_branch" id="filter_branch" class="custom-select">
								<option value="">---Select One---</option>
								@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Month :</label>
							<div class="input-group-prepend">
								<input type="month" name="filter_start_date" id="filter_start_date"
									class="form-control">
								<span class="input-group-text">to</span>
								<input type="month" name="filter_finish_date" id="filter_finish_date"
									class="form-control">
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group text-right">
							<button type="button" onclick="loadDataTable()" class="btn bg-purple mr-2"><i
									class="icon-filter4"></i> Search</button>
							<button type="button" onclick="resetFilter()" class="btn bg-danger"><i
									class="icon-sync"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped w-100 display nowrap">
						<thead class="bg-dark">
							<tr class="text-center">
								<th>No</th>
								<th>User</th>
								<th>Project</th>
								<th>Name</th>
								<th>Branch</th>
								<th>Period</th>
								<th>Sales Amount</th>
								<th>Approved By</th>
								<th>Checked By</th>
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h1 class="modal-title mt-4" id="exampleModalLabel" style="font-size:30px !important;">New Budget
						Project</h1>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						style="margin-top:20px !important;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form_data">
						<div class="alert alert-danger" id="validation_alert" style="display:none;">
							<ul id="validation_content"></ul>
						</div>
						<div class="row justify-content-center">
							<div class="col-md-12 text-center mb-3">
								Choose Mode :
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-success btn-block btn-mode" id="btn-import"
									style="font-size:30px;">IMPORT</button>
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-outline-success btn-block btn-mode" id="btn-local"
									style="font-size:30px;">LOCAL</button>
							</div>
						</div>
						<div class="row justify-content-center mt-3">
							<div class="col-md-3">
								<div class="form-group">
									<label>Project Name :<sup class="text-danger">*</sup></label>
									<select name="project_id" id="project_id"
										onchange="getProjectProduct(this.value)"></select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Name :<sup class="text-danger">*</sup></label>
									<input type="text" name="name" id="name" class="form-control"
										placeholder="Enter project name">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Currency :<span class="text-danger">*</span></label>
									<select name="currency_id" id="currency_id" class="custom-select">
										@foreach($currency as $c)
										<option value="{{ $c->id }}">{{ '('.$c->code.') '.$c->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Branch :<span class="text-danger">*</span></label>
									<select name="branch" id="branch" class="custom-select">
										@foreach (DB::table('company_entities')->get() as $company)
										<option value="{{$company->id}}">{{$company->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Start Month :<span class="text-danger">*</span></label>
									<input type="month" name="startmonth" id="startmonth" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>End Month :<span class="text-danger">*</span></label>
									<input type="month" name="endmonth" id="endmonth" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Remarks / Note :<span class="text-danger">*</span></label>
									<textarea name="remarks" id="remarks" class="form-control" rows="1"
										placeholder="Will be shown to director/owner..."></textarea>
								</div>
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col-md-12" id="productCalculator">
								<div class="form-group">
									<hr>
								</div>
								<h3 class="text-center">Product Buying Cost & Sell Price Calculation Helper</h3>
								<div class="form-group">
									<hr>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead class="table-secondary">
											<tr class="text-center">
												<th width="20%">Product</th>
												<th>Sqm</th>
												<th width="25%">Buy Price (Origin Currency)</th>
												<th width="25%">Sell Price / Unit (Before Tax)</th>
												<th width="10%">Qty</th>
												<th width="20%">Total Buy</th>
												<th width="20%">Total Sales</th>
												<th>#</th>
											</tr>
										</thead>
										<tbody id="data_content_product">
											<tr>
												<td colspan="8">
													<div
														class="alert alert-info alert-styled-left alert-dismissible mt-3">
														<span class="font-weight-semibold">Selected project must be at
															least has 1 products to be shown here.</span>
													</div>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<th class="text-right" colspan="5">Grandtotal</th>
												<th class="text-right" id="grandtotal" style="font-size:200%;">0</th>
												<th class="text-right" id="grandtotalsell" style="font-size:200%;">0
												</th>
												<th class="text-right"><button type="button" onclick="useGrandTotal()"
														class="btn bg-success btn-sm btn-block"><i
															class="icon-circle-left2"></i> Use</button></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<div class="col-md-12 text-center">
								<div class="form-group">
									<hr>
								</div>
								<h3>Percentage</h3>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Rental Cost (%) :</label>
									<input type="text" name="percent_rental" id="percent_rental"
										class="form-control form-control-sm" placeholder="Enter..." value="2"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Fixed Cost (%) :</label>
									<input type="text" name="percent_fixed" id="percent_fixed"
										class="form-control form-control-sm" placeholder="Enter..." value="2"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>RSV Profit (%) :</label>
									<input type="text" name="percent_rsv" id="percent_rsv"
										class="form-control form-control-sm" placeholder="Enter..." value="5"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Interest of Payment Method (%) :</label>
									<input type="text" name="percent_ipm" id="percent_ipm"
										class="form-control form-control-sm" placeholder="Enter..." value="2"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Interest of Buying Capital (%) :</label>
									<input type="text" name="percent_ibc" id="percent_ibc"
										class="form-control form-control-sm" placeholder="Enter..." value="3"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Import Duty (%) :</label>
									<input type="text" name="percent_import" id="percent_import"
										class="form-control form-control-sm" placeholder="Enter..." value="5"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Safe Guard (%) :</label>
									<input type="text" name="percent_safe" id="percent_safe"
										class="form-control form-control-sm" placeholder="Enter..." value="19"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>PPN (%) :</label>
									<input type="text" name="percent_ppn" id="percent_ppn"
										class="form-control form-control-sm" placeholder="Enter..." value="10"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>PPH (%) :</label>
									<input type="text" name="percent_pph" id="percent_pph"
										class="form-control form-control-sm" placeholder="Enter..." value="7,5"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Fee MKJ (%) :</label>
									<input type="text" name="percent_mkj" id="percent_mkj"
										class="form-control form-control-sm" placeholder="Enter..." value="5"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Fee PTA (%) :</label>
									<input type="text" name="percent_pta" id="percent_pta"
										class="form-control form-control-sm" placeholder="Enter..." value="5"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Middle Man (%) :</label>
									<input type="text" name="percent_mid" id="percent_mid"
										class="form-control form-control-sm" placeholder="Enter..." value="3"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Sales Commision (%) :</label>
									<input type="text" name="percent_scom" id="percent_scom"
										class="form-control form-control-sm" placeholder="Enter..." value="1"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-12 text-center">
								<div class="form-group">
									<hr>
								</div>
								<h3>Helper</h3>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Exchange Rate :<span class="text-danger">*</span><i>(Fill 1 if
											IDR)</i></label>
									<input type="text" name="exchange_rate" id="exchange_rate"
										class="form-control form-control-sm" placeholder="Enter nominal" value="15.000"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Number of Container :</label>
									<input type="text" name="number_container" id="number_container"
										class="form-control form-control-sm" placeholder="Enter nominal" value="1"
										onkeyup="formatRupiah(this);hitung();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>LS Cost :<i>(original Currency)</i></label>
									<input type="text" name="ls_cost" id="ls_cost" class="form-control form-control-sm"
										placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Product Total :<i>(original Currency)</i></label>
									<input type="text" name="product_total" id="product_total"
										class="form-control form-control-sm" placeholder="Enter nominal" value="0"
										onkeyup="formatRupiah(this)">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Quantity of Container :</label>
									<input type="text" name="qty_container" id="qty_container"
										class="form-control form-control-sm" placeholder="Enter nominal" value="1"
										onkeyup="formatRupiah(this);hitung2();">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Freight Cost :<i>(original Currency)</i></label>
									<input type="text" name="freight_cost" id="freight_cost"
										class="form-control form-control-sm" placeholder="Enter nominal" value="0"
										onkeyup="formatRupiah(this)">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>EMKL :<i>(in IDR)</i></label>
									<input type="text" name="emkl" id="emkl" class="form-control form-control-sm"
										placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<hr>
								</div>
								<div class="form-group">
									<button type="button" class="btn bg-success col-12 d-none" onclick="addDetail()"><i
											class="icon-plus22"></i> Add</button>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th>Coa</th>
											<th>Nominal</th>
											<th>Group</th>
											<th>Description</th>
											<th>#</th>
										</tr>
									</thead>
									<tbody id="data_content">
										@foreach($coa as $c)
										@if(in_array($c->code,array('4.000.01.01','4.000.01.02','4.000.02.01','4.000.02.02')))
										<tr class="bg-primary">
											<input type="hidden" name="coa_detail[]" value="{{ $c->id }}">
											<input type="hidden" name="group_detail[]" value="1">
											<td>[{{ $c->code }}] {{ $c->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $c->id }}"
													data-group="1"></td>
											<td class="align-middle">Revenue</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endif
										@endforeach
										@foreach($coa as $c)
										@if(in_array(substr($c->code,0,8),array('5.000.01','5.000.02')))
										@if(count($c->child()) == 0)
										<tr class="bg-secondary">
											<input type="hidden" name="coa_detail[]" value="{{ $c->id }}">
											<input type="hidden" name="group_detail[]" value="2">
											<td>[{{ $c->code }}] {{ $c->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $c->id }}"
													data-group="2"></td>
											<td class="align-middle">Product Buying Cost</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endif
										@foreach($c->child() as $rowchild)
										@if(count($rowchild->child()) == 0)
										<tr class="bg-secondary">
											<input type="hidden" name="coa_detail[]" value="{{ $rowchild->id }}">
											<input type="hidden" name="group_detail[]" value="2">
											<td>[{{ $rowchild->code }}] {{ $rowchild->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();"
													data-coa="{{ $rowchild->id }}" data-group="2"></td>
											<td class="align-middle">Product Buying Cost</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endif
										@foreach($rowchild->child() as $rowgrandchild)
										@if(count($rowgrandchild->child()) == 0)
										<tr class="bg-secondary">
											<input type="hidden" name="coa_detail[]" value="{{ $rowgrandchild->id }}">
											<input type="hidden" name="group_detail[]" value="2">
											<td>[{{ $rowgrandchild->code }}] {{ $rowgrandchild->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();"
													data-coa="{{ $rowgrandchild->id }}" data-group="2"></td>
											<td class="align-middle">Product Buying Cost</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endif
										@foreach($rowgrandchild->child() as $rowgrandgrandchild)
										<tr class="bg-secondary">
											<input type="hidden" name="coa_detail[]"
												value="{{ $rowgrandgrandchild->id }}">
											<input type="hidden" name="group_detail[]" value="2">
											<td>[{{ $rowgrandgrandchild->code }}] {{ $rowgrandgrandchild->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();"
													data-coa="{{ $rowgrandgrandchild->id }}" data-group="2"></td>
											<td class="align-middle">Product Buying Cost</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endforeach
										@endforeach
										@endforeach
										@endif
										@endforeach
										@foreach($coa as $c)
										@if(in_array(substr($c->code,0,8),array('5.000.03')))
										@foreach($c->child() as $rowchild)
										@if(count($rowchild->child()) == 0)
										<tr class="bg-danger">
											<input type="hidden" name="coa_detail[]" value="{{ $rowchild->id }}">
											<input type="hidden" name="group_detail[]" value="3">
											<td>[{{ $rowchild->code }}] {{ $rowchild->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();"
													data-coa="{{ $rowchild->id }}" data-group="3"></td>
											<td class="align-middle">Landed Cost</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endif
										@foreach($rowchild->child() as $rowgrandchild)
										@if(count($rowgrandchild->child()) == 0)
										<tr class="bg-danger">
											<input type="hidden" name="coa_detail[]" value="{{ $rowgrandchild->id }}">
											<input type="hidden" name="group_detail[]" value="3">
											<td>[{{ $rowgrandchild->code }}] {{ $rowgrandchild->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();"
													data-coa="{{ $rowgrandchild->id }}" data-group="3"></td>
											<td class="align-middle">Landed Cost</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endif
										@foreach($rowgrandchild->child() as $rowgrandgrandchild)
										<tr class="bg-danger">
											<input type="hidden" name="coa_detail[]"
												value="{{ $rowgrandgrandchild->id }}">
											<input type="hidden" name="group_detail[]" value="3">
											<td>[{{ $rowgrandgrandchild->code }}] {{ $rowgrandgrandchild->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();"
													data-coa="{{ $rowgrandgrandchild->id }}" data-group="3"></td>
											<td class="align-middle">Landed Cost</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endforeach
										@endforeach
										@endforeach
										@endif
										@endforeach
										@foreach($coa as $c)
										@if(in_array($c->code,array('6.100.00.01','6.100.00.02','6.100.00.03','6.100.00.04','6.100.00.05','6.100.00.06','7.200.02','7.200.03')))
										<tr class="bg-warning">
											<input type="hidden" name="coa_detail[]" value="{{ $c->id }}">
											<input type="hidden" name="group_detail[]" value="4">
											<td>[{{ $c->code }}] {{ $c->name }}</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $c->id }}"
													data-group="4"></td>
											<td class="align-middle">Marketing Cost</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..."></td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										@endif
										@endforeach
										<tr class="bg-success">
											<input type="hidden" name="coa_detail[]" value="111111">
											<input type="hidden" name="group_detail[]" value="5">
											<td>Rental Cost</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard2();" data-coa="111111"></td>
											<td class="align-middle">Company's Rsv Income (CRI)</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..." value="Rsv Profit & Other Cost">
											</td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										<tr class="bg-success">
											<input type="hidden" name="coa_detail[]" value="222222">
											<input type="hidden" name="group_detail[]" value="5">
											<td>Fixed Cost</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard2();" data-coa="222222"></td>
											<td class="align-middle">Company's Rsv Income (CRI)</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..." value="Rsv Profit & Other Cost">
											</td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										<tr class="bg-success">
											<input type="hidden" name="coa_detail[]" value="333333">
											<input type="hidden" name="group_detail[]" value="5">
											<td>RSV Profit</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard2();" data-coa="333333"></td>
											<td class="align-middle">Company's Rsv Income (CRI)</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..." value="Rsv Profit & Other Cost">
											</td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										<tr class="bg-success">
											<input type="hidden" name="coa_detail[]" value="444444">
											<input type="hidden" name="group_detail[]" value="5">
											<td>Interest on Payment Method</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard2();" data-coa="444444"></td>
											<td class="align-middle">Company's Rsv Income (CRI)</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..." value="Rsv Profit & Other Cost">
											</td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
										<tr class="bg-success">
											<input type="hidden" name="coa_detail[]" value="555555">
											<input type="hidden" name="group_detail[]" value="5">
											<td>Interest on Buying Capital</td>
											<td class="align-middle"><input type="text" name="nominal_detail[]"
													class="form-control form-control-sm" value="0"
													onkeyup="formatRupiah(this);safeGuard2();" data-coa="555555"></td>
											<td class="align-middle">Company's Rsv Income (CRI)</td>
											<td class="align-middle"><input type="text" name="description_detail[]"
													class="form-control form-control-sm"
													placeholder="Description here..." value="Rsv Profit & Other Cost">
											</td>
											<td class="align-middle">
												<button type="button" id="delete_data_content"
													class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<div class="mr-auto" style="font-size:25px !important;">
						Info <i class="icon-point-right mr-2 icon-2x"></i>
						Nett Profit : <span class="badge badge-success" id="total_profit">0</span>
						&nbsp;&nbsp; % Nett Profit : <span class="badge badge-success" id="percent_profit">0</span>
						&nbsp;&nbsp; % Real Profit : <span class="badge badge-secondary"
							id="percent_profit_real">0</span>
						&nbsp;&nbsp; % CRI : <span class="badge badge-secondary" id="percent_cri">0</span>
					</div>
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
					<button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()"><i
							class="icon-cross3"></i> Cancel</button>
					<button type="button" class="btn bg-warning" id="btn_update" onclick="update()"
						style="display:none;"><i class="icon-pencil7"></i> Save</button>
					<button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i
							class="icon-plus3"></i> Save</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(function() {
		select2ServerSide('#project_id', '{{ url("admin/select2/project") }}');
		select2ServerSide('#product_id', '{{ url("admin/select2/product") }}');
		
		@if($project)
			$('#modal_form').modal('toggle');
			$('#project_id').empty();
			$('#project_id').append(`
				<option value="{{ $project->id }}">{{ $project->code.' '.$project->name }}</option>
			`);
			$('#name').val('{{ $project->name }}');
			$('#project_id').trigger('change');
		@endif


		$('#data_content_product').on('click', '#delete_data_product', function() {
			 $(this).closest('tr').remove();
			 countAll();
		});
		
		$('#product_total').on('keyup', function(){
			var hasil = Math.round(parseFloat($('#exchange_rate').val().replaceAll('.','').replaceAll(',','.')) * parseFloat($(this).val().replaceAll('.','').replaceAll(',','.')) * 100 / 100).toString();
			$('input[data-coa="122"],input[data-coa="290"]').val(formatHelper(hasil));
			safeGuard();
		});
		
		$('#ls_cost').on('keyup', function(){
			var hasil = Math.round(parseFloat($('#exchange_rate').val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#number_container').val()) * parseFloat($(this).val().replaceAll('.','').replaceAll(',','.')) * 100 / 100).toString();
			$('input[data-coa="289"]').val(formatHelper(hasil));
		});
		
		$('#freight_cost').on('keyup', function(){
			var hasil = Math.round(parseFloat($('#exchange_rate').val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#qty_container').val()) * parseFloat($(this).val().replaceAll('.','').replaceAll(',','.')) * 100 / 100).toString();
			$('input[data-coa="288"]').val(formatHelper(hasil));
		});
		
		$('#emkl').on('keyup', function(){
			var hasil = Math.round(parseFloat($('#qty_container').val().replaceAll('.','').replaceAll(',','.')) * parseFloat($(this).val().replaceAll('.','').replaceAll(',','.')) * 100 / 100).toString();
			$('input[data-coa="291"]').val(formatHelper(hasil));
		});
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#form_data')[0].reset();
			$('#project_id').empty();
			$('input[name^="nominal_detail"]').val('0');
			$('#data_content_product').empty();
			$('#data_content_product').append(`
				<tr>
					<td colspan="6">
						<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
							<span class="font-weight-semibold">Selected project must be at least has 1 products to be shown here.</span>
						</div>
					</td>
				</tr>
			`);
			countAll();
		});
		
		$('.btn-mode').on('click', function(){
			clearBtn();
			$(this).addClass('btn-success');
			changeMode();
		});
	});
	
	$('#branch').change(function (e) { 
		var val = $('#project_id').val();
		if (e.originalEvent) {
			appendData(val)
		}
	});

	function clearBtn(){
		$('.btn-mode').each(function() {
			if($(this).hasClass('btn-success')){
				$(this).removeClass('btn-success');
				$(this).addClass('btn-outline-success');
			}else{
				$(this).removeClass('btn-outline-success');
				$(this).addClass('btn-success');
			}
		});
	}
	
	function changeMode(){
		if($('#btn-local').hasClass('btn-success')){
			$('#currency_id').val('5');
			$('#percent_import').val('0');
			$('#percent_safe').val('0');
			$('#percent_pph').val('0');
			$('#percent_ppn').val('0');
			$('#percent_mkj').val('0');
			/* $('#percent_pta').val('0'); */
			$('#percent_mid').val('0');
			$('#percent_scom').val('1');
			$('#exchange_rate').val('1');
			$('#number_container').val('0');
			$('#qty_container').val('0');
		}
		
		if($('#btn-import').hasClass('btn-success')){
			$('#currency_id').val('1');
			$('#percent_import').val('5');
			$('#percent_safe').val('19');
			$('#percent_pph').val('7,5');
			$('#percent_ppn').val('10');
			$('#percent_mkj').val('5');
			$('#percent_pta').val('5');
			$('#percent_mid').val('3');
			$('#percent_scom').val('1');
			$('#exchange_rate').val('15.000');
			$('#number_container').val('1');
			$('#qty_container').val('1');
			useGrandTotal();
		}
	}
	
	function hitung(){
		var hasil = Math.round(parseFloat($('#exchange_rate').val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#product_total').val().replaceAll('.','').replaceAll(',','.')) * 100 / 100).toString();
		$('input[data-coa="122"],input[data-coa="290"]').val(formatHelper(hasil));
		
		var hasil = Math.round(parseFloat($('#exchange_rate').val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#number_container').val()) * parseFloat($('#ls_cost').val().replaceAll('.','').replaceAll(',','.')) * 100 / 100).toString();
		$('input[data-coa="289"]').val(formatHelper(hasil));
		
		safeGuard();
	}
	
	function hitung2(){
		
		var hasil = Math.round(parseFloat($('#exchange_rate').val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#qty_container').val()) * parseFloat($('#freight_cost').val().replaceAll('.','').replaceAll(',','.')) * 100 / 100).toString();
		$('input[data-coa="288"]').val(formatHelper(hasil));
		
		var hasil = Math.round(parseFloat($('#qty_container').val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#emkl').val().replaceAll('.','').replaceAll(',','.')) * 100 / 100).toString();
		$('input[data-coa="291"]').val(formatHelper(hasil));
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
		var percent_rental = parseFloat($('#percent_rental').val().replaceAll('.','').replaceAll(',','.')) / 100;
		var percent_fixed = parseFloat($('#percent_fixed').val().replaceAll('.','').replaceAll(',','.')) / 100;
		var percent_rsv = parseFloat($('#percent_rsv').val().replaceAll('.','').replaceAll(',','.')) / 100;
		var percent_ipm = parseFloat($('#percent_ipm').val().replaceAll('.','').replaceAll(',','.')) / 100;
		var percent_ibc = parseFloat($('#percent_ibc').val().replaceAll('.','').replaceAll(',','.')) / 100;
		
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
		
		var total_rental = 0, total_interest_pm = 0, total_revenue = 0, total_cogs = 0, total_lc = 0, total_mc = 0;
		
		$('input[data-group="1"]').each(function() {
			total_interest_pm += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			total_revenue += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		$('input[data-group="2"]').each(function() {
			total_rental += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			total_cogs += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		$('input[data-group="3"]').each(function() {
			total_rental += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			total_lc += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		$('input[data-group="4"]').each(function() {
			total_mc += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		total_rental += parseFloat($('input[data-coa="134"]').val().replaceAll('.','').replaceAll(',','.'));
		total_rental += parseFloat($('input[data-coa="139"]').val().replaceAll('.','').replaceAll(',','.'));
		
		/*if($('#branch').val() == '1'){
			$('input[data-coa="111111"]').val(formatHelper(Math.round(total_revenue * percent_rental).toString()));
			$('input[data-coa="222222"]').val(formatHelper(Math.round(total_revenue * percent_fixed).toString()));
			$('input[data-coa="333333"]').val(formatHelper(Math.round(total_revenue * percent_rsv).toString()));
			$('input[data-coa="444444"]').val(formatHelper(Math.round(total_revenue * percent_ipm).toString()));
			$('input[data-coa="555555"]').val(formatHelper(Math.round(total_revenue * percent_ibc).toString()));
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#total_profit').text('-' + formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}else{
				$('#total_profit').text(formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#percent_profit').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) < 0){
				$('#percent_profit_real').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit_real').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			$('#percent_cri').text(formatRupiahIni(((((total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			
		}else if($('#branch').val() == '2'){
			
			$('input[data-coa="111111"]').val(formatHelper(Math.round(total_rental * percent_rental).toString()));
			$('input[data-coa="222222"]').val(formatHelper(Math.round(total_rental * percent_fixed).toString()));
			$('input[data-coa="333333"]').val(formatHelper(Math.round(total_rental * percent_rsv).toString()));
			$('input[data-coa="444444"]').val(formatHelper(Math.round(total_interest_pm * percent_ipm).toString()));
			$('input[data-coa="555555"]').val(formatHelper(Math.round(total_rental * percent_ibc).toString()));
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#total_profit').text('-' + formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}else{
				$('#total_profit').text(formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#percent_profit').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc + (total_rental * percent_rental) + (total_rental * percent_fixed) + (total_rental * percent_rsv) + (total_interest_pm * percent_ipm) + (total_rental * percent_ibc))) < 0){
				$('#percent_profit_real').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_rental * percent_rental) + (total_rental * percent_fixed) + (total_rental * percent_rsv) + (total_interest_pm * percent_ipm) + (total_rental * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit_real').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_rental * percent_rental) + (total_rental * percent_fixed) + (total_rental * percent_rsv) + (total_interest_pm * percent_ipm) + (total_rental * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			$('#percent_cri').text(formatRupiahIni(((((total_rental * percent_rental) + (total_rental * percent_fixed) + (total_rental * percent_rsv) + (total_interest_pm * percent_ipm) + (total_rental * percent_ibc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
		}else if($('#branch').val() == '3'){
			$('input[data-coa="111111"]').val(formatHelper(Math.round(total_revenue * percent_rental).toString()));
			$('input[data-coa="222222"]').val(formatHelper(Math.round(total_revenue * percent_fixed).toString()));
			$('input[data-coa="333333"]').val(formatHelper(Math.round(total_revenue * percent_rsv).toString()));
			$('input[data-coa="444444"]').val(formatHelper(Math.round(total_revenue * percent_ipm).toString()));
			$('input[data-coa="555555"]').val(formatHelper(Math.round(total_revenue * percent_ibc).toString()));
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#total_profit').text('-' + formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}else{
				$('#total_profit').text(formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#percent_profit').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) < 0){
				$('#percent_profit_real').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit_real').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			$('#percent_cri').text(formatRupiahIni(((((total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));

			console.log(total_revenue)
			
		}else if($('#branch').val() == '4'){
			$('input[data-coa="111111"]').val(formatHelper(Math.round(total_revenue * percent_rental).toString()));
			$('input[data-coa="222222"]').val(formatHelper(Math.round(total_revenue * percent_fixed).toString()));
			$('input[data-coa="333333"]').val(formatHelper(Math.round(total_revenue * percent_rsv).toString()));
			$('input[data-coa="444444"]').val(formatHelper(Math.round(total_revenue * percent_ipm).toString()));
			$('input[data-coa="555555"]').val(formatHelper(Math.round(total_revenue * percent_ibc).toString()));
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#total_profit').text('-' + formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}else{
				$('#total_profit').text(formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#percent_profit').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) < 0){
				$('#percent_profit_real').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit_real').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			$('#percent_cri').text(formatRupiahIni(((((total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			
		}*/


		if($('#branch').val() != undefined){
			$('input[data-coa="111111"]').val(formatHelper(Math.round(total_revenue * percent_rental).toString()));
			$('input[data-coa="222222"]').val(formatHelper(Math.round(total_revenue * percent_fixed).toString()));
			$('input[data-coa="333333"]').val(formatHelper(Math.round(total_revenue * percent_rsv).toString()));
			$('input[data-coa="444444"]').val(formatHelper(Math.round(total_revenue * percent_ipm).toString()));
			$('input[data-coa="555555"]').val(formatHelper(Math.round(total_revenue * percent_ibc).toString()));
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#total_profit').text('-' + formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}else{
				$('#total_profit').text(formatRupiahIni((total_revenue - (total_cogs + total_lc + total_mc)).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc)) < 0){
				$('#percent_profit').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			if((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) < 0){
				$('#percent_profit_real').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}else{
				$('#percent_profit_real').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + (total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc))) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			}
			
			$('#percent_cri').text(formatRupiahIni(((((total_revenue * percent_rental) + (total_revenue * percent_fixed) + (total_revenue * percent_rsv) + (total_revenue * percent_ipm) + (total_revenue * percent_ibc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
			
		}
	}
	
	function safeGuard2(){
		var total_rental = 0, total_interest_pm = 0, total_revenue = 0, total_cogs = 0, total_lc = 0, total_mc = 0;
		
		$('input[data-group="1"]').each(function() {
			total_interest_pm += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			total_revenue += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		$('input[data-group="2"]').each(function() {
			total_rental += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			total_cogs += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		$('input[data-group="3"]').each(function() {
			total_rental += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
			total_lc += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		$('input[data-group="4"]').each(function() {
			total_mc += parseFloat($(this).val().replaceAll('.','').replaceAll(',','.'));
		});
		
		total_rental += parseFloat($('input[data-coa="134"]').val().replaceAll('.','').replaceAll(',','.'));
		total_rental += parseFloat($('input[data-coa="139"]').val().replaceAll('.','').replaceAll(',','.'));
		
		var rental_cost = parseFloat($('input[data-coa="111111"]').val().replaceAll('.','').replaceAll(',','.'));
		var fixed_cost = parseFloat($('input[data-coa="222222"]').val().replaceAll('.','').replaceAll(',','.'));
		var rsv_profit = parseFloat($('input[data-coa="333333"]').val().replaceAll('.','').replaceAll(',','.'));
		var iopm = parseFloat($('input[data-coa="444444"]').val().replaceAll('.','').replaceAll(',','.'));
		var iobc = parseFloat($('input[data-coa="555555"]').val().replaceAll('.','').replaceAll(',','.'));
		
		if((total_revenue - (total_cogs + total_lc + total_mc + rental_cost + fixed_cost + rsv_profit + iopm + iobc)) < 0){
			$('#percent_profit_real').text('-' + formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + rental_cost + fixed_cost + rsv_profit + iopm + iobc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
		}else{
			$('#percent_profit_real').text(formatRupiahIni((((total_revenue - (total_cogs + total_lc + total_mc + rental_cost + fixed_cost + rsv_profit + iopm + iobc)) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
		}
		
		$('#percent_cri').text(formatRupiahIni((((rental_cost + fixed_cost + rsv_profit + iopm + iobc) / total_revenue) * 100).toFixed(2).toString().replace('.',',')));
	}
	
	$(function() {
		loadDataTable();
		
		$('#data_content').on('click', '#delete_data_content', function() {
			$(this).closest('tr').remove();
			safeGuard();
		});
	});
	
	function resetFilter() {
		$('#filter_branch').val(null);
		$('#filter_start_date').val(null);
		$('#filter_finish_date').val(null);
		loadDataTable();
   }
   
	function getProjectProduct(val){
		$('#data_content_product').empty();
		if(val !== ''){
			$.ajax({
				url: '{{ url("admin/sales/budgeting_project/get_project_product") }}',
				type: 'GET',
				dataType: 'JSON',
				data: {
				   id: val,
				   branch: $('#branch').val(),
				},
				beforeSend: function() {
				   loadingOpen('#form_data');
				},
				success: function(response) {
					$('#branch').val(response.branch).trigger('change');
					appendData(val);
				},
				error: function() {
				   loadingClose('#form_data');
				   swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			 });
		}else{
			$('#data_content_product').append(`
				<tr>
					<td colspan="6">
						<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
							<span class="font-weight-semibold">Selected project must be at least has 1 products to be shown here.</span>
						</div>
					</td>
				</tr>
			`);
		}
	}

	function appendData(val){
		$('#data_content_product').empty();
		if(val !== ''){
			$.ajax({
				url: '{{ url("admin/sales/budgeting_project/get_project_product") }}',
				type: 'GET',
				dataType: 'JSON',
				data: {
				   id: val,
				   branch: $('#branch').val(),
				},
				beforeSend: function() {
				   loadingOpen('#form_data');
				},
				success: function(response) {
					if(response.product.length > 0){
						$.each(response.product, function(i, val) {
							$('#data_content_product').append(`
							  <tr class="text-center">
								<input type="hidden" value="` + val.id + `" name="product_id[]">
								<input type="hidden" value="` + val.unitraw + `" name="product_unit[]">
								 <td class="align-middle">` + val.product + `</td>
								 <td class="align-middle">
									` + val.carton_sqm + `
								 </td>
								 <td class="align-middle">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<div class="position-relative">
											<input type="text" class="form-control form-control-sm" id="purchase_price` + val.id + `" value="` + val.purchase_price + `" onkeyup="formatRupiah(this);count(` + val.id + `);" name="product_price[]">
											<div class="form-control-feedback font-weight-bold">-</div>
										</div>
									</div>
								 </td>
								 <td class="align-middle">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<div class="position-relative">
											<input type="text" class="form-control form-control-sm" id="sell_price` + val.id + `" value="` + val.sell_price + `" onkeyup="formatRupiah(this);countSell(` + val.id + `);" name="product_price_sell[]">
											<div class="form-control-feedback font-weight-bold">-</div>
										</div>
									</div>
								 </td>
								 <td class="align-middle">
									<div class="form-group form-group-feedback form-group-feedback-right">
										<div class="position-relative">
											<input type="number" class="form-control form-control-sm" id="product_qty` + val.id + `" value="` + val.qty + `" onkeyup="count(` + val.id + `);countSell(` + val.id + `);" name="product_qty[]">
											<div class="form-control-feedback font-weight-bold">` + val.unit + `</div>
										</div>
									</div>
								 </td>   
								 <td class="text-right">
									<span id="total` + val.id + `" class="total">0</span>
								 </td>
								 <td class="text-right">
									<span id="totalsell` + val.id + `" class="totalsell">0</span>
								 </td>
								 <td class="align-middle">
									<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								 </td>
							  </tr>
							`);
							count(val.id);
							countSell(val.id);


							if(i==0 && val.delivery_cost > 0){
								$('#data_content_product').append(`
								<tr class="text-center">
									<td class="align-middle">Delivery cost</td>
									<td class="align-middle">
										-
									</td>
									<td class="align-middle">
										<div class="form-group form-group-feedback form-group-feedback-right">
											<div class="position-relative">
												<input type="text" class="form-control form-control-sm" id="purchase_price` + val.id + `" value="` + val.purchase_price + `" onkeyup="formatRupiah(this);count(` + val.id + `);" name="product_price[]">
												<div class="form-control-feedback font-weight-bold">-</div>
											</div>
										</div>
									</td>
									<td class="align-middle">
										<div class="form-group form-group-feedback form-group-feedback-right">
											<div class="position-relative">
												<input type="text" class="form-control form-control-sm" id="sell_price` + val.id + `" value="` + val.sell_price + `" onkeyup="formatRupiah(this);countSell(` + val.id + `);" name="product_price_sell[]">
												<div class="form-control-feedback font-weight-bold">-</div>
											</div>
										</div>
									</td>
									<td class="align-middle">
										<div class="form-group form-group-feedback form-group-feedback-right">
											<div class="position-relative">
												<input type="number" class="form-control form-control-sm" id="product_qty` + val.id + `" value="` + val.qty + `" onkeyup="count(` + val.id + `);countSell(` + val.id + `);" name="product_qty[]">
												<div class="form-control-feedback font-weight-bold">` + val.unit + `</div>
											</div>
										</div>
									</td>   
									<td class="text-right">
										<span id="total` + val.id + `" class="total">0</span>
									</td>
									<td class="text-right">
										<span id="totalsell` + val.id + `" class="totalsell">0</span>
									</td>
									<td class="align-middle">
										<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									</td>
								</tr>
								`);
							}
						});
						
						countAll();
					}else{
						$('#data_content_product').append(`
							<tr>
								<td colspan="6">
									<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
										<span class="font-weight-semibold">Selected project must be at least has 1 products to be shown here.</span>
									</div>
								</td>
							</tr>
						`);
					}
					
					loadingClose('#form_data');
				},
				error: function() {
				   loadingClose('#form_data');
				   swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			 });
		}else{
			$('#data_content_product').append(`
				<tr>
					<td colspan="6">
						<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
							<span class="font-weight-semibold">Selected project must be at least has 1 products to be shown here.</span>
						</div>
					</td>
				</tr>
			`);
		}
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
	
	function count(id){
		var price = $('#purchase_price' + id).val().replaceAll(".", "").replaceAll(",","."), qty = $('#product_qty' + id).val();
		$('#total' + id).html(formatRupiahIni((parseFloat(price) * parseFloat(qty)).toFixed(2).toString().replace('.',',')));
		
		countAll();
	}
	
	function countSell(id){
		var price = $('#sell_price' + id).val().replaceAll(".", "").replaceAll(",","."), qty = $('#product_qty' + id).val();
		$('#totalsell' + id).html(formatRupiahIni((parseFloat(price) * parseFloat(qty)).toFixed(2).toString().replace('.',',')));
		
		countAll();
	}
	
	function countAll(){
		var total = 0, totalsell = 0;
		
		$( ".total" ).each(function() {
			total += parseFloat($(this).text().replaceAll(".", "").replaceAll(",","."));
		});
		
		$( ".totalsell" ).each(function() {
			totalsell += parseFloat($(this).text().replaceAll(".", "").replaceAll(",","."));
		});
		
		$('#grandtotal').html(formatRupiahIni(total.toFixed(2).replace('.',',')));
		$('#grandtotalsell').html(formatRupiahIni(totalsell.toFixed(2).replace('.',',')));
	}
	
	function showCount(){
		$('input[name^="product_price"]').each(function(){
			$(this).trigger('keyup');
		});
		
		countAll();
	}
	
	function sendMessage(nomor,id){
		if(id){
			var link = '{{ url("admin/approval/detail") }}/' + id;
		}else{
			var link = '{{ url("admin/approval") }}';
		}
		var whatsapptemplate = 'https://wa.me/' + nomor + '?text=' + encodeURIComponent('Halo pak. Mohon approval untuk budgeting project dibawah ini. \n' + link);
		
		window.open(whatsapptemplate, '_blank').focus();
	}
	
	function useGrandTotal(){
		$('#product_total').val($('#grandtotal').text());
		$('input[data-coa="284"]').val($('#grandtotalsell').text());
		$('input[data-coa="285"]').val($('#grandtotalsell').text());
		$('#modal_form .modal-body').animate({
			scrollTop: $('#product_total').offset().top
		}, "slow");
		$('#product_total').focus();
		$('#product_total').trigger('keyup');
	}
	
	function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         //order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/sales/budgeting_project/datatable") }}',
            type: 'GET',
            data: {
				branch: $('#filter_branch').val(),
				start_date: $('#filter_start_date').val(),
				finish_date: $('#filter_finish_date').val()
            },
            beforeSend: function() {
               loadingOpen('#datatable_serverside');
            },
            complete: function() {
               loadingClose('#datatable_serverside');
            },
            error: function() {
               loadingClose('#datatable_serverside');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'user_id', className: 'text-center align-middle', orderable: false },
			{ name: 'project', className: 'text-center align-middle'},
			{ name: 'name', className: 'text-center align-middle', orderable: false },
			{ name: 'branch', className: 'text-center align-middle', orderable: false },
            { name: 'period', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'nominal', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'approved_by', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'checked_by', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }
	
	function reset() {
      $('#form_data').trigger('reset');
      $('#validation_alert').hide();
      $('#validation_content').html('');
	}

	function cancel(){
		reset();
		$('#modal_form').modal('hide');
		$('#btn_create').show();
		$('#btn_update').hide();
		$('#btn_cancel').hide();
	}
	
	function success() {
		reset();
		$('#modal_form').modal('hide');
		$('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
   
   function addDetail(){
		let coa_id   = $('#coa_id option:selected');
		let nominal = $('#nominal');
		let description = $('#description');
		let group = $('#group option:selected');

		if(coa_id.val() && nominal.val()) {
			if(group.val() == 1){
				var color = 'bg-primary';
			}else if(group.val() == 2){
				var color = 'bg-secondary';
			}else if(group.val() == 3){
				var color = 'bg-danger';
			}else if(group.val() == 4){
				var color = 'bg-warning';
			}else if(group.val() == 5){
				var color = 'bg-success';
			}
			
			$('#data_content').append(`
				<tr class="text-center ` + color + `">
				   <input type="hidden" name="coa_detail[]" value="` + coa_id.val() + `">
				   <input type="hidden" name="nominal_detail[]" value="` + nominal.val() + `">
				   <input type="hidden" name="description_detail[]" value="` + description.val() + `">
				   <input type="hidden" name="group_detail[]" value="` + group.val() + `">

				   <td class="align-middle">` + coa_id.text() + `</td>   
				   <td class="align-middle">` + nominal.val() + `</td>
				   <td class="align-middle">` + group.text() + `</td>
				   <td class="align-middle">` + description.val() + `</td>   
				   <td class="align-middle">
					  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				   </td>
				</tr>
			`);
		} else {
			swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
		}
   }
   
   function create() {
		$.ajax({
			 url: '{{ url("admin/sales/budgeting_project/create") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: $('#form_data').serialize(),
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
	
	function toShow() {
		$('#modal_form').modal('show');
		$('#validation_alert').hide();
		$('#validation_content').html('');
		$('#btn_create').hide();
		$('#btn_update').show();
		$('#btn_cancel').show();
	}
	
	function show(id) {
		toShow();
		$.ajax({
			 url: '{{ url("admin/sales/budgeting_project/show") }}',
			 type: 'GET',
			 dataType: 'JSON',
			 data: {
				id: id
			 },
			 beforeSend: function() {
				loadingOpen('#modal_form');
			 },
			 success: function(response) {
				loadingClose('#modal_form');
				$('#project_id').empty();
				$('#project_id').append(`
					<option value="` + response.info.project_id + `">` + response.info.project_info + `</option>
				`);
				$('#name').val(response.info.name);
				$('#branch').val(response.info.branch);
				$('#startmonth').val(response.info.month_start);
				$('#endmonth').val(response.info.month_end);
				$('#currency_id').val(response.info.currency_id);
				$('#percent_rental').val(response.info.percent_rental.replaceAll('.',','));
				$('#percent_fixed').val(response.info.percent_fixed.replaceAll('.',','));
				$('#percent_rsv').val(response.info.percent_rsv.replaceAll('.',','));
				$('#percent_ipm').val(response.info.percent_ipm.replaceAll('.',','));
				$('#percent_ibc').val(response.info.percent_ibc.replaceAll('.',','));
				$('#percent_import').val(response.info.percent_import.replaceAll('.',','));
				$('#percent_safe').val(response.info.percent_safe.replaceAll('.',','));
				$('#percent_ppn').val(response.info.percent_ppn.replaceAll('.',','));
				$('#percent_pph').val(response.info.percent_pph.replaceAll('.',','));
				$('#percent_mkj').val(response.info.percent_mkj.replaceAll('.',','));
				$('#percent_pta').val(response.info.percent_pta.replaceAll('.',','));
				$('#percent_mid').val(response.info.percent_mid.replaceAll('.',','));
				$('#percent_scom').val(response.info.percent_scom.replaceAll('.',','));
				$('#exchange_rate').val(formatRupiahIni(parseFloat(response.info.help_exchange_rate).toFixed(2).replace('.',',')));
				$('#number_container').val(formatRupiahIni(parseFloat(response.info.help_container_no).toFixed(2).replace('.',',')));
				$('#ls_cost').val(formatRupiahIni(parseFloat(response.info.help_ls_cost).toFixed(2).replace('.',',')));
				$('#product_total').val(formatRupiahIni(parseFloat(response.info.help_product_cost).toFixed(2).replace('.',',')));
				$('#qty_container').val(formatRupiahIni(parseFloat(response.info.help_container_qty).toFixed(2).replace('.',',')));
				$('#freight_cost').val(formatRupiahIni(parseFloat(response.info.help_freight_cost).toFixed(2).replace('.',',')));
				$('#emkl').val(formatRupiahIni(parseFloat(response.info.help_emkl_cost).toFixed(2).replace('.',',')));
				$('#remarks').val(response.info.remarks);
				
				$('#data_content').html('');
				
				var adacri = false;
				
				$.each(response.detail, function(i, val) {
					if(val.group_count == '1'){
						var color = 'bg-primary', romawi = 'Revenue';
					}else if(val.group_count == '2'){
						var color = 'bg-secondary', romawi = 'Product Buying Cost';
					}else if(val.group_count == '3'){
						var color = 'bg-danger', romawi = 'Landed Cost';
					}else if(val.group_count == '4'){
						var color = 'bg-warning', romawi = 'Marketing Cost';
					}else if(val.group_count == '5'){
						var color = 'bg-success', romawi = 'Companys Rsv Income (CRI)';
					}
					
					var sg = 'safeGuard();';
					
					if(val.coa_id == 111111 || val.coa_id == 222222 || val.coa_id == 333333 || val.coa_id == 444444 || val.coa_id == 555555){
						sg = 'safeGuard2();';
					}
					
					$('#data_content').append(`
						<tr class="` + color + `">
						   <input type="hidden" name="coa_detail[]" value="` + val.coa_id + `">
						   <input type="hidden" name="group_detail[]" value="` + val.group_count + `">
						   <td>` + val.coa_name + `</td>   
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="` + val.nominal + `" onkeyup="formatRupiah(this);` + sg + `" data-coa="` + val.coa_id + `" data-group="` + val.group_count + `"></td>
						   <td class="align-middle">` + romawi + `</td>
						   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..." value="` + val.description + `"></td>   
						   <td class="align-middle">
							  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
					`);
					
					if(val.coa_id == 111111){
						adacri = true;
					}
				});
				
				if(adacri == false){
					$('#data_content').append(`
						<tr class="bg-success">
						   <input type="hidden" name="coa_detail[]" value="111111">
						   <input type="hidden" name="group_detail[]" value="5">
						   <td>Rental Cost</td>   
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard2();" data-coa="111111"></td>
						   <td class="align-middle">Company's Rsv Income (CRI)</td>
						   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..." value="Rsv Profit & Other Cost"></td>   
						   <td class="align-middle">
							  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
						<tr class="bg-success">
						   <input type="hidden" name="coa_detail[]" value="222222">
						   <input type="hidden" name="group_detail[]" value="5">
						   <td>Fixed Cost</td>   
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard2();" data-coa="222222"></td>
						   <td class="align-middle">Company's Rsv Income (CRI)</td>
						   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..." value="Rsv Profit & Other Cost"></td>   
						   <td class="align-middle">
							  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
						<tr class="bg-success">
						   <input type="hidden" name="coa_detail[]" value="333333">
						   <input type="hidden" name="group_detail[]" value="5">
						   <td>RSV Profit</td>   
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard2();" data-coa="333333"></td>
						   <td class="align-middle">Company's Rsv Income (CRI)</td>
						   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..." value="Rsv Profit & Other Cost"></td>   
						   <td class="align-middle">
							  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
						<tr class="bg-success">
						   <input type="hidden" name="coa_detail[]" value="444444">
						   <input type="hidden" name="group_detail[]" value="5">
						   <td>Interest on Payment Method</td>   
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard2();" data-coa="444444"></td>
						   <td class="align-middle">Company's Rsv Income (CRI)</td>
						   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..." value="Rsv Profit & Other Cost"></td>   
						   <td class="align-middle">
							  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
						<tr class="bg-success">
						   <input type="hidden" name="coa_detail[]" value="555555">
						   <input type="hidden" name="group_detail[]" value="5">
						   <td>Interest on Buying Capital</td>   
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard2();" data-coa="555555"></td>
						   <td class="align-middle">Company's Rsv Income (CRI)</td>
						   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..." value="Rsv Profit & Other Cost"></td>   
						   <td class="align-middle">
							  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
					`);
				}
				
				$('#data_content_product').empty();
				if(response.product.length > 0){
					$.each(response.product, function(i, val) {
						$('#data_content_product').append(`
						  <tr class="text-center">
							<input type="hidden" value="` + val.id + `" name="product_id[]">
							<input type="hidden" value="` + val.unitraw + `" name="product_unit[]">
							 <td class="align-middle">` + val.product + `</td>
							 <td class="align-middle">
								` + val.carton_sqm + `
							 </td>
							 <td class="align-middle">
								<div class="form-group form-group-feedback form-group-feedback-right">
									<div class="position-relative">
										<input type="text" class="form-control form-control-sm" id="purchase_price` + val.id + `" value="` + val.purchase_price + `" onkeyup="formatRupiah(this);count(` + val.id + `);" name="product_price[]">
										<div class="form-control-feedback font-weight-bold">-</div>
									</div>
								</div>
							 </td>
							 <td class="align-middle">
								<div class="form-group form-group-feedback form-group-feedback-right">
									<div class="position-relative">
										<input type="text" class="form-control form-control-sm" id="sell_price` + val.id + `" value="` + val.sell_price + `" onkeyup="formatRupiah(this);countSell(` + val.id + `);" name="product_price_sell[]">
										<div class="form-control-feedback font-weight-bold">-</div>
									</div>
								</div>
							 </td>
							 <td class="align-middle">
								<div class="form-group form-group-feedback form-group-feedback-right">
									<div class="position-relative">
										<input type="number" class="form-control form-control-sm" id="product_qty` + val.id + `" value="` + val.qty + `" onkeyup="count(` + val.id + `);countSell(` + val.id + `);" name="product_qty[]">
										<div class="form-control-feedback font-weight-bold">` + val.unit + `</div>
									</div>
								</div>
							 </td>   
							 <td class="text-right">
								<span id="total` + val.id + `" class="total">` + val.total + `</span>
							 </td>
							 <td class="text-right">
								<span id="totalsell` + val.id + `" class="totalsell">` + val.total_sell + `</span>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
					});
					
					showCount();
				}else{
					$('#data_content_product').append(` 
						<tr>
							<td colspan="6">
								<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
									<span class="font-weight-semibold">Selected project must be at least has 1 products to be shown here.</span>
								</div>
							</td>
						</tr>
					`);
				}
				
				safeGuard();
				
				$('#btn_update').attr('onclick', 'update(' + id + ')');
			 },
			 error: function() {
				cancel();
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
	}
	
	function update(id) {
      $.ajax({
         url: '{{ url("admin/sales/budgeting_project/update") }}' + '/' + id,
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert').hide();
            $('#validation_content').html('');
            loadingOpen('#modal_form');
         },
         success: function(response) {
            loadingClose('#modal_form');
            if(response.status == 200) {
               success();
               notif('success', 'bg-success', response.message);
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
            loadingClose('#modal_form');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
	}
	
	function destroy(id) {
      var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-trash"></i>', 'btn bg-success ml-1', function() {
               $.ajax({
                  url: '{{ url("admin/sales/budgeting_project/destroy") }}',
                  type: 'POST',
                  dataType: 'JSON',
                  data: {
                     id: id
                  },
                  headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function(response) {
                     if(response.status == 200) {
                        $('#datatable_serverside').DataTable().ajax.reload(null, false);
                        notif('success', 'bg-success', response.message);
                        notyConfirm.close();
                     } else {
                        notif('error', 'bg-danger', response.message);
                     }
                  },
                  error: function() {
                     swalInit.fire({
                        title: 'Server Error',
                        text: 'Please contact developer',
                        type: 'error'
                     });
                  }
               });
            })
         ]
      }).show();
	}
	
	function productCalculator(){
		if($('#group').val() == '2'){
			$('#productCalculator').removeClass('d-none');
		}else{
			$('#productCalculator').addClass('d-none');
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