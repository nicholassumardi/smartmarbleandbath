<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SampleProforma extends Model
{
    use HasFactory;
    protected $table      = 'sample_proformas';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'customer_sample_id',
        'sample_purchase_id',
        'image',
        'date',
        'supplier_name',
        'supplier_warehouse',
        'note'
    ];

    public function samplePurchase()
    {
        return $this->belongsTo('App\Models\SamplePurchase');
    }

    public function customerSample()
    {
        return $this->belongsTo('App\Models\customerSample');
    }

    public function attachment()
    {
        if (Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
    }
}
