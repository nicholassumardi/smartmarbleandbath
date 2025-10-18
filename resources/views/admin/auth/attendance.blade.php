<!-- Theme JS files -->
<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/fullcalendar/core/main.min.js') }}"></script>
<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/fullcalendar/daygrid/main.min.js') }}"></script>
<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/fullcalendar/timegrid/main.min.js') }}"></script>
<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/fullcalendar/list/main.min.js') }}"></script>
<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/fullcalendar/interaction/main.min.js') }}"></script>
<style>
	.fc-event-time, .fc-event-title {
		padding: 0 1px !important;
		white-space: nowrap !important;
	}

	.fc-title {
		white-space: normal !important;
	}
	
	#my_camera video {
        width: 100% !important;
        height: auto !important;
        min-width: 100px;
        min-height: 100px;
    }
</style>
<div class="content-wrapper">
	<!-- Page header -->
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Attendance</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				
			</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<span class="breadcrumb-item active">{{ session('bo_name') }} Attendance</span>
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
						<h3 class="card-title">{{ session('bo_name') }} Attendance</h3>
						<div class="header-elements">
							
						</div>
					</div>
					<div class="card-body py-0">
						<div class="row">
							<div class="col-md-3">
								<button type="button" class="btn btn-sm bg-success btn-labeled mr-2 btn-labeled-left btn-block" onclick="loadDataTable()">
									<b><i class="icon-sync"></i></b> Refresh
								</button>
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-sm bg-primary btn-labeled mr-2 btn-labeled-left btn-block" data-toggle="modal" data-target="#modal_form">
									<b><i class="icon-iphone"></i></b> Scan QR
								</button>
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-sm bg-info btn-labeled mr-2 btn-labeled-left btn-block" data-toggle="modal" data-target="#modal_selfie">
									<b><i class="icon-reading"></i></b> Selfie
								</button>
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-sm bg-warning btn-labeled btn-labeled-left btn-block" data-toggle="modal" data-target="#modal_calendar">
									<b><i class="icon-calendar2"></i></b> Recap
								</button>
							</div>
						</div>
						<div class="table-responsive mt-3">
						   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
							  <thead class="bg-dark">
								 <tr class="text-center">
									<th width="10%">#</th>
									<th>Date</th>
									<th>IN</th>
									<th>Proof</th>
									<th>OUT</th>
									<th>Proof</th>
								 </tr>
							  </thead>
						   </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-sm">
		  <div class="modal-content" style="height:75%;">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Scan QR Code</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row justify-content-center">
					<div class="col-md-12">
						<div id="prescan" class="text-center">
						<strong><small>Make sure you allow camera access for this form to start scanning QR Code <br>Press Start button to continue.</small></strong>
						</div>
						<video id="preview" style="width: 100%;height:auto;"></video>
					</div>
				</div>
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn btn-danger" id="stopscan">STOP</button>
				<button type="button" class="btn btn-success" id="goscan">START</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_selfie" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-sm">
		  <div class="modal-content" style="height:75%;">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Selfie Attendance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row justify-content-center">
					<div class="col-md-12">
						<div class="alert alert-danger" id="validation_alert" style="display:none;">
							<ul id="validation_content"></ul>
						</div>
						<div id="prescan_selfie" class="text-center">
						<strong><small>Make sure you allow camera access and location for this form to start.<br>Press Start button to continue.</small></strong>
						</div>
						<div id="my_camera" style="width: 100% !important;height:auto !important;margin:auto;"></div>
						<input id="file_selfie" type="hidden" name="file_selfie">
					</div>
					<div class="col-md-12" style="top: -50px;">
						<div class="form-group">
							<label>Note :<sup class="text-danger">*</sup></label>
							<textarea type="text" name="note" id="note" class="form-control" placeholder="Please describe why you use selfie attendance." rows="5"></textarea>
						</div>
					</div>
				</div>
				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn btn-danger" id="closewebcam" onclick="reset()">STOP</button>
				<button type="button" class="btn btn-info" id="openwebcam" onclick="configure()">START</button>
				<button type="button" class="btn btn-success" id="savewebcam" onclick="save()">SAVE</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_calendar" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">My Attendance in Calendar ({{ date('Y') }})</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body" style="background-color:white !important;">
				<div class="row justify-content-center">
					<div class="col-md-12">
						<div class="fullcalendar-basic"></div>
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<div class="mr-auto" style="font-size:25px !important;">
					Helper <i class="icon-point-right mr-2 icon-2x"></i>
					Workdays : <span class="badge badge-success" id="tempworkdays">{{ $countWork }}</span>
					&nbsp;
					Attended : <span class="badge badge-danger" id="tempchecks">{{ $countAtt }}</span>
					&nbsp;
					<br><i><span style="font-size:15px;">Only approved and checked <b>Leave Request</b> is used in Attendance Recap.</span></i>
				</div>
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<script src="{{ asset('template/back-office/instascan/instascan.min.js') }}"></script>
	<script src="{{ asset('template/back-office/webcam/webcam.min.js') }}"></script>
	
	<script>
		var scanner;
		
		function resetScanner(){
			scanner = new Instascan.Scanner({ 
				refractoryPeriod: 5000, 
				video: document.getElementById('preview'),
				mirror: false,
				captureImage: true
			});
		}

		var kamera = 0, kameramati = 1;

		Instascan.Camera.getCameras().then(function (cameras) {
			if (cameras.length > 0) {
				if(cameras.length > 2){
					kamera = 2;
					kameramati = 1;
				}else if(cameras.length > 1){
					kamera = 1;
					kameramati = 0;
				}
			} else {
				notif('danger', 'bg-danger', 'Camera not found or found just 1!');
			}
		}).catch(function (e) {
			notif('danger', 'bg-danger', 'Please check your device / allow camera!');
		});

		$("#goscan").on('click',function() {
			resetScanner();
			scanner.addListener('scan', function (content, image) {
				$.ajax({
					url  : "{{ url('admin/my_attendance/scan') }}",
					type : "POST",
					dataType: 'JSON',
					data : {
						content : content,
						image : image
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						loadingOpen('.modal-content');
					},
					success: function(response){
						if(response.status == 200){
							notif('success', 'bg-success', 'Successfully ' + response.message + '.');
						}else if(response.status == 400){
							notif('danger', 'bg-danger', 'QR Code not found!');
						}else if(response.status == 300){
							notif('danger', 'bg-danger', 'You cannot check in more than 1 time!');
						}
						loadingClose('.modal-content');
						$('#modal_form').modal('toggle');
						scanner.stop();
						scanner = null;
					},
					error: function() {
						swalInit.fire({
						   title: 'Server Error',
						   text: 'Please contact developer',
						   type: 'error'
						});
					 }
				});
				
			});
			
			Instascan.Camera.getCameras().then(function (cameras) {
				if (cameras.length > 0) {
				  scanner.start(cameras[kamera]);
				} else {
				  notif('danger', 'bg-danger', 'Camera not found!');
				}
			}).catch(function (e) {
				 notif('danger', 'bg-danger', 'Ups, error!');
			});
		});

		$("#stopscan").on('click',function() {
			scanner.stop();
			scanner = null;
		});
		
		$(function() {
			loadDataTable();
			
			$('#modal_form').on('hidden.bs.modal', function (e) {
				if(scanner){
					scanner.stop();
					scanner = null;
				}
				loadDataTable();
			});
			
			$('#modal_calendar').on('shown.bs.modal', function () {
				FullCalendarBasic.init();
			});
			
			$('#modal_selfie').on('hidden.bs.modal', function () {
				loadDataTable();
				$('#note').val('');
			});
			
			$('#modal_calendar').on('hidden.bs.modal', function () {
				$('.fullcalendar-basic').html('');
			});
			
			getLocation();
		});
		
		function loadDataTable() {
		  window.table = $('#datatable_serverside').DataTable({
			 serverSide: true,
			 deferRender: true,
			 destroy: true,
			 iDisplayInLength: 10,
			 order: [[0, 'desc']],
			 ajax: {
				url: '{{ url("admin/my_attendance/datatable") }}',
				type: 'GET',
				data: {
					
				},
				beforeSend: function() {
				   loadingOpen('#datatable_serverside');
				},
				complete: function() {
				   loadingClose('#datatable_serverside');
				},
				error: function() {
				   loadingClose('#datatable_serverside');
				   swalInit.fire({
					  title: 'Server Error',
					  text: 'Please contact developer',
					  type: 'error'
				   });
				}
			 },
			 columns: [
				{ name: 'id', searchable: false, className: 'text-center align-middle' },
				{ name: 'date', className: 'text-center align-middle' },
				{ name: 'in', className: 'text-center align-middle' },
				{ name: 'in_image', searchable: false, orderable: false, className: 'text-center align-middle' },
				{ name: 'out', className: 'text-center align-middle' },
				{ name: 'out_image', searchable: false, orderable: false, className: 'text-center align-middle' },
			 ]
		  }); 
		}
		
		var FullCalendarBasic = function() {
			
			var _componentFullCalendarBasic = function() {
				if (typeof FullCalendar == 'undefined') {
					console.warn('Warning - Fullcalendar files are not loaded.');
					return;
				}

				var events = [
					{
					  daysOfWeek: [{{ implode(',',$arrdayoff) }}], //Sundays and saturdays
					  rendering:"background",
					  color: "#ff9f89",
					  overLap: false,
					  allDay: true
					},
					@foreach($arrholiday as $rowholiday)
					{
						title: '{{ $rowholiday->description }}',
						start: '{{ $rowholiday->date }}',
						backgroundColor: '#cc00cc'
					},
					@endforeach
				];
				
				$.ajax({
					url  : "{{ url('admin/my_attendance/get_attendance') }}",
					type : "POST",
					dataType: 'JSON',
					data : { },
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
						
					},
					success: function(response){
						$.each(response.attendance, function(i, val) {
							if(val.in_time){
								events.push({title: 'Attended In (' + val.in_late + ')', start: new Date(val.date + " " + val.in_time).toISOString(), backgroundColor: '#00e600'});
							}
							if(val.out_time){
								events.push({title: 'Attended Out (' + val.out_faster + ')',start: new Date(val.date + " " + val.out_time).toISOString(),backgroundColor: '#1a53ff'});
							}
						});
						
						$.each(response.leave_request, function(i, val) {
							events.push({title: 'Leave Request : ' + val.description, start: new Date(val.date).toISOString(), backgroundColor: '#99cc00'});
						});
						
						$.each(response.arrWorkDay, function(i, val) {
							events.push({title: 'Absence', start: new Date(val).toISOString(), backgroundColor: '#ff3333'});
						});
						
						$('#tempchecks').text(response.countAtt);
						
						var calendarBasicViewElement = document.querySelector('.fullcalendar-basic');

						if(calendarBasicViewElement) {
							var calendarBasicViewInit = new FullCalendar.Calendar(calendarBasicViewElement, {
								plugins: [ 'dayGrid', 'interaction' ],
								header: {
									left: 'prev,next today',
									/* left: 'today', */
									center: 'title',
									right: 'dayGridMonth,dayGridWeek,dayGridDay'
								},
								defaultDate: '{{ date("Y-m-d") }}',
								editable: false,
								events: events,
								eventRender : function(info) {
									const props = info.event.extendedProps,
									rendering = info.event.rendering;

									if(rendering == "background") {
										$(info.el).text(info.event.title);
									}
								},
								eventLimit: true
							}).render();
						}
					}
				});
			};

			return {
				init: function() {
					_componentFullCalendarBasic();
				}
			}
		}();
		
		var x = document.getElementById("demo");
		
		function getLocation() {
		  if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition,error);
		  } else {
			alert("Geolocation is not supported by this browser.");
		  }
		}
		
		function error(err) {
			alert(`ERROR(${err.code}): ${err.message}`);
		}

		var lat = '', lon = '';

		function showPosition(position) {
		  /* const result = "Latitude: " + position.coords.latitude + " - Longitude: " + position.coords.longitude; */
		  lat = position.coords.latitude;
		  lon = position.coords.longitude;
		}
		
		function configure(){
			Webcam.set({
			   width: 320,
			   height: 320,
			   image_format: 'jpeg',
			   jpeg_quality: 90
			});
			Webcam.attach('#my_camera');
		}
		
		function reset(){
			Webcam.reset();
		}
		
		function save(){
			if(lat && lon){
				Webcam.snap(function(data_uri){
					$.ajax({
					 url: "{{ url('admin/my_attendance/selfie') }}",
					 type: 'POST',
					 dataType: 'JSON',
					 data: {
						 image : data_uri,
						 lat : lat,
						 lon : lon,
						 note : $('#note').val()
					 },
					 cache: true,
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('.modal-content');
					 },
					 success: function(response) {
						loadingClose('.modal-content');
						if(response.status == 200) {
						   notif('success', 'bg-success', response.message);
						   $('#modal_selfie').modal('toggle');
						} else if(response.status == 422) {
						   $('#validation_alert').show();
						   notif('warning', 'bg-warning', 'Validation');
						   
						   $.each(response.error, function(i, val) {
							  $.each(val, function(i, val) {
								 $('#validation_content').append(`
									<li>` + val + `</li>
								 `);
							  });
						   });
						} else if(response.status == 400) {
							notif('error', 'bg-danger', response.message);
						}
						Webcam.reset();
					 },
					 error: function() {
						loadingClose('#form_data');
						swalInit.fire({
						   title: 'Server Error',
						   text: 'Please contact developer',
						   type: 'error'
						});
					 }
					});
					
				});
			}
		}
	</script>