<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProjectSaleReturn extends Model
{

	use HasFactory;

	protected $table      = 'project_sale_returns';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'user_id',
		'project_id',
		'project_sale_id',
		'project_return_memo_id',
		'date_return',
		'warehouse_id',
		'address',
		'code',
		'image',
		'note',
		'type',
		'approved_by',
		'grandtotal'
	];

	public function type()
	{
		switch ($this->type) {
			case '1':
				$type = 'Return to supplier / split to another Project.';
				break;
			case '2':
				$type = 'As a Cost.';
				break;
			default:
				$type = 'Invalid';
				break;
		}

		return $type;
	}

	public function warehouse()
	{
		return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
	}

	public function projectReturnMemo()
	{
		return $this->belongsTo('App\Models\ProjectReturnMemo');
	}

	public function approve()
	{
		return $this->belongsTo('App\Models\User', 'approved_by', 'id');
	}

	public static function generateCode()
	{
		$query = ProjectSaleReturn::selectRaw("RIGHT(code, 6) as code")
			->orderByRaw('RIGHT(code, 6) DESC')
			->limit(1)
			->get();

		if ($query->count() > 0) {
			$number = (int)$query[0]->code + 1;
		} else {
			$number = '0001';
		}

		$code = str_pad($number, 6, 0, STR_PAD_LEFT);
		return 'SR/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
	}

	public function projectSale()
	{
		return $this->belongsTo('App\Models\ProjectSale', 'project_sale_id', 'id');
	}

	public function project()
	{
		return $this->belongsTo('App\Models\Project', 'project_id', 'id');
	}

	public function projectSaleReturnProduct()
	{
		return $this->hasMany('App\Models\ProjectSaleReturnProduct');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function attachment()
	{
		if (Storage::exists($this->image)) {
			$image = asset(Storage::url($this->image));
		} else {
			$image = asset('website/empty.jpg');
		}

		return $image;
	}

	public function deleteFile()
	{
		if (Storage::exists($this->image)) {
			Storage::delete($this->image);
		}
	}

	public function getTotal()
	{
		$totalSale = 0;
		$totalPurchase = 0;

		if (date('Y-m-d', strtotime($this->projectSale->created_at)) < '2022-04-01') {
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		} else {
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}

		foreach ($this->projectSaleReturnProduct as $key => $pi) {

			foreach ($this->projectSale->projectSaleProduct->where('product_id', $pi->product_id) as $psp) {
				$price = $psp->best_price ? $psp->best_price : $psp->recommended_price;

				if ($psp->unit == '2' || $psp->unit == '3') {
					$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

					if ($this->project->ppn == '1') {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) >= '2022-06' && $psp->product->type->category->parent()->id !== 18) {
							$totalSale += ($price * $pi->qty) + (($price * $pi->qty) * $persenppn);
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$totalSale += ($price * $pi->qty) + (($price * $pi->qty) * $persenppn);
							} else {
								$totalSale += ($price * $pi->qty * $m2) + (($price * $pi->qty * $m2) * $persenppn);
							}
						}
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) >= '2022-06' && $psp->product->type->category->parent()->id !== 18) {
							$totalSale += $price * $pi->qty;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$totalSale += $price * $pi->qty;
							} else {
								$totalSale += $price * $pi->qty * $m2;
							}
						}
					}
				} else {
					if ($this->project->ppn == '1') {
						$totalSale += $price * $pi->qty + (($price * $pi->qty) * $persenppn);
					} else {
						$totalSale += $price * $pi->qty;
					}
				}
			}
		}

		return $totalSale;
	}

	public function getPurchase()
	{
		$totalPurchase = 0;
		$date = $this->date_return;

		$branch = $this->projectSale->sales->branch;

		foreach ($this->projectSaleReturnProduct as $key => $pi) {

			$adaprice = false;

			foreach ($this->projectSale->projectPurchase()->get() as $pp) {

				if ($pp->ppn == '1') {
					if (date('Y-m-d', strtotime($pp->created_at)) < '2022-04-01') {
						$ppnpembagi = 1.1;
					} else {
						$ppnpembagi = 1.11;
					}
				} else {
					$ppnpembagi = 1;
				}

				foreach ($pp->projectPurchaseProduct->where('product_id', $pi->product_id) as $psp) {
					$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

					if ($psp->unit == '2' || $psp->unit == '3') {
						if ($pp->ppn == '1') {
							if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
								$totalPurchase += ($psp->price / $ppnpembagi) * $pi->qty;
							} else {
								if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
									$totalPurchase += ($psp->price / $ppnpembagi) * $pi->qty;
								} else {
									$totalPurchase += ($psp->price / $ppnpembagi) * $pi->qty * $m2;
								}
							}
						} else {
							if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
								$totalPurchase += $psp->price * $pi->qty;
							} else {
								if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
									$totalPurchase += $psp->price * $pi->qty;
								} else {
									$totalPurchase += $psp->price * $pi->qty * $m2;
								}
							}
						}
					} else {
						if ($pp->ppn == '1') {
							$totalPurchase += ($psp->price / $ppnpembagi) * $pi->qty;
						} else {
							$totalPurchase += $psp->price * $pi->qty;
						}
					}

					$adaprice = true;

					break 2;
				}
			}

			if ($adaprice == false) {
				$totalpo = 0;
				$jumlah = 0;

				if (count(ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($date, $branch) {
					$query->whereHas('projectWarehouse', function ($query) use ($date) {
						$query->whereDate('date_receive', '<=', $date);
					})->whereHas('sales', function ($query) use ($branch) {
						$query->where('branch', $branch);
					});
				})->where('product_id', $pi->product_id)->get()) > 0) {
					foreach (
						ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($date, $branch) {
							$query->whereHas('projectWarehouse', function ($query) use ($date) {
								$query->whereDate('date_receive', '<=', $date);
							})->whereHas('sales', function ($query) use ($branch) {
								$query->where('branch', $branch);
							});
						})->where('product_id', $pi->product_id)->get() as $ppp
					) {

						$ppnpembagipo = 1;

						if (date('Y-m-d', strtotime($ppp->projectPurchase->created_at)) < '2022-04-01') {
							$ppnpembagipo = 1.1;
						} else {
							$ppnpembagipo = 1.11;
						}

						$m2 = (($ppp->product->type->length * $ppp->product->type->width) / 10000) * $ppp->product->carton_pcs;

						if ($ppp->unit == '2' || $ppp->unit == '3') {
							if ($ppp->projectPurchase->ppn == '1') {
								if ($m2 < 1.1 && $ppp->product->type->category->parent()->id !== 18) {
									$totalpo += ($ppp->qty * $ppp->price) / $ppnpembagipo;
								} else {
									if ($m2 < 1.1 && $ppp->product->type->category->parent()->id == 18 && date('Y-m', strtotime($ppp->projectPurchase->created_at)) < '2022-06') {
										$totalpo += ($ppp->qty * $ppp->price) / $ppnpembagipo;
									} else {
										$totalpo += ($ppp->qty * $m2 * $ppp->price) / $ppnpembagipo;
									}
								}
							} else {
								if ($m2 < 1.1 && $ppp->product->type->category->parent()->id !== 18) {
									$totalpo += $ppp->qty * $ppp->price;
								} else {
									if ($m2 < 1.1 && $ppp->product->type->category->parent()->id == 18 && date('Y-m', strtotime($ppp->projectPurchase->created_at)) < '2022-06') {
										$totalpo += $ppp->qty * $ppp->price;
									} else {
										$totalpo += $ppp->qty * $m2 * $ppp->price;
									}
								}
							}
						} else {
							if ($ppp->projectPurchase->ppn == '1') {
								$totalpo += ($ppp->qty * $ppp->price) / $ppnpembagipo;
							} else {
								$totalpo += $ppp->qty * $ppp->price;
							}
						}

						$jumlah += $ppp->qty;
					}
				}

				foreach (
					TransferProduct::whereHas('transfer', function ($query) use ($date, $branch) {
						$query->whereDate('date', '<=', $date)->where('branch', $branch)->where(function ($query) {
							$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
						});
					})->where('product_id', $pi->product_id)->get() as $tp
				) {
					$totalpo += $tp->price;
					$jumlah += $tp->qty;
				}

				$pricepurchase = round($totalpo / $jumlah, 2);
				$totalPurchase += $pricepurchase * $pi->qty;
			}
		}

		return $totalPurchase;
	}

	public function getTotalWithoutPpn()
	{
		$totalSale = 0;
		$totalPurchase = 0;

		if (date('Y-m-d', strtotime($this->projectSale->created_at)) < '2022-04-01') {
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		} else {
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}

		foreach ($this->projectSaleReturnProduct as $key => $pi) {
			foreach ($this->projectSale->projectSaleProduct->where('product_id', $pi->product_id) as $psp) {
				if ($psp->unit == '2' || $psp->unit == '3') {
					$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

					if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) >= '2022-06' && $psp->product->type->category->parent()->id !== 18) {
						$totalSale += $psp->best_price * $pi->qty;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$totalSale += $psp->best_price * $pi->qty;
						} else {
							$totalSale += $psp->best_price * $pi->qty * $m2;
						}
					}
				} else {
					$totalSale += $psp->best_price * $pi->qty;
				}
			}
		}

		return $totalSale;
	}

	public function getTotalRaw()
	{
		$discount = $this->project->discount;
		$totalqty = 0;
		$totalqtyreturn = 0;

		$totalSale = 0;

		foreach ($this->projectSaleReturnProduct as $key => $pi) {

			foreach ($this->projectSale->projectSaleProduct->where('product_id', $pi->product_id) as $psp) {
				if ($psp->unit == '2' || $psp->unit == '3') {
					$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

					if ($this->project->ppn == '1') {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) >= '2022-06' && $psp->product->type->category->parent()->id !== 18) {
							$totalSale += ($psp->best_price * $pi->qty) + (($psp->best_price * $pi->qty));
							$totalqty += $psp->qty;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$totalSale += ($psp->best_price * $pi->qty) + (($psp->best_price * $pi->qty));
								$totalqty += $psp->qty;
							} else {
								$totalSale += ($psp->best_price * $pi->qty * $m2) + (($psp->best_price * $pi->qty * $m2));
								$totalqty += $psp->qty * $m2;
							}
						}
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) >= '2022-06' && $psp->product->type->category->parent()->id !== 18) {
							$totalSale += $psp->best_price * $pi->qty;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($this->projectSale->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$totalSale += $psp->best_price * $pi->qty;
								$totalqty += $psp->qty;
							} else {
								$totalSale += $psp->best_price * $pi->qty * $m2;
								$totalqty += $psp->qty * $m2;
							}
						}
					}
				} else {
					if ($this->project->ppn == '1') {
						$totalSale += $psp->best_price * $pi->qty + (($psp->best_price * $pi->qty));
					} else {
						$totalSale += $psp->best_price * $pi->qty;
					}
					$totalqty += $psp->qty;
				}
			}

			$totalqtyreturn += $pi->qty;
		}

		$discount = $discount > 0 ? ($discount / $totalqty * $totalqtyreturn) : 0;

		return $totalSale - $discount;
	}

	public function getTotalRawNew()
	{

		$ppnpembagi = 1;

		if (date('Y-m-d', strtotime($this->projectSale->created_at)) < '2022-04-01') {
			$ppnpembagi = 1.1;
		} else {
			$ppnpembagi = 1.11;
		}

		$total = round($this->grandtotal / $ppnpembagi);

		return $total;
	}

	public function getTotalNew()
	{

		$total = $this->grandtotal;

		return $total;
	}

	public function updateGrandtotal()
	{
		$grandtotal = $this->getTotal();

		ProjectSaleReturn::find($this->id)->update([
			'grandtotal'	=> round($grandtotal, 2)
		]);
	}


	public function image()
	{
		if (Storage::exists($this->image)) {
			$image = asset(Storage::url($this->image));
		} else {
			$image = asset('website/empty.jpg');
		}

		return $image;
	}



	// private function getCodeFromDesc($description)
	// {
	// 	$do_code = NULL;
	// 	$pattern = "/DO\/\d{2}\/\d{2}\/\d{2}\/\d{6}/";

	// 	if (preg_match($pattern, $description, $matches)) {
	// 		$do_code = $matches[0];
	// 	}

	// 	return $do_code;
	// }

	// public function compareProductReturnAndDelivery($description)
	// {
	// 	$code = $this->getCodeFromDesc($description);
	// 	$exist = false;
	// 	$projectDelivery = ProjectDelivery::where('code', 'LIKE', "%$code%")->first();

	// 	foreach ($this->projectSaleReturnProduct as $saleReturnProduct) {
	// 		$deliveriesSameProduct  = $saleReturnProduct->countDeliveriesForSameProduct()['count'];
	// 		$firstRecordDelivery  = $saleReturnProduct->countDeliveriesForSameProduct()['firstRecordDelivery'];
	// 		foreach ($projectDelivery->projectDeliveryProduct as $devliveryProjectProduct) {
	// 			if (($deliveriesSameProduct  > 1 && $firstRecordDelivery == $projectDelivery->id) || $deliveriesSameProduct  == 1) {
	// 				if ($saleReturnProduct->product_id == $devliveryProjectProduct->product_id) {
	// 					$exist = true;
	// 				}
	// 			}
	// 		}

	// 	}

	// 	return $exist;
	// }

	// simplified function
	private function getCodeFromDesc($description)
	{
		$pattern = "/DO\/\d{2}\/\d{2}\/\d{2}\/\d{6}/";
		return preg_match($pattern, $description, $matches) ? $matches[0] : null;
	}

	public function compareProductReturnAndDelivery($description)
	{
		$code = $this->getCodeFromDesc($description);
		$exist = false;
		$projectDelivery = ProjectDelivery::where('code', 'LIKE', "%$code%")->first();

		if ($projectDelivery) {
			foreach ($this->projectSaleReturnProduct as $saleReturnProduct) {
				// mendapatkan jumlah total delivery dengan item yang sama, dan mendapatkan first record delivery
				$deliveryInfo = $saleReturnProduct->countDeliveriesForSameProduct();
				$deliveriesSameProduct = $deliveryInfo['count'];
				$firstRecordDelivery = $deliveryInfo['firstRecordDelivery'];

				// check apakah ada product yang sama antara retur dan delivery
				$productMatch = $projectDelivery->projectDeliveryProduct->contains('product_id', $saleReturnProduct->product_id);

				if ($productMatch && (($deliveriesSameProduct > 1 && $firstRecordDelivery == $projectDelivery->id) || ($productMatch && $deliveriesSameProduct == 1))) {
					$exist = true;
					break;
				}
			}
		}

		return $exist;
	}
}
