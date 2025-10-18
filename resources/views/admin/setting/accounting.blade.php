<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Setting Cut Off Accounting</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left"
						onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Setting</a>
					<span class="breadcrumb-item active">Accounting</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
				<div class="header-elements">

				</div>
			</div>

			<div class="card-body">
				<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
					<li class="nav-item"><a href="#Monthly" class="nav-link active" data-toggle="tab">Monthly</a></li>
					<li class="nav-item"><a href="#Daily" class="nav-link" data-toggle="tab">Daily</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane fade show active" id="Monthly">
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left mb-5"
							data-toggle="modal" data-target="#modal_accounting">
							<b><i class="icon-plus3"></i></b> Add
						</button>
						<div class="table-responsive">
							<table id="datatable_serverside" class="table table-bordered table-striped w-100">
								<thead class="bg-dark">
									<tr class="text-center">
										<th>#</th>
										<th>User</th>
										<th>Branch</th>
										<th>Month</th>
										<th>Note</th>
										<!-- <th>On/Off</th> -->
										<th>Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>

					<div class="tab-pane fade" id="Daily">
						<button type="button" class="btn bg-primary btn-labeled btn-labeled-left mb-5"
							data-toggle="modal" data-target="#modal_accounting_dailies">
							<b><i class="icon-plus3"></i></b> Add
						</button>
						<div class="table-responsive">
							<table id="datatable_serverside_daily" class="table table-bordered table-striped w-100">
								<thead class="bg-dark">
									<tr class="text-center">
										<th>#</th>
										<th>User</th>
										<th>Branch</th>
										<th>Date</th>
										<th>Note</th>
										<th>Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_accounting" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="body_deposit">
					<form id="form_data">
						<h5 class="card-title">Main Information</h5>
						<div class="alert alert-danger" id="validation_alert" style="display:none;">
							<ul id="validation_content"></ul>
						</div>
						<div class="row justify-content-center">
							<div class="col-md-12">
								<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
									<span class="font-weight-semibold">Beware!</span>
									&nbsp;<b>Cut off will prevent any changes on All Transactions.</b>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Branch :<sup class="text-danger">*</sup></label>
									<input type="hidden" id="temp" name="temp">
									<select name="branch" id="branch" class="custom-select"  onchange="getLatestMonth(this)">
										@foreach (DB::table('company_entities')->get() as $company)
											<option value="{{$company->id}}">{{$company->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Month :<sup class="text-danger">*</sup></label>
									<input type="month" name="month" id="month" class="form-control">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Note :<sup class="text-danger">*</sup></label>
									<textarea class="form-control" name="note" id="note" placeholder="Type here..."
										rows="1"></textarea>
								</div>
							</div>
						</div>
						<hr>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
					<button type="button" class="btn bg-primary" onclick="save()">Submit</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_accounting_dailies" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="body_deposit">
					<form id="form_data_dailies">
						<h5 class="card-title">Main Information</h5>
						<div class="alert alert-danger" id="validation_alert" style="display:none;">
							<ul id="validation_content"></ul>
						</div>
						<div class="row justify-content-center">
							<div class="col-md-12">
								<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
									<span class="font-weight-semibold">Beware!</span>
									&nbsp;<b>Cut off will prevent any changes on All Transactions.</b>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Branch :<sup class="text-danger">*</sup></label>
									<input type="hidden" id="temp" name="temp">
									<select name="branch_daily" id="branch_daily" class="custom-select">
										<option value="1">PTA</option>
										<option value="2">SMB</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Date :<sup class="text-danger">*</sup></label>
									<input class="form-control" type="datetime-local" name="date" id="date" value="{{ old('date') }}" max="{{ date('Y-m-d H:i:s') }}">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Note :<sup class="text-danger">*</sup></label>
									<textarea class="form-control" name="note" id="note" placeholder="Type here..."
										rows="1"></textarea>
								</div>
							</div>
						</div>
						<hr>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
					<button type="button" class="btn bg-primary" onclick="saveDaily()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function() {
		loadDataTable();
		loadDataTableDaily();
		
		$('#modal_accounting').on('hidden.bs.modal', function (e) {
			$('#temp').val('');
			$('#form_data')[0].reset();
			loadDataTable();
			loadDataTableDaily();
		});
		$('#modal_accounting_dailies').on('hidden.bs.modal', function (e) {
			$('#temp').val('');
			$('#form_data_dailies')[0].reset();
			loadDataTable();
			loadDataTableDaily();
		});
	});
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/setting/accounting/datatable") }}',
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
            { name: 'user_id', className: 'text-center align-middle' },
			{ name: 'branch', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'month', className: 'text-center align-middle' },
            { name: 'note', className: 'text-center align-middle' },
            /* { name: 'on_off', searchable: false, orderable: false, className: 'text-center align-middle' }, */
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}


	function loadDataTableDaily() {
      window.table = $('#datatable_serverside_daily').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[1, 'asc']],
         ajax: {
            url: '{{ url("admin/setting/accounting/datatable_daily") }}',
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
            { name: 'user_id', className: 'text-center align-middle' },
			{ name: 'branch', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'date', className: 'text-center align-middle' },
            { name: 'note', className: 'text-center align-middle' },
            /* { name: 'on_off', searchable: false, orderable: false, className: 'text-center align-middle' }, */
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function save(){
		$.ajax({
			 url: '{{ url("admin/setting/accounting/create") }}',
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
					notif('success', 'bg-success', response.message);
					$('#modal_accounting').modal('toggle');
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

	function saveDaily(){
		$.ajax({
			 url: '{{ url("admin/setting/accounting/create_daily") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_data_dailies')[0]),
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
					notif('success', 'bg-success', response.message);
					$('#modal_accounting').modal('toggle');
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
			 url: '{{ url("admin/setting/accounting/show") }}',
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
				$('#temp').val(id);
				$('#branch').val(response.branch);
				$('#month').val(response.month);
				$('#note').val(response.note);
				$('#modal_accounting').modal('toggle');
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
                  url: '{{ url("admin/setting/accounting/destroy") }}',
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

	function destroyDaily(id) {
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
                  url: '{{ url("admin/setting/accounting/destroy_daily") }}',
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
                        $('#datatable_serverside_daily').DataTable().ajax.reload(null, false);
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

	function updateStatus(id,element){
		var status = '0';
		if($(element).is(':checked')){
			status = '1';
		}
		
		$.ajax({
		  url: '{{ url("admin/setting/accounting/update_status") }}',
		  type: 'POST',
		  dataType: 'JSON',
		  data: {
			 id: id,
			 val: status
		  },
		  headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  },
		  success: function(response) {
			 if(response.status == 200) {
				notif('success', 'bg-success', response.message);
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
	}

	function getLatestMonth(branch){
		$.ajax({
		  url: '{{ url("admin/setting/accounting/get_latest_month") }}',
		  type: 'GET',
		  dataType: 'JSON',
		  data: {
			 branch: branch.value,
		  },
		  headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  },
		  success: function(response) {
			$('#month').val(response.month);
		  },
		  error: function() {
			 swalInit.fire({
				title: 'Server Error',
				text: 'Please contact developer',
				type: 'error'
			 });
		  }
	   });
	}
	</script>