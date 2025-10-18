<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Report Payment Monthly</span>
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
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Delivery Order</a>
					<span class="breadcrumb-item active">Payment</span>
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
							Periode {{ date('F Y', strtotime($filter)) }}</h6>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md-3">
						<label>Month Year</label>
						<input type="month" name="filter" id="filter" class="form-control" value="{{ date('Y-m') }}">
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable_serverside" class="table table-bordered table-striped w-100">
						<thead class="bg-dark">
							<tr class="text-center">
								<th>#</th>
								<th>No</th>
								<th>User</th>
								<th>Date</th>
								<th>To</th>
								<th>Customer</th>
								<th>Nominal</th>
								<th>Note</th>
								<th>Proof</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	{{-- MODAL --}}
	<div id="modal_detail_product" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Detail <b id="modal_title">A</b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title">
						<b>Detail Products</b>
						<a href="javascript:void(0);" target="_blank" id="show_invoice_other"
							class="btn btn-primary btn-sm float-right ml-1">
							<span>Invoice Other </span>
							<i class="icon-file-pdf"
								title="Invoice Other"></i></a>
						<a href="javascript:void(0);" target="_blank" id="show_invoice"
							class="btn btn-info btn-sm float-right ml-1">
							<span>Invoice Product </span>
							<i class="icon-file-pdf"
								title="Invoice Product"></i></a>
						<a href="javascript:void(0);" target="_blank" id="show_letter_way"
							class="btn btn-warning btn-sm float-right ml-1">
							<span>Surat Jalan/ DO </span>
							<i class="icon-file-pdf"
								title="Surat Jalan"></i></a>
						<a href="javascript:void(0);" target="_blank" id="show_sales_bill"
							class="btn btn-warning btn-sm float-right ml-1">
							<span>Sales Bill </span>
							<i class="icon-file-pdf"
								title="SalesBill"></i></a>
					</h5>
					<div class="form-group">
						<hr>
					</div>
					<div class="form-group">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead class="table-secondary">
									<tr class="text-center">
										<th>No</th>
										<th>Product</th>
										<th>Qty</th>
										<th>Unit</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody id="data_product_detail">

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(function() {
		refresh();
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

	function rowDetail(data) {
		var content = '';
		$.ajax({
			url: '{{ url("admin/report/delivery_order/payment/row_detail") }}',
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

	function success(){
		reset();
		$('#modal_form').modal('hide');
		$('#datatable_serverside').DataTable().ajax.reload(null, false);
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
			url: '{{ url("admin/report/delivery_order/payment/datatable") }}',
			type: 'GET',
			data: {
				filter: $('#filter').val()
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
			{ name: 'id', searchable: false, className: 'text-center align-middle' },
			{ name: 'user', className: 'text-center align-middle' },
			{ name: 'date', className: 'text-center align-middle' },
			{ name: 'coa', className: 'text-center align-middle' },
			{ name: 'customer', className: 'text-center align-middle' },
			{ name: 'nominal', className: 'text-center align-middle' },
			{ name: 'note', className: 'text-center align-middle' },
			{ name: 'proof', searchable: false, orderable: false, className: 'text-center align-middle' }
			]
		}); 
	}

		
	function showDetailProduct(element, id, mode){
		$.ajax({
		 url: '{{ url("admin/report/delivery_order/payment/get_detail_product") }}',
		 type: 'GET',
         dataType: 'JSON',
         data: {
            id : id
         },
         beforeSend: function() {
			 loadingOpen('.order-2');
         },
         success: function(response) {
			$('#modal_title').html(element.innerHTML);


			if(response.data.length > 0) {
				$('#data_product_detail').empty();
				
				if(response.letter_way != ''){
					$('#show_letter_way').removeAttr('hidden');		
					$('#show_invoice').removeAttr('hidden');
					$('#show_invoice_other').removeAttr('hidden');
					$('#show_sales_bill').attr("hidden",true);

					$('#show_letter_way').attr("href", response.letter_way)
					$('#show_invoice').attr("href", response.invoice)
					$('#show_invoice_other').attr("href", response.invoice_other)
				}else{
					$('#show_letter_way').attr("hidden",true);		
					$('#show_invoice').attr("hidden",true);
					$('#show_invoice_other').attr("hidden",true);
					

					$('#show_sales_bill').removeAttr('hidden');
					$('#show_sales_bill').attr("href", response.sales_bill)
				}
				var no = 1;
				
				$.each(response.data, function(i, val) {
					$('#data_product_detail').append(`
						<tr class="text-center">
						 <td>` + no + `</td>
						 <td class="align-middle">` + val.product_name + `</td>
						 <td class="align-middle">
							` + val.qty + `
						 </td>
						 <td class="align-middle">
							Box
						 </td>
						 <td class="align-middle">
							` + val.price + `
						 </td>
					  </tr>
					`);
					
					no++;
				});
				
				$('#modal_detail_product').modal('toggle');
				
				loadingClose('.order-2');
			}
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}





	</script>