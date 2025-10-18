<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceCostPayment extends Model
{
    use HasFactory;
    protected $table      = 'service_cost_payments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'cash_bank_id',
        'service_cost_id',
        'customer_id',
        'date_paid',
        'branch',
        'coa_id',
        'nominal',
        'image',
        'code',
        'note',
        'approved_by',
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function serviceCost()
    {
        return $this->belongsTo('App\Models\ServiceCost');
    }

    public function approved()
	{
		return $this->belongsTo('App\Models\User', 'approved_id', 'id');
	}

    public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }

    public static function generateCode()
    {
        $query = ServiceCostPayment::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '000001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SCP/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
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
		
		if(Storage::exists($this->image_paid)) {
            Storage::delete($this->image_paid);
        }
	}


    public function branch()
	{
		switch ($this->branch) {
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
	
}
