<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">App Version</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">Setting</a>
					<span class="breadcrumb-item active">Version</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Version</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="alert alert-info alert-styled-left alert-dismissible">
					<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
					<span class="font-weight-semibold">Info!</span> The version of App will be determined by latest version name and newest released date.
				</div>
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						<tr class="text-center">
							<th width="5%">#</th>
							<th width="15%">Version</th>
							<th width="20%">Released Date</th>
							<th width="50%">Changelog</th>
							<th width="10%">Operation</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Version</h5>
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
								<label class="col-lg-3 col-form-label">Version :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="hidden" name="temp" id="temp">
									<input type="text" class="form-control" name="version" id="version" placeholder="Ex : 1.1001.0101">
								</div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Released Date :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<input type="date" class="form-control" name="released_date" id="released_date">
							  </div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Changelog<span class="text-danger">*</span></label>
								<div class="col-lg-9">
									<textarea name="changelog" id="changelog" class="form-control" rows="1" placeholder="Enter description"></textarea>
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
			ckEditor('changelog');
			loadDataTable();
			$('#modal_form').on('hidden.bs.modal', function (e) {
				$('#version,#released_date').val('');
				$('#temp').val('');
				CKEDITOR.instances['changelog'].setData('');
			});
		});
		
		function loadDataTable() {
		  return $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'asc']],
			 ajax: {
				url: '{{ url("admin/setting/version/datatable") }}',
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
				{ name: 'version', className: 'text-center align-middle' },
				{ name: 'released_date', className: 'text-center align-middle' },
				{ name: 'changelog', className: 'align-middle' },
				{ name: 'operation', orderable: false, searchable: false, className: 'text-center align-middle' },
			 ]
		  });
		}
		
		function create(){
			CKEDITOR.instances['changelog'].updateElement();
			$.ajax({
				 url: '{{ url("admin/setting/version/create") }}',
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
		
		function success(){
			$('#modal_form').modal('toggle');
			loadDataTable();
		}
		
		function show(id) {
		  $.ajax({
			 url: '{{ url("admin/setting/version/show") }}',
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
				$('#modal_form').modal('toggle');
				$('#temp').val(id);
				$('#version').val(response.version);
				$('#released_date').val(response.released_date);
				CKEDITOR.instances['changelog'].setData(response.changelog);
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
			 text: '<h6 class="font-weight-bold mb-3">Are you sure want to delete this data?</h6><label>Deleted data cannot be reverted back.</label>',
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
					  url: '{{ url("admin/setting/version/destroy") }}',
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