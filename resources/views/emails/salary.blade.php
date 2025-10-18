<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title></title>
  <style media="all">
    *:not(br):not(tr):not(html) {
      font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
    }

    body {
      width: 100% !important;
      height: 100%;
      margin: 0;
      line-height: 1.4;
      background-color: #F5F7F9;
      color: #839197;
      -webkit-text-size-adjust: none;
    }

    a {
      color: #414EF9;
    }

    .email-wrapper {
      width: 100%;
      margin: 0;
      padding: 0;
      background-color: #F5F7F9;
    }

    .email-content {
      width: 100%;
      margin: 0;
      padding: 0;
    }

    .email-masthead {
      padding: 25px 0;
      text-align: center;
    }

    .email-masthead_logo {
      max-width: 400px;
      border: 0;
    }

    .email-masthead_name {
      font-size: 16px;
      font-weight: bold;
      color: #839197;
      text-decoration: none;
      text-shadow: 0 1px 0 white;
    }

    .email-body {
      width: 100%;
      margin: 0;
      padding: 0;
      border-top: 1px solid #E7EAEC;
      border-bottom: 1px solid #E7EAEC;
      background-color: #FFFFFF;
    }

    .email-body_inner {
      width: 570px;
      margin: 0 auto;
      padding: 0;
    }

    .email-footer {
      width: 570px;
      margin: 0 auto;
      padding: 0;
      text-align: center;
    }

    .email-footer p {
      color: #839197;
    }

    .body-action {
      width: 100%;
      margin: 30px auto;
      padding: 0;
      text-align: center;
    }

    .body-sub {
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid #E7EAEC;
    }

    .content-cell {
      padding: 35px;
    }

    .align-right {
      text-align: right;
    }

    h1 {
      margin-top: 0;
      color: #292E31;
      font-size: 19px;
      font-weight: bold;
      text-align: left;
    }

    h2 {
      margin-top: 0;
      color: #292E31;
      font-size: 16px;
      font-weight: bold;
      text-align: left;
    }

    h3 {
      margin-top: 0;
      color: #292E31;
      font-size: 14px;
      font-weight: bold;
      text-align: left;
    }

    p {
      margin-top: 0;
      color: #839197;
      font-size: 16px;
      line-height: 1.5em;
      text-align: left;
    }

    p.sub {
      font-size: 12px;
    }

    p.center {
      text-align: center;
    }

    .button {
      display: inline-block;
      width: 200px;
      background-color: #414EF9;
      border-radius: 3px;
      color: #ffffff;
      font-size: 15px;
      line-height: 45px;
      text-align: center;
      text-decoration: none;
      -webkit-text-size-adjust: none;
      mso-hide: all;
    }

    .button--green {
      background-color: #28DB67;
    }

    .button--red {
      background-color: #FF3665;
    }

    .button--blue {
      background-color: #414EF9;
    }

    @media only screen and (max-width: 600px) {
      .email-body_inner, .email-footer {
        width: 100% !important;
      }
    }

    @media only screen and (max-width: 500px) {
      .button {
        width: 100% !important;
      }
    }
  </style>
</head>
<body>
  <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
        <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td class="email-masthead">
              <a class="email-masthead_name">Smartmarble And Bath- Salary Slip</a>
            </td>
          </tr>
          <tr>
            <td class="email-body" width="100%" style="padding-bottom:25px;">
			  <table class="body-sub" align="center" width="650">
				  <tr>
					<td>Date Generate</td>
					<td>: {{ date('d M Y',strtotime($data->salary->date_generate)) }}</td>
					<td>Pay Period</td>
					<td>: {{ date('F Y',strtotime($data->salary->month)) }}</td>
				  </tr>
				  <tr>
					<td>Attendance Period</td>
					<td>: {{ date('d M Y',strtotime($data->salary->date_start)).' to '.date('d M Y',strtotime($data->salary->date_end)) }}</td>
					<td></td>
					<td></td>
				  </tr>
			   </table>
			   <table class="body-sub" align="center" width="650" border="1" style="border-collapse: collapse;border-top: 1px solid black;">
				  <thead>
					<tr>
						<th width="25%">Earnings</th>
						<th width="25%">Amounts</th>
						<th width="25%">Deductions</th>
						<th width="25%">Amounts</th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
						<td colspan="2">
							<table class="body-sub" align="center" style="border-collapse: collapse;margin-top: 0px !important;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;" width="100%">
							  <tbody>
								@php
									$totalearning = 0;
									$totaldeduction = 0;
								@endphp
								@foreach($data->employeeAllowance->employeeAllowanceDetail as $row)
								@php
									$totalrow = $row->getNominal($data->employeeAllowance->employee_id,$data->salary->branch,$data->salary->month,$data->salary->date_start,$data->salary->date_end);
									
									$totalearning += $totalrow;
								@endphp
								<tr>
									<td>{{ $row->allowance->name.' Qty. '.$row->getQty($data->salary->branch,$data->salary->date_start,$data->salary->date_end) }}</td>
									<td align="right">{{ number_format($totalrow,2,',','.') }}</td>
								</tr>
								@endforeach
								<tr>
									<td>Other Earnings</td>
									<td align="right">{{ number_format($data->addition,2,',','.') }}</td>
								</tr>
							  </tbody>
							  <tfoot>
								<tr style="font-size:15px;font-weight:800;">
									<td>TOTAL EARNING</td>
									<td align="right">{{ number_format($totalearning + $data->addition,2,',','.') }}</td>
								</tr>
							  </tfoot>
							</table>
						</td>
						<td colspan="2">
							<table class="body-sub" align="center" style="border-collapse: collapse;margin-top: 0px !important;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;" width="100%">
							  <tbody>
								@foreach($data->employeeAllowance->employeeAllowanceDetail as $row)
								@php
									$totalrow = $row->getNominalCutting($data->employeeAllowance->employee_id,$data->salary->branch,$data->salary->month,$data->salary->date_start,$data->salary->date_end);
									
									$totaldeduction += $totalrow;
								@endphp
								<tr>
									<td>{{ $row->allowance->name.' Qty. '.$row->getQtyCutting($data->employeeAllowance->employee_id,$data->salary->branch,$data->salary->date_start,$data->salary->date_end) }}</td>
									<td align="right">{{ number_format($totalrow,2,',','.') }}</td>
								</tr>
								@endforeach
								<tr>
									<td>Loan Credit</td>
									<td align="right">{{ number_format($data->loan,2,',','.') }}</td>
								</tr>
							  </tbody>
							  <tfoot>
								<tr style="font-size:15px;font-weight:800;">
									<td>TOTAL DEDUCTION</td>
									<td align="right">{{ number_format($totaldeduction + $data->loan,2,',','.') }}</td>
								</tr>
							  </tfoot>
							</table>
						</td>
					</tr>
					<tr style="font-size:15px;font-weight:800;color:red;">
						<td colspan="4" align="center">TOTAL RECEIVED : Rp {{ number_format(($totalearning + $data->addition) - ($totaldeduction + $data->loan),2,',','.') }}
						<br>
							<div style="font-size:11px;">{{ App\Helper\SMB::say($data->total) }}</div>
						</td>
					</tr>
				  </tbody>
			   </table>
			   <table class="body-sub" align="center" width="650">
				  <tbody>
					<tr>
						<td align="center">
							<b>PT. Perwira Tamaraya Abadi
							@if(isset($data->salary->user->sign))
								<div><img src="{{ url(Storage::url($data->salary->user->sign)) }}" height="65px"></div>
							@else
								<br><br><br>
							@endif
							<div><u>{{ $data->salary->user->name }}</u></div>
							<div>{{ $data->salary->user->userRole->first()->role() }}</div>
							</b>
						</td>
					</tr>
				  </tbody>
			   </table>
            </td>
          </tr>
          <tr>
            <td>
              <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="content-cell">
                    <p class="sub center">
                      <br>Surabaya, Baliwerti 119-121.
                      <br>Jawa Timur
                      <br>(+62) 811332642
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>