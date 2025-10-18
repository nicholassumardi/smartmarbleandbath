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
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Receivable Payment</span>
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
						<th>C&B</th>
						<th>Balance</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Convert to Cash & Banks</h5>
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
							<input type="hidden" name="is_receivable_payment" id="is_receivable_payment" class="form-control" value="1">
							<input type="text" name="code" id="code" class="form-control" placeholder="Enter code" readonly>
							<span class="badge d-block badge-danger form-text">Do not change this code, as it is used to connect Receivable Payment and Cash & Banks</span>
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
				   <div class="form-group justify-content-center">
						<div class="col-lg-4 mx-auto">
							<span class="badge d-block badge-danger form-text">ADD TO BALANCE CASH & BANK ?</span>
							<div class="form-check mt-3">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input-styled" data-fouc name="include_balance" id="include_balance" value="1">
									Check this one out!
								</label>
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
	
<script>
	$(function() {
		loadDataTable();
		
		$('#data_content_debit').on('click', '#delete_data_content_debit', function() {
			$(this).closest('tr').remove();
			countDebit();
		});
		$('#data_content_credit').on('click', '#delete_data_content_credit', function() {
			$(this).closest('tr').remove();
			countDebit();
		});
		
		$('.sidebar-main-toggle').click();
		
		$("#datatable_serverside").on( "click", 'tbody tr .btn-pindah', function() {
		  var id = $(this).data('id'),tgl = $(this).data('tgl'), nominal = $(this).data('nominal'), note = $(this).data('note'), coa = $(this).data('coa'), coaname = $(this).data('coaname');
		  
		  $('#code').val('ORP-' + id);
		  $('#date').val(tgl);
		  $('#nominal_detail').val(nominal);
		  $('#nominal_detail').keyup();
		  $('#description').val(note);
		  $('#note_detail').val(note);
		  $('#coa_id').val(coa).trigger('change');
		  
		  $('#data_content_credit').empty();
		  $('#data_content_debit').empty();
		  
		  $('#modal_form').modal('toggle');
		});
		
		$('.sidebar-main-toggle').click();
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$( "#include_balance" ).prop( "checked", false );
			$('.uniform-checker > span').removeClass('checked');
		});
	});
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'desc']],
         ajax: {
            url: '{{ url("admin/finance/receivable_payment/datatable") }}',
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
			{ name: 'action', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
			{ name: 'balance', searchable: false, orderable: false, className: 'text-center align-middle nowrap' }
         ]
      }); 
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
					$('#form_data').trigger('reset');
					$('#data_content_credit').empty();
					$('#data_content_debit').empty();
					$('#validation_alert').hide();
					$('#validation_content').html('');
					$('#modal_form').modal('toggle');
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
		}else{
			notif('error', 'bg-danger', 'Your input is not balanced.');
		}
	}
</script>