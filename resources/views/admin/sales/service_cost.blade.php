<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Service Charge</span>
                </h4>
            </div>
            <div class="header-elements">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left"
                        onclick="loadDataTable()">
                        <b><i class="icon-sync"></i></b> Refresh
                    </button>
                    <button type="button" class="btn bg-primary btn-labeled btn-labeled-left mr-2" onclick="reset()"
                        data-toggle="modal" data-target="#modal_form">
                        <b><i class="icon-plus3"></i></b> Add
                    </button>
                </div>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Data</a>
                    <span class="breadcrumb-item active">Service Charge</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">List Service Charge</h5>
                <div class="table-responsive">
                    <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                        <thead class="bg-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Code</th>
                                <th>User</th>
                                <th>Customer</th>
                                <th>SO Service</th>
                                <th>Proof</th>
                                @if (in_array(1, session('bo_role')) || in_array(3, session('bo_role')) || in_array(4, session('bo_role')) || in_array(11, session('bo_role')))
                                <th>Payment Progress</th>
                                @endif
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
                    <h5 class="modal-title" id="exampleModalLabel">Add New Service Charge</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_data">
                        <div class="alert alert-danger" id="validation_alert"
                            style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                            <ul id="validation_content"></ul>
                        </div>
                        <div class="row">
                            <input type="hidden" name="temp_scid" id="temp_scid">
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
                                    <label>Branch :<sup class="text-danger">*</sup></label>
                                    <select name="branch" id="branch" class="select2">
                                       @foreach (DB::table('company_entities')->get() as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>PPn :<sup class="text-danger">*</sup></label>
                                    <select name="is_ppn" id="is_ppn" class="custom-select">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Delivery Cost :<sup class="text-danger">*</sup></label>
                                    <input type="text" name="delivery_cost" id="delivery_cost" class="form-control"
                                        placeholder="0" onkeyup="formatRupiah(this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cutting Cost :<sup class="text-danger">*</sup></label>
                                    <input type="text" name="cutting_cost" id="cutting_cost" class="form-control"
                                        placeholder="0" onkeyup="formatRupiah(this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Miscellaneous Cost :<sup class="text-danger">*</sup></label>
                                    <input type="text" name="misc_cost" id="misc_cost" class="form-control"
                                        placeholder="0" onkeyup="formatRupiah(this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Note :</label>
                                    <textarea name="note" id="note" class="form-control"
                                        placeholder="Enter note" rows="1"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Proof :</sup></label>
                                    <input type="file" class="form-input-styled" id="file" name="file" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
                        Close</button>
                    <button type="button" class="btn bg-primary" id="btn_create" onclick="create()"><i
                            class="icon-plus3"></i> Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_form_pay" data-backdrop="static" role="dialog">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
              <div class="modal-header bg-light">
                 <h5 class="modal-title" id="exampleModalLabel">Add New Payment Service Charge</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                 </button>
              </div>
              <div class="modal-body">
                 <form id="form_data_pay">
                    <div class="alert alert-danger" id="validation_alert_pay" style="display:none;">
                       <ul id="validation_content_pay"></ul>
                    </div>
                    <h5 class="card-title"><b>Main Information</b></h5>
                    <div class="row">
                       <div class="col-md-3">
                           <input type="hidden" name="service_cost_id" id="service_cost_id">
                          <div class="form-group">
                             <label>Date Paid :<sup class="text-danger">*</sup></label>
                             <input type="hidden" name="temppay" id="temppay" class="form-control">
                             <input type="date" name="pay_date" id="pay_date" class="form-control">
                          </div>
                       </div>
                       <div class="col-md-3">
                         <div class="form-group">
                             <label>COA :<span class="text-danger">*</span></label>
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
                     <div class="form-group"><hr></div>
					<h5 class="card-title">Preview Proof</h5>
					<div class="form-group text-center" id="previewImg">
						<img id="previewImage" src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
					</div>
					<hr>
                     <h5 class="card-title"><b>Journal Information</b></h5>
                     <div class="row justify-content-center">
                         <div class="col-md-8">
                             <table class="table table-bordered">
                                 <thead class="table-secondary">
                                     <tr class="text-center">
                                        <th>Date</th>
                                        <th>Coa</th>
                                        <th>Branch</th>
                                        <th>Type</th>
                                        <th>Nominal</th>
                                     </tr>
                                 </thead>
                                 <tbody id="data_journal"></tbody>
                             </table>
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
                                    <th>Branch</th>
                                    <th>Source</th>
                                    <th>Nominal</th>
                                    <th>Proof</th>
                                    <th>Code</th>
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

      loadDataTable();
      select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
   });

    $('#modal_form_pay').on('hidden.bs.modal', function (e) {
        reset();
    })
   
   function reset() {
      $('#form_data').trigger('reset');
      $('#form_data_pay').trigger('reset');
      $('#customer_id').val(null).trigger('change.select2');
      $('#validation_alert').hide();
      $('#validation_content').html('');
   }

   function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
   
   @if(in_array(1, session('bo_role')) || in_array(3, session('bo_role')) || in_array(4, session('bo_role')) || in_array(11, session('bo_role')))
   function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/sales/service_cost/datatable") }}',
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
            { name: 'code', searchable: false, className: 'text-center align-middle' },
			   { name: 'user_id',className: 'text-center align-middle' },
            { name: 'customer_id', className: 'text-center align-middle' },
            { name: 'proof', searchable: false, orderable: false, className: 'text-center nowrap align-middle'},
            { name: 'progress', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'null', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
        
      }); 
   }
   @else
   function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/sales/service_cost/datatable") }}',
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
            { name: 'code', searchable: false, className: 'text-center align-middle' },
			   { name: 'user_id',className: 'text-center align-middle' },
            { name: 'customer_id', className: 'text-center align-middle' },
            { name: 'proof', searchable: false, orderable: false, className: 'text-center nowrap align-middle'},
            { name: 'null', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
      }); 
   }
   @endif


   function create() {
      $.ajax({
         url: '{{ url("admin/sales/service_cost/create") }}',
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

   function create_pay(){
    $.ajax({
         url: '{{ url("admin/sales/service_cost/create_payment") }}',
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

   function show(id){
    $('#temp_scid').val(id);
    $('#modal_form').modal('toggle');
    $.ajax({
         url: '{{ url("admin/sales/service_cost/show") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
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
            $('#customer_id').val(response.customer_id).trigger('change');
            $('#is_ppn').val(response.is_ppn).trigger('change');
            $('#delivery_cost').val(response.delivery_cost.toLocaleString("id"));
            $('#cutting_cost').val(response.cutting_cost.toLocaleString("id"));
            $('#misc_cost').val(response.misc_cost.toLocaleString("id"));
            $('#note').val(response.note);
        
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

   function getServiceCostPayment(id){
    $('#service_cost_id').val(id);
    $.ajax({
         url: '{{ url("admin/sales/service_cost/get_service_cost_payment") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
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
            getServiceCostJournal(id);
            $('#data_payment').empty();
            if(response.data.length > 0){ 
                $.each(response.data, function (i, val) { 
                    $('#data_payment').append(`
                        <tr class="text-center row` + val.id + `">
                            <td>` + val.date + `</td>
                            <td>` + val.branch + `</td>
                            <td>` + val.coa + `</td>
                            <td>` + val.nominal + `</td>
                            <td>` + val.proof + `</td>
                            <td>` + val.code + `</td>
                            <td>` + val.note + `</td>
                            <td>
                                <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyPayment(` + val.id + `)"><i class="icon-trash-alt"></i></button>
                            </td>
                        </tr>
                    `);
                });
            }else{
                $('#data_payment').append(`
                    <tr class="text-center align-middle">
                        <td class="bg-danger" colspan="9">There is no payment data.</td>
                    </tr>
                `);
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

   function getServiceCostJournal(id){
    $.ajax({
         url: '{{ url("admin/sales/service_cost/get_service_cost_journal") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
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
            $('#data_journal').empty();
            if(response.data.length > 0){ 
                $.each(response.data, function (i, val) { 
                    $('#data_journal').append(`
                    <tr class="text-center row` + val.id + `">
                        <td>` + val.date + `</td>
                        <td>` + val.coa_name + `</td>
                        <td>` + val.branch + `</td>
                        <td>` + val.type + `</td>
                        <td>` + val.nominal + `</td>
                    </tr>
                `);
                });
            }else{
                $('#data_journal').append(`
                    <tr class="text-center align-middle">
                        <td colspan="9">Data Not Found.</td>
                    </tr>
                `);
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
                  url: '{{ url("admin/sales/service_cost/destroy") }}',
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

   function destroyPayment(id) {
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
                  url: '{{ url("admin/sales/service_cost/destroy_payment") }}',
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

   function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
	}
    </script>