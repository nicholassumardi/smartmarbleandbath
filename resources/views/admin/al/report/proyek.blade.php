<style>
	#body-result {
	  min-height: 400px;
	}
	.progress {
		background-color:#af6464;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Accounting</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Laporan</a>
					<span class="breadcrumb-item active">Proyek</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h2 class="card-title font-weight-bold">Filter</h2>
			</div>
         <div class="card-body">
			<form action="{{ url('admin/al/report/proyek') }}" method="GET" id="form_filter">
			@csrf
            <div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Periode Proyek :</label>
						<select class="form-control" id="filter_year" name="filter_year">
							@for($i = intval(date('Y')-5); $i <= intval(date('Y') + 5);$i++)
							<option value="{{ $i }}" {{ $filter_year == $i ? 'selected' : '' }}>{{ $i }}</option>
							@endfor
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<button type="submit" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Cari</button>
							<a href="{{ url('admin/al/report/proyek') }}" class="btn bg-danger mr-2"><i class="icon-sync"></i></a>
							<button type="button" onclick="print()" class="btn bg-success"><i class="icon-printer2"></i></button>
						</div>
					</div>
				</div>
            </div>
			</form>
         </div>
		</div>
		<div class="mb-3">
         <h6 class="mb-0 font-weight-semibold text-center text-uppercase">
            <span id="string_filter_periode"></span>
         </h6>
		</div>
		<div class="card" id="card-result">
			<div class="card-header">
				<h2 class="card-title font-weight-bold">Hasil</h2>
			</div>
			<div class="card-body">
				<div class="row justify-content-center">
					<div class="col-md-12">
						@if(count($al_project) > 0)
						@php
							$alproject = $al_project;
							$totalchecklist = $total_checklist;
						@endphp
						<h1>Laporan Rekap Progres Proyek Tahun {{ $filter_year }}</h1>
						<h5><i>Prosentase progres didapatkan dari checklist setiap proyek dibagi dengan total keseluruhan checklist yang ada.</i></h5>
						<div class="table-responsive">	
							<table id="datatable_serverside" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark sidebar-sticky">
								<tr class="text-center">
									<th width="1%">No</th>
									<th width="40%">Proyek</th>
									<th>Progres</th>
									<th width="10%">Checklist</th>
								</tr>
							  </thead>
							  <tbody>
								@foreach($alproject as $key => $rowproject)
									<tr>
										<td>{{ ($key + 1) }}</td>
										<td>{{ $rowproject->code.' - '.$rowproject->name.' CUST. '.$rowproject->alCustomer->name }}</td>
										<td class="text-center">
											<div class="progress rounded-round">
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: {{ $rowproject->getPercentComplete() }}%">
													<span style="position: absolute;margin-left:45%;">{{ $rowproject->getPercentComplete() }}% Complete</span>
												</div>
											</div>
										</td>
										<td class="text-center">
											{{ count($rowproject->alChecklistProject).' / '.$totalchecklist }}
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
						@else
							<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
								<span class="font-weight-semibold">Data tidak ditemukan!</span> 
								Silahkan cari dengan periode lainnya.
							</div>
						@endif
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
<script>
		
	$(function() {
		$('.sidebar-main-toggle').click();
	});
	
	function print(){
		window.location.href = "{{ url('admin/al/report/proyek/print/') }}" + "?year=" + $('#filter_year').val();
		
		return false;
	}
</script>
