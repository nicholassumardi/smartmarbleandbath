<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Inventory Report Branch {{ $branch == '1' ? 'Surabaya' : 'Jakarta' }} Period {{ date('d M Y', strtotime($startDate)).' - '.date('d M Y', strtotime($endDate)) }}</title>
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
				@page {size: A4 landscape; }
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
											Inventory Product Report in Rupiahs 
											<br>Branch {{ $branch == '1' ? 'Surabaya' : 'Jakarta' }}
											<br>Period {{ date('d M Y', strtotime($startDate)).' - '.date('d M Y', strtotime($endDate)) }}
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
				$grandtotalbefore = 0;
				$grandtotalreceive = 0;
				$grandtotaltotal = 0;
				$grandtotalused = 0;
				$grandtotaladjust = 0;
				$grandtotalfinal = 0;
			@endphp
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead class="bg-dark sidebar-sticky">
					<tr class="text-center">
						<th rowspan="2" class="fixed" style="background-color:#324148;">Product</th>
						<th colspan="3">Previous</th>
						<th colspan="3">Receive</th>
						<th colspan="3">Total</th>
						<th colspan="3">Sold</th>
						<th colspan="3">Adjusment</th>
						<th colspan="3">Balance</th>
					</tr>
					<tr class="text-center">
						<th>Qty</th>
						<th>Price</th>
						<th>IDR</th>
						<th>Qty</th>
						<th>Price</th>
						<th>IDR</th>
						<th>Qty</th>
						<th>Price</th>
						<th>IDR</th>
						<th>Qty</th>
						<th>Price</th>
						<th>IDR</th>
						<th>Qty</th>
						<th>Price</th>
						<th>IDR</th>
						<th>Qty</th>
						<th>Price</th>
						<th>IDR</th>
					</tr>
				  </thead>
				<tbody>
					@foreach($array as $row)
							@php
								$totalqty = $row['qty_before'] + $row['qty'];
								$pricebefore = $row['qty_before'] ? $row['total_before'] / $row['qty_before'] : 0;
								$pricenow = $row['qty'] ? $row['total'] / $row['qty'] : 0;
								$pricetotal = $totalqty ? ($row['total'] + $row['total_before'])/$totalqty : 0;
								$priceused = $row['qty_used'] ? $row['total_used'] / $row['qty_used'] : 0;
								$priceadjust = $row['qty_adjust'] ? $row['total_adjust'] / $row['qty_adjust'] : 0;
								$totalqtyfinal = $totalqty - $row['qty_used'] - $row['qty_adjust'];
								$pricefinal = $totalqtyfinal ? ($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']) / $totalqtyfinal : 0;
								
								$finalqty = $totalqty - $row['qty_used'] - $row['qty_adjust'];
							@endphp
							@if ($mode == '1')
							<tr>
								<td>{{$row['product_name'].' - '.$row['product_id']}}</td>
								<td class="text-right">{{$row['qty_before']}}</td>
								<td class="text-right">Rp {{number_format($pricebefore,2,',','.')}}</td>
								<td class="text-right">Rp {{number_format(round($row['total_before']),0,',','.')}}</td>
								<td class="text-right">{{$row['qty']}}</td>
								<td class="text-right">Rp {{number_format($pricenow,2,',','.')}}</td>
								<td class="text-right">Rp {{number_format(round($row['total']),0,',','.')}}</td>
								<td class="text-right">{{$totalqty}}</td>
								<td class="text-right">Rp {{number_format($pricetotal,2,',','.')}}</td>
								<td class="text-right">Rp {{number_format(round($row['total'] + $row['total_before']),0,',','.')}}</td>
								<td class="text-right">{{($row['qty_used'])}}</td>
								<td class="text-right">Rp {{(number_format($priceused,2,',','.'))}}</td>
								<td class="text-right">Rp {{(number_format(round($row['total_used']),0,',','.'))}}</td>
								<td class="text-right">{{($row['qty_adjust'])}}</td>
								<td class="text-right">Rp {{(number_format($priceadjust,2,',','.'))}} </td>
								<td class="text-right">Rp {{(number_format(round($row['total_adjust']),0,',','.'))}}</td>
								<td class="text-right">{{($totalqty - $row['qty_used'] - $row['qty_adjust'])}}</td>
								<td class="text-right">Rp {{number_format($pricefinal,2,',','.')}}</td>
								<td class="text-right">Rp {{($finalqty == 0 ? 0 : number_format(round($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']),0,',','.'))}}</td>
							</tr>
							@elseif($mode == '2')
								@if ($finalqty > 0)
								<tr>
									<td>{{$row['product_name'].' - '.$row['product_id']}}</td>
									<td class="text-right">{{$row['qty_before']}}</td>
									<td class="text-right">Rp {{number_format($pricebefore,2,',','.')}}</td>
									<td class="text-right">Rp {{number_format(round($row['total_before']),0,',','.')}}</td>
									<td class="text-right">{{$row['qty']}}</td>
									<td class="text-right">Rp {{number_format($pricenow,2,',','.')}}</td>
									<td class="text-right">Rp {{number_format(round($row['total']),0,',','.')}}</td>
									<td class="text-right">{{$totalqty}}</td>
									<td class="text-right">Rp {{number_format($pricetotal,2,',','.')}}</td>
									<td class="text-right">Rp {{number_format(round($row['total'] + $row['total_before']),0,',','.')}}</td>
									<td class="text-right">{{($row['qty_used'])}}</td>
									<td class="text-right">Rp {{(number_format($priceused,2,',','.'))}}</td>
									<td class="text-right">Rp {{(number_format(round($row['total_used']),0,',','.'))}}</td>
									<td class="text-right">{{($row['qty_adjust'])}}</td>
									<td class="text-right">Rp {{(number_format($priceadjust,2,',','.'))}} </td>
									<td class="text-right">Rp {{(number_format(round($row['total_adjust']),0,',','.'))}}</td>
									<td class="text-right">{{($totalqty - $row['qty_used'] - $row['qty_adjust'])}}</td>
									<td class="text-right">Rp {{number_format($pricefinal,2,',','.')}}</td>
									<td class="text-right">Rp {{($finalqty == 0 ? 0 : number_format(round($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']),0,',','.'))}}</td>
								</tr>
								@endif
							@endif
							@php
								$grandtotalbefore += round($row['total_before']);
								$grandtotalreceive += round($row['total']);
								$grandtotaltotal += round($row['total'] + $row['total_before']);
								$grandtotalused += round($row['total_used']);
								$grandtotaladjust += round($row['total_adjust']);
								$grandtotalfinal += round($row['total'] + $row['total_before'] - $row['total_used'] - $row['total_adjust']);
							@endphp
					@endforeach
					<tr class="text-center">
						<th>Total</th>
						<th>...</th>
						<th>...</th>
						<th>Rp. {{number_format($grandtotalbefore,2,',','.')}}</th>
						<th>...</th>
						<th>...</th>
						<th>Rp. {{number_format($grandtotalreceive,2,',','.')}}</th>
						<th>...</th>
						<th>...</th>
						<th>Rp. {{number_format($grandtotaltotal,2,',','.')}}</th>
						<th>...</th>
						<th>...</th>
						<th>Rp. {{number_format($grandtotalused,2,',','.')}}</th>
						<th>...</th>
						<th>...</th>
						<th>Rp. {{number_format($grandtotaladjust,2,',','.')}}</th>
						<th>...</th>
						<th>...</th>
						<th>Rp. {{number_format($grandtotalfinal,2,',','.')}}</th>
					</tr>
				</tbody>
			</table><br>
		</div>
	</body>
</html>