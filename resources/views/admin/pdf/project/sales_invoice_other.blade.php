<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sales Invoice {{ $project->code }}</title>
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:14px;
			}
		
			.invoice-box {
				font-size: 16px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
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
				background: #ebb220;
				border-bottom: 1px solid #ebb220;
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
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
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
				padding: 15px;
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
							<img src="website/kop_brand_report.png" width="100%">
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
									<h3><b>SALES INVOICE OTHER</b></h3>
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
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">INVOICE</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PIC</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->project->manager }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">CUSTOMER</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->project->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">ADDRESS</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->project->customer->address }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">DATE</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($project->date)) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">SALES</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->sales->name }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">SALES NO.</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PHONE</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->project->customer->phone }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">SO Date</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($project->projectSale->created_at)) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PROFORMA CODE</td>
								<td style="text-align:left; font-size:12px;">: {{ isset($project->projectDelivery->proforma_code) ? $project->projectDelivery->proforma_code : '-' }}</td>
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
			
				$adatile = false;
				$adalain = false;
				$total = 0;
				$totaltile = 0;
				$totallain = 0;
				$totalpaid = 0;
				
				foreach($project->projectSale->projectSalePay as $key => $psp){
					$totalpaid += $psp->nominal;
				}
				
				foreach($project->projectSale->projectSaleProduct as $key => $pp){
					if($pp->unit == '2' || $pp->unit == '3'){
						$adatile = true;
					}
					if($pp->unit == '1' || $pp->unit == '4'){
						$adalain = true;
					}
				}
			@endphp

			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				@php
				if($adatile == true){
				@endphp
				<thead>
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
				</thead>
				<tbody>
					@foreach($project->projectSale->projectSaleProduct as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
								$countbox = ceil($pp->qty / $m2);
								if($m2 < 1.1){
									$total += $pp->best_price * ($countbox - $pp->getCountReturn());
									$totaltile += $pp->best_price * ($countbox - $pp->getCountReturn());
								}else{
									$total += $pp->best_price * $m2 * ($countbox - $pp->getCountReturn());
									$totaltile += $pp->best_price * $m2 * ($countbox - $pp->getCountReturn());
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
									{{ $countbox - $pp->getCountReturn() }}
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
										if($m2 < 1.1){
											echo number_format($pp->best_price, 0, ',', '.');
										}else{
											echo number_format($pp->best_price * $m2, 0, ',', '.');
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php 
										if($m2 < 1.1){
											echo number_format($pp->best_price * ($countbox - $pp->getCountReturn()), 0, ',', '.');
										}else{
											echo number_format($pp->best_price * $m2 * ($countbox - $pp->getCountReturn()), 0, ',', '.');
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
				@php
				}
				if($adalain == true){
				@endphp
				<thead>
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
				</thead>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($project->projectSale->projectSaleProduct as $key => $pp)
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
				@php
				}
				@endphp
			</table>
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="45%">
						<table cellpadding="2" cellspacing="0" border="1" style="width:100% !important;">
							<tr>
								<td>
									<div style="font-size:12px;">Note :
									<br>{{ $project->projectSale->note }}
									</div>
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
									<h6>TAX PRODUCT</h6>
								</td>
								<td>
									<h6>IDR {{ $project->projectSale->project->ppn == '1' ? number_format($persenppn * $total, 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TOTAL PRODUCT</h6>
								</td>
								<td>
									<h6>IDR {{ $project->projectSale->project->ppn == '1' ? number_format($total + ($persenppn * $total), 0, ',', '.') : number_format($total, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>DELIVERY COST</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($project->projectSale->delivery_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>CUTTING COST</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($project->projectSale->cutting_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>MISCELLANEOUS COST</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($project->projectSale->misc_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TOTAL COST</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TAX COST</h6>
								</td>
								<td>
									<h6>IDR {{ $project->projectSale->ppn_cost == '1' ? number_format($persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@php
								$grandtotal = 0;
								
								if($project->projectSale->project->ppn == '1'){
									$grandtotal += $total + ($persenppn * $total);
								}else{
									$grandtotal += $total;
								}
								
								if($project->projectSale->ppn_cost == '1'){
									$grandtotal += $persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost);
								}else{
									$grandtotal += $project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost;
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
							<tr>
								<td>
									<h6>UNDERPAYMENT</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($grandtotal - $totalpaid, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>NOMINAL IN WORDS</h6>
								</td>
								<td>
									<h6>{{ $grandtotal - $totalpaid > 0 ? App\Helper\SMB::say(round($grandtotal - $totalpaid)) : 'Zero' }} Rupiahs</h6>
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
						<th style="color:white;" colspan="6"><center>List Payments</center></th>
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
					@foreach($project->projectSale->projectSalePay as $key => $psp)
						@php
							$style = '';
							if($project->id == $psp->id){
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
					@if(isset($project->approved->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Acknowledged By,</div>
						@if(isset($project->approved->sign))
							<div><img src="{{ url(Storage::url($project->approved->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->approved->name }}</div>
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
					</td>
					@endif
					@if(isset($project->check->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Checked By,</div>
						@if(isset($project->check->sign))
							<div><img src="{{ url(Storage::url($project->check->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->check->name }}</div>
					</td>
					@endif
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Created By</div>
						@if(isset($project->user->sign))
							<div><img src="{{ url(Storage::url($project->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->user->name }}</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="separate-box">
			<table id="table-kwitansi" style="vertical-align: top;padding: 15px;font-size:13px;background-image: url({{ url('website/bg_kwitansi.png') }});" width="100%" height="auto">
				<tr>
					<td rowspan="6" width="20%" style="padding:0;"><img src="{{ url('website/logo_samping.png') }}" height="400px"></td>
					<td width="25%">Receipt No.</td>
					<td width="1%">:</td>
					<td><?=str_replace('INV','RCP',$project->code)?></td>
				</tr>
				<tr>
					<td>Already received from</td>
					<td width="1%">:</td>
					<td>{{ $project->projectSale->project->customer->name }}</td>
				</tr>
				<tr>
					<td>Nominal in words</td>
					<td width="1%">:</td>
					<td>{{ App\Helper\SMB::say($project->nominal) }} Rupiahs</td>
				</tr>
				<tr>
					<td>For payment</td>
					<td width="1%">:</td>
					<td>According to Sales Invoice {{ $project->code }}</td>
				</tr>
				<tr>
					<td>Total</td>
					<td width="1%">:</td>
					<td><div style="border:1p solid black;padding:10px;">Rp {{ number_format($project->nominal,0,',','.') }}</div></td>
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
								<td style="text-align:center;">
									@if($project->projectSale->sales->branch == '1')
									
									Surabaya, {{ date('d F Y', strtotime($project->date)) }}
										<br><br><br><br><br><br><br><br>
										{{ App\Models\User::find(11)->name }}
										<br>
										(AR Staff)
									@elseif($project->projectSale->sales->branch == '2')
									
										Jakarta, {{ date('d F Y', strtotime($project->date)) }}
										<br><br><br><br><br><br><br><br>
										(...............................)
										
									@endif
								</td>
							</tr>
						</table>
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