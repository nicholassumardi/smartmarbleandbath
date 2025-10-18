<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Surat Pembelian Barang {{ $data->code }}</title>
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
					<td style="text-align:center;" colspan="2">
						<img src="{{ url('website/logo_al_rev_3.jpg') }}" width="auto" height="100px">
					</td>
				</tr>
				<tr>
					<td width="50%">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="30%">Nomor</td>
								<td style="text-align:left !important;">: <u>{{ $data->code }}</u></td>
							</tr>
							<tr>
								<td width="30%">Lampiran</td>
								<td style="text-align:left !important;">: 1 (Satu) berkas</td>
							</tr>
							<tr>
								<td width="30%">Perihal</td>
								<td style="text-align:left !important;">: Pembelian Barang</td>
							</tr>
						</table>
						<br><br>
						Kepada
						<br>
						Yth. <u>Bpk/Ibu {{ $data->alSupplier->pic }}</u>
						<br>
						<u>{{ $data->alSupplier->name }}</u>
						<br>
						Di <u>{{ ucwords(strtolower($data->alSupplier->address)) }}</u>
					</td>
					<td width="50%">
						Surabaya, <u>{{ App\Helper\SMB::tgl_indo($data->date) }}</u>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: justify;">
						<p>Dengan Hormat,</p>
						<p>
							Berikut kami lampirkan surat pembelian dengan rincian sebagai berikut:
						</p>
						<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
							<thead>
								<tr style="background:#cf9604;text-align:center;">
									<th style="color:white;"><center>No</center></th>
									<th style="color:white;"><center>Produk</center></th>
									<th style="color:white;"><center>Spesifikasi</center></th>
									<th style="color:white;"><center>Qty</center></th>
									<th style="color:white;"><center>Unit</center></th>
									<th style="color:white;"><center>Harga/Unit</center></th>
									<th style="color:white;"><center>Total</center></th>
								</tr>
							</thead>
							<tbody>
								@php
									$no = 1;
								@endphp
								@foreach($data->alPurchaseProduct()->get()->sortBy('alProduct.name') as $key => $ps)
									<tr>
										<td style="vertical-align:center;">
											<center>
											{{ $no }}
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
											Rp {{ number_format($ps->buy_price,2,',','.') }}
										</td>
										<td style="vertical-align:center;" align="right">
											Rp {{ number_format($ps->total,2,',','.') }}	
										</td>
									</tr>
									@php
										$no++;
									@endphp
								@endforeach
							</tbody>
							<tfoot style="font-size:15px;font-weight:700;">
								<tr>
									<td style="vertical-align:center;" colspan="6" align="right">
										Total
									</td>
									<td style="vertical-align:center;" align="right">
										Rp {{ number_format($data->total,2,",",".") }}
									</td>
								</tr>
								<tr>
									<td style="vertical-align:center;" colspan="6" align="right">
										PPN
									</td>
									<td style="vertical-align:center;" align="right">
										Rp {{ number_format($data->ppn,2,",",".") }}
									</td>
								</tr>
								<tr>
									<td style="vertical-align:center;" colspan="6" align="right">
										Grandtotal
									</td>
									<td style="vertical-align:center;" align="right">
										Rp {{ number_format($data->grandtotal,2,",",".") }}
									</td>
								</tr>
							</tfoot>
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
					</td>
				</tr>
				<tr>
					<td style="text-align: justify;">
						&nbsp;
					</td>
					<td colspan="2" style="text-align: center;">
						Surabaya, <u>{{ App\Helper\SMB::tgl_indo($data->date) }}</u>
						<br>
						<b>PT. PERWIRA TAMARAYA ABADI</b>
						<br><br><br><br><br>
						<b><u>Prawiro Tedjo Tjandra</u></b>
						<br>Direktur
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>