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
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" onclick="cancel()" data-toggle="modal" data-target="#modal_form">
							<b><i class="icon-plus3"></i></b> Add
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
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
						  <input type="month" name="filter_start_date" id="filter_start_date" class="form-control">
						  <span class="input-group-text">to</span>
						  <input type="month" name="filter_finish_date" id="filter_finish_date" class="form-control">
					   </div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group text-right">
					   <button type="button" onclick="loadDataTable()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
					   <button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
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
                        <th>Nominal</th>
						<th>Updated At</th>
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
            <h5 class="modal-title" id="exampleModalLabel">Form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="form_data">
               <div class="alert alert-danger" id="validation_alert" style="display:none;">
                  <ul id="validation_content"></ul>
               </div>
				<div class="row justify-content-center">
					<div class="col-md-3">
						 <div class="form-group">
							<label>Project Name :<sup class="text-danger">*</sup></label>
							<select name="project_id" id="project_id"></select>
						 </div>
					</div>
					<div class="col-md-3">
						 <div class="form-group">
							<label>Name :<sup class="text-danger">*</sup></label>
							<input type="text" name="name" id="name" class="form-control" placeholder="Enter project name">
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
				</div>
				<div class="row justify-content-center">
					<div class="col-md-12 d-none" id="productCalculator">
						<div class="form-group"><hr></div>
						<h3 class="text-center">Product Buying Cost Calculation Helper</h3>
						<div class="form-group"><hr></div>
						<div class="row">
						   <div class="col-md-10">
							  <div class="form-group">
								 <select name="product_id" id="product_id"></select>
							  </div>
						   </div>
						   <div class="col-md-2">
							  <div class="form-group">
								 <button type="button" onclick="addProduct()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add</button>
							  </div>
						   </div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th>Product</th>
								   <th>Rate Unit</th>
								   <th>Sqm</th>
								   <th>Price</th>
								   <th>Qty</th>
								   <th>Total</th>
								   <th>#</th>
								</tr>
							 </thead>
							 <tbody id="data_content_product"></tbody>
							 <tfoot>
								<tr>
									<th class="text-right" colspan="5">Grandtotal</th>
									<th class="text-right" id="grandtotal" style="font-size:200%;">0</th>
									<th class="text-right"><button type="button" onclick="useGrandTotal()" class="btn bg-success btn-sm btn-block"><i class="icon-circle-left2"></i> Use</button></th>
								</tr>
							 </tfoot>
							</table>
						</div>
						<div class="form-group"><hr></div>
						<h3 class="text-center">End Product Helper</h3>
					</div>
					<div class="col-md-12 text-center">
						<div class="form-group"><hr></div>
						<h3>Detail Plan</h3>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>COA :<span class="text-danger">*</span></label>
						  <select name="coa_id" id="coa_id" class="select2">
							   <option value="">-- None --</option>
							   @foreach($coa as $c)
									@if(in_array(substr($c->code,0,1),array('4','5','6','7')))
										@foreach($c->child() as $rowchild)
											@if(count($rowchild->child()) == 0)
												<option value="{{ $rowchild->id }}">[{{ $rowchild->code }}] {{ $rowchild->name }}</option>
											@endif
											@foreach($rowchild->child() as $rowgrandchild)
												@if(count($rowgrandchild->child()) == 0)
													<option value="{{ $rowgrandchild->id }}">[{{ $rowgrandchild->code }}] {{ $rowgrandchild->name }}</option>
												@endif
												@foreach($rowgrandchild->child() as $rowgrandgrandchild)
													<option value="{{ $rowgrandgrandchild->id }}">[{{ $rowgrandgrandchild->code }}] {{ $rowgrandgrandchild->name }}</option>
												@endforeach
											@endforeach
										@endforeach
									@endif
							   @endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Nominal :<span class="text-danger">*</span></label>
						  <input type="text" name="nominal" id="nominal" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Group :<sup class="text-danger">*</sup></label>
							<select name="group" id="group" class="form-control" onchange="productCalculator()">
							   <option value="1">I - Revenue</option>
							   <option value="2">II - Product Buying Cost</option>
							   <option value="3">III - Landed Cost</option>
							   <option value="4">IV - Marketing Cost</option>
							   <option value="5">V - Company's Rsv Income (CRI)</option>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Description :</label>
						  <textarea name="description" id="description" class="form-control" placeholder="Enter description" rows="1"></textarea>
						</div>
					</div>
					<div class="col-md-12 text-center">
						<div class="form-group"><hr></div>
						<h3>Percentage</h3>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Import Duty (%) :</label>
						  <input type="text" name="percent_import" id="percent_import" class="form-control form-control-sm" placeholder="Enter..." value="5" onkeyup="formatRupiah(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Safe Guard (%) :</label>
						  <input type="text" name="percent_safe" id="percent_safe" class="form-control form-control-sm" placeholder="Enter..." value="19" onkeyup="formatRupiah(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>PPN (%) :</label>
						  <input type="text" name="percent_ppn" id="percent_ppn" class="form-control form-control-sm" placeholder="Enter..." value="10" onkeyup="formatRupiah(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>PPH (%) :</label>
						  <input type="text" name="percent_pph" id="percent_pph" class="form-control form-control-sm" placeholder="Enter..." value="7,5" onkeyup="formatRupiah(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Fee MKJ (%) :</label>
						  <input type="text" name="percent_mkj" id="percent_mkj" class="form-control form-control-sm" placeholder="Enter..." value="5" onkeyup="formatRupiah(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Fee PTA (%) :</label>
						  <input type="text" name="percent_pta" id="percent_pta" class="form-control form-control-sm" placeholder="Enter..." value="5" onkeyup="formatRupiah(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Middle Man (%) :</label>
						  <input type="text" name="percent_mid" id="percent_mid" class="form-control form-control-sm" placeholder="Enter..." value="3" onkeyup="formatRupiah(this);">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Sales Commision (%) :</label>
						  <input type="text" name="percent_scom" id="percent_scom" class="form-control form-control-sm" placeholder="Enter..." value="1" onkeyup="formatRupiah(this);">
						</div>
					</div>
					<div class="col-md-12 text-center">
						<div class="form-group"><hr></div>
						<h3>Helper</h3>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Exchange Rate :</label>
						  <input type="text" name="exchange_rate" id="exchange_rate" class="form-control form-control-sm" placeholder="Enter nominal" value="15000" onkeyup="formatRupiah(this);hitung();">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Number of Container :</label>
						  <input type="text" name="number_container" id="number_container" class="form-control form-control-sm" placeholder="Enter nominal" value="1" onkeyup="formatRupiah(this);hitung();">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>LS Cost :</label>
						  <input type="text" name="ls_cost" id="ls_cost" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Product Total :</label>
						  <input type="text" name="product_total" id="product_total" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Quantity of Container :</label>
						  <input type="text" name="qty_container" id="qty_container" class="form-control form-control-sm" placeholder="Enter nominal" value="1" onkeyup="formatRupiah(this);hitung2();">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>Freight Cost :</label>
						  <input type="text" name="freight_cost" id="freight_cost" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <label>EMKL :</label>
						  <input type="text" name="emkl" id="emkl" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group"><hr></div>
						 <div class="form-group">
							<button type="button" class="btn bg-success col-12 d-none" onclick="addDetail()"><i class="icon-plus22"></i> Add</button>
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
								@if(in_array($c->code,array('4.000.01.01','4.000.01.02')))
									<tr class="bg-primary">
									   <input type="hidden" name="coa_detail[]" value="{{ $c->id }}">
									   <input type="hidden" name="group_detail[]" value="1">
									   <td>[{{ $c->code }}] {{ $c->name }}</td>   
									   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $c->id }}" data-group="1"></td>
									   <td class="align-middle">Revenue</td>
									   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
									   <td class="align-middle">
										  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
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
										   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $c->id }}" data-group="2"></td>
										   <td class="align-middle">Product Buying Cost</td>
										   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
										   <td class="align-middle">
											  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
										   </td>
										</tr>
									@endif
									@foreach($c->child() as $rowchild)
										@if(count($rowchild->child()) == 0)
											<tr class="bg-secondary">
											   <input type="hidden" name="coa_detail[]" value="{{ $rowchild->id }}">
											   <input type="hidden" name="group_detail[]" value="2">
											   <td>[{{ $rowchild->code }}] {{ $rowchild->name }}</td>   
											   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $rowchild->id }}" data-group="2"></td>
											   <td class="align-middle">Product Buying Cost</td>
											   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
											   <td class="align-middle">
												  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
											   </td>
											</tr>
										@endif
										@foreach($rowchild->child() as $rowgrandchild)
											@if(count($rowgrandchild->child()) == 0)
												<tr class="bg-secondary">
												   <input type="hidden" name="coa_detail[]" value="{{ $rowgrandchild->id }}">
												   <input type="hidden" name="group_detail[]" value="2">
												   <td>[{{ $rowgrandchild->code }}] {{ $rowgrandchild->name }}</td>   
												   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $rowgrandchild->id }}" data-group="2"></td>
												   <td class="align-middle">Product Buying Cost</td>
												   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
												   <td class="align-middle">
													  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
												   </td>
												</tr>
											@endif
											@foreach($rowgrandchild->child() as $rowgrandgrandchild)
												<tr class="bg-secondary">
												   <input type="hidden" name="coa_detail[]" value="{{ $rowgrandgrandchild->id }}">
												   <input type="hidden" name="group_detail[]" value="2">
												   <td>[{{ $rowgrandgrandchild->code }}] {{ $rowgrandgrandchild->name }}</td>   
												   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $rowgrandgrandchild->id }}" data-group="2"></td>
												   <td class="align-middle">Product Buying Cost</td>
												   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
												   <td class="align-middle">
													  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
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
											   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $rowchild->id }}" data-group="3"></td>
											   <td class="align-middle">Landed Cost</td>
											   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
											   <td class="align-middle">
												  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
											   </td>
											</tr>
										@endif
										@foreach($rowchild->child() as $rowgrandchild)
											@if(count($rowgrandchild->child()) == 0)
												<tr class="bg-danger">
												   <input type="hidden" name="coa_detail[]" value="{{ $rowgrandchild->id }}">
												   <input type="hidden" name="group_detail[]" value="3">
												   <td>[{{ $rowgrandchild->code }}] {{ $rowgrandchild->name }}</td>   
												   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $rowgrandchild->id }}" data-group="3"></td>
												   <td class="align-middle">Landed Cost</td>
												   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
												   <td class="align-middle">
													  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
												   </td>
												</tr>
											@endif
											@foreach($rowgrandchild->child() as $rowgrandgrandchild)
												<tr class="bg-danger">
												   <input type="hidden" name="coa_detail[]" value="{{ $rowgrandgrandchild->id }}">
												   <input type="hidden" name="group_detail[]" value="3">
												   <td>[{{ $rowgrandgrandchild->code }}] {{ $rowgrandgrandchild->name }}</td>   
												   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $rowgrandgrandchild->id }}" data-group="3"></td>
												   <td class="align-middle">Landed Cost</td>
												   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
												   <td class="align-middle">
													  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
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
									   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this);safeGuard();" data-coa="{{ $c->id }}" data-group="4"></td>
									   <td class="align-middle">Marketing Cost</td>
									   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..."></td>   
									   <td class="align-middle">
										  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									   </td>
									</tr>
								@endif
						   @endforeach
							<tr class="bg-success">
							   <input type="hidden" name="coa_detail[]" value="111111">
							   <input type="hidden" name="group_detail[]" value="5">
							   <td>Rental Cost</td>   
							   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="111111"></td>
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
							   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="222222"></td>
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
							   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="333333"></td>
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
							   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="444444"></td>
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
							   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="555555"></td>
							   <td class="align-middle">Company's Rsv Income (CRI)</td>
							   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..." value="Rsv Profit & Other Cost"></td>   
							   <td class="align-middle">
								  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							   </td>
							</tr>
						 </tbody>
						</table>
					</div>
				</div>
            </form>
         </div>
         <div class="modal-footer bg-light">
			<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
            <button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()"><i class="icon-cross3"></i> Cancel</button>
            <button type="button" class="btn bg-warning" id="btn_update" onclick="update()" style="display:none;"><i class="icon-pencil7"></i> Save</button>
            <button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
         </div>
      </div>
   </div>
</div>

<script>
	$(function() {
		select2ServerSide('#project_id', '{{ url("admin/select2/project") }}');
		select2ServerSide('#product_id', '{{ url("admin/select2/product") }}');
		
		$('#data_content_product').on('click', '#delete_data_product', function() {
			 $(this).closest('tr').remove();
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
		});
	});
	
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
	
	function addProduct() {
		var id = $('#product_id');

		if(id.val()) {
		  
         $.ajax({
            url: '{{ url("admin/accounting/budgeting_project/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: id.val()
            },
            beforeSend: function() {
               loadingOpen('#form_data');
            },
            success: function(response) {
				if(response.error == '500'){
					
					var msg = '';
					
					$.each(response.message, function(i, val) {
						msg = msg + val;
					});
					
					swalInit.fire('Error product!', msg, 'error');
					
				}else{
					var same = false;
					
					$('input[name^="product_id"]').each(function() {
						if($(this).val() == response.id){
							same = true;
						}
					});
					
					if(same == false){
					   id.val(null).trigger('change');

					   $('#data_content_product').append(`
						  <tr class="text-center">
							 <td class="align-middle">` + response.product + `</td>
							 <td class="align-middle">
								` + response.rate_unit + `
							 </td>
							 <td class="align-middle">
								` + response.carton_sqm + `
							 </td>
							 <td class="align-middle">
								` + response.purchase_price + `
							 </td>  
							 <td class="align-middle">
								<input type="number" class="form-control form-control-sm" value="0" data-id="` + response.id + `" data-price="` + response.purchase_price + `" data-sqm="` + response.sqm + `" onkeyup="count(this)">
							 </td>   
							 <td class="text-right">
								<span id="total` + response.id + `" class="total">0</span>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
					   `);
					}else{
						swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
					}
				}
				
				loadingClose('#form_data');
            },
            error: function() {
               loadingClose('#form_data');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
      } else {
         swalInit.fire('Ooppsss!', 'Please select a product', 'info');
      }
	}
	
	function count(element){
		var id = $(element).data('id'), price = $(element).data('price'), sqm = $(element).data('sqm'), qty = $(element).val(), total = 0;
		
		$('#total' + id).html((parseFloat(price) * parseFloat(sqm) * parseFloat(qty)).toString());
		
		$( ".total" ).each(function() {
			total += parseFloat($(this).text());
		});
		
		$('#grandtotal').html(total.toString());
	}
	
	function useGrandTotal(){
		$('#nominal').val($('#grandtotal').text());
		$('#modal_form .modal-body').animate({
			scrollTop: $('#nominal').offset().top
		}, "slow");
		$('#nominal').focus();
	}
	
	function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         //order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/accounting/budgeting_project/datatable") }}',
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
			{ name: 'updated_at', className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }
	
	function reset() {
      $('#form_data').trigger('reset');
      //$('#data_content').html('');
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
			 url: '{{ url("admin/accounting/budgeting_project/create") }}',
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
			 url: '{{ url("admin/accounting/budgeting_project/show") }}',
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
				$('#percent_import').val(response.info.percent_import.replaceAll('.',','));
				$('#percent_safe').val(response.info.percent_safe.replaceAll('.',','));
				$('#percent_ppn').val(response.info.percent_ppn.replaceAll('.',','));
				$('#percent_pph').val(response.info.percent_pph.replaceAll('.',','));
				$('#percent_mkj').val(response.info.percent_mkj.replaceAll('.',','));
				$('#percent_pta').val(response.info.percent_pta.replaceAll('.',','));
				$('#percent_mid').val(response.info.percent_mid.replaceAll('.',','));
				$('#percent_scom').val(response.info.percent_scom.replaceAll('.',','));
				
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
					
					$('#data_content').append(`
						<tr class="` + color + `">
						   <input type="hidden" name="coa_detail[]" value="` + val.coa_id + `">
						   <input type="hidden" name="group_detail[]" value="` + val.group_count + `">
						   <td>` + val.coa_name + `</td>   
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="` + val.nominal + `" onkeyup="formatRupiah(this);safeGuard();" data-coa="` + val.coa_id + `" data-group="` + val.group_count + `"></td>
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
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="111111"></td>
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
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="222222"></td>
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
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="333333"></td>
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
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="444444"></td>
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
						   <td class="align-middle"><input type="text" name="nominal_detail[]" class="form-control form-control-sm" value="0" onkeyup="formatRupiah(this)" data-coa="555555"></td>
						   <td class="align-middle">Company's Rsv Income (CRI)</td>
						   <td class="align-middle"><input type="text" name="description_detail[]" class="form-control form-control-sm" placeholder="Description here..." value="Rsv Profit & Other Cost"></td>   
						   <td class="align-middle">
							  <button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
					`);
				}
				
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
         url: '{{ url("admin/accounting/budgeting_project/update") }}' + '/' + id,
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
                  url: '{{ url("admin/accounting/budgeting_project/destroy") }}',
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