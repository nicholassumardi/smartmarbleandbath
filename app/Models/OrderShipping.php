<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model {

    use HasFactory;

    protected $table      = 'order_shippings';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'order_id',
        'city_id',
        'delivery_id',
        'receiver_name',
        'email',
        'phone',
        'address'
    ];

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Models\Delivery');
    }

}
