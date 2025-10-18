<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlApplicationLetter extends Model {

    use HasFactory;

    protected $table      = 'al_application_letters';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'code',
		'al_project_id',
		'al_sph_id',
		'to_whom',
		'company',
		'address',
		'city_id',
		'nominal',
		'date',
		'period',
		'no_rekening',
		'bank',
		'contract_no'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
	
	public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function alSph()
    {
        return $this->belongsTo('App\Models\AlSph');
    }
	
	public function bank() 
    {
        switch($this->bank) {
            case '1':
                $bank = 'Bank Mandiri';
                break;
            case '2':
                $bank = 'Bank Central Asia';
                break;
			case '3':
                $bank = 'Bank BNI';
                break;
            default:
                $bank = 'Invalid';
                break;
        }

        return $bank;
    }
	
	public static function generateCode()
    {
        $query = AlApplicationLetter::selectRaw('LEFT(code, 4) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        return str_pad($code, 4, 0, STR_PAD_LEFT).'/PTA-SP/'.date('d').'/'.SMB::getRomawi(date('n')).'/'.date('y');
    }
}
