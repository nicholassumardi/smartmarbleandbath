<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowanceRule extends Model {

    use HasFactory;

    protected $table      = 'allowance_rules';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'allowance_id',
		'type_rule',
		'sign_rule',
		'unit_rule',
		'number_rule',
		'percentage_cutting',
    ];
	
	public function allowance()
    {
        return $this->belongsTo('App\Models\Allowance');
    }
	
	public function typeRule()
    {
        switch($this->type_rule) {
            case '1':
                $type_rule = 'Absence';
                break;
            case '2':
                $type_rule = 'Leave Permission';
                break;
			case '3':
                $type_rule = 'Late';
                break;
            default:
                $type_rule = 'Not Set';
                break;
        }

        return $type_rule;
    }
	
	public function unitRule()
    {
        switch($this->unit_rule) {
            case '1':
                $unit_rule = 'Month';
                break;
            case '2':
                $unit_rule = 'Day';
                break;
			case '3':
                $unit_rule = 'Hour';
                break;
			case '4':
                $unit_rule = 'Minute';
                break;
            default:
                $unit_rule = 'Not Set';
                break;
        }

        return $unit_rule;
    }
}