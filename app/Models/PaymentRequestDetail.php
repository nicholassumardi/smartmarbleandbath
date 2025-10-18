<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PaymentRequestDetail extends Model {

    use HasFactory;

    protected $table      = 'payment_request_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'payment_request_id',
		'purchase_request_id',
		'coa_id',
		'branch',
		'nominal',
		'code',
		'due_date'
    ];
	
	public function method() 
    {
        switch($this->method) {
			case '0':
                $method = 'Transfer';
                break;
            case '1':
                $method = 'Giro';
                break;
            case '2':
                $method = 'Check';
                break;
			case '3':
                $method = 'Cash';
                break;
            default:
                $method = 'Invalid';
                break;
        }

        return $method;
    }
	
	public function branch()
    {
        switch($this->branch) {
            case '1':
                $branch = 'Surabaya';
                break;
            case '2':
                $branch = 'Jakarta';
                break;
            case '3':
                $branch = 'MKJ';
                break;
            case '4':
                $branch = 'PSI';
                break;
			default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa', 'coa_id', 'id');
    }
	
	public function paymentRequest()
    {
        return $this->belongsTo('App\Models\PaymentRequest', 'payment_request_id', 'id');
    }
	
	public function purchaseRequest()
    {
        return $this->belongsTo('App\Models\PurchaseRequest', 'purchase_request_id', 'id');
    }
}
