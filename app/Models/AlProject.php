<?php

namespace App\Models;

use App\Helper\SMB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlProject extends Model {

    use HasFactory;

    protected $table      = 'al_projects';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'code',
        'al_customer_id',
		'city_id',
        'name',
        'date',
		'is_ppn',
		'note',
		'progress',
		'field_of_work',
		'location'
    ];
	
	public function is_ppn() 
    {
        switch($this->is_ppn) {
            case '1':
                $ppn = 'Ya';
                break;
            case '0':
                $ppn = 'Tidak';
                break;
            default:
                $ppn = 'Invalid';
                break;
        }

        return $ppn;
    }

    public static function generateCode($al_customer_id)
    {
		$customer = AlCustomer::find($al_customer_id);
		
		$initial = $customer->alInstitute->initial.'-'.$customer->initial;
		
        $query = AlProject::selectRaw('LEFT(code, 4) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '0001';
        }

        return str_pad($code, 4, 0, STR_PAD_LEFT).'/'.$initial.'/'.date('d').'/'.SMB::getRomawi(date('n')).'/'.date('y');
    }
	
	public function generateSPH()
    {
		$initial = $this->alCustomer->alInstitute->initial.'-'.$this->alCustomer->initial;
		
        return substr($this->code,0,4).'/PTA-SPH/'.$initial.'/'.SMB::getRomawi(date('n',strtotime($this->date))).'/'.date('y',strtotime($this->date));
    }

    public function alCustomer()
    {
        return $this->belongsTo('App\Models\AlCustomer');
    }
	
	public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
	
	public function alSph()
    {
        return $this->hasMany('App\Models\AlSph');
    }
	
	public function latestSphContractNo()
    {
        $query = AlSph::where('al_project_id', $this->id)->orderByRaw('revision DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            return $query[0]->contract_no;
        } else {
            return '-';
        }
    }
	
	public function latestSphContractDate()
    {
        $query = AlSph::where('al_project_id', $this->id)->orderByRaw('revision DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            return $query[0]->contract_date;
        } else {
            return '-';
        }
    }
	
	public function latestSphNominal()
    {
        $query = AlSph::where('al_project_id', $this->id)->orderByRaw('revision DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            return $query[0]->grandtotal;
        } else {
            return 0;
        }
    }
	
	public function alApplicationLetter()
    {
        return $this->hasMany('App\Models\AlApplicationLetter');
    }
	
	public function alPurchase()
    {
        return $this->hasMany('App\Models\AlPurchase');
    }
	
	public function alChecklistProject()
    {
        return $this->hasMany('App\Models\AlChecklistProject');
    }
	
	public function getPercentComplete(){
		$totalchecklist = AlChecklist::count();
		$totalthisproject = count($this->alChecklistProject);
		
		$percentage = round($totalthisproject / $totalchecklist * 100,2);
		
		return $percentage;
	}
	
	public function alDocumentProject()
    {
        return $this->hasMany('App\Models\AlDocumentProject');
    }
	
	public function alProjectSpk()
    {
        return $this->hasMany('App\Models\AlProjectSpk');
    }
	
	public function alIncome()
    {
        return $this->hasMany('App\Models\AlIncome');
    }
	
	public function alExpense()
    {
        return $this->hasMany('App\Models\AlExpense');
    }
	
	public function alBudgeting()
    {
        return $this->hasMany('App\Models\AlBudgeting');
    }
	
	public function alInvoice()
    {
        return $this->hasMany('App\Models\AlInvoice');
    }
	
	public function checkDocument($document_id){
		$data = AlDocumentProject::where('al_project_id',$this->id)->where('al_document_id',$document_id)->first();
		
		if($data){
			return 'checked';
		}else{
			return '';
		}
	}
	
	public function totalBudgetingIncome(){
		$total = 0;
		
		foreach($this->alBudgeting as $row){
			$total += $row->total_claim;
		}
		
		return $total;
	}
	
	public function totalBudgetingExpense(){
		$total = 0;
		
		foreach($this->alBudgeting as $row){
			$total += $row->getTotalExpense();
		}
		
		return $total;
	}
	
	public function totalIncome(){
		$total = 0;
		
		foreach($this->alIncome as $row){
			$total += $row->nominal;
		}
		
		return $total;
	}
	
	public function totalExpense(){
		$total = 0;
		
		foreach($this->alExpense as $row){
			$total += $row->totalPayment();
		}
		
		return $total;
	}
}
