<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class CompanyLegality extends Model {

    use HasFactory;

    protected $table      = 'company_legalities';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'company_legality_category_id',
        'file_name',
        'storage_name',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
	
	public function companyLegalityCategory()
    {
        return $this->belongsTo('App\Models\CompanyLegalityCategory');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->storage_name)) {
			/* if(explode('.',$this->storage_name)[count(explode('.',$this->file_name)) - 1] == 'pdf'){
				$image = asset('website/empty.jpg');
			}else{ */
				$image = asset(Storage::url($this->storage_name));
			//}
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->storage_name)) {
            Storage::delete($this->storage_name);
        }
	}
	
	public function extension(){
		return explode('.',$this->storage_name)[count(explode('.',$this->storage_name)) - 1];
	}

}
