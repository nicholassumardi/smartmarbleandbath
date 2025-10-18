<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlCustomer extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'al_customers';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable   = [
        'photo',
        'name',
        'pic',
		'pic_birthday',
		'pic_position',
		'pic_office',
		'initial',
		'al_institute_id',
        'email',
        'phone',
        'password',
        'type',
        'points',
        'verification',
		'address',
		'address_npwp',
		'npwp',
		'finance_name',
		'finance_hp'
    ];
	
	public function alInstitute()
    {
        return $this->belongsTo('App\Models\AlInstitute');
    }
	
	public function alDocument()
    {
        return $this->hasMany('App\Models\AlDocument');
    }

    public function type() 
    {
        switch($this->type) {
            case '1':
                $type = '<span class="text-success font-weight-bold">Online</span>';
                break;
            case '2':
                $type = '<span class="text-danger font-weight-bold">Offline</span>';
                break;
            case '3':
                    $type = '<span class="text-info font-weight-bold">Hybrid</span>';
                    break;
            default:
                $type = '<span class="text-warning font-weight-bold">Invalid</span>';
                break;
        }

        return $type;
    }

    public function photo()
    {
        if(Storage::exists($this->photo)) {
            $photo = asset(Storage::url($this->photo));
        } else if($this->photo) {
            $photo = $this->photo;
        } else {
            $photo = asset('website/user.png');
        }

        return $photo;
    }
}
