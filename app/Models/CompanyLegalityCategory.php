<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyLegalityCategory extends Model {

    use HasFactory;

    protected $table      = 'company_legality_categories';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'name'
    ];

	public function companyLegality()
    {
        return $this->hasMany('App\Models\CompanyLegality');
    }
	
}
