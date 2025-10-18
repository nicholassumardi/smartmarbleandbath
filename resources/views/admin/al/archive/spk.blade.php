<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Kelengkapan Data</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">Arsip</a>
					<span class="breadcrumb-item active">SPK TNI</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Arsip SPK TNI</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Nama Proyek</th>
							<th>Nama Customer</th>
							<th>Berkas</th>
						 </tr>
					  </thead>
				   </table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_pictures" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add Project SPK TNI Photos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<input type="hidden" name="tempTni" id="tempTni">
				<p class="mb-3">Anda bisa mengunggah file untuk spk tni dan proyek ini. <b>Ukuran maksimal : 1 Mb / 1024 Kb, Maksimal file : 5.</b></p>

				<p class="font-weight-semibold">Unggah lebih dari satu file :</p>
				<form action="{{ url('admin/al/arsip/spk_tni/add_pictures') }}" class="dropzone" id="dropzone_multiple">
					@csrf
				</form>
				<div class="row mt-3" id="list-images">
				
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
			
			$('#modal_pictures').on('hidden.bs.modal', function (e) {
				loadDataTable();
			});
		});
		
		function loadDataTable() {
		  window.table = $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[0, 'desc']],
			 ajax: {
				url: '{{ url("admin/al/arsip/spk_tni/datatable") }}',
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
				{ name: 'proyek', className: 'align-middle' },
				{ name: 'customer', orderable: false, className: 'align-middle' },
				{ name: 'bukti', searchable: false, orderable: false, className: 'text-center align-middle' }
			 ]
		  }); 
		}
		
		var tempTni = 0;
	
		function addPictures(id){
			$('#tempTni').val(id);
			tempTni = id;
			$('#list-images').empty();
			$.ajax({
				 url: '{{ url("admin/al/arsip/spk_tni/get_pictures") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					id: tempTni
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					if(response.length > 0){
						$.each(response, function(i, val) {
							$('#list-images').append(`
								<div class="col-md-2 text-center" id="picture` + val.id + `">
									<a data-magnify="gallery" data-src="" data-caption="` + val.name + `" data-group="a" href="` + val.filename + `"><img src="` + val.picture + `" style="max-height:200px;" class="img-fluid img-thumbnail"></a>
									<p class="mt-3">
										<h4>` + val.name + `</h4>
									</p>
									<p class="mt-3">
										<button class="btn btn-danger btn-sm mr-3" onclick="destroyPicture(` + val.id + `);"><i class="icon-trash"></i></button>
										<a class="btn btn-success btn-sm" href="` + val.filename + `" target="_blank"><i class="icon-download"></i></a>
									</p>
								</div>
							`);
						});
					}else{
						$('#list-images').append(`
							<div class="col-md-12 text-center">
								<div class="alert alert-warning alert-styled-left">
									There is no pictures in this project.
								</div>
							</div>
						`);
					}
					
					loadingClose('.modal-content');
				 },
				 error: function() {
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
				 }
			});
			
			$('#modal_pictures').modal('toggle');
		}
		
		Dropzone.options.dropzoneMultiple = {
			paramName: "file",
			maxFilesize: 5,
			maxFiles: 5,
			/* acceptedFiles: ".jpeg,.jpg,.png,.gif", */
			init: function() {
				this.on("sending", function(file, xhr, formData){
					formData.append('id', tempTni);
				});
				this.on("success", function(file, responseText) {
					if(responseText.status == '422'){
						notif('error', 'bg-danger', responseText.message);
					}
				});
			}
		};
		
		function destroyPicture(val) {
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
					 url: '{{ url("admin/al/arsip/spk_tni/delete_picture") }}',
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
							$('#picture' + val).remove();
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