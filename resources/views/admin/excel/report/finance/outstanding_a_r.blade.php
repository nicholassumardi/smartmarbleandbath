<table id="datatable_serverside" class="table table-bordered table-striped w-100">
    <thead class="bg-dark">
       <tr class="text-center">
          <th width="5%">No</th>
          <th>Description</th>
          <th>Debit</th>
          <th>Credit</th>
          <th>Balance</th>
       </tr>
    </thead>
    <tbody>
      @php
          $balance = 0;
          $no = 0;
      @endphp
      @foreach($projectsale as $key => $row)
          @php
              $balance += $row->totalbalance;
              $no++;
          @endphp
          <tr>
              <td class="text-center">{{$no}}</td>
              <td>{{$row->project->customer->name.' - '.$row->project->customer->phone.' - '.$row->project->code.' - '.$row->project->name}}</td>
              <td class="text-right">{{ number_format($row->totalbalance,2,',','.') }}</td>
              <td class="text-right"></td>
              <td class="text-right">{{ number_format($balance,2,',','.') }}</td>
          </tr>
      @endforeach
      @foreach($projectbill as $key => $row)
          @php
              $balance += $row->balancePeriod($filter);
              $no++;
          @endphp
          <tr>
              <td class="text-center">{{ $no }}</td>
              <td>{{ $row->project->customer->name.' - '.$row->project->customer->phone.' - PROJECT BILL - '.$row->project->code.' - '.$row->project->name}}</td>
              <td class="text-right">{{ number_format($row->balancePeriod($filter),2,',','.') }}</td>
              <td class="text-right"></td>
              <td class="text-right">{{ number_format($balance,2,',','.') }}</td>
          </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="text-right">
          <th colspan="3">Total</th>
          <th></th>
          <th>{{ number_format($balance,2,',','.') }}</th>
      </tr>
    </tfoot>
 </table>