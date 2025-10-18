<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">DO Retail</span>
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
					<a href="javascript:void(0);" class="breadcrumb-item">Delivery Order</a>
					<span class="breadcrumb-item active">Retail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Order :</label>
                     <select name="filter_order_id" id="filter_order_id" class="select2">
                        <option value="">All Order</option>
                        @foreach($order as $o)
                           <option value="{{ $o->id }}">{{ $o->number }}</option>
                        @endforeach
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
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Data</h5>
			</div>
			<div class="card-body">
            <div class="table-responsive">
               <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                  <thead class="bg-dark">
                     <tr class="text-center">
                        <th>No</th>
                        <th>Code</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Delivery Date</th>
                        <th>Action</th>
                     </tr>
                  </thead>
               </table>
            </div>
			</div>
		</div>
	</div>

<div class="modal fade" id="modal_information" data-backdrop="static" role="dialog">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-light">
            <h5 class="modal-title" id="exampleModalLabel">Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h5 class="mb-3">Data</h5>
            <div class="row">
               <div class="col-md-6">
                  <div class="alert alert-secondary">
                     <div class="text-center">
                        <div class="font-weight-bold text-uppercase">Code</div>
                        <div class="form-group"><hr></div>
                        <div class="font-weight-semibold" id="order_delivery_code"></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="alert alert-secondary">
                     <div class="text-center">
                        <div class="font-weight-bold text-uppercase">Status</div>
                        <div class="form-group"><hr></div>
                        <div class="font-weight-semibold" id="order_delivery_status"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="form-group">
               <hr class="border-5 border-secondary">
            </div>
            <h5 class="mb-3">Shipping Address</h5>
            <table class="table table-lg">
               <tbody>
                  <tr>
                     <th width="20%">Name</th>
                     <td><b>:</b> <span id="order_shipping_name"></span></td>
                  </tr>
                  <tr>
                     <th width="20%">Email</th>
                     <td><b>:</b> <span id="order_shipping_email"></span></td>
                  </tr>
                  <tr>
                     <th width="20%">Phone</th>
                     <td><b>:</b> <span id="order_shipping_phone"></span></td>
                  </tr>
                  <tr>
                     <th width="20%">City</th>
                     <td><b>:</b> <span id="order_shipping_city"></span></td>
                  </tr>
                  <tr>
                     <th width="20%">Type Of Transport</th>
                     <td><b>:</b> <span id="order_shipping_transport"></span></td>
                  </tr>
                  <tr>
                     <th width="20%">Address</th>
                     <td><b>:</b> <span id="order_shipping_address"></span></td>
                  </tr>
               </tbody>
            </table>
            <div class="form-group">
               <hr class="border-5 border-secondary">
            </div>
            <h5 class="mb-3">Product</h5>
            <div class="table-responsive">
               <table class="table table-borderd table-striped">
                  <thead class="table-secondary">
                     <tr class="text-center">
                        <th>Image</th>
                        <th>Product</th>
                        <th>Qty</th>
                     </tr>
                  </thead>
                  <tbody id="data_order_detail"></tbody>
               </table>
            </div>
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
         </div>
      </div>
   </div>
</div>

<script>
   $(function() {
      loadDataTable();
   });

   function resetFilter() {
      $('#filter_order_id').val(null).change();
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
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/delivery_order/retail/datatable") }}',
            type: 'GET',
            data: {
               order_id: $('#filter_order_id').val(),
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
            { name: 'delivery_order', className: 'text-center align-middle' },
            { name: 'order_id', className: 'text-center align-middle' },
            { name: 'status', orderable: false, searchable: false, className: 'text-center align-middle' },
            { name: 'created_at', searchable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
   }

   function information(id) {
      $('#modal_information').modal('show');
      $.ajax({
         url: '{{ url("admin/delivery_order/retail/information") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id: id
         },
         beforeSend: function() {
            loadingOpen('.modal-content');
            $('#data_order_detail').html('');
         },
         success: function(response) {
            loadingClose('.modal-content');
            $('#order_delivery_code').html(response.order_delivery.code);
            $('#order_delivery_status').html(response.order_delivery.status);
            $('#order_shipping_name').html(response.order_shipping.name);
            $('#order_shipping_email').html(response.order_shipping.email);
            $('#order_shipping_phone').html(response.order_shipping.phone);
            $('#order_shipping_city').html(response.order_shipping.city);
            $('#order_shipping_transport').html(response.order_shipping.fleet + '&nbsp;&nbsp;(' + response.order_shipping.vendor + ')');
            $('#order_shipping_address').html(response.order_shipping.address);

            $.each(response.order_detail, function(i, val) {
               $('#data_order_detail').append(`
                  <tr class="text-center">
                     <td class="align-middle">
                        <img src="` + val.image + `" width="80" class="img-fluid img-thumbnail">
                     </td>
                     <td class="align-middle">` + val.product + `</td>
                     <td class="align-middle">` + val.qty + `</td>
                  </tr>
               `);
            });
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