<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlDocument extends Model {

    use HasFactory;

    protected $table      = 'al_documents';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'document_name',
		'content',
		'status'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function alDocumentProject()
    {
        return $this->hasMany('App\Models\AlDocumentProject');
    }
	
}
