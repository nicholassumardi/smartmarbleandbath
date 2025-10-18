<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'vouchers';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'voucherable_type',
        'voucherable_id',
        'code',
        'name',
        'minimum',
        'maximum',
        'quota',
        'points',
        'percentage',
        'start_date',
        'finish_date',
        'terms',
        'type'
    ];

    public function voucherable()
    {
        return $this->morphTo();
    }

    public function order()
    {
        return $this->hasMany('App\Models\Order')->where('status', '!=', 6);
    }

    public function type() 
    {
        switch($this->type) {
            case '1':
                $type = 'Discount Purchase';
                break;
            case '2':
                $type = 'Discount Shipping';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }

    public function voucherType() 
    {
        switch($this->voucherable_type) {
            case 'brands':
                $voucher_type = 'Brand';
                break;
            case 'categories':
                $voucher_type = 'Category';
                break;
            default:
                $voucher_type = 'Global';
                break;
        }

        return $voucher_type;
    }

    public function usedVoucher()
    {
        $order = Order::where('voucher_id', $this->id)->where('customer_id', session('fo_id'))->first();
        return $order;
    }

}
