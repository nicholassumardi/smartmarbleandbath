@php
	use App\Models\SamplePurchase;
	use App\Models\SamplePurchaseProduct;
@endphp
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Sales Invoice {{ $sample->proforma_code }}</title>

</head>

<body>
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0">
			<tr class="top">
				<td colspan="2">
					@php
					//if($sample->is_dropshipper == '1'){
					@endphp
					<table>
						<tr>
							<td class="title" rowspan="2">
								<img src="{{ url('website/logo-black.png') }}" width="275">
							</td>
							<td colspan="2" style="text-align:right;padding-bottom:15px;"><img
									src="{{ url('website/pta_new_logo.png') }}" height="30px" style="margin-right:5px;">
							</td>
						</tr>
						<tr>
							<td style="padding-right:25px;padding-top:100px !important;">
								<div style="font-size:9px; font-weight:bold;">JAGAT BUILDING</div>
								<div style="font-size:9px; font-weight:500;">Tomang Raya No 28 - 30, Jakarta 11430</div>
								<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
								<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com
								</div>
							</td>
							<td style="border-left: 3px solid #51b6bc; text-align:right;padding-top:100px !important;">
								<div style="font-size:9px; font-weight:bold;">MODERN CERAMIC</div>
								<div style="font-size:9px; font-weight:500;">Baliwerti 119 - 121, Surabaya 60174</div>
								<div style="font-size:9px; font-weight:500;">Phone : 031-5472860 / 031-5324505</div>
								<div style="font-size:9px; font-weight:500;">Email : info@smartmarbleandbath.com</div>
							</td>
						</tr>
					</table>
					@php
					/* }elseif($sample->is_dropshipper == '2'){
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
							<td colspan="2" style="text-align:right;">
								<h3>{{ strtoupper($sample->dropshipper->name) }}</h3>
							</td>
						</tr>
						<tr>
							<td></td>
							<td style="text-align:right;">
								<div style="font-size:9px; font-weight:bold;">{{ $sample->dropshipper->address }}</div>
								<div style="font-size:9px; font-weight:500;">Phone : {{ $sample->dropshipper->phone }}
								</div>
								<div style="font-size:9px; font-weight:500;">Email : {{ $sample->dropshipper->email }}
								</div>
							</td>
						</tr>
					</table>
					@php
					} */
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
							<td colspan="3">
								<div style="font-size:12px;"><b>INFORMATION :</b></div>
							</td>
						</tr>
						<tr>
							<td width="40%" style="font-size:12px;">Date of DO</td>
							<td width="5%" style="font-size:12px;">:</td>
							<td style="text-align:left; font-size:12px;">{{ date('d F Y',
								strtotime($sample->delivery_date)) }}</td>
						</tr>
						<tr>
							<td width="40%" style="font-size:12px;">Invoice. Number</td>
							<td width="5%" style="font-size:12px;">:</td>
							<td style="text-align:left; font-size:12px;">{{ $sample->proforma_code }}</td>
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
							<td style="text-align:left; font-size:12px;">{{ $sample->customerSample->name }}</td>
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
							<td colspan="3">
								<div style="font-size:12px;"><b>SHIP TO :</b></div>
							</td>
						</tr>
						<tr>
							<td width="40%" style="font-size:12px;">Receiver</td>
							<td width="5%" style="font-size:12px;">:</td>
							<td style="text-align:left; font-size:12px;">{{ $sample->is_dropshipper == '1' ?
								$sample->receiver_name : strtoupper($sample->dropshipper->name) }}</td>
						</tr>
						<tr>
							<td width="40%" style="font-size:12px;">Address</td>
							<td width="5%" style="font-size:12px;">:</td>
							<td style="text-align:left; font-size:12px;">{{ $sample->is_dropshipper == '1' ?
								$sample->customerSample->customer->address : $sample->dropshipper->address }}</td>
						</tr>
						<tr>
							<td width="40%" style="font-size:12px;">Phone</td>
							<td width="5%" style="font-size:12px;">:</td>
							<td style="text-align:left; font-size:12px;">{{ $sample->is_dropshipper == '1' ?
								$sample->phone : $sample->dropshipper->phone }}</td>
						</tr>
						<!-- <tr>
								<td width="40%" style="font-size:12px;">Expedition</td>
								<td width="5%" style="font-size:12px;">:</td>
								<td style="text-align:left; font-size:12px;">{{ $sample->vendor->name }}</td>
							</tr> -->
						<tr>
							<td width="40%" style="font-size:12px;">City</td>
							<td width="5%" style="font-size:12px;">:</td>
							<td style="text-align:left; font-size:12px;">{{ $sample->city->name }}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table><br>
		@php
		if(date('Y-m-d',strtotime($sample->sample->created_at)) < '2022-04-01' ){ $persenppn=0.1; }else{
			$persenppn=0.11; } $koma=0; $adatile=false; $adalain=false; $total=0; $totaltile=0; $totallain=0;
			$qtysent=0; $qtytotal=0; $qtysentbox=0; $qtysentlain=0; foreach($sample->sampleDeliveryProduct as $key =>
			$pp){
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
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;" colspan="8">
							<center>TILES</center>
						</th>
					</tr>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;">
							<center>NO</center>
						</th>
						<th style="color:white;">
							<center>NAME</center>
						</th>
						<th style="color:white;">
							<center>SHADE</center>
						</th>
						<th style="color:white;">
							<center>SIZE</center>
						</th>
						<th style="color:white;">
							<center>PCS/BOX</center>
						</th>
						<th style="color:white;">
							<center>QTY DELIVERED</center>
						</th>
						<th style="color:white;">
							<center>PRICE</center>
						</th>
						<th style="color:white;">
							<center>TOTAL</center>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->sampleDeliveryProduct as $key => $ps)
							@php
							$samplepurchase = SamplePurchase::where('sample_id', $sample->sample_id)->orderBy('created_at', 'desc')->first();	
							if(($ps->unit == '2' || $ps->unit == '3') && $ps->getQtyMinusReturn() > 0){
							$ssp = SamplePurchaseProduct::where('sample_purchase_id', $samplepurchase->id)->where('product_id', $ps->product_id)->first();
							
							$m2 = (($ps->product->type->length *$ps->product->type->width ) / 10000) *
							$ps->product->carton_pcs;
							$countbox = $ps->getQtyMinusReturn();
								if($m2 < 1.1 &&$ps->product->type->category->parent()->id !== 18){
									$total += ($ssp->price * $countbox)/1;
									$price = $ssp->price/1;
									$totaltile += ($ssp->price * $countbox)/1;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($sample->customerSample->created_at)) < '2022-06' && 
									$ps->product->type->category->parent()->id == 18){
											$total += ($ssp->price * $countbox)/1;
											$price = $ssp->price/1;
											$totaltile += ($ssp->price * $countbox)/1;
									}else{
										$total += ($ssp->price * $m2 * $countbox)/1;
										$price = $ssp->price/1;
										$totaltile += ($ssp->price * $m2 * $countbox)/1;
									}

								}
									$qtysent += $ps->getQtyMinusReturn();
									$qtysentbox += $ps->getQtyMinusReturn();
									$qtytotal += $countbox;
						

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
												if($m2 < 1.1 && date('Y-m',strtotime($sample->
												customerSample->created_at)) < '2022-06' && $ps->
												product->type->category->parent()->id == 18){
													echo number_format($price,$koma,',','.');
												}else{
													echo number_format($price * $m2,$koma,',','.');
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
											if($m2 < 1.1 && date('Y-m',strtotime($sample->
												customerSample->created_at)) < '2022-06' && $ps->
													product->type->category->parent()->id == 18){
													echo number_format($price *
													$ps->getQtyMinusReturn(),$koma,',','.');
											}else{
													echo number_format($price * $m2 *
													$ps->getQtyMinusReturn(),$koma,',','.');
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
						<th style="color:white;" colspan="7">
							<center>NON-TILES</center>
						</th>
					</tr>
					<tr style="background:#0b95b8;text-align:center;">
						<th style="color:white;">
							<center>NO</center>
						</th>
						<th style="color:white;">
							<center>NAME</center>
						</th>
						<th style="color:white;">
							<center>SHADE</center>
						</th>
						<th style="color:white;">
							<center>SIZE(cm)</center>
						</th>
						<th style="color:white;">
							<center>PCS/BOX</center>
						</th>
						<th style="color:white;">
							<center>QTY DELIVERED</center>
						</th>
						<th style="color:white;">
							<center>PRICE</center>
						</th>
						<th style="color:white;">
							<center>TOTAL</center>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample->sampleDeliveryProduct as $key => $ps)
					@php
					if($ps->unit == '1' && $ps->getQtyMinusReturn() > 0){
					$samplepurchase = SamplePurchase::where('sample_id', $sample->sample_id)->orderBy('created_at', 'desc')->first();	
						
					$ssp = SamplePurchaseProduct::where('sample_purchase_id', $samplepurchase->id)->where('product_id', $ps->product_id)->first();
					
					$total += ($ssp->price * $ps->getQtyMinusReturn())/1;
					$totallain += ($ssp->price * $ps->getQtyMinusReturn())/1;
					$price = ($ssp->price)/1;
					$qtysent += $ps->getQtyMinusReturn();
					$qtytotal += $ps->getQtyMinusReturn();
			

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
								{{ round($ps->getQtyMinusReturn(),0).' '.$ps->unit() }}
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
						<th style="text-align:center;">{{ $qtysentlain }} Pcs</th>
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
						<table cellpadding="2" cellspacing="0" border="1"
							style="background-color:#0b95b8;font-size:14px;color:white;">
							<tr>
								<td>
									<b>GRANDTOTAL</b>
								</td>
								<td style="text-align:center;">
									<b> {{
										number_format(($total), $koma, ',', '.') }}</b>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<b>Nominal in words : {{
										App\Helper\SMB::say(round(($total))) }} Rupiahs</b>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									PAYMENT WILL BE TRANSFERRED TO :
									@if($sample->sample->sales->branch == '2')

									<p>
										<br>BCA 329.0258.510
										<br>a/n Andy Hidayat
										<br>Cab. Baliwerti<br>
									</p>

									@else

									<p>
										<br>BCA 329.0258.510
										<br>a/n Andy Hidayat
										<br>Cab. Baliwerti<br>
									</p>

									@endif
								</td>
							</tr>
							<!-- <tr>
								<td colspan="2">
									<div>Note :
									<br>{{ $sample->sample->note }}
									</div>
								</td>
							</tr> -->
						</table>
					</td>
					<td width="5%"></td>
					<td width="45%" style="vertical-align:top;">
						<table cellpadding="0" cellspacing="0" border="1">
							<tr>
								<td>
									<h6>SUBTOTAL PRODUCT</h6>
								</td>
								<td>
									<h6><b>
											{{
											number_format($total, $koma, ',', '.')
											}}
									</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>TAX PRODUCT</h6>
								</td>
								<td>
									<h6><b>
											{{
											number_format(0, $koma, ',', '.')
											}}
									</h6>
								</td>
							</tr>
							<tr>
								<td>
									<h6>GRANDTOTAL</h6>
								</td>
								<td>
									<h6><b>
											{{
											number_format(($total), $koma, ',', '.') }}</h6>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				@php
				$grandtotal = 0;

				$grandtotal = round(($total));


				$text = '';

				if($grandtotal < 5000000){ $text='background-image: url('
					.url("website/stempel_pta_baru_small_1.png").');background-size:12px;background-repeat:no-repeat;background-position:
					center center;'; } @endphp </table>
					<br><br>
					<table cellpadding="0" cellspacing="0">
						<tr>
							@php
							//if($sample->is_dropshipper == '1'){
							@endphp
							<td style="text-align:center;">
								<div style="font-size:10px;">Sales</div>
								@if(isset($sample->sample->sales->sign))
								<div><img src="{{ url(Storage::url($sample->sample->sales->sign)) }}" height="65px">
								</div>
								@else
								<br><br><br>
								@endif
								<div style="font-size:10px;font-weight:700;">( {{ isset($sample->sample->sales->name) ?
									$sample->sample->sales->name : '........................' }} )</div>
								<div style="font-size:10px;font-weight:700;">( {{ isset($sample->sample->sales->name) ?
									$sample->sample->sales->userRole->first()->role() : '' }} )</div>
							</td>
							<td style="text-align:center;{{ $text }}">
								<div style="font-size:10px;">Checked by</div>
								@if($sample->user->sign)
								<div><img src="{{ url(Storage::url($sample->user->sign)) }}" height="65px"></div>
								@else
								<br><br><br>
								@endif
								<div style="font-size:10px;font-weight:700;">( {{ $sample->user->name ?
									$sample->user->name : '' }} )</div>
								<div style="font-size:10px;font-weight:700;">( {{ $sample->user->name ?
									$sample->user->userRole->first()->role() : '' }} )</div>
							</td>
							<td style="text-align:center;">
								<div style="font-size:10px;">Acknowledged By</div>
								<div><img src="{{ url(Storage::url(App\Models\User::find(4)->sign)) }}" height="65px">
								</div>
								<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(4)->name }} )
								</div>
								<div style="font-size:10px;font-weight:700;">( {{
									App\Models\User::find(4)->userRole->first()->role() }} )</div>
							</td>
							<td style="text-align:center;">
								<div style="font-size:10px;">Approved By</div>
								<div><img src="{{ url(Storage::url(App\Models\User::find(7)->sign)) }}" height="65px">
								</div>
								<div style="font-size:10px;font-weight:700;">( {{ App\Models\User::find(7)->name }} )
								</div>
								<div style="font-size:10px;font-weight:700;">( {{
									App\Models\User::find(7)->userRole->first()->role() }} )</div>
							</td>
							@php
							//}
							@endphp
						</tr>
					</table>
					<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:50px;">
						<tr>
							<td style="text-align:right;color:black;font-size:10px;">
								Created at : {{ $sample->created_at }}
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
								@foreach($sample->sampleDeliveryProduct as $key => $pp)
								@php
								if($pp->unit == '2' || $pp->unit == '3'){
								if((($key + 1) % 6) == 1){
								echo '
							<tr>';
								}
								@endphp
								<td width="10%">
									<img src="{{ $pp->product->type->image() }}"
										style="max-width:50px; border:1px solid #ddd; border-radius:4px; padding: 5px;"
										class="img-fluid img-thumbnail">
									<center style="font-size:15px;">
										{{ $pp->product->type->code }}
									</center>
								</td>
								@php
								if((($key + 1) % 6) == 1){
								echo '
							</tr>';
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
							@foreach($sample->sampleDeliveryProduct as $key => $pp)
							@php
							if($pp->unit == '1' || $pp->unit == '4'){
							if(($no % 6) == 1){
							echo '<tr>';
								}
								@endphp
								<td>
									<img src="{{ $pp->product->type->image() }}"
										style="max-width:50px; border:1px solid #ddd; border-radius:4px; padding: 5px;"
										class="img-fluid img-thumbnail">
									<center>
										{{ $pp->product->type->code }}
									</center>
								</td>
								@php
								if(($no % 6) == 1){
								echo '
							</tr>';
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
		<table id="table-kwitansi"
			style="vertical-align: top;padding: 5px;font-size:13px;background-image: url({{ url('website/bg_kwitansi.png') }});"
			width="100%" height="auto">
			<tr>
				<td rowspan="6" width="20%" style="padding:0;"><img src="{{ url('website/logo_samping_1.png') }}"
						height="350px"></td>
				<td width="25%">Receipt Number</td>
				<td width="1%">:</td>
				<td>
					<?=str_replace('DO','RC',$sample->code)?>
				</td>
			</tr>
			<tr>
				<td>Received from</td>
				<td width="1%">:</td>
				<td>{{ $sample->sample->customerSample->customer->name }}</td>
			</tr>
			<tr>
				<td>Nominal in words</td>
				<td width="1%">:</td>
				<td>{{
					App\Helper\SMB::say(round(($total))) }} Rupiahs
				</td>
			</tr>
			<tr>
				<td>For payment</td>
				<td width="1%">:</td>
				<td>According to Sales Invoice {{ $sample->proforma_code }}</td>
			</tr>
			<tr>
				<td>Total</td>
				<td width="1%">:</td>
				<td>
					{{
					number_format(($total), $koma, ',', '.')
					}}
	</div>
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
						@if($sample->sample->sales->branch == '1')

						Surabaya, {{ date('d F Y', strtotime($sample->delivery_date)) }}
						<br>
						@if($grandtotal < 5000000) <div style="position: relative;">
							<img src="{{ url(Storage::url(App\Models\User::find(11)->sign)) }}" height="65px">
							</div>
							@else
							<br><br><br><br><br><br><br>
							@endif
							{{ App\Models\User::find(11)->name }}
							<br>
							(AR Staff)
							@elseif($sample->sample->sales->branch == '2')

							Jakarta, {{ date('d F Y', strtotime($sample->delivery_date)) }}
							<br><br><br><br><br><br><br><br>
							<u>DAVID PRAWIRO TEDJO</u>
							<br>
							(Direktur)
							@endif
					</td>
				</tr>
			</table>
			@if($grandtotal < 5000000) @endif </td>
	</tr>
	</table>
	</div>
</body>

</html>