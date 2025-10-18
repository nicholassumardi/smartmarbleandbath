<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Work Experience Data {{ $name }}</span>
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
					<span class="breadcrumb-item active">Work Experience - {{ $name }}</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Experience</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
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
								<label class="col-lg-3 col-form-label">Company :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="hidden" name="temp" id="temp">
									<input type="hidden" name="tempUser" id="tempUser" value="{{ $id }}">
									<input type="text" class="form-control" name="company" id="company" placeholder="PT.FIFA...">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Position :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="position" id="position" placeholder="Purchasing...">
								</div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Start From :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<input type="month" class="form-control" name="month_start" id="month_start">
							  </div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Until :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<input type="month" class="form-control" name="month_end" id="month_end">
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
				  url: '{{ url("admin/hrd/employee/experience/destroy") }}',
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
            url: '{{ url("admin/hrd/employee/experience/".$id."/datatable") }}',
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
            { name: 'company', className: 'text-center align-middle' },
            { name: 'position', className: 'text-center align-middle' },
            { name: 'from', className: 'text-center align-middle' },
			{ name: 'to', className: 'text-center align-middle' },
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
		 url: '{{ url("admin/hrd/employee/experience/create") }}',
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
		 url: '{{ url("admin/hrd/employee/experience/show") }}',
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
			$('#company').val(response.company);
			$('#position').val(response.position);
			$('#month_start').val(response.month_start);
			$('#month_end').val(response.month_end);
			
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