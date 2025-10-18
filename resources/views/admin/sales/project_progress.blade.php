<head>
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="{{ asset('template/back-office/calendarjs/css/style.css') }}" rel="stylesheet">
<script src="{{ asset('template/back-office/calendarjs/js/popper.js')}}"></script>
<script src="{{ asset('template/back-office/calendarjs/js/main.js')}}"></script>
</head>
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
					<a href="{{ url('admin/sales/project') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
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
					<a href="{{ url('admin/sales/project') }}" class="breadcrumb-item">Project</a>
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
				  <div class="form-group"><hr></div>
				  <h5 class="card-title"><b><span class="badge badge-danger">1.a</span> Sales Activities Notes</b></h5>
				  <div class="row justify-content-center">
						{{-- <div class="col-md-5">
						  <div class="form-group">
							<input type="text" class="form-control" name="project-note" id="project-note" placeholder="Type project note">
						  </div>
						</div>
						<div class="col-md-5">
						  <div class="form-group">
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="project-file" name="project-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg">
							   </div>
							</div>
						  </div>
						</div> --}}
						<div class="col-md-4">
						  <div class="form-group">
								<button type="button" class="btn bg-success col-6" onclick="reset()"
								data-toggle="modal" data-target="#modal_form">
								<b><i class="icon-plus3"></i></b> Add
								</button>
								<button type="button" class="btn bg-purple btn-labeled btn-labeled-left col-6" data-toggle="modal" data-target="#modal_recap">
								<b><i class="icon-calendar52"></i></b> Recap
								</button>
							 {{-- <button type="button" onclick="addProjectNote()" class="btn bg-success col-12" id="btnaddprojectnote" ><i class="icon-plus2"></i> Add</button> --}}
						  </div>
						</div>
					</div>
					<div class="form-group"><hr></div>
					<div class="form-group">
						<h5><b><span class="badge badge-danger">1.b</span> List of All Sales notes</b></h5>
					</div>
					<div class="form-group"><hr></div>
					<div class="form-group">
						<div class="table-responsive">
							{{-- <table class="table table-bordered table-striped">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th width="25%">Date</th>
									   <th>Note</th>
									   <th>Proof</th>
									</tr>
								 </thead>
								 <tbody id="data_project_note">
									@php $countnotes = 0; @endphp
										@foreach($note as $rownote)
										<tr class="text-center">
											<td>{{ $rownote->created_at }}</td>
											<td>{{ $rownote->note }}</td>
											<td>{!! $rownote->image() ? '<a href="' . $rownote->image() . '" data-lightbox="' . $rownote->notable->code . '" data-title="' . $rownote->notable->code . '"><img src="' . $rownote->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>' !!}</td>
										</tr>
										@php $countnotes++; @endphp
										@endforeach
										@if($countnotes == 0)
										<tr>
											<td colspan="3">
												<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes here.</div>
											</td>
										</tr>
										@endif
								 </tbody>
							</table> --}}
							<table id="datatable_serverside" class="table table-bordered table-striped w-100">
								<thead class="bg-dark">
									<tr class="text-center">
										<th>Date</th>
										<th>Note</th>
										<th>Proof</th>
										<th>Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<div class="form-group"><hr></div>
					<h5 class="card-title"><b><span class="badge badge-danger">1.c</span> Sales Retur Memos</b></h5>
					<div class="row justify-content-center">
						<div class="col-md-2">
						  <div class="form-group">
							 <button type="button" onclick="addProjectReturnMemo()" class="btn bg-success col-12" id="btnaddprojectnote"><i class="icon-plus2"></i> Add</button>
						  </div>
						</div>
					</div>
					<div class="form-group"><hr></div>
					<div class="form-group">
						<h5><b><span class="badge badge-danger">1.d</span> List of All Sales Retur Memos</b></h5>
					</div>
					<div class="form-group"><hr></div>
					<div class="form-group">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead class="table-secondary">
									<tr class="text-center">
									   <th width="25%">Date</th>
									   <th>Sales Order No.</th>
									   <th>Delivery Order No.</th>
									   <th>Reason</th>
									   <th>Proof</th>
									   <th>Detail Product</th>
									   <th>Document</th>
									   <th>Action</th>
									</tr>
								 </thead>
								 <tbody id="data_project_return">
									@if(count($project->projectReturnMemo) > 0)
										@foreach($project->projectReturnMemo as $key => $prm)
											<tr>
												<td class="text-center">{{ date('d M Y',strtotime($prm->date)) }}</td>
												<td class="text-center">{{ $prm->projectDelivery->projectSale->code }}</td>
												<td class="text-center">{{ $prm->projectDelivery->code }}</td>
												<td class="text-center">{{ $prm->reason }}</td>
												<td class="text-center"><a href="{{ $prm->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>
												<td class="text-center">
													@foreach($prm->projectReturnMemoDetail as $row)
														{!! $row->product->name().' <b>'.$row->qty.' '.$row->unit().'</b>' !!},<br>
													@endforeach
												</td>
												<td class="text-center">
													<a onclick="openLink('{{ url('admin/sales/project/print/return_memo/' . base64_encode($prm->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
												</td>
												<td class="text-center">
													<a href="javascript:void(0);" onclick="editReturnMemo({{$prm->id}})" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit"><i class="icon-pencil5"></i></a>
													<a href="javascript:void(0);" onclick="destroyReturnMemo({{$prm->id}})" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete"><i class="icon-trash"></i></a>
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="7">
												<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no return memo here.</div>
											</td>
										</tr>
									@endif
									
								 </tbody>
							</table>
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
                     @if(isset($_GET['step-2']))
                        @if($errors->any())
                           <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
					   <div class="col-md-10">
						  <div class="form-group">
							 <select name="product_id" id="product_id"></select>
						  </div>
					   </div>
					   <div class="col-md-2">
						  <div class="form-group">
							 <button type="button" onclick="addProduct()" class="btn bg-success col-12"><i class="icon-plus2"></i> Add</button>
						  </div>
					   </div>
					</div>
					<div class="alert alert-danger alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Attention!</span> For <span style="font-weight:700;">TILE</span> products please choose <span style="font-weight:700;">METER</span> as product's unit. <b>Press product name to see available stock.</b></span></div>
					<div class="form-group">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" style="min-width:850px;">
							   <thead class="table-secondary">
								  <tr class="text-center">
									<th>No.</th>
									<th>Product</th>
									<th width="15%">Site Area</th>
									<th width="15%">Surface/Spec</th>
									<th width="15%">Qty</th>
									<th width="15%">Unit</th>
									<th width="15%">Info</th>
									<th>Delete</th>
								  </tr>
							   </thead>
							   <tbody id="data_product">
								  @foreach($project->projectProduct()->orderBy('id')->get() as $no => $pp)
									<tr class="text-center rowproduct">
										 <input type="hidden" name="product_id[]" value="{{ $pp->product_id }}">
										 <input type="hidden" name="product_price[]" value="{{ $pp->price }}">
										 <td>{{ $no+1 }}</td>
										 <td class="align-middle">
											<a href="{{ $pp->product->type->image() }}" data-lightbox="{{ $pp->product->name() }}" data-title="{{ $pp->product->name() }}"><img src="{{ $pp->product->type->image() }}" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>
										   <div><a href="javascript:void(0);" onclick="getShading({{ $pp->product_id }})">{{ $pp->product->name() }}</a></div>
										   <div>{{ $pp->product->type->length }}x{{ $pp->product->type->width }}</div>
										 </td>
										 <td class="align-middle">
											<textarea type="text" name="product_area[]" class="form-control" placeholder="Type area" required rows="5">{{ $pp->area }}</textarea>
										 </td>
										 <td class="align-middle">
											<textarea name="product_spec[]" class="form-control" placeholder="Type spec" rows="5" required>{{ $pp->spec }}</textarea>
										 </td>
										 <td class="align-middle">
											<input type="text" name="product_qty[]" class="form-control" placeholder="0" required value="{{ $pp->qty }}" onkeyup="convertToBox(this,{{ (($pp->product->type->length * $pp->product->type->width) / 10000) * $pp->product->carton_pcs }},{{ $no+1 }})">
										 </td>
										 <td class="align-middle">
											<select name="product_unit[]" class="custom-select" required>
											   <option value="1" {{ $pp->unit == 1 ? 'selected' : '' }}>Pcs</option>   
											   <option value="3" {{ $pp->unit == 3 ? 'selected' : '' }}>Meter</option>   
											   <option value="4" {{ $pp->unit == 4 ? 'selected' : '' }}>Meter(custom)</option>   
											</select>
										 </td>   
										 <td class="align-middle">
											<div class="input-group">
											   <a class="btn btn-primary btn-sm" href="#collapse-icon{{ $pp->product_id }}" data-toggle="collapse"><i class="icon-info3"></i></a>
											</div>
											<div class="collapse" id="collapse-icon{{ $pp->product_id }}">
												<div class="mt-3">
												{{ $pp->product->carton_pcs }} PCS/CTN,
												{{ ($pp->product->type->length * $pp->product->type->width)/10000 * $pp->product->carton_pcs }} M<sup>2</sup>
												</div>
											</div>
											<br>Unit in Box <span class="badge badge-success" id="convertedBox{{ $no+1 }}">{{ $pp->unit == '2' || $pp->unit == '3' ? sprintf("%0.2f",$pp->qty / ((($pp->product->type->length * $pp->product->type->width) / 10000) * $pp->product->carton_pcs)) : 0 }}</span>
										 </td>  
										 <td class="align-middle">
											<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
										 </td>
									</tr>
								  @endforeach
							   </tbody>
							</table>
						</div>
					</div>
					<div class="form-group">
						<div class="alert bg-warning text-white alert-styled-left alert-dismissible mt-2 warning-product" style="display:none;">
							 <button type="button" class="close" data-dismiss="alert">
								<span>&times;</span>
							 </button>
							 You have changed the data of table, if you want to save changes please press Save & Next Button.
						</div>
						<hr>
					</div>
					<div class="form-group">
					   <div class="text-right">
						  <a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(2,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
						  &nbsp;
						  <button type="submit" name="submit" value="step-2" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
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
                        @if(isset($_GET['step-3']))
                           @if($errors->any())
                              <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Date :<sup class="text-danger">*</sup></label>
                                    <input type="date" name="consultant_date" id="consultant_date" class="form-control">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Person :<sup class="text-danger">*</sup></label>
                                    <input type="text" name="consultant_person" id="consultant_person" class="form-control" placeholder="Enter name person">
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <label>Result :<sup class="text-danger">*</sup></label>
                                    <textarea name="consul_result" id="consultant_result" class="form-control" placeholder="Enter result"></textarea>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-group text-center">
                                    <button type="button" onclick="addConsultant()" class="btn bg-success col-3"><i class="icon-plus2"></i> Add</button>
                                 </div>
                              </div>
                           </div>
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

											   <td class="align-middle">{{ $pcm->date }}</td>   
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
                           <div class="form-group">
								<div class="alert bg-warning text-white alert-styled-left alert-dismissible mt-2 warning-consultant" style="display:none;">
									 <button type="button" class="close" data-dismiss="alert">
										<span>&times;</span>
									 </button>
									 You have changed the data of table, if you want to save changes please press Save & Next Button.
								</div>
							<hr>
							</div>
                           <div class="form-group">
                              <div class="text-right">
								<a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(3,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
								&nbsp;
								<a href="#step-2" class="btn bg-info">Revise Material <i class="icon-git-compare"></i></a>
								&nbsp;
                                <button type="submit" name="submit" value="step-3" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
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
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">4</span> Quotation</b>  <a href="javascript:void(0);" class="btn btn-info float-right" onclick="resetQuotation({{ $project->id }})">Reset <i class="icon-loop3"></i></a></h3>
                        <div class="form-group"><hr></div>
                        @if(isset($_GET['step-4']))
                           @if($errors->any())
                              <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
						<div class="alert alert-danger alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold"><b>WARNING!</b></span><b> Any number except for 0 for discount 1, will create price strikethrough format (1) and revision will be 1. Any number except for 0 for discount 2, will also create price strikethrough format (2) and revision will be 2 and so on. Base price or first price is taken from COGS Price List.</b>
						</div>
						<div class="row">
							<div class="col-md-4">
							  <div class="form-group">
								<label>Discount :<sup class="text-danger">*</sup> <b>(From Project Creation)</b></label>
								<input type="text" name="discount" id="discount" class="form-control" placeholder="0" value="{{ number_format($project->discount,2,',','.') }}" onkeyup="formatRupiah(this)">
							  </div>
							</div>
						</div>
						<div class="form-group"><hr></div>
							<h3 class="card-title"><b><span class="badge badge-danger">4.a</span> Other Cost</b></h3>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
                                    <label>PPn :<sup class="text-danger">*</sup></label>
									<select name="ppn_cost_quotation" id="ppn_cost_quotation" class="custom-select">
									   <option value="0">No</option>   
									   <option value="1">Yes</option>   
									</select>
								</div>
							</div>
							<div class="col-md-4">
							  <div class="form-group">
								<label>Delivery Cost :<sup class="text-danger">*</sup></label>
								<input type="text" name="delivery_cost_quotation" id="delivery_cost_quotation" class="form-control" placeholder="0" value="{{ number_format($project->delivery_cost,2,',','.') }}" onkeyup="formatRupiah(this)">
							  </div>
							</div>
							<div class="col-md-4">
							  <div class="form-group">
								<label>Cutting Cost :<sup class="text-danger">*</sup></label>
								<input type="text" name="cutting_cost_quotation" id="cutting_cost_quotation" class="form-control" placeholder="0" value="{{ number_format($project->cutting_cost,2,',','.') }}" onkeyup="formatRupiah(this)">
							  </div>
							</div>
							<div class="col-md-4">
							  <div class="form-group">
								<label>Miscellaneous Cost :<sup class="text-danger">*</sup></label>
								<input type="text" name="misc_cost_quotation" id="misc_cost_quotation" class="form-control" placeholder="0" value="{{ number_format($project->misc_cost,2,',','.') }}" onkeyup="formatRupiah(this)">
							  </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
                                    <label>Display Brand :<sup class="text-danger">*</sup></label>
									<select name="display_brand" id="display_brand" class="custom-select">
									   <option value="0">Show</option>   
									   <option value="1">Hide</option>   
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
                                    <label>Letter Head :<sup class="text-danger">*</sup></label>
									<select name="letter_head" id="letter_head" class="custom-select">
									   <option value="1" {{$letter_head == 1 ? 'selected' : ''}}>PTA</option>   
									   <option value="2" {{$letter_head == 2 ? 'selected' : ''}}>PSI</option>   
									   <option value="3" {{$letter_head == 3 ? 'selected' : ''}}>MKJ</option>   
									   <option value="4" {{$letter_head == 4 ? 'selected' : ''}}>Bellaquattro</option>   
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<a class="btn btn-primary btn-block mt-4" data-toggle="collapse" href="#collapse-custom">
										Custom Terms & Conditions
									</a>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group collapse" id="collapse-custom">
							<div class="text-right">
								<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
									<li class="nav-item"><a href="#tos_id" class="nav-link active" data-toggle="tab">ID</a></li>
									<li class="nav-item"><a href="#tos_en" class="nav-link" data-toggle="tab">EN</a></li>
								</ul>
						
								<div class="tab-content">
									<div class="tab-pane fade show active" id="tos_id">
										<div class="form-group">
											<textarea name="custom_tos_id" id="custom_tos_id" class="description">{!!$term_id!!}</textarea>
										</div>
									</div>
									<div class="tab-pane fade show" id="tos_en">
										<div class="form-group">
											<textarea name="custom_tos_en" id="custom_tos_en" class="description">{!!$term_en!!}</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
                        <div class="form-group">
                           <div class="table-responsive">
                              <table class="table table-bordered table-striped" style="min-width:850px;">
                                 <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th>Product</th>
                                       <th>Qty</th>
                                       <th>Price</th>
									   <th width="10%">Disc 1(%)</th>
                                       <th>Disc 1 (Rp)</th>
									   <th width="10%">Disc 2(%)</th>
									   <th>Disc 2 (Rp)</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($project->projectProduct()->orderBy('id')->get() as $no => $pp)
                                       <tr class="text-center">
                                          <input type="hidden" name="project_product_id[]" value="{{ $pp->id }}">
										  <input type="hidden" name="project_product_unit[]" value="{{ $pp->unit }}">
										  <input type="hidden" name="project_product_price[]" value="{{ $pp->product->pricingPolicy ? $pp->product->pricingPolicy->price_list : 0 }}">
										  <input type="hidden" name="project_product_qty[]" value="{{ $pp->qty }}">
										  <input type="hidden" name="project_product_area[]" value="{{ $pp->area }}">
                                          <td class="align-middle">
                                             <a href="{{ $pp->product->type->image() }}" data-lightbox="{{ $pp->product->name() }}" data-title="{{ $pp->product->name() }}"><img src="{{ $pp->product->type->image() }}" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>
                                             <div><a href="javascript:void(0);" onclick="getShading({{ $pp->product->id }})">{{ $pp->product->name() }}</a></div>
                                             <div>{{ $pp->product->type->length }}x{{ $pp->product->type->width }}</div>
											 @if(in_array(1, session('bo_role')) || in_array(5, session('bo_role')))
											 <div class="badge badge-info" style="font-size:100% !important;">
												Bottom Price :
												<b> Rp {{ isset($pp->product->pricingPolicy->bottom_price) ? number_format($pp->product->pricingPolicy->bottom_price, 2, ',', '.') : 0 }} </b>
											 </div>
											 @endif
                                          </td>
                                          <td class="align-middle">{{ $pp->qty.' '.$pp->unit() }}</td>
										  @if(session('bo_branch') == '1')
                                          <td class="align-middle">Rp {{ $pp->budgetPrice() ? number_format($pp->budgetPrice(), 2, ',', '.') : ($pp->product->pricingPolicy ? number_format($pp->product->pricingPolicy->price_list, 2, ',', '.') : 0) }}</td>
										  @else
										  <td class="align-middle">Rp {{ $pp->product->pricingPolicy ? number_format($pp->product->pricingPolicy->price_list, 2, ',', '.') : 0 }}</td>
										  @endif
										  <td class="align-middle">
                                                <input type="text" name="product_discount_helper" class="form-control" value="0" placeholder="Discount 1 helper" required onkeyup="countBestPrice1(this,{{ $no }},{{ $pp->latestQuotationPrice()['price'] }})">
                                          </td> 
                                          <td class="align-middle">
											   <input type="text" name="product_recommended_price[]" class="form-control" value="{{ number_format($pp->latestQuotationPrice()['recommended_price'],2,',','.') }}" required onkeyup="formatRupiah(this);" id="discount-price{{ $no }}">
                                          </td>
										  <td class="align-middle">
                                                <input type="text" name="product_discount[]" class="form-control" value="{{ number_format($pp->discount, 0, ',', '.') }}" placeholder="Discount 2 helper" required onkeyup="countBestPrice(this,{{ $no }})">
                                          </td> 
										  <td class="align-middle">
												<input type="text" name="product_best_price[]" class="form-control" value="{{ number_format($pp->latestQuotationPrice()['best_price'], 2, ',', '.') }}" placeholder="0" required onkeyup="formatRupiah(this)" id="best-price{{ $no }}">
                                          </td>
                                       </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                        </div>
					   <div class="form-group"><hr></div>
					   <div class="form-group">
						  <div class="alert alert-danger alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Attention!</span><b> Quotation will immediately update product price and additional costs on available Sales Order in this project. So please be careful.</b>
						  </div>
						  <div class="text-right">
							<a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(4,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
							&nbsp;
							<button type="submit" name="submit" value="step-4" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
					   </div>
						@php
							$approval = false;
							
							$pq = $project->latestQuotation();
							if(isset($pq->approved_1->name) || isset($pq->approved_2->name)){
								$approval = true;
							}
						@endphp
					   
					   <div class="form-group"><hr></div>
						<div class="form-group text-center">
							<div class="row">
								<div class="col-md-6">
									<a class="btn btn-danger btn-block {{ $approval == false ? 'blink-notification' : '' }}" data-toggle="collapse" href="#collapse-link-collapsed1">
										4.a See Approval List
									</a>
								</div>
								<div class="col-md-6">
									<a class="btn btn-primary btn-block" data-toggle="collapse" href="#collapse-link-collapsed2">
										4.b See History of All Quotation Order
									</a>
								</div>
							</div>
						</div>
					    <div class="form-group"><hr></div>
						<div class="form-group collapse" id="collapse-link-collapsed1">
							<h5><b><span class="badge badge-danger">4.a</span> Need Approval List</b></h5>
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
													if($project->user->branch == '1'){
														echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveQuotation(1,'.$pq->id.')"><i class="icon-checkmark2"></i></button>';
													}else{
														echo 'Waiting';
													}
												}
											@endphp
										  </td>   
                                          <td class="align-middle">
											@php
												if(isset($pq->approved_2->name)){
													echo $pq->approved_2->name;
												}else{
													if($project->user->branch == '1'){
														echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveQuotation(2,'.$pq->id.')"><i class="icon-checkmark2"></i></button>';
													}else{
														echo 'Waiting';
													}
												}
											@endphp
										  </td>
                                       </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                        </div>
						<div class="form-group collapse" id="collapse-link-collapsed2">
							<h5><b><span class="badge badge-danger">4.b</span> History of All Quotation Order</b></h5>
							<div class="text-right">
								@if(count($project->projectQuotation) > 0)
									@foreach($project->projectQuotation as $pq)
										<a onclick="openLink('{{ url('admin/sales/project/print/quotation_order/' . base64_encode($pq->id)) }}')" href="javascript:void(0);" class="btn bg-info mr-1 ml-1 mt-1">Quotation (Rev-{{ $pq->revision - 1 }}) <i class="icon-file-pdf"></i> - ENG</a>
										<a onclick="openLink('{{ url('admin/sales/project/print/quotation_order/' . base64_encode($pq->id)) }}?la=idn')" href="javascript:void(0);" class="btn bg-success mr-1 ml-1 mt-1">Quotation (Rev-{{ $pq->revision - 1 }}) <i class="icon-file-pdf"></i> - IDN</a>
									@endforeach
								@else
									<button class="btn bg-danger" onclick="return false;">Empty <i class="icon-file-pdf"></i></button>
								@endif
							</div>
						</div>
                     </div>
                  </form>
               </div>
            @endif
			@php
				$approval = false;
				$pq = $project->latestQuotation();
				if(isset($pq->approved_1->name) || isset($pq->approved_2->name)){
					$approval = true;
				}
				if($approval == false){
					echo '<div class="alert bg-warning text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Warning!</span> Please contact Sales Manager to Approve one of the required approvals to continue to the next step.
						</div>';
				}
			@endphp
            @if($project->progress >= 25 || $approval == true)
               <div class="card" id="step-5">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">5</span> Form Sample</b></h3>
                        <div class="form-group"><hr></div>
                        @if(isset($_GET['step-5']))
                           @if($errors->any())
                              <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
                                    <label>Sent Date :<sup class="text-danger">*</sup></label>
                                    <input type="date" name="sample_sent_date" id="sample_sent_date" class="form-control" required>
                                 </div>
                              </div>
							  <div class="col-md-4">
                                 <div class="form-group">
                                    <label>Return Date :<sup class="text-danger">*</sup></label>
                                    <input type="date" name="sample_return_date" id="sample_return_date" class="form-control" required>
                                 </div>
                              </div>
							  <div class="col-md-4">
                                 <div class="form-group">
                                    <label>Note :</label>
                                    <textarea name="sample_note" id="sample_note" class="form-control" placeholder="Enter note" rows="1"></textarea>
                                 </div>
                              </div>
							</div>
							<div class="form-group"><hr></div>
							<h5><b><span class="badge badge-danger">5.a</span> Detail Product Sample</b></h5>
							<div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Product :<sup class="text-danger">*</sup></label>
                                    <select name="sample_product_id" id="sample_product_id"></select>
                                 </div>
                              </div>
                              <div class="col-md-2">
                                 <div class="form-group">
                                    <label>Qty :<sup class="text-danger">*</sup></label>
                                    <input type="number" name="sample_qty" id="sample_qty" class="form-control" placeholder="0">
                                 </div>
                              </div>
							  <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unit :<sup class="text-danger">*</sup></label>
									<select name="sample_unit" id="sample_unit" class="custom-select">
									   <option value="1">Pcs</option>   
									   <option value="2">Box</option>   
									   <option value="4">Meter(Custom)</option>   
									</select>
								</div>
							  </div>
                              <div class="col-md-2">
                                 <div class="form-group">
                                    <label>Size :<sup class="text-danger">*</sup></label>
                                    <select name="sample_size" id="sample_size" class="custom-select">
									   <option value="1">20x20</option>   
									   <option value="2">Full Size</option>   
									</select>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-group text-center">
                                    <button type="button" onclick="addSample()" class="btn bg-success col-3"><i class="icon-plus2"></i> Add</button>
                                 </div>
                              </div>
                           </div>
                           <div class="table-responsive">
                              <table class="table table-bordered table-striped">
                                 <thead class="table-secondary">
                                    <tr class="text-center">
                                       <th>Product</th>
                                       <th>Qty</th>
									   <th>Unit</th>
                                       <th>Size</th>
									   <th>Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody id="data_sample">
                                    
                                 </tbody>
                              </table>
                           </div>
						   <div class="form-group"><hr></div>
                           <div class="form-group">
                              <div class="text-right">
								 <a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(5,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
								 &nbsp;
                                 <button type="submit" name="submit" value="step-5" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
                              </div>
                           </div>
						   <div class="form-group"><hr></div>
						   <div class="form-group text-center">
								<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed2">
									See All Sample-to-give Details
								</a>
							</div>
						   <div class="form-group collapse" id="collapse-link-collapsed2">
								<h5><b><span class="badge badge-danger">5.b</span> Sample-to-give Details</b></h5>
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
										   <th>Proof</th>
										   <th>Action</th>
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
												<select class="custom-select status-sample" onchange="updateStatusSample(this.value,{{ $ps->id }});">
												   <option value="1" {{ $ps->status == '1' ? 'selected' : '' }}>Borrowed</option>   
												   <option value="2" {{ $ps->status == '2' ? 'selected' : '' }}>Returned</option>
												   <option value="3" {{ $ps->status == '3' ? 'selected' : '' }}>No need to return</option>
												</select>
											  </td>
											  <td>
													<a onclick="openLink('{{ url('admin/sales/project/print/sample_order/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
											  </td>
											  <td>
												@if($ps->return_proof)
													<a onclick="openLink('{{ $ps->attachment() }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
												@else
													<a class="btn bg-info" href="javascript:void(0);" onclick="addSampleProof({{ $ps->id }},'{{ $ps->code}} ')"><i class="icon-file-check2"></i></a>
												@endif
												<td>
													<a href="javascript:void(0);" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="deleteSample({{ $ps->id }})"><i class="icon-trash"></i></a>
												</td>
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
			@php
				$approval = false;
				$ps = $project->latestSample();
				if($ps){
					if(isset($ps->approved_1->name) && isset($ps->approved_2->name)){
						$approval = true;
					}
				}else{
					if($project->progress >= 30){
						$approval = true;
					}
				}
				
				if($approval == false){
					echo '<div class="alert bg-warning text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Warning!</span> Please contact Sales Manager, Purchasing, and Admin Sales & Stock to Approve the required approvals to continue to the next step.
						</div>';
				}
			@endphp
            @if($project->progress >= 30 && $approval == true)
               <div class="card" id="step-6">
                  <form action="{{ url()->full() }}" method="POST">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">6</span> Negotiation Progress Report</b></h3>
                        <div class="form-group"><hr></div>
                        @if(isset($_GET['step-6']))
                           @if($errors->any())
                              <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
						  <div class="col-md-6">
							 <div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="negotiation_date" id="negotiation_date" class="form-control">
							 </div>
						  </div>
						  <div class="col-md-6">
							 <div class="form-group">
								<label>Person :<sup class="text-danger">*</sup></label>
								<input type="text" name="negotiation_person" id="negotiation_person" class="form-control" placeholder="Enter name person">
							 </div>
						  </div>
						  <div class="col-md-12">
							 <div class="form-group">
								<label>Result :<sup class="text-danger">*</sup></label>
								<textarea name="negotiation_result" id="negotiation_result" class="form-control" placeholder="Enter result"></textarea>
							 </div>
						  </div>
						  <div class="col-md-12">
							 <div class="form-group text-center">
								<button type="button" onclick="addNegotiation()" class="btn bg-success col-3"><i class="icon-plus2"></i> Add</button>
							 </div>
						  </div>
					   </div>
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
                           <div class="form-group">
								<div class="alert bg-warning text-white alert-styled-left alert-dismissible mt-2 warning-negotiation" style="display:none;">
									 <button type="button" class="close" data-dismiss="alert">
										<span>&times;</span>
									 </button>
									 You have changed the data of table, if you want to save changes please press Save & Next Button.
								</div>
							<hr>
						</div>
						<div class="form-group">
						  <div class="text-right">
							<a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(6,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
								 &nbsp;
							<a href="#step-4" class="btn bg-info">Revise Quotation <i class="icon-git-compare"></i></a>
							&nbsp;
							<button type="submit" name="submit" value="step-6" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
                     </div>
                  </form>
               </div>
            @endif			
@php
	if($project->budgetingProjectByDate() == true){
@endphp			
            @if($project->progress >= 35)
               <div class="card" id="step-7">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">7</span> SO Project</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modesales" onclick="resetSales()">Add <i class="icon-loop3"></i></a></h3>
						<div class="form-group"><hr></div>
                        @if(isset($_GET['step-7']))
                           @if($errors->any())
                              <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
						<div class="row d-none edit-so">
							<div class="col-md-12">
								 <div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason" id="edit_reason" class="form-control" placeholder="Please describe why you edit this Sales Order." rows="2"></textarea>
							    </div>
							</div>
						</div>
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Attention!</span> Sales order products will be taken from the latest products generated from Quotation Process / Specs Project. <br>If you want to create new Sales order, you may change the products on Spec Project & Target Price form section.
						</div>
						<div class="row">
							<div class="col-md-4">
							 <div class="form-group">
								<input type="hidden" id="temp_so_id" name="temp_so_id" value="">
								<label>Sales :<sup class="text-danger">*</sup></label>
								<select name="sales_id" id="sales_id"></select>
							 </div>
							</div>
							<div class="col-md-4">
                                 <div class="form-group">
									<label>Customer PO :</label>
                                    <div class="input-group">
                                       <div class="custom-file">
                                          <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                       </div>
                                    </div>
                                 </div>
                            </div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Date :<sup class="text-danger">*</sup></label>
									<input class="form-control" type="date" name="date_so" id="date_so" value="{{ old('date_so') ? old('date_so') : date('Y-m-d') }}">
								</div>
							</div>
							<div class="col-md-4">
								 <div class="form-group">
									<label>Delivery Address :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="sales_address" id="sales_address" class="form-control" placeholder="Enter address here" rows="1"></textarea>
							    </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Currency :<span class="text-danger">*</span></label>
									<select name="currency_id" id="currency_id" class="select2">
										 @foreach($currency as $c)
											@if($c->id == 5)
												<option value="{{ $c->id }}" selected>{{ $c->code }}</option>
											@else
												<option value="{{ $c->id }}">{{ $c->code }}</option> 
											@endif
										 @endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
							  <div class="form-group">
								<label>Currency Rate :<sup class="text-danger">*</sup></label>
								<input type="text" name="currency_rate" id="currency_rate" class="form-control" placeholder="0" value="1" onkeyup="formatRupiah(this)">
							  </div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Note :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="sales_note" id="sales_note" class="form-control" placeholder="Enter note here" rows="1"></textarea>
							    </div>
							</div>
							<div class="col-md-8">
								<div class="alert alert-info alert-styled-left alert-dismissible">
									<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
									<span class="font-weight-semibold">Important Info!</span> Default currency is set to IDR, if you want to convert / show to another currency, you may choose another currency but the system will still count based on IDR.
								</div>
							</div>
						</div>
						<div class="form-group"><hr></div>
						<h3 class="card-title"><b><span class="badge badge-danger">7.a</span> Other Cost</b></h3>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
                                    <label>PPn :<sup class="text-danger">*</sup></label>
									<select name="ppn_cost" id="ppn_cost" class="custom-select">
									   <option value="0">No</option>   
									   <option value="1">Yes</option>   
									</select>
								</div>
							</div>
							<div class="col-md-4">
							  <div class="form-group">
								<label>Delivery Cost :<sup class="text-danger">*</sup></label>
								<input type="text" name="delivery_cost" id="delivery_cost" class="form-control" placeholder="0" value="{{ number_format($project->delivery_cost,2,',','.') }}" onkeyup="formatRupiah(this)">
							  </div>
							</div>
							<div class="col-md-4">
							  <div class="form-group">
								<label>Cutting Cost :<sup class="text-danger">*</sup></label>
								<input type="text" name="cutting_cost" id="cutting_cost" class="form-control" placeholder="0" value="{{ number_format($project->cutting_cost,2,',','.') }}" onkeyup="formatRupiah(this)">
							  </div>
							</div>
							<div class="col-md-4">
							  <div class="form-group">
								<label>Miscellaneous Cost :<sup class="text-danger">*</sup></label>
								<input type="text" name="misc_cost" id="misc_cost" class="form-control" placeholder="0" value="{{ number_format($project->misc_cost,2,',','.') }}" onkeyup="formatRupiah(this)">
							  </div>
							</div>
						</div>
						<div class="form-group"><hr></div>
						<h3 class="card-title"><b><span class="badge badge-danger">7.b</span> Additional Cost</b></h3>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
                                    <label>Middleman Fee :<sup class="text-danger">*</sup></label>
									<select name="mid_yes_no" id="mid_yes_no" class="custom-select">
									   <option value="0">No</option>   
									   <option value="1">Yes</option>   
									</select>
								</div>
							</div>
							<div class="col-md-4 mid-hide">
								<div class="form-group">
                                    <label>Type :<sup class="text-danger">*</sup></label>
									<select name="mid_type" id="mid_type" class="custom-select">
									   <option value="1">Percentage</option>   
									   <option value="2">Nominal(Per QTY)</option>   
									   <option value="3">Nominal</option>   
									</select>
								</div>
							</div>
							<div class="col-md-4 mid-hide">
							  <div class="form-group">
								<label>Value :<sup class="text-danger">*</sup></label>
								<input type="text" name="mid_fee" id="mid_fee" class="form-control" placeholder="0" value="0" onkeyup="formatRupiah(this)">
							  </div>
							</div>
							<div class="col-md-4 mid-hide">
							  <div class="form-group">
								<label>Note / Bank Account No. :<sup class="text-danger">*</sup></label>
								<input type="text" name="mid_note" id="mid_note" class="form-control" placeholder="Note here..."value="-">
							  </div>
							</div>
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group">
							<h5><b><span class="badge badge-danger">7.c</span> Product Shadings Information</b></h5>
							<div class="alert alert-danger alert-styled-left alert-dismissible">
								<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
								<span class="font-weight-semibold">Attention!</span> The shadings information below were taken from Ventura every 5 minutes, please kindly check the stock available to Ventura to confirm that the stock is valid. <span class="font-weight-semibold">Also, please input ventura code and warehouse code in Product Code Form if shading is not shown here!</span>
							</div>
						   <div class="table-responsive">
								<table class="table table-bordered table-striped">
								   <thead class="table-secondary">
									  <tr class="text-center">
										<th>No.</th>
										<th width="15%">Product</th>
										<th>Qty Ordered</th>
										<th>Qty Converted</th>
										<th>Shading</th>
										<th>Status</th>
									  </tr>
								   </thead>
								   <tbody id="data_product">
									  @foreach($project->projectProduct as $no => $pp)
										@php
											$qty = 0;
											$unit = '';
											if($pp->unit == '2' || $pp->unit == '3'){
												$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
								
												if($m2 < 1.1){
													$countbox = $pp->qty;
												}else{
													$countbox = ceil(round($pp->qty / $m2,2));
												}
												
												$qty = $countbox;
												$unit = $pp->unit == 3 ? 'Box' : $pp->unit();
											}else{
												$qty = $pp->qty;
												$unit = $pp->unit == 4 ? 'Meter' : 'pcs';
											}
										@endphp
										<tr class="text-center">
											 <input type="hidden" name="product_id[]" value="{{ $pp->product_id }}">
											 <input type="hidden" name="product_price[]" value="{{ $pp->price }}">
											 <td>{{ $no+1 }}</td>
											 <td class="align-middle">
												<a href="{{ $pp->product->type->image() }}" data-lightbox="{{ $pp->product->name() }}" data-title="{{ $pp->product->name() }}"><img src="{{ $pp->product->type->image() }}" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>
											   <div><a href="javascript:void(0);" onclick="getShading({{ $pp->product_id }})">{{ $pp->product->name() }}</a></div>
											   <div>{{ $pp->product->type->length }}x{{ $pp->product->type->width }}</div>
											 </td>
											 <td class="align-middle">
												{{ $pp->qty.' '.$pp->unit() }}
											 </td>  
											 <td class="align-middle">
												{{ $qty.' '.$unit }}
											 </td>  
											 <td class="text-center align-middle">
												@php
												$temprest = 0;
												$rest = $qty;
												if(count($pp->product->productShading) > 0){
													foreach($pp->product->productShading()->orderByDesc('qty')->get() as $shading){
														
														if($rest >= $shading->qty){
															$used = $shading->qty;
														}else{
															$used = $rest;
														}
														
														if($rest >= 0 && $used > 0){
															echo '<input type="hidden" name="shading_product_id[]" value="'.$shading->product_id.'">';
															echo '<input type="hidden" name="shading_warehouse_code[]" value="'.$shading->warehouse_code.'">';
															echo '<input type="hidden" name="shading_stock_code[]" value="'.$shading->stock_code.'">';
															echo '<input type="hidden" name="shading_code[]" value="'.$shading->code.'">';
															echo '<input type="hidden" name="shading_qty[]" value="'.$used.'">';
															echo $shading->warehouse->name.' Code : <b>'.$shading->code.'</b> Stock : <b>'.$shading->qty.'</b> Used : <b>'.$used.'</b> Rest : <b>'.($shading->qty-$used).'</b><br>';
														}
														
														if($rest >= $shading->qty){
															$rest -= $shading->qty;
															$temprest = $rest;
														}else{
															$rest = -1;
														}
													}
													//echo $temprest;
												}else{
													echo '<span class="badge badge-danger">Stock Not Available</span>';
												}
												@endphp
											 </td>
											 <td class="text-center align-middle">
												@php
													if($rest > 0){
														echo '<span class="badge badge-danger">Indent : '.$rest.'</span>';
													}else{
														echo '<span class="badge badge-success">Available</span>';
													}
  												@endphp	
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
							 <a href="#step-2" class="btn bg-info">Revise Material <i class="icon-git-compare"></i></a>
							 &nbsp;
							 <button type="submit" name="submit" value="step-7" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed3">
								See All Sales Order
							</a>
						</div>
						<div class="form-group collapse" id="collapse-link-collapsed3">
							<h5><b><span class="badge badge-danger">7.d</span> List of All Sales Order</b></h5>
							<div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>SO No.</th>
									   <th>Total</th>
									   <th>Sales</th>
									   <th>Date</th>
									   <th>Marketing</th>
									   <th>Ack. By</th>
									   <th>PO Customer</th>
									   <th>SO Product</th>
									   <th>SO Service</th>
									   <th>Mid Fee</th>
									   <th>Add Notes</th>
									   <th>Edit</th>
									   <th>Delete</th>
									</tr>
								 </thead>
								 <tbody>
									@foreach($project->projectSale as $ps)
									   <tr class="text-center">
										  <td class="align-middle">{{ $ps->code }}</td>
										  <td class="align-middle">Rp{{ $ps->getTotal() }}</td>
										  <td class="align-middle">{{ $ps->sales->name }}</td>
										  <td class="align-middle">{{ date('d M Y',strtotime($ps->created_at)) }}</td>   
										  <td class="align-middle">
											@php
												if(isset($ps->marketing->name)){
													echo $ps->marketing->name;
												}else{
													/* echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSale(1,'.$ps->id.')"><i class="icon-checkmark2"></i></button>'; */
													echo 'Waiting';
												}
											@endphp
										  </td>   
										  <td class="align-middle">
											@php
												if(isset($ps->approved->name)){
													echo $ps->approved->name;
												}else{
													/* echo '<button type="button" class="btn btn-primary btn-icon" onclick="approveSale(2,'.$ps->id.')"><i class="icon-checkmark2"></i></button>'; */
													echo 'Waiting';
												}
											@endphp
										  </td>
										  <td class="align-middle">
											<a href="{{ $ps->attachment() }}" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
										  </td>
										  <td>
											<a onclick="openLink('{{ url('admin/sales/project/print/sales_order/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td>
											<a onclick="openLink('{{ url('admin/sales/project/print/sales_cost/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-success"><i class="icon-file-pdf"></i></a>
										  </td>
										  <td>
											@php
												if($ps->mid_yes_no == '1'){
											@endphp
												<a onclick="openLink('{{ url('admin/sales/project/print/sales_news/'. base64_encode($ps->id)) }}')" href="javascript:void(0);" class="btn bg-primary"><i class="icon-file-pdf"></i></a>
											@php
												}else{
													echo 'None';
												}
											@endphp
										  </td>
										  <td>
											<a href="javascript:void(0);" data-popup="tooltip" title="Add Notes" onclick="addNotes({{ $ps->id }})" class="btn bg-warning btn-sm"><i class="icon-file-text"></i></a>
										  </td>
										  <td>
											<a href="#step-7" onclick="editSales({{ $ps->id }})" class="btn bg-info"><i class="icon-pencil5"></i></a>
										  </td>
										  <td>
											<a href="javascript:void(0);" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="deleteSales({{ $ps->id }})"><i class="icon-trash"></i></a>
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
			@php
				$approval = false;
				$ps = $project->latestSale();
				if(isset($ps->marketing->name) || isset($ps->approved->name)){
					$approval = true;
				}
				
				if($approval == false){
					echo '<div class="alert bg-warning text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Warning!</span> Please contact Sales Manager & Head of Accounting to approve the required approvals to continue to the next step.
						</div>';
				}
			@endphp	
			@if($project->progress >= 37 && $approval == true)
               <div class="card" id="step-8">
                  <form action="{{ url()->full() }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">8</span> Down Payment</b> <a href="javascript:void(0);" class="btn btn-info float-right" id="modepayment" onclick="resetPayment()">Add <i class="icon-loop3"></i></a></h3>
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
						@if(isset($_GET['step-8']))
						  @if($errors->any())
							 <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
						  <div class="col-md-12 text-center">
							<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Attention!</span> Please select sales order first to see total sales payment, paid, and balance not paid in buttons below.</div>
							<button type="button" class="btn btn-primary mb-2">Total : <i class="icon-cash4 mr-2"></i> <b id="payment_total">0</b></button>
							&nbsp;
							<button type="button" class="btn btn-success mb-2">Paid : <i class="icon-cash4 mr-2"></i> <b id="payment_paid">0</button>
							&nbsp;
							<button type="button" class="btn btn-warning mb-2">Balance not paid : <i class="icon-cash4 mr-2"></i> <b id="payment_left">0</button>
						  </div>
							<div class="col-md-12 d-none edit-sp">
								 <div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea type="text" name="edit_reason_payment" id="edit_reason_payment" class="form-control" placeholder="Please describe why you edit this Sales Order Payment." rows="2"></textarea>
							    </div>
							</div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Sales Order :<sup class="text-danger">*</sup></label>
								<input type="hidden" id="temp_pay_id" name="temp_pay_id" value="">
								<select name="sopd_id" id="sopd_id" class="select2" onchange="getSalesInfo(this,this.value);">
								   <option value="">-- Choose --</option>
								   @foreach($project->projectSale as $ps)
									  <option value="{{ $ps->id }}">{{ $ps->code }}</option>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>From Bill :</label>
								<select name="bill_id" id="bill_id" class="select2">
								   <option value="">-- None --</option>
								   @foreach($project->projectBill as $pb)
									  <option value="{{ $pb->id }}">{{ $pb->code }}</option>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Date :<sup class="text-danger">*</sup></label>
								<input type="date" name="date_create" id="date_create" class="form-control" value="{{ old('date_create') }}" onchange="countDueDate()">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Payment :<sup class="text-danger">*</sup></label>
								<select name="payment_method" id="payment_method" class="custom-select" onchange="">
								   <option value="">-- Choose --</option>
								   <option value="1" {{ old('payment_method') == 1 ? 'selected' : '' }}>Down Payment</option>
								   <option value="2" {{ old('payment_method') == 2 ? 'selected' : '' }} disabled>Full Payment Upfront</option>
								   <option value="2" {{ old('payment_method') == 3 ? 'selected' : '' }} disabled>Full Payment Last</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Percentage :<sup class="text-danger">*</sup></label>
								<input type="number" name="percentnominal" id="percentnominal" class="form-control" value="{{ old('percentnominal') ? old('percentnominal') : '0' }}" onkeyup="countPayment(this.value)">
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Nominal :<sup class="text-danger">*</sup></label>
								<input type="text" name="nominal" id="nominal" class="form-control" placeholder="0" value="{{ old('nominal') }}" onkeyup="formatRupiah(this)">
							 </div>
						  </div>
						  <div class="col-md-4">
							<div class="form-group">
								<label>Proof of Payment :<sup class="text-danger">*</sup></label>
								<div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="file" name="file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							</div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Bank Destination :</label>
								<select name="bank_id" id="bank_id" class="select2">
								   <option value="22">-- None --</option>
								   @foreach($bank as $b)
									  <optgroup label="{{ $b->name }}">
										  @foreach($b->child() as $bc)
											<option value="{{ $bc->id }}" {{ $project->coa_id == $bc->id ? 'selected' : '' }}>{{ $bc->name }}</option>
										  @endforeach
									  </optgroup>
								   @endforeach
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Method (Transfer/Giro/Check/Cash) :<sup class="text-danger">*</sup></label>
								<select name="giro" id="giro" class="custom-select">
									<option value="0" {{ old('giro') == 0 ? 'selected' : '' }}>Transfer</option>
									<option value="1" {{ old('giro') == 1 ? 'selected' : '' }}>Giro</option>
									<option value="2" {{ old('giro') == 2 ? 'selected' : '' }}>Check</option>
									<option value="3" {{ old('giro') == 3 ? 'selected' : '' }}>Cash</option>
								</select>
							 </div>
						  </div>
						  <div class="col-md-4">
							 <div class="form-group">
								<label>Note :</label>
								<textarea name="note" id="note" class="form-control" value="{{ old('note') }}" rows="1">-</textarea>
							 </div>
							</div>
						  <div class="col-md-4 giro-class d-none">
							 <div class="form-group">
								<label>Giro Number :</label>
								<input type="text" name="giro_code" id="giro_code" class="form-control" value="-">
							 </div>
						  </div>
						  <div class="col-md-4 giro-class d-none">
							 <div class="form-group">
								<label>Giro Due Date :</label>
								<input type="date" name="giro_date" id="giro_date" class="form-control">
							 </div>
						  </div>
						</div>
						<div class="form-group"><hr></div>
						<div class="form-group">
						  <div class="text-right">
							<a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(8,{{ $project->id }});">Skip <i class="icon-forward2"></i></a>
								 &nbsp;
							<button type="submit" name="submit" value="step-8" class="btn bg-purple">Save & Next <i class="icon-square-right"></i></button>
						  </div>
						</div>
						<div class="form-group text-center">
							<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed4">
								See All Sales Down Payment
							</a>
						</div>
						
						<div class="form-group collapse" id="collapse-link-collapsed4">
							<h5 class="card-title"><b><span class="badge badge-danger">8.a</span> List of All Sales Payment</b></h5>
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
									   <th>Approved By</th>
									   <th>Proof</th>
									   <th>Edit</th>
									   <th>Delete</th>
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
										  <td>
											<a href="#step-8" onclick="editPayment({{ $pp->id }})" class="btn bg-success"><i class="icon-pencil5"></i></a>
										  </td>
										  <td>
											<a href="javascript:void(0);" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="deletePayment({{ $pp->id }})"><i class="icon-trash"></i></a>
										  </td>
										  <td class="align-middle">
											<a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_invoice/' . base64_encode($pp->id)) }}')" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
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
@php
	}else{
@endphp
			<div class="alert bg-danger text-white alert-styled-left alert-dismissible" style="font-size:25px;">
				<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
	<span class="font-weight-semibold">Warning!</span> This project has no Budgeting Project, please contact Ms. Ismi Suryaningtyas to make one, so you may continue to the next step. Or press this button <a href="javascript:void(0);" onclick="sendMessage(`{{ env('SALES_MANAGER_PHONE') }}`,`{{ $project->code }}`)" class="btn btn-success btn-icon rounded-pill"><i class="icon-phone2"></i></a>.
			</div>
@php
	}
@endphp

         </div>
		 
		<div class="sidebar-sticky order-1 order-md-2">
			<!-- Secondary sidebar -->
			<div class="sidebar sidebar-light sidebar-secondary sidebar-component sidebar-component-right sidebar-expand-md">

				<!-- Sidebar content -->
				<div class="sidebar-content" style="overflow-y: scroll;background-color:#457c80 !important;height:90vh;">

					<div class="card">
						 <div class="card-header bg-transparent header-elements-inline" style="background-color:#457c80 !important;color:white !important;">
							<span class="text-uppercase font-size-sm font-weight-semibold" style="font-size:14px;">Project No. {{ $project->code }}</span>
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

   <div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Daily Visit Form</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_data">
					<div class="alert alert-danger" id="validation_alert"
						style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
						<ul id="validation_content"></ul>
					</div>
					<div class="form-group">
						<input type="hidden" name="temp_id" id="temp_id">
						<div class="form-group">
							<label class="form-label">Date<span class="text-danger">*</span></label>
							<input type="date" name="date_trip" id="date_trip" class="form-control" required>
						</div>
						<div class="form-group">
							<label class="form-label">Proof</label>
							<input type="file" id="proof" name="proof" class="form-control h-auto"
								accept="image/x-png,image/jpg,image/jpeg">
						</div>
						<div class="form-group">
							<label>Note :<span class="text-danger">*</span></label>
							<textarea class="form-control h-auto" id="note_trip" name="note_trip" rows="3"></textarea>
						</div>
					</div>

				</form>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
					Close</button>
				<button type="button" class="btn bg-primary" id="btn_create" onclick="createProjectTrip()"><i
						class="icon-plus3"></i> Save</button>
			</div>
		</div>
	</div>
   </div>

   
	<!-- Info modal -->
	
	<div class="modal fade" id="modal_notes" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Add Sales Order Notes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
							<span class="font-weight-semibold">Information!</span> This note will appear to sales report.
						</div>
					</div>
					<div class="col-md-5">
					  <div class="form-group">
					    <input type="hidden" name="tempSales" id="tempSales">
						<input type="text" class="form-control" name="sales_note_kuy" id="sales_note_kuy" placeholder="Type note">
					  </div>
					</div>
					<div class="col-md-5">
					  <div class="form-group">
						 <div class="input-group">
						   <div class="custom-file">
							  <input type="file" id="sales_file_kuy" name="sales_file_kuy" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
						   </div>
						</div>
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
						 <button type="button" onclick="addSalesNote()" class="btn bg-success col-12" id="btnaddpurchasenote"><i class="icon-plus2"></i> Add</button>
					  </div>
					</div>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
					<h5><b>List of All Sales Notes</b></h5>
				</div>
				<div class="form-group"><hr></div>
				<div class="form-group">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead class="table-secondary">
								<tr class="text-center">
								   <th width="25%">Date</th>
								   <th>Project</th>
								   <th>Note</th>
								   <th>Proof</th>
								</tr>
							 </thead>
							 <tbody id="data_sales_note">
								<tr>
									<td colspan="4">
										<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes here.</div>
									</td>
								</tr>
							 </tbody>
						</table>
					</div>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
	
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

	<div class="modal fade" id="modal_recap" data-backdrop="static" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Form</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body content-calendar w-100">
						<div class="calendar-container">
						  <div class="calendar"> 
							<div class="year-header"> 
							  <span class="left-button fa fa-chevron-left" id="prev"> </span> 
							  <span class="year" id="label"></span> 
							  <span class="right-button fa fa-chevron-right" id="next"> </span>
							</div> 
							<table class="months-table w-100"> 
							  <tbody>
								<tr class="months-row">
								  <td class="month">Jan</td> 
								  <td class="month">Feb</td> 
								  <td class="month">Mar</td> 
								  <td class="month">Apr</td> 
								  <td class="month">May</td> 
								  <td class="month">Jun</td> 
								  <td class="month">Jul</td>
								  <td class="month">Aug</td> 
								  <td class="month">Sep</td> 
								  <td class="month">Oct</td>          
								  <td class="month">Nov</td>
								  <td class="month">Dec</td>
								</tr>
							  </tbody>
							</table> 
							
							<table class="days-table w-100"> 
							  <td class="day">Sun</td> 
							  <td class="day">Mon</td> 
							  <td class="day">Tue</td> 
							  <td class="day">Wed</td> 
							  <td class="day">Thu</td> 
							  <td class="day">Fri</td> 
							  <td class="day">Sat</td>
							</table> 
							<div class="frame"> 
							  <table class="dates-table w-100"> 
							  <tbody class="tbody">             
							  </tbody> 
							  </table>
							</div> 
							<!-- <button class="button" id="add-button">Add Event</button> -->
						  </div>
						</div>
						<div class="events-container">
						</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
						Close</button>
				</div>
			</div>
		</div>
	</div>
	
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
					<h5 class="card-title"><b>Detail Tracking Shipment</b></h5>
					<a href="" target="_blank" id="link-tracking-shipment" class="btn btn-success btn-sm">Send View <i class="icon-file-eye"></i></a>
					<div class="form-group"><hr></div>
					<div class="row">
					   <div class="col-md-10">
						  <div class="form-group">
							<input type="hidden" id="tempshipmentid">
							 <input type="text" class="form-control" name="tracking-shipment-note" id="tracking-shipment-note" placeholder="Type note">
						  </div>
					   </div>
					   <div class="col-md-2">
						  <div class="form-group">
							 <button type="button" onclick="addTrackingShipmentDetail(this)" class="btn bg-success col-12" id="btnaddtrackingshipment"><i class="icon-plus2"></i> Add</button>
						  </div>
					   </div>
					</div>
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
					<h5 class="card-title"><b>Detail Tracking Delivery</b></h5>
					<a href="" target="_blank" id="link-tracking-delivery" class="btn btn-success btn-sm">Send View <i class="icon-file-eye"></i></a>
					<div class="form-group"><hr></div>
					<div class="row">
					   <div class="col-md-5">
						  <div class="form-group">
							<input type="hidden" id="tempdeliveryid">
							 <input type="text" class="form-control" name="tracking-delivery-note" id="tracking-delivery-note" placeholder="Type note">
						  </div>
					   </div>
					   <div class="col-md-5">
						  <div class="form-group">
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="file" id="tracking-delivery-file" name="tracking-delivery-file" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg">
							   </div>
							</div>
						  </div>
					   </div>
					   <div class="col-md-2">
						  <div class="form-group">
							 <button type="button" data-id="" onclick="addTrackingDeliveryDetail(this)" class="btn bg-success col-12" id="btnaddtrackingdelivery"><i class="icon-plus2"></i> Add</button>
						  </div>
					   </div>
					</div>
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
	
	<div id="modal_sample" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content" style="max-width: 600px !important;max-height: 100% !important;">
				<div class="modal-header">
					<h5 class="modal-title">Sample No. <b id="modal_title_sample"></b></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="modal-body-sample">
					<div class="form-group"><hr></div>
					<h5 class="card-title">
						<b>Sample Proof</b> 
					</h5>
					<div class="row">
					   <div class="col-md-12">
						<form id="form_data_sample">
						  <div class="alert alert-danger" id="validation_alert_sample" style="display:none;">
							<ul id="validation_content_sample"></ul>
						  </div>
						  <div class="form-group">
							 <div class="input-group">
							   <div class="custom-file">
								  <input type="hidden" id="tempsample">
								  <input type="file" id="return_proof" name="return_proof" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
							   </div>
							</div>
						  </div>
						</form>
					   </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn bg-primary" onclick="addSampleProofGo()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /small modal -->
	
	<div id="modal_return_memo" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content" style="max-width: 600px !important;max-height: 100% !important;">
				<div class="modal-header">
					<h5 class="modal-title">Form Add Return Memo</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<form id="form_return_memo">
						<div class="form-group"><hr></div>
						<h5 class="card-title">Main Information</h5>
						<div class="alert alert-danger" id="validation_alert_memo" style="display:none;">
							<ul id="validation_content_memo"></ul>
						</div>
						<div class="row">
							<input type="hidden" name="temp_return_memo_id" id="temp_return_memo_id">
							<div class="col-md-12">
								 <div class="form-group">
									<label>Delivery Order :<sup class="text-danger">*</sup></label>
									<select name="delivery_id" id="delivery_id" class="select2" onchange="getDeliveryProduct(this);">
									   <option value="">-- Choose --</option>
									   @foreach($project->projectDelivery as $pd)
										  <option value="{{ $pd->id }}">{{ 'DO No. '.$pd->code.' - SO No. '.$pd->projectSale->code }}</option>
									   @endforeach
									</select>
								 </div>
							</div>
							<div class="col-md-6">
								 <div class="form-group">
									<label>Date :<sup class="text-danger">*</sup></label>
									<input type="date" name="date" id="date" class="form-control">
								 </div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								 <label>Proof :</label>
								 <div class="input-group">
								   <div class="custom-file">
									  <input type="file" id="return_memo_proof" name="return_memo_proof" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg,application/pdf">
								   </div>
								</div>
							  </div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Reason :<sup class="text-danger">*</sup></label>
									<textarea name="reason" id="reason" class="form-control" rows="2"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group"><hr></div>
						<h5 class="card-title">Product Information</h5>
						<div class="form-group"><hr></div>
						<div class="form-group">
						   <div class="table-responsive">
							  <table class="table table-bordered table-striped">
								 <thead class="table-secondary">
									<tr class="text-center">
									   <th>No</th>
									   <th>Product</th>
									   <th>Qty</th>
									   <th>Unit</th>
									   <th>Delete</th>
									</tr>
								 </thead>
								 <tbody id="data_sales_return">
									<td class="bg-warning" colspan="5" style="text-align:center;">Choose Delivery Order first to see All products that can be returned.</td>
								 </tbody>
							  </table>
						   </div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="button" class="btn bg-primary" onclick="saveProjectReturnMemo()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /small modal -->
<link href="{{ asset('template/back-office/calendarjs/css/style.css') }}" rel="stylesheet">
<script src="{{ asset('template/back-office/calendarjs/js/popper.js')}}"></script>
<script src="{{ asset('template/back-office/calendarjs/js/main.js')}}"></script>

<script>
   $(function() {
	   ckEditor('custom_tos_id');
	   ckEditor('custom_tos_en');
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
	   
	   $('#giro').on('change', function(){
		   if($(this).val() == '1'){
			   $('.giro-class').removeClass('d-none');
		   }else{
			   $('.giro-class').addClass('d-none');
		   }
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
	  
	  $('#data_sales_return').on('click', '#delete_return_memo', function() {
         $(this).closest('tr').remove();
      });



	$('#modal_recap').on('hidden.bs.modal', function () {
		getProjectTrip();
	})

	  loadDataTable();
	  getProjectTrip();
	  
	  
   });
	
	var tempSales = 0;

	function loadDataTable() {
      window.table = $('#datatable_serverside').DataTable({
		 stateSave: true,
         serverSide: true,
         deferRender: true,
         destroy: true,
         iDisplayInLength: 10,
         order: [[2, 'asc']],
         ajax: {
            url: '{{ url("admin/sales/project/datatable_project_trip") }}',
            type: 'GET',
            data: {
               project_id: {{ $project->id }},
            },
            beforeSend: function() {
               loadingOpen('#datatable_serverside');
            },
            complete: function() {
               loadingClose('#datatable_serverside');
            },
            error: function() {
               loadingClose('#datatable_serverside');
               swalInit.fire({
                  title: 'Server Error',
                  text: 'Please contact developer',
                  type: 'error'
               });
            }
         },
         columns: [
            { name: 'date', searchable: false, className: 'text-center align-middle' },
			{ name: 'proof', searchable: false, orderable: false, className: 'text-center align-middle' },
			{ name: 'note', orderable: false, className: 'text-center align-middle' },
            { name: 'action', searchable: false, orderable: false, className: 'text-center nowrap align-middle' }
         ],
      }); 
   }

   function reset() {
      $('#form_data').trigger('reset');
      $('#validation_alert').hide();
      $('#validation_content').html('');
   }

   function success() {
      reset();
      $('#modal_form').modal('hide');
      $('#datatable_serverside').DataTable().ajax.reload(null, false);
	}

   function createProjectTrip() {
	  var fd = new FormData($('#form_data')[0]);
	  var project_id = {{$project->id}}
	  fd.append('project_id', project_id);

      $.ajax({
         url: '{{ url("admin/sales/project/create_project_trip") }}',
         type: 'POST',
         dataType: 'JSON',
         data: fd,
		 contentType: false,
		 processData: false,
		 cache: true,
		 headers: {
		 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
         beforeSend: function() {
            $('#validation_alert').hide();
            $('#validation_content').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
               success();
			   $('#form_data')[0].reset();
			   notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            } else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
			$('#datatable_serverside').DataTable().ajax.reload(null, false);
            $('.modal-body').scrollTop(0);
            loadingClose('.modal-content');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }


   function showProjectTrip(id){
		$("#temp_id").val(id);
		$('#modal_form').modal('toggle');
		$.ajax({
			 url: '{{ url("admin/sales/project/show_project_trip") }}',
			 type: 'GET',
			 dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#modal_form');
			 },
			 success: function(response) {
				loadingClose('#modal_form');
				if(response){
					$('#date_trip').val(response.date);
					$('#note_trip').val(response.note);
				}
			 },
			 error: function() {
				loadingClose('#modal_form');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

	function getProjectTrip(){
		$.ajax({
			url: '{{ url("admin/sales/project/get_project_trip") }}',
			type: 'GET',
			dataType: 'JSON',
			beforeSend: function() {
			loadingOpen('#modal_form');
			},
			success: function(response) {
			loadingClose('#modal_form');
			if(response){
				if(response.data.length > 0){
					$.each(response.data, function (i, val) { 
						var date = new Date(val.date);
				
						var currentDate = new Date();

						// console.log(curr_date)
						new_event_json(val.name, 1, date, currentDate, val.day, val.note, val.proof, val.id);

						if(val.progress < 37 ){
							$("#totalDailyVisit").text(response.data.length);
							new_unfinished_event_json(val.name, date, val.day, val.note, val.proof, val.id);
						}
					});
				}
			}
			},
			error: function() {
			loadingClose('#modal_form');
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
   
	function addNotes(id){
		$('#tempSales').val(id);
		tempSales = id;
		
		$.ajax({
			 url: '{{ url("admin/sales/project/get_sales_note") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: {
				id: tempSales
			 },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				$('#data_sales_note').empty();
				if(response.length > 0){
					$.each(response, function(i, val) {
						var checkedbox = '';
						if(val.is_public == '1'){
							checkedbox = 'checked';
						}
						$('#data_sales_note').append(`
							<tr class="text-center">
								<td>` + val.created_at + `</td>
								<td>` + val.code + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
							</tr>
						`);
					});
				}else{
					$('#data_sales_note').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes data.</div>
							</td>
						</tr>
					`);
				}
				
				loadingClose('.modal-content');
			 },
			 error: function() {
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
		
		$('#modal_notes').modal('toggle');
	}
	
	function addSalesNote(){
		var id = $('#tempSales').val(), note = $('#sales_note_kuy').val();
		var fd = new FormData(), files = $('#sales_file_kuy')[0].files;
		fd.append('note',note);
		fd.append('mode','project_sales');
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/sales/project/add_sales_notes") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_sales_note');
			},
			success: function(response) {
				$('#data_sales_note').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						$('#data_sales_note').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString('en-GB') + `</td>
								<td>` + val.code + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
							</tr>
						`);
					});
				}else{
					$('#data_sales_note').append(`
						<tr>
							<td colspan="4">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no notes data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#sales_file_kuy').val('');
				$('#sales_note_kuy').val('');
				
				loadingClose('#data_sales_note');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
   
	function getShading(val){
		if(val !== ''){
			$.ajax({
				url: '{{ url("admin/sales/project/get_shading_product") }}',
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
				url: '{{ url("admin/sales/project/get_shading_product") }}',
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
			url: '{{ url("admin/sales/project/get_sales_info") }}',
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
			}else if(elemen == 'sopd_id'){
				loadingOpen('#step-8');
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
			}else if(elemen == 'sopd_id'){
				loadingClose('#step-8');
				
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
			}else if(elemen == 'sopd_id'){
				loadingClose('#step-8');
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
			url: '{{ url("admin/sales/project/get_supplier_currency") }}',
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
			url: '{{ url("admin/sales/project/get_sales_product") }}',
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
			url: '{{ url("admin/sales/project/get_purchase_product") }}',
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
			url: '{{ url("admin/sales/project/get_purchase_product") }}',
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
			url: '{{ url("admin/sales/project/get_shipment_info") }}',
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
			url: '{{ url("admin/sales/project/get_shipment_product") }}',
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
			url: '{{ url("admin/sales/project/get_purchase_info") }}',
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
            url: '{{ url("admin/sales/project/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: id.val()
            },
            beforeSend: function() {
               loadingOpen('#step-2');
            },
            success: function(response) {
				if(response.error == '500'){
					
					var msg = '';
					
					$.each(response.message, function(i, val) {
						msg = msg + val;
					});
					
					swalInit.fire('Error product!', msg, 'error');
					
				}else{
					var same = false;
					
					$('input[name^="product_id"]').each(function() {
						if($(this).val() == response.id && (response.sell_unit == '1' || response.sell_unit == '4')){
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
	
						var unit = '';
						
						if(response.sell_unit == '1' || response.sell_unit == '4'){
							unit = '3';
						}else{
							unit = response.sell_unit == '11' ? '4' : '1';
						}

					   $('#data_product').append(`
						  <tr class="text-center rowproduct">
							 <input type="hidden" name="product_id[]" value="` + response.id + `">
							 <input type="hidden" name="product_price[]" value="` + response.price + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + response.product + `</td>
							 <td class="align-middle">
								<textarea type="text" name="product_area[]" class="form-control" placeholder="Type area" required rows="5"></textarea>
							 </td>
							 <td class="align-middle">
								<textarea name="product_spec[]" class="form-control" placeholder="Type spec" required rows="5">` + response.surface + `</textarea>
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_qty[]" class="form-control" placeholder="0" required onkeyup="convertToBox(this,`+ response.sqm +`,`+ no +`)">
							 </td>  
							 <td class="align-middle">
								<select name="product_unit[]" class="custom-select" required>
								   <option value="1" ` + (unit == '1' ? "selected" : "") + `>Pcs</option>   
								   <option value="3" ` + (unit == '3' ? "selected" : "") + `>Meter</option>   
								   <option value="3" ` + (unit == '4' ? "selected" : "") + `>Meter(custom)</option>   
								</select>
							 </td>   
							 <td class="align-middle">
								` + response.carton_pcs + ` pcs / carton, ` + response.carton_sqm + `
								<br>Unit in Box <span class="badge badge-success" id="convertedBox` + no +`">0</span>
							 </td>  
							 <td class="align-middle">
								<button type="button" id="delete_data_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
					   `);
					}else{
						swalInit.fire('Ooppsss!', 'Product was already added.', 'info');
					}
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
         url: '{{ url("admin/sales/project/approval") }}',
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
         url: '{{ url("admin/sales/project/approval") }}',
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
         url: '{{ url("admin/sales/project/approval") }}',
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
         url: '{{ url("admin/sales/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"purchase" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-8');
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
         url: '{{ url("admin/sales/project/approval") }}',
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
         url: '{{ url("admin/sales/project/approval") }}',
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
         url: '{{ url("admin/sales/project/update_status_sample") }}',
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
         url: '{{ url("admin/sales/project/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { approvalKe:approvalKe,id:id,mode:"sales_invoice" },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('.order-2');
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
            url: '{{ url("admin/sales/project/get_product") }}',
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
				
				if(response.error == '500'){
					
					var msg = '';
					
					$.each(response.message, function(i, val) {
						msg = msg + val;
					});
					
					swalInit.fire('Error product!', msg, 'error');
					
				}else{
				
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
   
   function countBestPrice1(percent,id,price){
	   
		var result = parseFloat(price) - (parseFloat((parseFloat(price) * parseFloat(percent.value)) / 100));
	   
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
	   
		$('#discount-price' + id).val(rupiah);
		
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
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to skip?</h6><label>Skipped data can no longer be rolled back.</label>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-redo"></i>', 'btn bg-success ml-1', function() {
               $.ajax({
					url: '{{ url("admin/sales/project/skip_form") }}',
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
						}else if(response.status == '422'){
							notif('error', 'bg-danger', response.message);
							notyConfirm.close();
						}
						loadingClose('#step-' + step);
					},
					error: function() {
					   loadingClose('#step-' + step);
					   swalInit.fire('Server Error!', 'Please contact developer', 'error');
					}
				 });
            })
         ]
		}).show();
	}
	
	function addTrackingShipment(idshipment,codeshipment){
		$('#modal_title_tracking_shipment').html(codeshipment);
		$('#tempshipmentid').val(idshipment);
		
		$.ajax({
            url: '{{ url("admin/sales/project/get_tracking_shipment") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: $('#tempshipmentid').val()
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
			url: '{{ url("admin/sales/project/add_shipment_tracking") }}',
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
			url: '{{ url("admin/sales/project/delete_shipment_tracking") }}',
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
		$('#tempdeliveryid').val(iddelivery);
		
		$.ajax({
            url: '{{ url("admin/sales/project/get_tracking_delivery") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: $('#tempdeliveryid').val()
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
			url: '{{ url("admin/sales/project/add_delivery_tracking") }}',
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
			url: '{{ url("admin/sales/project/delete_delivery_tracking") }}',
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
			url: '{{ url("admin/sales/project/get_sales_info") }}',
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
					$('#ppn_cost').val(response.ppn_cost);
					$('#date_so').val(response.created_at);
					$('html, body').animate({
						scrollTop: $('#step-7').offset().top
					}, 'slow');
					$('#sales_id').empty();
					$('#sales_id').append(`
						<option value="` + response.sales_id + `">` + response.sales_name + `</option>
					`);
					$('#delivery_cost').val(response.delivery_cost);
					$('#cutting_cost').val(response.cutting_cost);
					$('#misc_cost').val(response.misc_cost);
					$('#mid_yes_no').val(response.mid_yes_no);
					$('#mid_type').val(response.mid_type);
					$('#mid_fee').val(response.mid_fee);
					$('#mid_note').val(response.mid_note);
					$('#currency_id').val(response.currency_id).trigger('change');
					$('#currency_rate').val(response.currency_rate);
					
					$('.edit-so').removeClass('d-none');
					
				}
			 },
			 error: function() {
				loadingClose('#step-7');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function editPayment(idpay){
		$('#temp_pay_id').val(idpay);
		$('#modepayment').html('Edit <i class="icon-loop3"></i>');
		$('#modepayment').removeClass('btn-info');
		$('#modepayment').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/sales/project/get_payment_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				idprojectpayment : idpay
			 },
			 beforeSend: function() {
				loadingOpen('#step-8');
			 },
			 success: function(response) {
				loadingClose('#step-8');
				if(response){
					
					$('#sopd_id').empty();
					$('#sopd_id').append(`
						<option value="` + response.sale_id + `">` + response.sale_code + `</option>
					`);
					$('#date_create').val(response.date);
					$('#payment_method').val(response.payment_method);
					$('#nominal').val(response.nominal);
					$('#bank_id').val(response.coa_id).trigger('change');
					$('#note').val(response.note);
					$('#giro').val(response.giro).trigger('change');
					$('#giro_code').val(response.giro_code);
					$('#giro_date').val(response.giro_date);
					
					$('.edit-sp').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-8');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}
	
	function resetPayment(){
		location.reload();
		return false;
	}
	
	function countPayment(percent){
		var nominal = $('#payment_left').text();
		if(nominal !== '0'){
			$('#nominal').val( ((parseFloat(percent) / 100 * parseFloat(nominal.replace(/\./g,'').replace(',','.')))).toString().replace('.',',') );
			$('#nominal').trigger('keyup');
		}else{
			swalInit.fire('Error!', 'Please choose sales order first / your SO is already paid.', 'error');
		}
	}
	
	function resetSales(){
		$('#temp_so_id').val('');
		$('#modesales').html('Add <i class="icon-loop3"></i>');
		$('#modesales').removeClass('btn-warning');
		$('#modesales').addClass('btn-info');
		$('#sales_address').val('');
		$('#sales_note').val('');
		$('#sales_id').empty();
		$('.edit-so').addClass('d-none');
		return false;
	}
	
	function deleteSales(idso){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason" class="form-control" placeholder="Enter why this sales order should be deleted?"></div></div>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-trash"></i>', 'btn bg-success ml-1', function() {
				if($('#delete_reason').val() !== ''){
					$.ajax({
						url: '{{ url("admin/sales/project/delete_sales") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							idso : idso, reason : $('#delete_reason').val()
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-7');
						 },
						 success: function(response) {
							loadingClose('#step-7');
							if(response.status == 200) {
								notif('success', 'bg-success', response.message);
								notyConfirm.close();
								location.reload();
							} else if(response.status == 400) {
								notif('error', 'bg-danger', response.message);
								notyConfirm.close();
							} else {
								swalInit.fire({
								   title: 'Server Error',
								   text: 'Please contact developer',
								   type: 'error'
								});
							}
						 },
						 error: function() {
							loadingClose('#datatable_serverside');
							swalInit.fire({
							   title: 'You do not have permission to delete this project sale!',
							   text: 'Please contact sales manager to ask delete this project sale.',
							   type: 'error'
							});
						 }
					});
				}else{
					notif('error', 'bg-danger', 'Reason cannot empty.');
				}
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function resetQuotation(id){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to reset?</h6><label>Reset data can no longer be recovered.</label>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-loop3"></i>', 'btn bg-success ml-1', function() {
				$.ajax({
					url: '{{ url("admin/sales/project/reset_quotation") }}',
					type: 'POST',
					dataType: 'JSON',
					 data: {
						id : id
					 },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('#step-4');
					 },
					 success: function(response) {
						loadingClose('#step-4');
						if(response.status == 200) {
							notif('success', 'bg-success', response.message);
							notyConfirm.close();
							location.reload();
						} else if(response.status == 400) {
							notif('error', 'bg-danger', response.message);
							notyConfirm.close();
						} else {
							swalInit.fire({
							   title: 'Server Error',
							   text: 'Please contact developer',
							   type: 'error'
							});
						}
					 },
					 error: function() {
						loadingClose('#step-4');
						swalInit.fire({
						   title: 'You do not have permission to delete this project sale!',
						   text: 'Please contact sales manager to ask delete this project sale.',
						   type: 'error'
						});
					 }
				});
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function deletePayment(idpay){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason" class="form-control" placeholder="Enter why this sales payment should be deleted?"></div></div>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-trash"></i>', 'btn bg-success ml-1', function() {
				if($('#delete_reason').val() !== ''){
					$.ajax({
						url: '{{ url("admin/sales/project/delete_payment") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							idpay : idpay, reason : $('#delete_reason').val()
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-7');
						 },
						 success: function(response) {
							loadingClose('#step-7');
							if(response.status == 200) {
								notif('success', 'bg-success', response.message);
								notyConfirm.close();
								location.reload();
							} else if(response.status == 400) {
								notif('error', 'bg-danger', response.message);
								notyConfirm.close();
							} else {
								swalInit.fire({
								   title: 'Server Error',
								   text: 'Please contact developer',
								   type: 'error'
								});
							}
						 },
						 error: function() {
							swalInit.fire({
							   title: 'You do not have permission to delete this project sale!',
							   text: 'Please contact sales manager to ask delete this project sale.',
							   type: 'error'
							});
						 }
					});
				}else{
					notif('error', 'bg-danger', 'Reason cannot empty.');
				}
            })
         ]
      }).show();
	  
	  return false;
	}
	
	function addProjectNote(){
		var id = {{ $project->id }}, note = $('#project-note').val();
		var fd = new FormData(), files = $('#project-file')[0].files;
		fd.append('note',note);
		fd.append('mode','projects');
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/sales/project/add_project_note") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				 loadingOpen('#data_project_note');
			},
			success: function(response) {
				$('#data_project_note').empty();
				if(response.length > 0) {
					$.each(response, function(i, val) {
						var date = new Date(val.created_at);
						
						$('#data_project_note').append(`
							<tr class="text-center">
								<td>` + date.toLocaleString('en-GB') + `</td>
								<td>` + val.note + `</td>
								<td>` + val.image + `</td>
							</tr>
						`);
					});
					
				}else{
					$('#data_project_note').append(`
						<tr>
							<td colspan="3">
								<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no tracking data.</div>
							</td>
						</tr>
					`);
				}
				
				$('#purchase-file').val('');
				$('#purchase-note').val('');
				
				loadingClose('#data_project_note');
			},
			error: function() {
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
	}
	
	function addSampleProof(idSample,codeSample){
		$('#modal_title_sample').html(codeSample);
		$('#tempsample').val(idSample);
		$('#modal_sample').modal('toggle');
	}
	
	function addProjectReturnMemo(){
		$('#modal_return_memo').modal('toggle');
	}
	
	function addSampleProofGo(){
		var formdata = new FormData($('#form_data_sample')[0]);
		formdata.append('tempsample',$('#tempsample').val());
		
		$.ajax({
			 url: '{{ url("admin/sales/project/add_sample_proof") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: formdata,
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_sample').hide();
				$('#validation_content_sample').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   notif('success', 'bg-success', response.message);
				   location.reload();
				} else if(response.status == 422) {
				   $('#validation_alert_sample').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_sample').append(`
							<li>` + val + `</li>
						 `);
					  });
				   });
				} else {
				   notif('error', 'bg-danger', response.message);
				}
			 },
			 error: function() {
				$('.modal-body').scrollTop(0);
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
	}
	
	function convertToBox(element,sqm,id){
		var result = parseFloat(element.value) / parseFloat(sqm);
		
		var cek = parseInt(result.toString().split('.')[1],10);
		
		if(cek > 0){
			$('#convertedBox' + id).removeClass('badge-success');
			$('#convertedBox' + id).addClass('badge-danger');
		}else{
			$('#convertedBox' + id).removeClass('badge-danger');
			$('#convertedBox' + id).addClass('badge-success');
		}
		
		$('#convertedBox' + id).html(result.toFixed(2));
	}
	
	function getDeliveryProduct(element){
		if(element.value !== ''){
			$.ajax({
				url: '{{ url("admin/sales/project/get_delivery_product") }}',
				type: 'GET',
				dataType: 'JSON',
				data: {
				   id: element.value
				},
				beforeSend: function() {
				   loadingOpen('#form_return_memo');
				},
				success: function(response) {
					if(response.length > 0) {
						$('#data_sales_return').empty();
						$.each(response, function(i, val) {
							$('#data_sales_return').append(`
								<tr class="text-center">
									<td>` + (i + 1) + `</td>
									<td><input type="hidden" name="delivery_product_id[]" value="` + val.product_id + `">` + val.product_name + `</td>
									<td><input type="number" class="form-control" name="delivery_product_qty[]" value="` + val.qty + `"></td>
									<td><input type="hidden" name="delivery_product_unit[]" value="` + val.unitraw + `">` + val.unit + `</td>
									<td><button type="button" id="delete_return_memo" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
								</tr>
							`);
						});
						
					}
					
					loadingClose('#form_return_memo');
				},
				error: function() {
				   loadingClose('#form_return_memo');
				   swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		}
	}

	function editReturnMemo(id){
		$('#temp_return_memo_id').val(id);
		$.ajax({
			url: '{{ url("admin/sales/project/get_return_memo") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id_return_memo : id
			 },
			 beforeSend: function() {
				loadingOpen('#modal_return_memo');
			 },
			 success: function(response) {
				loadingClose('#modal_return_memo');
				if(response){
					$('#modal_return_memo').modal('toggle');
					$('#delivery_id').val(response.delivery_id).trigger('change');
					$('#date').val(response.date);
					$('#reason').val(response.reason);
				}
			 },
			 error: function() {
				loadingClose('#modal_return_memo');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

	function destroyReturnMemo(id) {
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-trash"></i>', 'btn bg-success ml-1', function() {
				$.ajax({
				 url: '{{ url("admin/sales/project/delete_picture") }}',
				 type: 'POST',
				 dataType: 'JSON',
				 data: { id : id },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				 },
				 beforeSend: function() {
					loadingOpen('.modal-content');
				 },
				 success: function(response) {
					loadingClose('.modal-content');
					if(response.status == 200) {
						notif('success', 'bg-success', response.message);
						notyConfirm.close();
					} else {
						notif('error', 'bg-danger', response.message);
					}
				 },
				 error: function() {
					loadingClose('.modal-content');
					swalInit.fire({
					   title: 'You do not have permission to delete this project!',
					   text: 'Please contact sales manager to ask delete this project.',
					   type: 'error'
					});
				 }
				});
			})
         ]
		}).show();
	}

	
	function saveProjectReturnMemo(){
		$.ajax({
			 url: '{{ url("admin/sales/project/add_return_memo") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: new FormData($('#form_return_memo')[0]),
			 contentType: false,
			 processData: false,
			 cache: true,
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				$('#validation_alert_memo').hide();
				$('#validation_content_memo').html('');
				loadingOpen('.modal-content');
			 },
			 success: function(response) {
				loadingClose('.modal-content');
				if(response.status == 200) {
				   $('#form_return_memo')[0].reset();
				   location.reload();
				} else if(response.status == 422) {
				   $('#validation_alert_memo').show();
				   $('.modal-body').scrollTop(0);
				   notif('warning', 'bg-warning', 'Validation');
				   
				   $.each(response.error, function(i, val) {
					  $.each(val, function(i, val) {
						 $('#validation_content_memo').append(`
							<li>` + val + `</li>
						 `);
					  });
				   });
				} else {
				   notif('error', 'bg-danger', response.message);
				}
			 },
			 error: function() {
				$('.modal-body').scrollTop(0);
				loadingClose('.modal-content');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
	}
	
	function sendMessage(nomor,code){
		var whatsapptemplate = 'https://wa.me/' + nomor + '?text=' + encodeURIComponent('Halo bu Ismi. Mohon dibuatkan budgeting project untuk project nomor ' + code + '. \nTerima Kasih.');
		
		window.open(whatsapptemplate, '_blank').focus();
	}


	function deleteSample(id){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason" class="form-control" placeholder="Enter why this sales order should be deleted?"></div></div>',
         timeout: false,
         modal: true,
         layout: 'center',
         closeWith: 'button',
         type: 'confirm',
         buttons: [
            Noty.button('<i class="icon-cross3"></i>', 'btn bg-danger', function() {
               notyConfirm.close();
            }),
            Noty.button('<i class="icon-trash"></i>', 'btn bg-success ml-1', function() {
				if($('#delete_reason').val() !== ''){
					$.ajax({
						url: '{{ url("admin/sales/project/delete_sample") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							id : id, reason : $('#delete_reason').val()
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-5');
						 },
						 success: function(response) {
							loadingClose('#step-5');
							if(response.status == 200) {
								notif('success', 'bg-success', response.message);
								notyConfirm.close();
								location.reload();
							}else {
								swalInit.fire({
								   title: 'Server Error',
								   text: 'Please contact developer',
								   type: 'error'
								});
							}
						 },
						 error: function() {
							loadingClose('#datatable_serverside');
							swalInit.fire({
							   title: 'You do not have permission to delete this project sale!',
							   text: 'Please contact sales manager to ask delete this project sale.',
							   type: 'error'
							});
						 }
					});
				}else{
					notif('error', 'bg-danger', 'Reason cannot empty.');
				}
            })
         ]
      }).show();
	  
	  return false;
	}
</script>

