<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Employment Data {{ $name }}</span>
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
					<span class="breadcrumb-item active">Employment - {{ $name }}</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Employment History</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Employment Data</h5>
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
								<label class="col-lg-3 col-form-label">Employee No.<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="hidden" name="temp" id="temp">
									<input type="hidden" name="tempUser" id="tempUser" value="{{ $id }}">
									<input type="text" class="form-control" name="employee_no" id="employee_no" placeholder="Employee Number...">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Document No.<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="document_no" id="document_no" placeholder="Document Number...">
								</div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Status :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<select name="status" id="status" class="custom-select">
										<option value="1">Permanent</option>
										<option value="2">Contract</option>
										<option value="3">Probation</option>
									</select>
									
							  </div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Branch :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<select name="branch" id="branch" class="custom-select">
										@foreach (DB::table('company_entities')->get() as $company)
											<option value="{{$company->id}}">{{$company->name}}</option>
										@endforeach
									</select>
									
							  </div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Proof's File</label>
								<div class="col-lg-9">
									<input type="file" class="form-input-styled" id="file" name="file">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Start Date<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="date" class="form-control" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">End Date<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="date" class="form-control" name="end_date" id="end_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3">Active / Resign</label>
								<div class="col-lg-9">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" value="1" name="active" id="active" checked style="transform: scale(1.25);">
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Resign Date</label>
								<div class="col-lg-9">
									<input type="date" class="form-control" name="resign_date" id="resign_date">
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
				  url: '{{ url("admin/hrd/employee/employment/destroy") }}',
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
            url: '{{ url("admin/hrd/employee/employment/".$id."/datatable") }}',
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
            { name: 'employee_no', className: 'text-center align-middle' },
            { name: 'document_no', className: 'text-center align-middle' },
            { name: 'status', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'branch', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'start_date', className: 'text-center align-middle' },
			{ name: 'end_date', className: 'text-center align-middle' },
			{ name: 'image', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'active', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'resign_date', className: 'text-center align-middle' },
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
		 url: '{{ url("admin/hrd/employee/employment/create") }}',
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
		 url: '{{ url("admin/hrd/employee/employment/show") }}',
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
			$('#employee_no').val(response.employee_no);
			$('#document_no').val(response.document_no);
			$('#status').val(response.status);
			$('#branch').val(response.branch);
			$('#start_date').val(response.start_date);
			$('#end_date').val(response.end_date);
			
			if(response.active == '1'){
				$('#active').prop('checked', true);
			}else{
				$('#active').prop('checked', false);
			}
			
			$('#resign_date').val(response.resign_date);
			
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