<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FieldTrip extends Model
{
    use HasFactory;

    protected $table      = 'field_trips';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'project_id',
		'date',
		'note',
		'reminder',
		'reminder_date',
		'date',
		'proof',
    ];
	
	public function customer()
    {
        return $this->hasMany('App\Models\Customer');
    }

	public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
    
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }


    public function attachment() 
    {
        if(Storage::exists($this->proof)) {
            $proof = asset(Storage::url($this->proof));
        } else {
            $proof = asset('website/empty.jpg');
        }

        return $proof;
    }
}
