<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ReceivablePayment extends Model {

    use HasFactory;

    protected $table      = 'receivable_payments';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'cash_bank_id',
		'customer_id',
		'coa_id',
		'branch',
		'date',
		'note',
		'nominal',
		'image',
		'approved_by',
		'checked_by'
    ];
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }
	
	public function checked()
    {
        return $this->belongsTo('App\Models\User', 'checked_by', 'id');
    }
	
	public function cashBank()
    {
        return $this->belongsTo('App\Models\CashBank');
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
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }
	
	public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
}
