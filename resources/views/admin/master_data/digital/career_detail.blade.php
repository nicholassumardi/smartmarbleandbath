<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Detail Career</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/master_data/digital/career') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Digital</a>
					<a href="{{ url('admin/master_data/digital/career') }}" class="breadcrumb-item">Career</a>
					<span class="breadcrumb-item active">Detail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="d-flex align-items-start flex-column flex-md-row">
         <div class="w-100 overflow-auto order-2 order-md-1">
            <div class="card">
               <div class="card-body">
                  <div class="mb-4">
                     <h4 class="font-weight-semibold mb-1">
                        <span class="text-default">{{ $career->title }}</span>
                     </h4>
                     <ul class="list-inline list-inline-dotted text-muted mb-3">
                        <li class="list-inline-item">Created : {{ date('d F Y', strtotime($career->created_at)) }}</li>
                        <li class="list-inline-item">Deadline : {{ date('d F Y', strtotime($career->deadline)) }}</li>
                        <li class="list-inline-item">{!! $career->status() !!}</li>
                     </ul>
                     <div class="form-group"><hr></div>
                     <h5>Description :</h5>
                     <p>{!! $career->description !!}</p>
                     <div class="form-group"><hr></div>
                     <h5>Requirements :</h5>
                     <p>{!! $career->requirements !!}</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
	</div>