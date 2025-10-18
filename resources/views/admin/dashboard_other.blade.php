<div class="content-wrapper">
	<!-- Page header -->
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Dashboard (Under
						maintenance)</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">

				</div>
			</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">

			</div>
		</div>
	</div>
	<!-- /page header -->
	<div class="content">
		<!-- Main charts -->
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Recap</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item btn btn-danger btn-sm text-white" data-action="reload"
									onclick="reset()" data-popup="tooltip" title="Reset Filter"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0">
						<div class="row">
							<div class="col-md-12">
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-6 col-md-12">
				<div class="card" id="doneReport">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">Sales Daily Visit Recap</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="reload" onclick="resetDoneSalesTarget()"
									data-popup="tooltip" title="Reset Filter"></a>
								<a class="list-icons-item" data-action="collapse"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<div class="row">
							<div class="col-sm-4 col-md-4">
								<div class="card card-body"
									style="background-color:purple !important;color:white !important;">
									<div class="media">
										<div class="media-body">
											<h5 class="mb-0" id="totalDailyVisit">0</h5>
											<span class="text-uppercase font-size-xs">Ongoing Daily visit</span>
											<div class="text-italic">All branch</div>
										</div>

										<div class="ml-3 align-self-center">
											<i class="icon-calendar52 icon-2x opacity-75"></i>
										</div>
									</div>
									<div class="text-center mt-2">
										&nbsp; <a href="javascript:void(0);" style="color: white !important;"
											data-toggle="modal" data-target="#modal_recap">Recap</a>
									</div>
								</div>
							</div>
							<input type="month" name="filter" id="filter" class="form-control" value=""
								onchange="submitFilter()">

							<button type="button" class="btn bg-pink-400 btn-labeled btn-labeled-left"
								data-toggle="modal" data-target="#modal_print_report">
								<b><i class="icon-file-pdf"></i></b> Print Sales
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-12">

				<div class="card">
					<div class="card-body py-0" style="max-height: 400px;min-height:400px;overflow:auto;">
						<link href='https://fonts.googleapis.com/css?family=Share+Tech+Mono' rel='stylesheet'
							type='text/css'>
						<link href='https://fonts.googleapis.com/css?family=Signika:400' rel='stylesheet'
							type='text/css'>
						<div class="card-holder">
							<div class="card-custom">
								<span class="title-custom">Bank of PTA</span>
								<span class="bank-logo">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
										class="bi bi-bank" viewBox="0 0 16 16">
										<path
											d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.501.501 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89L8 0ZM3.777 3h8.447L8 1 3.777 3ZM2 6v7h1V6H2Zm2 0v7h2.5V6H4Zm3.5 0v7h1V6h-1Zm2 0v7H12V6H9.5ZM13 6v7h1V6h-1Zm2-1V4H1v1h14Zm-.39 9H1.39l-.25 1h13.72l-.25-1Z" />
									</svg>
								</span>
								<img class="chip" src="{{asset('website/chip.png')}}">
								<img class="mc"
									src="https://upload.wikimedia.org/wikipedia/commons/7/72/MasterCard_early_1990s_logo.png">
								<span class="holo-back"></span>
								<span class="holo"></span>
								<br>
								<span class="emboss number">1234 5678 9876 5432</span><br><br>
								<span class="small-type">Valid From&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UNTIL
									END</span><br>
								<span class="emboss exp">07/23 – 10/33</span><br><br>
								<span class="emboss name-custom">MR CATHERINE HALSEY</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_recap" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Daily Visit Recap</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body content-calendar w-100">
					<div class="calendar-container">
						<div class="calendar">
							<div class="year-header">
								<span class="left-button fa fa-chevron-left" id="prev"> </span>
								<span class="year" id="label"></span>
								<span class="right-button fa fa-chevron-right" id="next"> </span>
							</div>
							<table class="months-table w-100">
								<tbody>
									<tr class="months-row">
										<td class="month">Jan</td>
										<td class="month">Feb</td>
										<td class="month">Mar</td>
										<td class="month">Apr</td>
										<td class="month">May</td>
										<td class="month">Jun</td>
										<td class="month">Jul</td>
										<td class="month">Aug</td>
										<td class="month">Sep</td>
										<td class="month">Oct</td>
										<td class="month">Nov</td>
										<td class="month">Dec</td>
									</tr>
								</tbody>
							</table>

							<table class="days-table w-100">
								<td class="day">Sun</td>
								<td class="day">Mon</td>
								<td class="day">Tue</td>
								<td class="day">Wed</td>
								<td class="day">Thu</td>
								<td class="day">Fri</td>
								<td class="day">Sat</td>
							</table>
							<div class="frame">
								<table class="dates-table w-100">
									<tbody class="tbody">
									</tbody>
								</table>
							</div>
							<!-- <button class="button" id="add-button">Add Event</button> -->
						</div>
					</div>
					<div class="events-container">
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="modal_print_report" data-backdrop="static" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-lg">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Report Sales</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form_report_sales">
						<div class="row justify-content-center">
							<div class="col-md-6">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Mode</label>
									<select name="mode" id="mode" class="form-control"
										onchange="changeMode(this.value)">
										<option value="1">Month</option>
										<option value="2">Date</option>
									</select>
								</div>
							</div>
							<div class="col-md-6 mode1">
								<label style="margin-bottom: 0rem;">Month</label>
								<input type="month" name="filter_month" id="filter_month" class="form-control"
									value="{{date('Y-m')}}">
							</div>
							<div class="col-md-6 mode2 d-none">
								<label style="margin-bottom: 0rem;">Date</label>
								<div class="input-group">
									<input type="date" name="start_date" id="start_date" class="form-control">
									<div class="input-group-prepend">
										<span class="input-group-text">To</span>
									</div>
									<input type="date" name="finish_date" id="finish_date" class="form-control">
								</div>
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col-md-6">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Sales</label>
									<select name="sales_id" id="sales_id"></select>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Customer</label>
									<select name="customer_id" id="customer_id"></select>
									</select>
								</div>
							</div>
						</div>
						<div class="row justify-content-center text-center">
							<div class="col-md-3">
								<div class="form-group">
									<button type="button" class="btn bg-teal-400 btn-block btn-labeled btn-labeled-left"
										data-toggle="modal" data-target="#modal_print_report"
										onclick="printReportSalesMonth()">
										<b><i class="icon-file-pdf"></i></b> Print Report
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
				</div>
			</div>
		</div>
	</div>

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="{{ asset('template/back-office/calendarjs/css/style.css') }}" rel="stylesheet">
	<script src="{{ asset('template/back-office/calendarjs/js/popper.js')}}"></script>
	<script src="{{ asset('template/back-office/calendarjs/js/main.js')}}"></script>
	<script>
		$(function () {
		getProjectTrip();
		select2ServerSide('#sales_id', '{{ url("admin/select2/user") }}');
		select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
	});

	
	
	
	function getProjectTrip(){
		$.ajax({
			url: '{{ url("admin/sales/project/get_project_trip") }}',
			type: 'GET',
			dataType: 'JSON',
			beforeSend: function() {
			loadingOpen('#modal_form');
			},
			success: function(response) {
			loadingClose('#modal_form');
			if(response){
				if(response.data.length > 0){
					$.each(response.data, function (i, val) { 
						var date = new Date(val.date);
				
						var currentDate = new Date();

						// console.log(curr_date)
						new_event_json(val.name, 1, date, currentDate, val.day, val.note, val.proof, val.id);

						if(val.progress < 37 ){
							$("#totalDailyVisit").text(response.data.length);
							new_unfinished_event_json(val.name, date, val.day, val.note, val.proof, val.id);
						}
					});
				}
			}
			},
			error: function() {
			loadingClose('#modal_form');
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}


			
	function printReportSalesMonth(){
		$.ajax({
			type : "GET",
			url  : "{{ url('admin/dashboard/print_sales_report') }}",
			data : {
				filter : $("#filter").val(),
				branch : $("#branch").val(),
				mode : $("#mode").val(),
				start_date : $("#start_date").val(),
				finish_date : $("#finish_date").val(),
				customer_id : $("#customer_id").val(),
				sales_id : $("#sales_id").val(),
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			cache: false,
			success: function(data){
				var w = window.open('about:blank');
				w.document.open();
				w.document.write(data);
				w.document.close();
			},
			error: function() {
			swalInit.fire({
				title: 'Ups, check your internet connection!',
				text: 'Ups. Sorry error.',
				type: 'error'
			});
			}
		});
	}

	function changeMode(val){
		if(val == '1'){
			$('.mode1').removeClass('d-none');
			$('#finish_date').val('');
			$('#start_date').val('');
			$('.mode2').addClass('d-none');
		}else if(val == '2'){
			$('.mode2').removeClass('d-none');
			$('#filter').val('');
			$('.mode1').addClass('d-none');
		}
	}

	$('#modal_print_report').on('hidden.bs.modal', function (e) {
		$("#form_report_sales").trigger('reset');
		$('#sales_id').val(null).trigger('change.select2');
		$('#customer_id').val(null).trigger('change.select2');
	})
				
	</script>