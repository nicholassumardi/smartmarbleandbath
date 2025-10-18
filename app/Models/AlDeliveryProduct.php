<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlDeliveryProduct extends Model {

    use HasFactory;

    protected $table      = 'al_delivery_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'al_delivery_id',
        'al_product_id',
		'qty',
		'merk',
        'equality',
		'note'
    ];

    public function alProduct()
    {
        return $this->belongsTo('App\Models\AlProduct');
    }
	
	public function alDelivery()
    {
        return $this->belongsTo('App\Models\AlDelivery');
    }
}
