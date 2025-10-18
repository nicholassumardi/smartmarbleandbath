<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProjectPurchase extends Model
{

	use HasFactory;

	protected $table      = 'project_purchases';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'user_id',
		'project_id',
		'project_sale_id',
		'ppn',
		'code',
		'note',
		'fee_pta',
		'percent_fee_pta',
		'supplier_id',
		'production_lead_time',
		'estimated_delivery',
		'estimated_arrival',
		'factory_name',
		'customer_id',
		'sales_id',
		'on_behalf',
		'delivery_address',
		'country_id',
		'city_id',
		'courier_method',
		'pic',
		'pic_no',
		'payment_method',
		'payment_due_date',
		'price',
		'currency_id',
		'currency_rate',
		'brand_on_box',
		'sni',
		'is_wip',
		'has_memo_item',
		'memo_address_item',
		'memo_up',
		'checked_by',
		'approved_by',
		'subtotal',
		'tax',
		'grandtotal',
		'letter_head',
	];

	public function ppn()
	{
		switch ($this->ppn) {
			case '1':
				$ppn = 'Yes';
				break;
			case '0':
				$ppn = 'No';
				break;
			default:
				$ppn = 'Invalid';
				break;
		}

		return $ppn;
	}

	public function has_memo()
	{
		switch ($this->has_memo_item) {
			case '1':
				$has_memo = 'Yes';
				break;
			case '0':
				$has_memo = 'No';
				break;
			default:
				$has_memo = 'Invalid';
				break;
		}

		return $has_memo;
	}

	public function fee_pta()
	{
		switch ($this->fee_pta) {
			case '1':
				$fee = 'Yes';
				break;
			case '0':
				$fee = 'No';
				break;
			default:
				$fee = 'Invalid';
				break;
		}

		return $fee;
	}

	public function courier()
	{
		switch ($this->courier_method) {
			case '1':
				$method = 'FCL';
				break;
			case '2':
				$method = 'LCL';
				break;
			default:
				$method = 'Invalid';
				break;
		}

		return $method;
	}

	public function price()
	{
		switch ($this->price) {
			case '1':
				$price = 'FOB';
				break;
			case '2':
				$price = 'EXW';
				break;
			case '3':
				$price = 'Franco';
				break;
			case '4':
				$price = 'CIF';
				break;
			default:
				$price = 'Invalid';
				break;
		}

		return $price;
	}

	public function is_wip()
	{
		switch ($this->is_wip) {
			case '0':
				$wip = 'Normal PO';
				break;
			case '1':
				$wip = 'WIP PO';
				break;
			default:
				$wip = 'Invalid';
				break;
		}

		return $from_stock;
	}

	public function checked()
	{
		return $this->belongsTo('App\Models\User', 'checked_by', 'id');
	}

	public function approved()
	{
		return $this->belongsTo('App\Models\User', 'approved_by', 'id');
	}

	public function currency()
	{
		return $this->belongsTo('App\Models\Currency', 'currency_id', 'id');
	}

	public function supplier()
	{
		return $this->belongsTo('App\Models\Supplier', 'supplier_id', 'id');
	}

	public function customer()
	{
		return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
	}

	public function sales()
	{
		return $this->belongsTo('App\Models\User', 'sales_id', 'id');
	}

	public function country()
	{
		return $this->belongsTo('App\Models\Country', 'country_id', 'id');
	}

	public function city()
	{
		return $this->belongsTo('App\Models\City', 'city_id', 'id');
	}

	public function projectSale()
	{
		return $this->belongsTo('App\Models\ProjectSale', 'project_sale_id', 'id');
	}

	public function projectProforma()
	{
		return $this->hasMany('App\Models\ProjectProforma');
	}

	public function projectShipment()
	{
		return $this->hasMany('App\Models\ProjectShipment');
	}

	public function purchaseCost()
	{
		return $this->hasOne('App\Models\PurchaseCost');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function project()
	{
		return $this->belongsTo('App\Models\Project', 'project_id', 'id');
	}

	public static function generateCode()
	{
		$query = ProjectPurchase::selectRaw("RIGHT(code, 6) as code")
			->orderByRaw('RIGHT(code, 6) DESC')
			->limit(1)
			->get();

		if ($query->count() > 0) {
			$number = (int)$query[0]->code + 1;
		} else {
			$number = '000001';
		}

		$code = str_pad($number, 6, 0, STR_PAD_LEFT);
		return 'PO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
	}

	public function projectPurchaseProduct()
	{
		return $this->hasMany('App\Models\ProjectPurchaseProduct');
	}

	public function projectPurchaseSplit()
	{
		return $this->hasMany('App\Models\ProjectPurchaseSplit');
	}

	public function projectWarehouse()
	{
		return $this->hasMany('App\Models\ProjectWarehouse');
	}

	public function transfer()
	{
		return $this->hasMany('App\Models\Transfer', 'project_purchase_id', 'id');
	}

	public function projectPurchaseBill()
	{
		return $this->hasMany('App\Models\ProjectPurchaseBill');
	}

	public function projectPurchaseProduction()
	{
		return $this->hasMany('App\Models\ProjectProduction');
	}

	public function projectPurchaseReturn()
	{
		return $this->hasMany('App\Models\ProjectPurchaseReturn');
	}

	public function totalAfterReturn()
	{
		$total = str_replace(',', '.', str_replace('.', '', $this->getTotal()));

		foreach ($this->projectPurchaseReturn as $row) {
			$total -= $row->getTotal();
		}

		return $total;
	}

	public function purchaseNote()
	{
		$pn = ProjectNote::where('notable_type', 'project_purchases')->where('notable_id', $this->id)->get();

		return $pn;
	}

	public function projectPurchasePayment()
	{
		return $this->hasMany('App\Models\ProjectPayment');
	}

	public function getTotal()
	{
		$project = ProjectPurchase::find($this->id);

		$total = 0;

		foreach ($project->projectPurchaseProduct as $key => $pp) {

			if ($project->currency_id !== '5') {
				if ($pp->unit == '2' || $pp->unit == '3') {
					$m2 = (($pp->product->type->length * $pp->product->type->width) / 10000) * $pp->product->carton_pcs;
					if ($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18) {
						$total += $pp->price * $project->currency_rate * $pp->qty;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18) {
							$total += $pp->price * $project->currency_rate * $pp->qty;
						} else {
							$total +=round( $pp->price * $project->currency_rate * $pp->qty * $m2,0);
						}
					}
				}

				if ($pp->unit == '1' || $pp->unit == '4') {
					$total += $pp->price * $project->currency_rate * $pp->qty;
				}
			} else {
				if ($pp->unit == '2' || $pp->unit == '3') {
					$m2 = (($pp->product->type->length * $pp->product->type->width) / 10000) * $pp->product->carton_pcs;
					if ($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18) {
						$total += $pp->price * $pp->qty;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18) {
							$total += $pp->price * $pp->qty;
						} else {
							$total += round($pp->price * $pp->qty * $m2, 0);
						}
					}
				}

				if ($pp->unit == '1' || $pp->unit == '4') {
					$total += $pp->price * $pp->qty;
				}
			}
		}

		return number_format($total, 0, ',', '.');
	}

	public function getReceived()
	{
		$total = 0;

		foreach ($this->projectWarehouse as $pw) {
			$total += $pw->grandtotal;
		}

		return number_format($total, 2, ',', '.');
	}

	public function getPaid()
	{
		$project = ProjectPurchase::find($this->id);

		$totalpaid = 0;

		foreach ($project->projectPurchasePayment as $ppp) {
			$totalpaid += $ppp->nominal;
		}

		return number_format($totalpaid, 2, ',', '.');
	}

	public function getBalance()
	{

		$totalsend = 0;

		foreach ($this->projectWarehouse as $row) {
			$totalsend += $row->grandtotal;
		}

		foreach (PurchaseRequest::where('link_type', 'project_purchases')->where('link_id', $this->id)->get() as $row) {
			foreach ($row->purchaseRequestPayment as $rowpay) {
				$totalsend -= $rowpay->nominal;
			}
		}

		return $totalsend;
	}

	public function projectWarehouseBill()
	{
		$count = 0;

		foreach ($this->projectWarehouse as $pw) {
			$count += PurchaseRequest::where('link_type', 'project_purchases')->where('link_id', $this->id)->where('project_warehouse_id', $pw->id)->count();
		}

		return $count;
	}

	public function updateGrandtotal()
	{
		$subtotal = str_replace(',', '.', str_replace('.', '', $this->getTotal()));

		$persenppn = 0;

		if ($this->ppn == '1') {
			if (date('Y-m-d', strtotime($this->created_at)) < '2022-04-01') {
				$persenppn = 0.1;
			} else {
				$persenppn = 0.11;
			}
		}

		$tax = $this->ppn == '1' ? ($subtotal * $persenppn) : 0;

		$grandtotal = $subtotal + $tax;

		ProjectPurchase::find($this->id)->update([
			'subtotal' 		=> round($subtotal, 2),
			'tax'			=> round($tax, 2),
			'grandtotal'	=> round($grandtotal, 2)
		]);
	}

	public function getTotalReceive()
	{
		$totalreceive = 0;

		foreach ($this->projectWarehouse as $row) {
			$totalreceive += $row->grandtotal;
		}

		return $totalreceive;
	}

	public function getBalanceReceiveWithPurchase()
	{
		$qtypo = 0;
		$qtywr = 0;

		foreach ($this->projectPurchaseProduct as $row) {
			$qtypo += $row->qty;
		}

		foreach ($this->projectWarehouse as $row) {
			foreach ($row->projectWarehouseProduct as $pwp) {
				$qtywr += $pwp->qty;
			}
		}

		$balance = $qtypo - $qtywr;

		if ($balance > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function countTaxDocument()
	{
		$data = ProjectTaxDocument::where('lookable_type', 'project_purchases')->where('lookable_id', $this->id)->get();

		$count = 0;

		if ($data) {
			$count = count($data);
		}

		return $count;
	}
	
	function dateDiffInDays($date1, $date2)
	{
		$diff = strtotime($date2) - strtotime($date1);

		return abs(round($diff / 86400));
	}

	public function getBalanceAP($filter)
	{
		$total30 = 0;
		$total60 = 0;
		$total90 = 0;
		$totalover = 0;
		$totalreturn = 0;
		$totalpay = 0;
		$totaltransfer = 0;
		$totalreceived = 0;
		$totalcb = 0;
		$totalpc = 0;
		$date = date('Y-m-t', strtotime($filter));

		foreach ($this->projectPurchaseReturn()->whereRaw("date <= '$date'")->get() as $row) {
			$totalreturn += round($row->getTotal());
		}

		foreach ($this->projectPurchasePayment()->whereRaw("date <= '$date'")->get() as $row) {
			$totalpay += $row->nominal;
		}

		// foreach ($this->transfer()->where('status', '1')->orWhere('status', '3')->whereRaw("date <= '$date'")->get() as $row) {
		// 	$totaltransfer += $row->getTotal();
		// }

		foreach ($this->projectWarehouse()->whereNotNull('date_receive')->whereRaw("date_receive <= '$date'")->get() as $row) {
			$totalreceived += $row->grandtotal;
			$diffdays = $this->dateDiffInDays($row->date_receive, $date);
			if ($diffdays <= 30) {
				$total30 += $row->grandtotal;
			} elseif ($diffdays <= 60) {
				$total60 += $row->grandtotal;
			} elseif ($diffdays <= 90) {
				$total90 += $row->grandtotal;
			} elseif ($diffdays > 90) {
				$totalover += $row->grandtotal;
			}
		}

		$cb = CashBank::where('lookable_type', 'project_purchases')->where('lookable_id', $this->id)->whereRaw("date <= '$date'")->get();

		if (count($cb) > 0) {
			foreach ($cb as $rowcb) {
				foreach ($rowcb->cashBankDetail()->where('coa_id', 332)->get() as $cbcb) {
					if ($cbcb->type == '1') {
						$totalcb += $cbcb->nominal;
					}
				}
			}
		}

		$pc = PurchaseCost::where('project_purchase_id', $this->id)->first();
		if ($pc) {
			$totalpc += $pc->totalCost();
		}

		$balance = $totalreturn + $totalpay + $totaltransfer + $totalcb + $totalpc;

		if ($totalover > 0) {
			if ($balance > 0) {
				if ($totalover - $balance < 0) {
					$totalover = 0;
				} else {
					$totalover = $totalover - $balance;
				}
				$balance -= $totalover;
			} 
		}

		if ($total90 > 0) {
			if ($balance > 0) {
				if ($total90 - $balance < 0) {
					$total90 = 0;
				} else {
					$total90 = $total90 - $balance;
				}
				$balance -= $total90;
			} 
		}

		if ($total60 > 0) {
			if ($balance > 0) {
				if ($total60 - $balance < 0) {
					$total60 = 0;
				} else {
					$total60 = $total60 - $balance;
				}
				$balance -= $total60;
			} 
		}

		if ($total30 > 0) {
			if ($balance > 0) {
				if ($total30 - $balance < 0) {
					$total30 = 0;
				} else {
					$total30 = $total30 - $balance;
				}
				$balance -= $total30;
			} 
		}


		$total = $totalreceived - $totalreturn - $totalpay - $totaltransfer - $totalcb - $totalpc;

		$result = [
			'total'		=> round(($total)),
			'total30'	=> round($total30, 0),
			'total60'	=> round($total60, 0),
			'total90'	=> round($total90, 0),
			'totalover'	=> round($totalover, 0)
		];

		return $result;
	}

	public function productAlreadyExist($product_id) {
		$qty_left = 0;
		
		$purchaseProducts = 0;
		$warehouseProducts = 0;

		$purchaseProducts = ProjectPurchaseProduct::where('project_purchase_id', $this->id)->where('product_id', $product_id)->sum('qty');	

		$warehouseProducts = ProjectWarehouseProduct::whereHas('projectWarehouse', function ($query){
			$query->where('project_purchase_id', $this->id);
		})->where('product_id', $product_id)
		->sum('qty');
		
		$qty_left = $purchaseProducts - $warehouseProducts;
	
	
		return $qty_left;
	}

	public function isQuantityExceeded($product_id, $qty){
		$isExceeded = false;

		$purchaseProducts = ProjectPurchaseProduct::where('project_purchase_id', $this->id)->where('product_id', $product_id)->sum('qty');	

		$warehouseProducts = ProjectWarehouseProduct::whereHas('projectWarehouse', function ($query){
			$query->where('project_purchase_id', $this->id);
		})->where('product_id', $product_id)
		->sum('qty');

		$latestInQty = $qty;

		$remainingQty = $purchaseProducts - ($warehouseProducts + $latestInQty);

		if($remainingQty >= 0){
			$isExceeded = true;
		}

		return [
			'isExceeded'   => $isExceeded,
			'productName'  => Product::where('id', $product_id)->first()->name(),
			'remainingQty' => $remainingQty
		];
	}
	
}
