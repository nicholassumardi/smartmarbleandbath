@php
	use App\Helper\SMB;
@endphp

<style>
	@if(in_array(1, session('bo_role')))
		.hide {
			display:none;
		}
	@endif
	
	.table-bordered td, .table-bordered th { border: 1px solid #818181; }
	
	table, tr td {
		border: 1px solid red
	}
	
	@if($mode == '1')
		
		tbody {
			display: block;
			height: 450px;
			overflow: auto;
		}
		
		tbody::-webkit-scrollbar {
		  display: none;
		}

		tbody {
		  -ms-overflow-style: none;  /* IE and Edge */
		  scrollbar-width: none;  /* Firefox */
		}
		
		thead, tbody tr, tfoot tr {
			display: table;
			width: 100%;
			table-layout: fixed;/* even columns width , fix width of table too*/
		}
		
		thead {
			width: calc( 100% - 0em )/* scrollbar is average 1em/16px width, remove it from thead width */
		}
	
	@endif
	
	table {
		width: 400px;
	}
	
	td:first-child, .fixed
	{
		position:sticky;
		left:0px;
		background-color:#ddd;
		color:black !important;
	}
	
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Profit & Loss</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Profit & Loss</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header">
				<h4 class="text-muted text-uppercase text-center font-weight-bold">Periode {{ $mode == '1' ? date('F Y', strtotime($filter)) : ($mode == '2' ? date('M Y',strtotime($filter_start)).' to '.date('M Y',strtotime($filter_end)) : $year) }} MODE - {{ $mode == '1' ? 'Single Month' : ($mode == '2' ? 'Month to Month' : ($date_start ? date('d M Y',strtotime($date_start)).' to '.date('d M Y',strtotime($date_end)) : 'Yearly with Budgeting')) }}</h4>
				<form action="{{ url('admin/report/accounting/profit_loss') }}" method="GET" id="form_filter">
				   @csrf
				   <div class="form-group">
					  <center class="d-block">
						 <div class="row justify-content-center">
							<div class="col-md-3 text-left">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Mode</label>
									<select name="mode" id="mode" class="form-control" onchange="changeMode(this.value)">
										<option value="1" {{ $mode == '1' ? 'selected' : '' }}>Single Month</option>
										<option value="2" {{ $mode == '2' ? 'selected' : '' }}>Month to Month</option>
										<!-- <option value="3" {{ $mode == '3' ? 'selected' : '' }}>Yearly with Budgeting</option> -->
										<option value="4" {{ $mode == '4' ? 'selected' : '' }}>Date to Date</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 text-left">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Branch</label>
									<select name="branch" id="branch" class="form-control">
										<option value="" {{ $branch == '' ? 'selected' : '' }}>All</option>
										@foreach (DB::table('company_entities')->get() as $company)
											<option value="{{$company->id}} {{$company->id == $branch ? 'selected' : '' }}">{{$company->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
						 </div>
						 <div class="row justify-content-center mt-3">
							<div class="col-md-3 text-left mode1">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Month Year</label>
									<input type="month" name="filter" id="filter" class="form-control" style="height:32px;" value="{{ $filter }}">
								</div>
							</div>
							<div class="col-md-3 text-left d-none mode2">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Start</label>
									<input type="month" name="filter_start" id="filter_start" class="form-control" style="height:32px;" value="{{ $filter_start }}">
								</div>
							</div>
							<div class="col-md-3 text-left d-none mode2">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">End</label>
									<input type="month" name="filter_end" id="filter_end" class="form-control" style="height:32px;" value="{{ $filter_end }}">
								</div>
							</div>
							<div class="col-md-3 text-left d-none mode3">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Year</label>
									<select name="year" id="year" class="form-control">
										<option value="" {{ $year == '' ? 'selected' : '' }}>Choose one</option>
										@for($i=date('Y')-4;$i<date('Y')+5;$i++)
										<option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
										@endfor
									</select>
								</div>
							</div>
							<div class="col-md-3 text-left d-none mode4">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Start</label>
									<input type="date" name="date_start" id="date_start" class="form-control" value="{{ $date_start }}">
								</div>
							</div>
							<div class="col-md-3 text-left d-none mode4">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">End</label>
									<input type="date" name="date_end" id="date_end" class="form-control" value="{{ $date_end }}">
								</div>
							</div>
						 </div>
						 <div class="row justify-content-center mt-3">
							<div class="col-md-3">
								<button class="btn bg-success btn-sm mr-3" onclick="submitFilter()"><i class="icon-search4"></i> Process</button>
								<a href="{{ url('admin/report/accounting/profit_loss') }}" class="btn bg-danger btn-sm"><i class="icon-reset"></i> Reset</a>
							</div>
						 </div>
					  </center>
				   </div>
				</form>
			</div>
			<div class="card-body">
				 <div class="table-responsive">
					@if($mode == '1')
					<table class="table table-sm table-bordered fixed_header">
					   <thead>
						  <tr class="text-center bg-primary font-weight-bold" style="font-size:18px;">
							 <th>Description</th>
							 <th>Actual</th>
							 <th>%</th>
							 <th>Goal</th>
							 <th>%</th>
							 <th class="hide">Variance</th>
							 <th class="hide">%</th>
						  </tr>
					   </thead>
					   <tbody>
							@php
								$total_revenue_actual = 0;
								$total_cogs_actual = 0;	
								$total_fixed_cost_actual = 0;
								$total_variable_cost_actual = 0;
								$total_other_expenses_actual = 0;
								$total_repair_expenses_actual = 0;
								$total_depreciation_actual = 0;
								$total_capex_actual = 0;
								$total_other_income_actual = 0;
								$total_other_deduction_actual = 0;
								
								$total_revenue_budget = 0;
								$total_cogs_budget = 0;	
								$total_fixed_cost_budget = 0;
								$total_variable_cost_budget = 0;
								$total_other_expenses_budget = 0;
								$total_repair_expenses_budget = 0;
								$total_depreciation_budget = 0;
								$total_capex_budget = 0;
								$total_other_income_budget = 0;
								$total_other_deduction_budget = 0;
								
								$total_revenue_variance = 0;
								$total_cogs_variance = 0;	
								$total_fixed_cost_variance = 0;
								$total_variable_cost_variance = 0;
								$total_other_expenses_variance = 0;
								$total_repair_expenses_variance = 0;
								$total_depreciation_variance = 0;
								$total_capex_variance = 0;
								$total_other_income_variance = 0;
								$total_other_deduction_variance = 0;
								
								
								foreach($coa->where('parent_id',0) as $rowparent){
									if(substr($rowparent->code,0,1) == '4' || substr($rowparent->code,0,1) == '5' || substr($rowparent->code,0,1) == '6' || substr($rowparent->code,0,1) == '7'){
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPL($filter,$branch);
											$budgeting = $rowparent->checkTotalBudgeting($filter,$branch);
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$total_revenue_actual += $balance;
												$total_revenue_budget += $budgeting;
												$total_revenue_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,5) == '5.000' || substr($rowparent->code,0,5) == '6.000' || substr($rowparent->code,0,5) == '6.100'){
												$total_cogs_actual += $balance;
												$total_cogs_budget += $budgeting;
												$total_cogs_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,5) == '6.200'){
												$total_fixed_cost_actual += $balance;
												$total_fixed_cost_budget += $budgeting;
												$total_fixed_cost_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,9) == '6.2100.02'){
												$total_variable_cost_actual += $balance;
												$total_variable_cost_budget += $budgeting;
												$total_variable_cost_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,9) == '6.2100.03'){
												$total_other_expenses_actual += $balance;
												$total_other_expenses_budget += $budgeting;
												$total_other_expenses_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,6) == '6.2200'){
												$total_repair_expenses_actual += $balance;
												$total_repair_expenses_budget += $budgeting;
												$total_repair_expenses_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,5) == '6.300'){
												$total_depreciation_actual += $balance;
												$total_depreciation_budget += $budgeting;
												$total_depreciation_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,5) == '6.400'){
												$total_capex_actual += $balance;
												$total_capex_budget += $budgeting;
												$total_capex_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,5) == '7.100'){
												$total_other_income_actual += $balance;
												$total_other_income_budget += $budgeting;
												$total_other_income_variance += $balance - $budgeting;
											}
											
											if(substr($rowparent->code,0,5) == '7.200'){
												$total_other_deduction_actual += $balance;
												$total_other_deduction_budget += $budgeting;
												$total_other_deduction_variance += $balance - $budgeting;
											}
										}
										
										foreach($rowparent->child() as $rowchild){
											if(count($rowchild->child()) == 0){
												$balance = $rowchild->checkTotalPL($filter,$branch);
												$budgeting = $rowchild->checkTotalBudgeting($filter,$branch);
												
												if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
													$total_revenue_actual += $balance;
													$total_revenue_budget += $budgeting;
													$total_revenue_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,5) == '5.000' || substr($rowchild->code,0,5) == '6.000' || substr($rowchild->code,0,5) == '6.100'){
													$total_cogs_actual += $balance;
													$total_cogs_budget += $budgeting;
													$total_cogs_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,5) == '6.200'){
													$total_fixed_cost_actual += $balance;
													$total_fixed_cost_budget += $budgeting;
													$total_fixed_cost_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,9) == '6.2100.02'){
													$total_variable_cost_actual += $balance;
													$total_variable_cost_budget += $budgeting;
													$total_variable_cost_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,9) == '6.2100.03'){
													$total_other_expenses_actual += $balance;
													$total_other_expenses_budget += $budgeting;
													$total_other_expenses_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,6) == '6.2200'){
													$total_repair_expenses_actual += $balance;
													$total_repair_expenses_budget += $budgeting;
													$total_repair_expenses_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,5) == '6.300'){
													$total_depreciation_actual += $balance;
													$total_depreciation_budget += $budgeting;
													$total_depreciation_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,5) == '6.400'){
													$total_capex_actual += $balance;
													$total_capex_budget += $budgeting;
													$total_capex_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,5) == '7.100'){
													$total_other_income_actual += $balance;
													$total_other_income_budget += $budgeting;
													$total_other_income_variance += $balance - $budgeting;
												}
												
												if(substr($rowchild->code,0,5) == '7.200'){
													$total_other_deduction_actual += $balance;
													$total_other_deduction_budget += $budgeting;
													$total_other_deduction_variance += $balance - $budgeting;
												}
											}
											
											foreach($rowchild->child() as $rowgrandchild){
												if(count($rowgrandchild->child()) == 0){
													$balance = $rowgrandchild->checkTotalPL($filter,$branch);
													$budgeting = $rowgrandchild->checkTotalBudgeting($filter,$branch);
													
													if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
														$total_revenue_actual += $balance;
														$total_revenue_budget += $budgeting;
														$total_revenue_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,5) == '5.000' || substr($rowgrandchild->code,0,5) == '6.000' || substr($rowgrandchild->code,0,5) == '6.100'){
														$total_cogs_actual += $balance;
														$total_cogs_budget += $budgeting;
														$total_cogs_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,5) == '6.200'){
														$total_fixed_cost_actual += $balance;
														$total_fixed_cost_budget += $budgeting;
														$total_fixed_cost_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,9) == '6.2100.02'){
														$total_variable_cost_actual += $balance;
														$total_variable_cost_budget += $budgeting;
														$total_variable_cost_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,9) == '6.2100.03'){
														$total_other_expenses_actual += $balance;
														$total_other_expenses_budget += $budgeting;
														$total_other_expenses_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,6) == '6.2200'){
														$total_repair_expenses_actual += $balance;
														$total_repair_expenses_budget += $budgeting;
														$total_repair_expenses_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,5) == '6.300'){
														$total_depreciation_actual += $balance;
														$total_depreciation_budget += $budgeting;
														$total_depreciation_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,5) == '6.400'){
														$total_capex_actual += $balance;
														$total_capex_budget += $budgeting;
														$total_capex_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,5) == '7.100'){
														$total_other_income_actual += $balance;
														$total_other_income_budget += $budgeting;
														$total_other_income_variance += $balance - $budgeting;
													}
													
													if(substr($rowgrandchild->code,0,5) == '7.200'){
														$total_other_deduction_actual += $balance;
														$total_other_deduction_budget += $budgeting;
														$total_other_deduction_variance += $balance - $budgeting;
													}
											
												}
												
												foreach($rowgrandchild->child() as $rowgrandgrandchild){
													if(count($rowgrandgrandchild->child()) == 0){
														$balance = $rowgrandgrandchild->checkTotalPL($filter,$branch);
														$budgeting = $rowgrandgrandchild->checkTotalBudgeting($filter,$branch);
														
														if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
															$total_revenue_actual += $balance;
															$total_revenue_budget += $budgeting;
															$total_revenue_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '5.000' || substr($rowgrandgrandchild->code,0,5) == '6.000' || substr($rowgrandgrandchild->code,0,5) == '6.100'){
															$total_cogs_actual += $balance;
															$total_cogs_budget += $budgeting;
															$total_cogs_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '6.200'){
															$total_fixed_cost_actual += $balance;
															$total_fixed_cost_budget += $budgeting;
															$total_fixed_cost_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,9) == '6.2100.02'){
															$total_variable_cost_actual += $balance;
															$total_variable_cost_budget += $budgeting;
															$total_variable_cost_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,9) == '6.2100.03'){
															$total_other_expenses_actual += $balance;
															$total_other_expenses_budget += $budgeting;
															$total_other_expenses_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,6) == '6.2200'){
															$total_repair_expenses_actual += $balance;
															$total_repair_expenses_budget += $budgeting;
															$total_repair_expenses_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '6.300'){
															$total_depreciation_actual += $balance;
															$total_depreciation_budget += $budgeting;
															$total_depreciation_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '6.400'){
															$total_capex_actual += $balance;
															$total_capex_budget += $budgeting;
															$total_capex_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '7.100'){
															$total_other_income_actual += $balance;
															$total_other_income_budget += $budgeting;
															$total_other_income_variance += $balance - $budgeting;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '7.200'){
															$total_other_deduction_actual += $balance;
															$total_other_deduction_budget += $budgeting;
															$total_other_deduction_variance += $balance - $budgeting;
														}
													}
												}
											}
										}
									}
								}
							
							@endphp
							@foreach($coa->where('parent_id',0) as $rowparent)
								@php
									$total = 0;
									$totalbudgeting = 0;
									$totalvariance = 0;
								@endphp
								
								@if(substr($rowparent->code,0,1) == '4' || substr($rowparent->code,0,1) == '5' || substr($rowparent->code,0,1) == '6' || substr($rowparent->code,0,1) == '7')
								<tr class="font-weight-bold bg-grey-300">
									<td style="padding:10px;">{{ $rowparent->name }}</td>
									<td class="text-right">
										@php
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPL($filter,$branch);
											$total += $balance;
					
											echo number_format($balance, 2, ',', '.');
										}
										@endphp
									</td>
									<td class="text-center">
										@php
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPL($filter,$branch);
											
											$percentage = 0;
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
											}
											
											if(in_array(substr($rowparent->code,0,1),array('5','6','7'))){
												$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
											}

											echo number_format($percentage, 2,',','.').'%';
										}
										@endphp
									</td>
									<td class="text-right">
										@php
										if(count($rowparent->child()) == 0){
											$budgeting = $rowparent->checkTotalBudgeting($filter,$branch);
											$totalbudgeting += $budgeting;
											
											echo number_format($budgeting, 2, ',', '.');
										}
										@endphp
									</td>
									<td class="text-center">
										@php
										if(count($rowparent->child()) == 0){
											$budgeting = $rowparent->checkTotalBudgeting($filter,$branch);
											
											$percentage = 0;
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$percentage = $total_revenue_budget == 0 ? 0 : ($budgeting / $total_revenue_budget) * 100;
											}
											
											if(in_array(substr($rowparent->code,0,1),array('5','6','7'))){
												$percentage = $total_revenue_budget == 0 ? 0 : ($budgeting / $total_revenue_budget) * 100;
											}

											echo number_format($percentage, 2,',','.').'%';
										}
										@endphp
									</td>
									<td class="text-right hide">
										@php
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPL($filter,$branch);
											$budgeting = $rowparent->checkTotalBudgeting($filter,$branch);
											$totalvariance += $balance - $budgeting;
											
											echo number_format($balance - $budgeting, 2, ',', '.');
										}
										@endphp
									</td>
									<td class="text-center hide">
										@php
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPL($filter,$branch);
											$budgeting = $rowparent->checkTotalBudgeting($filter,$branch);
											
											$percentage = 0;
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$percentage = $budgeting == 0 ? 0 : (($balance-$budgeting) / $budgeting) * 100;
											}
											
											if(in_array(substr($rowparent->code,0,1),array('5','6','7'))){
												$percentage = $budgeting == 0 ? 0 : (($balance-$budgeting) / $budgeting) * 100;
											}

											echo number_format($percentage, 2,',','.').'%';
										}
										@endphp
									</td>
								</tr>
									@foreach($rowparent->child() as $rowchild)
										<tr class="font-weight-bold">
											<td style="padding-left:50px;">{{ $rowchild->name }}</td>
											<td class="text-right">
												@php
												if(count($rowchild->child()) == 0){
													$balance = $rowchild->checkTotalPL($filter,$branch);
													$total += $balance;
													
													echo number_format($balance, 2, ',', '.');
												}
												@endphp
											</td>
											<td class="text-center">
												@php
												if(count($rowchild->child()) == 0){
													$balance = $rowchild->checkTotalPL($filter,$branch);
													
													$percentage = 0;
													
													if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
														$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
													}
													
													if(in_array(substr($rowchild->code,0,1),array('5','6','7'))){
														$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
													}

													echo number_format($percentage, 2,',','.').'%';
												}
												@endphp
											</td>
											<td class="text-right" style="background-color:#cccccc;">
												@php
												if(count($rowchild->child()) == 0){
													$budgeting = $rowchild->checkTotalBudgeting($filter,$branch);
													$totalbudgeting += $budgeting;
													
													echo number_format($budgeting, 2, ',', '.');
												}
												@endphp
											</td>
											<td class="text-center" style="background-color:#cccccc;">
												@php
												if(count($rowchild->child()) == 0){
													$budgeting = $rowchild->checkTotalBudgeting($filter,$branch);
													
													$percentage = 0;
													
													if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
														$percentage = $total_revenue_budget == 0 ? 0 : ($budgeting / $total_revenue_budget) * 100;
													}
													
													if(in_array(substr($rowchild->code,0,1),array('5','6','7'))){
														$percentage = $total_revenue_budget == 0 ? 0 : ($budgeting / $total_revenue_budget) * 100;
													}

													echo number_format($percentage, 2,',','.').'%';
												}
												@endphp
											</td>
											<td class="text-right hide">
												@php
												if(count($rowchild->child()) == 0){
													$balance = $rowchild->checkTotalPL($filter,$branch);
													$budgeting = $rowchild->checkTotalBudgeting($filter,$branch);
													$totalvariance += $balance - $budgeting;
													
													echo number_format($balance - $budgeting, 2, ',', '.');
												}
												@endphp
											</td>
											<td class="text-center hide">
												@php
												if(count($rowchild->child()) == 0){
													$balance = $rowchild->checkTotalPL($filter,$branch);
													$budgeting = $rowchild->checkTotalBudgeting($filter,$branch);
													
													$percentage = 0;
													
													if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
														$percentage = $budgeting == 0 ? 0 : (($balance-$budgeting) / $budgeting) * 100;
													}
													
													if(in_array(substr($rowchild->code,0,1),array('5','6','7'))){
														$percentage = $budgeting == 0 ? 0 : (($balance-$budgeting) / $budgeting) * 100;
													}

													echo number_format($percentage, 2,',','.').'%';
												}
												@endphp
											</td>
										</tr>
										@foreach($rowchild->child() as $rowgrandchild)
											<tr class="font-weight-bold">
												<td style="padding-left:75px;">{{ $rowgrandchild->name }}</td>
												<td class="text-right">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balance = $rowgrandchild->checkTotalPL($filter,$branch);
														$total += $balance;
														
														echo number_format($balance, 2, ',', '.');
													}
													@endphp
												</td>
												<td class="text-center">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balance = $rowgrandchild->checkTotalPL($filter,$branch);
														
														$percentage = 0;
														
														if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
															$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
														}
														
														if(in_array(substr($rowgrandchild->code,0,1),array('5','6','7'))){
															$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
														}

														echo number_format($percentage, 2,',','.').'%';
													}
													@endphp
												</td>
												<td class="text-right" style="background-color:#cccccc;">
													@php
													if(count($rowgrandchild->child()) == 0){
														$budgeting = $rowgrandchild->checkTotalBudgeting($filter,$branch);
														$totalbudgeting += $budgeting;
														
														echo number_format($budgeting, 2, ',', '.');
													}
													@endphp
												</td>
												<td class="text-center" style="background-color:#cccccc;">
													@php
													if(count($rowgrandchild->child()) == 0){
														$budgeting = $rowgrandchild->checkTotalBudgeting($filter,$branch);
														
														$percentage = 0;
														
														if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
															$percentage = $total_revenue_budget == 0 ? 0 : ($budgeting / $total_revenue_budget) * 100;
														}
														
														if(in_array(substr($rowgrandchild->code,0,1),array('5','6','7'))){
															$percentage = $total_revenue_budget == 0 ? 0 : ($budgeting / $total_revenue_budget) * 100;
														}

														echo number_format($percentage, 2,',','.').'%';
													}
													@endphp
												</td>
												<td class="text-right hide">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balance = $rowgrandchild->checkTotalPL($filter,$branch);
														$budgeting = $rowgrandchild->checkTotalBudgeting($filter,$branch);
														$totalvariance += $balance - $budgeting;
														
														echo number_format($balance - $budgeting, 2, ',', '.');
													}
													@endphp
												</td>
												<td class="text-center hide">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balance = $rowgrandchild->checkTotalPL($filter,$branch);
														$budgeting = $rowgrandchild->checkTotalBudgeting($filter,$branch);
														
														$percentage = 0;
														
														if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
															$percentage = $budgeting == 0 ? 0 : (($balance-$budgeting) / $budgeting) * 100;
														}
														
														if(in_array(substr($rowgrandchild->code,0,1),array('5','6','7'))){
															$percentage = $budgeting == 0 ? 0 : (($balance-$budgeting) / $budgeting) * 100;
														}

														echo number_format($percentage, 2,',','.').'%';
													}
													@endphp
												</td>
											</tr>
											@foreach($rowgrandchild->child() as $rowgrandgrandchild)
												<tr class="font-weight-bold">
													<td style="padding-left:100px;">{{ $rowgrandgrandchild->name }}</td>
													<td class="text-right">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balance = $rowgrandgrandchild->checkTotalPL($filter,$branch);
															$total += $balance;
															
															echo number_format($balance, 2, ',', '.');
														}
														@endphp
													</td>
													<td class="text-center">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balance = $rowgrandgrandchild->checkTotalPL($filter,$branch);
															
															$percentage = 0;
															
															if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
																$percentage = ($balance / $total_revenue_actual) * 100;
															}
															
															if(in_array(substr($rowgrandgrandchild->code,0,1),array('5','6','7'))){
																$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
															}

															echo number_format($percentage, 2,',','.').'%';
														}
														@endphp
													</td>
													<td class="text-right" style="background-color:#cccccc;">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$budgeting = $rowgrandgrandchild->checkTotalBudgeting($filter,$branch);
															$totalbudgeting += $budgeting;
															
															echo number_format($budgeting, 2, ',', '.');
														}
														@endphp
													</td>
													<td class="text-center" style="background-color:#cccccc;">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$budgeting = $rowgrandgrandchild->checkTotalBudgeting($filter,$branch);
															
															$percentage = 0;
															
															if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
																$percentage = $total_revenue_budget == 0 ? 0 : ($budgeting / $total_revenue_budget) * 100;
															}
															
															if(in_array(substr($rowgrandgrandchild->code,0,1),array('5','6','7'))){
																$percentage = $total_revenue_budget == 0 ? 0 : ($budgeting / $total_revenue_budget) * 100;
															}

															echo number_format($percentage, 2,',','.').'%';
														}
														@endphp
													</td>
													<td class="text-right hide">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balance = $rowgrandgrandchild->checkTotalPL($filter,$branch);
															$budgeting = $rowgrandgrandchild->checkTotalBudgeting($filter,$branch);
															$totalvariance += $balance - $budgeting;
															
															echo number_format($balance - $budgeting, 2, ',', '.');
														}
														@endphp
													</td>
													<td class="text-center hide">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balance = $rowgrandgrandchild->checkTotalPL($filter,$branch);
															$budgeting = $rowgrandgrandchild->checkTotalBudgeting($filter,$branch);
															
															$percentage = 0;
															
															if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
																$percentage = $budgeting == 0 ? 0 : (($balance-$budgeting) / $budgeting) * 100;
															}
															
															if(in_array(substr($rowgrandgrandchild->code,0,1),array('5','6','7'))){
																$percentage = $budgeting == 0 ? 0 : (($balance-$budgeting) / $budgeting) * 100;
															}

															echo number_format($percentage, 2,',','.').'%';
														}
														@endphp
													</td>
												</tr>
											@endforeach
										@endforeach
									@endforeach
								
								<tr class="font-weight-bold bg-brown-300" style="font-size:15px;">
									<td style="padding:7px;">Total {{ $rowparent->name }}</td>
									<td class="text-right">
										{{ number_format($total, 2, ',', '.') }}
									</td>
									<td class="text-center">
										{{ number_format(($total_revenue_actual == 0 ? 0 : $total / $total_revenue_actual) * 100, 2, ',', '.') }}%
									</td>
									<td class="text-right">
										{{ number_format($totalbudgeting, 2, ',', '.') }}
									</td>
									<td class="text-center">
										{{ number_format($total_revenue_budget == 0 ? 0 : ($totalbudgeting / $total_revenue_budget) * 100, 2, ',', '.') }}%
									</td>
									<td class="text-right hide">
										{{ number_format($totalvariance, 2, ',', '.') }}
									</td>
									<td class="text-center hide">
										{{ number_format($totalbudgeting == 0 ? 0 : ($totalvariance / $totalbudgeting) * 100, 2, ',', '.') }}%
									</td>
								</tr>
								<tr class="font-weight-bold" style="font-size:15px;border-right: hidden !important;border-left: hidden !important;">
									<td colspan="7">&nbsp;</td>
								</tr>
								@endif
							@endforeach
					   </tbody>
					   <tfoot style="border:1px solid black;">
							<tr class="bg-primary font-weight-bold" style="font-size:15px;border:1px solid black;">
								<td style="border:1px solid black;">Nett Profit (Loss)</td>
								<td style="border:1px solid black;" class="text-right">
									{{ number_format($total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual - $total_capex_actual + $total_other_income_actual - $total_other_deduction_actual, 2, ',', '.') }}
								</td>
								<td style="border:1px solid black;" class="text-center">
									{{ number_format($total_revenue_actual == 0 ? 0 : (($total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual - $total_capex_actual + $total_other_income_actual - $total_other_deduction_actual) / $total_revenue_actual) * 100, 2, ',', '.') }}%
								</td>
								<td style="border:1px solid black;" class="text-right">
									{{ number_format($total_revenue_budget - $total_cogs_budget - $total_fixed_cost_budget - $total_variable_cost_budget - $total_other_expenses_budget - $total_repair_expenses_budget - $total_depreciation_budget - $total_capex_budget + $total_other_income_budget - $total_other_deduction_budget, 2, ',', '.') }}
								</td>
								<td style="border:1px solid black;" class="text-center">
									{{ number_format($total_revenue_budget == 0 ? 0 : (($total_revenue_budget - $total_cogs_budget - $total_fixed_cost_budget - $total_variable_cost_budget - $total_other_expenses_budget - $total_repair_expenses_budget - $total_depreciation_budget - $total_capex_budget + $total_other_income_budget - $total_other_deduction_budget) / $total_revenue_budget) * 100, 2, ',', '.') }}%
								</td>
								<td style="border:1px solid black;" class="text-right hide">
									{{ number_format($total_revenue_variance - $total_cogs_variance - $total_fixed_cost_variance - $total_variable_cost_variance - $total_other_expenses_variance - $total_repair_expenses_variance - $total_depreciation_variance - $total_capex_variance + $total_other_income_variance - $total_other_deduction_variance, 2, ',', '.') }}
								</td>
								<td style="border:1px solid black;" class="text-center hide">
									{{ number_format( $total_revenue_budget == 0 ? 0 : (($total_revenue_variance - $total_cogs_variance - $total_fixed_cost_variance - $total_variable_cost_variance - $total_other_expenses_variance - $total_repair_expenses_variance - $total_depreciation_variance - $total_capex_variance + $total_other_income_variance - $total_other_deduction_variance) / ($total_revenue_budget - $total_cogs_budget - $total_fixed_cost_budget - $total_variable_cost_budget - $total_other_expenses_budget - $total_repair_expenses_budget - $total_depreciation_budget - $total_capex_budget + $total_other_income_budget - $total_other_deduction_budget)) * 100, 2, ',', '.') }}%
								</td>
							</tr>
						</tfoot>
					</table>
					@endif
					@if($mode == '2')
					@php
						$year1 = date('Y', strtotime($filter_start));
						$year2 = date('Y', strtotime($filter_end));
						$month1 = date('n',strtotime($filter_start));
						$month2 = date('n',strtotime($filter_end));
						$loopstart = strtotime($filter_start);
						$loopend = strtotime($filter_end);
						
						$diffmonth = (($year2 - $year1) * 12) + ($month2 - $month1) + 1;
						
						$totalcolumn = [];
						$total_revenue = [];
						$total_cogs = [];	
						$total_fixed_cost = [];
						$total_variable_cost = [];
						$total_other_expenses = [];
						$total_repair_expenses = [];
						$total_depreciation = [];
						$total_other_income = [];
						$total_other_deduction = [];
						
						$totalall = 0;
						$totalallrevenue = 0;
					@endphp
					<table class="table table-sm table-bordered">
					   <thead>
						  <tr class="text-center bg-primary font-weight-bold" style="font-size:18px;">
							 <th rowspan="2" width="400px">Description</th>
							 <th colspan="{{ $diffmonth * 2 }}">Month</th>
							 <th rowspan="2" width="400px">Total</th>
							 <th rowspan="2">%</th>
						  </tr>
						  <tr class="text-center bg-primary font-weight-bold" style="font-size:18px;">
							@php
								$loopstart = strtotime($filter_start);
								$loopend = strtotime($filter_end);
							@endphp
							 @while($loopstart <= $loopend)
								<th style="min-width:200px;">{{ date('M Y', $loopstart) }}</th>
								<th style="min-width:25px;">%</th>
								@php
									$total_revenue[$loopstart] = 0;
									$total_cogs[$loopstart] = 0;	
									$total_fixed_cost[$loopstart] = 0;
									$total_variable_cost[$loopstart] = 0;
									$total_other_expenses[$loopstart] = 0;
									$total_repair_expenses[$loopstart] = 0;
									$total_depreciation[$loopstart] = 0;
									$total_capex[$loopstart] = 0;
									$total_other_income[$loopstart] = 0;
									$total_other_deduction[$loopstart] = 0;
									
									$loopstart = strtotime("+1 month", $loopstart);
								@endphp
							 @endwhile
						  </tr>
					   </thead>
					   <tbody>
							@php
								foreach($coa->where('parent_id',0) as $rowparent){
									$loopstart = strtotime($filter_start);
									$loopend = strtotime($filter_end);
									
									while($loopstart <= $loopend){
										$balance = $rowparent->checkTotalPL(date('Y-m',$loopstart),$branch);
										
										if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
											$total_revenue[$loopstart] += $balance;
											$totalallrevenue += $balance;
										}
										
										if(substr($rowparent->code,0,5) == '5.000' || substr($rowparent->code,0,5) == '6.000' || substr($rowparent->code,0,5) == '6.100'){
											$total_cogs[$loopstart] += $balance;
										}
										
										if(substr($rowparent->code,0,5) == '6.200'){
											$total_fixed_cost[$loopstart] += $balance;
										}
										
										if(substr($rowparent->code,0,9) == '6.2100.02'){
											$total_variable_cost[$loopstart] += $balance;
										}
										
										if(substr($rowparent->code,0,9) == '6.2100.03'){
											$total_other_expenses[$loopstart] += $balance;
										}
										
										if(substr($rowparent->code,0,6) == '6.2200'){
											$total_repair_expenses[$loopstart] += $balance;
										}
										
										if(substr($rowparent->code,0,5) == '6.300'){
											$total_depreciation[$loopstart] += $balance;
										}
										
										if(substr($rowparent->code,0,5) == '6.400'){
											$total_capex[$loopstart] += $balance;
										}
										
										if(substr($rowparent->code,0,5) == '7.100'){
											$total_other_income[$loopstart] += $balance;
										}
										
										if(substr($rowparent->code,0,5) == '7.200'){
											$total_other_deduction[$loopstart] += $balance;
										}
										
										$loopstart = strtotime("+1 month", $loopstart);
									}
									
									foreach($rowparent->child() as $rowchild){
										$loopstart = strtotime($filter_start);
										$loopend = strtotime($filter_end);
										
										while($loopstart <= $loopend){
											$balance = $rowchild->checkTotalPL(date('Y-m',$loopstart),$branch);
											
											if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
												$total_revenue[$loopstart] += $balance;
												$totalallrevenue += $balance;
											}
											
											if(substr($rowchild->code,0,5) == '5.000' || substr($rowchild->code,0,5) == '6.000' || substr($rowchild->code,0,5) == '6.100'){
												$total_cogs[$loopstart] += $balance;
											}
											
											if(substr($rowchild->code,0,5) == '6.200'){
												$total_fixed_cost[$loopstart] += $balance;
											}
											
											if(substr($rowchild->code,0,9) == '6.2100.02'){
												$total_variable_cost[$loopstart] += $balance;
											}
											
											if(substr($rowchild->code,0,9) == '6.2100.03'){
												$total_other_expenses[$loopstart] += $balance;
											}
											
											if(substr($rowchild->code,0,6) == '6.2200'){
												$total_repair_expenses[$loopstart] += $balance;
											}
											
											if(substr($rowchild->code,0,5) == '6.300'){
												$total_depreciation[$loopstart] += $balance;
											}
											
											if(substr($rowchild->code,0,5) == '6.400'){
												$total_capex[$loopstart] += $balance;
											}
											
											if(substr($rowchild->code,0,5) == '7.100'){
												$total_other_income[$loopstart] += $balance;
											}
											
											if(substr($rowchild->code,0,5) == '7.200'){
												$total_other_deduction[$loopstart] += $balance;
											}
											
											$loopstart = strtotime("+1 month", $loopstart);
										}
										
										foreach($rowchild->child() as $rowgrandchild){
											$loopstart = strtotime($filter_start);
											$loopend = strtotime($filter_end);
											
											while($loopstart <= $loopend){
												$balance = $rowgrandchild->checkTotalPL(date('Y-m',$loopstart),$branch);
												if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
													$total_revenue[$loopstart] += $balance;
													$totalallrevenue += $balance;
												}
												
												if(substr($rowgrandchild->code,0,5) == '5.000' || substr($rowgrandchild->code,0,5) == '6.000' || substr($rowgrandchild->code,0,5) == '6.100'){
													$total_cogs[$loopstart] += $balance;
												}
												
												if(substr($rowgrandchild->code,0,5) == '6.200'){
													$total_fixed_cost[$loopstart] += $balance;
												}
												
												if(substr($rowgrandchild->code,0,9) == '6.2100.02'){
													$total_variable_cost[$loopstart] += $balance;
												}
												
												if(substr($rowgrandchild->code,0,9) == '6.2100.03'){
													$total_other_expenses[$loopstart] += $balance;
												}
												
												if(substr($rowgrandchild->code,0,6) == '6.2200'){
													$total_repair_expenses[$loopstart] += $balance;
												}
												
												if(substr($rowgrandchild->code,0,5) == '6.300'){
													$total_depreciation[$loopstart] += $balance;
												}
												
												if(substr($rowgrandchild->code,0,5) == '6.400'){
													$total_capex[$loopstart] += $balance;
												}
												
												if(substr($rowgrandchild->code,0,5) == '7.100'){
													$total_other_income[$loopstart] += $balance;
												}
												
												if(substr($rowgrandchild->code,0,5) == '7.200'){
													$total_other_deduction[$loopstart] += $balance;
												}
												
												$loopstart = strtotime("+1 month", $loopstart);
											}
											
											foreach($rowgrandchild->child() as $rowgrandgrandchild){
												$loopstart = strtotime($filter_start);
												$loopend = strtotime($filter_end);
												while($loopstart <= $loopend){
													$balance = $rowgrandgrandchild->checkTotalPL(date('Y-m',$loopstart),$branch);
													if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
														$total_revenue[$loopstart] += $balance;
														$totalallrevenue += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,5) == '5.000' || substr($rowgrandgrandchild->code,0,5) == '6.000' || substr($rowgrandgrandchild->code,0,5) == '6.100'){
														$total_cogs[$loopstart] += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,5) == '6.200'){
														$total_fixed_cost[$loopstart] += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,9) == '6.2100.02'){
														$total_variable_cost[$loopstart] += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,9) == '6.2100.03'){
														$total_other_expenses[$loopstart] += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,6) == '6.2200'){
														$total_repair_expenses[$loopstart] += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,5) == '6.300'){
														$total_depreciation[$loopstart] += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,5) == '6.400'){
														$total_capex[$loopstart] += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,5) == '7.100'){
														$total_other_income[$loopstart] += $balance;
													}
													
													if(substr($rowgrandgrandchild->code,0,5) == '7.200'){
														$total_other_deduction[$loopstart] += $balance;
													}
													
													$loopstart = strtotime("+1 month", $loopstart);
												}
											}
										}
									}
								}
							@endphp
					   
							@foreach($coa->where('parent_id',0) as $rowparent)
								
								@php
									$totaleachparent = 0;
									$loopstart = strtotime($filter_start);
									$loopend = strtotime($filter_end);
									
									while($loopstart <= $loopend){
										$totalcolumn[$loopstart] = 0;
										$loopstart = strtotime("+1 month", $loopstart);
									}
								@endphp
								
								@if(substr($rowparent->code,0,1) == '4' || substr($rowparent->code,0,1) == '5' || substr($rowparent->code,0,1) == '6' || substr($rowparent->code,0,1) == '7')
								<tr class="font-weight-bold bg-grey-300">
									<td style="padding:10px;">{{ $rowparent->name }}</td>
									@php
										$loopstart = strtotime($filter_start);
										$loopend = strtotime($filter_end);
										$totalrow = 0;
									@endphp
									@while($loopstart <= $loopend)
										@php
											$balance = $rowparent->checkTotalPL(date('Y-m',$loopstart),$branch);
											$totalcolumn[$loopstart] += $balance;
											$totalrow += $balance;
											$totaleachparent += $balance;
										@endphp
										<td style="min-width:200px;" class="text-right">
											{{ number_format($balance,2,',','.') }}
										</td>
										<td style="min-width:25px;" class="text-center">
											{{ $total_revenue[$loopstart] > 0 ? round($balance / $total_revenue[$loopstart] * 100,2) : 0 }}%
										</td>
										@php
											$loopstart = strtotime("+1 month", $loopstart);
										@endphp
									@endwhile
									<td style="min-width:200px;" class="text-right">
										{{ number_format($totalrow,2,',','.') }}
									</td>
									<td style="min-width:25px;" class="text-center">
										{{ $totalallrevenue > 0 ? round($totalrow / $totalallrevenue * 100,2) : 0 }}%
									</td>
								</tr>
									@foreach($rowparent->child() as $rowchild)
										<tr class="font-weight-bold">
											<td style="padding-left:50px;">{{ $rowchild->name }}</td>
											@php
												$loopstart = strtotime($filter_start);
												$loopend = strtotime($filter_end);
												$totalrow = 0;
											@endphp
											@while($loopstart <= $loopend)
												@php
													$balance = $rowchild->checkTotalPL(date('Y-m',$loopstart),$branch);
													$totalcolumn[$loopstart] += $balance;
													$totalrow += $balance;
													$totaleachparent += $balance;
												@endphp
												<td style="min-width:200px;" class="text-right">
													{{ number_format($balance,2,',','.') }}
												</td>
												<td style="min-width:25px;" class="text-center">
													{{ $total_revenue[$loopstart] > 0 ? round($balance / $total_revenue[$loopstart] * 100,2) : 0 }}%
												</td>
												@php
													$loopstart = strtotime("+1 month", $loopstart);
												@endphp
											@endwhile
											<td style="min-width:200px;" class="text-right">
												{{ number_format($totalrow,2,',','.') }}
											</td>
											<td style="min-width:25px;" class="text-center">
												{{ $totalallrevenue > 0 ? round($totalrow / $totalallrevenue * 100,2) : 0 }}%
											</td>
										</tr>
										@foreach($rowchild->child() as $rowgrandchild)
											<tr class="font-weight-bold">
												<td style="padding-left:75px;">{{ $rowgrandchild->name }}</td>
												@php
													$loopstart = strtotime($filter_start);
													$loopend = strtotime($filter_end);
													$totalrow = 0;
												@endphp
												@while($loopstart <= $loopend)
													@php
														$balance = $rowgrandchild->checkTotalPL(date('Y-m',$loopstart),$branch);
														$totalcolumn[$loopstart] += $balance;
														$totalrow += $balance;
														$totaleachparent += $balance;
													@endphp
													<td style="min-width:200px;" class="text-right">
														{{ number_format($balance,2,',','.') }}
													</td>
													<td style="min-width:25px;" class="text-center">
														{{ $total_revenue[$loopstart] > 0 ? round($balance / $total_revenue[$loopstart] * 100,2) : 0 }}%
													</td>
													@php
														$loopstart = strtotime("+1 month", $loopstart);
													@endphp
												@endwhile
												<td style="min-width:200px;" class="text-right">
													{{ number_format($totalrow,2,',','.') }}
												</td>
												<td style="min-width:25px;" class="text-center">
													{{ $totalallrevenue > 0 ? round($totalrow / $totalallrevenue * 100,2) : 0 }}%
												</td>
											</tr>
											@foreach($rowgrandchild->child() as $rowgrandgrandchild)
												<tr class="font-weight-bold">
													<td style="padding-left:100px;">{{ $rowgrandgrandchild->name }}</td>
													@php
														$loopstart = strtotime($filter_start);
														$loopend = strtotime($filter_end);
														$totalrow = 0;
													@endphp
													@while($loopstart <= $loopend)
														@php
															$balance = $rowgrandgrandchild->checkTotalPL(date('Y-m',$loopstart),$branch);
															$totalcolumn[$loopstart] += $balance;
															$totalrow += $balance;
															$totaleachparent += $balance;
														@endphp
														<td style="min-width:200px;" class="text-right">
															{{ number_format($balance,2,',','.') }}
														</td>
														<td style="min-width:25px;" class="text-center">
															{{ $total_revenue[$loopstart] > 0 ? round($balance / $total_revenue[$loopstart] * 100,2) : 0 }}%
														</td>
														@php
															$loopstart = strtotime("+1 month", $loopstart);
														@endphp
													@endwhile
													<td style="min-width:200px;" class="text-right">
														{{ number_format($totalrow,2,',','.') }}
													</td>
													<td style="min-width:25px;" class="text-center">
														{{ $totalallrevenue > 0 ? round($totalrow / $totalallrevenue * 100,2) : 0 }}%
													</td>
												</tr>
											@endforeach
										@endforeach
									@endforeach
									<tr class="font-weight-bold bg-brown-300" style="font-size:15px;">
										<td style="padding:7px;">Total {{ $rowparent->name }}</td>
										@php
											$loopstart = strtotime($filter_start);
											$loopend = strtotime($filter_end);
										@endphp
										@while($loopstart <= $loopend)
											<td style="min-width:200px;" class="text-right">
												{{ number_format($totalcolumn[$loopstart],2,',','.') }}
											</td>
											<td style="min-width:25px;" class="text-center">
												{{ $total_revenue[$loopstart] > 0 ? round($totalcolumn[$loopstart] / $total_revenue[$loopstart] * 100,2) : 0 }}%
											</td>
											@php
												$loopstart = strtotime("+1 month", $loopstart);
											@endphp
										@endwhile
										<td style="min-width:200px;" class="text-right">
											{{ number_format($totaleachparent,2,',','.') }}
										</td>
										<td style="min-width:25px;" class="text-center">
											{{ $totalallrevenue > 0 ? round($totaleachparent / $totalallrevenue * 100,2) : 0 }}%
										</td>
									</tr>
									<tr class="font-weight-bold" style="font-size:15px;border-right: hidden !important;border-left: hidden !important;">
										<td colspan="{{ ($diffmonth * 2) + 3 }}">&nbsp;</td>
									</tr>
								@endif
							@endforeach
					   </tbody>
					   <tfoot style="border:1px solid black;">
							<tr class="bg-primary font-weight-bold" style="font-size:15px;border:1px solid black;">
								<td style="border:1px solid black;">Nett Profit (Loss)</td>
								@php
									$loopstart = strtotime($filter_start);
									$loopend = strtotime($filter_end);
								@endphp
								@while($loopstart <= $loopend)
								<td style="border:1px solid black;" class="text-right">
									{{ number_format($total_revenue[$loopstart] - $total_cogs[$loopstart] - $total_fixed_cost[$loopstart] - $total_variable_cost[$loopstart] - $total_other_expenses[$loopstart] - $total_repair_expenses[$loopstart] - $total_depreciation[$loopstart] - $total_capex[$loopstart] + $total_other_income[$loopstart] - $total_other_deduction[$loopstart], 2, ',', '.') }}
								</td>
								<td style="border:1px solid black;" class="text-center">
									{{ $total_revenue[$loopstart] > 0 ? round(($total_revenue[$loopstart] - $total_cogs[$loopstart] - $total_fixed_cost[$loopstart] - $total_variable_cost[$loopstart] - $total_other_expenses[$loopstart] - $total_repair_expenses[$loopstart] - $total_depreciation[$loopstart] - $total_capex[$loopstart] + $total_other_income[$loopstart] - $total_other_deduction[$loopstart]) / $total_revenue[$loopstart] * 100,2) : 0 }}%
								</td>
									@php
										$totalall += $total_revenue[$loopstart] - $total_cogs[$loopstart] - $total_fixed_cost[$loopstart] - $total_variable_cost[$loopstart] - $total_other_expenses[$loopstart] - $total_repair_expenses[$loopstart] - $total_depreciation[$loopstart] - $total_capex[$loopstart] + $total_other_income[$loopstart] - $total_other_deduction[$loopstart];
										$loopstart = strtotime("+1 month", $loopstart);
									@endphp
								@endwhile
								<td style="min-width:200px;" class="text-right">
									{{ number_format($totalall,2,',','.') }}
								</td>
								<td style="min-width:25px;" class="text-center">
									{{ $totalallrevenue > 0 ? round($totalall / $totalallrevenue * 100,2) : 0 }}%
								</td>
							</tr>
						</tfoot>
					</table>
					@endif
					@if($mode == '4')
					<table class="table table-sm table-bordered fixed_header">
					   <thead>
						  <tr class="text-center bg-primary font-weight-bold" style="font-size:18px;">
							 <th>Description</th>
							 <th>Nominal</th>
							 <th>%</th>
						  </tr>
					   </thead>
					   <tbody>
							@php
								$total_revenue_actual = 0;
								$total_cogs_actual = 0;	
								$total_fixed_cost_actual = 0;
								$total_variable_cost_actual = 0;
								$total_other_expenses_actual = 0;
								$total_repair_expenses_actual = 0;
								$total_depreciation_actual = 0;
								$total_capex_actual = 0;
								$total_other_income_actual = 0;
								$total_other_deduction_actual = 0;
								
								foreach($coa->where('parent_id',0) as $rowparent){
									if(substr($rowparent->code,0,1) == '4' || substr($rowparent->code,0,1) == '5' || substr($rowparent->code,0,1) == '6' || substr($rowparent->code,0,1) == '7'){
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPLDate($date_start,$date_end,$branch);
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$total_revenue_actual += $balance;
											}
											
											if(substr($rowparent->code,0,5) == '5.000' || substr($rowparent->code,0,5) == '6.000' || substr($rowparent->code,0,5) == '6.100'){
												$total_cogs_actual += $balance;
											}
											
											if(substr($rowparent->code,0,5) == '6.200'){
												$total_fixed_cost_actual += $balance;
											}
											
											if(substr($rowparent->code,0,9) == '6.2100.02'){
												$total_variable_cost_actual += $balance;
											}
											
											if(substr($rowparent->code,0,9) == '6.2100.03'){
												$total_other_expenses_actual += $balance;
											}
											
											if(substr($rowparent->code,0,6) == '6.2200'){
												$total_repair_expenses_actual += $balance;
											}
											
											if(substr($rowparent->code,0,5) == '6.300'){
												$total_depreciation_actual += $balance;
											}
											
											if(substr($rowparent->code,0,5) == '6.400'){
												$total_capex_actual += $balance;
											}
											
											if(substr($rowparent->code,0,5) == '7.100'){
												$total_other_income_actual += $balance;
											}
											
											if(substr($rowparent->code,0,5) == '7.200'){
												$total_other_deduction_actual += $balance;
											}
										}
										
										foreach($rowparent->child() as $rowchild){
											if(count($rowchild->child()) == 0){
												$balance = $rowchild->checkTotalPLDate($date_start,$date_end,$branch);
												
												if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
													$total_revenue_actual += $balance;
												}
												
												if(substr($rowchild->code,0,5) == '5.000' || substr($rowchild->code,0,5) == '6.000' || substr($rowchild->code,0,5) == '6.100'){
													$total_cogs_actual += $balance;
												}
												
												if(substr($rowchild->code,0,5) == '6.200'){
													$total_fixed_cost_actual += $balance;
												}
												
												if(substr($rowchild->code,0,9) == '6.2100.02'){
													$total_variable_cost_actual += $balance;
												}
												
												if(substr($rowchild->code,0,9) == '6.2100.03'){
													$total_other_expenses_actual += $balance;
												}
												
												if(substr($rowchild->code,0,6) == '6.2200'){
													$total_repair_expenses_actual += $balance;
												}
												
												if(substr($rowchild->code,0,5) == '6.300'){
													$total_depreciation_actual += $balance;
												}
												
												if(substr($rowchild->code,0,5) == '6.400'){
													$total_capex_actual += $balance;
												}
												
												if(substr($rowchild->code,0,5) == '7.100'){
													$total_other_income_actual += $balance;
												}
												
												if(substr($rowchild->code,0,5) == '7.200'){
													$total_other_deduction_actual += $balance;
												}
											}
											
											foreach($rowchild->child() as $rowgrandchild){
												if(count($rowgrandchild->child()) == 0){
													$balance = $rowgrandchild->checkTotalPLDate($date_start,$date_end,$branch);
													
													if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
														$total_revenue_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,5) == '5.000' || substr($rowgrandchild->code,0,5) == '6.000' || substr($rowgrandchild->code,0,5) == '6.100'){
														$total_cogs_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,5) == '6.200'){
														$total_fixed_cost_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,9) == '6.2100.02'){
														$total_variable_cost_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,9) == '6.2100.03'){
														$total_other_expenses_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,6) == '6.2200'){
														$total_repair_expenses_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,5) == '6.300'){
														$total_depreciation_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,5) == '6.400'){
														$total_capex_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,5) == '7.100'){
														$total_other_income_actual += $balance;
													}
													
													if(substr($rowgrandchild->code,0,5) == '7.200'){
														$total_other_deduction_actual += $balance;
													}
											
												}
												
												foreach($rowgrandchild->child() as $rowgrandgrandchild){
													if(count($rowgrandgrandchild->child()) == 0){
														$balance = $rowgrandgrandchild->checkTotalPLDate($date_start,$date_end,$branch);
														
														if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
															$total_revenue_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '5.000' || substr($rowgrandgrandchild->code,0,5) == '6.000' || substr($rowgrandgrandchild->code,0,5) == '6.100'){
															$total_cogs_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '6.200'){
															$total_fixed_cost_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,9) == '6.2100.02'){
															$total_variable_cost_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,9) == '6.2100.03'){
															$total_other_expenses_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,6) == '6.2200'){
															$total_repair_expenses_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '6.300'){
															$total_depreciation_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '6.400'){
															$total_capex_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '7.100'){
															$total_other_income_actual += $balance;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '7.200'){
															$total_other_deduction_actual += $balance;
														}
													}
												}
											}
										}
									}
								}
							
							@endphp
							@foreach($coa->where('parent_id',0) as $rowparent)
								@php
									$total = 0;
								@endphp
								
								@if(substr($rowparent->code,0,1) == '4' || substr($rowparent->code,0,1) == '5' || substr($rowparent->code,0,1) == '6' || substr($rowparent->code,0,1) == '7')
								<tr class="font-weight-bold bg-grey-300">
									<td style="padding:10px;">{{ $rowparent->name }}</td>
									<td class="text-right">
										<a href="{{ url("admin/report/accounting/ledger")."?coa_id=".$rowparent->id."&start=".$date_start."&end=".$date_end."&branch=".$branch }}" target="_blank">
										@php
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPLDate($date_start,$date_end,$branch);
											$total += $balance;
					
											echo number_format($balance, 2, ',', '.');
										}
										@endphp
										</a>
									</td>
									<td class="text-center">
										@php
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPLDate($date_start,$date_end,$branch);
											
											$percentage = 0;
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
											}
											
											if(in_array(substr($rowparent->code,0,1),array('5','6','7'))){
												$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
											}

											echo number_format($percentage, 2,',','.').'%';
										}
										@endphp
									</td>
								</tr>
									@foreach($rowparent->child() as $rowchild)
										<tr class="font-weight-bold">
											<td style="padding-left:50px;">{{ $rowchild->name }}</td>
											<td class="text-right">
												<a href="{{ url("admin/report/accounting/ledger")."?coa_id=".$rowchild->id."&start=".$date_start."&end=".$date_end."&branch=".$branch }}" target="_blank">
												@php
												if(count($rowchild->child()) == 0){
													$balance = $rowchild->checkTotalPLDate($date_start,$date_end,$branch);
													$total += $balance;
													
													echo number_format($balance, 2, ',', '.');
												}
												@endphp
												</a>
											</td>
											<td class="text-center">
												@php
												if(count($rowchild->child()) == 0){
													$balance = $rowchild->checkTotalPLDate($date_start,$date_end,$branch);
													
													$percentage = 0;
													
													if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
														$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
													}
													
													if(in_array(substr($rowchild->code,0,1),array('5','6','7'))){
														$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
													}

													echo number_format($percentage, 2,',','.').'%';
												}
												@endphp
											</td>
										</tr>
										@foreach($rowchild->child() as $rowgrandchild)
											<tr class="font-weight-bold">
												<td style="padding-left:75px;">{{ $rowgrandchild->name }}</td>
												<td class="text-right">
													<a href="{{ url("admin/report/accounting/ledger")."?coa_id=".$rowgrandchild->id."&start=".$date_start."&end=".$date_end."&branch=".$branch }}" target="_blank">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balance = $rowgrandchild->checkTotalPLDate($date_start,$date_end,$branch);
														$total += $balance;
														
														echo number_format($balance, 2, ',', '.');
													}
													@endphp
													</a>
												</td>
												<td class="text-center">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balance = $rowgrandchild->checkTotalPLDate($date_start,$date_end,$branch);
														
														$percentage = 0;
														
														if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
															$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
														}
														
														if(in_array(substr($rowgrandchild->code,0,1),array('5','6','7'))){
															$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
														}

														echo number_format($percentage, 2,',','.').'%';
													}
													@endphp
												</td>
											</tr>
											@foreach($rowgrandchild->child() as $rowgrandgrandchild)
												<tr class="font-weight-bold">
													<td style="padding-left:100px;">{{ $rowgrandgrandchild->name }}</td>
													<td class="text-right">
														<a href="{{ url("admin/report/accounting/ledger")."?coa_id=".$rowgrandgrandchild->id."&start=".$date_start."&end=".$date_end."&branch=".$branch }}" target="_blank">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balance = $rowgrandgrandchild->checkTotalPLDate($date_start,$date_end,$branch);
															$total += $balance;
															
															echo number_format($balance, 2, ',', '.');
														}
														@endphp
														</a>
													</td>
													<td class="text-center">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balance = $rowgrandgrandchild->checkTotalPLDate($date_start,$date_end,$branch);
															
															$percentage = 0;
															
															if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
																$percentage = ($balance / $total_revenue_actual) * 100;
															}
															
															if(in_array(substr($rowgrandgrandchild->code,0,1),array('5','6','7'))){
																$percentage = $total_revenue_actual == 0 ? 0 : ($balance / $total_revenue_actual) * 100;
															}

															echo number_format($percentage, 2,',','.').'%';
														}
														@endphp
													</td>
												</tr>
											@endforeach
										@endforeach
									@endforeach
								
								<tr class="font-weight-bold bg-brown-300" style="font-size:15px;">
									<td style="padding:7px;">Total {{ $rowparent->name }}</td>
									<td class="text-right">
										{{ number_format($total, 2, ',', '.') }}
									</td>
									<td class="text-center">
										{{ number_format(($total_revenue_actual == 0 ? 0 : $total / $total_revenue_actual) * 100, 2, ',', '.') }}%
									</td>
								</tr>
								<tr class="font-weight-bold" style="font-size:15px;border-right: hidden !important;border-left: hidden !important;">
									<td colspan="7">&nbsp;</td>
								</tr>
								@endif
							@endforeach
					   </tbody>
					   <tfoot style="border:1px solid black;">
							<tr class="bg-primary font-weight-bold" style="font-size:15px;border:1px solid black;">
								<td style="border:1px solid black;">Nett Profit (Loss)</td>
								<td style="border:1px solid black;" class="text-right">
									{{ number_format($total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual - $total_capex_actual + $total_other_income_actual - $total_other_deduction_actual, 2, ',', '.') }}
								</td>
								<td style="border:1px solid black;" class="text-center">
									{{ number_format($total_revenue_actual == 0 ? 0 : (($total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual - $total_capex_actual + $total_other_income_actual - $total_other_deduction_actual) / $total_revenue_actual) * 100, 2, ',', '.') }}%
								</td>
							</tr>
						</tfoot>
					</table>
					@endif
				 </div>
			</div>
		</div>
	</div>
	
	@php
		/* if($mode == '1' && $branch){
			$finalnett = $total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual - $total_capex_actual + $total_other_income_actual - $total_other_deduction_actual;
			
			SMB::updateRetainedEarning($filter,$branch,$finalnett); 
		} */
	@endphp
	
<script>
   $(function() {
		$('.sidebar-main-toggle').click();
		
		@if($mode == '1')
			$('.mode2,.mode3,.mode4').addClass('d-none');
			$('.mode1').removeClass('d-none');
		@elseif($mode == '2')
			$('.mode1,.mode3,.mode4').addClass('d-none');
			$('.mode2').removeClass('d-none');
		@elseif($mode == '3')
			$('.mode1,.mode2,.mode4').addClass('d-none');
			$('.mode3').removeClass('d-none');
		@elseif($mode == '4')
			$('.mode1,.mode2,.mode3').addClass('d-none');
			$('.mode4').removeClass('d-none');
		@endif
   });
	
	function changeMode(val){
		if(val == '1'){
			$('.mode1').removeClass('d-none');
			$('.mode2').addClass('d-none');
			$('.mode3').addClass('d-none');
			$('.mode4').addClass('d-none');
		}else if(val == '2'){
			$('.mode2').removeClass('d-none');
			$('.mode1').addClass('d-none');
			$('.mode3').addClass('d-none');
			$('.mode4').addClass('d-none');
		}else if(val == '3'){
			$('.mode3').removeClass('d-none');
			$('.mode1').addClass('d-none');
			$('.mode2').addClass('d-none');
			$('.mode4').addClass('d-none');
		}else if(val == '4'){
			$('.mode4').removeClass('d-none');
			$('.mode1').addClass('d-none');
			$('.mode2').addClass('d-none');
			$('.mode3').addClass('d-none');
		}
	}
	
	function submitFilter() {
		loadingOpen('.content');
		$("#form_filter").submit(function(e){
			var lolos = true;
			
			if($('#mode').val() == '1'){
				
			}else if($('#mode').val() == '2'){
				if($('#filter_start').val() == '' || $('#filter_end').val() == ''){
					lolos = false;
				}
			}else if($('#mode').val() == '3'){
				if($('#year').val() == ''){
					lolos = false;
				}
			}else if($('#mode').val() == '4'){
				if($('#date_start').val() == '' || $('#date_end').val() == ''){
					lolos = false;
				}
			}
			
			if(lolos == false){
				notif('error', 'bg-danger', 'Please check your inputs form.');
				e.preventDefault(e);
			}
			
			loadingClose('.content');
		});
	}
</script>