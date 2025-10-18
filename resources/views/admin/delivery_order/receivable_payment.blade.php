<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Other Receivable Payment</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="filter()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Delivery Order</a>
					<span class="breadcrumb-item active">Other Receivable Payment</span>
				</div>
			</div>
		</div>
	</div>
	
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
						<th>Customer</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Description</th>
						<th>Progress</th>
						<th>Action</th>
                     </tr>
                  </thead>
               </table>
            </div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form_pay" data-backdrop="static" role="dialog">
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
				   <div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
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
							</select>
						</div>
					  </div>
					  <div class="col-md-3">
						<div class="form-group">
						  <label>Branch :<sup class="text-danger">*</sup></label>
						  <select name="branch" id="branch" class="custom-select">
						   @foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
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
							<label>Note :</label>
							<input type="text" name="pay_note" id="pay_note" class="form-control" value="-">
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
								   <th>Destination</th>
								   <th>Nominal</th>
								   <th>Proof</th>
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
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create_new" onclick="create_pay()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
<script>
	$(function() {
		loadDataTable();
		
		$("#datatable_serverside").on( "click", 'tbody tr .btn-pay', function() {
			  var id = $(this).data('cb'),nominal = $(this).data('nominal'), item = $(this).data('item'), nominalacc = $(this).data('nominalacc');
			  
			  $('#temppay').val(id);
			  $('#nominal').val(nominal);
			  $('#pay_total').html(nominalacc);
			  $('#pay_item').html(item);
			  $('#pay_note').val('PEMBAYARAN ' + item);
			  
			  $.ajax({
				 url: '{{ url("admin/delivery_order/receivable_payment/get_payment") }}',
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
					
					$('#data_payment').empty();
					
					if(response.length > 0){
					
						$.each(response, function(i, val) {
							var btn = 'Approved';
							if(val.check == '0'){
								btn = `<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyPay(` + val.id + `)"><i class="icon-trash-alt"></i></button>`;
							}
							$('#data_payment').append(`
								<tr class="text-center row` + val.id + `">
									<td>` + val.date + `</td>
									<td>` + val.source + `</td>
									<td>` + val.nominal + `</td>
									<td>` + val.proof + `</td>
									<td>` + val.note + `</td>
									<td>
										` + btn + `
									</td>
								</tr>
							`);
						});
						
					}else{
						$('#data_payment').append(`
							<tr class="text-center align-middle">
								<td class="bg-danger" colspan="6">There is no payment data.</td>
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
		
		$('#modal_form_pay').on('hidden.bs.modal', function (e) {
			$('#form_data_pay')[0].reset();
			$('#temppay').val('');
		});
		
		$('.sidebar-main-toggle').click();
	});
		
	function create_pay() {
	  $.ajax({
		 url: '{{ url("admin/delivery_order/receivable_payment/add_payment") }}',
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
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
			   $('#form_data_pay')[0].reset();
			   if(response.count == 1){
				   $('#data_payment').empty();
			   }
				$('#data_payment').append(`
					<tr class="text-center row` + response.result.id + `">
						<td>` + response.result.date + `</td>
						<td>` + response.result.source + `</td>
						<td>` + response.result.nominal + `</td>
						<td>` + response.result.proof + `</td>
						<td>` + response.result.note + `</td>
						<td>
							<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyPay(` + response.result.id + `)"><i class="icon-trash-alt"></i></button>
						</td>
					</tr>
				`);
			   $('#datatable_serverside').DataTable().ajax.reload(null, false);
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
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'desc']],
         ajax: {
            url: '{{ url("admin/delivery_order/receivable_payment/datatable") }}',
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
			{ name: 'customer_id', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'total', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
            { name: 'date', searchable: false, className: 'text-center align-middle' },
            { name: 'description', className: 'text-center align-middle' },
			{ name: 'progress', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
			{ name: 'action', searchable: false, orderable: false, className: 'text-center align-middle nowrap' }
         ]
      }); 
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
				  url: '{{ url("admin/delivery_order/receivable_payment/delete_payment") }}',
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
</script>