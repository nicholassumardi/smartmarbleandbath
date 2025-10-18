<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Progress Report Project</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh Data
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
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
                        <th>Code</th>
                        <th>User</th>
                        <th>Name</th>
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
               <div class="alert alert-danger" id="validation_alert" style="display:none;">
                  <ul id="validation_content"></ul>
               </div>
               <div class="form-group">
                  <label>Project Name :<sup class="text-danger">*</sup></label>
                  <textarea name="name" id="name" class="form-control" placeholder="Enter project name"></textarea>
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
                        <label>Timeline :<sup class="text-danger">*</sup></label>
                        <input type="date" name="timeline" id="timeline" class="form-control">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Project Manager :<sup class="text-danger">*</sup></label>
                        <input type="text" name="manager" id="manager" class="form-control" placeholder="Enter project manager">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Consultant Name :<sup class="text-danger">*</sup></label>
                        <input type="text" name="consultant" id="consultant" class="form-control" placeholder="Enter consultant name">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Owner :<sup class="text-danger">*</sup></label>
                        <input type="text" name="owner" id="owner" class="form-control" placeholder="Enter owner">
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
									<option value="{{ $bc->id }}">{{ $bc->name }}</option>
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
						</select>
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

<script>
   $(function() {
      loadDataTable();
   });

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

   function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/report/project/details/datatable") }}',
            type: 'GET',
            data: {
               status: $('#filter_status').val()
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
            { name: 'code', className: 'text-center align-middle' },
            { name: 'user_id', className: 'text-center align-middle' },
            { name: 'name', className: 'text-center align-middle' },
            { name: 'progress', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }

   function create() {
      $.ajax({
         url: '{{ url("admin/report/project/details/create") }}',
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
			$('#detail_payment').empty().append('<option value="">-- Choose --</option><option value="21">Cover BG</option><option value="22">SKBDN</option><option value="23">SCF</option><option value="24">Credit with DP</option>');
		}else{
			$('#detail_payment').empty().append('<option value="">-- Choose --</option>');
		}
	});
</script>