<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Dokumen</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="location.reload()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<a href="{{ url('admin/al/master_data/dokumen/tambah') }}" class="btn bg-primary btn-labeled btn-labeled-left">
						<b><i class="icon-plus3"></i></b> Tambah
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<span class="breadcrumb-item active">Kelengkapan Data</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List Dokumen</h5>
			</div>
			<div class="card-body">
				<div class="row">
					@foreach($document as $row)
					<div class="col-md-4">
						<div class="card">
							<div class="card-body text-bold" style="background-color:#80e5ff !important;">
								{{ strlen($row->document_name) > 50 ? substr($row->document_name,0,50)."..." : $row->document_name }}
							</div>

							<div class="card-footer d-flex justify-content-between">
								<span class="text-muted"><i class="icon-history"></i> {{ date('d M Y',strtotime($row->updated_at)) }}</span>

								<ul class="list-inline mb-0">
									<li class="list-inline-item"><a href="{{ url('admin/al/master_data/dokumen/print/'.$row->id) }}" target="_blank"><i class="icon-printer4"></i></a></li>
									<li class="list-inline-item"><a href="{{ url('admin/al/master_data/dokumen/edit/'.$row->id) }}"><i class="icon-pencil7"></i></a></li>
									<li class="list-inline-item"><a href="javascript:void(0);" onclick="destroy({{ $row->id }})"><i class="icon-trash"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function() {
			
		});
		
		function destroy(id) {
		  var notyConfirm = new Noty({
			 theme: 'limitless',
			 text: '<h6 class="font-weight-bold mb-3">Apakah ingin dihapus?</h6><label>Data yang terhapus tidak bisa dikembalikan.</label>',
			 timeout: false,
			 modal: true,
			 layout: 'center',
			 closeWith: 'button',
			 type: 'confirm',
			 buttons: [
				Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
				   notyConfirm.close();
				}),
				Noty.button('<i class="icon-trash"></i>', 'btn bg-success ml-1', function() {
				   $.ajax({
					  url: '{{ url("admin/al/master_data/dokumen/destroy") }}',
					  type: 'POST',
					  dataType: 'JSON',
					  data: {
						 id: id
					  },
					  headers: {
						 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					  },
					  success: function(response) {
						 if(response.status == 200) {
							notif('success', 'bg-success', response.message);
							notyConfirm.close();
							location.reload();
						 } else {
							notif('error', 'bg-danger', response.message);
						 }
					  },
					  error: function() {
						 swalInit.fire({
							title: 'Server Error',
							text: 'Please contact developer',
							type: 'error'
						 });
					  }
				   });
				})
			 ]
		  }).show();
		}
	</script>