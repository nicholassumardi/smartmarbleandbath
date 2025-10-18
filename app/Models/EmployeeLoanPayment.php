<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class EmployeeLoanPayment extends Model {

    use HasFactory;

    protected $table      = 'employee_loan_payments';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'employee_loan_id',
		'salary_detail_id',
		'date_paid',
		'nominal',
        'note',
    ];
	
	public function employeeLoan()
    {
        return $this->belongsTo('App\Models\EmployeeLoan');
    }
	
	public function salaryDetail()
    {
        return $this->belongsTo('App\Models\SalaryDetail');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}