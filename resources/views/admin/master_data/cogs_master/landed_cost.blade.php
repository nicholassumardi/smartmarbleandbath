<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Landed Cost</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">COGS Master</a>
					<span class="breadcrumb-item active">Landed Cost</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Landed Cost Components</h5>
			</div>
			<div class="card-body">
				<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
					<li class="nav-item"><a href="#buy-exchange-rate" class="nav-link active" data-toggle="tab">Buy Exchange Rate</a></li>
					<li class="nav-item"><a href="#freight" class="nav-link" data-toggle="tab">Freight</a></li>
					<li class="nav-item"><a href="#import-system" class="nav-link" data-toggle="tab">Import System</a></li>
					<li class="nav-item"><a href="#import-estimation" class="nav-link" data-toggle="tab">Import Estimation Rate</a></li>
					<li class="nav-item"><a href="#import-custom" class="nav-link" data-toggle="tab">Import Custom Rate</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane fade show active" id="buy-exchange-rate">
						<a href="{{ url('admin/master_data/cogs_master/buy_exchange_rate') }}" class="btn bg-warning btn-labeled mr-2 btn-labeled-left">
							<b><i class="icon-pencil7"></i></b> Manage
						</a>
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_ER">
							<b><i class="icon-plus3"></i></b> Add
						</button>
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside_ER" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>No</th>
									<th>Date & Time</th>
									<th>Currency</th>
									<th>Company</th>
									<th>Conversion</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>

					<div class="tab-pane fade" id="freight">
						<a href="{{ url('admin/master_data/cogs_master/freight') }}" class="btn bg-warning btn-labeled mr-2 btn-labeled-left">
							<b><i class="icon-pencil7"></i></b> Manage
						</a>
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_F">
							<b><i class="icon-plus3"></i></b> Add
						</button>
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside_F" class="table table-bordered table-striped">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>No</th>
									<th>Port Of Discharge</th>
									<th>Destination Port</th>
									<th>Container</th>
									<th>Shipping</th>
									<th>Cost</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>

					<div class="tab-pane fade" id="import-system">
						<a href="{{ url('admin/master_data/cogs_master/import_system') }}" class="btn bg-warning btn-labeled mr-2 btn-labeled-left">
							<b><i class="icon-pencil7"></i></b> Manage
						</a>
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_IS">
							<b><i class="icon-plus3"></i></b> Add
						</button>
					
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside_IS" class="table table-bordered table-striped">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>No</th>
									<th>Code</th>
									<th>Name</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
					
					<div class="tab-pane fade" id="import-estimation">
						<a href="{{ url('admin/master_data/cogs_master/import_estimation_rate') }}" class="btn bg-warning btn-labeled mr-2 btn-labeled-left">
							<b><i class="icon-pencil7"></i></b> Manage Data
						</a>
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_IE">
							<b><i class="icon-plus3"></i></b> Add Data
						</button>
						
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside_IE" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>No</th>
									<th>Company</th>
									<th>Import</th>
									<th>Port Of Discharge</th>
									<th>Destination Port</th>
									<th>Container</th>
									<th>Cost</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
					
					<div class="tab-pane fade" id="import-custom">
						<a href="{{ url('admin/master_data/cogs_master/import_custom_rate') }}" class="btn bg-warning btn-labeled mr-2 btn-labeled-left">
							<b><i class="icon-pencil7"></i></b> Manage
						</a>
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_IC">
							<b><i class="icon-plus3"></i></b> Add
						</button>
					
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside_IC" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th>No</th>
									<th>Date & Time</th>
									<th>Company</th>
									<th>Currency</th>
									<th>Conversion</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form_ER" data-backdrop="static" role="dialog">
	   <div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Buy Exchange Rate</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_ER">
				   <div class="alert alert-danger" id="validation_alert_ER" style="display:none;">
					  <ul id="validation_content_ER"></ul>
				   </div>
				   <div class="form-group">
					  <label>Exchange Rate's Currency :<span class="text-danger">*</span></label>
					  <select name="currency_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($currency as $c)
							<option value="{{ $c->id }}">{{ $c->code }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Exchange Rate's Company :<span class="text-danger">*</span></label>
					  <select name="company_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($company as $c)
							<option value="{{ $c->id }}">{{ $c->name }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group form-group-feedback form-group-feedback-right">
					  <label>Exchange Rate's Conversion :<span class="text-danger">*</span></label>
					  <div class="position-relative">
						 <input type="number" name="conversion" id="conversion" class="form-control" placeholder="0">
						 <div class="form-control-feedback font-weight-bold">IDR</div>
					  </div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_ER()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form_F" data-backdrop="static" role="dialog">
	   <div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Freight</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_F">
				   <div class="alert alert-danger" id="validation_alert_F" style="display:none;">
					  <ul id="validation_content_F"></ul>
				   </div>
				   <div class="form-group">
					  <label>Freight's Port Of Discharge :<span class="text-danger">*</span></label>
					  <select name="country_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($country as $c)
							<option value="{{ $c->id }}">{{ $c->code }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Freight's Destination Port :<span class="text-danger">*</span></label>
					  <select name="city_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($city as $c)
							<option value="{{ $c->id }}">{{ $c->name }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Freight's Container :<span class="text-danger">*</span></label>
					  <select name="container" id="container" class="custom-select">
						 <option value="">-- Choose --</option>
						 <option value="1">20 Feet</option>
						 <option value="2">40 Feet</option>
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Freight's Shipping :<span class="text-danger">*</span></label>
					  <select name="shipping" id="shipping" class="custom-select">
						 <option value="">-- Choose --</option>
						 <option value="1">FOB</option>
						 <option value="2">EXWORK</option>
					  </select>
				   </div>
				   <div class="form-group form-group-feedback form-group-feedback-right">
					  <label>Freight's Cost :<span class="text-danger">*</span></label>
					  <div class="position-relative">
						 <input type="number" name="cost" id="cost" class="form-control" placeholder="0">
						 <div class="form-control-feedback font-weight-bold">$</div>
					  </div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_F()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form_IS" data-backdrop="static" role="dialog">
	   <div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Import System</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_IS">
				   <div class="alert alert-danger" id="validation_alert_IS" style="display:none;">
					  <ul id="validation_content_IS"></ul>
				   </div>
				   <div class="form-group">
					  <label>Import System's Code :<span class="text-danger">*</span></label>
					  <input type="text" name="code" id="code" class="form-control" placeholder="Enter code">
				   </div>
				   <div class="form-group">
					  <label>Import System's Name :<span class="text-danger">*</span></label>
					  <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_IS()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form_IE" data-backdrop="static" role="dialog">
	   <div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Import Estimation Rate</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_IE">
				   <div class="alert alert-danger" id="validation_alert_IE" style="display:none;">
					  <ul id="validation_content_IE"></ul>
				   </div>
				   <div class="form-group">
					  <label>Import Estimation Rate's Company :<span class="text-danger">*</span></label>
					  <select name="company_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($company as $c)
							<option value="{{ $c->id }}">{{ $c->name }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Import Estimation Rate's Import :<span class="text-danger">*</span></label>
					  <select name="import_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($import as $i)
							<option value="{{ $i->id }}">{{ $i->name }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Import Estimation Rate's Port Of Discharge :<span class="text-danger">*</span></label>
					  <select name="country_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($country as $c)
							<option value="{{ $c->id }}">{{ $c->name }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Import Estimation Rate's Destination Port :<span class="text-danger">*</span></label>
					  <select name="city_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($city as $c)
							<option value="{{ $c->id }}">{{ $c->name }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Import Estimation Rate's Container :<span class="text-danger">*</span></label>
					  <select name="container" class="custom-select">
						 <option value="">-- Choose --</option>
						 <option value="1">20 Feet</option>
						 <option value="2">40 Feet</option>
					  </select>
				   </div>
				   <div class="form-group form-group-feedback form-group-feedback-right">
					  <label>Import Estimation Rate's Cost :<span class="text-danger">*</span></label>
					  <div class="position-relative">
						 <input type="number" name="cost" id="cost" class="form-control" placeholder="0">
						 <div class="form-control-feedback font-weight-bold">Rp</div>
					  </div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_IE()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form_IC" data-backdrop="static" role="dialog">
	   <div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Import Custom Rate</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_data_IC">
				   <div class="alert alert-danger" id="validation_alert_IC" style="display:none;">
					  <ul id="validation_content_IC"></ul>
				   </div>
				   <div class="form-group">
					  <label>Import Custom Rate's Company :<span class="text-danger">*</span></label>
					  <select name="company_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($company as $c)
							<option value="{{ $c->id }}">{{ $c->name }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group">
					  <label>Import Custom Rate's Currency :<span class="text-danger">*</span></label>
					  <select name="currency_id" class="select2">
						 <option value="">-- Choose --</option>
						 @foreach($currency as $c)
							<option value="{{ $c->id }}">{{ $c->code }}</option>
						 @endforeach
					  </select>
				   </div>
				   <div class="form-group form-group-feedback form-group-feedback-right">
					  <label>Import Custom Rate's Conversion :<span class="text-danger">*</span></label>
					  <div class="position-relative">
						 <input type="number" name="conversion" id="conversion" class="form-control" placeholder="0">
						 <div class="form-control-feedback font-weight-bold">Rp</div>
					  </div>
				   </div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="create_IC()"><i class="icon-plus3"></i> Save</button>
			 </div>
		  </div>
	   </div>
	</div>
<script>
	$(function() {
      loadDataTableER();
	  loadDataTableF();
	  loadDataTableIS();
	  loadDataTableIE();
	  loadDataTableIC();
	});
	
	function create_IC() {
      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/import_custom_rate/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_IC').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_IC').hide();
            $('#validation_content_IC').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
			   
            } else if(response.status == 422) {
               $('#validation_alert_IC').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_IC').append(`
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
	
	function loadDataTableIC() {
      $('#datatable_serverside_IC').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/master_data/cogs_master/import_custom_rate/datatable") }}',
            type: 'GET',
            beforeSend: function() {
               loadingOpen('#datatable_serverside_IC');
            },
            complete: function() {
               loadingClose('#datatable_serverside_IC');
            },
            error: function() {
               loadingClose('#datatable_serverside_IC');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'created_at', searchable: false, className: 'text-center align-middle' },
            { name: 'company_id', className: 'text-center align-middle' },
            { name: 'currency_id', className: 'text-center align-middle' },
            { name: 'conversion', searchable: false, className: 'text-center align-middle' },
         ]
      }); 
   }
	
	function loadDataTableIE() {
      $('#datatable_serverside_IE').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/master_data/cogs_master/import_estimation_rate/datatable") }}',
            type: 'GET',
            beforeSend: function() {
               loadingOpen('#datatable_serverside_IE');
            },
            complete: function() {
               loadingClose('#datatable_serverside_IE');
            },
            error: function() {
               loadingClose('#datatable_serverside_IE');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'company_id', className: 'text-center align-middle' },
            { name: 'import_id', className: 'text-center align-middle' },
            { name: 'country_id', className: 'text-center align-middle' },
            { name: 'city_id', className: 'text-center align-middle' },
            { name: 'container', searchable: false, className: 'text-center align-middle' },
            { name: 'cost', searchable: false, className: 'text-center align-middle' },
         ]
      }); 
   }
   
   function create_IE() {
      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/import_estimation_rate/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_IE').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_IE').hide();
            $('#validation_content_IE').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
			   $('#modal_form_IE').modal('toggle');
			   loadDataTableIE();
            } else if(response.status == 422) {
               $('#validation_alert_IE').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_IE').append(`
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
	
	function create_IS() {
      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/import_system/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_IS').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_IS').hide();
            $('#validation_content_IS').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
			   $('#modal_form_IS').modal('toggle');
			   loadDataTableIS();
            } else if(response.status == 422) {
               $('#validation_alert_IS').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_IS').append(`
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
	
	function loadDataTableIS() {
      $('#datatable_serverside_IS').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/master_data/cogs_master/import_system/datatable") }}',
            type: 'GET',
            beforeSend: function() {
               loadingOpen('#datatable_serverside_IS');
            },
            complete: function() {
               loadingClose('#datatable_serverside_IS');
            },
            error: function() {
               loadingClose('#datatable_serverside_IS');
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
         ]
      }); 
   }
	
	function create_F() {
      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/freight/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_F').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_F').hide();
            $('#validation_content_F').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
			   $('#modal_form_F').modal('toggle');
			   loadDataTableF();
            } else if(response.status == 422) {
               $('#validation_alert_F').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_F').append(`
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
	
	function loadDataTableF() {
      $('#datatable_serverside_F').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/master_data/cogs_master/freight/datatable") }}',
            type: 'GET',
            beforeSend: function() {
               loadingOpen('#datatable_serverside_F');
            },
            complete: function() {
               loadingClose('#datatable_serverside_F');
            },
            error: function() {
               loadingClose('#datatable_serverside_F');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'country_id', className: 'text-center align-middle' },
            { name: 'city_id', className: 'text-center align-middle' },
            { name: 'container', searchable: false, className: 'text-center align-middle' },
            { name: 'shipping', searchable: false, className: 'text-center align-middle' },
            { name: 'cost', searchable: false, className: 'text-center align-middle' },
         ]
      }); 
   }
	
	function loadDataTableER() {
      $('#datatable_serverside_ER').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/master_data/cogs_master/buy_exchange_rate/datatable") }}',
            type: 'GET',
            beforeSend: function() {
               loadingOpen('#datatable_serverside_ER');
            },
            complete: function() {
               loadingClose('#datatable_serverside_ER');
            },
            error: function() {
               loadingClose('#datatable_serverside_ER');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'created_at', searchable: false, className: 'text-center align-middle' },
            { name: 'currency_id', className: 'text-center align-middle' },
            { name: 'company_id', className: 'text-center align-middle' },
            { name: 'conversion', searchable: false, className: 'text-center align-middle' },
         ]
      }); 
	}
	
	function create_ER() {
      $.ajax({
         url: '{{ url("admin/master_data/cogs_master/buy_exchange_rate/create") }}',
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_ER').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_ER').hide();
            $('#validation_content_ER').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
			   $('#modal_form_ER').modal('toggle');
			   loadDataTableER();
            } else if(response.status == 422) {
               $('#validation_alert_ER').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_ER').append(`
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
</script>