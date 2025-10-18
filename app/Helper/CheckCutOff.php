<?php 

namespace App\Helper;

use App\Models\SettingAccounting;
use App\Models\SettingAccountingDaily;
use DateTime;
use DateInterval;
use Carbon\Carbon;

class CheckCutOff {

	public static function check($branch,$month)
    {
		$status = true;
		
		$data = SettingAccounting::where('branch',$branch)->where('month',$month)->get();
		
		foreach($data as $row){
			if($row->status == '1'){
				$status = false;
			}
		}
		
		return $status;
	}

	public static function checkMonthly($branch, $date){
		$status = true;
		
		$data = SettingAccountingDaily::where('branch',$branch)->where('date',$date)->get();
		
		foreach($data as $row){
			if($row->status == '1'){
				$status = false;
			}
		}
		
		return $status;
	}
	
}