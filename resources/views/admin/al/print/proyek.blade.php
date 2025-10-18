<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Laporan Proyek Tahun {{ $year }}</title>
		<style>
			body {
				font-family: "Times New Roman", Times, serif;
			}
			
			th {
				font-size:13px;
			}
		
			.invoice-box {
				font-size: 13px;
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

			@media print {
				@page {size: A4 portrait; }
			}

			.invoice-box.rtl {
				direction: rtl;
			}

			.invoice-box.rtl table {
				text-align: right;
			}
			
			@page { margin: 0.5cm; }
			body { margin: 0.5cm; }
			
			input[type=checkbox] {
			  accent-color: white;
			  color:black;
			  outline: 1px solid black;
			}
		</style>
	</head>
	<body onload="window.print()">
		<div class="invoice-box" style="font-size:12px !important;">
			<table cellpadding="5" cellspacing="0" width="100%">
				<tr>
					<td style="text-align:center;" colspan="2">
						<img src="{{ url('website/logo_al_rev_3.jpg') }}" width="100%" height="auto">
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: justify;">
						<table cellpadding="5" cellspacing="0" width="100%" border="1">
							<thead>
								<tr align="center">
									<th rowspan="2">No</th>
									<th rowspan="2">Bidang Pekerjaan</th>
									<th rowspan="2">Sub Bidang Pekerjaan</th>
									<th rowspan="2">Lokasi</th>
									<th colspan="2">Pemberi Tugas / Pengguna Jasa</th>
									<th colspan="2">Kontrak *)</th>
									<th colspan="2">Progres Terakhir</th>
								</tr>
								<tr>
									<th>Nama</th>
									<th>Alamat/Telepon</th>
									<th>No/Tanggal</th>
									<th>Nilai (Rp.)</th>
									<th>Tanggal</th>
									<th>Prestasi Kerja (%)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td align="center">1</td>
									<td align="center">2</td>
									<td align="center">3</td>
									<td align="center">4</td>
									<td align="center">5</td>
									<td align="center">6</td>
									<td align="center">7</td>
									<td align="center">8</td>
									<td align="center">9</td>
									<td align="center">10</td>
								</tr>
								@foreach($data as $key => $row)
								<tr>
									<td align="center">{{ $key+1 }}.</td>
									<td align="center">{{ $row->field_of_work }}</td>
									<td align="center">{{ $row->name }}</td>
									<td align="center">{{ $row->location }}</td>
									<td align="center">{{ $row->alCustomer->name }}</td>
									<td align="center">{{ $row->alCustomer->address }}</td>
									<td align="center">{{ $row->latestSphContractNo().' / '.$row->latestSphContractDate() }}</td>
									<td align="right">{{ number_format($row->latestSphNominal(),0,',','.') }}</td>
									<td align="center"></td>
									<td align="center"></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td style="text-align: justify;" width="60%">
						&nbsp;
					</td>
					<td style="text-align: center;" width="40%">
						<br>
						<br>Surabaya, {{ App\Helper\SMB::tgl_indo(date('Y-m-d')) }}
						<br>
						<b>PT. PERWIRA TAMARAYA ABADI</b>
						<br><br><br><br><br>
						<b>Prawiro Tedjo Tjandra</b>
						<br>Direktur
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>