<style>
	.tab-nav.tab-nav2 li {
		margin-left: 0;
		min-width: 250px;
		flex: 1;
	}
</style>
<section id="content">
	<div class="content-wrap" style="padding:40px 0;">
		<div class="container-fluid clearfix" style="width:85% !important;">
			<div class="row clearfix">
				<div class="col-md-12">
					<div style="font-size:30px;"><b>Customer Progress Report</b></div>
					<div class="mb-3" style="margin-top:-5px;">Project No. {{ $project->code }}</div>
					<div>
						<div class="alert alert-dismissible alert-warning">
						  <i class="icon-warning-sign"></i><strong>Disclaimer!</strong> This report may differ from the actual event done by our team. Please contact your salesperson for the latest update.
						</div>
					</div>
					<div class="tabs clearfix" id="tab-6">

						<ul class="tab-nav tab-nav2 clearfix justify-content-center" style="min-width: 250px !important;flex-wrap: wrap;">
							<li>
								<a href="#tabs-1">
									Project Information
									@if($project->progress >= 10)
										<span class="badge rounded-pill bg-success float-right text-white" style="margin-top:13px;">
											10%
										</span>
									@else
										<span class="badge rounded-pill bg-secondary float-right text-white" style="margin-top:13px;">
											<i class="icon-hourglass-half" style="margin-right: 0;"></i>
										</span>
									@endif
								</a>
							</li>
							<li>
								<a href="#tabs-2">
									Quotation
									@if($project->progress >= 25)
										<span class="badge rounded-pill bg-success float-right text-white" style="margin-top:13px;">
											25%
										</span>
									@else
										<span class="badge rounded-pill bg-secondary float-right text-white" style="margin-top:13px;">
											<i class="icon-hourglass-half" style="margin-right: 0;"></i>
										</span>
									@endif
								</a>
							</li>
							<li>
								<a href="#tabs-3">
									Negotiation Log
									@if($project->progress >= 35)
										<span class="badge rounded-pill bg-success float-right text-white" style="margin-top:13px;">
											35%
										</span>
									@else
										<span class="badge rounded-pill bg-secondary float-right text-white" style="margin-top:13px;">
											<i class="icon-hourglass-half" style="margin-right: 0;"></i>
										</span>
									@endif
								</a>
							</li>
							<li>
								<a href="#tabs-4">
									Sales Order
									@if($project->progress >= 37)
										<span class="badge rounded-pill bg-success float-right text-white" style="margin-top:13px;">
											37%
										</span>
									@else
										<span class="badge rounded-pill bg-secondary float-right text-white" style="margin-top:13px;">
											<i class="icon-hourglass-half" style="margin-right: 0;"></i>
										</span>
									@endif
								</a>
							</li>
							<li>
								<a href="#tabs-5">
									Product Tracking
									@if($project->progress >= 65)
										<span class="badge rounded-pill bg-success float-right text-white" style="margin-top:13px;">
											65%
										</span>
									@else
										<span class="badge rounded-pill bg-secondary float-right text-white" style="margin-top:13px;">
											<i class="icon-hourglass-half" style="margin-right: 0;"></i>
										</span>
									@endif
								</a>
							</li>
							<li>
								<a href="#tabs-7">
									Received by Customer
									@if(count($project->projectDelivery->whereNotNull('received_date')) > 0)
										<span class="badge rounded-pill bg-success float-right text-white" style="margin-top:13px;">
											85%
										</span>
									@else
										<span class="badge rounded-pill bg-secondary float-right text-white" style="margin-top:13px;">
											<i class="icon-hourglass-half" style="margin-right: 0;"></i>
										</span>
									@endif
								</a>
							</li>
							<li>
								<a href="#tabs-8">
									Payments
									@if(count($project->projectPay) > 0)
										<span class="badge rounded-pill bg-success float-right text-white" style="margin-top:13px;">
											100%
										</span>
									@else
										<span class="badge rounded-pill bg-secondary float-right text-white" style="margin-top:13px;">
											<i class="icon-hourglass-half" style="margin-right: 0;"></i>
										</span>
									@endif
								</a>
							</li>
							<li>
								<a href="#tabs-9">
									Returns
									@if(count($project->projectSaleReturn) > 0)
										<span class="badge rounded-pill bg-success float-right text-white" style="margin-top:13px;">
											<i class="icon-line-check-circle" style="margin-right: 0;"></i>
										</span>
									@else
										<span class="badge rounded-pill bg-secondary float-right text-white" style="margin-top:13px;">
											<i class="icon-line-ban" style="margin-right: 0;"></i>
										</span>
									@endif
								</a>
							</li>
						</ul>

						<div class="tab-container">

							<div class="tab-content clearfix" id="tabs-1">
								<div class="row">
									<div class="col-md-6">
										<ul class="list-group list-group-flush">
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Project Name:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->name) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Phone:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->customer->phone) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Email:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->customer->email) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Constructor Name:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->customer->constructor) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Country:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->country->name) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">City:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->city->name) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Timeline:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper(date('d F Y', strtotime($project->timeline))) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">PIC:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->manager) }}</span>
											</li>
										</ul>
									</div>
									<div class="col-md-6">
										<ul class="list-group list-group-flush">
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Consultant Name:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->consultant) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Owner:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->owner) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Bank Destination:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->coa->name) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Payment Method:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->paymentTerm()) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">Supply Method:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->supplyMethod()) }}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center px-0 font-size-13">
												<span class="text-muted">PPN:</span>
												<span class="text-dark font-weight-semibold">{{ strtoupper($project->ppn()) }}</span>
											</li>
										</ul>
									</div>
								</div>
								
							</div>
							<div class="tab-content" id="tabs-2">
								<div class="row">
									<div class="col-lg-12">
										@if(count($project->projectQuotation) > 0)
										<div class="table-responsive">
											@php
												if(date('Y-m-d',strtotime($project->timeline)) < '2022-04-01'){
													$persenppn = 0.1;
													$ppnpembagi = 1.1;
												}else{
													$persenppn = 0.11;
													$ppnpembagi = 1.11;
												}
											
												$adatile = false;
												$adalain = false;
												$total = 0;
												$totaltile = 0;
												$totallain = 0;
												foreach($project->projectProduct as $key => $pp){
													if($pp->unit == '2' || $pp->unit == '3'){
														$adatile = true;
													}
													if($pp->unit == '1' || $pp->unit == '4'){
														$adalain = true;
													}
												}
											@endphp

											<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
												@php
												if($adatile == true){
												@endphp
												<thead>
													@php
														if($adalain == true){
													@endphp
														<tr style="background:#51b6bc;text-align:center;">
															<th style="color:white;" colspan="12"><center>TILES</center></th>
														</tr>
													@php
														}
													@endphp
													<tr style="background:#51b6bc;text-align:center;">
														<th style="color:white;" rowspan="2"><center>NO</center></th>
														<th style="color:white;" rowspan="2"><center>AREA</center></th>
														<th style="color:white;" rowspan="2"><center>CODE</center></th>
														<th style="color:white;" rowspan="2"><center>BRAND</center></th>
														<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
														<th style="color:white;" colspan="2"><center>QTY</center></th>
														<th style="color:white;" colspan="2"><center>PRICE LIST (BEFORE TAX)</center></th>
														<th style="color:white;" colspan="2"><center>PROJECT PRICE (BEFORE TAX)</center></th>
														<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
													</tr>
													<tr style="background:#51b6bc;text-align:center;">
														<th style="color:white;">(M<sup>2</sup>)</th>
														<th style="color:white;">(BOX)</th>
														<th style="color:white;">(M<sup>2</sup>)</th>
														<th style="color:white;">(BOX)</th>
														<th style="color:white;">(M<sup>2</sup>)</th>
														<th style="color:white;">(BOX)</th>
													</th>
												</thead>
												<tbody>
													@foreach($project->projectProduct()->orderBy('id')->get() as $key => $pp)
														@php
															if($pp->unit == '2' || $pp->unit == '3'){
																$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
																if($m2 < 1.1){
																	$countbox = $pp->qty;
																	$pp->best_price == 0 ? $total += $pp->price * $countbox : $total += $pp->best_price * $countbox;
																	$pp->best_price == 0 ? $totaltile += $pp->price * $countbox : $totaltile += $pp->best_price * $countbox;
																}else{
																	$countbox = ceil(round($pp->qty / $m2,2));
																	$pp->best_price == 0 ? $total += $pp->price * $m2 * $countbox : $total += $pp->best_price * $m2 * $countbox;
																	$pp->best_price == 0 ? $totaltile += $pp->price * $m2 * $countbox : $totaltile += $pp->best_price * $m2 * $countbox;
																}
														@endphp
														<tr>
															<td style="vertical-align:center;">
																<center>
																	
																	{{ $key + 1 }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->area }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->product->type->code }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->product->brand->name }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->product->type->length }}x{{ $pp->product->type->width }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->qty }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $countbox }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	@php
																		$main = $project->lastQuotation();
																		foreach($main->projectQuotationProduct as $pqp){
																			if($pqp->product_id == $pp->product_id){
																				echo number_format($pqp->price, 0, ',', '.');
																			}
																		}
																	@endphp
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	@php
																		$main = $project->lastQuotation();
																		foreach($main->projectQuotationProduct as $pqp){
																			if($pqp->product_id == $pp->product_id){
																				echo number_format($pqp->price * $m2, 0, ',', '.');
																			}
																		}
																	@endphp
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	@php
																		if(count($project->projectQuotation) > 1){
																			foreach($project->projectQuotation as $pq2){
																				foreach($pq2->projectQuotationProduct as $pqp2){
																					if($pqp2->product_id == $pp->product_id){
																						
																						if($pq2->projectQuotationProduct()->latest()->first()->id == $pqp->id){
																							if($pqp2->best_price == 0){
																								echo number_format($pqp2->price, 0, ',', '.').'<br>';
																							}else{
																								echo number_format($pqp2->best_price, 0, ',', '.').'<br>';
																							}
																						}else{
																							if($pp->best_price !== $pqp2->best_price){
																								echo '<span style="color:red;text-decoration:line-through;"><span style="color:black">'.number_format($pqp2->best_price, 0, ',', '.').'</span></span><br>';
																							}
																						}
																						
																					}
																				}
																			}
																		}else{
																			foreach($project->projectQuotation as $pq2){
																				foreach($pq2->projectQuotationProduct as $pqp2){
																					if($pqp2->product_id == $pp->product_id){
																						if($pqp2->best_price == 0){
																							echo number_format($pqp2->price, 0, ',', '.').'<br>';
																						}else{
																							echo number_format($pqp2->best_price, 0, ',', '.').'<br>';
																						}
																					}
																				}
																			}
																		}
																	@endphp
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	@php
																		if($m2 < 1.1){
																			echo $pp->best_price == 0 ? number_format($pp->price, 0, ',', '.') : number_format($pp->best_price, 0, ',', '.');
																		}else{
																			echo $pp->best_price == 0 ? number_format($pp->price * $m2, 0, ',', '.') : number_format($pp->best_price * $m2, 0, ',', '.');
																		}
																		
																	@endphp
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	@php
																		if($m2 < 1.1){
																			echo $pp->best_price == 0 ? number_format($pp->price * $countbox, 0, ',', '.') : number_format($pp->best_price * $countbox, 0, ',', '.');
																		}else{
																			echo $pp->best_price == 0 ? number_format($pp->price * $m2 * $countbox, 0, ',', '.') : number_format($pp->best_price * $m2 * $countbox, 0, ',', '.');
																		}
																	@endphp
																</center>
															</td>
														</tr>
														@php
															}
														@endphp
													@endforeach
													<tr>
														<th colspan="9"></th>
														<th colspan="2" class="text-right">Subtotal</th>
														<th colspan="2" class="text-right">{{ number_format($totaltile, 0, ',', '.') }}</th>
													</tr>
												</tbody>
												@php
												}
												if($adalain == true){
												@endphp
												<thead>
													@php
														if($adatile == true){
													@endphp
														<tr style="background:#51b6bc;text-align:center;">
															<th style="color:white;" colspan="17"><center>NON-TILES</center></th>
														</tr>
													@php
														}
													@endphp
													<tr style="background:#51b6bc;text-align:center;">
														<th style="color:white;" rowspan="2"><center>NO</center></th>
														<th style="color:white;" rowspan="2"><center>AREA</center></th>
														<th style="color:white;" rowspan="2"><center>CODE</center></th>
														<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
														<th style="color:white;" rowspan="2"><center>BRAND</center></th>
														<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
														<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
														<th style="color:white;" rowspan="2"><center>COLOR</center></th>
														<th style="color:white;" colspan="2" rowspan="2"><center>SPEC</center></th>
														<th style="color:white;" colspan="2"><center>QTY</center></th>
														<th style="color:white;" colspan="2"><center>PRICE LIST (BEFORE TAX)</center></th>
														<th style="color:white;" colspan="2"><center>PROJECT PRICE (BEFORE TAX)</center></th>
														<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
													</tr>
													<tr style="background:#51b6bc;text-align:center;">
														<th style="color:white;" colspan="2">PCS</th>
														<th style="color:white;" colspan="2">PCS</th>
														<th style="color:white;" colspan="2">PCS</th>
													</tr>
												</thead>
												<tbody>
													@php
														$no = 1;
													@endphp
													@foreach($project->projectProduct()->orderBy('id')->get() as $key => $pp)
														@php
															if($pp->unit == '1' || $pp->unit == '4'){
																$pp->best_price == 0 ? $total += $pp->price * $pp->qty : $total += $pp->best_price * $pp->qty;
																$pp->best_price == 0 ? $totallain += $pp->price * $pp->qty : $totallain += $pp->best_price * $pp->qty;
														@endphp
														<tr>
															<td style="vertical-align:center;">
																<center>
																	{{ $no }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->area }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->product->type->code }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	<img src="{!! $pp->product->type->image() !!}" style="max-width:20px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->product->brand->name }}
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
															<td style="vertical-align:center;" colspan="2">
																<center>
																	{{ $pp->spec }}
																</center>
															</td>
															<td style="vertical-align:center;" colspan="2">
																<center>
																	{{ $pp->qty }}
																</center>
															</td>
															<td style="vertical-align:center;" colspan="2">
																<center>
																	@php
																		$main = $project->lastQuotation();
																		foreach($main->projectQuotationProduct as $pqp){
																			if($pqp->product_id == $pp->product_id){
																				echo number_format($pqp->price, 0, ',', '.');
																			}
																		}
																	@endphp
																</center>
															</td>
															<td style="vertical-align:center;" colspan="2">
																<center>
																	@php
																		if(count($project->projectQuotation) > 1){
																			foreach($project->projectQuotation as $pq2){
																				foreach($pq2->projectQuotationProduct as $pqp2){
																					if($pqp2->product_id == $pp->product_id){
																						if($pq2->projectQuotationProduct()->latest()->first()->id == $pqp->id){
																							if($pqp2->best_price == 0){
																								echo number_format($pqp2->price, 0, ',', '.').'<br>';
																							}else{
																								echo number_format($pqp2->best_price, 0, ',', '.').'<br>';
																							}
																						}else{
																							if($pp->best_price !== $pqp2->best_price){
																								echo '<span style="color:red;text-decoration:line-through;"><span style="color:black">'.number_format($pqp2->best_price, 0, ',', '.').'</span></span><br>';
																							}
																						}
																					}
																				}
																			}
																		}else{
																			foreach($project->projectQuotation as $pq2){
																				foreach($pq2->projectQuotationProduct as $pqp2){
																					if($pqp2->product_id == $pp->product_id){
																						if($pqp2->best_price == 0){
																							echo number_format($pqp2->price, 0, ',', '.').'<br>';
																						}else{
																							echo number_format($pqp2->best_price, 0, ',', '.').'<br>';
																						}
																					}
																				}
																			}
																		}
																	@endphp
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->best_price == 0 ? number_format($pp->price * $pp->qty, 0, ',', '.') : number_format($pp->best_price * $pp->qty, 0, ',', '.') }}
																</center>
															</td>
														</tr>
														@php
																$no++;
															}
														@endphp
													@endforeach
													<tr>
														<th colspan="14"></th>
														<th colspan="2" class="text-right">Subtotal</th>
														<th colspan="2" class="text-right">{{ number_format($totallain, 0, ',', '.') }}</th>
													</tr>
												</tbody>
												@php
												}
												@endphp
											</table>
											<br>
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td class="text-right" width="100%">
														<table class="table table-bordered" cellspacing="0" border="1">
															<tr>
																<td>
																	SUBTOTAL
																</td>
																<td>
																	IDR {{ number_format($total, 0, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	CUTTING COST
																</td>
																<td>
																	IDR {{ number_format($project->cutting_cost, 0, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	MISC COST
																</td>
																<td>
																	IDR {{ number_format($project->misc_cost, 0, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	DISCOUNT
																</td>
																<td>
																	IDR {{ number_format($project->discount, 0, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	TOTAL AFTER DISCOUNT
																</td>
																<td>
																	IDR {{ number_format($total + $project->misc_cost + $project->cutting_cost - $project->discount, 0, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	TAX
																</td>
																<td>
																	IDR {{ $project->ppn == '1' ? number_format(round($persenppn * ($total + $project->misc_cost + $project->cutting_cost - $project->discount)), 0, ',', '.') : number_format(0, 0, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	TOTAL
																</td>
																<td>
																	IDR {{ $project->ppn == '1' ? number_format(($total + $project->misc_cost + $project->cutting_cost - $project->discount) + round($persenppn * ($total + $project->misc_cost + $project->cutting_cost - $project->discount)), 0, ',', '.') : number_format($total + $project->misc_cost + $project->cutting_cost - $project->discount, 0, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	DELIVERY COST
																</td>
																<td>
																	IDR {{ number_format($project->delivery_cost, 0, ',', '.') }}
																</td>
															</tr>
															
															<tr>
																<td>
																	GRANDTOTAL
																</td>
																<td>
																	IDR {{ $project->ppn == '1' ? number_format(($total + $project->misc_cost + $project->cutting_cost - $project->discount) + round($persenppn * ($total + $project->misc_cost + $project->cutting_cost - $project->discount)) + $project->delivery_cost, 0, ',', '.') : number_format($total + $project->misc_cost + $project->cutting_cost - $project->discount + $project->delivery_cost, 0, ',', '.') }}
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table><br>
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td style="text-align:center;" width="33%">
														<div style="font-size:10px;">Created By</div>
														@if($project->user->sign)
															<div><img src="{{ asset(Storage::url($project->user->sign)) }}" height="65px"></div>
														@else
															<br>
														@endif
														<div style="font-size:10px;">{{ $project->user->name }}</div>
														<div style="font-size:10px;">{{ $project->user->userRole->first()->role() }}</div>
													</td>
													@if(isset($project->getApprovalQuotation()->approved_1->name))
													<td style="text-align:center;" width="33%">
														<div style="font-size:10px;">Approved By</div>
														@if($project->getApprovalQuotation()->approved_1->sign)
															<div><img src="{{ asset(Storage::url($project->getApprovalQuotation()->approved_1->sign)) }}" height="65px"></div>
														@else
															<br>
														@endif
														<div style="font-size:10px;">{{ $project->getApprovalQuotation()->approved_1->name }}</div>
														<div style="font-size:10px;">{{ $project->getApprovalQuotation()->approved_1->userRole->first()->role() }}</div>
													</td>
													@endif
													@if(isset($project->getApprovalQuotation()->approved_2->name))
													<td style="text-align:center;" width="33%">
														<div style="font-size:10px;">Approved By</div>
														@if($project->getApprovalQuotation()->approved_2->sign)
															<div><img src="{{ asset(Storage::url($project->getApprovalQuotation()->approved_2->sign)) }}" height="65px"></div>
														@else
															<br>
														@endif
														<div style="font-size:10px;">{{ $project->getApprovalQuotation()->approved_2->name }}</div>
														<div style="font-size:10px;">{{ $project->getApprovalQuotation()->approved_2->userRole->first()->role() }}</div>
													</td>
													@endif
												</tr>
											</table><br>
										</div>
										@else
											<div class="style-msg errormsg text-center">
												<div class="sb-msg"><i class="icon-remove"></i><strong>Sorry!</strong> This project hasn't reached 25% yet.</div>
											</div>
										@endif
									</div>
								</div>
								
							</div>
							<div class="tab-content clearfix" id="tabs-3">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											 <table class="table table-bordered table-striped">
												<thead class="table-secondary">
												   <tr class="text-center">
													  <th>Date</th>
													  <th>Person</th>
													  <th>Result</th>
												   </tr>
												</thead>
												<tbody id="data_negotiation">
													@foreach($project->projectNegotiation as $pn)
														<tr class="text-center">
														   <td class="align-middle">{{ $pn->date }}</td>   
														   <td class="align-middle">{{ $pn->person }}</td>   
														   <td class="align-middle">{{ $pn->result }}</td>
														</tr>
													@endforeach
													@if(count($project->projectNegotiation) == 0)
														<tr class="text-center">
														   <td class="align-middle bg-warning" colspan="3">None</td>
														</tr>
													@endif
												</tbody>
											 </table>
										  </div>
									</div>
								</div>
							</div>
							<div class="tab-content clearfix" id="tabs-4">
								<div class="row">
									<div class="col-md-12">
										@if(count($project->projectSale) > 0)
										<div class="table-responsive">
											@php
												$projectsale = $project->projectSale->first();
												
												if(date('Y-m-d',strtotime($projectsale->created_at)) < '2022-04-01'){
													$persenppn = 0.1;
													$ppnpembagi = 1.1;
												}else{
													$persenppn = 0.11;
													$ppnpembagi = 1.11;
												}
												
												$koma = 0;
												
												if($projectsale->currency_id !== '5'){
													$koma = 2;
												}
											
												$adatile = false;
												$adalain = false;
												$total = 0;
												$totaltile = 0;
												$totallain = 0;
												foreach($projectsale->projectSaleProduct as $key => $pp){
													if($pp->unit == '2' || $pp->unit == '3'){
														$adatile = true;
													}
													if($pp->unit == '1' || $pp->unit == '4'){
														$adalain = true;
													}
												}
											@endphp

											<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
												@php
												$no = 1;
												if($adatile == true){
												@endphp
												<thead>
													@php
														if($adalain == true){
													@endphp
														<tr style="background:#ebb220;text-align:center;">
															<th style="color:white;" colspan="12"><center>TILES</center></th>
														</tr>
													@php
														}
													@endphp
													<tr style="background:#ebb220;text-align:center;">
														<th style="color:white;" rowspan="2"><center>NO</center></th>
														<th style="color:white;" rowspan="2"><center>CODE</center></th>
														<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
														<th style="color:white;" rowspan="2"><center>BRAND</center></th>
														<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
														<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
														<th style="color:white;" rowspan="2"><center>COLOR</center></th>
														<th style="color:white;" colspan="2"><center>QTY</center></th>
														<th style="color:white;" colspan="2"><center>PRICE (BEFORE TAX)</center></th>
														<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
													</tr>
													<tr style="background:#ebb220;text-align:center;">
														<th style="color:white;">(M<sup>2</sup>)</th>
														<th style="color:white;">(BOX)</th>
														<th style="color:white;">(M<sup>2</sup>)</th>
														<th style="color:white;">(BOX)</th>
													</th>
												</thead>
												<tbody>
													@foreach($projectsale->projectSaleProduct as $key => $pp)
														@php
															if($pp->unit == '2' || $pp->unit == '3'){
																$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
																
																if($m2 < 1.1){
																	$countbox = $pp->qty;
																	$total += ($pp->best_price * $countbox) / $projectsale->currency_rate;
																	$totaltile += ($pp->best_price * $countbox) / $projectsale->currency_rate;
																}else{
																	$countbox = ceil(round($pp->qty / $m2,2));
																	$total += ($pp->best_price * $m2 * $countbox) / $projectsale->currency_rate;
																	$totaltile += ($pp->best_price * $m2 * $countbox) / $projectsale->currency_rate;
																}
																
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
																	<img src="{{ $pp->product->type->image() }}" style="max-width:15px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->product->brand->name }}
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
																	{{ $countbox * $m2 }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $countbox }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $m2 < 1.1 ? number_format((($pp->best_price * $countbox) / ($countbox * $m2)) / $projectsale->currency_rate, 2, ',', '.') : number_format((($pp->best_price * $m2 * $countbox) / ($countbox * $m2)) / $projectsale->currency_rate, $koma, ',', '.') }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	@php
																		if($m2 < 1.1){
																			echo number_format($pp->best_price / $projectsale->currency_rate, $koma, ',', '.');
																		}else{
																			echo number_format(($pp->best_price * $m2) / $projectsale->currency_rate, $koma, ',', '.');
																		}
																		
																	@endphp
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	@php 
																		if($m2 < 1.1){
																			echo number_format(($pp->best_price * $countbox) / $projectsale->currency_rate, $koma, ',', '.');
																		}else{
																			echo number_format(($pp->best_price * $m2 * $countbox) / $projectsale->currency_rate, $koma, ',', '.');
																		}
																	@endphp
																</center>
															</td>
														</tr>
														@php
																$no++;
															}
														@endphp
													@endforeach
													<tr>
														<th colspan="9"></th>
														<th colspan="2">Subtotal</th>
														<th colspan="1">{{ number_format($totaltile, $koma, ',', '.') }}</th>
													</tr>
												</tbody>
												@php
												}
												if($adalain == true){
												@endphp
												<thead>
													@php
														if($adatile == true){
													@endphp
														<tr style="background:#ebb220;text-align:center;">
															<th style="color:white;" colspan="12"><center>NON-TILES</center></th>
														</tr>
													@php
														}
													@endphp
													<tr style="background:#ebb220;text-align:center;">
														<th style="color:white;" rowspan="2"><center>NO</center></th>
														<th style="color:white;" rowspan="2"><center>CODE</center></th>
														<th style="color:white;" rowspan="2"><center>PICTURE</center></th>
														<th style="color:white;" rowspan="2"><center>BRAND</center></th>
														<th style="color:white;" rowspan="2"><center>SIZE(cm)</center></th>
														<th style="color:white;" rowspan="2"><center>CATEGORY</center></th>
														<th style="color:white;" rowspan="2"><center>COLOR</center></th>
														<th style="color:white;" rowspan="2"><center>SPEC</center></th>
														<th style="color:white;" rowspan="2"><center>QTY</center></th>
														<th style="color:white;" colspan="2"><center>PRICE (BEFORE TAX)</center></th>
														<th style="color:white;" rowspan="2"><center>TOTAL</center></th>
													</tr>
													<tr style="background:#ebb220;text-align:center;">
														<th style="color:white;" colspan="2">PCS</th>
													</th>
												</thead>
												<tbody>
													@foreach($projectsale->projectSaleProduct as $key => $pp)
														@php
															if($pp->unit == '1' || $pp->unit == '4'){
																$total += ($pp->best_price * $pp->qty) / $projectsale->currency_rate;
																$totallain += ($pp->best_price * $pp->qty) / $projectsale->currency_rate;
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
																	<img src="{{ $pp->product->type->image() }}" style="max-width:15px; border:1px solid #ddd; border-radius:4px; padding: 5px;" class="img-fluid img-thumbnail">
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->product->brand->name }}
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
																	{{ $pp->spec }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ $pp->qty }}
																</center>
															</td>
															<td style="vertical-align:center;" colspan="2">
																<center>
																	{{ number_format($pp->best_price / $projectsale->currency_rate, $koma, ',', '.') }}
																</center>
															</td>
															<td style="vertical-align:center;">
																<center>
																	{{ number_format(($pp->best_price * $pp->qty) / $projectsale->currency_rate, $koma, ',', '.') }}
																</center>
															</td>
														</tr>
														@php
																$no++;
															}
														@endphp
													@endforeach
													<tr>
														<th colspan="9"></th>
														<th colspan="2" class="text-right">Subtotal</th>
														<th colspan="1" class="text-right">{{ number_format($totallain, $koma, ',', '.') }}</th>
													</tr>
												</tbody>
												@php
												}
												@endphp
											</table>
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td class="text-right" width="100%">
														<table class="table table-bordered" cellpadding="2" cellspacing="0" border="1" width="100%">
															<tr>
																<td>
																	SUBTOTAL
																</td>
																<td>
																	{{ $projectsale->currency->code }} {{ number_format($total, $koma, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	DISCOUNT
																</td>
																<td>
																	{{ $projectsale->currency->code }} {{ number_format($projectsale->project->discount / $projectsale->currency_rate, $koma, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	TOTAL AFTER DISCOUNT
																</td>
																<td>
																	{{ $projectsale->currency->code }} {{ number_format(($projectsale->project->discount ? ($total - ($projectsale->project->discount / $projectsale->currency_rate)) : $total), $koma, ',', '.') }}
																</td>
															</tr>
															<tr>
																<td>
																	TAX
																</td>
																<td>
																	{{ $projectsale->currency->code }} {{ $projectsale->project->ppn == '1' ? number_format($persenppn * ($total - ($projectsale->project->discount / $projectsale->currency_rate)), $koma, ',', '.') : 0 }}
																</td>
															</tr>
															<tr>
																<td>
																	GRANDTOTAL
																</td>
																<td>
																	<b>{{ $projectsale->currency->code }} {{ $projectsale->project->ppn == '1' ? number_format(($total - ($projectsale->project->discount / $projectsale->currency_rate)) + ($persenppn * ($total - ($projectsale->project->discount / $projectsale->currency_rate))), $koma, ',', '.') : number_format(($total - ($projectsale->project->discount / $projectsale->currency_rate)), $koma, ',', '.') }}
																</td>
															</tr>
														</table>
													</td>
												</tr>
												
											</table>
											<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													@if(isset($projectsale->approved->name))
													<td style="text-align:center;" width="33%">
														<div style="font-size:10px;">Acknowledged By,</div>
														@if(isset($projectsale->approved->sign))
															<div><img src="{{ asset(Storage::url($projectsale->approved->sign)) }}" height="65px"></div>
														@else
															<br><br><br>
														@endif
														<div style="font-size:10px;">{{ $projectsale->approved->name }}</div>
														<div style="font-size:10px;">{{ $projectsale->approved->userRole->first()->role() }}</div>
													</td>
													@endif
													@if(isset($projectsale->marketing->name))
													<td style="text-align:center;" width="33%">
														<div style="font-size:10px;">Marketing,</div>
														@if(isset($projectsale->marketing->sign))
															<div><img src="{{ asset(Storage::url($projectsale->marketing->sign)) }}" height="65px"></div>
														@else
															<br><br><br>
														@endif
														<div style="font-size:10px;">{{ $projectsale->marketing->name }}</div>
														<div style="font-size:10px;">{{ $projectsale->marketing->userRole->first()->role() }}</div>
													</td>
													@endif
													<td style="text-align:center;" width="33%">
														<div style="font-size:10px;">Created By</div>
														@if(isset($projectsale->user->sign))
															<div><img src="{{ asset(Storage::url($projectsale->user->sign)) }}" height="65px"></div>
														@else
															<br><br><br>
														@endif
														<div style="font-size:10px;">{{ $projectsale->user->name }}</div>
														<div style="font-size:10px;">{{ $projectsale->user->userRole->first()->role() }}</div>
													</td>
												</tr>
											</table>
										</div>
										@else
											<div class="style-msg errormsg text-center">
												<div class="sb-msg"><i class="icon-remove"></i><strong>Sorry!</strong> This project hasn't reached 37% yet.</div>
											</div>
										@endif
									</div>
								</div>
							</div>
							<div class="tab-content clearfix" id="tabs-5">
								@if($project->progress >= 65)
									@if(count($project->projectWarehouse) > 0)
										@foreach($project->projectWarehouse as $warehouse)
											<div class="list-feed-item border-warning-400">
												<span class="badge badge-success">{{ $warehouse->date_receive }}</span> : Products received by mr/mrs <b>{{ $warehouse->person }}</b> in Our Warehouse.
											</div>
										@endforeach
									@endif
									@if(count($project->projectDelivery) > 0)
										@foreach($project->projectDelivery as $delivery)
											@if($delivery->received_date)
											<div class="list-feed-item border-warning-400">
												<span class="badge badge-success">{{ $delivery->updated_at }}</span> : Received by mr/mrs <b>{{ $delivery->receiver_name }} at {{ $delivery->received_date }} with details No. {{ $delivery->code }}</b>.
											</div>
											@endif
										@endforeach
									@endif
								@else
									<div class="alert alert-success">
										<i class="icon-spinner1"></i><strong>Well done!</strong> Please wait we are preparing your products.
									</div>
									<img src="{{ url('website/under_production.gif') }}" width="350px">
								@endif
							</div>
							<div class="tab-content clearfix" id="tabs-7">
								<div class="row">
									<div class="col-md-12">
										@if(count($project->projectDelivery->whereNotNull('received_date')) > 0)
											<div class="table-responsive">
												@foreach($project->projectDelivery->whereNotNull('received_date') as $projectdelivery)
												@php
													$adatile = false;
													$adalain = false;
													$total = 0;
													$totaltile = 0;
													$totallain = 0;
													foreach($projectdelivery->projectDeliveryProduct as $key => $pp){
														if($pp->unit == '2' || $pp->unit == '3'){
															$adatile = true;
														}
														if($pp->unit == '1' || $pp->unit == '4'){
															$adalain = true;
														}
													}
												@endphp
												<table class="table-table-bordered" border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
													<thead>
														<tr style="background:#cf9604;text-align:center;">
															<th style="color:white;" colspan="8"><center style="font-size:15px;">{{ $projectdelivery->code }}</center></th>
														</tr>
													</thead>
													@php
													$no = 1;
													if($adatile == true){
														$qtytot = 0;
														$qtym2 = 0;
													@endphp
													<thead>
														<tr style="background:#cf9604;text-align:center;">
															<th style="color:white;" colspan="8"><center>TILES</center></th>
														</tr>
														<tr style="background:#cf9604;text-align:center;">
															<th style="color:white;"><center>NO</center></th>
															<th style="color:white;"><center>NAME</center></th>
															<th style="color:white;"><center>BRAND</center></th>
															<th style="color:white;"><center>FINISHING</center></th>
															<th style="color:white;"><center>SHADE</center></th>
															<th style="color:white;"><center>SIZE(cm)</center></th>
															<th style="color:white;"><center>PCS/BOX</center></th>
															<th style="color:white;"><center>QTY DELIVERED</center></th>
														</tr>
													</thead>
													<tbody>
														@foreach($projectdelivery->projectDeliveryProduct as $key => $ps)
															@php
																if($ps->unit == '2' || $ps->unit == '3'){
															@endphp
															<tr>
																<td style="vertical-align:center;">
																	<center>
																		{{ $no }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->name() }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->brand->name }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->type->surface->name }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																		{{ $ps->shading }}
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->type->length }}x{{ $ps->product->type->width }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->carton_pcs }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ round($ps->qty,0).' '.$ps->unit() }}
																	</center>
																</td>
															</tr>
															@php
																	$no++;
																}
															@endphp
														@endforeach
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
														<tr style="background:#cf9604;text-align:center;">
															<th style="color:white;" colspan="8"><center>NON-TILES</center></th>
														</tr>
														<tr style="background:#cf9604;text-align:center;">
															<th style="color:white;"><center>NO</center></th>
															<th style="color:white;"><center>NAME</center></th>
															<th style="color:white;"><center>BRAND</center></th>
															<th style="color:white;"><center>FINISHING</center></th>
															<th style="color:white;"><center>SHADE</center></th>
															<th style="color:white;"><center>SIZE(mm)</center></th>
															<th style="color:white;"><center>PCS/BOX</center></th>
															<th style="color:white;"><center>QTY DELIVERED</center></th>
														</tr>
													</thead>
													<tbody>
														@foreach($projectdelivery->projectDeliveryProduct as $key => $ps)
															@php
																if($ps->unit == '1' || $ps->unit == '4'){
															@endphp
															<tr>
																<td style="vertical-align:center;">
																	<center>
																		{{ $no }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->name() }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->brand->name }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->type->surface->name }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																		{{ $ps->shading }}
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->type->length }}x{{ $ps->product->type->width }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $ps->product->carton_pcs }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ round($ps->qty,0).' '.$ps->unit() }}
																	</center>
																</td>
															</tr>
															@php
																	$no++;
																}
															@endphp
														@endforeach
													</tbody>
													@php
													}
													@endphp
												</table>
											</div>
											@endforeach
										@else
											<div class="style-msg errormsg text-center">
												<div class="sb-msg"><i class="icon-remove"></i><strong>Sorry!</strong> This project hasn't reached 85% or doesn't have any received by customer yet.</div>
											</div>
										@endif
									</div>
								</div>
							</div>
							<!-- <div class="tab-content clearfix" id="tabs-8">
								<div class="row">
									<div class="col-md-12">
										@if(count($project->projectPay) > 0)
											<div class="table-responsive">
											  <table class="table table-bordered" style="width:100%; font-size:10px;">
												 <thead class="table-secondary">
													<tr class="text-center">
													   <th>SO Code</th>
													   <th>Invoice</th>
													   <th>Date</th>
													   <th>Nominal</th>
													   <th>Note</th>
													</tr>
												 </thead>
												 <tbody>
													@foreach($project->projectPay()->orderBy('project_sale_id')->get() as $pp)
													   <tr class="text-center">
														  <td class="align-middle">{{ $pp->projectSale->code }}</td>
														  <td class="align-middle">{{ $pp->code }}</td>
														  <td class="align-middle">{{ $pp->date }}</td>
														  <td class="align-middle">IDR {{ number_format($pp->nominal,2,',','.') }}</td>
														  <td class="align-middle">{{ $pp->note }}</td>
													   </tr>
													@endforeach
												 </tbody>
											  </table>
										   </div>
										@else
											<div class="style-msg errormsg text-center">
												<div class="sb-msg"><i class="icon-remove"></i><strong>Sorry!</strong> This project hasn't reached 100% or doesn't have any payment yet.</div>
											</div>
										@endif
									</div>
								</div>
							</div> -->
							<!-- <div class="tab-content clearfix" id="tabs-9">
								<div class="row">
									<div class="col-md-12">
										@if(count($project->projectSaleReturn) > 0)
											@foreach($project->projectSaleReturn as $datareturn)
											<div class="table-responsive">
												@php
													$adatile = false;
													$adalain = false;
													$total = 0;
													$totaltile = 0;
													$totallain = 0;
													foreach($datareturn->projectSaleReturnProduct as $key => $pp){
														if($pp->unit == '2' || $pp->unit == '3'){
															$adatile = true;
														}
														if($pp->unit == '1' || $pp->unit == '4'){
															$adalain = true;
														}
													}
												@endphp

												<table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" style="width:100%; font-size:10px;">
													@php
													if($adatile == true){
													@endphp
													<thead>
														@php
															if($adalain == true){
														@endphp
															<tr style="background:#51b6bc;text-align:center;">
																<th style="color:white;" colspan="11"><center>TILES</center></th>
															</tr>
														@php
															}
														@endphp
														<tr style="background:#51b6bc;text-align:center;">
															<th style="color:white;"><center>NO</center></th>
															<th style="color:white;"><center>CODE</center></th>
															<th style="color:white;"><center>PICTURE</center></th>
															<th style="color:white;"><center>BRAND</center></th>
															<th style="color:white;"><center>SIZE(cm)</center></th>
															<th style="color:white;"><center>CATEGORY</center></th>
															<th style="color:white;"><center>COLOR</center></th>
															<th style="color:white;"><center>QTY</center></th>
															<th style="color:white;"><center>UNIT</center></th>
															<th style="color:white;"><center>NOMINAL</center></th>
														</tr>
													</thead>
													<tbody>
														@foreach($datareturn->projectSaleReturnProduct as $key => $pp)
															@php
																if($pp->unit == '2' || $pp->unit == '3'){
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
																		{{ $pp->product->brand->name }}
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
																		{{ $pp->qty }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $pp->unit() }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		@php
																			foreach($datareturn->projectSale->projectSaleProduct->where('product_id',$pp->product_id) as $psp){
																				if($psp->unit == '2' || $psp->unit == '3'){
																					$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
																					if($m2 < 1.1){
																						echo number_format($psp->best_price * $pp->qty,0,',','.');
																					}else{
																						echo number_format($psp->best_price * $pp->qty * $m2,0,',','.');
																					}
																					
																				}else{
																					echo number_format($psp->best_price * $pp->qty,0,',','.');
																				}
																			}
																		@endphp
																	</center>
																</td>
															</tr>
															@php
																}
															@endphp
														@endforeach
													</tbody>
													@php
													}
													if($adalain == true){
													@endphp
													<thead>
														@php
															if($adatile == true){
														@endphp
															<tr style="background:#51b6bc;text-align:center;">
																<th style="color:white;" colspan="11"><center>NON-TILES</center></th>
															</tr>
														@php
															}
														@endphp
														<tr style="background:#51b6bc;text-align:center;">
															<th style="color:white;"><center>NO</center></th>
															<th style="color:white;"><center>CODE</center></th>
															<th style="color:white;"><center>PICTURE</center></th>
															<th style="color:white;"><center>BRAND</center></th>
															<th style="color:white;"><center>SIZE(cm)</center></th>
															<th style="color:white;"><center>CATEGORY</center></th>
															<th style="color:white;"><center>COLOR</center></th>
															<th style="color:white;"><center>QTY</center></th>
															<th style="color:white;"><center>UNIT</center></th>
															<th style="color:white;"><center>NOMINAL</center></th>
														</tr>
													</thead>
													<tbody>
														@php
															$no = 1;
														@endphp
														@foreach($datareturn->projectSaleReturnProduct as $key => $pp)
															@php
																if($pp->unit == '1' || $pp->unit == '4'){
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
																		{{ $pp->product->brand->name }}
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
																		{{ $pp->qty }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		{{ $pp->unit() }}
																	</center>
																</td>
																<td style="vertical-align:center;">
																	<center>
																		@php
																			foreach($datareturn->projectSale->projectSaleProduct->where('product_id',$pp->product_id) as $psp){
																				if($psp->unit == '2' || $psp->unit == '3'){
																					$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
																					if($m2 < 1.1){
																						echo number_format($psp->best_price * $pp->qty,0,',','.');
																					}else{
																						echo number_format($psp->best_price * $pp->qty * $m2,0,',','.');
																					}
																					
																				}else{
																					echo number_format($psp->best_price * $pp->qty,0,',','.');
																				}
																			}
																		@endphp
																	</center>
																</td>
															</tr>
															@php
																	$no++;
																}
															@endphp
														@endforeach
													</tbody>
													@php
													}
													@endphp
												</table>
												<br>
												<table cellpadding="0" cellspacing="0" width="100%" class="text-center">
													<tr>
														<td width="45%"></td>
														<td width="10%"></td>
														<td width="45%"></td>
													</tr>
													<tr>
														<td colspan="3" style="padding-top:10px;">
															<h6 style="font-size:10px; text-align:center;">Note :</h6>
															<p style="font-size:10px;">
																{{ $datareturn->note }}
															</p>
														</td>
													</tr>
												</table>
											</div>
											@endforeach
										@else
											<div class="style-msg errormsg text-center">
												<div class="sb-msg"><i class="icon-remove"></i><strong>Sorry!</strong> This project doesn't have any return yet.</div>
											</div>
										@endif
									</div>
								</div>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="{{ asset('template/front-office/js/plugins.min.js') }}"></script>
<script src="{{ asset('template/front-office/js/functions.js?v=6') }}"></script>