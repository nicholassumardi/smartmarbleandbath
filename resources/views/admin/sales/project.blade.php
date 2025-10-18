<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Sales Project</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-labeled btn-labeled-left mr-2" onclick="reset()" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Add
					</button>
					<button class="btn bg-pink-400 btn-labeled btn-labeled-left" onclick="printSalesReport3()">
						<b><i class="icon-file-pdf"></i></b> Sales Report 
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
				<h5 class="card-title">List Data Projects</h5>
					<div class="row">
						@if( in_array(1, session('bo_role')))
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
								<label>Sales :<sup class="text-danger">*</sup></label>
								<select name="sales_id" id="sales_id"></select>
							  </select>
							</div>
						</div>
						@endif
						<div class="col-md-3">
							<div class="form-group">
								<label>Mode :<sup class="text-danger">*</sup></label>
								<select name="mode" id="mode" class="custom-select">
									<option value="">-- Choose --</option>
									<option value="1">Product</option>
									<option value="2">Payment</option>
									<option value="3">Customer List</option>
								 </select>
							  </select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>&nbsp;</label>
								<div class="input-group">
									<button type="button" onclick="loadDataTable()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
									<button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
								</div>
							</div>
						</div>
					</div>
				<div class="row">
					<div class="col-md-6">
						<div class="alert alert-danger alert-styled-left alert-dismissible">
							<span class="font-weight-semibold">Info!</span><b> Red rows means that the project doesn't have budgeting project.</b>
						</div>
					</div>
					<div class="col-md-6">
						<div class="alert alert-warning alert-styled-left alert-dismissible bg-warning">
							<span class="font-weight-semibold">Info!</span><b> Orange rows means that the project had been rejected.</b>
						</div>
					</div>
					<div class="col-md-6">
						<div class="alert alert-warning alert-styled-left alert-dismissible bg-secondary">
							<span class="font-weight-semibold">Info!</span><b> Gray rows means that the project SO had been closed.</b>
						</div>
					</div>
				</div>
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
							<th>Progress</th>
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
            <form id="form_data">
               <div class="alert alert-danger" id="validation_alert" style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                  <ul id="validation_content"></ul>
               </div>
               <div class="form-group">
                  <label>Project Name :<sup class="text-danger">*</sup></label>
				  <input type="hidden" name="temp_project" id="temp_project">
                  <input type="text" name="project_name" id="project_name" class="form-control" placeholder="Enter project name">
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Customer :<sup class="text-danger">*</sup></label>
                        <select name="customer_id" id="customer_id" class="select2">
                           <option value="">-- Choose --</option>
                           @foreach($customer as $c)
                              <option value="{{ $c->id }}">{{ $c->name.' - '.$c->email }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Sales :<sup class="text-danger">*</sup></label>
                        <select name="sales_project_id" id="sales_project_id" class="select2">
                           <option value="">-- Choose --</option>
                        </select>
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
                        <label>City of Project :<sup class="text-danger">*</sup></label>
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
                        <label>City of Franco :<sup class="text-danger">*</sup></label>
                        <select name="city_franco_id" id="city_franco_id" class="select2">
                           <option value="">-- Choose --</option>
                           @foreach($city as $c)
                              <option value="{{ $c->id }}">{{ $c->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Timeline :<sup class="text-danger">*</sup></label>
                        <input type="date" name="timeline" id="timeline" class="form-control">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>PIC :<sup class="text-danger">*</sup></label>
                        <input type="text" name="manager" id="manager" class="form-control" placeholder="Enter project manager" value="none">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Consultant Name :<sup class="text-danger">*</sup></label>
                        <input type="text" name="consultant" id="consultant" class="form-control" placeholder="Enter consultant name" value="none">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Owner :<sup class="text-danger">*</sup></label>
                        <input type="text" name="owner" id="owner" class="form-control" placeholder="Enter owner" value="none">
                     </div>
                  </div>
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
											@if(session('bo_branch') == 1)
												@if(in_array($bcc->code,array('1.000.03.01.01','1.000.03.01.03','1.000.03.02.01','1.000.03.02.02','1.000.03.03.05','1.000.03.03.06', '1.000.03.01.05')))
												<option value="{{ $bcc->id }}">{{ $bcc->name }}</option>
												@endif
											@else
												@if(in_array($bcc->code,array('1.000.03.01.01','1.000.03.01.03','1.000.03.02.01','1.000.03.02.02','1.000.03.03.05','1.000.03.03.06')))
												<option value="{{ $bcc->id }}">{{ $bcc->name }}</option>
												@endif
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
                        <label>Supply Method :<sup class="text-danger">*</sup></label>
                        <select name="supply_method" id="supply_method" class="custom-select">
                           <option value="">-- Choose --</option>
                           <option value="1">Full</option>
                           <option value="2">Partial</option>
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
						   <option value="75">75 Days</option>
						   <option value="90">90 Days</option>
						   <option value="120">120 Days</option>
						   <option value="180">180 Days</option>
						</select>
					 </div>
				  </div>
				  <div class="col-md-4">
                     <div class="form-group">
                        <label>Discount :<sup class="text-danger">*</sup></label>
                        <input type="text" name="discount" id="discount" class="form-control" value="0" onkeyup="formatRupiah(this);">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>PPN :<sup class="text-danger">*</sup></label>
                        <select name="ppn" id="ppn" class="custom-select">
                           <option value="">-- Choose --</option>
                           <option value="1">Yes</option>
                           <option value="0">No</option>
                        </select>
                     </div>
                  </div>
                     <div class="col-md-4">
                     <div class="form-group">
                        <label>Remark : </label>
						<input type="text" name="remark" id="remark" class="form-control">
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
            <button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i class="icon-plus3"></i> Save</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modal_pictures" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Add Project Photos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <input type="hidden" name="tempProject" id="tempProject">
			<p class="mb-3">You may upload finished results of installed Tiles or Sanitary. <b>Max size : 1 Mb / 1024 Kb, Max photos : 3.</b></p>

			<p class="font-weight-semibold">Multiple file upload :</p>
			<form action="{{ url('admin/sales/project/add_pictures') }}" class="dropzone" id="dropzone_multiple">
				@csrf
			</form>
			<div class="row mt-3" id="list-images">
			
			</div>
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modal_close_sales_order" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Close Sales Order</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
			<div class="alert alert-info alert-styled-left alert-dismissible bg-info">
				<span class="font-weight-semibold">Info!</span><b> Closed Sales Order will not be shown in Dashboard, any Unmatch or Pending delivery.</b>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped w-100">
					<thead class="bg-dark">
						<tr class="text-center">
							<th>#</th>
							<th>Code</th>
							<th>Sales</th>
							<th>Customer</th>
							<th>Nominal(A.Tax)</th>
							<th>Close</th>
							<th>Reason</th>
							<th>Save</th>
							<th>Approval</th>
						</tr>
					</thead>
					<tbody id="data_sales_order">
						
					</tbody>
				</table>
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
		select2ServerSide('#sales_id', '{{ url("admin/select2/user") }}');
		select2ServerSide('#sales_project_id', '{{ url("admin/select2/user") }}');
	});

	function resetFilter() {
		$('#filter_branch').val(null);
		$('#sales_id').val(null);
		loadDataTable();
	}
	
	function editProject(id){
		$.ajax({
			url: '{{ url("admin/sales/project/get_project") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				
			 },
			 success: function(response) {
				if(response){
					if(response.status == '200'){
						$('#modal_form').modal('toggle');
						$('#temp_project').val(id);
						if (response.sales_id) {
							$('#sales_project_id').empty();
							$('#sales_project_id').append(`
								<option value="` + response.sales_id + `">` + response.sales_name + `</option>
							`);
						}
						$('#project_name').val(response.name);
						$('#customer_id').val(response.customer_id).trigger('change');
						$('#country_id').val(response.country_id).trigger('change');
						$('#city_id').val(response.city_id).trigger('change');
						$('#city_franco_id').val(response.city_franco_id).trigger('change');
						$('#timeline').val(response.timeline);
						$('#manager').val(response.manager);
						$('#consultant').val(response.consultant);
						$('#owner').val(response.owner);
						$('#bank_id').val(response.coa_id).trigger('change');
						$('#supply_method').val(response.supply_method);
						$('#discount').val(response.discount);
						
						if(response.payment_method == '11' || response.payment_method == '12' || response.payment_method == '13'){
							$('#payment_method').val('1').trigger('change');
						}else if(response.payment_method == '21' || response.payment_method == '22' || response.payment_method == '23' || response.payment_method == '24' || response.payment_method == '25'){
							$('#payment_method').val('2').trigger('change');
						}
						
						$('#detail_payment').val(response.payment_method);
						$('#term_payment').val(response.term_payment);
						$('#ppn').val(response.ppn);
						$('#remark').val(response.remark);
					}else if(response.status == '400'){
						swalInit.fire('Server Error!', 'This project already has SO. Please contact developer!', 'error');
					}
				}
			 },
			 error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
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
			$('#form_data')[0].reset();
			$('#sales_project_id').empty();
			$('#temp_project').val('');
		});
   });

	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/sales/project/row_detail_sales") }}',
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

   function reset() {
      $('#form_data').trigger('reset');
      $('#validation_alert').hide();
      $('#validation_content').html('');
   }

   function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
   
	function deleteProject(val) {
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
					 url: '{{ url("admin/sales/project/delete_project") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: { id : val, reason : $('#delete_reason').val() },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('#datatable_serverside');
					 },
					 success: function(response) {
						loadingClose('#datatable_serverside');
						if(response.status == 200) {
							notif('success', 'bg-success', response.message);
							notyConfirm.close();
							loadDataTable();
						} else {
							notif('error', 'bg-danger', response.message);
						}
					 },
					 error: function() {
						loadingClose('#datatable_serverside');
						swalInit.fire({
						   title: 'You do not have permission to delete this project!',
						   text: 'Please contact sales manager to ask delete this project.',
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
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/sales/project/datatable") }}',
            type: 'GET',
            data: {
               status: $('#filter_status').val(),
			   branch: $('#filter_branch').val()
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
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
		 "createdRow": function( row, data, dataIndex){
			if(data[9] == ''){
				$(row).addClass('bg-danger');
			}
			if(data[10]){
				$(row).addClass('bg-secondary');
			}
			if(data[11]){
				$(row).addClass('bg-warning');
				$(row).attr('data-popup','tooltip').attr('title',data[10]);
			}
		 },
      }); 
   }

   function create() {
      $.ajax({
         url: '{{ url("admin/sales/project/create") }}',
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

	$('#payment_method').on('change', function() {
		if($(this).val() == '1'){
			$('#detail_payment').empty().append('<option value="">-- Choose --</option><option value="11">Cash Before Delivery</option><option value="12">Cash After Delivery</option><option value="13">Cash with DP</option>');
		}else if($(this).val() == '2'){
			$('#detail_payment').empty().append('<option value="">-- Choose --</option><option value="21">Cover BG</option><option value="22">SKBDN</option><option value="23">SCF</option><option value="24">Credit with DP</option><option value="25">Credit without DP</option>');
		}else{
			$('#detail_payment').empty().append('<option value="">-- Choose --</option>');
		}
	});
	
	var tempProject = 0;
	
	function addPictures(id){
		$('#tempProject').val(id);
		tempProject = id;
		$('#list-images').empty();
		$.ajax({
			 url: '{{ url("admin/sales/project/get_pictures") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: tempProject
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
						$('#list-images').append(`
							<div class="col-md-2 text-center" id="picture` + val.id + `">
								<a data-magnify="gallery" data-src="" data-caption="` + val.name + `" data-group="a" href="` + val.image + `"><img src="` + val.image + `" style="max-height:200px;" class="img-fluid img-thumbnail"></a>
								<p class="mt-3">
									<button class="btn btn-danger btn-sm" onclick="destroyPicture(` + val.id + `);"><i class="icon-trash"></i></button>
								</p>
							</div>
						`);
					});
				}else{
					$('#list-images').append(`
						<div class="col-md-12 text-center">
							<div class="alert alert-warning alert-styled-left">
								There is no pictures in this project.
							</div>
						</div>
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
		
		$('#modal_pictures').modal('toggle');
	}
	
	Dropzone.options.dropzoneMultiple = {
		paramName: "file",
		maxFilesize: 1,
		maxFiles: 5,
		acceptedFiles: ".jpeg,.jpg,.png,.gif",
		init: function() {
			this.on("sending", function(file, xhr, formData){
				formData.append('id', tempProject);
			});
			this.on("success", function(file, responseText) {
				if(responseText.status == '422'){
					notif('error', 'bg-danger', responseText.message);
				}
			});
		}
	};
	
	function destroyPicture(val) {
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
				 url: '{{ url("admin/sales/project/delete_picture") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : val },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					if(response.status == 200) {
						notif('success', 'bg-success', response.message);
						$('#picture' + val).remove();
						notyConfirm.close();
					} else {
						notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'You do not have permission to delete this project!',
					   text: 'Please contact sales manager to ask delete this project.',
					   type: 'error'
					});
				 }
				});
			})
         ]
		}).show();
	}
	
	function closeSalesOrder(id){
		$.ajax({
			 url: '{{ url("admin/sales/project/get_sales_order") }}',
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
				if(response.length > 0){
					$('#data_sales_order').empty();
					$.each(response, function(i, val) {
						$('#data_sales_order').append(`
							<tr align="center">
								<td>` + (i + 1) + `</td>
								<td>` + val.code + `</td>
								<td>` + val.sales + `</td>
								<td>` + val.customer + `</td>
								<td>` + val.nominal + `</td>
								<td>
									<label class="form-check-label">
										is Closed
										<input type="checkbox" class="form-check-input" id="checkSalesOrder` + val.id + `" style="margin-left: 0.5rem;" ` + (val.is_closed == '1' ? 'checked' : '') + `>
									</label>
								</td>
								<td>
									` + (val.is_closed == '1' ? val.reason_closed : `<input id="noteSalesOrder` + val.id + `" class="form-control" placeholder="Enter note here" type="text">`) + `
								</td>
								<td>
									` + (val.is_closed == '1' ? '-' : `<button type="button" class="btn bg-success btn-sm" onclick="saveCloseSalesOrder(` + val.id + `,` + id + `)"><i class="icon-floppy-disk"></i></button>`) + `
								</td>
								<td>
									` + val.approved + `
								</td>
							</tr>
						`);
					});
				}else{
					$('#data_sales_order').append(`
						<tr>
							<td colspan="9" align="center">Data not found</td>
						</tr>
					`);
				}
				
				$('#modal_close_sales_order').modal('toggle');
				
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
	}
	
	function saveCloseSalesOrder(sales,project){
		var note = $('#noteSalesOrder' + sales).val();
		if($('#checkSalesOrder' + sales).is(":checked") && note !== '' && note.length > 1){
			var notyConfirm = new Noty({
			 theme: 'limitless',
			 text: '<h6 class="font-weight-bold mb-3">Are sure you want to close?</h6><label>Closed SO can no longer be recovered.</label>',
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
					 url: '{{ url("admin/sales/project/close_sales_order") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: { id : sales, note : note },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('.modal-content');
					 },
					 success: function(response) {
						loadingClose('.modal-content');
						if(response.status == 200) {
							notif('success', 'bg-success', response.message);
							
							$.ajax({
								 url: '{{ url("admin/sales/project/get_sales_order") }}',
								 type: 'POST',
								 dataType: 'JSON',
								 data: {
									id: project
								 },
								 headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								 },
								 success: function(response) {
									if(response.length > 0){
										$('#data_sales_order').empty();
										$.each(response, function(i, val) {
											$('#data_sales_order').append(`
												<tr align="center">
													<td>` + (i + 1) + `</td>
													<td>` + val.code + `</td>
													<td>` + val.sales + `</td>
													<td>` + val.customer + `</td>
													<td>` + val.nominal + `</td>
													<td>
														<label class="form-check-label">
															is Closed
															<input type="checkbox" class="form-check-input" id="checkSalesOrder` + val.id + `" style="margin-left: 0.5rem;" ` + (val.is_closed == '1' ? 'checked' : '') + `>
														</label>
													</td>
													<td>
														` + (val.is_closed == '1' ? val.reason_closed : `<input id="noteSalesOrder` + val.id + `" class="form-control" placeholder="Enter note here" type="text">`) + `
													</td>
													<td>
														` + (val.is_closed == '1' ? '-' : `<button type="button" class="btn bg-success btn-sm" onclick="saveCloseSalesOrder(` + val.id + `,` + id + `)"><i class="icon-floppy-disk"></i></button>`) + `
													</td>
													<td>
														` + val.approved + `
													</td>
												</tr>
											`);
										});
									}
								 }
							});
							
							notyConfirm.close();
						}
					 },
					 error: function() {
						loadingClose('.modal-content');
						swalInit.fire({
						   title: 'Ups, check your internet connection!',
						   text: 'Ups. Sorry error.',
						   type: 'error'
						});
					 }
					});
				})
			 ]
			}).show();
		}else{
			notif('error', 'bg-danger', 'Please check your input first.');
		}
	}
	
	@if(in_array('1',session('bo_role')))
	function printSalesReport3(){
		if($('#mode').val()){
			$.ajax({
				type : "POST",
				url  : "{{ url('admin/sales/project/print/sales_report_3') }}",
				data : {
					mode: $('#mode').val(),
					sales : $('#sales_id').val()
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				cache: false,
				success: function(data){
					var w = window.open('about:blank');
					w.document.open();
					w.document.write(data);
					w.document.close();
				}
			});
		}else{
			notif('error', 'bg-danger', "Please Select Mode");
		}
	}
	@else
	function printSalesReport3(){
		if($('#mode').val()){
			var sales_id= {{ json_encode(session('bo_id')) }};
				$.ajax({
					type : "POST",
					url  : "{{ url('admin/sales/project/print/sales_report_3') }}",
					data : {
						mode: $('#mode').val(),
						sales : sales_id
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					cache: false,
					success: function(data){
						var w = window.open('about:blank');
						w.document.open();
						w.document.write(data);
						w.document.close();
					}
				});
		}else{
			notif('error', 'bg-danger', "Please Select Mode");
		}
	}
	@endif
</script>