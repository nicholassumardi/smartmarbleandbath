<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PaymentRequestSource extends Model {

    use HasFactory;

    protected $table      = 'payment_request_sources';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'payment_request_id',
		'coa_id',
		'branch',
		'nominal',
		'code',
		'due_date',
		'image'
    ];
	
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
	
	public function paymentRequest()
    {
        return $this->belongsTo('App\Models\PaymentRequest', 'payment_request_id', 'id');
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa', 'coa_id', 'id');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $gambar = asset(Storage::url($this->image));
        } else {
            $gambar = asset('website/empty.jpg');
        }

        return $gambar;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
}
