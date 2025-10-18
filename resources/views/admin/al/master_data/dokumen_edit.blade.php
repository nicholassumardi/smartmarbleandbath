<style>
	#cke_editor {
		display:none;
	}
	#editor {
		background-color:white;
	}
	
	.ck-editor__editable_inline {
		min-height: 1200px;
		min-height: 800px;
		margin:auto;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Edit Master Dokumen</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/al/master_data/dokumen') }}" class="btn bg-secondary mr-2 btn-labeled btn-labeled-left">
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
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="{{ url('admin/al/master_data/dokumen') }}" class="breadcrumb-item">Dokumen</a>
					<span class="breadcrumb-item active">Edit Dokumen</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Edit Dokumen</h5>
			</div>
			<div class="card-body">
				<form id="form_data">
					<div class="alert alert-danger" id="validation_alert" style="display:none;">
						<ul id="validation_content"></ul>
					</div>
					<div class="row justify-content-center">
						<div class="col-md-8">
							<div class="form-group">
								<label>Nama Dokumen :<span class="text-danger">*</span></label>
								<input type="hidden" name="temp" id="temp" value="{{ $document->id }}">
								<input type="text" name="name" id="name" class="form-control" placeholder="Ketik nama dokumen disini..." value="{{ $document->document_name }}">
							</div>
						</div>
						<div class="col-md-8">
							<div id="toolbar-container"></div>
							<div id="editor">
								{!! $document->content !!}
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/decoupled-document/ckeditor.js"></script>
	
	<script>
	
		class MyUploadAdapter {
		   constructor(loader) {
			  this.loader = loader;

			  this.url = '{{ url("admin/al/master_data/dokumen/ckeditor") }}';
		   }
		   upload() {
			  return this.loader.file.then(
				 (file) =>
					new Promise((resolve, reject) => {
					   this._initRequest();
					   this._initListeners(resolve, reject, file);
					   this._sendRequest(file);
					})
			  );
		   }
		   abort() {
			  if (this.xhr) {
				 this.xhr.abort();
			  }
		   }
		   _initRequest() {
			  const xhr = (this.xhr = new XMLHttpRequest());
			  xhr.open("POST", this.url, true);
			  xhr.setRequestHeader("x-csrf-token", "{{ csrf_token() }}");
			  xhr.responseType = "json";
		   }
		   _initListeners(resolve, reject, file) {
			  const xhr = this.xhr;
			  const loader = this.loader;
			  const genericErrorText = `Couldn't upload file: ${file.name}.`;
			  xhr.addEventListener("error", () => reject(genericErrorText));
			  xhr.addEventListener("abort", () => reject());
			  xhr.addEventListener("load", () => {
				 const response = xhr.response;
				 if (!response || response.error) {
					return reject(response && response.error ? response.error.message : genericErrorText);
				 }
				 resolve({
					default: response.url,
				 });
			  });
			  if (xhr.upload) {
				 xhr.upload.addEventListener("progress", (evt) => {
					if (evt.lengthComputable) {
					   loader.uploadTotal = evt.total;
					   loader.uploaded = evt.loaded;
					}
				 });
			  }
		   }
		   _sendRequest(file) {
			  const data = new FormData();
			  data.append("upload", file);
			  this.xhr.send(data);
		   }
		   // ...
		}

		function SimpleUploadAdapterPlugin(editor) {
		   editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
			  return new MyUploadAdapter(loader);
		   };
		}
		
		DecoupledEditor
            .create( document.querySelector( '#editor' ), {
				extraPlugins: [SimpleUploadAdapterPlugin],
				fontSize: {
					options: [
						9,
						10,
						11,
						12,
						13,
						14,
						15,
						16,
						17,
						19,
						20,
					]
				}
			})
            .then( editor => {
                const toolbarContainer = document.querySelector( '#toolbar-container' );
				
                toolbarContainer.appendChild( editor.ui.view.toolbar.element );
				
				ckvalue = editor;
            })
            .catch( error => {
                console.error( error );
            });
		
		function save(){
			
			var formData = new FormData($('#form_data')[0]);
			
			formData.append('content',ckvalue.getData());
			
			$.ajax({
				 url: '{{ url("admin/al/master_data/dokumen/create") }}',
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
							window.location.href = "{{ url('admin/al/master_data/dokumen') }}";
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