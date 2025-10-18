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
		@if($mode == '')
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
								<td style="text-align:left !important;">: Penawaran Harga</td>
							</tr>
						</table>
					</td>
					<td width="50%">
						Surabaya, <u>{{ App\Helper\SMB::tgl_indo($data->date) }}</u>
						<br><br><br><br>
						Kepada
						<br>
						Yth. <u>{{ $data->alProject->alCustomer->name }}</u>
						<br>
						<u>{{ $data->alProject->name }}</u>
						<br>
						Di <u>{{ ucwords(strtolower($data->alProject->city->name)) }}</u>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: justify;">
						<p>Dengan Hormat,</p>
						<p>
							&nbsp; &nbsp; &nbsp; &nbsp; Sehubungan dengan pengumuman <u>{{ $data->alProject->name }}</u> pada <u>{{ $data->source }}</u> pada tanggal <u>{{ App\Helper\SMB::tgl_indo($data->date) }}</u> dan setelah kami pelajari dengan seksama, maka dengan ini kami mengajukan penawaran harga untuk Pekerjaan Alat Bahari TA 2022 beserta kelengkapan sebesar <b><u>Rp {{ number_format($data->grandtotal,0,',','.') }},- ({{ App\Helper\SMB::terbilang($data->grandtotal) }} Rupiah)</u> {{ $data->alProject->is_ppn == '1' ? 'sudah termasuk PPN 11%' : '' }}</b>.
						</p>
						<p>
							&nbsp; &nbsp; &nbsp; &nbsp; Penawaran ini sudah memperhatikan ketentuan dan persyaratan yang tercantum dalam dokumen Pengadaan untuk melaksanakan pekerjaan tersebut di atas. Kami akan melaksanakan pekerjaan tersebut dalam jangka waktu pelaksanaan pekerjaan selama 30 (tiga puluh) hari kalender.
						</p>
						<p>
							&nbsp; &nbsp; &nbsp; &nbsp; Penawaran ini berlaku selama <b>{{ $data->period }} ({{ App\Helper\SMB::terbilang($data->period) }})</b> hari kalender sejak tanggal Surat Penawaran ini kami buat. Sesuai dengan persyaratan Dokumen Pengadaan, bersama Surat penawaran ini kami lampirkan :
						</p>
						<p>
							<ol type="a">
								<li>Daftar Harga dan Uraian Harga.</li>
								<li>Spesifikasi Teknis barang.</li>
								<li>Dokumen Isian Kualifikasi.</li>
								<li>Dokumen Legal.</li>
							</ol>
						</p>
						<p>
						&nbsp; &nbsp; &nbsp; &nbsp; Surat Penawaran beserta lampirannya kami sampaikan sebanyak 2 (dua) rangkap yang terdiri dari dokumen asli 1 (satu) rangkap dan salinannya 1 (satu) rangkap. Dengan disampaikannya Surat Penawaran ini, maka kami menyatakan sanggup dan akan tunduk pada semua ketentuan yang tercantum dalam Dokumen Pengadaan.
						</p>
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
		@endif
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
											DAFTAR KUANTITAS HARGA
											<br>
											{{ $data->alProject->name }}
											<br>
											Nomor {{ $data->code }}
										</b>
									</h4>
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
						@if($mode == '')
						<th style="color:white;"><center>Harga/Unit</center></th>
						<th style="color:white;"><center>Total</center></th>
						@endif
					</tr>
				</thead>
				<tbody>
					@php
						$noparent = 'A';
					@endphp
					@foreach($parent as $rowparent)
						@foreach($data->alSphProduct()->whereHas('alProduct', function($query) use ($rowparent){ $query->where('al_product_parent_id',$rowparent['id']); })->orderBy('id')->get() as $key => $ps)
							@if($key == 0 && count($parent) > 1)
								<tr>
									<td style="vertical-align:center;" colspan="13">
										<b>{{ $rowparent['name'] ? $noparent.'. '.$rowparent['name'] : $noparent.'. '.'Lain-lain' }}</b>
									</td>
								</tr>
							@endif
							@php
								$hpp = $ps->buy_price * $ps->qty;
								$dk = $ps->total * $data->dk / 100;
								$admin = $ps->total * $data->admin / 100;
								$profit = $ps->total - $hpp - $dk - $admin;
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
								@if($mode == '')
								<td style="vertical-align:center;" align="right">
									Rp {{ number_format($ps->sell_price,0,',','.') }}
								</td>
								<td style="vertical-align:center;" align="right">
									Rp {{ number_format($ps->total,0,',','.') }}	
								</td>
								@endif
							</tr>
						@endforeach
						@php
							$noparent++;
						@endphp
					@endforeach
					@if($mode == '')
					<tr>
						<td style="vertical-align:center;" colspan="6" align="right">
							Total
						</td>
						<td style="vertical-align:center;" align="right">
							Rp {{ number_format($data->total,0,",",".") }}
						</td>
					</tr>
					<tr>
						<td style="vertical-align:center;" colspan="6" align="right">
							PPN
						</td>
						<td style="vertical-align:center;" align="right">
							Rp {{ number_format($data->ppn,0,",",".") }}
						</td>
					</tr>
					<tr>
						<td style="vertical-align:center;" colspan="6" align="right">
							PPH
						</td>
						<td style="vertical-align:center;" align="right">
							Rp {{ number_format($data->pph,0,",",".") }}
						</td>
					</tr>
					<tr>
						<td style="vertical-align:center;" colspan="6" align="right">
							Grandtotal
						</td>
						<td style="vertical-align:center;" align="right">
							Rp {{ number_format($data->grandtotal,0,",",".") }}
						</td>
					</tr>
					@endif
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