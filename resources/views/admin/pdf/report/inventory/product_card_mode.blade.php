<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/bootstrap.min.css?v=7') }}" rel="stylesheet">
	<script src="{{ asset('template/back-office/global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<style>
		body {
			font-family: 'Lato', sans-serif;
		}

		th {
			font-size: 12px;
		}

	    .center-image {
			text-align: center;
		}
		.center-image img {
			display: inline-block;
			vertical-align: middle !important;
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

		.page-break {
			page-break-before: always;
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
			margin: 0.5cm;
		}

		body {
			margin: 0.5cm;
		}
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
								<h4>
									<b>
										Inventory Product Report
										{{-- <br>Branch {{ $branch == '2' ? 'Jakarta' : 'Surabaya' }}
										<br>Period {{ date('d M Y', strtotime($startDate)).' - '.date('d M Y',
										strtotime($endDate)) }} --}}
									</b>
								</h4>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		@foreach ($data as $rowWarehouse)
		@php
		$prevSize = null;
		@endphp
		<table>
			<tr>
				<td align="center" colspan="7" style="background-color:#b80d76;color:white;"><b
						style="font-size: 18pt;">{{ $rowWarehouse['warehouse_name'] }}</b></td>
			</tr>
		</table>
		<div class="row no-gutters">
			@foreach ($rowWarehouse['detail'] as $key => $rowDetail)
			<div class="col-6">
				<table class="table" border="2">
					<tr>
						<td rowspan="5" class="center-image align-middle" style="width:100px;"><img src="{{$rowDetail['image']}}"
								style="width: 100px !important;"></td>
					</tr>
					<tr>
						<td style="height: 110px !important;">
							<h2 style="font-size:18pt;" class="text-break card-title font-weight-bold">{{$rowDetail['name']}}</h2>
						</td>
					</tr>
					<tr>
						<td>
							<h5 class="card-text font-weight-bold">Size : {{$rowDetail['size']}}</h5>
						</td>
					</tr>
					<tr>
						<td>
							<h1 class="card-text font-weight-bold">Qty : {{$rowDetail['qty']}}</h1>
						</td>
					</tr>
					<tr>
						<td>
							<h5 class="card-text font-weight-bold">Brand Name : {{$rowDetail['brand_name']}}</h5>
						</td>
					</tr>
				</table>
			</div>
			@if (($key % 6 == 0) && ($key != 0))
				<div class="page-break">
				</div>
			@endif
			@endforeach
		</div>
		<div class="page-break">
		</div>
		@endforeach
	</div>
</body>

</html>