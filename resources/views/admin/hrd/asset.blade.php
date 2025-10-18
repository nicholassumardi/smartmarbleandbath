<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Employee Asset {{ $name }}</span>
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
					<span class="breadcrumb-item active">Asset - {{ $name }}</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Asset</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						<tr class="text-center">
							<th>#</th>
							<th>Name</th>
							<th>Date Borrow</th>
							<th>Date Return</th>
							<th>Status</th>
							<th>Note</th>
							<th>Note Return</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Employee Asset</h5>
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
								<label class="col-lg-3 col-form-label">Asset :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="hidden" name="temp" id="temp">
									<input type="hidden" name="tempUser" id="tempUser" value="{{ $id }}">
									<select name="asset_id" id="asset_id" class="select2">
									   @foreach($asset as $a)
											<option value="{{ $a->id }}">{{ $a->name }}</option>
									   @endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Date Borrow :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<input type="date" class="form-control" name="date_borrow" id="date_borrow">
							  </div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Note<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<textarea class="form-control" name="note" id="note" rows="3">-</textarea>
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
	
	<div class="modal fade" id="modal_retur" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Asset Return</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row justify-content-center">
					<div class="col-md-8">
						<form id="form_data_return">
							<div class="alert alert-danger" id="validation_alert_return" style="display:none;">
								<ul id="validation_content_return"></ul>
						    </div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Date Return :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="hidden" name="tempReturn" id="tempReturn">
									<input type="hidden" name="tempUserReturn" id="tempUserReturn" value="{{ $id }}">
									<input type="date" class="form-control" name="date_return" id="date_return">
								</div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Note Return<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<textarea class="form-control" name="note_return" id="note_return" rows="3">-</textarea>
							  </div>
							</div>
						</form>
					</div>
				</div>
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="createReturn()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
<script>
	$(function() {
		loadDataTable();
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#temp').val('');
			$('#asset_id').val($("#asset_id option:first").val()).trigger('change');
			$('#form_data').trigger('reset');
		});
		
		$('#modal_retur').on('hidden.bs.modal', function (e) {
			$('#tempReturn').val('');
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
				  url: '{{ url("admin/hrd/employee/asset/destroy") }}',
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
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/hrd/employee/asset/".$id."/datatable") }}',
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
            { name: 'name', orderable: false, className: 'text-center align-middle' },
            { name: 'borrow', className: 'text-center align-middle' },
            { name: 'return', className: 'text-center align-middle' },
			{ name: 'status', searchable: false, className: 'text-center align-middle' },
			{ name: 'note', className: 'text-center align-middle' },
			{ name: 'note_return', className: 'text-center align-middle' },
			{ name: 'operation', orderable: false, searchable: false, className: 'text-center align-middle' },
         ]
      });
	}
   
	function success() {
	  $('#modal_form').modal('hide');
	  $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function successReturn() {
	  $('#modal_retur').modal('hide');
	  $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function dateReturn(id) {
		$('#tempReturn').val(id);
		$('#modal_retur').modal('show');
	}
   
	function create() {
	  $.ajax({
		 url: '{{ url("admin/hrd/employee/asset/create") }}',
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
	
	function createReturn() {
	  $.ajax({
		 url: '{{ url("admin/hrd/employee/asset/update_return") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_return')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_return').hide();
			$('#validation_content_return').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
			   successReturn();
			   notif('success', 'bg-success', response.message);
			} else if(response.status == 422) {
			   $('#validation_alert_return').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_return').append(`
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
		 url: '{{ url("admin/hrd/employee/asset/show") }}',
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
			$('#asset_id').val(response.asset_id).trigger('change');
			$('#date_borrow').val(response.date_borrow);
			$('#note').val(response.note);
			
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