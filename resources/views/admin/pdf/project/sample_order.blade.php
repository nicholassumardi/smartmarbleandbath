<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sample Order {{ $project->code }}</title>
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:14px;
			}
			
			.invoice-box {
				font-size: 16px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
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
				background: #eb3c28;
				border-bottom: 1px solid #eb3c28;
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
			
			@page { margin: 1cm; }
			body { margin: 1cm; }
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
									<img src="{{ url('website/logo-black.png') }}" width="275">
								</td>
								<td colspan="2" style="text-align:right;padding-bottom:15px;"><img src="{{ url('website/pta_new_logo.png') }}" height="30px" style="margin-right:5px;"></td>
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
							<tr style="background-color:#eb3c28;">
								<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
									<h3><b>SAMPLE ORDER</b></h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table>
				<tr>
					<td colspan="2">
						<table width="100%">
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">ATTENTION</td>
								<td style="text-align:left; font-size:12px;">: {{ strtoupper($project->project->manager) }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">PROJECT NUMBER</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->code }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">CUSTOMER</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->customer->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">SAMPLE NUMBER</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->code }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">PROJECT NAME</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">STATUS</td>
								<td style="text-align:left; font-size:12px;">: {!! $project->status() !!}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">CITY</td>
								<td style="text-align:left; font-size:12px;">: {{ $project->project->city->name }}</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;">DATE</td>
								<td style="text-align:left; font-size:12px;">: {{ date('d F Y', strtotime($project->sent_date)).' to '.date('d F Y', strtotime($project->return_date)) }}</td>
							</tr>
							<tr style="line-height:0 !important;">
								<td width="20%" style="font-size:12px;">
									{{ $project->status == '2' ? 'DATE RETURNED' : '' }}
								</td>
								<td style="text-align:left; font-size:12px;">
									{{ $project->status == '2' ? ': '.$project->returned_at : '' }}
								</td>
								<td></td>
								<td></td>
								<td width="20%" style="font-size:12px;"></td>
								<td style="text-align:left; font-size:12px;"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"></td></tr>
				<tr>
					<td colspan="2">
						<!-- <p style="font-size:10px;">Dear mr. {{ $project->project->customer->name }}<br>
						We are delighted to provide a price offer for this exciting project . The quotation and details are as follow :</p> -->
					</td>
				</tr>
			</table>
			
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
				<thead>
					<tr style="background:#eb3c28;text-align:center;">
						<th style="color:white;"><center>NO</center></th>
						<th style="color:white;"><center>NAME</center></th>
						<th style="color:white;"><center>BRAND</center></th>
						<th style="color:white;"><center>COLOR</center></th>
						<th style="color:white;"><center>QTY</center></th>
						<th style="color:white;"><center>UNIT</center></th>
						<th style="color:white;"><center>SIZE</center></th>
					</tr>
				</thead>
				<tbody>
					@foreach($project->projectSampleProduct as $key => $psp)
						<tr>
							<td style="vertical-align:center;">
								<center>
									{{ $key + 1 }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $psp->product->name() }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $psp->product->brand->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $psp->product->type->color->name }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $psp->qty }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $psp->unit() }}
								</center>
							</td>
							<td style="vertical-align:center;">
								<center>
									{{ $psp->size() }}
								</center>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table><br><br><br>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Created By</div>
						@if(isset($project->project->user->sign))
							<div><img src="{{ url(Storage::url($project->project->user->sign)) }}" height="75px"></div>
						@else
							<br><br><br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->project->user->name }}</div>
					</td>
					@if(isset($project->approved_1->name))
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Validated By</div>
						@if(isset($project->approved_1->sign))
							<div><img src="{{ url(Storage::url($project->approved_1->sign)) }}" height="75px"></div>
						@else
							<br><br><br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->approved_1->name }}</div>
					</td>
					@endif
					@if(isset($project->approved_2->name))
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Approved By</div>
						@if(isset($project->approved_2->sign))
							<div><img src="{{ url(Storage::url($project->approved_2->sign)) }}" height="75px"></div>
						@else
							<br><br><br><br><br>
						@endif
						<div style="font-size:10px;">{{ $project->approved_2->name }}</div>
					</td>
					@endif
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Sent By</div>
							<br><br><br><br><br>
						<div style="font-size:10px;">(................................)</div>
					</td>
					<td style="text-align:center;" width="20%">
						<div style="font-size:10px;">Received By</div>
							<br><br><br><br><br>
						<div style="font-size:10px;">(................................)</div>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:50px;">
				<tr>
					<td style="text-align:right;color:black;font-size:10px;">
						Created at : {{ $project->created_at }}
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>