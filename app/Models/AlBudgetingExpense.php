<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlBudgetingExpense extends Model {

    use HasFactory;

    protected $table      = 'al_budgeting_expenses';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'al_budgeting_id',
        'to_whom',
        'note',
        'qty',
        'price',
        'total',
        'status'
    ];
	
	public function alBudgeting()
    {
        return $this->belongsTo('App\Models\AlBudgeting');
    }
}
