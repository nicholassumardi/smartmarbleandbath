<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlProductParent extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'al_product_parents';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'name'
    ];
	
	public function alProduct()
    {
        return $this->hasMany('App\Models\AlProduct');
    }

}
