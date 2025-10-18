<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Warehouse</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Product</a>
					<span class="breadcrumb-item active">Warehouse</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of Warehouses</h5>
				<div class="header-elements">
					<select name="filter_status" id="filter_status" class="custom-select" onchange="loadDataTable()">
						<option value="">All Status</option>
						<option value="1">Active</option>
						<option value="2">Not Active</option>
					</select>
				</div>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Status</th>
						<th>Type</th>
						<th>#</th>
                     </tr>
                  </thead>
               </table>
            </div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Add Warehouse</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data">
				   <div class="alert alert-danger" id="validation_alert" style="display:none;">
					  <ul id="validation_content"></ul>
				   </div>
				   <div class="row justify-content-center">
						<div class="col-md-3">
							 <div class="form-group">
								<label>Warehouse Code :<sup class="text-danger">*</sup></label>
								<input type="hidden" name="tempWarehouse" id="tempWarehouse">
								<input class="form-control" type="text" name="code" id="code" placeholder="Code : make sure not same with existing data...">
							 </div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
								<label>Warehouse Name :<sup class="text-danger">*</sup></label>
								<input class="form-control" type="text" name="name" id="name" placeholder="Type here...">
							 </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Type :<span class="text-danger">*</span></label>
								<select name="type" id="type" class="custom-select">
									<option value="1">Project</option>
									<option value="2">Broken</option>
									<option value="3">Display</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group text-center mt-4">
						<div class="form-check form-check-inline">
						 <label class="form-check-label">
							<input type="radio" class="form-check-input" name="status" value="1" checked>
							Active
						 </label>
						</div>
						<div class="form-check form-check-inline">
						 <label class="form-check-label">
							<input type="radio" class="form-check-input" name="status" value="2">
							Not Active
						 </label>
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
		
		$('#modal_form').on('hidden.bs.modal', function (e) {
			$('#form_data')[0].reset();
			$('#tempWarehouse').val('');
			loadDataTable();
		});
   });
   
	function create() {
		$.ajax({
			 url: '{{ url("admin/master_data/product/warehouse/create") }}',
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
	
	function reset() {
		$('#validation_alert').hide();
		$('#validation_content').html('');
		$('#form_data').trigger('reset');
		$('input[name="status"][value="1"]').prop('checked', true);
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
            url: '{{ url("admin/master_data/product/warehouse/datatable") }}',
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
            { name: 'name', className: 'text-center align-middle' },
            { name: 'status', searchable: false, className: 'text-center align-middle' },
			{ name: 'type', searchable: false, className: 'text-center align-middle' },
			{ name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
   
	function show(id) {
		$('#tempWarehouse').val(id);
		$.ajax({
         url: '{{ url("admin/master_data/product/warehouse/show") }}',
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
            $('#name').val(response.name);
			$('#type').val(response.type);
            $('input[name="status"][value="' + response.status + '"]').prop('checked', true);
			$('#modal_form').modal('toggle');
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
                  url: '{{ url("admin/master_data/product/warehouse/destroy") }}',
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