<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeLoan extends Model {

    use HasFactory;

    protected $table      = 'employee_loans';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'employee_id',
        'date_borrow',
        'interest_type',
        'interest_percent',
        'top',
        'nominal',
		'note'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function employee()
    {
        return $this->belongsTo('App\Models\User','employee_id','id');
    }
	
	public function employeeLoanPayment()
    {
        return $this->hasMany('App\Models\EmployeeLoanPayment');
    }
	
	public function interestType() 
    {
        switch($this->interest_type) {
            case '1':
                $interest_type = 'Fixed rate';
                break;
            case '2':
                $interest_type = 'Moving rate';
                break;
            default:
                $interest_type = 'Invalid';
                break;
        }

        return $interest_type;
    }
	
	public function percentProgress()
	{
		$total = $this->nominal;
		$totalpaid = 0;
		
		foreach($this->employeeLoanPayment as $row){
			$totalpaid += $row->nominal;
		}
		
		$percent = $total ? round(($totalpaid / $total) * 100,0) : 0;
		
		if($percent >= 100){
			$progress = '
				<div class="progress" style="height:0.875rem;">
					<div class="progress-bar progress-bar-striped bg-teal" style="width:100%">
						<span class="font-weight-bold text-uppercase">
							<span style="font-size:13px;">' . $percent . '%</span>
						</span>
					</div>
				</div>
			';
		}elseif($this->closed_by_acc){
			$progress = '
				<div class="progress" style="height:0.875rem;">
					<div class="progress-bar progress-bar-striped bg-danger" style="width:100%">
						<span class="font-weight-bold text-uppercase">
							<span style="font-size:13px;">
								Closed by<br>
								Accounting
							</span>
						</span>
					</div>
				</div>
			';
		}else{
			$progress = '
				<div class="progress" style="height:0.875rem;">
					<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
						<span class="font-weight-bold text-uppercase">
							<span style="font-size:13px;">' . $percent . '%</span>
						</span>
					</div>
				</div>
			';
		}
		
		return $progress;
	}
	
	public function balance()
	{
		$total = $this->nominal;
		$totalpaid = 0;
		
		foreach($this->employeeLoanPayment as $row){
			$totalpaid += $row->nominal;
		}
		
		$balance = $total - $totalpaid;
		
		return $balance;
	}
	
	public function payment()
	{
		$count = count($this->employeeLoanPayment);
		
		return $count;
	}
	
	public function mustPay(){
		$nominal = 0;
		
		if(count($this->employeeLoanPayment) < $this->top){
			if($this->interest_type == '1'){
				$nominal = round(($this->interest_percent / 100) * $this->nominal) + round(($this->nominal / $this->top));
			}elseif($this->interest_type == '2'){
				$creditperpayroll = round($this->nominal / $this->top);
				$balancepayroll = $this->nominal - round($creditperpayroll * count($this->employeeLoanPayment));
				$nominal = round(($this->interest_percent / 100) * $balancepayroll) + $creditperpayroll;
			}
		}
		
		return $nominal;
	}
}