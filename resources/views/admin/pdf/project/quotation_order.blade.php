<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Quotation Order {{ $project->code }}</title>
		@php
	    	$color = '#51b6bc';
			$margin = '1cm';
			$margin_top = '0cm';
			$margin_bottom = '0cm';
			$bg_image = '';

			if($project->project->ppn == '0'){
				$color = '#7ec9cd';
			}

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
			
			th {
				font-size:14px;
			}
			
			.invoice-box {
				font-size: 16px;
				color: #555;
				font-family: 'Lato', sans-serif;
				/* page-break-after: always; */
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				font-family: 'Lato', sans-serif;
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

			.invoice-box.rtl table {
				text-align: right;
				font-family: 'Lato', sans-serif;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
				font-family: 'Lato', sans-serif;
			}
			@page { 
				margin: {{$margin}}; 
			    margin-top:{{$margin_top}};
			    margin-bottom:{{$margin_bottom}};
			    background:url({{$bg_image}}) no-repeat 0 0;
                background-image-resize: 5;
			 }
			body { margin: 1cm; }
			
			.base-line {
				position: relative;
			}
			
			.strike-through {
				position: absolute;
				top: 0;
				left: 0;
			}
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
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>QUOTATION ORDER</b></h3>
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
								<td style="text-align:left; font-size:12px;">: {{ strtoupper($project->project->manager) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">DATE</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($project->created_at)) }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">CUSTOMER</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PROJECT NO</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->code }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">PROJECT NAME</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">REVISION</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->revision - 1 }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">CITY</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->city->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;"></td>
								<td style="text-align:left; font-size:12px;"></td>
							</tr>
							
						</table>
					</td>
				</tr>
				<tr><td colspan="2"></td></tr>
				<tr>
					<td colspan="2">
						<p style="font-size:12px;">Dear mr/mrs. {{ $project->project->manager }}<br>
						We are delighted to provide a price offer for this exciting project . The quotation and details are as follow :</p>
					</td>
				</tr>
			</table>
			
			@php
				if(date('Y-m-d',strtotime($project->project->timeline)) < '2022-04-01'){
					$persenppn = 0.1;
					$ppnpembagi = 1.1;
				}else{
					$persenppn = 0.11;
					$ppnpembagi = 1.11;
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
				
				foreach($detail as $key => $pp){
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
				if($adatile == true){
				@endphp
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
					@php
						if($adalain == true){
					@endphp
						<tr style="background:{{ $color }};text-align:center;">
							<th style="color:white;" colspan="17"><center>TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:{{ $color }};text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>SITE AREA</center></th>
						<th style="color:white;" rowspan="2"><center>CODE</center></th>
						<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
						@if($project->display_brand != 1)
						<th style="color:white;" rowspan="2"><center>BRAND</center></th>
						@endif
						<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
						<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
						<th style="color:white;" rowspan="2"><center>COLOR</center></th>
						<th style="color:white;" colspan="2"><center>VOL/CTN</center></th>
						<th style="color:white;" colspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>OUR PRICE {{ $project->project->ppn == '1' ? '(BEFORE TAX)' : '' }}</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:{{ $color }};text-align:center;">
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(Pcs)</th>
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
					</th>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($detail as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$countbox = ceil($pp->qty);
									$pp->best_price == 0 ? $total += ($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price) * $countbox : $total += $pp->best_price * $countbox;
									$pp->best_price == 0 ? $totaltile += ($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price) * $countbox : $totaltile += $pp->best_price * $countbox;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$countbox = ceil($pp->qty);
										$pp->best_price == 0 ? $total += ($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price) * $countbox : $total += $pp->best_price * $countbox;
										$pp->best_price == 0 ? $totaltile += ($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price) * $countbox : $totaltile += $pp->best_price * $countbox;
									}else{
										$countbox = ceil(round($pp->qty / $m2,2));
										$pp->best_price == 0 ? $total += round(($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price) * $m2, 0) * $countbox : $total += round($pp->best_price * $m2, 0) * $countbox;
										$pp->best_price == 0 ? $totaltile += round(($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price) * $m2, 0) * $countbox : $totaltile += round($pp->best_price * $m2, 0) * $countbox;
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
								<center>
									{{ $pp->area }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{{ $pp->product->type->image() }}" style="max-width:20px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
                           @if ($project->display_brand != 1)
                            <td style="vertical-align:center;">
								<center>
									{{ $pp->product->brand->name }}
								</center>
							</td>
							@else
							<td style="vertical-align:center;">
								<center>
							
								</center>
							</td>
                            @endif
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
									{{ $m2 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->carton_pcs }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->qty }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $countbox }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($pp->price > 0){
										   if($pp->best_price > 0 && $pp->best_price > $pp->price || $pp->recommended_price > 0 &&  $pp->recommended_price > $pp->price)
											{
											}else if($pp->best_price > 0  && $pp->best_price < $pp->price || $pp->recommended_price > 0  &&  $pp->recommended_price < $pp->price){
												echo '<span style="color:black">'.number_format($pp->price, 0, ',', '.').'</span><img src="website/strike_through.png" style="transform: rotate(-10deg); float: left; margin: -9px 0 7px 0" height="0.005px" width="40px" /><br>';
											}else{
												echo number_format($pp->price, 0, ',', '.').'<br>';
											}
										}
										
										if($pp->recommended_price > 0 && $pp->price !== $pp->recommended_price){
											if($pp->best_price > 0){
												echo '<span style="color:black">'.number_format($pp->recommended_price, 0, ',', '.').'</span><img src="website/strike_through.png" style="transform: rotate(-10deg); float: left; margin: -9px 0 7px 0" height="0.005px" width="40px" /><br>';
											}else{
												echo number_format($pp->recommended_price, 0, ',', '.').'<br>';
											}
										}
										
										$tempprice = [];
										
										if($pp->best_price > 0){
											if($project->revision > 1){
												foreach($project->project->projectQuotation->where('id','<',$project->id) as $pq2){
													foreach($pq2->projectQuotationProduct as $pqp2){
														if($pqp2->product_id == $pp->product_id){
															if($pqp2->best_price == 0){
																if($pqp2->recommended_price !== $pp->recommended_price && !in_array($pqp2->recommended_price,$tempprice) && count($tempprice) == 1){
																	echo '<span style="color:black">'.number_format($pqp2->recommended_price, 0, ',', '.').'</span><img src="website/strike_through.png" style="transform: rotate(-10deg); float: left; margin: -9px 0 7px 0" height="0.005px" width="40px" /><br>';
																}
																
																$tempprice[] = $pqp2->recommended_price;
															}else{
																if($pqp2->best_price !== $pp->best_price && !in_array($pqp2->best_price,$tempprice) && count($tempprice) == 1){
																	echo '<span style="color:black">'.number_format($pqp2->best_price, 0, ',', '.').'</span><img src="website/strike_through.png" style="transform: rotate(-10deg); float: left; margin: -9px 0 7px 0" height="0.005px" width="40px" /><br>';
																}
																$tempprice[] = $pqp2->best_price;
															}
														}
													}
												}
												if($pp->best_price == 0){
													echo number_format($pp->recommended_price, 0, ',', '.').'<br>';
												}else{
													echo number_format($pp->best_price, 0, ',', '.').'<br>';
												}
											}else{
												if($pp->best_price == 0){
													echo number_format($pp->recommended_price, 0, ',', '.').'<br>';
												}else{
													echo number_format($pp->best_price, 0, ',', '.').'<br>';
												}
											}
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo $pp->best_price == 0 ? number_format(($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price), 2, ',', '.') : number_format($pp->best_price, 2, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo $pp->best_price == 0 ? number_format(($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price), 2, ',', '.') : number_format($pp->best_price, 2, ',', '.');
											}else{
												echo $pp->best_price == 0 ? number_format(($pp->recommended_price == 0 ? $pp->price * $m2 : $pp->recommended_price * $m2), 2, ',', '.') : number_format(round($pp->best_price * $m2, 0), 2, ',', '.');
											}
										}
										
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo $pp->best_price == 0 ? number_format(($pp->recommended_price == 0 ? $pp->price * $countbox : $pp->recommended_price * $countbox), 0, ',', '.') : number_format($pp->best_price * $countbox, 0, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo $pp->best_price == 0 ? number_format(($pp->recommended_price == 0 ? $pp->price * $countbox : $pp->recommended_price * $countbox), 0, ',', '.') : number_format($pp->best_price * $countbox, 0, ',', '.');
											}else{
												echo $pp->best_price == 0 ? number_format(($pp->recommended_price == 0 ? round(($pp->price * $m2), 0) * $countbox : round(($pp->recommended_price * $m2),0) * $countbox), 0, ',', '.') : number_format(round((round($pp->best_price * $m2, 0)), 0) * $countbox, 0, ',', '.');
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
						<th colspan="{{$project->display_brand == 1 ? 9 : 10}}" style="text-align:right;">Total Qty</th>
						<th>{{ $totalqtytile }}</th>
						<th>{{ $totalqtybox }}</th>
						<th colspan="2">Subtotal</th>
						<th colspan="2">{{ number_format($totaltile, 0, ',', '.') }}</th>
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
						<tr style="background:{{ $color }};text-align:center;">
							<th style="color:white;" colspan="17"><center>NON-TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:{{ $color }};text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>SITE AREA</center></th>
						<th style="color:white;" rowspan="2"><center>CODE</center></th>
						<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
						@if($project->display_brand != 1)
						<th style="color:white;" rowspan="2"><center>BRAND</center></th>
						@endif
						<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
						<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
						<th style="color:white;" rowspan="2"><center>COLOR</center></th>
						<th style="color:white;" colspan="2" rowspan="2"><center>SPEC</center></th>
						<th style="color:white;" colspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>OUR PRICE {{ $project->project->ppn == '1' ? '(BEFORE TAX)' : '' }}</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:{{ $color }};text-align:center;">
						<th style="color:white;" colspan="2">{{$adacustom == true ? 'Unit' : 'PCS'}}</th>
						<th style="color:white;" colspan="2">{{$adacustom == true ? 'Unit' : 'PCS'}}</th>
					</tr>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($detail as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								$pp->best_price == 0 ? $total += ($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price) * $pp->qty : $total += $pp->best_price * $pp->qty;
								$pp->best_price == 0 ? $totallain += ($pp->recommended_price == 0 ? $pp->price : $pp->recommended_price) * $pp->qty : $totallain += $pp->best_price * $pp->qty;
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
									{{ $pp->area }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									<img src="{!! $pp->product->type->image() !!}" style="max-width:20px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
                            @if ($project->display_brand != 1)
                            <td style="vertical-align:center;">
								<center>
									{{ $pp->product->brand->name }}
								</center>
							</td>
							@else
							<td style="vertical-align:center;">
								<center>
							
								</center>
							</td>
                            @endif
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
							<td style="vertical-align:center;" colspan="2">
								<center>
									{{ $pp->spec() }}
								</center>
							</td>
							<td style="vertical-align:center;" colspan="2">
								<center>
									{{ $pp->qty }} {{ $adacustom == true ? $pp->unit() : ''}}
								</center>
							</td>
							<td style="vertical-align:center;" colspan="2">
								<center>
									@php
										if($pp->price > 0){
											if($pp->best_price > 0 || $pp->recommended_price > 0){
												echo '<span style="color:black">'.number_format($pp->price, 0, ',', '.').'</span><img src="website/strike_through.png" style="transform: rotate(-10deg); float: left; margin: -9px 0 7px 0" height="0.005px" width="40px" /><br>';
											}else{
												echo number_format($pp->price, 0, ',', '.').'<br>';
											}
										}
										
										if($pp->recommended_price > 0 && $pp->price !== $pp->recommended_price){
											if($pp->best_price > 0){
												echo '<span style="color:black">'.number_format($pp->recommended_price, 0, ',', '.').'</span><img src="website/strike_through.png" style="transform: rotate(-10deg); float: left; margin: -9px 0 7px 0" height="0.005px" width="40px" /><br>';
											}else{
												echo number_format($pp->recommended_price, 0, ',', '.').'<br>';
											}
										}
										
										$tempprice = [];
										if($pp->best_price > 0){
											if($project->revision > 1){
												foreach($project->project->projectQuotation->where('id','<',$project->id) as $pq2){
													foreach($pq2->projectQuotationProduct as $pqp2){
														if($pqp2->product_id == $pp->product_id){
															if($pqp2->best_price == 0){
																if($pqp2->price !== $pp->price && !in_array($pqp2->price,$tempprice) && count($tempprice) == 1){
																	echo '<span style="color:black">'.number_format($pqp2->price, 0, ',', '.').'</span><img src="website/strike_through.png" style="transform: rotate(-10deg); float: left; margin: -9px 0 7px 0" height="0.005px" width="40px" /><br>';
																}
																
																$tempprice[] = $pqp2->price;
															}else{
																if($pqp2->best_price !== $pp->best_price && !in_array($pqp2->best_price,$tempprice) && count($tempprice) == 1){
																	echo '<span style="color:black">'.number_format($pqp2->best_price, 0, ',', '.').'</span><img src="website/strike_through.png" style="transform: rotate(-10deg); float: left; margin: -9px 0 7px 0" height="0.005px" width="40px" /><br>';
																}
																
																$tempprice[] = $pqp2->best_price;
															}
														}
													}
												}
												if($pp->best_price == 0){
													echo number_format($pp->price, 0, ',', '.').'<br>';
												}else{
													echo number_format($pp->best_price, 0, ',', '.').'<br>';
												}
											}else{
												if($pp->best_price == 0){
													echo number_format($pp->price, 0, ',', '.').'<br>';
												}else{
													echo number_format($pp->best_price, 0, ',', '.').'<br>';
												}
											}
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->best_price == 0 ? number_format(($pp->recommended_price == 0 ? $pp->price * $pp->qty : $pp->recommended_price * $pp->qty), 0, ',', '.') : number_format($pp->best_price * $pp->qty, 0, ',', '.'); }}
								</center>
							</td>
						</tr>
						@php
								$no++;
							}
						@endphp
					@endforeach
					<tr>
						<th colspan="{{$project->display_brand == 1 ? 10 : 11}}" style="text-align:right;">Total Qty</th>
						<th>{{ $totalqtylain }}</th>
						<th colspan="2">Subtotal</th>
						<th colspan="2">{{ number_format($totallain, 0, ',', '.') }}</th>
					</tr>
				</tbody>
			</table>
				@php
				}
				@endphp
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" style="">
						<table cellpadding="2" cellspacing="0" border="1" style="background-color:{{ $color }};font-size:14px;color:white;">
							<tr>
								<td>
									<b>GRANDTOTAL</b>
								</td>
								<td style="text-align:center;">
							    	<b>IDR {{ $project->project->ppn == '1' ? number_format(($total + $project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost - $project->project->discount) + round($persenppn * ($total - $project->project->discount)) + round($persenppn * ($project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost)), 0, ',', '.') : number_format($total + $project->project->misc_cost + $project->project->cutting_cost - $project->project->discount + $project->project->delivery_cost, 0, ',', '.') }}</b>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									@if ($project->ppn_Cost == '1')
									<b><i>Nominal in words</i> : <br>{{ $project->project->ppn == '1' ? App\Helper\SMB::say(round(($total + $project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost - $project->project->discount) + round($persenppn * ($total - $project->project->discount)) + round($persenppn * ($project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost)))) : App\Helper\SMB::say($total + $project->project->misc_cost + $project->project->cutting_cost - $project->project->discount + $project->project->delivery_cost) }} </b>
									@else
									<b><i>Nominal in words</i> : <br>{{ $project->project->ppn == '1' ? App\Helper\SMB::say(round(($total + $project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost - $project->project->discount) + round($persenppn * ($total - $project->project->discount)))) : App\Helper\SMB::say($total + $project->project->misc_cost + $project->project->cutting_cost - $project->project->discount + $project->project->delivery_cost) }} </b>
									@endif
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<b><i>Payment will be transferred to</i></b> :
									@if($project->project->user->branch == '2')
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
											@if($project->project->coa_id == 380)
    											<p>
    												<br>BCA 3293-678-789
    												<br>a/n PT. Perwira Sejati Internusa
    												<br>Cab. Baliwerti - Surabaya<br>
    											</p>
    										@else
											<p>
												<br>BCA 468-3434-178
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Indrapura - Surabaya<br>

												<br><i>Atau</i><br>

												<br>Mandiri 14-000-5996-5997
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Kembang Jepun - Surabaya
											</p>
											@endif
										@else
											@if($project->project->coa_id == 397)
											<p>
												<br>Mandiri 14.000.23.000.673
												<br>a/n Prawiro Tedjo Tjandra
												<br>Cab. Kembang Jepun - Surabaya<br>
											</p>
										@elseif($project->project->coa_id == 380)
											<p>
												<br>BCA 3293-678-789
												<br>a/n PT. Perwira Sejati Internusa
												<br>Cab. Baliwerti - Surabaya<br>
											</p>
										@else
											<p>
												<br>BCA 329.0258.510
												<br>a/n Andy Hidayat
												<br>Cab. Baliwerti<br>
											</p>
											@endif
										@endif
									@endif
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
									<h6>IDR {{ number_format($total, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@php
							$costs = [
								'CUTTING COST'  => $project->project->cutting_cost,
								'MISC COST' 	=> $project->project->misc_cost,
								'DELIVERY COST' => $project->project->delivery_cost,
								'DISCOUNT'		=> $project->project->discount // Discount as negative for clarity
							];

							$totalAfterDiscount = $total + ($project->project->cutting_cost + $project->project->misc_cost + $project->project->delivery_cost) - $project->project->discount;
							@endphp

							@foreach ($costs as $label => $value)
								@if ($value > 0)
									<tr>
										<td>
											<h6>{{ $label }}</h6>
										</td>
										<td>
											<h6>IDR {{ number_format($value, 0, ',', '.') }}</h6>
										</td>
									</tr>
								@endif
							@endforeach
							@if ($project->project->discount > 0)
							<tr>
								<td>
									<h6>TOTAL AFTER DISCOUNT</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($totalAfterDiscount, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@endif
							<tr>
								<td>
									<h6>TAX</h6>
								</td>
								<td>
									<h6>IDR {{ $project->project->ppn == '1' ? number_format(round($persenppn * ($total - $project->project->discount)), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@if ($project->ppn_cost == '1')
							<tr>
								<td>
									<h6>Services TAX</h6>
								</td>
								<td>
									<h6>IDR {{ $project->ppn_cost == '1' ? number_format(round($persenppn * ($project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost)), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@endif
							<tr>
								<td>
									<h6>GRANDTOTAL</h6>
								</td>
								@if ($project->ppn_cost == '1')
								<td>
								 <h6>IDR {{ $project->project->ppn == '1' ? 
									number_format(
										($total + $project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost - $project->project->discount) + round($persenppn * ($total - $project->project->discount)) + round($persenppn * ($project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost)), 0, ',', '.') : number_format($total + $project->project->misc_cost + $project->project->cutting_cost - $project->project->discount + $project->project->delivery_cost, 0, ',', '.') }}</h6>
								</td>
								 @else
								 <td>
									<h6>IDR {{ $project->project->ppn == '1' ? 
									   number_format(
										   ($total + $project->project->misc_cost + $project->project->cutting_cost + $project->project->delivery_cost - $project->project->discount) + round($persenppn * ($total - $project->project->discount)), 0, ',', '.') : number_format($total + $project->project->misc_cost + $project->project->cutting_cost - $project->project->discount + $project->project->delivery_cost, 0, ',', '.') }}</h6>
								   </td>
								 @endif
							</tr>
						</table>
						<table cellpadding="2" cellspacing="0" border="1" style="margin-top:20px;color:red;">
							<tr>
								<td>
									<h6><i>THIS QUOTATION IS VALID UNTIL : {{ strtoupper(date('d F Y', strtotime($project->created_at . ' + 30 days'))) }}</i></h6>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="padding-top:10px;">
						<h6 style="font-size:10px; text-align:center;"><u>TERMS & CONDITIONS</u> :</h6>
						<p style="font-size:10px;">
							@if (isset($project->terms_cons_en))
								{!! $project->terms_cons_en !!}
							@else
							<ol>
								<li>We only supply 1st quality goods</li>
								@if($project->project->ppn == '1')
								<li>Grandtotal already include {{ $persenppn * 100 }}% VAT.</li>
								@endif
								<li>Prices already included franco to {{ $project->project->city_franco ? $project->project->city_franco->name : '' }} (On Truck).</li>
								<li>Normal delivery 3-7 days. If the items are not ready, please wait within 8-24 weeks.</li>
								<li>Payment {!! $project->project->paymentMethod() !!} - {!! $project->project->paymentTerm() !!}.</li>
								<li>Prices do not include installation.</li>
							</ol>
							@endif
						</p>
						<br>
						<p style="font-size:10px;">
							@if (isset($project->project->sales))
							We hope that we can work together in supplying the material of your project. 
							<br>For further information, please contact our sales person - {{ $project->project->sales->name.' - '.$project->project->sales->phone }}. 
							<br>Thank you for your time.
							@else
							We hope that we can work together in supplying the material of your project. 
							<br>For further information, please contact our sales person - {{ $project->project->user->name.' - '.$project->project->user->phone }}. 
							<br>Thank you for your time.
							@endif
							
						</p>
					</td>
				</tr>
			</table><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Checked By</div>
						<br><br><br>
						<div style="font-size:10px;">(..................................)</div>
						<div style="font-size:10px;">Customer</div>
					</td>
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Created By</div>
						@if (isset($project->project->sales))
						@if($project->project->sales->sign)
						<div><img src="{{ url(Storage::url($project->project->sales->sign)) }}" height="65px"></div>
						@else
							<br>
						@endif
						<div style="font-size:10px;">{{ $project->project->sales->name }}</div>
						<div style="font-size:10px;">{{ $project->project->sales->userRole->first()->role() }}</div>
						@else
						@if($project->project->user->sign)
						<div><img src="{{ url(Storage::url($project->project->user->sign)) }}" height="65px"></div>
						@else
							<br>
						@endif
						<div style="font-size:10px;">{{ $project->project->user->name }}</div>
						<div style="font-size:10px;">{{ $project->project->user->userRole->first()->role() }}</div>
						@endif
					</td>
					@if(isset($project->approved_1->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Approved By</div>
						@if($project->approved_1->sign)
							<div><img src="{{ url(Storage::url($project->approved_1->sign)) }}" height="65px"></div>
						@else
							<br>
						@endif
						<div style="font-size:10px;">{{ $project->approved_1->name }}</div>
						<div style="font-size:10px;">{{ $project->approved_1->userRole->first()->role() }}</div>
					</td>
					@endif
					@if(isset($project->approved_2->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Approved By</div>
						@if($project->approved_2->sign)
							<div><img src="{{ url(Storage::url($project->approved_2->sign)) }}" height="65px"></div>
						@else
							<br>
						@endif
						<div style="font-size:10px;">{{ $project->approved_2->name }}</div>
						@if($project->project->user->branch == '1')
							<div style="font-size:10px;">{{ $project->approved_2->userRole->first()->role() }}</div>
						@else
							<div style="font-size:10px;">Sales Manager</div>
						@endif
					</td>
					@endif
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:10px;">
				<tr>
					<td style="text-align:right;color:black;font-size:10px;">
						Created at : {{ $project->created_at }}
					</td>
				</tr>
			</table><br>
		</div>
		<div class="separate-box">
			<h3>Blow Up Material</h3>
			<table border="1" cellpadding="5" cellspacing="0" style="font-size:10px;">
				@php
				if($adatile == true){
				@endphp
				<tbody>
					<tr>
					@foreach($detail as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								if((($key + 1) % 4) == 1){
									echo '<tr>';
								}
						@endphp
							<td>
								<img src="{{ $pp->product->type->image() }}" style="max-width:200px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								<center style="font-size:15px;">
									{{ $pp->product->type->code }}
								</center>
							</td>
						@php
								if((($key + 1) % 4) == 1){
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
					@foreach($project->projectQuotationProduct as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								if(($no % 4) == 1){
									echo '<tr>';
								}
						@endphp
							<td>
								<img src="{{ $pp->product->type->image() }}" style="max-width:200px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								<center>
									{{ $pp->product->type->code }}
								</center>
							</td>
						@php
								if(($no % 4) == 1){
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
	</body>
</html>