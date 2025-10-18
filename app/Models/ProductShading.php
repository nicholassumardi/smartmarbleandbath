<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductShading extends Model {

    use HasFactory;

    protected $table      = 'product_shadings';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
        'product_id',
        'warehouse_code',
        'stock_code',
        'code',
        'qty'
    ];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_code','code');
    }

	public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
}
