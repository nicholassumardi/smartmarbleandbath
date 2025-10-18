<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">AL List Pelanggan</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button class="btn bg-indigo-400 btn-labeled mr-2 btn-labeled-left" onclick="exportData()">
						<b><i class="icon-file-excel"></i></b> Export
					</button>
					<button class="btn bg-pink-400 btn-labeled mr-2 btn-labeled-left" onclick="printData()">
						<b><i class="icon-printer2"></i></b> Print
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" onclick="cancel()" data-toggle="modal" data-target="#modal_form">
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
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<span class="breadcrumb-item active">Pelanggan</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data Pelanggan</h5>
				<div class="header-elements">
					<select name="filter_type" id="filter_type" class="custom-select" onchange="loadDataTable()">
						<option value="">All Type</option>
						<option value="1">Online</option>
						<option value="2">Offline</option>
						<option value="3">Hybrid</option>
					</select>
				</div>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Verification</th>
                        <th>Register</th>
                        <th>Action</th>
                     </tr>
                  </thead>
               </table>
            </div>
			</div>
		</div>
	</div>

   <div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Form Customer List</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
			<ul class="nav nav-tabs nav-tabs-top nav-justified mt-3">
				<li class="nav-item"><a href="#customer-tab" class="nav-link active" data-toggle="tab">Pelanggan</a></li>
				<li class="nav-item"><a href="#instansi-tab" class="nav-link" data-toggle="tab">Instansi</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="customer-tab">
					<form id="form_data">
					   <div class="alert alert-danger" id="validation_alert" style="display:none;">
						  <ul id="validation_content"></ul>
					   </div>
					   <div class="form-group">
						  <label>Nama :<span class="text-danger">*</span></label>
						  <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan contoh: STTAL">
					   </div>
					   <div class="form-group">
						  <label>Inisial :<span class="text-danger">*</span></label>
						  <input type="text" name="initial" id="initial" class="form-control" placeholder="Masukkan contoh: STL">
					   </div>
						<div class="form-group">
						  <label>Institusi :<span class="text-danger">*</span></label>
						  <select name="institute_id" id="institute_id" class="select2">
							   @foreach($institute as $ins)
								  <option value="{{ $ins->id }}">{{ $ins->name.' - '.$ins->initial }}</option>
							   @endforeach
							</select>
					   </div>
					   <div class="form-group">
						  <label>Nama PIC : </label>
						  <input type="text" name="pic" id="pic" class="form-control" placeholder="Leave empty if he/she has no constructor name" value="none">
					   </div>
					   <div class="form-group">
						  <label>Tanggal Lahir PIC : </label>
						  <input type="date" name="pic_birthday" id="pic_birthday" class="form-control">
					   </div>
					   <div class="form-group">
						  <label>Posisi : </label>
						  <input type="text" name="pic_position" id="pic_position" class="form-control" placeholder="Contoh : Danramil">
					   </div>
					   <div class="form-group">
						  <label>Jabatan : </label>
						  <input type="text" name="pic_office" id="pic_office" class="form-control" placeholder="Contoh : Jenderal">
					   </div>
					   <div class="form-group">
						  <label>Alamat : </label>
						  <input type="text" name="address" id="address" class="form-control" value="none">
					   </div>
					   <div class="form-group">
						  <label>NPWP : </label>
						  <input type="text" name="npwp" id="npwp" class="form-control" value="none">
					   </div>
					   <div class="form-group">
						  <label>Alamat NPWP : </label>
						  <input type="text" name="address_npwp" id="address_npwp" class="form-control" value="none">
					   </div>
					   <div class="form-group">
						  <label>PIC Finance : </label>
						  <input type="text" name="finance_name" id="finance_name" class="form-control" value="none">
					   </div>
					   <div class="form-group">
						  <label>PIC Finance Telepon : </label>
						  <input type="text" name="finance_hp" id="finance_hp" class="form-control" value="none">
					   </div>
					   <div class="form-group">
						  <label>Email :<span class="text-danger">*</span></label>
						  <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email">
					   </div>
					   <div class="form-group">
						  <label>Tipe :<span class="text-danger">*</span></label>
						  <div class="form-group">
							 <div class="form-check form-check-inline">
								<label class="form-check-label">
								   <input type="radio" class="form-check-input" name="type" value="1" checked>
								   Online
								</label>
							 </div>
							 <div class="form-check form-check-inline">
								<label class="form-check-label">
								   <input type="radio" class="form-check-input" name="type" value="2">
								   Offline
								</label>
							 </div>
							 <div class="form-check form-check-inline">
								<label class="form-check-label">
								   <input type="radio" class="form-check-input" name="type" value="3">
								   Hybrid
								</label>
							 </div>
						  </div>
						  <div>
							<p>
								<ul>
									<li>Online : customer registered from online store.</li>
									<li>Offline : customer added by sales from project's sales team or offline project.</li>
									<li>Hybrid : customer registered from online store but pay and visit the offline store.</li>
								</ul>
							</p>
						  </div>
					   </div>
					   <div class="form-group">
						  <label>Password :</label>
						  <input type="password" name="password" id="password" class="form-control" placeholder="Enter password / leave empty and it will not change current set password.">
					   </div>
					   <div class="form-group">
						  <label>Telepon :<span class="text-danger">*</span></label>
						  <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone">
					   </div>
					   <div class="form-group">
						  <label>Gambar Profil / Logo Perusahaan :</label>
						  <div class="input-group">
							 <div class="custom-file">
								<input type="file" id="image" name="image" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg" onchange="previewImage(this, '#preview_image')">
							 </div>
						  </div>
					   </div>
					   <div class="form-group">
						  <div class="text-center">
							 Preview Gambar<br>
							 <a href="{{ asset('website/user.png') }}" id="preview_image" data-lightbox="Image" data-title="Preview Image">
								<img src="{{ asset('website/user.png') }}" class="img-fluid img-thumbnail w-100" style="max-width:200px;">
							 </a>
							 <p class="text-danger font-italic mt-3">
								Maximum file size is <b>100KB</b> & the only files supported are <b>jpeg, jpg, png</b>
							 </p>
						  </div>
					   </div>
					</form>
				</div>
				<div class="tab-pane fade" id="instansi-tab">
					<div class="row justify-content-center">
						<div class="col-md-6">
							<form id="form_data_institute">
							   <div class="alert alert-danger" id="validation_alert_instansi" style="display:none;">
								  <ul id="validation_content_instansi"></ul>
							   </div>
							   <div class="form-group">
								  <label>Nama Instansi :<span class="text-danger">*</span></label>
								  <input type="hidden" name="tempInstansi" id="tempInstansi">
								  <input type="text" name="name_institute" id="name_institute" class="form-control" placeholder="Masukkan nama instansi disini.">
							   </div>
							   <div class="form-group">
								  <label>Inisial :<span class="text-danger">*</span></label>
								  <input type="text" name="initial_institute" id="initial_institute" class="form-control" placeholder="Masukkan inisial instansi disini.">
							   </div>
							</form>
							<div class="row">
								<div class="col-md-4 mx-auto mt-2">
									<button type="button" class="btn bg-primary btn-block" onclick="create_institute()"><i class="icon-plus3"></i> Tambah Instansi</button>
								</div>
							</div>
							<div class="form-group"><hr></div>
							<h5 class="card-title">List Data Institusi</h5>
							<div class="table-responsive">
							   <table id="datatable_serverside_institute" class="table table-bordered table-striped">
								  <thead class="bg-dark">
									 <tr class="text-center">
										<th>No</th>
										<th>Name</th>
										<th>Initial</th>
										<th>Action</th>
									 </tr>
								  </thead>
							   </table>
							</div>
						</div>
					</div>
				</div>
			</div>
            
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Tutup</button>
            <button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()" style="display:none;"><i class="icon-cross3"></i> Batal</button>
            <button type="button" class="btn bg-warning" id="btn_update" onclick="update()" style="display:none;"><i class="icon-pencil7"></i> Simpan</button>
            <button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Simpan</button>
         </div>
      </div>
   </div>
</div>
<script>
   $(function() {
      loadDataTable();
	  loadDataTableInstitute();
	  
	$('#modal_form').on('hidden.bs.modal', function (e) {
		reset();
	});
   });

   function cancel() {
      reset();
      $('#modal_form').modal('hide');
      $('#btn_create').show();
      $('#btn_update').hide();
      $('#btn_cancel').hide();
   }

   function toShow() {
      $('#modal_form').modal('show');
      $('#validation_alert').hide();
      $('#validation_content').html('');
      $('#btn_create').hide();
      $('#btn_update').show();
      $('#btn_cancel').show();
   }

  function reset() {
	  $('#tempInstansi').val('');
      $('#form_data').trigger('reset');
	  $('#form_data_institute').trigger('reset');
      $('input[name="status"][value="1"]').prop('checked', true);
      $('#validation_alert,#validation_alert_instansi').hide();
      $('#validation_content,#validation_content_instansi').html('');
   }

  function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	  $('#datatable_serverside_institute').DataTable().ajax.reload(null, false);
   }

	function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/master_data/customer/datatable") }}',
            type: 'GET',
            data: {
               type: $('#filter_type').val()
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
            { name: 'photo', searchable: false, className: 'text-center align-middle' },
            { name: 'name', className: 'text-center align-middle' },
            { name: 'email', className: 'text-center align-middle' },
            { name: 'phone', className: 'text-center align-middle' },
            { name: 'type', searchable: false, className: 'text-center align-middle' },
            { name: 'verification', searchable: false, className: 'text-center align-middle' },
            { name: 'created_at', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function loadDataTableInstitute() {
      $('#datatable_serverside_institute').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/al/master_data/customer/datatable_institute") }}',
            type: 'GET',
            data: {
               
            },
            beforeSend: function() {
               loadingOpen('#datatable_serverside_institute');
            },
            complete: function() {
               loadingClose('#datatable_serverside_institute');
            },
            error: function() {
               loadingClose('#datatable_serverside_institute');
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
            { name: 'initial', className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}

   function create() {
      $.ajax({
         url: '{{ url("admin/al/master_data/customer/create") }}',
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
               success();
               notif('success', 'bg-success', response.message);
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

   function show(id) {
      toShow();
      $.ajax({
         url: '{{ url("admin/al/master_data/customer/show") }}',
         type: 'POST',
         dataType: 'JSON',
         data: {
            id: id
         },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            $('#name').val(response.name);
            $('#pic').val(response.pic);
			$('#pic_birthday').val(response.pic_birthday);
			$('#pic_position').val(response.pic_position);
			$('#pic_office').val(response.pic_office);
			$('#initial').val(response.initial);
			$('#institute_id').val(response.al_institute_id).trigger('change');
            $('#email').val(response.email);
            $('#phone').val(response.phone);
            $('#preview_image').attr('href', response.photo);
            $('#preview_image img').attr('src', response.photo);
			$('#address').val(response.address);
			$('#npwp').val(response.npwp);
			$('#address_npwp').val(response.address_npwp);
			$('#finance_name').val(response.finance_name);
			$('#finance_hp').val(response.finance_hp);
            $('input[name="type"][value="' + response.type + '"]').prop('checked', true);
            $('#btn_update').attr('onclick', 'update(' + id + ')');
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

   function update(id) {
      $.ajax({
         url: '{{ url("admin/al/master_data/customer/update") }}' + '/' + id,
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
               success();
               notif('success', 'bg-success', response.message);
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
   
	function create_institute(){
		
		$.ajax({
         url: '{{ url("admin/al/master_data/customer/create_institute") }}',
         type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_institute')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_instansi').hide();
            $('#validation_content_instansi').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
				$('#form_data_institute')[0].reset();
				$('#tempInstansi').val('');
				$('#institute_id').empty();
				$.each(response.data, function(i, val) {
					$('#institute_id').append(`
						<option value="` + val.id + `">` + val.name + ` - ` + val.initial + `</option>
					`);
				});
				
				loadDataTableInstitute();
				notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_instansi').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_instansi').append(`
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
		
		return false;
	}
	
	function showCategory(id) {
      $.ajax({
         url: '{{ url("admin/al/master_data/customer/show_institute") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
         beforeSend: function() {
			$('#validation_alert_instansi').hide();
            $('#validation_content_instansi').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
			$('.modal-body').animate({ scrollTop: 0 }, 'slow');
			$('#tempInstansi').val(id);
			$('#name_institute').val(response.name);
			$('#initial_institute').val(response.initial);
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
	}
   
   function exportData(){
		var table = $('#datatable_serverside').DataTable();
		var info = table.page.info();
		var search = table.search(), numrow = info.length, start = info.start, type = $('#filter_type').val();
		
		window.location = "{{ url('admin/al/master_data/customer/export') }}?search=" + search + "&numrow=" + numrow + "&start=" + start + "&type=" + type;
   }
   
   function printData(){
		var table = $('#datatable_serverside').DataTable();
		var info = table.page.info();
		var search = table.search(), numrow = info.length, start = info.start, type = $('#filter_type').val();
		
		window.open("{{ url('admin/al/master_data/customer/print') }}?search=" + search + "&numrow=" + numrow + "&start=" + start + "&type=" + type, "_blank");
   }

	function destroy(id) {
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
                  url: '{{ url("admin/al/master_data/customer/destroy") }}',
                  type: 'POST',
                  dataType: 'JSON',
                  data: {
                     id: id
                  },
                  headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function(response) {
                     if(response.status == 200) {
                        $('#datatable_serverside').DataTable().ajax.reload(null, false);
                        notif('success', 'bg-success', response.message);
                        notyConfirm.close();
                     } else {
                        notif('error', 'bg-danger', response.message);
                     }
                  },
                  error: function() {
                     swalInit.fire({
                        title: 'Server Error',
                        text: 'Please contact developer',
                        type: 'error'
                     });
                  }
               });
            })
         ]
      }).show();
	}
</script>