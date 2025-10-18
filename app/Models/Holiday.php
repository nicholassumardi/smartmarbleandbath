<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model {

    use HasFactory;

    protected $table      = 'holidays';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'date',
		'branch',
		'description',
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
}
