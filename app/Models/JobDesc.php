<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDesc extends Model {

    use HasFactory;

    protected $table      = 'job_descs';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'position',
        'job',
        'branch'
    ];

    public function position() 
    {
        switch($this->position) {
            case '1':
                $position = 'Director';
                break;
            case '2':
                $position = 'Secretary';
                break;
            case '3':
                $position = 'Head Of Finance';
                break;
            case '4':
                $position = 'Head Of Accounting';
                break;
            case '5':
                $position = 'Sales & Marketing Manager';
                break;
            case '6':
                $position = 'Sales Project';
                break;
            case '7':
                $position = 'Head Of Administration';
                break;
            case '8':
                $position = 'Digital Marketing';
                break;
            case '9':
                $position = 'Purchasing';
                break;
            case '10':
                $position = 'Admin Sales & Stock';
                break;
            case '11':
                $position = 'Piutang & Pengiriman';
                break;
            case '12':
                $position = 'Assisten Hiro';
                break;
            case '13':
                $position = 'Assisten';
                break;
            default:
                $position = 'Invalid';
                break;
        }

        return $position;
    }

    public function branch() 
    {
        switch($this->branch) {
            case '1':
                $branch = 'Surabaya PTA';
                break;
            case '2':
                $branch = 'Jakarta SMB';
                break;
            case '3':
                $branch = 'Surabaya MKJ';
                break;
            case '4':
                $branch = 'Surabaya PSI';
                break;
            default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }

}
