<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlArchiveSupplier extends Model {

    use HasFactory;

    protected $table      = 'al_archive_suppliers';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'code',
        'al_project_id',
        'al_supplier_id'
    ];

    public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function alSupplier()
    {
        return $this->belongsTo('App\Models\AlSupplier');
    }
	
	public function alArchiveSupplierDetail()
    {
        return $this->hasMany('App\Models\AlArchiveSupplierDetail');
    }
	
}
