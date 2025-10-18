<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSaleShading extends Model {

    use HasFactory;

    protected $table      = 'project_sale_shadings';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
		'project_sale_id',
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
