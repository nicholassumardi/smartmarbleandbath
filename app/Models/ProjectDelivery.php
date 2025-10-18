<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectDelivery extends Model
{

	use HasFactory;

	protected $table      = 'project_deliveries';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'id',
		'user_id',
		'project_id',
		'project_sale_id',
		'code',
		'city_id',
		'receiver_name',
		'delivery_date',
		'received_date',
		'due_date',
		'due_date_tt',
		'image_tt',
		'email',
		'phone',
		'is_dropshipper',
		'dropshipper_id',
		'address',
		'warehouse_id',
		'vendor_id',
		'approved_by',
		'acknowledged_by',
		'image',
		'proforma_code',
		'is_sales',
		'pick_up_name',
		'pick_up_plat',
		'pick_up_vehicle',
		'subtotal_product',
		'tax_product',
		'grandtotal_product',
		'subtotal_service',
		'tax_service',
		'grandtotal_service',
		'service_note',
		'invoice_note',
		'break_tolerance',
	];

	public function approve()
	{
		return $this->belongsTo('App\Models\User', 'approved_by', 'id');
	}

	public function cashFlow()
	{
		$cf = CashFlow::where('type', 'project_deliveries')->where('type_id', $this->id)->first();

		if ($cf) {
			return $cf;
		} else {
			return '';
		}
	}

	public function acknowledge()
	{
		return $this->belongsTo('App\Models\User', 'acknowledged_by', 'id');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function isDropshipper()
	{
		switch ($this->is_dropshipper) {
			case '2':
				$status = 'Yes';
				break;
			case '1':
				$status = 'No';
				break;
			default:
				$status = 'Invalid';
				break;
		}

		return $status;
	}

	public function city()
	{
		return $this->belongsTo('App\Models\City');
	}

	public function warehouse()
	{
		return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
	}

	public function vendor()
	{
		return $this->belongsTo('App\Models\Vendor');
	}

	public function dropshipper()
	{
		return $this->belongsTo('App\Models\Dropshipper', 'dropshipper_id', 'id');
	}

	public function projectSale()
	{
		return $this->belongsTo('App\Models\ProjectSale', 'project_sale_id', 'id');
	}

	public function projectPay()
	{
		return $this->hasMany('App\Models\ProjectPay');
	}

	public function totalPay()
	{
		$total = 0;

		foreach (ProjectPay::where('project_delivery_id', $this->id)->get() as $row) {
			$total += $row->nominal;
		}

		return $total;
	}
	public function totalPayAR()
	{
		$total = 0;
		$isExist = array();
		$coa_name = '';

		foreach (ProjectPay::Where('project_id', $this->project_id)->get() as $row) {

			$total += $row->nominal;

			if (!in_array($row->coa_id, $isExist)) {
				$coa_name .= $row->coa->name . '<br>';
				array_push($isExist, $row->coa_id);
			}
		}

		$cb = CashBank::where('lookable_type', 'projects')->where('code', 'not like', "FEE-PTA%")->where('code', 'not like', "FEE-SMB%")->where('lookable_id', $this->project_id)->get();

		if (count($cb) > 0) {
			foreach ($cb as $rowcb) {
				foreach ($rowcb->cashBankDetail()->where('coa_id', 67)->get() as $cbcb) {
					if ($cbcb->type == '2') {
						$total += $cbcb->nominal;
					}
				}
			}
		}

		$arrResult = [
			'total'      => $total,
			'coa_name'   => $coa_name
		];


		return $arrResult;
	}

	public function project()
	{
		return $this->belongsTo('App\Models\Project', 'project_id', 'id');
	}

	public function projectDeliveryProduct()
	{
		return $this->hasMany('App\Models\ProjectDeliveryProduct');
	}

	public function projectDeliveryTrack()
	{
		return $this->hasMany('App\Models\ProjectDeliveryTrack');
	}

	public static function generateCode()
	{
		$query = ProjectDelivery::selectRaw("RIGHT(code, 6) as code")
			->orderByRaw('RIGHT(code, 6) DESC')
			->limit(1)
			->get();

		if ($query->count() > 0) {
			$number = (int)$query[0]->code + 1;
		} else {
			$number = '0001';
		}

		$code = str_pad($number, 6, 0, STR_PAD_LEFT);
		return 'DO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
	}

	public static function generateCodeProforma()
	{
		$query = ProjectDelivery::selectRaw("RIGHT(proforma_code, 6) as proforma_code")
			->orderByRaw('RIGHT(proforma_code, 6) DESC')
			->limit(1)
			->get();

		if ($query->count() > 0) {
			$number = (int)$query[0]->proforma_code + 1;
		} else {
			$number = '0001';
		}

		$code = str_pad($number, 6, 0, STR_PAD_LEFT);
		return 'INV/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
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

	public function attachment2()
	{
		if (Storage::exists($this->image_tt)) {
			$image = asset(Storage::url($this->image_tt));
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

		if (Storage::exists($this->image_tt)) {
			Storage::delete($this->image_tt);
		}
	}

	public function getTotalRawPlusService()
	{
		$project = ProjectDelivery::find($this->id);

		$grandtotal = 0;
		$total = 0;
		$totalpurchase = 0;
		$countbox = 0;
		$qty = 0;

		$total = $this->subtotal_product;

		if ($this->projectSale->projectDelivery->first()->code == $this->code) {
			$total -= $project->projectSale->project->discount;
			$total += ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost);
		}

		return $total;
	}

	public function getTotalService()
	{
		$project = ProjectDelivery::find($this->id);

		$total = 0;

		if ($this->projectSale->projectDelivery->first()->code == $this->code) {
			$total += $project->projectSale->delivery_cost + $project->projectSale->misc_cost + $project->projectSale->cutting_cost;
		}

		return $total;
	}

	public function getTotalRaw()
	{
		$project = ProjectDelivery::find($this->id);

		$grandtotal = 0;
		$total = 0;
		$totalpurchase = 0;

		foreach ($project->projectDeliveryProduct as $key => $ps) {
			if ($ps->unit == '2' || $ps->unit == '3') {
				foreach ($project->projectSale->projectSaleProduct->where('product_id', $ps->product_id) as $psp) {
					$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

					$price = $psp->best_price;
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$countbox = $psp->qty;
						$total += $price * $ps->qty;
					} else {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id == 18 && date('Y-m', strtotime($project->project->created_at)) < '2022-06') {
							$countbox = $psp->qty;
							$total += $price * $ps->qty;
						} else {
							$countbox = ceil(round($psp->qty / $m2, 2));
							$total += round($price * $m2, 0) * $ps->qty;
						}
					}
				}
			}

			if ($ps->unit == '1' || $ps->unit == '4') {
				foreach ($project->projectSale->projectSaleProduct->where('product_id', $ps->product_id) as $psp) {
					$total += $psp->best_price * $ps->qty;
				}
			}
		}

		$discount = 0;

		if ($project->id == ProjectDelivery::where('project_id', $project->project_id)->first()->id) {
			$discount = $project->projectSale->project->discount;
		}

		return $total - $discount;
	}

	public function getTotal()
	{
		$project = ProjectDelivery::find($this->id);

		// $date = $project->received_date ? $project->received_date : date('Y-m-d');
		// $branch = $project->projectSale->sales->branch;

		$grandtotal = 0;
		$total = $this->subtotal_product;
		$subtotal_service = $this->getServiceCost();

		$totalpurchase = 0;
		$breakToleranceNominal = $this->break_tolerance ? ($this->break_tolerance / 100) * $this->getTotalRaw() : 0;
		$totalQtySampleDeduction =  $this->getSampleDeduction()['qtyDeduction'] > 0 ? $this->getSampleDeduction()['qtyDeduction'] : 0;

		if (date('Y-m-d', strtotime($project->projectSale->created_at)) < '2022-04-01') {
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		} else {
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}

		foreach ($project->projectDeliveryProduct as $key => $ps) {
			$totalpurchase += $ps->qty * str_replace(',', '.', str_replace('.', '', $ps->purchasePrice()));
		}

		// sudah dipotong diskon di function gettotalraw

		// if($project->project->discount){
		// 	if(ProjectDelivery::where('project_sale_id',$project->projectSale->id)->first()->id == $project->id){
		// 		$total -= $project->project->discount;
		// 	}
		// }

	

		$serviceCost = $this->projectSale->ppn_cost == '1' ? ($subtotal_service + ($subtotal_service * $persenppn)) : $subtotal_service;

		$grandtotal = $project->project->ppn == '1' ? ($total + ($persenppn * $total)) : ($total);

		$arr = [
			'totalBreakTolerance'	  => $breakToleranceNominal,
			'totalQtySampleDeduction' => $totalQtySampleDeduction,
			'totaldelivery'   	  	  => $grandtotal,
			'totalpurchase' 	  	  => $totalpurchase,
			'totalServiceCost'    	  => $serviceCost,
		];

		return $arr;
	}

	public function getBalance()
	{
		$project = ProjectDelivery::find($this->id);

		if (date('Y-m-d', strtotime($project->projectSale->created_at)) < '2022-04-01') {
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		} else {
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}

		$grandtotal = 0;
		$total = 0;
		$paid = 0;
		$qtysent = 0;
		$qtytotal = 0;

		foreach ($project->projectDeliveryProduct as $key => $ps) {
			if ($ps->unit == '2' || $ps->unit == '3') {
				foreach ($project->projectSale->projectSaleProduct->where('product_id', $ps->product_id) as $psp) {
					$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

					$price = $psp->best_price;
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$countbox = $psp->qty;
						$total += $price * $ps->qty;
					} else {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id == 18 && date('Y-m', strtotime($project->project->created_at)) < '2022-06') {
							$countbox = $psp->qty;
							$total += $price * $ps->qty;
						} else {
							$countbox = ceil(round($psp->qty / $m2, 2));
							$total += round($price * $m2, 0) * $ps->qty;
						}
					}
					$qtysent += $ps->qty;
					$qtytotal += $countbox;
				}
			}

			if ($ps->unit == '1' || $ps->unit == '4') {
				foreach ($project->projectSale->projectSaleProduct->where('product_id', $ps->product_id) as $psp) {
					$total += $psp->best_price * $ps->qty;
					$qtysent += $ps->qty;
					$qtytotal += $psp->qty;
				}
			}
		}

		foreach ($project->projectPay as $pp) {
			$paid += $pp->nominal;
		}

		$grandtotal = $project->project->ppn == '1' ? ($total + ($qtysent / $qtytotal * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost))) + ($persenppn * ($total + ($qtysent / $qtytotal * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost)))) : ($total + ($qtysent / $qtytotal * ($project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost)));

		$grandtotal = $grandtotal - $paid;

		return number_format($grandtotal, 0, ',', '.');
	}

	public function getServiceCost()
	{
		$project = ProjectDelivery::find($this->id);

		if ($this->projectSale->projectDelivery->first()->code == $this->code) {
			$grandtotal = $project->projectSale->delivery_cost + $project->projectSale->cutting_cost + $project->projectSale->misc_cost;
		} else {
			$grandtotal = 0;
		}

		return $grandtotal;
	}

	public function getTotalMiddleman()
	{
		$project = ProjectDelivery::find($this->id);

		$total = 0;

		if (count($project->projectSale->projectDelivery) == 1) {
			if ($project->projectSale->mid_type == '1') {
				$total = ($project->projectSale->mid_fee * str_replace(',', '.', str_replace('.', '', $project->projectSale->getTotalRaw()))) / 100;
			} elseif ($project->projectSale->mid_type == '2') {
				$total = str_replace(',', '.', str_replace('.', '', $project->projectSale->getTotalMiddleman()));
			} elseif ($project->projectSale->mid_type == '3') {
				$total = str_replace(',', '.', str_replace('.', '', $project->projectSale->getTotalMiddleman()));
			}

			$total -= $project->projectSale->project->discount;
		}

		return number_format($total, 0, ',', '.');
	}



	public function haveSameProductInDeliveries()
	{
		$commonProductIds = $this->projectDeliveryProduct->pluck('product_id')->toArray();

		foreach ($this->projectSale->projectDelivery as $pd) {
			$productIds = $pd->projectDeliveryProduct->pluck('product_id')->toArray();

			// Use array_intersect to find common product IDs
			$commonProductIds = array_intersect($commonProductIds, $productIds);

			// If at any point the commonProductIds array is empty, there are no common products
			if (empty($commonProductIds)) {
				return false;
			}
		}

		return !empty($commonProductIds);
	}

	public function checkDiffDeliveriesForSameProduct()
	{
		$isSimilar = false;
		// CEK APAKAH PRODUK YANG DI RETUR TERDAPAT DI PENGIRIMAN TSB
		foreach ($this->projectDeliveryProduct as $pdp) {
			foreach ($this->project->projectSaleReturn as $psr) {
				foreach ($psr->projectSaleReturnProduct as $psp) {
					if ($pdp->product_id ==  $psp->product_id) {
						$isSimilar = true;
					}
				}
			}
		}

		return $isSimilar;
	}

	public function getTotalReturn()
	{
		$total = 0;
		$cek = ProjectSale::where('code', $this->projectSale->code)->get();
		$get_same_product_diff_delivery = $this->checkDiffDeliveriesForSameProduct();

		if ($cek) {
			// JIKA PRODUK ADA DI DELIVERY TSB MAKA KURANGI TOTAL TAGIHAN DENGAN TOTAL NOMINAL RETUR
			if ($get_same_product_diff_delivery) {
				foreach ($cek as $row) {
					if ($row->id == $this->projectSale->id) {
						foreach ($row->projectSaleReturn as $rowreturn) {
							$total += $rowreturn->getTotal();
						}
					}
				}
			}
		}

		return $total;
	}

	public function updateGrandtotal()
	{
		$breakToleranceNominal = $this->break_tolerance ? ($this->break_tolerance / 100) * $this->getTotalRaw() : 0;
		$totalSampleDeduction = max($this->getSampleDeduction()['totalDeduction'], 0);
		$subtotal_product = $this->getTotalRaw() - ($breakToleranceNominal + $totalSampleDeduction);


		if (date('Y-m-d', strtotime($this->projectSale->created_at)) < '2022-04-01') {
			$persenppn = 0.1;
		} else {
			$persenppn = 0.11;
		}

		$tax_product = $this->project->ppn == '1' ? ($subtotal_product * $persenppn) : 0;

		$grandtotal_product = $subtotal_product + $tax_product;

		$subtotal_service = $this->getServiceCost();

		$tax_service = $this->projectSale->ppn_cost == '1' ? ($subtotal_service * $persenppn) : 0;

		$grandtotal_service = $subtotal_service + $tax_service;

		ProjectDelivery::find($this->id)->update([
			'subtotal_product' 		=> round($subtotal_product, 2),
			'tax_product'			=> round($tax_product, 2),
			'grandtotal_product'	=> round($grandtotal_product, 2),
			'subtotal_service'		=> round($subtotal_service, 2),
			'tax_service'			=> round($tax_service, 2),
			'grandtotal_service'	=> round($grandtotal_service, 2)
		]);
	}

	public function isFirstDelivery()
	{
		$status = false;

		if (count($this->projectSale->projectDelivery) > 0) {
			if ($this->id == $this->projectSale->projectDelivery()->first()->id) {
				if ($this->grandtotal_service) {
					$status = true;
				}
			}
		}

		return $status;
	}

	public function journal()
	{
		$isExist = false;

		$cb = CashBank::where('lookable_type', 'project_deliveries')->where('lookable_id', $this->id)->first();
		if ($cb) {
			foreach ($cb->cashBankDetail as $rowcb) {
				// JIKA JURNAL MERUPAKAN PIUTANG/ AR
				if ($rowcb->coa_id == 27) {
					$isExist = true;
				}
			}
		}


		return $isExist;
	}

	public function getSampleDeduction()
	{
		$qty_dedution = 0;
		$total_deduction = 0;
		foreach ($this->projectDeliveryProduct as $product) {
			$qty_dedution += $product->qty_deduction;

			if ($product->unit == '2' || $product->unit == '3') {
				foreach ($this->projectSale->projectSaleProduct->where('product_id', $product->product_id) as $psp) {

					$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;
					$price = $psp->best_price;

					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$total_deduction += $price * $product->qty_deduction;
					} else {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id == 18 && date('Y-m', strtotime($this->project->created_at)) < '2022-06') {
							$total_deduction += $price * $product->qty_deduction;
						} else {
							$total_deduction += round($price * $m2, 0)  * $product->qty_deduction;
						}
					}
				}
			} else {
				foreach ($this->projectSale->projectSaleProduct->where('product_id', $product->product_id) as $psp) {
					$price = $psp->best_price;
					$total_deduction += $price * $product->qty_deduction;
				}
			}
		}


		return [
			'qtyDeduction' 	 => $qty_dedution,
			'totalDeduction' => $total_deduction,
		];
	}
}
