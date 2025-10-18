<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Quotation Order</title>
	<style>

		html,body{
			height:297mm;
			width:210mm;
		}
		table:first-of-type {
			position: absolute;
			top: 0;
			left: 0;
			z-index: -1;
			width: 100%;
		}

		body {
			font-family: 'Lato', sans-serif;
		}

		th {
			font-size: 14px;
		}

		.invoice-box {
			font-size: 16px;
			color: #555;
			font-family: 'Lato', sans-serif;
			/* page-break-after: always; */
		}

		.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
			font-family: 'Lato', sans-serif;
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
			background:
				border-bottom: 1px solid color: white;
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

		.invoice-box.rtl table {
			text-align: right;
			font-family: 'Lato', sans-serif;
		}

		.invoice-box.rtl table tr td:nth-child(2) {
			text-align: left;
			font-family: 'Lato', sans-serif;
		}

		@page {
			margin: 1cm;
			size: 7in 9.25in;
			margin: 27mm 16mm 27mm 16mm;
		}

		body {
			margin: 1cm;
		}

		.base-line {
			position: relative;
		}

		.strike-through {
			position: absolute;
			top: 0;
			left: 0;
		}
	</style>
</head>

<body>
	<div class="invoice-box">
		{{-- @if($project->letter_head == 1 || $project->letter_head == NULL)
		<table cellpadding="0" cellspacing="0">
			<tr class="top">
				<td colspan="2">
					<table>
						<tr>
							<td class="title" rowspan="2">
								<img src="{{ url('website/logo-black.png') }}" width="275">
							</td>
							<td colspan="2" style="text-align:right;padding-bottom:15px;"><img
									src="{{ url('website/pta_new_logo.png') }}" height="30px" style="margin-right:5px;">
							</td>
						</tr>
						<tr>
							<td style="padding-right:10px;padding-top:100px !important;">
								<div style="font-size:9px; font-weight:bold;">PERGUDANGAN KOSAMBI PERMAI </div>
								<div style="font-size:9px; font-weight:500;">Jalan Raya Perancis Blok E-6 Jati Mulya,
									Dadap, Tangerang</div>
								<div style="font-size:9px; font-weight:500;">Phone : 0811257180 / 081225575295</div>
								<div style="font-size:9px; font-weight:500;">Email : infojkt@smartmarbleandbath.com
								</div>
							</td>
							<td
								style="border-left: 2px solid #51b6bc; text-align:right;padding-top:100px !important; padding-left:10px;">
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
						<img src="{{ url('website/kop_brand_report.png') }}" width="100%">
					</center>
				</td>
			</tr>
		</table>
		@else
		{!!App\Helper\SMB::letterHead($project->letter_head)!!}
		@endif --}}
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="2" style="vertical-align: middle;padding-top:15px;padding-bottom:45px;">
					<center>
						<img src="{{url('website/letterhead_bq.png')}}" width="100%" style="width: 1500px !important;">
					</center>
				</td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="2">
					<table>
						<tr style="background-color:">
							<td style="text-align:center;color:white;padding-top:10px;padding-bottom:10px;">
								<h3><b>QUOTATION ORDER</b></h3>
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
						<tr>
							<td width="20%" style="font-size:12px;">ATTENTION</td>
							<td style="text-align:left; font-size:12px;">:</td>
							<td></td>
							<td></td>
							<td width="20%" style="font-size:12px;">DATE</td>
							<td style="text-align:left; font-size:12px;">:</td>
						</tr>
						<tr>
							<td width="20%" style="font-size:12px;">CUSTOMER</td>
							<td style="text-align:left; font-size:12px;">: </td>
							<td></td>
							<td></td>
							<td width="20%" style="font-size:12px;">PROJECT NO</td>
							<td style="text-align:left; font-size:12px;">: </td>
						</tr>
						<tr>
							<td width="20%" style="font-size:12px;">PROJECT NAME</td>
							<td style="text-align:left; font-size:12px;">:</td>
							<td></td>
							<td></td>
							<td width="20%" style="font-size:12px;">REVISION</td>
							<td style="text-align:left; font-size:12px;">:</td>
						</tr>
						<tr>
							<td width="20%" style="font-size:12px;">CITY</td>
							<td style="text-align:left; font-size:12px;">: </td>
							<td></td>
							<td></td>
							<td width="20%" style="font-size:12px;"></td>
							<td style="text-align:left; font-size:12px;"></td>
						</tr>

					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td colspan="2">
					<p style="font-size:12px;">Dear mr/mrs.<br>
						We are delighted to provide a price offer for this exciting project . The quotation and details
						are as follow :</p>
				</td>
			</tr>
		</table>

		<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
			<tr style="background:text-align:center;">
				<th style="color:white;" rowspan="2">
					<center>NO</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>SITE AREA</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>CODE</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>PICTURE</center>
				</th>

				<th style="color:white;" rowspan="2">
					<center>SIZE(cm)</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>CATEGORY</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>COLOR</center>
				</th>
				<th style="color:white;" colspan="2">
					<center>VOL/CTN</center>
				</th>
				<th style="color:white;" colspan="2">
					<center>QTY</center>
				</th>
				<th style="color:white;" colspan="2">
					<center>OUR PRICE </center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>TOTAL</center>
				</th>
			</tr>
			<tr style="background:text-align:center;">
				<th style="color:white;">(M<sup>2</sup>)</th>
				<th style="color:white;">(Pcs)</th>
				<th style="color:white;">(M<sup>2</sup>)</th>
				<th style="color:white;">(BOX)</th>
				<th style="color:white;">(M<sup>2</sup>)</th>
				<th style="color:white;">(BOX)</th>
				</th>
				<tbody>

					<tr>
						<td style="vertical-align:center;">
							<center>


							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>
								<img src=""
									style="max-width:20px; border:1px solid #ddd; border-radius:4px; padding: 5px;"
									class="img-fluid img-thumbnail">
							</center>
						</td>

						<td style="vertical-align:center;">
							<center>

							</center>
						</td>

						<td style="vertical-align:center;">
							<center>

							</center>
						</td>

						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
						<td style="vertical-align:center;">
							<center>

							</center>
						</td>
					</tr>
					<tr>
						<th colspan="" style="text-align:right;">Total Qty</th>
						<th></th>
						<th></th>
						<th colspan="2">Subtotal</th>
						<th colspan="2"></th>
					</tr>
				</tbody>
		</table>

		<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">

			<tr style="background:text-align:center;">
				<th style="color:white;" rowspan="2">
					<center>NO</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>SITE AREA</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>CODE</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>PICTURE</center>
				</th>

				<th style="color:white;" rowspan="2">
					<center>SIZE(cm)</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>CATEGORY</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>COLOR</center>
				</th>
				<th style="color:white;" colspan="2" rowspan="2">
					<center>SPEC</center>
				</th>
				<th style="color:white;" colspan="2">
					<center>QTY</center>
				</th>
				<th style="color:white;" colspan="2">
					<center>OUR PRICE</center>
				</th>
				<th style="color:white;" rowspan="2">
					<center>TOTAL</center>
				</th>
			</tr>
			<tr style="background:text-align:center;">
				<th style="color:white;" colspan="2">PCS</th>
				<th style="color:white;" colspan="2">PCS</th>
			</tr>
			<tbody>
				<tr>
					<td style="vertical-align:center;">
						<center>

						</center>
					</td>
					<td style="vertical-align:center;">
						<center>

						</center>
					</td>
					<td style="vertical-align:center;">
						<center>

						</center>
					</td>
					<td style="vertical-align:center;">
						<center>
							<img src="" style="max-width:20px; border:1px solid #ddd; border-radius:4px; padding: 5px;"
								class="img-fluid img-thumbnail">
						</center>
					</td>

					<td style="vertical-align:center;">
						<center>

						</center>
					</td>

					<td style="vertical-align:center;">
						<center>

						</center>
					</td>

					<td style="vertical-align:center;">
						<center>

						</center>
					</td>
					<td style="vertical-align:center;">
						<center>

						</center>
					</td>
					<td style="vertical-align:center;">
						<center>

						</center>
					</td>
					<td style="vertical-align:center;" colspan="2">
						<center>

						</center>
					</td>
					<td style="vertical-align:center;" colspan="2">
						<center>

						</center>
					</td>
					<td style="vertical-align:center;" colspan="2">
						<center>
						</center>
					</td>
					<td style="vertical-align:center;">
						<center>
						</center>
					</td>
				</tr>
				<tr>
					<th colspan="" style="text-align:right;">Total Qty</th>
					<th></th>
					<th colspan="2">Subtotal</th>
					<th colspan="2"></th>
				</tr>
			</tbody>
		</table>
		<br>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="50%" style="">
					<table cellpadding="2" cellspacing="0" border="1"
						style="background-color:font-size:14px;color:white;">
						<tr>
							<td>
								<b>GRANDTOTAL</b>
							</td>
							<td style="text-align:center;">
								<b>IDR </b>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<b><i>Nominal in words</i> : <br> </b>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<b><i>Payment will be transferred to</i></b> :
								<p>
									<br>BCA 329-37-22222
									<br>a/n PT. Perwira Tamaraya Abadi
									<br>Cab. Baliwerti - Surabaya<br>

									<br>OR<br>

									<br>Mandiri 14-000-888-222-01
									<br>a/n PT. Perwira Tamaraya Abadi
									<br>Cab. Kembang Jepun - Surabaya
								</p>

							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
				<td width="45%">
					<table cellpadding="2" cellspacing="0" border="1">
						<tr>
							<td>
								<h6>SUBTOTAL</h6>
							</td>
							<td>
								<h6>IDR </h6>
							</td>
						</tr>
						<tr>
							<td>
								<h6>CUTTING COST</h6>
							</td>
							<td>
								<h6>IDR </h6>
							</td>
						</tr>
						<tr>
							<td>
								<h6>MISC COST</h6>
							</td>
							<td>
								<h6>IDR</h6>
							</td>
						</tr>
						<tr>
							<td>
								<h6>DELIVERY COST</h6>
							</td>
							<td>
								<h6>IDR </h6>
							</td>
						</tr>
						<tr>
							<td>
								<h6>DISCOUNT</h6>
							</td>
							<td>
								<h6>IDR </h6>
							</td>
						</tr>
						<tr>
							<td>
								<h6>TOTAL AFTER DISCOUNT</h6>
							</td>
							<td>
								<h6>IDR</h6>
							</td>
						</tr>
						<tr>
							<td>
								<h6>TAX</h6>
							</td>
							<td>
								<h6>IDR</h6>
							</td>
						</tr>
						<tr>
							<td>
								<h6>Services TAX</h6>
							</td>
							<td>
								<h6>IDR </h6>
							</td>
						</tr>
						<tr>
							<td>
								<h6>GRANDTOTAL</h6>
							</td>

							<td>
								<h6>IDR</h6>
							</td>
						</tr>
					</table>
					<table cellpadding="2" cellspacing="0" border="1" style="margin-top:20px;color:red;">
						<tr>
							<td>
								<h6><i>THIS QUOTATION IS VALID UNTIL :</i></h6>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="padding-top:10px;">
					<h6 style="font-size:10px; text-align:center;"><u>TERMS & CONDITIONS</u> :</h6>
					<p style="font-size:10px;">

					<ol>
						<li>We only supply 1st quality goods</li>
						<li>Prices already included franco to (On Truck).</li>
						<li>Normal delivery 3-7 days. If the items are not ready, please wait within 8-24 weeks.</li>
						<li>Payment.</li>
						<li>Prices do not include installation.</li>
					</ol>

					</p>
					<br>
					<p style="font-size:10px;">

						We hope that we can work together in supplying the material of your project.
						<br>For further information, please contact our sales person -
						<br>Thank you for your time.

					</p>
				</td>
			</tr>
		</table><br>
		<table cellpadding="0" cellspacing="0">

		</table>
		<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:10px;">
			<tr>
				<td style="text-align:right;color:black;font-size:10px;">
					Created at :
				</td>
			</tr>
		</table><br>
	</div>
	<div class="separate-box">
		<h3>Blow Up Material</h3>
		<table border="1" cellpadding="5" cellspacing="0" style="font-size:10px;">

		</table>
	</div>
</body>

</html>