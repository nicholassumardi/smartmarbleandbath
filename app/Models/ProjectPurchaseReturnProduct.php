<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPurchaseReturnProduct extends Model {

    use HasFactory;

    protected $table      = 'project_purchase_return_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_purchase_return_id',
        'product_id',
        'qty',
        'unit'
    ];
	
	public function projectPurchaseReturn()
    {
        return $this->belongsTo('App\Models\ProjectPurchaseReturn');
    }
	
	public function unit()
    {
        switch($this->unit) {
            case '1':
                $unit = 'Pcs';
                break;
            case '2':
                $unit = 'Box';
                break;
            case '3':
                $unit = 'Meter';
                break;
            case '4':
                $unit = 'Meter (Custom)';
                break;
            default:
                $unit = 'Invalid';
                break;
        }

        return $unit;
    }
	
	public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
	
	public function purchasePrice()
    {
		
		$price = 0;
		
		$cek = ProductCogs::where('product_id',$this->product_id)->where('branch',$this->projectPurchaseReturn->projectPurchase->sales->branch)->where('date','<=',$this->projectPurchaseReturn->date)->orderByDesc('id')->first();
				
		if($cek){
			$price = $cek->price_final;
		}
		
		return number_format($price,2,',','.');
    }
	
	public function purchasePriceBefore($date)
    {
		
		$total = 0;
		$qty = 0;
		
		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($date){ 
					$query->whereHas('projectWarehouse', function($query) use ($date){ 
						$query->whereDate('date_receive', '<', $date); 
					});
				})->where('product_id',$this->product_id)->get();
		
		foreach($data as $psp){
			if(date('Y-m-d',strtotime($psp->projectPurchase->created_at)) < '2022-04-01'){
				$persenppn = 0.1;
				$ppnpembagi = 1.1;
			}else{
				$persenppn = 0.11;
				$ppnpembagi = 1.11;
			}
			
			if($psp->unit == '2' || $psp->unit == '3'){
				$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
				
				if($psp->projectPurchase->ppn == '1'){
					if($psp->projectPurchase->currency_id !== '5'){
						if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
							$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id) / $ppnpembagi;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id) / $ppnpembagi;
							}else{
								$total += ($m2 * $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id)) / $ppnpembagi;
							}
						}
					}else{
						if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
							$total += $psp->price * $psp->qty / $ppnpembagi;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$total += $psp->price * $psp->qty / $ppnpembagi;
							}else{
								$total += ($m2 * $psp->price * $psp->qty) / $ppnpembagi;
							}
						}
					}
					
				}else{
					if($psp->projectPurchase->currency_id !== '5'){
						if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
							$total = $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id);
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id);
							}else{
								$total += $m2 * $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id);
							}
						}
					}else{
						if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
							$total = $psp->price * $psp->qty;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$total += $psp->price * $psp->qty;
							}else{
								$total += $m2 * $psp->price * $psp->qty;
							}
						}
					}
				}
			}elseif($psp->unit == '1' || $psp->unit == '4'){
				if($psp->projectPurchase->ppn == '1'){
					if($psp->projectPurchase->currency_id !== '5'){
						$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id) / $ppnpembagi;
					}else{
						$total += $psp->price * $psp->qty / $ppnpembagi;
					}
				}else{
					if($psp->projectPurchase->currency_id !== '5'){
						$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id);
					}else{
						$total += $psp->price * $psp->qty;
					}
				}
			}
			
			$qty += $psp->qty;
		}
			
		foreach(TransferProduct::whereHas('transfer', function($query) use ($date) {
				$query->whereNotNull('for_starting')->whereDate('date', '<', $date);;
			})->where('product_id',$this->product_id)->get() as $tp){
			$total += $tp->price;
			$qty += $tp->qty;
		}
		
		$price = $qty > 0? $total / $qty : 0;
		
		return number_format($price,2,',','.');
    }
	
	public function getDateWarehouse(){
		$purchase = $this->projectPurchaseReturn->projectPurchase;
		
		$date = '';
		
		foreach($purchase->projectWarehouse as $pw){
			foreach($pw->projectWarehouseProduct->where('product_id',$this->product_id) as $pwp){
				$date = $pw->date_receive;
			}
		}
		
		return $date;
	}
}
