<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Allowance</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<span class="breadcrumb-item active">Allowance</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Allowance</h5>
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
							<th>Type</th>
							<th>Deduction Rules</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Asset</h5>
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
								<label class="col-lg-3 col-form-label">Allowance Name :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="hidden" name="temp" id="temp">
									<input type="text" class="form-control" name="name" id="name" placeholder="Ex : Gaji Pokok">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Type of Payment :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<select name="type" id="type" class="custom-select">
										<option value="1">Monthly</option>
										<option value="2">Daily</option>
										<option value="3">Hourly</option>
									</select>
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
	
	<div class="modal fade" id="modal_rule" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Rule Allowance : <span id="title_allowance"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form id="form_data_rule">
							<div class="alert alert-danger" id="validation_alert_rule" style="display:none;">
								<ul id="validation_content_rule"></ul>
						    </div>
							<div class="row">
								<div class="col-md-12">
									<h3>Main Information</h3>
									<hr>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered">
										<thead class="table-secondary">
											<tr class="text-center">
												<th>Type</th>
												<th>Rule ( > / < / <= / >= )</th>
												<th>Number</th>
												<th>Unit</th>
												<th>Deduction(%)</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr class="text-center">
												<td>
													<input type="hidden" class="form-control" name="temp_rule" id="temp_rule">
													<select name="type_rule" id="type_rule" class="custom-select">
														<option value="1">Absence</option>
														<option value="2">Leave</option>
														<option value="3">Late</option>
													</select>
												</td>
												<td>
													<input type="text" class="form-control" name="sign_rule" id="sign_rule" placeholder="Leave empty if none...">
												</td>
												<td>
													<input type="number" class="form-control" name="number_rule" id="number_rule" placeholder="Leave empty if none...">
												</td>
												<td>
													<select name="unit_rule" id="unit_rule" class="custom-select">
														<option value="">--Choose this if empty--</option>
														<option value="1">Month</option>
														<option value="2">Day</option>
														<option value="3">Hour</option>
														<option value="4">Minute</option>
													</select>
												</td>
												<td>
													<input type="text" class="form-control" name="percentage_cutting" id="percentage_cutting" value="100" onkeyup="formatRupiah(this)">
												</td>
												<td>
													<button type="button" class="btn bg-primary" id="btn_create" onclick="create_rule()"><i class="icon-plus3"></i> Save</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-12 mt-3">
						<h3>List of All Rules</h3>
						<hr>
					</div>
					<div class="col-md-12">
						<table class="table table-bordered">
							<thead class="table-secondary">
								<tr class="text-center">
									<th>Type</th>
									<th>Rule</th>
									<th>Number</th>
									<th>Unit</th>
									<th>Deduction(%)</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="data_rule">
								<tr>
									<td colspan="6" class="text-center">Empty rules, you may add here...</td>
								</tr>
							</tbody>
						</table>
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
			loadDataTable();
		});
		
		$('#modal_rule').on('hidden.bs.modal', function (e) {
			$('#temp_rule').val('');
			$('#form_data_rule').trigger('reset');
			loadDataTable();
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
				  url: '{{ url("admin/master_data/hrd/allowance/destroy") }}',
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
            url: '{{ url("admin/master_data/hrd/allowance/datatable") }}',
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
            { name: 'name', className: 'text-center align-middle' },
            { name: 'type', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'rules', orderable: false, searchable: false, className: 'text-center align-middle' },
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
		 url: '{{ url("admin/master_data/hrd/allowance/create") }}',
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
		 url: '{{ url("admin/master_data/hrd/allowance/show") }}',
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
			$('#name').val(response.name);
			$('#type').val(response.type);
			
			$('#modal_form').modal('toggle');
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
	
	function rule(id,name) {
		$('#title_allowance').text(name);
		$('#modal_rule').modal('toggle');
		$('#temp_rule').val(id);
		getListRules();
	}
	
	function getListRules(){
		$.ajax({
		 url: '{{ url("admin/master_data/hrd/allowance/show_rules") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: {
			id: $('#temp_rule').val()
		 },
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			$('#data_rule').empty();
			
			if(response.list.length > 0){
				$.each(response.list, function(i, val) {
					$('#data_rule').append(`
						<tr class="text-center">
							<td>` + val.type + `</td>
							<td>` + val.rule + `</td>
							<td>` + val.number + `</td>
							<td>` + val.unit + `</td>
							<td>` + val.percent + `</td>
							<td>
								<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyRule(` + val.id + `)"><i class="icon-trash-alt"></i></button>
							</td>
						</tr>
					`);
				});
			}else{
				$('#data_rule').append(`
					<tr>
						<td colspan="6" class="text-center">Empty rules, you may add here...</td>
					</tr>
				`);
			}
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
	
	function create_rule() {
	  $.ajax({
		 url: '{{ url("admin/master_data/hrd/allowance/create_rule") }}',
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
			$('#validation_alert').hide();
			$('#validation_content').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
			   getListRules();
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
	
	function destroyRule(id) {
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
				  url: '{{ url("admin/master_data/hrd/allowance/destroy_rule") }}',
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
						getListRules();
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