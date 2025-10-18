<table id="datatable_serverside" class="table table-bordered table-striped w-100">
    <thead class="bg-dark">
       <tr class="text-center">
          <th width="5%">No</th>
          <th>C&B No.</th>
          <th>Date</th>
          <th>Detail</th>
          <th>Total</th>
          <th>Balance</th>
       </tr>
    </thead>
    <tbody>
          @php
              $balance = 0;
              $no = 1;
              $filter = $filter ? $filter : $filter_date;
          @endphp
          @foreach($arrPayable as $row)
              <tr class="bg-success text-white" data-toggle="collapse" data-target="#collapse-button-{{ $row->cashBank->code }}">
                  <td class="text-center">{{ $no }}</td>
                  <td>{{ $row->cashBank->code }}</td>
                  <td>{{ date('d M Y',strtotime($row->cashBank->date)) }}</td>
                  <td>{{ $row->note.' '.$row['description'].' '.($row->cashBank->lookable_type == 'projects' ?  $row->cashBank->lookable->code : '') }}</td>
                  <td class="text-right">{{ number_format($row['nominal'],2,',','.') }}</td>
                  <td class="text-right">{{ number_format($row['balance'],2,',','.') }}</td>
              </tr>
              <tr class="collapse" id="collapse-button-{{ $row->cashBank->code }}">
                  <td colspan="6">
                      @if(count($row['arrpayment']) > 0)
                      Payments : 
                      <table id="datatable_serverside" class="table table-bordered table-striped w-100">
                        <thead>
                          <tr>
                              <th>No</th>
                              <th>Code</th>
                              <th>Date</th>
                              <th>Description</th>
                              <th>Debit</th>
                              <th>Credit</th>
                              <th>Balance</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php
                              $rowno = 1;
                          @endphp
                          <tr>
                              <td class="text-center">{{ $rowno }}</td>
                              <td>{{ $row->cashBank->code }}</td>
                              <td>{{ date('d M Y',strtotime($row->cashBank->date)) }}</td>
                              <td>{{ $row->note.' '.$row['description'].' '.($row->cashBank->lookable_type == 'projects' ?  $row->cashBank->lookable->code : '') }}</td>
                              <td class="text-right">0</td>
                              <td class="text-right">{{ number_format($row['nominal'],2,',','.') }}</td>
                              <td class="text-right">{{ number_format($row['nominal'],2,',','.') }}</td>
                          </tr>
                          @php
                              $rowbalance = $row['nominal'];
                          @endphp
                          @foreach($row['arrpayment'] as $key => $rowpay)
                              @php
                                  $rowbalance -= $rowpay['nominal'];
                                  $rowno++;
                              @endphp
                          <tr>
                              <td class="text-center">{{ $rowno }}.</td>
                              <td>{{ $rowpay['code'] }}</td>
                              <td>{{ date('d M Y',strtotime($rowpay['date'])) }}</td>
                              <td>{{ $rowpay['description'] }}</td>
                              <td class="text-right">{{ number_format($rowpay['nominal'],2,',','.') }}</td>
                              <td class="text-right">0</td>
                              <td class="text-right">{{ number_format($rowbalance,2,',','.') }}</td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                      @else
                          No payment found.
                      @endif
                  </td>
              </tr>
              @php
                  $no++;
                  $balance += $row['balance'];
              @endphp
          @endforeach
    </tbody>
 </table>