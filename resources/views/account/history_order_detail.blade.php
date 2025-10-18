<section id="content">
	<div class="content-wrap">
		<div class="container">
			<div class="card">
				<div class="card-body">
					<div class="card-title">
						<a href="{{ url('account/history_order') }}" class="text-dark text-uppercase font-size-13">
							<i class="icon-chevron-left"></i>
							&nbsp;Back
						</a>
						<span class="float-right text-uppercase font-size-13">
							No. # {{ $order->number }}
						</span>
					</div>
					@if($order->status == 1)
						<div class="mb-5">
							<div class="row justify-content-center">
								<div class="col-md-6 text-center">
									<div id="simple_timer"></div>
								</div>
							</div>
						</div>
					@else
						<div class="mt-5 mb-5"></div>
					@endif
					<div class="table-responsive">
						<table class="table cart table-bordered">
							<thead>
								<tr class="text-center font-size-13">
									<th class="cart-product-thumbnail text-center">Image</th>
									<th class="cart-product-name">Product</th>
									<th class="cart-product-price">Unit Price</th>
									<th class="cart-product-quantity">Qty</th>
									<th class="cart-product-subtotal">Total</th>
                                    @if($order->status == 4)
									    <th class="cart-product-subtotal">Review</th>
                                    @endif
								</tr>
							</thead>
							<tbody>
								@foreach($order->orderDetail as $od)
									<tr class="cart_item text-center font-size-12">
										<td class="cart-product-quantity">
											<a href="{{ url('product/detail/' . Str::slug($od->product->name()) . '?q=' . base64_encode($od->id)) }}">
												<img width="64" height="64" src="{{ $od->product->type->image() }}" class="img-fluid img-thumbnail" alt="{{ $od->product->name() }}">
											</a>
										</td>
										<td class="cart-product-name">
											<a href="{{ url('product/detail/' . Str::slug($od->product->name()) . '?q=' . base64_encode($od->id)) }}" class="font-size-12">{{ $od->product->name() }}</a>
										</td>
										<td class="cart-product-quantity">
											<span class="amount">Rp {{ number_format($od->price_list, 0, ',', '.') }}</span>
										</td>
										<td class="cart-product-quantity">
											<div class="quantity">
												<span class="amount">x{{ $od->qty }}</span>
											</div>
										</td>
										<td class="cart-product-subtotal">
											<span class="amount">
												Rp {{ number_format($od->total, 0, ',', '.') }}
											</span>
										</td>
                                        <td class="cart-product-quantity">
											<button type="button" onclick="openReview({{ $od->id }})" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal_review"><i class="icon-note"></i></button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-12 mt-4 mb-3">
							<div class="border p-4">
								<div class="table-responsive">
									<h5 class="text-uppercase">Billing Address</h5>
									<table class="table cart">
										<tbody>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Name</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong class="font-size-12 text-dark">{{ $order->customer->name }}</strong>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Email</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong class="font-size-12 text-dark">{{ $order->customer->email }}</strong>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Phone</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong class="font-size-12 text-dark">{{ $order->customer->phone }}</strong>
													</span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-md-12 mt-4 mb-3">
							<div class="border p-4">
								<div class="table-responsive">
									<h5 class="text-uppercase">Shipping Address</h5>
									<table class="table cart">
										<tbody>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Name</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong class="font-size-12 text-dark">
															{{ $order->orderShipping ? $order->orderShipping->receiver_name : 'Delivery not set' }}
														</strong>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Email</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong class="font-size-12 text-dark">
															{{ $order->orderShipping ? $order->orderShipping->email : 'Delivery not set' }}
														</strong>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Phone</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong class="font-size-12 text-dark">
															{{ $order->orderShipping ? $order->orderShipping->phone : 'Delivery not set' }}
														</strong>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">City</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong class="font-size-12 text-dark">
															{{ $order->orderShipping ? $order->orderShipping->city->name : 'Delivery not set' }}
														</strong>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Address</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong class="font-size-12 text-dark">
															{{ $order->orderShipping ? $order->orderShipping->address : 'Delivery not set' }}
														</strong>
													</span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="fancy-title title-border title-center mt-4 mb-4">
						<h1 style="color:rgba(0, 0, 0, .3);" class="text-uppercase">{{ $order->status() }}</h1>
					</div>
					<div class="row justify-content-end">
						<div class="col-lg-12 mb-5">
							<div class="border p-4">
								<div class="table-responsive">
									<h5 class="text-uppercase">Payment Information</h5>
									@if($order->orderPayment)
										<table class="table cart">
											<tbody>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Paid At</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																{{ date('d F Y, H:i', strtotime($order->orderPayment->created_at)) }}
															</strong>
														</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Method</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																{{ $order->orderPayment->method }}
															</strong>
														</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Channel</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																{{ $order->orderPayment->channel }}
															</strong>
														</span>
													</td>
												</tr>
											</tbody>
										</table>
									@else
										<div class="style-msg2" style="background-color: #EEE;">
											<div class="sb-msg font-size-12">
												<i class="icon-warning-sign"></i>
												<strong>Ooppsss!</strong>
												There is no payment on your order
											</div>
										</div>
									@endif
								</div>
							</div>
						</div>
						@if($order->voucher)
							<div class="col-lg-12 mb-5">
								<div class="border p-4">
									<div class="table-responsive">
										<h5 class="text-uppercase">Voucher Information</h5>
										<table class="table cart">
											<tbody>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Voucher</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																{{ $order->voucher->name }}
															</strong>
														</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Code</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																{{ $order->voucher->code }}
															</strong>
														</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Type</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																{{ $order->voucher->type() }}
															</strong>
														</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Discount</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																{{ $order->voucher->percentage }}%
															</strong>
														</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Minimum Order</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																Rp {{ number_format($order->voucher->minimum, 0, ',', '.') }}
															</strong>
														</span>
													</td>
												</tr>
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Maximum Discount</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																Rp {{ number_format($order->voucher->maximum, 0, ',', '.') }}
															</strong>
														</span>
													</td>
												</tr>
												@if($order->voucher->points > 0)
													<tr class="cart_item">
														<td class="cart-product-name">
															<strong class="font-size-12">Cashback</strong>
														</td>
														<td class="cart-product-name">
															<span class="amount color lead">
																<strong id="grandtotal" class="font-size-12 text-dark">
																	{{ number_format($order->voucher->points, 0, ',', '.') }} Points
																</strong>
															</span>
														</td>
													</tr>
												@endif
											</tbody>
										</table>
									</div>
								</div>
							</div>
						@endif
						<div class="col-lg-12">
							<div class="border p-4">
								<div class="table-responsive">
									<h5 class="text-uppercase">Summary</h5>
									<table class="table cart">
										<tbody>
											@if(Storage::exists($order->qr_code))
												<tr class="cart_item">
													<td rowspan="7">
														<center>
															<img src="{{ asset(Storage::url($order->qr_code)) }}" class="img-fluid">
														</center>
													</td>
												</tr>
											@endif
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Subtotal</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong id="grandtotal" class="font-size-12 text-dark">
															Rp {{ number_format($order->subtotal, 0, ',', '.') }}
														</strong>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Discount</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong id="grandtotal" class="font-size-12 text-dark">
															- Rp {{ number_format($order->discount, 0, ',', '.') }}
														</strong>
													</span>
												</td>
											</tr>
											@if($order->points)
												<tr class="cart_item">
													<td class="cart-product-name">
														<strong class="font-size-12">Redeem Points</strong>
													</td>
													<td class="cart-product-name">
														<span class="amount color lead">
															<strong id="grandtotal" class="font-size-12 text-dark">
																- {{ number_format($order->points, 0, ',', '.') }}
															</strong>
														</span>
													</td>
												</tr>
											@endif
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-12">Delivery Cost</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong id="grandtotal" class="font-size-12 text-dark">
															Rp {{ number_format($order->shipping, 0, ',', '.') }}
														</strong>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-14">Total</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<strong id="grandtotal" class="font-size-14 text-dark font-weight-bold">
															Rp {{ number_format($order->grandtotal, 0, ',', '.') }}
														</strong>
													</span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group"><hr></div>
					<div class="row">
						<div class="col-lg-4 col-md-12">
							<div class="style-msg2 successmsg">
								<div class="msgtitle text-center font-weight-bold text-uppercase font-size-12">Type Of Transport</div>
								<div class="sb-msg text-center font-weight-semibold font-size-12">
									{{ $order->orderShipping ? $order->orderShipping->delivery->transport->fleet : 'Delivery not set' }}
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12">
							<div class="style-msg2 errormsg">
								<div class="msgtitle text-center font-weight-bold text-uppercase font-size-12">Payment Method</div>
								<div class="sb-msg text-center font-weight-semibold font-size-12">
									{{ $order->type() }}
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12">
							<div class="style-msg2 alertmsg">
								<div class="msgtitle text-center font-weight-bold text-uppercase font-size-12">Status</div>
								<div class="sb-msg text-center font-weight-semibold font-size-12">
									@if($order->status == 1)
										Waiting for payment
									@elseif($order->status == 2)
										Order has been paid
									@elseif($order->status == 3)
										Order has been delivery
									@elseif($order->status == 4)
										Order has been completed
									@elseif($order->status == 5)
										Order canceled
									@endif
								</div>
							</div>
						</div>
					</div>
					@if($order->status == 1 && $order->type == 2)
						<div class="form-group"><hr></div>
						<div class="text-right mt-4">
							<a href="{{ $order->xendit()->url }}" class="button bg-teal button-3d font-size-12">Pay Now</a>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="modal_review" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Review Product</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form id="form_review">
                    <div class="form-group">
                        <div class="rating">
                            <input type="radio" name="field_rate" id="rating-5" value="5">
                            <label for="rating-5"></label>
                            <input type="radio" name="field_rate" id="rating-4" value="4">
                            <label for="rating-4"></label>
                            <input type="radio" name="field_rate" id="rating-3" value="3">
                            <label for="rating-3"></label>
                            <input type="radio" name="field_rate" id="rating-2" value="2">
                            <label for="rating-2"></label>
                            <input type="radio" name="field_rate" id="rating-1" value="1">
                            <label for="rating-1"></label>
                            <div class="emoji-wrapper">
                                <div class="emoji">
                                    <svg class="rating-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                        <path d="M512 256c0 141.44-114.64 256-256 256-80.48 0-152.32-37.12-199.28-95.28 43.92 35.52 99.84 56.72 160.72 56.72 141.36 0 256-114.56 256-256 0-60.88-21.2-116.8-56.72-160.72C474.8 103.68 512 175.52 512 256z" fill="#f4c534"/>
                                        <ellipse transform="scale(-1) rotate(31.21 715.433 -595.455)" cx="166.318" cy="199.829" rx="56.146" ry="56.13" fill="#fff"/>
                                        <ellipse transform="rotate(-148.804 180.87 175.82)" cx="180.871" cy="175.822" rx="28.048" ry="28.08" fill="#3e4347"/>
                                        <ellipse transform="rotate(-113.778 194.434 165.995)" cx="194.433" cy="165.993" rx="8.016" ry="5.296" fill="#5a5f63"/>
                                        <ellipse transform="scale(-1) rotate(31.21 715.397 -1237.664)" cx="345.695" cy="199.819" rx="56.146" ry="56.13" fill="#fff"/>
                                        <ellipse transform="rotate(-148.804 360.25 175.837)" cx="360.252" cy="175.84" rx="28.048" ry="28.08" fill="#3e4347"/>
                                        <ellipse transform="scale(-1) rotate(66.227 254.508 -573.138)" cx="373.794" cy="165.987" rx="8.016" ry="5.296" fill="#5a5f63"/>
                                        <path d="M370.56 344.4c0 7.696-6.224 13.92-13.92 13.92H155.36c-7.616 0-13.92-6.224-13.92-13.92s6.304-13.92 13.92-13.92h201.296c7.696.016 13.904 6.224 13.904 13.92z" fill="#3e4347"/>
                                    </svg>
                                    <svg class="rating-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534"/>
                                        <path d="M328.4 428a92.8 92.8 0 0 0-145-.1 6.8 6.8 0 0 1-12-5.8 86.6 86.6 0 0 1 84.5-69 86.6 86.6 0 0 1 84.7 69.8c1.3 6.9-7.7 10.6-12.2 5.1z" fill="#3e4347"/>
                                        <path d="M269.2 222.3c5.3 62.8 52 113.9 104.8 113.9 52.3 0 90.8-51.1 85.6-113.9-2-25-10.8-47.9-23.7-66.7-4.1-6.1-12.2-8-18.5-4.2a111.8 111.8 0 0 1-60.1 16.2c-22.8 0-42.1-5.6-57.8-14.8-6.8-4-15.4-1.5-18.9 5.4-9 18.2-13.2 40.3-11.4 64.1z" fill="#f4c534"/>
                                        <path d="M357 189.5c25.8 0 47-7.1 63.7-18.7 10 14.6 17 32.1 18.7 51.6 4 49.6-26.1 89.7-67.5 89.7-41.6 0-78.4-40.1-82.5-89.7A95 95 0 0 1 298 174c16 9.7 35.6 15.5 59 15.5z" fill="#fff"/>
                                        <path d="M396.2 246.1a38.5 38.5 0 0 1-38.7 38.6 38.5 38.5 0 0 1-38.6-38.6 38.6 38.6 0 1 1 77.3 0z" fill="#3e4347"/>
                                        <path d="M380.4 241.1c-3.2 3.2-9.9 1.7-14.9-3.2-4.8-4.8-6.2-11.5-3-14.7 3.3-3.4 10-2 14.9 2.9 4.9 5 6.4 11.7 3 15z" fill="#fff"/>
                                        <path d="M242.8 222.3c-5.3 62.8-52 113.9-104.8 113.9-52.3 0-90.8-51.1-85.6-113.9 2-25 10.8-47.9 23.7-66.7 4.1-6.1 12.2-8 18.5-4.2 16.2 10.1 36.2 16.2 60.1 16.2 22.8 0 42.1-5.6 57.8-14.8 6.8-4 15.4-1.5 18.9 5.4 9 18.2 13.2 40.3 11.4 64.1z" fill="#f4c534"/>
                                        <path d="M155 189.5c-25.8 0-47-7.1-63.7-18.7-10 14.6-17 32.1-18.7 51.6-4 49.6 26.1 89.7 67.5 89.7 41.6 0 78.4-40.1 82.5-89.7A95 95 0 0 0 214 174c-16 9.7-35.6 15.5-59 15.5z" fill="#fff"/>
                                        <path d="M115.8 246.1a38.5 38.5 0 0 0 38.7 38.6 38.5 38.5 0 0 0 38.6-38.6 38.6 38.6 0 1 0-77.3 0z" fill="#3e4347"/>
                                        <path d="M131.6 241.1c3.2 3.2 9.9 1.7 14.9-3.2 4.8-4.8 6.2-11.5 3-14.7-3.3-3.4-10-2-14.9 2.9-4.9 5-6.4 11.7-3 15z" fill="#fff"/>
                                    </svg>
                                    <svg class="rating-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534"/>
                                        <path d="M336.6 403.2c-6.5 8-16 10-25.5 5.2a117.6 117.6 0 0 0-110.2 0c-9.4 4.9-19 3.3-25.6-4.6-6.5-7.7-4.7-21.1 8.4-28 45.1-24 99.5-24 144.6 0 13 7 14.8 19.7 8.3 27.4z" fill="#3e4347"/>
                                        <path d="M276.6 244.3a79.3 79.3 0 1 1 158.8 0 79.5 79.5 0 1 1-158.8 0z" fill="#fff"/>
                                        <circle cx="340" cy="260.4" r="36.2" fill="#3e4347"/>
                                        <g fill="#fff">
                                        <ellipse transform="rotate(-135 326.4 246.6)" cx="326.4" cy="246.6" rx="6.5" ry="10"/>
                                        <path d="M231.9 244.3a79.3 79.3 0 1 0-158.8 0 79.5 79.5 0 1 0 158.8 0z"/>
                                        </g>
                                        <circle cx="168.5" cy="260.4" r="36.2" fill="#3e4347"/>
                                        <ellipse transform="rotate(-135 182.1 246.7)" cx="182.1" cy="246.7" rx="10" ry="6.5" fill="#fff"/>
                                    </svg>
                                    <svg class="rating-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                        <path d="M407.7 352.8a163.9 163.9 0 0 1-303.5 0c-2.3-5.5 1.5-12 7.5-13.2a780.8 780.8 0 0 1 288.4 0c6 1.2 9.9 7.7 7.6 13.2z" fill="#3e4347"/>
                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534"/>
                                        <g fill="#fff">
                                            <path d="M115.3 339c18.2 29.6 75.1 32.8 143.1 32.8 67.1 0 124.2-3.2 143.2-31.6l-1.5-.6a780.6 780.6 0 0 0-284.8-.6z"/>
                                            <ellipse cx="356.4" cy="205.3" rx="81.1" ry="81"/>
                                        </g>
                                        <ellipse cx="356.4" cy="205.3" rx="44.2" ry="44.2" fill="#3e4347"/>
                                        <g fill="#fff">
                                            <ellipse transform="scale(-1) rotate(45 454 -906)" cx="375.3" cy="188.1" rx="12" ry="8.1"/>
                                            <ellipse cx="155.6" cy="205.3" rx="81.1" ry="81"/>
                                        </g>
                                        <ellipse cx="155.6" cy="205.3" rx="44.2" ry="44.2" fill="#3e4347"/>
                                        <ellipse transform="scale(-1) rotate(45 454 -421.3)" cx="174.5" cy="188" rx="12" ry="8.1" fill="#fff"/>
                                    </svg>
                                    <svg class="rating-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z" fill="#f4c534"/>
                                        <path d="M232.3 201.3c0 49.2-74.3 94.2-74.3 94.2s-74.4-45-74.4-94.2a38 38 0 0 1 74.4-11.1 38 38 0 0 1 74.3 11.1z" fill="#e24b4b"/>
                                        <path d="M96.1 173.3a37.7 37.7 0 0 0-12.4 28c0 49.2 74.3 94.2 74.3 94.2C80.2 229.8 95.6 175.2 96 173.3z" fill="#d03f3f"/>
                                        <path d="M215.2 200c-3.6 3-9.8 1-13.8-4.1-4.2-5.2-4.6-11.5-1.2-14.1 3.6-2.8 9.7-.7 13.9 4.4 4 5.2 4.6 11.4 1.1 13.8z" fill="#fff"/>
                                        <path d="M428.4 201.3c0 49.2-74.4 94.2-74.4 94.2s-74.3-45-74.3-94.2a38 38 0 0 1 74.4-11.1 38 38 0 0 1 74.3 11.1z" fill="#e24b4b"/>
                                        <path d="M292.2 173.3a37.7 37.7 0 0 0-12.4 28c0 49.2 74.3 94.2 74.3 94.2-77.8-65.7-62.4-120.3-61.9-122.2z" fill="#d03f3f"/>
                                        <path d="M411.3 200c-3.6 3-9.8 1-13.8-4.1-4.2-5.2-4.6-11.5-1.2-14.1 3.6-2.8 9.7-.7 13.9 4.4 4 5.2 4.6 11.4 1.1 13.8z" fill="#fff"/>
                                        <path d="M381.7 374.1c-30.2 35.9-75.3 64.4-125.7 64.4s-95.4-28.5-125.8-64.2a17.6 17.6 0 0 1 16.5-28.7 627.7 627.7 0 0 0 218.7-.1c16.2-2.7 27 16.1 16.3 28.6z" fill="#3e4347"/>
                                        <path d="M256 438.5c25.7 0 50-7.5 71.7-19.5-9-33.7-40.7-43.3-62.6-31.7-29.7 15.8-62.8-4.7-75.6 34.3 20.3 10.4 42.8 17 66.5 17z" fill="#e24b4b"/>
                                    </svg>
                                    <svg class="rating-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                        <g fill="#ffd93b">
                                        <path d="M512 256A256 256 0 0 1 56.8 416.7a256 256 0 0 0 360-360c58 47 95.2 118.8 95.2 199.3z"/>
                                        </g>
                                        <path d="M512 99.4v165.1c0 11-8.9 19.9-19.7 19.9h-187c-13 0-23.5-10.5-23.5-23.5v-21.3c0-12.9-8.9-24.8-21.6-26.7-16.2-2.5-30 10-30 25.5V261c0 13-10.5 23.5-23.5 23.5h-187A19.7 19.7 0 0 1 0 264.7V99.4c0-10.9 8.8-19.7 19.7-19.7h472.6c10.8 0 19.7 8.7 19.7 19.7z" fill="#e9eff4"/>
                                        <path d="M204.6 138v88.2a23 23 0 0 1-23 23H58.2a23 23 0 0 1-23-23v-88.3a23 23 0 0 1 23-23h123.4a23 23 0 0 1 23 23z" fill="#45cbea"/>
                                        <path d="M476.9 138v88.2a23 23 0 0 1-23 23H330.3a23 23 0 0 1-23-23v-88.3a23 23 0 0 1 23-23h123.4a23 23 0 0 1 23 23z" fill="#e84d88"/>
                                        <g fill="#38c0dc">
                                        <path d="M95.2 114.9l-60 60v15.2l75.2-75.2zM123.3 114.9L35.1 203v23.2c0 1.8.3 3.7.7 5.4l116.8-116.7h-29.3z"/>
                                        </g>
                                        <g fill="#d23f77">
                                        <path d="M373.3 114.9l-66 66V196l81.3-81.2zM401.5 114.9l-94.1 94v17.3c0 3.5.8 6.8 2.2 9.8l121.1-121.1h-29.2z"/>
                                        </g>
                                        <path d="M329.5 395.2c0 44.7-33 81-73.4 81-40.7 0-73.5-36.3-73.5-81s32.8-81 73.5-81c40.5 0 73.4 36.3 73.4 81z" fill="#3e4347"/>
                                        <path d="M256 476.2a70 70 0 0 0 53.3-25.5 34.6 34.6 0 0 0-58-25 34.4 34.4 0 0 0-47.8 26 69.9 69.9 0 0 0 52.6 24.5z" fill="#e24b4b"/>
                                        <path d="M290.3 434.8c-1 3.4-5.8 5.2-11 3.9s-8.4-5.1-7.4-8.7c.8-3.3 5.7-5 10.7-3.8 5.1 1.4 8.5 5.3 7.7 8.6z" fill="#fff" opacity=".2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="field_review" id="field_review" class="summernote-review"></textarea>
                    </div>
                </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary font-size-13 btn-sm" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-success font-size-13 btn-sm" id="btn_review" onclick="saveReview()">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
        $('.summernote-review').summernote({
            placeholder: 'Enter your review here',
            height: 250
        });

		$('#simple_timer').syotimer({
			year: '{{ date("Y", strtotime("+1 day", strtotime($order->created_at))) }}',
			month: '{{ date("m", strtotime("+1 day", strtotime($order->created_at))) }}',
			day: '{{ date("d", strtotime("+1 day", strtotime($order->created_at))) }}',
			hour: '{{ date("H", strtotime("+1 day", strtotime($order->created_at))) }}',
			minute: '{{ date("i", strtotime("+1 day", strtotime($order->created_at))) }}',
			headTitle: '<h3 class="mb-2 mt-0">Deadline For Your Order</h3>',
			dayVisible: false,
			periodUnit: 'd',
			periodic: true,
			periodInterval: 10,
			timeZone: 'local'
		});
	});

    function openReview(id) {
        $.ajax({
            url: '{{ url("account/history_order/review") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                id: id
            },
            beforeSend: function() {
                loadingOpen('.modal-content');
                $('input[type="radio"][name="field_rate"]').prop('checked', false);
            },
            success: function(response) {
                loadingClose('.modal-content');
                $('input[name="field_rate"][value="' + response.rate + '"]').prop('checked', true);
                $('#field_review').summernote('code', response.review);
                $('#btn_review').attr('onclick', 'saveReview(' + id + ')');
            },
            error: function() {
                loadingClose('.modal-content');
                Swal.fire('Server Error', '', 'error');
            }
        });
    }

    function saveReview(id) {
        var review = $('#field_review').summernote('code');
        var rate   = $('input[name="field_rate"]:checked').val();

        if(review && rate) {
            $.ajax({
                url: '{{ url("account/history_order/review") }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    review: review,
                    rate: rate
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen('.modal-content');
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    if(response.status == 200) {
                        Swal.fire('Success', response.message, 'success');
                        $('#modal_review').modal('hide');
                    } else {
                        Swal.fire('Failed', response.message, 'error');
                    }
                },
                error: function() {
                    loadingClose('.modal-content');
                    Swal.fire('Server Error', '', 'error');
                }
            });
        } else {
            Swal.fire('Oooppsss', 'Please enter all field', 'info');
        }
    }
</script>
