@php
if(date('Y-m-d',strtotime($sample->created_at)) < '2022-04-01' ){ $persenppn=0.1; $ppnpembagi=1.1; }else{
	$persenppn=0.11; $ppnpembagi=1.11; } @endphp <!doctype html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Purchase Order {{ $sample->code }}</title>
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}

			th {
				font-size: 12px;
			}

			.invoice-box {
				font-size: 16px;
				font-family: 'Lato', sans-serif;
				color: #555;
				@if($sample->currency_id !=='5') page-break-after: always;
				@endif
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

			@if($sample->currency_id !==5) .separate-box {
				height: 100%;
			}

			.separate-box table tr td {
				padding: 15px;
			}

			.separate-box #table-kwitansi {
				border: 1px solid black;
			}

			@endif @page {
				margin: 1cm;
			}

			body {
				margin: 1cm;
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
								<td colspan="2" style="text-align:right;padding-bottom:15px;"><img
										src="{{ url('website/pta_new_logo.png') }}" height="30px"
										style="margin-right:5px;"></td>
							</tr>
							<tr>
								<td style="padding-right:25px;padding-top:100px !important;">
									<div style="font-size:9px; font-weight:bold;">JAGAT BUILDING</div>
									<div style="font-size:9px; font-weight:500;">Tomang Raya No 28 - 30, Jakarta 11430
									</div>
									<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
									<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com
									</div>
								</td>
								<td
									style="border-left: 3px solid #51b6bc; text-align:right;padding-top:100px !important;">
									<div style="font-size:9px; font-weight:bold;">MODERN CERAMIC</div>
									<div style="font-size:9px; font-weight:500;">Baliwerti 119 - 121, Surabaya 60174
									</div>
									<div style="font-size:9px; font-weight:500;">Phone : 031-5472860 / 031-5324505</div>
									<div style="font-size:9px; font-weight:500;">Email : info@smartmarbleandbath.com
									</div>
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
					<td>
						<table>
							<tr>
								<td width="55%">
									<table style="border: 1px solid #0b95b8;border-collapse: collapse;">
										<tr>
											<td
												style="background-color:#0b95b8;text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
												<h3><b>PURCHASE ORDER</b></h3>
											</td>
										</tr>
									</table>

								</td>
								<td width="5%">

								</td>
								<td width="40%" rowspan="6">
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="40%" style="font-size:10px;">Order from Buyer</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>PT PERWIRA TAMARAYA ABADI
													<br>
													PERGUD. BUMI MASPION IX/E-1, ROMOKALISARI,<br>
													BENOWO, SURABAYA, 60195, INDONESIA</b>
											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Telp/Fax.</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>031-547 2860/031-547
													8924</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Taxpayer Id No.</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>02.458.040.9-604.000</b>
											</td>
										</tr>
									</table>
									<br>
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="40%" style="font-size:10px;">Delivered To</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>{{ $sample->on_behalf
													}}</b> </td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Address</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>{{
													$sample->delivery_address }}</b> </td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Courier Method</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;">
												<b>{{ $sample->courier_method }}</b>

											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Destination</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;">
												<b>{{ $sample->city->name.', '.$sample->country->name }}</b>

											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">PIC</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;">
												<b>{{ $sample->pic }}</b>
											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">PIC Phone No.</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;">
												<b>{{ $sample->pic_no }}</b>
											</td>
										</tr>
									</table>
									<br>
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="40%" style="font-size:10px;">Payment Method</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>{{ $sample->payment_method
													}} days</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Price</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>{{ $sample->price() }}</b>
											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Currency</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>{{
													$sample->currency->code.' '.$sample->currency->name }}</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Brand on Box</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>{{ $sample->brand_on_box
													}}</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">SNI No.</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>{{ $sample->sni }}</b></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="55%">
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="30%" style="font-size:10px;">Date of PO</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ date('d F Y',
													strtotime($sample->created_at)) }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">PO No.</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->code }}</b>
											</td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Ref SO No.</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->sample ?
													$sample->sample->code : '-' }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Sales Person</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->sales ?
													$sample->sales->name : '-' }}</b></td>
										</tr>
									</table>
									<br>
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr class="heading">
											<td colspan="2">
												<div style="font-size:10px;"><b>Order To Supplier :</b></div>
											</td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Supplier</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->supplier->name }} </b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Address</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->supplier->address }} </b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">PIC</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->supplier->pic
													}}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">PIC Phone No.</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->supplier->phone }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Production Lead Time</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->production_lead_time }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Est. Delivery Time</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->estimated_delivery }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Est. Arrival Time</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->estimated_arrival }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Factory Name</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->factory_name
													}}</b></td>
										</tr>
									</table>
								</td>
								<td width="5%">

								</td>
							</tr>
							<tr>
								<td width="55%">


								</td>
								<td width="5%"></td>
							</tr>
							<tr>
								<td width="55%"></td>
								<td width="5%"></td>
							</tr>
							<tr>
								<td width="55%"></td>
								<td width="5%"></td>
							</tr>
							<tr>
								<td width="55%"></td>
								<td width="5%"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<p style="font-size:10px;">Our dear Supplier,
				<br>We would like to place an order with details as follows:
			</p>
			@php

			$adatile = false;
			$adalain = false;
			$total = 0;
			$totaltile = 0;
			$totalqtytile = 0;
			$totalqtybox = 0;
			$totalqtylain = 0;
			$totallain = 0;
			foreach($sample->samplePurchaseProduct as $key => $pp){
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
			$qtytot = 0;
			$qtym2 = 0;
			@endphp
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;" colspan="13">
							<center>DETAIL OF PURCHASE ORDER</center>
						</th>
					</tr>
				</thead>
				<thead>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;" colspan="13">
							<center>TILES</center>
						</th>
					</tr>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;">
							<center>NO</center>
						</th>
						<th style="color:white;">
							<center>CODE</center>
						</th>
						<th style="color:white;">
							<center>PRODUCT</center>
						</th>
						<th style="color:white;">
							<center>SIZE(cm)</center>
						</th>
						<th style="color:white;">
							<center>CATEGORY</center>
						</th>
						<th style="color:white;">
							<center>COLOR</center>
						</th>
						<th style="color:white;">
							<center>SHADE</center>
						</th>
						<th style="color:white;">
							<center>FINISH</center>
						</th>
						<th style="color:white;">
							<center>TOTAL (box)</center>
						</th>
						<th style="color:white;">
							<center>TOTAL (sqm)</center>
						</th>
						<th style="color:white;">
							<center>PRICE/M<sup>2</sup></center>
						</th>
						<th style="color:white;">
							<center>TOTAL</center>
						</th>
						<th style="color:white;">
							<center>CONT</center>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->samplePurchaseProduct as $key => $pp)
					@php
					if($pp->unit == '2' || $pp->unit == '3'){
					$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) *
					$pp->product->carton_pcs;
					if($sample->ppn == '1'){
					if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
						$total += ($pp->price / $ppnpembagi) * $pp->qty;
						$totaltile += ($pp->price / $ppnpembagi) * $pp->qty;
						}else{
						if($m2 < 1.1 && date('Y-m',strtotime($sample->created_at)) < '2022-06' && $pp->
								product->type->category->parent()->id == 18){
								$total += ($pp->price / $ppnpembagi) * $pp->qty;
								$totaltile += ($pp->price / $ppnpembagi) * $pp->qty;
								}else{
								$total += ($pp->price / $ppnpembagi) * $pp->qty * $m2;
								$totaltile += ($pp->price / $ppnpembagi) * $m2 * $pp->qty;
								}
								}

								$qtytot += $pp->qty;
								$qtym2 += $pp->qty*$m2;
								}else{
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$total += $pp->price * $pp->qty;
									$totaltile += $pp->price * $pp->qty;
									}else{
									if($m2 < 1.1 && date('Y-m',strtotime($sample->created_at)) < '2022-06' && $pp->
											product->type->category->parent()->id == 18){
											$total += $pp->price * $pp->qty;
											$totaltile += $pp->price * $pp->qty;
											}else{
											$total += $pp->price * $pp->qty * $m2;
											$totaltile += $pp->price * $m2 * $pp->qty;
											}
											}

											$qtytot += $pp->qty;
											$qtym2 += $pp->qty*$m2;
											}

											$totalqtytile += $pp->qty*$m2;
											$totalqtybox += $pp->qty;
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
														<img src="{{ $pp->product->type->image() }}"
															style="max-width:10px; border:1px solid #ddd; border-radius:4px; padding: 5px;"
															class="img-fluid img-thumbnail">
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->type->category->name }} <br>
														{{ $pp->product->hsCode->name }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->type->color->name }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center style="font-size:8px;">
														{{ $pp->remark }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->type->surface->name }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ number_format($pp->qty,2,',','.') }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ number_format($pp->qty*$m2,2,',','.') }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $sample->currency_id == 5 ? 'Rp' :
														$sample->currency->symbol }} {{ number_format( $sample->ppn ==
														'1' ? $pp->price / $ppnpembagi : $pp->price, 2, ',', '.') }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														@if($m2 < 1.1 && $pp->product->type->category->parent()->id !==
															18)
															{{ $sample->currency_id == 5 ? 'Rp' :
															$sample->currency->symbol }} {{ number_format($sample->ppn
															== '1' ? ($pp->price / $ppnpembagi) * $pp->qty : $pp->price
															* $pp->qty, 0, ',', '.') }}
															@else
															@if($m2 < 1.1 && date('Y-m',strtotime($sample->created_at))
																< '2022-06' && $pp->
																	product->type->category->parent()->id == 18)
																	{{ $sample->currency_id == 5 ? 'Rp' :
																	$sample->currency->symbol }} {{
																	number_format($sample->ppn == '1' ? ($pp->price /
																	$ppnpembagi) * $pp->qty : $pp->price * $pp->qty, 0,
																	',', '.') }}
																	@else
																	{{ $sample->currency_id == 5 ? 'Rp' :
																	$sample->currency->symbol }} {{
																	number_format($sample->ppn == '1' ? ($pp->price /
																	$ppnpembagi) * $pp->qty * $m2 : $pp->price *
																	$pp->qty * $m2, 0, ',', '.') }}
																	@endif
																	@endif
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->containerStandart() }}
													</center>
												</td>
											</tr>
											@php
											$no++;
											}
											@endphp
											@endforeach
											<tr>
												<td colspan="8" style="text-align:right;">Total Qty</td>
												<td style="text-align:center;">{{ $totalqtybox }}</td>
												<td style="text-align:center;">{{ $totalqtytile }}</td>
												<td style="text-align:right;">Total</td>
												<td colspan="3" style="text-align:right;">{{
													$sample->currency->symbol.' '.number_format($totaltile, 2, ',',
													'.') }}</td>
											</tr>
											<tr>
												<td colspan="10" style="text-align:right;">Tax</td>
												<td colspan="3" style="text-align:right;">{{ $sample->ppn == '1' ?
													$sample->currency->symbol.' '.number_format($totaltile *
													$persenppn, 0, ',', '.') : $sample->currency->symbol.'
													'.number_format(0, 2, ',', '.') }}</td>
											</tr>
											<tr>
												<td colspan="10" style="text-align:right;">Grandtotal</td>
												<td colspan="3" style="text-align:right;">{{ $sample->ppn == '1' ?
													$sample->currency->symbol.' '.number_format( $totaltile +
													($totaltile * $persenppn), 0, ',', '.') :
													$sample->currency->symbol.' '.number_format($totaltile, 2, ',',
													'.') }}</td>
											</tr>
				</tbody>
			</table>
			@php
			}
			if($adalain == true){
			$qtytot = 0;
			@endphp
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;" colspan="13">
							<center>NON-TILES</center>
						</th>
					</tr>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;">
							<center>NO</center>
						</th>
						<th style="color:white;">
							<center>CODE</center>
						</th>
						<th style="color:white;">
							<center>PRODUCT</center>
						</th>
						<th style="color:white;">
							<center>SIZE(cm)</center>
						</th>
						<th style="color:white;">
							<center>CATEGORY</center>
						</th>
						<th style="color:white;">
							<center>COLOR</center>
						</th>
						<th style="color:white;">
							<center>SHADE</center>
						</th>
						<th style="color:white;">
							<center>FINISH</center>
						</th>
						<th style="color:white;">
							<center>TOTAL(pcs)</center>
						</th>
						<th style="color:white;">
							<center>PRICE/PCS</center>
						</th>
						<th style="color:white;" colspan="2">
							<center>TOTAL</center>
						</th>
						<th style="color:white;">
							<center>CONT</center>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->samplePurchaseProduct as $key => $ppku)
					@php
					if($ppku->unit == '1' || $ppku->unit == '4'){
					if($sample->ppn == '1'){
					$total += round(($ppku->price / $ppnpembagi)) * $ppku->qty;
					$totallain += round(($ppku->price / $ppnpembagi)) * $ppku->qty;
					$qtytot += $ppku->qty;
					}else{
					$total += $ppku->price * $ppku->qty;
					$totallain += $ppku->price * $ppku->qty;
					$qtytot += $ppku->qty;
					}
					$totalqtylain += $ppku->qty;
					@endphp
					<tr>
						<td style="vertical-align:center;">
							<center>
								{{ $no }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $ppku->product->type->code }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								<img src="{{ $ppku->product->type->image() }}"
									style="max-width:10px; border:1px solid #ddd; border-radius:4px; padding: 5px;"
									class="img-fluid img-thumbnail">
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $ppku->product->type->length }}x{{ $ppku->product->type->width }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $ppku->product->type->category->name }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $ppku->product->type->color->name }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center style="font-size:8px;">
								{{ $ppku->remark }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $ppku->product->type->surface->name }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ number_format($ppku->qty,2,',','.') }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $sample->currency_id == 5 ? 'Rp' : $sample->currency->symbol }} {{
								number_format($sample->ppn == '1' ? round(($ppku->price / $ppnpembagi)) : $ppku->price,
								2, ',', '.') }}
							</center>
						</td>
						<td style="vertical-align:center;" colspan="2">
							<center>
								{{ $sample->currency_id == 5 ? 'Rp' : $sample->currency->symbol }} {{
								number_format($sample->ppn == '1' ? round(($ppku->price / $ppnpembagi)) * $ppku->qty :
								$ppku->price * $ppku->qty, 2, ',', '.') }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $ppku->product->containerStandart() }}
							</center>
						</td>
					</tr>
					@php
					$no++;
					}
					@endphp
					@endforeach
					<tr>
						<td colspan="8" style="text-align:right;">Total Qty</td>
						<td style="text-align:center;">{{ $totalqtylain }}</td>
						<td style="text-align:right;">Total</td>
						<td colspan="3" style="text-align:right;">{{ number_format($totallain, 2, ',', '.') }}</td>
					</tr>
					<tr>
						<td colspan="10" style="text-align:right;">Tax</td>
						<td colspan="3" style="text-align:right;">{{ $sample->ppn == '1' ? $sample->currency->symbol.'
							'.number_format($totallain * $persenppn, 2, ',', '.') : 0 }}</td>
					</tr>
					<tr>
						<td colspan="10" style="text-align:right;">Grandtotal</td>
						<td colspan="3" style="text-align:right;">{{ $sample->ppn == '1' ? $sample->currency->symbol.'
							'.number_format($totallain + ($totallain * $persenppn), 0, ',', '.') :
							$sample->currency->symbol.' '.number_format($totallain, 2, ',', '.') }}</td>
					</tr>
				</tbody>
			</table>
			@php
			}
			@endphp
			<br>

			<table style="border: 1px solid black;border-collapse: collapse;">
				@if($adatile == true && $adalain == true)
				<tr>
					<td width="15%" style="text-align:right;">
						<h6 style="font-size:10px;">Grandtotal All :</h6>
					</td>
					<td style="text-align:left;">
						<div style="font-size:10px;">
							{{ $sample->ppn == '1' ? number_format(round(($totaltile + $totallain) + (($totaltile +
							$totallain) * $persenppn)),0,',','.') : number_format(round($totaltile +
							$totallain),2,',','.') }}
						</div>
					</td>
				</tr>
				@endif
				<tr>
					<td width="15%" style="text-align:right;">
						<h6 style="font-size:10px;">Notes :</h6>
					</td>
					<td style="text-align:left;">
						<div style="font-size:10px;">
							{{ $sample->note }}
						</div>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;">
						<h6 style="font-size:10px;">Nominal in words :</h6>
					</td>
					<td style="text-align:left;">
						<div style="font-size:10px;">
							{{ $sample->ppn == '1' ? App\Helper\SMB::say(round(($totaltile + $totallain) + (($totaltile
							+ $totallain) * $persenppn))) : App\Helper\SMB::say(round($totaltile + $totallain)) }}
						</div>
					</td>
				</tr>
			</table><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;">
						<div style="font-size:10px;">PO released by</div>
						@if(isset($sample->user->sign))
						<div><img src="{{ url(Storage::url($sample->user->sign)) }}" height="65px"></div>
						@else
						<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $sample->user->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $sample->user->userRole->first()->role() }}
						</div>
					</td>

					@if(isset($sample->checked->name))
					<td style="text-align:center;">
						<div style="font-size:10px;">Checked by</div>
						@if(isset($sample->checked->sign))
						<div><img src="{{ url(Storage::url($sample->checked->sign)) }}" height="65px"></div>
						@else
						<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $sample->checked->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $sample->checked->userRole->first()->role() }}
						</div>
					</td>
					@endif
					@if(isset($sample->approved->name))
					<td style="text-align:center;">
						<div style="font-size:10px;">Approved By</div>
						@if(isset($sample->approved->sign))
						<div><img src="{{ url(Storage::url($sample->approved->sign)) }}" height="65px"></div>
						@else
						<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $sample->approved->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $sample->approved->userRole->first()->role() }}
						</div>
					</td>
					@endif
					<td style="text-align:center;">
						<div style="font-size:10px;">PO accepted by</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:50px;">
				<tr>
					<td style="text-align:right;color:black;font-size:10px;">
						Created at : {{ $sample->created_at }}
					</td>
				</tr>
			</table>
		</div>
		@if($sample->currency_id !== '5')
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title" rowspan="2">
									<img src="{{ url('website/logo-black.png') }}" width="275">
								</td>
								<td colspan="2" style="text-align:right;padding-bottom:15px;"><img
										src="{{ url('website/pta_new_logo.png') }}" height="30px"
										style="margin-right:5px;"></td>
							</tr>
							<tr>
								<td style="padding-right:25px;padding-top:100px !important;">
									<div style="font-size:9px; font-weight:bold;">JAGAT BUILDING</div>
									<div style="font-size:9px; font-weight:500;">Tomang Raya No 28 - 30, Jakarta 11430
									</div>
									<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
									<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com
									</div>
								</td>
								<td
									style="border-left: 3px solid #51b6bc; text-align:right;padding-top:100px !important;">
									<div style="font-size:9px; font-weight:bold;">MODERN CERAMIC</div>
									<div style="font-size:9px; font-weight:500;">Baliwerti 119 - 121, Surabaya 60174
									</div>
									<div style="font-size:9px; font-weight:500;">Phone : 031-5472860 / 031-5324505</div>
									<div style="font-size:9px; font-weight:500;">Email : info@smartmarbleandbath.com
									</div>
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
					<td>
						<table>
							<tr>
								<td style="background-color:#0b95b8;text-align:center;color:white;padding-top:10px;padding-bottom:10px;"
									width="55%">
									<h3><b>PURCHASE ORDER (IN IDR)</b></h3>
								</td>
								<td width="5%">

								</td>
								<td width="40%" rowspan="6">
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="40%" style="font-size:10px;">Company Address</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>PT PERWIRA TAMARAYA ABADI
													<br>
													PERGUD. BUMI MASPION IX/E-1, ROMOKALISARI,<br>
													BENOWO, SURABAYA, 60195, INDONESIA</b>
											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Telp/Fax.</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>031-547 2860/031-547
													8924</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Taxpayer Id No.</td>
											<td width="5%" style="font-size:10px;">:</td>
											<td style="text-align:left; font-size:10px;"><b>02.458.040.9-604.000</b>
											</td>
										</tr>
									</table>
									<br>
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="40%" style="font-size:10px;">Delivered To</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->on_behalf
													}}</b> </td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Address</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->delivery_address }}</b> </td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Courier Method</td>
											<td style="text-align:left; font-size:10px;">:
												<b>{{ $sample->courier_method }}</b>

											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Destination</td>
											<td style="text-align:left; font-size:10px;">:
												<b>{{ $sample->city->name.', '.$sample->country->name }}</b>

											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">PIC</td>
											<td style="text-align:left; font-size:10px;">:
												<b>{{ $sample->pic }}</b>
											</td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">PIC Phone No.</td>
											<td style="text-align:left; font-size:10px;">:
												<b>{{ $sample->pic_no }}</b>
											</td>
										</tr>
									</table>
									<br>
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="40%" style="font-size:10px;">Payment Method</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->payment_method }} days</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Price</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->price()
													}}</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Currency</td>
											<td style="text-align:left; font-size:10px;">: <b>IDR (Indonesia)</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">Brand on Box</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->brand_on_box
													}}</b></td>
										</tr>
										<tr>
											<td width="40%" style="font-size:10px;">SNI No.</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->sni }}</b>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="55%">
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="30%" style="font-size:10px;">Date of PO</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ date('d F Y',
													strtotime($sample->created_at)) }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">PO No.</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->code }}</b>
											</td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Ref SO No.</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->sample ?
													$sample->sample->code : '-' }}</b></td>
										</tr>
									</table>
									<br>
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="30%" style="font-size:10px;">Customer</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->customer ?
													$sample->customer->name : '-' }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Sales Person</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->sales ?
													$sample->sales->name : '-' }}</b></td>
										</tr>
									</table>
									<br>
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr class="heading">
											<td colspan="2">
												<div style="font-size:10px;"><b>Order To :</b></div>
											</td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Supplier</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->supplier->name }} </b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Address</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->supplier->address }} </b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">PIC</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->supplier->pic
													}}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">PIC Phone No.</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->supplier->phone }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Production Lead Time</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->production_lead_time }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Est. Delivery Time</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->estimated_delivery }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Est. Arrival Time</td>
											<td style="text-align:left; font-size:10px;">: <b>{{
													$sample->estimated_arrival }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Factory Name</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $sample->factory_name
													}}</b></td>
										</tr>
									</table>
								</td>
								<td width="5%">

								</td>
							</tr>
							<tr>
								<td width="55%">


								</td>
								<td width="5%"></td>
							</tr>
							<tr>
								<td width="55%"></td>
								<td width="5%"></td>
							</tr>
							<tr>
								<td width="55%"></td>
								<td width="5%"></td>
							</tr>
							<tr>
								<td width="55%"></td>
								<td width="5%"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<p style="font-size:10px;">Our dear Supplier,
				<br>We would like to place an order with details as follows:
			</p>
			@php
			$adatile = false;
			$adalain = false;
			$total = 0;
			$totaltile = 0;
			$totallain = 0;
			foreach($sample->samplePurchaseProduct as $key => $pp){
			if($pp->unit == '2' || $pp->unit == '3'){
			$adatile = true;
			}
			if($pp->unit == '1' || $pp->unit == '4'){
			$adalain = true;
			}
			}
			@endphp
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;" colspan="13">
							<center>DETAIL OF PURCHASE ORDER</center>
						</th>
					</tr>
				</thead>
				@php
				$no = 1;
				if($adatile == true){
				$qtytot = 0;
				$qtym2 = 0;
				@endphp
				<thead>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;" colspan="13">
							<center>TILES</center>
						</th>
					</tr>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;">
							<center>NO</center>
						</th>
						<th style="color:white;">
							<center>CODE</center>
						</th>
						<th style="color:white;">
							<center>PRODUCT</center>
						</th>
						<th style="color:white;">
							<center>SIZE(cm)</center>
						</th>
						<th style="color:white;">
							<center>CATEGORY</center>
						</th>
						<th style="color:white;">
							<center>COLOR</center>
						</th>
						<th style="color:white;">
							<center>SHADE</center>
						</th>
						<th style="color:white;">
							<center>FINISH</center>
						</th>
						<th style="color:white;">
							<center>TOTAL (box)</center>
						</th>
						<th style="color:white;">
							<center>TOTAL (sqm)</center>
						</th>
						<th style="color:white;">
							<center>PRICE/M<sup>2</sup></center>
						</th>
						<th style="color:white;">
							<center>TOTAL</center>
						</th>
						<th style="color:white;">
							<center>CONT</center>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->samplePurchaseProduct as $key => $pp)
					@php
					if($pp->unit == '2' || $pp->unit == '3'){
					$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) *
					$pp->product->carton_pcs;
					if($sample->ppn == '1'){
					if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
						$total += (($pp->price * $sample->currency_rate) / $ppnpembagi) * $pp->qty;
						$totaltile += (($pp->price * $sample->currency_rate) / $ppnpembagi) * $pp->qty;
						}else{
						if($m2 < 1.1 && date('Y-m',strtotime($sample->created_at)) < '2022-06' && $pp->
								product->type->category->parent()->id == 18){
								$total += (($pp->price * $sample->currency_rate) / $ppnpembagi) * $pp->qty;
								$totaltile += (($pp->price * $sample->currency_rate) / $ppnpembagi) * $pp->qty;
								}else{
								$total += (($pp->price * $sample->currency_rate) / $ppnpembagi) * $pp->qty * $m2;
								$totaltile += (($pp->price * $sample->currency_rate) / $ppnpembagi) * $m2 * $pp->qty;
								}
								}

								$qtytot += $pp->qty;
								$qtym2 += $pp->qty*$m2;
								}else{
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$total += ($pp->price * $sample->currency_rate) * $pp->qty;
									$totaltile += ($pp->price * $sample->currency_rate) * $pp->qty;
									}else{
									if($m2 < 1.1 && date('Y-m',strtotime($sample->created_at)) < '2022-06' && $pp->
											product->type->category->parent()->id == 18){
											$total += ($pp->price * $sample->currency_rate) * $pp->qty;
											$totaltile += ($pp->price * $sample->currency_rate) * $pp->qty;
											}else{
											$total += ($pp->price * $sample->currency_rate) * $pp->qty * $m2;
											$totaltile += ($pp->price * $sample->currency_rate) * $m2 * $pp->qty;
											}

											}

											$qtytot += $pp->qty;
											$qtym2 += $pp->qty*$m2;
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
														<img src="{{ $pp->product->type->image() }}"
															style="max-width:15px; border:1px solid #ddd; border-radius:4px; padding: 5px;"
															class="img-fluid img-thumbnail">
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->type->category->name }} <br>
														{{ $pp->product->hsCode->name }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->type->color->name }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center style="font-size:8px;">
														{{ $pp->remark }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->type->surface->name }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ number_format($pp->qty,2,',','.') }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ number_format($pp->qty*$m2,2,',','.') }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														Rp {{ number_format( $sample->ppn == '1' ? ($pp->price *
														$sample->currency_rate) / $ppnpembagi : ($pp->price *
														$sample->currency_rate), 2, ',', '.') }}
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														Rp
														@if($sample->ppn == '1')
														@if($m2 < 1.1 && $pp->product->type->category->parent()->id !==
															18)
															{{ number_format((($pp->price * $sample->currency_rate) /
															$ppnpembagi) * $pp->qty,0,',','.') }}
															@else
															@if($m2 < 1.1 && date('Y-m',strtotime($sample->created_at))
																< '2022-06' && $pp->
																	product->type->category->parent()->id == 18)
																	{{ number_format((($pp->price *
																	$sample->currency_rate) / $ppnpembagi) *
																	$pp->qty,0,',','.') }}
																	@else
																	{{ number_format((($pp->price *
																	$sample->currency_rate) / $ppnpembagi) * $pp->qty *
																	$m2,0,',','.') }}
																	@endif
																	@endif
																	@else
																	@if($m2 < 1.1 && $pp->
																		product->type->category->parent()->id !== 18)
																		{{ number_format(($pp->price *
																		$sample->currency_rate) * $pp->qty,0,',','.')
																		}}
																		@else
																		@if($m2 < 1.1 && date('Y-m',strtotime($sample->
																			created_at)) < '2022-06' && $pp->
																				product->type->category->parent()->id ==
																				18)
																				{{ number_format(($pp->price *
																				$sample->currency_rate) *
																				$pp->qty,0,',','.') }}
																				@else
																				{{ number_format(($pp->price *
																				$sample->currency_rate) * $pp->qty *
																				$m2,0,',','.') }}
																				@endif
																				@endif
																				@endif
													</center>
												</td>
												<td style="vertical-align:center;">
													<center>
														{{ $pp->product->containerStandart() }}
													</center>
												</td>
											</tr>
											@php
											$no++;
											}
											@endphp
											@endforeach
											<tr>
												<td colspan="8" style="text-align:right;">Total Qty</td>
												<td style="text-align:center;">{{ $totalqtybox }}</td>
												<td style="text-align:center;">{{ $totalqtytile }}</td>
												<td style="text-align:right;">Total</td>
												<td colspan="3" style="text-align:right;">{{ 'Rp
													'.number_format($totaltile, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td colspan="10" style="text-align:right;">Tax</td>
												<td colspan="3" style="text-align:right;">{{ $sample->ppn == '1' ? 'Rp
													'.number_format($totaltile * $persenppn, 0, ',', '.') : 'Rp
													'.number_format(0, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td colspan="10" style="text-align:right;">Grandtotal</td>
												<td colspan="3" style="text-align:right;">{{ $sample->ppn == '1' ? 'Rp
													'.number_format( $totaltile + ($totaltile * $persenppn), 0, ',',
													'.') : 'Rp '.number_format($totaltile, 0, ',', '.') }}</td>
											</tr>
				</tbody>
				@php
				}
				if($adatile == true && $adalain == true){
				@endphp
				<thead>
					<tr>
						<th style="color:white;" colspan="13"> ... </th>
					</tr>
				</thead>
				@php
				}
				if($adalain == true){
				$qtytot = 0;
				@endphp
				<thead>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;" colspan="13">
							<center>NON-TILES</center>
						</th>
					</tr>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;">
							<center>NO</center>
						</th>
						<th style="color:white;">
							<center>CODE</center>
						</th>
						<th style="color:white;">
							<center>PRODUCT</center>
						</th>
						<th style="color:white;">
							<center>SIZE(cm)</center>
						</th>
						<th style="color:white;">
							<center>CATEGORY</center>
						</th>
						<th style="color:white;">
							<center>COLOR</center>
						</th>
						<th style="color:white;">
							<center>SHADE</center>
						</th>
						<th style="color:white;">
							<center>FINISH</center>
						</th>
						<th style="color:white;">
							<center>TOTAL(pcs)</center>
						</th>
						<th style="color:white;">
							<center>PRICE/PCS</center>
						</th>
						<th style="color:white;">
							<center>RATE</center>
						</th>
						<th style="color:white;" colspan="2">
							<center>TOTAL</center>
						</th>

					</tr>
				</thead>
				<tbody>
					@foreach($sample->samplePurchaseProduct as $key => $pp)
					@php
					if($pp->unit == '1' || $pp->unit == '4'){
					if($sample->ppn == '1'){
					$total += (($pp->price * $sample->currency_rate) / $ppnpembagi) * $pp->qty;
					$totallain += (($pp->price * $sample->currency_rate) / $ppnpembagi) * $pp->qty;
					$qtytot += $pp->qty;
					}else{
					$total += ($pp->price * $sample->currency_rate) * $pp->qty;
					$totallain += ($pp->price * $sample->currency_rate) * $pp->qty;
					$qtytot += $pp->qty;
					}
					$totalqtylain += $pp->qty;
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
								<img src="{{ $pp->product->type->image() }}"
									style="max-width:15px; border:1px solid #ddd; border-radius:4px; padding: 5px;"
									class="img-fluid img-thumbnail">
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
							<center style="font-size:8px;">
								{{ $pp->remark }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $pp->product->type->surface->name }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ number_format($pp->qty,2,',','.') }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								Rp {{ number_format($sample->ppn == '1' ? (($pp->price * $sample->currency_rate) /
								$ppnpembagi) : ($pp->price * $sample->currency_rate), 2, ',', '.') }}
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								Rp {{ number_format($sample->currency_rate,0,',','.') }}
							</center>
						</td>
						<td style="vertical-align:center;" colspan="2">
							<center>
								Rp {{ number_format($sample->ppn == '1' ? (($pp->price * $sample->currency_rate) /
								$ppnpembagi) * $pp->qty : ($pp->price * $sample->currency_rate) * $pp->qty, 2, ',',
								'.') }}
							</center>
						</td>

					</tr>
					@php
					$no++;
					}
					@endphp
					@endforeach
					<tr>
						<td colspan="8" style="text-align:right;">Total Qty</td>
						<td style="text-align:center;">{{ $totalqtylain }}</td>
						<td style="text-align:right;">Total</td>
						<td colspan="3" style="text-align:right;">{{ number_format($totallain, 2, ',', '.') }}</td>
					</tr>
					<tr>
						<td colspan="10" style="text-align:right;">Tax</td>
						<td colspan="3" style="text-align:right;">{{ $sample->ppn == '1' ? 'Rp
							'.number_format($totallain * $persenppn, 2, ',', '.') : 0 }}</td>
					</tr>
					<tr>
						<td colspan="10" style="text-align:right;">Grandtotal</td>
						<td colspan="3" style="text-align:right;">{{ $sample->ppn == '1' ? 'Rp
							'.number_format($totallain + ($totallain * $persenppn), 2, ',', '.') : 'Rp
							'.number_format($totallain, 2, ',', '.') }}</td>
					</tr>
				</tbody>
				@php
				}
				@endphp
			</table><br>
			<table style="border: 1px solid black;border-collapse: collapse;">
				<tr>
					<td width="15%" style="text-align:right;">
						<h6 style="font-size:10px;">Notes :</h6>
					</td>
					<td style="text-align:left;">
						<div style="font-size:10px;">
							{{ $sample->note }}
						</div>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;">
						<h6 style="font-size:10px;">Nominal in words :</h6>
					</td>
					<td style="text-align:left;">
						<div style="font-size:10px;">
							{{ $sample->ppn == '1' ? App\Helper\SMB::say(round(($totaltile + $totallain) + (($totaltile
							+ $totallain) * $persenppn))) : App\Helper\SMB::say(round($totaltile + $totallain)) }}
						</div>
					</td>
				</tr>
			</table><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;">
						<div style="font-size:10px;">PO released by</div>
						@if(isset($sample->user->sign))
						<div><img src="{{ url(Storage::url($sample->user->sign)) }}" height="65px"></div>
						@else
						<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $sample->user->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $sample->user->userRole->first()->role() }}
						</div>
					</td>

					@if(isset($sample->checked->name))
					<td style="text-align:center;">
						<div style="font-size:10px;">Checked by</div>
						@if(isset($sample->checked->sign))
						<div><img src="{{ url(Storage::url($sample->checked->sign)) }}" height="65px"></div>
						@else
						<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $sample->checked->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $sample->checked->userRole->first()->role() }}
						</div>
					</td>
					@endif
					@if(isset($sample->approved->name))
					<td style="text-align:center;">
						<div style="font-size:10px;">Approved By</div>
						@if(isset($sample->approved->sign))
						<div><img src="{{ url(Storage::url($sample->approved->sign)) }}" height="65px"></div>
						@else
						<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $sample->approved->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $sample->approved->userRole->first()->role() }}
						</div>
					</td>
					@endif
					<td style="text-align:center;">
						<div style="font-size:10px;">PO accepted by</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:50px;">
				<tr>
					<td style="text-align:right;color:black;font-size:10px;">
						Created at : {{ $sample->created_at }}
					</td>
				</tr>
			</table>
		</div>
		@endif
	</body>

	</html>