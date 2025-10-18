<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model {

    use HasFactory;

    protected $table      = 'carts';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'customer_id',
        'product_id',
        'qty'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

}
