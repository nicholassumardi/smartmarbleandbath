<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Delivery Project Status</span>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
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
                </div>
                <div class="form-group">
                    <hr>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Month</label>
                            <input type="month" name="filter_month" id="filter_month" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group text-right">
                            <label>&nbsp;</label>
                            <div class="input-group">
                                <button type="button" onclick="loadDataTable()" class="btn bg-purple mr-2"><i
                                        class="icon-filter4"></i> Search</button>
                                <button type="button" onclick="resetFilter()" class="btn bg-danger"><i
                                        class="icon-sync"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="table-responsive">
                    <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                        <thead class="bg-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Date</th>
                                <th>Project</th>
                                <th>DO Code</th>
                                <th>Sales</th>
                                <th>Customer</th>
                                <th>INV Product</th>
                                <th>INV Other</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function resetFilter() {
		$('#filter_branch').val(null);
		$('#filter_month').val('');
		loadDataTable();
	}

	
	$(function() {
      loadDataTable();
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
            url: '{{ url("admin/delivery_order/delivery_status/datatable") }}',
            type: 'GET',
            data: {
               month: $('#filter_month').val(),
               branch: $('#filter_branch').val(),
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
            { name: 'created_at', searchable: false, className: 'text-center align-middle' },
            { name: 'project_id', searchable: false, className: 'text-center align-middle' },
            { name: 'code', searchable: false, className: 'text-center align-middle' },
            { name: 'user_id', searchable: false, className: 'text-center align-middle' },
            { name: 'user_id', searchable: false, className: 'text-center align-middle' },
            { name: null, searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: null, searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: null, searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: null, searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
      }); 
	}

    function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
	}
   
    </script>