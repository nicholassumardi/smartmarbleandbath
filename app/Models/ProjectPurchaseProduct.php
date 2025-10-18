<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPurchaseProduct extends Model
{

	use HasFactory;

	protected $table      = 'project_purchase_products';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'project_purchase_id',
		'product_id',
		'qty',
		'unit',
		'price',
		'remark'
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

	public function qty(){
		$qty = 0;
		
		if($this->unit == '2' || $this->unit == '3'){
			
			$m2 = (( $this->product->type->length * $this->product->type->width ) / 10000) * $this->product->carton_pcs;

			if($m2 < 1.1 && $this->product->type->category->parent()->id !== 18){
				$qty = $this->qty;
			}else{
				if($m2 < 1.1 && date('Y-m',strtotime($this->projectPurchase->project->created_at)) < '2022-06' && $this->product->type->category->parent()->id !== 18){
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

	public function product()
	{
		return $this->belongsTo('App\Models\Product');
	}

	public function projectPurchase()
	{
		return $this->belongsTo('App\Models\ProjectPurchase');
	}

	public function sellPrice()
	{
		if ($this->projectPurchase->project_sale_id > 0) {
			$projectsale = ProjectSale::find($this->projectPurchase->project_sale_id);
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

	public function priceBudget()
	{
		$data = BudgetingProject::where('project_id', $this->projectPurchase->project_id)->first();

		$price = 0;

		if ($data) {
			foreach ($data->budgetingProjectProduct()->where('product_id', $this->product_id)->get() as $row) {
				$price = $row->price * $data->help_exchange_rate;
			}
		}

		return $price;
	}

	public function realSellPrice()
	{
		if ($this->projectPurchase->project_sale_id > 0) {
			$projectsale = ProjectSale::find($this->projectPurchase->project_sale_id);
			$dataprice = $projectsale->projectSaleProduct->where('product_id', $this->product_id)->first();
			// 			$m2 = (( $dataprice->product->type->length * $dataprice->product->type->width ) / 10000) * $dataprice->product->carton_pcs;

			// 			$price = $dataprice->best_price ? $dataprice->best_price : $dataprice->recommended_price;

			// 			return $price;

			if ($dataprice) {
				$m2 = (($dataprice->product->type->length * $dataprice->product->type->width) / 10000) * $dataprice->product->carton_pcs;

				$price = $dataprice->best_price ? $dataprice->best_price : $dataprice->recommended_price;

				return $price;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	public function projectSplitProduct()
	{
		$pps = ProjectPurchaseSplit::where('project_purchase_id', $this->projectPurchase->id)->where('product_id', $this->product_id)->get();

		return $pps;
	}

	public function purchaseReal()
	{
		$price = 0;

		if (date('Y-m-d', strtotime($this->projectPurchase->created_at)) < '2022-04-01') {
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		} else {
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}

		if ($this->unit == '2' || $this->unit == '3') {

			$m2 = (($this->product->type->length * $this->product->type->width) / 10000) * $this->product->carton_pcs;

			if ($this->projectPurchase->currency_id !== '5') {
				if ($this->projectPurchase->ppn == '1') {
					if ($m2 < 1.1 && $this->product->type->category->parent()->id !== 18) {
						$price = $this->price * $this->projectPurchase->currency_rate / $ppnpembagi;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectPurchase->created_at)) < '2022-06' && $this->product->type->category->parent()->id == 18) {
							$price = $this->price * $this->projectPurchase->currency_rate / $ppnpembagi;
						} else {
							$price = ($m2 * $this->price * $this->projectPurchase->currency_rate) / $ppnpembagi;
						}
					}
				} else {
					if ($m2 < 1.1 && $this->product->type->category->parent()->id !== 18) {
						$price = $this->price * $this->projectPurchase->currency_rate;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectPurchase->created_at)) < '2022-06' && $this->product->type->category->parent()->id == 18) {
							$price = $this->price * $this->projectPurchase->currency_rate;
						} else {
							$price = $m2 * $this->price * $this->projectPurchase->currency_rate;
						}
					}
				}
			} else {
				if ($this->projectPurchase->ppn == '1') {
					if ($m2 < 1.1 && $this->product->type->category->parent()->id !== 18) {
						$price = $this->price / $ppnpembagi;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectPurchase->created_at)) < '2022-06' && $this->product->type->category->parent()->id == 18) {
							$price = $this->price / $ppnpembagi;
						} else {
							$price = ($m2 * $this->price) / $ppnpembagi;
						}
					}
				} else {
					if ($m2 < 1.1 && $this->product->type->category->parent()->id !== 18) {
						$price = $this->price;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($this->projectPurchase->created_at)) < '2022-06' && $this->product->type->category->parent()->id == 18) {
							$price = $this->price;
						} else {
							$price = $m2 * $this->price;
						}
					}
				}
			}
		}

		if ($this->unit == '1' || $this->unit == '4') {

			if ($this->projectPurchase->currency_id !== '5') {
				if ($this->projectPurchase->ppn == '1') {
					$price = $this->price * $this->projectPurchase->currency_rate / $ppnpembagi;
				} else {
					$price = $this->price * $this->projectPurchase->currency_rate;
				}
			} else {
				if ($this->projectPurchase->ppn == '1') {
					$price = $this->price / $ppnpembagi;
				} else {
					$price = $this->price;
				}
			}
		}

		return number_format($price, 2, ',', '.');
	}
}
