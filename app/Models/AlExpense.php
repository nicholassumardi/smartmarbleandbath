<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class AlExpense extends Model {

    use HasFactory;

    protected $table      = 'al_expenses';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'al_project_id',
        'to_person',
		'type',
		'date',
		'image',
		'title',
		'note',
		'nominal'
    ];

    public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function alExpensePay()
    {
        return $this->hasMany('App\Models\AlExpensePay');
    }
	
	public function alExpenseDocument()
    {
        return $this->hasMany('App\Models\AlExpenseDocument');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
	
	public function getProgressPayment(){
		$total = 0;
		
		foreach($this->alExpensePay as $row){
			$total += $row->nominal;
		}
		
		$persen = $total == 0 ? 0 : ($this->nominal > 0 ? round($total / $this->nominal * 100,2) : 0);
		
		$color = '';
		
		if($persen >= 100){
			$color = '<span class="badge badge-success">';
		}elseif($persen > 75){
			$color = '<span class="badge badge-warning">';
		}elseif($persen > 50){
			$color = '<span class="badge badge-warning">';
		}elseif($persen >= 0){
			$color = '<span class="badge badge-danger">';
		}
		
		return $color.$persen.'%'.'</span>';
	}
	
	public function totalPayment(){
		$total = 0;
		
		foreach($this->alExpensePay as $row){
			$total += $row->nominal;
		}
		
		return $total;
	}
	
	public function type() 
    {
        switch($this->type) {
            case '1':
                $type = 'HPP';
                break;
            case '2':
                $type = 'DK';
                break;
			case '3':
                $type = 'Admin';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }
}
