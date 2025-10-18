@php
	use App\Models\ProjectSale;
@endphp
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
				font-size:16px;
			}
		
			.invoice-box {
				font-size: 11px;
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
			
			@page { margin: 0.5cm; size: A4 landscape; }
			body { margin: 0.5cm; }
		</style>
	</head>
	<body onload="window.print()">
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
						<table>
							<tr style="background-color:#ebb220;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;font-size:25px !important;">
									<h3><b>SALES REPORT 2</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<hr style="margin-top:25px;">
			<br>
			@if(session('bo_branch') == '1')
				<h1 align="center">PTA</h1>
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
					<thead>
						<tr style="background:#ebb220;text-align:center;font-size:20px !important;">
							<th style="color:white;" width="3%" rowspan="2"><center>NO</center></th>
							<th style="color:white;" rowspan="2"><center>PJ NO.</center></th>
							<th style="color:white;" rowspan="2"><center>SO NO.</center></th>
							<th style="color:white;" rowspan="2"><center>CUSTOMER</center></th>
							<th style="color:white;" rowspan="2"><center>PROJECT</center></th>
							<th style="color:white;" rowspan="2"><center>SO REAL</center></th>
							<th style="color:white;" rowspan="2"><center>SO CLOSE</center></th>
							<th style="color:white;" rowspan="2"><center>SO FINAL</center></th>
							<th style="color:white;" colspan="4"><center>PAYMENT</center></th>
							<th style="color:white;" colspan="4"><center>DELIVERED/RETUR</center></th>
						</tr>
						<tr style="text-align:center;font-size:20px !important;">
							<th>No</th>
							<th>Date</th>
							<th>Nominal</th>
							<th>Balance</th>
							<th>No</th>
							<th>Date</th>
							<th>Nominal</th>
							<th>Balance</th>
						</tr>
					</thead>
					<tbody>
						@foreach(ProjectSale::whereHas('sales',function($query){ $query->where('branch','1'); })->get() as $key => $row)
							@php
								$rowspan = count($row->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->get()->toArray()) + count($row->projectSaleReturn()->get()->toArray()) + 1;
								$totalrow = $row->grandtotal_product + $row->grandtotal_service;
								$totalclose = 0;
								$totalfinal = $row->grandtotal_product + $row->grandtotal_service;
								$totalrowpay = $row->grandtotal_product + $row->grandtotal_service;
								if($row->is_closed){
									$totaldelivered = 0;
									$totalreturn = 0;
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
										$totaldelivered += ($rd->grandtotal_product + $rd->grandtotal_service);
									}
									foreach($row->projectSaleReturn as $rs){
										$totalreturn += $rs->grandtotal;
									}
									
									$totalclose = ($totalrow - $totaldelivered + $totalreturn);
									if($totalclose > 0){
										$totalfinal = $totalrow - $totalclose;
										$totalrowpay = $totalfinal;
									}
								}
							@endphp
							<tr>
								<td align="center" rowspan="{{ $rowspan }}">{{ $key+1 }}</td>
								<td align="center" rowspan="{{ $rowspan }}">{{ $row->project->code }}</td>
								<td align="center" rowspan="{{ $rowspan }}">{{ $row->code }}</td>
								<td align="center" rowspan="{{ $rowspan }}">{{ $row->project->customer->name }}</td>
								<td align="center" rowspan="{{ $rowspan }}">{{ $row->project->name }}</td>
								<td align="right" rowspan="{{ $rowspan }}">{{ number_format($totalrow,2,',','.') }}</td>
								<td align="right" rowspan="{{ $rowspan }}">{{ number_format($totalclose,2,',','.') }}</td>
								<td align="right" rowspan="{{ $rowspan }}">{{ number_format($totalfinal,2,',','.') }}</td>
								<td align="center" rowspan="{{ $rowspan }}">
									@foreach($row->projectSalePay as $rowpay)
										{!! $rowpay->code.'<br>' !!}
									@endforeach
								</td>
								<td align="center" rowspan="{{ $rowspan }}">
									@foreach($row->projectSalePay as $rowpay)
										{!! date('dMy',strtotime($rowpay->date)).'<br>' !!}
									@endforeach
								</td>
								<td align="right" rowspan="{{ $rowspan }}">
									@foreach($row->projectSalePay as $rowpay)
										{!! number_format($rowpay->nominal,0,',','.').'<br>' !!}
									@endforeach
								</td>
								<td align="right" rowspan="{{ $rowspan }}">
									@foreach($row->projectSalePay as $rowpay)
										@php
											$totalrowpay -= $rowpay->nominal;
										@endphp
										{!! number_format($totalrowpay,2,',','.').'<br>' !!}
									@endforeach
								</td>
								@if($rowspan == 1)
								<td align="center">-</td>
								<td align="center">-</td>
								<td align="center">-</td>
								<td align="center">-</td>
								@endif
							</tr>
							@if($rowspan > 1)
								@foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd)
									@php
										$totalfinal -= ($rd->grandtotal_product + $rd->grandtotal_service);
									@endphp
									<tr>
									<td align="center">{{ $rd->code }}</td>
									<td align="center">{{ date('dMy',strtotime($rd->received_date)) }}</td>
									<td align="right">- {{ number_format($rd->grandtotal_product + $rd->grandtotal_service,2,',','.') }}</td>
									<td align="right">{{ number_format($totalfinal,2,',','.') }}</td>
									</tr>
								@endforeach
								@foreach($row->projectSaleReturn as $rs)
									@php
										$totalfinal += $rs->grandtotal;
									@endphp
									<tr>
									<td align="center">{{ $rs->code }}</td>
									<td align="center">{{ date('dMy',strtotime($rs->date_return)) }}</td>
									<td align="right">+ {{ number_format($rs->grandtotal,2,',','.') }}</td>
									<td align="right">{{ number_format($totalfinal,2,',','.') }}</td>
									</tr>
								@endforeach
							@endif
						@endforeach
					</tbody>
				</table>
			@endif
			@if(session('bo_branch') == '1' || session('bo_branch') == '2')
				<h1 align="center">SMB</h1>
				<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
					<thead>
						<tr style="background:#ebb220;text-align:center;font-size:20px !important;">
							<th style="color:white;" width="3%" rowspan="2"><center>NO</center></th>
							<th style="color:white;" rowspan="2"><center>PJ NO.</center></th>
							<th style="color:white;" rowspan="2"><center>SO NO.</center></th>
							<th style="color:white;" rowspan="2"><center>CUSTOMER</center></th>
							<th style="color:white;" rowspan="2"><center>PROJECT</center></th>
							<th style="color:white;" rowspan="2"><center>SO REAL</center></th>
							<th style="color:white;" rowspan="2"><center>SO CLOSE</center></th>
							<th style="color:white;" rowspan="2"><center>SO FINAL</center></th>
							<th style="color:white;" colspan="4"><center>PAYMENT</center></th>
							<th style="color:white;" colspan="4"><center>DELIVERED/RETUR</center></th>
						</tr>
						<tr style="text-align:center;font-size:20px !important;">
							<th>No</th>
							<th>Date</th>
							<th>Nominal</th>
							<th>Balance</th>
							<th>No</th>
							<th>Date</th>
							<th>Nominal</th>
							<th>Balance</th>
						</tr>
					</thead>
					<tbody>
						@foreach(ProjectSale::whereHas('sales',function($query){ $query->where('branch','2'); })->get() as $key => $row)
							@php
								$rowspan = count($row->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->get()->toArray()) + count($row->projectSaleReturn()->get()->toArray()) + 1;
								$totalrow = $row->grandtotal_product + $row->grandtotal_service;
								$totalclose = 0;
								$totalfinal = $row->grandtotal_product + $row->grandtotal_service;
								$totalrowpay = $row->grandtotal_product + $row->grandtotal_service;
								if($row->is_closed){
									$totaldelivered = 0;
									$totalreturn = 0;
									foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
										$totaldelivered += ($rd->grandtotal_product + $rd->grandtotal_service);
									}
									foreach($row->projectSaleReturn as $rs){
										$totalreturn += $rs->grandtotal;
									}
									
									$totalclose = ($totalrow - $totaldelivered + $totalreturn);
									if($totalclose > 0){
										$totalfinal = $totalrow - $totalclose;
										$totalrowpay = $totalfinal;
									}
								}
							@endphp
							<tr>
								<td align="center" rowspan="{{ $rowspan }}">{{ $key+1 }}</td>
								<td align="center" rowspan="{{ $rowspan }}">{{ $row->project->code }}</td>
								<td align="center" rowspan="{{ $rowspan }}">{{ $row->code }}</td>
								<td align="center" rowspan="{{ $rowspan }}">{{ $row->project->customer->name }}</td>
								<td align="center" rowspan="{{ $rowspan }}">{{ $row->project->name }}</td>
								<td align="right" rowspan="{{ $rowspan }}">{{ number_format($totalrow,2,',','.') }}</td>
								<td align="right" rowspan="{{ $rowspan }}">{{ number_format($totalclose,2,',','.') }}</td>
								<td align="right" rowspan="{{ $rowspan }}">{{ number_format($totalfinal,2,',','.') }}</td>
								<td align="center" rowspan="{{ $rowspan }}">
									@foreach($row->projectSalePay as $rowpay)
										{!! $rowpay->code.'<br>' !!}
									@endforeach
								</td>
								<td align="center" rowspan="{{ $rowspan }}">
									@foreach($row->projectSalePay as $rowpay)
										{!! date('dMy',strtotime($rowpay->date)).'<br>' !!}
									@endforeach
								</td>
								<td align="right" rowspan="{{ $rowspan }}">
									@foreach($row->projectSalePay as $rowpay)
										{!! number_format($rowpay->nominal,0,',','.').'<br>' !!}
									@endforeach
								</td>
								<td align="right" rowspan="{{ $rowspan }}">
									@foreach($row->projectSalePay as $rowpay)
										@php
											$totalrowpay -= $rowpay->nominal;
										@endphp
										{!! number_format($totalrowpay,2,',','.').'<br>' !!}
									@endforeach
								</td>
								@if($rowspan == 1)
								<td align="center">-</td>
								<td align="center">-</td>
								<td align="center">-</td>
								<td align="center">-</td>
								@endif
							</tr>
							@if($rowspan > 1)
								@foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd)
									@php
										$totalfinal -= ($rd->grandtotal_product + $rd->grandtotal_service);
									@endphp
									<tr>
									<td align="center">{{ $rd->code }}</td>
									<td align="center">{{ date('dMy',strtotime($rd->received_date)) }}</td>
									<td align="right">- {{ number_format($rd->grandtotal_product + $rd->grandtotal_service,2,',','.') }}</td>
									<td align="right">{{ number_format($totalfinal,2,',','.') }}</td>
									</tr>
								@endforeach
								@foreach($row->projectSaleReturn as $rs)
									@php
										$totalfinal += $rs->grandtotal;
									@endphp
									<tr>
									<td align="center">{{ $rs->code }}</td>
									<td align="center">{{ date('dMy',strtotime($rs->date_return)) }}</td>
									<td align="right">+ {{ number_format($rs->grandtotal,2,',','.') }}</td>
									<td align="right">{{ number_format($totalfinal,2,',','.') }}</td>
									</tr>
								@endforeach
							@endif
						@endforeach
					</tbody>
				</table>
			@endif
		</div>
	</body>
</html>