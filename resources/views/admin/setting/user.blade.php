<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Setting User</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" onclick="cancel()" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Setting</a>
					<span class="breadcrumb-item active">User</span>
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
						<option value="1">Active</option>
						<option value="2">Not Active</option>
					</select>
				</div>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>#</th>
                        <th>No</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Branch</th>
                        <th>Verification</th>
                        <th>Status</th>
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
            <h5 class="modal-title" id="exampleModalLabel">Form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="form_data">
               <div class="alert alert-danger" id="validation_alert" style="display:none;">
                  <ul id="validation_content"></ul>
               </div>
               <div class="form-group">
                  <center class="mt-3">
                     <a href="{{ asset("website/user.png") }}" id="preview_photo" data-lightbox="User" data-title="Preview Photo">
                        <img src="{{ asset("website/user.png") }}" class="img-fluid img-thumbnail w-100" style="max-width:200px;">
                     </a>
                  </center>
                  
               </div>
               <div class="row">
				  <div class="col-md-4">
					<div class="form-group">
						<label>Photo :</label>
						<input type="file" id="photo" name="photo" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg" onchange="previewImage(this, '#preview_photo')">
					</div>
				  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Name :<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" value="{{ old('name') }}">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Email :<span class="text-danger">*</span></label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                     </div>
                  </div>
               </div>
			   <div class="row">
                  <div class="col-md-4">
					<div class="form-group">
					  <label>Branch :<span class="text-danger">*</span></label>
					  <select name="branch" id="branch" class="custom-select">
						 <option value="">-- Choose --</option>
                     @foreach (DB::table('company_entities')->get() as $company)
                        <option value="{{$company->id}}">{{$company->name}}</option>
                     @endforeach
					  </select>
				   </div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
					  <label>Phone/WA :<span class="text-danger">*</span></label>
					  <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone number / wa">
				   </div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group field_password">
					  <label>Password :<span class="text-danger">*</span></label>
					  <input type="password" name="password" id="password" class="form-control" placeholder="Enter password">
				   </div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
					  <label>Role :<span class="text-danger">*</span></label>
					  <select name="role[]" id="role" class="select2" multiple>
						 <option value="1">Director</option>
						 <option value="2">Secretary</option>
						 <option value="3">Head Of Finance</option>
						 <option value="4">Head Of Accounting</option>
						 <option value="5">Sales & Marketing Manager</option>
						 <option value="6">Sales Project</option>
						 <option value="7">Head Of Administration</option>
						 <option value="8">Digital Marketing</option>
						 <option value="9">Purchasing</option>
						 <option value="10">Admin Sales & Stock</option>
						 <option value="11">Piutang & Pengiriman</option>
						 <option value="12">Assisten Hiro</option>
						 <option value="13">Assisten</option>
						 <option value="14">Head Of HRD</option>
						 <option value="15">Admin Retail</option>
						 <option value="16">Co-Owner</option>
						 <option value="17">After Sales Service</option>
						 <option value="18">Audit</option>
						 <option value="19">Import</option>
						 <option value="20">Stock</option>
					  </select>
				   </div>
				  </div>
			   </div>
			   <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Place of Birth :<span class="text-danger">*</span></label>
                        <input type="text" name="pob" id="pob" class="form-control" placeholder="Enter place of birth" value="{{ old('pob') }}">
                     </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label>Date of Birth :<span class="text-danger">*</span></label>
                        <input type="date" name="dob" id="dob" class="form-control" placeholder="Enter date of birth" value="{{ old('dob') }}">
                     </div>
                  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Gender :<span class="text-danger">*</span></label>
						<select name="gender" id="gender" class="custom-select">
							<option value="1">Male</option>
							<option value="2">Female</option>
						</select>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Marital Status :<span class="text-danger">*</span></label>
						<select name="marital_status" id="marital_status" class="custom-select">
							<option value="1">Single</option>
							<option value="2">Married</option>
							<option value="3">Widow</option>
							<option value="4">Widower</option>
							<option value="5">Divorced</option>
						</select>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Blood Type :<span class="text-danger">*</span></label>
						<input type="text" name="blood_type" id="blood_type" class="form-control" placeholder="Enter type of blood" value="{{ old('blood_type') }}">
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Religion :<span class="text-danger">*</span></label>
						<select name="religion" id="religion" class="custom-select">
							<option value="1">Islam</option>
							<option value="2">Kristen</option>
							<option value="3">Katolik</option>
							<option value="4">Hindu</option>
							<option value="5">Budha</option>
						</select>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>ID Card :<span class="text-danger">*</span></label>
						<select name="id_card" id="id_card" class="custom-select">
							<option value="1">KTP</option>
							<option value="2">SIM</option>
						</select>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>ID Number :<span class="text-danger">*</span></label>
						<input type="text" name="id_number" id="id_number" class="form-control" placeholder="Enter choosen id card number" value="{{ old('id_number') }}">
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Post Code :<span class="text-danger">*</span></label>
						<input type="text" name="postcode" id="postcode" class="form-control" placeholder="Enter post code" value="{{ old('postcode') }}">
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>ID Card Address :<span class="text-danger">*</span></label>
						<input type="text" name="address_id" id="address_id" class="form-control" placeholder="Enter id card address" value="{{ old('address_id') }}">
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Residence Address :<span class="text-danger">*</span></label>
						<input type="text" name="address_residence" id="address_residence" class="form-control" placeholder="Enter residence address" value="{{ old('address_residence') }}">
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Taxpayer :<span class="text-danger">*</span></label>
						<select name="ispkp" id="ispkp" class="custom-select">
							<option value="1">Yes</option>
							<option value="2">No</option>
						</select>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>NPWP :</label>
						<input type="text" name="npwp" id="npwp" class="form-control" placeholder="Enter npwp" value="{{ old('npwp') }}">
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>PTKP Type :</label>
						<select name="ptkp_type" id="ptkp_type" class="custom-select">
							<option value="">None</option>
							<option value="TK0">TK/0</option>
							<option value="TK1">TK/1</option>
							<option value="TK2">TK/2</option>
							<option value="TK3">TK/3</option>
							<option value="K0">K/0</option>
							<option value="K1">K/1</option>
							<option value="K2">K/2</option>
							<option value="K3">K/3</option>
							<option value="KI0">K/I/0</option>
							<option value="KI1">K/I/1</option>
							<option value="KI2">K/I/2</option>
							<option value="KI3">K/I/3</option>
						</select>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Tax Type :</label>
						<select name="tax_type" id="tax_type" class="custom-select">
							<option value="1">Gross</option>
							<option value="2">Gross Up</option>
							<option value="3">Netto</option>
						</select>
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Account Number :<span class="text-danger">*</span></label>
						<input type="text" name="account_number" id="account_number" class="form-control" placeholder="Enter account number" value="{{ old('account_number') }}">
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Account Bank :<span class="text-danger">*</span></label>
						<input type="text" name="account_bank" id="account_bank" class="form-control" placeholder="Enter account bank" value="{{ old('account_bank') }}">
					</div>
				  </div>
				  <div class="col-md-4">
					<div class="form-group">
						<label>Account Name :<span class="text-danger">*</span></label>
						<input type="text" name="account_name" id="account_name" class="form-control" placeholder="Enter account bank" value="{{ old('account_name') }}">
					</div>
				  </div>
			   </div>
               <div class="form-group text-center mt-4">
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" value="2">
                        Not Active
                     </label>
                  </div>
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" value="1" checked>
                        Active
                     </label>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
            <button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()" style="display:none;"><i class="icon-cross3"></i> Cancel</button>
            <button type="button" class="btn bg-warning" id="btn_update" onclick="update()" style="display:none;"><i class="icon-pencil7"></i> Save</button>
            <button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
         </div>
      </div>
   </div>
</div>

<script>
   $(function() {
      var table = loadDataTable();

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
         url: '{{ url("admin/setting/user/row_detail") }}',
         type: 'GET',
         async: false,
         data: {
            id: $(data[0]).data('id')
         },
         success: function(response) {
            content += `
               <div class="form-group row">
                  <label class="col-form-label col-lg-2">Email</label>
                  <div class="col-lg-10">: ` + response.email + `</div>
               </div>
               <div class="form-group row">
                  <label class="col-form-label col-lg-2">Role</label>
                  <div class="col-lg-10">:
            `;

            $.each(response.role, function(i, val) {
               content += `
                  <span class="badge badge-info">` + val + `</span>
               `;
            });

            content += '</div></div>';
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
      $('.field_password').hide();
      $('#btn_create').hide();
      $('#btn_update').show();
      $('#btn_cancel').show();
   }

  function reset() {
      $('#form_data').trigger('reset');
      $('input[name="status"][value="1"]').prop('checked', true);
      $('#role').val(null).change();
      $('#validation_alert').hide();
      $('#validation_content').html('');
      $('#preview_photo').attr('href', '{{ asset("website/user.png") }}');
      $('#preview_photo img').attr('src', '{{ asset("website/user.png") }}');
      $('.field_password').show();
   }

  function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
   }

   function loadDataTable() {
      return $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/setting/user/datatable") }}',
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
            { name: 'detail', orderable: false, searchable: false, className: 'text-center align-middle details-control' },
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'photo', searchable: false, className: 'text-center align-middle' },
            { name: 'name', className: 'text-center align-middle' },
            { name: 'branch', searchable: false, className: 'text-center align-middle' },
            { name: 'verification', searchable: false, className: 'text-center align-middle' },
            { name: 'status', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }

   function create() {
      $.ajax({
         url: '{{ url("admin/setting/user/create") }}',
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
         url: '{{ url("admin/setting/user/show") }}',
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
            $('#preview_photo').attr('href', response.photo);
            $('#preview_photo img').attr('src', response.photo);
            $('#name').val(response.name);
			$('#phone').val(response.phone);
            $('#email').val(response.email);
            $('#branch').val(response.branch);
			$('#pob').val(response.place_of_birth);
			$('#dob').val(response.date_of_birth);
			$('#gender').val(response.gender);
			$('#marital_status').val(response.marital_status);
			$('#blood_type').val(response.blood_type);
			$('#religion').val(response.religion);
			$('#id_card').val(response.id_type);
			$('#id_number').val(response.id_no);
			$('#postcode').val(response.postcode);
			$('#address_id').val(response.address_id);
			$('#address_residence').val(response.address_residence);
			$('#npwp').val(response.npwp);
			$('#ispkp').val(response.ispkp);
			$('#ptkp_type').val(response.ptkp_type);
			$('#tax_type').val(response.tax_type);
			$('#account_number').val(response.account_number);
			$('#account_bank').val(response.account_bank);
			$('#account_name').val(response.account_name);
            $('#role').val(response.role).change();
            $('input[name="status"][value="' + response.status + '"]').prop('checked', true);
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
         url: '{{ url("admin/setting/user/update") }}' + '/' + id,
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
                  url: '{{ url("admin/setting/user/destroy") }}',
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

   function resetPassword(id) {
      var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to reset password?</h6><label>password will be reset to <b>"smartmarble"</b>.</label>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-lock"></i>', 'btn bg-success ml-1', function() {
               $.ajax({
                  url: '{{ url("admin/setting/user/reset_password") }}',
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