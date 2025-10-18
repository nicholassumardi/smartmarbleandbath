@php
	$discount = 0;
	if(App\Models\ProjectDelivery::where('project_sale_id',$project->projectSale->id)->first()->id == $project->id){
		$discount = $project->project->discount;
	}
@endphp
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
			
			td {
				font-size: 11pt;
				font-weight: bold;
			}
			
			th {
				font-size:11pt;
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
			
					
			.payment-note tr td, .payment-box tr td, .list-payments tr td, .list-payments tr th{
				font-size : 15pt !important;
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
									<h3><b>SALES INVOICE PRODUCT (INV)</b></h3>
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
							<tr>
								<td width="40%" style="font-size:12px;">Note</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $project->invoice_note ? $project->invoice_note :''  }}</td>
							</tr>
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
			@php
				if(date('Y-m-d',strtotime($project->projectSale->created_at)) < '2022-04-01'){
					$persenppn = 0.1;
				}else{
					$persenppn = 0.11;
				}
				
				$koma = 0;
				
				if($project->currency_id !== '5'){
					$koma = 2;
				}
			
				$koma = 0;
				
				if($project->projectSale->currency_id !== '5'){
					$koma = 2;
				}
			
				$adatile = false;
				$adalain = false;
				$adacustom = false;
				$total = 0;
				$totaltile = 0;
				$totallain = 0;
				$qtysent = 0;
				$qtytotal = 0;
				$qtysentbox = 0;
				$qtysentlain = 0;
				$break_tolerance = $project->getTotal()['totalBreakTolerance'];
				$sample_deduction = $project->getSampleDeduction()['totalDeduction'];
				
				foreach($project->projectDeliveryProduct as $key => $pp){
					if($pp->unit == '2' || $pp->unit == '3'){
						$adatile = true;
					}
					if($pp->unit == '1' || $pp->unit == '4'){
						$adalain = true;
						$adacustom = $pp->unit == '4' ? true : false;
					}
				}
			@endphp
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				@php
				$no = 1;
				if($adatile == true){
					$qtytot = 0;
					$qtym2 = 0;
				@endphp
				<thead>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;" colspan="8"><center>TILES</center></th>
					</tr>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>NAME</center></th>
						<th style="color:white;"><center>SHADE</center></th>
						<th style="color:white;"><center>SIZE</center></th>
						<th style="color:white;"><center>PCS/BOX</center></th>
						<th style="color:white;"><center>QTY DELIVERED</center></th>
						<th style="color:white;"><center>PRICE</center></th>
						<th style="color:white;"><center>TOTAL</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($project->projectDeliveryProduct as $key => $ps)
						@php
							if(($ps->unit == '2' || $ps->unit == '3') && $ps->getQtyMinusReturn() > 0){
								
								foreach($project->projectSale->projectSaleProduct->where('product_id',$ps->product_id) as $psp){
									$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
									$countbox = $ps->getQtyMinusReturn();
									if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
										$total += ($psp->best_price * $countbox) / $project->projectSale->currency_rate;
										$price = $psp->best_price / $project->projectSale->currency_rate;
										$totaltile += ($psp->best_price * $countbox) / $project->projectSale->currency_rate;
									}else{
										if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
											$total += ($psp->best_price * $countbox) / $project->projectSale->currency_rate;
											$price = $psp->best_price / $project->projectSale->currency_rate;
											$totaltile += ($psp->best_price * $countbox) / $project->projectSale->currency_rate;
										}else{
											$total += (round($psp->best_price * $m2,0) * $countbox) / $project->projectSale->currency_rate;
											$price = $psp->best_price / $project->projectSale->currency_rate;
											$totaltile += (round($psp->best_price * $m2,0) * $countbox) / $project->projectSale->currency_rate;
										}
										
									}
									$qtysent += $ps->getQtyMinusReturn();
									$qtysentbox += $ps->getQtyMinusReturn();
									$qtytotal += $countbox;
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
									{{ $ps->product->name() }}
								</center>
							</td>
							<td style="vertical-align:center;">
									{{ $ps->shading }}
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->type->length }}x{{ $ps->product->type->width }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->carton_pcs }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ round($ps->qty,0).' '.$ps->unit() }}
									@if($ps->qtyReturn() > 0)
										<br>
										Return {{ $ps->qtyReturn().' '.$ps->unit() }}
									@endif
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($m2 < 1.1 && $ps->product->type->category->parent()->id !== 18){
											echo number_format($price,$koma,',','.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $ps->product->type->category->parent()->id == 18){
												echo number_format($price,$koma,',','.');
											}else{
												echo number_format(round($price * $m2,0),$koma,',','.');
											}
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php 
										if($m2 < 1.1 && $ps->product->type->category->parent()->id !== 18){
											echo number_format($price * $ps->getQtyMinusReturn(),$koma,',','.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $ps->product->type->category->parent()->id == 18){
												echo number_format($price * $ps->getQtyMinusReturn(),$koma,',','.');
											}else{
												echo number_format(round($price * $m2,0) * $ps->getQtyMinusReturn(),$koma,',','.');
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
						<th style="text-align:right;" colspan="5">Total Qty</th>
						<th style="text-align:center;">{{ $qtysentbox }} Box</th>
						<th style="text-align:right;" colspan="2"></th>
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
						<th style="color:white;" colspan="8"><center>NON-TILES</center></th>
					</tr>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>NAME</center></th>
						<th style="color:white;"><center>SHADE</center></th>
						<th style="color:white;"><center>SIZE(cm)</center></th>
						<th style="color:white;"><center>PCS/BOX</center></th>
						<th style="color:white;"><center>QTY DELIVERED</center></th>
						<th style="color:white;"><center>PRICE</center></th>
						<th style="color:white;"><center>TOTAL</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($project->projectDeliveryProduct as $key => $ps)
						@php
							if(($ps->unit == '1' && $ps->getQtyMinusReturn() > 0) || ($ps->unit == '4' && $ps->getQtyMinusReturn() > 0)){
								
								foreach($project->projectSale->projectSaleProduct->where('product_id',$ps->product_id) as $psp){
									$total += (round($psp->best_price * $ps->getQtyMinusReturn(),0)) / $project->projectSale->currency_rate;
									$totallain += (round($psp->best_price * $ps->getQtyMinusReturn(),0)) / $project->projectSale->currency_rate;
									$price = ($psp->best_price) / $project->projectSale->currency_rate;
									$qtysent += $ps->getQtyMinusReturn();
									$qtytotal += $ps->getQtyMinusReturn();
								}
								
								$qtysentlain += $ps->getQtyMinusReturn();
						@endphp
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->name() }}
								</center>
							</td>
							<td style="vertical-align:center;">
									{{ $ps->shading }}
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->type->length }}x{{ $ps->product->type->width }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->carton_pcs }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->getQtyMinusReturn().' '.$ps->unit() }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ number_format($price,$koma,',','.') }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ number_format($price * $ps->getQtyMinusReturn(),$koma,',','.') }}
								</center>
							</td>
						</tr>
						@php
								$no++;
							}
						@endphp
					@endforeach
					<tr>
						<th style="text-align:right;" colspan="5">Total Qty</th>
						<th style="text-align:center;">{{ $qtysentlain }} {{$adacustom == true ? '' : 'pcs'}}</th>
						<th style="text-align:right;" colspan="2"></th>
					</tr>
				</tbody>
				@php
				}
				@endphp
			</table><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%">
						<table cellpadding="2" cellspacing="0" border="1" style="background-color:#0b95b8;font-size:14px;color:white;">
							<tr>
								<td>
									<b>GRANDTOTAL</b>
								</td>
								<td style="text-align:center;">
									<b>{{ $project->projectSale->currency->code }} {{ 
										$project->project->ppn == '1' ? 
											number_format(round(($total - $discount - ($break_tolerance + $sample_deduction)) * (1 + $persenppn)), $koma, ',', '.') 
										: 	number_format(round(($total - $discount - ($break_tolerance + $sample_deduction))), $koma, ',', '.') 
										}}
									</b>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<b>Nominal in words : {{ 
										$project->project->ppn == '1' ?
										App\Helper\SMB::say(round(($total - $discount - ($break_tolerance + $sample_deduction)) * (1 + $persenppn))) : App\Helper\SMB::say(round((($total - $discount) - ($break_tolerance + $sample_deduction)))) 
								     }} Rupiahs
								     </b>
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
										@if($project->project->ppn == '1' && $project->project->coa_id != 380 )
											<p>
												<br>BCA 468-3434-178
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Indrapura - Surabaya<br>

												<br>OR<br>

												<br>Mandiri 14-000-5996-5997
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Kembang Jepun - Surabaya
											</p>
										@elseif($project->project->coa_id == 380)
											<p>
												<br>BCA 3293-678-789
												<br>a/n PT. Perwira Sejati Internusa
												<br>Cab. Indrapura - Surabaya<br>
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
									</div>
								</td>
							</tr> -->
						</table>
					</td>
					<td width="5%"></td>
					<td width="45%" style="vertical-align:top;">
						<table cellpadding="0" cellspacing="0" border="1" class="payment-box">
							<tr>
								<td>
									<h6>SUBTOTAL PRODUCT</h6>
								</td>
								<td>
									<h6><b>{{ $project->projectSale->currency->code }} 
									{{ 
										number_format($total, $koma, ',', '.')
									}}
									</h6>
								</td>
							</tr>
							@if($discount > 0)
							<tr>
								<td>
									<h6>TOTAL PRODUCT</h6>
								</td>
								<td>
									<h6><b>{{ $project->projectSale->currency->code }} 
									{{ 
										number_format($total, $koma, ',', '.')
									}}
									</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>DISCOUNT</h6>
								</td>
								<td>
									<h6> 
									{{ 
										number_format($discount, $koma, ',', '.')
									}}
									</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TOTAL PRODUCT AFTER DISCOUNT</h6>
								</td>
								<td>
									<h6> 
									{{ 
										number_format($total - $discount, $koma, ',', '.')
									}}
									</h6>
								</td>
							</tr>
							@endif
							@if ($project->break_tolerance)
							<tr>
								<td>
									<h6>TOLERANSI PECAH {{ $project->break_tolerance }} %</h6>
								</td>
								<td>
									<h6>
										<b>{{ $project->projectSale->currency->code }}
											{{ number_format(round($break_tolerance, 0), $koma, ',', '.') }}
										</b>
									</h6>
								</td>
							</tr>
							@endif
							@if ($sample_deduction > 0)
							<tr>
								<td>
									<h6>POTONGAN SAMPLE ({{ $project->getSampleDeduction()['qtyDeduction'] }})</h6>
								</td>
								<td>
									<h6>
										<b>{{ $project->projectSale->currency->code }}
											{{ number_format(round($sample_deduction, 0), $koma, ',', '.') }}
										</b>
									</h6>
								</td>
							</tr>
							@endif
							<tr>
								<td>
									<h6>TAX PRODUCT</h6>
								</td>
								<td>
									<h6><b>{{ $project->projectSale->currency->code }} 
						        	{{ 
									    $project->project->ppn == '1' ? number_format((($total - $discount) - ($break_tolerance + $sample_deduction)) * $persenppn, $koma, ',', '.') : number_format(0, $koma,',', '.')
									}}
									</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>GRANDTOTAL</h6>
								</td>
								<td>
									<h6><b>{{ $project->projectSale->currency->code }} 
									{{
										$project->project->ppn == '1' ?
										number_format(($total - $discount - ($break_tolerance + $sample_deduction)) * (1 + $persenppn), $koma, ',', '.')
										: number_format((($total - $discount) - ($break_tolerance + $sample_deduction)), $koma, ',', '.')
									}}
									</h6>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				@php
					$grandtotal = 0;
					if($project->project->ppn == '1'){
						$grandtotal = round(($total - $discount - ($break_tolerance + $sample_deduction)) * (1 + $persenppn));
					}else{
						$grandtotal = round((($total - $discount) - ($break_tolerance + $sample_deduction)));
					}
					
					$text = '';
					
					if($grandtotal < 5000000){
						$text = 'background-image: url('.url("website/stempel_pta_baru_small_1.png").');background-size:12px;background-repeat:no-repeat;background-position: center center;';
					}
				@endphp
			</table>
			<br><br>
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
					<td style="text-align:center;{{ $text }}">
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
			<h4>Blow Up Material</h4>
			<table border="1" cellpadding="5" cellspacing="0" style="font-size:10px;">
				@php
				if($adatile == true){
				@endphp
				<tbody>
					<tr>
					@foreach($project->projectDeliveryProduct as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								if((($key + 1) % 6) == 1){
									echo '<tr>';
								}
						@endphp
							<td width="10%">
								<img src="{{ $pp->product->type->image() }}" style="max-width:50px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								<center style="font-size:15px;">
									{{ $pp->product->type->code }}
								</center>
							</td>
						@php
								if((($key + 1) % 6) == 1){
									echo '</tr>';
								}
							}
						@endphp
					@endforeach
					</tr>
				</tbody>
				@php
				}
				if($adalain == true){
				@endphp
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($project->projectDeliveryProduct as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								if(($no % 6) == 1){
									echo '<tr>';
								}
						@endphp
							<td>
								<img src="{{ $pp->product->type->image() }}" style="max-width:50px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
						@php
								if(($no % 6) == 1){
									echo '</tr>';
								}
								
								$no++;
							}
						@endphp
					@endforeach
				</tbody>
				@php
				}
				@endphp
			</table>
		</div>
		<div class="separate-box">
			<table id="table-kwitansi" style="vertical-align: top;padding: 5px;font-size:13px;background-image: url({{ url('website/bg_kwitansi.png') }});" width="100%" height="auto">
				<tr>
					<td rowspan="6" width="20%" style="padding:0;"><img src="{{ $project->projectSale->sales->branch == 4 ? url('website/logo_samping_psi.jpg') : url('website/logo_samping_1.png') }}" height="350px"></td>
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
					<td>
					 {{ 
						$project->project->ppn == '1' ?
						App\Helper\SMB::say(round(($total - $discount - ($break_tolerance + $sample_deduction)) * (1 + $persenppn))) : App\Helper\SMB::say(round((($total - $discount) - ($break_tolerance + $sample_deduction)))) 
					 }} Rupiahs
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
					<td>
						{{ $project->projectSale->currency->code }} {{
							$project->project->ppn == '1' ?
							number_format(($total - $discount - ($break_tolerance + $sample_deduction)) * (1 + $persenppn), $koma, ',', '.')
							: number_format((($total - $discount) - ($break_tolerance + $sample_deduction)), $koma, ',', '.')
						}}</div>
					</td>
					
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
								</td>
							</tr>
						</table>
						@if($grandtotal < 5000000)
							
						@endif
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>