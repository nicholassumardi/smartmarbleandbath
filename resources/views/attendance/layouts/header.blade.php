<body data-spy="scroll" data-target=".sidebar-component-right" class="navbar-top">
	<div class="navbar navbar-expand-md navbar-dark fixed-top">
		<div class="navbar-brand">
			<a href="{{ url('attendance/code') }}" class="d-inline-block">
				<img src="{{ asset('website/stempel_pta_baru_stroke_3.png') }}" alt="Logo" width="auto" style="height: 2.5rem !important;">
			</a>
		</div>
		<div class="d-md-none mt-2">
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
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						<img src="{{ url('website/user.png') }}" class="rounded-circle mr-2" height="34" alt="...">
						<span>QR Code Generator</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="{{ url('attendance/logout') }}" class="dropdown-item">
							<i class="icon-switch2"></i> Logout
						</a>
					</div>
				</li>
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			</ul>
		</div>
	</div>