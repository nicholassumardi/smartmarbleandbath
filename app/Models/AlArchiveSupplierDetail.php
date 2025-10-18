<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AlArchiveSupplierDetail extends Model {

    use HasFactory;

    protected $table      = 'al_archive_supplier_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'al_archive_supplier_id',
		'name',
		'filename'
    ];
	
	public function project()
    {
        return $this->belongsTo('App\Models\AlArchiveSupplier');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->filename)) {
            $attachment = asset(Storage::url($this->filename));
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
