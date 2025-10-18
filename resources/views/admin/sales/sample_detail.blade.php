<style>
    html {
        scroll-behavior: smooth;
        scroll-padding-top: 75px;
    }

    .sidebar-sticky {
        z-index: 900;
    }

    .sidebar-light .nav-sidebar>.nav-item>.nav-link.active {
        background-color: #07a9e7;
    }

    .sidebar-content::-webkit-scrollbar {
        width: 0px;
        height: 8px;
        background-color: white;
    }

    .sidebar-light .nav-sidebar .nav-link {
        color: white !important;
    }

    .sidebar-light .nav-sidebar .nav-link:hover {
        background-color: #457c80 !important;
        transform: scale(1.10);
    }

    .icon-check.text-success {
        color: #92ff01 !important;
    }
</style>
<div class="content-wrapper">
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Sample: <b><i></i></b></span>
                </h4>
            </div>
            <div class="header-elements">
                <div class="d-flex justify-content-center">
                    <a href="{{ url('admin/sales/sample') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
                        <b><i class="icon-arrow-left7"></i></b> Back To All
                    </a>
                </div>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Data</a>
                    <a href="{{ url('admin/sales/sample') }}" class="breadcrumb-item">Sample</a>
                    <span class="breadcrumb-item active">Detail</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="d-block d-flex align-items-start flex-column flex-md-row">
            <div class="order-2 order-md-1 w-100" id ="main-content">
                <div class="card" id="step-1">
                    <form action="" method="POST" id="form_data">
                        @csrf
                        <div class="card-body">
                            <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger"></span> Form
                                    Sample {{$customer_sample->customer->name}}</b></h3>
                            <div class="form-group">
                                <hr>
                            </div>

                            <div class="alert alert-danger" id="validation_alert-1"
                            style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                                <ul id="validation_content-1"></ul>
                            </div>
                            @if(session('success'))
                            <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                            @endif<div class="form-group">
                            </div>
                            <h5><b><span class="badge badge-danger"></span> Detail Product Sample</b></h5>
                            <div class="row">
                                <input type="hidden" name="temp_sample_id" id="temp_sample_id">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sent Date :<sup class="text-danger">*</sup></label>
                                        <input type="date" name="sample_sent_date" id="sample_sent_date"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Return Date :<sup class="text-danger">*</sup></label>
                                        <input type="date" name="sample_return_date" id="sample_return_date"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sales :<sup class="text-danger">*</sup></label>
                                        <select name="sales_so" id="sales_so"></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Note :</label>
                                        <textarea name="sample_note" id="sample_note" class="form-control"
                                            placeholder="Enter note" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
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
                                        <input type="number" name="sample_qty" id="sample_qty" class="form-control"
                                            placeholder="0">
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
                                        <button type="button" onclick="addSample()" class="btn bg-success col-3"><i
                                                class="icon-plus2"></i> Add</button>
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
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="button" name="submit" onclick="edit({{$customer_sample->id}})"
                                        class="btn bg-purple">Save
                                        <i class="icon-square-right"></i></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group text-center">
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed2">
                                    See All Sample-to-give Details
                                </a>
                            </div>
                            <div class="form-group collapse" id="collapse-link-collapsed2">
                                <h5><b><span class="badge badge-danger"></span> Sample-to-give Details</b></h5>
                                <div class="alert alert-info alert-styled-left alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                                    <span class="font-weight-semibold">Attention!</span> Document approval must be
                                    at
                                    least 1 for each document.<br>Status sample sent can be changed by change status
                                    option for each sample.
                                </div>
                                <div class="table-responsive" id="table_data_sample">
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
                                                <th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sample as $s)
                                            <tr class="text-center">
                                                <td class="align-middle">{{ $s->code }}</td>
                                                <td class="align-middle">{{ date('d M Y',strtotime($s->sent_date)).'
                                                    to
                                                    '.date('d M Y',strtotime($s->return_date)) }}</td>
                                                <td class="align-middle">{{ $s->note }}</td>
                                                <td class="align-middle">
                                                    @if (isset($s->approved_1->name))
                                                    {{$s->approved_1->name}}
                                                    @else
                                                    <button type="button" class="btn btn-primary btn-icon"
                                                        onclick="approveSample(1,{{$s->id}})"><i
                                                            class="icon-checkmark2"></i></button>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    @if (isset($s->approved_2->name))
                                                    {{$s->approved_2->name}}
                                                    @else
                                                    <button type="button" class="btn btn-primary btn-icon"
                                                        onclick="approveSample(2,{{$s->id}})"><i
                                                            class="icon-checkmark2"></i></button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select class="custom-select status-sample"
                                                        onchange="updateStatusSample(this.value,{{ $s->id }});">
                                                        <option value="1" {{ $s->status == '1' ? 'selected' : ''
                                                            }}>No Need Return</option>
                                                        <option value="2" {{ $s->status == '2' ? 'selected' : ''
                                                            }}>Need to Return</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/sample/'. base64_encode($s->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-info"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td>
                                                <td>
                                                    @if($s->return_proof)
                                                    <a onclick="openLink('{{ $s->attachment() }}')"
                                                        href="javascript:void(0);" class="btn bg-info"><i
                                                            class="icon-file-pdf"></i></a>
                                                    @else
                                                    <a class="btn bg-info" href="javascript:void(0);"
                                                        onclick="addSampleProof({{ $s->id }},'{{ $s->code}} ')"><i
                                                            class="icon-file-check2"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a onclick="showEditSample('{{ $s->id }}')" class="btn bg-warning"><i class="icon-pencil5"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title"><b><span class="badge badge-danger"></span> Sample Retur Memos</b>
                            </h5>
                            <div class="row justify-content-center">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="button" onclick="addSampleReturnMemo()"
                                            class="btn bg-success col-12" id="btnaddprojectnote"><i
                                                class="icon-plus2"></i> Add</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <h5><b>List of All Sample Retur Memos</b>
                                </h5>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
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
                                            </tr>
                                        </thead>
                                        <tbody id="data_project_return">
                                            @if(count($customer_sample->sampleReturnMemo) > 0)
                                            @foreach($customer_sample->sampleReturnMemo as $key => $prm)
                                            <tr>
                                                <td class="text-center">{{ date('d M Y',strtotime($prm->date)) }}</td>
                                                <td class="text-center">{{ $prm->sampleDelivery->sample->code }}
                                                </td>
                                                <td class="text-center">{{ $prm->sampleDelivery->code }}</td>
                                                <td class="text-center">{{ $prm->reason }}</td>
                                                <td class="text-center"><a href="{{ $prm->attachment() }}"
                                                        class="btn bg-info" target="_blank"><i
                                                            class="icon-search4"></i></a></td>
                                                <td class="text-center">
                                                    @foreach($prm->sampleReturnMemoDetail as $row)
                                                    {!! $row->product->name().' <b>'.$row->qty.' '.$row->unit().'</b>'
                                                    !!},<br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/return_memo/' . base64_encode($prm->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-info"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="7">
                                                    <div class="alert alert-info alert-styled-left alert-dismissible">
                                                        <button type="button" class="close"
                                                            data-dismiss="alert"><span>×</span></button><span
                                                            class="font-weight-semibold">Empty!</span> There is no
                                                        return memo here.
                                                    </div>
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
                @if (in_array(1, session('bo_role')) || in_array(5, session('bo_role')) || in_array(9,
                session('bo_role')))
                @if ($customer_sample->progress >= 20)
                @if (isset($customer_sample->sample->sortByDesc('created_at')->first()->approved_1->name) || isset($customer_sample->sample->sortByDesc('created_at')->first()->approved_2->name))
                <div class="card" id="step-2">
                    <div class="card-body">
                        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">2</span> PO
                                Supplier</b> <a href="javascript:void(0);" class="btn btn-info float-right"
                                id="modepurchase" onclick="resetPurchase()">Add <i class="icon-loop3"></i></a></h3>
                        <div class="form-group">
                            <hr>
                        </div>
                        <h5 class="card-title"><b><span class="badge badge-danger">2.a</span> New PO</b></h5>
                        <div class="form-group">
                            <hr>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-new-po">
                                <form action="" method="POST" id="form_purchase">
                                    @csrf
                                    @if(isset($_GET['step-2']))
                                    <div class="alert alert-danger" id="validation_alert-2"
                                    style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                                        <ul id="validation_content-2"></ul>
                                    </div>
                                    @if(session('success'))
                                    <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    @endif
                                    <div class="row d-none edit-po">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Reason :<sup class="text-danger">*</sup></label>
                                                <textarea type="text" name="edit_reason_po" id="edit_reason_po"
                                                    class="form-control"
                                                    placeholder="Please describe why you edit this Purchase Order."
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Mode :<sup class="text-danger">*</sup></label>
                                                <select name="is_wip" id="is_wip" class="custom-select">
                                                    <option value="0">NORMAL PO</option>
                                                    <option value="1">WIP PO</option>
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-8">
                                            <div class="alert alert-info alert-styled-left alert-dismissible">
                                                <button type="button" class="close"
                                                    data-dismiss="alert"><span>×</span></button>
                                                <span class="font-weight-semibold">Important Info!</span> Choose
                                                <b>Mode</b> to WIP PO, if you want to create this PO as WIP Project
                                                Purchase.</b>.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Purchase Date :<sup class="text-danger">*</sup></label>
                                                <input type="date" name="purchase_date" id="purchase_date"
                                                    class="form-control" value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Sample Order :<sup class="text-danger">*</sup></label>
                                                <input type="hidden" id="temp_po_id" name="temp_po_id" value="">
                                                <select name="sample_id" id="sample_id" class="select2"
                                                    onchange="getSampleProduct(this,this.value);getSalesInfo(this,this.value);">
                                                    <option value="">-- Choose --</option>
                                                    @foreach($customer_sample->sample as $s)
                                                    <option value="{{ $s->id }}">{{ $s->code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label>PPN :<sup class="text-danger">*</sup></label>
                                                <select name="ppn" id="ppn" class="custom-select">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Customer :<sup class="text-danger">*</sup></label>
                                                <select name="customer_id" id="customer_id"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Sales :<sup class="text-danger">*</sup></label>
                                                <select name="sales_po" id="sales_po"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Purchase Note :</label>
                                                <textarea name="sales_note" id="sales_note" class="form-control"
                                                    placeholder="Enter note here" rows="1"
                                                    value="{{ old('sales_note') }}"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                    <h5 class="card-title"><b><span class="badge badge-danger">2.b</span> Order
                                            To</b></h5>
                                    <div class="form-group">
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Supplier :<sup class="text-danger">*</sup></label>
                                                <select name="supplier_id" id="supplier_id"
                                                    onchange="getSupplierCurrency(this.value)"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Production Lead Time :<sup class="text-danger">*</sup></label>
                                                <input type="text" name="production_lead_time" id="production_lead_time"
                                                    class="form-control" placeholder="Enter here"
                                                    value="{{ old('production_lead_time') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Estimated Delivery Date :<sup class="text-danger">*</sup></label>
                                                <input type="date" name="est_delivery_date" id="est_delivery_date"
                                                    class="form-control" value="{{ old('est_delivery_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Estimated Arrival Date :<sup class="text-danger">*</sup></label>
                                                <input type="date" name="est_arrival_date" id="est_arrival_date"
                                                    class="form-control" value="{{ old('est_arrival_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Factory Name :<sup class="text-danger">*</sup></label>
                                                <input type="text" name="factory_name" id="factory_name"
                                                    class="form-control" placeholder="Enter here"
                                                    value="{{ old('factory_name') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title"><b><span class="badge badge-danger">2.c</span> Receiver
                                            Information</b></h5>
                                    <div class="form-group">
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Delivered To :<sup class="text-danger">*</sup></label>
                                                <input type="text" name="on_behalf" id="on_behalf" class="form-control"
                                                    placeholder="Enter here" value="{{ old('on_behalf') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Delivery Address :<sup class="text-danger">*</sup></label>
                                                <textarea type="text" name="delivery_address" id="delivery_address"
                                                    class="form-control" placeholder="Enter address here" rows="1"
                                                    value="{{ old('delivery_address') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Load Capacity :<sup class="text-danger">*</sup></label>
                                                <select name="courier_method" id="courier_method" class="custom-select">
                                                    <option value="FCL">FCL</option>
                                                    <option value="LCL">LCL</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Country :<sup class="text-danger">*</sup></label>
                                                <select name="country_id" id="country_id"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>City :<sup class="text-danger">*</sup></label>
                                                <select name="city_id" id="city_id"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>PIC Name :<sup class="text-danger">*</sup></label>
                                                <input type="text" name="pic_name" id="pic_name" class="form-control"
                                                    placeholder="Enter here" value="{{ old('pic_name') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>PIC Number :<sup class="text-danger">*</sup></label>
                                                <input type="text" name="pic_number" id="pic_number"
                                                    class="form-control" placeholder="Enter here"
                                                    value="{{ old('pic_number') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title"><b><span class="badge badge-danger">2.d</span> Payment
                                            Information</b></h5>
                                    <div class="form-group">
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Payment Method :<sup class="text-danger">*</sup></label>
                                                <input type="text" name="payment_method" id="payment_method"
                                                    class="form-control" placeholder="Ex: 0 for 0 days, 7 for 7 days"
                                                    value="{{ old('payment_method') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Payment Due Date :<sup class="text-danger">*</sup></label>
                                                <input class="form-control" type="date" name="payment_due_date"
                                                    id="payment_due_date" value="{{ old('payment_due_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Price :<sup class="text-danger">*</sup></label>
                                                <select name="price" id="price" class="form-control">
                                                    <option value="1">FOB</option>
                                                    <option value="2">EXW</option>
                                                    <option value="3">Franco</option>
                                                    <option value="4">CIF</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Currency :<sup class="text-danger">*</sup></label>
                                                <select name="currency" id="currency" class="custom-select">
                                                    <option value="" disabled selected>Select supplier first...
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Currency Rate :<sup class="text-danger">*</sup> (Leave '1'
                                                    For IDR)</label>
                                                <input class="form-control" type="text" name="currency_rate"
                                                    id="currency_rate" value="1" onkeyup="formatRupiah(this)">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Brand on box :</label>
                                                <input type="text" name="brand" id="brand" class="form-control"
                                                    placeholder="Enter here" value="{{ old('brand') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>SNI No. :</label>
                                                <input type="text" name="sni" id="sni" class="form-control"
                                                    placeholder="Enter here" value="{{ old('sni') }}">
                                            </div>
                                        </div>

                                    </div>
                                    <h5 class="card-title"><b><span class="badge badge-danger">2.e</span> Pick-up
                                            Memo Item To Supplier</b></h5>
                                    <div class="form-group">
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Need Pick Up Memo ?<sup class="text-danger">*</sup></label>
                                                <select name="has_memo_item" id="has_memo_item" class="custom-select">
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 memo-item-class d-none">
                                            <div class="form-group">
                                                <label>Address Pick-up Memo Item :</label>
                                                <textarea name="memo_address_item" id="memo_address_item"
                                                    class="form-control"
                                                    placeholder="Enter pick-up memo item address here" rows="1"
                                                    value="{{ old('memo_address_item') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 memo-item-class d-none">
                                            <div class="form-group">
                                                <label>Pick-up Contact Person :</label>
                                                <textarea name="memo_up" id="memo_up" class="form-control"
                                                    placeholder="Enter pick-up memo contact person here" rows="1"
                                                    value="{{ old('memo_up') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title"><b><span class="badge badge-danger">2.f</span> Detail
                                            Products</b></h5>
                                    <div class="form-group">
                                        <hr>
                                        <div class="alert alert-info alert-styled-left alert-dismissible">
                                            <button type="button" class="close"
                                                data-dismiss="alert"><span>×</span></button>
                                            <span class="font-weight-semibold">Information!</span> The product
                                            purchase's price must be included with PPN if this PO has PPN.</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" width="100%">
                                                <thead class="table-secondary">
                                                    <tr class="text-center">
                                                        <th width="10%">Product</th>
                                                        <th width="10%">Qty Need</th>
                                                        <th width="10%">Qty Left</th>
                                                        <th width="10%">Qty Order</th>
                                                        <th width="15%">Price @ M<sup>2</sup></th>
                                                        <th width="15%">Total</th>
                                                        <th width="20%">Shade</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data_purchase">

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="5" class="text-right">Grandtotal</th>
                                                        <th class="text-right" id="totalpo">0</th>
                                                        <th colspan="2"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <hr>
                                    </div>
                                    <div class="form-group">
                                        <div class="text-right">
                                            <button type="button" class="btn bg-purple"
                                                onclick="createPOSupplier({{$customer_sample->id}})">Save & Next <i
                                                    class="icon-square-right"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <hr>
                                    </div>
                                    <div class="form-group text-center">
                                        <a class="btn btn-primary" data-toggle="collapse"
                                            href="#collapse-link-collapsed">
                                            See All Purchase Order
                                        </a>
                                    </div>
                                    <div class="form-group collapse" id="collapse-link-collapsed">
                                        <h5><b><span class="badge badge-danger">2.h</span> List of All Purchase
                                                Order</b></h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="table-secondary">
                                                    <tr class="text-center">
                                                        <th>PO No.</th>
                                                        <th>SO No.</th>
                                                        <th>Supplier</th>
                                                        <th>Checked</th>
                                                        <th>Approved</th>
                                                        <th>SO Product</th>
                                                        {{-- <th>SO Service</th> --}}
                                                        <th>View PO</th>
                                                        <th>Pick-up Memo</th>
                                                        <th>Retur</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($customer_sample->samplePurchase as $sp)
                                                    <tr class="text-center">
                                                        <td class="align-middle">
                                                            <a class="btn btn-primary" data-toggle="collapse"
                                                                href="#collapse-link-collapsed{{ $sp->id }}">
                                                                {{ $sp->code }}
                                                            </a>
                                                        </td>
                                                        <td class="align-middle">{{ $sp->sample->code }}</td>
                                                        <td class="align-middle">{{ $sp->supplier->name }}</td>
                                                        <td class="align-middle">
                                                            @php
                                                            if(isset($sp->checked->name)){
                                                            echo $sp->checked->name;
                                                            }else{
                                                            echo '<button type="button" class="btn btn-primary btn-icon"
                                                                onclick="approvePurchase(1,'.$sp->id.')"><i
                                                                    class="icon-checkmark2"></i></button>';
                                                            }
                                                            @endphp
                                                        </td>
                                                        <td class="align-middle">
                                                            @php
                                                            if(isset($sp->approved->name)){
                                                            echo $sp->approved->name;
                                                            }else{
                                                            echo '<button type="button" class="btn btn-primary btn-icon"
                                                                onclick="approvePurchase(2,'.$sp->id.')"><i
                                                                    class="icon-checkmark2"></i></button>';
                                                            }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            <a onclick="openLink('{{ url('admin/sales/sample/print/sample/'. base64_encode($sp->sample->id)) }}')"
                                                                href="javascript:void(0);" class="btn bg-info"><i
                                                                    class="icon-file-pdf"></i></a>
                                                        </td>
                                                        {{-- <td>
                                                            <a onclick="openLink('{{ url('admin/purchase_order/project/print/sales_cost/'. base64_encode($sp->id)) }}')"
                                                                href="javascript:void(0);" class="btn bg-success"><i
                                                                    class="icon-file-pdf"></i></a>
                                                        </td> --}}
                                                        <td>
                                                            <a onclick="openLink('{{ url('admin/sales/sample/print/purchase_order/' . base64_encode($sp->id)) }}')"
                                                                href="javascript:void(0);" class="btn bg-info"><i
                                                                    class="icon-file-pdf"></i></a>
                                                        </td>
                                                        <td>
                                                            @if($sp->has_memo_item == '1')
                                                            <a onclick="openLink('{{ url('admin/sales/sample/print/pick_up_memo/' . base64_encode($sp->id)) }}')"
                                                                href="javascript:void(0);" class="btn bg-primary"><i
                                                                    class="icon-file-pdf"></i></a>
                                                            @else
                                                            <span class="badge badge-danger">Empty</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"
                                                                onclick="returPurchase({{ $sp->id }},'{{ $sp->code }}')"
                                                                class="btn bg-danger"><i class="icon-esc"></i></a>
                                                        </td>
                                                        {{-- <td>
                                                            <a href="javascript:void(0);"
                                                                onclick="addTaxDocument({{ $sp->id }})"
                                                                class="btn bg-primary btn-sm">
                                                                <i class="icon-file-spreadsheet"></i>
                                                                <span class="badge badge-warning badge-pill"
                                                                    style="position:absolute;top:-10px;right:-10px;z-index:999;">{{
                                                                    $sp->countTaxDocument() }}</span>
                                                            </a>
                                                        </td> --}}
                                                        <td>
                                                            <a href="#step-2" onclick="editPurchase({{ $sp->id }})"
                                                                class="btn bg-info"><i class="icon-pencil5"></i></a>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"
                                                                onclick="deletePurchase({{ $sp->id }})"
                                                                class="btn bg-danger"><i class="icon-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr id="collapse-link-collapsed{{ $sp->id }}" class="collapse">
                                                        <td colspan="12">
                                                            <h4>Purchase Progress Document Informations</h4>
                                                            <p>
                                                            <ol>
                                                                <li>Proof of Proforma : {{ count($sp->sampleProforma)
                                                                    }}</li>
                                                                <li>Delivery Document : {{ count($sp->sampleShipment)
                                                                    }}</li>
                                                                <li>Warehouse Received : {{ count($sp->sampleWarehouse)
                                                                    }}</li>
                                                            </ol>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <hr>
                                    </div>
                                    <div class="form-group text-center">
                                        <a class="btn btn-primary" data-toggle="collapse"
                                            href="#collapse-link-collapsed1">
                                            See All Purchase Return
                                        </a>
                                    </div>
                                    <div class="form-group collapse" id="collapse-link-collapsed1">
                                        <h5><b><span class="badge badge-danger">2.i</span> List of All Return of Goods
                                                to Supplier</b></h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="table-secondary">
                                                    <tr class="text-center">
                                                        <th>Return No.</th>
                                                        <th>PO No.</th>
                                                        <th>Supplier</th>
                                                        <th>Approved</th>
                                                        <th>Document</th>
                                                        <th>Proof</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($customer_sample->samplePurchaseReturn as $ppr)
                                                    <tr class="text-center">
                                                        <td class="align-middle">{{ $ppr->code }}</td>
                                                        <td class="align-middle">{{ $ppr->samplePurchase->code }}</td>
                                                        <td class="align-middle">{{
                                                            $ppr->samplePurchase->supplier->name }}</td>
                                                        <td class="align-middle">
                                                            @php
                                                            if(isset($ppr->approve->name)){
                                                            echo $ppr->approve->name;
                                                            }else{
                                                            echo '<button type="button" class="btn btn-primary btn-icon"
                                                                onclick="approvePurchaseReturn(1,'.$ppr->id.')"><i
                                                                    class="icon-checkmark2"></i></button>';
                                                            }
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            <a onclick="openLink('{{ url('admin/sales/sample/print/purchase_return/' . base64_encode($ppr->id)) }}')"
                                                                href="javascript:void(0);" class="btn bg-info"><i
                                                                    class="icon-file-pdf"></i></a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ $ppr->attachment() }}" target="_blank"
                                                                class="btn bg-info"><i class="icon-file-pdf"></i></a>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"
                                                                onclick="deleteReturn({{ $ppr->id }})"
                                                                class="btn bg-danger"><i class="icon-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @if(count($customer_sample->samplePurchaseReturn) == 0)
                                                    <tr class="text-center">
                                                        <td colspan="7">
                                                            <div
                                                                class="alert alert-info alert-styled-left alert-dismissible">
                                                                <button type="button" class="close"
                                                                    data-dismiss="alert"><span>×</span></button><span
                                                                    class="font-weight-semibold">Empty!</span> There is
                                                                no purchase return here.
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- @endif --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @else
                <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    <span class="font-weight-semibold">Warning!</span> Please contact Accounting (approve) and Sales Manager (acknowledge) to Approve the required approvals to continue to the next step.
                </div>
                @endif
                @if ($customer_sample->progress >= 55)
                <div class="card" id="step-3">
                    <form action="" method="POST" enctype="multipart/form-data" id="form-proforma">
                        @csrf
                        <div class="card-body">
                            <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">3</span> Proof of
                                    Proforma Invoice</b> <a href="javascript:void(0);" class="btn btn-info float-right"
                                    id="modeproforma" onclick="resetProforma()">Add <i class="icon-loop3"></i></a></h3>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="alert alert-info alert-styled-left alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                                <span class="font-weight-semibold">Attention!</span> Please select purchase order first
                                before input the proforma invoice.
                            </div>
                            @if(isset($_GET['step-3']))
                            @if($errors->any())
                            <div class="alert bg-warning text-white alert-styled-left alert-dismissible"
                                style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
                                        <label>Purchase Order :<sup class="text-danger">*</sup></label>
                                        <input type="hidden" id="temp_proforma" name="temp_proforma" value="">
                                        <select name="sample_purchase_id" id="sample_purchase_id" class="select2"
                                            onchange="getPurchaseInfo(this,this.value);">
                                            <option value="">-- Choose --</option>
                                            @foreach($customer_sample->samplePurchase as $sp)
                                            <option value="{{ $sp->id }}">{{ $sp->code.' - '.$sp->supplier->name.' -
                                                '.$sp->sample->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date :<sup class="text-danger">*</sup></label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ old('date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Supplier :</label>
                                        <input type="text" name="supplier_name" id="supplier_name" class="form-control"
                                            placeholder="Enter here" value="{{ old('supplier_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Origin of Goods :</label>
                                        <input type="text" name="supplier_warehouse" id="supplier_warehouse"
                                            class="form-control" placeholder="Enter here"
                                            value="{{ old('supplier_warehouse') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Note :</label>
                                        <input type="text" name="note" id="note" class="form-control"
                                            placeholder="Enter here" value="{{ old('note') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Proof of Proforma :</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" id="file" name="file" class="form-control h-auto"
                                                    accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group text-center">
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed2">
                                    See All Proof of Proforma
                                </a>
                            </div>
                            <div class="form-group collapse" id="collapse-link-collapsed2">
                                <h5><b><span class="badge badge-danger">3.a</span> List of All Proof of Proforma</b>
                                </h5>
                                <div class="table-responsive" id ="table-proforma">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-secondary">
                                            <tr class="text-center">
                                                <th>PO Code</th>
                                                <th>Date</th>
                                                <th>Supplier</th>
                                                <th>Warehouse</th>
                                                <th>Note</th>
                                                <th>Proof</th>
                                                <th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer_sample->sampleProforma as $sp)
                                            <tr class="text-center">
                                                <td class="align-middle"><a href="javascript:void(0);"
                                                        onclick="showPurchaseProduct(this,'{{ $sp->samplePurchase->id }}')">{{
                                                        $sp->samplePurchase->code }}</a></td>
                                                <td class="align-middle">{{ $sp->date }}</td>
                                                <td class="align-middle">{{ $sp->supplier_name }}</td>
                                                <td class="align-middle">{{ $sp->supplier_warehouse }}</td>
                                                <td class="align-middle">{{ $sp->note }}</td>
                                                <td class="align-middle">
                                                    <a href="{{ $sp->attachment() }}" class="btn bg-info"
                                                        target="_blank"><i class="icon-search4"></i></a>
                                                </td>
                                                <td>
                                                    <a href="#step-3" onclick="editProforma({{ $sp->id }})"
                                                        class="btn bg-warning"><i class="icon-pencil5"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="button" onclick="createProofProformaSample({{$customer_sample->id}})"
                                        class="btn bg-purple">Save &
                                        Next <i class="icon-square-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card" id="step-4">
                    <form action="" method="POST" enctype="multipart/form-data" id="form-shipment">
                        @csrf
                        <div class="card-body">
                            <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">4</span> Delivery
                                    Document Number</b> <a href="javascript:void(0);" class="btn btn-info float-right"
                                    id="modeshipment" onclick="resetShipment()">Add <i class="icon-loop3"></i></a></h3>
                            <div class="form-group">
                                <hr>
                            </div>
                            @if(isset($_GET['step-4']))
                            <div class="alert alert-danger" id="validation_alert-4"
                            style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                                <ul id="validation_content-4"></ul>
                            </div>
                            @if(session('success'))
                            <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                            @endif
                            @endif
                            <div class="row">
                                <div class="col-md-12 d-none edit-sh-full">
                                    <div class="form-group">
                                        <label>Reason :<sup class="text-danger">*</sup></label>
                                        <textarea type="text" name="edit_reason_shipment" id="edit_reason_shipment"
                                            class="form-control"
                                            placeholder="Please describe why you edit this shipment."
                                            rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Purchase Order :<sup class="text-danger">*</sup></label>
                                        <input type="hidden" id="temp_shipment_id" name="temp_shipment_id" value="">
                                        <select name="sample_purchase_id" id="smpl_purchase_id" class="select2"
                                            onchange="getPurchaseProduct(this,this.value);">
                                            <option value="">-- Choose --</option>
                                            @foreach($customer_sample->samplePurchase as $sp)
                                            <option value="{{ $sp->id }}">{{ $sp->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipment Document Code :<sup class="text-danger">*</sup></label>
                                        <input type="text" name="shipment_code" id="shipment_code" class="form-control"
                                            placeholder="Enter shipment code" value="{{ old('shipment_code') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Loading Date :<sup class="text-danger">*</sup></label>
                                        <input type="date" name="loading_date" id="loading_date" class="form-control"
                                            value="{{ old('loading_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Departure Date :<sup class="text-danger">*</sup></label>
                                        <input type="date" name="departure_date" id="departure_date"
                                            class="form-control" value="{{ old('departure_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Port :<sup class="text-danger">*</sup></label>
                                        <input type="text" name="from_port" id="from_port" class="form-control"
                                            placeholder="Enter from port" value="{{ old('from_port') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Port :<sup class="text-danger">*</sup></label>
                                        <input type="text" name="to_port" id="to_port" class="form-control"
                                            placeholder="Enter to port" value="{{ old('to_port') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>ETA :<sup class="text-danger">*</sup></label>
                                        <input type="date" name="eta" id="eta" class="form-control"
                                            value="{{ old('eta') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Delivery Method :<sup class="text-danger">*</sup></label>
                                        <select name="delivery_method" id="delivery_method" class="custom-select">
                                            <option value="1" {{ old('delivery_method')=='1' ? 'selected' : '' }}>Air
                                            </option>
                                            <option value="2" {{ old('delivery_method')=='2' ? 'selected' : '' }}>Sea
                                            </option>
                                            <option value="3" {{ old('delivery_method')=='3' ? 'selected' : '' }}>Land
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Proof of Document :</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" id="file" name="file" class="form-control h-auto"
                                                    accept="image/x-png,image/jpg,image/jpeg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Note :</label>
                                        <textarea name="note" id="note" class="form-control" placeholder="Enter note"
                                            rows="1">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-title"><b><span class="badge badge-danger">4.a</span> Detail Purchase
                                    Products</b></h5>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-secondary">
                                            <tr class="text-center">
                                                <th width="5%">No</th>
                                                <th width="45%">Product</th>
                                                <th width="20%">Qty Order</th>
                                                <th width="20%">Qty Sent</th>
                                                <th width="10%">Unit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_shipment_product">
                                            <tr>
                                                <td class="bg-warning" colspan="6" style="text-align:center;">Choose
                                                    Purchase Order first to see All products can be sent.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="button" class="btn bg-purple"
                                        onclick="createDeliveryShipment({{$customer_sample->id}})">Save &
                                        Next <i class="icon-square-right"></i></button>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed6">
                                    See All Shipment Purchases
                                </a>
                            </div>
                            <div class="form-group collapse" id="collapse-link-collapsed6">
                                <h5><b><span class="badge badge-danger">4.b</span> List of All Shipment Purchases</b>
                                </h5>
                                <div class="table-responsive" id="shipment-table">
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
                                                <th>Proof</th>
                                                <th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer_sample->sampleShipment()->orderBy('sample_purchase_id')->get()
                                            as $sp)
                                            <tr class="text-center">
                                                <td class="align-middle"><a href="javascript:void(0);"
                                                        onclick="showPurchaseProduct(this,'{{ $sp->samplePurchase->id }}')">{{
                                                        $sp->samplePurchase->code }}</a></td>
                                                <td class="align-middle">{{ $sp->shipment_code }}</td>
                                                <td class="align-middle">{{ $sp->loading_date }}</td>
                                                <td class="align-middle">{{ $sp->departure_date }}</td>
                                                <td class="align-middle">{{ $sp->from_port }}</td>
                                                <td class="align-middle">{{ $sp->to_port }}</td>
                                                <td class="align-middle">{{ $sp->eta }}</td>
                                                <td class="align-middle">{{ $sp->note }}</td>
                                                <td class="align-middle"><a class="btn bg-info"
                                                        href="javascript:void(0);"
                                                        onclick="addTrackingShipment({{ $sp->id }},'{{ $sp->shipment_code }}')"><i
                                                            class="icon-truck"></i></a></td>
                                                <td class="align-middle"><a href="{{ $sp->attachment() }}"
                                                        class="btn bg-info" target="_blank"><i
                                                            class="icon-search4"></i></a></td>
                                                <td>
                                                    <a href="#step-4" onclick="editShipment({{ $sp->id }})"
                                                        class="btn bg-info"><i class="icon-pencil5"></i></a>
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
                <div class="card" id="step-5">
                    <form action="" method="POST" enctype="multipart/form-data" id="form-warehouse">
                        @csrf
                        <div class="card-body">
                            <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">5</span>
                                    Warehouse Receive</b> <a href="javascript:void(0);" class="btn btn-info float-right"
                                    id="modewarehouse" onclick="resetWarehouse()">Add <i class="icon-loop3"></i></a>
                            </h3>
                            <div class="form-group">
                                <hr>
                            </div>

                            @if(isset($_GET['step-5']))
                            <div class="alert alert-danger" id="validation_alert-5"
                            style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                                <ul id="validation_content-5"></ul>
                            </div>
                            @if(session('success'))
                            <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                            @endif
                            @endif
                            <div class="row">
                                <div class="col-md-12 d-none edit-sw-full">
                                    <div class="form-group">
                                        <label>Reason :<sup class="text-danger">*</sup></label>
                                        <textarea type="text" name="edit_reason_warehouse" id="edit_reason_warehouse"
                                            class="form-control"
                                            placeholder="Please describe why you edit this warehouse."
                                            rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Purchase Order :<sup class="text-danger">*</sup></label>
                                        <input type="hidden" id="temp_warehouse_id" name="temp_warehouse_id" value="">
                                        <select name="sample_purchase_id" id="smple_purchase_id" class="select2"
                                            onchange="getShipmentInfo(this.value)">
                                            <option value="">-- Choose --</option>
                                            @foreach($customer_sample->samplePurchase as $sp)
                                            <option value="{{ $sp->id }}">{{ $sp->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipment :<sup class="text-danger">*</sup></label>
                                        <select name="shipment_id" id="shipment_id" class="form-control"
                                            onchange="getShipmentProduct(this.value)">
                                            <option value="">-- Empty --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Receiver :<sup class="text-danger">*</sup></label>
                                        <input type="text" name="person" id="person" class="form-control"
                                            value="{{ old('person') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date & time received :<sup class="text-danger">*</sup></label>
                                        <input class="form-control" type="datetime-local" name="date_receive"
                                            id="date_receive" value="{{ old('date_receive') }}"
                                            max="{{ date('Y-m-d H:i:s') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Warehouse :<sup class="text-danger">*</sup></label>
                                        <select name="warehouse_id" id="warehouse_id"></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Proof of Shipment :<sup class="text-danger">*</sup></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" id="file" name="file" class="form-control h-auto"
                                                    accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <h5 class="card-title"><b><span class="badge badge-danger">5.a</span> Journal
                                    Information</b></h5>
                            <div class="form-group">
                                <hr>
                            </div> --}}
                            <div class="row justify-content-center">
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Include Cost (EMKL,LS,Freight) :<sup class="text-danger">*</sup></label>
                                        <select name="include_cost" id="include_cost" class="custom-select">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 cost-class d-none">
                                    <div class="form-group">
                                        <label>Choose COA :</label>
                                        <select name="wr_coa" id="wr_coa" class="select2">
                                            @foreach($coa_id as $row)
                                            <option value="{{ $row->id }}">{{ $row->code.' - '.$row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 cost-class d-none">
                                    <div class="form-group">
                                        <label>Add New :</label>
                                        <a class="btn btn-info btn-block" onclick="addNewCoa()"
                                            href="javascript:void(0);"><i class="icon-plus3"></i></a>
                                    </div>
                                </div> --}}
                                <div class="col-md-6 cost-class d-none">
                                    <div class="alert alert-info alert-styled-left alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                                        <span class="font-weight-semibold">Important Info!</span><b> This information is
                                            used to determine journal information.</b>
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-title"><b><span class="badge badge-danger">5.a</span> Detail Shipment
                                    Products</b></h5>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-secondary">
                                            <tr class="text-center">
                                                <th width="5%">No</th>
                                                <th width="40%">Product</th>
                                                <th width="15%">Qty</th>
                                                <th width="10%">Unit</th>
                                                <th width="15%">Qty Broken</th>
                                                <th width="10%">Unit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_warehouse_product">
                                            <tr>
                                                <td class="bg-warning" colspan="7" style="text-align:center;">Choose
                                                    Shipment first to see All products that can be received.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="button" onclick="createWarehouseReceived({{$customer_sample->id}})"
                                        class="btn bg-purple">Save &
                                        Next <i class="icon-square-right"></i></button>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed7">
                                    See All Warehouse Receive
                                </a>
                            </div>
                            <div class="form-group collapse" id="collapse-link-collapsed7">
                                <h5 class="card-title"><b><span class="badge badge-danger">5.c</span> List of All
                                        Warehouse Receive</b></h5>
                                <div class="table-responsive" id="table-wr">
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
                                                <th>Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $tempid = 0;
                                            @endphp
                                            @foreach($customer_sample->sampleWarehouse()->orderBy('sample_purchase_id')->get()
                                            as $sw)
                                            <tr class="text-center">
                                                <td class="align-middle"><a href="javascript:void(0);"
                                                        onclick="showPurchaseProduct(this,'{{ $sw->samplePurchase->id }}')">{{
                                                        $sw->samplePurchase->code }}</a></td>
                                                <td class="align-middle">{{ $sw->sampleShipment ?
                                                    $sw->sampleShipment->shipment_code : '' }}</td>
                                                <td class="align-middle">{{ $sw->code }}</td>
                                                <td class="align-middle">{{ $sw->warehouse->name.' -
                                                    '.$sw->warehouse->code }}</td>
                                                <td class="align-middle">{{ $sw->person }}</td>
                                                <td class="align-middle">{{ $sw->date_receive }}</td>
                                                <td class="align-middle">
                                                    <a href="{{ $sw->attachment() }}" class="btn bg-info"
                                                        target="_blank"><i class="icon-search4"></i></a>
                                                </td>
                                                <td class="align-middle">
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/warehouse_receive/' . base64_encode($sw->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-info"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td>
                                                <td>
                                                    <a href="#step-5" onclick="editWarehouse({{ $sw->id }})"
                                                        class="btn bg-info"><i class="icon-pencil5"></i></a>
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
                @endif
                @if(in_array(1, session('bo_role'))|| in_array(10, session('bo_role')) || in_array(11, session('bo_role')))
                @if ($customer_sample->progress >= 75)
                <div class="card" id="step-6">
                    <form action="" method="POST" id="form-delivery">
                        @csrf
                        <div class="card-body">
                            <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">6</span> Delivery
                                    To Project</b> <a href="javascript:void(0);" class="btn btn-info float-right"
                                    id="modedelivery" onclick="resetDelivery()">Add <i class="icon-loop3"></i></a></h3>
                            <div class="form-group">
                                <hr>
                            </div>
                            @if(isset($_GET['step-6']))
                            @if($errors->any())
                            <div class="alert bg-warning text-white alert-styled-left alert-dismissible"
                                style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
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
                                <div class="col-md-12 d-none edit-do">
                                    <div class="form-group">
                                        <label>Reason :<sup class="text-danger">*</sup></label>
                                        <textarea type="text" name="edit_reason_delivery" id="edit_reason_delivery"
                                            class="form-control"
                                            placeholder="Please describe why you edit this Delivery Order."
                                            rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sample Order :<sup class="text-danger">*</sup></label>
                                        <input type="hidden" id="temp_delivery_id" name="temp_delivery_id" value="">
                                        <select name="sample_id" id="sod_id" class="select2"
                                            onchange="getSampleProduct(this,this.value);getSalesInfo(this,this.value);">
                                            <option value="">-- Choose --</option>
                                            @foreach($customer_sample->sample as $s)
                                            <option value="{{ $s->id }}">{{ $s->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Receiver Name :<span class="text-danger">*</span></label>
                                        <input type="text" name="receiver_name" id="receiver_name" class="form-control"
                                            value="{{ old('receiver_name') }}" placeholder="Enter receiver name"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Delivery Date :<span class="text-danger">*</span></label>
                                        <input type="date" name="delivery_date" id="delivery_date" class="form-control"
                                            value="{{ old('delivery_date') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email :</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email') }}" placeholder="Enter email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Phone :<span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ old('phone') }}" placeholder="Enter phone" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City :<span class="text-danger">*</span></label>
                                        <select name="city_id2" id="city_id2" class="select2" style="width:100%;"
                                            required></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Warehouse :<sup class="text-danger">*</sup></label>
                                        <select name="warehousedeliver_id" id="warehousedeliver_id"></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Expedition :<sup class="text-danger">*</sup></label>
                                        <select name="expedition_id" id="expedition_id" class="select2">
                                            <option value="">-- Choose --</option>
                                            @foreach($vendor as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Proof of Delivery :</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" id="file" name="file" class="form-control h-auto"
                                                    accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Address :<span class="text-danger">*</span></label>
                                        <textarea name="address" id="address" class="form-control"
                                            placeholder="Enter address" rows="1"
                                            required>{{ old('address') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Is Dropshipper? :<span class="text-danger">*</span></label>
                                        <select name="dropshipper" id="dropshipper" class="custom-select">
                                            <option value="1">No</option>
                                            <option value="2">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Is Sample? :<span class="text-danger">*</span></label>
                                        <select name="is_sales" id="is_sales" class="custom-select">
                                            <option value="1">No</option>
                                            <option value="2">Yes</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-4" style="display:none;" id="data-dropshipper">
                                    <div class="form-group">
                                        <label>Dropshipper :<span class="text-danger">*</span></label>
                                        <select name="dropshipper_id" id="dropshipper_id" class="custom-select">
                                            @foreach($dropshipper as $dr)
                                            <option value="{{ $dr->id }}">{{ $dr->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pick Up Driver :</label>
                                        <input type="text" name="pick_up_name" id="pick_up_name" class="form-control"
                                            value="{{ old('pick_up_name') ? old('pick_up_name') : '' }}"
                                            placeholder="Enter pick up driver name if any.">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pick Up Plat No. :</label>
                                        <input type="text" name="pick_up_plat" id="pick_up_plat" class="form-control"
                                            value="{{ old('pick_up_plat') ? old('pick_up_plat') : '' }}"
                                            placeholder="Enter pick up vehicle plat no if any.">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pick Up Vehicle :</label>
                                        <input type="text" name="pick_up_vehicle" id="pick_up_vehicle"
                                            class="form-control"
                                            value="{{ old('pick_up_vehicle') ? old('pick_up_vehicle') : '' }}"
                                            placeholder="Enter pick up vehicle name or type if any.">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Note :<span class="text-danger">*</span></label>
                                        <textarea name="service_note" id="service_note" class="form-control"
                                            placeholder="Enter note for service bill"
                                            rows="1">{{ old('service_note') ? old('service_note') : '-' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Note :</span></label>
                                        <textarea name="note" id="note" class="form-control"
                                            placeholder="Enter note for service bill"
                                            rows="1">{{ old('note') ? old('note') : '-' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-title"><b><span class="badge badge-danger">6.a</span> Detail Sales
                                    Products</b></h5>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-secondary">
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Product</th>
                                                <th>Qty Need</th>
                                                <th>Qty Left</th>
                                                <th>Qty Stock</th>
                                                <th>Qty Send</th>
                                                <th>Shading</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_delivery_products">
                                            <td class="bg-warning" colspan="8" style="text-align:center;">Choose Sales
                                                Order first to see All products that can be sent.</td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="button" onclick="createSampleDelivery({{$customer_sample->id}})"
                                        class="btn bg-purple submit_delivery">Save & Next <i
                                            class="icon-square-right"></i></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group text-center">
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed">
                                    See All Delivery Order
                                </a>
                            </div>

                            <div class="form-group collapse" id="collapse-link-collapsed">
                                <h5 class="card-title"><b><span class="badge badge-danger">6.b</span> List of All
                                        Delivery Order</b></h5>
                                <div class="table-responsive" id="table_delivery">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-secondary">
                                            <tr class="text-center">
                                                <th>SMP No.</th>
                                                <th>DO No.</th>
                                                <th>Warehouse</th>
                                                <th>Receiver</th>
                                                <th>Expedition</th>
                                                <th>Dropship</th>
                                                <th>Approved By</th>
                                                <th>Acknowledge By</th>
                                                <th>Tracking</th>
                                                <th>SO Product</th>
                                                {{-- <th>SO Service</th> --}}
                                                <th>SJ</th>
                                                <th>PUM</th>
                                                <th>Inv Product</th>
                                                <th>Inv Other</th>
                                                {{-- <th>Final News</th> --}}
                                                <th>Proof</th>
                                                <th>Received</th>
                                                <th>DueDate</th>
                                                <th>Add Notes</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer_sample->sampleDelivery()->orderBy('sample_id')->get() as
                                            $psi)
                                            <tr class="text-center">
                                                <td class="align-middle">{{ $psi->sample->code }}</td>
                                                <td class="align-middle">{{ $psi->code }}</td>
                                                <td class="align-middle">{{ $psi->warehouse ? $psi->warehouse->name.' -
                                                    '.$psi->warehouse->code : '' }}</td>
                                                <td class="align-middle">{{ $psi->receiver_name }}</td>
                                                <td class="align-middle">{{ $psi->vendor->name }}</td>
                                                <td class="align-middle">{{ $psi->isDropshipper() }}</td>
                                                <td class="align-middle">
                                                    @php
                                                    if(isset($psi->approve->name)){
                                                    echo $psi->approve->name;
                                                    }else{
                                                    echo '<button type="button" class="btn btn-primary btn-icon"
                                                        onclick="approveDelivery(1,'.$psi->id.')"><i
                                                            class="icon-checkmark2"></i></button>';
                                                    }
                                                    @endphp
                                                </td>
                                                <td class="align-middle">
                                                    @php
                                                    if(isset($psi->acknowledge->name)){
                                                    echo $psi->acknowledge->name;
                                                    }else{
                                                    echo '<button type="button" class="btn btn-primary btn-icon"
                                                        onclick="approveDelivery(2,'.$psi->id.')"><i
                                                            class="icon-checkmark2"></i></button>';
                                                    }
                                                    @endphp
                                                </td>
                                                <td class="align-middle">
                                                    <a class="btn bg-info" href="javascript:void(0);"
                                                        onclick="addTrackingDelivery({{ $psi->id }},'{{ $psi->code }}')"><i
                                                            class="icon-truck"></i></a>
                                                </td>
                                                <td>
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/sample/'. base64_encode($psi->sample->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-info"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td>
                                                {{-- <td>
                                                    <a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_cost/'. base64_encode($psi->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-success"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td> --}}
                                                <td class="align-middle">
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/delivery_order/' . base64_encode($psi->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-info"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td>
                                                <td class="align-middle">
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/pick_up_memo_delivery/' . base64_encode($psi->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-danger"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td>
                                                <td class="align-middle">
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/sales_proforma/' . base64_encode($psi->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-primary"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td>
                                                <td class="align-middle">
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/sales_proforma_other/' . base64_encode($psi->id)) }}')"
                                                        href="javascript:void(0);"
                                                        class="btn bg-primary {{ $psi->isFirstDelivery() ? 'blink-notification' : '' }}"><i
                                                            class="icon-file-pdf"></i></a>
                                                </td>
                                                {{-- <td class="align-middle">
                                                    @if($psi->projectSale->mid_yes_no == '1')
                                                    <a onclick="openLink('{{ url('admin/delivery_order/project/print/sales_news_final/' . base64_encode($psi->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-primary"><i
                                                            class="icon-file-pdf"></i></a>
                                                    @else
                                                    None
                                                    @endif
                                                </td> --}}
                                                <td class="align-middle">
                                                    <a href="{{ $psi->attachment() }}" target="_blank"
                                                        class="btn bg-info"><i class="icon-search4"></i></a>
                                                </td>
                                                <td class="align-middle">
                                                    @if(isset($psi->received_date))
                                                    {{ $psi->received_date }}
                                                    <br>
                                                    <a class="btn bg-danger" href="javascript:void(0);"
                                                        onclick="updateReceivedProof({{ $psi->id }},'{{ $psi->code }}')"><i
                                                            class="icon-file-upload"></i></a>
                                                    @else
                                                    <a class="btn bg-info" href="javascript:void(0);"
                                                        onclick="addDateReceived({{ $psi->id }},'{{ $psi->code }}')"><i
                                                            class="icon-file-check2"></i></a>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    Due date Payment {{ $psi->due_date ? date('d M
                                                    Y',strtotime($psi->due_date)) : 'Empty' }}<br>
                                                    Due date TT {{ $psi->due_date_tt ? date('d M
                                                    Y',strtotime($psi->due_date_tt)) : 'Empty' }}<br>
                                                    <a href="{{ $psi->attachment2() }}" target="_blank"
                                                        class="btn bg-info"><i class="icon-search4"></i></a>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" data-popup="tooltip" title="Add Notes"
                                                        onclick="addNotes({{ $psi->id }})"
                                                        class="btn bg-warning btn-sm"><i class="icon-file-text"></i></a>
                                                </td>
                                                <td>
                                                    <a href="#step-6" onclick="editDelivery({{ $psi->id }})"
                                                        class="btn bg-success"><i class="icon-pencil5"></i></a>
                                                </td>
                                                <td>
                                                    @if(isset($psi->received_date))
                                                    -
                                                    @else
                                                    <a href="javascript:void(0);" class="btn bg-danger btn-sm"
                                                        data-popup="tooltip" title="Delete"
                                                        onclick="deleteDelivery({{ $psi->id }})"><i
                                                            class="icon-trash"></i></a>
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
                @if ($customer_sample->progress >= 90)
                @if (count($customer_sample->sampleDelivery) > 0 &&  isset($customer_sample->sampleDelivery->sortByDesc('created_at')->first()->acknowledge->name) || isset($customer_sample->sampleDelivery->sortByDesc('created_at')->first()->approved->name))
                <div class="card" id="step-7">
                    <form action="" method="POST" enctype="multipart/form-data" id="form-return">
                        @csrf
                        <div class="card-body">
                            <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">7</span> Sample
                                    Return</b></h3>
                            <div class="form-group">
                                <hr>
                            </div>
                            @if(isset($_GET['step-7']))
                            <div class="alert alert-danger" id="validation_alert-7"
                            style="display:none;font-size:15px;font-weight:700;background-color:red;color:white;">
                                <ul id="validation_content-7"></ul>
                            </div>
                            @if(session('success'))
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
                                        <label>Sample Order :<sup class="text-danger">*</sup></label>
                                        <select name="sample_id" id="sor_id" class="select2"
                                            onchange="getSampleProduct(this,this.value);">
                                            <option value="">-- Choose --</option>
                                            @foreach($customer_sample->sample as $sp)
                                            <option value="{{ $sp->id }}">{{ $sp->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Return Memo :</label>
                                        <select name="return_memo" id="return_memo" class="select2">
                                            <option value="">-- Choose --</option>
                                            @foreach($customer_sample->sampleReturnMemo as $srm)
                                            <option value="{{ $srm->id }}">{{ $srm->date.' - '.$srm->reason }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date :<span class="text-danger">*</span></label>
                                        <input type="date" name="sale_return_date" id="sale_return_date"
                                            class="form-control" value="{{ old('sale_return_date') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Warehouse :<sup class="text-danger">*</sup></label>
                                        <select name="warehousereturn_id" id="warehousereturn_id"></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Return Type :<sup class="text-danger">*</sup></label>
                                        <select name="return_type" id="return_type" class="form-control">
                                            <option value="1">Return to supplier / split to another Project.</option>
                                            <option value="2">As a Cost.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Proof of Sales Return :<sup class="text-danger">*</sup></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" id="file" name="file" class="form-control h-auto"
                                                    accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Address :<sup class="text-danger">*</sup></label>
                                        <input type="text" name="address" id="address" class="form-control"
                                            value="{{ old('address') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Note :<sup class="text-danger">*</sup></label>
                                        <input type="text" name="note" id="note" class="form-control"
                                            value="{{ old('note') }}">
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-title"><b><span class="badge badge-danger">7.a</span> Detail Sample
                                    Products</b></h5>
                            <div class="form-group">
                                <hr>
                            </div>
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
                                            <td class="bg-warning" colspan="5" style="text-align:center;">Choose Sample
                                                Order first to see All products that can be returned.</td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="button" onclick="createSampleReturn({{$customer_sample->id}})"
                                        class=" btn bg-purple submit_delivery">Save & Next <i
                                            class="icon-square-right"></i></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr>
                            </div>
                            <div class="form-group text-center">
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed1">
                                    See All Sample Return
                                </a>
                            </div>

                            <div class="form-group collapse" id="collapse-link-collapsed1">
                                <h5 class="card-title"><b><span class="badge badge-danger">7.b</span> List of All Sample
                                        Return</b></h5>
                                <div class="table-responsive" id="table_return">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-secondary">
                                            <tr class="text-center">
                                                <th>SO Code</th>
                                                <th>Code</th>
                                                <th>Note</th>
                                                <th>Approved By</th>
                                                <th>Proof</th>
                                                <th>Upload/Receive Goods</th>
                                                <th><i class="icon-printer2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer_sample->sampleReturn()->orderBy('sample_id')->get() as
                                            $psr)
                                            <tr class="text-center">
                                                <td class="align-middle">{{ $psr->sample->code }}</td>
                                                <td class="align-middle">{{ $psr->code }}</td>
                                                <td class="align-middle">{{ $psr->note }}</td>
                                                <td class="align-middle">
                                                    @php
                                                    if(isset($psr->approve->name)){
                                                    echo $psr->approve->name;
                                                    }else{
                                                    echo '<button type="button" class="btn btn-primary btn-icon"
                                                        onclick="approveReturn(1,'.$psr->id.')"><i
                                                            class="icon-checkmark2"></i></button>';
                                                    }
                                                    @endphp
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ $psr->attachment() }}" class="btn bg-info"
                                                        target="_blank"><i class="icon-search4"></i></a>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="javascript:void(0);" class="btn bg-success"
                                                        onclick="uploadNewReturn({{ $psr->id }},'{{ $psr->attachment() }}')"><i
                                                            class="icon-file-upload"></i></a>
                                                </td>
                                                <td class="align-middle">
                                                    <a onclick="openLink('{{ url('admin/sales/sample/print/sales_return/' . base64_encode($psr->id)) }}')"
                                                        href="javascript:void(0);" class="btn bg-primary"><i
                                                            class="icon-file-pdf"></i></a>
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
                @else
                <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    <span class="font-weight-semibold">Warning!</span> Please contact Accounting (approve) and Sales Manager (acknowledge) to Approve the required approvals to continue to the next step.
                </div>
                @endif
                @endif
                @endif
            </div>

            {{-- SIDEBAR --}}
            <div class="sidebar-sticky order-1 order-md-2" id="progress-nav">
                <!-- Secondary sidebar -->
                <div
                    class="sidebar sidebar-light sidebar-secondary sidebar-component sidebar-component-right sidebar-expand-md">

                    <!-- Sidebar content -->
                    <div class="sidebar-content"
                        style="overflow-y: scroll;background-color:#457c80 !important;height:73vh;">

                        <div class="card">
                            <div class="card-header bg-transparent header-elements-inline"
                                style="background-color:#457c80 !important;color:white !important;">
                                <span class="text-uppercase font-size-sm font-weight-semibold"
                                    style="font-size:14px;">Sample
                                    No. {{ $customer_sample->code }}</span>
                            </div>
                            <ul class="nav nav-sidebar nav-scrollspy">
                                <li class="nav-item">
                                    <a href="#step-1" class="nav-link">
                                        <i class="icon-check text-success"></i>
                                        1. Form Sample
                                        <span class="badge bg-warning badge-pill ml-auto">20%</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#step-2" class="nav-link">
                                        @if($customer_sample->progress >= 55)
                                        <i class="icon-check text-success"></i>
                                        @else
                                        <i class="icon-spinner10"></i>
                                        @endif
                                        2. PO Supplier
                                        <span class="badge bg-warning badge-pill ml-auto">55%</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#step-3"
                                        class="nav-link {{ $customer_sample->progress >= 55 ? '' : 'disabled' }}">
                                        @if($customer_sample->progress >= 60)
                                        <i class="icon-check text-success"></i>
                                        @else
                                        <i class="icon-spinner10"></i>
                                        @endif
                                        3. Proof of Proforma Inv
                                        <span class="badge bg-warning badge-pill ml-auto">60%</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#step-4"
                                        class="nav-link {{ $customer_sample->progress >= 60 ? '' : 'disabled' }}">
                                        @if($customer_sample->progress >= 65)
                                        <i class="icon-check text-success"></i>
                                        @else
                                        <i class="icon-spinner10"></i>
                                        @endif
                                        4. Delivery Document Number
                                        <span class="badge bg-warning badge-pill ml-auto">65%</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#step-5"
                                        class="nav-link {{ $customer_sample->progress >= 65 ? '' : 'disabled' }}">
                                        @if($customer_sample->progress >= 75)
                                        <i class="icon-check text-success"></i>
                                        @else
                                        <i class="icon-spinner10"></i>
                                        @endif
                                        5. Warehouse Receive
                                        <span class="badge bg-warning badge-pill ml-auto">75%</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#step-6"
                                        class="nav-link {{ $customer_sample->progress >= 75 ? '' : 'disabled' }}">
                                        @if($customer_sample->progress >= 90)
                                        <i class="icon-check text-success"></i>
                                        @else
                                        <i class="icon-spinner10"></i>
                                        @endif
                                        6. Delivery to Project
                                        <span class="badge bg-warning badge-pill ml-auto">90%</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#step-7"
                                        class="nav-link {{ $customer_sample->progress >= 90 ? '' : 'disabled' }}">
                                        @if($customer_sample->progress >= 95)
                                        <i class="icon-check text-success"></i>
                                        @else
                                        <i class="icon-spinner10"></i>
                                        @endif
                                        7. Sample Return
                                        <span class="badge bg-warning badge-pill ml-auto">95%</span>
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



    {{-- MODAL --}}

    <div id="modal_return_memo" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="max-width: 600px !important;max-height: 100% !important;">
                <div class="modal-header">
                    <h5 class="modal-title">Form Add Return Memo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="form_return_memo">
                        <div class="form-group">
                            <hr>
                        </div>
                        <h5 class="card-title">Main Information</h5>
                        <div class="alert alert-danger" id="validation_alert_memo" style="display:none;">
                            <ul id="validation_content_memo"></ul>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Delivery Order :<sup class="text-danger">*</sup></label>
                                    <select name="delivery_id" id="delivery_id" class="select2"
                                        onchange="getDeliveryProduct(this);">
                                        <option value="">-- Choose --</option>
                                        @foreach($customer_sample->sampleDelivery as $pd)
                                        <option value="{{ $pd->id }}">{{ 'DO No. '.$pd->code.' - SO No.
                                            '.$pd->sample->code }}</option>
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
                                            <input type="file" id="return_memo_proof" name="return_memo_proof"
                                                class="form-control h-auto"
                                                accept="image/x-png,image/jpg,image/jpeg,application/pdf">
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
                        <div class="form-group">
                            <hr>
                        </div>
                        <h5 class="card-title">Product Information</h5>
                        <div class="form-group">
                            <hr>
                        </div>
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
                                    <tbody id="data_sample_return">
                                        <td class="bg-warning" colspan="5" style="text-align:center;">Choose Delivery
                                            Order first to see All products that can be returned.</td>
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

    <div id="modal_tracking_delivery" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width: 800px !important;">
                <div class="modal-header bg-info">
                    <h6 class="modal-title">Delivery No. <b id="modal_title_tracking_delivery"></b></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h5 class="card-title">
                        <b>Detail Tracking Delivery</b>
                    </h5>

                    <div class="form-group">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="hidden" id="tempdeliveryid">
                                <input type="text" class="form-control" name="tracking-delivery-note"
                                    id="tracking-delivery-note" placeholder="Type note">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" id="tracking-delivery-file" name="tracking-delivery-file"
                                            class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="button" data-id="" onclick="addDeliveryTrackingNote(this)"
                                    class="btn bg-success col-12" id="btnaddtrackingdelivery"><i class="icon-plus2"></i>
                                    Add</button>
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
                                            <div class="alert alert-info alert-styled-left alert-dismissible">
                                                <button type="button" class="close"
                                                    data-dismiss="alert"><span>×</span></button><span
                                                    class="font-weight-semibold">Empty!</span> There is no tracking
                                                data.
                                            </div>
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

    <div id="modal_tracking_shipment" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width: 800px !important;">
                <div class="modal-header bg-info">
                    <h6 class="modal-title">Shipment No. <b id="modal_title_tracking_shipment"></b></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h5 class="card-title">
                        <b>Detail Tracking Shipment</b>
                        {{-- <a href="javascript:void(0);" target="_blank" id="link-tracking-shipment"
                            class="btn btn-primary btn-sm float-right ml-1">View <i class="icon-file-eye"></i></a>
                        <a href="javascript:void(0);" id="email-tracking-shipment"
                            class="btn btn-info btn-sm float-right ml-1">Email <i class="icon-envelop3"></i></a>
                        <a href="javascript:void(0);" id="whatsapp-tracking-shipment"
                            class="btn btn-success btn-sm float-right ml-1">Whatsapp <i class="icon-phone-plus"></i></a>
                        --}}
                    </h5>

                    <div class="form-group">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <input type="hidden" id="tempshipmentid">
                                <input type="text" class="form-control" name="tracking-shipment-note"
                                    id="tracking-shipment-note" placeholder="Type note">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="button" onclick="addTrackingShipmentDetail(this)"
                                    class="btn bg-success col-12" id="btnaddtrackingshipment"><i class="icon-plus2"></i>
                                    Add</button>
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
                                            <div class="alert alert-info alert-styled-left alert-dismissible"><button
                                                    type="button" class="close"
                                                    data-dismiss="alert"><span>×</span></button><span
                                                    class="font-weight-semibold">Empty!</span> There is no tracking
                                                data.</div>
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

    <div id="modal_purchase_return" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width: 800px !important;">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Purchase Return Purchase Code. <b id="modal_title_purchase_return"></b></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modal-body-return-purchase">
                    <h5 class="card-title">
                        <b>Main Information</b>
                    </h5>
                    <div class="form-group">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" id="tempreturnpo">
                                <label>Note :<sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" name="purchase-return-note"
                                    id="purchase-return-note" placeholder="Type note">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date :<sup class="text-danger">*</sup></label>
                                <input type="date" name="purchase-return-date" id="purchase-return-date"
                                    class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From Warehouse :<sup class="text-danger">*</sup></label>
                                <select name="purchase-return-warehouse" id="purchase-return-warehouse"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Proof :</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" id="purchase-return-file" name="purchase-return-file"
                                            class="form-control h-auto"
                                            accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Warehouse Receive Link :</label>
                                <select name="sample_warehouse_id" id="sample_warehouse_id" class="select2">
                                    <option value="">--Choose this if from purchase bill--</option>
                                    @foreach($customer_sample->sampleWarehouse as $pw)
                                    @php
                                    $pr = '';

                                    if($pw->purchaseRequest()->exists()){
                                    $pr = number_format($pw->purchaseRequest->total_nominal,2,',','.');
                                    }
                                    @endphp
                                    <option value="{{ $pw->id }}">{{ $pw->code.' -
                                        '.$pw->projectPurchase->supplier->name.' '.$pr }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                    </div>
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="data_purchase_return">
                                    <tr>
                                        <td colspan="4">
                                            <div class="alert alert-info alert-styled-left alert-dismissible"><button
                                                    type="button" class="close"
                                                    data-dismiss="alert"><span>×</span></button><span
                                                    class="font-weight-semibold">Empty!</span> There is no purchase
                                                product data.</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-primary" onclick="addReturnPurchase()">Submit</button>
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
                    <div class="form-group">
                        <hr>
                    </div>
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
                                            <input type="file" id="return_proof" name="return_proof"
                                                class="form-control h-auto"
                                                accept="image/x-png,image/jpg,image/jpeg,application/pdf">
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

    <div id="modal_purchase_product" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width: 800px !important;">
                <div class="modal-header bg-info">
                    <h6 class="modal-title">Purchase <b id="modal_title_purchase_code">A</b></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h5 class="card-title"><b>Detail Products</b></h5>
                    <div class="form-group">
                        <hr>
                    </div>
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

    <div class="modal fade" id="modal_notes" data-backdrop="static" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="exampleModalLabel">Add Delivery Order Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info alert-styled-left alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                                <span class="font-weight-semibold">Information!</span> This note will appear to A/R
                                report.
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="hidden" name="tempSales" id="tempSales">
                                <input type="text" class="form-control" name="sales_note_kuy" id="sales_note_kuy"
                                    placeholder="Type note">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" id="sales_file_kuy" name="sales_file_kuy"
                                            class="form-control h-auto"
                                            accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="button" onclick="addSalesNote()" class="btn bg-success col-12"
                                    id="btnaddpurchasenote"><i class="icon-plus2"></i> Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <hr>
                    </div>
                    <div class="form-group">
                        <h5><b>List of All Delivery Notes</b></h5>
                    </div>
                    <div class="form-group">
                        <hr>
                    </div>
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
                                            <div class="alert alert-info alert-styled-left alert-dismissible"><button
                                                    type="button" class="close"
                                                    data-dismiss="alert"><span>×</span></button><span
                                                    class="font-weight-semibold">Empty!</span> There is no notes here.
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i>
                        Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_date_received" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="max-width: 600px !important;max-height: 100% !important;">
                <div class="modal-header">
                    <h5 class="modal-title">Delivery No. <b id="modal_title_received_date"></b></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modal-body-received">
                    <div class="form-group">
                        <hr>
                    </div>
                    <h5 class="card-title">
                        <b>Received Date</b>
                    </h5>
                    @php
                    $mindate = date('Y-m-d');

                    foreach($customer_sample->sampleWarehouse()->orderBy('date_receive')->get() as $key => $row){
                    if($key == 0){
                    $mindate = explode(' ',$row->date_receive)[0];
                    }
                    }

                    @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <label>Date Received</label>
                                <input type="hidden" id="tempreceived">
                                <input type="date" name="received_date" id="received_date" class="form-control"
                                    placeholder="choose date received by customer" min="{{ $mindate }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <label>Due Date Invoice / Bill</label>
                                <input type="date" name="received_due_date" id="received_due_date" class="form-control"
                                    placeholder="choose due date received by customer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <label>File proof</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" id="received_proof" name="received_proof"
                                            class="form-control h-auto filereceived"
                                            accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="card-title">Preview Proof</h5>
                    <div class="form-group text-center" id="previewImgReceived">
                        <img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
                    </div>
                    <div class="form-group">
                        <div class="alert alert-danger alert-styled-left alert-dismissible"><button type="button"
                                class="close" data-dismiss="alert"><span>×</span></button><span
                                class="font-weight-semibold">WARNING!</span> The product's quantity that you input here
                            will affect total sales inputted before.</div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                        <th>Product</th>
                                        <th>Qty Received</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody id="data_qty_received">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-primary" onclick="addReceivedDate()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_update_received" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="max-width: 800px !important;max-height: 100% !important;">
                <div class="modal-header">
                    <h5 class="modal-title">Delivery No. <b id="modal_title_update_received"></b></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modal-body-proof">
                    <div class="form-group">
                        <hr>
                    </div>
                    <h5 class="card-title">
                        <b>Update Proof Received</b>
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <label>Proof</label>
                                    <input type="hidden" id="tempreceivedproof">
                                    <div class="custom-file">
                                        <input type="file" id="received_proof_update" name="received_proof_update"
                                            class="form-control h-auto filereceivedproof"
                                            accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="card-title">Preview Proof</h5>
                    <div class="form-group text-center" id="previewImgReceivedProof">
                        <img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
                    </div>
                    <hr>
                    <h5 class="card-title">
                        <b>Update Receipt Invoice</b>
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <label>Proof</label>
                                    <div class="custom-file">
                                        <input type="file" id="receipt_proof" name="receipt_proof"
                                            class="form-control h-auto filereceiptproof"
                                            accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Due Date TT</label>
                                <input type="date" name="due_date_tt" id="due_date_tt" class="form-control"
                                    placeholder="choose due date tt by customer"
                                    value="{{ date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d')))) }}">
                            </div>
                        </div>
                    </div>
                    <h5 class="card-title">Preview Proof</h5>
                    <div class="form-group text-center" id="previewImgReceiptProof">
                        <img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-primary" onclick="addReceivedProof()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_return" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width: 800px !important;">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Return Proof / Received Date<span id="title-bill"></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="body-return">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Received :<sup class="text-danger">*</sup></label>
                                <input type="date" name="return_date" id="return_date" class="form-control"
                                    placeholder="choose date received by customer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Proof of Payment :<sup class="text-danger">*</sup></label>
                                <input type="hidden" id="id_return" name="id_return">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" id="file_return" name="file_return"
                                            class="form-control h-auto file_return"
                                            accept="image/x-png,image/jpg,image/jpeg,application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <hr>
                            </div>
                            <h5 class="card-title">Preview Proof</h5>
                            <div class="form-group text-center" id="previewImgFileReturn">
                                <img src="{{ url('website/empty.jpg') }}" alt="..." width="150px">
                            </div>
                            <div class="form-group">
                                <button type="button" onclick="addReturnProof()" class="btn bg-success col-12"><i
                                        class="icon-plus2"></i> Add</button>
                            </div>
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
      select2ServerSide('#sales_so, #sales_po', '{{ url("admin/select2/user") }}');
	  select2ServerSide('#customer_id', '{{ url("admin/select2/customer") }}');
      select2ServerSide('#sample_product_id', '{{ url("admin/select2/product") }}');
      select2ServerSide('#supplier_id', '{{ url("admin/select2/supplier") }}');
      select2ServerSide('#country_id', '{{ url("admin/select2/country") }}');
	  select2ServerSide('#city_id,#city_id2', '{{ url("admin/select2/city") }}');
      select2ServerSide('#warehouse_id,#warehousereturn_id,#warehousedeliver_id,#warehouse_destination', '{{ url("admin/select2/warehouse") }}');
      select2ServerSide('#purchase-return-warehouse', '{{ url("admin/select2/warehouse") }}', {
		  dropdownParent: $("#modal_purchase_return")
	  });
	  
    });

    $('#has_memo_item').on('change', function(){
        if($(this).val() == '1'){
            $('.memo-item-class').removeClass('d-none');
        }else{
            $('.memo-item-class').addClass('d-none');
        }
    });

    $('#dropshipper').on('change', function() {
		if($(this).val() == '1'){
			$('#data-dropshipper').hide();
		}else{
			$('#data-dropshipper').show();
		}
	});

    
    $('#data_sample').on('click','#delete_data_sample',function () { 
        $(this).closest('tr').remove();
    });

    $('#data_shipment_product').on('click', '#delete_ship_product', function() {
         $(this).closest('tr').remove();
    });

    $('#data_delivery_product').on('click', '#delete_delivery_products', function() {
         $(this).closest('tr').remove();
    });
	  
    $('#data_warehouse_product').on('click', '#delete_warehouse_product', function() {
         $(this).closest('tr').remove();
    });
	  

    function resetPurchase(){
		location.reload();
		return false;
	}
        
    function resetProforma(){
		location.reload();
		return false;
	}

    function resetShipment(){
		location.reload();
		return false;
	}

    function resetWarehouse(){
		location.reload();
		return false;
	}

    function resetDelivery(){
		location.reload();
		return false;
	}


   function openLink(url) {
		var random = Math.random();
		var newWin = window.open(url, 'New Document' + random, 'width=600,height=400');
	}

    function RefreshTable() {
       $("#table_data_sample").load(" #table_data_sample > *");
       $("#table_return").load(" #table_return > *");
       $("#table_delivery").load(" #table_delivery > *");
       $("#table-proforma").load(" #table-proforma > *");
       $("#shipment-table").load(" #shipment-table > *");
       $("#table-wr").load(" #table-wr > *");

    }
    function refreshProgress(){
        $("#main-content").load(" #main-content > *");
    }

    function refreshNavbar(){
        $("#progress-nav").load(" #progress-nav > *");
    }
    function addSampleReturnMemo(){
		$('#modal_return_memo').modal('toggle');
	}
    function addSampleProof(idSample,codeSample){
		$('#modal_title_sample').html(codeSample);
		$('#tempsample').val(idSample);
		$('#modal_sample').modal('toggle');
	}

    function addSampleProofGo(){
		var formdata = new FormData($('#form_data_sample')[0]);
		formdata.append('tempsample',$('#tempsample').val());
		
		$.ajax({
			 url: '{{ url("admin/sales/sample/add_sample_proof") }}',
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
   
    function addSample() {
      var sample_product_id = $('#sample_product_id');
      var sample_qty        = $('#sample_qty');
	  var sample_unit       = $('#sample_unit');
      var sample_size       = $('#sample_size');

      if(sample_product_id.val() && sample_qty.val() && sample_size.val()) {
         $.ajax({
            url: '{{ url("admin/sales/sample/get_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: sample_product_id.val()
            },
            beforeSend: function() {
               loadingOpen('#data_sample');
            },
            success: function(response) {
                loadingClose('#data_sample');
				
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
               loadingClose('#table_data_sample');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
      } else {
         swalInit.fire('Ooppsss!', 'Please entry all field', 'info');
      }
    }

    function showEditSample(id){
        $.ajax({
         url: '{{ url("admin/sales/sample/show_edit_sample") }}' + '/' + id,
         type: 'POST',
         dataType: 'JSON',
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         success: function(response) {

            if(response.status == 200) {
                $("#temp_sample_id").val(id);
                $("#sample_sent_date").val(response.sample.sent_date);
                $("#sample_return_date").val(response.sample.return_date);
                $('#sales_so').empty();
                $('#sales_so').append(`
                    <option value="` + response.sample.sales_id + `">` + response.sales_name + `</option>
                `);
                $("#sample_note").val(response.sample.note);
               if(response.data.length > 0){
                $.each(response.data, function (i, val) { 
                    $('#data_sample').append(`
                        <tr class="text-center">
                            <input type="hidden" name="sample_product_id[]" value="` + val.product_id + `">
                            <input type="hidden" name="sample_qty[]" value="` + val.qty + `">
                            <input type="hidden" name="sample_unit[]" value="` + val.unit + `">
                            <input type="hidden" name="sample_size[]" value="` + val.size + `">

                            <td class="align-middle">` + val.product_name + `</td>  
                            <td class="align-middle">` + val.qty + `</td>
                            <td class="align-middle">` + val.convert_unit + `</td>
                            <td class="align-middle">` + val.convert_size + `</td>  
                            <td class="align-middle">
                            <button type="button" id="delete_data_sample" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
                            </td>
                        </tr>
                    `);
                });
               }
            }else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            $('.modal-body').scrollTop(0);
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
    }

   function addTrackingShipment(idshipment,codeshipment){
        $('#modal_title_tracking_shipment').html(codeshipment);
		$('#tempshipmentid').val(idshipment);
		
		$.ajax({
            url: '{{ url("admin/sales/sample/get_tracking_shipment") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: $('#tempshipmentid').val()
            },
            beforeSend: function() {
               loadingOpen('#data_shipment_tracking');
            },
            success: function(response) {
			   
			//    var link = '{{ url("/project/tracking/shipment") }}/' + idshipment + '/' + codeshipment;
			//    var whatsapptemplate = 'https://wa.me/?text=' + encodeURIComponent('Hi Mr/Mrs. Here we send you a tracking shipment link for your products. \n' + link);
			   
			   $('#data_shipment_tracking').empty();
			//    $('#link-tracking-shipment').prop('href', '{{ url("/project/tracking/shipment") }}/' + idshipment + '/' + codeshipment);
			//    $('#whatsapp-tracking-shipment').prop('href', whatsapptemplate);
			   
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
               loadingClose('#data_shipment_tracking');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
         });
		
		$('#modal_tracking_shipment').modal('toggle');
    }

    function addTrackingShipmentDetail(element){
		var id = $('#tempshipmentid').val(), note = $('#tracking-shipment-note').val();
		
		$.ajax({
			url: '{{ url("admin/sales/sample/add_shipment_tracking") }}',
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

   function addTrackingDelivery(iddelivery,codedelivery){
            $('#modal_title_tracking_delivery').html(codedelivery);
            $('#tempdeliveryid').val(iddelivery);
            
            $.ajax({
                url: '{{ url("admin/sales/sample/get_delivery_tracking") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                id: $('#tempdeliveryid').val()
                },
                beforeSend: function() {
                loadingOpen('#data_delivery_tracking');
                },
                success: function(response) {
                    // var link = '{{ url("/project/tracking/delivery") }}/' + iddelivery + '/' + replaceAll(codedelivery,'/','-');
                    // var whatsapptemplate = 'https://wa.me/?text=' + encodeURIComponent('Hi Mr/Mrs. Here we send you a tracking delivery link for your products. \n' + link);
                    loadingClose('#data_delivery_tracking');
                    $('#data_delivery_tracking').empty();
                    // $('#link-tracking-delivery').prop('href', '{{ url("/project/tracking/delivery") }}/' + iddelivery + '/' + replaceAll(codedelivery,'/','-'));
                    // $('#whatsapp-tracking-delivery').prop('href', whatsapptemplate);
                    
                    if(response.length > 0) {
                        $.each(response, function(i, val) {
                            var date = new Date(val.created_at);
                            
                            $('#data_delivery_tracking').append(`
                                <tr class="text-center">
                                    <td>` + date.toLocaleString() + `</td>
                                    <td>` + val.note + `</td>
                                    <td>` + val.image + `</td>
                                    <td><button type="button" onclick="deleteTrackingDelivery(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
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

    function addDeliveryTrackingNote(element){
        var id = $('#tempdeliveryid').val(), note = $('#tracking-delivery-note').val();
        var fd = new FormData(), files = $('#tracking-delivery-file')[0].files;
        fd.append('note',note);
        fd.append('id',id);
        if(files.length > 0 ){
        fd.append('file',files[0]);
        }
        
        $.ajax({
            url: '{{ url("admin/sales/sample/add_delivery_tracking_note") }}',
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
                                <td><button type="button" onclick="deleteTrackingDelivery(this,` + val.id + `)" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button></td>
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

    function addNotes(id){
		$('#tempSales').val(id);
		tempSales = id;
		
		$.ajax({
			 url: '{{ url("admin/sales/sample/get_delivery_note") }}',
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

    function addReturnPurchase(){
		var id = $('#tempreturnpo').val();
		var fd = new FormData(), files = $('#purchase-return-file')[0].files;
		var arrProduct = [], arrQty = [], arrUnit = [];
		
		fd.append('id',id);
		fd.append('date',$('#purchase-return-date').val());
		fd.append('warehouse',$('#purchase-return-warehouse').val());
		fd.append('note',$('#purchase-return-note').val());
        
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$("input[name='return_product_id[]']").each(function() {
			fd.append('arrProduct[]',$(this).val());
		});
		
		$("input[name='return_product_qty[]']").each(function() {
			fd.append('arrQty[]',$(this).val());
		});
		
		$("input[name='return_product_unit[]']").each(function() {
			fd.append('arrUnit[]',$(this).val());
		});
		
		if($('#purchase-return-date').val() !== '' && $('#purchase-return-warehouse').val() !== '' && $('#purchase-return-note').val() !== ''){
		
			$.ajax({
				url: '{{ url("admin/sales/sample/add_purchase_return") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#modal-body-return-purchase');
				},
				success: function(response) {
					if(response.status == '200'){
						loadingClose('#modal-body-return-purchase');
						location.reload();
					}else{
						swalInit.fire('Warning!', response.message, 'error');
					}
				},
				error: function() {
					loadingClose('#modal-body-return-purchase');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
			
		}else{
			swalInit.fire('Server Error!', 'Please complete the form!', 'error');
		}
	}

    function addSalesNote(){
		var id = $('#tempSales').val(), note = $('#sales_note_kuy').val();
		var fd = new FormData(), files = $('#sales_file_kuy')[0].files;
		fd.append('note',note);
		fd.append('mode','sample_deliveries');
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/sales/sample/add_sales_notes") }}',
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

    function addDateReceived(iddelivery,codedelivery,term){
		$('#modal_title_received_date').html(codedelivery);
		$('#tempreceived').val(iddelivery);
		$('#modal_date_received').modal('toggle');
		$('#info_received_due_date').text(term);
		
		$.ajax({
            url: '{{ url("admin/sales/sample/get_delivery_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
               id: iddelivery
            },
            beforeSend: function() {
               loadingOpen('#data_qty_received');
            },
            success: function(response) {
				if(response.length > 0) {
					$('#data_qty_received').empty();
					$.each(response, function(i, val) {
						$('#data_qty_received').append(`
							<tr class="text-center">
								<td><input type="hidden" name="delivery_product_id[]" value="` + val.product_id + `">` + val.product_name + `</td>
								<td><input type="number" class="form-control form-control-sm" name="delivery_qty[]" value="` + val.qty + `"></td>
								<td>` + val.unit + `</td>
							</tr>
						`);
					});
					
				}
				
				loadingClose('#data_qty_received');
            },
            error: function() {
               loadingClose('#data_qty_received');
               swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
        });
	}

    function addReceivedDate(){
		var id = $('#tempreceived').val(), date = $('#received_date').val(), duedate = $('#received_due_date').val();
		var fd = new FormData(), files = $('#received_proof')[0].files;
		var arrProduct = [], arrQty = [];
		fd.append('date',date);
		fd.append('duedate',duedate);
		fd.append('id',id);
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$("input[name='delivery_product_id[]']").each(function() {
			fd.append('arrProduct[]',$(this).val());
		});
		
		$("input[name='delivery_qty[]']").each(function() {
			fd.append('arrQty[]',$(this).val());
		});
		
		if(date !== ''){
		
			$.ajax({
				url: '{{ url("admin/sales/sample/add_received_date") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#modal-body-received');
				},
				success: function(response) {
					if(response.status == '200'){
						loadingClose('#modal-body-received');
						location.reload();
					}else{
						swalInit.fire('Server Error!', response.message, 'error');
					}
				},
				error: function() {
					loadingClose('#modal-body-received');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
			
		}else{
			swalInit.fire('Server Error!', 'Please choose date first!', 'error');
		}
	}

    function addReceivedProof(){
		var id = $('#tempreceivedproof').val();
		var fd = new FormData(), files = $('#received_proof_update')[0].files, files2 = $('#receipt_proof')[0].files;
		fd.append('id',id);
		fd.append('due_date_tt',$('#due_date_tt').val());
		/* if(files.length > 0 ){ */
           fd.append('file',files[0]);
		   fd.append('file2',files2[0]);
		   
			$.ajax({
				url: '{{ url("admin/sales/sample/add_received_proof") }}',
				type: 'POST',
				dataType: 'JSON',
				data: fd,
				contentType: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#modal-body-proof');
				},
				success: function(response) {
					if(response.status == '200'){
						loadingClose('#modal-body-proof');
						location.reload();
					}else{
						swalInit.fire('Server Error!', response.message, 'error');
					}
				},
				error: function() {
					loadingClose('#modal-body-proof');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				}
			});
		/* }else{
			swalInit.fire('Server Error!', 'Please choose file first!', 'error');
		} */
	}

    function addReturnProof(){
		var id = $('#id_return').val();
		var fd = new FormData(), files = $('#file_return')[0].files;
		fd.append('id',id);
		fd.append('return_date',$('#return_date').val());
		if(files.length > 0 ){
           fd.append('file',files[0]);
		}
		
		$.ajax({
			url: '{{ url("admin/sales/sample/add_return_proof") }}',
			type: 'POST',
			dataType: 'JSON',
			data: fd,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function() {
				loadingOpen('#body-return');
			},
			success: function(response) {
				if(response.status == '200'){
					loadingClose('#body-return');
					location.reload();
				}else{
					swalInit.fire('Server Error!', response.message, 'error');
				}
			},
			error: function() {
				loadingClose('#modal-body-received');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
		});
	}
	
	function updateReceivedProof(iddelivery,codedelivery){
		$('#modal_title_update_received').html(codedelivery);
		$('#tempreceivedproof').val(iddelivery);
		$('#modal_update_received').modal('toggle');
	}
	

    function updateStatusSample(val,id){
        $.ajax({
            url: '{{ url("admin/sales/sample/update_status_sample") }}',
            type: 'POST',
            dataType: 'JSON',
            data: { 
                val: val,
                id: id 
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                loadingOpen('#table_data_sample');
            },
            success: function(response) {
                if(response.status == '200'){
                    //location.reload();
                    swalInit.fire('Success!', 'Sample status successfully changed.', 'success');
                    loadingClose('#table_data_sample');
                }
            }
        });
        
        return false;
    }

   function deleteTrackingDelivery(element,id){
        $.ajax({
            url: '{{ url("admin/sales/sample/delete_delivery_tracking") }}',
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

   
	function delete_tracking_shipment(element,id){
		$.ajax({
			url: '{{ url("admin/sales/sample/delete_shipment_tracking") }}',
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

    function deletePurchase(idpo){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason" id="delete_reason" class="form-control" placeholder="Enter why this project purchase should be deleted?"></div></div>',
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
						url: '{{ url("admin/sales/sample/delete_purchase") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							idpo : idpo, reason : $('#delete_reason').val()
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-9');
						 },
						 success: function(response) {
							loadingClose('#step-9');
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
							loadingClose('#step-9');
							swalInit.fire('Server Error!', 'Please contact developer', 'error');
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

    function deleteReturn(idreturn){
		var notyConfirm = new Noty({
         theme: 'limitless',
         text: '<h6 class="font-weight-bold mb-3">Are sure you want to delete?</h6><label>Deleted data can no longer be recovered.</label><div class="row"><div class="form-group col-md-12"><input type="text" name="delete_reason_return" id="delete_reason_return" class="form-control" placeholder="Enter why this purchase return should be deleted?"></div></div>',
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
						url: '{{ url("admin/sales/sample/delete_purchase_return") }}',
						type: 'POST',
						dataType: 'JSON',
						 data: {
							id : idreturn, reason : $('#delete_reason_return').val()
						 },
						 headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						 },
						 beforeSend: function() {
							loadingOpen('#step-9');
						 },
						 success: function(response) {
							loadingClose('#step-9');
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
							loadingClose('#step-9');
							swalInit.fire('Server Error!', 'Please contact developer', 'error');
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

   function getSampleProduct(element, idsample) {
     if(element.value !== ''){
		var elemen = element.getAttribute('id');
		$.ajax({
         url: '{{ url("admin/sales/sample/get_sample_product") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id_sample : idsample
         },
         beforeSend: function() {
            if(elemen == "sample_id"){
                loadingOpen('#step-2');
            }else if(elemen == "sod_id"){
                loadingOpen('#step-6');
            }else{
                loadingOpen('#step-7');
            }
         },
         success: function(response) {
            if(elemen == "sample_id"){
                loadingClose('#step-2');
				if(response.length > 0) {
					$('#data_purchase').empty();
					
					$.each(response, function(i, val) {
						$('#data_purchase').append(`
							<tr class="text-center rowproductsale purchaseproductdata` + val.product_id + `" data-m2="` + val.unitother + `">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + ` ` + val.unit + `
							 </td>
							 <td class="align-middle">
								` + val.qty_left +`<br>
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" id="purchaseproductqty` + val.product_id + `" class="form-control" placeholder="0" value="` + val.qty_left +`" required onkeyup="$('#product_price` + val.product_id + `').trigger('keyup');">
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_price[]" id="product_price` + val.product_id + `" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);countTotalPurchase(this,'`+ val.product_id +`');convertPriceBeforeTax(this,'`+ val.product_id +`')" value="` + val.price + `">
							 </td>
							 <td class="align-middle">
								<div id="purchaseproducttotal`+ val.product_id +`" class="purchaseproducttotal"></div>
							 </td>
							 <td class="align-middle">
								<textarea class="form-control" rows="1" name="product_remark[]">` + val.shading + `</textarea>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_data_product_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
						
					});
				}
            }else if(elemen == 'sod_id'){
				loadingClose('#step-6');
				
				if(response.length > 0) {
					$('#data_delivery_products').empty();
					var no = 1;
					
					$.each(response, function(i, val) {
						$('#data_delivery_products').append(`
							<tr class="text-center">
							 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
							 <input type="hidden" name="product_unit[]" value="` + val.unitconvert + `">
							 <input type="hidden" name="product_stock[]" value="` + val.nominalstock + `">
							 <td>` + no + `</td>
							 <td class="align-middle">` + val.product_name + `</td>
							 <td class="align-middle">
								` + val.qty + ` ` + val.unit + `
							 </td>
							 <td class="align-middle">
								` + val.qty_left_deliver +`
							 </td>
							 <td class="align-middle">
								` + val.stock +`
							 </td>
							 <td class="align-middle">
								<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="` + val.qty_left_deliver +`" required>
							 </td>
							 <td class="align-middle">
								<input type="text" name="product_shading[]" class="form-control" placeholder="Type shading here" value="` + val.shading +`" required>
							 </td>
							 <td class="align-middle">
								<button type="button" id="delete_delivery_products" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
							 </td>
						  </tr>
						`);
						no++;
					});
				}
            }else{
                loadingClose('#step-7');
				
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
            }		
         },
         error: function() {
            if(elemen == "sample_id"){
                loadingClose('#step-2');
                swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }else if(elemen == "sod_id"){
                loadingClose('#step-6');
                swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }else{
                loadingClose('#step-7');
                swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
            
          
         }
		});
     }
	}

    function getSalesInfo(element, idsample) {
		var elemen = element.getAttribute('id');
		
		$.ajax({
         url: '{{ url("admin/sales/sample/get_sales_info") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            id_sample : idsample
         },
         beforeSend: function() {
            if(elemen == 'sample_id'){
                loadingOpen('#step-2');
            }else if(elemen == "sod_id"){
                loadingOpen('#step-6');
            }
         },
         success: function(response) {
            if(elemen == 'sample_id'){
                loadingClose('#step-2');
                if(response) {
                    $('#sales_po').empty();
                    $('#customer_id').empty();
                    $('#sales_po').append(`
                        <option value="` + response.sales_id + `">` + response.sales_name + `</option>
                    `);
                    $('#customer_id').append(`
                        <option value="` + response.customer_id + `">` + response.customer_name + `</option>
                    `);
                    $('#sales_note').val(response.sales_note);
                    $('#pic_number').val(response.customer_phone);
                }
            }else if(elemen == 'sod_id'){
				loadingClose('#step-6');
				if(response) {
					$('#receiver_name').val(response.customer_name);
					$('#phone').val(response.customer_phone);
					if(response.warehouse){
						$('#warehousedeliver_id').empty();
						$('#warehousedeliver_id').append(`
							<option value="` + response.warehouse.id + `">` + response.warehouse.name  + `</option>
						`);
					}
					// $('#city_id2').empty();
					// $('#city_id2').append(`
					// 	<option value="` + response.city_id + `">` + response.city_name + `</option>
					// `);
				}
			}
         },
         error: function() {
            if(elemen == 'sample_id'){
                loadingClose('#step-2');
                swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }else if(elemen == 'sod_id'){
                loadingClose('#step-6');
                swalInit.fire('Server Error!', 'Please contact developer', 'error');
			}
         }
		});
	}

    function countTotalPurchase(element,id){

       var result = Math.round(parseFloat($(element).val().replaceAll('.','').replaceAll(',','.')) * parseFloat($('#purchaseproductqty' + id).val()) * parseFloat($('.purchaseproductdata' + id).data('m2')));
      
      
       $('#purchaseproducttotal'+id).html(formatRupiahIni(result));
       
       countAllTotal();
   }

   function getSupplierCurrency(idsupp) {
		$('#currency').empty();
		$.ajax({
         url: '{{ url("admin/sales/sample/get_supplier_currency") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            idsupp : idsupp
         },
         beforeSend: function() {
            loadingOpen('#step-2');
         },
         success: function(response) {
            loadingClose('#step-2');
				if(response.length > 0) {
					$.each(response, function(i, val) {
						$('#currency').append(`
							<option value="` + val.id + `">` + val.code + `</option>
						`);
					});
					
					$('#memo_up').val(response[0].up);
				}
         },
         error: function() {
            loadingClose('#step-2');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
    
   function createPOSupplier(id) {
      $.ajax({
         url: '{{ url("admin/sales/sample/create_po_supplier") }}' + '/' + id,
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_purchase').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert-2').hide();
            $('#validation_content-2').html('');
            loadingOpen('#step-2');
         },
         success: function(response) {
            loadingClose('#step-2');
            refreshProgress();
            refreshNavbar()
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
            }else if(response.status == 422) {
               $('#validation_alert-2').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content-2').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            }else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            $('.modal-body').scrollTop(0);
            loadingClose('#step-2');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }

   function createProofProformaSample(id) {
      var formdata = new FormData($('#form-proforma')[0]);
      $.ajax({
         url: '{{ url("admin/sales/sample/create_proforma") }}' + '/' + id,
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
            $('#validation_alert').hide();
            $('#validation_content').html('');
            loadingOpen('#step-3');
         },
         success: function(response) {
            RefreshTable();
            refreshNavbar()
            loadingClose('#step-3');
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
            }else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            $('.modal-body').scrollTop(0);
            loadingClose('#step-3');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }

   function createDeliveryShipment(id) {
      var formdata = new FormData($('#form-shipment')[0]);
      $.ajax({
         url: '{{ url("admin/sales/sample/create_delivery_shipment") }}' + '/' + id,
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
            $('#validation_alert-4').hide();
            $('#validation_content-4').html('');
            loadingOpen('#step-4');
         },
         success: function(response) {
            RefreshTable();
            refreshNavbar();
            loadingClose('#step-4');
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
            }else if(response.status == 422) {
               $('#validation_alert-4').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content-4').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            }else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            $('.modal-body').scrollTop(0);
            loadingClose('#step-4');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }

   function createWarehouseReceived(id) {
      var formdata = new FormData($('#form-warehouse')[0]);
      $.ajax({
         url: '{{ url("admin/sales/sample/create_warehouse_received") }}' + '/' + id,
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
            $('#validation_alert-5').hide();
            $('#validation_content-5').html('');
            loadingOpen('#step-5');
         },
         success: function(response) {
            loadingClose('#step-5');
            RefreshTable();
            refreshProgress();
            refreshNavbar();
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
            }else if(response.status == 422) {
               $('#validation_alert-5').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content-5').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            }else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            $('.modal-body').scrollTop(0);
            loadingClose('#step-5');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }

   function createSampleDelivery(id) {
      var formdata = new FormData($('#form-delivery')[0]);
      $.ajax({
         url: '{{ url("admin/sales/sample/create_sample_delivery") }}' + '/' + id,
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
            $('#validation_alert').hide();
            $('#validation_content').html('');
            loadingOpen('#step-6');
         },
         success: function(response) {
            loadingClose('#step-6');
            refreshNavbar();
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
               RefreshTable();
            }else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            $('.modal-body').scrollTop(0);
            loadingClose('#step-6');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }
   function createSampleReturn(id) {
      var formdata = new FormData($('#form-return')[0]);
      $.ajax({
         url: '{{ url("admin/sales/sample/create_sample_return") }}' + '/' + id,
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
            $('#validation_alert-7').hide();
            $('#validation_content-7').html('');
            loadingOpen('#step-7');
         },
         success: function(response) {
            loadingClose('#step-7');
            refreshNavbar();
            if(response.status == 200) {
               notif('success', 'bg-success', response.message);
               RefreshTable();
            }else if(response.status == 422) {
               $('#validation_alert-7').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content-7').append(`
                        <li>` + val + `</li>
                     `);
                  });
               });
            }else {
               notif('error', 'bg-danger', response.message);
            }
         },
         error: function() {
            $('.modal-body').scrollTop(0);
            loadingClose('#step-7');
            swalInit.fire({
               title: 'Server Error',
               text: 'Please contact developer',
               type: 'error'
            });
         }
      });
   }

   function getDeliveryProduct(element){
		if(element.value !== ''){
			$.ajax({
				url: '{{ url("admin/sales/sample/get_delivery_product") }}',
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
						$('#data_sample_return').empty();
						$.each(response, function(i, val) {
							$('#data_sample_return').append(`
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

   function getPurchaseProduct(elemen,idpo){
		var elemen = elemen.getAttribute('name');
		$.ajax({
         url: '{{ url("admin/sales/sample/get_purchase_product") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
			 if(elemen == 'sample_purchase_id'){
				loadingOpen('#step-4');
			 }
         },
         success: function(response) {
			 
			var no = 1;
			
			if(elemen == 'sample_purchase_id'){
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
			
			if(elemen == 'sample_purchase_id'){
				loadingClose('#step-4');
			}
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
 

   function getPurchaseInfo(element,purchaseid) {
		var elemen = element.getAttribute('name');
		$.ajax({
         url: '{{ url("admin/sales/sample/get_purchase_info") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            purchaseid : purchaseid
         },
         beforeSend: function() {
            loadingOpen('#step-3');
         },
         success: function(response) {
			loadingClose('#step-3');
			if(response) {
                $('#supplier_name').val(response.supplier_name);
            }
         },
         error: function() {
            loadingClose('#step-3');
            swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}

    function getShipmentInfo(idpo){
		$.ajax({
         url: '{{ url("admin/sales/sample/get_shipment_info") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
            loadingOpen('#step-5');
         },
         success: function(response) {
            loadingClose('#step-5');
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
            loadingClose('#step-5');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}
	function getShipmentProduct(idshipment){
		$.ajax({
         url: '{{ url("admin/sales/sample/get_shipment_product") }}',
         type: 'GET',
         dataType: 'JSON',
         data: {
            idshipment : idshipment
         },
         beforeSend: function() {
            loadingOpen('#step-5');
         },
         success: function(response) {
            loadingClose('#step-5');
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
									<button type="button" id="delete_warehouse_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
								 </td>
							</tr>
						`);
						
						no++;
					});
				}
         },
         error: function() {
            loadingClose('#step-5');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}


   function edit(id) {
      $.ajax({
         url: '{{ url("admin/sales/sample/edit") }}' + '/' + id,
         type: 'POST',
         dataType: 'JSON',
         data: $('#form_data').serialize(),
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            $('#validation_alert-1').hide();
            $('#validation_content-1').html('');
            loadingOpen('.modal-content');
         },
         success: function(response) {
            loadingClose('.modal-content');
            if(response.status == 200) {
               RefreshTable();
               notif('success', 'bg-success', response.message);
            } else if(response.status == 422) {
               $('#validation_alert-1').show();
               $('.modal-body').scrollTop(0);
               notif('warning', 'bg-warning', 'Validation');
               
               $.each(response.error, function(i, val) {
                  $.each(val, function(i, val) {
                     $('#validation_content-1').append(`
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

   function editPurchase(idpurchase){
		$('#temp_po_id').val(idpurchase);
		$('#modepurchase').html('Edit <i class="icon-loop3"></i>');
		$('#modepurchase').removeClass('btn-info');
		$('#modepurchase').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/sales/sample/get_purchase_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				purchaseid : idpurchase
			 },
			 beforeSend: function() {
				loadingOpen('#step-2');
			 },
			 success: function(response) {
				loadingClose('#step-2');
				if(response){
					$('html, body').animate({
						scrollTop: $('#step-2').offset().top
					}, 'slow');
					$('#sample_id').val(response.purchase.sample_id).trigger('change');
					$('#purchase_date').val(response.purchase.created_at.split('T')[0]);
					$('#ppn').val(response.purchase.ppn);
					$('#sales_note').val(response.purchase.note);
					$('#supplier_id').empty();
					$('#supplier_id').append(`
						<option value="` + response.purchase.supplier_id + `">` + response.purchase.supplier_name + `</option>
					`);
					$('#production_lead_time').val(response.purchase.production_lead_time);
					$('#est_delivery_date').val(response.purchase.estimated_delivery);
					$('#est_arrival_date').val(response.purchase.estimated_arrival);
					$('#factory_name').val(response.purchase.factory_name);
					$('#on_behalf').val(response.purchase.on_behalf);
					$('#delivery_address').val(response.purchase.delivery_address);
					$('#courier_method').val(response.purchase.courier_method);
					$('#country_id').empty();
					$('#country_id').append(`
						<option value="` + response.purchase.country_id + `">` + response.purchase.country_name + `</option>
					`);
					$('#city_id').empty();
					$('#city_id').append(`
						<option value="` + response.purchase.city_id + `">` + response.purchase.city_name + `</option>
					`);
					
					setTimeout(function(){
						$('#pic_name').val(response.purchase.pic);
						$('#pic_number').val(response.purchase.pic_no);
					}, 1000);
					
					$('#payment_method').val(response.purchase.payment_method);
					$('#payment_due_date').val(response.purchase.payment_due_date);
					$('#price').val(response.purchase.price);
					$('#currency').empty();
					$('#currency').append(`
						<option value="` + response.purchase.currency_id + `">` + response.purchase.currency_name + `</option>
					`);
					$('#brand').val(response.purchase.brand_on_box);
					$('#currency_rate').val(response.purchase.currency_rate);
					$('#currency_rate').trigger('keyup');
					$('#sni').val(response.purchase.sni);
					$('#is_wip').val(response.purchase.is_wip);
					$('#has_memo_item').val(response.purchase.has_memo_item).trigger('change');
					$('#memo_address_item').val(response.purchase.memo_address_item);
					$('#memo_up').val(response.purchase.memo_up);
					
					setTimeout(function(){
						$('#data_purchase').empty();
						
						$.each(response.purchaseproduct, function(i, val) {
							var totaltemp = 0;
							totaltemp = parseFloat(val.qty_left) * parseFloat(val.price.toString().replaceAll('.','').replaceAll(',','.'));
							
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
									<input type="text" name="product_price[]" class="form-control" placeholder="0" required onkeyup="formatRupiah(this);countTotalPurchase(this,'`+ val.product_id +`');convertPriceBeforeTax(this,'`+ val.product_id +`')" value="` + val.price + `">
								 </td>
								 <td class="align-middle">
									<div id="purchaseproducttotal`+ val.product_id +`" class="purchaseproducttotal">` + formatRupiahIni(totaltemp) + `</div>
								 </td>
								 <td class="align-middle">
									<textarea class="form-control" rows="1" name="product_remark[]">` + val.remark + `</textarea>
								 </td>
								 <td class="align-middle">
									<button type="button" id="delete_data_product_purchase" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
								 </td>
							  </tr>
							`);
						});
					}, 1500);
					
					countAllTotal();
					
					$('.edit-po').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-2');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

    function editShipment(id){
		$('#temp_shipment_id').val(id);
		$('#modeshipment').html('Edit <i class="icon-loop3"></i>');
		$('#modeshipment').removeClass('btn-info');
		$('#modeshipment').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/sales/sample/get_shipment_edit") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#step-4');
			 },
			 success: function(response) {
				
				if(response){
					$('#form-shipment #smpl_purchase_id').val(response.main.project_purchase_id).trigger('change');
					$('#form-shipment #shipment_code').val(response.main.shipment_code);
					$('#form-shipment #loading_date').val(response.main.loading_date);
					$('#form-shipment #departure_date').val(response.main.departure_date);
					$('#form-shipment #from_port').val(response.main.from_port);
					$('#form-shipment #to_port').val(response.main.to_port);
					$('#form-shipment #eta').val(response.main.eta);
					$('#form-shipment #delivery_method').val(response.main.delivery_method);
					$('#form-shipment #note').val(response.main.note);
					
					setTimeout(function(){
						if(response.detail.length > 0) {
							$('#data_shipment_product').empty();
							
							$.each(response.detail, function(i, val) {
								$('#data_shipment_product').append(`
									<tr class="text-center">
									 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
									 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
									 <td>` + (i + 1) + `</td>
									 <td class="align-middle">` + val.product + `</td>
									 <td class="align-middle">
										` + val.qty + `
									 </td>
									 <td class="align-middle">
										<input type="number" name="product_qty[]" class="form-control" placeholder="" value="` + val.qty +`" required>
									 </td>
									 <td class="align-middle">
										` + val.unit + `
									 </td>
									 <td class="align-middle">
										<button type="button" id="delete_ship_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									 </td>
								  </tr>
								`);
							});
							
							loadingClose('#step-4');
						}
					}, 1500);
					
					$('.edit-sh-full').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-4');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

    function editDelivery(iddelivery){
		$('#temp_delivery_id').val(iddelivery);
		$('#modedelivery').html('Edit <i class="icon-loop3"></i>');
		$('#modedelivery').removeClass('btn-info');
		$('#modedelivery').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/sales/sample/get_delivery_info") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : iddelivery
			 },
			 beforeSend: function() {
				loadingOpen('#step-6');
			 },
			 success: function(response) {
				loadingClose('#step-6');
				if(response){
					
					$('#sod_id').val(response.data.sample_id).trigger('change');
					$('#receiver_name').val(response.data.receiver_name);
					$('#delivery_date').val(response.data.delivery_date);
					$('#email').val(response.data.email);
					$('#phone').val(response.data.phone);
					$('#city_id2').empty();
					$('#city_id2').append(`
						<option value="` + response.data.city_id + `">` + response.data.city_name + `</option>
					`);
					$('#expedition_id').val(response.data.vendor_id).trigger('change');
					$('#address').val(response.data.address);
					$('#dropshipper').val(response.data.is_dropshipper).trigger('change');
					// $('#is_sales').val(response.data.is_sales);
					$('#pick_up_name').val(response.data.pick_up_name);
					$('#pick_up_plat').val(response.data.pick_up_plat);
					$('#pick_up_vehicle').val(response.data.pick_up_vehicle);
					$('#service_note').val(response.data.service_note);
					
					setTimeout(function(){
						$('#warehousedeliver_id').empty();
						$('#warehousedeliver_id').append(`
							<option value="` + response.data.warehouse_id + `">` + response.warehouse_name + `</option>
						`);
						
						$('#dropshipper_id').val(response.data.dropshipper_id);
						
						if(response.product.length > 0) {
							$('#data_delivery_products').empty();
							var no = 1;
							
							$.each(response.product, function(i, val) {
								$('#data_delivery_products').append(`
									<tr class="text-center">
									 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
									 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
									 <input type="hidden" name="product_stock[]" value="` + val.nominalstock + `">
									 <td>` + (i + 1) + `</td>
									 <td class="align-middle">` + val.product_name + `</td>
									 <td class="align-middle">
										-
									 </td>
									 <td class="align-middle">
										-
									 </td>
									 <td class="align-middle">
										-
									 </td>
									 <td class="align-middle">
										<input type="number" name="product_qty[]" class="form-control" placeholder="0" value="` + val.qty +`" required>
									 </td>
									 <td class="align-middle">
										<input type="text" name="product_shading[]" class="form-control" placeholder="Type shading here" value="` + val.shading +`" required>
									 </td>
									 <td class="align-middle">
										<button type="button" id="delete_delivery_products" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
									 </td>
								  </tr>
								`);
								no++;
							});
						}
						
					}, 1000);
					
					$('.edit-do').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-6');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}


    function returPurchase(idpo,code){
		$('#modal_title_purchase_return').html(code);
		$('#tempreturnpo').val(idpo);
		
		$('#modal_purchase_return').modal('toggle');
		
		$.ajax({
		 url: '{{ url("admin/sales/sample/get_purchase_product") }}',
		 type: 'GET',
         dataType: 'JSON',
         data: {
            idpo : idpo
         },
         beforeSend: function() {
			loadingOpen('#modal_purchase_return');
         },
         success: function(response) {
			if(response.length > 0) {
				$('#data_purchase_return').empty();
				
				$.each(response, function(i, val) {
					$('#data_purchase_return').append(`
						<tr class="text-center" data-m2="` + val.m2 + `">
						 <input type="hidden" name="return_product_id[]" value="` + val.product_id + `">
						 <input type="hidden" name="return_product_unit[]" value="` + val.convertunit + `">
						 <td class="align-middle">` + val.product_name + `</td>
						 <td class="align-middle">
							<input type="number" name="return_product_qty[]" class="form-control" placeholder="" value="` + val.qty +`" required>
						 </td>
						 <td class="align-middle">
							` + val.unit + `
						 </td>
						 <td class="align-middle">
							<button type="button" id="delete_purchase_return_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
						 </td>
					  </tr>
					`);
				});
				
			}
			
			loadingClose('#modal_purchase_return');
         },
         error: function() {
			swalInit.fire('Server Error!', 'Please contact developer', 'error');
         }
		});
	}

    function editProforma(id){
		$('#temp_proforma').val(id);
		$('#modeproforma').html('Edit <i class="icon-loop3"></i>');
		$('#modeproforma').removeClass('btn-info');
		$('#modeproforma').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/sales/sample/get_purchase_proforma") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#step-3');
			 },
			 success: function(response) {
				loadingClose('#step-3');
				if(response){
					$('#sample_purchase_id').val(response.sample_purchase_id).trigger('change');
					$('#form-proforma #date').closest('#date').val(response.date);
					$('#form-proforma #supplier_name').val(response.supplier_name);
					$('#form-proforma #supplier_warehouse').val(response.supplier_warehouse);
					$('#form-proforma #note').val(response.note);
				}
			 },
			 error: function() {
				loadingClose('#step-3');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

    function editWarehouse(id){
		$('#temp_warehouse_id').val(id);
		$('#modewarehouse').html('Edit <i class="icon-loop3"></i>');
		$('#modewarehouse').removeClass('btn-info');
		$('#modewarehouse').addClass('btn-warning');
		
		$.ajax({
			url: '{{ url("admin/sales/sample/get_warehouse_edit") }}',
			type: 'GET',
			dataType: 'JSON',
			 data: {
				id : id
			 },
			 beforeSend: function() {
				loadingOpen('#step-5');
			 },
			 success: function(response) {
				
				if(response){
					
					$('#form-warehouse #smple_purchase_id').val(response.main.sample_purchase_id).trigger('change');
					$('#form-warehouse #person').val(response.main.person);
					$('#form-warehouse #date_receive').val(response.main.date_receive);
					$('#form-warehouse #warehouse_id').empty();
					$('#form-warehouse #warehouse_id').append(`
						<option value="` + response.main.warehouse_id + `">` + response.main.warehouse_name + `</option>
					`);
					
					setTimeout(function(){
						$('#form-warehouse #shipment_id').val(response.main.shipment_id);
						
						if(response.detail.length > 0) {
							$('#data_warehouse_product').empty();
							
							$.each(response.detail, function(i, val) {
								$('#data_warehouse_product').append(`
									<tr class="text-center">
										 <input type="hidden" name="product_id[]" value="` + val.product_id + `">
										 <input type="hidden" name="product_unit[]" value="` + val.unitraw + `">
										 <td>` + (i+1) + `</td>
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
											<button type="button" id="delete_warehouse_product" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>
										 </td>
									</tr>
								`);
							});
						}
						
						loadingClose('#step-5');
					}, 1500);
					
					$('.edit-sw-full').removeClass('d-none');
				}
			 },
			 error: function() {
				loadingClose('#step-5');
				swalInit.fire('Server Error!', 'Please contact developer', 'error');
			 }
		});
	}

    function formatRupiahIni(angka){
		var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
		split   		= number_string.split(','),
		sisa     		= split[0].length % 3,
		rupiah     		= split[0].substr(0, sisa),
		ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
	 
		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
	 
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		
		return rupiah;
	}

    function countAllTotal(){
        var total = 0;
        
        $('.purchaseproducttotal').each(function() {
            total += parseFloat($(this).text().replaceAll('.','').replaceAll(',','.'));
        });
        
        $('#totalpo').text(formatRupiahIni(total.toFixed(2).toString().replace('.',',')));
    }

    function convertPriceBeforeTax(element,id){
            var sellprice = parseFloat($('#sellprice' + id).text().replaceAll('.','').replaceAll(',','.'));
        
            if($('#ppn').val() == '1'){
                var result = parseFloat($(element).val().replaceAll('.','').replaceAll(',','.')) / 1.11;
            }else{
                var result = parseFloat($(element).val().replaceAll('.','').replaceAll(',','.'));
            }
        
            $('#convertprice' + id).html(Math.round(result * 100) / 100);
            $('#profitprice' + id).html(parseFloat((result / sellprice) * 100).toFixed(2));
            
            if(parseFloat((result / sellprice) * 100) > 100){
                $('#profitprice' + id).parent().removeClass('badge-success');
                $('#profitprice' + id).parent().removeClass('badge-warning');
                $('#profitprice' + id).parent().addClass('badge-danger');
            }else if(parseFloat((result / sellprice) * 100) > 80){
                $('#profitprice' + id).parent().removeClass('badge-success');
                $('#profitprice' + id).parent().removeClass('badge-danger');
                $('#profitprice' + id).parent().addClass('badge-warning');
            }else{
                $('#profitprice' + id).parent().addClass('badge-success');
                $('#profitprice' + id).parent().removeClass('badge-warning');
                $('#profitprice' + id).parent().removeClass('badge-danger');
            }
    }

    function showPurchaseProduct(element,idpo){
		$.ajax({
         url: '{{ url("admin/sales/sample/get_purchase_product") }}',
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

    function saveProjectReturnMemo(){
		$.ajax({
			 url: '{{ url("admin/sales/sample/add_return_memo") }}',
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

    function approveSample(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/sales/sample/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { 
            approvalKe: approvalKe,
            id: id,
            mode : 'sample'
        },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#table_data_sample');
         },
         success: function(response) {
			 if(response.status == '200'){
                RefreshTable();
                loadingClose('#table_data_sample');
			 }
         }
      });
      return false;
   }

    function approvePurchase(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/sales/sample/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { 
            approvalKe:approvalKe,
            id:id,
            mode:"purchase" 
        },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-2');
         },
         success: function(response) {
			 if(response.status == '200'){
                loadingClose('#step-2');
			 }
         }
      });
	  return false;
    }

    function approveDelivery(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/sales/sample/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { 
            approvalKe:approvalKe,
            id:id,
            mode:"delivery" 
        },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-6');
         },
         success: function(response) {
			 if(response.status == '200'){
                refreshProgress();
                loadingClose('#step-6');
			 }
         }
      });
	  return false;
    }
    function approveReturn(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/sales/sample/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { 
            approvalKe:approvalKe,
            id:id,
            mode:"return" 
        },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-7');
         },
         success: function(response) {
			 if(response.status == '200'){
                loadingClose('#step-7');
			 }
         }
      });
	  return false;
    }
    function approvePurchaseReturn(approvalKe,id){
	   $.ajax({
         url: '{{ url("admin/sales/sample/approval") }}',
         type: 'POST',
         dataType: 'JSON',
         data: { 
            approvalKe:approvalKe,
            id:id,
            mode:"purchase_return" 
        },
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function() {
            loadingOpen('#step-7');
         },
         success: function(response) {
			 if(response.status == '200'){
                loadingClose('#step-7');
			 }
         }
      });
	  return false;
    }

    function uploadNewReturn(id,oldImage){
		$('#modal_return').modal('toggle');
		$('#id_return').val(id);
		var image_holder = $("#previewImgFileReturn");
		image_holder.empty();
		$("<img />", {
			"src": oldImage,
			"class": "thumb-image",
			"width": "300px"
		}).appendTo(image_holder);
	}

    function deleteDelivery(id){
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
					url: '{{ url("admin/sales/sample/delete_delivery") }}',
					type: 'POST',
					dataType: 'JSON',
					 data: {
						id : id
					 },
					 headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					 },
					 beforeSend: function() {
						loadingOpen('#step-16');
					 },
					 success: function(response) {
						loadingClose('#step-16');
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
						loadingClose('#step-9');
						swalInit.fire('Server Error!', 'Please contact developer', 'error');
					 }
				});
            })
         ]
      }).show();
	  
	  return false;
	}

    </script>