<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model {

    use HasFactory;

    protected $table      = 'assets';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'name',
        'type',
        'starting_value',
        'percent_depreciation',
        'date',
		'image',
		'coa_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function type() 
    {
        switch($this->type) {
            case '1':
                $type = 'Land';
                break;
            case '2':
                $type = 'Building & Infrastructure';
                break;
			case '3':
                $type = 'Vehicle';
                break;
			case '4':
                $type = 'Furniture & Equipment';
                break;
			case '5':
                $type = 'Other';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }
	
	public function image()
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else if($this->image) {
            $image = $this->image;
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
}
