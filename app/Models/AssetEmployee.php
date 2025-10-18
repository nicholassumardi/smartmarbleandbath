<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetEmployee extends Model {

    use HasFactory;

    protected $table      = 'asset_employees';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'employee_id',
        'asset_id',
        'date_borrow',
        'date_return',
        'status',
        'note',
		'note_return'
    ];

    public function status() 
    {
        switch($this->status) {
            case '1':
                $status = 'Borrowed';
                break;
            case '2':
                $status = 'Returned';
                break;
            default:
                $status = 'Invalid';
                break;
        }

        return $status;
    }
	
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function asset()
    {
        return $this->belongsTo('App\Models\Asset');
    }
	
	public function employee()
    {
        return $this->belongsTo('App\Models\User','employee_id','id');
    }
}
