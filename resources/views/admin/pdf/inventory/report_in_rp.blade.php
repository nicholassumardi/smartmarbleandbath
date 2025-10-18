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
				font-size:12px;
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
				@page {size: A4 portrait; }
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
											Inventory Product Report in Qty 
											<br>Branch {{ $branch == '1' ? 'Surabaya' : 'Jakarta' }}
										</b>
									</h4>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			@php
				$no = 1;
			@endphp
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center" style="background-color:#324148;color:white;font-size:25px !important;">
						<th>NO</th>
						<th>PRODUCT</th>
						<th>IN RUPIAH</th>
						<th>QTY</th>
					</tr>
				</thead>
				<tbody>
					@php
						$total = 0;
						$totalqty = 0;
					@endphp
					@foreach($main as $rowwarehouse)
						<tr>
							<td align="center" colspan="4" style="background-color:#b80d76;color:white;">{{ $rowwarehouse['warehouse_name'] }}</td>
						</tr>
						@php
							$totalrow = 0;
							$totalrowqty = 0;
						@endphp
						@foreach($data as $row)
							@if($row['warehouse_id'] == $rowwarehouse['warehouse_id'])
								<tr>
									<td align="center">{{ $no }}</td>
									<td>{{ $row['product_name'] }}</td>
									<td align="right">{{ number_format($row['price'] * $row['qty'],2,',','.') }}</td>
									<td align="center">{{ round($row['qty']) }}</td>
								</tr>
								@php
									$no++;
									$totalrow += $row['price'] * $row['qty'];
									$total += $row['price'] * $row['qty'];
									$totalqty += $row['qty'];
									$totalrowqty += $row['qty'];
								@endphp
							@endif
						@endforeach
						<tr style="background-color:#5dacba;color:white;">
							<td align="right" colspan="2">Total Warehouse - {{ $rowwarehouse['warehouse_name'] }}</td>
							<td align="right">{{ number_format($totalrow,2,',','.') }}</td>
							<td align="center">{{ $totalrowqty }}</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td align="right" colspan="2">GRANDTOTAL</td>
						<td align="right">{{ number_format($total,2,',','.') }}</td>
						<td align="center">{{ $totalqty }}</td>
					</tr>
				</tfoot>
			</table><br>
		</div>
	</body>
</html>