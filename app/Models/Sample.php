<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Sample extends Model
{
    use HasFactory;
    protected $table      = 'samples';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'customer_sample_id',
        'sales_id',
        'sent_date',
        'return_date',
        'note',
        'code',
        'status',
        'approved_by_1',
        'approved_by_2',
        'returned_at',
        'return_proof'
    ];

    public function customerSample()
    {
        return $this->belongsTo('App\Models\CustomerSample');
    }

    public function sales()
    {
        return $this->belongsTo('App\Models\User', 'sales_id', 'id');
    }
    
    public function approved_1()
    {
        return $this->belongsTo('App\Models\User', 'approved_by_1', 'id');
    }

    public function approved_2()
    {
        return $this->belongsTo('App\Models\User', 'approved_by_2', 'id');
    }

    public function sampleProduct()
    {
        return $this->hasMany('App\Models\SampleProduct');
    }
    public function samplePurchase()
    {
        return $this->hasMany('App\Models\SamplePurchase');
    }
    public function sampleProductShading()
    {
        return $this->hasMany('App\Models\SampleProductShading');
    }
    public function sampleDelivery()
    {
        return $this->hasMany('App\Models\SampleDelivery');
    }


    public function status()
    {
        switch ($this->status) {
            case '1':
                $check = 'On Customer';
                break;
            case '2':
                $check = 'Returned';
                break;
            case '3':
                $check = 'No need to return';
                break;
            default:
                $check = 'Invalid';
                break;
        }

        return $check;
    }

    public function attachment()
    {
        if (Storage::exists($this->return_proof)) {
            $attachment = asset(Storage::url($this->return_proof));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }

    public static function generateCode()
    {
        $query = Sample::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if ($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SMSO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->return_proof)) {
            Storage::delete($this->return_proof);
        }
    }

    public function deleteDetail(){
        SampleProduct::where('sample_id', $this->id)->delete();
        SampleProductShading::where('sample_id', $this->id)->delete();
    }

    public function deleteApproval(){
        Approval::where('approvalable_type', 'samples')->where('approvalable_id', $this->id)->delete();
    }
}
