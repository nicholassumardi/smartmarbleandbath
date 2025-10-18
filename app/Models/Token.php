<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model {

    use HasFactory;

    protected $table      = 'tokens';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'tokenable_type',
        'tokenable_id',
        'token',
        'type',
        'valid',
        'status'
    ];

}
