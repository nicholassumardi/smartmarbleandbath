<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTags extends Model {

    use HasFactory;

    protected $table      = 'news_tags';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'news_id',
        'tags'
    ];

}
