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
			font-size: 12px;
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
			@page {
				size: A4 portrait;
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

		@page {
			margin: 1cm;
		}

		body {
			margin: 1cm;
		}
	</style>
</head>

<body>
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
										{!! $title.' - '.$product_name !!}
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
					<th colspan="6">
						<h1>IN</h1>
					</th>
				</tr>
				<tr align="center">
					<th>Status.</th>
					<th>Description.</th>
					<th>Qty.</th>
					<th>Unit.</th>
					<th>Supplier.</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rowIn as $row)
				<tr align="center">
					<td>IN</td>
					<td>{{'Warehouse Receive '.$row->projectWarehouse->code.'- PO : '.$row->projectWarehouse->projectPurchase->code}}</td>
					<td>{{ $row->qty }}</td>
					<td>{{ $row->unit() }}</td>
					<td>{{ $row->projectWarehouse->projectPurchase->supplier->name }}</td>
					<td>{{ date('d M Y',strtotime($row->projectWarehouse->date_receive)) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center">
					<th colspan="6">
						<h1>OUT</h1>
					</th>
				</tr>
				<tr align="center">
					<th>Status.</th>
					<th>Description.</th>
					<th>Qty.</th>
					<th>Unit.</th>
					<th>Customer/ receiver.</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rowOut as $row)
				<tr align="center">
					<td>OUT</td>
					<td>{{'Delivery Order '.$row->projectDelivery->code}}</td>
					<td>{{ $row->qty }}</td>
					<td>{{ $row->unit() }}</td>
					<td><b>{{ $row->projectDelivery ? $row->projectDelivery->project->customer->name : '' }}</b></td>
					<td>{{ 'Delivery : '. date('d M Y',strtotime($row->projectDelivery->delivery_date)).' Received : '. date('d M Y',strtotime($row->projectDelivery->received_date)) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center">
					<th colspan="6">
						<h1>Retur IN</h1>
					</th>
				</tr>
				<tr align="center">
					<th>Status.</th>
					<th>Description.</th>
					<th>Qty.</th>
					<th>Unit.</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rowInReturn as $row)
				<tr align="center">
					<td>IN</td>
					<td>{{'Sale Return '.$row->projectSaleReturn->code}}</td>
					<td>{{ $row->qty }}</td>
					<td>{{ $row->unit() }}</td>
					<td>{{ date('d M Y',strtotime($row->projectSaleReturn->date_return)) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center">
					<th colspan="6">
						<h1>Retur OUT</h1>
					</th>
				</tr>
				<tr align="center">
					<th>Status.</th>
					<th>Description.</th>
					<th>Qty.</th>
					<th>Unit.</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rowOutReturn as $row)
				<tr align="center">
					<td>OUT</td>
					<td>{{'Purchase Return '.$row->projectPurchaseReturn->code}}</td>
					<td>{{ $row->qty }}</td>
					<td>{{ $row->unit() }}</td>
					<td>{{ date('d M Y',strtotime($row->projectPurchaseReturn->date)) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center">
					<th colspan="5">
						<h1>Transfer IN</h1>
					</th>
				</tr>
				<tr align="center">
					<th>Status.</th>
					<th>Description.</th>
					<th>Qty.</th>
					<th>Unit.</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rowInTransfer as $row)
				<tr align="center">
					<td>IN</td>
					<td>{{'Transfer number '.$row->transfer->code.' '.$row->transfer->note}}</td>
					<td>{{ $row->qty }}</td>
					<td>{{ $row->unit() }}</td>
					<td>{{ date('d M Y',strtotime($row->transfer->date)) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center">
					<th colspan="5">
						<h1>Transfer OUT</h1>
					</th>
				</tr>
				<tr align="center">
					<th>Status.</th>
					<th>Description.</th>
					<th>Qty.</th>
					<th>Unit.</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($rowOutTransfer as $row){
				<tr align="center">
					<td>IN</td>
					<td>{{'Transfer number '.$row->transfer->code.' '.$row->transfer->note}}</td>
					<td>{{ $row->qty }}</td>
					<td>{{ $row->unit() }}</td>
					<td>{{ date('d M Y',strtotime($row->transfer->date)) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<br><br>
	</div>
</body>

</html>