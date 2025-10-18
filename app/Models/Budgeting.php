<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budgeting extends Model {

    use HasFactory;

    protected $table      = 'budgetings';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'project_id',
        'coa_id',
		'branch',
        'month',
        'nominal'
    ];

	public function branch() 
    {
        switch($this->branch) {
            case '1':
                $branch = 'PTA';
                break;
            case '2':
                $branch = 'SMB';
                break;
            default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }

	public function project()
    {
        return $this->belongsTo('App\Models\Project'); 
    }

    public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }

}
