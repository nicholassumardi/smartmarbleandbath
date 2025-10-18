<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectPay extends Model {

    use HasFactory;

    protected $table      = 'project_pays';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
		'user_id',
        'project_id',
		'project_sale_id',
		'project_delivery_id',
		'project_bill_id',
		'project_main_payment_id',
		'code',
        'image',
        'date',
        'nominal',
        'payment_method',
		'coa_id',
		'giro',
		'giro_code',
		'giro_date',
		'note',
		'checked_id',
		'marketing_id',
		'approved_id'
    ];
	
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
	
	public function check()
    {
        return $this->belongsTo('App\Models\User', 'checked_id', 'id');
    }
	
	public function marketing()
    {
        return $this->belongsTo('App\Models\User', 'marketing_id', 'id');
    }
	
	public function projectMainPayment()
    {
        return $this->belongsTo('App\Models\ProjectMainPayment', 'project_main_payment_id', 'id');
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_id', 'id');
    }
	
	public static function generateCode()
    {
        $query = ProjectPay::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'INV-PAY/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	public function projectSale()
    {
        return $this->belongsTo('App\Models\ProjectSale','project_sale_id','id');
    }
	
	public function projectDelivery()
    {
        return $this->belongsTo('App\Models\ProjectDelivery','project_delivery_id','id');
    }
	
	public function projectBill()
    {
        return $this->belongsTo('App\Models\ProjectBill','project_bill_id','id');
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project','project_id','id');
    }

    public function paymentMethod() 
    {
        switch($this->payment_method) {
            case '1':
                $payment_method = 'Down Payment';
                break;
            case '2':
                $payment_method = 'Full Payment Upfront';
                break;
			case '3':
                $payment_method = 'Full Payment Last';
                break;
            default:
                $payment_method = 'Invalid';
                break;
        }

        return $payment_method;
    }
	
	public function giro() 
    {
        switch($this->giro) {
			case '0':
                $giro = 'Transfer';
                break;
            case '1':
                $giro = 'Giro';
                break;
            case '2':
                $giro = 'Check';
                break;
			case '3':
                $giro = 'Cash';
                break;
            default:
                $giro = 'Invalid';
                break;
        }

        return $giro;
    }

	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function image() 
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }

}
