<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProjectReturnMemo extends Model {

    use HasFactory;

    protected $table      = 'project_return_memos';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'date',		
		'project_id',
        'project_delivery_id',
		'reason',
        'note',
		'image'
    ];
	
	public function projectReturnMemoDetail()
    {
        return $this->hasMany('App\Models\ProjectReturnMemoDetail');
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project','project_id','id');
    }
	
	public function projectDelivery()
    {
        return $this->belongsTo('App\Models\ProjectDelivery','project_delivery_id','id');
    }
	
    public function projectSaleReturn()
    {
        return $this->hasOne('App\Models\ProjectSaleReturn');
    }

	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
}
