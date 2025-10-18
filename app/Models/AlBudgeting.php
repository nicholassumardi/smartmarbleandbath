<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlBudgeting extends Model {

    use HasFactory;

    protected $table      = 'al_budgetings';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'al_project_id',
        'al_sph_id',
        'al_customer_id',
        'name',
        'code',
        'total_claim',
        'total_profit',
        'total_deposit',
		'total_admin',
		'total_dk',
		'total_other',
		'budget_ppn',
		'budget_pph',
		'real_ppn',
		'real_pph',
        'note',
		'date',
		'date_disbursement',
		'type'
    ];

    public static function generateCode($al_customer_id)
    {
		$customer = AlCustomer::find($al_customer_id);
		
		$initial = $customer->alInstitute->initial.'-'.$customer->initial;
		
        $query = AlBudgeting::selectRaw('LEFT(code, 4) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        return str_pad($code, 4, 0, STR_PAD_LEFT).'/PTA-RAB/'.$initial.'/'.SMB::getRomawi(date('n')).'/'.date('y');
    }

    public function alSph()
    {
        return $this->belongsTo('App\Models\AlSph');
    }
	
	public function alCustomer()
    {
        return $this->belongsTo('App\Models\AlCustomer');
    }
	
	public function alProject()
    {
        return $this->belongsTo('App\Models\AlProject');
    }

    public function alBudgetingExpense()
    {
        return $this->hasMany('App\Models\AlBudgetingExpense');
    }
	
	public function alIncome()
    {
        $arr = AlIncome::where('al_project_id',$this->al_project_id)->get();
		
		return $arr;
    }
	
	public function alExpense()
    {
        $arr = AlExpense::where('al_project_id',$this->al_project_id)->whereHas('alExpensePay')->get();
		
		return $arr;
    }
	
	public function type() 
    {
        switch($this->is_ppn) {
            case '1':
                $ppn = 'PJK';
                break;
            case '2':
                $ppn = 'Real Pengadaan';
                break;
            default:
                $ppn = 'Invalid';
                break;
        }

        return $ppn;
    }
	
	public function getTotalExpense(){
		$total = $this->total_deposit + $this->total_admin + $this->total_dk;
		
		return $total;
	}
}
