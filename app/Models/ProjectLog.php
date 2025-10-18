<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLog extends Model {

    use HasFactory;

    protected $table      = 'project_logs';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'project_id',
        'project_table',
		'project_table_id',
        'approval',
		'type',
		'description',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}
