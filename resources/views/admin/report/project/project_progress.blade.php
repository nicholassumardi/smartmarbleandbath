<style>
	html {
      scroll-behavior: smooth;
	  scroll-padding-top: 75px;
	}
	
	.sidebar-sticky{
		z-index:900;
	}
	
	.sidebar-light .nav-sidebar>.nav-item>.nav-link.active {
		background-color:#07a9e7;
	}
	
	.sidebar-content::-webkit-scrollbar {
	  width: 0px;
	  height: 8px;
	  background-color: white;
	}
	
	.sidebar-light .nav-sidebar .nav-link {
		color:white !important;
	}
	
	.sidebar-light .nav-sidebar .nav-link:hover{
		background-color:#457c80 !important;
		transform: scale(1.10);
	}
	
	.icon-check.text-success {
		color:#92ff01 !important;
	}
</style>

<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Progress Sales Project : <b><i>{{ $project->name }}</i></b></span>
				</h4>
			</div>
         <div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/report/project/details') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To All
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Data</a>
					<a href="{{ url('admin/report/project') }}" class="breadcrumb-item">Project</a>
					<span class="breadcrumb-item active">Detail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="d-block d-flex align-items-start flex-column flex-md-row">
         <div class="order-2 order-md-1 w-100">
            <div class="card" id="step-1">
               <div class="card-body">
                  <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">1</span> Project Information</b></h3>
                  <div class="form-group"><hr></div>
				  <div class="row">
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td width="40%">Project Name</td>
										<td>: {{ $project->name }}</td>
									</tr>
									<tr>
										<td>Phone</td>
										<td>: {{ $project->customer->phone }}</td>
									</tr>
									<tr>
										<td>Email</td>
										<td>: {{ $project->customer->email }}</td>
									</tr>
									<tr>
										<td>Constructor Name</td>
										<td>: {{ $project->customer->constructor }}</td>
									</tr>
									<tr>
										<td>Country</td>
										<td>: {{ $project->country->name }}</td>
									</tr>
									<tr>
										<td>City</td>
										<td>: {{ $project->city->name }}</td>
									</tr>
									<tr>
										<td>Timeline</td>
										<td>: {{ date('d M Y', strtotime($project->timeline)) }}</td>
									</tr>
									<tr>
										<td>PIC</td>
										<td>: {{ $project->manager }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td width="40%">Consultant Name</td>
										<td>: {{ $project->consultant }}</td>
									</tr>
									<tr>
										<td>Owner</td>
										<td>: {!! $project->owner !!}</td>
									</tr>
									<tr>
										<td>Bank Destination</td>
										<td>: {!! $project->coa->name !!}</td>
									</tr>
									<tr>
										<td>Payment Method</td>
										<td>: {!! $project->paymentMethod() !!}</td>
									</tr>
									<tr>
										<td>Payment Term</td>
										<td>: {!! $project->paymentTerm() !!}</td>
									</tr>
									<tr>
										<td>Supply Method</td>
										<td>: {!! $project->supplyMethod() !!}</td>
									</tr>
									<tr>
										<td>PPN</td>
										<td>: {!! $project->ppn() !!}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				  </div>
               </div>
            </div>
            <div class="card" id="step-2">
               <form action="{{ url()->full() }}" method="POST">
                  @csrf
                  <div class="card-body">
                     <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">2</span> Project Material Specifications</b></h3>
                     <div class="form-group"><hr></div>
					<div class="form-group">
					   <div class="table-responsive">
							<table class="table table-bordered table-striped">
							   <thead class="table-secondary">
								  <tr class="text-center">
									<th>No.</th>
									<th>Product</th>
									<th width="15%">Area</th>
									<th width="15%">Surface</th>
									<th width="15%">Qty</th>
									<th width="15%">Unit</th>
									<th width="15%">Info</th>
								  </tr>
							   </thead>
							   <tbody id="data_product">
								  @foreach($project->projectProduct as $no => $pp)
									<tr class="text-center rowproduct">
										 <td>{{ $no+1 }}</td>
										 <td class="align-middle">
											<a href="{{ $pp->product->type->image() }}" data-lightbox="{{ $pp->product->name() }}" data-title="{{ $pp->product->name() }}"><img src="{{ $pp->product->type->image() }}" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>
										   <div><a href="javascript:void(0);" onclick="getShading({{ $pp->product_id }})">{{ $pp->product->name() }}</a></div>
										   <div>{{ $pp->product->type->length }}x{{ $pp->product->type->width }}</div>
										 </td>
										 <td class="align-middle">
											{{ $pp->area }}
										 </td>
										 <td class="align-middle">
											{{ $pp->spec }}
										 </td>
										 <td class="align-middle">
											{{ $pp->qty }}
										 </td>  
										 <td class="align-middle">
											{{ $pp->unit() }}
										 </td>   
										 <td class="align-middle">
											<div class="input-group">
											   <a class="btn btn-primary btn-sm" href="#collapse-icon{{ $pp->product_id }}" data-toggle="collapse" style="margin:auto;"><i class="icon-info3"></i></a>
											</div>
											<div class="collapse" id="collapse-icon{{ $pp->product_id }}">
												<div class="mt-3">
												{{ $pp->product->carton_pcs }} PCS/CTN,
												{{ ($pp->product->type->length * $pp->product->type->width)/10000 * $pp->product->carton_pcs }} M<sup>2</sup>
												</div>
											</div>
										 </td>  
									</tr>
								  @endforeach
							   </tbody>
							</table>
						</div>
					</div>
                  </div>
               </form>
            </div>
            @if($project->progress >= 15)
               <div class="card" id="step-3">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">3</span> Consultant Meeting</b></h3>
                        <div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="table-responsive">
							 <table class="table table-bordered table-striped">
								<thead class="table-secondary">
								   <tr class="text-center">
									  <th>Date</th>
									  <th>Person</th>
									  <th>Result</th>
									  <th>Delete</th>
								   </tr>
								</thead>
								<tbody id="data_consultant">
									@foreach($project->projectConsultantMeeting as $pcm)
										<tr class="text-center">
										   <input type="hidden" name="consultant_id[]" value="{{ $pcm->id }}">
										   <input type="hidden" name="consultant_date[]" value="{{ $pcm->date }}">
										   <input type="hidden" name="consultant_person[]" value="{{ $pcm->person }}">
										   <input type="hidden" name="consultant_result[]" value="{{ $pcm->result }}">

										   <td class="align-middle">{{ date('d M Y',strtotime($pcm->date)) }}</td>   
										   <td class="align-middle">{{ $pcm->person }}</td>   
										   <td class="align-middle">{{ $pcm->result }}</td>   
										   <td class="align-middle">
											  <button type="button" id="delete_data_consultant" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
										   </td>
										</tr>
									@endforeach
								</tbody>
							 </table>
						  </div>
					   </div>
                     </div>
                  </form>
               </div>
            @endif
            @if($project->progress >= 20)
               <div class="card" id="step-4">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">4</span> Quotation</b></h3>
                        <div class="form-group"><hr></div>
                        <div class="form-group">
                           <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                 <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th>Product</th>
                                       <th>Qty</th>
                                       <th>Price</th>
                                       <th>Project Price</th>
									   <th width="15%">Disc(%)</th>
									   <th>Best Price</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($project->projectProduct as $pp)
                                       <tr class="text-center">
                                          <td class="align-middle">
                                             <a href="{{ $pp->product->type->image() }}" data-lightbox="{{ $pp->product->name() }}" data-title="{{ $pp->product->name() }}"><img src="{{ $pp->product->type->image() }}" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>
                                             <div><a href="javascript:void(0);" onclick="getShading({{ $pp->product->id }})">{{ $pp->product->name() }}</a></div>
                                             <div>{{ $pp->product->type->length }}x{{ $pp->product->type->width }}</div>
                                          </td>
                                          <td class="align-middle">{{ $pp->qty.' '.$pp->unit() }}</td>   
                                          <td class="align-middle">Rp {{ number_format($pp->price, 2, ',', '.') }}</td>   
                                          <td class="align-middle">
											   {{ number_format($pp->recommended_price, 2, ',', '.') }}
                                          </td>
										  <td class="align-middle">
                                               {{ number_format($pp->discount, 2, ',', '.') }}
                                          </td> 
										  <td class="align-middle">
											   {{ number_format($pp->best_price, 2, ',', '.') }}
                                          </td>
                                       </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                        </div>
					   <div class="form-group"><hr></div>
					   <div class="form-group">
						  <div class="text-right">
							@if(count($project->projectQuotation) > 0)
								@foreach($project->projectQuotation as $pq)
									<a onclick="openLink('{{ url('admin/report/project/details/printHtml/quotation_order/' . base64_encode($pq->id)) }}')" href="javascript:void(0);" class="btn bg-info mr-1 ml-1 mt-1">Quotation (Rev-{{ $pq->revision - 1 }}) <i class="icon-file-pdf"></i></a>
								@endforeach
							@else
								<button class="btn bg-danger" onclick="return false;">Empty <i class="icon-file-pdf"></i></button>
							@endif
						  </div>
					   </div>
					   <div class="form-group"><hr></div>
					   <div class="form-group">
							<h5><b><span class="badge badge-danger">4.a</span> All Quotation Order</b></h5>
							<div class="alert alert-info alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
								<span class="font-weight-semibold">Attention!</span> Document approval must be at least 1 for each document.
							</div>
                           <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                 <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th>Revision</th>
                                       <th>Date</th>
                                       <th>Approved By(1)</th>
                                       <th>Approved By(2)</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($project->projectQuotation as $pq)
                                       <tr class="text-center">
                                          <td class="align-middle">{{ $pq->revision() }}</td>   
                                          <td class="align-middle">{{ date('d M Y',strtotime($pq->created_at)) }}</td>   
                                          <td class="align-middle">
											@php
												if(isset($pq->approved_1->name)){
													echo $pq->approved_1->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveQuotation(1,'.$pq->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>   
                                          <td class="align-middle">
											@php
												if(isset($pq->approved_2->name)){
													echo $pq->approved_2->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveQuotation(2,'.$pq->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
                                       </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                        </div>
					   
                     </div>
                  </form>
               </div>
            @endif
            @if($project->progress >= 25)
               <div class="card" id="step-5">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">5</span> Sample</b></h3>
                        <div class="form-group"><hr></div>
						<div class="form-group">
							<h5><b>List of All Sample Orders</b></h5>
							<div class="alert alert-info alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
								<span class="font-weight-semibold">Attention!</span> Document approval must be at least 1 for each document.<br>Status sample sent can be changed by change status option for each sample.
							</div>
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>Code</th>
									   <th>Date</th>
									   <th>Note</th>
									   <th>Validated By</th>
									   <th>Approved By</th>
									   <th width="18%">Status</th>
									   <th>View</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectSample as $ps)
									   <tr class="text-center">
										  <td class="align-middle">{{ $ps->code }}</td>   
										  <td class="align-middle">{{ date('d M Y',strtotime($ps->sent_date)).' to '.date('d M Y',strtotime($ps->return_date)) }}</td>
										  <td class="align-middle">{{ $ps->note }}</td>
										  <td class="align-middle">
											@php
												if(isset($ps->approved_1->name)){
													echo $ps->approved_1->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSample(1,'.$ps->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>   
										  <td class="align-middle">
											@php
												if(isset($ps->approved_2->name)){
													echo $ps->approved_2->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSample(2,'.$ps->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td>
											{{ $ps->status() }}
										  </td>
										  <td>
											@php
												echo '<a href="'.url('admin/report/project/print/sample_order/'. base64_encode($ps->id)).'" class="btn bg-info" target="_blank"><i class="icon-file-pdf"></i></a>';
											@endphp
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
            @if($project->progress >= 30)
               <div class="card" id="step-6">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">6</span> Negotiation Progress Report</b></h3>
                        <div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="table-responsive">
							 <table class="table table-bordered table-striped">
								<thead class="table-secondary">
								   <tr class="text-center">
									  <th>Date</th>
									  <th>Person</th>
									  <th>Result</th>
									  <th>Delete</th>
								   </tr>
								</thead>
								<tbody id="data_negotiation">
									@foreach($project->projectNegotiation as $pn)
										<tr class="text-center">
										   <input type="hidden" name="negotiation_id[]" value="{{ $pn->id }}">
										   <input type="hidden" name="negotiation_date[]" value="{{ $pn->date }}">
										   <input type="hidden" name="negotiation_person[]" value="{{ $pn->person }}">
										   <input type="hidden" name="negotiation_result[]" value="{{ $pn->result }}">

										   <td class="align-middle">{{ date('d M Y',strtotime($pn->date)) }}</td>   
										   <td class="align-middle">{{ $pn->person }}</td>   
										   <td class="align-middle">{{ $pn->result }}</td>   
										   <td class="align-middle">
											  <button type="button" id="delete_data_negotiation" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
										   </td>
										</tr>
									@endforeach
								</tbody>
							 </table>
						  </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif			
			
            @if($project->progress >= 35)
               <div class="card" id="step-7">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">7</span> SO Project</b></h3>
						<div class="form-group"><hr></div>
						<div class="form-group">
							<h5><b><span class="badge badge-danger">7.a</span> List of All Sales Orders</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO No.</th>
									   <th>Sales</th>
									   <th>Date</th>
									   <th>Marketing</th>
									   <th>Ack. By</th>
									   <th>PO Customer</th>
									   <th>SO Product</th>
									   <th>SO Other</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectSale as $ps)
									   <tr class="text-center">
										  <td class="align-middle">{{ $ps->code }}</td>  
										  <td class="align-middle">{{ $ps->sales->name }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($ps->created_at)) }}</td>   
										  <td class="align-middle">
											@php
												if(isset($ps->marketing->name)){
													echo $ps->marketing->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSale(1,'.$ps->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>   
										  <td class="align-middle">
											@php
												if(isset($ps->approved->name)){
													echo $ps->approved->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSale(2,'.$ps->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $ps->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td>
											<a onclick="openLink('{{ url('admin/report/project/details/printHtml/sales_order/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td>
											<a onclick="openLink('{{ url('admin/report/project/details/printHtml/sales_cost/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
			@if($project->progress >= 37)
               <div class="card" id="step-8">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">8</span> Down Payment</b></h3>
                        <div class="form-group"><hr></div>
						<h5 class="card-title">Payment Information</h5>
						<dl class="row mb-0">
							<dt class="col-sm-3">Bank Destination</dt>
							<dd class="col-sm-9">: {!! $project->coa->name !!}</dd>

							<dt class="col-sm-3">Payment Method</dt>
							<dd class="col-sm-9">: {!! $project->paymentMethod() !!}</dd>

							<dt class="col-sm-3">Supply Method</dt>
							<dd class="col-sm-9">: {!! $project->supplyMethod() !!}</dd>

							<dt class="col-sm-3 text-truncate">PPN</dt>
							<dd class="col-sm-9">: {!! $project->ppn() !!}</dd>
							
							<dt class="col-sm-3 text-truncate">Term Payment</dt>
							<dd class="col-sm-9">: {!! $project->paymentTerm() !!}</dd>
							
						</dl>
						<div class="form-group"><hr></div>
						<h5 class="card-title"><b><span class="badge badge-danger">8.a</span> List of All Sales Payment</b></h5>
						<div class="form-group">
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO Code</th>
									   <th>Invoice</th>
									   <th>Date</th>
									   <th>Nominal</th>
									   <th>Note</th>
									   <th>Checked By</th>
									   <th>Marketing</th>
									   <th>Ack.By</th>
									   <th>Proof</th>
									   <th><i class="icon-printer2"></i></th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPay()->orderBy('project_sale_id')->get() as $pp)
									   <tr class="text-center">
										  <td class="align-middle">{{ $pp->projectSale->code }}</td>
										  <td class="align-middle">{{ $pp->code }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($pp->date)) }}</td>
										  <td class="align-middle">IDR {{ number_format($pp->nominal,2,',','.') }}</td>
										  <td class="align-middle">{{ $pp->note }}</td>
										  <td class="align-middle">
											@php
												if(isset($pp->check->name)){
													echo $pp->check->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(3,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											@php
												if(isset($pp->marketing->name)){
													echo $pp->marketing->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(1,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										   <td class="align-middle">
											@php
												if(isset($pp->approved->name)){
													echo $pp->approved->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(2,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/report/project/printHtml/sales_invoice/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
            @if($project->progress >= 40)
               <div class="card" id="step-9">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">9</span> PO Supplier</b></h3>
                        <div class="form-group"><hr></div>
						<div class="form-group">
							<h5><b><span class="badge badge-danger">9.a</span> List of All Purchase Order</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>Code</th>
									   <th>SO No.</th>
									   <th>Supplier</th>
									   <th>Checked</th>
									   <th>Approved</th>
									   <th>View</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPurchase as $pp)
									   <tr class="text-center">
										  <td class="align-middle">{{ $pp->code }}</td>
										  <td class="align-middle">{{ $pp->projectSale->code }}</td>
										  <td class="align-middle">{{ $pp->supplier->name }}</td>
										  <td class="align-middle">
											@php
												if(isset($pp->checked->name)){
													echo $pp->checked->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approvePurchase(1,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>   
										  <td class="align-middle">
											@php
												if(isset($pp->approved->name)){
													echo $pp->approved->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approvePurchase(2,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td>
											<a onclick="openLink('{{ url('admin/report/project/details/printHtml/purchase_order/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
					    <div class="form-group"><hr></div>
						<div class="form-group">
							<h5><b><span class="badge badge-danger">9.b</span> List Notes</b></h5>
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group">
							<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<thead class="table-secondary">
										<tr class="text-center">
										   <th width="25%">Date</th>
										   <th>PO</th>
										   <th>Note</th>
										   <th>Proof</th>
										</tr>
									 </thead>
									 <tbody id="data_purchase_note">
									 @php $countnotes = 0; @endphp
									 @foreach($project->projectPurchase as $row)
										@foreach($row->purchaseNote() as $rowdetail)
										<tr class="text-center">
											<td>{{ $rowdetail->created_at }}</td>
											<td>{{ $rowdetail->notable->code }}</td>
											<td>{{ $rowdetail->note }}</td>
											<td>{!! $rowdetail->image ? '<a href="' . $rowdetail->image() . '" data-lightbox="' . $rowdetail->notable->code . '" data-title="' . $rowdetail->notable->code . '"><img src="' . $rowdetail->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>' !!}</td>
										</tr>
										@php $countnotes++; @endphp
										@endforeach
									@endforeach
										@if($countnotes == 0)
										<tr>
											<td colspan="4">
												<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes here.</div>
											</td>
										</tr>
										@endif
									 </tbody>
								</table>
							</div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
            @if($project->progress >= 43)
               <div class="card" id="step-10">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">10</span> Proof of Proforma Invoice</b></h3>
                        <div class="form-group"><hr></div>
					   <div class="form-group">
							<h5><b><span class="badge badge-danger">10.a</span> List of All Proof of Proformas</b></h5>
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
										  <td class="align-middle">{{ date('d M Y',strtotime($pp->date)) }}</td>
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
                  </form>
               </div>
            @endif
			@if($project->progress >= 45)
               <div class="card" id="step-11">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">11</span> Down Payment Purchase</b></h3>
                        <div class="form-group"><hr></div>
					   <div class="form-group">
							<h5><b><span class="badge badge-danger">11.a</span> List of All Down Payment PO</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>SO No.</th>
									   <th>Date</th>
									   <th>Bank</th>
									   <th>Nominal</th>
									   <th>Proof</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPayment()->where('status', 1)->get() as $pp)
									   <tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pp->projectPurchase->id }}')">{{ $pp->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $pp->projectPurchase->projectSale->code }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($pp->date)) }}</td>
										  <td class="align-middle">{{ $pp->coa->name }}</td>
										  <td class="align-middle">{{ $pp->projectPurchase->currency->symbol.' '.number_format($pp->nominal,2,',','.') }}</td>
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
                  </form>
               </div>
            @endif
            @if($project->progress >= 48)
               <div class="card" id="step-12">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">12</span> Progress Production</b></h3>
                        <div class="form-group"><hr></div>
						<h5><b><span class="badge badge-danger">12.a</span> List of All Progress Production PO</b></h5>
						<div class="form-group">
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO No.</th>
									   <th>SO No.</th>
									   <th>Start</th>
									   <th>Finish</th>
									   <th>Note</th>
									   <th>Progress</th>
									   <th>Proof</th>
									</tr>
								 </thead>
								 <tbody>
									@php
									$total = 0;
									$temppo = 0;
									foreach($project->projectProduction()->orderBy('project_purchase_id')->get() as $pp){
										if($temppo == 0 || $temppo == $pp->project_purchase_id){
											$total += $pp->progress;
										}else{
											$total = $pp->progress;
										}
										$temppo = $pp->project_purchase_id;
									@endphp
									   <tr class="text-center">
										  <td class="align-middle">
											<a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pp->projectPurchase->id }}')">{{ $pp->projectPurchase->code }}</a>
										  </div>
										  <td class="align-middle">
											{{ $pp->projectPurchase->projectSale->code }}
										  </div>
										  <td class="align-middle">
											{{ date('d M Y',strtotime($pp->start_date)) }}
										  </div>
										  <td class="align-middle">
											{{ date('d M Y',strtotime($pp->finish_date)) }}
										  </div>
										  <td class="align-middle">
											{{ $pp->note }}
										  </div>
										  <td class="align-middle">
											{{ $total }}%
										  </td>
										  <td class="align-middle">
											 <a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
									   </tr>
									@php
									}
									@endphp
								 </tbody>
							  </table>
						   </div>
						</div>
					</div>
                  </form>
               </div>
            @endif
            @if($project->progress >= 50)
               <div class="card" id="step-13">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">13</span> Pay Full Purchase</b></h3>
                        <div class="form-group"><hr></div>
						<div class="form-group">
							<h5><b><span class="badge badge-danger">13.a</span> List of All Payment Purchases</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>SO No.</th>
									   <th>Date</th>
									   <th>Bank</th>
									   <th>Nominal</th>
									   <th>Proof</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPayment()->orderBy('project_purchase_id')->get() as $pp)
									   <tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pp->projectPurchase->id }}')">{{ $pp->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $pp->projectPurchase->projectSale->code }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($pp->date)) }}</td>
										  <td class="align-middle">{{ $pp->coa->name }}</td>
										  <td class="align-middle">{{ $pp->projectPurchase->currency->symbol.' '.number_format($pp->nominal,2,',','.') }}</td>
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
                  </form>
               </div>
            @endif
            @if($project->progress >= 55)
               <div class="card" id="step-14">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">14</span> Shipment</b></h3>
                        <div class="form-group"><hr></div>
						<div class="form-group">
							<h5><b><span class="badge badge-danger">14.a</span> List of All Shipment Purchases</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>Shipment Code</th>
									   <th>Loading</th>
									   <th>Departure</th>
									   <th>From Port</th>
									   <th>To Port</th>
									   <th>ETA</th>
									   <th>Note</th>
									   <th>Tracking</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectShipment()->orderBy('project_purchase_id')->get() as $ps)
									   <tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $ps->projectPurchase->id }}')">{{ $ps->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $ps->shipment_code }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($ps->loading_date)) }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($ps->departure_date)) }}</td>
										  <td class="align-middle">{{ $ps->from_port }}</td>
										  <td class="align-middle">{{ $ps->to_port }}</td>
										  <td class="align-middle">{{ $ps->eta }}</td>
										  <td class="align-middle">{{ $ps->note }}</td>
										  <td class="align-middle"><a class="btn bg-info" href="javascript:void(0);" onclick="addTrackingShipment({{ $ps->id }},'{{ $ps->shipment_code }}')"><i class="icon-truck"></i></a></td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			@if($project->progress >= 60)
               <div class="card" id="step-15">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">15</span> Warehouse Receive</b></h3>
                        <div class="form-group"><hr></div>
						<h5 class="card-title"><b><span class="badge badge-danger">15.a</span> All Warehouse Receive</b></h5>
						<div class="form-group">
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>PO Code</th>
									   <th>Shipment</th>
									   <th>Code</th>
									   <th>Warehouse</th>
									   <th>Person</th>
									   <th>Date & Time Received</th>
									   <th>Proof</th>
									   <th><i class="icon-printer2"></i></th>
									</tr>
								 </thead>
								 <tbody>
									@php
										$tempid = 0;
									@endphp
									@foreach($project->projectWarehouse()->orderBy('project_purchase_id')->get() as $pw)
									   <tr class="text-center">
										  <td class="align-middle"><a href="javascript:void(0);" onclick="showPurchaseProduct(this,'{{ $pw->projectPurchase->id }}')">{{ $pw->projectPurchase->code }}</a></td>
										  <td class="align-middle">{{ $pw->projectShipment->shipment_code }}</td>
										  <td class="align-middle">{{ $pw->code }}</td>
										  <td class="align-middle">{{ $pw->warehouse->name.' - '.$pw->warehouse->code }}</td>
										  <td class="align-middle">{{ $pw->person }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($pw->date_receive)) }}</td>
										  <td class="align-middle">
											<a href="{{ $pw->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/report/project/details/printHtml/warehouse_receive/' . base64_encode($pw->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
            @if($project->progress >= 65)
               <div class="card" id="step-16">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">16</span> Delivery To Project</b></h3>
                        <div class="form-group"><hr></div>
						<h5 class="card-title"><b><span class="badge badge-danger">16.a</span> All Delivery Order</b></h5>
						<div class="form-group">
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO No.</th>
									   <th>DO No.</th>
									   <th>Warehouse</th>
									   <th>Receiver</th>
									   <th>Dropship</th>
									   <th>Approved By</th>
									   <th>Tracking</th>
									   <th>SJ</th>
									   <th>Inv Product</th>
									   <th>Inv Other</th>
									   <th>Proof</th>
									   <th>Received</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectDelivery()->orderBy('project_sale_id')->get() as $psi)
									   <tr class="text-center">
										  <td class="align-middle">{{ $psi->projectSale->code }}</td>
										  <td class="align-middle">{{ $psi->code }}</td>
										  <td class="align-middle">{{ $psi->warehouse->name.' - '.$psi->warehouse->code }}</td>
										  <td class="align-middle">{{ $psi->receiver_name }}</td>
										  <td class="align-middle">{{ $psi->isDropshipper() }}</td>
										  <td class="align-middle">
											@php
												if(isset($psi->approve->name)){
													echo $psi->approve->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveDelivery(1,'.$psi->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a class="btn bg-info" href="javascript:void(0);" onclick="addTrackingDelivery({{ $psi->id }},'{{ $psi->code }}')"><i class="icon-truck"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/report/project/details/printHtml/delivery_order/' . base64_encode($psi->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/report/project/details/printHtml/sales_proforma/' . base64_encode($psi->id)) }}')" href="javascript:void(0);" class="btn bg-primary"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/report/project/details/printHtml/sales_proforma_other/' . base64_encode($psi->id)) }}')" href="javascript:void(0);" class="btn bg-primary"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td class="align-middle">
											<a href="{{ $psi->attachment() }}" target="_blank" class="btn bg-info"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											@if(isset($psi->received_date))
												{{ date('d M Y',strtotime($psi->received_date)) }}
											@else
												Not yet received
											@endif
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
			@if($project->progress >= 75)
               <div class="card" id="step-17">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">17</span> Sales Return</b></h3>
                        <div class="form-group"><hr></div>
						<h5 class="card-title"><b><span class="badge badge-danger">17.a</span> All Sales Return</b></h5>
						<div class="form-group">
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO Code</th>
									   <th>Code</th>
									   <th>Note</th>
									   <th>Approved By</th>
									   <th>Proof</th>
									   <th><i class="icon-printer2"></i></th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectSaleReturn()->orderBy('project_sale_id')->get() as $psr)
									   <tr class="text-center">
										  <td class="align-middle">{{ $psr->projectSale->code }}</td>
										  <td class="align-middle">{{ $psr->code }}</td>
										  <td class="align-middle">{{ $psr->note }}</td>
										  <td class="align-middle">
											@php
												if(isset($psr->approve->name)){
													echo $psr->approve->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSaleReturn(1,'.$psr->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $psr->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											@php
												echo '<a href="'.url('admin/report/project/details/printHtml/sales_return/' . base64_encode($psr->id)).'" class="btn bg-info" target="_blank"><i class="icon-file-pdf"></i></a>';
											@endphp
										  </td>
									   </tr>
									@endforeach
									@if(count($project->projectSaleReturn()->orderBy('project_sale_id')->get()) == 0)
										<tr>
											<td class="bg-warning" colspan="6" style="text-align:center;">
												There is no sales return.
											</td>
										<tr>
									@endif
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
            @if($project->progress >= 80)
               <div class="card" id="step-18">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">18</span> Full Payment</b></h3>
                        <div class="form-group"><hr></div>
						<h5 class="card-title">Payment Information</h5>
						<dl class="row mb-0">
							<dt class="col-sm-3">Bank Destination</dt>
							<dd class="col-sm-9">: {!! $project->coa->name !!}</dd>

							<dt class="col-sm-3">Payment Method</dt>
							<dd class="col-sm-9">: {!! $project->paymentMethod() !!}</dd>

							<dt class="col-sm-3">Supply Method</dt>
							<dd class="col-sm-9">: {!! $project->supplyMethod() !!}</dd>

							<dt class="col-sm-3 text-truncate">PPN</dt>
							<dd class="col-sm-9">: {!! $project->ppn() !!}</dd>
							
							<dt class="col-sm-3 text-truncate">Term Payment</dt>
							<dd class="col-sm-9">: {!! $project->paymentTerm() !!}</dd>
							
						</dl>
						<div class="form-group"><hr></div>
						<h5 class="card-title"><b><span class="badge badge-danger">18.a</span> All Sales Payment</b></h5>
						<div class="form-group">
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO Code</th>
									   <th>Invoice</th>
									   <th>Date</th>
									   <th>Nominal</th>
									   <th>Note</th>
									   <th>Marketing</th>
									   <th>Ack.By</th>
									   <th>Proof</th>
									   <th><i class="icon-printer2"></i></th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectPay()->orderBy('project_sale_id')->get() as $pp)
									   <tr class="text-center">
										  <td class="align-middle">{{ $pp->projectSale->code }}</td>
										  <td class="align-middle">{{ $pp->code }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($pp->date)) }}</td>
										  <td class="align-middle">IDR {{ number_format($pp->nominal,2,',','.') }}</td>
										  <td class="align-middle">{{ $pp->note }}</td>
										  <td class="align-middle">
											@php
												if(isset($pp->marketing->name)){
													echo $pp->marketing->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(1,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										   <td class="align-middle">
											@php
												if(isset($pp->approved->name)){
													echo $pp->approved->name;
												}else{
													echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSalesInvoice(2,'.$pp->id.')"><i class="icon-checkmark2"></i></button>';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/report/project/details/printHtml/sales_invoice/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
			@if($project->progress >= 85)
               <div class="card" id="step-19">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">19</span> Troubleshooting</b></h3>
						<h5 class="card-title font-italic font-weight-bold">Please fill in here</h5>
                        <div class="form-group"><hr></div>
						@if(isset($_GET['step-19']))
						  @if($errors->any())
							 <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								<ul>
								   @foreach ($errors->all() as $error)
									  <li>{{ $error }}</li>
								   @endforeach
								</ul>
							 </div>
						  @elseif(session('success'))
							 <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert">
								   <span>&times;</span>
								</button>
								{{ session('success') }}
							 </div>
						  @endif
						@endif
						<div class="row">
						  <div class="col-md-4">
							<div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="date_trouble" id="date_trouble" class="form-control" value="{{ old('date_trouble') ? old('date_trouble') : date('Y-m-d') }}">
							 </div>
						  </div>
						  <div class="col-md-4">
							<div class="form-group">
								<label>Proof Troubleshooting :</label>
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							</div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Note :<sup class="text-danger">*</sup></label>
								<textarea name="note_trouble" id="note_trouble" class="form-control" rows="1">{{ old('note_trouble') ? old('note_trouble') : 'None' }}</textarea>
							 </div>
							</div>
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="text-right">
							 <button type="submit" name="submit" value="step-19" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
						<h5 class="card-title"><b><span class="badge badge-danger">19.a</span> All Troubleshooting</b></h5>
						<div class="form-group">
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>Date</th>
									   <th>Note</th>
									   <th>Proof</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectTroubleshooting as $pt)
									   <tr class="text-center">
										  <td class="align-middle">{{ date('d M Y',strtotime($pt->date_trouble)) }}</td>
										  <td class="align-middle">{{ $pt->note }}</td>
										  <td class="align-middle">
											<a href="{{ $pp->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
									   </tr>
									@endforeach
								 </tbody>
							  </table>
						   </div>
						</div>
						<h5 class="card-title"><b><span class="badge badge-danger">19.b</span> Gallery</b></h5>
						<div class="row mt-3">
							@if(count($project->projectPicture) > 0)
								@foreach($project->projectPicture as $key => $row)
									<div class="col-md-3 text-center">
										<a data-magnify="gallery" data-src="" data-caption="{{ $row->project->name.'-'.($key+1) }}" data-group="a" href="{{ $row->attachment() }}"><img src="{{ $row->attachment() }}" style="max-height:200px;" class="img-fluid img-thumbnail"></a>
									</div>
								@endforeach
							@else
								<div class="col-md-12 text-center">
									<div class="alert alert-warning alert-styled-left">
										There is no pictures uploaded in this project.
									</div>
								</div>
							@endif
						</div>
                     </div>
                  </form>
               </div>
            @endif
			
            @if($project->progress >= 90)
               <div class="card" id="step-20">
                  <form action="{{ url()->full() }}" method="POST" onSubmit="return confirm('Are you sure you wish to finish this project?');">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">20</span> Done</b></h3>
                        <div class="form-group"><hr></div>
                        @if(isset($_GET['step-20']))
                           @if($errors->any())
                              <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
                                 <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                 </button>
                                 <ul>
                                    @foreach ($errors->all() as $error)
                                       <li>{{ $error }}</li>
                                    @endforeach
                                 </ul>
                              </div>
                           @elseif(session('success'))
                              <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
                                 <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                 </button>
                                 {{ session('success') }}
                              </div>
                           @endif
                        @endif
                        <div class="text-center">
                           @if($project->progress >= 100)
                              <div class="text-center">
                                 <i class="icon-checkmark-circle text-success font-weight-bold" style="font-size:50px;"></i>
                                 <div class="font-weight-bold mt-2">
                                    <h5 class="text-uppercase font-weight-bold">Progress Complete</h5>
                                 </div>
                                 <p class="text-muted">Project progress has been completed up to 100%</p>
                              </div>
                           @else
                              <h6 class="font-italic text-danger font-weight-bold">*) Please press the button below if you want to complete the project</h6>
                           @endif
                        </div>
                        @if($project->progress < 100)
                           <div class="form-group"><hr></div>
                           <div class="form-group">
                              <div class="text-center">
                                  <button type="submit" name="submit" value="step-20" class="btn bg-purple" style="font-size:40px;"><i class="icon-floppy-disk icon-2x"></i> Save & Done</button>
                              </div>
                           </div>
                        @endif
						<div class="form-group">
						  <div class="text-right">
							<a class="btn bg-success" href="https://wa.me/?text=Halo%20Pak%2FBu%2C%20mohon%20dicek%20project%20dengan%20kode%20{{ $project->code }}">Whatsapp <i class="icon-share3"></i></a>
						  </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
         </div>
			<div class="sidebar-sticky order-1 order-md-2">
				<!-- Secondary sidebar -->
				<div class="sidebar sidebar-light sidebar-secondary sidebar-component sidebar-component-right sidebar-expand-md">

					<!-- Sidebar content -->
					<div class="sidebar-content" style="overflow-y: scroll;background-color:#457c80 !important;height:90vh;">

						<div class="card">
							 <div class="card-header bg-transparent header-elements-inline" style="background-color:#457c80 !important;color:white !important;">
								<span class="text-uppercase font-size-sm font-weight-semibold">Progress</span>
							 </div>
							 <ul class="nav nav-sidebar nav-scrollspy">
								<li class="nav-item">
								   <a href="#step-1" class="nav-link">
									  <i class="icon-check text-success"></i>
									  1. Project Information
									  <span class="badge bg-warning badge-pill ml-auto">10%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-2" class="nav-link">
									  @if($project->progress >= 15)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  2. Spec Project
									  <span class="badge bg-warning badge-pill ml-auto">15%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-3" class="nav-link {{ $project->progress >= 15 ? '' : 'disabled' }}">
									  @if($project->progress >= 20)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  3. Consultant Meeting
									  <span class="badge bg-warning badge-pill ml-auto">20%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-4" class="nav-link {{ $project->progress >= 20 ? '' : 'disabled' }}">
									  @if($project->progress >= 25)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  4. Quotation
									  <span class="badge bg-warning badge-pill ml-auto">25%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-5" class="nav-link {{ $project->progress >= 25 ? '' : 'disabled' }}">
									  @if($project->progress >= 30)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  5. Form Sample
									  <span class="badge bg-warning badge-pill ml-auto">30%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-6" class="nav-link {{ $project->progress >= 30 ? '' : 'disabled' }}">
									  @if($project->progress >= 35)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  6. Negotiation
									  <span class="badge bg-warning badge-pill ml-auto">35%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-7" class="nav-link {{ $project->progress >= 35 ? '' : 'disabled' }}">
									  @if($project->progress >= 37)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  7. SO Project
									  <span class="badge bg-warning badge-pill ml-auto">37%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-8" class="nav-link {{ $project->progress >= 37 ? '' : 'disabled' }}">
									  @if($project->progress >= 40)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  8. Down Payment
									  <span class="badge bg-warning badge-pill ml-auto">40%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <hr style="width: 70%;border-top: 1px solid black;">
								   <a href="#step-9" class="nav-link {{ $project->progress >= 40 ? '' : 'disabled' }}">
									  @if($project->progress >= 43)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  9. PO Supplier
									  <span class="badge bg-warning badge-pill ml-auto">43%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-10" class="nav-link {{ $project->progress >= 43 ? '' : 'disabled' }}">
									  @if($project->progress >= 45)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  10. Proof of Proforma Inv
									  <span class="badge bg-warning badge-pill ml-auto">45%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-11" class="nav-link {{ $project->progress >= 45 ? '' : 'disabled' }}">
									  @if($project->progress >= 48)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  11. Down Payment Purchase
									  <span class="badge bg-warning badge-pill ml-auto">48%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-12" class="nav-link {{ $project->progress >= 48 ? '' : 'disabled' }}">
									  @if($project->progress >= 50)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  12. Progress Production
									  <span class="badge bg-warning badge-pill ml-auto">50%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-13" class="nav-link {{ $project->progress >= 50 ? '' : 'disabled' }}">
									  @if($project->progress >= 55)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  13. Pay Full Purchase
									  <span class="badge bg-warning badge-pill ml-auto">55%</span>
								   </a>
								</li>
								<li class="nav-item">
									
								   <a href="#step-14" class="nav-link {{ $project->progress >= 55 ? '' : 'disabled' }}">
									  @if($project->progress >= 60)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  14. Delivery Document Number
									  <span class="badge bg-warning badge-pill ml-auto">60%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-15" class="nav-link {{ $project->progress >= 60 ? '' : 'disabled' }}">
										@if($project->progress >= 65)
										 <i class="icon-check text-success"></i>
										  @else
											 <i class="icon-spinner10"></i>
										  @endif
										15. Warehouse Receive
										<span class="badge bg-warning badge-pill ml-auto">65%</span>
								   </a>
								</li>
								
								<li class="nav-item">
									<hr style="width: 70%;border-top: 1px solid black;">
								   <a href="#step-16" class="nav-link {{ $project->progress >= 65 ? '' : 'disabled' }}">
									  @if($project->progress >= 75)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  16. Delivery To Project
									  <span class="badge bg-warning badge-pill ml-auto">75%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-17" class="nav-link {{ $project->progress >= 75 ? '' : 'disabled' }}">
										@if($project->progress >= 80)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
										17. Sales Return
										<span class="badge bg-warning badge-pill ml-auto">80%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-18" class="nav-link {{ $project->progress >= 80 ? '' : 'disabled' }}">
									  @if($project->progress >= 85)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  18. Full Payment
									  <span class="badge bg-warning badge-pill ml-auto">85%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-19" class="nav-link {{ $project->progress >= 85 ? '' : 'disabled' }}">
									  @if($project->progress >= 90)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  19. Troubleshooting
									  <span class="badge bg-warning badge-pill ml-auto">90%</span>
								   </a>
								</li>
								<li class="nav-item">
								   <a href="#step-20" class="nav-link {{ $project->progress >= 90 ? '' : 'disabled' }}">
									  @if($project->progress >= 100)
										 <i class="icon-check text-success"></i>
									  @else
										 <i class="icon-spinner10"></i>
									  @endif
									  20. Done
									  <span class="badge bg-warning badge-pill ml-auto">100%</span>
								   </a>
								</li>
							 </ul>
						  </div>

					</div>
					<!-- /sidebar content -->

				</div>
				<!-- /secondary sidebar -->
		</div>
      </div>
   </div>
	<!-- Info modal -->
	<div id="modal_purchase_product" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Purchase <b id="modal_title_purchase_code">A</b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title"><b>Detail Products</b></h5>
					<div class="form-group"><hr></div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
									   <th>No</th>
                                       <th>Product</th>
                                       <th>Qty Needed</th>
									   <th>Qty Sent</th>
									   <th>Qty Left</th>
									   <th>Unit</th>
									   <th>M<sup>2</sup></th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_purchase_detail">
                                    
                                 </tbody>
                              </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	<!-- Info modal -->
	<div id="modal_stock_product" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Stock <b id="modal_title_stock_product"></b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title"><b>Detail Stock</b></h5>
					<div class="form-group"><hr></div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
									   <th>No</th>
                                       <th>Warehouse</th>
                                       <th>Stock Code</th>
									   <th>Code</th>
									   <th>Qty</th>
									   <th>Unit</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_stock_product">
                                    
                                 </tbody>
								 <tfoot class="font-weight-black" style="font-size:20px;">
									<tr>
										<td colspan="4" class="text-right">Total</td>
										<td class="text-center" id="totalshading">0</td>
										<td class="text-center"></td>
									</tr>
								 </tfoot>
                              </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	<!-- Info modal -->
	<div id="modal_tracking_shipment" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Shipment No. <b id="modal_title_tracking_shipment"></b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title"><b>Detail Tracking Shipment</b> <a href="" target="_blank" id="link-tracking-shipment" class="btn btn-success btn-sm float-right">View <i class="icon-file-eye"></i></a></h5>
					
					<div class="form-group"><hr></div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th width="25%">Date</th>
                                       <th>Note</th>
									   <th width="15%">Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_shipment_tracking">
                                    <tr>
										<td colspan="3">
											<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
										</td>
									</tr>
                                 </tbody>
                              </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /info modal -->
	<!-- Info modal -->
	<div id="modal_tracking_delivery" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 800px !important;">
				<div class="modal-header bg-info">
					<h6 class="modal-title">Delivery No. <b id="modal_title_tracking_delivery"></b></h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h5 class="card-title"><b>Detail Tracking Delivery</b> <a href="" target="_blank" id="link-tracking-delivery" class="btn btn-success btn-sm float-right">View <i class="icon-file-eye"></i></a></h5>
					
					<div class="form-group"><hr></div>
					<div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th width="25%">Date</th>
                                       <th>Note</th>
									   <th>Proof</th>
									   <th width="15%">Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_delivery_tracking">
                                    <tr>
										<td colspan="4">
											<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
										</td>
									</tr>
                                 </tbody>
                              </table>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<script>
   $(function() {
	   var element = $('.sidebar-sticky'),
			originalY = element.offset().top;

		// Space between element and top of screen (when scrolling)
		var topMargin = 75;

		// Should probably be set in CSS; but here just for emphasis
		element.css('position', 'relative');

		$(window).on('scroll', function(event) {
			var scrollTop = $(window).scrollTop();

			element.stop(false, false).animate({
				top: scrollTop < originalY
						? 0
						: scrollTop - originalY + topMargin
			}, 250);
		});
	   
      var no = 0;

      $('.sidebar-main-toggle').click();
      select2ServerSide('#product_id,#product_id2', '{{ url("admin/select2/product") }}');
	  select2ServerSide('#sales_id,#sales_po', '{{ url("admin/select2/user") }}');
	  select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
	  select2ServerSide('#supplier_id', '{{ url("admin/select2/supplier") }}');
	  select2ServerSide('#warehouse_id,#warehousereturn_id,#warehousedeliver_id,#warehouse_destination', '{{ url("admin/select2/warehouse") }}');
      select2ServerSide('#sample_product_id', '{{ url("admin/select2/product") }}');
	  select2ServerSide('#country_id', '{{ url("admin/select2/country") }}');
	  select2ServerSide('#city_id,#city_id2', '{{ url("admin/select2/city") }}');
	  //select2ServerSide('#currency', '{{ url("admin/select2/currency") }}');
      
      $('#data_product').on('click', '#delete_data_product', function() {
         $(this).closest('tr').remove();
		 $('.warning-product').fadeIn(500);
      });

      $('#data_consultant').on('click', '#delete_data_consultant', function() {
         $(this).closest('tr').remove();
		 $('.warning-consultant').fadeIn(500);
      });
	  
	  $('#data_negotiation').on('click', '#delete_data_negotiation', function() {
         $(this).closest('tr').remove();
		 $('.warning-negotiation').fadeIn(500);
      });

      $('#data_sample').on('click', '#delete_data_sample', function() {
         $(this).closest('tr').remove();
		 $('.warning-sample').fadeIn(500);
      });
	  
	  $('#data_purchase').on('click', '#delete_data_product_purchase', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_shipment_product').on('click', '#delete_ship_product', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_warehouse_product').on('click', '#delete_shipment_product', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_purchase_return').on('click', '#delete_purchase_return_product', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_delivery_products').on('click', '#delete_delivery_products', function() {
         $(this).closest('tr').remove();
      });
	  
	  $('#data_sales_return').on('click', '#delete_sales_return_products', function() {
         $(this).closest('tr').remove();
      });
	  $('#data_transfer_product').on('click', '#delete_transfer_product', function() {
         $(this).closest('tr').remove();
      });
	  
   });
   
	function getShading(val){
		if(val !== ''){
			$.ajax({
				url: '{{ url("admin/report/project/get_shading_product") }}',
				type: 'GET',
			 dataType: 'JSON',
			 data: {
				val : val
			 },
			 beforeSend: function() {
				loadingOpen('.content');
			 },
			 success: function(response) {
				var total = 0;
				if(response.shading.length > 0) {
					var no = 1;
					$('#data_stock_product').empty();
					$.each(response.shading, function(i, val) {
						$('#data_stock_product').append(`
							<tr class="text-center">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.warehouse_code + `</td>
							 <td class="align-middle">
								` + val.stock_code + `
							 </td>
							 <td class="align-middle">
								` + val.code +`
							 </td>
							 <td class="align-middle">
								` + val.qty +`
							 </td>
							 <td class="align-middle">
								` + val.unit +`
							 </td>
							</tr>
						`);
						no++;
						total += parseFloat(val.qty);
					});
					$('#modal_title_stock_product').html(response.product_name);
					$('#totalshading').html(total);
					$('#modal_stock_product').modal('toggle');
				}else{
					swalInit.fire('Info Stock!', 'Stock / shading is not available.', 'info');
				}
				
				loadingClose('.content');
			 },
			 error: function() {
				loadingClose('.content');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
			});
		}
	}
	
	function getListShading(val){
		if(val !== ''){
			$.ajax({
				url: '{{ url("admin/report/project/get_shading_product") }}',
				type: 'GET',
			 dataType: 'JSON',
			 data: {
				val : val
			 },
			 beforeSend: function() {
				loadingOpen('.content');
			 },
			 success: function(response) {
				if(response.shading.length > 0) {
					$('#warehouse_from').empty();
					$.each(response.shading, function(i, val) {
						$('#warehouse_from').append(`
							<option value="` + val.product_id + `_` + val.warehouse_code + `_` + val.warehouse_name + `">` + val.warehouse_code + ` Shading : ` + val.code  + ` Stok : ` + val.qty + ` ` + val.unit + `</option>
						`);
					});
				}else{
					swalInit.fire('Info Stock!', 'Stock / shading is not available.', 'info');
				}
				
				loadingClose('.content');
			 },
			 error: function() {
				loadingClose('.content');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
			});
		}
	}
	
	function getSalesInfo(element, idprojectsale) {
		var elemen = element.getAttribute('name');
		
		$.ajax({
			url: '{{ url("admin/report/project/get_sales_info") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idprojectsale : idprojectsale
         },
         beforeSend: function() {
			if(elemen == 'so_id'){
				loadingOpen('#step-9');
			}else if(elemen == 'sop_id'){
				loadingOpen('#step-18');
			}else if(elemen == 'sod_id'){
				loadingOpen('#step-16');
			}
         },
         success: function(response) {
			if(elemen == 'so_id'){
			 
				loadingClose('#step-9');
				
				if(response) {
					$('#sales_po').empty();
					$('#customer_id').empty();
					$('#sales_po').append(`
						<option value="` + response.sales_id + `">` + response.sales_name + `</option>
					`);
					$('#customer_id').append(`
						<option value="` + response.customer_id + `">` + response.customer_name + `</option>
					`);
				}
			}else if(elemen == 'sop_id'){
				loadingClose('#step-18');
				
				if(response) {
					$('#payment_total').text(response.payment_total);
					$('#payment_paid').text(response.payment_paid);
					$('#payment_left').text(response.payment_left);
				}
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
				
				if(response) {
					$('#receiver_name').val(response.customer_name);
					$('#email').val(response.customer_email);
					$('#phone').val(response.customer_phone);
					$('#city_id2').empty();
					$('#city_id2').append(`
						<option value="` + response.city_id + `">` + response.city_name + `</option>
					`);
				}
			}
         },
         error: function() {
            if(elemen == 'so_id'){
				loadingClose('#step-9');
			}else if(elemen == 'sop_id'){
				loadingClose('#step-18');
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
			}
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getSupplierCurrency(idsupp) {
		$('#currency').empty();
		$.ajax({
			url: '{{ url("admin/report/project/get_supplier_currency") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idsupp : idsupp
         },
         beforeSend: function() {
            loadingOpen('#step-9');
         },
         success: function(response) {
            loadingClose('#step-9');
				if(response.length > 0) {
					$.each(response, function(i, val) {
						$('#currency').append(`
							<option value="` + val.id + `">` + val.code + ` ` + val.name + ` ` + val.symbol + `</option>
						`);
					});
				}
         },
         error: function() {
            loadingClose('#step-9');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getSalesProduct(element, idprojectsale) {
		var elemen = element.getAttribute('name');
		$.ajax({
			url: '{{ url("admin/report/project/get_sales_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idprojectsale : idprojectsale
         },
         beforeSend: function() {
			if(elemen == 'so_id'){
				loadingOpen('#step-9');
			}else if(elemen == 'sor_id'){
				loadingOpen('#step-17');
			}else if(elemen == 'sod_id'){
				loadingOpen('#step-16');
			}
         },
         success: function(response) {
			 
			if(elemen == 'so_id'){
				loadingClose('#step-9');
				
				if(response.length > 0) {
					$('#data_purchase').empty();
					
					$.each(response, function(i, val) {
						$('#data_purchase').append(`
							<tr class="text-center rowproductsale purchaseproductdata` + val.product_id + `" data-m2="` + val.m2 + `">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + ` ` + val.unit + `
							 </td>
							 <td class="align-middle">
								` + val.qty_left +`
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" id="purchaseproductqty` + val.product_id + `" class="form-control" placeholder="0" value="` + val.qty_left +`" required>
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_price[]" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);countTotalPurchase(this,'`+ val.product_id +`');">
							 </td>
							 <td class="align-middle">
								<div id="purchaseproducttotal`+ val.product_id +`"></div>
							 </td>
							 <td class="align-middle">
								<textarea class="form-control" rows="1" name="product_remark[]"></textarea>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_data_product_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
					});
				}
			}else if(elemen == 'sor_id'){
				loadingClose('#step-17');
				
				if(response.length > 0) {
					$('#data_sales_return').empty();
					var no = 1;
					
					$.each(response, function(i, val) {
						$('#data_sales_return').append(`
							<tr class="text-center">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="0" required>
							 </td>
							 <td class="align-middle">
								<select name="product_unit[]" class="custom-select" required>
								   <option value="1">Pcs</option>   
								   <option value="2">Box</option>   
								   <option value="3">Meter</option>   
								   <option value="4">Meter(Custom)</option>   
								</select>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_sales_return_products" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
							</tr>
						`);
						no++;
					});
				}
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
				
				if(response.length > 0) {
					$('#data_delivery_products').empty();
					var no = 1;
					
					$.each(response, function(i, val) {
						$('#data_delivery_products').append(`
							<tr class="text-center">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.unitconvert + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + ` ` + val.unit + `
							 </td>
							 <td class="align-middle">
								` + val.qty_left_deliver +`
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="` + val.qty_left_deliver +`" required>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_delivery_products" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
						no++;
					});
				}
			}
         },
         error: function() {
			
            if(elemen == 'so_id'){
				loadingClose('#step-9');
			}else if(elemen == 'sor_id'){
				loadingClose('#step-17');
			}else if(elemen == 'sod_id'){
				loadingClose('#step-16');
			}
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getPurchaseProduct(elemen,idpo){
		var elemen = elemen.getAttribute('name');
		$.ajax({
			url: '{{ url("admin/report/project/get_purchase_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
			 if(elemen == 'pos_id'){
				loadingOpen('#step-14');
			 }
         },
         success: function(response) {
			 
			var no = 1;
			
			if(elemen == 'pos_id'){
				if(response.length > 0) {
					$('#data_shipment_product').empty();
					
					$.each(response, function(i, val) {
						$('#data_shipment_product').append(`
							<tr class="text-center" data-m2="` + val.m2 + `">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.convertunit + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + `
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" class="form-control" placeholder="" value="` + val.qty_left +`" required>
							 </td>
							 <td class="align-middle">
								` + val.unit + `
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_ship_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
						
						no++;
					});
					
				}
			}
			
			if(elemen == 'pos_id'){
				loadingClose('#step-14');
			}
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function showPurchaseProduct(element,idpo){
		$.ajax({
			url: '{{ url("admin/report/project/get_purchase_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
			 loadingOpen('.order-2');
         },
         success: function(response) {
			$('#modal_title_purchase_code').html(element.innerHTML);
			 
			if(response.length > 0) {
				$('#data_purchase_detail').empty();
				
				var no = 1;
				
				$.each(response, function(i, val) {
					$('#data_purchase_detail').append(`
						<tr class="text-center" data-m2="` + val.m2 + `">
						 <td>` + no + `</td>
						 <td class="align-middle">` + val.product_name + `</td>
						 <td class="align-middle">
							` + val.qty + `
						 </td>
						 <td class="align-middle">
							` + val.qty_sent + `
						 </td>
						 <td class="align-middle">
							` + val.qty_left + `
						 </td>
						 <td class="align-middle">
							` + val.unit + `
						 </td>
						 <td class="align-middle">
							` + val.fixunit + `
						 </td>
					  </tr>
					`);
					
					no++;
				});
				
				$('#modal_purchase_product').modal('toggle');
				
				loadingClose('.order-2');
			}
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getShipmentInfo(idpo){
		$.ajax({
			url: '{{ url("admin/report/project/get_shipment_info") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
            loadingOpen('#step-15');
         },
         success: function(response) {
            loadingClose('#step-15');
				$('#shipment_id').empty();
				$('#shipment_id').append('<option value="">-- Empty --</option>');
				if(response.shipment_list.length > 0) {
					$.each(response.shipment_list, function(i, val) {
						$('#shipment_id').append(`
							<option value="` + val.shipment_id + `">` + val.shipment_code + `</option>
						`);
					});
				}
         },
         error: function() {
            loadingClose('#step-15');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getShipmentProduct(idshipment){
		$.ajax({
			url: '{{ url("admin/report/project/get_shipment_product") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            idshipment : idshipment
         },
         beforeSend: function() {
            loadingOpen('#step-15');
         },
         success: function(response) {
            loadingClose('#step-15');
				if(response.shipment_product.length > 0) {
					$('#data_warehouse_product').empty();
					
					var no = 1;
					
					$.each(response.shipment_product, function(i, val) {
						$('#data_warehouse_product').append(`
							<tr class="text-center">
								 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
								 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
								 <td>` + no + `</td>
								 <td class="align-middle">` + val.product_name + `</td>
								 <td class="align-middle">
									<input type="number" name="product_qty[]" class="form-control" value="` + val.qty + `" required>
								 </td>
								 <td class="align-middle">
									` + val.unit + `
								 </td>
								 <td class="align-middle">
									<input type="number" name="product_qty_broken[]" class="form-control" value="0" required>
								 </td>
								 <td class="align-middle">
									` + val.unit + `
								 </td>
								 <td class="align-middle">
									<button type="button" id="delete_shipment_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
								 </td>
							</tr>
						`);
						
						no++;
					});
				}
         },
         error: function() {
            loadingClose('#step-15');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	
	function getPurchaseInfo(element,purchaseid) {
		var elemen = element.getAttribute('name');
		$.ajax({
			url: '{{ url("admin/report/project/get_purchase_info") }}',
			type: 'GET',
         dataType: 'JSON',
         data: {
            purchaseid : purchaseid
         },
         beforeSend: function() {
			if(elemen == 'pop_id'){
				loadingOpen('#step-10');
			}else if(elemen == 'pr_id'){
				loadingOpen('#step-12');
			}else if(elemen == 'por_id'){
				loadingOpen('#step-13');
			}else{
				loadingOpen('#step-11');
			}
         },
         success: function(response) {
			if(elemen == 'pop_id'){
				loadingClose('#step-10');
			}else if(elemen == 'pr_id'){
				loadingClose('#step-12');
			}else if(elemen == 'por_id'){
				loadingClose('#step-13');
			}else{
				loadingClose('#step-11');
			}
			
			if(response) {
				if(elemen == 'pop_id'){
					$('#supplier_name').val(response.supplier_name);
				}else if(elemen == 'po_id'){
					$('#proforma_total').html(response.total);
					$('#proforma_paid').html(response.totalpaid);
					$('#proforma_total_raw').val(response.totalraw);
					$('#percentage').trigger('keyup');
				}else if(elemen == 'por_id'){
					$('#data_payment_purchase').empty();
					if(response.datapayment.length > 0){
						$('#proforma_total_1').html(response.total);
						$('#proforma_paid_1').html(response.totalpaid);
						$('#nominal_payment').val(response.totalleft);
						$('#paymentotal').html(response.total);
						$('#po-payment-code').html(element.options[element.selectedIndex].text);
						
						$.each(response.datapayment, function(i, val) {
							$('#data_payment_purchase').append(`
								<tr class="text-center">
									<td>`+ val.date +`</td>
									<td>`+ val.bank +`</td>
									<td>`+ val.nominal +`</td>
									<td>`+ val.status +`</td>
								</tr>
							`);
						});
					}else{
						$('#data_payment_purchase').append(`
							<tr>
								<td class="bg-warning" colspan="4" style="text-align:center;">Choose Purchase Order first to see detail payment each Purchases</td>
							</tr>
						`);
						swalInit.fire('Warning!', 'This Purchase Order has no down payment yet!', 'warning');
						$('#po-payment-code').html('');
					}
				}else if(elemen == 'pr_id'){
					$('#progress_production').val(response.progress_left);
				}
			}
         },
         error: function() {
            if(elemen == 'pop_id'){
				loadingClose('#step-10');
			}else if(elemen == 'pr_id'){
				loadingClose('#step-12');
			}else if(elemen == 'por_id'){
				loadingClose('#step-13');
			}else{
				loadingClose('#step-11');
			}
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
   
   function addProduct() {
      var id = $('#product_id');

      if(id.val()) {
		  
		  
         $.ajax({
            url: '{{ url("admin/report/project/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: id.val()
            },
            beforeSend: function() {
               loadingOpen('#step-2');
            },
            success: function(response) {
			    var same = false;
				
				$('input[name^="product_id"]').each(function() {
					if($(this).val() == response.id){
						same = true;
					}
				});
				
				if(same == false){
				   id.val(null).trigger('change');
				   
				   if($('.rowproduct').length == 0){
					  no = 1;
				   }else{
					  no = $('.rowproduct').length + 1;
				   }

				   $('#data_product').append(`
					  <tr class="text-center rowproduct">
						 <input type="hidden" name="product_id[]" value="` + response.id + `">
						 <input type="hidden" name="product_price[]" value="` + response.price + `">
						 <td>` + no + `</td>
						 <td class="align-middle">` + response.product + `</td>
						 <td class="align-middle">
							<input type="text" name="product_area[]" class="form-control" placeholder="Type area" required>
						 </td>
						 <td class="align-middle">
							<textarea name="product_spec[]" class="form-control" placeholder="Type spec" rows="1" required>` + response.surface + `</textarea>
						 </td>
						 <td class="align-middle">
							<input type="number" name="product_qty[]" class="form-control" min="1" placeholder="0" required>
						 </td>  
						 <td class="align-middle">
							<select name="product_unit[]" class="custom-select" required>
							   <option value="1">Pcs</option>   
							   <option value="2">Box</option>   
							   <option value="3">Meter</option>   
							   <option value="4">Meter(Custom)</option>   
							</select>
						 </td>   
						 <td class="align-middle">
							` + response.carton_pcs + ` pcs / carton, ` + response.carton_sqm + `
						 </td>  
						 <td class="align-middle">
							<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						 </td>
					  </tr>
				   `);
				}else{
					swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
				}
				
				loadingClose('#step-2');
            },
            error: function() {
               loadingClose('#step-2');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
      } else {
         swalInit.fire('Ooppsss!', 'Please select a product', 'info');
      }
   }
   
   function addTransfer() {
      var tgl = $('#transfer_date').val(), product_id = $('#product_id2').val(), product_name = $('#product_id2 option:selected').text(), from = $('#warehouse_from').val(), to = $('#warehouse_destination').val(), to_name = $('#warehouse_destination option:selected').text();

      if(from !== '') {
		   $('#data_transfer_product').append(`
			  <tr class="text-center">
				 <input type="hidden" name="transfer_date[]" value="` + tgl + `">
				 <input type="hidden" name="product_id[]" value="` + product_id + `">
				 <input type="hidden" name="from_warehouse[]" value="` + from + `">
				 <input type="hidden" name="to_warehouse[]" value="` + to + `">
				 <td class="align-middle">` + tgl + `</td>
				 <td class="align-middle">` + product_name + `</td>
				 <td class="align-middle">` + from + `</td>
				 <td class="align-middle"><input type="number" name="product_qty[]" class="form-control" min="1" placeholder="0" required></td>
				 <td class="align-middle">` + to_name + `</td>
				 <td class="align-middle">
					<button type="button" id="delete_transfer_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
				 </td>
			  </tr>
		   `);
      } else {
         swalInit.fire('Ooops! This product has no stock.', 'Please contact Ventura to confirm this problem.', 'info');
      }
   }

   function addConsultant() {
      var consultant_date   = $('#consultant_date');
      var consultant_person = $('#consultant_person');
      var consultant_result = $('#consultant_result');

      if(consultant_date.val() && consultant_person.val() && consultant_result.val()) {
         $('#data_consultant').append(`
            <tr class="text-center">
			   <input type="hidden" name="consultant_id[]" value="0">
               <input type="hidden" name="consultant_date[]" value="` + consultant_date.val() + `">
               <input type="hidden" name="consultant_person[]" value="` + consultant_person.val() + `">
               <input type="hidden" name="consultant_result[]" value="` + consultant_result.val() + `">

               <td class="align-middle">` + consultant_date.val() + `</td>   
               <td class="align-middle">` + consultant_person.val() + `</td>   
               <td class="align-middle">` + consultant_result.val() + `</td>   
               <td class="align-middle">
                  <button type="button" id="delete_data_consultant" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
               </td>
            </tr>
         `);

         consultant_date.val(null);
         consultant_person.val(null);
         consultant_result.val(null);
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
   }
   
   function addNegotiation() {
      var negotiation_date   = $('#negotiation_date');
      var negotiation_person = $('#negotiation_person');
      var negotiation_result = $('#negotiation_result');

      if(negotiation_date.val() && negotiation_person.val() && negotiation_result.val()) {
         $('#data_negotiation').append(`
            <tr class="text-center">
			   <input type="hidden" name="negotiation_id[]" value="0">
               <input type="hidden" name="negotiation_date[]" value="` + negotiation_date.val() + `">
               <input type="hidden" name="negotiation_person[]" value="` + negotiation_person.val() + `">
               <input type="hidden" name="negotiation_result[]" value="` + negotiation_result.val() + `">

               <td class="align-middle">` + negotiation_date.val() + `</td>   
               <td class="align-middle">` + negotiation_person.val() + `</td>   
               <td class="align-middle">` + negotiation_result.val() + `</td>   
               <td class="align-middle">
                  <button type="button" id="delete_data_negotiation" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
               </td>
            </tr>
         `);

         negotiation_date.val(null);
         negotiation_person.val(null);
         negotiation_result.val(null);
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
   }

	
   function approveQuotation(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/report/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"quotation" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-4');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approveSample(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/report/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sample" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-5');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approveSale(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/report/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sale" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-7');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approvePurchase(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/report/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"purchase" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-9');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approveDelivery(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/report/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"delivery" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-16');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function approveSaleReturn(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/report/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sales_return" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-17');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function updateStatusSample(val,id){
	   $.ajax({
         url: '{{ url("admin/report/project/update_status_sample") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { val:val,id:id },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-5');
         },
         success: function(response) {
			 if(response.status == '200'){
				 //location.reload();
				 swalInit.fire('Success!', 'Sample status successfully changed.', 'success');
				 loadingClose('#step-5');
			 }
         }
      });
	  
	  return false;
   }
   
   function approveSalesInvoice(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/report/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sales_invoice" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-18');
         },
         success: function(response) {
			 if(response.status == '200'){
				 location.reload();
			 }
         }
      });
	  
	  return false;
   }
   
   function addSample() {
      var sample_product_id = $('#sample_product_id');
      var sample_qty        = $('#sample_qty');
	  var sample_unit       = $('#sample_unit');
      var sample_size       = $('#sample_size');

      if(sample_product_id.val() && sample_qty.val() && sample_size.val()) {
         $.ajax({
            url: '{{ url("admin/report/project/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: sample_product_id.val()
            },
            beforeSend: function() {
               loadingOpen('#step-5');
            },
            success: function(response) {
               loadingClose('#step-5');
			   
				var same = false;
				
				$('input[name^="sample_product_id"]').each(function() {
					if($(this).val() == response.id){
						same = true;
					}
				});
				
				if(same == false){
					
					$('#data_sample').append(`
					  <tr class="text-center">
						 <input type="hidden" name="sample_product_id[]" value="` + sample_product_id.val() + `">
						 <input type="hidden" name="sample_qty[]" value="` + sample_qty.val() + `">
						 <input type="hidden" name="sample_unit[]" value="` + sample_unit.val() + `">
						 <input type="hidden" name="sample_size[]" value="` + sample_size.val() + `">

						 <td class="align-middle">` + response.product + `</td>  
						 <td class="align-middle">` + sample_qty.val() + `</td>
						 <td class="align-middle">` + $("#sample_unit option:selected").text() + `</td>
						 <td class="align-middle">` + $("#sample_size option:selected").text() + `</td>  
						 <td class="align-middle">
							<button type="button" id="delete_data_sample" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						 </td>
					  </tr>
				   `);

				   sample_product_id.val(null).trigger('change');
				   sample_qty.val(null);
				   sample_unit.find('option:eq(0)').prop('selected', true);
				   sample_size.val(null);
				
				}else{
					swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
				}
			   
               
            },
            error: function() {
               loadingClose('#step-5');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
   }
   
   function countTotalPurchase(element,id){
	   
		var result = parseFloat($(element).val().replace(',','.').replace(/\./g,'')) * parseFloat($('#purchaseproductqty' + id).val());
	   
		var number_string = result.toString(),
		split   		= number_string.split('.'),
		sisa     		= split[0].length % 3,
		rupiah     		= split[0].substr(0, sisa),
		ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		if(split[1]){
			split[1] = toFixed(parseFloat("0." + split[1]),2);
			split[1] = split[1].toString().split('.')[1];
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	   
		$('#purchaseproducttotal'+id).html(rupiah);
		
   }
   
   function countBestPrice(percent,id){
	    var projectPrice = $('#project-price' + id).val().replace(',','.').replace(/\./g,'');
	   
		var result = parseFloat(projectPrice) - (parseFloat((parseFloat(projectPrice) * parseFloat(percent.value)) / 100));
	   
		var number_string = result.toString(),
		split   		= number_string.split('.'),
		sisa     		= split[0].length % 3,
		rupiah     		= split[0].substr(0, sisa),
		ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		if(split[1]){
			split[1] = toFixed(parseFloat("0." + split[1]),2);
			split[1] = split[1].toString().split('.')[1];
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	   
		$('#best-price'+id).val(rupiah);
		
   }
   
	function countDueDate(){
		var tanggal = {{ $project->term_payment }};
		if($('#date_create').val() !== ''){
			var datenow = new Date($('#date_create').val())
			datenow.setDate(datenow.getDate() + parseInt(tanggal));
			var day = day_of_the_month(datenow);
			$('#due_date').val(datenow.getFullYear().toString() + '-' + (datenow.getMonth()+1).toString() + '-' + day.toString());
		}else{
			swalInit.fire('Error!', 'Please determine date create first if you want to auto count due date.', 'error');
			$('#date_create').focus();
		}
	}
	
	function day_of_the_month(d)
	{ 
	  return (d.getDate() < 10 ? '0' : '') + d.getDate();
	}
   
   function convertToNominal(ini,element){
		var result = parseFloat((parseFloat($('#proforma_total_raw').val()) * parseFloat(ini.value)) / 100);
	   
		var number_string = result.toString(),
		split   		= number_string.split('.'),
		sisa     		= split[0].length % 3,
		rupiah     		= split[0].substr(0, sisa),
		ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		if(split[1]){
			split[1] = toFixed(parseFloat("0." + split[1]),2);
			split[1] = split[1].toString().split('.')[1];
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	   
		$(element).val(rupiah);
   }
   
	function toFixed( num, precision ) {
		return (+(Math.round(+(num + 'e' + precision)) + 'e' + -precision)).toFixed(precision);
	}
	
	function skipForm(step,project) {
		$.ajax({
            url: '{{ url("admin/report/project/skip_form") }}',
            type: 'POST',
            dataType: 'JSON',
            data: {
               step : step, project : project
            },
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
            beforeSend: function() {
               loadingOpen('#step-' + step);
            },
            success: function(response) {
				if(response.status == '200'){
					location.reload();
				}
				loadingClose('#step-' + step);
            },
            error: function() {
               loadingClose('#step-' + step);
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
		 
		 return false;
	}
	
	function addTrackingShipment(idshipment,codeshipment){
		$('#modal_title_tracking_shipment').html(codeshipment);
		
		$.ajax({
            url: '{{ url("admin/report/project/get_tracking_shipment") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: idshipment
            },
            beforeSend: function() {
               loadingOpen('#data_shipment_tracking');
            },
            success: function(response) {
			   $('#data_shipment_tracking').empty();
			   $('#link-tracking-shipment').prop('href', '{{ url("/project/tracking/shipment") }}/' + idshipment + '/' + codeshipment);
			   
			   if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_shipment_tracking').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString() + `</td>
								<td>` + val.note + `</td>
								<td><button type="button" onclick="delete_tracking_shipment(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
				}else{
					$('#data_shipment_tracking').append(`
						<tr>
							<td colspan="3">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				loadingClose('#data_shipment_tracking');
            },
            error: function() {
               loadingClose('#step-5');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
		
		$('#modal_tracking_shipment').modal('toggle');
	}
	
	function addTrackingShipmentDetail(element){
		var id = $('#tempshipmentid').val(), note = $('#tracking-shipment-note').val();
		
		$.ajax({
			url: '{{ url("admin/report/project/add_shipment_tracking") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id, note : note
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_shipment_tracking');
			},
			success: function(response) {
				 $('#data_shipment_tracking').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_shipment_tracking').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString() + `</td>
								<td>` + val.note + `</td>
								<td><button type="button" onclick="delete_tracking_shipment(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
				}else{
					$('#data_shipment_tracking').append(`
						<tr>
							<td colspan="3">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#tracking-shipment-note').val('');
				$('#tracking-shipment-note').focus();
				
				loadingClose('#data_shipment_tracking');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function delete_tracking_shipment(element,id){
		$.ajax({
			url: '{{ url("admin/report/project/delete_shipment_tracking") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_shipment_tracking');
			},
			success: function(response) {
				if(response.status == '200'){
					$(element).closest('tr').remove();
				}
				loadingClose('#data_shipment_tracking');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function addTrackingDelivery(iddelivery,codedelivery){
		$('#modal_title_tracking_delivery').html(codedelivery);
		
		$.ajax({
            url: '{{ url("admin/report/project/get_tracking_delivery") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: iddelivery
            },
            beforeSend: function() {
               loadingOpen('#data_delivery_tracking');
            },
            success: function(response) {
               loadingClose('#data_delivery_tracking');
			   $('#data_delivery_tracking').empty();
			   $('#link-tracking-delivery').prop('href', '{{ url("/project/tracking/delivery") }}/' + iddelivery + '/' + replaceAll(codedelivery,'/','-'));
			   if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_delivery_tracking').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString() + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
								<td><button type="button" onclick="delete_tracking_delivery(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
				}else{
					$('#data_delivery_tracking').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				loadingClose('#data_delivery_tracking');
            },
            error: function() {
               loadingClose('#data_delivery_tracking');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
		
		$('#modal_tracking_delivery').modal('toggle');
	}
	
	function addTrackingDeliveryDetail(element){
		var id = $('#tempdeliveryid').val(), note = $('#tracking-delivery-note').val();
		var fd = new FormData(), files = $('#tracking-delivery-file')[0].files;
		fd.append('note',note);
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/report/project/add_delivery_tracking") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_delivery_tracking');
			},
			success: function(response) {
				 $('#data_delivery_tracking').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_delivery_tracking').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString() + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
								<td><button type="button" onclick="delete_tracking_delivery(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
							</tr>
						`);
					});
					
				}else{
					$('#data_delivery_tracking').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#tracking-delivery-file').val('');
				$('#tracking-delivery-note').val('');
				
				loadingClose('#data_delivery_tracking');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function delete_tracking_delivery(element,id){
		$.ajax({
			url: '{{ url("admin/report/project/delete_delivery_tracking") }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_delivery_tracking');
			},
			success: function(response) {
				if(response.status == '200'){
					$(element).closest('tr').remove();
				}
				loadingClose('#data_delivery_tracking');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	$('#sample_unit').on('change', function() {
		if($(this).val() == '1'){
			$('#sample_size').empty().append('<option value="1">20x20</option><option value="2">Full Size</option>');
		}else if($(this).val() == '2'){
			$('#sample_size').empty().append('<option value="2">Full Size</option>');
		}else{
			$('#sample_size').empty();
		}
	});
	
	$('#dropshipper').on('change', function() {
		if($(this).val() == '1'){
			$('#data-dropshipper').hide();
		}else{
			$('#data-dropshipper').show();
		}
	});
	
	function replaceAll(str, find, replace) {
	  return str.replace(new RegExp(find, 'g'), replace);
	}
	
	function editSales(idsales){
		$('#temp_so_id').val(idsales);
		$('#modesales').html('Edit <i class="icon-loop3"></i>');
		$('#modesales').removeClass('btn-info');
		$('#modesales').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/report/project/get_sales_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				idprojectsale : idsales
			 },
			 beforeSend: function() {
				loadingOpen('#step-7');
			 },
			 success: function(response) {
				loadingClose('#step-7');
				if(response){
					
					$('#sales_address').val(response.sales_address);
					$('#sales_note').val(response.sales_note);
					$('html, body').animate({
						scrollTop: $('#step-7').offset().top
					}, 'slow');
					$('#sales_id').empty();
					$('#sales_id').append(`
						<option value="` + response.sales_id + `">` + response.sales_name + `</option>
					`);
				}
			 },
			 error: function() {
				loadingClose('#step-7');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetSales(){
		$('#temp_so_id').val('');
		$('#modesales').html('Add <i class="icon-loop3"></i>');
		$('#modesales').removeClass('btn-warning');
		$('#modesales').addClass('btn-info');
		$('#sales_address').val('');
		$('#sales_note').val('');
		$('#sales_id').empty();
		return false;
	}
	
	function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
	}
	
	/* function confirmDone(){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to finish this project?</h6><label>Finished project can no longer be rolled back.</label>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
				return false;
				notyConfirm.close();
            }),
            Noty.button('<i class="icon-redo"></i>', 'btn bg-success ml-1', function() {
				return true;
            })
         ]
		}).show();
	} */
</script>