<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
#use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Coa extends Model {

    #use HasFactory, SoftDeletes;
	use HasFactory;

    protected $table      = 'coas';
    protected $primaryKey = 'id';
    #protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'code',
        'name',
        'parent_id',
        'status'
    ];

    public function status() 
    {
        switch($this->status) {
            case '1':
                $status = '<span class="text-success font-weight-bold">Active</span>';
                break;
            case '2':
                $status = '<span class="text-danger font-weight-bold">Not Active</span>';
                break;
            default:
                $status = '<span class="text-warning font-weight-bold">Invalid</span>';
                break;
        }

        return $status;
    }

    public function parent()
    {
        $query = Coa::find($this->parent_id);
		if($query){
			return $query;
		}else{
			return null;
		}
    }
	
	public function child()
    {
        $query = Coa::where('parent_id',$this->id)->orderBy('code')->get();
        return $query;
    }

    public function journalDebit()
    {
        return $this->hasMany('App\Models\Journal');
    }

    public function journalCredit()
    {
        return $this->hasMany('App\Models\Journal');
    }

    public function budgeting()
    {
        return $this->hasMany('App\Models\Budgeting');
    }

	public function checkBudget($month)
	{
		$query = Budgeting::where('coa_id',$this->id)->where('month',$month)->first();
		
		if($query){
			return number_format($query->nominal,2,',','.');
		}else{
			return 0;
		}
	}
	

	
	
	
	public function checkTotalRetainedBefore($filter,$branch){
		
		$where_raw = "LEFT(date_transaction, 7) < '$filter'";
		
		$balance_credit = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereHasMorph('journalable',
							[CashBank::class],
							function (Builder $query) {
								$query->where('code','like',"RETAINED-%");
							})->whereRaw($where_raw)->sum('nominal');
		
		return $balance_credit;
	}
	
	public function getProjectNominal($project_id,$arrProjectId,$branch,$start_date,$finish_date){
		
		$rows = CashBankDetail::where('coa_id', $this->id)->where('branch',$branch)->whereHas('cashBank', function($query) use ($start_date,$finish_date){ 
				$query->whereRaw('DATE(date) >= "'.$start_date.'" AND DATE(date) <= "'.$finish_date.'"');
			})->get();
		
		$balance = 0;
		$balanceunproject = 0;
		$total_balance = 0;
		$total_unproject = 0;
		$total_before = 0;
		
		foreach($rows as $row){
			if($row->cashBank->lookable_type == 'project_deliveries'){
				$cek = NULL;
				$cek = ProjectDelivery::find($row->cashBank->lookable_id);
				if($cek && $cek->project_id == $project_id){
					if($row->type == '1'){
						$balance += $row->nominal;
					}elseif($row->type == '2'){
						$balance -= $row->nominal;
					}
				}
				
				if(!in_array($row->cashBank->lookable->project_id,$arrProjectId)){
					if($row->type == '1'){
						//$balanceunproject += $row->nominal;
					}elseif($row->type == '2'){
						//$balanceunproject -= $row->nominal;
					}
				}
			}elseif($row->cashBank->lookable_type == 'projects'){
				if($row->cashBank->lookable_id == $project_id){
					if($row->type == '1'){
						$balance += $row->nominal;
					}elseif($row->type == '2'){
						$balance -= $row->nominal;
					}
				}
				
				if(!in_array($row->cashBank->lookable_id,$arrProjectId)){
					if($row->type == '1'){
						$balanceunproject += $row->nominal;
					}elseif($row->type == '2'){
						$balanceunproject -= $row->nominal;
					}
				}
			}elseif($row->cashBank->lookable_type == 'project_warehouses'){
				
			}elseif($row->cashBank->lookable_type == 'project_sale_returns'){
				$cek = NULL;
				$cek = ProjectSaleReturn::find($row->cashBank->lookable_id);
				if($cek && $cek->project_id == $project_id){
					if($row->type == '1'){
						$balance += $row->nominal;
					}elseif($row->type == '2'){
						$balance -= $row->nominal;
					}
				}
				
				if(!in_array($row->cashBank->lookable->project_id,$arrProjectId)){
					if($row->type == '1'){
						//$balanceunproject += $row->nominal;
					}elseif($row->type == '2'){
						//$balanceunproject -= $row->nominal;
					}
				}
			}else{
				if($row->type == '1'){
					$balanceunproject += $row->nominal;
				}elseif($row->type == '2'){
					$balanceunproject -= $row->nominal;
				}
			}
			
			$arr = ['5','6'];
		
			if(in_array(substr($this->code,0,1),$arr) || substr($this->code,0,4) == '7.200'){
				$total_balance         = $balance;
				$total_unproject	   = $balanceunproject;
			}else{
				$total_balance         = $balance > 0 ? ($balance) * -1 : abs($balance);
				$total_unproject	   = $balanceunproject > 0 ? ($balanceunproject) * -1 : abs($balanceunproject);
			}
		}
		
		$arr = [
			'total_balance' 		=> $total_balance,
			'total_unproject'		=> $total_unproject
		];
		
		return $arr;
	}
	
	public function getProjectCoa($project_id,$branch){
		
		$rows = CashBankDetail::where('coa_id', $this->id)->where('branch',$branch)->whereHas('cashBank')->get();
		
		$balance = 0;
		$total_balance = 0;
		
		foreach($rows as $row){
			if($row->cashBank->lookable_type == 'project_deliveries'){
				$cek = NULL;
				$cek = ProjectDelivery::find($row->cashBank->lookable_id);
				if($cek && $cek->project_id == $project_id){
					if($row->type == '1'){
						$balance += $row->nominal;
					}elseif($row->type == '2'){
						$balance -= $row->nominal;
					}
				}
			}elseif($row->cashBank->lookable_type == 'projects'){
				if($row->cashBank->lookable_id == $project_id){
					if($row->type == '1'){
						$balance += $row->nominal;
					}elseif($row->type == '2'){
						$balance -= $row->nominal;
					}
				}
			}elseif($row->cashBank->lookable_type == 'project_warehouses'){
				
			}elseif($row->cashBank->lookable_type == 'project_sale_returns'){
				$cek = NULL;
				$cek = ProjectSaleReturn::find($row->cashBank->lookable_id);
				if($cek && $cek->project_id == $project_id){
					if($row->type == '1'){
						$balance += $row->nominal;
					}elseif($row->type == '2'){
						$balance -= $row->nominal;
					}
				}
			}
			
			$arr = ['5','6'];
		
			if(in_array(substr($this->code,0,1),$arr) || substr($this->code,0,4) == '7.200'){
				$total_balance         = $balance;
			}else{
				$total_balance         = $balance > 0 ? ($balance) * -1 : abs($balance);
			}
		}
		
		$arr = [
			'total_balance' 		=> $total_balance,
		];
		
		return $arr;
	}


	public function checkTotal($filter, $branch) {
		$whereClause = "";
		
		if (strlen($filter) == 7) {
			$whereClause = "LEFT(date_transaction, 7) <= '$filter'";
		} elseif (strlen($filter) == 10) {
			$whereClause = "DATE(date_transaction) <= '$filter'";
		}
		
		// Check if balances are cached
		$cacheKey = "balances_{$this->id}_{$filter}_{$branch}";
		$cachedBalances = Cache::get($cacheKey);
		if ($cachedBalances) {
			return $cachedBalances;
		}
		
		if ($branch) {
			$balanceQuery = Journal::where('coa_id', $this->id)
								   ->whereRaw($whereClause)
								   ->where(function ($query) use ($branch) {
									   $query->where('branch', $branch)
											 ->orWhereNull('branch'); // Consider cases where branch is not specified
								   })
								   ->selectRaw('SUM(CASE WHEN type = 1 THEN nominal ELSE 0 END) AS balance_debit')
								   ->selectRaw('SUM(CASE WHEN type = 2 THEN nominal ELSE 0 END) AS balance_credit')
								   ->first();
		} else {
			$balanceQuery = Journal::where('coa_id', $this->id)
								   ->whereRaw($whereClause)
								   ->selectRaw('SUM(CASE WHEN type = 1 THEN nominal ELSE 0 END) AS balance_debit')
								   ->selectRaw('SUM(CASE WHEN type = 2 THEN nominal ELSE 0 END) AS balance_credit')
								   ->first();
		}
		
		$balance_debit = $balanceQuery->balance_debit ?? 0;
		$balance_credit = $balanceQuery->balance_credit ?? 0;
		
		$arr = ['1', '5', '6'];
		$codePrefix = substr($this->code, 0, 1);
		
		if (in_array($codePrefix, $arr) || substr($this->code, 0, 4) == '7.200') {
			$total_balance = $balance_debit - $balance_credit;
		} else {
			$total_balance = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
		}
		
		// Cache the computed balances
		Cache::put($cacheKey, $total_balance, 60); // Adjust expiry time as needed
		
		return $total_balance;
	}

	// public function checkTotal($filter,$branch){
		
	// 	if(strlen($filter) == 7){
	// 		$where_raw = "LEFT(date_transaction, 7) <= '$filter'";
	// 	}elseif(strlen($filter) == 10){
	// 		$where_raw = "DATE(date_transaction) <= '$filter'";
	// 	}
		
	// 	if($branch){
	// 		$balance_debit         = Journal::where('type','1')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
	// 		$balance_credit        = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
	// 	}else{
	// 		$balance_debit         = Journal::where('type','1')->where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
	// 		$balance_credit        = Journal::where('type','2')->where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
	// 	}
		
		
	// 	$arr = ['1','5','6'];
		
	// 	if(in_array(substr($this->code,0,1),$arr) || substr($this->code,0,4) == '7.200'){
	// 		$total_balance         = $balance_debit - $balance_credit;
	// 	}else{
	// 		$total_balance         = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
	// 	}
	// 	return $total_balance;
	// }

	public function checkTotalPL($filter, $branch) {
		$where_raw = '';
	
		if (strlen($filter) == 7) {
			$month = date('m', strtotime($filter));
			$year = date('Y', strtotime($filter));
			$where_raw = "YEAR(date_transaction) = '$year' AND MONTH(date_transaction) = '$month'";
		} elseif (strlen($filter) == 10) {
			$startDate = substr($filter, 0, 7) . '-01';
			$where_raw = "DATE(date_transaction) BETWEEN '$startDate' AND '$filter'";
		}
	
		if ($branch) {
			$balanceQuery = Journal::where('coa_id', $this->id)
								   ->whereRaw($where_raw)
								   ->where(function ($query) use ($branch) {
									   $query->where('branch', $branch)
											 ->orWhereNull('branch'); // Consider cases where branch is not specified
								   })
								   ->selectRaw('SUM(CASE WHEN type = 1 THEN nominal ELSE 0 END) AS balance_debit')
								   ->selectRaw('SUM(CASE WHEN type = 2 THEN nominal ELSE 0 END) AS balance_credit')
								   ->first();
		} else {
			$balanceQuery = Journal::where('coa_id', $this->id)
								   ->whereRaw($where_raw)
								   ->selectRaw('SUM(CASE WHEN type = 1 THEN nominal ELSE 0 END) AS balance_debit')
								   ->selectRaw('SUM(CASE WHEN type = 2 THEN nominal ELSE 0 END) AS balance_credit')
								   ->first();
		}
		
		$balance_debit = $balanceQuery->balance_debit ?? 0;
		$balance_credit = $balanceQuery->balance_credit ?? 0;
	
		$arr = ['1', '5', '6'];
		$codePrefix = substr($this->code, 0, 1);
		
		if (in_array($codePrefix, $arr) || substr($this->code, 0, 4) == '7.200') {
			$total_balance = $balance_debit - $balance_credit;
		} else {
			$total_balance = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
		}
	
		return $total_balance;
	}
	
	
	
	// public function checkTotalPL($filter,$branch){
		
	// 	if(strlen($filter) == 7){
	// 		$month     = date('m', strtotime($filter));
	// 		$year      = date('Y', strtotime($filter));
	// 		$where_raw = "YEAR(date_transaction) = '$year' AND MONTH(date_transaction) = '$month'";
	// 	}elseif(strlen($filter) == 10){
	// 		$startDate = substr($filter,0,7).'-01';
	// 		$where_raw = "DATE(date_transaction) BETWEEN '$startDate' AND '$filter'";
	// 	}
		
	// 	if($branch){
	// 		$balance_debit         = Journal::where('type','1')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
	// 		$balance_credit        = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
	// 	}else{
	// 		$balance_debit         = Journal::where('type','1')->where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
	// 		$balance_credit        = Journal::where('type','2')->where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
	// 	}
		
		
	// 	$arr = ['1','5','6'];
		
	// 	if(in_array(substr($this->code,0,1),$arr) || substr($this->code,0,4) == '7.200'){
	// 		$total_balance         = $balance_debit - $balance_credit;
	// 	}else{
	// 		$total_balance         = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
	// 	}
	// 	return $total_balance;
	// }
	
	public function checkTotalPLDate($date_start,$date_end,$branch){
		
		$where_raw = "date_transaction BETWEEN '$date_start' AND '$date_end'";
		$where_previous = "date_transaction < '$date_start'";
		
		if($branch){
			$balance_debit         = Journal::where('type','1')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
			$balance_credit        = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
		}else{
			$balance_debit         = Journal::where('type','1')->where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
			$balance_credit        = Journal::where('type','2')->where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
		}
		
		
		$arr = ['1','5','6'];
		
		if(in_array(substr($this->code,0,1),$arr) || substr($this->code,0,4) == '7.200'){
			$total_balance         = $balance_debit - $balance_credit;
		}else{
			$total_balance         = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
		}
		return $total_balance;
	}

	public function checkTotalPLDateCF($date_start,$date_end,$branch){
		
		$where_raw = "date_transaction BETWEEN '$date_start' AND '$date_end'";
		$where_previous = "date_transaction < '$date_start'";
		
		if($branch){
			$balance_debit         = Journal::where('type','1')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
			$balance_credit        = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');

			$balance_debit_previous  = Journal::where('type','1')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_previous)->sum('nominal');
			$balance_credit_previous = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_previous)->sum('nominal');
		}else{
			$balance_debit         = Journal::where('type','1')->where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
			$balance_credit        = Journal::where('type','2')->where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
			
			$balance_debit_previous  = Journal::where('type','1')->where('coa_id', $this->id)->whereRaw($where_previous)->sum('nominal');
			$balance_credit_previous = Journal::where('type','2')->where('coa_id', $this->id)->whereRaw($where_previous)->sum('nominal');
		}
		
		
		$arr = ['1','5','6'];
		
		if(in_array(substr($this->code,0,1),$arr) || substr($this->code,0,4) == '7.200'){
			$total_balance         = $balance_debit - $balance_credit;
			$total_balance_previous = $balance_debit_previous - $balance_credit_previous;
		}else{
			$total_balance         = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
			$total_balance_previous = $balance_debit_previous - $balance_credit_previous > 0 ? ($balance_debit_previous - $balance_credit_previous) * -1 : abs($balance_debit_previous - $balance_credit_previous);
		}
		return [
			'total_balance' 		 => $total_balance,
			'total_balance_previous' => $total_balance_previous,
		];
	}
	
	public function checkTotalBudgeting($filter,$branch){
		$where_raw = "month = '$filter'";
		
		if($branch){
			$balance = Budgeting::where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
		}else{
			$balance = Budgeting::where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
		}
		
		$total_balance = $balance;
		
		return $total_balance;
	}
	
	public function checkTotalBudgetingYearly($year,$branch){

		$balance = Budgeting::where('coa_id', $this->id)->where('branch',$branch)->whereRaw("LEFT(month,4) = '$year'")->sum('nominal');
		
		return $balance;
	}
	
	public function checkTotalBudgetingRemaining($filter,$branch){
		
		$yearstart = explode('-',$filter)[0];
		$monthstart = explode('-',$filter)[1];
		
		$balance = 0;
		
		for($i = intval($monthstart);$i<13;$i++){
			$filter = $yearstart.'-'.str_pad($i, 2, '0', STR_PAD_LEFT);
			$where_raw = "month = '$filter'";
			if($branch){
				$balance += Budgeting::where('coa_id', $this->id)->where('branch',$branch)->whereRaw($where_raw)->sum('nominal');
			}else{
				$balance += Budgeting::where('coa_id', $this->id)->whereRaw($where_raw)->sum('nominal');
			
			}
		}
		
		$total_balance = $balance;
		
		return $total_balance;
	}
	
	function getBalanceCashBank($branch,$start_date,$finish_date){
		$total = 0;
		
		
		$totalBefore = 0;
		
		if($start_date){
			$totalInBefore = BalanceHistory::where(function($query) use ($start_date) {
				if($start_date) {
					$query->whereDate('date', '<', $start_date);
				}
			})
			->where('coa_id',$this->id)->where('type','IN')->where('branch',$branch)->sum('nominal');
			
			$totalOutBefore = BalanceHistory::where(function($query) use ($start_date) {
				if($start_date) {
					$query->whereDate('date', '<', $start_date);
				}
			})
			->where('coa_id',$this->id)->where('type','OUT')->where('branch',$branch)->sum('nominal');
			
			$datapaymentBefore = PurchaseRequestPayment::where(function($query) use ($start_date) {
				if($start_date) {
					$query->whereDate('date_paid', '<', $start_date);
				}
			})
			->where('coa_id',$this->id)->where('branch',$branch)->get();
			
			$totalPurchaseBefore = 0;
			
			foreach($datapaymentBefore as $row){
				if(!$row->cekCB() && $row->purchaseRequest->link_type !== 'project_purchases' && $row->purchaseRequest->status == 'DONE' && !$row->purchase_request_main_payment_id){
					$totalPurchaseBefore += $row->nominal; 
				}
			}
			
			$totalBefore = $totalInBefore - $totalOutBefore - $totalPurchaseBefore;
		}
		
		$totalIn = BalanceHistory::where(function($query) use ($start_date, $finish_date) {
			if($start_date && $finish_date) {
				$query->whereDate('date', '>=', $start_date)
					->whereDate('date', '<=', $finish_date);
			}
		})
		->where('coa_id',$this->id)->where('type','IN')->where('branch',$branch)->sum('nominal');
		
		$totalOut = BalanceHistory::where(function($query) use ($start_date, $finish_date) {
			if($start_date && $finish_date) {
				$query->whereDate('date', '>=', $start_date)
					->whereDate('date', '<=', $finish_date);
			}
		})
		->where('coa_id',$this->id)->where('type','OUT')->where('branch',$branch)->sum('nominal');
		
		$datapayment = PurchaseRequestPayment::where(function($query) use ($start_date, $finish_date) {
			if($start_date && $finish_date) {
				$query->whereDate('date_paid', '>=', $start_date)
					->whereDate('date_paid', '<=', $finish_date);
			}
		})
		->where('coa_id',$this->id)->where('branch',$branch)->get();
		
		$totalPurchase = 0;
		
		foreach($datapayment as $row){
			if(!$row->cekCB() && $row->purchaseRequest->link_type !== 'project_purchases' && $row->purchaseRequest->status == 'DONE' && !$row->purchase_request_main_payment_id){
				$totalPurchase += $row->nominal; 
			}
		}
		
		$total = $totalIn - $totalOut - $totalPurchase + $totalBefore;
		
		return $total;
	}
	
	function getBalanceCashBankReal($branch,$start_date,$finish_date){
		$total = 0;
		
		
		$totalBefore = 0;
		
		if($start_date){
			$totalInBefore = BalanceHistory::where(function($query) use ($start_date) {
				if($start_date) {
					$query->whereDate('date', '<', $start_date);
				}
			})
			->where('coa_id',$this->id)->where('type','IN')->where('branch',$branch)->sum('nominal');
			
			$totalOutBefore = BalanceHistory::where(function($query) use ($start_date) {
				if($start_date) {
					$query->whereDate('date', '<', $start_date);
				}
			})
			->where('coa_id',$this->id)->where('type','OUT')->where('branch',$branch)->sum('nominal');
			
			$datapaymentBefore = PurchaseRequestPayment::where(function($query) use ($start_date) {
				if($start_date) {
					$query->whereDate('date_paid', '<', $start_date);
				}
			})
			->where('coa_id',$this->id)->where('branch',$branch)->get();
			
			$totalPurchaseBefore = 0;
			
			foreach($datapaymentBefore as $row){
				if(!$row->cekCB() && $row->purchaseRequest->link_type !== 'project_purchases' && !$row->purchase_request_main_payment_id){
					$totalPurchaseBefore += $row->nominal; 
				}
			}
			
			$totalBefore = $totalInBefore - $totalOutBefore - $totalPurchaseBefore;
		}
		
		$totalIn = BalanceHistory::where(function($query) use ($start_date, $finish_date) {
			if($start_date && $finish_date) {
				$query->whereDate('date', '>=', $start_date)
					->whereDate('date', '<=', $finish_date);
			}
		})
		->where('coa_id',$this->id)->where('type','IN')->where('branch',$branch)->sum('nominal');
		
		$totalOut = BalanceHistory::where(function($query) use ($start_date, $finish_date) {
			if($start_date && $finish_date) {
				$query->whereDate('date', '>=', $start_date)
					->whereDate('date', '<=', $finish_date);
			}
		})
		->where('coa_id',$this->id)->where('type','OUT')->where('branch',$branch)->sum('nominal');
		
		$datapayment = PurchaseRequestPayment::where(function($query) use ($start_date, $finish_date) {
			if($start_date && $finish_date) {
				$query->whereDate('date_paid', '>=', $start_date)
					->whereDate('date_paid', '<=', $finish_date);
			}
		})
		->where('coa_id',$this->id)->where('branch',$branch)->get();
		
		$totalPurchase = 0;
		
		foreach($datapayment as $row){
			if(!$row->cekCB() && $row->purchaseRequest->link_type !== 'project_purchases' && !$row->purchase_request_main_payment_id){
				$totalPurchase += $row->nominal; 
			}
		}
		
		$total = $totalIn - $totalOut - $totalPurchase + $totalBefore;
		
		return $total;
	}
	
	function getBalanceCashBankRealBefore($branch,$date){
		$total = 0;
		
		$totalBefore = 0;
		
		$totalInBefore = BalanceHistory::where(function($query) use ($date) {
			if($date) {
				$query->whereDate('date', '<', $date);
			}
		})
		->where('coa_id',$this->id)->where('type','IN')->where('branch',$branch)->sum('nominal');
		
		$totalOutBefore = BalanceHistory::where(function($query) use ($date) {
			if($date) {
				$query->whereDate('date', '<', $date);
			}
		})
		->where('coa_id',$this->id)->where('type','OUT')->where('branch',$branch)->sum('nominal');
		
		$datapaymentBefore = PurchaseRequestPayment::where(function($query) use ($date) {
			if($date) {
				$query->whereDate('date_paid', '<', $date);
			}
		})
		->where('coa_id',$this->id)->where('branch',$branch)->get();
		
		$totalPurchaseBefore = 0;
		
		foreach($datapaymentBefore as $row){
			if(!$row->cekCB() && !$row->purchase_request_main_payment_id){
				$totalPurchaseBefore += $row->nominal; 
			}
		}
		
		$totalBefore = $totalInBefore - $totalOutBefore - $totalPurchaseBefore;
		
		return $totalBefore;
	}
	
	public static function getNewCodeInventory()
	{
		$query = Coa::selectRaw("RIGHT(code, 3) as code")
			->where('code','like',"1.210.%")
            ->orderByRaw('RIGHT(code, 3) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '001';
        }

        $code = '1.210.'.str_pad($number, 3, 0, STR_PAD_LEFT);
        return $code;
	}
	
	public function nominalPurchaseRequest($branch){
		$total = 0;
		
		$arr = [];
		
		if($this->id == 332){
			$projectpurchase = ProjectPurchase::whereHas('sales', function($query) use ($branch) {
				$query->where('branch',$branch);
			})
			->whereHas('projectWarehouse')
			->whereDoesntHave('purchaseCost')
			->orderBy('created_at','asc')
			->get();
			
			foreach($projectpurchase as $row){
				$totalreceived = 0;
				$totalreturn = 0;
				$totalpay = 0;
				$totaltransfer = 0;
				$totalcb = 0;
				$totalpc = 0;
				
				foreach($row->projectWarehouse as $pw){
					$totalreceived += $pw->grandtotal;
				}
				
				foreach($row->projectPurchaseReturn as $rsr){
					$totalreturn += round($rsr->getTotal());
				}
				
				foreach($row->projectPurchasePayment as $rsp){
					$totalpay += $rsp->nominal;
				}
				
				$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$row->id)->get();
				
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
					$total += round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc));
					
					$arr[] = [
						'code'			=> $row->code,
						'description'	=> '',
						'total'			=> round(($totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc))
					];
				}
			}
			
			$other = CashBankDetail::whereHas('cashBank', function($query){
					$query->whereNotNull('supplier_id')
					->where('supplier_id','<>','0');
				})
				->where('coa_id',332)
				->where('type','2')
				->where('branch',$branch)
				->orderBy('created_at','asc')
				->get();
			
			$arrOther = [];
			
			foreach($other as $row){
				if(!str_contains($row->cashBank->code, 'RJCT')){
				
					$rowtotal = $row->nominal;
					
					$cekdata = $this->checkData($arrOther,$row->cash_bank_id);
					
					if($cekdata >= 0){
						$rowtotal += $arrOther[$cekdata]['nominal'];
						
						foreach(CashBank::where('code','like',$row->cashBank->code.'%')->get() as $rowpay){
							if(count(explode('-',$rowpay->code)) > 3){
								foreach(CashBankDetail::whereHas('cashBank',function($query) use($row){ $query->where('code',$row->cashBank->code); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
									if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
										foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
											$rowtotal -= $rowdetail->nominal;
										}
									}
								}
							}
						}
						
						if($rowtotal > 0){
							$arrOther[$cekdata]['nominal'] = $rowtotal;
						}
					}else{
						foreach(PurchaseRequest::where('link_type','fee_pta')->where('link_id',$row->cashBank->id)->get() as $rowpay){
							$rowtotal -= $rowpay->totalPayment();
						}
						
						foreach(CashBank::where('code','like',$row->cashBank->code.'%')->get() as $rowpay){
							if(count(explode('-',$rowpay->code)) > 3){
								foreach(CashBankDetail::whereHas('cashBank',function($query) use($row){ $query->where('code',$row->cashBank->code); })->where('coa_id',332)->where('type','2')->get() as $key => $rowcek){
									if((intval(explode('-',$rowpay->code)[3]) - 1) == $key && $rowcek->id == $row->id){
										foreach($rowpay->cashBankDetail->where('coa_id',332)->where('type','1') as $rowdetail){
											$rowtotal -= $rowdetail->nominal;
										}
									}
								}
							}
						}
						
						if($row->cashBank->lookable_type == 'projects'){
							foreach(Project::find($row->cashBank->lookable_id)->projectSaleReturn as $rowreturn){
								foreach(CashBankDetail::whereHas('cashBank',function($query) use($row,$rowreturn){ 
									$query->where('lookable_type','project_sale_returns')->where('lookable_id',$rowreturn->id); 
								})->where('coa_id',332)->where('type','1')->where('branch',$branch)->get() as $rowcek){
									$rowtotal -= $rowcek->nominal;
								}
							}
						}
						
						if($rowtotal > 0){
							$row['nominal'] = $rowtotal;
							$arrOther[] = $row;
						}
					}
				}
			}
			
			foreach($arrOther as $row){
				if($row->cashBank->lookable_type == 'purchase_requests' || $row->cashBank->lookable_type == 'projects'){
					$cek = NULL;
					if($row->cashBank->lookable_type == 'purchase_requests'){
						$cek = PurchaseRequest::find($row->cashBank->lookable_id);
					}elseif($row->cashBank->lookable_type == 'projects'){
						$cek = PurchaseRequest::find(explode('-',$row->cashBank->code)[1]);
					}
					$totalpay = $cek ? $cek->totalPayment() : 0;
					$sisa = $row->nominal - $totalpay;
					if($sisa > 0){
						$total += $sisa;
						
						$arr[] = [
							'code'			=> $row->cashBank->code,
							'description'	=> $row->cashBank->description,
							'total'			=> $sisa
						];
					}
				}else{
					if($row->cashBank->lookable_type !== 'project_warehouses'){
						$total += $row->nominal;
						$arr[] = [
							'code'			=> $row->cashBank->code,
							'description'	=> $row->cashBank->description,
							'total'			=> $row->nominal
						];
					}
				}
			}
		}else{
			$other = CashBankDetail::whereHas('cashBank')
				->where('coa_id',$this->id)
				->where('type','2')
				->where('branch',$branch)
				->orderBy('created_at','asc')
				->get();
			
			$arrTempReturn = [];
			$arrTempReturnNominal = [];
			
			foreach($other as $row){
				if(!str_contains($row->cashBank->code, 'RJCT')){
				
					$rowtotal = $row->nominal;
					$totalpay = 0;
					
					$arrpayment = [];
					
					if(str_contains($row->cashBank->code, 'PR-')){
						
						$payment = PurchaseRequestPayment::where('purchase_request_id',explode('-',$row->cashBank->code)[1])->get();
						
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
									
									foreach($projectsale->projectSaleReturn as $rowsr){
										if(in_array($rowsr->id,$arrTempReturn) && !in_array(1,$arrTempReturn)){
										
										}else{
											$cb = CashBankDetail::where('coa_id',$this->id)->where('type','1')->whereHas('cashBank',function($query) use($rowsr){
												$query->where('lookable_id',$rowsr->id)->where('lookable_type','project_sale_returns');
											})->get();
											
											if($cb){
											
												foreach($cb as $rowcb){
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
								if($rowpay->purchase_request_main_payment_id){
									$cb = CashBank::where('code','PRMP-'.$rowpay->purchase_request_main_payment_id)->get();
									
									if($cb){
										$totalpay += $rowpay->nominal;
									}
								}else{
									$cb = CashBankDetail::whereHas('cashBank',function($query) use($branch,$rowpay){
										$query->where('code','PRP-'.$rowpay->id);
									})->where('coa_id',$this->id)->where('type','1')->get();
									
									if($cb){
										foreach($cb as $rowcb){
											$totalpay += $rowcb->nominal;
										}
									}
								}
							}
						}
					}
					
					if(($rowtotal - $totalpay) > 0){
						$total += $rowtotal - $totalpay;
						$arr[] = [
							'code'			=> $row->cashBank->code,
							'description'	=> $row->cashBank->description,
							'total'			=> $rowtotal - $totalpay
						];
					}
				}
			}
		}
		
		$result = [
			'total'	=> $total,
			'data'	=> $arr
		];
		
		return $result;
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


	public function getCashFlowDetail($branch, $filter_start_date, $filter_end_date){
		$whereRaw = "DATE(date_transaction) BETWEEN '$filter_start_date' AND '$filter_end_date'";

		$balance_debit = Journal::where('type','1')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($whereRaw)->sum('nominal');
		$balance_credit = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($whereRaw)->sum('nominal');

		$arrResult = [
			'current_debit'	 		  		     => $balance_debit,
			'current_credit'	      		     => $balance_credit,
		];

		return $arrResult;
	}

	public function getTotalCashFlow($branch, $filter_start_date, $filter_end_date){
		$whereRaw = "DATE(date_transaction) BETWEEN '$filter_start_date' AND '$filter_end_date'";
		$whereRawPrevious = "DATE(date_transaction) < '$filter_start_date'";

		if($branch){
			$balance_debit = Journal::where('type','1')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($whereRaw)->sum('nominal');
			$balance_credit = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($whereRaw)->sum('nominal');

			$balance_debit_previous = Journal::where('type','1')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($whereRawPrevious)->sum('nominal');
			$balance_credit_previous = Journal::where('type','2')->where('coa_id', $this->id)->where('branch',$branch)->whereRaw($whereRawPrevious)->sum('nominal');

			$totalRetainedCorrectionPrevious = 0;
			$totalRetainedCorrectionCurrent = 0;

			if (substr($this->code, 0, 8) == '3.300.00') {
				$totalRetainedCorrectionPrevious = Journal::whereHasMorph('journalable', [CashBank::class], function ($query) {
						$query->where('code', 'not like', '%RETAINED-EARNING%');
					})
					->where('coa_id', $this->id)
					->where('branch',$branch)
					->where('type', '2')
					->whereRaw($whereRawPrevious)
					->sum('nominal');
	
				$totalRetainedCorrectionCurrent = Journal::whereHasMorph('journalable', [CashBank::class], function ($query) {
						$query->where('code', 'not like', '%RETAINED-EARNING%');
					})
					->where('coa_id', $this->id)
					->where('branch',$branch)
					->where('type', '2')
					->whereRaw($whereRaw)
					->sum('nominal');
			}
		}
		
		$arrResult = [
			'current_debit'	 		  		     => $balance_debit,
			'current_credit'	      		     => $balance_credit,
			'previous_debit' 	      		     => $balance_debit_previous,
			'previous_credit'		  		     => $balance_credit_previous,
			'total_retained_correction_previous' => $totalRetainedCorrectionPrevious,
			'total_retained_correction_current'  => $totalRetainedCorrectionCurrent,
		];
		
		return $arrResult;
	}

	public static function getCashFlow($branch, $filter_start_date, $filter_end_date){
		$categories = [
				// INCREASE
				'retained_earning' ,
				'increase_in_receivable' ,
				'increase_in_inventory' ,
				'increase_in_other_current_assets' ,
				'increase_in_accumulated_depreciation' ,
				'increase_in_payable' ,
				'increase_in_other_payable' ,
				'increase_in_fixed_assets' ,
				'increase_in_long_term_debt',
				'increase_in_equity' ,
				// DECREASE
				'decrease_in_receivable' ,
				'decrease_in_inventory' ,
				'decrease_in_other_current_assets' ,
				'decrease_in_accumulated_depreciation' ,
				'decrease_in_payable' ,
				'decrease_in_other_payable' ,
				'decrease_in_net_income' ,
				'decrease_in_fixed_assets' ,
				'decrease_in_long_term_debt' ,
				'decrease_in_equity' ,
		];
		
		$totals_pervious_period = $totals_current_period = array_fill_keys($categories, 0);

		$coas = Coa::orderBy('code')->get();
		$idCoa = [];
		// foreach ($coas as $coa) {
		// 	$cashFlow = $coa->getTotalCashFlow($branch, $filter_start_date, $filter_end_date);
	
		// 	$balance_debit_current = $cashFlow['current_debit'];
		// 	$balance_credit_current = $cashFlow['current_credit'];
	
		// 	$balance_debit_previous = $cashFlow['previous_debit'];
		// 	$balance_credit_previous = $cashFlow['previous_credit'];

		// 	if(substr($coa->code,0,5) == '1.100'){
		// 		$totals_current_period['increase_in_receivable'] += $balance_debit_current;
		// 		$totals_current_period['decrease_in_receivable'] -= $balance_credit_current;

		// 		$totals_pervious_period['increase_in_receivable'] += $balance_debit_previous;
		// 		$totals_pervious_period['decrease_in_receivable'] -= $balance_credit_previous;
		// 	}

		// 	if(substr($coa->code,0,5) == '1.200' || substr($coa->code,0,5) == '1.300' || substr($coa->code,0,5) == '1.400'){
		// 		$totals_current_period['increase_in_inventory'] += $balance_debit_current;
		// 		$totals_current_period['decrease_in_inventory'] -= $balance_credit_current;

		// 		$totals_pervious_period['increase_in_inventory'] += $balance_debit_previous;
		// 		$totals_pervious_period['decrease_in_inventory'] -= $balance_credit_previous;
		// 	}

		// 	if(substr($coa->code,0,5) == '1.500'){
		// 		$totals_current_period['increase_in_other_current_assets'] += $balance_debit_current;
		// 		$totals_current_period['decrease_in_other_current_assets'] -= $balance_credit_current;

		// 		$totals_pervious_period['increase_in_other_current_assets'] += $balance_debit_previous;
		// 		$totals_pervious_period['decrease_in_other_current_assets'] -= $balance_credit_previous;
		// 	}
		// 	if(substr($coa->code,0,5) == '1.600'){
		// 		$totals_current_period['increase_in_fixed_assets'] += $balance_debit_current;
		// 		$totals_current_period['decrease_in_fixed_assets'] -= $balance_credit_current;

		// 		$totals_pervious_period['increase_in_fixed_assets'] += $balance_debit_previous;
		// 		$totals_pervious_period['decrease_in_fixed_assets'] -= $balance_credit_previous;
		// 	}

		// 	if(substr($coa->code,0,5) == '1.610'){
		// 		$totals_current_period['increase_in_accumulated_depreciation'] += $balance_debit_current;
		// 		$totals_current_period['decrease_in_accumulated_depreciation'] -= $balance_credit_current;

		// 		$totals_pervious_period['increase_in_accumulated_depreciation'] += $balance_debit_previous;
		// 		$totals_pervious_period['decrease_in_accumulated_depreciation'] -= $balance_credit_previous;
		// 	}

		// 	if(substr($coa->code,0,5) == '2.000' || substr($coa->code,0,5) == '2.100'){
		// 		$totals_current_period['increase_in_other_current_assets'] += $balance_credit_current;
		// 		$totals_current_period['decrease_in_other_current_assets'] -= $balance_debit_current;

		// 		$totals_pervious_period['increase_in_other_current_assets'] += $balance_credit_previous;
		// 		$totals_pervious_period['decrease_in_other_current_assets'] -= $balance_debit_previous;
		// 	}

		// 	if(substr($coa->code,0,8) == '2.200.01'){
		// 		$totals_current_period['increase_in_payable'] += $balance_credit_current;
		// 		$totals_current_period['decrease_in_payable'] -= $balance_debit_current;

		// 		$totals_pervious_period['increase_in_payable'] += $balance_credit_previous;
		// 		$totals_pervious_period['decrease_in_payable'] -= $balance_debit_previous;
		// 	}

		// 	if(substr($coa->code,0,5) == '2.200' && !substr($coa->code,0,8) == '2.200.01' || substr($coa->code,0,5) == '2.300' || substr($coa->code,0,5) == '2.400' || substr($coa->code,0,5) == '2.500'){
		// 		$totals_current_period['increase_in_other_payable'] += $balance_credit_current;
		// 		$totals_current_period['decrease_in_other_payable'] -= $balance_debit_current;

		// 		$totals_pervious_period['increase_in_other_payable'] += $balance_credit_previous;
		// 		$totals_pervious_period['decrease_in_other_payable'] -= $balance_debit_previous;
		// 	}

		// 	if(substr($coa->code,0,5) == '3.000'){
		// 		$totals_current_period['increase_in_equity'] += $balance_credit_current;
		// 		$totals_current_period['decrease_in_equity'] -= $balance_debit_current;

		// 		$totals_pervious_period['increase_in_equity'] += $balance_credit_previous;
		// 		$totals_pervious_period['decrease_in_equity'] -= $balance_debit_previous;
		// 	}
			
		// 	if(substr($coa->code,0,8) == '3.300.00'){
		// 		$totals_current_period['retained_earning'] += SMB::retained_earning_by_date($filter_start_date, $filter_end_date, $branch)['total_retained_now'];
		// 		$totals_current_period['decrease_in_net_income'] -= $balance_debit_current;

		// 		$totals_pervious_period['retained_earning'] += SMB::retained_earning_by_date($filter_start_date, $filter_end_date, $branch)['total_retained_previous'];
		// 		$totals_pervious_period['decrease_in_net_income'] -= $balance_debit_previous;
		// 	}
		// }


		foreach ($coas as $coa) {
			$cashFlow = $coa->getTotalCashFlow($branch, $filter_start_date, $filter_end_date);
		
			$balance_debit_current = $cashFlow['current_debit'];
			$balance_credit_current = $cashFlow['current_credit'];
		
			$balance_debit_previous = $cashFlow['previous_debit'];
			$balance_credit_previous = $cashFlow['previous_credit'];
		
			$categoryMap = [
				'1.100' 	=> ['increase_in_receivable', 'decrease_in_receivable'],
				'1.200' 	=> ['increase_in_inventory', 'decrease_in_inventory'],
				'1.202' 	=> ['increase_in_inventory', 'decrease_in_inventory'],
				'1.210' 	=> ['increase_in_inventory', 'decrease_in_inventory'],
				'1.300' 	=> ['increase_in_inventory', 'decrease_in_inventory'],
				'1.400' 	=> ['increase_in_other_current_assets', 'decrease_in_other_current_assets'],
				'1.500' 	=> ['increase_in_other_current_assets', 'decrease_in_other_current_assets'],
				'1.600' 	=> ['increase_in_fixed_assets', 'decrease_in_fixed_assets'],
				'1.610' 	=> ['increase_in_accumulated_depreciation', 'decrease_in_accumulated_depreciation'],
				'2.000' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.100' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.200.01' 	=> ['increase_in_payable', 'decrease_in_payable'],
				'2.200' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.210' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.211' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.212' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.213' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.300' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.400' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'2.500' 	=> ['increase_in_other_payable', 'decrease_in_other_payable'],
				'3.000' 	=> ['increase_in_equity', 'decrease_in_equity'],
				'3.100' 	=> ['increase_in_equity', 'decrease_in_equity'],
				'3.300.00' 	=> ['retained_earning', 'decrease_in_net_income'],
				'3.300.01' 	=> ['retained_earning', 'decrease_in_net_income']
			];
			
			$coaCode = substr($coa->code, 0, 8);
			$code = null;

			foreach (array_keys($categoryMap) as $key) {
				if (str_contains($coaCode, $key)) {
					$code = $key;
					break;
				}
			}

			if ($code != null) {
				$category = $categoryMap[$code];
				if ($code == '3.300.00') {
					$total_retained = SMB::retained_earning_by_date($filter_start_date, $filter_end_date, $branch);
					
					$totals_current_period['retained_earning'] += $total_retained['total_retained_now'] + $cashFlow['total_retained_correction_current'];
					$totals_current_period['decrease_in_net_income'] -= $balance_debit_current;
					
				
					$totals_pervious_period['retained_earning'] += $total_retained['total_retained_previous'] + $cashFlow['total_retained_correction_previous'];
					$totals_pervious_period['decrease_in_net_income'] -= $balance_debit_previous;
					
				}else{
					$idCoa[$category[0]][] = $coa->id;
					$idCoa[$category[1]][] = $coa->id;
					
					$totals_current_period[$category[0]] += $balance_credit_current;
					$totals_current_period[$category[1]] -= $balance_debit_current;
			
					$totals_pervious_period[$category[0]] += $balance_credit_previous;
					$totals_pervious_period[$category[1]] -= $balance_debit_previous;
				}
			}
		}
		
		// PREVIOUS CASH FORMULA
		$total_operation_categories = [
			'retained_earning',
			'increase_in_receivable',
			'increase_in_inventory',
			'increase_in_other_current_assets',
			'increase_in_accumulated_depreciation',
			'increase_in_payable',
			'increase_in_other_payable',
			'decrease_in_receivable',
			'decrease_in_inventory',
			'decrease_in_other_current_assets',
			'decrease_in_accumulated_depreciation',
			'decrease_in_payable',
			'decrease_in_other_payable',
			'decrease_in_net_income',
		];
		
		$total_previous_operation_activities = 0;
		$total_operation_activities = 0;

		foreach ($total_operation_categories as $category) {
			$total_previous_operation_activities += $totals_pervious_period[$category];
			$total_operation_activities += $totals_current_period[$category];
		}
		
		// PREVIOUS CASH FORMULA
		$total_previous_investment = $totals_pervious_period['increase_in_fixed_assets'] + $totals_pervious_period['decrease_in_fixed_assets'];
		
		$total_previous_funding = $totals_pervious_period['increase_in_long_term_debt'] + $totals_pervious_period['decrease_in_long_term_debt'] + $totals_pervious_period['increase_in_equity'] + $totals_pervious_period['decrease_in_equity'];
		
		// CURRENT CASH FORMULA
		$total_investment = $totals_current_period['increase_in_fixed_assets'] + $totals_current_period['decrease_in_fixed_assets'];
		
		$total_funding = $totals_current_period['increase_in_long_term_debt'] + $totals_current_period['decrease_in_long_term_debt'] + $totals_current_period['increase_in_equity'] + $totals_current_period['decrease_in_equity'];
		
		// CASH FLOW TOTAL FORMULA
		$beginning_cash = $total_previous_operation_activities + $total_previous_investment + $total_previous_funding;
		
		$current_period_cash = $total_operation_activities + $total_investment + $total_funding;
		
		$ending_cash = $beginning_cash + $current_period_cash;

		$result = [
			'list_of_coas_id' 	 		 => $idCoa,
			'totals_current_period' 	 => $totals_current_period,
			'total_operation_activities' => number_format(round($total_operation_activities,2),2,',','.'),
			'total_investment'			 => number_format(round($total_investment, 2),2,',','.'),
			'total_funding'				 => number_format(round($total_funding, 2),2,',','.'),
			'beginning_cash'			 => number_format(round($beginning_cash, 2),2,',','.') ,
			'current_period_cash'		 => number_format(round($current_period_cash, 2),2,',','.') ,
			'ending_cash'				 => number_format(round($ending_cash, 2),2,',','.') ,
		];

		return $result;
	}
	

}
