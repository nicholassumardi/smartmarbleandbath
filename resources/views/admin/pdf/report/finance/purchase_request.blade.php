<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>{{ $title }}</title>
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:14px;
			}
		
			.invoice-box {
				font-size: 14px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
				/* page-break-after: always; */
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
				background: #ebb220;
				border-bottom: 1px solid #ebb220;
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
			
			.separate-box {
				height: 100%;
			}
			
			.separate-box table tr td {
				padding: 5px;
			}
			
			.separate-box #table-kwitansi {
				border: 1px solid black;
			}
			
			@page { margin: 0.5cm; }
			body { margin: 0.5cm; }
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
									<img src="{{ public_path('website/logo-black.png') }}" width="275">
								</td>
								<td colspan="2" style="text-align:right;padding-bottom:15px;"><img src="{{ public_path('website/pta_new_logo.png') }}" height="30px" style="margin-right:5px;"></td>
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
							<img src="website/kop_brand_report.png" width="100%">
						</center>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr style="background-color:#ebb220;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>PURCHASE REQUEST REPORT</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>USER</center></th>
						<th style="color:white;"><center>TO</center></th>
						<th style="color:white;"><center>DATE</center></th>
						<th style="color:white;"><center>DUE</center></th>
						<th style="color:white;"><center>TITLE</center></th>
						<th style="color:white;"><center>NOTE</center></th>
						<th style="color:white;"><center>NOMINAL</center></th>
					</tr>
				</thead>
				<tbody>
					@php
						$grandtotal = 0;
					@endphp
					@foreach($data as $key => $row)
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $key + 1 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->user->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->bill_to }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ date('d M Y', strtotime($row->date)) }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->due_date ? date('d M Y', strtotime($row->due_date)) : '-' }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->title }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->item }}
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;">
								Rp {{ number_format($row->total_nominal,2,',','.') }}
							</td>
						</tr>
						@php
							$grandtotal += $row->total_nominal;
						@endphp
					@endforeach
					<tr>
						<th colspan="7">GRANDTOTAL</th>
						<th>Rp{{ number_format($grandtotal,2,',','.') }}</th>
					</tr>
				</tbody>
			</table><br>
			<table cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<tbody>
					<tr>
						<td colspan="3" align="left">
							Surabaya, {{ date("j F Y") }}
						</td>
					</tr>
					<tr>
						<td width="33%" align="center">
							Created by,<br><br><br><br><br>
							(.....................................................)
						</td>
						<td width="33%" align="center">
							Approved by,<br><br><br><br><br>
							(.....................................................)
						</td>
						<td width="33%" align="center">
							Acknowledged by,<br><br><br><br><br>
							(.....................................................)
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>