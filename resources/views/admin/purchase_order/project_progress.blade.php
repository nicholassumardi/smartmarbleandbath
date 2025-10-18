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
					<a href="{{ url('admin/purchase_order/project') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
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
					<a href="{{ url('admin/purchase_order/project') }}" class="breadcrumb-item">Project</a>
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
										<td>Customer</td>
										<td>: {{ $project->customer->name }}</td>
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
								</tbody>
							</table>
						</div>
					</div>
				  </div>
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
									<a onclick="openLink('{{ url('admin/purchase_order/project/print/sales_order/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
								  </td>
								  <td>
									<a onclick="openLink('{{ url('admin/purchase_order/project/print/sales_cost/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-success"><i class="icon-file-pdf"></i></a>
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
               </div>
            </div>
@php
	if($project->budgetingProjectByDate() == true){
@endphp			
			@if($project->progress < 40)
				<div class="alert alert-warning alert-styled-left alert-dismissible">
					<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
					<span class="font-weight-semibold">Warning!</span> This project <b>hasn't reached</b> 43% yet, please contact <b>Sales team</b> to complete their tasks.
				</div>
			@endif
			
			@php
				$approval = false;
				$ps = $project->latestDownPaymentSale();
				if($ps){
					/* if($ps->checked_id && $ps->marketing_id && $ps->approved_id){ */
						$approval = true;
					/* } */
				}else{
					if($project->progress >= 40){
						$approval = true;
					}
				}
				
				if($approval == false){
					echo '<div class="alert bg-warning text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Warning!</span> Please contact Finance (check), Sales Manager (marketing), and Accounting (approve) to Approve the required approvals to continue to the next step.
						</div>';
				}
			@endphp
			
            @if($project->progress >= 40 && $approval == true)
               <div class="card" id="step-9">
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">9</span> PO Supplier</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modepurchase" onclick="resetPurchase()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
						<ul class="nav nav-tabs nav-tabs-highlight nav-justified">
							<li class="nav-item"><a href="#tab-new-po" class="nav-link active" data-toggle="tab">New PO</a></li>
							<li class="nav-item"><a href="#tab-from-stock" class="nav-link" data-toggle="tab">From Stock</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="tab-new-po">
								<form action="{{ url()->full() }}" method="POST" id="form_purchase">
								@csrf
								@if(isset($_GET['step-9']))
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
									<div class="row d-none edit-po">
										<div class="col-md-12">
											 <div class="form-group">
												<label>Reason :<sup class="text-danger">*</sup></label>
												<textarea type="text" name="edit_reason_po" id="edit_reason_po" class="form-control" placeholder="Please describe why you edit this Purchase Order." rows="2"></textarea>
											</div>
										</div>
									</div>
									<div class="row justify-content-center">
										<div class="col-md-4">
											<div class="form-group">
												<label>Mode :<sup class="text-danger">*</sup></label>
												<select name="is_wip" id="is_wip" class="custom-select">
													<option value="0">NORMAL PO</option>
													<option value="1">WIP PO</option>
												</select>
											</div>
										</div>
										<!-- <div class="col-md-8">
											<div class="form-group">
												<label>Purchase Order From Stock : <span class="badge badge-info">Choose this if FROM STOCK</span></label>
												<select name="po_from_stock" id="po_from_stock" class="select2">
													<option value="">----None----</option>
													@foreach($purchaseforstock as $pfs)
														<option value="{{ $pfs->id }}">{{ $pfs->code.' - '.$pfs->supplier->name.' - Rp '.$pfs->getTotal() }}</option>
													@endforeach
												</select>
											 </div>
										</div> -->
										<div class="col-md-8">
											<div class="alert alert-info alert-styled-left alert-dismissible">
												<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
												<span class="font-weight-semibold">Important Info!</span> Choose <b>Mode</b> to WIP PO, if you want to create this PO as WIP Project Purchase.</b>.
											</div>
										</div>
									</div>
									<div class="form-group"><hr></div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Purchase Date :<sup class="text-danger">*</sup></label>
												<input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
											</div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
												<label>Sales Order :<sup class="text-danger">*</sup></label>
												<input type="hidden" id="temp_po_id" name="temp_po_id" value="">
												<select name="so_id" id="so_id" class="select2" onchange="getSalesProduct(this,this.value);getSalesInfo(this,this.value);">
												   <option value="">-- Choose --</option>
												   @foreach($project->projectSale as $ps)
													  <option value="{{ $ps->id }}">{{ $ps->code }}</option>
												   @endforeach
												</select>
											 </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>PPN :<sup class="text-danger">*</sup></label>
												<select name="ppn" id="ppn" class="custom-select">
													<option value="1">Yes</option>
													<option value="0">No</option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
										 <div class="form-group">
											<label>Customer :<sup class="text-danger">*</sup></label>
											<select name="customer_id" id="customer_id"></select>
										 </div>
										</div>
										<div class="col-md-4">
										 <div class="form-group">
											<label>Sales :<sup class="text-danger">*</sup></label>
											<select name="sales_po" id="sales_po"></select>
										 </div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
												<label>Purchase Note :</label>
												<textarea name="sales_note" id="sales_note" class="form-control" placeholder="Enter note here" rows="1" value="{{ old('sales_note') }}"></textarea>
											</div>
										</div>
										
									</div>
									<h5 class="card-title"><b><span class="badge badge-danger">9.a</span> Fee PTA</b></h5>
									<div class="form-group"><hr></div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Fee PTA :<sup class="text-danger">*</sup></label>
												<select name="fee_pta" id="fee_pta" class="custom-select">
													<option value="0">No</option>
													<option value="1">Yes</option>
												</select>
											</div>
										</div>
										<div class="col-md-2 fee-pta-class d-none">
											<div class="form-group">
												<label>Percentage :</label>
												<input type="number" name="percent_fee_pta" id="percent_fee_pta" class="form-control" placeholder="Enter here" value="{{ old('percent_fee_pta') ? old('percent_fee_pta') : 0 }}">
											</div>
										</div>
										<div class="col-md-6 fee-pta-class d-none">
											<div class="alert alert-info alert-styled-left alert-dismissible">
												<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
												<span class="font-weight-semibold">Important Info!</span> Fee PTA will be taken from total purchase <b>BEFORE TAX</b> inputted by purchase team.</b>.
											</div>
										</div>
									</div>
									<h5 class="card-title"><b><span class="badge badge-danger">9.b</span> Order To</b></h5>
									<div class="form-group"><hr></div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Supplier :<sup class="text-danger">*</sup></label>
												<select name="supplier_id" id="supplier_id" onchange="getSupplierCurrency(this.value)"></select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Production Lead Time :<sup class="text-danger">*</sup></label>
												<input type="text" name="production_lead_time" id="production_lead_time" class="form-control" placeholder="Enter here" value="{{ old('production_lead_time') }}">
											</div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
												<label>Estimated Delivery Date :<sup class="text-danger">*</sup></label>
												<input type="date" name="est_delivery_date" id="est_delivery_date" class="form-control" value="{{ old('est_delivery_date') }}">
											 </div>
										 </div>
										<div class="col-md-4">
											 <div class="form-group">
												<label>Estimated Arrival Date :<sup class="text-danger">*</sup></label>
												<input type="date" name="est_arrival_date" id="est_arrival_date" class="form-control" value="{{ old('est_arrival_date') }}">
											 </div>
										 </div>
										 <div class="col-md-4">
											<div class="form-group">
												<label>Factory Name :<sup class="text-danger">*</sup></label>
												<input type="text" name="factory_name" id="factory_name" class="form-control" placeholder="Enter here" value="{{ old('factory_name') }}"></textarea>
											</div>
										</div>
									</div>
									<h5 class="card-title"><b><span class="badge badge-danger">9.c</span> Receiver Information</b></h5>
									<div class="form-group"><hr></div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Delivered To :<sup class="text-danger">*</sup></label>
												<input type="text" name="on_behalf" id="on_behalf" class="form-control" placeholder="Enter here" value="{{ old('on_behalf') }}"></textarea>
											</div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
												<label>Delivery Address :<sup class="text-danger">*</sup></label>
												<textarea type="text" name="delivery_address" id="delivery_address" class="form-control" placeholder="Enter address here" rows="1" value="{{ old('delivery_address') }}"></textarea>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Load Capacity :<sup class="text-danger">*</sup></label>
												<select name="courier_method" id="courier_method" class="custom-select">
													<option value="FCL">FCL</option>
													<option value="LCL">LCL</option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
												<label>Country :<sup class="text-danger">*</sup></label>
												<select name="country_id" id="country_id"></select>
											 </div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
												<label>City :<sup class="text-danger">*</sup></label>
												<select name="city_id" id="city_id"></select>
											 </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>PIC Name :<sup class="text-danger">*</sup></label>
												<input type="text" name="pic_name" id="pic_name" class="form-control" placeholder="Enter here" value="{{ old('pic_name') }}"></textarea>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>PIC Number :<sup class="text-danger">*</sup></label>
												<input type="text" name="pic_number" id="pic_number" class="form-control" placeholder="Enter here" value="{{ old('pic_number') }}"></textarea>
											</div>
										</div>
									</div>
									<h5 class="card-title"><b><span class="badge badge-danger">9.d</span> Payment Information</b></h5>
									<div class="form-group"><hr></div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Payment Method :<sup class="text-danger">*</sup></label>
												<input type="text" name="payment_method" id="payment_method" class="form-control" placeholder="Ex: 0 for 0 days, 7 for 7 days" value="{{ old('payment_method') }}"></textarea>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Payment Due Date :<sup class="text-danger">*</sup></label>
												<input class="form-control" type="date" name="payment_due_date" id="payment_due_date" value="{{ old('payment_due_date') }}">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Price :<sup class="text-danger">*</sup></label>
												<select name="price" id="price" class="form-control">
												   <option value="1">FOB</option>   
												   <option value="2">EXW</option>   
												   <option value="3">Franco</option> 
												   <option value="4">CIF</option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Currency :<sup class="text-danger">*</sup></label>
												<select name="currency" id="currency" class="custom-select">
													<option value="" disabled selected>Select supplier first...</option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Currency Rate :<sup class="text-danger">*</sup> (Leave '1' For IDR)</label>
												<input class="form-control" type="text" name="currency_rate" id="currency_rate" value="1" onkeyup="formatRupiah(this)">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Brand on box :</label>
												<input type="text" name="brand" id="brand" class="form-control" placeholder="Enter here" value="{{ old('brand') }}">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>SNI No. :</label>
												<input type="text" name="sni" id="sni" class="form-control" placeholder="Enter here" value="{{ old('sni') }}">
											</div>
										</div>
										
									</div>
									<h5 class="card-title"><b><span class="badge badge-danger">9.e</span> Pick-up Memo Item To Supplier</b></h5>
									<div class="form-group"><hr></div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Need Pick Up Memo ?<sup class="text-danger">*</sup></label>
												<select name="has_memo_item" id="has_memo_item" class="custom-select">
													<option value="0">No</option>
													<option value="1">Yes</option>
												</select>
											</div>
										</div>
										<div class="col-md-4 memo-item-class d-none">
											<div class="form-group">
												<label>Address Pick-up Memo Item :</label>
												<textarea name="memo_address_item" id="memo_address_item" class="form-control" placeholder="Enter pick-up memo item address here" rows="1" value="{{ old('memo_address_item') }}"></textarea>
											</div>
										</div>
										<div class="col-md-4 memo-item-class d-none">
											<div class="form-group">
												<label>Pick-up Contact Person :</label>
												<textarea name="memo_up" id="memo_up" class="form-control" placeholder="Enter pick-up memo contact person here" rows="1" value="{{ old('memo_up') }}"></textarea>
											</div>
										</div>
									</div>
									<h5 class="card-title"><b><span class="badge badge-danger">9.f</span> Detail Products</b></h5>
									<div class="form-group">
										<hr>
										<div class="alert alert-info alert-styled-left alert-dismissible">
											<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
											<span class="font-weight-semibold">Information!</span> The product purchase's price must be included with PPN if this PO has PPN.</a>
										</div>
									</div>
									<div class="form-group">
									   <div class="table-responsive">
										  <table class="table table-bordered table-striped" width="100%">
											 <thead class="table-secondary">
												<tr class="text-center">
												   <th width="10%">Product</th>
												   <th width="10%">Qty Need</th>
												   <th width="10%">Qty Left</th>
												   <th width="10%">Qty Order</th>
												   <th width="15%">Price @ M<sup>2</sup></th>
												   <th width="15%">Total</th>
												   <th width="20%">Shade</th>
												   <th>Delete</th>
												</tr>
											 </thead>
											 <tbody id="data_purchase">
												
											 </tbody>
											 <tfoot>
												<tr>
													<th colspan="5" class="text-right">Grandtotal</th>
													<th class="text-right" id="totalpo">0</th>
													<th colspan="2"></th>
												</tr>
											 </tfoot>
										  </table>
									   </div>
									</div>
								   <div class="form-group"><hr></div>
								   <h5 class="card-title"><b><span class="badge badge-danger">9.g</span> Detail Products Split</b></h5>
								   <div class="form-group">
										<hr>
										<div class="alert alert-info alert-styled-left alert-dismissible">
											<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
											<span class="font-weight-semibold">Information!</span> Fill this information if this project use split products from another PO that had warehouse receive / PO for stock.</a>
										</div>
									</div>
									<div class="form-group">
									   <div class="table-responsive">
										  <table class="table table-bordered table-striped" width="100%">
											 <thead class="table-secondary">
												<tr class="text-center">
													<th>Product</th>
													<th>Purchase</th>
													<th>Qty</th>
													<th>Delete</th>
												</tr>
											 </thead>
											 <tbody id="data_purchase_split">
												
											 </tbody>
										  </table>
									   </div>
									</div>
								   <div class="form-group"><hr></div>
								   <div class="form-group">
									  <div class="text-right">
										 <button type="submit" name="submit" value="step-9" class="btn bg-purple" onclick="checkShading();">Save & Next <i class="icon-square-right"></i></button>
									  </div>
								   </div>
								   <div class="form-group"><hr></div>
								   <div class="form-group text-center">
										<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed">
											See All Purchase Order
										</a>
									</div>
									<div class="form-group collapse" id="collapse-link-collapsed">
										<h5><b><span class="badge badge-danger">9.h</span> List of All Purchase Order</b></h5>
										<div class="table-responsive">
										  <table class="table table-bordered table-striped">
											 <thead class="table-secondary">
												<tr class="text-center">
												   <th>PO No.</th>
												   <th>SO No.</th>
												   <th>Supplier</th>
												   <th>Checked</th>
												   <th>Approved</th>
												   <th>SO Product</th>
												   <th>SO Service</th>
												   <th>View PO</th>
												   <th>Pick-up Memo</th>
												   <th>Retur</th>
												   <th>Tax</th>
												   <th>Edit</th>
												   <th>Delete</th>
												</tr>
											 </thead>
											 <tbody>
												@foreach($project->projectPurchase as $pp)
												   <tr class="text-center">
													  <td class="align-middle">
														<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed{{ $pp->id }}">
															{{ $pp->code }}
														</a>
													  </td>
													  <td class="align-middle">{{ $pp->projectSale->code }}</td>
													  <td class="align-middle">{{ $pp->supplier->name }}</td>
													  <td class="align-middle">
														@php
															if(isset($pp->checked->name)){
																echo $pp->checked->name;
															}else{
																echo '<button type="button" class="btn btn-primary btn-icon" onclick="approvePurchase(1,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
															}
														@endphp
													  </td>   
													  <td class="align-middle">
														@php
															if(isset($pp->approved->name)){
																echo $pp->approved->name;
															}else{
																echo '<button type="button" class="btn btn-primary btn-icon" onclick="approvePurchase(2,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
															}
														@endphp
													  </td>
													  <td>
														<a onclick="openLink('{{ url('admin/purchase_order/project/print/sales_order/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
													  </td>
													  <td>
														<a onclick="openLink('{{ url('admin/purchase_order/project/print/sales_cost/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-success"><i class="icon-file-pdf"></i></a>
													  </td>
													  <td>
														<a onclick="openLink('{{ url('admin/purchase_order/project/print/purchase_order/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
													  </td>
													  <td>
														@if($pp->has_memo_item == '1')
															<a onclick="openLink('{{ url('admin/purchase_order/project/print/pick_up_memo/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-primary"><i class="icon-file-pdf"></i></a>
														@else
															<span class="badge badge-danger">Empty</span>
														@endif
													  </td>
													  <td>
														<a href="javascript:void(0);" onclick="returPurchase({{ $pp->id }},'{{ $pp->code }}')" class="btn bg-danger"><i class="icon-esc"></i></a>
													  </td>
													  <td>
														<a href="javascript:void(0);" onclick="addTaxDocument({{ $pp->id }})" class="btn bg-primary btn-sm">
															<i class="icon-file-spreadsheet"></i>
															<span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">{{ $pp->countTaxDocument() }}</span>
														</a>
													  </td>
													  <td>
														<a href="#step-9" onclick="editPurchase({{ $pp->id }})" class="btn bg-info"><i class="icon-pencil5"></i></a>
													  </td>
													  <td>
														<a href="javascript:void(0);" onclick="deletePurchase({{ $pp->id }})" class="btn bg-danger"><i class="icon-trash"></i></a>
													  </td>
												   </tr>
												   <tr id="collapse-link-collapsed{{ $pp->id }}" class="collapse">
													<td colspan="12">
														<h4>Purchase Progress Document Informations</h4>
														<p>
															<ol>
																<li>Purchase Bills : {{ count($pp->projectPurchaseBill) }}</li>
																<li>Proof of Proforma : {{ count($pp->projectProforma) }}</li>
																<li>Delivery Document : {{ count($pp->projectShipment) }}</li>
																<li>Warehouse Received : {{ count($pp->projectWarehouse) }}</li>
																<li>Warehouse Bills : {{ $pp->projectWarehouseBill() }}</li>
															</ol>
														</p>
													</td>
												   </tr>
												@endforeach
											 </tbody>
										  </table>
									   </div>
									</div>
									<div class="form-group"><hr></div>
									<div class="form-group text-center">
										<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed1">
											See All Purchase Return
										</a>
									</div>
									<div class="form-group collapse" id="collapse-link-collapsed1">
										<h5><b><span class="badge badge-danger">9.i</span> List of All Return of Goods to Supplier</b></h5>
										<div class="table-responsive">
										  <table class="table table-bordered table-striped">
											 <thead class="table-secondary">
												<tr class="text-center">
												   <th>Return No.</th>
												   <th>PO No.</th>
												   <th>Supplier</th>
												   <th>Approved</th>
												   <th>Document</th>
												   <th>Proof</th>
												   <th>Delete</th>
												</tr>
											 </thead>
											 <tbody>
												@foreach($project->projectPurchaseReturn as $ppr)
												   <tr class="text-center">
													  <td class="align-middle">{{ $ppr->code }}</td>
													  <td class="align-middle">{{ $ppr->projectPurchase->code }}</td>
													  <td class="align-middle">{{ $ppr->projectPurchase->supplier->name }}</td>
													  <td class="align-middle">
														@php
															if(isset($ppr->approve->name)){
																echo $ppr->approve->name;
															}else{
																echo '<button type="button" class="btn btn-primary btn-icon" onclick="approvePurchaseReturn(1,'.$ppr->id.')"><i class="icon-checkmark2"></i></button>';
															}
														@endphp
													  </td>
													  <td>
														<a onclick="openLink('{{ url('admin/purchase_order/project/print/purchase_return/' . base64_encode($ppr->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
													  </td>
													  <td>
														<a href="{{ $ppr->attachment() }}" target="_blank" class="btn bg-info"><i class="icon-file-pdf"></i></a>
													  </td>
													  <td>
														<a href="javascript:void(0);" onclick="deleteReturn({{ $ppr->id }})" class="btn bg-danger"><i class="icon-trash"></i></a>
													  </td>
												   </tr>
												@endforeach
												@if(count($project->projectPurchaseReturn) == 0)
													<tr class="text-center">
														<td colspan="6">
															<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no purchase return here.</div>
														</td>
													</tr>
												@endif
											 </tbody>
										  </table>
									   </div>
									</div>
									
									@if(count($project->projectPurchase) > 0)
									<div class="form-group"><hr></div>
									<h5><b><span class="badge badge-danger">9.j</span> Purchase Activity</b></h5>
									<div class="row">
										<div class="col-md-12">
											 <div class="form-group">
												<label>Purchase Order :</label>
												<select name="purchase-id" id="purchase-id" class="select2">
												   @foreach($project->projectPurchase as $pp)
													  <option value="{{ $pp->id }}">{{ $pp->code.' - '.$pp->supplier->name.' - '.$pp->projectSale->code }}</option>
												   @endforeach
												</select>
											 </div>
										</div>
										<div class="col-md-5">
										  <div class="form-group">
											<input type="text" class="form-control" name="purchase-note" id="purchase-note" placeholder="Type note">
										  </div>
										</div>
										<div class="col-md-5">
										  <div class="form-group">
											 <div class="input-group">
											   <div class="custom-file">
												  <input type="file" id="purchase-file" name="purchase-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
											   </div>
											</div>
										  </div>
										</div>
										<div class="col-md-2">
										  <div class="form-group">
											 <button type="button" onclick="addProjectNote()" class="btn bg-success col-12" id="btnaddpurchasenote"><i class="icon-plus2"></i> Add</button>
										  </div>
										</div>
									</div>
									<div class="form-group"><hr></div>
									<div class="form-group">
										<h5><b><span class="badge badge-danger">9.k</span> List of All Notes</b></h5>
									</div>
									<div class="form-group"><hr></div>
									<div class="form-group">
										<div class="table-responsive">
											<table class="table table-bordered table-striped">
												<thead class="table-secondary">
													<tr class="text-center">
													   <th width="25%">Date</th>
													   <th>PO</th>
													   <th>Note</th>
													   <th>Proof</th>
													   <th width="15%">is Public</th>
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
														<td>
															<label class="form-check-label">
																<input type="checkbox" class="form-check-input" onclick="isPublic(this,{{ $rowdetail->id }})" style="margin-top:.125rem;margin-left: -1.5rem;transform: scale(1.25);" {{ $rowdetail->is_public == '1' ? 'checked' : '' }}>
																YES
															</label>
														</td>
													</tr>
													@php $countnotes++; @endphp
													@endforeach
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
									<div class="form-group">
										<h5><b><span class="badge badge-danger">9.l</span> Purchase Bills</b></h5>
									</div>
									<div class="row" id="form-purchase-bill">
										<div class="col-md-4">
											 <div class="form-group">
												<label>Purchase Order :<sup class="text-danger">*</sup></label>
												<select name="purchase-bill-id" id="purchase-bill-id" class="select2" onchange="getTotalPurchase(this)">
													<option value="">--Choose one--</option>
												   @foreach($project->projectPurchase as $pp)
													  <option value="{{ $pp->id }}" data-total="{{ number_format($pp->totalAfterReturn(),0,',','.') }}">{{ $pp->code.' - '.$pp->supplier->name.' - '.$pp->projectSale->code }}</option>
												   @endforeach
												</select>
											 </div>
										</div>
										<div class="col-md-4">
										  <div class="form-group">
											<label>Document Number :<sup class="text-danger">*</sup></label>
											<input type="text" class="form-control" name="purchase-bill-doc" id="purchase-bill-doc" placeholder="Document number...">
										  </div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Method :<sup class="text-danger">*</sup></label>
												<select name="purchase-bill-method" id="purchase-bill-method" class="form-control">
												   <option value="1">Transfer</option>   
												   <option value="2">Cash</option>   
												   <option value="3">Giro</option> 
												   <option value="4">Check</option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Date Bill :<sup class="text-danger">*</sup></label>
												<input class="form-control" type="date" name="purchase-bill-date" id="purchase-bill-date" max="{{ date('Y-m-d') }}">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Due Date Bill :<sup class="text-danger">*</sup></label>
												<input class="form-control" type="date" name="purchase-bill-due-date" id="purchase-bill-due-date">
											</div>
										</div>
										<div class="col-md-4">
										 <div class="form-group">
											<label>Nominal in Rupiah (IDR) :<sup class="text-danger">*</sup></label>
											<input type="text" name="purchase-bill-nominal" id="purchase-bill-nominal" class="form-control" placeholder="0" onkeyup="formatRupiah(this)">
										 </div>
										</div>
										<div class="col-md-4">
										  <div class="form-group">
											<label>Note :<sup class="text-danger">*</sup></label>
											<input type="text" class="form-control" name="purchase-bill-note" id="purchase-bill-note" placeholder="Document number...">
										  </div>
										</div>
										<div class="col-md-4">
										  <div class="form-group">
											<label>Document Proof :<sup class="text-danger">*</sup></label>
											 <div class="input-group">
											   <div class="custom-file">
												  <input type="file" id="purchase-bill-file" name="purchase-bill-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
											   </div>
											</div>
										  </div>
										</div>
										<div class="col-md-4">
										  <div class="form-group">
											<label>&nbsp;</label>
											 <button type="button" onclick="addPurchaseBill()" class="btn bg-success col-12" id="btnaddpurchasebill"><i class="icon-plus2"></i> Add</button>
										  </div>
										</div>
									</div>
									<div class="form-group"><hr></div>
									<div class="form-group">
										<h5><b><span class="badge badge-danger">9.m</span> List of All Purchase Bills</b></h5>
									</div>
									<div class="form-group">
										<div class="table-responsive">
											<table class="table table-bordered table-striped">
												<thead class="table-secondary">
													<tr class="text-center">
													   <th>PO</th>
													   <th>Document Number</th>
													   <th>Date</th>
													   <th>Due Date</th>
													   <th>Method</th>
													   <th>Nominal</th>
													   <th>Note</th>
													   <th>Proof</th>
													   <th>Delete</th>
													</tr>
												 </thead>
												 <tbody id="data_purchase_note">
												 @php $countbills = 0; @endphp
												 @foreach($project->projectPurchase as $row)
													@foreach($row->projectPurchaseBill as $rowdetail)
													<tr class="text-center">
														<td>{{ $row->code }}</td>
														<td>{{ $rowdetail->no_document }}</td>
														<td>{{ $rowdetail->date }}</td>
														<td>{{ $rowdetail->due_date }}</td>
														<td>{{ $rowdetail->method() }}</td>
														<td>{{ number_format($rowdetail->nominal,0,',','.') }}</td>
														<td>{{ $rowdetail->note }}</td>
														<td>{!! $rowdetail->image ? '<a href="' . $rowdetail->attachment() . '" target="_blank" class="btn btn-info"><i class="icon-search4"></i></a>' : '<span class="badge badge-secondary">None</span>' !!}</td>
														<td>
															<a href="javascript:void(0);" onclick="deletePurchaseBill({{ $rowdetail->id }})" class="btn bg-danger"><i class="icon-trash"></i></a>
														</td>
													</tr>
													@php $countbills++; @endphp
													@endforeach
												@endforeach
													@if($countbills == 0)
													<tr>
														<td colspan="9">
															<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no bills here.</div>
														</td>
													</tr>
													@endif
												 </tbody>
											</table>
										</div>
									</div>
									<div class="form-group"><hr></div>
									@endif
								</form>
							</div>

							<div class="tab-pane fade" id="tab-from-stock">
								<form id="form_data_form_stock">
									<div class="alert alert-danger" id="validation_alert_from_stock" style="display:none;">
										<ul id="validation_content_from_stock"></ul>
									</div>
									<div class="row edit-from-stock">
										<div class="col-md-4">
											 <div class="form-group">
												<label>Sales Order :<sup class="text-danger">*</sup></label>
												<select name="sofr_id" id="sofr_id" class="select2" onchange="getSalesProductForStock(this,this.value);">
												   <option value="">-- Choose --</option>
												   @foreach($project->projectSale as $ps)
													  <option value="{{ $ps->id }}">{{ $ps->code }}</option>
												   @endforeach
												</select>
											 </div>
										</div>
										<div class="col-md-4">
											 <div class="form-group">
												<label>Note :<sup class="text-danger">*</sup></label>
												<textarea name="from_stock_memo" id="from_stock_memo" class="form-control" placeholder="Enter note" rows="1">-</textarea>
											 </div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
											   <div class="table-responsive">
												  <table class="table table-bordered table-striped" width="100%">
													 <thead class="table-secondary">
														<tr class="text-center">
														   <th>No</th>	
														   <th>Product</th>
														   <th>Qty From Stock</th>
														   <th>Stock Available</th>
														   <th>Unit</th>
														   <th>Delete</th>
														</tr>
													 </thead>
													 <tbody id="data_purchase_from_stock">
														<tr>
															<td colspan="6">
																<div class="alert alert-info alert-styled-left alert-dismissible">
																	<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
																	<span class="font-weight-semibold">Important Info!</span> Choose project sale to show product list.
																</div>
															</td>
														</tr>
													 </tbody>
												  </table>
											   </div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="text-right">
											<a name="btn-add-from-stock" class="btn bg-primary" onclick="saveFromStock()" href="javascript:void(0);">Save <i class="icon-square-right"></i></a>
										</div>
									</div>
								</form>
								<div class="form-group"><hr></div>
								<div class="form-group text-center">
									<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed5">
										See All From Stock
									</a>
								</div>
								<div class="form-group collapse" id="collapse-link-collapsed5">
									<h5><b><span class="badge badge-danger">9.n</span> List of All Sales From Stock</b></h5>
									<div class="table-responsive">
									  <table class="table table-bordered table-striped">
										 <thead class="table-secondary">
											<tr class="text-center">
											   <th>Sales No.</th>
											   <th>Note</th>
											   <th>Approved</th>
											   <th>Product(s)</th>
											   <th>Action</th>
											</tr>
										 </thead>
										 <tbody>
											@foreach($project->projectFromStock as $pfs)
											   <tr>
												  <td class="align-middle text-center">{{ $pfs->projectSale->code }}</td>
												  <td class="align-middle text-center">{{ $pfs->note }}</td>
												  <td class="align-middle text-center">
													@php
														if(isset($pfs->approved->name)){
															echo $pfs->approved->name;
														}else{
															echo 'Waiting';
														}
													@endphp
												  </td>
												  <td>
													<ol type="a">
													@foreach($pfs->projectFromStockProduct as $product)
														<li>{{ $product->product->name().' Qty. '.$product->qty.' '.$product->unitConvert() }}</li>
													@endforeach
													</ol>
												  </td>
												  <td align="center">
													<a href="javascript:void(0);" onclick="deleteFromStock({{ $pfs->id }})" class="btn bg-danger"><i class="icon-trash"></i></a>
												   </td>
											   </tr>
											@endforeach
											@if(count($project->projectFromStock) == 0)
												<tr class="text-center">
													<td colspan="5">
														<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no purchase return here.</div>
													</td>
												</tr>
											@endif
										 </tbody>
									  </table>
								   </div>
								</div>
							</div>
						</div>
                     </div>
               </div>
            @endif
			
			@php
				$approval = false;
				$ps = $project->latestPurchase();
				if($ps){
					if(isset($ps->approved->name)){
						$approval = true;
					}
				}
				
				if($approval == false){
					echo '<div class="alert bg-warning text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Warning!</span> Please contact Sales Manager (check) and Accounting (approve) to Approve the required approvals to continue to the next step.
						</div>';
				}
			@endphp
			
            @if($project->progress >= 43 && $approval == true)
               <div class="card" id="step-10">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data" id="form-proforma">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">10</span> Proof of Proforma Invoice</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modeproforma" onclick="resetProforma()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Attention!</span> Please select purchase order first before input the proforma invoice.
						</div>
						@if(isset($_GET['step-10']))
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
							<div class="col-md-4">
								 <div class="form-group">
									<label>Purchase Order :<sup class="text-danger">*</sup></label>
									<input type="hidden" id="temp_proforma" name="temp_proforma" value="">
									<select name="pop_id" id="pop_id" class="select2" onchange="getPurchaseInfo(this,this.value);">
									   <option value="">-- Choose --</option>
									   @foreach($project->projectPurchase as $pp)
										  <option value="{{ $pp->id }}">{{ $pp->code.' - '.$pp->supplier->name.' - '.$pp->projectSale->code }}</option>
									   @endforeach
									</select>
								 </div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>Date :<sup class="text-danger">*</sup></label>
									<input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}">
								 </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Supplier :</label>
									<input type="text" name="supplier_name" id="supplier_name" class="form-control" placeholder="Enter here" value="{{ old('supplier_name') }}">
							    </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Origin of Goods :</label>
									<input type="text" name="supplier_warehouse" id="supplier_warehouse" class="form-control" placeholder="Enter here" value="{{ old('supplier_warehouse') }}">
							    </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Note :</label>
									<input type="text" name="note" id="note" class="form-control" placeholder="Enter here" value="{{ old('note') }}">
							    </div>
							</div>
							<div class="col-md-4">
                                 <div class="form-group">
									<label>Proof of Proforma :</label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                 </div>
                            </div>
					   </div>
					   <div class="form-group"><hr></div>
					   <div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed2">
								See All Proof of Proforma
							</a>
						</div>
					   <div class="form-group collapse" id="collapse-link-collapsed2">
							<h5><b><span class="badge badge-danger">10.a</span> List of All Proof of Proforma</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>Date</th>
									   <th>Supplier</th>
									   <th>Warehouse</th>
									   <th>Note</th>
									   <th>Proof</th>
									   <th>Edit</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectProforma as $pp)
									   <tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pp->projectPurchase->id }}')">{{ $pp->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $pp->date }}</td>
										  <td class="align-middle">{{ $pp->supplier_name }}</td>
										  <td class="align-middle">{{ $pp->supplier_warehouse }}</td>
										  <td class="align-middle">{{ $pp->note }}</td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td>
											<a href="#step-10" onclick="editProforma({{ $pp->id }})" class="btn bg-warning"><i class="icon-pencil5"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
					   <div class="form-group">
						  <div class="text-right">
							 <button type="submit" name="submit" value="step-10" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
					   </div>
                     </div>
                  </form>
               </div>
            @endif
			
			@if($project->progress >= 45 && $approval == true)
               <div class="card" id="step-11">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">11</span> Down Payment Purchase</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modepayment" onclick="resetPayment()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Attention!</span> Please select purchase order first before input down payment purchase.
						</div>
					   @if(isset($_GET['step-11']))
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
					   <div class="row" id="form-po">
						  <div class="col-md-12 text-center">
							<button type="button" class="btn btn-primary mb-2">Total : <i class="icon-cash4 mr-2"></i> <b id="proforma_total">0</b></button>
							&nbsp;
							<button type="button" class="btn btn-success mb-2">Paid : <i class="icon-cash4 mr-2"></i> <b id="proforma_paid">0</b></button>
							<!-- <button type="button" class="btn btn-warning mb-2">Left : <i class="icon-cash4 mr-2"></i> 0</button>-->
						  </div>
						  <div class="col-md-12 d-none edit-sp">
								<div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason_payment" id="edit_reason_payment" class="form-control" placeholder="Please describe why you edit this Sales Order Payment." rows="2"></textarea>
							    </div>
							</div>
						  <div class="col-md-3">
								<div class="form-group">
									<label>Purchase Order :<sup class="text-danger">*</sup></label>
									<input type="hidden" id="temp_pay_id" name="temp_pay_id" value="">
									<select name="po_id" id="po_id" class="select2" onchange="getPurchaseInfo(this,this.value);">
									   <option value="">-- Choose --</option>
									   @foreach($project->projectPurchase as $pp)
										  <option value="{{ $pp->id }}">{{ $pp->code }}</option>
									   @endforeach
									</select>
								</div>
							</div>
						  <div class="col-md-3">
							 <div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="hidden" value="0" id="proforma_total_raw">
								<input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}">
							 </div>
						  </div>
						  <div class="col-md-3">
							 <div class="form-group">
								<label>Bank :<sup class="text-danger">*</sup></label>
								<select name="bank" id="bank" class="custom-select">
								   <option value="">-- Choose --</option>
								   @foreach($bank as $b)
									  <optgroup label="{{ $b->name }}">
										  @foreach($b->child() as $bc)
											<option value="{{ $bc->id }}">{{ $bc->name }}</option>
										  @endforeach
									  </optgroup>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-3">
							 <div class="form-group">
								<label>Type :<sup class="text-danger">*</sup></label>
								<select name="status" id="status" class="custom-select">
								   <option value="1">Down Payment</option>
								   <option value="2" disabled>Full Payment</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-3">
							 <div class="form-group">
								<label>Method (Giro/Check) :<sup class="text-danger">*</sup></label>
								<select name="giro" id="giro" class="custom-select">
									<option value="0" {{ old('giro') == 0 ? 'selected' : '' }}>None</option>
									<option value="1" {{ old('giro') == 1 ? 'selected' : '' }}>Giro</option>
									<option value="2" {{ old('giro') == 2 ? 'selected' : '' }}>Check</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-3">
							 <div class="form-group">
								<label>Giro Number :</label>
								<input type="text" name="giro_code" id="giro_code" class="form-control" value="-">
							 </div>
						  </div>
						  <div class="col-md-3">
							 <div class="form-group">
								<label>Giro Due Date :</label>
								<input type="date" name="giro_date" id="giro_date" class="form-control">
							 </div>
						  </div>
						  <div class="col-md-3">
                                 <div class="form-group">
									<label>Proof of Payment :<sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                 </div>
                           </div>
						  <div class="col-md-3">
							 <div class="form-group">
								<label>Percentage :</label>
								<input type="text" name="percentage" id="percentage" class="form-control" placeholder="0" value="50" onkeyup="convertToNominal(this,'.nominal-proforma')">
							 </div>
						  </div>
						  <div class="col-md-3">
							 <div class="form-group">
								<label>Nominal :<sup class="text-danger">*</sup></label>
								<input type="text" name="nominal" id="nominal" class="form-control nominal-proforma" placeholder="0" value="0" onkeyup="formatRupiah(this)">
							 </div>
						  </div>
					   </div>
						<div class="form-group"><hr></div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed3">
								See All Down Payment PO
							</a>
						</div>
					   <div class="form-group collapse" id="collapse-link-collapsed3">
							<h5><b><span class="badge badge-danger">11.a</span> List of All Down Payment PO</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>SO No.</th>
									   <th>Date</th>
									   <th>Bank</th>
									   <th>Nominal</th>
									   <th>Checked By</th>
									   <th>Proof</th>
									   <th>Edit</th>
									   <th>Delete</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPayment()->where('status', 1)->get() as $pp)
									   <tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pp->projectPurchase->id }}')">{{ $pp->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $pp->projectPurchase->projectSale->code }}</td>
										  <td class="align-middle">{{ $pp->date }}</td>
										  <td class="align-middle">{{ $pp->coa->name }}</td>
										  <td class="align-middle">{{ $pp->projectPurchase->currency->symbol.' '.number_format($pp->nominal,2,',','.') }}</td>
										  <td class="align-middle">
											@php
												if(isset($pp->checked_by->name)){
													echo $pp->checked_by->name;
												}else{
													echo '<span class="badge badge-danger d-block">Waiting</span>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td>
											<a href="#step-11" onclick="editPurchasePayment({{ $pp->id }})" class="btn bg-info"><i class="icon-pencil5"></i></a>
										  </td>
										  <td>
											<a href="javascript:void(0);" onclick="deletePurchasePayment({{ $pp->id }})" class="btn bg-danger"><i class="icon-trash"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
					   <div class="form-group"><hr></div>
					   <div class="form-group">
						  <div class="text-right">
							 <a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(11,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
								 &nbsp;
							 <button type="submit" name="submit" value="step-11" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
					   </div>
                     </div>
                  </form>
               </div>
            @endif
			
			@php
				$approval = false;
				$ps = $project->latestDownPaymentPurchase();
				if($ps){
					if(isset($ps->check->name)){
						$approval = true;
					}
				}else{
					if($project->progress >= 48){
						$approval = true;
					}
				}
				
				if($approval == false){
					echo '<div class="alert bg-warning text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Warning!</span> Please contact Finance (check) to Approve the required approvals to continue to the next step.
						</div>';
				}
			@endphp
			
            @if($project->progress >= 48 && $approval == true)
               <div class="card" id="step-12">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">12</span> Progress Production</b></h3>
                        <div class="form-group"><hr></div>
                           @if(isset($_GET['step-12']))
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
							  <div class="col-md-4">
								 <div class="form-group">
									<label>Purchase Order :<sup class="text-danger">*</sup></label>
									<select name="pr_id" id="pr_id" class="select2" onchange="getPurchaseInfo(this,this.value);">
									   <option value="">-- Choose --</option>
									   @foreach($project->projectPurchase as $pp)
										  <option value="{{ $pp->id }}">{{ $pp->code }}</option>
									   @endforeach
									</select>
								 </div>
							  </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label>Start Date :<sup class="text-danger">*</sup></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label>Finish Date :<sup class="text-danger">*</sup></label>
                                    <input type="date" name="finish_date" id="finish_date" class="form-control" value="{{ old('finish_date') }}">
                                 </div>
                              </div>
							  <div class="col-md-4">
                                 <div class="form-group">
                                    <label>Progress(%) :<sup class="text-danger">*</sup></label>
                                    <input type="number" name="progress_production" id="progress_production" placeholder="0" class="form-control" value="{{ old('progress_production') }}">
                                 </div>
                              </div>
							  <div class="col-md-4">
                                 <div class="form-group">
									<label>Proof of Production :</label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                 </div>
							  </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label>Note :<sup class="text-danger">*</sup></label>
                                    <textarea name="note" id="note" class="form-control" placeholder="Enter note" rows="1">{{ old('note') }}</textarea>
                                 </div>
                              </div>
                           </div>
						   <div class="form-group"><hr></div>
                           <div class="form-group">
                              <div class="text-right">
                                 <button type="submit" name="submit" value="step-12" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
                              </div>
                           </div>
						   <div class="form-group"><hr></div>
							<div class="form-group text-center">
								<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed4">
									See All Purchase Order
								</a>
							</div>
							<div class="form-group collapse" id="collapse-link-collapsed4">
								<h5><b><span class="badge badge-danger">12.a</span> List of All Progress Production PO</b></h5>
							   <div class="table-responsive">
								  <table class="table table-bordered table-striped">
									 <thead class="table-secondary">
										<tr class="text-center">
										   <th>PO No.</th>
										   <th>Start</th>
										   <th>Finish</th>
										   <th>Note</th>
										   <th>Proforma Notes</th>
										   <th>Progress</th>
										   <th>Proof</th>
										</tr>
									 </thead>
									 <tbody>
										@php
										$total = 0;
										$temppo = 0;
										foreach($project->projectProduction()->orderBy('project_purchase_id')->get() as $pp){
											if($temppo == 0 || $temppo == $pp->project_purchase_id){
												$total += $pp->progress;
											}else{
												$total = $pp->progress;
											}
											$temppo = $pp->project_purchase_id;
										@endphp
										   <tr class="text-center">
											  <td class="align-middle">
												<a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pp->projectPurchase->id }}')">{{ $pp->projectPurchase->code }}</a>
											  </div>
										      <td class="align-middle">
												{{ $pp->start_date }}
											  </div>
											  <td class="align-middle">
												{{ $pp->finish_date }}
											  </div>
											  <td class="align-middle">
												{{ $pp->note }}
											  </div>
											  <td class="align-middle">
												@foreach($pp->projectPurchase->projectProforma as $rowproforma)
													{!! $rowproforma->note.'<br>' !!}
												@endforeach
											  </div>
											  <td class="align-middle">
												{{ $total }}%
											  </td>
											  <td class="align-middle">
												 <a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
											  </td>
										   </tr>
										@php
										}
										@endphp
									 </tbody>
								  </table>
							   </div>
							</div>
						</div>
                  </form>
               </div>
            @endif
			
            @if($project->progress >= 50 && $approval == true)
               <div class="card" id="step-13">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">13</span> Pay Full Purchase</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modepaymentfull" onclick="resetPaymentFull()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
                        @if(isset($_GET['step-13']))
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
						<div class="row" id="form-po-full">
						  <div class="col-md-12 text-center">
							<button type="button" class="btn btn-primary mb-2">Total : <i class="icon-cash4 mr-2"></i> <b id="proforma_total_1">0</b></button>
							&nbsp;
							<button type="button" class="btn btn-success mb-2">Paid : <i class="icon-cash4 mr-2"></i> <b id="proforma_paid_1">0</b></button>
						  </div>
						  <div class="col-md-12 d-none edit-sp-full">
								<div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason_payment_full" id="edit_reason_payment_full" class="form-control" placeholder="Please describe why you edit this Sales Order Payment." rows="2"></textarea>
							    </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Purchase Order :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="temp_pay_id_full" name="temp_pay_id_full" value="">
								<select name="por_id" id="por_id" class="select2" onchange="getPurchaseInfo(this,this.value);">
								   <option value="">-- Choose --</option>
								   @foreach($project->projectPurchase as $pp)
									  <option value="{{ $pp->id }}">{{ $pp->code }}</option>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Bill Number :</label>
								<select name="bill_id" id="bill_id" class="select2" onchange="getPurchaseBillInfo(this.value);">
								   <option value="">-- Choose --</option>
								   @foreach($project->projectPurchase as $pp)
										@foreach($pp->projectPurchaseBill as $ppb)
										<option value="{{ $ppb->id }}">{{ $ppb->no_document }}</option>
										@endforeach
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Bank :<sup class="text-danger">*</sup></label>
								<select name="bank" id="bank" class="custom-select">
								   <option value="">-- Choose --</option>
								   @foreach($bank as $b)
									  <optgroup label="{{ $b->name }}">
										  @foreach($b->child() as $bc)
											<option value="{{ $bc->id }}">{{ $bc->name }}</option>
										  @endforeach
									  </optgroup>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Type :<sup class="text-danger">*</sup></label>
								<select name="status" id="status" class="custom-select">
								   <option value="1" disabled>Down Payment</option>
								   <option value="2">Full Payment</option>
								   <option value="3">Other</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Method (Giro/Check) :<sup class="text-danger">*</sup></label>
								<select name="giro" id="giro" class="custom-select">
									<option value="0" {{ old('giro') == 0 ? 'selected' : '' }}>None</option>
									<option value="1" {{ old('giro') == 1 ? 'selected' : '' }}>Giro</option>
									<option value="2" {{ old('giro') == 2 ? 'selected' : '' }}>Check</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Giro Number :</label>
								<input type="text" name="giro_code" id="giro_code" class="form-control" value="-">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Giro Due Date :</label>
								<input type="date" name="giro_date" id="giro_date" class="form-control">
							 </div>
						  </div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Nominal Pay :<sup class="text-danger">*</sup></label>
								<input type="text" name="nominal_payment" id="nominal_payment" class="form-control" placeholder="0" value="{{ old('nominal') }}" onkeyup="formatRupiah(this)">
							 </div>
							</div>
							<div class="col-md-4">
                                <div class="form-group">
									<label>Proof of Payment :<sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                </div>
							</div>
					   </div>
					   <div class="form-group">
							<h5><b><span class="badge badge-danger">13.a</span> Detail Payment Purchases #<span id="po-payment-code"></span></b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>Date</th>
									   <th>Bank</th>
									   <th>Nominal</th>
									   <th>Type</th>
									</tr>
								 </thead>
								 <tbody id="data_payment_purchase">
									<tr>
										<td class="bg-warning" colspan="4" style="text-align:center;">Choose Purchase Order first to see detail payment each Purchases</td>
									</tr>
								 </tbody>
								 <tbody style="text-align:center;">
									<tr class="bg-info">
										<td colspan="2">TOTAL</td>
										<td id="paymentotal"></td>
										<td></td>
									</tr>
								 </tbody>
							  </table>
						   </div>
						</div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed5">
								See All Payment Purchases
							</a>
						</div>
						<div class="form-group collapse" id="collapse-link-collapsed5">
							<h5><b><span class="badge badge-danger">13.b</span> List of All Payment Purchases</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>SO No.</th>
									   <th>Date</th>
									   <th>Bank</th>
									   <th>Nominal</th>
									   <th>Checked By</th>
									   <th>Proof</th>
									   <th>Edit</th>
									   <th>Delete</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPayment()->orderBy('project_purchase_id')->get() as $pp)
									   <tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pp->projectPurchase->id }}')">{{ $pp->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $pp->projectPurchase->projectSale->code }}</td>
										  <td class="align-middle">{{ $pp->date }}</td>
										  <td class="align-middle">{{ $pp->coa->name }}</td>
										  <td class="align-middle">{{ $pp->projectPurchase->currency->symbol.' '.number_format($pp->nominal,2,',','.') }}</td>
										  <td class="align-middle">
											@php
												if(isset($pp->check->name)){
													echo $pp->check->name;
												}else{
													echo '<span class="badge badge-danger d-block">Waiting</span>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td>
											<a href="#step-13" onclick="editPurchasePaymentFull({{ $pp->id }})" class="btn bg-info"><i class="icon-pencil5"></i></a>
										  </td>
										  <td>
											<a href="javascript:void(0);" onclick="deletePurchasePayment({{ $pp->id }})" class="btn bg-danger"><i class="icon-trash"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
					   <div class="form-group"><hr></div>
					   <div class="form-group">
						  <div class="text-right">
						     <a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(13,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
							 &nbsp;
							 <button type="submit" name="submit" value="step-13" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
					   </div>
                     </div>
                  </form>
               </div>
            @endif
			
			@php
				$approval = false;
				$ps = $project->latestFullPaymentPurchase();
				if($ps){
					if(isset($ps->check->name)){
						$approval = true;
					}
				}else{
					if($project->progress >= 55){
						$approval = true;
					}
				}
				
				if($approval == false){
					echo '<div class="alert bg-warning text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Warning!</span> Please contact Finance (check) to Approve the required approvals to continue to the next step.
						</div>';
				}
			@endphp
			
            @if($project->progress >= 55 && $approval == true)
               <div class="card" id="step-14">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data" id="form-shipment">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">14</span> Delivery Document Number</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modeshipment" onclick="resetShipment()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
                           @if(isset($_GET['step-14']))
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
						  <div class="col-md-12 d-none edit-sh-full">
								<div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason_shipment" id="edit_reason_shipment" class="form-control" placeholder="Please describe why you edit this shipment." rows="2"></textarea>
							    </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Purchase Order :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="temp_shipment_id" name="temp_shipment_id" value="">
								<select name="pos_id" id="pos_id" class="select2" onchange="getPurchaseProduct(this,this.value);">
								   <option value="">-- Choose --</option>
								   @foreach($project->projectPurchase as $pp)
									  <option value="{{ $pp->id }}">{{ $pp->code }}</option>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Shipment Document Code :<sup class="text-danger">*</sup></label>
								<input type="text" name="shipment_code" id="shipment_code" class="form-control" placeholder="Enter shipment code" value="{{ old('shipment_code') }}">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Loading Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="loading_date" id="loading_date" class="form-control" value="{{ old('loading_date') }}">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Departure Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="departure_date" id="departure_date" class="form-control" value="{{ old('departure_date') }}">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>From Port :<sup class="text-danger">*</sup></label>
								<input type="text" name="from_port" id="from_port" class="form-control" placeholder="Enter from port" value="{{ old('from_port') }}">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>To Port :<sup class="text-danger">*</sup></label>
								<input type="text" name="to_port" id="to_port" class="form-control" placeholder="Enter to port" value="{{ old('to_port') }}">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>ETA :<sup class="text-danger">*</sup></label>
								<input type="date" name="eta" id="eta" class="form-control" value="{{ old('eta') }}">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Delivery Method :<sup class="text-danger">*</sup></label>
								<select name="delivery_method" id="delivery_method" class="custom-select">
									<option value="1" {{ old('delivery_method') == '1' ? 'selected' : '' }}>Air</option>
									<option value="2" {{ old('delivery_method') == '2' ? 'selected' : '' }}>Sea</option>
									<option value="3" {{ old('delivery_method') == '3' ? 'selected' : '' }}>Land</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
                                <div class="form-group">
									<label>Proof of Document :</label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                </div>
							</div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Note :</label>
								<textarea name="note" id="note" class="form-control" placeholder="Enter note" rows="1">{{ old('note') }}</textarea>
							 </div>
						  </div>
						</div>
						<h5 class="card-title"><b><span class="badge badge-danger">14.a</span> Detail Purchase Products</b></h5>
                        <div class="form-group"><hr></div>
						<div class="form-group">
                           <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                 <thead class="table-secondary">
                                    <tr class="text-center">
									   <th width="5%">No</th>
                                       <th width="45%">Product</th>
									   <th width="20%">Qty Order</th>
									   <th width="20%">Qty Sent</th>
									   <th width="10%">Unit</th>
									   <th>Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_shipment_product">
                                    <tr>
										<td class="bg-warning" colspan="6" style="text-align:center;">Choose Purchase Order first to see All products can be sent.</td>
									</tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
						<div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="text-right">
							 <button type="submit" name="submit" value="step-14" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed6">
								See All Shipment Purchases
							</a>
						</div>
						<div class="form-group collapse" id="collapse-link-collapsed6">
							<h5><b><span class="badge badge-danger">14.b</span> List of All Shipment Purchases</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>Shipment Code</th>
									   <th>Loading</th>
									   <th>Departure</th>
									   <th>From Port</th>
									   <th>To Port</th>
									   <th>ETA</th>
									   <th>Note</th>
									   <th>Tracking</th>
									   <th>Proof</th>
									   <th>Edit</th>
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
										  <td class="align-middle"><a class="btn bg-info" href="javascript:void(0);" onclick="addTrackingShipment({{ $ps->id }},'{{ $ps->shipment_code }}')"><i class="icon-truck"></i></a></td>
										  <td class="align-middle"><a href="{{ $ps->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>
										  <td>
											<a href="#step-14" onclick="editShipment({{ $ps->id }})" class="btn bg-info"><i class="icon-pencil5"></i></a>
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
			@if($project->progress >= 60 && $approval == true)
               <div class="card" id="step-15">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data" id="form-warehouse">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">15</span> Warehouse Receive</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modewarehouse" onclick="resetWarehouse()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
						
						@if(isset($_GET['step-15']))
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
							<div class="col-md-12 d-none edit-sw-full">
								<div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason_warehouse" id="edit_reason_warehouse" class="form-control" placeholder="Please describe why you edit this warehouse." rows="2"></textarea>
							    </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Purchase Order :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="temp_warehouse_id" name="temp_warehouse_id" value="">
								<select name="posw_id" id="posw_id" class="select2" onchange="getShipmentInfo(this.value)">
								   <option value="">-- Choose --</option>
								   @foreach($project->projectPurchase as $pp)
									  <option value="{{ $pp->id }}">{{ $pp->code }}</option>
								   @endforeach
								</select>
							 </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Shipment :<sup class="text-danger">*</sup></label>
								<select name="shipment_id" id="shipment_id" class="form-control" onchange="getShipmentProduct(this.value)">
								   <option value="">-- Empty --</option>
								</select>
							 </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Receiver :<sup class="text-danger">*</sup></label>
								<input type="text" name="person" id="person" class="form-control" value="{{ old('person') }}">
							 </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Date & time received :<sup class="text-danger">*</sup></label>
								<input class="form-control" type="datetime-local" name="date_receive" id="date_receive" value="{{ old('date_receive') }}" max="{{ date('Y-m-d H:i:s') }}">
							 </div>
							</div>
							<div class="col-md-4">
							 <div class="form-group">
								<label>Warehouse :<sup class="text-danger">*</sup></label>
								<select name="warehouse_id" id="warehouse_id"></select>
							 </div>
							</div>
							<div class="col-md-4">
                                <div class="form-group">
									<label>Proof of Shipment :<sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                </div>
							</div>
							<div class="col-md-4">
							  <div class="form-group">
								<label>Document For Purchase Request Payment :</label>
								 <div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="purchase-request-file" name="purchase-request-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							  </div>
							</div>
					   </div>
					   <h5 class="card-title"><b><span class="badge badge-danger">15.a</span> Journal Information</b></h5>
                        <div class="form-group"><hr></div>
						<div class="row justify-content-center">
							<div class="col-md-4">
								<div class="form-group">
									<label>Include Cost (EMKL,LS,Freight) :<sup class="text-danger">*</sup></label>
									<select name="include_cost" id="include_cost" class="custom-select">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</div>
							</div>
							<div class="col-md-4 cost-class d-none">
								<div class="form-group">
									<label>Choose COA :</label>
									<select name="wr_coa" id="wr_coa" class="select2">
									   @foreach($coa_id as $row)
										  <option value="{{ $row->id }}">{{ $row->code.' - '.$row->name }}</option>
									   @endforeach
									</select>
							    </div>
							</div>
							<div class="col-md-4 cost-class d-none">
								<div class="form-group">
									<label>Add New :</label>
									<a class="btn btn-info btn-block" onclick="addNewCoa()" href="javascript:void(0);"><i class="icon-plus3"></i></a>
							    </div>
							</div>
							<div class="col-md-6 cost-class d-none">
								<div class="alert alert-info alert-styled-left alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
									<span class="font-weight-semibold">Important Info!</span><b> This information is used to determine journal information.</b>
							    </div>
							</div>
						</div>
					   <h5 class="card-title"><b><span class="badge badge-danger">15.b</span> Detail Shipment Products</b></h5>
                        <div class="form-group"><hr></div>
						<div class="form-group">
                           <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                 <thead class="table-secondary">
                                    <tr class="text-center">
									   <th width="5%">No</th>
                                       <th width="40%">Product</th>
									   <th width="15%">Qty</th>
									   <th width="10%">Unit</th>
									   <th width="15%">Qty Broken</th>
									   <th width="10%">Unit</th>
									   <th>Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_warehouse_product">
                                    <tr>
										<td class="bg-warning" colspan="7" style="text-align:center;">Choose Shipment first to see All products that can be received.</td>
									</tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
						<div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="text-right">
							 <button type="submit" name="submit" value="step-15" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
					   </div>
					   <div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed7">
								See All Warehouse Receive
							</a>
						</div>
						<div class="form-group collapse" id="collapse-link-collapsed7">
							<h5 class="card-title"><b><span class="badge badge-danger">15.c</span> List of All Warehouse Receive</b></h5>
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>Shipment</th>
									   <th>Code</th>
									   <th>Warehouse</th>
									   <th>Person</th>
									   <th>Date & Time Received</th>
									   <th>Proof</th>
									   <th><i class="icon-printer2"></i></th>
									   <th>Edit</th>
									</tr>
								 </thead>
								 <tbody>
									@php
										$tempid = 0;
									@endphp
									@foreach($project->projectWarehouse()->orderBy('project_purchase_id')->get() as $pw)
										<tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pw->projectPurchase->id }}')">{{ $pw->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $pw->projectShipment ? $pw->projectShipment->shipment_code : '' }}</td>
										  <td class="align-middle">{{ $pw->code }}</td>
										  <td class="align-middle">{{ $pw->warehouse->name.' - '.$pw->warehouse->code }}</td>
										  <td class="align-middle">{{ $pw->person }}</td>
										  <td class="align-middle">{{ $pw->date_receive }}</td>
										  <td class="align-middle">
											<a href="{{ $pw->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/purchase_order/project/print/warehouse_receive/' . base64_encode($pw->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td>
											<a href="#step-15" onclick="editWarehouse({{ $pw->id }})" class="btn bg-info"><i class="icon-pencil5"></i></a>
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
   <!-- Bottom right menu 
	<ul class="fab-menu fab-menu-fixed fab-menu-bottom-right" data-fab-toggle="click">
		<li>
			<a class="fab-menu-btn btn bg-teal-400 btn-float rounded-round btn-icon">
				<i class="fab-icon-open icon-paragraph-justify3"></i>
				<i class="fab-icon-close icon-cross2"></i>
			</a>

			<ul class="fab-menu-inner">
				<li>
					<div data-fab-label="Compose email">
						<a href="#" class="btn btn-light rounded-round btn-icon btn-float">
							<i class="icon-pencil"></i>
						</a>
					</div>
				</li>
				<li>
					<div data-fab-label="Conversations">
						<a href="#" class="btn btn-light rounded-round btn-icon btn-float">
							<i class="icon-bubbles3"></i>
						</a>
						<span class="badge bg-primary-400">5</span>
					</div>
				</li>
				<li>
					<div data-fab-label="Chat with Jack">
						<a href="#" class="btn bg-pink-400 rounded-round btn-icon btn-float">
							<img src="{{ url('website/icon.png') }}" class="img-fluid rounded-circle" alt="">
						</a>
						<span class="badge badge-mark border-pink-400"></span>
					</div>
				</li>
			</ul>
		</li>
	</ul>-->
	<!-- /bottom right menu -->
   
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
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	<div id="modal_add_coa" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 400px !important;max-height: 300px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Add New Coa For Purchase</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row">
                        <div class="col-md-12">
							<div class="form-group">
								<label>Coa Prefix :<sup class="text-danger">*</sup></label>
								<input type="text" class="form-control" name="prefix_coa" id="prefix_coa" placeholder="Type prefix here...">
							</div>
						</div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn bg-primary" onclick="addNewCoaPurchase()">Save</button>
				</div>
			</div>
		</div>
	</div>
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
						<a href="javascript:void(0);" id="email-tracking-shipment" class="btn btn-info btn-sm float-right ml-1" onclick="emailTrackingShipment()">Email <i class="icon-envelop3"></i></a>
						<a href="" target="_blank" id="whatsapp-tracking-shipment" class="btn btn-success btn-sm float-right ml-1">Whatsapp <i class="icon-phone-plus"></i></a>
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
						<a href="javascript:void(0);" id="email-tracking-delivery" class="btn btn-info btn-sm float-right ml-1">Email <i class="icon-envelop3"></i></a>
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
	
	<div id="modal_purchase_return" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h4 class="modal-title">Purchase Return Purchase Code. <b id="modal_title_purchase_return"></b></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modal-body-return-purchase">
					<h5 class="card-title">
						<b>Main Information</b>
					</h5>
					<div class="form-group"><hr></div>
					<div class="row">
						<div class="col-md-6">
						  <div class="form-group">
							<input type="hidden" id="tempreturnpo">
							<label>Note :<sup class="text-danger">*</sup></label>
							<input type="text" class="form-control" name="purchase-return-note" id="purchase-return-note" placeholder="Type note">
						  </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="purchase-return-date" id="purchase-return-date" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>From Warehouse :<sup class="text-danger">*</sup></label>
								<select name="purchase-return-warehouse" id="purchase-return-warehouse"></select>
							</div>
						</div>
						<div class="col-md-6">
						  <div class="form-group">
							 <label>Proof :</label>
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="purchase-return-file" name="purchase-return-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
						  </div>
					   </div>
					   <div class="col-md-6">
							 <div class="form-group">
								<label>Warehouse Receive Link :</label>
								<select name="project_warehouse_id" id="project_warehouse_id" class="select2">
									<option value="">--Choose this if from purchase bill--</option>
									@foreach($project->projectWarehouse as $pw)
										@php
											$pr = '';
											
											if($pw->purchaseRequest()->exists()){
												$pr = number_format($pw->purchaseRequest->total_nominal,2,',','.');
											}
										@endphp
										<option value="{{ $pw->id }}">{{ $pw->code.' - '.$pw->projectPurchase->supplier->name.' '.$pr }}</option>
									@endforeach
								</select>
							 </div>
						</div>
					</div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th>Product</th>
                                       <th>Qty</th>
									   <th>Unit</th>
									   <th>Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_purchase_return">
                                    <tr>
										<td colspan="4">
											<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no purchase product data.</div>
										</td>
									</tr>
                                 </tbody>
                              </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn bg-primary" onclick="addReturnPurchase()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_documents" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add Purchase Order Tax</h5>
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
						 <label>Proof :</label>
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
   $(function() {
		$('#form_purchase').submit(function(e){
			
			var adakosong = false;
			$('textarea[name^="product_remark"]').each(function(){
				if($(this).val() == ''){
					adakosong = true;
				}
			});
			
			if(adakosong == true){
				e.preventDefault();
				notif('error', 'bg-danger', 'There is(are) empty shading information. Please fill it.');
			}
		});
		
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
	   
	   $('#fee_pta').on('change', function(){
			if($(this).val() == '1'){
				$('.fee-pta-class').removeClass('d-none');
			}else{
				$('.fee-pta-class').addClass('d-none');
			}
	   });
	   
	   $('#include_cost').on('change', function(){
			if($(this).val() == '0'){
				$('.cost-class').removeClass('d-none');
			}else{
				$('.cost-class').addClass('d-none');
			}
	   });
	   
	   $('#has_memo_item').on('change', function(){
			if($(this).val() == '1'){
				$('.memo-item-class').removeClass('d-none');
			}else{
				$('.memo-item-class').addClass('d-none');
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
      
	  select2ServerSide('#purchase-return-warehouse', '{{ url("admin/select2/warehouse") }}', {
		  dropdownParent: $("#modal_purchase_return")
	  });
	  
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
	  
	  $('#data_purchase_split').on('click', '#delete_data_product_purchase_split', function() {
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
	  
	  $('#data_purchase_from_stock').on('click', '#delete_purchase_from_stock', function() {
         $(this).closest('tr').remove();
      });
	  
		var index = $('#from_stock').prop('selectedIndex'); 
		var select = $('#from_stock');
		select.change(function(e){
			var conf = confirm('Are you sure? You cannot undo this.');
			if(!conf){
				$('#from_stock').prop('selectedIndex',index);
				return false;
			}else{
				index = $('#from_stock').prop('selectedIndex');
				
				if($('#from_stock').val() == '1'){
					/* if($('#po_from_stock').val()){
						$.ajax({
							url: '{{ url("admin/purchase_order/project/skip_po") }}',
							type: 'POST',
							dataType: 'JSON',
							data: { projectid : {{ $project->id }}, purchase_id : $('#po_from_stock').val() },
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							beforeSend: function() {
								loadingOpen('#step-9');
							},
							success: function(response) {
								if(response.status == '200'){
									window.location.href = response.url;
								}
								loadingClose('#step-9');
							}
						});
					}else{
						swalInit.fire('Info!', 'Please choose which purchase is used as replacement.', 'info');
						$('#from_stock').val('0');
					} */
				}
			}
		});
		
		$('#modal_documents').on('hidden.bs.modal', function (e) {
			location.reload();
		});
   });
	
	function addNewCoa(){
	   $('#modal_add_coa').modal('toggle');
	}
	
	function addNewCoaPurchase(){
		if($('#prefix_coa').val() !== ''){
			var prefix = $('#prefix_coa').val();
			
			$.ajax({
				url: '{{ url("admin/purchase_order/project/add_new_coa") }}',
				type: 'POST',
				dataType: 'JSON',
				data: { prefix: prefix },
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#modal_add_coa');
				},
				success: function(response) {
					if(response.status == '200'){
						
						if(response.data.length > 0){
							$('#wr_coa').empty();
							$.each(response.data, function(i, val) {
								$('#wr_coa').append(`
									<option value="` + val.id + `">` + val.code + ` - ` + val.name + `</option>
								`);
							});
						}
						
						notif('success', 'bg-success', 'Succesfully add new coa.');
						
						$('#modal_add_coa').modal('toggle');
					}
					
					loadingClose('#modal_add_coa');
				}
			});
		}else{
			
		}
	}
   
	function getShading(val){
		if(val !== ''){
			$.ajax({
				url: '{{ url("admin/purchase_order/project/get_shading_product") }}',
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
				url: '{{ url("admin/purchase_order/project/get_shading_product") }}',
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
			url: '{{ url("admin/purchase_order/project/get_sales_info") }}',
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
					$('#sales_note').val(response.sales_note);
					$('#delivery_address').val(response.sales_address);
					$('#pic_name').val(response.sales_manager);
					$('#pic_number').val(response.customer_phone);
				}
			}else if(elemen == 'sop_id'){
				loadingClose('#step-18');
				
				if(response) {
					$('#payment_total').text(response.payment_total);
					$('#payment_paid').text(response.payment_paid);
					$('#payment_left').text(response.payment_left);
				}
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
				
				if(response) {
					$('#receiver_name').val(response.customer_name);
					$('#email').val(response.customer_email);
					$('#phone').val(response.customer_phone);
					$('#city_id2').empty();
					$('#city_id2').append(`
						<option value="` + response.city_id + `">` + response.city_name + `</option>
					`);
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
			url: '{{ url("admin/purchase_order/project/get_supplier_currency") }}',
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
							<option value="` + val.id + `">` + val.code + `</option>
						`);
					});
					
					$('#memo_up').val(response[0].up);
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
		 url: '{{ url("admin/purchase_order/project/get_sales_product") }}',
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
					$('#data_purchase_split').empty();
					
					$.each(response, function(i, val) {
						$('#data_purchase').append(`
							<tr class="text-center rowproductsale purchaseproductdata` + val.product_id + `" data-m2="` + val.unitother + `">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + ` ` + val.unit + `
							 </td>
							 <td class="align-middle">
								` + val.qty_left +`<br><span class="badge badge-warning">Qty from stock ` + val.qtyfromstock + `</span>
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" id="purchaseproductqty` + val.product_id + `" class="form-control" placeholder="0" value="` + val.qty_left +`" required onkeyup="$('#product_price` + val.product_id + `').trigger('keyup');">
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_price[]" id="product_price` + val.product_id + `" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);countTotalPurchase(this,'`+ val.product_id +`');convertPriceBeforeTax(this,'`+ val.product_id +`')" value="` + val.latestpurchaseprice + `">
								<span class="badge badge-info">Sell price : <i id="sellprice` + val.product_id + `">` + val.price + `</i> (before tax)</span>
								<span class="badge badge-warning">Buy price : <i id="convertprice` + val.product_id + `">0</i> (before tax)</span>
								<span class="badge badge-success">Buy/Price (%) : <i id="profitprice` + val.product_id + `">0</i></span>
								<span class="badge bg-purple">Budget price : ` + val.budget_price  + `</i> (before tax)</span>
							 </td>
							 <td class="align-middle">
								<div id="purchaseproducttotal`+ val.product_id +`" class="purchaseproducttotal"></div>
							 </td>
							 <td class="align-middle">
								<textarea class="form-control" rows="1" name="product_remark[]">` + val.shading + `</textarea>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_data_product_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
						
						var listpo = [];
						
						$.each(val.listpurchaseproduct, function(i, value) {
							$('#data_purchase_split').append(`
								<tr class="text-center">
									 <input type="hidden" name="product_id_split[]" value="` + val.product_id + `">
									 <input type="hidden" name="product_unit_split[]" value="` + val.unitraw + `">
									 <input type="hidden" name="product_purchase_order_split[]" value="` + value.id + `">
									 <input type="hidden" name="product_price_split[]" value="` + value.price + `">
									 <td class="align-middle">` + val.product_name + `</td>
									 <td class="align-middle">
										` + value.text + ' - Rp ' + value.price + `
									 </td>
									 <td class="align-middle">
										<input type="number" name="product_qty_split[]" class="form-control" placeholder="0" value="0" required>
									 </td>
									 <td class="align-middle">
										<button type="button" id="delete_data_product_purchase_split" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									 </td>
								</tr>
							`);
						});
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
								   <option value="4">Meter(Custom)</option>   
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
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + ` ` + val.unit + `
							 </td>
							 <td class="align-middle">
								` + val.qty_left_deliver +`
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="` + val.qty_left_deliver +`" required>
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
	
	function getSalesProductForStock(element, idprojectsale) {
		var elemen = element.getAttribute('name');
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_sales_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idprojectsale : idprojectsale
         },
         beforeSend: function() {
			if(elemen == 'sofr_id'){
				loadingOpen('#step-9');
			}
         },
         success: function(response) {
			 
			if(elemen == 'sofr_id'){
				loadingClose('#step-9');
				
				if(response.length > 0) {
					$('#data_purchase_from_stock').empty();
					var no = 1;
					
					$.each(response, function(i, val) {
						$('#data_purchase_from_stock').append(`
							<tr class="text-center">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="` + val.qty_left +`" required>
							 </td>
							 <td class="align-middle">
								` + val.nominalstock + `
							 </td>
							 <td class="align-middle">
								` + val.unit + `
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_purchase_from_stock" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
							</tr>
						`);
						no++;
					});
				}
			}
         },
         error: function() {
			
            if(elemen == 'sofr_id'){
				loadingClose('#step-9');
			}
			
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getPurchaseProduct(elemen,idpo){
		var elemen = elemen.getAttribute('name');
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_product") }}',
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
			url: '{{ url("admin/purchase_order/project/get_purchase_product") }}',
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
			url: '{{ url("admin/purchase_order/project/get_shipment_info") }}',
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
			url: '{{ url("admin/purchase_order/project/get_shipment_product") }}',
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
			url: '{{ url("admin/purchase_order/project/get_purchase_info") }}',
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
            url: '{{ url("admin/purchase_order/project/get_product") }}',
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
							   <option value="4">Meter(Custom)</option>   
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
         url: '{{ url("admin/purchase_order/project/approval") }}',
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
         url: '{{ url("admin/purchase_order/project/approval") }}',
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
         url: '{{ url("admin/purchase_order/project/approval") }}',
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
         url: '{{ url("admin/purchase_order/project/approval") }}',
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
         url: '{{ url("admin/purchase_order/project/approval") }}',
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
         url: '{{ url("admin/purchase_order/project/approval") }}',
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
         url: '{{ url("admin/purchase_order/project/update_status_sample") }}',
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
         url: '{{ url("admin/purchase_order/project/approval") }}',
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
            url: '{{ url("admin/purchase_order/project/get_product") }}',
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
	   
		var result = Math.round(parseFloat($(element).val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#purchaseproductqty' + id).val()) * parseFloat($('.purchaseproductdata' + id).data('m2')));
	   
	   
		$('#purchaseproducttotal'+id).html(formatRupiahIni(result));
		
		countAllTotal();
   }
   
   function countAllTotal(){
	   var total = 0;
	   
	   $('.purchaseproducttotal').each(function() {
		   total += parseFloat($(this).text().replaceAll('.','').replaceAll(',','.'));
	   });
	   
	   $('#totalpo').text(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
   }
   
   function convertPriceBeforeTax(element,id){
		var sellprice = parseFloat($('#sellprice' + id).text().replaceAll('.','').replaceAll(',','.'));
	   
		if($('#ppn').val() == '1'){
			var result = parseFloat($(element).val().replaceAll('.','').replaceAll(',','.')) / 1.11;
		}else{
			var result = parseFloat($(element).val().replaceAll('.','').replaceAll(',','.'));
		}
	   
		$('#convertprice' + id).html(Math.round(result * 100) / 100);
		$('#profitprice' + id).html(parseFloat((result / sellprice) * 100).toFixed(2));
		
		if(parseFloat((result / sellprice) * 100) > 100){
			$('#profitprice' + id).parent().removeClass('badge-success');
			$('#profitprice' + id).parent().removeClass('badge-warning');
			$('#profitprice' + id).parent().addClass('badge-danger');
		}else if(parseFloat((result / sellprice) * 100) > 80){
			$('#profitprice' + id).parent().removeClass('badge-success');
			$('#profitprice' + id).parent().removeClass('badge-danger');
			$('#profitprice' + id).parent().addClass('badge-warning');
		}else{
			$('#profitprice' + id).parent().addClass('badge-success');
			$('#profitprice' + id).parent().removeClass('badge-warning');
			$('#profitprice' + id).parent().removeClass('badge-danger');
		}
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
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to skip?</h6><label>Skipped data can no longer be rolled back.</label>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-redo"></i>', 'btn bg-success ml-1', function() {
               $.ajax({
					url: '{{ url("admin/purchase_order/project/skip_form") }}',
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
            })
         ]
		}).show();
	}
	
	function addTrackingShipment(idshipment,codeshipment){
		$('#modal_title_tracking_shipment').html(codeshipment);
		$('#tempshipmentid').val(idshipment);
		
		$.ajax({
            url: '{{ url("admin/purchase_order/project/get_tracking_shipment") }}',
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
			url: '{{ url("admin/purchase_order/project/add_shipment_tracking") }}',
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
			url: '{{ url("admin/purchase_order/project/delete_shipment_tracking") }}',
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
	
	function addTrackingDelivery(iddelivery,codedelivery){
		$('#modal_title_tracking_delivery').html(codedelivery);
		$('#tempdeliveryid').val(iddelivery);
		
		$.ajax({
            url: '{{ url("admin/purchase_order/project/get_tracking_delivery") }}',
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
			url: '{{ url("admin/purchase_order/project/add_delivery_tracking") }}',
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
	
	function addProjectNote(){
		var id = $('#purchase-id').val(), note = $('#purchase-note').val();
		var fd = new FormData(), files = $('#purchase-file')[0].files;
		fd.append('note',note);
		fd.append('mode','project_purchases');
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/add_project_note") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_purchase_note');
			},
			success: function(response) {
				$('#data_purchase_note').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						if(val.is_public == '1'){
							var status = 'checked';
						}else{
							var status = '';
						}
						$('#data_purchase_note').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString('en-GB') + `</td>
								<td>` + val.code + `</td>
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
					$('#data_purchase_note').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#purchase-file').val('');
				$('#purchase-note').val('');
				
				loadingClose('#data_purchase_note');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function addPurchaseBill(){
		var id = $('#purchase-bill-id').val(), no = $('#purchase-bill-doc').val(), method = $('#purchase-bill-method').val(), date = $('#purchase-bill-date').val(), duedate = $('#purchase-bill-due-date').val(), nominal = $('#purchase-bill-nominal').val(),note = $('#purchase-bill-note').val();
		
		var fd = new FormData(), files = $('#purchase-bill-file')[0].files;
		
		fd.append('no',no);
		fd.append('method',method);
		fd.append('date', date);
		fd.append('duedate',duedate);
		fd.append('nominal', nominal);
		fd.append('note',note);
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		if(id && no && method && date && duedate && nominal && note && files[0]){
			$.ajax({
				url: '{{ url("admin/purchase_order/project/add_purchase_bill") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#form-purchase-bill');
				},
				success: function(response) {
					if(response.status == 500){
						swalInit.fire('Server Error!', response.message, 'error');
					}else{
						location.reload();
					}
				},
				error: function() {
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}else{
			swalInit.fire('Error!', 'Please fill all inputs.', 'error');
		}
	}
	
	function delete_tracking_delivery(element,id){
		$.ajax({
			url: '{{ url("admin/purchase_order/project/delete_delivery_tracking") }}',
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
			url: '{{ url("admin/purchase_order/project/get_sales_info") }}',
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
	
	function deletePurchase(idpo){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason" class="form-control" placeholder="Enter why this project purchase should be deleted?"></div></div>',
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
				if($('#delete_reason').val() !== ''){
					$.ajax({
						url: '{{ url("admin/purchase_order/project/delete_purchase") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							idpo : idpo, reason : $('#delete_reason').val()
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-2');
						 },
						 success: function(response) {
							loadingClose('#step-2');
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
							loadingClose('#step-2');
							swalInit.fire('Server Error!', 'Please contact developer', 'error');
						 }
					});
				}else{
					notif('error', 'bg-danger', 'Reason cannot empty.');
				}
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function deleteReturn(idreturn){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason_return" id="delete_reason_return" class="form-control" placeholder="Enter why this purchase return should be deleted?"></div></div>',
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
				if($('#delete_reason').val() !== ''){
					$.ajax({
						url: '{{ url("admin/purchase_order/project/delete_purchase_return") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							id : idreturn, reason : $('#delete_reason_return').val()
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-9');
						 },
						 success: function(response) {
							loadingClose('#step-9');
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
				}else{
					notif('error', 'bg-danger', 'Reason cannot empty.');
				}
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function deletePurchasePayment(idpay){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason" class="form-control" placeholder="Enter why this purchase payment should be deleted?"></div></div>',
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
				if($('#delete_reason').val() !== ''){
					$.ajax({
						url: '{{ url("admin/purchase_order/project/delete_purchase_payment") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							idpay : idpay, reason : $('#delete_reason').val()
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-7');
						 },
						 success: function(response) {
							loadingClose('#step-7');
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
							swalInit.fire({
							   title: 'You do not have permission to delete this project sale!',
							   text: 'Please contact sales manager to ask delete this project sale.',
							   type: 'error'
							});
						 }
					});
				}else{
					notif('error', 'bg-danger', 'Reason cannot empty.');
				}
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function deletePurchaseBill(id){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12">Do not forget, also delete your purchase bill in Purchase Request Form.</div></div>',
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
				if($('#delete_reason').val() !== ''){
					$.ajax({
						url: '{{ url("admin/purchase_order/project/delete_purchase_bill") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							id : id
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-9');
						 },
						 success: function(response) {
							loadingClose('#step-9');
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
							swalInit.fire({
							   title: 'You do not have permission to delete this project sale!',
							   text: 'Please contact sales manager to ask delete this project sale.',
							   type: 'error'
							});
						 }
					});
				}else{
					notif('error', 'bg-danger', 'Reason cannot empty.');
				}
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function deleteFromStock(id){
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
					url: '{{ url("admin/purchase_order/project/delete_from_stock") }}',
					type: 'POST',
					dataType: 'JSON',
					 data: {
						id : id
					 },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('#step-9');
					 },
					 success: function(response) {
						loadingClose('#step-9');
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
						swalInit.fire({
						   title: 'You do not have permission to delete this project sale!',
						   text: 'Please contact sales manager to ask delete this project sale.',
						   type: 'error'
						});
					 }
				});
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function emailTrackingShipment()
	{
		var idshipment = $('#tempshipmentid').val();
		
		if(idshipment){
			$.ajax({
				url: '{{ url("admin/purchase_order/project/email_tracking_shipment") }}',
				type: 'POST',
				dataType: 'JSON',
				 data: {
					idshipment : idshipment
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				 beforeSend: function() {
					loadingOpen('#modal_tracking_shipment');
				 },
				 success: function(response) {
					loadingClose('#modal_tracking_shipment');
					swalInit.fire('Success!', 'Email has been successfully sent.', 'success');
				 },
				 error: function() {
					loadingClose('#modal_tracking_shipment');
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
	
	function editPurchase(idpurchase){
		$('#temp_po_id').val(idpurchase);
		$('#modepurchase').html('Edit <i class="icon-loop3"></i>');
		$('#modepurchase').removeClass('btn-info');
		$('#modepurchase').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				purchaseid : idpurchase
			 },
			 beforeSend: function() {
				loadingOpen('#step-9');
			 },
			 success: function(response) {
				loadingClose('#step-9');
				if(response){
					$('html, body').animate({
						scrollTop: $('#step-9').offset().top
					}, 'slow');
					$('#from_stock').val(response.purchase.from_stock);
					$('#so_id').val(response.purchase.project_sale_id).trigger('change');
					$('#purchase_date').val(response.purchase.created_at.split('T')[0]);
					$('#ppn').val(response.purchase.ppn);
					$('#sales_note').val(response.purchase.note);
					$('#fee_pta').val(response.purchase.fee_pta).trigger('change');
					$('#percent_fee_pta').val(response.purchase.percent_fee_pta);
					$('#supplier_id').empty();
					$('#supplier_id').append(`
						<option value="` + response.purchase.supplier_id + `">` + response.purchase.supplier_name + `</option>
					`);
					$('#production_lead_time').val(response.purchase.production_lead_time);
					$('#est_delivery_date').val(response.purchase.estimated_delivery);
					$('#est_arrival_date').val(response.purchase.estimated_arrival);
					$('#factory_name').val(response.purchase.factory_name);
					$('#on_behalf').val(response.purchase.on_behalf);
					$('#delivery_address').val(response.purchase.delivery_address);
					$('#courier_method').val(response.purchase.courier_method);
					$('#country_id').empty();
					$('#country_id').append(`
						<option value="` + response.purchase.country_id + `">` + response.purchase.country_name + `</option>
					`);
					$('#city_id').empty();
					$('#city_id').append(`
						<option value="` + response.purchase.city_id + `">` + response.purchase.city_name + `</option>
					`);
					
					setTimeout(function(){
						$('#pic_name').val(response.purchase.pic);
						$('#pic_number').val(response.purchase.pic_no);
					}, 1000);
					
					$('#payment_method').val(response.purchase.payment_method);
					$('#payment_due_date').val(response.purchase.payment_due_date);
					$('#price').val(response.purchase.price);
					$('#currency').empty();
					$('#currency').append(`
						<option value="` + response.purchase.currency_id + `">` + response.purchase.currency_name + `</option>
					`);
					$('#brand').val(response.purchase.brand_on_box);
					$('#currency_rate').val(response.purchase.currency_rate);
					$('#currency_rate').trigger('keyup');
					$('#sni').val(response.purchase.sni);
					$('#is_wip').val(response.purchase.is_wip);
					$('#has_memo_item').val(response.purchase.has_memo_item).trigger('change');
					$('#memo_address_item').val(response.purchase.memo_address_item);
					$('#memo_up').val(response.purchase.memo_up);
					
					setTimeout(function(){
						$('#data_purchase').empty();
						
						$.each(response.purchaseproduct, function(i, val) {
							var totaltemp = 0;
							totaltemp = parseFloat(val.qty_left) * parseFloat(val.price.toString().replaceAll('.','').replaceAll(',','.'));
							
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
									<input type="text" name="product_price[]" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);countTotalPurchase(this,'`+ val.product_id +`');convertPriceBeforeTax(this,'`+ val.product_id +`')" value="` + val.price + `">
									<span class="badge badge-info">Sell price : <i id="sellprice` + val.product_id + `">` + val.sell_price + `</i> (before tax)</span>
									<span class="badge badge-warning">Buy price : <i id="convertprice` + val.product_id + `">0</i> (before tax)</span>
									<span class="badge badge-success">Buy/Price (%) : <i id="profitprice` + val.product_id + `">0</i></span>
									<span class="badge bg-purple">Budget price : ` + val.budget_price  + `</i> (before tax)</span>
								 </td>
								 <td class="align-middle">
									<div id="purchaseproducttotal`+ val.product_id +`" class="purchaseproducttotal">` + formatHelper(totaltemp) + `</div>
								 </td>
								 <td class="align-middle">
									<textarea class="form-control" rows="1" name="product_remark[]">` + val.remark + `</textarea>
								 </td>
								 <td class="align-middle">
									<button type="button" id="delete_data_product_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								 </td>
							  </tr>
							`);
						});
					}, 1500);
					
					countAllTotal();
					
					$('.edit-po').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-9');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetPurchase(){
		location.reload();
		return false;
	}
	
	function editPurchasePayment(idpay){
		$('#temp_pay_id').val(idpay);
		$('#modepayment').html('Edit <i class="icon-loop3"></i>');
		$('#modepayment').removeClass('btn-info');
		$('#modepayment').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_payment_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				idpay : idpay
			 },
			 beforeSend: function() {
				loadingOpen('#step-11');
			 },
			 success: function(response) {
				loadingClose('#step-11');
				if(response){
					
					$('#po_id').empty();
					$('#po_id').append(`
						<option value="` + response.purchase_id + `">` + response.purchase_code + `</option>
					`);
					$('#form-po #date').closest('#date').val(response.date);
					$('#form-po #nominal').val(response.nominal);
					$('#form-po #bank').val(response.bank).trigger('change');
					$('#form-po #status').val(response.status);
					$('#form-po #giro').val(response.giro).trigger('change');
					$('#form-po #giro_code').val(response.giro_code);
					$('#form-po #giro_date').val(response.giro_date);
					
					$('.edit-sp').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-11');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetPayment(){
		location.reload();
		return false;
	}
	
	function editProforma(id){
		$('#temp_proforma').val(id);
		$('#modeproforma').html('Edit <i class="icon-loop3"></i>');
		$('#modeproforma').removeClass('btn-info');
		$('#modeproforma').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_proforma") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#step-10');
			 },
			 success: function(response) {
				loadingClose('#step-10');
				if(response){
					
					$('#pop_id').val(response.project_purchase_id).trigger('change');
					$('#form-proforma #date').closest('#date').val(response.date);
					$('#form-proforma #supplier_name').val(response.supplier_name);
					$('#form-proforma #supplier_warehouse').val(response.supplier_warehouse);
					$('#form-proforma #note').val(response.note);
				}
			 },
			 error: function() {
				loadingClose('#step-10');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetProforma(){
		location.reload();
		return false;
	}
	
	function editShipment(id){
		$('#temp_shipment_id').val(id);
		$('#modeshipment').html('Edit <i class="icon-loop3"></i>');
		$('#modeshipment').removeClass('btn-info');
		$('#modeshipment').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_shipment_edit") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#step-14');
			 },
			 success: function(response) {
				
				if(response){
					
					$('#form-shipment #pos_id').val(response.main.project_purchase_id).trigger('change');
					$('#form-shipment #shipment_code').val(response.main.shipment_code);
					$('#form-shipment #loading_date').val(response.main.loading_date);
					$('#form-shipment #departure_date').val(response.main.departure_date);
					$('#form-shipment #from_port').val(response.main.from_port);
					$('#form-shipment #to_port').val(response.main.to_port);
					$('#form-shipment #eta').val(response.main.eta);
					$('#form-shipment #delivery_method').val(response.main.delivery_method);
					$('#form-shipment #note').val(response.main.note);
					
					setTimeout(function(){
						if(response.detail.length > 0) {
							$('#data_shipment_product').empty();
							
							$.each(response.detail, function(i, val) {
								$('#data_shipment_product').append(`
									<tr class="text-center">
									 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
									 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
									 <td>` + (i + 1) + `</td>
									 <td class="align-middle">` + val.product + `</td>
									 <td class="align-middle">
										` + val.qty + `
									 </td>
									 <td class="align-middle">
										<input type="number" name="product_qty[]" class="form-control" placeholder="" value="` + val.qty +`" required>
									 </td>
									 <td class="align-middle">
										` + val.unit + `
									 </td>
									 <td class="align-middle">
										<button type="button" id="delete_ship_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									 </td>
								  </tr>
								`);
							});
							
							loadingClose('#step-14');
						}
					}, 1500);
					
					$('.edit-sh-full').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-14');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetShipment(){
		location.reload();
		return false;
	}
	
	function editWarehouse(id){
		$('#temp_warehouse_id').val(id);
		$('#modewarehouse').html('Edit <i class="icon-loop3"></i>');
		$('#modewarehouse').removeClass('btn-info');
		$('#modewarehouse').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_warehouse_edit") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#step-15');
			 },
			 success: function(response) {
				
				if(response){
					
					$('#form-warehouse #posw_id').val(response.main.project_purchase_id).trigger('change');
					$('#form-warehouse #person').val(response.main.person);
					$('#form-warehouse #date_receive').val(response.main.date_receive);
					$('#form-warehouse #warehouse_id').empty();
					$('#form-warehouse #warehouse_id').append(`
						<option value="` + response.main.warehouse_id + `">` + response.main.warehouse_name + `</option>
					`);
					
					setTimeout(function(){
						$('#form-warehouse #shipment_id').val(response.main.shipment_id);
						
						if(response.detail.length > 0) {
							$('#data_warehouse_product').empty();
							
							$.each(response.detail, function(i, val) {
								$('#data_warehouse_product').append(`
									<tr class="text-center">
										 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
										 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
										 <td>` + (i+1) + `</td>
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
							});
						}
						
						loadingClose('#step-15');
					}, 1500);
					
					$('.edit-sw-full').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-15');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetWarehouse(){
		location.reload();
		return false;
	}
	
	function editPurchasePaymentFull(idpay){
		$('#temp_pay_id_full').val(idpay);
		$('#modepaymentfull').html('Edit <i class="icon-loop3"></i>');
		$('#modepaymentfull').removeClass('btn-info');
		$('#modepaymentfull').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_payment_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				idpay : idpay
			 },
			 beforeSend: function() {
				loadingOpen('#step-13');
			 },
			 success: function(response) {
				loadingClose('#step-13');
				if(response){
					
					$('#por_id').empty();
					$('#por_id').append(`
						<option value="` + response.purchase_id + `">` + response.purchase_code + `</option>
					`);
					$('#form-po-full #date').closest('#date').val(response.date);
					$('#form-po-full #nominal').val(response.nominal);
					$('#form-po-full #bank').val(response.bank).trigger('change');
					$('#form-po-full #status').val(response.status);
					$('#form-po-full #giro').val(response.giro).trigger('change');
					$('#form-po-full #giro_code').val(response.giro_code);
					$('#form-po-full #giro_date').val(response.giro_date);
					
					$('.edit-sp-full').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-13');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetPaymentFull(){
		location.reload();
		return false;
	}
	
	function returPurchase(idpo,code){
		$('#modal_title_purchase_return').html(code);
		$('#tempreturnpo').val(idpo);
		
		$('#modal_purchase_return').modal('toggle');
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
			//loadingOpen('#modal_purchase_return');
         },
         success: function(response) {
			if(response.length > 0) {
				$('#data_purchase_return').empty();
				
				$.each(response, function(i, val) {
					$('#data_purchase_return').append(`
						<tr class="text-center" data-m2="` + val.m2 + `">
						 <input type="hidden" name="return_product_id[]" value="` + val.product_id + `">
						 <input type="hidden" name="return_product_unit[]" value="` + val.convertunit + `">
						 <td class="align-middle">` + val.product_name + `</td>
						 <td class="align-middle">
							<input type="number" name="return_product_qty[]" class="form-control" placeholder="" value="` + val.qty +`" required>
						 </td>
						 <td class="align-middle">
							` + val.unit + `
						 </td>
						 <td class="align-middle">
							<button type="button" id="delete_purchase_return_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						 </td>
					  </tr>
					`);
				});
				
			}
			
			//loadingClose('#modal_purchase_return');
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function addReturnPurchase(){
		var id = $('#tempreturnpo').val();
		var fd = new FormData(), files = $('#purchase-return-file')[0].files;
		var arrProduct = [], arrQty = [], arrUnit = [];
		
		fd.append('id',id);
		fd.append('date',$('#purchase-return-date').val());
		fd.append('warehouse',$('#purchase-return-warehouse').val());
		fd.append('note',$('#purchase-return-note').val());
		fd.append('project_warehouse_id',$('#project_warehouse_id').val());
		
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$("input[name='return_product_id[]']").each(function() {
			fd.append('arrProduct[]',$(this).val());
		});
		
		$("input[name='return_product_qty[]']").each(function() {
			fd.append('arrQty[]',$(this).val());
		});
		
		$("input[name='return_product_unit[]']").each(function() {
			fd.append('arrUnit[]',$(this).val());
		});
		
		if($('#purchase-return-date').val() !== '' && $('#purchase-return-warehouse').val() !== '' && $('#purchase-return-note').val() !== ''){
		
			$.ajax({
				url: '{{ url("admin/purchase_order/project/add_purchase_return") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#modal-body-return-purchase');
				},
				success: function(response) {
					if(response.status == '200'){
						loadingClose('#modal-body-return-purchase');
						location.reload();
					}else{
						swalInit.fire('Warning!', response.message, 'error');
					}
				},
				error: function() {
					loadingClose('#modal-body-return-purchase');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
			
		}else{
			swalInit.fire('Server Error!', 'Please complete the form!', 'error');
		}
	}
	
	function formatHelper(angka){
		var number_string = angka.toString().replaceAll('.', ''),
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
	
	function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
	}
	
	function getPurchaseBillInfo(bill) {
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_bill_info") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
            bill : bill
			},
			 beforeSend: function() {
				loadingOpen('.content-wrapper');
			 },
			 success: function(response) {
				loadingClose('.content-wrapper');
				
				if(response) {
					$('#nominal_payment').val(response.nominal)
				}
			 },
			 error: function() {
				loadingClose('.content-wrapper');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function getTotalPurchase(element){
		if(element.value !== ''){
			$('#purchase-bill-nominal').val(element.options[element.selectedIndex].getAttribute('data-total'));
		}else{
			$('#purchase-bill-nominal').val('');
		}
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
			url: '{{ url("admin/purchase_order/project/update_status_note") }}',
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
	
	function saveFromStock(){
		
		$.ajax({
			 url: '{{ url("admin/purchase_order/project/save_from_stock") }}',
			 type: 'POST',
			 dataType: 'JSON',
		     data: new FormData($('#form_data_form_stock')[0]),
		     contentType: false,
		     processData: false,
		     cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_from_stock').hide();
				$('#validation_content_from_stock').html('');
				loadingOpen('#step-9');
			 },
			 success: function(response) {
				loadingClose('#step-9');
				if(response.status == 200) {
				   location.reload();
				   notif('success', 'bg-success', response.message);
				}else if(response.status == 300){
					notif('error', 'bg-danger', response.message);
				} else if(response.status == 422) {
				   $('#validation_alert_from_stock').show();
				   notif('warning', 'bg-warning', 'Validation');
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_from_stock').append(`
							<li>` + val + `</li>
						 `);
					  });
				   });
				} else {
				   notif('error', 'bg-danger', response.message);
				}
			 },
			 error: function() {
				loadingClose('#step-9');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
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
			 url: '{{ url("admin/purchase_order/project/get_tax_documents") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: id,
				type: 'project_purchases'
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
								<td class="text-center">` + proof + `</td>
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
	
	function saveTaxDocument(){
		var id = $('#tempSalesProject').val();
		var fd = new FormData(), files = $('#sales_file_tax')[0].files;
		fd.append('id',id);
		fd.append('date',$('#date_tax').val());
		fd.append('nominal',$('#nominal_tax').val());
		fd.append('no',$('#no_tax').val());
		fd.append('type','project_purchases');
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		if($('#date_tax').val() && $('#nominal_tax').val() && $('#no_tax').val()){
			$.ajax({
				url: '{{ url("admin/purchase_order/project/add_tax_document") }}',
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
										<td class="text-center">` + proof + `</td>
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
		fd.append('id',id);
		fd.append('date',$('#row_date_tax' + id).val());
		fd.append('nominal',$('#row_nominal_tax' + id).val());
		fd.append('no',$('#row_no_tax' + id).val());
		
		if($('#row_date_tax' + id).val() && $('#row_nominal_tax' + id).val() && $('#row_no_tax' + id).val()){
			$.ajax({
				url: '{{ url("admin/purchase_order/project/update_tax_document") }}',
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
</script>