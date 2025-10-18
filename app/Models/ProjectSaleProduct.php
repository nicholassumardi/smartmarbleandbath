<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSaleProduct extends Model {

    use HasFactory;

    protected $table      = 'project_sale_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_sale_id',
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
	
	public function priceBudget(){
		$data = BudgetingProject::where('project_id',$this->projectSale->project_id)->first();
		
		$price = 0;
		
		if($data){
			foreach($data->budgetingProjectProduct()->where('product_id',$this->product_id)->get() as $row){
				$price = $row->price * $data->help_exchange_rate;
			}
		}
		
		return $price;
	}
	
	public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
	
	public function projectSale()
    {
        return $this->belongsTo('App\Models\ProjectSale','project_sale_id', 'id');
    }
	
	public function getCountReturn()
	{
		$jumlah = 0;
		
		$ps = $this->projectSale->id;
		
		foreach(ProjectSaleReturn::where('project_sale_id',$ps)->get() as $row){
			foreach($row->projectSaleReturnProduct as $rowdet)
			{
				if($this->product_id == $rowdet->product_id){
					$jumlah += $rowdet->qty;
				}
			}
		}
		
		return $jumlah;
	}
	
	public function getCountSent()
	{
		$jumlah = 0;
		
		$ps = $this->projectSale->id;
		
		foreach(ProjectDelivery::where('project_sale_id',$ps)->get() as $row){
			foreach($row->projectDeliveryProduct as $rowdet)
			{
				if($this->product_id == $rowdet->product_id){
					$jumlah += $rowdet->qty;
				}
			}
		}
		
		return $jumlah;
	}
	
	public function getCountFromStock()
	{
		$jumlah = 0;
		
		$ps = $this->projectSale->id;
		
		foreach(ProjectFromStock::where('project_sale_id',$ps)->whereNotNull('approved_by')->get() as $row){
			foreach($row->projectFromStockProduct as $rowdet)
			{
				if($this->product_id == $rowdet->product_id){
					$jumlah += $rowdet->qty;
				}
			}
		}
		
		return $jumlah;
	}
}
