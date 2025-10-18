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
						<th colspan="6"><h1>DELIVERY</h1></th>
					</tr>
					<tr align="center">
						<th>DO No.</th>
						<th>Prof No.</th>
						<th>Date</th>
						<th>DEBIT</th>
					</tr>
				</thead>
				<tbody>
					@php
						$totaldelivery = 0;
						$totalpay = 0;
					@endphp
					@foreach($data as $row)
						@foreach($row->projectDelivery->whereNotNull('received_date')->where('is_sales','1') as $rowpd)
							<tr align="center">
								<td>{{ $rowpd->code }}</td>
								<td>{{ $rowpd->proforma_code }}</td>
								<td>{{ date('d M Y',strtotime($rowpd->received_date)) }}</td>
								<td align="right"><b>{{ number_format($rowpd->grandtotal_product + $rowpd->grandtotal_service,2,',','.') }}</b></td>
							</tr>
							@php
								$totaldelivery += $rowpd->grandtotal_product + $rowpd->grandtotal_service;
							@endphp
						@endforeach
					@endforeach
				</tbody>
			</table>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th colspan="6"><h1>RETURN</h1></th>
					</tr>
					<tr align="center">
						<th>PJ No.</th>
						<th>SO No.</th>
						<th>SR No.</th>
						<th>Date</th>
						<th>Nominal(Incl.Tax)</th>
					</tr>
				</thead>
				<tbody>
					@php
						$totalreturn = 0;
					@endphp
					@foreach($data as $row)
						@foreach($row->projectSaleReturn as $rowsr)
							<tr align="center">
								<td>{{ $rowsr->project->code }}</td>
								<td>{{ $rowsr->projectSale->code }}</td>
								<td>{{ $rowsr->code }}</td>
								<td>{{ date('d M Y',strtotime($rowsr->date_return)) }}</td>
								<td align="right"><b>{{ number_format($rowsr->grandtotal,2,',','.') }}</b></td>
							</tr>
							@php
								$totalreturn += $rowsr->grandtotal;
								$totaldelivery -= $rowsr->grandtotal;
							@endphp
						@endforeach
					@endforeach
				</tbody>
			</table>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th colspan="6"><h1>BILL</h1></th>
					</tr>
					<tr align="center">
						<th>PJ No.</th>
						<th>BILL No.</th>
						<th>Date</th>
						<th>Nominal(Incl.Tax)</th>
						<th>Paid</th>
						<th>Balance(Receivable)</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $row)
						@foreach($row->projectBill as $rowbill)
							@php
								$pay = $rowbill->paid();
								$balancebill = $rowbill->nominal + $rowbill->nominal_service - $pay;
							@endphp
							<tr align="center">
								<td>{{ $rowbill->project->code }}</td>
								<td>{{ $rowbill->code }}</td>
								<td>{{ date('d M Y',strtotime($rowbill->date)) }}</td>
								<td align="right"><b>{{ number_format($rowbill->nominal + $rowbill->nominal_service,2,',','.') }}</b></td>
								<td align="right"><b>{{ number_format($pay,2,',','.') }}</b></td>
								<td align="right"><b>{{ number_format($balancebill >= 0 ? $balancebill : 0,2,',','.') }}</b></td>
							</tr>
							@php
								$totalpay += ($balancebill >= 0 ? $balancebill : 0);
								$totaldelivery += ($balancebill >= 0 ? $balancebill : 0);
							@endphp
						@endforeach
					@endforeach
				</tbody>
			</table>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th colspan="5"><h1>PAYMENT</h1></th>
					</tr>
					<tr align="center">
						<th>PJ No.</th>
						<th>SO No.</th>
						<th>INV No.</th>
						<th>Date</th>
						<th>Nominal(Incl.Tax)</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $row)
						@foreach($row->projectPay()->whereHas('coa',function($query){
							$query->where('code','like',"1.000.010%")
								->orWhere('code','like',"1.000.020%")
								->orWhere('code','like',"1.000.021%")
								->orWhere('code','like',"1.000.021%")
								->orWhereIn('parent_id',[9,12,15,18]);
						})->get() as $rowpay)
							<tr align="center">
								<td>{{ $rowpay->project->code }}</td>
								<td>{{ $rowpay->projectSale->code }}</td>
								<td>{{ $rowpay->code }}</td>
								<td>{{ date('d M Y',strtotime($rowpay->date)) }}</td>
								<td align="right"><b>{{ number_format($rowpay->nominal,2,',','.') }}</b></td>
							</tr>
							@php
								$totalpay += $rowpay->nominal;
							@endphp
						@endforeach
					@endforeach
				</tbody>
			</table>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th colspan="4"><h1>ADJUSTMENT</h1></th>
					</tr>
					<tr align="center">
						<th>PJ No.</th>
						<th>CB No.</th>
						<th>Date</th>
						<th>Nominal(Incl.Tax)</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $row)
						@foreach(CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->id)->get() as $rowcb)
							@foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb)
								<tr align="center">
									<td>{{ $rowcb->lookable->code }}</td>
									<td>{{ $rowcb->code }}</td>
									<td>{{ date('d M Y',strtotime($rowcb->date)) }}</td>
									<td align="right"><b>{{ number_format($cbcb->nominal,2,',','.') }}</b></td>
								</tr>
								@php
									$totalpay += $cbcb->nominal;
								@endphp
							@endforeach
						@endforeach
					@endforeach
				</tbody>
			</table>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<tbody>
					<tr align="center">
						<td width="33%"><h3>Total Receivable</h3><h1>{{ number_format($totaldelivery,2,',','.') }}</h1></td>
						<td width="33%"><h3>Paid</h3><h1>{{ number_format($totalpay,2,',','.') }}</h1></td>
						<td width="33%"><h3>Balance</h3><h1>{{ number_format($totaldelivery - $totalpay,2,',','.') }}</h1></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>