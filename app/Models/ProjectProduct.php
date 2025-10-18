<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectProduct extends Model {

    use HasFactory;

    protected $table      = 'project_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_id',
        'product_id',
		'area',
		'spec',
        'qty',
        'cogs',
        'price',
        'recommended_price',
		'best_price',
        'discount',
        'unit'
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
	
	public function price(){
		$price = 0;
		
		if($this->best_price > 0){
			$price = $this->best_price;
		}elseif($this->recommended_price > 0){
			$price = $this->recommended_price;
		}elseif($this->price > 0){
			$price = $this->price;
		}
		
		return $price;
	}

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
	
	public function latestQuotationPrice()
	{
		$price = 0;
		$recommended_price = 0;
		$best_price = 0;
		
		$result = [];
		
		$lastpq = ProjectQuotation::where('project_id',$this->project_id)->orderBy('id','DESC')->first();
		
		if($lastpq){
			$detailcek = $lastpq->projectQuotationProduct->where('product_id',$this->product_id)->first();
			if($detailcek){
				$price = $detailcek->price;
				$recommended_price = $detailcek->recommended_price;
				$best_price = $detailcek->best_price;
			}else{
				$price = $this->price;
				$recommended_price = isset($this->product->pricingPolicy->recommended_price) ?  $this->product->pricingPolicy->recommended_price : 0;
				$best_price = $this->best_price;
			}
		}else{
			$price = $this->price;
			$recommended_price = isset($this->product->pricingPolicy->recommended_price) ?  $this->product->pricingPolicy->recommended_price : 0;
			$best_price = $this->best_price;
		}
		
		$result['price'] = $price;
		$result['recommended_price'] = $recommended_price;
		$result['best_price'] = $best_price;
		
		return $result;
	}
	
	public function budgetPrice()
	{
		$price = 0;
		
		$data = BudgetingProject::where('project_id',$this->project_id)->get();
		
		foreach($data as $row){
			foreach($row->budgetingProjectProduct->where('product_id',$this->product_id) as $rowproduct){
				$price = $rowproduct->sell_price;
			}
		}
		
		return $price;
	}
}
