<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlCategory extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'al_categories';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'name',
		'parent_id'
    ];
	
	public function alProduct()
    {
        return $this->hasMany('App\Models\AlProduct');
    }
	
	public function parent()
    {
        return $this->hasOne('App\Models\AlCategory','id','parent_id');
    }
	
	public function child()
    {
        $query = AlCategory::where('parent_id',$this->id)->get();
        return $query;
    }

    public function parentName()
    {
        $query = AlCategory::find($this->parent_id);
		if($query){
			return $query;
		}else{
			return null;
		}
    }
}
