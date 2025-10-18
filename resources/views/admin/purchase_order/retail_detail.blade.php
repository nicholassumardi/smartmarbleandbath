<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Detail PO Retail</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/purchase_order/retail') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
					<a href="{{ url('admin/purchase_order/retail/print/' . $purchase_order->id) }}" class="btn bg-success ml-2 btn-labeled btn-labeled-left">
						<b><i class="icon-printer2"></i></b> Print
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Purchase Order</a>
					<a href="{{ url('admin/purchase_order/retail') }}" class="breadcrumb-item">Retail</a>
					<span class="breadcrumb-item active">Detail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		@if(session('success'))
			<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
				<span class="font-weight-semibold">Success!</span> 
				{{ session('success') }}
			</div>
		@endif
		<div class="card">
			<form action="" method="POST">
				@csrf
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
						<h1 class="font-weight-bold text-center">PURCHASE ORDER</h1>
					</div>
					<div class="form-group mt-5 font-weight-semibold">
						<table width="100%">
							<tr>
								<td width="7%">Date</td>
								<td>: {{ date('d F Y', strtotime($purchase_order->created_at)) }}</td>
							</tr>
							<tr>
								<td width="7%">Invoice</td>
								<td>: {{ $purchase_order->order->invoice }}</td>
							</tr>
							<tr>
								<td width="7%">PO</td>
								<td>: {{ $purchase_order->purchase_order }}</td>
							</tr>
							<tr>
								<td width="7%">Ship Via</td>
								<td>: Land Transport</td>
							</tr>
						</table>
					</div>
					<div class="row">
						<div class="col-md-6">
							<h4 class="bg-primary p-1">Vendor</h4>
							<div style="font-size:12px;" class="font-weight-semibold">Karya Modern</div>
							<div style="font-size:12px;" class="font-weight-semibold">031-5472860</div>
							<div style="font-size:12px;" class="font-weight-semibold">info@karyamodern.com</div>
							<div style="font-size:12px;" class="font-weight-semibold">Baliwerti 119 - 121, Surabaya Jawa Timur</div>
						</div>
						<div class="col-md-6">
							<h4 class="bg-primary p-1">Ship To</h4>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $purchase_order->order->orderShipping->receiver_name }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $purchase_order->order->orderShipping->phone }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $purchase_order->order->orderShipping->email }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $purchase_order->order->orderShipping->city->name }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">{{ $purchase_order->order->orderShipping->city->address }}</div>
							<div style="font-size:12px;" class="font-weight-semibold">
								Fleet : {{ $purchase_order->order->orderShipping->delivery->transport->fleet }}
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
									@if($purchase_order->status == 2)
										<th>Total</th>
									@endif
								</tr>
							</thead>
							<tbody>
								@foreach($purchase_order->order->orderDetail as $key => $od)
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
										<td class="align-middle" width="{{ $purchase_order->status == 1 ? '20%' : '' }}">
											@if($purchase_order->status == 2)
												Rp {{ number_format($od->bottom_price, 2, ',', '.') }}
											@else
												<input type="hidden" name="order_detail_id[]" value="{{ $od->id }}">
												<input type="text" name="order_detail_bottom_price[]" class="form-control" value="{{ $od->bottom_price }}" placeholder="0" required>
											@endif
										</td>
										@if($purchase_order->status == 2)
											<td class="align-middle">Rp {{ number_format($od->bottom_price * $od->qty, 2, ',', '.') }}</td>
										@endif
									</tr>
								@endforeach
							</tbody>
							@if($purchase_order->status == 2)
								<tfoot>
									<tr>
										<td colspan="4" class="text-right">Subtotal</td>
										<td colspan="2">Rp {{ number_format($purchase_order->order->orderDetail->sum('bottom_price') * $purchase_order->order->orderDetail->sum('qty'), 2, ',', '.') }}</td>
									</tr>
									<tr>
										<td colspan="4" class="text-right">Shipping</td>
										<td colspan="2">
											Rp {{ number_format($shipping, 2, ',', '.') }}
										</td>
									</tr>
									<tr>
										<td colspan="4" class="text-right">Discount</td>
										<td colspan="2">
											Rp {{ number_format($discount, 2, ',', '.') }}
										</td>
									</tr>
									<tr>
										<td colspan="4" class="text-right">Total</td>
										<td colspan="2" class="text-danger font-weight-bold">
											Rp {{ number_format(($purchase_order->order->orderDetail->sum('bottom_price') * $purchase_order->order->orderDetail->sum('qty')) + $shipping, 2, ',', '.') }}
										</td>
									</tr>
								</tfoot>
							@endif
						</table>
					</div>
					@if($purchase_order->status == 1)
						<div class="form-group"><hr></div>
						<div class="text-right">
							<div class="form-group">
								<label class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="status" value="2">
									<span class="custom-control-label">Done</span>
								</label>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success">Submit</button>
							</div>
						</div>
					@endif
				</div>
			</form>
		</div>
	</div>