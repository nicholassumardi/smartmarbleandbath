<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class SettingAccounting extends Model {

    use HasFactory;

    protected $table      = 'setting_accountings';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'branch',
		'month',
		'note',
		'status',
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
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
	
	public function lastButton(){
		$id = SettingAccounting::where('branch',$this->branch)->orderByDesc('month')->latest()->first()->id;
		
		return $id;
	}
}