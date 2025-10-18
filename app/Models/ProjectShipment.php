<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectShipment extends Model {

    use HasFactory;

    protected $table      = 'project_shipments';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
        'project_id',
		'project_purchase_id',
		'shipment_code',
        'loading_date',
        'departure_date',
        'from_port',
        'to_port',
        'eta',
		'delivery_method',
        'note',
		'gambar'
    ];
	
	public function attachment() 
    {
        if(Storage::exists($this->gambar)) {
            $gambar = asset(Storage::url($this->gambar));
        } else {
            $gambar = asset('website/empty.jpg');
        }

        return $gambar;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->gambar)) {
            Storage::delete($this->gambar);
        }
	}
	
	public function projectPurchase()
    {
        return $this->belongsTo('App\Models\ProjectPurchase', 'project_purchase_id', 'id');
    }
	
	public function projectShipmentProduct()
    {
        return $this->hasMany('App\Models\ProjectShipmentProduct');
    }
	
	public function projectShipmentTrack()
    {
        return $this->hasMany('App\Models\ProjectShipmentTrack');
    }
	
	public function projectShipmentWarehouse()
    {
        return $this->hasMany('App\Models\ProjectWarehouse');
    }
	
	public function delivery() 
    {
        switch($this->delivery_method) {
            case '3':
                $method = 'Land';
                break;
			case '2':
                $method = 'Sea';
                break;
            case '1':
                $method = 'Air';
                break;
            default:
                $method = 'Invalid';
                break;
        }

        return $method;
    }
}
