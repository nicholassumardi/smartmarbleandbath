<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model {

    use HasFactory;

    protected $table      = 'papers';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'coa_id',
        'image'
    ];

    public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }

}
