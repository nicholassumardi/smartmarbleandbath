<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Delivery Order {{ $sample->code }}</title>
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
				background: #cf9604;
				border-bottom: 1px solid #cf9604;
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
			
			@page { margin: 1cm; }
			body { margin: 1cm; }
		</style>
	</head>
	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						@php
							if($sample->is_dropshipper == '1'){
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
									<div style="	font-size:9px; font-weight:500;">Phone : 031-5472860 / 031-5324505</div>
									<div style="font-size:9px; font-weight:500;">Email : info@smartmarbleandbath.com</div>
								</td>
							</tr>
						</table>
						@php
							}elseif($sample->is_dropshipper == '2'){
						@endphp
						<table>
							<tr>
								<td class="title" rowspan="2">
									@php
										if($sample->dropshipper->image !== ''){
											echo '<img src="'.$sample->dropshipper->image().'" height="75">';
										}
									@endphp
								</td>
								<td colspan="2" style="text-align:right;"><h3>{{ strtoupper($sample->dropshipper->name) }}</h3></td>
							</tr>
							<tr>
								<td></td>
								<td style="text-align:right;">
									<div style="font-size:9px; font-weight:bold;">{{ $sample->dropshipper->address }}</div>
									<div style="font-size:9px; font-weight:500;">Phone : {{ $sample->dropshipper->phone }}</div>
									<div style="font-size:9px; font-weight:500;">Email : {{ $sample->dropshipper->email }}</div>
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
							if($sample->is_dropshipper == '1'){
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
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr style="background-color:#cf9604;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>DELIVERY ORDER</b></h3>
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
								<td style="text-align:left; font-size:12px;">{{ date('d F Y', strtotime($sample->delivery_date)) }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">DO. Number</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->code }}</td>
							</tr>
							@php
								if($sample->is_dropshipper == '1'){
							@endphp
							<tr>
								<td width="40%" style="font-size:12px;">SO. Number</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->sample->code }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Sample</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->sample->note }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Warehouse</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->warehouse->name }}</td>
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
								<td style="text-align:left; font-size:12px;">{{ $sample->receiver_name }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Phone</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->phone }}</td>
							</tr>
							<!-- <tr>
								<td width="40%" style="font-size:12px;">Expedition</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->vendor->name }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Email</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->email }}</td>
							</tr> -->
							<tr>
								<td width="40%" style="font-size:12px;">City</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->city->name }}</td>
							</tr>
							<tr>
								<td width="40%" style="font-size:12px;">Address</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->address }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			@php
				$adatile = false;
				$adalain = false;
				$total = 0;
				$totaltile = 0;
				$totallain = 0;
				foreach($sample->sampleDeliveryProduct as $key => $pp){
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
				$no = 1;
				if($adatile == true){
					$qtytot = 0;
					$qtym2 = 0;
				@endphp
				<thead>
					<tr style="background:#cf9604;text-align:center;">
						<th style="color:white;" colspan="9"><center>TILES</center></th>
					</tr>
					<tr style="background:#cf9604;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>NAME</center></th>
						<th style="color:white;"><center>PICTURE</center></th>
						<th style="color:white;"><center>BRAND</center></th>
						<th style="color:white;"><center>FINISHING</center></th>
						<th style="color:white;"><center>SHADE</center></th>
						<th style="color:white;"><center>SIZE(cm)</center></th>
						<th style="color:white;"><center>PCS/BOX</center></th>
						<th style="color:white;"><center>QTY DELIVERED</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->sampleDeliveryProduct as $key => $ps)
						@php
							if($ps->unit == '2' || $ps->unit == '3'){
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
								<center>
									<img src="{{ $ps->product->type->image() }}" style="max-width:25px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->brand->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->type->surface->name }}
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
								</center>
							</td>
						</tr>
						@php
								$totaltile += round($ps->qty,0);
								$no++;
							}
						@endphp
					@endforeach
					<tr>
						<td style="vertical-align:center;text-align:right;" colspan="8">
							Total Qty
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $totaltile }} Box
							</center>
						</td>
					</tr>
				</tbody>
				@php
				}
				if($adatile == true && $adalain == true){
				@endphp
				<thead>
					<tr>
						<th style="color:white;" colspan="11"> ... </th>
					</tr>
				</thead>
				@php
				}
				if($adalain == true){
					$qtytot = 0;
				@endphp
				<thead>
					<tr style="background:#cf9604;text-align:center;">
						<th style="color:white;" colspan="7"><center>NON-TILES</center></th>
					</tr>
					<tr style="background:#cf9604;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>NAME</center></th>
						<th style="color:white;"><center>PICTURE</center></th>
						<th style="color:white;"><center>BRAND</center></th>
						<th style="color:white;"><center>FINISHING</center></th>
						<th style="color:white;"><center>SHADE</center></th>
						<!-- <th style="color:white;"><center>SIZE(mm)</center></th> 
						<th style="color:white;"><center>PCS/BOX</center></th>-->
						<th style="color:white;"><center>QTY DELIVERED</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->sampleDeliveryProduct as $key => $ps)
						@php
							if($ps->unit == '1'){
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
								<center>
									<img src="{{ $ps->product->type->image() }}" style="max-width:25px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->brand->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->type->surface->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
									{{ $ps->shading }}
							</td>
							@foreach($ps->sampleDelivery->sample->sampleProduct as $val)
							@if ($val->product_id == $ps->product_id) {
								<td style="vertical-align:center;">
								<center>
									{{ $val->size() }}
								</center>
							</td> 
							@endif
							@endforeach
							<!-- <td style="vertical-align:center;">
								<center>
									{{ $ps->product->type->length }}x{{ $ps->product->type->width }}
								</center>
							</td> 
							<td style="vertical-align:center;">
								<center>
									{{ $ps->product->carton_pcs }}
								</center>
							</td>-->
							<td style="vertical-align:center;">
								<center>
									{{ round($ps->qty,0).' '.$ps->unit() }}
								</center>
							</td>
						</tr>
						@php
								$totallain += round($ps->qty,0);
								$no++;
							}
						@endphp
					@endforeach
					<tr>
						<td style="vertical-align:center;text-align:right;" colspan="6">
							Total Qty
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ $totallain }} Pcs
							</center>
						</td>
					</tr>
				</tbody>
				@php
				}
				@endphp
			</table><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<h6 style="font-size:10px; text-align:center;">Notes :</h6>
						<div></div>
						<div style="font-size:10px; text-align:left;">
							<ol>
								<li>Goods are sent according to the address on the delivery order.</li>
								<li>When goods are received, please check it immediately. If there is no claim, then Delivery Order considered finished.</li>
								<li>Signature must be completed by name and stamp.</li>
							</ol>
						</div>
					</td>
				</tr>
				<tr>
					<td style="font-size:10px;" class="pt-3">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td style="font-size:10px;">
						
					</td>
				</tr>
			</table><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					@php
						if($sample->is_dropshipper == '1'){
					@endphp
					<td style="text-align:center;">
						<div style="font-size:10px;">Created by</div>
						@if($sample->user->sign)
							<div><img src="{{ url(Storage::url($sample->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">( {{ isset($sample->user->name) ? $sample->user->name : '' }} )</div>
						<div style="font-size:10px;font-weight:700;">{{ isset($sample->user->name) ? $sample->user->userRole->first()->role() : '' }}</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Acknowledged By</div>
						<div><img src="{{ url(Storage::url(App\Models\User::find(4)->sign)) }}" height="65px"></div>
						<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(4)->name }} )</div>
						<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(4)->userRole->first()->role() }} )</div>
					</td>
					@php
						}
					@endphp
					@php
						if($sample->is_dropshipper == '1'){
					@endphp
					<td style="text-align:center;">
						<div style="font-size:10px;">Approved By</div>
						<div><img src="{{ url(Storage::url(App\Models\User::find(7)->sign)) }}" height="65px"></div>
						<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(7)->name }} )</div>
						<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(7)->userRole->first()->role() }} )</div>
					</td>
					@php
						}
					@endphp
					<td style="text-align:center;">
						<div style="font-size:10px;">Warehouse</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
					</td>
					
					<td style="text-align:center;">
						<div style="font-size:10px;">Sent by</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Received by</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
					</td>
					
				</tr>
				<tr>
					<td colspan="7" style="text-align:center;padding-top:50px !important;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7" style="text-align:center;padding-top:50px !important;font-size:10px;">Printed on : {{ date('Y-m-d H:i:s') }}</td>
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