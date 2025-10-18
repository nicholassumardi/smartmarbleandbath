<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class AlIncome extends Model {

    use HasFactory;

    protected $table      = 'al_incomes';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
		'code',
        'al_project_id',
		'al_invoice_id',
        'from_person',
		'date',
		'image',
		'note',
		'nominal',
		'nominal_other',
		'ppn',
		'pph',
    ];

    public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
	
	public static function generateCode()
    {
        $query = AlIncome::selectRaw('LEFT(code, 4) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        return str_pad($code, 4, 0, STR_PAD_LEFT).'/PTA-KW/'.date('d').'/'.SMB::getRomawi(date('n')).'/'.date('y');
    }
}
