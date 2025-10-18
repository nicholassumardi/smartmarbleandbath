<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky no-print">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Product Type</span>
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
					<span class="breadcrumb-item active">Product Type</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of Product Types</h5>
				<div class="header-elements">
					<form action="#" class="mr-2">
						<select name="filter_image" id="filter_image" class="custom-select" onchange="loadDataTable()">
							<option value="">All Types Image</option>
							<option value="1">Empty Image</option>
							<option value="2">Empty Image & Has Shading</option>
							<option value="3">Empty Image & Empty Shading</option>
						</select>
					</form>
					<form action="#">
						<select name="filter_status" id="filter_status" class="custom-select" onchange="loadDataTable()">
							<option value="">All Status</option>
							<option value="1">Active</option>
							<option value="2">Not Active</option>
						</select>
					</form>
				</div>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
                        <th>Image</th>
                        <th>Code</th>
                        <th>Category</th>
                        <th>Thickness</th>
                        <th>Surface</th>
                        <th>Color</th>
                        <th>Pattern</th>
                        <th>Status</th>
						<th>Store</th>
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
               <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
                  <li class="nav-item">
                     <a href="#highlighted-justified-tab1" class="nav-link active" data-toggle="tab">Data</a>
                  </li>
                  <li class="nav-item">
                     <a href="#highlighted-justified-tab2" class="nav-link" data-toggle="tab">Specification</a>
                  </li>
                  <li class="nav-item">
                     <a href="#highlighted-justified-tab3" class="nav-link" data-toggle="tab">Stock</a>
                  </li>
                  <li class="nav-item">
                     <a href="#highlighted-justified-tab4" class="nav-link" data-toggle="tab">Image</a>
                  </li>
               </ul>
               <div class="tab-content">
                  <div class="tab-pane fade show active" id="highlighted-justified-tab1">
                     <p class="mt-4">
                        <div class="form-group">
                           <label>Category :<span class="text-danger">*</span></label>
                           <select name="category_id" id="category_id" class="select2" onchange="selectionField()">
                              <option value="">-- Choose --</option>
                              @foreach($category as $c)
                                 @php $sub_1 = App\Models\Category::where('parent_id', $c->id)->where('status', 1)->oldest('name')->get(); @endphp
                                 @if($sub_1->count() > 0)
                                    @foreach($sub_1 as $s1)
                                       @php $sub_2 = App\Models\Category::where('parent_id', $s1->id)->where('status', 1)->oldest('name')->get(); @endphp
                                       @if($sub_2->count() > 0)
                                          @foreach($sub_2 as $s2)
                                             <option value="{{ $s2->id }}">{{ $c->name }} &rarr; {{ $s1->name }} &rarr; {{ $s2->name }}</option>
                                          @endforeach
                                       @else
                                          <option value="{{ $s1->id }}">{{ $c->name }} &rarr; {{ $s1->name }}</option>
                                       @endif
                                    @endforeach
                                 @else
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                 @endif
                              @endforeach
                           </select>
                        </div>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Code :<span class="text-danger">*</span></label>
                                 <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Faces :</label>
                                 <input type="text" name="faces" id="faces" class="form-control" placeholder="Enter faces">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Division :<span class="text-danger">*</span></label>
                                 <select name="division_id" id="division_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($division as $d)
                                       <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Surface :</label>
                                 <select name="surface_id" id="surface_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($surface as $s)
                                       <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Color :<span class="text-danger">*</span></label>
                                 <select name="color_id" id="color_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($color as $c)
                                       <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Pattern :<span class="text-danger">*</span></label>
                                 <select name="pattern_id" id="pattern_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($pattern as $p)
                                       <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
						   <div class="col-md-4">
								<div class="form-group">
									<label>Show in Store :<span class="text-danger">*</span></label>
									<select name="show_in_store" id="show_in_store" class="custom-select">
										<option value="2">No</option>
										<option value="1">Yes</option>
									</select>
									<span class="badge d-block badge-danger form-text">Please, make sure SMB Item Code is not empty.</span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Best Seller :<span class="text-danger">*</span></label>
									<select name="best_seller" id="best_seller" class="custom-select">
										<option value="1">Yes</option>
										<option value="2">No</option>
									</select>
									<span class="badge d-block badge-danger form-text">Please, make sure SMB Item Code is not empty.</span>
								</div>
							</div>
                        </div>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="highlighted-justified-tab2">
                     <p class="mt-4">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Material :<span class="text-danger">*</span></label>
                                 <select name="material" id="material" class="custom-select">
                                    <option value="">-- Choose --</option>
                                    <option value="1">High</option>
                                    <option value="2">Medium</option>
                                    <option value="3">Low</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Loading Limit :<span class="text-danger">*</span></label>
                                 <select name="loading_limit_id" id="loading_limit_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($loading_limit as $ll)
                                       <option value="{{ $ll->id }}">{{ $ll->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Length :</label>
                                 <div class="position-relative">
                                    <input type="number" name="length" id="length" class="form-control" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">Cm</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Width :</label>
                                 <div class="position-relative">
                                    <input type="number" name="width" id="width" class="form-control" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">Cm</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Height :</label>
                                 <div class="position-relative">
                                    <input type="number" name="height" id="height" class="form-control" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">Cm</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Weight :<span class="text-danger">*</span></label>
                                 <div class="position-relative">
                                    <input type="number" name="weight" id="weight" class="form-control" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">Kg</div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Thickness :</label>
                                 <div class="position-relative">
                                    <input type="number" name="thickness" id="thickness" class="form-control" placeholder="0">
                                    <div class="form-control-feedback font-weight-bold">mm</div>
                                 </div>
                              </div>
                           </div>
						   <div class="col-md-12">
                              <div class="form-group form-group-feedback form-group-feedback-right">
                                 <label>Other Dimension :</label>
                                 <div class="position-relative">
                                    <textarea name="other_dimension" id="other_dimension" class="form-control" placeholder="Such as : Diameter or etc." rows="4"></textarea>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="highlighted-justified-tab3">
                     <p class="mt-4">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Buying Unit :<span class="text-danger">*</span></label>
                                 <select name="buy_unit_id" id="buy_unit_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($unit as $u)
                                       <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Stock Unit :<span class="text-danger">*</span></label>
                                 <select name="stock_unit_id" id="stock_unit_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($unit as $u)
                                       <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Selling Unit :<span class="text-danger">*</span></label>
                                 <select name="selling_unit_id" id="selling_unit_id" class="select2">
                                    <option value="">-- Choose --</option>
                                    @foreach($unit as $u)
                                       <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Need To Stock :<span class="text-danger">*</span></label>
                                 <select name="stockable" id="stockable" class="custom-select">
                                    <option value="">-- Choose --</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Small Stock :<span class="text-danger">*</span></label>
                                 <input type="number" name="small_stock" id="small_stock" class="form-control" placeholder="0">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Min Stock :<span class="text-danger">*</span></label>
                                 <input type="number" name="min_stock" id="min_stock" class="form-control" placeholder="0">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Max Stock :<span class="text-danger">*</span></label>
                                 <input type="number" name="max_stock" id="max_stock" class="form-control" placeholder="0">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Conversion :<span class="text-danger">*</span></label>
                                 <input type="number" name="conversion" id="conversion" class="form-control" placeholder="0" value="1">
                              </div>
                           </div>
                        </div>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="highlighted-justified-tab4">
                     <p class="mt-4">
                        <div class="form-group">
                           <div class="input-group">
                              <div class="custom-file">
                                 <input type="file" id="image" name="image" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg" onchange="previewImage(this, '#preview_image')">
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="text-center">
                              <a href="{{ asset('website/empty.jpg') }}" id="preview_image" data-lightbox="Image" data-title="Preview Image">
                                 <img src="{{ asset('website/empty.jpg') }}" class="img-fluid img-thumbnail w-100" style="max-width:200px;">
                              </a>
                              <p class="text-danger font-italic mt-3">
                                 Maximum file size is <b>100KB</b> & the only files supported are <b>jpeg, jpg, png</b>
                              </p>
                           </div>
                        </div>
                     </p>
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
							<li class="nav-item"><a href="#category-tab" class="nav-link active" data-toggle="tab">Category</a></li>
							<li class="nav-item"><a href="#division-tab" class="nav-link" data-toggle="tab">Division</a></li>
							<li class="nav-item"><a href="#surface-tab" class="nav-link" data-toggle="tab">Surface</a></li>
							<li class="nav-item"><a href="#color-tab" class="nav-link" data-toggle="tab">Color</a></li>
							<li class="nav-item"><a href="#pattern-tab" class="nav-link" data-toggle="tab">Pattern</a></li>
							<li class="nav-item"><a href="#loadlimit-tab" class="nav-link" data-toggle="tab">L.Limit</a></li>
							<li class="nav-item"><a href="#unit-tab" class="nav-link" data-toggle="tab">Unit</a></li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade show active" id="category-tab">
								<form id="form_data_category">
								   <div class="alert alert-danger" id="validation_alert_category" style="display:none;">
									  <ul id="validation_content_category"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/category') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Category's Name :<span class="text-danger">*</span></label>
									  <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
								   </div>
								   <div class="form-group">
									  <label>Category's Parent :<span class="text-danger">*</span></label>
									  <select name="parent_id" id="parent_id" class="select2">
										 <option value="0">Is Parent</option>
										 @foreach($category as $c)
											<option value="{{ $c->id }}">{{ $c->name }}</option>
										 @endforeach
									  </select>
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
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_category()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>

							<div class="tab-pane fade" id="division-tab">
								<form id="form_data_division">
								   <div class="alert alert-danger" id="validation_alert_division" style="display:none;">
									  <ul id="validation_content_division"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/division') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Division's Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Division's Name :<span class="text-danger">*</span></label>
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
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_division()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>

							<div class="tab-pane fade" id="surface-tab">
								<form id="form_data_surface">
								   <div class="alert alert-danger" id="validation_alert_surface" style="display:none;">
									  <ul id="validation_content_surface"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/surface') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Surface's Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Surface's Name :<span class="text-danger">*</span></label>
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
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_surface()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>
							
							<div class="tab-pane fade" id="color-tab">
								<form id="form_data_color">
								   <div class="alert alert-danger" id="validation_alert_color" style="display:none;">
									  <ul id="validation_content_color"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/color') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Color's Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Color's Name :<span class="text-danger">*</span></label>
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
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_color()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="pattern-tab">
								<form id="form_data_pattern">
								   <div class="alert alert-danger" id="validation_alert_pattern" style="display:none;">
									  <ul id="validation_content_pattern"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/pattern') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Pattern's Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Pattern's Name :<span class="text-danger">*</span></label>
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
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_pattern()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>
							
							<div class="tab-pane fade" id="loadlimit-tab">
								<form id="form_data_loading">
								   <div class="alert alert-danger" id="validation_alert_loading" style="display:none;">
									  <ul id="validation_content_loading"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/loading_limit') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Loading's Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Loading's Name :<span class="text-danger">*</span></label>
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
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_loading()"><i class="icon-plus3"></i> Save</button>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="unit-tab">
								<form id="form_data_unit">
								   <div class="alert alert-danger" id="validation_alert_unit" style="display:none;">
									  <ul id="validation_content_unit"></ul>
								   </div>
								   <div class="text-center"><a href="{{ url('admin/master_data/product/unit') }}" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-pencil5"></i></b> Manage</a></div>
								   <div class="form-group">
									  <label>Unit's Code :<span class="text-danger">*</span></label>
									  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
								   </div>
								   <div class="form-group">
									  <label>Unit's Name :<span class="text-danger">*</span></label>
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
										<button type="button" class="btn bg-primary btn-block" id="btn_create" onclick="create_unit()"><i class="icon-plus3"></i> Save</button>
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
      loadDataTable();
      selectionField();
	  $('#modal_form').on('shown.bs.modal', function () {
		$('.tab-pane#category-tab').removeClass('show active');
		$('.tab-pane#category-tab').addClass('show active');
	  });
	  
		$("#datatable_serverside").on( "click", 'tbody tr .check-show', function() {
			var id = $(this).data('product');
			var value = '2';
			
			if($(this).is(':checked')){
				value = '1';
			}
			
			$.ajax({
				 url: '{{ url("admin/master_data/product/product_type/show_in_store") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : id, value : value },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('#datatable_serverside');
				 },
				 success: function(response) {
					loadingClose('#datatable_serverside');
					if(response.status == 200) {
						loadDataTable();
						notif('success', 'bg-success', response.message);
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
		});
   });

   function selectionField() {
      var category = $('#category_id option:selected').text();
      if(category.toLowerCase().indexOf('sanitary') >= 0) {
         $('#thickness').val(null);
         $('#thickness').attr('disabled', true);
         $('#height').attr('disabled', false);
      } else if(category.toLowerCase().indexOf('tile') >= 0) {
         $('#height').val(null);
         $('#height').attr('disabled', true);
         $('#thickness').attr('disabled', false);
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
	  $('.nav-justified > li.nav-item > a.nav-link').removeClass('active');
      $('.nav-tabs-highlight > li.nav-item > a[href="#category-tab"]').addClass('active');
      $('.tab-pane').removeClass('show active');
      $('.tab-pane#highlighted-justified-tab1').addClass('show active');
      $('.tab-pane#category-tab').addClass('show active');
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
      $('#preview_image').attr('href', '{{ asset("website/empty.jpg") }}');
      $('#preview_image img').attr('src', '{{ asset("website/empty.jpg") }}');
      $('.nav-tabs-highlight > li.nav-item > a.nav-link').removeClass('active');
      $('.nav-tabs-highlight > li.nav-item > a[href="#highlighted-justified-tab1"]').addClass('active');
      $('.tab-pane').removeClass('show active');
      $('.tab-pane#highlighted-justified-tab1').addClass('show active');
      $('#category_id').val(null).change();
      $('#division_id').val(null).change();
      $('#surface_id').val(null).change();
      $('#color_id').val(null).change();
      $('#pattern_id').val(null).change();
      $('#loading_limit_id').val(null).change();
      $('#buy_unit_id').val(null).change();
      $('#stock_unit_id').val(null).change();
      $('#selling_unit_id').val(null).change();
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
            url: '{{ url("admin/master_data/product/product_type/datatable") }}',
            type: 'GET',
            data: {
               status: $('#filter_status').val(),
			   image: $('#filter_image').val()
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
            { name: 'image', searchable: false, className: 'text-center align-middle' },
            { name: 'code', className: 'text-center align-middle' },
            { name: 'category_id', className: 'text-center align-middle' },
            { name: 'thickness', searchable: false, className: 'text-center align-middle' },
            { name: 'surface_id', className: 'text-center align-middle' },
            { name: 'color_id', className: 'text-center align-middle' },
            { name: 'pattern_id', className: 'text-center align-middle' },
            { name: 'status', searchable: false, className: 'text-center align-middle' },
			{ name: 'store', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }

   function create() {
      $.ajax({
         url: '{{ url("admin/master_data/product/product_type/create") }}',
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
         url: '{{ url("admin/master_data/product/product_type/show") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
         beforeSend: function() {
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            $('#category_id').val(response.category_id).change();
            $('#division_id').val(response.division_id).change();
            $('#surface_id').val(response.surface_id).change();
            $('#color_id').val(response.color_id).change();
            $('#pattern_id').val(response.pattern_id).change();
            $('#loading_limit_id').val(response.loading_limit_id).change();
            $('#buy_unit_id').val(response.buy_unit_id).change();
            $('#stock_unit_id').val(response.stock_unit_id).change();
            $('#selling_unit_id').val(response.selling_unit_id).change();
            $('#code').val(response.code);
            $('#faces').val(response.faces);
            $('#material').val(response.material);
            $('#length').val(response.lengths);
            $('#width').val(response.width);
            $('#height').val(response.height);
            $('#weight').val(response.weight);
            $('#thickness').val(response.thickness);
            $('#small_stock').val(response.small_stock);
            $('#min_stock').val(response.min_stock);
            $('#max_stock').val(response.max_stock);
            $('#conversion').val(response.conversion);
            $('#stockable').val(response.stockable);
			$('#show_in_store').val(response.show_in_store);
			$('#best_seller').val(response.best_seller);
			$('#other_dimension').val(response.other_dimension);
            $('#preview_image').attr('href', response.image);
            $('#preview_image img').attr('src', response.image);
            $('input[name="status"][value="' + response.status + '"]').prop('checked', true);
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
         url: '{{ url("admin/master_data/product/product_type/update") }}' + '/' + id,
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
                  url: '{{ url("admin/master_data/product/product_type/destroy") }}',
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
   
   function create_category() {
      $.ajax({
         url: '{{ url("admin/master_data/product/category/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_category').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_category').hide();
            $('#validation_content_category').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_category')[0].reset();
				$('#category_id').empty();
				$.each(response.category, function(i, val) {
				   $('#category_id').append(`<option value="` + val.category_id + `">` + val.category_text + `</option>`);
				});
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_category').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_category').append(`
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
   
   function create_division() {
      $.ajax({
         url: '{{ url("admin/master_data/product/division/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_division').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_division').hide();
            $('#validation_content_division').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_division')[0].reset();
				$('#division_id').empty();
				$.each(response.division, function(i, val) {
				   $('#division_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
               notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_division').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_division').append(`
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
   
   function create_surface() {
      $.ajax({
         url: '{{ url("admin/master_data/product/surface/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_surface').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_surface').hide();
            $('#validation_content_surface').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_surface')[0].reset();
				$('#surface_id').empty();
				$.each(response.surface, function(i, val) {
				   $('#surface_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_surface').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_surface').append(`
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
	
	function create_color() {
      $.ajax({
         url: '{{ url("admin/master_data/product/color/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_color').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_color').hide();
            $('#validation_content_color').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_color')[0].reset();
				$('#color_id').empty();
				$.each(response.color, function(i, val) {
				   $('#color_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_color').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_color').append(`
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
	
	function create_pattern() {
      $.ajax({
         url: '{{ url("admin/master_data/product/pattern/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_pattern').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_pattern').hide();
            $('#validation_content_pattern').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_pattern')[0].reset();
				$('#pattern_id').empty();
				$.each(response.pattern, function(i, val) {
				   $('#pattern_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
				$('#validation_alert_pattern').show();
				notif('warning', 'bg-warning', 'Validation');  
				$.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_pattern').append(`
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
	
	function create_loading() {
      $.ajax({
         url: '{{ url("admin/master_data/product/loading_limit/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_loading').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_loading').hide();
            $('#validation_content_loading').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_loading')[0].reset();
				$('#loading_limit_id').empty();
				$.each(response.loading, function(i, val) {
				   $('#loading_limit_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
				$('#validation_alert_loading').show();
				notif('warning', 'bg-warning', 'Validation');
				
				$.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_loading').append(`
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
	
	function create_unit() {
      $.ajax({
         url: '{{ url("admin/master_data/product/unit/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_unit').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_unit').hide();
            $('#validation_content_unit').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_unit')[0].reset();
				$('#buy_unit_id').empty();
				$('#selling_unit_id').empty();
				$('#stock_unit_id').empty();
				$.each(response.unit, function(i, val) {
				   $('#buy_unit_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				   $('#selling_unit_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				   $('#stock_unit_id').append(`<option value="` + val.id + `">` + val.name + `</option>`);
				});
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_unit').show();
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_unit').append(`
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