<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Personal Leave Request (Under Maintenance)</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
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
					<span class="breadcrumb-item active">Personal Leave Request</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Leave Request</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						<tr class="text-center">
							<th>#</th>
							<th>Type</th>
							<th>Category</th>
							<th>Note</th>
							<th>Period (day)</th>
							<th>Period (hour)</th>
							<th>Proof</th>
							<th>Approved By</th>
							<th>Checked By</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add New Leave Request</h5>
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
						<div class="col-md-3">
							<div class="form-group">
								<label>Category :<sup class="text-danger">*</sup></label>
								<input type="hidden" name="leave_id" id="leave_id" class="form-control">
								<select name="type_leave" id="type_leave" class="form-control">
								   @foreach($type as $tp)
									  <option value="{{ $tp->id }}">{{ $tp->name.' - '.$tp->type() }}</option>
								   @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Type :<sup class="text-danger">*</sup></label>
								<select name="category" id="category" class="form-control" onchange="changeDateTime()">
									<option value="1">Days</option>
									<option value="2">Hours</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Proof :</label>
								<input type="file" class="form-control" id="proof" name="proof" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Note/Description :<sup class="text-danger">*</sup></label>
								<textarea name="note" id="note" class="form-control" placeholder="Type your information" rows="1"></textarea>
							</div>
						</div>
					</div>
					<hr>
					<div class="row justify-content-center">
						<div class="col-md-4 date-div">
							<div class="form-group">
								<label>Date :</label>
								<div class="input-group-prepend">
									<input type="date" name="start_date" id="start_date" class="form-control">
									<span class="input-group-text">to</span>
									<input type="date" name="finish_date" id="finish_date" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-2 d-none time-div">
							<div class="form-group">
								<label>Date :</label>
								<input type="date" name="date_hour" id="date_hour" class="form-control">
							</div>
						</div>
						<div class="col-md-4 d-none time-div">
							<div class="form-group">
								<label>Hour :</label>
								<div class="input-group-prepend">
									<input type="time" name="start_hour" id="start_hour" class="form-control">
									<span class="input-group-text">to</span>
									<input type="time" name="finish_hour" id="finish_hour" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</form>
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
			$('#form_data')[0].reset();
			$('#leave_id').val('');
		});
	});
	
	function changeDateTime(){
		if($('#category').val() == '1'){
			$('.date-div').removeClass('d-none');
			$('.time-div').addClass('d-none');
		}else if($('#category').val() == '2'){
			$('.date-div').addClass('d-none');
			$('.time-div').removeClass('d-none');
		}
	}
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/leave_request/user_datatable") }}',
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
			{ name: 'id', searchable: false, className: 'text-center align-middle details-control' },
			{ name: 'type_leave', orderable: false, className: 'text-center align-middle' },
			{ name: 'category', orderable: false, className: 'text-center align-middle' },
			{ name: 'note', className: 'text-center align-middle' },
			{ name: 'from_date', className: 'text-center align-middle' },
			{ name: 'from_hour', className: 'text-center align-middle' },
            { name: 'proof', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'approved', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'checked', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'action', searchable: false, orderable: false, className: 'text-center align-middle' }
		]
      }); 
	}
	
	function create(){
		$.ajax({
			 url: '{{ url("admin/leave_request/create") }}',
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
				   $('#form_data')[0].reset();
				   $('#modal_form').modal('toggle');
				   loadDataTable();
				   notif('success', 'bg-success', 'Data saved successfully!');
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
	
	function toShow() {
      $('#modal_form').modal('show');
      $('#validation_alert').hide();
      $('#validation_content').html('');
	}
	
	function show(id) {
      toShow();
	  $('#leave_id').val(id);
      $.ajax({
         url: '{{ url("admin/leave_request/get_leave") }}',
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
			
            $('#leave_id').val(id);
            $('#type_leave').val(response.type_leave_id);
            $('#category').val(response.category).trigger('change');
			$('#note').val(response.note);
			$('#due_date').val(response.due_date);
			$('#start_date').val(response.start_date);
			$('#finish_date').val(response.finish_date);
			$('#date_hour').val(response.date_hour);
			$('#start_hour').val(response.start_hour);
			$('#finish_hour').val(response.finish_hour);
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
				  url: '{{ url("admin/leave_request/userDestroy") }}',
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