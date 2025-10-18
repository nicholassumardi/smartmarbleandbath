<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sales News {{ $project->code }}</title>
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:14px;
			}
		
			.invoice-box {
				font-size: 16px;
				font-family: font-family: 'Lato', sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
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
				background: #51b6bc;
				border-bottom: 1px solid #51b6bc;
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
				font-family: 'Lato', sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}
			
			@page { margin: 1cm 1.5cm 1cm 1.5cm; }
			body { 
				margin: 1cm 1.5cm 1cm 1.5cm; 
			}
		
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
									<img src="{{ url('website/logo-black.png') }}" width="275">
								</td>
								<td colspan="2" style="text-align:right;padding-bottom:15px;"><img src="{{ url('website/pta_new_logo.png') }}" height="30px" style="margin-right:5px;"></td>
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
							<img src="{{ url('website/kop_brand_report.png') }}" width="100%">
						</center>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr>
								<td style="text-align:right;">
								{{ $project->sales->branch == '1' ? 'Surabaya' : 'Jakarta' }}, {{ date('d M Y',strtotime($project->created_at)) }}
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr>
								<td style="text-align:center;padding-top:10px;padding-bottom:10px;">
									<h3><b>BERITA ACARA</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table width="100%" style="background-image:url('{{ url('website/confidential_2.png') }}');background-repeat: no-repeat;background-position: center;">
				<tr>
					<td>
						@php
							if(date('Y-m-d',strtotime($project->created_at)) < '2022-04-01'){
								$persenppn = 10;
							}else{
								$persenppn = 11;
							}
						@endphp
						Yth. Bapak David Prawiro Tedjo
						<br>PT. Perwira Tamaraya Abadi
						<br>Surabaya
						<br><br>
						Dengan Hormat,
						<br><br>
						Berikut ini adalah penjabaran komisi untuk Proyek {{ $project->project->name }}, dengan rincian :
						<br>
						<table style="margin-left:25px;" width="100%">
							<tr>
								<td width="40%">Nama Customer</td>
								<td>: {{ $project->project->customer->name }}</td>
							</tr>
							<tr>
								<td width="40%">SO No.</td>
								<td>: {{ $project->code }}</td>
							</tr>
							<tr>
								<td>Total Penjualan</td>
								<td>: Rp. {{ $project->getTotalRaw() }} (Exclude PPN {{ $persenppn }}%)</td>
							</tr>
							<tr>
								<td>Fee Konsultan</td>
								<td>: {{ $project->mid_type == '1' ? $project->mid_fee.' % From Total Product Cost (Before Tax)' : ( $project->mid_type == '2' ? 'Rp. '.number_format($project->mid_fee,0,',','.').' per Unit Product Type' : 'Rp. '.number_format($project->mid_fee,0,',','.').' From Total Product Cost (Before Tax)') }}</td>
							</tr>
							<tr>
								<td>Nominal Fee</td>
								<td>: Rp. {{ $project->getTotalMiddleman() }}</td>
							</tr>
							<tr>
								<td>Keterangan</td>
								<td>: {{ $project->mid_note }}</td>
							</tr>
						</table>
						<br>
						dengan ini saya PIC PT. Perwira Tamaraya Abadi yang bertanggung jawab di sini:
						<br>
						@if($project->sales->branch == '1')
							<table width="100%">
								<tr>
									<td width="20%">Nama</td>
									<td>: {{ $project->user->name }}</td>
								</tr>
								<tr>
									<td>Jabatan</td>
									<td>: Sales</td>
								</tr>
							</table>
						@else
							<table width="100%">
								<tr>
									<td width="20%">Nama</td>
									<td>: Antonius Trijanto</td>
								</tr>
								<tr>
									<td>Jabatan</td>
									<td>: Sales & Marketing Manager</td>
								</tr>
							</table>
						@endif
						<br>
						Mengajukan permohonan untuk pencairan komisi.
						<br><br>
						Atas perhatian dan kerjasamanya, saya sampaikan terima kasih.
					</td>
				</tr>
			</table>
			<br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					@if($project->sales->branch == '1')
						<td style="text-align:center;" width="33%">
							<div style="font-size:10px;">Pemohon</div>
							@if(isset($project->user->sign))
								<div><img src="{{ url(Storage::url($project->user->sign)) }}" height="65px"></div>
							@else
								<br><br><br>
							@endif
							<div style="font-size:10px;">{{ $project->user->name }}</div>
							<div style="font-size:10px;">{{ $project->user->userRole->first()->role() }}</div>
						</td>
						
						<td style="text-align:center;padding-top:100px;" width="33%">
							<div style="font-size:10px;">Menyetujui,</div>
							@if(isset($project->director->name))
								@if(isset($project->director->sign))
									<div><img src="{{ url(Storage::url($project->director->sign)) }}" height="65px"></div>
								@else
									<br><br><br>
								@endif
								<div style="font-size:10px;">{{ $project->director->name }}</div>
								<div style="font-size:10px;">{{ $project->director->userRole->first()->role() }}</div>
							@else
								<br><br><br>
								<div style="font-size:10px;">(David Prawiro Tedjo)</div>
							@endif
						</td>
						<td style="text-align:center;" width="33%">
							<div style="font-size:10px;">Mengetahui,</div>
							@if(isset($project->marketing->name))
								@if(isset($project->marketing->sign))
									<div><img src="{{ url(Storage::url($project->marketing->sign)) }}" height="65px"></div>
								@else
									<br><br><br>
								@endif
								<div style="font-size:10px;">{{ $project->marketing->name }}</div>
								<div style="font-size:10px;">{{ $project->marketing->userRole->first()->role() }}</div>
							@else
								<br><br><br>
								<div style="font-size:10px;">(.............................)</div>
								<div style="font-size:10px;">(Sales & Marketing Manager)</div>
							@endif
						</td>
					@else
						<td style="text-align:center;" width="50%">
							<div style="font-size:10px;">Penanggung jawab,</div>
							@if(isset($project->marketing->name))
								@if(isset($project->marketing->sign))
									<div><img src="{{ url(Storage::url($project->marketing->sign)) }}" height="65px"></div>
								@else
									<br><br><br>
								@endif
								<div style="font-size:10px;">{{ $project->marketing->name }}</div>
								<div style="font-size:10px;">(Sales & Marketing Manager)</div>
							@else
								<br><br><br>
								<div style="font-size:10px;">(.............................)</div>
								<div style="font-size:10px;">(Sales & Marketing Manager)</div>
							@endif
						</td>
						<td style="text-align:center;" width="50%">
							<div style="font-size:10px;">Mengetahui,</div>
							@if(isset($project->director->name))
								@if(isset($project->director->sign))
									<div><img src="{{ url(Storage::url($project->director->sign)) }}" height="65px"></div>
								@else
									<br><br><br>
								@endif
								<div style="font-size:10px;">{{ $project->director->name }}</div>
								<div style="font-size:10px;">{{ $project->director->userRole->first()->role() }}</div>
							@else
								<br><br><br>
								<div style="font-size:10px;">David Prawiro Tedjo</div>
								<div style="font-size:10px;">(Direktur)</div>
							@endif
						</td>
					@endif
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:50px;">
				<tr>
					<td style="text-align:right;color:black;font-size:10px;">
						Created at : {{ $project->created_at }}
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>