<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model {

    use HasFactory;

    protected $table      = 'schedules';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'branch',
		'day',
		'in_time',
		'out_time'
    ];
	
	public function branch() 
    {
        switch($this->branch) {
			case '1':
                $type = 'PTA';
                break;
            case '2':
                $type = 'SMB';
                break;
            case '3':
                $type = 'MKJ';
                break;
            case '4':
                $type = 'PSI';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }
	
	public function day()
    {
        switch($this->day) {
			case 1:
                $type = 'Monday';
                break;
			case 2:
                $type = 'Tuesday';
                break;
			case 3:
                $type = 'Wednesday';
                break;
			case 4:
                $type = 'Thursday';
                break;
			case 5:
                $type = 'Friday';
                break;
			case 6:
                $type = 'Saturday';
                break;
			case 7:
                $type = 'Sunday';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }
}
