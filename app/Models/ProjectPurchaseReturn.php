<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProjectPurchaseReturn extends Model {

    use HasFactory;

    protected $table      = 'project_purchase_returns';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
		'project_id',
        'project_purchase_id',
		'project_warehouse_id',
        'code',
		'date',
        'warehouse_id',
		'image',
		'note',
		'approved_by'
    ];
	
	public function approve()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }

	public static function generateCode()
    {
        $query = ProjectPurchaseReturn::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'PR/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse','warehouse_id','id');
    }
	
	public function projectPurchase()
    {
        return $this->belongsTo('App\Models\ProjectPurchase','project_purchase_id','id');
    }
	
	public function projectPurchaseReturnProduct()
    {
        return $this->hasMany('App\Models\ProjectPurchaseReturnProduct');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
	
	public function getTotal(){
		$totalPurchase = 0;
		
		$projectpurchase = ProjectPurchase::find($this->projectPurchase->id);
		
		foreach($this->projectPurchaseReturnProduct as $key => $pi) {
			foreach($projectpurchase->projectPurchaseProduct->where('product_id',$pi->product_id) as $psp){
				if($psp->unit == '2' || $psp->unit == '3'){
					$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
					if($projectpurchase->currency_id !== '5'){
						if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
							$totalPurchase += $psp->price * $pi->qty * $projectpurchase->currency_rate;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($this->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$totalPurchase += $psp->price * $pi->qty * $projectpurchase->currency_rate;
							}else{
								$totalPurchase += $psp->price * $pi->qty * $m2 * $projectpurchase->currency_rate;
							}
						}
					}else{
						if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
							$totalPurchase += $psp->price * $pi->qty;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($this->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$totalPurchase += $psp->price * $pi->qty;
							}else{
								$totalPurchase += $psp->price * $pi->qty * $m2;
							}
						}
					}
				}else{
					if($projectpurchase->currency_id !== '5'){
						$totalPurchase += $psp->price * $pi->qty * $projectpurchase->currency_rate;
					}else{
						$totalPurchase += $psp->price * $pi->qty;
					}
				}
			}
		}
		
		return $totalPurchase;
	}
	
	public function getTotalKomplit(){
		$totalPurchase = 0;
		
		foreach($this->projectPurchaseReturnProduct as $key => $pi) {
			
			foreach($this->projectPurchase->get() as $pp){
				foreach($pp->projectPurchaseProduct->where('product_id',$pi->product_id) as $psp){
					$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
					
					if($psp->unit == '2' || $psp->unit == '3'){
						if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
							$totalPurchase += $psp->price * $pi->qty;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($this->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$totalPurchase += $psp->price * $pi->qty;
							}else{
								$totalPurchase += $psp->price * $pi->qty * $m2;
							}
						}
					}else{
						$totalPurchase += $psp->price * $pi->qty;
					}
				}
			}
		}
		
		return $totalPurchase;
	}
	
	public function getRealInventory(){
		$totalinventory = 0;
		
		foreach($this->projectPurchaseReturnProduct as $row){
			$totalinventory += $row->qty * str_replace(',','.',str_replace('.','',$row->purchasePrice()));
		}
		
		return $totalinventory;
	}

	public function journalDownPayment()
	{
		$isExist = false;

		$cb = CashBank::where('lookable_type', 'project_purchase_returns')->where('lookable_id', $this->id)->first();

		foreach ($cb->cashBankDetail->where('type', 1) as $val) {
			if ($val->coa_id ==  24 && (date('Y-m-d', strtotime($val->cashBank->date)) > '2022-12-31')) {
				$isExist = true;
			}
		}
		
		return $isExist;
	}
}
