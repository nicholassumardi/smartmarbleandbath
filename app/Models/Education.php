<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Education extends Model {

    use HasFactory;

    protected $table      = 'educations';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'employee_id',
        'grade',
        'institution',
        'majors',
        'score',
        'start_year',
        'end_year'
    ];

    public function grade() 
    {
        switch($this->grade) {
            case '1':
                $grade = 'SD';
                break;
            case '2':
                $grade = 'SMP';
                break;
			case '3':
                $grade = 'SMA/SMK';
                break;
			case '4':
                $grade = 'D1';
                break;
			case '5':
                $grade = 'D2';
                break;
			case '6':
                $grade = 'D3';
                break;
			case '7':
                $grade = 'S1/D4';
                break;
			case '8':
                $grade = 'S2';
                break;
			case '9':
                $grade = 'S3';
                break;
            default:
                $grade = 'Invalid';
                break;
        }

        return $grade;
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
