<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectShipmentTrack extends Model {

    use HasFactory;

    protected $table      = 'project_shipment_tracks';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'project_shipment_id',
        'note'
    ];
	
	public function projectShipment()
    {
        return $this->belongsTo('App\Models\ProjectShipment', 'project_shipment_id', 'id');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
