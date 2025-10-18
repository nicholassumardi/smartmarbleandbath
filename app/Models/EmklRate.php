<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmklRate extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'emkl_rates';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'company_id',
        'currency_id',
        'conversion'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

}
