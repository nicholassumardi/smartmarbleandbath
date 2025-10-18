<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Pengeluaran</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Pengeluaran</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data Pengeluaran</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Kode</th>
							<th>Nama Proyek</th>
							<th>Bidang Pekerjaan</th>
							<th>Lokasi Pekerjaan</th>
							<th>Pelanggan</th>
							<th>Tanggal</th>
							<th>PPN</th>
							<th>Pengeluaran</th>
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
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/finance/pengeluaran/datatable") }}',
            type: 'GET',
            data: {
               
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
            { name: 'kode', className: 'text-center align-middle' },
			{ name: 'nama', className: 'text-center align-middle' },
			{ name: 'bidang', className: 'text-center align-middle' },
			{ name: 'lokasi', className: 'text-center align-middle' },
			{ name: 'customer', className: 'text-center align-middle' },
            { name: 'tanggal', className: 'text-center align-middle' },
			{ name: 'ppn', searchable: false, className: 'text-center align-middle' },
            { name: 'pengeluaran', searchable: false, orderable: false, className: 'text-center align-middle' }
         ]
      }); 
	}
</script>