<style>
	#datatable_serverside tbody tr.selected {
		background-color: green;
		color:white;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Purchase Request</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled mr-2 btn-labeled-left" data-toggle="modal" data-target="#modal_payment">
						<b><i class="icon-cash"></i></b> Multi Payment
					</button>
					<button type="button" class="btn bg-danger btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_report">
						<b><i class="icon-stack-text"></i></b> Payable Report
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Purchase Request</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Purchase Request</h5>
				<div class="header-elements">
				</div>
			</div>
			<div class="card-body">
				<form id="form_submit" method="POST" action="{{ url('admin/finance/purchase_request/print') }}" target="_blank">
					@csrf
					<div class="row">
						<div class="col-md-2">
						  <div class="form-group">
							 <label>Date :</label>
							 <div class="input-group-prepend">
								<input type="hidden" name="filter_temp" id="filter_temp">
								<input type="date" name="filter_start_date" id="filter_start_date" class="form-control">
							 </div>
						  </div>
						</div>
						<div class="col-md-2">
						  <div class="form-group">
							 <label>To :</label>
							 <div class="input-group-prepend">
								<input type="date" name="filter_finish_date" id="filter_finish_date" class="form-control">
							 </div>
						  </div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>&nbsp;</label>
								<select name="filter_user_id" id="filter_user_id" class="select2">
									<option value="">All User</option>
									@foreach($user as $u)
									   <option value="{{ $u->id }}">{{ $u->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>&nbsp;</label>
								<select name="filter_type" id="filter_type" class="custom-select">
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
						<div class="col-md-2">
							<div class="form-group">
								<label>&nbsp;</label>
								<select name="filter_branch" id="filter_branch" class="custom-select">
									<option value="">All Branch</option>
									@foreach (DB::table('company_entities')->get() as $company)
										<option value="{{$company->id}}">{{$company->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>&nbsp;</label>
								<div class="input-group-prepend">
									<button type="button" onclick="filter()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
									<button type="button" onclick="filter('reset')" class="btn bg-danger mr-2"><i class="icon-sync"></i></button>
									<button type="submit" class="btn bg-success"><i class="icon-printer2"></i></button>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
								<span class="font-weight-semibold">Important Info!</span> 
								Before you press print button, you may filter the data by search and choose the data rows you want.
							</div>
						</div>
					</div>
				</form>
				<button onclick="selectAllRow()" class="btn btn-primary float-right">(Un) Select All Rows</button>
				<div class="table-responsive mt-5">
					<table id="datatable_serverside" class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>ID</th>
							<th>Name</th>
							<th>Date</th>
							<th>Due Date</th>
							<th>Branch</th>
							<th>To</th>
							<th width="25%">Title / Item</th>
							<th>Cash Adv.</th>
							<th>Real Total</th>
							<th>Status</th>
							<th>Payments</th>
							<th>User Proof</th>
							<th>Action</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data">
				   <div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
				   </div>
					<h5 class="card-title"><b>Main Information</b></h5>
				   <div class="row">
					  <div class="col-md-4">
						 <div class="form-group">
							<label>Code :<sup class="text-danger">*</sup></label>
							<input type="text" name="code" id="code" class="form-control" placeholder="Enter code" readonly>
							<span class="badge d-block badge-danger form-text">Do not change this code, as it is used to connect PRF and Cash & Banks</span>
						 </div>
					  </div>
					  <div class="col-md-4">
						 <div class="form-group">
							<label>Date :<sup class="text-danger">*</sup></label>
							<input type="date" name="date" id="date" class="form-control">
						 </div>
					  </div>
					  <div class="col-md-4">
						<div class="form-group">
						  <label>Description :<sup class="text-danger">*</sup></label>
						  <textarea name="description" id="description" class="form-control" placeholder="Enter description" rows="1"></textarea>
						</div>
					  </div>
				   </div>
				   <div class="form-group"><hr></div>
				   <h5 class="card-title"><b>Details Coa</b></h5>
				   <div class="form-group text-center mt-4">
					  <div class="form-check form-check-inline">
						 <label class="form-check-label">
							<input type="radio" class="form-check-input" name="mode" value="1" checked>
							Debet
						 </label>
					  </div>
					  <div class="form-check form-check-inline">
						 <label class="form-check-label">
							<input type="radio" class="form-check-input" name="mode" value="2">
							Credit
						 </label>
					  </div>
				   </div>
				   <div class="row">
					  <div class="col-md-6 mx-auto">
						 <div class="form-group">
							<label>Coa :<sup class="text-danger">*</sup></label>
							<select name="coa_id" id="coa_id" class="select2">
							   <option value="">-- Choose --</option>
							   @foreach($coa->where('parent_id',0) as $c)
									@if(count($c->child()) == 0)
										<option value="{{ $c->id }}">{{ $c->name }}</option>
									@else
										<optgroup label="{{ $c->name }}">
										  @foreach($c->child() as $bc)
											@if(count($bc->child()) == 0)
												<option value="{{ $bc->id }}">{{ $bc->name }}</option>
											@else
												<optgroup label="{{ $bc->name }}">
													@foreach($bc->child() as $bcc)
														@if(count($bcc->child()) == 0)
															<option value="{{ $bcc->id }}">{{ $bcc->name }}</option>
														@else
															<optgroup label="{{ $bcc->name }}">
																@foreach($bcc->child() as $bccc)
																	@if(count($bccc->child()) == 0)
																		<option value="{{ $bccc->id }}">{{ $bccc->name }}</option>
																	@endif
																@endforeach
															</optgroup>
														@endif
													@endforeach
												</optgroup>
											@endif
										  @endforeach
										</optgroup>
									@endif
							   @endforeach
							</select>
						 </div>
					  </div>
					  <div class="col-md-6 mx-auto">
						 <div class="form-group">
							<label>Nominal :<sup class="text-danger">*</sup></label>
							<input type="text" name="nominal_detail" id="nominal_detail" class="form-control" placeholder="0" onkeyup="formatRupiah(this)">
						 </div>
					  </div>
					  <div class="col-md-6">
						<div class="form-group">
						  <label>Branch :<span class="text-danger">*</span></label>
						  <select name="branch" id="branch" class="custom-select branch">
							@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							@endforeach
						  </select>
					   </div>
					  </div>
					  <div class="col-md-6">
						 <div class="form-group">
							<label>Note :<sup class="text-danger">*</sup></label>
							<input type="text" name="note_detail" id="note_detail" class="form-control" placeholder="Enter note">
						 </div>
					  </div>
					  <div class="col-md-12">
						 <div class="form-group">
							<button type="button" class="btn bg-success col-12" onclick="addContent()"><i class="icon-plus22"></i></button>
						 </div>
					  </div>
				   </div>
				   <div class="row">
						<div class="form-group col-md-6">
							<table class="table table-bordered">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th>Debit</th>
								   <th>Branch</th>
								   <th>Nominal</th>
								   <th>Note</th>
								   <th>#</th>
								</tr>
							 </thead>
							 <tbody id="data_content_debit"></tbody>
							</table>
						</div>
						<div class="form-group col-md-6">
							<table class="table table-bordered">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th>Credit</th>
								   <th>Branch</th>
								   <th>Nominal</th>
								   <th>Note</th>
								   <th>#</th>
								</tr>
							 </thead>
							 <tbody id="data_content_credit"></tbody>
						  </table>
						</div>
					</div>
				   <div class="form-group"><hr></div>
				   <div class="form-group text-center mt-4">
					  <div class="form-check form-check-inline">
						 <label class="form-check-label">
							<input type="radio" class="form-check-input" name="type" value="1">
							Cash / Bank In
						 </label>
					  </div>
					  <div class="form-check form-check-inline">
						 <label class="form-check-label">
							<input type="radio" class="form-check-input" name="type" value="2" checked>
							Cash / Bank Out
						 </label>
					  </div>
					  <div class="form-check form-check-inline">
						 <label class="form-check-label">
							<input type="radio" class="form-check-input" name="type" value="3">
							Journal
						 </label>
					  </div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Debit : <span class="badge badge-success" id="tempdebit">0</span>
					&nbsp;
					Credit : <span class="badge badge-danger" id="tempcredit">0</span>
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()" style="display:none;"><i class="icon-cross3"></i> Cancel</button>
				<button type="button" class="btn bg-warning" id="btn_update" onclick="update()" style="display:none;"><i class="icon-pencil7"></i> Save</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form_new" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add New Purchase Request</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_new">
				   <div class="alert alert-danger" id="validation_alert_new" style="display:none;">
					  <ul id="validation_content_new"></ul>
				   </div>
				   <div class="row justify-content-center">
					  <div class="col-md-4">
						 <div class="form-group">
							<label>User :<sup class="text-danger">*</sup></label>
							<select name="user_id" id="user_id"></select>
						 </div>
					  </div>
					  <div class="col-md-4">
						 <div class="form-group">
							<label>Date :<sup class="text-danger">*</sup></label>
							<input type="date" name="date_android" id="date_android" class="form-control">
						 </div>
					  </div>
					  <div class="col-md-4">
						<div class="form-group">
						  <label>Item :<sup class="text-danger">*</sup></label>
						  <textarea name="item_android" id="item_android" class="form-control" placeholder="Enter item name" rows="1"></textarea>
						</div>
					  </div>
					  <div class="col-md-4">
						<div class="form-group">
						  <label>Total Needed :<sup class="text-danger">*</sup></label>
						  <input name="total_android" id="total_android" type="text" onkeyup="formatRupiah(this)" class="form-control" placeholder="Enter item name" rows="1">
						</div>
					  </div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_new" onclick="create_new()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form_update" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Update Status & Item Purchase Request</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row justify-content-center">
					<div class="col-md-8">
						<form id="form_data_update">
							<div class="alert alert-danger" id="validation_alert_update" style="display:none;">
								<ul id="validation_content_update"></ul>
						    </div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">To<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<span id="bill_to"></span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Item<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<textarea class="form-control" name="item" id="item" rows="5"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Date Request<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="date" class="form-control" name="date_request" id="date_request">
								</div>
							 </div>
							
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Nominal<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="nominal" id="nominal" onkeyup="formatRupiah(this)">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Branch :<span class="text-danger">*</span></label>
								<div class="col-lg-9">
									<select name="branch" id="branch" class="custom-select branch">
										@foreach (DB::table('company_entities')->get() as $company)
											<option value="{{$company->id}}">{{$company->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Status<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<select name="status" id="status" class="custom-select">
										<option value="">All Status</option>
										<option value="PEND">Pending</option>
										<option value="APPR" disabled>Approve</option>
										<option value="PAID">Paid</option>
										<option value="RCVD">Received</option>
										<option value="SUBM">Submit</option>
										<option value="RJCT">Rejected</option>
										<option value="DONE">Done</option>
									</select>
									<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
										<span class="font-weight-semibold">Information!</span> 
										Choose PAID, if you don't want the payment not shown in Accounting, but shown in Balance Cash & Bank.
									</div>
								</div>
							</div>
							<div class="form-group row d-none" id="reason-div">
								<label class="col-lg-3 col-form-label">Reject Reason<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<textarea class="form-control" name="reject_reason" id="reject_reason" rows="3" placeholder="Please describe your reason why rejecting this Purchase Request"></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3">Repeat / Fixed Bill</label>
								<div class="col-lg-9">
									<div class="form-check">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input-styled-primary" data-fouc onclick="switchFixed(this)" name="fixed_cost" id="fixed_cost">
											Check this if yes.
										</label>
									</div>
									<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
										<span class="font-weight-semibold">Info!</span>
										If you check this, this will create multiple purchase request with same nominal on each number of months inputted.
									</div>
								</div>
							</div>
							<div class="form-group row d-none fixed-class">
								<label class="col-lg-3">Number of months</label>
								<div class="col-lg-3">
									<input type="number" class="form-control" name="fixed_month" id="fixed_month" placeholder="Number of months" value="1">
								</div>
								<div class="col-lg-6">
									<i>The number of months this fixed cost will shown (starting next month from date PR).</i>
								</div>
							</div>
							<div class="form-group row d-none fixed-class">
								<label class="col-lg-3">Date Repeat (Every month)</label>
								<div class="col-lg-3">
									<input type="number" class="form-control" name="fixed_date" id="fixed_date" placeholder="Date repeat" value="1">
								</div>
								<div class="col-lg-6">
									<i>Date every month will shown repeatedly.</i>
								</div>
							</div>
						</form>
					</div>
				</div>
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()"><i class="icon-cross3"></i> Cancel</button>
				<button type="button" class="btn bg-warning" id="btn_update_status" onclick="update()"><i class="icon-pencil7"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form_pay" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add New Payment Purchase Request</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_pay">
				   <div class="alert alert-danger" id="validation_alert_pay" style="display:none;">
					  <ul id="validation_content_pay"></ul>
				   </div>
				   <div class="row justify-content-center">
					  <div class="col-md-12 text-center">
						<h1>ITEM : <span id="pay_item"></span></h1>
					  </div>
				   </div>
				   <div class="row justify-content-center">
					  <div class="col-md-12 text-center">
						<h1>TOTAL : <span id="pay_total"></span></h1>
					  </div>
				   </div>
				   <hr>
				   <h5 class="card-title"><b>Main Information</b></h5>
				   <div class="alert alert-info alert-styled-left alert-dismissible mt-3">
						<span class="font-weight-semibold">Information!</span> 
						<b>&nbsp;CHOOSE PAID PURCHASE STATUS FIRST, if you don't want the payment not shown in Accounting, but shown in Balance Cash & Bank.</b>
					</div>
				   <div class="row">
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Date Paid :<sup class="text-danger">*</sup></label>
							<input type="hidden" name="temppay" id="temppay" class="form-control">
							<input type="date" name="pay_date" id="pay_date" class="form-control">
						 </div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
						  <label>Branch :<span class="text-danger">*</span></label>
						  <select name="pay_branch" id="pay_branch" class="custom-select">
								<option value="1">PTA</option>
								<option value="2">SMB</option>
							</select>
						</div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
							<label>Cash & Bank Source :<span class="text-danger">*</span></label>
							<select name="pay_coa" id="pay_coa" class="custom-select" onchange="getBalanceCashBank(this);">
							   <option value="">-- Choose --</option>
							   @foreach($coa->where('parent_id',0)->whereIn('code',['1.000.00']) as $c)
									@if(count($c->child()) == 0)
										<option value="{{ $c->id }}">{{ $c->name }}</option>
									@else
										<optgroup label="{{ $c->name }}">
										  @foreach($c->child() as $bc)
											@if(count($bc->child()) == 0)
												<option value="{{ $bc->id }}">{{ $bc->name }}</option>
											@else
												<optgroup label="{{ $bc->name }}">
													@foreach($bc->child() as $bcc)
														@if(count($bcc->child()) == 0)
															<option value="{{ $bcc->id }}">{{ $bcc->name }}</option>
														@else
															<optgroup label="{{ $bcc->name }}">
																@foreach($bcc->child() as $bccc)
																	@if(count($bccc->child()) == 0)
																		<option value="{{ $bccc->id }}">{{ $bccc->name }}</option>
																	@endif
																@endforeach
															</optgroup>
														@endif
													@endforeach
												</optgroup>
											@endif
										  @endforeach
										</optgroup>
									@endif
							   @endforeach
							</select>
						</div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
						  <label>Total Pay :<sup class="text-danger">*</sup></label>
						  <input name="pay_nominal" id="pay_nominal" type="text" onkeyup="formatRupiah(this)" class="form-control">
						</div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
							<label>Proof :<sup class="text-danger">*</sup></label>
							<input type="file" class="form-input-styled" id="pay_file" name="pay_file" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
						</div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Giro/Check Code :</label>
							<input type="text" name="pay_code" id="pay_code" class="form-control" value="-">
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Giro/Check Due Date :</label>
							<input type="date" name="pay_due_date" id="pay_due_date" class="form-control">
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Note :</label>
							<input type="text" name="pay_note" id="pay_note" class="form-control" value="-">
						 </div>
					  </div>
					</div>
					<hr>
					<h5 class="card-title"><b>Journal Information</b></h5>
					<div class="row justify-content-center">
						<div class="col-md-8">
							<table class="table table-bordered">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th>Coa</th>
									   <th>Branch</th>
									   <th>Type</th>
									   <th>Nominal</th>
									</tr>
								</thead>
								<tbody id="data_journal"></tbody>
							</table>
						</div>
					</div>
					<hr>
					<h5 class="card-title"><b>Project Payment Purchase (optional)</b></h5>
					<div class="row">
						<div class="col-md-4">
						 <div class="form-group">
							<label>Purchase Order :<sup class="text-danger">*</sup></label>
							<select name="purchase_id" id="purchase_id" class="select2">
							   <option value="">-- None --</option>
							   @foreach($projectpurchase as $pp)
								  <option value="{{ $pp->id }}">{{ $pp->code }}</option>
							   @endforeach
							</select>
						 </div>
					  </div>
					  <div class="col-md-4">
						 <div class="form-group">
							<label>Type :<sup class="text-danger">*</sup></label>
							<select name="type" id="type" class="custom-select">
								<option value="2">Full Payment</option>
								<option value="1">Down Payment</option>
							   <!-- <option value="3">Other</option> -->
							</select>
						 </div>
					  </div>
					  <div class="col-md-4">
						 <div class="form-group">
							<label>Method (Giro/Check) :<sup class="text-danger">*</sup></label>
							<select name="giro" id="giro" class="custom-select">
								<option value="0" {{ old('giro') == 0 ? 'selected' : '' }}>Transfer/Cash</option>
								<option value="1" {{ old('giro') == 1 ? 'selected' : '' }}>Giro/Check</option>
								<!-- <option value="2" {{ old('giro') == 2 ? 'selected' : '' }}>Check</option> -->
							</select>
						 </div>
					  </div>
					</div>
				</form>
				<div class="row">
					<div class="col-md-12">
						<hr>
						<h3>List of All Payments</h3>
						<table class="table table-bordered">
							<thead class="table-secondary">
								<tr class="text-center">
								   <th>Date</th>
								   <th>Branch</th>
								   <th>Source</th>
								   <th>Nominal</th>
								   <th>Proof</th>
								   <th>Code</th>
								   <th>Due Date</th>
								   <th>Note</th>
								   <th>#<//th>
								</tr>
							</thead>
						 <tbody id="data_payment"></tbody>
						</table>
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Balance C&B : <span class="badge badge-primary" id="tempcashbank">0</span>
					<input type="hidden" id="tempcashbankval">
					&nbsp;
					Cash Adv : <span class="badge badge-success" id="tempcashbon">0</span>
					&nbsp;
					Real : <span class="badge badge-danger" id="tempreal">0</span>
					&nbsp;
					Balance : <span class="badge badge-info" id="tempchange">0</span>
					&nbsp;
					<button type="button" class="btn bg-warning" onclick="update_payment()" id="btn-update-payment"><i class="icon-cloud-upload"></i> Update Payment</button>
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_new" onclick="create_pay()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_payment" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add Multi Payment Purchase Request</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_multi">
				   <div class="alert alert-danger" id="validation_alert_multi" style="display:none;">
					  <ul id="validation_content_multi"></ul>
				   </div>
				   <h5 class="card-title"><b>Source Payment Information</b></h5>
				   <div class="row">
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Date Paid :<sup class="text-danger">*</sup></label>
							<input type="date" name="source_date" id="source_date" class="form-control">
						 </div>
					  </div>
					  
					  <div class="col-md-3">
						<div class="form-group">
							<label>Proof :<sup class="text-danger">*</sup></label>
							<input type="file" class="form-input-styled" id="source_file" name="source_file" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
						</div>
					  </div>
					  <div class="col-md-3">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <select name="source_branch" id="source_branch" class="custom-select">
									<option value="1">PTA</option>
									<option value="2">SMB</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Cash & Bank Source :<span class="text-danger">*</span></label>
								<select name="source_coa" id="source_coa" class="custom-select" onchange="getBalanceCashBankMulti(this);">
								   <option value="">-- Choose --</option>
								   @foreach($coa->where('parent_id',0)->whereIn('code',['1.000.00']) as $c)
										@if(count($c->child()) == 0)
											<option value="{{ $c->id }}">{{ $c->name }}</option>
										@else
											<optgroup label="{{ $c->name }}">
											  @foreach($c->child() as $bc)
												@if(count($bc->child()) == 0)
													<option value="{{ $bc->id }}">{{ $bc->name }}</option>
												@else
													<optgroup label="{{ $bc->name }}">
														@foreach($bc->child() as $bcc)
															@if(count($bcc->child()) == 0)
																<option value="{{ $bcc->id }}">{{ $bcc->name }}</option>
															@else
																<optgroup label="{{ $bcc->name }}">
																	@foreach($bcc->child() as $bccc)
																		@if(count($bccc->child()) == 0)
																			<option value="{{ $bccc->id }}">{{ $bccc->name }}</option>
																		@endif
																	@endforeach
																</optgroup>
															@endif
														@endforeach
													</optgroup>
												@endif
											  @endforeach
											</optgroup>
										@endif
								   @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Total Pay :<sup class="text-danger">*</sup></label>
								<input name="source_nominal" id="source_nominal" type="text" onkeyup="formatRupiah(this)" class="form-control" value="0">
							</div>
						</div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Giro/Check Code :</label>
							<input type="text" name="source_code" id="source_code" class="form-control" value="-">
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Giro/Check Due Date :</label>
							<input type="date" name="source_due_date" id="source_due_date" class="form-control">
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Note :</label>
							<input type="text" name="source_note" id="source_note" class="form-control" value="-">
						 </div>
					  </div>
					  
					</div>
					<hr>
					
					<div class="row justify-content-center">
						<div class="col-md-6">
							<h5 class="card-title"><b>Choose Purchase Request</b></h5>
							<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
								<span class="font-weight-semibold">Info!</span>
								&nbsp;Only <b>Approve, Paid, Received, Submit</b> PR can be added here.
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
									  <label>Purchase Request :<span class="text-danger">*</span></label>
									  <select name="purchase_request_id" id="purchase_request_id" onchange="getTotalPR(this)"></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Total Pay :<sup class="text-danger">*</sup></label>
										<input name="temp_purchase_request_nominal" id="temp_purchase_request_nominal" type="hidden">
										<input name="purchase_request_nominal" id="purchase_request_nominal" type="text" onkeyup="formatRupiah(this)" class="form-control" value="0">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<button type="button" class="btn bg-success col-12" onclick="addPurchaseRequest()"><i class="icon-plus22"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="row justify-content-center">
						<div class="col-md-8">
							<h3>List of Request</h3>
							<table class="table table-bordered">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th>Item</th>
									   <th>Total</th>
									   <th>Pay</th>
									   <th>#</th>
									</tr>
								</thead>
							 <tbody id="data_purchase"></tbody>
							</table>
						</div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Balance C&B : <span class="badge badge-primary" id="tempcashbankmulti">0</span>
					&nbsp;
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_new" onclick="create_multi_payment()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_journal" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Purchase Request Journal Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" id="journal_id">
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_report" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Payable Report From Purchase Request</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<h3>Choose branch and process</h3>
				<hr>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
						  <select name="filter_branch_cd" id="filter_branch_report" class="custom-select">
							 <option value="">Choose Branch</option>
							 <option value="1">PTA</option>
							 <option value="2">SMB</option>
						  </select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<button class="btn bg-success btn-block" onclick="getPayableReport()"><i class="icon-search4"></i> Process</button>
						</div>
					</div>
				</div>
				<hr>
				<h3>Result</h3>
				<hr>
				<div class="row">
					<div class="col-md-12" id="body_report">
						
					</div>
				</div>
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
	  
		select2ServerSide('#purchase_request_id', '{{ url("admin/select2/purchase_request_available") }}');
		
		$('#data_purchase').on('click', '#delete_data_purchase', function() {
			$(this).closest('tr').remove();
			countTotal();
		});
		
		$('#datatable_serverside tbody').on('click', 'tr', function () {
			$(this).toggleClass('selected');
			
			var arrId = [];
			
			$('#datatable_serverside tr.selected').each(function(){
				arrId.push($(this).find('.pick').text());
			});
			
			$('#filter_temp').val(arrId.join());
		});
	  
	  $("#datatable_serverside").on( "click", 'tbody tr .btn-pindah', function() {
		  var id = $(this).data('no'),tgl = $(this).data('datepaid'), nominal = $(this).data('nominal'), item = $(this).data('item'), branch = $(this).data('branch'), coa = $(this).data('coa'), coaname = $(this).data('coaname'), branchname = $(this).data('branchname');
		  
		  $('#code').val('PRF-' + id);
		  $('#date').val(tgl);
		  $('#nominal_detail').val(nominal);
		  $('#nominal_detail').keyup();
		  $('#description').val(item);
		  $('#note_detail').val(item);
		  $('#branch').val(branch);
		  
		  $('#data_content_credit').append(`
			<tr class="text-center">
			   <input type="hidden" name="coa_detail[]" value="` + coa + `">
			   <input type="hidden" name="type_detail[]" value="2">
			   <input type="hidden" name="branch_detail[]" value="` + branch + `">
			   <input type="hidden" name="note_detail[]" value="` + item + `">

			   <td class="align-middle">` + coaname + `</td>
			   <td class="align-middle">` + branchname + `</td>
			   <td class="align-middle">
				  <div class="form-group">
					 <input type="text" name="nominal_detail[]" data-mode="2" class="form-control" placeholder="0" value="` + formatRupiahIni(nominal) + `" onkeyup="formatRupiah(this);countDebit();">
				  </div>
			   </td>
			   <td class="align-middle">` + item + `</td>   
			   <td class="align-middle">
				  <button type="button" id="delete_data_content_credit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
			   </td>
			</tr>
		`);
		  
		  $('#modal_form').modal('toggle');
		  
			$('#modal_journal').on('hidden.bs.modal', function (e) {
				$('#journal_id').empty();
			});
	  });
	  
	  $("#datatable_serverside").on( "click", 'tbody tr .btn-pay', function() {
		  var id = $(this).data('id'),nominal = $(this).data('total'), item = $(this).data('item'), branch = $(this).data('branch'), tgl = $(this).data('date'), purchase = $(this).data('purchase'), cashbon = $(this).data('cashbon'), paid = $(this).data('paid');
		  
		  var change = parseFloat(cashbon.replaceAll(".", "").replaceAll(",",".")) > 0 ? parseFloat(cashbon.replaceAll(".", "").replaceAll(",",".")) - parseFloat(nominal.replaceAll(".", "").replaceAll(",",".")) : 0;
		  
		  if(change > 0){
			  $('#tempchange').addClass('badge-info');
			  $('#tempchange').removeClass('badge-danger');
			  $('#btn-update-payment').removeClass('d-none');
		  }else if(change < 0){
			  $('#tempchange').removeClass('badge-info');
			  $('#tempchange').addClass('badge-danger');
			  $('#btn-update-payment').addClass('d-none');
		  } else {
			  $('#btn-update-payment').addClass('d-none');
		  }
		  
		  $('#temppay').val(id);
		  $('#pay_date').val(tgl);
		  $('#pay_nominal').val(nominal);
		  $('#pay_total').text(nominal);
		  $('#pay_item').html(item);
		  $('#pay_branch').val(branch);
		  $('#tempcashbon').text(cashbon);
		  $('#tempreal').text(nominal);
		  $('#tempchange').text(change < 0 ? '-' + formatRupiahIni(change.toFixed(2).toString().replace('.',',')) : formatRupiahIni(change.toFixed(2).toString().replace('.',',')));
		  
		  $.ajax({
			 url: '{{ url("admin/finance/purchase_request/get_payment") }}',
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
				
				$('#purchase_id').val(purchase).trigger('change');
				
				$('#data_payment').empty();
				
				if(response.length > 0){
				
					$.each(response, function(i, val) {
						$('#data_payment').append(`
							<tr class="text-center row` + val.id + `">
								<td>` + val.date + `</td>
								<td>` + val.branch + `</td>
								<td>` + val.source + `</td>
								<td>` + val.nominal + `</td>
								<td>` + val.proof + `</td>
								<td>` + val.code + `</td>
								<td>` + val.due_date + `</td>
								<td>` + val.note + `</td>
								<td>
									<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyPay(` + val.id + `)"><i class="icon-trash-alt"></i></button>
								</td>
							</tr>
						`);
					});
					
				}else{
					$('#data_payment').append(`
						<tr class="text-center align-middle">
							<td class="bg-danger" colspan="9">There is no payment data.</td>
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
		  
		  $('#modal_form_pay').modal('toggle');
	  });
	  
	  $("#datatable_serverside").on( "click", 'tbody tr .btn-update', function() {
		  var nominal = $(this).data('total'), id = $(this).data('id'), status = $(this).data('status'), datepaid = $(this).data('datepaid'), date = $(this).data('date'), coa = $(this).data('coa'), bill = $(this).data('bill'), branch = $(this).data('branch');
		  
		  $('#btn_update_status').attr('onclick', 'update(' + id + ')');
		  
		  $('#bill_to').html(bill);
		  $('#item').val($(this).data('item'));
		  $('#nominal').val(nominal);
		  $('#give').val($(this).data('give'));
		  $('#type_coa').val($(this).data('coa')).trigger('change');
		  $('#real').val($(this).data('real'));
		  $('#revision').val($(this).data('rev'));
		  $('#status').val(status);
		  $('.branch').val(branch);
		  $('#date_paid').val(datepaid);
		  $('#date').val(datepaid);
		  $('#date_request').val(date);
		  $('#coa_update').val(coa).trigger('change');
		  
		  if($(this).data('link')){
			  $('#purchase_id').val($(this).data('linkid')).trigger('change');
		  }
		  
		  $('#modal_form_update').modal('toggle');
	  });
	  
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#date').val('');
			$('#nominal_detail').val('');
			$('#code').val('');
			$('#description').val('');
		    $('#note_detail').val('');
			$('#coa_id').val('').trigger('change');
			$('#data_content_debit').empty();
			$('#data_content_credit').empty();
		});
		
		$('#modal_form_new').on('hidden.bs.modal', function (e) {
			$('#login_android').val(null).trigger('change');
			$('#date_android').val('');
			$('#item_android').val('');
			$('#total_android').val('');
		});
		
		$('#modal_form_update').on('hidden.bs.modal', function (e) {
			$('#nominal').val('');
			$('#status').val('');
			$('#file').val('');
			$('#date_paid').val(null);
			$('#date_request').html('');
			$('#purchase_id').val(null).trigger('change');
			$('#coa_id').val(null).trigger('change');
		});
		
		$('#modal_form_pay').on('hidden.bs.modal', function (e) {
			$('#form_data_pay')[0].reset();
			$('#temppay').val('');
			$('#tempreal,#tempcashbon,#tempchange').text('0');
		});
		
		$('#modal_report').on('hidden.bs.modal', function (e) {
			$('#filter_branch_report').val('');
			$('#body_report').html('');
		});
		
		$('#data_content_debit').on('click', '#delete_data_content_debit', function() {
			$(this).closest('tr').remove();
			countDebit();
		});
		$('#data_content_credit').on('click', '#delete_data_content_credit', function() {
			$(this).closest('tr').remove();
			countDebit();
		});
		
		$('#status').on('change', function() {
			if($(this).val() == 'RJCT'){
				$('#reason-div').removeClass('d-none');
			}else{
				$('#reason-div').addClass('d-none');
			}
		});
		
		select2ServerSide('#user_id', '{{ url("admin/select2/user") }}');
		
		$('.sidebar-main-toggle').click();
	});
	
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
	
	function selectAllRow(){
		$('#datatable_serverside tbody tr').trigger('click');
	}
	
	function addPurchaseRequest(){
		if($('#purchase_request_id').val() !== ''){
			
			var adapr = false;

			$('input[name^="purchase_detail"]').each(function(){
				if($(this).val() == $('#purchase_request_id').val()){
					adapr = true;
				}
			});
			
			if(adapr == false){
				$('#data_purchase').append(`
					<tr class="text-center">
					   <input type="hidden" name="purchase_detail[]" value="` + $('#purchase_request_id').val() + `">

					   <td class="align-middle">` + $('#purchase_request_id option:selected').text() + `</td>
					   <td class="align-middle">
						` + $('#temp_purchase_request_nominal').val() + `
					   </td>
					   <td class="align-middle">
						  <div class="form-group" style="margin-bottom: 0rem;">
							 <input type="text" name="purchase_nominal_detail[]" class="form-control" placeholder="0" value="` + $('#purchase_request_nominal').val() + `" onkeyup="formatRupiah(this);countTotal();" style="width:125px;">
						  </div>
					   </td>
					   <td class="align-middle">
						  <button type="button" id="delete_data_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
					   </td>
					</tr>
				`);
				
				$('#purchase_request_id').val(null).trigger('change');
				$('#purchase_request_nominal').val('0');
				
				countTotal();
			}else{
				notif('error', 'bg-danger', 'Ups. This purchase request already added to table.');
			}
			
		}
	}
	
	function countTotal(){
		var total = 0;
		
		$('input[name^="purchase_nominal_detail"]').each(function(){
			total += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
		});
		
		$('#source_nominal').val(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
	}
	
	function create_multi_payment() {
		var totalpay = 0;
		$('input[name^="purchase_nominal_detail"]').each(function(){
			totalpay += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
		});
		var balancecb = parseFloat($('#tempcashbankmulti').text().replaceAll(".", "").replaceAll(",","."));
		
		if((balancecb - totalpay) >= 0){
		  $.ajax({
			 url: '{{ url("admin/finance/purchase_request/add_multi_payment") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data_multi')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_multi').hide();
				$('#validation_content_multi').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   $('#form_data_multi')[0].reset();
				   $('#data_purchase').empty();
				   $('#modal_payment').modal('toggle');
				   $('#datatable_serverside').DataTable().ajax.reload(null, false);
				   notif('success', 'bg-success', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_multi').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_multi').append(`
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
			 }
		  });
		}else{
			notif('error', 'bg-danger', 'Ups. Hayo, your saldo cash/bank is not enough.');
		}
	}
	
	function update_payment() {
		var notyConfirm = new Noty({
		 theme: 'limitless',
		 text: '<h6 class="font-weight-bold mb-3">Are you sure you want to update payment?</h6><label>All payment will be replaced with real nominal.</label>',
		 timeout: false,
		 modal: true,
		 layout: 'center',
		 closeWith: 'button',
		 type: 'confirm',
		 buttons: [
			Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
			   notyConfirm.close();
			}),
			Noty.button('<i class="icon-checkmark"></i>', 'btn bg-success ml-1', function() {
				$.ajax({
				 url: '{{ url("admin/finance/purchase_request/update_payment") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : $('#temppay').val() },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					if(response.status == 200) {
					   notif('success', 'bg-success', response.message);
					} else {
					   notif('error', 'bg-danger', response.message);
					}
					notyConfirm.close();
				 },
				 error: function() {
					$('.modal-body').scrollTop(0);
					loadingClose('.modal-content');
				 }
				});
			})
		 ]
	  }).show();
	}
	
	function getTotalPR(element){
		if(element.value !== ''){
			var id = element.value;
			
			$.ajax({
				 url: '{{ url("admin/finance/purchase_request/get_purchase_request_total") }}',
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
					
					$('#purchase_request_nominal').val(response.total);
					$('#temp_purchase_request_nominal').val(response.total);
					
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
	}
	
	function getJournalInfo(id){
		$.ajax({
			url: '{{ url("admin/finance/purchase_request/get_journal") }}',
			type: 'GET',
			async: false,
			data: {
				id: id
			},
			success: function(response) {
				
				$('#data_journal').empty();
				if(response.length > 0){
					
					$.each(response, function(i, val) {
						$('#data_journal').append(`
							<tr>
								<td class="text-center">` + val.coa + `</td>
								<td class="text-center">` + val.branch + `</td>
								<td class="text-center">` + val.type + `</td>
								<td class="text-center">` + val.nominal + `</td>
							</tr>
						`);
					});
				}else{
					$('#data_journal').append(`
						<tr>
							<td class="text-center" colspan="4">Data not found</td>
						</tr>
					`);
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
	}
	
	function create_pay() {
		var balance = parseFloat($('#tempcashbank').text().replaceAll(".", "").replaceAll(",","."));
		var pay = parseFloat($('#pay_nominal').val().replaceAll(".", "").replaceAll(",","."));
		var coa_id =  $('#tempcashbankval').val();
		if(coa_id == 230 || (balance - pay) >= 0){
			$.ajax({
			 url: '{{ url("admin/finance/purchase_request/add_payment") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data_pay')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_pay').hide();
				$('#validation_content_pay').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
					$('#purchase_id').val('').trigger('reset');
					$('#form_data_pay')[0].reset();
				  
				   if(response.count == 1){
					   $('#data_payment').empty();
				   }
					$('#data_payment').append(`
						<tr class="text-center row` + response.result.id + `">
							<td>` + response.result.date + `</td>
							<td>` + response.result.branch + `</td>
							<td>` + response.result.source + `</td>
							<td>` + response.result.nominal + `</td>
							<td>` + response.result.proof + `</td>
							<td>` + response.result.code + `</td>
							<td>` + response.result.due_date + `</td>
							<td>` + response.result.note + `</td>
							<td>
								<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyPay(` + response.result.id + `)"><i class="icon-trash-alt"></i></button>
							</td>
						</tr>
					`);
				   $('#datatable_serverside').DataTable().ajax.reload(null, false);
				   notif('success', 'bg-success', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_pay').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_pay').append(`
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
			notif('error', 'bg-danger', 'Ups. Hayo, your saldo cash/bank is not enough.');
		}
	}
	
	function destroyPay(id) {
	  var notyConfirm = new Noty({
		 theme: 'limitless',
		 text: '<h6 class="font-weight-bold mb-3">Are you sure you want to delete payment?</h6><label>Deleted data can no longer be recovered.</label>',
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
				  url: '{{ url("admin/finance/purchase_request/delete_payment") }}',
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
						 if(response.count == 0){
							$('#data_payment').append(`
								<tr class="text-center align-middle">
									<td class="bg-danger" colspan="9">There is no payment data.</td>
								</tr>
							`);
						 }
						$('.row' + id).remove();
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
	
	function filter(param = null) {
		if(param == 'reset') {
			resetFilter();
		}

		loadDataTable();
		return false;
	}
	
	function resetFilter() {
		$('#filter_start_date').val(null);
		$('#filter_finish_date').val(null);
	}
	
	function addContent() {
      let coa_id   = $('#coa_id option:selected');
      let nominal_detail = $('#nominal_detail');
      let note_detail    = $('#note_detail');
	  let branch = $('#branch option:selected');
	  var mode = $('input[name="mode"]:checked').val();

      if(coa_id.val() && nominal_detail.val() && note_detail.val()) {
		 if(mode == '1'){
			$('#data_content_debit').append(`
				<tr class="text-center">
				   <input type="hidden" name="coa_detail[]" value="` + coa_id.val() + `">
				   <input type="hidden" name="type_detail[]" value="` + mode + `">
				   <input type="hidden" name="branch_detail[]" value="` + branch.val() + `">
				   <input type="hidden" name="note_detail[]" value="` + note_detail.val() + `">

				   <td class="align-middle">` + coa_id.text() + `</td>
				   <td class="align-middle">` + branch.text() + `</td>
				   <td class="align-middle">
					  <div class="form-group">
						 <input type="text" name="nominal_detail[]" data-mode="` + mode + `" class="form-control" placeholder="0" value="` + nominal_detail.val() + `" onkeyup="formatRupiah(this);countDebit();">
					  </div>
				   </td>
				   <td class="align-middle">` + note_detail.val() + `</td>   
				   <td class="align-middle">
					  <button type="button" id="delete_data_content_debit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				   </td>
				</tr>
			`);
		 }else{
			$('#data_content_credit').append(`
				<tr class="text-center">
				   <input type="hidden" name="coa_detail[]" value="` + coa_id.val() + `">
				   <input type="hidden" name="type_detail[]" value="` + mode + `">
				   <input type="hidden" name="branch_detail[]" value="` + branch.val() + `">
				   <input type="hidden" name="note_detail[]" value="` + note_detail.val() + `">

				   <td class="align-middle">` + coa_id.text() + `</td>
				   <td class="align-middle">` + branch.text() + `</td>
				   <td class="align-middle">
					  <div class="form-group">
						 <input type="text" name="nominal_detail[]" data-mode="` + mode + `" class="form-control" placeholder="0" value="` + nominal_detail.val() + `" onkeyup="formatRupiah(this);countDebit();">
					  </div>
				   </td>
				   <td class="align-middle">` + note_detail.val() + `</td>   
				   <td class="align-middle">
					  <button type="button" id="delete_data_content_credit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				   </td>
				</tr>
			`);
		 }
		 
		 countDebit();
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
	}
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
		 /* searchDelay: 2000, */
         order: [[3, 'desc']],
         ajax: {
            url: '{{ url("admin/finance/purchase_request/datatable") }}',
            type: 'GET',
            data: {
               type: $('#filter_type').val(),
			   user_id: $('#filter_user_id').val(),
			   start_date: $('#filter_start_date').val(),
               finish_date: $('#filter_finish_date').val(),
			   branch: $('#filter_branch').val()
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
		 "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
         columns: [
			{ name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'name', className: 'text-center align-middle' },
            { name: 'tgl', className: 'text-center align-middle' },
			{ name: 'tgldue', className: 'text-center align-middle' },
			{ name: 'branch', className: 'text-center align-middle' },
			{ name: 'bill_to', className: 'text-center align-middle' },
            { name: 'item', className: 'text-center align-middle' },
			{ name: 'cash_adv', className: 'text-right align-middle' },
            { name: 'total', className: 'text-right align-middle' },
            { name: 'status', searchable: false, className: 'text-center align-middle' },
			{ name: 'progress', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'photo', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
         ]
      }); 
	}
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/finance/purchase_request/row_detail") }}',
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
   
	function create() {
		var debit = 0, credit = 0;
		
		$('input[name^="nominal_detail"]').each(function(){
			if($(this).data('mode') == '1'){
				debit = debit + parseInt($(this).val().replace(".", "").replace(".", "").replace(".", ""));
			}else if($(this).data('mode') == '2'){
				credit = credit + parseInt($(this).val().replace(".", "").replace(".", "").replace(".", ""));
			}
		});
		
		if((debit - credit) == 0){
			$.ajax({
			 url: '{{ url("admin/finance/cash_bank/create") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: $('#form_data').serialize(),
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
			notif('error', 'bg-danger', 'Your input is not balanced.');
		}
	}
	
	function countDebit(){
		var debit = 0, credit = 0;
		
		$('input[name^="nominal_detail"]').each(function(){
			if($(this).data('mode') == '1'){
				debit = debit + parseFloat($(this).val().replace(".", "").replace(".", "").replace(".", "").replace(",","."));
			}else if($(this).data('mode') == '2'){
				credit = credit + parseFloat($(this).val().replace(".", "").replace(".", "").replace(".", "").replace(",","."));
			}
		});
		
		$('#tempdebit').html(formatRupiahIni(debit.toFixed(2).toString().replace('.',',')));
		$('#tempcredit').html(formatRupiahIni(credit.toFixed(2).toString().replace('.',',')));
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
	
	function create_new() {
		$.ajax({
		 url: '{{ url("admin/finance/cash_bank/create_prf") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: $('#form_data_new').serialize(),
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_new').hide();
			$('#validation_content_new').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
			   success_new();
			   notif('success', 'bg-success', response.message);
			} else if(response.status == 422) {
			   $('#validation_alert_new').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_new').append(`
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
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function success_new() {
      $('#modal_form_new').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function success() {
	  $('#modal_form_update').modal('hide');
	  $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function update(id) {
		if($('#status').val() == 'DONE'){
			var notyConfirm = new Noty({
			 theme: 'limitless',
			 text: '<h6 class="font-weight-bold mb-3">Are you sure you want to update status PR to Done?</h6><label>Done data cannot be reverted back.</label>',
			 timeout: false,
			 modal: true,
			 layout: 'center',
			 closeWith: 'button',
			 type: 'confirm',
			 buttons: [
				Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
				   notyConfirm.close();
				}),
				Noty.button('<i class="icon-checkmark"></i>', 'btn bg-success ml-1', function() {
				   $.ajax({
					 url: '{{ url("admin/finance/purchase_request/update_status") }}' + '/' + id,
					 type: 'POST',
					 dataType: 'JSON',
					 data: new FormData($('#form_data_update')[0]),
					 contentType: false,
					 processData: false,
					 cache: true,
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						$('#validation_alert_update').hide();
						$('#validation_content_update').html('');
						loadingOpen('.modal-content');
					 },
					 success: function(response) {
						loadingClose('.modal-content');
						if(response.status == 200) {
							notyConfirm.close();
							success();
							notif('success', 'bg-success', response.message);
						} else if(response.status == 422) {
						   $('#validation_alert_update').show();
						   $('.modal-body').scrollTop(0);
						   notif('warning', 'bg-warning', 'Validation');
						   
						   $.each(response.error, function(i, val) {
							  $.each(val, function(i, val) {
								 $('#validation_content_update').append(`
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
				})
			 ]
			}).show();
		}else{
			$.ajax({
			 url: '{{ url("admin/finance/purchase_request/update_status") }}' + '/' + id,
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data_update')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_update').hide();
				$('#validation_content_update').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   success();
				   notif('success', 'bg-success', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_update').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_update').append(`
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
	}
	
	function approvePurchase(id){
		var notyConfirm = new Noty({
		 theme: 'limitless',
		 text: '<h6 class="font-weight-bold mb-3">Are you sure you want to approve purchase request?</h6><label>Approved data cannot be reverted back, you must delete this purchase request.</label>',
		 timeout: false,
		 modal: true,
		 layout: 'center',
		 closeWith: 'button',
		 type: 'confirm',
		 buttons: [
			Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
			   notyConfirm.close();
			}),
			Noty.button('<i class="icon-checkmark"></i>', 'btn bg-success ml-1', function() {
			   $.ajax({
				  url: '{{ url("admin/finance/purchase_request/approve") }}',
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
						notif('success', 'bg-success', 'Hooray! success.');
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
	
	function rejectPurchase(id){
		var notyConfirm = new Noty({
		 theme: 'limitless',
		 text: '<h6 class="font-weight-bold mb-3">Are you sure you want to reject purchase request?</h6><label>Rejected data cannot be reverted back, you must delete this purchase request.</label>',
		 timeout: false,
		 modal: true,
		 layout: 'center',
		 closeWith: 'button',
		 type: 'confirm',
		 buttons: [
			Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
			   notyConfirm.close();
			}),
			Noty.button('<i class="icon-checkmark"></i>', 'btn bg-success ml-1', function() {
			   $.ajax({
				  url: '{{ url("admin/finance/purchase_request/reject") }}',
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
						notif('success', 'bg-success', 'Hooray! success.');
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
	
	function switchFixed(element){
		if($(element).is(':checked')){
			$('.fixed-class').removeClass('d-none');
		}else{
			$('.fixed-class').addClass('d-none');
		}
	}
	
	function showJournal(id){
		$.ajax({
		  url: '{{ url("admin/finance/purchase_request/show_journal") }}',
		  type: 'POST',
		  dataType: 'JSON',
		  data: {
			 id: id
		  },
		  headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  },
		  success: function(response) {
			 $('#modal_journal').modal('toggle');
			 $('#journal_id').html(response.content);
		  },
		  error: function() {
			 swalInit.fire({
				title: 'Server Error',
				text: 'Please contact developer',
				type: 'error'
			 });
		  }
	   });
	}
	
	function getPayableReport(){
		if($('#filter_branch_report').val()){
			$.ajax({
			  url: '{{ url("admin/finance/purchase_request/show_payable_report") }}',
			  type: 'POST',
			  dataType: 'JSON',
			  data: {
				 branch : $('#filter_branch_report').val()
			  },
			  headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			  },
			  beforeSend: function() {
				 loadingOpen('.modal-content');
			  },
			  success: function(response) {
				 $('#body_report').html(response.content);
				 loadingClose('.modal-content');
				 $(".modal-content").animate({
					scrollTop: $(
					  '.modal-content').get(0).scrollHeight
				 }, 500);
			  },
			  error: function() {
				 swalInit.fire({
					title: 'Server Error',
					text: 'Please contact developer',
					type: 'error'
				 });
			  }
		   });
		}else{
			notif('error', 'bg-danger', 'Please choose branch');
		}
	}
	
	function getBalanceCashBank(element){
		if($(element).val()){
			$.ajax({
			  url: '{{ url("admin/finance/purchase_request/get_balance_cash_bank") }}',
			  type: 'POST',
			  dataType: 'JSON',
			  data: {
				 branch : $('#pay_branch').val(),
				 coa_id : $(element).val()
			  },
			  headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			  },
			  beforeSend: function() {
				 loadingOpen('.modal-content');
			  },
			  success: function(response) {
				 loadingClose('.modal-content');
				 $('#tempcashbank').text(response.nominal);
				 $('#tempcashbankval').val(response.coa_id);
			  },
			  error: function() {
				 swalInit.fire({
					title: 'Server Error',
					text: 'Please contact developer',
					type: 'error'
				 });
			  }
		   });
		}
	}
	
	function getBalanceCashBankMulti(element){
		if($(element).val()){
			$.ajax({
			  url: '{{ url("admin/finance/purchase_request/get_balance_cash_bank") }}',
			  type: 'POST',
			  dataType: 'JSON',
			  data: {
				 branch : $('#pay_branch').val(),
				 coa_id : $(element).val()
			  },
			  headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			  },
			  beforeSend: function() {
				 loadingOpen('.modal-content');
			  },
			  success: function(response) {
				 loadingClose('.modal-content');
				 $('#tempcashbankmulti').text(response.nominal);
		
			  },
			  error: function() {
				 swalInit.fire({
					title: 'Server Error',
					text: 'Please contact developer',
					type: 'error'
				 });
			  }
		   });
		}
	}
</script>