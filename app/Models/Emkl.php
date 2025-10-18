<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Emkl extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'emkls';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'company_id',
        'import_id',
        'country_id',
        'city_id',
        'container',
        'cost'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function import()
    {
        return $this->belongsTo('App\Models\Import');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function container() 
    {
        switch($this->container) {
            case '1':
                $container = '20 Feet';
                break;
            case '2':
                $container = '40 Feet';
                break;
            default:
                $container = 'Invalid';
                break;
        }

        return $container;
    }

}
