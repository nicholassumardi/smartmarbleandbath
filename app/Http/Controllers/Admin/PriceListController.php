<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProjectDeliveryProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BudgetingProjectProduct;
use App\Models\ProjectPurchaseProduct;
use App\Models\ProjectWarehouseProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PriceListController extends Controller {
    
    public function index(Request $request)
    {
		
        $data = [
            'title'   => 'Price List',
            'content' => 'admin.price_list'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
            'product_id'
        ];
		
		$branch = session('bo_branch') == 1 ? $request->branch: session('bo_branch');

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use($search,$request,$branch){
			$query->whereHas('projectSale',function($query) use($search,$request,$branch) {
				$query->whereHas('sales',function($query) use($search,$request,$branch){
					$query->where('branch',$branch);
				});
			})->whereNotNull('received_date');
		})->count();
        
        $query_data = ProjectDeliveryProduct::where(function($query) use($request,$search,$branch){
				if($search){
					$query->whereHas('product',function($query) use($search){
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
							$query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
						})
						->orWhereHas('country', function($query) use ($search) {
							$query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
						})
						->orWhereHas('productShading', function($query) use ($search) {
							$query->where('stock_code','like',"%$search%");
						});
					});
				}
			})
			->whereHas('projectDelivery',function($query) use($search,$request,$branch){
				$query->whereHas('projectSale',function($query) use($search,$request,$branch) {
					$query->whereHas('sales',function($query) use($search,$request,$branch){
						$query->where('branch',$branch);
					});
				})->whereNotNull('received_date');
			})
            ->offset($start)
            ->limit($length)
			->orderByDesc('updated_at')
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectDeliveryProduct::where(function($query) use($search,$request,$branch){
				if($search){
					$query->whereHas('product',function($query) use($search){
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
							$query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
						})
						->orWhereHas('country', function($query) use ($search) {
							$query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
						})
						->orWhereHas('productShading', function($query) use ($search) {
							$query->where('stock_code','like',"%$search%");
						});
					});
				}
			})
			->whereHas('projectDelivery',function($query) use($search,$request,$branch){
				$query->whereHas('projectSale',function($query) use($search,$request,$branch) {
					$query->whereHas('sales',function($query) use($search,$request,$branch){
						$query->where('branch',$branch);
					});
				})->whereNotNull('received_date');
			})
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$unit = $val->unit == '2' || $val->unit == '3' ? 'sqm' : 'pcs';
				
                $response['data'][] = [
                    $nomor,
                    $val->product->name(),
                    $val->projectDelivery->projectSale->project->customer->name,
					date('d M Y',strtotime($val->projectDelivery->received_date)),
					number_format($val->priceList(),2,',','.').'/'.$unit,
					number_format($val->recommendedPrice(),2,',','.').'/'.$unit,
					number_format($val->bestPrice(),2,',','.').'/'.$unit,
					number_format($val->price(),2,',','.').'/'.$unit,
					$val->qty().' '.$unit,
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
	

	public function datatableBuyPrice(Request $request){
		$column = [
            'id',
            'product_id'
        ];
		
		$branch = session('bo_branch');

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use($search,$request,$branch){
			$query->whereNotNull('date_receive')
				  ->whereHas('projectPurchase')
				  ->whereHas('project');
		})->count();
        
        $query_data = ProjectWarehouseProduct::where(function($query) use($request,$search,$branch){
				if($search){
					$query->whereHas('product',function($query) use($search){
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
							$query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
						})
						->orWhereHas('country', function($query) use ($search) {
							$query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
						})
						->orWhereHas('productShading', function($query) use ($search) {
							$query->where('stock_code','like',"%$search%");
						});
					})->orWhereHas('projectWarehouse',function($query) use($search,$request,$branch){
						$query->whereHas('projectPurchase', function($query) use ($search){
							$query->whereHas('supplier', function($query) use ($search){
								$query->where('name', 'like', "%$search%");
							});
						})
							  ->whereNotNull('date_receive')
							  ->whereHas('project');
					});
				}
			})
            ->offset($start)
            ->limit($length)
			->orderByDesc('updated_at')
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectWarehouseProduct::where(function($query) use($search,$request,$branch){
				if($search){
					$query->whereHas('product',function($query) use($search){
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
							$query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
						})
						->orWhereHas('country', function($query) use ($search) {
							$query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
						})
						->orWhereHas('productShading', function($query) use ($search) {
							$query->where('stock_code','like',"%$search%");
						});
					})->orWhereHas('projectWarehouse',function($query) use($search,$request,$branch){
						$query->whereHas('projectPurchase', function($query) use ($search){
							$query->whereHas('supplier', function($query) use ($search){
								$query->where('name', 'like', "%$search%");
							});
						})
							  ->whereNotNull('date_receive')
							  ->whereHas('project');
					});
				}
			})

            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$unit = $val->unit == '2' || $val->unit == '3' ? 'sqm' : 'pcs';
				
                $response['data'][] = [
                    $nomor,
                    $val->product->name()." - ".($val->product->type->category->name ? ucwords(strtoupper($val->product->type->category->name)) : ''),
                    $val->projectWarehouse->projectPurchase->supplier->name,
					$val->projectWarehouse->projectPurchase->project ? $val->projectWarehouse->projectPurchase->project->customer->name : '',
					date('d M Y',strtotime($val->created_at)),
					$val->projectWarehouse->projectPurchase->ppn(),
					$val->purchasePricePPn().'/'.$unit,
					$val->qty().' '.$unit,
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
}