<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleDeliveryProduct extends Model
{
    use HasFactory;
    protected $table      = 'sample_delivery_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'sample_delivery_id',
        'product_id',
        'qty',
        'unit',
        'shading'
    ];

    public function sampleDelivery()
    {
        return $this->belongsTo('App\Models\SampleDelivery');
    }

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


    public function getQtyMinusReturn()
	{
		$totalqty = $this->qty;
		$totalreturn = 0;
		
		$salereturn = SampleReturn::where('sample_id',$this->sampleDelivery->sample->id)->get();
		
		if($this->sampleDelivery->sample->sampleDelivery()->first()->id == $this->sampleDelivery->id){
			foreach($salereturn as $sr){
				foreach($sr->sampleReturnProduct->where('product_id',$this->product_id) as $psrp){
					$totalreturn += $psrp->qty;
				}
			}
		}
		
		$balance = $totalqty - $totalreturn;
		
		return $balance;
	}

    public function qtyReturn()
	{
		$totalreturn = 0;
		
		$salereturn = SampleReturn::where('sample_id',$this->sampleDelivery->sample->id)->get();
		
		if($this->sampleDelivery->sample->sampleDelivery()->first()->id == $this->sampleDelivery->id){
			foreach($salereturn as $sr){
				foreach($sr->sampleReturnProduct->where('product_id',$this->product_id) as $psrp){
					$totalreturn += $psrp->qty;
				}
			}
		}
		
		return $totalreturn;
	}
}
