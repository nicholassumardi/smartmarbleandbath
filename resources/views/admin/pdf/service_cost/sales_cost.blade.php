<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sales Service Order {{ $service->code }}</title>
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:14px;
			}
		
			.invoice-box {
				font-size: 16px;
				font-family: 'Lato', sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
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
			
			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: right;
			}
			
			.invoice-box.rtl table tr td:nth-child(1) {
				text-align: right;
			}
			
			.invoice-box.rtl table tr td:nth-child(0) {
				text-align: right;
			}
			
			@page { margin: 1cm; }
			body { margin: 1cm; }
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
							<tr style="background-color:#51b6bc;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>SERVICE CHARGE</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table>
				<tr>
					<td colspan="2">
						<table width="100%">
							<tr>
								<td width="20%" style="font-size:12px;">CUSTOMER</td>
								<td style="text-align:left; font-size:12px;">: {{$service->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">DATE</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($service->created_at)) }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">DOCUMENT NUMBER</td>
								<td style="text-align:left; font-size:12px;">: {{$service->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">ADDRESS</td>
								<td style="text-align:left; font-size:12px;">: {{ $service->address }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">PHONE</td>
								<td style="text-align:left; font-size:12px;">: {{$service->customer->phone }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;"></td>
								<td style="text-align:left; font-size:12px;"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#51b6bc;;text-align:center;">
						<th style="color:white;" colspan="2"><center>OTHER(S)</center></th>
					</tr>
					<tr style="background:#51b6bc;;text-align:center;">
						<th style="color:white;"><center>ITEM</center></th>
						<th style="color:white;"><center>NOMINAL</center></th>
					</tr>
				</thead>
				<tbody>
					@php
						if(date('Y-m-d',strtotime($service->created_at)) < '2022-04-01'){
							$persenppn = 0.1;
							$ppnpembagi = 1.1;
						}else{
							$persenppn = 0.11;
							$ppnpembagi = 1.11;
						}
					
						$koma = 0;
					@endphp
					<tr>
						<td style="vertical-align:center;">
							<center>
								{{ $service->note ? $service->note : 'Other' }}
							</center>
						</td>
						<td>
							Rp. {{ number_format(($service->delivery_cost + $service->cutting_cost + $service->misc_cost),$koma,',','.') }}
						</td>
					</tr>
					<tr>
						<td><center>Subtotal</center></td>
						<td>Rp. {{ number_format(($service->delivery_cost + $service->cutting_cost + $service->misc_cost), $koma, ',', '.') }}</td>
					</tr>
					<tr>
						<td><center>Tax</center></td>
						<td>Rp. {{ $service->is_ppn == '1' ? number_format((($service->delivery_cost + $service->cutting_cost + $service->misc_cost) * $persenppn), $koma, ',', '.') : 0 }}</td>
					</tr>
					<tr>
						<td><center>Total</center></td>
						<td>Rp. {{ $service->is_ppn == '1' ? number_format(((($service->delivery_cost + $service->cutting_cost + $service->misc_cost) * $persenppn) + ($service->delivery_cost + $service->cutting_cost + $service->misc_cost)), $koma, ',', '.') : number_format(($service->delivery_cost + $service->cutting_cost + $service->misc_cost), $koma, ',', '.') }}</td>
					</tr>
				</tbody>
			</table>
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<table cellpadding="2" cellspacing="0" border="1" style="background-color:#51b6bc;font-size:14px;color:white;">
							<tr>
								<td>
									<b>GRANDTOTAL</b>
								</td>
								<td style="text-align:center;">
									<b>Rp. {{ $service->is_ppn == '1' ? number_format((($service->delivery_cost + $service->cutting_cost + $service->misc_cost) + ($persenppn * ($service->delivery_cost + $service->cutting_cost + $service->misc_cost))), $koma, ',', '.') : number_format(($service->delivery_cost + $service->cutting_cost + $service->misc_cost), $koma, ',', '.') }}</b>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									PAYMENT WILL BE TRANSFERRED TO :
									@if($service->customer->branch == '2')
										@if($service->is_ppn == '1')
											<p>
												<br>BCA 329-37-22222
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Baliwerti - Surabaya<br>

												<br>OR<br>

												<br>Mandiri 14-000-888-222-01
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Kembang Jepun - Surabaya
											</p>
										@else
											<p>
												<br>CIMB NIAGA 706.884.834.000
												<br>a/n Andy Hidayat
												<br>Cab. Klampis - Surabaya<br>
											</p>
										@endif
									@else
										@if($service->is_ppn == '1')
											<p>
												<br>BCA 468-3434-178
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Indrapura - Surabaya<br>

												<br>OR<br>

												<br>Mandiri 14-000-5996-5997
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Kembang Jepun - Surabaya
											</p>
										@else
											<p>
												<br>BCA 329.0258.510
												<br>a/n Andy Hidayat
												<br>Cab. Baliwerti<br>
											</p>
										@endif
									@endif
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div>Note :
									<br>{{ $service->note }}
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div>
										Nominal in words : {{ $service->is_ppn == '1' ? App\Helper\SMB::say((($service->delivery_cost + $service->cutting_cost + $service->misc_cost) + ($persenppn * ($service->delivery_cost + $service->cutting_cost + $service->misc_cost)))) : App\Helper\SMB::say(($service->delivery_cost + $service->cutting_cost + $service->misc_cost)) }}
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td width="5%"></td>
					<td width="45%">
					
					</td>
				</tr>
				
			</table><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					@if(isset($service->approved->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Acknowledged By,</div>
						@if(isset($service->approved->sign))
							<div><img src="{{ url(Storage::url($service->approved->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $service->approved->name }}</div>
						<div style="font-size:10px;">{{ $service->approved->userRole->first()->role() }}</div>
					</td>
					@endif
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Created By</div>
						@if(isset($service->user->sign))
							<div><img src="{{ url(Storage::url($service->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $service->user->name }}</div>
						<div style="font-size:10px;">{{ $service->user->userRole->first()->role() }}</div>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:50px;">
				<tr>
					<td style="text-align:right;color:black;font-size:10px;">
						Created at : {{ $service->created_at }}
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>