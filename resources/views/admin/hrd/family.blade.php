<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Family Data {{ $name }}</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/hrd/employee') }}" class="btn bg-secondary btn-labeled btn-labeled-left mr-2">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Employee</a>
					<span class="breadcrumb-item active">Family - {{ $name }}</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Family</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						<tr class="text-center">
							<th>#</th>
							<th>Fullname</th>
							<th>Relationship</th>
							<th>HP</th>
							<th>Address</th>
							<th>ID No.</th>
							<th>Gender</th>
							<th>Birthday</th>
							<th>Religion</th>
							<th>Marital</th>
							<th>Job</th>
							<th>Emergency</th>
							<th>Operation</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Family Member</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row justify-content-center">
					<div class="col-md-8">
						<form id="form_data">
							<div class="alert alert-danger" id="validation_alert" style="display:none;">
								<ul id="validation_content"></ul>
						    </div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Fullname :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="hidden" name="temp" id="temp">
									<input type="hidden" name="tempUser" id="tempUser" value="{{ $id }}">
									<input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full name ...">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Relationship :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<select name="relationship" id="relationship" class="custom-select">
										<option value="1">Father</option>
										<option value="2">Mother</option>
										<option value="3">Sibling</option>
										<option value="4">Spouse</option>
										<option value="5">Child</option>
										<option value="6">Cousin</option>
										<option value="7">Nibling</option>
										<option value="8">Parent in Law</option>
										<option value="9">Brother in Law</option>
										<option value="10">Sister in Law</option>
										<option value="11">Uncle</option>
										<option value="12">Aunt</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">HP :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="hp" id="hp" placeholder="081...">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Address :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<textarea class="form-control" id="address" name="address" rows="1" placeholder="Jl..."></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">ID Number :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="id_number" id="id_number" placeholder="351...">
								</div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Gender :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<select name="gender" id="gender" class="custom-select">
										<option value="M">Male</option>
										<option value="F">Female</option>
									</select>
							  </div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Birth date :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="date" class="form-control" name="birthday" id="birthday">
								</div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Religion :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<select name="religion" id="religion" class="custom-select">
										<option value="1">Islam</option>
										<option value="2">Catholic</option>
										<option value="3">Christian</option>
										<option value="4">Buddha</option>
										<option value="5">Hindu</option>
										<option value="6">Confucius</option>
										<option value="7">Others</option>
									</select>
							  </div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Marital Status :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<select name="marital_status" id="marital_status" class="custom-select">
										<option value="1">Single</option>
										<option value="2">Married</option>
										<option value="3">Widow</option>
										<option value="4">Widower</option>
									</select>
							  </div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Job :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="job" id="job" placeholder="Wiraswasta">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3">Emergency Contact :</label>
								<div class="col-lg-9">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" value="1" name="emergency" id="emergency" checked style="transform: scale(1.25);">
									</div>
								</div>
							</div>
							
						</form>
					</div>
				</div>
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
<script>
	$(function() {
		$('.sidebar-main-toggle').click();
		
		loadDataTable();
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#temp').val('');
			$('#form_data').trigger('reset');
		});
	});
	
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
				  url: '{{ url("admin/hrd/employee/family/destroy") }}',
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
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/hrd/employee/family/".$id."/datatable") }}',
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
            { name: 'fullname', className: 'text-center align-middle' },
            { name: 'relationship', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'hp', className: 'text-center align-middle' },
			{ name: 'address', className: 'text-center align-middle' },
			{ name: 'id_no', className: 'text-center align-middle' },
			{ name: 'gender', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'birthday', className: 'text-center align-middle' },
			{ name: 'religion', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'marital_status', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'job', className: 'text-center align-middle' },
			{ name: 'emergency', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'operation', orderable: false, searchable: false, className: 'text-center align-middle' },
         ]
      });
	}
   
	function success() {
	  $('#modal_form').modal('hide');
	  $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
   
	function create() {
	  $.ajax({
		 url: '{{ url("admin/hrd/employee/family/create") }}',
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
	  $.ajax({
		 url: '{{ url("admin/hrd/employee/family/show") }}',
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
			$('#temp').val(id);
			$('#fullname').val(response.fullname);
			$('#relationship').val(response.relationship);
			$('#hp').val(response.hp);
			$('#address').val(response.address);
			$('#id_number').val(response.id_number);
			$('#gender').val(response.gender);
			$('#birthday').val(response.birthday);
			$('#religion').val(response.religion);
			$('#marital_status').val(response.marital_status);
			$('#job').val(response.job);
			
			if(response.emergency == '1'){
				$('#emergency').prop('checked', true);
			}else{
				$('#emergency').prop('checked', false);
			}
			
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
</script>