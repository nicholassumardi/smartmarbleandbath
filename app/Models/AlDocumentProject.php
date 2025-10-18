<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlDocumentProject extends Model {

    use HasFactory;

    protected $table      = 'al_document_projects';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'al_project_id',
		'al_document_id'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function alDocument()
    {
        return $this->belongsTo('App\Models\AlDocument');
    }
}
