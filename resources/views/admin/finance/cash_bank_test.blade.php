<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Cash & Bank</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-danger btn-labeled mr-2 btn-labeled-left {{ $unmatch > 0 ? 'blink-notification' : '' }}" onclick="unmatchJournal()">
						<b><i class="icon-wrench"></i></b> Unmatch Journal ({{ $unmatch }})
					</button>
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="filter()">
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
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Cash & Bank</span>
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
            <div class="row">
               <div class="col-md-3">
                  <div class="form-group">
                     <label>User :</label>
                     <select name="filter_user_id" id="filter_user_id" class="select2">
                        <option value="">All</option>
                        @foreach($user as $u)
                           <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               
               <div class="col-md-8">
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
               </div>
            </div>
            <div class="form-group">
               <div class="form-check form-check-inline">
                  <label class="form-check-label">
                     <input type="radio" name="filter_type" class="form-check-input" value="" checked>
                     All
                  </label>
               </div>
               <div class="form-check form-check-inline">
                  <label class="form-check-label">
                     <input type="radio" name="filter_type" value="1" class="form-check-input">
                     Cash / Bank In
                  </label>
               </div>
               <div class="form-check form-check-inline">
                  <label class="form-check-label">
                     <input type="radio" name="filter_type" value="2" class="form-check-input">
                     Cash / Bank Out
                  </label>
               </div>
               <div class="form-check form-check-inline">
                  <label class="form-check-label">
                     <input type="radio" name="filter_type" value="3" class="form-check-input">
                     Journal
                  </label>
               </div>
			   <div class="form-check form-check-inline">
                  <label class="form-check-label">
                     <input type="radio" name="filter_type" value="4" class="form-check-input">
                     Receivable
                  </label>
               </div>
			   <div class="form-check form-check-inline">
                  <label class="form-check-label">
                     <input type="radio" name="filter_type" value="5" class="form-check-input">
                     Payable
                  </label>
               </div>
            </div>
         </div>
      </div>
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>#</th>
                        <th>No</th>
                        <th>User</th>
						<th>Cust/Supp</th>
                        <th>Code</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Description</th>
						<th>Ref</th>
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
            <h5 class="modal-title" id="exampleModalLabel">Form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ url("admin/finance/cash_bank/update", 10160)}}" id="form-test" method="POST">
               <div class="alert alert-danger" id="validation_alert" style="display:none;">
                  <ul id="validation_content"></ul>
               </div>
				<div class="row">
                  <div class="col-md-12 text-center">
					<div id="proof-material">
						
					</div>
				  </div>
				</div>
				<h5 class="card-title text-center"><b>Main Information</b></h5>
				<div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Code :<sup class="text-danger">*</sup> <i>(Auto generate on XX-XXXX format)</i></label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="Enter code" onkeyup="getCode(this.value);">
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
					  <textarea name="description" id="description" class="form-control" placeholder="Enter description" rows="1" onkeyup="copyToNote(this.value)"></textarea>
					</div>
				  </div>
				</div>
				<div class="form-group"><hr></div>
				<h5 class="card-title text-center"><b>Receivable/Payable Outside The Project (Optional)</b></h5>
				<div class="row justify-content-center">
					<div class="col-md-4">
                     <div class="form-group">
                        <label>Supplier. :</label>
                        <select name="supplier_id" id="supplier_id"></select>
                     </div>
					</div>
					<div class="col-md-1 text-center pt-4">
						OR
					</div>
					<div class="col-md-4">
                     <div class="form-group">
                        <label>Customer. :</label>
                        <select name="customer_id" id="customer_id"></select>
                     </div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-4">
                     <div class="form-group">
                        <label>Receipt No. :</label>
                        <input type="text" name="receipt" id="receipt" class="form-control" placeholder="Enter receipt">
                     </div>
					</div>
					<div class="col-md-4">
                     <div class="form-group">
                        <label>Request Date :</label>
                        <input type="date" name="request_date" id="request_date" class="form-control">
                     </div>
					</div>
					<div class="col-md-4">
                     <div class="form-group">
                        <label>Due Date :</label>
                        <input type="date" name="due_date" id="due_date" class="form-control">
                     </div>
					</div>
					<div class="col-md-4">
                     <div class="form-group">
                        <label>Proof :</label>
                        <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                     </div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-4">
						<div class="form-group"><hr></div>
						<h5 class="card-title text-center"><b>Project Link (Optional)</b></h5>
						<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
							<span class="font-weight-semibold">Important Info!</span> 
							Use this only to pay off outstanding A/R or add costs to specific project. Do not use this link to existing project delivery.
						</div>
						<div class="form-group">
							<label>Project :</label>
							<select name="project_id" id="project_id"></select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group"><hr></div>
						<h5 class="card-title text-center"><b>Purchase Order Link (Optional)</b></h5>
						<div class="alert alert-success alert-styled-left alert-dismissible mt-3">
							<span class="font-weight-semibold">Important Info!</span> 
							Use this only to pay off outstanding A/P or add costs to specific project. Do not use this link to existing project warehouse receive.
						</div>
						<div class="form-group">
							<label>Purchase Order :</label>
							<select name="purchase_id" id="purchase_id"></select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group"><hr></div>
						<h5 class="card-title text-center"><b>Purchase Request Link (Optional)</b></h5>
						<div class="alert alert-danger alert-styled-left alert-dismissible mt-3">
							<span class="font-weight-semibold">Important Info!</span> 
							Use this only to automatically add debit payable IDR purchase request based on Purchase Request branch and nominal into Details Coa table.
						</div>
						<div class="form-group">
							<label>Purchase Request :</label>
							<select name="purchase_request_id" id="purchase_request_id" onchange="getDebitPayable()"></select>
						</div>
					</div>
				</div>
			   <div class="form-group"><hr></div>
			   <h5 class="card-title text-center"><b>Details Coa</b></h5>
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
						 <option value="1">PTA</option>
						 <option value="2">SMB</option>
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
				@csrf
				<div class="row">
					<div class="form-group col-md-6">
						<table class="table table-bordered">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>Debit</th>
							   <th>Branch</th>
							   <th>Nominal</th>
							   <th>Note</th>
							   <th>Sum</th>
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
							   <th>Sum</th>
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
				  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="type" value="4">
                        Receivable
                     </label>
                  </div>
				  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="type" value="5">
                        Payable
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
	   $('.sidebar-main-toggle').click();
	   $('#supplier_id').on('change', function(){
			if($(this).val() == null){
				$('#customer_id').attr("disabled", false);
			}else{
				$('#customer_id').val(null).trigger('change');
				$('#customer_id').attr("disabled", true);
			}
	   });
	   $('#customer_id').on('change', function(){
			if($(this).val() == null){
			   $('#supplier_id').attr("disabled", false);
			}else{
				$('#supplier_id').val(null).trigger('change');
				$('#supplier_id').attr("disabled", true);
			}
			
	   });
      filter();

      /* $('#code').autocomplete({
         appendTo: '#modal_form',
         autoFocus: true,
         source: function(request, response) {
            $.get('{{ url("admin/finance/cash_bank/suggest_code") }}', { 
               search: request.term
            }, function(data) {
               response(data);
            });
         }
      }); */
	  
	  select2ServerSide('#supplier_id', '{{ url("admin/select2/supplier") }}');
	  select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
	  select2ServerSide('#purchase_id', '{{ url("admin/select2/project_purchase") }}');
	  select2ServerSide('#project_id', '{{ url("admin/select2/project") }}');
	  select2ServerSide('#purchase_request_id', '{{ url("admin/select2/purchase_request") }}');

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
      
      $('#data_content_debit').on('click', '#delete_data_content_debit', function() {
         $(this).closest('tr').remove();
		 countDebit()
      });
	  $('#data_content_credit').on('click', '#delete_data_content_credit', function() {
         $(this).closest('tr').remove();
		 countDebit()
      });
	  
	  @if($edit)
		show({{ $id }});
	  @endif
   });
   
	function getCode(val){
		if(val.length == 7){
			$.ajax({
				 url: '{{ url("admin/finance/cash_bank/get_code") }}',
				 type: 'GET',
				 dataType: 'JSON',
				 data: {
					val: val
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					
					$('#code').val(response.code);
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
	}
	
	function getDebitPayable(){
		if($('#purchase_request_id').val() !== ''){
			var branch = $("#purchase_request_id").select2('data')[0].branch;
			var nominal = $("#purchase_request_id").select2('data')[0].nominal;
			var id = $('#purchase_request_id').select2('data')[0].id;
			
			$('#data_content_debit').append(`
				<tr class="text-center">
				   <input type="hidden" name="coa_detail[]" value="332">
				   <input type="hidden" name="type_detail[]" value="1">
				   <input type="hidden" name="branch_detail[]" data-mode="1" value="` + branch + `">

				   <td class="align-middle">Payable IDR</td>
				   <td class="align-middle">` + (branch == '1' ? 'PTA' : 'SMB') + `</td>
				   <td class="align-middle">
					  <div class="form-group">
						 <input type="text" name="nominal_detail[]" data-mode="1" class="form-control" placeholder="0" value="` + nominal + `" onkeyup="formatRupiah(this);countDebit();">
					  </div>
				   </td>
				   <td class="align-middle"><input type="text" name="note_detail[]" value="Purchase Request Acquittance No ` + id + `" class="form-control"></td>   
				   <td class="align-middle">
					  <button type="button" id="delete_data_content_debit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
				   </td>
				</tr>
			`);
		}
	}
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/finance/cash_bank/row_detail") }}',
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
				   <input type="hidden" name="branch_detail[]" data-mode="` + mode + `" value="` + branch.val() + `">

				   <td class="align-middle">` + coa_id.text() + `</td>
				   <td class="align-middle">` + branch.text() + `</td>
				   <td class="align-middle">
					  <div class="form-group">
						 <input type="text" name="nominal_detail[]" data-mode="` + mode + `" class="form-control" placeholder="0" value="` + nominal_detail.val() + `" onkeyup="formatRupiah(this);countDebit();">
					  </div>
				   </td>
				   <td class="align-middle"><input type="text" name="note_detail[]" value="` + note_detail.val() + `" class="form-control"></td>
				   <td class="align-middle">
						<div class="form-check" style="padding-left: 0rem;">
							<label class="form-check-label">
							<input type="checkbox" class="form-check-input-styled-primary" value="` + nominal_detail.val() + `" name="credit_checkbox[]">
						</div>
				   </td>
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
				   <input type="hidden" name="branch_detail[]" data-mode="` + mode + `" value="` + branch.val() + `">

				   <td class="align-middle">` + coa_id.text() + `</td>
				   <td class="align-middle">` + branch.text() + `</td>
				   <td class="align-middle">
					  <div class="form-group">
						 <input type="text" name="nominal_detail[]" data-mode="` + mode + `" class="form-control" placeholder="0" value="` + nominal_detail.val() + `" onkeyup="formatRupiah(this);countDebit();">
					  </div>
				   </td>
				   <td class="align-middle"><input type="text" name="note_detail[]" value="` + note_detail.val() + `" class="form-control"></td>
				   <td class="align-middle">
						<div class="form-check" style="padding-left: 0rem;">
							<label class="form-check-label">
							<input type="checkbox" class="form-check-input-styled-primary" value="` + nominal_detail.val() + `" name="credit_checkbox[]">
						</div>
				   </td>
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

   function cancel() {
      reset();
      $('#modal_form').modal('hide');
      $('#btn_create').show();
      $('#btn_update').hide();
      $('#btn_cancel').hide();
	  countDebit();
	  $('#proof-material').html('');
   }

   function toShow() {
      $('#data_content_credit').html('');
	  $('#data_content_debit').html('');
      $('#modal_form').modal('show');
      $('#validation_alert').hide();
      $('#validation_content').html('');
      $('#btn_create').hide();
      $('#btn_update').show();
      $('#btn_cancel').show();
   }

   function resetFilter() {
      $('#filter_user_id').val(null).trigger('change');
      $('#filter_start_date').val(null);
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
   }

   function reset() {
      $('#form_data').trigger('reset');
      $('#data_content_debit').html('');
	  $('#data_content_credit').html('');
	  $('#project_id').empty();
	  $('#project_detail').empty().append(`<option value="">-- None --</option>`);
	  $('#project_detail').val('').change();
      $('input[name="type"][value="1"]').prop('checked', true);
      $('#validation_alert').hide();
      $('#validation_content').html('');
	  $('#proof-material').html('');
	  $('#purchase_request_id').empty();
	  $('#coa_id').val($("#coa_id option:first").val()).trigger('change');
	  $('#purchase_id').empty();
   }

	function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}

	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/finance/cash_bank/datatable") }}',
            type: 'GET',
            data: {
               user_id: $('#filter_user_id').val(),
               start_date: $('#filter_start_date').val(),
               finish_date: $('#filter_finish_date').val(),
               start_nominal: $('#filter_start_nominal').val(),
               finish_nominal: $('#filter_finish_nominal').val(),
               type: $('input[name="filter_type"]:checked').val()
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
            { name: 'user_id', className: 'text-center align-middle' },
			{ name: 'customer_id', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'code', className: 'text-center align-middle nowrap' },
            { name: 'total', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
            { name: 'date', searchable: false, className: 'text-center align-middle' },
            { name: 'description', className: 'text-center align-middle' },
			{ name: 'ref', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'proof', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function unmatchJournal() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/finance/cash_bank/datatable") }}',
            type: 'GET',
            data: {
				user_id: $('#filter_user_id').val(),
				start_date: $('#filter_start_date').val(),
				finish_date: $('#filter_finish_date').val(),
				start_nominal: $('#filter_start_nominal').val(),
				finish_nominal: $('#filter_finish_nominal').val(),
				type: $('input[name="filter_type"]:checked').val(),
				mode : 'unmatch'
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
            { name: 'user_id', className: 'text-center align-middle' },
			{ name: 'customer_id', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'code', className: 'text-center align-middle nowrap' },
            { name: 'total', searchable: false, orderable: false, className: 'text-center align-middle nowrap' },
            { name: 'date', searchable: false, className: 'text-center align-middle' },
            { name: 'description', className: 'text-center align-middle' },
			{ name: 'ref', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
			{ name: 'proof', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
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
				debit = debit + parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
			}else if($(this).data('mode') == '2'){
				credit = credit + parseFloat($(this).val().replaceAll(".", "").replaceAll(",","."));
			}
		});
		
		var debetsby = 0, debetjkt = 0, kreditsby = 0, kreditjkt = 0, satusbysatujkt = false, multibranch = false, jumDebet = 0, jumKredit = 0;
		
		$('input[name^="branch_detail"]').each(function(){
			if($(this).val() == '1' && $(this).data('mode') == '1'){
				debetsby++;
				jumDebet++;
			}else if($(this).val() == '1' && $(this).data('mode') == '2'){
				kreditsby++;
				jumKredit++;
			}else if($(this).val() == '2' && $(this).data('mode') == '1'){
				debetjkt++;
				jumDebet++;
			}else if($(this).val() == '2' && $(this).data('mode') == '2'){
				kreditjkt++;
				jumKredit++;
			}
		});
		
		if(jumDebet == 1 && jumKredit == 1){
			if((debetsby == 1 && kreditjkt == 1) || (debetjkt == 1 && kreditsby == 1)){
				satusbysatujkt = true;
			}
		}
		
		if(jumDebet > 1 && jumKredit > 1){
			if((debetsby !== kreditsby) || (debetjkt !== kreditjkt)){
				multibranch = true;
			}
			
			if((debetsby > 0 && kreditsby > 0 && debetjkt == 0 && kreditjkt == 0) || (debetjkt > 0 && kreditjkt > 0 && debetsby == 0 && kreditsby == 0)){
				multibranch = false;
			}
		}
		
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
		fd.append('supplier_id',$('#supplier_id').val());
		fd.append('customer_id',$('#customer_id').val());
		fd.append('receipt',$('#receipt').val());
		fd.append('request_date',$('#request_date').val());
		fd.append('due_date',$('#due_date').val());
		fd.append('project_id',$('#project_id').val());
		fd.append('purchase_id',$('#purchase_id').val());
		fd.append('type',$('input[name="type"]:checked').val());
		fd.append('purchase_request_id',$('#purchase_request_id').val());
		
		for(var i = 0; i < coa_details.length; i++){
            fd.append(coa_details[i].name, coa_details[i].value);
			fd.append(branch_details[i].name, branch_details[i].value);
			fd.append(type_details[i].name, type_details[i].value);
			fd.append(note_details[i].name, note_details[i].value);
			fd.append(nominal_details[i].name, nominal_details[i].value);
        }
		
		if((debit.toFixed(0) - credit.toFixed(0)) == '0'){
			if(satusbysatujkt == false && multibranch == false){
				$.ajax({
				 url: '{{ url("admin/finance/cash_bank/create") }}',
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
			}else{
				notif('error', 'bg-danger', 'Your branch are not same.');
			}
		}else{
			notif('error', 'bg-danger', 'Your input is not balanced.');
		}
	}

	function show(id) {
      toShow();
      $.ajax({
         url: '{{ url("admin/finance/cash_bank/show") }}',
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
			
            $('#code').val(response.code);
            $('#date').val(response.date);
            $('#description').val(response.description);
			$('#supplier_id,#customer_id').empty();
			
			if(response.lookable_type == 'project_purchases'){
				$('#purchase_id').empty();
				$('#purchase_id').append(`
					<option value="` + response.lookable_id + `">` + response.purchase_info + `</option>
				`);
			}
			
			if(response.lookable_type == 'projects'){
				$('#project_id').empty();
				$('#project_id').append(`
					<option value="` + response.lookable_id + `">` + response.project_info + `</option>
				`);
			}
			
			if(response.purchase_request_ref){
				$('#purchase_request_id').empty();
				$('#purchase_request_id').append(`
					<option value="` + response.purchase_request_ref + `">` + response.purchase_request_info + `</option>
				`);
			}
			
			if(response.customer_id){
				$('#customer_id').append(`
					<option value="` + response.customer_id + `">` + response.customer_name + `</option>
				`);
			}
			if(response.supplier_id){
				$('#supplier_id').append(`
					<option value="` + response.supplier_id + `">` + response.supplier_name + `</option>
				`);
			}
			$('#receipt').val(response.no_nota);
			$('#request_date').val(response.request_date);
			$('#due_date').val(response.due_date);
			$('#due_date').val(response.due_date);
            $('input[name="type"][value="' + response.type + '"]').prop('checked', true);
			$('#proof-material').html(response.proof);
			
			$('#data_content_debit').empty();
			$('#data_content_credit').empty();
			
			var counter = 1;
            $.each(response.cash_bank_detail, function(i, val) {
				if(val.type == '1'){
					$('#data_content_debit').append(`
						<tr class="text-center">
						   <input type="hidden" name="coa_detail[]" value="` + val.coa_id + `">
						   <input type="hidden" name="branch_detail[]" data-mode="` + val.type + `" value="` + val.branch_id + `">
						   <input type="hidden" name="type_detail[]" value="` + val.type + `">

						   <td class="align-middle">` + val.coa_info + `</td>
						   <td class="align-middle">` + val.branch_name + `</td>
						   <td class="align-middle">
							  <div class="form-group">
								 <input type="text" name="nominal_detail[]" data-mode="` + val.type + `" class="form-control" placeholder="0" value="` + val.nominal + `" onkeyup="formatRupiah(this);countDebit();">
							  </div>
						   </td>   
						   <td class="align-middle"><input type="text" name="note_detail[]" value="` + val.note + `" class="form-control"></td>   
						   <td class="align-middle">
								<div class="form-check" style="padding-left: 0rem;">
									<label class="form-check-label">
									<input type="checkbox" class="form-check-input-styled-primary" value="` + val.nominal + `"  name="credit_checkbox[]" ` + (val.checked ? val.checked : ((counter == 1 ? `checked` : ``))) + `>
								</div>
						   </td>
						   <td class="align-middle">
							  <button type="button" id="delete_data_content_debit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
					`);
				 }else if(val.type == '2'){
					$('#data_content_credit').append(`
						<tr class="text-center">
						   <input type="hidden" name="coa_detail[]" value="` + val.coa_id + `">
						   <input type="hidden" name="branch_detail[]" data-mode="` + val.type + `" value="` + val.branch_id + `">
						   <input type="hidden" name="type_detail[]" value="` + val.type + `">

						   <td class="align-middle">` + val.coa_info + `</td>
						   <td class="align-middle">` + val.branch_name + `</td>
						   <td class="align-middle">
							  <div class="form-group">
								 <input type="text" name="nominal_detail[]" data-mode="` + val.type + `" class="form-control" placeholder="0" value="` + val.nominal + `" onkeyup="formatRupiah(this);countDebit();">
							  </div>
						   </td>   
						   <td class="align-middle"><input type="text" name="note_detail[]" value="` + val.note + `" class="form-control"></td>
						   <td class="align-middle">
								<div class="form-check" style="padding-left: 0rem;">
									<label class="form-check-label">
									<input type="checkbox" class="form-check-input-styled-primary" value="` + val.nominal + `"  name="credit_checkbox[]" ` + (val.checked ? val.checked : ((counter == 1 ? `checked` : ``))) + `>
								</div>
						   </td>
						   <td class="align-middle">
								<button type="button" id="delete_data_content_credit" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						   </td>
						</tr>
					`);
					
					counter++;
				 }
            });
			
			countDebit();
            $('#btn_update').attr('onclick', 'update(' + id + ')');
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


   function update(id) {
		$('#form-test').submit();
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
                  url: '{{ url("admin/finance/cash_bank/destroy") }}',
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
	
	function copyToNote(val){
		$('#note_detail').val(val);
	}
</script>