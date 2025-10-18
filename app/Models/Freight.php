<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Freight extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'freights';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'country_id',
        'city_id',
        'container',
        'shipping',
        'cost'
    ];

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

    public function shipping() 
    {
        switch($this->shipping) {
            case '1':
                $shipping = 'FOB';
                break;
            case '2':
                $shipping = 'EXWORK';
                break;
            default:
                $shipping = 'Invalid';
                break;
        }

        return $shipping;
    }

}
