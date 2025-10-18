<style>
	td:first-child, .fixed
	{
		position:sticky;
		left:0px;
		background-color:inherit;
	}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Profit & Loss Project</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Profit & Loss Project</span>
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
				<div class="col-md-2">
					<div class="form-group">
						<label>Branch :</label>
						<select name="filter_branch" id="filter_branch" class="form-control">
							@foreach (DB::table('company_entities')->get() as $company)
								<option value="{{$company->id}}">{{$company->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
               <div class="col-md-4">
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
			   <div class="col-md-4">
					<div class="form-group">
						<label>&nbsp;</label>
						<div class="input-group">
							<button type="button" onclick="generate()" class="btn bg-purple mr-2"><i class="icon-filter4"></i> Search</button>
							<button type="button" onclick="location.reload();" class="btn bg-danger"><i class="icon-sync"></i></button>
						</div>
					</div>
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
			<div class="card-header header-elements-inline">
				<h5 class="card-title">Results</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12" id="result" style="/* height:400px;overflow:auto; */">
						<div class="alert alert-warning alert-styled-left">
							Please choose branch and date period.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal_form" data-backdrop="static" role="dialog">
	   <div class="modal-dialog modal-lg">
		  <div class="modal-content">
			 <div class="modal-header bg-light">
				<h5 class="modal-title" id="exampleModalLabel">Jurnal Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				   <span aria-hidden="true">&times;</span>
				</button>
			 </div>
			 <div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead class="table-secondary">
							<tr class="text-center">
								<th>C&B Code</th>
								<th width="25%">Coa</th>
								<th width="15%">Date</th>
								<th>Debit</th>
								<th>Kredit</th>
								<th width="30%">Note</th>
							</tr>
						</thead>
						<tbody id="result_journal">
							
						</tbody>
					</table>
				</div>
			 </div>
			 <div class="modal-footer bg-light">
				<button type="button" class="btn bg-secondary" data-dismiss="modal"><i class="icon-switch2"></i> Close</button>
			 </div>
		  </div>
	   </div>
	</div>
<script>
	$(function() {
		$('.sidebar-main-toggle').click();
	});
	
	function getCoaDetails(project_id,coa_id,branch,start_date,finish_date){
		$.ajax({
			 url: '{{ url("admin/report/accounting/profit_loss_project/get_project_journal") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: { project_id : project_id, coa_id : coa_id, branch : branch, start_date : start_date, finish_date : finish_date },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.content-wrapper');
			 },
			 success: function(response) {
				loadingClose('.content-wrapper');
				if(response.status == 200) {
					$('#result_journal').empty();
					$('#result_journal').append(response.result);
				}
				$('#modal_form').modal('toggle');
			 },
			 error: function() {
				loadingClose('.content-wrapper');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
	}
	
	function getCoaDetailsNonProject(coa_id,branch,start_date,finish_date){
		$.ajax({
			 url: '{{ url("admin/report/accounting/profit_loss_project/get_non_project_journal") }}',
			 type: 'POST',
			 dataType: 'JSON',
			 data: { coa_id : coa_id, branch : branch, start_date : start_date, finish_date : finish_date },
			 headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			 },
			 beforeSend: function() {
				loadingOpen('.content-wrapper');
			 },
			 success: function(response) {
				loadingClose('.content-wrapper');
				if(response.status == 200) {
					$('#result_journal').empty();
					$('#result_journal').append(response.result);
				}
				$('#modal_form').modal('toggle');
			 },
			 error: function() {
				loadingClose('.content-wrapper');
				swalInit.fire({
				   title: 'Server Error',
				   text: 'Please contact developer',
				   type: 'error'
				});
			 }
		});
	}
	
	function generate(){
		$.ajax({
		 url: '{{ url("admin/report/accounting/profit_loss_project/get_result") }}',
		 type: 'POST',
		 dataType: 'JSON',
		 data: { branch: $('#filter_branch').val(), start_date: $('#filter_start_date').val(), finish_date: $('#filter_finish_date').val() },
		 headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		 },
		 beforeSend: function() {
			loadingOpen('.content-wrapper');
		 },
		 success: function(response) {
			loadingClose('.content-wrapper');
			if(response.status == 200) {
				$('#result').html(response.result);
				var element1 = $('.sticky-header'),
					originalY1 = element1.offset().top;

				var topMargin1 = 175;

				element1.css('position', 'relative');
				element1.css('z-index', '10');
				element1.css('border', '1px solid;');
				
				$(window).on('scroll', function(event) {
					var scrollTop1 = $(window).scrollTop();

					var imgtop1 = scrollTop1 < originalY1 ? 0 : scrollTop1 - originalY1 + topMargin1;
					element1.css('top', imgtop1 + 'px');
				});
			}
		 },
		 error: function() {
			loadingClose('.content-wrapper');
			swalInit.fire({
			   title: 'Server Error',
			   text: 'Please contact developer',
			   type: 'error'
			});
		 }
		});
	}
</script>