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
						<th colspan="13"><h1>DELIVERY</h1></th>
					</tr>
					<tr align="center">
						<th>Prof No.</th>
						<th>Date Received</th>
						<th>Product Name</th>
						<th>Qty</th>
						<th>Price @</th>
						<th>TAX</th>
						<th>Service Charge</th>
						<th>Total Nominal(Incl.Tax)</th>
						<th>DUE DATE.</th>
					</tr>
				</thead>
				<tbody>
					@php
						$totaldelivery = 0;
						$totalpay = 0;
						$cust_depo =0;
						$unpaid_ar =0;
						$total_paid = 0;
					@endphp
					@foreach($data as $row)
					@php
						$totaldeliveryperproject = 0;
						$total_delivery_paid = 0;
						$total_return = 0;
						$coa_name = '';
						$bill_paid = 0;
						$totaladjust = 0;
						$bill_nominal = 0;
					@endphp
					
					@foreach($row->projectDelivery->whereNotNull('received_date')->where('is_sales','1') as $rowpd)
						<tr align="center" >
							<td rowspan="{{count($rowpd->projectDeliveryProduct)}}">{{ $rowpd->proforma_code }}</td>
							<td rowspan="{{count($rowpd->projectDeliveryProduct)}}">{{ date('d M Y',strtotime($rowpd->received_date)) }}</td>
						
							@foreach ($rowpd->projectDeliveryProduct as $key => $rowpdp)
								@if ($key == 0)
									<td>{{$rowpdp->product->name()}}</td>
									<td>{{$rowpdp->qty}}</td>
									<td>{{$rowpdp->salePrice()}}</td>
									<td rowspan="{{count($rowpd->projectDeliveryProduct)}}" align="right"><b>{{ number_format( $rowpd->tax_product,2,',','.') }}</b></td>
									<td rowspan="{{count($rowpd->projectDeliveryProduct)}}" align="right"><b>{{ number_format( $rowpd->grandtotal_service,2,',','.') }}</b></td>
									<td rowspan="{{count($rowpd->projectDeliveryProduct)}}" align="right"><b>{{ number_format($rowpd->grandtotal_product + $rowpd->grandtotal_service,2,',','.') }}</b></td>
									<td rowspan="{{count($rowpd->projectDeliveryProduct)}}" align="center"><b>{{date('d M Y',strtotime($rowpd->due_date)) }}</b></td>
								@else
								<tr align="center">  
									<td>{{$rowpdp->product->name()}}</td>
									<td>{{$rowpdp->qty}}</td>
									<td>{{$rowpdp->salePrice()}}</td>
								</tr>
								@endif
							@endforeach
						</tr>

						@php
						    $totaldeliveryperproject += $rowpd->grandtotal_product + $rowpd->grandtotal_service;
							$totaldelivery += $rowpd->grandtotal_product + $rowpd->grandtotal_service;
							$total_delivery_paid = $rowpd->totalpayAR()['total'];
							$coa_name = $rowpd->totalpayAR()['coa_name'].'<br>';
						@endphp
					
					@endforeach
					@if ($totaldeliveryperproject > 0)
						<tr>
							<th colspan="6"></th>
							<th>TOTAL</th>
							<th  align="right">{{number_format($totaldeliveryperproject,2,',','.')}}</th>
							<th></th>
						</tr>
						@foreach($row->projectSaleReturn as $rowsr)
							@php
								$total_return += $rowsr->grandtotal;
							@endphp
						@endforeach
						<tr>
							<th colspan="6"></th>
							<th>RETURN</th>
							<th  align="right">{{number_format($total_return,2,',','.')}}</th>
							<th></th>
						</tr>

						@foreach ($row->projectBill as $rowbill)
						@php
						$bill_paid = $rowbill->paidAR()['pays'] > 0 ? $rowbill->nominal + $rowbill->nominal_service - $rowbill->paidAR()['pays'] : 0;
						$bill_nominal =  $rowbill->paidAR()['pays'] -  $rowbill->nominal + $rowbill->nominal_service;
						$coa_name .= $rowbill->paidAR()['coa_name'].'<br>'; 
						@endphp
						@endforeach
						<tr>
							<th colspan="6"></th>
							<th>PAID </th>
							<th  align="right">{{number_format($bill_paid + $total_delivery_paid,2,',','.')}}</th>
							<th>{!!$coa_name!!}</th>
						</tr>


						@foreach(CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->id)->get() as $rowcb)
						@foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb)
								@php
								if($cbcb->type == "2"){
									$totaladjust += $cbcb->nominal;
								}	
							@endphp
						@endforeach
						@endforeach
						<tr>
							@php
								// TOTAL TAGIHAN DAN PEMBAYARAN PER PROJECT
								$amount = ($bill_paid + $total_delivery_paid + $total_return + $totaladjust) - $totaldeliveryperproject;

								// TOTAL BAYAR
								$total_paid += ($total_delivery_paid + $total_return + $totaladjust);

								// JIKA CUST LEBIH BAYAR
								if(($bill_paid + $total_delivery_paid + $total_return + $totaladjust) - $totaldeliveryperproject > 0){
									// JIKA CUST LEBIH BAYAR, MENJADI CUST DEPOSIT DAN PEMBAYARAN DISESUAIKAN NOMINAL TAGIHAN
									$cust_depo += $amount;
									$total_paid -= $amount;
								}

								// JIKA CUST KURANG BAYAR
								if($amount < 0){
									// JIKA ADA BILL YANG DITERBITKAN DAN NOMINALNYA LEBIH RENDAH ATAU SAMA DENGAN TOTAL TAGIHAN PER PROJECT (BELUM LUNAS/ KURANG BAYAR)
									if($bill_nominal <= $amount){
										// JIKA CUST KURANG BAYAR DAN PUNYA BILL, MAKA CUST DIANGGAP PUNYA DP
										$unpaid_ar -= $bill_nominal;

										// NOMINAL BILL BELUM TERBAYAR/ LUNAS (DIBERLAKUKAN SEBAGAI DP) - TOTAL TAGIHAN BELUM TERBAYAR 
										// NB:(NILAI HARUSNYA POSITIF KARENA (-) BILL NOMINAL - (- TOTAL TAGIHAN)
										$total_paid += $bill_nominal - $amount; 
									}else{
										$unpaid_ar += $totaldeliveryperproject - ($bill_paid + $total_delivery_paid + $total_return+$totaladjust);
										
									}
								}
							@endphp
							
							<th colspan="6"></th>
							<th>BALANCE</th>
							<th  align="right">{{number_format($amount,2,',','.')}}</th>
							<th></th>
						</tr>
						
						<tr align="center">
							<td  style='border-left:none; border-right:none; border-bottom:none;' colspan="11"></td>
						</tr>
						<tr align="center">
							<td  style='border:none; !important' colspan="11"></td>
						</tr>
						<tr align="center">
							<td  style='border-left:none; border-right:none; border-top:none;' colspan="11"></td>
						</tr>
					@endif
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
								$pay = $rowbill->paidAR()['pays'];
								$balancebill = $rowbill->nominal + $rowbill->nominal_service - $pay;
							@endphp
							<tr align="center">
								<td>{{ $rowbill->project->code }}</td>
								<td>{{ $rowbill->code }}</td>
								<td>{{ date('d M Y',strtotime($rowbill->date)) }}</td>
								<td align="right"><b>{{ number_format($rowbill->nominal + $rowbill->nominal_service,2,',','.') }}</b></td>
								<td align="right"><b>{{ number_format($pay,2,',','.') }}</b></td>
								<td align="right"><b>{{ number_format($balancebill,2,',','.') }}</b></td>
							</tr>
							@php
								$totalpay += ($balancebill >= 0 ? $balancebill : 0);
								$cust_depo += $balancebill < 0  ? $balancebill * -1 : 0;
								// $totaldelivery += ($balancebill >= 0 ? $balancebill : 0);
							@endphp
						@endforeach
					@endforeach
				</tbody>
			</table>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th colspan="6"><h1>CUSTOMER DEPOSIT HISTORY</h1></th>
					</tr>
					<tr align="center">
						<th>Nominal(Before)</th>
						<th>Date</th>
						<th>Nominal(Used)</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($data as $row)
						@foreach ($row->projectPay->where('coa_id', '67') as $rpp)
						@php
							
						@endphp
						<tr align="center">  
							<td>{{number_format($cust_depo > 0 ? $cust_depo : 0,2,',','.')}}</td>
							<td>{{date('d M Y', strtotime($rpp->date))}}</td>
							<td>{{number_format($rpp->nominal * (-1),2,',','.')}}</td>
							<td>CUSTOMER DEPOSIT IS USED FOR {{isset($rpp->projectDelivery) ? $rpp->projectDelivery->proforma_code : (isset($rpp->projectBill) ? $rpp->projectBill->proforma_code : $row->code)}}</td>
						</tr>
						@php
							$cust_depo -= $rpp->nominal
						@endphp
						@endforeach
					@endforeach

				</tbody>
			</table>
			<br><br>
			<br><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<tbody>
					<tr align="center">
						<td width="25%"><h3>Total Receivable</h3><h1>{{ number_format($totaldelivery,2,',','.') }}</h1></td>
						<td width="25%"><h3>Paid</h3><h1>{{ number_format($total_paid ,2,',','.') }}</h1></td>
						<td width="25%"><h3>Balance</h3><h1>{{ number_format($unpaid_ar, 2,',','.') }}</h1></td> 
						<td width="25%"><h3>Customer Deposit Final</h3><h1>{{ number_format($cust_depo > 0 ? $cust_depo : 0,2,',','.') }}</h1></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>