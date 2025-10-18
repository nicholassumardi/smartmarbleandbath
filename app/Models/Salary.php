<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model {

    use HasFactory;

    protected $table      = 'salaries';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'code',
		'month',
		'branch',
		'date_generate',
		'date_start',
		'date_end',
		'total_allowance',
		'total_addition',
		'total_cutting',
		'total_loan',
		'grandtotal',
		'purchase_request_id'
    ];
	
	public function employeeAllowance()
    {
        return $this->hasMany('App\Models\EmployeeAllowance');
    }
	
	public function salaryDetail()
    {
        return $this->hasMany('App\Models\SalaryDetail');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function purchaseRequest()
    {
        return $this->belongsTo('App\Models\PurchaseRequest');
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
	
	public static function generateCode()
    {
        $query = Salary::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SA/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	
}