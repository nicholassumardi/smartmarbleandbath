<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Detail News</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/master_data/digital/news') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
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
					<a href="{{ url('admin/master_data/digital/news') }}" class="breadcrumb-item">News</a>
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
                     <div class="mb-3 text-center">
                        <div class="d-inline-block">
                           <img src="{{ $news->image() }}" class="img-fluid">
                        </div>
                     </div>
                     <h4 class="font-weight-semibold mb-1">
                        <span class="text-default">{{ $news->title }}</span>
                     </h4>
                     <ul class="list-inline list-inline-dotted text-muted mb-3">
                        <li class="list-inline-item">By {{ $news->user->name }}</li>
                        <li class="list-inline-item">{{ date('d F Y', strtotime($news->created_at)) }}</li>
                        <li class="list-inline-item">{{ $news->category->name }}</li>
                        <li class="list-inline-item">{!! $news->status() !!}</li>
                     </ul>
                     @if($news->newsTags)
                        <ul class="list-inline list-inline-condensed mb-0">
                           @foreach($news->newsTags as $nt)
                              <li class="list-inline-item">
                                 <a href="javascript:void(0);">
                                    <span class="badge bg-teal badge-striped text-uppercase font-weight-bold">{{ $nt->tags }}</span>
                                 </a>
                              </li>
                           @endforeach
                        </ul>
                     @else
                        <div class="alert alert-warning text-center">No Tags</div>
                     @endif
                     <div class="form-group"><hr></div>
                     <p>{!! $news->description !!}</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
	</div>