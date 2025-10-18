<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Time Management</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<span class="breadcrumb-item active">Time Management</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Data</h5>
			</div>
			<div class="card-body">
				<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
					<li class="nav-item"><a href="#schedule-tab" class="nav-link active" data-toggle="tab">Schedule</a></li>
					<li class="nav-item"><a href="#holiday-tab" class="nav-link" data-toggle="tab">Holiday</a></li>
					<li class="nav-item"><a href="#company-tab" class="nav-link" data-toggle="tab">Company</a></li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade show active" id="schedule-tab">
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_schedule">
							<b><i class="icon-plus3"></i></b> Add Schedule
						</button>
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside_schedule" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>No</th>
									<th>Branch</th>
									<th>Day</th>
									<th>In</th>
									<th>Out</th>
									<th>Operation</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
					<div class="tab-pane fade" id="holiday-tab">
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Info!</span> Only for national or company holiday, do not add dayoff as it is already in schedule form. The date saved here will override all employee attendance on the same date and will be considered as holiday.
						</div>
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_holiday">
							<b><i class="icon-plus3"></i></b> Add Holiday
						</button>
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside_holiday" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>No</th>
									<th>Date</th>
									<th>Branch</th>
									<th>Description</th>
									<th>Operation</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
					<div class="tab-pane fade" id="company-tab">
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Info!</span> Only for national or company holiday, do not add dayoff as it is already in schedule form. The date saved here will override all employee attendance on the same date and will be considered as holiday.
						</div>
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_company">
							<b><i class="icon-plus3"></i></b> Add Company
						</button>
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside_company" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>No</th>
									<th>Branch</th>
									<th>Operation</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form_schedule" data-backdrop="static" role="dialog">
	   <div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Schedule</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_schedule">
				   <div class="alert alert-danger" id="validation_alert_schedule" style="display:none;">
					  <ul id="validation_content_schedule"></ul>
				   </div>
				   <div class="row">
						<div class="col-md-3">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <input type="hidden" id="tempSchedule" name="tempSchedule">
							  <select name="branch" id="branch" class="form-control">
								@foreach (DB::table('company_entities')->get() as $company)
									<option value="{{$company->id}}">{{$company->name}}</option>
								@endforeach
							  </select>
						   </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>Day :<span class="text-danger">*</span></label>
							  <select name="day" id="day" class="form-control">
								 <option value="1">Monday</option>
								 <option value="2">Tuesday</option>
								 <option value="3">Wednesday</option>
								 <option value="4">Thursday</option>
								 <option value="5">Friday</option>
								 <option value="6">Saturday</option>
								 <option value="7">Sunday</option>
							  </select>
						   </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>In time :<span class="text-danger">*</span></label>
							  <input class="form-control" type="time" name="in_time" id="in_time">
						   </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>Out time :<span class="text-danger">*</span></label>
							  <input class="form-control" type="time" name="out_time" id="out_time">
						   </div>
						</div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_schedule()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form_holiday" data-backdrop="static" role="dialog">
	   <div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form National & Company Holiday</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_holiday">
				   <div class="alert alert-danger" id="validation_alert_holiday" style="display:none;">
					  <ul id="validation_content_holiday"></ul>
				   </div>
				   <div class="row">
						<div class="col-md-3">
							<div class="form-group">
							  <label>Date :<span class="text-danger">*</span></label>
							  <input type="hidden" id="tempHoliday" name="tempHoliday">
							  <input class="form-control" type="date" name="date" id="date">
						   </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <select name="holiday_branch" id="holiday_branch" class="form-control">
								@foreach (DB::table('company_entities')->get() as $company)
									<option value="{{$company->id}}">{{$company->name}}</option>
								@endforeach
							  </select>
						   </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>Description :<span class="text-danger">*</span></label>
							  <textarea class="form-control" name="description" id="description" rows="1"></textarea>
						   </div>
						</div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_holiday()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>

	<div class="modal fade" id="modal_form_company" data-backdrop="static" role="dialog">
	   <div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Company</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_holiday">
				   <div class="alert alert-danger" id="validation_alert_holiday" style="display:none;">
					  <ul id="validation_content_holiday"></ul>
				   </div>
				   <div class="row">
						<div class="col-md-3">
							<div class="form-group">
							  <label>Name :<span class="text-danger">*</span></label>
							  <input type="hidden" id="tempCompany" name="tempCompany">
							  <input class="form-control" type="text" name="company_name" id="company_name">
						   </div>
						</div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_company()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<script>
		$(function() {
			loadDataTableSchedule();
			loadDataTableHoliday();
			loadDataTableCompany();
			
			$('#modal_form_schedule').on('hidden.bs.modal', function (e) {
				$('#form_data_schedule').trigger('reset');
				$('#tempSchedule').val('');
				loadDataTableSchedule();
			});
			
			$('#modal_form_holiday').on('hidden.bs.modal', function (e) {
				$('#form_data_holiday').trigger('reset');
				$('#tempHoliday').val('');
				loadDataTableHoliday();
			});

			$('#modal_form_company').on('hidden.bs.modal', function (e) {
				$('#form_data_company').trigger('reset');
				$('#tempCompany').val('');
				loadDataTableCompany();
			});
		});
		
		function create_schedule(){
		  $.ajax({
			 url: '{{ url("admin/master_data/hrd/time_management/create_schedule") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data_schedule')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_schedule').hide();
				$('#validation_content_schedule').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   $('#modal_form_schedule').modal('toggle');
				   notif('success', 'bg-success', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_schedule').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_schedule').append(`
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
		
		function create_holiday(){
		  $.ajax({
			 url: '{{ url("admin/master_data/hrd/time_management/create_holiday") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data_holiday')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_holiday').hide();
				$('#validation_content_holiday').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   $('#modal_form_holiday').modal('toggle');
				   notif('success', 'bg-success', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_holiday').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_holiday').append(`
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

		function create_company(){
		  $.ajax({
			 url: '{{ url("admin/master_data/hrd/time_management/create_company") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data_company')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_holiday').hide();
				$('#validation_content_holiday').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   $('#modal_form_holiday').modal('toggle');
				   notif('success', 'bg-success', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_holiday').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_holiday').append(`
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
		
		function loadDataTableSchedule() {
		  $('#datatable_serverside_schedule').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[0, 'asc']],
			 ajax: {
				url: '{{ url("admin/master_data/hrd/time_management/datatable_schedule") }}',
				type: 'GET',
				beforeSend: function() {
				   loadingOpen('#datatable_serverside_schedule');
				},
				complete: function() {
				   loadingClose('#datatable_serverside_schedule');
				},
				error: function() {
				   loadingClose('#datatable_serverside_schedule');
				   swalInit.fire({
					  title: 'Server Error',
					  text: 'Please contact developer',
					  type: 'error'
				   });
				}
			 },
			 columns: [
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'branch', searchable: false, className: 'text-center align-middle' },
				{ name: 'day', searchable: false, className: 'text-center align-middle' },
				{ name: 'in', className: 'text-center align-middle' },
				{ name: 'out', className: 'text-center align-middle' },
				{ name: 'operation', searchable: false, orderable: false, className: 'text-center align-middle' },
			 ]
		  }); 
	   }
	   
	   function loadDataTableHoliday() {
		  $('#datatable_serverside_holiday').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[0, 'asc']],
			 ajax: {
				url: '{{ url("admin/master_data/hrd/time_management/datatable_holiday") }}',
				type: 'GET',
				beforeSend: function() {
				   loadingOpen('#datatable_serverside_holiday');
				},
				complete: function() {
				   loadingClose('#datatable_serverside_holiday');
				},
				error: function() {
				   loadingClose('#datatable_serverside_holiday');
				   swalInit.fire({
					  title: 'Server Error',
					  text: 'Please contact developer',
					  type: 'error'
				   });
				}
			 },
			 columns: [
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'date', className: 'text-center align-middle' },
				{ name: 'branch', searchable: false, className: 'text-center align-middle' },
				{ name: 'description', className: 'text-center align-middle' },
				{ name: 'operation', searchable: false, orderable: false, className: 'text-center align-middle' },
			 ]
		  }); 
	   }

	   function loadDataTableCompany() {
		  $('#datatable_serverside_company').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[0, 'asc']],
			 ajax: {
				url: '{{ url("admin/master_data/hrd/time_management/datatable_company") }}',
				type: 'GET',
				beforeSend: function() {
				   loadingOpen('#datatable_serverside_company');
				},
				complete: function() {
				   loadingClose('#datatable_serverside_company');
				},
				error: function() {
				   loadingClose('#datatable_serverside_company');
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
				{ name: 'operation', searchable: false, orderable: false, className: 'text-center align-middle' },
			 ]
		  }); 
	   }
	   
	   function showSchedule(id) {
		  $.ajax({
			 url: '{{ url("admin/master_data/hrd/time_management/show_schedule") }}',
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
				$('#tempSchedule').val(id);
				$('#branch').val(response.branch);
				$('#day').val(response.day);
				$('#in_time').val(response.in_time);
				$('#out_time').val(response.out_time);
				$('#modal_form_schedule').modal('toggle');
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
		
		function showHoliday(id) {
		  $.ajax({
			 url: '{{ url("admin/master_data/hrd/time_management/show_holiday") }}',
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
				$('#tempHoliday').val(id);
				$('#holiday_branch').val(response.branch);
				$('#description').val(response.description);
				$('#date').val(response.in_time);
				$('#modal_form_holiday').modal('toggle');
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

		function showCompany(id) {
		  $.ajax({
			 url: '{{ url("admin/master_data/hrd/time_management/show_company") }}',
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
				$('#tempCompany').val(id);
				$('#company_name').val(response.name);
				$('#modal_form_company').modal('toggle');
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
		
		function destroySchedule(id) {
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
					  url: '{{ url("admin/master_data/hrd/time_management/destroy_schedule") }}',
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
							$('#datatable_serverside_schedule').DataTable().ajax.reload(null, false);
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
		
		function destroyHoliday(id) {
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
					  url: '{{ url("admin/master_data/hrd/time_management/destroy_holiday") }}',
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
							$('#datatable_serverside_holiday').DataTable().ajax.reload(null, false);
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

		function destroyCompany(id) {
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
					  url: '{{ url("admin/master_data/hrd/time_management/destroy_company") }}',
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
							$('#datatable_serverside_company').DataTable().ajax.reload(null, false);
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