<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PurchaseCost extends Model {

    use HasFactory;

    protected $table      = 'purchase_costs';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'project_purchase_id',
		'coa_id',
		'percent_fee_mkj'
    ];
	
	public function totalCost(){
		$total = 0;
		
		foreach($this->purchaseCostDetail as $row){
			if($row->is_ppn == '1'){
				$total += round($row->nominal / ((100 + $row->percent_ppn) / 100));
			}else{
				$total += $row->nominal;
			}
		}
		
		return $total;
	}
	
	public function purchase()
    {
        return $this->belongsTo('App\Models\ProjectPurchase');
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }
	
	public function purchaseCostDetail()
    {
        return $this->hasMany('App\Models\PurchaseCostDetail');
    }
	
	public function ppn()
    {
        switch($this->is_ppn) {
            case '1':
                $ppn = 'Yes';
                break;
            case '0':
				$ppn = 'No';
                break;
            default:
                $ppn = 'Invalid';
                break;
        }

        return $ppn;
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
