<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFLowBalance extends Model
{
    use HasFactory;

    protected $table      = 'cash_flow_balances';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'credit_nominal',
		'balance_nominal',
		'debit_nominal',
		'date',
		'branch',
    ];
	
}
