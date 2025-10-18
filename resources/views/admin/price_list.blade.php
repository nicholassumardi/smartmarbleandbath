<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Price List</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<span class="breadcrumb-item active">Product Price List</span>
				</div>
			</div>
		</div>
	</div>
	@if(session('bo_branch') == '1')
	<div class="content">
		<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
			<li class="nav-item"><a href="#sell_price" class="nav-link active" data-toggle="tab">Price List (Sell Price)</a></li>
			<li class="nav-item"><a href="#buy_price" class="nav-link" data-toggle="tab">Price List (Buy Price)</a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane fade show active" id="sell_price">
				<div class="card">
		    	<div class="card-header header-elements-inline">
				    <h5 class="card-title">List of All Warehouse Stock</h5>
			    	    <div class="header-elements">
    						<select name="branch" id="branch" class="custom-select" onchange="loadDataTable()">
							@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							 @endforeach
    						</select>
        				</div>
        			</div>
					<div class="card-body">
						<div class="table-responsive">
						   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark sidebar-sticky">
								<tr class="text-center">
									<th>#</th>
									<th>Product</th>
									<th>Customer</th>
									<th>Date</th>
									<th>Pricelist</th>
									<th>Discount 1</th>
									<th>Discount 2</th>
									<th>Last Price</th>
									<th>Qty</th>
								</tr>
							  </thead>
						   </table>
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane fade" id="buy_price">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
						   <table id="datatable_serverside_buy" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark sidebar-sticky">
								<tr class="text-center">
									<th>#</th>
									<th>Product</th>
									<th>Supplier</th>
									<th>Customer</th>
									<th>Date</th>
									<th>Is PPn</th>
									<th>Buy Price(After TAX)</th>
									<th>Qty</th>
								</tr>
							  </thead>
						   </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@else
	<div class="content">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark sidebar-sticky">
						<tr class="text-center">
							<th>#</th>
							<th>Product</th>
							<th>Customer</th>
							<th>Date</th>
							<th>Pricelist</th>
							<th>Discount 1</th>
							<th>Discount 2</th>
							<th>Last Price</th>
							<th>Qty</th>
						</tr>
					  </thead>
				   </table>
				</div>
			</div>
		</div>
	</div>
	@endif
	
	
	<script>
		$(function() {
			loadDataTable();
			@if(session('bo_branch') == '1')
			loadDataTableBuyPrice();
			@endif
		});
		
		function loadDataTable() {
		  window.table = $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
				ajax: {
				url: '{{ url("admin/price_list/datatable") }}',
				type: 'GET',
				data: {
					type : $('#filter_type').val(),
					@if(session('bo_branch') == '1')
    				branch : $('#branch').val()
    				@endif
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
				{ name: 'id', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'product', orderable: false, className: 'text-center align-middle' },
				{ name: 'customer', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'date', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'price', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'disc1', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'disc2', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'lastprice', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'qty', orderable: false, searchable: false, className: 'text-center align-middle' },
			 ]
		  }); 
		}

		@if(session('bo_branch') == '1')
		function loadDataTableBuyPrice() {
		  window.table = $('#datatable_serverside_buy').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
				ajax: {
				url: '{{ url("admin/price_list/datatable_buy_price") }}',
				type: 'GET',
				data: {
					type : $('#filter_type').val()
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
				{ name: 'id', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'product', orderable: false, className: 'text-center align-middle' },
				{ name: 'customer', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'date', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'ppn', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'price', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'qty', orderable: false, searchable: false, className: 'text-center align-middle' },
			 ]
		  }); 
		}
		@endif
	</script>