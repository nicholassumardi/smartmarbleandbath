<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBankDetail extends Model
{

	use HasFactory;

	protected $table      = 'cash_bank_details';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'cash_bank_id',
		'coa_id',
		'branch',
		'type',
		'nominal',
		'note'
	];

	public function coa()
	{
		return $this->belongsTo('App\Models\Coa');
	}

	public function cashBank()
	{
		return $this->belongsTo('App\Models\CashBank');
	}

	public function branch()
	{
		switch ($this->branch) {
			case '1':
				$branch = 'PTA';
				break;
			case '2':
				$branch = 'SMB';
				break;
			case '3':
				$branch = 'MKJ';
				break;
			case '4':
				$branch = 'PSI';
				break;
			default:
				$branch = 'Invalid';
				break;
		}

		return $branch;
	}

	public function type()
	{
		switch ($this->type) {
			case '1':
				$type = 'Debit';
				break;
			case '2':
				$type = 'Credit';
				break;
			default:
				$type = 'Invalid';
				break;
		}

		return $type;
	}

	function dateDiffInDays($date1, $date2)
	{
		$diff = strtotime($date2) - strtotime($date1);

		return abs(round($diff / 86400));
	}

	public function getBalanceOtherAP($filter, $branch, $index)
	{
		$total30 = 0;
		$total60 = 0;
		$total90 = 0;
		$totalover = 0;
		$totalpr = 0;
		$totalcrAP = 0;
		$totaldbAP = 0;
		$balanceReal = 0;
		$sisa = 0;
		$payment = 0;
		$data_other = [];
		
		$date = date('Y-m-t', strtotime($filter));

		$total = $this->nominal;
		$diffdays = $this->dateDiffInDays($this->cashBank->date, $date);
		if ($diffdays <= 30) {
			$total30 =  $this->nominal;
		} elseif ($diffdays <= 60) {
			$total60 =  $this->nominal;
		} elseif ($diffdays <= 90) {
			$total90 =  $this->nominal;
		} elseif ($diffdays > 90) {
			$totalover =  $this->nominal;
		}


		foreach (PurchaseRequest::where('link_type', 'fee_pta')->where('link_id', $this->cashBank->id)->get() as $rowpay) {
			$totalpr += $rowpay->totalPaymentPeriod($filter);
		}

		foreach (CashBank::where('code', 'like', $this->cashBank->code . '%')->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rowpay) {
			if (count(explode('-', $rowpay->code)) > 3) {
				foreach (CashBankDetail::whereHas('cashBank', function ($query) use ($filter) {
					$query->where('code', $this->cashBank->code)->whereRaw("LEFT(date, 7) <= '$filter'");
				})->where('coa_id', 332)->where('type', '2')->get() as $key => $rowcek) {
					if ((intval(explode('-', $rowpay->code)[3]) - 1) == $key && $rowcek->id == $this->id) {
						foreach ($rowpay->cashBankDetail->where('coa_id', 332)->where('type', '1') as $rowdetail) {
							$totalcrAP += $rowdetail->nominal;
						}
					}
				}
			}
		}

		if ($this->cashBank->lookable_type == 'projects') {
			foreach (Project::find($this->cashBank->lookable_id)->projectSaleReturn as $rowreturn) {
				foreach (CashBankDetail::whereHas('cashBank', function ($query) use ($filter, $rowreturn) {
					$query->where('lookable_type', 'project_sale_returns')->where('lookable_id', $rowreturn->id)->whereRaw("LEFT(date, 7) <= '$filter'");
				})->where('coa_id', 332)->where('type', '1')->where('branch', $branch)->get() as $rowcek) {
					$totaldbAP += $rowcek->nominal;
				}
			}
		}


		if($this->cashBank->lookable_type == 'purchase_requests' || $this->cashBank->lookable_type == 'projects' || str_contains($this->cashBank->code, 'RJCT')  ||  $this->cashBank->lookable_type == null ){
	
			$cek = NULL;
			$cek_rjct = NULL;
			if($this->cashBank->lookable_type == 'purchase_requests'){
				$cek = PurchaseRequest::find($this->cashBank->lookable_id);
			}elseif($this->cashBank->lookable_type == 'projects'){
				$cek = PurchaseRequest::find(explode('-',$this->cashBank->code)[1]);
			}elseif(str_contains($this->cashBank->code, 'RJCT')){
				$whereRaw = strlen($filter) == 7 ? "LEFT(date, 7) <= '$filter'" : "date <= '$filter'";
				$isMultipleReject = explode("-",$this->cashBank->code);
				$length_of_last_code = isset($isMultipleReject[3]) ? strlen($isMultipleReject[3]) : 0;

				$cek_rjct = isset($isMultipleReject[3]) ? CashBank::where('code','like',substr($this->cashBank->code, 0, -$length_of_last_code).'CLOSE-%'.$isMultipleReject[3])->whereRaw($whereRaw)->first() : CashBank::where('code','like', $this->cashBank->code.'-CLOSE%')->whereRaw($whereRaw)->first() ;
			}elseif($this->cashBank->lookable_type == null){
				
			}
			
			$totalpay = $cek ? $cek->totalPaymentPeriod($filter) : ($cek_rjct ? $cek_rjct->cashBankDetail->where('coa_id', 332)->where('type', 1)->first()->nominal : 0);

			$payment = $totalpay;
		}else {
			$payment += $this->nominal;
		}


		if ($index > 0) {
			$total = $total - $totalcrAP;
			$balance = $totalcrAP + $payment;
		} else {
			$total = $total -  $totalpr - $totalcrAP - $totaldbAP;
			$balance = $totalpr + $totalcrAP + $totaldbAP + $payment;
		}

		if ($totalover > 0) {
			if ($balance > 0) {
				if ($totalover - $balance < 0) {
					$totalover = 0;
				} else {
					$totalover = $totalover - $balance;
				}
				$balance -= $totalover;
			} 
		}

		if ($total90 > 0) {
			if ($balance > 0) {
				if ($total90 - $balance < 0) {
					$total90 = 0;
				} else {
					$total90 = $total90 - $balance;
				}
				$balance -= $total90;
			} 
		}

		if ($total60 > 0) {
			if ($balance > 0) {
				if ($total60 - $balance < 0) {
					$total60 = 0;
				} else {
					$total60 = $total60 - $balance;
				}
				$balance -= $total60;
			}
		}

		if ($total30 > 0) {
			if ($balance > 0) {
				if ($total30 - $balance < 0) {
					$total30 = 0;
				} else {
					$total30 = $total30 - $balance;
				}
				$balance -= $total30;
			} 
		}


		$total =  $total > 0 ?  $total : 0;

		if ($this->cashBank->lookable_type == 'purchase_requests' || $this->cashBank->lookable_type == 'projects' || str_contains($this->cashBank->code, 'RJCT')  ||  $this->cashBank->lookable_type == null) {
			$cek = NULL;
			if ($this->cashBank->lookable_type == 'purchase_requests') {
				$cek = PurchaseRequest::find($this->cashBank->lookable_id);
			} elseif ($this->cashBank->lookable_type == 'projects') {
				$cek = PurchaseRequest::find(explode('-', $this->cashBank->code)[1]);
			}elseif(str_contains($this->cashBank->code, 'RJCT')){
				$whereRaw = strlen($filter) == 7 ? "LEFT(date, 7) <= '$filter'" : "date <= '$filter'";
				$isMultipleReject = explode("-",$this->cashBank->code);
				$length_of_last_code = isset($isMultipleReject[3]) ? strlen($isMultipleReject[3]) : 0;

				$cek_rjct = isset($isMultipleReject[3]) ? CashBank::where('code','like',substr($this->cashBank->code, 0, -$length_of_last_code).'CLOSE-%'.$isMultipleReject[3])->whereRaw($whereRaw)->first() : CashBank::where('code','like', $this->cashBank->code.'-CLOSE%')->whereRaw($whereRaw)->first() ;
			}elseif($this->cashBank->lookable_type == null){
				
			}
			
			$totalpay = $cek ? $cek->totalPaymentPeriod($filter) : ($cek_rjct ? $cek_rjct->cashBankDetail->where('coa_id', 332)->where('type', 1)->first()->nominal : 0);
			// $totalpay = $cek ? $cek->totalPaymentPeriod($filter) : 0;
			$sisa = $total - $totalpay;
			
			if ($sisa > 0) {
				$balanceReal += $sisa;
			}
		} else {
			if ($this->cashBank->lookable_type !== 'project_warehouses') {
				$balanceReal += $total;
			}
		}

		$result = [
			'payment'	   => round($payment, 0),
			'total'		   => round($total, 0),
			'total30'	   => round($total30, 0),
			'total60'	   => round($total60, 0),
			'total90'	   => round($total90, 0),
			'totalover'	   => round($totalover, 0),
			'balance_real' => round($balanceReal, 2)
		];

		return $result;
		
	}
}
