<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Budgeting</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<div class="btn-group">
					  <button type="button" class="btn bg-primary btn-labeled btn-labeled-left dropdown-toggle" data-toggle="dropdown">
						 <b><i class="icon-plus3"></i></b> Add
					  </button>
					  <div class="dropdown-menu dropdown-menu-right">
						 <a href="javascript:void(0);" class="dropdown-item" onclick="cancel()" data-toggle="modal" data-target="#modal_form">Single Month</a>
						 <a href="{{ url('admin/accounting/budgeting/yearly') }}" class="dropdown-item">Yearly</a>
					  </div>
				   </div>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Budgeting</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Filter</h5>
			</div>
         <div class="card-body">
			<div class="form-group">
			  <label>Branch :<span class="text-danger">*</span></label>
			  <select name="filter_branch" id="filter_branch" class="custom-select">
				 <option value="">-- Choose --</option>
             @foreach (DB::table('company_entities')->get() as $company)
                <option value="{{$company->id}}">{{$company->name}}</option>
             @endforeach
			  </select>
			</div>
            <!-- <div class="form-group">
               <label>Month :</label>
               <div class="input-group-prepend">
                  <input type="month" name="filter_start_date" id="filter_start_date" class="form-control">
                  <span class="input-group-text">to</span>
                  <input type="month" name="filter_finish_date" id="filter_finish_date" class="form-control">
               </div>
            </div> -->
            <div class="form-group text-right">
               <button type="button" onclick="loadDataTable()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
			   <button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
            </div>
         </div>
      </div>
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100 display nowrap">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>#</th>
							<th>No</th>
							<th>Branch</th>
							<th>Month</th>
							<th>Total</th>
							<th>Action</th>
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
            <h5 class="modal-title" id="exampleModalLabel">Form Budgeting Single Monthly</h5>
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
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  <label>Branch :<span class="text-danger">*</span></label>
								  <select name="branch" id="branch" class="custom-select">
                            @foreach (DB::table('company_entities')->get() as $company)
                             <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
								  </select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Project (Optional) :</label>
									<select name="project_id" id="project_id" class="select2">
									   <option value="">-- None --</option>
									   @foreach($project as $p)
										  <option value="{{ $p->id }}">[{{ $p->code }}] {{ $p->customer->name }}</option>
									   @endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  <label>Start Month :<span class="text-danger">*</span></label>
								  <input type="month" name="startmonth" id="startmonth" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  <label>End Month :<span class="text-danger">*</span></label>
								  <input type="month" name="endmonth" id="endmonth" class="form-control">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
						  <label>COA :<span class="text-danger">*</span></label>
						  <select name="coa_id" id="coa_id" class="select2">
							   <option value="">-- None --</option>
							   @foreach($coa as $c)
								  <option value="{{ $c->id }}">[{{ $c->code }}] {{ $c->name }}</option>
							   @endforeach
							</select>
						</div>
					</div>
					<div class="col-md-8">
						<button type="button" class="btn bg-success col-12" onclick="addDetail()"><i class="icon-plus22"></i> Add</button>
					</div>
					<div class="col-md-8 table-responsive">
						<table class="table table-bordered">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>Coa</th>
							   <th>Nominal</th>
							   <th>Delete</th>
							</tr>
						 </thead>
						 <tbody id="data_content">
							@foreach($coa->where('parent_id',0) as $c)
								@if(floatval(substr($c->code,0,1)) >= 4)
									@if(count($c->child()) == 0)
										<input type="hidden" name="coa_id[]" value="{{ $c->id }}">
										<tr>
											<td>[{{ $c->code }}] {{ $c->name }}</td>
											<td><input type="text" name="nominal[]" id="nominal" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)"></td>
											<td><button type="button" id="delete_budgeting_data" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
										</tr>
									@endif
									@foreach($c->child() as $rowchild)
										@if(count($rowchild->child()) == 0)
											<input type="hidden" name="coa_id[]" value="{{ $rowchild->id }}">
											<tr>
												<td>[{{ $rowchild->code }}] {{ $rowchild->name }}</td>
												<td><input type="text" name="nominal[]" id="nominal" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)"></td>
												<td><button type="button" id="delete_budgeting_data" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
											</tr>
										@endif
										@foreach($rowchild->child() as $rowgrandchild)
											@if(count($rowgrandchild->child()) == 0)
												<input type="hidden" name="coa_id[]" value="{{ $rowgrandchild->id }}">
												<tr>
													<td>[{{ $rowgrandchild->code }}] {{ $rowgrandchild->name }}</td>
													<td><input type="text" name="nominal[]" id="nominal" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)"></td>
													<td><button type="button" id="delete_budgeting_data" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
												</tr>
											@endif
											@foreach($rowgrandchild->child() as $rowgrandgrandchild)
												<input type="hidden" name="coa_id[]" value="{{ $rowgrandgrandchild->id }}">
												<tr>
													<td>[{{ $rowgrandgrandchild->code }}] {{ $rowgrandgrandchild->name }}</td>
													<td><input type="text" name="nominal[]" id="nominal" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)"></td>
													<td><button type="button" id="delete_budgeting_data" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
												</tr>
											@endforeach
										@endforeach
									@endforeach
								@endif
						   @endforeach
						 </tbody>
						</table>
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
		$('#data_content').on('click', '#delete_budgeting_data', function() {
			$(this).closest('tr').remove();
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
   
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/accounting/budgeting/row_detail") }}',
         type: 'GET',
         async: false,
         data: {
            month: $(data[0]).data('month'),
			branch: $(data[0]).data('branch')
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

   function resetFilter() {
		$('#filter_branch').val(null);
		loadDataTable();
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
	  $('#data_content').empty();
      $('#validation_alert').hide();
      $('#validation_content').html('');
   }

   function reset() {
      $('#form_data').trigger('reset');
      $('#coa_id').val(null).change();
      $('#validation_alert').hide();
      $('#validation_content').html('');
   }

   function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
   }
   
   function successUpdate() {
      $('#form_data_detail').trigger('reset');
      $('#coa_id').val(null).change();
      $('#validation_alert_detail').hide();
      $('#validation_content_detail').html('');
      $('#modal_form_detail').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
   }

	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 searching: false,
		 lengthChange: false,
         serverSide: true,
         deferRender: true,
         destroy: true,
		 paging:   false,
         iDisplayInLength: 10,
         ajax: {
            url: '{{ url("admin/accounting/budgeting/datatable") }}',
            type: 'GET',
            data: {
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
			{ name: 'detail', searchable: false, orderable: false, className: 'text-center align-middle details-control' },
			{ name: 'no', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'branch', orderable: false, className: 'text-center align-middle' },
            { name: 'month', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'total', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }
	
	function addDetail(){
		let coa_id   = $('#coa_id option:selected');

		if(coa_id.val()) {			
			$('#data_content').append(`
				<tr>
					<input type="hidden" name="coa_id[]" value="` + coa_id.val() + `">
					<td>` + coa_id.text() + `</td>
					<td><input type="text" name="nominal[]" id="nominal" class="form-control form-control-sm" placeholder="Enter nominal" value="0" onkeyup="formatRupiah(this)"></td>
					<td><button type="button" id="delete_budgeting_data" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
				</tr>
			`);
		} else {
			swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
		}
	}
	
   function create() {
      $.ajax({
         url: '{{ url("admin/accounting/budgeting/create") }}',
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
			   setTimeout(function(){ 
					location.reload();
			   }, 2000);
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

	function show(month,branch) {
      toShow();
      $.ajax({
         url: '{{ url("admin/accounting/budgeting/show") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            month: month,
			branch: branch
         },
         beforeSend: function() {
            loadingOpen('#modal_form');
         },
         success: function(response) {
            loadingClose('#modal_form');
			$('#branch').val(response.branch);
			$('#startmonth').val(response.month);
			$('#endmonth').val(response.month);
			
			$.each(response.data, function(i, val) {
				$('#data_content').append(`
					<tr>
						<input type="hidden" name="coa_id[]" value="` + val.coa_id + `">
						<td>[` + val.coa_code + `] ` + val.coa_name + `</td>
						<td><input type="text" name="nominal[]" id="nominal" class="form-control form-control-sm" placeholder="Enter nominal" value="` + val.nominal + `" onkeyup="formatRupiah(this)"></td>
						<td><button type="button" id="delete_budgeting_data" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
					</tr>
				`);
			});
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
         url: '{{ url("admin/accounting/budgeting/update") }}' + '/' + id,
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data_detail').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert_detail').hide();
            $('#validation_content_detail').html('');
            loadingOpen('#modal_form_detail');
         },
         success: function(response) {
            loadingClose('#modal_form_detail');
            if(response.status == 200) {
               successUpdate();
               notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert_detail').show();
               notif('warning', 'bg-warning', 'Validation');
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content_detail').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            } else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            loadingClose('#modal_form_detail');
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
                  url: '{{ url("admin/accounting/budgeting/destroy") }}',
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