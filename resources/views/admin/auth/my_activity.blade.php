<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">My Activity</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<span class="breadcrumb-item active">My Activity</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <div class="card">
         <div class="card-body">
            <form action="{{ url('admin/my_activity') }}" method="GET">
               @csrf
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Start Date :</label><br>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Finish Date :</label><br>
                        <input type="date" name="finish_date" id="finish_date" class="form-control" value="{{ $finish_date }}">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group text-right mb-0">
                        <a href="{{ url('admin/my_activity') }}" class="btn bg-danger"><i class="icon-sync"></i> Reset</a>
                        <button type="submit" class="btn bg-success"><i class="icon-filter4"></i> Search</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-bordered table-striped">
                  <thead class="bg-dark text-white">
                     <tr class="text-center">
                        <th>No</th>
                        <th>Activity</th>
                        <th>Date & Time</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if($activity->count() > 0)
                        @foreach($activity as $key => $a)
                           <tr class="text-center">
                              <td class="align-middle">{{ $key + 1 }}</td>
                              <td class="align-middle">{{ $a->description }}</td>
                              <td class="align-middle">
                                 {{ date('d F Y, H:i', strtotime($a->created_at)) }}
                              </td>
                           </tr>
                        @endforeach
                     @else
                        <tr class="text-center">
                           <td colspan="3">Data empty</td>
                        </tr>
                     @endif
                  </tbody>
               </table>
               <div class="form-group"><hr></div>
               <div class="form-group">
                  {{ $activity->withQueryString()->onEachSide(1)->links('admin.pagination') }}
               </div>
            </div>
         </div>
      </div>
	</div>