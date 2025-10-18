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
						<th colspan="7"><h1>UNPAID RECEIVABLE</h1></th>
					</tr>
					<tr align="center">
						<th>INV NO</th>
						<th>DATE RECEIVED</th>
						<th>Nominal</th>
						<th>PAID</th>
						<th>RETURN</th>
						<th>BALANCE</th>
						<th>DUE DATE</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($data as $row)

						@php
							$unpaid_bill =0;
							$unpaid_delivery = 0;
							$total_return = 0;
						@endphp

						{{-- GET NOMINAL BILL UNPAID --}}
						@foreach ($row->projectBill as $rb)
							@if (($rb->nominal + $rb->nominal_service) - $rb->paidAR()['pays'] > 0)
								@php
									$unpaid_bill += $rb->nominal + $rb->nominal_service;
								@endphp
							@endif
						@endforeach

						{{-- GET NOMINAL DELIVERY UNPAID --}}
						@foreach ($row->projectDelivery->whereNotNull('received_date')->where('is_sales','1') as $rpd)
							@if(($rpd->grandtotal_product + $rpd->grandtotal_service) - $rpd->totalPayAR()['total'] - $total_return > 0)
								@php
									$unpaid_delivery += $rpd->grandtotal_product + $rpd->grandtotal_service;
								@endphp
							@endif
						@endforeach

						{{-- GET NOMINAL RETURN --}}
						@foreach($row->projectSaleReturn as $rowsr)
							@php
								$total_return += $rowsr->grandtotal;
							@endphp
						@endforeach

						@foreach ($row->projectDelivery->whereNotNull('received_date')->where('is_sales','1') as $rowpd)
							@php
								$nominal_invoice = $rowpd->grandtotal_product + $rowpd->grandtotal_service;
							@endphp
							@if($nominal_invoice - $rowpd->totalPayAR()['total'] - $total_return > 0 && $unpaid_bill - $unpaid_delivery < 0)
							<tr align="center">  
								<td>{{$rowpd->proforma_code}}</td>
								<td>{{date('d M Y', strtotime($rowpd->received_date))}}</td>
								<td>{{number_format($nominal_invoice,2,',','.')}}</td>
								<td>{{number_format($rowpd->totalPayAR()['total'],2,',','.')}}</td>
								<td>{{number_format($total_return,2,',','.')}}</td>
								<td>{{number_format($nominal_invoice - $rowpd->totalPayAR()['total'] - $total_return,2,',','.')}}</td>
								<td>{{date('d M Y', strtotime($rowpd->due_date))}}</td>
							</tr>
							@endif
						@endforeach
					@endforeach
				</tbody>
			</table>
			<br><br>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th colspan="6"><h1>UNPAID BILL</h1></th>
					</tr>
					<tr align="center">
						<th>PJ No.</th>
						<th>BILL No.</th>
						<th>Date</th>
						<th>Nominal(Incl.Tax)</th>
						<th>PAID</th>
						<th>BALANCE</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($data as $row)
						@foreach($row->projectBill  as $rowbill)
						@php
							$nominal_bill = $rowbill->nominal + $rowbill->nominal_service;
						@endphp
							@if ($nominal_bill - $rowbill->paidAR()['pays'] > 0)
							<tr align="center">
								<td>{{ $rowbill->project->code }}</td>
								<td>{{ $rowbill->code }}</td>
								<td>{{ date('d M Y',strtotime($rowbill->date)) }}</td>
								<td align="right"><b>{{ number_format($nominal_bill,2,',','.') }}</b></td>
								<td align="right"><b>{{ number_format($rowbill->paidAR()['pays'],2,',','.') }}</b></td>
								<td align="right"><b>{{ number_format($nominal_bill - $rowbill->paidAR()['pays'],2,',','.') }}</b></td>
							</tr>
							@endif
						@endforeach
					@endforeach

				</tbody>
			</table>
		</div>
	</body>
</html>