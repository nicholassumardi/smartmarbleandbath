<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleWarehouseProduct extends Model
{
    use HasFactory;

    protected $table      = 'sample_warehouse_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'sample_warehouse_id',
        'product_id',
        'qty',
        'unit',
        'qty_broken',
        'unit_broken'
    ];

    public function sampleWarehouse()
    {
        return $this->belongsTo('App\Models\SampleWarehouse');
    }

    public function unit()
    {
        switch ($this->unit) {
            case '1':
                $unit = 'Pcs';
                break;
            case '2':
                $unit = 'Box';
                break;
            case '3':
                $unit = 'Meter';
                break;
            default:
                $unit = 'Invalid';
                break;
        }

        return $unit;
    }

    public function unit_broken()
    {
        switch ($this->unit_broken) {
            case '1':
                $unit = 'Pcs';
                break;
            case '2':
                $unit = 'Box';
                break;
            case '3':
                $unit = 'Meter';
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
