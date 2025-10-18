<div class="content-wrapper">
	<!-- Page header -->
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Attendance</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn btn-sm bg-success btn-labeled mr-2 btn-labeled-left"
						onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left mr-2" data-toggle="modal"
						data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add Manual Attendance
					</button>
					<button type="button" class="btn bg-warning btn-labeled btn-labeled-left" data-toggle="modal"
						data-target="#modal_rule">
						<b><i class="icon-alarm-check"></i></b> Time Rule Correction
					</button>
				</div>
			</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<span class="breadcrumb-item active">Attendance</span>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">

			</div>
		</div>
	</div>
	<!-- /page header -->
	<div class="content">
		<!-- Main charts -->
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Employee Attendance</h3>
						<div class="header-elements">

						</div>
					</div>
					<div class="card-body py-0">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group" style="margin-bottom: 0rem !important;">
									<label>Month :</label>
									<div class="input-group-prepend">
										<input type="month" name="filter_month" id="filter_month" class="form-control">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group" style="margin-bottom: 0rem !important;">
									<label>&nbsp;</label>
									<select name="filter_branch" id="filter_branch" class="custom-select">
										<option value="">All Branch</option>
										@foreach (DB::table('company_entities')->get() as $company)
											<option value="{{$company->id}}">{{$company->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group" style="margin-bottom: 0rem !important;">
									<label>&nbsp;</label>
									<select name="filter_user_id" id="filter_user_id" class="select2">
										<option value="">All Employee/User</option>
										@foreach($user as $u)
										<option value="{{ $u->id }}">{{ $u->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>&nbsp;</label>
									<div class="input-group-prepend">
										<button type="button" onclick="filter()" class="btn bg-purple mr-2 btn-block"><i
												class="icon-filter4"></i> Search</button>
										<button type="button" onclick="filter('reset')" class="btn bg-danger mr-2"><i
												class="icon-sync"></i></button>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<button type="button" class="btn bg-success btn-block" onclick="printMonthBranch()"><i
										class="icon-printer2 mr-1"></i> Per Branch</button>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn bg-info btn-block" onclick="printMonthEmployee()"><i
										class="icon-printer2 mr-1"></i> Per Employee</button>
							</div>
						</div>
						<div class="table-responsive mt-3">
							<table id="datatable_serverside" class="table table-bordered table-striped w-100">
								<thead class="bg-dark">
									<tr class="text-center">
										<th rowspan="2">#</th>
										<th rowspan="2">No</th>
										<th rowspan="2">Employee</th>
										<th rowspan="2">Date</th>
										<th colspan="3">IN</th>
										<th colspan="3">OUT</th>
									</tr>
									<tr class="text-center">
										<th>Time</th>
										<th>Code</th>
										<th>Proof</th>
										<th>Time</th>
										<th>Code</th>
										<th>Proof</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Form Add New Manual Attendance</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form_data">
						<div class="alert alert-danger" id="validation_alert" style="display:none;">
							<ul id="validation_content"></ul>
						</div>
						<h5 class="card-title text-center"><b>Main Information</b></h5>
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
									<span class="font-weight-semibold">Important Info!</span>
									&nbsp;<b>This manual attendance will override existing data check in and/or check
										out for selected date. Leave blank if you doesn't want to override the time.</b>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Employee :<sup class="text-danger">*</sup></label>
									<input type="hidden" name="temp" id="temp">
									<select name="employee_id" id="employee_id" class="select2">
										@foreach($user as $u)
										<option value="{{ $u->id }}">{{ $u->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>From :<sup class="text-danger">*</sup></label>
									<input type="date" name="date_from" id="date_from" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>To :<sup class="text-danger">*</sup></label>
									<input type="date" name="date_to" id="date_to" class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Note :<sup class="text-danger">*</sup></label>
									<input type="text" name="manual_attendance_note" id="date_to"
										class="form-control">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>&nbsp;</label>
									<button type="button" class="btn bg-success col-12" onclick="addContent()"><i
											class="icon-plus22"></i></button>
								</div>
							</div>
						</div>
						<div class="form-group">
							<hr>
						</div>
						<h5 class="card-title text-center"><b>Details Attendance</b></h5>
						<div class="row">
							<div class="form-group col-md-12">
								<table class="table table-bordered">
									<thead class="table-secondary">
										<tr class="text-center">
											<th>No</th>
											<th>Employee</th>
											<th>Date</th>
											<th>Check In</th>
											<th>Check Out</th>
											<th>Note</th>
											<th>#</th>
										</tr>
									</thead>
									<tbody id="data_content"></tbody>
								</table>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
					<button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i
							class="icon-plus3"></i> Save</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_rule" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Form Rule Correction</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form_data_rule">
						<div class="alert alert-danger" id="validation_alert_rule" style="display:none;">
							<ul id="validation_content_rule"></ul>
						</div>
						<h5 class="card-title text-center"><b>Main Information</b></h5>
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
									<span class="font-weight-semibold">Important Info!</span>
									&nbsp;<b>This manual attendance will override existing data in / out rule check out
										for selected date.</b>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Branch :<sup class="text-danger">*</sup></label>
									<select name="branch" id="branch" class="custom-select">
										@foreach (DB::table('company_entities')->get() as $company)
											<option value="{{$company->id}}">{{$company->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Type :<sup class="text-danger">*</sup></label>
									<select name="type" id="type" class="custom-select">
										<option value="IN">In</option>
										<option value="OUT">Out</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>From :<sup class="text-danger">*</sup></label>
									<input type="date" name="date_from" id="date_from" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>To :<sup class="text-danger">*</sup></label>
									<input type="date" name="date_to" id="date_to" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Rule Time :<sup class="text-danger">*</sup></label>
									<input type="time" name="time" id="time" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Note :<sup class="text-danger">*</sup></label>
									<input type="text" name="time_correction_note" id="time_correction_note"
										class="form-control">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
					<button type="button" class="btn bg-primary" id="btn_create" onclick="createRule()"><i
							class="icon-plus3"></i> Save</button>
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
			
			$('#data_content').on('click', '#delete_data_content', function() {
				 $(this).closest('tr').remove();
			});
			
			$('#modal_rule').on('hidden.bs.modal', function (e) {
				$('#form_data_rule')[0].reset();
				loadDataTable();
			});
		});
		
		function addContent(){
			if($('#date_from').val() && $('#date_to').val()){
				
				const start = new Date($('#date_from').val());
				const end = new Date($('#date_to').val());
				let loop = new Date(start);
				var no = $('.row-attendance').length + 1;
				while (loop <= end) {				
				
					$('#data_content').append(`
						<tr class="text-center row-attendance">
							<input type="hidden" name="employee_attendance[]" value="` + $('#employee_id').val() + `">
							<td>` + no + `.</td>
							<td>` + $('#employee_id').select2('data')[0].text + `</td>
							<td><input type="date" name="date_attendance[]" class="form-control" value="` + loop.toISOString().split('T')[0] + `"></td>
							<td><input type="time" name="in_time_attendance[]" class="form-control"></td>
							<td><input type="time" name="out_time_attendance[]" class="form-control"></td>
							<td><input type="time" name="out_time_attendance[]" class="form-control"></td>
							<td><button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
						</tr>
					`)
					
					let newDate = loop.setDate(loop.getDate() + 1);
					loop = new Date(newDate);
					no++;
				}
			}else{
				notif('danger', 'bg-danger', 'Please choose date range.');
			}
		}
		
		function loadDataTable() {
		  window.table = $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'desc']],
			 ajax: {
				url: '{{ url("admin/hrd/attendance/datatable") }}',
				type: 'GET',
				data: {
					month 	: $('#filter_month').val(),
					user_id	: $('#filter_user_id').val(),
					branch	: $('#filter_branch').val(),
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
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'employee', className: 'text-center align-middle' },
				{ name: 'date', className: 'text-center align-middle' },
				{ name: 'in_time', className: 'text-center align-middle' },
				{ name: 'in_code', className: 'text-center align-middle' },
				{ name: 'in_image', searchable: false, orderable: false, className: 'text-center align-middle' },
				{ name: 'out_time', className: 'text-center align-middle' },
				{ name: 'out_code', className: 'text-center align-middle' },
				{ name: 'out_image', searchable: false, orderable: false, className: 'text-center align-middle' },
			 ]
		  }); 
		}
		
		function filter(param = null) {
			if(param == 'reset') {
				resetFilter();
			}

			loadDataTable();
			return false;
		}
		
		function success(){
			$('#modal_form').modal('toggle');
			$('#data_content').empty();
			$('#form_data')[0].reset();
			loadDataTable();
		}
		
		function resetFilter() {
			$('#filter_month').val(null);
			$('#filter_branch').val(null);
			$('#filter_user_id').val($("#filter_user_id option:first").val()).trigger('change');
		}
		
		function rowDetail(data) {
		  var content = '';
		  $.ajax({
			 url: '{{ url("admin/hrd/attendance/row_detail") }}',
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
		
		function printMonthBranch(){
			if($('#filter_month').val() && $('#filter_branch').val()){
				$.ajax({
					type : "POST",
					url  : "{{ url('admin/hrd/attendance/print') }}",
					data : {
						month : $('#filter_month').val(),
						branch : $('#filter_branch').val(),
						mode : 'monthbranch'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					cache: false,
					success: function(data){
						var w = window.open('about:blank');
						w.document.open();
						w.document.write(data);
						w.document.close();
					}
				});
			}else{
				notif('danger', 'bg-danger', 'Please choose month and branch!');
			}
		}
		
		function printMonthEmployee(){
			if($('#filter_month').val() && $('#filter_user_id').val()){
				$.ajax({
					type : "POST",
					url  : "{{ url('admin/hrd/attendance/print') }}",
					data : {
						month : $('#filter_month').val(),
						user_id : $('#filter_user_id').val(),
						mode : 'monthemployee'
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					cache: false,
					success: function(data){
						var w = window.open('about:blank');
						w.document.open();
						w.document.write(data);
						w.document.close();
					}
				});
			}else{
				notif('danger', 'bg-danger', 'Please choose user/employee.');
			}
		}
		
		function create() {
			if($('.row-attendance').length > 0){
				$.ajax({
				 url: '{{ url("admin/hrd/attendance/create") }}',
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
			}else{
				notif('danger', 'bg-danger', 'Data must at least has 1 row attendance.');
			}
		}
		
		function createRule() {
			$.ajax({
			 url: '{{ url("admin/hrd/attendance/create_rule") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data_rule')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_rule').hide();
				$('#validation_content_rule').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   $('#modal_rule').modal('toggle');
				   notif('success', 'bg-success', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_rule').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_rule').append(`
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
	</script>