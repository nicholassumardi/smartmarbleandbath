<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sales Return {{ $sample->code }}</title>
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
							<tr style="background-color:#51b6bc;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h4><b>SALES RETURN</b></h4>
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
								<td width="20%" style="font-size:12px;">Sales No.</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->sample->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">Customer</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->sample->customerSample->customer->name }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">Sales Return</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">Date</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y',strtotime($sample->created_at)) }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">PIC</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->sample->customerSample->manager }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">Address</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->address }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;"></td>
								<td style="text-align:left; font-size:12px;"></td>
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
				$persenppn = 0;
				
				if($sample->customerSample->ppn == '1'){
					if(date('Y-m-d',strtotime($sample->sample->created_at)) < '2022-04-01'){
						$persenppn = 0.1;
					}else{
						$persenppn = 0.11;
					}
				}
				
				$adatile = false;
				$adalain = false;
				$total = 0;
				$totaltile = 0;
				$totallain = 0;
				
				$qtysentbox = 0;
				$qtysentlain = 0;
				
				foreach($sample->sampleReturnProduct as $key => $pp){
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
						<tr style="background:#51b6bc;text-align:center;">
							<th style="color:white;" colspan="11"><center>TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#51b6bc;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>CODE</center></th>
						<th style="color:white;"><center>PICTURE</center></th>
						<th style="color:white;"><center>BRAND</center></th>
						<th style="color:white;"><center>SIZE(cm)</center></th>
						<th style="color:white;"><center>CATEGORY</center></th>
						<th style="color:white;"><center>COLOR</center></th>
						<th style="color:white;"><center>QTY</center></th>
						<th style="color:white;"><center>UNIT</center></th>
						<th style="color:white;"><center>NOMINAL</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->sampleReturnProduct as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								$qtysentbox += $pp->qty;
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
									{{ $pp->unit() }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										foreach($sample->sample->sampleProduct->where('product_id',$pp->product_id) as $psp){
											if($psp->unit == '2' || $psp->unit == '3'){
												$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
												if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
													echo number_format($psp->best_price * $pp->qty,0,',','.');
													$total += $psp->best_price * $pp->qty;
												}else{
													if($m2 < 1.1 && date('Y-m',strtotime($sample->sample->customerSample->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
														echo number_format($psp->best_price * $pp->qty,0,',','.');
														$total += $psp->best_price * $pp->qty;
													}else{
														echo number_format($psp->best_price * $pp->qty * $m2,0,',','.');
														$total += $psp->best_price * $pp->qty * $m2;
													}
												}
												
											}else{
												echo number_format($psp->best_price * $pp->qty,0,',','.');
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
				</tbody>
				@php
				}
				if($adalain == true){
				@endphp
				<thead>
					@php
						if($adatile == true){
					@endphp
						<tr style="background:#51b6bc;text-align:center;">
							<th style="color:white;" colspan="11"><center>NON-TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#51b6bc;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>CODE</center></th>
						<th style="color:white;"><center>PICTURE</center></th>
						<th style="color:white;"><center>BRAND</center></th>
						<th style="color:white;"><center>SIZE(cm)</center></th>
						<th style="color:white;"><center>CATEGORY</center></th>
						<th style="color:white;"><center>COLOR</center></th>
						<th style="color:white;"><center>QTY</center></th>
						<th style="color:white;"><center>UNIT</center></th>
						<th style="color:white;"><center>NOMINAL</center></th>
					</tr>
				</thead>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($sample->sampleReturnProduct as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								$qtysentlain += $pp->qty;
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
									{{ $pp->qty }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->unit() }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										foreach($sample->sample->sampleProduct->where('product_id',$pp->product_id) as $psp){
											echo number_format($psp->best_price * $pp->qty,0,',','.');
											$total += $psp->best_price * $pp->qty;
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
				</tbody>
				@php
				}
				@endphp
				<tfoot>
					<tr>
						<td style="vertical-align:center;text-align:right;" colspan="7">
							Total Qty
						</td>
						<td style="vertical-align:center;text-align:center;">
							{{ $qtysentbox + $qtysentlain }}
						</td>
						<td style="vertical-align:center;text-align:right;">
							Total
						</td>
						<td style="vertical-align:center;text-align:right;">
							Rp {{ number_format($total,0,',','.') }}
						</td>
					</tr>
					<tr>
						<td style="vertical-align:center;text-align:right;" colspan="9">
							PPN ({{ $persenppn * 100 }}%)
						</td>
						<td style="vertical-align:center;text-align:right;">
							Rp {{ number_format($total * $persenppn,0,',','.') }}
						</td>
					</tr>
					<tr>
						<td style="vertical-align:center;text-align:right;" colspan="9">
							Grandtotal
						</td>
						<td style="vertical-align:center;text-align:right;">
							Rp {{ number_format(($total * $persenppn) + $total,0,',','.') }}
						</td>
					</tr>
				</tfoot>
			</table>
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="45%"></td>
					<td width="10%"></td>
					<td width="45%"></td>
				</tr>
				<tr>
					<td colspan="3" style="padding-top:10px;">
						<h6 style="font-size:10px; text-align:center;">Note :</h6>
						<p style="font-size:10px;">
							{{ $sample->note }}
						</p>
					</td>
				</tr>
			</table><br><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;">
						<div style="font-size:10px;">Created by</div>
						<div style="font-size:10px;"></div>
						@if(isset($sample->user->sign))
							<div><img src="{{ url(Storage::url($sample->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $sample->user->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $sample->user->userRole->first()->role()}}</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Approved by</div>
						<div style="font-size:10px;"></div>
						@if(isset($sample->approve->sign))
							<div><img src="{{ url(Storage::url($sample->approve->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ isset($sample->approve->name) ? $sample->approve->name : '' }}</div>
						<div style="font-size:10px;font-weight:700;">{{ isset($sample->approve->name) ? $sample->approve->userRole->first()->role() : '' }}</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Customer sign</div>
						<div style="font-size:10px;"></div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">{{ $sample->sample->customerSample->customer->name }}</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Warehouse sign</div>
						<div style="font-size:10px;"></div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(.........................)</div>
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
	</body>
</html>