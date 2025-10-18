<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectNote extends Model {

    use HasFactory;

    protected $table      = 'project_notes';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'notable_type',
        'notable_id',
		'note',
        'image',
		'is_public'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function notable()
    {
        return $this->morphTo();
    }
	
	public function image() 
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }
	
	public function isPublic(){
		$isPublic = '';
		
		if($this->is_public == '1'){
			$isPublic = 'Yes';
		}else{
			$isPublic = 'No';
		}
		
		return $isPublic;
	}
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
}
