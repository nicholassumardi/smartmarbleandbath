<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{ asset('website/icon.png') }}">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@500&display=swap" rel="stylesheet">
	<link href="{{ asset('template/back-office/global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/bootstrap.min.css?v=7') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/layout.min.css?v=8') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/components.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/assets/css/colors.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/plugins/waitMe/waitMe.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/plugins/lightbox/dist/css/lightbox.min.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/global_assets/image-viewer/css/jquery.magnify.css') }}" rel="stylesheet">
	<link href="{{ asset('template/back-office/custom.css?v=197') }}" rel="stylesheet">
	<script src="{{ asset('template/back-office/global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/forms/styling/switchery.min.js?v=1') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/notifications/noty.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/prism.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/sticky.min.js') }}"></script>
	<script src="{{ asset('template/plugins/waitMe/waitMe.min.js') }}"></script>
	<script src="{{ asset('template/plugins/lightbox/dist/js/lightbox.min.js') }}"></script>
	<script src="{{ asset('template/plugins/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ asset('template/back-office/push.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/extensions/jquery_ui/touch.min.js') }}"></script>
	<script src="{{ asset('template/back-office/assets/js/app.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/plugins/ui/fab.min.js') }}"></script>
	<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
	<script src="{{ asset('template/back-office/global_assets/image-viewer/js/jquery.magnify.js') }}"></script>
	<script src="{{ asset('template/back-office/custom2.js?v=4') }}"></script>
	<script src="{{ asset('template/back-office/signature_pad.min.js') }}"></script>
	<script src="{{ asset('template/back-office/global_assets/js/demo_pages/content_cards_draggable.js') }}"></script>
	<title>SMB Admin - {{ $title }}</title>
</head>