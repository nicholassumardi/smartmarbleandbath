<div class="content-wrapper">
	<!-- Page header -->
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Salary</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<a href="{{ url('admin/hrd/salary/employee_allowance') }}" class="btn bg-info btn-labeled mr-2 btn-labeled-left">
						<b><i class="icon-users2"></i></b> Set Employee Allowance
					</a>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add Monthly Payroll
					</button>
				</div>
			</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<span class="breadcrumb-item active">Salary</span>
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
						<h3 class="card-title">List of All Monthly Payroll</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0">
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>#</th>
									<th>No</th>
									<th>Branch</th>
									<th>Date</th>
									<th>Month</th>
									<th>THP</th>
									<th>Addition</th>
									<th>Cutting</th>
									<th>Loan Credit</th>
									<th>Grandtotal</th>
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
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add and Generate Salary</h5>
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
										<label class="col-form-label">Payment method :<sup class="text-danger">*</sup></label>
										<input type="hidden" name="temp" id="temp">
										<select name="payment_type" id="payment_type" class="custom-select" onchange="resetBranch()">
											<option value="1">Monthly</option>
											<option value="2">Weekly</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">For Payment's Month :<span class="text-danger">*</span></label>
										<input type="month" class="form-control" name="month" id="month" value="{{ date('Y-m') }}" onchange="resetBranch()">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">From Date (Attendance) :<span class="text-danger">*</span></label>
										<input type="date" class="form-control" name="date_start" id="date_start" value="{{ date('Y-m-d') }}" onchange="resetBranch()">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">To Date (Attendance) :<span class="text-danger">*</span></label>
										<input type="date" class="form-control" name="date_end" id="date_end" value="{{ date('Y-m-d') }}" onchange="resetBranch()">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Date Generate :<span class="text-danger">*</span></label>
										<input type="date" class="form-control" name="date_generate" id="date_generate" value="{{ date('Y-m-d') }}">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Branch :<sup class="text-danger">*</sup></label>
										<select name="branch" id="branch" class="custom-select" onchange="getEmployeeSalary(this.value)">
											<option value="">--Select Branch--</option>
											@foreach (DB::table('company_entities')->get() as $company)
												<option value="{{$company->id}}">{{$company->name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group mt-3">
										<label class="col-form-label">&nbsp;</label>
										<b><i>When you select branch, it will automatically get employee salary with their attendance data based on date (from and to) choosen.</i></b>
									</div>
								</div>
								<div class="col-md-12">
									<h3>Nominal Information</h3>
									<hr>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Total Take Home Pay :<span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="total_allowance" id="total_allowance" value="0" onkeyup="formatRupiah(ini)">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Total Addition :<span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="total_addition" id="total_addition" value="0" onkeyup="formatRupiah(ini)">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Total Deduction :<span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="total_cutting" id="total_cutting" value="0" onkeyup="formatRupiah(ini)">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Total Loan Credit :<span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="total_loan" id="total_loan" value="0" onkeyup="formatRupiah(ini)">
									</div>
								</div>
								<div class="col-md-12">
									<h3>Employee Information</h3>
									<hr>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered">
									 <thead class="table-secondary">
										<tr class="text-center">
										   <th>Employee</th>
										   <th class="bg-success">Take Home Pay</th>
										   <th class="bg-success">Addition</th>
										   <th class="bg-danger">Deduction</th>
										   <th class="bg-danger">Loan Credit</th>
										   <th class="bg-info">Total</th>
										</tr>
									 </thead>
									 <tbody id="data_content"></tbody>
									 <thead class="table-secondary">
										<tr class="text-right">
										   <th colspan="5">Total</th>
										   <th id="grandtotal_all">0</th>
										</tr>
									 </thead>
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
	
	<div id="modal_info" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="width: 50%;background-color: #c5c5c5 !important;border: 2px solid blue;">
				<div class="modal-header bg-info">
					<h3 class="modal-title" id="modal_info_title"></h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body" id="modal_info_content">
					
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		$(function() {
			loadDataTable();
			
			$('#modal_date').on('hidden.bs.modal', function (e) {
				$('#modal_info_title,#modal_info_content').html('');
			});
			
			$('#modal_form').on('hidden.bs.modal', function (e) {
				$('#temp').val('');
				$('#form_data')[0].reset();
				$('#grandtotal_all').text('0');
				$('#data_content').empty();
			});
		});
		
		function resetBranch(){
			$('#branch').val('');
			$('#data_content').empty();
		}
		
		function loadDataTable() {
		  window.table = $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[0, 'desc']],
			 ajax: {
				url: '{{ url("admin/hrd/salary/datatable") }}',
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
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'code', className: 'text-center align-middle' },
				{ name: 'branch', searchable: false, className: 'text-center align-middle' },
				{ name: 'date_generate', className: 'text-center align-middle' },
				{ name: 'month', className: 'text-center align-middle' },
				{ name: 'allowance', className: 'text-center align-middle' },
				{ name: 'addition', className: 'text-center align-middle' },
				{ name: 'cutting', className: 'text-center align-middle' },
				{ name: 'loan', className: 'text-center align-middle' },
				{ name: 'grandtotal', className: 'text-center align-middle' },
				{ name: 'operation', searchable: false, orderable: false, className: 'text-center align-middle' },
			 ]
		  }); 
		}
		
		function getEmployeeSalary(val){
			if(val){
				$.ajax({
				 url: '{{ url("admin/hrd/salary/get_employee_salary") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					month: $('#month').val(),
					date_start: $('#date_start').val(),
					date_end: $('#date_end').val(),
					branch: val,
					payment: $('#payment_type').val()
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					$('#data_content').empty();
					
					if(response.list.length > 0){
						$.each(response.list, function(i, val) {
							$('#data_content').append(`
								<tr class="text-center">
									<input type="hidden" name="arr_id[]" value="` + val.id + `">
									<input type="hidden" name="arr_allowance[]" value="` + val.value_allowance + `" id="allowance` + val.id + `">
									<input type="hidden" name="arr_cutting[]" value="` + val.value_cutting + `" id="cutting` + val.id + `">
									<input type="hidden" name="arr_loan[]" value="` + val.value_loan + `" id="loan` + val.id + `">
									<input type="hidden" name="arr_total[]" value="` + val.total + `" id="total` + val.id + `">
									<input type="hidden" name="arr_employee_loan[]" value="` + val.arr_loan + `">
									<td>` + val.employee + `</td>
									<td class="text-right">` + val.allowance + `</td>
									<td class="text-right"><input type="text" name="arr_addition[]" value="0" onkeyup="formatRupiah(this);countRow(this,` + val.id + `);" class="form-control"></td>
									<td class="text-right">` + val.cutting + `</td>
									<td class="text-right">` + val.loan + `</td>
									<td class="text-right rowTotal" id="textTotal` + val.id + `">` + val.total + `</td>
								</tr>
							`);
						});
					}
					
					countAll();
					
					$('#grandtotal_all').html(response.grandtotal);
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
			}else{
				$('#data_content').empty();
			}
		}
		
		function countRow(element,id){
			var allowance = parseFloat($('#allowance' + id).val().replaceAll(".", "").replaceAll(",",".")), addition = parseFloat($(element).val().replaceAll(".", "").replaceAll(",",".")), cutting = parseFloat($('#cutting' + id).val().replaceAll(".", "").replaceAll(",",".")), loan = parseFloat($('#loan' + id).val().replaceAll(".", "").replaceAll(",","."));
			
			$('#textTotal' + id).text(formatRupiahIni((allowance + addition - cutting - loan).toFixed(2).toString().replace('.',',')));
			
			$('#total' + id).val(formatRupiahIni((allowance + addition - cutting - loan).toFixed(2).toString().replace('.',',')));
			
			countAll();
		}
		
		function countAll(){
			var total = 0;
			$('.rowTotal').each(function(){
				total += parseFloat($(this).text().replaceAll(".", "").replaceAll(",","."));
			});
			
			var total_allowance = 0, total_addition = 0, total_loan = 0, total_cutting = 0;
			
			$('input[name^="arr_allowance"]').each(function(){
				total_allowance += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
			});
			
			$('input[name^="arr_addition"]').each(function(){
				total_addition += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
			});
			
			$('input[name^="arr_loan"]').each(function(){
				total_loan += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
			});
			
			$('input[name^="arr_cutting"]').each(function(){
				total_cutting += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
			});
			
			$('#total_allowance').val(formatRupiahIni(total_allowance.toFixed(2).toString().replace('.',',')));
			$('#total_addition').val(formatRupiahIni(total_addition.toFixed(2).toString().replace('.',',')));
			$('#total_loan').val(formatRupiahIni(total_loan.toFixed(2).toString().replace('.',',')));
			$('#total_cutting').val(formatRupiahIni(total_cutting.toFixed(2).toString().replace('.',',')));
			
			$('#grandtotal_all').html(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
		}
		
		function formatRupiahIni(angka){
			var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
		 
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
		 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			
			return rupiah;
		}
		
		function showAllowance(user,month,date_start,date_end){
			$('#modal_info_title').html('Allowance Info');
			
			$.ajax({
				 url: '{{ url("admin/hrd/salary/get_info_allowance") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					user: user,
					month: month,
					date_start: date_start,
					date_end: date_end
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					
					$('#modal_info_content').html(response.content);
					
					$('#modal_info').modal('toggle');
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
		
		function showCutting(user,month,date_start,date_end){
			$('#modal_info_title').html('Salary Deduction Info');
			
			$.ajax({
				 url: '{{ url("admin/hrd/salary/get_info_cutting") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					user: user,
					month: month,
					date_start: date_start,
					date_end: date_end
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					
					$('#modal_info_content').html(response.content);
					
					$('#modal_info').modal('toggle');
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
		
		function showLoanCredit(user){
			$('#modal_info_title').html('Loan Info');
			
			$.ajax({
				 url: '{{ url("admin/hrd/salary/get_info_loan") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					user: user
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					
					$('#modal_info_content').html(response.content);
					
					$('#modal_info').modal('toggle');
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
		
		function create() {
			var notyConfirm = new Noty({
				 theme: 'limitless',
				 text: '<h6 class="font-weight-bold mb-3">Are sure you want to send?</h6><label>Selected employees will receive details salary slip in their emails.</label>',
				 timeout: false,
				 modal: true,
				 layout: 'center',
				 closeWith: 'button',
				 type: 'confirm',
				 buttons: [
					Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
					   notyConfirm.close();
					}),
					Noty.button('<i class="icon-stack-check"></i>', 'btn bg-success ml-1', function() {
						if($('.rowTotal').length > 0){
							$.ajax({
							 url: '{{ url("admin/hrd/salary/create") }}',
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
							notif('error', 'bg-danger', 'Check your form.');
						}
					})
				 ]
			}).show();
		}
		
		function success() {
		  $('#modal_form').modal('hide');
		  $('#datatable_serverside').DataTable().ajax.reload(null, false);
		}
		
		function show(id){
			$.ajax({
			 url: '{{ url("admin/hrd/salary/show") }}',
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
				$('#month').val(response.data.month);
				$('#date_start').val(response.data.date_start);
				$('#date_end').val(response.data.date_end);
				$('#date_generate').val(response.data.date_generate);
				$('#branch').val(response.data.branch);
				$('#data_content').empty();
				
				$.each(response.detail, function(i, val) {
					$('#data_content').append(`
						<tr class="text-center">
							<input type="hidden" name="arr_id[]" value="` + val.id + `">
							<input type="hidden" name="arr_allowance[]" value="` + val.value_allowance + `" id="allowance` + val.id + `">
							<input type="hidden" name="arr_cutting[]" value="` + val.value_cutting + `" id="cutting` + val.id + `">
							<input type="hidden" name="arr_loan[]" value="` + val.value_loan + `" id="loan` + val.id + `">
							<input type="hidden" name="arr_total[]" value="` + val.total + `" id="total` + val.id + `">
							<input type="hidden" name="arr_employee_loan[]" value="` + val.arr_loan + `">
							<td>` + val.employee + `</td>
							<td class="text-right">` + val.allowance + `</td>
							<td class="text-right"><input type="text" name="arr_addition[]" value="` + val.addition + `" onkeyup="formatRupiah(this);countRow(this,` + val.id + `);" class="form-control"></td>
							<td class="text-right">` + val.cutting + `</td>
							<td class="text-right">` + val.loan + `</td>
							<td class="text-right rowTotal" id="textTotal` + val.id + `">` + val.total + `</td>
						</tr>
					`);
				});
				
				$('#modal_form').modal('toggle');
				
				countAll();
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