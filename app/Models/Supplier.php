<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'suppliers';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'country_id',
        'code',
        'name',
        'email',
        'phone',
        'address',
        'pic',
        'limit_credit',
        'term_of_payment',
		'isppn',
        'status'
    ];

    public static function generateCode()
    {
        $query = Supplier::selectRaw('RIGHT(code, 4) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->withTrashed()
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        return 'SUPP-' . str_pad($code, 4, 0, STR_PAD_LEFT);
    }

    public function status() 
    {
        switch($this->status) {
            case '1':
                $status = '<span class="text-success font-weight-bold">Active</span>';
                break;
            case '2':
                $status = '<span class="text-danger font-weight-bold">Not Active</span>';
                break;
            default:
                $status = '<span class="text-warning font-weight-bold">Invalid</span>';
                break;
        }

        return $status;
    }
	
	public function ppn() 
    {
        switch($this->isppn) {
            case '1':
                $status = 'Yes';
                break;
            case '2':
                $status = 'No';
                break;
            default:
                $status = 'Invalid';
                break;
        }

        return $status;
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function supplierCurrency()
    {
        return $this->hasMany('App\Models\SupplierCurrency');
    }

}
