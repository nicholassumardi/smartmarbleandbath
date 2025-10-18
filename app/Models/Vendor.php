<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'vendors';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'pic',
        'status'
    ];

    public static function generateCode()
    {
        $query = Vendor::selectRaw('RIGHT(code, 4) as code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->limit(1)
			->withTrashed()
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 4, 0, STR_PAD_LEFT);

        return 'VEND-' . $code;
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

}
