<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SampleReturn extends Model
{
    use HasFactory;


    protected $table      = 'sample_returns';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'customer_sample_id',
        'sample_id',
        'sample_return_memo_id',
        'date_return',
        'warehouse_id',
        'address',
        'code',
        'image',
        'note',
        'type',
        'approved_by',
        'grandtotal'
    ];

    public function type()
    {
        switch ($this->type) {
            case '1':
                $type = 'Return to supplier / split to another Project.';
                break;
            case '2':
                $type = 'As a Cost.';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    }

    public function approve()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }

    public static function generateCode()
    {
        $query = SampleReturn::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if ($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SMR/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public function sample()
    {
        return $this->belongsTo('App\Models\Sample');
    }

    public function customerSample()
    {
        return $this->belongsTo('App\Models\CustomerSample');
    }

    public function sampleReturnProduct()
    {
        return $this->hasMany('App\Models\SampleReturnProduct');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function attachment()
    {
        if (Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
    }


    public function getTotal()
    {
        $sample = SampleReturn::find($this->id);
        $samplepurchase = SamplePurchase::where('sample_id', $this->sample_id)->orderBy('created_at', 'desc')->first();
        $totalpurchase = 0;

        foreach ($sample->sampleReturnProduct as $key => $ps) {
            $ssp = SamplePurchaseProduct::where('sample_purchase_id', $samplepurchase->id)->where('product_id', $ps->product_id)->first();
            $totalpurchase += $ps->qty * $ssp->price;
        }

        $arr = [
            'totalpurchase' => $totalpurchase
        ];

        return $arr;
    }
}
