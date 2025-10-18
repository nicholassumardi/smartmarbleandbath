<style>
	.selected-card {
		border: 2px solid blue;
	}
</style>
<script src="{{ url('template/back-office/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Company's Legal Documents</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<span class="float-right">
						<b class="mr-1">Bulk Operation : <i id="nominal_count" style="font-size:15px;">0</i>
							selected.</b>
						<button type="button" class="btn bg-danger btn-icon mr-2" onclick="deleteFiles()">
							<b><i class="icon-bin"></i></b> Delete
						</button>
						<button type="button" class="btn bg-info btn-icon mr-2" onclick="downloadFiles()">
							<b><i class="icon-download"></i></b> Download
						</button>
						<button type="button" class="btn bg-secondary btn-icon mr-2" onclick="showModalEmail()">
							<b><i class="icon-envelop3"></i></b> Mail
						</button>
						<span style="border-right:3px solid black;height:100%;" class="mr-2"></span>
						<button type="button" class="btn bg-success btn-icon mr-2" onclick="location.reload()">
							<b><i class="icon-sync"></i></b> Refresh
						</button>
						<button type="button" class="btn bg-primary btn-icon" data-toggle="modal"
							data-target="#modal_upload">
							<b><i class="icon-plus3"></i></b> Add
						</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
						Dashboard</a>
					<span class="breadcrumb-item active">Company's Legal Documents</span>

				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">List of Company's Legal Documents</h5>
				<div class="header-elements">
					<button type="button" class="btn bg-warning btn-icon mr-2" onclick="showHistory()">
						<b><i class="icon-history"></i></b> History
					</button>
				</div>
			</div>
			<div class="card-body" id="data-files">
				<div class="row">
					{{-- @foreach($datacompany as $key => $row)
					<div class="col-sm-6 col-xl-3">
						<div class="card" data-id="{{ $row->id }}" data-name="{{ $row->file_name }}">
							<div class="card-img-actions mx-1 mt-1">
								<span class="badge badge-pill bg-danger ml-auto ml-md-0">{{ $key + 1 }}</span>

								@if($row->extension() == 'pdf')
								<!-- <img class="card-img img-fluid" src="{{ $row->attachment() }}" alt="{{ $row->file_name }}"> -->
								<canvas id="the-canvas{{ $key }}"></canvas>
								@else
								<div style="background:white;height:200px;">
									<img class="card-img img-fluid" src="{{ $row->attachment() }}"
										alt="{{ $row->file_name }}">
								</div>
								@endif

								<div class="card-img-actions-overlay card-img">
									<a href="{{ asset(Storage::url($row->storage_name)) }}"
										class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round"
										data-popup="lightbox" rel="group">
										<i class="icon-search4"></i>
									</a>
									<a href="{{ url('admin/company_legality/download/'.$row->id) }}"
										class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2"
										target="_blank">
										<i class="icon-download"></i>
									</a>
									<a href="javascript:void(0);"
										class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2"
										onclick="deleteFile({{ $row->id }})">
										<i class="icon-bin"></i>
									</a>
								</div>
							</div>

							<div class="card-body choose-data">
								<div class="d-flex align-items-start flex-nowrap">
									<div>
										<div class="font-weight-semibold mr-2">{{ $row->file_name }}</div>
										<span class="font-size-sm text-muted">Size: {{
											number_format(Storage::disk('local')->size($row->storage_name)/1024,0,',','.')
											}} Kb</span>
									</div>
								</div>
								<p class="mt-2 text-muted font-italic font-size-sm">Click/tap here to select this file.
								</p>
							</div>
						</div>
					</div>
					@endforeach --}}
					@if(count($datacompany) == 0)
					<div class="row">
						<div class="col-md-12 mx-auto">
							<div class="alert alert-info alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
								<span class="font-weight-semibold">Ups!</span> There is no legal documents found. You
								may upload using Add button.</b>.
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal_upload" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Add Files</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Max 5 files each upload. Refresh the page to add more.</p>
					<p>Accepted files are image & pdf.</p>
					<p>Please ensure your internet connection is stable.</p>
					<form action="{{ url('admin/company_legality/add_files') }}" class="dropzone"
						id="dropzone_multiple">
						@csrf
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_email" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Send file(s) to multiple Email.</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form_data">
						<div class="row">
							<div class="col-md-9">
								<h3>Form Email</h3>
								<div class="alert alert-danger" id="validation_alert" style="display:none;">
									<ul id="validation_content"></ul>
								</div>
								<div class="form-group">
									<div class="alert alert-info alert-styled-left alert-dismissible"><button
											type="button" class="close"
											data-dismiss="alert"><span>×</span></button><span
											class="font-weight-semibold">Important!</span> While sending the emails
										please wait, it may take 1-3 minutes to send, it depends on your file's size.
									</div>
								</div>
								<div class="form-group">
									<label>To :<span class="text-danger">*</span></label>
									<input type="text" name="email" id="email" class="form-control"
										placeholder="Email recipient.">
								</div>
								<div class="form-group">
									<label>Subject :<span class="text-danger">*</span></label>
									<input type="text" name="subject" id="subject" class="form-control"
										placeholder="Enter subject here.">
								</div>
								<div class="form-group">
									<label>Content :<span class="text-danger">*</span></label>
									<textarea name="content" id="content" class="content"></textarea>
								</div>

							</div>
							<div class="col-md-3">
								<h3>Attachment(s)</h3>
								<div id="list-attachment">

								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
					<button type="button" class="btn bg-success" onclick="sendQuotation()"><i class="icon-plus2"></i>
						Send</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_history" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Send file(s) to multiple Email.</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form_data">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="datatable_serverside" class="table table-bordered table-striped w-100">
										<thead class="bg-dark">
											<tr class="text-center">
												<th>Date Sent</th>
												<th>To</th>
												<th>Subject</th>
												<th>Content</th>
												<th>Attachments</th>
											</tr>
										</thead>
										<tbody id="body-history">

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
					<button type="button" class="btn bg-success" onclick="sendQuotation()"><i class="icon-plus2"></i>
						Send</button>
				</div>
			</div>
		</div>
	</div>
	<script src="{{ url('template/back-office/pdfjs/build/pdf.js') }}"></script>
	<script id="script">
		pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ url("template/back-office/pdfjs/build/pdf.worker.js") }}';

	</script>
	<script>
		$(function() {
		ckEditor('content');
		
		$('.choose-data').click(function() {
			if($(this).parent().hasClass('selected-card')){
				$(this).parent().removeClass('selected-card');
			}else{
				$(this).parent().addClass('selected-card');
			}
			
			countBulk();
		});
		
		$('#modal_upload').on('hidden.bs.modal', function (e) {
			location.reload();
		});
	});
	
	function countBulk(){
		$('#nominal_count').text($('.selected-card').length);
	}

	Dropzone.options.dropzoneMultiple = {
		paramName: "file",
		timeout: 180000,
		maxFilesize: 5,
		maxFiles: 50,
		acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf",
		init: function() {
			this.on("success", function(file, responseText) {
				if(responseText.status == '422'){
					notif('error', 'bg-danger', responseText.message);
				}else if(responseText.status == '200'){
					/* this.removeFile(file); */
					notif('success', 'bg-success', responseText.message);
				}
			});
		}
	};
	
	function deleteFile(val) {
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label>',
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
				 url: '{{ url("admin/company_legality/delete_file") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : val },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('#data-files');
				 },
				 success: function(response) {
					loadingClose('.data-files');
					if(response.status == 200) {
						notif('success', 'bg-success', response.message);
						location.reload();
						notyConfirm.close();
					} else {
						notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					loadingClose('.data-files');
					swalInit.fire({
					   title: 'You do not have permission to delete this project!',
					   text: 'Please contact sales manager to ask delete this project.',
					   type: 'error'
					});
				 }
				});
			})
         ]
		}).show();
	}
	
	function deleteFiles() {
		if($('.selected-card').length > 0){
			
			var arrid = [];
			
			$('.selected-card').each(function( index ) {
				arrid.push($(this).data('id'));
			});
			
			var notyConfirm = new Noty({
			 theme: 'limitless',
			 text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete these ' + $(".selected-card").length + ' file(s)?</h6><label>Deleted files can no longer be recovered.</label>',
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
					 url: '{{ url("admin/company_legality/delete_files") }}',
					 type: 'POST',
					 dataType: 'JSON',
					 data: { arrid : arrid },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('#data-files');
					 },
					 success: function(response) {
						loadingClose('#data-files');
						if(response.status == 200) {
							notif('success', 'bg-success', response.message);
							location.reload();
							notyConfirm.close();
						} else {
							notif('error', 'bg-danger', response.message);
						}
					 },
					 error: function() {
						loadingClose('#data-files');
						swalInit.fire({
						   title: 'You do not have permission to delete this project!',
						   text: 'Please contact sales manager to ask delete this project.',
						   type: 'error'
						});
					 }
					});
				})
			 ]
			}).show();
		}else{
			notif('error', 'bg-danger', 'Please choose at least 1 file to delete.');
		}
	}
	
	function downloadFiles() {
		if($('.selected-card').length > 0){
			
			var arrid = [];
			
			$('.selected-card').each(function( index ) {
				arrid.push($(this).data('id'));
			});
			
			$.ajax({
			 url: '{{ url("admin/company_legality/download_files") }}',
			 type: 'GET',
			 data: { arrid : arrid },
			 xhrFields: {
                responseType: 'blob'
             },
			 beforeSend: function() {
				loadingOpen('#data-files');
			 },
			 success: function(response) {
				loadingClose('#data-files');
				var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "company_legality_docs.zip";
                link.click();
			 }
			});
				
		}else{
			notif('error', 'bg-danger', 'Please choose at least 1 file to download.');
		}
	}
	
	function showModalEmail(){
		if($('.selected-card').length > 0){
			$('#modal_email').modal('toggle');
			
			$('#list-attachment').empty();
			
			$('#list-attachment').append(`
				<ol>
			`);
			
			$('.selected-card').each(function( index ) {
				$('#list-attachment').append(`
					<li><input type="hidden" name="file_id[]" value="`+ $(this).data('id') + `">` + $(this).data('name') + `</li>
				`);
			});
			
			$('#list-attachment').append(`
				</ol>
			`);
		}else{
			notif('warning', 'bg-warning', 'Please choose at least 1 file to email.');
		}
	}
	
	function showHistory(){
		$.ajax({
		 url: '{{ url("admin/company_legality/get_history_email") }}',
		 type: 'GET',
		 data: {  },
		 beforeSend: function() {
			loadingOpen('#data-files');
		 },
		 success: function(response) {
			loadingClose('#data-files');
			if(response.status == 200){
				$('#body-history').empty();
				$.each(response.data, function(i, val) {
					$('#body-history').append(`
						<tr>
							<td class="text-center">` + val.created_at.replace("T", " ") + `</td>
							<td class="text-center">` + val.email + `</td>
							<td class="text-center">` + val.subject + `</td>
							<td class="text-center">` + val.content + `</td>
							<td class="text-center">` + val.attachments + `</td>
						</tr>
					`);
				});
				
				$('#modal_history').modal('toggle');
			}
		 }
		});
	}
	
	function sendQuotation(){
		var formData = new FormData($('#form_data')[0]);
		formData.append("content",CKEDITOR.instances.content.getData());
		
		$.ajax({
			url: '{{ url("admin/company_legality/send_mail") }}',
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
				loadingOpen('.modal-content');
			},
			success: function(response) {
				loadingClose('.modal-content');
				console.log(response.data)
				if(response.status == 200) {
				   $('#modal_email').modal('toggle');
				   notif('success', 'bg-success', response.message);
				   setTimeout(function() { 
						location.reload();
				   }, 1500);
				} else if(response.status == 422) {
				   $('#validation_alert').show();
				   $('.modal-body').scrollTop(0);
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