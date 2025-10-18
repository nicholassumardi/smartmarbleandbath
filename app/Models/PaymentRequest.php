<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PaymentRequest extends Model {

    use HasFactory;

    protected $table      = 'payment_requests';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'code',
        'user_id',
		'checked_by',
		'approved_by',
        'date',
		'title',
		'note'
    ];
	
	public static function generateCode()
    {
        $query = PaymentRequest::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'PR-' . date('y') . '-' . date('m') . '-' . date('d') . '-' . $code;
    }
	
	public function paymentDetail()
    {
        return $this->hasMany('App\Models\PaymentRequestDetail');
    }
	
	public function paymentSource()
    {
        return $this->hasMany('App\Models\PaymentRequestSource');
    }
	
	public function approve()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }
	
	public function check()
    {
        return $this->belongsTo('App\Models\User', 'checked_by', 'id');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
