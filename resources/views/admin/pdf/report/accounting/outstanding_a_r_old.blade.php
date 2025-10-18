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
				font-size: 14px;
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
							<tr style="background-color:#ebb220;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>OUTSTANDING A/R REPORT {{ $branch == '1' ? 'SURABAYA' : 'JAKARTA' }} {{ strtoupper(date('F',strtotime($filter))) }} {{ date('Y',strtotime($filter)) }}</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:9px;">
				<thead>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" colspan="8"><center>RECEIVABLE</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>CUSTOMER</center></th>
						<th style="color:white;"><center>PROJECT</center></th>
						<th style="color:white;"><center>AMOUNT</center></th>
						<th style="color:white;"><center>DUE DATE PAYMENT</center></th>
						<th style="color:white;"><center>DUE DATE TT</center></th>
						<th style="color:white;"><center>PAID</center></th>
						<th style="color:white;"><center>BALANCE</center></th>
						<th style="color:white;"><center>NOTES</center></th>
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
							<td style="vertical-align:center;">
								<center>
									{{ $row->project->customer->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $row->project->code.' - '.$row->project->name }}
									<br>
									Total Project : Rp {{ $row->getTotal() }}
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;">
								@php
								$totalrow = 0;
								foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
									$totaldelivered = round($rd->getTotal()['totaldelivery'] + $rd->getServiceCost());
									echo 'Rp '.number_format($totaldelivered,0,',','.').'<br>';
									$totalrow += $totaldelivered;
								}
								echo '<b><hr>Rp'.number_format($totalrow,0,',','.').'</b><br>';
								@endphp
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
									$sisa = $row->totalpay;
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
										$totaldelivered = round($rd->getTotal()['totaldelivery'] + $rd->getServiceCost());
										
										if($sisa >= $totaldelivered){
											echo '<span style="background-color:green;color:white;">'.date('d M Y',strtotime($rd->due_date)).'</span><br>';
										}else{
											if($rd->due_date <= date('Y-m-d')){ 
												echo '<span style="background-color:red;color:white;">'.date('d M Y',strtotime($rd->due_date)).'</span><br>';
											}else{
												echo date('d M Y',strtotime($rd->due_date)).'<br>';
											}
										}
										
										$sisa -= $totaldelivered;
									}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									@php
									$sisa = $row->totalpay;
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
										$totaldelivered = round($rd->getTotal()['totaldelivery'] + $rd->getServiceCost());
										
										if($sisa >= $totaldelivered){
											if($rd->due_date_tt){
											 echo '<span style="background-color:green;color:white;">'.date('d M Y',strtotime($rd->due_date_tt)).'</span><br>';
											}else{
											 echo '<span style="background-color:green;color:white;">Belum TT</span><br>';
											}
										}else{
											if(!$rd->due_date_tt){
												echo '<span style="background-color:red;color:white;">Belum TT</span><br>';
											}else if($rd->due_date_tt <= date('Y-m-d')){ 
												echo '<span style="background-color:red;color:white;">'.date('d M Y',strtotime($rd->due_date_tt)).'</span><br>';
											}else{
												echo $rd->due_date_tt ? date('d M Y',strtotime($rd->due_date_tt)).'<br>' : 'Belum TT <br>';
											}
										}
										
										$sisa -= $totaldelivered;
									}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;">
								{{ number_format($row->totalpay,0,',','.') }}
							</td>
							<td style="vertical-align:center;text-align:right;">
								{{ number_format($row->totalbalance,0,',','.') }}
							</td>
							<td style="vertical-align:center;text-align:right;">
								@php
									foreach(App\Models\ProjectNote::where('notable_type','project_sales')->where('notable_id',$row->id)->get() as $rownote){
										echo $rownote->note.'<br>';
									}
									
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
										foreach(App\Models\ProjectNote::where('notable_type','project_deliveries')->where('notable_id',$rd->id)->get() as $rownote){
											echo $rownote->note.'<br>';
										}
									}
								@endphp
							</td>
						</tr>
						@php
							$grandtotalproject += $row->totalbalance;
							$no++;
						@endphp
					@endforeach
					<tr>
						<th colspan="6"></th>
						<th>Subtotal</th>
						<th>{{ number_format($grandtotalproject,0,',','.') }}</th>
						<th></th>
					</tr>
				</tbody>
			</table>
			<br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;" colspan="8"><center>CUSTOMER DEPOSIT RECEIVABLE</center></th>
					</tr>
					<tr style="background:#ebb220;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>CUSTOMER</center></th>
						<th style="color:white;"><center>PROJECT</center></th>
						<th style="color:white;"><center>AMOUNT</center></th>
						<th style="color:white;"><center>DUE DATE</center></th>
						<th style="color:white;"><center>PAID</center></th>
						<th style="color:white;"><center>BALANCE</center></th>
						<th style="color:white;"><center>NOTES</center></th>
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
							<td style="vertical-align:center;">
								<center>
									@php
										if($row->due_date <= date('Y-m-d')){
											echo '<span style="background-color:red;color:white;">'.$row->due_date.'</span><br>';
										}else{ 
											echo $row->due_date.'<br>';
										}
									@endphp
								</center>
							</td>
							<td style="vertical-align:center;text-align:right;">
								{{ number_format($row->paid(),0,',','.') }}
							</td>
							<td style="vertical-align:center;text-align:right;">
								{{ number_format($row->balance(),0,',','.') }}
							</td>
							<td style="vertical-align:center;text-align:right;">
								-
							</td>
						</tr>
						@php
							$grandtotalbill += $row->balance();
						@endphp
					@endforeach
					<tr>
						<th colspan="5"></th>
						<th>Subtotal</th>
						<th>{{ number_format($grandtotalbill,0,',','.') }}</th>
						<th></th>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>