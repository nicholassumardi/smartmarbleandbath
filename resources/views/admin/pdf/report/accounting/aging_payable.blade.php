@php
use App\Models\CashBank;
use App\Models\PurchaseCost;
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
										{!! $title.' - '.$supplier_name !!}
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
						<h1>Purchase Return</h1>
					</th>
				</tr>
				<tr align="center">
					<th>PJ No.</th>
					<th>SO No.</th>
					<th>DO No.</th>
					<th>Received Date</th>
					<th>Nominal(Incl.Tax)</th>
				</tr>
			</thead>
			<tbody>
				@php
				$totalreturn = 0;
				@endphp
				@foreach($data as $row)
				@foreach($row->projectPurchaseReturn as $rowpd)
				<tr align="center">
					<td>{{ isset($rowpd->project->code) ? $rowpd->project->code : 'NO CODE'}}</td>
					<td>{{ isset($rowpd->projectPurchase->code) ? $rowpd->projectPurchase->code : 'NO CODE' }}</td>
					<td>{{ isset($rowpd->code) ? $rowpd->code : 'NO CODE'}}</td>
					<td>{{ date('d M Y',strtotime($rowpd->date)) }}</td>
					<td align="right"><b>{{ number_format(round($rowpd->getTotal()),2,',','.') }}</b></td>
				</tr>
				@php
				$totalreturn += round($rowpd->getTotal());
				@endphp
				@endforeach
				@endforeach
			</tbody>
		</table>
		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center">
					<th colspan="6">
						<h1>Project Warehouse</h1>
					</th>
				</tr>
				<tr align="center">
					<th>PJ No.</th>
					<th>WR No.</th>
					<th>Received Date</th>
					<th>Nominal(Incl.Tax)</th>
					<th>Paid</th>
					<th>Retur</th>
					<th>Balance(Payable)</th>
				</tr>
			</thead>
			<tbody>
				@php
				$totalreceived = 0;
				$total_paid_project = 0;
				$totalreturnfinal = 0;
				@endphp
				@foreach($data as $row)
				@php
					$totalpaid = floatval(str_replace(',','.',str_replace('.','',$row->getPaid())));
					$total_wr = 0;
					$pj_code= '';
					$wr_code= '';
					$date_receive = '';
					$totalreturnwr = 0;
				@endphp
				@foreach($row->projectWarehouse->whereNotNull('date_receive')->where('date_receive', '<=', $date) as $wrow)
				@php
					$total_wr += $wrow->grandtotal;
					$pj_code = isset($wrow->project->code) ? $wrow->project->code : 'Unknown';
					$wr_code .= isset( $wrow->code) ?  $wrow->code.'<br>' :'Unknown <br>';
					$date_receive .= date('d M Y',strtotime($wrow->date_receive))."<br>";
				@endphp
				@endforeach
				@foreach($row->projectPurchaseReturn()->where('date', '<=', $date)->get() as $rowpd)
				@php
					$totalreturnwr += round($rowpd->getTotal());
				@endphp
				@endforeach
				@php
				$balance = $total_wr - $totalpaid - $totalreturnwr;
				@endphp
				@if($balance > 0)
				<tr align="center">
					<td>{{ isset($pj_code) ? $pj_code : 'NO CODE' }}</td>
					<td>{!! isset($wr_code) ? $wr_code : 'NO CODE' !!}</td>
					<td>{!! $date_receive !!}</td>
					<td align="right"><b>{{ number_format($total_wr,2,',','.') }}</b></td>
					<td align="right"><b>{{ number_format($totalpaid,2,',','.') }}</b></td>
					<td align="right"><b>{{ number_format($totalreturnwr,2,',','.') }}</b></td>
					<td align="right"><b>{{ number_format( $balance >= 0 ? $balance : 0 ,2,',','.') }}</b>
					</td>
				</tr>
				@php
					$totalreceived += $total_wr;
					$total_paid_project += $totalpaid ;
					$totalreturnfinal += ($total_wr - $totalpaid) - $totalreturnwr >= 0 ? $totalreturnwr : 0;
				@endphp
				@endif
				@endforeach
			</tbody>
		</table>
		<br><br>

		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center">
					<th colspan="4">
						<h1>ADJUSTMENT</h1>
					</th>
				</tr>
				<tr align="center">
					<th>PO No.</th>
					<th>CB No.</th>
					<th>Date</th>
					<th>Nominal(Incl.Tax)</th>
				</tr>
			</thead>
			<tbody>
				@php
				$totalcb = 0;
				@endphp
				@foreach($data as $row)
				@foreach(CashBank::where('lookable_type', 'project_purchases')->where('lookable_id', $row->id)->where('date', '<=', $date)->get() as
				$rowcb)
				@foreach($rowcb->cashBankDetail()->where('coa_id', 332)->get() as $cbcb)
				@if ($cbcb->type == '1')
				<tr align="center">
					<td>{{ isset($rowcb->lookable->code) ? $rowcb->lookable->code : 'NO CODE' }}</td>
					<td>{{ isset($rowcb->code) ? $rowcb->code : 'NO CODE' }}</td>
					<td>{{ date('d M Y',strtotime($rowcb->date)) }}</td>
					<td align="right"><b>{{ number_format($cbcb->nominal,2,',','.') }}</b></td>
				</tr>
				@php
				$totalcb += $cbcb->nominal;
				@endphp
				@endif
				@endforeach
				@endforeach
				@endforeach
			</tbody>
		</table>
		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			<thead>
				<tr align="center">
					<th colspan="6">
						<h1>Purchase COST</h1>
					</th>
				</tr>
				<tr align="center">
					<th>PO No.</th>
					<th>Nominal(Incl.Tax)</th>
				</tr>
			</thead>
			<tbody>
				@php
				$totalpc = 0;
				@endphp
				@foreach($data as $row)
				@php
				$pc = PurchaseCost::where('project_purchase_id', $row->id)->first()
				@endphp
				@if ($pc)
				<td>{{ isset($pc->purchase->code) ? $pc->purchase->code : 'NO CODE' }}</td>
				<td>{{ number_format($pc->totalCost(),2,',','.') }}</td>
				@php
				$totalpc += $pc->totalCost();
				@endphp
				@endif
				@endforeach
			</tbody>
		</table>
		<br><br>
		<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
			@php
			$finalpaid = $total_paid_project + $totalcb + $totalreturnfinal + $totalpc;
			$finalbalanace = $totalreceived - $finalpaid;
			@endphp
			<tbody>
				<tr align="center">
					<td width="33%">
						<h3>Total Payable</h3>
						<h1>{{ number_format($totalreceived,2,',','.') }}</h1>
					</td>
					<td width="33%">
						<h3>Paid</h3>
						<h1>{{ number_format($finalpaid,2,',','.') }}</h1>
					</td>
					<td width="33%">
						<h3>Balance</h3>
						<h1>{{ number_format($finalbalanace,2,',','.')}}</h1>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>

</html>