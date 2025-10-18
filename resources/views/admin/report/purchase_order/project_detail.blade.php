<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Detail Purchase Report</span>
				</h4>
			</div>
			<div class="header-elements">
				
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="{{ url('admin/report/purchase_order/project') }}" class="breadcrumb-item">Purchase Order</a>
					<span class="breadcrumb-item active">Project</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
				<h3 class="card-title" id="scrollspy"><b>Project Information - {{ $project->code }}</b></h3>
                  <div class="form-group"><hr></div>
				  <div class="row">
					<div class="col-md-6">
						<dl class="row">
						  <dt class="col-sm-4">Project Name</dt>
						  <dd class="col-sm-8">: {{ $project->project->name }}</dd>
						  <dt class="col-sm-4">Phone</dt>
						  <dd class="col-sm-8">: {{ $project->project->customer->phone }}</dd>
						  <dt class="col-sm-4">Email</dt>
						  <dd class="col-sm-8">: {{ $project->project->customer->email }}</dd>
						  <dt class="col-sm-4">Constructor Name</dt>
						  <dd class="col-sm-8">: {{ $project->project->customer->constructor }}</dd>
						  <dt class="col-sm-4">Country</dt>
						  <dd class="col-sm-8">: {{ $project->project->country->name }}</dd>
						  <dt class="col-sm-4">City</dt>
						  <dd class="col-sm-8">: {{ $project->project->city->name }}</dd>
						  <dt class="col-sm-4">Timeline</dt>
						  <dd class="col-sm-8">: {{ date('d F Y', strtotime($project->project->timeline)) }}</dd>
						  <dt class="col-sm-4">Project Manager</dt>
						  <dd class="col-sm-8">: {{ $project->project->manager }}</dd>
						</dl>
					</div>
					<div class="col-md-6">
						<dl class="row">
						  <dt class="col-sm-4">Consultant Name</dt>
						  <dd class="col-sm-8">: {{ $project->project->consultant }}</dd>
						  <dt class="col-sm-4">Owner</dt>
						  <dd class="col-sm-8">: {!! $project->project->owner !!}</dd>
						  <dt class="col-sm-4">Bank Destination</dt>
						  <dd class="col-sm-8">: {!! $project->project->coa->name !!}</dd>
						  <dt class="col-sm-4">Payment Method</dt>
						  <dd class="col-sm-8">: {!! $project->project->paymentMethod() !!}</dd>
						  <dt class="col-sm-4">Payment Term</dt>
						  <dd class="col-sm-8">: {!! $project->project->paymentTerm() !!}</dd>
						  <dt class="col-sm-4">Supply Method</dt>
						  <dd class="col-sm-8">: {!! $project->project->supplyMethod() !!}</dd>
						  <dt class="col-sm-4">PPN</dt>
						  <dd class="col-sm-8">: {!! $project->project->ppn() !!}</dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
		
		<div class="card">
			<div class="card-body">
				<h3 class="card-title" id="scrollspy"><b>Detail Purchase Order</b></h3>
                  <div class="form-group"><hr></div>
				  <div class="row">
					<div class="col-md-12 table-responsive">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
									<table width="100%">
										<tr>
											<td style="background-color:#0b95b8;text-align:center;color:white;padding-top:10px;padding-bottom:10px;" width="55%">
												<h3><b>PURCHASE ORDER</b></h3>
											</td>
											<td width="5%">
												
											</td>
											<td width="40%" rowspan="6">
												<table style="border: 1px solid black;border-collapse: collapse;" width="100%">
													<tr>
														<td width="40%" style="font-size:10px;">Company Address</td>
														<td style="text-align:left; font-size:10px;">: <b>PT PERWIRA TAMARAYA ABADI
															<br>
														PERGUD. BUMI MASPION IX/E-1, ROMOKALISARI,<br>
														BENOWO, SURABAYA, 60195, INDONESIA</b>									
														</td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">Telp/Fax.</td>
														<td style="text-align:left; font-size:10px;">:  <b>031-547 2860/031-547 8924</b></td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">Taxpayer Id No.</td>
														<td style="text-align:left; font-size:10px;">: <b>02.458.040.9-604.000</b></td>
													</tr>
												</table>
												<br>
												<table style="border: 1px solid black;border-collapse: collapse;" width="100%">
													<tr>
														<td width="40%" style="font-size:10px;">For Delivery Address</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->on_behalf }}</b> </td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">Address</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->delivery_address }}</b> </td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">Courier Method</td>
														<td style="text-align:left; font-size:10px;">: 
															<b>{{ $project->courier_method }}</b>
															
														</td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">Destination</td>
														<td style="text-align:left; font-size:10px;">: 
															<b>{{ $project->city->name.', '.$project->country->name }}</b>
															
														</td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">PIC</td>
														<td style="text-align:left; font-size:10px;">: 
															<b>{{ $project->pic }}</b>
														</td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">PIC Phone No.</td>
														<td style="text-align:left; font-size:10px;">: 
															<b>{{ $project->pic_no }}</b>
														</td>
													</tr>
												</table>
												<br>
												<table style="border: 1px solid black;border-collapse: collapse;" width="100%">
													<tr>
														<td width="40%" style="font-size:10px;">Payment Method</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->payment_method }}</b></td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">Price</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->price() }}</b></td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">Currency</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->currency->code.' '.$project->currency->name }}</b></td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">Brand on Box</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->brand_on_box }}</b></td>
													</tr>
													<tr>
														<td width="40%" style="font-size:10px;">SNI No.</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->sni }}</b></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td width="55%">
												<table style="border: 1px solid black;border-collapse: collapse;" width="100%">
													<tr>
														<td width="30%" style="font-size:10px;">Date of PO</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ date('d F Y', strtotime($project->created_at)) }}</b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">PO No.</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->code }}</b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">Ref SO No.</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->projectSale->code }}</b></td>
													</tr>
												</table>
												<br>
												<table style="border: 1px solid black;border-collapse: collapse;" width="100%">
													<tr>
														<td width="30%" style="font-size:10px;">Customer</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->customer->name }}</b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">Sales</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->sales->name }}</b></td>
													</tr>
												</table>
												<br>
												<table style="border: 1px solid black;border-collapse: collapse;" width="100%">
													<tr class="heading">
														<td colspan="2"><div style="font-size:10px;"><b>Order To :</b></div></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">Supplier</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->supplier->name }} </b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">Address</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->supplier->address }} </b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">PIC</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->supplier->pic }}</b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">PIC Phone No.</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->supplier->phone }}</b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">Production Lead Time</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->production_lead_time }}</b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">Est. Del. Time</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->estimated_delivery }}</b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">Est. Arr. Time</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->estimated_arrival }}</b></td>
													</tr>
													<tr>
														<td width="30%" style="font-size:10px;">Factory Name</td>
														<td style="text-align:left; font-size:10px;">: <b>{{ $project->factory_name }}</b></td>
													</tr>
												</table>
											</td>
											<td width="5%">
												
											</td>
										</tr>
										<tr>
											<td width="55%">
												
												
											</td>
											<td width="5%"></td>
										</tr>
										<tr>
											<td width="55%"></td><td width="5%"></td>
										</tr>
										<tr>
											<td width="55%"></td><td width="5%"></td>
										</tr>
										<tr>
											<td width="55%"></td><td width="5%"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					<div class="col-md-12 table-responsive">
						@php
							$adatile = false;
							$adalain = false;
							$total = 0;
							$totaltile = 0;
							$totallain = 0;
							foreach($project->projectPurchaseProduct as $key => $pp){
								if($pp->product->type->category->parent()->parent()->slug == 'tile'){
									$adatile = true;
								}
								if($pp->product->type->category->parent()->parent()->slug !== 'tile'){
									$adalain = true;
								}
							}
						@endphp
						<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
							<thead>
								<tr style="background:#0b95b8;text-align:center;">
									<th style="color:white;" colspan="13"><center>DETAIL OF ORDER</center></th>
								</tr>
							</thead>
							@php
							if($adatile == true){
								$qtytot = 0;
								$qtym2 = 0;
							@endphp
							<thead>
								<tr style="background:#0b95b8;text-align:center;">
									<th style="color:white;" colspan="13"><center>TILE(S)</center></th>
								</tr>
								<tr style="background:#0b95b8;text-align:center;">
									<th style="color:white;"><center>NO</center></th>
									<th style="color:white;"><center>CODE</center></th>
									<th style="color:white;"><center>PRODUCT</center></th>
									<th style="color:white;"><center>SIZE(cm)</center></th>
									<th style="color:white;"><center>CATEGORY</center></th>
									<th style="color:white;"><center>COLOR</center></th>
									<th style="color:white;"><center>HS CODE</center></th>
									<th style="color:white;"><center>FINISHING</center></th>
									<th style="color:white;" width="5%"><center>QTY TO ORDER</center></th>
									<th style="color:white;"><center>TOTAL(sqm)</center></th>
									<th style="color:white;"><center>PRICE/M<sup>2</sup></center></th>
									<th style="color:white;"><center>TOTAL</center></th>
									<th style="color:white;"><center>CONTAINER</center></th>
								</tr>
							</thead>
							<tbody>
								@foreach($project->projectPurchaseProduct as $key => $pp)
									@php
										if($pp->product->type->category->parent()->parent()->slug == 'tile'){
											$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
											$total += $pp->price * $pp->qty * $m2;
											$totaltile += $pp->price * $m2 * $pp->qty;
											$qtytot += $pp->qty;
											$qtym2 += $pp->qty*$m2;
									@endphp
									<tr>
										<td style="vertical-align:center;">
											<center>
												{{ $key + 1 }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->code }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												<img src="{{ $pp->product->type->image() }}" style="max-width:28px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->category->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->color->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->hsCode->code }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->surface->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->qty }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->qty*$m2 }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ number_format($pp->price, 0, ',', '.') }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ number_format($pp->price * $pp->qty * $m2, 0, ',', '.') }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->containerStandart() }}
											</center>
										</td>
									</tr>
									@php
										}
									@endphp
								@endforeach
								<tr>
									<td colspan="8" style="text-align:right;">Total</td>
									<td style="text-align:center;">{{ $qtytot }}</td>
									<td style="text-align:center;">{{ $qtym2 }}</td>
									<td colspan="3" style="text-align:right;">{{ $project->currency->symbol.' '.number_format($totaltile, 0, ',', '.') }}</td>
								</tr>
							</tbody>
							@php
							}
							if($adatile == true && $adalain == true){
							@endphp
							<thead>
								<tr>
									<th style="color:white;" colspan="13"> ... </th>
								</tr>
							</thead>
							@php
							}
							if($adalain == true){
								$qtytot = 0;
							@endphp
							<thead>
								<tr style="background:#0b95b8;text-align:center;">
									<th style="color:white;" colspan="13"><center>ETC(S)</center></th>
								</tr>
								<tr style="background:#0b95b8;text-align:center;">
									<th style="color:white;"><center>NO</center></th>
									<th style="color:white;"><center>CODE</center></th>
									<th style="color:white;"><center>PRODUCT</center></th>
									<th style="color:white;"><center>SIZE(cm)</center></th>
									<th style="color:white;"><center>CATEGORY</center></th>
									<th style="color:white;"><center>COLOR</center></th>
									<th style="color:white;"><center>HS CODE</center></th>
									<th style="color:white;"><center>FINISHING</center></th>
									<th style="color:white;"><center>QTY TO ORDER</center></th>
									<th style="color:white;"><center>PRICE/PCS</center></th>
									<th style="color:white;" colspan="2"><center>TOTAL</center></th>
									<th style="color:white;"><center>CONTAINER</center></th>
								</tr>
							</thead>
							<tbody>
								@php
									$no = 1;
								@endphp
								@foreach($project->projectPurchaseProduct as $key => $pp)
									@php
										if($pp->product->type->category->parent()->parent()->slug !== 'tile'){
											$total += $pp->price * $pp->qty;
											$totallain += $pp->price * $pp->qty;
											$qtytot += $pp->qty;
									@endphp
									<tr>
										<td style="vertical-align:center;">
											<center>
												{{ $no }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->code }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												<img src="{{ $pp->product->type->image() }}" style="max-width:28px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->category->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->color->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->hsCode->code }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->type->surface->name }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->qty }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ number_format($pp->price, 0, ',', '.') }}
											</center>
										</td>
										<td style="vertical-align:center;" colspan="2">
											<center>
												{{ number_format($pp->price * $pp->qty, 0, ',', '.') }}
											</center>
										</td>
										<td style="vertical-align:center;">
											<center>
												{{ $pp->product->containerStandart() }}
											</center>
										</td>
									</tr>
									@php
											$no++;
										}
									@endphp
								@endforeach
								<tr>
									<td colspan="8" style="text-align:right;">Total</td>
									<td style="text-align:center;">{{ $qtytot }}</td>
									<td style="text-align:center;"></td>
									<td colspan="3" style="text-align:right;">{{ $project->currency->symbol.' '.number_format($totallain, 0, ',', '.') }}</td>
								</tr>
							</tbody>
							@php
							}
							@endphp
						</table>
					</div>
					<div class="col-md-12 table-responsive">
						<h3 class="card-title" id="scrollspy"><b>Proof of Proforma Invoice</b></h3>
                        <div class="form-group"><hr></div>
						<div class="form-group">
							<h5><b>All Proof of Proforma</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>SO No.</th>
									   <th>Date</th>
									   <th>Supplier</th>
									   <th>Warehouse</th>
									   <th>Proof</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectProforma as $pp)
									   <tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pp->projectPurchase->id }}')">{{ $pp->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $pp->projectPurchase->projectSale->code }}</td>
										  <td class="align-middle">{{ $pp->date }}</td>
										  <td class="align-middle">{{ $pp->supplier_name }}</td>
										  <td class="align-middle">{{ $pp->supplier_warehouse }}</td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>