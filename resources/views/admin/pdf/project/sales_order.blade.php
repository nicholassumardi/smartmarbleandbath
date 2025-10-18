<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sales Order {{ $project->code }}</title>
		@php
	    	$color = '#ebb220';
			$margin = '1cm';
			$margin_top = '0cm';
			$margin_bottom = '0cm';
			$bg_image = '';

			if($project->letter_head == '4'){
				$color = '#C59B6D';
				$margin = '2cm';
				$margin_top = '4cm';
				$margin_bottom = '3.5cm';
				$bg_image = 'website/letterhead_bq.png';
			}
		@endphp
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			
			td {
				font-size: 20pt;
				font-weight: bold;
			}
			
			th {
				font-size:18pt;
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
				background: {{ $color }};
				border-bottom: 1px solid {{ $color }};
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
			
			.table-notes tr td{
				font-size : 13pt !important;
			}
			
	    	@page { 
				margin: {{$margin}}; 
			    margin-top:{{$margin_top}};
			    margin-bottom:{{$margin_bottom}};
			    background:url({{$bg_image}}) no-repeat 0 0;
                background-image-resize: 5;
			 }
			body { margin: 1cm; }
		</style>
	</head>
	<body>
		<div class="invoice-box">
			@if($project->letter_head == 1 || $project->letter_head == NULL)
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
								<td style="padding-right:10px;padding-top:100px !important;">
									<div style="font-size:9px; font-weight:bold;">PERGUDANGAN KOSAMBI PERMAI </div>
									<div style="font-size:9px; font-weight:500;">Jalan Raya Perancis Blok E-6 Jati Mulya, Dadap, Tangerang</div>
									<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
									<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com</div>
								</td>
				                <td style="border-left: 2px solid #51b6bc; text-align:right;padding-top:100px !important; padding-left:10px;">
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
			@else
				{!!App\Helper\SMB::letterHead($project->letter_head)!!}
			@endif
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr style="background-color:{{ $color }};">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px; font-size:12pt;">
									<h5><b>SALES ORDER</b></h5>
									<h5><b>(PRODUCT)</b></h5>
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
							<tr>
								<td width="20%" style="font-size:12px;">PHONE</td>
								<td style="text-align:left; font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->project->customer->phone }}</td>
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
				$adacustom = false;
				$total = 0;
				$totaltile = 0;
				$totallain = 0;
				
				$totalqtybox = 0;
				$totalqtytile = 0;
				$totalqtylain = 0;
				
				foreach($project->projectSaleProduct as $key => $pp){
					if($pp->unit == '2' || $pp->unit == '3'){
						$adatile = true;
					}
					if($pp->unit == '1' || $pp->unit == '4'){
						$adalain = true;
					    $adacustom = ($pp->unit == '4') ? true : false;
					}
				}
			@endphp

			
				@php
				$no = 1;
				if($adatile == true){
				@endphp
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead style="display: table-header-group !important;">
					@php
						if($adalain == true){
					@endphp
						<tr style="background:{{ $color }};text-align:center;">
							<th style="color:white;" colspan="14"><center>TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:{{ $color }};text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>AREA</center></th>
						<th style="color:white;" rowspan="2"><center>CODE</center></th>
						<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
						<th style="color:white;" rowspan="2"><center>BRAND</center></th>
						<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
						<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
						<th style="color:white;" rowspan="2"><center>COLOR</center></th>
						<th style="color:white;" rowspan="2"><center>WEIGHT<br>(kg)</center></th>
						<th style="color:white;" colspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>PRICE (BEFORE TAX)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:{{ $color }};text-align:center;">
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
					</th>
				</thead>
				<tbody>
					@foreach($project->projectSaleProduct()->orderBy('id')->get() as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
								
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$countbox = ceil($pp->qty);
									$total += ($pp->best_price * $countbox) / $project->currency_rate;
									$totaltile += ($pp->best_price * $countbox) / $project->currency_rate;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$countbox = ceil($pp->qty);
										$total += ($pp->best_price * $countbox) / $project->currency_rate;
										$totaltile += ($pp->best_price * $countbox) / $project->currency_rate;
									}else{
										$countbox = ceil(round($pp->qty / $m2,2));
										$total += (round($pp->best_price * $m2, 0) * $countbox) / $project->currency_rate;
										$totaltile += (round($pp->best_price * $m2, 0) * $countbox) / $project->currency_rate;
									}
								}
								
								$totalqtybox += $countbox;
								$totalqtytile += $pp->qty;
						@endphp
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
								@foreach ($project->project->projectProduct as $rowpp)
									@if ($rowpp->product_id == $pp->product_id) 
										<center>
										{{ $rowpp->area ?  $rowpp->area : '' }}
										</center>
									@endif
								@endforeach
							</td>
					
							<td style="vertical-align:center; font-size:25pt; word-wrap:break;">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $pp->product->type->image() }}" style="max-width:160px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
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
									{{ $pp->product->type->weight }}
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
											echo number_format($pp->best_price / $project->currency_rate, $koma, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format((($pp->best_price * $countbox) / ($countbox * $m2)) / $project->currency_rate, $koma, ',', '.');
											}else{
												echo number_format(((round($pp->best_price * $m2, 0) * $countbox) / ($countbox * $m2)) / $project->currency_rate, $koma, ',', '.');
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
												echo number_format((round($pp->best_price * $m2, 0)) / $project->currency_rate, $koma, ',', '.');
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
												echo number_format((round((round($pp->best_price * $m2, 0)), 0) * $countbox) / $project->currency_rate, $koma, ',', '.');
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
						<td style="vertical-align:center;text-align:right;" colspan="9">
							Total Qty
						</td>
						<td style="vertical-align:center;text-align:center;">{{ $totalqtytile }}</td>
						<td style="vertical-align:center;text-align:center;">{{ $totalqtybox }}</td>
						<th colspan="2">Subtotal</th>
						<th colspan="1">{{ number_format($totaltile, $koma, ',', '.') }}</th>
					</tr>
				</tbody>
				</table>
				@php
				}
				if($adalain == true){
				@endphp
				<br>
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
			    <thead style="display: table-header-group !important;">
					@php
						if($adatile == true){
					@endphp
						<tr style="background:{{ $color }};text-align:center;">
							<th style="color:white;" colspan="14"><center>NON-TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:{{ $color }};text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>AREA</center></th>
						<th style="color:white;" rowspan="2"><center>CODE</center></th>
						<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
						<th style="color:white;" rowspan="2"><center>BRAND</center></th>
						<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
						<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
						<th style="color:white;" rowspan="2"><center>COLOR</center></th>
						<th style="color:white;" rowspan="2"><center>WEIGHT<br>(kg)</center></th>
						<th style="color:white;" rowspan="2"><center>SPEC</center></th>
						<th style="color:white;" rowspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>PRICE (BEFORE TAX)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:{{ $color }};text-align:center;">
						<th style="color:white;" colspan="2">{{$adacustom == true ? 'Unit' : 'PCS'}}</th>
					</th>
			    </thead>
				<tbody>
					@foreach($project->projectSaleProduct()->orderBy('id')->get() as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								$total += ($pp->best_price * $pp->qty) / $project->currency_rate;
								$totallain += ($pp->best_price * $pp->qty) / $project->currency_rate;
								$totalqtylain += $pp->qty;
						@endphp
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
							@foreach ($project->project->projectProduct as $rowpp)
								@if ($rowpp->product_id == $pp->product_id) 
									<center>
									{{ $rowpp->area }}
									</center>
								@endif
							@endforeach
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $pp->product->type->image() }}" style="max-width:140px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
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
									{{ $pp->product->type->weight }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->spec }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->qty }} {{ $adacustom == true ? $pp->unit() : ''}}
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
						<td colspan="10" style="text-align:right;">Total Qty</td>
						<td style="text-align:center;">{{ $totalqtylain }}</td>
						<th colspan="2">Subtotal</th>
						<th colspan="1">{{ number_format($totallain, $koma, ',', '.') }}</th>
					</tr>
				</tbody>
				</table>
				@php
				}
				@endphp
			<br>
			<table cellpadding="0" cellspacing="0" class="table-notes">
				<tr>
					<td width="50%">
						<table cellpadding="2" cellspacing="0" border="1" style="background-color:{{ $color }};font-size:14px;color:white;">
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
											@if($project->project->coa_id = 397)
											<p>
												<br>Mandiri 14.000.23.000.673
												<br>a/n Prawiro Tedjo Tjandra
												<br>Cab. Kembang Jepun - Surabaya<br>
											</p>
											@else
											<p>
												<br>BCA 329.0258.510
												<br>a/n Andy Hidayat
												<br>Cab. Baliwerti<br>
											</p>
											@endif
										@endif
									@else
										@if($project->project->coa_id == 380)
											<p>
												<br>BCA 3293-678-789
												<br>a/n PT. Perwira Sejati Internusa
												<br>Cab. Baliwerti - Surabaya<br>
											</p>
										@elseif($project->project->ppn == '1')
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
									<h6>({{ $total == 0 ? 0 : number_format((($project->project->discount / $project->currency_rate) / $total ) * 100,2,',','.') }}%) {{ $project->currency->code }} {{ number_format($project->project->discount / $project->currency_rate, $koma, ',', '.') }}</h6>
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
	</body>
</html>