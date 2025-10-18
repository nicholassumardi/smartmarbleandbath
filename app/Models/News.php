<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model {

    use HasFactory;

    protected $table      = 'news';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'category_id',
        'user_id',
        'image',
        'title',
        'slug',
        'description',
        'status'
    ];

    public function image() 
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function newsTags()
    {
        return $this->hasMany('App\Models\NewsTags');
    }

    public function status() 
    {
        switch($this->status) {
            case '1':
                $status = '<span class="text-success font-weight-bold">Publish</span>';
                break;
            case '2':
                $status = '<span class="text-warning font-weight-bold">Draft</span>';
                break;
            default:
                $status = '<span class="text-warning font-weight-bold">Invalid</span>';
                break;
        }

        return $status;
    }

}
