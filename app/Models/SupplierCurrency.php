<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierCurrency extends Model {

    use HasFactory;

    protected $table      = 'supplier_currencies';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'supplier_id',
        'currency_id'
    ];
	
	public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id', 'id');
    }
}
