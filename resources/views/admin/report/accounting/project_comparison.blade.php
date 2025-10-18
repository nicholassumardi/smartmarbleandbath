<style>
	.table-bordered td, .table-bordered th { border: 1px solid #818181; }
	td:first-child, .fixed
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
					<span class="font-weight-semibold">Project Comparison</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Report</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
					<span class="breadcrumb-item active">Project Comparison</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
			<div class="card-body">
				<h6 class="text-muted text-uppercase text-center font-weight-bold">Please choose projects to compare!</h6>
				<div class="form-group">
				  <center class="d-block">
					 <div class="row justify-content-center">
						<div class="col-md-6">
							<h6>Real Projects</h6>
							<div class="form-group">
								<select name="project_id" id="project_id" class="select2" onchange="addProject()">
									<option value="">--Choose Project--</option>
									@foreach($project as $row)
										<option value="{{ $row->id }}">{{ $row->code.' '.$row->name.' '.$row->customer->name.' '.number_format($row->getTotalSale(),0,',','.') }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<button id="addproject" class="btn btn-success" onclick="addProject()"><i class="icon-add"></i> Add</button>
							</div>
						</div>
					 </div>
					 <div class="form-group"><hr></div>
					 <form action="{{ url()->full() }}" method="POST">
						 @csrf
						 <div class="row justify-content-center">
							<div class="col-md-6">
								<div class="form-group">
									 <label>Date :</label>
									 <div class="input-group">
										<input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}">
										<div class="input-group-prepend">
										   <span class="input-group-text">To</span>
										</div>
										<input type="date" name="finish_date" id="finish_date" class="form-control" value="{{ $finish_date }}">
									 </div>
								</div>
							</div>
						 </div>
						 <div class="row justify-content-center mt-2">
							<div class="col-md-6">
								<div class="table-responsive">
								   <table class="table table-bordered table-striped">
									  <thead class="bg-dark">
										 <tr class="text-center">
											<th width="80%">Project's Name</th>
											<th>Delete</th>
										 </tr>
									  </thead>
									  <tbody id="project_data">
										@foreach($resultproject as $row)
											<tr>
												<input type="hidden" name="project_id[]" value="{{ $row->id }}">
												<td>{{ $row->name.' - '.$row->code }}</td>
												<td class="align-middle text-center">
													<button type="button" id="delete_data_project" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
												 </td>
											</tr>
										@endforeach
									  </tbody>
								   </table>
								</div>
							</div>
						 </div>
						 <div class="form-group"><hr></div>
						 <div class="row justify-content-center">
							<div class="col-md-12">
								<div class="form-group">
									<button class="btn btn-info mr-2" onclick="processData()"><i class="icon-hour-glass2"></i> Process</button>
									<a href="{{ url('admin/report/accounting/project_comparison') }}" class="btn bg-danger"><i class="icon-sync"></i> Reset</a>
								</div>
							</div>
						 </div>
					</form>
					<div class="form-group"><hr></div>
					<h6 class="text-muted text-uppercase text-center font-weight-bold">RESULTS!</h6>
					<div class="form-group">
						@if($errors->any())
						 <div class="alert bg-warning text-white alert-styled-left alert-dismissible" style="font-size:15px;font-weight:700;background-color:red !important;;color:white !important;">
							<button type="button" class="close" data-dismiss="alert">
							   <span>&times;</span>
							</button>
							<ul>
							   @foreach ($errors->all() as $error)
								  <li>{{ $error }}</li>
							   @endforeach
							</ul>
						 </div>
						@elseif(session('success'))
						 <div class="alert bg-teal text-white alert-styled-left alert-dismissible">
							<button type="button" class="close" data-dismiss="alert">
							   <span>&times;</span>
							</button>
							{{ session('success') }}
						 </div>
						@endif
					</div>
					@if(count($resultproject) > 0)
					@php
						$totalallrevenue = 0;
						$totalallcogs = 0;
						$totalallmarketing = 0;
						$totalallnett = 0;
						$arrtotalrevenue = [];
						$arrtotalcogs = [];
						$arrtotalmarketing = [];
						$arrtotalnett = [];
						
						$arrsales = [];
						$arrservice = [];
						$arrreturn = [];
						$arrcogs = [];
						$arrfreightcost = [];
						$arrlscost = [];
						$arremkl = [];
						$arrimportduty = [];
						$arrsafeguard = [];
						$arrppnimport = [];
						$arrpphimport = [];
						$arrtotalcogs = [];
						$arrfeemkj = [];
						$arrfeepta = [];
						$arrsalescom = [];
						$arrtransport = [];
						$arrstoragecost = [];
						$arrmiddleman = [];
						$arrbankcharge = [];
						$arrbankgiro = [];
						$arrmisccost = [];
						
						foreach($resultproject as $key => $row){
							$arrsales[] = $row->ppn == '1' ? ($row->getProjectJournal(284,$start_date,$finish_date) + $row->getProjectJournal(354,$start_date,$finish_date)) : ($row->getProjectJournal(285,$start_date,$finish_date) + $row->getProjectJournal(355,$start_date,$finish_date));
							$arrservice[] = $row->getProjectJournal(286,$start_date,$finish_date) + $row->getProjectJournal(287,$start_date,$finish_date);
							$arrreturn[] = $row->getProjectJournal(118,$start_date,$finish_date);
							$arrcogs[] = $row->getProjectJournal(122,$start_date,$finish_date) + $row->getProjectJournal(290,$start_date,$finish_date) + $row->getProjectJournal(356,$start_date,$finish_date) + $row->getProjectJournal(363,$start_date,$finish_date) + $row->getProjectJournal(369,$start_date,$finish_date);
							$arrfreightcost[] = $row->getProjectJournal(288,$start_date,$finish_date);
							$arrlscost[] = $row->getProjectJournal(289,$start_date,$finish_date);
							$arremkl[] = $row->getProjectJournal(291,$start_date,$finish_date);
							$arrimportduty[] = $row->getProjectJournal(292,$start_date,$finish_date);
							$arrsafeguard[] = $row->getProjectJournal(293,$start_date,$finish_date);
							$arrppnimport[] = $row->getProjectJournal(294,$start_date,$finish_date);
							$arrpphimport[] = $row->getProjectJournal(295,$start_date,$finish_date);
							
							$arrfeemkj[] = $row->getProjectJournal(134,$start_date,$finish_date);
							$arrfeepta[] = $row->getProjectJournal(139,$start_date,$finish_date);
							$arrsalescom[] = $row->getProjectJournal(296,$start_date,$finish_date);
							$arrtransport[] = $row->getProjectJournal(297,$start_date,$finish_date);
							$arrstoragecost[] = $row->getProjectJournal(298,$start_date,$finish_date);
							$arrmiddleman[] = $row->getProjectJournal(299,$start_date,$finish_date);
							$arrbankcharge[] = $row->getProjectJournal(212,$start_date,$finish_date);
							$arrbankgiro[] = $row->getProjectJournal(213,$start_date,$finish_date);
							$arrmisccost[] = $row->getProjectJournal(328,$start_date,$finish_date);
						}
						
						foreach($resultproject as $key => $row){
							$totalrevenue = $arrsales[$key] + $arrservice[$key] + $arrreturn[$key];
							
							$totalallrevenue += $totalrevenue;
							$totalallcogs += $row->getTotalLandedCost($start_date,$finish_date);
							$totalallmarketing += $row->getTotalMarketingCost($start_date,$finish_date);
							
							$arrtotalrevenue[] = $totalrevenue;
							$arrtotalcogs[] = $row->getTotalLandedCost($start_date,$finish_date);
							$arrtotalmarketing[] = $row->getTotalMarketingCost($start_date,$finish_date);
							
							$nett = $totalrevenue - $row->getTotalLandedCost($start_date,$finish_date) - $row->getTotalMarketingCost($start_date,$finish_date) - $row->getProjectJournal(328,$start_date,$finish_date);
							
							$arrtotalnett[] = $nett;
							$totalallnett += $nett;
						}
				
						$colspan = (count($resultproject) * 2) + 2;
					@endphp
					<div class="form-group" id="result_show">
						<div class="table-responsive" style="overflow:auto;">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th width="400px" class="fixed">Description</th>
										@foreach($resultproject as $row)
										<th width="200px" colspan="2">Project : {{ $row->name.' '.($row->ppn == '1' ? '(PPN)' : '(Non-PPN)') }}</th>
										@endforeach
										<th class="text-right">Total</th>
									</tr>
								</thead>
								</tbody>
									<tr class="parent">
										<td>CUSTOMER</td>
										@foreach($resultproject as $key => $row)
											<td class="text-center" colspan="2">{{ $row->customer->name }}</td>
										@endforeach
										<td class="text-right"></td>
									</tr>
									<tr class="font-italic parent">
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td>REVENUE</td>
											@endif
											<td class="text-right">Nominal</td>
											<td class="text-right">%</td>
										@endforeach
										<td class="text-right"></td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Sales</td>
											@endif
											<td class="text-right">{{ number_format($arrsales[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrsales[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Sales Service</td>
											@endif
											<td class="text-right">{{ number_format($arrservice[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrservice[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Sales Return</td>
											@endif
											<td class="text-right">{{ number_format($arrreturn[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrreturn[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr class="bg-info">
										@for($key=0;$key<count($arrtotalrevenue);$key++)
											@if($key == 0)
											<td>Total Revenue</td>
											@endif
											<td class="text-right">{{ number_format($arrtotalrevenue[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrtotalrevenue[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endfor
										<td class="text-right">{{ number_format($totalallrevenue,0,',','.') }}</td>
									</tr>
									<tr class="font-italic parent">
										<td colspan="{{ $colspan }}">COGS</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">COGS Product Buying</td>
											@endif
											<td class="text-right">{{ number_format($arrcogs[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrcogs[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr class="font-italic parent">
										<td style="padding-left:50px;" colspan="{{ $colspan }}">Landed Cost</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:75px;">Freight Cost</td>
											@endif
											<td class="text-right">{{ number_format($arrfreightcost[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrfreightcost[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:75px;">LS Cost</td>
											@endif
											<td class="text-right">{{ number_format($arrlscost[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrlscost[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:75px;">EMKL</td>
											@endif
											<td class="text-right">{{ number_format($arremkl[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arremkl[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:75px;">Import Duty</td>
											@endif
											<td class="text-right">{{ number_format($arrimportduty[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrimportduty[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:75px;">Safe Guard</td>
											@endif
											<td class="text-right">{{ number_format($arrsafeguard[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrsafeguard[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:75px;">PPN Import</td>
											@endif
											<td class="text-right">{{ number_format($arrppnimport[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrppnimport[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:75px;">PPH Import</td>
											@endif
											<td class="text-right">{{ number_format($arrpphimport[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrpphimport[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr class="bg-info">
										<td>Total COGS</td>
										@foreach($resultproject as $key => $row)
											<td class="text-right">{{ number_format($arrtotalcogs[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrtotalcogs[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">{{ number_format($totalallcogs,0,',','.') }}</td>
									</tr>
									<tr class="font-italic parent">
										<td colspan="{{ $colspan }}">MARKETING COST</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Fee MKJ</td>
											@endif
											<td class="text-right">{{ number_format($arrfeemkj[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrfeemkj[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Fee PTA</td>
											@endif
											<td class="text-right">{{ number_format($arrfeepta[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrfeepta[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Sales Commision</td>
											@endif
											<td class="text-right">{{ number_format($arrsalescom[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrsalescom[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Transport to Client Site / Project</td>
											@endif
											<td class="text-right">{{ number_format($arrtransport[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrtransport[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Storage Cost</td>
											@endif
											<td class="text-right">{{ number_format($arrstoragecost[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrstoragecost[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Middleman & Project Commision</td>
											@endif
											<td class="text-right">{{ number_format($arrmiddleman[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrmiddleman[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Bank Charge</td>
											@endif
											<td class="text-right">{{ number_format($arrbankcharge[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrbankcharge[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Bank Charge Giro</td>
											@endif
											<td class="text-right">{{ number_format($arrbankgiro[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrbankgiro[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr class="bg-info">
										<td>Total Marketing Cost</td>
										@foreach($resultproject as $key => $row)
											<td class="text-right">{{ number_format($arrtotalmarketing[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrtotalmarketing[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">{{ number_format($totalallmarketing,0,',','.') }}</td>
									</tr>
									<tr class="font-italic parent">
										<td colspan="{{ $colspan }}">Company's Rsv Income (CRI)</td>
									</tr>
									<tr>
										@foreach($resultproject as $key => $row)
											@if($key == 0)
											<td style="padding-left:50px;">Miscellaneous Expenses Rsv Profit & Other Cost</td>
											@endif
											<td class="text-right">{{ number_format($arrmisccost[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrmisccost[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">0</td>
									</tr>
									<tr class="font-italic parent">
										<td colspan="{{ $colspan }}">Nett Income</td>
									</tr>
									<tr class="font-italic parent bg-success">
										<td style="padding-left:50px;">Revenue - (Cogs + Marketing + CRI)</td>
										@foreach($resultproject as $key => $row)
											<td class="text-right">{{ number_format($arrtotalnett[$key],0,',','.') }}</td>
											<td class="text-right">{{ $arrtotalrevenue[$key] ? round($arrtotalnett[$key]/$arrtotalrevenue[$key] * 100,2) : 0 }}%</td>
										@endforeach
										<td class="text-right">{{ number_format($totalallnett,0,',','.') }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					@endif
				  </center>
				</div>
			</div>
		</div>
	</div>

<script>
   $(function() {
      //$('.sidebar-main-toggle').click();
	  /* select2ServerSide('#project_id', '{{ url("admin/select2/project") }}'); */

	  $('#project_data').on('click', '#delete_data_project', function() {
         $(this).closest('tr').remove();
      });
	  
		@if(count($resultproject) > 0)
			$('html, body').animate({
				scrollTop: $('#result_show').offset().top - 150
			}, 'slow');
		@endif
	  
		$('.sidebar-main-toggle').click();
   });
	
	function addProject(){
		if($('#project_id').val()){
			var projectname = $('#project_id').select2('data')[0].text;
			var projectid = $('#project_id').select2('data')[0].id;
		   
			$('#project_data').append(`
				<tr>
					<input type="hidden" name="project_id[]" value="` + projectid + `">
					<td>` + projectname + `</td>
					<td class="align-middle text-center">
						<button type="button" id="delete_data_project" class="btn bg-danger btn-sm"><i class="icon-trash"></i></button>   
					 </td>
				</tr>
			`);
			
			$('#project_id').val('').trigger('change');
			
		}
	}
	
	
</script>