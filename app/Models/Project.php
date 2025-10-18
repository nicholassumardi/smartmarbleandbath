<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model {

    use HasFactory;

    protected $table      = 'projects';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
        'user_id',
        'sales_id',
        'country_id',
        'city_id',
		'city_franco_id',
        'code',
        'name',
        'customer_id',
        'timeline',
        'manager',
        'consultant',
        'owner',
		'coa_id',
        'payment_method',
		'term_payment',
        'supply_method',
        'ppn',
        'progress',
		'delivery_cost',
		'cutting_cost',
		'misc_cost',
		'discount',
		'purchase_order_id',
		'in_store',
		'reject_reason',
		'remark'
    ];

    public static function generateCode()
    {
        $query = Project::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '000001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'PJ/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	public static function generateCodeInStore()
    {
        $query = Project::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '000001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'PJR/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	public function inStore() 
    {
        switch($this->in_store) {
            case '1':
                $in_store = 'Yes';
                break;
            case '0':
                $in_store = 'No';
                break;
            default:
                $in_store = 'Invalid';
                break;
        }

        return $in_store;
    }

    public function paymentMethod() 
    {
        switch($this->payment_method) {
            case '11':
                $payment_method = 'Cash Before Delivery';
                break;
            case '12':
                $payment_method = 'Cash After Delivery';
                break;
			case '13':
                $payment_method = 'Cash with Down Payment';
                break;
			case '21':
                $payment_method = 'Credit with Cover BG';
                break;
			case '22':
                $payment_method = 'Credit with SKBDN';
                break;
			case '23':
                $payment_method = 'Credit with SCF';
                break;
			case '24':
                $payment_method = 'Credit with Down Payment';
                break;
			case '25':
                $payment_method = 'Credit without Down Payment';
                break;
            default:
                $payment_method = 'Invalid';
                break;
        }

        return $payment_method;
    }
	
	public function paymentMethodIdn() 
    {
        switch($this->payment_method) {
            case '11':
                $payment_method = 'Cash Sebelum Pengiriman';
                break;
            case '12':
                $payment_method = 'Cash Setelah Delivery';
                break;
			case '13':
                $payment_method = 'Cash dengan Uang Muka';
                break;
			case '21':
                $payment_method = 'Kredit dengan Cover BG';
                break;
			case '22':
                $payment_method = 'Kredit dengan SKBDN';
                break;
			case '23':
                $payment_method = 'Kredit dengan SCF';
                break;
			case '24':
                $payment_method = 'Kredit dengan Uang Muka';
                break;
			case '25':
                $payment_method = 'Kredit tanpa Uang Muka';
                break;
            default:
                $payment_method = 'Invalid';
                break;
        }

        return $payment_method;
    }
	
	public function paymentTerm() 
    {
        switch($this->term_payment) {
            case '0':
                $payment_term = 'Default (0 days)';
                break;
            case '7':
                $payment_term = '7 Days';
                break;
			case '14':
                $payment_term = '14 Days';
                break;
			case '30':
                $payment_term = '30 Days';
                break;
			case '45':
                $payment_term = '45 Days';
                break;
			case '60':
                $payment_term = '60 Days';
                break;
			case '90':
                $payment_term = '90 Days';
                break;
			case '120':
                $payment_term = '120 Days';
                break;
			case '180':
                $payment_term = '180 Days';
                break;
            default:
                $payment_term = 'Invalid';
                break;
        }

        return $payment_term;
    }
	
	public function paymentTermIdn() 
    {
        switch($this->term_payment) {
            case '0':
                $payment_term = 'Langsung Bayar (0 Hari)';
                break;
            case '7':
                $payment_term = '7 Hari';
                break;
			case '14':
                $payment_term = '14 Hari';
                break;
			case '30':
                $payment_term = '30 Hari';
                break;
			case '45':
                $payment_term = '45 Hari';
                break;
			case '60':
                $payment_term = '60 Hari';
                break;
			case '90':
                $payment_term = '90 Hari';
                break;
			case '120':
                $payment_term = '120 Hari';
                break;
			case '180':
                $payment_term = '180 Hari';
                break;
            default:
                $payment_term = 'Invalid';
                break;
        }

        return $payment_term;
    }

    public function supplyMethod() 
    {
        switch($this->supply_method) {
            case '1':
                $supply_method = 'Full';
                break;
            case '2':
                $supply_method = 'Partial';
                break;
            default:
                $supply_method = 'Invalid';
                break;
        }

        return $supply_method;
    }

	public function serviceCost()
    {
        return $this->hasOne('App\Models\ServiceCost');
    }

    public function ppn() 
    {
        switch($this->ppn) {
            case '1':
                $ppn = 'Yes';
                break;
            case '0':
                $ppn = 'No';
                break;
            default:
                $ppn = 'Invalid';
                break;
        }

        return $ppn;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

	public function sales()
    {
        return $this->belongsTo('App\Models\User', 'sales_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
	
	public function city_franco()
    {
        return $this->belongsTo('App\Models\City','city_franco_id','id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function projectProduct()
    {
        return $this->hasMany('App\Models\ProjectProduct');
    }
	
	public function projectReturnMemo()
    {
        return $this->hasMany('App\Models\ProjectReturnMemo');
    }

    public function projectConsultantMeeting()
    {
        return $this->hasMany('App\Models\ProjectConsultantMeeting');
    }

    public function projectSample()
    {
        return $this->hasMany('App\Models\ProjectSample');
    }
	
	public function projectLog()
    {
        return $this->hasMany('App\Models\ProjectLog');
    }

    public function projectPayment()
    {
        return $this->hasMany('App\Models\ProjectPayment');
    }

    public function projectProduction()
    {
        return $this->hasMany('App\Models\ProjectProduction');
    }

    public function projectShipment()
    {
        return $this->hasMany('App\Models\ProjectShipment');
    }

    public function projectDelivery()
    {
        return $this->hasMany('App\Models\ProjectDelivery');
    }

    public function projectPay()
    {
        return $this->hasMany('App\Models\ProjectPay');
    }
	
	public function projectNote()
    {
		$arr = [];
		
        $data = ProjectNote::where('notable_type','projects')->where('notable_id',$this->id)->get();
		
		foreach($data as $row){
			$arr[] = [
				'date'		=> $row->updated_at,
				'content'	=> $row->note
			];
		}
		
		foreach($this->projectSale as $rowsale){
			$data = ProjectNote::where('notable_type','project_sales')->where('notable_id',$rowsale->id)->get();
			
			foreach($data as $row){
				$arr[] = [
					'date'		=> $row->updated_at,
					'content'	=> $row->note
				];
			}
		}
		
		return $arr;
    }
	
	public function projectPicture()
    {
        return $this->hasMany('App\Models\ProjectPicture');
    }
	
	public function budgetingProject()
    {
        return $this->hasMany('App\Models\BudgetingProject');
    }
	
	public function budgetingProjectByDate()
    {
		$status = false;
		// $status = true;
		
		if(date('Y-m-d',strtotime($this->created_at)) >= '2022-09-22'){
			if($this->budgetingProject()->exists()){
				foreach($this->budgetingProject as $row){
					if($row->checked_by){
						$status = true;
					}
				}
			}else{
				$status = false;
			}
		}else{
			$status = true;
		}
		
		return $status;
    }

	public function isCloseSO()
    {
		$status = false;
		//$status = true;
	
		if($this->projectSale()->exists()){
			foreach($this->projectSale as $row){
				if($row->approved_closed){
					$status = true;
				}
			}
		}else{
			$status = false;
		}

		
		return $status;
    }
	
	public function projectBill()
    {
        return $this->hasMany('App\Models\ProjectBill');
    }
	
	public function projectWarehouse()
    {
        return $this->hasMany('App\Models\ProjectWarehouse');
    }
	
	public function projectQuotation()
    {
        return $this->hasMany('App\Models\ProjectQuotation');
    }
	
	public function projectNegotiation()
    {
        return $this->hasMany('App\Models\ProjectNegotiation');
    }
	
	public function projectSale()
    {
        return $this->hasMany('App\Models\ProjectSale');
    }
	
	public function projectPurchase()
    {
        return $this->hasMany('App\Models\ProjectPurchase');
    }
	
	public function projectProforma()
    {
        return $this->hasMany('App\Models\ProjectProforma');
    }
	
	public function projectPurchaseReturn()
    {
        return $this->hasMany('App\Models\ProjectPurchaseReturn');
    }
	
	public function projectFromStock()
    {
        return $this->hasMany('App\Models\ProjectFromStock');
    }
	
	public function projectSaleReturn()
    {
        return $this->hasMany('App\Models\ProjectSaleReturn');
    }
	
	public function projectTroubleshooting()
    {
        return $this->hasMany('App\Models\ProjectTroubleshooting');
    }
	
	public function getApprovalQuotation()
	{
		$data = ProjectQuotation::where('project_id', $this->id)->orderByDesc('id')->first();
		return $data;
	}
	
	public function lastQuotation()
	{
		$result = ProjectQuotation::where('project_id',$this->id)->latest()->first();
		
		return $result;
	}
	
	public function latestQuotation()
	{
		$pp = ProjectQuotation::where('project_id',$this->id)->orderBy('created_at','desc')->first();
		return $pp;
	}
	
	public function latestSample()
	{
		$pp = ProjectSample::where('project_id',$this->id)->orderBy('created_at','desc')->first();
		return $pp;
	}
	
	public function latestSale()
	{
		$pp = ProjectSale::where('project_id',$this->id)->orderBy('created_at','desc')->first();
		return $pp;
	}
	
	public function latestDownPaymentSale()
	{
		$pp = ProjectPay::where('project_id',$this->id)->where('payment_method','1')->orderBy('created_at','desc')->first();
		return $pp;
	}
	
	public function latestPurchase()
	{
		$pp = ProjectPurchase::where('project_id',$this->id)->orderBy('created_at','desc')->first();
		return $pp;
	}
	
	public function latestDownPaymentPurchase()
	{
		$pp = ProjectPayment::where('project_id',$this->id)->where('status','1')->orderBy('created_at','desc')->first();
		return $pp;
	}
	
	public function latestFullPaymentPurchase()
	{
		$pp = ProjectPayment::where('project_id',$this->id)->orderBy('created_at','desc')->first();
		return $pp;
	}
	
	public function latestDelivery()
	{
		$pp = ProjectDelivery::where('project_id',$this->id)->orderBy('created_at','desc')->first();
		return $pp;
	}
	
	public function getBalance(){
		$totalsale = 0;
		$totalsalereturn = 0;
		$totalpay = 0;
		
		foreach($this->projectSale as $ps){
			$totalsale += $ps->grandtotal_product + $ps->grandtotal_service;
			foreach($ps->projectSaleReturn as $psr){
				$totalsalereturn += $psr->getTotal();
			}
		}
		
		foreach($this->projectPay as $pp){
			$totalpay += $pp->nominal;
		}
		
		$balance = $totalsale - $totalsalereturn - $totalpay;
		
		return $balance;
	}
	
	public function getBalanceDeliveredBill($psr_id = NULL){
		$totaldelivery = 0;
		$totalpay = 0;
		$totalBill= 0;

		foreach($this->projectDelivery->whereNotNull('received_date') as $row){
			$totaldelivery += $row->grandtotal_product + $row->grandtotal_service;
		}
		
		foreach($this->projectPay as $pp){
			$totalpay += $pp->nominal;
		}
		
		foreach($this->projectBill as $pb){
			$paid = $pb->paid();
			$nominal_bill = ($pb->balanceJournal() - $paid) < 0 ? '0' : ($pb->balanceJournal() - $paid);
			$totalpay += $nominal_bill;
		}

		foreach($this->projectSaleReturn as $psr){
			if($psr->id !== (int)$psr_id){
				$totalpay += $psr->grandtotal;
			}
		}
		
		$balance = round($totalpay, 2) - round($totaldelivery, 2);


		return $balance;
	}
	
	public function getBalanceCustomer(){
		$data = Project::where('customer_id',$this->customer_id)->get();
		
		$balance = 0;
		
		foreach($data as $project){
			
			foreach($project->projectSale as $row){
				if((str_replace(',','.',str_replace('.','',$row->getPaid())) - str_replace(',','.',str_replace('.','',$row->getTotal()))) > 0){
					$balance += str_replace(',','.',str_replace('.','',$row->getPaid())) - str_replace(',','.',str_replace('.','',$row->getTotal()));
				}else{
					$balance += 0;
				}
			}
		}
		
		return number_format($balance,0,',','.');
	}
	
	public function getTotalSale(){
		$totalsale = 0;
		
		foreach($this->projectSale as $ps){
			$totalsale += round(str_replace(',','.',str_replace('.','',$ps->getTotal())),0);
			foreach($ps->projectSaleReturn as $psr){
				$totalsale -= $psr->getTotal();
			}
		}
		
		return round($totalsale);
	}
	
	public function getTotalPurchase(){
		$totalpurchase = 0;
		
		foreach($this->projectPurchase as $pp){
			$totalpurchase += round(str_replace(',','.',str_replace('.','',$pp->getTotal())),0);
			foreach($pp->projectPurchaseReturn as $ppr){
				$totalpurchase -= $ppr->getTotal();
			}
		}
		
		return round($totalpurchase);
	}
	
	public function getTotalProject(){
		if($this->timeline < '2022-04-01'){
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		}else{
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}
	
		$total = 0;
		
		foreach($this->projectProduct as $key => $pp){
			if($pp->unit == '2' || $pp->unit == '3'){
				$price = $pp->best_price > 0 ? $pp->best_price : $pp->recommended_price;
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				
				if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
					$countbox = $pp->qty;
					$total += $price * ($countbox);
				}else{
					if($m2 < 1.1 && date('Y-m',strtotime($this->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
						$countbox = $pp->qty;
						$total += $price * ($countbox);
					}else{
						$countbox = ceil(round($pp->qty / $m2,2));
						$total += $price * $m2 * ($countbox);
					}
					
				}
			}elseif($pp->unit == '1' || $pp->unit == '4'){
				$price = $pp->best_price > 0 ? $pp->best_price : $pp->recommended_price;
				$total += $price * $pp->qty;
			}
		}
		
		$totalafterdiscount = $total - $this->discount;
		
		$totalafterppn = $this->ppn == '1' ? $totalafterdiscount + ($totalafterdiscount * $persenppn) : $totalafterdiscount;
		
		$totalafterservice = $totalafterppn + $this->delivery_cost + $this->cutting_cost + $this->misc_cost;
			
		return $totalafterservice;
	}
	
	public function totalDelivered(){
		
		$arr = [];
		$total = 0;
		$ppn = 0;
		$service = 0;
		$return = 0;
		
		foreach($this->projectSaleReturn as $row){
			$return += $row->getTotalWithoutPpn();
		}
 		
		foreach($this->projectDelivery->whereNotNull('received_date')->where('is_sales','1') as $row){
			
			if(date('Y-m-d',strtotime($row->projectSale->created_at)) < '2022-04-01'){
				$persenppn = 0.1;
				$ppnpembagi = 1.1;
			}else{
				$persenppn = 0.11;
				$ppnpembagi = 1.11;
			}
			
			if($this->ppn == '1'){
				$total += $row->getTotal()['totaldelivery'] / $ppnpembagi;
				$ppn += $row->getTotal()['totaldelivery'] * $persenppn;
			}else{
				$total += $row->getTotal()['totaldelivery'];
			}
			
			$service += $row->getServiceCost();
		}
		
		$arr['total'] = $total;
		$arr['ppn'] = $ppn;
		$arr['service'] = $service;
		$arr['return'] = $return;
		
		return $arr;
	}
	
	public function getProjectJournal($coa,$start_date,$finish_date){
		$total = 0;
		
		$start_date = $start_date ? $start_date : '';
		$finish_date = $finish_date ? $finish_date : '';
		
		if($finish_date && $start_date){
			$journal = Journal::where('coa_id',$coa)->whereRaw('DATE(date_transaction) >= "'.$start_date.'" AND DATE(date_transaction) <= "'.$finish_date.'"')->get();
		}else{
			$journal = Journal::where('coa_id',$coa)->get();
		}
		
		$arr = ['1','5','6'];
		
		if($journal){
			foreach($journal as $row){
				if(($row->journalable->lookable_type == 'projects' && $row->journalable->lookable_id == $this->id) || ($row->journalable->lookable_type == 'project_deliveries' && $row->journalable->lookable->project->id == $this->id) || ($row->journalable->lookable_type == 'project_sale_returns' && $row->journalable->lookable->project->id == $this->id)){
					if($row->type == '1'){
						if(in_array(substr($row->coa->code,0,1),$arr) || substr($row->coa->code,0,4) == '7.200'){
							$total += $row->nominal;
						}else{
							$total -= $row->nominal;
						}
					}elseif($row->type == '2'){
						if(in_array(substr($row->coa->code,0,1),$arr) || substr($row->coa->code,0,4) == '7.200'){
							$total -= $row->nominal;
						}else{
							$total += $row->nominal;
						}
					}
				}
			}
		}
		
		return $total;
	}
	
	public function getTotalLandedCost($start_date,$finish_date){
		$total = 0;
		
		$total += $this->getProjectJournal(122,$start_date,$finish_date);
		$total += $this->getProjectJournal(288,$start_date,$finish_date);
		$total += $this->getProjectJournal(289,$start_date,$finish_date);
		$total += $this->getProjectJournal(291,$start_date,$finish_date);
		$total += $this->getProjectJournal(292,$start_date,$finish_date);
		$total += $this->getProjectJournal(293,$start_date,$finish_date);
		$total += $this->getProjectJournal(294,$start_date,$finish_date);
		$total += $this->getProjectJournal(295,$start_date,$finish_date);
		
		return $total;
	}
	
	public function getTotalMarketingCost($start_date,$finish_date){
		$total = 0;
		
		$total += $this->getProjectJournal(134,$start_date,$finish_date);
		$total += $this->getProjectJournal(139,$start_date,$finish_date);
		$total += $this->getProjectJournal(296,$start_date,$finish_date);
		$total += $this->getProjectJournal(297,$start_date,$finish_date);
		$total += $this->getProjectJournal(298,$start_date,$finish_date);
		$total += $this->getProjectJournal(299,$start_date,$finish_date);
		$total += $this->getProjectJournal(212,$start_date,$finish_date);
		$total += $this->getProjectJournal(213,$start_date,$finish_date);
		
		return $total;
	}
	
	public function getNettProfit(){
		$netprofit = 0;
		
		$coa = Coa::where('parent_id',0)->whereRaw('SUBSTR(code,1,1) = "4" OR SUBSTR(code,1,1) = "5" OR SUBSTR(code,1,1) = "6" OR SUBSTR(code,1,1) = "7"')->orderBy('code')->get();
		
		$total_revenue = 0;
		$total_cogs = 0;
		$total_fixed_cost = 0;
		$total_variable_cost = 0;
		$total_other_expenses = 0;
		$total_repair_expenses = 0;
		$total_depreciation = 0;
		$total_capex = 0;
		$total_other_income = 0;
		$total_other_deduction = 0;
		
		foreach($coa->where('parent_id',0) as $primarykey => $rowparent){
			$balance = 0;
			if(count($rowparent->child()) == 0){
				$balance = $rowparent->getProjectCoa($this->id,$this->user->branch)['total_balance'];
				
				if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
					$total_revenue += $balance;
				}
				
				if(substr($rowparent->code,0,5) == '5.000' || substr($rowparent->code,0,5) == '6.000' || substr($rowparent->code,0,5) == '6.100'){
					$total_cogs += $balance;
				}
				
				if(substr($rowparent->code,0,5) == '6.200'){
					$total_fixed_cost += $balance;
				}
				
				if(substr($rowparent->code,0,9) == '6.2100.02'){
					$total_variable_cost += $balance;
				}
				
				if(substr($rowparent->code,0,9) == '6.2100.03'){
					$total_other_expenses += $balance;
				}
				
				if(substr($rowparent->code,0,6) == '6.2200'){
					$total_repair_expenses += $balance;
				}
				
				if(substr($rowparent->code,0,5) == '6.300'){
					$total_depreciation += $balance;
				}
				
				if(substr($rowparent->code,0,5) == '6.400'){
					$total_capex += $balance;
				}
				
				if(substr($rowparent->code,0,5) == '7.100'){
					$total_other_income += $balance;
				}
				
				if(substr($rowparent->code,0,5) == '7.200'){
					$total_other_deduction += $balance;
				}
			}
			
			foreach($rowparent->child() as $rowchild){
				$balance = 0;
				if(count($rowchild->child()) == 0){
					$balance = $rowchild->getProjectCoa($this->id,$this->user->branch)['total_balance'];
					
					if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
						$total_revenue += $balance;
					}
					
					if(substr($rowchild->code,0,5) == '5.000' || substr($rowchild->code,0,5) == '6.000' || substr($rowchild->code,0,5) == '6.100'){
						$total_cogs += $balance;
					}
					
					if(substr($rowchild->code,0,5) == '6.200'){
						$total_fixed_cost += $balance;
					}
					
					if(substr($rowchild->code,0,9) == '6.2100.02'){
						$total_variable_cost += $balance;
					}
					
					if(substr($rowchild->code,0,9) == '6.2100.03'){
						$total_other_expenses += $balance;
					}
					
					if(substr($rowchild->code,0,6) == '6.2200'){
						$total_repair_expenses += $balance;
					}
					
					if(substr($rowchild->code,0,5) == '6.300'){
						$total_depreciation += $balance;
					}
					
					if(substr($rowchild->code,0,5) == '6.400'){
						$total_capex += $balance;
					}
					
					if(substr($rowchild->code,0,5) == '7.100'){
						$total_other_income += $balance;
					}
					
					if(substr($rowchild->code,0,5) == '7.200'){
						$total_other_deduction += $balance;
					}
				}
				
				foreach($rowchild->child() as $rowgrandchild){
					$balance = 0;
					if(count($rowgrandchild->child()) == 0){
						$balance = $rowgrandchild->getProjectCoa($this->id,$this->user->branch)['total_balance'];
						
						if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
							$total_revenue += $balance;
						}
						
						if(substr($rowgrandchild->code,0,5) == '5.000' || substr($rowgrandchild->code,0,5) == '6.000' || substr($rowgrandchild->code,0,5) == '6.100'){
							$total_cogs += $balance;
						}
						
						if(substr($rowgrandchild->code,0,5) == '6.200'){
							$total_fixed_cost += $balance;
						}
						
						if(substr($rowgrandchild->code,0,9) == '6.2100.02'){
							$total_variable_cost += $balance;
						}
						
						if(substr($rowgrandchild->code,0,9) == '6.2100.03'){
							$total_other_expenses += $balance;
						}
						
						if(substr($rowgrandchild->code,0,6) == '6.2200'){
							$total_repair_expenses += $balance;
						}
						
						if(substr($rowgrandchild->code,0,5) == '6.300'){
							$total_depreciation += $balance;
						}
						
						if(substr($rowgrandchild->code,0,5) == '6.400'){
							$total_capex += $balance;
						}
						
						if(substr($rowgrandchild->code,0,5) == '7.100'){
							$total_other_income += $balance;
						}
						
						if(substr($rowgrandchild->code,0,5) == '7.200'){
							$total_other_deduction += $balance;
						}
					}
					
					foreach($rowgrandchild->child() as $rowgrandgrandchild){
						$balance = 0;
						if(count($rowgrandgrandchild->child()) == 0){
							$balance = $rowgrandgrandchild->getProjectCoa($this->id,$this->user->branch)['total_balance'];
							
							if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
								$total_revenue += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,5) == '5.000' || substr($rowgrandgrandchild->code,0,5) == '6.000' || substr($rowgrandgrandchild->code,0,5) == '6.100'){
								$total_cogs += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,5) == '6.200'){
								$total_fixed_cost += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,9) == '6.2100.02'){
								$total_variable_cost += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,9) == '6.2100.03'){
								$total_other_expenses += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,6) == '6.2200'){
								$total_repair_expenses += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,5) == '6.300'){
								$total_depreciation += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,5) == '6.400'){
								$total_capex += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,5) == '7.100'){
								$total_other_income += $balance;
							}
							
							if(substr($rowgrandgrandchild->code,0,5) == '7.200'){
								$total_other_deduction += $balance;
							}
						}
					}
				}
			}
		}
		
		$netprofit = $total_revenue - $total_cogs - $total_fixed_cost - $total_variable_cost - $total_other_expenses - $total_repair_expenses - $total_depreciation - $total_capex + $total_other_income - $total_other_deduction;
		
		return $netprofit;
	}
	
	public function getRevenue(){
		
		$coa = Coa::where('parent_id',0)->whereRaw('SUBSTR(code,1,1) = "4" OR SUBSTR(code,1,1) = "5" OR SUBSTR(code,1,1) = "6" OR SUBSTR(code,1,1) = "7"')->orderBy('code')->get();
		
		$total_revenue = 0;
		
		foreach($coa->where('parent_id',0) as $primarykey => $rowparent){
			$balance = 0;
			if(count($rowparent->child()) == 0){
				$balance = $rowparent->getProjectCoa($this->id,$this->user->branch)['total_balance'];
				
				if(substr($rowparent->code,0,5) == '4.000' || substr($rowparent->code,0,5) == '4.100'){
					$total_revenue += $balance;
				}
				
			}
			
			foreach($rowparent->child() as $rowchild){
				$balance = 0;
				if(count($rowchild->child()) == 0){
					$balance = $rowchild->getProjectCoa($this->id,$this->user->branch)['total_balance'];
					
					if(substr($rowchild->code,0,5) == '4.000' || substr($rowchild->code,0,5) == '4.100'){
						$total_revenue += $balance;
					}
					
				}
				
				foreach($rowchild->child() as $rowgrandchild){
					$balance = 0;
					if(count($rowgrandchild->child()) == 0){
						$balance = $rowgrandchild->getProjectCoa($this->id,$this->user->branch)['total_balance'];
						
						if(substr($rowgrandchild->code,0,5) == '4.000' || substr($rowgrandchild->code,0,5) == '4.100'){
							$total_revenue += $balance;
						}
						
					}
					
					foreach($rowgrandchild->child() as $rowgrandgrandchild){
						$balance = 0;
						if(count($rowgrandgrandchild->child()) == 0){
							$balance = $rowgrandgrandchild->getProjectCoa($this->id,$this->user->branch)['total_balance'];
							
							if(substr($rowgrandgrandchild->code,0,5) == '4.000' || substr($rowgrandgrandchild->code,0,5) == '4.100'){
								$total_revenue += $balance;
							}
							
						}
					}
				}
			}
		}
		
		return $total_revenue;
	}
	
	function dateDiffInDays($date1, $date2){
		$diff = strtotime($date2) - strtotime($date1);
  
		return abs(round($diff / 86400));
	}
	
	public function getBalanceAR($filter){
		$totalDelivered = 0;
		$total30 = 0;
		$total60 = 0;
		$total90 = 0;
		$totalover = 0;
		$totalPay = 0;
		$totalBill = 0;
		$date = date('Y-m-t',strtotime($filter));
		$arrdetail = [];
		
		foreach($this->projectSaleReturn()->whereRaw("date_return <= '$date'")->get() as $row){
			$totalPay += $row->grandtotal;
		}
		
		foreach($this->projectBill()->whereRaw("date <= '$date'")->get() as $row){
			$pay = $row->paidPeriod($date);
			
			$totalPay += ($row->nominal + $row->nominal_service - $pay);
		}
		
		foreach($this->projectPay()->whereRaw("date <= '$date'")->get() as $row){
			$totalPay += $row->nominal;
		}
		
		foreach($this->projectDelivery()->where('is_sales','1')->whereNotNull('received_date')->whereRaw("received_date <= '$date'")->get() as $row){
			$totalDelivered += round($row->grandtotal_product + $row->grandtotal_service);
			$diffdays = $this->dateDiffInDays($row->received_date, $date);
			if($diffdays <= 30){
				$total30 += round($row->grandtotal_product + $row->grandtotal_service);
			}elseif($diffdays <= 60){
				$total60 += round($row->grandtotal_product + $row->grandtotal_service);
			}elseif($diffdays <= 90){
				$total90 += round($row->grandtotal_product + $row->grandtotal_service);
			}elseif($diffdays > 90){
				$totalover += round($row->grandtotal_product + $row->grandtotal_service);
			}
		}
		
		$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$this->id)->whereRaw("date <= '$date'")->get();
				
		if(count($cb) > 0){
			foreach($cb as $rowcb){
				foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
					if($cbcb->type == '2'){
						$totalPay += $cbcb->nominal;
					}
				}
			}
		}
		
		$balance = $totalPay;
		
		if($totalover > 0){
			if($balance > 0){
				if($totalover - $balance < 0){
					$totalover = 0;
				}else{
					$totalover = $totalover - $balance;
				}
				$balance -= $totalover;
			}
		}
		
		if($total90 > 0){
			if($balance > 0){
				if($total90 - $balance < 0){
					$total90 = 0;
				}else{
					$total90 = $total90 - $balance;
				}
				$balance -= $total90;
			}
		}
		
		if($total60 > 0){
			if($balance > 0){
				if($total60 - $balance < 0){
					$total60 = 0;
				}else{
					$total60 = $total60 - $balance;
				}
				$balance -= $total60;
			}
		}
		
		if($total30 > 0){
			if($balance > 0){
				if($total30 - $balance < 0){
					$total30 = 0;
				}else{
					$total30 = $total30 - $balance;
				}
				$balance -= $total60;
			}
		}
		
		$result = [
			'total'		=> round($totalDelivered - $totalPay,0),
			'total30'	=> round($total30,0),
			'total60'	=> round($total60,0),
			'total90'	=> round($total90,0),
			'totalover'	=> round($totalover,0)
		];
		
		return $result;
	}
	
	public function getARBill($filter){
		$totalAr = 0;
		$total30 = 0;
		$total60 = 0;
		$total90 = 0;
		$totalover = 0;
		$totalPay = 0;
		$date = date('Y-m-t',strtotime($filter));
		
		foreach($this->projectBill()->whereRaw("date <= '$date'")->get() as $row){
			if($row->journal()){
				$pay = $row->paidPeriod($date);
				
				if(($row->nominal + $row->nominal_service - $pay) > 0){
					$diffdays = $this->dateDiffInDays($row->date, $date);
					if($diffdays <= 30){
						$total30 += ($row->nominal + $row->nominal_service - $pay);
					}elseif($diffdays <= 60){
						$total60 += ($row->nominal + $row->nominal_service - $pay);
					}elseif($diffdays <= 90){
						$total90 += ($row->nominal + $row->nominal_service - $pay);
					}elseif($diffdays > 90){
						$totalover += ($row->nominal + $row->nominal_service - $pay);
					}
					$totalAr += ($row->nominal + $row->nominal_service - $pay);
				}
			}
		}
		
		$cb = CashBank::where('lookable_type','projects')->where('code','not like',"FEE-PTA%")->where('code','not like',"FEE-SMB%")->where('lookable_id',$this->id)->whereRaw("date <= '$date'")->get();
				
		if(count($cb) > 0){
			foreach($cb as $rowcb){
				foreach($rowcb->cashBankDetail()->where('coa_id',27)->get() as $cbcb){
					if($cbcb->type == '2'){
						$totalAr -= $cbcb->nominal;
					}
				}
			}
		}
		
		$result = [
			'total'		=> round($totalAr,0),
			'total30'	=> round($total30,0),
			'total60'	=> round($total60,0),
			'total90'	=> round($total90,0),
			'totalover'	=> round($totalover,0)
		];
		
		return $result;
	}
}
