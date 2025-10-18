<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Analisa Budgeting</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="location.reload()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<!-- <button type="button" class="btn bg-primary btn-labeled mr-2 btn-labeled-left" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-plus3"></i></b> Tambah SPH
					</button> -->
					<a href="{{ url('admin/al/rab') }}" class="btn bg-secondary mr-2 btn-labeled btn-labeled-left"><b><i class="icon-arrow-left7"></i></b> Back To All</a>
					<a href="{{url('admin/al/rab/print/' . $data->id) }} " target="_blank" class="btn bg-warning btn-labeled btn-labeled-left"><b><i class="icon-printer2"></i></b>Print</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="{{ url('admin/al/rab') }}" class="breadcrumb-item">RAB</a>
					<span class="breadcrumb-item active">{{ $data->name.' '.$data->alProject->name }}</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Informasi Proyek</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td width="40%">Nama Proyek</td>
										<td>: {{ $data->alProject->name }}</td>
									</tr>
									<tr>
										<td>Nomor</td>
										<td>: {{ $data->alProject->code }}</td>
									</tr>
									<tr>
										<td>Pelanggan</td>
										<td>: {{ $data->alProject->alCustomer->name }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td>Tanggal</td>
										<td>: {{ date('d M Y',strtotime($data->alProject->date)) }}</td>
									</tr>
									<tr>
										<td>PPN</td>
										<td>: {{ $data->alProject->is_ppn() }}</td>
									</tr>
									<tr>
										<td>Kota</td>
										<td>: {{ $data->alProject->city->name }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="form-group"><hr></div>
				<div class="row">
					<div class="col-md-12">
						<h5 class="card-title">Detail Perbandingan Anggaran dan Real</h5>
						<div class="table-responsive">
							<table class="table table-bordered table-striped w-100 table-hover">
							  <thead class="bg-dark">
								 <tr class="text-center">
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
								 <tr class="text-center">
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
									$budgetprofit = $data->budget_ppn ?  $totalbudgetnonppn + $data->total_other - $data->total_deposit - $data->total_dk - $data->total_admin : $data->total_profit - ($data->alSph->ppn + $budgetpph);
									
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
									<td><span class="badge badge-success badge-pill"><i class="icon-download10"></i></span> Pemasukan</td>
									<td class="text-right">Rp{{ number_format($data->total_claim,2,',','.') }}</td>
									<td class="text-center"></td>
									<td class="text-right">Rp{{ number_format($totalpemasukan,2,',','.') }}</td>
									<td class="text-center"></td>
									<td class="text-right">{{ number_format($totalpemasukan - $data->total_claim,2,',','.') }}</td>
									<td class="text-center">{{ round((($totalpemasukan - $data->total_claim) / $data->total_claim) * 100,2) }}%</td>
									<td class="text-center">{{ round(($totalpemasukan / $data->total_claim) * 100,2) }}%</td>
									<td class="text-center"><a href="{{ url('admin/al/finance/pemasukan') }}" class="btn {{ $btn_pemasukan }}">{{ $status_pemasukan }}</a></td>
								</tr>
								<tr>
									<td class="text-center">2.</td>
									<td><span class="badge badge-danger badge-pill"><i class="icon-upload10"></i></span> Pajak PPN</td>
									<td class="text-right">Rp <input type="text" name="budget_ppn" id="budget_ppn" value="{{ number_format($data->budget_ppn ? $data->budget_ppn : $data->alSph->ppn,2,',','.') }}" onkeypress="if(event.keyCode==13){save()}" onkeyup="formatRupiah(this)"></td>
									<td class="text-center">-</td>
									{{-- <td class="text-right">Rp{{ number_format($data->alSph->ppn,2,',','.') }}</td> --}}
									<td class="text-right">Rp <input type="text" name="real_ppn" id="real_ppn" value="{{ number_format($data->real_ppn ? $data->real_ppn : $data->alSph->ppn,2,',','.') }}" onkeypress="if(event.keyCode==13){save()}" onkeyup="formatRupiah(this)"></td>
									<td class="text-center">-</td>
									<td class="text-right">-</td>
									<td class="text-center">-</td>
									<td class="text-center">-</td>
									<td class="text-center">-</td>
								</tr>
								<tr>
									<td class="text-center">3.</td>
									<td><span class="badge badge-danger badge-pill"><i class="icon-upload10"></i></span> Pajak PPH</td>
									<td class="text-right">Rp <input type="text" name="budget_pph" id="budget_pph" value="{{ number_format($budgetpph,2,',','.') }}" onkeypress="if(event.keyCode==13){save()}" onkeyup="formatRupiah(this)"></td>
									<td class="text-center">-</td>
									<td class="text-right">Rp <input type="text" name="real_pph" id="real_pph" value="{{ number_format($realpph,2,',','.') }}" onkeypress="if(event.keyCode==13){save()}" onkeyup="formatRupiah(this)"></td>
									<td class="text-center">-</td>
									<td class="text-right">-</td>
									<td class="text-center">-</td>
									<td class="text-center">-</td>
									<td class="text-center">-</td>
								</tr>
								<tr>
									<td class="text-center">4.</td>
									<td><span class="badge badge-success badge-pill"><i class="icon-download10"></i></span> Pemasukan Sebelum Pajak</td>
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
									<td><span class="badge badge-danger badge-pill"><i class="icon-upload10"></i></span> Pembelanjaan (HPP)</td>
									<td class="text-right">Rp{{ number_format($data->total_deposit,2,',','.') }}</td>
									<td class="text-center">{{ round($data->total_deposit / $totalbudgetnonppn * 100,2) }}%</td>
									<td class="text-right">Rp{{ number_format($totalhpp,2,',','.') }}</td>
									<td class="text-center">{{ $totalpemasukansebelumppn > 0 ? round($totalhpp / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
									<td class="text-right">{{ number_format($data->total_deposit - $totalhpp,2,',','.') }}</td>
									<td class="text-center">{{ round((($data->total_deposit - $totalhpp) / $data->total_deposit) * 100,2) }}%</td>
									<td class="text-center">{{ round(($totalhpp / $data->total_deposit) * 100,2) }}%</td>
									<td class="text-center"><a href="{{ url('admin/al/finance/pengeluaran') }}" class="btn {{ $btn_pengeluaran }}">{{ $status_hpp }}</a></td>
								</tr>
								<tr>
									<td class="text-center">6.</td>
									<td><span class="badge badge-danger badge-pill"><i class="icon-upload10"></i></span> DK</td>
									<td class="text-right">Rp{{ number_format($data->total_dk,2,',','.') }}</td>
									<td class="text-center">{{ round($data->total_dk / $totalbudgetnonppn * 100,2) }}%</td>
									<td class="text-right">Rp{{ number_format($totaldk,2,',','.') }}</td>
									<td class="text-center">{{ $totalpemasukansebelumppn > 0 ? round($totaldk / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
									<td class="text-right">{{ number_format($data->total_dk - $totaldk,2,',','.') }}</td>
									<td class="text-center">{{ round((($data->total_dk - $totaldk) / $data->total_dk) * 100,2) }}%</td>
									<td class="text-center">{{ round(($totaldk / $data->total_dk) * 100,2) }}%</td>
									<td class="text-center"><a href="{{ url('admin/al/finance/pengeluaran') }}" class="btn {{ $btn_dk }}">{{ $status_dk }}</a></td>
								</tr>
								<tr>
									<td class="text-center">7.</td>
									<td><span class="badge badge-danger badge-pill"><i class="icon-upload10"></i></span> Admin</td>
									<td class="text-right">Rp{{ number_format($data->total_admin,2,',','.') }}</td>
									<td class="text-center">{{ round($data->total_admin / $totalbudgetnonppn * 100,2) }}%</td>
									<td class="text-right">Rp{{ number_format($totaladmin,2,',','.') }}</td>
									<td class="text-center">{{ $totalpemasukansebelumppn > 0 ? round($totaladmin / $totalpemasukansebelumppn * 100,2) : 0 }}%</td>
									<td class="text-right">{{ number_format($data->total_admin - $totaladmin,2,',','.') }}</td>
									<td class="text-center">{{ round((($data->total_admin - $totaladmin) / $data->total_admin) * 100,2) }}%</td>
									<td class="text-center">{{ round(($totaladmin / $data->total_admin) * 100,2) }}%</td>
									<td class="text-center"><a href="{{ url('admin/al/finance/pengeluaran/detail').'/'.$data->alProject->id }}" class="btn {{ $btn_admin }}">{{ $status_admin }}</a></td>
								</tr>
								<tr>
									<td class="text-center">8.</td>
									<td><span class="badge badge-success badge-pill"><i class="icon-download10"></i></span> Total Biaya Lain - lain</td>
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
									<td><span class="badge badge-success badge-pill"><i class="icon-download10"></i></span> Profit</td>
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
				</div>
			</div>
		</div>
	</div>
<script>
	function save(){
		$.ajax({
				 url: '{{ url("admin/al/rab/update_tax") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: {
					id: '{{$data->id}}',
					budget_ppn : $('#budget_ppn').val(),
					budget_pph : $('#budget_pph').val(),
					real_ppn : $('#real_ppn').val(),
					real_pph : $('#real_pph').val(),
				 },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					if(response.status == 200) {
						location.reload();
					   notif('success', 'bg-success', response.message);
					} else {
					   notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					$('.modal-body').scrollTop(0);
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
				 }
			  });
	}
</script>