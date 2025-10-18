<style>
	.table-hover tbody tr:hover {
		background-color: rgb(68 212 223 / 70%);
	}
	
	#datatable_serverside tbody tr.selected {
		background-color: green;
		color:white;
	}
</style>
@php
	function random_color_part() {
		$dt = '';
		for($o=1;$o<=3;$o++)
		{
			$dt .= str_pad( dechex( mt_rand( 0, 127 ) ), 2, '0', STR_PAD_LEFT);
		}
		return $dt;
	}
@endphp
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Balance Cash & Banks</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="filter()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled mr-2 btn-labeled-left" onclick="cancel()" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add
					</button>
					<button type="button" class="btn bg-danger btn-labeled mr-2 btn-labeled-left" data-toggle="modal" data-target="#modal_transfer">
						<b><i class="icon-loop"></i></b> Cash/Bank Exchange
					</button>
					@if(in_array('4', session('bo_role')) || in_array('1', session('bo_role')))
					<button type="button" class="btn bg-info btn-labeled btn-labeled-left" onclick="approved()" data-toggle="modal" data-target="#modal_approved">
						<b><i class="icon-clipboard"></i></b> Un-Transferred CB
					</button>
					@endif
					<a class="btn btn-secondary btn-labeled btn-labeled-left" data-toggle="collapse" href="#collapse-link-collapsed" style="margin-left:10px;">
						<b><i class="icon-clipboard2"></i></b> Balance
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Balance Cash & Banks</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Filter</h5>
			</div>
         <div class="card-body">
			<div class="collapse {{ $start_date ? 'show' : '' }}" id="collapse-link-collapsed">
				<div class="mt-3">
					<div class="row">
						<div class="col-md-12">
							<h3 class="card-title text-center">Total Balance Cash & Banks</h3>
							<hr>
							<form action="" method="get" id="form-balance">
								<div class="form-group">
									<label>Choose Date Balance :</label>
									<div class="input-group-prepend">
										<input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}">
										<span class="input-group-text">to</span>
										<input type="date" name="finish_date" id="finish_date" class="form-control" value="{{ $finish_date }}">
									</div>
								</div>
								<div class="form-group text-center">
									<button type="submit" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
									<a href="{{ url('admin/finance/balance_cash_bank') }}" class="btn bg-danger"><i class="icon-sync"></i></a>
								</div>
							</form>
							<hr>
							<div class="row">
								<h1 class="col-md-6">PTA</h1>
								<h1 class="col-md-6 text-right">Total Cash & Bank IDR <span id="totalsurabaya">0</span></h1>
							</div>
							<hr>
							<div class="row">
								@php
									$totalsby = 0;
								@endphp
								@foreach($balancecoa as $row)
									@php
										$totalsby += $row->getBalanceCashBank('1',$start_date,$finish_date);
									@endphp
									<div class="col-md-4 p-3">
										<div class="card" style="height:150px !important;">
											<div class="card-body" style="background-color:#{{ random_color_part() }} !important;height:90px !important;color:white;border-radius:15px;border:1px solid black;">
												<div class="d-flex float-right">
													<h3 class="font-weight-semibold mb-0" style="font-size:25px;">IDR {{ number_format($row->getBalanceCashBank('1',$start_date,$finish_date),2,',','.') }}</h3>
												</div>
												<div class="mt-4">
													<div class="font-weight-semibold" style="font-size:12px;">
														Balance {{ $row->name }} PTA
														<h6 class="font-weight-semibold mb-0">(Real IDR {{ number_format($row->getBalanceCashBankReal('1',$start_date,$finish_date),2,',','.') }})</h6>
													</div>
												</div>
												<div class="float-right">
													<button class="btn btn-primary btn-sm mr-2" onclick="showDetail({{ $row->id }},'1','{{ $row->name }} Branch PTA')" data-popup="tooltip" title="See Details"><i class="icon-file-spreadsheet"></i></button>
													<button class="btn btn-warning btn-sm" onclick="uploadNews({{ $row->id }},'1','{{ $row->name }}')" data-popup="tooltip" title="Upload News"><i class="icon-file-plus"></i></button>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>
						<div class="col-md-12">
							<hr>
							<div class="row">
								<h1 class="col-md-6">SMB</h1>
								<h1 class="col-md-6 text-right">Total Cash & Bank IDR <span id="totaljakarta">0</span></h1>
							</div>
							<hr>
							<div class="row">
								@php
									$totaljkt = 0;
								@endphp
								@foreach($balancecoa as $row)
									@php
										$totaljkt += $row->getBalanceCashBank('2',$start_date,$finish_date);
									@endphp
									<div class="col-md-4 p-3">
										<div class="card" style="height:150px !important;">
											<div class="card-body" style="background-color:#{{ random_color_part() }} !important;height:90px !important;color:white;border-radius:15px;border:1px solid black;">
												<div class="d-flex float-right">
													<h3 class="font-weight-semibold mb-0" style="font-size:25px;">IDR {{ number_format($row->getBalanceCashBank('2',$start_date,$finish_date),2,',','.') }}</h3>
												</div>
												<div class="mt-4">
													<div class="font-weight-semibold" style="font-size:12px;">
														Balance {{ $row->name }} SMB
														<h6 class="font-weight-semibold mb-0">(Real IDR {{ number_format($row->getBalanceCashBankReal('2',$start_date,$finish_date),2,',','.') }})</h6>
													</div>
												</div>
												<div class="float-right">
													<button class="btn btn-primary btn-sm mr-2" onclick="showDetail({{ $row->id }},'2','{{ $row->name }} Branch SMB')" data-popup="tooltip" title="See Details"><i class="icon-file-spreadsheet"></i> See details</button>
													<button class="btn btn-warning btn-sm" onclick="uploadNews({{ $row->id }},'2','{{ $row->name }}')" data-popup="tooltip" title="Upload News"><i class="icon-file-plus"></i></button>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
			<form id="form_submit" method="POST" action="{{ url('admin/finance/balance_cash_bank/print') }}" target="_blank">
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
							<label>User :</label>
							<select name="filter_user_id" id="filter_user_id"></select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Type :</label>
							<select name="filter_type" id="filter_type" class="form-control">
								<option value="">All</option>
								<option value="IN">IN</option>
								<option value="OUT">OUT</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Branch :</label>
							<select name="filter_branch" id="filter_branch" class="form-control">
								<option value="">All</option>
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
         </div>
      </div>
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Balance Cash & Banks</h5><button onclick="selectAllRow()" class="btn btn-primary float-right">(Un) Select All Rows</button>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
                        <th>User</th>
						<th>Branch</th>
						<th>Nominal</th>
                        <th>Type</th>
                        <th>Date</th>
						<th>Note</th>
						<th>Cash/Bank</th>
						<th>Coa</th>
						<th>Reference</th>
                        <th>Proof</th>
                        <th>Action</th>
                     </tr>
                  </thead>
				  <tfoot align="right">
					<tr>
						<th></th>
						<th></th>
						<th style="font-size:20px;font-weight:800;">Total</th>
						<th style="font-size:20px;font-weight:800;"></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				  </tfoot>
               </table>
            </div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form_cb" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Convert to Cash & Bank</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data">
				   <div class="alert alert-danger" id="validation_alert_cb" style="display:none;">
					  <ul id="validation_content_cb"></ul>
				   </div>
					<h5 class="card-title"><b>Main Information</b></h5>
				   <div class="row">
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Code :<sup class="text-danger">*</sup></label>
							<input type="text" name="code" id="code" class="form-control" placeholder="Enter code" readonly>
							<span class="badge d-block badge-danger form-text">Auto Generate</span>
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Date :<sup class="text-danger">*</sup></label>
							<input type="date" name="date" id="date" class="form-control">
						 </div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
						  <label>Description :<sup class="text-danger">*</sup></label>
						  <textarea name="description" id="description" class="form-control" placeholder="Enter description" rows="1"></textarea>
						</div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Proof :</label>
							<div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
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
						  <select name="branch" id="branch" class="custom-select">
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
							<input type="radio" class="form-check-input" name="type" value="1" checked>
							Cash / Bank In
						 </label>
					  </div>
					  <div class="form-check form-check-inline">
						 <label class="form-check-label">
							<input type="radio" class="form-check-input" name="type" value="2">
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
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_cb" onclick="create_cb()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>

	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Cash/Bank In/Out</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_bpc">
					<div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
					</div>
					<div class="row">
						<div class="col-md-3">
						 <div class="form-group">
							<label>Nominal :<sup class="text-danger">*</sup></label>
							<input type="hidden" name="temp_bpc" id="temp_bpc">
							<input type="text" name="nominal_bpc" id="nominal_bpc" class="form-control" placeholder="0" onkeyup="formatRupiah(this)">
						 </div>
						</div>
						<div class="col-md-3">
						 <div class="form-group">
						  <label>Cash or Bank :<span class="text-danger">*</span></label>
						  <select name="type_cb" id="type_cb" class="custom-select">
							 <option value="">-- Choose --</option>
							 <option value="CASH">Cash</option>
							 <option value="BANK">Bank</option>
						  </select>
						</div>
						</div>
						<div class="col-md-3">
						 <div class="form-group">
						  <label>Type :<span class="text-danger">*</span></label>
						  <select name="type_bpc" id="type_bpc" class="custom-select">
							 <option value="IN">In</option>
							 <option value="OUT">Out</option>
						  </select>
						</div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
							  <label>COA In = To / Out = From :<span class="text-danger">*</span></label>
							  <select name="type_coa" id="type_coa" class="select2">
								   <option value="">-- Choose Cash or Bank First --</option>
							  </select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <select name="branch_bpc" id="branch_bpc" class="custom-select">
								 <option value="1">PTA</option>
								 <option value="2">SMB</option>
							  </select>
						   </div>
						  </div>
						<div class="col-md-3">
						 <div class="form-group">
							<label>Date :<sup class="text-danger">*</sup></label>
							<input type="date" name="date_bpc" id="date_bpc" class="form-control">
						 </div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
								<label>Proof :</label>
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file_bpc" name="file_bpc" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							 </div>
						  </div>
						<div class="col-md-3">
						 <div class="form-group">
							<label>Note :<sup class="text-danger">*</sup></label>
							<textarea type="text" name="note_bpc" id="note_bpc" class="form-control" placeholder="Enter note" rows="1"></textarea>
						 </div>
						</div>
					</div>
				</form>
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
	
	<div class="modal fade" id="modal_upload" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabelUpload"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_bpc">
					<div class="alert alert-danger" id="validation_alert_upload" style="display:none;">
					  <ul id="validation_content_upload"></ul>
					</div>
					<div class="row">
						<div class="col-md-3">
						 <div class="form-group">
							<label>Month :<sup class="text-danger">*</sup></label>
							<input type="hidden" name="tempcoa" id="tempcoa" class="form-control">
							<input type="month" name="month_upload" id="month_upload" class="form-control">
						 </div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
								<label>Proof :</label>
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file_upload" name="file_upload" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							 </div>
						</div>
						<div class="col-md-3">
						 <div class="form-group">
							<label>Description :<sup class="text-danger">*</sup></label>
							<textarea type="text" name="description_upload" id="description_upload" class="form-control" placeholder="Enter note" rows="1"></textarea>
						 </div>
						</div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="createUpload();"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_transfer" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Cash / Bank Exchange</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_transfer">
					<div class="alert alert-danger" id="validation_alert_transfer" style="display:none;">
					  <ul id="validation_content_transfer"></ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>Main Information</h3>
							<hr>
						</div>
						<div class="col-md-3">
						 <div class="form-group">
							<label>Date :<sup class="text-danger">*</sup></label>
							<input type="date" name="date_bpc_transfer" id="date_bpc_transfer" class="form-control">
						 </div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
								<label>Proof :</label>
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file_bpc_transfer" name="file_bpc_transfer" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							 </div>
						</div>
						<div class="col-md-6">
						 <div class="form-group">
							<label>Note :<sup class="text-danger">*</sup></label>
							<textarea type="text" name="note_bpc_transfer" id="note_bpc_transfer" class="form-control" placeholder="Enter note" rows="1"></textarea>
						 </div>
						</div>
						<div class="col-md-12">
							<h3>Details Information</h3>
							<hr>
						</div>
						<div class="col-md-3">
						 <div class="form-group">
							<label>Nominal :<sup class="text-danger">*</sup></label>
							<input type="text" name="nominal_bpc_transfer" id="nominal_bpc_transfer" class="form-control" placeholder="0" onkeyup="formatRupiah(this)">
						 </div>
						</div>
						<div class="col-md-3">
						 <div class="form-group">
						  <label>Cash or Bank :<span class="text-danger">*</span></label>
						  <select name="type_cb_transfer" id="type_cb_transfer" class="custom-select">
							 <option value="">-- Choose --</option>
							 <option value="CASH">Cash</option>
							 <option value="BANK">Bank</option>
						  </select>
						</div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
							  <label>Coa :<span class="text-danger">*</span></label>
							  <select name="type_coa_transfer" id="type_coa_transfer" class="select2" onchange="getBalanceCashBank(this)">
								   <option value="">-- Choose Cash or Bank First --</option>
							  </select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <select name="branch_bpc_transfer" id="branch_bpc_transfer" class="custom-select" onchange="getBalanceCashBank(this)">
								 <option value="1">PTA</option>
								 <option value="2">SMB</option>
							  </select>
						   </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 text-center">
							<button class="btn btn-warning btn-block" onclick="addOut()"><i class="icon-plus2"></i> Add Out</button>
							<hr>
							<h1>OUT</h1>
							<hr>
							<table class="table table-bordered">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th>Coa</th>
									   <th>Branch</th>
									   <th>Cash/Bank</th>
									   <th>Nominal</th>
									   <th>#</th>
									</tr>
								</thead>
								<tbody id="data_out"></tbody>
							</table>
						</div>
						<div class="col-md-6 text-center">
							<button class="btn btn-info btn-block" onclick="addIn()"><i class="icon-plus2"></i> Add In</button>
							<hr>
							<h1>IN</h1>
							<hr>
							<table class="table table-bordered">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th>Coa</th>
									   <th>Branch</th>
									   <th>Cash/Bank</th>
									   <th>Nominal</th>
									   <th>#</th>
									</tr>
								</thead>
								<tbody id="data_in"></tbody>
							</table>
						</div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Balance C&B : <span class="badge badge-primary" id="tempcashbank">0</span>
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Total In : <span class="badge badge-success" id="tempin">0</span>
					&nbsp;
					Total Out : <span class="badge badge-danger" id="tempout">0</span>
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_transfer()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>

<div class="modal fade" id="modal_detail" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Details Balance Cash & Banks <span id="title-detail" class="font-weight-bold"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="body-detail">
			
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modal_approved" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Data Approved Cash & Bank <span id="title-detail" class="font-weight-bold"></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="body-approved">
			
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
         </div>
      </div>
   </div>
</div>

<script>
   $(function() {
		filter();
		
		$('#totalsurabaya').text('{{ number_format($totalsby,2,',','.') }}');
		$('#totaljakarta').text('{{ number_format($totaljkt,2,',','.') }}');
		
		select2ServerSide('#filter_user_id', '{{ url("admin/select2/user") }}');
		
		$('#datatable_serverside tbody').on('click', 'tr', function () {
			$(this).toggleClass('selected');
			
			var arrId = [];
			
			$('#datatable_serverside tr.selected').each(function(){
				arrId.push($(this).find('.pick').text());
			});
			
			$('#filter_temp').val(arrId.join());
		});
		
		$("#datatable_serverside").on( "click", 'tbody tr .btn-pindah', function() {
			var nominal = $(this).data('nominal'), id = $(this).data('id'), tgl = $(this).data('date'), item = $(this).data('item'), tipe = $(this).data('tipe'), coa = $(this).data('coa'), coaname = $(this).data('coaname'), branch = $(this).data('branch'), branchname = $(this).data('branchname');

			$('#code').val('BPC-' + id);
			$('#date').val(tgl);
			$('#nominal_detail').val(nominal);
			$('#nominal_detail').keyup();
			$('#description').val(item);
			$('#note_detail').val(item);
			$('#branch').val(branch);
			
			if(tipe == 'IN'){
				$('#data_content_debit').append(`
					<tr class="text-center">
					   <input type="hidden" name="coa_detail[]" value="` + coa + `">
					   <input type="hidden" name="type_detail[]" value="1">
					   <input type="hidden" name="branch_detail[]" value="` + branch + `">
					   <input type="hidden" name="note_detail[]" value="` + item + `">

					   <td class="align-middle">` + coaname + `</td>
					   <td class="align-middle">` + branchname + `</td>
					   <td class="align-middle">
						  <div class="form-group">
							 <input type="text" name="nominal_detail[]" data-mode="1" class="form-control" placeholder="0" value="` + nominal + `" onkeyup="formatRupiah(this)">
						  </div>
					   </td>
					   <td class="align-middle">` + item + `</td>   
					   <td class="align-middle">
						  <button type="button" id="delete_data_content_debit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
					   </td>
					</tr>
				`);
			 }else if(tipe == 'OUT'){
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
							 <input type="text" name="nominal_detail[]" data-mode="2" class="form-control" placeholder="0" value="` + nominal + `" onkeyup="formatRupiah(this)">
						  </div>
					   </td>
					   <td class="align-middle">` + item + `</td>   
					   <td class="align-middle">
						  <button type="button" id="delete_data_content_credit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
					   </td>
					</tr>
				`);
			 }

			$('#modal_form_cb').modal('toggle');
		});
		
		$('#modal_form_cb').on('hidden.bs.modal', function (e) {
			$('#date').val('');
			$('#file').val('');
			$('#nominal_detail').val('');
			$('#code').val('');
			$('#description').val('');
		    $('#note_detail').val('');
			$('#coa_id').val('').trigger('change');
			$('#data_content_debit').empty();
			$('#data_content_credit').empty();
		});
		
		$('#modal_detail').on('hidden.bs.modal', function (e) {
			$('#body-detail').empty();
			$('#title-detail').empty();
		});
		
		$('#data_content_debit').on('click', '#delete_data_content_debit', function() {
			$(this).closest('tr').remove();
		});
		$('#data_content_credit').on('click', '#delete_data_content_credit', function() {
			$(this).closest('tr').remove();
		});
		
		$('#data_in').on('click', '#delete_data_in', function() {
			$(this).closest('tr').remove();
			countIn();
		});
		$('#data_out').on('click', '#delete_data_out', function() {
			$(this).closest('tr').remove();
			countOut();
		});
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#temp_bpc').val('');
			$('#nominal_bpc').val('');
			$('#type_bpc').val('');
			$('#date_bpc').val('');
			$('#note_bpc').val('');
			$('#file_bpc').val(null);
		});
		
		$('#modal_transfer').on('hidden.bs.modal', function (e) {
			$('#form_data_transfer')[0].reset();
			$('#data_in').empty();
			$('#tempin').text('0');
			$('#data_out').empty();
			$('#tempout').text('0');
			$('#type_coa_transfer').empty();
			$('#type_coa_transfer').append(`
				<option value="">-- Choose Cash or Bank First --</option>
			`);
		});
		
		$('.sidebar-main-toggle').click();
		
		var arrCash = @php echo json_encode($balancecoa); @endphp;
		
		$('#type_cb').on("change",function(){
			$('#type_coa').empty();
			
			if($(this).val() == 'CASH'){
				$.each(arrCash, function( index, value ) {
					if(value.parent_id == '1'){
						$('#type_coa').append(`
							<option value="` + value.id + `">` + `[` + value.code + `] ` + value.name + `</option>
						`);
					}
				});
			}else if($(this).val() == 'BANK'){
				$.each(arrCash, function( index, value ) {
					if(value.parent_id !== '1'){
						$('#type_coa').append(`
							<option value="` + value.id + `">` + `[` + value.code + `] ` + value.name + `</option>
						`);
					}
				});
			}else{
				$('#type_coa').append(`
					<option value="">-- Choose Cash or Bank First --</option>
				`);
			}
		});
		
		$('#type_cb_transfer').on("change",function(){
			$('#type_coa_transfer').empty();
			
			if($(this).val() == 'CASH'){
				$.each(arrCash, function( index, value ) {
					if(value.parent_id == '1'){
						$('#type_coa_transfer').append(`
							<option value="` + value.id + `">` + `[` + value.code + `] ` + value.name + `</option>
						`);
					}
				});

				$('#type_coa_transfer').trigger('change')
			}else if($(this).val() == 'BANK'){
				$.each(arrCash, function( index, value ) {
					if(value.parent_id !== '1'){
						$('#type_coa_transfer').append(`
							<option value="` + value.id + `">` + `[` + value.code + `] ` + value.name + `</option>
						`);
					}
				});
				$('#type_coa_transfer').trigger('change')
			}else{
				$('#type_coa_transfer').append(`
					<option value="">-- Choose Cash or Bank First --</option>
				`);
			}
		});
		
		$("#form_data_transfer").on("submit", function (e) {
            e.preventDefault();
        });
   });
   
	function uploadNews(coa,branch,title){
		$('#exampleModalLabelUpload').text('Form Upload ' + title);
		$('#modal_upload').modal('toggle');
	}
	
	function createUpload(){
		alert('Ups, sabar ya. Masih repot yang lainnya.')
	}
	
	function addOut(){
		var nominal = parseFloat($('#nominal_bpc_transfer').val().replaceAll(".", "").replaceAll(",","."));
		var balance = parseFloat($('#tempcashbank').text().replaceAll(".", "").replaceAll(",","."));
		if((balance - nominal) >= 0){
			if($('#nominal_bpc_transfer').val() && $('#type_cb_transfer').val() && $('#type_coa_transfer').val()){
				var type_cb = $('#type_cb_transfer option:selected'), type_coa = $('#type_coa_transfer option:selected');
				
				$('#data_out').append(`
					<tr class="text-center">
						<input type="hidden" name="arr_type_cb_out[]" value="` + type_cb.val() + `">
						<input type="hidden" name="arr_nominal_cb_out[]" value="` + $('#nominal_bpc_transfer').val() + `">
						<input type="hidden" name="arr_coa_cb_out[]" value="` + type_coa.val() + `">
						<input type="hidden" name="arr_branch_cb_out[]" value="` + $('#branch_bpc_transfer').val() + `">
						<td class="align-middle">` + type_coa.text() + `</td>
						<td class="align-middle">` + ($('#branch_bpc_transfer').val() == '1' ? 'PTA' : 'SMB') + `</td>
						<td class="align-middle">` + type_cb.val() + `</td>
						<td class="align-middle">` + $('#nominal_bpc_transfer').val() + `</td>
						<td class="align-middle">
							<button type="button" id="delete_data_out" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						</td>
					</tr>
				`);
				
				countOut();
			}else{
				notif('error', 'bg-danger', 'Please complyete details information form.');
			}
		
		return false;
		}else{
			notif('error', 'bg-danger', 'Ups. Hayo, your saldo cash/bank is not enough.');
		}
	}
	
	function addIn(){
		if($('#nominal_bpc_transfer').val() && $('#type_cb_transfer').val() && $('#type_coa_transfer').val()){
			var type_cb = $('#type_cb_transfer option:selected'), type_coa = $('#type_coa_transfer option:selected');
			
			$('#data_in').append(`
				<tr class="text-center">
					<input type="hidden" name="arr_type_cb_in[]" value="` + type_cb.val() + `">
					<input type="hidden" name="arr_nominal_cb_in[]" value="` + $('#nominal_bpc_transfer').val() + `">
					<input type="hidden" name="arr_coa_cb_in[]" value="` + type_coa.val() + `">
					<input type="hidden" name="arr_branch_cb_in[]" value="` + $('#branch_bpc_transfer').val() + `">
					<td class="align-middle">` + type_coa.text() + `</td>
					<td class="align-middle">` + ($('#branch_bpc_transfer').val() == '1' ? 'PTA' : 'SMB') + `</td>
					<td class="align-middle">` + type_cb.val() + `</td>
					<td class="align-middle">` + $('#nominal_bpc_transfer').val() + `</td>
					<td class="align-middle">
						<button type="button" id="delete_data_in" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
					</td>
				</tr>
			`);
			
			countIn();
		}else{
			notif('error', 'bg-danger', 'Please complyete details information form.');
		}
		
		return false;
	}
	
	function selectAllRow(){
		$('#datatable_serverside tbody tr').trigger('click');
	}
	
	function countIn(){
		var total = 0;
		
		$('input[name^="arr_nominal_cb_in"]').each(function(){
			total += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
		});
		
		$('#tempin').html(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
	}
	
	function countOut(){
		var total = 0;
		
		$('input[name^="arr_nominal_cb_out"]').each(function(){
			total += parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
		});
		
		$('#tempout').html(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
	}
	
   function cancel() {
      reset();
      $('#modal_form').modal('hide');
      $('#btn_create').show();
      $('#btn_update').hide();
      $('#btn_cancel').hide();
   }

   function toShow() {
      $('#modal_form').modal('show');
      $('#validation_alert').hide();
      $('#validation_content').html('');
   }

   function resetFilter() {
      $('#filter_user_id').val(null).trigger('change');
	  $('#filter_branch').val('');
      $('#filter_start_date').val(null);
	  $('#filter_type').val('');
      $('#filter_finish_date').val(null);
      $('#filter_start_nominal').val(null);
      $('#filter_finish_nominal').val(null);
      $('input[name="filter_type"][value=""]').prop('checked', true);
   }

	function filter(param = null) {
      if(param == 'reset') {
         resetFilter();
      }

      window.table = loadDataTable();
	  return false;
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
						 <input type="text" name="nominal_detail[]" data-mode="` + mode + `" class="form-control" placeholder="0" value="` + nominal_detail.val() + `" onkeyup="formatRupiah(this)">
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
						 <input type="text" name="nominal_detail[]" data-mode="` + mode + `" class="form-control" placeholder="0" value="` + nominal_detail.val() + `" onkeyup="formatRupiah(this)">
					  </div>
				   </td>
				   <td class="align-middle">` + note_detail.val() + `</td>   
				   <td class="align-middle">
					  <button type="button" id="delete_data_content_credit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				   </td>
				</tr>
			`);
		 }

      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
	}	

	function refreshUntrasnferred(){
		$("#body-approved").load(" #body-approved > *");
	}

	function reset() {
      $('#form_data').trigger('reset');
      $('#data_content_debit').html('');
	  $('#data_content_credit').html('');
      $('input[name="type"][value="1"]').prop('checked', true);
      $('#validation_alert').hide();
      $('#validation_content').html('');
	}

	function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function success_cb() {
	  $("#modal_approved").load(" #modal_approved > *");
	  $('#modal_form_cb').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}

	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
		 stateSave: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[5, 'desc']],
         ajax: {
            url: '{{ url("admin/finance/balance_cash_bank/datatable") }}',
            type: 'GET',
            data: {
				user_id: $('#filter_user_id').val(),
				start_date: $('#filter_start_date').val(),
				finish_date: $('#filter_finish_date').val(),
				type: $('#filter_type').val(),
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
		 "footerCallback": function ( row, data, start, end, display ) {
			  var api = this.api(), data;
	 
			  var intVal = function ( i ) {
				  return typeof i === 'string' ?
					  i.replaceAll('.','').replaceAll(',','.')*1 :
					  typeof i === 'number' ?
						  i : 0;
			  };
	 
			  sal = api
				  .column( 3 )
				  .data()
				  .reduce( function (a, b) {
					  return intVal(a) + intVal(b);
				  }, 0 );
	 
			$( api.column( 3 ).footer() ).html('Rp' + formatRupiahIni(sal.toFixed(0)));
		  },
		 "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'user_id', className: 'text-center align-middle' },
			{ name: 'branch', className: 'text-center align-middle' },
			{ name: 'nominal', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'type', className: 'text-center align-middle nowrap' },
            { name: 'date', searchable: false, className: 'text-center align-middle' },
			{ name: 'note', className: 'text-center align-middle' },
			{ name: 'cashbank', className: 'text-center align-middle' },
			{ name: 'coa_id', className: 'text-center align-middle' },
			{ name: 'ref', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'image', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
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
	
	function create() {
		
		var fd = new FormData(), files = $('#file_bpc')[0].files;
		
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		fd.append('temp_bpc',$('#temp_bpc').val());
		fd.append('nominal_bpc',$('#nominal_bpc').val());
		fd.append('type_bpc',$('#type_bpc').val());
		fd.append('type_cb',$('#type_cb').val());
		fd.append('type_coa',$('#type_coa').val());
		fd.append('branch_bpc',$('#branch_bpc').val());
		fd.append('note_bpc',$('#note_bpc').val());
		fd.append('date_bpc',$('#date_bpc').val());
		
		$.ajax({
		 url: '{{ url("admin/finance/balance_cash_bank/create") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: fd,
		 contentType: false,
		 processData: false,
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
	
	function create_transfer() {
		
		$.ajax({
		 url: '{{ url("admin/finance/balance_cash_bank/create_transfer") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_transfer')[0]),
		 contentType: false,
		 processData: false,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_transfer').hide();
			$('#validation_content_transfer').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
			   notif('success', 'bg-success', response.message);
			   $('#modal_transfer').modal('toggle');
			} else if(response.status == 422) {
			   $('#validation_alert_transfer').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_transfer').append(`
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
	
	function create_cb() {
		var debit = 0, credit = 0;
		
		$('input[name^="nominal_detail"]').each(function(){
			if($(this).data('mode') == '1'){
				debit = debit + parseFloat($(this).val().replace(".", "").replace(".", "").replace(".", "").replaceAll(",","."));
			}else if($(this).data('mode') == '2'){
				credit = credit + parseFloat($(this).val().replace(".", "").replace(".", "").replace(".", "").replaceAll(",","."));
			}
		});
		
		var fd = new FormData(), files = $('#file')[0].files;
		var coa_details = $('input[name="coa_detail[]"]');
		var branch_details = $('input[name="branch_detail[]"]');
		var type_details = $('input[name="type_detail[]"]');
		var note_details = $('input[name="note_detail[]"]');
		var nominal_details = $('input[name="nominal_detail[]"]');
		
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		fd.append('code',$('#code').val());
		fd.append('date',$('#date').val());
		fd.append('description',$('#description').val());
		fd.append('type',$('input[name="type"]:checked').val());
		
		for(var i = 0; i < coa_details.length; i++){
            fd.append(coa_details[i].name, coa_details[i].value);
			fd.append(branch_details[i].name, branch_details[i].value);
			fd.append(type_details[i].name, type_details[i].value);
			fd.append(note_details[i].name, note_details[i].value);
			fd.append(nominal_details[i].name, nominal_details[i].value);
        }
		
		if((debit - credit) == 0){
			$.ajax({
			 url: '{{ url("admin/finance/balance_cash_bank/create_cb") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: fd,
			 contentType: false,
			 processData: false,
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
				// refreshUntrasnferred();
				if(response.status == 200) {
					success_cb();
					notif('success', 'bg-success', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_cb').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_cb').append(`
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
	
	function show(id) {
      toShow();
      $.ajax({
         url: '{{ url("admin/finance/balance_cash_bank/show") }}',
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
			$('#temp_bpc').val(id);
			$('#nominal_bpc').val(response.nominal);
			$('#type_bpc').val(response.type);
			$('#type_cb').val(response.cash_or_bank).trigger('change');
			$('#branch_bpc').val(response.branch);
			$('#type_coa').val(response.coa_id);
			$('#date_bpc').val(response.date);
			$('#note_bpc').val(response.note);
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
                  url: '{{ url("admin/finance/balance_cash_bank/destroy") }}',
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
	
	function showDetail(coa,branch,title) {
		
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Sure want to see?</h6><label>Please choose start and end date to continue.</label><div class="row"><div class="form-group col-md-12 text-center"><input type="date" name="date_start" id="date_start" class="form-control"> <span class="mt-3 mb-3">until</span> <input type="date" name="date_finish" id="date_finish" class="form-control"></div></div>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-file-eye2"></i>', 'btn bg-success ml-1', function() {
				if($('#date_start').val() !== '' && $('#date_finish').val() !== ''){
					var start = $('#date_start').val();
					var end = $('#date_finish').val();
					
					$.ajax({
						 url: '{{ url("admin/finance/balance_cash_bank/show_detail") }}',
						 type: 'POST',
						 dataType: 'JSON',
						 data: { coa : coa, branch : branch, startDate : $('#date_start').val(), endDate : $('#date_finish').val() },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#body-detail');
						 },
						 success: function(response) {
							loadingClose('#body-detail');
							$('#modal_detail').modal('toggle');
							if(response.status == 200) {
								$('#title-detail').html(title + ' Period ' + start + ' until ' + end);
								$('#body-detail').html(response.content);
							}
						 },
						 error: function() {
							$('#body-detail').scrollTop(0);
							loadingClose('.modal-content');
							swalInit.fire({
							   title: 'Server Error',
							   text: 'Please contact developer',
							   type: 'error'
							});
						 }
					});
					
					notyConfirm.close();
				}else{
					notif('error', 'bg-warning', 'Please choose start date and end date to show details report.');
				}
			 })
         ]
      }).show();
	}
	
	function approved() {
		$.ajax({
			 url: '{{ url("admin/finance/balance_cash_bank/show_approved") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: { },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('#body-approved');
			 },
			 success: function(response) {
				loadingClose('#body-approved');
				/* $('#modal_approved').modal('toggle');*/
				if(response.status == 200) {
					$('#body-approved').html(response.content);
				}
			 },
			 error: function() {
				$('#body-approved').scrollTop(0);
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
	}
	
	$('#form-balance').submit(function () {
		if ($('#start_date').val()  == '' || $('#finish_date').val()  == '') {
			notif('error', 'bg-warning', 'Please choose start date and end date to show details report.');
			return false;
		}
	});
	
	function getPindah(nominal,id,tgl,item,tipe,coa,coaname,branch,branchname){
		
		$('#code').val('BPC-' + id);
		$('#date').val(tgl);
		$('#nominal_detail').val(nominal);
		$('#nominal_detail').keyup();
		$('#description').val(item);
		$('#note_detail').val(item);
		$('#branch').val(branch);
		
		if(tipe == 'IN'){
			$('#data_content_debit').append(`
				<tr class="text-center">
				   <input type="hidden" name="coa_detail[]" value="` + coa + `">
				   <input type="hidden" name="type_detail[]" value="1">
				   <input type="hidden" name="branch_detail[]" value="` + branch + `">
				   <input type="hidden" name="note_detail[]" value="` + item + `">

				   <td class="align-middle">` + coaname + `</td>
				   <td class="align-middle">` + branchname + `</td>
				   <td class="align-middle">
					  <div class="form-group">
						 <input type="text" name="nominal_detail[]" data-mode="1" class="form-control" placeholder="0" value="` + nominal + `" onkeyup="formatRupiah(this)">
					  </div>
				   </td>
				   <td class="align-middle">` + item + `</td>   
				   <td class="align-middle">
					  <button type="button" id="delete_data_content_debit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				   </td>
				</tr>
			`);
		 }else if(tipe == 'OUT'){
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
						 <input type="text" name="nominal_detail[]" data-mode="2" class="form-control" placeholder="0" value="` + nominal + `" onkeyup="formatRupiah(this)">
					  </div>
				   </td>
				   <td class="align-middle">` + item + `</td>   
				   <td class="align-middle">
					  <button type="button" id="delete_data_content_credit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				   </td>
				</tr>
			`);
		 }
		
		$('#modal_approved').modal('toggle');
		
		$('#modal_form_cb').modal('toggle');
	}

	function getBalanceCashBank(element){
		if($(element).val()){
			$.ajax({
			  url: '{{ url("admin/finance/balance_cash_bank/get_balance_cash_bank") }}',
			  type: 'POST',
			  dataType: 'JSON',
			  data: {
				 branch : $('#filter_branch').val(),
				 coa_id : $('#type_cb_transfer option:selected').val()
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