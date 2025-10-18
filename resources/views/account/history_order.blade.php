<section id="content">
	<div class="content-wrap">
		<div class="container-fluid clearfix" style="width:85% !important;">
			<div class="row clearfix">
				<div class="col-lg-3 col-md-12">
					<div class="list-group">
						<a href="{{ url('account/history_order') }}" class="list-group-item list-group-item-action d-flex justify-content-between {{ $status == null ? 'active' : '' }}">
							<div class="font-size-13">All</div>
							<span class="badge badge-light">{{ App\Models\Order::where('customer_id', session('fo_id'))->count() }}</span>
						</a>
						<a href="{{ url('account/history_order?status=1') }}" class="list-group-item list-group-item-action d-flex justify-content-between {{ $status == 1 ? 'active' : '' }}">
							<div class="font-size-13">Unpaid</div>
							<span class="badge badge-light">{{ App\Models\Order::where('customer_id', session('fo_id'))->where('status', 1)->count() }}</span>
						</a>
						<a href="{{ url('account/history_order?status=2') }}" class="list-group-item list-group-item-action d-flex justify-content-between {{ $status == 2 ? 'active' : '' }}">
							<div class="font-size-13">Paid</div>
							<span class="badge badge-light">{{ App\Models\Order::where('customer_id', session('fo_id'))->where('status', 2)->count() }}</span>
						</a>
						<a href="{{ url('account/history_order?status=3') }}" class="list-group-item list-group-item-action d-flex justify-content-between {{ $status == 3 ? 'active' : '' }}">
							<div class="font-size-13">On Delivery</div>
							<span class="badge badge-light">{{ App\Models\Order::where('customer_id', session('fo_id'))->where('status', 3)->count() }}</span>
						</a>
						<a href="{{ url('account/history_order?status=4') }}" class="list-group-item list-group-item-action d-flex justify-content-between {{ $status == 4 ? 'active' : '' }}">
							<div class="font-size-13">Done</div>
							<span class="badge badge-light">{{ App\Models\Order::where('customer_id', session('fo_id'))->where('status', 4)->count() }}</span>
						</a>
						<a href="{{ url('account/history_order?status=5') }}" class="list-group-item list-group-item-action d-flex justify-content-between {{ $status == 5 ? 'active' : '' }}">
							<div class="font-size-13">Cancel</div>
							<span class="badge badge-light">{{ App\Models\Order::where('customer_id', session('fo_id'))->where('status', 5)->count() }}</span>
						</a>
					</div>
				</div>
				<div class="w-100 line d-block d-lg-none"></div>
				<div class="col-lg-9 col-md-12">
					@if($order->count() > 0)
						@foreach($order as $o)
							<div class="card bg-light">
								<div class="card-body">
									<div class="card-title">
										<span style="font-size:15px; font-weight:500;"># {{ $o->number }}</span>
										<div class="float-right d-none d-sm-block font-size-13">
											{{ date('d M Y', strtotime($o->created_at)) }}
										</div>
									</div>
								</div>
							</div>
							<table class="table cart mb-4 bg-light table-bordered">
								<tbody>
									@foreach($o->orderDetail as $od)
										<tr class="cart_item">
											<td class="cart-product-name text-center">
												<div class="row">
													<div class="col-lg-3 col-md-12">
														<div class="form-group">
															<center>
																<a href="{{ url('product/detail/' . Str::slug($od->product->name()) . '?q=' . base64_encode($od->product->id)) }}">
																	<img width="64" height="64" src="{{ $od->product->type->image() }}" class="img-fluid img-thumbnail" alt="{{ $od->product->name() }}">
																</a>
															</center>
														</div>
													</div>
													<div class="col-lg-3 col-md-12">
														<div class="form-group font-size-13">
															{{ $od->product->name() }}
															<div class="text-muted">
																{{ $od->product->type->weight }} Kg
															</div>
															x{{ $od->qty }}
														</div>
													</div>
													<div class="col-lg-3 col-md-12">
														<div class="form-group font-size-13">
															Item Price
															<div>Rp {{ number_format($od->price_list, 0, ',', '.') }}</div>
														</div>
													</div>
													<div class="col-lg-3 col-md-12">
														<div class="form-group font-size-13">
															Subtotal
															<div>Rp {{ number_format($od->total, 0, ',', '.') }}</div>
														</div>
													</div>
												</div>
											</td>
										</tr>
									@endforeach
									<tr class="cart_item">
										<td class="mb-0">
											<div class="row">
												<div class="col-md-6">
													<h5 class="font-weight-medium">
														<div class="font-size-12">Total : <span class="text-muted">Rp {{  number_format($o->grandtotal, 0, ',', '.') }}</span></div>
														<div class="font-size-12 text-uppercase"><sub class="text-dark text-left font-weight-bold font-italic">{{ $o->type() }}</sub></div>
													</h5>
												</div>
												<div class="col-md-6 inline-block">
													<div class="text-center">
														@if($o->status == 3)
															<button type="button" class="button button-small button-red font-size-13" onclick="confirmationDelivery({{ $o->id }})">Arrived</button>
														@endif
														<a href="{{ url('account/history_order/detail/' . base64_encode($o->id)) }}" class="button bg-teal button-small font-size-13">Detail Order</a>
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						@endforeach
						{{ $order->withQueryString()->onEachSide(1)->links('pagination') }}
					@else
						<div class="alert alert-warning">
							<div class="text-center font-size-13">Transaction not found.</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	function confirmationDelivery(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: 'Order confirmation has arrived',
			icon: 'info',
			allowOutsideClick: false,
			showCancelButton: true,
			confirmButtonText: 'Ya, has arrived',
			cancelButtonText: 'Not arrived',
			reverseButtons: true
		}).then((result) => {
			if(result.isConfirmed) {
				$.ajax({
					url: '{{ url("account/history_order/confirmation_delivery") }}',
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
							let timerInterval
							Swal.fire({
								title: response.message,
								html: 'I will close in <b></b> milliseconds.',
								timer: 1000,
								allowOutsideClick: false,
								timerProgressBar: true,
								didOpen: () => {
									Swal.showLoading();
									timerInterval = setInterval(() => {
										const content = Swal.getHtmlContainer();
										if(content) {
											const b = content.querySelector('b');
											if(b) {
												b.textContent = Swal.getTimerLeft();
											}
										}
									}, 100)
								},
								willClose: () => {
									clearInterval(timerInterval);
								}
							}).then((result) => {
								if(result.dismiss === Swal.DismissReason.timer) {
									window.location.href = '{{ url("account/history_order?status=4") }}';
								}
							});
						} else {
							Swal.fire({
								title: 'Oooppsss!',
								text: 'Please try some more time',
								icon: 'info'
							});
						}
					},
					error: function() {
						Swal.fire({
							title: 'Server Error',
							text: 'Please try some more time',
							icon: 'error'
						});
					}
				});
			}
		})
	}
</script>
