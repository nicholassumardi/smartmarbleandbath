<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BudgetingProjectDetail extends Model {

    use HasFactory;

    protected $table      = 'budgeting_project_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'budgeting_project_id',
        'coa_id',
		'nominal',
		'estimation',
		'actual',
		'group_count',
		'description'
    ];
	
	public function coa()
    {
		if($this->group_count == '5'){
			if($this->coa_id == 111111){
				return array("name" => 'Rental Cost',"code" => '111111');
			}elseif($this->coa_id == 222222){
				return array("name" => 'Fixed Cost',"code" => '222222');
			}elseif($this->coa_id == 333333){
				return array("name" => 'RSV Profit',"code" => '333333');
			}elseif($this->coa_id == 444444){
				return array("name" => 'Interest on Payment Method',"code" => '444444');
			}elseif($this->coa_id == 555555){
				return array("name" => 'Interest on Buying Capital',"code" => '555555');
			}else{
				$cek = Coa::find($this->coa_id)->toArray();
				return $cek;
			}
		}else{
			return $this->belongsTo('App\Models\Coa');
		}
		
    }
	
	public function budgetingProject()
    {
        return $this->belongsTo('App\Models\BudgetingProject');
    }
	
	public function getRealNominal($project)
	{
		$coa = $this->coa_id;
		
		$nominal = 0;
		
		$lc = Journal::where('coa_id',$coa)->get();
		
		foreach($lc as $row){
			foreach($project as $pj){
				if($row->journalable->lookable_type == 'projects' && $row->journalable->lookable_id == $pj->id){
					$nominal += $row->nominal;
				}
			}
		}
		
		return $nominal;
	}
}
