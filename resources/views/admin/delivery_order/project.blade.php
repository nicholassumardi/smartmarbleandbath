<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Delivery Order Project</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn bg-pink-400 btn-labeled btn-labeled-left mr-2" data-toggle="modal" data-target="#modal_notes">
						<b>
							<i class="icon-pencil4"></i>
						</b> A/R Notes
					</button>
					<button class="btn bg-teal-400 btn-labeled btn-labeled-left mr-2" data-toggle="modal" data-target="#modal_deposit">
						<b>
							<i class="icon-wallet"></i>
						</b> GOBLOG Deposit
					</button>
					<button class="btn bg-orange-400 btn-labeled btn-labeled-left mr-2" data-toggle="modal" data-target="#modal_tax">
						<b>
							<i class="icon-percent"></i>
						</b> Tax
					</button>
					<button class="btn bg-indigo-400 btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_report">
						<b>
							<i class="icon-file-excel"></i>
						</b> Items Report
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Data</a>
					<span class="breadcrumb-item active">Project</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-sm-6 col-xl-3">
						<div class="card card-body">
							<div class="media">
								<div class="mr-3 align-self-center">
									<i class="icon-cash4 icon-3x text-success-400"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="font-weight-semibold mb-0">{{ $totalpaid }}</h3>
									<span class="text-uppercase font-size-sm text-muted">Total Paid Delivery</span>
								</div>
							</div>
							<div class="mt-2 text-center">
								<button class="btn btn-primary btn-sm" onclick="detailPaid()"><i class="icon-file-spreadsheet mr-1"></i> See details</button>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xl-3">
						<div class="card card-body">
							<div class="media">
								<div class="mr-3 align-self-center">
									<i class="icon-basket icon-3x text-success-400"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="font-weight-semibold mb-0">{{ $totalunpaid }}</h3>
									<span class="text-uppercase font-size-sm text-muted">Total Unpaid Delivery</span>
								</div>
							</div>
							<div class="mt-2 text-center">
								<button class="btn btn-primary btn-sm" onclick="detailUnpaid()"><i class="icon-file-spreadsheet mr-1"></i> See details</button>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xl-3">
						<div class="card card-body">
							<div class="media">
								<div class="mr-3 align-self-center">
									<i class="icon-calendar52 icon-3x text-success-400"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="font-weight-semibold mb-0">{{ $totalagingbill }}</h3>
									<span class="text-uppercase font-size-sm text-muted">Total Aging Bill</span>
								</div>
							</div>
							<div class="mt-2 text-center">
								<button class="btn btn-primary btn-sm" onclick="detailBill()"><i class="icon-file-spreadsheet mr-1"></i> See details</button>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xl-3">
						<div class="card {{ $totalUndelivered > 0 ? 'blink-notification' : '' }} card-body">
							<div class="media">
								<div class="mr-3 align-self-center">
									<i class="icon-truck icon-3x text-success-400"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="font-weight-semibold mb-0">{{ $totalUndelivered }}</h3>
									<span class="text-uppercase font-size-sm text-muted">Undelivered & Unreceived</span>
								</div>
							</div>
							<div class="mt-2 text-center">
								<button class="btn btn-primary btn-sm" onclick="detailUndelivered()"><i class="icon-file-spreadsheet mr-1"></i> See details</button>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xl-3">
						<div class="card card-body">
							<div class="media">
								<div class="mr-3 align-self-center">
									<i class="icon-clippy icon-3x text-success-400"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="font-weight-semibold mb-0">{{ $totalunmatchso }}</h3>
									<span class="text-uppercase font-size-sm text-muted">Total Unmatch SO & PO</span>
								</div>
							</div>
							<div class="mt-2 text-center">
								<button class="btn btn-primary btn-sm" onclick="detailUnmatchSO()"><i class="icon-file-spreadsheet mr-1"></i> See details</button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<hr>
				</div>
				<h5 class="card-title">List Data Projects</h5>
				@if( App\Models\User::find(session('bo_id'))->branch == '1')
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <select name="filter_branch" id="filter_branch" class="custom-select">
								 <option value="">-- Choose --</option>
								 @foreach (DB::table('company_entities')->get() as $company)
								 	<option value="{{$company->id}}">{{$company->name}}</option>
								 @endforeach
							  </select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label>Other Filter :<span class="text-danger">*</span></label>
							  <select name="filter_other" id="filter_other" class="custom-select">
								 <option value="">-- None --</option>
								 <option value="1">Has Sales Return</option>
								 <option value="2">Has Empty Info Tax</option>
								 <option value="3">Has Empty Tax</option>
							  </select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group text-right">
								<label>&nbsp;</label>
								<div class="input-group">
									<button type="button" onclick="loadDataTable()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
									<button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="alert alert-danger alert-styled-left alert-dismissible">
								<span class="font-weight-semibold">Info!</span><b> Red rows means that the project doesn't have budgeting project.</b>
							</div>
						</div>
					</div>
				
				
				@endif
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>#</th>
							<th>No</th>
							<th>Code</th>
							<th>User</th>
							<th>Sales</th>
							<th>Customer</th>
							<th>Project</th>
							<th width="15%">Progress</th>
							<th width="15%">Pay/Deliver</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Report Detail <span id="judul"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12" id="data_report">
					
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_deposit" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Customer Deposit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" id="body_deposit">
				<h3>Choose date and process</h3>
				<hr>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<input type="date" name="filter_date" id="filter_date" class="form-control" value="{{ date('Y-m-d') }}">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
						  <select name="filter_branch_cd" id="filter_branch_cd" class="custom-select">
							 <option value="">Choose Branch</option>
							 @foreach (DB::table('company_entities')->get() as $company)
								 <option value="{{$company->id}}">{{$company->name}}</option>
							  @endforeach
						  </select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<button class="btn bg-success btn-block" onclick="showCustomerDeposit()"><i class="icon-search4"></i> Process</button>
						</div>
					</div>
					<div class="col-md-6">
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<span class="font-weight-semibold">Info!</span><b> Click on customer name / row to show details transactions.</b>
						</div>
					</div>
				</div>
				<hr>
				<h3>Result</h3>
				<hr>
				<div class="row">
					<div class="col-md-12" id="data_deposit">
						
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					<span class="badge badge-success">PTA</span>
					&nbsp;
					<span class="badge badge-danger">SMB</span>
					&nbsp;
					<span class="badge badge-primary">Unknown PTA</span>
					&nbsp;
					<span class="badge badge-info">Unknown SMB</span>
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_notes" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">A/R Notes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" id="body_deposit">
				<h3>Choose customer</h3>
				<hr>
				<form id="form_notes">
					<h5 class="card-title">Main Information</h5>
					<div class="alert alert-danger" id="validation_alert" style="display:none;">
						<ul id="validation_content"></ul>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-3">
							<div class="form-group">
								<select name="customer_id_notes" id="customer_id_notes"></select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<button class="btn bg-success btn-block" onclick="showCustomerDelivery();return false;"><i class="icon-search4"></i> Process</button>
							</div>
						</div>
						<div class="col-md-6">
							<div class="alert alert-info alert-styled-left alert-dismissible">
								<span class="font-weight-semibold">Info!</span><b> Choose customer first and press Process button to add notes.</b>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<textarea class="form-control" name="delivery_note" id="delivery_note" placeholder="Type here..." onkeyup="copyThis(this);"></textarea>
							</div>
						</div>
					</div>
					<hr>
					<h3>Result</h3>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
							   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
								  <thead class="bg-dark">
									 <tr class="text-center">
										<th>#</th>
										<th>DO No.</th>
										<th>SO No.</th>
										<th>Project</th>
										<th>Nominal (+Tax)</th>
										<th>Notes</th>
									 </tr>
								  </thead>
								  <tbody id="body-notes">
									
								  </tbody>
							   </table>
							</div>
						</div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" onclick="saveNotes()">Submit</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_report" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Report Delivery & Item Sales Price By Customer</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-6 text-center">
						<div class="row">
						  <div class="col-md-12">
							<h3 class="card-title">Search By Customer</h3>
							<div class="form-group">
								<label>Customer :<sup class="text-danger">*</sup></label>
								<select name="customer_id" id="customer_id"></select>
							</div>
						  </div>
						</div>
					</div>
					<div class="col-md-6 text-center">
						<div class="row">
						  <div class="col-md-12">
							<h3 class="card-title">Search By Product</h3>
							<div class="form-group">
								<label>Product :<sup class="text-danger">*</sup></label>
								<select name="product_id" id="product_id"></select>
							</div>
						  </div>
						</div>
					</div>
					<div class="col-md-12 text-center">
						<button class="btn bg-purple mr-2" id="showReport" onclick="showReport();">Generate <i class="icon-eye"></i></button>
						<button class="btn bg-danger" onclick="resetReport();">Reset <i class="icon-sync"></i></button>
					</div>
					<div class="col-md-12 text-center">
						<hr>
						<h3 class="card-title">Result</h3>
					</div>
					<div class="col-md-12" id="reportResult"></div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_tax" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Report Tax In (Masukan) & Out (Keluaran)</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="row justify-content-center">
							<div class="col-md-12 text-center">
								<h3>Tax In (Masukan)</h3>
								<div class="row justify-content-center">
									<div class="col-md-3">
										<div class="form-group">
											<label>From :<sup class="text-danger">*</sup></label>
											<input type="date" name="date_from_tax_in" id="date_from_tax_in" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>To :<sup class="text-danger">*</sup></label>
											<input type="date" name="date_to_tax_in" id="date_to_tax_in" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 text-center">
								<button class="btn bg-purple mr-2" id="showReportTaxIn" onclick="showReportTaxIn();">Generate <i class="icon-eye"></i></button>
								<button class="btn bg-danger" onclick="resetReportTaxIn();">Reset <i class="icon-sync"></i></button>
							</div>
							<div class="col-md-12 text-center">
								<hr>
								<h3 class="card-title">Result</h3>
							</div>
							<div class="col-md-12" id="reportResultTaxIn"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row justify-content-center">
							<div class="col-md-12 text-center">
								<h3>Tax Out (Keluaran)</h3>
								<div class="row justify-content-center">
									<div class="col-md-3">
										<div class="form-group">
											<label>From :<sup class="text-danger">*</sup></label>
											<input type="date" name="date_from_tax_out" id="date_from_tax_out" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>To :<sup class="text-danger">*</sup></label>
											<input type="date" name="date_to_tax_out" id="date_to_tax_out" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 text-center">
								<button class="btn bg-purple mr-2" id="showReportTaxOut" onclick="showReportTaxOut();">Generate <i class="icon-eye"></i></button>
								<button class="btn bg-danger" onclick="resetReportTaxOut();">Reset <i class="icon-sync"></i></button>
							</div>
							<div class="col-md-12 text-center">
								<hr>
								<h3 class="card-title">Result</h3>
							</div>
							<div class="col-md-12" id="reportResultTaxOut"></div>
						</div>
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
	function resetFilter() {
		$('#filter_branch').val(null);
		$('#filter_other').val('');
		loadDataTable();
	}
	
	function copyThis(element){
		$('textarea[name^="detail_notes"]').each(function(){
			if($('#checkNote' + $(this).data('id')).is(':checked')){
				$(this).val(element.value);
			}
		});
	}
	
	function clearNotes(id){
		if($('#checkNote' + id).is(':checked')){
			$('#detail_notes' + id).val($('#delivery_note').val());
		}else{
			$('#detail_notes' + id).val('');
		}
	}
	
	$(function() {
      loadDataTable();
	  
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
			$('#data_report').html('');
		});
		
		$('#modal_report').on('hidden.bs.modal', function (e) {
			resetReport();
		});
		
		$('#modal_deposit').on('hidden.bs.modal', function (e) {
			$('#data_deposit').html('');
		});
		
		select2ServerSide('#customer_id,#customer_id_notes', '{{ url("admin/select2/customer") }}');
		select2ServerSide('#product_id', '{{ url("admin/select2/product") }}');
	});
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/delivery_order/project/row_detail_delivery") }}',
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
	
   function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/delivery_order/project/datatable") }}',
            type: 'GET',
            data: {
               status: $('#filter_status').val(),
			   branch: $('#filter_branch').val(),
			   filter: $('#filter_other').val()
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
            { name: 'user_id', className: 'text-center align-middle' },
			{ name: 'sales', className: 'text-center align-middle' },
			{ name: 'customer', className: 'text-center align-middle' },
            { name: 'name', className: 'text-center align-middle' },
            { name: 'progress', searchable: false, className: 'text-center align-middle' },
			{ name: 'payment', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
		 "createdRow": function( row, data, dataIndex){
			if( data[10] == ''){
				$(row).addClass('bg-danger');
			}
		 },
      }); 
	}
   
	function showCustomerDeposit(){
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_customer_deposit") }}',
			type: 'GET',
			async: false,
			data: { filter_date : $('#filter_date').val(), filter_branch : $('#filter_branch_cd').val() },
			beforeSend: function() {
				loadingOpen('#body_deposit');
            },
			success: function(response) {
				$('#data_deposit').html('');
				$('#data_deposit').html(response.content);
				loadingClose('#body_deposit');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
				loadingClose('#body_deposit');
			}
		});
	}
	
	function showCustomerDelivery(){
		if($('#customer_id_notes').val()){
			$.ajax({
				url: '{{ url("admin/delivery_order/project/get_customer_delivery") }}',
				type: 'GET',
				async: false,
				data: { filter_customer : $('#customer_id_notes').val() },
				beforeSend: function() {
					loadingOpen('.modal-body');
				},
				success: function(response) {
					loadingClose('.modal-body');
					$('#body-notes').empty('');
					
					$.each(response.data, function(i, val) {
						$('#body-notes').append(`
							<tr class="text-center row-notes" data-id="` + val.do_id + `">
								<input type="hidden" name="detail_id[]" value="` + val.do_id + `">
								<td>
									<div class="form-check" style="padding-left: 0rem;">
										<label class="form-check-label">
										<input type="checkbox" class="form-check-input-styled-primary" onclick="clearNotes(` + val.do_id + `)" id="checkNote` + val.do_id + `" checked>
									</div>
								</td>
								<td>` + val.do_code + `</td>
								<td>` + val.so_code + `</td>
								<td>` + val.pj_code + `</td>
								<td class="text-right">` + val.nominal + `</td>
								<td>
									<textarea class="form-control" name="detail_notes[]" id="detail_notes` + val.do_id + `" data-id="` + val.do_id + `"></textarea>
								</td>
							</tr>
						`);
					});
				},
				error: function() {
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
					loadingClose('#body_deposit');
				}
			});
		}else{
			notif('error', 'bg-danger', 'You must choose customer.');
		}
	}
	
	function saveNotes(){
		$.ajax({
			 url: '{{ url("admin/delivery_order/project/add_multi_notes") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_notes')[0]),
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
				   $('#customer_id_notes').val(null).trigger('change');
				   $('#body-notes').empty();
				   $('#delivery_note').val('');
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
	
	function showReport(){
		var customer_id = $('#customer_id').val(), product_id = $('#product_id').val();
		
		if(!customer_id && !product_id){
			notif('error', 'bg-danger', 'You must choose customer or product.');
		}else{
			if(customer_id && product_id){
				notif('error', 'bg-danger', 'You must choose customer or product.');
			}else{
				loadingOpen('.modal-body');
				$.ajax({
					url: '{{ url("admin/delivery_order/project/get_items") }}',
					type: 'GET',
					async: false,
					data: {
						customer_id : customer_id,
						product_id : product_id
					},
					success: function(response) {
						$('#reportResult').html('');
						$('#reportResult').html(response.content);
						loadingClose('.modal-body');
					},
					error: function() {
						swalInit.fire({
						   title: 'Server Error',
						   text: 'Please contact developer',
						   type: 'error'
						});
					}
				});
			}
		}
	}
   
	function detailPaid() {
		$('#judul').html('Delivery Paid & Tax Information');
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_report") }}',
			type: 'GET',
			async: false,
			data: {
				mode : 'paid'
			},
			success: function(response) {
				$('#data_report').html(response.contentPaid);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_form').modal('toggle');
	}
   
   function detailUnpaid() {
		$('#judul').html('Delivery Unpaid');
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_report") }}',
			type: 'GET',
			async: false,
			data: {
				mode : 'unpaid'
			},
			success: function(response) {
				$('#data_report').html(response.contentUnpaid);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_form').modal('toggle');
   }
   
   function detailBill() {
		$('#judul').html('Bill Unpaid');
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_report") }}',
			type: 'GET',
			async: false,
			data: {
				mode : 'bill'
			},
			success: function(response) {
				$('#data_report').html(response.contentBill);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_form').modal('toggle');
   }
   
   function detailUndelivered() {
		$('#judul').html('Undelivered Project');
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_report") }}',
			type: 'GET',
			async: false,
			data: {
				mode : 'undelivered'
			},
			success: function(response) {
				$('#data_report').html(response.contentUndelivered);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_form').modal('toggle');
   }
   
	function resetReport() {
	   $('#customer_id').empty();
	   $('#product_id').empty();
	   $('#reportResult').html('');
	}
	
	function detailUnmatchSO() {
		$('#judul').html('Unmatch SO & PO');
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/delivery_order/project/get_report") }}',
			type: 'GET',
			async: false,
			data: {
				mode : 'unmatchso'
			},
			success: function(response) {
				$('#data_report').html(response.contentUnmatchSo);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_form').modal('toggle');
	}
	
	function showReportTaxOut(){
		if($('#date_from_tax_out').val() && $('#date_to_tax_out').val()){
			loadingOpen('.modal-body');
			$.ajax({
				url: '{{ url("admin/delivery_order/project/get_report_tax") }}',
				type: 'GET',
				async: false,
				data: {
					date_from : $('#date_from_tax_out').val(),
					date_to : $('#date_to_tax_out').val(),
					mode : 'tax_out'
				},
				success: function(response) {
					$('#reportResultTaxOut').html('');
					$('#reportResultTaxOut').html(response.content);
					loadingClose('.modal-body');
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
			notif('error', 'bg-danger', 'You must choose date from and to.');
		}
	}
	
	function showReportTaxIn(){
		if($('#date_from_tax_in').val() && $('#date_to_tax_in').val()){
			loadingOpen('.modal-body');
			$.ajax({
				url: '{{ url("admin/delivery_order/project/get_report_tax") }}',
				type: 'GET',
				async: false,
				data: {
					date_from : $('#date_from_tax_in').val(),
					date_to : $('#date_to_tax_in').val(),
					mode : 'tax_in'
				},
				success: function(response) {
					$('#reportResultTaxIn').html('');
					$('#reportResultTaxIn').html(response.content);
					loadingClose('.modal-body');
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
			notif('error', 'bg-danger', 'You must choose date from and to.');
		}
	}
</script>