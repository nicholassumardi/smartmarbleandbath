<section id="content">
	<div class="content-wrap p-0 mt-4">
		<div class="container clearfix">
			<form method="GET" class="mt-0" action="{{ url('product') }}">
				<div id="side-panel" class="dark side-panel-filter d-xl-none d-xl-block">
					<div class="side-panel-wrap">
						<div class="widget clearfix">
							<h4 class="mb-4">Filter</h4>
							<div class="sidebar">
								<div class="sidebar-widgets-wrap">
									<div class="clearfix">
										<input type="text" class="form-control no-outline font-size-12" name="search" id="search" value="{{ $filter['other']['search'] ? $filter['other']['search'] : '' }}" placeholder="Search ...">
									</div>
									<div class="form-group"><hr></div>
									<div class="toggle toggle-border">
										<div class="toggle-header">
											<div class="toggle-icon">
												<i class="toggle-closed icon-line-plus"></i>
												<i class="toggle-open icon-line-minus"></i>
											</div>
											<div class="toggle-title">
												<h4 class="mb-0 text-uppercase" style="font-size:13px;">Brand</h4>
											</div>
										</div>
										<div class="toggle-content">
											<ul class="sidebar-filter-product mb-0">
												@foreach($brand as $b)
													<li>
														<div class="form-check">
															<input type="checkbox" class="form-check-input" name="brand[]" id="panel-{{ $b->code }}" value="{{ $b->code }}" {{ in_array($b->code, $filter['brand']) ? 'checked' : '' }}>
															<label class="form-check-label font-weight-normal text-white font-size-11" style="font-size:10px !important; color:white !important;" for="panel-{{ $b->code }}">{{ $b->name }}</label>
														</div>
													</li>
												@endforeach
											</ul>
										</div>
									</div>
									@foreach($category as $c)
										<div class="toggle toggle-border">
											<div class="toggle-header">
												<div class="toggle-icon">
													<i class="toggle-closed icon-line-plus"></i>
													<i class="toggle-open icon-line-minus"></i>
												</div>
												<div class="toggle-title">
													<h4 class="mb-0 text-uppercase" style="font-size:13px;">{{ $c->name }}</h4>
												</div>
											</div>
											<div class="toggle-content">
												<ul class="sidebar-filter-product mb-0">
													@foreach($c->sub() as $s)
														@if($s->sub()->count() > 0)
															@foreach($s->sub() as $val)
																<li>
																	<div class="form-check">
																		<input type="checkbox" class="form-check-input" name="category[]" id="{{ $val->slug }}" value="{{ $val->slug }}" onchange="clickFilter(this)" {{ in_array($val->slug, $filter['category']) ? 'checked' : '' }}>
																		<label class="form-check-label font-weight-normal font-size-11" for="{{ $val->slug }}">{{ $val->name }}</label>
																	</div>
																</li>
															@endforeach
														@else
															<li>
																<div class="form-check">
																	<input type="checkbox" class="form-check-input" name="category[]" id="{{ $s->slug }}" value="{{ $s->slug }}" onchange="clickFilter(this)" {{ in_array($s->slug, $filter['category']) ? 'checked' : '' }}>
																	<label class="form-check-label font-weight-normal font-size-11" for="{{ $s->slug }}">{{ $s->name }}</label>
																</div>
															</li>
														@endif
													@endforeach
												</ul>
											</div>
										</div>
									@endforeach
									<div class="toggle toggle-border">
										<div class="toggle-header">
											<div class="toggle-icon">
												<i class="toggle-closed icon-line-plus"></i>
												<i class="toggle-open icon-line-minus"></i>
											</div>
											<div class="toggle-title">
												<h4 class="mb-0 text-uppercase" style="font-size:13px;">Size</h4>
											</div>
										</div>
										<div class="toggle-content">
											<ul class="sidebar-filter-product mb-0">
												@foreach($size as $s)
													<li>
														<div class="form-check">
															<input type="checkbox" class="form-check-input" name="size[]" id="panel-{{ $s->length }}x{{ $s->width }}" value="{{ $s->length }}x{{ $s->width }}" {{ in_array($s->length . 'x' . $s->width, $filter['size']) ? 'checked' : '' }}>
															<label class="form-check-label font-weight-normal text-white font-size-11" style="font-size:10px !important; color:white !important;" for="panel-{{ $s->length }}x{{ $s->width }}">{{ $s->length }}x{{ $s->width }}</label>
														</div>
													</li>
												@endforeach
											</ul>
										</div>
									</div>
									<div class="toggle toggle-border">
										<div class="toggle-header">
											<div class="toggle-icon">
												<i class="toggle-closed icon-line-plus"></i>
												<i class="toggle-open icon-line-minus"></i>
											</div>
											<div class="toggle-title">
												<h4 class="mb-0 text-uppercase" style="font-size:13px;">Color</h4>
											</div>
										</div>
										<div class="toggle-content">
											<ul class="sidebar-filter-product mb-0">
												@foreach($color as $c)
													<li>
														<div class="form-check">
															<input type="checkbox" class="form-check-input" name="color[]" id="panel-{{ $c->code }}" value="{{ $c->code }}" {{ in_array($c->code, $filter['color']) ? 'checked' : '' }}>
															<label class="form-check-label font-weight-normal text-white font-size-11" style="font-size:10px !important; color:white !important;" for="panel-{{ $c->code }}">{{ $c->name }}</label>
														</div>
													</li>
												@endforeach
											</ul>
										</div>
									</div>
									<div class="toggle toggle-border">
										<div class="toggle-header">
											<div class="toggle-icon">
												<i class="toggle-closed icon-line-plus"></i>
												<i class="toggle-open icon-line-minus"></i>
											</div>
											<div class="toggle-title">
												<h4 class="mb-0 text-uppercase" style="font-size:13px;">Pattern</h4>
											</div>
										</div>
										<div class="toggle-content">
											<ul class="sidebar-filter-product mb-0">
												@foreach($pattern as $p)
													<li>
														<div class="form-check">
															<input type="checkbox" class="form-check-input" name="pattern[]" id="panel-{{ $p->code }}" value="{{ $p->code }}" {{ in_array($p->code, $filter['pattern']) ? 'checked' : '' }}>
															<label class="form-check-label font-weight-normal text-white font-size-11" style="font-size:10px !important; color:white !important;" for="panel-{{ $p->code }}">{{ $p->name }}</label>
														</div>
													</li>
												@endforeach
											</ul>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" class="button button-3d button-mini bg-teal col-12 font-size-11">Filter</button>
										<a href="{{ url('product') }}" class="button button-3d button-mini button-red text-center col-12 font-size-11">Reset</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row gutter-50 justify-content-center col-mb-80">
					<div class="postcontent col-lg-12 order-lg-last">
						<div class="row mb-3">
							<div class="col-md-3 d-none d-xl-block">
								<div class="form-group row no-gutters">
									<label class="col-3 col-form-label font-size-13" style="text-transform: capitalize;">Show</label>
									<div class="col-9">
										<select name="show" id="show" class="custom-select no-outline font-size-13" style="border-radius:0;" onchange="clickFilter(this)">
											<option value="">24</option>
											<option value="48" {{ $filter['other']['show'] == 48 ? 'selected' : '' }}>48</option>
											<option value="60" {{ $filter['other']['show'] == 60 ? 'selected' : '' }}>60</option>
											<option value="100" {{ $filter['other']['show'] == 100 ? 'selected' : '' }}>100</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-3 d-none d-xl-block">
								<div class="form-group row no-gutters">
									<label class="col-3 col-form-label font-size-13" style="text-transform: capitalize;">Stock</label>
									<div class="col-9">
										<select name="stock" id="stock" class="custom-select no-outline font-size-13" style="border-radius:0;" onchange="clickFilter(this)">
											<option value="">All</option>
											<option value="ready" {{ $filter['other']['stock'] == 'ready' ? 'selected' : '' }}>Ready</option>
											<option value="limited" {{ $filter['other']['stock'] == 'limited' ? 'selected' : '' }}>Limited</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-3 d-none d-xl-block">
								<div class="form-group row no-gutters">
									<label class="col-3 col-form-label font-size-13" style="text-transform: capitalize;">Sort</label>
									<div class="col-9">
										<select name="sort" id="sort" class="custom-select no-outline font-size-13" style="border-radius:0;" onchange="clickFilter(this)">
											<option value="">Alphabet (A-Z)</option>
											<option value="latest" {{ $filter['other']['sort'] == 'latest' ? 'selected' : '' }}>Latest</option>
											<option value="low_to_high" {{ $filter['other']['sort'] == 'low_to_high' ? 'selected' : '' }}>Lowest Price</option>
											<option value="high_to_low" {{ $filter['other']['sort'] == 'high_to_low' ? 'selected' : '' }}>Highest Price</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-12">
								<div class="form-group row no-gutters">
									<div class="col-12">
										<button type="button" id="side-panel-filter-trigger" style="height:33px; border-radius:0;" class="btn bg-teal col-12 font-size-13"><i class="icon-list"></i> Filter</button>
									</div>
								</div>
							</div>
							<div class="col-12 text-center">
								@if($filter['other']['search'])
									<div class="font-size-12 mb-1 mt-2">Search For : <strong class="font-italic">{{ $filter['other']['search'] }}</strong></div>
								@endif
							</div>
						</div>
						<div class="form-group"><hr></div>
						@if($product->count() > 0)
							<div id="shop" class="shop row grid-container gutter-30">
								@foreach($product as $p)
									<div class="product col-lg-3 col-md-3 col-6 mb-4">
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
							</div>
						@else
							<center class="mt-5">
								<img src="{{ asset('website/data-empty.png') }}" style="max-width:80%;" class="img-fluid" alt="Empty">
							</center>
						@endif
						{{ $product->withQueryString()->onEachSide(1)->links('pagination') }}
					</div>
				</div>
			</form>
		</div>
	</div>
</section>

<script>
	$(function() {
		$('.side-panel-filter').slideReveal({
			trigger: $('#side-panel-filter-trigger'),
			push: false,
			overlay: true,
			width: '300px',
			position: 'left'
		});
	});

	function clickFilter(event) {
		loadingOpen('body');
		$(event).parents('form').submit();
	}
</script>