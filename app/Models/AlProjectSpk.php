<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AlProjectSpk extends Model {

    use HasFactory;

    protected $table      = 'al_project_spks';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'al_project_id',
		'name',
		'filename'
    ];
	
	public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->filename)) {
			//if(in_array(explode('.',$this->filename)[1],array('jpeg','jpg','png'))){
				$attachment = asset(Storage::url($this->filename));
			/* }else{
				$attachment = asset('website/empty.jpg');
			} */
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function picture() 
    {
        if(Storage::exists($this->filename)) {
			if(in_array(explode('.',$this->filename)[1],array('jpeg','jpg','png'))){
				$attachment = asset(Storage::url($this->filename));
			}else{
				$attachment = asset('website/empty.jpg');
			}
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->filename)) {
            Storage::delete($this->filename);
        }
	}

}
