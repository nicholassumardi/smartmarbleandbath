<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Detail Product Code</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/master_data/product/product_code') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
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
					<a href="{{ url('admin/master_data/product/product_code') }}" class="breadcrumb-item">Product Code</a>
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
                  <a href="#highlighted-justified-tab2" class="nav-link" data-toggle="tab">Stock</a>
               </li>
               <li class="nav-item">
                  <a href="#highlighted-justified-tab3" class="nav-link" data-toggle="tab">Shading</a>
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
                     <table cellpadding="10" cellspacing="0" width="100%">
                        <tbody>
                           <tr>
                              <th width="20%" class="align-middle">Name</th>
                              <td class="align-middle">: {{ $product->name() }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Code</th>
                              <td class="align-middle">: {{ $product->code() }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Type</th>
                              <td class="align-middle">: {{ $product->type->code }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Hs Code</th>
                              <td class="align-middle">: {{ $product->hsCode->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Company</th>
                              <td class="align-middle">: {{ $product->company->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Brand</th>
                              <td class="align-middle">: {{ $product->brand->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Country</th>
                              <td class="align-middle">: {{ $product->country->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Supplier</th>
                              <td class="align-middle">: {{ $product->supplier->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Grade</th>
                              <td class="align-middle">: {{ $product->grade->name }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Stock</th>
                              <td class="align-middle">: {{ $product->availability()->stock }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Status</th>
                              <td class="align-middle">: {!! $product->status() !!}</td>
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
                              <th width="20%" class="align-middle">Pcs</th>
                              <td class="align-middle">: {{ $product->carton_pcs }} <sub>/ carton</sub></td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Carton</th>
                              <td class="align-middle">: {{ $product->carton_pallet }} <sub>/ pallet</sub></td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Stock Unit</th>
                              <td class="align-middle">: {{ $product->container_stock }} <sub>/ container</sub></td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Standart Container</th>
                              <td class="align-middle">: {{ $product->containerStandart() }}</td>
                           </tr>
                           <tr>
                              <th width="20%" class="align-middle">Max Stock Unit</th>
                              <td class="align-middle">: {{ $product->container_max_stock }} <sub>/ container</sub></td>
                           </tr>
                        </tbody>
                     </table>
                  </p>
               </div>
               <div class="tab-pane fade" id="highlighted-justified-tab3">
                  <p class="mt-4">
                     <div class="table-responsive">
                        <table id="datatable_shading" class="table table-bordered table-striped w-100">
                           <thead class="bg-info">
                              <tr class="text-center">
                                 <th>Warehouse</th>
                                 <th>Ventura</th>
                                 <th>Code</th>
                                 <th>Qty</th>
                              </tr>
                           </thead>
                           <tbody class="text-center">
                              @foreach($product->productShading as $ps)
                                 <tr class="text-center">
                                    <td class="align-middle">{{ $ps->warehouse_code }}</td>
                                    <td class="align-middle">{{ $ps->stock_code }}</td>
                                    <td class="align-middle">{{ $ps->code }}</td>
                                    <td class="align-middle">{{ $ps->qty }}</td>
                                 </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                  </p>
               </div>
               <div class="tab-pane fade" id="highlighted-justified-tab4">
                  <p class="mt-4">{!! $product->description !!}</p>
               </div>
			   <div class="tab-pane fade" id="highlighted-justified-tab5">
				 <p class="mt-4 text-center">
					<img src="{{ $product->attachment() }}" alt="{{ $product->name() }}" width="300px">
				 </p>
			   </div>
            </div>
			</div>
		</div>
	</div>

<script>
   $(function() {
      $('#datatable_shading').DataTable();

      $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
         $('#datatable_shading').DataTable().columns.adjust();
      });
   });
</script>