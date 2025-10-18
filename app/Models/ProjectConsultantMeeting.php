<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectConsultantMeeting extends Model {

    use HasFactory;

    protected $table      = 'project_consultant_meetings';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_id',
        'date',
        'person',
        'result'
    ];

}
