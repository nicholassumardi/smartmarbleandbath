<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Laporan Pengeluaran {{ $title }}</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:18px;
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
									<h3>
										<b>
											LAPORAN PENGELUARAN PROYEK {{ $title }}
										</b>
									</h3>
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
						<th style="color:white;"><center>Proyek</center></th>
						<th style="color:white;"><center>Kepada</center></th>
						<th style="color:white;"><center>Tanggal Buat</center></th>
						<th style="color:white;" width="30%"><center>Catatan</center></th>
						<th style="color:white;"><center>Progres Pembayaran</center></th>
						<th style="color:white;"><center>Nominal</center></th>
						<th style="color:white;"><center>Paid(Rp)</center></th>
						<th style="color:white;"><center>Paid(%)</center></th>
					</tr>
				</thead>
				<tbody>
					@php
						$total = 0;
						$totalpaid = 0;
					@endphp
					<tr style="background:#165dd9;">
						<th style="color:white;" colspan="9"><center>1. HPP</center></th>
					</tr>
					@php
						$totalhpp = 0;
						$totalhpppaid = 0;
						$totaldk = 0;
						$totaldkpaid = 0;
						$totaladmin = 0;
						$totaladminpaid = 0;
						$totalluar = 0;
						$totalluarpaid = 0;
						$no = 1;
					@endphp
					@foreach($data->where('type','1') as $key => $row)
						<tr>
							<td style="vertical-align:center;">
								<center>
								{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ $row->alProject->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ $row->to_person }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ date('d M Y',strtotime($row->date)) }}
								</center>
							</td>
							<td style="vertical-align:center;" width="30%">
								Judul : {{ $row->title }} <br> {!! $row->note !!}
							</td>
							<td style="vertical-align:center;">
								<center>
								{!! $row->getProgressPayment() !!}
								</center>
							</td>
							<td style="vertical-align:center;" align="right">
								Rp{{ number_format($row->nominal,0,',','.') }}	
							</td>
							<td style="vertical-align:center;" align="right">
								Rp{{ number_format($row->totalPayment(),0,',','.') }}	
							</td>
							<td style="vertical-align:center;" align="right">
								{{ $row->totalPayment() ? number_format(($row->totalPayment() / $row->nominal) * 100,2,',','.') : 0 }}%
							</td>
						</tr>
						@php
							$total += $row->nominal;
							$totalpaid += $row->totalPayment();
							$totalhpp += $row->nominal;
							$totalhpppaid += $row->totalPayment();
							$no++;
						@endphp
					@endforeach
					<tr>
						<th colspan="6" align="right">TOTAL HPP</th>
						<th align="right">Rp{{ number_format($totalhpp,0,',','.') }}</th>
						<th align="right">Rp{{ number_format($totalhpppaid,0,',','.') }}</th>
						<th align="right">{{ number_format(($totalhpp ? ($totalhpppaid / $totalhpp) * 100 : 0),0,',','.') }}%</th>
					</tr>
					<tr style="background:#165dd9;">
						<th style="color:white;" colspan="9"><center>2. DK</center></th>
					</tr>
					@php
						$no = 1;
					@endphp
					@foreach($data->where('type','2') as $key => $row)
						<tr>
							<td style="vertical-align:center;">
								<center>
								{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ $row->alProject->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ $row->to_person }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ date('d M Y',strtotime($row->date)) }}
								</center>
							</td>
							<td style="vertical-align:center;" width="30%">
								Judul : {{ $row->title }} <br> {!! $row->note !!}
							</td>
							<td style="vertical-align:center;">
								<center>
								{!! $row->getProgressPayment() !!}
								</center>
							</td>
							<td style="vertical-align:center;" align="right">
								Rp{{ number_format($row->nominal,0,',','.') }}	
							</td>
							<td style="vertical-align:center;" align="right">
								Rp{{ number_format($row->totalPayment(),0,',','.') }}	
							</td>
							<td style="vertical-align:center;" align="right">
								{{ $row->totalPayment() ? number_format(($row->totalPayment() / $row->nominal) * 100,2,',','.') : 0 }}%
							</td>
						</tr>
						@php
							$total += $row->nominal;
							$totalpaid += $row->totalPayment();
							$totaldk += $row->nominal;
							$totaldkpaid += $row->totalPayment();
							$no++;
						@endphp
					@endforeach
					@if(count($data->where('type','2')) == 0)
					<tr style="background:#eb4034;text-align:center;">
						<th style="color:white;" colspan="7"><center>Data kosong</center></th>
					</tr>
					@endif
					<tr>
						<th colspan="6" align="right">TOTAL DK</th>
						<th align="right">Rp{{ number_format($totaldk,0,',','.') }}</th>
						<th align="right">Rp{{ number_format($totaldkpaid,0,',','.') }}</th>
						<th align="right">{{ number_format(($totaldk ? ($totaldkpaid / $totaldk) * 100 : 0),0,',','.') }}%</th>
					</tr>
					<tr style="background:#165dd9;">
						<th style="color:white;" colspan="9"><center>3. Admin</center></th>
					</tr>
					@php
						$no = 1;
					@endphp
					@foreach($data->where('type','3') as $key => $row)
						<tr>
							<td style="vertical-align:center;">
								<center>
								{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ $row->alProject->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ $row->to_person }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
								{{ date('d M Y',strtotime($row->date)) }}
								</center>
							</td>
							<td style="vertical-align:center;" width="30%">
								Judul : {{ $row->title }} <br> {!! $row->note !!}
							</td>
							<td style="vertical-align:center;">
								<center>
								{!! $row->getProgressPayment() !!}
								</center>
							</td>
							<td style="vertical-align:center;" align="right">
								Rp{{ number_format($row->nominal,0,',','.') }}	
							</td>
							<td style="vertical-align:center;" align="right">
								Rp{{ number_format($row->totalPayment(),0,',','.') }}	
							</td>
							<td style="vertical-align:center;" align="right">
								{{ $row->totalPayment() ? number_format(($row->totalPayment() / $row->nominal) * 100,2,',','.') : 0 }}%	
							</td>
						</tr>
						@php
							$total += $row->nominal;
							$totalpaid += $row->totalPayment();
							$totaladmin += $row->nominal;
							$totaladminpaid += $row->totalPayment();
							$no++;
						@endphp
					@endforeach
					@if(count($data->where('type','3')) == 0)
					<tr style="background:#eb4034;text-align:center;">
						<th style="color:white;" colspan="9"><center>Data kosong</center></th>
					</tr>
					@endif
					<tr>
						<th colspan="6" align="right">TOTAL ADMIN</th>
						<th align="right">Rp{{ number_format($totaladmin,0,',','.') }}</th>
						<th align="right">Rp{{ number_format($totaladminpaid,0,',','.') }}</th>
						<th align="right">{{ number_format(($totaladmin ? ($totaladminpaid / $totaladmin) * 100 : 0),0,',','.') }}%</th>
					</tr>
					@php
						$no = 1;
					@endphp
					@if(count($data->whereNull('type')) > 0)
						<tr style="background:#165dd9;">
							<th style="color:white;" colspan="9"><center>4. Belum terkelompok</center></th>
						</tr>
						@foreach($data->whereNull('type') as $key => $row)
							<tr>
								<td style="vertical-align:center;">
									<center>
									{{ $no }}
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
									{{ $row->alProject->name }}
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
									{{ $row->to_person }}
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
									{{ date('d M Y',strtotime($row->date)) }}
									</center>
								</td>
								<td style="vertical-align:center;" width="30%">
									Judul : {{ $row->title }} <br> {!! $row->note !!}
								</td>
								<td style="vertical-align:center;">
									<center>
									{!! $row->getProgressPayment() !!}
									</center>
								</td>
								<td style="vertical-align:center;" align="right">
									Rp{{ number_format($row->nominal,0,',','.') }}	
								</td>
								<td style="vertical-align:center;" align="right">
									Rp{{ number_format($row->totalPayment(),0,',','.') }}	
								</td>
								<td style="vertical-align:center;" align="right">
									{{ $row->totalPayment() ? number_format(($row->totalPayment() / $row->nominal) * 100,2,',','.') : 0 }}%	
								</td>
							</tr>
							@php
								$total += $row->nominal;
								$totalpaid += $row->totalPayment();
								$totalluar += $row->nominal;
								$totalluarpaid += $row->totalPayment();
								$no++;
							@endphp
						@endforeach
						<tr>
							<th colspan="8" align="right">TOTAL LAIN-LAIN</th>
							<th align="right">Rp{{ number_format($totalluar,0,',','.') }}</th>
							<th align="right">Rp{{ number_format($totalluarpaid,0,',','.') }}</th>
							<th align="right">{{ number_format(($totalluar ? ($totalluarpaid / $totalluar) * 100 : 0),0,',','.') }}%</th>
						</tr>
					@endif
					<tr>
						<th style="vertical-align:center;font-size:20px;" colspan="6" align="right">
							GRANDTOTAL
						</th>
						<th style="vertical-align:center;font-size:20px;" align="right">
							Rp{{ number_format($total,0,",",".") }}
						</th>
						<th style="vertical-align:center;font-size:20px;" align="right">
							Rp{{ number_format($totalpaid,0,",",".") }}
						</th>
						<th style="vertical-align:center;font-size:20px;" align="right">
							{{ number_format(($total ? ($totalpaid / $total) * 100 : 0),0,",",".") }}%
						</th>
					</tr>
				</tbody>
			</table><br><br>
		</div>
	</body>
</html>