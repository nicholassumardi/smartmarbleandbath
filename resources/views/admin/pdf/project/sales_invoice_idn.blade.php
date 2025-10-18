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
							<img src="{{ url('website/kop_brand_report.png') }}" width="100%">
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
									<h3><b>INVOICE PEMBAYARAN</b></h3>
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
								<td width="20%" style="font-size:12px;">INVOICE</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PENANGGUNG JAWAB</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->project->manager }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">PELANGGAN</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->project->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">ALAMAT</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->project->customer->address }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">TANGGAL</td>
								<td style="text-align:left; font-size:12px;">: {{ App\Helper\SMB::tgl_indo($project->date) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">SALES</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->sales->name }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">SALES NO.</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->code }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">TELEPON</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->projectSale->project->customer->phone }}</td>
							</tr>
							<tr>
								<td width="20%" style="font-size:12px;">SO TGL</td>
								<td style="text-align:left; font-size:12px;">: {{ App\Helper\SMB::tgl_indo(date('Y-m-d', strtotime($project->projectSale->created_at))) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">KODE PROFORMA</td>
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
				$totalPaidCurrentInv = 0;
				$totalqtybox = 0;
				$totalqtytile = 0;
				$totalqtylain = 0;
				
				foreach($project->projectSale->projectSalePay as $key => $psp){
					$totalpaid += $psp->nominal;
					if($project->id == $psp->id){
						$totalPaidCurrentInv += $psp->nominal;
					}
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
						<th style="color:white;" colspan="2"><center>JUM</center></th>
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
					@foreach($project->projectSale->projectSaleProduct as $key => $pp)
						@php
							if($pp->unit == '2' || $pp->unit == '3'){
								$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
								
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$countbox = ceil($pp->qty);
									$total += $pp->best_price * ($countbox);
									$totaltile += $pp->best_price * ($countbox);
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$countbox = ceil($pp->qty);
										$total += $pp->best_price * ($countbox);
										$totaltile += $pp->best_price * ($countbox);
									}else{
										$countbox = ceil(round($pp->qty / $m2,2));
										$total += $pp->best_price * $m2 * ($countbox);
										$totaltile += $pp->best_price * $m2 * ($countbox);
									}
									
								}
								
								$totalqtybox += $countbox;
								$totalqtytile += $pp->qty;
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
									@php 
										//echo $countbox - $pp->getCountReturn();
										echo $countbox;
									@endphp
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
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format($pp->best_price, 0, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format($pp->best_price, 0, ',', '.');
											}else{
												echo number_format($pp->best_price * $m2, 0, ',', '.');
											}
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php 
										if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
											echo number_format($pp->best_price * ($countbox), 0, ',', '.');
										}else{
											if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
												echo number_format($pp->best_price * ($countbox), 0, ',', '.');
											}else{
												echo number_format($pp->best_price * $m2 * ($countbox), 0, ',', '.');
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
				<br>
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
						<th style="color:white;" rowspan="2"><center>JUM</center></th>
						<th style="color:white;" colspan="2"><center>HARGA (BELUM TAX)</center></th>
						<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" colspan="2">PCS</th>
					</th>
				<tbody>
					@php
						$no = 1;
					@endphp
					@foreach($project->projectSale->projectSaleProduct as $key => $pp)
						@php
							if($pp->unit == '1' || $pp->unit == '4'){
								$total += $pp->best_price * $pp->qty;
								$totallain += $pp->best_price * $pp->qty;
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
			<br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="45%">
						<table cellpadding="2" cellspacing="0" border="1" style="width:100% !important;">
							<tr>
								<td>
									<div style="font-size:12px;">Catatan :
									<br>{{ $project->projectSale->note }}
									</div>
								</td>
							</tr>
							<tr>
								<td>
									PEMBAYARAN HARAP DITRANSFER KE REKENING :
									@if($project->projectSale->sales->branch == '2')
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
							<tr>
								<td>
									<h6>SUBTOTAL SETELAH DISKON</h6>
								</td>
								<td>
									<h6>Rp {{ number_format(($project->project->discount ? ($total - $project->project->discount) : $total), 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>PPN PRODUK</h6>
								</td>
								<td>
									<h6>Rp {{ $project->projectSale->project->ppn == '1' ? number_format($persenppn * ($total - $project->project->discount), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TOTAL PRODUK</h6>
								</td>
								<td>
									<h6>Rp {{ $project->projectSale->project->ppn == '1' ? number_format(($total - $project->project->discount) + ($persenppn * ($total  - $project->project->discount)), 0, ',', '.') : number_format($total - $project->project->discount, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>BIAYA KIRIM</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($project->projectSale->delivery_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>BIAYA POTONG</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($project->projectSale->cutting_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>BIAYA LAIN-LAIN</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($project->projectSale->misc_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TOTAL BIAYA</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>BIAYA PPN</h6>
								</td>
								<td>
									<h6>Rp {{ $project->projectSale->ppn_cost == '1' ? number_format($persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost), 0, ',', '.') : number_format(0, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@php
								$grandtotal = 0;
								
								if($project->projectSale->project->ppn == '1'){
									$grandtotal += ($total - $project->project->discount) + ($persenppn * ($total - $project->project->discount));
								}else{
									$grandtotal += ($total - $project->project->discount);
								}
								
								if($project->projectSale->ppn_cost == '1'){
									$grandtotal += $persenppn * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost);
								}else{
									$grandtotal += $project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost;
								}
								
								$totalreturn = 0;
								
								foreach($project->projectSale->projectSaleReturn as $rowreturn){
									$totalreturn += $rowreturn->grandtotal;
								}
							@endphp
							<tr>
								<td>
									<h6>GRANDTOTAL</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($grandtotal,0,',','.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TERBAYAR</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($totalpaid, 0, ',', '.') }}</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>RETURN</h6>
								</td>
								<td>
									<h6>IDR {{ number_format($totalreturn, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@if($grandtotal - $totalpaid >= 0)
							<tr>
								<td>
									<h6>KEKURANGAN BAYAR</h6>
								</td>
								<td>
									<h6>Rp {{ $grandtotal - $totalpaid < 0 ? '0' : number_format($grandtotal - $totalpaid - $totalreturn, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@else
							<tr>
								<td>
									<h6>KELEBIHAN BAYAR</h6>
								</td>
								<td>
									<h6>Rp {{ number_format($totalpaid - $grandtotal + $totalreturn, 0, ',', '.') }}</h6>
								</td>
							</tr>
							@endif
							<tr>
								<td>
									<h6>TERBILANG</h6>
								</td>
								<td>
									<h6>{{ $grandtotal - $totalpaid > 0 ? App\Helper\SMB::terbilang(round($grandtotal - $totalpaid - $totalreturn)) : 'Zero' }} Rupiah</h6>
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
						<th style="color:white;" colspan="7"><center>Daftar Pembayaran</center></th>
					</tr>
					<tr style="background:#c92089;text-align:center;">
						<th style="color:white;"><center>No.</center></th>
						<th style="color:white;"><center>Invoice</center></th>
						<th style="color:white;"><center>Tanggal</center></th>
						<th style="color:white;"><center>Tipe</center></th>
						<th style="color:white;"><center>Ke</center></th>
						<th style="color:white;"><center>Catatan</center></th>
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
							<td><center>{{ App\Helper\SMB::tgl_indo($psp->date) }}</center></td>
							<td><center>{{ $psp->giro() }}</center></td>
							<td><center>{{ $psp->coa->name }}</center></td>
							<td><center>{{ $psp->note }}</center></td>
							<td><center>Rp {{ number_format($psp->nominal,2,',','.') }}</center></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<br><br>
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
						<div style="font-size:10px;">Dicek Oleh,</div>
						@if(isset($project->check->sign))
							<div><img src="{{ url(Storage::url($project->check->sign)) }}" height="65px"></div>
						@else
							<br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->check->name }}</div>
					</td>
					@endif
					<td style="text-align:center;" width="33%">
						<div style="font-size:10px;">Dibuat Oleh,</div>
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
		@php
			$text = '';
			
			if(($grandtotal - $totalpaid - $totalreturn) < 5000000){
				$text = 'background-image: url('.url("website/stempel_pta_baru_small_1.png").');background-size:12px;background-repeat:no-repeat;background-position: center center;';
			}
		@endphp
		<div class="separate-box">
			<table id="table-kwitansi" style="vertical-align: top;padding: 5px;font-size:13px;background-image: url({{ url('website/bg_kwitansi.png') }});" width="100%" height="auto">
				<tr>
					<td rowspan="6" width="20%" style="padding:0;"><img src="{{ url('website/logo_samping_1.png') }}" height="350px"></td>
					<td width="25%">Receipt No.</td>
					<td width="1%">:</td>
					<td><?=str_replace('INV','RCP',$project->code)?></td>
				</tr>
				<tr>
					<td>Bill to</td>
					<td width="1%">:</td>
					<td>{{ $project->projectSale->project->customer->name }}</td>
				</tr>
				<tr>
					<td>Nominal in words</td>
					<td width="1%">:</td>
					<td>{{ App\Helper\SMB::terbilang($grandtotal - $totalpaid - $totalreturn) }} Rupiah</td>
				</tr>
				<tr>
					<td>For payment</td>
					<td width="1%">:</td>
					<td>According to Sales Invoice {{ $project->code }}</td>
				</tr>
				<tr>
					<td>Total</td>
					<td width="1%">:</td>
					<td><div>Rp {{ number_format($grandtotal - $totalpaid - $totalreturn,0,',','.') }}</div></td>
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
									@if($project->projectSale->sales->branch == '1')
									
									Surabaya, {{ App\Helper\SMB::tgl_indo(date('Y-m-d')) }}
										<br>
										@if(($grandtotal - $totalpaid - $totalreturn) < 5000000)
										<div style="position: relative;">
											<img src="{{ url(Storage::url(App\Models\User::find(11)->sign)) }}" height="65px">
										</div>
										@else
											<br><br><br><br><br>
										@endif
										{{ App\Models\User::find(11)->name }}
										<br>
										(AR Staff)
									@elseif($project->projectSale->sales->branch == '2')
									
										Jakarta, {{ App\Helper\SMB::tgl_indo(date('Y-m-d')) }}
										<br><br><br><br><br><br><br><br><br>
										Prawiro Tedjo Tjandra
										<br>
										(Direktur)
										
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
						Dibuat pada : {{ $project->created_at }}
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>