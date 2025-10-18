<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Attendance Employee Report</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
		<style>
			body {
				font-family: 'Lato', sans-serif;
			}
			
			th {
				font-size:12px;
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
				@page {size: A4 portrait; }
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
			
			@page { margin: 1cm; }
			body { margin: 1cm; }
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
											{!! $title !!}
										</b>
									</h2>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br>
			@if($mode == 'monthbranch')
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th>No</th>
						<th>Employee</th>
						<th>Workdays (days)</th>
						<th>Attended (days)</th>
						<th>Days Late (days)</th>
						<th>Late (minutes)</th>
						<th>Leave Request Overtime</th>
					</tr>
				</thead>
				<tbody>
					@foreach($result['listEmployee'] as $key => $row)
					@php 
						$rowdata = $row->getCheckIn($result['month']);
					@endphp
					<tr align="center">
						<td>{{ $key + 1 }}</td>
						<td>{{ $row->name }}</td>
						<td>{{ count($result['arrWorkDay']) }}</td>
						<td>{{ $rowdata['countCheckIn'] }}</td>
						<td>{{ $rowdata['daysLate'] }}</td>
						<td>{{ $rowdata['lateMinute'] }}</td>
						<td>{{ $rowdata['overLeave'] }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@elseif($mode == 'monthemployee')
			<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
				<thead>
					<tr align="center">
						<th>No</th>
						<th>Date</th>
						<th>In Time</th>
						<th>In Rule</th>
						<th>In Late (min)</th>
						<th>Out Time</th>
						<th>Out Rule</th>
						<th>Out Early (min)</th>
						<th>Leave Out</th>
						<th>Leave In</th>
						<th>Leave Diff (min)</th>
						<th>Leave Diff (hour)</th>
					</tr>
				</thead>
				<tbody>
					@php
						$total_late = 0;
						$total_leave = 0;
						$total_check_in = 0;
					@endphp
					@foreach($result['arrWorkDay'] as $key => $row)
						@php
							$leave = $result['leaveRequest']->where('date',$row)->first();
							$data = $result['attendance']->where('date',$row)->first();
							$in_time = $data ? $data->in_time : '-';
							$in_note = $data ? $data->in_note : '-';
							$in_rule = $data ? $data->in_rule : '-';
							$late_min = $data ? $data->getMinLateIn() : 0;
							$out_time = $data ? $data->out_time : '-';
							$out_note = $data ? $data->out_note : '-';
							$out_rule = $data ? $data->out_rule : '-';
							$early_min = $data ? $data->getMinFastOut() : '-';
							$leave_out_time = $data ? $data->leave_out_time : '';
							$leave_in_time = $data ? $data->leave_in_time : '';
							$diff_leave = '';
							$diff_leave_hour = '';
							
							if($leave_out_time && $leave_in_time){
								$diff_leave = round((strtotime($leave_in_time) - strtotime($leave_out_time))/60,2);
								$diff_leave_hour = round((strtotime($leave_in_time) - strtotime($leave_out_time))/3600,2);
							}
							
							$total_late += $late_min;
							
							if($data && !$leave){
								$total_check_in++;
							}
						@endphp
						@if($leave)
							<tr align="center" style="background-color:yellow !important;">
								<td>{{ $key + 1 }}</td>
								<td>{{ date('d M Y',strtotime($row)) }}</td>
								<td colspan="10">{{ $leave['description'] }}</td>
							</tr>
							@php
								$total_leave++;
							@endphp
						@else
							<tr align="center">
								<td>{{ $key + 1 }}</td>
								<td>{{ date('d M Y',strtotime($row)) }}</td>
								<td>{{ $in_time }}<br><b>{{$in_note}}</b></td>
								<td>{{ $in_rule }}</td>
								<td>{{ $late_min }}</td>
								<td>{{ $out_time }}<br><b>{{$out_note}}</b></td>
								<td>{{ $out_rule }}</td>
								<td>{{ $early_min }}</td>
								<td>{{ $leave_out_time }}</td>
								<td>{{ $leave_in_time }}</td>
								<td>{{ $diff_leave }}</td>
								<td>{{ $diff_leave_hour }}</td>
							</tr>
						@endif
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<th colspan="4" align="center">TOTAL</th>
						<th align="center">{{ $total_late }}</th>
						<th colspan="7"></th>
					</tr>
					<tr>
						<th colspan="12">
							TOTAL WORKDAYS : {{ count($result['arrWorkDay']) }}
							<br>
							TOTAL LEAVE REQUEST : {{ $total_leave }}
							<br>
							TOTAL ATTENDED : {{ $total_check_in }}
						</th>
					</tr>
				</tfoot>
			</table>
			@endif
		</div>
	</body>
</html>