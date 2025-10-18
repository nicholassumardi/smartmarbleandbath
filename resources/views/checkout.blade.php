<!DOCTYPE html>
<html dir="ltr" lang="id-ID">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="author" content="Calvin Dito Pratama">
   <meta name="google-site-verification" content="-PVsEPUU7R41vERWZ6fLe04fbAUA8mlyRjNHM2AkCDg">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Montserrat:300,400,500,600,700|Merriweather:300,400,300i,400i&display=swap" rel="stylesheet">
   <link rel="shortcut icon" href="{{ asset('website/icon.png') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/style.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/css/dark.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/css/swiper.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/demos/shop/css/fonts.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/demos/shop/shop.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/css/font-icons.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/css/animate.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/css/magnific-popup.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/css/components/radio-checkbox.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/css/colors.php?color=51b4ba') }}">
   <link rel="stylesheet" href="{{ asset('template/plugins/waitMe/waitMe.min.css') }}">
   <link rel="stylesheet" href="{{ asset('template/plugins/countdown/resources/default.css') }}">
	<link rel="stylesheet" href="{{ asset('template/front-office/custom.css') }}">
   <script src="{{ asset('template/front-office/js/jquery.js') }}"></script>
   <script src="{{ asset('template/plugins/waitMe/waitMe.min.js') }}"></script>
   <script src="{{ asset('template/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
   <script src="{{ asset('template/plugins/countdown/source/syotimer.lang.js') }}"></script>
   <script src="{{ asset('template/plugins/countdown/source/jquery.syotimer.js') }}"></script>
   <script src="{{ asset('template/plugins/side-panel/dist/jquery.slidereveal.min.js') }}"></script>
   <script src="{{ asset('template/front-office/custom.js') }}"></script>
   <title>Smartmarble And Bath | {{ $title }}</title>
</head>
<body class="stretched">
	<section id="page-title" class="page-title">
		<div class="container clearfix">
			<h1>
				<a href="{{ url('/') }}">
					<img src="{{ asset('website/logo-black.png') }}" alt="Smartmarble And Bath">
				</a>
			</h1>
			<ol class="breadcrumb text-uppercase font-weight-bold">
				<li class="breadcrumb-item active" aria-current="page">
					Checkout
				</li>
			</ol>
		</div>
	</section>
	<div id="wrapper" class="clearfix">
		<section id="content">
			<div class="content-wrap">
				<div class="container clearfix">
					<form action="" method="POST" id="form_order">
						@csrf
						<input type="hidden" name="param" id="param">
						@if($errors->any())
							<div class="style-msg2 errormsg">
								<div class="msgtitle">Something Wrong :</div>
								<div class="sb-msg">
									<ul>
										@foreach($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							</div>
						@endif
						<div class="card mb-4">
							<div class="card-body">
								<h5 class="card-title text-uppercase mb-4">Your Product</h5>
								<div class="table-responsive">
									<table class="table table-bordered cart">
										<tbody class="table-secondary">
											@php 
												$total_checkout = 0; 
												$total_weight   = 0; 
											@endphp
											@foreach($customer->cart as $c)
												@php 
													$total_checkout += $c->product->price() * $c->qty; 
													$total_weight   += $c->product->type->weight * $c->qty; 
												@endphp
												<tr class="cart_item text-center">
													<td class="cart-product-name">
														<a href="{{ url('product/detail/' . Str::slug($c->product->name()) . '?q=' . base64_encode($c->product->id)) }}">
															<center>
																<img width="64" height="64" src="{{ $c->product->type->image() }}" class="img-fluid img-thumbnail" alt="{{ $c->product->name() }}">
															</center>
														</a>
													</td>
													<td class="cart-product-name">
														<a href="{{ url('product/detail/' . Str::slug($c->product->name()) . '?q=' . base64_encode($c->product->id)) }}" class="font-size-13 font-weight-normal">{{ $c->product->name() }}</a>
													</td>
													<td class="cart-product-quantity">
														<span class="amount font-size-13">Rp {{ number_format($c->product->price(), 0, ',', '.') }}</span>
													</td>
													<td class="cart-product-quantity">
														<div class="quantity">
															<span class="amount font-size-13">x{{ $c->qty }}</span>
														</div>
													</td>
													<td class="cart-product-subtotal">
														<span class="amount font-size-13">
															Rp {{ number_format($c->product->price() * $c->qty, 0, ',', '.') }}
														</span>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="card mb-4">
							<div class="card-body">
								<h5 class="card-title text-uppercase mb-4">Shipping Address</h5>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="font-size-11 text-capitalize">Receiver Name :<span class="text-danger">*</span></label>
											<input type="text" name="receiver_name" id="receiver_name" class="form-control no-outline font-size-12" value="{{ old('receiver_name', session('fo_name')) }}" placeholder="Enter receiver name" onkeyup="checkSubmitButton()" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="font-size-11 text-capitalize">Email :<span class="text-danger">*</span></label>
											<input type="email" name="email" id="email" class="form-control no-outline font-size-12" value="{{ old('email', session('fo_email')) }}" placeholder="Enter email" onkeyup="checkSubmitButton()" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="font-size-11 text-capitalize">Phone :<span class="text-danger">*</span></label>
											<input type="text" name="phone" id="phone" class="form-control no-outline font-size-12" value="{{ old('phone', session('fo_phone')) }}" placeholder="Enter phone" onkeyup="checkSubmitButton()" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="font-size-11 text-capitalize">City :<span class="text-danger">*</span></label>
											<select name="city_id" id="city_id" class="form-control no-outline font-size-12" onchange="getDelivery()" required>
												<option value="">-- Choose --</option>
												@foreach($city as $c)
													<option value="{{ $c->id }}">{{ $c->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label class="font-size-11 text-capitalize">Address :<span class="text-danger">*</span></label>
											<textarea name="address" id="address" class="form-control no-outline font-size-12" placeholder="Enter address" onkeyup="checkSubmitButton()" required>{{ old('address') }}</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card mb-4">
							<div class="card-body">
								<h5 class="card-title text-uppercase mb-4">Fleet</h5>
								<div class="form-group">
									<label class="font-size-11 text-capitalize">Transport :<span class="text-danger">*</span></label>
									<select name="delivery_id" id="delivery_id" class="form-control no-outline font-size-12" onchange="grandtotal()">
										<option value="">-- Choose --</option>
									</select>
								</div>
								<div class="form-group">
									<label class="font-size-11 text-capitalize">Important Note :</label>
									<textarea name="description" id="description" class="form-control no-outline font-size-12" placeholder="Enter important note">{{ old('description') }}</textarea>
								</div>
							</div>
						</div>
						<div class="card mb-4">
							<div class="card-body">
								<h5 class="card-title text-uppercase mb-4">Voucher</h5>
								<div class="input-group mb-3">
									<input type="text" name="voucher_id" id="voucher_id" class="form-control font-size-12 text-uppercase" placeholder="Enter voucher code">
									<div class="input-group-append">
										<button type="button" class="btn bg-teal font-size-12 text-uppercase" onclick="grandtotal('voucher')">Claim</button>
									</div>
								</div>
								@if($customer->points > 0)
									<div class="form-group"><hr></div>
									<span class="font-size-14">
										<i class="icon-coins text-warning"></i> Point SMB
									</span>
									<span class="float-right font-size-12">
										<input type="checkbox" id="pointable" class="checkbox-style" name="pointable" onchange="grandtotal()">
										<label for="pointable" class="checkbox-style-1-label checkbox-small text-muted">
											- Rp {{ number_format($customer->points, 0, ',', '.') }}
										</label>
									</span>
								@endif
							</div>
						</div>
						<div class="card mb-5 border-secondary">
							<div class="card-body">
								<h4 class="text-uppercase">Summary</h4>
								<div class="table-responsive">
									<table class="table cart">
										<tbody>
											<tr class="cart_item">
												<td class="cart-product-name">
													<span class="font-size-13 font-weight-normal">Subtotal</span>
												</td>
												<td class="cart-product-name">
													<span class="amount">
														<span class="font-weight-normal font-size-13" id="subtotal">Rp {{ number_format($total_checkout, 0, ',', '.') }}</span>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<span class="font-size-13 font-weight-normal">Type Of Transport</span>
												</td>
												<td class="cart-product-name">
													<span class="amount">
														<span class="font-weight-normal font-size-13" id="transport"></span>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong class="font-size-13 font-weight-normal">Delivery Cost</strong>
												</td>
												<td class="cart-product-name">
													<span class="amount">
														<span class="font-weight-normal font-size-13" id="shipping_fee"></span>
													</span>
												</td>
											</tr>
											<tr class="voucher_claim cart_item" style="display:none;">
												<td class="cart-product-name">
													<span class="font-size-13 font-weight-normal">Discount</span>
												</td>
												<td class="cart-product-name">
													<span class="amount">
														<span class="font-weight-normal font-size-13" id="discount"></span>
													</span>
												</td>
											</tr>
											<tr class="voucher_claim cart_item" style="display:none;">
												<td class="cart-product-name">
													<span class="font-size-13 font-weight-normal">Voucher</span>
												</td>
												<td class="cart-product-name">
													<span class="amount">
														<span class="font-weight-normal font-size-13" id="voucher"></span>
													</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<span class="font-weight-semibold text-uppercase font-size-14">Total</span>
												</td>
												<td class="cart-product-name">
													<span class="amount color lead">
														<span class="font-weight-semibold text-dark font-size-14" id="grandtotal"></span>
													</span>
												</td>
											</tr>
										</tbody>
									</table>
									<div style="display:none;" class="voucher_claim alert font-size-12 font-italic alert-success font-weight-bold" id="voucher_notif"></div>
								</div>
							</div>
						</div>
						<div class="form-group mb-0">
							<div class="row justify-content-center">
								<div class="col-md-2">
									<button type="button" class="cash btn btn-danger text-white col-12 font-size-14 submit_order" onclick="submitOrder('cash')" disabled>Pay At Cashier</button>
								</div>
								<div class="col-md-2">
									<button type="button" class="cashless btn btn-success text-white col-12 font-size-14 submit_order" onclick="submitOrder('cashless')" disabled>Pay Online</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

<script>
	$(function() {
		grandtotal();
	});

	function submitOrder(param) {
		$('#param').val(param);
		$('.submit_order').attr('disabled', true);
		$('.submit_order').html('Processing ...');
		$('.' + param).parents('form').submit();
	}

	function checkSubmitButton() {
		var receiver_name = $('#receiver_name').val();
		var email         = $('#email').val();
		var phone         = $('#phone').val();
		var city_id       = $('#city_id').val();
		var address       = $('#address').val();
		var delivery_id   = $('#delivery_id').val();

		if(receiver_name && email && phone && city_id && address && delivery_id) {
			$('.submit_order').attr('disabled', false);
		} else {
			$('.submit_order').attr('disabled', true);
		}
	}

	function getDelivery() {
		$.ajax({
			url: '{{ url("checkout/process/get_delivery") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            city_id: $('#city_id').val(),
				weight: '{{ $total_weight }}'
         },
         beforeSend: function() {
            loadingOpen('#content');
				$('#delivery_id').html('<option value="">-- Choose --</option>');
         },
         success: function(response) {
				grandtotal();
            loadingClose('#content');
				if($('#city_id').val()) {
					if(response.length > 0) {
						$.each(response, function(i, val) {
							$('#delivery_id').append(`
								<option value="` + val.id + `">(` + val.transport_name + `) &nbsp;&nbsp; ` + val.price + `</option>
							`);
						});
					}
				}
         },
         error: function() {
            loadingClose('#content');
				Swal.fire('Ooppsss', 'Server Error', 'error');
         }
		});
	}

	function grandtotal(param = null) {
		$.ajax({
			url: '{{ url("checkout/process/grandtotal") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            delivery_id: $('#delivery_id').val(),
				weight: '{{ $total_weight }}',
				voucher_id: $('#voucher_id').val(),
				pointable: $('input[name="pointable"]:checked').val(),
				subtotal: '{{ $total_checkout }}'
         },
         beforeSend: function() {
            loadingOpen('#content');
				$('.voucher_claim').hide();
				$('#discount').html('');
				$('#voucher').html('');
				$('#transport').html('Not Selected');
				$('#shipping_fee').html('Rp 0');
				$('#grandtotal').html('Rp ' + '{{ number_format($total_checkout) }}');
         },
         success: function(response) {
				checkSubmitButton();
            loadingClose('#content');

				if(param) {
					if(response.code == 200) {
						$('.voucher_claim').show();
						$('#discount').html(response.discount);
						$('#voucher').html(response.voucher);
						$('#transport').html(response.transport);
						$('#shipping_fee').html(response.shipping_fee);
						$('#grandtotal').html(response.grandtotal);
						$('#voucher_notif').html(response.notification);
					} else {
						$('#transport').html(response.transport);
						$('#shipping_fee').html(response.shipping_fee);
						$('#grandtotal').html(response.grandtotal);
					}

					Swal.fire({
						icon: response.notif_type,
						text: response.notif_text
					});
				} else {
					if($('#voucher_id').val()) {
						$('.voucher_claim').show();
						$('#discount').html(response.discount);
						$('#voucher').html(response.voucher);
					} else {
						$('.voucher_claim').hide();
					}

					$('#transport').html(response.transport);
					$('#shipping_fee').html(response.shipping_fee);
					$('#grandtotal').html(response.grandtotal);
				}
         },
         error: function() {
            loadingClose('#content');
				Swal.fire('Ooppsss', 'Server Error', 'error');
         }
		});
	}
</script>

<script src="{{ asset('template/front-office/js/plugins.min.js') }}"></script>
<script src="{{ asset('template/front-office/js/functions.js') }}"></script>
</body>
</html>