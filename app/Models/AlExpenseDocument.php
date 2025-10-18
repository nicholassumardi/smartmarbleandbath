<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AlExpenseDocument extends Model {

    use HasFactory;

    protected $table      = 'al_expense_documents';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'al_expense_id',
		'document_name',
		'file_name'
    ];
	
	public function alExpense()
    {
        return $this->belongsTo('App\Models\AlExpense');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->file_name)) {
			$attachment = asset(Storage::url($this->file_name));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function picture() 
    {
        if(Storage::exists($this->file_name)) {
			if(in_array(explode('.',$this->file_name)[1],array('jpeg','jpg','png'))){
				$attachment = asset(Storage::url($this->file_name));
			}else{
				$attachment = asset('website/empty.jpg');
			}
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->file_name)) {
            Storage::delete($this->file_name);
        }
	}

}
