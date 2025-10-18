<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAllowance extends Model {

    use HasFactory;

    protected $table      = 'employee_allowances';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'employee_id',
		'payment_type',
		'start_month',
		'end_month',
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function employee()
    {
        return $this->belongsTo('App\Models\User','employee_id','id');
    }
	
	public function employeeAllowanceDetail()
    {
        return $this->hasMany('App\Models\EmployeeAllowanceDetail');
    }
	
	public function paymentType()
    {
        switch($this->payment_type) {
            case '1':
                $payment_type = 'Monthly';
                break;
            case '2':
                $payment_type = 'Weekly';
                break;
			default:
                $payment_type = 'Invalid';
                break;
        }

        return $payment_type;
    }
	
}