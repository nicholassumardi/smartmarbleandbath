<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>RAB {{ $data->code }}</title>
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
				text-align: right;
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
					<td style="text-align:center;" colspan="2">
						<img src="{{ url('website/logo_al_rev_3.jpg') }}" width="auto" height="100px">
					</td>
				</tr>
				<tr>
					<td style="text-align:center;" colspan="2">
						<h3>RAB PROYEK {{ $data->alSph ? strtoupper($data->alSph->alProject->name) : $data->code }}</h3>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
						<table>
							<tr>
								<td style="text-align:center;">
									<h4>
										DAFTAR PENGELUARAN
									</h4>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			<table style="text-align:left;">
				<tr >
					<th>Nomor</th>
					<th>: {{ $data->alProject->code }}</th>
				</tr>
				<tr>
					<th>Pelanggan</th>
					<th>: {{ $data->alProject->alCustomer->name }}</th>
				</tr>
				<tr>
					<th>Tanggal</th>
					<th>: {{ date('d M Y',strtotime($data->alProject->date)) }}</th>
				</tr>
				<tr>
					<th>PPN</th>
					<th>: {{ $data->alProject->is_ppn() }}</th>
				</tr>
				<tr>
					<th>Kota</th>
					<th>: {{ $data->alProject->city->name }}</th>
				</tr>
			</table>
			<div class="form-group"><hr></div>
			<div class="row">
				<div class="col-md-12">
					<h5 class="card-title">Detail Perbandingan Anggaran dan Real</h5>
					<div class="table-responsive">
						<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
						  <thead class="bg-dark">
							<tr style="background:#cf9604;text-align:center;">
								<th rowspan="2">#</th>
								<th rowspan="2">Tujuan</th>
								<th rowspan="2">Anggaran</th>
								<th rowspan="2">%</th>
								<th rowspan="2">Real</th>
								<th rowspan="2">%</th>
								<th colspan="2">Variasi Sisa</th>
								<th rowspan="2">Real / Anggaran</th>
								<th rowspan="2">Status</th>
							 </tr>
							<tr style="background:#cf9604;text-align:center;">
								<th>Rupiah (Rp)</th>
								<th>Prosentase (%)</th>
							 </tr>
						  </thead>
						  <tbody>
							@php
								$totalpemasukan = 0;
								$totalprofit = 0;
								$totalother = 0;
								foreach($data->alIncome() as $row){
									$totalpemasukan += $row->nominal;
								}
								
								$realpph = $data->real_pph ? $data->real_pph : 0.015 * ($totalpemasukan - $data->alSph->ppn);
								$budgetpph = $data->budget_pph ? $data->budget_pph :  0.015 * ($data->total_claim - $data->alSph->ppn);
								
								$totalpemasukansebelumppn = $data->real_ppn ? $totalpemasukan - ($data->real_ppn + $realpph) : $totalpemasukan - ($data->alSph->ppn + $realpph);
								$totalbudgetnonppn = $data->budget_ppn ? $data->total_claim - ($data->budget_ppn + $budgetpph) : $data->total_claim - ($data->alSph->ppn + $budgetpph);
								
								$totalhpp = 0;
								foreach($data->alExpense()->where('type','1') as $row){
									$totalhpp += $row->totalPayment();
								}
								
								$totaldk = 0;
								foreach($data->alExpense()->where('type','2') as $row){
									$totaldk += $row->totalPayment();
								}
								
								$totaladmin = 0;
								foreach($data->alExpense()->where('type','3') as $row){
									$totaladmin += $row->totalPayment();
								}
								
								$status_pemasukan = '';
								$btn_pemasukan = '';
								
								if(($totalpemasukan - $data->total_claim) > 0){
									$status_pemasukan = 'Surplus';
									$btn_pemasukan = 'btn-success';
								}elseif(($totalpemasukan - $data->total_claim) < 0){
									$status_pemasukan = 'Defisit';
									$btn_pemasukan = 'btn-danger';
								}elseif(($totalpemasukan - $data->total_claim) == 0){
									$status_pemasukan = 'Sesuai';
									$btn_pemasukan = 'btn-success';
								}
								
								$status_hpp = '';
								
								if(($totalhpp - $data->total_deposit) > 0){
									$status_hpp = 'Defisit';
									$btn_pengeluaran = 'btn-danger';
								}elseif(($totalhpp - $data->total_deposit) < 0){
									$status_hpp = 'Surplus';
									$btn_pengeluaran = 'btn-success';
								}elseif(($totalhpp - $data->total_deposit) == 0){
									$status_hpp = 'Sesuai';
									$btn_pengeluaran = 'btn-success';
								}
								
								$status_dk = '';
								
								if(($totaldk - $data->total_dk) > 0){
									$status_dk = 'Defisit';
									$btn_dk = 'btn-danger';
								}elseif(($totaldk - $data->total_dk) < 0){
									$status_dk = 'Surplus';
									$btn_dk = 'btn-success';
								}elseif(($totaldk - $data->total_dk) == 0){
									$status_dk = 'Sesuai';
									$btn_dk = 'btn-success';
								}
								
								$status_admin = '';
								
								if(($totaladmin - $data->total_admin) > 0){
									$status_admin = 'Defisit';
									$btn_admin = 'btn-danger';
								}elseif(($totaladmin - $data->total_admin) < 0){
									$status_admin = 'Surplus';
									$btn_admin = 'btn-success';
								}elseif(($totaladmin - $data->total_admin) == 0){
									$status_admin = 'Sesuai';
									$btn_admin = 'btn-success';
								}

								foreach($data->alIncome() as $row){
									$totalother += $row->nominal_other;
								}
								
								$totalprofit =  $totalpemasukansebelumppn + $totalother - $totalhpp - $totaldk - $totaladmin;
								$budgetprofit = $data->budget_ppn ? $data->total_profit - ($data->budget_ppn + $budgetpph) : $data->total_profit - ($data->alSph->ppn + $budgetpph);
								
								$status_profit = '';
								
								if(($totalprofit - $data->totalprofit) > 0){
									$status_profit = 'Surplus';
									$btn_profit = 'btn-success';
								}elseif(($totalprofit - $data->totalprofit) < 0){
									$status_profit = 'Defisit';
									$btn_profit = 'btn-danger';
								}elseif(($totalprofit - $data->totalprofit) == 0){
									$status_profit = 'Sesuai';
									$btn_profit = 'btn-success';
								}
								
								
							@endphp
							<tr>
								<td class="text-center">1.</td>
								<td style="text-align:left;"> Pemasukan</td>
								<td class="text-right">Rp{{ number_format($data->total_claim,2,',','.') }}</td>
								<td class="text-center"></td>
								<td class="text-right">Rp{{ number_format($totalpemasukan,2,',','.') }}</td>
								<td class="text-center"></td>
								<td class="text-right">{{ number_format($totalpemasukan - $data->total_claim,2,',','.') }}</td>
								<td class="text-center">{{ round((($totalpemasukan - $data->total_claim) / $data->total_claim) * 100,2) }}%</td>
								<td class="text-center">{{ round(($totalpemasukan / $data->total_claim) * 100,2) }}%</td>
								<td class="text-center">{{ $status_pemasukan }}</td>
							</tr>
							<tr>
								<td class="text-center">2.</td>
								<td style="text-align:left;">Pajak PPN</td>
								<td class="text-right">Rp {{ number_format($data->budget_ppn ? $data->budget_ppn : $data->alSph->ppn,2,',','.') }}</td>
								<td class="text-center">-</td>
								{{-- <td class="text-right">Rp{{ number_format($data->alSph->ppn,2,',','.') }}</td> --}}
								<td class="text-right">Rp {{ number_format($data->real_ppn ? $data->real_ppn : $data->alSph->ppn,2,',','.') }}</td>
								<td class="text-center">-</td>
								<td class="text-right">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
							</tr>
							<tr>
								<td class="text-center">3.</td>
								<td style="text-align:left;">Pajak PPH</td>
								<td class="text-right">Rp{{ number_format($budgetpph,2,',','.') }}</td>
								<td class="text-center">-</td>
								<td class="text-right">Rp {{ number_format($realpph,2,',','.') }}</td>
								<td class="text-center">-</td>
								<td class="text-right">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
							</tr>
							<tr>
								<td class="text-center">4.</td>
								<td style="text-align:left;"> Pemasukan Sebelum Pajak</td>
								<td class="text-right">Rp{{ number_format($totalbudgetnonppn,2,',','.') }}</td>
								<td class="text-center">{{ round($totalbudgetnonppn / $totalbudgetnonppn * 100,2) }}%</td>
								<td class="text-right">Rp{{ number_format($totalpemasukansebelumppn,2,',','.') }}</td>
								<td class="text-center">{{ $totalpemasukansebelumppn > 0 ? round($totalpemasukansebelumppn / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
								<td class="text-right">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
							</tr>
							<tr>
								<td class="text-center">5.</td>
								<td style="text-align:left;">Pembelanjaan (HPP)</td>
								<td class="text-right">Rp{{ number_format($data->total_deposit,2,',','.') }}</td>
								<td class="text-center">{{ round($data->total_deposit / $totalbudgetnonppn * 100,2) }}%</td>
								<td class="text-right">Rp{{ number_format($totalhpp,2,',','.') }}</td>
								<td class="text-center">{{ $totalpemasukansebelumppn > 0 ? round($totalhpp / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
								<td class="text-right">{{ number_format($data->total_deposit - $totalhpp,2,',','.') }}</td>
								<td class="text-center">{{ round((($data->total_deposit - $totalhpp) / $data->total_deposit) * 100,2) }}%</td>
								<td class="text-center">{{ round(($totalhpp / $data->total_deposit) * 100,2) }}%</td>
								<td class="text-center">{{ $status_hpp }}</td>
							</tr>
							<tr>
								<td class="text-center">6.</td>
								<td style="text-align:left;"> DK</td>
								<td class="text-right">Rp{{ number_format($data->total_dk,2,',','.') }}</td>
								<td class="text-center">{{ round($data->total_dk / $totalbudgetnonppn * 100,2) }}%</td>
								<td class="text-right">Rp{{ number_format($totaldk,2,',','.') }}</td>
								<td class="text-center">{{ $totalpemasukansebelumppn > 0 ? round($totaldk / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
								<td class="text-right">{{ number_format($data->total_dk - $totaldk,2,',','.') }}</td>
								<td class="text-center">{{ round((($data->total_dk - $totaldk) / $data->total_dk) * 100,2) }}%</td>
								<td class="text-center">{{ round(($totaldk / $data->total_dk) * 100,2) }}%</td>
								<td class="text-center">{{ $status_dk }}</td>
							</tr>
							<tr>
								<td class="text-center">7.</td>
								<td style="text-align:left;">Admin</td>
								<td class="text-right">Rp{{ number_format($data->total_admin,2,',','.') }}</td>
								<td class="text-center">{{ round($data->total_admin / $totalbudgetnonppn * 100,2) }}%</td>
								<td class="text-right">Rp{{ number_format($totaladmin,2,',','.') }}</td>
								<td class="text-center">{{ $totalpemasukansebelumppn > 0 ? round($totaladmin / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
								<td class="text-right">{{ number_format($data->total_admin - $totaladmin,2,',','.') }}</td>
								<td class="text-center">{{ round((($data->total_admin - $totaladmin) / $data->total_admin) * 100,2) }}%</td>
								<td class="text-center">{{ round(($totaladmin / $data->total_admin) * 100,2) }}%</td>
								<td class="text-center">{{ $status_admin }}</td>
							</tr>
							<tr>
								<td class="text-center">8.</td>
								<td style="text-align:left;">Total Biaya Lain - lain</td>
								<td class="text-right">Rp{{ number_format($data->total_other,2,',','.') }}</td>
								<td class="text-center">{{ $data->total_other > 0 ?  round($data->total_other / $totalbudgetnonppn * 100,2) : 0}}%</td>
								<td class="text-right">Rp{{ number_format($totalother,2,',','.') }}</td>
								<td class="text-center">{{ $totalpemasukansebelumppn > 0 && $totalother > 0 ? round($totalother / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
								<td class="text-right">Rp{{ number_format($totalother - $data->total_other,2,',','.') }}</td>
								<td class="text-center">{{  $data->total_other > 0 ?  round((($totalother - $data->total_other) / $data->total_other) * 100,2) : 0 }}%</td>
								<td class="text-center">{{  $data->total_other > 0 ? round(($totalother / $data->total_other) * 100,2) : 0}}%</td>
								<td class="text-center">-</td>
							</tr>
							<tr>
								<td class="text-center">9.</td>
								<td style="text-align:left;">Profit</td>
								<td class="text-right">Rp{{ number_format($budgetprofit,2,',','.') }}</td>
								<td class="text-center">{{ round($budgetprofit/ $totalbudgetnonppn * 100,2) }}%</td>
								<td class="text-right">Rp{{ number_format($totalprofit,2,',','.') }}</td>
								<td class="text-center">{{ $totalpemasukansebelumppn > 0 ? round($totalprofit / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
								<td class="text-right">Rp{{ number_format($totalprofit - $budgetprofit,2,',','.') }}</td>
								<td class="text-center">{{ round((($totalprofit - $budgetprofit) / $budgetprofit) * 100,2) }}%</td>
								<td class="text-center">{{ round(($totalprofit / $budgetprofit) * 100,2) }}%</td>
								<td class="text-center">-</td>
							</tr>
						  </tbody>
						</table>
					</div>
				</div>
			</div><br><br>
			<table cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<tr>
					<td>
						Catatan : 
						<br>
						{{ $data->note }}
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>