<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryDetail extends Model {

    use HasFactory;

    protected $table      = 'salary_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'salary_id',
		'employee_allowance_id',
		'allowance',
		'addition',
		'cutting',
		'loan',
		'total'
    ];
	
	public function salary()
    {
        return $this->belongsTo('App\Models\Salary');
    }
	
	public function employeeAllowance()
    {
        return $this->belongsTo('App\Models\EmployeeAllowance','employee_allowance_id','id');
    }
	
	public function employeeLoanPayment()
    {
        return $this->hasMany('App\Models\EmployeeLoanPayment');
    }
	
	public function arrLoan(){
		$arr = [];
		$arrtotal = [];
	
		foreach($this->employeeLoanPayment as $row){
			$arr[] = $row->employeeLoan->id;
			$arrtotal[] = $row->nominal;
		}
		
		return implode(',',$arr).'|'.implode(',',$arrtotal);
	}
	
	public function deleteEmployeeLoanPayment(){
		foreach($this->employeeLoanPayment as $row){
			$row->delete();
		}
	}
}