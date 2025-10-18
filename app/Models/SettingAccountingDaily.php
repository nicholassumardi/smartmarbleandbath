<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingAccountingDaily extends Model
{
    use HasFactory;

    protected $table      = 'setting_accounting_dailies';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'branch',
		'date',
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
		$id = SettingAccountingDaily::where('branch',$this->branch)->orderByDesc('date')->latest()->first()->id;
		
		return $id;
	}
}
