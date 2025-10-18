<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			td {
				font-size: 16pt;
				font-weight: bold;
			}
			
			th {
				font-size:16pt;
			}
		
			.invoice-box {
				font-size: 14px;
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
				padding: 5px;
				font-size : 10pt;
			}
			
			.separate-box #table-kwitansi {
				border: 1px solid black;
			}
			
			.service-cost tr td, .service-cost tr th, .list-payments tr th{
				font-size : 11pt !important;
			}
			
			@page { margin: 0.5cm; }
			body { margin: 0.5cm; }
		</style>
	</head>
	<body>
		<div class="invoice-box">
			@if($project->branch == 4)
    			<table cellpadding="0" cellspacing="0">
    				<tr>
    					<td colspan="2" style="vertical-align: middle;padding-top:15px;padding-bottom:45px;">
    						<center>
    							<img src="{{ url('website/letterheadpsi_big.jpg') }}" width="100%" style="width: 1500px !important;">
    						</center>
    					</td>
    				</tr>
    			</table>
			@else
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
							<img src="website/kop_brand_report.png" width="100%">
						</center>
					</td>
				</tr>
			</table>
			@endif
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr style="background-color:#ebb220;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>SURAT PENAGIHAN (BARANG)</b></h3>
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
								<td style="text-align:left; font-size:12px;">: {{ $project->project->manager }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">PELANGGAN</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">ALAMAT</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->address }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">TANGGAL</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($project->date)) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">TELEPON</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->customer->phone }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">TENGGAT WAKTU</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($project->due_date)) }}</td>
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
				if($project->project->timeline < '2022-04-01'){
					$persenppn = 0.1;
					$ppnpembagi = 1.1;
				}else{
					$persenppn = 0.11;
					$ppnpembagi = 1.11;
				}

				$koma = 0;
				
				if($project->project->projectSale->first()->currency_id !== '5'){
					$koma = 2;
				}
			
				$adatile = false;
				$adalain = false;
				$total = 0;
				$totaltile = 0;
				$totallain = 0;
				$totalqtybox = 0;
				$totalqtytile = 0;
				$totalqtylain = 0;
				
				foreach($project->project->projectProduct as $key => $pp){
					if($pp->unit == '2' || $pp->unit == '3'){
						$adatile = true;
					}
					if($pp->unit == '1' || $pp->unit == '4'){
						$adalain = true;
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
						<tr style="background:#ebb220;text-align:center;">
							<th style="color:white;" colspan="12"><center>TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>KODE</center></th>
						<th style="color:white;" rowspan="2"><center>GAMBAR</center></th>
						<th style="color:white;" rowspan="2"><center>MERK</center></th>
						<th style="color:white;" rowspan="2"><center>UKURAN(cm)</center></th>
						<th style="color:white;" rowspan="2"><center>KATEGORI</center></th>
						<th style="color:white;" rowspan="2"><center>WARNA</center></th>
						<th style="color:white;" colspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>HARGA (BELUM PPN)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
						<th style="color:white;">(M<sup>2</sup>)</th>
						<th style="color:white;">(BOX)</th>
					</th>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($project->project->projectSale as $key => $ps)
					@foreach($ps->projectSaleProduct()->orderBy('id')->get() as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
								
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$countbox = ceil($pp->qty);
									$total += ($pp->best_price * $countbox) / $project->project->projectSale->first()->currency_rate;
									$totaltile += ($pp->best_price * $countbox) / $project->project->projectSale->first()->currency_rate;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$countbox = ceil($pp->qty);
										$total += ($pp->best_price * $countbox) / $project->project->projectSale->first()->currency_rate;
										$totaltile += ($pp->best_price * $countbox) / $project->project->projectSale->first()->currency_rate;
									}else{
										$countbox = ceil(round($pp->qty / $m2,2));
										$total += (round(($pp->best_price * $m2), 0) * $countbox) / $project->project->projectSale->first()->currency_rate;
										$totaltile += (round(($pp->best_price * $m2), 0) * $countbox) / $project->project->projectSale->first()->currency_rate;
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
							<td style="vertical-align:center; font-size:19pt; word-wrap:break;">
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
											echo number_format($pp->best_price / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format((($pp->best_price * $countbox) / ($countbox * $m2)) / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
											}else{
												echo number_format(((round(($pp->best_price * $m2), 0) * $countbox) / ($countbox * $m2)) / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
											}
										}
										
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format($pp->best_price / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format($pp->best_price / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
											}else{
												echo number_format((round(($pp->best_price * $m2), 0)) / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
											}
										}
										
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php 
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format(($pp->best_price * $countbox) / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format(($pp->best_price * $countbox) / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
											}else{
												echo number_format((round(($pp->best_price * $m2), 0) * $countbox) / $project->project->projectSale->first()->currency_rate, $koma, ',', '.');
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
					@endforeach
					<tr>
						<td style="vertical-align:center;text-align:right;" colspan="7">
							Total Qty
						</td>
						<td style="vertical-align:center;text-align:center;">{{ $totalqtytile }}</td>
						<td style="vertical-align:center;text-align:center;">{{ $totalqtybox }}</td>
						<th colspan="2">Subtotal</th>
						<th colspan="1">{{ number_format($totaltile, 0, ',', '.') }}</th>
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
						<tr style="background:#ebb220;text-align:center;">
							<th style="color:white;" colspan="12"><center>NON-TILES</center></th>
						</tr>
					@php
						}
					@endphp
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" rowspan="2"><center>NO</center></th>
						<th style="color:white;" rowspan="2"><center>KODE</center></th>
						<th style="color:white;" rowspan="2"><center>GAMBAR</center></th>
						<th style="color:white;" rowspan="2"><center>MERK</center></th>
						<th style="color:white;" rowspan="2"><center>UKURAN(mm)</center></th>
						<th style="color:white;" rowspan="2"><center>KATEGORI</center></th>
						<th style="color:white;" rowspan="2"><center>WARNA</center></th>
						<th style="color:white;" rowspan="2"><center>SPEK</center></th>
						<th style="color:white;" rowspan="2"><center>QTY</center></th>
						<th style="color:white;" colspan="2"><center>HARGA (BELUM PPN)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" colspan="2">PCS</th>
					</th>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($project->project->projectSale as $key => $ps)
					@foreach($ps->projectSaleProduct()->orderBy('id')->get() as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								$total += ($pp->best_price * $pp->qty) / $project->project->projectSale->first()->currency_rate;
								$totallain += ($pp->best_price * $pp->qty) / $project->project->projectSale->first()->currency_rate;
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
									{{ $pp->spec }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $pp->qty }}
								</center>
							</td>
							<td style="vertical-align:center;" colspan="2">
								<center>
									{{ number_format($pp->best_price / $project->project->projectSale->first()->currency_rate, $koma, ',', '.') }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ number_format(($pp->best_price * $pp->qty) / $project->project->projectSale->first()->currency_rate, $koma, ',', '.') }}
								</center>
							</td>
						</tr>
						@php
								$no++;
							}
						@endphp
					@endforeach
					@endforeach
					<tr>
						<td colspan="8" style="text-align:right;">Total Qty</td>
						<td style="text-align:center;">{{ $totalqtylain }}</td>
						<th colspan="2">Subtotal</th>
						<th colspan="1">{{ number_format($totallain, 0, ',', '.') }}</th>
					</tr>
				</tbody>
				</table>
				@php
				}
				@endphp
				@php
					$grandtotal = 0;
					
					if($project->project->ppn == '1'){
						$grandtotal += ($total - $project->project->discount) + ($persenppn * ($total - $project->project->discount));
					}else{
						$grandtotal += ($total - $project->project->discount);
					}
					
					$grandtotal += $project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost;
				@endphp
			
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="35%">
						<table cellpadding="2" cellspacing="0" border="1" style="width:100% !important;">
							<tr>
								<td colspan="2" style="font-size:12px;">
									PEMBAYARAN HARUS DITRANSFER KE :
									@if($project->branch == '2')
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
									@elseif($project->branch == '4')
								        @if($project->project->coa_id == 380 || $project->branch == '4')
											<p>
												<br>BCA 3293-678-789
												<br>a/n PT. Perwira Sejati Internusa
												<br>Cab. Baliwerti - Surabaya<br>
											</p>
										@endif
									@else
										@if($project->project->ppn == '1')
											<p>
												<br>BCA 468-3434-178
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Indrapura - Surabaya<br>

												<br>ATAU<br>

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
								<td>
									<div style="font-size:12px;">CATATAN :
									<br>{{ $project->note }}
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div style="font-size:12px;">
										TERBILANG : {{ App\Helper\SMB::terbilang($project->nominal) }} Rupiah
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
									<h6>Rp {{ number_format($total, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>DISKON</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($project->project->discount, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr style="background-color:#b0c3cf;">
								<td>
									<h6>TOTAL PRODUK</h6>
								</td>
								<td>
									<h6>Rp {{ number_format(($total - $project->project->discount), 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>PPN PRODUK</h6>
								</td>
								<td>
									<h6>Rp {{ $project->project->ppn == '1' ? number_format($persenppn * ($total - $project->project->discount), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr style="background-color:#b0c3cf;">
								<td>
									<h6>GRANDTOTAL PRODUK</h6>
								</td>
								<td>
									<h6>Rp {{ $project->project->ppn == '1' ? number_format(($total - $project->project->discount) + ($persenppn * ($total - $project->project->discount)), 0, ',', '.') : number_format($total - $project->project->discount, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@php
								$grandtotalproduct = $project->project->ppn == '1' ? ($total - $project->project->discount) + ($persenppn * ($total - $project->project->discount)) : $total - $project->project->discount;
							@endphp
							<tr style="background-color:#ec8131;">
								<td>
									<h6>TAGIHAN PRODUK ({{ $grandtotalproduct ? round(($project->nominal/$grandtotalproduct) * 100) : 0 }}%)</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($project->nominal,0,',','.') }}</h6>
								</td>
							</tr>
						</table>
						<p style="font-size:10pt;">Dibuat pada : {{ $project->created_at }}</p>
					</td>

				</tr>
				<tr>
					<td colspan="3">
						
					</td>
				</tr>
			</table>
			@php
				$text = '';
				
				if($project->nominal < 5000000){
					$text = 'background-image: url('.url("website/stempel_pta_baru_small_1.png").');background-size:12px;background-repeat:no-repeat;background-position: center center;';
				}
			@endphp
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					@if(isset($project->approved->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Mengetahui,</div>
						@if(isset($project->approved->sign))
							<div><img src="{{ url(Storage::url($project->approved->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->approved->name }}</div>
						<div style="font-size:10px;">{{ $project->approved->userRole->first()->role() }}</div>
					</td>
					@endif
					@if(isset($project->check->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Dicek Oleh,</div>
						@if(isset($project->check->sign))
							<div><img src="{{ url(Storage::url($project->check->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->check->name }}</div>
						<div style="font-size:10px;">{{ $project->check->userRole->first()->role() }}</div>
					</td>
					@endif
					<td style="text-align:center;{{ $text }}" width="33%">
						<div style="font-size:10px;">Dibuat Oleh,</div>
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
		</div>
		<div class="separate-box">
			<table id="table-kwitansi" style="vertical-align: top;padding: 5px;font-size:13px;background-image: url({{ url('website/bg_kwitansi.png') }});" width="100%" height="auto">
				<tr>
					<td rowspan="6" width="20%" style="padding:0;"><img src="{{ $project->branch == 4 ?  url('website/logo_samping_psi.jpg') : url('website/logo_samping_1.png') }}" height="420px"></td>
					<td width="25%">Nomor Kwitansi</td>
					<td width="1%">:</td>
					<td><?=str_replace('BI','RC-BI',$project->code)?>-PRODUK</td>
				</tr>
				<tr>
					<td>Telah menerima dari</td>
					<td width="1%">:</td>
					<td>{{ $project->project->customer->name }}</td>
				</tr>
				<tr>
					<td>Terbilang</td>
					<td width="1%">:</td>
					<td>{{ App\Helper\SMB::terbilang($project->nominal) }} Rupiah
					</td>
				</tr>
				<tr>
					<td>Untuk Pembayaran</td>
					<td width="1%">:</td>
					<td>Berdasarkan Surat Penagihan Produk Nomor {{ $project->project->code }}</td>
				</tr>
				<tr>
					<td>Total</td>
					<td width="1%">:</td>
					<td><div style="border:1p solid black;padding:10px;">
						Rp {{ number_format($project->nominal, 0, ',', '.') }}</div></td>
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
									@if($project->branch == '2')
										Jakarta, {{ date('d F Y', strtotime($project->date)) }}
										<br>
										@if($project->nominal < 5000000)
										<div style="position: relative;">
											<img src="{{ url(Storage::url(App\Models\User::find(12)->sign)) }}" height="65px">
										</div>
										@else
											<br><br><br><br><br><br><br>
										@endif
										Prawiro Tedjo Tjandra
										<br>
										(Direktur)
									@else
										Surabaya, {{ date('d F Y', strtotime($project->date)) }}
										<br>
										@if($project->nominal < 5000000)
										<div style="position: relative;">
											<img src="{{ url(Storage::url(App\Models\User::find(11)->sign)) }}" height="65px">
										</div>
										@else
											<br><br><br><br><br><br><br>
										@endif
										{{ App\Models\User::find(11)->name }}
										<br>
										(AR Staff)
									@endif
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="invoice-box">
		    @if($project->branch == 4)
    			<table cellpadding="0" cellspacing="0">
    				<tr>
    					<td colspan="2" style="vertical-align: middle;padding-top:15px;padding-bottom:45px;">
    						<center>
    							<img src="{{ url('website/letterheadpsi_big.jpg') }}" width="100%" style="width: 1500px !important;">
    						</center>
    					</td>
    				</tr>
    			</table>
			@else
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
								<td style="padding-right:25px;padding-top:100px !important;">
									<!--<div style="font-size:9px; font-weight:bold;">JAGAT BUILDING</div>-->
									<!--<div style="font-size:9px; font-weight:500;">Tomang Raya No 28 - 30, Jakarta 11430</div>-->
									<!--<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>-->
									<!--<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com</div>-->
								</td>
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
			@endif
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr style="background-color:#ebb220;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>SURAT PENAGIHAN JASA</b></h3>
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
								<td style="text-align:left; font-size:12px;">: {{ $project->project->manager }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">PELANGGAN</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">ALAMAT</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->address }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">TANGGAL</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($project->date)) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">TELEPON</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->customer->phone }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">TENGGAT WAKTU</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($project->due_date)) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;"></td>
								<td style="text-align:left; font-size:12px;"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;" class="service-cost">
				<thead>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" colspan="3"><center>BIAYA JASA</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>INFORMASI</center></th>
						<th style="color:white;"><center>TOTAL</center></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="vertical-align:center;">
							<center>
								1.
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								Biaya Pengiriman
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ number_format($project->project->delivery_cost, 0, ',', '.') }}
							</center>
						</td>
					</tr>
					<tr>
						<td style="vertical-align:center;">
							<center>
								2.
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								Biaya Potong
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ number_format($project->project->cutting_cost, 0, ',', '.') }}
							</center>
						</td>
					</tr>
					<tr>
						<td style="vertical-align:center;">
							<center>
								2.
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								Biaya Lain-lain
							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								{{ number_format($project->project->misc_cost, 0, ',', '.') }}
							</center>
						</td>
					</tr>
				</tbody>
			</table>
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="35%">
						<table cellpadding="2" cellspacing="0" border="1" style="width:100% !important;">
							<tr>
								<td colspan="2" style="font-size:12px;">
									PEMBAYARAN HARUS DITRANSFER KE :
									@if($project->branch == '2')
										@if($project->project->ppn == '1')
											<p>
												<br>BCA 329-37-22222
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Baliwerti - Surabaya<br>

												<br>ATAU<br>

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
											<p>
												<br>BCA 468-3434-178
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Indrapura - Surabaya<br>

												<br>ATAU<br>

												<br>Mandiri 14-000-5996-5997
												<br>a/n PT. Perwira Tamaraya Abadi
												<br>Cab. Kembang Jepun - Surabaya
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
								</td>
							</tr>
							<tr>
								<td>
									<div style="font-size:12px;">CATATAN :
									<br>{{ $project->note }}
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div style="font-size:12px;">
										TERBILANG : {{ App\Helper\SMB::terbilang($project->nominal_service) }} Rupiah
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td width="10%"></td>
					<td width="45%">
						<table cellpadding="2" cellspacing="0" border="1">
							<tr style="background-color:#90acd5;">
								<td>
									<h6>TOTAL JASA</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>PPN JASA</h6>
								</td>
								<td>
									<h6>Rp {{ $project->project->ppn_cost == '1' ? number_format($persenppn * ($project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr style="background-color:#90acd5;">
								<td>
									<h6>GRANDTOTAL JASA</h6>
								</td>
								<td>
									<h6>Rp {{ $project->project->ppn_cost == '1' ? number_format(($project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost) + ($persenppn * ($project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost)), 0, ',', '.') : number_format($project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@php
								$grandtotalservice = $project->project->ppn_cost == '1' ? ($project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost) + ($persenppn * ($project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost)) : $project->project->delivery_cost + $project->project->cutting_cost + $project->project->misc_cost;
							@endphp
							<tr style="background-color:#ec8131;">
								<td>
									<h6>OUTSTANDING PAYMENT SERVICE ({{ $grandtotalservice > 0 ? round(($project->nominal_service/$grandtotalservice) * 100) : 0 }}%)</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($project->nominal_service,0,',','.') }}</h6>
								</td>
							</tr>
						</table>
						<p style="font-size:10pt;">Dibuat pada : {{ $project->created_at }}</p>
					</td>
				</tr>
			</table>
			<br>
			@if(count($project->project->projectBill) > 1)
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:12px;">
				<thead>
					<tr style="background:#c92089;text-align:center;">
						<th style="color:white;" colspan="7"><center>Daftar Tagihan & Pembayarannya</center></th>
					</tr>
					<tr style="background:#c92089;text-align:center;">
						<th style="color:white;"><center>No.</center></th>
						<th style="color:white;"><center>Tagihan No.</center></th>
						<th style="color:white;"><center>Tanggal Tagihan</center></th>
						<th style="color:white;"><center>Tanggal Tenggat</center></th>
						<th style="color:white;"><center>Total</center></th>
						<th style="color:white;"><center>Terbayar</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($project->project->projectBill as $key => $pb)
						@php
							$style = '';
							if($project->id == $pb->id){
								$style = 'background:#65f075;';
							}
						@endphp
						<tr style="{{ $style }}">
							<td><center>{{ $key + 1 }}</center></td>
							<td><center>{{ $pb->code }}</center></td>
							<td><center>{{ date('d F Y', strtotime($pb->date)) }}</center></td>
							<td><center>{{ date('d F Y', strtotime($pb->due_date)) }}</center></td>
							<td><center>Rp {{ number_format($pb->nominal + $pb->nominal_service,0,',','.') }}</center></td>
							<td><center>Rp {{ number_format($pb->paid(),0,',','.') }}</center></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<br><br>
			@endif
			@php
				$text = '';
				
				if($project->nominal_service < 5000000){
					$text = 'background-image: url('.url("website/stempel_pta_baru_small_1.png").');background-size:12px;background-repeat:no-repeat;background-position: center center;';
				}
			@endphp
			<table cellpadding="0" cellspacing="0">
				<tr>
					@if(isset($project->approved->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Mengetahui,</div>
						@if(isset($project->approved->sign))
							<div><img src="{{ url(Storage::url($project->approved->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->approved->name }}</div>
						<div style="font-size:10px;">{{ $project->approved->userRole->first()->role() }}</div>
					</td>
					@endif
					@if(isset($project->check->name))
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Dicek Oleh,</div>
						@if(isset($project->check->sign))
							<div><img src="{{ url(Storage::url($project->check->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->check->name }}</div>
						<div style="font-size:10px;">{{ $project->check->userRole->first()->role() }}</div>
					</td>
					@endif
					<td style="text-align:center;{{ $text }}" width="33%">
						<div style="font-size:10px;">Dibuat Oleh</div>
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
		</div>
		<div class="separate-box">
			<table id="table-kwitansi" style="vertical-align: top;padding: 5px;font-size:13px;background-image: url({{ url('website/bg_kwitansi.png') }});" width="100%" height="auto">
				<tr>
					<td rowspan="6" width="20%" style="padding:0;"><img src="{{ $project->branch == 4 ?  url('website/logo_samping_psi.jpg') : url('website/logo_samping_1.png') }}" height="350px"></td>
					<td width="25%">Nomor Kwitansi</td>
					<td width="1%">:</td>
					<td><?=str_replace('BI','RC-BI',$project->code)?>-JASA</td>
				</tr>
				<tr>
					<td>Telah diterima dari</td>
					<td width="1%">:</td>
					<td>{{ $project->project->customer->name }}</td>
				</tr>
				<tr>
					<td>Terbilang</td>
					<td width="1%">:</td>
					<td>{{ App\Helper\SMB::terbilang($project->nominal_service) }} Rupiah
					</td>
				</tr>
				<tr>
					<td>Untuk pembayaran</td>
					<td width="1%">:</td>
					<td>According to Project {{ $project->project->code }} Service</td>
				</tr>
				<tr>
					<td>Total</td>
					<td width="1%">:</td>
					<td><div style="border:1p solid black;padding:10px;">
						Rp {{ number_format($project->nominal_service, 0, ',', '.') }}</div></td>
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
									@if($project->branch == '2')
										Jakarta, {{ date('d F Y', strtotime($project->date)) }}
										<br>
										@if($project->nominal_service < 5000000)
										<div style="position: relative;">
											<img src="{{ url(Storage::url(App\Models\User::find(12)->sign)) }}" height="65px">
										</div>
										@else
											<br><br><br><br><br><br><br>
										@endif
										Prawiro Tedjo Tjandra
										<br>
										(Direktur)
									@else
										Surabaya, {{ date('d F Y', strtotime($project->date)) }}
										<br>
										@if($project->nominal_service < 5000000)
										<div style="position: relative;">
											<img src="{{ url(Storage::url(App\Models\User::find(11)->sign)) }}" height="65px">
										</div>
										@else
											<br><br><br><br><br><br><br>
										@endif
										{{ App\Models\User::find(11)->name }}
										<br>
										(AR Staff)
									@endif
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>