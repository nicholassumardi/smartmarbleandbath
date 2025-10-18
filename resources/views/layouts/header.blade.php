@php 
	$total_cart     = App\Models\Cart::where('customer_id', session('fo_id'))->count(); 
	$total_wishlist = App\Models\Wishlist::where('customer_id', session('fo_id'))->count(); 
	$total_points   = App\Models\Customer::find(session('fo_id'));
@endphp
<body class="stretched">
	<div id="img-loader">
		<img src="{{ url('website/loader.gif?v=0') }}" height="auto" width="100%">
	</div>
	<nav class="p-0 navbar fixed-bottom navbar-dark navbar-expand navigation-menu d-md-block d-lg-none d-xl-none">
		<ul class="navbar-nav nav-justified w-100">
			<li class="nav-item">
				<a href="{{ url('/') }}" class="nav-link {{ !Request::segment(1) ? 'active' : '' }}">
					<i class="icon-home"></i>
					<span class="small d-block">Home</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="{{ url('account/cart') }}" class="nav-link {{ Request::segment(2) == 'cart' ? 'active' : '' }}">
					<i class="icon-line-bag"></i>
					<sup class="sup-top">
						<span class="badge badge-custom badge-warning">{{ $total_cart }}</span>
					</sup>
					<span class="small d-block">Cart</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="https://tawk.to/chat/60881c7062662a09efc29df5/1f49s1kk2" class="nav-link" target="_blank">
					<i class="icon-headphones-alt"></i>
					<span class="small d-block">Live Chat</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="{{ url('account/wishlist') }}" class="nav-link {{ Request::segment(2) == 'wishlist' ? 'active' : '' }}">
					<i class="icon-line-heart"></i>
					<sup class="sup-top">
						<span class="badge badge-custom badge-warning">{{ $total_wishlist }}</span>
					</sup>
					<span class="small d-block">Wishlist</span>
				</a>
			</li>
			<li class="nav-item">
				@if(session('fo_id'))
					<a href="javascript:void(0);" class="nav-link side-panel-trigger">
						<img src="{{ session('fo_photo') }}" class="img-circle" style="margin-bottom:1px;" height="16" alt="{{ session('fo_name') }}">
						<span class="small d-block">
							@php $str = explode(' ', session('fo_name')); @endphp
							Hi, {{ $str[0] }}
						</span>
					</a>
				@else
					<a href="{{ url('account/login') }}" class="nav-link">
						<i class="icon-user21"></i>
						<span class="small d-block">Account</span>
					</a>
				@endif
			</li>
		</ul>
	</nav>
	<div class="body-overlay"></div>
	<div id="side-panel" class="dark">
		<div id="side-panel-trigger-close" class="side-panel-trigger">
			<a href="#"><i class="icon-line-cross"></i></a>
		</div>
		<div class="side-panel-wrap">
			<div class="widget clearfix">
				<center>
					<img src="{{ session('fo_photo') }}" class="mr-1 position-relative img-circle mb-3" style="max-width:80px;" alt="{{ session('fo_name') }}">
				</center>
				<h4 class="text-center mb-2">
					@php $str = explode(' ', session('fo_name')); @endphp
					Hi, {{ $str[0] }}
				</h4>
				<p class="text-center">
					<small style="font-size-14">
						<i class="icon-coins text-warning"></i>
						&nbsp; 
						{{ $total_points ? number_format($total_points->points, 0, ',', '.') : 0 }}
					</small>
				</p>
				<div class="form-group"><hr></div>
				<nav class="nav-tree">
					<ul>
						<li>
							<a href="{{ url('account/profile') }}"><i class="icon-user-circle1"></i> Edit Profile</a>
						</li>
						<li>
							<a href="{{ url('account/history_order') }}"><i class="icon-line2-notebook"></i> History Order</a>
						</li>
						<li>
							<a href="{{ url('account/voucher') }}"><i class="icon-ticket"></i> My Voucher</sup></a>
						</li>
						<li>
							<a href="{{ url('account/points') }}"><i class="icon-coins"></i> Point</sup></a>
						</li>
					</ul>
				</nav>
			</div>
			<div class="form-group"><hr></div>
			<div class="form-group">
				<div class="text-center">
					<a href="{{ url('account/logout') }}" class="button button-red col-12">Logout</a>
				</div>	
			</div>	
		</div>
	</div>
	<div id="wrapper" class="clearfix">
		<div id="top-bar" class="p-0 m-0 bg-light shadow-lg">
			<div class="container">
				<div class="row justify-content-between align-items-center">
					<div class="col-12 col-md-auto d-none d-lg-block">
						<div class="top-links on-click">
							<ul class="top-links-container">
								<li class="top-links-item">
									<a href="tel:0315477501" style="text-transform:none;" class="font-weight-normal font-size-12">
										<i class="icon-line-phone"></i> Hotline +62 811-332-642
									</a>
								</li>
								<li class="top-links-item">
									<a href="https://wa.me/62811332642" target="_blank" style="text-transform:none;" class="font-weight-normal font-size-12">
										<i class="icon-whatsapp"></i> +62 811-332-642
									</a>
								</li>
								<li class="top-links-item">
									<a href="mailto:info@smartmarbleandbath.com" style="text-transform:none;" class="font-weight-normal font-size-13">
										<i class="icon-line2-envelope"></i> info@smartmarbleandbath.com
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-12 col-md-auto">
						<p class="mb-0 py-2 text-center text-md-left">
							<a href="https://www.google.co.id/maps/place/smartmarbleandbath/@-7.2518882,112.7338775,17z/data=!3m1!4b1!4m5!3m4!1s0x2dd7f9699d672725:0x38ff0a5b6a1722a4!8m2!3d-7.2518978!4d112.736106" target="_blank" class="font-size-13 text-dark" style="text-transform:none;">
								<marquee width="80%" behavior="scroll" direction="left" height="36%">
									Open Every Monday - Saturday, 08.30 - 17.00 WIB
								</marquee>
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>
		<header id="header" class="full-header header-size-sm" data-sticky-shrink="false">
			<div id="header-wrap">
				<div class="container">
					<div class="header-row justify-content-lg-between bg-white">
						<div id="logo">
							<a href="{{ url('/') }}" class="standard-logo">
								<img src="{{ asset('website/logo-black2.png?v=0') }}" class="img-fluid" alt="Logo">
							</a>
							<a href="{{ url('/') }}" class="retina-logo">
								<img src="{{ asset('website/logo-black2.png?v=0') }}" class="img-fluid" alt="Logo">
							</a>
						</div>
						<div class="header-misc">
							<div id="top-account" class="d-none d-xl-block">
								@if(session('fo_id'))
									<a href="javascript:void(0);" class="side-panel-trigger text-dark">
										<img src="{{ session('fo_photo') }}" class="mr-1 position-relative img-circle" style="margin-bottom:1px;" height="20" alt="{{ session('fo_name') }}">
										<span class="d-none d-sm-inline-block font-primary font-weight-medium text-dark">
											@php $str = explode(' ', session('fo_name')); @endphp
											Hi, {{ $str[0] }}
										</span>
									</a>
								@else
									<a href="{{ url('account/login') }}">
										<i class="icon-line2-user mr-1 position-relative text-teal" style="top: 1px;"></i>
										<span class="d-none d-xl-inline-block font-primary font-weight-medium text-dark">Login / Sign Up</span>
									</a>
								@endif
							</div>
							<div id="top-search" class="header-misc-icon">
								<a href="#" id="top-search-trigger">
									<i class="icon-line-search text-teal"></i>
									<i class="icon-line-cross text-teal"></i>
								</a>
							</div>
							<div id="top-search" class="header-misc-icon d-none d-xl-block">
								<a href="{{ url('account/wishlist') }}" id="top-search-trigger">
									<i class="icon-heart21 text-teal"></i>
									<span class="top-cart-number">{{ $total_wishlist }}</span>
								</a>
							</div>
							<div id="top-search" class="header-misc-icon d-none d-xl-block">
								<a href="{{ url('account/cart') }}" id="top-search-trigger">
									<i class="icon-line-bag text-teal"></i>
									<span class="top-cart-number">{{ $total_cart }}</span>
								</a>
							</div>
						</div>
						<div id="primary-menu-trigger">
							<i class="icon-list text-teal"></i>
						</div>
						<nav class="primary-menu with-arrows mr-lg-auto">
							<ul class="menu-container">
								<li class="menu-item">
									<a class="menu-link" href="{{ url('/') }}">
										<div class="font-size-header-top">Home</div>
									</a>
								</li>
								<li class="menu-item">
									<a class="menu-link" href="{{ url('product') }}">
										<div class="font-size-header-top">Product</div>
									</a>
								</li>
								<li class="menu-item">
									<a class="menu-link" href="{{ url('information/how_to_buy') }}">
										<div class="font-size-header-top">How To Buy</div>
									</a>
								</li>
								<li class="menu-item">
									<a class="menu-link" href="{{ url('information/about_us') }}">
										<div class="font-size-header-top">About Us</div>
									</a>
								</li>
								<li class="menu-item">
									<a class="menu-link" href="{{ url('information/contact') }}">
										<div class="font-size-header-top">Contact Us</div>
									</a>
								</li>
								<li class="menu-item">
									<a class="menu-link" href="{{ url('information/tracking') }}">
										<div class="font-size-header-top">Tracking</div>
									</a>
								</li>
							</ul>
						</nav>
						<form class="top-search-form" action="{{ url('product') }}" method="GET">
							<input type="text" name="search" class="form-control" placeholder="Search ..." autocomplete="off">
						</form>
					</div>
				</div>
			</div>
			<div class="header-wrap-clone"></div>
		</header>