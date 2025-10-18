<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Asset</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<span class="breadcrumb-item active">Asset</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Asset</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						<tr class="text-center">
							<th>#</th>
							<th>Name</th>
							<th>Type</th>
							<th>Start Value</th>
							<th>Percent Dpr.</th>
							<th>Date</th>
							<th>Proof</th>
							<th>Coa</th>
							<th>Operation</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Asset</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row justify-content-center">
					<div class="col-md-8">
						<form id="form_data">
							<div class="alert alert-danger" id="validation_alert" style="display:none;">
								<ul id="validation_content"></ul>
						    </div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Asset Name :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<input type="hidden" name="temp" id="temp">
									<input type="text" class="form-control" name="name" id="name" placeholder="Toyota Cayla...">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-form-label">Type :<sup class="text-danger">*</sup></label>
								<div class="col-lg-9">
									<select name="type" id="type" class="custom-select">
										<option value="1">Land</option>
										<option value="2">Building & Infrastructure</option>
										<option value="3">Vehicle</option>
										<option value="4">Furniture & Equipment</option>
										<option value="5">Other</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Starting Value / Nominal (in IDR) :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<input type="text" class="form-control" name="starting_value" id="starting_value" onkeyup="formatRupiah(this)" value="0">
							  </div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Percent Depreciation :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<input type="number" class="form-control" name="percent_depreciation" id="percent_depreciation" value="0">
							  </div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Date :<span class="text-danger">*</span></label>
							  <div class="col-lg-9">
									<input type="date" class="form-control" name="date" id="date">
							  </div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">PO Document (If any) :</label>
							  <div class="col-lg-9">
									<input type="file" class="form-input-styled" id="file" name="file">
							  </div>
							</div>
							<div class="form-group row">
							  <label class="col-lg-3 col-form-label">Coa :</label>
							  <div class="col-lg-9">
									<select name="coa_id" id="coa_id" class="select2">
									   @foreach($coa as $c)
											<option value="{{ $c->id }}">{{ $c->name }}</option>
									   @endforeach
									</select>
							  </div>
							</div>
						</form>
					</div>
				</div>
				
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
			$('#temp').val('');
			$('#coa_id').val($("#coa_id option:first").val()).trigger('change');
			$('#form_data').trigger('reset');
		});
	});
	
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
				  url: '{{ url("admin/master_data/hrd/asset/destroy") }}',
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
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/master_data/hrd/asset/datatable") }}',
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
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'name', className: 'text-center align-middle' },
            { name: 'type', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'starting_value', className: 'text-center align-middle' },
			{ name: 'percent_depreciation', className: 'text-center align-middle' },
			{ name: 'date', className: 'text-center align-middle' },
			{ name: 'image', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'coa', className: 'text-center align-middle' },
			{ name: 'operation', orderable: false, searchable: false, className: 'text-center align-middle' },
         ]
      });
	}
   
	function success() {
		$('#modal_form').modal('hide');
		$('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
   
	function create() {
	  $.ajax({
		 url: '{{ url("admin/master_data/hrd/asset/create") }}',
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
	
	function show(id) {
	  $.ajax({
		 url: '{{ url("admin/master_data/hrd/asset/show") }}',
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
			loadingClose('.modal-content');
			$('#temp').val(id);
			$('#name').val(response.name);
			$('#type').val(response.type);
			$('#starting_value').val(response.starting_value);
			$('#percent_depreciation').val(response.percent_depreciation);
			$('#date').val(response.date);
			$('#coa_id').val(response.coa_id).trigger('change');
			
			$('#modal_form').modal('toggle');
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
</script>