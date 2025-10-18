<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class ProjectTroubleshooting extends Model {

    use HasFactory;

    protected $table      = 'project_troubleshootings';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'project_id',
        'date_trouble',
        'note',
        'image'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
}
