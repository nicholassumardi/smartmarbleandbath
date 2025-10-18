<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectMainPayment extends Model {

    use HasFactory;

    protected $table      = 'project_main_payments';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
		'user_id',
        'cash_bank_id',
		'code',
		'date',
		'coa_id',
		'customer_id',
		'nominal',
		'note',
		'payment_method',
		'giro_code',
		'giro_date',
        'image',
		'checked_id',
		'marketing_id',
		'approved_id'
    ];
	
	public function check()
    {
        return $this->belongsTo('App\Models\User', 'checked_id', 'id');
    }
	
	public function marketing()
    {
        return $this->belongsTo('App\Models\User', 'marketing_id', 'id');
    }
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_id', 'id');
    }
	
	public function paymentMethod() 
    {
        switch($this->payment_method) {
            case '0':
                $payment_method = 'Transfer';
                break;
            case '1':
                $payment_method = 'Giro';
                break;
			case '2':
                $payment_method = 'Cheque';
                break;
			case '3':
                $payment_method = 'Cash';
                break;
            default:
                $payment_method = 'Invalid';
                break;
        }

        return $payment_method;
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
	
	public function projectPay()
    {
        return $this->hasMany('App\Models\ProjectPay');
    }
	
	public function cashBank()
    {
        return $this->belongsTo('App\Models\CashBank');
    }
	
	public static function generateCode()
    {
        $query = ProjectMainPayment::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'PP/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

	public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

}
