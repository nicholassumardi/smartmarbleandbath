<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyRate extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'currency_rates';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'currency_id',
        'company_id',
        'conversion'
    ];

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

}
