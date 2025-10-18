<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model {

    use HasFactory;

    protected $table      = 'product_reviews';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
        'order_detail_id',
        'product_id',
        'customer_id',
        'review',
        'rate'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

}
