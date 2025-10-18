<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Detail Voucher Category</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/master_data/voucher/category') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
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
					<a href="javascript:void(0);" class="breadcrumb-item">Voucher</a>
					<a href="{{ url('admin/master_data/voucher/category') }}" class="breadcrumb-item">Category</a>
					<span class="breadcrumb-item active">Detail</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <div class="card">
         <div class="card-header header-elements-inline">
				<h5 class="card-title">Information Voucher</h5>
			</div>
         <div class="card-body">
            <table cellpadding="10" cellspacing="0" width="100%">
               <tbody>
                  <tr>
                     <th width="20%" class="align-middle">Name</th>
                     <td class="align-middle">: {{ $voucher->name }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Code</th>
                     <td class="align-middle">: {{ $voucher->code }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Category</th>
                     <td class="align-middle">: {{ $voucher->voucherable->name }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Minimum Order</th>
                     <td class="align-middle">: {{ number_format($voucher->minimum, 2, ',', '.') }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Maximum Discount</th>
                     <td class="align-middle">: {{ number_format($voucher->maximum, 2, ',', '.') }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Quota</th>
                     <td class="align-middle">: {{ $voucher->quota }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Points</th>
                     <td class="align-middle">: {{ number_format($voucher->points, 2, ',', '.') }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Percentage</th>
                     <td class="align-middle">: {{ $voucher->percentage }}%</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Start Voucher</th>
                     <td class="align-middle">: {{ date('d F Y', strtotime($voucher->start_date)) }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Expired Voucher</th>
                     <td class="align-middle">: {{ date('d F Y', strtotime($voucher->finish_date)) }}</td>
                  </tr>
                  <tr>
                     <th width="20%" class="align-middle">Type</th>
                     <td class="align-middle">: {{ $voucher->type() }}</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      <div class="card">
         <div class="card-header header-elements-inline mb-3">
				<h5 class="card-title">Terms & Conditions</h5>
			</div>
         <div class="card-body">
            {!! $voucher->terms !!}
         </div>
      </div>
      <div class="card">
         <div class="card-header header-elements-inline mb-3">
				<h5 class="card-title">Order</h5>
            <div class="header-elements text-muted">
               Used : {{ $voucher->order->count() }} | Remaining : {{ $voucher->quota - $voucher->order->count() }}
				</div>
			</div>
         <div class="card-body">
            <table class="table table-bordered table-striped">
               <thead class="table-secondary">
                  <tr class="text-center">
                     <th>Customer</th>
                     <th>Order</th>
                     <th>Date</th>
                  </tr>
               </thead>
               <tbody>
                  @if($voucher->order->count() > 0) 
                     @foreach($voucher->order as $o)
                        <tr class="text-center">
                           <td class="align-middle">{{ $o->customer->name }}</td>
                           <td class="align-middle">{{ $o->number }}</td>
                           <td class="align-middle">{{ date('d F Y', strtotime($o->created_at)) }}</td>
                        </tr>
                     @endforeach
                  @else
                     <tr>
                        <td colspan="3" class="text-center text-muted">Empty</td>
                     </tr>
                  @endif
               </tbody>
            </table>
         </div>
      </div>
	</div>