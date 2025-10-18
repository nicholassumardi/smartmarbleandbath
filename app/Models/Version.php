<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Version extends Model {

    use HasFactory;

    protected $table      = 'versions';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'version',
		'released_date',
		'changelog'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
