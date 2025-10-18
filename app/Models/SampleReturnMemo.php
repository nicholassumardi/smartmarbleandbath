<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SampleReturnMemo extends Model
{
    use HasFactory;

    protected $table      = 'sample_return_memos';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'date',
        'customer_sample_id',
        'sample_delivery_id',
        'reason',
        'note',
        'image'
    ];

    public function sampleReturnMemoDetail()
    {
        return $this->hasMany('App\Models\SampleReturnMemoDetail');
    }

    public function customerSample()
    {
        return $this->belongsTo('App\Models\CustomerSample');
    }

    public function sampleDelivery()
    {
        return $this->belongsTo('App\Models\SampleDelivery');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function attachment()
    {
        if (Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
    }
}
