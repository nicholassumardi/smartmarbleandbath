<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Invoice Retail</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="loadDataTable()">
						<b><i class="icon-sync"></i></b> Refresh Data
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Invoice</a>
					<span class="breadcrumb-item active">Retail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      @if(session('success'))
			<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
				<span class="font-weight-semibold">Success!</span> 
				{!! session('success') !!}
			</div>
		@endif
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Customer :</label>
                     <select name="filter_customer_id" id="filter_customer_id" class="select2">
                        <option value="">All Customer</option>
                        @foreach($customer as $c)
                           <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Nominal :</label>
                     <select name="filter_nominal" id="filter_nominal" class="custom-select">
                        <option value="">All Nominal</option>
                        <option value="1">Hundreds</option>
                        <option value="2">Millions</option>
                        <option value="3">Billions</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label>Status :</label>
                     <select name="filter_status" id="filter_status" class="custom-select">
                        <option value="">All Status</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="full_payment">Full Payment</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Date :</label>
                     <div class="input-group">
                        <input type="date" name="filter_start_date" id="filter_start_date" max="{{ date('Y-m-d') }}" class="form-control">
                        <div class="input-group-prepend">
                           <span class="input-group-text">To</span>
                        </div>
                        <input type="date" name="filter_finish_date" id="filter_finish_date" max="{{ date('Y-m-d') }}" class="form-control">
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group"><hr></div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <div class="text-right">
                        <button type="button" onclick="loadDataTable()" class="btn bg-teal mr-2"><i class="icon-filter4"></i> Search</button>
						<button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
		<div class="card">
			<div class="card-header header-elements-inline mb-3">
				<h5 class="card-title">List Data</h5>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
                        <th>Customer</th>
                        <th>Code</th>
                        <th>Payment</th>
                        <th>Grandtotal</th>
                        <th>Change</th>
                        <th>Status</th>
                        <th>Date</th>
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
      loadDataTable();
   });

   function resetFilter() {
      $('#filter_customer_id').val(null).change();
      $('#filter_nominal').val(null);
      $('#filter_status').val(null);
      $('#filter_start_date').val(null);
      $('#filter_finish_date').val(null);
      loadDataTable();
   }

   function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[6, 'desc']],
         ajax: {
            url: '{{ url("admin/invoice/retail/datatable") }}',
            type: 'GET',
            data: {
               customer_id: $('#filter_customer_id').val(),
               nominal: $('#filter_nominal').val(),
               status: $('#filter_status').val(),
               start_date: $('#filter_start_date').val(),
               finish_date: $('#filter_finish_date').val()
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
            { name: 'customer_id', className: 'text-center align-middle' },
            { name: 'invoice', className: 'text-center nowrap align-middle' },
            { name: 'payment', searchable: false, className: 'text-center nowrap align-middle' },
            { name: 'grandtotal', searchable: false, className: 'text-center nowrap align-middle' },
            { name: 'change', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'status', searchable: false, orderable: false, className: 'text-center nowrap align-middle' },
            { name: 'created_at', searchable: false, className: 'text-center nowrap align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }
</script>