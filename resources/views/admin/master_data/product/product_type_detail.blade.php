<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Detail Product Type</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/master_data/product/product_type') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Product</a>
					<a href="{{ url('admin/master_data/product/product_type') }}" class="breadcrumb-item">Product Type</a>
					<span class="breadcrumb-item active">Detail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
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
                     <table cellpadding="10" cellspacing="0" width="100%">
                        <tbody>
                           <tr>
                              <th width="20%" class="align-middle">Category</th>
                              <td class="align-middle">: {{ $type->category->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Code</th>
                              <td class="align-middle">: {{ $type->code }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Faces</th>
                              <td class="align-middle">: {{ $type->faces }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Division</th>
                              <td class="align-middle">: {{ $type->division->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Surface</th>
                              <td class="align-middle">: {{ $type->surface->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Color</th>
                              <td class="align-middle">: {{ $type->color->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Pattern</th>
                              <td class="align-middle">: {{ $type->pattern->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Status</th>
                              <td class="align-middle">: {!! $type->status() !!}</td>
                           </tr>
                        </tbody>
                     </table>
                  </p>
               </div>
               <div class="tab-pane fade" id="highlighted-justified-tab2">
                  <p class="mt-4">
                     <table cellpadding="10" cellspacing="0" width="100%">
                        <tbody>
                           <tr>
                              <th width="20%" class="align-middle">Material</th>
                              <td class="align-middle">: {{ $type->material() }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Loading Limit</th>
                              <td class="align-middle">: {{ $type->loadingLimit->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Length</th>
                              <td class="align-middle">: {{ $type->length }} Cm</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Width</th>
                              <td class="align-middle">: {{ $type->width }} Cm</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Height</th>
                              <td class="align-middle">: {{ $type->height }} Cm</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Weight</th>
                              <td class="align-middle">: {{ $type->weight }} Kg</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Thickness</th>
                              <td class="align-middle">: {{ $type->thickness }} mm</td>
                           </tr>
                        </tbody>
                     </table>
                  </p>
               </div>
               <div class="tab-pane fade" id="highlighted-justified-tab3">
                  <p class="mt-4">
                     <table cellpadding="10" cellspacing="0" width="100%">
                        <tbody>
                           <tr>
                              <th width="20%" class="align-middle">Buy Unit</th>
                              <td class="align-middle">: {{ $type->buyUnit->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Stock Unit</th>
                              <td class="align-middle">: {{ $type->stockUnit->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Selling Unit</th>
                              <td class="align-middle">: {{ $type->sellingUnit->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Need To Stock</th>
                              <td class="align-middle">: {{ $type->stockable ? 'Yes' : 'No' }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Small Stock</th>
                              <td class="align-middle">: {{ $type->small_stock }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Min Stock</th>
                              <td class="align-middle">: {{ $type->min_stock }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Max Stock</th>
                              <td class="align-middle">: {{ $type->max_stock }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Conversion</th>
                              <td class="align-middle">: {{ $type->conversion }}</td>
                           </tr>
                        </tbody>
                     </table>
                  </p>
               </div>
               <div class="tab-pane fade" id="highlighted-justified-tab4">
                  <p class="mt-4">
                     <div class="form-group">
                        <div class="text-center">
                           <a href="{{ $type->image() }}" id="detail_image" data-lightbox="Image" data-title="Preview Image">
                              <img src="{{ $type->image() }}" class="img-fluid img-thumbnail w-100" style="max-width:350px;">
                           </a>
                        </div>
                     </div>
                  </p>
               </div>
            </div>
			</div>
		</div>
	</div>