<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SampleDeliveryTrack extends Model
{
    use HasFactory;

    protected $table      = 'sample_delivery_tracks';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'sample_id',
        'note',
        'image'
    ];

    public function sample()
    {
        return $this->belongsTo('App\Models\Sample');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function image()
    {
        if (Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }
        
        return $image;
    }
}
