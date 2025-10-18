<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlPurchase extends Model {

    use HasFactory;

    protected $table      = 'al_purchases';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'al_project_id',
		'al_sph_id',
		'al_supplier_id',
        'code',
        'date',
        'is_ppn',
		'total',
		'ppn',
		'grandtotal',
		'note'
    ];

    public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function alSph()
    {
        return $this->belongsTo('App\Models\AlSph');
    }
	
	public function alSupplier()
    {
        return $this->belongsTo('App\Models\AlSupplier');
    }
	
	public function alPurchaseProduct()
    {
        return $this->hasMany('App\Models\AlPurchaseProduct');
    }
	
	public static function generateCode()
    {
        $query = AlPurchase::selectRaw('LEFT(code, 4) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        return str_pad($code, 4, 0, STR_PAD_LEFT).'/PTA-PO/'.date('d').'/'.SMB::getRomawi(date('n')).'/'.date('y');
    }
}
