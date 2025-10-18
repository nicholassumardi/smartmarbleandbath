<style>
	td:first-child,
	.fixed {
		position: sticky;
		left: 0px;
		background-color: #c5c3c3;
	}

	.table-bordered>thead>tr>th {
		border: 1px solid white !important;
	}

	.table-bordered>tbody {
		background-color: #c5c3c3;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Product Inventory (In Rupiah)</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Inventory</a>
					<span class="breadcrumb-item active">Product Inventory</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h2 class="card-title font-weight-bold">Filter</h2>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-1">
						<div class="form-group">
							<label>Branch :</label>
							<select name="filter_branch" id="filter_branch" class="form-control">
								@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<label>Mode :</label>
							<select name="filter_mode" id="filter_mode" class="form-control">
								<option value="1">All</option>
								<option value="2">Stock Final On Hand(On Stock)</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Brand :</label>
							<select name="brand" id="brand" class="form-control select2">
								<option value="">-- Pilih satu --</option>
								@foreach($brands as $brand)
								<option value="{{ $brand->name }}">{{ $brand->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Size :</label>
							<select name="size" id="size" class="form-control select2">
								<option value="">All</option>
								@foreach ($sizes as $size)
								<option value="{{$size->length}};{{$size->width}}">{{$size->size()}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Warehouse :</label>
							<select name="warehouse_id" id="warehouse_id" class="form-control select2">
								<option value="">All</option>
								@foreach ($warehouses as $warehouse)
								<option value="{{$warehouse->id}}">{{$warehouse->code.' - '. $warehouse->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-sm-4 col-md-3">
						<div class="form-group">
							<label>Date :</label>
							<div class="input-group">
								<input type="date" name="filter_start_date" id="filter_start_date" class="form-control">
							</div>
						</div>
					</div>
					<div class="col-sm-2 col-md-3">
						<div class="form-group">
							<label>To :</label>
							<div class="input-group">
								<input type="date" name="filter_finish_date" id="filter_finish_date"
									class="form-control">
							</div>
						</div>
					</div>
					{{-- <div class="col-sm-6 col-md-6">
						<div class="row">
							<div class="col-sm-4 col-md-3">
								<div class="form-group">
									<label>Date :</label>
									<div class="input-group">
										<input type="date" name="filter_start_date" id="filter_start_date"
											class="form-control">
									</div>
								</div>
							</div>
							<div class="col-sm-2 col-md-3">
								<div class="form-group">
									<label>To :</label>
									<div class="input-group">
										<input type="date" name="filter_finish_date" id="filter_finish_date"
											class="form-control">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 col-md-8">
								<div class="form-group">
									<label>&nbsp;<br></label>
									<div class="input-group">
										<button type="button" onclick="show()" class="btn bg-purple mr-sm-1 mr-md-2"><i
												class="icon-filter4"></i> Search</button>
										<button type="button" onclick="resetFilter()"
											class="btn bg-danger mr-sm-1 mr-md-2"><i class="icon-sync"></i></button>
										<div class="header-elements">
											<div class="d-flex justify-content-center">
												<button
													class="btn bg-pink-400 btn-labeled mr-2 btn-labeled-left dropdown-toggle"
													data-toggle="dropdown">
													<b><i class="icon-printer2"></i></b> Print</button>
												<ul class="dropdown-menu">
													<li><a class="dropdown-item" onclick="print()">
															Print All (No IMG)</a>
													</li>
													<li><a class="dropdown-item" onclick="printByWarehouse()">
															Print By
															Location And Sizes</a></li>
													<li><a class="dropdown-item" onclick="printCardMode()">
															Print Card Mode (No Price)</a> </li>
													<li></li>
												</ul>
											</div>
										</div>
										<button type="button" onclick="exportFile()" title="Export To Excel"
											class="btn bg-green-400"><i class="icon-file-excel"></i> Export As
											Excel</button>
									</div>
								</div>
							</div>
						</div>
					</div> --}}
				</div>
				<div class="row justify-content-center">
					<div class="col-12">
						<div class="input-group d-flex justify-content-center">
							<button type="button" onclick="show()" class="btn bg-purple mr-sm-1 mr-md-2"><i
									class="icon-filter4"></i> Search</button>
							<button type="button" onclick="resetFilter()" class="btn bg-danger mr-sm-1 mr-md-2"><i
									class="icon-sync"></i></button>
							<div class="header-elements">
								<div>
									<button class="btn bg-pink-400 btn-labeled mr-2 btn-labeled-left dropdown-toggle"
										data-toggle="dropdown">
										<b><i class="icon-printer2"></i></b> Print</button>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" onclick="print()">
												Print All (No IMG)</a>
										</li>
										<li><a class="dropdown-item" onclick="printByWarehouse()">
												Print By
												Location And Sizes</a></li>
										<li><a class="dropdown-item" onclick="printCardMode()">
												Print Card Mode (No Price)</a> </li>
										<li></li>
									</ul>
								</div>
							</div>
							<button type="button" onclick="exportFile()" title="Export To Excel"
								class="btn bg-green-400"><i class="icon-file-excel"></i> Export As
								Excel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="mb-3">
			<h6 class="mb-0 font-weight-semibold text-center text-uppercase">
				<span id="string_filter_periode"></span>
			</h6>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped w-100">
						<thead class="bg-dark sidebar-sticky">
							<tr class="text-center">
								<th rowspan="2" class="fixed" style="background-color:#324148;">Product</th>
								<th colspan="3">Previous</th>
								<th colspan="3">Receive</th>
								<th colspan="3">Total</th>
								<th colspan="3">Sold</th>
								<th colspan="3">Adjusment</th>
								<th colspan="3">Balance</th>
							</tr>
							<tr class="text-center">
								<th>Qty</th>
								<th>Price</th>
								<th>IDR</th>
								<th>Qty</th>
								<th>Price</th>
								<th>IDR</th>
								<th>Qty</th>
								<th>Price</th>
								<th>IDR</th>
								<th>Qty</th>
								<th>Price</th>
								<th>IDR</th>
								<th>Qty</th>
								<th>Price</th>
								<th>IDR</th>
								<th>Qty</th>
								<th>Price</th>
								<th>IDR</th>
							</tr>
						</thead>
						<tbody id="body-result">
							<tr>
								<td colspan="19" class="text-center bg-warning">PLEASE CHOOSE FILTER TO SHOW STOCK CARD
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(function() {
		$('.sidebar-main-toggle').click();

	});
	
	function show(){
		if($('#filter_start_date').val() !== '' && $('#filter_finish_date').val() !== ''){
			$.ajax({
				url: '{{ url("admin/report/inventory/product_inventory/report") }}',
				type: 'POST',
				dataType: 'JSON',
				data: { startDate : $('#filter_start_date').val(), endDate : $('#filter_finish_date').val(), branch : $('#filter_branch').val(), mode : $('#filter_mode').val() },
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#datatable_serverside');
				},
				success: function(response) {
					loadingClose('#datatable_serverside');
					if(response.status == 200) {
						$('#body-result').html(response.content);
						$("html, body").animate({
							scrollTop: $(
							  'html, body').get(0).scrollHeight
						}, 500);
					} else {
						notif('warning', 'bg-warning', 'Ups! Error.');
					}
				},
				error: function() {
					loadingClose('#datatable_serverside');
				}
			});
		}else{
			notif('warning', 'bg-warning', 'Ups! Please choose start and end date.');
		}
	}
	
	function print(){
		if($('#filter_start_date').val() !== '' && $('#filter_finish_date').val() !== ''){
			let url = '{{ url("admin/report/inventory/product_inventory/print") }}?branch=' + $('#filter_branch').val() + '&startDate=' + $('#filter_start_date').val() + '&endDate=' + $('#filter_finish_date').val() + '&mode=' + $('#filter_mode').val();
			window.open(url, '_blank');
		}else{
			notif('warning', 'bg-warning', 'Ups! Please choose dates period.');
		}
	}


	function printByWarehouse(){
		if($('#filter_start_date').val() !== '' && $('#filter_finish_date').val() !== ''){
			let url = '{{ url("admin/report/inventory/product_inventory/print_by_warehouse") }}?branch=' + $('#filter_branch').val() + '&startDate=' + $('#filter_start_date').val() + '&endDate=' + $('#filter_finish_date').val()+ '&brand=' + $('#brand').val() + '&size=' + $('#size').val();
			window.open(url, '_blank');
		}else{
			notif('warning', 'bg-warning', 'Ups! Please choose dates period.');
		}
	}

	function printCardMode(){
		if($('#filter_start_date').val() !== '' && $('#filter_finish_date').val() !== ''){
			let url = '{{ url("admin/report/inventory/product_inventory/print_card_mode") }}?branch=' + $('#filter_branch').val() + '&startDate=' + $('#filter_start_date').val() + '&endDate=' + $('#filter_finish_date').val() + '&brand=' + $('#brand').val() + '&size=' + $('#size').val() + '&warehouse_id=' + $('#warehouse_id').val();
			window.open(url, '_blank');
		}else{
			notif('warning', 'bg-warning', 'Ups! Please choose dates period.');
		}
	}

	function exportFile(){
			if($('#filter_start_date').val() !== '' && $('#filter_finish_date').val() !== ''){
			let url = '{{ url("admin/report/inventory/product_inventory/export_by_warehouse") }}?branch=' + $('#filter_branch').val() + '&startDate=' + $('#filter_start_date').val() + '&endDate=' + $('#filter_finish_date').val() + '&brand=' + $('#brand').val() + '&size=' + $('#size').val();;
			return window.location.href = url;
		}else{
			notif('warning', 'bg-warning', 'Ups! Please choose dates period.');
		}
	}
	
	function resetFilter(){
		$('#filter_start_date').val('');
		$('#size').val('').trigger('change.select2');
		$('#brand').val('').trigger('change.select2');
		$('#filter_finish_date').val('');
		$('#body-result').html(`
			<tr>
				<td colspan="19" class="text-center bg-warning">PLEASE CHOOSE FILTER TO SHOW STOCK CARD</td>
			</tr>
		`);
	}
	</script>