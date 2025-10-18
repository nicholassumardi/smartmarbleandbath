<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class CompanyLegalityEmail extends Model {

    use HasFactory;

    protected $table      = 'company_legality_emails';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'email',
        'subject',
		'content',
		'attachments'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
	
	public function attachments() 
    {
        
    }
	
}
