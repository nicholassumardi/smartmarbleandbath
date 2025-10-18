@php
	use App\Models\ProjectNote;
@endphp
<style>
   html {
      scroll-behavior: smooth;
	  scroll-padding-top: 75px;
   }
	
	.sidebar-sticky{
		z-index:900;
	}
	
	.sidebar-light .nav-sidebar>.nav-item>.nav-link.active {
		background-color:#07a9e7;
	}
	
	.sidebar-content::-webkit-scrollbar {
	  width: 0px;
	  height: 8px;
	  background-color: white;
	}
	
	.sidebar-light .nav-sidebar .nav-link {
		color:white !important;
	}
	
	.sidebar-light .nav-sidebar .nav-link:hover{
		background-color:#457c80 !important;
		transform: scale(1.10);
	}
	
	.icon-check.text-success {
		color:#92ff01 !important;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Progress Sales Project : <b><i>{{ $project->name }}</i></b></span>
				</h4>
			</div>
         <div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/delivery_order/project') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To All
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Data</a>
					<a href="{{ url('admin/deliver_order/project') }}" class="breadcrumb-item">Project</a>
					<span class="breadcrumb-item active">Detail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="d-block d-flex align-items-start flex-column flex-md-row">
         <div class="order-2 order-md-1 w-100">
			<div class="card" id="step-1">
               <div class="card-body">
                  <h3 class="card-title" id="scrollspy"><b>Project Information</b></h3>
                  <div class="form-group"><hr></div>
				  <div class="row">
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td width="40%">Project Name</td>
										<td>: {{ $project->name }}</td>
									</tr>
									<tr>
										<td>Phone</td>
										<td>: {{ $project->customer->phone }}</td>
									</tr>
									<tr>
										<td>Email</td>
										<td>: {{ $project->customer->email }}</td>
									</tr>
									<tr>
										<td>Constructor Name</td>
										<td>: {{ $project->customer->constructor }}</td>
									</tr>
									<tr>
										<td>Country</td>
										<td>: {{ $project->country->name }}</td>
									</tr>
									<tr>
										<td>City</td>
										<td>: {{ $project->city->name }}</td>
									</tr>
									<tr>
										<td>Timeline</td>
										<td>: {{ date('d F Y', strtotime($project->timeline)) }}</td>
									</tr>
									<tr>
										<td>PIC</td>
										<td>: {{ $project->manager }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td width="40%">Consultant Name</td>
										<td>: {{ $project->consultant }}</td>
									</tr>
									<tr>
										<td>Owner</td>
										<td>: {!! $project->owner !!}</td>
									</tr>
									<tr>
										<td>Bank Destination</td>
										<td>: {!! $project->coa->name !!}</td>
									</tr>
									<tr>
										<td>Payment Method</td>
										<td>: {!! $project->paymentMethod() !!}</td>
									</tr>
									<tr>
										<td>Payment Term</td>
										<td>: {!! $project->paymentTerm() !!}</td>
									</tr>
									<tr>
										<td>Supply Method</td>
										<td>: {!! $project->supplyMethod() !!}</td>
									</tr>
									<tr>
										<td>PPN</td>
										<td>: {!! $project->ppn() !!}</td>
									</tr>
									<tr class="bg-purple-500" style="background-color:#cc33ff;">
										<td>Budgeting Delivery Cost</td>
										<td>: 
											@php
												$totaltransport = 0;
												$userbudget = '';
												foreach($project->budgetingProject as $rowbudget){
													foreach($rowbudget->budgetingProjectDetail->where('coa_id',297) as $rowdetail){
														$totaltransport += $rowdetail->nominal;
													}
													$userbudget .= $rowbudget->user->name;
												}
											@endphp
											{{ number_format($totaltransport,2,',','.') }} <i>(created by : {{ $userbudget ? $userbudget : 'Empty' }})</i>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				  </div>
				  <div class="form-group"><hr></div>
				   <div class="form-group text-center">
						<button class="btn btn-primary" onclick="addBill({{ $project->id.',"'.$project->code.'"' }})">ADD BILL</button>
				   </div>
				  
				   <h5 class="mt-3"><b>List of All Sales Bill</b></h5>
					<div class="table-responsive">
					  <table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>SO No.</th>
							   <th>Bill No.</th>
							   <th>User</th>
							   <th>Date Create</th>
							   <th>Due Date</th>
							   <th>Checked</th>
							   <th>Approved</th>
							   <th><i class="icon-printer2"></i></th>
							   <th>Edit</th>
							</tr>
						 </thead>
						 <tbody>
							@foreach($project->projectBill as $pb)
							   <tr class="text-center">
								  <td class="align-middle">{{ $pb->project->code }}</td>  
								  <td class="align-middle">{{ $pb->code }}</td>
								  <td class="align-middle">{{ $pb->user->name }}</td>
								  <td class="align-middle">{{ $pb->date }}</td>
								  <td class="align-middle">{{ $pb->due_date }}</td>
								  <td class="align-middle">
									@php
										if(isset($pb->check->name)){
											echo $pb->check->name;
										}else{
											echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveBill(1,'.$pb->id.')"><i class="icon-checkmark2"></i></button>';
										}
									@endphp
								  </td>   
								  <td class="align-middle">
									@php
										if(isset($pb->approved->name)){
											echo $pb->approved->name;
										}else{
											echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveBill(2,'.$pb->id.')"><i class="icon-checkmark2"></i></button>';
										}
									@endphp
								  </td>
								  <td>
									<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_bill/'. base64_encode($pb->id)) }}')" href="javascript:void(0);" class="btn bg-primary"><i class="icon-file-pdf"></i> - ENG</a>
									<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_bill/'. base64_encode($pb->id)) }}?la=idn')" href="javascript:void(0);" class="btn bg-success"><i class="icon-file-pdf"></i> - IDN</a>
								  </td>
								  <td>
									<a href="javascript:void(0);" onclick="editBill({{ $pb->id }},'{{ $project->code }}')" class="btn bg-info"><i class="icon-pencil5"></i></a>
								  </td>
							   </tr>
							@endforeach
						 </tbody>
					  </table>
				   </div>
				   <div class="form-group"><hr></div>
				  <div class="form-group mt-3">
					<h5><b>List of All Delivery Document Purchases</b></h5>
					<div class="table-responsive">
					  <table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>PO Number</th>
							   <th>Shipment Number</th>
							   <th>Loading</th>
							   <th>Departure</th>
							   <th>From Port</th>
							   <th>To Port</th>
							   <th>ETA</th>
							   <th>Note</th>
							   <th>Proof</th>
							</tr>
						 </thead>
						 <tbody>
							@foreach($project->projectShipment()->orderBy('project_purchase_id')->get() as $ps)
							   <tr class="text-center">
								  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $ps->projectPurchase->id }}')">{{ $ps->projectPurchase->code }}</a></td>
								  <td class="align-middle">{{ $ps->shipment_code }}</td>
								  <td class="align-middle">{{ $ps->loading_date }}</td>
								  <td class="align-middle">{{ $ps->departure_date }}</td>
								  <td class="align-middle">{{ $ps->from_port }}</td>
								  <td class="align-middle">{{ $ps->to_port }}</td>
								  <td class="align-middle">{{ $ps->eta }}</td>
								  <td class="align-middle">{{ $ps->note }}</td>
								  <td class="align-middle"><a href="{{ $ps->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>
							   </tr>
							@endforeach
						 </tbody>
					  </table>
				   </div>
				  </div>
				  <div class="form-group"><hr></div>
				  <div class="form-group mt-3">
					<h5><b>List of All Purchase Notes</b></h5>
					<div class="table-responsive">
					  <table class="table table-bordered table-striped">
						<thead class="table-secondary">
							<tr class="text-center">
							   <th width="25%">Date</th>
							   <th>PO</th>
							   <th>Note</th>
							   <th>Proof</th>
							</tr>
						 </thead>
						 <tbody id="data_purchase_note">
						 @php $countnotes = 0; @endphp
						 @foreach($project->projectPurchase as $row)
							@foreach($row->purchaseNote() as $rowdetail)
								<tr class="text-center">
									<td>{{ $rowdetail->created_at }}</td>
									<td>{{ $rowdetail->notable->code }}</td>
									<td>{{ $rowdetail->note }}</td>
									<td>{!! $rowdetail->image ? '<a href="' . $rowdetail->image() . '" target="_blank" class="btn btn-info"><i class="icon-search4"></i></a>' : '<span class="badge badge-secondary">None</span>' !!}</td>
								</tr>
							@php $countnotes++; @endphp
							@endforeach
						@endforeach
						@foreach(ProjectNote::where('notable_type','pre_purchase')->where('notable_id',$project->id)->where('is_public','1')->get() as $rowdetail)
							<tr class="text-center">
								<td>{{ $rowdetail->created_at }}</td>
								<td>-</td>
								<td>{{ $rowdetail->note }}</td>
								<td>{!! $rowdetail->image ? '<a href="' . $rowdetail->image() . '" target="_blank" class="btn btn-info"><i class="icon-search4"></i></a>' : '<span class="badge badge-secondary">None</span>' !!}</td>
							</tr>
						@php $countnotes++; @endphp
						@endforeach
							@if($countnotes == 0)
							<tr>
								<td colspan="5">
									<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes here.</div>
								</td>
							</tr>
							@endif
						 </tbody>
					  </table>
				   </div>
				  </div>
				  <div class="form-group"><hr></div>
				  <div class="form-group mt-3">
					<h5><b>List of All Sales Order</b></h5>
					<div class="table-responsive">
					  <table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>SO No.</th>
							   <th>Sales</th>
							   <th>Date</th>
							   <th>PO Customer</th>
							   <th>SO Product</th>
							   <th>SO Service</th>
							   <th>Tax Invoice</th>
							   <th>Note</th>
							</tr>
						 </thead>
						 <tbody>
							@foreach($project->projectSale as $ps)
							   <tr class="text-center">
								  <td class="align-middle">{{ $ps->code }}</td>  
								  <td class="align-middle">{{ $ps->sales->name }}</td>
								  <td class="align-middle">{{ date('Y-m-d',strtotime($ps->created_at)) }}</td>   
								  <td class="align-middle">
									<a href="{{ $ps->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
								  </td>
								  <td>
									<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_order/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
								  </td>
								  <td>
									<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_cost/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-success"><i class="icon-file-pdf"></i></a>
								  </td>
								  <td>
									<a href="javascript:void(0);" onclick="addTaxDocument({{ $ps->id }})" class="btn bg-primary btn-sm">
										<i class="icon-file-spreadsheet"></i>
										<span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">{{ $ps->countTaxDocument() }}</span>
									</a>
								  </td>
								  <td>
									{{ $ps->note }}
								  </td>
							   </tr>
							@endforeach
						 </tbody>
					  </table>
				   </div>
				  </div>
				  <div class="form-group"><hr></div>
				  <div class="form-group mt-3">
					<h5><b>List of All Sales Return Memo</b></h5>
					<div class="table-responsive">
					  <table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>SO No.</th>
							   <th>DO No.</th>
							   <th>User</th>
							   <th>Date</th>
							   <th>Note</th>
							   <th>Proof</th>
							   <th>Show Product</th>
							   <th>Document</th>
							</tr>
						 </thead>
						 <tbody>
							@foreach($project->projectReturnMemo as $prm)
							   <tr class="text-center">
								  <td class="align-middle">{{ $prm->projectDelivery->projectSale->code }}</td>  
								  <td class="align-middle">{{ $prm->projectDelivery->code }}</td>
								  <td class="align-middle">{{ $prm->user->name }}</td>
								  <td class="align-middle">{{ date('d M Y',strtotime($prm->date)) }}</td>
								  <td>
									{{ $prm->reason }}
								  </td>
								  <td class="align-middle">
									<a href="{{ $prm->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
								  </td>
								  <td class="align-middle">
									<a class="btn btn-primary collapsed" data-toggle="collapse" href="#return-memo-{{ $prm->id }}" aria-expanded="false"><i class="icon-safe"></i></a>
								  </td>
								  <td class="align-middle">
									<a onclick="openLink('{{ url('admin/delivery_order/project/print/return_memo/' . base64_encode($prm->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
								  </td>
							   </tr>
							   <tr class="text-center collapse" id="return-memo-{{ $prm->id }}">
									<td colspan="7">
										@foreach($prm->projectReturnMemoDetail as $rowdetail)
											{{ $rowdetail->product->name() }} Jumlah {{ $rowdetail->qty.' '.$rowdetail->unit() }} <br>
										@endforeach
									</td>
							   </tr>
							@endforeach
						 </tbody>
					  </table>
				   </div>
				  </div>
					
					<div style="border:2px solid red;padding:5px;">
						<div class="form-group"><hr></div>
						<h5><b>Pre Delivery Notes</b></h5>
						<div class="row">
							<div class="col-md-5">
							  <div class="form-group">
								<input type="text" class="form-control" name="delivery-note" id="delivery-note" placeholder="Type note">
							  </div>
							</div>
							<div class="col-md-5">
							  <div class="form-group">
								 <div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="delivery-file" name="delivery-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							  </div>
							</div>
							<div class="col-md-2">
							  <div class="form-group">
								 <button type="button" onclick="addPreDeliveryNote()" class="btn bg-success col-12" id="btnadddelivery"><i class="icon-plus2"></i> Add</button>
							  </div>
							</div>
						</div>
						
						<div class="form-group"><hr></div>
						<div class="form-group">
							<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<thead class="table-secondary">
										<tr class="text-center">
										   <th width="25%">Date</th>
										   <th>Note</th>
										   <th>Proof</th>
										   <th width="15%">is Public</th>
										</tr>
									 </thead>
									 <tbody id="data_delivery_note">
										@foreach(App\Models\ProjectNote::where('notable_type','pre_delivery')->where('notable_id',$project->id)->get() as $row)
											@php
												if($row->is_public == '1'){
													$status = 'checked';
												}else{
													$status = '';
												}
											@endphp
											<tr class="text-center">
												<td>{{ $row->created_at }}</td>
												<td>{{ $row->note }}</td>
												<td>{!! $row->image ? (explode('.',$row->image)[1] == 'pdf' ? '<a href="' .$row->image() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->note.'" data-group="a" href="' .$row->image() . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>' ) : '' !!}</td>
												<td>
													<label class="form-check-label">
														<input type="checkbox" class="form-check-input" onclick="isPublic(this,{{ $row->id }})" style="margin-top:.125rem;margin-left: -1.5rem;transform: scale(1.25);" {{ $status }}>
														YES
													</label>
												</td>
											</tr>
										@endforeach
									 </tbody>
								</table>
							</div>
						</div>
					</div>
               </div>
            </div>
@php
	if($project->budgetingProjectByDate() == true){
@endphp				
			<div class="card" id="step-8">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">8</span> Down Payment</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modepayment" onclick="resetPayment()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
						<h5 class="card-title">Payment Information</h5>
						<dl class="row mb-0">
							<dt class="col-sm-3">Bank Destination</dt>
							<dd class="col-sm-9">: {!! $project->coa->name !!}</dd>

							<dt class="col-sm-3">Customer Deposit</dt>
							<dd class="col-sm-9">: <span class="badge badge-success" style="font-size:15px;">{{ $project->getBalanceCustomer() }}</span></dd>

							<dt class="col-sm-3">Payment Method</dt>
							<dd class="col-sm-9">: {!! $project->paymentMethod() !!}</dd>

							<dt class="col-sm-3">Supply Method</dt>
							<dd class="col-sm-9">: {!! $project->supplyMethod() !!}</dd>

							<dt class="col-sm-3 text-truncate">PPN</dt>
							<dd class="col-sm-9">: {!! $project->ppn() !!}</dd>
							
							<dt class="col-sm-3 text-truncate">Term Payment</dt>
							<dd class="col-sm-9">: {!! $project->paymentTerm() !!}</dd>
							
						</dl>
						<div class="form-group"><hr></div>
						@if(isset($_GET['step-8']))
						  @if($errors->any())
							 <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								<ul>
								   @foreach ($errors->all() as $error)
									  <li>{{ $error }}</li>
								   @endforeach
								</ul>
							 </div>
						  @elseif(session('success'))
							 <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								{{ session('success') }}
							 </div>
						  @endif
						@endif
						
						<div class="row">
						  <div class="col-md-12 text-center">
							<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Attention!</span> Please select sales order first to see total sales payment, paid, and balance not paid in buttons below.</div>
							<button type="button" class="btn btn-primary mb-2">Total : <i class="icon-cash4 mr-2"></i> <b id="payment_total">0</b></button>
							&nbsp;
							<button type="button" class="btn btn-success mb-2">Paid : <i class="icon-cash4 mr-2"></i> <b id="payment_paid">0</button>
							&nbsp;
							<button type="button" class="btn btn-warning mb-2">Balance not paid : <i class="icon-cash4 mr-2"></i> <b id="payment_left">0</button>
						  </div>
							<div class="col-md-12 d-none edit-sp">
								 <div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason_payment" id="edit_reason_payment" class="form-control" placeholder="Please describe why you edit this Sales Order Payment." rows="2"></textarea>
							    </div>
							</div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Sales Order :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="temp_pay_id" name="temp_pay_id" value="">
								<select name="sopd_id" id="sopd_id" class="select2" onchange="getSalesInfo(this,this.value);">
								   <option value="">-- Choose --</option>
								   @foreach($project->projectSale as $ps)
									  <option value="{{ $ps->id }}">{{ $ps->code }}</option>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Delivery & Proforma :</label>
								<select name="do_id" id="do_id" class="select2">
									<option value="">-- Empty --</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>From Bill :</label>
								<select name="bill_id" id="bill_id" class="select2">
								   <option value="">-- None --</option>
								   @foreach($project->projectBill as $pb)
									  <option value="{{ $pb->id }}">{{ $pb->code }}</option>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="date_create" id="date_create" class="form-control" value="{{ old('date_create') }}" onchange="countDueDate()">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Payment :<sup class="text-danger">*</sup></label>
								<select name="payment_method" id="payment_method" class="custom-select" onchange="">
								   <option value="">-- Choose --</option>
								   <option value="1" {{ old('payment_method') == 1 ? 'selected' : '' }}>Down Payment</option>
								   <!-- <option value="2" {{ old('payment_method') == 2 ? 'selected' : '' }} disabled>Full Payment Upfront</option>
								   <option value="3" {{ old('payment_method') == 3 ? 'selected' : '' }} disabled>Full Payment Last</option> -->
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Nominal :<sup class="text-danger">*</sup></label>
								<input type="text" name="nominal" id="nominal" class="form-control" placeholder="0" value="{{ old('nominal') }}" onkeyup="formatRupiah(this)">
							 </div>
						  </div>
						  <div class="col-md-4">
							<div class="form-group">
								<label>Proof of Payment :<sup class="text-danger">*</sup></label>
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file" name="file" class="form-control h-auto filedp" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							</div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Bank Destination :</label>
								<select name="bank_id" id="bank_id" class="select2">
								   <option value="">-- None --</option>
								   <option value="230">Deposit Transit (Post Silang)</option>
								   @foreach($bank->where('parent_id',0)->whereIn('code',['1.000.00']) as $c)
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
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Method (Transfer/Giro/Check/Cash) :<sup class="text-danger">*</sup></label>
								<select name="giro" id="giro" class="custom-select">
									<option value="0" {{ old('giro') == 0 ? 'selected' : '' }}>Transfer</option>
									<option value="1" {{ old('giro') == 1 ? 'selected' : '' }}>Giro</option>
									<option value="2" {{ old('giro') == 2 ? 'selected' : '' }}>Check</option>
									<option value="3" {{ old('giro') == 3 ? 'selected' : '' }}>Cash</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Note :</label>
								<textarea name="note" id="note" class="form-control" value="{{ old('note') }}" rows="1">-</textarea>
							 </div>
							</div>
						  <div class="col-md-4 giro-class d-none">
							 <div class="form-group">
								<label>Giro Number :</label>
								<input type="text" name="giro_code" id="giro_code" class="form-control" value="-">
							 </div>
						  </div>
						  <div class="col-md-4 giro-class d-none">
							 <div class="form-group">
								<label>Giro Due Date :</label>
								<input type="date" name="giro_date" id="giro_date" class="form-control">
							 </div>
						  </div>
						</div>
						<div class="form-group"><hr></div>
						<h5 class="card-title">Preview Proof</h5>
						<div class="form-group text-center" id="previewImgDp">
							<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="text-right">
							<a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(8,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
								 &nbsp;
							<button type="submit" name="submit" value="step-8" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed4">
								See All Sales Down Payment
							</a>
						</div>
						
						<div class="form-group collapse" id="collapse-link-collapsed4">
							<h5 class="card-title"><b><span class="badge badge-danger">8.a</span> List of All Sales Payment</b></h5>
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO Code</th>
									   <th>Invoice</th>
									   <th>Date</th>
									   <th>Nominal</th>
									   <th>Note</th>
									   <th>Checked By</th>
									   <th>Marketing</th>
									   <th>Approved By</th>
									   <th>Proof</th>
									   <th>Edit</th>
									   <th>Delete</th>
									   <th><i class="icon-printer2"></i></th>
									   <th>Invoice Delivery</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPay()->orderBy('project_sale_id')->get() as $pp)
									   <tr class="text-center">
										  <td class="align-middle">{{ $pp->projectSale->code }}</td>
										  <td class="align-middle">{{ $pp->code }}</td>
										  <td class="align-middle">{{ $pp->date }}</td>
										  <td class="align-middle">IDR {{ number_format($pp->nominal,2,',','.') }}</td>
										  <td class="align-middle">{{ $pp->note }}</td>
										  <td class="align-middle">
											@php
												if(isset($pp->check->name)){
													echo $pp->check->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(3,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											@php
												if(isset($pp->marketing->name)){
													echo $pp->marketing->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(1,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										   <td class="align-middle">
											@php
												if(isset($pp->approved->name)){
													echo $pp->approved->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(2,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td>
											<a href="#step-8" onclick="editPayment({{ $pp->id }})" class="btn bg-success"><i class="icon-pencil5"></i></a>
										  </td>
										  <td>
											<a href="javascript:void(0);" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="deletePayment({{ $pp->id }})"><i class="icon-trash"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_invoice/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_invoice_delivery/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
			
			@if($project->progress < 65)
				<div class="alert alert-warning alert-styled-left alert-dismissible">
					<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
					<span class="font-weight-semibold">Warning!</span> This project <b>hasn't reached</b> 75% yet, please contact <b>Purchase team</b> to complete their tasks.
				</div>
			@endif
			
            @if($project->progress >= 65)
               <div class="card" id="step-16">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">16</span> Delivery To Project</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modedelivery" onclick="resetDelivery()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
					   @if(isset($_GET['step-16']))
						  @if($errors->any())
							 <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								<ul>
								   @foreach ($errors->all() as $error)
									  <li>{{ $error }}</li>
								   @endforeach
								</ul>
							 </div>
						  @elseif(session('success'))
							 <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								{{ session('success') }}
							 </div>
						  @endif
					   @endif
					   <div class="row">
					      <div class="col-md-12 d-none edit-do">
								 <div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason_delivery" id="edit_reason_delivery" class="form-control" placeholder="Please describe why you edit this Delivery Order." rows="2"></textarea>
							    </div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>Sales Order :<sup class="text-danger">*</sup></label>
									<input type="hidden" id="temp_delivery_id" name="temp_delivery_id" value="">
									<select name="sod_id" id="sod_id" class="select2" onchange="getSalesProduct(this,this.value);getSalesInfo(this,this.value);">
									   <option value="">-- Choose --</option>
									   @foreach($project->projectSale as $ps)
										  <option value="{{ $ps->id }}">{{ $ps->code }}</option>
									   @endforeach
									</select>
								 </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Receiver Name :<span class="text-danger">*</span></label>
								<input type="text" name="receiver_name" id="receiver_name" class="form-control" value="{{ old('receiver_name') }}" placeholder="Enter receiver name" required>
							 </div>
							</div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Delivery Date :<span class="text-danger">*</span></label>
								<input type="date" name="delivery_date" id="delivery_date" class="form-control" value="{{ old('delivery_date') }}" required>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Email :</label>
								<input type="email" name="email" id="email" class="form-control" value="{{ old('email', $project->email) }}" placeholder="Enter email">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Phone :<span class="text-danger">*</span></label>
								<input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $project->phone) }}" placeholder="Enter phone" required>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>City :<span class="text-danger">*</span></label>
								<select name="city_id2" id="city_id2" class="select2" style="width:100%;" required></select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>From Warehouse :<sup class="text-danger">*</sup></label>
								<select name="warehousedeliver_id" id="warehousedeliver_id"></select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Expedition :<sup class="text-danger">*</sup></label>
								<select name="expedition_id" id="expedition_id" class="select2">
									<option value="">-- Choose --</option>
									@foreach($vendor as $v)
									  <option value="{{ $v->id }}">{{ $v->name }}</option>
									@endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Proof of Delivery :</label>
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							 </div>
                            </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Address :<span class="text-danger">*</span></label>
								<textarea name="address" id="address" class="form-control" placeholder="Enter address" rows="1" required>{{ old('address') }}</textarea>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Is Dropshipper? :<span class="text-danger">*</span></label>
								<select name="dropshipper" id="dropshipper" class="custom-select">
								   <option value="1">No</option>
								   <option value="2">Yes</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Is Sample? :<span class="text-danger">*</span></label>
								<select name="is_sales" id="is_sales" class="custom-select">
									<option value="1">No</option>
									<option value="2">Yes</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4" style="display:none;" id="data-dropshipper">
							 <div class="form-group">
								<label>Dropshipper :<span class="text-danger">*</span></label>
								<select name="dropshipper_id" id="dropshipper_id" class="custom-select">
									@foreach($dropshipper as $dr)
									  <option value="{{ $dr->id }}">{{ $dr->name }}</option>
									@endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Pick Up Driver :</label>
								<input type="text" name="pick_up_name" id="pick_up_name" class="form-control" value="{{ old('pick_up_name') ? old('pick_up_name') : '' }}" placeholder="Enter pick up driver name if any.">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Pick Up Plat No. :</label>
								<input type="text" name="pick_up_plat" id="pick_up_plat" class="form-control" value="{{ old('pick_up_plat') ? old('pick_up_plat') : '' }}" placeholder="Enter pick up vehicle plat no if any.">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Pick Up Vehicle :</label>
								<input type="text" name="pick_up_vehicle" id="pick_up_vehicle" class="form-control" value="{{ old('pick_up_vehicle') ? old('pick_up_vehicle') : '' }}" placeholder="Enter pick up vehicle name or type if any.">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Service Note :<span class="text-danger">*</span></label>
								<textarea name="service_note" id="service_note" class="form-control" placeholder="Enter note for service bill" rows="1">{{ old('service_note') ? old('service_note') : '-' }}</textarea>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Note (Invoice) :</label>
								<textarea name="invoice_note" id="invoice_note" class="form-control" placeholder="Note for invoice" rows="1">{{ old('invoice_note') ? old('invoice_note') : '-' }}</textarea>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Break Tolerance (%) :</label>
								<input type="number" step="0.01" max="100" name="break_tolerance" id="break_tolerance" class="form-control" value="{{ old('break_tolerance') ? old('break_tolerance') : '' }}" placeholder="Enter break tolerance.">
							 </div>
						  </div>
						</div>
						<h5 class="card-title"><b><span class="badge badge-danger">16.a</span> Detail Sales Products</b></h5>
                        <div class="form-group"><hr></div>
						<div class="form-group">
                           <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                 <thead class="table-secondary">
                                    <tr class="text-center">
									   <th>No</th>
                                       <th>Product</th>
                                       <th>Qty Need</th>
									   <th>Qty Left</th>
									   <th>Qty Stock</th>
									   <th>Qty Send</th>
									   <th>Shading</th>
									   <th>Qty Deduction</th>
									   <th>Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_delivery_products">
                                    <td class="bg-warning" colspan="9" style="text-align:center;">Choose Sales Order first to see All products that can be sent.</td>
                                 </tbody>
                              </table>
                           </div>
                        </div>
					   <div class="form-group"><hr></div>
					   <div class="form-group">
						  <div class="text-right">
							<button type="submit" name="submit" value="step-16" class="btn bg-purple submit_delivery">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed">
								See All Delivery Order
							</a>
						</div>
					  
						<div class="form-group collapse" id="collapse-link-collapsed">
							 <h5 class="card-title"><b><span class="badge badge-danger">16.b</span> List of All Delivery Order</b></h5>
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO No.</th>
									   <th>DO No.</th>
									   <th>Warehouse</th>
									   <th>Receiver</th>
									   <th>Expedition</th>
									   <th>Dropship</th>
									   <th>Approved By</th>
									   <th>Acknowledge By</th>
									   <th>Tracking</th>
									   <th>SO Product</th>
									   <th>SO Service</th>
									   <th>SJ</th>
									   <th>PUM</th>
									   <th>Inv Product</th>
									   <th>Inv Other</th>
									   <th>Final News</th>
									   <th>Proof</th>
									   <th>Received</th>
									   <th>DueDate</th>
									   <th>Add Notes</th>
									   <th>Edit</th>
									   <th>Delete</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectDelivery()->orderBy('project_sale_id')->get() as $psi)
									   <tr class="text-center">
										  <td class="align-middle">{{ $psi->projectSale->code }}</td>
										  <td class="align-middle">{{ $psi->code }}</td>
										  <td class="align-middle">{{ $psi->warehouse ? $psi->warehouse->name.' - '.$psi->warehouse->code : '' }}</td>
										  <td class="align-middle">{{ $psi->receiver_name }}</td>
										  <td class="align-middle">{{ $psi->vendor->name }}</td>
										  <td class="align-middle">{{ $psi->isDropshipper() }}</td>
										  <td class="align-middle">
											@php
												if(isset($psi->approve->name)){
													echo $psi->approve->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveDelivery(1,'.$psi->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											@php
												if(isset($psi->acknowledge->name)){
													echo $psi->acknowledge->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveDelivery(2,'.$psi->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a class="btn bg-info" href="javascript:void(0);" onclick="addTrackingDelivery({{ $psi->id }},'{{ $psi->code }}')"><i class="icon-truck"></i></a>
										  </td>
										  <td>
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_order/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td>
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_cost/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-success"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/delivery_order/' . base64_encode($psi->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/pick_up_memo_delivery/' . base64_encode($psi->id)) }}')" href="javascript:void(0);" class="btn bg-danger"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_proforma/' . base64_encode($psi->id)) }}')" href="javascript:void(0);" class="btn bg-primary"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_proforma_other/' . base64_encode($psi->id)) }}')" href="javascript:void(0);" class="btn bg-primary {{ $psi->isFirstDelivery() ? 'blink-notification' : '' }}"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											@if($psi->projectSale->mid_yes_no == '1')
												<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_news_final/' . base64_encode($psi->id)) }}')" href="javascript:void(0);" class="btn bg-primary"><i class="icon-file-pdf"></i></a>
											@else
												None
											@endif
										  </td>
										  <td class="align-middle">
											<a href="{{ $psi->attachment() }}" target="_blank" class="btn bg-info"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											@if(isset($psi->received_date))
												{{ $psi->received_date }}
												<br>
												<a class="btn bg-danger" href="javascript:void(0);" onclick="updateReceivedProof({{ $psi->id }},'{{ $psi->code }}')"><i class="icon-file-upload"></i></a>
											@else
												<a class="btn bg-info" href="javascript:void(0);" onclick="addDateReceived({{ $psi->id }},'{{ $psi->code }}','{{ $psi->project->paymentTerm() }}')"><i class="icon-file-check2"></i></a>
											@endif
										  </td>
										  <td class="align-middle">
											Due date Payment {{ $psi->due_date ? date('d M Y',strtotime($psi->due_date)) : 'Empty' }}<br>
											Due date TT {{ $psi->due_date_tt ? date('d M Y',strtotime($psi->due_date_tt)) : 'Empty' }}<br>
											<a href="{{ $psi->attachment2() }}" target="_blank" class="btn bg-info"><i class="icon-search4"></i></a>
										  </td>
										  <td>
											<a href="javascript:void(0);" data-popup="tooltip" title="Add Notes" onclick="addNotes({{ $psi->id }})" class="btn bg-warning btn-sm"><i class="icon-file-text"></i></a>
										  </td>
										  <td>
											<a href="#step-16" onclick="editDelivery({{ $psi->id }})" class="btn bg-success"><i class="icon-pencil5"></i></a>
										  </td>
										  <td>
											@if(isset($psi->received_date))
												-
											@else
												<a href="javascript:void(0);" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="deleteDelivery({{ $psi->id }})"><i class="icon-trash"></i></a>
											@endif
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
			@php
				$approval = false;
				$ps = $project->latestDelivery();
				if($ps){
					if(isset($ps->acknowledge->name) || isset($ps->approve->name)){
						$approval = true;
					}
				}
				
				if($approval == false){
					echo '<div class="alert bg-warning text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Warning!</span> Please contact Accounting (approve) and Sales Manager (acknowledge) to Approve the required approvals to continue to the next step.
						</div>';
				}
			@endphp
			
			@if($project->progress >= 75 && $approval == true)
               <div class="card" id="step-17">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data" id="form-sales-return">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">17</span> Sales Return</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="moderetur" onclick="resetReturSales()">Add <i class="icon-loop3"></i></a></h3>
                        @if(isset($_GET['step-17']))
						  @if($errors->any())
							 <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								<ul>
								   @foreach ($errors->all() as $error)
									  <li>{{ $error }}</li>
								   @endforeach
								</ul>
							 </div>
						  @elseif(session('success'))
							 <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								{{ session('success') }}
							 </div>
						  @endif
					   @endif
					   <div class="row">
						<input type="hidden" id="temp_pr_id" name="temp_pr_id" value="">
						<div class="col-md-12 d-none edit-sh-full">
								<div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason_sales_retur" id="edit_reason_sales_retur" class="form-control" placeholder="Please describe why you edit this retur." rows="2"></textarea>
							    </div>
						  </div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Sales Order :<sup class="text-danger">*</sup></label>
									<select name="sor_id" id="sor_id" class="select2" onchange="getSalesProduct(this,this.value);">
									   <option value="">-- Choose --</option>
									   @foreach($project->projectSale as $ps)
										  <option value="{{ $ps->id }}">{{ $ps->code }}</option>
									   @endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Return Memo :</label>
									<select name="return_memo" id="return_memo" class="select2">
									   <option value="">-- Choose --</option>
									   @foreach($project->projectReturnMemo as $prm)
										  <option value="{{ $prm->id }}">{{ $prm->date.' - '.$prm->projectDelivery->code.' - '.$prm->reason }}</option>
									   @endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>Date :<span class="text-danger">*</span></label>
									<input type="date" name="sale_return_date" id="sale_return_date" class="form-control" value="{{ old('sale_return_date') }}" required>
								 </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>To Warehouse :<sup class="text-danger">*</sup></label>
								<select name="warehousereturn_id" id="warehousereturn_id"></select>
							 </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Return Type :<sup class="text-danger">*</sup></label>
									<select name="return_type" id="return_type" class="form-control">
										<option value="1">Return to supplier / split to another Project.</option>
										<option value="2">As a Cost.</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
                                <div class="form-group">
									<label>Proof of Sales Return :<sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Address :<sup class="text-danger">*</sup></label>
								<input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
							 </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Note :<sup class="text-danger">*</sup></label>
								<input type="text" name="note" id="note" class="form-control" value="{{ old('note') }}">
							 </div>
							</div>
					   </div>
					   <h5 class="card-title"><b><span class="badge badge-danger">17.a</span> Detail Sales Products</b></h5>
                        <div class="form-group"><hr></div>
						<div class="form-group">
                           <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                 <thead class="table-secondary">
                                    <tr class="text-center">
									   <th>No</th>
                                       <th>Product</th>
                                       <th>Qty</th>
									   <th>Unit</th>
									   <th>Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_sales_return">
                                    <td class="bg-warning" colspan="5" style="text-align:center;">Choose Sales Order first to see All products that can be returned.</td>
                                 </tbody>
                              </table>
                           </div>
                        </div>
						<div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="text-right">
							<button type="submit" name="submit" value="step-17" class="btn bg-purple submit_delivery">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed1">
								See All Sales Return
							</a>
						</div>
						
						<div class="form-group collapse" id="collapse-link-collapsed1">
							<h5 class="card-title"><b><span class="badge badge-danger">17.b</span> List of All Sales Return</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO Code</th>
									   <th>Code</th>
									   <th>Note</th>
									   <th>Approved By</th>
									   <th>Proof</th>
									   <th>Upload/Receive Goods</th>
									   <th><i class="icon-printer2"></i></th>
									   <th>Edit</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectSaleReturn()->orderBy('project_sale_id')->get() as $psr)
									   <tr class="text-center">
										  <td class="align-middle">{{ $psr->projectSale->code }}</td>
										  <td class="align-middle">{{ $psr->code }}</td>
										  <td class="align-middle">{{ $psr->note }}</td>
										  <td class="align-middle">
											@php
												if(isset($psr->approve->name)){
													echo $psr->approve->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSaleReturn(1,'.$psr->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $psr->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											<a href="javascript:void(0);" class="btn bg-success" onclick="uploadNewReturn({{ $psr->id }},'{{ $psr->attachment() }}')"><i class="icon-file-upload"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_return/' . base64_encode($psr->id)) }}')" href="javascript:void(0);" class="btn bg-primary"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td>
											<a href="#step-17" onclick="editSalesRetur({{ $psr->id }})" class="btn bg-success"><i class="icon-pencil5"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
            @if($project->progress >= 80 && $approval == true)
               <div class="card" id="step-18">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">18</span> Full Payment</b></h3>
                        <div class="form-group"><hr></div>
						<h5 class="card-title">Payment Information</h5>
						<dl class="row mb-0">
							<dt class="col-sm-3">Bank Destination</dt>
							<dd class="col-sm-9">: {!! $project->coa->name !!}</dd>

							<dt class="col-sm-3">Payment Method</dt>
							<dd class="col-sm-9">: {!! $project->paymentMethod() !!}</dd>

							<dt class="col-sm-3">Supply Method</dt>
							<dd class="col-sm-9">: {!! $project->supplyMethod() !!}</dd>

							<dt class="col-sm-3 text-truncate">PPN</dt>
							<dd class="col-sm-9">: {!! $project->ppn() !!}</dd>
							
							<dt class="col-sm-3 text-truncate">Term Payment</dt>
							<dd class="col-sm-9">: {!! $project->paymentTerm() !!}</dd>
							
						</dl>
						<div class="form-group"><hr></div>
						@if(isset($_GET['step-18']))
						  @if($errors->any())
							 <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								<ul>
								   @foreach ($errors->all() as $error)
									  <li>{{ $error }}</li>
								   @endforeach
								</ul>
							 </div>
						  @elseif(session('success'))
							 <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								{{ session('success') }}
							 </div>
						  @endif
						@endif
						<div class="row">
						  <div class="col-md-12 text-center">
							<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Attention!</span> Please select sales order first to see total sales payment, paid, and balance not paid in buttons below.</div>
							<button type="button" class="btn btn-primary mb-2">Total : <i class="icon-cash4 mr-2"></i> <b id="payment_total_full">0</b></button>
							&nbsp;
							<button type="button" class="btn btn-success mb-2">Paid : <i class="icon-cash4 mr-2"></i> <b id="payment_paid_full">0</b></button>
							&nbsp;
							<button type="button" class="btn btn-warning mb-2">Balance not paid : <i class="icon-cash4 mr-2"></i> <b id="payment_left_full">0</b></button>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Sales Order :<sup class="text-danger">*</sup></label>
								<select name="sop_id" id="sop_id" class="select2" onchange="getSalesInfo(this,this.value);">
								   <option value="">-- Choose --</option>
								   @foreach($project->projectSale as $ps)
									  <option value="{{ $ps->id }}">{{ $ps->code }}</option>
								   @endforeach
								</select>
							 </div>
						  </div>
						  
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Delivery & Proforma :</label>
								<select name="do_id_full" id="do_id_full" class="select2">
									<option value="">-- Empty --</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>From Bill :</label>
								<select name="bill_id_full" id="bill_id_full" class="select2">
								   <option value="">-- None --</option>
								   @foreach($project->projectBill as $pb)
									  <option value="{{ $pb->id }}">{{ $pb->code }}</option>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="date_create_full" id="date_create_full" class="form-control" value="{{ old('date_create_full') }}" onchange="countDueDateFull()">
							 </div>
						  </div>
						  
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Payment :<sup class="text-danger">*</sup></label>
								<select name="payment_method_full" id="payment_method_full" class="custom-select" onchange="">
								   <option value="">-- Choose --</option>
								   <option value="1" {{ old('payment_method') == 1 ? 'selected' : '' }} disabled>Down Payment</option>
								   <!-- <option value="2" {{ old('payment_method') == 2 ? 'selected' : '' }}>Full Payment Upfront</option>
								   <option value="3" {{ old('payment_method') == 3 ? 'selected' : '' }}>Full Payment Last</option> -->
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Percentage :<sup class="text-danger">*</sup></label>
								<input type="number" name="percentnominal_full" id="percentnominal_full" class="form-control" value="{{ old('percentnominal_full') ? old('percentnominal_full') : '0' }}" onkeyup="countPaymentFull(this.value)">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Nominal :<sup class="text-danger">*</sup></label>
								<input type="text" name="nominal_full" id="nominal_full" class="form-control" placeholder="0" value="{{ old('nominalfull') }}" onkeyup="formatRupiah(this)">
							 </div>
						  </div>
						  <div class="col-md-4">
                                <div class="form-group">
									<label>Proof of Payment :<sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file_full" name="file_full" class="form-control h-auto filefull" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Bank / Cash Destination :</label>
								<select name="bank_id_full" id="bank_id_full" class="select2">
								   <option value="22">-- None --</option>
									@foreach($bank->where('parent_id',0)->whereIn('code',['1.000.00']) as $c)
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
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Method (Transfer/Giro/Check/Cash) :<sup class="text-danger">*</sup></label>
								<select name="giro_full" id="giro_full" class="custom-select">
									<option value="0" {{ old('giro_full') == 0 ? 'selected' : '' }}>Transfer</option>
									<option value="1" {{ old('giro_full') == 1 ? 'selected' : '' }}>Giro</option>
									<option value="2" {{ old('giro_full') == 2 ? 'selected' : '' }}>Check</option>
									<option value="3" {{ old('giro_full') == 3 ? 'selected' : '' }}>Cash</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Note :</label>
								<textarea name="note_full" id="note_full" class="form-control" value="{{ old('note_full') }}" rows="1"></textarea>
							 </div>
							</div>
						  <div class="col-md-4 giro-class d-none">
							 <div class="form-group">
								<label>Giro Number :</label>
								<input type="text" name="giro_code_full" id="giro_code_full" class="form-control" value="-">
							 </div>
						  </div>
						  <div class="col-md-4 giro-class d-none">
							 <div class="form-group">
								<label>Giro Due Date :</label>
								<input type="date" name="giro_date_full" id="giro_date_full" class="form-control">
							 </div>
						  </div>
						  
						</div>
						<div class="form-group"><hr></div>
						<h5 class="card-title">Preview Proof</h5>
						<div class="form-group text-center" id="previewImgFull">
							<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="text-right">
							 <button type="submit" name="submit" value="step-18" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed2">
								See All Sales Payments
							</a>
						</div>
						
						<div class="form-group collapse" id="collapse-link-collapsed2">
							<h5 class="card-title"><b><span class="badge badge-danger">18.a</span> List of All Sales Payments</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO Code</th>
									   <th>Invoice</th>
									   <th>Date</th>
									   <th>Nominal</th>
									   <th>Note</th>
									   <th>Checked By</th>
									   <th>Marketing</th>
									   <th>Ack.By</th>
									   <th>Proof</th>
									   <th>Inv Product</th>
									   <th>Inv Delivery</th>
									   <!-- <th>Inv Other</th> -->
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPay()->orderBy('project_sale_id')->get() as $pp)
									   <tr class="text-center">
										  <td class="align-middle">{{ $pp->projectSale->code }}</td>
										  <td class="align-middle">{{ $pp->code }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($pp->date)) }}</td>
										  <td class="align-middle">IDR {{ number_format($pp->nominal,2,',','.') }}</td>
										  <td class="align-middle">{{ $pp->note }}</td>
										  <td class="align-middle">
											@php
												if(isset($pp->check->name)){
													echo $pp->check->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(3,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											@php
												if(isset($pp->marketing->name)){
													echo $pp->marketing->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(1,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										   <td class="align-middle">
											@php
												if(isset($pp->approved->name)){
													echo $pp->approved->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(2,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_invoice/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i> - ENG</a>
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_invoice/'. base64_encode($pp->id)) }}?la=idn')" href="javascript:void(0);" class="btn bg-success"><i class="icon-file-pdf"></i> - IDN</a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_invoice_delivery/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
										  <!-- <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_invoice_other/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td> -->
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
@php
	}else{
@endphp
			<div class="alert bg-danger text-white alert-styled-left alert-dismissible" style="font-size:25px;">
				<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
				<span class="font-weight-semibold">Warning!</span> This project has no Budgeting Project, please contact Ms. Ismi Suryaningtyas to make one, so you may continue to the next step. Or press this button <a href="javascript:void(0);" onclick="sendMessage(`{{ env('SALES_MANAGER_PHONE') }}`,`{{ $project->code }}`)" class="btn btn-success btn-icon rounded-pill"><i class="icon-phone2"></i></a>.
			</div>
@php
	}
@endphp
         </div>
		 
		 <div class="sidebar-sticky order-1 order-md-2">
			<!-- Secondary sidebar -->
			<div class="sidebar sidebar-light sidebar-secondary sidebar-component sidebar-component-right sidebar-expand-md">

				<!-- Sidebar content -->
				<div class="sidebar-content" style="overflow-y: scroll;background-color:#457c80 !important;height:90vh;">

					<div class="card">
						 <div class="card-header bg-transparent header-elements-inline" style="background-color:#457c80 !important;color:white !important;">
							<span class="text-uppercase font-size-sm font-weight-semibold">Project No. {{ $project->code }}</span>
						 </div>
						 <ul class="nav nav-sidebar nav-scrollspy">
							<li class="nav-item">
							   <a href="#step-1" class="nav-link">
								  <i class="icon-check text-success"></i>
								  1. Project Information
								  <span class="badge bg-warning badge-pill ml-auto">10%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-2" class="nav-link">
								  @if($project->progress >= 15)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  2. Spec Project
								  <span class="badge bg-warning badge-pill ml-auto">15%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-3" class="nav-link {{ $project->progress >= 15 ? '' : 'disabled' }}">
								  @if($project->progress >= 20)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  3. Consultant Meeting
								  <span class="badge bg-warning badge-pill ml-auto">20%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-4" class="nav-link {{ $project->progress >= 20 ? '' : 'disabled' }}">
								  @if($project->progress >= 25)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  4. Quotation
								  <span class="badge bg-warning badge-pill ml-auto">25%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-5" class="nav-link {{ $project->progress >= 25 ? '' : 'disabled' }}">
								  @if($project->progress >= 30)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  5. Form Sample
								  <span class="badge bg-warning badge-pill ml-auto">30%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-6" class="nav-link {{ $project->progress >= 30 ? '' : 'disabled' }}">
								  @if($project->progress >= 35)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  6. Negotiation
								  <span class="badge bg-warning badge-pill ml-auto">35%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-7" class="nav-link {{ $project->progress >= 35 ? '' : 'disabled' }}">
								  @if($project->progress >= 37)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  7. SO Project
								  <span class="badge bg-warning badge-pill ml-auto">37%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-8" class="nav-link {{ $project->progress >= 37 ? '' : 'disabled' }}">
								  @if($project->progress >= 40)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  8. Down Payment
								  <span class="badge bg-warning badge-pill ml-auto">40%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <hr style="width: 70%;border-top: 1px solid black;">
							   <a href="#step-9" class="nav-link {{ $project->progress >= 40 ? '' : 'disabled' }}">
								  @if($project->progress >= 43)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  9. PO Supplier
								  <span class="badge bg-warning badge-pill ml-auto">43%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-10" class="nav-link {{ $project->progress >= 43 ? '' : 'disabled' }}">
								  @if($project->progress >= 45)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  10. Proof of Proforma Inv
								  <span class="badge bg-warning badge-pill ml-auto">45%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-11" class="nav-link {{ $project->progress >= 45 ? '' : 'disabled' }}">
								  @if($project->progress >= 48)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  11. Down Payment Purchase
								  <span class="badge bg-warning badge-pill ml-auto">48%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-12" class="nav-link {{ $project->progress >= 48 ? '' : 'disabled' }}">
								  @if($project->progress >= 50)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  12. Progress Production
								  <span class="badge bg-warning badge-pill ml-auto">50%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-13" class="nav-link {{ $project->progress >= 50 ? '' : 'disabled' }}">
								  @if($project->progress >= 55)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  13. Pay Full Purchase
								  <span class="badge bg-warning badge-pill ml-auto">55%</span>
							   </a>
							</li>
							<li class="nav-item">
								
							   <a href="#step-14" class="nav-link {{ $project->progress >= 55 ? '' : 'disabled' }}">
								  @if($project->progress >= 60)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  14. Delivery Document Number
								  <span class="badge bg-warning badge-pill ml-auto">60%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-15" class="nav-link {{ $project->progress >= 60 ? '' : 'disabled' }}">
									@if($project->progress >= 65)
									 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									15. Warehouse Receive
									<span class="badge bg-warning badge-pill ml-auto">65%</span>
							   </a>
							</li>
							
							<li class="nav-item">
								<hr style="width: 70%;border-top: 1px solid black;">
							   <a href="#step-16" class="nav-link {{ $project->progress >= 65 ? '' : 'disabled' }}">
								  @if($project->progress >= 75)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  16. Delivery To Project
								  <span class="badge bg-warning badge-pill ml-auto">75%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-17" class="nav-link {{ $project->progress >= 75 ? '' : 'disabled' }}">
									@if($project->progress >= 80)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
									17. Sales Return
									<span class="badge bg-warning badge-pill ml-auto">80%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-18" class="nav-link {{ $project->progress >= 80 ? '' : 'disabled' }}">
								  @if($project->progress >= 85)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  18. Full Payment
								  <span class="badge bg-warning badge-pill ml-auto">85%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-19" class="nav-link {{ $project->progress >= 85 ? '' : 'disabled' }}">
								  @if($project->progress >= 90)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  19. Troubleshooting
								  <span class="badge bg-warning badge-pill ml-auto">90%</span>
							   </a>
							</li>
							<li class="nav-item">
							   <a href="#step-20" class="nav-link {{ $project->progress >= 90 ? '' : 'disabled' }}">
								  @if($project->progress >= 100)
									 <i class="icon-check text-success"></i>
								  @else
									 <i class="icon-spinner10"></i>
								  @endif
								  20. Done
								  <span class="badge bg-warning badge-pill ml-auto">100%</span>
							   </a>
							</li>
						 </ul>
					  </div>

				</div>
				<!-- /sidebar content -->

			</div>
			<!-- /secondary sidebar -->
		</div>
      </div>
   </div>
	<!-- Info modal -->
	<div id="modal_purchase_product" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Purchase <b id="modal_title_purchase_code">A</b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title"><b>Detail Products</b></h5>
					<div class="form-group"><hr></div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
									   <th>No</th>
                                       <th>Product</th>
                                       <th>Qty Needed</th>
									   <th>Qty Sent</th>
									   <th>Qty Left</th>
									   <th>Unit</th>
									   <th>M<sup>2</sup></th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_purchase_detail">
                                    
                                 </tbody>
                              </table>
                        </div>
                    </div>
					<div class="form-group">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th width="25%">Date</th>
									   <th>PO</th>
									   <th>Note</th>
									   <th>Proof</th>
									</tr>
								 </thead>
								 <tbody id="data_purchase_note">
									
								 </tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	<!-- Info modal -->
	<div id="modal_stock_product" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Stock <b id="modal_title_stock_product"></b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title"><b>Detail Stock</b></h5>
					<div class="form-group"><hr></div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
									   <th>No</th>
                                       <th>Warehouse</th>
                                       <th>Stock Code</th>
									   <th>Code</th>
									   <th>Qty</th>
									   <th>Unit</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_stock_product">
                                    
                                 </tbody>
								 <tfoot class="font-weight-black" style="font-size:20px;">
									<tr>
										<td colspan="4" class="text-right">Total</td>
										<td class="text-center" id="totalshading">0</td>
										<td class="text-center"></td>
									</tr>
								 </tfoot>
                              </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	<!-- Info modal -->
	<div id="modal_tracking_shipment" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Shipment No. <b id="modal_title_tracking_shipment"></b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title">
						<b>Detail Tracking Shipment</b> 
						<a href="javascript:void(0);" target="_blank" id="link-tracking-shipment" class="btn btn-primary btn-sm float-right ml-1">View <i class="icon-file-eye"></i></a>
						<a href="javascript:void(0);" id="email-tracking-shipment" class="btn btn-info btn-sm float-right ml-1">Email <i class="icon-envelop3"></i></a>
						<a href="javascript:void(0);" id="whatsapp-tracking-shipment" class="btn btn-success btn-sm float-right ml-1">Whatsapp <i class="icon-phone-plus"></i></a>
					</h5>
					
					<div class="form-group"><hr></div>
					<div class="row">
					   <div class="col-md-10">
						  <div class="form-group">
							<input type="hidden" id="tempshipmentid">
							 <input type="text" class="form-control" name="tracking-shipment-note" id="tracking-shipment-note" placeholder="Type note">
						  </div>
					   </div>
					   <div class="col-md-2">
						  <div class="form-group">
							 <button type="button" onclick="addTrackingShipmentDetail(this)" class="btn bg-success col-12" id="btnaddtrackingshipment"><i class="icon-plus2"></i> Add</button>
						  </div>
					   </div>
					</div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th width="25%">Date</th>
                                       <th>Note</th>
									   <th width="15%">Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_shipment_tracking">
                                    <tr>
										<td colspan="3">
											<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
										</td>
									</tr>
                                 </tbody>
                              </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	<!-- Info modal -->
	
	<!-- Small modal -->
	<div id="modal_date_received" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content" style="max-width: 600px !important;max-height: 100% !important;">
				<div class="modal-header">
					<h5 class="modal-title">Delivery No. <b id="modal_title_received_date"></b></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modal-body-received">
					<div class="form-group"><hr></div>
					<h5 class="card-title">
						<b>Received Date</b> 
					</h5>
					@php
						$mindate = date('Y-m-d');
						
						foreach($project->projectWarehouse()->orderBy('date_receive')->get() as $key => $row){
							if($key == 0){
								$mindate = explode(' ',$row->date_receive)[0];
							}
						}
						
					@endphp
					<div class="row">
					   <div class="col-md-6">
						  <div class="form-group text-center">
							<label>Date Received</label>
							<input type="hidden" id="tempreceived">
							<input type="date" name="received_date" id="received_date" class="form-control" placeholder="choose date received by customer" min="{{ $mindate }}">
						  </div>
					   </div>
					   <div class="col-md-6">
						  <div class="form-group text-center">
							<label>Due Date Invoice / Bill</label>
							<input type="date" name="received_due_date" id="received_due_date" class="form-control" placeholder="choose due date received by customer">
							Term Payment Information : <span class="badge badge-info" id="info_received_due_date"></span> taken from project creation. Please ask sales to confirm.
						  </div>
					   </div>
					   <div class="col-md-6">
						  <div class="form-group text-center">
							<label>File proof</label>
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="received_proof" name="received_proof" class="form-control h-auto filereceived" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
						  </div>
					   </div>
					</div>
					<h5 class="card-title">Preview Proof</h5>
					<div class="form-group text-center" id="previewImgReceived">
						<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
					</div>
					<div class="form-group">
						<div class="alert alert-danger alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">WARNING!</span> The product's quantity that you input here will affect total sales inputted before.</div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th>Product</th>
                                       <th>Qty Received</th>
									   <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody id="data_qty_received">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn bg-primary" onclick="addReceivedDate()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /small modal -->
	
	<!-- Small modal -->
	<div id="modal_update_received" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content" style="max-width: 800px !important;max-height: 100% !important;">
				<div class="modal-header">
					<h5 class="modal-title">Delivery No. <b id="modal_title_update_received"></b></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modal-body-proof">
					<div class="form-group"><hr></div>
					<h5 class="card-title">
						<b>Update Proof Received</b> 
					</h5>
					<div class="row">
					   <div class="col-md-6">
						  <div class="form-group">
							 <div class="input-group">
							   <label>Proof</label>
							   <input type="hidden" id="tempreceivedproof">
							   <div class="custom-file">
								  <input type="file" id="received_proof_update" name="received_proof_update" class="form-control h-auto filereceivedproof" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
						  </div>
					   </div>
					</div>
					<h5 class="card-title">Preview Proof</h5>
					<div class="form-group text-center" id="previewImgReceivedProof">
						<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
					</div>
					<hr>
					<h5 class="card-title">
						<b>Update Receipt Invoice</b>
					</h5>
					<div class="row">
					   <div class="col-md-6">
						  <div class="form-group">
							 <div class="input-group">
							   <label>Proof</label>
							   <div class="custom-file">
								  <input type="file" id="receipt_proof" name="receipt_proof" class="form-control h-auto filereceiptproof" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
						  </div>
					   </div>
					   <div class="col-md-6">
						  <div class="form-group">
							<label>Due Date TT</label>
							<input type="date" name="due_date_tt" id="due_date_tt" class="form-control" placeholder="choose due date tt by customer" value="{{ date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d')))) }}">
						  </div>
					   </div>
					</div>
					<h5 class="card-title">Preview Proof</h5>
					<div class="form-group text-center" id="previewImgReceiptProof">
						<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn bg-primary" onclick="addReceivedProof()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /small modal -->
	
	<div id="modal_tracking_delivery" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Delivery No. <b id="modal_title_tracking_delivery"></b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title">
						<b>Detail Tracking Delivery</b> 
						<a href="javascript:void(0);" target="_blank" id="link-tracking-delivery" class="btn btn-primary btn-sm float-right ml-1">View <i class="icon-file-eye"></i></a>
						<a href="javascript:void(0);" id="email-tracking-delivery" class="btn btn-info btn-sm float-right ml-1" onclick="emailTrackingDelivery()">Email <i class="icon-envelop3"></i></a>
						<a href="javascript:void(0);" target="_blank" id="whatsapp-tracking-delivery" class="btn btn-success btn-sm float-right ml-1">Whatsapp <i class="icon-phone-plus"></i></a>
					</h5>
					
					<div class="form-group"><hr></div>
					<div class="row">
					   <div class="col-md-5">
						  <div class="form-group">
							<input type="hidden" id="tempdeliveryid">
							 <input type="text" class="form-control" name="tracking-delivery-note" id="tracking-delivery-note" placeholder="Type note">
						  </div>
					   </div>
					   <div class="col-md-5">
						  <div class="form-group">
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="tracking-delivery-file" name="tracking-delivery-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg">
							   </div>
							</div>
						  </div>
					   </div>
					   <div class="col-md-2">
						  <div class="form-group">
							 <button type="button" data-id="" onclick="addTrackingDeliveryDetail(this)" class="btn bg-success col-12" id="btnaddtrackingdelivery"><i class="icon-plus2"></i> Add</button>
						  </div>
					   </div>
					</div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th width="25%">Date</th>
                                       <th>Note</th>
									   <th>Proof</th>
									   <th width="15%">Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="data_delivery_tracking">
                                    <tr>
										<td colspan="4">
											<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
										</td>
									</tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	<div id="modal_return" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h4 class="modal-title">Add Return Proof / Received Date<span id="title-bill"></span></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="body-return">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Date Received :<sup class="text-danger">*</sup></label>
								<input type="date" name="return_date" id="return_date" class="form-control" placeholder="choose date received by customer">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Proof of Payment :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="id_return" name="id_return">
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file_return" name="file_return" class="form-control h-auto file_return" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group"><hr></div>
							<h5 class="card-title">Preview Proof</h5>
							<div class="form-group text-center" id="previewImgFileReturn">
								<img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
							</div>
						  <div class="form-group">
							 <button type="button" onclick="addReturnProof()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add</button>
						  </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Info modal -->
	<div id="modal_bill" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h4 class="modal-title">Add Project Bill <span id="title-bill"></span></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="body-bill">
					<div class="row">
						  <div class="col-md-4">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <input type="hidden" id="temp_bill_edit" name="temp_bill_edit">
							  <select name="branch_bill" id="branch_bill" class="custom-select">
								@foreach (DB::table('company_entities')->get() as $company)
									<option value="{{$company->id}}" {{$branch == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
								@endforeach
							  </select>
						   </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Date Create :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="temp_bill" name="temp_bill">
								<input type="date" name="date_bill" id="date_bill" class="form-control">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Due Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="due_date_bill" id="due_date_bill" class="form-control">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Nominal Product :<sup class="text-danger">*</sup></label>
								<input type="text" name="nominal_bill" id="nominal_bill" class="form-control" placeholder="0" onkeyup="formatRupiah(this)" value="0">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Nominal Service :<sup class="text-danger">*</sup></label>
								<input type="text" name="nominal_service_bill" id="nominal_service_bill" class="form-control" placeholder="0" onkeyup="formatRupiah(this)" value="0">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Address :<sup class="text-danger">*</sup></label>
								<textarea type="text" name="address_bill" id="address_bill" class="form-control" rows="1"></textarea>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Note :<sup class="text-danger">*</sup></label>
								<textarea type="text" name="note_bill" id="note_bill" class="form-control" rows="1"></textarea>
							 </div>
						  </div>
						  <div class="col-md-12">
							  <div class="form-group">
								 <button type="button" onclick="addBillToCustomer()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add</button>
							  </div>
						  </div>
						  <input type="hidden" name="nominal_so_real" id="nominal_so_real" value="{{$nominal_so_real}}">
						  <input type="hidden" name="nominal_service_real" id="nominal_service_real" value="{{$nominal_service_real}}">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	
	<div class="modal fade" id="modal_notes" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add Delivery Order Notes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Information!</span> This note will appear to A/R report.
						</div>
					</div>
					<div class="col-md-5">
					  <div class="form-group">
					    <input type="hidden" name="tempSales" id="tempSales">
						<input type="text" class="form-control" name="sales_note_kuy" id="sales_note_kuy" placeholder="Type note">
					  </div>
					</div>
					<div class="col-md-5">
					  <div class="form-group">
						 <div class="input-group">
						   <div class="custom-file">
							  <input type="file" id="sales_file_kuy" name="sales_file_kuy" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
						   </div>
						</div>
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
						 <button type="button" onclick="addSalesNote()" class="btn bg-success col-12" id="btnaddpurchasenote"><i class="icon-plus2"></i> Add</button>
					  </div>
					</div>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
					<h5><b>List of All Delivery Notes</b></h5>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead class="table-secondary">
								<tr class="text-center">
								   <th width="25%">Date</th>
								   <th>Project</th>
								   <th>Note</th>
								   <th>Proof</th>
								</tr>
							 </thead>
							 <tbody id="data_sales_note">
								<tr>
									<td colspan="4">
										<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes here.</div>
									</td>
								</tr>
							 </tbody>
						</table>
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_documents" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add Sales Project Document</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-3">
					  <div class="form-group">
						<label>Date release :<sup class="text-danger">*</sup></label>
					    <input type="hidden" name="tempSalesProject" id="tempSalesProject">
						<input type="date" class="form-control" name="date_tax" id="date_tax">
					  </div>
					</div>
					<div class="col-md-3">
					  <div class="form-group">
						<label>Nominal :<sup class="text-danger">*</sup></label>
						<input type="text" class="form-control" name="nominal_tax" id="nominal_tax" placeholder="Nominal Tax" onkeyup="formatRupiah(this)">
					  </div>
					</div>
					<div class="col-md-3">
					  <div class="form-group">
						<label>Document No. :<sup class="text-danger">*</sup></label>
						<input type="text" class="form-control" name="no_tax" id="no_tax" placeholder="Document no">
					  </div>
					</div>
					<div class="col-md-3">
					  <div class="form-group">
						 <label>Proof :<sup class="text-danger">*</sup></label>
						 <div class="input-group">
						   <div class="custom-file">
							  <input type="file" id="sales_file_tax" name="sales_file_tax" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
						   </div>
						</div>
					  </div>
					</div>
					<div class="col-md-12">
					  <div class="form-group">
						 <label>&nbsp;</label>
						 <button type="button" onclick="saveTaxDocument()" class="btn btn-block bg-success col-12"><i class="icon-plus2"></i> Add</button>
					  </div>
					</div>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
					<h5><b>List of All Sales Tax Document</b></h5>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead class="table-secondary">
								<tr class="text-center">
								   <th>Date</th>
								   <th>No. Tax</th>
								   <th>Nominal</th>
								   <th>Proof</th>
								   <th>Action</th>
								</tr>
							 </thead>
							 <tbody id="data_sales_tax">
								<tr>
									<td colspan="5">
										<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tax here.</div>
									</td>
								</tr>
							 </tbody>
						</table>
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
	var tempSalesProject = 0;
   $(function() {
	    var element = $('.sidebar-sticky'),
			originalY = element.offset().top;

		// Space between element and top of screen (when scrolling)
		var topMargin = 75;

		// Should probably be set in CSS; but here just for emphasis
		element.css('position', 'relative');

		$(window).on('scroll', function(event) {
			var scrollTop = $(window).scrollTop();

			element.stop(false, false).animate({
				top: scrollTop < originalY
						? 0
						: scrollTop - originalY + topMargin
			}, 250);
		});
	   
	   $('#giro').on('change', function(){
		   if($(this).val() == '1'){
			   $('.giro-class').removeClass('d-none');
		   }else{
			   $('.giro-class').addClass('d-none');
		   }
	   });
	   
	   $('#giro_full').on('change', function(){
		   if($(this).val() == '1'){
			   $('.giro-class').removeClass('d-none');
		   }else{
			   $('.giro-class').addClass('d-none');
		   }
	   });

	   
      var no = 0;

      $('.sidebar-main-toggle').click();
      select2ServerSide('#product_id,#product_id2', '{{ url("admin/select2/product") }}');
	  select2ServerSide('#sales_id,#sales_po', '{{ url("admin/select2/user") }}');
	  select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
	  select2ServerSide('#supplier_id', '{{ url("admin/select2/supplier") }}');
	  select2ServerSide('#warehouse_id,#warehousereturn_id,#warehousedeliver_id,#warehouse_destination', '{{ url("admin/select2/warehouse") }}');
      select2ServerSide('#sample_product_id', '{{ url("admin/select2/product") }}');
	  select2ServerSide('#country_id', '{{ url("admin/select2/country") }}');
	  select2ServerSide('#city_id,#city_id2', '{{ url("admin/select2/city") }}');
	  //select2ServerSide('#currency', '{{ url("admin/select2/currency") }}');
      
      $('#data_product').on('click', '#delete_data_product', function() {
         $(this).closest('tr').remove();
		 $('.warning-product').fadeIn(500);
      });

      $('#data_consultant').on('click', '#delete_data_consultant', function() {
         $(this).closest('tr').remove();
		 $('.warning-consultant').fadeIn(500);
      });
	  
	  $('#data_negotiation').on('click', '#delete_data_negotiation', function() {
         $(this).closest('tr').remove();
		 $('.warning-negotiation').fadeIn(500);
      });

      $('#data_sample').on('click', '#delete_data_sample', function() {
         $(this).closest('tr').remove();
		 $('.warning-sample').fadeIn(500);
      });
	  
	  $('#data_purchase').on('click', '#delete_data_product_purchase', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_shipment_product').on('click', '#delete_ship_product', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_warehouse_product').on('click', '#delete_shipment_product', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_purchase_return').on('click', '#delete_purchase_return_product', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_delivery_products').on('click', '#delete_delivery_products', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_sales_return').on('click', '#delete_sales_return_products', function() {
         $(this).closest('tr').remove();
      });
	  $('#data_transfer_product').on('click', '#delete_transfer_product', function() {
         $(this).closest('tr').remove();
      });
	  
		$(".filedp").on('change', function () {

			if (typeof (FileReader) != "undefined") {

				var image_holder = $("#previewImgDp");
				image_holder.empty();

				var reader = new FileReader();
				reader.onload = function (e) {
					$("<img />", {
						"src": e.target.result,
						"class": "thumb-image",
						"width": "300px"
					}).appendTo(image_holder);
				};
				image_holder.show();
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		});
		
		$(".filefull").on('change', function () {

			if (typeof (FileReader) != "undefined") {

				var image_holder = $("#previewImgFull");
				image_holder.empty();

				var reader = new FileReader();
				reader.onload = function (e) {
					$("<img />", {
						"src": e.target.result,
						"class": "thumb-image",
						"width": "300px"
					}).appendTo(image_holder);
				};
				image_holder.show();
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		});
		
		$(".filereceived").on('change', function () {

			if (typeof (FileReader) != "undefined") {

				var image_holder = $("#previewImgReceived");
				image_holder.empty();

				var reader = new FileReader();
				reader.onload = function (e) {
					$("<img />", {
						"src": e.target.result,
						"class": "thumb-image",
						"width": "300px"
					}).appendTo(image_holder);
				};
				image_holder.show();
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		});
		
		$(".filereceivedproof").on('change', function () {

			if (typeof (FileReader) != "undefined") {

				var image_holder = $("#previewImgReceivedProof");
				image_holder.empty();

				var reader = new FileReader();
				reader.onload = function (e) {
					$("<img />", {
						"src": e.target.result,
						"class": "thumb-image ",
						"width": "500px"
					}).appendTo(image_holder);
				};
				image_holder.show();
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		});
		
		$(".filereceiptproof").on('change', function () {

			if (typeof (FileReader) != "undefined") {

				var image_holder = $("#previewImgReceiptProof");
				image_holder.empty();

				var reader = new FileReader();
				reader.onload = function (e) {
					$("<img />", {
						"src": e.target.result,
						"class": "thumb-image ",
						"width": "500px"
					}).appendTo(image_holder);
				};
				image_holder.show();
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		});
		
		$(".file_return").on('change', function () {

			if (typeof (FileReader) != "undefined") {

				var image_holder = $("#previewImgFileReturn");
				image_holder.empty();

				var reader = new FileReader();
				reader.onload = function (e) {
					$("<img />", {
						"src": e.target.result,
						"class": "thumb-image",
						"width": "300px"
					}).appendTo(image_holder);
				};
				image_holder.show();
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		});
		
		$('#modal_bill').on('hidden.bs.modal', function (e) {
			$('#temp_bill_edit').val('');
			$('#date_bill').val('');
			$('#due_date_bill').val('');
			$('#nominal_bill').val('');
			$('#nominal_service_bill').val('');
			$('#address_bill').val('');
			$('#note_bill').val('');
			$('#temp_bill').val('');
		});
		
		$('#modal_documents').on('hidden.bs.modal', function (e) {
			location.reload();
		});
		
		$("form").submit(function() {
			$(this).submit(function() {
				return false;
			});
			return true;
		});
   });
   
	function addNotes(id){
		$('#tempSales').val(id);
		tempSales = id;
		
		$.ajax({
			 url: '{{ url("admin/delivery_order/project/get_delivery_note") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: tempSales
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				$('#data_sales_note').empty();
				if(response.length > 0){
					$.each(response, function(i, val) {
						var checkedbox = '';
						if(val.is_public == '1'){
							checkedbox = 'checked';
						}
						$('#data_sales_note').append(`
							<tr class="text-center">
								<td>` + val.created_at + `</td>
								<td>` + val.code + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
							</tr>
						`);
					});
				}else{
					$('#data_sales_note').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes data.</div>
							</td>
						</tr>
					`);
				}
				
				loadingClose('.modal-content');
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
		
		$('#modal_notes').modal('toggle');
	}
	
	function addSalesNote(){
		var id = $('#tempSales').val(), note = $('#sales_note_kuy').val();
		var fd = new FormData(), files = $('#sales_file_kuy')[0].files;
		fd.append('note',note);
		fd.append('mode','project_deliveries');
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/add_sales_notes") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_sales_note');
			},
			success: function(response) {
				$('#data_sales_note').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						$('#data_sales_note').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString('en-GB') + `</td>
								<td>` + val.code + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
							</tr>
						`);
					});
				}else{
					$('#data_sales_note').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#sales_file_kuy').val('');
				$('#sales_note_kuy').val('');
				
				loadingClose('#data_sales_note');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
   
	function getShading(val){
		if(val !== ''){
			$.ajax({
				url: '{{ url("admin/delivery_order/project/get_shading_product") }}',
				type: 'GET',
			 dataType: 'JSON',
			 data: {
				val : val
			 },
			 beforeSend: function() {
				loadingOpen('.content');
			 },
			 success: function(response) {
				var total = 0;
				if(response.shading.length > 0) {
					var no = 1;
					$('#data_stock_product').empty();
					$.each(response.shading, function(i, val) {
						$('#data_stock_product').append(`
							<tr class="text-center">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.warehouse_code + `</td>
							 <td class="align-middle">
								` + val.stock_code + `
							 </td>
							 <td class="align-middle">
								` + val.code +`
							 </td>
							 <td class="align-middle">
								` + val.qty +`
							 </td>
							 <td class="align-middle">
								` + val.unit +`
							 </td>
							</tr>
						`);
						no++;
						total += parseFloat(val.qty);
					});
					$('#modal_title_stock_product').html(response.product_name);
					$('#totalshading').html(total);
					$('#modal_stock_product').modal('toggle');
				}else{
					swalInit.fire('Info Stock!', 'Stock / shading is not available.', 'info');
				}
				
				loadingClose('.content');
			 },
			 error: function() {
				loadingClose('.content');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
			});
		}
	}
	
	function getListShading(val){
		if(val !== ''){
			$.ajax({
				url: '{{ url("admin/delivery_order/project/get_shading_product") }}',
				type: 'GET',
			 dataType: 'JSON',
			 data: {
				val : val
			 },
			 beforeSend: function() {
				loadingOpen('.content');
			 },
			 success: function(response) {
				if(response.shading.length > 0) {
					$('#warehouse_from').empty();
					$.each(response.shading, function(i, val) {
						$('#warehouse_from').append(`
							<option value="` + val.product_id + `_` + val.warehouse_code + `_` + val.warehouse_name + `">` + val.warehouse_code + ` Shading : ` + val.code  + ` Stok : ` + val.qty + ` ` + val.unit + `</option>
						`);
					});
				}else{
					swalInit.fire('Info Stock!', 'Stock / shading is not available.', 'info');
				}
				
				loadingClose('.content');
			 },
			 error: function() {
				loadingClose('.content');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
			});
		}
	}
	
	function getSalesInfo(element, idprojectsale) {
		var elemen = element.getAttribute('name');
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_sales_info") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idprojectsale : idprojectsale
         },
         beforeSend: function() {
			if(elemen == 'so_id'){
				loadingOpen('#step-9');
			}else if(elemen == 'sop_id'){
				loadingOpen('#step-18');
			}else if(elemen == 'sod_id'){
				loadingOpen('#step-16');
			}else if(elemen == 'sopd_id'){
				loadingOpen('#step-8');
			}
         },
         success: function(response) {
			if(elemen == 'so_id'){
			 
				loadingClose('#step-9');
				
				if(response) {
					$('#sales_po').empty();
					$('#customer_id').empty();
					$('#sales_po').append(`
						<option value="` + response.sales_id + `">` + response.sales_name + `</option>
					`);
					$('#customer_id').append(`
						<option value="` + response.customer_id + `">` + response.customer_name + `</option>
					`);
				}
			}else if(elemen == 'sop_id'){
				loadingClose('#step-18');
				
				if(response) {
					$('#payment_total_full').text(response.payment_total);
					$('#payment_paid_full').text(response.payment_paid);
					$('#payment_left_full').text(response.payment_left);
					
					$('#do_id_full').empty();
					
					$.each(response.do_list, function(i, val) {
						$('#do_id_full').append(`
							<option value="` + val.id + `">` + val.code + ` - ` + val.proforma_code + ` total : ` + val.balance + `</option>
						`);
					});
				}
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
				
				if(response) {
					$('#receiver_name').val(response.customer_name);
					$('#email').val(response.customer_email);
					$('#phone').val(response.customer_phone);
					$('#address').val(response.sales_address);
					if(response.warehouse){
						$('#warehousedeliver_id').empty();
						$('#warehousedeliver_id').append(`
							<option value="` + response.warehouse.id + `">` + response.warehouse.name  + `</option>
						`);
					}
					$('#city_id2').empty();
					$('#city_id2').append(`
						<option value="` + response.city_id + `">` + response.city_name + `</option>
					`);
				}
			}else if(elemen == 'sopd_id'){
				loadingClose('#step-8');
				
				if(response) {
					$('#do_id').empty();
					$('#do_id').append(`
							<option value="">-- Empty --</option>
					`);
					$.each(response.do_list, function(i, val) {
						$('#do_id').append(`
							<option value="` + val.id + `">` + val.code + ` - ` + val.proforma_code + ` total : ` + val.balance + `</option>
						`);
					});
				}
			}
         },
         error: function() {
            if(elemen == 'so_id'){
				loadingClose('#step-9');
			}else if(elemen == 'sop_id'){
				loadingClose('#step-18');
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
			}
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getSupplierCurrency(idsupp) {
		$('#currency').empty();
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_supplier_currency") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idsupp : idsupp
         },
         beforeSend: function() {
            loadingOpen('#step-9');
         },
         success: function(response) {
            loadingClose('#step-9');
				if(response.length > 0) {
					$.each(response, function(i, val) {
						$('#currency').append(`
							<option value="` + val.id + `">` + val.code + ` ` + val.name + ` ` + val.symbol + `</option>
						`);
					});
				}
         },
         error: function() {
            loadingClose('#step-9');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getSalesProduct(element, idprojectsale) {
		var elemen = element.getAttribute('name');
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_sales_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idprojectsale : idprojectsale
         },
         beforeSend: function() {
			if(elemen == 'so_id'){
				loadingOpen('#step-9');
			}else if(elemen == 'sor_id'){
				loadingOpen('#step-17');
			}else if(elemen == 'sod_id'){
				loadingOpen('#step-16');
			}
         },
         success: function(response) {
			 
			if(elemen == 'so_id'){
				loadingClose('#step-9');
				
				if(response.length > 0) {
					$('#data_purchase').empty();
					
					$.each(response, function(i, val) {
						$('#data_purchase').append(`
							<tr class="text-center rowproductsale purchaseproductdata` + val.product_id + `" data-m2="` + val.m2 + `">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + ` ` + val.unit + `
							 </td>
							 <td class="align-middle">
								` + val.qty_left +`
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" id="purchaseproductqty` + val.product_id + `" class="form-control" placeholder="0" value="` + val.qty_left +`" required>
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_price[]" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);countTotalPurchase(this,'`+ val.product_id +`');">
							 </td>
							 <td class="align-middle">
								<div id="purchaseproducttotal`+ val.product_id +`"></div>
							 </td>
							 <td class="align-middle">
								<textarea class="form-control" rows="1" name="product_remark[]"></textarea>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_data_product_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
					});
				}
			}else if(elemen == 'sor_id'){
				loadingClose('#step-17');
				
				if(response.length > 0) {
					$('#data_sales_return').empty();
					var no = 1;
					
					$.each(response, function(i, val) {
						$('#data_sales_return').append(`
							<tr class="text-center">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="0" required>
							 </td>
							 <td class="align-middle">
								<select name="product_unit[]" class="custom-select" required>
								   <option value="1">Pcs</option>   
								   <option value="2">Box</option>   
								   <option value="3">Meter</option>   
								   <option value="4">Meter (Custom)</option>   
								</select>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_sales_return_products" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
							</tr>
						`);
						no++;
					});
				}
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
				
				if(response.length > 0) {
					$('#data_delivery_products').empty();
					var no = 1;
					
					$.each(response, function(i, val) {
						$('#data_delivery_products').append(`
							<tr class="text-center">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.unitconvert + `">
							 <input type="hidden" name="product_stock[]" value="` + val.nominalstock + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + ` ` + val.unit + `
							 </td>
							 <td class="align-middle">
								` + val.qty_left_deliver +`
							 </td>
							 <td class="align-middle">
								` + val.stock +`
							 </td>
							 <td class="align-middle">
								<input type="number" step="any" name="product_qty[]" class="form-control" placeholder="0" value="` + val.qty_left_deliver +`" required>
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_shading[]" class="form-control" placeholder="Type shading here" value="` + val.shading +`" required>
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_qty_deduction[]" class="form-control" placeholder="Type shading here" value="` + val.qtydeduction +`" required>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_delivery_products" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
						no++;
					});
				}
			}
         },
         error: function() {
			
            if(elemen == 'so_id'){
				loadingClose('#step-9');
			}else if(elemen == 'sor_id'){
				loadingClose('#step-17');
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
			}
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getPurchaseProduct(elemen,idpo){
		var elemen = elemen.getAttribute('name');
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_purchase_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
			 if(elemen == 'pos_id'){
				loadingOpen('#step-14');
			 }
         },
         success: function(response) {
			 
			var no = 1;
			
			if(elemen == 'pos_id'){
				if(response.length > 0) {
					$('#data_shipment_product').empty();
					
					$.each(response, function(i, val) {
						$('#data_shipment_product').append(`
							<tr class="text-center" data-m2="` + val.m2 + `">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.convertunit + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + `
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" class="form-control" placeholder="" value="` + val.qty_left +`" required>
							 </td>
							 <td class="align-middle">
								` + val.unit + `
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_ship_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
						
						no++;
					});
					
				}
			}
			
			if(elemen == 'pos_id'){
				loadingClose('#step-14');
			}
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function showPurchaseProduct(element,idpo){
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_purchase_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
			 loadingOpen('.order-2');
         },
         success: function(response) {
			$('#modal_title_purchase_code').html(element.innerHTML);
			 
			if(response.length > 0) {
				$('#data_purchase_detail').empty();
				
				var no = 1;
				
				$.each(response, function(i, val) {
					$('#data_purchase_detail').append(`
						<tr class="text-center" data-m2="` + val.m2 + `">
						 <td>` + no + `</td>
						 <td class="align-middle">` + val.product_name + `</td>
						 <td class="align-middle">
							` + val.qty + `
						 </td>
						 <td class="align-middle">
							` + val.qty_sent + `
						 </td>
						 <td class="align-middle">
							` + val.qty_left + `
						 </td>
						 <td class="align-middle">
							` + val.unit + `
						 </td>
						 <td class="align-middle">
							` + val.fixunit + `
						 </td>
					  </tr>
					`);
					
					no++;
				});
				
				$('#modal_purchase_product').modal('toggle');
				
				loadingClose('.order-2');
			}
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getShipmentInfo(idpo){
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_shipment_info") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
            loadingOpen('#step-15');
         },
         success: function(response) {
            loadingClose('#step-15');
				$('#shipment_id').empty();
				$('#shipment_id').append('<option value="">-- Empty --</option>');
				if(response.shipment_list.length > 0) {
					$.each(response.shipment_list, function(i, val) {
						$('#shipment_id').append(`
							<option value="` + val.shipment_id + `">` + val.shipment_code + `</option>
						`);
					});
				}
         },
         error: function() {
            loadingClose('#step-15');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getShipmentProduct(idshipment){
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_shipment_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idshipment : idshipment
         },
         beforeSend: function() {
            loadingOpen('#step-15');
         },
         success: function(response) {
            loadingClose('#step-15');
				if(response.shipment_product.length > 0) {
					$('#data_warehouse_product').empty();
					
					var no = 1;
					
					$.each(response.shipment_product, function(i, val) {
						$('#data_warehouse_product').append(`
							<tr class="text-center">
								 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
								 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
								 <td>` + no + `</td>
								 <td class="align-middle">` + val.product_name + `</td>
								 <td class="align-middle">
									<input type="number" name="product_qty[]" class="form-control" value="` + val.qty + `" required>
								 </td>
								 <td class="align-middle">
									` + val.unit + `
								 </td>
								 <td class="align-middle">
									<input type="number" name="product_qty_broken[]" class="form-control" value="0" required>
								 </td>
								 <td class="align-middle">
									` + val.unit + `
								 </td>
								 <td class="align-middle">
									<button type="button" id="delete_shipment_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
								 </td>
							</tr>
						`);
						
						no++;
					});
				}
         },
         error: function() {
            loadingClose('#step-15');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getPurchaseInfo(element,purchaseid) {
		var elemen = element.getAttribute('name');
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_purchase_info") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            purchaseid : purchaseid
         },
         beforeSend: function() {
			if(elemen == 'pop_id'){
				loadingOpen('#step-10');
			}else if(elemen == 'pr_id'){
				loadingOpen('#step-12');
			}else if(elemen == 'por_id'){
				loadingOpen('#step-13');
			}else{
				loadingOpen('#step-11');
			}
         },
         success: function(response) {
			if(elemen == 'pop_id'){
				loadingClose('#step-10');
			}else if(elemen == 'pr_id'){
				loadingClose('#step-12');
			}else if(elemen == 'por_id'){
				loadingClose('#step-13');
			}else{
				loadingClose('#step-11');
			}
			
			if(response) {
				if(elemen == 'pop_id'){
					$('#supplier_name').val(response.supplier_name);
				}else if(elemen == 'po_id'){
					$('#proforma_total').html(response.total);
					$('#proforma_paid').html(response.totalpaid);
					$('#proforma_total_raw').val(response.totalraw);
					$('#percentage').trigger('keyup');
				}else if(elemen == 'por_id'){
					$('#data_payment_purchase').empty();
					if(response.datapayment.length > 0){
						$('#proforma_total_1').html(response.total);
						$('#proforma_paid_1').html(response.totalpaid);
						$('#nominal_payment').val(response.totalleft);
						$('#paymentotal').html(response.total);
						$('#po-payment-code').html(element.options[element.selectedIndex].text);
						
						$.each(response.datapayment, function(i, val) {
							$('#data_payment_purchase').append(`
								<tr class="text-center">
									<td>`+ val.date +`</td>
									<td>`+ val.bank +`</td>
									<td>`+ val.nominal +`</td>
									<td>`+ val.status +`</td>
								</tr>
							`);
						});
					}else{
						$('#data_payment_purchase').append(`
							<tr>
								<td class="bg-warning" colspan="4" style="text-align:center;">Choose Purchase Order first to see detail payment each Purchases</td>
							</tr>
						`);
						swalInit.fire('Warning!', 'This Purchase Order has no down payment yet!', 'warning');
						$('#po-payment-code').html('');
					}
				}else if(elemen == 'pr_id'){
					$('#progress_production').val(response.progress_left);
				}
			}
         },
         error: function() {
            if(elemen == 'pop_id'){
				loadingClose('#step-10');
			}else if(elemen == 'pr_id'){
				loadingClose('#step-12');
			}else if(elemen == 'por_id'){
				loadingClose('#step-13');
			}else{
				loadingClose('#step-11');
			}
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
   
   function addProduct() {
      var id = $('#product_id');

      if(id.val()) {
		  
		  
         $.ajax({
            url: '{{ url("admin/delivery_order/project/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: id.val()
            },
            beforeSend: function() {
               loadingOpen('#step-2');
            },
            success: function(response) {
			    var same = false;
				
				$('input[name^="product_id"]').each(function() {
					if($(this).val() == response.id){
						same = true;
					}
				});
				
				if(same == false){
				   id.val(null).trigger('change');
				   
				   if($('.rowproduct').length == 0){
					  no = 1;
				   }else{
					  no = $('.rowproduct').length + 1;
				   }

				   $('#data_product').append(`
					  <tr class="text-center rowproduct">
						 <input type="hidden" name="product_id[]" value="` + response.id + `">
						 <input type="hidden" name="product_price[]" value="` + response.price + `">
						 <td>` + no + `</td>
						 <td class="align-middle">` + response.product + `</td>
						 <td class="align-middle">
							<input type="text" name="product_area[]" class="form-control" placeholder="Type area" required>
						 </td>
						 <td class="align-middle">
							<textarea name="product_spec[]" class="form-control" placeholder="Type spec" rows="1" required>` + response.surface + `</textarea>
						 </td>
						 <td class="align-middle">
							<input type="number" name="product_qty[]" class="form-control" min="1" placeholder="0" required>
						 </td>  
						 <td class="align-middle">
							<select name="product_unit[]" class="custom-select" required>
							   <option value="1">Pcs</option>   
							   <option value="2">Box</option>   
							   <option value="3">Meter</option>   
							   <option value="4">Meter (Custom)</option>   
							</select>
						 </td>   
						 <td class="align-middle">
							` + response.carton_pcs + ` pcs / carton, ` + response.carton_sqm + `
						 </td>  
						 <td class="align-middle">
							<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						 </td>
					  </tr>
				   `);
				}else{
					swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
				}
				
				loadingClose('#step-2');
            },
            error: function() {
               loadingClose('#step-2');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
      } else {
         swalInit.fire('Ooppsss!', 'Please select a product', 'info');
      }
   }
   
   function addTransfer() {
      var tgl = $('#transfer_date').val(), product_id = $('#product_id2').val(), product_name = $('#product_id2 option:selected').text(), from = $('#warehouse_from').val(), to = $('#warehouse_destination').val(), to_name = $('#warehouse_destination option:selected').text();

      if(from !== '') {
		   $('#data_transfer_product').append(`
			  <tr class="text-center">
				 <input type="hidden" name="transfer_date[]" value="` + tgl + `">
				 <input type="hidden" name="product_id[]" value="` + product_id + `">
				 <input type="hidden" name="from_warehouse[]" value="` + from + `">
				 <input type="hidden" name="to_warehouse[]" value="` + to + `">
				 <td class="align-middle">` + tgl + `</td>
				 <td class="align-middle">` + product_name + `</td>
				 <td class="align-middle">` + from + `</td>
				 <td class="align-middle"><input type="number" name="product_qty[]" class="form-control" min="1" placeholder="0" required></td>
				 <td class="align-middle">` + to_name + `</td>
				 <td class="align-middle">
					<button type="button" id="delete_transfer_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
				 </td>
			  </tr>
		   `);
      } else {
         swalInit.fire('Ooops! This product has no stock.', 'Please contact Ventura to confirm this problem.', 'info');
      }
   }

   function addConsultant() {
      var consultant_date   = $('#consultant_date');
      var consultant_person = $('#consultant_person');
      var consultant_result = $('#consultant_result');

      if(consultant_date.val() && consultant_person.val() && consultant_result.val()) {
         $('#data_consultant').append(`
            <tr class="text-center">
			   <input type="hidden" name="consultant_id[]" value="0">
               <input type="hidden" name="consultant_date[]" value="` + consultant_date.val() + `">
               <input type="hidden" name="consultant_person[]" value="` + consultant_person.val() + `">
               <input type="hidden" name="consultant_result[]" value="` + consultant_result.val() + `">

               <td class="align-middle">` + consultant_date.val() + `</td>   
               <td class="align-middle">` + consultant_person.val() + `</td>   
               <td class="align-middle">` + consultant_result.val() + `</td>   
               <td class="align-middle">
                  <button type="button" id="delete_data_consultant" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
               </td>
            </tr>
         `);

         consultant_date.val(null);
         consultant_person.val(null);
         consultant_result.val(null);
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
   }
   
   function addNegotiation() {
      var negotiation_date   = $('#negotiation_date');
      var negotiation_person = $('#negotiation_person');
      var negotiation_result = $('#negotiation_result');

      if(negotiation_date.val() && negotiation_person.val() && negotiation_result.val()) {
         $('#data_negotiation').append(`
            <tr class="text-center">
			   <input type="hidden" name="negotiation_id[]" value="0">
               <input type="hidden" name="negotiation_date[]" value="` + negotiation_date.val() + `">
               <input type="hidden" name="negotiation_person[]" value="` + negotiation_person.val() + `">
               <input type="hidden" name="negotiation_result[]" value="` + negotiation_result.val() + `">

               <td class="align-middle">` + negotiation_date.val() + `</td>   
               <td class="align-middle">` + negotiation_person.val() + `</td>   
               <td class="align-middle">` + negotiation_result.val() + `</td>   
               <td class="align-middle">
                  <button type="button" id="delete_data_negotiation" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
               </td>
            </tr>
         `);

         negotiation_date.val(null);
         negotiation_person.val(null);
         negotiation_result.val(null);
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
   }

	
   function approveQuotation(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/delivery_order/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"quotation" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-4');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approveSample(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/delivery_order/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sample" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-5');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approveSale(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/delivery_order/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sale" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-7');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approvePurchase(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/delivery_order/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"purchase" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-9');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approveDelivery(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/delivery_order/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"delivery" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-16');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approveSaleReturn(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/delivery_order/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sales_return" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-17');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function updateStatusSample(val,id){
	   $.ajax({
         url: '{{ url("admin/delivery_order/project/update_status_sample") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { val:val,id:id },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-5');
         },
         success: function(response) {
			 if(response.status == '200'){
				 //location.reload();
				 swalInit.fire('Success!', 'Sample status successfully changed.', 'success');
				 loadingClose('#step-5');
			 }
         }
      });
	  
	  return false;
   }
   
   function approveSalesInvoice(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/delivery_order/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sales_invoice" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-18');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function addSample() {
      var sample_product_id = $('#sample_product_id');
      var sample_qty        = $('#sample_qty');
	  var sample_unit       = $('#sample_unit');
      var sample_size       = $('#sample_size');

      if(sample_product_id.val() && sample_qty.val() && sample_size.val()) {
         $.ajax({
            url: '{{ url("admin/delivery_order/project/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: sample_product_id.val()
            },
            beforeSend: function() {
               loadingOpen('#step-5');
            },
            success: function(response) {
               loadingClose('#step-5');
			   
				var same = false;
				
				$('input[name^="sample_product_id"]').each(function() {
					if($(this).val() == response.id){
						same = true;
					}
				});
				
				if(same == false){
					
					$('#data_sample').append(`
					  <tr class="text-center">
						 <input type="hidden" name="sample_product_id[]" value="` + sample_product_id.val() + `">
						 <input type="hidden" name="sample_qty[]" value="` + sample_qty.val() + `">
						 <input type="hidden" name="sample_unit[]" value="` + sample_unit.val() + `">
						 <input type="hidden" name="sample_size[]" value="` + sample_size.val() + `">

						 <td class="align-middle">` + response.product + `</td>  
						 <td class="align-middle">` + sample_qty.val() + `</td>
						 <td class="align-middle">` + $("#sample_unit option:selected").text() + `</td>
						 <td class="align-middle">` + $("#sample_size option:selected").text() + `</td>  
						 <td class="align-middle">
							<button type="button" id="delete_data_sample" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						 </td>
					  </tr>
				   `);

				   sample_product_id.val(null).trigger('change');
				   sample_qty.val(null);
				   sample_unit.find('option:eq(0)').prop('selected', true);
				   sample_size.val(null);
				
				}else{
					swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
				}
			   
               
            },
            error: function() {
               loadingClose('#step-5');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
   }
   
   function countTotalPurchase(element,id){
	   
		var result = parseFloat($(element).val().replace(',','.').replace(/\./g,'')) * parseFloat($('#purchaseproductqty' + id).val());
	   
		var number_string = result.toString(),
		split   		= number_string.split('.'),
		sisa     		= split[0].length % 3,
		rupiah     		= split[0].substr(0, sisa),
		ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		if(split[1]){
			split[1] = toFixed(parseFloat("0." + split[1]),2);
			split[1] = split[1].toString().split('.')[1];
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	   
		$('#purchaseproducttotal'+id).html(rupiah);
		
   }
   
	function countBestPrice(percent,id){
	    var projectPrice = $('#project-price' + id).val().replace(',','.').replace(/\./g,'');
	   
		var result = parseFloat(projectPrice) - (parseFloat((parseFloat(projectPrice) * parseFloat(percent.value)) / 100));
	   
		var number_string = result.toString(),
		split   		= number_string.split('.'),
		sisa     		= split[0].length % 3,
		rupiah     		= split[0].substr(0, sisa),
		ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		if(split[1]){
			split[1] = toFixed(parseFloat("0." + split[1]),2);
			split[1] = split[1].toString().split('.')[1];
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	   
		$('#best-price'+id).val(rupiah);
		
	}
   
	function countDueDate(){
		var tanggal = {{ $project->term_payment }};
		if($('#date_create').val() !== ''){
			var datenow = new Date($('#date_create').val())
			datenow.setDate(datenow.getDate() + parseInt(tanggal));
			var day = day_of_the_month(datenow);
			$('#due_date').val(datenow.getFullYear().toString() + '-' + (datenow.getMonth()+1).toString() + '-' + day.toString());
		}else{
			swalInit.fire('Error!', 'Please determine date create first if you want to auto count due date.', 'error');
			$('#date_create').focus();
		}
	}
	
	function countDueDateFull(){
		var tanggal = {{ $project->term_payment }};
		if($('#date_create_full').val() !== ''){
			var datenow = new Date($('#date_create_full').val())
			datenow.setDate(datenow.getDate() + parseInt(tanggal));
			var day = day_of_the_month(datenow);
			$('#due_date').val(datenow.getFullYear().toString() + '-' + (datenow.getMonth()+1).toString() + '-' + day.toString());
		}else{
			swalInit.fire('Error!', 'Please determine date create first if you want to auto count due date.', 'error');
			$('#date_create_full').focus();
		}
	}
	
	function day_of_the_month(d)
	{ 
	  return (d.getDate() < 10 ? '0' : '') + d.getDate();
	}
   
   function convertToNominal(ini,element){
		var result = parseFloat((parseFloat($('#proforma_total_raw').val()) * parseFloat(ini.value)) / 100);
	   
		var number_string = result.toString(),
		split   		= number_string.split('.'),
		sisa     		= split[0].length % 3,
		rupiah     		= split[0].substr(0, sisa),
		ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		if(split[1]){
			split[1] = toFixed(parseFloat("0." + split[1]),2);
			split[1] = split[1].toString().split('.')[1];
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	   
		$(element).val(rupiah);
   }
   
	function toFixed( num, precision ) {
		return (+(Math.round(+(num + 'e' + precision)) + 'e' + -precision)).toFixed(precision);
	}
	
	function skipForm(step,project) {
		$.ajax({
            url: '{{ url("admin/delivery_order/project/skip_form") }}',
            type: 'POST',
            dataType: 'JSON',
            data: {
               step : step, project : project
            },
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
            beforeSend: function() {
               loadingOpen('#step-' + step);
            },
            success: function(response) {
				if(response.status == '200'){
					location.reload();
				}
				loadingClose('#step-' + step);
            },
            error: function() {
               loadingClose('#step-' + step);
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
		 
		 return false;
	}
	
	function addTrackingShipment(idshipment,codeshipment){
		$('#modal_title_tracking_shipment').html(codeshipment);
		$('#tempshipmentid').val(idshipment);
		
		$.ajax({
            url: '{{ url("admin/delivery_order/project/get_tracking_shipment") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: $('#tempshipmentid').val()
            },
            beforeSend: function() {
               loadingOpen('#data_shipment_tracking');
            },
            success: function(response) {
			   
			   var link = '{{ url("/project/tracking/shipment") }}/' + idshipment + '/' + codeshipment;
			   var whatsapptemplate = 'https://wa.me/?text=' + encodeURIComponent('Hi Mr/Mrs. Here we send you a tracking shipment link for your products. \n' + link);
			   
			   $('#data_shipment_tracking').empty();
			   $('#link-tracking-shipment').prop('href', '{{ url("/project/tracking/shipment") }}/' + idshipment + '/' + codeshipment);
			   $('#whatsapp-tracking-shipment').prop('href', whatsapptemplate);
			   
			   if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_shipment_tracking').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString() + `</td>
								<td>` + val.note + `</td>
								<td><button type="button" onclick="delete_tracking_shipment(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
				}else{
					$('#data_shipment_tracking').append(`
						<tr>
							<td colspan="3">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				loadingClose('#data_shipment_tracking');
            },
            error: function() {
               loadingClose('#step-5');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
		
		$('#modal_tracking_shipment').modal('toggle');
	}
	
	function addTrackingShipmentDetail(element){
		var id = $('#tempshipmentid').val(), note = $('#tracking-shipment-note').val();
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/add_shipment_tracking") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id, note : note
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_shipment_tracking');
			},
			success: function(response) {
				 $('#data_shipment_tracking').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_shipment_tracking').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString() + `</td>
								<td>` + val.note + `</td>
								<td><button type="button" onclick="delete_tracking_shipment(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
				}else{
					$('#data_shipment_tracking').append(`
						<tr>
							<td colspan="3">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#tracking-shipment-note').val('');
				$('#tracking-shipment-note').focus();
				
				loadingClose('#data_shipment_tracking');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function delete_tracking_shipment(element,id){
		$.ajax({
			url: '{{ url("admin/delivery_order/project/delete_shipment_tracking") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_shipment_tracking');
			},
			success: function(response) {
				if(response.status == '200'){
					$(element).closest('tr').remove();
				}
				loadingClose('#data_shipment_tracking');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function addDateReceived(iddelivery,codedelivery,term){
		$('#modal_title_received_date').html(codedelivery);
		$('#tempreceived').val(iddelivery);
		$('#modal_date_received').modal('toggle');
		$('#info_received_due_date').text(term);
		
		$.ajax({
            url: '{{ url("admin/delivery_order/project/get_delivery_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: iddelivery
            },
            beforeSend: function() {
               loadingOpen('#data_qty_received');
            },
            success: function(response) {
				if(response.length > 0) {
					$('#data_qty_received').empty();
					$.each(response, function(i, val) {
						$('#data_qty_received').append(`
							<tr class="text-center">
								<td><input type="hidden" name="delivery_product_id[]" value="` + val.product_id + `">` + val.product_name + `</td>
								<td><input type="number" class="form-control form-control-sm" name="delivery_qty[]" value="` + val.qty + `"></td>
								<td>` + val.unit + `</td>
							</tr>
						`);
					});
					
				}
				
				loadingClose('#data_qty_received');
            },
            error: function() {
               loadingClose('#data_qty_received');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
        });
	}
	
	function updateReceivedProof(iddelivery,codedelivery){
		$('#modal_title_update_received').html(codedelivery);
		$('#tempreceivedproof').val(iddelivery);
		$('#modal_update_received').modal('toggle');
	}
	
	function addReceivedDate(){
		var id = $('#tempreceived').val(), date = $('#received_date').val(), duedate = $('#received_due_date').val();
		var fd = new FormData(), files = $('#received_proof')[0].files;
		var arrProduct = [], arrQty = [];
		fd.append('date',date);
		fd.append('duedate',duedate);
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$("input[name='delivery_product_id[]']").each(function() {
			fd.append('arrProduct[]',$(this).val());
		});
		
		$("input[name='delivery_qty[]']").each(function() {
			fd.append('arrQty[]',$(this).val());
		});
		
		if(date !== ''){
		
			$.ajax({
				url: '{{ url("admin/delivery_order/project/add_received_date") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#modal-body-received');
				},
				success: function(response) {
					if(response.status == '200'){
						loadingClose('#modal-body-received');
						location.reload();
					}else{
						swalInit.fire('Server Error!', response.message, 'error');
					}
				},
				error: function() {
					loadingClose('#modal-body-received');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
			
		}else{
			swalInit.fire('Server Error!', 'Please choose date first!', 'error');
		}
	}
	
	function addReceivedProof(){
		var id = $('#tempreceivedproof').val();
		var fd = new FormData(), files = $('#received_proof_update')[0].files, files2 = $('#receipt_proof')[0].files;
		fd.append('id',id);
		fd.append('due_date_tt',$('#due_date_tt').val());
		/* if(files.length > 0 ){ */
           fd.append('file',files[0]);
		   fd.append('file2',files2[0]);
		   
			$.ajax({
				url: '{{ url("admin/delivery_order/project/add_received_proof") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#modal-body-proof');
				},
				success: function(response) {
					if(response.status == '200'){
						loadingClose('#modal-body-proof');
						location.reload();
					}else{
						swalInit.fire('Server Error!', response.message, 'error');
					}
				},
				error: function() {
					loadingClose('#modal-body-proof');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		/* }else{
			swalInit.fire('Server Error!', 'Please choose file first!', 'error');
		} */
	}
	
	function addTrackingDelivery(iddelivery,codedelivery){
		$('#modal_title_tracking_delivery').html(codedelivery);
		$('#tempdeliveryid').val(iddelivery);
		
		$.ajax({
            url: '{{ url("admin/delivery_order/project/get_tracking_delivery") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: $('#tempdeliveryid').val()
            },
            beforeSend: function() {
               loadingOpen('#data_delivery_tracking');
            },
            success: function(response) {
				var link = '{{ url("/project/tracking/delivery") }}/' + iddelivery + '/' + replaceAll(codedelivery,'/','-');
				var whatsapptemplate = 'https://wa.me/?text=' + encodeURIComponent('Hi Mr/Mrs. Here we send you a tracking delivery link for your products. \n' + link);
			   
				loadingClose('#data_delivery_tracking');
				$('#data_delivery_tracking').empty();
				$('#link-tracking-delivery').prop('href', '{{ url("/project/tracking/delivery") }}/' + iddelivery + '/' + replaceAll(codedelivery,'/','-'));
				$('#whatsapp-tracking-delivery').prop('href', whatsapptemplate);
				
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_delivery_tracking').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString() + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
								<td><button type="button" onclick="delete_tracking_delivery(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
				}else{
					$('#data_delivery_tracking').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				loadingClose('#data_delivery_tracking');
            },
            error: function() {
               loadingClose('#data_delivery_tracking');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
		
		$('#modal_tracking_delivery').modal('toggle');
	}
	
	function addTrackingDeliveryDetail(element){
		var id = $('#tempdeliveryid').val(), note = $('#tracking-delivery-note').val();
		var fd = new FormData(), files = $('#tracking-delivery-file')[0].files;
		fd.append('note',note);
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/add_delivery_tracking") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_delivery_tracking');
			},
			success: function(response) {
				 $('#data_delivery_tracking').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_delivery_tracking').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString() + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
								<td><button type="button" onclick="delete_tracking_delivery(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
				}else{
					$('#data_delivery_tracking').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#tracking-delivery-file').val('');
				$('#tracking-delivery-note').val('');
				
				loadingClose('#data_delivery_tracking');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function delete_tracking_delivery(element,id){
		$.ajax({
			url: '{{ url("admin/delivery_order/project/delete_delivery_tracking") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_delivery_tracking');
			},
			success: function(response) {
				if(response.status == '200'){
					$(element).closest('tr').remove();
				}
				loadingClose('#data_delivery_tracking');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	$('#sample_unit').on('change', function() {
		if($(this).val() == '1'){
			$('#sample_size').empty().append('<option value="1">20x20</option><option value="2">Full Size</option>');
		}else if($(this).val() == '2'){
			$('#sample_size').empty().append('<option value="2">Full Size</option>');
		}else{
			$('#sample_size').empty();
		}
	});
	
	$('#dropshipper').on('change', function() {
		if($(this).val() == '1'){
			$('#data-dropshipper').hide();
		}else{
			$('#data-dropshipper').show();
		}
	});
	
	function replaceAll(str, find, replace) {
	  return str.replace(new RegExp(find, 'g'), replace);
	}
	
	function editSales(idsales){
		$('#temp_so_id').val(idsales);
		$('#modesales').html('Edit <i class="icon-loop3"></i>');
		$('#modesales').removeClass('btn-info');
		$('#modesales').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_sales_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				idprojectsale : idsales
			 },
			 beforeSend: function() {
				loadingOpen('#step-7');
			 },
			 success: function(response) {
				loadingClose('#step-7');
				if(response){
					
					$('#sales_address').val(response.sales_address);
					$('#sales_note').val(response.sales_note);
					$('html, body').animate({
						scrollTop: $('#step-7').offset().top
					}, 'slow');
					$('#sales_id').empty();
					$('#sales_id').append(`
						<option value="` + response.sales_id + `">` + response.sales_name + `</option>
					`);
				}
			 },
			 error: function() {
				loadingClose('#step-7');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function countPayment(percent){
		var nominal = $('#payment_left').text();
		if(nominal !== '0'){
			$('#nominal').val( ((parseFloat(percent) / 100 * parseFloat(nominal.replace(/\./g,'').replace(',','.')))).toString().replace('.',',') );
			$('#nominal').trigger('keyup');
		}else{
			swalInit.fire('Error!', 'Please choose sales order first / your SO is already paid.', 'error');
		}
	}
	
	function countPaymentFull(percent){
		var nominal = $('#payment_left_full').text();
		if(nominal !== '0'){
			$('#nominal_full').val( ((parseFloat(percent) / 100 * parseFloat(nominal.replace(/\./g,'').replace(',','.')))).toString().replace('.',',') );
			$('#nominal_full').trigger('keyup');
		}else{
			swalInit.fire('Error!', 'Please choose sales order first / your SO is already paid.', 'error');
		}
	}
	
	function emailTrackingDelivery()
	{
		var iddelivery = $('#tempdeliveryid').val();
		
		if(iddelivery){
			$.ajax({
				url: '{{ url("admin/delivery_order/project/email_tracking_delivery") }}',
				type: 'POST',
				dataType: 'JSON',
				 data: {
					iddelivery : iddelivery
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				 beforeSend: function() {
					loadingOpen('#modal_tracking_delivery');
				 },
				 success: function(response) {
					loadingClose('#modal_tracking_delivery');
					swalInit.fire('Success!', 'Email has been successfully sent.', 'success');
				 },
				 error: function() {
					loadingClose('#modal_tracking_delivery');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				 }
			});
		}
	}
	
	function resetSales(){
		$('#temp_so_id').val('');
		$('#modesales').html('Add <i class="icon-loop3"></i>');
		$('#modesales').removeClass('btn-warning');
		$('#modesales').addClass('btn-info');
		$('#sales_address').val('');
		$('#sales_note').val('');
		$('#sales_id').empty();
		return false;
	}
	
	function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
	}
	
	function addBill(idproject,code){
		$('#modal_bill').modal('toggle');
		$('#temp_bill').val(idproject);
		$('#title-bill').html(code);
	}
	
	function editBill(id,code){
		$('#modal_bill').modal('toggle');
		$('#temp_bill_edit').val(id);
		$('#title-bill').html(code);
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_bill_edit") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#modal-bill');
			 },
			 success: function(response) {
				
				if(response){
					$('#temp_bill').val(response.project_id);
					$('#branch_bill').val(response.branch);
					$('#date_bill').val(response.date);
					$('#due_date_bill').val(response.due_date);
					$('#nominal_bill').val(response.nominal);
					$('#nominal_service_bill').val(response.nominal_service);
					$('#address_bill').val(response.address);
					$('#note_bill').val(response.note);
				}
				
				loadingClose('#modal-bill');
			 },
			 error: function() {
				loadingClose('#modal-bill');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function addBillToCustomer(){
		var id = $('#temp_bill').val(), date = $('#date_bill').val(), due_date = $('#due_date_bill').val(), nominal = $('#nominal_bill').val(), note = $('#note_bill').val(), branch = $('#branch_bill').val(), address = $('#address_bill').val(), nominal_service = $('#nominal_service_bill').val(), temp_bill_edit = $('#temp_bill_edit').val();
		
		var nominal_so_real = parseFloat($('#nominal_so_real').val());
		var nominal_service_real = parseFloat($('#nominal_service_real').val());

		if(id && date && due_date && nominal && note && address){
			if(parseFloat(nominal.replaceAll(".", "").replaceAll(",",".")) >  nominal_so_real ||  parseFloat(nominal_service.replaceAll(".", "").replaceAll(",",".")) > nominal_service_real){
				var notyConfirm = new Noty({
				theme: 'limitless',
				text: '<h6 class="font-weight-bold mb-3">Are you sure you want to proceed ?</h6><label>Nominal Bill is higher than SO .</label>',
				timeout: false,
				modal: true,
				layout: 'center',
				closeWith: 'button',
				type: 'confirm',
				buttons: [
					Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
					notyConfirm.close();
					}),
					Noty.button('<i class="icon-checkmark2"></i>', 'btn bg-success ml-1', function() {
						$.ajax({
							url: '{{ url("admin/delivery_order/project/add_project_bill") }}',
							type: 'POST',
							dataType: 'JSON',
							data: {
								id : id, date : date, due_date : due_date, nominal : nominal, note : note, branch : branch, address : address, nominal_service : nominal_service, temp_bill_edit : temp_bill_edit
							},
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							beforeSend: function() {
								loadingOpen('#body-bill');
							},
							success: function(response) {
								if(response.status == '200'){
									location.reload();
								}else if(response.status == '500'){
									swalInit.fire('Server Error!', response.message, 'error');
								}
								
								loadingClose('#body-bill');
							},
							error: function() {
								swalInit.fire('Server Error!', 'Please contact developer', 'error');
							}
						});
					})
				]
				}).show();
			}else{
				$.ajax({
					url: '{{ url("admin/delivery_order/project/add_project_bill") }}',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id : id, date : date, due_date : due_date, nominal : nominal, note : note, branch : branch, address : address, nominal_service : nominal_service, temp_bill_edit : temp_bill_edit
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('#body-bill');
					},
					success: function(response) {
						if(response.status == '200'){
							location.reload();
						}else if(response.status == '500'){
							swalInit.fire('Server Error!', response.message, 'error');
						}
						
						loadingClose('#body-bill');
					},
					error: function() {
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				});
			}
		
		}
	}
	
	function editPayment(idpay){
		$('#temp_pay_id').val(idpay);
		$('#modepayment').html('Edit <i class="icon-loop3"></i>');
		$('#modepayment').removeClass('btn-info');
		$('#modepayment').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_payment_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				idprojectpayment : idpay
			 },
			 beforeSend: function() {
				loadingOpen('#step-8');
			 },
			 success: function(response) {
				loadingClose('#step-8');
				if(response){
					
					$('#sopd_id').val(response.sale_id).trigger('change');
					$('#date_create').val(response.date);
					$('#payment_method').val(response.payment_method);
					$('#nominal').val(response.nominal);
					$('#bank_id').val(response.coa_id).trigger('change');
					$('#note').val(response.note);
					$('#giro').val(response.giro).trigger('change');
					$('#giro_code').val(response.giro_code);
					
					if(response.image){
						$('#previewImgDp').html('');
						$('#previewImgDp').append(`
							<img src="` + response.image + `" style="150px">
						`);
					}
					
					setTimeout(function(){
						$('#do_id').val(response.delivery_id).trigger('change');
						$('#bill_id').val(response.bill_id).trigger('change');
					}, 1000);
					
					$('.edit-sp').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-8');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetPayment(){
		location.reload();
		return false;
	}
	
	function editDelivery(iddelivery){
		$('#temp_delivery_id').val(iddelivery);
		$('#modedelivery').html('Edit <i class="icon-loop3"></i>');
		$('#modedelivery').removeClass('btn-info');
		$('#modedelivery').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_delivery_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : iddelivery
			 },
			 beforeSend: function() {
				loadingOpen('#step-16');
			 },
			 success: function(response) {
				loadingClose('#step-16');
				if(response){
					
					$('#sod_id').val(response.data.project_sale_id).trigger('change');
					$('#receiver_name').val(response.data.receiver_name);
					$('#delivery_date').val(response.data.delivery_date);
					$('#email').val(response.data.email);
					$('#phone').val(response.data.phone);
					$('#city_id2').empty();
					$('#city_id2').append(`
						<option value="` + response.data.city_id + `">` + response.data.city_name + `</option>
					`);
					$('#expedition_id').val(response.data.vendor_id).trigger('change');
					$('#address').val(response.data.address);
					$('#dropshipper').val(response.data.is_dropshipper).trigger('change');
					$('#is_sales').val(response.data.is_sales);
					$('#pick_up_name').val(response.data.pick_up_name);
					$('#pick_up_plat').val(response.data.pick_up_plat);
					$('#pick_up_vehicle').val(response.data.pick_up_vehicle);
					$('#service_note').val(response.data.service_note);
					$('#break_tolerance').val(response.data.break_tolerance);
					
					setTimeout(function(){
						$('#warehousedeliver_id').empty();
						$('#warehousedeliver_id').append(`
							<option value="` + response.data.warehouse_id + `">` + response.warehouse_name + `</option>
						`);
						
						$('#dropshipper_id').val(response.data.dropshipper_id);
						
						if(response.product.length > 0) {
							$('#data_delivery_products').empty();
							var no = 1;
							
							$.each(response.product, function(i, val) {
								$('#data_delivery_products').append(`
									<tr class="text-center">
									 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
									 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
									 <input type="hidden" name="product_stock[]" value="` + val.nominalstock + `">
									 <td>` + (i + 1) + `</td>
									 <td class="align-middle">` + val.product_name + `</td>
									 <td class="align-middle">
										-
									 </td>
									 <td class="align-middle">
										-
									 </td>
									 <td class="align-middle">
										-
									 </td>
									 <td class="align-middle">
										<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="` + val.qty +`" required>
									 </td>
									 <td class="align-middle">
										<input type="text" name="product_shading[]" class="form-control" placeholder="Type shading here" value="` + val.shading +`" required>
									 </td>
									 <td class="align-middle">
										<input type="text" name="product_qty_deduction[]" class="form-control" placeholder="Type shading here" value="` + val.qtydeduction +`" required>
									 </td>
									 <td class="align-middle">
										<button type="button" id="delete_delivery_products" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									 </td>
								  </tr>
								`);
								no++;
							});
						}
						
					}, 1000);
					
					$('.edit-do').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-16');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetDelivery(){
		location.reload();
		return false;
	}
	
	function uploadNewReturn(id,oldImage){
		$('#modal_return').modal('toggle');
		$('#id_return').val(id);
		var image_holder = $("#previewImgFileReturn");
		image_holder.empty();
		$("<img />", {
			"src": oldImage,
			"class": "thumb-image",
			"width": "300px"
		}).appendTo(image_holder);
	}
	
	function addReturnProof(){
		var id = $('#id_return').val();
		var fd = new FormData(), files = $('#file_return')[0].files;
		fd.append('id',id);
		fd.append('return_date',$('#return_date').val());
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/add_return_proof") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				loadingOpen('#body-return');
			},
			success: function(response) {
				if(response.status == '200'){
					loadingClose('#body-return');
					location.reload();
				}else{
					swalInit.fire('Server Error!', response.message, 'error');
				}
			},
			error: function() {
				loadingClose('#modal-body-received');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function addPreDeliveryNote(){
		var id = {{ $project->id }}, note = $('#delivery-note').val();
		var fd = new FormData(), files = $('#delivery-file')[0].files;
		fd.append('note',note);
		fd.append('mode','pre_delivery');
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/add_pre_project_note") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#step-1');
			},
			success: function(response) {
				$('#data_delivery_note').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						if(val.is_public == '1'){
							var status = 'checked';
						}else{
							var status = '';
						}
						$('#data_delivery_note').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString('en-GB') + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
								<td>
									<label class="form-check-label">
										<input type="checkbox" class="form-check-input" onclick="isPublic(this,` + val.id + `)" style="margin-top:.125rem;margin-left: -1.5rem;transform: scale(1.25);" ` + status + `>
										YES
									</label>
								</td>
							</tr>
						`);
					});
					
				}else{
					$('#data_delivery_note').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#delivery-file').val('');
				$('#delivery-note').val('');
				
				loadingClose('#step-1');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function isPublic(element,value){
		var nilai = '';
		if ($(element).is(":checked"))
		{
			nilai = '1';
		}else{
			nilai = '0';
		}
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/update_status_note") }}',
			type: 'POST',
			dataType: 'JSON',
			data: { nilai : nilai, id : value },
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				loadingOpen('#step-9');
			},
			success: function(response) {
				if(response.status == '200'){
					loadingClose('#step-9');
					location.reload();
				}else{
					swalInit.fire('Warning!', response.message, 'error');
				}
			},
			error: function() {
				loadingClose('#step-9');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function sendMessage(nomor,code){
		var whatsapptemplate = 'https://wa.me/' + nomor + '?text=' + encodeURIComponent('Halo bu Ismi. Mohon dibuatkan budgeting project untuk project nomor ' + code + '. \nTerima Kasih.');
		
		window.open(whatsapptemplate, '_blank').focus();
	}
	
	function addTaxDocument(id){
		$('#tempSalesProject').val(id);
		$('#data_sales_tax').empty();
		$.ajax({
			 url: '{{ url("admin/delivery_order/project/get_tax_documents") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: id,
				type: 'project_sales'
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				if(response.length > 0){
					$.each(response, function(i, val) {
						var proof = '';
						if(val.extension == 'pdf'){
							proof = `<a href="` + val.image + `" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>`;
						}else{
							proof = `<a data-magnify="gallery" data-src="" data-caption="` + val.name + `" data-group="a" href="` + val.image + `"><img src="` + val.image + `" style="max-height:200px;" class="img-fluid img-thumbnail"></a>`;
						}
						
						$('#data_sales_tax').append(`
							<tr>
								<td class="text-center"><input type="date" class="form-control" id="row_date_tax` + val.id + `" value="` + val.date + `"></td>
								<td class="text-center"><input type="text" class="form-control" id="row_no_tax` + val.id + `" value="` + val.no + `"></td>
								<td class="text-center"><input type="text" class="form-control" id="row_nominal_tax` + val.id + `" value="` + val.nominal + `" onkeyup="formatRupiah(this)"></td>
								<td class="text-center">` + proof + `<br><input type="file" id="proof` + val.id + `" name="proof" class="form-control"
									accept="image/x-png,image/jpg,image/jpeg,application/pdf"></td>
								<td class="text-center"><button type="button" class="btn bg-success btn-sm" data-popup="tooltip" title="Update row" onclick="updateTaxDocument(` + val.id + `);"><i class="icon-floppy-disk"></i></button>  </td>
							</tr>
						`);
						
					});
				}else{
					$('#data_sales_tax').append(`
						<tr>
							<td colspan="5">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tax here.</div>
							</td>
						</tr>
					`);
				}
				
				loadingClose('.modal-content');
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
		
		$('#modal_documents').modal('toggle');
	}
	
	function destroyTaxDocument(val) {
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
				 url: '{{ url("admin/delivery_order/project/delete_tax_document") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : val },
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
						$('#picture' + val).remove();
						notyConfirm.close();
					} else {
						notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'You do not have permission to delete this project!',
					   text: 'Please contact sales manager to ask delete this project.',
					   type: 'error'
					});
				 }
				});
			})
         ]
		}).show();
	}
	
	function deleteDelivery(id){
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
					url: '{{ url("admin/delivery_order/project/delete_delivery") }}',
					type: 'POST',
					dataType: 'JSON',
					 data: {
						id : id
					 },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('#step-16');
					 },
					 success: function(response) {
						loadingClose('#step-16');
						if(response.status == 200) {
							notif('success', 'bg-success', response.message);
							notyConfirm.close();
							location.reload();
						} else if(response.status == 400) {
							notif('error', 'bg-danger', response.message);
							notyConfirm.close();
						} else {
							swalInit.fire({
							   title: 'Server Error',
							   text: 'Please contact developer',
							   type: 'error'
							});
						}
					 },
					 error: function() {
						loadingClose('#step-9');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					 }
				});
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function saveTaxDocument(){
		var id = $('#tempSalesProject').val();
		var fd = new FormData(), files = $('#sales_file_tax')[0].files;
		fd.append('id',id);
		fd.append('date',$('#date_tax').val());
		fd.append('nominal',$('#nominal_tax').val());
		fd.append('no',$('#no_tax').val());
		fd.append('type','project_sales');
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		if(files.length > 0 && $('#date_tax').val() && $('#nominal_tax').val() && $('#no_tax').val()){
			$.ajax({
				url: '{{ url("admin/delivery_order/project/add_tax_document") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('.modal-content');
				},
				success: function(response) {
					if(response.status == '200'){
						loadingClose('.modal-content');
						notif('success', 'bg-success', response.message);
						
						$('#date_tax').val('');
						$('#nominal_tax').val('');
						$('#no_tax').val('');
						$('#sales_file_tax').val('');
						
						$('#data_sales_tax').empty();
						
						if(response.data.length > 0){
							$.each(response.data, function(i, val) {
								var proof = '';
								if(val.extension == 'pdf'){
									proof = `<a href="` + val.image + `" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>`;
								}else{
									proof = `<a data-magnify="gallery" data-src="" data-caption="` + val.name + `" data-group="a" href="` + val.image + `"><img src="` + val.image + `" style="max-height:200px;" class="img-fluid img-thumbnail"></a>`;
								}
								
								$('#data_sales_tax').append(`
									<tr>
										<td class="text-center"><input type="date" class="form-control" id="row_date_tax` + val.id + `" value="` + val.date + `"></td>
										<td class="text-center"><input type="text" class="form-control" id="row_no_tax` + val.id + `" value="` + val.no + `"></td>
										<td class="text-center"><input type="text" class="form-control" id="row_nominal_tax` + val.id + `" value="` + val.nominal + `" onkeyup="formatRupiah(this)"></td>
										<td class="text-center">` + proof + `<br><input type="file" id="proof` + val.id + `" name="proof" class="form-control"
									accept="image/x-png,image/jpg,image/jpeg,application/pdf"></td>
										<td class="text-center"><button type="button" class="btn bg-success btn-sm" data-popup="tooltip" title="Update row" onclick="updateTaxDocument(` + val.id + `);"><i class="icon-floppy-disk"></i></button>  </td>
									</tr>
								`);
								
							});
						}else{
							$('#data_sales_tax').append(`
								<tr>
									<td colspan="5">
										<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tax here.</div>
									</td>
								</tr>
							`);
						}
					}else{
						swalInit.fire('Server Error!', response.message, 'error');
					}
				},
				error: function() {
					loadingClose('#modal-body-received');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}else{
			notif('error', 'bg-danger', 'Check your form.');
		}
	}
	
	function updateTaxDocument(id){
		var fd = new FormData();
		var files = $('#proof'+ id)[0].files;
		fd.append('id',id);
		fd.append('date',$('#row_date_tax' + id).val());
		fd.append('nominal',$('#row_nominal_tax' + id).val());
		fd.append('no',$('#row_no_tax' + id).val());
		
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}

		for(var pair of fd.entries()) {
			console.log(pair[0]+ ', '+ pair[1]); 
		}
		if($('#row_date_tax' + id).val() && $('#row_nominal_tax' + id).val() && $('#row_no_tax' + id).val()){
			$.ajax({
				url: '{{ url("admin/delivery_order/project/update_tax_document") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('.modal-content');
				},
				success: function(response) {
					if(response.status == '200'){
						loadingClose('.modal-content');
						notif('success', 'bg-success', response.message);
					}else{
						swalInit.fire('Server Error!', response.message, 'error');
					}
				},
				error: function() {
					loadingClose('#modal-body-received');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}else{
			notif('error', 'bg-danger', 'Check your form.');
		}
	}

	function editSalesRetur(id){
		$('#temp_pr_id').val(id);
		$('#moderetur').html('Edit <i class="icon-loop3"></i>');
		$('#moderetur').removeClass('btn-info');
		$('#moderetur').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_edit_sales_retur") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#step-17');
			 },
			 success: function(response) {
				
				if(response){
					$('#sor_id').val(response.main.project_sale_id).trigger('change');
					$('#return_memo').val(response.main.project_return_memo_id).trigger('change');
					$('#sale_return_date').val(response.main.date_return).trigger('change');
					$('#return_type').val(response.main.type).trigger('change');
					$("#form-sales-return").find('#address').val(response.main.address);
					$("#form-sales-return").find('#note').val(response.main.note);
					$('#warehousereturn_id').empty();
					$('#warehousereturn_id').append(`
						<option value="` + response.main.warehouse_id + `">` + response.main.warehouse_name + `</option>
					`);
					
					setTimeout(function(){
						if(response.detail.length > 0) {
							$('#data_sales_return').empty();
							var no =1;
							$.each(response.detail, function(i, val) {
								$('#data_sales_return').append(`
								<tr class="text-center">
									<input type="hidden" name="product_id[]" value="` + val.product_id + `">
									<td>` + no + `</td>
									<td class="align-middle">` + val.product_name + `</td>
									<td class="align-middle">
										<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="`+ val.qty +`" required>
									</td>
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
										<option  `+ (val.unit == 1 ? "selected": "") +` value="1">Pcs</option>   
										<option  `+ (val.unit == 2 ? "selected": "") +` value="2">Box</option>   
										<option  `+ (val.unit == 3 ? "selected": "") +` value="3">Meter</option>   
										<option  `+ (val.unit == 4 ? "selected": "") +` value="4">Meter (Custom)</option>   
										</select>
									</td>
									<td class="align-middle">
										<button type="button" id="delete_sales_return_products" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									</td>
								</tr>
								`);
								no++
							});
						}
						
						loadingClose('#step-17');
					}, 1500);
					
					$('.edit-sh-full').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-17');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

		
	function resetReturSales(){
		location.reload();
		return false;
	}
</script>