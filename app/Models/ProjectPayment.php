<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectPayment extends Model {

    use HasFactory;

    protected $table      = 'project_payments';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_id',
		'project_purchase_id',
        'image',
        'date',
        'nominal',
        'bank',
		'giro',
		'giro_code',
		'giro_date',
        'status',
		'checked_by'
    ];
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }
	
	public function check()
    {
        return $this->belongsTo('App\Models\User', 'checked_by', 'id');
    }

	public function projectPurchase()
    {
        return $this->belongsTo('App\Models\ProjectPurchase', 'project_purchase_id', 'id');
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa','bank','id');
    }
	
    public function status() 
    {
        switch($this->status) {
            case '1':
                $status = 'Down Payment';
                break;
            case '2':
                $status = 'Full Payment';
                break;
            default:
                $status = 'Other';
                break;
        }

        return $status;
    }
	
	public function giro() 
    {
        switch($this->giro) {
			case '0':
                $giro = 'None';
                break;
            case '1':
                $giro = 'Giro';
                break;
            case '2':
                $giro = 'Check';
                break;
            default:
                $giro = 'Invalid';
                break;
        }

        return $giro;
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
}
