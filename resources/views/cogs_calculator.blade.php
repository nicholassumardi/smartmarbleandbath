<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">COGS Calculator</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left"
						onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<a href="{{url('cogs_calculator/create')}}" class="btn bg-primary btn-labeled btn-labeled-left mr-2">
						<b><i class="icon-plus3"></i></b> Add
                    </a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<span class="breadcrumb-item active">COGS Calculator</span>
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
								<th>Exclusive Partners</th>
								<th>Items</th>
								<th>Size</th>
								<th>Date</th>
								<th>Price</th>
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>



	<script>
    $(function() {
	// loadDataTable();
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