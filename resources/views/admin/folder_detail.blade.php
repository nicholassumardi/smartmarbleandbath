<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">My Folder & Files</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/folder') }}" class="btn bg-secondary btn-labeled btn-labeled-left mr-2">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
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
					<span class="breadcrumb-item active">My Folder & Files</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Files</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<form action="{{ url('admin/folder/add_files') }}" class="dropzone" id="dropzone_multiple">
					@csrf
				</form>
				<div class="table-responsive mt-3">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						<tr class="text-center">
							<th>File Name</th>
							<th>See & Download</th>
							<th>Created At</th>
							<th>Action</th>
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
      window.table = $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[2, 'desc']],
         ajax: {
            url: '{{ url("admin/folder/datatable_detail") }}',
            type: 'GET',
            data: {
				id : {{ $id }}
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
			{ name: 'filename', className: 'text-center align-middle' },
			{ name: 'filesource', orderable: false, className: 'text-center align-middle' },
			{ name: 'created_at', className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center align-middle' }
		]
      }); 
	}

	Dropzone.options.dropzoneMultiple = {
		paramName: "file",
		maxFilesize: 15,
		init: function() {
			this.on("sending", function(file, xhr, formData){
				formData.append('id', {{ $id }});
			});
			this.on("success", function(file, responseText) {
				if(responseText.status == '422'){
					notif('error', 'bg-danger', responseText.message);
				}else if(responseText.status == '200'){
					this.removeFile(file);
					notif('success', 'bg-success', responseText.message);
					loadDataTable();
				}
			});
		}
	};
	
	function destroy(val) {
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-trash"></i>', 'btn bg-success ml-1', function() {
				$.ajax({
				 url: '{{ url("admin/folder/delete_file") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : val },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					if(response.status == 200) {
						notif('success', 'bg-success', response.message);
						loadDataTable();
						notyConfirm.close();
					} else {
						notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'You do not have permission to delete this project!',
					   text: 'Please contact sales manager to ask delete this project.',
					   type: 'error'
					});
				 }
				});
			})
         ]
		}).show();
	}
</script>