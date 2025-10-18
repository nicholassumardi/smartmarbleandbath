<style>
	.selected-card {
		border:2px solid blue;
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
					<b class="mr-1">Bulk Operation : <i id="nominal_count" style="font-size:15px;">0</i> selected.</b>
					<button type="button" class="btn bg-danger btn-icon mr-2" onclick="deleteFiles()">
						<b><i class="icon-bin"></i></b> Delete
					</button>
					<button type="button" class="btn bg-info btn-icon mr-2" onclick="downloadFiles()">
						<b><i class="icon-download"></i></b> Download
					</button>
					<button type="button" class="btn bg-secondary btn-icon mr-2" onclick="showModalEmail()">
						<b><i class="icon-envelop3"></i></b> Mail
					</button>
					<button type="button" class="btn bg-warning btn-icon mr-2" onclick="showModalChange()">
						<b><i class="icon-flip-vertical2"></i></b> Change Category
					</button>
					<span style="border-right:3px solid black;height:100%;" class="mr-2"></span>
					<button type="button" class="btn bg-success btn-icon mr-2" onclick="location.reload()">
						<b><i class="icon-sync"></i></b> Refresh
					</button>
					<button type="button" class="btn bg-primary btn-icon mr-2" data-toggle="modal" data-target="#modal_upload" onclick="setCategoryId()">
						<b><i class="icon-plus3"></i></b> Doc / Category
					</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<span class="breadcrumb-item active">Company's Legal Documents</span>
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h3 class="card-title">List of Company's Legal Documents</h3>
				<div class="header-elements">
					<form action="#" class="mr-2">
						<input type="text" class="form-control wmin-200" placeholder="Search..." onkeyup="find(this.value)">
					</form>
					<button type="button" class="btn bg-warning btn-icon mr-2" onclick="showHistory()">
						<b><i class="icon-history"></i></b> Mail History
					</button>
				</div>
			</div>
			<div class="card-body" id="data-files">
				<div class="d-lg-flex">
					<ul class="nav nav-tabs nav-tabs-vertical flex-column mr-lg-3 wmin-lg-300 mb-lg-0 border-bottom-0 list-content">
						<li class="nav-item">
							<a href="javascript:void(0);" class="nav-link" style="color: black !important;font-weight:900;">
								CATEGORY
							</a>
						</li>
						@foreach($category as $mainkey => $rowcategory)
						<li class="nav-item">
							<a href="#vertical-left-tab{{ $mainkey }}" class="nav-link {{ $mainkey == 0 ? 'active' : '' }} category-list" data-toggle="tab">{{ ($mainkey + 1).'. '.$rowcategory->name }}</a>
						</li>
						@endforeach
						@if(count($unmatch) > 0)
						<li class="nav-item">
							<a href="#vertical-left-tab-unmatch" class="nav-link category-list" data-toggle="tab">Empty Category</a>
						</li>	
						@endif
					</ul>
					<div class="tab-content flex-lg-fill list-content">
						@foreach($category as $mainkey => $rowcategory)
							<div class="tab-pane fade {{ $mainkey == 0 ? 'show active' : '' }} content-list" id="vertical-left-tab{{ $mainkey }}">
								<h3>{{ ($mainkey + 1).'. '.$rowcategory->name }}</h3>
								<div class="row">
									@foreach($rowcategory->companyLegality as $key => $row)
										<div class="col-sm-6 col-xl-3">
											<div class="card" data-id="{{ $row->id }}" data-name="{{ $row->file_name }}">
												<div class="card-img-actions mx-1 mt-1" onclick="chooseData(this)">
													<span class="badge badge-pill bg-danger ml-auto ml-md-0">{{ $key + 1 }}</span>
													
													@if($row->extension() == 'pdf')
														<canvas id="the-canvas{{ $row->id }}"></canvas>
													@else
														<div style="background:white;height:auto;">
															<img class="card-img img-fluid" src="{{ $row->attachment() }}" alt="{{ $row->file_name }}">
														</div>
													@endif
													
													<div class="card-img-actions-overlay card-img">
														<a href="{{ asset(Storage::url($row->storage_name)) }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
															<i class="icon-search4"></i>
														</a>
														<a href="{{ url('admin/legal_docs/company/download/'.$row->id) }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2" target="_blank">
															<i class="icon-download"></i>
														</a>
														<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2" onclick="deleteFile({{ $row->id }})">
															<i class="icon-bin"></i>
														</a>
													</div>
												</div>

												<div class="card-body" onclick="chooseData(this)">
													<div class="d-flex align-items-start flex-nowrap">
														<div>
															<div class="font-weight-semibold mr-2">{{ $row->file_name }}</div>
															<span class="font-size-sm text-muted">Size: {{ number_format(Storage::disk('local')->size($row->storage_name)/1024,0,',','.') }} Kb</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endforeach
									@if(count($rowcategory->companyLegality) == 0)
										<div class="row">
											<div class="col-md-12 mx-auto">
												<div class="alert alert-info alert-styled-left alert-dismissible">
													<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
													<span class="font-weight-semibold">Ups!</span> There is no legal documents found. You may upload using Add button.</b>.
												</div>
											</div>
										</div>
									@endif
								</div>
							</div>
						@endforeach
						@if(count($unmatch) > 0)
							<div class="tab-pane fade content-list" id="vertical-left-tab-unmatch">
								<div class="row">
									@foreach($unmatch as $key => $row)
										<div class="col-sm-6 col-xl-3">
											<div class="card" data-id="{{ $row->id }}" data-name="{{ $row->file_name }}">
												<div class="card-img-actions mx-1 mt-1" onclick="chooseData(this)">
													<span class="badge badge-pill bg-danger ml-auto ml-md-0">{{ $key + 1 }}</span>
													
													@if($row->extension() == 'pdf')
														<canvas id="the-canvas{{ $row->id }}"></canvas>
													@else
														<div style="background:white;height:auto;">
															<img class="card-img img-fluid" src="{{ $row->attachment() }}" alt="{{ $row->file_name }}">
														</div>
													@endif
													
													<div class="card-img-actions-overlay card-img">
														<a href="{{ asset(Storage::url($row->storage_name)) }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
															<i class="icon-search4"></i>
														</a>
														<a href="{{ url('admin/legal_docs/company/download/'.$row->id) }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2" target="_blank">
															<i class="icon-download"></i>
														</a>
														<a href="javascript:void(0);" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2" onclick="deleteFile({{ $row->id }})">
															<i class="icon-bin"></i>
														</a>
													</div>
												</div>

												<div class="card-body" onclick="chooseData(this)">
													<div class="d-flex align-items-start flex-nowrap">
														<div>
															<div class="font-weight-semibold mr-2">{{ $row->file_name }}</div>
															<span class="font-size-sm text-muted">Size: {{ number_format(Storage::disk('local')->size($row->storage_name)/1024,0,',','.') }} Kb</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						@endif
					</div>
				</div>
				<div class="d-none" id="search-content" style="min-height:400px;">
					
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
				<div class="d-lg-flex">
					<ul class="nav nav-tabs nav-tabs-vertical flex-column mr-lg-3 wmin-lg-200 mb-lg-0 border-bottom-0">
						<li class="nav-item"><a href="#tabdoc" class="nav-link active" data-toggle="tab"><i class="icon-images3 mr-2"></i> Doc</a></li>
						<li class="nav-item"><a href="#tabcategory" class="nav-link" data-toggle="tab"><i class="icon-stack3 mr-2"></i> Category</a></li>
					</ul>

					<div class="tab-content flex-lg-fill">
						<div class="tab-pane fade show active" id="tabdoc">
							<p>Max 5 files each upload. Refresh the page to add more.</p>
							<p>Accepted files are image & pdf.</p>
							<p>Please ensure your internet connection is stable.</p>
							<hr>
							<div class="row">
								<div class="form-group col-md-4">
									<label>Category :<sup class="text-danger">*</sup> <i>Choose category to group documents.</i></label>
									<select name="categori_id" id="categori_id" class="select2" onchange="setCategoryId()">
										@if(count($datacategory) > 0)
											@foreach($datacategory as $row)
												<option value="{{ $row->id }}">{{ $row->name }}</option>
											@endforeach
										@else
											<option value="">----Empty! Please add one----</option>
										@endif
									</select>
								</div>
							</div>
							
							<form action="{{ url('admin/legal_docs/company/add_files') }}" class="dropzone" id="dropzone_multiple">
								@csrf
							</form>
						</div>
						<div class="tab-pane fade" id="tabcategory">
							<form id="form_data_category">
							   <div class="alert alert-danger" id="validation_alert" style="display:none;">
								  <ul id="validation_content"></ul>
							   </div>
								<div class="row">
								  <div class="col-md-4">
									 <div class="form-group">
										<label>Name of Category :<sup class="text-danger">*</sup></label>
										<input type="text" name="category_name" id="category_name" class="form-control" placeholder="Enter category name">
									 </div>
								  </div>
								  <div class="col-md-4">
									<button type="button" class="btn bg-success mt-4" id="btn_create" onclick="createCategory()"><i class="icon-plus3"></i> Add</button>
								  </div>
								</div>
							</form>
							
							<div class="table-responsive mt-3">
								<table id="datatable_serverside" class="table table-bordered table-striped w-100">
									<thead class="bg-dark">
										<tr class="text-center">
											<th>#</th>
											<th>Name</th>
											<th>Action</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>				
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
	<div class="modal fade" id="modal_change" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Form Change Category</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<form id="form_change">
					<div class="alert alert-danger" id="validation_alert_change" style="display:none;">
						<ul id="validation_content_change"></ul>
					</div>
					<div class="row mt-3 justify-content-center">
						<div class="form-group col-md-4">
							<h3>File(s)</h3>
							<div id="list-attachment-change">
							
							</div>
						</div>
						<div class="form-group col-md-4">
							<label>To Category :<sup class="text-danger">*</sup></label>
							<select name="change_category_id" id="change_category_id" class="select2">
								@if(count($datacategory) > 0)
									@foreach($datacategory as $row)
										<option value="{{ $row->id }}">{{ $row->name }}</option>
									@endforeach
								@else
									<option value="">----Empty! Please add one----</option>
								@endif
							</select>
						</div>
					</div>
				</form>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-success" onclick="saveChange()"><i class="icon-plus2"></i> Save</button>
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
									<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Important!</span> While sending the emails please wait, it may take 1-3 minutes to send, it depends on your file's size.</div>
								</div>
								<div class="form-group">
									<label>To :<span class="text-danger">*</span></label>
									<input type="text" name="email" id="email" class="form-control" placeholder="Email recipient.">
								</div>
								<div class="form-group">
									<label>Subject :<span class="text-danger">*</span></label>
									<input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject here.">
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
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-success" onclick="sendQuotation()"><i class="icon-plus2"></i> Send</button>
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
				<form id="form_data_quotation">
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
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
				<button type="button" class="btn bg-success" onclick="sendQuotation()"><i class="icon-plus2"></i> Send</button>
			 </div>
		  </div>
	   </div>
	</div>
<script src="{{ url('template/back-office/pdfjs/build/pdf.js') }}"></script>
<script id="script">
	pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ url("template/back-office/pdfjs/build/pdf.worker.js") }}';
	@foreach($category as $rowcategory)
		@foreach($rowcategory->companyLegality as $key => $row)
			@if($row->extension() == 'pdf')
				var url = '{{ $row->attachment() }}';
				
				var loadingTask = pdfjsLib.getDocument(url);
				
				(async () => {
					var pdf = await loadingTask.promise;
					var page = await pdf.getPage(1);
					var scale = 0.3;
					var viewport = page.getViewport({ scale });
					var outputScale = window.devicePixelRatio || 1;

					var canvas = document.getElementById("the-canvas{{ $row->id }}");
					var context = canvas.getContext("2d");

					canvas.width = Math.floor(viewport.width * outputScale);
					canvas.height = Math.floor(viewport.height * outputScale);
					canvas.style.width = '100%';
					canvas.style.height = '100%';

					const transform = outputScale !== 1 
					  ? [outputScale, 0, 0, outputScale, 0, 0] 
					  : null;

					const renderContext = {
					  canvasContext: context,
					  transform,
					  viewport,
					};
					page.render(renderContext);
				})();
			@endif
		@endforeach
	@endforeach
	
	@foreach($unmatch as $key => $row)
		@if($row->extension() == 'pdf')
			var url = '{{ $row->attachment() }}';
			
			var loadingTask = pdfjsLib.getDocument(url);
			
			(async () => {
				var pdf = await loadingTask.promise;
				var page = await pdf.getPage(1);
				var scale = 0.3;
				var viewport = page.getViewport({ scale });
				var outputScale = window.devicePixelRatio || 1;

				var canvas = document.getElementById("the-canvas{{ $row->id }}");
				var context = canvas.getContext("2d");

				canvas.width = Math.floor(viewport.width * outputScale);
				canvas.height = Math.floor(viewport.height * outputScale);
				canvas.style.width = '100%';
				canvas.style.height = '100%';

				const transform = outputScale !== 1 
				  ? [outputScale, 0, 0, outputScale, 0, 0] 
				  : null;

				const renderContext = {
				  canvasContext: context,
				  transform,
				  viewport,
				};
				page.render(renderContext);
			})();
		@endif
	@endforeach
</script>
<script>

	var categori_id = 0;
	
	function setCategoryId(){
		categori_id = $('#categori_id').val();
	}
	
	function chooseData(element){
		if($(element).parent().hasClass('selected-card')){
			$(element).parent().removeClass('selected-card');
		}else{
			$(element).parent().addClass('selected-card');
		}
		
		countBulk();
	}
	
	function find(val){
		if(val == ''){
			$('.category-list').eq(0).trigger('click');
			$('#search-content').html('');
			$('#search-content').addClass('d-none');
			$('.list-content').removeClass('d-none');
		}else{
			$('.category-list').each(function( index ) {
				$(this).removeClass('active');
			});
			$('.content-list').each(function( index ) {
				$(this).removeClass('show');
				$(this).removeClass('active');
			});
			
			$('#search-content').removeClass('d-none');
			$('.list-content').addClass('d-none');
			
			$.ajax({
			 url: '{{ url("admin/legal_docs/company/find") }}',
			 type: 'POST',
			 data: { search : val },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				$('#search-content').empty();
				if(response.data !== ''){
					$('#search-content').html(response.data);
				}else{
					$('#search-content').append(`
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Ups!</span> There is no file(s) found.</b>.
						</div>
					`);
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
	}

	$(function() {
		ckEditor('content');
		
		$('#modal_upload').on('hidden.bs.modal', function (e) {
			location.reload();
		});
		
		loadDataTable();
	});
	
	function createCategory() {
		$.ajax({
		 url: '{{ url("admin/legal_docs/company/create_category") }}',
		 type: 'POST',
         dataType: 'JSON',
		 data: $('#form_data_category').serialize(),
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
			if(response.status == 200) {
				success();
				notif('success', 'bg-success', response.message);
				if($("#categori_id option[value='']").length > 0){
					$('#categori_id').empty();
				}
				if($("#change_categori_id option[value='']").length > 0){
					$('#change_categori_id').empty();
				}
				$('#categori_id,#change_categori_id').append(`
					<option value="` + response.data.id + `">` + response.data.name + `</option>
				`);
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
	
	function saveChange() {
		$.ajax({
		 url: '{{ url("admin/legal_docs/company/change_category") }}',
		 type: 'POST',
         dataType: 'JSON',
		 data: new FormData($('#form_change')[0]),
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			$('#validation_alert_change').hide();
			$('#validation_content_change').html('');
			loadingOpen('.modal-content');
		 },
		 success: function(response) {
			loadingClose('.modal-content');
			if(response.status == 200) {
				notif('success', 'bg-success', response.message);
				setTimeout(function() { 
					location.reload();
				}, 1500);
			} else if(response.status == 422) {
			   $('#validation_alert_change').show();
			   $('.modal-body').scrollTop(0);
			   notif('warning', 'bg-warning', 'Validation');
			   
			   $.each(response.error, function(i, val) {
				  $.each(val, function(i, val) {
					 $('#validation_content_change').append(`
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
	
	function countBulk(){
		$('#nominal_count').text($('.selected-card').length);
	}

	Dropzone.options.dropzoneMultiple = {
		paramName: "file",
		timeout: 180000,
		maxFilesize: 10,
		maxFiles: 50,
		acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf",
		init: function() {
			this.on("sending", function(file, xhr, formData){
				formData.append('id', categori_id);
			});
			this.on("success", function(file, responseText) {
				if(responseText.status == '422'){
					notif('error', 'bg-danger', responseText.message);
				}else if(responseText.status == '200'){
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
				 url: '{{ url("admin/legal_docs/company/delete_file") }}',
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
					 url: '{{ url("admin/legal_docs/company/delete_files") }}',
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
			 url: '{{ url("admin/legal_docs/company/download_files") }}',
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
	
	function showModalChange(){
		if($('.selected-card').length > 0){
			$('#modal_change').modal('toggle');
			
			$('#list-attachment-change').empty();
			
			$('#list-attachment-change').append(`
				<ol>
			`);
			
			$('.selected-card').each(function( index ) {
				$('#list-attachment-change').append(`
					<li><input type="hidden" name="file_id_change[]" value="`+ $(this).data('id') + `">` + $(this).data('name') + `</li>
				`);
			});
			
			$('#list-attachment-change').append(`
				</ol>
			`);
		}else{
			notif('warning', 'bg-warning', 'Please choose at least 1 file to change.');
		}
	}
	
	function showHistory(){
		$.ajax({
		 url: '{{ url("admin/legal_docs/company/get_history_email") }}',
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
	
	function success(){
		loadDataTable();
		$('#form_data_category').trigger('reset');
	}
	
	function loadDataTable() {
      return $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[0, 'asc']],
         ajax: {
            url: '{{ url("admin/legal_docs/company/datatable") }}',
            type: 'GET',
            data: {
				
            },
            beforeSend: function() {
               loadingOpen('#datatable_serverside');
            },
            complete: function() {
               loadingClose('#datatable_serverside');
            },
            error: function() {
               loadingClose('#datatable_serverside');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'id', searchable: false, className: 'text-center align-middle' },
            { name: 'name', className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ]
      }); 
	}
	
	function sendQuotation(){
		var formData = new FormData($('#form_data_quotation')[0]);
		formData.append("content",CKEDITOR.instances.content.getData());
		
		$.ajax({
			url: '{{ url("admin/legal_docs/company/send_mail") }}',
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
	
	function destroy(id) {
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
                  url: '{{ url("admin/legal_docs/company/destroy_category") }}',
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
						loadDataTable();
                        notif('success', 'bg-success', response.message);
                        notyConfirm.close();
						
						$('#categori_id').empty();
						$('#change_categori_id').empty();
						if(response.data.length > 0){
							$.each(response.data, function(i, val) {
								$('#categori_id,#change_categori_id').append(`
									<option value="` + val.id + `">` + val.name + `</option>
								`);
							});
						}else{
							$('#categori_id,#change_categori_id').append(`
								<option value="">----Empty! Please add one----</option>
							`);
						}
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