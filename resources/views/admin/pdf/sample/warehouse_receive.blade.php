<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Warehouse Receive {{ $sample->code }}</title>
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
							<tr style="background-color:#b51405;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>WAREHOUSE RECEIVE (LPB)</b></h3>
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
								<td width="20%" style="font-size:12px;">PO Number</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->samplePurchase->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">Shipment Code</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->sampleShipment->shipment_code }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">WR Number</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">ETA</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->sampleShipment->eta }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">Receiver Name</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->person }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">From Port</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->sampleShipment->from_port }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">Date Receive</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->date_receive }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">To Port</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->sampleShipment->to_port }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">Warehouse</td>
								<td style="text-align:left; font-size:12px;">: {{ $sample->warehouse->name }}</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
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
				$qtysentbox = 0;
				$qtysentlain = 0;
				
				foreach($sample->sampleWarehouseProduct as $key => $pp){
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
						<tr style="background:#b51405;text-align:center;">
							<th style="color:white;" colspan="10"><center>TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#b51405;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>CODE</center></th>
						<th style="color:white;"><center>PICTURE</center></th>
						<th style="color:white;"><center>BRAND</center></th>
						<th style="color:white;"><center>SIZE(cm)</center></th>
						<th style="color:white;"><center>CATEGORY</center></th>
						<th style="color:white;"><center>COLOR</center></th>
						<th style="color:white;"><center>QTY</center></th>
						<th style="color:white;"><center>UNIT</center></th>
						<th style="color:white;"><center>BROKEN</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->sampleWarehouseProduct as $key => $pp)
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
									{{ $pp->qty_broken }}
								</center>
							</td>
						</tr>
						@php
							}
						@endphp
					@endforeach
					<tr>
						<th style="text-align:right;" colspan="7">Total Qty</th>
						<th style="text-align:center;">{{ $qtysentbox }}</th>
						<th style="text-align:right;" colspan="2"></th>
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
						<tr style="background:#b51405;text-align:center;">
							<th style="color:white;" colspan="10"><center>NON-TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#b51405;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>CODE</center></th>
						<th style="color:white;"><center>PICTURE</center></th>
						<th style="color:white;"><center>BRAND</center></th>
						<th style="color:white;"><center>SIZE(cm)</center></th>
						<th style="color:white;"><center>CATEGORY</center></th>
						<th style="color:white;"><center>COLOR</center></th>
						<th style="color:white;"><center>QTY</center></th>
						<th style="color:white;"><center>UNIT</center></th>
						<th style="color:white;"><center>BROKEN</center></th>
					</tr>
				</thead>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($sample->sampleWarehouseProduct as $key => $pp)
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
									{{ $pp->qty_broken }}
								</center>
							</td>
						</tr>
						@php
								$no++;
							}
						@endphp
					@endforeach
					<tr>
						<th style="text-align:right;" colspan="7">Total Qty</th>
						<th style="text-align:center;">{{ $qtysentlain }}</th>
						<th style="text-align:right;" colspan="2"></th>
					</tr>
				</tbody>
				@php
				}
				@endphp
			</table>
			<br><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;">
						<div style="font-size:10px;">Created by</div>
						<div style="font-size:10px;">PT. Perwira Tamaraya Abadi</div>
						@if(isset($sample->user->sign))
							<div><img src="{{ url(Storage::url($sample->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{!! $sample->user->name !!}</div>
					</td>
					<td style="text-align:center;">
						<div style="font-size:10px;">Receiver Name</div>
						<div style="font-size:10px;">PT. Perwira Tamaraya Abadi</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">{!! $sample->person !!}</div>
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