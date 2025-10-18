<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SMB;
use App\Models\User;
use App\Models\Coa;
use App\Models\Approval;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectSale;
use App\Models\ProjectDelivery;
use App\Models\ProjectSaleReturn;
use App\Models\BalanceHistory;
use App\Models\CashBank;
use App\Models\ProjectPurchase;

class DashboardController extends Controller
{

	// public function index(Request $request)
	// {

	// 	if(SMB::cekRole(session('bo_role'),array('1','2','3','4','5','6','7','9','10','11'))){

	// 		$filter = $request->filter ? $request->filter : date('Y-m');
	// 		$salesbranch = User::find(session('bo_id'))->branch;
	// 		$year = $request->year ? $request->year : date('Y');

	// 		if($salesbranch == '1'){
	// 			$branch = $request->branch ? $request->branch : '1';
	// 		}else{
	// 			$branch = '2';
	// 		}

	// 		$salebefore = ProjectSale::whereHas('sales',function($query) use($branch){
	// 			$query->where('branch',$branch);
	// 		})->whereRaw('DATE(created_at) < "'.$filter.'-01"')->orderBy('project_id','DESC')->get();

	// 		$totalbefore = 0;
	// 		$totalclosedbefore = 0;

	// 		$realclosedbefore = [];
	// 		$realsalebefore = [];
	// 		$deliverybefore = [];

	// 		foreach($salebefore as $val){
	// 			if($val->is_closed && $val->approved_closed && substr($val->date_closed,0,7) < $filter){

	// 			}else{
	// 				$adadata = false;
	// 				$adapenjualan = true;

	// 				$totalDelivery = 0;
	// 				$totalReturn = 0;

	// 				foreach($val->projectDelivery()->where('is_sales','1')->whereRaw('received_date < "'.$filter.'-01"')->get() as $pd){
	// 					$totalDelivery += $pd->subtotal_product + $pd->subtotal_service;
	// 					$deliverybefore[] = $pd;
	// 				}

	// 				foreach($val->projectSaleReturn()->whereRaw('date_return < "'.$filter.'-01"')->get() as $pr){
	// 					$totalReturn += $pr->getTotalRawNew();
	// 				}

	// 				$sisa = round($val->subtotal_product + $val->subtotal_service + $totalReturn - $totalDelivery,0);

	// 				if($sisa > 0){
	// 					$adadata = true;
	// 				}

	// 				if($adadata == true){
	// 					$totalbefore += $sisa;
	// 					$val['total_sisa'] = $sisa;
	// 					$realsalebefore[] = $val;
	// 				}
	// 			}
	// 		}

	// 		foreach($salebefore as $val){
	// 			if($val->is_closed && $val->approved_closed && substr($val->date_closed,0,7) == $filter){
	// 				$adadata = false;
	// 				$adapenjualan = true;

	// 				$totalDelivery = 0;
	// 				$totalReturn = 0;

	// 				foreach($val->projectDelivery()->where('is_sales','1')->whereRaw('received_date < "'.$filter.'-01"')->get() as $pd){
	// 					$totalDelivery += $pd->subtotal_product + $pd->subtotal_service;
	// 				}

	// 				foreach($val->projectSaleReturn()->whereRaw('date_return < "'.$filter.'-01"')->get() as $pr){
	// 					$totalReturn += $pr->getTotalRawNew();
	// 				}

	// 				$sisa = round($val->subtotal_product + $val->subtotal_service + $totalReturn - $totalDelivery,0);

	// 				if($sisa > 0){
	// 					$adadata = true;
	// 				}

	// 				if($adadata == true){
	// 					$totalclosedbefore += $sisa;
	// 					$val['total_sisa'] = $sisa;
	// 					$realclosedbefore[] = $val;
	// 				}
	// 			}
	// 		}

	// 		$data = [
	// 			'projectsale' 			=> ProjectSale::whereRaw('SUBSTR(created_at, 1, 7) = "'.$filter.'"')->orderBy('project_id','DESC')->get(),
	// 			'projectsalebefore'		=> round($totalbefore),
	// 			'projectclosedbefore'	=> round($totalclosedbefore),
	// 			'dataclosedbefore'		=> $realclosedbefore,
	// 			'datasalesbefore'		=> $realsalebefore,
	// 			'projectpaid' 			=> ProjectDelivery::whereRaw('SUBSTR(received_date, 1, 7) = "'.$filter.'"')->where('is_sales','1')->orderBy('project_id','DESC')->get(),
	// 			'projectsalereturn' 	=> ProjectSaleReturn::whereRaw('SUBSTR(date_return, 1, 7) = "'.$filter.'"')->orderBy('project_id','DESC')->get(),
	// 			'filter'  				=> $filter,
	// 			'branch'  				=> $branch,
	// 			'salesbranch'			=> $salesbranch,
	// 			'budget'  				=> SMB::total_sales_budget($filter,$branch),
	// 			'budgetYear'  			=> SMB::total_sales_budget_year($branch,$year),
	// 			'budgetYearRemaining'  	=> SMB::total_sales_budget_remaining($filter,$branch),
	// 			'year'					=> $year,
	// 			'title'   				=> 'Dashboard',
	// 			'content' 				=> 'admin.dashboard_main',
	// 			'deliverybefore'		=> $deliverybefore
	// 		];

	// 	}else{
	// 		$data = [
	// 			'title'   				=> 'Dashboard',
	// 			'content' 				=> 'admin.dashboard_other'
	// 		];
	// 	}

	//     return view('admin.layouts.index', ['data' => $data]);
	// }

	public function index(Request $request)
	{

		// if (SMB::cekRole(session('bo_role'), array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11'))) {

		// 	$filter = $request->filter ? $request->filter : date('Y-m');
		// 	$salesbranch = User::find(session('bo_id'))->branch;
		// 	$year = $request->year ? $request->year : date('Y');
		// 	// CashFlow Variable
		// 	$month = $request->filter_cf ? $request->filter_cf : date('Y-m');
		// 	$branch_cashflow = $request->branch_cf ? $request->branch_cf : '1';
		// 	$noweek = 1;
		// 	$totalcredit = 0;
		// 	$totaldebit = 0;
		// 	$balance = 0;
		// 	// End of CashFlow Variable

		// 	if ($salesbranch == '1') {
		// 		$branch = $request->branch ? $request->branch : '1';
		// 	} else {
		// 		$branch = '2';
		// 	}

		// 	$salebefore = ProjectSale::whereHas('sales', function ($query) use ($branch) {
		// 		$query->where('branch', $branch);
		// 	})->whereRaw('DATE(created_at) < "' . $filter . '-01"')->orderBy('project_id', 'DESC')->get();

		// 	$totalbefore = 0;
		// 	$totalclosedbefore = 0;

		// 	$realclosedbefore = [];
		// 	$realsalebefore = [];
		// 	$deliverybefore = [];

		// 	foreach ($salebefore as $val) {
		// 		if ($val->is_closed && $val->approved_closed && substr($val->date_closed, 0, 7) < $filter) {
		// 		} else {
		// 			$adadata = false;
		// 			$adapenjualan = true;

		// 			$totalDelivery = 0;
		// 			$totalReturn = 0;

		// 			foreach ($val->projectDelivery()->where('is_sales', '1')->whereRaw('received_date < "' . $filter . '-01"')->get() as $pd) {
		// 				$totalDelivery += $pd->subtotal_product + $pd->subtotal_service;
		// 				$deliverybefore[] = $pd;
		// 			}

		// 			foreach ($val->projectSaleReturn()->whereRaw('date_return < "' . $filter . '-01"')->get() as $pr) {
		// 				$totalReturn += $pr->getTotalRawNew();
		// 			}

		// 			$sisa = round($val->subtotal_product + $val->subtotal_service + $totalReturn - $totalDelivery, 0);

		// 			if ($sisa > 0) {
		// 				$adadata = true;
		// 			}

		// 			if ($adadata == true) {
		// 				$totalbefore += $sisa;
		// 				$val['total_sisa'] = $sisa;
		// 				$realsalebefore[] = $val;
		// 			}
		// 		}
		// 	}

		// 	foreach ($salebefore as $val) {
		// 		if ($val->is_closed && $val->approved_closed && substr($val->date_closed, 0, 7) == $filter) {
		// 			$adadata = false;
		// 			$adapenjualan = true;

		// 			$totalDelivery = 0;
		// 			$totalReturn = 0;

		// 			foreach ($val->projectDelivery()->where('is_sales', '1')->whereRaw('received_date < "' . $filter . '-01"')->get() as $pd) {
		// 				$totalDelivery += $pd->subtotal_product + $pd->subtotal_service;
		// 			}

		// 			foreach ($val->projectSaleReturn()->whereRaw('date_return < "' . $filter . '-01"')->get() as $pr) {
		// 				$totalReturn += $pr->getTotalRawNew();
		// 			}

		// 			$sisa = round($val->subtotal_product + $val->subtotal_service + $totalReturn - $totalDelivery, 0);

		// 			if ($sisa > 0) {
		// 				$adadata = true;
		// 			}

		// 			if ($adadata == true) {
		// 				$totalclosedbefore += $sisa;
		// 				$val['total_sisa'] = $sisa;
		// 				$realclosedbefore[] = $val;
		// 			}
		// 		}
		// 	}

		// 	// if (in_array(1, session('bo_role'))) {
		// 	// 	$get_cash_flow = SMB::getCashFlow($month, $branch_cashflow);


		// 	// 	foreach ($get_cash_flow ['weeks'] as $key => $row) {

		// 	// 		foreach ($get_cash_flow ['balance_cash_bank'] as $rowcb) {
		// 	// 			if (in_array($rowcb['date'], $row)) {
		// 	// 				$totaldebit += $rowcb['total'];
		// 	// 			}
		// 	// 		}

		// 	// 		foreach (collect($get_cash_flow ['resultdebitbh'])->sortBy('date')->all() as $rowbh) {
		// 	// 			if (in_array($rowbh['date'], $row)) {
		// 	// 				$totaldebit += $rowbh['totalreal'];
		// 	// 			}
		// 	// 		}


		// 	// 		foreach (collect($get_cash_flow ['resultdebit'])->sortBy('date')->all() as $key => $rowar) {
		// 	// 			if (!in_array($rowar['date'], $row)) {
		// 	// 				if ($noweek == 1 && $rowar['date'] < $month . '-01') {
		// 	// 					$totaldebit += $rowar['totalreal'] > 0 ? $rowar['total'] - $rowar['totalreal'] : $rowar['total'];
		// 	// 				}
		// 	// 			} else {
		// 	// 				$totaldebit += $rowar['total'];
		// 	// 			}
		// 	// 		}

		// 	// 		foreach (collect($get_cash_flow ['resultcreditbh'])->sortBy(function ($credit, $key) {
		// 	// 			return $credit['date'];
		// 	// 		})->all() as $rowbh) {
		// 	// 			if (in_array($rowbh['date'], $row)) {
		// 	// 				$totalcredit += $rowbh['totalreal'];
		// 	// 			}
		// 	// 		}

		// 	// 		foreach (collect($get_cash_flow ['resultcredit'])->sortBy(function ($credit, $key) {
		// 	// 			return $credit['fixedcost'] . $credit['date'];
		// 	// 		})->all() as $rowap) {
		// 	// 			if (in_array($rowap['date'], $row)) {
		// 	// 				$totalcredit += $rowap['totalreal'] > 0 ? $rowap['total'] - $rowap['totalreal'] : $rowap['total'];
		// 	// 			} else {
		// 	// 				if ($noweek == 1 && $rowap['date'] < $month . '-01') {
		// 	// 					$totalcredit += $rowap['totalreal'] > 0 ? $rowap['total'] - $rowap['totalreal'] : $rowap['total'];
		// 	// 				}
		// 	// 			}
		// 	// 		}
		// 	// 		$noweek++;
		// 	// 	}

		// 	// 	$balance = $totaldebit - $totalcredit;

		// 	// }

		// 	$data = [
		// 		'projectsale' 			=> ProjectSale::whereRaw('SUBSTR(created_at, 1, 7) = "' . $filter . '"')->orderBy('project_id', 'DESC')->get(),
		// 		'projectsalebefore'		=> round($totalbefore),
		// 		'projectclosedbefore'	=> round($totalclosedbefore),
		// 		'dataclosedbefore'		=> $realclosedbefore,
		// 		'datasalesbefore'		=> $realsalebefore,
		// 		'projectpaid' 			=> ProjectDelivery::whereRaw('SUBSTR(received_date, 1, 7) = "' . $filter . '"')->where('is_sales', '1')->orderBy('project_id', 'DESC')->get(),
		// 		'projectsalereturn' 	=> ProjectSaleReturn::whereRaw('SUBSTR(date_return, 1, 7) = "' . $filter . '"')->orderBy('project_id', 'DESC')->get(),
		// 		'filter'  				=> $filter,
		// 		'branch'  				=> $branch,
		// 		'salesbranch'			=> $salesbranch,
		// 		'budget'  				=> SMB::total_sales_budget($filter, $branch),
		// 		'budgetYear'  			=> SMB::total_sales_budget_year($branch, $year),
		// 		'budgetYearRemaining'  	=> SMB::total_sales_budget_remaining($filter, $branch),
		// 		'year'					=> $year,
		// 		'title'   				=> 'Dashboard',
		// 		'content' 				=> 'admin.dashboard_main',
		// 		'deliverybefore'		=> $deliverybefore,

		// 		'month'					=> $month,
		// 		'branch_cashflow'		=> $branch_cashflow,
		// 		'total_credit' 			=> $totalcredit,
		// 		'total_debit' 			=> $totaldebit,
		// 		'balance'				=> $balance
		// 	];
		// } else {
		$data = [
			'title'   				=> 'Dashboard',
			'content' 				=> 'admin.dashboard_other'
		];
		// }

		return view('admin.layouts.index', ['data' => $data]);
	}

	public function getDashboardData(Request $request)
	{
		$param = $request->param;

		if ($param == '1') {
			$totalOngoingOrder = Project::whereRaw("progress BETWEEN 37 AND 80")->get();
			$projectSale = ProjectSale::whereRaw('SUBSTR(created_at, 1, 7) = "' . date('Y-m') . '"')->get();
			$totalSaleThisMonth = 0;
			foreach ($projectSale as $val) {
				$totalSaleThisMonth += $val->grandtotal_product + $val->grandtotal_service;
			}

			$pp = ProjectPurchase::all();

			$totalPurchaseOrder = 0;

			foreach ($pp as $row) {
				if ($row->getBalanceReceiveWithPurchase()) {
					$totalPurchaseOrder++;
				}
			}

			$dataBalanceInPTA = BalanceHistory::where('type', 'IN')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where('branch', '1')->sum('nominal');

			$dataBalanceOutPTA = BalanceHistory::where('type', 'OUT')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where('branch', '1')->sum('nominal');

			$dataBalanceInSMB = BalanceHistory::where('type', 'IN')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where('branch', '2')->sum('nominal');

			$dataBalanceOutSMB = BalanceHistory::where('type', 'OUT')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where('branch', '2')->sum('nominal');

			$totalBalance = 0;

			if ($dataBalanceInPTA - $dataBalanceOutPTA > 0) {
				$totalBalance = $dataBalanceInPTA - $dataBalanceOutPTA;
			} else {
				$totalBalance = 0;
			}

			if ($dataBalanceInSMB - $dataBalanceOutSMB > 0) {
				$totalBalance += $dataBalanceInSMB - $dataBalanceOutSMB;
			} else {
				$totalBalance += 0;
			}

			$arrProject = Project::whereRaw("progress BETWEEN 37 AND 100")->get();
			$totalsale = 0;
			$totalpaid = 0;
			foreach ($arrProject as $row) {
				$totalsale += $row->getTotalSale();
				foreach ($row->projectPay as $rowpay) {
					$totalpaid += $rowpay->nominal;
				}
			}
			$percentpay = $totalsale > 0 ? round($totalpaid / $totalsale, 2) * 100 : 0;

			$response = [
				'totalOngoingOrder'			=> count($totalOngoingOrder),
				'totalSaleThisMonth'		=> number_format($totalSaleThisMonth, 0, ',', '.'),
				'totalPurchaseOrder'		=> $totalPurchaseOrder,
				'totalAvailableCash'		=> number_format($totalBalance, 2, ',', '.'),
				'percentPay'				=> $percentpay,
				'status'  					=> 200,
			];
		} elseif ($param == '3') {

			$arrMonth = [];

			$branch = $request->branch ? $request->branch : '';
			$mode_in = $request->mode_in;
			$mode_out = $request->mode_out;

			$month = $request->from_month ? strtotime($request->from_month) : strtotime('2021-12');
			$end = $request->to_month ? strtotime($request->to_month) : strtotime(date('Y-m'));
			while ($month <= $end) {
				$arrMonth[] = [
					'monthid' 	=> date('Y-m', $month),
					'monthname'	=> date('M y', $month),
					'total'		=> 0,
					'totalout'	=> 0,
				];
				$month = strtotime("+1 month", $month);
			}

			foreach ($arrMonth as $key => $rowMonth) {
				$month = $rowMonth['monthid'] . '%';
				$dataIn = BalanceHistory::where('type', 'IN')->where('date', 'like', "$month")->whereHas('coa', function ($query) {
					$query->where('code', 'like', "1.000.010%")
						->orWhere('code', 'like', "1.000.020%")
						->orWhere('code', 'like', "1.000.021%")
						->orWhere('code', 'like', "1.000.021%")
						->orWhereIn('parent_id', [9, 12, 15, 18]);
				})->where(function ($query) use ($branch) {
					if ($branch) {
						$query->where('branch', $branch);
					}
				})->get();
				$dataOut = BalanceHistory::where('type', 'OUT')->where('date', 'like', "$month")->whereHas('coa', function ($query) {
					$query->where('code', 'like', "1.000.010%")
						->orWhere('code', 'like', "1.000.020%")
						->orWhere('code', 'like', "1.000.021%")
						->orWhere('code', 'like', "1.000.021%")
						->orWhereIn('parent_id', [9, 12, 15, 18]);
				})->where(function ($query) use ($branch) {
					if ($branch) {
						$query->where('branch', $branch);
					}
				})->get();

				foreach ($dataIn as $row) {

					if ($mode_in == '') {
						$arrMonth[$key]['total'] += $row->nominal;
					}

					if ($mode_in == '1') {
						$cek = CashBank::where('code', 'BPC-' . $row->id)->first();

						if ($cek) {
							if ($cek->lookable_type == 'project_pays' || $cek->lookable_type == 'project_main_payments') {
								$arrMonth[$key]['total'] += $row->nominal;
							}
						}
					}

					if ($mode_in == '2') {
						if ($row->interestBankAndLoan()) {
							$arrMonth[$key]['total'] += $row->nominal;
						}
					}
				}

				foreach ($dataOut as $row) {

					if ($mode_out == '') {
						if ($row->cash_bank_reference) {
							$cek2 = CashBank::find($row->cash_bank_reference);
						} else {
							$cek2 = CashBank::where('code', 'BPC-' . $row->id)->first();
						}

						if (isset($cek2)) {
							foreach ($cek2->cashBankDetail()->whereHas('coa', function ($query) {
								$query->where('code', 'like', "5%")->orWhere('code', 'like', "6%")->orWhere('code', 'like', "7%");
							})->get() as $rowcb) {
								$arrMonth[$key]['totalout'] += $row->nominal;
							}
						}
					}

					if ($row->cash_bank_reference) {
						$cek = CashBank::find($row->cash_bank_reference);
						if ($cek) {
							if ($cek->lookable_type == 'purchase_request_payments' || $cek->lookable_type == 'purchase_request_main_payments' || $cek->lookable_type == 'project_payments') {
								$arrMonth[$key]['totalout'] += $row->nominal;
							}
						}
					}
				}
			}

			$response = [
				'data'	=> $arrMonth,
			];
		}

		return response()->json($response);
	}

	public function getDashboardDetail(Request $request)
	{
		$param = $request->param;

		if ($param == 'ongoingorder') {

			$dataOngoingOrderPTA = Project::whereHas('user', function ($query) {
				$query->where('branch', '1');
			})->whereRaw("progress BETWEEN 37 AND 80")->get();
			$dataOngoingOrderSMB = Project::whereHas('user', function ($query) {
				$query->where('branch', '2');
			})->whereRaw("progress BETWEEN 37 AND 80")->get();

			$response = '<div class="row justify-content-center">';

			$response .= '<div class="col-md-6">';

			$response .= '<table class="table table-bordered">
							<thead class="bg-dark">
								<tr align="center"><th colspan="4">PTA (' . count($dataOngoingOrderPTA) . ')</th></tr>
								<tr align="center"><th>#</th><th>Project</th><th>Customer</th><th>Progress</th></tr>
							</thead><tbody>';

			foreach ($dataOngoingOrderPTA as $key => $row) {
				$response .= '<tr>';

				$response .= 	'<td class="text-center">' . ($key + 1) . '</td>
								<td class="text-center">' . $row->code . '</td>
								<td class="text-center">' . $row->customer->name . '</td>
								<td class="text-center">' . $row->progress . '%</td>
				';

				$response .= '</tr>';
			}

			$response .= '</tbody></table></div>';

			$response .= '<div class="col-md-6">';

			$response .= '<table class="table table-bordered">
							<thead class="bg-dark">
								<tr align="center"><th colspan="4">SMB (' . count($dataOngoingOrderSMB) . ')</th></tr>
								<tr align="center"><th>#</th><th>Project</th><th>Customer</th><th>Progress</th></tr>
							</thead><tbody>';

			foreach ($dataOngoingOrderSMB as $key => $row) {
				$response .= '<tr>';

				$response .= 	'<td class="text-center">' . ($key + 1) . '</td>
								<td class="text-center">' . $row->code . '</td>
								<td class="text-center">' . $row->customer->name . '</td>
								<td class="text-center">' . $row->progress . '%</td>
				';

				$response .= '</tr>';
			}

			$response .= '</tbody></table></div>';

			$response .= '</div>';
		} elseif ($param == 'salesthismonth') {

			$dataSalePTA = ProjectSale::whereHas('sales', function ($query) {
				$query->where('branch', '1');
			})->whereRaw('SUBSTR(created_at, 1, 7) = "' . date('Y-m') . '"')->get();
			$dataSaleSMB = ProjectSale::whereHas('sales', function ($query) {
				$query->where('branch', '2');
			})->whereRaw('SUBSTR(created_at, 1, 7) = "' . date('Y-m') . '"')->get();

			$response = '<div class="row justify-content-center">';

			$response .= '<div class="col-md-6">';

			$response .= '<table class="table table-bordered">
							<thead class="bg-dark">
								<tr align="center"><th colspan="5">PTA (' . count($dataSalePTA) . ')</th></tr>
								<tr align="center"><th>#</th><th>Project</th><th>Customer</th><th>Sales</th><th>Nominal (+Tax)</th></tr>
							</thead><tbody>';

			$total = 0;

			foreach ($dataSalePTA as $key => $row) {
				$response .= '<tr>';

				$response .= 	'<td class="text-center">' . ($key + 1) . '</td>
								<td class="text-center">' . $row->project->code . ' - ' . $row->code . '</td>
								<td class="text-center">' . $row->project->customer->name . '</td>
								<td class="text-center">' . $row->sales->name . '%</td>
								<td class="text-right">Rp ' . number_format(($row->grandtotal_product + $row->grandtotal_service), 2, ',', '.') . '</td>
				';

				$response .= '</tr>';

				$total += $row->grandtotal_product + $row->grandtotal_service;
			}

			$response .= '<tr><td colspan="4" class="text-right">TOTAL</td><td class="text-right">Rp' . number_format($total, 2, ',', '.') . '</td></tr></tbody></table></div>';

			$response .= '<div class="col-md-6">';

			$response .= '<table class="table table-bordered">
							<thead class="bg-dark">
								<tr align="center"><th colspan="5">SMB (' . count($dataSaleSMB) . ')</th></tr>
								<tr align="center"><th>#</th><th>Project</th><th>Customer</th><th>Sales</th><th>Nominal (+Tax)</th></tr>
							</thead><tbody>';

			$total = 0;

			foreach ($dataSaleSMB as $key => $row) {
				$response .= '<tr>';

				$response .= 	'<td class="text-center">' . ($key + 1) . '</td>
								<td class="text-center">' . $row->project->code . ' - ' . $row->code . '</td>
								<td class="text-center">' . $row->project->customer->name . '</td>
								<td class="text-center">' . $row->sales->name . '%</td>
								<td class="text-right">Rp ' . number_format(($row->grandtotal_product + $row->grandtotal_service), 2, ',', '.') . '</td>
				';

				$response .= '</tr>';

				$total += $row->grandtotal_product + $row->grandtotal_service;
			}

			$response .= '<tr><td colspan="4" class="text-right">TOTAL</td><td class="text-right">Rp' . number_format($total, 2, ',', '.') . '</td></tr></tbody></table></div>';

			$response .= '</div>';
		} elseif ($param == 'purchaseorder') {

			$dataPurchasePTA = ProjectPurchase::whereHas('sales', function ($query) {
				$query->where('branch', '1');
			})->get();
			$dataPurchaseSMB = ProjectPurchase::whereHas('sales', function ($query) {
				$query->where('branch', '2');
			})->get();

			$response = '<div class="row justify-content-center">';

			$response .= '<div class="col-md-12">
							<div class="alert alert-info alert-styled-left alert-dismissible mt-3">
								<span class="font-weight-semibold">Info!</span> 
								This data is taken from balance PO product quantity and its Warehouse Receive Quantity
							</div>
						  </div>';

			$response .= '<div class="col-md-6">';

			$response .= '<table class="table table-bordered">
							<thead class="bg-dark">
								<tr align="center"><th colspan="4">PTA (' . count($dataPurchasePTA) . ')</th></tr>
								<tr align="center"><th>#</th><th>Purchase No.</th><th>Supplier</th><th>Sales</th></tr>
							</thead><tbody>';

			$no = 1;

			foreach ($dataPurchasePTA as $key => $row) {
				if ($row->getBalanceReceiveWithPurchase()) {
					$response .= '<tr>';

					$response .= 	'<td class="text-center">' . $no . '</td>
									<td class="text-center">' . $row->code . '</td>
									<td class="text-center">' . $row->supplier->name . '</td>
									<td class="text-center">' . $row->sales->name . '</td>
					';

					$response .= '</tr>';
					$no++;
				}
			}

			$response .= '</tbody></table></div>';

			$response .= '<div class="col-md-6">';

			$response .= '<table class="table table-bordered">
							<thead class="bg-dark">
								<tr align="center"><th colspan="4">SMB (' . count($dataPurchaseSMB) . ')</th></tr>
								<tr align="center"><th>#</th><th>Project</th><th>Customer</th><th>Sales</th></tr>
							</thead><tbody>';

			$no = 1;

			foreach ($dataPurchaseSMB as $key => $row) {
				if ($row->getBalanceReceiveWithPurchase()) {
					$response .= '<tr>';

					$response .= 	'<td class="text-center">' . $no . '</td>
									<td class="text-center">' . $row->code . '</td>
									<td class="text-center">' . $row->supplier->name . '</td>
									<td class="text-center">' . $row->sales->name . '</td>
					';

					$response .= '</tr>';
					$no++;
				}
			}

			$response .= '</tbody></table></div>';

			$response .= '</div>';
		} elseif ($param == 'availablecash') {

			$dataBalanceInPTA = BalanceHistory::where('type', 'IN')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where('branch', '1')->sum('nominal');

			$dataBalanceOutPTA = BalanceHistory::where('type', 'OUT')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where('branch', '1')->sum('nominal');

			$dataBalanceInSMB = BalanceHistory::where('type', 'IN')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where('branch', '2')->sum('nominal');

			$dataBalanceOutSMB = BalanceHistory::where('type', 'OUT')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where('branch', '2')->sum('nominal');
			$response = [
				'availableCashPTA' => 'PTA : ' . number_format((($dataBalanceInPTA - $dataBalanceOutPTA) > 0 ? $dataBalanceInPTA - $dataBalanceOutPTA : 0), 2, ',', '.') . '',
				'availableCashSMB' => 'SMB : ' . number_format((($dataBalanceInSMB - $dataBalanceOutSMB) > 0 ? $dataBalanceInSMB - $dataBalanceOutSMB : 0), 2, ',', '.') . '',
			];
			// $response = '<div class="row justify-content-center">';
			// $response .= '<div class="col-md-4">
			// 					<div class="card card-body" style="background-color:#e60000 !important;color:white !important;">
			// 						<div class="media">
			// 							<div class="media-body">
			// 								<h3 class="mb-0">Rp '.number_format((($dataBalanceInPTA - $dataBalanceOutPTA) > 0 ? $dataBalanceInPTA - $dataBalanceOutPTA : 0),2,',','.').'</h3>
			// 								<span class="text-uppercase font-size-xs">PTA</span>
			// 								<div class="text-italic"><i class="icon-download7"></i> '.number_format($dataBalanceInPTA,2,',','.').' <i class="icon-upload7"></i> '.number_format($dataBalanceOutPTA,2,',','.').'</div>
			// 							</div>

			// 							<div class="ml-3 align-self-center">
			// 								<i class="icon-cash2 icon-3x opacity-75"></i>
			// 							</div>
			// 						</div>
			// 					</div>
			// 				</div>';
			// $response .= '<div class="col-md-4">
			// 					<div class="card card-body" style="background-color:#0099cc !important;color:white !important;">
			// 						<div class="media">
			// 							<div class="media-body">
			// 								<h3 class="mb-0">Rp '.number_format((($dataBalanceInSMB - $dataBalanceOutSMB) > 0 ? $dataBalanceInSMB - $dataBalanceOutSMB : 0),2,',','.').'</h3>
			// 								<span class="text-uppercase font-size-xs">SMB</span>
			// 								<div class="text-italic"><i class="icon-download7"></i> '.number_format($dataBalanceInSMB,2,',','.').' <i class="icon-upload7"></i> '.number_format($dataBalanceOutSMB,2,',','.').'</div>
			// 							</div>

			// 							<div class="ml-3 align-self-center">
			// 								<i class="icon-cash2 icon-3x opacity-75"></i>
			// 							</div>
			// 						</div>
			// 					</div>
			// 				</div>';
			// $response .= '</div>';

		} elseif ($param == 'actualdonesales') {

			$year = $request->year;
			$branch = $request->branch;

			$totaldelivered = 0;
			$totalreturn = 0;

			$response = '<h1 class="text-center">Year ' . $year . '</h1>
							<div class="row">
							<div class="col-md-12">
							<table class="table table-bordered mt-3">
							<thead class="bg-dark">
								<tr align="center">
									<th colspan="8"><h3>Delivered</h3></th>
								</tr>
								<tr align="center">
									<th>#</th>
									<th>Month</th>
									<th>Delivered (B.Tax)</th>
									<th>Returned (B.Tax)</th>
									<th>Balance</th>
								</tr>
							</thead><tbody>';

			for ($i = 1; $i <= 12; $i++) {

				$totalrow = 0;
				$totalrowreturn = 0;

				$month = $year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

				foreach (ProjectDelivery::whereHas('projectSale', function ($query) use ($branch) {
					$query->whereHas('sales', function ($query) use ($branch) {
						$query->where('branch', $branch);
					});
				})->whereNotNull('received_date')->whereRaw("LEFT(received_date,7) = '$month'")->get() as $key => $rowpd) {
					$totalrow += round($rowpd->subtotal_product + $rowpd->subtotal_service);
					$totaldelivered += round($rowpd->subtotal_product + $rowpd->subtotal_service);
				}

				foreach (ProjectSaleReturn::whereHas('projectSale', function ($query) use ($branch) {
					$query->whereHas('sales', function ($query) use ($branch) {
						$query->where('branch', $branch);
					});
				})->whereRaw("LEFT(date_return,7) = '$month'")->get() as $rowsr) {

					$ppnpembagi = 1;

					if (date('Y-m-d', strtotime($rowsr->projectSale->created_at)) < '2022-04-01') {
						$ppnpembagi = 1.1;
					} else {
						$ppnpembagi = 1.11;
					}

					if ($rowsr->project->ppn == '1') {
						$totalrowreturn = round($rowsr->grandtotal / $ppnpembagi);
					} else {
						$totalrowreturn = round($rowsr->grandtotal);
					}

					$totalreturn += $totalrowreturn;
				}

				$response .= '<tr class="text-center">
						<td>' . ($i) . '</td>
						<td>' . date('F Y', strtotime($month)) . '</td>
						<td class="text-right">' . number_format($totalrow, 0, ',', '.') . '</td>
						<td class="text-right">' . number_format($totalrowreturn, 0, ',', '.') . '</td>
						<td class="text-right">' . number_format($totalrow - $totalrowreturn, 0, ',', '.') . '</td>
					</tr>
				';
			}

			$response .= '
					<!-- <tr class="text-right">
						<td colspan="2"><h3>GRANDTOTAL</h3></td>
						<td class="text-right"><h3></h3></td>
						<td class="text-right"><h3></h3></td>
						<td class="text-right"><h3></h3></td>
					</tr> -->
				</tbody>
			</table></div></div>';

			$response .= '
				<div class="row mt-3">
					<div class="col-sm-6 col-xl-4">
						<div class="card card-body bg-blue-400 has-bg-image" style="background-color: #29b6f6 !important;">
							<div class="media">
								<div class="media-body">
									<h3 class="mb-0">' . number_format($totaldelivered, 0, ',', '.') . '</h3>
									<span class="text-uppercase font-size-xs">total delivered</span>
								</div>

								<div class="ml-3 align-self-center">
									<i class="icon-bubbles4 icon-3x opacity-75"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xl-4">
						<div class="card card-body bg-danger-400 has-bg-image" style="background-color: #ef5350 !important;">
							<div class="media">
								<div class="media-body">
									<h3 class="mb-0">' . number_format($totalreturn, 0, ',', '.') . '</h3>
									<span class="text-uppercase font-size-xs">total returned</span>
								</div>

								<div class="ml-3 align-self-center">
									<i class="icon-bag icon-3x opacity-75"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xl-4">
						<div class="card card-body bg-success-400 has-bg-image" style="background-color: #66bb6a !important;">
							<div class="media">
								<div class="mr-3 align-self-center">
									<i class="icon-pointer icon-3x opacity-75"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="mb-0">' . number_format($totaldelivered - $totalreturn, 0, ',', '.') . '</h3>
									<span class="text-uppercase font-size-xs">total balance</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		} elseif ($param == 'cashbank') {

			$branch = $request->branch ? $request->branch : '';
			$mode_in = $request->mode_in;
			$mode_out = $request->mode_out;

			$month = $request->from_month ? $request->from_month . '-01' : '2021-12-01';
			$end = $request->to_month ? date('Y-m-t', strtotime($request->to_month)) : date('Y-m-t');

			$dataInBefore = BalanceHistory::where('type', 'IN')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where(function ($query) use ($branch) {
				if ($branch) {
					$query->where('branch', $branch);
				}
			})->whereRaw("date < '$month'")->sum('nominal');

			$dataOutBefore = BalanceHistory::where('type', 'OUT')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where(function ($query) use ($branch) {
				if ($branch) {
					$query->where('branch', $branch);
				}
			})->whereRaw("date < '$month'")->sum('nominal');

			$dataOut = BalanceHistory::where('type', 'OUT')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where(function ($query) use ($branch) {
				if ($branch) {
					$query->where('branch', $branch);
				}
			})->whereRaw("date BETWEEN '$month' AND '$end'")->get();

			$dataIn = BalanceHistory::where('type', 'IN')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where(function ($query) use ($branch) {
				if ($branch) {
					$query->where('branch', $branch);
				}
			})->whereRaw("date BETWEEN '$month' AND '$end'")->get();

			$dataOut = BalanceHistory::where('type', 'OUT')->whereHas('coa', function ($query) {
				$query->where('code', 'like', "1.000.010%")
					->orWhere('code', 'like', "1.000.020%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhere('code', 'like', "1.000.021%")
					->orWhereIn('parent_id', [9, 12, 15, 18]);
			})->where(function ($query) use ($branch) {
				if ($branch) {
					$query->where('branch', $branch);
				}
			})->whereRaw("date BETWEEN '$month' AND '$end'")->get();

			$response = '<div class="row"><div class="col-md-6">';

			$response .= '<table class="table table-bordered">
							<thead class="bg-dark">
								<tr align="center"><th colspan="6">IN</th></tr>
								<tr align="center"><th>#</th><th>ID</th><th>DATE</th><th>COA</th><th>NOTE</th><th>NOMINAL</th></tr>
							</thead>
							<tbody>';

			$noin = 1;
			$totalIn = $dataInBefore - $dataOutBefore;

			$response .= '<tr><td colspan="5" class="text-right">BALANCE BEFORE</td><td class="text-right"><h3>' . number_format($dataInBefore - $dataOutBefore, 2, ',', '.') . '</h3></td></tr>';

			foreach ($dataIn as $row) {

				if ($mode_in == '') {
					$response .= '
						<tr>
							<td class="text-center">' . $noin . '</td>
							<td class="text-center">' . $row->id . '</td>
							<td class="text-center">' . date('d M Y', strtotime($row->date)) . '</td>
							<td class="text-center">' . $row->coa->name . '</td>
							<td class="text-center">' . $row->note . '</td>
							<td class="text-right">' . number_format($row->nominal, 2, ',', '.') . '</td>
						</tr>
					';

					$totalIn += $row->nominal;
					$noin++;
				}

				if ($mode_in == '1') {

					$cek = CashBank::where('code', 'BPC-' . $row->id)->first();

					if ($cek) {
						if ($cek->lookable_type == 'project_pays' || $cek->lookable_type == 'project_main_payments') {
							$response .= '
								<tr>
									<td class="text-center">' . $noin . '</td>
									<td class="text-center">' . $row->id . '</td>
									<td class="text-center">' . date('d M Y', strtotime($row->date)) . '</td>
									<td class="text-center">' . $row->coa->name . '</td>
									<td class="text-center">' . $row->note . '</td>
									<td class="text-right">' . number_format($row->nominal, 2, ',', '.') . '</td>
								</tr>
							';

							$totalIn += $row->nominal;
							$noin++;
						}
					}
				}

				if ($mode_in == '2') {
					if ($row->interestBankAndLoan()) {
						$response .= '
							<tr>
								<td class="text-center">' . $noin . '</td>
								<td class="text-center">' . $row->id . '</td>
								<td class="text-center">' . date('d M Y', strtotime($row->date)) . '</td>
								<td class="text-center">' . $row->coa->name . '</td>
								<td class="text-center">' . $row->note . '</td>
								<td class="text-right">' . number_format($row->nominal, 2, ',', '.') . '</td>
							</tr>
						';

						$totalIn += $row->nominal;
						$noin++;
					}
				}
			}

			$response .= '
							<tr>
								<td colspan="5" class="text-right">TOTAL</td>
								<td class="text-right"><h3>' . number_format($totalIn, 2, ',', '.') . '</h3></td></tr></tbody></table></div><div class="col-md-6">';

			$response .= '<table class="table table-bordered">
							<thead class="bg-dark">
								<tr align="center"><th colspan="6">OUT</th></tr>
								<tr align="center"><th>#</th><th>ID</th><th>DATE</th><th>COA</th><th>NOTE</th><th>NOMINAL</th></tr>
							</thead>
							<tbody>';

			$noout = 1;
			$totalOut = 0;

			foreach ($dataOut as $row) {

				if ($mode_out == '') {
					if ($row->cash_bank_reference) {
						$cek2 = CashBank::find($row->cash_bank_reference);
					} else {
						$cek2 = CashBank::where('code', 'BPC-' . $row->id)->first();
					}

					if (isset($cek2)) {
						foreach ($cek2->cashBankDetail()->whereHas('coa', function ($query) {
							$query->where('code', 'like', "5%")->orWhere('code', 'like', "6%")->orWhere('code', 'like', "7%");
						})->get() as $rowcb) {
							$response .= '
								<tr>
									<td class="text-center">' . $noout . '</td>
									<td class="text-center">' . $row->id . '</td>
									<td class="text-center">' . date('d M Y', strtotime($row->date)) . '</td>
									<td class="text-center">' . $row->coa->name . '</td>
									<td class="text-center">' . $row->note . '</td>
									<td class="text-right">' . number_format($row->nominal, 2, ',', '.') . '</td>
								</tr>
							';

							$totalOut += $row->nominal;

							$noout++;
						}
					}
				}

				if ($row->cash_bank_reference) {
					$cek = CashBank::find($row->cash_bank_reference);
					if ($cek) {
						if ($cek->lookable_type == 'purchase_request_payments' || $cek->lookable_type == 'purchase_request_main_payments' || $cek->lookable_type == 'project_payments') {
							$response .= '
								<tr>
									<td class="text-center">' . $noout . '</td>
									<td class="text-center">' . $row->id . '</td>
									<td class="text-center">' . date('d M Y', strtotime($row->date)) . '</td>
									<td class="text-center">' . $row->coa->name . '</td>
									<td class="text-center">' . $row->note . '</td>
									<td class="text-right">' . number_format($row->nominal, 2, ',', '.') . '</td>
								</tr>
							';

							$totalOut += $row->nominal;

							$noout++;
						}
					}
				}
			}

			$response .= '<tr><td colspan="5" class="text-right">TOTAL</td><td class="text-right"><h3>' . number_format($totalOut, 2, ',', '.') . '</h3></td></tr></tbody></table></div></div>';
		}

		return response()->json($response);
	}

	public function getDashboardApproval(Request $request)
	{
		$data = Approval::where('user_id', session('bo_id'))->where('seen', 0)->orderBy('created_at', 'desc')->limit(10)->get();

		$result = [];

		foreach ($data as $row) {
			$result[] = [
				'user'			=> '<img src="' . $row->references->photo() . '" class="rounded-circle mr-2" height="34" alt=""><br><i>' . explode(' ', $row->references->name)[0] . '</i>',
				'description'	=> $row->type() . ' Approval' . ' on ' . date('d M Y', strtotime($row->created_at)),
				'action'		=> '<a href="' . url('admin/approval/detail/') . '/' . $row->id . '" class="btn bg-info btn-sm" target="_blank"><i class="icon-magic-wand"></i></a>'
			];
		}

		return response()->json($result);
	}

	public function getDashboardDoneSales(Request $request)
	{
		$year = $request->year;
		$branch = $request->branch;

		$totalbudget = SMB::total_sales_budget_yearly($branch, $year);

		$totaldone = 0;

		foreach (ProjectDelivery::whereHas('projectSale', function ($query) use ($branch) {
			$query->whereHas('sales', function ($query) use ($branch) {
				$query->where('branch', $branch);
			});
		})->whereNotNull('received_date')->whereRaw("LEFT(received_date,4) = '$year'")->get() as $rowpd) {
			$totaldone += round($rowpd->subtotal_product + $rowpd->subtotal_service);
		}

		foreach (ProjectSaleReturn::whereHas('projectSale', function ($query) use ($branch) {
			$query->whereHas('sales', function ($query) use ($branch) {
				$query->where('branch', $branch);
			});
		})->whereRaw("LEFT(date_return,4) = '$year'")->get() as $rowsr) {

			$ppnpembagi = 1;

			if (date('Y-m-d', strtotime($rowsr->projectSale->created_at)) < '2022-04-01') {
				$ppnpembagi = 1.1;
			} else {
				$ppnpembagi = 1.11;
			}

			if ($rowsr->project->ppn == '1') {
				$totaldone -= $rowsr->grandtotal / $ppnpembagi;
			} else {
				$totaldone -= $rowsr->grandtotal;
			}
		}

		$totalvariance = $totalbudget - $totaldone;

		return response()->json([
			'budget'	=> number_format($totalbudget, 0, ',', '.'),
			'sales'		=> number_format($totaldone, 0, ',', '.'),
			'variance'	=> $totalvariance > 0 ? '-' . number_format($totalvariance, 0, ',', '.') : number_format(abs($totalvariance), 0, ',', '.'),
			'status'	=> $totalvariance > 0 ? '-' : '+'
		]);
	}

	public function getDashboardProfitLoss(Request $request)
	{
		$filter = $request->filter ? $request->filter : date('Y-m');
		$coa = Coa::orderBy('code')->get();
		$totalRevenuePTA = 0;
		$totalCOGSPTA = 0;
		$totalRevenueSMB = 0;
		$totalCOGSSMB = 0;


		foreach ($coa as $rowparent) {
			$balance = $rowparent->checkTotalPL($filter, 1);

			if (substr($rowparent->code, 0, 5) == '4.000' || substr($rowparent->code, 0, 5) == '4.100') {
				$totalRevenuePTA += $balance;
			}
			if (substr($rowparent->code, 0, 5) == '5.000' || substr($rowparent->code, 0, 5) == '6.000' || substr($rowparent->code, 0, 5) == '6.100') {
				$totalCOGSPTA += $balance;
			}
		}

		foreach ($coa as $rowparent) {
			$balance = $rowparent->checkTotalPL($filter, 2);

			if (substr($rowparent->code, 0, 5) == '4.000' || substr($rowparent->code, 0, 5) == '4.100') {
				$totalRevenueSMB += $balance;
			}
			if (substr($rowparent->code, 0, 5) == '5.000' || substr($rowparent->code, 0, 5) == '6.000' || substr($rowparent->code, 0, 5) == '6.100') {
				$totalCOGSSMB += $balance;
			}
		}


		$response = [
			'total_revenue_pta' => 'PENJUALAN : ' . number_format((($totalRevenuePTA) > 0 ? $totalRevenuePTA : 0), 2, ',', '.') . '',
			'total_cogs_pta'    => 'COGS : ' . number_format((($totalCOGSPTA) > 0 ? $totalCOGSPTA : 0), 2, ',', '.') . '',
			'total_revenue_smb' => 'PENJUALAN : ' . number_format((($totalRevenueSMB) > 0 ? $totalRevenueSMB : 0), 2, ',', '.') . '',
			'total_cogs_smb'    => 'COGS : ' . number_format((($totalCOGSSMB) > 0 ? $totalCOGSSMB : 0), 2, ',', '.') . '',
			'gross_profit_pta' => $totalRevenuePTA > 0 ? round((($totalRevenuePTA - $totalCOGSPTA) / $totalRevenuePTA) * 100, 2) . ' %' : 0 . ' %',
			'gross_profit_smb' => $totalRevenueSMB > 0 ? round((($totalRevenueSMB - $totalCOGSSMB) / $totalRevenueSMB) * 100, 2) . ' %' : 0 . ' %',
			'filter'			=> $filter,
		];

		return response()->json($response);
	}

	public function printSalesReport(Request $request)
	{

		$filter = $request->filter ? $request->filter : date('Y-m');
		$branch = $request->branch ? $request->branch : '1';
		$branch_name = $branch == '1' ? 'PTA' : 'SMB';

		if($request->mode == '1'){
				$periode_sales_order = "SUBSTR(created_at, 1, 7) = '$filter'";
				$periode_delivery = "SUBSTR(received_date, 1, 7) = '$filter'";
				$periode_retur = "SUBSTR(date_return, 1, 7) = '$filter'";

				$periode_sales_order_before = "DATE(created_at) < '$filter'-01";
				$periode_delivery_before = "received_date < '$filter'-01";
				$periode_retur_before = "date_return < '$filter'-01";

		}else{
			if($request->start_date && $request->finish_date) {
				$periode_sales_order = "DATE(created_at) >= '$request->start_date' AND DATE(created_at) <= '$request->finish_date'";
				$periode_delivery = "DATE(received_date) >= '$request->start_date' AND DATE(received_date) <= '$request->finish_date'";
				$periode_retur = "DATE(date_return) >= '$request->start_date' AND DATE(date_return) <= '$request->finish_date'";

				$periode_sales_order_before = "DATE(created_at) <= '$request->start_date'";
				$periode_delivery_before = "DATE(received_date) <= '$request->start_date'";
				$periode_retur_before = "DATE(date_return) <= '$request->start_date'";


			} else if($request->start_date) {
				$periode_delivery = "DATE(received_date) >= '$request->start_date' AND DATE(received_date) <= CURDATE()";
				$periode_delivery = "DATE(received_date) >= '$request->start_date' AND DATE(received_date) <= CURDATE()";
				$periode_retur = "DATE(date_return) >= '$request->start_date' AND DATE(date_return) <= CURDATE()";

				$periode_sales_order_before = "DATE(created_at) <= '$request->start_date'";
				$periode_delivery_before = "DATE(received_date) <= '$request->start_date'";
				$periode_retur_before = "DATE(date_return) <= '$request->start_date'";
			} else if($request->finish_date) {
				$periode_sales_order = "DATE(created_at) >= CURDATE() AND DATE(created_at) <= '$request->finish_date'";
				$periode_delivery = "DATE(received_date) >= CURDATE() AND DATE(received_date) <= '$request->finish_date'";
				$periode_retur = "DATE(date_return) >= CURDATE() AND DATE(date_return) <= '$request->finish_date'";

				$periode_sales_order_before = "DATE(created_at) >= '$request->finish_date'";
				$periode_delivery_before = "DATE(received_date) <= '$request->finish_date'";
				$periode_retur_before = "DATE(date_return) <= '$request->finish_date'";
			}
		}

		$salebefore = ProjectSale::whereHas('sales', function ($query) use ($request, $branch) {
			$query->where('branch', $branch);

			if($request->sales_id){
				$query->where('id', $request->sales_id);
			}
		})
		->whereHas('project', function ($query) use ($request) {
			if($request->customer_id){
				$query->where('customer_id', $request->customer_id);
			}
		})
		->where('is_closed', null)
		->whereRaw($periode_sales_order_before)
		->orderBy('project_id', 'DESC')
		->get();

		$totalbefore = 0;

		$realsalebefore = [];
		$deliverybefore = [];

		foreach ($salebefore as $val) {
			if ($val->is_closed && $val->approved_closed && substr($val->date_closed, 0, 7) < $filter) {
			} else {
				$adadata = false;

				$totalDelivery = 0;
				$totalReturn = 0;

				foreach ($val->projectDelivery()->where('is_sales', '1')->whereRaw($periode_delivery_before)->get() as $pd) {
					$totalDelivery += $pd->subtotal_product + $pd->subtotal_service;
					$deliverybefore[] = $pd;
				}

				foreach ($val->projectSaleReturn()->whereRaw($periode_retur_before)->get() as $pr) {
					$totalReturn += $pr->getTotalRawNew();
				}

				$sisa = round($val->subtotal_product + $val->subtotal_service + $totalReturn - $totalDelivery, 0);

				if ($sisa > 0) {
					$adadata = true;
				}

				if ($adadata == true) {
					$totalbefore += $sisa;
					$val['total_sisa'] = $sisa;
					$realsalebefore[] = $val;
				}
			}
		}

		$closeso = ProjectSale::whereRaw($periode_sales_order)->whereHas('sales', function ($query) use ($request, $branch) {
			$query->where('branch', $branch);

			if($request->sales_id){
				$query->where('id', $request->sales_id);
			}
			
		})->whereHas('project', function ($query) use ($request) {
			if($request->customer_id){
				$query->where('customer_id', $request->customer_id);
			}
		})
		->where('is_closed', '!=', null)
			->orderBy('project_id', 'DESC')->get();

		$projectsale = ProjectSale::whereRaw($periode_sales_order)->whereHas('sales', function ($query) use ($request, $branch) {
			$query->where('branch', $branch);

			if($request->sales_id){
				$query->where('id', $request->sales_id);
			}
		})->whereHas('project', function ($query) use ($request) {
			if($request->customer_id){
				$query->where('customer_id', $request->customer_id);
			}
		})
		->orderBy('project_id', 'DESC')->get();

		$projectdelivery = ProjectDelivery::whereRaw($periode_delivery)->whereHas('projectSale', function ($query) use ($request, $branch) {
			$query->whereHas('sales', function ($query) use ($request, $branch) {
				$query->where('branch', $branch);

				if($request->sales_id){
					$query->where('id', $request->sales_id);
				}
			});
		})->whereHas('project', function ($query) use ($request) {
			if($request->customer_id){
				$query->where('customer_id', $request->customer_id);
			}
		})
		->where('is_sales', '1')->orderBy('project_id', 'DESC')->get();

		$projectsalereturn = ProjectSaleReturn::whereRaw($periode_retur)->whereHas('projectSale', function ($query) use ($request,$branch) {
			$query->whereHas('sales', function ($query) use ($request, $branch) {
				$query->where('branch', $branch);

				if($request->sales_id){
					$query->where('id', $request->sales_id);
				}
			});
		})->whereHas('project', function ($query) use ($request) {
			if($request->customer_id){
				$query->where('customer_id', $request->customer_id);
			}
		})
		->orderBy('project_id', 'DESC')->get();

		$data = [
			'title'					=> 'Sales Report Monthly ' . $branch_name,
			'closeso' 			    => $closeso,
			'datasalesbefore' 	    => $realsalebefore,
			'projectsale' 			=> $projectsale,
			'projectpaid' 			=> $projectdelivery,
			'projectsalereturn' 	=> $projectsalereturn,
			'filter'			    => $filter,
			'branch'			    => $branch,
			'content'				=> 'admin.sales_report'
		];

		return view('admin.sales_report', $data);
	}
}
