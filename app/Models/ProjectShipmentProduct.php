<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectShipmentProduct extends Model {

    use HasFactory;

    protected $table      = 'project_shipment_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_shipment_id',
        'product_id',
        'qty',
        'unit'
    ];
	
	public function projectShipment()
    {
        return $this->belongsTo('App\Models\ProjectShipment', 'project_shipment_id', 'id');
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
