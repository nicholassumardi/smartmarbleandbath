<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PurchaseRequestPayment extends Model {

    use HasFactory;

    protected $table      = 'purchase_request_payments';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'purchase_request_id',
		'purchase_request_main_payment_id',
		'date_paid',
		'branch',
		'coa_id',
        'nominal',
		'image',
		'code',
		'due_date',
		'note'
    ];
	
	public function purchaseRequest()
    {
        return $this->belongsTo('App\Models\PurchaseRequest','purchase_request_id','id');
    }
	
	public function purchaseRequestMainPayment()
    {
        return $this->belongsTo('App\Models\PurchaseRequestMainPayment');
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }
	
	public function branch() 
    {
        switch($this->branch) {
			case '1':
                $branch = 'PTA';
                break;
            case '2':
                $branch = 'SMB';
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
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
	
	public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id', 'id');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
	
	public function cekCB(){
		
		$cb = CashBank::where('code','PRP-'.$this->id)->first();
		
		if($cb){
			return true;
		}else{
			return false;
		}
		
	}
}
