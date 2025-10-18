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
							<option value="">All Branch</option>
						@foreach (DB::table('company_entities')->get() as $company)
							<option value="{{$company->id}}">{{$company->name}}</option>
						@endforeach
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
							 <th>No</th>
							 <th>SMB Item Code</th>
							 <th>Warehouse</th>
							 <th>Qty</th>
							 <th>Unit</th>
							 <th>Branch</th>
							 <th>Show Stock Card</th>
						 </tr>
					  </thead>
				   </table>
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
            url: '{{ url("admin/report/inventory/stock_card/datatable") }}',
            type: 'GET',
            data: {
				@if(session('bo_branch') == '1')
				branch : $('#filter_branch').val()
				@else
				branch : '2'
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
			{ name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'item', className: 'text-center align-middle' },
            { name: 'warehouse', orderable: false, className: 'text-center align-middle' },
            { name: 'qty', className: 'text-center align-middle' },
            { name: 'unit', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'branch', orderable: false, className: 'text-center align-middle' },
			{ name: 'card', orderable: false, searchable: false, className: 'text-center align-middle' },
		]
      }); 
	}

	
	function showStockCards(id){
			$.ajax({
				type : "POST",
				url  : "{{ url('admin/report/inventory/stock_card/print') }}",
				data : {
					id : id
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				cache: false,
				success: function(data){
					var w = window.open('about:blank');
					w.document.open();
					w.document.write(data);
					w.document.close();
				}
			});
		}
	
</script>