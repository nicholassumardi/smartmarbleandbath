<style>
	@if(in_array(1, session('bo_role')))
		.hide {
			display:none;
		}
	@endif

	.table-bordered td, .table-bordered th { border: 1px solid #818181; }
	
	table, tr td {
		font-size:10px !important;
	}
	
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
		width: calc( 100% - 0em );/* scrollbar is average 1em/16px width, remove it from thead width */
	}
	
	table {
		width: 400px;
	}
	
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Profit & Loss Comparison</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Profit & Loss Comparison</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header">
				<h4 class="text-muted text-uppercase text-center font-weight-bold">Periode {{ date('F Y', strtotime($filter)) }}</h4>
				<form method="GET" id="form_filter">
				   @csrf
				   <div class="form-group">
					  <center class="d-block">
						 <div class="row justify-content-center">
							<div class="col-md-3 text-left">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Branch</label>
									<select name="branch" id="branch" class="form-control" onchange="submitFilter()">
										<option value="" {{ $branch == '' ? 'selected' : '' }}>All</option>
										@foreach (DB::table('company_entities')->get() as $company)
											<option value="{{$company->id == $branch ? 'selected' : '' }}">{{$company->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3 text-left">
								<div class="form-group">
									<label style="margin-bottom: 0rem;">Month Year</label>
									<input type="month" name="filter" id="filter" class="form-control" value="{{ $filter }}" onchange="submitFilter()">
								</div>
							</div>
							@if(date('Y-m') != $filter)
							<div class="col-md-1">
								<a href="{{ url('admin/report/accounting/profit_loss_comparison') }}" class="btn bg-danger btn-sm">Reset</a> 
							</div>
							@endif
						 </div>
					  </center>
				   </div>
				</form>
			</div>
			<div class="card-body">
				 <div class="table-responsive">
					<table class="table table-sm table-bordered table-scrollable">
					   <thead>
						  <tr class="text-center" style="font-size:15px;">
							 <th rowspan="2" class="bg-grey" style="width:250px;">Description</th>
							 <th colspan="{{ in_array(1, session('bo_role')) ? '4' : '6' }}" class="bg-primary">Current Month</th>
							 <th colspan="{{ in_array(1, session('bo_role')) ? '4' : '6' }}" class="bg-primary">Last Month</th>
						  </tr>
						  <tr class="text-center" style="font-size:15px;">
							 <th class="bg-grey">Actual</th>
							 <th class="bg-grey">%</th>
							 <th class="bg-grey">Goal</th>
							 <th class="bg-grey">%</th>
							 <th class="bg-grey hide">Variance</th>
							 <th class="bg-grey hide">%</th>
							 <th class="bg-grey">Actual</th>
							 <th class="bg-grey">%</th>
							 <th class="bg-grey">Goal</th>
							 <th class="bg-grey">%</th>
							 <th class="bg-grey hide">Variance</th>
							 <th class="bg-grey hide">%</th>
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
								$total_other_income_actual = 0;
								$total_other_deduction_actual = 0;
								
								$total_revenue_budget = 0;
								$total_cogs_budget = 0;	
								$total_fixed_cost_budget = 0;
								$total_variable_cost_budget = 0;
								$total_other_expenses_budget = 0;
								$total_repair_expenses_budget = 0;
								$total_depreciation_budget = 0;
								$total_other_income_budget = 0;
								$total_other_deduction_budget = 0;

								$total_revenue_variance = 0;
								$total_cogs_variance = 0;	
								$total_fixed_cost_variance = 0;
								$total_variable_cost_variance = 0;
								$total_other_expenses_variance = 0;
								$total_repair_expenses_variance = 0;
								$total_depreciation_variance = 0;
								$total_other_income_variance = 0;
								$total_other_deduction_variance = 0;
								
								$total_revenue_actual_last = 0;
								$total_cogs_actual_last = 0;	
								$total_fixed_cost_actual_last = 0;
								$total_variable_cost_actual_last = 0;
								$total_other_expenses_actual_last = 0;
								$total_repair_expenses_actual_last = 0;
								$total_depreciation_actual_last = 0;
								$total_other_income_actual_last = 0;
								$total_other_deduction_actual_last = 0;
								
								$total_revenue_budget_last = 0;
								$total_cogs_budget_last = 0;	
								$total_fixed_cost_budget_last = 0;
								$total_variable_cost_budget_last = 0;
								$total_other_expenses_budget_last = 0;
								$total_repair_expenses_budget_last = 0;
								$total_depreciation_budget_last = 0;
								$total_other_income_budget_last = 0;
								$total_other_deduction_budget_last = 0;

								$total_revenue_variance_last = 0;
								$total_cogs_variance_last = 0;	
								$total_fixed_cost_variance_last = 0;
								$total_variable_cost_variance_last = 0;
								$total_other_expenses_variance_last = 0;
								$total_repair_expenses_variance_last = 0;
								$total_depreciation_variance_last = 0;
								$total_other_income_variance_last = 0;
								$total_other_deduction_variance_last = 0;
								
								foreach($coa->where('parent_id',0) as $rowparent){
									if(substr($rowparent->code,0,1) == '4' || substr($rowparent->code,0,1) == '5' || substr($rowparent->code,0,1) == '6' || substr($rowparent->code,0,1) == '7'){
										if(count($rowparent->child()) == 0){
											$balance = $rowparent->checkTotalPL($filter,$branch);
											$budgeting = $rowparent->checkTotalBudgeting($filter,$branch);
											$balancelast = $rowparent->checkTotalPL($last,$branch);
											$budgetinglast = $rowparent->checkTotalBudgeting($last,$branch);
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$total_revenue_actual += $balance;
												$total_revenue_budget += $budgeting;
												$total_revenue_variance += $balance - $budgeting;
												
												$total_revenue_actual_last += $balancelast;
												$total_revenue_budget_last += $budgetinglast;
												$total_revenue_variance += $balancelast - $budgetinglast;
											}
											
											if(substr($rowparent->code,0,5) == '5.000' || substr($rowparent->code,0,5) == '6.000' || substr($rowparent->code,0,5) == '6.100'){
												$total_cogs_actual += $balance;
												$total_cogs_budget += $budgeting;
												$total_cogs_variance += $balance - $budgeting;
												
												$total_cogs_actual_last += $balancelast;
												$total_cogs_budget_last += $budgetinglast;
												$total_cogs_variance_last += $balancelast - $budgetinglast;
											}
											
											if(substr($rowparent->code,0,5) == '6.200'){
												$total_fixed_cost_actual += $balance;
												$total_fixed_cost_budget += $budgeting;
												$total_fixed_cost_variance += $balance - $budgeting;
												
												$total_fixed_cost_actual_last += $balancelast;
												$total_fixed_cost_budget_last += $budgetinglast;
												$total_fixed_cost_variance_last += $balancelast - $budgetinglast;
											}
											
											if(substr($rowparent->code,0,9) == '6.2100.02'){
												$total_variable_cost_actual += $balance;
												$total_variable_cost_budget += $budgeting;
												$total_variable_cost_variance += $balance - $budgeting;
												
												$total_variable_cost_actual_last += $balancelast;
												$total_variable_cost_budget_last += $budgetinglast;
												$total_variable_cost_variance_last += $balancelast - $budgetinglast;
											}
											
											if(substr($rowparent->code,0,9) == '6.2100.03'){
												$total_other_expenses_actual += $balance;
												$total_other_expenses_budget += $budgeting;
												$total_other_expenses_variance += $balance - $budgeting;
												
												$total_other_expenses_actual_last += $balancelast;
												$total_other_expenses_budget_last += $budgetinglast;
												$total_other_expenses_variance_last += $balancelast - $budgetinglast;
											}
											
											if(substr($rowparent->code,0,6) == '6.2200'){
												$total_repair_expenses_actual += $balance;
												$total_repair_expenses_budget += $budgeting;
												$total_repair_expenses_variance += $balance - $budgeting;
												
												$total_repair_expenses_actual_last += $balancelast;
												$total_repair_expenses_budget_last += $budgetinglast;
												$total_repair_expenses_variance_last += $balancelast - $budgetinglast;
											}
											
											if(substr($rowparent->code,0,5) == '6.300'){
												$total_depreciation_actual += $balance;
												$total_depreciation_budget += $budgeting;
												$total_depreciation_variance += $balance - $budgeting;
												
												$total_depreciation_actual_last += $balancelast;
												$total_depreciation_budget_last += $budgetinglast;
												$total_depreciation_variance_last += $balancelast - $budgetinglast;
											}
											
											if(substr($rowparent->code,0,5) == '7.100'){
												$total_other_income_actual += $balance;
												$total_other_income_budget += $budgeting;
												$total_other_income_variance += $balance - $budgeting;
												
												$total_other_income_actual_last += $balancelast;
												$total_other_income_budget_last += $budgetinglast;
												$total_other_income_variance_last += $balancelast - $budgetinglast;
											}
											
											if(substr($rowparent->code,0,5) == '7.200'){
												$total_other_deduction_actual += $balance;
												$total_other_deduction_budget += $budgeting;
												$total_other_deduction_variance += $balance - $budgeting;
												
												$total_other_deduction_actual_last += $balancelast;
												$total_other_deduction_budget_last += $budgetinglast;
												$total_other_deduction_variance_last += $balancelast - $budgetinglast;
											}
										}
										
										foreach($rowparent->child() as $rowchild){
											if(count($rowchild->child()) == 0){
												$balance = $rowchild->checkTotalPL($filter,$branch);
												$budgeting = $rowchild->checkTotalBudgeting($filter,$branch);
												$balancelast = $rowchild->checkTotalPL($last,$branch);
												$budgetinglast = $rowchild->checkTotalBudgeting($last,$branch);
												
												if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
													$total_revenue_actual += $balance;
													$total_revenue_budget += $budgeting;
													$total_revenue_variance += $balance - $budgeting;
													
													$total_revenue_actual_last += $balancelast;
													$total_revenue_budget_last += $budgetinglast;
													$total_revenue_variance += $balancelast - $budgetinglast;
												}
												
												if(substr($rowchild->code,0,5) == '5.000' || substr($rowchild->code,0,5) == '6.000' || substr($rowchild->code,0,5) == '6.100'){
													$total_cogs_actual += $balance;
													$total_cogs_budget += $budgeting;
													$total_cogs_variance += $balance - $budgeting;
													
													$total_cogs_actual_last += $balancelast;
													$total_cogs_budget_last += $budgetinglast;
													$total_cogs_variance_last += $balancelast - $budgetinglast;
												}
												
												if(substr($rowchild->code,0,5) == '6.200'){
													$total_fixed_cost_actual += $balance;
													$total_fixed_cost_budget += $budgeting;
													$total_fixed_cost_variance += $balance - $budgeting;
													
													$total_fixed_cost_actual_last += $balancelast;
													$total_fixed_cost_budget_last += $budgetinglast;
													$total_fixed_cost_variance_last += $balancelast - $budgetinglast;
												}
												
												if(substr($rowchild->code,0,9) == '6.2100.02'){
													$total_variable_cost_actual += $balance;
													$total_variable_cost_budget += $budgeting;
													$total_variable_cost_variance += $balance - $budgeting;
													
													$total_variable_cost_actual_last += $balancelast;
													$total_variable_cost_budget_last += $budgetinglast;
													$total_variable_cost_variance_last += $balancelast - $budgetinglast;
												}
												
												if(substr($rowchild->code,0,9) == '6.2100.03'){
													$total_other_expenses_actual += $balance;
													$total_other_expenses_budget += $budgeting;
													$total_other_expenses_variance += $balance - $budgeting;
													
													$total_other_expenses_actual_last += $balancelast;
													$total_other_expenses_budget_last += $budgetinglast;
													$total_other_expenses_variance_last += $balancelast - $budgetinglast;
												}
												
												if(substr($rowchild->code,0,6) == '6.2200'){
													$total_repair_expenses_actual += $balance;
													$total_repair_expenses_budget += $budgeting;
													$total_repair_expenses_variance += $balance - $budgeting;
													
													$total_repair_expenses_actual_last += $balancelast;
													$total_repair_expenses_budget_last += $budgetinglast;
													$total_repair_expenses_variance_last += $balancelast - $budgetinglast;
												}
												
												if(substr($rowchild->code,0,5) == '6.300'){
													$total_depreciation_actual += $balance;
													$total_depreciation_budget += $budgeting;
													$total_depreciation_variance += $balance - $budgeting;
													
													$total_depreciation_actual_last += $balancelast;
													$total_depreciation_budget_last += $budgetinglast;
													$total_depreciation_variance_last += $balancelast - $budgetinglast;
												}
												
												if(substr($rowchild->code,0,5) == '7.100'){
													$total_other_income_actual += $balance;
													$total_other_income_budget += $budgeting;
													$total_other_income_variance += $balance - $budgeting;
													
													$total_other_income_actual_last += $balancelast;
													$total_other_income_budget_last += $budgetinglast;
													$total_other_income_variance_last += $balancelast - $budgetinglast;
												}
												
												if(substr($rowchild->code,0,5) == '7.200'){
													$total_other_deduction_actual += $balance;
													$total_other_deduction_budget += $budgeting;
													$total_other_deduction_variance += $balance - $budgeting;
													
													$total_other_deduction_actual_last += $balancelast;
													$total_other_deduction_budget_last += $budgetinglast;
													$total_other_deduction_variance_last += $balancelast - $budgetinglast;
												}
											}
											
											foreach($rowchild->child() as $rowgrandchild){
												if(count($rowgrandchild->child()) == 0){
													$balance = $rowgrandchild->checkTotalPL($filter,$branch);
													$budgeting = $rowgrandchild->checkTotalBudgeting($filter,$branch);
													$balancelast = $rowgrandchild->checkTotalPL($last,$branch);
													$budgetinglast = $rowgrandchild->checkTotalBudgeting($last,$branch);
													
													if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
														$total_revenue_actual += $balance;
														$total_revenue_budget += $budgeting;
														$total_revenue_variance += $balance - $budgeting;
														
														$total_revenue_actual_last += $balancelast;
														$total_revenue_budget_last += $budgetinglast;
														$total_revenue_variance += $balancelast - $budgetinglast;
													}
													
													if(substr($rowgrandchild->code,0,5) == '5.000' || substr($rowgrandchild->code,0,5) == '6.000' || substr($rowgrandchild->code,0,5) == '6.100'){
														$total_cogs_actual += $balance;
														$total_cogs_budget += $budgeting;
														$total_cogs_variance += $balance - $budgeting;
														
														$total_cogs_actual_last += $balancelast;
														$total_cogs_budget_last += $budgetinglast;
														$total_cogs_variance_last += $balancelast - $budgetinglast;
													}
													
													if(substr($rowgrandchild->code,0,5) == '6.200'){
														$total_fixed_cost_actual += $balance;
														$total_fixed_cost_budget += $budgeting;
														$total_fixed_cost_variance += $balance - $budgeting;
														
														$total_fixed_cost_actual_last += $balancelast;
														$total_fixed_cost_budget_last += $budgetinglast;
														$total_fixed_cost_variance_last += $balancelast - $budgetinglast;
													}
													
													if(substr($rowgrandchild->code,0,9) == '6.2100.02'){
														$total_variable_cost_actual += $balance;
														$total_variable_cost_budget += $budgeting;
														$total_variable_cost_variance += $balance - $budgeting;
														
														$total_variable_cost_actual_last += $balancelast;
														$total_variable_cost_budget_last += $budgetinglast;
														$total_variable_cost_variance_last += $balancelast - $budgetinglast;
													}
													
													if(substr($rowgrandchild->code,0,9) == '6.2100.03'){
														$total_other_expenses_actual += $balance;
														$total_other_expenses_budget += $budgeting;
														$total_other_expenses_variance += $balance - $budgeting;
														
														$total_other_expenses_actual_last += $balancelast;
														$total_other_expenses_budget_last += $budgetinglast;
														$total_other_expenses_variance_last += $balancelast - $budgetinglast;
													}
													
													if(substr($rowgrandchild->code,0,6) == '6.2200'){
														$total_repair_expenses_actual += $balance;
														$total_repair_expenses_budget += $budgeting;
														$total_repair_expenses_variance += $balance - $budgeting;
														
														$total_repair_expenses_actual_last += $balancelast;
														$total_repair_expenses_budget_last += $budgetinglast;
														$total_repair_expenses_variance_last += $balancelast - $budgetinglast;
													}
													
													if(substr($rowgrandchild->code,0,5) == '6.300'){
														$total_depreciation_actual += $balance;
														$total_depreciation_budget += $budgeting;
														$total_depreciation_variance += $balance - $budgeting;
														
														$total_depreciation_actual_last += $balancelast;
														$total_depreciation_budget_last += $budgetinglast;
														$total_depreciation_variance_last += $balancelast - $budgetinglast;
													}
													
													if(substr($rowgrandchild->code,0,5) == '7.100'){
														$total_other_income_actual += $balance;
														$total_other_income_budget += $budgeting;
														$total_other_income_variance += $balance - $budgeting;
														
														$total_other_income_actual_last += $balancelast;
														$total_other_income_budget_last += $budgetinglast;
														$total_other_income_variance_last += $balancelast - $budgetinglast;
													}
													
													if(substr($rowgrandchild->code,0,5) == '7.200'){
														$total_other_deduction_actual += $balance;
														$total_other_deduction_budget += $budgeting;
														$total_other_deduction_variance += $balance - $budgeting;
														
														$total_other_deduction_actual_last += $balancelast;
														$total_other_deduction_budget_last += $budgetinglast;
														$total_other_deduction_variance_last += $balancelast - $budgetinglast;
													}
											
												}
												
												foreach($rowgrandchild->child() as $rowgrandgrandchild){
													if(count($rowgrandgrandchild->child()) == 0){
														$balance = $rowgrandgrandchild->checkTotalPL($filter,$branch);
														$budgeting = $rowgrandgrandchild->checkTotalBudgeting($filter,$branch);
														$balancelast = $rowgrandgrandchild->checkTotalPL($last,$branch);
														$budgetinglast = $rowgrandgrandchild->checkTotalBudgeting($last,$branch);
														
														if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
															$total_revenue_actual += $balance;
															$total_revenue_budget += $budgeting;
															$total_revenue_variance += $balance - $budgeting;
															
															$total_revenue_actual_last += $balancelast;
															$total_revenue_budget_last += $budgetinglast;
															$total_revenue_variance += $balancelast - $budgetinglast;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '5.000' || substr($rowgrandgrandchild->code,0,5) == '6.000' || substr($rowgrandgrandchild->code,0,5) == '6.100'){
															$total_cogs_actual += $balance;
															$total_cogs_budget += $budgeting;
															$total_cogs_variance += $balance - $budgeting;
															
															$total_cogs_actual_last += $balancelast;
															$total_cogs_budget_last += $budgetinglast;
															$total_cogs_variance_last += $balancelast - $budgetinglast;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '6.200'){
															$total_fixed_cost_actual += $balance;
															$total_fixed_cost_budget += $budgeting;
															$total_fixed_cost_variance += $balance - $budgeting;
															
															$total_fixed_cost_actual_last += $balancelast;
															$total_fixed_cost_budget_last += $budgetinglast;
															$total_fixed_cost_variance_last += $balancelast - $budgetinglast;
														}
														
														if(substr($rowgrandgrandchild->code,0,9) == '6.2100.02'){
															$total_variable_cost_actual += $balance;
															$total_variable_cost_budget += $budgeting;
															$total_variable_cost_variance += $balance - $budgeting;
															
															$total_variable_cost_actual_last += $balancelast;
															$total_variable_cost_budget_last += $budgetinglast;
															$total_variable_cost_variance_last += $balancelast - $budgetinglast;
														}
														
														if(substr($rowgrandgrandchild->code,0,9) == '6.2100.03'){
															$total_other_expenses_actual += $balance;
															$total_other_expenses_budget += $budgeting;
															$total_other_expenses_variance += $balance - $budgeting;
															
															$total_other_expenses_actual_last += $balancelast;
															$total_other_expenses_budget_last += $budgetinglast;
															$total_other_expenses_variance_last += $balancelast - $budgetinglast;
														}
														
														if(substr($rowgrandgrandchild->code,0,6) == '6.2200'){
															$total_repair_expenses_actual += $balance;
															$total_repair_expenses_budget += $budgeting;
															$total_repair_expenses_variance += $balance - $budgeting;
															
															$total_repair_expenses_actual_last += $balancelast;
															$total_repair_expenses_budget_last += $budgetinglast;
															$total_repair_expenses_variance_last += $balancelast - $budgetinglast;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '6.300'){
															$total_depreciation_actual += $balance;
															$total_depreciation_budget += $budgeting;
															$total_depreciation_variance += $balance - $budgeting;
															
															$total_depreciation_actual_last += $balancelast;
															$total_depreciation_budget_last += $budgetinglast;
															$total_depreciation_variance_last += $balancelast - $budgetinglast;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '7.100'){
															$total_other_income_actual += $balance;
															$total_other_income_budget += $budgeting;
															$total_other_income_variance += $balance - $budgeting;
															
															$total_other_income_actual_last += $balancelast;
															$total_other_income_budget_last += $budgetinglast;
															$total_other_income_variance_last += $balancelast - $budgetinglast;
														}
														
														if(substr($rowgrandgrandchild->code,0,5) == '7.200'){
															$total_other_deduction_actual += $balance;
															$total_other_deduction_budget += $budgeting;
															$total_other_deduction_variance += $balance - $budgeting;
															
															$total_other_deduction_actual_last += $balancelast;
															$total_other_deduction_budget_last += $budgetinglast;
															$total_other_deduction_variance_last += $balancelast - $budgetinglast;
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
									$total_last = 0;
									$totalbudgeting_last = 0;
									$totalvariance_last = 0;
								@endphp
								
								@if(substr($rowparent->code,0,1) == '4' || substr($rowparent->code,0,1) == '5' || substr($rowparent->code,0,1) == '6' || substr($rowparent->code,0,1) == '7')
								<tr class="font-weight-bold bg-grey-300" style="font-size:17px;">
									<td style="width:250px;padding:15px;">{{ $rowparent->name }}</td>
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
									<td class="text-right">
										@php
										if(count($rowparent->child()) == 0){
											$balancelast = $rowparent->checkTotalPL($last,$branch);
											$total_last += $balancelast;
					
											echo number_format($balancelast, 2, ',', '.');
										}
										@endphp
									</td>
									<td class="text-center">
										@php
										if(count($rowparent->child()) == 0){
											$balancelast = $rowparent->checkTotalPL($last,$branch);
											
											$percentage = 0;
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$percentage = $total_revenue_actual_last == 0 ? 0 : ($balancelast / $total_revenue_actual_last) * 100;
											}
											
											if(in_array(substr($rowparent->code,0,1),array('5','6','7'))){
												$percentage = $total_revenue_actual_last == 0 ? 0 : ($balancelast / $total_revenue_actual_last) * 100;
											}

											echo number_format($percentage, 2,',','.').'%';
										}
										@endphp
									</td>
									<td class="text-right">
										@php
										if(count($rowparent->child()) == 0){
											$budgetinglast = $rowparent->checkTotalBudgeting($last,$branch);
											$totalbudgeting_last += $budgetinglast;
											
											echo number_format($budgetinglast, 2, ',', '.');
										}
										@endphp
									</td>
									<td class="text-center">
										@php
										if(count($rowparent->child()) == 0){
											$budgetinglast = $rowparent->checkTotalBudgeting($last,$branch);
											
											$percentage = 0;
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$percentage = $total_revenue_budget_last == 0 ? 0 : ($budgetinglast / $total_revenue_budget_last) * 100;
											}
											
											if(in_array(substr($rowparent->code,0,1),array('5','6','7'))){
												$percentage = $total_revenue_budget_last == 0 ? 0 : ($budgetinglast / $total_revenue_budget_last) * 100;
											}

											echo number_format($percentage, 2,',','.').'%';
										}
										@endphp
									</td>
									<td class="text-right hide">
										@php
										if(count($rowparent->child()) == 0){
											$balancelast = $rowparent->checkTotalPL($last,$branch);
											$budgetinglast = $rowparent->checkTotalBudgeting($last,$branch);
											$totalvariance_last += $balancelast - $budgetinglast;
											
											echo number_format($balancelast - $budgetinglast, 2, ',', '.');
										}
										@endphp
									</td>
									<td class="text-center hide">
										@php
										if(count($rowparent->child()) == 0){
											$balancelast = $rowparent->checkTotalPL($last,$branch);
											$budgetinglast = $rowparent->checkTotalBudgeting($last,$branch);
											
											$percentage = 0;
											
											if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
												$percentage = $budgetinglast == 0 ? 0 : (($balancelast-$budgetinglast) / $budgetinglast) * 100;
											}
											
											if(in_array(substr($rowparent->code,0,1),array('5','6','7'))){
												$percentage = $budgetinglast == 0 ? 0 : (($balancelast-$budgetinglast) / $budgetinglast) * 100;
											}

											echo number_format($percentage, 2,',','.').'%';
										}
										@endphp
									</td>
								</tr>
									@foreach($rowparent->child() as $rowchild)
										<tr class="font-weight-bold">
											<td style="padding-left:50px;width:250px;">{{ $rowchild->name }}</td>
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
											<td class="text-right">
												@php
												if(count($rowchild->child()) == 0){
													$balancelast = $rowchild->checkTotalPL($last,$branch);
													$total_last += $balancelast;
							
													echo number_format($balancelast, 2, ',', '.');
												}
												@endphp
											</td>
											<td class="text-center">
												@php
												if(count($rowchild->child()) == 0){
													$balancelast = $rowchild->checkTotalPL($last,$branch);
													
													$percentage = 0;
													
													if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
														$percentage = $total_revenue_actual_last == 0 ? 0 : ($balancelast / $total_revenue_actual_last) * 100;
													}
													
													if(in_array(substr($rowchild->code,0,1),array('5','6','7'))){
														$percentage = $total_revenue_actual_last == 0 ? 0 : ($balancelast / $total_revenue_actual_last) * 100;
													}

													echo number_format($percentage, 2,',','.').'%';
												}
												@endphp
											</td>
											<td class="text-right" style="background-color:#cccccc;">
												@php
												if(count($rowchild->child()) == 0){
													$budgetinglast = $rowchild->checkTotalBudgeting($last,$branch);
													$totalbudgeting_last += $budgetinglast;
													
													echo number_format($budgetinglast, 2, ',', '.');
												}
												@endphp
											</td>
											<td class="text-center" style="background-color:#cccccc;">
												@php
												if(count($rowchild->child()) == 0){
													$budgetinglast = $rowchild->checkTotalBudgeting($last,$branch);
													
													$percentage = 0;
													
													if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
														$percentage = $total_revenue_budget_last == 0 ? 0 : ($budgetinglast / $total_revenue_budget_last) * 100;
													}
													
													if(in_array(substr($rowchild->code,0,1),array('5','6','7'))){
														$percentage = $total_revenue_budget_last == 0 ? 0 : ($budgetinglast / $total_revenue_budget_last) * 100;
													}

													echo number_format($percentage, 2,',','.').'%';
												}
												@endphp
											</td>
											<td class="text-right hide">
												@php
												if(count($rowchild->child()) == 0){
													$balancelast = $rowchild->checkTotalPL($last,$branch);
													$budgetinglast = $rowchild->checkTotalBudgeting($last,$branch);
													$totalvariance_last += $balancelast - $budgetinglast;
													
													echo number_format($balancelast - $budgetinglast, 2, ',', '.');
												}
												@endphp
											</td>
											<td class="text-center hide">
												@php
												if(count($rowchild->child()) == 0){
													$balancelast = $rowchild->checkTotalPL($last,$branch);
													$budgetinglast = $rowchild->checkTotalBudgeting($last,$branch);
													
													$percentage = 0;
													
													if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
														$percentage = $budgetinglast == 0 ? 0 : (($balancelast-$budgetinglast) / $budgetinglast) * 100;
													}
													
													if(in_array(substr($rowchild->code,0,1),array('5','6','7'))){
														$percentage = $budgetinglast == 0 ? 0 : (($balancelast-$budgetinglast) / $budgetinglast) * 100;
													}

													echo number_format($percentage, 2,',','.').'%';
												}
												@endphp
											</td>
										</tr>
										@foreach($rowchild->child() as $rowgrandchild)
											<tr class="font-weight-bold">
												<td style="padding-left:75px;width:250px;">{{ $rowgrandchild->name }}</td>
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
												<td class="text-right">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balancelast = $rowgrandchild->checkTotalPL($last,$branch);
														$total_last += $balancelast;
								
														echo number_format($balancelast, 2, ',', '.');
													}
													@endphp
												</td>
												<td class="text-center">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balancelast = $rowgrandchild->checkTotalPL($last,$branch);
														
														$percentage = 0;
														
														if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
															$percentage = $total_revenue_actual_last == 0 ? 0 : ($balancelast / $total_revenue_actual_last) * 100;
														}
														
														if(in_array(substr($rowgrandchild->code,0,1),array('5','6','7'))){
															$percentage = $total_revenue_actual_last == 0 ? 0 : ($balancelast / $total_revenue_actual_last) * 100;
														}

														echo number_format($percentage, 2,',','.').'%';
													}
													@endphp
												</td>
												<td class="text-right" style="background-color:#cccccc;">
													@php
													if(count($rowgrandchild->child()) == 0){
														$budgetinglast = $rowgrandchild->checkTotalBudgeting($last,$branch);
														$totalbudgeting_last += $budgetinglast;
														
														echo number_format($budgetinglast, 2, ',', '.');
													}
													@endphp
												</td>
												<td class="text-center" style="background-color:#cccccc;">
													@php
													if(count($rowgrandchild->child()) == 0){
														$budgetinglast = $rowgrandchild->checkTotalBudgeting($last,$branch);
														
														$percentage = 0;
														
														if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
															$percentage = $total_revenue_budget_last == 0 ? 0 : ($budgetinglast / $total_revenue_budget_last) * 100;
														}
														
														if(in_array(substr($rowgrandchild->code,0,1),array('5','6','7'))){
															$percentage = $total_revenue_budget_last == 0 ? 0 : ($budgetinglast / $total_revenue_budget_last) * 100;
														}

														echo number_format($percentage, 2,',','.').'%';
													}
													@endphp
												</td>
												<td class="text-right hide">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balancelast = $rowgrandchild->checkTotalPL($last,$branch);
														$budgetinglast = $rowgrandchild->checkTotalBudgeting($last,$branch);
														$totalvariance_last += $balancelast - $budgetinglast;
														
														echo number_format($balancelast - $budgetinglast, 2, ',', '.');
													}
													@endphp
												</td>
												<td class="text-center hide">
													@php
													if(count($rowgrandchild->child()) == 0){
														$balancelast = $rowgrandchild->checkTotalPL($last,$branch);
														$budgetinglast = $rowgrandchild->checkTotalBudgeting($last,$branch);
														
														$percentage = 0;
														
														if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
															$percentage = $budgetinglast == 0 ? 0 : (($balancelast-$budgetinglast) / $budgetinglast) * 100;
														}
														
														if(in_array(substr($rowgrandchild->code,0,1),array('5','6','7'))){
															$percentage = $budgetinglast == 0 ? 0 : (($balancelast-$budgetinglast) / $budgetinglast) * 100;
														}

														echo number_format($percentage, 2,',','.').'%';
													}
													@endphp
												</td>
											</tr>
											@foreach($rowgrandchild->child() as $rowgrandgrandchild)
												<tr class="font-weight-bold">
													<td style="padding-left:100px;width:250px;">{{ $rowgrandgrandchild->name }}</td>
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
													<td class="text-right">
													@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balancelast = $rowgrandgrandchild->checkTotalPL($last,$branch);
															$total_last += $balancelast;
									
															echo number_format($balancelast, 2, ',', '.');
														}
														@endphp
													</td>
													<td class="text-center">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balancelast = $rowgrandgrandchild->checkTotalPL($last,$branch);
															
															$percentage = 0;
															
															if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
																$percentage = $total_revenue_actual_last == 0 ? 0 : ($balancelast / $total_revenue_actual_last) * 100;
															}
															
															if(in_array(substr($rowgrandgrandchild->code,0,1),array('5','6','7'))){
																$percentage = $total_revenue_actual_last == 0 ? 0 : ($balancelast / $total_revenue_actual_last) * 100;
															}

															echo number_format($percentage, 2,',','.').'%';
														}
														@endphp
													</td>
													<td class="text-right" style="background-color:#cccccc;">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$budgetinglast = $rowgrandgrandchild->checkTotalBudgeting($last,$branch);
															$totalbudgeting_last += $budgetinglast;
															
															echo number_format($budgetinglast, 2, ',', '.');
														}
														@endphp
													</td>
													<td class="text-center" style="background-color:#cccccc;">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$budgetinglast = $rowgrandgrandchild->checkTotalBudgeting($last,$branch);
															
															$percentage = 0;
															
															if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
																$percentage = $total_revenue_budget_last == 0 ? 0 : ($budgetinglast / $total_revenue_budget_last) * 100;
															}
															
															if(in_array(substr($rowgrandgrandchild->code,0,1),array('5','6','7'))){
																$percentage = $total_revenue_budget_last == 0 ? 0 : ($budgetinglast / $total_revenue_budget_last) * 100;
															}

															echo number_format($percentage, 2,',','.').'%';
														}
														@endphp
													</td>
													<td class="text-right hide">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balancelast = $rowgrandgrandchild->checkTotalPL($last,$branch);
															$budgetinglast = $rowgrandgrandchild->checkTotalBudgeting($last,$branch);
															$totalvariance_last += $balancelast - $budgetinglast;
															
															echo number_format($balancelast - $budgetinglast, 2, ',', '.');
														}
														@endphp
													</td>
													<td class="text-center hide">
														@php
														if(count($rowgrandgrandchild->child()) == 0){
															$balancelast = $rowgrandgrandchild->checkTotalPL($last,$branch);
															$budgetinglast = $rowgrandgrandchild->checkTotalBudgeting($last,$branch);
															
															$percentage = 0;
															
															if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
																$percentage = $budgetinglast == 0 ? 0 : (($balancelast-$budgetinglast) / $budgetinglast) * 100;
															}
															
															if(in_array(substr($rowgrandgrandchild->code,0,1),array('5','6','7'))){
																$percentage = $budgetinglast == 0 ? 0 : (($balancelast-$budgetinglast) / $budgetinglast) * 100;
															}

															echo number_format($percentage, 2,',','.').'%';
														}
														@endphp
													</td>
												</tr>
											@endforeach
										@endforeach
									@endforeach
								
								<tr class="font-weight-bold bg-brown-300">
									<td style="width:250px;padding:8px;">Total {{ $rowparent->name }}</td>
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
									<td class="text-right">
										{{ number_format($total_last, 2, ',', '.') }}
									</td>
									<td class="text-center">
										{{ number_format(($total_revenue_actual_last == 0 ? 0 : $total_last / $total_revenue_actual_last) * 100, 2, ',', '.') }}%
									</td>
									<td class="text-right">
										{{ number_format($totalbudgeting_last, 2, ',', '.') }}
									</td>
									<td class="text-center">
										{{ number_format($total_revenue_budget_last == 0 ? 0 : ($totalbudgeting_last / $total_revenue_budget_last) * 100, 2, ',', '.') }}%
									</td>
									<td class="text-right hide">
										{{ number_format($totalvariance_last, 2, ',', '.') }}
									</td>
									<td class="text-center hide">
										{{ number_format($totalbudgeting_last == 0 ? 0 : ($totalvariance_last / $totalbudgeting_last) * 100, 2, ',', '.') }}%
									</td>
								</tr>
								<tr>
									<td style="width:250px;padding:8px;border-right: hidden !important;border-left: hidden !important;">&nbsp;</td>
								</tr>
								@endif
							@endforeach
					   </tbody>
					   <tfoot style="border:1px solid black;">
							<tr class="bg-primary font-weight-bold">
								<td style="width:250px;border:1px solid black;">Nett Profit (Loss)</td>
								<td class="text-right" style="border:1px solid black;">
									{{ number_format($total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual + $total_other_income_actual - $total_other_deduction_actual, 2, ',', '.') }}
								</td>
								<td class="text-center" style="border:1px solid black;">
									{{ number_format($total_revenue_actual == 0 ? 0 : (($total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual + $total_other_income_actual - $total_other_deduction_actual) / $total_revenue_actual) * 100, 2, ',', '.') }}%
								</td>
								<td class="text-right" style="border:1px solid black;">
									{{ number_format($total_revenue_budget - $total_cogs_budget - $total_fixed_cost_budget - $total_variable_cost_budget - $total_other_expenses_budget - $total_repair_expenses_budget - $total_depreciation_budget + $total_other_income_budget - $total_other_deduction_budget, 2, ',', '.') }}
								</td>
								<td class="text-center" style="border:1px solid black;">
									{{ number_format($total_revenue_budget == 0 ? 0 : (($total_revenue_budget - $total_cogs_budget - $total_fixed_cost_budget - $total_variable_cost_budget - $total_other_expenses_budget - $total_repair_expenses_budget - $total_depreciation_budget + $total_other_income_budget - $total_other_deduction_budget) / $total_revenue_budget) * 100, 2, ',', '.') }}%
								</td>
								<td class="text-right hide" style="border:1px solid black;">
									{{ number_format($total_revenue_variance - $total_cogs_variance - $total_fixed_cost_variance - $total_variable_cost_variance - $total_other_expenses_variance - $total_repair_expenses_variance - $total_depreciation_variance + $total_other_income_variance - $total_other_deduction_variance, 2, ',', '.') }}
								</td>
								<td class="text-center hide" style="border:1px solid black;">
									{{ number_format( $total_revenue_budget == 0 ? 0 : (($total_revenue_variance - $total_cogs_variance - $total_fixed_cost_variance - $total_variable_cost_variance - $total_other_expenses_variance - $total_repair_expenses_variance - $total_depreciation_variance + $total_other_income_variance - $total_other_deduction_variance) / ($total_revenue_budget - $total_cogs_budget - $total_fixed_cost_budget - $total_variable_cost_budget - $total_other_expenses_budget - $total_repair_expenses_budget - $total_depreciation_budget + $total_other_income_budget - $total_other_deduction_budget)) * 100, 2, ',', '.') }}%
								</td>
								<td class="text-right" style="border:1px solid black;">
									{{ number_format($total_revenue_actual_last - $total_cogs_actual_last - $total_fixed_cost_actual_last - $total_variable_cost_actual_last - $total_other_expenses_actual_last - $total_repair_expenses_actual_last - $total_depreciation_actual_last + $total_other_income_actual_last - $total_other_deduction_actual_last, 2, ',', '.') }}
								</td>
								<td class="text-center" style="border:1px solid black;">
									{{ number_format($total_revenue_actual_last == 0 ? 0 : (($total_revenue_actual_last - $total_cogs_actual_last - $total_fixed_cost_actual_last - $total_variable_cost_actual_last - $total_other_expenses_actual_last - $total_repair_expenses_actual_last - $total_depreciation_actual_last + $total_other_income_actual_last - $total_other_deduction_actual_last) / $total_revenue_actual_last) * 100, 2, ',', '.') }}%
								</td>
								<td class="text-right" style="border:1px solid black;">
									{{ number_format($total_revenue_budget_last - $total_cogs_budget_last - $total_fixed_cost_budget_last - $total_variable_cost_budget_last - $total_other_expenses_budget_last - $total_repair_expenses_budget_last - $total_depreciation_budget_last + $total_other_income_budget_last - $total_other_deduction_budget_last, 2, ',', '.') }}
								</td>
								<td class="text-center" style="border:1px solid black;">
									{{ number_format($total_revenue_budget_last == 0 ? 0 : (($total_revenue_budget_last - $total_cogs_budget_last - $total_fixed_cost_budget_last - $total_variable_cost_budget_last - $total_other_expenses_budget_last - $total_repair_expenses_budget_last - $total_depreciation_budget_last + $total_other_income_budget_last - $total_other_deduction_budget_last) / $total_revenue_budget_last) * 100, 2, ',', '.') }}%
								</td>
								<td class="text-right hide" style="border:1px solid black;">
									{{ number_format($total_revenue_variance_last - $total_cogs_variance_last - $total_fixed_cost_variance_last - $total_variable_cost_variance_last - $total_other_expenses_variance_last - $total_repair_expenses_variance_last - $total_depreciation_variance_last + $total_other_income_variance_last - $total_other_deduction_variance_last, 2, ',', '.') }}
								</td>
								<td class="text-center hide" style="border:1px solid black;">
									{{ number_format( $total_revenue_budget_last == 0 ? 0 : (($total_revenue_variance_last - $total_cogs_variance_last - $total_fixed_cost_variance_last - $total_variable_cost_variance_last - $total_other_expenses_variance_last - $total_repair_expenses_variance_last - $total_depreciation_variance_last + $total_other_income_variance_last - $total_other_deduction_variance_last) / ($total_revenue_budget_last - $total_cogs_budget_last - $total_fixed_cost_budget_last - $total_variable_cost_budget_last - $total_other_expenses_budget_last - $total_repair_expenses_budget_last - $total_depreciation_budget_last + $total_other_income_budget_last - $total_other_deduction_budget_last)) * 100, 2, ',', '.') }}%
								</td>
							</tr>
					   </tfoot>
					</table>
				 </div>
			</div>
		</div>
	</div>

<script>
   $(function() {
		$('.sidebar-main-toggle').click();
   });

   function submitFilter() {
      loadingOpen('.content');
      $('#form_filter').submit();
   }
</script>