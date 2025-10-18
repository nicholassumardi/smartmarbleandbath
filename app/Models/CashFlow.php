<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashFlow extends Model {

    use HasFactory;

    protected $table      = 'cash_flows';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
		'code',
		'user_id',
        'date',
		'type',
		'type_id',
		'nominal'
    ];
	
	public function totalPayment()
	{
		$totalpaid = 0;
		
		if($this->type == 'purchase_requests'){
			$totalpaid = PurchaseRequest::find($this->type_id)->totalPayment();
		}
		
		return $totalpaid;
	}
}