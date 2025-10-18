<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>{{ $data->document_name }}</title>
		<style>
			
			@media print {
				@page {size: A4 portrait; }
			}

		</style>
	</head>
	<body onload="window.print()">
		@php
			$var = 'JOKO';
		@endphp
		<div class="invoice-box">
			{!! $data->content !!}
		</div>
	</body>
</html>