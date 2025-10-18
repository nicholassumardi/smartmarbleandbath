<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlPurchaseProduct extends Model {

    use HasFactory;

    protected $table      = 'al_purchase_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'al_purchase_id',
        'al_product_id',
		'qty',
		'buy_price',
        'total'
    ];

    public function alProduct()
    {
        return $this->belongsTo('App\Models\AlProduct');
    }
	
	public function alPurchase()
    {
        return $this->belongsTo('App\Models\AlPurchase');
    }
}
