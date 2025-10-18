<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'customers';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable   = [
        'city_id',
        'photo',
        'name',
        'constructor',
        'email',
        'phone',
        'password',
        'type',
        'points',
        'verification',
		'address',
		'address_npwp',
		'npwp',
		'finance_name',
		'finance_hp',
		'photo_ktp',
		'photo_npwp',
		'balance'
    ];

    public function type() 
    {
        switch($this->type) {
            case '1':
                $type = '<span class="text-success font-weight-bold">Online</span>';
                break;
            case '2':
                $type = '<span class="text-danger font-weight-bold">Offline</span>';
                break;
            case '3':
                    $type = '<span class="text-info font-weight-bold">Hybrid</span>';
                    break;
            default:
                $type = '<span class="text-warning font-weight-bold">Invalid</span>';
                break;
        }

        return $type;
    }

	public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

	
	public function project()
    {
        return $this->hasMany('App\Models\Project');
    }

    public function photo()
    {
        if(Storage::exists($this->photo)) {
            $photo = asset(Storage::url($this->photo));
        } else if($this->photo) {
            $photo = $this->photo;
        } else {
            $photo = asset('website/user.png');
        }

        return $photo;
    }
	
	public function photo_ktp()
    {
        if(Storage::exists($this->photo_ktp)) {
            $photo = asset(Storage::url($this->photo_ktp));
        } else if($this->photo_ktp) {
            $photo = $this->photo_ktp;
        } else {
            $photo = asset('website/empty.jpg');
        }

        return $photo;
    }
	
	public function photo_npwp()
    {
        if(Storage::exists($this->photo_npwp)) {
            $photo = asset(Storage::url($this->photo_npwp));
        } else if($this->photo_npwp) {
            $photo = $this->photo_npwp;
        } else {
            $photo = asset('website/empty.jpg');
        }

        return $photo;
    }

    public function cart()
    {
        return $this->hasMany('App\Models\Cart');
    }

    public function wishlist()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function getReceivableCustomer(){
		$allproject = Project::whereHas('projectSale',function($query){
			$query->whereHas('sales',function($query){
			})->where('customer_id', $this->id);
		})->get();
		
        $total_debit = 0;
        $total_credit = 0;
		$data = [];
		
		foreach($allproject as $row){
			$total_delivery = 0;
			$total_paid = 0;
			$total_customer_deposit = 0;
			$total_return = 0;
			$total_bill_unpaid = 0;

			//OPENING BALANCE 			
			$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->id)->get();
				
    		if(count($cb) > 0){
    			foreach($cb as $rowcb){
    				foreach($rowcb->cashBankDetail()->where('coa_id',67)->get() as $rowopb){
    					if($rowopb->type == '2'){
    							$data [] = [
            					'id'		  => $row->id,
            					'child_id'	  => $rowopb->cashBank->id,
            					'description' => $rowopb->cashBank->code.' - OPENING BALANCE CUSTOMER DEPOSIT',
            					'type'		  => 'credit',
            					'mode'		  => 'opening_balance',
            					'nominal'	  => $rowopb->nominal,
            					'date'   	  => $rowopb->cashBank->date,
            					'approval'    => '-'
            				];
    					}
    				}
    			}
    		}


			foreach ($row->projectDelivery()->whereNotNull('received_date')->where('is_sales','1')->get() as $rowpd) {
				$total_delivery += $rowpd->grandtotal_product + $rowpd->grandtotal_service;

				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowpd->id,
					'description' => $rowpd->proforma_code.' FROM -'.$rowpd->project->code,
					'type'		  => 'debit',
					'mode'		  => 'project_deliveries',
					'nominal'	  => $rowpd->grandtotal_product + $rowpd->grandtotal_service,
					'date'   	  => $rowpd->received_date,
					'approval'    => $rowpd->approve ? $rowpd->approve->name : '-'
				];
			}


			foreach($row->projectSaleReturn()->get() as $rowsr){
				$total_return += $rowsr->grandtotal;

				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowsr->id,
					'description' => $rowsr->code,
					'type'		  => 'credit',
					'mode'		  => 'project_sale_returns',
					'nominal'	  => $rowsr->grandtotal,
					'date'   	  => $rowsr->date_return,
					'approval'    => $rowsr->approve ? $rowsr->approve->name : '-',
				];
			}


		

			foreach(CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$row->id)->get() as $rowcb){
				foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowcb->id,
						'description' => $cbcb->cashBank->code.'-ADJUSTMENT',
						'type'		  => 'credit',
						'mode'		  => 'adjustment',
						'nominal'	  => $cbcb->nominal,
						'date'   	  => $cbcb->cashBank->created_at,
						'approval'    => '-',
					];
				}
			}

			
			foreach($row->projectPay()->get() as $rowpay){
				$total_paid +=  $rowpay->nominal;

				if($rowpay->coa_id == '67' && (!$rowpay->projectMainPayment->cashBank->customer_id ||$rowpay->projectMainPayment->cashBank->customer_id == 0)){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => 'CUSTOMER DEPPOSIT USED FOR PAYMENT- '.$rowpay->project->code,
						'type'		  => 'debit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}
				
				if($total_delivery - ($total_paid + $total_return) >= 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - PAYMENT FOR '.$rowpay->project->code,
						'type'		  => 'credit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}else if($total_delivery - ($total_paid + $total_return) < 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - PAYMENT FOR '.$rowpay->project->code,
						'type'		  => 'credit',
						'mode'		  => 'project_pays',
						'nominal'	  => $rowpay->nominal + ($total_delivery - ($total_paid + $total_return)),
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-'
					];
				}
				
			}

			foreach($row->projectPay()->get() as $rowpay){
				$total_customer_deposit += $rowpay->nominal;
				
				if($total_delivery - ($total_customer_deposit + $total_return)  < 0 ){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowpay->id,
						'description' => $rowpay->code.' - Customer Deposit',
						'type'		  => 'customer_deposit',
						'nominal'	  => $total_customer_deposit + $total_return  - $total_delivery,
						'date'   	  => $rowpay->date,
						'approval'    => $rowpay->approved ? $rowpay->approved->name : '-',
						'note'   	  => 'DOWN PAYMENT'
					];
				}
			}


			$total_unpaid_delivery = $total_delivery - $total_paid;
			foreach ($row->projectBill()->get() as $rowbill){
				if($total_unpaid_delivery - $rowbill->paidAR()['pays'] > 0){
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowbill->id,
						'description' => $rowbill->code,
						'type'		  => 'credit',
						'mode'		  => 'project_bills',
						'nominal'	  => $total_unpaid_delivery,
						'date'   	  => $rowbill->date,
						'approval'    => $rowbill->approved ? $rowbill->approved->name : '-',
					];

					$total_unpaid_delivery  = 0;
				}

				if($rowbill->paidAR()['pays'] <= 0){
					$total_bill_unpaid += $rowbill->nominal + $rowbill->nominal_service;
					$data [] = [
						'id'		  => $row->id,
						'child_id'	  => $rowbill->id,
						'description' => $rowbill->code,
						'type'		  => 'debit',
						'mode'		  => 'project_bills',
						'nominal'	  => ($rowbill->nominal + $rowbill->nominal_service),
						'date'   	  => $rowbill->date,
						'approval'    => $rowbill->approved ? $rowbill->approved->name : '-',
					];
				}
					
			}
		
		}

		foreach (CashBank::whereHas('cashBankDetail',function($query){ $query->where('coa_id',67);})->whereNotNull('customer_id')->where('customer_id', '!=', '0')->where('customer_id', $row->customer_id)->get() as $rowcb) {
			foreach($rowcb->cashBankDetail->where('coa_id',67)->where('type','1') as $rowcbdetail){
				$data [] = [
					'id'		  => $row->id,
					'child_id'	  => $rowcb->id,
					'description' => $rowcbdetail->cashBank->code.' CUSTOMER DEPOSIT - USED',
					'type'		  => 'debit',
					'mode'		  => 'customer_deposit',
					'nominal'	  => $rowcbdetail->nominal,
					'date'   	  => $rowcbdetail->cashBank->created_at,
					'approval'    => '-',
					'note'		  => 'CUSTOMER DEPOSIT FROM '.$rowcb->customer->name.' USED BY '. $rowcb->lookable->customer->name
				];
			}
		}

		$arrayResult = collect($data)->sortBy('id')->toArray();


        foreach ($arrayResult as $row) {
           if($row['type'] == 'debit') {
                $total_debit += $row['nominal'];
           }else if($row['type'] == 'credit'){
                $total_credit += $row['nominal'];
           }else{
				$total_credit += $row['nominal'];
		   }
        }

        $balance = $total_debit - $total_credit;
        

        return round($balance < 0 ? 0 : $balance, 0);
    }
}
