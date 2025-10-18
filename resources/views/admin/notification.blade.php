<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">All Activities</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<span class="breadcrumb-item active">All Activities</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
				<div class="header-elements">
					<select name="filter_status" id="filter_status" class="custom-select" onchange="loadDataTable()">
						<option value="">All Status</option>
						<option value="1">Unread</option>
						<option value="2">Read</option>
					</select>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				  <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					<thead class="bg-dark">
						<tr class="text-center">
							<th>Title</th>
							<th>Description</th>
							<th>Date</th>
						</tr>
					</thead>
				  </table>
			  </div>
			</div>
		</div>
	</div>
	<script>
		$(function() {
			var table = loadDataTable();
		});
	
		function loadDataTable() {
		  return $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [],
			 ajax: {
				url: '{{ url("admin/all_activities/datatable") }}',
				type: 'GET',
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
				{ name: 'title', orderable: false, className: 'text-center align-middle' },
				{ name: 'description', orderable: false, className: 'align-middle' },
				{ name: 'date', orderable: false, searchable: false, className: 'text-center align-middle' }
			 ]
		  }); 
	   }
	</script>