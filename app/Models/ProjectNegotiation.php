<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectNegotiation extends Model {

    use HasFactory;

    protected $table      = 'project_negotiations';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_id',
        'date',
        'person',
        'result'
    ];

}
