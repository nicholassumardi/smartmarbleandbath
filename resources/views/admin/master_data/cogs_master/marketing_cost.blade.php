<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Marketing Cost</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
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
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">COGS Master</a>
					<span class="breadcrumb-item active">Marketing Cost</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of Marketing Costs</h5>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>#</th>
                        <th>No</th>
                        <th>Company</th>
                        <th>Fixed Cost</th>
                        <th>Nett Profit</th>
                        <th>Marketing Cost</th>
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
            <h5 class="modal-title" id="exampleModalLabel">Form Marketing Cost</h5>
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
                  <label>Marketing Cost's Company :<span class="text-danger">*</span></label>
                  <select name="company_id" id="company_id" class="select2">
                     <option value="">-- Choose --</option>
                     @foreach($company as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Rental Cost :<span class="text-danger">*</span></label>
                        <input type="number" name="rental_cost" id="rental_cost" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Travel Sales Cost :<span class="text-danger">*</span></label>
                        <input type="number" name="travel_sales_cost" id="travel_sales_cost" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Marketing Cost :<span class="text-danger">*</span></label>
                        <input type="number" name="marketing_cost" id="marketing_cost" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's On Site Cost :<span class="text-danger">*</span></label>
                        <input type="number" name="on_site_cost" id="on_site_cost" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Storage Cost :<span class="text-danger">*</span></label>
                        <input type="number" name="storage_cost" id="storage_cost" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Fixed Cost :<span class="text-danger">*</span></label>
                        <input type="number" name="fixed_cost" id="fixed_cost" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Interest In Payment :<span class="text-danger">*</span></label>
                        <input type="number" name="interest_in_payment" id="interest_in_payment" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Nett Profit :<span class="text-danger">*</span></label>
                        <input type="number" name="nett_profit" id="nett_profit" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Saving :<span class="text-danger">*</span></label>
                        <input type="number" name="saving" id="saving" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Sales Commission :<span class="text-danger">*</span></label>
                        <input type="number" name="sales_commission" id="sales_commission" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Middlemant Commission :<span class="text-danger">*</span></label>
                        <input type="number" name="middlemant_commission" id="middlemant_commission" class="form-control" placeholder="0">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Project Commission :<span class="text-danger">*</span></label>
                        <input type="number" name="project_commission" id="project_commission" class="form-control" placeholder="0">
                     </div>
                  </div>
				  <div class="col-md-6">
                     <div class="form-group">
                        <label>Marketing Cost's Interest On Buying :<span class="text-danger">*</span></label>
                        <input type="number" name="interest_on_buying" id="interest_on_buying" class="form-control" placeholder="0">
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer bg-light">
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
      var table = loadDataTable();

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
   });

   function rowDetail(data) {
      var content = '';

      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/marketing_cost/row_detail") }}',
         type: 'GET',
         async: false,
         data: {
            id: $(data[0]).data('id')
         },
         success: function(response) {
            $.each(response, function(i, val) {
               content += `
                  <div class="col-md-4">
                     <div class="card border-top-2 border-top-success">
                        <div class="card-header">
                           <h6 class="card-title text-center"><b>` + i + `</b></h6>
                        </div>
                        <div class="card-body text-center">` + val + `</div>
                     </div>
                  </div>
               `;
            });
         },
         error: function() {
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });

      return '<div class="row mt-3">' + content + '</div>';
   }

   function cancel() {
      reset();
      $('#modal_form').modal('hide');
      $('#btn_create').show();
      $('#btn_update').hide();
      $('#btn_cancel').hide();
   }

   function toShow() {
      $('#modal_form').modal('show');
      $('#validation_alert').hide();
      $('#validation_content').html('');
      $('#btn_create').hide();
      $('#btn_update').show();
      $('#btn_cancel').show();
   }

  function reset() {
      $('#form_data').trigger('reset');
      $('#company_id').val(null).change();
      $('#validation_alert').hide();
      $('#validation_content').html('');
   }

  function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
   }

   function loadDataTable() {
      return $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/master_data/cogs_master/marketing_cost/datatable") }}',
            type: 'GET',
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
            { name: 'company_id', className: 'text-center align-middle' },
            { name: 'fixed_cost', searchable: false, className: 'text-center align-middle' },
            { name: 'nett_profit', searchable: false, className: 'text-center align-middle' },
            { name: 'marketing_cost', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }

   function create() {
      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/marketing_cost/create") }}',
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

   function show(id) {
      toShow();
      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/marketing_cost/show") }}',
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
            $('#company_id').val(response.company_id).change();
            $('#rental_cost').val(response.rental_cost);
            $('#travel_sales_cost').val(response.travel_sales_cost);
            $('#marketing_cost').val(response.marketing_cost);
            $('#on_site_cost').val(response.on_site_cost);
            $('#storage_cost').val(response.storage_cost);
            $('#fixed_cost').val(response.fixed_cost);
            $('#interest_in_payment').val(response.interest_in_payment);
            $('#nett_profit').val(response.nett_profit);
            $('#saving').val(response.saving);
            $('#sales_commission').val(response.sales_commission);
            $('#middlemant_commission').val(response.middlemant_commission);
            $('#project_commission').val(response.project_commission);
			$('#interest_on_buying').val(response.interest_on_buying);
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
      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/marketing_cost/update") }}' + '/' + id,
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
                  url: '{{ url("admin/master_data/cogs_master/marketing_cost/destroy") }}',
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