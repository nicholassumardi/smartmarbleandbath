<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sales Order {{ $project->code }}</title>
		<style>
			.invoice-box {
				font-size: 16px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
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
				padding-bottom: 20px;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #1a73e8;
				border-bottom: 1px solid #1a73e8;
				color: white;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
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

			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
		</style>
	</head>
	<body>
		<div class="invoice-box">
			<h3 style="text-align:right;">PT. PERWIRA TAMARAYA ABADI</h3>
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title">
									<img src="{{ asset('website/logo-black.png') }}" width="150">
								</td>
								<td>
									<div style="font-size:9px; font-weight:bold;">JAGAT BUILDING</div>
									<div style="font-size:9px; font-weight:500;">St. Tomang Raya No 28 - 30</div>
									<div style="font-size:9px; font-weight:500;">Jakarta 11430</div>
									<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
									<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com</div>
								</td>
								<td style="text-align:right;">
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
					<td colspan="2">
						<table>
							<tr>
								@foreach($brand as $b)
									<td>
										<center>
											<img src="{{ asset(Storage::url($b->image)) }}">
										</center>
									</td>
								@endforeach
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr>
								<td style="text-align:center;">
									<h4><b>SALES ORDER</b></h4>
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
								<td style="text-align:left; font-size:10px;">: {{ date('d F Y', strtotime($project->created_at)) }}</td>
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
						<div style="font-weight:500; font-size:10px;">{{ $project->manager }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $project->phone }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $project->email }}</div>
					</td>
					<td style="text-align:left !important;">
						<table>
							<tr class="heading">
								<td><div style="font-size:10px;"><b>SHIP TO :</b></div></td>
							</tr>
						</table>
						<div style="font-weight:500; font-size:10px;">{{ $project->projectDelivery ? $project->projectDelivery->receiver_name : 'Not set' }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $project->projectDelivery ? $project->projectDelivery->phone : 'Not set' }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $project->projectDelivery ? $project->projectDelivery->email : 'Not set' }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $project->projectDelivery ? $project->projectDelivery->city->name : 'Not set' }}</div>
						<div style="font-weight:500; font-size:10px;">{{ $project->projectDelivery ? $project->projectDelivery->address : 'Not set' }}</div>
						<div style="font-weight:500; font-size:10px;">
							Fleet : {{ $project->projectDelivery ? $project->projectDelivery->delivery->transport->fleet : 'Not set' }}	
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
						<th style="color:white;"><center>Discount</center></th>
						<th style="color:white;"><center>Unit Price</center></th>
						<th style="color:white;"><center>Best Price</center></th>
						<th style="color:white;"><center>Total</center></th>
					</tr>
				</thead>
				<tbody>
					@php 
						$subtotal = 0;
						$discount = 0;
					@endphp
					@foreach($project->projectProduct as $key => $pp)
						@php
							$total     = $pp->best_price * $pp->qty;
							$subtotal += $pp->best_price * $pp->qty;
							$discount += ($pp->discount / 100) * $total;
						@endphp
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $key + 1 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $pp->product->type->image() }}" style="max-width:28px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->name() }}
									<div>{{ $pp->product->type->length }}x{{ $pp->product->type->width }}</div>
									<div>{{ $pp->product->type->category->name }}</div>
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->qty.' '.$pp->unit() }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->discount }}%
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;">
								<center>
									Rp {{ number_format($pp->recommended_price, 2, ',', '.') }}
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;">
								<center>
									Rp {{ number_format($pp->best_price, 2, ',', '.') }}
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;">
								<center>
									Rp {{ number_format($total, 2, ',', '.') }}
								</center>
							</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					@php $shipping_fee = $project->projectDelivery ? $project->projectDelivery->price : 0;  @endphp
					<tr>
						<th colspan="6" style="text-align:right;">SUBTOTAL</th>
						<th colspan="2" style="text-align:right;">IDR {{ number_format($subtotal, 2, ',', '.') }}</th>
					</tr>
					<tr>
						<th colspan="6" style="text-align:right;">SHIPPING</th>
						<th colspan="2" style="text-align:right;">IDR {{ number_format($shipping_fee, 2, ',', '.') }}</th>
					</tr>
					@if($project->ppn)
						@php $tax = (10 / 100) * $subtotal; @endphp
						<tr>
							<th colspan="6" style="text-align:right;">TAX (10%)</th>
							<th colspan="2" style="text-align:right;">IDR {{ number_format($tax, 2, ',', '.') }}</th>
						</tr>
					@else
						@php $tax = 0; @endphp
					@endif
					<tr>
						<th colspan="6" style="text-align:right;">TOTAL</th>
						<th colspan="2" style="text-align:right;">IDR {{ number_format($subtotal + ($shipping_fee + $tax), 2, ',', '.') }}</th>
					</tr>
				</tfoot>
			</table><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<h6 style="font-size:10px; text-align:center;">Comments or Special Instructions :</h6>
						<div></div>
						<div style="font-size:10px; text-align:left;">1. Down Payment is 50% from the total</div>
						<div style="font-size:10px; text-align:left;">2. The goods must be shipped to Jakarta before May 19th, 2021. If the goods are not in stock, please send the Concealed Body Art. HT5716ZZ11 first.</div>
					</td>
				</tr>
			</table><br><br><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;">
						<div style="font-size:10px;">Created By</div>
						<br><br><br>
						<div style="font-size:10px;">{{ $project->user->name }}</div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>