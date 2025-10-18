<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceCode extends Model {

    use HasFactory;

    protected $table      = 'attendance_codes';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'pass',
		'code',
		'mode',
		'logged_in'
    ];
}
