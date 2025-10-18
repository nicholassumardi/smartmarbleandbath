<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PurchaseCostDetail extends Model {

    use HasFactory;

    protected $table      = 'purchase_cost_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'purchase_cost_id',
		'purchase_request_id',
		'is_ppn',
		'percent_ppn',
		'nominal'
    ];
	
	
	public function purchaseRequest()
    {
        return $this->belongsTo('App\Models\PurchaseRequest');
    }
	
	public function purchaseCost()
    {
        return $this->belongsTo('App\Models\PurchaseCost');
    }
	
}