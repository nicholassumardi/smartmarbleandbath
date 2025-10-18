<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">{{ $title }}</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="location.reload()">
						<b><i class="icon-sync"></i></b> Reset
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Sales</a>
					<span class="breadcrumb-item active">Retail Store</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<form id="form_data">
			<div class="alert alert-danger" id="validation_alert" style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
				<ul id="validation_content"></ul>
			</div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title text-center"><b>CUSTOMER INFORMATION</b></h3>
					<hr>
				</div>
				<div class="card-body">
					<h5 class="card-title">Retail / Project Information</h5>
					<div class="row justify-content-center">
						<div class="col-md-4">
							<div class="form-group">
								<label>Retail / Project :<span class="text-danger">*</span></label>
								<select name="type" id="type" class="form-control">
								   <option value="1">Retail</option>
								   <option value="0">Project</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Sales. :<span class="text-danger">*</span></label>
								<select name="user_id" id="user_id"></select>
							</div>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-4">
							<div class="form-group"><hr></div>
							<h5 class="card-title">Choose From Existing Data</h5>
							<div class="row justify-content-center">
								<div class="col-md-12">
									<div class="form-group">
										<label>Customer. :</label>
										<select name="customer_id" id="customer_id"></select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-1 text-center" style="margin:auto !important;font-size:20px;">
							<b>OR</b>
						</div>
						<div class="col-md-7">
							<div class="form-group"><hr></div>
							<h5 class="card-title">Add New Customer</h5>
							<div class="row justify-content-center">
								<div class="col-md-4">
									<div class="form-group">
										<label>Name :<span class="text-danger">*</span></label>
										<input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Phone/WA :<span class="text-danger">*</span></label>
										<input type="text" name="phone" id="phone" class="form-control" placeholder="Ex : 081330091122">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Email :</label>
										<input type="email" name="email" id="email" class="form-control" placeholder="Ex : jaya@gmail.com">
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<h5 class="card-title">Tax & Other Information</h5>
					<div class="row justify-content-center">
						<div class="col-md-4">
							<div class="form-group">
								<label>PPN / Non-PPN :<span class="text-danger">*</span></label>
								<select name="isppn" id="isppn" class="form-control">
								   <option value="1">Yes</option>
								   <option value="0">No</option>
								</select>
							</div>
						</div>
						
						<div class="col-md-4">
						  <div class="form-group">
							 <label>Customer PO :</label>
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
						  </div>
						</div>
						<div class="col-md-4">
						  <div class="form-group">
							<label>Delivery Cost :<sup class="text-danger">* <i>Leave 0 if empty.</i></sup></label>
							<input type="text" name="delivery_cost" id="delivery_cost" class="form-control" placeholder="0" value="0" onkeyup="formatRupiah(this)">
						  </div>
						</div>
						<div class="col-md-4">
						  <div class="form-group">
							<label>Cutting Cost :<sup class="text-danger">* <i>Leave 0 if empty.</i></sup></label>
							<input type="text" name="cutting_cost" id="cutting_cost" class="form-control" placeholder="0" value="0" onkeyup="formatRupiah(this)">
						  </div>
						</div>
						<div class="col-md-4">
						  <div class="form-group">
							<label>Miscellaneous Cost :<sup class="text-danger">* <i>Leave 0 if empty.</i></sup></label>
							<input type="text" name="misc_cost" id="misc_cost" class="form-control" placeholder="0" value="0" onkeyup="formatRupiah(this)">
						  </div>
						</div>
					</div>
					<hr>
					<h5 class="card-title">Mid Fee Information (If Any)</h5>
					<div class="row justify-content-center">
						<div class="col-md-4">
							<div class="form-group">
								<label>Middleman Fee :<sup class="text-danger">*</sup></label>
								<select name="mid_yes_no" id="mid_yes_no" class="custom-select">
								   <option value="0">No</option>   
								   <option value="1">Yes</option>   
								</select>
							</div>
						</div>
						<div class="col-md-4 mid-hide">
							<div class="form-group">
								<label>Type :<sup class="text-danger">*</sup></label>
								<select name="mid_type" id="mid_type" class="custom-select">
								   <option value="1">Percentage</option>   
								   <option value="2">Nominal</option>   
								</select>
							</div>
						</div>
						<div class="col-md-4 mid-hide">
						  <div class="form-group">
							<label>Value :<sup class="text-danger">*</sup></label>
							<input type="text" name="mid_fee" id="mid_fee" class="form-control" placeholder="0" value="0" onkeyup="formatRupiah(this)">
						  </div>
						</div>
						<div class="col-md-4 mid-hide">
						  <div class="form-group">
							<label>Note :<sup class="text-danger">*</sup></label>
							<input type="text" name="mid_note" id="mid_note" class="form-control" placeholder="Note here..."value="-">
						  </div>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<button type="button" class="btn bg-primary" onclick="confirmCustomer()"><i class="icon-arrow-down132"></i> Next</button>
				</div>
			</div>
			<div class="card d-none" id="form-product">
				<div class="card-header">
					<h3 class="card-title text-center"><b>PRODUCTS INFORMATION</b></h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-10">
							<div class="form-group">
								<select name="product_id" id="product_id"></select>
							</div>
						</div>
						<div class="col-md-2">
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
									<th>Area</th>
									<th>Surface</th>
									<th>Unit</th>
									<th>Qty</th>
									<th>Box</th>
									<th>Price</th>
									<th>Total</th>
									<th>Delete</th>
								  </tr>
							   </thead>
							   <tbody id="data_product">
							   </tbody>
							</table>
						</div>
					</div>
					<div class="form-group text-center">
						<label>Discount :<span class="text-danger">*</span></label>
						<input type="text" name="discount" class="form-control" value="0" onkeyup="formatRupiah(this);countGrandtotal();" id="discount">
					</div>
					<div class="form-group text-right">
						<h3>Total : <b id="grandtotal">0</b><br></h3>
						<h3>After Discount : <b id="afterdiscount">0</b><br></h3>
						<h3>PPN : <b id="ppn">0</b><br></h3>
						<h3>Grandtotal : <b id="grandtotalall">0</b></h3>
					</div>
				</div>
				<div class="card-footer text-right">
					<button type="button" class="btn bg-primary" onclick="confirmProduct()"><i class="icon-arrow-down132"></i> Next</button>
				</div>
			</div>
			<div class="card d-none" id="form-delivery">
				<div class="card-header">
					<h3 class="card-title text-center"><b>DELIVERY ADDRESS</b></h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							 <div class="form-group">
								<label>Delivery Address :<sup class="text-danger">*</sup></label>
								<textarea type="text" name="address" id="address" class="form-control" placeholder="Enter address here" rows="1"></textarea>
							</div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
								<label>Country :<sup class="text-danger">*</sup></label>
								<select name="country_id" id="country_id" class="select2">
								   <option value="">-- Choose --</option>
								   @foreach($country as $c)
									  <option value="{{ $c->id }}">{{ $c->name }}</option>
								   @endforeach
								</select>
							 </div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
								<label>City :<sup class="text-danger">*</sup></label>
								<select name="city_id" id="city_id" class="select2">
								   <option value="">-- Choose --</option>
								   @foreach($city as $c)
									  <option value="{{ $c->id }}">{{ $c->name }}</option>
								   @endforeach
								</select>
							 </div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Note :<sup class="text-danger">*</sup></label>
								<textarea type="text" name="note" id="note" class="form-control" placeholder="Enter note here" rows="1">-</textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<button type="button" class="btn bg-primary" onclick="confirmDelivery()"><i class="icon-arrow-down132"></i> Next</button>
				</div>
			</div>
			<div class="card d-none" id="form-payment">
				<div class="card-header">
					<h3 class="card-title text-center"><b>PAYMENT METHOD</b></h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Bank Destination :<sup class="text-danger">*</sup></label>
								<select name="bank_id" id="bank_id" class="select2">
								   <option value="">-- Choose --</option>
								   @foreach($bank as $b)
									  <optgroup label="{{ $b->name }}">
										  @foreach($b->child() as $bc)
											<optgroup label="{{ $bc->name }}">
												@foreach($bc->child() as $bcc)
													@if(in_array($bcc->code,array('1.000.03.01.01','1.000.03.01.03','1.000.03.02.01','1.000.03.02.02','1.000.03.03.05', '1.000.03.03.06')))
													<option value="{{ $bcc->id }}">{{ $bcc->name }}</option>
													@endif
												@endforeach
											</optgroup>
										  @endforeach
									  </optgroup>
								   @endforeach
								</select>
							 </div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Payment Method :<sup class="text-danger">*</sup></label>
								<select name="payment_method" id="payment_method" class="custom-select">
								   <option value="">-- Choose --</option>
								   <option value="1">Cash</option>
								   <option value="2">Credit</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
								<label>Detail Payment :<sup class="text-danger">*</sup></label>
								<select name="detail_payment" id="detail_payment" class="custom-select">
								   <option value="">-- Choose --</option>
								</select>
							 </div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Term Payment :</label>
								<select name="term_payment" id="term_payment" class="custom-select">
								   <option value="0">Default</option>
								   <option value="7">7 Days</option>
								   <option value="14">14 Days</option>
								   <option value="30">30 Days</option>
								   <option value="45">45 Days</option>
								   <option value="60">60 Days</option>
								   <option value="90">90 Days</option>
								   <option value="120">120 Days</option>
								   <option value="180">180 Days</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer text-right">
					<button type="button" class="btn bg-primary" onclick="confirmPayment()"><i class="icon-arrow-down132"></i> Next</button>
				</div>
			</div>
			<div class="card d-none" id="form-confirmation">
				<div class="card-header">
					<h3 class="card-title text-center"><b>CONFIRMATION</b></h3>
				</div>
				<div class="card-body" id="content-confirmation">
					<div class="alert alert-warning alert-styled-left">
						Please complete the data before confirmation.
					</div>
				</div>
				<div class="card-footer text-right">
					<div class="alert alert-danger" id="validation_alert" style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
						<ul id="validation_content"></ul>
					</div>
					<button type="button" class="btn bg-success" onclick="saveProject()"><i class="icon-floppy-disk"></i> Save</button>
				</div>
			</div>
		</form>
	</div>

<script>
	$(function() {
		select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
		select2ServerSide('#product_id', '{{ url("admin/select2/product") }}');
		select2ServerSide('#user_id', '{{ url("admin/select2/user") }}');
		
		$('#data_product').on('click', '#delete_data_product', function() {
			$(this).closest('tr').remove();
			countGrandtotal();
		});
	});
	
	function addProduct() {
      var id = $('#product_id');

      if(id.val()) {
		  
		  
         $.ajax({
            url: '{{ url("admin/sales/project/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: id.val()
            },
            beforeSend: function() {
               loadingOpen('#form-product');
            },
            success: function(response) {
				if(response.error == '500'){
					
					var msg = '';
					
					$.each(response.message, function(i, val) {
						msg = msg + val;
					});
					
					swalInit.fire('Error product!', msg, 'error');
					
				}else{
					var same = false;
					
					$('input[name^="product_id"]').each(function() {
						if($(this).val() == response.id){
							same = true;
						}
					});
					
					if(same == false){
					   id.val(null).trigger('change');

					   $('#data_product').append(`
						  <tr class="text-center rowproduct">
							 <input type="hidden" name="product_id[]" value="` + response.id + `">
							 <input type="hidden" name="product_price[]" value="` + response.price + `">
							 <td class="align-middle dataproduct">` + response.product + `</td>
							 <td class="align-middle">
								<input type="text" name="product_area[]" class="form-control" placeholder="Type area" style="width:100px !important;" required>
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_spec[]" class="form-control" placeholder="Type spec" value="` + response.surface + `" style="width:100px !important;" required>
							 </td>
							 <td class="align-middle">
								<select name="product_unit[]" class="custom-select" required style="width:100px !important;" id="unit`+ response.id +`">
								   <option value="1">Pcs</option>   
								   <option value="3">Meter</option>   
								   <option value="4">Meter(Custom)</option>   
								</select>
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_qty[]" style="width:100px !important;" class="form-control" placeholder="0" required id="product`+ response.id +`" data-m2="` + response.sqm + `" onkeyup="countSuggestion(this,` + response.id + `);">
							 </td>
						     <td class="align-middle">
								<span id="qtysuggestion` + response.id + `">0</span>
							 </td>
							 
							 <td class="align-middle">
								<input type="text" name="product_price_new[]" class="form-control" value="0" required onkeyup="formatRupiah(this);countTotal(this,` + response.id + `);" style="width:100px !important;">
							 </td>
							 <td class="align-right">
								<span style="width:100px !important;" id="total`+ response.id +`" class="totaleachproduct">0</span>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
					   `);
					}else{
						swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
					}
				}
				
				loadingClose('#form-product');
            },
            error: function() {
               loadingClose('#form-product');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
      } else {
         swalInit.fire('Ooppsss!', 'Please select a product', 'info');
      }
   }
   
   function countTotal(element,id){
		var result = parseFloat($(element).val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#product' + id).val());
	   
		$('#total'+id).html(formatRupiahIni(result.toFixed(2).toString().replace('.',',')));
		
		countGrandtotal();
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
   
   function countGrandtotal(){
		var grandtotal = 0, afterdiscount = 0, grandtotalall = 0, ppn = 0, discount = parseFloat($('#discount').val().replaceAll('.','').replaceAll(',','.'));
		
		$('.totaleachproduct').each(function() {
			grandtotal += parseFloat($(this).text().replaceAll('.','').replaceAll(',','.'));
		});
		
		afterdiscount = grandtotal - discount;
		
		ppn = $('#isppn').val() == '1' ? afterdiscount * 0.11 : 0;
		
		grandtotalall = afterdiscount + ppn;
		
		$('#grandtotal').html(formatRupiahIni(grandtotal.toFixed(2).toString().replace('.',',')));
		$('#afterdiscount').html(formatRupiahIni(afterdiscount.toFixed(2).toString().replace('.',',')));
		$('#ppn').html(formatRupiahIni(ppn.toFixed(2).toString().replace('.',',')));
		$('#grandtotalall').html(formatRupiahIni(grandtotalall.toFixed(2).toString().replace('.',',')));
	}
	
	$('#payment_method').on('change', function() {
		if($(this).val() == '1'){
			$('#detail_payment').empty().append('<option value="">-- Choose --</option><option value="11">Cash Before Delivery</option><option value="12">Cash After Delivery</option><option value="13">Cash with DP</option>');
		}else if($(this).val() == '2'){
			$('#detail_payment').empty().append('<option value="">-- Choose --</option><option value="21">Cover BG</option><option value="22">SKBDN</option><option value="23">SCF</option><option value="24">Credit with DP</option><option value="25">Credit without DP</option>');
		}else{
			$('#detail_payment').empty().append('<option value="">-- Choose --</option>');
		}
	});
	
	function confirmCustomer(){
		var customer_id = $('#customer_id').val(), customer_name = $('#name').val(), customer_phone = $('#phone').val();
		if(!customer_id && !customer_name && !customer_phone){
			notif('warning', 'bg-warning', 'Please at least choose customer from existing data or fill in customer form if no data found.');
		}else{
			if(customer_name && customer_phone){
				$('#form-product').removeClass('d-none');
				$('html, body').animate({
					scrollTop: $("#form-product").offset().top
				}, 500);
			}else if(customer_id){
				$('#form-product').removeClass('d-none');
				$('html, body').animate({
					scrollTop: $("#form-product").offset().top
				}, 500);
			}else{
				notif('warning', 'bg-warning', 'Please at least choose customer from existing data or fill in customer form if no data found.');
			}
		}
	}
	
	function confirmProduct(){
		if($('input[name^="product_id"]').length == 0){
			notif('warning', 'bg-warning', 'Please choose at least one product to continue.');
		}else{
			var grandtotalall = parseFloat($('#grandtotalall').text().replace(',','.').replace(/\./g,''));
			
			if(grandtotalall > 0){
				$('#form-delivery').removeClass('d-none');
				$('html, body').animate({
					scrollTop: $("#form-delivery").offset().top
				}, 500);
			}else{
				notif('warning', 'bg-warning', 'Your grandtotal is 0.');
			}
		}
	}
	
	function confirmDelivery(){
		if(!$('#address').val() || !$('#country_id').val() || !$('#city_id').val()){
			notif('warning', 'bg-warning', 'Please complete the form and required inputs.');
		}else{
			$('#form-payment').removeClass('d-none');
			$('html, body').animate({
				scrollTop: $("#form-payment").offset().top
			}, 500);
		}
	}
	
	function confirmPayment(){
		if(!$('#bank_id').val() || !$('#payment_method').val() || !$('#detail_payment').val()){
			notif('warning', 'bg-warning', 'Please complete the form and required inputs.');
		}else{
			$('#form-confirmation').removeClass('d-none');
			$('html, body').animate({
				scrollTop: $("#form-confirmation").offset().top
			}, 500);
			
			if($('#name').val() && $('#phone').val()){
				var customer_name = $('#name').val();
				var customer_phone = $('#phone').val();
			}else if($('#customer_id').val()){
				var customer_name = $('#customer_id').select2('data')[0].text.split('|')[0];
				var customer_phone = $('#customer_id').select2('data')[0].text.split('|')[1];
			}
			
			var arrUnit = [];
			
			$('select[name^="product_unit"]').each(function() {
				arrUnit.push($(this).find('option:selected').text());
			});
			
			var tableProducts = ``;
			
			var index = 0;
			$('input[name^="product_id"]').each(function() {
				tableProducts += `
					<tr>
						<td class="text-center">` + $('.dataproduct').eq(index).html() + `</td>
						<td class="text-center">` + $('input[name^="product_area"]').eq(index).val() + `</td>
						<td class="text-center">` + $('input[name^="product_spec"]').eq(index).val() + `</td>
						<td class="text-center">` + $('input[name^="product_qty"]').eq(index).val() + `</td>
						<td class="text-center">` + arrUnit[index] + `</td>
						<td class="text-right">` + $('input[name^="product_price_new"]').eq(index).val() + `</td>
						<td class="text-right">` + $('.totaleachproduct').eq(index).html() + `</td>
					</tr>
				`;
				
				index++;
			});
			
			tableProducts += `
				<tr>
					<td class="text-right" colspan="6">Total</td>
					<td class="text-right">` + $('#grandtotal').html() + `</td>
				</tr>
				<tr>
					<td class="text-right" colspan="6">Discount</td>
					<td class="text-right">` + $('#discount').val() + `</td>
				</tr>
				<tr>
					<td class="text-right" colspan="6">After Discount</td>
					<td class="text-right">` + $('#afterdiscount').html() + `</td>
				</tr>
				<tr>
					<td class="text-right" colspan="6">PPN</td>
					<td class="text-right">` + $('#ppn').html() + `</td>
				</tr>
				<tr>
					<td class="text-right" colspan="6">Grandtotal</td>
					<td class="text-right">` + $('#grandtotalall').html() + `</td>
				</tr>
			`;
			
			$('#content-confirmation').html(`
				<h5>Customer Information</h5>
				<hr>
				<dl class="row mb-0" style="font-size:15px;">
					<dd class="col-sm-3">Customer</dd>
					<dt class="col-sm-9">
					` + customer_name + `
					</dt>
					<dd class="col-sm-3">Phone</dd>
					<dt class="col-sm-9">
					` + customer_phone + `
					</dt>
				</dl>
				<hr>
				<h5>Products Information</h5>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
					   <thead class="table-secondary">
						  <tr class="text-center">
							<th>Product</th>
							<th>Area</th>
							<th>Surface</th>
							<th>Qty</th>
							<th>Unit</th>
							<th>Price</th>
							<th>Total</th>
						  </tr>
					   </thead>
					   <tbody id="data_product">
						` + tableProducts + `
					   </tbody>
					</table>
				</div>
				<hr>
				<h5>Delivery Address</h5>
				<hr>
				<dl class="row mb-0" style="font-size:15px;">
					<dd class="col-sm-3">Address</dd>
					<dt class="col-sm-9">
					` + $('#address').val() + `
					</dt>
					<dd class="col-sm-3">City</dd>
					<dt class="col-sm-9">
					` + $('#city_id').select2('data')[0].text + `
					</dt>
					<dd class="col-sm-3">Country</dd>
					<dt class="col-sm-9">
					` + $('#country_id').select2('data')[0].text + `
					</dt>
					<dd class="col-sm-3">Note</dd>
					<dt class="col-sm-9">
					` + $('#note').val() + `
					</dt>
				</dl>
				<hr>
				<h5>Payment Information</h5>
				<hr>
				<dl class="row mb-0" style="font-size:15px;">
					<dd class="col-sm-3">Bank</dd>
					<dt class="col-sm-9">
					` + $('#bank_id').select2('data')[0].text + `
					</dt>
					<dd class="col-sm-3">Payment Method</dd>
					<dt class="col-sm-9">
					` + $('#payment_method').find('option:selected').text() + `
					</dt>
					<dd class="col-sm-3">Detail Payment</dd>
					<dt class="col-sm-9">
					` + $('#detail_payment').find('option:selected').text() + `
					</dt>
					<dd class="col-sm-3">Term Payment</dd>
					<dt class="col-sm-9">
					` + $('#term_payment').find('option:selected').text() + `
					</dt>
				</dl>
			`);
		}
	}
	
	function saveProject(){
		var notyConfirm = new Noty({
		 theme: 'limitless',
		 text: '<h6 class="font-weight-bold mb-3">Are sure you want to save this purchase?</h6><label>Saved data can be deleted in Sales Project page.</label>',
		 timeout: false,
		 modal: true,
		 layout: 'center',
		 closeWith: 'button',
		 type: 'confirm',
		 buttons: [
			Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
			   notyConfirm.close();
			}),
			Noty.button('<i class="icon-floppy-disk"></i>', 'btn bg-success ml-1', function() {
				$.ajax({
				 url: '{{ url("admin/sales/in_store/create") }}',
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
						notif('success', 'bg-success', response.message);
						notyConfirm.close();
						setTimeout(function() {
							location.reload();
						}, 1500);
					} else if(response.status == 422) {
						$('#validation_alert').show();
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
			})
		 ]
		}).show();
	}
	
	function countSuggestion(element,id){
		if($('#unit' + id).val() == '3'){
			var m2 = parseFloat($(element).data('m2'));
			$('#qtysuggestion' + id).html((parseFloat($(element).val()) / m2).toFixed(2).toString());
		}else{
			$('#qtysuggestion' + id).html($(element).val());
		}
	}
</script>