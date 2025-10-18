<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProjectSample extends Model {

    use HasFactory;

    protected $table      = 'project_samples';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_id',
        'code',
		'sent_date',
		'return_date',
		'note',
		'status',
        'approved_by_1',
        'approved_by_2',
		'returned_at',
		'return_proof'
    ];

	public function approved_1()
    {
        return $this->belongsTo('App\Models\User', 'approved_by_1', 'id');
    }
	
	public function approved_2()
    {
        return $this->belongsTo('App\Models\User', 'approved_by_2', 'id');
    }
	
    public function projectSampleProduct()
    {
        return $this->hasMany('App\Models\ProjectSampleProduct');
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }
	
	public function status()
    {
        switch($this->status) {
            case '1':
                $check = 'On Customer';
                break;
            case '2':
                $check = 'Returned';
                break;
			case '3':
                $check = 'No need to return';
                break;
            default:
                $check = 'Invalid';
                break;
        }

        return $check;
    }
	
	public static function generateCode()
    {
        $query = ProjectSample::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SPO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->return_proof)) {
            $attachment = asset(Storage::url($this->return_proof));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->return_proof)) {
            Storage::delete($this->return_proof);
        }
	}

    public function deleteDetail(){
        ProjectSampleProduct::where('project_sample_id', $this->id)->delete();
    }
}
