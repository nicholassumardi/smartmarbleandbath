<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Inventory Report Branch {{ $branch == '1' ? 'Surabaya' : 'Jakarta' }}</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
	<style>
		body {
			font-family: 'Lato', sans-serif;
		}

		th {
			font-size: 12px;
		}

		.invoice-box {
			font-size: 16px;
			font-family: 'Lato', sans-serif;
			color: #555;
			page-break-after: always;
		}

		.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
		}

		.invoice-box table td {
			vertical-align: top;
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

		@media print {
			@page {
				size: A4 portrait;
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

		@page {
			margin: 0.5cm;
		}

		body {
			margin: 0.5cm;
		}
	</style>
</head>

<body onload="window.print()">
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td style="text-align:center;">
								<img src="{{ url('website/logo_al_rev_3.jpg') }}" width="auto" height="100px">
							</td>
						</tr>
						<tr>
							<td style="text-align:center;">
								<h4>
									<b>
										Inventory Product Report
										<br>Branch {{ $branch == '1' ? 'Surabaya' : 'Jakarta' }}
										<br>Period {{ date('d M Y', strtotime($startDate)).' - '.date('d M Y',
										strtotime($endDate)) }}
									</b>
								</h4>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table><br>
		@foreach($arrResult as $rowwarehouse)
		@php
		$no = 1;
		$prevSize = null;
		$grandtotalfinal = 0;
		@endphp
		<table>
			<tr>
				<td align="center" colspan="7" style="background-color:#b80d76;color:white;"><b
						style="font-size: 18pt;">{{ $rowwarehouse['warehouse_name'] }}</b></td>
			</tr>
		</table>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center" style="background-color:#324148;color:white;font-size:25px !important;">
					<th>NO</th>
					<th>IMAGE</th>
					<th>PRODUCT</th>
					<th>SIZE</th>
					<th>Thickness</th>
					<th>QTY (sqm)</th>
					<th>QTY (BOX)</th>
					<th>COGS (HPP) PRICE/m<sup>2</sup></th>
					<th>COGS (HPP) Price</th>
					<th>Grandtotal</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rowwarehouse['detail'] as $row)
				@if ($prevSize != $row['size'])
				<tr>
					<td align="center" colspan="9" style="background-color:#b80d76;color:white;"><b
							style="font-size: 14pt;">{{ $row['size'] }}</b></td>
				</tr>
				@php
				$prevSize = $row['size'];
				@endphp
				@endif

				<tr>
					<td align="center">{{ $no }}</td>
					<td align="center"><img src="{{ $row['image'] }}" style="max-width:70px;"></td>
					<td><b style="font-size:13pt;">{{ $row['product_name'] }}</b></td>
					<td align="center">
						<b style="font-size:13pt;">
							{{ $row['size'] }}
							<br>
							({{ $row['m2'] }}) m<sup>2</sup>
						</b>
					</td>
					<td align="center"><b style=" font-size:13pt;">{{ $row['thickness'] }}  mm<sup>2</sup></b></td>
					<td align="center"><b style=" font-size:13pt;">{{ $row['qty_sqm'] }}  m<sup>2</sup></b></td>
					<td align="center">
						<b style="font-size:13pt;">
							{{ round($row['qty']).' Box'}}
							<br>
							{{$row['carton_pcs']}}
						</b>
					</td>
					<td class="text-right"><b style=" font-size:13pt;">Rp {{number_format($row['pricefinal_sqm'],2,',','.')}}</b></td>
					<td class="text-right"><b style=" font-size:13pt;">Rp {{number_format($row['pricefinal'],2,',','.')}}</b></td>
					<td class="text-right"><b style=" font-size:13pt;">Rp {{number_format(round($row['pricefinal'] * $row['qty']),0,',','.')}}</b></td>
				</tr>

				@php
				$no ++;
				$grandtotalfinal += $row['pricefinal'] * $row['qty'];
				@endphp
				@endforeach
				<tr>
				<tr class="text-center">
					<th colspan="7"><b style="font-size:13pt;">Total<b></th>
					<th>...</th>
					<th><b style="font-size:13pt;">Rp. {{number_format($grandtotalfinal,2,',','.')}}<b></th>
				</tr>
				</tr>
			</tbody>
		</table><br>
		@endforeach
	</div>
</body>

</html>