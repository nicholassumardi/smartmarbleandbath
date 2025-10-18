@php
	function rand_color() {
		return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
	}
@endphp
<style>
	.card {
		border: 1px solid #818181;
	}
	.parent {
		background-color:#6b6b6b;
		color:white;
	}
	
	.child {
		/* background-color:{{ rand_color() }};
		color:white; */
	}
</style>
<div class="content-wrapper">
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-arrow-left52 mr-2"></i>
                    <span class="font-weight-semibold">Balance Sheet</span>
                </h4>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                        Dashboard</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Report</a>
                    <a href="javascript:void(0);" class="breadcrumb-item">Accounting</a>
                    <span class="breadcrumb-item active">Balance Sheet</span>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
			<div class="card-header">
				<h4 class="text-muted text-uppercase text-center font-weight-bold">Periode {{ date('F Y', strtotime($filter)) }}</h4>
                <form method="GET" id="form_filter">
                    @csrf
                    <div class="form-group">
                        <center class="d-block">
                            <div class="row justify-content-center">
								<div class="col-md-3 text-left">
									<div class="form-group">
										<label style="margin-bottom: 0rem;">Mode</label>
										<select name="mode" id="mode" class="form-control" onchange="changeMode(this.value)">
											<option value="1" {{ $mode == '1' ? 'selected' : '' }}>Month</option>
											<option value="2" {{ $mode == '2' ? 'selected' : '' }}>Date</option>
										</select>
									</div>
								</div>
								<div class="col-md-3 text-left">
									<div class="form-group">
										<label style="margin-bottom: 0rem;">Branch</label>
										<select name="branch" id="branch" class="form-control">
											<option value="" {{ $branch == '' ? 'selected' : '' }}>All</option>
											@foreach (DB::table('company_entities')->get() as $company)
												<option value="{{$company->id}}" {{$branch == $company->id ? 'selected' : '' }}>{{$company->name}}</option>
											@endforeach
										</select>
									</div>
								</div>
                                <div class="col-md-3 text-left mode1">
									<label style="margin-bottom: 0rem;">Month Year</label>
                                    <input type="month" name="filter" id="filter" class="form-control" value="{{ $filter }}">
                                </div>
								<div class="col-md-3 text-left d-none mode2">
									<div class="form-group">
										<label style="margin-bottom: 0rem;">Date</label>
										<input type="date" name="date" id="date" class="form-control" style="height:32px;" value="{{ $date }}">
									</div>
								</div>
                            </div>
							<div class="row justify-content-center mt-3">
								<div class="col-md-3">
									<button class="btn bg-success btn-sm mr-3" onclick="submitFilter()"><i class="icon-search4"></i> Process</button>
									<a href="{{ url('admin/report/accounting/balance_sheet') }}" class="btn bg-danger btn-sm"><i class="icon-reset"></i> Reset</a>
								</div>
							 </div>
							 <div class="alert alert-info alert-styled-left alert-dismissible mt-3">
								<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
								<span class="font-weight-semibold">Info!</span> Single month mode with choosen branch will create/update journal retained earning for that month.
							 </div>
                        </center>
                    </div>
                </form>
			</div>
            <div class="card-body">
                <div class="row" style="font-size:12px;font-weight:700;max-height:450px;overflow: auto;">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h6 class="card-title font-weight-bold text-uppercase">Current Assets</h6>
                            </div>
                            <div class="card-body">
							@php
								$totalassets = 0;
							@endphp
								@foreach($coa->where('parent_id',0) as $rowparent)
									@if(substr($rowparent->code,0,1) == '1')
									@php
										$total = 0;
									@endphp
									<div class="row border p-1 parent" style="font-size:18px;">
										<div class="col-md-8 text-uppercase font-weight-bolder font-italic">{{ $rowparent->name }}
										</div>
										<div class="col-md-4 text-right">
											@php
											if(count($rowparent->child()) == 0){
												$balance = $rowparent->checkTotal($filter,$branch);
												$total += $balance;
												echo number_format($balance, 2, ',', '.');
											}
											@endphp
										</div>
									</div>
										@foreach($rowparent->child() as $rowchild)
											<div class="row p-1 border child">
												<div class="col-md-9 font-weight-semibold"><ul class="ml-2 list list-unstyled mb-0"><li>{{ $rowchild->name }}</li></ul></div>
												<div class="col-md-3 text-right">
													@php
													
													if(count($rowchild->child()) == 0){
														$balance = $rowchild->checkTotal($filter,$branch);
														$total += $balance;
														echo number_format($balance, 2, ',', '.');
													}
													@endphp
												</div>
											</div>
												@foreach($rowchild->child() as $rowgrandchild)
													<div class="row p-1 border child">
														<div class="col-md-9"><ul class="ml-4 list list-unstyled mb-0"><li>{{ $rowgrandchild->name }} </li></ul></div>
														<div class="col-md-3 text-right">
															@php
															if(count($rowgrandchild->child()) == 0){
																$balance = $rowgrandchild->checkTotal($filter,$branch);
																$total += $balance;
																echo number_format($balance, 2, ',', '.');
															}
															@endphp
														</div>
													</div>
													@foreach($rowgrandchild->child() as $rowgrandgrandchild)
														<div class="row p-1 border child">
															<div class="col-md-9"><ul class="ml-5 list list-unstyled mb-0"><li>{{ $rowgrandgrandchild->name }}</li></ul></div>
															<div class="col-md-3 text-right">
																@php
																if(count($rowgrandgrandchild->child()) == 0){
																	$balance = $rowgrandgrandchild->checkTotal($filter,$branch);
																	$total += $balance;
																	echo number_format($balance, 2, ',', '.');
																}
																@endphp
															</div>
														</div>
													@endforeach
												@endforeach
										@endforeach
									<div class="row border p-1 bg-info">
										<div class="col-md-8 text-uppercase font-weight-bolder font-italic" style="font-size:17px;">TOTAL {{ $rowparent->name }}</div>
										<div class="col-md-4 text-right font-weight-bolder font-italic" style="font-size:17px;">
											{{ number_format($total, 2, ',', '.') }}
										</div>
									</div>
									@php
										$totalassets += $total;
									@endphp
                                    @endif
									<div class="row p-1">
										<div class="col-md-12 text-uppercase font-weight-bolder font-italic" style="font-size:17px;">&nbsp;</div>
									</div>
                                @endforeach
                            </div>
						</div>
                    </div>
                    <div class="col-md-6">
                        <div class="card" style="height:2408px;">
                            <div class="card-header bg-white">
                                <h6 class="card-title font-weight-bold text-uppercase">Current Liabilities</h6>
                            </div>
                            <div class="card-body">
                                @php
								$totalliabilities = 0;
							@endphp
								@foreach($coa->where('parent_id',0) as $rowparent)
									@if(substr($rowparent->code,0,1) == '2' || substr($rowparent->code,0,1) == '3')
										@php
											$total = 0;
										@endphp
										<div class="row border p-1 parent" style="font-size:18px;">
											<div class="col-md-8 text-uppercase font-weight-bolder font-italic">{{ $rowparent->name }}
											</div>
											<div class="col-md-4 text-right">
												@php
												if(count($rowparent->child()) == 0){
													if($rowparent->id == 94){
														$balance = $retained_earning;
													}else{
														$balance = $rowparent->checkTotal($filter,$branch);
													}
													$total += $balance;
													echo number_format($balance, 2, ',', '.');
												}
												@endphp
											</div>
										</div>
											@foreach($rowparent->child() as $rowchild)
												<div class="row p-1 border child">
													<div class="col-md-9 font-weight-semibold"><ul class="ml-2 list list-unstyled mb-0"><li>{{ $rowchild->name }}</li></ul></div>
													<div class="col-md-3 text-right">
														@php
														if(count($rowchild->child()) == 0){
															$balance = $rowchild->checkTotal($filter,$branch);
															$total += $balance;
															echo number_format($balance, 2, ',', '.');
														}
														@endphp
													</div>
												</div>
													@foreach($rowchild->child() as $rowgrandchild)
														<div class="row p-1 border">
															<div class="col-md-9"><ul class="ml-4 list list-unstyled mb-0"><li>{{ $rowgrandchild->name }} </li></ul></div>
															<div class="col-md-3 text-right">
																@php
																if(count($rowgrandchild->child()) == 0){
																	$balance = $rowgrandchild->checkTotal($filter,$branch);
																	$total += $balance;
																	echo number_format($balance, 2, ',', '.');
																}
																@endphp
															</div>
														</div>
														@foreach($rowgrandchild->child() as $rowgrandgrandchild)
															<div class="row p-1 border">
																<div class="col-md-9"><ul class="ml-5 list list-unstyled mb-0"><li>{{ $rowgrandgrandchild->name }}</li></ul></div>
																<div class="col-md-3 text-right">
																	@php
																	if(count($rowgrandgrandchild->child()) == 0){
																		$balance = $rowgrandgrandchild->checkTotal($filter,$branch);
																		$total += $balance;
																		echo number_format($balance, 2, ',', '.');
																	}
																	@endphp
																</div>
															</div>
														@endforeach
													@endforeach
											@endforeach
										<div class="row border p-1 bg-info">
											<div class="col-md-8 text-uppercase font-weight-bolder font-italic" style="font-size:17px;">TOTAL {{ $rowparent->name }}</div>
											<div class="col-md-4 text-right font-weight-bolder font-italic" style="font-size:17px;">
												{{ number_format($total, 2, ',', '.') }}
											</div>
										</div>
										@php
											$totalliabilities += $total;
										@endphp
										<div class="row p-1">
											<div class="col-md-12 text-uppercase font-weight-bolder font-italic" style="font-size:17px;">&nbsp;</div>
										</div>
                                    @endif
                                @endforeach
                            </div>
						</div>
                    </div>
                </div>	
				<div class="row">
					<div class="col-md-12">
                        <div class="row p-2" style="font-size:20px;">
                            <div class="col-md-6">
                                <div class="row p-1 border border bg-success">
                                    <div class="col-md-8 text-uppercase font-weight-bold">Total Current Assets</div>
                                    <div class="col-md-4 font-weight-bold text-right">
                                        {{ number_format($totalassets, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row p-1 border border bg-success">
                                    <div class="col-md-8 text-uppercase font-weight-bold">Total Current Liabilities
                                    </div>
                                    <div class="col-md-4 font-weight-bold text-right">
                                        {{ number_format($totalliabilities, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
						</div>
                    </div>
				</div>
			</div>
        </div>
    </div>
	<script>
		$(function() {
			$('.sidebar-main-toggle').click();
			@if($mode == '1')
				$('.mode2').addClass('d-none');
				$('.mode1').removeClass('d-none');
			@elseif($mode == '2')
				$('.mode1').addClass('d-none');
				$('.mode2').removeClass('d-none');
			@endif
		});
		
		function changeMode(val){
			if(val == '1'){
				$('.mode1').removeClass('d-none');
				$('.mode2').addClass('d-none');
			}else if(val == '2'){
				$('.mode2').removeClass('d-none');
				$('.mode1').addClass('d-none');
			}
		}
	
        function submitFilter() {
            loadingOpen('.content');
			$("#form_filter").submit(function(e){
				var lolos = true;
				
				if($('#mode').val() == '1'){
					$('#date').val('');
				}else if($('#mode').val() == '2'){
					if($('#date').val() == ''){
						lolos = false;
					}
					$('#filter').val('');
				}
				
				if(lolos == false){
					notif('error', 'bg-danger', 'Please check your inputs form.');
					e.preventDefault(e);
				}
				
				loadingClose('.content');
			});
        }
    </script>