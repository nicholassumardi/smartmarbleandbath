<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">A/R {{$customer_name}}</span>
                </h4>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn bg-purple-400" onclick="showARCardsRealCustomer()">
                    <i class="icon-file-pdf"></i>
                </button>
                <a href="{{ url('admin/report/accounting/ar_customer') }}"
                    class="btn bg-secondary btn-labeled btn-labeled-left ml-2">
                    <b><i class="icon-arrow-left7"></i></b> Back To All
                </a>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Report</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
                    <span class="breadcrumb-item active">Outstanding A/R</span>
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
                            Periode <br> {{ $filter_start && $filter_finish ? date('d M Y', strtotime($filter_start)).'
                            To '.date('d M Y', strtotime($filter_finish)) : ($filter_start && !$filter_finish ? date('d
                            M Y', strtotime($filter_start)).' To '.date('d M Y') : date('F Y', strtotime($filter))) }}
                        </h6>
                        <form method="GET" id="form_filter">
                            @csrf
                            <div class="form-group">
                                <center class="d-block">
                                    <div class="row justify-content-center no-gutters">
                                        <div class="col-md-6 mode1">
                                            <label>Date :</label>
                                            <div class="input-group">
                                                <input type="date" name="start_date" id="start_date"
                                                    class="form-control" value="{{$filter_start}}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">To</span>
                                                </div>
                                                <input type="date" name="finish_date" id="finish_date"
                                                    class="form-control" value="{{$filter_finish}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center mt-3">
                                        <div class="col-md-3">
                                            <button class="btn bg-success btn-sm mr-3" onclick="submitFilter()"><i
                                                    class="icon-search4"></i> Process</button>
                                            <a onclick="reset()" class="btn bg-danger btn-sm"><i class="icon-reset"></i>
                                                Reset</a>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr align="center">
                                    <th colspan="13">
                                        <h1>AR CARD</h1>
                                    </th>
                                </tr>
                                <tr align="center">
                                    <th>DESCRIPTION</th>
                                    <th>DATE</th>
                                    <th>DEBIT</th>
                                    <th>CREDIT</th>
                                    <th>BALANCE</th>
                                    <th>NOTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_debit = 0;
                                $total_customer_deposit = 0;
                                $total_credit = 0;
                                $balance_each_transaction = 0;
                                @endphp
                                @foreach($arrayResult as $row)
                                @if ($row['type'] == 'debit')
                                @php
                                $balance_each_transaction += $row['nominal'];
                                @endphp
                                <tr align="center">
                                    @if ($row['mode'] == 'project_pays')
                                    <td><b><a href="javascript:void(0);"
                                        onclick="showDetailPayment(this, {{$row['child_id']}}, '{{$row['mode']}}');">{{$row['description']}}</a></b>
                                     </td>
                                    @else
                                    <td><b><a href="javascript:void(0);"
                                        onclick="showDetail(this, {{$row['child_id']}}, '{{$row['mode']}}');">{{$row['description']}}</a></b>
                                     </td>
                                    @endif
                                  
                                    <td>{{ date('d M Y', strtotime($row['date'])) }}</td>
                                    <td><b>{{number_format( $row['nominal'],2,',','.') }}</b></td>
                                    <td><b> </b></td>
                                    <td align="right"><b>{{number_format( $balance_each_transaction,2,',','.') }}</b>
                                    </td>
                                    <td><b>{{isset($row['note']) ? $row['note'] : ''}}</b></td>
                                </tr>
                                @php
                                $total_debit += $row['nominal'];
                                @endphp
                                @elseif($row['type'] == 'customer_deposit')
                                @php
                                $balance_each_transaction -= $row['nominal'];
                                @endphp
                                <tr align="center">
                                    <td><b><a href="javascript:void(0);" onclick="">{{$row['description']}}</a></b></td>
                                    <td>{{ date('d M Y', strtotime($row['date'])) }}</td>
                                    <td><b> </b></td>
                                    <td><b>{{number_format( $row['nominal'],2,',','.') }}</b></td>
                                    <td align="right"><b>{{number_format( $balance_each_transaction,2,',','.') }}</b>
                                    </td>
                                    <td><b>{{$row['note']}}</b></td>
                                </tr>
                                @php
                                $total_customer_deposit += $row['nominal'];
                                $total_credit += $row['nominal'];
                                @endphp
                                @else
                                @php
                                $balance_each_transaction -= $row['nominal'];
                                @endphp
                                <tr align="center">
                                    <td><b><a href="javascript:void(0);"
                                                onclick="showDetailPayment(this, {{$row['child_id']}}, '{{$row['mode']}}');">{{$row['description']}}</a></b>
                                    </td>
                                    <td>{{ date('d M Y', strtotime($row['date'])) }}</td>
                                    <td><b> </b></td>
                                    <td><b>{{number_format( $row['nominal'],2,',','.') }}</b></td>
                                    <td align="right"><b>{{number_format( $balance_each_transaction,2,',','.') }}</b>
                                    </td>
                                    <td></td>
                                </tr>
                                @php
                                $total_credit += $row['nominal'];
                                @endphp
                                @endif
                                @endforeach
                                @php
                                $balance = $total_debit- $total_credit;
                                @endphp
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- MODAL --}}
    <div id="modal_detail" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width: 800px !important;">
                <div class="modal-header bg-info">
                    <h6 class="modal-title">Detail <b id="modal_title">A</b></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h5 class="card-title">
                        <b>Detail Products</b>
                        <a href="javascript:void(0);" target="_blank" id="show_invoice_other"
                            class="btn btn-primary btn-sm float-right ml-1"><i class="icon-file-pdf"
                                title="Invoice Other"></i></a>
                        <a href="javascript:void(0);" target="_blank" id="show_invoice"
                            class="btn btn-info btn-sm float-right ml-1"><i class="icon-file-pdf"
                                title="Invoice Product"></i></a>
                        <a href="javascript:void(0);" target="_blank" id="show_letter_way"
                            class="btn btn-warning btn-sm float-right ml-1"><i class="icon-file-pdf"
                                title="Surat Jalan"></i></a>
                        <div id="show_retur"></div>
                    </h5>
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
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody id="data_product_detail">

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

    <div id="modal_detail_payment" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="max-width: 800px !important;">
                <div class="modal-header bg-info">
                    <h6 class="modal-title">Detail <b id="modal_title_payment">A</b></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h5 class="card-title">
                        <b>Detail Payments</b>
                    </h5>
                    <div class="form-group">
                        <hr>
                    </div>
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-secondary">
                                    <tr class="text-center">
                                        <th>Nominal Payment</th>
                                        <th>Bank</th>
                                        <th>Proof</th>
                                    </tr>
                                </thead>
                                <tbody id="data_payment_detail">

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
			$("html, body").animate({
				scrollTop: $(
				  'html, body').get(0).scrollHeight
			}, 500);
		});
		
        function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }

        function reset(){
            $('#start_date').val();
            $('#finish_date').val();
            
            window.location.href = window.location.href.replace(window.location.search,'');
        }

        function showDetail(element, id, mode){
            $.ajax({
            url: '{{ url("admin/report/accounting/ar_customer/get_detail_product") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                id : id,
                mode : mode,
            },
            beforeSend: function() {
                loadingOpen('.order-2');
            },
            success: function(data) {
                $('#modal_title').html(element.innerHTML);

                if(data.response.length > 0) {
                    $('#data_product_detail').empty();
                    $('#show_retur').empty();
                    $('#show_letter_way').removeAttr('hidden');
                    $('#show_invoice').removeAttr('hidden');
                    $('#show_invoice_other').removeAttr('hidden');

                    $('#show_letter_way').attr("href", data.letter_way)
                    $('#show_invoice').attr("href", data.invoice)
                    $('#show_invoice_other').attr("href", data.invoice_other)
                    
                    var no = 1;
                    
                    $.each(data.response, function(i, val) {
                        $('#data_product_detail').append(`
                            <tr class="text-center">
                            <td>` + no + `</td>
                            <td class="align-middle">` + val.product_name + `</td>
                            <td class="align-middle">
                                ` + val.qty + `
                            </td>
                            <td class="align-middle">
                                Box
                            </td>
                            <td class="align-middle">
                                ` + val.price + `
                            </td>
                        </tr>
                        `);
                        
                        no++;
                    });
                    
                    $('#modal_detail').modal('toggle');
                    
                    loadingClose('.order-2');
                }
            },
            error: function() {
                swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
            });
        }


        function showDetailPayment(element, id, mode){
            $.ajax({
            url: '{{ url("admin/report/accounting/ar_customer/get_detail_payment") }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                id : id,
                mode : mode,
            },
            beforeSend: function() {
                loadingOpen('.order-2');
            },
            success: function(response) {

                if(mode == 'project_sale_returns'){
                    $('#modal_title').html(element.innerHTML);
                        $('#data_product_detail').empty();
                        $('#show_retur').empty();
                        $('#show_letter_way').attr("hidden",true);
                        $('#show_invoice').attr("hidden",true);
                        $('#show_invoice_other').attr("hidden",true);


                        
                        $('#show_retur').append(`<a data-magnify="gallery" data-src="" data-group="a" href="`+response.proof+`"><img src="`+response.proof+`" style="max-width:70px;" class="img-fluid img-thumbnail"></a>`);
  
                        
                        var no = 1;
                        
                        $.each(response.data, function(i, val) {
                            $('#data_product_detail').append(`
                                <tr class="text-center">
                                <td>` + no + `</td>
                                <td class="align-middle">` + val.product_name + `</td>
                                <td class="align-middle">
                                    ` + val.qty + `
                                </td>
                                <td class="align-middle">
                                    Box
                                </td>
                                <td class="align-middle">
                                    ` + val.price + `
                                </td>
                            </tr>
                            `);
                            
                            no++;
                        });

                        $('#data_product_detail').append(`
                        <tr class="text-center">
                            <td colspan="2"></td>
                            <td></td>
                            <td>Total</td>
                            <td>`+response.nominal+`</td>
					    </tr>
                        `);

                        $('#modal_detail').modal('toggle');
                        
                        loadingClose('.order-2');

                }else{
                    $('#modal_title_payment').html(element.innerHTML);
                    $('#data_payment_detail').empty();
                    $('#data_payment_detail').append(`
                        <tr class="text-center">
                        <td class="align-middle">` + response.nominal + `</td>
                        <td class="align-middle">` + response.coa_name + `</td>
                        <td class="align-middle">
                            <a data-magnify="gallery" data-src="" data-group="a" href="`+response.proof+`"><img src="`+response.proof+`" style="max-width:70px;" class="img-fluid img-thumbnail"></a>
                        </td>
                    </tr>
                    `);
                    
                    $('#modal_detail_payment').modal('toggle');
                    
                    loadingClose('.order-2');
                } 
            },
            error: function() {
                swalInit.fire('Server Error!', 'Please contact developer', 'error');
            }
            });
        }


        function showARCardsRealCustomer(){
			$.ajax({
				type : "POST",
				url  : "{{ url('admin/report/accounting/aging_receivable/card') }}",
				data : {
					id : {{$customer_id}},
					branch : {{$branch}},
					start_date : $('#start_date').val(),
					finish_date : $('#finish_date').val()
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
		}
		
    </script>