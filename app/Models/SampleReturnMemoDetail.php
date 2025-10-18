<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleReturnMemoDetail extends Model
{
    use HasFactory;

    protected $table      = 'sample_return_memo_details';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'sample_return_memo_id',
        'product_id',
        'qty',
        'unit',
		'remark'
    ];
	
	public function sampleReturnMemo()
    {
        return $this->belongsTo('App\Models\SampleReturnMemo');
    }
	
    public function unit()
    {
        switch($this->unit) {
            case '1':
                $unit = 'Pcs';
                break;
            case '2':
                $unit = 'Box';
                break;
            case '3':
                $unit = 'Meter';
                break;
            case '4':
                $unit = 'Meter (Custom)';
                break;
            default:
                $unit = 'Invalid';
                break;
        }

        return $unit;
    }
	
	public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
