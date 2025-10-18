<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDeliveryProduct extends Model {

    use HasFactory;

    protected $table      = 'project_delivery_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_delivery_id',
        'product_id',
        'qty',
        'qty_deduction',
        'unit',
		'shading'
    ];
	
	public function projectDelivery()
    {
        return $this->belongsTo('App\Models\ProjectDelivery', 'project_delivery_id', 'id');
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
	
	public function price(){
		$price = 0;
		
		$data = $this->projectDelivery->projectSale->projectSaleProduct->where('product_id',$this->product_id)->first();
		
		if($data){
			$price = $data->best_price ? $data->best_price : ($data->recommended_price ? $data->recommended_price : $data->price);
		}
		
		return $price;
	}
	
	public function priceList(){
		$price = 0;
		
		$data = $this->projectDelivery->projectSale->projectSaleProduct->where('product_id',$this->product_id)->first();
		
		if($data){
			$price = $data->price;
		}
		
		return $price;
	}
	
	public function recommendedPrice(){
		$price = 0;
		
		$data = $this->projectDelivery->projectSale->projectSaleProduct->where('product_id',$this->product_id)->first();
		
		if($data){
			$price = $data->recommended_price;
		}
		
		return $price;
	}
	
	public function bestPrice(){
		$price = 0;
		
		$data = $this->projectDelivery->projectSale->projectSaleProduct->where('product_id',$this->product_id)->first();
		
		if($data){
			$price = $data->best_price;
		}
		
		return $price;
	}
	
	public function qty(){
		$qty = 0;
		
		if($this->unit == '2' || $this->unit == '3'){
			
			$m2 = (( $this->product->type->length * $this->product->type->width ) / 10000) * $this->product->carton_pcs;

			if($m2 < 1.1 && $this->product->type->category->parent()->id !== 18){
				$qty = $this->qty;
			}else{
				if($m2 < 1.1 && date('Y-m',strtotime($this->projectDelivery->project->created_at)) < '2022-06' && $this->product->type->category->parent()->id !== 18){
					$qty = $this->qty;
				}else{
					$qty = $this->qty * $m2;
				}
			}
		}
		
		if($this->unit == '1' || $this->unit == '4'){
			$qty = $this->qty;
		}
		
		return round($qty,2);
	}
	
	public function salePrice()
    {
        $price = 0;
		
		$project = ProjectDelivery::find($this->projectDelivery->id);
		
		if($this->unit == '2' || $this->unit == '3'){
			$psp = $project->projectSale->projectSaleProduct->where('product_id',$this->product_id)->first();
			
			$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
			$countbox = ceil(round($psp->qty / $m2,2));

			if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
				$price = $psp->best_price;
			}else{
				if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id !== 18){
					$price = $psp->best_price;
				}else{
					$price = $psp->best_price * $m2;
				}
				
			}
		}
		
		if($this->unit == '1' || $this->unit == '4'){
			$psp = $project->projectSale->projectSaleProduct->where('product_id',$this->product_id)->first();
			
			$price = $psp->best_price;
		}
		
		return number_format(round($price),0,',','.');
    }
	
	public function purchasePrice()
    {
		
		$date = $this->projectDelivery->received_date ? $this->projectDelivery->received_date : date('Y-m-d');
		
        $cogs = ProductCogs::where('branch',$this->projectDelivery->projectSale->sales->branch)->where('product_id',$this->product_id)->where('date','<=',$date)->orderByDesc('id')->first();
		
		$price = 0;
		
		if($cogs){
			$price = $cogs->price_final;
		}
		
		return number_format($price,2,',','.');
    }



	// public function purchasePriceUnit()
	// {
	// 	$total = 0;
	// 	$price =str_replace(',','.',str_replace('.','', $this->purchasePrice()));
		
	// 	if ($this->unit == 2 || $this->unit == 3) {
	// 		$m2 = (($this->product->type->length * $this->product->type->width) / 10000) * $this->product->carton_pcs;

	// 		if ($m2 < 1.1 && $this->product->type->category->parent()->id !== 18) {
	// 			$total += ($price / $this->qty);
	// 		} else {
	// 			if ($m2 < 1.1 && date('Y-m', strtotime($this->created_at)) < '2022-06' && $this->product->type->category->parent()->id == 18) {
	// 				$total += ($price / $this->qty);
	// 			} else {
	// 				$total += ($price / $this->qty) / $m2;
	// 			}
	// 		}
	// 	} else {
	// 		$total += ($price / $this->qty);
	// 	}


	// 	$finalPrice = $total;

	// 	return $finalPrice;
	// }
	
	public function purchasePriceBefore($date)
    {
		$price = 0;
		
		$branch = $this->projectDelivery->projectSale->sales->branch;
		
		$projectsale = ProjectSale::find($this->projectDelivery->project_sale_id);
		
		$adaproduct = false;
		
		foreach($projectsale->projectPurchase as $psp){
			
			if(date('Y-m-d',strtotime($psp->created_at)) < '2022-04-01'){
				$persenppn = 0.1;
				$ppnpembagi = 1.1;
			}else{
				$persenppn = 0.11;
				$ppnpembagi = 1.11;
			}
			
			foreach($psp->projectPurchaseProduct as $pp){
				if($pp->product_id == $this->product_id){
					if($this->unit == '2' || $this->unit == '3'){
						
						$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
						
						if($psp->currency_id !== '5'){
							if($pp->projectPurchase->ppn == '1'){
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$price = $pp->price * $pp->projectPurchase->currency_rate / $ppnpembagi;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($psp->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$price = $pp->price * $pp->projectPurchase->currency_rate / $ppnpembagi;
									}else{
										$price = ($m2 * $pp->price * $pp->projectPurchase->currency_rate) / $ppnpembagi;
									}
								}
							}else{
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$price = $pp->price * $pp->projectPurchase->currency_rate;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($psp->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$price = $pp->price * $pp->projectPurchase->currency_rate;
									}else{
										$price = $m2 * $pp->price * $pp->projectPurchase->currency_rate;
									}
									
								}
							}
						}else{
							if($pp->projectPurchase->ppn == '1'){
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$price = $pp->price / $ppnpembagi;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($psp->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$price = $pp->price / $ppnpembagi;
									}else{
										$price = ($m2 * $pp->price) / $ppnpembagi;
									}
									
								}
							}else{
								if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
									$price = $pp->price;
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($psp->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
										$price = $pp->price;
									}else{
										$price = $m2 * $pp->price;
									}
								}
							}
						}
					}elseif($this->unit == '1' || $this->unit == '4'){
						if($psp->currency_id !== '5'){
							if($pp->projectPurchase->ppn == '1'){
								$price = $pp->price * $pp->projectPurchase->currency_rate / $ppnpembagi;
							}else{
								$price = $pp->price * $pp->projectPurchase->currency_rate;
							}
						}else{
							if($pp->projectPurchase->ppn == '1'){
								$price = $pp->price / $ppnpembagi;
							}else{
								$price = $pp->price;
							}
						}
					}
					
					$adaproduct = true;
					break 2;
				}
			}
		}
		
		if($adaproduct == false){
			$total = 0;
			$qty = 0;
			
			$branch = $this->projectDelivery->projectSale->sales->branch;
			
			$data = ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($date,$branch){ 
						$query->whereHas('projectWarehouse', function($query) use ($date){ 
							$query->whereDate('date_receive', '<', $date); 
						})->whereHas('sales',function($query)use($branch){
							$query->where('branch',$branch);
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
								$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
							}else{
								if($m2 < 1.1 && date('Y-m',strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
									$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
								}else{
									$total += ($m2 * $psp->price * $psp->qty * $psp->projectPurchase->currency_rate) / $ppnpembagi;
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
								$total = $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
							}else{
								if($m2 < 1.1 && date('Y-m',strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
									$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
								}else{
									$total += $m2 * $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
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
							$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
						}else{
							$total += $psp->price * $psp->qty / $ppnpembagi;
						}
					}else{
						if($psp->projectPurchase->currency_id !== '5'){
							$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
						}else{
							$total += $psp->price * $psp->qty;
						}
					}
				}
				
				$qty += $psp->qty;
			}
				
			foreach(TransferProduct::whereHas('transfer', function($query) use ($date,$branch) {
					$query->whereDate('date', '<', $date)->where('branch',$branch)->where(function($query){
						$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
					});
				})->where('product_id',$this->product_id)->get() as $tp){
				$total += $tp->price;
				$qty += $tp->qty;
			}
			
			$price = $qty > 0? $total / $qty : 0;
		}
		
		return number_format($price,2,',','.');
    }
	
	public function purchasePriceNow($startDate,$finishDate)
    {
		$price = 0;
		
		$branch = $this->projectDelivery->projectSale->sales->branch;
		
		$total = 0;
		$qty = 0;
		
		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function($query) use($startDate,$finishDate,$branch){ 
					$query->whereHas('projectWarehouse', function($query) use ($startDate,$finishDate){ 
						$query->whereDate('date_receive', '<=', $finishDate)->whereDate('date_receive', '>=', $startDate); 
					})->whereHas('sales',function($query)use($branch){
						$query->where('branch',$branch);
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
							$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
							}else{
								$total += ($m2 * $psp->price * $psp->qty * $psp->projectPurchase->currency_rate) / $ppnpembagi;
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
							$total = $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
							}else{
								$total += $m2 * $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
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
						$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
					}else{
						$total += $psp->price * $psp->qty / $ppnpembagi;
					}
				}else{
					if($psp->projectPurchase->currency_id !== '5'){
						$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
					}else{
						$total += $psp->price * $psp->qty;
					}
				}
			}
			
			$qty += $psp->qty;
		}
			
		foreach(TransferProduct::whereHas('transfer', function($query) use ($startDate,$finishDate,$branch) {
				$query->whereDate('date', '<=', $finishDate)->whereDate('date','>=',$startDate)->where('branch',$branch)->where(function($query){
					$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
				});
			})->where('product_id',$this->product_id)->get() as $tp){
			$total += $tp->price;
			$qty += $tp->qty;
		}
		
		$price = $qty > 0? $total / $qty : 0;
		
		return number_format($price,2,',','.');
    }

	private function countDeliveriesForSameProduct()
	{
		$count = 0;
	
		$product_id = $this->product_id;
		$projectSale = $this->projectDelivery->projectSale;
	
		foreach ($projectSale->projectDelivery as $pd) {
			foreach ($pd->projectDeliveryProduct as $pdp) {
				if ($pdp->product_id == $product_id) {
					$count++;
				}
			}
		}
	
		return $count;
	}

	public function getQtyMinusReturnold()
	{
		$totalqty = $this->qty;
		$totalreturn = 0;
	
		
		// Count the number of deliveries for the same product
		$get_same_product_diff_delivery = $this->countDeliveriesForSameProduct();
	
		// Get sale returns associated with the current sale
		$salereturn = ProjectSaleReturn::whereHas('projectReturnMemo', function($query){
			$query->where('project_delivery_id', $this->project_delivery_id);
		})
		->where('project_sale_id', $this->projectDelivery->projectSale->id)
		->get();
	
		// Check the conditions and calculate total returns
		if ($get_same_product_diff_delivery > 1 &&
			$this->projectDelivery->projectSale->projectDelivery->first()->id == $this->project_delivery_id) {
			foreach ($salereturn as $sr) {
				$totalreturn += $sr->projectSaleReturnProduct->where('product_id', $this->product_id)->sum('qty');
			}
		}else if($get_same_product_diff_delivery == 1){
			if ($this->projectDelivery->projectSale->id == $this->projectDelivery->project_sale_id) {
				foreach ($salereturn as $sr) {
					$totalreturn += $sr->projectSaleReturnProduct->where('product_id', $this->product_id)->sum('qty');
				}
			}
		}



		$balance = $totalqty - $totalreturn;
	
		return $balance;
	}

	public function qtyReturnOld()
	{
		$totalreturn = 0;
		// Count the number of deliveries for the same product
		$get_same_product_diff_delivery = $this->countDeliveriesForSameProduct();

		// Get sale returns associated with the current sale
		$salereturn = ProjectSaleReturn::where('project_sale_id', $this->projectDelivery->projectSale->id)->get();
	
		// Check the conditions and calculate total returns
		if ($get_same_product_diff_delivery > 1 &&
			$this->projectDelivery->projectSale->projectDelivery->first()->id == $this->project_delivery_id) {
			foreach ($salereturn as $sr) {
				foreach($sr->projectSaleReturnProduct->where('product_id',$this->product_id) as $psrp){
					$totalreturn += $psrp->qty;
				}
			}
		}else if($get_same_product_diff_delivery == 1){
			if ($this->projectDelivery->projectSale->id == $this->projectDelivery->project_sale_id) {
				foreach ($salereturn as $sr) {
					foreach($sr->projectSaleReturnProduct->where('product_id',$this->product_id) as $psrp){
						$totalreturn += $psrp->qty;
					}
				}
			}
		}
		
		return $totalreturn;
	}
	
	public function getQtyMinusReturn()
	{
		$totalqty = $this->qty;
		$totalreturn = 0;
	
	
		$salereturn = ProjectSaleReturn::whereHas('projectReturnMemo', function($query){
			$query->where('project_delivery_id', $this->project_delivery_id);
		})
		->where('project_sale_id', $this->projectDelivery->projectSale->id)
		->get();
	

		foreach ($salereturn as $sr) {
			$totalreturn += $sr->projectSaleReturnProduct->where('product_id', $this->product_id)->sum('qty');
		}

		$balance = $totalqty - $totalreturn;
	
		return $balance;
	}

	public function qtyReturn()
	{
		$totalreturn = 0;
		$salereturn = ProjectSaleReturn::whereHas('projectReturnMemo', function($query){
			$query->where('project_delivery_id', $this->project_delivery_id);
		})
		->where('project_sale_id', $this->projectDelivery->projectSale->id)
		->get();
	
	
		foreach ($salereturn as $sr) {
			$totalreturn += $sr->projectSaleReturnProduct->where('product_id', $this->product_id)->sum('qty');
		}
		
		return $totalreturn;
	}
	
}

