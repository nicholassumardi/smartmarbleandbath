<style>
	table.table-bordered {
		border:2px solid black;
	}
	table.table-bordered > thead{
		border:2px solid black;
	}
	table.table-bordered > thead > tr > th{
		border:2px solid black;
	}
	table.table-bordered > tbody > tr > td{
		border:2px solid black;
	}
	table.table-bordered > tfoot > tr > td{
		border:2px solid black;
	}
	
	.parent {
		background-color:#aeafb0;
		color:white;
	}
</style>
<div class="content-wrapper">
    <div class="page-header page-header-light sidebar-sticky">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Budgeting Project Recapitulation</span>
                </h4>
            </div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/sales/budgeting_project') }}" class="btn bg-secondary btn-labeled btn-labeled-left mr-2">
						<b><i class="icon-arrow-left7"></i></b> Back To Budgeting Project
					</a>
					<a href="{{ url('admin/sales/project') }}" class="btn bg-info btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To Sales Project
					</a>
				</div>
			</div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Sales</a>
                    <a href="{{ url('admin/sales/budgeting_project') }}" class="breadcrumb-item">Budgeting</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Project</a>
                    <span class="breadcrumb-item active">List</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<h6 class="text-uppercase font-weight-bold">
							Project Information
						</h6>
						<hr>
					</div>
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td width="40%">Project Name</td>
										<td>: {{ $project->name }}</td>
									</tr>
									<tr>
										<td>Phone</td>
										<td>: {{ $project->customer->phone }}</td>
									</tr>
									<tr>
										<td>Email</td>
										<td>: {{ $project->customer->email }}</td>
									</tr>
									<tr>
										<td>Constructor Name</td>
										<td>: {{ $project->customer->constructor }}</td>
									</tr>
									<tr>
										<td>Country</td>
										<td>: {{ $project->country->name }}</td>
									</tr>
									<tr>
										<td>City</td>
										<td>: {{ $project->city->name }}</td>
									</tr>
									<tr>
										<td>Timeline</td>
										<td>: {{ date('d F Y', strtotime($project->timeline)) }}</td>
									</tr>
									<tr>
										<td>PIC</td>
										<td>: {{ $project->manager }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td width="40%">Consultant Name</td>
										<td>: {{ $project->consultant }}</td>
									</tr>
									<tr>
										<td>Owner</td>
										<td>: {!! $project->owner !!}</td>
									</tr>
									<tr>
										<td>Bank Destination</td>
										<td>: {!! $project->coa->name !!}</td>
									</tr>
									<tr>
										<td>Payment Method</td>
										<td>: {!! $project->paymentMethod() !!}</td>
									</tr>
									<tr>
										<td>Payment Term</td>
										<td>: {!! $project->paymentTerm() !!}</td>
									</tr>
									<tr>
										<td>Supply Method</td>
										<td>: {!! $project->supplyMethod() !!}</td>
									</tr>
									<tr>
										<td>PPN</td>
										<td>: {!! $project->ppn() !!}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
                <div class="form-group">
                    <hr>
                </div>
				<h6 class="text-uppercase font-weight-bold">
                    Budgeting Data
				</h6>
                <div class="row">
                    <div class="col-md-12">
						<div class="table-responsive">
						  <table class="table table-bordered table-striped">
							 <thead class="table-secondary">
								<tr class="text-center">
								   <th rowspan="2">Name</th>
								   <th rowspan="2">Period</th>
								   <th rowspan="2">Product</th>
								   <th colspan="2">Date</th>
								   <th colspan="2">Counter</th>
								   <th colspan="5">Total</th>
								</tr>
								<tr class="text-center">
									<th>Created At</th>
									<th>Updated At</th>
									<th>Revision</th>
									<th>Estimation</th>
									<th>Revenue</th>
									<th>Cogs</th>
									<th>Marketing</th>
									<th>CRI</th>
									<th>Nett Income</th>
								</tr>
							 </thead>
							 <tbody>
								@if(count($budget) == 0)
									<tr>
										<td colspan="12">
											<div class="alert alert-info alert-styled-left alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Empty!</span> There is no budgeting data in this project, you may add <a href="{{ url('admin/sales/budgeting_project').'?project_id='.$project->id }}" class="btn btn-danger">here</a>.</div>
										</td>
									</tr>
								@else
									@php
										$totalallrevenue = 0;
										$totalallcogs = 0;
										$totalallmarketing = 0;
										$totalallcri = 0;
										$totalallnett = 0;
									@endphp
									@foreach($budget as $row)
										@php
											$totalrevenue = $row->getTotalRevenue();
											$totalcogs = $row->getTotalCogs();
											$totalmarketing = $row->getTotalMarketing();
											$totalcri = $row->getTotalCri();
											$totalprofit = $totalrevenue - ($totalcogs + $totalmarketing + $totalcri);
											
											$totalallrevenue += $totalrevenue;
											$totalallcogs += $totalcogs;
											$totalallmarketing += $totalmarketing;
											$totalallcri += $totalcri;
											$totalallnett += $totalprofit;
										@endphp
										<tr>
											<td>{{ $row->name }}</td>
											<td class="text-center">{{ date('M Y',strtotime($row->month_start)).' - '.date('M Y',strtotime($row->month_end)) }}</td>
											<td>
												<ol>
												@foreach($row->budgetingProjectProduct as $pr)
													<li>{{ $pr->product->name().' Qty. '.$pr->qty.' '.$pr->unit() }}</li>
												@endforeach
												</ol>
											</td>
											<td class="text-center">{{ date('d M Y, H:i:s',strtotime($row->created_at)) }}</td>
											<td class="text-center">{{ date('d M Y, H:i:s',strtotime($row->updated_at)) }}</td>
											<td class="text-center">{{ $row->revision_counter }}</td>
											<td class="text-center">{{ $row->estimation_counter	 }}</td>
											<td class="text-center">{{ number_format($totalrevenue,2,',','.') }}</td>
											<td class="text-center">{{ number_format($totalcogs,2,',','.') }}</td>
											<td class="text-center">{{ number_format($totalmarketing,2,',','.') }}</td>
											<td class="text-center">{{ number_format($totalcri,2,',','.') }}</td>
											<td class="text-center">{{ number_format($totalprofit,2,',','.') }}</td>
										</tr>
									@endforeach
										<tr style="font-size:17px;font-weight:800;">
											<td colspan="7" class="text-right">GRANDTOTAL</td>
											<td class="text-center">{{ number_format($totalallrevenue,2,',','.') }}</td>
											<td class="text-center">{{ number_format($totalallcogs,2,',','.') }}</td>
											<td class="text-center">{{ number_format($totalallmarketing,2,',','.') }}</td>
											<td class="text-center">{{ number_format($totalallcri,2,',','.') }}</td>
											<td class="text-center">{{ number_format($totalallnett,2,',','.') }}</td>
										</tr>
										<tr style="font-size:17px;font-weight:800;">
											<td colspan="7" class="text-right">PERCENTAGE</td>
											<td class="text-center bg-success">{{ number_format(($totalallrevenue ? ($totalallrevenue / $totalallrevenue) * 100 : 0),2,',','.') }}%</td>
											<td class="text-center bg-danger">{{ number_format(($totalallrevenue ? ($totalallcogs / $totalallrevenue) * 100 : 0),2,',','.') }}%</td>
											<td class="text-center bg-danger">{{ number_format(($totalallrevenue ? ($totalallmarketing / $totalallrevenue) * 100 : 0),2,',','.') }}%</td>
											<td class="text-center bg-danger">{{ number_format(($totalallrevenue ? ($totalallcri / $totalallrevenue) * 100 : 0),2,',','.') }}%</td>
											<td class="text-center bg-danger">{{ number_format(($totalallrevenue ? ($totalallnett / $totalallrevenue) * 100 : 0),2,',','.') }}%</td>
										</tr>
								@endif
							 </tbody>
						  </table>
						</div>
                    </div>
                </div>	
			</div>
        </div>
    </div>
	
	<script>
		
	</script>