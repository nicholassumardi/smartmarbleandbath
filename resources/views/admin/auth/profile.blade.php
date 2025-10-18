<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Profile</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<span class="breadcrumb-item active">Profile</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <div class="row">
         <div class="col-md-3">
            <div class="card">
               <div class="card-body text-center card-img-top" style="background-image: url({{ asset('template/back-office/global_assets/images/backgrounds/panel_bg.png') }}; background-size: contain;">
                  <div class="card-img-actions d-inline-block mb-3">
                     <img class="img-fluid rounded-circle" src="{{ Storage::exists($user->photo) ? asset(Storage::url($user->photo)) : asset('website/user.png') }}" width="170" height="170" alt="{{ $user->name }}">
                  </div>
                  <h6 class="font-weight-semibold mb-0">{{ $user->name }}</h6>
                  @foreach($user->userRole as $ur)
                     <span class="d-block opacity-75">{{ $ur->role() }}</span>
                  @endforeach
                  <span class="d-block opacity-75">{{ $user->branch() }}</span>
               </div>
               <div class="card-body p-0">
				  <ul class="nav nav-tabs nav-tabs-vertical flex-column wmin-md-200 mb-md-0 border-bottom-0">
					<li class="nav-item"><a href="#tab-profile" class="nav-link active" data-toggle="tab"><i class="icon-vcard mr-2"></i> Account & Sign</a></li>
					<li class="nav-item"><a href="#tab-family" class="nav-link" data-toggle="tab"><i class="icon-collaboration mr-2"></i> Family</a></li>
					<li class="nav-item"><a href="#tab-education" class="nav-link" data-toggle="tab"><i class="icon-graduation mr-2"></i> Education</a></li>
					<li class="nav-item"><a href="#tab-experience" class="nav-link" data-toggle="tab"><i class="icon-user-tie mr-2"></i> Work Experience</a></li>
					<li class="nav-item"><a href="#tab-employment" class="nav-link" data-toggle="tab"><i class="icon-portfolio mr-2"></i> Employment</a></li>
				  </ul>
                  <ul class="nav nav-sidebar">
                     <li class="nav-item-header text-center">
                        Verification on {{ date('d F Y', strtotime($user->verification)) }}
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <div class="col-md-9">
            <div class="card">
               <div class="card-body">
				<div class="tab-content">
					<div class="tab-pane fade show active" id="tab-profile">
						<h3>Account</h3>
						<hr>
						 @if($errors->any())
							 <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								<ul>
								   @foreach ($errors->all() as $error)
									  <li>{{ $error }}</li>
								   @endforeach
								</ul>
							 </div>
						  @elseif(session('success'))
							 <div class="alert bg-success text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								{{ session('success') }}
							 </div>
						  @elseif(session('failed'))
							 <div class="alert bg-danger text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								{{ session('failed') }}
							 </div>
						  @endif
						  <form action="{{ url('admin/profile') }}" method="POST" enctype="multipart/form-data">
							 @csrf
							 <div class="form-group">
								<label>Photo :<sup class="text-danger">*</sup> Recommend 1:1 / square picture.</label>
								<input type="file" name="photo" id="photo" class="form-control h-auto">
							 </div>
							 <div class="form-group">
								<label>Name :<sup class="text-danger">*</sup></label>
								<input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" placeholder="Enter name">
							 </div>
							 <div class="form-group">
								<label>HP :<sup class="text-danger">*</sup></label>
								<input type="text" name="hp" id="hp" class="form-control" value="{{ $user->phone }}" placeholder="Enter hp/phone">
							 </div>
							 <div class="form-group">
								<label>Email :<sup class="text-danger">*</sup></label>
								<input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" placeholder="Enter email">
							 </div>
							 <div class="form-group">
								<div class="form-check">
								   <label class="form-check-label">
									  <input type="checkbox" name="change_password" id="change_password" class="form-check-input-styled-success" data-toggle="collapse" data-target="#collapse-link" {{ old('change_password') ? 'checked' : '' }}>
									  <span class="text-muted" for="change_password">With Change Password</span>
								   </label>
								</div>
							 </div>
							 <div class="collapse {{ old('change_password') ? 'show' : '' }}" id="collapse-link">
										<div class="form-group">
								   <label>New Password :<sup class="text-danger">*</sup></label>
								   <input type="password" name="password" id="password" class="form-control" placeholder="Enter password">
								</div>
								<div class="form-group">
								   <label>Password Confirm :<sup class="text-danger">*</sup></label>
								   <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Enter confirmation password">
								</div>
									</div>
							 <div class="form-group">
								<div class="text-right">
								   <button type="submit" class="btn bg-success">Update</button>
								</div>
							 </div>
						  </form>
						<h3>Sign</h3>
						<hr>
						<div class="row justify-content-center">
							<div class="col-md-4">
								<form id="form_data">
								   <div class="alert alert-danger" id="validation_alert" style="display:none;">
									  <ul id="validation_content"></ul>
								   </div>
								   <h3 class="text-center">UPLOAD</h3>
								   <div class="form-group">
										<label>File :</label>
										<input type="file" name="file" id="file" class="form-control h-auto">
								   </div>
								   <div class="form-group">
									<hr>
								   </div>
								   <div class="form-group text-center">
									  <h3>OR</h3>
								   </div>
								   <div class="form-group">
									<hr>
								   </div>
								   <div class="form-group">
									  <label>Sign :</label>
									  <canvas id="signature-pad" class="signature-pad"></canvas>
									  <center class="mt-3">
										 <a href="{{ $user->sign ? asset(Storage::url($user->sign)) : asset("website/empty.jpg") }}" id="preview_image" data-lightbox="Brand" data-title="Preview Image">
											<img src="{{ $user->sign ? asset(Storage::url($user->sign)) : asset("website/empty.jpg") }}" class="img-fluid img-thumbnail w-100" style="max-width:200px;">
										 </a>
									  </center>
								   </div>
								   <div class="form-group text-center">
										<button type="button" class="btn btn-warning" id="undo"><i class="fa fa-undo"></i> Undo</button>&nbsp;
										<button type="button" class="btn btn-danger" id="clear"><i class="fa fa-eraser"></i> Clear</button>&nbsp;
										<button type="button" class="btn bg-primary" id="btn_create" onclick="create()">Save</button>
								   </div>
								</form>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="tab-family">
						<h3>Family</h3>
						<hr>
						<div class="table-responsive">
						   <table id="datatable_serverside_family" class="table table-bordered table-striped w-100">
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
					<div class="tab-pane fade" id="tab-education">
						<h3>Education</h3>
						<hr>
						<div class="table-responsive">
						   <table id="datatable_serverside_education" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								<tr class="text-center">
									<th>#</th>
									<th>Grade</th>
									<th>Institution</th>
									<th>Majors</th>
									<th>Score</th>
									<th>Period</th>
									<th>Operation</th>
								</tr>
							  </thead>
						   </table>
						</div>
					</div>
					<div class="tab-pane fade" id="tab-experience">
						<h3>Experience</h3>
						<hr>
						<div class="table-responsive">
						   <table id="datatable_serverside_experience" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								<tr class="text-center">
									<th>#</th>
									<th>Company</th>
									<th>Position</th>
									<th>From</th>
									<th>To</th>
									<th>Operation</th>
								</tr>
							  </thead>
						   </table>
						</div>
					</div>
					<div class="tab-pane fade" id="tab-employment">
						<h3>Employment</h3>
						<hr>
						<div class="table-responsive">
						   <table id="datatable_serverside_employment" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								<tr class="text-center">
									<th>#</th>
									<th>Employee No.</th>
									<th>Document No.</th>
									<th>Status</th>
									<th>Branch</th>
									<th>Start</th>
									<th>End</th>
									<th>Doc./Proof</th>
									<th>Active</th>
									<th>Resign</th>
								</tr>
							  </thead>
						   </table>
						</div>
					</div>
				</div>
               </div>
            </div>
         </div>
      </div>
	</div>
	<script>
	   
	   var canvas = document.getElementById('signature-pad');
	   
		function resizeCanvas() {
			var parentWidth = $(canvas).parent().outerWidth();
			canvas.setAttribute("width", parentWidth);
			canvas.style.background = "white";
			this.signaturePad = new SignaturePad(canvas);
		}

		window.onresize = resizeCanvas;
		resizeCanvas();

		var signaturePad = new SignaturePad(canvas, {
		  backgroundColor: 'rgb(255, 255, 255)'
		});
		
		document.getElementById('clear').addEventListener('click', function () {
		  signaturePad.clear();
		});

		document.getElementById('undo').addEventListener('click', function () {
			var data = signaturePad.toData();
		  if (data) {
			data.pop(); // remove the last dot or line
			signaturePad.fromData(data);
		  }
		});
		
		function create() {
			
			if(signaturePad.isEmpty()){
				var fd = new FormData();
				var files = $('#file')[0].files;
				if(files.length > 0 ){
					fd.append('file',files[0]);
					
					$.ajax({
					  url: "{{ url('admin/profile/uploadSign') }}",
					  type: 'POST',
					  data: fd,
					  contentType: false,
					  processData: false,
					  headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					  },
					  beforeSend: function() {
						$('#validation_alert').hide();
						$('#validation_content').html('');
						loadingOpen('#form_data');
					  },
					  success: function(response){
						loadingClose('#form_data');
						if(response.status == 200) {
						   notif('success', 'bg-success', response.message);
						   setTimeout(function(){ location.reload() }, 1500);
						} else if(response.status == 422) {
						   $('#validation_alert').show();
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
						loadingClose('#form_data');
						swalInit.fire({
						   title: 'Server Error',
						   text: 'Please contact developer',
						   type: 'error'
						});
					  }
				   });
				}
			}else{
				var signdata = signaturePad.toDataURL('image/png');
				
				$.ajax({
				 url: "{{ url('admin/profile/uploadSign') }}",
				 type: 'POST',
				 dataType: 'JSON',
				 data: {signdata:signdata},
				 cache: true,
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					$('#validation_alert').hide();
					$('#validation_content').html('');
					loadingOpen('#form_data');
				 },
				 success: function(response) {
					loadingClose('#form_data');
					if(response.status == 200) {
					   notif('success', 'bg-success', response.message);
					   setTimeout(function(){ location.reload() }, 1500);
					} else if(response.status == 422) {
					   $('#validation_alert').show();
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
					loadingClose('#form_data');
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
				 }
				});
			}
	   }
	   
	   $(function() {
			loadDataTableFamily();
			loadDataTableEducation();
			loadDataTableExperience();
			loadDataTableEmployment();
	   });
	   
	   function loadDataTableFamily() {
		  return $('#datatable_serverside_family').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'asc']],
			 ajax: {
				url: '{{ url("admin/hrd/employee/family/".(session('bo_id'))."/datatable") }}',
				type: 'GET',
				beforeSend: function() {
				   loadingOpen('#datatable_serverside_family');
				},
				complete: function() {
				   loadingClose('#datatable_serverside_family');
				},
				error: function() {
				   loadingClose('#datatable_serverside_family');
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
		
		function loadDataTableEducation() {
		  return $('#datatable_serverside_education').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'asc']],
			 ajax: {
				url: '{{ url("admin/hrd/employee/education/".(session('bo_id'))."/datatable") }}',
				type: 'GET',
				beforeSend: function() {
				   loadingOpen('#datatable_serverside_education');
				},
				complete: function() {
				   loadingClose('#datatable_serverside_education');
				},
				error: function() {
				   loadingClose('#datatable_serverside_education');
				   swalInit.fire({
					  title: 'Server Error',
					  text: 'Please contact developer',
					  type: 'error'
				   });
				}
			 },
			 columns: [
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'grade', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'institution', className: 'text-center align-middle' },
				{ name: 'majors', className: 'text-center align-middle' },
				{ name: 'score', className: 'text-center align-middle' },
				{ name: 'period', className: 'text-center align-middle' },
				{ name: 'operation', orderable: false, searchable: false, className: 'text-center align-middle' },
			 ]
		  });
		}
		
		function loadDataTableExperience() {
		  return $('#datatable_serverside_experience').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'asc']],
			 ajax: {
				url: '{{ url("admin/hrd/employee/experience/".(session('bo_id'))."/datatable") }}',
				type: 'GET',
				beforeSend: function() {
				   loadingOpen('#datatable_serverside_experience');
				},
				complete: function() {
				   loadingClose('#datatable_serverside_experience');
				},
				error: function() {
				   loadingClose('#datatable_serverside_experience');
				   swalInit.fire({
					  title: 'Server Error',
					  text: 'Please contact developer',
					  type: 'error'
				   });
				}
			 },
			 columns: [
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'company', className: 'text-center align-middle' },
				{ name: 'position', className: 'text-center align-middle' },
				{ name: 'from', className: 'text-center align-middle' },
				{ name: 'to', className: 'text-center align-middle' },
				{ name: 'operation', orderable: false, searchable: false, className: 'text-center align-middle' },
			 ]
		  });
		}
		
		function loadDataTableEmployment() {
		  return $('#datatable_serverside_employment').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'asc']],
			 ajax: {
				url: '{{ url("admin/hrd/employee/employment/".(session('bo_id'))."/datatable") }}',
				type: 'GET',
				beforeSend: function() {
				   loadingOpen('#datatable_serverside_employment');
				},
				complete: function() {
				   loadingClose('#datatable_serverside_employment');
				},
				error: function() {
				   loadingClose('#datatable_serverside_employment');
				   swalInit.fire({
					  title: 'Server Error',
					  text: 'Please contact developer',
					  type: 'error'
				   });
				}
			 },
			 columns: [
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'employee_no', className: 'text-center align-middle' },
				{ name: 'document_no', className: 'text-center align-middle' },
				{ name: 'status', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'branch', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'start_date', className: 'text-center align-middle' },
				{ name: 'end_date', className: 'text-center align-middle' },
				{ name: 'image', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'active', orderable: false, searchable: false, className: 'text-center align-middle' },
				{ name: 'resign_date', className: 'text-center align-middle' },
			 ]
		  });
		}
	</script>