@php
	if(date('Y-m-d',strtotime($project->created_at)) < '2022-04-01'){
		$persenppn = 0.1;
		$ppnpembagi = 1.1;
	}else{
		$persenppn = 0.11;
		$ppnpembagi = 1.11;
	}
@endphp

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Purchase Order {{ $project->code }}</title>
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
				@if($project->currency_id !== '5')
				page-break-after: always;
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
			
			@page { margin: 1cm; }
			body { margin: 1cm; }
		</style>
	</head>
	<body>
		<div class="invoice-box">
			@if($project->supplier_id != 181)
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
			@else
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2" style="width: 100vh; vertical-align: middle;padding-top:15px;padding-bottom:15px;">
						<center>
							<img src="{{ url('website/LETTERHEADpsi.jpg') }}" width="100%">
						</center>
					</td>
				</tr>
			</table>
			@endif
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table>
							<tr>
								<td width="100%">
									<table style="border: 1px solid #0b95b8;border-collapse: collapse;">
										<tr>
											<td style="background-color:#0b95b8;text-align:center;color:white;padding-top:10px;padding-bottom:10px;"><h3><b>REQUEST QUOTATION</b></h3></td>
										</tr>
									</table>
                                    <td width="55%">
									<table style="border: 1px solid black;border-collapse: collapse;">
										<tr>
											<td width="30%" style="font-size:10px;">Date of RQ</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ date('d F Y', strtotime($project->created_at)) }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">RQ No.</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $project->code }}</b></td>
										</tr>
										<tr>
											<td width="30%" style="font-size:10px;">Sales Person</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $project->sales ? $project->sales->name : '-' }}</b></td>
										</tr>
                                        <tr>
											<td width="30%" style="font-size:10px;">Note</td>
											<td style="text-align:left; font-size:10px;">: <b>{{ $project->note ? $project->note : '-' }}</b></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<p style="font-size:10px;">Our dear Supplier,
			<br>we would like to request a quote from your company. Please inform us price and stock availability for products below.
			</p>
			@php
				$adatile = false;
				$adalain = false;
				foreach($project->projectPurchaseProduct as $key => $pp){
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
					<thead>
						<tr style="background:#0b95b8;text-align:center;">
							<th style="color:white;" colspan="13"><center>DETAIL OF REQUEST QUOTATION</center></th>
						</tr>
					</thead>
					<thead>
						<tr style="background:#0b95b8;text-align:center;">
							<th style="color:white;" colspan="13"><center>TILES</center></th>
						</tr>
						<tr style="background:#0b95b8;text-align:center;">
							<th style="color:white;"><center>NO</center></th>
							<th style="color:white;"><center>CODE</center></th>
							<th style="color:white;"><center>PRODUCT</center></th>
							<th style="color:white;"><center>SIZE(cm)</center></th>
							<th style="color:white;"><center>CATEGORY</center></th>
							<th style="color:white;"><center>COLOR</center></th>
							<th style="color:white;"><center>SHADE</center></th>
							<th style="color:white;"><center>FINISH</center></th>
							<th style="color:white;"><center>QTY</center></th>
							<th style="color:white;"><center>PRICE/M<sup>2</sup></center></th>
						</tr>
					</thead>
					<tbody>
						@foreach($project->projectPurchaseProduct as $key => $pp)
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
										<img src="{{ $pp->product->type->image() }}" style="max-width:10px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
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
										?
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
										?
									</center>
								</td>
							</tr>
							@php
									$no++;
								}
							@endphp
						@endforeach
					</tbody>
				</table>
				@php
				if($adalain == true){
				@endphp
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
					<thead>
						<tr style="background:#0b95b8;text-align:center;">
							<th style="color:white;" colspan="13"><center>NON-TILES</center></th>
						</tr>
						<tr style="background:#0b95b8;text-align:center;">
							<th style="color:white;"><center>NO</center></th>
							<th style="color:white;"><center>CODE</center></th>
							<th style="color:white;"><center>PRODUCT</center></th>
							<th style="color:white;"><center>SIZE(cm)</center></th>
							<th style="color:white;"><center>CATEGORY</center></th>
							<th style="color:white;"><center>COLOR</center></th>
							<th style="color:white;"><center>SHADE</center></th>
							<th style="color:white;"><center>FINISH</center></th>
							<th style="color:white;"><center>TOTAL(pcs)</center></th>
							<th style="color:white;"><center>PRICE/PCS</center></th>
						</tr>
					</thead>
					<tbody>
						@foreach($project->projectPurchaseProduct as $key => $ppku)
							@php
								if($ppku->unit == '1' || $ppku->unit == '4'){
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
										<img src="{{ $ppku->product->type->image() }}" style="max-width:10px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
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
										?
									</center>
								</td>
								<td style="vertical-align:center;">
									<center>
										?
									</center>
								</td>
							</tr>
							@php
									$no++;
								}
							@endphp
						@endforeach
					</tbody>
				</table>
				@php
				}
				@endphp
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;">
						<div style="font-size:10px;">RQ released by</div>
						@if(isset($project->user->sign))
							<div><img src="{{ url(Storage::url($project->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $project->user->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $project->user->userRole->first()->role() }}</div>
					</td>
					@if(isset($project->approved->name))
					<td style="text-align:center;">
						<div style="font-size:10px;">Approved By</div>
						@if(isset($project->approved->sign))
							<div><img src="{{ url(Storage::url($project->approved->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;font-weight:700;">{{ $project->approved->name }}</div>
						<div style="font-size:10px;font-weight:700;">{{ $project->approved->userRole->first()->role() }}</div>
					</td>
					@endif
					<td style="text-align:center;">
						<div style="font-size:10px;">RQ accepted by</div>
						<br><br><br>
						<div style="font-size:10px;font-weight:700;">(........................)</div>
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