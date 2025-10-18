<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Family extends Model {

    use HasFactory;

    protected $table      = 'families';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'employee_id',
        'fullname',
        'relationship',
        'hp',
        'address',
        'id_number',
        'gender',
        'birthday',
		'religion',
		'marital_status',
		'job',
		'emergency'
    ];

    public function relationship() 
    {
        switch($this->relationship) {
            case '1':
                $relationship = 'Father';
                break;
            case '2':
                $relationship = 'Mother';
                break;
			case '3':
                $relationship = 'Sibling';
                break;
			case '4':
                $relationship = 'Spouse';
                break;
			case '5':
                $relationship = 'Child';
                break;
			case '6':
                $relationship = 'Cousin';
                break;
			case '7':
                $relationship = 'Nibling';
                break;
			case '8':
                $relationship = 'Parent in Law';
                break;
			case '9':
                $relationship = 'Brother in Law';
                break;
			case '10':
                $relationship = 'Sister in Law';
                break;
			case '11':
                $relationship = 'Uncle';
                break;
			case '12':
                $relationship = 'Aunt';
                break;
            default:
                $relationship = 'Invalid';
                break;
        }

        return $relationship;
    }
	
	public function gender() 
    {
        switch($this->gender) {
            case 'M':
                $gender = 'Male';
                break;
            case 'F':
                $gender = 'Female';
                break;
            default:
                $gender = 'Invalid';
                break;
        }

        return $gender;
    }
	
	public function marital_status() 
    {
        switch($this->marital_status) {
            case '1':
                $marital_status = 'Single';
                break;
            case '2':
                $marital_status = 'Married';
                break;
			case '3':
                $marital_status = 'Widow';
                break;
			case '4':
                $marital_status = 'Widower';
                break;
            default:
                $marital_status = 'Invalid';
                break;
        }

        return $marital_status;
    }
	
	public function religion() 
    {
        switch($this->religion) {
            case '1':
                $religion = 'Islam';
                break;
			case '2':
                $religion = 'Catholic';
                break;
			case '3':
                $religion = 'Christian';
                break;
			case '4':
                $religion = 'Buddha';
                break;
			case '5':
                $religion = 'Hindu';
                break;
			case '6':
                $religion = 'Confucius';
                break;
			case '7':
                $religion = 'Others';
                break;
            default:
                $religion = 'Invalid';
                break;
        }

        return $religion;
    }
	
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function employee()
    {
        return $this->belongsTo('App\Models\User','employee_id','id');
    }
}
