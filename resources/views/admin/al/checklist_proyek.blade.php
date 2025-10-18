<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Checklist Proyek</span>
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
					<span class="breadcrumb-item active">Checklist Proyek</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<td>Checklist</td>
							<td>No.Proyek</td>
							<td>Nama Proyek</td>
							<td>Customer</td>
							<td>Tanggal</td>
							<td>Keterangan</td>
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
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/al/checklist_proyek/row_detail") }}',
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
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/al/checklist_proyek/datatable") }}',
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
			{ name: 'detail', searchable: false, className: 'text-center align-middle details-control' },
            { name: 'code', className: 'text-center align-middle' },
			{ name: 'name', className: 'text-center align-middle' },
			{ name: 'customer', className: 'text-center align-middle' },
            { name: 'date', className: 'text-center align-middle' },
            { name: 'note', className: 'align-middle' }
         ]
      }); 
	}
	
	function saveRowChecklist(checklist,project){
		var status = $('#opsi' + checklist + '_' + project).val(), tgl = $('#date' + checklist + '_' + project).val(), note = $('#note' + checklist + '_' + project).val();
		
		$.ajax({
			 url: '{{ url("admin/al/checklist_proyek/create") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				al_checklist_id: checklist,
				al_project_id: project,
				status: status,
				date: tgl,
				note: note
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-body');
			 },
			 success: function(response) {
				if(response.status == '200'){
					notif('success', 'bg-success', response.message);
					$('#opsi' + checklist + '_' + project).prop('disabled', true);
					$('#date' + checklist + '_' + project).prop('disabled', true);
					$('#note' + checklist + '_' + project).prop('disabled', true);
					$('#btnadd' + checklist + '_' + project).addClass('d-none');
					$('#btnedit' + checklist + '_' + project).removeClass('d-none');
				}
				
				loadingClose('.modal-body');
			 },
			 error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
	}
	
	function showRowChecklist(checklist,project){
		$('#btnadd' + checklist + '_' + project).removeClass('d-none');
		$('#btnedit' + checklist + '_' + project).addClass('d-none');
		$('#opsi' + checklist + '_' + project).prop('disabled', false);
		$('#date' + checklist + '_' + project).prop('disabled', false);
		$('#note' + checklist + '_' + project).prop('disabled', false);
	}
</script>