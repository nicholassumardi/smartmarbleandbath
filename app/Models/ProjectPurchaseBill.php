<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectPurchaseBill extends Model {

    use HasFactory;

    protected $table      = 'project_purchase_bills';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_id',
		'project_purchase_id',
        'image',
		'no_document',
		'method',
        'date',
		'due_date',
        'nominal',
		'note'
    ];
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }

	public function projectPurchase()
    {
        return $this->belongsTo('App\Models\ProjectPurchase', 'project_purchase_id', 'id');
    }
	
    public function method() 
    {
        switch($this->method) {
			case '1':
                $method = 'Transfer';
                break;
            case '2':
                $method = 'Cash';
                break;
            case '3':
                $method = 'Giro';
                break;
			case '4':
                $method = 'Check';
                break;
            default:
                $method = 'Invalid';
                break;
        }

        return $method;
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
