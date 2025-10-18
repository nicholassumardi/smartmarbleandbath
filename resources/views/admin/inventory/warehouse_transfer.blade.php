<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Warehouse Exchange / Correction</span>
				</h4>
			</div>
			<div class="header-elements">
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
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Inventory</a>
					<span class="breadcrumb-item active">Warehouse Changes</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Warehouse Changes</h5>
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
							<th>WT.Number</th>
							<th>Note</th>
							<th>From</th>
							<th>To</th>
							<th>Proof</th>
							<th>Status Sample</th>
							<th>Action</th>
							<th>Print</th>
						 </tr>
					  </thead>
				   </table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Warehouse Exchanges/Corrections</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data">
				   <div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
				   </div>
				   <div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Note :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="temp" name="temp">
								<textarea name="note" id="note" class="form-control" placeholder="Enter note here" rows="1"></textarea>
							</div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input class="form-control" type="date" name="date" id="date" value="{{ date('Y-m-d') }}">
							 </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <select name="branch" id="branch" class="custom-select">
								@foreach (DB::table('company_entities')->get() as $company)
									<option value="{{$company->id}}">{{$company->name}}</option>
								@endforeach
							  </select>
						   </div>
						</div>
						<div class="col-md-3 input_from_warehouse">
							 <div class="form-group">
								<label>Origin Warehouse :<sup class="text-danger">*</sup></label>
								<select name="from_warehouse_id" id="from_warehouse_id" onchange="getProductOriginWarehouse()"></select>
							 </div>
						</div>
						<div class="col-md-3 input_to_warehouse">
							 <div class="form-group">
								<label>To Warehouse :<sup class="text-danger">*</sup></label>
								<select name="to_warehouse_id" id="to_warehouse_id"></select>
							 </div>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<div class="form-group">
								<label>Proof of Transfer :</label>
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group"><hr></div>
					<h3>Additional Options</h3>
					<div class="row justify-content-center">
						<div class="col-md-4">
							<div class="form-group">
								<span class="badge d-block badge-danger form-text">ONLY FOR STARTING BALANCE INVENTORY.</span>
								<div class="form-check mt-2">
									<label class="form-check-label">
										Click this!
										<input type="checkbox" class="form-check-input" style="margin-top: 0.2rem;margin-left: 10px;" name="for_starting" id="for_starting" value="1">
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<span class="badge d-block badge-danger form-text">ONLY FOR DELIVERY OUTSIDE PROJECT WITHOUT PAYMENT (SAMPLE).</span>
								<div class="form-check mt-2">
									<label class="form-check-label">
										Click this!
										<input type="checkbox" class="form-check-input" style="margin-top: 0.2rem;margin-left: 10px;" name="for_customer" id="for_customer" value="1">
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<span class="badge d-block badge-danger form-text">ONLY FOR STOCK ADJUSTMENT/OPNAME (CORRECTION).</span>
								<div class="form-check mt-2">
									<label class="form-check-label">
										Click this!
										<input type="checkbox" class="form-check-input" style="margin-top: 0.2rem;margin-left: 10px;" name="for_correction" id="for_correction" value="1">
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<span class="badge d-block badge-danger form-text">ONLY FOR BROKEN PRODUCT TO COST.</span>
								<div class="form-check mt-2">
									<label class="form-check-label">
										Click this!
										<input type="checkbox" class="form-check-input" style="margin-top: 0.2rem;margin-left: 10px;" name="for_broken" id="for_broken" value="1">
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<span class="badge d-block badge-danger form-text">OUT FOR ANOTHER BRANCH.</span>
								<div class="form-check mt-2">
									<label class="form-check-label">
										Click this!
										<input type="checkbox" class="form-check-input" style="margin-top: 0.2rem;margin-left: 10px;" name="for_out_transfer" id="for_out_transfer" value="1">
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<span class="badge d-block badge-danger form-text">IN FROM ANOTHER BRANCH.</span>
								<div class="form-check mt-2">
									<label class="form-check-label">
										Click this!
										<input type="checkbox" class="form-check-input" style="margin-top: 0.2rem;margin-left: 10px;" name="for_in_transfer" id="for_in_transfer" value="1">
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group d-none customer_input"><hr></div>
					<h3 class="customer_input d-none">Receiver Informations</h3>
					<div class="row justify-content-center d-none customer_input">
						<div class="col-md-3">
							<div class="form-group">
								<label>Purchase :</label>
								<select name="project_purchase_id" id="project_purchase_id" onchange="getPurchaseProduct(this.value)"></select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Customer :<sup class="text-danger">*</sup></label>
								<select name="customer_id" id="customer_id"></select>
							 </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>PIC :<sup class="text-danger">*</sup></label>
								<input type="text" name="pic" id="pic" class="form-control" placeholder="Enter pic here with phone number">
							 </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Address :<sup class="text-danger">*</sup></label>
								<textarea name="address" id="address" class="form-control" placeholder="Enter address here" rows="1"></textarea>
							</div>
						</div>
					</div>
					<div class="form-group d-none starting_input"><hr></div>
					<h3 class="starting_input d-none">Nominal Total Price<!-- (Include All Costs Related to Purchase - Before PPN) --></h3>
					<div class="row justify-content-center d-none starting_input">
						<div class="col-md-4">
							<div class="form-group">
								<input type="text" name="nominal" id="nominal" class="form-control" placeholder="0" onkeyup="formatRupiah(this)" readonly>
							 </div>
						</div>
					</div>
					<div class="form-group"><hr></div>
					<h3>Details Product To Be Transferred</h3>
					<span class="badge d-block badge-danger form-text">CHOOSE WAREHOUSE ORIGIN FIRST</span>
					<div class="row">
					   <div class="col-md-8 mt-3 mx-auto">
						  <div class="form-group">
							 <select name="product_id" id="product_id" class="select2">
								<option value="">--Select Warehouse Origin First--</option>
							 </select>
						  </div>
					   </div>
					   <div class="col-md-8 mx-auto">
						  <div class="form-group">
							 <button type="button" onclick="addProduct()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add</button>
						  </div>
					   </div>
					</div>
					<div class="form-group">
					   <div class="table-responsive">
						  <table class="table table-bordered table-striped">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th>Product</th>
								   <th>Shading</th>
								   <th>Stock</th>
								   <th>Qty</th>
								   <th>Unit</th>
								   <th class="starting_input d-none">Total Price</th>
								   <th>Delete</th>
								</tr>
							 </thead>
							 <tbody id="data_transfer">
								
							 </tbody>
						  </table>
					   </div>
					</div>
				</form>
			</div>
			<div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Total : <span class="badge badge-success" id="totalHelper">0</span>
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-danger" id="btn_cancel" onclick="cancel()" style="display:none;"><i class="icon-cross3"></i> Cancel</button>
				<button type="button" class="btn bg-warning" id="btn_update" onclick="update()" style="display:none;"><i class="icon-pencil7"></i> Save</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
		</div>
	</div>
<script>
	$(function() {
		loadDataTable();
		
		$('#for_customer').click(function(){
			if($(this).prop("checked") == true){
				$('.input_to_warehouse').addClass('d-none');
				$('.customer_input').removeClass('d-none');
				$('#for_starting,#for_correction,#for_out_transfer,#for_in_transfer,#for_broken').prop('disabled',true);
			}else{
				$('.input_to_warehouse').removeClass('d-none');
				$('.customer_input').addClass('d-none');
				$('#for_starting,#for_correction,#for_out_transfer,#for_in_transfer,#for_broken').prop('disabled',false);
			}

			$('#from_warehouse_id').val(null).trigger('change');
			$('#to_warehouse_id').val(null).trigger('change');
		});
		
		$('#for_correction').click(function(){
			if($(this).prop("checked") == true){
				$('.input_to_warehouse').addClass('d-none');
				$('#for_starting,#for_customer,#for_out_transfer,#for_in_transfer,#for_broken').prop('disabled',true);
			}else{
				$('.input_to_warehouse').removeClass('d-none');
				$('.customer_input').addClass('d-none');
				$('#for_starting,#for_customer,#for_out_transfer,#for_in_transfer,#for_broken').prop('disabled',false);
			}
			
			$('#from_warehouse_id').val(null).trigger('change');
			$('#to_warehouse_id').val(null).trigger('change');
		});
		
		$('#for_broken').click(function(){
			if($(this).prop("checked") == true){
				$('.input_to_warehouse').addClass('d-none');
				$('#for_correction,#for_starting,#for_customer,#for_out_transfer,#for_in_transfer').prop('disabled',true);
			}else{
				$('.input_to_warehouse').removeClass('d-none');
				$('.customer_input').addClass('d-none');
				$('#for_correction,#for_starting,#for_customer,#for_out_transfer,#for_in_transfer').prop('disabled',false);
			}
			
			$('#from_warehouse_id').val(null).trigger('change');
			$('#to_warehouse_id').val(null).trigger('change');
		});
		
		$('#for_out_transfer').click(function(){
			if($(this).prop("checked") == true){
				$('.input_to_warehouse').addClass('d-none');
				$('#for_starting,#for_customer,#for_correction,#for_in_transfer,#for_broken').prop('disabled',true);
			}else{
				$('.input_to_warehouse').removeClass('d-none');
				$('#for_starting,#for_customer,#for_correction,#for_in_transfer,#for_broken').prop('disabled',false);
			}
			
			$('#from_warehouse_id').val(null).trigger('change');
			$('#to_warehouse_id').val(null).trigger('change');
		});
		
		$('#for_starting').click(function(){
			if($(this).prop("checked") == true){
				$('.input_from_warehouse').addClass('d-none');
				$('.starting_input').removeClass('d-none');
				$('#for_out_transfer,#for_customer,#for_correction,#for_in_transfer,#for_broken').prop('disabled',true);
			}else{
				$('.input_from_warehouse').removeClass('d-none');
				$('.starting_input').addClass('d-none');
				$('#for_out_transfer,#for_customer,#for_correction,#for_in_transfer,#for_broken').prop('disabled',false);
			}
			
			$('#from_warehouse_id').val(null).trigger('change');
			$('#to_warehouse_id').val(null).trigger('change');
			
			getAllProduct();
		});
		
		$('#for_in_transfer').click(function(){
			if($(this).prop("checked") == true){
				$('.input_from_warehouse').addClass('d-none');
				$('.starting_input').removeClass('d-none');
				$('#for_out_transfer,#for_customer,#for_correction,#for_starting,#for_broken').prop('disabled',true);
			}else{
				$('.input_from_warehouse').removeClass('d-none');
				$('.starting_input').addClass('d-none');
				$('#for_out_transfer,#for_customer,#for_correction,#for_starting,#for_broken').prop('disabled',false);
			}
			
			$('#from_warehouse_id').val(null).trigger('change');
			$('#to_warehouse_id').val(null).trigger('change');
			
			getAllProduct();
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
		
		$('#datatable_serverside tbody').on('change', '.status-sample', function() {
			var id = $(this).data('id'), thisoption = $(this), newVal = $(this).val(), oldVal = $(this).data('old');
			if(newVal !== ''){
				if(confirm('Are you sure you want to change this status sample?')){
					$.ajax({
						 url: '{{ url("admin/inventory/transfer/change_status_sample") }}',
						 type: 'POST',
						 dataType: 'JSON',
						 data: { id : id, val : newVal },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#datatable_serverside');
						 },
						 success: function(response) {
							loadingClose('#datatable_serverside');
							if(response.status == 200) {
							   $('#datatable_serverside').DataTable().ajax.reload(null, false);
							   notif('success', 'bg-success', 'Success bro sis!');
							}
						 },
						 error: function() {
							loadingClose('#datatable_serverside');
							swalInit.fire({
							   title: 'Server Error',
							   text: 'Please contact developer',
							   type: 'error'
							});
						 }
					});
				}else{
					thisoption.val(oldVal);
					return;
				}
			}else{
				thisoption.val(oldVal);
				return;
				notif('warning', 'bg-warning', 'Please choose valid status!');
			}
		});
		
		select2ServerSide('#from_warehouse_id,#to_warehouse_id', '{{ url("admin/select2/warehouse") }}');
		select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
		select2ServerSide('#project_purchase_id', '{{ url("admin/select2/project_purchase") }}');
		
		$('.sidebar-main-toggle').click();
		
		$('#data_transfer').on('click', '#delete_data_product', function() {
			$(this).closest('tr').remove();
		});
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#form_data')[0].reset();
			$('#temp').val('');
			$('#from_warehouse_id').val(null).trigger('change');
			$('#to_warehouse_id').val(null).trigger('change');
		});
		
	});
	
	function toShow() {
      $('#data_transfer').html('');
	  $('#temp').val('');
      $('#modal_form').modal('show');
      $('#validation_alert').hide();
      $('#validation_content').html('');
   }
	
	function show(id) {
      toShow();
	  $('#temp').val(id);
      $.ajax({
         url: '{{ url("admin/inventory/transfer/show") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
         beforeSend: function() {
            loadingOpen('.modal-body');
         },
         success: function(response) {
            loadingClose('.modal-body');
			
			$('#note').val(response.data.note);
			$('#date').val(response.data.date);
			$('#branch').val(response.data.branch);
			$('#from_warehouse_id').val(null).trigger('change');
			$('#from_warehouse_id').append(`
				<option value="` + response.data.from_warehouse_id + `">` + response.data.from_warehouse_name + `</option>
			`);
			$('#to_warehouse_id').val(null).trigger('change');
			$('#to_warehouse_id').append(`
				<option value="` + response.data.to_warehouse_id + `">` + response.data.to_warehouse_name + `</option>
			`);
			
			$('#for_customer').prop('checked', false);
			$('#for_starting').prop('checked', false);
			$('#for_correction').prop('checked', false);
			$('.customer_input').addClass('d-none');
			$('.input_from_warehouse').removeClass('d-none');
			$('.starting_input').addClass('d-none');
			
			if(response.data.for_starting){
				$('#for_starting').prop('checked', true);
				$('.input_from_warehouse').addClass('d-none');
				$('.starting_input').removeClass('d-none');
			}
			
			if(response.data.for_in_transfer){
				$('#for_starting').prop('checked', true);
				$('.input_from_warehouse').addClass('d-none');
				$('.starting_input').removeClass('d-none');
			}
			
			if(response.data.for_customer){
				$('#for_customer').prop('checked', true);
				$('.input_to_warehouse').addClass('d-none');
				$('.customer_input').removeClass('d-none');
			}
			
			if(response.data.for_correction){
				$('#for_correction').prop('checked', true);
				$('.input_to_warehouse').addClass('d-none');
			}
			
			if(response.data.for_broken){
				$('#for_broken').prop('checked', true);
				$('.input_to_warehouse').addClass('d-none');
			}
			
			if(response.purchase !== '0'){
				$('#project_purchase_id').empty();
				$('#project_purchase_id').append(`
					<option value="` + response.purchase.id + `">` + response.purchase.code + `</option>
				`);
			}
			
			if(response.detail.length > 0){
				$.each(response.detail, function(i, val) {
					if(response.data.for_starting || response.data.for_in_transfer){
						$('#data_transfer').append(`
							<tr class="text-center rowproduct">
								 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
								 <td class="align-middle">` + val.product_name + `</td>
								 <td class="align-middle">
									<input type="text" name="product_shading[]" class="form-control" placeholder="Shading" required value="` + val.code + `">
								 </td>
								 <td class="align-middle">
									0
								 </td>
								 <td class="align-middle">
									<input type="text" name="product_qty[]" class="form-control" placeholder="0" required value="` + val.qty + `">
								 </td>
								 <td class="align-middle">
									<select name="product_unit[]" class="custom-select" required>
									   <option value="1" ` + ((val.unit == '1') ? 'selected' : '' ) + `>Pcs</option>   
									   <option value="2" ` + ((val.unit == '2') ? 'selected' : '' ) + `>Box</option>   
									   <option value="4" ` + ((val.unit == '4') ? 'selected' : '' ) + `>Meter (Custom)</option>   
									</select>
								 </td>
								 <td class="align-middle">
									<input type="text" name="product_price[]" class="form-control" placeholder="0" value="` + val.price.toLocaleString("id") + `" onkeyup="formatRupiah(this);count();">
								 </td>								 
								 <td class="align-middle">
									<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								 </td>
							</tr>
						`);
					}else{
						$('#data_transfer').append(`
							<tr class="text-center rowproduct">
								 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
								 <td class="align-middle">` + val.product_name + `</td>
								 <td class="align-middle">
									<input type="text" name="product_shading[]" class="form-control" placeholder="Shading" required value="` + val.code + `">
								 </td>
								 <td class="align-middle">
									0
								 </td>
								 <td class="align-middle">
									<input type="text" name="product_qty[]" class="form-control" placeholder="0" required value="` + val.qty + `">
								 </td>
								 <td class="align-middle">
									<select name="product_unit[]" class="custom-select" required>
									   <option value="1" ` + ((val.unit == '1') ? 'selected' : '' ) + `>Pcs</option>   
									   <option value="2" ` + ((val.unit == '2') ? 'selected' : '' ) + `>Box</option>   
									   <option value="4" ` + ((val.unit == '4') ? 'selected' : '' ) + `>Meter (Custom)</option>   
									</select>
								 </td>
								 <td class="align-middle">
									<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								 </td>
							</tr>
						`);
					}
				});
			}
			
			if(response.data.for_starting || response.data.for_in_transfer){
				count();
			}
         },
         error: function() {
            loadingClose('.modal-body');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/inventory/transfer/row_detail") }}',
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
	
	function getProductOriginWarehouse(){
		$('#product_id').empty();
		$('#product_id').val(null).trigger('change');
		
		if($('#from_warehouse_id').val() !== ''){
			$('#product_id').select2({
				ajax: {
					url: '{{ url("admin/select2/stock") }}',
					dataType: 'JSON',
					delay: 250,
					data: function (params){
						return {
							search: params.term,
							warehouse: $('#from_warehouse_id').val(),
							branch: $('#branch').val(),
						}
					},
					processResults: function (data, params) {
						return {
							results: data.items
						};
					},
					cache: true
				},
				placeholder: 'Search for a product',
				/* minimumInputLength: 2 */
			});
		}else{
			notif('error', 'bg-danger', 'Please choose warehouse origin first!');
		}
	}
	
	function getAllProduct(){
		$('#product_id').empty();
		$('#product_id').val(null).trigger('change');
		
		if($('#to_warehouse_id').val() !== ''){
			select2ServerSide('#product_id', '{{ url("admin/select2/product") }}');
		}
	}
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/inventory/transfer/datatable") }}',
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
			{ name: 'code', className: 'text-center align-middle' },
            { name: 'note', className: 'text-center align-middle' },
			{ name: 'warehousefrom', className: 'text-center align-middle' },
            { name: 'warehouseto', className: 'text-center align-middle' },
            { name: 'proof', className: 'text-center align-middle' },
			{ name: 'sample', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'print', searchable: false, orderable: false, className: 'text-center align-middle' }
		]
      }); 
	}
	
	function success() {
		reset();
		$('#modal_form').modal('hide');
		$('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function cancel() {
		reset();
		$('#modal_form').modal('hide');
		$('#btn_create').show();
		$('#btn_update').hide();
		$('#btn_cancel').hide();
	}
	
	function reset() {
      $('#form_data').trigger('reset');
      $('#data_transfer').html('');
      $('#for_customer').prop('checked', false);
	  $('#for_starting').prop('checked', false);
      $('#validation_alert').hide();
      $('#validation_content').html('');
	  $('.uniform-checker > span').removeClass('checked');
	}
	
	function create() {
	  $.ajax({
		 url: '{{ url("admin/inventory/transfer/create") }}',
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
	
	function addProduct() {
		var id = $('#product_id');

		if(id.val()) {
			$.ajax({
				url: '{{ url("admin/inventory/transfer/get_product") }}',
				type: 'GET',
				dataType: 'JSON',
				data: {
				   id: id.val(),
				   warehouse : $('#from_warehouse_id').val(),
				   branch : $('#branch').val()
				},
				beforeSend: function() {
				   loadingOpen('.modal-body');
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
						if($('#for_starting').prop("checked") == true || $('#for_in_transfer').prop("checked") == true ){
							$('#data_transfer').append(`
							  <tr class="text-center rowproduct">
								 <input type="hidden" name="product_id[]" value="` + response.id + `">
								 <td class="align-middle">` + response.product + `</td>
								 <td class="align-middle">
									<input type="text" name="product_shading[]" class="form-control" placeholder="Shading" required>
								 </td>
								 <td class="align-middle">
									` + response.stock + `
								 </td>
								 <td class="align-middle">
									<input type="text" name="product_qty[]" class="form-control" placeholder="0" required value="0">
								 </td>
								 <td class="align-middle">
									<select name="product_unit[]" class="custom-select" required>
									   <option value="1">Pcs</option>   
									   <option value="2">Box</option>   
									   <option value="4">Meter(Custom)</option>   
									</select>
								 </td>
								 <td class="align-middle">
									<input type="text" name="product_price[]" class="form-control" placeholder="0" value="0" onkeyup="formatRupiah(this);count();">
								 </td>								 
								 <td class="align-middle">
									<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								 </td>
							  </tr>
							`);
						}else{
							$('#data_transfer').append(`
							  <tr class="text-center rowproduct">
								 <input type="hidden" name="product_id[]" value="` + response.id + `">
								 <td class="align-middle">` + response.product + `</td>
								 <td class="align-middle">
									<input type="text" name="product_shading[]" class="form-control" placeholder="Shading" required>
								 </td>
								 <td class="align-middle">
									` + response.stock + `
								 </td>
								 <td class="align-middle">
									<input type="text" name="product_qty[]" class="form-control" placeholder="0" required value="` + response.stock + `">
								 </td>
								 <td class="align-middle">
									<select name="product_unit[]" class="custom-select" required>
									   <option value="1">Pcs</option>   
									   <option value="2">Box</option>   
									   <option value="4">Meter(Custom)</option>   
									</select>
								 </td>
								 <td class="align-middle">
									<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								 </td>
							  </tr>
							`);
						}
					}else{
						swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
					}
					
					loadingClose('.modal-body');
				},
				error: function() {
				   loadingClose('.modal-body');
				   swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			 });
		} else {
			swalInit.fire('Ooppsss!', 'Please select a product', 'info');
		}
	}
	
	function getPurchaseProduct(val) {
		if(val) {
			$.ajax({
				url: '{{ url("admin/inventory/transfer/get_purchase_product") }}',
				type: 'GET',
				dataType: 'JSON',
				data: {
				   id: val
				},
				beforeSend: function() {
				   loadingOpen('.modal-body');
				},
				success: function(response) {
					if(response.status == '200'){
						
						$.each(response.products, function(i, val) {
							$('#data_transfer').append(`
							  <tr class="text-center rowproduct">
								 <input type="hidden" name="product_id[]" value="` + val.id + `">
								 <td class="align-middle">` + val.product + `</td>
								 <td class="align-middle">
									<input type="text" name="product_shading[]" class="form-control" placeholder="Shading" required>
								 </td>
								 <td class="align-middle">
									-
								 </td>
								 <td class="align-middle">
									<input type="text" name="product_qty[]" class="form-control" placeholder="0" required value="` + val.qty + `">
								 </td>
								 <td class="align-middle">
									<select name="product_unit[]" class="custom-select" required>
									   <option value="1">Pcs</option>   
									   <option value="2">Box</option>   
									   <option value="4">Meter(Custom)</option>   
									</select>
								 </td>
								 <td class="align-middle">
									<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								 </td>
							  </tr>
							`);
						});
						
					}else{
						notif('error', 'bg-danger', response.message);
					}
					loadingClose('.modal-body');
				},
				error: function() {
				   loadingClose('.modal-body');
				   swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			 });
		} else {
			swalInit.fire('Ooppsss!', 'Please select a product', 'info');
		}
	}
	
	function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
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
                  url: '{{ url("admin/inventory/transfer/destroy") }}',
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
	
	function count(){
		var total = 0;
		
		$('input[name^="product_price"]').each(function(){
			total = total + parseFloat($(this).val().replace(".", "").replace(".", "").replace(".", "").replace(",","."));
		});
		
		$('#nominal').val(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
		$('#totalHelper').html(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
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
</script>