<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLogin extends Model {
	
	use HasFactory;

    protected $table      = 'user_logins';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'loginable'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
