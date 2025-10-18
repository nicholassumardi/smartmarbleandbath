<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use App\Models\Product;
use App\Models\AlProduct;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Country;
use App\Models\City;
use App\Models\Supplier;
use App\Models\Project;
use App\Models\ProjectSale;
use App\Models\ProjectPurchase;
use App\Models\User;
use App\Models\Stock;
use App\Models\PurchaseRequestLogin;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;

class Select2Controller extends Controller {
    
    public function purchaseRequest(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = PurchaseRequest::where('date', 'like', "%$search%")
			->orWhere('total_nominal', 'like', "%$search%")
			->orWhere('item', 'like', "%$search%")
			->orWhere('bill_to', 'like', "%$search%")
            ->orWhere('title', 'like', "%$search%")
			->get();

        foreach($data as $d) {
			$total = $d->total_nominal;
			$totalpaid = $d->totalPayment();

            $response[] = [
                'id'   			=> $d->id,
                'text' 			=> $d->date.'-'.$d->user->name.'-'.$d->title.'-'.$d->item.' - Total : '.number_format($total,0,',','.').' Paid : '.number_format($totalpaid,2,',','.').' STATUS : '.$d->status,
				'branch'		=> $d->branch,
				'nominal'		=> number_format($total,0,',','.'),
				'nominalpay'	=> $totalpaid > 0 ? number_format($totalpaid,2,',','.') : number_format($total,0,',','.'),
				'to'			=> $d->bill_to
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function purchaseRequestAvailable(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = PurchaseRequest::where('date', 'like', "%$search%")
			->orWhere('total_nominal', 'like', "%$search%")
			->orWhere('item', 'like', "%$search%")
			->orWhere('bill_to', 'like', "%$search%")
            ->orWhere('title', 'like', "%$search%")
			->get();

        foreach($data->whereIn('status',['APPR','PAID','RCVD','SBMT']) as $d) {
			$total = $d->total_nominal;
			$totalpaid = $d->totalPayment();
			
			if(($total - $totalpaid) > 0){
				$response[] = [
					'id'   			=> $d->id,
					'text' 			=> $d->date.'-'.$d->user->name.'-'.$d->title.'-'.$d->item.' - Total : '.number_format($total,0,',','.').' Paid : '.number_format($totalpaid,2,',','.').' STATUS : '.$d->status,
					'branch'		=> $d->branch,
					'nominal'		=> number_format($total,0,',','.'),
					'nominalpay'	=> number_format($totalpaid,2,',','.'),
					'balance'		=> number_format(($total - $totalpaid),2,',','.'),
					'to'			=> $d->bill_to
				];
			}
        }

        return response()->json(['items' => $response]);
    }
	
	public function type(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Type::select('id', 'code')
            ->where('code', 'like', "%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->code
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function warehouse(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Warehouse::select('id', 'name')
            ->where('name', 'like', "%$search%")->where('status', '1')
			->orWhere('code', 'like', "%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function supplier(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Supplier::where('code', 'like', "%$search%")->orWhere('name', 'like', "%$search%")->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name.' PPn : '.$d->ppn()
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function user(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = User::select('id', 'name')
            ->where('name', 'like', "%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function customer(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Customer::select('id', 'name', 'phone')
            ->where('name', 'like', "%$search%")
			->orWhere('phone', 'like', "%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name.'|'.$d->phone.'|'.$d->address
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function customerUnpaid(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Customer::select('id', 'name', 'phone')
            ->where('name', 'like', "%$search%")
			->whereHas('project')
			->orWhere('phone', 'like', "%$search%")
            ->get();

        foreach($data as $d) {
			
			$balance = 0;
			
			foreach($d->project as $parent){
				foreach($parent->projectSale as $row){
					$balance += str_replace(',','.',str_replace('.','',$row->getTotal()));

					foreach($row->projectSalePay as $detail){
						//$balance -= $detail->nominal;
					}

				}
			}
			
			if($balance > 0){
				$response[] = [
					'id'   => $d->id,
					'text' => $d->name.'|'.$d->phone
				];
			}
        }

        return response()->json(['items' => $response]);
    }
	
	public function country(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Country::select('id', 'name')
            ->where('name', 'like', "%$search%")
			->where('status', 1)
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function currency(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Currency::select('id', 'name', 'code')
            ->where('code', 'like', "%$search%")
			->where('status', 1)
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name.' - '.$d->code
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function city(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = City::select('id', 'name')
            ->where('name', 'like', "%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function product(Request $request)
    {
        $response = [];
        $search   = $request->search;
		
		if(session('bo_branch') == '2'){
			$data     = Product::whereHas('type', function($query){
					$query->whereHas('category',function($query){
						$query->where('parent_id','<>',137);	
					});
				})
				->whereHas('type', function($query) use ($search) {
					$query->where('code', 'like', "%$search%")
						->orWhereHas('category', function($query) use ($search) {
							$query->where('name', 'like', "%$search%")->where('parent_id','<>',137);
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
						$query->where('name', 'like', "%$search%")->where('name','not like','%meir%');
					})
				->orWhereHas('country', function($query) use ($search) {
						$query->where('name', 'like', "%$search%");
					})
				->get();
		}else{
			$data   = Product::whereHas('type', function($query) use ($search) {
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
					})
				->orWhereHas('country', function($query) use ($search) {
						$query->where('name', 'like', "%$search%");
					})
				->get();
		}
        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->type->category->name.' - '.$d->name(),
            ];
        }

        // 6165

        return response()->json(['items' => $response]);
    }
	
	public function alProduct(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = AlProduct::where('code','like',"%$search%")->orWhere('name','like',"%$search%")->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->name.' Supp. '.$d->alSupplier->name.' Beli Rp.'.number_format($d->buy_price,0,',','.'),
				'beli' => number_format($d->buy_price,0,',','.'),
				'jual' => number_format($d->sell_price,0,',','.'),
				'unit' => $d->unit(),
				'name' => $d->name
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function loginAndroid(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = PurchaseRequestLogin::select('id', 'username', 'nama')
            ->where('username', 'like', "%$search%")
			->orWhere('nama', 'like', "%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->nama,
                'text' => $d->username.' - '.$d->nama
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function project(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = Project::where('name', 'like', "%$search%")
			->orWhere('code', 'like', "%$search%")
			->orWhereHas('customer', function($query) use ($search){
				$query->where('name', 'like', "%$search%");
			})
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => $d->code.' - '.$d->name.' Cust. '.$d->customer->name.' Rp '.number_format($d->getTotalSale(),0,',','.')
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function stock(Request $request)
    {
        $response = [];
        $search   = $request->search;
		$warehouse = $request->warehouse;
		$branch = $request->branch;
        $data     = Stock::whereHas('product', function($query) use ($search) {
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
							})
						->orWhereHas('country', function($query) use ($search) {
								$query->where('name', 'like', "%$search%");
							});
					})
					->where('warehouse_id',$warehouse)
					->where('branch',$branch)
					->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->product_id,
                'text' => $d->product->name().' - Qty : '.$d->qty
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	public function salesOrder(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = ProjectSale::whereHas('project', function($query) use ($search) {
				$query->whereHas('customer', function($query) use ($search) {
					$query->where('name', 'like', "%$search%");
				})
				->orWhere('name','like',"%$search%")
				->orWhere('code','like',"%$search%");
			})
			->orWhere('code','like',"%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => 'SO : '.$d->code.' - PJ : '.$d->project->code.' - Customer : '.$d->project->customer->name.' - Total : Rp. '.$d->getTotal()
            ];
        }

        return response()->json(['items' => $response]);
    }


    public function salesOrderForPurchaseFromStock(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = ProjectSale::whereHas('project', function($query) use ($search) {
				$query->whereHas('customer', function($query) use ($search) {
					$query->where('name', 'like', "%$search%");
				})
				->orWhere('name','like',"%$search%")
				->orWhere('code','like',"%$search%");
			})
			->orWhere('code','like',"%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => 'SO : '.$d->code.' - PJ : '.$d->project->code.' - Customer : '.$d->project->customer->name
            ];
        }

        return response()->json(['items' => $response]);
    }
	
	
	public function projectPurchase(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data     = ProjectPurchase::whereHas('supplier', function($query) use ($search) {
				$query->where('name','like',"%$search%");
			})
			->orWhere('code','like',"%$search%")
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   => $d->id,
                'text' => 'PO : '.$d->code.' - Supplier : '.$d->supplier->name.' - Total : '.$d->getTotal()
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function brand(Request $request){
        $response = [];
        $search = $request->search;
        $data = Brand::where('name','like', "%$search%")->get();

        foreach ($data as $d) {
            $response[] = [
                'id' => $d->name,
                'text' => $d->name,
            ];
        }

        return response()->json(['items' => $response]);
    }
}
