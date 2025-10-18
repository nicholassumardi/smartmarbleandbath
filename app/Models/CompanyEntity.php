<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyEntity extends Model
{
    use HasFactory;

    protected $table      = 'company_entities';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'name',
        'image',
    ];

	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }

    public function branch($id){
       $branch =  CompanyEntity::where('id', $id)->first();

       return $branch->name;
    }
}
