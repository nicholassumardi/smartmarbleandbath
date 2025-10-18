<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Delivery Order {{ $data->code }}</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:12px;
			}
		
			.invoice-box {
				font-size: 16px;
				font-family: 'Lato', sans-serif;
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
				background: #cf9604;
				border-bottom: 1px solid #cf9604;
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
				font-family: 'Lato', sans-serif;
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
								<td colspan="2" style="text-align:right;padding-bottom:15px;"><img src="{{ asset('website/pta_new_logo.png') }}" height="30px" style="margin-right:5px;"></td>
							</tr>
							<tr>
								{{-- <td style="padding-right:25px;padding-top:100px !important;">
									<div style="font-size:9px; font-weight:bold;">JAGAT BUILDING</div>
									<div style="font-size:9px; font-weight:500;">Tomang Raya No 28 - 30, Jakarta 11430</div>
									<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
									<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com</div>
								</td> --}}
								<td style="border-left: 3px solid #51b6bc; text-align:right;padding-top:100px !important;">
									<div style="font-size:9px; font-weight:bold;">MODERN CERAMIC</div>
									<div style="font-size:9px; font-weight:500;">Baliwerti 119 - 121, Surabaya 60174</div>
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
							<img src="{{ asset('website/kop_brand_report.png') }}" width="100%">
						</center>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr style="background-color:#cf9604;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>WAREHOUSE EXCHANGE</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table>
				<tr>
					<td width="50%">
						<table>
							<tr class="heading">
								<td colspan="3"><div style="font-size:12px;"><b>INFORMATION :</b></div></td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Date of WE</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ date('d F Y', strtotime($data->date)) }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">WE. Number</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $data->code }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Out From (Warehouse)</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $data->warehouseFrom ? $data->warehouseFrom->name.' - '.$data->warehouseFrom->code : '' }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">In To (Warehouse)</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $data->warehouseTo ? $data->warehouseTo->name.' - '.$data->warehouseTo->code : '' }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Note</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $data->note }}</td>
							</tr>
						</table>
					</td>
					<td width="50%">
						<table>
							@php
								if($data->for_customer == '1'){
							@endphp
							<tr class="heading">
								<td colspan="3"><div style="font-size:12px;"><b>RECEIVER :</b></div></td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Customer</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $data->customer ? $data->customer->name : '' }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Phone</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $data->customer ? $data->customer->phone : ''  }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Address</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $data->customer_address ? $data->customer_address : $data->customer->address : ''  }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">PIC</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $data->customer ? $data->customer_pic : '' }}</td>
							</tr>
							@php
								}
							@endphp
						</table>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#cf9604;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>NAME</center></th>
						<th style="color:white;"><center>QTY</center></th>
						<th style="color:white;"><center>SIZE</center></th>
						<th style="color:white;"><center>SHADE</center></th>
						<th style="color:white;"><center>UNIT</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($data->transferProduct as $key => $ps)
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $key + 1 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->name() }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->qty }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->type->length }}x{{ $ps->product->type->width }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->unit() }}
								</center>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<br><br><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Created by</div>
						@if($data->user->sign)
							<div><img src="{{ asset(Storage::url($data->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">( {{ isset($data->user->name) ? $data->user->name : '' }} )</div>
						<div style="font-size:10px;font-weight:700;">{{ isset($data->user->name) ? $data->user->userRole->first()->role() : '' }}</div>
					</td>
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">QC</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
					</td>
					@if(isset($data->approved->name))
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Mengetahui,</div>
						@if(isset($data->approved->sign))
							<div><img src="{{ url(Storage::url($data->approved->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $data->approved->name }}</div>
						<div style="font-size:10px;">{{ $data->approved->userRole->first()->role() }}</div>
					</td>
					@endif
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Sent by</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
					</td>
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Warehouse</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
					</td>
				</tr>
				<tr>
					<td colspan="7" style="text-align:center;padding-top:50px !important;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7" style="text-align:center;padding-top:50px !important;font-size:10px;">Printed on : {{ date('Y-m-d H:i:s') }}</td>
				</tr>
			</table>
		</div>
	</body>
</html>