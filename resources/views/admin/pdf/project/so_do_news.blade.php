<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sales Order {{ $project->code }}</title>
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
				background: #1a73e8;
				border-bottom: 1px solid #1a73e8;
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
							<tr style="background-color:#ebb220;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>SALES ORDER</b></h3>
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
								<td width="20%" style="font-size:12px;">ATTENTION</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ strtoupper($project->project->manager) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">DATE</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ date('d F Y', strtotime($project->created_at)) }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">CUSTOMER</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->project->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PROJECT NUMBER</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->project->code }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">PROJECT NAME</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->project->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">SALES NAME</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->sales->name }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">ADDRESS</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->address }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">SO NUMBER</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->code }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">CITY</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->project->city->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;"></td>
								<td style="text-align:left; font-size:12px;"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			@php
				if(date('Y-m-d',strtotime($project->created_at)) < '2022-04-01'){
					$persenppn = 0.1;
					$ppnpembagi = 1.1;
				}else{
					$persenppn = 0.11;
					$ppnpembagi = 1.11;
				}
				
				$koma = 0;
				
				if($project->currency_id !== '5'){
					$koma = 2;
				}
			
				$adatile = false;
				$adalain = false;
				$total = 0;
				$totaltile = 0;
				$totallain = 0;
				foreach($project->projectSaleProduct as $key => $pp){
					if($pp->unit == '2' || $pp->unit == '3'){
						$adatile = true;
					}
					if($pp->unit == '1' || $pp->unit == '4'){
						$adalain = true;
					}
				}
			@endphp

				@php
				$no = 1;
				if($adatile == true){
				@endphp
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
					@php
						if($adalain == true){
					@endphp
						<tr style="background:#ebb220;text-align:center;">
							<th style="color:white;" colspan="12"><center>TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>CODE</center></th>
						<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
						<th style="color:white;" rowspan="2"><center>BRAND</center></th>
						<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
						<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
						<th style="color:white;" rowspan="2"><center>COLOR</center></th>
						<th style="color:white;" colspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>PRICE (BEFORE TAX)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
					</th>
				<tbody>
					@foreach($project->projectSaleProduct()->orderBy('id')->get() as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
								
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$countbox = $pp->qty;
									$total += ($pp->best_price * $countbox) / $project->currency_rate;
									$totaltile += ($pp->best_price * $countbox) / $project->currency_rate;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$countbox = $pp->qty;
										$total += ($pp->best_price * $countbox) / $project->currency_rate;
										$totaltile += ($pp->best_price * $countbox) / $project->currency_rate;
									}else{
										$countbox = ceil(round($pp->qty / $m2,2));
										$total += ($pp->best_price * $m2 * $countbox) / $project->currency_rate;
										$totaltile += ($pp->best_price * $m2 * $countbox) / $project->currency_rate;
									}
									
								}
								
						@endphp
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $pp->product->type->image() }}" style="max-width:15px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->brand->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->category->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->color->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $countbox * $m2 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ number_format($countbox,0,',','.') }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format((($pp->best_price * $countbox) / ($countbox * $m2)) / $project->currency_rate, $koma, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format((($pp->best_price * $countbox) / ($countbox * $m2)) / $project->currency_rate, $koma, ',', '.');
											}else{
												echo number_format((($pp->best_price * $m2 * $countbox) / ($countbox * $m2)) / $project->currency_rate, $koma, ',', '.');
											}
										}
										
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format($pp->best_price / $project->currency_rate, $koma, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format($pp->best_price / $project->currency_rate, $koma, ',', '.');
											}else{
												echo number_format(($pp->best_price * $m2) / $project->currency_rate, $koma, ',', '.');
											}
										}
										
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php 
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format(($pp->best_price * $countbox) / $project->currency_rate, $koma, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format(($pp->best_price * $countbox) / $project->currency_rate, $koma, ',', '.');
											}else{
												echo number_format(($pp->best_price * $m2 * $countbox) / $project->currency_rate, $koma, ',', '.');
											}
										}
									@endphp
								</center>
							</td>
						</tr>
						@php
								$no++;
							}
						@endphp
					@endforeach
					<tr>
						<th colspan="9"></th>
						<th colspan="2">Subtotal</th>
						<th colspan="1">{{ number_format($totaltile, $koma, ',', '.') }}</th>
					</tr>
				</tbody>
				</table>
				@php
				}
				if($adalain == true){
				@endphp
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
					@php
						if($adatile == true){
					@endphp
						<tr style="background:#ebb220;text-align:center;">
							<th style="color:white;" colspan="12"><center>NON-TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>CODE</center></th>
						<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
						<th style="color:white;" rowspan="2"><center>BRAND</center></th>
						<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
						<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
						<th style="color:white;" rowspan="2"><center>COLOR</center></th>
						<th style="color:white;" rowspan="2"><center>SPEC</center></th>
						<th style="color:white;" rowspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>PRICE (BEFORE TAX)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" colspan="2">PCS</th>
					</th>
				<tbody>
					@foreach($project->projectSaleProduct()->orderBy('id')->get() as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								$total += ($pp->best_price * $pp->qty) / $project->currency_rate;
								$totallain += ($pp->best_price * $pp->qty) / $project->currency_rate;
						@endphp
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $pp->product->type->image() }}" style="max-width:15px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->brand->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->category->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->color->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->spec }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->qty }}
								</center>
							</td>
							<td style="vertical-align:center;" colspan="2">
								<center>
									{{ number_format($pp->best_price / $project->currency_rate, $koma, ',', '.') }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ number_format(($pp->best_price * $pp->qty) / $project->currency_rate, $koma, ',', '.') }}
								</center>
							</td>
						</tr>
						@php
								$no++;
							}
						@endphp
					@endforeach
					<tr>
						<th colspan="9"></th>
						<th colspan="2">Subtotal</th>
						<th colspan="1">{{ number_format($totallain, $koma, ',', '.') }}</th>
					</tr>
				</tbody>
				</table>
				@php
				}
				@endphp
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<table cellpadding="2" cellspacing="0" border="1" style="background-color:#ebb220;font-size:14px;color:white;">
							<tr>
								<td>
									<b>GRANDTOTAL</b>
								</td>
								<td style="text-align:center;">
									<b>IDR {{ $project->project->ppn == '1' ? number_format(($total - ($project->project->discount / $project->currency_rate)) + ($persenppn * ($total - ($project->project->discount / $project->currency_rate))), $koma, ',', '.') : number_format($total - ($project->project->discount / $project->currency_rate), $koma, ',', '.') }}
									</b>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<b>Nominal in words : {{ $project->project->ppn == '1' ? App\Helper\SMB::say(round(($total - ($project->project->discount / $project->currency_rate)) + ($persenppn * ($total - ($project->project->discount / $project->currency_rate))),$koma)) : App\Helper\SMB::say(round($total - ($project->project->discount / $project->currency_rate),$koma)) }}
									</b>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									PAYMENT WILL BE TRANSFERRED TO :
									@if($project->sales->branch == '2')
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
							<tr>
								<td colspan="2">
									<div>Note :
									<br>{{ $project->note }}
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td width="5%"></td>
					<td width="45%">
						<table cellpadding="2" cellspacing="0" border="1">
							<tr>
								<td>
									<h6>SUBTOTAL</h6>
								</td>
								<td>
									<h6>{{ $project->currency->code }} {{ number_format($total, $koma, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>DISCOUNT</h6>
								</td>
								<td>
									<h6>{{ $project->currency->code }} {{ number_format($project->project->discount / $project->currency_rate, $koma, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TOTAL AFTER DISCOUNT</h6>
								</td>
								<td>
									<h6>{{ $project->currency->code }} {{ number_format(($project->project->discount ? ($total - ($project->project->discount / $project->currency_rate)) : $total), $koma, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TAX</h6>
								</td>
								<td>
									<h6>{{ $project->currency->code }} {{ $project->project->ppn == '1' ? number_format($persenppn * ($total - ($project->project->discount / $project->currency_rate)), $koma, ',', '.') : 0 }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>GRANDTOTAL</h6>
								</td>
								<td>
									<h6><b>{{ $project->currency->code }} {{ $project->project->ppn == '1' ? number_format(($total - ($project->project->discount / $project->currency_rate)) + ($persenppn * ($total - ($project->project->discount / $project->currency_rate))), $koma, ',', '.') : number_format(($total - ($project->project->discount / $project->currency_rate)), $koma, ',', '.') }}</h6>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				
			</table><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					@if(isset($project->approved->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Acknowledged By,</div>
						@if(isset($project->approved->sign))
							<div><img src="{{ url(Storage::url($project->approved->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->approved->name }}</div>
						<div style="font-size:10px;">{{ $project->approved->userRole->first()->role() }}</div>
					</td>
					@endif
					@if(isset($project->marketing->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Marketing,</div>
						@if(isset($project->marketing->sign))
							<div><img src="{{ url(Storage::url($project->marketing->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->marketing->name }}</div>
						<div style="font-size:10px;">{{ $project->marketing->userRole->first()->role() }}</div>
					</td>
					@endif
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Sales Person</div>
						@if(isset($project->sales->sign))
							<div><img src="{{ url(Storage::url($project->sales->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->sales->name }}</div>
						<div style="font-size:10px;">{{ $project->sales->userRole->first()->role() }}</div>
					</td>
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Created By</div>
						@if(isset($project->user->sign))
							<div><img src="{{ url(Storage::url($project->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->user->name }}</div>
						<div style="font-size:10px;">{{ $project->user->userRole->first()->role() }}</div>
					</td>
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
		@foreach($project->projectSalePay as $pay)
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
							<tr style="background-color:#ebb220;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>SALES INVOICE</b></h3>
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
								<td width="20%" style="font-size:12px;">INVOICE</td>
								<td style="text-align:left; font-size:12px;">: {{ $pay->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PIC</td>
								<td style="text-align:left; font-size:12px;">: {{ $pay->projectSale->project->manager }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">CUSTOMER</td>
								<td style="text-align:left; font-size:12px;">: {{ $pay->projectSale->project->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">ADDRESS</td>
								<td style="text-align:left; font-size:12px;">: {{ $pay->projectSale->project->customer->address }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">DATE</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($pay->date)) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">SALES</td>
								<td style="text-align:left; font-size:12px;">: {{ $pay->projectSale->sales->name }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">SALES NO.</td>
								<td style="text-align:left; font-size:12px;">: {{ $pay->projectSale->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PHONE</td>
								<td style="text-align:left; font-size:12px;">: {{ $pay->projectSale->project->customer->phone }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">SO Date</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($pay->projectSale->created_at)) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PROFORMA CODE</td>
								<td style="text-align:left; font-size:12px;">: {{ isset($pay->projectDelivery->proforma_code) ? $pay->projectDelivery->proforma_code : '-' }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			@php
				if(date('Y-m-d',strtotime($pay->projectSale->created_at)) < '2022-04-01'){
					$persenppn = 0.1;
				}else{
					$persenppn = 0.11;
				}
				
				$adatile = false;
				$adalain = false;
				$total = 0;
				$totaltile = 0;
				$totallain = 0;
				$totalpaid = 0;
				
				foreach($pay->projectSale->projectSalePay as $key => $psp){
					$totalpaid += $psp->nominal;
				}
				
				foreach($pay->projectSale->projectSaleProduct as $key => $pp){
					if($pp->unit == '2' || $pp->unit == '3'){
						$adatile = true;
					}
					if($pp->unit == '1' || $pp->unit == '4'){
						$adalain = true;
					}
				}
			@endphp

			
				@php
				if($adatile == true){
				@endphp
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
					@php
						if($adalain == true){
					@endphp
						<tr style="background:#ebb220;text-align:center;">
							<th style="color:white;" colspan="12"><center>TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>CODE</center></th>
						<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
						<th style="color:white;" rowspan="2"><center>BRAND</center></th>
						<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
						<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
						<th style="color:white;" rowspan="2"><center>COLOR</center></th>
						<th style="color:white;" colspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>PRICE (BEFORE TAX)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
					</th>
				<tbody>
					@foreach($pay->projectSale->projectSaleProduct as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
								
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$countbox = $pp->qty;
									$total += $pp->best_price * ($countbox);
									$totaltile += $pp->best_price * ($countbox);
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$countbox = $pp->qty;
										$total += $pp->best_price * ($countbox);
										$totaltile += $pp->best_price * ($countbox);
									}else{
										$countbox = ceil(round($pp->qty / $m2,2));
										$total += $pp->best_price * $m2 * ($countbox);
										$totaltile += $pp->best_price * $m2 * ($countbox);
									}
								}
								
						@endphp
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $key + 1 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $pp->product->type->image() }}" style="max-width:28px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->brand->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->category->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->color->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->qty }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php 
										//echo $countbox - $pp->getCountReturn();
										echo $countbox;
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ number_format($pp->best_price, 0, ',', '.') }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format($pp->best_price, 0, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format($pp->best_price, 0, ',', '.');
											}else{
												echo number_format($pp->best_price * $m2, 0, ',', '.');
											}
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php 
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format($pp->best_price * ($countbox), 0, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format($pp->best_price * ($countbox), 0, ',', '.');
											}else{
												echo number_format($pp->best_price * $m2 * ($countbox), 0, ',', '.');
											}
										}
									@endphp
								</center>
							</td>
						</tr>
						@php
							}
						@endphp
					@endforeach
					<tr>
						<th colspan="9"></th>
						<th colspan="2">Subtotal</th>
						<th colspan="1">{{ number_format($totaltile, 0, ',', '.') }}</th>
					</tr>
				</tbody>
				</table>
				@php
				}
				if($adalain == true){
				@endphp
				<br>
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
					@php
						if($adatile == true){
					@endphp
						<tr style="background:#ebb220;text-align:center;">
							<th style="color:white;" colspan="12"><center>NON-TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>CODE</center></th>
						<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
						<th style="color:white;" rowspan="2"><center>BRAND</center></th>
						<th style="color:white;" rowspan="2"><center>SIZE(mm)</center></th>
						<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
						<th style="color:white;" rowspan="2"><center>COLOR</center></th>
						<th style="color:white;" rowspan="2"><center>SPEC</center></th>
						<th style="color:white;" rowspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>PRICE (BEFORE TAX)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" colspan="2">PCS</th>
					</th>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($pay->projectSale->projectSaleProduct as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								$total += $pp->best_price * $pp->qty;
								$totallain += $pp->best_price * $pp->qty;
						@endphp
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $pp->product->type->image() }}" style="max-width:28px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->brand->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->category->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->color->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->spec }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->qty - $pp->getCountReturn() }}
								</center>
							</td>
							<td style="vertical-align:center;" colspan="2">
								<center>
									{{ number_format($pp->best_price, 0, ',', '.') }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ number_format($pp->best_price * ($pp->qty - $pp->getCountReturn()), 0, ',', '.') }}
								</center>
							</td>
						</tr>
						@php
								$no++;
							}
						@endphp
					@endforeach
					<tr>
						<th colspan="9"></th>
						<th colspan="2">Subtotal</th>
						<th colspan="1">{{ number_format($totallain, 0, ',', '.') }}</th>
					</tr>
				</tbody>
				</table>
				@php
				}
				@endphp
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="45%">
						<table cellpadding="2" cellspacing="0" border="1" style="width:100% !important;">
							<tr>
								<td>
									<div style="font-size:12px;">Note :
									<br>{{ $pay->projectSale->note }}
									</div>
								</td>
							</tr>
							<tr>
								<td>
									PAYMENT WILL BE TRANSFERRED TO :
									@if($pay->projectSale->sales->branch == '2')
										@if($pay->project->ppn == '1')
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
										@if($pay->project->ppn == '1')
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
						</table>
					</td>
					<td width="10%"></td>
					<td width="45%">
						<table cellpadding="2" cellspacing="0" border="1">
							<tr>
								<td>
									<h6>SUBTOTAL</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($total, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>DISCOUNT</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($pay->project->discount, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>SUBTOTAL AFTER DISCOUNT</h6>
								</td>
								<td>
									<h6>IDR {{ number_format(($pay->project->discount ? ($total - $pay->project->discount) : $total), 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TAX PRODUCT</h6>
								</td>
								<td>
									<h6>IDR {{ $pay->projectSale->project->ppn == '1' ? number_format($persenppn * ($total - $pay->project->discount), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TOTAL PRODUCT</h6>
								</td>
								<td>
									<h6>IDR {{ $pay->projectSale->project->ppn == '1' ? number_format(($total - $pay->project->discount) + ($persenppn * ($total  - $pay->project->discount)), 0, ',', '.') : number_format($total - $pay->project->discount, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>DELIVERY COST</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($pay->projectSale->delivery_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>CUTTING COST</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($pay->projectSale->cutting_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>MISCELLANEOUS COST</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($pay->projectSale->misc_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TOTAL COST</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($pay->projectSale->delivery_cost + $pay->projectSale->cutting_cost + $pay->projectSale->misc_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TAX COST</h6>
								</td>
								<td>
									<h6>IDR {{ $pay->projectSale->ppn_cost == '1' ? number_format($persenppn * ($pay->projectSale->delivery_cost + $pay->projectSale->cutting_cost + $pay->projectSale->misc_cost), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@php
								$grandtotal = 0;
								
								if($pay->projectSale->project->ppn == '1'){
									$grandtotal += ($total - $pay->project->discount) + ($persenppn * ($total - $pay->project->discount));
								}else{
									$grandtotal += ($total - $pay->project->discount);
								}
								
								if($pay->projectSale->ppn_cost == '1'){
									$grandtotal += $persenppn * ($pay->projectSale->delivery_cost + $pay->projectSale->cutting_cost + $pay->projectSale->misc_cost);
								}else{
									$grandtotal += $pay->projectSale->delivery_cost + $pay->projectSale->cutting_cost + $pay->projectSale->misc_cost;
								}
							@endphp
							<tr>
								<td>
									<h6>GRANDTOTAL</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($grandtotal,0,',','.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>PAID</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($totalpaid, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@if($grandtotal - $totalpaid >= 0)
							<tr>
								<td>
									<h6>UNDERPAYMENT</h6>
								</td>
								<td>
									<h6>IDR {{ $grandtotal - $totalpaid < 0 ? '0' : number_format($grandtotal - $totalpaid, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@else
							<tr>
								<td>
									<h6>OVERPAYMENT</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($totalpaid - $grandtotal, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@endif
							<tr>
								<td>
									<h6>NOMINAL IN WORDS</h6>
								</td>
								<td>
									<h6>{{ $grandtotal - $totalpaid > 0 ? App\Helper\SMB::say(round($grandtotal - $totalpaid)) : 'Zero' }}</h6>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						
					</td>
				</tr>
			</table>
			<br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:12px;">
				<thead>
					<tr style="background:#c92089;text-align:center;">
						<th style="color:white;" colspan="7"><center>List Payments</center></th>
					</tr>
					<tr style="background:#c92089;text-align:center;">
						<th style="color:white;"><center>No.</center></th>
						<th style="color:white;"><center>Invoice</center></th>
						<th style="color:white;"><center>Date</center></th>
						<th style="color:white;"><center>Payment</center></th>
						<th style="color:white;"><center>To</center></th>
						<th style="color:white;"><center>Note</center></th>
						<th style="color:white;"><center>Nominal</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($pay->projectSale->projectSalePay as $key => $psp)
						@php
							$style = '';
							if($pay->id == $psp->id){
								$style = 'background:#65f075;';
							}
						@endphp
						<tr style="{{ $style }}">
							<td><center>{{ $key + 1 }}</center></td>
							<td><center>{{ $psp->code }}</center></td>
							<td><center>{{ date('d F Y', strtotime($psp->date)) }}</center></td>
							<td><center>{{ $psp->giro() }}</center></td>
							<td><center>{{ $psp->coa->name }}</center></td>
							<td><center>{{ $psp->note }}</center></td>
							<td><center>IDR {{ number_format($psp->nominal,2,',','.') }}</center></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					@if(isset($pay->approved->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Acknowledged By,</div>
						@if(isset($pay->approved->sign))
							<div><img src="{{ url(Storage::url($pay->approved->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $pay->approved->name }}</div>
					</td>
					@endif
					@if(isset($pay->marketing->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Marketing,</div>
						@if(isset($pay->marketing->sign))
							<div><img src="{{ url(Storage::url($pay->marketing->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $pay->marketing->name }}</div>
					</td>
					@endif
					@if(isset($pay->check->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Checked By,</div>
						@if(isset($pay->check->sign))
							<div><img src="{{ url(Storage::url($pay->check->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $pay->check->name }}</div>
					</td>
					@endif
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Created By</div>
						@if(isset($pay->user->sign))
							<div><img src="{{ url(Storage::url($pay->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $pay->user->name }}</div>
					</td>
				</tr>
			</table>
		</div>
		@endforeach
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
								<td style="text-align:left;">: {{ $project->project->customer->name }}</td>
							</tr>
							<tr>
								<td width="40%">SO No.</td>
								<td style="text-align:left;">: {{ $project->code }}</td>
							</tr>
							<tr>
								<td>Total Penjualan</td>
								<td style="text-align:left;">: Rp. {{ $project->getTotalRaw() }} (Exclude PPN {{ $persenppn }}%)</td>
							</tr>
							<tr>
								<td>Fee Konsultan</td>
								<td style="text-align:left;">: {{ $project->mid_type == '1' ? $project->mid_fee.' % From Total Product Cost (Before Tax)' : 'Rp. '.number_format($project->mid_fee,0,',','.').' per Unit Product Type' }}</td>
							</tr>
							<tr>
								<td>Nominal Fee</td>
								<td style="text-align:left;">: Rp. {{ $project->getTotalMiddleman() }}</td>
							</tr>
							<tr>
								<td>No REKENING</td>
								<td style="text-align:left;">: {{ $project->mid_note }}</td>
							</tr>
						</table>
						<br>
						dengan ini saya PIC PT. Perwira Tamaraya Abadi yang bertanggung jawab di sini:
						<br>
						@if($project->sales->branch == '1')
							<table width="100%">
								<tr>
									<td width="20%">Nama</td>
									<td style="text-align:left;">: {{ $project->user->name }}</td>
								</tr>
								<tr>
									<td>Jabatan</td>
									<td style="text-align:left;">: Sales</td>
								</tr>
							</table>
						@else
							<table width="100%">
								<tr>
									<td width="20%">Nama</td>
									<td style="text-align:left;">: Antonius Trijanto</td>
								</tr>
								<tr>
									<td>Jabatan</td>
									<td style="text-align:left;">: Sales & Marketing Manager</td>
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
		
		@foreach($project->projectDelivery->whereNotNull('received_date') as $rowproject)
			<div class="invoice-box">
				<img src="{{ $rowproject->attachment() }}" style="margin:auto !important;">
			</div>
		@endforeach
	</body>
</html>