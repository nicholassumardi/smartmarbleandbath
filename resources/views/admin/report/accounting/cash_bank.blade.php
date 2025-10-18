<style>
	.table-bordered td, .table-bordered th { border: 1px solid #818181; }
</style>
<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Cash & Bank</span>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Report</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
                    <span class="breadcrumb-item active">Cash & Bank</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <form method="GET" id="form_filter">
                    @csrf
                    <div class="form-group">
                        <center class="d-block">
                            <div class="row justify-content-center">
								<div class="col-md-3">
									<label>Branch</label>
									<select name="filter_branch" id="filter_branch" class="form-control" onchange="submitFilter()">
                                        @foreach (DB::table('company_entities')->get() as $company)
                                            <option value="{{$company->id}}" {{ $branch == $company->id ? 'selected' : ''  }}>{{$company->name}}</option>
                                        @endforeach
									</select>
								</div>
                                <div class="col-md-3">
									<label>Month Year</label>
                                    <input type="month" name="filter_month" id="filter_month" class="form-control" style="height:36px;" value="{{ $month }}" onchange="submitFilter()">
                                </div>
                                <div class="col-md-3">
									<label>List of COAs</label>
                                    <select name="filter_coa_id" id="filter_coa_id" class="select2" onchange="submitFilter()"  style="height:36px;">
                                        <option value="">All Coa</option>
                                        @foreach($coa as $c)
                                            <option value="{{ $c->id }}" {{ $coa_id == $c->id ? 'selected' : '' }}>[{{ $c->code }}] {{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(date('Y-m') != $month)
                                    <div class="col-md-1">
                                        <a href="{{ url('admin/report/accounting/cash_bank') }}" class="btn bg-danger" style="height:35px !important;">Reset</a>
                                    </div>
                                @endif
                            </div>
                        </center>
                    </div>
                </form>
                <div class="form-group"><hr></div>
                <div class="text-center mb-4">
                    <h4 class="font-weight-bold text-uppercase">List Account Cash & Bank</h4>
                    <h6 class="text-muted text-uppercase text-center font-weight-bold">Periode {{ date('F Y', strtotime($month)) }}</h6>
                </div>
                <div class="table-responsive">
					
                    <table class="table table-hover table-bordered">
                        <thead class="table-secondary">
                            <tr class="text-center">
                                <th>Account No</th>
                                <th>Account Name</th>
                                <th>Increase</th>
                                <th>Decrease</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
							@php
								$totaldebit = 0;
								$totalcredit = 0;
								$totalbalance = 0;
							@endphp
                            @foreach($result as $r)
                                <tr>
                                    <td class="align-middle text-left">{{ $r['no'] }}</td>
                                    <td class="align-middle text-left">{{ $r['name'] }}</td>
                                    <td class="align-middle text-right">{{ number_format($r['debit'], 2, ',', '.') }}</td>
                                    <td class="align-middle text-right">{{ number_format($r['credit'], 2, ',', '.') }}</td>
                                    <td class="align-middle text-right">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_detail" onclick="showDetail({{ $r['id'] }}, {{ $r['balance'] }}, {{ $r['branch'] }});">{{ number_format($r['balance'], 2, ',', '.') }}</a>
                                    </td>
                                </tr>
								@php
									$totaldebit += $r['debit'];
									$totalcredit += $r['credit'];
									$totalbalance += $r['balance'];
								@endphp
                            @endforeach
                        </tbody>
						<thead class="table-secondary">
                            <tr class="text-center">
                                <th colspan="2" class="text-right">Total</th>
                                <th>{{ number_format($totaldebit, 2, ',', '.') }}</th>
                                <th>{{ number_format($totalcredit, 2, ',', '.') }}</th>
                                <th>{{ number_format($totalbalance, 2, ',', '.') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="modal_detail" data-backdrop="static" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="exampleModalLabel">Detail Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                        <a href="#transaction" class="nav-link active" data-toggle="tab"><i class="icon-cart5 mr-2"></i> Transaction</a>
                    </li>
                    <li class="nav-item">
                        <a href="#checking_account" class="nav-link" data-toggle="tab"><i class="icon-newspaper mr-2"></i> Checking Account</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="transaction">
                        <div class="alert bg-teal">
                            <table class="table w-100">
                                <tbody>
                                    <tr class="text-white">
                                        <td class="align-middle text-left" width="25%">
                                            <div>Year : <span id="data_detail_year"></span></div>
                                            <div>Month : <span id="data_detail_month"></span></div>
                                        </td>
                                        <td class="align-middle text-center" width="50%">
                                            <h4 class="text-uppercase" id="data_detail_account_name"></h4>
                                            <h6 class="text-uppercase" id="data_detail_account_no"></h6>
                                        </td>
                                        <td class="align-middle text-right" width="25%">
                                            <div>Total Transaction : <span id="data_detail_total_transaction"></span></div>
                                            <div>Balance : <span id="data_detail_balance"></span></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Income</th>
                                        <th>Expense</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="data_detail"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="checking_account">
                        <form id="form_data">
                            <input type="hidden" name="coa_id" id="coa_id_upload">
                            <input type="hidden" name="balance" id="balance_upload">
                            <input type="hidden" name="month" id="month_upload">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" id="image" name="image" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <button type="button" class="btn btn-success col-12" id="btn_upload" onclick="uploadFile()" style="height:42px;">Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="alert alert-warning text-center font-weight-bold" id="image_not_upload">Image not uploaded</div>
                                <div id="image_uploaded">
                                    <div class="form-group">
                                        <div class="text-center">
                                            <a href="" id="preview_image" data-lightbox="Image" data-title="">
                                                <img src="" class="img-fluid img-thumbnail" style="max-width:500px;">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
            </div>
        </div>
    </div>
</div>

    <script>
        function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }

        function showDetail(id, balance) {
            $.ajax({
                url: '{{ url("admin/report/accounting/cash_bank/detail") }}',
                type: 'POST',
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    month: $('#filter_month').val(),
                    balance: balance
                },
                beforeSend: function() {
                    loadingOpen('.modal-content');
                    $('#data_detail').html('');
                    $('#form_data').trigger('reset');
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    $('#data_detail_year').text(response.year);
                    $('#data_detail_month').text(response.month);
                    $('#data_detail_account_name').text(response.name);
                    $('#data_detail_account_no').text(response.code);
                    $('#data_detail_total_transaction').text(response.total_transaction);
                    $('#data_detail_balance').text(response.balance);
                    $('#coa_id_upload').val(id);
                    $('#balance_upload').val(balance);
                    $('#month_upload').val($('#filter_month').val());

                    if(response.image) {
                        $('#image_not_upload').hide();
                        $('#preview_image').attr('href', response.image);
                        $('#preview_image').data('title', response.name);
                        $('#preview_image img').attr('src', response.image);
                        $('#image_uploaded').show();
                    } else {
                        $('#image_not_upload').show();
                        $('#image_uploaded').hide();
                    }

                    $.each(response.result, function(i, val) {
                        $('#data_detail').append(`
                            <tr>
                                <td class="align-middle text-center">` + val.date + `</td>
                                <td class="align-middle text-center">` + val.code + `</td>
                                <td class="align-middle text-center">` + val.description+ `</td>
                                <td class="align-middle text-right">` + val.income + `</td>
                                <td class="align-middle text-right">` + val.expense + `</td>
                                <td class="align-middle text-right">` + val.balance + `</td>
                            </tr>
                        `);
                    });
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
        }

        function uploadFile() {
            $.ajax({
                url: '{{ url("admin/report/accounting/cash_bank/upload_file") }}',
                type: 'POST',
                dataType: 'JSON',
                data: new FormData($('#form_data')[0]),
                contentType: false,
                processData: false,
                cache: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    loadingOpen('.modal-content');
                },
                success: function(response) {
                    loadingClose('.modal-content');
                    if(response.status == 200) {
                        showDetail($('#coa_id_upload').val(), $('#balance_upload').val());
                    } else {
                        swalInit.fire({
                            title: 'Failed Upload',
                            text: 'Please contact developer',
                            type: 'error'
                        });
                    }
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
        }
    </script>
