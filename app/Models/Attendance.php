<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Attendance extends Model {

    use HasFactory;

    protected $table      = 'attendances';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'date',
		'in_code',
		'in_time',
		'in_rule',
		'in_image',
		'in_latitude',
		'in_longitude',
		'in_note',
		'in_approved_by',
		'in_rejected_note',
		'out_code',
		'out_time',
		'out_rule',
		'out_image',
		'out_latitude',
		'out_longitude',
		'out_note',
		'out_approved_by',
		'out_rejected_note',
		'leave_out_code',
		'leave_out_time',
		'leave_in_code',
		'leave_in_time'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function inApprove()
    {
        return $this->belongsTo('App\Models\User', 'in_approved_by', 'id');
    }
	
	public function outApprove()
    {
        return $this->belongsTo('App\Models\User', 'out_approved_by', 'id');
    }
	
	public function getLateIn(){
		$day = date('N',strtotime($this->date));
		$datalate = Schedule::where('branch',$this->user->branch)->where('day',$day)->first();
		
		if($datalate){
			$result = $datalate->in_time;
		}else{
			$result = '';
		}
		
		return $result;
	}
	
	public function getMinLateIn(){
		$day = date('N',strtotime($this->date));
		$datalate = Schedule::where('branch',$this->user->branch)->where('day',$day)->first();
		
		if($datalate){
			$count = round((strtotime($this->in_time) - strtotime($this->in_rule)) / 60,0);
			if($count > 0){
				$result = $count;
			}else{
				$result = 0;
			}
		}else{
			$result = 0;
		}
		
		return $result;
	}
	
	public function getFastOut(){
		$day = date('N',strtotime($this->date));
		$datafast = Schedule::where('branch',$this->user->branch)->where('day',$day)->first();
		
		if($datafast){
			$result = $datafast->out_time;
		}else{
			$result = '';
		}
		
		return $result;
	}
	
	public function getMinFastOut(){
		
		$count = round((strtotime($this->out_rule) - strtotime($this->out_time)) / 60,0);
		
		if($count > 0){
			$result = $count;
		}else{
			$result = 0;
		}
		
		return $result;
	}
	
	public function inImage() 
    {
        if(Storage::exists($this->in_image)) {
            $attachment = asset(Storage::url($this->in_image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function outImage() 
    {
        if(Storage::exists($this->out_image)) {
            $attachment = asset(Storage::url($this->out_image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
}
