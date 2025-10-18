<style>
	.table td, .table th {
		padding: 0.40rem 0.75rem;
	}
	
	th:first-child, td:first-child
	{
	  position:sticky;
	  left:0px;
	  background-color:#c5c3c3;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i>
					<span class="font-weight-semibold">Create New Budgeting</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/accounting/budgeting') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<a href="{{ url('admin/accounting/budgeting') }}" class="breadcrumb-item">Budgeting</a>
					<span class="breadcrumb-item active">Create Yearly</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      @if($errors->any())
         <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <ul>
               @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      @elseif(session('success'))
         <div class="alert bg-success text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Success!</span> {{ session('success') }}
         </div>
      @elseif(session('failed'))
         <div class="alert bg-danger text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Failed!</span> {{ session('failed') }}
         </div>
      @endif
		<div class="card">
			<div class="card-body">
            <form action="" method="POST" id="form_data">
               @csrf
               <div class="row justify-content-center">
                  <div class="col-md-4">
                     <div class="form-group mb-0">
						<label>Budgeting Year</label>
                        <select name="year" id="year" class="form-control">
							@php
								$yearStart = date('Y') - 5;
								$yearEnd = date('Y') + 5;
								for($i = $yearStart;$i<=$yearEnd;$i++){
									$status = '';
									if($i == date('Y')){
										$status = 'selected';
									}
									echo '<option value="'.$i.'" '.$status.'>'.$i.'</option>';
								} 
							@endphp
                        </select>
                     </div>
                  </div>
				  <div class="col-md-4">
                     <div class="form-group mb-0">
						<label>Branch</label>
                       	 <select name="branch" id="branch" class="custom-select">
							 @foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							 @endforeach
						  </select>
                     </div>
                  </div>
               </div>
               <div class="form-group"><hr></div>
			   <div class="table-responsive">
					<table class="table table-bordered table-striped table-scrollable">
						<thead class="bg-dark">
							<tr>
								<th style="padding:0 100px 0 100px;" class="bg-dark">Coa</th>
								@for($i = 1; $i <= 12; $i++)
									<th style="width:150px;" class="text-center">{{ date('F', strtotime(date('Y') . '-' . $i)) }}</th>
								@endfor
							</tr>
						</thead>
						<tbody>
							@php $no = 1; @endphp
							@foreach($coa as $key => $c)
								@if(explode('.',$c->code)[0] >= 4)
									<tr>
										<td>{{ $no }}. [{{ $c->code }}] {{ $c->name }}</td>
										@for($i = 1; $i <= 12; $i++)
											<td><input type="hidden" name="month[]" value="{{ $i }}">
											<input type="hidden" name="coa_id[]" value="{{ $c->id }}">
											@php $nominal = old('nominal') ? old('nominal')[$i - 1] : 0; @endphp
											<input type="text" name="nominal[]" class="form-control form-control-sm" style="width:150px;" placeholder="Enter nominal" value="{{ $nominal }}" onkeyup="formatRupiah(this)"></td>
										@endfor
									</tr>
									@php $no++; @endphp
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
               <div class="form-group"><hr></div>
               <div class="form-group text-right">
                  <button type="reset" id="btn_reset" class="btn bg-danger btn-labeled btn-labeled-left">
                     <b><i class="icon-sync"></i></b> Reset Form
                  </button>
                  <button type="submit" id="btn_submit" class="btn bg-primary btn-labeled btn-labeled-left">
                     <b><i class="icon-plus3"></i></b> Create
                  </button>
               </div>
            </form>
			</div>
		</div>
	</div>

<script>
	
   $(function() {
		$('.sidebar-main-toggle').click();
		$('#btn_submit').click(function() {
			 $('#btn_reset').attr('disabled', true);
			 $('#btn_submit').attr('disabled', true);
			 $('#btn_submit').html('<b><i class="icon-spinner4 spinner"></i></b> Processed ...');
			 $('#form_data').submit();
		});
   });
</script>
