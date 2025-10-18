<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Approval;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Journal;
use App\Models\ProjectLog;
use App\Models\ProjectSale;
use App\Models\ProjectPurchase;
use App\Models\PurchaseRequest;
use App\Models\PurchaseCost;
use App\Models\ProjectPurchaseBill;
use App\Models\ProjectNote;
use App\Models\ProjectShipment;
use App\Models\ProjectShipmentProduct;
use App\Models\ProjectWarehouse;
use App\Models\ProjectWarehouseProduct;
use App\Models\ProjectProforma;
use App\Models\ProjectProduction;
use App\Models\Notification;
use App\Models\Stock;
use App\Models\ProductCogs;
use App\Models\Transfer;
use App\Models\TransferProduct;
use App\Models\ProjectPurchaseProduct;
use App\Models\ProjectPurchaseReturnProduct;
use App\Models\ProjectDeliveryProduct;
use App\Models\ProjectSaleReturnProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryStockController extends Controller {

    public function index(Request $request)
    {
		
        $data = [
            'title'   		=> 'Stock Inventory',
            'content' 		=> 'admin.inventory.stock'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
			'detail',
            'id',
            'product_id',
			'warehouse_id',
            'qty',
            'unit',
			'branch'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Stock::count();
        
        $query_data = Stock::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
						$query->whereHas('product', function($query) use ($search) {
							$query->whereHas('type', function($query) use ($search) {
								$query->where('code', 'like', "%$search%")
									->orWhereHas('category', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									})
									->orWhereHas('surface', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									})
									->orWhereHas('color', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									})
									->orWhereHas('pattern', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									});
							})
								->orWhereHas('brand', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									});
						})
						->orWhereHas('warehouse', function($query) use ($search){
							$query->where('code', 'like', "%$search%")
							->orWhere('name','like', "%$search%");
						});
					});
                }
				
				if($request->branch){
					$query->where('branch',$request->branch);
				}

			
				if($request->hasQty != 1){
					if($request->hasQty != 2){
						$query->where('qty','<=', 0);
					}else{
						$query->where('qty','>=', 0);
					}
				}
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Stock::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
						$query->whereHas('product', function($query) use ($search) {
							$query->whereHas('type', function($query) use ($search) {
								$query->where('code', 'like', "%$search%")
									->orWhereHas('category', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									})
									->orWhereHas('surface', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									})
									->orWhereHas('color', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									})
									->orWhereHas('pattern', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									});
							})
							->orWhereHas('brand', function($query) use ($search) {
										$query->where('name', 'like', "%$search%");
									});
						})
						->orWhereHas('warehouse', function($query) use ($search){
							$query->where('code', 'like', "%$search%")
							->orWhere('name','like', "%$search%");
						});
					});
                }
				
				if($request->branch){
					$query->where('branch',$request->branch);
				}

				if($request->hasQty != 1){
					if($request->hasQty != 2){
						$query->where('qty','<=', 0);
					}else{
						$query->where('qty','>=', 0);
					}
				}
				
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $nomor,
                    $val->product->name(),
                    $val->product->size(),
					isset($val->warehouse->code) ? $val->warehouse->code.' - '.$val->warehouse->name : '',
					$val->qty,
					$val->unit(),
					$val->branch()
                ];

                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }
	
	public function rowDetail(Request $request)
    {
		$data = Stock::find($request->id);
		
        $rowIn   = ProjectWarehouseProduct::where(function($query) use ($data) {
			$query->whereHas('projectWarehouse', function($query) use ($data) {
				$query->where('warehouse_id',$data->warehouse_id)
				->whereHas('projectPurchase',function($query) use ($data){
					$query->whereHas('sales',function($query) use ($data){
						$query->where('branch',$data->branch);
					});
				});
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowOut   = ProjectDeliveryProduct::where(function($query) use ($data) {
			$query->whereHas('projectDelivery', function($query) use ($data) {
				$query->where('warehouse_id',$data->warehouse_id)
				->whereNotNull('received_date')
				->whereHas('projectSale',function($query) use ($data){
					$query->whereHas('sales',function($query) use ($data){
						$query->where('branch',$data->branch);
					});
				});
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowInReturn   = ProjectSaleReturnProduct::where(function($query) use ($data) {
			$query->whereHas('projectSaleReturn', function($query) use ($data) {
				$query->where('warehouse_id',$data->warehouse_id)
				->whereHas('projectSale',function($query) use ($data){
					$query->whereHas('sales',function($query) use ($data){
						$query->where('branch',$data->branch);
					});
				});
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowOutReturn   = ProjectPurchaseReturnProduct::where(function($query) use ($data) {
			$query->whereHas('projectPurchaseReturn', function($query) use ($data) {
				$query->where('warehouse_id',$data->warehouse_id)
				->whereHas('projectPurchase',function($query) use ($data){
					$query->whereHas('sales',function($query) use ($data){
						$query->where('branch',$data->branch);
					});
				});
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowInTransfer   = TransferProduct::where(function($query) use ($data) {
			$query->whereHas('transfer', function($query) use ($data) {
				$query->where('to_warehouse_id',$data->warehouse_id)
				->where('branch',$data->branch);
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowOutTransfer   = TransferProduct::where(function($query) use ($data) {
			$query->whereHas('transfer', function($query) use ($data) {
				$query->where('from_warehouse_id',$data->warehouse_id)
				->where('branch',$data->branch);
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$no = 1;
		
        $string = '<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
								<th style="color:white;"><center>NO</center></th>
								<th style="color:white;"><center>TIPE</center></th>
								<th style="color:white;"><center>DESCRIPTION</center></th>
								<th style="color:white;"><center>BRANCH</center></th>
								<th style="color:white;"><center>QTY</center></th>
								<th style="color:white;"><center>UNIT</center></th>
								<th style="color:white;"><center>PRICE PER UNIT (Before Tax)</center></th>
							</tr>
						 </thead>
						 <tbody>';
		foreach($rowIn as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button>
			  </div>	
			  <td class="align-middle">
				Warehouse Receive '.$row->projectWarehouse->code.' - PO : '.$row->projectWarehouse->projectPurchase->code.' Date '.date("d M Y",strtotime($row->projectWarehouse->date_receive)).'
			  </div>
			  <td class="align-middle">
				'.$row->projectWarehouse->projectPurchase->sales->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
			  <td class="align-middle">
				Rp'.$row->purchasePrice().' / <b>(Rp'.$row->averageBuyPrice().')</b>
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowOut as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button>
			  </div>
			  <td class="align-middle">
				Delivery Order '.$row->projectDelivery->code.' - Project : '.$row->projectDelivery->project->code.' Customer : '.$row->projectDelivery->project->customer->name.' Date '.date("d M Y",strtotime($row->projectDelivery->received_date)).'
			  </div>
			  <td class="align-middle">
				'.$row->projectDelivery->projectSale->sales->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
			  <td class="align-middle">
				Rp'.$row->salePrice().'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowInReturn as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button>
			  </div>
			  <td class="align-middle">
				Sale Return '.$row->projectSaleReturn->code.' - Project : '.($row->projectSaleReturn->project ? $row->projectSaleReturn->project->code : "None").'
			  </div>
			  <td class="align-middle">
				'.$row->projectSaleReturn->projectSale->sales->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
			  <td class="align-middle">
				Rp'.$row->salePrice().'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowOutReturn as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button>
			  </div>
			  <td class="align-middle">
				Purchase Return '.$row->projectPurchaseReturn->code.' - Project : '.($row->projectPurchaseReturn->project ? $row->projectPurchaseReturn->project->code : "None").'
			  </div>
			  <td class="align-middle">
				'.$row->projectPurchaseReturn->projectPurchase->sales->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
			  <td class="align-middle">
				Rp'.$row->purchasePrice().'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowInTransfer as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button>
			  </div>
			  <td class="align-middle">
				Transfer number '.$row->transfer->code.' '.$row->transfer->note.'
			  </div>
			  <td class="align-middle">
				'.$row->transfer->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
			  <td class="align-middle">
				Rp'.number_format($row->price(),0,',','.').'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowOutTransfer as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button>
			  </div>
			  <td class="align-middle">
				Transfer number '.$row->transfer->code.'
			  </div>
			  <td class="align-middle">
				'.$row->transfer->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
			  <td class="align-middle">
				Note : '.($row->transfer->customer_id ? $row->transfer->customer->name.' Address '.$row->transfer->customer_address : '').$row->transfer->note.'
			  </td>
		   </tr>';
		   $no++;
		}
				
		$string .= '
					</tbody>
					  </table>
				</div>
			</div>
				';
		
        return response()->json($string);
    }
	
	public function rowDetailJkt(Request $request)
    {
		$data = Stock::find($request->id);
		
        $rowIn   = ProjectWarehouseProduct::where(function($query) use ($data) {
			$query->whereHas('projectWarehouse', function($query) use ($data) {
				$query->where('warehouse_id',$data->warehouse_id);
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowOut   = ProjectDeliveryProduct::where(function($query) use ($data) {
			$query->whereHas('projectDelivery', function($query) use ($data) {
				$query->where('warehouse_id',$data->warehouse_id)
				->whereNotNull('received_date');
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowInReturn   = ProjectSaleReturnProduct::where(function($query) use ($data) {
			$query->whereHas('projectSaleReturn', function($query) use ($data) {
				$query->where('warehouse_id',$data->warehouse_id);
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowOutReturn   = ProjectPurchaseReturnProduct::where(function($query) use ($data) {
			$query->whereHas('projectPurchaseReturn', function($query) use ($data) {
				$query->where('warehouse_id',$data->warehouse_id);
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowInTransfer   = TransferProduct::where(function($query) use ($data) {
			$query->whereHas('transfer', function($query) use ($data) {
				$query->where('to_warehouse_id',$data->warehouse_id);
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$rowOutTransfer   = TransferProduct::where(function($query) use ($data) {
			$query->whereHas('transfer', function($query) use ($data) {
				$query->where('from_warehouse_id',$data->warehouse_id);
			});
		})
		->where('product_id',$data->product_id)
		->get();
		
		$no = 1;
		
        $string = '<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
								<th style="color:white;"><center>NO</center></th>
								<th style="color:white;"><center>TIPE</center></th>
								<th style="color:white;"><center>DESCRIPTION</center></th>
								<th style="color:white;"><center>BRANCH</center></th>
								<th style="color:white;"><center>QTY</center></th>
								<th style="color:white;"><center>UNIT</center></th>
							</tr>
						 </thead>
						 <tbody>';
		foreach($rowIn as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button>
			  </div>	
			  <td class="align-middle">
				Warehouse Receive '.$row->projectWarehouse->code.' - PO : '.$row->projectWarehouse->projectPurchase->code.'
			  </div>
			  <td class="align-middle">
				'.$row->projectWarehouse->projectPurchase->sales->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowOut as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button>
			  </div>
			  <td class="align-middle">
				Delivery Order '.$row->projectDelivery->code.'
			  </div>
			  <td class="align-middle">
				'.$row->projectDelivery->projectSale->sales->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowInReturn as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button>
			  </div>
			  <td class="align-middle">
				Sale Return '.$row->projectSaleReturn->code.'
			  </div>
			  <td class="align-middle">
				'.$row->projectSaleReturn->projectSale->sales->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowOutReturn as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button>
			  </div>
			  <td class="align-middle">
				Purchase Return '.$row->projectPurchaseReturn->code.'
			  </div>
			  <td class="align-middle">
				'.$row->projectPurchaseReturn->projectPurchase->sales->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowInTransfer as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-add"></i></b> IN</button>
			  </div>
			  <td class="align-middle">
				Transfer number '.$row->transfer->code.' '.$row->transfer->note.'
			  </div>
			  <td class="align-middle">
				'.$row->transfer->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
		   </tr>';
		   $no++;
		}
		
		foreach($rowOutTransfer as $row){
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.$no.'
			  </div>
			  <td class="align-middle">
				<button type="button" class="btn btn-danger btn-labeled btn-labeled-left rounded-round"><b><i class="icon-box-remove"></i></b> OUT</button>
			  </div>
			  <td class="align-middle">
				Transfer number '.$row->transfer->code.'
			  </div>
			  <td class="align-middle">
				'.$row->transfer->branch().'
			  </div>
			  <td class="align-middle">
				'.$row->qty.'
			  </div>
			  <td class="align-middle">
				'.$row->unit().'
			  </td>
		   </tr>';
		   $no++;
		}
				
		$string .= '
					</tbody>
					  </table>
				</div>
			</div>
				';
		
        return response()->json($string);
    }
	
	public function report(Request $request,$branch){

		$rowIn = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use ($branch){
			$query->whereHas('projectPurchase',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		$arrResult = [];
		
		foreach($rowIn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->projectWarehouse->warehouse_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'warehouse_id'		=> $row->projectWarehouse->warehouse_id,
					'warehouse_name'	=> $row->projectWarehouse->warehouse->code.' - '.$row->projectWarehouse->warehouse->name,
					'qty'				=> $row->qty,
				];
			}
		}
		
		$rowOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use ($branch){
			$query->whereNotNull('received_date')
				->whereHas('projectSale',function($query) use ($branch){
					$query->whereHas('sales',function($query) use ($branch){
						$query->where('branch',$branch);
					});
			});
		})->get();
		
		foreach($rowOut as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->projectDelivery->warehouse_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'warehouse_id'		=> $row->projectDelivery->warehouse_id,
					'warehouse_name'	=> $row->projectDelivery->warehouse->code.' - '.$row->projectDelivery->warehouse->name,
					'qty'				=> 0 - $row->qty,
				];
			}
		}
		
		$rowInReturn = ProjectSaleReturnProduct::whereHas('projectSaleReturn',function($query) use ($branch){
			$query->whereHas('projectSale',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowInReturn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->projectSaleReturn->warehouse_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'warehouse_id'		=> $row->projectSaleReturn->warehouse_id,
					'warehouse_name'	=> $row->projectSaleReturn->warehouse->code.' - '.$row->projectSaleReturn->warehouse->name,
					'qty'				=> $row->qty,
				];
			}
		}
		
		$rowOutReturn = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn',function($query) use ($branch){
			$query->whereHas('projectPurchase',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowOutReturn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->projectPurchaseReturn->warehouse_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'warehouse_id'		=> $row->projectPurchaseReturn->warehouse_id,
					'warehouse_name'	=> $row->projectPurchaseReturn->warehouse->code.' - '.$row->projectPurchaseReturn->warehouse->name,
					'qty'				=> 0 - $row->qty,
				];
			}
		}
		
		$rowTransfer = TransferProduct::whereHas('transfer',function($query) use ($branch){
						$query->where('branch',$branch);
					})->get();
		
		foreach($rowTransfer as $row){
			if($row->transfer->from_warehouse_id){
				$ada = false;
			
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->transfer->from_warehouse_id){
							$ada = true;
							$index = $key;
						}
					}
				}
				
				if($ada == true){
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
				}else{
					$arrResult[] = [
						'product_id' 		=> $row->product_id,
						'product_name'		=> $row->product->name(),
						'product_color'		=> $row->product->type->color->name,
						'product_surface'	=> $row->product->type->surface->name,
						'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
						'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
						'product_picture'	=> $row->product->type->image(),
						'warehouse_id'		=> $row->transfer->from_warehouse_id,
						'warehouse_name'	=> $row->transfer->warehouseFrom->code.' - '.$row->transfer->warehouseFrom->name,
						'qty'				=> 0 - $row->qty,
					];
				}
			}
			
			if($row->transfer->to_warehouse_id){
				$ada = false;
			
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->transfer->to_warehouse_id){
							$ada = true;
							$index = $key;
						}
					}
				}
				
				if($ada == true){
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
				}else{
					$arrResult[] = [
						'product_id' 		=> $row->product_id,
						'product_name'		=> $row->product->name(),
						'product_color'		=> $row->product->type->color->name,
						'product_surface'	=> $row->product->type->surface->name,
						'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
						'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
						'product_picture'	=> $row->product->type->image(),
						'warehouse_id'		=> $row->transfer->to_warehouse_id,
						'warehouse_name'	=> $row->transfer->warehouseTo->code.' - '.$row->transfer->warehouseTo->name,
						'qty'				=> 0 + $row->qty,
					];
				}
			}
		}
		
		$array = collect($arrResult)->filter(function ($value, $key) {
						return $value['qty'] > 0 || $value['qty'] < 0;
					})->sortBy('product_name')->toArray();
		
		return view('admin.pdf.inventory.report', [
			'main' 		=> $this->getWarehouse($array),
			'data'		=> $array,
			'branch'	=> $branch
		]);
	}
	
	public function reportInReal(Request $request,$branch){

		$rowIn = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use ($branch){
			$query->whereHas('projectPurchase',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		$arrResult = [];
		
		foreach($rowIn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'qty'				=> $row->qty,
					'qty_in_so'			=> 0,
				];
			}
		}
		
		$rowOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use ($branch){
			$query->whereNotNull('received_date')
				->whereHas('projectSale',function($query) use ($branch){
					$query->whereHas('sales',function($query) use ($branch){
						$query->where('branch',$branch);
					});
			});
		})->get();
		
		foreach($rowOut as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'qty'				=> 0 - $row->qty,
					'qty_in_so'			=> 0,
				];
			}
		}
		
		$rowInReturn = ProjectSaleReturnProduct::whereHas('projectSaleReturn',function($query) use ($branch){
			$query->whereHas('projectSale',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowInReturn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'qty'				=> $row->qty,
					'qty_in_so'			=> 0,
				];
			}
		}
		
		$rowOutReturn = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn',function($query) use ($branch){
			$query->whereHas('projectPurchase',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowOutReturn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'qty'				=> 0 - $row->qty,
					'qty_in_so'			=> 0,
				];
			}
		}
		
		$rowTransfer = TransferProduct::whereHas('transfer',function($query) use ($branch){
						$query->where('branch',$branch);
					})->get();
		
		foreach($rowTransfer as $row){
			if($row->transfer->from_warehouse_id){
				$ada = false;
			
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $row->product_id){
							$ada = true;
							$index = $key;
						}
					}
				}
				
				if($ada == true){
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
				}else{
					$arrResult[] = [
						'product_id' 		=> $row->product_id,
						'product_name'		=> $row->product->name(),
						'product_color'		=> $row->product->type->color->name,
						'product_surface'	=> $row->product->type->surface->name,
						'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
						'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
						'product_picture'	=> $row->product->type->image(),
						'qty'				=> 0 - $row->qty,
						'qty_in_so'			=> 0,
					];
				}
			}
			
			if($row->transfer->to_warehouse_id){
				$ada = false;
			
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $row->product_id){
							$ada = true;
							$index = $key;
						}
					}
				}
				
				if($ada == true){
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
				}else{
					$arrResult[] = [
						'product_id' 		=> $row->product_id,
						'product_name'		=> $row->product->name(),
						'product_color'		=> $row->product->type->color->name,
						'product_surface'	=> $row->product->type->surface->name,
						'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
						'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
						'product_picture'	=> $row->product->type->image(),
						'qty'				=> 0 + $row->qty,
						'qty_in_so'			=> 0,
					];
				}
			}
		}
		
		#qty in so
		$allsales = ProjectSale::whereHas('sales',function($query) use($branch){
			$query->where('branch',$branch);
		})->get();
		
		foreach($allsales as $rowsales){
			foreach($rowsales->projectSaleProduct as $rowsaleproduct){
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $rowsaleproduct->product_id){
							
							if($rowsaleproduct->unit == '2' || $rowsaleproduct->unit == '3'){
								$m2 = (( $rowsaleproduct->product->type->length * $rowsaleproduct->product->type->width ) / 10000) * $rowsaleproduct->product->carton_pcs;
								
								if($m2 < 1.1 && $rowsaleproduct->product->type->category->parent()->id !== 18){
									$countbox = ceil($rowsaleproduct->qty);
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($rowsales->project->created_at)) < '2022-06' && $rowsaleproduct->product->type->category->parent()->id == 18){
										$countbox = ceil($rowsaleproduct->qty);
									}else{
										$countbox = ceil(round($rowsaleproduct->qty / $m2,2));
									}
								}
							}else{
								$countbox = $rowsaleproduct->qty;
							}
							
							$arrResult[$key]['qty_in_so'] = $arrResult[$key]['qty_in_so'] + $countbox;
						}
					}
				}
			}
			
			foreach($rowsales->projectDelivery->whereNotNull('received_date') as $rowdelivery){
				foreach($rowdelivery->projectDeliveryProduct as $row){
					if(count($arrResult) > 0){
						foreach($arrResult as $key => $cek){
							if($cek['product_id'] == $row->product_id){
								$arrResult[$key]['qty_in_so'] = $arrResult[$key]['qty_in_so'] - $row->qty;
							}
						}
					}
				}
			}
		}
		
		#in broken warehouse
		
		foreach($arrResult as $key => $row){
			$datastock = null;
			
			$totalbrokenanddisplay = 0;
			
			$datastock = Stock::where('product_id',$row['product_id'])->where('branch',$branch)->whereHas('warehouse',function($query){
				$query->whereIn('type',['2','3']);
			})->get();
			
			foreach($datastock as $rowstock){
				$totalbrokenanddisplay += $rowstock->qty;
			}
			
			$arrResult[$key]['qty'] -= $totalbrokenanddisplay;
		}
		
		$array = collect($arrResult)->filter(function ($value, $key) {
						return $value['qty'] > 0 || $value['qty'] < 0;
					})->sortBy('product_name')->toArray();
		
		return view('admin.pdf.inventory.report_real', [
			'data'		=> $array,
			'branch'	=> $branch
		]);
	}
	
	public function reportInRealIdr(Request $request,$branch){

		$rowIn = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use ($branch){
			$query->whereHas('projectPurchase',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		$arrResult = [];
		
		foreach($rowIn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'qty'				=> $row->qty,
					'qty_in_so'			=> 0,
				];
			}
		}
		
		$rowOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use ($branch){
			$query->whereNotNull('received_date')
				->whereHas('projectSale',function($query) use ($branch){
					$query->whereHas('sales',function($query) use ($branch){
						$query->where('branch',$branch);
					});
			});
		})->get();
		
		foreach($rowOut as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'qty'				=> 0 - $row->qty,
					'qty_in_so'			=> 0,
				];
			}
		}
		
		$rowInReturn = ProjectSaleReturnProduct::whereHas('projectSaleReturn',function($query) use ($branch){
			$query->whereHas('projectSale',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowInReturn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'qty'				=> $row->qty,
					'qty_in_so'			=> 0,
				];
			}
		}
		
		$rowOutReturn = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn',function($query) use ($branch){
			$query->whereHas('projectPurchase',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowOutReturn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'product_color'		=> $row->product->type->color->name,
					'product_surface'	=> $row->product->type->surface->name,
					'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
					'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
					'product_picture'	=> $row->product->type->image(),
					'qty'				=> 0 - $row->qty,
					'qty_in_so'			=> 0,
				];
			}
		}
		
		$rowTransfer = TransferProduct::whereHas('transfer',function($query) use ($branch){
						$query->where('branch',$branch);
					})->get();
		
		foreach($rowTransfer as $row){
			if($row->transfer->from_warehouse_id){
				$ada = false;
			
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $row->product_id){
							$ada = true;
							$index = $key;
						}
					}
				}
				
				if($ada == true){
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
				}else{
					$arrResult[] = [
						'product_id' 		=> $row->product_id,
						'product_name'		=> $row->product->name(),
						'product_color'		=> $row->product->type->color->name,
						'product_surface'	=> $row->product->type->surface->name,
						'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
						'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
						'product_picture'	=> $row->product->type->image(),
						'qty'				=> 0 - $row->qty,
						'qty_in_so'			=> 0,
					];
				}
			}
			
			if($row->transfer->to_warehouse_id){
				$ada = false;
			
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $row->product_id){
							$ada = true;
							$index = $key;
						}
					}
				}
				
				if($ada == true){
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
				}else{
					$arrResult[] = [
						'product_id' 		=> $row->product_id,
						'product_name'		=> $row->product->name(),
						'product_color'		=> $row->product->type->color->name,
						'product_surface'	=> $row->product->type->surface->name,
						'product_size'		=> $row->product->type->length.' x '.$row->product->type->width,
						'product_sqm'		=> (($row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs,
						'product_picture'	=> $row->product->type->image(),
						'qty'				=> 0 + $row->qty,
						'qty_in_so'			=> 0,
					];
				}
			}
		}
		
		#qty in so
		$allsales = ProjectSale::whereHas('sales',function($query) use($branch){
			$query->where('branch',$branch);
		})->get();
		
		foreach($allsales as $rowsales){
			foreach($rowsales->projectSaleProduct as $rowsaleproduct){
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $rowsaleproduct->product_id){
							
							if($rowsaleproduct->unit == '2' || $rowsaleproduct->unit == '3'){
								$m2 = (( $rowsaleproduct->product->type->length * $rowsaleproduct->product->type->width ) / 10000) * $rowsaleproduct->product->carton_pcs;
								
								if($m2 < 1.1 && $rowsaleproduct->product->type->category->parent()->id !== 18){
									$countbox = ceil($rowsaleproduct->qty);
								}else{
									if($m2 < 1.1 && date('Y-m',strtotime($rowsales->project->created_at)) < '2022-06' && $rowsaleproduct->product->type->category->parent()->id == 18){
										$countbox = ceil($rowsaleproduct->qty);
									}else{
										$countbox = ceil(round($rowsaleproduct->qty / $m2,2));
									}
								}
							}else{
								$countbox = $rowsaleproduct->qty;
							}
							
							$arrResult[$key]['qty_in_so'] = $arrResult[$key]['qty_in_so'] + $countbox;
						}
					}
				}
			}
			
			foreach($rowsales->projectDelivery->whereNotNull('received_date') as $rowdelivery){
				foreach($rowdelivery->projectDeliveryProduct as $row){
					if(count($arrResult) > 0){
						foreach($arrResult as $key => $cek){
							if($cek['product_id'] == $row->product_id){
								$arrResult[$key]['qty_in_so'] = $arrResult[$key]['qty_in_so'] - $row->qty;
							}
						}
					}
				}
			}
		}
		
		foreach($arrResult as $key => $rowprice){
			$data = NULL;
			$data = ProductCogs::where('product_id',$rowprice['product_id'])->where('branch',$branch)->orderByDesc('id')->first();
			$arrResult[$key]['price'] = $data ? $data->price_final : 0;
		}
		
		#in broken warehouse
		
		foreach($arrResult as $key => $row){
			$datastock = null;
			
			$totalbrokenanddisplay = 0;
			
			$datastock = Stock::where('product_id',$row['product_id'])->where('branch',$branch)->whereHas('warehouse',function($query){
				$query->whereIn('type',['2','3']);
			})->get();
			
			foreach($datastock as $rowstock){
				$totalbrokenanddisplay += $rowstock->qty;
			}
			
			$arrResult[$key]['qty'] -= $totalbrokenanddisplay;
		}
		
		$array = collect($arrResult)->filter(function ($value, $key) {
						return $value['qty'] > 0 || $value['qty'] < 0;
					})->sortBy('product_name')->toArray();
		
		return view('admin.pdf.inventory.report_real_in_rp', [
			'data'		=> $array,
			'branch'	=> $branch
		]);
	}
	
	public function reportInRp(Request $request,$branch){

		$rowIn = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use ($branch){
			$query->whereHas('projectPurchase',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		$arrResult = [];
		
		foreach($rowIn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->projectWarehouse->warehouse_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'warehouse_id'		=> $row->projectWarehouse->warehouse_id,
					'warehouse_name'	=> $row->projectWarehouse->warehouse->code.' - '.$row->projectWarehouse->warehouse->name,
					'qty'				=> $row->qty,
				];
			}
		}
		
		$rowOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use ($branch){
			$query->whereNotNull('received_date')
			->whereHas('projectSale',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowOut as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->projectDelivery->warehouse_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'warehouse_id'		=> $row->projectDelivery->warehouse_id,
					'warehouse_name'	=> $row->projectDelivery->warehouse->code.' - '.$row->projectDelivery->warehouse->name,
					'qty'				=> 0 - $row->qty,
				];
			}
		}
		
		$rowInReturn = ProjectSaleReturnProduct::whereHas('projectSaleReturn',function($query) use ($branch){
			$query->whereHas('projectSale',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowInReturn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->projectSaleReturn->warehouse_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'warehouse_id'		=> $row->projectSaleReturn->warehouse_id,
					'warehouse_name'	=> $row->projectSaleReturn->warehouse->code.' - '.$row->projectSaleReturn->warehouse->name,
					'qty'				=> $row->qty,
				];
			}
		}
		
		$rowOutReturn = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn',function($query) use ($branch){
			$query->whereHas('projectPurchase',function($query) use ($branch){
				$query->whereHas('sales',function($query) use ($branch){
					$query->where('branch',$branch);
				});
			});
		})->get();
		
		foreach($rowOutReturn as $row){
			$ada = false;
			
			if(count($arrResult) > 0){
				foreach($arrResult as $key => $cek){
					if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->projectPurchaseReturn->warehouse_id){
						$ada = true;
						$index = $key;
					}
				}
			}
			
			if($ada == true){
				$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
			}else{
				$arrResult[] = [
					'product_id' 		=> $row->product_id,
					'product_name'		=> $row->product->name(),
					'warehouse_id'		=> $row->projectPurchaseReturn->warehouse_id,
					'warehouse_name'	=> $row->projectPurchaseReturn->warehouse->code.' - '.$row->projectPurchaseReturn->warehouse->name,
					'qty'				=> 0 - $row->qty,
				];
			}
		}
		
		$rowTransfer = TransferProduct::whereHas('transfer',function($query) use ($branch){
						$query->where('branch',$branch);
					})->get();
		
		foreach($rowTransfer as $row){
			if($row->transfer->from_warehouse_id){
				$ada = false;
			
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->transfer->from_warehouse_id){
							$ada = true;
							$index = $key;
						}
					}
				}
				
				if($ada == true){
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] - $row->qty;
				}else{
					$arrResult[] = [
						'product_id' 		=> $row->product_id,
						'product_name'		=> $row->product->name(),
						'warehouse_id'		=> $row->transfer->from_warehouse_id,
						'warehouse_name'	=> $row->transfer->warehouseFrom->code.' - '.$row->transfer->warehouseFrom->name,
						'qty'				=> 0 - $row->qty,
					];
				}
			}
			
			if($row->transfer->to_warehouse_id){
				$ada = false;
			
				if(count($arrResult) > 0){
					foreach($arrResult as $key => $cek){
						if($cek['product_id'] == $row->product_id && $cek['warehouse_id'] == $row->transfer->to_warehouse_id){
							$ada = true;
							$index = $key;
						}
					}
				}
				
				if($ada == true){
					$arrResult[$index]['qty'] = $arrResult[$index]['qty'] + $row->qty;
				}else{
					$arrResult[] = [
						'product_id' 		=> $row->product_id,
						'product_name'		=> $row->product->name(),
						'warehouse_id'		=> $row->transfer->to_warehouse_id,
						'warehouse_name'	=> $row->transfer->warehouseTo->code.' - '.$row->transfer->warehouseTo->name,
						'qty'				=> 0 + $row->qty,
					];
				}
			}
		}
		
		foreach($arrResult as $key => $rowprice){
			$data = NULL;
			$data = ProductCogs::where('product_id',$rowprice['product_id'])->where('branch',$branch)->orderByDesc('id')->first();
			$arrResult[$key]['price'] = $data ? $data->price_final : 0;
		}
		
		$array = collect($arrResult)->filter(function ($value, $key) {
						return $value['qty'] > 0 || $value['qty'] < 0;
					})->sortBy('product_name')->toArray();
		
		return view('admin.pdf.inventory.report_in_rp', [
			'main' 		=> $this->getWarehouse($array),
			'data'		=> $array,
			'branch'	=> $branch
		]);
	}
	
	function getWarehouse($array){
		$arrWarehouse = [];
		
		foreach($array as $row){
			$arr = $row;
			unset($arr['product_id'],$arr['product_name']);
			$ada = false;
			
			foreach($arrWarehouse as $rowcek){
				if($arr['warehouse_id'] == $rowcek['warehouse_id']){
					$ada = true;
				}
			}
			
			if($ada == false){
				$arrWarehouse[] = $arr;
			}
		}
		
		$warehouse = array_column($arrWarehouse, 'warehouse_name');

		array_multisort($warehouse, SORT_ASC, $arrWarehouse);
		
		return $arrWarehouse;
	}
}
