<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journal extends Model {

    use HasFactory;

    protected $table      = 'journals';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'date_transaction',
        'journalable_type',
        'journalable_id',
        'coa_id',
		'branch',
        'type',
        'nominal',
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

    public function journalable()
    {
        return $this->morphTo();
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }

}
