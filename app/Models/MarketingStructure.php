<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarketingStructure extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'marketing_structures';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'company_id',
        'rental_cost',
        'travel_sales_cost',
        'marketing_cost',
        'on_site_cost',
        'storage_cost',
        'fixed_cost',
        'interest_in_payment',
        'nett_profit',
        'saving',
        'sales_commission',
        'middlemant_commission',
        'project_commission',
		'interest_on_buying'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

}
