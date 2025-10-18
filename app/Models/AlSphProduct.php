<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlSphProduct extends Model {

    use HasFactory;

    protected $table      = 'al_sph_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'al_sph_id',
        'al_product_id',
		'qty',
		'buy_price',
		'sell_price',
        'total'
    ];

    public function alProduct()
    {
        return $this->belongsTo('App\Models\AlProduct');
    }
	
	public function alSph()
    {
        return $this->belongsTo('App\Models\AlSph');
    }
}
