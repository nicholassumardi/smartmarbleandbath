<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">SMB Stock</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled btn-labeled-left mr-2" data-toggle="modal" data-target="#modal_stock">
						<b><i class="icon-file-pdf"></i></b> Report
					</button>
					<!-- <button type="button" class="btn bg-primary btn-labeled btn-labeled-left mr-2" onclick="alert('Under maintenance!')">
						<b><i class="icon-file-pdf"></i></b> Report
					</button> -->
					<button type="button" class="btn bg-success btn-labeled btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Inventory</a>
					<span class="breadcrumb-item active">Stock</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Warehouse Stock</h5>
				<div class="header-elements">
					@if(session('bo_branch') == '1')
					<form action="#" class="mr-2">
						<select name="filter_branch" id="filter_branch" class="custom-select" onchange="loadDataTable()">
							@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							@endforeach
						</select>
						<select name="hasQty" id="hasQty" class="custom-select" onchange="loadDataTable()">
							<option value="1">All</option>
							<option value="2">In Stock</option>
							<option value="2">No Stock</option>
						</select>
					</form>
					@endif
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100 table-hover">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>#</th>
							<th>No</th>
							<th>SMB Item Code</th>
							<th>Size</th>
							<th>Warehouse</th>
							<th>Qty</th>
							<th>Unit</th>
							<th>Branch</th>
						 </tr>
					  </thead>
				   </table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_stock" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content modal-sm">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Report Stock SMB & PTA</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<a class="btn bg-teal-400 btn-block" href="https://smartmarbleandbath.com/admin/inventory/stock/report/1" target="_blank">PTA in QTY</a>
					</div>
					<div class="col-md-12 mt-3">
						<a class="btn bg-indigo-400 btn-block" href="https://smartmarbleandbath.com/admin/inventory/stock/report/2" target="_blank">SMB in QTY</a>
					</div>
					<div class="col-md-12 mt-3">
						<a class="btn bg-success btn-block" href="https://smartmarbleandbath.com/admin/inventory/stock/report_real/1" target="_blank">PTA in Real</a>
					</div>
					<div class="col-md-12 mt-3">
						<a class="btn bg-warning btn-block" href="https://smartmarbleandbath.com/admin/inventory/stock/report_real/2" target="_blank">SMB in Real</a>
					</div>
					@if(session('bo_branch') == '1')
					<div class="col-md-12 mt-3">
						<a class="btn bg-success btn-block" href="https://smartmarbleandbath.com/admin/inventory/stock/report_real_in_rp/1" target="_blank">PTA in Real & IDR</a>
					</div>
					<div class="col-md-12 mt-3">
						<a class="btn bg-warning btn-block" href="https://smartmarbleandbath.com/admin/inventory/stock/report_real_in_rp/2" target="_blank">SMB in Real & IDR</a>
					</div>
					<div class="col-md-12 mt-3">
						<a class="btn bg-purple-400 btn-block" href="https://smartmarbleandbath.com/admin/inventory/stock/report_in_rp/1" target="_blank">PTA in IDR</a>
					</div>
					<div class="col-md-12 mt-3">
						<a class="btn bg-success-400 btn-block" href="https://smartmarbleandbath.com/admin/inventory/stock/report_in_rp/2" target="_blank">SMB in IDR</a>
					</div>
					@endif
				</div>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
		</div>
	</div>
<script>
	$(function() {
		loadDataTable();
		
		$('#datatable_serverside tbody').on('click', 'td.details-control', function() {
			var tr    = $(this).closest('tr');
			var badge = tr.find('span.badge');
			var icon  = tr.find('i');
			var row   = table.row(tr);

			if(row.child.isShown()) {
				row.child.hide();
				tr.removeClass('shown');
				badge.first().removeClass('badge-danger');
				badge.first().addClass('badge-success');
				icon.first().removeClass('icon-minus3');
				icon.first().addClass('icon-plus3');
			} else {
				row.child(rowDetail(row.data())).show();
				tr.addClass('shown');
				badge.first().removeClass('badge-success');
				badge.first().addClass('badge-danger');
				icon.first().removeClass('icon-plus3');
				icon.first().addClass('icon-minus3');
			}
		});
	});
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/inventory/stock/datatable") }}',
            type: 'GET',
            data: {
				@if(session('bo_branch') == '1')
				branch : $('#filter_branch').val(),
				hasQty : $('#hasQty').val()
				@else
				branch : '2',
				hasQty : $('#hasQty').val()
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
			{ name: 'detail', orderable: false, searchable: false, className: 'text-center align-middle details-control' },
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'item', className: 'text-center align-middle' },
            { name: 'size',  orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'warehouse', orderable: false, className: 'text-center align-middle' },
            { name: 'qty', className: 'text-center align-middle' },
            { name: 'unit', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'branch', orderable: false, className: 'text-center align-middle' },
		]
      }); 
	}
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ session("bo_branch") == "1" ? url("admin/inventory/stock/row_detail") : url("admin/inventory/stock/row_detail_jkt") }}',
         type: 'GET',
         async: false,
         data: {
            id: $(data[0]).data('id')
         },
         success: function(response) {
            content += response;
         },
         error: function() {
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });

      return content;
	}
</script>