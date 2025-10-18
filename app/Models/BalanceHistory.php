<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class BalanceHistory extends Model {

    use HasFactory;

    protected $table      = 'balance_histories';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'cash_or_bank',
		'coa_id',
		'cash_bank_reference',
		'user_id',
		'branch',
		'nominal',
		'type',
		'note',
		'date',
		'image'
    ];
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $gambar = asset(Storage::url($this->image));
        } else {
            $gambar = asset('website/empty.jpg');
        }

        return $gambar;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
	
	public function getInformation(){
		
		$info = '-';
		
		if($this->cash_bank_reference){
			$cek = CashBank::find($this->cash_bank_reference);
			if($cek){
				if($cek->lookable_type == 'project_main_payments'){
					$info = 'CUSTOMER '.$cek->lookable->customer->name;
				}elseif($cek->lookable_type == 'project_pays'){
					$info = 'CUSTOMER '.$cek->lookable->project->customer->name;
				}elseif($cek->lookable_type == 'project_payments'){
					$info = 'SUPPLIER. '.(isset($cek->lookable->projectPurchase) ? $cek->lookable->projectPurchase->supplier->name : 'NONE');
				}
			}
		}else{
			$cek = CashBank::where('code','BPC-'.$this->id)->first();
			if($cek){
				if($cek->lookable_type == 'project_main_payments'){
					$info = 'CUSTOMER '.$cek->lookable->customer->name;
				}elseif($cek->lookable_type == 'project_pays'){
					$info = 'CUSTOMER '.$cek->lookable->project->customer->name;
				}elseif($cek->lookable_type == 'project_payments'){
					$info = 'SUPPLIER. '.(isset($cek->lookable->projectPurchase) ? $cek->lookable->projectPurchase->supplier->name : 'NONE');
				}
			}
		}
		
		return $info;
	}
	
	public function branch() 
    {
        switch($this->branch) {
			case '1':
                $branch = 'PTA';
                break;
            case '2':
                $branch = 'SMB';
                break;
            default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }
	
	public function interestBankAndLoan(){
		$betul = false;
		$cek = CashBank::where('code','BPC-'.$this->id)->first();
					
		if($cek){
			foreach($cek->cashBankDetail->where('coa_id',207) as $row){
				$betul = true;
			}
			
			foreach($cek->cashBankDetail()->whereHas('coa',function($query){
				$query->where('parent_id',221);
			})->get() as $row){
				$betul = true;
			}
		}
		
		return $betul;
	}
}