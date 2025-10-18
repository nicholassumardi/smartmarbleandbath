<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Delivery Order {{ $delivery_order->delivery_order }}</title>
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:14px;
			}
			
			.invoice-box {
				font-size: 16px;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 0px;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 0px;
			}

			.invoice-box table tr.heading td {
				background: #51b6bc;
				border-bottom: 1px solid #51b6bc;
				color: white;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 0px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 1px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
			
			@page { margin: 1cm; }
			body { margin: 1cm; }
		</style>
	</head>
	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title" rowspan="2">
									<img src="{{ asset('website/logo-black.png') }}" width="275">
								</td>
								<td colspan="2" style="text-align:right;"><h3><img src="website/pta_small.png" height="13px" style="margin-right:5px;">PT. PERWIRA TAMARAYA ABADI</h3></td>
							</tr>
							<tr>
								<td style="padding-right:25px;">
									<div style="font-size:9px; font-weight:bold;">JAGAT BUILDING</div>
									<div style="font-size:9px; font-weight:500;">St. Tomang Raya No 28 - 30</div>
									<div style="font-size:9px; font-weight:500;">Jakarta 11430</div>
									<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
									<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com</div>
								</td>
								<td style="border-left: 3px solid #51b6bc; text-align:right;">
									<div style="font-size:9px; font-weight:bold;">MODERN CERAMIC</div>
									<div style="font-size:9px; font-weight:500;">St. Baliwerti 119 - 121</div>
									<div style="font-size:9px; font-weight:500;">Surabaya, Jawa Timur 60174</div>
									<div style="font-size:9px; font-weight:500;">Phone : 031-5472860 / 031-5324505</div>
									<div style="font-size:9px; font-weight:500;">Email : info@smartmarbleandbath.com</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2" style="vertical-align: middle;padding-top:15px;padding-bottom:15px;">
						<center>
							<img src="website/kop_brand_report.png" width="100%">
						</center>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr>
								<td style="text-align:center;padding-top:10px;padding-bottom:10px;">
									<h3><b>DELIVERY ORDER</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table>
				<tr>
					<td colspan="2">
						<table>
							<tr style="line-height:0 !important;">
								<td width="10%" style="font-size:10px;">Date</td>
								<td style="text-align:left; font-size:10px;">: {{ date('d F Y', strtotime($delivery_order->created_at)) }}</td>
							</tr>
							<tr>
								<td width="10%" style="font-size:10px;">Invoice</td>
								<td style="text-align:left; font-size:10px;">: {{ $delivery_order->order->invoice }}</td>
							</tr>
							<tr>
								<td width="10%" style="font-size:10px;">DO</td>
								<td style="text-align:left; font-size:10px;">: {{ $delivery_order->delivery_order }}</td>
							</tr>
							<tr>
								<td width="10%" style="font-size:10px;">Ship Via</td>
								<td style="text-align:left; font-size:10px;">: Land Transport</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"></td></tr>
				<tr><td colspan="2"></td></tr>
				<tr>
					<td style="text-align:left !important;">
						<table>
							<tr class="heading">
								<td><div style="font-size:10px;"><b>CUSTOMER :</b></div></td>
							</tr>
						</table>
						<div style="font-weight:500; font-size:10px;">{{ $delivery_order->order->customer->name }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $delivery_order->order->customer->phone }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $delivery_order->order->customer->email }}</div>
					</td>
					<td style="text-align:left !important;">
						<table>
							<tr class="heading">
								<td><div style="font-size:10px;"><b>SHIP TO :</b></div></td>
							</tr>
						</table>
						<div style="font-weight:500; font-size:10px;">{{ $delivery_order->order->orderShipping->receiver_name }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $delivery_order->order->orderShipping->phone }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $delivery_order->order->orderShipping->email }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $delivery_order->order->orderShipping->city->name }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $delivery_order->order->orderShipping->address }}</div>
						<div style="font-weight:500; font-size:10px;">
							Fleet : {{ $delivery_order->order->orderShipping->delivery->transport->fleet }}	
						</div>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#1a73e8;">
						<th style="color:white;"><center>No</center></th>
						<th style="color:white;"><center>Picture</center></th>
						<th style="color:white;"><center>Item Name</center></th>
						<th style="color:white;"><center>Qty</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($delivery_order->order->orderDetail as $key => $od)
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $key + 1 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $od->product->type->image() }}" style="max-width:28px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $od->product->name() }}
									<div>{{ $od->product->type->length }}x{{ $od->product->type->width }}</div>
									<div>{{ $od->product->type->category->name }}</div>
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $od->qty }}
								</center>
							</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3" style="text-align:right;">TOTAL QTY</th>
						<th style="text-align:left;">{{ number_format($delivery_order->order->orderDetail->sum('qty'), 2, ',', '.') }}</th>
					</tr>
				</tfoot>
			</table><br><br><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;">
						<div style="font-size:10px;">Knowing</div>
						<br><br><br>
						<div style="font-size:10px;">(....................................)</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Warehouse</div>
						<br><br><br>
						<div style="font-size:10px;">(....................................)</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Created By</div>
						<br><br><br>
						<div style="font-size:10px;">({{ session('bo_name') }})</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Delivery</div>
						<br><br><br>
						<div style="font-size:10px;">(....................................)</div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>