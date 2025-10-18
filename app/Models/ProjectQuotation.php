<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectQuotation extends Model {

    use HasFactory;

    protected $table      = 'project_quotations';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_id',
        'revision',
        'display_brand',
        'approved_by_1',
        'approved_by_2',
        'terms_cons_id',
        'terms_cons_en',
        'ppn_cost',
        'letter_head',
    ];
	
	public function approved_1()
    {
        return $this->belongsTo('App\Models\User', 'approved_by_1', 'id');
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project','project_id','id');
    }
	
	public function approved_2()
    {
        return $this->belongsTo('App\Models\User', 'approved_by_2', 'id');
    }
	
	public function projectQuotationProduct()
    {
        return $this->hasMany('App\Models\ProjectQuotationProduct','project_quotation_id','id');
    }
	
	public function revision()
	{
		return $this->revision - 1;
	}
	
}
