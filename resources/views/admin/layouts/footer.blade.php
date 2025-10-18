@php
	use App\Models\Version;
	$data = Version::where('released_date','<=',date('Y-m-d'))->orderByDesc('version')->first();
	if($data){
		$version = $data->version;
	}else{
		$version = 'Empty';
	}
@endphp
			<div class="chat-box" style="display:none;">
				<div class="card chat-body">
					<div class="card-header header-elements-inline" style="background-color: white !important;">
						<h5 class="card-title">Chat Box <i class="icon-users"></i> TRIAL</h5>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="reload"></a>
		                		<a class="list-icons-item" id="remove-chat-box" href="javascript:void(0);"><i class="icon-cross2"></i></a>
		                	</div>
	                	</div>
					</div>

					<div class="card-body chat-main" style="overflow:auto;max-height:100%;background-color: white !important;">
						<ul class="media-list media-chat media-chat-scrollable mb-3">
							<li class="media content-divider justify-content-center text-muted mx-0">
								<span class="px-2">Monday, Feb 10</span>
							</li>

							<li class="media">
								<div class="mr-3">
									<a href="{{ url('website/empty.jpg') }}">
										<img src="{{ url('website/empty.jpg') }}" class="rounded-circle" width="40" height="40" alt="">
									</a>
								</div>

								<div class="media-body">
									<div class="media-chat-item">aaa.</div>
									<div class="font-size-sm text-muted mt-2">Tue, 10:28 am</div>
								</div>
							</li>
							
							<li class="media content-divider justify-content-center text-muted mx-0">
								<span class="px-2">Monday, Feb 10</span>
							</li>
							
							<li class="media">
								<div class="mr-3">
									<a href="{{ url('website/empty.jpg') }}">
										<img src="{{ url('website/empty.jpg') }}" class="rounded-circle" width="40" height="40" alt="">
									</a>
								</div>

								<div class="media-body">
									<div class="media-chat-item">aaa.</div>
									<div class="font-size-sm text-muted mt-2">Tue, 10:28 am</div>
								</div>
							</li>
							
							<li class="media content-divider justify-content-center text-muted mx-0">
								<span class="px-2">Monday, Feb 10</span>
							</li>
							
							<li class="media">
								<div class="mr-3">
									<a href="{{ url('website/empty.jpg') }}">
										<img src="{{ url('website/empty.jpg') }}" class="rounded-circle" width="40" height="40" alt="">
									</a>
								</div>

								<div class="media-body">
									<div class="media-chat-item">aaa.</div>
									<div class="font-size-sm text-muted mt-2">Tue, 10:28 am</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="card-footer">
						<textarea name="enter-message" class="form-control mb-3" rows="1" cols="1" placeholder="Enter your message..."></textarea>

                    	<div class="d-flex align-items-center">
                    		<div class="list-icons list-icons-extended">
                                <a href="#" class="list-icons-item" data-popup="tooltip" data-container="body" title="Send file"><i class="icon-file-plus"></i></a>
                    		</div>

                    		<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b> Send</button>
                    	</div>
					</div>
				</div>
			</div>
			
			<div class="modal fade" id="modal_version" data-backdrop="static" role="dialog">
			   <div class="modal-dialog modal-lg">
				  <div class="modal-content">
					 <div class="modal-header bg-light">
						<h5 class="modal-title" id="exampleModalLabel">Version <span id="data_version_title"></span></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						   <span aria-hidden="true">&times;</span>
						</button>
					 </div>
					 <div class="modal-body" id="data_version_content">
						
					 </div>
				  </div>
			   </div>
			</div>
			
			<div class="navbar navbar-expand-lg navbar-light" style="top:-20px;">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar-footer" style="display:block !important;">
					<div class="row">
						<div class="col-md-4">
							<img src="{{ url("website/logo_smart_work_sm.png") }}" width="65px">
							&nbsp; <span style="bottom:-4px !important;position:absolute !important;"><a href="javascript:void(0);" onclick="showVersion();">V.{{ $version }}</a></span>
						</div>
						<div class="col-md-4 text-center my-auto">
							Copyright &copy; {{ date('Y') }}
						</div>
						<div class="col-md-4 text-right">
							<div style="display:inline-block;">
								<img src="{{ url('website/logo_hiro.png') }}" width="30px">
							</div>
							<div style="display:inline-block;vertical-align:bottom;">
								&nbsp; <span style="bottom:0px !important;">HiroSolution</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
