<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SampleShipment extends Model
{
    use HasFactory;
    protected $table      = 'sample_shipments';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'customer_sample_id',
		'sample_purchase_id',
		'shipment_code',
        'loading_date',
        'departure_date',
        'from_port',
        'to_port',
        'eta',
		'delivery_method',
        'note',
		'image',
    ];
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
	
	public function samplePurchase()
    {
        return $this->belongsTo('App\Models\SamplePurchase');
    }
	
	public function sampleShipmentProduct()
    {
        return $this->hasMany('App\Models\SampleShipmentProduct');
    }
	
	public function sampleShipmentTrack()
    {
        return $this->hasMany('App\Models\SampleShipmentTrack');
    }
	
	public function sampleWarehouse()
    {
        return $this->hasMany('App\Models\SampleWarehouse');
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
