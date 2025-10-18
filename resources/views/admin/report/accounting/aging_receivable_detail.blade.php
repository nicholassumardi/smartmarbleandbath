<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Aging Receivable</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn bg-purple-400 btn-labeled btn-labeled-left mr-2" data-toggle="modal" data-target="#modal_form">
						<b><i class="icon-file-pdf"></i></b>AR Card
					</button>
					<button class="btn bg-pink-400 btn-labeled btn-labeled-left" data-toggle="modal" data-target="#modal_form_detail">
						<b><i class="icon-file-pdf"></i></b>AR Card Detail
					</button>
					
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Aging Receivable</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
				<div class="row justify-content-center">
				   <div class="col-md-12">
					  <h6 class="text-muted text-uppercase text-center font-weight-bold">
						Periode {{ date('F Y', strtotime($filter)) }}</h6>
						<form method="GET" id="form_filter">
							@csrf
							<div class="form-group">
								<center class="d-block">
									<div class="row justify-content-center no-gutters">
										<div class="col-md-3">
											<div class="form-group">
												<label>Branch</label>
												<select name="branch" id="branch" class="form-control" onchange="submitFilter()">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id == $branch ? 'selected' : '' }}">{{$company->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<label>Month Year</label>
											<input type="month" name="filter" id="filter" class="form-control" value="{{ $filter }}" onchange="submitFilter()">
										</div>
										@if (date('Y-m') != $filter)
											<div class="col-md-1 mt-4">
												<label>&nbsp;</label>
												<a href="{{ url('admin/report/accounting/aging_receivable') }}" class="btn bg-danger btn-sm"><i class="icon-reset"></i> Reset</a>
											</div>
										@endif
									</div>
								</center>
							</div>
						</form>
				   </div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
							<li class="nav-item"><a href="#tab-delivered" class="nav-link active" data-toggle="tab">Delivered</a></li>
							<li class="nav-item"><a href="#tab-bill" class="nav-link" data-toggle="tab">Bill</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="tab-delivered">
								<div class="table-responsive mt-3">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th rowspan="2" width="5%">No</th>
											<th rowspan="2">Customer</th>
											<th colspan="4">Term (days)</th>
											<th rowspan="2">Total</th>
											<th rowspan="2" width="5%">A/R Card</th>
										 </tr>
										 <tr class="text-center">
											<th>0-30</th>
											<th>31-60</th>
											<th>61-90</th>
											<th>Over 90</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$totalproject = 0;
											$total30 = 0;
											$total60 = 0;
											$total90 = 0;
											$totalover = 0;
										@endphp
										@foreach($data as $key => $row)
											<tr>
												<td class="text-center">{{ ($key + 1) }}</td>
												<td>{{ $row['customer_name'] }}</td>
												<td class="text-right" data-trigger="hover" data-popup="popover" data-placement="top" title="{{ $row['customer_name'] }}" data-content="Coming soon..." data-html="true">{{ number_format($row['total30'],2,',','.') }}</td>
												<td class="text-right" data-trigger="hover" data-popup="popover" data-placement="top" title="{{ $row['customer_name'] }}" data-content="Coming soon..." data-html="true">{{ number_format($row['total60'],2,',','.') }}</td>
												<td class="text-right" data-trigger="hover" data-popup="popover" data-placement="top" title="{{ $row['customer_name'] }}" data-content="Coming soon..." data-html="true">{{ number_format($row['total90'],2,',','.') }}</td>
												<td class="text-right" data-trigger="hover" data-popup="popover" data-placement="top" title="{{ $row['customer_name'] }}" data-content="Coming soon..." data-html="true">{{ number_format($row['totalover'],2,',','.') }}</td>
												<td class="text-right">{{ number_format($row['total'],2,',','.') }}</td>
												<td class="text-center">
													<a onclick="showARCardsReal({{ $row['customer_id'] }});" href="javascript:void(0);" class="btn btn-sm bg-info"><i class="icon-calculator2"></i></a>
												</td>
											</tr>
											@php
												$totalproject += $row['total'];
												$total30 += $row['total30'];
												$total60 += $row['total60'];
												$total90 += $row['total90'];
												$totalover += $row['totalover'];
											@endphp
										@endforeach
										<tr>
											<td class="text-center" colspan="2"><h5>Grandtotal</h5></td>
											<td class="text-right"><h5><b>{{ number_format($total30,2,',','.') }}</b></h5></td>
											<td class="text-right"><h5><b>{{ number_format($total60,2,',','.') }}</b></h5></td>
											<td class="text-right"><h5><b>{{ number_format($total90,2,',','.') }}</b></h5></td>
											<td class="text-right"><h5><b>{{ number_format($totalover,2,',','.') }}</b></h5></td>
											<td class="text-right"><h5><b>{{ number_format($totalproject,2,',','.') }}</b></h5></td>
											<td class="text-right"></td>
										</tr>
									  </tbody>
								   </table>
								</div>
							</div>
							<div class="tab-pane fade" id="tab-bill">
								<div class="table-responsive mt-3">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th rowspan="2" width="5%">No</th>
											<th rowspan="2">Customer</th>
											<th colspan="4">Term (days)</th>
											<th rowspan="2">Total</th>
											<th rowspan="2" width="5%">A/R Card</th>
										 </tr>
										 <tr class="text-center">
											<th>0-30</th>
											<th>31-60</th>
											<th>61-90</th>
											<th>Over 90</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$totalbill = 0;
											$total30 = 0;
											$total60 = 0;
											$total90 = 0;
											$totalover = 0;
										@endphp
										@foreach($data2 as $key => $row)
											<tr>
												<td class="text-center">{{ ($key + 1) }}</td>
												<td>{{ $row['customer_name'] }}</td>
												<td class="text-right">{{ number_format($row['total30'],2,',','.') }}</td>
												<td class="text-right">{{ number_format($row['total60'],2,',','.') }}</td>
												<td class="text-right">{{ number_format($row['total90'],2,',','.') }}</td>
												<td class="text-right">{{ number_format($row['totalover'],2,',','.') }}</td>
												<td class="text-right">{{ number_format($row['total'],2,',','.') }}</td>
												<td class="text-center">
													<a onclick="showARCards({{ $row['customer_id'] }});" href="javascript:void(0);" class="btn btn-sm bg-info"><i class="icon-calculator2"></i></a>
												</td>
											</tr>
											@php
												$totalbill += $row['total'];
												$total30 += $row['total30'];
												$total60 += $row['total60'];
												$total90 += $row['total90'];
												$totalover += $row['totalover'];
											@endphp
										@endforeach
										<tr>
											<td class="text-right" colspan="2"><h5>Grandtotal</h5></td>
											<td class="text-right"><h5><b>{{ number_format($total30,2,',','.') }}</b></h5></td>
											<td class="text-right"><h5><b>{{ number_format($total60,2,',','.') }}</b></h5></td>
											<td class="text-right"><h5><b>{{ number_format($total90,2,',','.') }}</b></h5></td>
											<td class="text-right"><h5><b>{{ number_format($totalover,2,',','.') }}</b></h5></td>
											<td class="text-right"><h5><b>{{ number_format($totalbill,2,',','.') }}</b></h5></td>
											<td class="text-right"></td>
										</tr>
									  </tbody>
								   </table>
								</div>
							</div>
						</div>
						<h1>Total All : Rp {{ number_format($totalproject + $totalbill,2,',','.') }}</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
		   <div class="modal-content">
			  <div class="modal-header bg-light">
				 <h5 class="modal-title" id="exampleModalLabel">Form AR Card</h5>
				 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				 </button>
			  </div>
			  <div class="modal-body">
				 <form id="form_data">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Customer :<sup class="text-danger">*</sup></label>
							 <select name="customer_id" id="customer_id" class="select2">
								<option value="">-- Choose --</option>
								@foreach($customer as $c)
								   <option value="{{ $c->id }}">{{ $c->name.' - '.$c->email }}</option>
								@endforeach
							 </select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Branch :<sup class="text-danger">*</sup></label>
								<select name="branch_customer" id="branch_customer" class="form-control">
									<option value="">-- Choose --</option>
									<option value="1">PTA</option>
									<option value="2">SMB</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>&nbsp;</label>
								<button type="button" onclick="showARCardsRealCustomer()" class="btn bg-info col-12"><i class="icon-calculator2"></i> Show Card</button>
							</div>
						</div>
					</div>
				 </form>
			  </div>
			  <div class="modal-footer bg-light">
				 <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			  </div>
		   </div>
		</div>
	</div>

	<div class="modal fade" id="modal_form_detail" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
		   <div class="modal-content">
			  <div class="modal-header bg-light">
				 <h5 class="modal-title" id="exampleModalLabel">Form AR Card Detail</h5>
				 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				 </button>
			  </div>
			  <div class="modal-body">
				 <form id="form_data">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Customer :<sup class="text-danger">*</sup></label>
							 <select name="customer_id_detail" id="customer_id_detail" class="select2">
								<option value="">-- Choose --</option>
								@foreach($customer as $c)
								   <option value="{{ $c->id }}">{{ $c->name.' - '.$c->email }}</option>
								@endforeach
							 </select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Branch :<sup class="text-danger">*</sup></label>
								<select name="branch_customer_detail" id="branch_customer_detail" class="form-control">
									<option value="">-- Choose --</option>
									<option value="1">PTA</option>
									<option value="2">SMB</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>&nbsp;</label>
								<button type="button" onclick="showARCardsCustomer()" class="btn bg-info col-12"><i class="icon-calculator2"></i> Show Card</button>
							</div>
						</div>
					</div>
				 </form>
			  </div>
			  <div class="modal-footer bg-light">
				 <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			  </div>
		   </div>
		</div>
	</div>
	<script>
		$(function(){
			$('.sidebar-main-toggle').click();
			$("html, body").animate({
				scrollTop: $(
				  'html, body').get(0).scrollHeight
			}, 500);
		});
        
		function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }
		
		function showARCards(cust){
			$.ajax({
				type : "POST",
				url  : "{{ url('admin/report/accounting/aging_receivable/card_detail') }}",
				data : {
					id : cust
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				cache: false,
				success: function(data){
					var w = window.open('about:blank');
					w.document.open();
					w.document.write(data);
					w.document.close();
				}
			});
		}

		function showARCardsReal(cust){
			$.ajax({
				type : "POST",
				url  : "{{ url('admin/report/accounting/aging_receivable/card') }}",
				data : {
					id : cust
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				cache: false,
				success: function(data){
					var w = window.open('about:blank');
					w.document.open();
					w.document.write(data);
					w.document.close();
				}
			});
		}


		function showARCardsRealCustomer(){
			if($("#customer_id").val()){
				$.ajax({
					type : "POST",
					url  : "{{ url('admin/report/accounting/aging_receivable/card') }}",
					data : {
						id : $("#customer_id").val(),
						branch : $("#branch_customer").val(),
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					cache: false,
					success: function(data){
						if(data.status == '500'){
							notif('error', 'bg-danger', data.message);
						}else{
							var w = window.open('about:blank');
							w.document.open();
							w.document.write(data);
							w.document.close();
						}
					},
					error: function() {
					swalInit.fire({
						title: 'Ups, check your internet connection!',
						text: 'Ups. Sorry error.',
						type: 'error'
					});
					}
				});
			}else{
				notif('error', 'bg-danger', 'Coose Customer');
			}
		}


		function showARCardsCustomer(){
			if($("#customer_id_detail").val()){
					$.ajax({
					type : "POST",
					url  : "{{ url('admin/report/accounting/aging_receivable/card_detail')}}",
					data : {
						id : $("#customer_id_detail").val(),
						branch : $("#branch_customer_detail").val(),
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					cache: false,
					success: function(data){
						if(data.status == '500'){
							notif('error', 'bg-danger', data.message);
						}else{
							var w = window.open('about:blank');
							w.document.open();
							w.document.write(data);
							w.document.close();
						}
					},
					error: function() {
					swalInit.fire({
						title: 'Ups, check your internet connection!',
						text: 'Ups. Sorry error.',
						type: 'error'
					});
					}
				});
			}else{
				notif('error', 'bg-danger', 'Choose Customer');
			}
			
		}
    </script>
	