<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectFromStock extends Model {

    use HasFactory;

    protected $table      = 'project_from_stocks';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'project_id',
        'project_sale_id',
        'note',
		'approved_by'
    ];

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
	
	public function projectSale()
    {
        return $this->belongsTo('App\Models\ProjectSale');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function projectFromStockProduct()
    {
        return $this->hasMany('App\Models\ProjectFromStockProduct');
    }
	
	public function approved(){
		 return $this->belongsTo('App\Models\User', 'approved_by', 'id');
	}
}
