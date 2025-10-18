<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
        }

        th {
            font-size: 12px;
        }

        .invoice-box {
            font-size: 16px;
            font-family: 'Lato', sans-serif;
            color: #555;
            page-break-after: always;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            /* text-align: right; */
        }

        .invoice-box table tr.top table td {
            padding-bottom: 0px;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 0px;
        }

        .invoice-box table tr.heading td {
            background: #cf9604;
            border-bottom: 1px solid #cf9604;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 0px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        @media print {
            @page {
                size: A4 portrait;
            }

            table {
                width: 100%;
            }
        }

        .invoice-box.rtl {
            direction: rtl;
            font-family: 'Lato', sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }


        @page {
            margin: 1cm;
        }

        body {
            margin: 1cm;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="text-align:center;">
                                <img src="{{ url('website/logo_al_rev_3.jpg') }}" width="auto" height="100px">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;">
                                <h2>
                                    <b>
                                        {!! $title.' - '.date('F Y', strtotime($filter)) !!}
                                    </b>
                                </h2>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
            <thead class="bg-dark">
                <tr align="center">
                    <th colspan="9">
                        <h1>Pending/ Unfinished SO Last Month</h1>
                    </th>
                </tr>
                <tr class="text-center">
                    <th>No</th>
                    <th>No. PRJ</th>
                    <th>No. SO</th>
                    <th>Date</th>
                    <th>Sales</th>
                    <th>Customer</th>
                    <th>Project Name</th>
                    <th>Amount (B4 Tax)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalbefore = 0;
                    $nomor = 1;
                @endphp
                @foreach($datasalesbefore as $val)
                    @php
                    $adadata = false;
                    
                    if($val->sales->branch == $branch){
                        $adadata = true;
                    }
                    
                    if($adadata == true){
                        if($val->is_closed && substr($val->date_closed,0,7) <= $filter){
                            
                        }else{
                            $totalbefore += $val->total_sisa;
                        }
                    @endphp
                    <tr class="{{ $val->is_closed && substr($val->date_closed,0,7) <= $filter ? 'bg-danger' : '' }}">
                        <td>{{ $nomor }}</td>
                        <td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
                        <td>{{ $val->code }}</td>
                        <td>{{ date('d M Y',strtotime($val->created_at)) }}</td>

                        <td>{{ $val->sales->name }}</td>
                        <td>{{ $val->project->customer->name }}</td>
                        <td>{{ $val->project->name }}</td>
                        <td class="text-right">
                            Rp@php
                                echo number_format($val->total_sisa,0,',','.');
                            @endphp
                        </td>
                    </tr>
                @php 
                        $nomor++; 
                    }
                @endphp
                @endforeach
              </tbody>
              <tfoot>
                <tr class="bg-teal">
                    <th colspan="7" class="text-right">Total</th>
                    <th colspan="2" class="text-right">Rp{{ number_format($totalbefore,0,',','.') }}</th>
                </tr>
              </tfoot>
        </table>
        <br><br>
        <table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
            <thead class="bg-dark">
                <tr align="center">
                    <th colspan="9">
                        <h1>Close/ Cancel SO</h1>
                    </th>
                </tr>
                <tr class="text-center">
                    <th>No</th>
                    <th>No. PRJ</th>
                    <th>No. SO</th>
                    <th>Date</th>
                    <th>Sales</th>
                    <th>Customer</th>
                    <th>Project Name</th>
                    <th>Amount (B4 Tax)</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                $nomor = 1;
                $totalDelivery = 0;
                $totalReturn = 0;
                @endphp
                @foreach($closeso as $val)
                @php
                $adadata = false;
                @endphp
                @if($val->sales->branch == $branch)
                @php
                $adadata = true;
                @endphp
                @endif

                @if($adadata == true)
                @php
                foreach($val->projectDelivery()->where('is_sales','1')->whereRaw('received_date < "'.$filter.'-01"')->get() as $pd){
						$totalDelivery += $pd->subtotal_product + $pd->subtotal_service;
					}

                foreach($val->projectSaleReturn()->whereRaw('date_return < "'.$filter.'-01"')->get() as $pr){
                    $totalReturn += $pr->getTotalRawNew();
                }

                $sisa = round($val->subtotal_product + $val->subtotal_service + $totalReturn - $totalDelivery,0);
                @endphp
                <tr>
                    <td>{{ $nomor }}</td>
                    <td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) :
                        str_replace('PJ','PJN',$val->project->code) }}</td>
                    <td>{{ $val->code }}</td>
                    <td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
                    <td>{{ $val->sales->name }}</td>
                    <td>{{ $val->project->customer->name }}</td>
                    <td>{{ $val->project->name }}</td>
                    <td class="text-right">
                        Rp
                        @php
                        echo $sisa;
                        @endphp
                    </td>
                </tr>
                @endif
                @php
                $nomor++;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-teal">
                    <th colspan="7" class="text-right">Total</th>
                    <th colspan="2" class="text-right">Rp{{ number_format($total,0,',','.') }}</th>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
            <thead class="bg-dark">
                <tr align="center">
                    <th colspan="9">
                        <h1>Sales Order</h1>
                    </th>
                </tr>
                <tr class="text-center">
                    <th>No</th>
                    <th>No. PRJ</th>
                    <th>No. SO</th>
                    <th>Date</th>
                    <th>Sales</th>
                    <th>Customer</th>
                    <th>Project Name</th>
                    <th>Amount (B4 Tax)</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                $nomor = 1;
                @endphp
                @foreach($projectsale as $val)
                @php
                $adadata = false;
                @endphp
                @if($val->sales->branch == $branch)
                @php
                $adadata = true;
                @endphp
                @endif

                @if($adadata == true)
                @php
                $total += str_replace(',','.',str_replace('.','',$val->getTotalRawPlusService()));
                @endphp
                <tr>
                    <td>{{ $nomor }}</td>
                    <td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) :
                        str_replace('PJ','PJN',$val->project->code) }}</td>
                    <td>{{ $val->code }}</td>
                    <td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
                    <td>{{ $val->sales->name }}</td>
                    <td>{{ $val->project->customer->name }}</td>
                    <td>{{ $val->project->name }}</td>
                    <td class="text-right">
                        Rp
                        @php
                        echo $val->getTotalRawPlusService();
                        @endphp
                    </td>
                </tr>
                @endif
                @php
                $nomor++;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-teal">
                    <th colspan="7" class="text-right">Total</th>
                    <th colspan="2" class="text-right">Rp{{ number_format($total,0,',','.') }}</th>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
            <thead class="bg-dark">
                <tr align="center">
                    <th colspan="10">
                        <h1>Delivery</h1>
                    </th>
                </tr>
                <tr class="text-center">
                    <th>No</th>
                    <th>No. PRJ</th>
                    <th>No. SO</th>
                    <th>No. INV</th>
                    <th>Date</th>
                    <th>Sales</th>
                    <th>Customer</th>
                    <th>Project Name</th>
                    <th>Amount (B4 Tax)</th>
                </tr>
            </thead>
            <tbody>
                @php
                $totalpaid = 0;
                $totalraw = 0;
                $totalservice = 0;
                $nomor = 1;
                $totalallreturn = 0;
                @endphp
                @foreach($projectpaid as $val)
                @php
                $adadata = false;
                @endphp
                @if($val->projectSale->sales->branch == $branch)
                @php
                $adadata = true;
                @endphp
                @endif

                @if($adadata == true)
                @php
                $totalpaid += $val->subtotal_product + $val->subtotal_service;
                $totalraw += $val->subtotal_product;
                $totalservice += $val->subtotal_service;
                $totalreturn = 0;
                @endphp
                <tr>
                    <td>{{ $nomor }}</td>
                    <td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) :
                        str_replace('PJ','PJN',$val->project->code) }}</td>
                    <td>{{ $val->projectSale->code }}</td>
                    <td>{{ $val->proforma_code }}</td>
                    <td>{{ date('d M Y',strtotime($val->received_date)) }}</td>
                    <td>{{ $val->projectSale->sales->name }}</td>
                    <td>{{ $val->project->customer->name }}</td>
                    <td>{{ $val->project->name }}</td>
                    <td class="text-right">
                        Rp{{ number_format($val->subtotal_product + $val->subtotal_service,0,',','.') }}
                    </td>
                </tr>
                @endif
                @php
                $nomor++;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-teal">
                    <th colspan="8" class="text-right">Total</th>
                    <th colspan="2" class="text-right">Rp{{ number_format($totalpaid,0,',','.') }}</th>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
            <thead class="bg-dark">
                <tr align="center">
                    <th colspan="9">
                        <h1>Sales Return</h1>
                    </th>
                </tr>
                <tr class="text-center">
                    <th>No</th>
                    <th>No. PJ</th>
                    <th>No. Retur</th>
                    <th>Date</th>
                    <th>Sales</th>
                    <th>Customer</th>
                    <th>Project Name</th>
                    <th>Note</th>
                    <th>Amount (B4 Tax)</th>
                </tr>
            </thead>
            <tbody>
                @php
                $nomor = 1;
                $totalallreturn = 0;
                @endphp
                @foreach($projectsalereturn as $val)
                @php

                $adadata = false;
                @endphp

                @if($val->projectSale->sales->branch == $branch)
                @php
                $adadata = true;
                @endphp
                @endif

                @if($adadata == true){

                @if(date('Y-m-d',strtotime($val->projectSale->created_at)) < '2022-04-01' ){ @php $ppnpembagi=1.1;
                    @endphp @else @php $ppnpembagi=1.11; @endphp @endif @php $totalreturn=0; @endphp @if($val->
                    project->ppn == '1')
                    @php
                    $totalreturn = $val->grandtotal / $ppnpembagi;
                    $totalallreturn += $val->grandtotal / $ppnpembagi;
                    @endphp
                    @else
                    @php
                    $totalreturn = $val->grandtotal;
                    $totalallreturn += $val->grandtotal;
                    @endphp
                    @endif
                    <tr>
                        <td>{{ $nomor }}</td>
                        <td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) :
                            str_replace('PJ','PJN',$val->project->code) }}</td>
                        <td>{{ $val->code }}</td>
                        <td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
                        <td>{{ $val->projectSale->sales->name }}</td>
                        <td>{{ $val->project->customer->name }}</td>
                        <td>{{ $val->project->name }}</td>
                        <td>{{ $val->note }}</td>
                        <td class="text-right">
                            Rp{{ number_format($totalreturn,0,',','.') }}
                        </td>
                    </tr>
                    @endif
                    @php
                    $nomor++;
                    @endphp
                    @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-teal">
                    <th colspan="7" class="text-right">Total</th>
                    <th colspan="2" class="text-right">Rp{{ number_format($totalallreturn,0,',','.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>