<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Tambah Kelengkapan Data</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/al/kelengkapan_data') }}" class="btn bg-secondary mr-2 btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Kembali
					</a>
					<button type="button" class="btn bg-success btn-labeled mr-2 btn-labeled-left" onclick="location.reload()">
						<b><i class="icon-sync"></i></b> Reset
					</button>
					<button type="button" class="btn bg-primary btn-labeled mr-2 btn-labeled-left" onclick="save()">
						<b><i class="icon-floppy-disk"></i></b> Save
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">AL</a>
					<a href="{{ url('admin/al/kelengkapan_data') }}" class="breadcrumb-item">Kelengkapan Data</a>
					<span class="breadcrumb-item active">Tambah</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Tambah Kelengkapan Data</h5>
			</div>
			<div class="card-body">
				<form id="form_data">
					<div class="alert alert-danger" id="validation_alert" style="display:none;">
						<ul id="validation_content"></ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Pilih Proyek :<span class="text-danger">*</span></label>
								<select name="al_project_id" id="al_project_id" class="select2">
									 <option value="">-- Pilih satu --</option>
									 @foreach($proyek as $p)
										<option value="{{ $p->id }}">{{ $p->code.' - '.$p->name }}</option> 
									 @endforeach
								</select>
							</div>
						</div>
						@foreach($document as $d)
						<div class="col-md-3">
							<div class="form-check">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="document" id="document{{ $d->id }}">
									Checked default
								</label>
							</div>
						</div>
						@endforeach
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/decoupled-document/ckeditor.js"></script>
	
	<script>
	
		function save(){
			
			var formData = new FormData($('#form_data')[0]);
			
			formData.append('content',ckvalue.getData());
			
			$.ajax({
				 url: '{{ url("admin/al/kelengkapan_data/create") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: formData,
				 contentType: false,
				 processData: false,
				 cache: true,
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					$('#validation_alert').hide();
					$('#validation_content').html('');
					loadingOpen('#form_data');
				 },
				 success: function(response) {
					loadingClose('#form_data');
					if(response.status == 200) {
						notif('success', 'bg-success', response.message);
						setTimeout(function(){
							window.location.href = "{{ url('admin/al/kelengkapan_data') }}";
						}, 1000);
					} else if(response.status == 422) {
					   $('#validation_alert').show();
					   $('#form_data').scrollTop(0);
					   notif('warning', 'bg-warning', 'Validation');
					   
					   $.each(response.error, function(i, val) {
						  $.each(val, function(i, val) {
							 $('#validation_content').append(`
								<li>` + val + `</li>
							 `);
						  });
						});
					} else {
					   notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					$('.modal-body').scrollTop(0);
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'Server Error',
					   text: 'Please contact developer',
					   type: 'error'
					});
				 }
			});
		}
	</script>