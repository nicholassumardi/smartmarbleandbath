@php
	use App\Models\CashBank;
@endphp
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>{{ $title }}</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
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
				/* text-align: right; */
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
			
			@media print {
				@page {size: A4 portrait; }
				table{
					width: 100% !important;
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
	<body onload="window.print()">
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td colspan="2">
						<table>
							<tr>
								<td style="text-align:center;">
									<img src="{{ url('website/logo_al_rev_3.jpg') }}" width="auto" height="100px">
								</td>
							</tr>
							<tr>
								<td style="text-align:center;">
									<h2>
										<b>
											{!! $title.' - '.$customer_name !!}
										</b>
									</h2>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th colspan="13"><h1>AR CARD</h1></th>
					</tr>
					<tr align="center">
						<th>DESCRIPTION</th>
						<th>DATE</th>
						<th>DEBIT</th>
						<th>CREDIT</th>
						<th>BALANCE</th>
						<th>NOTE</th>
					</tr>
				</thead>
				<tbody>
					@php
					$total_debit = 0;
					$total_customer_deposit = 0;
					$total_credit = 0;
					$balance_each_transaction = 0;
					@endphp
					@foreach($arrayResult as $row)
					@if ($row['type'] == 'debit')
					@php
					$balance_each_transaction += $row['nominal'];
					@endphp
					<tr align="center">
						<td><b>{{$row['description']}}</b>
						</td>
						<td>{{ date('d M Y', strtotime($row['date'])) }}</td>
						<td><b>{{number_format( $row['nominal'],2,',','.') }}</b></td>
						<td><b> </b></td>
						<td align="right"><b>{{number_format( $balance_each_transaction,2,',','.') }}</b>
						</td>
						<td></td>
					</tr>
					@php
					$total_debit += $row['nominal'];
					@endphp
					@elseif($row['type'] == 'customer_deposit')
					@php
					$balance_each_transaction -= $row['nominal'];
					@endphp
					<tr align="center">
						<td><b>{{$row['description']}}</b></td>
						<td>{{ date('d M Y', strtotime($row['date'])) }}</td>
						<td><b> </b></td>
						<td><b>{{number_format( $row['nominal'],2,',','.') }}</b></td>
						<td align="right"><b>{{number_format( $balance_each_transaction,2,',','.') }}</b>
						</td>
						<td><b>{{$row['note']}}</b></td>
					</tr>
					@php
					$total_customer_deposit += $row['nominal'];
					$total_credit += $row['nominal'];
					@endphp
					@else
					@php
					$balance_each_transaction -= $row['nominal'];
					@endphp
					<tr align="center">
						<td><b>{{$row['description']}}</b>
						</td>
						<td>{{ date('d M Y', strtotime($row['date'])) }}</td>
						<td><b> </b></td>
						<td><b>{{number_format( $row['nominal'],2,',','.') }}</b></td>
						<td align="right"><b>{{number_format( $balance_each_transaction,2,',','.') }}</b>
						</td>
						<td></td>
					</tr>
					@php
					$total_credit += $row['nominal'];
					@endphp
					@endif
					@endforeach
					@php
					$balance = $total_debit- $total_credit;
					@endphp
				</tbody>
			</table>
			<br><br>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<tbody>
					<tr align="center">
						<td width="25%"><h3>TOTAL DEBIT</h3><h1>{{number_format($total_debit,2,',','.')}}</h1></td>
						<td width="25%"><h3>TOTAL CREDIT</h3><h1>{{number_format($total_credit,2,',','.')}}</h1></td>
						<td width="25%"><h3>BALANCE</h3><h1>{{number_format($balance,2,',','.')}}</h1></td> 
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>