<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Faktur Barang {{ $data->code }}</title>
		<style>
			body {
				font-family: "Times New Roman", Times, serif;
			}
			
			th {
				font-size:20px;
			}
		
			.invoice-box {
				font-size: 20px;
				font-family: "Times New Roman", Times, serif;
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
				font-family: "Times New Roman", Times, serif;
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
					<td width="70%">
						<b>FAKTUR BARANG</b>
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="10%">No</td>
								<td style="text-align:left !important;">: {{ $data->code }}</td>
							</tr>
						</table>
					</td>
					<td width="30%">
						Surabaya, <u>{{ App\Helper\SMB::tgl_indo($data->date) }}</u>
						<br><br>
						Kepada
						<br>
						Yth. Wadan {{ $data->alProject->alCustomer->name }}
						<br>
						Selaku Pejabat Pembuat Komitmen
						<br>
						Di {{ ucwords(strtolower($data->alProject->city->name)) }}
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: justify;">
						Berdasarkan Kontrak Pengadaaan Barang
						<br>Nomor Kontrak : {{ $data->alSph->contract_no ? $data->alSph->contract_no : 'Kosong' }} tanggal {{ $data->alSph->contract_date ? App\Helper\SMB::tgl_indo($data->alSph->contract_date) : 'Kosong' }}
						<table border="1" cellpadding="3" cellspacing="0" style="width:100%;">
							<thead>
								<tr style="background:#cf9604;text-align:center;">
									<th style="color:white;"><center>NO</center></th>
									<th style="color:white;"><center>URAIAN KEGIATAN</center></th>
									<th style="color:white;"><center>VOL</center></th>
									<th style="color:white;"><center>SAT</center></th>
									<th style="color:white;"><center>HARGA SATUAN (RP)</center></th>
									<th style="color:white;"><center>PRESTASI</center></th>
									<th style="color:white;"><center>JUMLAH (RP)</center></th>
								</tr>
							</thead>
							<tbody>
								@foreach($data->alSph->alSphProduct as $key => $row)
									<tr>
										<td style="vertical-align:center;">
											<center>
											{{ $key + 1 }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
											{{ $row->alProduct->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
											{{ $row->qty }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
											{{ $row->alProduct->unit() }}
											</center>
										</td>
										<td style="vertical-align:center;" align="right">
											Rp {{ number_format($row->sell_price,0,',','.') }}
										</td>
										<td style="vertical-align:center;" align="right">
											100%
										</td>
										<td style="vertical-align:center;" align="right">
											Rp {{ number_format($row->total,0,',','.') }}	
										</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot style="font-weight:700;">
								<tr>
									<td style="vertical-align:center;border-bottom: none;" colspan="6" align="right">
										Total
									</td>
									<td style="vertical-align:center;border-bottom: none;" align="right">
										Rp {{ number_format($data->alSph->total,0,",",".") }}
									</td>
								</tr>
								<tr>
									<td style="vertical-align:center;border-top: none;border-bottom: none;" colspan="6" align="right">
										PPN
									</td>
									<td style="vertical-align:center;border-top: none;border-bottom: none;" align="right">
										Rp {{ number_format($data->alSph->ppn,0,",",".") }}
									</td>
								</tr>
								<tr>
									<td style="vertical-align:center;border-bottom: none;border-top: none;" colspan="6" align="right">
										PPH
									</td>
									<td style="vertical-align:center;border-bottom: none;border-top: none;" align="right">
										Rp {{ number_format($data->alSph->pph,0,",",".") }}
									</td>
								</tr>
								<tr>
									<td style="vertical-align:center;border-top: none;" colspan="6" align="right">
										Grandtotal
									</td>
									<td style="vertical-align:center;border-top: none;" align="right">
										Rp {{ number_format($data->alSph->grandtotal,0,",",".") }}
									</td>
								</tr>
								<tr>
									<td style="vertical-align:center;" colspan="7">
										Terbilang ({{ App\Helper\SMB::terbilang($data->alSph->grandtotal) }} Rupiah)
									</td>
								</tr>
							</tfoot>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;padding-top:50px;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="50%" align="center">
									a.n Komandan {{ $data->alProject->alCustomer->name }}
									<br>
									Wadan
									<br>Selaku Pejabat Pembuat Komitmen
									<br><br><br><br>
									<br>{{ $data->sign_name }}
									<br>{{ $data->sign_position }}
								</td>
								<td width="50%" align="center">
									Penyedia Barang
									<br>
									PT. PERWIRA TAMARAYA ABADI
									<br><br><br><br><br><br>
									Prawiro Tedjo Tjandra
									<br>Direktur
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>