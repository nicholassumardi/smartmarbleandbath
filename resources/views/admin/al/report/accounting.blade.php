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
					<span class="breadcrumb-item active">Accounting</span>
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
            <div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Pilih Mode :</label>
						<select name="mode" id="mode" class="form-control">
							<option value="1">Laporan Keuangan</option>
							<option value="2">Laporan Keuangan dengan RAB</option>
							<!-- <option value="3">Progres Setiap Proyek</option> -->
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                     <label>Periode Proyek :</label>
                     <div class="input-group">
                        <input type="month" name="filter_start_month" id="filter_start_month" class="form-control">
                        <div class="input-group-prepend">
                           <span class="input-group-text">s/d</span>
                        </div>
                        <input type="month" name="filter_finish_month" id="filter_finish_month" class="form-control">
                     </div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<button type="button" onclick="generate()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Cari</button>
							<button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
						</div>
					</div>
				</div>
            </div>
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
						<div id="body-result">
							<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
								<span class="font-weight-semibold">Informasi!</span> 
								Pilih mode, periode, dan tekan tombol cari untuk menampilkan laporan.
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
<script>
		
	$(function() {
		$('.sidebar-main-toggle').click();
	});
	
	function resetFilter(){
		$('#filter_start_month').val(null);
		$('#filter_finish_month').val(null);
		$('#branch').val(null);
		$('#method').val('1');
		$('#body-result').html('');
		$('#body-result').append(`
			<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
				<span class="font-weight-semibold">Information!</span> 
				Choose mode and press generate button to show chart.
			</div>
		`);
	}
	
	function generate(){
		if($('#filter_start_month').val() !== '' && $('#filter_finish_month').val() !== ''){
			$.ajax({
				url: '{{ url("admin/al/report/accounting/get_report") }}',
				type: 'POST',
				dataType: 'JSON',
				data: { startMonth : $('#filter_start_month').val(), endMonth : $('#filter_finish_month').val(), mode : $('#mode').val() },
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#body-result');
				},
				success: function(response) {
					loadingClose('#body-result');
					if(response.status == 200) {
						$('#body-result').html(response.content);
						$('html, body').animate({
							scrollTop: $('#card-result').offset().top - 100
						}, 'slow');
					} else {
						notif('warning', 'bg-warning', 'Ups! Error.');
					}
				},
				error: function() {
					loadingClose('#body-result');
				}
			});
		}else{
			notif('error', 'bg-danger', "Please choose periode.");
		}
	}
</script>