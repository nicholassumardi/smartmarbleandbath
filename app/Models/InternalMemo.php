<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalMemo extends Model
{
    use HasFactory;

    protected $table      = 'internal_memos';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'title',
        'code',
        'date',
        'note',
        'proof',
    ];


    public static function generateCode()
    {
        $query = InternalMemo::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '000001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SMB/MEMO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }



    public function projectPurchase()
    {
        return $this->hasOne('App\Models\ProjectPurchase');
    }

}
