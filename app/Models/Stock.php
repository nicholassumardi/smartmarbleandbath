<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model {

    use HasFactory;

    protected $table      = 'stocks';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'product_id',
		'warehouse_id',
        'qty',
		'code',
		'unit',
		'price_per_unit',
		'branch'
    ];
	
	public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }
	
	public function product()
    {
        return $this->belongsTo('App\Models\Product');
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
	
	public function branch() 
    {
        switch($this->branch) {
            case '1':
                $branch = 'PTA';
                break;
            case '2':
                $branch = 'SMB';
                break;
            case '3':
                $branch = 'MKJ';
                break;
            case '4':
                $branch = 'PSI';
                break;
            default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }
}
