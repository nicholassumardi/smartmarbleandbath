<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experience extends Model {

    use HasFactory;

    protected $table      = 'experiences';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'employee_id',
        'company',
        'position',
        'month_start',
        'month_end'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function employee()
    {
        return $this->belongsTo('App\Models\User','employee_id','id');
    }
	
}
