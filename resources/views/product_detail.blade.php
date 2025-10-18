<div itemscope itemtype="http://schema.org/Product">
	<meta itemprop="brand" content="{{ $product->brand->name }}">
	<meta itemprop="name" content="{{ $product->name() }}">
	<meta itemprop="description" content="{!! $product->description !!}">
	<meta itemprop="productID" content="{{ base64_encode($product->id) }}">
	<meta itemprop="url" content="{{ url()->current() }}">
	<meta itemprop="image" content="{{ $product->type->image() }}">
	<div itemprop="value" itemscope itemtype="http://schema.org/PropertyValue">
		<span itemprop="propertyID" content="{{ $product->type->category->id }}"></span>
		<meta itemprop="value" content="{{ $product->type->category->name }}">
	</div>
	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<link itemprop="availability" href="{{ url()->current() }}">
		<link itemprop="itemCondition" href="{{ url()->current() }}">
		<meta itemprop="price" content="{{ $product->price() }}">
		<meta itemprop="priceCurrency" content="IDR">
	</div>
</div>

<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="single-product">
				<div class="product">
					<div class="row gutter-40">
						<div class="col-lg-4 col-md-12">
							<div class="product-image">
								<div class="fslider" data-pagi="false" data-arrows="false" data-thumbs="true">
									<div class="flexslider">
										<div class="slider-wrap" data-lightbox="gallery">
											<div class="slide" data-thumb="{{ $product->type->image() }}">
												<a href="{{ $product->type->image() }}" title="{{ $product->name() }}" data-lightbox="gallery-item">
													<img src="{{ $product->type->image() }}" alt="{{ $product->name() }}" class="" alt="{{ $product->name() }}" style="width:250px !important;height:auto !important;margin:auto !important;">
												</a>
											</div>
										</div>
									</div>
								</div>
								<div class="sale-flash badge {{ $product->availability()->color }} p-2">{{ $product->availability()->status }}</div>
							</div>
						</div>
						<div class="col-lg-8 col-md-12 product-desc">
							<h3 class="font-weight-bold mb-0">{{ $product->name() }}</h3>
                            <div class="mt-3 mb-3">
                                <span style="font-size:15px;">
                                    @foreach($product->review()->star as $r)
                                        {!! $r !!}
                                    @endforeach
                                </span>
                                <span style="font-size:12px; font-weight:600;" class="ml-2">{{ $product->review()->sold }} Sold</span>
                            </div>
							<div class="d-flex align-items-center justify-content-between">
								<div class="product-price text-danger font-weight-bold" style="font-size:25px;">
									Rp <ins class="text-danger font-weight-bold">{{ number_format($product->price(), 0, ',', '.') }}</ins>
								</div>
								<div class="d-flex align-items-center justify-content-end font-size-14">
									Availability&nbsp;&nbsp;<strong>{{ $product->availability()->stock }} Carton</strong>
								</div>
							</div>
                            @if($product_grade->count() > 0)
                                <div class="text-left mt-3">
                                    @foreach($product_grade as $pg)
                                        @if($pg->id == $product->id)
                                            <a href="javascript:void(0);" class="btn btn-teal btn-sm btn-no-outline btn-xs-small cursor-none mr-1">
                                                {{ ucwords(strtolower($pg->grade->name)) }}
                                            </a>
                                        @else
                                            <a href="{{ url('product/detail/' . Str::slug($pg->name()) . '?q=' . base64_encode($pg->id)) }}" class="btn btn-outline-teal btn-sm btn-no-outline btn-xs-small mr-1">
                                                {{ ucwords(strtolower($pg->grade->name)) }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
							@if($product->availability()->stock > 0)
								<div class="line"></div>
								<form method="POST" action="{{ url('product/add_to_cart') }}" class="cart mb-0" method="POST">
									@csrf
									<div class="row gutter-30">
										<div class="col-6">
											<div class="quantity">
												<input type="hidden" name="product_id" value="{{ base64_encode($product->id) }}">
												<input type="button" value="-" class="minus">
												<input type="number" step="1" min="1" name="qty" id="qty" onchange="checkStock()" value="1" max="{{ $product->availability()->stock }}" title="Quantity" class="qty">
												<input type="button" value="+" class="plus">
											</div>
											<div class="mb-2 mt-2">
												<a href="javascript:void(0);" data-toggle="modal" data-target="#see_detail" class="text-primary mt-2 font-size-13"><i class="icon-line-info"></i> See Detail</a>
											</div>
										</div>
										<div class="col-6 text-right">
											<button type="submit" class="button bg-teal font-size-12 m-0">Add to cart</button>
										</div>
									</div>
								</form>
							@endif
							<div class="line"></div>
							<form method="POST" action="{{ url('product/add_to_wishlist') }}" class="cart mb-0 d-flex justify-content-between align-items-center">
								@csrf
								<div class="quantity clearfix">
									<input type="hidden" name="product_id" value="{{ base64_encode($product->id) }}">
									<input type="text" value="Add To Wishlist" class="form-control-plaintext font-size-12 font-weight-bold" disabled>
								</div>
								@if(count($product->wishlist) > 0)
									@if(count($product->wishlist->where('customer_id', session('fo_id'))) > 0)
										<a href="javascript:void(0);" class="button button-red m-0 cursor-none font-size-12"><i class="icon-heart21"></i></a>
									@endif
								@else
									<button type="submit" class="button button-small button-teal m-0 font-size-12"><i class="icon-heart21"></i></button>
								@endif
							</form>
							<div class="line"></div>
							<ul class="list-group list-group-flush">
								<li class="list-group-item d-flex justify-content-between align-items-center px-0">
									@if($product->availability()->status == 'Ready')
										<div class="style-msg successmsg w-100 text-center">
											<div class="sb-msg font-size-13">
												<i class="icon-ok-circle"></i>
												<strong>Ready Stock!</strong> Buy now
											</div>
										</div>
									@elseif($product->availability()->status == 'Limited')
										<div class="style-msg alertmsg w-100 text-center">
											<div class="sb-msg font-size-13">
												<i class="icon-warning-sign"></i>
												<strong>Stock Limited!</strong> Buy now before it runs out
											</div>
										</div>
									@else
										<div class="style-msg errormsg w-100 text-center">
											<div class="sb-msg font-size-13">
												<i class="icon-remove-sign"></i>
												<strong>Stock Empty!</strong> Sorry our stock is empty
											</div>
										</div>
									@endif
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
									<span class="text-muted">Code:</span>
									<span class="text-dark font-weight-semibold">{{ $product->code() }}</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
									<span class="text-muted">Category:</span>
									<span class="text-dark font-weight-semibold">{{ $product->type->category->name }}</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
									<span class="text-muted">Color:</span>
									<span class="text-dark font-weight-semibold">{{ $product->type->color->name }}</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
									<span class="text-muted">Brand:</span>
									<span class="text-dark font-weight-semibold">{{ $product->brand->name }}</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
									<span class="text-muted">Size:</span>
									<span class="text-dark font-weight-semibold">
										{{ $product->type->length }}x{{ $product->type->width }} Cm
									</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
									<span class="text-muted">Revit File:</span>
									<span class="text-dark font-weight-semibold">
										@if($product->design)
											<a class="button button-small button-teal m-0 font-size-12"target="_blank" href="{{ $product->attachment() }}"><i class="icon-line-download"></i></a>
										@else
											<span class="badge badge-danger">Empty</span>
										@endif
									</span>
								</li>
								<li class="list-group-item d-flex justify-content-center align-items-center px-0">
									<div class="addthis_inline_share_toolbox_ykjh"></div>
								</li>
							</ul>
						</div>
						@if($voucher->count() > 0)
							<div class="w-100"></div>
							<div class="col-12 mt-3">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title text-uppercase">Voucher</h4>
										<p>
											<div class="owl-carousel carousel-widget" data-nav="true" data-pagi="false" data-items-xs="1" data-items-sm="1" data-items-md="1" data-items-lg="2" data-items-xl="2" style="overflow: visible;">
												@foreach($voucher as $v)
													<div class="card shadow">
														<div class="card-body">
															<h6 class="card-title mb-2">{{ $v->name }}</h6>
															<div class="mb-1">
																<span class="font-size-11">
																	Code : {{ $v->code }}
																</span>
															</div>
															<div class="mb-1">
																<span class="font-size-11 text-uppercase">
																	{{ $v->voucherType() }} | {{ $v->percentage }}%
																</span>
															</div>
															<span class="text-teal font-size-12 font-weight-medium">Expired : {{ date('d F Y', strtotime($v->finish_date)) }}</span>
														</div>
													</div>
												@endforeach
											</div>
										</p>
									</div>
								</div>
							</div>
						@endif
						<div class="w-100"></div>
						<div class="col-12 mt-3">
							<div class="card">
								<div class="card-body">
									<div class="tabs clearfix mb-0" id="tab-1">
										<ul class="tab-nav justify-content-center clearfix">
											<li>
												<a href="#tabs-1" class="font-size-13">
													<i class="icon-align-justify2"></i> Description
												</a>
											</li>
											<li>
												<a href="#tabs-2" class="font-size-13">
													<i class="icon-settings"></i> Specification
												</a>
											</li>
                                            <li>
												<a href="#tabs-3" class="font-size-13">
													<i class="icon-star3"></i> Review
												</a>
											</li>
										</ul>
										<div class="tab-container">
											<div class="tab-content clearfix" id="tabs-1">
												<p>{!! $product->description !!}</p>
											</div>
											<div class="tab-content clearfix" id="tabs-2">
												<table class="table table-striped table-bordered font-size-13">
													<tbody>
														<tr>
															<td>Pattern</td>
															<td>{{ $product->type->pattern->name }}</td>
														</tr>
														<tr>
															<td>Surface</td>
															<td>{{ $product->type->surface->name }}</td>
														</tr>
														<tr>
															<td>Loading Limit</td>
															<td>{{ $product->type->loadingLimit->name }}</td>
														</tr>
														<tr>
															<td>Weight</td>
															<td>{{ $product->type->weight }} Kg</td>
														</tr>
														<tr>
															<td>Thickness</td>
															<td>{{ $product->type->thickness }} mm</td>
														</tr>
														<tr>
															<td>Grade</td>
															<td>{{ $product->grade->name }}</td>
														</tr>
														<tr>
															<td>Country</td>
															<td>{{ $product->country->name }}</td>
														</tr>
													</tbody>
												</table>
											</div>
                                            <div class="tab-content clearfix" id="tabs-3">
                                                @if($product->productReview)
                                                    <div id="reviews" class="clearfix">
                                                        <ol class="commentlist clearfix">
                                                            @foreach($product->productReview as $pr)
                                                                <li class="comment even thread-even depth-{{ $pr->id }}" id="li-comment-{{ $pr->id }}">
                                                                    <div id="comment-{{ $pr->id }}" class="comment-wrap clearfix">
                                                                        <div class="comment-meta">
                                                                            <div class="comment-author vcard">
                                                                                <span class="comment-avatar clearfix">
                                                                                    <img src="{{ $pr->customer->photo() }}" height="60" width="60">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="comment-content clearfix">
                                                                            <div class="comment-author">
                                                                                {{ $pr->customer->name }}
                                                                                <span>
                                                                                    <a href="javascript:void(0);" class="cursor-none">
                                                                                        {{ $pr->created_at->format('F d, Y') }} at {{ $pr->created_at->format('H:i A') }}
                                                                                    </a>
                                                                                </span>
                                                                            </div>
                                                                            <p>{!! $pr->review !!}</p>
                                                                            <div class="review-comment-ratings">
                                                                                @foreach($pr->product->review($pr->rate)->star as $r)
                                                                                    {!! $r !!}
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                        <div class="clear"></div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ol>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">No Review</div>
                                                @endif
                                            </div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@if($related_product->count() > 0)
				<div class="line"></div>
				<div class="w-100">
					<h4>Related Product</h4>
					<div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-nav="true" data-loop="true" data-pagi="true" data-autoplay="5000" data-items-xs="2" data-items-md="3" data-items-lg="4" data-items-xl="4">
						@foreach($related_product as $p)
							<div class="oc-item p-2">
								<div class="product">
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
							</div>
						@endforeach
					</div>
				</div>
			@endif
		</div>
	</div>
</section>

<div class="modal fade" id="see_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					DETAIL STOCK
					<div class="mt-2 text-danger font-italic" style="font-size:11px;">
						*) Shading is very important to determine your tile product purchase to have all the same color.
					</div>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped font-size-13">
						<thead class="alert-info">
							<tr class="text-center">
								<th>Shading</th>
								<th>Available</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody id="data_shading"></tbody>
						<tfoot>
							<tr>
								<th colspan="2" class="text-right">Request Stock</th>
								<th id="total_request_stock">0</th>
							</tr>
							<tr>
								<th colspan="2" class="text-right">Available Stock</th>
								<th id="total_stock">0</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary font-size-13 btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		checkStock();
	});

	function checkStock() {
		$.ajax({
			url: '{{ url("product/check_stock") }}',
			type: 'GET',
			dataType: 'JSON',
			data: {
				product_id: '{{ base64_encode($product->id) }}',
				qty: $('#qty').val()
			},
			beforeSend: function() {
				$('#data_shading').html('');
				$('#total_stock').html(0);
				$('#total_request_stock').html(0);
			},
			success: function(response) {
				$('#total_stock').html(response.total_stock);
				$('#total_request_stock').html(response.total_request);

				if(response.data_shading.length > 0) {
					$.each(response.data_shading, function(i, val) {
						$('#data_shading').append(`
							<tr class="text-center">
								<td>` + val.shading + `</td>
								<td>` + val.initial_stock + `</td>
								<td>` + val.last_stock + `</td>
							</tr>
						`);
					});
				} else {
					$('#data_shading').html('<tr><td colspan="3" class="text-center">Data empty</td></tr>');
				}
			},
            error: function() {
                loadingClose('.modal-content');
                Swal.fire('Server Error', '', 'error');
            }
		});
	}
</script>