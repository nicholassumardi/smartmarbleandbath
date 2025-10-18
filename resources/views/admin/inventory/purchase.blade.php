<style>
	.table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
		background-color: #ff9999; 
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Purchase Inventory</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button class="btn bg-primary btn-labeled btn-labeled-left"  data-toggle="dropdown">
						<b><i class="icon-plus3"></i></b> Add
					</button>
					<ul class="dropdown-menu">
						<li>
							<a class="dropdown-item" onclick="cancel()" data-toggle="modal" data-target="#modal_form">Add PO</a>
						</li>
						<li>
							<a class="dropdown-item"  data-toggle="modal" data-target="#modal_form_quotation">Add Quotation</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Inventory</a>
					<span class="breadcrumb-item active">Purchase</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
			<li class="nav-item"><a href="#purchase_order" class="nav-link active" data-toggle="tab">Purchase order</a></li>
			<li class="nav-item"><a href="#request_quotation" class="nav-link" data-toggle="tab">Request Quotation</a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane fade show active" id="purchase_order">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title">List of All Purchase</h5>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
						   <table id="datatable_serverside" class="table table-bordered table-striped w-100 table-hover">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>#</th>
									<th>No</th>
									<th>PO No.</th>
									<th>SO No.</th>
									<th>Customer</th>
									<th>Supplier</th>
									<th>Checked</th>
									<th>Approved</th>
									<th>Date</th>
									<th>View</th>
									<th>Edit/Del</th>
									<th>Add.Cost</th>
									<th>Proforma</th>
									<th>Payment Bill</th>
									<th>Production</th>
									<th>Delivery</th>
									<th>W. Receive</th>
									<th>Return</th>
									<th>Tax</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane fade" id="request_quotation">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title">List of All Request Quotation</h5>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
						   <table id="datatable_serverside_quotation" class="table table-bordered table-striped w-100 table-hover">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>#</th>
									<th>No</th>
									<th>RQ No.</th>
									<th>SO No.</th>
									<th>Supplier</th>
									<th>Approved</th>
									<th>Date</th>
									<th>View</th>
									<th>Edit/Del</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	</div>
	
	<div class="modal fade" id="modal_cost" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Purchase Costs <span id="cost_id"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_cost">
				<div class="alert alert-danger" id="validation_alert_cost" style="display:none;">
					<ul id="validation_content_cost"></ul>
				</div>
				<hr>
				<h5 class="modal-title">Calculation Helper</h5>
				<hr>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>WIP :</label>
							<input type="hidden" name="tempCost" id="tempCost">
							<select name="cost_wip" id="cost_wip" class="select2">
							   @foreach($coa_id as $row)
								  <option value="{{ $row->id }}">{{ $row->code.' - '.$row->name }}</option>
							   @endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Fee MKJ (%) :<sup class="text-danger">*</sup></label>
							<input type="text" name="cost_fee_mkj" id="cost_fee_mkj" class="form-control" placeholder="0" onkeyup="formatRupiah(this);countAll();" value="2,5">
						</div>
					</div>
				</div>
				<hr>
				<h5 class="modal-title">Detail Costs (From Purchase Request)</h5>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-styled-left alert-dismissible bg-info">
							<span class="font-weight-semibold">Info!</span><b> Choose Add Manual or Generate PR from WIP COA.</b>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Purchase Request :</label>
							<select name="purchase_request_id" id="purchase_request_id" onchange="getPurchaseRequestNominal()"></select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Nominal :</label>
							<input type="text" name="purchase_request_nominal" id="purchase_request_nominal" class="form-control" placeholder="0" onkeyup="formatRupiah(this)" value="0">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Is PPN? :<sup class="text-danger">*</sup></label>
							<select name="cost_ppn" id="cost_ppn" class="custom-select">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>PPN (%) :<sup class="text-danger">*</sup></label>
							<input type="text" name="cost_ppn_nominal" id="cost_ppn_nominal" class="form-control" placeholder="0" onkeyup="formatRupiah(this);" value="11">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>&nbsp;</label>
							<button type="button" onclick="addPurchaseRequest()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add Manual</button>
						</div>
					</div>
				</div>
				<div class="row d-flex justify-content-center">
					<div class="col-md-6">
						<div class="form-group">
							<label>&nbsp;</label>
							<button type="button" onclick="getWipCoa()" class="btn bg-info col-12"><i class="icon-plus2"></i> Generate Purchase Request From COA</button>
						</div>
					</div>
				</div>
				<hr>
				<h5 class="modal-title">List of Costs</h5>
				<div class="form-group mt-2">
				   <div class="table-responsive">
					  <table class="table table-bordered table-striped" width="100%">
						 <thead class="table-secondary">
							<tr class="text-center">
								<th>No.</th>
								<th>To</th>
								<th>Details</th>
								<th>Nominal</th>
								<th>PPN</th>
								<th>Total B.Tax</th>
								<th>Action</th>
							</tr>
						 </thead>
						 <tbody id="data_cost">
							
						 </tbody>
						 <tfoot style="font-size:15px !important;">
							<tr>
								<td colspan="3" align="right">Total</td>
								<td align="right" class="bg-primary" id="totalaftertax">0</td>
								<td align="right" class="bg-info" id="totalppn">0</td>
								<td align="right" class="bg-info" id="totalbeforetax">0</td>
							</tr>
							<tr>
								<td colspan="3" align="right">Fee MKJ</td>
								<td align="right" class="bg-info" id="totalfeemkj" colspan="3">0</td>
							</tr>
							<tr>
								<td colspan="3" align="right">Grandtotal (Total B.Tax + Total Fee MKJ)</td>
								<td align="right" class="bg-teal-400" id="grandtotal" colspan="3" style="font-size:30px !important;">0</td>
							</tr>
						 </tfoot>
					  </table>
				   </div>
				</div>
				<h5 class="modal-title">List of Products</h5>
				<div class="form-group mt-2">
				   <div class="table-responsive">
					  <table class="table table-bordered table-striped" width="100%">
						 <thead class="table-secondary">
							<tr class="text-center">
								<th>No.</th>
								<th>Product</th>
								<th>Qty</th>
								<th>Price@</th>
								<th>Total</th>
								<th>Pro-rate</th>
								<th>Total Pro-rate</th>
								<th>Final Cogs</th>
							</tr>
						 </thead>
						 <tbody id="data_product">
							
						 </tbody>
					  </table>
				   </div>
				</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_cost" onclick="createCost()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
		</div>
	</div>

	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Purchase</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-warning alert-styled-left alert-dismissible bg-warning">
							<span class="font-weight-semibold">Info!</span><b> Generate from PJ/ SO, only if buying for existing projects.</b>
						</div>
					</div>
				</div>
				<form id="form_data">
				   <div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
				   </div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Generate From PJ/ SO :<sup class="text-danger"></sup></label>
								<select name="so_id" id="so_id" class="custom-select select2"  onchange="getSalesProduct(this.value);getSalesInfo(this.value);">
									<option value="">-- Choose --</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Mode :<sup class="text-danger">*</sup></label>
								<select name="is_wip" id="is_wip" class="custom-select">
									<option value="0">NORMAL PO</option>
									<option value="1">WIP PO</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>PPN :<sup class="text-danger">*</sup></label>
								<input type="hidden" name="temp" id="temp">
								<select name="ppn" id="ppn" class="custom-select">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Letter Head :<sup class="text-danger">*</sup></label>
								<select name="letter_head" id="letter_head" class="custom-select">
									<option value="1">PTA</option>
									<option value="2">PSI</option>
									<option value="3">MKJ</option>
									<option value="4">BELLAQUATTRO</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Purchase Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
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
					<h5 class="card-title"><b>Customer</b></h5>
					<div class="form-group"><hr></div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Customer :<sup class="text-danger">*</sup></label>
								<select name="customer_id" id="customer_id" class="custom-select">
								</select>
							</div>
						</div>
					</div>
					<h5 class="card-title"><b>Fee PTA</b></h5>
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
								<span class="font-weight-semibold">Important Info!</span> Fee PTA will be taken from total purchase inputed by purchase team.</a>.
							</div>
						</div>
					</div>
					<h5 class="card-title"><b>Order To</b></h5>
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
					<h5 class="card-title"><b>Receiver Information</b></h5>
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
					<h5 class="card-title"><b>Payment Information</b></h5>
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
								<input class="form-control" type="text" name="currency_rate" id="currency_rate" value="1">
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
					<h5 class="card-title"><b>Detail Products</b></h5>
					<div class="form-group">
						<hr>
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Information!</span> The product purchase's price must be included with PPN if this PO has PPN.</a>.
						</div>
					</div>
					<div class="row">
					   <div class="col-md-8">
						  <div class="form-group">
							 <select name="product_id" id="product_id"></select>
						  </div>
					   </div>
					   <div class="col-md-4">
						  <div class="form-group">
							 <button type="button" onclick="addProduct()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add</button>
						  </div>
					   </div>
					</div>
					<div class="form-group">
					   <div class="table-responsive">
						  <table class="table table-bordered table-striped" width="100%">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th>Product</th>
								   <th>Qty</th>
								   <th width="10%">Unit</th>
								   <th>Price M<sup>2</sup> / PCS</th>
								   <!-- <th>Total</th> -->
								   <th>Shade</th>
								   <th>Delete</th>
								</tr>
							 </thead>
							 <tbody id="data_purchase">
								
							 </tbody>
						  </table>
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

	<div class="modal fade" id="modal_form_quotation" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Request Quotation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_quotation">
				   <div class="alert alert-danger" id="validation_alert_quotation" style="display:none;">
					  <ul id="validation_content_quotation"></ul>
				   </div>
				    <input type="hidden" name="temp_quotation_id" id="temp_quotation_id">
					<h5 class="card-title"><b>Request To</b></h5>
					<div class="form-group"><hr></div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Supplier :<sup class="text-danger">*</sup></label>
								<select name="supplier_id" id="supplier_id_quotation"></select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Sales :<sup class="text-danger">*</sup></label>
								<select name="sales_id" id="sales_id"></select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Note :</label>
								<input type="text" name="note" id="note" class="form-control" placeholder="Enter here" value="{{ old('note') }}">
							</div>
						</div>
					</div>
					<h5 class="card-title"><b>Detail Products</b></h5>
					<div class="form-group">
						<hr>
					</div>
					<div class="row">
					   <div class="col-md-8">
						  <div class="form-group">
							 <select name="product_id_quotation" id="product_id_quotation"></select>
						  </div>
					   </div>
					   <div class="col-md-4">
						  <div class="form-group">
							 <button type="button" onclick="addProduct()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add</button>
						  </div>
					   </div>
					</div>
					<div class="form-group">
					   <div class="table-responsive">
						  <table class="table table-bordered table-striped" width="100%">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th>Product</th>
								   <th width="10%">Unit</th>
								   <th>Shade</th>
								   <th>Delete</th>
								</tr>
							 </thead>
							 <tbody id="data_quotation">
								
							 </tbody>
						  </table>
					   </div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()" style="display:none;"><i class="icon-cross3"></i> Cancel</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="createQuotation()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_proforma" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Proforma <span id="proforma_id"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_proforma">
				   <div class="alert alert-danger" id="validation_alert_proforma" style="display:none;">
					  <ul id="validation_content_proforma"></ul>
				   </div>
				   <div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="hidden" name="tempProforma" id="tempProforma">
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
				   <div class="table-responsive">
					  <table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>PO Code</th>
							   <th>SO No.</th>
							   <th>Date</th>
							   <th>Supplier</th>
							   <th>Warehouse</th>
							   <th>Proof</th>
							</tr>
						 </thead>
						 <tbody id="data_proforma">
						  </tbody>
					  </table>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_proforma" onclick="createProforma()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_bill" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Purchase Bill Request <span id="bill_id"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_bill">
				   <div class="alert alert-danger" id="validation_alert_bill" style="display:none;">
					  <ul id="validation_content_bill"></ul>
				   </div>
				   <div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Document Number :<sup class="text-danger">*</sup></label>
								<input type="hidden" name="tempBill" id="tempBill">
								<input type="text" class="form-control" name="purchase_bill_doc" id="purchase_bill_doc" placeholder="Document number...">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Method :<sup class="text-danger">*</sup></label>
								<select name="purchase_bill_method" id="purchase_bill_method" class="form-control">
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
								<input class="form-control" type="date" name="purchase_bill_date" id="purchase_bill_date">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Due Date Bill :<sup class="text-danger">*</sup></label>
								<input class="form-control" type="date" name="purchase_bill_due_date" id="purchase_bill_due_date">
							</div>
						</div>
						<div class="col-md-4">
						 <div class="form-group">
							<label>Nominal in Rupiah (IDR) :<sup class="text-danger">*</sup></label>
							<input type="text" name="purchase_bill_nominal" id="purchase_bill_nominal" class="form-control" placeholder="0" onkeyup="formatRupiah(this)">
						 </div>
						</div>
						<div class="col-md-4">
						  <div class="form-group">
							<label>Note :<sup class="text-danger">*</sup></label>
							<input type="text" class="form-control" name="purchase_bill_note" id="purchase_bill_note" placeholder="Document number...">
						  </div>
						</div>
						<div class="col-md-4">
						  <div class="form-group">
							<label>Document Proof :<sup class="text-danger">*</sup></label>
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="purchase_bill_file" name="purchase_bill_file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
						  </div>
						</div>
				   </div>
				</form>
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
						 <tbody id="data_bill"></tbody>
					</table>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_bill" onclick="createBill()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_production" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Progress Production <span id="production_id"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_production">
				   <div class="alert alert-danger" id="validation_alert_production" style="display:none;">
					  <ul id="validation_content_production"></ul>
				   </div>
				   <div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<input type="hidden" name="tempProduction" id="tempProduction">
								<label>Start Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="start_date" id="start_date" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Finish Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="finish_date" id="finish_date" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Progress(%) :<sup class="text-danger">*</sup></label>
								<input type="number" name="progress_production" id="progress_production" placeholder="0" class="form-control">
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
								<textarea name="note" id="note" class="form-control" placeholder="Enter note" rows="1">-</textarea>
							 </div>
						</div>
				   </div>
				</form>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_production" onclick="createProduction()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_delivery" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Delivery Document Number <span id="delivery_id"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_delivery">
				   <div class="alert alert-danger" id="validation_alert_delivery" style="display:none;">
					  <ul id="validation_content_delivery"></ul>
				   </div>
				   <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
					<li class="nav-item">
					   <a href="#delivery-tab1" class="nav-link active" data-toggle="tab">Form</a>
					</li>
					<li class="nav-item">
					   <a href="#delivery-tab2" class="nav-link" data-toggle="tab">List of All Shipment Purchases</a>
					</li>
				  </ul>
				  <div class="tab-content">
					<div class="tab-pane fade show active" id="delivery-tab1">
						<div class="row">
						 <div class="col-md-4 d-none" id="temp_pos_id">
							 <div class="form-group">
								<label>Purchase Order :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="temp_shipment_id" name="temp_shipment_id" value="">
								<select name="pos_id" id="pos_id" class="select2" onchange="getPurchaseProduct(this,this.value);">
								   <option value="">-- Choose --</option>
								</select>
							 </div>
						  </div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="hidden" name="tempDelivery" id="tempDelivery">
									<label>Shipment Document Code :<sup class="text-danger">*</sup></label>
									<input type="text" name="shipment_code" id="shipment_code" class="form-control" placeholder="Enter shipment code">
								</div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>Loading Date :<sup class="text-danger">*</sup></label>
									<input type="date" name="loading_date" id="loading_date" class="form-control">
								 </div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>Departure Date :<sup class="text-danger">*</sup></label>
									<input type="date" name="departure_date" id="departure_date" class="form-control">
								 </div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>From Port :<sup class="text-danger">*</sup></label>
									<input type="text" name="from_port" id="from_port" class="form-control" placeholder="Enter from port">
								 </div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>To Port :<sup class="text-danger">*</sup></label>
									<input type="text" name="to_port" id="to_port" class="form-control" placeholder="Enter to port">
								 </div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>ETA :<sup class="text-danger">*</sup></label>
									<input type="date" name="eta" id="eta" class="form-control">
								 </div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>Delivery Method :<sup class="text-danger">*</sup></label>
									<select name="delivery_method" id="delivery_method" class="custom-select">
										<option value="1">Air</option>
										<option value="2">Sea</option>
										<option value="3">Land</option>
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
									<textarea name="note" id="note" class="form-control" placeholder="Enter note" rows="1">-</textarea>
								 </div>
							</div>
					   </div>
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
								 </tbody>
							  </table>
						   </div>
						</div>
					</div>
					<div class="tab-pane fade" id="delivery-tab2">
						<h5 class="card-title">
							<b>List of All Shipment Purchases</b>
						</h5>
						<div class="form-group"><hr></div>
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
							 <tbody id="detail_delivery_shipment">
								<tr class="text-center">
									<td colspan="11">
										<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no Shipment Purchases here.</div>
									</td>
								</tr>
							 </tbody>
						  </table>
					   </div>
					</div>
				</div>
				
				</form>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_delivery" onclick="createDelivery()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_warehouse" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Warehouse Receive Purchase <span id="warehouse_info"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_warehouse">
				   <div class="alert alert-danger" id="validation_alert_warehouse" style="display:none;">
					  <ul id="validation_content_warehouse"></ul>
				   </div>
				   <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
					  <li class="nav-item">
						 <a href="#warehouse-tab1" class="nav-link active" data-toggle="tab">Form</a>
					  </li>
					  <li class="nav-item">
						 <a href="#warehouse-tab2" class="nav-link" data-toggle="tab">List Of All Warehouse Receive</a>
					  </li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="warehouse-tab1">
							<input type="hidden" name="temp_warehouse_id" id="temp_warehouse_id">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<input type="hidden" name="tempWarehouse" id="tempWarehouse">
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
										<input class="form-control" type="datetime-local" name="date_receive" id="date_receive" value="{{ old('date_receive') }}">
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
								<div class="col-md-4 cost-class d-none">
									<div class="alert alert-info alert-styled-left alert-dismissible">
										<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
										<span class="font-weight-semibold">Important Info!</span><b> This information is used to determine journal information.</b>
									</div>
								</div>
							</div>
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
										   <th>Qty Broken</th>
										   <th>Unit</th>
										   <th>Delete</th>
										</tr>
									 </thead>
									 <tbody id="data_warehouse_product">
										
									 </tbody>
								  </table>
							   </div>
							</div>
						</div>
						<div class="tab-pane fade" id="warehouse-tab2">
							<h5 class="card-title">
								<b>List of All Warehouse Receive</b>
							</h5>
							<div class="form-group"><hr></div>
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
								 <tbody id="detail_warehouse_receive">
									<tr class="text-center">
										<td colspan="8">
											<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no warehouse receive here.</div>
										</td>
									</tr>
								 </tbody>
							  </table>
						   </div>
						</div>
					</div>
					
				</form>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_warehouse" onclick="createWarehouse()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
		</div>
	</div>
	
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
	
	<div class="modal fade" id="modal_return" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h4 class="modal-title">Purchase Return Purchase Code. <b id="modal_title_purchase_return"></b></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
					<div class="alert alert-danger" id="validation_alert_return" style="display:none;">
					  <ul id="validation_content_return"></ul>
					</div>
					<ul class="nav nav-tabs nav-tabs-highlight nav-justified">
					  <li class="nav-item">
						 <a href="#highlighted-justified-tab1" class="nav-link active" data-toggle="tab">Form</a>
					  </li>
					  <li class="nav-item">
						 <a href="#highlighted-justified-tab2" class="nav-link" data-toggle="tab">List Of All Return</a>
					  </li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="highlighted-justified-tab1">
							<h5 class="card-title">
								<b>Main Information</b>
							</h5>
							<div class="form-group"><hr></div>
							<div class="row">
								<div class="col-md-6">
								  <div class="form-group">
									<input type="hidden" id="tempReturn">
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
											   <th>Location</th>
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
						<div class="tab-pane fade" id="highlighted-justified-tab2">
							<h5 class="card-title">
								<b>List of All Purchase Requests</b>
							</h5>
							<div class="form-group"><hr></div>
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
									   <th>Action</th>
									</tr>
								 </thead>
								 <tbody id="detail_purchase_return">
									<tr class="text-center">
										<td colspan="7">
											<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no purchase return here.</div>
										</td>
									</tr>
								 </tbody>
							  </table>
						   </div>
						</div>
					</div>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_sample" onclick="addReturnPurchase()"><i class="icon-plus3"></i> Save</button>
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
<script>
	$(function() {
		loadDataTable();
		loadDataTableQuotation();
		select2ServerSide('#product_id, #product_id_quotation', '{{ url("admin/select2/product") }}');
		select2ServerSide('#sales_po, #sales_id', '{{ url("admin/select2/user") }}');
		select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
		select2ServerSide('#supplier_id, #supplier_id_quotation', '{{ url("admin/select2/supplier") }}');
		select2ServerSide('#country_id, #country_id_quotation', '{{ url("admin/select2/country") }}');
		select2ServerSide('#city_id,#city_id2', '{{ url("admin/select2/city") }}');
		select2ServerSide('#warehouse_id, #purchase-return-warehouse', '{{ url("admin/select2/warehouse") }}');
		select2ServerSide('#purchase_request_id', '{{ url("admin/select2/purchase_request") }}');
	    select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
		select2ServerSide('#so_id', '{{ url("admin/select2/sales_order_for_purchase") }}');
	  
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
		
		$('#data_purchase').on('click', '#delete_data_product_purchase', function() {
			$(this).closest('tr').remove();
		});

		$('#data_quotation').on('click', '#delete_data_quotation', function() {
			$(this).closest('tr').remove();
		});

		$('#data_purchase_return').on('click', '#delete_purchase_return_product', function() {
			$(this).closest('tr').remove();
		});

		$('#data_cost').on('click', '#delete_wip_unsave', function() {
			$('.row' + $('#delete_wip_unsave').val()).remove();
			countAll();
		});

		$('#data_cost').on('click', '#delete_wip', function() {
			var notyConfirm = new Noty({
				theme: 'limitless',
				text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason" class="form-control" placeholder="Enter why this project should be deleted?"></div></div>',
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
							url: '{{ url("admin/inventory/purchase/delete_cost") }}',
							type: 'POST',
							dataType: 'JSON',
							data: {
								purchase_request_id: $('#delete_wip').val()
							},
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							beforeSend: function() {
								loadingOpen('#modal_cost');
							},
							success: function(response) {
								$('.row' + $('#delete_wip').val()).remove();
								countAll();
								if(response.status == 200) {
									$('#datatable_serverside').DataTable().ajax.reload(null, false);
									notif('success', 'bg-success', response.message);
									notyConfirm.close();
								
									loadingClose('#modal_cost');
								} else {
									notif('error', 'bg-danger', response.message);
								}
							},
							error: function() {
								loadingClose('#modal_cost');
								swalInit.fire({
									title: 'Server Error',
									text: 'Please contact developer',
									type: 'error'
								});
							}
							});
						}else{
							notif('error', 'bg-warning', 'Please explain why this project should be deleted?');
						}
					})
				]
			}).show();
			
		});
		
		$('#data_shipment_product').on('click', '#delete_ship_product', function() {
			$(this).closest('tr').remove();
		});
		
		$('#data_warehouse_product').on('click', '#delete_shipment_product', function() {
			$(this).closest('tr').remove();
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

		$('#datatable_serverside_quotation tbody').on('click', 'td.details-control', function() {
			var tr    = $(this).closest('tr');
			var badge = tr.find('span.badge');
			var icon  = tr.find('i');
			var row   = tableQuotation.row(tr);

			if(row.child.isShown()) {
				row.child.hide();
				tr.removeClass('shown');
				badge.first().removeClass('badge-danger');
				badge.first().addClass('badge-success');
				icon.first().removeClass('icon-minus3');
				icon.first().addClass('icon-plus3');
			} else {
				row.child(rowDetailQuotation(row.data())).show();
				tr.addClass('shown');
				badge.first().removeClass('badge-success');
				badge.first().addClass('badge-danger');
				icon.first().removeClass('icon-plus3');
				icon.first().addClass('icon-minus3');
			}
		});
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#form_data').trigger('reset');
			$('#temp').val(null);
		});

		$('#modal_form_quotation').on('hidden.bs.modal', function (e) {
			$('#form_data_quotation').trigger('reset');
			$('#product_id_quotation').val(null).trigger('change.select2');
			$('#data_quotation').empty();
			$('#temp_quotation_id').val(null);
		});
		
		$('#modal_proforma').on('hidden.bs.modal', function (e) {
			$('#form_data_proforma').trigger('reset');
			$('#tempProforma').val(null);
		});
		
		$('#modal_bill').on('hidden.bs.modal', function (e) {
			$('#form_data_bill').trigger('reset');
			$('#tempBill').val(null);
		});
		
		$('#modal_production').on('hidden.bs.modal', function (e) {
			$('#form_data_production').trigger('reset');
			$('#tempProduction').val(null);
		});
		
		$('#modal_return').on('hidden.bs.modal', function (e) {
			$('#project_warehouse_id').empty();
			$('#project_warehouse_id').append(`
				<option value="">--Choose this if from purchase bill--</option>
			`);
		});
		
		$('#modal_cost').on('hidden.bs.modal', function (e) {
			$('#form_data_cost').trigger('reset');
			$('#tempCost').val(null);
			$('#data_cost').empty();
			$('#totalaftertax,#totalppn,#totalbeforetax,#totalfeemkj,#grandtotal').text('0');
		});
		
		$('#modal_delivery').on('hidden.bs.modal', function (e) {
			$('#form_data_delivery').trigger('reset');
			$('#tempDelivery').val(null);
			$('#data_shipment_product').empty();
		});
		
		$('#modal_warehouse').on('hidden.bs.modal', function (e) {
			$('#form_data_warehouse').trigger('reset');
			$('#tempWarehouse').val(null);
			$('#data_warehouse_product').empty();
		});
		
		$('.sidebar-main-toggle').click();
		
		$('#modal_documents').on('hidden.bs.modal', function (e) {
			loadDataTable();
		});
		
	});
	
	function addPurchaseRequest(){
		if($('#purchase_request_id').val()){
			var nominal = $('#purchase_request_nominal').val();
			var detail = $("#purchase_request_id").select2('data')[0].text;
			var to = $("#purchase_request_id").select2('data')[0].to;
			var id = $('#purchase_request_id').select2('data')[0].id;
			var persenppn = $('#cost_ppn').val() == '1' ? $('#cost_ppn_nominal').val() : 0;
			var ada = false;
			
			$(".rowcost").each(function() {
				if($(this).data('pr') == id){
					ada = true;
				}
			});
			
			if(ada == false){
				var ppnnominal = Math.round(parseFloat(nominal.replaceAll('.','').replaceAll(',','.')) / ((100 + parseFloat(persenppn.toString().replaceAll('.','').replaceAll(',','.'))) / 100));
				var ppn = Math.round(parseFloat(nominal.replaceAll('.','').replaceAll(',','.')) - parseFloat(ppnnominal.toFixed(0)));
				
				var no = $('.rowcost').length;
				
				if(no == 0){
					$('#data_cost').empty();
				}
				
				$('#data_cost').append(`
					<tr class="text-center rowcost row` + id + `" data-pr="` + id + `">
					   <input type="hidden" name="purchase_request_id[]" value="` + id + `">
					   <input type="hidden" name="purchase_request_nominal[]" value="` + nominal + `">
					   <input type="hidden" name="purchase_is_ppn[]" value="` + $('#cost_ppn').val() + `" class="rowisppn">
					   <input type="hidden" name="purchase_percent_ppn[]" value="` + $('#cost_ppn_nominal').val() + `" class="rowppnnominal">
					   <td class="align-middle">` + (no + 1) + `</td>
					   <td class="align-middle">` + to + `</td>
					   <td class="align-middle">` + detail + `</td>
					   <td align="right" class="rowaftertax" data-pr="` + id + `">` + nominal + `</td>
					   <td align="right" class="rowppn" id="rowppn` + id + `">` + (ppn ? formatRupiahIni(ppn.toFixed(0)) : 0) + `</td>
					   <td align="right" class="rowbeforetax" id="rowbeforetax` + id + `">` + (ppnnominal ? formatRupiahIni(ppnnominal.toFixed(0)) : nominal) + `</td>
					   <td class="align-middle">
						<button type="button" id="delete_wip_unsave" value="` + id + `" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
					   </td>
					</tr>
				`);
				
				countAll();
				
				$('#purchase_request_id').val(null).trigger('change');
				$('#purchase_request_nominal').val('0');
			}else{
				swalInit.fire('Hayo', 'This Purchase Request already in Cost list.', 'error');
			}
		}
	}
	
	function countAll(){
		
		$(".rowaftertax").each(function( index ) {
			var persenppn = $('.rowisppn').eq(index).val() == '1' ? $('.rowppnnominal').eq(index).val() : 0;
			var ppnnominal = parseFloat($(this).text().replaceAll('.','').replaceAll(',','.')) / ((100 + parseFloat(persenppn.toString().replaceAll('.','').replaceAll(',','.'))) / 100);
			var ppn = parseFloat($(this).text().replaceAll('.','').replaceAll(',','.')) - parseFloat(ppnnominal.toFixed(2));
			$('.rowppn').eq(index).text(formatRupiahIni(ppn.toFixed(0).toString().replace('.',',')));
			$('.rowbeforetax').eq(index).text(formatRupiahIni(ppnnominal.toFixed(0).toString().replace('.',',')));
		});

		var totalaftertax = 0, totalppn = 0, totalbeforetax = 0;
		
		$(".rowaftertax").each(function() {
			totalaftertax += parseFloat($(this).text().replaceAll('.','').replaceAll(',','.'));
		});
		
		$(".rowppn").each(function() {
			totalppn += parseFloat($(this).text().replaceAll('.','').replaceAll(',','.'));
		});
		
		$(".rowbeforetax").each(function() {
			totalbeforetax += parseFloat($(this).text().replaceAll('.','').replaceAll(',','.'));
		});
		
		$('#totalaftertax').text(formatRupiahIni(totalaftertax.toFixed(0).toString().replace('.',',')));
		$('#totalppn').text(formatRupiahIni(totalppn.toFixed(0).toString().replace('.',',')));
		$('#totalbeforetax').text(formatRupiahIni(totalbeforetax.toFixed(0).toString().replace('.',',')));
		
		var feemkj = (parseFloat($('#cost_fee_mkj').val().replaceAll('.','').replaceAll(',','.')) * totalbeforetax) / 100;
		
		$('#totalfeemkj').text(formatRupiahIni(feemkj.toFixed(0).toString().replace('.',',')));
		
		/* var grandtotal = totalppn + feemkj + totalbeforetax; */
		var grandtotal = feemkj + totalbeforetax;
		
		$('#grandtotal').text(formatRupiahIni(grandtotal.toFixed(0).toString().replace('.',',')));
		
		var totalfinalprorate = 0, totalfinalprice = 0;
		
		$(".prorate").each(function() {
			var totalprorate = (parseFloat($(this).text()) * grandtotal) / 100;
			var finalprice = totalprorate / parseFloat($('#qty' + $(this).data('id')).text());
			$('#totalprorate' + $(this).data('id')).text(formatRupiahIni(totalprorate.toFixed(0).toString().replace('.',',')));
			$('#finalprice' + $(this).data('id')).val(formatRupiahIni(finalprice.toFixed(0).toString().replace('.',',')));
			
			totalfinalprorate += totalprorate;
			totalfinalprice += finalprice;
		});
		
		$('#totalfinalprorate').text(formatRupiahIni(totalfinalprorate.toFixed(0).toString().replace('.',',')));
		$('#totalfinalprice').text(formatRupiahIni(totalfinalprice.toFixed(0).toString().replace('.',',')));
	}
	
	function getPurchaseRequestNominal(){
		if($("#purchase_request_id").val()){
			var nominal = $("#purchase_request_id").select2('data')[0].nominalpay;
			$('#purchase_request_nominal').val(nominal);
		}else{
			$('#purchase_request_nominal').val('0');
		}
	}
	
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
	
	function addReturnPurchase(){
		var id = $('#tempReturn').val();
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
	
	function updateReturnPurchase(idpo,code){
		$('#modal_title_purchase_return').html(code);
		$('#tempReturn').val(idpo);
		
		$('#modal_return').modal('toggle');
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_product") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
            idpo : idpo
         },
         beforeSend: function() {
			
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
						 <td class="align-middle">` + val.product_location + `</td>
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
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_return") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
            idpo : idpo
         },
         beforeSend: function() {
			loadingOpen('.modal-body');
         },
         success: function(response) {
			if(response.length > 0) {
				$('#detail_purchase_return').empty();
				
				$.each(response, function(i, val) {
					$('#detail_purchase_return').append(`
						<tr class="text-center">
						  <td class="align-middle">` + val.no + `</td>
						  <td class="align-middle">` + val.po_no + `</td>
						  <td class="align-middle">` + val.supplier + `</td>
						  <td class="align-middle">
							` + val.approved + `
						  </td>
						  <td>
							<a onclick="openLink('` + val.link + `')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
						  </td>
						  <td>
							<a href="` + val.proof + `" target="_blank" class="btn bg-info"><i class="icon-file-pdf"></i></a>
						  </td>
						  <td>
							<a href="javascript:void(0);" onclick="deleteReturn(`+ val.id +`)" class="btn bg-danger"><i class="icon-trash"></i></a>
						  </td>
					   </tr>
					`);
				});
				
			}
			
			loadingClose('.modal-body');
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
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
							loadingOpen('#modal-body-return-purchase');
						 },
						 success: function(response) {
							loadingClose('#modal-body-return-purchase');
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
							loadingClose('#modal-body-return-purchase');
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
   
	function getSupplierCurrency(idsupp) {
		$('#currency').empty();
		$('#currency_quotation').empty();
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
						$('#currency_quotation').append(`
							<option value="` + val.id + `">` + val.code + `</option>
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
      $('#btn_create').hide();
      $('#btn_update').show();
      $('#btn_cancel').show();
	}

	function reset() {
      $('#form_data').trigger('reset');
	  $('#temp').val('');
      $('#validation_alert').hide();
      $('#validation_content').html('');
	}

	function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}

	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'desc']],
         ajax: {
            url: '{{ url("admin/inventory/purchase/datatable") }}',
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
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'po', className: 'text-center align-middle' },
            { name: 'so', orderable: false, className: 'text-center align-middle' },
            { name: 'customer', className: 'text-center align-middle' },
            { name: 'supplier', className: 'text-center align-middle' },
            { name: 'check', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'approve', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'created_at', className: 'text-center align-middle' },
            { name: 'view', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'cost', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'proforma', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'payment', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'production', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'delivery', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'warehouse', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'sample', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'tax', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
		]
      }); 
	}

	function loadDataTableQuotation() {
      window.tableQuotation = $('#datatable_serverside_quotation').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'desc']],
         ajax: {
            url: '{{ url("admin/inventory/request_quotation/datatable") }}',
            type: 'GET',
            data: {
				
            },
            beforeSend: function() {
               loadingOpen('#datatable_serverside_quotation');
            },
            complete: function() {
               loadingClose('#datatable_serverside_quotation');
            },
            error: function() {
               loadingClose('#datatable_serverside_quotation');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
        columns: [
			{ name: 'detail', orderable: false, searchable: false, className: 'text-center align-middle details-control' },
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'po', className: 'text-center align-middle' },
            { name: 'so', orderable: false, className: 'text-center align-middle' },
            { name: 'supplier', className: 'text-center align-middle' },
            { name: 'approve', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'created_at', className: 'text-center align-middle' },
            { name: 'view', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
		]
      }); 
	}
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/inventory/purchase/row_detail") }}',
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

	function rowDetailQuotation(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/inventory/request_quotation/row_detail") }}',
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
	
	function openLink(url){
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
	}
	
	function addProduct(){
		var product = $('#product_id');
		var product_quotation = $('#product_id_quotation');
		if(product.val() != null){
			$('#data_purchase').append(`
				<tr class="text-center rowproductsale purchaseproductdata` + product.val() + `">
				 <input type="hidden" name="product_id[]" value="` + product.val() + `">
				 <td class="align-middle">` + product.select2('data')[0].text + `</td>
				 <td class="align-middle" width="10%">
					<input type="number" name="product_qty[]" id="purchaseproductqty` + product.val() + `" class="form-control" placeholder="0" value="0" required onkeyup="countTotalPurchase(this,'`+ product.val() +`');">
				 </td>
				 <td class="align-middle">
					<select name="product_unit[]" class="custom-select" required>
						<option value="4">Meter (Custom)</option>
						<option value="2">Box</option>
						<option value="1">Pcs</option>   
					</select>
				 </td>
				 <td class="align-middle">
					<input type="text" name="product_price[]" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);">
				 </td>
				 <td class="align-middle">
					<textarea class="form-control" rows="1" name="product_remark[]">-</textarea>
				 </td>
				 <td class="align-middle">
					<button type="button" id="delete_data_product_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				 </td>
			  </tr>
			`);
		}else if(product_quotation){
			$('#data_quotation').append(`
				<tr class="text-center rowproductsale quotationdata` + product_quotation.val() + `">
				 <input type="hidden" name="product_id[]" value="` + product_quotation.val() + `">
				 <td class="align-middle">` + product_quotation.select2('data')[0].text + `</td>
				 <td class="align-middle">
					<select name="product_unit[]" class="custom-select" required>
						<option value="4">Meter (Custom)</option>
						<option value="2">Box</option>
						<option value="1">Pcs</option>   
					</select>
				 </td>
				 <td class="align-middle">
					<textarea class="form-control" rows="1" name="product_remark[]">-</textarea>
				 </td>
				 <td class="align-middle">
					<button type="button" id="delete_data_quotation" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				 </td>
			  </tr>
			`);
		}
	}
	
	/* function countTotalPurchase(element,id){
	   
		var result = parseFloat($(element).val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#purchaseproductqty' + id).val());
	   
	   
		$('#purchaseproducttotal'+id).html(formatRupiahIni(result));
		
	} */
	
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
	
	function reset() {
		$('#form_data').trigger('reset');
		$('#data_purchase').empty();
		$('#validation_alert').hide();
		$('#validation_content').html('');
	}
	
	function success() {
		reset();
		$('#modal_form').modal('hide');
		$('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	function successQuotation() {
		reset();
		$('#modal_form_quotation').modal('hide');
		$('#datatable_serverside_quotation').DataTable().ajax.reload(null, false);
	}
	
	function create() {
		$.ajax({
		 url: '{{ url("admin/inventory/purchase/create") }}',
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
	}

	function createQuotation(){
		$.ajax({
		 url: '{{ url("admin/inventory/request_quotation/create") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: $('#form_data_quotation').serialize(),
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_quotation').hide();
			$('#validation_content_quotation').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
			   successQuotation();
			   notif('success', 'bg-success', response.message);
			} else if(response.status == 422) {
			   $('#validation_alert_quotation').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_quotation').append(`
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
	
	function destroy(id) {
      var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason" class="form-control" placeholder="Enter why this project should be deleted?"></div></div>',
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
					  url: '{{ url("admin/inventory/purchase/destroy") }}',
					  type: 'POST',
					  dataType: 'JSON',
					  data: {
						 id: id, reason : $('#delete_reason').val()
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
				}else{
					notif('error', 'bg-warning', 'Please explain why this project should be deleted?');
				}
            })
         ]
      }).show();
	}

	function destroyQuotation(id) {
      var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason_quotation" class="form-control" placeholder="Enter why this quotation should be deleted?"></div></div>',
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
				if($('#delete_reason_quotation').val() !== ''){
					$.ajax({
					  url: '{{ url("admin/inventory/request_quotation/destroy") }}',
					  type: 'POST',
					  dataType: 'JSON',
					  data: {
						 id: id, reason : $('#delete_reason_quotation').val()
					  },
					  headers: {
						 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					  },
					  success: function(response) {
						 if(response.status == 200) {
							$('#datatable_serverside_quotation').DataTable().ajax.reload(null, false);
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
				}else{
					notif('error', 'bg-warning', 'Please explain why this project should be deleted?');
				}
            })
         ]
      }).show();
	}
	
	function show(id){
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				purchaseid : id
			 },
			 beforeSend: function() {
				loadingOpen('.modal-body');
			 },
			 success: function(response) {
				if(response.status == '422'){
					notif('error', 'bg-danger', response.message);
				}else{
					$('#temp').val(id);
					
					$('#modal_form').modal('toggle');
					if(response){
						$('html, body').animate({
							scrollTop: $('.modal-body').offset().top
						}, 'slow');
						
						if(response.purchase.project_sale_id){
							$('#so_id').empty();
							$('#so_id').append(`
								<option value="` + response.purchase.project_sale_id + `">` + response.purchase.so_code + `</option>
							`);
						}

						if(response.purchase.customer_id){
							$('#customer_id').empty();
							$('#customer_id').append(`
								<option value="` + response.purchase.customer_id + `">` + response.purchase.customer_name + `</option>
							`);
						}

						$('#sales_po').empty();
						$('#sales_po').append(`
							<option value="` + response.purchase.sales_id + `">` + response.purchase.sales_name + `</option>
						`);
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
						$('#pic_name').val(response.purchase.pic);
						$('#pic_number').val(response.purchase.pic_no);
						$('#payment_method').val(response.purchase.payment_method);
						$('#payment_due_date').val(response.purchase.payment_due_date);
						$('#price').val(response.purchase.price);
						$('#currency').empty();
						$('#currency').append(`
							<option value="` + response.purchase.currency_id + `">` + response.purchase.currency_name + `</option>
							<option value="5">IDR Indonesia</option>
						`);
						$('#brand').val(response.purchase.brand_on_box);
						$('#sni').val(response.purchase.sni);
						$('#is_wip').val(response.purchase.is_wip);
						$('#data_purchase').empty();
						
						$.each(response.purchaseproduct, function(i, val) {
							
							var unit = val.unitraw;
							
							if(unit == '2' || unit == '3'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="2" selected>Box</option>
											<option value="1">Pcs</option>   
											<option value="4">Meter (Custom)</option>
										</select>
									 </td>
								`;
							}else if(unit == '1'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="1" selected>Pcs</option>
											<option value="2">Box</option>
											<option value="4">Meter (Custom)</option>
										</select>
									 </td>
								`;
							}else if(unit == '4'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="4" selected>Meter (Custom)</option>
											<option value="1">Pcs</option>
											<option value="2">Box</option>
										</select>
									 </td>
								`;
							}
							
							$('#data_purchase').append(`
								<tr class="text-center rowproductsale purchaseproductdata` + val.product_id + `">
									 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
									 <td class="align-middle">` + val.product_name + `</td>
									 <td class="align-middle" width="10%">
										<input type="number" name="product_qty[]" id="purchaseproductqty` + val.product_id + `" class="form-control" placeholder="0" value="` + val.qty + `" required onkeyup="countTotalPurchase(this,'` + val.product_id + `');">
									 </td>
									 `+ htmlUnit +`
									 <td class="align-middle">
										<input type="text" name="product_price[]" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);" value="` + val.price + `">
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
					}
				}
				
				loadingClose('.modal-body');
			 },
			 error: function() {
				loadingClose('.modal-body');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

	function showQuotation(id){
		$.ajax({
			url: '{{ url("admin/inventory/request_quotation/get_quotation") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('.modal-body');
			 },
			 success: function(response) {
				if(response.status == '422'){
					notif('error', 'bg-danger', response.message);
				}else{
					$('#temp_quotation_id').val(id);
					$('#modal_form_quotation').modal('toggle');
					if(response){
						$('html, body').animate({
							scrollTop: $('.modal-body').offset().top
						}, 'slow');
						
						$('#sales_id').empty();
						$('#sales_id').append(`
							<option value="` + response.sales_id + `">` + response.sales_name + `</option>
						`);
						$('#supplier_id_quotation').empty();
						$('#supplier_id_quotation').append(`
							<option value="` + response.supplier_id + `">` + response.supplier_name + `</option>
						`);
						$('#note').val(response.note);

						
						$.each(response.quotation_products, function(i, val) {
							
							var unit = val.unitraw;
						
							if(unit == '2' || unit == '3'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="2" selected>Box</option>
											<option value="1">Pcs</option>   
											<option value="4">Meter (Custom)</option>
										</select>
									 </td>
								`;
							}else if(unit == '1'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="1" selected>Pcs</option>
											<option value="2">Box</option>
											<option value="4">Meter (Custom)</option>
										</select>
									 </td>
								`;
							}else if(unit == '4'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="4" selected>Meter (Custom)</option>
											<option value="1">Pcs</option>
											<option value="2">Box</option>
										</select>
									 </td>
								`;
							}
							
							$('#data_quotation').append(`
								<tr class="text-center rowproductsale purchaseproductdata` + val.product_id + `">
									 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
									 <td class="align-middle">` + val.product_name + `</td>
									 `+ htmlUnit +`
									 <td class="align-middle">
										<textarea class="form-control" rows="1" name="product_remark[]">` + val.remark + `</textarea>
									 </td>
									 <td class="align-middle">
										<button type="button" id="delete_data_quotation" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									 </td>
								</tr>
							`);
						});
					}
				}
				
				loadingClose('.modal-body');
			 },
			 error: function() {
				loadingClose('.modal-body');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function updateProforma(id,code,supplier){
		$('#proforma_id').html("Code " + code + " Supplier " + supplier);
		
		$('#tempProforma').val(id);
					
		$('#modal_proforma').modal('toggle');
		
		$.ajax({
			url: '{{ url("admin/inventory/purchase/get_proforma") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
				idpo : id
			},
			beforeSend: function() {
				loadingOpen('.modal-body');
			},
			success: function(response) {
				$('#data_proforma').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						$('#data_proforma').append(`
							<tr class="text-center">
							  <td class="align-middle">` + val.purchase_code + `</td>
							  <td class="align-middle">` + val.sales_code + `</td>
							  <td class="align-middle">` + val.date + `</td>
							  <td class="align-middle">` + val.supplier_name + `</td>
							  <td class="align-middle">` + val.supplier_warehouse + `</td>
							  <td class="align-middle">
								<a href="` + val.attachment + `" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
							  </td>
						   </tr>
						`);
					});
					
				}else{
					$('#data_proforma').append(`
						<tr class="text-center">
							<td class="align-middle" colspan="6">There is no data proforma in this Purchase Order</td>
						</tr>
					`);
				}
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function updatePayment(id,code,supplier){
		$('#bill_id').html("Code " + code + " Supplier " + supplier);
		
		$('#tempBill').val(id);
		
		$('#modal_bill').modal('toggle');
		
		$.ajax({
			url: '{{ url("admin/inventory/purchase/get_bill") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
				idpo : id
			},
			beforeSend: function() {
				loadingOpen('.modal-body');
			},
			success: function(response) {
				$('#data_bill').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						$('#data_bill').append(`
							<tr class="text-center">
								<td>` + val.purchase_code + `</td>
								<td>` + val.no_document + `</td>
								<td>` + val.date + `</td>
								<td>` + val.due_date + `</td>
								<td>` + val.method + `</td>
								<td>` + val.nominal + `</td>
								<td>` + val.note + `</td>
								<td><a href="` + val.attachment + `" target="_blank" class="btn btn-info"><i class="icon-search4"></i></a></td>
								<td>
									<a href="javascript:void(0);" onclick="deletePurchaseBill(` + val.id + `)" class="btn bg-danger"><i class="icon-trash"></i></a>
								</td>
							</tr>
						`);
					});
					
				}else{
					$('#data_bill').append(`
						<tr class="text-center">
							<td class="align-middle" colspan="9">There is no data bills in this Purchase Order</td>
						</tr>
					`);
				}
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function updateProduction(id,code,supplier){
		$('#production_id').html("Code " + code + " Supplier " + supplier);
		
		$('#tempProduction').val(id);
					
		$('#modal_production').modal('toggle');
	}
	
	function updateCost(id,code,supplier){
		$('#cost_id').html("Code " + code + " Supplier " + supplier);
		
		$('#tempCost').val(id);
		
		$.ajax({
			url: '{{ url("admin/inventory/purchase/get_purchase_cost") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
				idpo : id
			},
			beforeSend: function() {
				loadingOpen('.modal-body');
			},
			success: function(response) {
				
				if(response.product.length > 0) {
					$('#data_product').empty();
					
					var totalprorate = 0;
					
					$.each(response.product, function(i, val) {
						
						var prorate = parseFloat(val.product_total.replaceAll('.','').replaceAll(',','.')) / parseFloat(response.purchase.grandtotal.replaceAll('.','').replaceAll(',','.')) * 100;
						
						totalprorate += prorate;
						
						$('#data_product').append(`
							<tr class="text-center rowproduct">
								<input type="hidden" name="project_purchase_product_id[]" value="` + val.id + `">
								<td class="align-middle">` + (i + 1) + `</td>
								<td class="align-middle">` + val.product_name + `</td>
								<td class="align-middle" id="qty` + val.id + `">` + val.product_qty + `</td>
								<td align="right">` + val.product_price + `</td>
								<td align="right">` + val.product_total + `</td>
								<td align="right" class="prorate" data-id="` + val.id + `" id="prorate` + val.id + `">` + prorate + `</td>
								<td align="right" class="totalprorate" id="totalprorate` + val.id + `">0</td>
								<td align="right"><input type="text" readonly class="form-control finalprice" id="finalprice` + val.id + `" name="final_price[]" value="0"></td>
							</tr>
						`);
					});
					
					$('#data_product').append(`
						<tr class="text-center rowproduct">
							<td class="align-middle" colspan="4">Grandtotal</td>
							<td align="right">` + response.purchase.grandtotal + `</td>
							<td align="right">` + totalprorate.toFixed(2) + `</td>
							<td align="right" id="totalfinalprorate"></td>
							<td align="right" id="totalfinalprice">0</td>
						</tr>
					`);
				}
				
				if(response.cost.length > 0) {
					$('#data_cost').empty();
					
					$('#cost_ppn').val(response.maincost.is_ppn);
					$('#cost_ppn_nominal').val(formatRupiahIni(parseFloat(response.maincost.percent_ppn).toFixed(0).toString().replace('.',',')));
					$('#cost_fee_mkj').val(formatRupiahIni(parseFloat(response.maincost.percent_fee_mkj).toFixed(0).toString().replace('.',',')));
					
					$.each(response.cost, function(i, val) {
						
						$('#data_cost').append(`
							<tr class="text-center rowcost row` + val.purchase_request_id + `" data-pr="` + val.purchase_request_id + `">
							   <input type="hidden" name="purchase_request_id[]" value="` + val.purchase_request_id + `">
							   <input type="hidden" name="purchase_request_nominal[]" value="` + val.nominal + `">
							   <input type="hidden" name="purchase_is_ppn[]" value="` + val.is_ppn + `" class="rowisppn">
							   <input type="hidden" name="purchase_percent_ppn[]" value="` + val.tax + `" class="rowppnnominal">
							   <td class="align-middle">` + (i + 1) + `</td>
							   <td class="align-middle">` + val.to + `</td>
							   <td class="align-middle">` + val.detail + `</td>
							   <td align="right" class="rowaftertax" data-pr="` + val.purchase_request_id + `">` + val.nominal + `</td>
							   <td align="right" class="rowppn" id="rowppn` + val.purchase_request_id + `">` + val.nominaltax + `</td>
							   <td align="right" class="rowbeforetax" id="rowbeforetax` + val.purchase_request_id + `">` + val.nominalbtax + `</td>
							   <td class="align-middle">
								<button type="button" id="delete_wip" value="` + val.purchase_request_id + `" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							   </td>
							</tr>
						`);
					});
					
					countAll();
				}else{
					$('#data_cost').append(`
						<tr class="text-center">
							<td class="align-middle" colspan="7">There is no data costs in this Purchase Order. You may add from purchase request information above.</td>
						</tr>
					`);
				}
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
					
		$('#modal_cost').modal('toggle');
	}
	
	function updateDelivery(id,code,supplier){
		$('#delivery_id').html("Code " + code + " Supplier " + supplier);
		
		$('#tempDelivery').val(id);
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_product") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
				idpo : id
			},
			beforeSend: function() {
				loadingOpen('.modal-body');
			},
			success: function(response) {
				 
				var no = 1;
				
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
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});


		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_shipment_info") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
            idpo : id
         },
         beforeSend: function() {
			loadingOpen('.modal-body');
         },
         success: function(response) {
			if(response.shipment_list.length > 0) {
				$('#detail_delivery_shipment').empty();
				
				$.each(response.shipment_list, function(i, val) {
					$('#detail_delivery_shipment').append(`
						<tr class="text-center">
						  <td class="align-middle">` + val.po_code + `</td>
						  <td class="align-middle">` + val.shipment_code + `</td>
						  <td class="align-middle">` + val.loading_date + `</td>
						  <td class="align-middle">` + val.departure_date + `</td>
						  <td class="align-middle">` + val.from_port + `</td>
						  <td class="align-middle">` + val.to_port + `</td>
						  <td class="align-middle">` + val.eta + `</td>
						  <td class="align-middle">` + val.note + `</td>
						  <td class="align-middle"><a class="btn bg-info" href="javascript:void(0);" onclick="addTrackingShipment(`+ val.shipment_id+`,`+ val.shipment_code+`)"><i class="icon-truck"></i></a></td>
						  <td class="align-middle">
							<a href="` + val.proof + `" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
						  </td>
						  <td>
							<a href="#step-14" onclick="editShipment(`+ val.shipment_id + `)" class="btn bg-info"><i class="icon-pencil5"></i>
							</a>
						 </td>
						</tr>
					`);
				});
				
			}
			
			loadingClose('.modal-body');
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
					
		$('#modal_delivery').modal('toggle');
	}
	
	function updateWarehouseReceive(id,code,supplier){
		$('#warehouse_info').html("Code " + code + " Supplier " + supplier);
		
		$('#tempWarehouse').val(id);
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_shipment_info") }}',
			type: 'GET',
			 dataType: 'JSON',
			 data: {
				idpo : id
			 },
			 beforeSend: function() {
				loadingOpen('.modal-body');
			 },
			 success: function(response) {
				loadingClose('.modal-body');
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
				loadingClose('.modal-body');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_warehouse_receive") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
            idpo : id
         },
         beforeSend: function() {
			loadingOpen('.modal-body');
         },
         success: function(response) {
			if(response.length > 0) {
				$('#detail_warehouse_receive').empty();
				
				$.each(response, function(i, val) {
					$('#detail_warehouse_receive').append(`
						<tr class="text-center">
						  <td class="align-middle">` + val.po_no + `</td>
						  <td class="align-middle">` + val.shipment_no + `</td>
						  <td class="align-middle">` + val.no + `</td>
						  <td class="align-middle">` + val.warehouse + `</td>
						  <td class="align-middle">` + val.person + `</td>
						  <td class="align-middle">` + val.date + `</td>
						  <td class="align-middle">
							<a href="` + val.proof + `" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
						  </td>
						  <td class="align-middle">
							<a onclick="openLink('` + val.link + `')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
						  </td>
						  <td>
							<a href="#warehouse-tab1" onclick="editWarehouse(`+ val.id +`)" class="btn bg-info"><i class="icon-pencil5"></i><a>
						</td>
						</tr>
					`);
				});
				
			}
			
			loadingClose('.modal-body');
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
					
		$('#modal_warehouse').modal('toggle');
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
            loadingOpen('.modal-body');
         },
         success: function(response) {
            loadingClose('.modal-body');
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
            loadingClose('.modal-body');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function createProforma() {
		$.ajax({
		 url: '{{ url("admin/inventory/purchase/create_proforma") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_proforma')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_proforma').hide();
			$('#validation_content_proforma').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
				loadDataTable();
				$('#modal_proforma').modal('toggle');
				notif('success', 'bg-success', response.message);
			} else if(response.status == 422) {
			   $('#validation_alert_proforma').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_proforma').append(`
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
	
	function createBill() {
		$.ajax({
		 url: '{{ url("admin/inventory/purchase/create_bill") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_bill')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_bill').hide();
			$('#validation_content_bill').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
				loadDataTable();
				$('#modal_bill').modal('toggle');
				notif('success', 'bg-success', response.message);
			} else if(response.status == 422) {
			   $('#validation_alert_bill').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_bill').append(`
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
	
	function createProduction() {
		$.ajax({
		 url: '{{ url("admin/inventory/purchase/create_production") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_production')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_production').hide();
			$('#validation_content_production').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
				loadDataTable();
				$('#modal_production').modal('toggle');
				notif('success', 'bg-success', response.message);
			} else if(response.status == 422) {
			   $('#validation_alert_production').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_production').append(`
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
	
	function createDelivery() {
		$.ajax({
		 url: '{{ url("admin/inventory/purchase/create_delivery") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_delivery')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_delivery').hide();
			$('#validation_content_delivery').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
				loadDataTable();
				$('#modal_delivery').modal('toggle');
				notif('success', 'bg-success', response.message);
			} else if(response.status == 422) {
			   $('#validation_alert_delivery').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_delivery').append(`
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
	

	function createCost() {
		
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Warning?!! Are sure you want to save?</h6><label>This cost will create new price in IDR and replace current Purchase Order price each product recpectively.</label>',
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
					 url: '{{ url("admin/inventory/purchase/create_cost") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: new FormData($('#form_data_cost')[0]),
					 contentType: false,
					 processData: false,
					 cache: true,
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						$('#validation_alert_cost').hide();
						$('#validation_content_cost').html('');
						loadingOpen('.modal-content');
					 },
					 success: function(response) {
						loadingClose('.modal-content');
						if(response.status == 200) {
							loadDataTable();
							$('#modal_cost').modal('toggle');
							notyConfirm.close();
							notif('success', 'bg-success', response.message);
						} else if(response.status == 422) {
						   $('#validation_alert_cost').show();
						   $('.modal-body').scrollTop(0);
						   notif('warning', 'bg-warning', 'Validation');
						   
						   $.each(response.error, function(i, val) {
							  $.each(val, function(i, val) {
								 $('#validation_content_cost').append(`
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
	}

	function createWarehouse() {
		$.ajax({
		 url: '{{ url("admin/inventory/purchase/create_warehouse") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: new FormData($('#form_data_warehouse')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_warehouse').hide();
			$('#validation_content_warehouse').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
				loadDataTable();
				$('#modal_warehouse').modal('toggle');
				notif('success', 'bg-success', response.message);
			} else if(response.status == 422) {
			   $('#validation_alert_warehouse').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_warehouse').append(`
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
	
	function updateSampleOrder(id){
		$.ajax({
			url: '{{ url("admin/inventory/purchase/get_warehouse") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
				id : id
			},
			beforeSend: function() {
				loadingOpen('.modal-body');
			},
			success: function(response) {
				loadingClose('.modal-body');
				if(response.length > 0) {
					$('#warehouse_receive_id').empty();
					$('#warehouse_receive_id').append(`
							<option value="">-- Choose One --</option>
						`);
					$.each(response, function(i, val) {
						$('#warehouse_receive_id').append(`
							<option value="` + val.id + `">` + val.name + `</option>
						`);
					});
				}
			},
			error: function() {
				loadingClose('.modal-body');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
		
		$('#modal_sample').modal('toggle');
	}
	
	function getWarehouseReceive(id){
		$.ajax({
			url: '{{ url("admin/inventory/purchase/get_warehouse") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
				id : id
			},
			beforeSend: function() {
				loadingOpen('.modal-body');
			},
			success: function(response) {
				loadingClose('.modal-body');
				if(response.length > 0) {
					$('#project_warehouse_id').empty();
					$('#project_warehouse_id').append(`
							<option value="">--Choose this if from purchase bill--</option>
						`);
					$.each(response, function(i, val) {
						$('#project_warehouse_id').append(`
							<option value="` + val.id + `">` + val.name + `</option>
						`);
					});
				}
			},
			error: function() {
				loadingClose('.modal-body');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
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


	function editWarehouse(id){
		$('.nav-tabs a[href="#warehouse-tab1"]').tab('show');
		$('#temp_warehouse_id').val(id);
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_warehouse_edit") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 success: function(response) {
				
				if(response){
		
					$('#form_data_warehouse #person').val(response.main.person);
					$('#form_data_warehouse #date_receive').val(response.main.date_receive);
					$('#form_data_warehouse #warehouse_id').empty();
					$('#form_data_warehouse #warehouse_id').append(`
						<option value="` + response.main.warehouse_id + `">` + response.main.warehouse_name + `</option>
					`);
					
					setTimeout(function(){
						$('#form_data_warehouse #shipment_id').val(response.main.shipment_id);
						
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
						
					}, 1500);
					
				}
			 },
			 error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

	function editShipment(id){
		$('#temp_shipment_id').val(id);
		$('.nav-tabs a[href="#delivery-tab1"]').tab('show');


	

		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_shipment_edit") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 success: function(response) {
				
				if(response){	
					$('#temp_pos_id').removeClass('d-none');
					$('#pos_id').empty();
					$('#pos_id').append('<option value="">-- Empty --</option>');
					$('#pos_id').append(`
						<option value="` + response.main.project_purchase_id + `">` + response.po_code + `</option>
					`);
					$('#pos_id').val(response.main.project_purchase_id).trigger('select2.change');

					$('#form_data_delivery #shipment_code').val(response.main.shipment_code);
					$('#form_data_delivery #loading_date').val(response.main.loading_date);
					$('#form_data_delivery #departure_date').val(response.main.departure_date);
					$('#form_data_delivery #from_port').val(response.main.from_port);
					$('#form_data_delivery #to_port').val(response.main.to_port);
					$('#form_data_delivery #eta').val(response.main.eta);
					$('#form_data_delivery #delivery_method').val(response.main.delivery_method);
					$('#form_data_delivery #note').val(response.main.note)
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
							
						}
					}, 1500);
					
				}
			 },
			 error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
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

		
	function getPurchaseProduct(elemen,idpo){
		var elemen = elemen.getAttribute('name');
		$.ajax({
		 url: '{{ url("admin/purchase_order/project/get_purchase_product") }}',
		 type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
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
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}

	function getSalesProduct(idprojectsale) {
		$.ajax({
		 url: '{{ url("admin/purchase_order/project/get_sales_product") }}',
		 type: 'GET',
         dataType: 'JSON',
         data: {
            idprojectsale : idprojectsale
         },
         beforeSend: function() {
			loadingOpen('.modal-body');
         },
         success: function(response) {
			loadingClose('.modal-body');
			if(response.length > 0) {
				$('#data_purchase').empty();
				
				$.each(response, function(i, val) {
					var unit = val.unitraw;
					console.log(unit);
							if(unit == '2' || unit == '3'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="2" selected>Box</option>
											<option value="1">Pcs</option>   
											<option value="4">Meter (Custom)</option>
										</select>
									 </td>
								`;
							}else if(unit == '1'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="1" selected>Pcs</option>
											<option value="2">Box</option>
											<option value="4">Meter (Custom)</option>
										</select>
									 </td>
								`;
							}else if(unit == '4'){
								var htmlUnit = `
									<td class="align-middle">
										<select name="product_unit[]" class="custom-select" required>
											<option value="4" selected>Meter (Custom)</option>
											<option value="1">Pcs</option>
											<option value="2">Box</option>
										</select>
									 </td>
								`;
							}
					$('#data_purchase').append(`
						<tr class="text-center rowproductsale purchaseproductdata` + val.product_id + `" data-m2="` + val.unitother + `">
							<input type="hidden" name="product_id[]" value="` + val.product_id + `">
							<input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
							<td class="align-middle">` + val.product_name + `</td>
							<td class="align-middle" width="10%">
								<input type="number" name="product_qty[]" id="purchaseproductqty` + val.product_id + `" class="form-control" placeholder="0" value="` + val.qty + `" required onkeyup="countTotalPurchase(this,'` + val.product_id + `');">
							</td>
							` + htmlUnit + `
							<td class="align-middle">
							<input type="text" name="product_price[]" id="product_price` + val.product_id + `" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);countTotalPurchase(this,'`+ val.product_id +`');convertPriceBeforeTax(this,'`+ val.product_id +`')" value="` + val.latestpurchaseprice + `">
							</td>
							<td class="align-middle">
							<textarea class="form-control" rows="1" name="product_remark[]">` + val.shading + `</textarea>
							</td>
							<td class="align-middle">
							<button type="button" id="delete_data_product_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							</td>
						</tr>
					`);
				
				});
			}
			
         },
         error: function() {
			loadingClose('.modal-body');
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}

		
	function getSalesInfo(idprojectsale) {
		$.ajax({
		 url: '{{ url("admin/purchase_order/project/get_sales_info") }}',
		 type: 'GET',
         dataType: 'JSON',
         data: {
            idprojectsale : idprojectsale
         },
         beforeSend: function() {
			loadingOpen('.modal-body');
         },
         success: function(response) {
			loadingClose('.modal-body');
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
         },
         error: function() {
			loadingClose('.modal-body');
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}

	function getWipCoa(){
		var wip_coa = $('#cost_wip').val();

		if(wip_coa){
			$.ajax({
			url: '{{ url("admin/inventory/purchase/get_wip_coa") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
				wip_coa : wip_coa,
			},
			beforeSend: function() {
				loadingOpen('.modal-body');
			},
			success: function(response) {
				loadingClose('.modal-body');
				$('#data_cost').empty();
				if(response.data.length > 0) {
					$.each(response.data, function (i, val) { 
						var nominal = val.nominal;
						var detail = val.detail;
						var to = val.to;
						var id = val.id;
						var persenppn = $('#cost_ppn').val() == '1' ? $('#cost_ppn_nominal').val() : 0;
						var exists = false;
						
						$(".rowcost").each(function() {
							if($(this).data('pr') == id){
								exists = true;
							}
						});
						
						if(exists == false){
							var ppnnominal = Math.round(parseFloat(nominal) / ((100 + parseFloat(persenppn.toString())) / 100));
							var ppn = Math.round(parseFloat(nominal) - parseFloat(ppnnominal.toFixed(0)));
							
							var no = $('.rowcost').length;
							
							if(no == 0){
								$('#data_cost').empty();
							}
							
							$('#data_cost').append(`
								<tr class="text-center rowcost row` + id + `" data-pr="` + id + `">
								<input type="hidden" name="purchase_request_id[]" value="` + id + `">
								<input type="hidden" name="purchase_request_nominal[]" value="` + nominal + `">
								<input type="hidden" name="purchase_is_ppn[]" value="` + $('#cost_ppn').val() + `" class="rowisppn">
								<input type="hidden" name="purchase_percent_ppn[]" value="` + $('#cost_ppn_nominal').val() + `" class="rowppnnominal">
								<td class="align-middle">` + (no + 1) + `</td>
								<td class="align-middle">` + to + `</td>
								<td class="align-middle">` + detail + `</td>
								<td align="right" class="rowaftertax" data-pr="` + id + `">` + nominal + `</td>
								<td align="right" class="rowppn" id="rowppn` + id + `">` + (ppn ? formatRupiahIni(ppn.toFixed(0)) : 0) + `</td>
								<td align="right" class="rowbeforetax" id="rowbeforetax` + id + `">` + (ppnnominal ? formatRupiahIni(ppnnominal.toFixed(0)) : nominal) + `</td>
								<td class="align-middle">
									<button type="button" id="delete_wip_unsave" value="` + id + `" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								</td>
								</tr>
							`);
							
							countAll();
							
							$('#purchase_request_id').val(null).trigger('change');
							$('#purchase_request_nominal').val('0');
						}else{
							swalInit.fire('Hayo', 'This Purchase Request already in Cost list.', 'error');
						}
					});
				}else{
					swalInit.fire('Info !', 'No WIP Allocated to this COA yet', 'info');
				}
			},
			error: function() {
				loadingClose('.modal-body');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
			});
		}else{
			swalInit.fire('Info !', 'Please select COA First', 'info');
		}
	}
</script>