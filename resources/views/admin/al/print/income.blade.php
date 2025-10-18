<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Kwitansi {{ $data->code }}</title>
		<style>
			body {
				font-family: "Times New Roman", Times, serif;
			}
			
			th {
				font-size:15px;
			}
		
			.invoice-box {
				font-size: 15px;
				font-family: "Times New Roman", Times, serif;
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
				font-family: "Times New Roman", Times, serif;
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
			<table cellpadding="5" cellspacing="0" width="100%" border="1">
				<tr>
					<td style="text-align:center;border-bottom:none;border-right:none;" width="50%">
						<img src="{{ url('website/logo_al_rev_4.jpg') }}" width="100%" height="">
					</td>
					<td style="text-align:center;border-bottom:none;vertical-align:middle;border-left:none;" width="40%">
						<span style="border:1px solid black;padding:5px;">Kwitansi No. : {{ $data->code }}</span>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="border-bottom:none;border-top:none;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="25%">Telah Terima Dari</td>
								<td width="1%">:</td>
								<td style="text-align:left !important;"><b>{{ $data->from_person }}</b></td>
							</tr>
							<tr>
								<td>Jumlah Uang</td>
								<td width="1%">:</td>
								<td style="text-align:left !important;"><b>{{ App\Helper\SMB::terbilang($data->nominal) }} Rupiah</b></td>
							</tr>
							<tr>
								<td>Untuk Pembayaran</td>
								<td width="1%">:</td>
								<td style="text-align:left !important;"><b>{{ $data->note }}</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;padding-top:20px;border-top:none;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="60%" align="left">
									<br><br><br><br><br><br><br><br>
									<b>Terbilang : Rp {{ number_format($data->nominal,0,',','.') }}</b>
								</td>
								<td width="40%" align="left">
									Surabaya,
									<br>
									PT. Perwira Tamaraya Abadi
									<br><br><br><br><br><br>
									Prawiro Tedjo Tjandra
									<br>Direktur
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>