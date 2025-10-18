<?php

namespace App\Console\Commands;

use App\Models\Coa;
use Illuminate\Console\Command;

class RetainedEarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retained:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upate Retained earnings daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    private static function calculateTotals($coas, $filter, $branch)
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
        $totals = self::calculateCOATotals($coa, $filter, $branch, $totals);
    }

    return $totals;
    }

    private static function calculateCOATotals($coa, $filter, $branch, $totals)
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
    
    public function handle()
    {
         // Fetch Coa data once outside the loop
         $coa = Coa::orderBy('code')->get();
     
        
         // Loop through months
         $firstMonth = '2021-10-31';
         $currentMonth = date('Y-m-d');
         while ($firstMonth <= $currentMonth) {
             // Calculate totals for the current month if it's a valid month
             if (strlen($currentMonth) == 7 || strlen($currentMonth) == 10) {
                 // Calculate totals for the current month
                 $totals = self::calculateTotals($coa, $currentMonth, $branch);
     
                 // Calculate retained earnings for the current month
                   $total_retained_now = $totals['revenue_actual_now'] - $totals['cogs_actual_now'] -
                    $totals['fixed_cost_actual_now'] - $totals['variable_cost_actual_now'] -
                    $totals['other_expenses_actual_now'] - $totals['repair_expenses_actual_now'] -
                    $totals['depreciation_actual_now'] + $totals['other_income_actual_now'] -
                    $totals['other_deduction_actual_now'];
     
                 // Update retained earnings for the current month
                 if ($branch) {
                     self::updateRetainedEarning($currentMonth, $branch, $total_retained_now);
                 } else {
                     self::updateAllBranch($currentMonth, $total_retained_now);
                 }
             }
     
             // Move to the next month
             if (strlen($currentMonth) == 7) {
                 $currentMonth = date("Y-m", strtotime("$currentMonth +1 month"));
             } elseif (strlen($currentMonth) == 10) {
                 $currentMonth = date("Y-m-d", strtotime("$currentMonth +1 month"));
             }
         }
         
         return true;
 
    }
}
