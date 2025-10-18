<style>
	#img_qr img {
		width:100% !important;
	}
	
	#my_camera video {
        width: 100% !important;
        min-width: 100px;
        min-height: 100px;
    }
	
	.container-refresh{
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		z-index: 9989;
		text-align: center;
		overflow: hidden;
		background-color: rgba(255, 255, 255, 1);
	}
	
	.refresh-button {
		position: absolute;
		left: 0;
		right: 0;
		top: 50%;
		backface-visibility: hidden;
	}
	
	.mode-leave {
		-ms-transform: scale(3);
		-webkit-transform: scale(3);
		transform: scale(2);
		margin-top: .9rem !important;
		margin-left: -2.5rem !important;
	}
	
	.text-leave {
		font-size:25px;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Generate QR Code</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="location.reload();">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('attendance/code') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Main</a>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="row">
			<div class="col-md-4">
				<!-- Top placement -->
				<div class="card">
					<div class="card-img-actions">
						<div id="img_qr" style="aspect-ratio:1/1;width:100%;">
							
						</div>
					</div>

					<div class="card-body">
						<h5 class="card-title">SCAN HERE!</h5>
						<p class="card-text">This code is generated every 5 seconds.</p>
					</div>
				</div>
				<!-- /top placement -->
			</div>
			<div class="col-md-4">
				<!-- Top placement -->
				<div class="card">
					<div class="card-img-actions">
						<div id="my_camera" style="width: 100% !important;height:auto !important;margin:auto;"></div>
					</div>

					<div class="card-body">
						<div class="text-uppercase mb-0 font-weight-bold text-center">
                            <!-- <span style="font-size:35px;" id="header-clock-realtime">{{ date('H:i:s') }}</span> -->
                            <h5>{{ date('D, d M Y') }}</h5>
                        </div>
					</div>
				</div>
				<!-- /top placement -->
			</div>
			<div class="col-md-4" style="zoom:0.8;">
				<div class="card border-success">
					<div class="card-header bg-success-100 border-success d-flex justify-content-between">
						<span class="text-success font-weight-semibold">CHECK LOG MODE</span>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<p>
									This mode will automatically change based on real time system (GMT+7). Before 12pm, it will select <b>IN</b> (check in), after that it will select <b>OUT</b> (check out).
								</p>
							</div>
							<div class="col-md-6 mx-auto">
								<button type="button" class="btn btn-success btn-block btn-mode" id="btn-in" style="font-size:18px;"><i class="icon-checkmark4 icon-in"></i> IN</button>
							</div>
							<div class="col-md-6">
								<button type="button" class="btn btn-danger btn-block btn-outline-danger btn-mode" id="btn-out" style="font-size:18px;"><i class="icon-checkmark4 icon-out d-none"></i> OUT</button>
							</div>
						</div>
					</div>
				</div>
				
				<div class="card border-warning mt-3">
					<div class="card-header bg-warning-100 border-warning d-flex justify-content-between">
						<span class="text-danger font-weight-semibold">LEAVE OUT/IN MODE</span>
						<button class="btn btn-info btn-sm" onclick="resetLeave();"><i class="icon-sync"></i> Reset</button>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<p>
									Tap this if you want to leave <b>out</b> or <b>in</b> from office in working hour. 
								</p>
							</div>
							<div class="col-md-6 text-center text-leave">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input mode-leave" name="leave_request" value="OUT">
										OUT
									</label>
								</div>
							</div>
							<div class="col-md-6 text-center text-leave">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input mode-leave" name="leave_request" value="IN">
										IN
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="{{ asset('template/back-office/jquery_qrcode_master/dist/jquery-qrcode.js') }}"></script>
	<script src="{{ asset('template/back-office/webcam/webcam.min.js') }}"></script>
<script>
	var interval = null;
	var timeout = null;
	
	$(function() {
		generateCode();
		runInterval();
		runTimeout();
		configure();
		
		@if(date('H') < 12)
			$('#btn-in').removeClass('btn-outline-danger btn-danger').addClass('btn-success');
			$('.icon-in').removeClass('d-none');
			$('#btn-out').addClass('btn-outline-danger btn-danger').removeClass('btn-success');
			$('.icon-in').addClass('d-none');
		@else
			$('#btn-out').removeClass('btn-outline-danger btn-danger').addClass('btn-success');
			$('.icon-out').removeClass('d-none');
			$('#btn-in').addClass('btn-outline-danger btn-danger').removeClass('btn-success');
			$('.icon-in').addClass('d-none');
		@endif
		
		$('.mode-leave').click(function(){
			if($(this).is(':checked')){
				if(interval){
					clearInterval(interval);
				}
				generateCode();
				runInterval();
				configure();
			}
		});
		
		$('.sidebar-main-toggle').click();
	});
	
	var timer = '';
	var flag = true;
	
	/* setInterval(function(){
		phpJavascriptClock();
	},1000); */
	
	
	function phpJavascriptClock()
	{
		if(flag){
			timer = {{ strtotime(date('Y-m-d H:i:s')) }} * 1000;
		}
		
		var d = new Date(timer);

		var hours = d.getHours();
		var minutes = d.getMinutes();
		var seconds = d.getSeconds();

		$('#header-clock-realtime').text(('0' + hours).substr(-2) + ':' + ('0' + minutes).substr(-2) + ':' + ('0' + seconds).substr(-2));

		flag = false;
		timer = timer + 1000;
	}
	
	function resetLeave(){
		$('.mode-leave').each(function(){
			if($(this).is(':checked')){
				$(this).prop('checked', false);
				if(interval){
					clearInterval(interval);
				}
				
				if(timeout){
					clearTimeout(timeout);
				}
				generateCode();
				runInterval();
				runTimeout();
				configure();
			}
		});
	}
	
	function runInterval(){
		interval = setInterval(function () {
			$('#img_qr').empty();
			loadingOpen('#img_qr');
			generateCode();
		}, 5000);
	}
	
	function runTimeout(){
		timeout = setTimeout(function() {
			clearInterval(interval);
			Webcam.reset();
			$('#img_qr').append(`
				<div class="container-refresh">
					<div class="refresh-button">
						<button type="button" class="btn btn-secondary btn-icon rounded-pill" onclick="refreshCode();"><i class="icon-reload-alt icon-2x"></i></button>
					</div>
				</div>
			`);
			
			let leave_request = $(".mode-leave:checked").val() == undefined ? '' : $(".mode-leave:checked").val();
			
			$.ajax({
				url: 'https://smartmarbleandbath.com/attendance/generate',
				type: 'POST',
				dataType: 'JSON',
				data: { leave : leave_request },
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					
				},
				success: function(response) {
					
				},
				error: function() {
					notif('error', 'bg-danger', 'Ups. Check your connetion.');
				}
			});
		}, 24500);
	}
	
	function refreshCode(){
		generateCode();
		runInterval();
		runTimeout();
		configure();
	}
	
	function generateCode(){
		$('#img_qr').empty();
		loadingOpen('#img_qr');
		
		let leave_request = $(".mode-leave:checked").val() == undefined ? '' : $(".mode-leave:checked").val();
		
		$.ajax({
			url: 'https://smartmarbleandbath.com/attendance/generate',
			type: 'POST',
			dataType: 'JSON',
			data: { leave : leave_request },
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				
			},
			success: function(response) {
				if(response.status == '200'){
					loadingClose('#img_qr');
					
					$('#img_qr').qrcode({
						render: 'image',

						minVersion: 1,
						maxVersion: 40,

						ecLevel: 'L',

						left: 0,
						top: 0,

						size: 400,

						fill: '#000',

						background: null,

						text: response.code,

						radius: 0,

						quiet: 0,

						mode: 0,

						mSize: 0.1,
						mPosX: 0.5,
						mPosY: 0.5,

						label: 'no label',
						fontname: 'sans',
						fontcolor: '#000',

						image: null
					});
				}
			},
			error: function() {
				notif('error', 'bg-danger', 'Ups. Check your connetion.');
			}
		});
	}
	
	function configure(){
		Webcam.set({
		   width: 250,
		   height: 250,
		   image_format: 'jpeg',
		   jpeg_quality: 100
		});
		Webcam.attach('#my_camera');
	}
	
	function reset(){
		Webcam.reset();
	}
</script>