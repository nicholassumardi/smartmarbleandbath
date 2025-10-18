<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPo extends Model {

    use HasFactory;

    protected $table      = 'order_pos';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'order_id',
        'purchase_order',
        'status'
    ];

    public static function generateCode()
    {
        $query = OrderPo::selectRaw("RIGHT(purchase_order, 6) as code")
            ->orderByRaw('RIGHT(purchase_order, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'PO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public function status() 
    {
        switch($this->status) {
            case '1':
                $status = 'Process';
                break;
            case '2':
                $status = 'Done';
                break;
            default:
                $status = 'Invalid';
                break;
        }

        return $status;
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

}
