<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Detail Invoice Retail</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/invoice/retail') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
					<a href="{{ url('admin/invoice/retail/print/' . $order->id) }}" class="btn bg-success ml-2 btn-labeled btn-labeled-left">
						<b><i class="icon-printer2"></i></b> Print
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Invoice</a>
					<a href="{{ url('admin/invoice/retail') }}" class="breadcrumb-item">Retail</a>
					<span class="breadcrumb-item active">Detail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		@if($errors->any())
			<div class="alert alert-danger">
				<ul>
					@php $validation = array_unique($errors->all()); @endphp
					@foreach($validation as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@elseif(session('success'))
			<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
				<span class="font-weight-semibold">Success!</span> 
				{{ session('success') }}
			</div>
		@endif
		<form action="" method="POST" id="form_data">
			@csrf
			<div class="card">
				<div class="card-body" style="background-color: white !important;">
					<div class="row">
						<div class="col-md-4">
							<center>
								<img src="{{ asset('website/logo-black.png') }}" height="80" class="mt-4">
							</center>
						</div>
						<div class="col-md-8 text-right">
							<h4 class="font-weight-bold">PT. PERWIRA TAMARAYA ABADI</h4>
							<div class="row no-gutters">
								<div class="col-md-5">
									<div style="font-size:12px;" class="font-weight-semibold">JAGAT BUILDING</div>
									<div style="font-size:12px;" class="font-weight-semibold">St. Tomang Raya No 28 - 30</div>
									<div style="font-size:12px;" class="font-weight-semibold">Jakarta 11430</div>
									<div style="font-size:12px;" class="font-weight-semibold">Phone : 0811257180 / 081225575295</div>
									<div style="font-size:12px;" class="font-weight-semibold">Email : infojkt@smartmarbleandbath.com</div>
								</div>
								<div class="col-md-2">
									<center>
										<div class="bg-secondary" style="width:0px; height:88px; border:1px #777777 solid;"></div>
									</center>
								</div>
								<div class="col-md-5">
									<div style="font-size:12px;" class="font-weight-semibold">MODERN CERAMIC</div>
									<div style="font-size:12px;" class="font-weight-semibold">St. Baliwerti 119 - 121</div>
									<div style="font-size:12px;" class="font-weight-semibold">Surabaya, Jawa Timur 60174</div>
									<div style="font-size:12px;" class="font-weight-semibold">Phone : 031-5472860 / 031-5324505</div>
									<div style="font-size:12px;" class="font-weight-semibold">Email : info@smartmarbleandbath.com</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group mb-0"><hr class="border-2 border-secondary mb-0"></div>
					<div class="row justify-content-center">
						<div class="col-md-12 mt-3 mb-5">
							<img src="{{ url('website/kop_brand_report.png') }}" width="100%">
						</div>
					</div>
					<div class="form-group">
						<h1 class="font-weight-bold text-center">INVOICE</h1>
					</div>
					<div class="form-group mt-5 font-weight-semibold">
						<table width="100%">
							<tr>
								<td width="7%">Date</td>
								<td>: {{ date('d F Y', strtotime($order->created_at)) }}</td>
							</tr>
							<tr>
								<td width="7%">Invoice</td>
								<td>: {{ $order->invoice }}</td>
							</tr>
							<tr>
								<td width="7%">Ship Via</td>
								<td>: Land Transport</td>
							</tr>
						</table>
					</div>
					<div class="row">
						<div class="col-md-6">
							<h4 class="bg-primary p-1">Customer</h4>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $order->customer->name }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $order->customer->phone }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $order->customer->email }}</div>
						</div>
						<div class="col-md-6">
							<h4 class="bg-primary p-1">Ship To</h4>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $order->orderShipping->receiver_name }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $order->orderShipping->phone }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $order->orderShipping->email }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $order->orderShipping->city->name }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $order->orderShipping->city->address }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">
								Fleet : {{ $order->orderShipping->delivery->transport->fleet }}
							</div>
						</div>
					</div>
					<div class="table-responsive mt-4">
						<table class="table table-bordered table-sm table-striped" style="background-color: white !important;">
							<thead class="bg-primary text-white">
								<tr class="text-center">
									<th>No</th>
									<th>Picture</th>
									<th>Item Name</th>
									<th>Qty</th>
									<th>Unit Price</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								@foreach($order->orderDetail as $key => $od)
									<tr class="text-center">
										<td class="align-middle">{{ $key + 1 }}</td>
										<td class="align-middle">
											<img src="{{ $od->product->type->image() }}" style="max-width:80px;" class="img-fluid img-thumbnail">
										</td>
										<td class="align-middle">
											<b>{{ $od->product->name() }}</b>
											<div class="text-muted">
												{{ $od->product->type->length }}x{{ $od->product->type->width }}
											</div>
											<div class="text-muted">
												{{ $od->product->type->category->name }}
											</div>
										</td>
										<td class="align-middle">{{ $od->qty }}</td>
										<td class="align-middle">Rp {{ number_format($od->price_list, 2, ',', '.') }}</td>
										<td class="align-middle">Rp {{ number_format($od->total, 2, ',', '.') }}</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4" class="text-right">Subtotal</td>
									<td colspan="2">Rp {{ number_format($order->subtotal, 2, ',', '.') }}</td>
								</tr>
								<tr>
									<td colspan="4" class="text-right">Shipping</td>
									<td colspan="2">Rp {{ number_format($order->shipping, 2, ',', '.') }}</td>
								</tr>
								<tr>
									<td colspan="4" class="text-right">Discount</td>
									<td colspan="2">Rp {{ number_format($order->discount, 2, ',', '.') }}</td>
								</tr>
								<tr>
									<td colspan="4" class="text-right">Total</td>
									<td colspan="2" class="text-danger font-weight-bold">Rp {{ number_format($order->grandtotal, 2, ',', '.') }}</td>
								</tr>
								<tr>
									<td colspan="4" class="text-right">Status</td>
									<td colspan="2">
										@if($order->payment == 0 || $order->payment == null)
											Unpaid
										@elseif($order->payment >= $order->grandtotal)
											Full Payment
										@else
											Down Payment
										@endif
									</td>
								</tr>
								<tr>
									<td colspan="4" class="text-right">Payment</td>
									<td colspan="2" class="text-danger">
										@if($order->payment < $order->grandtotal)
											<input type="number" name="payment" id="payment" class="form-control form-control-sm" value="{{ $order->payment }}" placeholder="0">
										@else
											<span class="text-success font-weight-bold">Rp {{ number_format($order->payment, 2, ',', '.') }}</span>
										@endif
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
					@if($order->payment < $order->grandtotal)
						<div class="form-group"><hr></div>
						<div class="form-group text-right">
							<button type="submit" id="btn_submit" class="btn bg-success">Submit</button>
						</div>
					@endif
				</div>
			</div>
		</form>
	</div>

<script>
	$(function() {
		$('#btn_submit').click(function() {
			$('#btn_submit').attr('disabled', true);
			$('#btn_submit').html('Processing ...');
			$('#form_data').submit();
		});
	});
</script>