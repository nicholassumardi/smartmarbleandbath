<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sales Invoice {{ $project->proforma_code }}</title>
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
				background: #0b95b8;
				border-bottom: 1px solid #0b95b8;
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
			
			.separate-box {
				height: 100%;
			}
			
			.separate-box table tr td {
				padding: 5px;
			}
			
			.separate-box #table-kwitansi {
				border: 1px solid black;
			}
			
			@page { margin: 1cm; }
			body { margin: 1cm; }
		</style>
	</head>
	<body>
		<div class="invoice-box">
			@if($project->projectSale->sales->branch != '4')
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						@php
							if($project->is_dropshipper == '1'){
						@endphp
						<table>
							<tr>
								<td class="title" rowspan="2">
									<img src="{{ url('website/logo-black.png') }}" width="275">
								</td>
								<td colspan="2" style="text-align:right;padding-bottom:15px;"><img src="{{ url('website/pta_new_logo.png') }}" height="30px" style="margin-right:5px;"></td>
							</tr>
							<tr>
								// <td style="padding-right:25px;padding-top:100px !important;">
								// 	<div style="font-size:9px; font-weight:bold;">JAGAT BUILDING</div>
								// 	<div style="font-size:9px; font-weight:500;">Tomang Raya No 28 - 30, Jakarta 11430</div>
								// 	<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
								// 	<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com</div>
								// </td>
								<td style="border-left: 3px solid #51b6bc; text-align:right;padding-top:100px !important;">
									<div style="font-size:9px; font-weight:bold;">MODERN CERAMIC</div>
									<div style="font-size:9px; font-weight:500;">Baliwerti 119 - 121, Surabaya 60174</div>
									<div style="font-size:9px; font-weight:500;">Phone : 031-5472860 / 031-5324505</div>
									<div style="font-size:9px; font-weight:500;">Email : info@smartmarbleandbath.com</div>
								</td>
							</tr>
						</table>
						@php
							}elseif($project->is_dropshipper == '2'){
						@endphp
						<table>
							<tr>
								<td class="title" rowspan="2">
									@php
										if($project->dropshipper->image !== ''){
											echo '<img src="'.$project->dropshipper->image().'" height="75">';
										}
									@endphp
								</td>
								<td colspan="2" style="text-align:right;"><h3>{{ strtoupper($project->dropshipper->name) }}</h3></td>
							</tr>
							<tr>
								<td></td>
								<td style="text-align:right;">
									<div style="font-size:9px; font-weight:bold;">{{ $project->dropshipper->address }}</div>
									<div style="font-size:9px; font-weight:500;">Phone : {{ $project->dropshipper->phone }}</div>
									<div style="font-size:9px; font-weight:500;">Email : {{ $project->dropshipper->email }}</div>
								</td>
							</tr>
						</table>
						@php
							}
						@endphp
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2" style="vertical-align: middle;padding-top:15px;padding-bottom:15px;">
						@php
							if($project->is_dropshipper == '1'){
						@endphp
						<center>
							<img src="{{ url('website/kop_brand_report.png') }}" width="100%">
						</center>
						@php
							}
						@endphp
					</td>
				</tr>
			</table>
			@else
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2" style="vertical-align: middle;padding-top:15px;padding-bottom:45px;">
						<center>
							<img src="{{ url('website/letterheadpsi_big.jpg') }}" width="100%" style="width: 1500px !important;">
						</center>
					</td>
				</tr>
			</table>
			@endif
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr style="background-color:#0b95b8;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>SALES INVOICE OTHER (INV)</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table>
				<tr>
					<td width="50%">
						<table>
							<tr class="heading">
								<td colspan="3"><div style="font-size:12px;"><b>INFORMATION :</b></div></td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Date of DO</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ date('d F Y', strtotime($project->delivery_date)) }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Invoice. Number</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->proforma_code }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">DO. Number</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->code }}</td>
							</tr>
							@php
								if($project->is_dropshipper == '1'){
							@endphp
							<tr>
								<td width="40%" style="font-size:12px;">SO. Number</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->projectSale->code }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Project</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->project->name }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Warehouse</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->warehouse->name }}</td>
							</tr>
							@php
								}
							@endphp
						</table>
					</td>
					<td width="50%">
						<table>
							<tr class="heading">
								<td colspan="3"><div style="font-size:12px;"><b>SHIP TO :</b></div></td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Receiver</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->is_dropshipper == '1' ? $project->receiver_name : strtoupper($project->dropshipper->name) }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Address</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->is_dropshipper == '1' ? $project->project->customer->address : $project->dropshipper->address }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Phone</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->is_dropshipper == '1' ? $project->phone : $project->dropshipper->phone }}</td>
							</tr>
							<!-- <tr>
								<td width="40%" style="font-size:12px;">Expedition</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->vendor->name }}</td>
							</tr> -->
							<tr>
								<td width="40%" style="font-size:12px;">City</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->city->name }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#0b95b8;;text-align:center;">
						<th style="color:white;" colspan="2"><center>OTHER(S)</center></th>
					</tr>
					<tr style="background:#0b95b8;;text-align:center;">
						<th style="color:white;"><center>ITEM</center></th>
						<th style="color:white;"><center>NOMINAL</center></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<center>
								Other
								<br>Subtotal
								<br>Tax
								<br>Total
							</center>
						</td>
						<td>
							@php
								if(date('Y-m-d',strtotime($project->projectSale->created_at)) < '2022-04-01'){
									$persenppn = 0.1;
								}else{
									$persenppn = 0.11;
								}
							@endphp
							@if($project->projectSale->projectDelivery->first()->code == $project->code)
								IDR {{ number_format($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost,0,',','.') }}
								<br>IDR {{ number_format($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost, 0, ',', '.') }}
								<br>IDR {{ $project->projectSale->ppn_cost == '1' ? number_format(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost) * $persenppn, 0, ',', '.') : 0 }}
								<br>IDR {{ $project->projectSale->ppn_cost == '1' ? number_format((($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost) * $persenppn) + ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost), 0, ',', '.') : number_format(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost), 0, ',', '.') }}
							@else
								IDR {{ number_format(0,0,',','.') }}
								<br>IDR 0
								<br>IDR 0
								<br>IDR 0
							@endif
						</td>
					</tr>
				</tbody>
			</table>
			
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<table cellpadding="2" cellspacing="0" border="1" style="background-color:#0b95b8;font-size:14px;color:white;">
							<tr>
								<td>
									<b>GRANDTOTAL</b>
								</td>
								<td style="text-align:center;">
									@if($project->projectSale->projectDelivery->first()->code == $project->code)
										<b>IDR {{ $project->projectSale->ppn_cost == '1' ? number_format(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost) + ($persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost)), 0, ',', '.') : number_format(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost), 0, ',', '.') }}</b>
									@else
										<b>IDR 0</b>
									@endif
								</td>
							</tr>
							<tr>
								<td colspan="2">
									PAYMENT WILL BE TRANSFERRED TO :
									@if($project->projectSale->sales->branch == '2')
										@if($project->project->ppn == '1')
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
												<br>BCA 329.0258.510
												<br>a/n Andy Hidayat
												<br>Cab. Baliwerti<br>
											</p>
										@endif
									@else
										@if($project->project->ppn == '1')
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
							<!-- <tr>
								<td colspan="2">
									<div>Note :
									<br>{{ $project->projectSale->note }}
								</td>
							</tr> -->
							<tr>
								<td colspan="2">
									<div>
										@if($project->projectSale->projectDelivery->first()->code == $project->code)
											Nominal in words : {{ $project->projectSale->ppn_cost == '1' ? App\Helper\SMB::say(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost) + ($persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost))) : App\Helper\SMB::say(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost)) }}
										@else
											Nominal in words : Zero
										@endif Rupiahs
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td width="5%"></td>
					<td width="45%">
						<table cellpadding="2" cellspacing="0" border="1" style="font-size:14px;">
							<tr>
								<td>
									<div>Note :
									<br>{{ $project->service_note }}
								</td>
							</tr>
						</table>
					</td>
				</tr>
				
			</table>
			<br><br><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					@php
						//if($project->is_dropshipper == '1'){
					@endphp
					<td style="text-align:center;">
						<div style="font-size:10px;">Sales</div>
						@if(isset($project->projectSale->sales->sign))
							<div><img src="{{ url(Storage::url($project->projectSale->sales->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">( {{ isset($project->projectSale->sales->name) ? $project->projectSale->sales->name : '........................' }} )</div>
						<div style="font-size:10px;font-weight:700;">( {{ isset($project->projectSale->sales->name) ? $project->projectSale->sales->userRole->first()->role() : '' }} )</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Checked by</div>
						@if($project->user->sign)
							<div><img src="{{ url(Storage::url($project->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">( {{ $project->user->name ? $project->user->name : '' }} )</div>
						<div style="font-size:10px;font-weight:700;">( {{ $project->user->name ? $project->user->userRole->first()->role() : '' }} )</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Acknowledged By</div>
						<div><img src="{{ url(Storage::url(App\Models\User::find(4)->sign)) }}" height="65px"></div>
						<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(4)->name }} )</div>
						<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(4)->userRole->first()->role() }} )</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Approved By</div>
						<div><img src="{{ url(Storage::url(App\Models\User::find(7)->sign)) }}" height="65px"></div>
						<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(7)->name }} )</div>
						<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(7)->userRole->first()->role() }} )</div>
					</td>
					@php
						//}
					@endphp
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
		@php
			$grandtotal = $project->projectSale->ppn_cost == '1' ? ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost) + ($persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost)) : ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost);
		
			$text = '';
					
			if($grandtotal < 5000000){
				$text = 'background-image: url('.url("website/stempel_pta_baru_small_1.png").');background-size:12px;background-repeat:no-repeat;background-position: center center;';
			}
		@endphp
		<div class="separate-box">
			<table id="table-kwitansi" style="vertical-align: top;padding: 5px;font-size:13px;background-image: url({{ url('website/bg_kwitansi.png') }});" width="100%" height="auto">
				<tr>
					<td rowspan="6" width="20%" style="padding:0;"><img src="{{ url('website/logo_samping_1.png') }}" height="350px"></td>
					<td width="25%">Receipt Number</td>
					<td width="1%">:</td>
					<td><?=str_replace('DO','RC',$project->code)?></td>
				</tr>
				<tr>
					<td>Received from</td>
					<td width="1%">:</td>
					<td>{{ $project->projectSale->project->customer->name }}</td>
				</tr>
				<tr>
					<td>Nominal in words</td>
					<td width="1%">:</td>
					<td>{{ 
						$project->projectSale->ppn_cost == '1' ? 
							App\Helper\SMB::say(round(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost) + ($persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost)))) 
						: 	App\Helper\SMB::say(round(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost))) }} Rupiahs
					</td>
				</tr>
				<tr>
					<td>For payment</td>
					<td width="1%">:</td>
					<td>According to Sales Invoice {{ $project->proforma_code }}</td>
				</tr>
				<tr>
					<td>Total</td>
					<td width="1%">:</td>
					<td><div style="border:1p solid black;padding:10px;">
						IDR {{ 
							$project->projectSale->ppn_cost == '1' ? 
								number_format(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost) + ($persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost)), 0, ',', '.') 
							: 	number_format(($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost), 0, ',', '.') 
						}}</div></td>
				</tr>
				<tr>
					<td></td>
					<td width="1%"></td>
					<td>
						<table>
							<tr>
								<td width="10%"></td>
								<td width="10%"></td>
								<td width="10%"></td>
								<td width="10%"></td>
								<td style="text-align:center;{{ $text }}">
									@if($project->projectSale->sales->branch == '1')
									
									Surabaya, {{ date('d F Y', strtotime($project->delivery_date)) }}
										<br>
										@if($grandtotal < 5000000)
										<div style="position: relative;">
											<img src="{{ url(Storage::url(App\Models\User::find(11)->sign)) }}" height="65px">
										</div>
										@else
											<br><br><br><br><br><br><br>
										@endif
										{{ App\Models\User::find(11)->name }}
										<br>
										(AR Staff)
									@elseif($project->projectSale->sales->branch == '2')
									
										Jakarta, {{ date('d F Y', strtotime($project->delivery_date)) }}
										<br><br><br><br><br><br><br><br>
										<u>DAVID PRAWIRO TEDJO</u>
										<br>
										(Direktur)
									@endif
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>