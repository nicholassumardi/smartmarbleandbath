<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Inventory Report Branch {{ $branch == '1' ? 'Surabaya' : 'Jakarta' }}</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
		<style>
			.selected {
				background-color:#ec3df5 !important;
			}
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
	<body onload="/* window.print() */">
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
											Inventory Product Report in Qty 
											<br>Branch {{ $branch == '1' ? 'Surabaya' : 'Jakarta' }}
										</b>
									</h4>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			@php
				$no = 1;
			@endphp
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center" style="background-color:#324148;color:white;font-size:25px !important;">
						<th>NO</th>
						<th>PRODUCT</th>
						<th>PICTURE</th>
						<th>COLOR</th>
						<th>SURFACE</th>
						<th>SIZE</th>
						<th>SQM</th>
						<th>BOX</th>
						<th>TOTAL SQM</th>
					</tr>
				</thead>
				<tbody>
					@foreach($main as $rowwarehouse)
						<tr>
							<td align="center" colspan="9" style="background-color:#b80d76;color:white;">{{ $rowwarehouse['warehouse_name'] }}</td>
						</tr>
						@foreach($data as $key => $row)
							@if($row['warehouse_id'] == $rowwarehouse['warehouse_id'])
								<tr>
									<td align="center">{{ $no }}</td>
									<td>{{ $row['product_name'] }}</td>
									<td align="center"><a href="#product{{ $key }}" onclick="checkImage({{ $key }})">Click here</a></td>
									<td align="center">{{ $row['product_color'] }}</td>
									<td align="center">{{ $row['product_surface'] }}</td>
									<td align="center">{{ $row['product_size'] }}</td>
									<td align="center">{{ $row['product_sqm'] }}</td>
									<td align="center">{{ round($row['qty']) }}</td>
									<td align="center">{{ $row['product_sqm'] * round($row['qty']) }}</td>
								</tr>
								@php
									$no++;
								@endphp
							@endif
						@endforeach
					@endforeach
				</tbody>
			</table><br>
			<table border="1" cellpadding="3" cellspacing="0" style="width:90%; font-size:13px;" width="90%">
				@php
					$no = 1;
				@endphp
				@foreach($data as $key => $row)
					@php
						if(($no % 4) == 1){
							echo '<tr>';
						}
					@endphp
					<td id="product{{ $key }}" class="listproduct" width="25%" align="center">
						<img src="{{ $row['product_picture'] }}" style="max-height:150px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
						<center style="font-size:15px;">
							{{ $row['product_name'] }}
						</center>
					</td>
					@php
						$no++;
					@endphp
				@endforeach
			</table>
		</div>
		<script src="{{ asset('template/back-office/global_assets/js/main/jquery.min.js') }}"></script>
		<script>
			function checkImage(id){
				$('.listproduct').each(function(){
					$(this).removeClass('selected');
				});
				$('#product' + id).addClass('selected');
			}
		</script>
	</body>
</html>