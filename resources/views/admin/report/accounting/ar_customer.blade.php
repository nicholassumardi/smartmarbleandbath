<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Account Receivable Customer</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="refresh()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accountingr</a>
					<span class="breadcrumb-item active">AR Customer</span>
				</div>
			</div>
		</div>
	</div>
	
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h2 class="card-title font-weight-bold">Filter</h2>
			</div>
         <div class="card-body">
            <div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Branch :</label>
						<select name="filter_branch" id="filter_branch" class="form-control">
							@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Status :</label>
						<select name="status" id="status" class="form-control">
							<option value="">All</option>
							<option value="1">Unpaid</option>
						</select>
					</div>
				</div>
            </div>
            <div class="form-group text-right">
               <button type="button" onclick="refresh()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
			   <button type="button" onclick="filter('reset')" class="btn bg-danger"><i class="icon-sync"></i></button>
            </div>
         </div>
      </div>
		<div class="card">
			<div class="card-header">
				<div class="row">
                    <div class="col-12">
                        <h6 id="title_periode" class="text-muted text-uppercase text-center font-weight-bold">
                            AR Customer
                    </div>
                </div>
                <div class="row justify-content-center">
                </div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Customer</th>
							<th>Total Receivable</th>
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
		refresh();
	});
	function refresh(){
		window.table = loadDataTable();
	}

	function loadDataTable() {
		return $('#datatable_serverside').DataTable({
			stateSave: true,
			serverSide: true,
			deferRender: true,
			destroy: true,
			iDisplayInLength: 10,
			order: [[1, 'desc']],
			ajax: {
			url: '{{ url("admin/report/accounting/ar_customer/datatable") }}',
			type: 'GET',
			data: {
				branch: $('#filter_branch').val(),
				status: $('#status').val(),
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
                { name: 'name', className: 'text-center align-middle' },
                { name: 'receivable', orderable: false, searchable: false, className: 'text-center align-middle' },
                { name: 'action', orderable: false, searchable: false, className: 'text-center align-middle details-control' },
			]
		}); 
	}


	function showARCardsRealCustomer(id, branch){
			$.ajax({
				type : "POST",
				url  : "{{ url('admin/report/accounting/aging_receivable/card') }}",
				data : {
					id : id,
					branch : branch
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				cache: false,
				success: function(data){
					if(data.status == '500'){
						notif('error', 'bg-danger', data.message);
					}else{
						var w = window.open('about:blank');
						w.document.open();
						w.document.write(data);
						w.document.close();
					}
				},
				error: function() {
				swalInit.fire({
					title: 'Ups, check your internet connection!',
					text: 'Ups. Sorry error.',
					type: 'error'
				});
				}
			});
		}


	function showARCardsCustomer(id, branch){
			$.ajax({
			type : "POST",
			url  : "{{ url('admin/report/accounting/aging_receivable/card_detail')}}",
			data : {
				id : id,
				branch : branch,
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			cache: false,
			success: function(data){
				if(data.status == '500'){
					notif('error', 'bg-danger', data.message);
				}else{
					var w = window.open('about:blank');
					w.document.open();
					w.document.write(data);
					w.document.close();
				}
			},
			error: function() {
			swalInit.fire({
				title: 'Ups, check your internet connection!',
				text: 'Ups. Sorry error.',
				type: 'error'
			});
			}
		});
	}

	function showUnpaidARCardsCustomer(id, branch){
			$.ajax({
			type : "POST",
			url  : "{{ url('admin/report/accounting/ar_customer/unpaid_receivable_card')}}",
			data : {
				id : id,
				branch : branch,
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			cache: false,
			success: function(data){
				if(data.status == '500'){
					notif('error', 'bg-danger', data.message);
				}else{
					var w = window.open('about:blank');
					w.document.open();
					w.document.write(data);
					w.document.close();
				}
			},
			error: function() {
			swalInit.fire({
				title: 'Ups, check your internet connection!',
				text: 'Ups. Sorry error.',
				type: 'error'
			});
			}
		});
	}

</script>