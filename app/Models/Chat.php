<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model {

    use HasFactory;

    protected $table      = 'chats';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_from',
        'user_to',
        'message',
        'attachment',
        'status'
    ];

    public function attachment()
    {
        if(Storage::exists($this->attachment)) {
            $attachment = asset(Storage::url($this->attachment));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }

}
