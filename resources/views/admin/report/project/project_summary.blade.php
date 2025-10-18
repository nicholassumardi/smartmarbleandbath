<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Project Summary</span>
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
					<span class="breadcrumb-item active">Project</span>
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
							<option value="">All Project Between Selected Period</option>
							<option value="1">Pending By Payment</option>
							<option value="2">Pending By Qty Delivered</option>
							<option value="3">Search by Project Number, Customer Name</option>
						</select>
					</div>
				</div>
				<div class="col-md-6" id="filter-date">
					<div class="form-group">
                     <label>Date :</label>
                     <div class="input-group">
                        <input type="date" name="filter_start_date" id="filter_start_date" class="form-control">
                        <div class="input-group-prepend">
                           <span class="input-group-text">To</span>
                        </div>
                        <input type="date" name="filter_finish_date" id="filter_finish_date" class="form-control">
                     </div>
					</div>
				</div>
				<div class="col-md-6 d-none" id="filter-project">
					<div class="form-group">
						<label>Project No / Customer name. :</label>
						<input type="text" name="filter_project" id="filter_project" class="form-control">
					</div>
				</div>
            </div>
            <div class="form-group text-center">
				<button type="button" onclick="show()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
				<button type="button" onclick="resetFilter()" class="btn bg-danger"><i class="icon-sync"></i></button>
				<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
					<span class="font-weight-semibold">Information!</span> 
					Search is based on timeline's project date.
				</div>
            </div>
         </div>
		</div>
		<div class="mb-3">
         <h6 class="mb-0 font-weight-semibold text-center text-uppercase">
            <span id="string_filter_periode"></span>
         </h6>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
				   <table id="datatable_serverside" class="table table-bordered table-striped w-100">
					  <thead class="bg-dark sidebar-sticky">
						<tr class="text-center">
							<th rowspan="2" width="1%">No</th>
							<th colspan="2">Project</th>
							<th colspan="5">Sales</th>
							<th colspan="8">Purchase</th>
							<th colspan="8">Delivery</th>
							<th colspan="2">Payment</th>
							<th colspan="3">Balance</th>
						</tr>
						<tr class="text-center">
							<th>Name</th>
							<th>No.</th>
							<th>Sales</th>
							<th>No.</th>
							<th>Qty</th>
							<th>Nominal(B.Tax)</th>
							<th>Nominal(A.Tax)</th>
							<th>No.</th>
							<th>Qty PO</th>
							<th>Qty Rec.</th>
							<th>Nominal(B.Tax)</th>
							<th>Nominal(A.Tax)</th>
							<th>Qty Retur</th>
							<th>Tracking</th>
							<th>Nominal Retur</th>
							<th>No.</th>
							<th>Qty Del.</th>
							<th>Qty Rec.</th>
							<th>Nominal</th>
							<th>Qty Retur</th>
							<th>Nominal Retur</th>
							<th>Tracking</th>
							<th>Other Cost</th>
							<th>No.</th>
							<th>Nominal</th>
							<th>In Qty</th>
							<th>In Nominal</th>
							<th>Payment</th>
						</tr>
					  </thead>
					  <tbody id="body-result">
						<tr>
							<td colspan="29" class="text-center bg-warning">PLEASE CHOOSE FILTER TO SHOW PROJECT SUMMARY</td>
						</tr>
					  </tbody>
					  <tfoot class="bg-dark">
						
					  </tfoot>
				   </table>
				</div>
			</div>
		</div>
	</div>
	
<script>
	$(function() {
		$('.sidebar-main-toggle').click();
		
		var element = $('.sidebar-sticky'),
			originalY = element.offset().top;

		// Space between element and top of screen (when scrolling)
		var topMargin = 73;

		// Should probably be set in CSS; but here just for emphasis
		element.css('position', 'relative');
		element.css('border', '1px solid;');
		
		$(window).on('scroll', function(event) {
			var scrollTop = $(window).scrollTop();

			element.stop(false, false).animate({
				top: scrollTop < originalY
						? 0
						: scrollTop - originalY + topMargin
			}, 0);
		});
		
		$('#method').change(function() {
			if($(this).val() == ''){
				$('#filter-date').removeClass('d-none');
				$('#filter-project').addClass('d-none');
			}else if($(this).val() == '3'){
				$('#filter-date').addClass('d-none');
				$('#filter-project').removeClass('d-none');
			}else{
				$('#filter-date').addClass('d-none');
				$('#filter-project').addClass('d-none');
			}
		});
	});
	
	function show(){
		
		var lolos = false;
		
		if($('#method').val() == '1' || $('#method').val() == '2' || $('#method').val() == '3'){
			lolos = true;
		}else{
			if($('#filter_start_date').val() !== '' && $('#filter_finish_date').val() !== ''){
				lolos = true;
			}
		}
		
		if(lolos == true){
			$.ajax({
				url: '{{ url("admin/report/project/summary/report") }}',
				type: 'POST',
				dataType: 'JSON',
				data: { startDate : $('#filter_start_date').val(), endDate : $('#filter_finish_date').val(), branch : $('#branch').val(), method : $('#method').val(), project : $('#filter_project').val() },
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				beforeSend: function() {
					loadingOpen('#datatable_serverside');
				},
				success: function(response) {
					loadingClose('#datatable_serverside');
					if(response.status == 200) {
						$('#body-result').html(response.content);
					} else {
						notif('warning', 'bg-warning', 'Ups! Error.');
					}
				},
				error: function() {
					loadingClose('#datatable_serverside');
				}
			});
		}else{
			notif('warning', 'bg-warning', 'Ups! Please choose start and end date.');
		}
	}
	
	function resetFilter(){
		$('#filter-project').val('');
		$('#filter-date').removeClass('d-none');
		$('#filter-project').addClass('d-none');
		$('#filter_start_date').val(null);
		$('#filter_finish_date').val(null);
		$('#branch').val(null);
		$('#method').val(null);
		$('#body-result').html('');
		$('#body-result').append(`
			<tr>
				<td colspan="29" class="text-center bg-warning">PLEASE CHOOSE FILTER TO SHOW PROJECT SUMMARY</td>
			</tr>
		`);
	}
</script>