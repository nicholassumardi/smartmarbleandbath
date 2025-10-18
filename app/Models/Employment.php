<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employment extends Model {

    use HasFactory;

    protected $table      = 'employments';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'employee_id',
        'employee_no',
        'document_no',
        'status',
        'branch',
        'start_date',
        'end_date',
        'image',
		'active',
		'resign_date',
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
	
	public function status() 
    {
        switch($this->status) {
            case '1':
                $status = 'Permanent';
                break;
            case '2':
                $status = 'Contract';
                break;
			case '3':
                $status = 'Probation';
                break;
            default:
                $status = 'Invalid';
                break;
        }

        return $status;
    }
	
	public function active() 
    {
        switch($this->active) {
            case '1':
                $active = '<span class="badge badge-success">Active</span>';
                break;
            case '2':
                $active = '<span class="badge badge-danger">Inactive</span>';
                break;
            default:
                $active = 'Invalid';
                break;
        }

        return $active;
    }
	
    public function image()
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else if($this->image) {
            $image = $this->image;
        } else {
            $image = asset('website/user.png');
        }

        return $image;
    }
	
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function employee()
    {
        return $this->belongsTo('App\Models\User','employee_id','id');
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
}
