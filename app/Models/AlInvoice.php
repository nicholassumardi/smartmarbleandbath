<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helper\SMB;

class AlInvoice extends Model {

    use HasFactory;

    protected $table      = 'al_invoices';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'code',
        'al_project_id',
        'al_sph_id',
		'date',
		'nominal',
		'note',
		'sign_name',
		'sign_position'
    ];
	
	public static function generateCode()
    {
        $query = AlInvoice::selectRaw('LEFT(code, 4) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        return str_pad($code, 4, 0, STR_PAD_LEFT).'/PTA-FB/'.date('d').'/'.SMB::getRomawi(date('n')).'/'.date('y');
    }

    public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function alSph()
    {
        return $this->belongsTo('App\Models\AlSph');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function alIncome()
    {
        return $this->hasMany('App\Models\AlIncome');
    }
}
