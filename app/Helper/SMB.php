<?php 

namespace App\Helper;

use App\Models\Coa;
use App\Models\Journal;
use App\Models\Budgeting;
use App\Models\ProjectSale;
use App\Models\ProjectDelivery;
use App\Models\ProjectSaleReturn;
use App\Models\BalanceHistory;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\PurchaseRequest;
use App\Models\CashFlow;
use App\Models\Schedule;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\CashFLowBalance;
use DateTime;
use DateInterval;
use Carbon\Carbon;

class SMB {

	public static function say($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = [
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        ];

        if(!is_numeric($number)) {
            return false;
        }

        if(($number >= 0 && (int) $number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);
            return false;
        }

        if($number < 0) {
            return $negative . self::say(abs($number));
        }

        $string = $fraction = null;
        if(strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch(true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int)($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];

                if($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string    = $dictionary[$hundreds] . ' ' . $dictionary[100];

                if($remainder) {
                    $string .= $conjunction . self::say($remainder);
                }
                break;
            default:
                $baseUnit     = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder    = $number % $baseUnit;
                $string       = self::say($numBaseUnits) . ' ' . $dictionary[$baseUnit];

                if($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::say($remainder);
                }
                break;
        }

        if(null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }

            $string .= implode(' ', $words);
        }

        return ucwords($string);
    }
	
	public static function tgl_indo($tanggal){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);
		
		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun
	 
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}
	
	public static function terbilang($angka) {
	   $angka=abs($angka);
	   
	   $baca =array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	 
	   $terbilang="";
		if ($angka < 12){
			$terbilang= " " . $baca[$angka];
		}
		else if ($angka < 20){
			$terbilang= self::terbilang($angka - 10) . " belas";
		}
		else if ($angka < 100){
			$terbilang= self::terbilang($angka / 10) . " puluh" . self::terbilang($angka % 10);
		}
		else if ($angka < 200){
			$terbilang= " seratus" . self::terbilang($angka - 100);
		}
		else if ($angka < 1000){
			$terbilang= self::terbilang($angka / 100) . " ratus" . self::terbilang($angka % 100);
		}
		else if ($angka < 2000){
			$terbilang= " seribu" . self::terbilang($angka - 1000);
		}
		else if ($angka < 1000000){
			$terbilang= self::terbilang($angka / 1000) . " ribu" . self::terbilang($angka % 1000);
		}
		else if ($angka < 1000000000){
		   $terbilang= self::terbilang($angka / 1000000) . " juta" . self::terbilang($angka % 1000000);
		}
		else if ($angka < 1000000000000){
		   $terbilang= self::terbilang($angka / 1000000000) . " miliar" . self::terbilang($angka % 1000000000);
		}
		
		return ucwords($terbilang);
	}
	
   public static function diffTime($start_date, $finish_date) 
   {
      $start  = date_create($start_date);
      $finish = date_create($finish_date);
      $diff   = date_diff($start, $finish);

      return $diff;
   }
   
    public static function getRomawi($bln){

        switch ($bln){
			case 1:
				return "I";
				break;
			case 2:
				return "II";
				break;
			case 3:
				return "III";
				break;
			case 4:
				return "IV";
				break;
			case 5:
				return "V";
				break;
			case 6:
				return "VI";
				break;
			case 7:
				return "VII";
				break;
			case 8:
				return "VIII";
				break;
			case 9:
				return "IX";
				break;
			case 10:
				return "X";
				break;
			case 11:
				return "XI";
				break;
			case 12:
				return "XII";
				break;
		}
	}
	
	public static function total_sales_budget($filter,$branch)
	{
		$coa = Coa::where('code','LIKE','4.%')->get();
		
		$total = 0;
		
		foreach($coa as $row){
			$total += $row->checkTotalBudgeting($filter,$branch);
		}
		
		return $total;
	}
	
	public static function total_sales_budget_yearly($branch,$filter)
	{
		$coa = Coa::where('code','LIKE','4.%')->get();
		
		$total = 0;
		
		foreach($coa as $row){
			$total += $row->checkTotalBudgetingYearly($filter,$branch);
		}
		
		return $total;
	}
	
	public static function total_sales_budget_remaining($filter,$branch)
	{
		$coa = Coa::where('code','LIKE','4.%')->get();
		
		$total = 0;
		
		foreach($coa as $row){
			$total += $row->checkTotalBudgetingRemaining($filter,$branch);
		}
		
		return $total;
	}
	
	public static function total_sales_budget_year($branch,$year)
	{
		$coa = Coa::where('code','LIKE','4.%')->get();

		$result = [];
		
		for($i=1;$i<13;$i++){
			$totalbudget = 0;
			
			$filter = $year.'-'.str_pad($i, 2, '0', STR_PAD_LEFT);
			
			foreach($coa as $row){
				$totalbudget += $row->checkTotalBudgeting($filter,$branch);
			}
			
			$sale = ProjectSale::whereHas('sales', function($query) use($branch){
							$query->where('branch',$branch);
					})
					->where('created_at','like',"$filter%")
					->get();
			
			$totalsale = 0;
			
			foreach($sale as $row){
				$totalsale += str_replace(',','.',str_replace('.','',$row->getTotalRawPlusService()));
			}
			
			$delivery = ProjectDelivery::whereHas('projectSale', function($query) use($branch){
						$query->whereHas('sales', function($query) use($branch) {
							$query->where('branch',$branch);
						});
					})
					->whereNotNull('received_date')
					->where('received_date','like',"$filter%")
					->get();
			
			$totaldelivery = 0;
			
			foreach($delivery as $row){
				$totaldelivery += round($row->getTotalRawPlusService(),2);
			}
			
			$return = ProjectSaleReturn::whereHas('projectSale', function($query) use($branch){
						$query->whereHas('sales', function($query) use($branch) {
							$query->where('branch',$branch);
						});
					})
					->where('created_at','like',"$filter%")
					->get();
			
			$totalreturn = 0;
			
			foreach($return as $row){
				if($row->project->ppn == '1'){
					if(date('Y-m-d',strtotime($row->projectSale->created_at)) < '2022-04-01'){
						$totalreturn += $row->getTotal() / 1.1;
					}else{
						$totalreturn += $row->getTotal() / 1.11;
					}
				}else{
					$totalreturn += $row->getTotal();
				}
			}
			
			$result[] = [
				'month'			=> $filter,
				'totalbudget'	=> $totalbudget,
				'totalsale'		=> $totalsale,
				'totaldelivery'	=> $totaldelivery - round($totalreturn,2),
				'totalreturn'	=> $totalreturn
			];
		}
		
		return $result;
	}


    // STILL TESTING
   public static function retained_earning_by_date($filter_start_date, $filter_end_date, $branch)
   {
      $coa = Coa::orderBy('code')->get();

      $totals = self::calculateTotals($coa, $filter_start_date, $filter_end_date, $branch);

      $total_retained_previous = $totals['revenue_actual_previous'] - $totals['cogs_actual_previous'] -
         $totals['fixed_cost_actual_previous'] - $totals['variable_cost_actual_previous'] -
         $totals['other_expenses_actual_previous'] - $totals['repair_expenses_actual_previous'] -
         $totals['depreciation_actual_previous'] + $totals['other_income_actual_previous'] -
         $totals['other_deduction_actual_previous'];

      $total_retained_now = $totals['revenue_actual_now'] - $totals['cogs_actual_now'] -
         $totals['fixed_cost_actual_now'] - $totals['variable_cost_actual_now'] -
         $totals['other_expenses_actual_now'] - $totals['repair_expenses_actual_now'] -
         $totals['depreciation_actual_now'] + $totals['other_income_actual_now'] -
         $totals['other_deduction_actual_now'];

      return [
         'total_retained_previous' => $total_retained_previous,
         'total_retained_now'      => $total_retained_now,
      ];
   }

   private static function calculateTotals($coas, $filter_start_date, $filter_end_date, $branch)
   {
      $totals = [
         'revenue_actual_previous' => 0,
         'cogs_actual_previous' => 0,
         'fixed_cost_actual_previous' => 0,
         'variable_cost_actual_previous' => 0,
         'other_expenses_actual_previous' => 0,
         'repair_expenses_actual_previous' => 0,
         'depreciation_actual_previous' => 0,
         'other_income_actual_previous' => 0,
         'other_deduction_actual_previous' => 0,
         'revenue_actual_now' => 0,
         'cogs_actual_now' => 0,
         'fixed_cost_actual_now' => 0,
         'variable_cost_actual_now' => 0,
         'other_expenses_actual_now' => 0,
         'repair_expenses_actual_now' => 0,
         'depreciation_actual_now' => 0,
         'other_income_actual_now' => 0,
         'other_deduction_actual_now' => 0,
      ];

      foreach ($coas as $coa) {
         $totals = self::calculateCOATotals($coa, $filter_start_date, $filter_end_date, $branch, $totals);
      }

      return $totals;
   }

   private static function calculateCOATotals($coa, $filter_start_date, $filter_end_date, $branch, $totals)
   {
    $balanceprevious = $coa->checkTotalPLDateCF($filter_start_date, $filter_end_date, $branch)['total_balance_previous'];
    $balancenow = $coa->checkTotalPLDateCF($filter_start_date, $filter_end_date, $branch)['total_balance'];

    if (substr($coa->code, 0, 5) == '4.000' || substr($coa->code, 0, 5) == '4.100') {
        $totals['revenue_actual_previous'] += $balanceprevious;
        $totals['revenue_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '5.000' || substr($coa->code, 0, 5) == '6.000' ||
        substr($coa->code, 0, 5) == '6.100') {
        $totals['cogs_actual_previous'] += $balanceprevious;
        $totals['cogs_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '6.200') {
        $totals['fixed_cost_actual_previous'] += $balanceprevious;
        $totals['fixed_cost_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 9) == '6.2100.02') {
        $totals['variable_cost_actual_previous'] += $balanceprevious;
        $totals['variable_cost_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 9) == '6.2100.03') {
        $totals['other_expenses_actual_previous'] += $balanceprevious;
        $totals['other_expenses_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 6) == '6.2200') {
        $totals['repair_expenses_actual_previous'] += $balanceprevious;
        $totals['repair_expenses_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '6.300' || substr($coa->code, 0, 5) == '6.400') {
        $totals['depreciation_actual_previous'] += $balanceprevious;
        $totals['depreciation_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '7.100') {
        $totals['other_income_actual_previous'] += $balanceprevious;
        $totals['other_income_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '7.200') {
        $totals['other_deduction_actual_previous'] += $balanceprevious;
        $totals['other_deduction_actual_now'] += $balancenow;
    }

    return $totals;
   }


   // STILL TESTING

   	
	// public static function retained_earning($filter,$branch)
	// {
	// 	$total_revenue_actual = 0;
	// 	$total_cogs_actual = 0;	
	// 	$total_fixed_cost_actual = 0;
	// 	$total_variable_cost_actual = 0;
	// 	$total_other_expenses_actual = 0;
	// 	$total_repair_expenses_actual = 0;
	// 	$total_depreciation_actual = 0;
	// 	$total_other_income_actual = 0;
	// 	$total_other_deduction_actual = 0;
		
	// 	$total_revenue_actual_now = 0;
	// 	$total_cogs_actual_now = 0;	
	// 	$total_fixed_cost_actual_now = 0;
	// 	$total_variable_cost_actual_now = 0;
	// 	$total_other_expenses_actual_now = 0;
	// 	$total_repair_expenses_actual_now = 0;
	// 	$total_depreciation_actual_now = 0;
	// 	$total_other_income_actual_now = 0;
	// 	$total_other_deduction_actual_now = 0;
		
	// 	$coa = Coa::orderBy('code')->get();
		
	// 	foreach($coa->where('parent_id',0) as $rowparent){
	// 		if(substr($rowparent->code,0,1) == '4' || substr($rowparent->code,0,1) == '5' || substr($rowparent->code,0,1) == '6' || substr($rowparent->code,0,1) == '7'){
	// 			if(count($rowparent->child()) == 0){
	// 				$balance = $rowparent->checkTotal($filter,$branch);
	// 				$balancenow = $rowparent->checkTotalPL($filter,$branch);
					
	// 				if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
	// 					$total_revenue_actual += $balance;
	// 					$total_revenue_actual_now += $balancenow;
	// 				}
					
	// 				if(substr($rowparent->code,0,5) == '5.000' || substr($rowparent->code,0,5) == '6.000' || substr($rowparent->code,0,5) == '6.100'){
	// 					$total_cogs_actual += $balance;
	// 					$total_cogs_actual_now += $balancenow;
	// 				}
					
	// 				if(substr($rowparent->code,0,5) == '6.200'){
	// 					$total_fixed_cost_actual += $balance;
	// 					$total_fixed_cost_actual_now += $balancenow;
	// 				}
					
	// 				if(substr($rowparent->code,0,9) == '6.2100.02'){
	// 					$total_variable_cost_actual += $balance;
	// 					$total_variable_cost_actual_now += $balancenow;
	// 				}
					
	// 				if(substr($rowparent->code,0,9) == '6.2100.03'){
	// 					$total_other_expenses_actual += $balance;
	// 					$total_other_expenses_actual_now += $balancenow;
	// 				}
					
	// 				if(substr($rowparent->code,0,6) == '6.2200'){
	// 					$total_repair_expenses_actual += $balance;
	// 					$total_repair_expenses_actual_now += $balancenow;
	// 				}
					
	// 				if(substr($rowparent->code,0,5) == '6.300' || substr($rowparent->code,0,5) == '6.400'){
	// 					$total_depreciation_actual += $balance;
	// 					$total_depreciation_actual_now += $balancenow;
	// 				}
					
	// 				if(substr($rowparent->code,0,5) == '7.100'){
	// 					$total_other_income_actual += $balance;
	// 					$total_other_income_actual_now += $balancenow;
	// 				}
					
	// 				if(substr($rowparent->code,0,5) == '7.200'){
	// 					$total_other_deduction_actual += $balance;
	// 					$total_other_deduction_actual_now += $balancenow;
	// 				}
	// 			}
				
	// 			foreach($rowparent->child() as $rowchild){
	// 				if(count($rowchild->child()) == 0){
	// 					$balance = $rowchild->checkTotal($filter,$branch);
	// 					$balancenow = $rowchild->checkTotalPL($filter,$branch);
					
	// 					if(substr($rowchild->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
	// 						$total_revenue_actual += $balance;
	// 						$total_revenue_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($rowchild->code,0,5) == '5.000' || substr($rowchild->code,0,5) == '6.000' || substr($rowchild->code,0,5) == '6.100'){
	// 						$total_cogs_actual += $balance;
	// 						$total_cogs_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($rowchild->code,0,5) == '6.200'){
	// 						$total_fixed_cost_actual += $balance;
	// 						$total_fixed_cost_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($rowchild->code,0,9) == '6.2100.02'){
	// 						$total_variable_cost_actual += $balance;
	// 						$total_variable_cost_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($rowchild->code,0,9) == '6.2100.03'){
	// 						$total_other_expenses_actual += $balance;
	// 						$total_other_expenses_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($rowchild->code,0,6) == '6.2200'){
	// 						$total_repair_expenses_actual += $balance;
	// 						$total_repair_expenses_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($rowchild->code,0,5) == '6.300' || substr($rowchild->code,0,5) == '6.400'){
	// 						$total_depreciation_actual += $balance;
	// 						$total_depreciation_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($rowchild->code,0,5) == '7.100'){
	// 						$total_other_income_actual += $balance;
	// 						$total_other_income_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($rowchild->code,0,5) == '7.200'){
	// 						$total_other_deduction_actual += $balance;
	// 						$total_other_deduction_actual_now += $balancenow;
	// 					}
	// 				}
					
	// 				foreach($rowchild->child() as $rowgrandchild){
	// 					if(count($rowgrandchild->child()) == 0){
	// 						$balance = $rowgrandchild->checkTotal($filter,$branch);
	// 						$balancenow = $rowgrandchild->checkTotalPL($filter,$branch);
					
	// 						if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
	// 							$total_revenue_actual += $balance;
	// 							$total_revenue_actual_now += $balancenow;
	// 						}
							
	// 						if(substr($rowgrandchild->code,0,5) == '5.000' || substr($rowgrandchild->code,0,5) == '6.000' || substr($rowgrandchild->code,0,5) == '6.100'){
	// 							$total_cogs_actual += $balance;
	// 							$total_cogs_actual_now += $balancenow;
	// 						}
							
	// 						if(substr($rowgrandchild->code,0,5) == '6.200'){
	// 							$total_fixed_cost_actual += $balance;
	// 							$total_fixed_cost_actual_now += $balancenow;
	// 						}
							
	// 						if(substr($rowgrandchild->code,0,9) == '6.2100.02'){
	// 							$total_variable_cost_actual += $balance;
	// 							$total_variable_cost_actual_now += $balancenow;
	// 						}
							
	// 						if(substr($rowgrandchild->code,0,9) == '6.2100.03'){
	// 							$total_other_expenses_actual += $balance;
	// 							$total_other_expenses_actual_now += $balancenow;
	// 						}
							
	// 						if(substr($rowgrandchild->code,0,6) == '6.2200'){
	// 							$total_repair_expenses_actual += $balance;
	// 							$total_repair_expenses_actual_now += $balancenow;
	// 						}
							
	// 						if(substr($rowgrandchild->code,0,5) == '6.300' || substr($rowgrandchild->code,0,5) == '6.400'){
	// 							$total_depreciation_actual += $balance;
	// 							$total_depreciation_actual_now += $balancenow;
	// 						}
							
	// 						if(substr($rowgrandchild->code,0,5) == '7.100'){
	// 							$total_other_income_actual += $balance;
	// 							$total_other_income_actual_now += $balancenow;
	// 						}
							
	// 						if(substr($rowgrandchild->code,0,5) == '7.200'){
	// 							$total_other_deduction_actual += $balance;
	// 							$total_other_deduction_actual_now += $balancenow;
	// 						}
					
	// 					}
						
	// 					foreach($rowgrandchild->child() as $rowgrandgrandchild){
	// 						if(count($rowgrandgrandchild->child()) == 0){
	// 							$balance = $rowgrandgrandchild->checkTotal($filter,$branch);
	// 							$balancenow = $rowgrandgrandchild->checkTotalPL($filter,$branch);
					
	// 							if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
	// 								$total_revenue_actual += $balance;
	// 								$total_revenue_actual_now += $balancenow;
	// 							}
								
	// 							if(substr($rowgrandgrandchild->code,0,5) == '5.000' || substr($rowgrandgrandchild->code,0,5) == '6.000' || substr($rowgrandgrandchild->code,0,5) == '6.100'){
	// 								$total_cogs_actual += $balance;
	// 								$total_cogs_actual_now += $balancenow;
	// 							}
								
	// 							if(substr($rowgrandgrandchild->code,0,5) == '6.200'){
	// 								$total_fixed_cost_actual += $balance;
	// 								$total_fixed_cost_actual_now += $balancenow;
	// 							}
								
	// 							if(substr($rowgrandgrandchild->code,0,9) == '6.2100.02'){
	// 								$total_variable_cost_actual += $balance;
	// 								$total_variable_cost_actual_now += $balancenow;
	// 							}
								
	// 							if(substr($rowgrandgrandchild->code,0,9) == '6.2100.03'){
	// 								$total_other_expenses_actual += $balance;
	// 								$total_other_expenses_actual_now += $balancenow;
	// 							}
								
	// 							if(substr($rowgrandgrandchild->code,0,6) == '6.2200'){
	// 								$total_repair_expenses_actual += $balance;
	// 								$total_repair_expenses_actual_now += $balancenow;
	// 							}
								
	// 							if(substr($rowgrandgrandchild->code,0,5) == '6.300' || substr($rowgrandgrandchild->code,0,5) == '6.400'){
	// 								$total_depreciation_actual += $balance;
	// 								$total_depreciation_actual_now += $balancenow;
	// 							}
								
	// 							if(substr($rowgrandgrandchild->code,0,5) == '7.100'){
	// 								$total_other_income_actual += $balance;
	// 								$total_other_income_actual_now += $balancenow;
	// 							}
								
	// 							if(substr($rowgrandgrandchild->code,0,5) == '7.200'){
	// 								$total_other_deduction_actual += $balance;
	// 								$total_other_deduction_actual_now += $balancenow;
	// 							}
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
		
	// 	$total_retained = $total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual + $total_other_income_actual - $total_other_deduction_actual;
		
	// 	$total_retained_now = $total_revenue_actual_now - $total_cogs_actual_now - $total_fixed_cost_actual_now - $total_variable_cost_actual_now - $total_other_expenses_actual_now - $total_repair_expenses_actual_now - $total_depreciation_actual_now + $total_other_income_actual_now - $total_other_deduction_actual_now;
		
	// 	if($branch){
	// 		self::updateRetainedEarning($filter,$branch,$total_retained_now);
	// 		// self::updateRetainedEarning($filter,$branch,$total_retained_now);
	// 	}else{
	// 		self::updateAllBranch($filter);
	// 	}
		
	// 	$jumlahfromcoa = Coa::find(94)->checkTotal($filter,$branch);
		
	// 	return $jumlahfromcoa;
	// }





   public static function retained_earning($filter, $branch)
   {
       // Fetch Coa data once outside the loop
       $coa = Coa::orderBy('code')->get();

   
       // Loop through months
       $currentMonth = '2021-10-31';
      //  while ($currentMonth <= $filter) {
           // Calculate totals for the current month if it's a valid month
           if (strlen($filter) == 7 || strlen($filter) == 10) {
               // Calculate totals for the current month
               $totals = self::calculateTotals2($coa, $filter, $branch);
   
               // Calculate retained earnings for the current month
                 $total_retained_now = $totals['revenue_actual_now'] - $totals['cogs_actual_now'] -
                  $totals['fixed_cost_actual_now'] - $totals['variable_cost_actual_now'] -
                  $totals['other_expenses_actual_now'] - $totals['repair_expenses_actual_now'] -
                  $totals['depreciation_actual_now'] + $totals['other_income_actual_now'] -
                  $totals['other_deduction_actual_now'];
   
               // Update retained earnings for the current month
               if ($branch) {
                   self::updateRetainedEarning($filter, $branch, $total_retained_now);
               } else {
                   self::updateAllBranch($filter, $total_retained_now);
               }
           }
   
           // Move to the next month
           if (strlen($filter) == 7) {
               $currentMonth = date("Y-m", strtotime("$currentMonth +1 month"));
           } elseif (strlen($filter) == 10) {
               $currentMonth = date("Y-m-d", strtotime("$currentMonth +1 month"));
           }
      //  }
   
       return Coa::find(94)->checkTotal($filter, $branch);
   }



   
   private static function calculateTotals2($coas, $filter, $branch)
   {
      $totals = [
         'revenue_actual' => 0,
         'cogs_actual' => 0,
         'fixed_cost_actual' => 0,
         'variable_cost_actual' => 0,
         'other_expenses_actual' => 0,
         'repair_expenses_actual' => 0,
         'depreciation_actual' => 0,
         'other_income_actual' => 0,
         'other_deduction_actual' => 0,
         'revenue_actual_now' => 0,
         'cogs_actual_now' => 0,
         'fixed_cost_actual_now' => 0,
         'variable_cost_actual_now' => 0,
         'other_expenses_actual_now' => 0,
         'repair_expenses_actual_now' => 0,
         'depreciation_actual_now' => 0,
         'other_income_actual_now' => 0,
         'other_deduction_actual_now' => 0,
      ];

      foreach ($coas as $coa) {
         $totals = self::calculateCOATotals2($coa, $filter, $branch, $totals);
      }

      return $totals;
   }

   private static function calculateCOATotals2($coa, $filter, $branch, $totals)
   {
   //  $balance = $coa->checkTotal($filter, $branch);
    $balancenow = $coa->checkTotalPL($filter, $branch);

    if (substr($coa->code, 0, 5) == '4.000' || substr($coa->code, 0, 5) == '4.100') {
      //   $totals['revenue_actual'] += $balance;
        $totals['revenue_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '5.000' || substr($coa->code, 0, 5) == '6.000' ||
        substr($coa->code, 0, 5) == '6.100') {
      //   $totals['cogs_actual'] += $balance;
        $totals['cogs_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '6.200') {
      //   $totals['fixed_cost_actual'] += $balance;
        $totals['fixed_cost_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 9) == '6.2100.02') {
      //   $totals['variable_cost_actual'] += $balance;
        $totals['variable_cost_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 9) == '6.2100.03') {
      //   $totals['other_expenses_actual'] += $balance;
        $totals['other_expenses_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 6) == '6.2200') {
      //   $totals['repair_expenses_actual'] += $balance;
        $totals['repair_expenses_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '6.300' || substr($coa->code, 0, 5) == '6.400') {
      //   $totals['depreciation_actual'] += $balance;
        $totals['depreciation_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '7.100') {
      //   $totals['other_income_actual'] += $balance;
        $totals['other_income_actual_now'] += $balancenow;
    }

    if (substr($coa->code, 0, 5) == '7.200') {
      //   $totals['other_deduction_actual'] += $balance;
        $totals['other_deduction_actual_now'] += $balancenow;
    }

    return $totals;
   }

   // private static function calculateCOATotals($coa, $filter_start_date, $filter_end_date, $branch, $totals)
   // {
   // //  $balanceprevious = $coa->checkTotalPLDateCF($filter_start_date, $filter_end_date, $branch)['total_balance_previous'];
   //  $balancenow = $coa->checkTotalPLDateCF($filter_start_date, $filter_end_date, $branch)['total_balance'];

   //  if (substr($coa->code, 0, 5) == '4.000' || substr($coa->code, 0, 5) == '4.100') {
   //    //   $totals['revenue_actual_previous'] += $balanceprevious;
   //      $totals['revenue_actual_now'] += $balancenow;
   //  }

   //  if (substr($coa->code, 0, 5) == '5.000' || substr($coa->code, 0, 5) == '6.000' ||
   //      substr($coa->code, 0, 5) == '6.100') {
   //    //   $totals['cogs_actual_previous'] += $balanceprevious;
   //      $totals['cogs_actual_now'] += $balancenow;
   //  }

   //  if (substr($coa->code, 0, 5) == '6.200') {
   //    //   $totals['fixed_cost_actual_previous'] += $balanceprevious;
   //      $totals['fixed_cost_actual_now'] += $balancenow;
   //  }

   //  if (substr($coa->code, 0, 9) == '6.2100.02') {
   //    //   $totals['variable_cost_actual_previous'] += $balanceprevious;
   //      $totals['variable_cost_actual_now'] += $balancenow;
   //  }

   //  if (substr($coa->code, 0, 9) == '6.2100.03') {
   //    //   $totals['other_expenses_actual_previous'] += $balanceprevious;
   //      $totals['other_expenses_actual_now'] += $balancenow;
   //  }

   //  if (substr($coa->code, 0, 6) == '6.2200') {
   //    //   $totals['repair_expenses_actual_previous'] += $balanceprevious;
   //      $totals['repair_expenses_actual_now'] += $balancenow;
   //  }

   //  if (substr($coa->code, 0, 5) == '6.300' || substr($coa->code, 0, 5) == '6.400') {
   //    //   $totals['depreciation_actual_previous'] += $balanceprevious;
   //      $totals['depreciation_actual_now'] += $balancenow;
   //  }

   //  if (substr($coa->code, 0, 5) == '7.100') {
   //    //   $totals['other_income_actual_previous'] += $balanceprevious;
   //      $totals['other_income_actual_now'] += $balancenow;
   //  }

   //  if (substr($coa->code, 0, 5) == '7.200') {
   //    //   $totals['other_deduction_actual_previous'] += $balanceprevious;
   //      $totals['other_deduction_actual_now'] += $balancenow;
   //  }

   //  return $totals;
   // }


 
   // STILL TESTING 2
   // private static function recursiveRetainedEarning($categories, $allCategories, $filter, $branch){
	// 	$total_revenue_actual = 0;
	// 	$total_cogs_actual = 0;	
	// 	$total_fixed_cost_actual = 0;
	// 	$total_variable_cost_actual = 0;
	// 	$total_other_expenses_actual = 0;
	// 	$total_repair_expenses_actual = 0;
	// 	$total_depreciation_actual = 0;
	// 	$total_other_income_actual = 0;
	// 	$total_other_deduction_actual = 0;
		
	// 	$total_revenue_actual_now = 0;
	// 	$total_cogs_actual_now = 0;	
	// 	$total_fixed_cost_actual_now = 0;
	// 	$total_variable_cost_actual_now = 0;
	// 	$total_other_expenses_actual_now = 0;
	// 	$total_repair_expenses_actual_now = 0;
	// 	$total_depreciation_actual_now = 0;
	// 	$total_other_income_actual_now = 0;
	// 	$total_other_deduction_actual_now = 0;

	// 	foreach ($categories as $category) {

	// 		if(substr($category->code,0,1) == '4' || substr($category->code,0,1) == '5' || substr($category->code,0,1) == '6' || substr($category->code,0,1) == '7'){

	// 			$category->children = $allCategories->where('parent_id', $category->id)->values();

	// 			if($category->children->isNotEmpty()){
	// 				self::recursiveRetainedEarning($category->children, $allCategories, $filter, $branch);
	// 			}else{
	// 					$balance = $category->checkTotal($filter,$branch);
	// 					$balancenow = $category->checkTotalPL($filter,$branch);
						
	// 					if(substr($category->code,0,5) == '4.000' || substr($category->code,0,5) == '4.100'){
	// 						$total_revenue_actual += $balance;
	// 						$total_revenue_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($category->code,0,5) == '5.000' || substr($category->code,0,5) == '6.000' || substr($category->code,0,5) == '6.100'){
	// 						$total_cogs_actual += $balance;
	// 						$total_cogs_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($category->code,0,5) == '6.200'){
	// 						$total_fixed_cost_actual += $balance;
	// 						$total_fixed_cost_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($category->code,0,9) == '6.2100.02'){
	// 						$total_variable_cost_actual += $balance;
	// 						$total_variable_cost_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($category->code,0,9) == '6.2100.03'){
	// 						$total_other_expenses_actual += $balance;
	// 						$total_other_expenses_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($category->code,0,6) == '6.2200'){
	// 						$total_repair_expenses_actual += $balance;
	// 						$total_repair_expenses_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($category->code,0,5) == '6.300' || substr($category->code,0,5) == '6.400'){
	// 						$total_depreciation_actual += $balance;
	// 						$total_depreciation_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($category->code,0,5) == '7.100'){
	// 						$total_other_income_actual += $balance;
	// 						$total_other_income_actual_now += $balancenow;
	// 					}
						
	// 					if(substr($category->code,0,5) == '7.200'){
	// 						$total_other_deduction_actual += $balance;
	// 						$total_other_deduction_actual_now += $balancenow;
	// 					}
	// 			}
	// 		}
			
	// 	}

   //    $total_retained = $total_revenue_actual - $total_cogs_actual - $total_fixed_cost_actual - $total_variable_cost_actual - $total_other_expenses_actual - $total_repair_expenses_actual - $total_depreciation_actual + $total_other_income_actual - $total_other_deduction_actual;
		
	// 	$total_retained_now = $total_revenue_actual_now - $total_cogs_actual_now - $total_fixed_cost_actual_now - $total_variable_cost_actual_now - $total_other_expenses_actual_now - $total_repair_expenses_actual_now - $total_depreciation_actual_now + $total_other_income_actual_now - $total_other_deduction_actual_now;

   //    return [
   //       'total_retained'      => $total_retained,
   //       'total_retained_now'  => $total_retained_now,
   //    ];
	// }
	
	private static function updateAllBranch($filter){
		self::retained_earning($filter,'1');
		self::retained_earning($filter,'2');
	}

   public static function reportBalanceSheet($filter)
   {
      $month     = date('m', strtotime($filter));
      $year      = date('Y', strtotime($filter));
      $where_raw = "YEAR(created_at) = '$year' AND MONTH(created_at) <= '$month'";

      $grandtotal_cash_bank             = 0;
      $grandtotal_receivable            = 0;
      $grandtotal_supply                = 0;
      $grandtotal_assets_facile         = 0;
      $grandtotal_assets_consistenly    = 0;
      $grandtotal_accumulated_shrinkage = 0;
      $grandtotal_debt                  = 0;
      $grandtotal_responbility          = 0;
      $grandtotal_equity                = 0;

      $petty_cash        = Coa::where('code', '1.000.01')->first();
      $petty_cash_sub    = Coa::where('parent_id', $petty_cash->id)->get();
      $petty_cash_result = [];
      foreach($petty_cash_sub as $pcs) {
         $balance_debit         = Journal::where('type','1')->where('coa_id', $pcs->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit        = Journal::where('type','2')->where('coa_id', $pcs->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance         = $balance_debit - $balance_credit;
         $grandtotal_cash_bank += $total_balance;

         $petty_cash_result[] = [
            'name'    => $pcs->name,
            'balance' => $total_balance
         ];
      }

      $big_cash        = Coa::where('code', '1.000.02')->first();
      $big_cash_sub    = Coa::where('parent_id', $big_cash->id)->get();
      $big_cash_result = [];
      foreach($big_cash_sub as $bcs) {
         $balance_debit         = Journal::where('type','1')->where('coa_id', $bcs->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit        = Journal::where('type','2')->where('coa_id', $bcs->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance         = $balance_debit - $balance_credit;
         $grandtotal_cash_bank += $total_balance;

         $big_cash_result[] = [
            'name'    => $bcs->name,
            'balance' => $total_balance
         ];
      }

      $bank_sby        = Coa::where('code', '1.000.03.01')->first();
      $bank_sby_sub    = Coa::where('parent_id', $bank_sby->id)->get();
      $bank_sby_result = [];
      foreach($bank_sby_sub as $bss) {
         $balance_debit         = Journal::where('type','1')->where('coa_id', $bss->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit        = Journal::where('type','2')->where('coa_id', $bss->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance         = $balance_debit - $balance_credit;
         $grandtotal_cash_bank += $total_balance;

         $bank_sby_result[] = [
            'name'    => $bss->name,
            'balance' => $total_balance
         ];
      }

      $bank_jkt        = Coa::where('code', '1.000.03.02')->first();
      $bank_jkt_sub    = Coa::where('parent_id', $bank_jkt->id)->get();
      $bank_jkt_result = [];
      foreach($bank_jkt_sub as $bjs) {
         $balance_debit         = Journal::where('type','1')->where('coa_id', $bjs->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit        = Journal::where('type','2')->where('coa_id', $bjs->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance         = $balance_debit - $balance_credit;
         $grandtotal_cash_bank += $total_balance;

         $bank_jkt_result[] = [
            'name'    => $bjs->name,
            'balance' => $total_balance
         ];
      }

      $dp_purchase        = Coa::where('code', '1.100.01')->first();
      $dp_purchase_sub    = Coa::where('parent_id', $dp_purchase->id)->get();
      $dp_purchase_result = [];
      foreach($dp_purchase_sub as $dps) {
         $balance_debit          = Journal::where('type','1')->where('coa_id', $dps->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit         = Journal::where('type','2')->where('coa_id', $dps->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance          = $balance_debit - $balance_credit;
         $grandtotal_receivable += $total_balance;

         $dp_purchase_result[] = [
            'name'    => $dps->name,
            'balance' => $total_balance
         ];
      }

      $receivable_effort        = Coa::where('code', '1.100.02')->first();
      $receivable_effort_sub    = Coa::where('parent_id', $receivable_effort->id)->get();
      $receivable_effort_result = [];
      foreach($receivable_effort_sub as $res) {
         $balance_debit          = Journal::where('type','1')->where('coa_id', $res->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit         = Journal::where('type','2')->where('coa_id', $res->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance          = $balance_debit - $balance_credit;
         $grandtotal_receivable += $total_balance;

         $receivable_effort_result[] = [
            'name'    => $res->name,
            'balance' => $total_balance
         ];
      }

      $advance_purchase        = Coa::where('code', '1.100.03')->first();
      $advance_purchase_debit  = Journal::where('type','1')->where('coa_id', $advance_purchase->id)->whereRaw($where_raw)->sum('nominal');
      $advance_purchase_credit = Journal::where('type','2')->where('coa_id', $advance_purchase->id)->whereRaw($where_raw)->sum('nominal');
      $total_advance_purchase  = $advance_purchase_debit - $advance_purchase_credit;
      $grandtotal_receivable  += $total_advance_purchase;

      $owner_ledger           = Coa::where('code', '1.100.04')->first();
      $owner_ledger_debit     = Journal::where('type','1')->where('coa_id', $owner_ledger->id)->whereRaw($where_raw)->sum('nominal');
      $owner_ledger_credit    = Journal::where('type','2')->where('coa_id', $owner_ledger->id)->whereRaw($where_raw)->sum('nominal');
      $total_owner_ledger     = $owner_ledger_debit - $owner_ledger_credit;
      $grandtotal_receivable += $total_owner_ledger;

      $employee_ledger        = Coa::where('code', '1.100.05')->first();
      $employee_ledger_debit  = Journal::where('type','1')->where('coa_id', $employee_ledger->id)->whereRaw($where_raw)->sum('nominal');
      $employee_ledger_credit = Journal::where('type','2')->where('coa_id', $employee_ledger->id)->whereRaw($where_raw)->sum('nominal');
      $total_employee_ledger  = $employee_ledger_debit - $employee_ledger_credit;
      $grandtotal_receivable += $total_employee_ledger;

      $holding_company_ledger        = Coa::where('code', '1.100.06')->first();
      $holding_company_ledger_debit  = Journal::where('type','1')->where('coa_id', $holding_company_ledger->id)->whereRaw($where_raw)->sum('nominal');
      $holding_company_ledger_credit = Journal::where('type','2')->where('coa_id', $holding_company_ledger->id)->whereRaw($where_raw)->sum('nominal');
      $total_holding_company_ledger  = $holding_company_ledger_debit - $holding_company_ledger_credit;
      $grandtotal_receivable        += $total_holding_company_ledger;
      
      $supply_item_sby        = Coa::where('code', '1.200.01')->first();
      $supply_item_sby_sub    = Coa::where('parent_id', $supply_item_sby->id)->get();
      $supply_item_sby_result = [];
      foreach($supply_item_sby_sub as $siss) {
         $balance_debit      = Journal::where('type','1')->where('coa_id', $siss->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit     = Journal::where('type','2')->where('coa_id', $siss->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance      = $balance_debit - $balance_credit;
         $grandtotal_supply += $total_balance;

         $supply_item_sby_result[] = [
            'name'    => $siss->name,
            'balance' => $total_balance
         ];
      }

      $supply_item_jkt        = Coa::where('code', '1.200.02')->first();
      $supply_item_jkt_sub    = Coa::where('parent_id', $supply_item_jkt->id)->get();
      $supply_item_jkt_result = [];
      foreach($supply_item_jkt_sub as $sijs) {
         $balance_debit      = Journal::where('type','1')->where('coa_id', $sijs->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit     = Journal::where('type','2')->where('coa_id', $sijs->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance      = $balance_debit - $balance_credit;
         $grandtotal_supply += $total_balance;

         $supply_item_jkt_result[] = [
            'name'    => $sijs->name,
            'balance' => $total_balance
         ];
      }

      $sent_item          = Coa::where('code', '1.201.00')->first();
      $sent_item_debit    = Journal::where('type','1')->where('coa_id', $sent_item->id)->whereRaw($where_raw)->sum('nominal');
      $sent_item_credit   = Journal::where('type','2')->where('coa_id', $sent_item->id)->whereRaw($where_raw)->sum('nominal');
      $total_sent_item    = $sent_item_debit - $sent_item_credit;
      $grandtotal_supply += $total_sent_item;

      $fee_dp        = Coa::where('code', '1.400.00')->first();
      $fee_dp_sub    = Coa::where('parent_id', $fee_dp->id)->get();
      $fee_dp_result = [];
      foreach($fee_dp_sub as $fds) {
         $balance_debit             = Journal::where('type','1')->where('coa_id', $fds->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit            = Journal::where('type','2')->where('coa_id', $fds->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance             = $balance_debit - $balance_credit;
         $grandtotal_assets_facile += $total_balance;

         $fee_dp_result[] = [
            'name'    => $fds->name,
            'balance' => $total_balance
         ];
      }

      $prepaid_tax        = Coa::where('code', '1.500.00')->first();
      $prepaid_tax_sub    = Coa::where('parent_id', $prepaid_tax->id)->get();
      $prepaid_tax_result = [];
      foreach($prepaid_tax_sub as $pts) {
         $balance_debit             = Journal::where('type','1')->where('coa_id', $pts->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit            = Journal::where('type','2')->where('coa_id', $pts->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance             = $balance_debit - $balance_credit;
         $grandtotal_assets_facile += $total_balance;

         $prepaid_tax_result[] = [
            'name'    => $pts->name,
            'balance' => $total_balance
         ];
      }

      $assets_consistenly        = Coa::where('code', '1.600.00')->first();
      $assets_consistenly_sub    = Coa::where('parent_id', $assets_consistenly->id)->get();
      $assets_consistenly_result = [];
      foreach($assets_consistenly_sub as $acs) {
         $balance_debit                  = Journal::where('type','1')->where('coa_id', $acs->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit                 = Journal::where('type','2')->where('coa_id', $acs->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance                  = $balance_debit - $balance_credit;
         $grandtotal_assets_consistenly += $total_balance;

         $assets_consistenly_result[] = [
            'name'    => $acs->name,
            'balance' => $total_balance
         ];
      }

      $accumulated_shrinkage        = Coa::where('code', '1.610.00')->first();
      $accumulated_shrinkage_sub    = Coa::where('parent_id', $accumulated_shrinkage->id)->get();
      $accumulated_shrinkage_result = [];
      foreach($accumulated_shrinkage_sub as $ass) {
         $balance_debit                     = Journal::where('type','1')->where('coa_id', $ass->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit                    = Journal::where('type','2')->where('coa_id', $ass->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance                     = $balance_debit - $balance_credit;
         $grandtotal_accumulated_shrinkage += $total_balance;

         $accumulated_shrinkage_result[] = [
            'name'    => $ass->name,
            'balance' => $total_balance
         ];
      }

      $dp_sale        = Coa::where('code', '2.000.01')->first();
      $dp_sale_sub    = Coa::where('parent_id', $dp_sale->id)->get();
      $dp_sale_result = [];
      foreach($dp_sale_sub as $dss) {
         $balance_debit    = Journal::where('type','1')->where('coa_id', $dss->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit   = Journal::where('type','2')->where('coa_id', $dss->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance    = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
         $grandtotal_debt += $total_balance;

         $dp_sale_result[] = [
            'name'    => $dss->name,
            'balance' => $total_balance
         ];
      }

      $debt_business        = Coa::where('code', '2.200.00')->first();
      $debt_business_sub    = Coa::where('parent_id', $debt_business->id)->get();
      $debt_business_result = [];
      foreach($debt_business_sub as $dbs) {
         $balance_debit    = Journal::where('type','1')->where('coa_id', $dbs->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit   = Journal::where('type','2')->where('coa_id', $dbs->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance    = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
         $grandtotal_debt += $total_balance;

         $debt_business_result[] = [
            'name'    => $dbs->name,
            'balance' => $total_balance
         ];
      }

      $owner_loan         = Coa::where('code', '2.210.00')->first();
      $owner_loan_debit   = Journal::where('type','1')->where('coa_id', $owner_loan->id)->whereRaw($where_raw)->sum('nominal');
      $owner_loan_credit  = Journal::where('type','2')->where('coa_id', $owner_loan->id)->whereRaw($where_raw)->sum('nominal');
      $total_owner_loan   = $owner_loan_debit - $owner_loan_credit > 0 ? ($owner_loan_debit - $owner_loan_credit) * -1 : abs($owner_loan_debit - $owner_loan_credit);
      $grandtotal_equity += $total_owner_loan;

      $bank_loan          = Coa::where('code', '2.211.00')->first();
      $bank_loan_debit    = Journal::where('type','1')->where('coa_id', $bank_loan->id)->whereRaw($where_raw)->sum('nominal');
      $bank_loan_credit   = Journal::where('type','2')->where('coa_id', $bank_loan->id)->whereRaw($where_raw)->sum('nominal');
      $total_bank_loan    = $bank_loan_debit - $bank_loan_credit > 0 ? ($bank_loan_debit - $bank_loan_credit) * -1 : abs($bank_loan_debit - $bank_loan_credit);
      $grandtotal_equity += $total_bank_loan;

      $vehichle_loan        = Coa::where('code', '2.212.00')->first();
      $vehichle_loan_debit  = Journal::where('type','1')->where('coa_id', $vehichle_loan->id)->whereRaw($where_raw)->sum('nominal');
      $vehichle_loan_credit = Journal::where('type','2')->where('coa_id', $vehichle_loan->id)->whereRaw($where_raw)->sum('nominal');
      $total_vehichle_loan  = $vehichle_loan_debit - $vehichle_loan_credit > 0 ? ($vehichle_loan_debit - $vehichle_loan_credit) * -1 : abs($vehichle_loan_debit - $vehichle_loan_credit);
      $grandtotal_equity   += $total_vehichle_loan;

      $holding_company_loan        = Coa::where('code', '2.213.00')->first();
      $holding_company_loan_debit  = Journal::where('type','1')->where('coa_id', $holding_company_loan->id)->whereRaw($where_raw)->sum('nominal');
      $holding_company_loan_credit = Journal::where('type','2')->where('coa_id', $holding_company_loan->id)->whereRaw($where_raw)->sum('nominal');
      $total_holding_company_loan  = $holding_company_loan_debit - $holding_company_loan_credit > 0 ? ($holding_company_loan_debit - $holding_company_loan_credit) * -1 : abs($holding_company_loan_debit - $holding_company_loan_credit);
      $grandtotal_equity          += $total_holding_company_loan;

      $tax        = Coa::where('code', '2.100.00')->first();
      $tax_sub    = Coa::where('parent_id', $tax->id)->get();
      $tax_result = [];
      foreach($tax_sub as $ts) {
         $balance_debit            = Journal::where('type','1')->where('coa_id', $ts->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit           = Journal::where('type','2')->where('coa_id', $ts->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance            = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
         $grandtotal_responbility += $total_balance;

         $tax_result[] = [
            'name'    => $ts->name,
            'balance' => $total_balance
         ];
      }

      $other_payable        = Coa::where('code', '2.300.00')->first();
      $other_payable_sub    = Coa::where('parent_id', $other_payable->id)->get();
      $other_payable_result = [];
      foreach($other_payable_sub as $ops) {
         $balance_debit            = Journal::where('type','1')->where('coa_id', $ops->id)->whereRaw($where_raw)->sum('nominal');
         $balance_credit           = Journal::where('type','2')->where('coa_id', $ops->id)->whereRaw($where_raw)->sum('nominal');
         $total_balance            = $balance_debit - $balance_credit > 0 ? ($balance_debit - $balance_credit) * -1 : abs($balance_debit - $balance_credit);
         $grandtotal_responbility += $total_balance;

         $other_payable_result[] = [
            'name'    => $ops->name,
            'balance' => $total_balance
         ];
      }

      $debt_purchase            = Coa::where('code', '2.400.00')->first();
      $debt_purchase_debit      = Journal::where('type','1')->where('coa_id', $debt_purchase->id)->whereRaw($where_raw)->sum('nominal');
      $debt_purchase_credit     = Journal::where('type','2')->where('coa_id', $debt_purchase->id)->whereRaw($where_raw)->sum('nominal');
      $total_debt_purchase      = $debt_purchase_debit - $debt_purchase_credit > 0 ? ($debt_purchase_debit - $debt_purchase_credit) * -1 : abs($debt_purchase_debit - $debt_purchase_credit);
      $grandtotal_responbility += $total_debt_purchase;

      $capital            = Coa::where('code', '3.000.00')->first();
      $capital_debit      = Journal::where('type','1')->where('coa_id', $capital->id)->whereRaw($where_raw)->sum('nominal');
      $capital_credit     = Journal::where('type','2')->where('coa_id', $capital->id)->whereRaw($where_raw)->sum('nominal');
      $total_capital      = $capital_debit - $capital_credit > 0 ? ($capital_debit - $capital_credit) * -1 : abs($capital_debit - $capital_credit);
      $grandtotal_equity += $total_capital;

      $opening_balance        = Coa::where('code', '3.100.00')->first();
      $opening_balance_debit  = Journal::where('type','1')->where('coa_id', $opening_balance->id)->whereRaw($where_raw)->sum('nominal');
      $opening_balance_credit = Journal::where('type','2')->where('coa_id', $opening_balance->id)->whereRaw($where_raw)->sum('nominal');
      $total_opening_balance  = $opening_balance_debit - $opening_balance_credit > 0 ? ($opening_balance_debit - $opening_balance_credit) * -1 : abs($opening_balance_debit - $opening_balance_credit);
      $grandtotal_equity     += $total_opening_balance;

      $deviden            = Coa::where('code', '3.200.00')->first();
      $deviden_debit      = Journal::where('type','1')->where('coa_id', $deviden->id)->whereRaw($where_raw)->sum('nominal');
      $deviden_credit     = Journal::where('type','2')->where('coa_id', $deviden->id)->whereRaw($where_raw)->sum('nominal');
      $total_deviden      = $deviden_debit - $deviden_credit > 0 ? ($deviden_debit - $deviden_credit) * -1 : abs($deviden_debit - $deviden_credit);
      $grandtotal_equity += $total_deviden;

      $retained_earning         = self::profitLossSummary($filter, '<=');
      $total_retained_earning   = $retained_earning['grandtotal']['nett']['actual']['current']['nominal'];
      $grandtotal_equity       += $total_retained_earning;

      $result = [
         'assets' => [
            'cash_bank' => [
               [
                  'name'    => $petty_cash->name,
                  'balance' => null,
                  'sub'     => $petty_cash_result
               ],
               [
                  'name'    => $big_cash->name,
                  'balance' => null,
                  'sub'     => $big_cash_result
               ],
               [
                  'name'    => $bank_sby->name,
                  'balance' => null,
                  'sub'     => $bank_sby_result
               ],
               [
                  'name'    => $bank_jkt->name,
                  'balance' => null,
                  'sub'     => $bank_jkt_result
               ]
            ],
            'receivable' => [
               [
                  'name'    => $dp_purchase->name,
                  'balance' => null,
                  'sub'     => $dp_purchase_result
               ],
               [
                  'name'    => $receivable_effort->name,
                  'balance' => null,
                  'sub'     => $receivable_effort_result
               ],
               [
                  'name'    => $advance_purchase->name,
                  'balance' => $total_advance_purchase,
                  'sub'     => []
               ],
               [
                  'name'    => $owner_ledger->name,
                  'balance' => $total_owner_ledger,
                  'sub'     => []
               ],
               [
                  'name'    => $employee_ledger->name,
                  'balance' => $total_employee_ledger,
                  'sub'     => []
               ],
               [
                  'name'    => $holding_company_ledger->name,
                  'balance' => $total_holding_company_ledger,
                  'sub'     => []
               ]
            ],
            'supply' => [
               [
                  'name'    => $supply_item_sby->name,
                  'balance' => null,
                  'sub'     => $supply_item_sby_result
               ],
               [
                  'name'    => $supply_item_jkt->name,
                  'balance' => null,
                  'sub'     => $supply_item_jkt_result
               ],
               [
                  'name'    => $sent_item->name,
                  'balance' => $total_sent_item,
                  'sub'     => []
               ]
            ],
            'assets_facile' => [
               [
                  'name'    => $fee_dp->name,
                  'balance' => null,
                  'sub'     => $fee_dp_result
               ],
               [
                  'name'    => $prepaid_tax->name,
                  'balance' => null,
                  'sub'     => $prepaid_tax_result
               ]
            ],
            'assets_consistenly' => [
               [
                  'name'    => $assets_consistenly->name,
                  'balance' => null,
                  'sub'     => $assets_consistenly_result
               ]
            ],
            'accumulated_shrinkage' => [
               [
                  'name'    => $accumulated_shrinkage->name,
                  'balance' => null,
                  'sub'     => $accumulated_shrinkage_result
               ]
            ],
            'total' => [
               'total_cash_bank'             => $grandtotal_cash_bank,
               'total_receivable'            => $grandtotal_receivable,
               'total_supply'                => $grandtotal_supply,
               'total_assets_facile'         => $grandtotal_assets_facile,
               'total_assets_consistenly'    => $grandtotal_assets_consistenly,
               'total_accumulated_shrinkage' => $grandtotal_accumulated_shrinkage
            ]
         ],
         'responbility_equity' => [
            'debt' => [
               [
                  'name'    => $dp_sale->name,
                  'balance' => null,
                  'sub'     => $dp_sale_result
               ],
               [
                  'name'    => $debt_business->name,
                  'balance' => null,
                  'sub'     => $debt_business_result
               ],
               [
                  'name'    => $owner_loan->name,
                  'balance' => $total_owner_loan,
                  'sub'     => []
               ],
               [
                  'name'    => $bank_loan->name,
                  'balance' => $total_bank_loan,
                  'sub'     => []
               ],
               [
                  'name'    => $vehichle_loan->name,
                  'balance' => $total_vehichle_loan,
                  'sub'     => []
               ],
               [
                  'name'    => $holding_company_loan->name,
                  'balance' => $total_holding_company_loan,
                  'sub'     => []
               ]
            ],
            'responbility' => [
               [
                  'name'    => $tax->name,
                  'balance' => null,
                  'sub'     => $tax_result
               ],
               [
                  'name'    => $other_payable->name,
                  'balance' => null,
                  'sub'     => $other_payable_result
               ],
               [
                  'name'    => $debt_purchase->name,
                  'balance' => $total_debt_purchase,
                  'sub'     => []
               ]
            ],
            'equity' => [
               [
                  'name'    => $capital->name,
                  'balance' => $total_capital,
                  'sub'     => []
               ],
               [
                  'name'    => $opening_balance->name,
                  'balance' => $total_opening_balance,
                  'sub'     => []
               ],
               [
                  'name'    => $deviden->name,
                  'balance' => $total_deviden,
                  'sub'     => []
               ],
               [
                  'name'    => 'Retairned Earning',
                  'balance' => $total_retained_earning,
                  'sub'     => []
               ]
            ],
            'total' => [
               'total_debt'         => $grandtotal_debt,
               'total_responbility' => $grandtotal_responbility,
               'total_equity'       => $grandtotal_equity
            ]
         ]
      ];

      return $result;
   }

   public static function reportProfitLoss($filter)
   {
      return [
         'summary'       => self::profitLossSummary($filter),
         'surabaya'      => self::profitLossSurabaya($filter),
         'jakarta'       => self::profitLossJakarta($filter),
         'non_operation' => self::profitLossNonOperation($filter)
      ];
   }
   
   public static function reportProfitLossAnother($filter,$branch)
   {
      return self::profitLoss($filter,$branch);
   }
   
   private static function profitLoss($filter,$branch)
   {
		$month     = date('m', strtotime($filter));
		$year      = date('Y', strtotime($filter));
		$where_raw = "YEAR(created_at) = '$year' AND MONTH(created_at) $expression '$month'";
		
		$income_actual   		= 0;
		$income_budget          = 0;
		$income_variance 		= 0;
		$cogs_actual     		= 0;
		$cogs_budget            = 0;
		$cogs_variance   		= 0;
		$fee_actual      		= 0;
		$fee_budget             = 0;
		$fee_variance		    = 0;
		$nett_actual    		= 0;
		$nett_budget            = 0;
		
   }

   private static function profitLossSummary($filter, $expression = '=')
   {
      $month_current     = date('m', strtotime($filter));
      $year_current      = date('Y', strtotime($filter));
      $where_raw_current = "YEAR(created_at) = '$year_current' AND MONTH(created_at) $expression '$month_current'";
      $month_last        = date('m', strtotime('-1 months', strtotime($filter)));
      $year_last         = date('Y', strtotime('-1 months', strtotime($filter)));
      $where_raw_last    = "YEAR(created_at) = '$year_last' AND MONTH(created_at) $expression '$month_last'";

      $income_actual_current   = 0;
      $income_actual_last      = 0;
      $income_budget           = 0;
      $income_variance_current = 0;
      $income_variance_last    = 0;
      $cogs_actual_current     = 0;
      $cogs_actual_last        = 0;
      $cogs_budget             = 0;
      $cogs_variance_current   = 0;
      $cogs_variance_last      = 0;
      $fee_actual_current      = 0;
      $fee_actual_last         = 0;
      $fee_budget              = 0;
      $fee_variance_current    = 0;
      $fee_variance_last       = 0;
      $nett_actual_current     = 0;
      $nett_actual_last        = 0;
      $nett_budget             = 0;

      $sale        = Coa::whereIn('code', ['4.000.01'])->get();
      $sale_result = [];
      foreach($sale as $ss) {
         $sale     = Coa::find($ss->id);
         $sale_sub = Coa::where('parent_id', $sale->id)->orderBy('code', 'asc')->get();
         foreach($sale_sub as $ss) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $ss->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$ss->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $ss->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $income_actual_current   += $total_balance_current;
            $income_actual_last      += $total_balance_last;
            $income_budget           += $budget_nominal;
            $income_variance_current += $variance_current;
            $income_variance_last    += $variance_last;

            $sale_result[] = [
               'name'     => $ss->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $sale_service        = Coa::whereIn('code', ['4.000.02'])->get();
      $sale_service_result = [];
      foreach($sale_service as $sss) {
         $sale     = Coa::find($sss->id);
         $sale_sub = Coa::where('parent_id', $sale->id)->orderBy('code', 'asc')->get();
         foreach($sale_sub as $ss) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $ss->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$ss->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $ss->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $income_actual_current   += $total_balance_current;
            $income_actual_last      += $total_balance_last;
            $income_budget           += $budget_nominal;
            $income_variance_current += $variance_current;
            $income_variance_last    += $variance_last;

            $sale_service_result[] = [
               'name'     => $ss->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $cogs        = Coa::whereIn('code', ['5.000.00'])->get();
      $cogs_result = [];
      foreach($cogs as $sc) {
         $cogs     = Coa::find($sc->id);
         $cogs_sub = Coa::where('id', $cogs->id)->orderBy('code', 'asc')->get();
         foreach($cogs_sub as $cs) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $cs->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$cs->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $cs->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $cogs_actual_current   += $total_balance_current;
            $cogs_actual_last      += $total_balance_last;
            $cogs_budget           += $budget_nominal;
            $cogs_variance_current += $variance_current;
            $cogs_variance_last    += $variance_last;

            $cogs_result[] = [
               'name'     => $cs->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $salary_wages        = Coa::whereIn('code', ['6.200.01'])->get();
      $salary_wages_result = [];
      foreach($salary_wages as $ssw) {
         $salary_wages     = Coa::find($ssw->id);
         $salary_wages_sub = Coa::where('parent_id', $salary_wages->id)->orderBy('code', 'asc')->get();
         foreach($salary_wages_sub as $sws) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $sws->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$sws->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $sws->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $salary_wages_result[] = [
               'name'     => $sws->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_marketing        = Coa::whereIn('code', ['6.100.00'])->get();
      $fee_marketing_result = [];
      foreach($fee_marketing as $sfm) {
         $fee_marketing     = Coa::find($sfm->id);
         $fee_marketing_sub = Coa::where('parent_id', $fee_marketing->id)->orderBy('code', 'asc')->get();
         foreach($fee_marketing_sub as $fms) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fms->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fms->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $fms->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_marketing_result[] = [
               'name'     => $fms->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_other        = Coa::whereIn('code', ['6.2100.01'])->get();
      $fee_other_result = [];
      foreach($fee_other as $sfo) {
         $fee_other     = Coa::find($sfo->id);
         $fee_other_sub = Coa::where('parent_id', $fee_other->id)->orderBy('code', 'asc')->get();
         foreach($fee_other_sub as $fos) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fos->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fos->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $fos->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_other_result[] = [
               'name'     => $fos->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_maintenance        = Coa::whereIn('code', ['6.2200.01'])->get();
      $fee_maintenance_result = [];
      foreach($fee_maintenance as $sfm) {
         $fee_maintenance     = Coa::find($sfm->id);
         $fee_maintenance_sub = Coa::where('parent_id', $fee_maintenance->id)->orderBy('code', 'asc')->get();
         foreach($fee_maintenance_sub as $fms) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fms->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fms->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $fms->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_maintenance_result[] = [
               'name'     => $fms->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_shrinkage        = Coa::whereIn('code', ['6.300.00'])->get();
      $fee_shrinkage_result = [];
      foreach($fee_shrinkage as $fs) {
         $fee_shrinkage     = Coa::find($fs->id);
         $fee_shrinkage_sub = Coa::where('parent_id', $fee_shrinkage->id)->orderBy('code', 'asc')->get();
         foreach($fee_shrinkage_sub as $fss) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fss->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fss->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $fss->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $nett_actual_current += $total_balance_current;
            $nett_actual_last    += $total_balance_last;
            $nett_budget         += $budget_nominal;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_shrinkage_result[] = [
               'name'     => $fss->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_outside                    = Coa::where('code', '7.200.00')->first();
      $fee_outside_sub_1              = collect(Coa::select('id')->where('parent_id', $fee_outside->id)->get()->toArray());
      $fee_outside_sub_2              = collect(Coa::select('id')->whereIn('parent_id', $fee_outside_sub_1->flatten())->get()->toArray());
      $fee_outside_sub_3              = collect(Coa::select('id')->whereIn('parent_id', $fee_outside_sub_2->flatten())->get()->toArray());
      $fee_outside_merge              = $fee_outside_sub_1->merge($fee_outside_sub_2->merge($fee_outside_sub_3->merge([$fee_outside->id])));
      $fee_outside_debit_current      = Journal::where('type','1')->whereIn('coa_id', $fee_outside_merge->flatten())->whereRaw($where_raw_current)->sum('nominal');
      $fee_outside_credit_current     = Journal::where('type','2')->whereIn('coa_id', $fee_outside_merge->flatten())->whereRaw($where_raw_current)->sum('nominal');
      $total_fee_outside_current      = abs($fee_outside_debit_current - $fee_outside_credit_current);
      $fee_outside_debit_last         = Journal::where('type','1')->whereIn('coa_id', $fee_outside_merge->flatten())->whereRaw($where_raw_last)->sum('nominal');
      $fee_outside_credit_last        = Journal::where('type','2')->whereIn('coa_id', $fee_outside_merge->flatten())->whereRaw($where_raw_last)->sum('nominal');
      $total_fee_outside_last         = abs($fee_outside_debit_last - $fee_outside_credit_last);
      $fee_outside_budget             = Budgeting::where('month', $filter)->whereIn('coa_id', $fee_outside_merge)->orderByDesc('id')->limit(1)->get();
      $fee_outside_budget_nominal     = $fee_outside_budget->count() > 0 ? $fee_outside_budget[0]->nominal : 0;
      $fee_outside_variance_current   = $total_fee_outside_current - $fee_outside_budget_nominal;
      $fee_outside_variance_last      = $total_fee_outside_current - $total_fee_outside_last;

      $income_outside                   = Coa::where('code', '7.100.00')->first();
      $income_outside_sub_1             = collect(Coa::select('id')->where('parent_id', $income_outside->id)->get()->toArray());
      $income_outside_sub_2             = collect(Coa::select('id')->whereIn('parent_id', $income_outside_sub_1->flatten())->get()->toArray());
      $income_outside_sub_3             = collect(Coa::select('id')->whereIn('parent_id', $income_outside_sub_2->flatten())->get()->toArray());
      $income_outside_merge             = $income_outside_sub_1->merge($income_outside_sub_2->merge($income_outside_sub_3->merge([$income_outside->id])));
      $income_outside_debit_current     = Journal::where('type','1')->whereIn('coa_id', $income_outside_merge->flatten())->whereRaw($where_raw_current)->sum('nominal');
      $income_outside_credit_current    = Journal::where('type','2')->whereIn('coa_id', $income_outside_merge->flatten())->whereRaw($where_raw_current)->sum('nominal');
      $total_income_outside_current     = abs($income_outside_debit_current - $income_outside_credit_current);
      $income_outside_debit_last        = Journal::where('type','1')->whereIn('coa_id', $income_outside_merge->flatten())->whereRaw($where_raw_last)->sum('nominal');
      $income_outside_credit_last       = Journal::where('type','2')->whereIn('coa_id', $income_outside_merge->flatten())->whereRaw($where_raw_last)->sum('nominal');
      $total_income_outside_last        = abs($income_outside_debit_last - $income_outside_credit_last);
      $income_outside_budget            = Budgeting::where('month', $filter)->whereIn('coa_id', $income_outside_merge)->orderByDesc('id')->limit(1)->get();
      $income_outside_budget_nominal    = $income_outside_budget->count() > 0 ? $income_outside_budget[0]->nominal : 0;
      $income_outside_variance_current  = $total_income_outside_current - $income_outside_budget_nominal;
      $income_outside_variance_last     = $total_income_outside_current - $total_income_outside_last;

      $nett_actual_current += $total_fee_outside_current - $total_income_outside_current;
      $nett_actual_last    += $total_fee_outside_last - $total_income_outside_last;
      $nett_budget         += $fee_outside_budget_nominal - $income_outside_budget_nominal;

      $fee_income_outside_result = [
         [
            'name' => $income_outside->name,
            'actual' => [
               'nominal' => [
                  'current' => $total_income_outside_current, 
                  'last'    => $total_income_outside_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_income_outside_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_income_outside_last / $income_actual_last) * 100) : 0
               ]
            ],
            'budget' => [
               'nominal' => $income_outside_budget_nominal,
               'percent' => ($income_budget > 0) ? round(($income_outside_budget_nominal / $income_budget) * 100) : 0
            ],
            'variance' => [
               'nominal' => [
                  'current' => $income_outside_variance_current, 
                  'last'    => $income_outside_variance_last
               ],
               'percent' => [
                  'current' => ($income_outside_budget_nominal > 0) ? round(($income_outside_variance_current / $income_outside_budget_nominal) * 100) : 0,
                  'last'    => ($total_income_outside_last > 0) ? round(($income_outside_variance_last / $total_income_outside_last) * 100) : 0
               ]
            ]
         ],
         [
            'name' => $fee_outside->name,
            'actual' => [
               'nominal' => [
                  'current' => $total_fee_outside_current, 
                  'last'    => $total_fee_outside_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_fee_outside_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_fee_outside_last / $income_actual_last) * 100) : 0
               ]
            ],
            'budget' => [
               'nominal' => $fee_outside_budget_nominal,
               'percent' => ($income_budget > 0) ? round(($fee_outside_budget_nominal / $income_budget) * 100) : 0
            ],
            'variance' => [
               'nominal' => [
                  'current' => $fee_outside_variance_current, 
                  'last'    => $fee_outside_variance_last
               ],
               'percent' => [
                  'current' => ($fee_outside_budget_nominal > 0) ? round(($fee_outside_variance_current / $fee_outside_budget_nominal) * 100) : 0,
                  'last'    => ($total_fee_outside_last > 0) ? round(($fee_outside_variance_last / $total_fee_outside_last) * 100) : 0
               ]
            ]
         ]
      ];

      $total = [
         'income' => [
            'budget'   => $income_budget,
            'actual'   => ['current' => $income_actual_current, 'last' => $income_actual_last],
            'variance' => ['current' => $income_variance_current, 'last' => $income_variance_last]
         ],
         'cogs' => [
            'budget'   => $cogs_budget,
            'actual'   => ['current' => $cogs_actual_current, 'last' => $cogs_actual_last],
            'variance' => ['current' => $cogs_variance_current, 'last' => $cogs_variance_last]
         ],
         'fee' => [
            'budget'   => $fee_budget,
            'actual'   => ['current' => $fee_actual_current, 'last' => $fee_actual_last],
            'variance' => ['current' => $fee_variance_current, 'last' => $fee_variance_last]
         ],
      ];

      $gross_actual_nominal_current   = $income_actual_current - $cogs_actual_current - $fee_actual_current;
      $gross_actual_nominal_last      = $income_actual_last - $cogs_actual_last - $fee_actual_last;
      $gross_actual_percent_current   = 0;
      $gross_actual_percent_last      = 0;
      $gross_budget_nominal           = $income_budget - $cogs_budget - $fee_budget;
      $gross_budget_percent           = 0;
      $gross_variance_nominal_current = $gross_actual_nominal_current - $gross_budget_nominal;
      $gross_variance_nominal_last    = $gross_actual_nominal_current - $gross_actual_nominal_last;
      $gross_variance_percent_current = 0;
      $gross_variance_percent_last    = 0;
      
      if($income_actual_current > 0) {
         $gross_actual_percent_current = round(($gross_actual_nominal_current / $income_actual_current) * 100);
      }

      if($income_budget > 0) {
         $gross_budget_percent = round(($gross_budget_nominal / $income_budget) * 100);
      }

      if($gross_budget_nominal > 0) {
         $gross_variance_percent_current = round(($gross_variance_nominal_current / $gross_budget_nominal) * 100);
      }

      if($income_actual_last > 0) {
         $gross_actual_percent_last = round(($gross_actual_nominal_current / $income_actual_last) * 100);
      }

      if($gross_actual_nominal_last > 0) {
         $gross_variance_percent_last = round(($gross_variance_nominal_last / $gross_actual_nominal_last) * 100);
      }

      $nett_actual_nominal_current   = $gross_actual_nominal_current - $nett_actual_current;
      $nett_actual_nominal_last      = $gross_actual_nominal_last - $nett_actual_last;
      $nett_actual_percent_current   = 0;
      $nett_actual_percent_last      = 0;
      $nett_budget_nominal           = $gross_budget_nominal - $nett_budget;
      $nett_budget_percent           = 0;
      $nett_variance_nominal_current = $nett_actual_nominal_current - $nett_budget_nominal;
      $nett_variance_nominal_last    = $nett_actual_nominal_current - $nett_actual_nominal_last;
      $nett_variance_percent_current = 0;
      $nett_variance_percent_last    = 0;

      if($income_actual_current > 0) {
         $nett_actual_percent_current = round(($nett_actual_nominal_current / $income_actual_current) * 100);
      }

      if($income_budget > 0) {
         $nett_budget_percent = round(($nett_budget_nominal / $income_budget) * 100);
      }

      if($nett_budget_nominal > 0) {
         $nett_variance_percent_current = round(($nett_variance_nominal_current / $nett_budget_nominal) * 100);
      }

      if($income_actual_last > 0) {
         $nett_actual_percent_last = round(($nett_actual_nominal_last / $income_actual_last) * 100);
      }

      if($nett_actual_nominal_last > 0) {
         $nett_variance_percent_last = round(($nett_variance_nominal_last / $nett_actual_nominal_last) * 100);
      }

      $grandtotal = [
         'gross' => [
            'actual' => [
               'current' => [
                  'nominal' => $gross_actual_nominal_current,
                  'percent' => $gross_actual_percent_current
               ],
               'last' => [
                  'nominal' => $gross_actual_nominal_last,
                  'percent' => $gross_actual_percent_last
               ]
            ],
            'budget' => [
               'nominal' => $gross_budget_nominal,
               'percent' => $gross_budget_percent
            ],
            'variance' => [
               'current' => [
                  'nominal' => $gross_variance_nominal_current,
                  'percent' => $gross_variance_percent_current
               ],
               'last' => [
                  'nominal' => $gross_variance_nominal_last,
                  'percent' => $gross_variance_percent_last
               ]
            ],
         ],
         'nett' => [
            'actual' => [
               'current' => [
                  'nominal' => $nett_actual_nominal_current,
                  'percent' => $nett_actual_percent_current
               ],
               'last' => [
                  'nominal' => $nett_actual_nominal_last,
                  'percent' => $nett_actual_percent_last
               ]
            ],
            'budget' => [
               'nominal' => $nett_budget_nominal,
               'percent' => $nett_budget_percent
            ],
            'variance' => [
               'current' => [
                  'nominal' => $nett_variance_nominal_current,
                  'percent' => $nett_variance_percent_current
               ],
               'last' => [
                  'nominal' => $nett_variance_nominal_last,
                  'percent' => $nett_variance_percent_last
               ]
            ],
         ] 
      ];

      return [
         'sale'               => $sale_result,
         'sale_service'       => $sale_service_result,
         'cogs'               => $cogs_result,
         'salary_wages'       => $salary_wages_result,
         'fee_marketing'      => $fee_marketing_result,
         'fee_other'          => $fee_other_result,
         'fee_maintenance'    => $fee_maintenance_result,
         'fee_shrinkage'      => $fee_shrinkage_result,
         'fee_income_outside' => $fee_income_outside_result,
         'total'              => $total,
         'grandtotal'         => $grandtotal
      ];
   }

   private static function profitLossSurabaya($filter)
   {
      $month_current     = date('m', strtotime($filter));
      $year_current      = date('Y', strtotime($filter));
      $where_raw_current = "YEAR(created_at) = '$year_current' AND MONTH(created_at) = '$month_current'";
      $month_last        = date('m', strtotime('-1 months', strtotime($filter)));
      $year_last         = date('Y', strtotime('-1 months', strtotime($filter)));
      $where_raw_last    = "YEAR(created_at) = '$year_last' AND MONTH(created_at) = '$month_last'";

      $income_actual_current   = 0;
      $income_actual_last      = 0;
      $income_budget           = 0;
      $income_variance_current = 0;
      $income_variance_last    = 0;
      $cogs_actual_current     = 0;
      $cogs_actual_last        = 0;
      $cogs_budget             = 0;
      $cogs_variance_current   = 0;
      $cogs_variance_last      = 0;
      $fee_actual_current      = 0;
      $fee_actual_last         = 0;
      $fee_budget              = 0;
      $fee_variance_current    = 0;
      $fee_variance_last       = 0;
      $nett_actual_current     = 0;
      $nett_actual_last        = 0;
      $nett_budget             = 0;

      $sale        = Coa::whereIn('code', ['4.000.01'])->get();
      $sale_result = [];
      foreach($sale as $ss) {
         $sale     = Coa::find($ss->id);
         $sale_sub = Coa::where('parent_id', $sale->id)->orderBy('code', 'asc')->get();
         foreach($sale_sub as $ss) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $ss->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$ss->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','1')->where('coa_id', $ss->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $income_actual_current   += $total_balance_current;
            $income_actual_last      += $total_balance_last;
            $income_budget           += $budget_nominal;
            $income_variance_current += $variance_current;
            $income_variance_last    += $variance_last;

            $sale_result[] = [
               'name'     => $ss->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $sale_service        = Coa::whereIn('code', ['4.000.02'])->get();
      $sale_service_result = [];
      foreach($sale_service as $sss) {
         $sub_1                  = collect(Coa::select('id')->where('parent_id', $sss->id)->get()->toArray());
         $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
         $sub_merge              = $sub_1->merge(collect([$sss->id])->merge($sub_2));
         $balance_debit_current  = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
         $balance_credit_current = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
         $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
         $balance_debit_last     = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
         $balance_credit_last    = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
         $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
         $budget                 = Budgeting::where('month', $filter)->where('branch','1')->where('coa_id', $sss->id)->orderByDesc('id')->limit(1)->get();
         $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
         $variance_current       = $total_balance_current - $budget_nominal;
         $variance_last          = $total_balance_current - $total_balance_last;

         $income_actual_current   += $total_balance_current;
         $income_actual_last      += $total_balance_last;
         $income_budget           += $budget_nominal;
         $income_variance_current += $variance_current;
         $income_variance_last    += $variance_last;

         $sale_service_result[] = [
            'name'     => $sss->name,
            'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
            'budget'   => $budget_nominal,
            'variance' => [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ]
         ];
      }

      $cogs        = Coa::whereIn('code', ['5.000.00'])->get();
      $cogs_result = [];
      foreach($cogs as $sc) {
         $cogs     = Coa::find($sc->id);
         $cogs_sub = Coa::where('parent_id', $cogs->id)->orderBy('code', 'asc')->get();
         foreach($cogs_sub as $cs) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $cs->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$cs->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','1')->where('coa_id', $cs->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $cogs_actual_current   += $total_balance_current;
            $cogs_actual_last      += $total_balance_last;
            $cogs_budget           += $budget_nominal;
            $cogs_variance_current += $variance_current;
            $cogs_variance_last    += $variance_last;

            $cogs_result[] = [
               'name'     => $cs->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $salary_wages        = Coa::whereIn('code', ['6.200.01'])->get();
      $salary_wages_result = [];
      foreach($salary_wages as $ssw) {
         $salary_wages     = Coa::find($ssw->id);
         $salary_wages_sub = Coa::where('parent_id', $salary_wages->id)->orderBy('code', 'asc')->get();
         foreach($salary_wages_sub as $sws) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $sws->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$sws->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','1')->where('coa_id', $sws->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $salary_wages_result[] = [
               'name'     => $sws->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_marketing        = Coa::whereIn('code', ['6.100.00'])->get();
      $fee_marketing_result = [];
      foreach($fee_marketing as $sfm) {
         $fee_marketing     = Coa::find($sfm->id);
         $fee_marketing_sub = Coa::where('parent_id', $fee_marketing->id)->orderBy('code', 'asc')->get();
         foreach($fee_marketing_sub as $fms) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fms->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fms->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','1')->where('coa_id', $fms->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_marketing_result[] = [
               'name'     => $fms->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_other        = Coa::whereIn('code', ['6.2100.01'])->get();
      $fee_other_result = [];
      foreach($fee_other as $sfo) {
         $fee_other     = Coa::find($sfo->id);
         $fee_other_sub = Coa::where('parent_id', $fee_other->id)->orderBy('code', 'asc')->get();
         foreach($fee_other_sub as $fos) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fos->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fos->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','1')->where('coa_id', $fos->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_other_result[] = [
               'name'     => $fos->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_maintenance        = Coa::whereIn('code', ['6.2200.01'])->get();
      $fee_maintenance_result = [];
      foreach($fee_maintenance as $sfm) {
         $fee_maintenance     = Coa::find($sfm->id);
         $fee_maintenance_sub = Coa::where('parent_id', $fee_maintenance->id)->orderBy('code', 'asc')->get();
         foreach($fee_maintenance_sub as $fms) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fms->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fms->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','1')->where('coa_id', $fms->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_maintenance_result[] = [
               'name'     => $fms->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $total = [
         'income' => [
            'budget'   => $income_budget,
            'actual'   => ['current' => $income_actual_current, 'last' => $income_actual_last],
            'variance' => ['current' => $income_variance_current, 'last' => $income_variance_last]
         ],
         'cogs' => [
            'budget'   => $cogs_budget,
            'actual'   => ['current' => $cogs_actual_current, 'last' => $cogs_actual_last],
            'variance' => ['current' => $cogs_variance_current, 'last' => $cogs_variance_last]
         ],
         'fee' => [
            'budget'   => $fee_budget,
            'actual'   => ['current' => $fee_actual_current, 'last' => $fee_actual_last],
            'variance' => ['current' => $fee_variance_current, 'last' => $fee_variance_last]
         ],
      ];

      $nett_actual_nominal_current   = $income_actual_current - $cogs_actual_current - $fee_actual_current;
      $nett_actual_nominal_last      = $income_actual_last - $cogs_actual_last - $fee_actual_last;
      $nett_actual_percent_current   = 0;
      $nett_actual_percent_last      = 0;
      $nett_budget_nominal           = $income_budget - $cogs_budget - $fee_budget;
      $nett_budget_percent           = 0;
      $nett_variance_nominal_current = $nett_actual_nominal_current - $nett_budget_nominal;
      $nett_variance_nominal_last    = $nett_actual_nominal_current - $nett_actual_nominal_last;
      $nett_variance_percent_current = 0;
      $nett_variance_percent_last    = 0;

      if($income_actual_current > 0) {
         $nett_actual_percent_current = round(($nett_actual_nominal_current / $income_actual_current) * 100);
      }

      if($income_budget > 0) {
         $nett_budget_percent = round(($nett_budget_nominal / $income_budget) * 100);
      }

      if($nett_budget_nominal > 0) {
         $nett_variance_percent_current = round(($nett_variance_nominal_current / $nett_budget_nominal) * 100);
      }

      if($income_actual_last > 0) {
         $nett_actual_percent_last = round(($nett_actual_nominal_last / $income_actual_last) * 100);
      }

      if($nett_actual_nominal_last > 0) {
         $nett_variance_percent_last = round(($nett_variance_nominal_last / $nett_actual_nominal_last) * 100);
      }

      $grandtotal = [
         'nett' => [
            'actual' => [
               'current' => [
                  'nominal' => $nett_actual_nominal_current,
                  'percent' => $nett_actual_percent_current
               ],
               'last' => [
                  'nominal' => $nett_actual_nominal_last,
                  'percent' => $nett_actual_percent_last
               ]
            ],
            'budget' => [
               'nominal' => $nett_budget_nominal,
               'percent' => $nett_budget_percent
            ],
            'variance' => [
               'current' => [
                  'nominal' => $nett_variance_nominal_current,
                  'percent' => $nett_variance_percent_current
               ],
               'last' => [
                  'nominal' => $nett_variance_nominal_last,
                  'percent' => $nett_variance_percent_last
               ]
            ],
         ] 
      ];

      return [
         'sale'            => $sale_result,
         'sale_service'    => $sale_service_result,
         'cogs'            => $cogs_result,
         'salary_wages'    => $salary_wages_result,
         'fee_marketing'   => $fee_marketing_result,
         'fee_other'       => $fee_other_result,
         'fee_maintenance' => $fee_maintenance_result,
         'total'           => $total,
         'grandtotal'      => $grandtotal
      ];
   }

   private static function profitLossJakarta($filter)
   {
      $month_current     = date('m', strtotime($filter));
      $year_current      = date('Y', strtotime($filter));
      $where_raw_current = "YEAR(created_at) = '$year_current' AND MONTH(created_at) = '$month_current'";
      $month_last        = date('m', strtotime('-1 months', strtotime($filter)));
      $year_last         = date('Y', strtotime('-1 months', strtotime($filter)));
      $where_raw_last    = "YEAR(created_at) = '$year_last' AND MONTH(created_at) = '$month_last'";

      $income_actual_current   = 0;
      $income_actual_last      = 0;
      $income_budget           = 0;
      $income_variance_current = 0;
      $income_variance_last    = 0;
      $cogs_actual_current     = 0;
      $cogs_actual_last        = 0;
      $cogs_budget             = 0;
      $cogs_variance_current   = 0;
      $cogs_variance_last      = 0;
      $fee_actual_current      = 0;
      $fee_actual_last         = 0;
      $fee_budget              = 0;
      $fee_variance_current    = 0;
      $fee_variance_last       = 0;
      $nett_actual_current     = 0;
      $nett_actual_last        = 0;
      $nett_budget             = 0;

      $sale        = Coa::whereIn('code', ['4.000.01'])->get();
      $sale_result = [];
      foreach($sale as $ss) {
         $sale     = Coa::find($ss->id);
         $sale_sub = Coa::where('parent_id', $sale->id)->orderBy('code', 'asc')->get();
         foreach($sale_sub as $ss) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $ss->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$ss->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','2')->where('coa_id', $ss->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $income_actual_current   += $total_balance_current;
            $income_actual_last      += $total_balance_last;
            $income_budget           += $budget_nominal;
            $income_variance_current += $variance_current;
            $income_variance_last    += $variance_last;

            $sale_result[] = [
               'name'     => $ss->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $sale_service        = Coa::whereIn('code', ['4.000.02'])->get();
      $sale_service_result = [];
      foreach($sale_service as $sss) {
         $sub_1                  = collect(Coa::select('id')->where('parent_id', $sss->id)->get()->toArray());
         $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
         $sub_merge              = $sub_1->merge(collect([$sss->id])->merge($sub_2));
         $balance_debit_current  = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
         $balance_credit_current = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
         $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
         $balance_debit_last     = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
         $balance_credit_last    = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
         $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
         $budget                 = Budgeting::where('month', $filter)->where('branch','2')->where('coa_id', $sss->id)->orderByDesc('id')->limit(1)->get();
         $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
         $variance_current       = $total_balance_current - $budget_nominal;
         $variance_last          = $total_balance_current - $total_balance_last;

         $income_actual_current   += $total_balance_current;
         $income_actual_last      += $total_balance_last;
         $income_budget           += $budget_nominal;
         $income_variance_current += $variance_current;
         $income_variance_last    += $variance_last;

         $sale_service_result[] = [
            'name'     => $sss->name,
            'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
            'budget'   => $budget_nominal,
            'variance' => [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ]
         ];
      }

      $cogs        = Coa::whereIn('code', ['5.000.00'])->get();
      $cogs_result = [];
      foreach($cogs as $sc) {
         $cogs     = Coa::find($sc->id);
         $cogs_sub = Coa::where('parent_id', $cogs->id)->orderBy('code', 'asc')->get();
         foreach($cogs_sub as $cs) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $cs->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$cs->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','2')->where('coa_id', $cs->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $cogs_actual_current   += $total_balance_current;
            $cogs_actual_last      += $total_balance_last;
            $cogs_budget           += $budget_nominal;
            $cogs_variance_current += $variance_current;
            $cogs_variance_last    += $variance_last;

            $cogs_result[] = [
               'name'     => $cs->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $salary_wages        = Coa::whereIn('code', ['6.200.01'])->get();
      $salary_wages_result = [];
      foreach($salary_wages as $ssw) {
         $salary_wages     = Coa::find($ssw->id);
         $salary_wages_sub = Coa::where('parent_id', $salary_wages->id)->orderBy('code', 'asc')->get();
         foreach($salary_wages_sub as $sws) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $sws->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$sws->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','2')->where('coa_id', $sws->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $salary_wages_result[] = [
               'name'     => $sws->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_marketing        = Coa::whereIn('code', ['6.100.00'])->get();
      $fee_marketing_result = [];
      foreach($fee_marketing as $sfm) {
         $fee_marketing     = Coa::find($sfm->id);
         $fee_marketing_sub = Coa::where('parent_id', $fee_marketing->id)->orderBy('code', 'asc')->get();
         foreach($fee_marketing_sub as $fms) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fms->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fms->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','2')->where('coa_id', $fms->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_marketing_result[] = [
               'name'     => $fms->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_other        = Coa::whereIn('code', ['6.2100.01'])->get();
      $fee_other_result = [];
      foreach($fee_other as $sfo) {
         $fee_other     = Coa::find($sfo->id);
         $fee_other_sub = Coa::where('parent_id', $fee_other->id)->orderBy('code', 'asc')->get();
         foreach($fee_other_sub as $fos) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fos->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fos->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','2')->where('coa_id', $fos->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_other_result[] = [
               'name'     => $fos->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $fee_maintenance        = Coa::whereIn('code', ['6.2200.01'])->get();
      $fee_maintenance_result = [];
      foreach($fee_maintenance as $sfm) {
         $fee_maintenance     = Coa::find($sfm->id);
         $fee_maintenance_sub = Coa::where('parent_id', $fee_maintenance->id)->orderBy('code', 'asc')->get();
         foreach($fee_maintenance_sub as $fms) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $fms->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$fms->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->where('branch','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('branch','2')->where('coa_id', $fms->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $fee_actual_current   += $total_balance_current;
            $fee_actual_last      += $total_balance_last;
            $fee_budget           += $budget_nominal;
            $fee_variance_current += $variance_current;
            $fee_variance_last    += $variance_last;

            $actual = [
               'nominal' => [
                  'current' => $total_balance_current, 
                  'last'    => $total_balance_last
               ],
               'percent' => [
                  'current' => ($income_actual_current > 0) ? round(($total_balance_current / $income_actual_current) * 100) : 0,
                  'last'    => ($income_actual_last > 0) ? round(($total_balance_last / $income_actual_last) * 100) : 0
               ]
            ];

            $budgeting = [
               'nominal' => $budget_nominal,
               'percent' => ($income_budget > 0) ? round(($budget_nominal / $income_budget) * 100) : 0
            ];

            $variance = [
               'nominal' => [
                  'current' => $variance_current, 
                  'last'    => $variance_last
               ],
               'percent' => [
                  'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                  'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
               ]
            ];

            $fee_maintenance_result[] = [
               'name'     => $fms->name,
               'actual'   => $actual,
               'budget'   => $budgeting,
               'variance' => $variance
            ];
         }
      }

      $total = [
         'income' => [
            'budget'   => $income_budget,
            'actual'   => ['current' => $income_actual_current, 'last' => $income_actual_last],
            'variance' => ['current' => $income_variance_current, 'last' => $income_variance_last]
         ],
         'cogs' => [
            'budget'   => $cogs_budget,
            'actual'   => ['current' => $cogs_actual_current, 'last' => $cogs_actual_last],
            'variance' => ['current' => $cogs_variance_current, 'last' => $cogs_variance_last]
         ],
         'fee' => [
            'budget'   => $fee_budget,
            'actual'   => ['current' => $fee_actual_current, 'last' => $fee_actual_last],
            'variance' => ['current' => $fee_variance_current, 'last' => $fee_variance_last]
         ],
      ];

      $nett_actual_nominal_current   = $income_actual_current - $cogs_actual_current - $fee_actual_current;
      $nett_actual_nominal_last      = $income_actual_last - $cogs_actual_last - $fee_actual_last;
      $nett_actual_percent_current   = 0;
      $nett_actual_percent_last      = 0;
      $nett_budget_nominal           = $income_budget - $cogs_budget - $fee_budget;
      $nett_budget_percent           = 0;
      $nett_variance_nominal_current = $nett_actual_nominal_current - $nett_budget_nominal;
      $nett_variance_nominal_last    = $nett_actual_nominal_current - $nett_actual_nominal_last;
      $nett_variance_percent_current = 0;
      $nett_variance_percent_last    = 0;

      if($income_actual_current > 0) {
         $nett_actual_percent_current = round(($nett_actual_nominal_current / $income_actual_current) * 100);
      }

      if($income_budget > 0) {
         $nett_budget_percent = round(($nett_budget_nominal / $income_budget) * 100);
      }

      if($nett_budget_nominal > 0) {
         $nett_variance_percent_current = round(($nett_variance_nominal_current / $nett_budget_nominal) * 100);
      }

      if($income_actual_last > 0) {
         $nett_actual_percent_last = round(($nett_actual_nominal_last / $income_actual_last) * 100);
      }

      if($nett_actual_nominal_last > 0) {
         $nett_variance_percent_last = round(($nett_variance_nominal_last / $nett_actual_nominal_last) * 100);
      }

      $grandtotal = [
         'nett' => [
            'actual' => [
               'current' => [
                  'nominal' => $nett_actual_nominal_current,
                  'percent' => $nett_actual_percent_current
               ],
               'last' => [
                  'nominal' => $nett_actual_nominal_last,
                  'percent' => $nett_actual_percent_last
               ]
            ],
            'budget' => [
               'nominal' => $nett_budget_nominal,
               'percent' => $nett_budget_percent
            ],
            'variance' => [
               'current' => [
                  'nominal' => $nett_variance_nominal_current,
                  'percent' => $nett_variance_percent_current
               ],
               'last' => [
                  'nominal' => $nett_variance_nominal_last,
                  'percent' => $nett_variance_percent_last
               ]
            ],
         ] 
      ];

      return [
         'sale'            => $sale_result,
         'sale_service'    => $sale_service_result,
         'cogs'            => $cogs_result,
         'salary_wages'    => $salary_wages_result,
         'fee_marketing'   => $fee_marketing_result,
         'fee_other'       => $fee_other_result,
         'fee_maintenance' => $fee_maintenance_result,
         'total'           => $total,
         'grandtotal'      => $grandtotal
      ];
   }

   private static function profitLossNonOperation($filter)
   {
      $month_current     = date('m', strtotime($filter));
      $year_current      = date('Y', strtotime($filter));
      $where_raw_current = "YEAR(created_at) = '$year_current' AND MONTH(created_at) = '$month_current'";
      $month_last        = date('m', strtotime('-1 months', strtotime($filter)));
      $year_last         = date('Y', strtotime('-1 months', strtotime($filter)));
      $where_raw_last    = "YEAR(created_at) = '$year_last' AND MONTH(created_at) = '$month_last'";

      $depreciation_actual_current   = 0;
      $depreciation_actual_last      = 0;
      $depreciation_budget           = 0;
      $depreciation_variance_current = 0;
      $depreciation_variance_last    = 0;
      $income_actual_current         = 0;
      $income_actual_last            = 0;
      $income_budget                 = 0;
      $income_variance_current       = 0;
      $income_variance_last          = 0;
      $deduction_actual_current      = 0;
      $deduction_actual_last         = 0;
      $deduction_budget              = 0;
      $deduction_variance_current    = 0;
      $deduction_variance_last       = 0;

      $depreciation        = Coa::whereIn('code', ['6.300.00'])->get();
      $depreciation_result = [];
      foreach($depreciation as $d) {
         $depreciation     = Coa::find($d->id);
         $depreciation_sub = Coa::where('parent_id', $depreciation->id)->orderBy('code', 'asc')->get();
         foreach($depreciation_sub as $ds) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $ds->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$ds->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $ds->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $depreciation_actual_current   += $total_balance_current;
            $depreciation_actual_last      += $total_balance_last;
            $depreciation_budget           += $budget_nominal;
            $depreciation_variance_current += $variance_current;
            $depreciation_variance_last    += $variance_last;

            $depreciation_result[] = [
               'name'     => $ds->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $other_income        = Coa::whereIn('code', ['7.100.00'])->get();
      $other_income_result = [];
      foreach($other_income as $oi) {
         $other_income     = Coa::find($oi->id);
         $other_income_sub = Coa::where('parent_id', $other_income->id)->orderBy('code', 'asc')->get();
         foreach($other_income_sub as $ois) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $ois->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$ois->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $ois->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $income_actual_current   += $total_balance_current;
            $income_actual_last      += $total_balance_last;
            $income_budget           += $budget_nominal;
            $income_variance_current += $variance_current;
            $income_variance_last    += $variance_last;

            $other_income_result[] = [
               'name'     => $ois->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $other_deduction        = Coa::whereIn('code', ['7.200.00'])->get();
      $other_deduction_result = [];
      foreach($other_deduction as $od) {
         $other_deduction     = Coa::find($od->id);
         $other_deduction_sub = Coa::where('parent_id', $other_deduction->id)->orderBy('code', 'asc')->get();
         foreach($other_deduction_sub as $ods) {
            $sub_1                  = collect(Coa::select('id')->where('parent_id', $ods->id)->get()->toArray());
            $sub_2                  = collect(Coa::select('id')->whereIn('parent_id', $sub_1->flatten())->get()->toArray());
            $sub_merge              = $sub_1->merge(collect([$ods->id])->merge($sub_2));
            $balance_debit_current  = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $balance_credit_current = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_current)->sum('nominal');
            $total_balance_current  = abs($balance_debit_current - $balance_credit_current);
            $balance_debit_last     = Journal::where('type','1')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $balance_credit_last    = Journal::where('type','2')->whereIn('coa_id', $sub_merge)->whereRaw($where_raw_last)->sum('nominal');
            $total_balance_last     = abs($balance_debit_last - $balance_credit_last);
            $budget                 = Budgeting::where('month', $filter)->where('coa_id', $ods->id)->orderByDesc('id')->limit(1)->get();
            $budget_nominal         = $budget->count() > 0 ? $budget[0]->nominal : 0;
            $variance_current       = $total_balance_current - $budget_nominal;
            $variance_last          = $total_balance_current - $total_balance_last;

            $deduction_actual_current   += $total_balance_current;
            $deduction_actual_last      += $total_balance_last;
            $deduction_budget           += $budget_nominal;
            $deduction_variance_current += $variance_current;
            $deduction_variance_last    += $variance_last;

            $other_deduction_result[] = [
               'name'     => $ods->name,
               'actual'   => ['current' => $total_balance_current, 'last' => $total_balance_last],
               'budget'   => $budget_nominal,
               'variance' => [
                  'nominal' => [
                     'current' => $variance_current, 
                     'last'    => $variance_last
                  ],
                  'percent' => [
                     'current' => ($budget_nominal > 0) ? round(($variance_current / $budget_nominal) * 100) : 0,
                     'last'    => ($total_balance_last > 0) ? round(($variance_last / $total_balance_last) * 100) : 0
                  ]
               ]
            ];
         }
      }

      $income_deduction_actual_current   = $income_actual_current - $deduction_actual_current;
      $income_deduction_actual_last      = $income_actual_last - $deduction_actual_last;
      $income_deduction_budget           = $income_budget - $deduction_budget;
      $income_deduction_variance_current = $income_deduction_budget - $income_deduction_actual_current;
      $income_deduction_variance_last    = $income_deduction_actual_last - $income_deduction_actual_current;

      $non_operation_actual_current   = $depreciation_actual_current - $income_actual_current + $deduction_actual_current;
      $non_operation_actual_last      = $depreciation_actual_last - $income_actual_last + $deduction_actual_last;
      $non_operation_budget           = $depreciation_budget - $income_budget + $deduction_budget;
      $non_operation_variance_current = $non_operation_actual_current - $non_operation_budget;
      $non_operation_variance_last    = $non_operation_actual_current - $non_operation_actual_last;

      $total = [
         'depreciation' => [
            'budget'   => $depreciation_budget,
            'actual'   => ['current' => $depreciation_actual_current, 'last' => $depreciation_actual_last],
            'variance' => ['current' => $depreciation_variance_current, 'last' => $depreciation_variance_last]
         ],
         'other_income' => [
            'budget'   => $income_budget,
            'actual'   => ['current' => $income_actual_current, 'last' => $income_actual_last],
            'variance' => ['current' => $income_variance_current, 'last' => $income_variance_last]
         ],
         'other_deduction' => [
            'budget'   => $deduction_budget,
            'actual'   => ['current' => $deduction_actual_current, 'last' => $deduction_actual_last],
            'variance' => ['current' => $deduction_variance_current, 'last' => $deduction_variance_last]
         ],
         'income_deduction' => [
            'budget'   => $income_deduction_budget,
            'actual'   => ['current' => $income_deduction_actual_current, 'last' => $income_deduction_actual_last],
            'variance' => ['current' => $income_deduction_variance_current, 'last' => $income_deduction_variance_last]
         ],
         'non_operation' => [
            'budget'   => $non_operation_budget,
            'actual'   => ['current' => $non_operation_actual_current, 'last' => $non_operation_actual_last],
            'variance' => ['current' => $non_operation_variance_current, 'last' => $non_operation_variance_last]
         ]
      ];

      return [
         'depreciation'    => $depreciation_result,
         'other_income'    => $other_income_result,
         'other_deduction' => $other_deduction_result,
         'total'           => $total
      ];
   }
   
	public static function getCashFlow($month,$branch){
		$balancecoa = [];
		$balancecashbank = [];
		$result = [];
		
		foreach(Coa::where('parent_id',0)->whereIn('code',['1.000.00'])->get() as $c){
			if(count($c->child()) == 0){
				$balancecoa[] = $c;
			}else{
				foreach($c->child()->whereNotIn('code',['1.000.04','1.000.05','1.000.06']) as $bc){
					if(count($bc->child()) == 0){
						$balancecoa[] = $bc;
					}else{
						foreach($bc->child()->whereNotIn('code',['1.000.04','1.000.05','1.000.06']) as $bcc){
							if(count($bcc->child()) == 0){
								$balancecoa[] = $bcc;
							}else{
								foreach($bcc->child()->whereNotIn('code',['1.000.04','1.000.05','1.000.06']) as $bccc){
									if(count($bccc->child()) == 0){
										$balancecoa[] = $bccc;
									}
								}
							}
						}
					}
				}
			}
		}
		
		foreach($balancecoa as $rowcoa){
			$total = $rowcoa->getBalanceCashBankRealBefore($branch,$month.'-01');
			$balancecashbank[] = [
				'description' 	=> $rowcoa->name,
				'date'			=> $month.'-01',
				'total' 		=> $total
			];
		}
		
		$weeks = self::weeksInMonth($month);
		$resultdebit = self::getARData($branch,$month);
		$resultdebitbh = self::getBhDataIn($branch,$month);
		$resultcreditbh = self::getBhDataOut($branch,$month);
		$resultcredit = self::getAPData($branch,$month);

		
		$data = [
			'resultdebit'		=> $resultdebit,
			'resultdebitbh'		=> $resultdebitbh,
			'resultcredit'		=> $resultcredit,
			'resultcreditbh'	=> $resultcreditbh,
			'weeks'				=> $weeks,
			'balance_cash_bank'	=> $balancecashbank
		];
      
      // self::updateCashFlow($data, $month, $branch);
		
		return $data;
	}

   private static function updateCashFlow($data, $month, $branch){
		$noweek = 1;
		$totalcredit = 0;
		$totaldebit = 0;
      foreach ($data['weeks'] as $key => $row) {

			foreach ($data['balance_cash_bank'] as $rowcb) {
				if (in_array($rowcb['date'], $row)) {
					$totaldebit += $rowcb['total'];
				}
			}

			foreach (collect($data['resultdebitbh'])->sortBy('date')->toArray() as $rowbh) {
				if (in_array($rowbh['date'], $row)) {
					$totaldebit += $rowbh['totalreal'];
				}
			}


			foreach (collect($data['resultdebit'])->sortBy('date')->toArray() as $key => $rowar) {
				if (!in_array($rowar['date'], $row)) {
					if ($noweek == 1 && $rowar['date'] < $month . '-01') {
						$totaldebit += $rowar['totalreal'] > 0 ? $rowar['total'] - $rowar['totalreal'] : $rowar['total'];
					}
				} else {
					$totaldebit += $rowar['total'];
				}
			}


			foreach (collect($data['resultcreditbh'])->sortBy(function ($credit, $key) {
				return $credit['date'];
			})->toArray() as $rowbh) {
				if (in_array($rowbh['date'], $row)) {
					$totalcredit += $rowbh['totalreal'];
				}
			}

			foreach (collect($data['resultcredit'])->sortBy(function ($credit, $key) {
				return $credit['fixedcost'] . $credit['date'];
			})->toArray() as $rowap) {
				if (in_array($rowap['date'], $row)) {
					$totalcredit += $rowap['totalreal'] > 0 ? $rowap['total'] - $rowap['totalreal'] : $rowap['total'];
				} else {
					if ($noweek == 1 && $rowap['date'] < $month . '-01') {
						$totalcredit += $rowap['totalreal'] > 0 ? $rowap['total'] - $rowap['totalreal'] : $rowap['total'];
					}
				}
			}
			$noweek++;
		}

		$balance = $totaldebit - $totalcredit;

      $isExist = CashFLowBalance::where('date','like',"$month%")->where('branch', $branch)->first();

      if($isExist){
         $isExist->update([
            'credit_nominal'   => $totalcredit,
            'debit_nominal'    => $totaldebit,
            'balance_nominal'  => $balance
         ]);
      }else{
         CashFLowBalance::create([
            'credit_nominal'   => $totalcredit,
            'debit_nominal'    => $totaldebit,
            'balance_nominal'  => $balance,
            'date'             => $month . '-01',
            'branch'           => $branch
         ]);
      }
   }
 	
	private static function updateRetainedEarning($month,$branch,$nominal){
		
		if(strlen($month) == 7){
			$filter = $month;
			$date = date("Y-m-t", strtotime($month));
		}elseif(strlen($month) == 10){
			$filter = substr($month,0,7);
			$date = $month;
		}
		
		$cek = 'RETAINED-EARNING-'.$branch.'-'.$filter;
		
		$code = 'RETAINED-EARNING-'.$branch.'-'.$month;
		
		$kreditcb = 94;
		
		$cek = CashBank::where('code','like',"$cek%")->first();
		
		if($cek){
			$cek->update([
				'user_id'     			=> session('bo_id'),
				'date'        			=> $date,
				'type'        			=> '3',
				'description' 			=> 'Retained earning Per '.date('M Y',strtotime($month))
			]);
			
			foreach($cek->cashBankDetail as $row){
				$row->update([
					'cash_bank_id' 	=> $cek->id,
					'coa_id'       	=> $kreditcb,
					'branch'		      => $branch,
					'type'       	   => '2',
					'nominal'      	=> $nominal,
					'note'         	=> 'Retained earning Per '.date('M Y',strtotime($month))
				]);
				
				$journal = Journal::where('journalable_type','cash_banks')->where('journalable_id',$cek->id)->first();
				
				if($journal){
					$journal->update([
						'date_transaction' => $date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cek->id,
						'coa_id'           => $kreditcb,
						'branch'		   => $branch,
						'type'	           => '2',
						'nominal'          => $nominal
					]);
				}
			}
		}else{
			$cb = CashBank::create([
				'user_id'     			=> session('bo_id'),
				'code'        			=> $code,
				'date'        			=> $date,
				'type'        			=> '3',
				'description' 			=> 'Retained earning Per '.date('M Y',strtotime($month))
			]);
			
			if($cb){
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $kreditcb,
					'branch'		      => $branch,
					'type'       	   => '2',
					'nominal'      	=> $nominal,
					'note'         	=> 'Retained earning Per '.date('M Y',strtotime($month))
				]);

				Journal::insert([
					'date_transaction' => $date,
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $kreditcb,
					'branch'		       => $branch,
					'type'	          => '2',
					'nominal'          => $nominal,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
			}
		}
	}
	
	private static function weeksInMonth($month){
	
		$dates = [];

		$week = 1;
		$date = new DateTime("$month-01");
		$days = (int)$date->format('t');

		$oneDay = new DateInterval('P1D');

		for ($day = 1; $day <= $days; $day++) {
			$dates["week-$week"][]= $date->format('Y-m-d');

			$dayOfWeek = $date->format('l');
			if ($dayOfWeek === 'Saturday') {
				$week++;
			}

			$date->add($oneDay);
		}

		return $dates;
	}
	
	private static function getARData($branch,$filter){
		
		$result = [];
		
		#AR PRODUK
		
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
				$totaldelivered += round($rd->getTotal()['totaldelivery'] + $rd->getServiceCost());
			}
			
			foreach($row->projectSaleReturn()->whereRaw("LEFT(DATE(created_at), 7) <= '$filter'")->get() as $rsr){
				$totalreturn += round($rsr->getTotal());
			}
			
			foreach($row->projectSalePay()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $rsp){
				$totalpay += $rsp->nominal;
			}
			
			foreach($row->project->projectBill()->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get() as $pb){
				$totalbill += $pb->nominal + $pb->nominal_service;
			}
			
			$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->project->id)->whereRaw("LEFT(DATE(date), 7) <= '$filter'")->get();
			
			if(count($cb) > 0){
				foreach($cb as $rowcb){
					foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
						if($cbcb->type == '2'){
							$totalcb -= $cbcb->nominal;
						}
					}
				}
			}
			
			if(round(($totaldelivered - $totalreturn - $totalpay + $totalcb - $totalbill)) > 0){
				$sisa = $totalpay;
				foreach($row->projectDelivery->where('is_sales','1')->whereNotNull('received_date') as $rd){
					$totaldelivered = round($rd->getTotal()['totaldelivery'] + $rd->getServiceCost());
					
					if($sisa >= $totaldelivered){
						
					}else{
						$baris['id'] = $rd->cashFlow() ? $rd->cashFlow()->code : $rd->id;
						$baris['type'] = 'project_deliveries';
						$baris['date'] = $rd->cashFlow() ? $rd->cashFlow()->date : ($rd->due_date_tt ? $rd->due_date_tt : $rd->due_date);
						$baris['description'] = $rd->code.' Cust. '.$rd->project->customer->name;
						$baris['total'] = $rd->cashFlow() ? $rd->cashFlow()->nominal : $totaldelivered;
						$baris['totalreal'] = $sisa > 0 ? $sisa : 0;
						$result[] = $baris;
					}
					
					$sisa -= $totaldelivered;
				}
			}
		}
		
		#AR BILL
		foreach($cb->where('lookable_type','project_bills') as $row){
			if($row->lookable->balance() > 0){
				$row['id'] = $row->cashFlow() ? $row->cashFlow()->code : $row->lookable->id;
				$row['type'] = $row->lookable_type;
				$row['date'] = $row->cashFlow() ? $row->cashFlow()->date : $row->lookable->due_date;
				$row['description']	= $row->lookable->code.' Cust. '.$row->lookable->project->customer->name;
				$row['total'] = $row->cashFlow() ? $row->cashFlow()->nominal : $row->lookable->nominal + $row->lookable->nominal_service;
				$row['totalreal'] = $row->lookable->paid();
				$result[] = $row;
			}
		}
		
		return $result;
	}
	
	private static function getBhDataIn($branch,$filter){
		$result = [];

		#balance cb in
		$balancehistory = BalanceHistory::where('coa_id','!=' ,230)->whereRaw("LEFT(date, 7) = '$filter'")->where('type','IN')->where('branch',$branch)->get();
		
		foreach($balancehistory as $rowbcb){
			
			$cust = '';
			
			$datacb = CashBank::where('code','BPC-'.$rowbcb->id)->first();
			
			if($datacb){
				if($datacb->lookable_type == 'project_main_payments'){
					$cust = ' Cust. '.$datacb->lookable->customer->name;
				}
			}
			
			$row['id'] = $rowbcb->id;
			$row['type'] = 'balance_histories';
			$row['date'] = $rowbcb->date;
			$row['description']	= 'BCB-'.$rowbcb->note.' Nominal : '.$rowbcb->nominal.$cust;
			$row['total'] = 0;
			$row['totalreal'] = $rowbcb->nominal;
			$result[] = $row;
		}
		
		return $result;
	}
	
	private static function getBhDataOut($branch,$filter){
		$result = [];
		
		#balance cb in
		$balancehistory = BalanceHistory::where('coa_id','!=', 230)->whereRaw("LEFT(date, 7) = '$filter'")->where('type','OUT')->where('branch',$branch)->get();
		
		foreach($balancehistory as $rowbcb){
			$row['id'] = $rowbcb->id;
			$row['type'] = 'balance_histories';
			$row['date'] = $rowbcb->date;
			$row['description']	= 'BCB-'.$rowbcb->note.' Nominal : '.$rowbcb->nominal;
			$row['total'] = 0;
			$row['totalreal'] = $rowbcb->nominal;
			$result[] = $row;
		}
		
		return $result;
	}
	
	private static function getAPData($branch,$filter){
		$result = [];
		$arrcoa = [];
		$arrcoaresult = [];
		
		$coa = Coa::whereIn('id',[83,84,85,86,87,88])->get();
		
		foreach($coa as $row){
			$arrcoa[] = $row->id;
		}
		
		$pr = PurchaseRequest::where('branch',$branch)->whereRaw("LEFT(due_date, 7) <= '$filter' AND (status = 'APPR' OR status = 'DONE')")->get();
		
		foreach($pr->where('status','APPR') as $row){
			
			$total = $row->cashFlow() ? $row->cashFlow()->nominal : $row->total_nominal;
			
			$sisa = $total - $row->totalPayment();
			
			foreach($coa as $key => $rowcoa){
				if($rowcoa->id == $row->coa_id && $sisa > 0){
					$arrcoaresult[] = $rowcoa->id;
				}
			}
			
			$fixed_text = in_array($row->coa_id,$arrcoa) ? 'FC-'.$row->coa->name.' - ' : '';
			
			$cekcashflow = CashFlow::where('type','purchase_requests')->where('type_id',$row->id)->get();
			
			if(count($cekcashflow) > 0){
				$saldo = $row->totalPayment();
				foreach($cekcashflow as $rowcf){
					$rowku['id'] = $row->id;
					$rowku['idcf'] = $rowcf->code;
					$rowku['type'] = 'purchase_requests';
					$rowku['date'] = $rowcf->date;
					$rowku['description']	= $fixed_text.'PR-'.$row->id.' Detail : '.$row->title.' - '.$row->item.' - Rp '.number_format($row->total_nominal,0,',',',');
					$rowku['total'] = $rowcf->nominal;
					$rowku['totalreal'] = $saldo > $rowcf->nominal ? $rowcf->nominal : ($saldo > 0 ? $saldo : 0);
					$rowku['fixedcost'] = in_array($row->coa_id,$arrcoa) ? '1' : '2';
					$result[] = $rowku;
					$saldo -= $rowcf->nominal;
				}
			}else{
				if($sisa > 0){
					$row['id'] = $row->id;
					$row['idcf'] = '';
					$row['type'] = 'purchase_requests';
					$row['date'] = $row->cashFlow() ? $row->cashFlow()->date : $row->due_date;
					$row['description']	= $fixed_text.'PR-'.$row->id.' Detail : '.$row->title.' - '.$row->item.' - Rp '.number_format($row->total_nominal,0,',',',');
					$row['total'] = $row->cashFlow() ? $row->cashFlow()->nominal : $row->total_nominal;
					$row['totalreal'] = $row->totalPayment() > $row['total'] ? $row['total'] : $row->totalPayment();
					$row['fixedcost'] = in_array($row->coa_id,$arrcoa) ? '1' : '2';
					$result[] = $row;
				}
			}
		}
		
		foreach($pr->where('status','DONE') as $row){
			
			$total = $row->cashFlow() ? $row->cashFlow()->nominal : $row->total_nominal;
			
			$sisa = $total - $row->totalPayment();
			
			foreach($coa as $key => $rowcoa){
				if($rowcoa->id == $row->coa_id && $sisa > 0){
					$arrcoaresult[] = $rowcoa->id;
				}
			}
			
			$fixed_text = in_array($row->coa_id,$arrcoa) ? 'FC-'.$row->coa->name.' - ' : '';
			
			$cekcashflow = CashFlow::where('type','purchase_requests')->where('type_id',$row->id)->get();
			
			if(count($cekcashflow) > 0){
				foreach($cekcashflow as $rowcf){
					if(substr($rowcf->date,0,7) == $filter){
						$rowku['id'] = $row->id;
						$rowku['idcf'] = $rowcf->code;
						$rowku['type'] = 'purchase_requests';
						$rowku['date'] = $rowcf->date;
						$rowku['description']	= $fixed_text.'PR-'.$row->id.' Detail : '.$row->title.' - '.$row->item.' - Rp '.number_format($row->total_nominal,0,',',',');
						$rowku['total'] = $rowcf->nominal;
						$rowku['totalreal'] = $rowcf->totalPayment() > $rowcf->nominal ? $rowcf->nominal : $row->totalPayment();
						$rowku['fixedcost'] = in_array($row->coa_id,$arrcoa) ? '1' : '2';
						$result[] = $rowku;
					}
				}
			}else{
				if(substr($row->due_date,0,7) == $filter){
					$row['id'] = $row->id;
					$row['idcf'] = '';
					$row['type'] = 'purchase_requests';
					$row['date'] = $row->cashFlow() ? $row->cashFlow()->date : $row->due_date;
					$row['description']	= $fixed_text.'PR-'.$row->id.' Detail : '.$row->title.' - '.$row->item.' - Rp '.number_format($row->total_nominal,0,',',',');
					$row['total'] = $row->cashFlow() ? $row->cashFlow()->nominal : $row->total_nominal;
					$row['totalreal'] = $row->totalPayment() > $row['total'] ? $row['total'] : $row->totalPayment();
					$row['fixedcost'] = in_array($row->coa_id,$arrcoa) ? '1' : '2';
					$result[] = $row;
				}
			}
		}
		
		foreach($coa as $row){
			if(in_array($row->id,$arrcoaresult)){
				
			}else{
				$row['id'] = 99999999 + $row->id;
				$row['idcf'] = '';
				$row['type'] = 'coas';
				$row['date'] = $filter.'-01';
				$row['description']	= 'FC-'.$row->name;
				$row['total'] = 0;
				$row['totalreal'] = 0;
				$row['fixedcost'] = '1';
				$result[] = $row;
			}
		}
		
		return $result;
	}
	
	public static function getWorkDay($branch,$date_start,$date_end){
		$arrworkday = [];
		
		$getdayoff = Schedule::where('branch',$branch)->pluck('day')->toArray();
		
		$arrdayoff = [];
		for($i=1;$i<=7;$i++){
			if(!in_array(strval($i),$getdayoff)){
				$arrdayoff[] = $i == 7 ? 0 : $i;
			}
		}
		
		$exceptholiday = Holiday::whereBetween('date',[$date_start,$date_end])->where('branch',$branch)->pluck('date')->toArray();
		
		$startDate = new Carbon($date_start);
		$endDate = new Carbon($date_end);
		
		while ($startDate->lte($endDate)){
			$date = $startDate->toDateString();
			
			$inholiday = false;
			$inweekend = false;
			
			if(in_array($date,$exceptholiday)){
				$inholiday = true;
			}
			
			if(in_array(date('w',strtotime($date)),$arrdayoff)){
				$inweekend = true;
			}
			
			if($inholiday == false && $inweekend == false){
				$arrworkday[] = $date;
			}
			
			$startDate->addDay();
		}
		
		return $arrworkday;
	}
	
	public static function getCheckIn($user,$branch,$date_start,$date_end){
		
		$exceptholiday = Holiday::whereBetween('date',[$date_start,$date_end])->where('branch',$branch)->pluck('date')->toArray();
		
		$arrleave = [];
		
		$dataleave = LeaveRequest::where('user_id',$user)->where('category','1')->whereNotNull('approved_by')->whereNotNull('checked_by')->get();
		
		foreach($dataleave as $rowleave){
			$startDate = new Carbon($rowleave->start_date);
			$endDate = new Carbon($rowleave->finish_date);
			while ($startDate->lte($endDate)){
				$arrleave[] = $startDate->toDateString();
				$startDate->addDay();
			}
		}
		
		$getattendance = Attendance::whereBetween('date',[$date_start,$date_end])->where('user_id',$user)->whereNotIn('date',$exceptholiday)->whereNotIn('date',$arrleave)->get();
		
        return $getattendance;
	}
	
	public static function getLeaveOver($user,$branch,$date_start,$date_end,$number_rule,$sign_rule,$unit_rule){
		
		$exceptholiday = Holiday::whereBetween('date',[$date_start,$date_end])->where('branch',$branch)->pluck('date')->toArray();
		
		$arrleave = [];
		
		$dataleave = LeaveRequest::where('user_id',$user)->where('category','1')->whereNotNull('approved_by')->whereNotNull('checked_by')->get();
		
		foreach($dataleave as $rowleave){
			$startDate = new Carbon($rowleave->start_date);
			$endDate = new Carbon($rowleave->finish_date);
			while ($startDate->lte($endDate)){
				$arrleave[] = $startDate->toDateString();
				$startDate->addDay();
			}
		}
		
		$getattendance = Attendance::whereBetween('date',[$date_start,$date_end])->where('user_id',$user)->whereNotIn('date',$exceptholiday)->whereNotIn('date',$arrleave)->whereNotNull('leave_out_code')->get();
		
		$total = 0;
		
		foreach($getattendance as $rowattendance){
			$count = round((strtotime($rowattendance->in_time) - strtotime($rowattendance->in_rule)) / 60,0);
			
			if($count <= 0){
				$leave_out = $rowattendance->leave_out_time;
				$leave_in = $rowattendance->leave_in_time ? $rowattendance->leave_in_time : (date('H:i:s',strtotime($rowattendance->leave_out_time) + (3600*$number_rule)));
				
				if($sign_rule == '<'){
					$balance = round((strtotime($leave_in) - strtotime($leave_out)) / ($unit_rule == '3' ? 3600 : ($unit_rule == '4' ? 60 : 1)),2);
					if($balance < $number_rule){
						$total++;
					}
				}
				
				if($sign_rule == '<='){
					$balance = round((strtotime($leave_in) - strtotime($leave_out)) / ($unit_rule == '3' ? 3600 : ($unit_rule == '4' ? 60 : 1)),2);
					if($balance <= $number_rule){
						$total++;
					}
				}
				
				if($sign_rule == '>'){
					$balance = round((strtotime($leave_in) - strtotime($leave_out)) / ($unit_rule == '3' ? 3600 : ($unit_rule == '4' ? 60 : 1)),2);
					if($balance > $number_rule){
						$total++;
					}
				}
				
				if($sign_rule == '>='){
					$balance = round((strtotime($leave_in) - strtotime($leave_out)) / ($unit_rule == '3' ? 3600 : ($unit_rule == '4' ? 60 : 1)),2);
					if($balance >= $number_rule){
						$total++;
					}
				}
			}
		}
		
        return $total;
	}
	
	public static function cekRole($arrRoleEmployee,$arrRoleRule){
		
		$allowed = false;
		
		foreach($arrRoleEmployee as $row){
			if(in_array($row,$arrRoleRule)){
				$allowed = true;
			}
		}
		
		return $allowed;
	}

   public static function letterHead($branch){
      $letterhead = NULL;

      if($branch == 2){
         $letterhead = url('website/letterheadpsi_big.jpg');
      }else if($branch == 3){
         $letterhead = url('website/letterhead_mkj.jpg');
      }else{
         $letterhead = '';
      }

      $html = '<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2" style="vertical-align: middle;padding-top:15px;padding-bottom:45px;">
						<center>
							<img src="'. $letterhead .'" width="100%" style="width: 1500px !important;">
						</center>
					</td>
				</tr>
			</table>';

      return $html;
   }

   public static function branch($branch) 
   {
       switch($branch) {
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
}