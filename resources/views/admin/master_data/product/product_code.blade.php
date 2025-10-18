<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">SMB Item Code</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Product</a>
					<span class="breadcrumb-item active">SMB Item Code</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <div class="card">
			<div class="card-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Brand :</label>
                     <select name="filter_brand_id" id="filter_brand_id" class="select2">
                     <option value="">All</option>
                     @foreach($brand as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                     @endforeach
                  </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Country :</label>
                     <select name="filter_country_id" id="filter_country_id" class="select2">
                     <option value="">All</option>
                     @foreach($country as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                     @endforeach
                  </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Stock :</label>
                     <select name="filter_stock" id="filter_stock" class="custom-select">
                     <option value="">All</option>
                     <option value="not_available">Not Available</option>
                     <option value="limited">Limited</option>
                     <option value="ready">Ready</option>
                  </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Shading :</label>
                     <select name="filter_shading" id="filter_shading" class="custom-select">
                     <option value="">All</option>
                     <option value="has_shading">Has Shading</option>
                     <option value="no_shading">No Shading</option>
                  </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Status :</label>
                     <select name="filter_status" id="filter_status" class="custom-select">
                     <option value="">All</option>
                     <option value="1">Active</option>
                     <option value="2">Not Active</option>
                  </select>
                  </div>
               </div>
			   <div class="col-md-3">
                  <div class="form-group">
                     <label>Check :</label>
                     <select name="filter_check" id="filter_check" class="custom-select">
                     <option value="">All</option>
                     <option value="1">Not Checked</option>
                     <option value="2">Already Checked</option>
                  </select>
                  </div>
               </div>
			   <div class="col-md-3">
                  <div class="form-group">
                     <label>Length :</label>
                     <input type="text" name="filter_length" id="filter_length" class="form-control" placeholder="Ex: 80">
                  </div>
               </div>
			   <div class="col-md-3">
                  <div class="form-group">
                     <label>Width :</label>
                     <input type="text" name="filter_width" id="filter_width" class="form-control" placeholder="Ex: 160">
                  </div>
               </div>
            </div>
            <div class="form-group"><hr></div>
            <div class="form-group mb-0">
               <div class="text-right">
                  <button type="button" class="btn bg-teal mr-2" onclick="loadDataTable()"><i class="icon-search4"></i> Search</button>
				  <button type="button" class="btn bg-danger" onclick="resetFilter()"><i class="icon-sync"></i></button>
               </div>
            </div>
			</div>
		</div>
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of SMB Item Codes</h5>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
                        <th>Shade</th>
						<th>SMB Code</th>
                        <th>Supplier Code</th>
                        <th>Stock</th>
						<th>Check</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
               </table>
            </div>
			</div>
		</div>
	</div>

<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Form SMB Item's Code</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="form_data">
               <div class="alert alert-danger" id="validation_alert" style="display:none;">
                  <ul id="validation_content"></ul>
               </div>
               <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                  <li class="nav-item">
                     <a href="#highlighted-justified-tab1" class="nav-link active" data-toggle="tab">Data</a>
                  </li>
                  <li class="nav-item">
                     <a href="#highlighted-justified-tab2" class="nav-link" data-toggle="tab">Stock</a>
                  </li>
                  <li class="nav-item">
                     <a href="#highlighted-justified-tab3" class="nav-link" data-toggle="tab">Shade</a>
                  </li>
                  <li class="nav-item">
                     <a href="#highlighted-justified-tab4" class="nav-link" data-toggle="tab">Description</a>
                  </li>
				  <li class="nav-item">
                     <a href="#highlighted-justified-tab5" class="nav-link" data-toggle="tab">Architect Design</a>
                  </li>
               </ul>
               <div class="tab-content">
                  <div class="tab-pane fade show active" id="highlighted-justified-tab1">
                     <p class="mt-4">
                        <div class="form-group">
                           <label>Code :</label>
                           <input type="text" name="code" id="code" class="form-control" placeholder="Auto Generate" readonly>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Type :<span class="text-danger">*</span></label>
                                 <select name="type_id" id="type_id" onchange="generateCode()"></select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Hs Code :<span class="text-danger">*</span></label>
                                 <select name="hs_code_id" id="hs_code_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($hs_code as $hs)
                                       <option value="{{ $hs->id }}">({{ $hs->code }}) {{ $hs->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Company :<span class="text-danger">*</span></label>
                                 <select name="company_id" id="company_id" class="select2" onchange="generateCode()">
                                    <option value="">-- Choose --</option>
                                    @foreach($company as $c)
                                       <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Brand :<span class="text-danger">*</span></label>
                                 <select name="brand_id" id="brand_id" class="select2" onchange="generateCode()">
                                    <option value="">-- Choose --</option>
                                    @foreach($brand as $b)
                                       <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Country :<span class="text-danger">*</span></label>
                                 <select name="country_id" id="country_id" class="select2" onchange="generateCode()">
                                    <option value="">-- Choose --</option>
                                    @foreach($country as $c)
                                       <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Supplier :<span class="text-danger">*</span></label>
                                 <select name="supplier_id" id="supplier_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($supplier as $s)
                                       <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Grade :<span class="text-danger">*</span></label>
                                 <select name="grade_id" id="grade_id" class="select2" onchange="generateCode()">
                                    <option value="">-- Choose --</option>
                                    @foreach($grade as $g)
                                       <option value="{{ $g->id }}">{{ $g->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group">
                                 <label>Check :<span class="text-danger">*</span></label>
                                 <select name="check" id="check" class="custom-select">
                                    <option value="1">Not Checked</option>
                                    <option value="2">Already Checked</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="highlighted-justified-tab2">
                     <p class="mt-4">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Pcs :</label>
                                 <div class="position-relative">
                                    <input type="number" name="carton_pcs" id="carton_pcs" class="form-control" onkeyup="formula()" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">/ Carton</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Carton :</label>
                                 <div class="position-relative">
                                    <input type="number" name="carton_pallet" id="carton_pallet" class="form-control" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">/ Pallet</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>SQM :</label>
                                 <div class="position-relative">
                                    <input type="number" name="carton_sqm" id="carton_sqm" class="form-control" placeholder="0" disabled>
                                    <div class="form-control-feedback font-weight-bold">/ Carton</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Cubic Meters :<span class="text-danger">*</span></label>
                                 <div class="position-relative">
                                    <input type="number" name="cubic_meter" id="cubic_meter" class="form-control" disabled>
                                    <div class="form-control-feedback font-weight-bold">/ Stock Unit</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Stock Unit :<span class="text-danger">*</span></label>
                                 <div class="position-relative">
                                    <input type="number" name="container_stock" id="container_stock" class="form-control" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">/ Container</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Standart Container :<span class="text-danger">*</span></label>
                                 <select name="container_standart" id="container_standart" class="custom-select">
                                    <option value="">-- Choose --</option>
                                    <option value="1">20 Feet</option>
                                    <option value="2">40 Feet</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Max Stock Unit :<span class="text-danger">*</span></label>
                                 <div class="position-relative">
                                    <input type="number" name="container_max_stock" id="container_max_stock" class="form-control" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">/ Container</div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="highlighted-justified-tab3">
                     <p class="mt-4">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Warehouse :<span class="text-danger">*</span></label>
                                 <select name="shading_warehouse" id="shading_warehouse" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($warehouse as $w)
                                       <option value="{{ $w->code }};{{ $w->name }}">{{ '['.$w->code.']'.$w->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Code:<span class="text-danger">*</span></label>
                                 <input type="text" name="shading_stock_code" id="shading_stock_code" class="form-control" placeholder="Enter code ventura">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Shading:<span class="text-danger">*</span></label>
                                 <input type="text" name="shading_code" id="shading_code" class="form-control" placeholder="Enter code">
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="text-right">
                              <button type="button" class="btn btn-success" onclick="addShading()"><i class="icon-plus3"></i> Add New</button>
                           </div>
                        </div>
                        <div class="form-group"><hr></div>
                        <div class="table-responsive">
                           <table id="datatable_shading" class="table table-bordered table-striped w-100">
                              <thead class="bg-info">
                                 <tr class="text-center">
                                    <th>Warehouse</th>
                                    <th>Ventura</th>
                                    <th>Code</th>
                                    <th>Delete</th>
                                 </tr>
                              </thead>
                              <tbody class="text-center" id="data_shading"></tbody>
                           </table>
                        </div>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="highlighted-justified-tab4">
                     <p class="mt-4">
                        <textarea name="description" id="description" class="form-control" rows="8" placeholder="Enter description"></textarea>
                     </p>
                  </div>
				  <div class="tab-pane fade" id="highlighted-justified-tab5">
                     <p class="mt-4">
                        <div class="form-group">
							<label>File :</label>
							<div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="file" name="file" class="form-control h-auto filecode">
							   </div>
							</div>
						</div>
                     </p>
					 <div class="form-group"><hr></div>
					<h5 class="card-title">Preview Design</h5>
					<div class="form-group text-center" id="previewImg">
						<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
					</div>
                  </div>
               </div>
               <div class="form-group text-center mt-4">
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" value="2">
                        Not Active
                     </label>
                  </div>
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" value="1" checked>
                        Active
                     </label>
                  </div>
               </div>
            </form>
			<div class="form-group"><hr></div>
			<div class="form-group">
				<div class="row p-3" style="background-color:#d9d9d9 !important;">
					<div class="col-md-12">
						<h3 class="text-center">Master</h3>
						<ul class="nav nav-tabs nav-tabs-top nav-justified mt-3">
							<li class="nav-item"><a href="#hscode-tab" class="nav-link active" data-toggle="tab">Hs Code</a></li>
							<li class="nav-item"><a href="#company-tab" class="nav-link" data-toggle="tab">Company</a></li>
							<li class="nav-item"><a href="#brand-tab" class="nav-link" data-toggle="tab">Brand</a></li>
							<li class="nav-item"><a href="#country-tab" class="nav-link" data-toggle="tab">Country</a></li>
							<li class="nav-item"><a href="#supplier-tab" class="nav-link" data-toggle="tab">Supplier</a></li>
							<li class="nav-item"><a href="#grade-tab" class="nav-link" data-toggle="tab">Grade</a></li>
						</ul>
						
						<div class="tab-content">
							<div class="tab-pane fade show active" id="hscode-tab">
								<form id="form_data_hscode">
								   <div class="alert alert-danger" id="validation_alert_hscode" style="display:none;">
									  <ul id="validation_content_hscode"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/hs_code') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>HS Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>HS Code Name :<span class="text-danger">*</span></label>
									  <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
								   </div>
								   <div class="form-group">
									  <label>HS Code Alias :</label>
									  <input type="text" name="alias" id="alias" class="form-control" placeholder="Enter alias">
								   </div>
								   <div class="form-group text-center mt-4">
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="2">
											Not Active
										 </label>
									  </div>
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="1" checked>
											Active
										 </label>
									  </div>
								   </div>
								</form>
								<div class="row">
									<div class="col-md-4 mx-auto mt-2">
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_hscode()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>

							<div class="tab-pane fade" id="company-tab">
								<form id="form_data_company">
								   <div class="alert alert-danger" id="validation_alert_company" style="display:none;">
									  <ul id="validation_content_company"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/company') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Company Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Company Name :<span class="text-danger">*</span></label>
									  <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
								   </div>
								   <div class="form-group text-center mt-4">
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="2">
											Not Active
										 </label>
									  </div>
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="1" checked>
											Active
										 </label>
									  </div>
								   </div>
								</form>
								<div class="row">
									<div class="col-md-4 mx-auto mt-2">
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_company()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>
							
							<div class="tab-pane fade" id="brand-tab">
								<form id="form_data_brand">
								   <div class="alert alert-danger" id="validation_alert_brand" style="display:none;">
									  <ul id="validation_content_brand"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/brand') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Brand Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Brand Name :<span class="text-danger">*</span></label>
									  <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
								   </div>
								   <div class="form-group">
									  <label>Brand Order :<span class="text-danger">*</span></label>
									  <input type="number" name="order" id="order" class="form-control" placeholder="0">
								   </div>
								   <div class="form-group">
									  <label>Brand Image :</label>
									  <input type="file" id="image" name="image" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg" onchange="previewImage(this, '#preview_image')">
									  <center class="mt-3">
										 <a href="{{ asset("website/empty.jpg") }}" id="preview_image" data-lightbox="Brand" data-title="Preview Image">
											<img src="{{ asset("website/empty.jpg") }}" class="img-fluid img-thumbnail w-100" style="max-width:200px;">
										 </a>
									  </center>
								   </div>
								   <div class="form-group text-center mt-4">
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="2">
											Not Active
										 </label>
									  </div>
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="1" checked>
											Active
										 </label>
									  </div>
								   </div>
								</form>
								<div class="row">
									<div class="col-md-4 mx-auto mt-2">
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_brand()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>
							
							<div class="tab-pane fade" id="country-tab">
								<form id="form_data_country">
								   <div class="alert alert-danger" id="validation_alert_country" style="display:none;">
									  <ul id="validation_content_country"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/country') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Country Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Country Name :<span class="text-danger">*</span></label>
									  <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
								   </div>
								   <div class="form-group">
									  <label>Country Phone Code :<span class="text-danger">*</span></label>
									  <input type="text" name="phone_code" id="phone_code" class="form-control" placeholder="Enter phone code">
								   </div>
								   <div class="form-group text-center mt-4">
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="2">
											Not Active
										 </label>
									  </div>
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="1" checked>
											Active
										 </label>
									  </div>
								   </div>
								</form>
								<div class="row">
									<div class="col-md-4 mx-auto mt-2">
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_country()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div> 
							
							<div class="tab-pane fade" id="supplier-tab">
								<form id="form_data_supplier">
								   <div class="alert alert-danger" id="validation_alert_supplier" style="display:none;">
									  <ul id="validation_content_supplier"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/supplier') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Supplier Code :</label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Auto Generate" readonly>
								   </div>
								   <div class="form-group">
									  <label>Supplier Name :<span class="text-danger">*</span></label>
									  <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
								   </div>
								   <div class="form-group">
									  <label>Supplier Country :<span class="text-danger">*</span></label>
									  <select name="country_id" id="country_id_ku" class="select2">
										 <option value="">-- Choose --</option>
										 @foreach($country as $c)
											<option value="{{ $c->id }}">{{ $c->name }}</option> 
										 @endforeach
									  </select>
								   </div>
								   <div class="form-group">
									  <label>Supplier Currency :<span class="text-danger">*</span></label>
									  <select name="currency_id[]" id="currency_id" class="select2" multiple>
										 @foreach($currency as $c)
											<option value="{{ $c->id }}">{{ $c->code }}</option> 
										 @endforeach
									  </select>
								   </div>
								   <div class="row">
									  <div class="col-md-6">
										 <div class="form-group">
											<label>Supplier Email :<span class="text-danger">*</span></label>
											<input type="text" name="email" id="email" class="form-control" placeholder="Enter email">
										 </div>
									  </div>
									  <div class="col-md-6">
										 <div class="form-group">
											<label>Supplier Phone :<span class="text-danger">*</span></label>
											<input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone">
										 </div>
									  </div>
								   </div>
								   <div class="form-group">
									  <label>Supplier Address :<span class="text-danger">*</span></label>
									  <textarea name="address" id="address" class="form-control" placeholder="Enter address" style="resize:none;"></textarea>
								   </div>
								   <div class="form-group">
									  <label>Supplier PIC :<span class="text-danger">*</span></label>
									  <input type="text" name="pic" id="pic" class="form-control" placeholder="Enter PIC">
								   </div>
								   <div class="row">
									  <div class="col-md-6">
										 <div class="form-group">
											<label>Supplier Term Of Payment :<span class="text-danger">*</span></label>
											<input type="number" name="term_of_payment" id="term_of_payment" class="form-control" placeholder="0">
										 </div>
									  </div>
									  <div class="col-md-6">
										 <div class="form-group">
											<label>Supplier Limit Credit :</label>
											<input type="text" name="limit_credit" id="limit_credit" class="form-control number" placeholder="Enter limit credit">
										 </div>
									  </div>
								   </div>
								   <div class="form-group text-center mt-4">
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="ppn" value="1" checked>
											PPn
										 </label>
									  </div>
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="ppn" value="2">
											Non-PPn
										 </label>
									  </div>
								   </div>
								   <div class="form-group text-center mt-4">
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="2">
											Not Active
										 </label>
									  </div>
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="1" checked>
											Active
										 </label>
									  </div>
								   </div>
								</form>
								<div class="row">
									<div class="col-md-4 mx-auto mt-2">
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_supplier()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>
							
							<div class="tab-pane fade" id="grade-tab">
								<form id="form_data_grade">
								   <div class="alert alert-danger" id="validation_alert_grade" style="display:none;">
									  <ul id="validation_content_grade"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/grade') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Grade Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Grade Name :<span class="text-danger">*</span></label>
									  <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
								   </div>
								   <div class="form-group text-center mt-4">
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="2">
											Not Active
										 </label>
									  </div>
									  <div class="form-check form-check-inline">
										 <label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" value="1" checked>
											Active
										 </label>
									  </div>
								   </div>
								</form>
								<div class="row">
									<div class="col-md-4 mx-auto mt-2">
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_grade()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
            <button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()" style="display:none;"><i class="icon-cross3"></i> Cancel</button>
            <button type="button" class="btn bg-warning" id="btn_update" onclick="update()" style="display:none;"><i class="icon-pencil7"></i> Save</button>
            <button type="button" class="btn bg-primary btn_create" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
         </div>
      </div>
   </div>
</div>

<script>
   $(function() {
	  ckEditor('description');
	   
      loadDataTable();
      $('#datatable_shading').DataTable({
		lengthMenu : [ [-1], ["All"] ]
	  });

      $('#datatable_shading tbody').on('click', '#delete_data_shading', function () {
         $('#datatable_shading').DataTable().row($(this).parents('tr')).remove().draw();
      });

      $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
         $('#datatable_shading').DataTable().columns.adjust();
      });

      select2ServerSide('#type_id', '{{ url("admin/select2/type") }}');
	  
	  $('#modal_form').on('shown.bs.modal', function () {
		$('.tab-pane#hscode-tab').removeClass('show active');
		$('.tab-pane#hscode-tab').addClass('show active');
	  });
	  
		$(".filecode").on('change', function () {

			if (typeof (FileReader) != "undefined") {

				var image_holder = $("#previewImg");
				image_holder.empty();

				var reader = new FileReader();
				reader.onload = function (e) {
					$("<img />", {
						"src": e.target.result,
						"class": "thumb-image",
						"width": "300px"
					}).appendTo(image_holder);
				};
				image_holder.show();
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		});
   });

   function resetFilter() {
      $('#filter_brand_id').val(null).change();
      $('#filter_country_id').val(null).change();
      $('#filter_stock').val(null);
      $('#filter_shading').val(null);
      $('#filter_status').val(null);
	  $('#filter_check').val(null);
      loadDataTable();
   }

   function formula() {
      $.ajax({
         url: '{{ url("admin/master_data/product/product_code/formula") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            type_id: $('#type_id').val(),
            carton_pcs: $('#carton_pcs').val()
         },
         success: function(response) {
            $('#carton_sqm').val(response.carton_sqm);
            $('#cubic_meter').val(response.cubic_meter);
         }
      });
   }

   function generateCode() {
      $.ajax({
         url: '{{ url("admin/master_data/product/product_code/generate_code") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            brand_id: $('#brand_id').val(),
            country_id: $('#country_id').val(),
            type_id: $('#type_id').val(),
            grade_id: $('#grade_id').val()
         },
         success: function(response) {
            formula();
            $('#code').val(response);
         },
         error: function() {
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }

   function addShading() {
      let shading_warehouse  = $('#shading_warehouse');
      let shading_stock_code = $('#shading_stock_code');
      let shading_code       = $('#shading_code');

      if(!shading_warehouse.val() || !shading_stock_code.val() || !shading_code.val()) {
         swalInit.fire({
            title: 'Please fill in all fields.',
            type: 'info'
         });
      } else {
         let arr_shading_warehouse = shading_warehouse.val().split(';');

         $('#datatable_shading').DataTable().row.add([
            arr_shading_warehouse[1],
            shading_stock_code.val(),
            shading_code.val(),
            `
               <button type="button" class="btn bg-danger btn-sm" id="delete_data_shading"><i class="icon-trash-alt"></i></button>
               <input type="hidden" name="shading_warehouse_code[]" value="` + arr_shading_warehouse[0] + `">
               <input type="hidden" name="shading_stock_code[]" value="` + shading_stock_code.val() + `">
               <input type="hidden" name="shading_code[]" value="` + shading_code.val() + `">
            `
         ]).draw().node();

         shading_warehouse.val(null).trigger('change');
         shading_stock_code.val(null);
         shading_code.val(null);
      }
   }

   function cancel() {
      reset();
      $('#modal_form').modal('hide');
      $('.btn_create').show();
      $('#btn_update').hide();
      $('#btn_cancel').hide();
   }

   function toShow() {
      $('.nav-tabs-highlight > li.nav-item > a.nav-link').removeClass('active');
      $('.nav-tabs-highlight > li.nav-item > a[href="#highlighted-justified-tab1"]').addClass('active');
      $('.tab-pane').removeClass('active');
      $('.tab-pane#highlighted-justified-tab1').addClass('show active');
      $('#modal_form').modal('show');
      $('#validation_alert').hide();
      $('#validation_content').html('');
      $('.btn_create').hide();
      $('#btn_update').show();
      $('#btn_cancel').show();
   }

   function reset() {
      $('#validation_alert').hide();
      $('#validation_content').html('');
      $('#form_data').trigger('reset');
      $('input[name="status"][value="1"]').prop('checked', true);
      $('.nav-tabs-highlight > li.nav-item > a.nav-link').removeClass('active');
      $('.nav-tabs-highlight > li.nav-item > a[href="#highlighted-justified-tab1"]').addClass('active');
      $('.tab-pane').removeClass('active');
      $('.tab-pane#highlighted-justified-tab1').addClass('show active');
      $('#type_id').val(null).change();
      $('#hs_code_id').val(null).change();
      $('#company_id').val(null).change();
      $('#brand_id').val(null).change();
      $('#country_id').val(null).change();
      $('#supplier_id').val(null).change();
      $('#grade_id').val(null).change();
      $('#datatable_shading').DataTable().clear().draw();
   }

   function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
   }

   function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/master_data/product/product_code/datatable") }}',
            type: 'GET',
            data: {
               brand_id: $('#filter_brand_id').val(),
               country_id: $('#filter_country_id').val(),
               stock: $('#filter_stock').val(),
			   check: $('#filter_check').val(),
               shading: $('#filter_shading').val(),
               status: $('#filter_status').val(),
			   length_param: $('#filter_length').val(),
			   width_param: $('#filter_width').val()
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
            { name: 'shading', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'code', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'name', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'stock', searchable: false, className: 'text-center align-middle' },
			{ name: 'check', searchable: false, className: 'text-center align-middle' },
            { name: 'status', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }

   function create() {
	   CKEDITOR.instances['description'].updateElement();
      $.ajax({
         url: '{{ url("admin/master_data/product/product_code/create") }}',
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

   function show(id) {
      toShow();
      $.ajax({
         url: '{{ url("admin/master_data/product/product_code/show") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
         beforeSend: function() {
            $('#datatable_shading').DataTable().clear().draw();
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            $('#type_id').html('<option value="' + response.type_id + '" selected>' + response.type_code + '</option>');
            $('#company_id').val(response.company_id).change();
            $('#hs_code_id').val(response.hs_code_id).change();
            $('#brand_id').val(response.brand_id).change();
            $('#country_id').val(response.country_id).change();
            $('#supplier_id').val(response.supplier_id).change();
            $('#grade_id').val(response.grade_id).change();
            $('#carton_pallet').val(response.carton_pallet);
            $('#carton_pcs').val(response.carton_pcs);
            $('#container_standart').val(response.container_standart);
            $('#container_stock').val(response.container_stock);
            $('#container_max_stock').val(response.container_max_stock);
			CKEDITOR.instances['description'].setData(response.description);
			$('#check').val(response.check);
			$("#previewImg").html('');
			$("#previewImg").append(response.design);
            $('input[name="status"][value="' + response.status + '"]').prop('checked', true);

            $.each(response.shading, function(i, val) {
               $('#datatable_shading').DataTable().row.add([
                  val.warehouse_code,
                  val.stock_code,
                  val.code,
                  `
                     <button type="button" class="btn bg-danger btn-sm" id="delete_data_shading"><i class="icon-trash-alt"></i></button>
                     <input type="hidden" name="shading_warehouse_code[]" value="` + val.warehouse_code + `">
                     <input type="hidden" name="shading_stock_code[]" value="` + val.stock_code + `">
                     <input type="hidden" name="shading_code[]" value="` + val.code + `">
                  `
               ]).draw().node();
            });

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
	  CKEDITOR.instances['description'].updateElement();
      $.ajax({
         url: '{{ url("admin/master_data/product/product_code/update") }}' + '/' + id,
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
                  url: '{{ url("admin/master_data/product/product_code/destroy") }}',
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
   
   function create_hscode() {
      $.ajax({
         url: '{{ url("admin/master_data/product/hs_code/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_hscode').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_hscode').hide();
            $('#validation_content_hscode').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_hscode')[0].reset();
				$('#hs_code_id').empty();
				$.each(response.hscode, function(i, val) {
				   $('#hs_code_id').append(`<option value="` + val.id + `">(` + val.code + `) ` + val.name + `</option>`);
				});
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
				$('#validation_alert_hscode').show();
				notif('warning', 'bg-warning', 'Validation');
               
				$.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_hscode').append(`
                        <li>` + val + `</li>
                     `);
                  });
				});
            } else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
	}
	
	function create_company() {
      $.ajax({
         url: '{{ url("admin/master_data/product/company/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_company').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_company').hide();
            $('#validation_content_company').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_company')[0].reset();
				$('#company_id').empty();
				$.each(response.company, function(i, val) {
				   $('#company_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_company').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_company').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            } else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
	}
	
	function create_brand() {
      $.ajax({
         url: '{{ url("admin/master_data/product/brand/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: new FormData($('#form_data_brand')[0]),
         contentType: false,
         processData: false,
         cache: true,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_brand').hide();
            $('#validation_content_brand').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_brand')[0].reset();
				$('#brand_id').empty();
				$.each(response.brand, function(i, val) {
				   $('#brand_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_brand').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_brand').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            } else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
	}
	
	function create_country() {
      $.ajax({
         url: '{{ url("admin/master_data/product/country/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_country').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_country').hide();
            $('#validation_content_country').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_country')[0].reset();
				$('#country_id,#country_id_ku').empty();
				$.each(response.country, function(i, val) {
				   $('#country_id,#country_id_ku').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});

               notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_country').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_country').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            } else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
	}
	
	function create_supplier() {
      $.ajax({
         url: '{{ url("admin/master_data/product/supplier/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_supplier').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_supplier').hide();
            $('#validation_content_supplier').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_supplier')[0].reset();
				$('#supplier_id').empty();
				$.each(response.supplier, function(i, val) {
				   $('#supplier_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				
               notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_supplier').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_supplier').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            } else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
	}
	
	function create_grade() {
      $.ajax({
         url: '{{ url("admin/master_data/product/grade/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_grade').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_grade').hide();
            $('#validation_content_grade').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_grade')[0].reset();
				$('#grade_id').empty();
				$.each(response.grade, function(i, val) {
				   $('#grade_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_grade').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_grade').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            } else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
	}
</script>