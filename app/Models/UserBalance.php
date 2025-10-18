<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBalance extends Model {
	
	use HasFactory;

    protected $table      = 'user_balances';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'description',
		'balance'
    ];
	
	public function user()
    {
         return $this->belongsTo('App\Models\User');
    }
}
