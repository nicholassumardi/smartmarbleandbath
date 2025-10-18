<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashBank extends Model
{

    use HasFactory;

    protected $table      = 'cash_banks';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'budgeting_project_id',
        'lookable_type',
        'lookable_id',
        'supplier_id',
        'customer_id',
        'no_nota',
        'request_date',
        'due_date',
        'image',
        'code',
        'date',
        'type',
        'description',
        'purchase_request_ref'
    ];

    public function lookable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id', 'id');
    }

    public function budgetingProject()
    {
        return $this->belongsTo('App\Models\BudgetProject');
    }

    public function sum()
    {
        $cashbank = CashBank::find($this->id);

        $total = $cashbank->cashBankDetail()->where('type', '1')->sum('nominal');

        return $total;
    }

    public function abnormal()
    {
        $abnormal = false;

        $countSby = 0;
        $countJkt = 0;

        $adabeda = false;

        foreach ($this->cashBankDetail as $row) {
            if ($row->branch == '1') {
                $countSby++;
            } elseif ($row->branch == '2') {
                $countJkt++;
            }
        }

        if ($countSby == 1 && $countJkt == 1) {
            $adabeda = true;
        }

        if (count($this->cashBankDetail) == 2 && $adabeda == true) {
            $abnormal = true;
        }

        return $abnormal;
    }

    public function cashBankDetail()
    {
        return $this->hasMany('App\Models\CashBankDetail');
    }

    public function journalDetail()
    {
        return $this->hasMany('App\Models\Journal', 'journalable_id', 'id');
    }

    public function branch()
    {
        $branch = $this->cashBankDetail->first()->branch;

        return $branch;
    }

    public function type()
    {
        switch ($this->type) {
            case '1':
                $type = 'Cash / Bank In';
                break;
            case '2':
                $type = 'Cash / Bank Out';
                break;
            case '3':
                $type = 'Journal';
                break;
            case '4':
                $type = 'Receivable';
                break;
            case '5':
                $type = 'Payable';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }

    public function deleteDetail()
    {
        CashBankDetail::where('cash_bank_id', $this->id)->delete();
        Journal::where('journalable_type', 'cash_banks')->where('journalable_id', $this->id)->delete();
    }

    public function deleteFile()
    {
        if (Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
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

    public function getRejectedPurchaseRequestNominal($branch)
    {
        return $this->cashBankDetail()->where('coa_id', 332)->where('type', 1)->where('branch', $branch)->first()->nominal;
    }
}
