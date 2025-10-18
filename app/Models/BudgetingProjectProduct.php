<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BudgetingProjectProduct extends Model {

    use HasFactory;

    protected $table      = 'budgeting_project_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'budgeting_project_id',
        'product_id',
		'qty',
		'unit',
		'price',
		'sell_price',
		'total',
		'total_sell'
    ];
	
    public function unit()
    {
        switch($this->unit) {
            case '1':
                $unit = 'Pcs';
                break;
            case '2':
                $unit = 'Box';
                break;
            case '3':
                $unit = 'Meter';
                break;
            case '4':
                $unit = 'Meter (Custom)';
                break;
            default:
                $unit = 'Invalid';
                break;
        }

        return $unit;
    }
	
	public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
	
	public function budgetingProject()
    {
        return $this->belongsTo('App\Models\BudgetingProject');
    }

    public function getLatestPrice(){
       $data = $this->price;


       return $data;
    }

    public function qty(){
		$qty = 0;
		
		if($this->unit == '2' || $this->unit == '3'){
			
			$m2 = (( $this->product->type->length * $this->product->type->width ) / 10000) * $this->product->carton_pcs;

			if($m2 < 1.1 && $this->product->type->category->parent()->id !== 18){
				$qty = $this->qty;
			}else{
				if($m2 < 1.1 && date('Y-m',strtotime($this->budgetingProject->project->created_at)) < '2022-06' && $this->product->type->category->parent()->id !== 18){
					$qty = $this->qty;
				}else{
					$qty = $this->qty * $m2;
				}
			}
		}
		
		if($this->unit == '1' || $this->unit == '4'){
			$qty = $this->qty;
		}
		
		return round($qty,2);
	}
}
