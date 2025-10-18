<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class User extends Authenticatable {

    use HasFactory, SoftDeletes, Notifiable;

    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'photo',
		'sign',
        'name',
        'email',
        'password',
        'branch',
		'place_of_birth',
		'date_of_birth',
		'gender',
		'marital_status',
		'blood_type',
		'religion',
		'id_type',
		'id_no',
		'postcode',
		'address_id',
		'address_residence',
		'npwp',
		'ispkp',
		'ptkp_type',
		'tax_type',
		'account_number',
		'account_bank',
		'account_name',
        'verification',
        'token_device',
        'status',
		'phone'
    ];
	
	public function tax_type()
	{
		switch($this->tax_type) {
            case '1':
                $tax_type = 'Gross';
                break;
            case '2':
                $tax_type = 'Gross Up';
                break;
			case '3':
                $tax_type = 'Netto';
                break;
            default:
                $tax_type = 'Invalid';
                break;
        }

        return $tax_type;
	}
	
	public function ispkp()
	{
		switch($this->ispkp) {
            case '1':
                $ispkp = 'Yes';
                break;
            case '2':
                $ispkp = 'No';
                break;
            default:
                $ispkp = 'Invalid';
                break;
        }

        return $ispkp;
	}
	
	public function id_type()
	{
		switch($this->id_type) {
            case '1':
                $id_type = 'KTP';
                break;
            case '2':
                $id_type = 'SIM';
                break;
            default:
                $id_type = 'Invalid';
                break;
        }

        return $id_type;
	}
	
	public function religion()
	{
		switch($this->religion) {
            case '1':
                $religion = 'Islam';
                break;
            case '2':
                $religion = 'Kristen';
                break;
			case '3':
                $religion = 'Katolik';
                break;
			case '4':
                $religion = 'Hindu';
                break;
			case '5':
                $religion = 'Budha';
                break;
            default:
                $religion = 'Invalid';
                break;
        }

        return $religion;
	}

	public function gender()
	{
		switch($this->gender) {
            case '1':
                $gender = 'Male';
                break;
            case '2':
                $gender = 'Female';
                break;
            default:
                $gender = 'Invalid';
                break;
        }

        return $gender;
	}
	
	public function marital_status()
	{
		switch($this->marital_status) {
            case '1':
                $marital_status = 'Single';
                break;
            case '2':
                $marital_status = 'Married';
                break;
			case '3':
                $marital_status = 'Widow';
                break;
			case '4':
                $marital_status = 'Widower';
                break;
			case '5':
                $marital_status = 'Divorced';
                break;
            default:
                $marital_status = 'Invalid';
                break;
        }

        return $marital_status;
	}

    public function branch() 
    {
        switch($this->branch) {
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

    public function userRole()
    {
        return $this->hasMany('App\Models\UserRole');
    }
	
	public function userLogin()
    {
        return $this->hasMany('App\Models\UserLogin');
    }
	
	public function userBalance()
    {
        return $this->hasMany('App\Models\UserBalance');
    }
	
	public function employment()
    {
        return $this->hasMany('App\Models\Employment','employee_id','id');
    }
	
	public function family()
    {
        return $this->hasMany('App\Models\Family','employee_id','id');
    }
	
	public function education()
    {
        return $this->hasMany('App\Models\Education','employee_id','id');
    }
	
	public function experience()
    {
        return $this->hasMany('App\Models\Experience','employee_id','id');
    }
	
	public function asset()
    {
        return $this->hasMany('App\Models\AssetEmployee','employee_id','id');
    }
	
	public function employeeLoan()
    {
        return $this->hasMany('App\Models\EmployeeLoan','employee_id','id');
    }
	
	public function photo(){
		return $this->photo ? asset(Storage::url($this->photo)) : asset('website/user.png');
	}
	
	public function getCheckIn($month){
		$exceptholiday = Holiday::where('date','like',"$month%")->where('branch',$this->branch)->pluck('date')->toArray();
		
		$dataleave = LeaveRequest::where('user_id',$this->id)->where('category','1')->whereNotNull('approved_by')->whereNotNull('checked_by')->get();
		
		$arrleave = [];
		
		foreach($dataleave as $rowleave){
			$startDate = new Carbon($rowleave->start_date);
			$endDate = new Carbon($rowleave->finish_date);
			while ($startDate->lte($endDate)){
				$arrleave[] = $startDate->toDateString();
				$startDate->addDay();
			}
		}
		
		$getattendance = Attendance::where('date','like',"$month%")->where('user_id',$this->id)->whereNotIn('date',$exceptholiday)->whereNotIn('date',$arrleave)->get();
		
		$leaverule = AllowanceRule::where('type_rule','2')->latest()->first();
		
		$totalminutelate = 0;
		$dayslate = 0;
		$totaloverleave = 0;
		
		foreach($getattendance as $row){
			if($row->getMinLateIn() > 0){
				$dayslate++;
			}
			
			$totalminutelate += $row->getMinLateIn();
			
			$leaveOutTime = $row->leave_out_time ? $row->leave_out_time : '';
			$leaveInTime = $row->leave_in_time ? $row->leave_in_time : '';
			
			$difference = 0;
			
			if($leaverule){
				if($leaveOutTime && $leaveInTime){
					if($leaverule->unit_rule == '3'){
						$difference = round((strtotime($leaveInTime) - strtotime($leaveOutTime)) / 3600,2);
					}elseif($leaverule->unit_rule == '4'){
						$difference = round((strtotime($leaveInTime) - strtotime($leaveOutTime)) / 60,2);
					}
					if($leaverule->sign_rule == '<'){
						if($difference < $leaverule->number_rule){
							$totaloverleave++;
						}
					}elseif($leaverule->sign_rule == '>'){
						if($difference > $leaverule->number_rule){
							$totaloverleave++;
						}
					}elseif($leaverule->sign_rule == '<='){
						if($difference <= $leaverule->number_rule){
							$totaloverleave++;
						}
					}elseif($leaverule->sign_rule == '>='){
						if($difference >= $leaverule->number_rule){
							$totaloverleave++;
						}
					}
				}
			}
		}
		
		$result = [
			'countCheckIn' 	=> count($getattendance),
			'lateMinute'	=> $totalminutelate,
			'daysLate'		=> $dayslate,
			'overLeave'		=> $totaloverleave
		];
		
		return $result;
	}
	
	public function checkPayment($payment,$month){
		$allowance = EmployeeAllowance::where('employee_id',$this->id)->where('start_month','<=',"$month")->where('end_month','>=',"$month")->where('payment_type',$payment)->latest()->first();
		
		$status = '0';
		
		if($allowance){
			$status = '1';
		}
		
		return $status;
	}
	
	public function checkPayrollUser($payment,$month){
		$allowance = EmployeeAllowance::where('employee_id',$this->id)->where('start_month','<=',"$month")->where('end_month','>=',"$month")->where('payment_type',$payment)->latest()->first();
		
		return $allowance;
	}
	
	public function getAllowance($month,$date_start,$date_end){
		$allowance = EmployeeAllowance::where('employee_id',$this->id)->where('start_month','<=',"$month")->where('end_month','>=',"$month")->latest()->first();
		
		$total = 0;
		
		if($allowance){
			foreach($allowance->employeeAllowanceDetail as $row){
				$total += $row->getNominal($this->id,$this->branch,$month,$date_start,$date_end);
			}
		}
		
		return number_format($total,2,',','.');
	}
	
	public function getCutting($month,$date_start,$date_end){
		$allowance = EmployeeAllowance::where('employee_id',$this->id)->where('start_month','<=',"$month")->where('end_month','>=',"$month")->latest()->first();
		
		$total = 0;
		
		if($allowance){
			foreach($allowance->employeeAllowanceDetail as $row){
				$total += $row->getNominalCutting($this->id,$this->branch,$month,$date_start,$date_end);
			}
		}
		
		return number_format($total,2,',','.');
	}
	
	public function getLoanCredit(){
		$cash_advance = EmployeeLoan::where('employee_id',$this->id)->get();
		
		$total = 0;
		
		if($cash_advance){
			foreach($cash_advance as $row){
				if(count($row->employeeLoanPayment) < $row->top){
					$nominal = 0;
					
					if($row->interest_type == '1'){
						$nominal = round(($row->interest_percent / 100) * $row->nominal) + round(($row->nominal / $row->top));
					}elseif($row->interest_type == '2'){
						$creditperpayroll = round($row->nominal / $row->top);
						$balancepayroll = $row->nominal - round($creditperpayroll * count($row->employeeLoanPayment));
						$nominal = round(($row->interest_percent / 100) * $balancepayroll) + $creditperpayroll;
					}
					
					$total += $nominal;
				}
			}
		}
		
		return number_format($total,2,',','.');
	}
	
	public function getLoanArr(){
		$loan = EmployeeLoan::where('employee_id',$this->id)->get();
		
		$arr = [];
		$arrtotal = [];
		
		if($loan){
			foreach($loan as $row){
				if(count($row->employeeLoanPayment) < $row->top){
					
					$nominal = 0;
					
					if($row->interest_type == '1'){
						$nominal = round(($row->interest_percent / 100) * $row->nominal) + round(($row->nominal / $row->top));
					}elseif($row->interest_type == '2'){
						$creditperpayroll = round($row->nominal / $row->top);
						$balancepayroll = $row->nominal - round($creditperpayroll * count($row->employeeLoanPayment));
						$nominal = round(($row->interest_percent / 100) * $balancepayroll) + $creditperpayroll;
					}
					
					$arr[] = $row->id;
					$arrtotal[] = $nominal;
				}
			}
		}
		
		return implode(',',$arr).'|'.implode(',',$arrtotal);
	}
	
	public function employeeAllowance(){
		return $this->hasMany('App\Models\EmployeeAllowance','employee_id','id');
	}
}
