<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectFromStockProduct extends Model
{

	use HasFactory;

	protected $table      = 'project_from_stock_products';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'project_from_stock_id',
		'product_id',
		'qty',
		'unit'
	];

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

	public function unitConvert()
	{
		if ($this->unit == '2' || $this->unit == '3') {
			return 'Box';
		} else {
			return $this->unit == '4' ? 'Meter(Custom)' : 'Pcs';
		}
	}

	public function projectFromStock()
	{
		return $this->belongsTo('App\Models\ProjectFromStock');
	}

	public function product()
	{
		return $this->belongsTo('App\Models\Product');
	}

	public function sellPrice()
	{
		if ($this->projectFromStock->project_sale_id > 0) {
			$projectsale = ProjectSale::find($this->projectFromStock->project_sale_id);
			$dataprice = $projectsale->projectSaleProduct->where('product_id', $this->product_id)->first();

			$m2 = (($dataprice->product->type->length * $dataprice->product->type->width) / 10000) * $dataprice->product->carton_pcs;

			$price = 0;

			if ($dataprice->unit == '2' || $dataprice->unit == '3') {
				if ($m2 < 1.1 && $dataprice->product->type->category->parent()->id !== 18) {
					$price = $dataprice->best_price ? $dataprice->best_price : $dataprice->recommended_price;
				} else {
					if ($m2 < 1.1 && date('Y-m', strtotime($projectsale->project->created_at)) < '2022-06' && $dataprice->product->type->category->parent()->id == 18) {
						$price = $dataprice->best_price ? $dataprice->best_price : $dataprice->recommended_price;
					} else {
						$price = ($dataprice->best_price ? $dataprice->best_price : $dataprice->recommended_price) * $m2;
					}
				}
			} else {
				$price = $dataprice->best_price ? $dataprice->best_price : $dataprice->recommended_price;
			}

			return $price;
		} else {
			return 0;
		}
	}

	// public function buyPrice()
	// {
	// 	$qty = 0;
	// 	$total = 0;
	// 	$qty_product_out = 0;
	// 	$qty_product_in = 0;
	// 	$qty_stock = 0;
	// 	$warehouse_id = array();
	// 	$final_price_in = 0;
	// 	$dataTransferIN = [];

	// 	$branch = $this->projectFromStock->projectSale->sales->branch;

	// 	$pp = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
	// 		$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
	// 			$query->where('branch', $branch);
	// 		});
	// 	})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();

	// 	$tp = TransferProduct::whereHas('transfer', function ($query) use ($branch) {
	// 		$query->where('branch', $branch)->where(function ($query) {
	// 			$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
	// 		});
	// 	})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();

	// 	foreach (Stock::where('branch', $branch)->where('product_id', $this->product_id)->whereDate('created_at','<',$pp ? $pp->created_at :  $tp->created_at)->get()as $sqty) {
	// 		$qty_stock +=  $sqty->qty;
	// 	}


	// 	if ($qty_stock > 0) {
	// 		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
	// 			$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
	// 				$query->where('branch', $branch);
	// 			});
	// 		})->where('product_id', $this->product_id)->get();

	// 		foreach ($data as $psp) {
	// 			if (date('Y-m-d', strtotime($psp->projectPurchase->created_at)) < '2022-04-01') {
	// 				$persenppn = 0.1;
	// 				$ppnpembagi = 1.1;
	// 			} else {
	// 				$persenppn = 0.11;
	// 				$ppnpembagi = 1.11;
	// 			}

	// 			if ($psp->projectPurchase->ppn == '1') {
	// 				if ($psp->projectPurchase->currency_id !== '5') {
	// 					$total += $psp->price * $psp->projectPurchase->currency_rate / $ppnpembagi;
	// 				} else {
	// 					$total += $psp->price / $ppnpembagi;
	// 				}
	// 			} else {
	// 				if ($psp->projectPurchase->currency_id !== '5') {
	// 					$total += $psp->price * $psp->projectPurchase->currency_rate;
	// 				} else {
	// 					$total += $psp->price;
	// 				}
	// 			}

	// 			$qty++;
	// 		}

	// 		foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch) {
	// 			$query->where('branch', $branch)->where(function ($query) {
	// 				$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
	// 			});
	// 		})->where('product_id', $this->product_id)->get() as $tp) {
	// 			$m2 = (( $tp->product->type->length * $tp->product->type->width ) / 10000) * $tp->product->carton_pcs;


	// 			if($m2 < 1.1 && $tp->product->type->category->parent()->id !== 18){
	// 				$total += ($tp->price / $tp->qty);
	// 				$final_price_in += ($tp->price / $tp->qty);
	// 			}else{
	// 				if($m2 < 1.1 && date('Y-m',strtotime($tp->created_at)) < '2022-06' && $tp->product->type->category->parent()->id == 18){
	// 					$total += ($tp->price / $tp->qty);
	// 					$final_price_in += ($tp->price / $tp->qty);
	// 				}else{
	// 					$total += ($tp->price / $tp->qty) / $m2;
	// 					$final_price_in += ($tp->price / $tp->qty) / $m2;
	// 				}
	// 			}
			
	// 			$qty_product_in +=  $tp->qty;
	// 			array_push($warehouse_id, $tp->transfer->to_warehouse_id);

	// 			$qty++;
	// 		}

	// 		$dataTransferIN = [
	// 			'product_id' => $this->product_id,
	// 			'price' => $final_price_in,
	// 			'qty' => $qty_product_in
	// 		];

	// 		foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch, $warehouse_id) {
	// 			$query->where('branch', $branch)->where(function ($query) use ($warehouse_id) {
	// 				$query->whereIn('from_warehouse_id', $warehouse_id)->whereNull('to_warehouse_id');
	// 			});
	// 		})->where('product_id', $this->product_id)->get() as $tp) {
	// 			$qty_product_out += $tp->qty;
	// 		}

	// 			if ($qty_product_out >= $dataTransferIN['qty'] && $this->product_id == $dataTransferIN['product_id'] && $dataTransferIN['price'] > 0) {
	// 			$total -=  $dataTransferIN['price'];
	// 			$qty--;
	// 		}
	// 	} else {

	// 		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
	// 			$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
	// 				$query->where('branch', $branch);
	// 			});
	// 		})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();


	// 		if (date('Y-m-d', strtotime($data->projectPurchase->created_at)) < '2022-04-01') {
	// 			$persenppn = 0.1;
	// 			$ppnpembagi = 1.1;
	// 		} else {
	// 			$persenppn = 0.11;
	// 			$ppnpembagi = 1.11;
	// 		}

	// 		if ($data->projectPurchase->ppn == '1') {
	// 			if ($data->projectPurchase->currency_id !== '5') {
	// 				$total += $data->price * $data->projectPurchase->currency_rate / $ppnpembagi;
	// 			} else {
	// 				$total += $data->price / $ppnpembagi;
	// 			}
	// 		} else {
	// 			if ($data->projectPurchase->currency_id !== '5') {
	// 				$total += $data->price * $data->projectPurchase->currency_rate;
	// 			} else {
	// 				$total += $data->price;
	// 			}
	// 		}

	// 		$qty++;
	// 	}

	// 	$price = $qty > 0 ? $total / $qty : 0;

	// 	return $price;
	// }


	// public function buyPrice()
	// {
	// 	$qty = 0;
	// 	$total = 0;
	// 	$qty_product_out = 0;
	// 	$qty_product_in = 0;
	// 	$qty_stock = 0;
	// 	$warehouse_id = array();
	// 	$final_price_in = 0;
	// 	$dataTransferIN = [];

	// 	$branch = $this->projectFromStock->projectSale->sales->branch;

	// 	$pp = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
	// 		$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
	// 			$query->where('branch', $branch);
	// 		});
	// 	})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();

	// 	$tp = TransferProduct::whereHas('transfer', function ($query) use ($branch) {
	// 		$query->where('branch', $branch)->where(function ($query) {
	// 			$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
	// 		});
	// 	})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();

	// 	foreach (Stock::where('branch', $branch)->where('product_id', $this->product_id)->whereDate('created_at','<', isset($pp) ? $pp->created_at :  $tp->created_at)->get()as $sqty) {
	// 		$qty_stock +=  $sqty->qty;
	// 	}

	// 	if ($qty_stock > 0) {
	// 		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
	// 			$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
	// 				$query->where('branch', $branch);
	// 			});
	// 		})->where('product_id', $this->product_id)->get();

	// 		foreach ($data as $psp) {
	// 			if (date('Y-m-d', strtotime($psp->projectPurchase->created_at)) < '2022-04-01') {
	// 				$persenppn = 0.1;
	// 				$ppnpembagi = 1.1;
	// 			} else {
	// 				$persenppn = 0.11;
	// 				$ppnpembagi = 1.11;
	// 			}

	// 			if ($psp->projectPurchase->ppn == '1') {
	// 				if ($psp->projectPurchase->currency_id !== '5') {
	// 					$total += $psp->price * $psp->projectPurchase->currency_rate / $ppnpembagi;
	// 				} else {
	// 					$total += $psp->price / $ppnpembagi;
	// 				}
	// 			} else {
	// 				if ($psp->projectPurchase->currency_id !== '5') {
	// 					$total += $psp->price * $psp->projectPurchase->currency_rate;
	// 				} else {
	// 					$total += $psp->price;
	// 				}
	// 			}

	// 			$qty++;
	// 		}

	// 		foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch) {
	// 			$query->where('branch', $branch)->where(function ($query) {
	// 				$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
	// 			});
	// 		})->where('product_id', $this->product_id)->get() as $tp) {
	// 			$m2 = (( $tp->product->type->length * $tp->product->type->width ) / 10000) * $tp->product->carton_pcs;


	// 			if($m2 < 1.1 && $tp->product->type->category->parent()->id !== 18){
	// 				$total += ($tp->price / $tp->qty);
	// 				$final_price_in += ($tp->price / $tp->qty);
	// 			}else{
	// 				if($m2 < 1.1 && date('Y-m',strtotime($tp->created_at)) < '2022-06' && $tp->product->type->category->parent()->id == 18){
	// 					$total += ($tp->price / $tp->qty);
	// 					$final_price_in += ($tp->price / $tp->qty);
	// 				}else{
	// 					$total += ($tp->price / $tp->qty) / $m2;
	// 					$final_price_in += ($tp->price / $tp->qty) / $m2;
	// 				}
	// 			}
			
	// 			$qty_product_in +=  $tp->qty;
	// 			array_push($warehouse_id, $tp->transfer->to_warehouse_id);

	// 			$qty++;
	// 		}

	// 		$dataTransferIN = [
	// 			'product_id' => $this->product_id,
	// 			'price' => $final_price_in,
	// 			'qty' => $qty_product_in
	// 		];

	// 		foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch, $warehouse_id) {
	// 			$query->where('branch', $branch)->where(function ($query) use ($warehouse_id) {
	// 				$query->whereIn('from_warehouse_id', $warehouse_id)->whereNull('to_warehouse_id');
	// 			});
	// 		})->where('product_id', $this->product_id)->get() as $tp) {
	// 			$qty_product_out += $tp->qty;
	// 		}

	// 		// JIKA BARANG KELUAR QTY LEBIH DARI SAMA DENGAN BARANG MASUK DAN HARGA MASUK LEBIH DARI 0
	// 		if ($qty_product_out >= $dataTransferIN['qty'] && $this->product_id == $dataTransferIN['product_id'] && $dataTransferIN['price'] > 0) {
	// 			$total -=  $dataTransferIN['price'];
	// 			$qty--;
	// 		}
	// 	} else {

	// 		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
	// 			$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
	// 				$query->where('branch', $branch);
	// 			});
	// 		})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();
	// 		if($data){
	// 			if (date('Y-m-d', strtotime($data->projectPurchase->created_at)) < '2022-04-01') {
	// 				$persenppn = 0.1;
	// 				$ppnpembagi = 1.1;
	// 			} else {
	// 				$persenppn = 0.11;
	// 				$ppnpembagi = 1.11;
	// 			}
	
	// 			if ($data->projectPurchase->ppn == '1') {
	// 				if ($data->projectPurchase->currency_id !== '5') {
	// 					$total += $data->price * $data->projectPurchase->currency_rate / $ppnpembagi;
	// 				} else {
	// 					$total += $data->price / $ppnpembagi;
	// 				}
	// 			} else {
	// 				if ($data->projectPurchase->currency_id !== '5') {
	// 					$total += $data->price * $data->projectPurchase->currency_rate;
	// 				} else {
	// 					$total += $data->price;
	// 				}
	// 			}
	// 		}else{
	// 			foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch) {
	// 				$query->where('branch', $branch)->where(function ($query) {
	// 					$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
	// 				});
	// 			})->where('product_id', $this->product_id)->get() as $tp) {
	// 				$m2 = (( $tp->product->type->length * $tp->product->type->width ) / 10000) * $tp->product->carton_pcs;
	
	
	// 				if($m2 < 1.1 && $tp->product->type->category->parent()->id !== 18){
	// 					$total += ($tp->price / $tp->qty);
	// 				}else{
	// 					if($m2 < 1.1 && date('Y-m',strtotime($tp->created_at)) < '2022-06' && $tp->product->type->category->parent()->id == 18){
	// 						$total += ($tp->price / $tp->qty);
	
	// 					}else{
	// 						$total += ($tp->price / $tp->qty) / $m2;
	// 					}
	// 				}
	// 			}
	// 		}

	// 		$qty++;
	// 	}

	// 	$price = $qty > 0 ? $total / $qty : 0;

	// 	return $price;
	// }



	//! buyprice old sistem
//     public function buyPrice()
// 	{
// 		$qty = 0;
// 		$total = 0;
// 		$qty_product_out = 0;
// 		$qty_product_in = 0;
// 		$qty_stock = 0;
// 		$warehouse_id = array();
// 		$final_price_in = 0;
// 		$dataTransferIN = [];

// 		$branch = $this->projectFromStock->projectSale->sales->branch;

// 		$pp = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
// 			$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
// 				$query->where('branch', $branch);
// 			});
// 		})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();

// 		$tp = TransferProduct::whereHas('transfer', function ($query) use ($branch) {
// 			$query->where('branch', $branch)->where(function ($query) {
// 				$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
// 			});
// 		})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();

// 		foreach (Stock::where('branch', $branch)->where('product_id', $this->product_id)->whereDate('created_at','<', isset($pp) ? $pp->created_at :  $tp->created_at)->get()as $sqty) {
// 			$qty_stock +=  $sqty->qty;
// 		}

// 		if ($qty_stock > 0) {
// 			$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
// 				$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
// 					$query->where('branch', $branch);
// 				});
// 			})->where('product_id', $this->product_id)->get();

// 			foreach ($data as $psp) {
// 				if (date('Y-m-d', strtotime($psp->projectPurchase->created_at)) < '2022-04-01') {
// 					$persenppn = 0.1;
// 					$ppnpembagi = 1.1;
// 				} else {
// 					$persenppn = 0.11;
// 					$ppnpembagi = 1.11;
// 				}

// 				if ($psp->projectPurchase->ppn == '1') {
// 					if ($psp->projectPurchase->currency_id !== '5') {
// 						$total += $psp->price * $psp->projectPurchase->currency_rate / $ppnpembagi;
// 					} else {
// 						$total += $psp->price / $ppnpembagi;
// 					}
// 				} else {
// 					if ($psp->projectPurchase->currency_id !== '5') {
// 						$total += $psp->price * $psp->projectPurchase->currency_rate;
// 					} else {
// 						$total += $psp->price;
// 					}
// 				}

// 				$qty++;
// 			}

// 			foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch) {
// 				$query->where('branch', $branch)->where(function ($query) {
// 					$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
// 				});
// 			})->where('product_id', $this->product_id)->get() as $tp) {
// 				$m2 = (( $tp->product->type->length * $tp->product->type->width ) / 10000) * $tp->product->carton_pcs;


// 				if($m2 < 1.1 && $tp->product->type->category->parent()->id !== 18){
// 					$total += ($tp->price / $tp->qty);
// 					$final_price_in += ($tp->price / $tp->qty);
// 				}else{
// 					if($m2 < 1.1 && date('Y-m',strtotime($tp->created_at)) < '2022-06' && $tp->product->type->category->parent()->id == 18){
// 						$total += ($tp->price / $tp->qty);
// 						$final_price_in += ($tp->price / $tp->qty);
// 					}else{
// 						$total += ($tp->price / $tp->qty) / $m2;
// 						$final_price_in += ($tp->price / $tp->qty) / $m2;
// 					}
// 				}
			
// 				$qty_product_in +=  $tp->qty;
// 				array_push($warehouse_id, $tp->transfer->to_warehouse_id);

// 				$qty++;
// 			}

// 			$dataTransferIN = [
// 				'product_id' => $this->product_id,
// 				'price' => $final_price_in,
// 				'qty' => $qty_product_in
// 			];

// 			foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch, $warehouse_id) {
// 				$query->where('branch', $branch)->where(function ($query) use ($warehouse_id) {
// 					$query->whereIn('from_warehouse_id', $warehouse_id)->whereNull('to_warehouse_id');
// 				});
// 			})->where('product_id', $this->product_id)->get() as $tp) {
// 				$qty_product_out += $tp->qty;
// 			}

// 				if ($qty_product_out >= $dataTransferIN['qty'] && $this->product_id == $dataTransferIN['product_id'] && $dataTransferIN['price'] > 0) {
// 				$total -=  $dataTransferIN['price'];
// 				$qty--;
// 			}
// 		} else {

// 			$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
// 				$query->whereHas('projectWarehouse')->whereHas('sales', function ($query) use ($branch) {
// 					$query->where('branch', $branch);
// 				});
// 			})->where('product_id', $this->product_id)->orderBy('created_at', 'desc')->first();
// 			if($data){
// 				if (date('Y-m-d', strtotime($data->projectPurchase->created_at)) < '2022-04-01') {
// 					$persenppn = 0.1;
// 					$ppnpembagi = 1.1;
// 				} else {
// 					$persenppn = 0.11;
// 					$ppnpembagi = 1.11;
// 				}
	
// 				if ($data->projectPurchase->ppn == '1') {
// 					if ($data->projectPurchase->currency_id !== '5') {
// 						$total += $data->price * $data->projectPurchase->currency_rate / $ppnpembagi;
// 					} else {
// 						$total += $data->price / $ppnpembagi;
// 					}
// 				} else {
// 					if ($data->projectPurchase->currency_id !== '5') {
// 						$total += $data->price * $data->projectPurchase->currency_rate;
// 					} else {
// 						$total += $data->price;
// 					}
// 				}
// 			}else{
// 				foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch) {
// 					$query->where('branch', $branch)->where(function ($query) {
// 						$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
// 					});
// 				})->where('product_id', $this->product_id)->get() as $tp) {
// 					$m2 = (( $tp->product->type->length * $tp->product->type->width ) / 10000) * $tp->product->carton_pcs;
	
	
// 					if($m2 < 1.1 && $tp->product->type->category->parent()->id !== 18){
// 						$total += ($tp->price / $tp->qty);
// 					}else{
// 						if($m2 < 1.1 && date('Y-m',strtotime($tp->created_at)) < '2022-06' && $tp->product->type->category->parent()->id == 18){
// 							$total += ($tp->price / $tp->qty);
	
// 						}else{
// 							$total += ($tp->price / $tp->qty) / $m2;
// 						}
// 					}
// 				}
// 			}

// 			$qty++;
// 		}

// 		$price = $qty > 0 ? $total / $qty : 0;

// 		return $price;
// 	}
	public function buyPrice(){
		$branch = $this->projectFromStock->projectSale->sales->branch;
		$total = 0;
		$projectWarehouseProduct = ProjectWarehouseProduct::where('product_id', $this->product_id)->whereHas('projectWarehouse', function ($query) use ($branch) {
			$query->whereHas('projectPurchase', function ($query) use ($branch) {
				$query->whereHas('sales', function ($query) use ($branch) {
					$query->where('branch', $branch);
				});
			});
		})->first();
		$m2 = (( $projectWarehouseProduct->product->type->length * $projectWarehouseProduct->product->type->width ) / 10000) * $projectWarehouseProduct->product->carton_pcs;
	
	

		if ($projectWarehouseProduct->unit == '2' || $projectWarehouseProduct->unit == '3') {
			if($m2 < 1.1 && $projectWarehouseProduct->product->type->category->parent()->id !== 18){
				$total += str_replace(',','.',str_replace('.','', $projectWarehouseProduct->averageBuyPrice()));
			}else{
				if($m2 < 1.1 && date('Y-m',strtotime($projectWarehouseProduct->created_at)) < '2022-06' && $projectWarehouseProduct->product->type->category->parent()->id == 18){
					$total += str_replace(',','.',str_replace('.','', $projectWarehouseProduct->averageBuyPrice()));
	
				}else{
					$total += str_replace(',','.',str_replace('.','', $projectWarehouseProduct->averageBuyPrice())) / $m2;
				}
			}
	
		}else if($projectWarehouseProduct->unit == '1' || $projectWarehouseProduct->unit == '4'){
			$total += str_replace(',','.',str_replace('.','', $projectWarehouseProduct->averageBuyPrice()));
		}
		
		$finalPrice = $total;

		return $finalPrice;
	}

}
