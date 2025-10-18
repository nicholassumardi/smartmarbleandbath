<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleProductShading extends Model
{
    use HasFactory;

    protected $table      = 'sample_product_shadings';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
		'sample_id',
        'product_id',
        'warehouse_code',
        'stock_code',
        'code',
        'qty'
    ];

	public function projectSale()
    {
        return $this->belongsTo('App\Models\ProjectSale', 'project_sale_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_code');
    }

	public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
}
