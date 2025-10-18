<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Revision extends Model {

    use HasFactory;

    protected $table      = 'revisions';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'date',
		'lookable_type',
		'lookable_id',
		'total_old',
		'total_new',
		'total_variance',
		'note'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function lookable()
    {
        return $this->morphTo();
    }
}