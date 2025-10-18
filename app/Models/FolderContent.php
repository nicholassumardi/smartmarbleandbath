<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class FolderContent extends Model {

    use HasFactory;

    protected $table      = 'folder_contents';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'folder_id',
		'filename',
		'filestore'
    ];
	
	public function folder()
    {
        return $this->belongsTo('App\Models\FolderContent','folder_id', 'id');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->filestore)) {
            $attachment = asset(Storage::url($this->filestore));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->filestore)) {
            Storage::delete($this->filestore);
        }
	}
}
