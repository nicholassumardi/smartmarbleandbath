<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>{{ $title }}</title>
	<link rel="shortcut icon" href="{{ asset('website/icon.png') }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet">
	<link href="{{ asset('template/back-office/global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/bootstrap.min.css?v=0') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/layout.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/components.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/colors.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/plugins/lightbox/dist/css/lightbox.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/plugins/jquery-ui/jquery-ui.theme.min.css') }}" rel="stylesheet">
	<script src="{{ asset('template/back-office/global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('template/plugins/lightbox/dist/js/lightbox.min.js') }}"></script>
</head>
<body>
	<div class="page-content">
		<div class="content-wrapper">
			<div class="row">
				<div class="col-md-6 col-xl-4 mx-auto mt-5">
					<!-- Thumbnail with feed -->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h6 class="card-title"><b>{{ $title }}</b></h6>
						</div>

						<div class="card-body">
							<div class="list-feed">
								@foreach($track as $tr)
								<div class="list-feed-item border-warning-400">
									<div class="text-muted font-size-sm mb-1">{{ $tr->created_at }}</div>
									{{ $tr->note }} proof : {!! $tr->image ? '<a href="' . $tr->image() . '" data-lightbox="' . $tr->note . '" data-title="' . $tr->note . '"><img src="' . $tr->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>' !!}
								</div>
								@endforeach
							</div>
						</div>
					</div>
					<!-- /thumbnail with feed -->
				</div>
			</div>
		</div>
	</div>
</body>