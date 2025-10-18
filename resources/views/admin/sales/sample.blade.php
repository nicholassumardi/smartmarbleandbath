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
                    <span class="breadcrumb-item active">Project</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">List Data Samples</h5>
                <div class="table-responsive">
                    <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                        <thead class="bg-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Code</th>
                                <th>User</th>
                                <th>Customer</th>
                                <th>Progress</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>



    {{-- MODAL --}}

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
                        <div class="alert alert-danger" id="validation_alert"
                            style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                            <ul id="validation_content"></ul>
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
                                    <label>Sent Date :<sup class="text-danger">*</sup></label>
                                    <input type="date" name="sample_sent_date" id="sample_sent_date"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Return Date :<sup class="text-danger">*</sup></label>
                                    <input type="date" name="sample_return_date" id="sample_return_date"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sales :<sup class="text-danger">*</sup></label>
                                    <select name="sales_so" id="sales_so"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Note :</label>
                                    <textarea name="sample_note" id="sample_note" class="form-control"
                                        placeholder="Enter note" rows="1"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <hr>
                        </div>

                        <h5><b><span class="badge badge-danger"></span> Detail Product Sample</b></h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product :<sup class="text-danger">*</sup></label>
                                    <select name="sample_product_id" id="sample_product_id"></select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Qty :<sup class="text-danger">*</sup></label>
                                    <input type="number" name="sample_qty" id="sample_qty" class="form-control"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unit :<sup class="text-danger">*</sup></label>
                                    <select name="sample_unit" id="sample_unit" class="custom-select">
                                        <option value="1">Pcs</option>
                                        <option value="2">Box</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Size :<sup class="text-danger">*</sup></label>
                                    <select name="sample_size" id="sample_size" class="custom-select">
                                        <option value="1">20x20</option>
                                        <option value="2">Full Size</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <button type="button" onclick="addSample()" class="btn bg-success col-3"><i
                                            class="icon-plus2"></i>
                                        Add</button>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-secondary">
                                        <tr class="text-center">
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Unit</th>
                                            <th>Size</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_sample">

                                    </tbody>
                                </table>
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
                    <p class="mb-3">You may upload finished results of installed Tiles or Sanitary. <b>Max size : 1 Mb /
                            1024 Kb, Max photos : 3.</b></p>

                    <p class="font-weight-semibold">Multiple file upload :</p>
                    <form action="{{ url('admin/sales/project/add_pictures') }}" class="dropzone"
                        id="dropzone_multiple">
                        @csrf
                    </form>
                    <div class="row mt-3" id="list-images">

                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
                        Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="modal_close_sales_order" data-backdrop="static" role="dialog">
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
                        <span class="font-weight-semibold">Info!</span><b> Closed Sales Order will not be shown in
                            Dashboard, any Unmatch or Pending delivery.</b>
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
                    <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
                        Close</button>
                </div>
            </div>
        </div>
    </div> --}}

    <script>
        function resetFilter() {
		$('#filter_branch').val(null);
		loadDataTable();
	}
	
   $(function() {
      loadDataTable();
      select2ServerSide('#sales_so, #sales_po', '{{ url("admin/select2/user") }}');
      select2ServerSide('#sample_product_id', '{{ url("admin/select2/product") }}');
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
			$('#temp_project').val('');
		});
   });

   $('#data_sample').on('click','#delete_data_sample',function () { 
    $(this).closest('tr').remove();
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
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/sales/sample/datatable") }}',
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
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'code', searchable: false, className: 'text-center align-middle' },
			{ name: 'user_id',className: 'text-center align-middle' },
            { name: 'customer_id', className: 'text-center align-middle' },
            { name: 'progress', className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
		 "createdRow": function( row, data, dataIndex){
			if(data[9] == ''){
				$(row).addClass('bg-danger');
			}
			if(data[10]){
				$(row).addClass('bg-warning');
				$(row).attr('data-popup','tooltip').attr('title',data[10]);
			}
		 },
      }); 
   }

   function create() {
      $.ajax({
         url: '{{ url("admin/sales/sample/create") }}',
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
	
    function addSample() {
      var sample_product_id = $('#sample_product_id');
      var sample_qty        = $('#sample_qty');
	  var sample_unit       = $('#sample_unit');
      var sample_size       = $('#sample_size');

      if(sample_product_id.val() && sample_qty.val() && sample_size.val()) {
         $.ajax({
            url: '{{ url("admin/sales/sample/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: sample_product_id.val()
            },
            beforeSend: function() {
               loadingOpen('#step-5');
            },
            success: function(response) {
               loadingClose('#step-5');
				
				if(response.error == '500'){
					
					var msg = '';
					
					$.each(response.message, function(i, val) {
						msg = msg + val;
					});
					
					swalInit.fire('Error product!', msg, 'error');
					
				}else{
				
					var same = false;
					
					$('input[name^="sample_product_id"]').each(function() {
						if($(this).val() == response.id){
							same = true;
						}
					});
					
					if(same == false){
						
						$('#data_sample').append(`
						  <tr class="text-center">
							 <input type="hidden" name="sample_product_id[]" value="` + sample_product_id.val() + `">
							 <input type="hidden" name="sample_qty[]" value="` + sample_qty.val() + `">
							 <input type="hidden" name="sample_unit[]" value="` + sample_unit.val() + `">
							 <input type="hidden" name="sample_size[]" value="` + sample_size.val() + `">

							 <td class="align-middle">` + response.product + `</td>  
							 <td class="align-middle">` + sample_qty.val() + `</td>
							 <td class="align-middle">` + $("#sample_unit option:selected").text() + `</td>
							 <td class="align-middle">` + $("#sample_size option:selected").text() + `</td>  
							 <td class="align-middle">
								<button type="button" id="delete_data_sample" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
					   `);

					   sample_product_id.val(null).trigger('change');
					   sample_qty.val(null);
					   sample_unit.find('option:eq(0)').prop('selected', true);
					   sample_size.val(null);
					
					}else{
						swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
					}
				
				}
               
            },
            error: function() {
               loadingClose('#step-5');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
   }

   function destroy(val) {
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
					 url: '{{ url("admin/sales/sample/destroy") }}',
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
						   title: 'You do not have permission to delete this sample!',
						   text: 'Please contact sales manager to ask delete this sample.',
						   type: 'error'
						});
					 }
					});
				}else{
					notif('error', 'bg-warning', 'Please explain why this sample should be deleted?');
				}
			})
         ]
		}).show();
	}

    
	
    </script>