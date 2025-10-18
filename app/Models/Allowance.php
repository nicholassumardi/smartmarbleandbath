<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allowance extends Model {

    use HasFactory;

    protected $table      = 'allowances';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'name',
		'type'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function allowanceRule()
    {
        return $this->hasMany('App\Models\AllowanceRule','allowance_id','id');
    }
	
	public function type()
    {
        switch($this->type) {
            case '1':
                $type = 'Monthly';
                break;
            case '2':
                $type = 'Daily';
                break;
			case '3':
                $type = 'Hourly';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }
	
}