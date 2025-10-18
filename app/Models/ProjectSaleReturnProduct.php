<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSaleReturnProduct extends Model
{

	use HasFactory;

	protected $table      = 'project_sale_return_products';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'project_sale_return_id',
		'product_id',
		'qty',
		'unit'
	];

	public function projectSaleReturn()
	{
		return $this->belongsTo('App\Models\ProjectSaleReturn', 'project_sale_return_id', 'id');
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

	public function salePrice()
	{
		$price = 0;

		$project = ProjectSaleReturn::find($this->projectSaleReturn->id);

		if ($this->unit == '2' || $this->unit == '3') {
			$psp = $project->projectSale->projectSaleProduct->where('product_id', $this->product_id)->first();

			$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;
			$countbox = ceil(round($psp->qty / $m2, 2));

			if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
				$price = $psp->best_price;
			} else {
				$price = $psp->best_price * $m2;
			}
		}

		if ($this->unit == '1' || $this->unit == '4') {
			$psp = $project->projectSale->projectSaleProduct->where('product_id', $this->product_id)->first();

			$price = $psp->best_price;
		}

		return number_format(round($price), 0, ',', '.');
	}

	public function purchasePrice()
	{
		$price = 0;

		$cek = ProductCogs::where('product_id', $this->product_id)->where('branch', $this->projectSaleReturn->projectSale->sales->branch)->where('date', '<=', $this->getDateDelivery())->orderByDesc('id')->first();

		if ($cek) {
			$price = $cek->price_final;
		}

		return number_format($price, 2, ',', '.');
	}

	public function purchasePriceBefore($date)
	{

		$total = 0;
		$qty = 0;

		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($date) {
			$query->whereHas('projectWarehouse', function ($query) use ($date) {
				$query->whereDate('date_receive', '<', $date);
			});
		})->where('product_id', $this->product_id)->get();

		foreach ($data as $psp) {
			if (date('Y-m-d', strtotime($psp->projectPurchase->created_at)) < '2022-04-01') {
				$persenppn = 0.1;
				$ppnpembagi = 1.1;
			} else {
				$persenppn = 0.11;
				$ppnpembagi = 1.11;
			}

			if ($psp->unit == '2' || $psp->unit == '3') {
				$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

				if ($psp->projectPurchase->ppn == '1') {
					if ($psp->projectPurchase->currency_id !== '5') {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
							$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id) / $ppnpembagi;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id) / $ppnpembagi;
							} else {
								$total += ($m2 * $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id)) / $ppnpembagi;
							}
						}
					} else {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
							$total += $psp->price * $psp->qty / $ppnpembagi;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->qty / $ppnpembagi;
							} else {
								$total += ($m2 * $psp->price * $psp->qty) / $ppnpembagi;
							}
						}
					}
				} else {
					if ($psp->projectPurchase->currency_id !== '5') {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
							$total = $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id);
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id);
							} else {
								$total += $m2 * $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id);
							}
						}
					} else {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
							$total = $psp->price * $psp->qty;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->qty;
							} else {
								$total += $m2 * $psp->price * $psp->qty;
							}
						}
					}
				}
			} elseif ($psp->unit == '1' || $psp->unit == '4') {
				if ($psp->projectPurchase->ppn == '1') {
					if ($psp->projectPurchase->currency_id !== '5') {
						$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id) / $ppnpembagi;
					} else {
						$total += $psp->price * $psp->qty / $ppnpembagi;
					}
				} else {
					if ($psp->projectPurchase->currency_id !== '5') {
						$total += $psp->price * $psp->qty * $psp->product->getConvertPrice($psp->projectPurchase->currency_id);
					} else {
						$total += $psp->price * $psp->qty;
					}
				}
			}

			$qty += $psp->qty;
		}

		foreach (
			TransferProduct::whereHas('transfer', function ($query) use ($date) {
				$query->whereNotNull('for_starting')->whereDate('date', '<', $date);;
			})->where('product_id', $this->product_id)->get() as $tp
		) {
			$total += $tp->price;
			$qty += $tp->qty;
		}

		$price = $qty > 0 ? $total / $qty : 0;

		return number_format($price, 2, ',', '.');
	}

	public function getDateDelivery()
	{
		$sale = $this->projectSaleReturn->projectSale;

		$date = '';

		foreach ($sale->projectDelivery->whereNotNull('received_date') as $pd) {
			foreach ($pd->projectDeliveryProduct->where('product_id', $this->product_id) as $pdp) {
				$date = $pd->received_date;
			}
		}

		return $date;
	}

	
	// public function countDeliveriesForSameProduct()
	// {
	// 	$count = 0;

	// 	$product_id = $this->product_id;
	// 	$project = $this->projectSaleReturn->project;
	// 	$firstRecordDelivery = $project->projectDelivery()->orderBy('id', 'ASC')->first()->id;

	// 	foreach ($project->projectDelivery as $pd) {
	// 		foreach ($pd->projectDeliveryProduct as $pdp) {
	// 			if ($pdp->product_id == $product_id) {
	// 				$count++;
	// 			}
	// 		}
	// 	}

	// 	return [
	// 		'count' => $count,
	// 		'firstRecordDelivery' => $firstRecordDelivery
	// 	];
	// }
	
	
	// untuk mengecek apakah pengiriman pertama dan berikutnya memiliki tipe barang yang sama
	public function countDeliveriesForSameProduct()
	{
		$product_id = $this->product_id;
		$project = $this->projectSaleReturn->project;
		$firstRecordDelivery = $project->projectDelivery()->orderBy('id', 'ASC')->first()->id;

		// Filter deliveries that contain the product and count them
		$count = $project->projectDelivery->filter(function ($pd) use ($product_id) {
			return $pd->projectDeliveryProduct->contains('product_id', $product_id);
		})->count();

		return [
			'count' => $count,
			'firstRecordDelivery' => $firstRecordDelivery
		];
	}

}
