<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Payment Request</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
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
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Payment Request</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Payment Request</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
					  <div class="form-group">
						 <label>Date :</label>
						 <div class="input-group-prepend">
							<input type="date" name="filter_start_date" id="filter_start_date" class="form-control">
						 </div>
					  </div>
				   </div>
				   <div class="col-md-4">
					  <div class="form-group">
						 <label>To :</label>
						 <div class="input-group-prepend">
							<input type="date" name="filter_finish_date" id="filter_finish_date" class="form-control">
						 </div>
					  </div>
				   </div>
				   <div class="col-md-4">
						<div class="form-group">
							<label>&nbsp;</label>
							<div class="input-group-prepend">
								<button type="button" onclick="filter()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
								<button type="button" onclick="filter('reset')" class="btn bg-danger"><i class="icon-sync"></i></button>
							</div>
						</div>
				   </div>
				</div>
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>#</th>
							<th>Code</th>
							<th>User</th>
							<th>Checked By</th>
							<th>Approved By</th>
							<th>Date</th>
							<th>Note</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form New Payment Request</h5>
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
					  <!-- <div class="col-md-4">
						 <div class="form-group">
							<label>Title :<sup class="text-danger">*</sup></label>
							<input type="text" name="title" id="title" class="form-control" placeholder="Enter title">
						 </div>
					  </div> -->
					  <div class="col-md-4">
						 <div class="form-group">
							<label>Date :<sup class="text-danger">*</sup></label>
							<input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
						 </div>
					  </div>
					  <div class="col-md-4">
						<div class="form-group">
						  <label>Note :<sup class="text-danger">*</sup></label>
						  <textarea name="note" id="note" class="form-control" placeholder="Enter note" rows="1"></textarea>
						</div>
					  </div>
				   </div>
				   <div class="form-group"><hr></div>
				   <h5 class="card-title"><b>Choose Purchase Request</b></h5>
				   <div class="row">
				      <div class="col-md-4">
						<div class="form-group">
							<label>Purchase Request :<sup class="text-danger">*</sup></label>
							<select name="purchase_request_id" id="purchase_request_id"></select>
							<span class="badge d-block badge-danger form-text">You may search by date (yyyy-mm-dd), total nominal, rev nominal, or item name.</span>
						 </div>
					  </div>
					  
					  <div class="col-md-12">
						 <div class="form-group">
							<label>&nbsp;</label>
							<button type="button" class="btn bg-success col-12" onclick="addPurchaseRequest()"><i class="icon-plus22"></i> Add to Table</button>
						 </div>
					  </div>
					  
					  <div class="col-md-12 mx-auto">
						 <table class="table table-bordered">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th width="25%">Purchase Request</th>
								   <th>Nominal</th>
								   <th>#</th>
								</tr>
							 </thead>
							 <tbody id="data_content_request"></tbody>
						  </table>
					  </div>
					  
					  <div class="col-md-12">
						<hr>
						<h5 class="card-title"><b>Choose Source of Payment</b></h5>
					  </div>
					  
					  <div class="col-md-4">
						<div class="form-group">
							<label>Source of payment :<sup class="text-danger">*</sup></label>
							<select name="coa_id" id="coa_id" class="custom-select">
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
							<span class="badge d-block badge-danger form-text">You may choose more than one source of payment.</span>
						 </div>
					  </div>
					  <div class="col-md-4">
						<div class="form-group">
							<label>Branch :<span class="text-danger">*</span></label>
							<select name="branch" id="branch" class="custom-select">
								@foreach (DB::table('company_entities')->get() as $company)
									<option value="{{$company->id}}">{{$company->name}}</option>
								@endforeach
							</select>
						</div>
					  </div>
					  
					  <div class="col-md-4">
						<div class="form-group">
							<label>Nominal :<span class="text-danger">*</span></label>
							<input type="text" name="nominal_source" id="nominal_source" class="form-control" placeholder="0" onkeyup="formatRupiah(this)">
						</div>
					  </div>
					  
					  <div class="col-md-12 mx-auto">
						 <div class="form-group">
							<button type="button" class="btn bg-success col-12" onclick="addSourcePayment()"><i class="icon-plus22"></i> Add to Table</button>
						 </div>
					  </div>
					  <div class="col-md-12 mx-auto">
						 <table class="table table-bordered">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th>Cash/Bank</th>
								   <th>Branch</th>
								   <th>Nominal</th>
								   <th>Code</th>
								   <th>Due Date</th>
								   <th>#</th>
								</tr>
							 </thead>
							 <tbody id="data_content_source"></tbody>
						  </table>
					  </div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Total Source : <span class="badge badge-success" id="tempsource">0</span>
					&nbsp;
					Total Request : <span class="badge badge-danger" id="tempneeded">0</span>
					&nbsp;
					Balance : <span class="badge badge-info" id="tempbalance">0</span>
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
		
		select2ServerSide('#purchase_request_id', '{{ url("admin/select2/purchase_request") }}');
		
		$('#data_content_request').on('click', '#delete_data_content_request', function() {
			$(this).closest('tr').remove();
			count();
		});
		
		$('#data_content_source').on('click', '#delete_data_content_source', function() {
			$(this).closest('tr').remove();
			count();
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
			reset();
		});
	});
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/finance/payment_request/row_detail") }}',
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
	
	function filter(param = null) {
		if(param == 'reset') {
			resetFilter();
		}

		loadDataTable();
	}
	
	function resetFilter() {
		$('#filter_start_date').val(null);
		$('#filter_finish_date').val(null);
	}
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[4, 'desc']],
         ajax: {
            url: '{{ url("admin/finance/payment_request/datatable") }}',
            type: 'GET',
            data: {
			   start_date: $('#filter_start_date').val(),
               finish_date: $('#filter_finish_date').val()
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
			{ name: 'code', className: 'text-center align-middle' },
            { name: 'user', className: 'text-center align-middle' },
			{ name: 'check', className: 'text-center align-middle' },
            { name: 'approve', className: 'text-center align-middle' },
            { name: 'date', className: 'text-center align-middle' },
            { name: 'title', searchable: false, className: 'align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function addPurchaseRequest() {
		let prf = $('#purchase_request_id option:selected');
		let arr = prf.text().split("-");
		let nominal = arr[arr.length - 1];
		let source = $('#coa_id option:selected');
		let branch = $('#branch option:selected');
		
		if(prf.val()){
			$('#data_content_request').append(`
				<tr class="text-center">
				   <input type="hidden" name="purchase_arr[]" value="` + prf.val() + `">
				   <td class="align-middle">` + prf.text() + `</td>
				   <td class="align-middle"><input type="text" name="nominal_arr[]" class="form-control" placeholder="0" onkeyup="formatRupiah(this);count();" value="` + formatRupiahIni(nominal) + `"></td>
				   <td class="align-middle">
					  <button type="button" id="delete_data_content_request" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				   </td>
				</tr>
			`);
			
			$('#purchase_request_id').empty();
			
			count();
		}
	}
	
	function addSourcePayment() {
		let coa = $('#coa_id option:selected');
		let branch = $('#branch option:selected');
		let nominal = $('#nominal_source').val();

		$('#data_content_source').append(`
			<tr class="text-center">
			   <input type="hidden" name="coa_arr[]" value="` + coa.val() + `">
			   <input type="hidden" name="nominal_source_arr[]" value="` + nominal + `">
			   <input type="hidden" name="branch_source_arr[]" value="` + branch.val() + `">
			   <td class="align-middle">` + coa.text() + `</td>
			   <td class="align-middle">` + branch.text() + `</td>
			   <td class="align-middle">` + nominal + `</td>
			   <td class="align-middle"><input type="text" class="form-control-sm" name="code_arr[]" placeholder="Leave empty if not giro/cek"></td>
			   <td class="align-middle"><input type="date" class="form-control-sm" name="due_date_arr[]"></td>
			   <td class="align-middle">
				  <button type="button" id="delete_data_content_source" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
			   </td>
			</tr>
		`);
		
		$('#coa_id').val(null);
		
		count();
	}
	
	function count(){
		var needed = 0, source = 0;
		
		$('input[name^="nominal_arr"]').each(function(){
			needed = needed + parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
		});
		
		$('input[name^="nominal_source_arr"]').each(function(){
			source = source + parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
		});
		
		$('#tempneeded').html(formatRupiahIni(needed.toFixed(2).toString().replaceAll('.',',')));
		$('#tempsource').html(formatRupiahIni(source.toFixed(2).toString().replaceAll('.',',')));
		$('#tempbalance').html(formatRupiahIni((source - needed).toFixed(2).toString().replaceAll('.',',')));
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
	
	function reset() {
      $('#form_data').trigger('reset');
      $('#data_content_request').empty();
	  $('#data_content_source').empty();
	  $('#purchase_request_id').empty();
	  $('#coa_id').val('').trigger('change');
      $('#validation_alert').hide();
      $('#validation_content').html('');
	  $('#btn_create').show();
      $('#btn_update').hide();
      $('#btn_cancel').hide();
	}
	
	function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function create() {
		
		if($('input[name^="nominal_arr"]').length > 0){
			$.ajax({
			 url: '{{ url("admin/finance/payment_request/create") }}',
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
		}else{
			swalInit.fire({
			   title: 'Error!',
			   text: 'Please input purchase request or source of payment first.',
			   type: 'error'
			});
		}
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
	  
	  $.ajax({
		 url: '{{ url("admin/finance/payment_request/show") }}',
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
			 
			if(response.error == '422'){
				swalInit.fire({
				   title: 'Swuorry!',
				   text: 'This payment request already approved. Contact developer!',
				   type: 'error'
				});
			}else{
				toShow();
				
				//$('#title').val(response.title);
				$('#date').val(response.date);
				$('#note').val(response.note);
				
				$('#data_content_request').empty();
				
				$.each(response.detailrequest, function(i, val) {
					$('#data_content_request').append(`
						<tr class="text-center">
						   <input type="hidden" name="purchase_arr[]" value="` + val.id + `">
						   <input type="hidden" name="nominal_arr[]" value="` + val.nominal + `">
						   <td class="align-middle">` + val.item + `</td>
						   <td class="align-middle"><input type="text" name="nominal_arr[]" class="form-control" placeholder="0" onkeyup="formatRupiah(this);count();" value="` + formatRupiahIni(val.nominal) + `"></td>
						   <td class="align-middle">
							  <button type="button" id="delete_data_content_request" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
					`);
				});
				
				$('#data_content_source').empty();
				
				$.each(response.detailsource, function(i, val) {
					$('#data_content_source').append(`
						<tr class="text-center">
						   <input type="hidden" name="coa_arr[]" value="` + val.id + `">
						   <input type="hidden" name="nominal_source_arr[]" value="` + val.nominal + `">
						   <input type="hidden" name="branch_source_arr[]" value="` + val.branch + `">
						   <td class="align-middle">` + val.name + `</td>
						   <td class="align-middle">` + val.branchname + `</td>
						   <td class="align-middle">` + val.nominalview + `</td>
						   <td class="align-middle"><input type="text" class="form-control-sm" name="code_arr[]" placeholder="Leave empty if not giro/cek" value="` + val.code +`"></td>
						   <td class="align-middle"><input type="date" class="form-control-sm" name="due_date_arr[]" value="` + val.due_date + `"></td>
						   <td class="align-middle">
							  <button type="button" id="delete_data_content_source" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
						   </td>
						</tr>
					`);
				});
				
				$('#btn_update').attr('onclick', 'update(' + id + ')');
				
				count();
			}
			
			loadingClose('.modal-content');
		 },
		 error: function() {
			//cancel();
			loadingClose('.modal-content');
			swalInit.fire({
			   title: 'Server Error',
			   text: 'Please contact developer',
			   type: 'error'
			});
		 }
	  });
	}
	
	function update(id) {
		
	  if($('input[name^="nominal_arr"]').length > 0){	
		  $.ajax({
			 url: '{{ url("admin/finance/payment_request/update") }}' + '/' + id,
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
	  }else{
			swalInit.fire({
			   title: 'Error!',
			   text: 'Please input purchase request or source of payment first.',
			   type: 'error'
			});
		}
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
				  url: '{{ url("admin/finance/payment_request/destroy") }}',
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
	
	function uploadBukti(id,idpayment){
		var fd = new FormData(), files = $('#file' + id)[0].files;
		fd.append('id',id);
		fd.append('idpayment',idpayment);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		if(files.length > 0){
			$.ajax({
				url: '{{ url("admin/finance/payment_request/upload_proof") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					 loadingOpen('#cell' + id);
				},
				success: function(response) {
					$('#cell' + id).empty();
					if(response.status == '200'){
						$('#cell' + id).html(response.message);
					}else if(response.status == '400'){
						notif('error', 'bg-danger', response.message);
					}
					loadingClose('#cell' + id);
				},
				error: function() {
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}else{
			notif('error', 'bg-danger', 'Please choose file first.');
		}
	}
	
	function uploadBuktiSource(id,idsource){
		var fd = new FormData(), files = $('#filesource' + id)[0].files;
		fd.append('idsource',idsource);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		if(files.length > 0){
			$.ajax({
				url: '{{ url("admin/finance/payment_request/upload_proof_source") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					 loadingOpen('#cellsource' + id);
				},
				success: function(response) {
					$('#cellsource' + id).empty();
					if(response.status == '200'){
						$('#cellsource' + id).html(response.message);
					}else if(response.status == '400'){
						notif('error', 'bg-danger', response.message);
					}
					loadingClose('#cellsource' + id);
				},
				error: function() {
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}else{
			notif('error', 'bg-danger', 'Please choose file first.');
		}
	}
</script>