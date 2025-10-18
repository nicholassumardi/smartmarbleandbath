<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleShipmentTrack extends Model
{
    use HasFactory;
    protected $table      = 'sample_shipment_tracks';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'sample_shipment_id',
        'note'
    ];

    public function sampleShipment()
    {
        return $this->belongsTo('App\Models\SampleShipment');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
