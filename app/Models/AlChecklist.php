<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlChecklist extends Model {

    use HasFactory;

    protected $table      = 'al_checklists';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'order_data',
		'user_id',
        'name',
		'description'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function alChecklistProject()
    {
        return $this->hasMany('App\Models\AlChecklistProject');
    }

}
