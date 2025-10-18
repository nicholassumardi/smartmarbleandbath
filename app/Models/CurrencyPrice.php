<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyPrice extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'currency_prices';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'product_id',
        'currency_id',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

}
