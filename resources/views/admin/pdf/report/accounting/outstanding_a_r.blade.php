<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:14px;
			}
		
			.invoice-box {
				font-size: 18px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
				/* page-break-after: always; */
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
				background: #dfe6e9;
				border-bottom: 1px solid #dfe6e9;
				color: black;
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
			}
			
			.separate-box #table-kwitansi {
				border: 1px solid black;
			}
			
			@page { margin: 0.5cm; }
			body { margin: 0.5cm; }
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
									<img src="{{ public_path('website/logo-black.png') }}" width="275">
								</td>
								<td colspan="2" style="text-align:right;padding-bottom:15px;"><img src="{{ public_path('website/pta_new_logo.png') }}" height="30px" style="margin-right:5px;"></td>
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
							<tr style="background-color:#b2bec3;">
								<td style="text-align:center;color:black;padding-top:10px;padding-bottom:10px;">
									<h3><b>OUTSTANDING A/R REPORT {{ $branch == '1' ? 'SURABAYA' : 'JAKARTA' }} {{ strtoupper(date('F',strtotime($filter))) }} {{ date('Y',strtotime($filter)) }}</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:9px;">
				<thead>
					<tr style="background:#b2bec3;text-align:center;">
						<th style="color:black;" colspan="11"><center>RECEIVABLE</center></th>
					</tr>
					<tr style="background:#b2bec3;text-align:center;">
						<th style="color:black;"><center>NO</center></th>
						<th style="color:black;"><center>CUSTOMER</center></th>
						<th style="color:black;"><center>PROJECT</center></th>
						<th style="color:black;"><center>AMOUNT</center></th>
						<th style="color:black;"><center>INVOICE NO</center></th>
						<th style="color:black;"><center>DATE RECEIVED</center></th>
						<th style="color:black; width:100px"><center>DATE TT</center></th>
						<th style="color:black;"><center>DUE DATE PAYMENT</center></th>
						<th style="color:black;"><center>PAID</center></th>
						<th style="color:black;"><center>OUTSTANDING A/R</center></th>
						<th style="color:black;"><center>NOTES (OVER DUE DATE IN DAYS)</center></th>
					</tr>
				</thead>
				<tbody>
					@php
						$no = 1;
						$grandtotalproject = 0;
						$grandtotalbill = 0;
					@endphp
					@foreach($projectsale as $key => $row)
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $no }}
								</center>
							</td>
							<td style="vertical-align:center; font-size:12pt;">
								<center>
									<b>{{ $row->project->customer->name }}</b>
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->project->code.' - '.$row->project->name }}
									<br>
									
								@php
								$totalrow = 0;
								foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
									$totaldelivered = round($rd->grandtotal_product + $rd->getServiceCost());
									$totalrow += $totaldelivered;
								}
								echo '<b><hr>Total Project : Rp'.number_format($totalrow,0,',','.').'</b><br>';
								@endphp
						
								</center>
							</td>
							<td style="vertical-align:center;text-align:right; font-size:9pt">
								@php
								$totalrow = 0;
								foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
									$totaldelivered = round($rd->grandtotal_product + $rd->getServiceCost());
									echo 'Rp '.number_format($totaldelivered,0,',','.').'<br>';
									$totalrow += $totaldelivered;
								}
								echo '<b><hr>Rp'.number_format($totalrow,0,',','.').'</b><br>';
								@endphp
							</td>
								<td style="vertical-align:center;text-align:right; font-size:9pt">
								@php
								$totalrow = 0;
								foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
									echo $rd->proforma_code.'<br>';
								}
								@endphp
							</td>
							<td style="vertical-align:center;font-size:9pt;">
								<center>
									@php
									$sisa = $row->totalpay;
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
										$totaldelivered = round($rd->getTotal()['totaldelivery'] + $rd->getServiceCost());
										
										if($sisa >= $totaldelivered){
											echo date('d M Y',strtotime($rd->received_date)).'<br>';
										}else{
											if($rd->received_date <= date('Y-m-d')){ 
													echo date('d M Y',strtotime($rd->received_date)).'<br>';
											}else{
												echo date('d M Y',strtotime($rd->received_date)).'<br>';
											}
										}
										
										$sisa -= $totaldelivered;
									}
									@endphp
								</center>
							</td>
								<td style="vertical-align:center;font-size:9pt;">
								<center>
									@php
									$sisa = $row->totalpay;
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
										$totaldelivered = round($rd->getTotal()['totaldelivery'] + $rd->getServiceCost());
										
										if($sisa >= $totaldelivered){
												echo date('d M Y',strtotime($rd->received_date)).'<br>';
										}else{
											if($rd->received_date <= date('Y-m-d')){ 
												echo date('d M Y',strtotime($rd->received_date)).'<br>';
											}else{
												echo date('d M Y',strtotime($rd->received_date)).'<br>';
											}
										}
										
										$sisa -= $totaldelivered;
									}
									@endphp
								</center>
							</td>
					    	<td style="vertical-align:center; font-size:9pt;">
								<center>
									@php
									$sisa = $row->totalpay;
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
										$totaldelivered = round($rd->getTotal()['totaldelivery'] + $rd->getServiceCost());
										
										if($sisa >= $totaldelivered){
										    if(!$rd->due_date){
										    	echo '<span style="background-color:green;color:white;"> - </span><br>';
										    }else{
								        		echo date('d M Y',strtotime($rd->due_date)).'<br>';
										    }
										}else{
										    if(!$rd->due_date){
										        echo '<span style="background-color:red;color:white;"> - </span><br>';
										    }else if($rd->due_date <= date('Y-m-d')){ 
												echo date('d M Y',strtotime($rd->due_date)).'<br>';
											}else{
												echo date('d M Y',strtotime($rd->due_date)).'<br>';
											}
										}
										
										$sisa -= $totaldelivered;
									}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;text-align:right; font-size:12pt;">
								<b>{{ number_format($row->totalpay,0,',','.') }}</b>
							</td>
							<td style="vertical-align:center;text-align:right; font-size:12pt;">
								<b>{{ number_format($row->totalbalance,0,',','.') }}</b>
							</td>
							<td style="vertical-align:center; font-size:10pt;">
								<center>
									@php
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
								
									if($rd->due_date){
    									$due_date = Carbon\Carbon::parse($rd->due_date);
    									
    									$date_diff = $due_date->diffInDays(date('Y-m-d'),false);
    											echo '<span >'.abs($date_diff).'</span><br>';
									}else{
										$received_date = Carbon\Carbon::parse($rd->received_date);
    									
    									$date_diff = $received_date->diffInDays(date('Y-m-d'),false);
    											echo '<span >'.abs($date_diff).'</span><br>';
									}   
									
										
									}
									@endphp
								</center>
							</td>
						</tr>
						@php
							$grandtotalproject += $row->totalbalance;
							$no++;
						@endphp
					@endforeach
					<tr>
						<th colspan="8"></th>
						<th>Subtotal</th>
						<th>{{ number_format($grandtotalproject,0,',','.') }}</th>
						<th></th>
					</tr>
				</tbody>
			</table>
			<br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#b2bec3;text-align:center;">
						<th style="color:white;" colspan="11"><center>CUSTOMER DEPOSIT RECEIVABLE</center></th>
					</tr>
					<tr style="background:#b2bec3;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>CUSTOMER</center></th>
						<th style="color:white;"><center>PROJECT</center></th>
						<th style="color:white;"><center>AMOUNT</center></th>
						<th style="color:white;"><center>INV NO</center></th>
						<th style="color:white;"><center>DATE INVOICE</center></th>
						<th style="color:white; width:100px;"><center>DATE TT</center></th>
						<th style="color:white;"><center>DUE DATE PAYMENT</center></th>
						<th style="color:white;"><center>PAID</center></th>
						<th style="color:white;"><center>BALANCE</center></th>
						<th style="color:white;"><center>NOTES (OVER DUE DATE IN DAYS)</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($projectbill as $key => $row)
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $key + 1 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->project->customer->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->project->code.' BILL - '.$row->project->name }}
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;">
								{{ number_format(floatval($row->nominal) + floatval($row->nominal_service),0,',','.') }}
							</td>
							<td style="vertical-align:center;text-align:right;">
								{{ $row->code }}
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($row->date <= date('Y-m-d')){
											echo date('d M Y',strtotime($row->date)).'<br>';
										}else{ 
											echo date('d M Y',strtotime($row->date)).'<br>';
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($row->date <= date('Y-m-d')){
									    	echo date('d M Y',strtotime($row->date)).'<br>';
										}else{ 
											echo date('d M Y',strtotime($row->date)).'<br>';
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
										if($row->date <= date('Y-m-d')){
											echo date('d M Y',strtotime($row->due_date)).'<br>';
										}else{ 
											echo date('d M Y',strtotime($row->due_date)).'<br>';
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;font-size:10pt;">
								<b>{{ number_format($row->paid(),0,',','.') }}</b>
							</td>
							<td style="vertical-align:center;text-align:right;font-size:10pt;">
							    <b>	{{ number_format($row->balance(),0,',','.') }}</b>
							</td>
							<td style="vertical-align:center;text-align:center; font-size:10pt;">
								@php
									$due_date = Carbon\Carbon::parse($row->due_date);
									
									$date_diff = $due_date->diffInDays(date('Y-m-d'),false);
									echo '<span >'.abs($date_diff).'</span><br>';
								@endphp
							</td>
						</tr>
						@php
							$grandtotalbill += $row->balance();
						@endphp
					@endforeach
					<tr>
						<th colspan="8"></th>
						<th>Subtotal</th>
						<th>{{ number_format($grandtotalbill,0,',','.') }}</th>
						<th></th>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>