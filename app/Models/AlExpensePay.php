<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class AlExpensePay extends Model {

    use HasFactory;

    protected $table      = 'al_expense_pays';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'al_expense_id',
        'receiver',
		'date',
		'image',
		'nominal',
		'note'
    ];

    public function alExpense()
    {
        return $this->belongsTo('App\Models\AlExpense');
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
