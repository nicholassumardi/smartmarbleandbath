<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model {

    use HasFactory;

    protected $table      = 'order_deliveries';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'order_id',
        'delivery_order'
    ];

    public static function generateCode()
    {
        $query = OrderDelivery::selectRaw("RIGHT(delivery_order, 6) as code")
            ->orderByRaw('RIGHT(delivery_order, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'DO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

}
