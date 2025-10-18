<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Purchase Order Project</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn bg-violet-400 btn-labeled btn-labeled-left mr-2" data-toggle="modal" data-target="#modal_wip">
						<b>
							<i class="icon-cogs"></i>
						</b> WIP Report
					</button>
					<button class="btn bg-indigo-400 btn-labeled btn-labeled-left mr-2" data-toggle="modal" data-target="#modal_downpayment" onclick="alert('komingsun!')">
						<b>
							<i class="icon-piggy-bank"></i>
						</b> Down Payment
					</button>
					<button class="btn bg-teal-400 btn-labeled btn-labeled-left" onclick="showPurchaseBill()">
						<b>
							<i class="icon-wallet"></i>
						</b> Purchase Bills
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Data</a>
					<span class="breadcrumb-item active">Project</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
			
				<div class="form-group row">
					<div class="col-md-3 p-3">
						<div class="card" style="height:150px !important;">
							<div class="card-body" style="background-color:#1b4c91 !important;height:90px !important;color:white;border-radius:15px;border:1px solid black;">
								<div class="d-flex float-right">
									<h3 class="font-weight-semibold mb-0" style="font-size:30px;">{{ $totalunmatch }}</h3>
								</div>
								<div style="margin-top:50px;">
									<div class="font-weight-semibold" style="font-size:14px;">
										Unmatch PO & DO
									</div>
								</div>
								<div class="float-right" style="margin-top:0px;">
									<button class="btn btn-primary btn-sm" onclick="detailUnmatch()"><i class="icon-file-spreadsheet mr-1"></i> See details</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 p-3">
						<div class="card" style="height:150px !important;">
							<div class="card-body" style="background-color:#b7c8d2 !important;height:90px !important;color:white;border-radius:15px;border:1px solid black;">
								<div class="d-flex float-right">
									<h3 class="font-weight-semibold mb-0" style="font-size:30px;">{{ $totalunmatchso }}</h3>
								</div>
								<div style="margin-top:50px;">
									<div class="font-weight-semibold" style="font-size:14px;">
										Total Unmatch SO & PO
									</div>
								</div>
								<div class="float-right mt-1">
									<button class="btn btn-primary btn-sm" onclick="detailUnmatchSO()"><i class="icon-file-spreadsheet mr-1"></i> See details</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 p-3">
						<div class="card {{ $totalunmatchpowr > 0 ? 'blink-notification' : '' }}" style="height:150px !important;">
							<div class="card-body" style="background-color:#ff0000 !important;height:90px !important;color:white;border-radius:15px;border:1px solid black;">
								<div class="d-flex float-right">
									<h3 class="font-weight-semibold mb-0" style="font-size:30px;">{{ $totalunmatchpowr }}</h3>
								</div>
								<div style="margin-top:50px;">
									<div class="font-weight-semibold" style="font-size:14px;">
										Total Unmatch PO & WR
									</div>
								</div>
								<div class="float-right mt-1">
									<button class="btn btn-primary btn-sm" onclick="detailPOWR()"><i class="icon-file-spreadsheet mr-1"></i> See details</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<h5 class="card-title">List Data Projects</h5>
				@if( App\Models\User::find(session('bo_id'))->branch == '1')
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
							  <label>Branch :<span class="text-danger">*</span></label>
							  <select name="filter_branch" id="filter_branch" class="custom-select">
								 <option value="">-- Choose --</option>
								 @foreach (DB::table('company_entities')->get() as $company)
									<option value="{{$company->id}}">{{$company->name}}</option>
								@endforeach
							  </select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group text-right">
								<label>&nbsp;</label>
								<div class="input-group">
									<button type="button" onclick="loadDataTable()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
									<button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="alert alert-danger alert-styled-left alert-dismissible">
								<span class="font-weight-semibold">Info!</span><b> Red rows means that the project doesn't have budgeting project.</b>
							</div>
						</div>
					</div>
				@endif
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>#</th>
							<th>No</th>
							<th>Code</th>
							<th>User</th>
							<th>Sales</th>
							<th>Customer</th>
							<th>Project</th>
							<th>Progress</th>
							<th>Action</th>
						 </tr>
					  </thead>
				   </table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_wip" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title">WIP (Work in Progress) Report</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
						  <select name="filter_branch_wip" id="filter_branch_wip" class="custom-select">
							 <option value="">Choose Branch</option>
							 <option value="1">PTA</option>
							 <option value="2">SMB</option>
						  </select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<button class="btn bg-success btn-block" onclick="showWipReport()"><i class="icon-search4"></i> Process</button>
						</div>
					</div>
					<div class="col-md-12">
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<span class="font-weight-semibold">Info!</span><b> Choose branch and press Process button.</b>
						</div>
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Report Detail <span id="judul"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12" id="data_report">
					
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_bill" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">List of Purchase Bills</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12" id="data_purchase_bill">
						
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<span class="bg-primary p-1">Blue Status : Done with payments</span>
				<span class="bg-success p-1">Green Status : More than 7 days to due date</span>
				<span class="bg-warning p-1">Orange Status : 1 to 7 days to due date</span>
				<span class="bg-danger p-1">Red Status : More than due date (expired)</span>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_notes" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add Pre Project Notes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-5">
					  <div class="form-group">
					    <input type="hidden" name="tempProject" id="tempProject">
						<input type="text" class="form-control" name="purchase-note" id="purchase-note" placeholder="Type note">
					  </div>
					</div>
					<div class="col-md-5">
					  <div class="form-group">
						 <div class="input-group">
						   <div class="custom-file">
							  <input type="file" id="purchase-file" name="purchase-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
						   </div>
						</div>
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
						 <button type="button" onclick="addProjectNote()" class="btn bg-success col-12" id="btnaddpurchasenote"><i class="icon-plus2"></i> Add</button>
					  </div>
					</div>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
					<h5><b><span class="badge badge-danger">9.i</span> List of All Notes</b></h5>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead class="table-secondary">
								<tr class="text-center">
								   <th width="25%">Date</th>
								   <th>Project</th>
								   <th>Note</th>
								   <th>Proof</th>
								   <th>Is Public</th>
								</tr>
							 </thead>
							 <tbody id="data_purchase_note">
								<tr>
									<td colspan="4">
										<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes here.</div>
									</td>
								</tr>
							 </tbody>
						</table>
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
<script>
	function resetFilter() {
		$('#filter_branch').val(null);
		loadDataTable();
	}

   $(function() {
      loadDataTable();
	  
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
	  
		$('#modal_notes').on('hidden.bs.modal', function (e) {
			loadDataTable();
		});
   });
	
	function showPurchaseBill(){
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_purchase_bill") }}',
			type: 'GET',
			async: false,
			data: {
				
			},
			success: function(response) {
				$('#data_purchase_bill').html(response.content);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_bill').modal('toggle');
	}
	
	function rowDetail(data) {
      var content = '';
      $.ajax({
         url: '{{ url("admin/purchase_order/project/row_detail_purchase") }}',
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
	
   function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
	     stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         ajax: {
            url: '{{ url("admin/purchase_order/project/datatable") }}',
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
			{ name: 'detail', orderable: false, searchable: false, className: 'text-center align-middle details-control' },
            { name: 'id', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'code', orderable: false, className: 'text-center align-middle' },
            { name: 'user_id', orderable: false, className: 'text-center align-middle' },
			{ name: 'sales', orderable: false, className: 'text-center align-middle' },
			{ name: 'customer', orderable: false, className: 'text-center align-middle' },
            { name: 'name', orderable: false, className: 'text-center align-middle' },
            { name: 'progress', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
		 "createdRow": function( row, data, dataIndex){
			if( data[9] == ''){
				$(row).addClass('bg-danger');
			}
		 },
      }); 
   }
   
	function detailUnmatch() {
		$('#judul').html('Unmatch PO & Delivery');
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_report") }}',
			type: 'GET',
			async: false,
			data: {
				mode : 'unmatch'
			},
			success: function(response) {
				$('#data_report').html(response.contentUnmatch);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_form').modal('toggle');
	}
	
	function detailUnmatchSO() {
		$('#judul').html('Unmatch SO & PO');
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_report") }}',
			type: 'GET',
			async: false,
			data: {
				mode : 'unmatchso'
			},
			success: function(response) {
				$('#data_report').html(response.contentUnmatchSo);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_form').modal('toggle');
	}
	
	function detailPOWR() {
		$('#judul').html('Ummatch PO and WR');
		loadingOpen('.modal-body');
		$.ajax({
			url: '{{ url("admin/purchase_order/project/get_report") }}',
			type: 'GET',
			async: false,
			data: {
				mode : 'unmatchpowr'
			},
			success: function(response) {
				$('#data_report').html(response.contentUnmatchPoWr);
				loadingClose('.modal-body');
			},
			error: function() {
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			}
		});
		
		$('#modal_form').modal('toggle');
	}
	
	var tempProject = 0;
	
	function addNotes(id){
		$('#tempProject').val(id);
		tempProject = id;
		
		$.ajax({
			 url: '{{ url("admin/purchase_order/project/get_project_note") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: tempProject
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				$('#data_purchase_note').empty();
				if(response.length > 0){
					$.each(response, function(i, val) {
						var checkedbox = '';
						if(val.is_public == '1'){
							checkedbox = 'checked';
						}
						$('#data_purchase_note').append(`
							<tr class="text-center">
								<td>` + val.created_at + `</td>
								<td>` + val.code + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
								<td>
									<label class="form-check-label">
										<input type="checkbox" class="form-check-input" onclick="isPublic(this,` + val.id + `)" style="margin-top:.125rem;margin-left: -1.5rem;transform: scale(1.25);" ` + checkedbox + ` >
										YES
									</label>
								</td>
							</tr>
						`);
					});
				}else{
					$('#data_purchase_note').append(`
						<tr>
							<td colspan="5">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes data.</div>
							</td>
						</tr>
					`);
				}
				
				loadingClose('.modal-content');
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
		
		$('#modal_notes').modal('toggle');
	}
	
	function addProjectNote(){
		var id = $('#tempProject').val(), note = $('#purchase-note').val();
		var fd = new FormData(), files = $('#purchase-file')[0].files;
		fd.append('note',note);
		fd.append('mode','pre_purchase');
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/add_pre_project_note") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_purchase_note');
			},
			success: function(response) {
				$('#data_purchase_note').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						if(val.is_public == '1'){
							var status = 'checked';
						}else{
							var status = '';
						}
						$('#data_purchase_note').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString('en-GB') + `</td>
								<td>` + val.code + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
								<td>
									<label class="form-check-label">
										<input type="checkbox" class="form-check-input" onclick="isPublic(this,` + val.id + `)" style="margin-top:.125rem;margin-left: -1.5rem;transform: scale(1.25);" ` + status + `>
										YES
									</label>
								</td>
							</tr>
						`);
					});
				}else{
					$('#data_purchase_note').append(`
						<tr>
							<td colspan="5">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#purchase-file').val('');
				$('#purchase-note').val('');
				
				loadingClose('#data_purchase_note');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function isPublic(element,value){
		var nilai = '';
		if ($(element).is(":checked"))
		{
			nilai = '1';
		}else{
			nilai = '0';
		}
		
		$.ajax({
			url: '{{ url("admin/purchase_order/project/update_status_note") }}',
			type: 'POST',
			dataType: 'JSON',
			data: { nilai : nilai, id : value },
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				loadingOpen('.modal-body');
			},
			success: function(response) {
				if(response.status == '200'){
					loadingClose('.modal-body');
					notif('success', 'bg-success', 'Succesfully change public status.');
				}else{
					swalInit.fire('Warning!', response.message, 'error');
				}
			},
			error: function() {
				loadingClose('.modal-body');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function showWipReport(){
		alert('coming soon');
	}
</script>