<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricingPolicy extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'pricing_policies';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'product_id',
        'rental_cost',
		'fixed_cost',
		'interest_buying',
		'saving',
		'rsv_profit',
		'sales_commission',
		'interest_payment',
        'sales_travel_cost',
        'marketing_cost',
        'middleman_fee',
        'project_commission',
        'on_site_cost',
        'storage_cost',
        'bottom_price',
		'price_list',
        'recommended_price',
        'store_price_list',
        'discount_retail_sales',
        'discount_retail_manager',
        'discount_retail_director'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

}
