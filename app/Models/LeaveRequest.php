<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class LeaveRequest extends Model {

    use HasFactory;

    protected $table      = 'leave_requests';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'code',
		'user_id',
		'type_leave_id',
		'category',
		'note',
		'start_date',
		'finish_date',
		'date_hour',
		'start_hour',
		'finish_hour',
		'proof',
		'approved_by',
		'reject_approved',
		'checked_by',
		'reject_checked',
		'seen_by'
    ];
	
	public function typeLeave()
    {
        return $this->belongsTo('App\Models\TypeLeave','type_leave_id','id'); 
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->proof)) {
            $attachment = asset(Storage::url($this->proof));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->proof)) {
            Storage::delete($this->proof);
        }
	}
	
	public function category() 
    {
        switch($this->category) {
			case '1':
                $category = 'Days';
                break;
            case '2':
                $category = 'Hours';
                break;
            default:
                $category = 'Invalid';
                break;
        }

        return $category;
    }
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }
	
	public function checked()
    {
        return $this->belongsTo('App\Models\User', 'checked_by', 'id');
    }
	
	public function seen()
    {
        return $this->belongsTo('App\Models\User', 'seen_by', 'id');
    }
}
