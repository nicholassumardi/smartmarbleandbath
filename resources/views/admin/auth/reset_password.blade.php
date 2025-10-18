<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>SMB Admin - Reset Password</title>
	<link rel="shortcut icon" href="{{ asset('website/icon.png') }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet">
	<link href="{{ asset('template/back-office/global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/layout.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/components.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/colors.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/plugins/waitMe/waitMe.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/plugins/lightbox/dist/css/lightbox.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/custom.css') }}" rel="stylesheet">
	<script src="{{ asset('template/back-office/global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/notifications/noty.min.js') }}"></script>
	<script src="{{ asset('template/plugins/waitMe/waitMe.min.js') }}"></script>
	<script src="{{ asset('template/plugins/number-format/jquery.number.min.js') }}"></script>
	<script src="{{ asset('template/plugins/lightbox/dist/js/lightbox.min.js') }}"></script>
	<script src="{{ asset('template/back-office/assets/js/app.js') }}"></script>
	<script src="{{ asset('template/back-office/custom.js') }}"></script>

	<style>
		body {
			background: url('{{ asset("website/bg-login-bo.jpg") }}') no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
	</style>

</head>
<body>
	<div class="page-content">
		<div class="content-wrapper">
			<div class="content d-flex justify-content-center align-items-center">
				<form class="login-form" action="{{ url()->full() }}" method="POST">
					@csrf
					<div class="content d-flex justify-content-center align-items-center">
						<div class="card shadow-lg bg-white rounded mb-0 w-100">
							<div class="card-body">
								<div class="text-center mb-3">
									<img src="{{ asset('website/icon.png') }}" style="max-width:70px;" alt="Logo">
									<div class="mb-3 mt-3">
										<h5 class="mb-0">RESET PASSWORD</h5>
										<span class="d-block text-muted">Reset your password here</span>
									</div>
								</div>
								@if($errors->any())
									<div class="alert alert-danger font-weight-bold">
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@elseif(session('failed'))
									<div class="alert alert-danger font-weight-bold text-center">{{ session('failed') }}</div>
								@endif
								<div class="form-group">
									<input type="password" name="password" id="password" class="form-control" placeholder="New password" required>
								</div>
								<div class="form-group">
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmation password" required>
								</div>
								<button type="submit" class="btn bg-blue btn-block"><i class="icon-spinner11 mr-2"></i> Reset password</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
