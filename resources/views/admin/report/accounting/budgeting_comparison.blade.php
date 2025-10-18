@php
	use App\Models\CashBankDetail;
@endphp
<style>
	.table-bordered td, .table-bordered th { border: 1px solid #818181; }
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Budgeting Comparison</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Budgeting Comparison</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
            <h6 class="text-muted text-uppercase text-center font-weight-bold">Please choose budgeting data and projects to compare!</h6>
			<div class="form-group">
			  <center class="d-block">
				 <div class="row justify-content-center">
					<div class="col-md-6">
						<h6>Budgeting Projects</h6>
						<div class="form-group">
							<select name="budgeting_project_id" id="budgeting_project_id" class="select2">
							   <option value="">-- Choose --</option>
							   @foreach($budgeting_project as $bp)
								  <option value="{{ $bp->id }}">{{ 'User '.$bp->user->name.', Project No. '.($bp->project ? $bp->project->code : 'DELETED').' - '.$bp->name.', Branch - '.$bp->branch() }}</option>
							   @endforeach
							</select>
						</div>
						<div class="form-group">
							<button id="addbudgetingproject" class="btn btn-success" onclick="addBudget()"><i class="icon-add"></i> Add</button>
						</div>
					</div>
					<div class="col-md-6">
						<h6>Real Projects</h6>
						<div class="form-group">
							<select name="project_id" id="project_id"></select>
						</div>
						<div class="form-group">
							<button id="addproject" class="btn btn-success" onclick="addProject()"><i class="icon-add"></i> Add</button>
						</div>
					</div>
				 </div>
				 <div class="form-group"><hr></div>
				 <form action="{{ url()->full() }}" method="POST">
                     @csrf
					 <div class="row justify-content-center">
						<div class="col-md-6">
							<div class="table-responsive">
							   <table class="table table-bordered table-striped">
								  <thead class="bg-dark">
									 <tr class="text-center">
										<th width="80%">Budgeting Project's Name</th>
										<th>Delete</th>
									 </tr>
								  </thead>
								  <tbody id="budget_data">
									@foreach($resultbudget as $row)
										<tr>
											<input type="hidden" name="budget_id[]" value="{{ $row->id }}">
											<td>{{ 'User '.$row->user->name.', Project - '.$row->name.', Branch - '.$row->branch() }}</td>
											<td class="align-middle text-center">
												<button type="button" id="delete_data_budget" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
											 </td>
										</tr>
									@endforeach
								  </tbody>
							   </table>
							</div>
						</div>
						<div class="col-md-6">
							<div class="table-responsive">
							   <table class="table table-bordered table-striped">
								  <thead class="bg-dark">
									 <tr class="text-center">
										<th width="80%">Project's Name</th>
										<th>Delete</th>
									 </tr>
								  </thead>
								  <tbody id="project_data">
									@foreach($resultproject as $row)
										<tr>
											<input type="hidden" name="project_id[]" value="{{ $row->id }}">
											<td>{{ $row->name.' - '.$row->code }}</td>
											<td class="align-middle text-center">
												<button type="button" id="delete_data_project" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
											 </td>
										</tr>
									@endforeach
								  </tbody>
							   </table>
							</div>
						</div>
					 </div>
					 <div class="form-group"><hr></div>
					 <div class="row justify-content-center">
						<div class="col-md-12">
							<div class="form-group">
								<button class="btn btn-info" onclick="processData()"><i class="icon-hour-glass2"></i> Process</button>
							</div>
						</div>
					 </div>
				</form>
				<div class="form-group"><hr></div>
				<h6 class="text-muted text-uppercase text-center font-weight-bold">RESULTS!</h6>
				<div class="form-group">
					@if($errors->any())
					 <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
						<button type="button" class="close" data-dismiss="alert">
						   <span>&times;</span>
						</button>
						<ul>
						   @foreach ($errors->all() as $error)
							  <li>{{ $error }}</li>
						   @endforeach
						</ul>
					 </div>
					@elseif(session('success'))
					 <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
						<button type="button" class="close" data-dismiss="alert">
						   <span>&times;</span>
						</button>
						{{ session('success') }}
					 </div>
					@endif
				</div>
				@if(count($resultbudget) > 0)
				<div class="form-group" id="result_show">
					@foreach($resultbudget as $rowall)
						<div class="col-md-12">
						@php 
							$totalrevenue = 0;
							$totalrevenueestimation = 0;
							
							foreach($rowall->budgetingProjectDetail->where('group_count','1')->all() as $row){
								$totalrevenue += $row->nominal;
								$totalrevenueestimation += $row->estimation;
 							}
							
							$totalbuying = 0;
							$totalcogs = 0;
							$totalmkt = 0;
							$totalcri = 0;
							$totalbuyingestimation = 0;
							$totalcogsestimation = 0;
							$totalmktestimation = 0;
							$totalcriestimation = 0;
							
							$totalactualsale = 0;
							$totalactualcogs = 0;
							$totalactualmkt = 0;
							$totalactualcri = 0;
							$totalactualfreight = 0;
							$totalactualimportduty = 0;
							$totalactualsafeguard = 0;
							
							foreach($rowall->budgetingProjectDetail->where('group_count','2')->all() as $row){
								$totalbuying += $row->nominal;
								$totalbuyingestimation += $row->estimation;
							}
							
							foreach($rowall->budgetingProjectDetail->where('group_count','3')->all() as $row){
								$totalcogs += $row->nominal;
								$totalcogsestimation += $row->estimation;
								
								/* if(in_array($row->coa_id,array(288,289,291,292,293,294,295))){
									$totalactualcogs += $row->estimation;
								} */
							}
							
							foreach($rowall->budgetingProjectDetail->where('group_count','4')->all() as $row){
								$totalmkt += $row->nominal;
								$totalmktestimation += $row->estimation;
							}
							
							foreach($rowall->budgetingProjectDetail->where('group_count','5')->all() as $row){
								$totalcri += $row->nominal;
								$totalcriestimation += $row->estimation;
							}
							
							$ppn = 0;
							
							foreach($resultproject as $p){
								$totalactualsale += $p->totalDelivered()['total'] + $p->totalDelivered()['service'] - $p->totalDelivered()['return'];
								if($p->ppn == '1'){
									$ppn = 284;
								}else{
									$ppn = 285;
								}
							}
							
							foreach($resultproject as $p){
								foreach($p->projectDelivery->whereNotNull('received_date') as $pd){
									$data = CashBankDetail::where('coa_id',122)->whereHas('cashBank',function($query) use ($pd){
										$query->where('lookable_type','project_deliveries')->where('lookable_id',$pd->id);
									})->first();
									$totalactualcogs += $data->nominal;
								}
							}
							
							$totalcogs += $totalbuying;
							$totalcogsestimation += $totalbuyingestimation;
						@endphp
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="text-right" colspan="7">Estimation Title : </th>
										<th class="font-italic" colspan="2">
											<span class="estimation-text">{{ $rowall->estimation_name }}</span>
											<input type="text" class="form-control form-control-sm d-none" id="estimation-name" name="estimation-name" value="{{ $rowall->estimation_name }}">
										</th>
									</tr>
								</thead>
								<tbody>
									<tr class="font-italic parent">
										<td>Description</td>
										<td class="text-right">Budget</td>
										<td class="text-right" width="5%">%</td>
										<td class="text-right">Estimation</td>
										<td class="text-right" width="5%">%</td>
										<td class="text-right">Actual</td>
										<td class="text-right" width="5%">%</td>
										<td class="text-right">Variance</td>
										<td class="text-right" width="5%">%</td>
									</tr>
									@foreach($rowall->budgetingProjectDetail->where('group_count','1')->all() as $row)
										@php
											$totalsale = 0;
											if($row->coa_id == $ppn){
												$totalsale = $totalactualsale;
											}
										@endphp
									<tr>
										<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
										<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($row->nominal/$totalrevenue)*100,2) }}%</td>
										<td class="text-right">
											<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
										</td>
										<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
										<td class="text-right">
											@php 
												echo number_format($totalsale,0,',','.');
											@endphp
										</td>
										<td class="text-right">
											{{ $totalsale == 0 ? 0 : round(($totalsale/$totalsale)*100,2) }}%</td>
										<td class="text-right">
											{{ number_format($totalsale - $totalrevenueestimation,0,',','.') }}
										</td>
										<td class="text-right">
											{{ $totalrevenueestimation == 0 ? 0 : number_format((($totalsale - $totalrevenueestimation) / $totalrevenueestimation) * 100,2,',','.')}}
										</td>
									</tr>
									@endforeach
									<tr class="font-italic parent">
										<td>Total Revenue</td>
										<td class="text-right">{{ number_format($totalrevenue,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalrevenue/$totalrevenue)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalrevenueestimation,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($totalrevenueestimation/$totalrevenueestimation)*100,2) }}%</td>
										<td class="text-right">
											{{ number_format($totalactualsale,0,',','.') }}
										</td>
										<td class="text-right">{{ $totalactualsale == 0 ? 0 : round(($totalactualsale/$totalactualsale) * 100,2) }}%</td>
										<td class="text-right">
											{{ number_format($totalactualsale - $totalrevenueestimation,0,',','.') }}
										</td>
										<td class="text-right">
											{{ $totalrevenueestimation == 0 ? 0 : number_format((($totalactualsale - $totalrevenueestimation) / $totalrevenueestimation) * 100,2,',','.') }}
										</td>
									</tr>
									<tr>
										<td colspan="9">COGS</td>
									</tr>
									@foreach($rowall->budgetingProjectDetail->where('group_count','2')->all() as $key => $row)
										<tr>
											<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
											<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
											<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($row->nominal/$totalrevenue)*100,2) }}%</td>
											<td class="text-right">
												<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
											</td>
											<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
											<td class="text-right">
												{{ $key == 2 ? number_format($totalactualcogs,0,',','.') : 0 }}
											</td>
											<td class="text-right">{{ $key == 2 ? ($totalactualsale == 0 ? 0 : round(($totalactualcogs/$totalactualsale)*100,2)) : 0 }}%</td>
											<td class="text-right">
												{{ $key == 2 ? number_format($totalactualcogs - $row->estimation,0,',','.') : 0 }}
											</td>
											<td class="text-right">
												{{ $key == 2 ? ($row->estimation == 0 ? 0 : number_format((($totalactualcogs - $row->estimation) /  $row->estimation) * 100,2,',','.')) : 0 }}
											</td>
										</tr>
									@endforeach
									<tr>
										<td colspan="9">Landed Cost</td>
									</tr>
									@foreach($rowall->budgetingProjectDetail->where('group_count','3')->all() as $row)
										@if($row->coa_id == 292)
											@php 
												$total = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290,288])->sum('nominal');
												$totalestimation = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290,288])->sum('estimation');
												$totalactualcogs += $row->getRealNominal($resultproject);
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
												</td>
												<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
											</tr>
										@elseif($row->coa_id == 293)
											@php 
												$total = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
												$totalestimation = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
												$totalactualcogs += $row->getRealNominal($resultproject);
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
												</td>
												<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
											</tr>
										@elseif($row->coa_id == 294)
											@php 
												$total = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
												$totalestimation = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
												$totalactualcogs += $row->getRealNominal($resultproject);
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
												</td>
												<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
											</tr>
										@elseif($row->coa_id == 295)
											@php 
												$total = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('nominal');
												$totalestimation = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290,292,288])->sum('estimation');
												$totalactualcogs += $row->getRealNominal($resultproject);
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
												</td>
												<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
											</tr>
										@else
											@php
												$total = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290])->sum('nominal');
												$totalestimation = $rowall->budgetingProjectDetail->whereIn('coa_id',[122,290])->sum('estimation');
												$totalactualcogs += $row->getRealNominal($resultproject);
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
												</td>
												<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
											</tr>
										@endif
									@endforeach
									<tr class="font-italic parent">
										<td>Total COGS</td>
										<td class="text-right">{{ number_format($totalcogs,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalcogs/$totalrevenue)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalcogsestimation,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($totalcogsestimation/$totalrevenueestimation)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalactualcogs,0,',','.') }}</td>
										<td class="text-right">{{ $totalactualsale == 0 ? 0 : round(($totalactualcogs/$totalactualsale) * 100,2) }}%</td>
										<td class="text-right">{{ number_format($totalactualcogs - $totalcogsestimation,0,',','.') }}</td>
										<td class="text-right">{{ $totalcogsestimation == 0 ? 0 : round((($totalactualcogs - $totalcogsestimation)/$totalcogsestimation) * 100,2) }}%</td>
									</tr>
									<tr>
										<td colspan="9">Marketing Cost</td>
									</tr>
									@foreach($rowall->budgetingProjectDetail->where('group_count','4')->all() as $row)
										@if($row->coa_id == 139)
											@php
												$total = $rowall->budgetingProjectDetail->whereIn('coa_id',[134])->sum('nominal') + $totalcogs;
												$totalestimation = $rowall->budgetingProjectDetail->whereIn('coa_id',[134])->sum('estimation') + $totalcogsestimation;
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
												</td>
												<td class="text-right">
													{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%
												</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
												</td>
												<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
											</tr>
										@elseif($row->coa_id == 296 || $row->coa_id == 299)
											@php
												$total = $rowall->budgetingProjectDetail->whereIn('coa_id',[284,285])->sum('nominal');
												$totalestimation = $rowall->budgetingProjectDetail->whereIn('coa_id',[284,285])->sum('estimation');
											@endphp
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $total == 0 ? 0 : round(($row->nominal/$total)*100,2) }}%</td>
												<td class="text-right">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right">{{ $totalestimation == 0 ? 0 : round(($row->estimation/$totalestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
												</td>
												<td class="text-right">
													{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%
												</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
												</td>
												<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
											</tr>
										@else
											<tr>
												<td style="padding-left:50px;">{{ $row->coa->name.' '.$row->description }}</td>
												<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
												<td class="text-right">{{ $totalcogs == 0 ? 0 : round(($row->nominal/$totalcogs)*100,2) }}%</td>
												<td class="text-right">
													<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
												</td>
												<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
												</td>
												<td class="text-right">
													{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%
												</td>
												<td class="text-right">
													{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
												</td>
												<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
											</tr>
										@endif
									@endforeach
									<tr class="font-italic parent">
										<td>Total Marketing Cost</td>
										<td class="text-right">{{ number_format($totalmkt,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalmkt/$totalrevenue)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalmktestimation,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($totalmktestimation/$totalrevenueestimation)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalactualmkt,0,',','.') }}</td>
										<td class="text-right">{{ $totalactualsale == 0 ? 0 : round(($totalactualmkt/$totalactualsale) * 100,2) }}%</td>
										<td class="text-right">{{ number_format($totalactualmkt - $totalmktestimation,0,',','.') }}</td>
										<td class="text-right">{{ $totalmktestimation == 0 ? 0 : round((($totalactualmkt - $totalmktestimation)/$totalmktestimation) * 100,2) }}%</td>
									</tr>
									<tr>
										<td colspan="9">Company's Rsv Income (CRI)</td>
									</tr>
									@foreach($rowall->budgetingProjectDetail->where('group_count','5')->all() as $row)
									<tr>
										<td style="padding-left:50px;">{{ $row->coa()['name'].' '.$row->description }}</td>
										<td class="text-right">{{ number_format($row->nominal,0,',','.') }}</td>
										<td class="text-right">{{ $totalcogs == 0 ? 0 : round(($row->nominal/$totalcogs)*100,2) }}%</td>
										<td class="text-right">
											<span class="estimation-text">{{ number_format($row->estimation,0,',','.') }}</span>
										</td>
										<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($row->estimation/$totalrevenueestimation)*100,2) }}%</td>
										<td class="text-right">
											{{ number_format($row->getRealNominal($resultproject),0,',','.') }}
										</td>
										<td class="text-right">
											{{ $totalestimation == 0 ? 0 : round(($row->getRealNominal($resultproject)/$totalestimation)*100,2) }}%
										</td>
										<td class="text-right">
											{{ number_format($row->getRealNominal($resultproject) - $row->estimation,0,',','.') }}
										</td>
										<td class="text-right">{{ $row->estimation == 0 ? 0 : round((($row->getRealNominal($resultproject) - $row->estimation)/ $row->estimation)*100,2) }}%</td>
									</tr>
									@endforeach
									<tr class="font-italic parent">
										<td>Total CRI</td>
										<td class="text-right">{{ number_format($totalcri,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round(($totalcri/$totalrevenue)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalcriestimation,0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round(($totalcriestimation/$totalrevenueestimation)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalactualcri,0,',','.') }}</td>
										<td class="text-right">{{ $totalactualsale == 0 ? 0 : round(($totalactualcri/$totalactualsale) * 100,2) }}%</td>
										<td class="text-right">{{ number_format($totalactualcri - $totalcriestimation,0,',','.') }}</td>
										<td class="text-right">{{ $totalcriestimation == 0 ? 0 : round((($totalactualcri - $totalcriestimation)/$totalcriestimation) * 100,2) }}%</td>
									</tr>
									<tr>
										<td colspan="9">Nett Income</td>
									</tr>
									<tr class="font-italic parent">
										<td>Revenue - (Cogs + Marketing + CRI)</td>
										<td class="text-right">{{ number_format($totalrevenue - ($totalcogs + $totalmkt + $totalcri),0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round((($totalrevenue - ($totalcogs + $totalmkt + $totalcri))/$totalrevenue)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation),0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round((($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation))/$totalrevenueestimation)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalactualsale - ($totalactualcogs + $totalactualmkt + $totalactualcri),0,',','.') }}</td>
										<td class="text-right">
											{{ $totalactualsale == 0 ? 0 : round((($totalactualsale - ($totalactualcogs + $totalactualmkt + $totalactualcri))/$totalactualsale)*100,2) }}%
										</td>
										<td class="text-right">
											{{ number_format(($totalactualsale - ($totalactualcogs + $totalactualmkt + $totalactualcri)) - ($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation)),0,',','.') }}
										</td>
										<td class="text-right">
											{{ $totalactualsale == 0 ? 0 : round(((($totalactualsale - ($totalactualcogs + $totalactualmkt + $totalactualcri)) - ($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation)))/$totalactualsale)*100,2) }}%
										</td>
									</tr>
									<tr class="font-italic parent">
										<td>Total Income</td>
										<td class="text-right">{{ number_format($totalrevenue - ($totalcogs + $totalmkt + $totalcri),0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenue == 0 ? 0 : round((($totalrevenue - ($totalcogs + $totalmkt + $totalcri))/$totalrevenue)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation),0,',','.') }}</td>
										<td class="text-right">{{ $totalrevenueestimation == 0 ? 0 : round((($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation))/$totalrevenueestimation)*100,2) }}%</td>
										<td class="text-right">{{ number_format($totalactualsale - ($totalactualcogs + $totalactualmkt + $totalactualcri),0,',','.') }}</td>
										<td class="text-right">
											{{ $totalactualsale == 0 ? 0 : round((($totalactualsale - ($totalactualcogs + $totalactualmkt + $totalactualcri))/$totalactualsale)*100,2) }}%
										</td>
										<td class="text-right">
											{{ number_format(($totalactualsale - ($totalactualcogs + $totalactualmkt + $totalactualcri)) - ($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation)),0,',','.') }}
										</td>
										<td class="text-right">
											{{ $totalactualsale == 0 ? 0 : round(((($totalactualsale - ($totalactualcogs + $totalactualmkt + $totalactualcri)) - ($totalrevenueestimation - ($totalcogsestimation + $totalmktestimation + $totalcriestimation)))/$totalactualsale)*100,2) }}%
										</td>
									</tr>
								</tbody>
							</table>
						</div>
                    </div>
					@endforeach
				</div>
				@endif
			  </center>
			</div>
		</div>
	</div>
	</div>
<script>
   $(function() {
      //$('.sidebar-main-toggle').click();
	  select2ServerSide('#project_id', '{{ url("admin/select2/project") }}');
	  $('#budget_data').on('click', '#delete_data_budget', function() {
         $(this).closest('tr').remove();
      });
	  $('#project_data').on('click', '#delete_data_project', function() {
         $(this).closest('tr').remove();
      });
	  
		@if(count($resultbudget) > 0)
			$('html, body').animate({
				scrollTop: $('#result_show').offset().top - 150
			}, 'slow');
		@endif
	  
		$('.sidebar-main-toggle').click();
   });
   
	function addBudget(){
		if($('#budgeting_project_id').val()){
			var budgetname = $('#budgeting_project_id').select2('data')[0].text;
			var budgetid = $('#budgeting_project_id').select2('data')[0].id;
		   
			$('#budget_data').append(`
				<tr>
					<input type="hidden" name="budget_id[]" value="` + budgetid + `">
					<td>` + budgetname + `</td>
					<td class="align-middle text-center">
						<button type="button" id="delete_data_budget" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
					 </td>
				</tr>
			`);
			
			$('#budgeting_project_id').val('').trigger('change');
		}
	}
	
	function addProject(){
		if($('#project_id').val()){
			var projectname = $('#project_id').select2('data')[0].text;
			var projectid = $('#project_id').select2('data')[0].id;
		   
			$('#project_data').append(`
				<tr>
					<input type="hidden" name="project_id[]" value="` + projectid + `">
					<td>` + projectname + `</td>
					<td class="align-middle text-center">
						<button type="button" id="delete_data_project" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
					 </td>
				</tr>
			`);
			
			$('#project_id').empty();
			
		}
	}
	
	
</script>