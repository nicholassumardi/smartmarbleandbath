<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlChecklistProject extends Model {

    use HasFactory;

    protected $table      = 'al_checklist_projects';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'al_project_id',
        'al_checklist_id',
		'date',
		'note',
		'status'
    ];
	
	public function status() 
    {
        switch($this->status) {
            case '1':
                $status = 'Finish';
                break;
            case '0':
                $status = 'Skip';
                break;
            default:
                $status = 'Invalid';
                break;
        }

        return $status;
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function alChecklist()
    {
        return $this->belongsTo('App\Models\AlChecklist');
    }
}
