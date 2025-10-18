<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Folder extends Model {

    use HasFactory;

    protected $table      = 'folders';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'code',
		'name'
    ];
	
	public function folderContents()
    {
        return $this->hasMany('App\Models\FolderContent');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
