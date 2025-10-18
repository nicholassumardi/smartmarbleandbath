<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSampleProduct extends Model {

    use HasFactory;

    protected $table      = 'project_sample_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_sample_id',
        'product_id',
        'qty',
		'unit',
        'size'
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
	
	public function size()
    {
        switch($this->size) {
            case '1':
                $unit = '20x20';
                break;
            case '2':
                $unit = 'Full Size';
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
