<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportAccountPayable;
use App\Exports\ExportAccountReceivable;
use App\Exports\ExportApOther;
use PDF;
use App\Helper\SMB;
use App\Models\BudgetingProject;
use App\Models\Project;
use App\Models\Customer;
use App\Models\ProjectBill;
use App\Models\ProjectSale;
use App\Models\ProjectDelivery;
use App\Models\ProjectSaleReturn;
use App\Models\ProjectPurchase;
use App\Models\PurchaseRequest;
use App\Models\Coa;
use App\Models\Paper;
use App\Models\ProjectPay;
use App\Models\PurchaseRequestPayment;
use App\Models\Journal;
use App\Models\CashBank;
use App\Models\PurchaseCost;
use Illuminate\Http\Request;
use App\Models\CashBankDetail;
use App\Http\Controllers\Controller;
use App\Models\SamplePurchase;
use App\Models\ServiceCost;
use App\Models\ServiceCostPayment;
use App\Models\Supplier;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportAccountingController extends Controller {

    public function balanceSheet(Request $request) 
    {


		// $currentMonth = '2021-07-31';
		// $filter = "2024-02-13";
		
		// $currentDate = new DateTime($currentMonth);
		// $filterDate = new DateTime($filter);
		
		// while ($currentDate->format('Y-m-d') <= $filterDate->format('Y-m-d')) {
		// 	// Do something with $currentDate
			
		// 	// Move to the next month
		// 	$currentDate->modify('+1 month');
		// 	echo $currentDate->format('Y-m-d') . "\n";
		// }
		
		// dd($currentDate);


        $filter = $request->filter ? $request->filter : ($request->date ? $request->date : date('Y-m'));
		$date = $request->date ? $request->date : '';
		$branch = $request->branch ? $request->branch : '';
		$mode = $request->mode ? $request->mode : '1';
		
        $data   = [
            'title'         	=> 'Balance Sheet',
			'retained_earning' 	=> SMB::retained_earning($filter,$branch),
			'coa'				=> Coa::orderBy('code')->get(),
            'filter'        	=> $filter,
			'branch'        	=> $branch,
			'date'				=> $date,
			'mode'				=> $mode,
            'content'       	=> 'admin.report.accounting.balance_sheet'
        ];


        return view('admin.layouts.index', ['data' => $data]);
    }

    public function profitLossComparison(Request $request) 
    {
        $filter = $request->filter ? $request->filter : date('Y-m');
		$branch = $request->branch ? $request->branch : '';
		$month_last = date('m', strtotime('-1 months', strtotime($filter)));
		$year_last = date('Y', strtotime('-1 months', strtotime($filter)));
		$last = $year_last.'-'.$month_last;

        $data   = [
            'title'       => 'Profit & Loss',
			'coa'		  => Coa::orderBy('code')->get(),
            'filter'      => $filter,
			'last'		  => $last,
			'branch'	  => $branch,
            'content'     => 'admin.report.accounting.profit_loss_comparison'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function profitLoss(Request $request) 
    {
        $filter = $request->filter ? $request->filter : date('Y-m');
		$mode = $request->mode ? $request->mode : '1';
		$branch = $request->branch ? $request->branch : '';
		$filter_start = $request->filter_start ? $request->filter_start : '';
		$filter_end = $request->filter_end ? $request->filter_end : '';
		$date_start = $request->date_start ? $request->date_start : '';
		$date_end = $request->date_end ? $request->date_end : '';
		$year = $request->year ? $request->year : '';

        $data   = [
            'title'       		=> 'Profit & Loss',
			'coa'		  		=> Coa::orderBy('code')->get(),
            'filter'      		=> $filter,
			'mode'      		=> $mode,
			'filter_start'		=> $filter_start,
			'filter_end'		=> $filter_end,
			'date_start'		=> $date_start,
			'date_end'			=> $date_end,
			'year'				=> $year,
			'branch'	  		=> $branch,
            'content'     		=> 'admin.report.accounting.profit_loss'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function ledger(Request $request) 
    {
		$coa_id = $request->coa_id ? $request->coa_id : '';
		$start_date = $request->start ? $request->start : '';
		$end_date = $request->end ? $request->end : '';
		$branch = $request->branch ? $request->branch : '';
		
        $data = [
            'title'   	=> 'Ledger',
            'coa'     	=> Coa::all(),
			'coa_id'	=> $coa_id,
			'start_date'=> $start_date,
			'end_date'	=> $end_date,
			'branch'	=> $branch,
            'content' 	=> 'admin.report.accounting.ledger'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function ledgerDatatable(Request $request)
    {
        $column = [
            'detail',
            'id',
            'date',
            'name',
            'beginning',
            'debit',
            'credit',
            'ending'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Coa::where('status', 1)
            ->count();
        
        $query_data = Coa::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }     

                if($request->coa_id) {
                    $query->where('id', $request->coa_id);
                }
            })
            ->where('status', 1)
            ->offset($start)
            ->limit($length)
            ->orderBy('code', 'asc')
            ->get();

        $total_filtered = Coa::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }     

                if($request->coa_id) {
                    $query->where('id', $request->coa_id);
                }
            })
            ->where('status', 1)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {

                if($request->start_date && $request->finish_date) {
					$before = "DATE(date_transaction) < '$request->start_date'";
                    $periode = "DATE(date_transaction) >= '$request->start_date' AND DATE(date_transaction) <= '$request->finish_date'";
                } else if($request->start_date) {
					$before = "DATE(date_transaction) < '$request->start_date'";
                    $periode = "DATE(date_transaction) >= '$request->start_date' AND DATE(date_transaction) <= CURDATE()";
                } else if($request->finish_date) {
					$before = "DATE(date_transaction) < CURDATE()";
                    $periode = "DATE(date_transaction) >= CURDATE() AND DATE(date_transaction) <= '$request->finish_date'";
                } else {
					$before = "created_at IS NOT NULL";
                    $periode = "created_at IS NOT NULL";
                }

                $beginning_debit  = $val->journalDebit()->where('type','1')->where('branch',$request->branch)->whereRaw($before)->sum('nominal');
                $beginning_credit = $val->journalCredit()->where('type','2')->where('branch',$request->branch)->whereRaw($before)->sum('nominal');
                if(!$request->start_date && !$request->finish_date) {
                    $beginning_total = 0;
                } else {
                    $beginning_total = $beginning_debit - $beginning_credit;
                }

                $ending_debit  = $val->journalDebit()->where('type','1')->where('branch',$request->branch)->whereRaw($periode)->sum('nominal');
                $ending_credit = $val->journalCredit()->where('type','2')->where('branch',$request->branch)->whereRaw($periode)->sum('nominal');
                $ending_total  = $ending_debit - $ending_credit;

                $response['data'][] = [
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $nomor,
                    $val->name,
                    number_format($beginning_total, 2, ',', '.'),
                    number_format($ending_debit, 2, ',', '.'),
                    number_format($ending_credit, 2, ',', '.'),
                    number_format($beginning_total + $ending_total, 2, ',', '.')
                ];

                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function ledgerRowDetail(Request $request)
    {
		
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>C&B Code</th>
							<th width="25%">Coa</th>
							<th width="15%">Date</th>
							<th>Debit</th>
							<th>Kredit</th>
							<th>Ending</th>
							<th width="30%">Note</th>
						</tr>
					</thead>
					<tbody>';

		if(in_array(4, session('bo_role'))){
			$journal = Journal::where(function($query) use ($request) {
					$query->where('coa_id', $request->id);
			})
			->where(function($query) use ($request) {
				if($request->start_date && $request->finish_date) {
					$query->whereDate('date_transaction', '>=', $request->start_date)
						->whereDate('date_transaction', '<=', $request->finish_date);
				} else if($request->start_date) {
					$query->whereDate('date_transaction', '>=', $request->start_date)
						->whereDate('date_transaction', '<=', date('Y-m-d'));
				} else if($request->finish_date) {
					$query->whereDate('date_transaction', '>=', date('Y-m-d'))
						->whereDate('date_transaction', '<=', $request->finish_date);
				}
			})
			->where('branch',$request->branch)
			->oldest('date_transaction')
			//->oldest('updated_at')
			->get();
		}else{
			$journal = Journal::where(function($query) use ($request) {
					$query->where('coa_id', $request->id);
			})
			->where(function($query) use ($request) {
				if($request->start_date && $request->finish_date) {
					$query->whereDate('date_transaction', '>=', $request->start_date)
						->whereDate('date_transaction', '<=', $request->finish_date);
				} else if($request->start_date) {
					$query->whereDate('date_transaction', '>=', $request->start_date)
						->whereDate('date_transaction', '<=', date('Y-m-d'));
				} else if($request->finish_date) {
					$query->whereDate('date_transaction', '>=', date('Y-m-d'))
						->whereDate('date_transaction', '<=', $request->finish_date);
				}
			})
			->where('branch',$request->branch)
			//->oldest('date_transaction')
			->oldest('updated_at')
			->get();
		}
		
		$beginning_debit = 0;
		
        foreach($journal as $key => $d) {
			
			$customer_info = isset($d->journalable->lookable->project->customer->name) ? $d->journalable->lookable->project->customer->name : '';
			
			$project_info = isset($d->journalable->lookable->project->code) ? $d->journalable->lookable->project->code : '';
			
			$project_main = $d->journalable->lookable_type == 'projects' ? $d->journalable->lookable->code : '';
			
			if($d->journalable->lookable_type == 'project_main_payments'){
				$projectpay = ProjectPay::where('project_main_payment_id',$d->journalable->lookable_id)->get();
				foreach($projectpay as $rowpay){
					$project_main .= ' Project no. '.$rowpay->project->code.' SO no. '.$rowpay->projectSale->code.' DO no. '.($rowpay->projectDelivery()->exists() ? $rowpay->projectDelivery->code.', ' : ', ');
				}
				$customer_info = $d->journalable->lookable->customer->name;
			}elseif($d->journalable->lookable_type == 'project_warehouses' && isset($d->journalable->lookable->projectPurchase)){
				$project_main .= ' Supplier Name '.$d->journalable->lookable->projectPurchase->supplier->name.' ';
			}
			
			$additional_info = '';
			
			if($d->journalable->lookable_type == 'purchase_requests'){
				if($d->journalable->lookable){
					if($d->journalable->lookable->link_type == 'project_sales'){
						$data = ProjectSale::find($d->journalable->lookable->link_id);
						$additional_info = 'PJ No. '.$data->project->code.' SO No. '.$data->code;
					}
				}else{
					$additional_info = '<span class="badge badge-danger">Reference not found</span>';
				}
			}
			
			if($d->journalable->lookable_type == 'purchase_request_main_payments'){
				$arrCb = CashBankDetail::where('cash_bank_id',$d->journalable_id)->orderBy('id')->get();
				$arrJou = Journal::where('journalable_id',$d->journalable_id)->orderBy('id')->get();
				$index = -1;
				foreach($arrJou as $key => $row){
					if($row->id == $d->id){
						$index = $key;
					}
				}
				
				if($index >= 0){
					$additional_info .= ' '.$arrCb[$index]->note;
				}
			}
			
			if($key == 0){
				if($request->start_date && $request->finish_date) {
					$before = "DATE(date_transaction) < '$request->start_date'";
				} else if($request->start_date) {
					$before = "DATE(date_transaction) < '$request->start_date'";
				} else if($request->finish_date) {
					$before = "DATE(date_transaction) < CURDATE()";
				} else {
					$before = "created_at IS NOT NULL";
				}
				
				$beginning_debit  = $d->coa->journalDebit()->where('type','1')->where('branch',$request->branch)->whereRaw($before)->sum('nominal');
				$beginning_credit = $d->coa->journalCredit()->where('type','2')->where('branch',$request->branch)->whereRaw($before)->sum('nominal');
				
				if(!$request->start_date && !$request->finish_date) {
					$beginning_total = 0;
				} else {
					$beginning_total = $beginning_debit - $beginning_credit;
				}
			}
			
			$cbcode = $d->journalable ? $d->journalable->code : '';
			
			$style = '';
			
			if($d->journalable->abnormal()){
				$style = 'style="background-color:red !important;color:white;"';
			}
			
			$koderef = '';
			
			if($d->journalable->lookable_type == 'purchase_request_payments'){
				if($d->journalable->lookable->purchaseRequest->link_type == 'project_sales'){
					$datasale = ProjectSale::find($d->journalable->lookable->purchaseRequest->link_id);
					$koderef = $datasale ? $datasale->project->code.' - '.$datasale->project->customer->name.' - '.$datasale->code : '';
				}else{
					$koderef = 'PR-'.$d->journalable->lookable->purchase_request_id;
				}
			}
			
            if($d->type == '1'){
				$beginning_total += $d->nominal;
				$string .= '
					<tr '.$style.'>
						<td><a href="'.url('admin/finance/cash_bank?mode_edit=true&id='.$d->journalable->id).'">'.$cbcode.'</a></td>
						<td>'.$d->coa->name.'</td>
						<td>'.date('d M Y',strtotime($d->date_transaction)).'</td>
						<td class="text-center">'.number_format($d->nominal,2,',','.').'</td>
						<td class="text-center">-</td>
						<td class="text-center">'.number_format($beginning_total,2,',','.').'</td>
						<td>'.$koderef.'-'.$project_info.' - '.$customer_info.' - '.$additional_info.' - '.$d->journalable->description.' - '.$project_main.'</td>
					</tr>
				';
			}elseif($d->type == '2'){
				$beginning_total -= $d->nominal;
				$string .= '
					<tr '.$style.'>
						<td><a href="'.url('admin/finance/cash_bank?mode_edit=true&id='.$d->journalable->id).'">'.$cbcode.'</a></td>
						<td>'.$d->coa->name.'</td>
						<td>'.date('d M Y',strtotime($d->date_transaction)).'</td>
						<td class="text-center">-</td>
						<td class="text-center">'.number_format($d->nominal,2,',','.').'</td>
						<td class="text-center">'.number_format($beginning_total,2,',','.').'</td>
						<td>'.$koderef.'-'.$project_info.' - '.$customer_info.' - '.$additional_info.' - '.$d->journalable->description.' - '.$project_main.'</td>
					</tr>
				';
			}
        }

        $string .= '</tbody></table>';
        
		return response()->json($string);

    }

    public function trialBalance() 
    {
        $data = [
            'title'   => 'Trial Balance',
            'coa'     => Coa::all(),
            'content' => 'admin.report.accounting.trial_balance'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function trialBalanceDatatable(Request $request)
    {
        $column = [
            'id',
            'coa_id',
            'balance_debit',
            'balance_credit',
            'change_debit',
            'change_credit',
            'end_balance_debit',
            'end_balance_credit'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Coa::where('status', 1)
            ->count();
        
        $query_data = Coa::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }     
            })
            ->where('status', 1)
            ->offset($start)
            ->limit($length)
            ->orderBy('code', 'asc')
            ->get();

        $total_filtered = Coa::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }     
            })
            ->where('status', 1)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $balance_debit = $val->journalDebit()->where('type','1')
					->where('branch',$request->branch)
                    ->whereYear('created_at', date('Y', strtotime($request->date)))
                    ->whereMonth('created_at', '<', date('m', strtotime($request->date)))
                    ->sum('nominal');

                $balance_credit = $val->journalCredit()->where('type','2')
					->where('branch',$request->branch)
                    ->whereYear('created_at', date('Y', strtotime($request->date)))
                    ->whereMonth('created_at', '<', date('m', strtotime($request->date)))
                    ->sum('nominal');

                $change_debit = $val->journalDebit()->where('type','1')
					->where('branch',$request->branch)
                    ->whereYear('created_at', date('Y', strtotime($request->date)))
                    ->whereMonth('created_at', date('m', strtotime($request->date)))
                    ->sum('nominal');

                $change_credit = $val->journalCredit()->where('type','2')
					->where('branch',$request->branch)
                    ->whereYear('created_at', date('Y', strtotime($request->date)))
                    ->whereMonth('created_at', date('m', strtotime($request->date)))
                    ->sum('nominal');


                $explode_code = explode('.', $val->code);
                if($explode_code[0] == 1 || $explode_code[0] == 5 || $explode_code[0] == 6 || $explode_code[0] == 7) {
                    $end_balance_debit  = $balance_debit + $change_debit - $change_credit;
                    $end_balance_credit = 0;
                } else {
                    $end_balance_debit  = 0;
                    $end_balance_credit = $balance_credit + $change_credit - $change_debit;
                }

                $response['data'][] = [
                    $nomor,
                    $val->name,
                    number_format($balance_debit, 2, ',', '.'),
                    number_format($balance_credit, 2, ',', '.'),
                    number_format($change_debit, 2, ',', '.'),
                    number_format($change_credit, 2, ',', '.'),
                    number_format($end_balance_debit, 2, ',', '.'),
                    number_format($end_balance_credit, 2, ',', '.')
                ];

                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }
    
	public function cashBank(Request $request)
    {
        $result   = [];
        $month    = $request->filter_month ? $request->filter_month : date('Y-m');
        $coa_id   = $request->filter_coa_id ? $request->filter_coa_id : '';
		$branch   = $request->filter_branch ? $request->filter_branch : '1';
        $code     = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 215,333,335];
        $list_coa = Coa::where(function($query) use ($coa_id, $code, $request) {
                if($coa_id) {
                    $query->where('id', $coa_id);
                } else {
                    $query->whereIn('id', $code);
                }
            })
            ->get();
			
		$where_raw = "LEFT(date_transaction, 7) <= '$month'";

        foreach($list_coa as $lc) {
            $balance_debit  = Journal::where('type','1')->where('coa_id', $lc->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
			
            $balance_credit = Journal::where('type','2')->where('coa_id', $lc->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');

            $result[] = [
                'id'      => $lc->id,
                'no'      => $lc->code,
                'name'    => $lc->name,
				'branch'  => $lc->branch,
                'debit'   => $balance_debit,
                'credit'  => $balance_credit,
                'balance' => $balance_debit - $balance_credit,
            ];
        }

        $data = [
            'title'   => 'Cash & Bank',
            'month'   => $month,
            'coa_id'  => $coa_id,
            'coa'     => Coa::all(),
			'branch'  => $branch,
            'result'  => $result,
            'content' => 'admin.report.accounting.cash_bank'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function cashBankDetail(Request $request)
    {
        $result  = [];
        $coa     = Coa::find($request->id);
        $journal = Journal::where(function($query) use ($request) {
                $query->whereYear('created_at', date('Y', strtotime($request->month)))
                    ->whereMonth('created_at', '<=', date('m', strtotime($request->month)));
            })
            ->where(function($query) use ($request) {
                $query->where('coa_id', $request->id);
            })
            ->groupBy('id')
            ->get();

        foreach($journal as $j) {
            foreach($j->journalable->cashBankDetail()->where('coa_id',$request->id)->get() as $cbd) {
                $income = CashBankDetail::where(function($query) use ($request, $cbd) {
                        $query->whereDate('created_at', '<=', $cbd->created_at->format('Y-m-d'));
                    })
                    ->where('coa_id', $request->id)->where('type','1')
                    ->sum('nominal');

                $expense = CashBankDetail::where(function($query) use ($request, $cbd) {
                        $query->whereDate('created_at', '<=', $cbd->created_at->format('Y-m-d'));
                    })
                    ->where('coa_id', $request->id)->where('type','2')
                    ->sum('nominal');

                if($cbd->type == '1') {
                    $debit  = $cbd->nominal;
                    $credit = 0;
                } else {
                    $debit  = 0;
                    $credit = $cbd->nominal;
                }

                $result[] = [
                    'date'        => $cbd->created_at->format('d F Y'),
                    'code'        => $j->journalable->code,
                    'description' => $cbd->note,
                    'income'      => number_format($debit, 2, ',', '.'),
                    'expense'     => number_format($credit, 2, ',', '.'),
                    'balance'     => number_format($income - $expense, 2, ',', '.')
                ];
            }
        }

        $checking_account = Paper::where('coa_id', $request->id)
            ->whereYear('created_at', date('Y', strtotime($request->month)))
            ->whereMonth('created_at', date('m', strtotime($request->month)))
            ->first();

        if($checking_account) {
            $image = asset(Storage::url($checking_account->image));
        } else {
            $image = null;
        }

        return response()->json([
            'year'              => date('Y', strtotime($request->month)),
            'month'             => date('F', strtotime($request->month)),
            'name'              => $coa->name,
            'code'              => $coa->code,
            'image'             => $image,
            'total_transaction' => $journal->count(),
            'balance'           => number_format($request->balance, 2, ',', '.'),
            'result'            => $result
        ]);
    }

    public function cashBankUploadFile(Request $request)
    {
        $checking_account = Paper::where('coa_id', $request->coa_id)
            ->whereYear('created_at', date('Y', strtotime($request->month)))
            ->whereMonth('created_at', date('m', strtotime($request->month)))
            ->first();

        if($checking_account) {
            if(Storage::exists($checking_account->image)) {
                Storage::delete($checking_account->image);
            }

            $query = Paper::where('coa_id', $request->coa_id)
                ->whereYear('created_at', date('Y', strtotime($request->month)))
                ->whereMonth('created_at', '<=', date('m', strtotime($request->month)))
                ->update([
                    'image' => $request->file('image')->store('public/paper')
                ]);
        } else {
            $query = Paper::create([
                'coa_id' => $request->coa_id,
                'image'  => $request->file('image')->store('public/paper')
            ]);
        }

        if($query) {
            $response = [
                'status'  => 200,
                'message' => 'Bank statement uploaded successfully.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Bank statement failed to upload.'
            ];
        }

        return response()->json($response);
    }
	
	public function outstandingAR(Request $request) 
    {
		$mode = $request->mode ? $request->mode : '1';
		$filter = $mode == '1' ? ($request->filter ? $request->filter : date('Y-m')) : '';
		$branch = $request->branch ? $request->branch : 1;
		$filter_date = $mode == '2' ? ($request->filter_date ? $request->filter_date : date('Y-m-d')) : '';
		
		$sale = [];
		$bill = [];
		$service_cost = [];
		
		if($mode == '1'){
		
			$projectsale = ProjectSale::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('projectDelivery', function($query) use ($filter) {
				$query->whereRaw("LEFT(received_date, 7) <= '$filter'");
			})->get();
			
			foreach($projectsale as $key => $row){
				$totaldelivered = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totalcb = 0;
				$totalbill = 0;
				
				foreach($row->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->whereRaw("LEFT(DATE(received_date), 7) <= '$filter'")->get() as $rd){
					if($rd->journal() || (count($rd->project->projectBill()->get()) > 0)){
						$totaldelivered += round($rd->grandtotal_product + $rd->grandtotal_service);
					}else{
						$totaldelivered += 0;
					}
				}
				
				foreach($row->projectSaleReturn()->whereRaw("LEFT(DATE(date_return), 7) <= '$filter'")->get() as $rsr){
					$totalreturn += round($rsr->grandtotal);
				}
				
				foreach($row->projectSalePay()->whereRaw("LEFT(DATE(date), 7) <= '$filter' AND ((project_bill_id IS NOT NULL AND project_delivery_id IS NOT NULL) OR (project_bill_id IS NULL OR project_bill_id = 0))")->get() as $rsp){
					if(isset($rsp->projectBill) && $rsp->projectBill->journal()){
						if(($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal == 0){
							$totalpay -=  ($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal;
						}else{
							// kalau bill nominalnya lebih besar maka tidak dikurangi
							if(($rsp->projectBill->nominal + $rsp->projectBill->nominal_service ) <= $row->projectSalePay()->sum('nominal')){
								$totalpay += $rsp->nominal;
							}
						}
					}else{
						$totalpay += $rsp->nominal;
					}
				}
				
				foreach($row->project->projectBill()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $pb){
					if($pb->journal()){
						$totalbill += $pb->nominal + $pb->nominal_service;
					}
				}
				
				$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->project->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
							if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}

						foreach($rowcb->cashBankDetail()->where('coa_id',67)->get() as $cbcb){
							if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}
					}
				}
				
				if(round(($totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill)) > 0){
					$row['totalbalance'] = $totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill;
					$sale[] = $row;
				}
			}
			
			$projectbill = ProjectBill::where('branch',$branch)
			->whereRaw("LEFT(date, 7) <= '$filter'")
			->get();
			
			foreach($projectbill as $key => $row){
				$already_bill = false;
				$already_sent = false;
				
				$cek = CashBank::where('lookable_type','project_bills')->where('lookable_id',$row->id)->whereRaw("LEFT(date, 7) <= '$filter'")->first();
				
				if(!$cek){
					$already_bill = true;
				}
				
				if($already_bill == false && $row->balancePeriod($filter) > 0){
					$bill[] = $row;
				}
			}

			$serviceCharge = ServiceCost::where('branch', $branch)
			->whereRaw("LEFT(DATE(created_at), 7) <= '$filter'")
			->get()
			->filter(function ($servicecharge) use ($filter){
				$payment_service_charge = $servicecharge->serviceCostPayment()
				->whereRaw("LEFT(DATE(date_paid), 7) <= '$filter'")
				->sum('nominal');
				$balance = $servicecharge->grandtotal_service - $payment_service_charge;

				if ($balance > 0) {
					$servicecharge['totalbalance'] = $balance;
					return true;
				}

				return false;
			});

			
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
					$query->whereNotNull('customer_id')
					->where('customer_id','<>','0')
					->whereRaw("LEFT(date, 7) <= '$filter'");
				})
				->where('coa_id',27)
				->where('branch',$branch)
				->where('type','1')
				->orderBy('created_at','asc')
				->get();
				
		}elseif($mode == '2'){
			
			$projectsale = ProjectSale::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('projectDelivery', function($query) use ($filter_date) {
				$query->whereRaw("received_date <= '$filter_date'");
			})->get();
			
			foreach($projectsale as $key => $row){
				$totaldelivered = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totalcb = 0;
				$totalbill = 0;
				
				foreach($row->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->whereRaw("DATE(received_date) <= '$filter_date'")->get() as $rd){
					if($rd->journal() || (count($rd->project->projectBill()->get()) > 0)){
						$totaldelivered += round($rd->grandtotal_product + $rd->grandtotal_service);
					}else{
						$totaldelivered += 0;
					}
				}
				
				foreach($row->projectSaleReturn()->whereRaw("DATE(date_return) <= '$filter_date'")->get() as $rsr){
					$totalreturn += round($rsr->grandtotal);
				}
				
				foreach($row->projectSalePay()->whereRaw("DATE(date) <= '$filter_date'  AND ((project_bill_id IS NOT NULL AND project_delivery_id IS NOT NULL) OR (project_bill_id IS NULL OR project_bill_id = 0))")->get() as $rsp){
					if(isset($rsp->projectBill) && $rsp->projectBill->journal()){
						if(($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal == 0){
							$totalpay -=  ($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal;
						}else{
							// kalau bill nominalnya lebih besar maka tidak dikurangi
							if(($rsp->projectBill->nominal + $rsp->projectBill->nominal_service ) <= $row->projectSalePay()->sum('nominal')){
								$totalpay += $rsp->nominal;
							}
						}
					}else{
						$totalpay += $rsp->nominal;
					}

					
				}
				
				foreach($row->project->projectBill()->whereRaw("DATE(date) <= '$filter_date'")->get() as $pb){
					if($pb->journal()){
						$totalbill += $pb->nominal + $pb->nominal_service;
					}
				}
				
				$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->project->id)->whereRaw("DATE(date) <= '$filter_date'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
							if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}
					}
				}
				
				
				if(round(($totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill)) > 0){
					$row['totalbalance'] = $totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill;
					$sale[] = $row;
				}
				
			}
			
			$projectbill = ProjectBill::where('branch',$branch)
			->whereRaw("date <= '$filter_date'")
			->get();
			
			foreach($projectbill as $key => $row){
				$already_bill = false;
				$already_sent = false;
				
				$cek = CashBank::where('lookable_type','project_bills')->where('lookable_id',$row->id)->whereRaw("date <= '$filter_date'")->first();
				
				if(!$cek){
					$already_bill = true;
				}
				
				if($already_bill == false && $row->balancePeriod($filter_date) > 0){
					$bill[] = $row;
				}
			}

			$serviceCharge = ServiceCost::where('branch', $branch)
			->whereRaw("DATE(created_at) <= '$filter_date'")
			->get()
			->filter(function ($servicecharge) use ($filter_date){
				$payment_service_charge = $servicecharge->serviceCostPayment()
				->whereRaw("DATE(date_paid) <= ?", [$filter_date])
				->sum('nominal');
				$balance = $servicecharge->grandtotal_service - $payment_service_charge;

				if ($balance > 0) {
					$servicecharge['totalbalance'] = $balance;
					return true;
				}

				return false;
			});

	
			// dd($serviceCharge);
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter_date){
					$query->whereNotNull('customer_id')
					->where('customer_id','<>','0')
					->whereRaw("date <= '$filter_date'");
				})
				->where('coa_id',27)
				->where('branch',$branch)
				->where('type','1')
				->orderBy('created_at','asc')
				->get();
				
		}

		// $yolo = 0;
		// foreach($bill as $val) {
		// 	$yolo += $val->nominal;
		// 	echo '<pre>' . var_export($val->nominal) . '</pre>';
		// }
		// dd($yolo);
        $data = [
            'title'   			=> 'Outstanding A/R',
			'filter'  			=> $filter,
			'branch'			=> $branch,
			'projectsale' 		=> $sale,
			'projectbill'		=> $bill,
			'servicecharges'	=> $serviceCharge,
			'other'				=> $other,
			'mode'				=> $mode,
			'filter_date'		=> $filter_date,
            'content' 			=> 'admin.report.finance.outstanding_a_r'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

	// public function outstandingAP(Request $request) 
    // {
	// 	$mode = $request->mode ? $request->mode : '1';
	// 	$filter = $mode == '1' ? ($request->filter ? $request->filter : date('Y-m')) : '';
	// 	$branch = $request->branch ? $request->branch : '1';
	// 	$filter_date = $mode == '2' ? ($request->filter_date ? $request->filter_date : date('Y-m-d')) : '';
		
	// 	$arrPurchase = [];
	
		
	// 	if($mode == '1'){
	// 		$projectpurchase = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
	// 			$query->where('branch',$branch);
	// 		})->whereHas('projectWarehouse', function($query) use ($filter) {
	// 			$query->whereRaw("LEFT(DATE(date_receive), 7) <= '$filter'");
	// 		})->whereDoesntHave('purchaseCost')
	// 		->orderBy('created_at','asc')
	// 		->get();
			
	// 		foreach($projectpurchase as $row){
	// 			$totalreceived = 0;
	// 			$totalreturn = 0;
	// 			$totalpay = 0;
	// 			$totaltransfer = 0;
	// 			$totalcb = 0;
	// 			$totalpc = 0;
				
	// 			foreach($row->projectWarehouse()->whereRaw("LEFT(DATE(date_receive),7) <= '$filter'")->get() as $pw){
	// 				$totalreceived += $pw->grandtotal;
	// 			}
				
	// 			foreach($row->projectPurchaseReturn()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsr){
	// 				if(!$rsr->journalDownPayment()){
	// 					$totalreturn += round($rsr->getTotal());
	// 				}
	// 				// kalo retur sudah jadi DP (payable sudah dibayar/ tagihan supplier sudah lunas)tidak dikurangi 
	// 			}
				
	// 			foreach($row->projectPurchasePayment()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsp){
	// 				$totalpay += $rsp->nominal;
	// 			}
				
	// 			$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$row->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
	// 			if(count($cb) > 0){
	// 				foreach($cb as $rowcb){
	// 					foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
	// 						if($cbcb->type == '1'){
	// 							$totalcb += $cbcb->nominal;
	// 						}
	// 					}
	// 				}
	// 			}
				
	// 			$pc = PurchaseCost::where('project_purchase_id',$row->id)->first();
				
	// 			if($pc){
	// 				$totalpc += $pc->totalCost();
	// 			}
				
	// 			if(round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc)) > 0){
	// 				$row['totalbalance'] = round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc));
	// 				$arrPurchase[] = $row;
	// 			}
	// 		}

		
	// 		$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
	// 			$query
	// 			->whereRaw("LEFT(date, 7) <= '$filter'")
	// 			->whereNotNull('supplier_id')
	// 			->where('supplier_id','<>','0')
	// 			->orWhere('code', 'like', '%BPC-%');
	// 		})
	// 		->where('coa_id',332)
	// 		->where('type','2')
	// 		->where('branch',$branch)
	// 		->orderBy('created_at','asc')
	// 		->get();
			

	// 		$arrOther = [];
			
	// 		foreach($other as $row){
				
	// 				$total = $row->nominal;
					
	// 				$cekdata = $this->checkData($arrOther,$row->cash_bank_id);
					
	// 				if($cekdata >= 0){
	// 					$total += $arrOther[$cekdata]['nominal'];
						
	// 					if(!str_contains($row->cashBank->code, 'BPC-')){
	// 						foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rowpay){
	// 							if(count(explode('-',$rowpay->code)) > 3){
	// 								foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter){ $query->where('code',$row->cashBank->code)->whereRaw("LEFT(date, 7) <= '$filter'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
	// 									if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
	// 										foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
	// 											$total -= $rowdetail->nominal;
	// 										}
	// 									}
	// 								}
	// 							}
	// 						}
	// 					}
						
	// 					if($total > 0){
	// 						$arrOther[$cekdata]['nominal'] = $total;
	// 					}
	// 				}else{
	// 					if(!str_contains($row->cashBank->code, 'BPC-')){
	// 						foreach(PurchaseRequest::where('link_type','fee_pta')->where('link_id',$row->cashBank->id)->get() as $rowpay){
	// 							$total -= $rowpay->totalPaymentPeriod($filter);
	// 						}
							
	// 						foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rowpay){
	// 							if(count(explode('-',$rowpay->code)) > 3){
	// 								foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter){ $query->where('code',$row->cashBank->code)->whereRaw("LEFT(date, 7) <= '$filter'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
	// 									if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
	// 										foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
	// 											$total -= $rowdetail->nominal;
	// 										}
	// 									}
	// 								}
	// 							}
	// 						}
	// 						// cek by desc no DO
	// 						if($row->cashBank->lookable_type == 'projects'){
	// 							foreach(Project::find($row->cashBank->lookable_id)->projectSaleReturn as $rowreturn){
	// 								if($rowreturn->compareProductReturnAndDelivery($row->cashBank->description)){
	// 									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter_date,$rowreturn){ 
	// 										$query->where('lookable_type','project_sale_returns')->where('lookable_id',$rowreturn->id)->whereRaw("LEFT(date, 7) <= '$filter'"); 
	// 									})->where('coa_id',332)->where('type','1')->where('branch',$branch)->get() as $rowcek){
	// 										$total -= $rowcek->nominal;
	// 									}
	// 								}
	// 							}
	// 						}
	// 					}
						
	// 					if($total > 0){
	// 						$row['nominal'] = $total;
	// 						$arrOther[] = $row;
	// 					}
	// 				}
	// 		}


	// 		$samplepurchase = SamplePurchase::whereHas('sales', function($query) use ($branch) {
	// 			$query->where('branch',$branch);
	// 		})->whereHas('sampleWarehouse', function($query) use ($filter) {
	// 			$query->whereRaw("LEFT(DATE(date_receive), 7) <= '$filter'");
	// 		})
	// 		->orderBy('created_at','asc')
	// 		->get();

	// 		$arrPurchaseSample = [];
			
	// 		foreach($samplepurchase as $row){
	// 			$totalreceivedsample = 0;
	// 			$totalreturnsample = 0;
	// 			$totalpaysample = 0;
	// 			$totalcbsample = 0;
				
	// 			foreach($row->sampleWarehouse()->whereRaw("LEFT(DATE(date_receive),7) <= '$filter'")->get() as $pw){
	// 				$totalreceivedsample += $pw->grandtotal;
	// 			}
				
	// 			foreach($row->samplePurchaseReturn()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsr){
	// 					$totalreturnsample += round($rsr->getTotal());
	// 			}
				
				
	// 			$cb = CashBank::where('lookable_type','sample_purchases')->where('lookable_id',$row->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
	// 			if(count($cb) > 0){
	// 				foreach($cb as $rowcb){
	// 					foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
	// 						if($cbcb->type == '1'){
	// 							$totalcbsample += $cbcb->nominal;
	// 						}
	// 					}
	// 				}
	// 			}
				
	// 			if(round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample)) > 0){
	// 				$row['totalbalance'] = round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample));
	// 				$arrPurchaseSample[] = $row;
	// 			}
	// 		}
	// 	}elseif($mode == '2'){
			
	// 		$projectpurchase = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
	// 			$query->where('branch',$branch);
	// 		})->whereHas('projectWarehouse', function($query) use ($filter_date) {
	// 			$query->whereRaw("DATE(date_receive) <= '$filter_date'");
	// 		})->whereDoesntHave('purchaseCost')
	// 		->orderBy('created_at','asc')
	// 		->get();
			
	// 		foreach($projectpurchase as $row){
	// 			$totalreceived = 0;
	// 			$totalreturn = 0;
	// 			$totalpay = 0;
	// 			$totaltransfer = 0;
	// 			$totalcb = 0;
				
	// 			foreach($row->projectWarehouse()->whereRaw("DATE(date_receive) <= '$filter_date'")->get() as $pw){
	// 				$totalreceived += $pw->grandtotal;
	// 			}
				
	// 			foreach($row->projectPurchaseReturn()->whereRaw("DATE(date) <= '$filter_date'")->get() as $rsr){
	// 				if(!$rsr->journalDownPayment()){
	// 					$totalreturn += round($rsr->getTotal());
	// 				}
	// 			}
				
	// 			foreach($row->projectPurchasePayment()->whereRaw("DATE(date) <= '$filter_date'")->get() as $rsp){
	// 				$totalpay += $rsp->nominal;
	// 			}
				
	// 			$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$row->id)->whereRaw("DATE(date) <= '$filter_date'")->get();
				
	// 			if(count($cb) > 0){
	// 				foreach($cb as $rowcb){
	// 					foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
	// 						if($cbcb->type == '1'){
	// 							$totalcb += $cbcb->nominal;
	// 						}
	// 					}
	// 				}
	// 			}
				
	// 			if(round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb)) > 0){
	// 				$row['totalbalance'] = round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb));
	// 				$arrPurchase[] = $row;
	// 			}
	// 		}
			
	// 		$other = CashBankDetail::whereHas('cashBank', function($query) use($filter_date){
	// 				$query
	// 				->whereRaw("date <= '$filter_date'")
	// 				->whereNotNull('supplier_id')
	// 				->where('supplier_id','<>','0')
	// 				->orWhere('code', 'like', '%BPC-%');
	// 			})
	// 			->where('coa_id',332)
	// 			->where('type','2')
	// 			->where('branch',$branch)
	// 			->orderBy('created_at','asc')
	// 			->get();
				
	// 		$arrOther = [];
			
	// 		foreach($other as $row){
				
	// 				$total = $row->nominal;
					
	// 				$cekdata = $this->checkData($arrOther,$row->cash_bank_id);
					
	// 				if($cekdata >= 0){
	// 					if(!str_contains($row->cashBank->code, 'BPC-')){
	// 						$total += $arrOther[$cekdata]['nominal'];

	// 						foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("DATE(date) <= '$filter_date'")->get() as $rowpay){
	// 							if(count(explode('-',$rowpay->code)) > 3){
	// 								foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter_date){ $query->where('code',$row->cashBank->code)->whereRaw("date <= '$filter_date'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
	// 									if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
	// 										foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
	// 											$total -= $rowdetail->nominal;
	// 										}
	// 									}
	// 								}
	// 							}
	// 						}		

	// 					}

	// 					if($total > 0){
	// 						$arrOther[$cekdata]['nominal'] = $total;
	// 					}
	// 				}else{

	// 					if(!str_contains($row->cashBank->code, 'BPC-')){
	// 						foreach(PurchaseRequest::where('link_type','fee_pta')->where('link_id',$row->cashBank->id)->get() as $rowpay){
	// 							$total -= $rowpay->totalPaymentPeriod($filter_date);
	// 						}
							
	// 						foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("DATE(date) <= '$filter_date'")->get() as $rowpay){
	// 							if(count(explode('-',$rowpay->code)) > 3){
	// 								foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter_date){ $query->where('code',$row->cashBank->code)->whereRaw("date <= '$filter_date'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
	// 									if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
	// 										foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
	// 											$total -= $rowdetail->nominal;
	// 										}
	// 									}
	// 								}
	// 							}
	// 						}
							
	// 					    // cek by desc no DO
	// 						if($row->cashBank->lookable_type == 'projects'){
	// 							foreach(Project::find($row->cashBank->lookable_id)->projectSaleReturn as $rowreturn){
	// 								if($rowreturn->compareProductReturnAndDelivery($row->cashBank->description)){
	// 									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter_date,$rowreturn){ 
	// 										$query->where('lookable_type','project_sale_returns')->where('lookable_id',$rowreturn->id)->whereRaw("date <= '$filter_date'"); 
	// 									})->where('coa_id',332)->where('type','1')->where('branch',$branch)->get() as $rowcek){
	// 										$total -= $rowcek->nominal;
	// 									}
	// 								}
	// 							}
	// 						}
	// 					}
						
						
	// 					if($total > 0){
	// 						$row['nominal'] = $total;
	// 						$arrOther[] = $row;
	// 					}
	// 				}
	// 		}

		

	// 		$samplepurchase = SamplePurchase::whereHas('sales', function($query) use ($branch) {
	// 			$query->where('branch',$branch);
	// 		})->whereHas('sampleWarehouse', function($query) use ($filter_date) {
	// 			$query->whereRaw("DATE(date_receive) <= '$filter_date'");
	// 		})
	// 		->orderBy('created_at','asc')
	// 		->get();

	// 		$arrPurchaseSample = [];
			
	// 		foreach($samplepurchase as $row){
	// 			$totalreceivedsample = 0;
	// 			$totalreturnsample = 0;
	// 			$totalpaysample = 0;
	// 			$totalcbsample = 0;
				
	// 			foreach($row->sampleWarehouse()->whereRaw("DATE(date_receive) <= '$filter_date'")->get() as $pw){
	// 				$totalreceivedsample += $pw->grandtotal;
	// 			}
				
	// 			foreach($row->samplePurchaseReturn()->whereRaw("DATE(date) <= '$filter_date'")->get() as $rsr){
	// 				$totalreturnsample += round($rsr->getTotal());
	// 			}
				
				
	// 			$cb = CashBank::where('lookable_type','sample_purchases')->where('lookable_id',$row->id)->whereRaw("DATE(date) <= '$filter_date'")->get();
				
	// 			if(count($cb) > 0){
	// 				foreach($cb as $rowcb){
	// 					foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
	// 						if($cbcb->type == '1'){
	// 							$totalcbsample += $cbcb->nominal;
	// 						}
	// 					}
	// 				}
	// 			}
				
	// 			if(round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample)) > 0){
	// 				$row['totalbalance'] = round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample));
	// 				$arrPurchaseSample[] = $row;
	// 			}
	// 		}
	// 	}
		

    //     $data = [
    //         'title'   			=> 'Outstanding A/P',
	// 		'filter'  			=> $filter,
	// 		'branch'			=> $branch,
	// 		'projectpurchase' 	=> $arrPurchase,
	// 		'samplepurchase' 	=> $arrPurchaseSample,
	// 		'other'				=> $arrOther,
	// 		'mode'				=> $mode,
	// 		'filter_date'		=> $filter_date,
    //         'content' 			=> 'admin.report.finance.outstanding_a_p'
    //     ];

    //     return view('admin.layouts.index', ['data' => $data]);
    // }
	
	public function outstandingAP(Request $request) 
    {
		// $other = CashBankDetail::whereHas('cashBank', function($query) {
		// 	$query->whereNotNull('supplier_id')
		// 	->where('supplier_id','<>','0')
		// 	->where("date",'<=',date('Y-m-d'));
		// })
		// ->where('coa_id',332)
		// ->where('type','2')
		// ->where('branch','2')
		// ->orderBy('created_at','asc')
		// ->get();

		// $breakthecode = '';
		// foreach ($other as $row) {
			

		// 	foreach(CashBank::where('code','like',$row->cashBank->code.'%')->where("date",'<=',date('Y-m-d'))->get() as $rowpay){
		// 		if(count(explode('-',$rowpay->code)) > 3){
		// 			foreach(CashBankDetail::whereHas('cashBank',function($query) use($row){ $query->where('code',$row->cashBank->code)->where("date",'<=',date('Y-m-d')); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
		// 				if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
		// 					foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
		// 						$breakthecode = $rowpay->code;
		// 						echo '<pre>' . var_export($rowpay->code, true) . '</pre>';
		// 					}
		// 				}
		// 			}
		// 		}

		// 	}
			
		// }
		// dd($breakthecode);

		// $breakthecode = CashBank::where('code','like', 'PR-2249-RJCT-2%')->get();
		// $breakthecode2 = CashBank::where('code','like', 'PR-2242229-RJCT%')->where('code','not like', '%CLOSE%')->get();
		// // $code = explode('-', $breakthecode2->code);
		// $breakthecode = explode("-","PR-2249-RJCT-2");
		// $length_of_last_code = strlen($breakthecode[3]);
		// dd(substr("PR-2249-RJCT-2", -$length_of_last_code));
		// dd(str_contains($breakthecode->code,'RJCT'));

		$mode = $request->mode ? $request->mode : '1';
		$filter = $mode == '1' ? ($request->filter ? $request->filter : date('Y-m')) : '';
		$branch = $request->branch ? $request->branch : '1';
		$filter_date = $mode == '2' ? ($request->filter_date ? $request->filter_date : date('Y-m-d')) : '';
		
		$arrPurchase = [];
	
		
		if($mode == '1'){
			$projectpurchase = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('projectWarehouse', function($query) use ($filter) {
				$query->whereRaw("LEFT(DATE(date_receive), 7) <= '$filter'");
			})->whereDoesntHave('purchaseCost')
			->orderBy('created_at','asc')
			->get();
			
			foreach($projectpurchase as $row){
				$totalreceived = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totaltransfer = 0;
				$totalcb = 0;
				$totalpc = 0;
				
				foreach($row->projectWarehouse()->whereRaw("LEFT(DATE(date_receive),7) <= '$filter'")->get() as $pw){
					$totalreceived += $pw->grandtotal;
				}
				
				foreach($row->projectPurchaseReturn()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsr){
					if(!$rsr->journalDownPayment()){
						$totalreturn += round($rsr->getTotal());
					}
					// kalo retur sudah jadi DP (payable sudah dibayar/ tagihan supplier sudah lunas)tidak dikurangi 
				}
				
				foreach($row->projectPurchasePayment()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsp){
					$totalpay += $rsp->nominal;
				}
				
				$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$row->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
							if($cbcb->type == '1'){
								$totalcb += $cbcb->nominal;
							}
						}
					}
				}
				
				// $pc = PurchaseCost::where('project_purchase_id',$row->id)->first();
				
				// if($pc){
				// 	$totalpc += $pc->totalCost();
				// }
				
				if(round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc)) > 0){
					$row['totalbalance'] = round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc));
					$arrPurchase[] = $row;
				}
			}

		
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
				$query
				->whereRaw("LEFT(date, 7) <= '$filter'")
				->whereNotNull('supplier_id')
				->where('supplier_id','<>','0')
				->orWhere('code', 'like', '%BPC-%');
			})
			->where('coa_id',332)
			->where('type','2')
			->where('branch',$branch)
			->orderBy('created_at','asc')
			->get();
			

			$arrOther = [];
			
			foreach($other as $row){
				// if(!str_contains($row->cashBank->code, 'RJCT')){
				
					$total = $row->nominal;
					
					$cekdata = $this->checkData($arrOther,$row->cash_bank_id);
					
					if($cekdata >= 0){
						$total += $arrOther[$cekdata]['nominal'];
						
						if(!str_contains($row->cashBank->code, 'BPC-')){
							foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rowpay){
								if(count(explode('-',$rowpay->code)) > 3){
									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter){ $query->where('code',$row->cashBank->code)->whereRaw("LEFT(date, 7) <= '$filter'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
										if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
											foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
												$total -= $rowdetail->nominal;
											}
										}
									}
								}
							}
						}
						
						if($total > 0){
							$arrOther[$cekdata]['nominal'] = $total;
						}
					}else{
						if(!str_contains($row->cashBank->code, 'BPC-')){
							foreach(PurchaseRequest::where('link_type','fee_pta')->where('link_id',$row->cashBank->id)->get() as $rowpay){
								$total -= $rowpay->totalPaymentPeriod($filter);
							}
							
							foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rowpay){
								if(count(explode('-',$rowpay->code)) > 3){
									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter){ $query->where('code',$row->cashBank->code)->whereRaw("LEFT(date, 7) <= '$filter'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
										if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
											foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
												$total -= $rowdetail->nominal;
											}
										}
									}
								}
							}
							
							if($row->cashBank->lookable_type == 'projects'){
								foreach(Project::find($row->cashBank->lookable_id)->projectSaleReturn as $rowreturn){
									if($rowreturn->compareProductReturnAndDelivery($row->cashBank->description)){
										foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter,$rowreturn){ 
											$query->where('lookable_type','project_sale_returns')->where('lookable_id',$rowreturn->id)->whereRaw("LEFT(date, 7) <= '$filter'"); 
										})->where('coa_id',332)->where('type','1')->where('branch',$branch)->get() as $rowcek){
											$total -= $rowcek->nominal;
										}
									}
								}
							}
						}
						
						if($total > 0){
							$row['nominal'] = $total;
							$arrOther[] = $row;
						}
					}
				// }
			}


			$samplepurchase = SamplePurchase::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('sampleWarehouse', function($query) use ($filter) {
				$query->whereRaw("LEFT(DATE(date_receive), 7) <= '$filter'");
			})
			->orderBy('created_at','asc')
			->get();

			$arrPurchaseSample = [];
			
			foreach($samplepurchase as $row){
				$totalreceivedsample = 0;
				$totalreturnsample = 0;
				$totalpaysample = 0;
				$totalcbsample = 0;
				
				foreach($row->sampleWarehouse()->whereRaw("LEFT(DATE(date_receive),7) <= '$filter'")->get() as $pw){
					$totalreceivedsample += $pw->grandtotal;
				}
				
				foreach($row->samplePurchaseReturn()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsr){
						$totalreturnsample += round($rsr->getTotal());
				}
				
				// foreach($row->samplePurchasePayment()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsp){
				// 	$totalpaysample += $rsp->nominal;
				// }
				
				$cb = CashBank::where('lookable_type','sample_purchases')->where('lookable_id',$row->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
							if($cbcb->type == '1'){
								$totalcbsample += $cbcb->nominal;
							}
						}
					}
				}
				
				if(round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample)) > 0){
					$row['totalbalance'] = round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample));
					$arrPurchaseSample[] = $row;
				}
			}
		}elseif($mode == '2'){
			
			$projectpurchase = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('projectWarehouse', function($query) use ($filter_date) {
				$query->whereRaw("DATE(date_receive) <= '$filter_date'");
			})->whereDoesntHave('purchaseCost')
			->orderBy('created_at','asc')
			->get();
			
			foreach($projectpurchase as $row){
				$totalreceived = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totaltransfer = 0;
				$totalcb = 0;
				
				foreach($row->projectWarehouse()->whereRaw("DATE(date_receive) <= '$filter_date'")->get() as $pw){
					$totalreceived += $pw->grandtotal;
				}
				
				foreach($row->projectPurchaseReturn()->whereRaw("DATE(date) <= '$filter_date'")->get() as $rsr){
					if(!$rsr->journalDownPayment()){
						$totalreturn += round($rsr->getTotal());
					}
				}
				
				foreach($row->projectPurchasePayment()->whereRaw("DATE(date) <= '$filter_date'")->get() as $rsp){
					$totalpay += $rsp->nominal;
				}
				
				$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$row->id)->whereRaw("DATE(date) <= '$filter_date'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
							if($cbcb->type == '1'){
								$totalcb += $cbcb->nominal;
							}
						}
					}
				}
				
				if(round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb)) > 0){
					$row['totalbalance'] = round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb));
					$arrPurchase[] = $row;
				}
			}
			
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter_date){
					$query
					->whereRaw("date <= '$filter_date'")
					->whereNotNull('supplier_id')
					->where('supplier_id','<>','0')
					->orWhere('code', 'like', '%BPC-%');
				})
				->where('coa_id',332)
				->where('type','2')
				->where('branch',$branch)
				->orderBy('created_at','asc')
				->get();
				
			$arrOther = [];
			
			foreach($other as $row){
				// if(!str_contains($row->cashBank->code, 'RJCT')){
				
					$total = $row->nominal;
					
					$cekdata = $this->checkData($arrOther,$row->cash_bank_id);
					
					if($cekdata >= 0){
						if(!str_contains($row->cashBank->code, 'BPC-')){
							$total += $arrOther[$cekdata]['nominal'];

							foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("DATE(date) <= '$filter_date'")->get() as $rowpay){
								if(count(explode('-',$rowpay->code)) > 3){
									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter_date){ $query->where('code',$row->cashBank->code)->whereRaw("date <= '$filter_date'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
										if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
											foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
												$total -= $rowdetail->nominal;
											}
										}
									}
								}
							}		

						}

						if($total > 0){
							$arrOther[$cekdata]['nominal'] = $total;
						}
					}else{

						if(!str_contains($row->cashBank->code, 'BPC-')){
							foreach(PurchaseRequest::where('link_type','fee_pta')->where('link_id',$row->cashBank->id)->get() as $rowpay){
								$total -= $rowpay->totalPaymentPeriod($filter_date);
							}
							
							foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("DATE(date) <= '$filter_date'")->get() as $rowpay){
								if(count(explode('-',$rowpay->code)) > 3){
									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter_date){ $query->where('code',$row->cashBank->code)->whereRaw("date <= '$filter_date'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
										if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
											foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
												$total -= $rowdetail->nominal;
											}
										}
									}
								}
							}
							
							// cek by desc no DO
							if($row->cashBank->lookable_type == 'projects'){
								foreach(Project::find($row->cashBank->lookable_id)->projectSaleReturn as $rowreturn){
									if($rowreturn->compareProductReturnAndDelivery($row->cashBank->description)){
										foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter_date,$rowreturn){ 
											$query->where('lookable_type','project_sale_returns')->where('lookable_id',$rowreturn->id)->whereRaw("date <= '$filter_date'"); 
										})->where('coa_id',332)->where('type','1')->where('branch',$branch)->get() as $rowcek){
											$total -= $rowcek->nominal;
										}
									}
								}
							}
						}
						
				
						if($total > 0){
							$row['nominal'] = $total;
							$arrOther[] = $row;
						}
					}
				// }
			}

			
			// foreach ($samplepurchase as $key => $row) {
			// 	foreach($row->sampleWarehouse()->get() as $pw){
			// 		echo '<pre>' . var_export($pw->id, true) . '</pre>';
			// 		echo '<pre>' . var_export($pw->code, true) . '</pre>';
				
			// 	}
			// }
			// dd($samplepurchase);

			$samplepurchase = SamplePurchase::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('sampleWarehouse', function($query) use ($filter_date) {
				$query->whereRaw("DATE(date_receive) <= '$filter_date'");
			})
			->orderBy('created_at','asc')
			->get();

			$arrPurchaseSample = [];
			
			foreach($samplepurchase as $row){
				$totalreceivedsample = 0;
				$totalreturnsample = 0;
				$totalpaysample = 0;
				$totalcbsample = 0;
				
				foreach($row->sampleWarehouse()->whereRaw("DATE(date_receive) <= '$filter_date'")->get() as $pw){
					$totalreceivedsample += $pw->grandtotal;
				}
				
				foreach($row->samplePurchaseReturn()->whereRaw("DATE(date) <= '$filter_date'")->get() as $rsr){
					$totalreturnsample += round($rsr->getTotal());
				}
				
				// foreach($row->samplePurchasePayment()->whereRaw("DATE(date) <= '$filter'")->get() as $rsp){
				// 	$totalpaysample += $rsp->nominal;
				// }
				
				$cb = CashBank::where('lookable_type','sample_purchases')->where('lookable_id',$row->id)->whereRaw("DATE(date) <= '$filter_date'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
							if($cbcb->type == '1'){
								$totalcbsample += $cbcb->nominal;
							}
						}
					}
				}
				
				if(round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample)) > 0){
					$row['totalbalance'] = round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample));
					$arrPurchaseSample[] = $row;
				}
			}
		}
		

        $data = [
            'title'   			=> 'Outstanding A/P',
			'filter'  			=> $filter,
			'branch'			=> $branch,
			'projectpurchase' 	=> $arrPurchase,
			'samplepurchase' 	=> $arrPurchaseSample,
			'other'				=> $arrOther,
			'mode'				=> $mode,
			'filter_date'		=> $filter_date,
            'content' 			=> 'admin.report.finance.outstanding_a_p'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function outstandingAPOther(Request $request) 
    {
		$mode = $request->mode ? $request->mode : '1';
		$filter = $mode == '1' ? ($request->filter ? $request->filter : date('Y-m')) : '';
		$branch = $request->branch ? $request->branch : '1';
		$filter_date = $mode == '2' ? ($request->filter_date ? $request->filter_date : date('Y-m-d')) : '';
		$listpayable = Coa::where('parent_id',81)->orderBy('code')->get();
		$type = $request->type ? $request->type : '';
		
		$other = [];
		
		$arrPayable = [];
		
		if($mode == '1'){
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
					$query->whereRaw("LEFT(DATE(date), 7) <= '$filter'");
				})
				->where('coa_id',$type)
				->where('type','2')
				->where('branch',$branch)
				->orderBy('created_at','asc')
				->get();
			
			$arrTempReturn = [];
			$arrTempReturnNominal = [];
			$arrAlreadyPaid = [];
			
			foreach($other as $row){
				// if(!str_contains($row->cashBank->code, 'RJCT')){
				
					$total = $row->nominal;
					$totalpay = 0;
					
					$arrpayment = [];
					
					if(str_contains($row->cashBank->code, 'PR-')){
						
						$payment = PurchaseRequestPayment::where('purchase_request_id',explode('-',$row->cashBank->code)[1])->whereRaw("LEFT(DATE(date_paid), 7) <= '$filter'")->get();
						
						$pr = PurchaseRequest::find(explode('-',$row->cashBank->code)[1]);
						
						if($pr){
							
							if($pr->link_type == 'project_deliveries'){
							
								if($pr->link_type == 'project_sales'){
									$projectsale = ProjectSale::find($pr->link_id);
								}elseif($pr->link_type == 'project_deliveries'){
									$projectdelivery = ProjectDelivery::find($pr->link_id);
									$projectsale = $projectdelivery->projectSale;
								}
								
								if($projectsale){
									
									foreach($projectsale->projectSaleReturn()->whereRaw("LEFT(DATE(date_return), 7) <= '$filter'")->get() as $rowsr){
										if(in_array($rowsr->id,$arrTempReturn) && !in_array(1,$arrTempReturn)){
										
										}else{
											$cb = CashBankDetail::where('coa_id',$type)->where('type','1')->whereHas('cashBank',function($query) use($rowsr){
												$query->where('lookable_id',$rowsr->id)->where('lookable_type','project_sale_returns');
											})->get();
											
											if($cb){
											
												foreach($cb as $rowcb){
													
													$arrpayment[] = [
														'code'			=> $rowcb->cashBank->code,
														'date'			=> $rowcb->cashBank->date,
														'description'	=> $rowcb->cashBank->description.' - SO '.$projectsale->code.' - PJ '.$projectsale->project->code,
														'nominal'		=> $rowcb->nominal
													];
													
													$totalpay += $rowcb->nominal;
												}
												
												$arrTempReturn[] = $rowsr->id;
											}
										}
									}
								}
							}
						}
						
						if($payment){
							foreach($payment as $rowpay){
								if(!in_array($rowpay->id, $arrAlreadyPaid)){
									if($rowpay->purchase_request_main_payment_id){
										$cb = CashBank::where('code','PRMP-'.$rowpay->purchase_request_main_payment_id)->get();
										
										if($cb){
											$arrpayment[] = [
												'code'			=> 'PRMP-'.$rowpay->purchase_request_main_payment_id,
												'date'			=> $rowpay->date_paid,
												'description'	=> $rowpay->note,
												'nominal'		=> $rowpay->nominal
											];
											
											$totalpay += $rowpay->nominal;
										}
									}else{
										$cb = CashBankDetail::whereHas('cashBank',function($query) use($branch,$filter,$rowpay){
											$query->where('code','PRP-'.$rowpay->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'");
										})->where('coa_id',$type)->where('type','1')->get();
										
										if($cb){
											foreach($cb as $rowcb){
												$arrpayment[] = [
													'code'			=> $rowcb->cashBank->code,
													'date'			=> $rowcb->cashBank->date,
													'description'	=> $rowcb->cashBank->description,
													'nominal'		=> $rowcb->nominal
												];
												
												$totalpay += $rowcb->nominal;
											}
										}
									}

									$arrAlreadyPaid [] = $rowpay->id;
								}
							}
						}

					}else{
						$projectsale = null;

						if($row->cashBank->lookable_type == 'project_deliveries'){
							$projectdelivery = ProjectDelivery::find($row->cashBank->lookable_id);
							$projectsale = $projectdelivery->projectSale;
						}


						if($projectsale) {
							$pr = PurchaseRequest::where('link_type', 'project_sales')->where('link_id', $projectsale->id)->first();
	
							if ($pr) {
								foreach ($projectsale->projectSaleReturn()->whereRaw("LEFT(DATE(date_return), 7) <= '$filter'")->get() as $rowsr) {
									if(in_array($rowsr->id, $arrTempReturn)) {
									}else{
										$cb = CashBankDetail::where('coa_id', $type)->where('type', '1')->whereHas('cashBank', function ($query) use ($rowsr) {
											$query->where('lookable_id', $rowsr->id)->where('lookable_type', 'project_sale_returns');
										})->get();
	
										if ($cb) {
	
											foreach ($cb as $rowcb) {
	
												$arrpayment[] = [
													'code'			=> $rowcb->cashBank->code,
													'date'			=> $rowcb->cashBank->date,
													'description'	=> $rowcb->cashBank->description . ' - SO ' . $projectsale->code . ' - PJ ' . $projectsale->project->code,
													'nominal'		=> $rowcb->nominal
												];
	
												$totalpay += $rowcb->nominal;
											}
										}
	
										$arrTempReturn[] = $rowsr->id;
									}
								}
							}
	
							$payment = PurchaseRequestPayment::where('purchase_request_id', $pr->id)->whereRaw("LEFT(DATE(date_paid), 7) <= '$filter'")->get();
	
							if($payment) {
								foreach ($payment as $rowpay) {
									if(!in_array($rowpay->id, $arrAlreadyPaid)){
										if($rowpay->purchase_request_main_payment_id) {
											$cb = CashBank::where('code', 'PRMP-' . $rowpay->purchase_request_main_payment_id)->get();
		
											if ($cb) {
												$arrpayment[] = [
													'code'			=> 'PRMP-' . $rowpay->purchase_request_main_payment_id,
													'date'			=> $rowpay->date_paid,
													'description'	=> $rowpay->note,
													'nominal'		=> $rowpay->nominal
												];
		
												$totalpay += $rowpay->nominal;
											}
										}else{
											$cb = CashBankDetail::whereHas('cashBank',function($query) use($branch,$filter,$rowpay){
												$query->where('code','PRP-'.$rowpay->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'");
											})->where('coa_id',$type)->where('type','1')->get();
		
											if ($cb) {
												foreach ($cb as $rowcb) {
													$arrpayment[] = [
														'code'			=> $rowcb->cashBank->code,
														'date'			=> $rowcb->cashBank->date,
														'description'	=> $rowcb->cashBank->description,
														'nominal'		=> $rowcb->nominal
													];
		
													$totalpay += $rowcb->nominal;
												}
											}
										}

										$arrAlreadyPaid [] = $rowpay->id;
									}
								}
							}
						}
					}
						
					if(str_contains($row->cashBank->code, 'RJCT')){
						$whereRaw = strlen($filter) == 7 ? "LEFT(date, 7) <= '$filter'" : "date <= '$filter'";
						$isMultipleReject = explode("-",$row->cashBank->code);
						$length_of_last_code = isset($isMultipleReject[3]) ? strlen($isMultipleReject[3]) : 0;
		
						$cek_rjct = isset($isMultipleReject[3]) ? CashBank::where('code','like',substr($row->cashBank->code, 0, -$length_of_last_code).'CLOSE-%'.$isMultipleReject[3])->whereRaw($whereRaw)->first() : CashBank::where('code','like', $row->cashBank->code.'-CLOSE%')->whereRaw($whereRaw)->first() ;


						$arrpayment[] = [
							'code'			=> $row->cashBank->code,
							'date'			=> $row->cashBank->date_paid,
							'description'	=> $row->cashBank->description,
							'nominal'		=> $row->nominal
						];
						

						$totalpay = $cek_rjct ? $cek_rjct->cashBankDetail->whereIn('coa_id', [342, 82, 83, 84, 85, 86, 87, 88, 281])->where('type', 1)->first()->nominal : 0;
						
					}
			
					
					// if(($total - $totalpay) > 0){
						$row['nominal'] = $total;
						$row['balance'] = $total - $totalpay;
						$row['arrpayment'] = $arrpayment;
						$row['description'] = isset($projectsale) ? 'SO '.$projectsale->code.' - PJ '.$projectsale->project->code : '';
						$arrPayable[] = $row;
					// }
				// }
			}
		}elseif($mode == '2'){
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter_date){
					$query->whereRaw("date <= '$filter_date'");
				})
				->where('coa_id',$type)
				->where('type','2')
				->where('branch',$branch)
				->orderBy('created_at','asc')
				->get();
			
			$arrTempReturn = [];
			$arrTempReturnNominal = [];
			$arrAlreadyPaid = [];
			
			foreach($other as $row){
				// if(!str_contains($row->cashBank->code, 'RJCT')){
				
					$total = $row->nominal;
					$totalpay = 0;
					
					$arrpayment = [];
					
					if(str_contains($row->cashBank->code, 'PR-')){
						
						$pr = PurchaseRequest::find(explode('-',$row->cashBank->code)[1]);
						
						if($pr){
							
							if($pr->link_type == 'project_deliveries'){
								
								$projectsale = null;
								
								if($pr->link_type == 'project_sales'){
									$projectsale = ProjectSale::find($pr->link_id);
								}elseif($pr->link_type == 'project_deliveries'){
									$projectdelivery = ProjectDelivery::find($pr->link_id);
									$projectsale = $projectdelivery->projectSale;
								}
								
								if($projectsale){
									
									foreach($projectsale->projectSaleReturn()->whereRaw("DATE(date_return) <= '$filter_date'")->get() as $rowsr){
										
										if(in_array($rowsr->id,$arrTempReturn)){
												
										}else{
											$cb = CashBankDetail::where('coa_id',$type)->where('type','1')->whereHas('cashBank',function($query) use($rowsr){
												$query->where('lookable_id',$rowsr->id)->where('lookable_type','project_sale_returns');
											})->get();
											
											if($cb){
												
												foreach($cb as $rowcb){
													
													$arrpayment[] = [
														'code'			=> $rowcb->cashBank->code,
														'date'			=> $rowcb->cashBank->date,
														'description'	=> $rowcb->cashBank->description.' - SO '.$projectsale->code.' - PJ '.$projectsale->project->code,
														'nominal'		=> $rowcb->nominal
													];
													
													$totalpay += $rowcb->nominal;
												}
											}
											
											$arrTempReturn[] = $rowsr->id;
										}
									}
								}
							}
						}
						
						$payment = PurchaseRequestPayment::where('purchase_request_id',explode('-',$row->cashBank->code)[1])->whereRaw("DATE(date_paid) <= '$filter_date'")->get();
						
						if($payment){
							foreach($payment as $rowpay){
								if(!in_array($rowpay->id, $arrAlreadyPaid)){
									if($rowpay->purchase_request_main_payment_id){
										$cb = CashBank::where('code','PRMP-'.$rowpay->purchase_request_main_payment_id)->get();
										
										if($cb){
											$arrpayment[] = [
												'code'			=> 'PRMP-'.$rowpay->purchase_request_main_payment_id,
												'date'			=> $rowpay->date_paid,
												'description'	=> $rowpay->note,
												'nominal'		=> $rowpay->nominal
											];
											
											$totalpay += $rowpay->nominal;
										}
									}else{
										$cb = CashBankDetail::whereHas('cashBank',function($query) use($branch,$filter_date,$rowpay){
											$query->where('code','PRP-'.$rowpay->id)->whereRaw("DATE(date) <= '$filter_date'");
										})->where('coa_id',$type)->where('type','1')->get();
										
										if($cb){
											foreach($cb as $rowcb){
												$arrpayment[] = [
													'code'			=> $rowcb->cashBank->code,
													'date'			=> $rowcb->cashBank->date,
													'description'	=> $rowcb->cashBank->description,
													'nominal'		=> $rowcb->nominal
												];
												
												$totalpay += $rowcb->nominal;
											}
										}
									}
									$arrAlreadyPaid [] = $rowpay->id;
								}
							}
						}
					}else{
						$projectsale = null;

						if($row->cashBank->lookable_type == 'project_deliveries'){
							$projectdelivery = ProjectDelivery::find($row->cashBank->lookable_id);
							$projectsale = $projectdelivery->projectSale;
						}


						if($projectsale) {
							$pr = PurchaseRequest::where('link_type', 'project_sales')->where('link_id', $projectsale->id)->first();
	
							if ($pr) {
								foreach ($projectsale->projectSaleReturn()->whereRaw("DATE(date_return) <= '$filter_date'")->get() as $rowsr) {
									if(in_array($rowsr->id, $arrTempReturn)) {
									}else{
										$cb = CashBankDetail::where('coa_id', $type)->where('type', '1')->whereHas('cashBank', function ($query) use ($rowsr) {
											$query->where('lookable_id', $rowsr->id)->where('lookable_type', 'project_sale_returns');
										})->get();
	
										if ($cb) {
	
											foreach ($cb as $rowcb) {
	
												$arrpayment[] = [
													'code'			=> $rowcb->cashBank->code,
													'date'			=> $rowcb->cashBank->date,
													'description'	=> $rowcb->cashBank->description . ' - SO ' . $projectsale->code . ' - PJ ' . $projectsale->project->code,
													'nominal'		=> $rowcb->nominal
												];
	
												$totalpay += $rowcb->nominal;
											}
										}
	
										$arrTempReturn[] = $rowsr->id;
									}
								}
							}
	
							$payment = PurchaseRequestPayment::where('purchase_request_id', $pr->id)->whereRaw("DATE(date_paid) <= '$filter_date'")->get();
	
							if($payment) {
								foreach ($payment as $rowpay) {
									if(!in_array($rowpay->id, $arrAlreadyPaid)){
										if($rowpay->purchase_request_main_payment_id) {
											$cb = CashBank::where('code', 'PRMP-' . $rowpay->purchase_request_main_payment_id)->get();
		
											if ($cb) {
												$arrpayment[] = [
													'code'			=> 'PRMP-' . $rowpay->purchase_request_main_payment_id,
													'date'			=> $rowpay->date_paid,
													'description'	=> $rowpay->note,
													'nominal'		=> $rowpay->nominal
												];
		
												$totalpay += $rowpay->nominal;
											}
										}else{
											$cb = CashBankDetail::whereHas('cashBank', function ($query) use ($branch, $filter_date, $rowpay) {
												$query->where('code', 'PRP-' . $rowpay->id)->whereRaw("DATE(date) <= '$filter_date'");
											})->where('coa_id', $type)->where('type', '1')->get();
		
											if ($cb) {
												foreach ($cb as $rowcb) {
													$arrpayment[] = [
														'code'			=> $rowcb->cashBank->code,
														'date'			=> $rowcb->cashBank->date,
														'description'	=> $rowcb->cashBank->description,
														'nominal'		=> $rowcb->nominal
													];
		
													$totalpay += $rowcb->nominal;
												}
											}
										}

										$arrAlreadyPaid [] = $rowpay->id;
									}
								}
							}
						}
					}

					
					if(str_contains($row->cashBank->code, 'RJCT')){
						$whereRaw = "date <= '$filter_date'";
						$isMultipleReject = explode("-",$row->cashBank->code);
						$length_of_last_code = isset($isMultipleReject[3]) ? strlen($isMultipleReject[3]) : 0;
		
						$cek_rjct = isset($isMultipleReject[3]) ? CashBank::where('code','like',substr($row->cashBank->code, 0, -$length_of_last_code).'CLOSE-%'.$isMultipleReject[3])->whereRaw($whereRaw)->first() : CashBank::where('code','like', $row->cashBank->code.'-CLOSE%')->whereRaw($whereRaw)->first() ;

						$arrpayment[] = [
							'code'			=> $row->cashBank->code,
							'date'			=> $row->cashBank->date_paid,
							'description'	=> $row->cashBank->description,
							'nominal'		=> $row->nominal
						];

						$totalpay = $cek_rjct ? $cek_rjct->cashBankDetail->whereIn('coa_id', [342, 82, 83, 84, 85, 86, 87, 88, 281])->where('type', 1)->first()->nominal : 0;
						
					}
					
					// if(($total - $totalpay) > 0){
						$row['nominal'] = $total;
						$row['balance'] = $total - $totalpay;
						$row['arrpayment'] = $arrpayment;
						$row['description'] = isset($projectsale) ? 'SO '.$projectsale->code.' - PJ '.$projectsale->project->code : '';
						$arrPayable[] = $row;
					// }
				// }
			}
		}
		
        $data = [
            'title'   			=> 'Outstanding A/P Other',
			'filter'  			=> $filter,
			'branch'			=> $branch,
			'mode'				=> $mode,
			'filter_date'		=> $filter_date,
			'listpayable'		=> $listpayable,
			'type'				=> $type,
			'arrPayable'		=> $arrPayable,
			'arrTempReturn'		=> $arrTempReturnNominal,
            'content' 			=> 'admin.report.finance.outstanding_a_p_other'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	function checkData($arr,$val){
		$ada = -1;
		
		foreach($arr as $key => $row){
			if($row->cash_bank_id == $val){
				$ada = $key;
			}
		}
		
		return $ada;
	}
	
	function checkDataAR($arr,$val){
		$ada = -1;
		
		foreach($arr as $key => $row){
			if($row['customer_id'] == $val){
				$ada = $key;
			}
		}
		
		return $ada;
	}
	
	public function agingReceivable(Request $request) 
    {
		$filter = $request->filter ? $request->filter : date('Y-m');
		$branch = $request->branch ? $request->branch : 1;
		
		$data = [];
		$data2 = [];
		
		$allproject = Project::whereHas('projectSale',function($query) use($branch){
			$query->whereHas('sales',function($query) use($branch){
				$query->where('branch',$branch);
			});
		})->get();
		
		foreach($allproject as $row){
			$arrdata = $row->getBalanceAR($filter);
			$total = $arrdata['total'];
			if($total > 0){
				$total30 = $arrdata['total30'];
				$total60 = $arrdata['total60'];
				$total90 = $arrdata['total90'];
				$totalover = $arrdata['totalover'];
				$index = $this->checkDataAR($data,$row->customer_id);
				if($index >= 0){
					$data[$index]['total'] += $total;
					$data[$index]['total30'] += $total30;
					$data[$index]['total60'] += $total60;
					$data[$index]['total90'] += $total90;
					$data[$index]['totalover'] += $totalover;
				}else{
					$data[] = [
						'customer_id'	=> $row->customer_id,
						'customer_name'	=> $row->customer->name.' - '.$row->customer->phone,
						'filter'		=> $filter,
						'total'			=> $total,
						'total30' 		=> $total30,
						'total60' 		=> $total60,
						'total90' 		=> $total90,
						'totalover' 	=> $totalover,
					];
				}
			}
			
			$arrdata2 = $row->getARBill($filter);
			$total2 = $arrdata2['total'];
			$total302 = $arrdata2['total30'];
			$total602 = $arrdata2['total60'];
			$total902 = $arrdata2['total90'];
			$totalover2 = $arrdata2['totalover'];
			$index2 = $this->checkDataAR($data2,$row->customer_id);
			if($total2 > 0){
				if($index2 >= 0){
					$data2[$index2]['total'] += $total2;
					$data2[$index2]['total30'] += $total302;
					$data2[$index2]['total60'] += $total602;
					$data2[$index2]['total90'] += $total902;
					$data2[$index2]['totalover'] += $totalover2;
				}else{
					$data2[] = [
						'customer_id'	=> $row->customer_id,
						'customer_name'	=> $row->customer->name.' - '.$row->customer->phone,
						'filter'		=> $filter,
						'total'			=> $total2,
						'total30' 		=> $total302,
						'total60' 		=> $total602,
						'total90' 		=> $total902,
						'totalover' 	=> $totalover2,
					];
				}
			}
		}
		
		$data = [
            'title'   			=> 'Aging Receivable',
			'filter'  			=> $filter,
			'branch'			=> $branch,
			'data'				=> $data,
			'data2'				=> $data2,
			'customer'			=> Customer::all(),
            'content' 			=> 'admin.report.accounting.aging_receivable_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	


	function checkDataAP($arr,$val){
		$index = -1;
		
		foreach($arr as $key => $row){
			if($row['supplier_id'] == $val){
				$index = $key;
			}
		}
		
		return $index;
	}
	function checkDataAPOther($arr,$val){
		$index = -1;
		
		foreach($arr as $key => $row){
			if($row['supplier_id'] == $val){
				$index = $key;
			}
		}
		
		return $index;
	}
	
	public function agingPayable(Request $request) 
    {
		set_time_limit(300);
		$filter = $request->filter ? $request->filter : date('Y-m');
		$branch = $request->branch ? $request->branch : 1;
		
		$data = [];
		$dataOther = [];

		$projectpurchase = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
			$query->where('branch',$branch);
		})->whereHas('projectWarehouse', function($query) use ($filter) {
		})->whereDoesntHave('purchaseCost')
		->orderBy('created_at','asc')
		->get();

		foreach ($projectpurchase as $row) {
			$arrdata = $row->getBalanceAP($filter);
			$total = $arrdata['total'];
			if($total > 0){
				$total30 = $arrdata['total30'];
				$total60 = $arrdata['total60'];
				$total90 = $arrdata['total90'];
				$totalover = $arrdata['totalover'];
				$index = $this->checkDataAP($data,$row->supplier_id);
				if($index >= 0){
					$data[$index]['total'] += $total;
					$data[$index]['total30'] += $total30;
					$data[$index]['total60'] += $total60;
					$data[$index]['total90'] += $total90;
					$data[$index]['totalover'] += $totalover;
				}else{
					$data[] = [
						'supplier_id'	=> $row->supplier_id,
						'supplier_name'	=> $row->supplier->name,
						'filter'		=> $filter,
						'total'			=> $total,
						'total30' 		=> $total30,
						'total60' 		=> $total60,
						'total90' 		=> $total90,
						'totalover' 	=> $totalover,
					];
				}
				
			}

		}
		
		$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
				$query
				->whereRaw("LEFT(date, 7) <= '$filter'")
				->whereNotNull('supplier_id')
				->where('supplier_id','<>','0')
				->orWhere('code', 'like', '%BPC-%');
			})
			->where('coa_id',332)
			->where('type','2')
			->where('branch',$branch)
			->orderBy('created_at','asc')
			->get();

			

			foreach ($other as $row) {
				$index = $this->checkDataAPOther($dataOther, $row->cashBank->supplier_id);
				$arrdata = $row->getBalanceOtherAP($filter, $branch, $index);
				$total = isset($arrdata['total']) ? $arrdata['total'] : 0;
				if($total > 0){
					$total30 = $arrdata['total30'];
					$total60 = $arrdata['total60'];
					$total90 = $arrdata['total90'];
					$totalover = $arrdata['totalover'];
					$balance_real = $arrdata['balance_real'];
				
					if($index >= 0){
						$dataOther[$index]['total'] += $total;
						$dataOther[$index]['total30'] += $total30;
						$dataOther[$index]['total60'] += $total60;
						$dataOther[$index]['total90'] += $total90;
						$dataOther[$index]['totalover'] += $totalover;
						$dataOther[$index]['balance_real'] += $balance_real;
						$dataOther[$index]['detail'][] = [
							'total'      => $total,
							'total30'    => $total30,
							'total60'    => $total60,
							'total90'    => $total90,
							'totalover'  => $totalover,
						];
					}else{
						$dataOther[] = [
							'cash_bank_id'  => $row->cash_bank_id,
							'lookable_type' => $row->cashBank->lookable_type,
							'lookable_id'   => $row->cashBank->lookable_id,
							'code'    		=> $row->cashBank->code,
							'supplier_id'	=> $row->cashBank->supplier_id ? $row->cashBank->supplier_id : '',
							'supplier_name'	=> isset($row->cashBank->supplier->name) ? $row->cashBank->supplier->name : 'Unknown',
							'description'	=> $row->cashBank->description,
							'filter'		=> $filter,
							'total'			=> $total,
							'total30' 		=> $total30,
							'total60' 		=> $total60,
							'total90' 		=> $total90,
							'totalover' 	=> $totalover,
							'balance_real'  => $balance_real,
							'detail'  	    => array(
								[
									'total'      => $total,
									'total30'    => $total30,
									'total60'    => $total60,
									'total90'    => $total90,
									'totalover'  => $totalover,
								]
							)
						];
					}
					
				}
	
			}

        $data = [
            'title'   			=> 'Aging Payable',
			'filter'  			=> $filter,
			'branch'			=> $branch,
			'data' 	            => $data,
			'dataother' 	    => $dataOther,
			'other'				=> $other,
            'content' 			=> 'admin.report.accounting.aging_payable_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}

	public function agingPayableCard(Request $request) 
    {

		$branch = $request->branch ? $request->branch : "1";
	
		$allproject = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
			$query->where('branch',$branch);
		})->whereHas('projectWarehouse')
		->whereDoesntHave('purchaseCost')
		->where('supplier_id', $request->supplier_id)
		->orderBy('created_at','asc')
		->get();

		$data = [];
		
		foreach($allproject as $row){
			$data[] = $row;
		}
		
		$data = [
			'title'				=> 'PAYABLE CARD <br>Supplier',
			'supplier_name'		=> Supplier::find($request->supplier_id)->name,
			'data'				=> $data ,
			'date'				=> date('Y-m-t', strtotime($request->filter))
		];
		
		return view('admin.pdf.report.accounting.aging_payable', $data);
	}
	
	public function agingPayableOtherCard(Request $request) 
    {

		$branch = $request->branch ? $request->branch : "1";
		$filter = $request->filter ? $request->filter : date('Y-m');
		$supplier = $request->supplier_id;

		$other = CashBankDetail::whereHas('cashBank', function($query) use($filter, $supplier){
			$query
			->whereRaw("LEFT(date, 7) <= '$filter'")
			->whereNotNull('supplier_id')
			->where('supplier_id', $supplier)
			->orWhere('code', 'like', '%BPC-%');
		})
		->where('coa_id',332)
		->where('type','2')
		->where('branch',$branch)
		->orderBy('created_at','asc')
		->get();

		$data = [];
		
		foreach($other  as $row){
			$data[] = $row;
		}
		
		$data = [
			'title'				=> 'PAYABLE CARD <br>Supplier',
			'supplier_name'		=> Supplier::find($request->supplier_id)->name,
			'data'				=> $data ,
			'date'				=> date('Y-m-t', strtotime($request->filter))
		];
		
		return view('admin.pdf.report.accounting.aging_payable', $data);
	}
	
	// public function agingPayable2(Request $request) 
    // {
	// 	$filter = $request->filter ? $request->filter : date('Y-m');
	// 	$branch = $request->branch ? $request->branch : 1;
		
	// 	$projectpurchase = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
	// 		$query->where('branch',$branch);
	// 	})->whereHas('projectWarehouse', function($query) use ($filter) {
	// 		$query->whereRaw("LEFT(DATE(date_receive), 7) <= '$filter'");
	// 	})
	// 	->orderBy('created_at','asc')
	// 	->get();
		
	// 	$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
	// 			$query->whereNotNull('supplier_id')
	// 			->where('supplier_id','<>','0')
	// 			->whereRaw("LEFT(date, 7) <= '$filter'");
	// 		})
	// 		->where('coa_id',332)
	// 		->where('type','2')
	// 		->where('branch',$branch)
	// 		->orderBy('created_at','asc')
	// 		->get();

    //     $data = [
    //         'title'   			=> 'Aging Payable',
	// 		'filter'  			=> $filter,
	// 		'branch'			=> $branch,
	// 		'projectpurchase' 	=> $projectpurchase,
	// 		'other'				=> $other,
    //         'content' 			=> 'admin.report.accounting.aging_payable_detail'
    //     ];

    //     return view('admin.layouts.index', ['data' => $data]);
	// }
	
	public function coaCheat() 
    {
		$coa = Coa::orderBy('code')->get();
		
		foreach($coa->where('parent_id',0) as $rowparent){
			echo $rowparent->code.' '.$rowparent->name.' Child '.count($rowparent->child()).'<br>';
			foreach($rowparent->child() as $rowchild){
				$txt = '';
				if(count($rowchild->child()) == 0){
					$balance = $rowchild->checkTotal("2021-11");
					$txt = ' Balance '.$balance;
				}
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$rowchild->code.' '.$rowchild->name.' Child '.count($rowchild->child()).$txt.'<br>';
				foreach($rowchild->child() as $rowgrandchild){
					$txt = '';
					if(count($rowgrandchild->child()) == 0){
						$balance = $rowgrandchild->checkTotal("2021-11");
						$txt = ' Balance '.$balance;
					}
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$rowgrandchild->code.' '.$rowgrandchild->name.' Child '.count($rowgrandchild->child()).$txt.'<br>';
					
					foreach($rowgrandchild->child() as $rowgrandgrandchild){
						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$rowgrandgrandchild->code.' '.$rowgrandgrandchild->name.' Balance '.$rowgrandgrandchild->checkTotal("2021-11").'<br>';
					}
				}
			}
		}
	}
	
	public function budgetingComparison(Request $request){
		
		$resultbudget = [];
		$resultproject = [];
		
		if($request->has('_token') && session()->token() == $request->_token) {
			if($request->budget_id && $request->project_id){
				foreach($request->budget_id as $key => $bgid){
					$bp = BudgetingProject::find($bgid);
					$resultbudget[] = $bp;
				}
				
				foreach($request->project_id as $key => $pjid){
					$pr = Project::find($pjid);
					$resultproject[] = $pr;
				}
				
				$errorEstimation = false;
				
				foreach($resultbudget as $rowall){
					foreach($rowall->budgetingProjectDetail as $row){
						if($row->estimation == 0){
							//$errorEstimation = true;
						}
					}
				}
				
				if($errorEstimation == true){
					return redirect(url()->full())
						->withErrors(['Error' => 'Budgeting Project detail(s) estimation is(are) zero, please complete the form.']);
				}
					
			}else{
				return redirect(url()->full())
						->withErrors(['Error' => 'Budgeting Project or Project cannot be empty.']);
			}
		}
		
		$data = [
            'title'   				=> 'Budgeting Comparison',
			'budgeting_project'  	=> BudgetingProject::all(),
			'resultbudget'			=> $resultbudget,
			'resultproject'			=> $resultproject,
            'content' 				=> 'admin.report.accounting.budgeting_comparison'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function print(Request $request,$param){
		if($param == 'oar'){
			
			$filter = $request->monthyear;
			$branch = $request->branch;
			
			$sale = [];
			$bill = [];
			
			$projectsale = ProjectSale::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('projectDelivery', function($query) use ($filter) {
				$query->whereRaw("LEFT(received_date, 7) <= '$filter'");
			})->get();
			
			foreach($projectsale as $key => $row){
				$totaldelivered = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totalcb = 0;
				$totalbill = 0;
				
				foreach($row->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->whereRaw("LEFT(DATE(received_date), 7) <= '$filter'")->get() as $rd){
					if($rd->journal() || (count($rd->project->projectBill()->get()) > 0)){
						$totaldelivered += round($rd->grandtotal_product + $rd->grandtotal_service);
					}else{
						$totaldelivered += 0;
					}
				}
				
				foreach($row->projectSaleReturn()->whereRaw("LEFT(DATE(created_at), 7) <= '$filter'")->get() as $rsr){
					$totalreturn += round($rsr->getTotal());
				}
				
				foreach($row->projectSalePay()->whereRaw("LEFT(DATE(date), 7) <= '$filter' AND ((project_bill_id IS NOT NULL AND project_delivery_id IS NOT NULL) OR (project_bill_id IS NULL OR project_bill_id = 0))")->get() as $rsp){
					if(isset($rsp->projectBill) && $rsp->projectBill->journal()){
						if(($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal == 0){
							$totalpay -=  ($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal;
						}else{
							$totalpay += $rsp->nominal;
						}
					}else{
						$totalpay += $rsp->nominal;
					}
				}
				
				foreach($row->project->projectBill()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $pb){
					if($pb->journal()){
						$totalbill += $pb->nominal + $pb->nominal_service;
					}
				}
				
				$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->project->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
							/* if($cbcb->type == '1'){
								$totalcb += $cbcb->nominal;
							}else*/if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}

						foreach($rowcb->cashBankDetail()->where('coa_id',67)->get() as $cbcb){
							if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}
					}
				}
				
				if(round(($totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill)) > 0){
					$row['totalbalance'] = $totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill;
					$row['totaldelivered'] = $totaldelivered;
					$row['totalpay'] = $totalpay + $totalbill;
					$row['totalreturn'] = $totalreturn;
					$sale[] = $row;
				}
			}
			
			$projectbill = ProjectBill::where('branch',$branch)
			->whereRaw("LEFT(date, 7) <= '$filter'")
			->get();
			
			foreach($projectbill as $key => $row){
				$already_bill = false;
				foreach($row->project->projectSale as $rs){
					/* if($rs->getTotalDelivered()['totaldelivered'] == ($row->nominal + $row->nominal_service)){
						$already_bill = true;
					} */
					
					/* if($rs->getTotalDelivered()['totaldelivered'] >= str_replace(',','.',str_replace('.','',$rs->getTotal()))){
						$already_sent = true;
					} */
				}
				
				$cek = CashBank::where('lookable_type','project_bills')->where('lookable_id',$row->id)->whereRaw("LEFT(date, 7) <= '$filter'")->first();
				
				if(!$cek){
					$already_bill = true;
				}
				
				if($already_bill == false && $row->balancePeriod($filter) > 0){
					$bill[] = $row;
				}
			}
			
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
					$query->whereNotNull('customer_id')
					->where('customer_id','<>','0')
					->whereRaw("LEFT(date, 7) <= '$filter'");
				})
				->where('coa_id',27)
				->where('branch',$branch)
				->where('type','1')
				->orderBy('created_at','asc')
				->get();
			
			$data = [
				'title'   			=> 'Outstanding A/R',
				'filter'  			=> $filter,
				'branch'			=> $branch,
				'projectsale' 		=> $sale,
				'projectbill'		=> $bill,
				'other'				=> $other,
				'content' 			=> 'admin.report.finance.outstanding_a_r'
			];
			
			$pdf = PDF::loadView('admin.pdf.report.accounting.outstanding_a_r', [
					'filter'  			=> $filter,
					'branch'			=> $branch,
					'projectsale' 		=> $sale,
					'projectbill'		=> $bill,
					'other'				=> $other
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'oar_selected'){
			$filter = $request->monthyear;
			$branch = $request->branch;
			
			$sale = [];
			$bill = [];

			$arrId = explode(',', $request->id);

			$projectsale = ProjectSale::whereIn('id', $arrId)->whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('projectDelivery', function($query) use ($filter) {
				$query->whereRaw("LEFT(received_date, 7) <= '$filter'");
			})->get();
			
			foreach($projectsale as $key => $row){
				$totaldelivered = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totalcb = 0;
				$totalbill = 0;
				
				foreach($row->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->whereRaw("LEFT(DATE(received_date), 7) <= '$filter'")->get() as $rd){
					// JIKA DELIVERY PUNYA JURNAL BERUPA PIUTANG/ PUNYA BILL (INI UNTUK MENGHINDARI CRASH DULU SEBELUM PROGRAM PUNYA FITUR BILL)
					if($rd->journal() || (count($rd->project->projectBill()->get()) > 0)){
						$totaldelivered += round($rd->grandtotal_product + $rd->grandtotal_service);
					}else{
						$totaldelivered += 0;
					}
				}
				
				
				foreach($row->projectSaleReturn()->whereRaw("LEFT(DATE(created_at), 7) <= '$filter'")->get() as $rsr){
					$totalreturn += round($rsr->getTotal());
				}
				
				foreach($row->projectSalePay()->whereRaw("LEFT(DATE(date), 7) <= '$filter' AND ((project_bill_id IS NOT NULL AND project_delivery_id IS NOT NULL) OR (project_bill_id IS NULL OR project_bill_id = 0))")->get() as $rsp){
					if(isset($rsp->projectBill) && $rsp->projectBill->journal()){
						if(($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal == 0){
							$totalpay -=  ($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal;
						}else{
							$totalpay += $rsp->nominal;
						}
					}else{
						$totalpay += $rsp->nominal;
					}
				}
				
				foreach($row->project->projectBill()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $pb){
					if($pb->journal()){
						$totalbill += $pb->nominal + $pb->nominal_service;
					}
				}
				
				$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->project->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
							/* if($cbcb->type == '1'){
								$totalcb += $cbcb->nominal;
							}else*/if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}

						foreach($rowcb->cashBankDetail()->where('coa_id',67)->get() as $cbcb){
							if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}
					}
				}
				
				if(round(($totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill)) > 0){
					$row['totalbalance'] = $totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill;
					$row['totaldelivered'] = $totaldelivered;
					$row['totalpay'] = $totalpay + $totalbill;
					$row['totalreturn'] = $totalreturn;
					$sale[] = $row;
				}
			}
			
			$projectbill = ProjectBill::where('branch',$branch)
			->whereRaw("LEFT(date, 7) <= '$filter'")
			->get();
			
			foreach($projectbill as $key => $row){
				$already_bill = false;
				foreach($row->project->projectSale as $rs){

				}
				
				$cek = CashBank::where('lookable_type','project_bills')->where('lookable_id',$row->id)->whereRaw("LEFT(date, 7) <= '$filter'")->first();
				
				if(!$cek){
					$already_bill = true;
				}
				
				if($already_bill == false && $row->balancePeriod($filter) > 0){
					$bill[] = $row;
				}
			}
			
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
					$query->whereNotNull('customer_id')
					->where('customer_id','<>','0')
					->whereRaw("LEFT(date, 7) <= '$filter'");
				})
				->where('coa_id',27)
				->where('branch',$branch)
				->where('type','1')
				->orderBy('created_at','asc')
				->get();
		
			
			$pdf = PDF::loadView('admin.pdf.report.accounting.outstanding_a_r', [
					'filter'  			=> $filter,
					'branch'			=> $branch,
					'projectsale' 		=> $sale,
					'projectbill'		=> $bill,
					'other'				=> $other
				],
				[],
				[ 
					'format' => 'A4-P',
					'orientation' => 'P'
				]
			);
			
		}
		
		return $pdf->stream('TJS Report Outstanding A_R Branch '.($branch == "1" ? "Surabaya" : "Jakarta").' Period '.$filter.'.pdf');
	}
	
	public function projectComparison(Request $request){
		
		$resultproject = [];
		
		$start_date = '';
		$finish_date = '';
		
		if($request->has('_token') && session()->token() == $request->_token) {
			if($request->project_id){
				foreach($request->project_id as $key => $pjid){
					$pr = Project::find($pjid);
					$resultproject[] = $pr;
				}
				
				$start_date = $request->start_date ? $request->start_date : '';
				$finish_date = $request->finish_date ? $request->finish_date : '';
			}else{
				return redirect(url()->full())
						->withErrors(['Error' => 'Project cannot be empty.']);
			}
		}
		
		$data = [
            'title'   				=> 'Project Comparison',
			'project'				=> Project::whereHas('projectDelivery')->get(),
			'resultproject'			=> $resultproject,
			'start_date'			=> $start_date,
			'finish_date'			=> $finish_date,
            'content' 				=> 'admin.report.accounting.project_comparison'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function profitLossProjectIndex(Request $request){
		
		
		$data = [
            'title'   				=> 'Profit and Loss Project',
            'content' 				=> 'admin.report.accounting.profit_loss_project'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function profitLossProjectResult(Request $request){
		
		$coa = Coa::where('parent_id',0)->whereRaw('SUBSTR(code,1,1) = "4" OR SUBSTR(code,1,1) = "5" OR SUBSTR(code,1,1) = "6" OR SUBSTR(code,1,1) = "7"')->orderBy('code')->get();
		
		$html = '
			<div class="table-responsive">
				<table class="table table-sm table-bordered fixed_header">
				   <thead class="sticky-header">
					  <tr class="text-center bg-primary font-weight-bold" style="font-size:18px;">
						 <th class="fixed" style="min-width:450px;">Description</th>
						 ';
						
		$project = Project::whereHas('projectSale', function($query) use ($request) {
						$query->whereHas('sales', function($query) use ($request){
							$query->where('branch',$request->branch);
						})->whereHas('projectDelivery', function($query) use ($request){
							$query->whereRaw('DATE(received_date) >= "'.$request->start_date.'" AND DATE(received_date) <= "'.$request->finish_date.'"');
						});
					})->orWhereHas('projectSaleReturn', function($query) use ($request){
						$query->whereHas('projectSale', function($query) use ($request) {
							$query->whereHas('sales', function($query) use ($request){
								$query->where('branch',$request->branch);
							});
						})->whereRaw('DATE(date_return) >= "'.$request->start_date.'" AND DATE(date_return) <= "'.$request->finish_date.'"');
					})->get();
					
		$arrProjectId = [];
		
		foreach($project as $rowproject){
			$arrProjectId[] = $rowproject->id;
			$title = 'Project No. '.$rowproject->code.' - '.$rowproject->name;
			$html .= '
				<th style="min-width:200px;">'.(strlen($title) > 55 ? substr($title,0,55)."..." : $title).'</th>
				<th style="min-width:25px;">%</th>
			';
		}
		
		$html .= '
						<th style="min-width:200px;">'.($request->branch == '1' ? 'PTA' : 'SMB').'</th>
						<th style="min-width:25px;">%</th>
						<th style="min-width:200px;">Total</th>
						<th style="min-width:25px;">%</th>
					  </tr>
				   </thead>
		';
		
		$html .= '<tbody>';
		
		$total_revenue_actual = [];
		$total_cogs_actual = [];	
		$total_fixed_cost_actual = [];
		$total_variable_cost_actual = [];
		$total_other_expenses_actual = [];
		$total_repair_expenses_actual = [];
		$total_depreciation_actual = [];
		$total_capex_actual = [];
		$total_other_income_actual = [];
		$total_other_deduction_actual = [];
		$total_revenue_unallocated = 0;
		$total_cogs_unallocated = 0;	
		$total_fixed_cost_unallocated = 0;
		$total_variable_cost_unallocated = 0;
		$total_repair_expenses_unallocated = 0;
		$total_depreciation_unallocated = 0;
		$total_capex_unallocated = 0;
		$total_other_income_unallocated = 0;
		$total_other_deduction_unallocated = 0;
		$total_unallocated_coa = 0;
		
		foreach($project as $key => $rowproject){
			$total_revenue_actual[$key] = 0;
			$total_cogs_actual[$key] = 0;	
			$total_fixed_cost_actual[$key] = 0;
			$total_variable_cost_actual[$key] = 0;
			$total_other_expenses_actual[$key] = 0;
			$total_repair_expenses_actual[$key] = 0;
			$total_depreciation_actual[$key] = 0;
			$total_capex_actual[$key] = 0;
			$total_other_income_actual[$key] = 0;
			$total_other_deduction_actual[$key] = 0;
		}
		
		$totalallrevenue = 0;
		
		foreach($coa->where('parent_id',0) as $primarykey => $rowparent){
			foreach($project as $key => $rowproject){
				$balance = 0;
				if(count($rowparent->child()) == 0){
					$balance = $rowparent->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_balance'];
					
					if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
						$total_revenue_actual[$key] += $balance;
						$totalallrevenue += $balance;
					}
					
					if(substr($rowparent->code,0,5) == '5.000' || substr($rowparent->code,0,5) == '6.000' || substr($rowparent->code,0,5) == '6.100'){
						$total_cogs_actual[$key] += $balance;
					}
					
					if(substr($rowparent->code,0,5) == '6.200'){
						$total_fixed_cost_actual[$key] += $balance;
					}
					
					if(substr($rowparent->code,0,9) == '6.2100.02'){
						$total_variable_cost_actual[$key] += $balance;
					}
					
					if(substr($rowparent->code,0,9) == '6.2100.03'){
						$total_other_expenses_actual[$key] += $balance;
					}
					
					if(substr($rowparent->code,0,6) == '6.2200'){
						$total_repair_expenses_actual[$key] += $balance;
					}
					
					if(substr($rowparent->code,0,5) == '6.300'){
						$total_depreciation_actual[$key] += $balance;
					}
					
					if(substr($rowparent->code,0,5) == '6.400'){
						$total_capex_actual[$key] += $balance;
					}
					
					if(substr($rowparent->code,0,5) == '7.100'){
						$total_other_income_actual[$key] += $balance;
					}
					
					if(substr($rowparent->code,0,5) == '7.200'){
						$total_other_deduction_actual[$key] += $balance;
					}
				}
			}
			
			foreach($rowparent->child() as $rowchild){
				foreach($project as $key => $rowproject){
					$balance = 0;
					if(count($rowchild->child()) == 0){
						$balance = $rowchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_balance'];
						
						if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
							$total_revenue_actual[$key] += $balance;
							$totalallrevenue += $balance;
						}
						
						if(substr($rowchild->code,0,5) == '5.000' || substr($rowchild->code,0,5) == '6.000' || substr($rowchild->code,0,5) == '6.100'){
							$total_cogs_actual[$key] += $balance;
						}
						
						if(substr($rowchild->code,0,5) == '6.200'){
							$total_fixed_cost_actual[$key] += $balance;
						}
						
						if(substr($rowchild->code,0,9) == '6.2100.02'){
							$total_variable_cost_actual[$key] += $balance;
						}
						
						if(substr($rowchild->code,0,9) == '6.2100.03'){
							$total_other_expenses_actual[$key] += $balance;
						}
						
						if(substr($rowchild->code,0,6) == '6.2200'){
							$total_repair_expenses_actual[$key] += $balance;
						}
						
						if(substr($rowchild->code,0,5) == '6.300'){
							$total_depreciation_actual[$key] += $balance;
						}
						
						if(substr($rowchild->code,0,5) == '6.400'){
							$total_capex_actual[$key] += $balance;
						}
						
						if(substr($rowchild->code,0,5) == '7.100'){
							$total_other_income_actual[$key] += $balance;
						}
						
						if(substr($rowchild->code,0,5) == '7.200'){
							$total_other_deduction_actual[$key] += $balance;
						}
					}
				}
				
				foreach($rowchild->child() as $rowgrandchild){
					foreach($project as $key => $rowproject){
						$balance = 0;
						if(count($rowgrandchild->child()) == 0){
							$balance = $rowgrandchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_balance'];
							
							if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
								$total_revenue_actual[$key] += $balance;
								$totalallrevenue += $balance;
							}
							
							if(substr($rowgrandchild->code,0,5) == '5.000' || substr($rowgrandchild->code,0,5) == '6.000' || substr($rowgrandchild->code,0,5) == '6.100'){
								$total_cogs_actual[$key] += $balance;
							}
							
							if(substr($rowgrandchild->code,0,5) == '6.200'){
								$total_fixed_cost_actual[$key] += $balance;
							}
							
							if(substr($rowgrandchild->code,0,9) == '6.2100.02'){
								$total_variable_cost_actual[$key] += $balance;
							}
							
							if(substr($rowgrandchild->code,0,9) == '6.2100.03'){
								$total_other_expenses_actual[$key] += $balance;
							}
							
							if(substr($rowgrandchild->code,0,6) == '6.2200'){
								$total_repair_expenses_actual[$key] += $balance;
							}
							
							if(substr($rowgrandchild->code,0,5) == '6.300'){
								$total_depreciation_actual[$key] += $balance;
							}
							
							if(substr($rowgrandchild->code,0,5) == '6.400'){
								$total_capex_actual[$key] += $balance;
							}
							
							if(substr($rowgrandchild->code,0,5) == '7.100'){
								$total_other_income_actual[$key] += $balance;
							}
							
							if(substr($rowgrandchild->code,0,5) == '7.200'){
								$total_other_deduction_actual[$key] += $balance;
							}
						}
					}
					
					foreach($rowgrandchild->child() as $rowgrandgrandchild){
						foreach($project as $key => $rowproject){
							$balance = 0;
							if(count($rowgrandgrandchild->child()) == 0){
								$balance = $rowgrandgrandchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_balance'];
								
								if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
									$total_revenue_actual[$key] += $balance;
									$totalallrevenue += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,5) == '5.000' || substr($rowgrandgrandchild->code,0,5) == '6.000' || substr($rowgrandgrandchild->code,0,5) == '6.100'){
									$total_cogs_actual[$key] += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,5) == '6.200'){
									$total_fixed_cost_actual[$key] += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,9) == '6.2100.02'){
									$total_variable_cost_actual[$key] += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,9) == '6.2100.03'){
									$total_other_expenses_actual[$key] += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,6) == '6.2200'){
									$total_repair_expenses_actual[$key] += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,5) == '6.300'){
									$total_depreciation_actual[$key] += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,5) == '6.400'){
									$total_capex_actual[$key] += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,5) == '7.100'){
									$total_other_income_actual[$key] += $balance;
								}
								
								if(substr($rowgrandgrandchild->code,0,5) == '7.200'){
									$total_other_deduction_actual[$key] += $balance;
								}
							}
						}
					}
				}
			}
		}
		
		foreach($coa->where('parent_id',0) as $primarykey => $rowparent){
			foreach($project as $key => $rowproject){
				$total[$key] = 0;
			}
			
			$html .= '<tr class="font-weight-bold bg-grey-300">
				<td style="padding:10px;">['.$rowparent->code.'] '.$rowparent->name.'</td>
				';
			$totalunallocated = 0;
			$totalunallocatedcoa = 0;
			$totalrow = 0;
			$totalrowall = 0;
			
			foreach($project as $key => $rowproject){
				$balance = 0;
				if(count($rowparent->child()) == 0){
					$totalunallocated += $key == 0 ? ($rowparent->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_unproject']) : 0;
					$balance = $rowparent->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_balance'];
					$total[$key] += $balance;
					
					$totalrow += $balance;
				}
				$html .= '
					<td class="text-right"><a href="javascript:void(0);" onclick="getCoaDetails('.$rowproject->id.','.$rowparent->id.','.$request->branch.',`'.$request->start_date.'`,`'.$request->finish_date.'`)">'.number_format($balance,2,',','.').'</a></td>
					<td class="text-center">'.($total_revenue_actual[$key] > 0 ? round($balance/$total_revenue_actual[$key] * 100,2) : 0).'%</td>
				';
			}
			
			$totalunallocatedcoa += $totalunallocated;
			$totalrow += $totalunallocated;
			$totalrowall += $totalrow;
			
			$html .= '
					<td class="text-right"><a href="javascript:void(0);" onclick="getCoaDetailsNonProject('.$rowparent->id.','.$request->branch.',`'.$request->start_date.'`,`'.$request->finish_date.'`)">'.number_format($totalunallocated,2,',','.').'</a></td>
					<td class="text-center">0</td>
					<td class="text-right"><a href="'.url("admin/report/accounting/ledger").'?coa_id='.$rowparent->id.'&start='.$request->start_date.'&end='.$request->finish_date.'&branch='.$request->branch.'" target="_blank">'.number_format($totalrow,2,',','.').'</a></td>
					<td class="text-center">'.($totalallrevenue > 0 ? round(($totalrow / $totalallrevenue) * 100,2) : 0).'%</td>
				</tr>
			';
			
			foreach($rowparent->child() as $rowchild){
				$html .= '<tr class="font-weight-bold" style="background-color:#e6e6e6;">
					<td style="padding-left:50px;">['.$rowchild->code.'] '.$rowchild->name.'</td>
					';
				$totalrow = 0;
				$totalunallocated = 0;
				foreach($project as $key => $rowproject){
					$balance = 0;
					if(count($rowchild->child()) == 0){
						$totalunallocated += $key == 0 ? ($rowchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_unproject']) : 0;
						$balance = $rowchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_balance'];
						$total[$key] += $balance;
						
						$totalrow += $balance;
					}
					$html .= '
						<td class="text-right"><a href="javascript:void(0);" onclick="getCoaDetails('.$rowproject->id.','.$rowchild->id.','.$request->branch.',`'.$request->start_date.'`,`'.$request->finish_date.'`)">'.number_format($balance,2,',','.').'</a></td>
						<td class="text-center">'.($total_revenue_actual[$key] > 0 ? round($balance/$total_revenue_actual[$key] * 100,2) : 0).'%</td>
						
					';
				}
				
				$totalunallocatedcoa += $totalunallocated;
				$totalrow += $totalunallocated;
				$totalrowall += $totalrow;
					
				$html .= '
						<td class="text-right"><a href="javascript:void(0);" onclick="getCoaDetailsNonProject('.$rowchild->id.','.$request->branch.',`'.$request->start_date.'`,`'.$request->finish_date.'`)">'.number_format($totalunallocated,2,',','.').'</a></td>
						<td class="text-center">0</td>
						<td class="text-right"><a href="'.url("admin/report/accounting/ledger").'?coa_id='.$rowchild->id.'&start='.$request->start_date.'&end='.$request->finish_date.'&branch='.$request->branch.'" target="_blank">'.number_format($totalrow,2,',','.').'</a></td>
						<td class="text-center">'.($totalallrevenue > 0 ? round(($totalrow / $totalallrevenue) * 100,2) : 0).'%</td>
					</tr>
				';
				
				foreach($rowchild->child() as $rowgrandchild){
					$html .= '<tr class="font-weight-bold" style="background-color:#e6e6e6;">
						<td style="padding-left:75px;">['.$rowgrandchild->code.'] '.$rowgrandchild->name.'</td>
						';
					$totalrow = 0;
					$totalunallocated = 0;
					foreach($project as $key => $rowproject){
						$balance = 0;
						if(count($rowgrandchild->child()) == 0){
							$totalunallocated += $key == 0 ? ($rowgrandchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_unproject']) : 0;
							$balance = $rowgrandchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_balance'];
							$total[$key] += $balance;
							
							$totalrow += $balance;
						}
						$html .= '
							<td class="text-right"><a href="javascript:void(0);" onclick="getCoaDetails('.$rowproject->id.','.$rowgrandchild->id.','.$request->branch.',`'.$request->start_date.'`,`'.$request->finish_date.'`)">'.number_format($balance,2,',','.').'</a></td>
							<td class="text-center">'.($total_revenue_actual[$key] > 0 ? round($balance/$total_revenue_actual[$key] * 100,2) : 0).'%</td>
						';
					}
					
					$totalunallocatedcoa += $totalunallocated;
					$totalrow += $totalunallocated;
					$totalrowall += $totalrow;
				
					$html .='
							<td class="text-right"><a href="javascript:void(0);" onclick="getCoaDetailsNonProject('.$rowgrandchild->id.','.$request->branch.',`'.$request->start_date.'`,`'.$request->finish_date.'`)">'.number_format($totalunallocated,2,',','.').'</a></td>
							<td class="text-center">0</td>
							<td class="text-right"><a href="'.url("admin/report/accounting/ledger").'?coa_id='.$rowgrandchild->id.'&start='.$request->start_date.'&end='.$request->finish_date.'&branch='.$request->branch.'" target="_blank">'.number_format($totalrow,2,',','.').'</a></td>
							<td class="text-center">'.($totalallrevenue > 0 ? round(($totalrow / $totalallrevenue) * 100,2) : 0).'%</td>
						</tr>
					';
					
					foreach($rowgrandchild->child() as $rowgrandgrandchild){
						$html .= '<tr class="font-weight-bold" style="background-color:#e6e6e6;">
							<td style="padding-left:100px;">['.$rowgrandgrandchild->code.'] '.$rowgrandgrandchild->name.'</td>
							';
						$totalunallocated = 0;
						$totalrow = 0;
						foreach($project as $key => $rowproject){
							$balance = 0;
							if(count($rowgrandgrandchild->child()) == 0){
								$totalunallocated += $key == 0 ? ($rowgrandgrandchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_unproject']) : 0;
								$balance = $rowgrandgrandchild->getProjectNominal($rowproject->id,$arrProjectId,$request->branch,$request->start_date,$request->finish_date)['total_balance'];
								$total[$key] += $balance;
								
								$totalrow += $balance;
							}
							$html .= '
								<td class="text-right"><a href="javascript:void(0);" onclick="getCoaDetails('.$rowproject->id.','.$rowgrandgrandchild->id.','.$request->branch.',`'.$request->start_date.'`,`'.$request->finish_date.'`)">'.number_format($balance,2,',','.').'</a></td>
								<td class="text-center">'.($total_revenue_actual[$key] > 0 ? round($balance/$total_revenue_actual[$key] * 100,2) : 0).'%</td>
							';
						}
						
						$totalunallocatedcoa += $totalunallocated;
						$totalrow += $totalunallocated;
						$totalrowall += $totalrow;
							
						$html .= '
								<td class="text-right"><a href="javascript:void(0);" onclick="getCoaDetailsNonProject('.$rowgrandgrandchild->id.','.$request->branch.',`'.$request->start_date.'`,`'.$request->finish_date.'`)">'.number_format($totalunallocated,2,',','.').'</a></td>
								<td class="text-center">0</td>
								<td class="text-right"><a href="'.url("admin/report/accounting/ledger").'?coa_id='.$rowgrandgrandchild->id.'&start='.$request->start_date.'&end='.$request->finish_date.'&branch='.$request->branch.'" target="_blank">'.number_format($totalrow,2,',','.').'</a></td>
								<td class="text-center">'.($totalallrevenue > 0 ? round(($totalrow / $totalallrevenue) * 100,2) : 0).'%</td>
							</tr>
						';
					}
				}
			}
			
			//$total_unallocated_coa += $totalunallocatedcoa;
			
			$html .= '
				<tr class="font-weight-bold bg-brown-300" style="font-size:15px;">
					<td style="padding:7px;">Total '.$rowparent->name.'</td>
					';
			
			foreach($project as $key => $rowproject){
				$html .= '
					<td class="text-right">'.number_format($total[$key],2,',','.').'</td>
					<td class="text-center">'.($total_revenue_actual[$key] > 0 ? round($total[$key]/$total_revenue_actual[$key] * 100,2) : 0).'%</td>
				';
			}
			
			if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
				$total_revenue_unallocated += $totalunallocatedcoa;
			}
			
			if(substr($rowparent->code,0,5) == '5.000' || substr($rowparent->code,0,5) == '6.000' || substr($rowparent->code,0,5) == '6.100'){
				$total_cogs_unallocated += $totalunallocatedcoa;
			}
			
			if(substr($rowparent->code,0,5) == '6.200'){
				$total_fixed_cost_unallocated += $totalunallocatedcoa;
			}
			
			if(substr($rowparent->code,0,5) == '6.2100'){
				$total_variable_cost_unallocated += $totalunallocatedcoa;
			}
			
			if(substr($rowparent->code,0,6) == '6.2200'){
				$total_repair_expenses_unallocated += $totalunallocatedcoa;
			}
			
			if(substr($rowparent->code,0,5) == '6.300'){
				$total_depreciation_unallocated += $totalunallocatedcoa;
			}
			
			if(substr($rowparent->code,0,5) == '6.400'){
				$total_capex_unallocated += $totalunallocatedcoa;
			}
			
			if(substr($rowparent->code,0,5) == '7.100'){
				$total_other_income_unallocated += $totalunallocatedcoa;
			}
			
			if(substr($rowparent->code,0,5) == '7.200'){
				$total_other_deduction_unallocated += $totalunallocatedcoa;
			}
			
			$html .= '
					<td class="text-right">'.number_format($totalunallocatedcoa,2,',','.').'</td>
					<td class="text-center">0</td>
					<td class="text-right">'.number_format($totalrowall,2,',','.').'</td>
					<td class="text-center">'.($totalallrevenue > 0 ? round(($totalrowall / $totalallrevenue) * 100,2) : 0).'%</td>
				</tr>';
				
			$html .= '
				<tr class="font-weight-bold" style="font-size:15px;border-right: hidden !important;border-left: hidden !important;">
					<td colspan="'.((count($project) * 2) + 5).'">&nbsp;</td>
				</tr>
			';
		}
		
		$total_unallocated_coa = $total_revenue_unallocated - $total_cogs_unallocated - $total_fixed_cost_unallocated - $total_variable_cost_unallocated - $total_repair_expenses_unallocated - $total_depreciation_unallocated - $total_capex_unallocated + $total_other_income_unallocated - $total_other_deduction_unallocated;
		
		$html .= '
					</tbody>
					<tfoot style="border:1px solid black;">
						<tr class="bg-primary font-weight-bold" style="font-size:15px;border:1px solid black;">
							<td style="border:1px solid black;">Nett Profit (Loss)</td>
					';
					
					$totalall = 0;
					foreach($project as $key => $rowproject){
						$netprofit = $total_revenue_actual[$key] - $total_cogs_actual[$key] - $total_fixed_cost_actual[$key] - $total_variable_cost_actual[$key] - $total_other_expenses_actual[$key] - $total_repair_expenses_actual[$key] - $total_depreciation_actual[$key] - $total_capex_actual[$key] + $total_other_income_actual[$key] - $total_other_deduction_actual[$key];
						
						$html .= '
								<td style="border:1px solid black;" class="text-right">'.number_format($netprofit, 2, ',', '.').'</td>
								<td class="text-center" style="border:1px solid black;">'.($total_revenue_actual[$key] > 0 ? round($netprofit/$total_revenue_actual[$key] * 100,2) : 0).'%</td>';
								
								$totalall += $netprofit;
					}
		
		$html .= '			<td class="text-right" style="border:1px solid black;">'.number_format($total_unallocated_coa,2,',','.').'</td>
							<td class="text-center" style="border:1px solid black;">'.($total_revenue_unallocated > 0 ? round($total_unallocated_coa / $total_revenue_unallocated,2) : 0).'%</td>
							<td class="text-right" style="border:1px solid black;">'.number_format($totalall + $total_unallocated_coa,2,',','.').'</td>
							<td class="text-center" style="border:1px solid black;">'.($totalallrevenue > 0 ? round((($totalall + $total_unallocated_coa) / $totalallrevenue) * 100,2) : 0).'%</td>
						</tr>
					</tfoot>
				</table>
			</div>
		';
		
		return response()->json([
			'status'			=> 200,
            'result'            => $html
        ]);
	}
	
	public function getProjectJournal(Request $request){
		
		$coa = $request->coa_id;
		$project = $request->project_id;
		$branch = $request->branch;
		$start_date = $request->start_date;
		$finish_date = $request->finish_date;
		$balance = 0;
		$total_balance = 0;
		
		$data = CashBankDetail::whereHas('cashBank',function($query) use ($start_date,$finish_date){
			$query->whereRaw("(lookable_type = 'projects' OR lookable_type = 'project_deliveries' OR lookable_type = 'project_sale_returns') AND date BETWEEN '$start_date' AND '$finish_date'");
		})->where('coa_id',$coa)->where('branch',$branch)->get();
		
		$html = '';
		
		foreach($data as $row){
			$ada = false;
			if($row->cashBank->lookable_type == 'projects'){
				if($row->cashBank->lookable_id == $project){
					$ada = true;
				}
			}elseif($row->cashBank->lookable_type == 'project_deliveries'){
				$cek = NULL;
				$cek = ProjectDelivery::find($row->cashBank->lookable_id);
				if($cek && $cek->project_id == $project){
					$ada = true;
				}
			}elseif($row->cashBank->lookable_type == 'project_sale_returns'){
				$cek = NULL;
				$cek = ProjectSaleReturn::find($row->cashBank->lookable_id);
				if($cek && $cek->project_id == $project){
					$ada = true;
				}
			}
			
			if($ada == true){
				$html .= '<tr>
						<td>'.$row->cashBank->code.'</td>
						<td>'.$row->coa->name.'</td>
						<td>'.date('d M Y',strtotime($row->cashBank->date)).'</td>
						<td align="right">'.($row->type == '1' ? number_format($row->nominal,2,',','.') : 0).'</td>
						<td align="right">'.($row->type == '2' ? number_format($row->nominal,2,',','.') : 0).'</td>
						<td>'.$row->cashBank->description.' - '.$row->note.'</td>
					</tr>';
			}
		}
		
		
			
		return response()->json([
			'status'			=> 200,
            'result'            => $html
        ]);
	}
	
	public function getNonProjectJournal(Request $request){
		
		$coa = $request->coa_id;
		$branch = $request->branch;
		$start_date = $request->start_date;
		$finish_date = $request->finish_date;
		$balance = 0;
		$total_balance = 0;
		
		$data = CashBankDetail::whereHas('cashBank',function($query) use ($start_date,$finish_date){
			$query->whereRaw("date BETWEEN '$start_date' AND '$finish_date'");
		})->where('coa_id',$coa)->where('branch',$branch)->get();
		
		$project = Project::whereHas('projectSale', function($query) use ($request) {
						$query->whereHas('sales', function($query) use ($request){
							$query->where('branch',$request->branch);
						})->whereHas('projectDelivery', function($query) use ($request){
							$query->whereRaw('DATE(received_date) >= "'.$request->start_date.'" AND DATE(received_date) <= "'.$request->finish_date.'"');
						});
					})->orWhereHas('projectSaleReturn', function($query) use ($request){
						$query->whereHas('projectSale', function($query) use ($request) {
							$query->whereHas('sales', function($query) use ($request){
								$query->where('branch',$request->branch);
							});
						})->whereRaw('DATE(created_at) >= "'.$request->start_date.'" AND DATE(created_at) <= "'.$request->finish_date.'"');
					})->get();
		
		$html = '';
		
		$totaldebit = 0;
		$totalkredit = 0;
		
		foreach($data as $row){
			
			$ada = false;
			if($row->cashBank->lookable_type == 'projects'){
				
				foreach($project as $rowproject){
					if($row->cashBank->lookable_id == $rowproject->id){
						$ada = true;
					}
				}
				
			}elseif($row->cashBank->lookable_type == 'project_deliveries'){
				$cek = NULL;
				$cek = ProjectDelivery::find($row->cashBank->lookable_id);
				
				if($cek){
					foreach($project as $rowproject){
						if($cek->project_id == $rowproject->id){
							$ada = true;
						}
					}
				}
			}elseif($row->cashBank->lookable_type == 'project_sale_returns'){
				$cek = NULL;
				$cek = ProjectSaleReturn::find($row->cashBank->lookable_id);
				
				if($cek){
					foreach($project as $rowproject){
						if($cek->project_id == $rowproject->id){
							$ada = true;
						}
					}
				}
			}
			
			if($ada == false){
				$html .= '<tr>
						<td>'.$row->cashBank->code.'</td>
						<td>'.$row->coa->name.'</td>
						<td>'.date('d M Y',strtotime($row->cashBank->date)).'</td>
						<td align="right">'.($row->type == '1' ? number_format($row->nominal,2,',','.') : 0).'</td>
						<td align="right">'.($row->type == '2' ? number_format($row->nominal,2,',','.') : 0).'</td>
						<td>'.$row->cashBank->description.' - '.$row->note.'</td>
					</tr>';
				
				if($row->type == '1'){
					$totaldebit += $row->nominal;
				}else{
					$totalkredit += $row->nominal;
				}
			}
		}
		
		$html .= '<tr class="text-bold" style="font-size:25px;">
						<td colspan="3" align="right">TOTAL</td>
						<td align="right" colspan="2">'.number_format($totaldebit - $totalkredit,2,',','.').'</td>
						<td></td>
					</tr>';
			
		return response()->json([
			'status'			=> 200,
            'result'            => $html
        ]);
	}

	public function agingReceivableCard(Request $request){
		$branch = $request->branch ? $request->branch : 1;


		if($request->start_date && $request->finish_date) {
			$periode_delivery = "DATE(received_date) >= '$request->start_date' AND DATE(received_date) <= '$request->finish_date'";
			$periode_payment = "DATE(date) >= '$request->start_date' AND DATE(date) <= '$request->finish_date'";
			$periode_bill = "DATE(date) >= '$request->start_date' AND DATE(date) <= '$request->finish_date'";
			$periode_retur = "DATE(date_return) >= '$request->start_date' AND DATE(date_return) <= '$request->finish_date'";
			$periode_adjustment = "DATE(created_at) >= '$request->start_date' AND DATE(created_at) <= '$request->finish_date'";
		} else if($request->start_date) {
			$periode_delivery = "DATE(received_date) >= '$request->start_date' AND DATE(received_date) <= CURDATE()";
			$periode_payment = "DATE(date) >= '$request->start_date' AND DATE(date) <= CURDATE()";
			$periode_bill = "DATE(date_transaction) >= '$request->start_date' AND DATE(date_transaction) <= CURDATE()";
			$periode_retur = "DATE(date_return) >= '$request->start_date' AND DATE(date_return) <= CURDATE()";
			$periode_adjustment = "DATE(created_at) >= '$request->start_date' AND DATE(created_at) <= CURDATE()";
		} else if($request->finish_date) {
			$periode_delivery = "DATE(received_date) >= CURDATE() AND DATE(received_date) <= '$request->finish_date'";
			$periode_payment = "DATE(date) >= CURDATE() AND DATE(date) <= '$request->finish_date'";
			$periode_bill = "DATE(date) >= CURDATE() AND DATE(date) <= '$request->finish_date'";
			$periode_retur = "DATE(date_return) >= CURDATE() AND DATE(date_return) <= '$request->finish_date'";
			$periode_adjustment = "DATE(created_at) >= CURDATE() AND DATE(created_at) <= '$request->finish_date'";
		
		} else {
			$periode_delivery = "created_at IS NOT NULL";
			$periode_payment = "created_at IS NOT NULL";
			$periode_bill = "created_at IS NOT NULL";
			$periode_retur = "created_at IS NOT NULL";
			$periode_adjustment = "created_at IS NOT NULL";
			
		}

		$allproject = Project::whereHas('projectSale',function($query) use($request, $branch){
			$query->whereHas('sales',function($query) use($branch){
				$query->where('branch',$branch);
			})->where('customer_id', $request->id);
		})->get();
		
		$data = [];
		
		foreach($allproject as $row){
			$total_delivery = 0;
			$total_paid = 0;
			$total_customer_deposit = 0;
			$total_return = 0;
			$total_bill_unpaid = 0;

			//OPENING BALANCE 			
			$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->id)->get();
				
    		if(count($cb) > 0){
    			foreach($cb as $rowcb){
    				foreach($rowcb->cashBankDetail()->where('coa_id',67)->get() as $rowopb){
    					if($rowopb->type == '2'){
    							$data [] = [
            					'id'		  => $row->id,
            					'child_id'	  => $rowopb->cashBank->id,
            					'description' => $rowopb->cashBank->code.' - OPENING BALANCE CUSTOMER DEPOSIT',
            					'type'		  => 'credit',
            					'mode'		  => 'opening_balance',
            					'nominal'	  => $rowopb->nominal,
            					'date'   	  => $rowopb->cashBank->date,
            					'approval'    => '-'
            				];
    					}
    				}
    			}
    		}

			// NOMINAL TAGIHAN
			foreach ($row->projectDelivery()->whereRaw($periode_delivery)->whereNotNull('received_date')->where('is_sales','1')->get() as $rowpd) {
				$total_delivery += $rowpd->grandtotal_product + $rowpd->grandtotal_service;

				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowpd->id,
					'description' => $rowpd->proforma_code.' FROM -'.$rowpd->project->code,
					'type'		  => 'debit',
					'mode'		  => 'project_deliveries',
					'nominal'	  => $rowpd->grandtotal_product + $rowpd->grandtotal_service,
					'date'   	  => $rowpd->received_date,
					'approval'    => $rowpd->approve ? $rowpd->approve->name : '-'
				];
			}

			// RETUR
			foreach($row->projectSaleReturn()->whereRaw($periode_retur)->get() as $rowsr){
				$total_return += $rowsr->grandtotal;

				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowsr->id,
					'description' => $rowsr->code,
					'type'		  => 'credit',
					'mode'		  => 'project_sale_returns',
					'nominal'	  => $rowsr->grandtotal,
					'date'   	  => $rowsr->date_return,
					'approval'    => $rowsr->approve ? $rowsr->approve->name : '-',
				];
			}			


		
			// ADJUSTMENT
			foreach(CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->id)->whereRaw($periode_adjustment)->get() as $rowcb){
				foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowcb->id,
						'description' => $cbcb->cashBank->code.'-ADJUSTMENT',
						'type'		  => 'credit',
						'mode'		  => 'adjustment',
						'nominal'	  => $cbcb->nominal,
						'date'   	  => $cbcb->cashBank->created_at,
						'approval'    => '-',
					];
				}
			}

			
			foreach($row->projectPay()->whereRaw($periode_payment)->get() as $rowpay){
				$total_paid +=  $rowpay->nominal;
				// BARU
				// APABILA PEMBAYARAN MENGGUNAKAN CUSTOMER DEPOSIT (BUKAN CUSTOMER DEPOSIT ORANG LAIN) MAKA CUSTOMER DEPOSIT MASUK SEBAGAI DEBIT (AGAR CUSTOMER DEPOSIT BERKURANG)
				if($rowpay->coa_id == '67' && (!$rowpay->projectMainPayment->cashBank->customer_id ||$rowpay->projectMainPayment->cashBank->customer_id == 0)){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => 'CUSTOMER DEPPOSIT USED FOR PAYMENT- '.$rowpay->project->code,
						'type'		  => 'debit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}
				// APABILA KURANG BAYAR ATAU PAS(SESUAI) MAKA NOMINAL PEMBAYARAN MENGIKUTI NOMINAL YANG DIBAYARKAN (DI INPUTKAN USER), SEDANGKAN JIKA LEBIH BAYAR MAKA NOMINAL PEMBAYARAN DISESUAIKAN DENGAN NOMINAL TAGIHAN DAN MENJADI CUST DEPOSIT DI LOOPING BERIKUTNYA
				if($total_delivery - ($total_paid + $total_return) >= 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - PAYMENT FOR '.$rowpay->project->code,
						'type'		  => 'credit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}else if($total_delivery - ($total_paid + $total_return) < 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - PAYMENT FOR '.$rowpay->project->code,
						'type'		  => 'credit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal + ($total_delivery - ($total_paid + $total_return)),
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}
				
			}

			foreach($row->projectPay()->whereRaw($periode_payment)->get() as $rowpay){
				$total_customer_deposit += $rowpay->nominal;

				// lebih bayar jadi customer deposit
				if($total_delivery - ($total_customer_deposit + $total_return)  < 0 ){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - Customer Deposit',
						'type'		  => 'customer_deposit',
						'nominal'	  => $total_customer_deposit + $total_return  - $total_delivery,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-',
						'note'   	  => 'DOWN PAYMENT'
					];
				}
			}


			$total_unpaid_delivery = $total_delivery - $total_paid;
			foreach ($row->projectBill()->whereRaw($periode_bill)->get() as $rowbill){
				// JIKA ADA TAGIHAN BILL DAN TERBIT INVOICE (BELUM LUNAS) MAKA DIBUAT JURNAL UNTUK MELUNASI INVOICE DAN MERUBAH TAGIHAN BILL SEBAGAI TAGIHAN UTAMA UNTUK CUSTOMER
				if($total_unpaid_delivery - $rowbill->paidAR()['pays'] > 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowbill->id,
						'description' => $rowbill->code,
						'type'		  => 'credit',
						'mode'		  => 'project_bills',
						'nominal'	  => $total_unpaid_delivery,
						'date'   	  => $rowbill->date,
						'approval'    => $rowbill->approved ? $rowbill->approved->name : '-',
					];

					$total_unpaid_delivery  = 0;
				}

				if($rowbill->paidAR()['pays'] <= 0){
					$total_bill_unpaid += $rowbill->nominal + $rowbill->nominal_service;
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowbill->id,
						'description' => $rowbill->code,
						'type'		  => 'debit',
						'mode'		  => 'project_bills',
						'nominal'	  => ($rowbill->nominal + $rowbill->nominal_service),
						'date'   	  => $rowbill->date,
						'approval'    => $rowbill->approved ? $rowbill->approved->name : '-',
					];
				}
					
			}
		
		}

		// BARU
		// JIKA PEMBAYARAN MENGGUNAKAN CUSTOMER DEPOSIT ORANG(CUSTOMER) LAIN
		foreach (CashBank::whereHas('cashBankDetail',function($query) use($branch){ $query->where('coa_id',67)->where('branch',$branch); })->whereNotNull('customer_id')->where('customer_id', '!=', '0')->where('customer_id', $row->customer_id)->get() as $rowcb) {
			foreach($rowcb->cashBankDetail->where('coa_id',67)->where('type','1') as $rowcbdetail){
				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowcb->id,
					'description' => $rowcbdetail->cashBank->code.' CUSTOMER DEPOSIT - USED',
					'type'		  => 'debit',
					'mode'		  => 'customer_deposit',
					'nominal'	  => $rowcbdetail->nominal,
					'date'   	  => $rowcbdetail->cashBank->created_at,
					'approval'    => '-',
					'note'		  => 'CUSTOMER DEPOSIT FROM '.$rowcb->customer->name.' USED BY '. $rowcb->lookable->customer->name
				];
			}
		}

		$arrayResult = collect($data)->sortBy('id')->toArray();
		if(count($allproject) > 0){
			$dataView = [
				'title'				=> 'RECEIVABLE CARD Customer',
				'customer_name'		=> Customer::find($request->id)->name,
				'arrayResult'		=> $arrayResult,
			];

			return view('admin.pdf.report.accounting.aging_receivable_card', $dataView);
		}else{
			$data = [
				'status'  => 500,
				'message' => "Customer in this branch not found, try to change branch or customer"
			];

			return response()->json($data);
		}
		

		
	 
	}

	public function agingReceivableCardDetail(Request $request) 
    {
		$branch = $request->branch ? $request->branch : 1;

		$allproject = Project::whereHas('projectSale',function($query) use($request, $branch){
			$query->whereHas('sales',function($query) use($branch){
				$query->where('branch',$branch);
			})->where('customer_id', $request->id);
		})->get();
		
		$data = [];
		
		foreach($allproject as $row){
			$data[] = $row;
		}
		
		if(count($allproject) > 0){
			$data = [
				'title'				=> 'RECEIVABLE CARD Customer',
				'customer_name'		=> Customer::find($request->id)->name,
				'data'				=> $data
			];
			
			return view('admin.pdf.report.accounting.aging_receivable_card_detail', $data);
		}else{
			$data = [
				'status'  => 500,
				'message' => "Customer in this branch not found, try to change branch or customer"
			];

			return response()->json($data);
		}
		
	}

	public function UnpaidReceivableCard(Request $request){
		$branch = $request->branch ? $request->branch : 1;

		$allproject = Project::whereHas('projectSale',function($query) use($request, $branch){
			$query->whereHas('sales',function($query) use($branch){
				$query->where('branch',$branch);
			})->where('customer_id', $request->id);
		})
		->get();
		
		$data = [];
		
		foreach($allproject as $row){
			$data[] = $row;
		}

		
		
		if(count($allproject) > 0){
			$dataView = [
				'title'				=> 'UNPAID RECEIVABLE AND BILL CARD Customer',
				'customer_name'		=> Customer::find($request->id)->name,
				'data'		=> $data,
			];
	
			return view('admin.pdf.report.accounting.unpaid_receivable_card', $dataView);
		}else{
			$data = [
				'status'  => 500,
				'message' => "Customer in this branch not found, try to change branch or customer"
			];

			return response()->json($data);
		}
	}

	public function accountReceivableCustomer(){
		$data = [
			'title'				=> 'Account Receivable Customer',
			'content' 			=> 'admin.report.accounting.ar_customer'
		];
		
		return view('admin.layouts.index', ['data' => $data]);
	}

	public function datatableArCustomer(Request $request){
		$column = [
            'id',
            'name',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$branch = $request->branch ? $request->branch : 1;

        $total_data = Customer::where('type', 2)->whereHas('project')->count();
        
        $query_data = Customer::where(function($query) use ($search, $request, $branch) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }     

				$query->whereHas('project', function($query) use ($search, $request, $branch){
					$query->whereHas('projectSale',function($query) use($request, $branch){
						$query->whereHas('sales',function($query) use($branch){
							$query->where('branch',$branch);
						});
					});
				});
            })
			->whereHas('project')
            ->where('type', 2)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Customer::where(function($query) use ($search, $request, $branch) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }     

				$query->whereHas('project', function($query) use ($search, $request, $branch){
					$query->whereHas('projectSale',function($query) use($request, $branch){
						$query->whereHas('sales',function($query) use($branch){
							$query->where('branch',$branch);
						});
					});
				});
            })
			->where('type', 2)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;

			
            foreach($query_data as $val) {
				$btnAction = '<a href="' . url('admin/report/accounting/ar_customer/detail/' . $val->id.'?branch='.$branch.'') . '" target="_blank" class="btn bg-secondary" data-popup="tooltip" title="Details"><i class="icon-square-right"></i></a>
				<button class="btn bg-purple-400" onclick="showARCardsRealCustomer('.$val->id.','.$branch.' )" title="Print AR Card">
					<i class="icon-file-pdf"></i>
				</button>
				<button class="btn bg-pink-400" onclick="showARCardsCustomer('.$val->id.','.$branch.' )" title="Print AR Detail Card">
					<i class="icon-file-pdf"></i>
				</button>
				<button class="btn bg-teal-400" onclick="showUnpaidARCardsCustomer('.$val->id.','.$branch.' )" title="Print Unpaid AR Card">
					<i class="icon-file-pdf"></i>
				</button>
				';

				if($request->status == '1'){
					if($val->getReceivableCustomer() > 0){
						$response['data'][] = [
							$nomor,
							$val->name,
							number_format($val->getReceivableCustomer(),2,',','.'),
							$btnAction
						];

						$nomor++;
					}
				}else{
					if($val->getReceivableCustomer() >= 0){
						$response['data'][] = [
							$nomor,
							$val->name,
							number_format($val->getReceivableCustomer(),2,',','.'),
							$btnAction
						];
					}

					$nomor++;
				}
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
	}

	public function showARCustomerDetail(Request $request){
		$branch = $request->branch ? $request->branch : 1;
		$filter = $request->filter ? $request->filter : date('Y-m');

		if($request->start_date && $request->finish_date) {
			$periode_delivery = "DATE(received_date) >= '$request->start_date' AND DATE(received_date) <= '$request->finish_date'";
			$periode_payment = "DATE(date) >= '$request->start_date' AND DATE(date) <= '$request->finish_date'";
			$periode_bill = "DATE(date) >= '$request->start_date' AND DATE(date) <= '$request->finish_date'";
			$periode_retur = "DATE(date_return) >= '$request->start_date' AND DATE(date_return) <= '$request->finish_date'";
			$periode_adjustment = "DATE(created_at) >= '$request->start_date' AND DATE(created_at) <= '$request->finish_date'";
		} else if($request->start_date) {
			$periode_delivery = "DATE(received_date) >= '$request->start_date' AND DATE(received_date) <= CURDATE()";
			$periode_payment = "DATE(date) >= '$request->start_date' AND DATE(date) <= CURDATE()";
			$periode_bill = "DATE(date_transaction) >= '$request->start_date' AND DATE(date_transaction) <= CURDATE()";
			$periode_retur = "DATE(date_return) >= '$request->start_date' AND DATE(date_return) <= CURDATE()";
			$periode_adjustment = "DATE(created_at) >= '$request->start_date' AND DATE(created_at) <= CURDATE()";
		} else if($request->finish_date) {
			$periode_delivery = "DATE(received_date) >= CURDATE() AND DATE(received_date) <= '$request->finish_date'";
			$periode_payment = "DATE(date) >= CURDATE() AND DATE(date) <= '$request->finish_date'";
			$periode_bill = "DATE(date) >= CURDATE() AND DATE(date) <= '$request->finish_date'";
			$periode_retur = "DATE(date_return) >= CURDATE() AND DATE(date_return) <= '$request->finish_date'";
			$periode_adjustment = "DATE(created_at) >= CURDATE() AND DATE(created_at) <= '$request->finish_date'";
		
		} else {
			$periode_delivery = "created_at IS NOT NULL";
			$periode_payment = "created_at IS NOT NULL";
			$periode_bill = "created_at IS NOT NULL";
			$periode_retur = "created_at IS NOT NULL";
			$periode_adjustment = "created_at IS NOT NULL";
			
		}

		$allproject = Project::whereHas('projectSale',function($query) use($request, $branch){
			$query->whereHas('sales',function($query) use($branch){
				$query->where('branch',$branch);
			})->where('customer_id', $request->id);
		})->get();
		
		$data = [];
		
		foreach($allproject as $row){
			$total_delivery = 0;
			$total_paid = 0;
			$total_customer_deposit = 0;
			$total_return = 0;
			$total_bill_unpaid = 0;

            //OPENING BALANCE 			
			$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->id)->get();
				
    		if(count($cb) > 0){
    			foreach($cb as $rowcb){
    				foreach($rowcb->cashBankDetail()->where('coa_id',67)->get() as $rowopb){
    					if($rowopb->type == '2'){
    							$data [] = [
            					'id'		  => $row->id,
            					'child_id'	  => $rowopb->cashBank->id,
            					'description' => $rowopb->cashBank->code.' - OPENING BALANCE CUSTOMER DEPOSIT',
            					'type'		  => 'credit',
            					'mode'		  => 'opening_balance',
            					'nominal'	  => $rowopb->nominal,
            					'date'   	  => $rowopb->cashBank->date,
            					'approval'    => '-'
            				];
    					}
    				}
    			}
    		}

			// NOMINAL TAGIHAN
			foreach ($row->projectDelivery()->whereRaw($periode_delivery)->whereNotNull('received_date')->where('is_sales','1')->get() as $rowpd) {
				$total_delivery += $rowpd->grandtotal_product + $rowpd->grandtotal_service;

				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowpd->id,
					'description' => $rowpd->proforma_code.' FROM -'.$rowpd->project->code,
					'type'		  => 'debit',
					'mode'		  => 'project_deliveries',
					'nominal'	  => $rowpd->grandtotal_product + $rowpd->grandtotal_service,
					'date'   	  => $rowpd->received_date,
					'approval'    => $rowpd->approve ? $rowpd->approve->name : '-'
				];
			}

			// RETUR
			foreach($row->projectSaleReturn()->whereRaw($periode_retur)->get() as $rowsr){
				$total_return += $rowsr->grandtotal;

				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowsr->id,
					'description' => $rowsr->code,
					'type'		  => 'credit',
					'mode'		  => 'project_sale_returns',
					'nominal'	  => $rowsr->grandtotal,
					'date'   	  => $rowsr->date_return,
					'approval'    => $rowsr->approve ? $rowsr->approve->name : '-',
				];
			}			


		
			// ADJUSTMENT
			foreach(CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->id)->whereRaw($periode_adjustment)->get() as $rowcb){
				foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowcb->id,
						'description' => $cbcb->cashBank->code.'-ADJUSTMENT',
						'type'		  => 'credit',
						'mode'		  => 'adjustment',
						'nominal'	  => $cbcb->nominal,
						'date'   	  => $cbcb->cashBank->created_at,
						'approval'    => '-',
					];
				}
			}

			
			foreach($row->projectPay()->whereRaw($periode_payment)->get() as $rowpay){
				$total_paid +=  $rowpay->nominal;
				// BARU
				// APABILA PEMBAYARAN MENGGUNAKAN CUSTOMER DEPOSIT (BUKAN CUSTOMER DEPOSIT ORANG LAIN) MAKA CUSTOMER DEPOSIT MASUK SEBAGAI DEBIT (AGAR CUSTOMER DEPOSIT BERKURANG)
				if($rowpay->coa_id == '67' && (!$rowpay->projectMainPayment->cashBank->customer_id ||$rowpay->projectMainPayment->cashBank->customer_id == 0)){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => 'CUSTOMER DEPPOSIT USED FOR PAYMENT- '.$rowpay->project->code,
						'type'		  => 'debit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}
				// APABILA KURANG BAYAR ATAU PAS(SESUAI) MAKA NOMINAL PEMBAYARAN MENGIKUTI NOMINAL YANG DIBAYARKAN (DI INPUTKAN USER), SEDANGKAN JIKA LEBIH BAYAR MAKA NOMINAL PEMBAYARAN DISESUAIKAN DENGAN NOMINAL TAGIHAN DAN MENJADI CUST DEPOSIT DI LOOPING BERIKUTNYA
				if($total_delivery - ($total_paid + $total_return) >= 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - PAYMENT FOR '.$rowpay->project->code,
						'type'		  => 'credit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}else if($total_delivery - ($total_paid + $total_return) < 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - PAYMENT FOR '.$rowpay->project->code,
						'type'		  => 'credit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal + ($total_delivery - ($total_paid + $total_return)),
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}
				
			}

			foreach($row->projectPay()->whereRaw($periode_payment)->get() as $rowpay){
				$total_customer_deposit += $rowpay->nominal;

				// lebih bayar jadi customer deposit
				if($total_delivery - ($total_customer_deposit + $total_return)  < 0 ){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - Customer Deposit',
						'type'		  => 'customer_deposit',
						'nominal'	  => $total_customer_deposit + $total_return  - $total_delivery,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-',
						'note'   	  => 'DOWN PAYMENT'
					];
				}
			}


			$total_unpaid_delivery = $total_delivery - $total_paid;
			foreach ($row->projectBill()->whereRaw($periode_bill)->get() as $rowbill){
				// JIKA ADA TAGIHAN BILL DAN TERBIT INVOICE (BELUM LUNAS) MAKA DIBUAT JURNAL UNTUK MELUNASI INVOICE DAN MERUBAH TAGIHAN BILL SEBAGAI TAGIHAN UTAMA UNTUK CUSTOMER
				if($total_unpaid_delivery - $rowbill->paidAR()['pays'] > 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowbill->id,
						'description' => $rowbill->code,
						'type'		  => 'credit',
						'mode'		  => 'project_bills',
						'nominal'	  => $total_unpaid_delivery,
						'date'   	  => $rowbill->date,
						'approval'    => $rowbill->approved ? $rowbill->approved->name : '-',
					];

					$total_unpaid_delivery  = 0;
				}

				if($rowbill->paidAR()['pays'] <= 0){
					$total_bill_unpaid += $rowbill->nominal + $rowbill->nominal_service;
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowbill->id,
						'description' => $rowbill->code,
						'type'		  => 'debit',
						'mode'		  => 'project_bills',
						'nominal'	  => ($rowbill->nominal + $rowbill->nominal_service),
						'date'   	  => $rowbill->date,
						'approval'    => $rowbill->approved ? $rowbill->approved->name : '-',
					];
				}
					
			}
		
		}

		// BARU
		// JIKA PEMBAYARAN MENGGUNAKAN CUSTOMER DEPOSIT ORANG(CUSTOMER) LAIN
		foreach (CashBank::whereHas('cashBankDetail',function($query) use($branch){ $query->where('coa_id',67)->where('branch',$branch); })->whereNotNull('customer_id')->where('customer_id', '!=', '0')->where('customer_id', $row->customer_id)->get() as $rowcb) {
			foreach($rowcb->cashBankDetail->where('coa_id',67)->where('type','1') as $rowcbdetail){
				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowcb->id,
					'description' => $rowcbdetail->cashBank->code.' CUSTOMER DEPOSIT - USED',
					'type'		  => 'debit',
					'mode'		  => 'customer_deposit',
					'nominal'	  => $rowcbdetail->nominal,
					'date'   	  => $rowcbdetail->cashBank->created_at,
					'approval'    => '-',
					'note'		  => 'CUSTOMER DEPOSIT FROM '.$rowcb->customer->name.' USED BY '. $rowcb->lookable->customer->name
				];
			}
		}

		$arrayResult = collect($data)->sortBy('id')->toArray();
		
			$dataView = [
				'title'			 => 'RECEIVABLE CARD Customer',
				'customer_name'	 => Customer::find($request->id)->name,
				'customer_id'	 => Customer::find($request->id)->id,
				'branch'	 	 => $branch,
				'arrayResult'	 => $arrayResult,
				'filter'		 => $filter,
				'filter_start'	 => $request->start_date ? $request->start_date : '',
				'filter_finish'	 => $request->finish_date ? $request->finish_date : '',
				'content'		 => 'admin.report.accounting.ar_customer_detail'
			];

			return view('admin.layouts.index', ['data' => $dataView]);
		
	}


	public function showDetailProduct(Request $request)
    {

		$mode = $request->mode;
        $query = $mode == "project_deliveries" ? projectDelivery::where('id', $request->id)->first() : ProjectBill::where('id', $request->id)->first();
        $arrProduct = [];
        $data = [];
        $totalqtybox = 0;
        $totalqtytile = 0;
        $total = 0;
        $totaltile = 0;

        if ($mode == "project_deliveries") {
            foreach ($query->projectDeliveryProduct as $val) {
                $arrProduct[] = [
                    'product_id'   => $val->product_id,
                    'product_name' => $val->product->name(),
                    'qty'          => $val->qty,
                    'price'        => $val->salePrice()
                ];

            }

			$data = [
				'response'	 	=> $arrProduct,
				'letter_way' 	=>url('admin/report/project/details/print/delivery_order/'.base64_encode($query->id)),
				'invoice'    	=>url('admin/report/project/details/print/sales_proforma/'.base64_encode($query->id)),
				'invoice_other' =>url('admin/report/project/details/print/sales_proforma_other/'.base64_encode($query->id))
			];
        }else {
            foreach ($query->project->projectProduct as $pp) {
                if($pp->unit == '2' || $pp->unit == '3'){
                    $price = $pp->best_price > 0 ? $pp->best_price : ($pp->recommended_price > 0 ? $pp->recommended_price : $pp->price);
                    $m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
                    
                    if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
                        $countbox = ceil($pp->qty);
                        $total += $price * ($countbox);
                        $totaltile += $price * ($countbox);
                    }else{
                        if($m2 < 1.1 && date('Y-m',strtotime($pp->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
                            $countbox = ceil($pp->qty);
                            $total += $price * ($countbox);
                            $totaltile += $price * ($countbox);
                        }else{
                            $countbox = ceil(round($pp->qty / $m2,2));
                            $total += $price * $m2 * ($countbox);
                            $totaltile += $price * $m2 * ($countbox);
                        }
                    }
                    
                    $totalqtybox += $countbox;
                    $totalqtytile += $pp->qty;
                    

                    $arrProduct[] = [
                        'product_id'   => $pp->product_id,
                        'product_name' => $pp->product->name(),
                        'qty'          => $countbox,
                        'price'        => number_format($price, 0, ',', '.')
                    ];
                }else{
                    $price = $pp->best_price > 0 ? $pp->best_price : ($pp->recommended_price > 0 ? $pp->recommended_price : $pp->price);
                    
                    $arrProduct[] = [
                        'product_id'   => $pp->product_id,
                        'product_name' => $pp->product->name(),
                        'qty'          => $pp->qty,
                        'price'        => number_format($price, 0, ',', '.')
                    ];
                }
            }
			
			$data = [
				'response'	 	=> $arrProduct,
				'letter_way' 	=>'',
				'invoice'    	=>'',
				'invoice_other' =>''
			];
        }

        return response()->json($data);
    }

	public function showDetailPayment(Request $request)
    {
		$mode = $request->mode;
        $query = $mode == "project_pays" ? ProjectPay::where('id', $request->id)->first() : ($mode == 'project_sale_returns' ? ProjectSaleReturn::where('id', $request->id)->first() : ProjectBill::where('id', $request->id)->first());
        $data = [];

        if ($mode == "project_pays") {  
			$data = [
				'proof' 	=> $query->image(),
				'nominal' 	=> number_format($query->nominal, 2, ',','.'),
				'coa_name'  => $query->coa->name
			];
        }else if($mode == "project_sale_returns"){
			foreach ($query->projectSaleReturnProduct as $pp) {
				$arrProduct [] = [
					'product_id'   => $pp->product_id,
					'product_name' => $pp->product->name(),
					'qty'          => $pp->qty,
					'price'        => $pp->salePrice()
				];
			}
			$data = [
				'proof' 	=> $query->image(),
				'data' 		=> $arrProduct,
				'nominal' 	=> number_format($query->getTotal(), 2, ',','.'),
			];
		}else{
			$data = [
				'proof' 	=>'',
				'nominal' 	=> number_format($query->nominal +  $query->nominal_service, 2, ',','.'),
				'coa_name'  =>'DOWN PAYMENT FROM BILL '.$query->code
			];
        }

        return response()->json($data);
    }

	public function export(Request $request, $param){
		$filter = $request->filter ? $request->filter : date('Y-m');
		$branch = $request->branch ? $request->branch : '1';
		$type = $request->type ? $request->type : '';

		if($param == 'ar'){
			$projectsale = ProjectSale::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('projectDelivery', function($query) use ($filter) {
				$query->whereRaw("LEFT(received_date, 7) <= '$filter'");
			})->get();
			
			foreach($projectsale as $key => $row){
				$totaldelivered = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totalcb = 0;
				$totalbill = 0;
				
				foreach($row->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->whereRaw("LEFT(DATE(received_date), 7) <= '$filter'")->get() as $rd){
					$totaldelivered += round($rd->grandtotal_product + $rd->grandtotal_service);
				}
				
				foreach($row->projectSaleReturn()->whereRaw("LEFT(DATE(date_return), 7) <= '$filter'")->get() as $rsr){
					$totalreturn += round($rsr->grandtotal);
				}
				
				foreach($row->projectSalePay()->whereRaw("LEFT(DATE(date), 7) <= '$filter' AND ((project_bill_id IS NOT NULL AND project_delivery_id IS NOT NULL) OR (project_bill_id IS NULL OR project_bill_id = 0))")->get() as $rsp){
					if(isset($rsp->projectBill) && $rsp->projectBill->journal()){
						if(($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal == 0){
							$totalpay -=  ($rsp->projectBill->nominal + $rsp->projectBill->nominal_service) - $rsp->nominal;
						}else{
							$totalpay += $rsp->nominal;
						}
					}else{
						$totalpay += $rsp->nominal;
					}
				}
				
				foreach($row->project->projectBill()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $pb){
					if($pb->journal()){
						$totalbill += $pb->nominal + $pb->nominal_service;
					}
				}
				
				$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->project->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
							if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}

						foreach($rowcb->cashBankDetail()->where('coa_id',67)->get() as $cbcb){
							if($cbcb->type == '2'){
								$totalcb += $cbcb->nominal;
							}
						}
					}
				}
				
				if(round(($totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill)) > 0){
					$row['totalbalance'] = $totaldelivered - $totalreturn - $totalpay - $totalcb - $totalbill;
					$sale[] = $row;
				}
			}
			
			$projectbill = ProjectBill::where('branch',$branch)
			->whereRaw("LEFT(date, 7) <= '$filter'")
			->get();
			
			foreach($projectbill as $key => $row){
				$already_bill = false;
				$already_sent = false;
				
				$cek = CashBank::where('lookable_type','project_bills')->where('lookable_id',$row->id)->whereRaw("LEFT(date, 7) <= '$filter'")->first();
				
				if(!$cek){
					$already_bill = true;
				}
				
				if($already_bill == false && $row->balancePeriod($filter) > 0){
					$bill[] = $row;
				}
			}
			
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
					$query->whereNotNull('customer_id')
					->where('customer_id','<>','0')
					->whereRaw("LEFT(date, 7) <= '$filter'");
				})
				->where('coa_id',27)
				->where('branch',$branch)
				->where('type','1')
				->orderBy('created_at','asc')
				->get();


			$data = [
				'title'   			=> 'Outstanding A/R',
				'filter'  			=> $filter,
				'branch'			=> $branch,
				'projectsale' 		=> $sale,
				'projectbill'		=> $bill,
				'other'				=> $other,
			];
		
			return Excel::download(new ExportAccountReceivable($data), 'outstanding_ar.xlsx');

		}else if($param == 'ap'){

			$projectpurchase = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('projectWarehouse', function($query) use ($filter) {
				$query->whereRaw("LEFT(DATE(date_receive), 7) <= '$filter'");
			})->whereDoesntHave('purchaseCost')
			->orderBy('created_at','asc')
			->get();
			
			foreach($projectpurchase as $row){
				$totalreceived = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totaltransfer = 0;
				$totalcb = 0;
				$totalpc = 0;
				
				foreach($row->projectWarehouse()->whereRaw("LEFT(DATE(date_receive),7) <= '$filter'")->get() as $pw){
					$totalreceived += $pw->grandtotal;
				}
				
				foreach($row->projectPurchaseReturn()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsr){
					if(!$rsr->journalDownPayment()){
						$totalreturn += round($rsr->getTotal());
					}
				}
				
				foreach($row->projectPurchasePayment()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsp){
					$totalpay += $rsp->nominal;
				}
				
				$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$row->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
							if($cbcb->type == '1'){
								$totalcb += $cbcb->nominal;
							}
						}
					}
				}
				
				$pc = PurchaseCost::where('project_purchase_id',$row->id)->first();
				
				if($pc){
					$totalpc += $pc->totalCost();
				}
				
				if(round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc)) > 0){
					$row['totalbalance'] = round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc));
					$arrPurchase[] = $row;
				}
			}

		
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
				$query
				->whereRaw("LEFT(date, 7) <= '$filter'")
				->whereNotNull('supplier_id')
				->where('supplier_id','<>','0')
				->orWhere('code', 'like', '%BPC-%');
			})
			->where('coa_id',332)
			->where('type','2')
			->where('branch',$branch)
			->orderBy('created_at','asc')
			->get();
			

			$arrOther = [];
			
			foreach($other as $row){
					$total = $row->nominal;
					
					$cekdata = $this->checkData($arrOther,$row->cash_bank_id);
					
					if($cekdata >= 0){
						$total += $arrOther[$cekdata]['nominal'];
						
						if(!str_contains($row->cashBank->code, 'BPC-')){
							foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rowpay){
								if(count(explode('-',$rowpay->code)) > 3){
									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter){ $query->where('code',$row->cashBank->code)->whereRaw("LEFT(date, 7) <= '$filter'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
										if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
											foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
												$total -= $rowdetail->nominal;
											}
										}
									}
								}
							}
						}
						
						if($total > 0){
							$arrOther[$cekdata]['nominal'] = $total;
						}
					}else{
						if(!str_contains($row->cashBank->code, 'BPC-')){
							foreach(PurchaseRequest::where('link_type','fee_pta')->where('link_id',$row->cashBank->id)->get() as $rowpay){
								$total -= $rowpay->totalPaymentPeriod($filter);
							}
							
							foreach(CashBank::where('code','like',$row->cashBank->code.'%')->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rowpay){
								if(count(explode('-',$rowpay->code)) > 3){
									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter){ $query->where('code',$row->cashBank->code)->whereRaw("LEFT(date, 7) <= '$filter'"); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
										if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
											foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
												$total -= $rowdetail->nominal;
											}
										}
									}
								}
							}
							
							if($row->cashBank->lookable_type == 'projects'){
								foreach(Project::find($row->cashBank->lookable_id)->projectSaleReturn as $rowreturn){
									foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$filter,$rowreturn){ 
										$query->where('lookable_type','project_sale_returns')->where('lookable_id',$rowreturn->id)->whereRaw("LEFT(date, 7) <= '$filter'"); 
									})->where('coa_id',332)->where('type','1')->where('branch',$branch)->get() as $rowcek){
										$total -= $rowcek->nominal;
									}
								}
							}
						}
						
						if($total > 0){
							$row['nominal'] = $total;
							$arrOther[] = $row;
						}
					}
			
			}


			$samplepurchase = SamplePurchase::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})->whereHas('sampleWarehouse', function($query) use ($filter) {
				$query->whereRaw("LEFT(DATE(date_receive), 7) <= '$filter'");
			})
			->orderBy('created_at','asc')
			->get();

			
			foreach($samplepurchase as $row){
				$totalreceivedsample = 0;
				$totalreturnsample = 0;
				$totalpaysample = 0;
				$totalcbsample = 0;
				
				foreach($row->sampleWarehouse()->whereRaw("LEFT(DATE(date_receive),7) <= '$filter'")->get() as $pw){
					$totalreceivedsample += $pw->grandtotal;
				}
				
				foreach($row->samplePurchaseReturn()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsr){
						$totalreturnsample += round($rsr->getTotal());
				}
				
				// foreach($row->samplePurchasePayment()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsp){
				// 	$totalpaysample += $rsp->nominal;
				// }
				
				$cb = CashBank::where('lookable_type','sample_purchases')->where('lookable_id',$row->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
				
				if(count($cb) > 0){
					foreach($cb as $rowcb){
						foreach($rowcb->cashBankDetail()->where('coa_id',332)->get() as $cbcb){
							if($cbcb->type == '1'){
								$totalcbsample += $cbcb->nominal;
							}
						}
					}
				}
				
				if(round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample)) > 0){
					$row['totalbalance'] = round(($totalreceivedsample - $totalreturnsample - $totalpaysample - $totalcbsample));
					$arrPurchaseSample[] = $row;
				}
			}

			$data = [
				'title'   			=> 'Outstanding A/P',
				'filter'  			=> $filter,
				'branch'			=> $branch,
				'projectpurchase' 	=> $arrPurchase,
				'samplepurchase' 	=> $arrPurchaseSample,
				'other'				=> $arrOther,
			];
		
			return Excel::download(new ExportAccountPayable($data), 'outstanding_ap.xlsx');
            
		}else{
			$other = CashBankDetail::whereHas('cashBank', function($query) use($filter){
					$query->whereRaw("LEFT(DATE(date), 7) <= '$filter'");
				})
				->where('coa_id',$type)
				->where('type','2')
				->where('branch',$branch)
				->orderBy('created_at','asc')
				->get();
			
			$arrTempReturn = [];
			$arrTempReturnNominal = [];
			$arrAlreadyPaid = [];
			
			foreach($other as $row){
				$total = $row->nominal;
				$totalpay = 0;
				
				$arrpayment = [];
				
				if(str_contains($row->cashBank->code, 'PR-')){
					
					$payment = PurchaseRequestPayment::where('purchase_request_id',explode('-',$row->cashBank->code)[1])->whereRaw("LEFT(DATE(date_paid), 7) <= '$filter'")->get();
					
					$pr = PurchaseRequest::find(explode('-',$row->cashBank->code)[1]);
					
					if($pr){
						
						if($pr->link_type == 'project_deliveries'){
						
							if($pr->link_type == 'project_sales'){
								$projectsale = ProjectSale::find($pr->link_id);
							}elseif($pr->link_type == 'project_deliveries'){
								$projectdelivery = ProjectDelivery::find($pr->link_id);
								$projectsale = $projectdelivery->projectSale;
							}
							
							if($projectsale){
								
								foreach($projectsale->projectSaleReturn()->whereRaw("LEFT(DATE(date_return), 7) <= '$filter'")->get() as $rowsr){
									if(in_array($rowsr->id,$arrTempReturn) && !in_array(1,$arrTempReturn)){
									
									}else{
										$cb = CashBankDetail::where('coa_id',$type)->where('type','1')->whereHas('cashBank',function($query) use($rowsr){
											$query->where('lookable_id',$rowsr->id)->where('lookable_type','project_sale_returns');
										})->get();
										
										if($cb){
										
											foreach($cb as $rowcb){
												
												$arrpayment[] = [
													'code'			=> $rowcb->cashBank->code,
													'date'			=> $rowcb->cashBank->date,
													'description'	=> $rowcb->cashBank->description.' - SO '.$projectsale->code.' - PJ '.$projectsale->project->code,
													'nominal'		=> $rowcb->nominal
												];
												
												$totalpay += $rowcb->nominal;
											}
											
											$arrTempReturn[] = $rowsr->id;
										}
									}
								}
							}
						}
					}
					
					if($payment){
						foreach($payment as $rowpay){
							if(!in_array($rowpay->id, $arrAlreadyPaid)){
								if($rowpay->purchase_request_main_payment_id){
									$cb = CashBank::where('code','PRMP-'.$rowpay->purchase_request_main_payment_id)->get();
									
									if($cb){
										$arrpayment[] = [
											'code'			=> 'PRMP-'.$rowpay->purchase_request_main_payment_id,
											'date'			=> $rowpay->date_paid,
											'description'	=> $rowpay->note,
											'nominal'		=> $rowpay->nominal
										];
										
										$totalpay += $rowpay->nominal;
									}
								}else{
									$cb = CashBankDetail::whereHas('cashBank',function($query) use($branch,$filter,$rowpay){
										$query->where('code','PRP-'.$rowpay->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'");
									})->where('coa_id',$type)->where('type','1')->get();
									
									if($cb){
										foreach($cb as $rowcb){
											$arrpayment[] = [
												'code'			=> $rowcb->cashBank->code,
												'date'			=> $rowcb->cashBank->date,
												'description'	=> $rowcb->cashBank->description,
												'nominal'		=> $rowcb->nominal
											];
											
											$totalpay += $rowcb->nominal;
										}
									}
								}

								$arrAlreadyPaid [] = $rowpay->id;
							}
						}
					}

				}else{
					$projectsale = null;

					if($row->cashBank->lookable_type == 'project_deliveries'){
						$projectdelivery = ProjectDelivery::find($row->cashBank->lookable_id);
						$projectsale = $projectdelivery->projectSale;
					}


					if($projectsale) {
						$pr = PurchaseRequest::where('link_type', 'project_sales')->where('link_id', $projectsale->id)->first();

						if ($pr) {
							foreach ($projectsale->projectSaleReturn()->whereRaw("LEFT(DATE(date_return), 7) <= '$filter'")->get() as $rowsr) {
								if(in_array($rowsr->id, $arrTempReturn)) {
								}else{
									$cb = CashBankDetail::where('coa_id', $type)->where('type', '1')->whereHas('cashBank', function ($query) use ($rowsr) {
										$query->where('lookable_id', $rowsr->id)->where('lookable_type', 'project_sale_returns');
									})->get();

									if ($cb) {

										foreach ($cb as $rowcb) {

											$arrpayment[] = [
												'code'			=> $rowcb->cashBank->code,
												'date'			=> $rowcb->cashBank->date,
												'description'	=> $rowcb->cashBank->description . ' - SO ' . $projectsale->code . ' - PJ ' . $projectsale->project->code,
												'nominal'		=> $rowcb->nominal
											];

											$totalpay += $rowcb->nominal;
										}
									}

									$arrTempReturn[] = $rowsr->id;
								}
							}
						}

						$payment = PurchaseRequestPayment::where('purchase_request_id', $pr->id)->whereRaw("LEFT(DATE(date_paid), 7) <= '$filter'")->get();

						if($payment) {
							foreach ($payment as $rowpay) {
								if(!in_array($rowpay->id, $arrAlreadyPaid)){
									if($rowpay->purchase_request_main_payment_id) {
										$cb = CashBank::where('code', 'PRMP-' . $rowpay->purchase_request_main_payment_id)->get();
	
										if ($cb) {
											$arrpayment[] = [
												'code'			=> 'PRMP-' . $rowpay->purchase_request_main_payment_id,
												'date'			=> $rowpay->date_paid,
												'description'	=> $rowpay->note,
												'nominal'		=> $rowpay->nominal
											];
	
											$totalpay += $rowpay->nominal;
										}
									}else{
										$cb = CashBankDetail::whereHas('cashBank',function($query) use($branch,$filter,$rowpay){
											$query->where('code','PRP-'.$rowpay->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'");
										})->where('coa_id',$type)->where('type','1')->get();
	
										if ($cb) {
											foreach ($cb as $rowcb) {
												$arrpayment[] = [
													'code'			=> $rowcb->cashBank->code,
													'date'			=> $rowcb->cashBank->date,
													'description'	=> $rowcb->cashBank->description,
													'nominal'		=> $rowcb->nominal
												];
	
												$totalpay += $rowcb->nominal;
											}
										}
									}

									$arrAlreadyPaid [] = $rowpay->id;
								}
							}
						}
					}
				}
					
				if(str_contains($row->cashBank->code, 'RJCT')){
					$whereRaw = strlen($filter) == 7 ? "LEFT(date, 7) <= '$filter'" : "date <= '$filter'";
					$isMultipleReject = explode("-",$row->cashBank->code);
					$length_of_last_code = isset($isMultipleReject[3]) ? strlen($isMultipleReject[3]) : 0;
	
					$cek_rjct = isset($isMultipleReject[3]) ? CashBank::where('code','like',substr($row->cashBank->code, 0, -$length_of_last_code).'CLOSE-%'.$isMultipleReject[3])->whereRaw($whereRaw)->first() : CashBank::where('code','like', $row->cashBank->code.'-CLOSE%')->whereRaw($whereRaw)->first() ;


					$arrpayment[] = [
						'code'			=> $row->cashBank->code,
						'date'			=> $row->cashBank->date_paid,
						'description'	=> $row->cashBank->description,
						'nominal'		=> $row->nominal
					];
					

					$totalpay = $cek_rjct ? $cek_rjct->cashBankDetail->whereIn('coa_id', [342, 82, 83, 84, 85, 86, 87, 88, 281])->where('type', 1)->first()->nominal : 0;
					
				}
		
				$row['nominal'] = $total;
				$row['balance'] = $total - $totalpay;
				$row['arrpayment'] = $arrpayment;
				$row['description'] = isset($projectsale) ? 'SO '.$projectsale->code.' - PJ '.$projectsale->project->code : '';
				$arrPayable[] = $row;
			}

			$data = [
				'title'   			=> 'Outstanding A/P Other',
				'filter'  			=> $filter,
				'branch'			=> $branch,
				'type'				=> $type,
				'arrPayable'		=> $arrPayable,
				'arrTempReturn'		=> $arrTempReturnNominal,
				'content' 			=> 'admin.report.finance.outstanding_a_p_other'
			];

			return Excel::download(new ExportApOther($data), 'outstanding_ap_other.xlsx');	
		}
	}

	public function cashFlow(){
		
		// $categoryMap = [
		// 	'1.100' => ['increase_in_receivable', 'decrease_in_receivable'],
		// 	'1.200' => ['increase_in_inventory', 'decrease_in_inventory'],
		// 	'1.300' => ['increase_in_inventory', 'decrease_in_inventory'],
		// 	'1.400' => ['increase_in_inventory', 'decrease_in_inventory'],
		// 	'1.500' => ['increase_in_other_current_assets', 'decrease_in_other_current_assets'],
		// 	'1.600' => ['increase_in_fixed_assets', 'decrease_in_fixed_assets'],
		// 	'1.610' => ['increase_in_accumulated_depreciation', 'decrease_in_accumulated_depreciation'],
		// 	'2.000' => ['increase_in_other_current_assets', 'decrease_in_other_current_assets'],
		// 	'2.100' => ['increase_in_other_current_assets', 'decrease_in_other_current_assets'],
		// 	'2.200.01' => ['increase_in_payable', 'decrease_in_payable'],
		// 	'2.200' => ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 	'2.300' => ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 	'2.400' => ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 	'2.500' => ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 	'3.000' => ['increase_in_equity', 'decrease_in_equity'],
		// 	'3.300.00' => ['retained_earning', 'decrease_in_net_income']
		// ];
		
	
		// // $coaCode = substr('1.610', 0, 8);
		// // dd(str_contains($coaCode, array_keys($categoryMap)[6]));
		// dd(	$categoryMap['1.100'][1]);
		
		// $test = [];
		// $coa = Coa::all();
		// foreach ($coa as $key => $value) {
		// 	$test['test'][] = $value->id;
		// }

		// dd($test['test'][2]);
			
		// $coas = Coa::orderBy('code')->get();
		// $idCoa = [];

		// foreach ($coas as $coa) {
		
		// 	$categoryMap = [
		// 		'1.100' 	=> ['increase_in_receivable', 'decrease_in_receivable'],
		// 		'1.200' 	=> ['increase_in_inventory', 'decrease_in_inventory'],
		// 		'1.202' 	=> ['increase_in_inventory', 'decrease_in_inventory'],
		// 		'1.210' 	=> ['increase_in_inventory', 'decrease_in_inventory'],
		// 		'1.300' 	=> ['increase_in_inventory', 'decrease_in_inventory'],
		// 		'1.400' 	=> ['increase_in_other_current_assets', 'decrease_in_other_current_assets'],
		// 		'1.500' 	=> ['increase_in_other_current_assets', 'decrease_in_other_current_assets'],
		// 		'1.600' 	=> ['increase_in_fixed_assets', 'decrease_in_fixed_assets'],
		// 		'1.610' 	=> ['increase_in_accumulated_depreciation', 'decrease_in_accumulated_depreciation'],
		// 		'2.000' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.100' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.200.01' 	=> ['increase_in_payable', 'decrease_in_payable'],
		// 		'2.200' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.210' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.211' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.212' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.213' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.300' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.400' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'2.500' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
		// 		'3.000' 	=> ['increase_in_equity', 'decrease_in_equity'],
		// 		'3.100' 	=> ['increase_in_equity', 'decrease_in_equity'],
		// 		'3.300.00' 	=> ['retained_earning', 'decrease_in_net_income'],
		// 		'3.300.01' 	=> ['retained_earning', 'decrease_in_net_income']
		// 	];
			
		// 	$coaCode = substr($coa->code, 0, 8);
		// 	$code = null;

		// 	foreach (array_keys($categoryMap) as $key) {
		// 		if (str_contains($coaCode, $key)) {
		// 			$code = $key;
		// 			break;
		// 		}
		// 	}

		// 	if ($code != null) {
		// 		$category = $categoryMap[$code];
		// 		if ($code == '3.300.00') {
					
		// 		}else{
		// 			$idCoa[$category[0]][] = $coa->id;
		// 			$idCoa[$category[1]][] = $coa->id;
		// 		}
		// 	}
		// }

		// // dd($idCoa);

		// $ids = $idCoa['increase_in_receivable'];
		// $listId = array();

		// foreach ($ids as $id) {
		// 	array_push($listId, $id);
		// }

		// // dd($listId);

		// $test = Coa::whereIn('id', $listId)->get();
		// dd($test);
		$data = [
			'title'    => 'Report Cash Flow',
			'content'  => 'admin.report.accounting.cashflow'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}

	public function generateCashFlow(Request $request){
		$branch = $request->input('branch', 1);

		$filter_start_date = $request->input('start_date', date('Y-m-d'));
		$filter_end_date =  $request->input('finish_date', date('Y-m-d'));	
		
		$result = Coa::getCashFlow($branch, $filter_start_date, $filter_end_date);

		$response = [
			'status'  => 200,
			'data'    => $result,
		];
		

		return $response;
	}

	public function getDetailCashFlow(Request $request){
		$branch = $request->branch;
		$start_date = $request->start_date;
		$end_date = $request->finish_date;
		$ids = $this->generateCashFlow($request)['data']['list_of_coas_id'][$request->type];
		$listId = array();

		foreach ($ids as $id) {
			array_push($listId, $id);
		}

		$coas = Coa::where('status', 1)->whereIn('id', $listId)->get();
		$coaList = [];

		foreach ($coas as $coa) {
			$coaList[] = [
				'id' 	  => $coa->id,
				'code' 	  => $coa->code,
				'name'    => $coa->name,
				'nominal' => str_contains($request->type,'increase') ? $coa->getCashFlowDetail($branch, $start_date, $end_date)['current_credit'] : $coa->getCashFlowDetail($branch, $start_date, $end_date)['current_debit'],
			];
		}

		$response = [
			'status'   => 200,
			'message'  => 'Success',
			'coa_list' => $coaList,
		];

		return $response;
	}
}
