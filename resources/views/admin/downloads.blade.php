<style>
#chartdiv2 {
  width: 100%;
  height: 400px;
}

#chartdiv {
  width: 100%;
  height: 200px;
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
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('/admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Downloads</a>
				</div>

				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
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
						<h3 class="card-title">Downloads Folder</h3>
					</div>
					<div class="card-body">
						<p>Tab/Click button below to download the files.</p>
						<div class="row">
							<div class="col-md-3 text-center">
								<a href="https://apps.smartmarbleandbath.com/quickcount.apk" class="btn btn-danger btn-float btn-block"><i class="icon-calculator icon-2x"></i> <span>Quickcount</span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>