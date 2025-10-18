<style>
	.owl-carousel {
		background-color: white !important;
	}
</style>
<section id="slider" class="slider-element h-auto" style="background-color: #222;">
	<div class="slider-inner">
		<div class="owl-carousel carousel-widget shadow-lg" data-margin="0" data-items="1" data-pagi="true" data-loop="true" data-animate-in="fadeIn" data-speed="450" data-animate-out="fadeOut" data-autoplay="10000">
			@foreach($banner as $key => $b)
				@if(explode('.',$b->image)[1] == 'mp4')
					<a href="javascript:void(0);" class="cursor-none">
						<video style="margin: 0 auto;display: block;" width="73%" height="100%" autoplay muted>
						  <source src="{{ $b->image() }}" type="video/mp4">
						</video>
					</a>	
				@else
					<a href="javascript:void(0);" class="cursor-none">
						<img src="{{ $b->image() }}" alt="{{ $key }}" style="max-height:800px;" class="img-fluid" alt="Banner Smart Marble">
					</a>
				@endif
			@endforeach
		</div>
	</div>
</section>
<section id="content">
	<div class="content-wrap">
		<div class="promo promotion promo-full promo-light promo-border p-5 header-stick bottommargin-lg">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-12 col-lg">
						<h3>Everyone, Everytime & Everywhere Personalize Your Home</h3>
						<small>
							<em>Buy products from various brands that you like now.</em>
						</small>
					</div>
					<div class="col-12 col-lg-auto mt-4 mt-lg-0">
						<a href="{{ url('product') }}" class="button button-large button-circle bg-teal m-0">Shop Now</a>
					</div>
				</div>
			</div>
		</div>
		<div class="container clearfix">
			<div class="heading-block center mb-0">
				<h4 class="text-uppercase">Shop By Brand</h4>
				<span>Find {{ $brand->count() }} brand from various products for your needs</span>
			</div>
			<div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="20" data-nav="false" data-pagi="true" data-items-xs="2" data-items-sm="3" data-items-md="4" data-items-lg="5" data-items-xl="5">
				@foreach($brand as $b)
					<div class="oc-item">
						<a href="{{ url('product?brand=' . $b->code) }}">
							<img src="{{ $b->image() }}" class="img-fluid" alt="{{ $b->name }}" alt="{{ $b->name }}">
						</a>
					</div>
				@endforeach
			</div>
			<div class="clearfix"></div>
			<div class="topmargin-lg row align-items-stretch gutter-30">
				<div class="col-md-12"><hr style="border: 1px solid #51b4ba; height:2px; background:#51b4ba;"></div>
				<div class="col-md-7">
					<div class="row gutter-30">
						<div class="col-4">
							<a href="https://drive.google.com/file/d/16bV5arT67mpgIwzyKjA2QnnLe2J_lCOW/view?usp=sharing" target="_blank" class="grid-inner d-block text-center">
								<img src="{{ asset('website/c_smartmarble.jpg') }}" style="max-width:200px;" class="banner-grid img-fluid" alt="Smart Marble">
							</a>
						</div>
						<div class="col-4">
							<a href="https://drive.google.com/file/d/14oM27LfTnXDhhAYOPGAgnapO2jzQ2c2d/view?usp=sharing" target="_blank" class="grid-inner d-block text-center">
								<img src="{{ asset('website/c_treeme_1.jpg') }}" style="background-position:center top; max-width:200px;" class="banner-grid img-fluid" alt="Treeme Italy">
							</a>
						</div>
						<div class="col-4">
							<a href="https://drive.google.com/file/d/1yuwi8KoH9NsNwgnZMv-jSN3jGFX1ZtP6/view?usp=sharing" target="_blank" class="grid-inner d-block text-center">
								<img src="{{ asset('website/c_bravat.jpg') }}" style="background-position:center top; max-width:200px;" class="banner-grid img-fluid" alt="Bravat">
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="text-right">
						<h3 class="font-weight-bold mb-0">
							<span class="font-weight-normal text-dark">We Have</span> Strong Portfolios
						</h3>
						<h3 class="font-weight-bold">
							<span class="font-weight-normal text-dark">From The</span> Greatest Brands
						</h3>
						<a href="{{ url('information/product_catalog') }}" class="button button-mini button-3d button-circle button-large bg-teal">More Catalogue</a>
						<div class="mt-3">
							<div class="circle-element bg-teal"></div>
							<div class="circle-element bg-danger"></div>
							<div class="circle-element bg-warning"></div>
						</div>
					</div>
				</div>
				<div class="col-md-12"><hr style="border: 1px solid #51b4ba; height:2px; background:#51b4ba;"></div>
			</div>
			<div class="clearfix"></div>
			<div class="topmargin-lg mb-4">
				<div class="card bg-light">
					<div class="card-body">
						<h4 class="text-uppercase mb-0 text-center">New Arrival</h4>
					</div>
				</div>
			</div>
			<div id="shop" class="shop row grid-container gutter-30">
				@foreach($product_new as $p)
					<div class="product col-lg-3 col-md-4 col-6">
						<div class="grid-inner border">
							<div class="product-image">
								<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}">
									<img src="{{ $p->type->image() }}" alt="{{ $p->name() }}" class="img-fluid product-thumbnail">
								</a>
								<div class="sale-flash badge {{ $p->availability()->color }} p-2">{{ $p->availability()->status }}</div>
							</div>
							<div class="product-desc p-3">
								<div class="product-title mb-0">
									<h4 class="limit-text-list-product-1 mb-0">
										<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}" class="font-wight-semibold text-dark" style="font-size:13.5px;">{{ $p->code() }}</a>
									</h4>
								</div>
								<hr class="mb-2 mt-2">
								<div class="product-title">
									<h4 class="mb-3 limit-text-list-product-2">
										<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}" class="font-weight-normal text-muted" style="font-size:12px;">{{ $p->name() }}</a>
									</h4>
								</div>
								<div class="product-price font-weight-bold">
									<ins>
										<h1 style="font-size:17px;" class="mb-0 text-danger font-weight-bold">Rp {{ number_format($p->price(), 0, ',', '.') }}</h1>
									</ins>
								</div>
                                <div class="product-rating">
                                    <span style="font-size:12px;">
                                        @foreach($p->review()->star as $r)
                                            {!! $r !!}
                                        @endforeach
                                    </span>
                                    <span style="font-size:12px; font-weight:600;" class="ml-2 float-right">{{ $p->review()->sold }} Sold</span>
                                </div>
							</div>
						</div>
					</div>
				@endforeach
				<div class="col-12">
					<div class="text-center mt-4">
						<a href="{{ url('product?sort=newest') }}" class="button button-3d button-circle bg-teal button-large">Discover More</a>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="topmargin-lg card bg-light">
				<div class="card-body">
					<div class="heading-block center mb-4">
						<h4 class="text-uppercase">Shop By Category</h4>
					</div>
					<nav class="tabable mb-3">
						<div class="nav nav-tabs nav-justified bg-warning" id="nav-tab" role="tablist">
							@foreach($category as $key => $c)
								<a class="nav-link font-size-13 font-weight-semibold {{ $key == 0 ? 'active' : '' }}" id="nav-category_{{ $key }}-tab" data-toggle="tab" href="#nav-category_{{ $key }}" role="tab" aria-controls="nav-category_{{ $key }}" aria-selected="{{ $key == 0 ? 'true' : 'false' }}">{{ $c->name }}</a>
							@endforeach
						</div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
						@foreach($category as $key => $c)
							<div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="nav-category_{{ $key }}" role="tabpanel" aria-labelledby="nav-category_{{ $key }}-tab">
								<div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="0" data-nav="true" data-pagi="true" data-items-xs="2" data-items-sm="2" data-items-md="3" data-items-lg="4" data-items-xl="4">
									@foreach($c->product() as $p)
										<div class="oc-item p-2">
											<div class="product">
												<div class="grid-inner border">
													<div class="product-image">
														<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}">
															<img src="{{ $p->type->image() }}" alt="{{ $p->name() }}" class="img-fluid product-thumbnail">
														</a>
														<div class="sale-flash badge {{ $p->availability()->color }} p-2">{{ $p->availability()->status }}</div>
													</div>
													<div class="product-desc bg-light p-3">
														<div class="product-title mb-0">
															<h4 class="limit-text-list-product-1 mb-0">
																<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}" class="font-wight-semibold text-dark" style="font-size:13.5px;">{{ $p->code() }}</a>
															</h4>
														</div>
														<hr class="mb-2 mt-2">
														<div class="product-title">
															<h4 class="mb-3 limit-text-list-product-2">
																<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}" class="font-weight-normal text-muted" style="font-size:12px;">{{ $p->name() }}</a>
															</h4>
														</div>
														<div class="product-price font-weight-bold">
															<ins>
																<h1 style="font-size:17px;" class="mb-0 text-danger font-weight-bold">Rp {{ number_format($p->price(), 0, ',', '.') }}</h1>
															</ins>
														</div>
                                                        <div class="product-rating">
                                                            <span style="font-size:12px;">
                                                                @foreach($p->review()->star as $r)
                                                                    {!! $r !!}
                                                                @endforeach
                                                            </span>
                                                            <span style="font-size:12px; font-weight:600;" class="ml-2 float-right">{{ $p->review()->sold }} Sold</span>
                                                        </div>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="topmargin-lg mb-4">
				<div class="card bg-light">
					<div class="card-body">
						<h4 class="text-uppercase mb-0 text-center">Best Seller</h4>
					</div>
				</div>
			</div>
			<div id="shop" class="shop row grid-container gutter-30">
				@foreach($product_cheapest as $p)
					<div class="product col-lg-3 col-md-4 col-6">
						<div class="grid-inner border">
							<div class="product-image">
								<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}">
									<img src="{{ $p->type->image() }}" alt="{{ $p->name() }}" class="img-fluid product-thumbnail">
								</a>
								<div class="sale-flash badge {{ $p->availability()->color }} p-2">{{ $p->availability()->status }}</div>
							</div>
							<div class="product-desc p-3">
								<div class="product-title mb-0">
									<h4 class="limit-text-list-product-1 mb-0">
										<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}" class="font-wight-semibold text-dark" style="font-size:13.5px;">{{ $p->code() }}</a>
									</h4>
								</div>
								<hr class="mb-2 mt-2">
								<div class="product-title">
									<h4 class="mb-3 limit-text-list-product-2">
										<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}" class="font-weight-normal text-muted" style="font-size:12px;">{{ $p->name() }}</a>
									</h4>
								</div>
								<div class="product-price font-weight-bold">
									<ins>
										<h1 style="font-size:17px;" class="mb-0 text-danger font-weight-bold">Rp {{ number_format($p->price(), 0, ',', '.') }}</h1>
									</ins>
								</div>
                                <div class="product-rating">
                                    <span style="font-size:12px;">
                                        @foreach($p->review()->star as $r)
                                            {!! $r !!}
                                        @endforeach
                                    </span>
                                    <span style="font-size:12px; font-weight:600;" class="ml-2 float-right">{{ $p->review()->sold }} Sold</span>
                                </div>
							</div>
						</div>
					</div>
				@endforeach
				<div class="col-12">
					<div class="text-center mt-4">
						<a href="{{ url('product?sort=low_to_high') }}" class="button button-3d button-circle bg-teal button-large">Discover More</a>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="topmargin-lg mb-4">
				<div class="card bg-light">
					<div class="card-body">
						<h4 class="text-uppercase mb-0 text-center">Specials Deals</h4>
					</div>
				</div>
			</div>
			<div id="shop" class="shop row grid-container gutter-30">
				@foreach($product_limited as $p)
					<div class="product col-lg-3 col-md-4 col-6">
						<div class="grid-inner border">
							<div class="product-image">
								<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}">
									<img src="{{ $p->type->image().'?v=0' }}" alt="{{ $p->name() }}" class="img-fluid product-thumbnail">
								</a>
								<div class="sale-flash badge {{ $p->availability()->color }} p-2">{{ $p->availability()->status }}</div>
							</div>
							<div class="product-desc p-3">
								<div class="product-title mb-0">
									<h4 class="limit-text-list-product-1 mb-0">
										<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}" class="font-wight-semibold text-dark" style="font-size:13.5px;">{{ $p->code() }}</a>
									</h4>
								</div>
								<hr class="mb-2 mt-2">
								<div class="product-title">
									<h4 class="mb-3 limit-text-list-product-2">
										<a href="{{ url('product/detail/' . Str::slug($p->name()) . '?q=' . base64_encode($p->id)) }}" class="font-weight-normal text-muted" style="font-size:12px;">{{ $p->name() }}</a>
									</h4>
								</div>
								<div class="product-price font-weight-bold">
									<ins>
										<h1 style="font-size:17px;" class="mb-0 text-danger font-weight-bold">Rp {{ number_format($p->price(), 0, ',', '.') }}</h1>
									</ins>
								</div>
                                <div class="product-rating">
                                    <span style="font-size:12px;">
                                        @foreach($p->review()->star as $r)
                                            {!! $r !!}
                                        @endforeach
                                    </span>
                                    <span style="font-size:12px; font-weight:600;" class="ml-2 float-right">{{ $p->review()->sold }} Sold</span>
                                </div>
							</div>
						</div>
					</div>
				@endforeach
				<div class="col-12">
					<div class="text-center mt-4">
						<a href="{{ url('product?stock=limited') }}" class="button button-3d button-circle bg-teal button-large">Discover More</a>
					</div>
				</div>
			</div>
		</div>
		<div class="section dark bg-light footer-stick" style="background-color: #444; padding: 35px 0;">
			<div class="container">
				<div class="row col-mb-30 justify-content-center text-center">
					<div class="col-lg-4 col-md-4 col-12">
						<div class="text-center">
						</div>
						<i class="i-large text-center icon-thumbs-up2 text-dark mb-0" style="font-size:30px;"></i>
						<div class="clear"></div>
						<div class="counter counter-small text-dark mb-2" style="font-family:'Lato', sans-serif; font-size: 18px;">
							100% Original
						</div>
						<h5 style="font-size:10px;" class="text-dark">We guarantee you the sale of Original Brands</h5>
					</div>
					<div class="col-lg-4 col-md-4 col-12">
						<div class="text-center">
						</div>
						<i class="i-large text-center icon-line2-home text-dark mb-0" style="font-size:30px;"></i>
						<div class="clear"></div>
						<div class="counter counter-small text-dark mb-2" style="font-family:'Lato', sans-serif; font-size: 18px;">
							Warranty
						</div>
						<h5 style="font-size:10px;" class="text-dark">3 years for Sanitary ware & 2 years for tiles</h5>
					</div>
					<div class="col-lg-4 col-md-4 col-12">
						<div class="text-center">
						</div>
						<i class="i-large text-center icon-user-tie text-dark mb-0" style="font-size:30px;"></i>
						<div class="clear"></div>
						<div class="counter counter-small text-dark mb-2" style="font-family:'Lato', sans-serif; font-size: 18px;">
							Pre & Post Sales Services
						</div>
						<h5 style="font-size:10px;" class="text-dark">Design Consultancy, Installation, Supervision, Maintenance</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>