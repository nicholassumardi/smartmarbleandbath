<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CustomerSample extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'customer_samples';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'customer_id',
        'code',
        'progress',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function sample()
    {
        return $this->hasMany('App\Models\Sample');
    }
    public function samplePurchase()
    {
        return $this->hasMany('App\Models\SamplePurchase');
    }
    public function samplePurchaseReturn()
    {
        return $this->hasMany('App\Models\SamplePurchaseReturn');
    }
    public function sampleProforma()
    {
        return $this->hasMany('App\Models\SampleProforma');
    }
    public function sampleShipment()
    {
        return $this->hasMany('App\Models\SampleShipment');
    }
    public function sampleWarehouse()
    {
        return $this->hasMany('App\Models\SampleWarehouse');
    }
    public function sampleDelivery()
    {
        return $this->hasMany('App\Models\SampleDelivery');
    }
    public function sampleReturn()
    {
        return $this->hasMany('App\Models\SampleReturn');
    }
    public function sampleReturnMemo()
    {
        return $this->hasMany('App\Models\SampleReturnMemo');
    }

    public static function generateCode()
    {
        $query = CustomerSample::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if ($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SMP/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
}
