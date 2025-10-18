<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSaleProductTemp extends Model {

    use HasFactory;

    protected $table      = 'project_sale_products_temps';
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
	
	public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
	
	public function projectSaleTemp()
    {
        return $this->belongsTo('App\Models\ProjectSaleTemp','project_sale_id', 'id');
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
		
		$ps = $this->projectSaleTemp->id;
		
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
}
