<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class AlDelivery extends Model {

    use HasFactory;

    protected $table      = 'al_deliveries';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'code',
        'al_project_id',
		'al_sph_id',
		'to_whom',
        'date_sent',
		'date_received',
		'image',
        'driver',
		'vehicle_type',
		'vehicle_no',
		'city_id',
		'note'
    ];
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}

    public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
	
	public function alSph()
    {
        return $this->belongsTo('App\Models\AlSph');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function alDeliveryProduct()
    {
        return $this->hasMany('App\Models\AlDeliveryProduct');
    }
	
	public static function generateCode()
    {
        $query = AlDelivery::selectRaw('LEFT(code, 4) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        return str_pad($code, 4, 0, STR_PAD_LEFT).'/PTA-SJ/'.date('d').'/'.SMB::getRomawi(date('n')).'/'.date('y');
    }
}
