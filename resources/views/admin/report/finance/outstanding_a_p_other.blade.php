@php
	use App\Models\Transfer;
	use App\Models\CashBank;
	use App\Models\PurchaseRequest;
	use App\Models\PurchaseCost;
@endphp
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Outstanding A/P Other</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<button class="btn bg-pink-400 btn-labeled mr-2 btn-labeled-left" onclick="print()">
					<b><i class="icon-printer2"></i></b> Print</button>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Finance</a>
					<span class="breadcrumb-item active">Outstanding A/P Other</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
				<div class="row justify-content-center">
				   <div class="col-md-12">
						@php
							print_r($arrTempReturn);
						@endphp
					  <h6 class="text-muted text-uppercase text-center font-weight-bold">
						A/P REPORT PER {{ $mode == '1' ? date('F Y', strtotime($filter)) : date('d M Y', strtotime($filter_date)) }}</h6>
						<form method="GET" id="form_filter" onsubmit="return validateMyForm();">
							@csrf
							<div class="form-group">
								<center class="d-block">
									<div class="row justify-content-center mt-3">
										<div class="col-md-12">
											<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
												<b><span class="font-weight-semibold">Important Info!</span> 
												Click rows to show detail payments for each A/P.</b>
											</div>
										</div>
									</div>
									<div class="row justify-content-center">
										<div class="col-md-3">
											<div class="form-group">
												<label style="margin-bottom: 0rem;">Mode</label>
												<select name="mode" id="mode" class="form-control" onchange="changeMode(this.value)">
													<option value="1" {{ $mode == '1' ? 'selected' : '' }}>Month</option>
													<option value="2" {{ $mode == '2' ? 'selected' : '' }}>Date</option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label style="margin-bottom: 0rem;">Branch</label>
												<select name="branch" id="branch" class="form-control">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id == $branch ? 'selected' : '' }}">{{$company->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label style="margin-bottom: 0rem;">Type</label>
												<select name="type" id="type" class="form-control">
													<option value="">--Choose one--</option>
													@foreach($listpayable as $row)
													<option value="{{ $row->id }}">{{ '['.$row->code.'] '.$row->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3 mode1">
											<label style="margin-bottom: 0rem;">Month</label>
											<input type="month" name="filter" id="filter" class="form-control" value="{{ $filter }}">
										</div>
										<div class="col-md-3 mode2 d-none">
											<label style="margin-bottom: 0rem;">Date</label>
											<input type="date" name="filter_date" id="filter_date" class="form-control" value="{{ $filter_date }}">
										</div>
									</div>
									<div class="row justify-content-center mt-3">
										<div class="col-md-3">
											<button class="btn bg-success btn-sm mr-3"><i class="icon-search4"></i> Process</button>
											<a href="{{ url('admin/report/finance/outstanding_a_p_other') }}" class="btn bg-danger btn-sm"><i class="icon-reset"></i> Reset</a>
										</div>
									</div>
								</center>
							</div>
						</form>
				   </div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
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
						</div>
						<h1 align="right">Total All : Rp {{ number_format($balance,2,',','.') }}</h1>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<script>
		$(function() {
			@if($mode == '1')
				$('.mode2').addClass('d-none');
				$('#filter_date').val('');
				$('.mode1').removeClass('d-none');
			@elseif($mode == '2')
				$('.mode1').addClass('d-none');
				$('#filter').val('');
				$('.mode2').removeClass('d-none');
			@endif
			
			$("html, body").animate({
				scrollTop: $(
				  'html, body').get(0).scrollHeight
			}, 500);
			
			$('#type').val('{{ $type }}');
		});
	   
        function validateMyForm() {
			if($('#type').val()){
				loadingOpen('.content');
				return true;
			}else{
				notif('error', 'bg-danger', 'Please choose type.');
			}
			return false;
        }
		
		function changeMode(val){
			if(val == '1'){
				$('.mode1').removeClass('d-none');
				$('#filter_date').val('');
				$('.mode2').addClass('d-none');
			}else if(val == '2'){
				$('.mode2').removeClass('d-none');
				$('#filter').val('');
				$('.mode1').addClass('d-none');
			}
		}
		
		function print(){
			let url = '@php echo url("admin/report/finance/outstanding_a_p_other/print/oap?branch=".$branch."&monthyear=".$filter); @endphp';
			window.open(url, '_blank');
		}
    </script>
	