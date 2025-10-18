<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Project Multi Payment</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="refresh()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Delivery Order</a>
					<span class="breadcrumb-item active">Project Payment</span>
				</div>
			</div>
		</div>
	</div>
	
	<div class="content">
		<div class="card">
			<div class="card-header">
				<div class="row">
                    <div class="col-12">
                        <h6 id="title_periode" class="text-muted text-uppercase text-center font-weight-bold">
                            Periode {{ date('F Y', strtotime($filter)) }}</h6>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <label>Month Year</label>
                        <input type="month" name="filter" id="filter" class="form-control" value="{{ date('Y-m') }}">
                    </div>
                </div>
			</div>
			<div class="card-body">
				<div class="alert alert-info alert-styled-left alert-dismissible">
					<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
					<span class="font-weight-semibold">Important Info!</span> Please make sure the sales & deliveries you input here were already sent to customer, because the journal will automatically create account receivable.</a>
				</div>
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>#</th>
							<th>No</th>
							<th>User</th>
							<th>Code</th>
							<th>Date</th>
							<th>To</th>
							<th>Customer</th>
							<th>Nominal</th>
							<th>Note</th>
							<th>Proof</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Add New Payment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_pay">
					<div class="customer_deposit_warning col-md-12 d-none">
						<div class="alert alert-danger alert-styled-left alert-dismissible">
							<span class="font-weight-semibold">Info!</span><b> Choose customer (customer deposit from) field, if only this customer used another customer (Payment/ Customer Deposit) for their own bills</b>
						</div>
					</div>
				   <div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
				   </div>
				   <h5 class="card-title"><b>Main Information</b></h5>
				   <div class="row">
					  <div class="col-md-3">
						 <div class="form-group">
							<input type="hidden" id="temp_pay" name="temp_pay">
							<label>Customer :<sup class="text-danger">*</sup></label>
							<select name="customer_id" id="customer_id" onchange="getBalancePayment(this)"></select>
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Date Paid :<sup class="text-danger">*</sup></label>
							<input type="date" name="pay_date" id="pay_date" class="form-control" min="{{date('Y-m-d')}}">
						 </div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
							<label>Cash & Bank Destination :<span class="text-danger">*</span></label>
							<select name="pay_coa" id="pay_coa" class="custom-select">
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
							   <option value="67">Customer Deposit</option>
							</select>
						</div>
					  </div>
					  <div class="customer_deposit col-md-3 d-none">
						<div class="form-group">
						  <label>Customer Deposit From :<sup class="text-danger">*</sup></label>
						  <select name="customer_deposit_list" id="customer_deposit_list" class="select2">
							<option value="">-- Choose --</option>
							@foreach($customerdeposit as $row)
							@if(str_replace(',','.',str_replace('.','',$row['total'])) > 0)
							<option value="{{ $row['customer_id'] }}" data-branch="{{$row['branch']}}" data-balance="{{$row['total']}}" data-customerdeposit="{{'Customer Deposit '.$row['customer_name'].' IDR '.$row['total']}}" data-customer="{{ $row['customer_name'] }}">Customer {{ $row['customer_name'].'- IDR '.$row['total'] }}</option>
							@endif
							@endforeach
						  </select>
						</div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
						  <label>Total Pay :<sup class="text-danger">*</sup></label>
						  <input name="pay_nominal" id="pay_nominal" type="text" onkeyup="formatRupiah(this);countTotalPay(this.value);" class="form-control">
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
							<label>Method (Transfer/Giro/Check/Cash) :</label>
							<select name="payment_method" id="payment_method" class="custom-select">
								<option value="0">Transfer</option>
								<option value="1">Giro</option>
								<option value="2">Check</option>
								<option value="3">Cash</option>
							</select>
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Giro Number :</label>
							<input type="text" name="giro_code_detail" id="giro_code_detail" class="form-control" value="-">
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Giro Due Date :</label>
							<input type="date" name="giro_date_detail" id="giro_date_detail" class="form-control">
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Note :</label>
							<input type="text" name="pay_note" id="pay_note" class="form-control" value="-">
						 </div>
					  </div>
					</div>
					<div class="form-group"><hr></div>
					<h5 class="card-title">Preview Proof</h5>
					<div class="form-group text-center" id="previewImg">
						<img id="previewImage" src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
					</div>
					<hr>
					<h5 class="card-title"><b>Details Customer Payments</b></h5>
					<div class="row">
						<div class="form-group col-md-12">
							<table class="table table-bordered">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th>PJ Number</th>
									   <th>SO Number</th>
									   <th>Total Payment</th>
									   <th>Total Sales</th>
									   <th>Balance Overpayment</th>
									</tr>
								</thead>
								<tbody id="data_payment">
									<tr>
										<td colspan="5" class="text-center"><span class="badge badge-info" style="font-size:15px;">Choose customer first.</span></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<hr>
					<h5 class="card-title"><b>Details Project Sale</b></h5>
					<div class="alert alert-info alert-styled-left alert-dismissible">
						<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
						<span class="font-weight-semibold">Important!</span> You may add more than 1 sales order to pay.
					</div>
					<div class="row">
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Sales Order :<sup class="text-danger">*</sup></label>
							<select name="so_id" id="so_id" onchange="getBill(this)"></select>
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Delivery :</label>
							<select name="delivery_id" id="delivery_id" class="select2" onchange="getTotalDelivery(this)">
							   <option value="">-- None --</option>
							</select>
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>From Bill :</label>
							<select name="bill_id" id="bill_id" class="select2">
							   <option value="">-- None --</option>
							</select>
						 </div>
					  </div>
					  <div class="col-md-3">
						 <div class="form-group">
							<label>Type :<sup class="text-danger">*</sup></label>
							<select name="payment_method_detail" id="payment_method_detail" class="custom-select" onchange="">
							   <option value="1">Down Payment</option>
							   <option value="2">Full Payment Upfront</option>
							   <option value="3">Full Payment Last</option>
							</select>
						 </div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
						  <label>Nominal :<sup class="text-danger">*</sup></label>
						  <input name="nominal_detail" id="nominal_detail" type="text" onkeyup="formatRupiah(this)" class="form-control">
						</div>
					  </div>
					  <div class="col-md-12">
						<div class="form-group">
							<button type="button" onclick="addSalesProject()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add More</button>
						</div>
					  </div>
					</div>
					<hr>
					<h5 class="card-title"><b>List of All Sales Order</b></h5>
					<div class="row">
						<div class="form-group col-md-12">
							<table class="table table-bordered">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th>SO Number</th>
									   <th>Bill</th>
									   <th>Type</th>
									   <th>Method</th>
									   <th>Date</th>
									   <th>Nominal</th>
									   <th>Giro Number</th>
									   <th>Giro Due Date</th>
									   <th>Note</th>
									   <th>#</th>
									</tr>
								</thead>
								<tbody id="data_content"></tbody>
							</table>
						</div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					In : <span class="badge badge-success" id="tempdebit">0</span>
					&nbsp;
					Out : <span class="badge badge-danger" id="tempcredit">0</span>
					&nbsp;
					Balance : <span class="badge badge-info" id="tempbalance">0</span> &nbsp; <= MUST BE ZERO (0)
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_new" onclick="create_pay()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
<script>
	$(function() {
		
		$("#pay_file").on('change', function () {

			if (typeof (FileReader) != "undefined") {

				var image_holder = $("#previewImg");
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
		
		select2ServerSide('#so_id', '{{ url("admin/select2/sales_order") }}');
		select2ServerSide('#customer_id', '{{ url("admin/select2/customer_unpaid") }}');
		
		refresh();

		$('#bill_id, #delivery_id').change(function () { 
				$.ajax({
				url: '{{ url("admin/delivery_order/project_payment/get_payment_date") }}',
				type: 'GET',
				async: false,
				data: {
					bill_id: $('#bill_id').val(),
					delivery_id : $('#delivery_id').val()
				},
				success: function(response) {	
					var date = response.project_bill_date ? new Date(response.project_bill_date) : new Date(response.project_delivery_date);
					var yyyy = date.getFullYear().toString();
					var mm = (date.getMonth()+1).toString();
					var dd  = date.getDate().toString();
					var realDate = yyyy+'-'+("0"+(mm)).slice(-2)+'-'+("0"+dd).slice(-2)
					
					if(response.project_bill_date){
						$('#pay_date').attr('min', realDate);
					}else{
						$('#pay_date').attr('min', realDate);
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
			
		});
		
		$('#data_content').on('click', '#delete_data', function() {
			$(this).closest('tr').remove();
			countAll();
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
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#form_data_pay').trigger('reset');
			$('#customer_id').empty();
			$('#temp_pay').val('');
			$('#data_content').html('');
		});
	});
	
	$('#filter').change(function () { 
		var date = new Date($(this).val()),
		month = date.toLocaleString('default', { month: 'long' }),              
		year =  date.getFullYear();
		$('#title_periode').html(`Periode `+month+` `+year);
		refresh();
	});

	$("#pay_coa").change(function (e) { 
		if($(this).val() == "67"){
			$('.customer_deposit').removeClass('d-none');
			$('.customer_deposit_warning').removeClass('d-none');
		}else{
			$('.customer_deposit').addClass('d-none');
			$('.customer_deposit_warning').addClass('d-none');
		}
	});

	function countTotalPay(val){
		$('#tempdebit').text(val);
	}
	
	function countAll(){
		var In = parseFloat($('#tempdebit').text().replaceAll(".", "").replaceAll(",",".")), Out = 0, Balance = 0;
		
		$('input[name^="arr_nominal"]').each(function(){
			Out = Out + parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
		});
		
		Balance = In - Out < 0 ? -1 * (In - Out) : In - Out;
		
		$('#tempcredit').text(formatRupiahIni(Out));
		$('#tempbalance').text(formatRupiahIni(Balance));
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
	
	function refresh(){
		window.table = loadDataTable();
	}
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/delivery_order/project_payment/row_detail") }}',
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
	
	function getBalancePayment(element){
		if(element.value !== ''){
			$.ajax({
				url: '{{ url("admin/delivery_order/project_payment/get_balance_payment") }}',
				type: 'GET',
				dataType: 'JSON',
				data: {
					customer : element.value
				},
				beforeSend: function() {
					loadingOpen('#form_data_pay');
				},
				success: function(response) {
					if(response.length > 0) {
						$('#data_payment').empty();
						$.each(response, function(i, val) {
							$('#data_payment').append(`
								<tr>
									<td>` + val.project + `</td>
									<td>` + val.project_sale + `</td>
									<td>` + val.total_payment + `</td>
									<td>` + val.total_sales + `</td>
									<td>` + val.balance + `</td>
								</tr>
							`);
						});
					}
					
					loadingClose('#form_data_pay');
				},
				error: function() {
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
					loadingClose('#form_data_pay');
				}
			});
		}
	}
	
	function getBill(element){
		if(element.value !== ''){
			var so_id = element.value;
			
			$.ajax({
				url: '{{ url("admin/delivery_order/project_payment/get_bill") }}',
				type: 'GET',
				dataType: 'JSON',
				data: {
					so_id : so_id
				},
				beforeSend: function() {
					loadingOpen('#form_data_pay');
				},
				success: function(response) {
					if(response.bill.length > 0) {
						$('#bill_id').empty();
						$('#bill_id').append(`
							<option value="">-- None --</option>
						`);
						$.each(response.bill, function(i, val) {
							$('#bill_id').append(`
								<option value="` + val.id + `">` + val.text + `</option>
							`);
						});
					}else{
						$('#bill_id').val(null).trigger('change');
					}
					
					if(response.delivery.length > 0) {
						$('#delivery_id').empty();
						$('#delivery_id').append(`
							<option value="">-- None --</option>
						`);
						$.each(response.delivery, function(i, val) {
							$('#delivery_id').append(`
								<option value="` + val.id + `" data-total="` + val.total + `">` + val.text + `</option>
							`);
						});
					}else{
						$('#delivery_id').val(null).trigger('change');
					}
					
					loadingClose('#form_data_pay');
				},
				error: function() {
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
					loadingClose('#form_data_pay');
				}
			});
		}
	}
	
	function addSalesProject(){
		if($('#customer_id').val() && $('#pay_date').val() && $('#pay_coa').val() && $('#payment_method').val()){
			var sales = $('#so_id'), delivery = $('#delivery_id'), bill = $('#bill_id'), payment_method_detail = $('#payment_method_detail'), nominal_detail = $('#nominal_detail'), pay_date = $('#pay_date'), pay_giro = $('#giro_code_detail'), pay_giro_date = $('#giro_date_detail'), method = $('#payment_method'), note = $('#pay_note');
			
			var same = false;
						
			/* $('.rowsales').each(function() {
				if($(this).data('sales') == sales.val()){
					same = true;
				}
			}); */
			
			if(same == false){
				$('#data_content').append(`
					<tr class="rowsales" data-sales="` + sales.val() + `">
						<input type="hidden" name="arr_sales_id[]" value="` + sales.val() + `">
						<input type="hidden" name="arr_delivery_id[]" value="` + delivery.val() + `">
						<input type="hidden" name="arr_bill_id[]" value="` + bill.val() + `">
						<input type="hidden" name="arr_type[]" value="` + payment_method_detail.val() + `">
						<input type="hidden" name="arr_method[]" value="` + method.val() + `">
						<input type="hidden" name="arr_date[]" value="` + pay_date.val() + `">
						<input type="hidden" name="arr_nominal[]" value="` + nominal_detail.val() + `">
						<input type="hidden" name="arr_giro_code[]" value="` + pay_giro.val() + `">
						<input type="hidden" name="arr_giro_date[]" value="` + pay_giro_date.val() + `">
						<input type="hidden" name="arr_note[]" value="` + note.val() + `">
						<td>` + sales.select2('data')[0].text + `</td>
						<td>` + bill.select2('data')[0].text + `</td>
						<td>` + payment_method_detail.find('option:selected').text() + `</td>
						<td>` + method.find('option:selected').text() + `</td>
						<td>` + pay_date.val() + `</td>
						<td>` + nominal_detail.val() + `</td>
						<td>` + pay_giro.val() + `</td>
						<td>` + pay_giro_date.val() + `</td>
						<td>` + note.val() + `</td>
						<td><button type="button" id="delete_data" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
					</tr>
				`);
				
				countAll();
				
				$('#bill_id').val($("#bill_id option:first").val()).trigger('change');
			}else{
				notif('error', 'bg-danger', 'The SO you choose is already added, choose another.');
			}
		}else{
			notif('error', 'bg-danger', 'Please complete form to continue add project sale to table.');
		}
	}
	
	function getTotalDelivery(element){
		if(element.value !== ''){
			$('#nominal_detail').val($(element).find('option:selected').data('total'));
		}
	}
	
	function create_pay(){
		loadingOpen('.modal-content');
		
		if($('.rowsales').length > 0){
			
			if($('#tempbalance').text() == '0'){ 
				var totalpay = parseFloat($('#pay_nominal').val().replaceAll(".", "").replaceAll(",","."));
				var totalsales = 0;
				
				$('input[name^="arr_nominal"]').each(function(){
					totalsales = totalsales + parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
				});
				
				/* if(totalpay >= totalsales){*/
					$.ajax({
					 url: '{{ url("admin/delivery_order/project_payment/create") }}',
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
						$('#validation_alert').hide();
						$('#validation_content').html('');
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
				/* }else{
					notif('error', 'bg-danger', 'Total sales more than total pay. Please check each sales nominal.');
					loadingClose('.modal-content');
				}*/
 			}else{
				notif('error', 'bg-danger', 'Total sales more than total pay. Please check each sales nominal.');
			}
		
		}else{
			notif('error', 'bg-danger', 'Please choose at least 1 Sales Order to pay.');
			loadingClose('.modal-content');
		}
	}
	
	function success(){
		reset();
		$('#modal_form').modal('hide');
		$('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function reset() {
		$('#form_data_pay').trigger('reset');
		$('#validation_alert').hide();
		$('#validation_content').html('');
		$('#data_content').empty();
	}
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'desc']],
         ajax: {
            url: '{{ url("admin/delivery_order/project_payment/datatable") }}',
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
            { name: 'user', className: 'text-center align-middle' },
			{ name: 'code', className: 'text-center align-middle' },
			{ name: 'date', className: 'text-center align-middle' },
            { name: 'coa', className: 'text-center align-middle' },
            { name: 'customer', className: 'text-center align-middle' },
            { name: 'nominal', className: 'text-center align-middle' },
            { name: 'note', className: 'text-center align-middle' },
			{ name: 'proof', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center align-middle' }
         ]
      }); 
	}
	
	function toShow() {
      $('#data_content').html('');
      $('#modal_form').modal('show');
      $('#validation_alert').hide();
      $('#validation_content').html('');
	}
	
	function show(id) {
      toShow();
	  $('#temp_pay').val(id);
      $.ajax({
         url: '{{ url("admin/delivery_order/project_payment/show") }}',
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
			
			$('#customer_id').empty();
            $('#customer_id').append(`
				<option value="` + response.data.customer_id + `">` + response.data.customer_name + `</option>
			`);
            $('#pay_date').val(response.data.date);
			$('#pay_coa').val(response.data.coa_id);
			$('#pay_nominal').val(response.data.nominalconvert);
			$('#payment_method').val(response.data.payment_method);
			$('#giro_code_detail').val(response.data.giro_code);
			$('#giro_date_detail').val(response.data.giro_date);
			$('#previewImage').attr("src", response.data.proof_file);
			
			$('#data_content').empty();
			
            $.each(response.detail, function(i, val) {
				$('#data_content').append(`
					<tr class="rowsales" data-sales="` + val.project_sale_id + `">
						<input type="hidden" name="arr_sales_id[]" value="` + val.project_sale_id + `">
						<input type="hidden" name="arr_delivery_id[]" value="` + val.project_delivery_id + `">
						<input type="hidden" name="arr_bill_id[]" value="` + val.project_bill_id + `">
						<input type="hidden" name="arr_type[]" value="` + val.giro + `">
						<input type="hidden" name="arr_method[]" value="` + val.type + `">
						<input type="hidden" name="arr_date[]" value="` + val.date + `">
						<input type="hidden" name="arr_nominal[]" value="` + val.nominal + `">
						<input type="hidden" name="arr_giro_code[]" value="` + val.giro_code + `">
						<input type="hidden" name="arr_giro_date[]" value="` + val.giro_date + `">
						<input type="hidden" name="arr_note[]" value="` + val.note + `">
						<td>` + val.project_sale_code + `</td>
						<td>` + val.project_bill_code + `</td>
						<td>` + val.giro_name + `</td>
						<td>` + val.type_name + `</td>
						<td>` + val.date + `</td>
						<td>` + val.nominal + `</td>
						<td>` + val.giro_code + `</td>
						<td>` + val.giro_date + `</td>
						<td>` + val.note + `</td>
						<td><button type="button" id="delete_data" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
					</tr>
				`);
            });
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
                  url: '{{ url("admin/delivery_order/project_payment/destroy") }}',
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
</script>