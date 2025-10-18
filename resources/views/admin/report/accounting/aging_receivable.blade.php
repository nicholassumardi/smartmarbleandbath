<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Aging Receivable</span>
				</h4>
			</div>

		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Aging Receivable</span>
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
						Periode {{ date('F Y', strtotime($filter)) }}</h6>
						<form method="GET" id="form_filter">
							@csrf
							<div class="form-group">
								<center class="d-block">
									<div class="row justify-content-center no-gutters">
										<div class="col-md-3">
											<div class="form-group">
												<label>Branch</label>
												<select name="branch" id="branch" class="form-control" onchange="submitFilter()">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id == $branch ? 'selected' : '' }}">{{$company->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<label>Month Year</label>
											<input type="month" name="filter" id="filter" class="form-control" value="{{ $filter }}" onchange="submitFilter()">
										</div>
										@if (date('Y-m') != $filter)
											<div class="col-md-1">
												<a href="{{ url('admin/report/accounting/aging_receivable') }}"
													class="btn bg-danger btn-sm">Reset</a>
											</div>
										@endif
									</div>
								</center>
							</div>
						</form>
				   </div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
							<li class="nav-item"><a href="#tab-project" class="nav-link active" data-toggle="tab">Project</a></li>
							<li class="nav-item"><a href="#tab-other" class="nav-link" data-toggle="tab">Other</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="tab-project">
								<div class="table-responsive">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="5%">No</th>
											<th>Customer</th>
											<th>Information</th>
											<th>Date Received</th>
											<th>Term</th>
											<th>Nominal</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$debit = 0;
											$credit = 0;
										@endphp
										@foreach($result as $key => $row)
											@php
												$debit += $row['debit'];
												$credit += $row['credit'];
											@endphp
											<tr>
												<td class="text-center">{{ $key+1 }}</td>
												<td>{{ $row['customer'] }}</td>
												<td>{{ $row['info'] }}</td>
												<td class="text-center">{{ $row['project']->received_date !== null ? $row['project']->received_date : '' }}</td>
												<td class="text-center">{{ $row['project']->project->paymentTerm() !== null ? $row['project']->project->paymentTerm() : '' }}</td>
												<td class="text-right">{{ number_format($row['debit'],2,',','.') }}</td>
											</tr>
										@endforeach
									  </tbody>
									  <tfoot>
										<tr class="text-right">
											<th colspan="5">Total</th>
											<th>{{ number_format($debit,2,',','.') }}</th>
											<!-- <th>{{ number_format($credit,2,',','.') }}</th> -->
										</tr>
									  </tfoot>
								   </table>
								</div>
							</div>
							<div class="tab-pane fade" id="tab-other">
								<div class="table-responsive">
								   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="5%">No</th>
											<th>Customer</th>
											<th>Information</th>
											<th>Request Date</th>
											<th>Due Date</th>
											<th>Nominal</th>
										 </tr>
									  </thead>
									  <tbody>
										@php
											$debit = 0;
											$credit = 0;
										@endphp
										@foreach($resultOther as $key => $row)
											@php
												$debit += $row['debit'];
												$credit += $row['credit'];
											@endphp
											<tr>
												<td class="text-center">{{ $key+1 }}</td>
												<td>{{ $row['customer'] }}</td>
												<td>{{ $row['info'] }}</td>
												<td class="text-center">{{ $row['project']->request_date !== null ? $row['project']->request_date : '' }}</td>
												<td class="text-center">{{ $row['project']->due_date !== null ? $row['project']->due_date : '' }}</td>
												<td class="text-right">{{ number_format($row['debit'],2,',','.') }}</td>
											</tr>
										@endforeach
									  </tbody>
									  <tfoot>
										<tr class="text-right">
											<th colspan="5">Total</th>
											<th>{{ number_format($debit,2,',','.') }}</th>
											<!-- <th>{{ number_format($credit,2,',','.') }}</th> -->
										</tr>
									  </tfoot>
								   </table>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
			</div>
		</div>
	</div>
	<script>
        function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }
    </script>
	