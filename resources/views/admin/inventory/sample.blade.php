<form action="{{ url()->full() }}" method="POST">
    @csrf
    <div class="card-body">
        <h3 class="card-title" id="scrollspy"><b><span class="badge badge-danger">5</span> Form Sample</b></h3>
        <div class="form-group">
            <hr>
        </div>
        @if(isset($_GET['step-5']))
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
                    <textarea name="sample_note" id="sample_note" class="form-control" placeholder="Enter note"
                        rows="1"></textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
            <hr>
        </div>
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
                    <button type="button" onclick="addSample()" class="btn bg-success col-3"><i class="icon-plus2"></i>
                        Add</button>
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
                <a href="javascript:void(0);" class="btn bg-primary" onclick="skipForm(5,{{ $project->id }});">Skip <i
                        class="icon-forward2"></i></a>
                &nbsp;
                <button type="submit" name="submit" value="step-5" class="btn bg-purple">Save & Next <i
                        class="icon-square-right"></i></button>
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
            <h5><b><span class="badge badge-danger">5.b</span> Sample-to-give Details</b></h5>
            <div class="alert alert-info alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                <span class="font-weight-semibold">Attention!</span> Document approval must be at least 1 for each
                document.<br>Status sample sent can be changed by change status option for each sample.
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->projectSample as $ps)
                        <tr class="text-center">
                            <td class="align-middle">{{ $ps->code }}</td>
                            <td class="align-middle">{{ date('d M Y',strtotime($ps->sent_date)).' to '.date('d M
                                Y',strtotime($ps->return_date)) }}</td>
                            <td class="align-middle">{{ $ps->note }}</td>
                            <td class="align-middle">
                                @php
                                if(isset($ps->approved_1->name)){
                                echo $ps->approved_1->name;
                                }else{
                                echo '<button type="button" class="btn btn-primary btn-icon"
                                    onclick="approveSample(1,'.$ps->id.')"><i class="icon-checkmark2"></i></button>';
                                }
                                @endphp
                            </td>
                            <td class="align-middle">
                                @php
                                if(isset($ps->approved_2->name)){
                                echo $ps->approved_2->name;
                                }else{
                                echo '<button type="button" class="btn btn-primary btn-icon"
                                    onclick="approveSample(2,'.$ps->id.')"><i class="icon-checkmark2"></i></button>';
                                }
                                @endphp
                            </td>
                            <td>
                                <select class="custom-select status-sample"
                                    onchange="updateStatusSample(this.value,{{ $ps->id }});">
                                    <option value="1" {{ $ps->status == '1' ? 'selected' : '' }}>Borrowed</option>
                                    <option value="2" {{ $ps->status == '2' ? 'selected' : '' }}>Returned</option>
                                    <option value="3" {{ $ps->status == '3' ? 'selected' : '' }}>No need to return
                                    </option>
                                </select>
                            </td>
                            <td>
                                <a onclick="openLink('{{ url('admin/sales/project/print/sample_order/'. base64_encode($ps->id)) }}')"
                                    href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
                            </td>
                            <td>
                                @if($ps->return_proof)
                                <a onclick="openLink('{{ $ps->attachment() }}')" href="javascript:void(0);"
                                    class="btn bg-info"><i class="icon-file-pdf"></i></a>
                                @else
                                <a class="btn bg-info" href="javascript:void(0);"
                                    onclick="addSampleProof({{ $ps->id }},'{{ $ps->code}} ')"><i
                                        class="icon-file-check2"></i></a>
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