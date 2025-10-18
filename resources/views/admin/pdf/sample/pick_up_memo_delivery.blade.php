<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Pick-up Memo {{ $sample->code }}</title>
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:14px;
			}
		
			.invoice-box {
				font-size: 16px;
				font-family: font-family: 'Lato', sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
			}

			.invoice-box table td {
				vertical-align: top;
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

			.invoice-box.rtl {
				direction: rtl;
				font-family: 'Lato', sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}
			
			@page { margin: 1cm 1.5cm 1cm 1.5cm; }
			body { 
				margin: 1cm 1.5cm 1cm 1.5cm; 
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
								{{ $sample->sample->sales->branch == '1' ? 'Surabaya' : 'Jakarta' }}, {{ date('d M Y',strtotime($sample->created_at)) }}
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
									<h2><b>MEMO PENGAMBILAN BARANG</b></h2>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table width="100%">
				<tr>
					<td>
						@php
							$adatile = false;
							$adalain = false;
							foreach($sample->sampleDeliveryProduct as $key => $pp){
								if($pp->unit == '2' || $pp->unit == '3'){
									$adatile = true;
								}
								if($pp->unit == '1' || $pp->unit == '4'){
									$adalain = true;
								}
							}
						@endphp
						<br>
						Dengan Hormat,
						<br><br>
						Berikut saya buat memo pengambilan barang untuk mengambil barang ke PT Modern Keramik Jaya, dengan detail sebagai berikut:
						<br><br>
						<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
							@php
							$no = 1;
							if($adatile == true){
							@endphp
							<thead>
								<tr style="text-align:center;">
									<th colspan="6"><center>TILES</center></th>
								</tr>
								<tr style="text-align:center;">
									<th><center>No</center></th>
									<th><center>Type</center></th>
									<th><center>Category</center></th>
									<th><center>Ukuran(cm)</center></th>
									<th><center>Isi Per Dos</center></th>
									<th><center>Qty (box)</center></th>
								</tr>
							</thead>
							<tbody>
								@foreach($sample->sampleDeliveryProduct as $key => $pp)
									@php
										if($pp->unit == '2' || $pp->unit == '3'){
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
												{{ $pp->product->type->category->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->carton_pcs }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ number_format($pp->qty,2,',','.') }}
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
							if($adatile == true && $adalain == true){
							@endphp
							<thead>
								<tr>
									<th colspan="6"> ... </th>
								</tr>
							</thead>
							@php
							}
							if($adalain == true){
							@endphp
							<thead>
								<tr style="text-align:center;">
									<th colspan="6"><center>NON-TILES</center></th>
								</tr>
								<tr style="text-align:center;">
									<th><center>No</center></th>
									<th><center>Type</center></th>
									<th><center>Category</center></th>
									<th><center>Ukuran(cm)</center></th>
									<th><center>Isi Per Dos</center></th>
									<th><center>Qty (box)</center></th>
								</tr>
							</thead>
							<tbody>
								@foreach($sample->sampleDeliveryProduct as $key => $ppku)
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
												{{ $pp->product->type->category->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $ppku->product->type->length }}x{{ $ppku->product->type->width }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $ppku->product->carton_pcs }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ number_format($ppku->qty,0,',','.') }}
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
						</table>
						<br>
						<b>Dengan detail informasi supir sebagai berikut :
						<br>
						Nama : {{ $sample->pick_up_name }}
						<br>
						Nopol : {{ $sample->pick_up_plat }}
						<br>
						Jenis Kendaraan : {{ $sample->pick_up_vehicle }}
						<br>
						Alamat Pengambilan Barang : PERGUDANGAN BUMI MASPION BLOK C9 NO. 61-69  ROMOKALISARI, GRESIK, INDONESIA
						<br>
						</b>
						<br><br>
						Demikian memo ini saya buat. Atas perhatiannya, saya sampaikan terima kasih.
					</td>
				</tr>
			</table>
			<br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;" width="33%">
					</td>
					<td style="text-align:center;" width="33%">
					</td>
					<td style="text-align:center;background-image: url({{ url("website/stempel_pta_baru_small_1.png") }});background-size:12px;background-repeat:no-repeat;background-position: center center;" width="33%">
						<div style="font-size:10px;text-align:left;">Hormat saya,</div>
						<div style="font-size:10px;text-align:left;">PT Perwira Tamaraya Abadi</div>
						@if(isset($sample->user->sign))
							<div><img src="{{ url(Storage::url($sample->user->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $sample->user->name }}</div>
						<div style="font-size:10px;">{{ $sample->user->userRole->first()->role() }}</div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>