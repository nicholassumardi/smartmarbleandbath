<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPoint extends Model {

    use HasFactory;

    protected $table      = 'customer_points';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'customer_id',
        'order_id',
        'points'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

}
