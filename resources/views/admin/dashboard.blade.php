<style>
#chartdiv2 {
  width: 100%;
  height: 400px;
}

#chartdiv3 {
  width: 100%;
  height: 400px;
}

#chartdiv {
  width: 100%;
  height: 400px;
}
</style>
<div class="content-wrapper">
	<!-- Page header -->
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Dashboard</span></h4>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				<div class="d-flex justify-content-center">
					<a href="javascript:void(0);" class="btn btn-link btn-float text-default" onclick="getToDoList()"><i class="icon-notebook text-primary"></i> <span>To Do</span></a>
				</div>
			</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>

			<div class="header-elements d-none">
				
			</div>
		</div>
	</div>
	<!-- /page header -->
	<div class="content">
		<!-- Main charts -->
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">{{ $year }} Sales Report {{ $branch == '1' ? 'PTA' : 'SMB' }} (Yearly)</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item btn btn-danger btn-sm text-white" data-action="reload" onclick="reset()" data-popup="tooltip" title="Reset Filter"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0">
						<div class="row">
							<div class="col-md-12">
								<form method="GET" id="form_filter_year">
									<center class="d-block">
										<div class="row justify-content-center">
											@csrf
											@if($salesbranch == '1')
											<div class="col-md-3 text-left">
												<div class="form-group">
													<label>Year</label>
													<select name="year" id="year" class="form-control" onchange="submitFilterYear()">
														@for($i=date('Y');$i > (date('Y')-5);$i--)
															<option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
														@endfor
													</select>
												</div>
											</div>
											<div class="col-md-3 text-left">
												<div class="form-group">
													<label>Branch</label>
													<select name="branch" id="branch" class="form-control" onchange="submitFilterYear()">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id}}" {{$company->id == $branch ? 'selected' : ''}}>{{$company->name}}</option>
													 @endforeach
													</select>
												</div>
											</div>
											@endif
										</div>
									</center>
								</form>
								<div id="chartdiv"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header header-elements-inline">
						<h3 class="card-title">{{ date('F Y', strtotime($filter)) }} Sales Report (Monthly)</h3>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item btn btn-danger btn-sm text-white" data-action="reload" onclick="reset()" data-popup="tooltip" title="Reset Filter"></a>
							</div>
						</div>
					</div>
					<div class="card-body py-0">
						<div class="row">
							<div class="col-md-12">
								<form method="GET" id="form_filter">
									<center class="d-block">
										<div class="row justify-content-center">
											@csrf
											@if($salesbranch == '1')
											<div class="col-md-3">
												<div class="form-group">
													<label>Branch</label>
													<select name="branch" id="branch" class="form-control" onchange="submitFilter()">
													@foreach (DB::table('company_entities')->get() as $company)
														<option value="{{$company->id}}" {{$company->id == $branch ? 'selected' : ''}}>{{$company->name}}</option>
													 @endforeach
													</select>
												</div>
											</div>
											@endif
											<div class="col-md-3">
												<div class="form-group">
													<label>Month Year</label>
													<input type="month" name="filter" id="filter" class="form-control" value="{{ $filter }}" onchange="submitFilter()">
												</div>
											</div>
										</div>
									</center>
								</form>
								<div class="row justify-content-center">
									<div class="col-sm-8">
										<div id="chartdiv2"></div>
									</div>
									<div class="col-sm-4">
										<div id="chartdiv3"></div>
									</div>
									<div class="col-sm-12">
										<!-- Members online -->
										<div class="row justify-content-center">
											<div class="col-sm-4">
												<div class="card" style="background-color:#3399ff !important;">
													<div class="card-body" style="background-color:#3399ff !important;color:white;">
														<div class="d-flex">
															<h3 class="font-weight-semibold mb-0" id="nominaltotal"></h3>
															<span class="badge bg-teal-800 badge-pill align-self-center ml-auto" id="percenttotal"></span>
														</div>
														<div>
															Sales Order (SO) <br>
															Pending sales {{ number_format($projectsalebefore,0,',','.') }}<br>
															This month <span id="nominaltotal1"></span>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="card" style="background-color:#009933 !important;">
													<div class="card-body" style="background-color:#009933 !important;color:white;">
														<div class="d-flex">
															<h3 class="font-weight-semibold mb-0" id="nominalpaid"></h3>
															<span class="badge bg-teal-800 badge-pill align-self-center ml-auto" id="percentpaid"></span>
														</div>
														<div>
															Done Sales <br>
															Total Sales <span id="totalsale"></span><br>
															Total Return <span id="totalsalereturn"></span>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-12">
												<div class="text-center mb-3">
													<a class="btn btn-primary" data-toggle="collapse" href="#collapse-link-collapsed">
														Show Details
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row collapse mt-3" id="collapse-link-collapsed">
									<div class="col-sm-12">
										
										<h5 class="card-title">{{ date('F Y', strtotime($filter)) }} Sales Report Lists</h5>
										<div class="card">
											<div class="card-body">
												<ul class="nav nav-tabs nav-tabs-solid bg-slate border-0 nav-justified rounded">
													<li class="nav-item"><a href="#bordered-justified-tab3" class="nav-link" data-toggle="tab">Pending</a></li>
													<li class="nav-item"><a href="#bordered-justified-tab1" class="nav-link active" data-toggle="tab">SO</a></li>
													<li class="nav-item"><a href="#bordered-justified-tab2" class="nav-link" data-toggle="tab">Done</a></li>
													<li class="nav-item"><a href="#bordered-justified-tab4" class="nav-link" data-toggle="tab">Return</a></li>
												</ul>
												
												<div class="tab-content">
													
													<div class="tab-pane fade" id="bordered-justified-tab3">
														<div class="table-responsive">
														   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
															  <thead class="bg-dark">
																 <tr class="text-center">
																	<th>No</th>
																	<th>No. PRJ</th>
																	<th>No. SO</th>
																	<th>Date</th>
																	<th>User</th>
																	<th>Sales</th>
																	<th>Customer</th>
																	<th>Project Name</th>
																	<th>Amount (B4 Tax)</th>
																 </tr>
															  </thead>
															  <tbody>
																@php
																	$totalbefore = 0;
																	$nomor = 1;
																@endphp
																@foreach($datasalesbefore as $val)
																	@php
																	$adadata = false;
																	
																	if($val->sales->branch == $branch){
																		$adadata = true;
																	}
																	
																	if($adadata == true){
																		//$totalbefore += str_replace(',','.',str_replace('.','',$val->getTotalRaw()));
																		$totalbefore += $val->total_sisa;
																	@endphp
																	<tr>
																		<td>{{ $nomor }}</td>
																		<td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
																		<td>{{ $val->code }}</td>
																		<td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
																		<td>{{ $val->project->user->name }}</td>
																		<td>{{ $val->sales->name }}</td>
																		<td>{{ $val->project->customer->name }}</td>
																		<td>{{ $val->project->name }}</td>
																		<td class="text-right">
																			Rp@php
																				echo number_format($val->total_sisa,0,',','.');
																			@endphp
																		</td>
																	</tr>
																@php 
																		$nomor++; 
																	}
																@endphp
																@endforeach
															  </tbody>
															  <tfoot>
																<tr class="bg-teal">
																	<th colspan="7" class="text-right">Total</th>
																	<th colspan="2" class="text-right">Rp{{ number_format($totalbefore,0,',','.') }}</th>
																</tr>
															  </tfoot>
														   </table>
														</div>
													</div>
												
													<div class="tab-pane fade show active" id="bordered-justified-tab1">
														<div class="table-responsive">
														   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
															  <thead class="bg-dark">
																 <tr class="text-center">
																	<th>No</th>
																	<th>No. PRJ</th>
																	<th>No. SO</th>
																	<th>Date</th>
																	<th>User</th>
																	<th>Sales</th>
																	<th>Customer</th>
																	<th>Project Name</th>
																	<th>Amount (B4 Tax)</th>
																 </tr>
															  </thead>
															  <tbody>
																@php
																	$total = 0;
																	$nomor = 1;
																@endphp
																@foreach($projectsale as $val)
																	@php
																	$adadata = false;
																	
																	if($val->sales->branch == $branch){
																		$adadata = true;
																	}
																	
																	if($adadata == true){
																		$total += str_replace(',','.',str_replace('.','',$val->getTotalRawPlusService()));
																	@endphp
																	<tr>
																		<td>{{ $nomor }}</td>
																		<td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
																		<td>{{ $val->code }}</td>
																		<td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
																		<td>{{ $val->project->user->name }}</td>
																		<td>{{ $val->sales->name }}</td>
																		<td>{{ $val->project->customer->name }}</td>
																		<td>{{ $val->project->name }}</td>
																		<td class="text-right">
																			Rp@php
																				echo $val->getTotalRawPlusService();
																			@endphp
																		</td>
																	</tr>
																@php 
																		$nomor++; 
																	}
																@endphp
																@endforeach
															  </tbody>
															  <tfoot>
																<tr class="bg-teal">
																	<th colspan="7" class="text-right">Total</th>
																	<th colspan="2" class="text-right">Rp{{ number_format($total,0,',','.') }}</th>
																</tr>
															  </tfoot>
														   </table>
														</div>
													</div>

													<div class="tab-pane fade" id="bordered-justified-tab2">
														<div class="table-responsive">
														   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
															  <thead class="bg-dark">
																 <tr class="text-center">
																	<th>No</th>
																	<th>No. PRJ</th>
																	<th>No. SO</th>
																	<th>Date</th>
																	<th>User</th>
																	<th>Sales</th>
																	<th>Customer</th>
																	<th>Project Name</th>
																	<th>Amount (B4 Tax)</th>
																 </tr>
															  </thead>
															  <tbody>
																@php
																	$totalpaid = 0;
																	$totalraw = 0;
																	$totalservice = 0;
																	$nomor = 1;
																	$totalallreturn = 0;
																@endphp
																@foreach($projectpaid as $val)
																	@php
																	
																	$adadata = false;
																	
																	if($val->projectSale->sales->branch == $branch){
																		$adadata = true;
																	}
																	
																	if($adadata == true){
																		$totalpaid += $val->getTotalRawPlusService();
																		$totalraw += $val->getTotalRaw();
																		$totalservice += $val->getTotalService();
																		$totalreturn = 0;
																	@endphp
																	<tr>
																		<td>{{ $nomor }}</td>
																		<td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
																		<td>{{ $val->projectSale->code }}</td>
																		<td>{{ date('d M Y',strtotime($val->received_date)) }}</td>
																		<td>{{ $val->user->name }}</td>
																		<td>{{ $val->projectSale->sales->name }}</td>
																		<td>{{ $val->project->customer->name }}</td>
																		<td>{{ $val->project->name }}</td>
																		<td class="text-right">
																			Rp{{ number_format($val->getTotalRawPlusService(),0,',','.') }}
																		</td>
																	</tr>
																@php 
																		$nomor++; 
																	}
																@endphp
																@endforeach
															  </tbody>
															  <tfoot>
																<tr class="bg-teal">
																	<th colspan="7" class="text-right">Total</th>
																	<th colspan="2" class="text-right">Rp{{ number_format($totalpaid,0,',','.') }}</th>
																</tr>
															  </tfoot>
														   </table>
														</div>
													</div>
													
													<div class="tab-pane fade" id="bordered-justified-tab4">
														<div class="table-responsive">
														   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
															  <thead class="bg-dark">
																 <tr class="text-center">
																	<th>No</th>
																	<th>No. PJ</th>
																	<!-- <th>No. SO</th> -->
																	<th>No. Retur</th>
																	<th>Date</th>
																	<th>Sales</th>
																	<th>Customer</th>
																	<th>Project Name</th>
																	<th>Note</th>
																	<th>Amount (B4 Tax)</th>
																 </tr>
															  </thead>
															  <tbody>
																@php
																	$nomor = 1;
																	$totalallreturn = 0;
																@endphp
																@foreach($projectsalereturn as $val)
																	@php
																	
																	$adadata = false;
																	
																	if($val->projectSale->sales->branch == $branch){
																		$adadata = true;
																	}
																	
																	if($adadata == true){
																		
																		if(date('Y-m-d',strtotime($val->projectSale->created_at)) < '2022-04-01'){
																			$ppnpembagi = 1.1;
																		}else{
																			$ppnpembagi = 1.11;
																		}
																		
																		$totalreturn = 0;
																		
																		if($val->project->ppn == '1'){
																			$totalreturn = $val->getTotal() / $ppnpembagi;
																			$totalallreturn += $val->getTotal() / $ppnpembagi;
																		}else{
																			$totalreturn = $val->getTotal();
																			$totalallreturn += $val->getTotal();
																		}
																	@endphp
																	<tr>
																		<td>{{ $nomor }}</td>
																		<td>{{ $val->project->ppn == '1' ? str_replace('PJ','PJP',$val->project->code) : str_replace('PJ','PJN',$val->project->code) }}</td>
																		<!-- <td>{{ $val->projectSale->code }}</td> -->
																		<td>{{ $val->code }}</td>
																		<td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
																		<td>{{ $val->projectSale->sales->name }}</td>
																		<td>{{ $val->project->customer->name }}</td>
																		<td>{{ $val->project->name }}</td>
																		<td>{{ $val->note }}</td>
																		<td class="text-right">
																			Rp{{ number_format($totalreturn,0,',','.') }}
																		</td>
																	</tr>
																@php 
																		$nomor++; 
																	}
																@endphp
																@endforeach
															  </tbody>
															  <tfoot>
																<tr class="bg-teal">
																	<th colspan="7" class="text-right">Total</th>
																	<th colspan="2" class="text-right">Rp{{ number_format($totalallreturn,0,',','.') }}</th>
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="modal_todo" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content" style="max-width: 600px !important;max-height: 100% !important;">
				<div class="modal-header">
					<h5 class="modal-title">To Do List</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="todo-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Resources -->
	<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
	<script>
		$(function() {
			$('.btn-link').on('click', function() {
				//alert('Ups! coming soon!');
				cekNotif();
			});
			
			$("[opacity='0.3']").hide();
		});
		
		function getToDoList(){
			$('#modal_todo').modal('toggle');
			$.ajax({
				url: '{{ url("admin/dashboard/get_todo") }}',
				type: 'GET',
				dataType: 'JSON',
				 data: {
					
				 },
				 beforeSend: function() {
					loadingOpen('#todo-body');
				 },
				 success: function(response) {
					loadingClose('#todo-body');
					if(response){
						
					}
				 },
				 error: function() {
					loadingClose('#todo-body');
					swalInit.fire('Server Error!', 'Please contact developer', 'error');
				 }
			});
			
		}

		function reset(){
			window.location.href = "{{ URL::current() }}";
		}

		function submitFilter() {
            loadingOpen('.content');
            $('#form_filter').submit();
        }
		
		function submitFilterYear() {
            loadingOpen('.content');
            $('#form_filter_year').submit();
        }
		
		am4core.ready(function() {

			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			// Create chart instance
			var chart = am4core.create("chartdiv2", am4charts.RadarChart);

			// Add data
			chart.data = [{
			  "category": "Done",
			  "value": {{ $totalpaid }},
			  "full": {{ $budget }},
			  "config": { "fill": "#009933" }
			},{
			  "category": "Target",
			  "value": {{ $total }},
			  "full": {{ $budget }},
			  "config": { "fill": "#3399ff" }
			}];

			// Make chart not full circle
			chart.startAngle = -90;
			chart.endAngle = 240;
			chart.fontSize = 12;
			chart.innerRadius = am4core.percent(20);

			// Set number format
			chart.numberFormatter.numberFormat = "'Rp'#,###";

			// Create axes
			var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "category";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.grid.template.strokeOpacity = 0;
			categoryAxis.renderer.labels.template.horizontalCenter = "right";
			categoryAxis.renderer.labels.template.fontWeight = 500;
			categoryAxis.renderer.labels.template.adapter.add("fill", function(fill, target) {
			  return (target.dataItem.index >= 0) ? chart.colors.getIndex(target.dataItem.index) : fill;
			});
			categoryAxis.renderer.minGridDistance = 10;

			var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
			valueAxis.renderer.grid.template.strokeOpacity = 0;
			valueAxis.min = 0;
			valueAxis.max = {{ $budget }};
			valueAxis.strictMinMax = true;

			// Create series
			var series1 = chart.series.push(new am4charts.RadarColumnSeries());
			series1.dataFields.valueX = "full";
			series1.dataFields.categoryY = "category";
			series1.clustered = false;
			series1.columns.template.fill = new am4core.InterfaceColorSet().getFor("alternativeBackground");
			series1.columns.template.fillOpacity = 0.08;
			series1.columns.template.cornerRadiusTopLeft = 20;
			series1.columns.template.strokeWidth = 0;
			series1.columns.template.radarColumn.cornerRadius = 0;

			var series2 = chart.series.push(new am4charts.RadarColumnSeries());
			series2.dataFields.valueX = "value";
			series2.dataFields.categoryY = "category";
			series2.clustered = false;
			series2.columns.template.strokeWidth = 0;
			series2.columns.template.tooltipText = "{category}: [bold]{value}[/]";
			series2.columns.template.radarColumn.cornerRadius = 0;
			series2.columns.template.radarColumn.configField = 'config';

			/* series2.columns.template.adapter.add("fill", function(fill, target) {
			  return chart.colors.getIndex(target.dataItem.index);
			}); */
			
			/* var title = chart.titles.create();
			title.text = "Sales Turnover";
			title.fontSize = 20;
			title.marginBottom = 30; */
			
			// Add cursor
			chart.cursor = new am4charts.RadarCursor();
			
			 // Create chart instance
			var chart1 = am4core.create("chartdiv", am4charts.XYChart);

			// Add data
			chart1.data = [{
			  "year": '{{ $filter }}',
			  "Products": {{ round($totalraw,0) }},
			  "Services": {{ round($totalservice,0) }}
			}];
			
			// Create axes
			var categoryAxis = chart1.yAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "year";
			categoryAxis.numberFormatter.numberFormat = "'Rp'#,###";
			categoryAxis.renderer.inversed = true;
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.cellStartLocation = 0.1;
			categoryAxis.renderer.cellEndLocation = 0.9;

			var  valueAxis = chart1.xAxes.push(new am4charts.ValueAxis()); 
			valueAxis.renderer.opposite = true;

			// Create series
			function createSeries(field, name) {
			  var series = chart1.series.push(new am4charts.ColumnSeries());
			  series.dataFields.valueX = field;
			  series.dataFields.categoryY = "year";
			  series.name = name;
			  series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
			  series.columns.template.height = am4core.percent(100);
			  series.sequencedInterpolation = true;

			  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
			  valueLabel.label.text = "{valueX}";
			  valueLabel.label.horizontalCenter = "left";
			  valueLabel.label.dx = 10;
			  valueLabel.label.hideOversized = false;
			  valueLabel.label.truncate = false;

			  var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
			  categoryLabel.label.text = "{name}";
			  categoryLabel.label.horizontalCenter = "right";
			  categoryLabel.label.dx = -10;
			  categoryLabel.label.fill = am4core.color("#fff");
			  categoryLabel.label.hideOversized = false;
			  categoryLabel.label.truncate = false;
			}
			
			var title1 = chart1.titles.create();
			title1.text = "Sales by Products & Services";
			title1.fontSize = 20;
			title1.marginBottom = 30;

			createSeries("Products", "Products");
			createSeries("Services", "Services");

		}); // end am4core.ready()
		
		am4core.ready(function() {

			am4core.useTheme(am4themes_animated);

			var chart = am4core.create("chartdiv", am4charts.XYChart);
			
			chart.legend = new am4charts.Legend();
			
			var data = [
			@php
				foreach($budgetYear as $row){
			@endphp
				{
				  "month": "{{ date('M',strtotime($row['month'])) }}",
				  "sales": {{ $row['totalsale'] }},
				  "done": {{ $row['totaldelivery'] }},
				  "target": {{ $row['totalbudget'] }}
				},
			@php
				}
			@endphp
			];

			/* Create axes */
			var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "month";
			categoryAxis.renderer.grid.template.disabled = true;
			categoryAxis.renderer.cellStartLocation = 0.1;
			categoryAxis.renderer.cellEndLocation = 0.9;
			categoryAxis.renderer.minGridDistance = 30;

			/* Create value axis */
			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
			valueAxis.renderer.grid.template.disabled = true;

			/* Create series */
			var columnSeries = chart.series.push(new am4charts.ColumnSeries());
			columnSeries.name = "Sales ";
			columnSeries.dataFields.valueY = "sales";
			columnSeries.dataFields.categoryX = "month";

			columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
			columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
			columnSeries.columns.template.propertyFields.stroke = "stroke";
			columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
			columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
			columnSeries.tooltip.label.textAlign = "middle";
			columnSeries.fill = am4core.color("#3399ff");
			columnSeries.columns.template.width = am4core.percent(100);
			
			var columnSeries1 = chart.series.push(new am4charts.ColumnSeries());
			columnSeries1.name = "Done ";
			columnSeries1.dataFields.valueY = "done";
			columnSeries1.dataFields.categoryX = "month";

			columnSeries1.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
			columnSeries1.columns.template.propertyFields.fillOpacity = "fillOpacity";
			columnSeries1.columns.template.propertyFields.stroke = "stroke";
			columnSeries1.columns.template.propertyFields.strokeWidth = "strokeWidth";
			columnSeries1.columns.template.propertyFields.strokeDasharray = "columnDash";
			columnSeries1.tooltip.label.textAlign = "middle";
			columnSeries1.fill = am4core.color("#00e600");
			columnSeries1.columns.template.width = am4core.percent(100);

			var lineSeries = chart.series.push(new am4charts.LineSeries());
			lineSeries.name = "Target ";
			lineSeries.dataFields.valueY = "target";
			lineSeries.dataFields.categoryX = "month";

			lineSeries.stroke = am4core.color("#fdd400");
			lineSeries.strokeWidth = 3;
			lineSeries.propertyFields.strokeDasharray = "lineDash";
			lineSeries.tooltip.label.textAlign = "middle";

			var bullet = lineSeries.bullets.push(new am4charts.Bullet());
			bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
			bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]";
			var circle = bullet.createChild(am4core.Circle);
			circle.radius = 4;
			circle.fill = am4core.color("#fff");
			circle.strokeWidth = 3;

			chart.data = data;

		}); // end am4core.ready()
		
		am4core.ready(function() {

			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			// Create chart instance
			var chart3 = am4core.create("chartdiv3", am4charts.XYChart3D);

			// Add data
			chart3.data = [{
				"country": "Sales & Target",
				"salestotal": {{ $projectsalebefore + $total }},
				"targetbudget": {{ $budgetYearRemaining }}
			}];

			// Create axes
			var categoryAxis3 = chart3.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis3.dataFields.category = "country";
			categoryAxis3.renderer.grid.template.location = 0;
			categoryAxis3.renderer.minGridDistance = 30;

			var valueAxis3 = chart3.yAxes.push(new am4charts.ValueAxis());
			valueAxis3.title.text = "Remaining Budgeting Target";
			valueAxis3.renderer.labels.template.adapter.add("text", function(text) {
			  return "Rp " + text;
			});

			// Create series
			var series3 = chart3.series.push(new am4charts.ColumnSeries3D());
			series3.dataFields.valueY = "salestotal";
			series3.dataFields.categoryX = "country";
			series3.name = "Sales";
			series3.clustered = false;
			series3.columns.template.tooltipText = "Sales: Rp [bold]{valueY}[/]";
			series3.columns.template.fillOpacity = 0.9;
			series3.fill = am4core.color("#009933");

			var series4 = chart3.series.push(new am4charts.ColumnSeries3D());
			series4.dataFields.valueY = "targetbudget";
			series4.dataFields.categoryX = "country";
			series4.name = "Target";
			series4.clustered = false;
			series4.columns.template.tooltipText = "Target: Rp [bold]{valueY}[/]";
			series4.fill = am4core.color("#fdd400");
			
			var title3 = chart3.titles.create();
			title3.text = "Period {{ date('F Y', strtotime($filter)) }} Until December {{ date('Y') }}";
			title3.fontSize = 20;
			title3.marginBottom = 30;

		}); // end am4core.ready()
		
		$('#nominaltotal').html('Rp {{ number_format($projectsalebefore + $total,0,',','.') }}');
		$('#nominaltotal1').html('Rp {{ number_format($total,0,',','.') }}');
		$('#percenttotal').html('{{ $budget !== 0 ? round((($total + $projectsalebefore)/$budget) * 100,2) : 0 }}%');
		$('#nominalpaid').html('Rp {{ number_format($totalpaid - $totalallreturn,0,',','.') }}');
		$('#percentpaid').html('{{ $budget !== 0 ? round((($totalpaid - $totalallreturn)/$budget) * 100,2) : 0 }}%');
		$('#nominalbefore').html('Rp {{ number_format($projectsalebefore,0,',','.') }}');
		$('#totalsale').html('Rp {{ number_format($totalpaid,0,',','.') }}');
		$('#totalsalereturn').html('Rp {{ number_format($totalallreturn,0,',','.') }}');
	</script>