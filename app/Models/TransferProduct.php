<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferProduct extends Model {

    use HasFactory;

    protected $table      = 'transfer_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'transfer_id',
        'product_id',
		'price',
		'qty',
		'code',
		'unit'
    ];
	
	public function transfer()
    {
        return $this->belongsTo('App\Models\Transfer');
    }
	
	public function product()
    {
        return $this->belongsTo('App\Models\Product');
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
	
	public function price()
	{
		$pricepurchase = 0;
		
		$cek = ProductCogs::where('product_id',$this->product_id)->where('branch',$this->transfer->branch)->where('date','<=',$this->transfer->date)->orderByDesc('id')->first();
				
		if($cek){
			$pricepurchase = $cek->price_final;
		}
		
		return $pricepurchase;
	}

	public function transferPriceUnit()
	{
		$finalPrice = $this->price / $this->qty;

		return $finalPrice;
	}

	
	public function priceDateNow($startDate,$endDate)
	{
		$pricepurchase = 0;
		
		$branch = $this->transfer->branch;
		
		$totalpo = 0;
		$jumlah = 0;
		
		if($this->unit == '2' || $this->unit == '3'){
			if(count(ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($startDate,$endDate,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($startDate,$endDate){ 
						$query->whereDate('date_receive', '<=', $endDate)->whereDate('date_receive', '>=', $startDate); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
					});
				})->where('product_id',$this->product_id)->get()) > 0){
				foreach(ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($startDate,$endDate,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($startDate,$endDate){ 
						$query->whereDate('date_receive', '<=', $endDate)->whereDate('date_receive', '>=', $startDate); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
					});
				})->where('product_id',$this->product_id)->get() as $ppp){
					
					if(date('Y-m-d',strtotime($ppp->projectPurchase->created_at)) < '2022-04-01'){
						$persenppn = 0.1;
						$ppnpembagi = 1.1; 
					}else{
						$persenppn = 0.11;
						$ppnpembagi = 1.11;
					}
					
					$m2 = (( $ppp->product->type->length * $ppp->product->type->width ) / 10000) * $ppp->product->carton_pcs;
					
					if($m2 < 1.1 && $ppp->product->type->category->parent()->id !== 18){
						if($ppp->projectPurchase->ppn == '1'){
							$totalpo += ($ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate) / $ppnpembagi;
						}else{
							$totalpo += $ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate;
						}
					}else{
						if($m2 < 1.1 && date('Y-m',strtotime($ppp->projectPurchase->created_at)) < '2022-06' && $ppp->product->type->category->parent()->id == 18){
							if($ppp->projectPurchase->ppn == '1'){
								$totalpo += ($ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate) / $ppnpembagi;
							}else{
								$totalpo += $ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate;
							}
						}else{
							if($ppp->projectPurchase->ppn == '1'){
								$totalpo += ($ppp->qty * $m2 * $ppp->price * $ppp->projectPurchase->currency_rate) / $ppnpembagi;
							}else{
								$totalpo += $ppp->qty * $m2 * $ppp->price * $ppp->projectPurchase->currency_rate;
							}
						}
						
					}
					
					$jumlah += $ppp->qty;
				}
			}else{
				foreach(TransferProduct::whereHas('transfer',function($query) use($startDate,$endDate,$branch){ $query->where('branch',$branch)->whereDate('date', '<=', $endDate)->whereDate('date', '>=', $startDate)->whereNotNull('for_starting'); })->where('product_id',$this->product_id)->get() as $tp){
					$totalpo += $tp->price;
					$jumlah += $tp->qty;
				}
			}
			
			$pricepurchase = $jumlah ? round($totalpo / $jumlah,2) : 0;
		}
		
		if($this->unit == '1' || $this->unit == '4'){
			if(count(ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($startDate,$endDate,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($startDate,$endDate){ 
						$query->whereDate('date_receive', '<=', $endDate)->whereDate('date_receive', '>=', $startDate); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
					});
				})->where('product_id',$this->product_id)->get()) > 0){
				foreach(ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($startDate,$endDate,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($startDate,$endDate){ 
						$query->whereDate('date_receive', '<=', $endDate)->whereDate('date_receive', '>=', $startDate); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
					});
				})->where('product_id',$this->product_id)->get() as $ppp){
					
					if(date('Y-m-d',strtotime($ppp->projectPurchase->created_at)) < '2022-04-01'){
						$persenppn = 0.1;
						$ppnpembagi = 1.1; 
					}else{
						$persenppn = 0.11;
						$ppnpembagi = 1.11;
					}
					
					if($ppp->projectPurchase->ppn == '1'){
						$totalpo += round(($ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate) / $ppnpembagi);
					}else{
						$totalpo += $ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate;
					}
					$jumlah += $ppp->qty;
				}
			}else{
				foreach(TransferProduct::whereHas('transfer',function($query) use($startDate,$endDate,$branch){ $query->where('branch',$branch)->whereDate('date', '<=', $endDate)->whereDate('date', '>=', $startDate)->whereNotNull('for_starting'); })->where('product_id',$this->product_id)->get() as $tp){
					$totalpo += $tp->price;
					$jumlah += $tp->qty;
				}
			}
			
			$pricepurchase = $jumlah ? round($totalpo / $jumlah,2) : 0;
		}
				
		return $pricepurchase;
	}
	
	public function priceDateBefore($date)
	{
		$pricepurchase = 0;
		
		$branch = $this->transfer->branch;
		
		$totalpo = 0;
		$jumlah = 0;
		
		if($this->unit == '2' || $this->unit == '3'){
			if(count(ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($date,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($date){ 
						$query->whereDate('date_receive', '<', $date); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
					});
				})->where('product_id',$this->product_id)->get()) > 0){
				foreach(ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($date,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($date){ 
						$query->whereDate('date_receive', '<', $date); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
					});
				})->where('product_id',$this->product_id)->get() as $ppp){
					
					if(date('Y-m-d',strtotime($ppp->projectPurchase->created_at)) < '2022-04-01'){
						$persenppn = 0.1;
						$ppnpembagi = 1.1; 
					}else{
						$persenppn = 0.11;
						$ppnpembagi = 1.11;
					}
					
					$m2 = (( $ppp->product->type->length * $ppp->product->type->width ) / 10000) * $ppp->product->carton_pcs;
					
					if($m2 < 1.1 && $ppp->product->type->category->parent()->id !== 18){
						if($ppp->projectPurchase->ppn == '1'){
							$totalpo += ($ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate) / $ppnpembagi;
						}else{
							$totalpo += $ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate;
						}
					}else{
						if($m2 < 1.1 && date('Y-m',strtotime($ppp->projectPurchase->created_at)) < '2022-06' && $ppp->product->type->category->parent()->id == 18){
							if($ppp->projectPurchase->ppn == '1'){
								$totalpo += ($ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate) / $ppnpembagi;
							}else{
								$totalpo += $ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate;
							}
						}else{
							if($ppp->projectPurchase->ppn == '1'){
								$totalpo += ($ppp->qty * $m2 * $ppp->price * $ppp->projectPurchase->currency_rate) / $ppnpembagi;
							}else{
								$totalpo += $ppp->qty * $m2 * $ppp->price * $ppp->projectPurchase->currency_rate;
							}
						}
						
					}
					
					$jumlah += $ppp->qty;
				}
			}else{
				foreach(TransferProduct::whereHas('transfer',function($query) use($date,$branch){ $query->where('branch',$branch)->whereDate('date', '<', $date)->whereNotNull('for_starting'); })->where('product_id',$this->product_id)->get() as $tp){
					$totalpo += $tp->price;
					$jumlah += $tp->qty;
				}
			}
			
			$pricepurchase = $jumlah ? round($totalpo / $jumlah,2) : 0;
		}
		
		if($this->unit == '1' || $this->unit == '4'){
			if(count(ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($date,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($date){ 
						$query->whereDate('date_receive', '<', $date); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
					});
				})->where('product_id',$this->product_id)->get()) > 0){
				foreach(ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($date,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($date){ 
						$query->whereDate('date_receive', '<', $date); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
					});
				})->where('product_id',$this->product_id)->get() as $ppp){
					
					if(date('Y-m-d',strtotime($ppp->projectPurchase->created_at)) < '2022-04-01'){
						$persenppn = 0.1;
						$ppnpembagi = 1.1; 
					}else{
						$persenppn = 0.11;
						$ppnpembagi = 1.11;
					}
					
					if($ppp->projectPurchase->ppn == '1'){
						$totalpo += round(($ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate) / $ppnpembagi);
					}else{
						$totalpo += $ppp->qty * $ppp->price * $ppp->projectPurchase->currency_rate;
					}
					$jumlah += $ppp->qty;
				}
			}else{
				foreach(TransferProduct::whereHas('transfer',function($query) use($date,$branch){ $query->where('branch',$branch)->whereDate('date', '<', $date)->whereNotNull('for_starting'); })->where('product_id',$this->product_id)->get() as $tp){
					$totalpo += $tp->price;
					$jumlah += $tp->qty;
				}
			}
			
			$pricepurchase = $jumlah ? round($totalpo / $jumlah,2) : 0;
		}
		
		return $pricepurchase;
	}
}
