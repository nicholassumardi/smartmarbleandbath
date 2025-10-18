<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PurchaseRequestMainPayment extends Model
{

    use HasFactory;

    protected $table      = 'purchase_request_main_payments';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'id',
        'user_id',
        'date_paid',
        'branch',
        'coa_id',
        'nominal',
        'image',
        'code',
        'due_date',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function purchaseRequestPayment()
    {
        return $this->hasMany('App\Models\PurchaseRequestPayment');
    }

    public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }

    public function attachment()
    {
        if (Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
    }

    public function branch()
    {
        switch ($this->branch) {
            case '1':
                $branch = 'PTA';
                break;
            case '2':
                $branch = 'SMB';
                break;
            case '3':
                $branch = 'MKJ';
                break;
            case '4':
                $branch = 'PSI';
                break;
            default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }
}
