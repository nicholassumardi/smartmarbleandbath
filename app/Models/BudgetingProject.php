<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BudgetingProject extends Model {

    use HasFactory;

    protected $table      = 'budgeting_projects';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
		'user_id',
		'project_id',
		'branch',
		'name',
		'estimation_name',
        'month_start',
		'month_end',
		'revision_counter',
		'estimation_counter',
		'percent_rental',
		'percent_fixed',
		'percent_rsv',
		'percent_ipm',
		'percent_ibc',
		'percent_import',
		'percent_safe',
		'percent_ppn',
		'percent_pph',
		'percent_mkj',
		'percent_pta',
		'percent_mid',
		'percent_scom',
		'currency_id',
		'help_exchange_rate',
		'help_container_no',
		'help_ls_cost',
		'help_product_cost',
		'help_container_qty',
		'help_freight_cost',
		'help_emkl_cost',
		'remarks',
		'approved_by',
		'checked_by'
    ];
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }
	
	public function getApprovalAccounting(){
		$data = Approval::where('approvalable_type','budgeting_projects')->where('approvalable_id',$this->id)->where('user_id',7)->first();
		
		return $data->id;
	}
	
	public function getApprovalOwner(){
		$data = Approval::where('approvalable_type','budgeting_projects')->where('approvalable_id',$this->id)->where('user_id',12)->first();
		
		return $data->id;
	}

	public function checked()
    {
        return $this->belongsTo('App\Models\User', 'checked_by', 'id');
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
            default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project','project_id','id');
    }
	
	public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }
	
	public function budgetingProjectDetail()
    {
        return $this->hasMany('App\Models\BudgetingProjectDetail')->orderBy('id');
    }
	
	public function budgetingProjectProduct()
    {
        return $this->hasMany('App\Models\BudgetingProjectProduct');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function getTotalRevenue(){
		
		$bpd = BudgetingProjectDetail::where('budgeting_project_id',$this->id)->where('group_count','1')->get();
		
		$total = 0;
		
		foreach($bpd as $row){
			$total += $row->nominal;
		}
		
		return $total;
	}
	
	public function getTotalCogs(){
		
		$bpd = BudgetingProjectDetail::where('budgeting_project_id',$this->id)->get();
		
		$total = 0;
		
		foreach($bpd as $row){
			if($row->group_count == '2' || $row->group_count == '3'){
				$total += $row->nominal;
			}
		}
		
		return $total;
	}
	
	public function getTotalMarketing(){
		
		$bpd = BudgetingProjectDetail::where('budgeting_project_id',$this->id)->where('group_count','4')->get();
		
		$total = 0;
		
		foreach($bpd as $row){
			$total += $row->nominal;
		}
		
		return $total;
	}
	
	public function getTotalCri(){
		
		$bpd = BudgetingProjectDetail::where('budgeting_project_id',$this->id)->where('group_count','5')->get();
		
		$total = 0;
		
		foreach($bpd as $row){
			$total += $row->nominal;
		}
		
		return $total;
	}
	
	public function getNettProfit(){
		$total = $this->getTotalRevenue() - $this->getTotalCogs() - $this->getTotalMarketing();
		
		return $total;
	}
	
	public function getNettProfitPercent(){
		$total = $this->getTotalRevenue() - $this->getTotalCogs() - $this->getTotalMarketing();
		
		$percent = round(($total / $this->getTotalRevenue()) * 100,2);
		
		return $percent;
	}
}
