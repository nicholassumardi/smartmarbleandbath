<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPurchaseSplit extends Model {

    use HasFactory;

    protected $table      = 'project_purchase_splits';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_purchase_id',
		'project_purchase_reference',
        'product_id',
        'qty',
		'unit',
        'price'
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
	
	public function projectPurchase()
    {
        return $this->belongsTo('App\Models\ProjectPurchase');
    }
	
	public function projectPurchaseReference()
    {
        return $this->belongsTo('App\Models\ProjectPurchase','project_purchase_reference','id');
    }
}
