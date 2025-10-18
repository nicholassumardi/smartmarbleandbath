<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Surat Penawaran Harga {{ $data->code }}</title>
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
	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
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
									<h4><b>LAPORAN SPH (INTERNAL)</b></h4>
									<h4><b>{{ $data->alProject->name }}</b></h4>
									<h4><b>Nomor {{ $data->code }}</b></h4>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr style="background:#cf9604;text-align:center;">
						<th style="color:white;"><center>No</center></th>
						<th style="color:white;"><center>Produk</center></th>
						<th style="color:white;"><center>Spesifikasi</center></th>
						<th style="color:white;"><center>Qty</center></th>
						<th style="color:white;"><center>Unit</center></th>
						<th style="color:white;"><center>HPP PTA</center></th>
						<th style="color:white;"><center>Total HPP PTA</center></th>
						<th style="color:white;"><center>HPP</center></th>
						<th style="color:white;"><center>Total HPP</center></th>
						<th style="color:white;"><center>DK {{ $data->dk }}%</center></th>
						<th style="color:white;"><center>Admin {{ $data->admin }}%</center></th>
						<th style="color:white;"><center>Profit (Rp)</center></th>
						<th style="color:white;"><center>Profit (%)</center></th>
						<th style="color:white;"><center>Supplier</center></th>
					</tr>
				</thead>
				<tbody>
					@php
						$noparent = 'A';
						$nograndparent = '1';
						$totalhpp = 0;
						$totaldk = 0;
						$totaladmin = 0;
						$totalprofit = 0;
					@endphp
					@foreach($parent as $rowparent)
						@foreach($data->alSphProduct()->whereHas('alProduct', function($query) use ($rowparent){ $query->where('al_product_parent_id',$rowparent['id'])->groupBy('al_category_id'); })->orderBy('id')->get() as $key => $ps)
							@if($key == 0)
								<tr>
									<td style="vertical-align:center;" colspan="14">
										<b>{{ $nograndparent.'. '.$ps->alProduct->alCategory->name}}</b>
									</td>
								</tr>
							@endif
							@if($key == 0)
								<tr>
									<td style="vertical-align:center;" colspan="14">
										<b>{{ $rowparent['name'] ? $noparent.'. '.$rowparent['name'] : $noparent.'. '.'Lain-lain' }}</b>
									</td>
								</tr>
							@endif
							@php
								$hpp = $ps->buy_price * $ps->qty;
								$dk = $ps->total * $data->dk / 100;
								$admin = $ps->total * $data->admin / 100;
								$profit = $ps->total - $hpp - $dk - $admin;
								$totalhpp += $hpp;
								$totaldk += $dk;
								$totaladmin += $admin;
								$totalprofit += $profit;
							@endphp
							<tr>
								<td style="vertical-align:center;">
									<center>
									{{ $key + 1 }}
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
									{{ $ps->alProduct->name }}
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
									{!! $ps->alProduct->description !!}
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
									{{ $ps->qty }}
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
									{{ $ps->alProduct->unit() }}
									</center>
								</td>
								<td style="vertical-align:center;" align="right">
									{{ number_format($ps->sell_price,0,',','.') }}
								</td>
								<td style="vertical-align:center;" align="right">
									{{ number_format($ps->total,0,',','.') }}	
								</td>
								<td style="vertical-align:center;" align="right">
									{{ number_format($ps->buy_price,0,',','.') }}
								</td>
								<td style="vertical-align:center;" align="right">
									{{ number_format($hpp,0,',','.') }}
								</td>
								<td style="vertical-align:center;" align="right">
									{{ number_format($dk,0,',','.') }}
								</td>
								<td style="vertical-align:center;" align="right">
									{{ number_format($admin,0,',','.') }}
								</td>
								<td style="vertical-align:center;" align="right">
									{{ number_format($profit,0,',','.') }}
								</td>
								<td style="vertical-align:center;" align="right">
									{{ $ps->total > 0 ? number_format($profit / $ps->total * 100,2,',','.') : 0 }}%
								</td>
								<td style="vertical-align:center;">
									<center>
									{{ $ps->alProduct->alSupplier->name }}
									</center>
								</td>
							</tr>
						@endforeach
						@php
							$noparent++;
							$nograndparent++;
						@endphp
					@endforeach
					<tr style="font-size:15px;font-weight:700;">
						<td style="vertical-align:center;" colspan="6" align="right">
							Total
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($data->total,0,",",".") }}
						</td>
						<td style="vertical-align:center;" align="right">
							
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($totalhpp,0,",",".") }}
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($totaldk,0,",",".") }}
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($totaladmin,0,",",".") }}
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($totalprofit,0,",",".") }}
						</td>
						<td style="vertical-align:center;" colspan="2">
							
						</td>
					</tr>
					<tr style="font-size:15px;font-weight:700;">
						<td style="vertical-align:center;" colspan="6" align="right">
							PPN
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($data->ppn,0,",",".") }}
						</td>
						<td style="vertical-align:center;" align="right">
							Prosentase
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($totalhpp / $data->total * 100,2,",",".") }}%
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($totaldk / $data->total * 100,2,",",".") }}%
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($totaladmin / $data->total * 100,2,",",".") }}%
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($totalprofit / $data->total * 100,2,",",".") }}%
						</td>
						<td style="vertical-align:center;" colspan="2">
							
						</td>
					</tr>
					<tr style="font-size:15px;font-weight:700;">
						<td style="vertical-align:center;" colspan="6" align="right">
							PPH
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($data->pph,0,",",".") }}
						</td>
						<td style="vertical-align:center;" colspan="7">
							
						</td>
					</tr>
					<tr style="font-size:15px;font-weight:700;">
						<td style="vertical-align:center;" colspan="6" align="right">
							Grandtotal
						</td>
						<td style="vertical-align:center;" align="right">
							{{ number_format($data->grandtotal,0,",",".") }}
						</td>
						<td style="vertical-align:center;" colspan="7">
							
						</td>
					</tr>
				</tbody>
			</table><br><br>
			<table cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<tr>
					<td>
						Catatan : 
						<br>
						{!! $data->note !!}
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>