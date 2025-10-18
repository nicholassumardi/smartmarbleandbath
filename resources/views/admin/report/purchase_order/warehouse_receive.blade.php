<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Warehouse Receive Project Report (SPB)</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Purchase Order</a>
					<span class="breadcrumb-item active">Warehouse Receive</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header">
				<div class="row">
                    <div class="col-12">
                        <h6 id="title_periode" class="text-muted text-uppercase text-center font-weight-bold">
                            All Periode</h6>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <label>Month Year</label>
                        <input type="month" name="filter" id="filter" class="form-control" value="">
                    </div>
                </div>
			</div>
			<div class="card-body">
				<div class="alert alert-info alert-styled-left alert-dismissible">
					<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
					<span class="font-weight-semibold">Info!</span> Searchable columns are only for <span class="font-weight-semibold">Date & Warehouse Receive Code (WR Code)</span>.</a>.
				</div>
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>No</th>
							<th>Date</th>
							<th>WR Code</th>
							<th>PO Code</th>
							<th>SO Code</th>
							<th>Customer</th>
							<th>Sales SO</th>
							<th>Supplier</th>
							<th>Proforma Code</th>
							<th>Item</th>
							<th>Qty</th>
							<th>Qty(M<sup>2</sup>)</th>
							<th>Price@</th>
							<th>Total + PPN</th>
							<th>DPP</th>
							<th>PPN</th>
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
	
	$('#filter').change(function () { 
		var date = new Date($(this).val()),
		month = date.toLocaleString('default', { month: 'long' }),              
		year =  date.getFullYear();
		$('#title_periode').html(`Periode `+month+` `+year);
		refresh();
	});
	
	function refresh(){
		window.table = loadDataTable();
	}

	function loadDataTable() {
      $('#datatable_serverside').DataTable({
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'desc']],
         ajax: {
            url: '{{ url("admin/report/purchase_order/warehouse_receive/datatable") }}',
            type: 'GET',
            data: {
               status: $('#filter_status').val(),
               filter_month : $('#filter').val(),
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
            { name: 'id', className: 'text-center align-middle' },
            { name: 'date', orderable: false, className: 'text-center align-middle' },
			{ name: 'wr_code', orderable: false, className: 'text-center align-middle' },
			{ name: 'po_code', orderable: false, className: 'text-center align-middle' },
            { name: 'so_code', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'customer', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'sales', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'supplier', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'proforma', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'item', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'qty', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'qtym2', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'price', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'total', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'dpp', searchable: false, orderable: false, className: 'text-center align-middle' },
            { name: 'ppn', searchable: false, orderable: false, className: 'text-center align-middle' },
         ]
      }); 
	}
</script>