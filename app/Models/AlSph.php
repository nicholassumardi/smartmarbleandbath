<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlSph extends Model {

    use HasFactory;

    protected $table      = 'al_sphs';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
		'user_id',
        'al_project_id',
        'code',
        'date',
        'revision',
		'total',
		'ppn',
		'is_pph',
		'pph',
		'grandtotal',
		'dk',
		'admin',
		'note',
		'period',
		'source',
		'contract_no',
		'contract_date'
    ];

    public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function alSphProduct()
    {
        return $this->hasMany('App\Models\AlSphProduct');
    }
	
	public function alBudgeting()
    {
        return $this->hasMany('App\Models\AlBudgeting');
    }
	
	public function alApplicationLetter()
    {
        return $this->hasMany('App\Models\AlApplicationLetter');
    }
	
	public function totalHpp(){
		$totalhpp = 0;
		
		foreach($this->alSphProduct as $row){
			$totalhpp += $row->qty * $row->buy_price;
		}
		
		return $totalhpp;
	}
	
	public function totalAdmin(){
		$totaladmin = round(($this->admin * $this->total) / 100,2);
		
		return  number_format($totaladmin, 0, ',', '.');
	}
	
	public function totalDk(){
		$totaldk = round(($this->dk * $this->total) / 100,2);
		
		return number_format($totaldk, 0, ',', '.');
	}
	
	public function alProductParent()
	{
		$parent = [];
		
		foreach($this->alSphProduct as $row){
			$ada = false;
			foreach($parent as $iterasi){
				if($iterasi['id'] == $row->alProduct->al_product_parent_id){
					$ada = true;
				}
			}
			if($ada == false){
				$parent[] = [
					'id' 	=> $row->alProduct->al_product_parent_id,
					'name'	=> $row->alProduct->al_product_parent_id ? $row->alProduct->alProductParent->name : NULL
				];
			}
		}
		
		return $parent;
	}
	
	public function alProductParent2()
	{
		$parent = [];
		
		foreach($this->alSphProduct as $row){
			$ada = false;
			foreach($parent as $iterasi){
				if($row->alProduct->alCategory->parent()->exists()){
					if($iterasi['id'] == $row->alProduct->alCategory->parent->id){
						$ada = true;
					}
				}else{
					if($iterasi['id'] == $row->alProduct->alCategory->id){
						$ada = true;
					}
				}
			}
			if($ada == false){
				$parent[] = [
					'id' 			=> $row->alProduct->alCategory->parent()->exists() ? $row->alProduct->alCategory->parent->id : $row->alProduct->alCategory->id,
					'name'			=> $row->alProduct->alCategory->parent()->exists() ? $row->alProduct->alCategory->parent->name : $row->alProduct->alCategory->name,
				];
			}
		}
		
		$parent = collect($parent)->sortBy('name')->reverse()->toArray();
		
		return $parent;
	}
}
