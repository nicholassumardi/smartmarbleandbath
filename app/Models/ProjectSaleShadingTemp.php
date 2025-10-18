<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSaleShadingTemp extends Model {

    use HasFactory;

    protected $table      = 'project_sale_shadings_temps';
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
}
