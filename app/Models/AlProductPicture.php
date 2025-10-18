<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlProductPicture extends Model {

    use HasFactory;

    protected $table      = 'al_product_pictures';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'al_product_id',
        'image'
    ];
	
	public function image() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }

    public function alProduct()
    {
        return $this->belongsTo('App\Models\AlProduct');
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
}
