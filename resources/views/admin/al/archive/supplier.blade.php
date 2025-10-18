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
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Tambah
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
					<span class="breadcrumb-item active">Supplier</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Arsip Data Supplier</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Code</th>
							<th>Nama Proyek</th>
							<th>Supplier</th>
							<th>Berkas</th>
							<th>Action</th>
						 </tr>
					  </thead>
				   </table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add/Edit Al Arsip Data Supplier</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data">
				   <div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
				   </div>
				   <div class="row justify-content-center">
						<div class="col-md-4">
							<div class="alert alert-info border-0 alert-dismissible">
								<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
								<span class="font-weight-semibold">Info!</span> Supplier diambil dari proyek yang telah memiliki PO, jadi pastikan proyek terpilih memiliki PO agar supplier muncul disini.
							</div>
						</div>
				   </div>
				   <div class="row justify-content-center">
					  <div class="col-md-4">
							<div class="form-group">
								<label>Proyek :<span class="text-danger">*</span></label>
								<input type="hidden" id="temp" name="temp">
								<select name="al_project_id" id="al_project_id" class="select2" onchange="getSupplier(this.value);">
									 <option value="">-- Pilih satu --</option>
									 @foreach($proyek as $p)
										<option value="{{ $p->id }}">{{ $p->name }}</option> 
									 @endforeach
								</select>
							</div>
					  </div>
					  <div class="col-md-4">
							<div class="form-group">
								<label>Supplier :<span class="text-danger">*</span></label>
								<select name="al_supplier_id" id="al_supplier_id" class="select2">
									<option value="">-- Pilih proyek --</option>
								</select>
							</div>
					  </div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_new" onclick="create()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_pictures" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add Project Supplier Photos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<input type="hidden" name="tempSupplier" id="tempSupplier">
				<p class="mb-3">Anda bisa mengunggah file untuk supplier dan proyek ini. <b>Ukuran maksimal : 1 Mb / 1024 Kb, Maksimal file : 5.</b></p>

				<p class="font-weight-semibold">Unggah lebih dari satu file :</p>
				<form action="{{ url('admin/al/arsip/supplier/add_pictures') }}" class="dropzone" id="dropzone_multiple">
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
			
			$('#modal_form').on('hidden.bs.modal', function (e) {
				$('#temp').val('');
				$('#al_project_id').val('').trigger('change');
				$('#al_supplier_id').val('').trigger('change');
			});
			
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
				url: '{{ url("admin/al/arsip/supplier/datatable") }}',
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
				{ name: 'code', className: 'text-center align-middle' },
				{ name: 'proyek', className: 'text-center align-middle' },
				{ name: 'supplier', className: 'text-center align-middle' },
				{ name: 'bukti', searchable: false, orderable: false, className: 'text-center align-middle' },
				{ name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
			 ]
		  }); 
		}
		
		function getSupplier(val){
			$('#al_supplier_id').empty();
			$('#al_supplier_id').append(`
				<option value="">-- Pilih proyek --</option>
			`);
			if(val !== ''){
				$.ajax({
					 url: '{{ url("admin/al/arsip/supplier/get_supplier") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: {
						id: val
					 },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('.modal-body');
					 },
					 success: function(response) {
						
						if(response.supplier.length > 0){
							$.each(response.supplier, function(i, val) {
								$('#al_supplier_id').append(`
									<option value="` + val.al_supplier_id + `">` + val.al_supplier_code + ` ` + val.al_supplier_name + `</option>
								`);
							});
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
		}
		
		var tempSupplier = 0;
	
		function addPictures(id){
			$('#tempSupplier').val(id);
			tempSupplier = id;
			$('#list-images').empty();
			$.ajax({
				 url: '{{ url("admin/al/arsip/supplier/get_pictures") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					id: tempSupplier
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
									<a data-magnify="gallery" data-src="" data-caption="` + val.name + `" data-group="a" href="` + val.filename + `"><img src="` + val.filename + `" style="max-height:200px;" class="img-fluid img-thumbnail"></a>
									<p class="mt-3">
										<button class="btn btn-danger btn-sm" onclick="destroyPicture(` + val.id + `);"><i class="icon-trash"></i></button>
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
			
		function create(){
			$.ajax({
			 url: '{{ url("admin/al/arsip/supplier/create") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert').hide();
				$('#validation_content').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   notif('success', 'bg-success', response.message);
				   $('#modal_form').modal('toggle');
				   loadDataTable();
				} else if(response.status == 422) {
				   $('#validation_alert').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content').append(`
							<li>` + val + `</li>
						 `);
					  });
					});
				} else {
				   notif('error', 'bg-danger', response.message);
				}
			 },
			 error: function() {
				$('.modal-body').scrollTop(0);
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
			});
		}
		
		function show(id){
			$.ajax({
				 url: '{{ url("admin/al/arsip/supplier/show") }}',
				 type: 'GET',
				 dataType: 'JSON',
				 data: {
					id: id
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					
					$('#temp').val(response.data.id);
					$('#al_project_id').val(response.data.al_project_id).trigger('change');
					
					setTimeout(function(){
						$('#al_supplier_id').val(response.data.al_supplier_id).trigger('change');
					}, 1000);
					
					$('#modal_form').modal('toggle');
				 },
				 error: function() {
					cancel();
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
				 }
			});
		}
		
		Dropzone.options.dropzoneMultiple = {
			paramName: "file",
			maxFilesize: 1,
			maxFiles: 5,
			acceptedFiles: ".jpeg,.jpg,.png,.gif",
			init: function() {
				this.on("sending", function(file, xhr, formData){
					formData.append('id', tempSupplier);
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
					 url: '{{ url("admin/al/arsip/supplier/delete_picture") }}',
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