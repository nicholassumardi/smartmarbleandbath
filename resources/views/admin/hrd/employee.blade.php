<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Employee</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<span class="breadcrumb-item active">Employee</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Employee</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						<tr class="text-center">
							<th>#</th>
							<th>Employee</th>
							<th>Branch</th>
							<th>Position</th>
							<th>Employment</th>
							<th>Family</th>
							<th>Education</th>
							<th>Experience</th>
							<th>Assets</th>
							<th>Loan</th>
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
	});

	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/hrd/employee/datatable") }}',
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
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'name', className: 'text-center align-middle' },
            { name: 'branch', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'position', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'status', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'family', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'education', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'experience', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'assets', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'loan', orderable: false, searchable: false, className: 'text-center align-middle' },
         ]
      }); 
   }
</script>