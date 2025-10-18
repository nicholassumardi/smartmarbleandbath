<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductCogs extends Model {

    use HasFactory;

    protected $table      = 'product_cogs';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'product_id',
		'branch',
		'qty_in',
		'price_in',
		'total_in',
		'qty_out',
		'price_out',
		'total_out',
		'qty_final',
		'price_final',
		'total_final',
		'date',
		'type'
    ];

	public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
	
	public static function updateCogs($dateStart,$branch){
		$startDate = new Carbon($dateStart);
		$endDate = new Carbon(date('Y-m-d'));
		
		// while ($startDate->lte($endDate)){
			
		// 	$date = $startDate->toDateString();
			
		// 	ProductCogs::where('branch',$branch)->where('date','=',$date)->delete();
			
		// 	$dataIn = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use($date,$branch){
		// 		$query->whereHas('projectPurchase',function($query) use($date,$branch){
		// 			$query->whereHas('sales',function($query) use($date,$branch){
		// 				$query->where('branch',$branch);
		// 			});
		// 		})->whereDate('date_receive',$date);
		// 	})->get();
			
		// 	foreach($dataIn as $row){
		// 		$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
		// 		$pricenow = str_replace(',','.',str_replace('.','',$row->purchaseReal()));
				
		// 		if($cek){
					
		// 			$totalold = $cek->total_final;
		// 			$totalnew = $row->qty * $pricenow;
		// 			$priceperqty = round(($totalold + $totalnew) / ($cek->qty_final + $row->qty),2);
		// 			$pricein = round(($cek->total_in + ($pricenow * $row->qty)) / ($cek->qty_in + $row->qty),2);
					
		// 			if($cek->date == $date){
		// 				$cek->update([
		// 					'qty_in'		=> $cek->qty_in + $row->qty,
		// 					'price_in'		=> $pricein,
		// 					'total_in'		=> ($cek->qty_in + $row->qty) * $pricein,
		// 					'qty_final'		=> $cek->qty_final + $row->qty,
		// 					'price_final'	=> $priceperqty,
		// 					'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty)
		// 				]);
		// 			}else{
		// 				ProductCogs::create([
		// 					'product_id'	=> $row->product_id,
		// 					'branch'		=> $branch,
		// 					'qty_in'		=> $row->qty,
		// 					'price_in'		=> $pricenow,
		// 					'total_in'		=> $row->qty * $pricenow,
		// 					'qty_final'		=> $cek->qty_final + $row->qty,
		// 					'price_final'	=> $priceperqty,
		// 					'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty),
		// 					'date'			=> $date,
		// 					'type'			=> 'WR'
		// 				]);
		// 			}
		// 		}else{
		// 			ProductCogs::create([
		// 				'product_id'	=> $row->product_id,
		// 				'branch'		=> $branch,
		// 				'qty_in'		=> $row->qty,
		// 				'price_in'		=> $pricenow,
		// 				'total_in'		=> $row->qty * $pricenow,
		// 				'qty_final'		=> $row->qty,
		// 				'price_final'	=> $pricenow,
		// 				'total_final'	=> $row->qty * $pricenow,
		// 				'date'			=> $date,
		// 				'type'			=> 'WR'
		// 			]);
		// 		}
		// 	}
			
		// 	$dataInTp = TransferProduct::whereHas('transfer', function($query) use ($date,$branch) {
		// 		$query->where('branch',$branch)->whereNotNull('for_starting')->whereDate('date',$date);
		// 	})->orWhereHas('transfer', function($query) use ($date,$branch) {
		// 		$query->where('branch',$branch)->whereNotNull('for_in_transfer')->whereDate('date',$date);
		// 	})->get();
			
		// 	foreach($dataInTp as $row){
		// 		$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
		// 		$pricenow = round($row->price / $row->qty,2);
				
		// 		if($cek){
					
		// 			$totalold = $cek->total_final;
		// 			$totalnew = $row->qty * $pricenow;
		// 			$priceperqty = round(($totalold + $totalnew) / ($cek->qty_final + $row->qty),2);
		// 			$pricein = round(($cek->total_in + $row->price) / ($cek->qty_in + $row->qty),2);
					
		// 			if($cek->date == $date){
		// 				$cek->update([
		// 					'qty_in'		=> $cek->qty_in + $row->qty,
		// 					'price_in'		=> $pricein,
		// 					'total_in'		=> ($cek->qty_in + $row->qty) * $pricein,
		// 					'qty_final'		=> $cek->qty_final + $row->qty,
		// 					'price_final'	=> $priceperqty,
		// 					'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty)
		// 				]);
		// 			}else{
		// 				ProductCogs::create([
		// 					'product_id'	=> $row->product_id,
		// 					'branch'		=> $branch,
		// 					'qty_in'		=> $row->qty,
		// 					'price_in'		=> $pricenow,
		// 					'total_in'		=> $row->qty * $pricenow,
		// 					'qty_final'		=> $cek->qty_final + $row->qty,
		// 					'price_final'	=> $priceperqty,
		// 					'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty),
		// 					'date'			=> $date,
		// 					'type'			=> 'WE-IN'
		// 				]);
		// 			}
		// 		}else{
		// 			ProductCogs::create([
		// 				'product_id'	=> $row->product_id,
		// 				'branch'		=> $branch,
		// 				'qty_in'		=> $row->qty,
		// 				'price_in'		=> $pricenow,
		// 				'total_in'		=> $row->qty * $pricenow,
		// 				'qty_final'		=> $row->qty,
		// 				'price_final'	=> $pricenow,
		// 				'total_final'	=> $row->qty * $pricenow,
		// 				'date'			=> $date,
		// 				'type'			=> 'WE-IN'
		// 			]);
		// 		}
		// 	}
			
		// 	$dataOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use($date,$branch){
		// 		$query->whereHas('projectSale',function($query) use($date,$branch){
		// 			$query->whereHas('sales',function($query) use($date,$branch){
		// 				$query->where('branch',$branch);
		// 			});
		// 		})->whereRaw("DATE(received_date) = '$date'");
		// 	})->get();
			
		// 	foreach($dataOut as $row){
		// 		$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
		// 		if($cek){
		// 			$pricenow = $cek->price_final;
					
		// 			ProductCogs::create([
		// 				'product_id'	=> $row->product_id,
		// 				'branch'		=> $branch,
		// 				'qty_out'		=> $row->qty,
		// 				'price_out'		=> $pricenow,
		// 				'total_out'		=> $row->qty * $pricenow,
		// 				'qty_final'		=> $cek->qty_final - $row->qty,
		// 				'price_final'	=> $pricenow,
		// 				'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
		// 				'date'			=> $date,
		// 				'type'			=> 'DO'
		// 			]);
		// 		}
		// 	}
			
		// 	$dataInRt = ProjectSaleReturnProduct::whereHas('projectSaleReturn', function($query) use ($branch) {
		// 		$query->whereHas('projectSale', function($query) use ($branch) {
		// 			$query->whereHas('sales', function($query) use ($branch) {
		// 				$query->where('branch',$branch);
		// 			});
		// 		});
		// 	})->whereHas('projectSaleReturn',function($query) use($date,$branch){
		// 		$query->where('date_return',$date);
		// 	})
		// 	->get();
			
		// 	foreach($dataInRt as $row){
		// 		$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$row->getDateDelivery())->orderByDesc('id')->first();
		// 		$cek2 = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
		// 		$pricenow = round($row->price / $row->qty,2);
				
		// 		if($cek){
		// 			$pricenow = $cek->price_final;
		// 			$pricenew = ($cek2->total_final + ($row->qty * $pricenow)) / ($cek2->qty_final + $row->qty);
					
		// 			ProductCogs::create([
		// 				'product_id'	=> $row->product_id,
		// 				'branch'		=> $branch,
		// 				'qty_in'		=> $row->qty,
		// 				'price_in'		=> $pricenow,
		// 				'total_in'		=> $row->qty * $pricenow,
		// 				'qty_final'		=> $cek2->qty_final + $row->qty,
		// 				'price_final'	=> $pricenew,
		// 				'total_final'	=> ($cek2->qty_final + $row->qty) * $pricenew,
		// 				'date'			=> $date,
		// 				'type'			=> 'SR'
		// 			]);
		// 		}
		// 	}
			
		// 	$dataOutRt = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn', function($query) use ($branch) {
		// 		$query->whereHas('projectPurchase', function($query) use ($branch) {
		// 			$query->whereHas('sales', function($query) use ($branch) {
		// 				$query->where('branch',$branch);
		// 			});
		// 		});
		// 	})->whereHas('projectPurchaseReturn',function($query) use($date){
		// 		$query->where('date',$date);
		// 	})
		// 	->get();
			
		// 	foreach($dataOutRt as $row){
		// 		$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
		// 		if($cek){
		// 			$pricenow = $cek->price_final;
					
		// 			ProductCogs::create([
		// 				'product_id'	=> $row->product_id,
		// 				'branch'		=> $branch,
		// 				'qty_out'		=> $row->qty,
		// 				'price_out'		=> $pricenow,
		// 				'total_out'		=> $row->qty * $pricenow,
		// 				'qty_final'		=> $cek->qty_final - $row->qty,
		// 				'price_final'	=> $pricenow,
		// 				'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
		// 				'date'			=> $date,
		// 				'type'			=> 'PR'
		// 			]);
		// 		}
		// 	}
			
		// 	$dataOutTp = TransferProduct::whereHas('transfer', function($query) use ($branch,$date) {
		// 		$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','3')->whereDate('date',$date);
		// 	})->orWhereHas('transfer', function($query) use ($branch,$date) {
		// 		$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','2')->whereDate('date',$date);
		// 	})->orWhereHas('transfer', function($query) use ($branch,$date) {
		// 		$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','1')->whereDate('date',$date);
		// 	})->orWhereHas('transfer', function($query) use ($branch,$date) {
		// 		$query->where('branch',$branch)->whereNotNull('for_correction')->whereDate('date',$date);
		// 	})->orWhereHas('transfer', function($query) use ($branch,$date) {
		// 		$query->where('branch',$branch)->whereNotNull('for_out_transfer')->whereDate('date',$date);
		// 	})->orWhereHas('transfer', function($query) use ($branch,$date) {
		// 		$query->where('branch',$branch)->whereNotNull('for_broken')->whereDate('date',$date);
		// 	})
		// 	->get();
		
		// 	foreach($dataOutTp as $row){
		// 		$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
		// 		if($cek){
		// 			$pricenow = $cek->price_final;
					
		// 			ProductCogs::create([
		// 				'product_id'	=> $row->product_id,
		// 				'branch'		=> $branch,
		// 				'qty_out'		=> $row->qty,
		// 				'price_out'		=> $pricenow,
		// 				'total_out'		=> $row->qty * $pricenow,
		// 				'qty_final'		=> $cek->qty_final - $row->qty,
		// 				'price_final'	=> $pricenow,
		// 				'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
		// 				'date'			=> $date,
		// 				'type'			=> 'WE-OUT'
		// 			]);
		// 		}
		// 	}
			
		// 	$startDate->addDay();
		// }

		while ($startDate->lte($endDate)){
			$date = $startDate->toDateString();

			ProductCogs::where('branch',$branch)->where('date','=',$date)->delete();
			
			$dataIn = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use($date,$branch){
				$query->whereHas('projectPurchase',function($query) use($date,$branch){
					$query->whereHas('sales',function($query) use($date,$branch){
						$query->where('branch',$branch);
					});
				})->whereDate('date_receive',$date);
			})->get();
			
			foreach($dataIn as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				$pricenow = str_replace(',','.',str_replace('.','',$row->purchaseReal()));
				
				if($cek){
					
					$totalold = $cek->total_final;
					$totalnew = $row->qty * $pricenow;
					$priceperqty = $cek->qty_final + $row->qty > 0 ? round(($totalold + $totalnew) / ($cek->qty_final + $row->qty),2) : $totalnew;
					$qty_now = $cek->qty_in + $row->qty ? $cek->qty_in + $row->qty : 1;
					$pricein = round(($cek->total_in + ($pricenow * $row->qty)) / $qty_now,2);
					
					if($cek->date == $date){
						$cek->update([
							'qty_in'		=> $cek->qty_in + $row->qty,
							'price_in'		=> $pricein,
							'total_in'		=> ($cek->qty_in + $row->qty) * $pricein,
							'qty_final'		=> $cek->qty_final + $row->qty,
							'price_final'	=> $priceperqty,
							'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty)
						]);
					}else{
						ProductCogs::create([
							'product_id'	=> $row->product_id,
							'branch'		=> $branch,
							'qty_in'		=> $row->qty,
							'price_in'		=> $pricenow,
							'total_in'		=> $row->qty * $pricenow,
							'qty_final'		=> $cek->qty_final + $row->qty,
							'price_final'	=> $priceperqty,
							'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty),
							'date'			=> $date,
							'type'			=> 'WR'
						]);
					}
				}else{
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_in'		=> $row->qty,
						'price_in'		=> $pricenow,
						'total_in'		=> $row->qty * $pricenow,
						'qty_final'		=> $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> $row->qty * $pricenow,
						'date'			=> $date,
						'type'			=> 'WR'
					]);
				}
			}
			
			$dataInTp = TransferProduct::whereHas('transfer', function($query) use ($date,$branch) {
				$query->where('branch',$branch)->whereNotNull('for_starting')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($date,$branch) {
				$query->where('branch',$branch)->whereNotNull('for_in_transfer')->whereDate('date',$date);
			})->get();
			
			foreach($dataInTp as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				$pricenow = round($row->price / $row->qty,2);
				
				if($cek){
					
					$totalold = $cek->total_final;
					$totalnew = $row->qty * $pricenow;
					$priceperqty = $cek->qty_final + $row->qty > 0 ? round(($totalold + $totalnew) / ($cek->qty_final + $row->qty),2) : $totalnew;
					$qty_now = $cek->qty_in + $row->qty ? $cek->qty_in + $row->qty : 1;
					$pricein = round(($cek->total_in + $row->price) / ($cek->qty_in + $row->qty),2);
					
					if($cek->date == $date){
						$cek->update([
							'qty_in'		=> $cek->qty_in + $row->qty,
							'price_in'		=> $pricein,
							'total_in'		=> ($cek->qty_in + $row->qty) * $pricein,
							'qty_final'		=> $cek->qty_final + $row->qty,
							'price_final'	=> $priceperqty,
							'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty)
						]);
					}else{
						ProductCogs::create([
							'product_id'	=> $row->product_id,
							'branch'		=> $branch,
							'qty_in'		=> $row->qty,
							'price_in'		=> $pricenow,
							'total_in'		=> $row->qty * $pricenow,
							'qty_final'		=> $cek->qty_final + $row->qty,
							'price_final'	=> $priceperqty,
							'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty),
							'date'			=> $date,
							'type'			=> 'WE-IN'
						]);
					}
				}else{
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_in'		=> $row->qty,
						'price_in'		=> $pricenow,
						'total_in'		=> $row->qty * $pricenow,
						'qty_final'		=> $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> $row->qty * $pricenow,
						'date'			=> $date,
						'type'			=> 'WE-IN'
					]);
				}
			}
			
			$dataOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use($date,$branch){
				$query->whereHas('projectSale',function($query) use($date,$branch){
					$query->whereHas('sales',function($query) use($date,$branch){
						$query->where('branch',$branch);
					});
				})->whereRaw("DATE(received_date) = '$date'");
			})->get();
			
			foreach($dataOut as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				if($cek){
					$pricenow = $cek->price_final;
					
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_out'		=> $row->qty,
						'price_out'		=> $pricenow,
						'total_out'		=> $row->qty * $pricenow,
						'qty_final'		=> $cek->qty_final - $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
						'date'			=> $date,
						'type'			=> 'DO'
					]);
				}else{
					$purchase = ProjectPurchase::whereHas('projectPurchaseProduct', function($query) use($row){
						$query->where('product_id', $row->product_id);
					})
					->where('project_id', $row->projectDelivery->project_id)
					->first();
					
					if($purchase){
						$purchase_product = ProjectPurchaseProduct::where('project_purchase_id',$purchase->id)->where('product_id', $row->product_id)->first();

						$real_price = str_replace(',','.',str_replace('.','', $purchase_product->purchaseReal()));

						ProductCogs::create([
							'product_id'	=> $row->product_id,
							'branch'		=> $branch,
							'qty_out'		=> $row->qty,
							'price_out'		=> $real_price,
							'total_out'		=> $row->qty * $real_price,
							'qty_final'		=> -$row->qty,
							'price_final'	=> $real_price,
							'total_final'	=> $row->qty * $real_price,
							'date'			=> $date,
							'type'			=> 'DO'
						]);
					}
					
				}
			}
			
			$dataInRt = ProjectSaleReturnProduct::whereHas('projectSaleReturn', function($query) use ($branch) {
				$query->whereHas('projectSale', function($query) use ($branch) {
					$query->whereHas('sales', function($query) use ($branch) {
						$query->where('branch',$branch);
					});
				});
			})->whereHas('projectSaleReturn',function($query) use($date,$branch){
				$query->where('date_return',$date);
			})
			->get();
			
			foreach($dataInRt as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$row->getDateDelivery())->orderByDesc('id')->first();
				$cek2 = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				if($cek){
					$pricenow = $cek->price_final;
					$pricenew = $cek2->qty_final + $row->qty > 0 ? ($cek2->total_final + ($row->qty * $pricenow)) / ($cek2->qty_final + $row->qty) : $pricenow;

					// old
					// $pricenew = $cek2->qty_final + $row->qty > 0 ? ($cek2->total_final + ($row->qty * $pricenow)) / ($cek2->qty_final + $row->qty) : $row->qty * $pricenow;
					
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_in'		=> $row->qty,
						'price_in'		=> $pricenow,
						'total_in'		=> $row->qty * $pricenow,
						'qty_final'		=> $cek2->qty_final + $row->qty,
						'price_final'	=> $pricenew,
						'total_final'	=> ($cek2->qty_final + $row->qty) * $pricenew,
						'date'			=> $date,
						'type'			=> 'SR'
					]);
				}
			}
			
			$dataOutRt = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn', function($query) use ($branch) {
				$query->whereHas('projectPurchase', function($query) use ($branch) {
					$query->whereHas('sales', function($query) use ($branch) {
						$query->where('branch',$branch);
					});
				});
			})->whereHas('projectPurchaseReturn',function($query) use($date){
				$query->where('date',$date);
			})
			->get();
			
			foreach($dataOutRt as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				if($cek){
					$pricenow = $cek->price_final;
					
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_out'		=> $row->qty,
						'price_out'		=> $pricenow,
						'total_out'		=> $row->qty * $pricenow,
						'qty_final'		=> $cek->qty_final - $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
						'date'			=> $date,
						'type'			=> 'PR'
					]);
				}
			}
			
			$dataOutTp = TransferProduct::whereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','3')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','2')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','1')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_correction')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_out_transfer')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_broken')->whereDate('date',$date);
			})
			->get();
		
			foreach($dataOutTp as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				if($cek){
					$pricenow = $cek->price_final;
					
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_out'		=> $row->qty,
						'price_out'		=> $pricenow,
						'total_out'		=> $row->qty * $pricenow,
						'qty_final'		=> $cek->qty_final - $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
						'date'			=> $date,
						'type'			=> 'WE-OUT'
					]);
				}
			}
			
			$startDate->addDay();
		}
	}
}