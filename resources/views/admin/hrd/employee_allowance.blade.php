<div class="content-wrapper">
	<!-- Page header -->
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Manage Employee Allowance</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/hrd/salary') }}" class="btn bg-secondary btn-labeled btn-labeled-left mr-2">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add Employee Allowance
					</button>
				</div>
			</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Salary</a>
					<span class="breadcrumb-item active">Employee Allowance</span>
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
						<h3 class="card-title">List of All Employee Allowance</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0">
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>#</th>
									<th>Employee</th>
									<th>Payment Method</th>
									<th>Start Month</th>
									<th>End Month</th>
									<th>Action</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Employee Wage And Allowance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form id="form_data">
							<div class="alert alert-danger" id="validation_alert" style="display:none;">
								<ul id="validation_content"></ul>
						    </div>
							<div class="row">
								<div class="col-md-12">
									<h3>Main Information</h3>
									<hr>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Employee :<sup class="text-danger">*</sup></label>
										<input type="hidden" name="temp" id="temp">
										<select name="employee_id" id="employee_id" class="select2">
											@foreach($user as $u)
											   <option value="{{ $u->id }}">{{ $u->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">From Month :<span class="text-danger">*</span></label>
										<input type="month" class="form-control" name="start_month" id="start_month" value="{{ date('Y-m') }}">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">To Month :<span class="text-danger">*</span></label>
										<input type="month" class="form-control" name="end_month" id="end_month" value="{{ date('Y-m') }}">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Payment method :<sup class="text-danger">*</sup></label>
										<select name="payment_type" id="payment_type" class="custom-select">
											<option value="1">Monthly</option>
											<option value="2">Weekly</option>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<h3>List of Wage And Allowace</h3>
									<hr>
								</div>
								<div class="col-md-12">
									<div class="row justify-content-center">
										<div class="col-md-6">
											<div class="form-group">
												<select name="allowance_id" id="allowance_id" class="select2">
													<option value="">-- Choose --</option>
													@foreach($allowance as $a)
														<option value="{{ $a->id }}" data-type="{{ $a->type() }}">{{ $a->name }}</option>
													@endforeach
												</select>
											 </div>
										 </div>
										 <div class="col-md-2	">
											<div class="form-group">
												<button type="button" class="btn bg-success col-12" onclick="addAllowance()"><i class="icon-plus22"></i></button>
											</div>
										 </div>
									</div>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered">
										 <thead class="table-secondary">
											<tr class="text-center">
											   <th>No</th>
											   <th>Name</th>
											   <th>Type of Payment</th>
											   <th>Nominal</th>
											   <th>#</th>
											</tr>
										 </thead>
										 <tbody id="data_content">
											<tr>
												<td colspan="5" class="text-center">Please Add 1 Allowance</td>
											</tr>
										 </tbody>
									</table>
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
			
			$('#data_content').on('click', '#delete_data_content', function() {
				$(this).closest('tr').remove();
				if($('.row_allowance').length == 0){
					$('#data_content').append(`
						<tr>
							<td colspan="5" class="text-center">Please Add 1 Allowance</td>
						</tr>
					`);
				}
			});
			
			$('#modal_form').on('hidden.bs.modal', function (e) {
				$('#temp').val('');
				$('#allowance_id').val($("#allowance_id option:first").val()).trigger('change');
				$('#form_data')[0].reset();
				$('#data_content').empty();
				$('#data_content').append(`
					<tr>
						<td colspan="5" class="text-center">Please Add 1 Allowance</td>
					</tr>
				`);
				loadDataTable();
			});
			
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
		});
		
		function loadDataTable(){
			window.table = $('#datatable_serverside').DataTable({
			 stateSave: true,
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'asc']],
			 ajax: {
				url: '{{ url("admin/hrd/salary/employee_allowance/datatable") }}',
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
				{ name: 'detail', orderable: false, searchable: false, className: 'text-center align-middle details-control' },
				{ name: 'employee_id', className: 'text-center align-middle' },
				{ name: 'payment_type', searchable: false, className: 'text-center align-middle' },
				{ name: 'start_month', className: 'text-center align-middle' },
				{ name: 'end_month', className: 'text-center align-middle' },
				{ name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
			 ]
		  });
		}
		
		function rowDetail(data) {
		  var content = '';
		  $.ajax({
			 url: '{{ url("admin/hrd/salary/employee_allowance/row_detail") }}',
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
		
		function addAllowance(){
			let allowance_id   = $('#allowance_id option:selected');
			
			if(allowance_id.val()){
				if($('.row_allowance').length == 0){
					$('#data_content').empty();
				}
				
				var ada = false;
				
				$('.row_allowance').each(function(){
					if($(this).data('id') == allowance_id.val()){
						ada = true;
					}
				});
				
				if(ada == false){
					$('#data_content').append(`
						<tr class="text-center row_allowance" data-id="` + allowance_id.val() + `">
							<input type="hidden" name="allowance[]" value="` + allowance_id.val() + `">
							<td class="align-middle">` + ($('.row_allowance').length + 1) + `</td>
							<td class="align-middle">` + allowance_id.text() + `</td>
							<td class="align-middle">` + allowance_id.data('type') + `</td>
							<td class="align-middle">
								<div class="form-group">
									<input type="text" name="nominal[]" class="form-control" value="0" onkeyup="formatRupiah(this);">
								</div>
							</td>
							<td class="align-middle">
								<button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							</td>
						</tr>
					`);
					
					$('#allowance_id').val($("#allowance_id option:first").val()).trigger('change');
				}else{
					swalInit.fire('Ooppsss!', 'Allowance already added..', 'error');
				}
			} else {
				swalInit.fire('Ooppsss!', 'Please choose allowance..', 'info');
			}
		}
		
		function create() {
		  $.ajax({
			 url: '{{ url("admin/hrd/salary/employee_allowance/create") }}',
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
		}
		
		function show(id) {
		  $.ajax({
			 url: '{{ url("admin/hrd/salary/employee_allowance/show") }}',
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
				$('#employee_id').val(response.data.employee_id).trigger('change');
				$('#payment_type').val(response.data.payment_type);
				$('#start_month').val(response.data.start_month);
				$('#end_month').val(response.data.end_month);
				$('#data_content').empty();
				
				$.each(response.detail, function(i, val) {
					$('#data_content').append(`
						<tr class="text-center row_allowance" data-id="` + val.allowance_id + `">
							<input type="hidden" name="allowance[]" value="` + val.allowance_id + `">
							<td class="align-middle">` + (i + 1) + `</td>
							<td class="align-middle">` + val.allowance_name + `</td>
							<td class="align-middle">` + val.allowance_type + `</td>
							<td class="align-middle">
								<div class="form-group">
									<input type="text" name="nominal[]" class="form-control" value="` + val.nominal + `" onkeyup="formatRupiah(this);">
								</div>
							</td>
							<td class="align-middle">
								<button type="button" id="delete_data_content" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							</td>
						</tr>
					`);
				});
				
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
	</script>