<style>
	#body-result {
	  min-height: 400px;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Project Sales Chart</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Data</a>
					<span class="breadcrumb-item active">Project Sales Chart</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-header header-elements-inline">
				<h2 class="card-title font-weight-bold">Filter</h2>
			</div>
         <div class="card-body">
            <div class="row">
				<div class="col-md-12">
					<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
						<span class="font-weight-semibold">Important!</span> 
						This sales report is generated from Project Sales that had Delivery.
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Branch :</label>
						<select name="branch" id="branch" class="form-control">
							<option value="">All</option>
							@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Method :</label>
						<select name="method" id="method" class="form-control">
							<option value="1">All Sales Between Selected Period</option>
							<option value="2">Sales Group By Sales Person Between Selected Period</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                     <label>Period :</label>
                     <div class="input-group">
                        <input type="month" name="filter_start_date" id="filter_start_month" class="form-control">
                        <div class="input-group-prepend">
                           <span class="input-group-text">To</span>
                        </div>
                        <input type="month" name="filter_finish_date" id="filter_finish_month" class="form-control">
                     </div>
					</div>
				</div>
            </div>
            <div class="form-group text-center">
				<button type="button" onclick="generate()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Generate</button>
				<button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
            </div>
         </div>
		</div>
		<div class="mb-3">
         <h6 class="mb-0 font-weight-semibold text-center text-uppercase">
            <span id="string_filter_periode"></span>
         </h6>
		</div>
		<div class="card" id="card-result">
			<div class="card-header">
				<h2 class="card-title font-weight-bold">Result</h2>
			</div>
			<div class="card-body">
				<div class="row justify-content-center">
					<div class="col-md-10">
						<div id="body-result">
							<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
								<span class="font-weight-semibold">Information!</span> 
								Choose mode and press generate button to show chart.
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script>
		
	$(function() {
		$('.sidebar-main-toggle').click();
	});
	
	function chart(param,judul){
		if($('#method').val() == '1'){
			$("#body-result").css("min-height","400px");
			am4core.ready(function() {

				am4core.useTheme(am4themes_animated);
				
				var chart = am4core.create("body-result", am4charts.XYChart);
				
				chart.legend = new am4charts.Legend();
				
				var data = param;

				/* Create axes */
				var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
				categoryAxis.dataFields.category = "month";
				categoryAxis.renderer.cellStartLocation = 0.1;
				categoryAxis.renderer.cellEndLocation = 0.9;
				categoryAxis.renderer.minGridDistance = 30;

				/* Create value axis */
				var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

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
				
				let title = chart.titles.create();
				title.text = judul;
				title.fontSize = 25;
				title.marginBottom = 30;
				
				chart.data = data;

			});
		}else{
			$("#body-result").css("min-height","700px");
			
			am4core.ready(function() {

				am4core.useTheme(am4themes_animated);
				// Themes end

				/**
				 * Chart design taken from Samsung health app
				 */

				var chart = am4core.create("body-result", am4charts.XYChart);
				chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

				chart.paddingRight = 40;
				chart.paddingLeft = 40;

				chart.data = param;

				var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
				categoryAxis.dataFields.category = "name";
				categoryAxis.renderer.grid.template.strokeOpacity = 0;
				categoryAxis.renderer.minGridDistance = 10;
				categoryAxis.renderer.labels.template.dx = -40;
				categoryAxis.renderer.minWidth = 120;
				categoryAxis.renderer.tooltip.dx = -40;

				var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
				valueAxis.renderer.inside = true;
				valueAxis.renderer.labels.template.fillOpacity = 0.3;
				valueAxis.renderer.grid.template.strokeOpacity = 0;
				valueAxis.min = 0;
				valueAxis.cursorTooltipEnabled = false;
				valueAxis.renderer.baseGrid.strokeOpacity = 0;
				valueAxis.renderer.labels.template.dy = 20;

				var series = chart.series.push(new am4charts.ColumnSeries);
				series.dataFields.valueX = "steps";
				series.dataFields.categoryY = "name";
				series.tooltipText = "{valueX.value}";
				series.tooltip.pointerOrientation = "vertical";
				series.tooltip.dy = - 30;
				series.columnsContainer.zIndex = 100;

				var columnTemplate = series.columns.template;
				columnTemplate.height = am4core.percent(50);
				columnTemplate.maxHeight = 50;
				columnTemplate.column.cornerRadius(60, 10, 60, 10);
				columnTemplate.strokeOpacity = 0;

				series.heatRules.push({ target: columnTemplate, property: "fill", dataField: "valueX", min: am4core.color("#e5dc36"), max: am4core.color("#5faa46") });
				series.mainContainer.mask = undefined;

				var cursor = new am4charts.XYCursor();
				chart.cursor = cursor;
				cursor.lineX.disabled = true;
				cursor.lineY.disabled = true;
				cursor.behavior = "none";
				
				let title = chart.titles.create();
				title.text = judul;
				title.fontSize = 25;
				title.marginBottom = 30;

				var bullet = columnTemplate.createChild(am4charts.CircleBullet);
				bullet.circle.radius = 30;
				bullet.valign = "middle";
				bullet.align = "left";
				bullet.isMeasured = true;
				bullet.interactionsEnabled = false;
				bullet.horizontalCenter = "right";
				bullet.interactionsEnabled = false;

				var hoverState = bullet.states.create("hover");
				var outlineCircle = bullet.createChild(am4core.Circle);
				outlineCircle.adapter.add("radius", function (radius, target) {
					var circleBullet = target.parent;
					return circleBullet.circle.pixelRadius + 10;
				})

				var image = bullet.createChild(am4core.Image);
				image.width = 60;
				image.height = 60;
				image.horizontalCenter = "middle";
				image.verticalCenter = "middle";
				image.propertyFields.href = "href";

				image.adapter.add("mask", function (mask, target) {
					var circleBullet = target.parent;
					return circleBullet.circle;
				})

				var previousBullet;
				chart.cursor.events.on("cursorpositionchanged", function (event) {
					var dataItem = series.tooltipDataItem;

					if (dataItem.column) {
						var bullet = dataItem.column.children.getIndex(1);

						if (previousBullet && previousBullet != bullet) {
							previousBullet.isHover = false;
						}

						if (previousBullet != bullet) {

							var hs = bullet.states.getKey("hover");
							hs.properties.dx = dataItem.column.pixelWidth;
							bullet.isHover = true;

							previousBullet = bullet;
						}
					}
				})

			});
		}
	}
	
	function generate(){
		
		if($('#filter_start_month').val() !== '' && $('#filter_finish_month').val() !== ''){
		
			$.ajax({
				url: '{{ url("admin/report/project/sales_chart/generate") }}',
				type: 'POST',
				dataType: 'JSON',
				data: { startMonth : $('#filter_start_month').val(), endMonth : $('#filter_finish_month').val(), branch : $('#branch').val(), method : $('#method').val() },
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#body-result');
				},
				success: function(response) {
					loadingClose('#body-result');
					if(response.status == 200) {
						chart(response.data,response.title);
						$("[opacity='0.3']").hide();
						$('html, body').animate({
							scrollTop: $('#card-result').offset().top - 100
						}, 'slow');
					} else {
						notif('warning', 'bg-warning', 'Ups! Error.');
					}
				},
				error: function() {
					loadingClose('#body-result');
				}
			});
			
		}else{
			notif('warning', 'bg-warning', 'Ups! Please choose start and end months.');
		}
	}
	
	function resetFilter(){
		$('#filter_start_month').val(null);
		$('#filter_finish_month').val(null);
		$('#branch').val(null);
		$('#method').val('1');
		$('#body-result').html('');
		$('#body-result').append(`
			<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
				<span class="font-weight-semibold">Information!</span> 
				Choose mode and press generate button to show chart.
			</div>
		`);
	}
</script>