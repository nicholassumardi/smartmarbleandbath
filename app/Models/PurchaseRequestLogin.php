<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseRequestLogin extends Model {

    use HasFactory;

    protected $table      = 'login';
	protected $connection = 'mysql2';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'username',
        'nama'
    ];
}
