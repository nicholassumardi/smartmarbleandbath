<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectTaxDocument extends Model {

    use HasFactory;

    protected $table      = 'project_tax_documents';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'lookable_type',
		'lookable_id',
		'image',
		'date',
		'no',
		'nominal'
    ];
	
	public function lookable()
    {
        return $this->morphTo();
    }
	
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
