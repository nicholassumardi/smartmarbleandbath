<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeLeave extends Model {

    use HasFactory;

    protected $table      = 'type_leaves';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'name',
		'quota',
		'type',
		'status'
    ];
	
	public function type() 
    {
        switch($this->type) {
			case '1':
                $type = 'Leave (Ijin)';
                break;
            case '2':
                $type = 'Paid Leave (Cuti)';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }
	
	public function status()
    {
        switch($this->status) {
			case 1:
                $type = 'Active';
                break;
            case 0:
                $type = 'Non-active';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }
}
