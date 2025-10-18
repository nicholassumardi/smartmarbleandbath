<div class="content-wrapper">
	<!-- Page header -->
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Purchase Request</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" onclick="cancel()" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add
					</button>
				</div>
			</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<span class="breadcrumb-item active">Purchase Request</span>
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
						<h3 class="card-title">My Purchase Request</h3>
						<div class="header-elements">
							<div class="form-group row">
								<label class="col-form-label mr-2">Filter</label>
								<div class="mr-2">
									<select name="filter_type" id="filter_type" class="custom-select" onchange="loadDataTable()">
										<option value="">All</option>
										<option value="1">Empty Proof</option>
										<option value="2">Tax Purchase Order</option>
									</select>
								</div>

								<div class="col">
									<select name="filter_status" id="filter_status" class="custom-select" onchange="loadDataTable()">
										<option value="">All Status</option>
										<option value="PEND">Pending</option>
										<option value="APPR">Approve</option>
										<option value="PAID">Paid</option>
										<option value="RCVD">Received</option>
										<option value="SUBM">Submit</option>
										<option value="RJCT">Rejected</option>
										<option value="DONE">Done</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body py-0">
						<div class="row text-center">
							<div class="col-4 bg-info">
								<div class="mb-3 mt-3">
									<h5 class="font-weight-semibold mb-0">{{ number_format($totalPaid,2,',','.') }}</h5>
									<span class="font-size-sm">Total Paid</span>
									<p><button class="btn btn-sm bg-violet-600" onclick="showPaid()">Show Details</button></p>
								</div>
							</div>

							<div class="col-4 bg-danger">
								<div class="mb-3 mt-3">
									<h5 class="font-weight-semibold mb-0">{{ number_format($totalUnpaid,2,',','.') }}</h5>
									<span class="font-size-sm">Total Un-paid</span>
									<p><button class="btn btn-sm bg-violet-600" onclick="showUnpaid()">Show Details</button></p>
								</div>
							</div>

							<div class="col-4 bg-success">
								<div class="mb-3 mt-3">
									<h5 class="font-weight-semibold mb-0">{{ number_format($totalRequest,2,',','.') }}</h5>
									<span class="font-size-sm">Total Requested</span>
									<p><button class="btn btn-sm bg-violet-600" onclick="showRequest()">Show Details</button></p>
								</div>
							</div>
						</div>
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside" class="table table-bordered table-striped">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>#</th>
									<th>User</th>
									<th>Date Req.</th>
									<th>Due Date</th>
									<th>To</th>
									<th width="25%">Title & Item</th>
									<th>Cash Adv</th>
									<th>Real Total</th>
									<th>Paid</th>
									<th>Status</th>
									<th>Photo</th>
									<th>Paid Proof</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add Purchase Request</h5>
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
								<label class="col-lg-3 col-form-label">Supplier/Store<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="bill_to" id="bill_to" placeholder="Add person / company your purchase from.">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Title<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="title" id="title" maxlength="50" placeholder="Max 50 Char.">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Additional Note<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<textarea class="form-control" name="item" id="item" rows="5" onkeyup="onTestChange();" placeholder="Unlimited Character"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Date<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
								</div>
							</div>
							<div class="form-group form-group-feedback form-group-feedback-right row">
								<label class="col-lg-3">Is Real / Cash Advance?</label>
								<div class="col-lg-9">
									<div class="form-check">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input-styled-primary" data-fouc onclick="switchCash(this)">
											Check this if cash advance.
										</label>
									</div>
								</div>
							</div>
							<div class="form-group form-group-feedback form-group-feedback-right row">
								<label class="col-lg-3">Cash Advance (Kasbon)</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="cash_advance" id="cash_advance" onkeyup="formatRupiah(this)" value="0" readonly>
									<div class="form-control-feedback font-weight-bold">
										Leave 0 if not cash adv.
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Real Nominal<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="nominal" id="nominal" onkeyup="formatRupiah(this)" value="0">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Proof's File</label>
								<div class="col-lg-9">
									<input type="file" class="form-input-styled" id="file" name="file">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-3"></div>
								<div class="col-lg-4">
									<span class="badge d-block badge-danger form-text">ONLY FOR PAYMENT FEE PTA.</span>
									<div class="form-check">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input-styled" data-fouc name="fee_pta" id="fee_pta" value="1" readonly>
											Is Fee PTA Purchase Request?
										</label>
									</div>
								</div>
								<div class="col-lg-4">
									<span class="badge d-block badge-danger form-text">ONLY FOR MIDDLEMAN PAYMENT REQUEST.</span>
									<div class="form-check">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input-styled" data-fouc name="middleman" id="middleman" value="1">
											Is Middleman Payment Request?
										</label>
									</div>
								</div>
							</div>
							@if (session('bo_branch') == '1')
							<div class="form-group row">
								<div class="col-lg-3"></div>
								<div class="col-lg-4">
									<span class="badge d-block badge-danger form-text">ONLY FOR CUSTOMER DEPOSIT.</span>
									<div class="form-check">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input-styled" data-fouc name="customer_deposit" id="customer_deposit" value="1" readonly>
											Is Customer Deposit Payment Request?
										</label>
									</div>
								</div>
								<div class="col-lg-4">
									<span class="badge d-block badge-danger form-text">ONLY FOR WIP PURCHASE REQUEST.</span>
									<div class="form-check">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input-styled" data-fouc name="is_wip" id="is_wip" value="1">
											Is WIP ?
										</label>
									</div>
								</div>
							</div>
							@endif
							
							
							<div class="form-group row middle-hide d-none">
							  <label class="col-lg-3 col-form-label">Choose Comission list :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
								  <select name="middle_list" id="middle_list" class="select2" onchange="getBalanceMiddleman(this)">
									<option value="">-- Choose --</option>
									@foreach($apCommission as $result)
										<option value="{{ $result->id }}" data-balance="{{ $result->request }}" data-customer="{{ $result->project->customer->name }}" data-so="{{ $result->code }}">{{ 'Project : '.$result->project->name.' - '.$result->project->code.' - '.number_format($result->balance,0,',','.').' Cust. : '.$result->project->customer->name.' Total SO - '.$result->code.' : '.$result->getTotalRaw().' Delivered : '.number_format($result->getTotalDelivered()['totaldelivered'],0,',','.').' Paid : '.$result->getPaid().' Total '.number_format($result->request,0,',','.') }}</option>
									@endforeach
								  </select>
							  </div>
							</div>
							
							<div class="form-group row fee-hide d-none">
							  <label class="col-lg-3 col-form-label">Choose Fee PTA list :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
								  <select name="fee_pta_list" id="fee_pta_list" class="select2" onchange="getFeePta(this)">
									<option value="">-- Choose --</option>
									@foreach($listFee as $row)
										<option value="{{ $row->cashBank->id }}" data-balance="{{ number_format($row->nominal,2,',','.') }}" data-pj="{{ $row->cashBank->description.' PJ No.'.$row->cashBank->lookable->code }}" data-supplier="{{ $row->cashBank->supplier->name }}">Project {{ $row->cashBank->lookable->name.' '.$row->cashBank->lookable->code.' IDR '.number_format($row->nominal,2,',','.') }}</option>
									@endforeach
								  </select>
							  </div>
							</div>

							<div class="form-group row custdepo-hide d-none">
								<label class="col-lg-3 col-form-label">Choose Customer Deposit list :<span class="text-danger">*</span></label>
								<div class="col-lg-9">
									<select name="customer_deposit_list" id="customer_deposit_list" class="select2" onchange="getCustomerDeposit(this)">
									  <option value="">-- Choose --</option>
									  @foreach($customerdeposit as $row)
									  @if(str_replace(',','.',str_replace('.','',$row['total'])) > 0)
									  <option value="{{ $row['customer_id'] }}" data-branch="{{$row['branch']}}" data-balance="{{$row['total']}}" data-customerdeposit="{{'Customer Deposit '.$row['customer_name'].' IDR '.$row['total']}}" data-customer="{{ $row['customer_name'] }}">Customer {{ $row['customer_name'].'- IDR '.$row['total'] }}</option>
									  @endif
									  @endforeach
									</select>
								</div>
							</div>
							
							<div class="form-group row wip-hide d-none">
								<label class="col-lg-3 col-form-label">Choose COA  :<span class="text-danger">*</span></label>
								<div class="col-lg-9">
									<div class="form-group">
										<select name="wip_coa_id" id="wip_coa_id" class="select2">
											@foreach($wip_coa as $row)
												<option value="{{ $row->id }}">{{ $row->code.' - '.$row->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							
							<div class="form-group row term-hide">
							  <label class="col-lg-3 col-form-label">Branch :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<select name="branch" id="branch" class="custom-select">
									@foreach (DB::table('company_entities')->get() as $company)
										@if(session('bo_branch') == 2)
    										@if(in_array($company->id,array('1','2')))
    										    <option value="{{$company->id}}" {{$company->id == session('bo_branch') ? 'selected' : ''}}>{{$company->name}}</option>
    										@endif
										@else
										    <option value="{{$company->id}}" {{$company->id == session('bo_branch') ? 'selected' : ''}}>{{$company->name}}</option>
										@endif
									 @endforeach
									</select>
									
							  </div>
							</div>
							<div class="form-group row term-hide">
								<label class="col-lg-3 col-form-label">Supplier :<span class="text-danger">*</span></label>
								<div class="col-lg-9">
									<select name="supplier_id" id="supplier_id">
										<option value="132" selected>Other</option>
									</select>
								</div>
							</div>
							<div class="form-group row term-hide">
								<label class="col-lg-3 col-form-label">Term (days) :</label>
								<div class="col-lg-9">
									<!-- <input type="number" class="form-control" name="termcount" id="termcount" value="0" step="1" min="0" onkeyup="countTerm(this.value)" readonly> -->
									<input type="number" class="form-control" name="termcount" id="termcount" value="0" step="1" min="0" readonly>
								</div>
							</div>
							<div class="form-group row term-hide">
								<label class="col-lg-3 col-form-label">Due Date<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="date" class="form-control" name="due_date" id="due_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
								</div>
							</div>
						</form>
					</div>
				</div>
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()" style="display:none;"><i class="icon-cross3"></i> Cancel</button>
				<button type="button" class="btn bg-warning" id="btn_update" onclick="update()" style="display:none;"><i class="icon-pencil7"></i> Save</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_information" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Detail Purchase Request Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" id="body-information">
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<script>
		$(function() {
			loadDataTable();
			
			$('#modal_form').on('hidden.bs.modal', function (e) {
				$('#term').prop('checked', false);
				$('#middleman').prop('checked', false);
				$('#fee_pta').prop('checked', false);
				$('#uniform-middleman span').removeClass('checked');
				$('#uniform-fee_pta span').removeClass('checked');
				$('#middle_list').val('').trigger('change');
				$('#fee_pta_list').val('').trigger('change');
				$('#supplier_id').empty();
				$('#supplier_id').append(`
					<option value="132" selected>Other</option>
				`);
				$('#termcount').val('0');
				$('#due_date').val('');
				$('#item').val('');
				$('#date').val('');
				$('#nominal').val('');
				$('#file').val('');
				if(!$('.middle-hide').hasClass('d-none')){
					$('.middle-hide').addClass('d-none');
				}
				if(!$('.fee-hide').hasClass('d-none')){
					$('.fee-hide').addClass('d-none');
				}
				if(!$('.custdepo-hide').hasClass('d-none')){
					$('.custdepo-hide').addClass('d-none');
				}
				if(!$('.wip-hide').hasClass('d-none')){
					$('.wip-hide').addClass('d-none');
				}
			});
			
			$('#modal_information').on('hidden.bs.modal', function (e) {
				$('#body-information').html('');
			});
			
			select2ServerSide('#supplier_id', '{{ url("admin/select2/supplier") }}');
			
			$('#term').click(function(){
				if($(this).prop("checked") == true){
					$('.term-hide').removeClass('d-none');
				}else{
					$('.term-hide').addClass('d-none');
				}
			});


			function toggleElements(activeElement, relatedClass) {
				$(relatedClass).toggleClass('d-none', !$(activeElement).prop('checked'));

				['#middleman', '#fee_pta', '#customer_deposit', '#is_wip'].forEach((selector) => {
					if (selector !== activeElement && $(selector).prop('checked')) {
						$(selector).trigger('click');
					}
				});
			}

			$('#middleman').click(function() {
				toggleElements('#middleman', '.middle-hide');
			});

			$('#fee_pta').click(function() {
				toggleElements('#fee_pta', '.fee-hide');
			});

			$('#customer_deposit').click(function() {
				toggleElements('#customer_deposit', '.custdepo-hide');
			});

			$('#is_wip').click(function() {
				toggleElements('#is_wip', '.wip-hide');
			});
			
			/*$('#middleman').click(function(){
				if($(this).prop("checked") == true){
					$('.middle-hide').removeClass('d-none');
					if($('#fee_pta').prop("checked") == true){
						$('#fee_pta').trigger('click');
					}else if($('#customer_deposit').prop("checked") == true){
						$('#customer_deposit').trigger('click');
					}else if($('#is_wip').prop("checked") == true){
						$('#is_wip').trigger('click');
					}
				}else{
					$('.middle-hide').addClass('d-none');
				}
			});
			
			$('#fee_pta').click(function(){
				if($(this).prop("checked") == true){
					$('.fee-hide').removeClass('d-none');
					if($('#middleman').prop("checked") == true){
						$('#middleman').trigger('click');
					}else if($('#customer_deposit').prop("checked") == true){
						$('#customer_deposit').trigger('click');
					}else if($('#is_wip').prop("checked") == true){
						$('#is_wip').trigger('click');
					}
				}else{
					$('.fee-hide').addClass('d-none');
				}
			});

			$('#customer_deposit').click(function(){
				if($(this).prop("checked") == true){
					$('.custdepo-hide').removeClass('d-none');
					if($('#middleman').prop("checked") == true){
						$('#middleman').trigger('click');
					}else if($('#fee_pta').prop("checked") == true){
						$('#fee_pta').trigger('click');
					}else if($('#is_wip').prop("checked") == true){
						$('#is_wip').trigger('click');
					}
				}else{
					$('.custdepo-hide').addClass('d-none');
				}
			});

			$('#is_wip').click(function(){
				if($(this).prop("checked") == true){
					$('.wip-hide').removeClass('d-none');

					if($('#middleman').prop("checked") == true){
						$('#middleman').trigger('click');
					}else if($('#fee_pta').prop("checked") == true){
						$('#fee_pta').trigger('click');
					}else if($('#customer_deposit').prop("checked") == true){
						$('#customer_deposit').trigger('click');
					}

				}else{
					$('.wip-hide').addClass('d-none');
				}
			}); */

			

			
			
			/* $('#date').change(function () {
				if($(this).val() !== ''){
					$('#due_date').val($(this).val());
				}
			}); */
			
			$('.sidebar-main-toggle').click();
		});
		
		function getBalanceMiddleman(element){
			$('#nominal').val($(element).find(':selected').attr('data-balance')).trigger('keyup');
			$('#bill_to').val($(element).find(':selected').attr('data-customer'));
			$('#title').val('MIDDLEMAN FEE 100% SO ' + $(element).find(':selected').attr('data-so'));
		}
		
		function getFeePta(element){
			$('#nominal').val($(element).find(':selected').attr('data-balance')).trigger('keyup');
			$('#bill_to').val($(element).find(':selected').attr('data-supplier'));
			$('#title').val('PURCHASE REQUEST ' + $(element).find(':selected').attr('data-pj'));
			$('#branch').val('2');
		}

		function getCustomerDeposit(element){
			$('#nominal').val($(element).find(':selected').attr('data-balance')).trigger('keyup');
			$('#bill_to').val($(element).find(':selected').attr('data-customer'));
			$('#item').val($(element).find(':selected').attr('data-customerdeposit'));
			$('#title').val('CUSTOMER DEPOSIT '+$(element).find(':selected').attr('data-customer'));
			$('#branch').val($(element).find(':selected').attr('data-branch')).trigger('change');
		}
		
		function onTestChange() {
			var key = window.event.keyCode;

			if (key === 13) {
				return true
			}
		}
		
		function countTerm(val){
			if(val == ''){
				val = '0';
				$('#due_date').val(addDays($('#date').val(),parseInt(val)).toISOString().split('T')[0]);
			}else{
				$('#due_date').val(addDays($('#date').val(),parseInt(val)).toISOString().split('T')[0]);
			}
		}
		
		function addDays(date, days) {
			var result = new Date(date);
			result.setDate(result.getDate() + days);
			return result;
		}
		
		function create() {
		  $.ajax({
			 url: '{{ url("admin/purchase_request/create") }}',
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
				   location.reload();
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
		
		function reset() {
			$('#form_data').trigger('reset');
			$('#validation_alert').hide();
			$('#validation_content').html('');
		}
		
		function cancel() {
			reset();
			$('#modal_form').modal('hide');
			$('#btn_create').show();
			$('#btn_update').hide();
			$('#btn_cancel').hide();
		}
		
		function update(id) {
		  $.ajax({
			 url: '{{ url("admin/purchase_request/userUpdate") }}' + '/' + id,
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
		
		function success() {
		  reset();
		  $('#modal_form').modal('hide');
		  $('#datatable_serverside').DataTable().ajax.reload(null, false);
		}
		
		function reset() {
			$('#form_data').trigger('reset');
			$('#validation_alert').hide();
			$('#validation_content').html('');
		}
		
		function loadDataTable() {
		  window.table = $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[1, 'desc']],
			 ajax: {
				url: '{{ url("admin/purchase_request/user_datatable") }}',
				type: 'GET',
				data: {
					filter_type : $('#filter_type').val(),
					filter_status : $('#filter_status').val(),
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
				{ name:  null, orderable: false, className: 'text-right align-middle' },
				{ name: 'date', className: 'text-center align-middle' },
				{ name: 'due_date', className: 'text-center align-middle' },
				{ name: 'to', className: 'text-center align-middle' },
				{ name: 'item', className: 'text-center align-middle' },
				{ name: 'cash_adv', className: 'text-right align-middle' },
				{ name: 'total', className: 'text-right align-middle' },
				{ name: 'paid', searchable: false, orderable: false, className: 'text-right align-middle' },
				{ name: 'status', className: 'text-center align-middle' },
				{ name: 'image', searchable: false, className: 'text-center align-middle' },
				{ name: 'proof', searchable: false, orderable: false, className: 'text-center align-middle' },
				{ name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
			 ]
		  }); 
		}
		
		function toShow() {
			$('#modal_form').modal('show');
			$('#validation_alert').hide();
			$('#validation_content').html('');
			$('#btn_create').hide();
			$('#btn_update').show();
			$('#btn_cancel').show();
		}
		
		function show(id) {
		  toShow();
		  $.ajax({
			 url: '{{ url("admin/purchase_request/userShow") }}',
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
				$('#bill_to').val(response.bill_to);
				$('#title').val(response.title);
				$('#item').val(response.item);
				$('#date').val(response.date);
				$('#nominal').val(response.total_nominal);
				$('#cash_advance').val(response.cash_advance);
				$('#cash_advance').prop('readonly', true);
				
				$('#supplier_id').empty();
				$('#supplier_id').append(`
					<option value="` + response.supplier_id + `">` + response.supplier_name + `</option>
				`);
				$('#branch').val(response.branch);
				$('#termcount').val(response.termcount);
				$('#due_date').val(response.due_date);
				
				$('#btn_update').attr('onclick', 'update(' + id + ')');
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
			 text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="text-muted">If your purchase request is for Purchase Bill, make sure also delete the purchase bill in Purchase Order.</div>',
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
					  url: '{{ url("admin/purchase_request/userDestroy") }}',
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
		
		function showPaid(){
			$.ajax({
			 url: '{{ url("admin/purchase_request/showInformation") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				type: 'paid'
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				$('#modal_information').modal('toggle');
				$('#body-information').html(response.content);
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
		
		function showUnpaid(){
			$.ajax({
			 url: '{{ url("admin/purchase_request/showInformation") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				type: 'unpaid'
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				$('#modal_information').modal('toggle');
				$('#body-information').html(response.content);
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
		
		function showRequest(){
			$.ajax({
			 url: '{{ url("admin/purchase_request/showInformation") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				type: 'request'
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				$('#modal_information').modal('toggle');
				$('#body-information').html(response.content);
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
		
		function switchCash(element){
			if($(element).is(':checked')){
				$('#cash_advance').prop('readonly', false);
				$('#nominal').prop('readonly', true);
			}else{
				$('#cash_advance').prop('readonly', true);
				$('#nominal').prop('readonly', false);
			}
		}
	</script>