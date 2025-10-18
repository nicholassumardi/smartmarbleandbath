<body data-spy="scroll" data-target=".sidebar-component-right" class="navbar-top">
	<div class="navbar navbar-expand-md navbar-dark fixed-top">
		<div class="navbar-brand">
			<a href="{{ url('admin/dashboard') }}" class="d-inline-block">
				<img src="{{ asset('website/stempel_pta_baru_stroke_3.png') }}" alt="Logo" width="auto" style="height: 2.5rem !important;">
			</a>
		</div>
		<div class="d-md-none mt-2">
			<a class="navbar-toggler" type="button" href="{{ url('admin/my_attendance') }}">
				<i class="icon-qrcode"></i>
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="navbar-mobile">
			<span class="badge badge-success my-3 my-lg-0 ml-lg-3 d-none d-lg-block d-xl-block" style="margin-left:-75px !important;">ONLINE</span>
			
			<ul class="navbar-nav ml-lg-auto">
				@if(Request::segment(1) != 'cogs_calculator')
				<li class="nav-item {{ Request::segment(2) == 'price_list' ? 'active' : '' }}">
					<a href="{{ url('admin/price_list') }}" class="navbar-nav-link" data-popup="tooltip" title="Price List">
						Price List
					</a>
				</li>
				<li class="nav-item {{ Request::segment(2) == 'folder' ? 'active' : '' }}">
					<a href="{{ url('admin/folder') }}" class="navbar-nav-link" data-popup="tooltip" title="Folder">
						My Folder
					</a>
				</li>
				
				<li class="nav-item dropdown {{ Request::segment(2) == 'purchase_request' || Request::segment(2) == 'leave_request' ? 'active' : '' }}">
					<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Request</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="{{ url('admin/purchase_request') }}" class="dropdown-item {{ Request::segment(2) == 'purchase_request' ? 'active' : '' }}">Purchase</a>
						<a href="{{ url('admin/leave_request') }}" class="dropdown-item {{ Request::segment(2) == 'leave_request' ? 'active' : '' }}">Leave</a>
					</div>
				</li>

				<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle caret-0 chat-dropdown" data-toggle="dropdown">
						<i class="icon-bubbles4"></i>
						<span class="d-md-none ml-2">Chat</span>
						<span class="badge badge-pill bg-warning-400 ml-auto ml-md-0">0</span>
					</a>
					
					<div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
						<div class="dropdown-content-header">
							<span class="font-weight-semibold">Chat</span>
							<a href="javascript:void(0);" class="text-default"><i class="icon-compose"></i></a>
						</div>

						<div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">
								<li class="media show-chat-box">
									<div class="mr-3 position-relative">
										<img src="{{ url('website/empty.jpg') }}" width="36" height="36" class="rounded-circle" alt="">
									</div>

									<div class="media-body">
										<div class="media-title">
											<a href="#">
												<span class="font-weight-semibold">James Alexander</span>
												<span class="text-muted float-right font-size-sm">04:58</span>
											</a>
										</div>

										<span class="text-muted">Chat module. Coming soon...</span>
									</div>
								</li>
							</ul>
						</div>

						<div class="dropdown-content-footer justify-content-center p-0">
							<a href="#" class="bg-light text-grey w-100 py-2" data-popup="tooltip" title="Load more"><i class="icon-menu7 d-block top-0"></i></a>
						</div>
					</div>
				</li>
				<li class="nav-item nav-item-dropdown-lg dropdown {{ Request::segment(2) == 'all_activities' ? 'active' : '' }}">
					@php 
						$notify = App\Models\Notification::where('user_id', session('bo_id'))
							->whereDate('created_at', date('Y-m-d')); 
					@endphp
					<a href="#" class="navbar-nav-link" data-toggle="dropdown" data-popup="tooltip" title="All Activities">
						<i class="icon-bell2"></i>
						<span class="d-lg-none ml-3">All Activities</span>
						<span class="badge badge-warning badge-pill ml-auto ml-lg-0">{{ $notify->count() }}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-content wmin-lg-350">
						<div class="dropdown-content-header">
							<span class="font-weight-semibold">All Activities</span>
						</div>
						<div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">
								@if($notify->count() > 0)
									@foreach($notify->latest()->limit(4)->get() as $n)
										<li class="media">
											<div class="media-body">
												<div class="media-title">
													<a href="{{ $n->link }}" class="text-dark">
														<span class="font-weight-semibold">{{ $n->title }}</span>
														<span class="float-right font-size-sm">
															{{ date('d F Y, H:i', strtotime($n->created_at)) }}
														</span>
													</a>
												</div>
												<span class="text-muted">{!! $n->description !!}</span>
											</div>
										</li>
									@endforeach
								@else
									<li class="media">
										<div class="media-body">
											<div class="alert alert-warning text-center">Empty</div>
										</div>
									</li>
								@endif
							</ul>
						</div>
						<div class="dropdown-content-footer justify-content-center p-0 mt-0">
							<a href="{{ url('admin/all_activities') }}" class="btn btn-light text-primary font-weight-bold btn-block border-0 rounded-top-0">View All</a>
						</div>
					</div>
				</li>
				@if(session('bo_branch') == '1')
					@if(in_array(1, session('bo_role')) || in_array(5, session('bo_role')) || in_array(3, session('bo_role')) || in_array(4, session('bo_role')) || in_array(9, session('bo_role')) || in_array(10, session('bo_role')) || in_array(14, session('bo_role')))
					<li class="nav-item text-left {{ App\Models\Approval::where('user_id', session('bo_id'))->where('seen', 0)->count() > 0 ? 'blink-notification' : '' }}">
						<a href="{{ url('admin/approval') }}" class="navbar-nav-link" data-popup="tooltip" title="Approval">
							<i class="icon-shield-check"></i>
							<span class="d-lg-none ml-3">Approval</span>
							<span class="badge badge-warning badge-pill ml-auto ml-lg-0">
								{{ App\Models\Approval::where('user_id', session('bo_id'))->where('seen', 0)->count() }}
							</span>
						</a>
					</li>
					@endif
				@else
					<li class="nav-item text-left {{ App\Models\Approval::where('user_id', session('bo_id'))->where('seen', 0)->count() > 0 ? 'blink-notification' : '' }}">
						<a href="{{ url('admin/approval') }}" class="navbar-nav-link" data-popup="tooltip" title="Approval">
							<i class="icon-shield-check"></i>
							<span class="d-lg-none ml-3">Approval</span>
							<span class="badge badge-warning badge-pill ml-auto ml-lg-0">
								{{ App\Models\Approval::where('user_id', session('bo_id'))->where('seen', 0)->count() }}
							</span>
						</a>
					</li>
				@endif
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						<img src="{{ session('bo_photo') }}" class="rounded-circle mr-2" height="34" alt="{{ session('fo_name') }}">
						<span>{{ session('bo_name') }}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="{{ url('/') }}" class="dropdown-item" target="_blank">
							<i class="icon-home"></i> Main Page
						</a>
						<a href="{{ url('admin/profile') }}" class="dropdown-item">
							<i class="icon-user-plus"></i> Profile
						</a>
						<a href="{{ url('admin/my_attendance') }}" class="dropdown-item">
							<i class="icon-calendar2"></i> My Attendance
						</a>
						<a href="{{ url('admin/my_activity') }}" class="dropdown-item">
							<i class="icon-person"></i> My Activity
						</a>
						<div class="dropdown-divider"></div>
						<a href="{{ url('admin/logout') }}" class="dropdown-item">
							<i class="icon-switch2"></i> Logout
						</a>
					</div>
				</li>
				@endif
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			</ul>
		</div>
	</div>