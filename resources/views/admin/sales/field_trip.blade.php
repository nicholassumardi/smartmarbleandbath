<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Sales Field Trip</span>
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
					<button type="button" class="btn bg-purple btn-labeled btn-labeled-left mr-2" onclick=""
						data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-calendar52"></i></b> Recap
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
					<span class="breadcrumb-item active">Field Trips</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">List Data Field Trips</h5>
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped w-100">
						<thead class="bg-dark">
							<tr class="text-center">
								<th>No</th>
								<th>User</th>
								<th>Date</th>
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
						<div class="form-group">
							<input type="hidden" name="temp_id">
							<div class="form-group">
								<label>Customer :<sup class="text-danger">*</sup></label>
								<select name="customer_id" id="customer_id" class="select2">
									<option value="">-- Choose --</option>
									@foreach($customer as $c)
									<option value="{{ $c->id }}">{{ $c->name.' - '.$c->email }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label class="form-label">Date<span class="text-danger">*</span></label>
								<input type="date" name="date" id="date" class="form-control" required>
							</div>
							<div class="form-group">
								<label for="formControlRange">Progress</label>
								<input type="range" class="form-control-range" value="0" min="1" max="100"
									name="progress" id="progress" onInput="$('#rangeval').html($(this).val())">
								<span id="rangeval">0</span>
							</div>
							<div class="form-group">
								<label class="form-label">Proof</label>
								<input type="file" id="proof" name="proof" class="form-control h-auto"
									accept="image/x-png,image/jpg,image/jpeg">
							</div>
							<div class="form-group">
								<label>Note :<span class="text-danger">*</span></label>
								<textarea class="form-control h-auto" id="note" name="note" rows="3"></textarea>
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


	<script>
		$(function() {
	loadDataTable();
	$('#modal_form').on('hidden.bs.modal', function (e) {
		$('#form_data')[0].reset();
		$('#form_data').trigger('reset');
		$('#temp_id').val('');
		$('#rangeval').html(0)
	});
   });

	function resetFilter() {
		loadDataTable();
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
   	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/sales/field_trip/datatable") }}',
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
            { name: 'user', className: 'text-center align-middle' },
            { name: 'date', className: 'text-center align-middle' },
			{ name: 'progress', className: 'text-center align-middle' },
			{ name: 'proof', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
      }); 
   }

   function create() {
      $.ajax({
         url: '{{ url("admin/sales/field_trip/create") }}',
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
			   $('#form_data')[0].reset();
               notif('success', 'bg-success', response.message);
			   $('#modal_form').modal('toggle');
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
	$("#temp_id").val(id);
	$('#modal_form').modal('toggle');
	$.ajax({
         url: '{{ url("admin/sales/field_trip/show") }}',
         type: 'POST',
         dataType: 'JSON',
         id: id,
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
				if(response.data){
					$('#customer_id').val(response.customer_id).trigger('change');
					$('#date').val(response.date);
					$('#progress').val(response.progress);
					$('#note').val(response.note);
				}
            }else {
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

	
	</script>