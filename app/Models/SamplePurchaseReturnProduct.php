<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamplePurchaseReturnProduct extends Model
{
    use HasFactory;

    
    protected $table      = 'sample_purchase_return_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'sample_purchase_return_id',
        'product_id',
        'qty',
        'unit'
    ];
	
	public function samplePurchaseReturn()
    {
        return $this->belongsTo('App\Models\SamplePurchaseReturn');
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
}
