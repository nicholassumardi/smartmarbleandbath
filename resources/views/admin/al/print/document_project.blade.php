<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Daftar dokumen proyek {{ $proyek->name.' '.$proyek->code }}</title>
		<style>
		
			.invoice-box {
				page-break-after: always;
			}
			
			@media print {
				@page {size: A4 portrait; }
			}

		</style>
	</head>
	<body onload="window.print()">
		@foreach($proyek->alDocumentProject as $row)
		<div class="invoice-box">
			{!! str_replace('project_name',$row->alProject->name,str_replace('project_year',date('Y',strtotime($row->alProject->date)),str_replace('customer_name',$row->alProject->alCustomer->name,str_replace('customer_institution_name',$row->alProject->alCustomer->alInstitute->name,str_replace('customer_address',$row->alProject->alCustomer->address,str_replace('date_print',App\Helper\SMB::tgl_indo(date("Y-m-d")),$row->alDocument->content)))))) !!}
		</div>
		@endforeach
	</body>
</html>