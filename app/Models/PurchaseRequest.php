<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PurchaseRequest extends Model {

    use HasFactory;

    protected $table      = 'purchase_requests';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'date',
		'date_paid',
        'user_id',
		'branch',
		'coa_id',
		'wip_coa_id',
		'bill_to',
		'title',
        'item',
		'qty',
		'value',
		'total_nominal',
		'total_cash_advance',
		'status',
		'image',
		'image_paid',
		'link_type',
		'link_id',
		'project_purchase_bill_id',
		'project_warehouse_id',
		'term',
		'supplier_id',
		'term_days',
		'due_date',
		'approved_by',
		'reject_reason',
		'checked_by',
		'closed_by_acc',
		'fixed_cost',
		'fixed_month',
		'fixed_ref'
    ];
	
	public function purchaseRequestPayment()
    {
        return $this->hasMany('App\Models\PurchaseRequestPayment'); 
    }
	
	public function cashFlow()
    {
        $cf = CashFlow::where('type','purchase_requests')->where('type_id',$this->id)->first();
			
		if($cf){
			return $cf;
		}else{
			return '';
		}
    }
	
	public function totalPayment()
	{
		$totalpaid = 0;
		
		foreach($this->purchaseRequestPayment as $row){
			$totalpaid += $row->nominal;
		}
		
		return $totalpaid;
	}


	
	public function totalBalance()
	{
		$totalpaid = 0;
		
		foreach($this->purchaseRequestPayment as $row){
			$totalpaid += $row->nominal;
		}
		
		return $this->total_nominal - $totalpaid;
	}
	
	public function totalPaymentPeriod($filter)
	{
		$totalpaid = 0;
		
		$whereRaw = strlen($filter) == 7 ? "LEFT(date_paid, 7) <= '$filter'" : "date_paid <= '$filter'";

		foreach($this->purchaseRequestPayment()->whereRaw($whereRaw)->get() as $row){
			$totalpaid += $row->nominal;
		}
		
		// $cek = CashBank::where('purchase_request_ref',$this->id)->whereRaw($whereRaw2)->first();
		
		// if($cek){
		// 	//$totalpaid = $this->total_nominal;
		// }
		
		return $totalpaid;
	}
	
	public function totalPaymentReal()
	{
		$totalpaid = 0;
		
		foreach($this->purchaseRequestPayment as $row){
			//if($row->purchaseRequest->status == 'DONE'){
				$totalpaid += $row->nominal;
			//}
		}
		
		$cek = CashBank::where('purchase_request_ref',$this->id)->first();
		
		if($cek){
			//$totalpaid = $this->total_nominal;
		}
		
		return $totalpaid;
	}
	
	public function getPercentPay()
	{
		$total = $this->total_nominal;
		$totalpaid = 0;
		
		foreach($this->purchaseRequestPayment as $row){
			$totalpaid += $row->nominal;
		}
		
		$percent = $total ? round(($totalpaid / $total) * 100,0) : 0;
		
		if($percent >= 100){
			$progress = '
				<div class="progress" style="height:0.875rem;">
					<div class="progress-bar progress-bar-striped bg-teal" style="width:100%">
						<span class="font-weight-bold text-uppercase">
							<span style="font-size:13px;">' . $percent . '%</span>
						</span>
					</div>
				</div>
			';
		}elseif($this->closed_by_acc){
			$progress = '
				<div class="progress" style="height:0.875rem;">
					<div class="progress-bar progress-bar-striped bg-danger" style="width:100%">
						<span class="font-weight-bold text-uppercase">
							<span style="font-size:13px;">
								Closed by<br>
								Accounting
							</span>
						</span>
					</div>
				</div>
			';
		}else{
			$progress = '
				<div class="progress" style="height:0.875rem;">
					<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
						<span class="font-weight-bold text-uppercase">
							<span style="font-size:13px;">' . $percent . '%</span>
						</span>
					</div>
				</div>
			';
		}
		
		return $progress;
	}
	
	public function link()
    {
        return $this->morphTo();
    }

	
	public function coa()
    {
        return $this->belongsTo('App\Models\Coa');
    }
	
	public function projectPurchaseBill()
    {
        return $this->belongsTo('App\Models\ProjectPurchaseBill');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
	
	public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id', 'id');
    }
	
	public function cashBank()
    {
        $result = CashBank::where('code','PRF-'.$this->id)->first();
		
		if($result){
			return true;
		}else{
			return false;
		}
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function attachmentPaid() 
    {
        if(Storage::exists($this->image_paid)) {
            $attachment = asset(Storage::url($this->image_paid));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
		
		if(Storage::exists($this->image_paid)) {
            Storage::delete($this->image_paid);
        }
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
			case '3':
				$branch = 'MKJ';
				break;
			case '4':
				$branch = 'PSI';
				break;
            default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }
	
	public function checked()
    {
        return $this->belongsTo('App\Models\User', 'checked_by', 'id');
    }
	
	public function getPurchaseReturnTotal(){
		$total = 0;
		
		if($this->link_type == 'project_purchases'){
			
			if($this->project_warehouse_id){
				$data = ProjectPurchase::find($this->link_id);
			
				foreach($data->projectPurchaseReturn->where('project_warehouse_id',$this->project_warehouse_id) as $row){
					$total += $row->getTotal();
				}
			}elseif($this->project_purchase_bill_id){
				
			}
		}
		
		return $total;
	}
	
	public function getPurchaseReturn(){
		$data = ProjectPurchaseReturn::where('project_warehouse_id',$this->project_warehouse_id)->first();
		if($data){
			return $data;
		}
		
		return '';
	}
}
