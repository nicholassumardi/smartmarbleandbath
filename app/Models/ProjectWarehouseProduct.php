<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectWarehouseProduct extends Model
{

	use HasFactory;

	protected $table      = 'project_warehouse_products';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'project_warehouse_id',
		'product_id',
		'qty',
		'unit',
		'qty_broken',
		'unit_broken'
	];

	public function projectWarehouse()
	{
		return $this->belongsTo('App\Models\ProjectWarehouse');
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

	public function qty()
	{
		$qty = 0;

		if ($this->unit == '2' || $this->unit == '3') {

			$m2 = (($this->product->type->length * $this->product->type->width) / 10000) * $this->product->carton_pcs;

			if ($m2 < 1.1 && $this->product->type->category->parent()->id !== 18) {
				$qty = $this->qty;
			} else {
				if ($m2 < 1.1 && date('Y-m', strtotime($this->projectWarehouse->created_at)) < '2022-06' && $this->product->type->category->parent()->id !== 18) {
					$qty = $this->qty;
				} else {
					$qty = $this->qty * $m2;
				}
			}
		}

		if ($this->unit == '1' || $this->unit == '4') {
			$qty = $this->qty;
		}

		return round($qty, 2);
	}


	public function unit_broken()
	{
		switch ($this->unit_broken) {
			case '1':
				$unit = 'Pcs';
				break;
			case '2':
				$unit = 'Box';
				break;
			case '3':
				$unit = 'Meter';
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

		$cogs = ProductCogs::where('branch', $this->projectWarehouse->projectPurchase->sales->branch)->where('product_id', $this->product_id)->where('date', '<=', explode(' ', $this->projectWarehouse->date_receive)[0])->where('type', 'WR')->orderByDesc('id')->first();

		if ($cogs) {
			$price = $cogs->price_in;
		}

		return number_format($price, 2, ',', '.');
	}

	public function purchasePricePPn()
	{
		$price = 0;

		$project = ProjectWarehouse::find($this->projectWarehouse->id);

		if ($this->unit == '2' || $this->unit == '3') {
			$findDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id', $this->product_id)->where('qty', $this->qty)->first();

			$findNonDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id', $this->product_id)->first();

			$psp = $findDuplicateItems ? $findDuplicateItems : $findNonDuplicateItems;

			$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

			if ($project->projectPurchase->currency_id !== '5') {
				if ($project->projectPurchase->ppn == '1') {
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$price = $psp->price * $project->projectPurchase->currency_rate;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$price = $psp->price * $project->projectPurchase->currency_rate;
						} else {
							$price = ($psp->price * $project->projectPurchase->currency_rate);
						}
					}
				} else {
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$price = $psp->price * $project->projectPurchase->currency_rate;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$price = $psp->price * $project->projectPurchase->currency_rate;
						} else {
							$price = $psp->price * $project->projectPurchase->currency_rate;
						}
					}
				}
			} else {
				if ($project->projectPurchase->ppn == '1') {
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$price = $psp->price;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$price = $psp->price;
						} else {
							$price = ($psp->price);
						}
					}
				} else {
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$price = $psp->price;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$price = $psp->price;
						} else {
							$price = $psp->price;
						}
					}
				}
			}
		}

		if ($this->unit == '1' || $this->unit == '4') {

			$findDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id', $this->product_id)->where('qty', $this->qty)->first();

			$findNonDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id', $this->product_id)->first();

			$psp = $findDuplicateItems ? $findDuplicateItems : $findNonDuplicateItems;

			if ($project->projectPurchase->currency_id !== '5') {
				if ($project->projectPurchase->ppn == '1') {
					$price = $psp->price * $project->projectPurchase->currency_rate;
				} else {
					$price = $psp->price * $project->projectPurchase->currency_rate;
				}
			} else {
				if ($project->projectPurchase->ppn == '1') {
					$price = $psp->price;
				} else {
					$price = $psp->price;
				}
			}
		}

		return number_format($price, 2, ',', '.');
	}

	public function purchaseReal()
	{
		$price = 0;

		$project = ProjectWarehouse::find($this->projectWarehouse->id);

		if (date('Y-m-d', strtotime($project->projectPurchase->created_at)) < '2022-04-01') {
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		} else {
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}

		if ($this->unit == '2' || $this->unit == '3') {
			// Mencari item di dalam PO dengan nama sama tetapi dengan qty dan harga berbeda
			$findDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id', $this->product_id)->where('qty', $this->qty)->first();

			$findNonDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id', $this->product_id)->first();

			$psp = $findDuplicateItems ? $findDuplicateItems : $findNonDuplicateItems;
			if($psp == null){
				dd( $this->product_id);
			}
			$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;

			if ($project->projectPurchase->currency_id !== '5') {
				if ($project->projectPurchase->ppn == '1') {
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$price = $psp->price * $project->projectPurchase->currency_rate / $ppnpembagi;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$price = $psp->price * $project->projectPurchase->currency_rate / $ppnpembagi;
						} else {
							$price = ($m2 * $psp->price * $project->projectPurchase->currency_rate) / $ppnpembagi;
						}
					}
				} else {
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$price = $psp->price * $project->projectPurchase->currency_rate;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$price = $psp->price * $project->projectPurchase->currency_rate;
						} else {
							$price = $m2 * $psp->price * $project->projectPurchase->currency_rate;
						}
					}
				}
			} else {
				if ($project->projectPurchase->ppn == '1') {
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$price = $psp->price / $ppnpembagi;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$price = $psp->price / $ppnpembagi;
						} else {
							$price = ($m2 * $psp->price) / $ppnpembagi;
						}
					}
				} else {
					if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
						$price = $psp->price;
					} else {
						if ($m2 < 1.1 && date('Y-m', strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
							$price = $psp->price;
						} else {
							$price = $m2 * $psp->price;
						}
					}
				}
			}
		}

		if ($this->unit == '1' || $this->unit == '4') {

			$findDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id', $this->product_id)->where('qty', $this->qty)->first();

			$findNonDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id', $this->product_id)->first();

			$psp = $findDuplicateItems ? $findDuplicateItems : $findNonDuplicateItems;

			if ($project->projectPurchase->currency_id !== '5') {
				if ($project->projectPurchase->ppn == '1') {
					$price = $psp->price * $project->projectPurchase->currency_rate / $ppnpembagi;
				} else {
					$price = $psp->price * $project->projectPurchase->currency_rate;
				}
			} else {
				if ($project->projectPurchase->ppn == '1') {
					$price = $psp->price / $ppnpembagi;
				} else {
					$price = $psp->price;
				}
			}
		}

		return number_format($price, 2, ',', '.');
	}

	public function purchasePriceNow($date)
	{
		$total = 0;
		$qty = 0;

		$branch = $this->projectWarehouse->projectPurchase->sales->branch;

		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($date, $branch) {
			$query->whereHas('projectWarehouse', function ($query) use ($date) {
				$query->whereDate('date_receive', '<=', $date);
			})->whereHas('sales', function ($query) use ($branch) {
				$query->where('branch', $branch);
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
							$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
							} else {
								$total += ($m2 * $psp->price * $psp->qty * $psp->projectPurchase->currency_rate) / $ppnpembagi;
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
							$total = $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
							} else {
								$total += $m2 * $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
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
						$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
					} else {
						$total += $psp->price * $psp->qty / $ppnpembagi;
					}
				} else {
					if ($psp->projectPurchase->currency_id !== '5') {
						$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
					} else {
						$total += $psp->price * $psp->qty;
					}
				}
			}

			$qty += $psp->qty;
		}

		foreach (TransferProduct::whereHas('transfer', function ($query) use ($date, $branch) {
			$query->whereDate('date', '<=', $date)->where('branch', $branch)->where(function ($query) {
				$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
			});
		})->where('product_id', $this->product_id)->get() as $tp) {
			$total += $tp->price;
			$qty += $tp->qty;
		}

		$price = $qty > 0 ? $total / $qty : 0;

		return number_format($price, 2, ',', '.');
	}

	public function purchasePriceBefore($date)
	{

		$total = 0;
		$qty = 0;

		$branch = $this->projectWarehouse->projectPurchase->sales->branch;

		$data = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch, $date) {
			$query->whereHas('projectWarehouse', function ($query) use ($date) {
				$query->whereDate('date_receive', '<', $date);
			})->whereHas('sales', function ($query) use ($branch) {
				$query->where('branch', $branch);
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
							$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
							} else {
								$total += ($m2 * $psp->price * $psp->qty * $psp->projectPurchase->currency_rate) / $ppnpembagi;
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
							$total = $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
							} else {
								$total += $m2 * $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
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
						$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate / $ppnpembagi;
					} else {
						$total += $psp->price * $psp->qty / $ppnpembagi;
					}
				} else {
					if ($psp->projectPurchase->currency_id !== '5') {
						$total += $psp->price * $psp->qty * $psp->projectPurchase->currency_rate;
					} else {
						$total += $psp->price * $psp->qty;
					}
				}
			}

			$qty += $psp->qty;
		}

		foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch, $date) {
			$query->whereDate('date', '<=', $date)->where('branch', $branch)->where(function ($query) {
				$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
			});
		})->where('product_id', $this->product_id)->get() as $tp) {
			$total += $tp->price;
			$qty += $tp->qty;
		}

		$price = $qty > 0 ? $total / $qty : 0;

		return number_format($price, 2, ',', '.');
	}

	public function purchasePriceAllTimeAccumulated()
	{
		$total = 0;

		$branch = $this->projectWarehouse->projectPurchase->sales->branch;

		$findDuplicateItems = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
			$query->whereHas('projectWarehouse', function ($query) {
				$query->where('id', $this->project_warehouse_id)
				->whereHas('projectWarehouseProduct', function ($query) {
					$query->where('product_id', $this->product_id);
				});
			})->whereHas('sales', function ($query) use ($branch) {
				$query->where('branch', $branch);
			});
		})
			->where('qty', $this->qty)
			->where('product_id', $this->product_id)
			->first();


		$findNonDuplicateItems = ProjectPurchaseProduct::whereHas('projectPurchase', function ($query) use ($branch) {
			$query->whereHas('projectWarehouse', function ($query) {
				$query->where('id', $this->project_warehouse_id)
				->whereHas('projectWarehouseProduct', function ($query) {
					$query->where('product_id', $this->product_id);
				});
			})->whereHas('sales', function ($query) use ($branch) {
				$query->where('branch', $branch);
			});
		})
			->where('product_id', $this->product_id)
			->first();


		$psp = $findDuplicateItems ?  $findDuplicateItems : $findNonDuplicateItems;
		// qty di atas untuk where di hapus, dan $psp->qty dihapus juga karena diakhir akan dikali qty dari LPB

		if($psp){
			if (date('Y-m-d', strtotime($psp->projectPurchase->created_at)) < '2022-04-01') {
				$ppnpembagi = 1.1;
			} else {
				$ppnpembagi = 1.11;
			}
	
			if ($psp->unit == '2' || $psp->unit == '3') {
				$m2 = (($psp->product->type->length * $psp->product->type->width) / 10000) * $psp->product->carton_pcs;
	
				if ($psp->projectPurchase->ppn == '1') {
					if ($psp->projectPurchase->currency_id !== '5') {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
							$total += $psp->price * $psp->projectPurchase->currency_rate / $ppnpembagi;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->projectPurchase->currency_rate / $ppnpembagi;
							} else {
								$total += ($m2 * $psp->price * $psp->projectPurchase->currency_rate) / $ppnpembagi;
							}
						}
					} else {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
							$total += $psp->price / $ppnpembagi;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price / $ppnpembagi;
							} else {
								$total += ($m2 * $psp->price) / $ppnpembagi;
							}
						}
					}
				} else {
					if ($psp->projectPurchase->currency_id !== '5') {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
							$total = $psp->price * $psp->projectPurchase->currency_rate;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price * $psp->projectPurchase->currency_rate;
							} else {
								$total += $m2 * $psp->price * $psp->projectPurchase->currency_rate;
							}
						}
					} else {
						if ($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18) {
							$total = $psp->price;
						} else {
							if ($m2 < 1.1 && date('Y-m', strtotime($psp->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18) {
								$total += $psp->price;
							} else {
								$total += $m2 * $psp->price;
							}
						}
					}
				}
			} elseif ($psp->unit == '1' || $psp->unit == '4') {
				if ($psp->projectPurchase->ppn == '1') {
					if ($psp->projectPurchase->currency_id !== '5') {
						$total += $psp->price * $psp->projectPurchase->currency_rate / $ppnpembagi;
					} else {
						$total += $psp->price / $ppnpembagi;
					}
				} else {
					if ($psp->projectPurchase->currency_id !== '5') {
						$total += $psp->price * $psp->projectPurchase->currency_rate;
					} else {
						$total += $psp->price;
					}
				}
			}
	
		}

	

		$finalPrice = $total * $this->qty;

		return $finalPrice;
	}

	private function getProducts()
	{
		$branch = $this->projectWarehouse->projectPurchase->sales->branch;
		$product_id = $this->product_id;
		$data = [];

		$purchaseProducts = ProjectWarehouseProduct::where('product_id', $product_id)->whereHas('projectWarehouse', function ($query) use ($branch) {
			$query->whereHas('projectPurchase', function ($query) use ($branch) {
				$query->whereHas('sales', function ($query) use ($branch) {
					$query->where('branch', $branch);
				});
			});
		})->get();

		$deliveryProducts = ProjectDeliveryProduct::where('product_id', $product_id)->whereHas('projectDelivery', function ($query) use ($branch) {
			$query->whereHas('projectSale', function ($query) use ($branch) {
				$query->whereHas('sales', function ($query) use ($branch) {
					$query->where('branch', $branch);
				});
			});
		})->get();

		$transferProductsIn = TransferProduct::where('product_id', $product_id)->whereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)
			->whereNotNull('for_starting')
			->orWhereNotNull('for_in_transfer');
		})->get();


		$transferProductsOut = TransferProduct::where('product_id', $product_id)->whereHas('transfer', function ($query) use ($branch) {
			$query->where('branch', $branch)
			->whereNotNull('from_warehouse_id')
			->whereNull('to_warehouse_id');
		})->get();

		foreach ($purchaseProducts as $product) {
		// need to fix jika ada LPB 2x di satu barang yang sama, contoh purchase beli 35 tapi di LPB 15 dan 20 nanti tidak ketemu IF nya di accumulated, jadi accumulated lebih baik hanya return harga, nanti untuk total harga di kalikan qty di foreach bawah ini
			$data[] = [
				'product_id'	=> $product->product_id,
				'date'			=> $product->projectWarehouse->date_receive,
				'types'			=> 'IN',
				'qty'			=> $product->qty,
				'price'			=> $product->purchasePriceAllTimeAccumulated(),
			];
		}
	

		foreach ($deliveryProducts as $product) {
			$data[] = [
				'product_id'	=> $product->product_id,
				'date'			=> $product->projectDelivery->received_date,
				'types'			=> 'OUT',
				'qty'			=> $product->qty,
				'price'			=> str_replace(',','.',str_replace('.','', $product->purchasePrice())),
			];
		}

		foreach ($transferProductsIn as $product) {
			$data[] = [
				'product_id'	=> $product->product_id,
				'date'			=> $product->transfer->date,
				'types'			=> 'IN',
				'qty'			=> $product->qty,
				'price'			=> $product->price / $product->qty,
			];
		}

		foreach ($transferProductsOut as $product) {
			$data[] = [
				'product_id'	=> $product->product_id,
				'date'			=> $product->transfer->date,
				'types'			=> 'OUT',
				'qty'			=> $product->qty,
				'price'			=> $product->price / $product->qty,
			];
		}


		$dataProduct = collect($data)->sortBy('date')->values()->all();

		return $dataProduct;
	}

	public function averageBuyPrice()
	{
		$products = $this->getProducts();
		$totalQty = 0;
		$totalQtyDelivered = 0;
		$accumulatedPrice = 0;
		$avgPrice = 0;

		foreach ($products as $product) {

			if ($product['types'] == 'IN') {
				$accumulatedPrice += $product['price'];
				$totalQty += $product['qty'];
				$totalQtyDelivered += $product['qty'];
			} else {
				$totalQtyDelivered -= $product['qty'];
				if ($totalQtyDelivered <= 0 || ($totalQty - $product['qty'] <= 0)) {
					$accumulatedPrice = 0;
					$totalQty = 0;
				}
			}
		}

		$avgPrice = $accumulatedPrice > 0 ? number_format(($accumulatedPrice / $totalQty), 2, ',', '.') : ($this->purchasePrice() > 0 ? $this->purchasePrice() : 0);

		return $avgPrice;
	}

	// public function averageBuyPrice()
	// {
	// 	$branch = $this->projectWarehouse->projectPurchase->sales->branch;
	// 	$qty = 0;
	// 	$qty_product_in = 0;
	// 	$qty_product_out = 0;
	// 	$final_price_in = 0;
	// 	$warehouse_id = array();

	// 	$projectPurchaseProduct = ProjectPurchaseProduct::where('product_id', $this->product_id)->whereHas('projectPurchase', function ($query) use ($branch) {
	// 		$query->whereHas('sales', function ($query) use ($branch) {
	// 			$query->where('branch', $branch);
	// 		})->whereHas('projectWarehouse');
	// 	})->get();

	// 	$buyprice = 0;
	// 	$totalQty = 0;
	// 	$ppnpembagi = 1;

	// 	foreach ($projectPurchaseProduct as $p) {
	// 		$ppnpembagi = 1;

	// 		$m2 = (($p->product->type->length * $p->product->type->width) / 10000) * $p->product->carton_pcs;

	// 		if ($p->projectPurchase->ppn == '1') {
	// 			if (date('Y-m-d', strtotime($p->projectPurchase->created_at)) < '2022-04-01') {
	// 				$ppnpembagi = 1.1;
	// 			} else {
	// 				$ppnpembagi = 1.11;
	// 			}
	// 		}

	// 		if ($p->projectPurchase->currency_id !== '5') {
	// 			if ($p->unit == '2' || $p->unit == '3') {
	// 				if ($m2 < 1.1 && $p->product->type->category->parent()->id !== 18) {
	// 					$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi) * $p->qty;
	// 				} else {
	// 					if ($m2 < 1.1 && date('Y-m', strtotime($p->projectPurchase->created_at)) < '2022-06' && $p->product->type->category->parent()->id == 18) {
	// 						$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi) * $p->qty;
	// 					} else {
	// 						$buyprice += ($m2 * $p->price * $p->projectPurchase->currency_rate / $ppnpembagi) * $p->qty;
	// 					}
	// 				}
	// 			} elseif ($p->unit == '1') {
	// 				$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi) * $p->qty;
	// 			}
	// 		} else {
	// 			if ($p->unit == '2' || $p->unit == '3') {
	// 				if ($m2 < 1.1 && $p->product->type->category->parent()->id !== 18) {
	// 					$buyprice += ($p->price / $ppnpembagi) * $p->qty;
	// 				} else {
	// 					if ($m2 < 1.1 && date('Y-m', strtotime($p->projectPurchase->created_at)) < '2022-06' && $p->product->type->category->parent()->id == 18) {
	// 						$buyprice += ($p->price / $ppnpembagi) * $p->qty * $p->qty;
	// 					} else {
	// 						$buyprice += ($m2 * $p->price / $ppnpembagi) * $p->qty;
	// 					}
	// 				}
	// 			} elseif ($p->unit == '1') {
	// 				$buyprice += ($p->price / $ppnpembagi) * $p->qty;
	// 			}
	// 		}
	// 		$totalQty += $p->qty;
	// 		$qty++;
	// 	}


	// 	$projectDeliveryProduct = ProjectDeliveryProduct::where('product_id', $this->product_id)->whereHas('projectDelivery', function ($query) use ($branch) {
	// 	})->get();

	// 	foreach ($projectDeliveryProduct as $pdp) {
	// 		if ($totalQty == 0) {
	// 		} else {
	// 			$totalQty -= $pdp->qty;
	// 		}
	// 	}



	// 	return number_format($qty > 0 ? $buyprice / $totalQty  :  $buyprice / count($projectPurchaseProduct), 2, ',', '.');
	// }

	// public function averageBuyPrice()
	// {
	// 	$branch = $this->projectWarehouse->projectPurchase->sales->branch;
	// 	$qty = 0;
	// 	$qty_product_in = 0;
	// 	$qty_product_out = 0;
	// 	$final_price_in = 0;
	// 	$warehouse_id = array();

	// 	$ppp = ProjectPurchaseProduct::where('product_id', $this->product_id)->whereHas('projectPurchase', function ($query) use ($branch) {
	// 		$query->whereHas('sales', function ($query) use ($branch) {
	// 			$query->where('branch', $branch);
	// 		})->whereHas('projectWarehouse');
	// 	})->get();

	// 	$buyprice = 0;
	// 	$ppnpembagi = 1;

	// 	foreach ($ppp as $p) {
	// 		$ppnpembagi = 1;

	// 		$m2 = (($p->product->type->length * $p->product->type->width) / 10000) * $p->product->carton_pcs;

	// 		if ($p->projectPurchase->ppn == '1') {
	// 			if (date('Y-m-d', strtotime($p->projectPurchase->created_at)) < '2022-04-01') {
	// 				$ppnpembagi = 1.1;
	// 			} else {
	// 				$ppnpembagi = 1.11;
	// 			}
	// 		}

	// 		if ($p->projectPurchase->currency_id !== '5') {
	// 			if ($p->unit == '2' || $p->unit == '3') {
	// 				if ($m2 < 1.1 && $p->product->type->category->parent()->id !== 18) {
	// 					$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi);
	// 				} else {
	// 					if ($m2 < 1.1 && date('Y-m', strtotime($p->projectPurchase->created_at)) < '2022-06' && $p->product->type->category->parent()->id == 18) {
	// 						$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi);
	// 					} else {
	// 						$buyprice += ($m2 * $p->price * $p->projectPurchase->currency_rate / $ppnpembagi);
	// 					}
	// 				}
	// 			} elseif ($p->unit == '1') {
	// 				$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi);
	// 			}
	// 		} else {
	// 			if ($p->unit == '2' || $p->unit == '3') {
	// 				if ($m2 < 1.1 && $p->product->type->category->parent()->id !== 18) {
	// 					$buyprice += ($p->price / $ppnpembagi);
	// 				} else {
	// 					if ($m2 < 1.1 && date('Y-m', strtotime($p->projectPurchase->created_at)) < '2022-06' && $p->product->type->category->parent()->id == 18) {
	// 						$buyprice += ($p->price / $ppnpembagi);
	// 					} else {
	// 						$buyprice += ($m2 * $p->price / $ppnpembagi);
	// 					}
	// 				}
	// 			} elseif ($p->unit == '1') {
	// 				$buyprice += ($p->price / $ppnpembagi);
	// 			}
	// 		}
	// 		$qty++;
	// 	}

	// 	foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch) {
	// 		$query->where('branch', $branch)->where(function ($query) {
	// 			$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
	// 		});
	// 	})->where('product_id', $this->product_id)->get() as $tp) {
	// 		$m2 = (( $tp->product->type->length * $tp->product->type->width ) / 10000) * $tp->product->carton_pcs;


	// 		if($m2 < 1.1 && $tp->product->type->category->parent()->id !== 18){
	// 			$buyprice += ($tp->price / $tp->qty);
	// 			$final_price_in += ($tp->price / $tp->qty);
	// 		}else{
	// 			if($m2 < 1.1 && date('Y-m',strtotime($tp->created_at)) < '2022-06' && $tp->product->type->category->parent()->id == 18){
	// 				$buyprice += ($tp->price / $tp->qty);
	// 				$final_price_in += ($tp->price / $tp->qty);
	// 			}else{
	// 				$buyprice += ($tp->price / $tp->qty);
	// 				$final_price_in += ($tp->price / $tp->qty);
	// 			}
	// 		}

	// 		$qty_product_in +=  $tp->qty;
	// 		array_push($warehouse_id, $tp->transfer->to_warehouse_id);

	// 		$qty++;
	// 	}

	// 	$dataTransferIN = [
	// 		'product_id' => $this->product_id,
	// 		'price' => $final_price_in,
	// 		'qty' => $qty_product_in
	// 	];

	// 	foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch, $warehouse_id) {
	// 		$query->where('branch', $branch)->where(function ($query) use ($warehouse_id) {
	// 			$query->whereIn('from_warehouse_id', $warehouse_id)->whereNull('to_warehouse_id');
	// 		});
	// 	})->where('product_id', $this->product_id)->get() as $tp) {
	// 		$qty_product_out += $tp->qty;
	// 	}

	// 	if ($qty_product_out >= $dataTransferIN['qty'] && $this->product_id == $dataTransferIN['product_id']) {
	// 		$buyprice -=  $dataTransferIN['price'];
	// 		$qty--;
	// 	}

	// 	return number_format($qty > 1 ? $buyprice / $qty :  $buyprice / count($ppp), 2, ',', '.');
	// }


	public function productAlreadyExist($purchase_id, $product_id)
	{
		$qty_left = 0;

		$purchaseProducts = 0;
		$warehouseProducts = 0;

		$purchaseProducts = ProjectPurchaseProduct::where('project_purchase_id', $purchase_id)->where('product_id', $product_id)->sum('qty');

		$warehouseProducts = ProjectWarehouseProduct::whereHas('projectWarehouse', function ($query) use ($purchase_id) {
			$query->where('project_purchase_id', $purchase_id);
		})->where('product_id', $product_id)
			->sum('qty');

		$qty_left = $purchaseProducts - $warehouseProducts;


		return $qty_left;
	}

	// ! averagebuyprice sistem yang bawah
// 	public function averageBuyPrice()
// 	{
// 		$branch = $this->projectWarehouse->projectPurchase->sales->branch;
// 		$qty = 0;
// 		$warehouse_id = array();

// 		$ppp = ProjectPurchaseProduct::where('product_id', $this->product_id)->whereHas('projectPurchase', function ($query) use ($branch) {
// 			$query->whereHas('sales', function ($query) use ($branch) {
// 				$query->where('branch', $branch);
// 			})->whereHas('projectWarehouse');
// 		})->get();

// 		$buyprice = 0;
// 		$ppnpembagi = 1;

// 		foreach ($ppp as $p) {
// 			$ppnpembagi = 1;

// 			$m2 = (($p->product->type->length * $p->product->type->width) / 10000) * $p->product->carton_pcs;

// 			if ($p->projectPurchase->ppn == '1') {
// 				if (date('Y-m-d', strtotime($p->projectPurchase->created_at)) < '2022-04-01') {
// 					$ppnpembagi = 1.1;
// 				} else {
// 					$ppnpembagi = 1.11;
// 				}
// 			}

// 			if ($p->projectPurchase->currency_id !== '5') {
// 				if ($p->unit == '2' || $p->unit == '3') {
// 					if ($m2 < 1.1 && $p->product->type->category->parent()->id !== 18) {
// 						$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi);
// 					} else {
// 						if ($m2 < 1.1 && date('Y-m', strtotime($p->projectPurchase->created_at)) < '2022-06' && $p->product->type->category->parent()->id == 18) {
// 							$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi);
// 						} else {
// 							$buyprice += ($m2 * $p->price * $p->projectPurchase->currency_rate / $ppnpembagi);
// 						}
// 					}
// 				} elseif ($p->unit == '1') {
// 					$buyprice += ($p->price * $p->projectPurchase->currency_rate / $ppnpembagi);
// 				}
// 			} else {
// 				if ($p->unit == '2' || $p->unit == '3') {
// 					if ($m2 < 1.1 && $p->product->type->category->parent()->id !== 18) {
// 						$buyprice += ($p->price / $ppnpembagi);
// 					} else {
// 						if ($m2 < 1.1 && date('Y-m', strtotime($p->projectPurchase->created_at)) < '2022-06' && $p->product->type->category->parent()->id == 18) {
// 							$buyprice += ($p->price / $ppnpembagi);
// 						} else {
// 							$buyprice += ($m2 * $p->price / $ppnpembagi);
// 						}
// 					}
// 				} elseif ($p->unit == '1') {
// 					$buyprice += ($p->price / $ppnpembagi);
// 				}
// 			}
// 			$qty++;
// 		}

// 		foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch) {
// 			$query->where('branch', $branch)->where(function ($query) {
// 				$query->whereNotNull('for_starting')->orWhereNotNull('for_in_transfer');
// 			});
// 		})->where('product_id', $this->product_id)->get() as $tp) {
// 			$buyprice += $tp->price / $tp->qty;
// 			array_push($warehouse_id, $tp->transfer->to_warehouse_id);
// 			$dataTransferIN[] = [
// 				'product_id' => $this->product_id,
// 				'price' => $tp->price / $tp->qty,
// 				'qty' => $tp->qty,
// 			];
// 			$qty++;
// 		}


// 		foreach (TransferProduct::whereHas('transfer', function ($query) use ($branch, $warehouse_id) {
// 			$query->where('branch', $branch)->where(function ($query) use ($warehouse_id) {
// 				$query->whereNotNull('for_correction')->whereIn('from_warehouse_id', $warehouse_id);
// 			});
// 		})->where('product_id', $this->product_id)->get() as $key => $tp) {

// 			if ($tp->qty >= $dataTransferIN[$key]['qty']) {
// 				$buyprice -=  $dataTransferIN[$key]['price'];
// 				$qty--;
// 			}
// 		}

// 		return number_format($qty > 1 ? $buyprice / $qty :  $buyprice / count($ppp), 2, ',', '.');
// 	}
}
