<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helper\SMB;

class EmployeeAllowanceDetail extends Model {

    use HasFactory;

    protected $table      = 'employee_allowance_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'employee_allowance_id',
		'allowance_id',
		'nominal',
    ];
	
	public function employeeAllowance()
    {
        return $this->belongsTo('App\Models\EmployeeAllowance');
    }
	
	public function allowance()
    {
        return $this->belongsTo('App\Models\Allowance');
    }
	
	public function getNominal($user,$branch,$month,$date_start,$date_end){
		
		$total = 0;
		
		if($this->allowance->type == '1'){
			$total = $this->nominal;
		}elseif($this->allowance->type == '2'){
			$arrDate = SMB::getWorkDay($branch,$date_start,$date_end);
			$total = $this->nominal * count($arrDate);
		}
		
		return $total;
	}
	
	public function getQty($branch,$date_start,$date_end){
		
		$total = 0;
		
		if($this->allowance->type == '1'){
			$total = 1;
		}elseif($this->allowance->type == '2'){
			$arrDate = SMB::getWorkDay($branch,$date_start,$date_end);
			$total = count($arrDate);
		}
		
		return $total;
	}
	
	public function getQtyCutting($user,$branch,$date_start,$date_end){
		
		$total = 0;
		
		$rule = AllowanceRule::where('allowance_id',$this->allowance_id)->get();
		
		foreach($rule as $row){
			if($row->type_rule == '1'){
				if($this->allowance->type == '1'){
					$total = 1;
				}elseif($this->allowance->type == '2'){
					$workday = count(SMB::getWorkDay($branch,$date_start,$date_end));
					$checkin = count(SMB::getCheckIn($user,$branch,$date_start,$date_end));
					
					$total += ($workday - $checkin);
				}
			}elseif($row->type_rule == '2'){
				if($this->allowance->type == '2'){
					$total += SMB::getLeaveOver($user,$branch,$date_start,$date_end,$row->number_rule,$row->sign_rule,$row->unit_rule);
				}
			}elseif($row->type_rule == '3'){
				$checkin = SMB::getCheckIn($user,$branch,$date_start,$date_end);
				
				$total_late = 0;
				foreach($checkin as $rowcek){
					$total_late += $rowcek->getMinLateIn() > 0 ? 1 : 0;
				}
				
				$total += $total_late;
			}
		}
		
		return $total;
	}
	
	public function getNominalCutting($user,$branch,$month,$date_start,$date_end){
		
		$total = 0;
		
		$rule = AllowanceRule::where('allowance_id',$this->allowance_id)->get();

		foreach($rule as $row){
			if($row->type_rule == '1'){
				$workday = count(SMB::getWorkDay($branch,$date_start,$date_end));
				$checkin = count(SMB::getCheckIn($user,$branch,$date_start,$date_end));
				
				$selisih = $workday - $checkin;
				
				if($selisih > 0){
					if($this->allowance->type == '1'){
						$total += $this->nominal * ($row->percentage_cutting / 100);
					}elseif($this->allowance->type == '2'){
						$total += $selisih * $this->nominal * ($row->percentage_cutting / 100);
					}
				}
			}elseif($row->type_rule == '2'){
				if($row->unit_rule == '3'){
					if($this->allowance->type == '2'){
						$total += SMB::getLeaveOver($user,$branch,$date_start,$date_end,$row->number_rule,$row->sign_rule,$row->unit_rule) * $this->nominal * ($row->percentage_cutting / 100);
					}
				}
			}elseif($row->type_rule == '3'){
				$checkin = SMB::getCheckIn($user,$branch,$date_start,$date_end);
				
				$total_late = 0;
				foreach($checkin as $rowcek){
					$total_late += $rowcek->getMinLateIn() > 0 ? 1 : 0;
				}
				
				$total += $total_late * $this->nominal * ($row->percentage_cutting / 100);
			}
		}
		
		return $total;
	}
}