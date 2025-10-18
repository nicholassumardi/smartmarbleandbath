@php
use App\Models\Transfer;
use App\Models\CashBank;
use App\Models\PurchaseRequest;
use App\Models\PurchaseCost;
@endphp
<table id="datatable_serverside" class="table table-bordered table-striped w-100">
    <thead class="bg-dark">
        <tr class="text-center">
            <th bgcolor="#808080"><b>No</b></th>
            <th bgcolor="#808080"><b>Description</b></th>
            <th bgcolor="#808080"><b>Debit</b></th>
            <th bgcolor="#808080"><b>Credit</b></th>
            <th bgcolor="#808080"><b>Balance</b></th>
        </tr>
    </thead>
    <tbody>
        @php
        $totalprojectsample = 0;
        $totalproject = 0;
        $balance = 0;
        $filter = $filter ? $filter : $filter_date;
        $no = 0;
        @endphp
        @foreach($projectpurchase as $key => $row)
        @php
        $balance += $row->totalbalance;
        $no++;
        @endphp
        <tr>
            <td class="text-center">{{ $no }}</td>
            <td>{{$row->project ? $row->project->code.' - '.$row->project->name.' PO : '.$row->code : 'For Stock PO : '.$row->code.' - '.$row->supplier->name}}</td>
            <td class="text-right"></td>
            <td class="text-right">{{ number_format($row->totalbalance,2,'.',',') }}</td>
            <td class="text-right">{{ number_format($balance,2,'.',',')}}</td>
        </tr>
        @endforeach
        @foreach($samplepurchase as $key => $row)
        @php
        $balance += $row->totalbalance;
        $no++;
        @endphp
        <tr>
            <td class="text-center">{{ $no }}</td>
            <td>{{ $row->supplier->name.' - '. $row->sample ? $row->sample->code.' - '.$row->sample->name.' PO :
                '.$row->code :
                'For Stock PO : '.$row->code}}</td>
            <td class="text-right"></td>
            <td class="text-right">{{ number_format($row->totalbalance,2,'.',',') }}</td>
            <td class="text-right">{{ number_format($balance,2,'.',',')}}</td>
        </tr>
        @endforeach
        @foreach($other as $row)
        @php
        if($row->cashBank->lookable_type == 'purchase_requests' || $row->cashBank->lookable_type == 'projects' ||
        str_contains($row->cashBank->code, 'RJCT') || $row->cashBank->lookable_type == null ){
        $cek = NULL;
        $cek_rjct = NULL;
        if($row->cashBank->lookable_type == 'purchase_requests'){
        $cek = PurchaseRequest::find($row->cashBank->lookable_id);
        }elseif($row->cashBank->lookable_type == 'projects'){
        $cek = PurchaseRequest::find(explode('-',$row->cashBank->code)[1]);
        }elseif(str_contains($row->cashBank->code, 'RJCT')){
            $whereRaw = strlen($filter) == 7 ? "LEFT(date, 7) <= '$filter'" : "date <= '$filter'";
            $isMultipleReject = explode("-",$row->cashBank->code);
            $length_of_last_code = isset($isMultipleReject[3]) ? strlen($isMultipleReject[3]) : 0;

            $cek_rjct = isset($isMultipleReject[3]) ? CashBank::where('code','like',substr($row->cashBank->code, 0, -$length_of_last_code).'CLOSE-%'.$isMultipleReject[3])->whereRaw($whereRaw)->first() : CashBank::where('code','like', $row->cashBank->code.'-CLOSE%')->whereRaw($whereRaw)->first() ;
        }elseif($row->cashBank->lookable_type == null){

        }
        $totalpay = $cek ? $cek->totalPaymentPeriod($filter) : ($cek_rjct ?
        $cek_rjct->cashBankDetail->where('coa_id', 332)->where('type', 1)->first()->nominal : 0);

        $sisa = $row->nominal - $totalpay;
        if($sisa > 0 && $length_of_last_code == 0){
        $balance += $sisa;
        @endphp
        <tr>
            <td class="text-center">{{ $no }}</td>
            <td>{{ (isset($row->cashBank->supplier->name) ? $row->cashBank->supplier->name : '') .' '.
                $row->cashBank->code.' - '.$row->cashBank->description.' - '.$row->note }}</td>
            <td></td>
            <td class="text-right">{{ number_format($sisa,2,'.',',') }}</td>
            <td class="text-right">{{ number_format($balance,2,'.',',') }}</td>
        </tr>
        @php
        $no++;
        }
        }else{
        if($row->cashBank->lookable_type !== 'project_warehouses'){
        $balance += $row->nominal;
        @endphp
        <tr>
            <td class="text-center">{{ $no }}</td>
            <td>{{ (isset($row->cashBank->supplier->name) ? $row->cashBank->supplier->name : '') .'
                '.$row->cashBank->code.' - '.$row->cashBank->description.' - '.$row->note }}</td>
            <td></td>
            <td class="text-right">{{ number_format($row->nominal,2,'.',',') }}</td>
            <td class="text-right">{{ number_format($balance,2,'.',',') }}</td>
        </tr>
        @php
        $no++;
        }
        }
        @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-right">
            <th colspan="3">Total</th>
            <th></th>
            <th>{{ number_format($balance,2,'.',',') }}</th>
        </tr>
    </tfoot>
</table>