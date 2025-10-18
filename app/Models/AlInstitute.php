<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlInstitute extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'al_institutes';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable   = [
        'name',
		'initial'
    ];
}
