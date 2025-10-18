<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectDeliveryTrack extends Model {

    use HasFactory;

    protected $table      = 'project_delivery_tracks';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'project_delivery_id',
        'note',
		'image'
    ];
	
	public function projectDelivery()
    {
        return $this->belongsTo('App\Models\ProjectDelivery', 'project_delivery_id', 'id');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
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
}
