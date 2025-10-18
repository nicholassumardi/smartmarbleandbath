<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Cash Advance - {{ $user->name }}</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/hrd/employee') }}" class="btn bg-secondary btn-labeled btn-labeled-left mr-2">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
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
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Employee</a>
					<span class="breadcrumb-item active">Cash Advance - {{ $user->name }}</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of All Cash Advance</h5>
				<div class="header-elements">
					
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						<tr class="text-center">
							<th>#</th>
							<th>Date Borrow</th>
							<th>Type of Interest</th>
							<th>Percent Interest</th>
							<th>TOP</th>
							<th>Nominal</th>
							<th>Note</th>
							<th>Progress</th>
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
				<h5 class="modal-title" id="exampleModalLabel">Form Add / Edit Employee Cash Advance</h5>
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
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Date :<sup class="text-danger">*</sup></label>
										<input type="hidden" name="temp" id="temp">
										<input type="hidden" name="tempUser" id="tempUser" value="{{ $id }}">
										<input type="date" class="form-control" name="date_borrow" id="date_borrow">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Type of Interest :<span class="text-danger">*</span></label>
										<select name="interest_type" id="interest_type" class="custom-select">
											<option value="1">Fixed Rate</option>
											<option value="2">Moving Rate</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Percent Interest (%) / per payroll :	<span class="text-danger">*</span></label>
										<input type="number" class="form-control" name="interest_percent" id="interest_percent" value="2">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group form-group-feedback form-group-feedback-right" style="margin-top:8px;"><label>Term of Payment (based on payroll) :</label>
										<div class="position-relative">
											<input type="number" name="top" id="top" class="form-control" value="1">
											<div class="form-control-feedback font-weight-bold">
												@php
													$status = 'Undefined';
												
													if($user->employeeAllowance()->exists()){
														$status = $user->employeeAllowance()->latest()->first()->paymentType();
													}
													
													echo $status;
												@endphp
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Nominal :<span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="nominal" id="nominal" value="0" onkeyup="formatRupiah(this)">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="col-form-label">Note<span class="text-danger">*</span></label>
										<textarea class="form-control" name="note" id="note" rows="1">-</textarea>
									</div>
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
			$('#form_data').trigger('reset');
		});
		
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
	
	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/hrd/employee/loan/".$id."/datatable") }}',
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
            { name: 'date', orderable: false, className: 'text-center align-middle' },
            { name: 'type', searchable: false, className: 'text-center align-middle' },
            { name: 'percent', className: 'text-center align-middle' },
			{ name: 'top', className: 'text-center align-middle' },
			{ name: 'nominal', className: 'text-center align-middle' },
			{ name: 'note', className: 'text-center align-middle' },
			{ name: 'progress', orderable: false, searchable: false, className: 'text-center align-middle' },
			{ name: 'operation', orderable: false, searchable: false, className: 'text-center align-middle' },
         ]
      });
	}
	
	function rowDetail(data) {
	  var content = '';
	  $.ajax({
		 url: '{{ url("admin/hrd/employee/loan/".$id."/row_detail") }}',
		 type: 'GET',
		 async: false,
		 data: {
			id: $(data[0]).data('id')
		 },
		 success: function(response) {
			content += response;
		 },
		 error: function() {
			swalInit.fire({
			   title: 'Server Error',
			   text: 'Please contact developer',
			   type: 'error'
			});
		 }
	  });

	  return content;
	}
	
	function success() {
	  $('#modal_form').modal('hide');
	  $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}
	
	function create() {
	  $.ajax({
		 url: '{{ url("admin/hrd/employee/loan/create") }}',
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
		 url: '{{ url("admin/hrd/employee/loan/show") }}',
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
			$('#date_borrow').val(response.date_borrow);
			$('#interest_type').val(response.interest_type);
			$('#interest_percent').val(response.interest_percent);
			$('#top').val(response.top);
			$('#nominal').val(response.nominal);
			$('#note').val(response.note);
			
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
				  url: '{{ url("admin/hrd/employee/loan/destroy") }}',
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
					 } else {
						notif('error', 'bg-danger', response.message);
					 }
					 
					 notyConfirm.close();
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
				  url: '{{ url("admin/hrd/employee/loan/payment/destroy") }}',
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
					 } else {
						notif('error', 'bg-danger', response.message);
					 }
					 
					 notyConfirm.close();
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