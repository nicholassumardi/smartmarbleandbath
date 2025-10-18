<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Approval;
use App\Models\City;
use App\Models\BudgetingProject;
use App\Models\BalanceHistory;
use App\Models\Coa;
use App\Models\User;
use App\Models\Stock;
use App\Models\UserRole;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Journal;
use App\Models\Product;
use App\Models\ProductShading;
use App\Models\ProductCogs;
use App\Models\Project;
use App\Models\Delivery;
use App\Models\Dropshipper;
use App\Models\Supplier;
use App\Models\Notification;
use App\Models\ProjectPay;
use App\Models\ProjectBill;
use Illuminate\Http\Request;
use App\Models\ProjectSample;
use App\Models\ProjectPicture;
use App\Models\ProjectTaxDocument;
use App\Models\ProjectSampleProduct;
use App\Models\ProjectSale;
use App\Models\ProjectSaleProduct;
use App\Models\ProjectSaleTemp;
use App\Models\ProjectSaleProductTemp;
use App\Models\ProjectSaleShadingTemp;
use App\Models\ProjectPurchase;
use App\Models\ProjectPurchaseSplit;
use App\Models\ProjectPurchaseProduct;
use App\Models\ProjectPayment;
use App\Models\ProjectProforma;
use App\Models\ProjectFromStock;
use App\Models\ProjectFromStockProduct;
use App\Models\ProjectProduct;
use App\Models\ProjectNote;
use App\Models\ProjectPurchaseBill;
use App\Models\ProjectDelivery;
use App\Models\ProjectDeliveryProduct;
use App\Models\ProjectDeliveryTrack;
use App\Models\ProjectShipment;
use App\Models\ProjectShipmentProduct;
use App\Models\ProjectShipmentTrack;
use App\Models\ProjectProduction;
use App\Models\ProjectWarehouse;
use App\Models\ProjectWarehouseProduct;
use App\Models\ProjectPurchaseReturn;
use App\Models\ProjectPurchaseReturnProduct;
use App\Models\ProjectSaleShading;
use App\Models\ProjectSaleReturn;
use App\Models\ProjectSaleReturnProduct;
use App\Models\ProjectQuotation;
use App\Models\ProjectQuotationProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectConsultantMeeting;
use App\Models\ProjectNegotiation;
use App\Models\ProjectTroubleshooting;
use App\Models\ProjectReturnMemo;
use App\Models\PurchaseCost;
use App\Models\ProjectReturnMemoDetail;
#use Illuminate\Support\Facades\Mail;
use App\Jobs\EmailProcess;
use App\Models\Warehouse;
use App\Models\ProjectLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\PurchaseRequest;
use App\Helper\SendMessage;
use App\Helper\CheckCutOff;
use App\Helper\SMB;
use App\Models\FieldTrip;
use App\Models\ProjectPurchaseQuotation;
use Illuminate\View\View;

class ProjectController extends Controller {
    
    public function index() 
    {
		
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode('/', $uri_path);
		
		if($uri[2] == 'purchase_order'){
			$totalUnmatch = 0;
			$totalUnmatchSO = 0;
			$totalUnmatchPOWR = 0;
			
			$project = Project::whereHas('projectSale',function($query){ $query->whereNull('is_closed'); })->where('progress','>=',40)->get();
			// $test = ProjectSaleProduct::Doesnthave('product')->get();
			// foreach ($test as $val) {
			// 	dd($val->id);
			// }
			foreach($project as $val){
				$balanceItems = 0;
				$balanceItemsSO = 0;
				$balanceItemsPOWR = 0;
				
				foreach($val->projectSale as $ps){
					
					foreach($ps->projectSaleProduct as $psp){
						if($psp->unit == '2' || $psp->unit == '3'){
							$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
							
							if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
								$balanceItemsSO += $psp->qty;
							}else{
								if($m2 < 1.1 && date('Y-m',strtotime($ps->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
									$balanceItemsSO += $psp->qty;
								}else{
									$balanceItemsSO += ceil(round($psp->qty / $m2,2));
								}
							}
						}else{
							$balanceItemsSO += $psp->qty;
						}
					}
					
					foreach($ps->projectPurchase as $pp){
					
						foreach($pp->projectPurchaseProduct as $ppp){
							$balanceItemsSO -= $ppp->qty;
							$balanceItemsPOWR += $ppp->qty;
						}
					
						foreach($pp->projectWarehouse as $pw){
							foreach($pw->projectWarehouseProduct as $pwp){
								$balanceItems += $pwp->qty;
								$balanceItemsPOWR -= $pwp->qty;
							}
						}
						
						foreach($pp->projectPurchaseReturn as $ppr){
							foreach($ppr->projectPurchaseReturnProduct as $pprp){
								$balanceItemsSO += $pprp->qty;
								$balanceItems -= $pprp->qty;
								$balanceItemsPOWR -= $pprp->qty;
							}
						}
					}
					
					foreach($ps->projectFromStock as $pfs){
						foreach($pfs->projectFromStockProduct as $pfsp){
							$balanceItemsSO -= $pfsp->qty;
						}
					}
					
					foreach($ps->projectDelivery as $pd){
						foreach($pd->projectDeliveryProduct as $pdp){
							$balanceItems -= $pdp->qty;
						}
					}
					
					foreach($ps->projectSaleReturn() as $psr){
						foreach($pd->projectSaleReturnProduct as $psrp){
							$balanceItems += $psrp->qty;
						}
					}
				}
				
				if(round($balanceItems) > 0.9){
					$totalUnmatch++;
				}
				
				if(round($balanceItemsSO) > 0.9){
					$totalUnmatchSO++;
				}
				
				if($balanceItemsPOWR > 0.9){
					$totalUnmatchPOWR++;
				}
			}
			
			$data = [
				'totalunmatch'		=> $totalUnmatch,
				'totalunmatchso'	=> $totalUnmatchSO,
				'totalunmatchpowr'	=> $totalUnmatchPOWR,
				'title'   			=> 'Purchase Order Project',
				'content' 			=> 'admin.purchase_order.project'
			];
		}elseif($uri[2] == 'delivery_order'){
			
			$totalpaid = 0;
			$totalunpaid = 0;
			$totalagingbill = 0;
			$totalUndelivered = 0;
			$totalUnreceived = 0;
			$totalUnmatchSO = 0;
			
			$project = Project::whereHas('projectSale',function($query){ $query->whereNull('is_closed'); })->where('progress','>=',40)->get();
			
		
			foreach($project as $val){
				$balanceItems = 0;
				$balanceItemsSO = 0;
				foreach($val->projectSale as $ps){
					
					foreach($ps->projectSaleProduct as $psp){
						if($psp->unit == '2' || $psp->unit == '3'){
							$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
							
							if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
								$balanceItemsSO += $psp->qty;
							}else{
								if($m2 < 1.1 && date('Y-m',strtotime($ps->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
									$balanceItemsSO += $psp->qty;
								}else{
									$balanceItemsSO += ceil(round($psp->qty / $m2,2));
								}
							}
						}else{
							$balanceItemsSO += $psp->qty;
						}
					}
					
					foreach($ps->projectPurchase as $pp){
						
						foreach($pp->projectPurchaseProduct as $ppp){
							$balanceItemsSO -= $ppp->qty;
						}
					
						foreach($pp->projectWarehouse as $pw){
							foreach($pw->projectWarehouseProduct as $pwp){
								$balanceItems += $pwp->qty;
							}
						}
						
						foreach($pp->projectPurchaseReturn as $ppr){
							foreach($ppr->projectPurchaseReturnProduct as $pprp){
								$balanceItemsSO += $pprp->qty;
								$balanceItems -= $pprp->qty;
							}
						}
					}
					
					foreach($ps->projectFromStock as $pfs){
						foreach($pfs->projectFromStockProduct as $pfsp){
							$balanceItems += $pfsp->qty;
							$balanceItemsSO -= $pfsp->qty;
						}
					}
					
					foreach($ps->projectDelivery as $pd){
						foreach($pd->projectDeliveryProduct as $pdp){
							$balanceItems -= $pdp->qty;
						}
						
						if($pd->received_date == NULL || $pd->received_date == ''){
							$totalUnreceived++;
						}
					}
					
					foreach($ps->projectSaleReturn() as $psr){
						foreach($pd->projectSaleReturnProduct as $psrp){
							$balanceItems += $psrp->qty;
						}
					}
				}
				
				if(round($balanceItems) > 0){
					$totalUndelivered++;
				}
				
				if(round($balanceItemsSO) > 0.9){
					$totalUnmatchSO++;
				}
			}
			
			foreach(ProjectSale::all() as $rowsale){
				$deliverypaid = 0;
				$customer = $rowsale->project->customer_id;
				$project_id = $rowsale->project->id;
				
				foreach($rowsale->projectDelivery as $key => $rowdelivery){
					if($key == 0){
						$deliverypaid += $rowdelivery->subtotal_product + $rowdelivery->subtotal_service;
					}else{
						$deliverypaid += $rowdelivery->subtotal_product;
					}
				}
				
				foreach($rowsale->projectSalePay as $rowpay){
					$deliverypaid -= $rowpay->nominal;
				}
				
				foreach($rowsale->projectSaleReturn as $rowreturn){
					$deliverypaid -= $rowreturn->grandtotal;
				}
				
				$otherpayment = CashBankDetail::whereHas('cashBank', function($query) use($customer,$project_id){
					$query->where('customer_id',$customer)
					->where('lookable_type','projects')
					->where('lookable_id',$project_id);
				})
				->where('coa_id',27)
				->where('type','2')
				->sum('nominal');
				
				$deliverypaid -= $otherpayment;
				
				if($rowsale->project->progress >= 80){
					if(round($deliverypaid) <= 0){
						$totalpaid++;
					}else{
						$totalunpaid++;
					}
				}
			}
			
			foreach(ProjectBill::all() as $row){
				$cek = ProjectPay::where('project_bill_id',$row->id)->get();
				if(count($cek) > 0){
					
				}else{
					$totalagingbill++;
				}
			}
			
			$data = [
				'totalpaid' => $totalpaid,
				'totalunpaid' => $totalunpaid,
				'totalagingbill' => $totalagingbill,
				'totalUndelivered' => $totalUndelivered + $totalUnreceived,
				'totalunmatchso'	=> $totalUnmatchSO,
				'title'   => 'Delivery Order Project',
				'content' => 'admin.delivery_order.project'
			];
		}elseif($uri[2] == 'invoice'){
			$data = [
				'title'   => 'Invoice Project',
				'content' => 'admin.invoice.project'
			];
		}elseif($uri[2] == 'sales'){
			$data = [
				'title'   => 'Sales Project',
				'country' => Country::where('status', 1)->get(),
				'city'    => City::all(),
				'bank' => Coa::where('id', 8)->where('status', 1)->get(),
				'customer' => Customer::where('type', 2)->get(),
				'content' => 'admin.sales.project'
			];
		}

        return view('admin.layouts.index', ['data' => $data]);
    }

	// public function index(){
	// 	$test = ProjectSaleProduct::whereHas('product', function($query){
	// 		$query->whereDoesnthave('type');
	// 	})->get();
	// 	foreach ($test as $val) {
	// 		dd($val->product->type_id);
	// 	}

	// // $test = ProjectSaleProduct::Doesnthave('product')->get();
	// // 		foreach ($test as $val) {
	// // 			dd($val->id);
	// // 		}
	// }

    public function datatable(Request $request) 
    {
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode('/', $uri_path);
		$salesbranch = User::find(session('bo_id'))->branch;
		
        $column = [
			'detail',
            'id',
            'code',
            'user_id',
			'sales',
			'customer',
            'name',
            'progress'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$branch = session('bo_branch') == '2' ? '2' : ($request->branch ? $request->branch : '');
		
		if($uri[2] == 'purchase_order'){
			$total_data = Project::count();
			
			$query_data = Project::where(function($query) use ($search, $salesbranch, $branch, $request) {
					if($search) {
						$query->where(function($query) use ($search) {
							$query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%");
						})->orWhereHas('customer', function($query) use ($search) {
							$query->where('name','like',"%$search%");
						})->orWhereHas('projectSale', function($query) use ($search) {
							$query->whereHas('sales', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							});
						})->orWhereHas('user', function($query) use ($search) {
							$query->where('name','like',"%$search%");
						})->orWhereHas('projectPurchase', function($query) use ($search) {
							$query->where('code','like',"%$search%")
							->orWhereHas('projectPurchaseBill', function($query) use ($search) {
								$query->where('no_document','like',"%$search%");
							})
							->orWhereHas('projectPurchaseProduct', function($query) use ($search) {
								$query->whereHas('product', function($query) use ($search) {
									$query->whereHas('type', function($query) use ($search) {
										$query->where('code', 'like', "%$search%");
									});
								});
							})
							->orWhereHas('supplier', function($query) use ($search){
								$query->where('name','like',"%$search%");
							});
						});
					}

					if($salesbranch == '1'){
						if($branch){
							$query->whereHas('user', function($query) use ($branch) {
								$query->where('branch',$branch);
							});
						}
					}else{
						$query->whereHas('user', function($query) use ($branch) {
							$query->where('branch','2');
						});
					}
				})
				->whereRaw('progress >= 37')
				->offset($start)
				->limit($length)
				->orderBy('created_at','DESC')
				//->orderBy($order, $dir)
				->get();

			$total_filtered = Project::where(function($query) use ($search, $salesbranch, $branch, $request) {
					if($search) {
						$query->where(function($query) use ($search) {
							$query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%");
						})->orWhereHas('customer', function($query) use ($search) {
							$query->where('name','like',"%$search%");
						})->orWhereHas('projectSale', function($query) use ($search) {
							$query->whereHas('sales', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							});
						})->orWhereHas('user', function($query) use ($search) {
							$query->where('name','like',"%$search%");
						})->orWhereHas('projectPurchase', function($query) use ($search) {
							$query->where('code','like',"%$search%")
							->orWhereHas('projectPurchaseBill', function($query) use ($search) {
								$query->where('no_document','like',"%$search%");
							})
							->orWhereHas('projectPurchaseProduct', function($query) use ($search) {
								$query->whereHas('product', function($query) use ($search) {
									$query->whereHas('type', function($query) use ($search) {
										$query->where('code', 'like', "%$search%");
									});
								});
							})
							->orWhereHas('supplier', function($query) use ($search){
								$query->where('name','like',"%$search%");
							});
						});
					}

					if($salesbranch == '1'){
						if($branch){
							$query->whereHas('user', function($query) use ($branch) {
								$query->where('branch',$branch);
							});
						}
					}else{
						$query->whereHas('user', function($query) use ($branch) {
							$query->where('branch','2');
						});
					}
				})
				->whereRaw('progress >= 40')
				->count();
		}elseif($uri[2] == 'delivery_order'){
			
			$total_data = Project::count();
			
			$query_data = Project::where(function($query) use ($search, $salesbranch, $branch, $request) {
					if($search) {
						$query->where(function($query) use ($search) {
							$query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%");
						})->orWhereHas('customer', function($query) use ($search) {
							$query->where('name','like',"%$search%");
						})->orWhereHas('projectSale', function($query) use ($search) {
							$query->whereHas('sales', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('projectDelivery', function($query) use ($search){
								$query->where('code','like',"%$search%")
								->orWhere('proforma_code','like',"%$search%")
								->orWhere('address','like',"%$search%");
							})->orWhereHas('projectSalePay', function($query) use ($search){
								$query->where('code','like',"%$search%");
							})->orWhere('code','like',"%$search%");
						})->orWhereHas('projectSaleReturn', function ($query) use ($search){
							$query->where('code','like',"%$search%");
						})
						->orWhereHas('user', function($query) use ($search) {
							$query->where('name','like',"%$search%");
						});
					}
					
					if($salesbranch == '1'){
						if($branch){
							$query->whereHas('user', function($query) use ($branch) {
								$query->where('branch',$branch);
							});
						}
					}else{
						$query->whereHas('user', function($query) use ($branch) {
							$query->where('branch','2');
						});
					}
					
					if($request->filter){
						if($request->filter == '1'){
							$query->whereHas('projectSaleReturn');
						}
						if($request->filter == '2'){
							$query->whereHas('projectSale', function($query){
								$query->whereHas('checkTaxDocument');
							});
						}
						if($request->filter == '3'){
							$query->whereHas('projectSale', function($query){
								$query->whereDoesntHave('checkAvailableTax');
							})->where('ppn','1');
						}
					}
				})
				->whereRaw('progress >= 37')
				->offset($start)
				->limit($length)
				//->orderBy('created_at','DESC')
				->orderBy($order, $dir)
				->get();

			$total_filtered = Project::where(function($query) use ($search, $salesbranch, $branch, $request) {
					if($search) {
						$query->where(function($query) use ($search) {
							$query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%");
						})->orWhereHas('customer', function($query) use ($search) {
							$query->where('name','like',"%$search%");
						})->orWhereHas('projectSale', function($query) use ($search) {
							$query->whereHas('sales', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('projectDelivery', function($query) use ($search){
								$query->where('code','like',"%$search%")
								->orWhere('proforma_code','like',"%$search%")
								->orWhere('address','like',"%$search%");
							})->orWhereHas('projectSalePay', function($query) use ($search){
								$query->where('code','like',"%$search%");
							})->orWhere('code','like',"%$search%");
						})->orWhereHas('projectSaleReturn', function ($query) use ($search){
							$query->where('code','like',"%$search%");
						})
						->orWhereHas('user', function($query) use ($search) {
							$query->where('name','like',"%$search%");
						});
					}
					
					if($salesbranch == '1'){
						if($branch){
							$query->whereHas('user', function($query) use ($branch) {
								$query->where('branch',$branch);
							});
						}
					}else{
						$query->whereHas('user', function($query) use ($branch) {
							$query->where('branch','2');
						});
					}
					
					if($request->filter){
						if($request->filter == '1'){
							$query->whereHas('projectSaleReturn');
						}
						
						if($request->filter == '2'){
							$query->whereHas('projectSale', function($query){
								$query->whereHas('checkTaxDocument');
							});
						}
						
						if($request->filter == '3'){
							$query->whereHas('projectSale', function($query){
								$query->whereDoesntHave('checkAvailableTax');
							})->where('ppn','1');
						}
					}
				})
				->whereRaw('progress >= 37')
				->count();
			
		}else{
			$total_data = Project::count();
			
			$query_data = Project::where(function($query) use ($search, $salesbranch, $branch, $request) {
					
					if($salesbranch == '1'){
						if($search) {
							$query->where(function($query) use ($search) {
								$query->where('name', 'like', "%$search%")
									->orWhere('code', 'like', "%$search%");
							})->orWhereHas('customer', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('projectSale', function($query) use ($search) {
								$query->whereHas('sales', function($query) use ($search) {
									$query->where('name','like',"%$search%");
								})->orWhere('code', 'like', "%$search%");
							})->orWhereHas('user', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('projectProduct', function($query) use ($search) {
								$query->whereHas('product', function($query) use ($search) {
									$query->whereHas('type', function($query) use ($search) {
										$query->where('code', 'like', "%$search%");
									});
								});
							});
						}
					}else{
						if($search) {
							$query->whereHas('user', function($query) {
								$query->where('branch','2');
							})->where(function($query) use ($search) {
								$query->where('name', 'like', "%$search%")
									->orWhere('code', 'like', "%$search%");
							})->orWhereHas('customer', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('user', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('projectProduct', function($query) use ($search) {
								$query->whereHas('product', function($query) use ($search) {
									$query->whereHas('type', function($query) use ($search) {
										$query->where('code', 'like', "%$search%");
									});
								});
							});
						}
						
						$query->whereHas('user', function($query) {
							$query->where('branch','2');
						});
					}
				})
				->offset($start)
				->limit($length)
				//->orderBy('created_at','DESC')
				->orderBy($order, $dir)
				->get();

			$total_filtered = Project::where(function($query) use ($search, $salesbranch, $branch, $request) {
					
					if($salesbranch == '1'){
						if($search) {
							$query->where(function($query) use ($search) {
								$query->where('name', 'like', "%$search%")
									->orWhere('code', 'like', "%$search%");
							})->orWhereHas('customer', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('projectSale', function($query) use ($search) {
								$query->whereHas('sales', function($query) use ($search) {
									$query->where('name','like',"%$search%");
								})->orWhere('code', 'like', "%$search%");
							})->orWhereHas('user', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('projectProduct', function($query) use ($search) {
								$query->whereHas('product', function($query) use ($search) {
									$query->whereHas('type', function($query) use ($search) {
										$query->where('code', 'like', "%$search%");
									});
								});
							});
						}
					}else{
						if($search) {
							$query->whereHas('user', function($query) {
								$query->where('branch','2');
							})->where(function($query) use ($search) {
								$query->where('name', 'like', "%$search%")
									->orWhere('code', 'like', "%$search%");
							})->orWhereHas('customer', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							})->orWhereHas('user', function($query) use ($search) {
								$query->where('name','like',"%$search%");
							});
						}
						
						$query->whereHas('user', function($query) use ($branch) {
							$query->where('branch','2');
						});
					}
					
				})
				->count();
		}

        $response['data'] = [];
		
        if($query_data <> FALSE) {
            $nomor = $start + 1;
			
            foreach($query_data as $val) {
				$color = '';
				$reject = '';
				$closeSO = '';
				
                if($val->progress == 100) {
					if($uri[2] == 'sales'){
						$action = '<a href="' . url('admin/sales/project/progress/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Detail"><i class="icon-search4"></i></a> <button class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="deleteProject(' . $val->id . ')"><i class="icon-cancel-circle2"></i></button>';
						$action .= ' <a href="javascript:void(0);" onclick="addPictures('.$val->id.')" class="btn bg-success btn-sm"><i class="icon-images3"></i></a> <a href="'.url('information/tracking/progress/').'/'.$this->enkripsi($val->code).'" data-popup="tooltip" title="Tracking Project" class="btn bg-primary btn-sm"><i class="icon-link"></i></a>';
						
						if(in_array(1, session('bo_role')) || in_array(5, session('bo_role')) || in_array(4, session('bo_role'))){
							$action .= ' <a href="'.url("admin/sales/budgeting_project/project/")."/".$val->id.'" data-popup="tooltip" title="Budgeting Project : '.(count($val->budgetingProject) > 0 ? "Available" : "Empty").'" class="btn bg-secondary btn-sm"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.(count($val->budgetingProject) ? "1" : "0").'</span><i class="icon-coins"></i></a>';
						}
						
						if($val->projectSale()->exists()){
							$action .= ' <a href="javascript:void(0);" data-popup="tooltip" title="Close Sales Order" onclick="closeSO('.$val->id.')" class="btn bg-pink btn-sm"><i class="icon-close2"></i></a>';
						}
					}elseif($uri[2] == 'purchase_order'){
						$action = '<a href="' . url('admin/purchase_order/project/progress/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Detail"><i class="icon-search4"></i></a>';
					}elseif($uri[2] == 'delivery_order'){
						$action = '<a href="' . url('admin/delivery_order/project/progress/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Detail"><i class="icon-search4"></i></a>';
					}elseif($uri[2] == 'invoice'){
						$action = '<a href="' . url('admin/invoice/project/progress/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Detail"><i class="icon-search4"></i></a>';
					}

                    $progress = '
                        <div class="progress" style="height:0.875rem;">
                            <div class="progress-bar progress-bar-striped bg-teal" style="width:100%">
                                <span class="font-weight-bold text-uppercase">
                                    <span style="font-size:13px;">' . $val->progress . '%</span>
                                </span>
                            </div>
                        </div>
                    ';
                } else {
					if($uri[2] == 'sales'){
						$action = '<a href="javascript:void(0);" onclick="editProject('.$val->id.')" class="btn bg-info btn-sm" data-popup="tooltip" title="Edit"><i class="icon-pencil5"></i></a> <a href="' . url('admin/sales/project/progress/' . $val->id) . '" class="btn bg-warning btn-sm" data-popup="tooltip" title="Progress"><i class="icon-zoomin3"></i></a> <button class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="deleteProject(' . $val->id . ')"><i class="icon-trash"></i></button>';
						$action .= ' <a href="javascript:void(0);" data-popup="tooltip" title="Image Gallery" onclick="addPictures('.$val->id.')" class="btn bg-success btn-sm"><i class="icon-images3"></i></a> <a href="'.url('information/tracking/progress/').'/'.$this->enkripsi($val->code).'" data-popup="tooltip" title="Tracking Project" class="btn bg-primary btn-sm"><i class="icon-link"></i></a>';
						
						if(in_array(1, session('bo_role')) || in_array(5, session('bo_role')) || in_array(4, session('bo_role'))){
							$action .= ' <a href="'.url("admin/sales/budgeting_project/project/")."/".$val->id.'" data-popup="tooltip" title="Budgeting Project : '.(count($val->budgetingProject) > 0 ? "Available" : "Empty").'" class="btn bg-secondary btn-sm"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.(count($val->budgetingProject) ? "1" : "0").'</span><i class="icon-coins"></i></a>';
						}
						
						if($val->projectSale()->exists()){
							$action .= ' <a href="javascript:void(0);" data-popup="tooltip" title="Close Sales Order" onclick="closeSalesOrder('.$val->id.')" class="btn bg-pink btn-sm"><i class="icon-close2"></i></a>';
						}
					}elseif($uri[2] == 'purchase_order'){
						
						$countPreNotes = ProjectNote::where('notable_type','pre_purchase')->where('notable_id',$val->id)->count();
						
						$action = '<a href="' . url('admin/purchase_order/project/progress/' . $val->id) . '" class="btn bg-warning btn-sm" data-popup="tooltip" title="Progress"><i class="icon-zoomin3"></i></a> <a href="javascript:void(0);" data-popup="tooltip" title="Notes" onclick="addNotes('.$val->id.')" class="btn bg-success btn-sm"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.$countPreNotes.'</span><i class="icon-pen6"></i></a>';
					}elseif($uri[2] == 'delivery_order'){
						$action = '<a href="' . url('admin/delivery_order/project/progress/' . $val->id) . '" class="btn bg-warning btn-sm" data-popup="tooltip" title="Progress"><i class="icon-zoomin3"></i></a>';
					}elseif($uri[2] == 'invoice'){
						$action = '<a href="' . url('admin/invoice/project/progress/' . $val->id) . '" class="btn bg-warning btn-sm" data-popup="tooltip" title="Progress"><i class="icon-zoomin3"></i></a>';
					}

                    $progress = '
                        <div class="progress" style="height:0.875rem;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
                                <span class="font-weight-bold text-uppercase">
                                    <span style="font-size:13px;">' . $val->progress . '%</span>
                                </span>
                            </div>
                        </div>
                    ';
                }
				
				if($val->budgetingProjectByDate() == true){
					$color = '1';
				}

				if($val->isCloseSO() == true){
					$closeSO = '1';
				}
				
				if($uri[2] == 'delivery_order'){
					
					$totalPay = 0;
					$totalDelivery = 0;
					
					foreach($val->projectSale as $rowsale){
						foreach($rowsale->projectDelivery as $key => $rowdelivery){
							if($key == 0){
								$totalDelivery += $rowdelivery->grandtotal_product + $rowdelivery->grandtotal_service;
							}else{
								$totalDelivery += $rowdelivery->grandtotal_product;
							}
						}
						
						foreach($rowsale->projectSalePay as $rowpay){
							$totalPay += $rowpay->nominal;
						}
					}
					
					$percentpay = $totalDelivery ? (round(($totalPay / $totalDelivery) * 100,2)) : 0;
					
					if($percentpay < 100){
						$payment = '
							<div class="progress" style="height:0.875rem;">
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
									<span class="font-weight-bold text-uppercase">
										<span style="font-size:13px;">' . ($percentpay) . '%</span>
									</span>
								</div>
							</div>
						';
					}else{
						$payment = '
							<div class="progress" style="height:0.875rem;">
								<div class="progress-bar progress-bar-striped bg-teal" style="width:100%;">
									<span class="font-weight-bold text-uppercase">
										<span style="font-size:13px;">' . ($percentpay) . '%</span>
									</span>
								</div>
							</div>
						';
					}
					
					$response['data'][] = [
						'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
						$nomor,
						$val->code,
						strtoupper($val->user->name),
						$val->projectSale()->count() > 0 ? strtoupper($val->projectSale()->first()->sales->name) : '',
						strtoupper($val->customer->name),
						strtoupper($val->name),
						$progress,
						$payment,
						$action,
						$color,
						$closeSO
					];
				}else{
					$response['data'][] = [
						'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
						$nomor,
						$val->code,
						strtoupper($val->user->name),
						$val->projectSale()->count() > 0 ? strtoupper($val->projectSale()->first()->sales->name) : '',
						strtoupper($val->customer->name),
						strtoupper($val->name),
						$progress,
						$action,
						$color,
						$closeSO,
						$val->reject_reason ? 'Rejected with reason : '.$val->reject_reason : ''
					];
				}
				
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'country_id'    		 => 'required',
            'sales_project_id'       => 'required',
            'city_id'       		 => 'required',
			'city_franco_id'		 => 'required',
            'project_name'			 => 'required',
            'customer_id'   		 => 'required',
            'timeline'      		 => 'required',
            'manager'       		 => 'required',
            'consultant'    		 => 'required',
            'owner'         		 => 'required',
			'bank_id'    			 => 'required',
            'detail_payment'		 => 'required',
            'supply_method' 		 => 'required',
            'ppn'           		 => 'required'
        ], [
            'country_id.required'    		 => 'Please select a country.',
            'sales_project_id.required'      => 'Please select sales.',
            'city_id.required'       		 => 'Please select a city of project.',
			'city_franco_id.required'		 => 'Please select a city of franco.',
            'project_name.required'  		 => 'Project name cannot be empty.',
            'customer_id.required'   		 => 'Customer cannot be empty.',
            'timeline.required'      		 => 'Timeline cannot be empty',
            'constructor.required'   		 => 'Constructor name cannot be empty',
            'manager.required'       		 => 'Project manager cannot be empty',
            'consultant.required'    		 => 'Consultant name cannot be empty',
            'owner.required'         		 => 'Owner cannot be empty',
			'bank_id.required' 		 		 => 'Please select a bank destination.',
            'detail_payment.required'		 => 'Please select a payment method.',
            'supply_method.required' 		 => 'Please select a supply method.',
            'ppn.required'           		 => 'Please select a PPN.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp_project){
				$query = Project::find($request->temp_project);

				if($request->ppn != $query->ppn && count($query->projectDelivery) > 0){
					$response = [
						'status'  => 403,
						'message'  => 'Data cannot be saved, because delivery already sent'
					];
				}else{
					$query->user_id = session('bo_id');
					$query->sales_id = $request->sales_project_id;
					$query->country_id = $request->country_id;
					$query->city_id = $request->city_id;
					$query->city_franco_id = $request->city_franco_id;
					$query->name = $request->project_name;
					$query->customer_id = $request->customer_id;
					$query->timeline = $request->timeline;
					$query->manager = $request->manager;
					$query->consultant = $request->consultant;
					$query->owner = $request->owner;
					$query->coa_id = $request->bank_id;
					$query->payment_method = $request->detail_payment;
					$query->term_payment = $request->term_payment;
					$query->supply_method = $request->supply_method;
					$query->ppn = $request->ppn;
					$query->discount = str_replace(',','.',str_replace('.','',$request->discount));
					$query->remark = $request->remark;
					
					$query->update();
					
					if($query) {
					
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Existing project information has been updated!';
						$description = 'Project '.$query->code.' information has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif

						activity()
							->performedOn(new Project())
							->causedBy(session('bo_id'))
							->withProperties($query)
							->log('Add project data');

						$response = [
							'status'  => 200,
							'message' => 'Data added successfully.'
						];
					} else {
						$response = [
							'status'  => 500,
							'message' => 'Data failed to add.'
						];
					}
				}
			
			}else{
				$query = Project::create([
					'user_id'        => session('bo_id'),
					'sales_id'       => $request->sales_project_id,
					'country_id'     => $request->country_id,
					'city_id'        => $request->city_id,
					'city_franco_id' => $request->city_franco_id,
					'code'           => Project::generateCode(),
					'name'           => $request->project_name,
					'customer_id'    => $request->customer_id,
					'timeline'       => $request->timeline,
					'manager'        => $request->manager,
					'consultant'     => $request->consultant,
					'owner'          => $request->owner,
					'coa_id' 		 => $request->bank_id,
					'payment_method' => $request->detail_payment,
					'term_payment'   => $request->term_payment,
					'supply_method'  => $request->supply_method,
					'ppn'            => $request->ppn,
					'progress'       => 10,
					'discount'       => str_replace(',','.',str_replace('.','',$request->discount)),
					'in_store'		 => '0',
					'remark'		 => $request->remark
				]);
				
				if($query) {
				
					#start notif
					$role = array('1','2','3','4','5','6','7','9','10','11');
					$title = 'New project has been created!';
					$description = 'New project '.$query->code.' has been created by '.session('bo_name');
					$link = '#';
					Notification::sendNotif($role,$title,$description,$link);
					#end notif

					activity()
						->performedOn(new Project())
						->causedBy(session('bo_id'))
						->withProperties($query)
						->log('Add project data');

					$response = [
						'status'  => 200,
						'message' => 'Data added successfully.'
					];
				} else {
					$response = [
						'status'  => 500,
						'message' => 'Data failed to add.'
					];
				}
			}
        }

        return response()->json($response);
    }

    public function getProduct(Request $request)
    {
        $data  = Product::find($request->id);
        $price = $data->cogs;
        $image = '<a href="' . $data->type->image() . '" data-lightbox="' . $data->name() . '" data-title="' . $data->name() . '"><img src="' . $data->type->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>';
		
		$salesinfo = User::find(session('bo_id'));
		
		//if($price && $data->pricingPolicy->price_list > 0){
			$m2 = (($data->type->length * $data->type->width) / 10000) * $data->carton_pcs;
				
			if($data->type->category->parent()->exists()){
				if($m2 < 1.1 && $data->type->category->parent()->id !== 18){
					$m2 = 1;
				}
			}
			
			if($salesinfo->branch == '1'){
				return response()->json([
					'id'      		=> $data->id,
					'product' 		=> $image . '<div><a href="javascript:void(0);" onclick="getShading('.$data->id.')">' . $data->name() . '</a></div><div>' . $data->type->length . 'x' . $data->type->width . '</div>',
					'price'   		=> isset($price) ? $data->pricingPolicy->price_list : 0,
					'cogs'  		=> isset($price) ? $price->formula()->cogs_pta_idr : 0,
					'bottom'  		=> isset($data->pricingPolicy->bottom_price) ? $data->pricingPolicy->bottom_price : 0,
					'surface' 		=> $data->type->surface->name,
					'sell_unit' 	=> $data->type->selling_unit_id,
					'carton_pcs' 	=> $data->carton_pcs,
					'carton_sqm' 	=> (($data->type->length * $data->type->width) / 10000) * $data->carton_pcs.' M<sup>2</sup>',
					'sqm' 			=> $m2
				]);
			}elseif($salesinfo->branch == '2'){
				return response()->json([
					'id'      		=> $data->id,
					'product' 		=> $image . '<div><a href="javascript:void(0);" onclick="getShading('.$data->id.')">' . $data->name() . '</a></div><div>' . $data->type->length . 'x' . $data->type->width . '</div>',
					'price'   		=> $price ? $data->pricingPolicy->price_list : 0,
					'cogs'  		=> $price ? $price->formula()->cogs_smb_idr : 0,
					'bottom'  		=> isset($data->pricingPolicy->bottom_price) ? $data->pricingPolicy->bottom_price : 0,
					'surface' 		=> $data->type->surface->name,
					'sell_unit' 	=> $data->type->selling_unit_id,
					'carton_pcs' 	=> $data->carton_pcs,
					'carton_sqm' 	=> (($data->type->length * $data->type->width) / 10000) * $data->carton_pcs.' M<sup>2</sup>',
					'sqm' 			=> $m2
				]);
			}
		/* }else{
			$msg_error = [];
			
			if(!$price){
				$msg_error[] = 'Please set cogs for this product in <b>Master Data - COGS Master - COGS Sales</b>.<br>';
			}
			
			if(!isset($data->pricingPolicy->price_list) || isset($data->pricingPolicy->price_list) == 0){
				$msg_error[] = 'Please set price list for this product in <b>Master Data - COGS Master - Pricing Sales</b>.<br>';
			}
			
			return response()->json([
				'error' => '500',
				'message' => $msg_error
			]);
		} */
    }
    
    public function getDelivery(Request $request)
    {
        $total_weight = 0;
        $project      = Project::find($request->id);

        foreach($project->projectProduct as $pp) {
            $total_weight += $pp->product->type->weight * $pp->qty;
        }

        $data     = [];
        $city_id  = $request->city_id;
        $delivery = Delivery::where('destination_id', $city_id)
            ->where('capacity', '>=', $total_weight)
            ->orderBy('capacity', 'asc')
            ->groupBy('transport_id')
            ->get();

        foreach($delivery as $d) {
            $data[] = [
                'id'             => $d->id,
                'price'          => 'Rp ' . number_format($d->price_per_kg * $total_weight, '0', ',', '.'),
                'transport_name' => $d->transport->fleet
            ];
        }

        return response()->json($data);
    }
	
	public function getDeliveryProduct(Request $request)
    {
        $delivery      = ProjectDeliveryProduct::where('project_delivery_id',$request->id)->get();

		$result = [];
		
        foreach($delivery as $d){
            $result[] = [
				'product_name'	=> $d->product->name(),
				'product_id'	=> $d->product_id,
				'qty'			=> $d->qty,
				'unit'			=> $d->unit(),
				'unitraw'		=> $d->unit
			];
        }

        return response()->json($result);
    }

    public function progress(Request $request, $id)
    {
        $query = Project::find($id);
        $step  = $request->submit;
        
        if(!$query) {
            abort(404);
        }
		
		$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$uri = explode('/', $uri_path);

        if($request->has('_token') && session()->token() == $request->_token) {
            switch($step) {
                case 'step-1':
                    $validation = Validator::make($request->all(), [
                        'country_id'     => 'required',
                        'city_id'        => 'required',
                        'name'           => 'required',
                        'customer_id'    => 'required',
                        'timeline'       => 'required',
                        'manager'        => 'required',
                        'consultant'     => 'required',
                        'owner'          => 'required',
						'bank_id'    	 => 'required',
                        'payment_method' => 'required',
						'detail_payment' => 'required',
                        'supply_method'  => 'required',
                        'ppn'            => 'required'
                    ], [
                        'country_id.required'     => 'Please select a country.',
                        'city_id.required'        => 'Please select a city.',
                        'name.required'           => 'Name cannot be empty.',
                        'customer_id.required'    => 'Customer cannot be empty.',
                        'timeline.required'       => 'Timeline cannot be empty',
                        'manager.required'        => 'Project manager cannot be empty',
                        'consultant.required'     => 'Consultant name cannot be empty',
                        'owner.required'          => 'Owner cannot be empty',
						'bank_id.required' 		  => 'Please select a bank destination.',
                        'payment_method.required' => 'Please select a payment method.',
						'detail_payment.required' => 'Please select a detail payment method.',
                        'supply_method.required'  => 'Please select a supply method.',
                        'ppn.required'            => 'Please select a PPN.'
                    ]);
                    break;

                case 'step-2':
                    $validation = Validator::make($request->all(), [
                        'product_id' => 'required|array'
                    ], [
                        'product_id.required' => 'Please add min 1 product.',
                        'product_id.array'    => 'product_id must be array.'
                    ]);
                    break;

                case 'step-3':
                    $validation = Validator::make($request->all(), [
                        'consultant_date'   	=> 'required|array',
                        'consultant_person'		=> 'required|array',
                        'consultant_result' 	=> 'required|array',
						'consultant_result.*'  	=> 'required|string|distinct|min:5'
                    ], [
                        'consultant_date.required'   => 'Date cannot empty.',
                        'consultant_date.array'      => 'Date must be array.',
                        'consultant_person.required' => 'Person cannot empty.',
                        'consultant_person.array'    => 'Person must be array.',
                        'consultant_result.required' => 'Result cannot empty.',
                        'consultant_result.array'    => 'Result must be array.'
                    ]);
                    break;

                case 'step-4':
                    $validation = Validator::make($request->all(), [
                        'product_recommended_price' => 'required|array',
						'product_best_price' => 'required|array',
                        'product_discount' => 'required|array'
                    ], [
                        'product_recommended_price.required' => 'Recommended price cannot empty.',
                        'product_recommended_price.array'    => 'Recommended price must be array.',
						'product_best_price.required' => 'Best Price cannot empty.',
                        'product_best_price.array'    => 'Best Price must be array.',
                        'product_discount.required' => 'Discount cannot empty.',
                        'product_discount.array'    => 'Discount must be array.'
                    ]);
                    break;

                case 'step-5':
                    $validation = Validator::make($request->all(), [
						'sample_sent_date'	=> 'required',
						'sample_return_date'=> 'required',
                        'sample_product_id' => 'required|array',
                        'sample_qty'        => 'required|array',
						'sample_unit'       => 'required|array',
                        'sample_size'       => 'required|array'
                    ], [
						'sample_sent_date.required' 	=> 'Sample sent date cannot empty.',
						'sample_return_date.required' 	=> 'Sample return date cannot empty.',
                        'sample_product_id.required' 	=> 'Please select a product.',
                        'sample_product_id.array'    	=> 'Product id must be array.',
                        'sample_qty.required'        	=> 'Qty cannot empty.',
                        'sample_qty.array'           	=> 'Qty must be array.',
						'sample_unit.required'       	=> 'Unit cannot empty.',
                        'sample_unit.array'          	=> 'Unit must be array.',
                        'sample_size.required'       	=> 'Size cannot empty.',
                        'sample_size.array'          	=> 'Size must be array.'
                    ]);
                    break;

                case 'step-6':
                    $validation = Validator::make($request->all(), [
                        'negotiation_date'   => 'required|array',
                        'negotiation_person' => 'required|array',
                        'negotiation_result' => 'required|array'
                    ], [
                        'negotiation_date.required'   => 'Date cannot empty.',
                        'negotiation_date.array'      => 'Date must be array.',
                        'negotiation_person.required' => 'Person cannot empty.',
                        'negotiation_person.array'    => 'Person must be array.',
                        'negotiation_result.required' => 'Result cannot empty.',
                        'negotiation_result.array'    => 'Result must be array.'
                    ]);
                    break;

                case 'step-7':
                    $validation = Validator::make($request->all(), [
						'sales_id' 		=> 'required',
						'date_so'		=> 'required',
						'sales_address' => 'required',
                        'submit' 		=> 'required'
                    ], [
						'sales.required'  		=> 'Sales cannot empty.',
						'date_so.required'		=> 'Date Sales Order empty.',
						'sales_address.required'=> 'Address cannot be empty.',
                        'submit.required' 		=> 'Step cannot empty.'
                    ]);
                    break;

                case 'step-8':
					if($request->temp_pay_id){
						$validation = Validator::make($request->all(), [
							'sopd_id'		 => 'required',
							'date_create'    => 'required',
							'bank_id'   	 => 'required',
							'payment_method' => 'required',
							'nominal'        => 'required'
						], [
							'sopd_id'				  => 'Sales cannot empty',
							'date_create.required'    => 'Date invoice created cannot empty.',
							'bank_id.required'    	  => 'Bank cannot empty.',
							'payment_method.required' => 'Please select a payment method.',
							'nominal.required'        => 'Nominal cannot empty.'
						]);
					}else{
						$validation = Validator::make($request->all(), [
							'sopd_id'		 => 'required',
							'file'         	 => 'required|mimes:jpg,jpeg,png,pdf',
							'date_create'    => 'required',
							'payment_method' => 'required',
							'bank_id'   	 => 'required',
							'nominal'        => 'required'
						], [
							'sopd_id'				  => 'Sales cannot empty',
							'file.required'           => 'File cannot empty.',
							'file.mimes'              => 'File must have an extension jpg, jpeg, png, and pdf.',
							'date_create.required'    => 'Date invoice created cannot empty.',
							'payment_method.required' => 'Please select a payment method.',
							'bank_id.required'    	  => 'Bank cannot empty.',
							'nominal.required'        => 'Nominal cannot empty.'
						]);
					}
                    
                    break;
				
				case 'step-9':
                    $validation = Validator::make($request->all(), [
						'sales_po' 			=> 'required',
						'supplier_id' 		=> 'required',
						'factory_name'		=> 'required',
						'on_behalf'			=> 'required',
						'delivery_address'	=> 'required',
						'courier_method'	=> 'required',
						'country_id'		=> 'required',
						'city_id'			=> 'required',
						'pic_name'			=> 'required',
						'pic_number'		=> 'required',
						'payment_method'	=> 'required',
						'payment_due_date'	=> 'required',
						'price'				=> 'required',
						'currency'			=> 'required',
						'currency_rate'		=> 'required',
						'product_id'		=> 'required|array',
						'product_unit'		=> 'required|array',
						'product_qty'		=> 'required|array',
						'product_price'		=> 'required|array',
                        'submit' 			=> 'required'
                    ], [
						'sales_po.required' 				=> 'Sales cannot empty.',
						'supplier_id.required' 				=> 'Supplier cannot empty.',
						'factory_name.required'				=> 'Factory name cannot empty.',
						'on_behalf.required'				=> 'On behalf info cannot empty.',
						'delivery_address.required'			=> 'On behalf address cannot empty.',
						'courier_method.required'			=> 'Courier info cannot empty.',
						'country_id.required'				=> 'Country cannot empty.',
						'city_id.required'					=> 'City cannot empty.',
						'pic_name.required'					=> 'PIC Name cannot empty.',
						'pic_number.required'				=> 'PIC Number cannot empty.',
						'city_id.required'					=> 'City cannot empty.',
						'payment_method.required'			=> 'Payment method cannot empty.',
						'payment_due_date.required'			=> 'Payment due date cannot empty.',
						'price.required'					=> 'Price cannot empty.',
						'currency.required'					=> 'Currency cannot empty.',
						'currency_rate.required'			=> 'Currency rate cannot empty.',
						'product_id.required'				=> 'Product cannot empty.',
						'product_id.array'					=> 'Product must be array.',
						'product_unit.required'				=> 'Product unit cannot empty.',
						'product_unit.array'				=> 'Product unit must be array.',
						'product_qty.required'				=> 'Product qty cannot empty.',
						'product_qty.array'					=> 'Product qty must be array.',
						'product_price.required'			=> 'Product price cannot empty.',
						'product_price.array'				=> 'Product qty must be array.',
                        'submit.required' 					=> 'Step cannot empty.'
                    ]);
                    break;
				
                case 'step-10':
                    $validation = Validator::make($request->all(), [
						'pop_id'  				=> 'required',
                        'date'    				=> 'required',
                        'supplier_name'    		=> 'required',
                        'supplier_warehouse' 	=> 'required'
                    ], [
						'pop_id.required'  				=> 'Purchase order cannot empty.',
                        'date.required'    				=> 'Date cannot empty.',
                        'supplier_name.required'    	=> 'Supplier name cannot empty.',
                        'supplier_warehouse.required' 	=> 'Supplier warehouse cannot empty.'
                    ]);
                    break;

                case 'step-11':
					if($request->temp_pay_id){
						$validation = Validator::make($request->all(), [
							'po_id'	  	=> 'required',
							'status'    => 'required',
							'date'    	=> 'required',
							'bank'    	=> 'required',
							'nominal' 	=> 'required'
						], [
							'po_id.required' 	=> 'PO cannot empty.',
							'status.required' 	=> 'Type cannot empty.',
							'date.required'    	=> 'Date cannot empty.',
							'bank.required'    	=> 'Bank cannot empty.',
							'nominal.required' 	=> 'Nominal cannot empty.'
						]);
					}else{
						$validation = Validator::make($request->all(), [
							'po_id'	  	=> 'required',
							'file'   	=> 'required|mimes:jpg,jpeg,png,pdf',
							'status'    => 'required',
							'date'    	=> 'required',
							'bank'    	=> 'required',
							'nominal' 	=> 'required'
						], [
							'po_id.required' 	=> 'PO cannot empty.',
							'file.required'   	=> 'File cannot empty.',
							'file.mimes'      	=> 'File must have an extension jpg, jpeg, png, and pdf.',
							'status.required' 	=> 'Type cannot empty.',
							'date.required'    	=> 'Date cannot empty.',
							'bank.required'    	=> 'Bank cannot empty.',
							'nominal.required' 	=> 'Nominal cannot empty.'
						]);
					}
					
                    break;

                case 'step-12':
                    $validation = Validator::make($request->all(), [
						'pr_id'					=> 'required',
                        'start_date'  			=> 'required',
                        'finish_date'			=> 'required',
						'progress_production'	=> 'required',
                        'note'        			=> 'required'
                    ], [
						'pr_id.required'					=> 'Purchase cannot empty.',
                        'start_date.required'  				=> 'Start date cannot empty.',
                        'finish_date.required' 				=> 'Finish date cannot empty.',
                        'note.required'        				=> 'Note cannot empty.',
						'progress_production.required'   	=> 'Progress production cannot empty.'
                    ]);
                    break;

                case 'step-13':
                    $validation = Validator::make($request->all(), [
						'por_id'  => 'required',
						'status'  => 'required',
                        'file'    => 'required|mimes:jpg,jpeg,png,pdf',
                        'date'    => 'required',
                        'bank'    => 'required',
                        'nominal_payment' => 'required'
                    ], [
						'por_id.required'  => 'Purchase cannot empty',
						'status.required'  => 'Type cannot empty',
                        'file.required'    => 'File cannot empty.',
                        'file.mimes'       => 'File must have an extension jpg, jpeg, png, and pdf.',
                        'date.required'    => 'Date cannot empty.',
                        'bank.required'    => 'Bank cannot empty.',
                        'nominal_payment.required' => 'Nominal cannot empty.'
                    ]);
                    break;
				case 'step-14':
					$validation = Validator::make($request->all(), [
						'pos_id'   		 => 'required',
						'shipment_code'	 => 'required',
                        'loading_date'   => 'required',
                        'departure_date' => 'required',
                        'from_port'      => 'required',
                        'to_port'        => 'required',
                        'eta'            => 'required',
						'product_id'	 => 'required|array',
						'product_unit'	 => 'required|array',
						'product_qty'	 => 'required|array'
                    ], [
						'pos_id.required'		  => 'Purchase cannot empty.',
						'shipment_code.required'  => 'Shipment code cannot empty.',
                        'loading_date.required'   => 'Loading date cannot empty.',
                        'departure_date.required' => 'Departure date cannot empty.',
                        'from_port.required'      => 'from port cannot empty.',
                        'to_port.required'        => 'to port cannot empty.',
                        'eta.required'            => 'ETA cannot empty.',
						'product_id.required'	  => 'Product cannot empty.',
						'product_id.array'		  => 'Product must be array.',
						'product_unit.required'	  => 'Product unit cannot empty.',
						'product_unit.array'	  => 'Product unit must be array.',
						'product_qty.required'	  => 'Product qty cannot empty.',
						'product_qty.array'		  => 'Product qty must be array.'
                    ]);
                    break;
				case 'step-15':
					if($request->temp_warehouse_id){
						$validation = Validator::make($request->all(), [
							'posw_id'   			=> 'required',
							'shipment_id'   		=> 'required',
							'person'   				=> 'required',
							'date_receive' 			=> 'required',
							'warehouse_id'      	=> 'required',
							'product_id'	 		=> 'required|array',
							'product_unit'	 		=> 'required|array',
							'product_qty'	 		=> 'required|array',
						], [
							'posw_id.required'		  		=> 'Purchase cannot empty.',
							'shipment_id.required'	  		=> 'Shipment cannot empty.',
							'person.required'   	  		=> 'Person who is responsible cannot empty.',
							'date_receive.required'   		=> 'Date received cannot empty.',
							'warehouse_id.required'  		=> 'Warehouse cannot empty.',
							'product_id.required'	  		=> 'Product cannot empty.',
							'product_id.array'		  		=> 'Product must be array.',
							'product_unit.required'	  		=> 'Product unit cannot empty.',
							'product_unit.array'	  		=> 'Product unit must be array.',
							'product_qty.required'	  		=> 'Product qty cannot empty.',
							'product_qty.array'		  		=> 'Product qty must be array.',
						]);
					}else{
						$validation = Validator::make($request->all(), [
							'posw_id'   			=> 'required',
							'shipment_id'   		=> 'required',
							'person'   				=> 'required',
							'date_receive' 			=> 'required',
							'warehouse_id'      	=> 'required',
							'file'   				=> 'required|mimes:jpg,jpeg,png,pdf',
							'product_id'	 		=> 'required|array',
							'product_unit'	 		=> 'required|array',
							'product_qty'	 		=> 'required|array',
						], [
							'posw_id.required'		  		=> 'Purchase cannot empty.',
							'shipment_id.required'	  		=> 'Shipment cannot empty.',
							'person.required'   	  		=> 'Person who is responsible cannot empty.',
							'date_receive.required'   		=> 'Date received cannot empty.',
							'warehouse_id.required'  		=> 'Warehouse cannot empty.',
							'file.required'   		  		=> 'File cannot empty.',
							'file.mimes'      		  		=> 'File must have an extension jpg, jpeg, png, and pdf.',
							'product_id.required'	  		=> 'Product cannot empty.',
							'product_id.array'		  		=> 'Product must be array.',
							'product_unit.required'	  		=> 'Product unit cannot empty.',
							'product_unit.array'	  		=> 'Product unit must be array.',
							'product_qty.required'	  		=> 'Product qty cannot empty.',
							'product_qty.array'		  		=> 'Product qty must be array.',
						]);
					}
					
					break;
                case 'step-16':
                    $validation = Validator::make($request->all(), [
						'sod_id' 		=> 'required',
                        'receiver_name' => 'required',
                        'delivery_date' => 'required',
                        'city_id2'       => 'required',
                        'address'       => 'required',
                        'warehousedeliver_id'  	=> 'required',
						'expedition_id'			=> 'required',
						'product_id'			=> 'required|array',
						'product_unit'			=> 'required|array',
						'product_qty'			=> 'required|array',
						'product_shading'		=> 'required|array',
                    ], [
						'sod_id.required' 		 		=> 'Sales order cannot be empty.',
                        'receiver_name.required' 		=> 'Receiver name cannot be empty.',
                        'delivery_date.required' 		=> 'Delivery date cannot be empty.',
                        'city_id2.required'       		=> 'Please select a city.',
                        'address.required'       		=> 'Address cannot be empty.',
                        'warehousedeliver_id.required'  => 'Please select a warehouse.',
						'expedition_id.required'   		=> 'Please select an expedition.',
						'product_id.required'	  		=> 'Product cannot empty.',
						'product_id.array'		  		=> 'Product must be array.',
						'product_unit.required'	  		=> 'Product unit cannot empty.',
						'product_unit.array'	  		=> 'Product unit must be array.',
						'product_qty.required'	  		=> 'Product qty cannot empty.',
						'product_qty.array'		  		=> 'Product qty must be array.',
						'product_shading.required'	  	=> 'Product shading cannot empty.',
						'product_shading.array'		  	=> 'Product shading must be array.',
                    ]);
                    break;
                
				case 'step-17':
					$validation = Validator::make($request->all(), [
						'sor_id'	   			=> 'required',
						'warehousereturn_id'	=> 'required',
						'return_memo'			=> 'required',
						'address'				=> 'required',
						'note'    				=> 'required',
                        'file'   				=> 'required|mimes:jpg,jpeg,png,pdf',
						'product_id'	 		=> 'required|array',
						'product_unit'	 		=> 'required|array',
						'product_qty'	 		=> 'required|array',
                    ], [
						'sor_id.required'		  		=> 'Sales cannot empty.',
						'warehousereturn_id.required'	=> 'Warehouse cannot empty.',
                        'note.required'   				=> 'Note cannot empty.',
						'return_memo.required'   		=> 'Return memo cannot empty.',
						'address.required'   			=> 'Address cannot empty.',
                        'file.required'   		  		=> 'File cannot empty.',
                        'file.mimes'      		  		=> 'File must have an extension jpg, jpeg, png, and pdf.',
						'product_id.required'	  		=> 'Product cannot empty.',
						'product_id.array'		  		=> 'Product must be array.',
						'product_unit.required'	  		=> 'Product unit cannot empty.',
						'product_unit.array'	  		=> 'Product unit must be array.',
						'product_qty.required'	  		=> 'Product qty cannot empty.',
						'product_qty.array'		  		=> 'Product qty must be array.',
                    ]);
					
					break;
				
                case 'step-18':
                    $validation = Validator::make($request->all(), [
						'sop_id'		 => 'required',
                        'file_full'      => 'required|mimes:jpg,jpeg,png,pdf',
                        'date_create_full'    => 'required',
                        'payment_method_full' => 'required',
                        'nominal_full'        => 'required'
                    ], [
						'sop_id.required'				  => 'Sales cannot empty',
                        'file_full.required'           => 'File cannot empty.',
                        'file_full.mimes'              => 'File must have an extension jpg, jpeg, png, and pdf.',
						'date_create_full.required'    => 'Date invoice created cannot empty.',
                        'payment_method_full.required' => 'Please select a payment method.',
                        'nominal_full.required'        => 'Nominal cannot empty.'
                    ]);
                    break;

                case 'step-19':
                    $validation = Validator::make($request->all(), [
						'date_trouble'				=> 'required',
                        'note_trouble'    			=> 'required'
                    ], [
						'date_trouble.required'    	=> 'Date trouble cannot empty.',
                        'note_trouble.required'     => 'Note cannot empty.'
                    ]);
                    break;
				case 'step-20':
					$validation = Validator::make($request->all(), [
                        'submit' => 'required'
                    ], [
                        'submit.required' => 'Step cannot empty.'
                    ]);
                    break;
            }

            if($validation->fails()) {
                return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?' . $step . '=1#' . $step)
                    ->withErrors($validation)
                    ->withInput();
            } else {
                switch($step) {
                    case 'step-1':
                        $query->update([
                            'country_id'     => $request->country_id,
                            'city_id'        => $request->city_id,
                            'name'           => $request->name,
                            'customer_id'    => $request->customer_id,
                            'timeline'       => $request->timeline,
                            'constructor'    => $request->constructor,
                            'manager'        => $request->manager,
                            'consultant'     => $request->consultant,
                            'owner'          => $request->owner,
							'coa_id' 		 => $request->bank_id,
                            'payment_method' => $request->detail_payment,
                            'supply_method'  => $request->supply_method,
                            'ppn'            => $request->ppn
                        ]);

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
							->withProperties($query)
                            ->log('Change data project ' . $query->name . ' (Step 1)');
                        break;

                    case 'step-2':
                        $query->update([
                            'progress' => $query->progress < 15 ? 15 : $query->progress
                        ]);
						
						//ProjectProduct::where('project_id',$query->id)->whereNotIn('product_id',$request->product_id)->delete();
						ProjectProduct::where('project_id',$query->id)->delete();
						//$pq = ProjectQuotation::where('project_id',$query->id)->get();
						
						$salesinfo = User::find(session('bo_id'));
						
                        foreach($request->product_id as $key => $pi) {
                            $product = Product::find($pi);
                            $cogs    = 0;
                            
                            if($product->pricingPolicy) {
								if($salesinfo->branch == '1'){
									$cogs = isset($product->cogs) ? $product->cogs->formula()->cogs_pta_idr : 0;
								}elseif($salesinfo->branch == '2'){
									$cogs = isset($product->cogs) ? $product->cogs->formula()->cogs_smb_idr : 0;
								}
                            }
							
							/* foreach($pq as $row){
								foreach(ProjectQuotationProduct::where('project_quotation_id',$row->id)->get() as $row1){
									$row1->delete();
								}
							} */
							
							/* $count = ProjectProduct::where('project_id',$query->id)->where('product_id',$pi)->count();
							
							if($count > 0){
								ProjectProduct::where(['project_id' => $query->id, 'product_id' => $pi])->update([
									'area'         => $request->product_area[$key],
									'spec'         => $request->product_spec[$key],
									'qty'          => str_replace(',','.',str_replace('.','.',$request->product_qty[$key])),
									'cogs'         => $cogs,
									'price'        => $request->product_price[$key],
									'unit'         => $request->product_unit[$key]
								]);
							}else{ */
								ProjectProduct::create([
									'project_id'   => $query->id,
									'product_id'   => $pi,
									'area'   	   => $request->product_area[$key],
									'spec'   	   => $request->product_spec[$key],
									'qty'          => str_replace(',','.',str_replace('.','.',$request->product_qty[$key])),
									'cogs'         => $cogs,
									'price'        => $request->product_price[$key],
									'unit'         => $request->product_unit[$key]
								]);
							//}
                            
                        }
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details spec product has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif

                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 2)');
                        break;

                    case 'step-3':
                        $query->update([
                            'progress' => $query->progress < 20 ? 20 : $query->progress
                        ]);
						
						ProjectConsultantMeeting::whereNotIn('id',$request->consultant_id)->delete();
						
                        foreach($request->consultant_date as $key => $cd) {
							if($request->consultant_id[$key] !== '0'){
								
							}else{
								ProjectConsultantMeeting::create([
									'project_id' => $query->id,
									'date'       => $cd,
									'person'     => $request->consultant_person[$key],
									'result'     => $request->consultant_result[$key]
								]);
							}
                        }
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details consultation meeting has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 3)');
                        break;

                    case 'step-4':
                        $query->update([
                            'progress' => $query->progress < 25 ? 25 : $query->progress
                        ]);
						
						/* $countDelivery = ProjectDelivery::where('project_id',$query->id)->count();

						if($countDelivery > 0){
							return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-4=1#step-4')
								->withErrors('This Project already has Delivery, please create new Project or contact developer.');
						} */
						
						$revision = ProjectQuotation::where('project_id', $query->id)->max('revision');
				
						// dd($revision);
						if($revision == 0){
							$rev1 = false;
							$rev2 = false;
							foreach($request->project_product_id as $key => $ppi) {
								if(str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_recommended_price[$key]))) > 0){
									$rev1 = true;
								}
								if(str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_best_price[$key]))) > 0){
									$rev2 = true;
								}
							}
							
							if($rev1 == true){
								$revision++;
							}
							
							if($rev2 == true){
								$revision++;
							}
						}

						$revision++;

						$delivery_cost = floatval(str_replace(',','.',str_replace('.','',$request->delivery_cost_quotation)));
						$cutting_cost = floatval(str_replace(',','.',str_replace('.','',$request->cutting_cost_quotation)));
						$misc_cost = floatval(str_replace(',','.',str_replace('.','',$request->misc_cost_quotation)));
						$discount = floatval(str_replace(',','.',str_replace('.','',$request->discount)));
						
						$project = Project::find($query->id)->update([
							'delivery_cost'	=> $delivery_cost,
							'cutting_cost'	=> $cutting_cost,
							'misc_cost'		=> $misc_cost,
							'discount'		=> $discount
						]);
						
						$projectQuotation = ProjectQuotation::create([
							'project_id'     => $query->id,
							'revision'  	 => $revision,
							'display_brand'  => $request->display_brand,
							'approved_by_1'  => 0,
							'approved_by_1'  => 0,
							'terms_cons_id'  => $request->custom_tos_id,
							'terms_cons_en'  => $request->custom_tos_en,
							'ppn_cost'  	 => $request->ppn_cost_quotation,
							'letter_head'  	 => $request->letter_head,
						]);

                        foreach($request->project_product_id as $key => $ppi) {
							ProjectProduct::find($ppi)->update([
								'price' => str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->project_product_price[$key]))),
								'recommended_price' => str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_recommended_price[$key]))),
								'best_price' => str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_best_price[$key]))),
								'discount' => str_replace(',','.',str_replace('.','',$request->product_discount[$key]))
							]);
							
							$ppp = ProjectProduct::find($ppi);
							
							ProjectQuotationProduct::create([
								'project_quotation_id'     	=> $projectQuotation->id,
								'product_id'  	 			=> $ppp->product_id,
								'area'						=> $request->project_product_area[$key],
								'price'  	 				=> $ppp->price,
								'recommended_price'  		=> str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_recommended_price[$key]))),
								'best_price' 				=> str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_best_price[$key]))),
								'discount'					=> str_replace(',','.',str_replace('.','',$request->product_discount[$key])),
								'qty'						=> $request->project_product_qty[$key],
								'unit'						=> $request->project_product_unit[$key]
							]);
                        }
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details quotation revision no. '.$revision.' has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						#send approval
						if(session('bo_branch') == '1'){
							$roleapproval = array('5');
							Approval::sendApproval($roleapproval,'project_quotations',$projectQuotation->id,'approved_by_1',session('bo_id'));
							Approval::sendApproval($roleapproval,'project_quotations',$projectQuotation->id,'approved_by_2',session('bo_id'));
							#end approval
							
							SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Quotation Nomor '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						}elseif(session('bo_branch') == '2'){
							/* $roleapproval = 18;
							$project  = Approval::sendApproval($roleapproval,'project_quotations',$projectQuotation->id,'approved_by_1',session('bo_id')); */
							$roleapproval = 16;
							Approval::sendApproval($roleapproval,'project_quotations',$projectQuotation->id,'approved_by_1',session('bo_id'));
							
							SendMessage::send(env('SALES_MANAGER_JAKARTA_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Quotation Nomor '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						}
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 4)');
                        break;

                    case 'step-5':
                        $query->update([
                            'progress' => $query->progress < 30 ? 30 : $query->progress
                        ]);
						
						$productdesc = '';
						
						$projectSample = ProjectSample::create([
							'project_id' 	=> $query->id,
							'code' 		 	=> ProjectSample::generateCode(),
							'sent_date'		=> $request->sample_sent_date,
							'return_date'	=> $request->sample_return_date,
							'note'			=> $request->sample_note,
							'status'		=> '1',
							'approved_by_1' => 0,
							'approved_by_2' => 0
						]);
						
						foreach($request->sample_product_id as $key => $spi) {
							$psp = ProjectSampleProduct::create([
								'project_sample_id' => $projectSample->id,
								'product_id' 		=> $spi,
								'qty'        		=> $request->sample_qty[$key],
								'unit'       		=> $request->sample_unit[$key],
								'size'       		=> $request->sample_size[$key]
							]);
							
							$productdesc .= '. Detail products : '.$psp->product->name().', Qty : '.$psp->qty.' '.$psp->unit().', Size : '.$psp->size().'<br>';
                        }
						
						/* $debetcb = 341;
								
						$kreditcb = 332;
						
						$cb = CashBank::create([
							'user_id'     		=> session('bo_id'),
							'lookable_type'     => 'project_samples',
							'lookable_id'		=> $projectSample->id,
							'code'        		=> strtoupper(Str::random(15)),
							'date'        		=> $request->sample_sent_date,
							'type'        		=> '2',
							'description' 		=> 'Project Sample Code '.$projectSample->code.' in Project code '.$projectSample->project->code.' with Customer '.$projectSample->project->customer->name.' '.$productdesc
						]);
						
						if($cb){
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> $debetcb,
								'branch'		=> '1',
								'type'       	=> '1',
								'nominal'      	=> 1,
								'note'         	=> ''
							]);
							
							Journal::insert([
								'date_transaction' => $request->sample_sent_date,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => $debetcb,
								'branch'		   => '1',
								'type'	           => '1',
								'nominal'          => 1,
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
							
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> $kreditcb,
								'branch'		=> '1',
								'type'       	=> '2',
								'nominal'      	=> 1,
								'note'         	=> ''
							]);

							Journal::insert([
								'date_transaction' => $request->sample_sent_date,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => $kreditcb,
								'branch'		   => '1',
								'type'	           => '2',
								'nominal'          => 1,
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
						} */

						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details sample code '.$projectSample->code.' products has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						#send approval
						$roleapproval = array('5','9','10');
						Approval::sendApproval($roleapproval,'project_samples',$projectSample->id,'approved_by_1',session('bo_id'));
						Approval::sendApproval($roleapproval,'project_samples',$projectSample->id,'approved_by_2',session('bo_id'));
						#end approval
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 5)');
                        break;

                    case 'step-6':
                        $query->update([
                            'progress' => $query->progress < 35 ? 35 : $query->progress
                        ]);

                        ProjectNegotiation::whereNotIn('id',$request->negotiation_id)->delete();
						
                        foreach($request->negotiation_date as $key => $nd) {
							if($request->negotiation_id[$key] !== '0'){
								
							}else{
								ProjectNegotiation::create([
									'project_id' => $query->id,
									'date'       => $nd,
									'person'     => $request->negotiation_person[$key],
									'result'     => $request->negotiation_result[$key]
								]);
							}
                        }
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details negotiation report has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 6)');
                        break;

                    case 'step-7':
                        $query->update([
                            'progress' => $query->progress < 37 ? 37 : $query->progress
                        ]);
						
						if($request->temp_so_id){
							$projectSale = ProjectSale::find($request->temp_so_id);
							
							// if(!CheckCutOff::check($projectSale->sales->branch,substr($request->date_so,0,7))){
							// 	return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')
							// 				->withErrors('You cannot add/edit. The journal for this month was already closed.');
							// }
							
							if($projectSale->is_closed){
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')
											->withErrors('Sorry, this sales order was already closed. You can not edit it.');
							}
						}
						
						if($request->mid_yes_no == '1'){
							if($request->mid_type == '1'){
								if($query->budgetingProject()->exists()){
									foreach($query->budgetingProject as $budget){
										if(round($budget->percent_mid) !== round(floatval(str_replace(',','.',str_replace('.','',$request->mid_fee))))){
											return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')
											->withErrors('Sorry, your budgeting project middleman fee is not same with your middleman fee percentage. Please edit your budgeting middleman fee first.');
										}
									}
								}
							}
						}
						
						$delivery_cost = floatval(str_replace(',','.',str_replace('.','',$request->delivery_cost)));
						$cutting_cost = floatval(str_replace(',','.',str_replace('.','',$request->cutting_cost)));
						$misc_cost = floatval(str_replace(',','.',str_replace('.','',$request->misc_cost)));
						
						$project = Project::find($query->id)->update([
							'delivery_cost'	=> $delivery_cost,
							'cutting_cost'	=> $cutting_cost,
							'misc_cost'	=> $misc_cost
						]);
						
						if($request->temp_so_id){
							
							if($request->edit_reason){
								
								$budget = BudgetingProject::where('project_id',$query->id)->first();
								
								if($budget){
									
									// $diffproduct = false;
									// $countqtybg = $countqtypp = 0;
									// $productBudgeting = $productSales =[];
									

									// // check jika tipe barang dan qty di budgeting dan project apakah sama
									// foreach($budget->budgetingProjectProduct as $rowbg){
									// 	$countqtybg += floatval($rowbg->qty);
									// 	array_push($productBudgeting, $rowbg->product_id);
									// }
									
									// foreach($query->projectProduct as $rowpp){
									// 	$countqtypp += floatval($rowpp->qty);
									// 	array_push($productSales, $rowpp->product_id);
									// }

									// // check apakah harga barang antara budgeting dan project sama
									// foreach($budget->budgetingProjectProduct as $rowbg){
									// 	foreach($query->projectProduct as $rowpp){
									// 		if($rowbg->product_id == $rowpp->product_id){
									// 			if(round($rowbg->sell_price, 0) != round($rowpp->best_price, 0)){
									// 				$diffproduct = true;
									// 			}
									// 		}
									// 	}
									// }
									

									// sort($productBudgeting);
									// sort($productSales);
									
									// if(round($countqtybg,2) == round($countqtypp,2) && $productBudgeting == $productSales) {
										
									// }else{
									// 	$diffproduct = true;
									// }

									
									$countqtybg = $countqtypp = 0;
									$productBudgeting = $productSales = [];
									$diffproduct = false;

									// Sum qty dan push tipe barang dan harga ke array
									foreach ($budget->budgetingProjectProduct as $rowbg) {
										$countqtybg += floatval($rowbg->qty);
										$productBudgeting[$rowbg->product_id] = round($rowbg->sell_price, 0);
									}

									foreach ($query->projectProduct as $rowpp) {
										$countqtypp += floatval($rowpp->qty);
										$productSales[$rowpp->product_id] = round($rowpp->best_price, 0);
									}

									// Compare qty dan tipe ID produk
									if (round($countqtybg, 2) != round($countqtypp, 2) || count(array_diff_key($productBudgeting, $productSales)) > 0 || count(array_diff_key($productSales, $productBudgeting)) > 0) {
										$diffproduct = true;
									} 
									
									// Compare harga budgeting product dan harga penjualan real product
									foreach ($productBudgeting as $product_id => $sell_price) {
										if ($sell_price != $productSales[$product_id]) {
											$diffproduct = true;
										}
									}
									

									
									if($diffproduct == true){
										return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')
										->withErrors('Sorry, your budgeting project qty/ product/ price product does not same with current project product. Please edit your budgeting first.');
									}
								}
								
								$countDelivery = ProjectDelivery::where('project_id',$query->id)->count();
								
								if($countDelivery == 0){
								//if($query->progress <= 40){
								
									$projectSale = ProjectSale::find($request->temp_so_id);
									
									if($request->has('file')) {
										if(Storage::exists($projectSale->so_file)) {
											Storage::delete($projectSale->so_file);
										}

										$image = $request->file('file')->store('public/project');
									} else {
										$image = $projectSale->so_file;
									}
									
									$projectSale->so_file = $image;
									$projectSale->sales_id = $request->sales_id;
									$projectSale->address = $request->sales_address;
									$projectSale->note = $request->sales_note;
									$projectSale->ppn_cost = $request->ppn_cost;
									$projectSale->mid_yes_no = $request->mid_yes_no;
									$projectSale->mid_type = $request->mid_type;
									$projectSale->mid_fee = str_replace(',','.',str_replace('.','',$request->mid_fee));
									$projectSale->mid_note = $request->mid_note;
									$projectSale->delivery_cost = $delivery_cost;
									$projectSale->cutting_cost = $cutting_cost;
									$projectSale->misc_cost	= $misc_cost;
									$projectSale->approved_id = 7;
									$projectSale->created_at = $request->date_so.' 12:00:00';
									$projectSale->currency_id = $request->currency_id;
									$projectSale->currency_rate = str_replace(',','.',str_replace('.','',$request->currency_rate));
									$projectSale->letter_head  	= $request->letter_head_sales;
									
									$projectSale->update();
									
									ProjectSaleShading::where('project_sale_id',$request->temp_so_id)->delete();
									
									if($request->shading_product_id){
										foreach($request->shading_product_id as $key => $pi) {
											ProjectSaleShading::create([
												'project_sale_id'  	=> $request->temp_so_id,
												'product_id'   	   	=> $request->shading_product_id[$key],
												'warehouse_code'   	=> $request->shading_warehouse_code[$key],
												'stock_code'        => $request->shading_stock_code[$key],
												'code'         		=> $request->shading_code[$key],
												'qty'        		=> $request->shading_qty[$key]
											]);
										}
									}
									
									ProjectSale::find($projectSale->id)->updateGrandtotal();
									
									#start notif
									$role = array('1','2','3','4','5','6','7','9','10','11');
									$title = 'Project has been edited!';
									$description = 'Project '.$query->code.' with details project sale '.$projectSale->code.' and products has been edited by '.session('bo_name').' with reason : '.$request->edit_reason;
									$link = '#';
									Notification::sendNotif($role,$title,$description,$link);
									#end notif
									
									$this->addProjectLog(session('bo_id'),$query->id,'project_sales',$projectSale->id,0,'update',$request->edit_reason);
									
								/* }else{
									return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')
										->withErrors('This SO already has PO, please contact developer.');
								} */
								
								}else{
									/* return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')
										->withErrors('This SO already has Delivery, please create new SO or discuss with another member.'); */
									
									$projectSale = ProjectSale::find($request->temp_so_id);
									
									if($request->has('file')) {
										if(Storage::exists($projectSale->so_file)) {
											Storage::delete($projectSale->so_file);
										}

										$image = $request->file('file')->store('public/project');
									} else {
										$image = $projectSale->so_file;
									}
									
									$cek = ProjectSaleTemp::find($request->temp_so_id);
									if($cek){
										if($cek->delete()){
											$cek->projectSaleProductTemp()->delete();
											$cek->projectSaleShadingTemp()->delete();
										}
									}
									
									ProjectSaleTemp::create([
										'id'			=> $projectSale->id,
										'user_id'		=> session('bo_id'),
										'project_id'	=> $projectSale->project_id,
										'sales_id'		=> $request->sales_id,
										'code'			=> $projectSale->code,
										'address'		=> $request->sales_address,
										'note'			=> $request->sales_note,
										'so_file'		=> $image,
										'marketing_id'	=> $projectSale->marketing_id,
										'approved_id'	=> $projectSale->approved_id,
										'delivery_cost'	=> $delivery_cost,
										'cutting_cost'	=> $cutting_cost,
										'misc_cost'		=> $misc_cost,
										'misc_note'		=> null,
										'ppn_cost'		=> $request->ppn_cost,
										'mid_yes_no'	=> $request->mid_yes_no,
										'mid_type'		=> $request->mid_type,
										'mid_fee'		=> str_replace(',','.',str_replace('.','',$request->mid_fee)),
										'mid_director'	=> $projectSale->mid_director,
										'mid_note'		=> $request->mid_note,
										'currency_id'	=> $request->currency_id,
										'currency_rate'	=> str_replace(',','.',str_replace('.','',$request->currency_rate)),
										'created_at' 	=> $request->date_so.' 12:00:00',
										'letter_head'  	=> $request->letter_head_sales,
										'reason'		=> $request->edit_reason,
										'status'		=> '0'
									]);
									
									if($request->shading_product_id){
										foreach($request->shading_product_id as $key => $pi) {
											ProjectSaleShadingTemp::create([
												'project_sale_id'  	=> $request->temp_so_id,
												'product_id'   	   	=> $request->shading_product_id[$key],
												'warehouse_code'   	=> $request->shading_warehouse_code[$key],
												'stock_code'        => $request->shading_stock_code[$key],
												'code'         		=> $request->shading_code[$key],
												'qty'        		=> $request->shading_qty[$key]
											]);
										}
									}
									
									$dataProduct = ProjectProduct::where('project_id', $query->id)->get();
						
									foreach($dataProduct as $ps) {
										$cek = ProjectSaleProductTemp::where('project_sale_id',$projectSale->id)->where('product_id',$ps->product_id)->first();
										
										if($cek){
											$cek->update([
												'qty' => $cek->qty + $ps->qty,
											]);
										}else{
											ProjectSaleProductTemp::create([
												'project_sale_id'   => $projectSale->id,
												'product_id'   		=> $ps->product_id,
												'area'   	   		=> $ps->area,
												'spec'   	   		=> $ps->spec,
												'qty'          		=> $ps->qty,
												'cogs'        		=> $ps->cogs,
												'price'        		=> $ps->price,
												'recommended_price'	=> $ps->recommended_price == 0 ? $ps->price : $ps->recommended_price,
												'best_price'		=> $ps->best_price == 0 ? ($ps->recommended_price == 0 ? $ps->price : $ps->recommended_price) : $ps->best_price,
												'discount'			=> $ps->discount,
												'unit'         		=> $ps->unit
											]);
										}
									}
									
									$roleapproval = array('4');
									Approval::sendApproval($roleapproval,'project_sales_temps',$projectSale->id,'approved_by',session('bo_id'));
									
									SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Sales Project Revision No '.$projectSale->code.' Project No. '.$projectSale->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
									
									return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')->with(['success' => 'Data successfully saved. Waiting for approval.']);
								}
							}else{
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')
										->withErrors('Please describe your reason.');
							}
							
						}else{

							if(count($query->projectSale) > 0){
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-7=1#step-7')
								->withErrors('Sorry Sales Order already exists, if you want make change please edit the existing SO Or Create New Project.');
							}

							$projectSale = ProjectSale::create([
								'user_id'		=> session('bo_id'),
								'project_id' 	=> $query->id,
								'sales_id'		=> $request->sales_id,
								'code' 		 	=> ProjectSale::generateCode(),
								'address'		=> $request->sales_address,
								'note'			=> $request->sales_note,
								'so_file'		=> $request->file('file') ? $request->file('file')->store('public/project') : '',
								'marketing_id' 	=> 0,
								'approved_id' 	=> 0,
								'delivery_cost'	=> $delivery_cost,
								'cutting_cost'	=> $cutting_cost,
								'misc_cost'		=> $misc_cost,
								'ppn_cost'		=> $request->ppn_cost,
								'mid_yes_no'	=> $request->mid_yes_no,
								'mid_type'		=> $request->mid_type,
								'mid_fee'		=> str_replace(',','.',str_replace('.','',$request->mid_fee)),
								'mid_director'	=> 0,
								'mid_note'		=> $request->mid_note,
								'currency_id'	=> $request->currency_id,
								'currency_rate'	=> str_replace(',','.',str_replace('.','',$request->currency_rate)),
								'approved_id'	=> 7,
								'created_at'	=> $request->date_so.' 12:00:00',
								'letter_head'  	=> $request->letter_head_sales,
							]);
							
							ProjectSaleShading::where('project_sale_id',$request->temp_so_id)->delete();
							
							if($request->shading_product_id){
								foreach($request->shading_product_id as $key => $pi) {
									ProjectSaleShading::create([
										'project_sale_id'  	=> $projectSale->id,
										'product_id'   	   	=> $request->shading_product_id[$key],
										'warehouse_code'   	=> $request->shading_warehouse_code[$key],
										'stock_code'        => $request->shading_stock_code[$key],
										'code'         		=> $request->shading_code[$key],
										'qty'        		=> $request->shading_qty[$key]
									]);
								}
							}
							
							#start notif
							$role = array('1','2','3','4','5','6','7','9','10','11');
							$title = 'Project has been updated!';
							$description = 'Project '.$query->code.' with details project sale '.$projectSale->code.' and products has been updated by '.session('bo_name');
							$link = '#';
							Notification::sendNotif($role,$title,$description,$link);
							#end notif
							
							#send approval
							if(session('bo_branch') == '1'){
								$roleapproval = array('5');
								Approval::sendApproval($roleapproval,'project_sales',$projectSale->id,'marketing_id',session('bo_id'));
								
								SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Sales Project No '.$projectSale->code.' Project No. '.$projectSale->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
							}else{
								$roleapproval = 16; #if user jakarta
								Approval::sendApproval($roleapproval,'project_sales',$projectSale->id,'marketing_id',session('bo_id'));
								
								SendMessage::send(env('SALES_MANAGER_JAKARTA_PHONE'),'Halo pak/bu. Mohon dibantu approve Sales Project No '.$projectSale->code.' Project No. '.$projectSale->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
							}
							
							$roleapproval = array('4');
							Approval::sendApproval($roleapproval,'project_sales',$projectSale->id,'approved_id',session('bo_id'));
							
							SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Sales Project No '.$projectSale->code.' Project No. '.$projectSale->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
							
							// if($request->mid_yes_no == '1'){
							// 	$roleapproval = array('4');
							// 	Approval::sendApproval($roleapproval,'project_sales',$projectSale->id,'mid_director',session('bo_id'));
							// }
							#end approval
						}
						
						ProjectSaleProduct::where('project_sale_id',$request->temp_so_id)->delete();
						
						$dataProduct = ProjectProduct::where('project_id', $query->id)->get();
						
						foreach($dataProduct as $ps) {
							$cek = ProjectSaleProduct::where('project_sale_id',$projectSale->id)->where('product_id',$ps->product_id)->first();
							
							if($cek){
								$cek->update([
									'qty' => $cek->qty + $ps->qty,
								]);
							}else{
								ProjectSaleProduct::create([
									'project_sale_id'   => $projectSale->id,
									'product_id'   		=> $ps->product_id,
									'area'   	   		=> $ps->area,
									'spec'   	   		=> $ps->spec,
									'qty'          		=> $ps->qty,
									'cogs'        		=> $ps->cogs,
									'price'        		=> $ps->price,
									'recommended_price'	=> $ps->recommended_price == 0 ? $ps->price : $ps->recommended_price,
									'best_price'		=> $ps->best_price == 0 ? ($ps->recommended_price == 0 ? $ps->price : $ps->recommended_price) : $ps->best_price,
									'discount'			=> $ps->discount,
									'unit'         		=> $ps->unit
								]);
							}
                        }
						
						ProjectSale::find($projectSale->id)->updateGrandtotal();
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
							->withProperties($projectSale)
                            ->log('Change data project ' . $query->name . ' (Step 7)');
                        break;
					
					case 'step-8':
						
						
						
                    case 'step-9':
                        $query->update([
                            'progress' => $query->progress < 43 ? 43 : $query->progress
                        ]);
						
						if($request->temp_po_id){
							
							if($request->edit_reason_po){
									
								$projectpurchase = ProjectPurchase::find($request->temp_po_id);
								
								/* if(count($projectpurchase->projectWarehouse) > 0){
									return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-9=1#step-9')
									->withErrors('This Purchase Order is already have(s) Warehouse Receive. Sorry.');
								}else{ */
									$projectpurchase->user_id = session('bo_id');
									$projectpurchase->project_id = $query->id;
									$projectpurchase->project_sale_id = $request->so_id;
									$projectpurchase->ppn = $request->ppn;
									$projectpurchase->note = $request->sales_note;
									$projectpurchase->fee_pta = $request->fee_pta;
									$projectpurchase->percent_fee_pta = $request->percent_fee_pta;
									$projectpurchase->supplier_id = $request->supplier_id;
									$projectpurchase->production_lead_time = $request->production_lead_time;
									$projectpurchase->estimated_delivery = $request->est_delivery_date;
									$projectpurchase->estimated_arrival = $request->est_arrival_date;
									$projectpurchase->factory_name = $request->factory_name;
									$projectpurchase->customer_id = $request->customer_id;
									$projectpurchase->sales_id = $request->sales_po;
									$projectpurchase->on_behalf = $request->on_behalf;
									$projectpurchase->delivery_address	= $request->delivery_address;
									$projectpurchase->country_id = $request->country_id;
									$projectpurchase->city_id	= $request->city_id;
									$projectpurchase->courier_method = $request->courier_method;
									$projectpurchase->pic = $request->pic_name;
									$projectpurchase->pic_no = $request->pic_number;
									$projectpurchase->payment_method = $request->payment_method;
									$projectpurchase->payment_due_date = $request->payment_due_date;
									$projectpurchase->price = $request->price;
									$projectpurchase->currency_id = $request->currency;
									$projectpurchase->currency_rate = str_replace(',','.',str_replace('.','',$request->currency_rate));
									$projectpurchase->brand_on_box = $request->brand;
									$projectpurchase->sni = $request->sni;
									$projectpurchase->is_wip = $request->is_wip;
									$projectpurchase->has_memo_item = $request->has_memo_item;
									$projectpurchase->memo_address_item = $request->memo_address_item;
									$projectpurchase->memo_up = $request->memo_up;
									$projectpurchase->checked_by = 0;
									$projectpurchase->created_at = $request->purchase_date.' '.date('H:i:s');
									$projectpurchase->approved_by = 0;
									
									$projectpurchase->update();
								
									#start notif
									$role = array('1','2','3','4','5','6','7','9','10','11');
									$title = 'Project has been edited!';
									$description = 'Project '.$query->code.' with details project purchase code '.$projectpurchase->code.' has been edited by '.session('bo_name').' with reason : '.$request->edit_reason_po;
									$link = '#';
									Notification::sendNotif($role,$title,$description,$link);
									#end notif
									
									$this->addProjectLog(session('bo_id'),$query->id,'project_purchases',$projectpurchase->id,0,'update',$request->edit_reason_po);
									// Approval::where('approvalable_type','project_purchases')->where('approvalable_id',$projectpurchase->id)->delete();

									// foreach ($projectpurchase->projectpurchaseproduct as $row) {
									// 	foreach($request->product_id as $key => $pi) {
									// 		if($row->product_id == 	$pi){
									// 			if(round($row->priceBudget(), 2) < round($request->product_price[$key],2)){
									// 				Approval::where('approvalable_type','project_purchases')->where('approvalable_id',$projectpurchase->id)->delete();
									// 			}
									// 		}
									// 	}
									// }	
									
								//}
							
							}else{
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-9=1#step-9')
										->withErrors('Please describe your reason.');
							}
						}else{
							$projectpurchase = ProjectPurchase::create([
								'user_id'					=> session('bo_id'),
								'project_id' 				=> $query->id,
								'project_sale_id' 			=> $request->so_id,
								'ppn'						=> $request->ppn,
								'code'						=> ProjectPurchase::generateCode(),
								'note' 		 				=> $request->sales_note,
								'fee_pta' 		 			=> $request->fee_pta,
								'percent_fee_pta' 		 	=> $request->percent_fee_pta,
								'supplier_id'				=> $request->supplier_id,
								'production_lead_time'		=> $request->production_lead_time,
								'estimated_delivery'		=> $request->est_delivery_date,
								'estimated_arrival' 		=> $request->est_arrival_date,
								'factory_name' 				=> $request->factory_name,
								'customer_id'				=> $request->customer_id,
								'sales_id'					=> $request->sales_po,
								'on_behalf'					=> $request->on_behalf,
								'delivery_address'			=> $request->delivery_address,
								'country_id' 				=> $request->country_id,
								'city_id' 					=> $request->city_id,
								'courier_method'			=> $request->courier_method,
								'pic'						=> $request->pic_name,
								'pic_no'					=> $request->pic_number,
								'payment_method'			=> $request->payment_method,
								'payment_due_date'			=> $request->payment_due_date,
								'price'						=> $request->price,
								'currency_id'				=> $request->currency,
								'currency_rate'				=> str_replace(',','.',str_replace('.','',$request->currency_rate)),
								'brand_on_box'				=> $request->brand,
								'sni'						=> $request->sni,
								'is_wip'					=> $request->is_wip,
								'has_memo_item' 			=> $request->has_memo_item,
								'memo_address_item' 		=> $request->memo_address_item,
								'memo_up' 					=> $request->memo_up,
								'checked_by'				=> 0,
								'approved_by'				=> 0,
								'created_at' 				=> $request->purchase_date.' '.date('H:i:s')
							]);
						}
						
						ProjectPurchaseProduct::where('project_purchase_id',$request->temp_po_id)->delete();
						ProjectPurchaseSplit::where('project_purchase_id',$request->temp_po_id)->delete();
						
						foreach($request->product_id as $key => $pi) {
							ProjectPurchaseProduct::create([
								'project_purchase_id' 	=> $projectpurchase->id,
								'product_id'  	 		=> $request->product_id[$key],
								'qty'  					=> $request->product_qty[$key],
								'unit'					=> $request->product_unit[$key],
								'price'					=> round(str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_price[$key]))),2),
								'remark'				=> $request->product_remark[$key]
							]);
                        }
						
						if($request->product_id_split){
							foreach($request->product_id_split as $key => $pis) {
								if($request->product_qty_split[$key] > 0){
									ProjectPurchaseSplit::create([
										'project_purchase_id' 			=> $projectpurchase->id,
										'project_purchase_reference' 	=> $request->product_purchase_order_split[$key],
										'product_id'  	 				=> $pis,
										'qty'  							=> $request->product_qty_split[$key],
										'unit'							=> $request->product_unit_split[$key],
										'price'							=> round(str_replace(',','.',str_replace('.','',$request->product_price_split[$key])),2)
									]);
								}
							}
						}
						
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details purchase code '.$projectpurchase->code.' has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						if($request->is_wip == '0'){
							$isHigher = false;

							if(date('Y-m-d',strtotime($projectpurchase->created_at)) < '2022-04-01'){
								$ppnpembagi = 1.1;
							}else{
								$ppnpembagi = 1.11;
							}

							foreach ($projectpurchase->projectPurchaseProduct as $row) {
								foreach($request->product_id as $key => $pi) {
									if($row->product_id == 	$pi){
										$budgetPrice = round($row->priceBudget(), 0);
										$price = str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_price[$key])));
										$buyPrice =  $projectpurchase->ppn == 1 ? round($price/$ppnpembagi, 0) : round($price, 0);

										if($budgetPrice < $buyPrice){
											$approval = Approval::where('approvalable_type','project_purchases')->where('approvalable_id',$projectpurchase->id)->get();
											$isHigher = true;

											if(count($approval) > 0){
												foreach ($approval as $row) {
													$row->delete();
												}
												break;
											}else{
												break;
											}
										}
									}
								}
							}	

							if($isHigher){
								#send approval
								$roleapproval = array('5');
								Approval::sendApproval($roleapproval,'project_purchases',$projectpurchase->id,'checked_by',session('bo_id'));
								$roleapproval = array('4');
								Approval::sendApproval($roleapproval,'project_purchases',$projectpurchase->id,'approved_by',session('bo_id'));
								#end approval	
								
								SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Purchase No '.$projectpurchase->code.' Project No. '.$projectpurchase->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
								
								SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Purchase No '.$projectpurchase->code.' Project No. '.$projectpurchase->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
							}else{
								$projectpurchase->update([
									'approved_by' => '7'
								]);
							}
						}
						
						ProjectPurchase::find($projectpurchase->id)->updateGrandtotal();
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 9)');
                        break;
					
					case 'step-10':
                        $query->update([
                            'progress' => $query->progress < 45 ? 45 : $query->progress
                        ]);
						
						if($request->temp_proforma){
							$data = ProjectProforma::find($request->temp_proforma);
							
							if($request->has('file')) {
								if(Storage::exists($data->image)) {
									Storage::delete($data->image);
								}

								$image = $request->file('file')->store('public/project');
							} else {
								$image = $data->image;
							}
							
							$data->update([
								'project_id' 			=> $query->id,
								'project_purchase_id' 	=> $request->pop_id,
								'image'      			=> $image,
								'date'       			=> $request->date,
								'supplier_name'    		=> $request->supplier_name,
								'supplier_warehouse'    => $request->supplier_warehouse,
								'note'					=> $request->note
							]);
						}else{
							ProjectProforma::create([
								'project_id' 			=> $query->id,
								'project_purchase_id' 	=> $request->pop_id,
								'image'      			=> $request->file('file') ? $request->file('file')->store('public/project') : '',
								'date'       			=> $request->date,
								'supplier_name'    		=> $request->supplier_name,
								'supplier_warehouse'    => $request->supplier_warehouse,
								'note'					=> $request->note
							]);
						}
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details proforma from '.$request->supplier_name.' has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 10)');
                        break;
					
                    case 'step-11':
						

                    case 'step-12':
                        $query->update([
                            'progress' => $query->progress < 50 ? 50 : $query->progress
                        ]);

                        $pp = ProjectProduction::create([
                            'project_id'  			=> $query->id,
							'project_purchase_id'	=> $request->pr_id,
                            'image'       			=> $request->file('file') ? $request->file('file')->store('public/project') : '',
                            'start_date'  			=> $request->start_date,
                            'finish_date' 			=> $request->finish_date,
                            'note'        			=> $request->note,
							'progress'	  			=> $request->progress_production
                        ]);
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details progress production and purchase code '.$pp->projectPurchase->code.' has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 12)');
                        break;

                    case 'step-13':
                        

                    case 'step-14':
                        $query->update([
                            'progress' => $query->progress < 60 ? 60 : $query->progress
                        ]);
						
						if($request->temp_shipment_id){
							
							if($request->edit_reason_shipment){
								
								$projectshipment = ProjectShipment::find($request->temp_shipment_id);
								
								if($request->has('file')) {
									if(Storage::exists($projectshipment->image)) {
										Storage::delete($projectshipment->image);
									}

									$image = $request->file('file')->store('public/project');
								} else {
									$image = $projectshipment->image;
								}
								
								$projectshipment->project_purchase_id	= $request->pos_id;
								$projectshipment->shipment_code			= $request->shipment_code;
								$projectshipment->loading_date   		= $request->loading_date;
								$projectshipment->departure_date 		= $request->departure_date;
								$projectshipment->from_port      		= $request->from_port;
								$projectshipment->to_port        		= $request->to_port;
								$projectshipment->eta            		= $request->eta;
								$projectshipment->delivery_method		= $request->delivery_method;
								$projectshipment->note           		= $request->note;
								$projectshipment->gambar      	 		= $image;
								
								$projectshipment->update();
								
								foreach($projectshipment->projectShipmentProduct as $row){
									$row->delete();
								}
								
								#start notif
								$role = array('1','2','3','4','5','6','7','9','10','11');
								$title = 'Project has been edited!';
								$description = 'Project '.$query->code.' with details shipment code '.$request->shipment_code.' has been edited by '.session('bo_name').' with reason : '.$request->edit_reason_shipment;
								$link = '#';
								Notification::sendNotif($role,$title,$description,$link);
								#end notif
								
								$this->addProjectLog(session('bo_id'),$query->id,'project_shipments',$projectshipment->id,0,'update',$request->edit_reason_shipment);
								
							}else{
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-14=1#step-14')
										->withErrors('Please describe your reason.');
							}
							
						}else{
							
							if($request->has('file')) {
								$image = $request->file('file')->store('public/project');
							}else{
								$image = '';
							}
							
							$projectshipment = ProjectShipment::create([
								'project_id'     		=> $query->id,
								'project_purchase_id'	=> $request->pos_id,
								'shipment_code'			=> $request->shipment_code,
								'loading_date'   		=> $request->loading_date,
								'departure_date' 		=> $request->departure_date,
								'from_port'      		=> $request->from_port,
								'to_port'        		=> $request->to_port,
								'eta'            		=> $request->eta,
								'delivery_method'		=> $request->delivery_method,
								'note'           		=> $request->note,
								'gambar'      	 		=> $image
							]);
							
							#start notif
							$role = array('1','2','3','4','5','6','7','9','10','11');
							$title = 'Project has been updated!';
							$description = 'Project '.$query->code.' with details shipment code '.$request->shipment_code.' in purchase code '.$projectshipment->projectPurchase->code.' has been updated by '.session('bo_name');
							$link = '#';
							Notification::sendNotif($role,$title,$description,$link);
							#end notif
						}
						
						foreach($request->product_id as $key => $pi) {
							ProjectShipmentProduct::create([
								'project_shipment_id' 	=> $projectshipment->id,
								'product_id'  	 		=> $request->product_id[$key],
								'qty'  					=> $request->product_qty[$key],
								'unit'					=> $request->product_unit[$key]
							]);
                        }
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
							->withProperties($projectshipment)
                            ->log('Change data project ' . $query->name . ' (Step 14)');
                        break;
					
					case 'step-15':
					
						$query->update([
                            'progress' => $query->progress < 65 ? 65 : $query->progress
                        ]);
						
						$projectpurchase = ProjectPurchase::find($request->posw_id);

						foreach($request->product_id as $key => $pi) {
							$qty_po = $projectpurchase->productAlreadyExist($request->product_id[$key]);

							if($request->temp_warehouse_id){
								$projectPurchaseQty = ProjectPurchaseProduct::where('project_purchase_id', $projectpurchase->id)->where('product_id', $request->product_id[$key])->sum('qty');

								if($projectPurchaseQty < $request->product_qty[$key]){
									return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-15=1#step-15')
												->withErrors('quantity exceeds PO quantity.');
								}
							}else{
								if($qty_po < $request->product_qty[$key]){
									return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-15=1#step-15')
												->withErrors('quantity exceeds PO quantity.');
								}
							}
						
						}

						if(!CheckCutOff::check($projectpurchase->sales->branch,substr($request->date_receive,0,7))){
							return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-15=1#step-15')
										->withErrors('You cannot add/edit. The journal for this month was already closed.');
						}
						
						if($request->temp_warehouse_id){
							
							if($request->edit_reason_warehouse){
								
								$projectwarehouse = ProjectWarehouse::find($request->temp_warehouse_id);
								
								if($request->has('file')) {
									if(Storage::exists($projectwarehouse->image)) {
										Storage::delete($projectwarehouse->image);
									}

									$image = $request->file('file')->store('public/project');
								} else {
									$image = $projectwarehouse->image;
								}

								foreach($projectwarehouse->projectWarehouseProduct as $row){
									#updatestock
									$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$projectwarehouse->warehouse_id)->where('branch',$projectwarehouse->projectPurchase->sales->branch)->first();
									if($cek){
										if($cek->qty > 0){
											$cek->update([
												'qty' 	=> $cek->qty - $row->qty,
												'unit'	=> $row->unit
											]);
										}
									}
									
									$row->delete();
								}
								
								$projectwarehouse->user_id				= session('bo_id');
								$projectwarehouse->project_purchase_id	= $request->posw_id;
								$projectwarehouse->project_shipment_id  = $request->shipment_id;
								$projectwarehouse->person 				= $request->person;
								$projectwarehouse->date_receive      	= $request->date_receive;
								$projectwarehouse->warehouse_id  	 	= $request->warehouse_id;
								$projectwarehouse->include_cost			= $request->include_cost;
								$projectwarehouse->coa_id				= $request->include_cost == '0' ? $request->wr_coa : null;
								$projectwarehouse->image      	 		= $image;
								
								$projectwarehouse->update();
								
								
								
								$cb = CashBank::where('lookable_type','project_warehouses')->where('lookable_id',$request->temp_warehouse_id)->get();
								
								foreach($cb as $row){
									$row->deleteDetail();
									$row->delete();
								}
								
								$prcek = PurchaseRequest::where('project_warehouse_id',$request->temp_warehouse_id)->get();
								
								foreach($prcek as $rowcek){
									foreach($rowcek->purchaseRequestPayment() as $rowpay){
										$rowpay->deleteFile();
										$rowpay->delete();
									}
									$rowcek->deleteFile();
									$rowcek->delete();
								}
								
								#start notif
								$role = array('1','2','3','4','5','6','7','9','10','11');
								$title = 'Project has been updated!';
								$description = 'Project '.$query->code.' with details warehouse code '.$projectwarehouse->code.' has been edited by '.session('bo_name').'.';
								$link = '#';
								Notification::sendNotif($role,$title,$description,$link);
								#end notif
								
								$this->addProjectLog(session('bo_id'),$query->id,'project_warehouses',$projectwarehouse->id,0,'update',$request->edit_reason_warehouse);
								
							}else{
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-15=1#step-15')
										->withErrors('Please describe your reason.');
							}
							
						}else{

							$projectwarehouse = ProjectWarehouse::create([
								'user_id'				=> session('bo_id'),
								'project_id'     		=> $query->id,
								'project_purchase_id'	=> $request->posw_id,
								'project_shipment_id'	=> $request->shipment_id,
								'code'					=> ProjectWarehouse::generateCode(),
								'image'      	 		=> $request->file('file')->store('public/project'),
								'date_receive' 	 		=> $request->date_receive,
								'warehouse_id'  	 	=> $request->warehouse_id,
								'person'         		=> $request->person,
								'include_cost'			=> $request->include_cost,
								'coa_id'				=> $request->include_cost == '0' ? $request->wr_coa : null
							]);
							
							#start notif
							$role = array('1','2','3','4','5','6','7','9','10','11');
							$title = 'Project has been updated!';
							$description = 'Project '.$query->code.' with details purchase code '.$projectwarehouse->projectPurchase->code.' has been received by '.$request->person.' with warehouse receive code '.$projectwarehouse->code.'.';
							$link = '#';
							Notification::sendNotif($role,$title,$description,$link);
							#end notif
						}
						


						foreach($request->product_id as $key => $pi) {

							ProjectWarehouseProduct::create([
								'project_warehouse_id' 	=> $projectwarehouse->id,
								'product_id'  	 		=> $request->product_id[$key],
								'qty'  					=> $request->product_qty[$key],
								'unit'					=> $request->product_unit[$key],
								'qty_broken'			=> $request->product_qty_broken[$key],
								'unit_broken'			=> $request->product_unit[$key],
							]);
							
							#updatestock
							$cek = Stock::where('product_id',$request->product_id[$key])->where('warehouse_id',$request->warehouse_id)->where('branch',$projectwarehouse->projectPurchase->sales->branch)->first();
							if($cek){
								$cek->update([
									'qty' 	=> $cek->qty + $request->product_qty[$key],
									'unit'	=> $request->product_unit[$key]
								]);
							}else{
								Stock::create([
									'product_id'	=> $request->product_id[$key],
									'warehouse_id'	=> $request->warehouse_id,
									'qty'			=> $request->product_qty[$key],
									'unit'			=> $request->product_unit[$key],
									'branch'		=> $projectwarehouse->projectPurchase->sales->branch
								]);
							}
                        }
						
						$pp = ProjectPurchase::find($request->posw_id);
						
						if(date('Y-m-d',strtotime($pp->created_at)) < '2022-04-01'){
							$persenppn = 0.1;
							$ppnpembagi = 1.1;
						}else{
							$persenppn = 0.11;
							$ppnpembagi = 1.11;
						}
						
						$totalPurchase = round($projectwarehouse->getTotal()['totalpurchase']);
						$totalUnder = 0;
						
						$projectdetail = $pp->id;
						$reference = '2'; #is purchase
						$type = '2'; #journal
						$description = 'Warehouse receive '.$projectwarehouse->code.' From PO Code '.$pp->code;
						
						$debetcb = $request->include_cost == '0' && !$projectwarehouse->projectPurchase->purchaseCost()->exists() ? $request->wr_coa : 31;
						
						if($pp->ppn == '1'){
							$debetnominal = round($totalPurchase / $ppnpembagi,0);
							$ppnmasukan = 50;
							$ppnnominal = round($totalPurchase - ($totalPurchase / $ppnpembagi),0);
						}else{
							$debetnominal = round($totalPurchase,0);
						}
						
						ProjectWarehouse::find($projectwarehouse->id)->updateGrandtotal();
						
						#START
						
						$cb = CashBank::create([
							'user_id'     		=> session('bo_id'),
							'lookable_type'  	=> 'project_warehouses',
							'lookable_id'		=> $projectwarehouse->id,
							'code'        		=> strtoupper(Str::random(15)),
							'date'        		=> $request->date_receive,
							'type'        		=> $type,
							'description' 		=> $description
						]);
						
						if($cb){
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> $debetcb,
								'branch'		=> $pp->sales->branch,
								'type'       	=> '1',
								'nominal'      	=> $debetnominal,
								'note'         	=> ''
							]);
							
							Journal::insert([
								'date_transaction' => $request->date_receive,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => $debetcb,
								'branch'		   => $pp->sales->branch,
								'type'	           => '1',
								'nominal'          => $debetnominal,
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
							
							#if purchase ppn
							if($pp->ppn == '1'){
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $ppnmasukan,
									'branch'		=> $pp->sales->branch,
									'type'       	=> '1',
									'nominal'      	=> $ppnnominal,
									'note'         	=> ''
								]);
								
								Journal::insert([
									'date_transaction' => $request->date_receive,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $ppnmasukan,
									'branch'		   => $pp->sales->branch,
									'type'	           => '1',
									'nominal'          => $ppnnominal,
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
							}
							
							$pc = PurchaseCost::where('project_purchase_id',$pp->id)->first();
							
							if($pc){
								$totalsisa = $pc->totalCost();
								
								foreach($pp->projectWarehouse()->where('id','<>',$projectwarehouse->id)->get() as $row){
									$totalsisa -= round($row->getTotal()['totalpurchase']);
								}
								
								$totalcurrent = round($projectwarehouse->getTotal()['totalpurchase']);
								
								if($totalsisa >= $totalcurrent){
									$kreditcb = $pc->coa_id;
								
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $totalcurrent,
										'note'         	=> ''
									]);

									Journal::insert([
										'date_transaction' => $request->date_receive,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $pp->sales->branch,
										'type'	           => '2',
										'nominal'          => $totalcurrent,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
								
								if($totalcurrent > $totalsisa){
									$kreditcb = $pc->coa_id;
								
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $totalsisa,
										'note'         	=> ''
									]);

									Journal::insert([
										'date_transaction' => $request->date_receive,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $pp->sales->branch,
										'type'	           => '2',
										'nominal'          => $totalsisa,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
									$kreditcb = 332;
								
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $totalcurrent - $totalsisa,
										'note'         	=> ''
									]);

									Journal::insert([
										'date_transaction' => $request->date_receive,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $pp->sales->branch,
										'type'	           => '2',
										'nominal'          => $totalcurrent - $totalsisa,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
								
							}else{
								$totalsisa = $totalPurchase;
								
								foreach($pp->projectPurchasePayment as $ppp){
									$totalsisa -= $ppp->nominal;
									
									$kreditcb = 24;
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $ppp->projectPurchase->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $totalsisa >= 0 ? $ppp->nominal : $totalsisa + $ppp->nominal,
										'note'         	=> ''
									]);

									Journal::insert([
										'date_transaction' => $request->date_receive,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $ppp->projectPurchase->sales->branch,
										'type'	           => '2',
										'nominal'          => $totalsisa >= 0 ? $ppp->nominal : $totalsisa + $ppp->nominal,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
								
								if($totalsisa > 0){
									
									$kreditcb = 332;
								
									$kreditnominal = round($totalsisa);
											
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $kreditnominal,
										'note'         	=> ''
									]);

									Journal::insert([
										'date_transaction' => $request->date_receive,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $pp->sales->branch,
										'type'	           => '2',
										'nominal'          => $kreditnominal,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
							}
							
						}
						
						if(isset($pc)){
				
						}else{
							$totalbill = 0;
							foreach($pp->projectPurchaseBill as $rowbill){
								foreach(PurchaseRequest::where('project_purchase_bill_id',$rowbill->id)->get() as $rowpr){
									$totalbill += $rowpr->total_nominal;
								}
							}
							
							$balance = $totalPurchase - $totalbill;
							
							if($balance > 0 && !$pp->purchaseCost()->exists()){
								#purchaserequestlink
								$pr = PurchaseRequest::create([
									'date'	     				=> $request->date_receive,
									'user_id'					=> session('bo_id'),
									'coa_id'					=> 332,
									'branch'					=> $pp->sales->branch,
									'title'						=> 'PROJECT PURCHASE PAYMENT PO NO. : '.$pp->code,
									'item'						=> 'PROJECT PURCHASE PAYMENT REQUEST NUMBER '.$pp->project->code.' - '.$pp->supplier->name.' - '.$pp->projectSale->code.' with PO NO. : '.$pp->code.' with WR NO. '.$projectwarehouse->code.' CUSTOMER : '.$pp->project->customer->name.' AUTOMATICALLY BY SYSTEM TJS.',
									'image'						=> $request->file('purchase-request-file') ? $request->file('purchase-request-file')->store('public/purchase') : '',
									'total_nominal'				=> round($balance),
									'status'					=> 'PEND',
									'link_type'					=> 'project_purchases',
									'link_id'					=> $pp->id,
									'project_warehouse_id'		=> $projectwarehouse->id,
									'due_date'					=> $pp->payment_due_date ? $pp->payment_due_date : NULL
								]);
								
								$roleapproval = array('4');
								Approval::sendApproval($roleapproval,'purchase_requests',$pr->id,'approved_by',session('bo_id'));
								
								SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No PR-'.$pr->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
								
								#END
							}
						}
						
						#update cogs
						
						ProductCogs::updateCogs(substr($projectwarehouse->date_receive,0,10),$pp->sales->branch);
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 15)');
						break;
						
					case 'step-16':
						
						$query->update([
                            'progress' => $query->progress < 80 ? 80 : $query->progress
                        ]);
						
						if($request->temp_delivery_id){
							
							if($request->edit_reason_delivery){
							
								$cek = ProjectDelivery::find($request->temp_delivery_id);
								
								if($cek->received_date){
									return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-16=1#step-16')
										->withErrors('Ups. Sorry this delivery already received by customer.');
								}
								
								if($request->has('file')) {
									if($cek->image){
										if(Storage::exists($cek->image)) {
											Storage::delete($cek->image);
										}

										$image = $request->file('file')->store('public/project');
									}else{
										$image = '';
									}
								} else {
									$image = $cek->image;
								}
								
								$projectdelivery = ProjectDelivery::find($request->temp_delivery_id)->update([
									'user_id'				=> session('bo_id'),
									'project_id'    		=> $query->id,
									'project_sale_id'    	=> $request->sod_id,
									'city_id'       		=> $request->city_id2,
									'receiver_name' 		=> $request->receiver_name,
									'delivery_date' 		=> $request->delivery_date,
									'email'         		=> $request->email,
									'phone'        			=> $request->phone,
									'is_dropshipper'        => $request->dropshipper,
									'dropshipper_id'        => $request->dropshipper_id ? $request->dropshipper_id : 0,
									'address'       		=> $request->address,
									'warehouse_id'         	=> $request->warehousedeliver_id,
									'vendor_id'				=> $request->expedition_id,
									'image'       			=> $image,
									'is_sales'				=> $request->is_sales,
									'pick_up_name'			=> $request->pick_up_name,
									'pick_up_plat'			=> $request->pick_up_plat,
									'pick_up_vehicle'		=> $request->pick_up_vehicle,
									'service_note'			=> $request->service_note,
									'invoice_note'			=> $request->invoice_note,
									'break_tolerance'		=> $request->break_tolerance ? $request->break_tolerance : NULL,
									
								]);
								
								ProjectDeliveryProduct::where('project_delivery_id',$request->temp_delivery_id)->delete();
								
								$projectdelivery = ProjectDelivery::find($request->temp_delivery_id);
								
							}else{
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-16=1#step-16')
										->withErrors('Please describe your reason.');
							}
							
						}else{
							
							$projectsale = ProjectSale::find($request->sod_id);
							
							$stokkurang = false;
						
							foreach($request->product_id as $key => $pi) {
								
								$cek = Stock::where('product_id',$request->product_id[$key])->where('warehouse_id',$request->warehousedeliver_id)->where('branch',$projectsale->sales->branch)->get();
								
								if(count($cek) > 0){
									foreach($cek as $row){
										if($row->qty < $request->product_qty[$key]){
											$stokkurang = true;
										}
									}
								}else{
									$stokkurang = true;
								}
							}
							
							foreach($request->product_id as $key => $pi) {
								if($request->product_stock[$key] < $request->product_qty[$key]){
									$stokkurang = true;
								}
							}
							
							if($stokkurang == true){
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-16=1#step-16')
									->withErrors('Your items are not same with choosen product and warehouse origin. Please check again.');
							}
							
							$projectdelivery = ProjectDelivery::create([
								'user_id'				=> session('bo_id'),
								'project_id'    		=> $query->id,
								'project_sale_id'    	=> $request->sod_id,
								'code'					=> ProjectDelivery::generateCode(),
								'city_id'       		=> $request->city_id2,
								'receiver_name' 		=> $request->receiver_name,
								'delivery_date' 		=> $request->delivery_date,
								'email'         		=> $request->email,
								'phone'        			=> $request->phone,
								'is_dropshipper'        => $request->dropshipper,
								'dropshipper_id'        => $request->dropshipper_id ? $request->dropshipper_id : 0,
								'address'       		=> $request->address,
								'warehouse_id'         	=> $request->warehousedeliver_id,
								'vendor_id'				=> $request->expedition_id,
								'approved_by'			=> 0,
								'acknowledged_by'		=> 0,
								'image'       			=> $request->file('file') ? $request->file('file')->store('public/project') : '',
								'proforma_code'			=> ProjectDelivery::generateCodeProforma(),
								'is_sales'				=> $request->is_sales,
								'pick_up_name'			=> $request->pick_up_name,
								'pick_up_plat'			=> $request->pick_up_plat,
								'pick_up_vehicle'		=> $request->pick_up_vehicle,
								'service_note'			=> $request->service_note,
								'invoice_note'			=> $request->invoice_note,
								'break_tolerance'		=> $request->break_tolerance ? $request->break_tolerance : NULL,
							]);
							
						}
						
						foreach($request->product_id as $key => $pi) {
							ProjectDeliveryProduct::create([
								'project_delivery_id' 			=> $projectdelivery->id,
								'product_id'  	 				=> $request->product_id[$key],
								'qty'  							=> $request->product_qty[$key],
								'unit'							=> $request->product_unit[$key],
								'shading'						=> $request->product_shading[$key],
								'qty_deduction'					=> $request->product_qty_deduction[$key]
							]);
                        }
						
						ProjectDelivery::find($projectdelivery->id)->updateGrandtotal();
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details sales code '.$projectdelivery->projectSale->code.' has been sent to '.$request->receiver_name.' with delivery project code '.$projectdelivery->code.'.';
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						#send approval
						$roleapproval = array('5');
						Approval::sendApproval($roleapproval,'project_deliveries',$projectdelivery->id,'acknowledged_by',session('bo_id'));
						$roleapproval = array('4');
						Approval::sendApproval($roleapproval,'project_deliveries',$projectdelivery->id,'approved_by',session('bo_id'));
						#end approval
						
						SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Delivery Order No. '.$projectdelivery->code.' Project No. '.$projectdelivery->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						
						SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Delivery Order No. '.$projectdelivery->code.' Project No. '.$projectdelivery->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 16)');
                        break;
					
                    case 'step-17':
                        $query->update([
                            'progress' => $query->progress < 80 ? 80 : $query->progress
                        ]);
						
						$cekimbang = $request->temp_pr_id ? $query->getBalanceDeliveredBill($request->temp_pr_id) :  $query->getBalanceDeliveredBill();

						$exceedingProducts = [];
				
						$projectsale = ProjectSale::find($request->sor_id);
						
						if(!CheckCutOff::check($projectsale->sales->branch,substr($request->sale_return_date,0,7))){
							return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-17=1#step-17')
										->withErrors('You cannot add/edit. The journal for this month was already closed.');
						}

						foreach($request->product_id as $key => $pi) {
							if($projectsale->checkExceedReturn($request->product_id[$key], $request->product_qty[$key])){
								$exceedingProducts[] = Product::where('id', $request->product_id[$key])->first()->name();
							}
						}

						if (!empty($exceedingProducts)) {
							return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-17=1#step-17')
							->withErrors([
								'exceeding_products' => $exceedingProducts,
								'exceeding_description' => 'This list of products exceeds the qty limits sent.'
							]);
						}

						
						if($request->temp_pr_id){
							if($request->edit_reason_sales_retur){
								$projectreturn = ProjectSaleReturn::find($request->temp_pr_id);

								if($request->has('file')) {
									if(Storage::exists($projectreturn->image)) {
										Storage::delete($projectreturn->image);
									}
	
									$image = $request->file('file')->store('public/project');
								} else {
									$image = $projectreturn ->image;
								}
	
								$projectreturn->user_id				   = session('bo_id');
								$projectreturn->project_id			   = $query->id;
								$projectreturn->project_sale_id        = $request->sor_id;
								$projectreturn->project_return_memo_id = $request->return_memo;
								$projectreturn->date_return      	   = $request->sale_return_date;
								$projectreturn->warehouse_id  	 	   = $request->warehousereturn_id;
								$projectreturn->address			       = $request->address;
								$projectreturn->image      	 		   = $image;
								$projectreturn->note				   = $request->note;
								$projectreturn->type				   = $request->return_type;
								$projectreturn->approved_by			   = 0;
								
								$projectreturn->update();


								foreach($projectreturn->projectSaleReturnProduct as $row){
									#updatestock
									$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$projectreturn->warehouse_id)->where('branch',$projectreturn->projectSale->sales->branch)->first();
									if($cek){
										$cek->update([
											'qty' 	=> $cek->qty - $row->qty,
											'unit'	=> $row->unit
										]);
									}else{
										Stock::create([
											'product_id'	=> $row->product_id,
											'warehouse_id'	=> $projectreturn->warehouse_id,
											'qty'			=> $row->qty,
											'unit'			=> $row->unit,
											'branch'		=> $projectreturn->projectSale->sales->branch
										]);
									}
									
									$row->delete();
								}
								
								$cb = CashBank::where('lookable_type','project_sale_returns')->where('lookable_id',$request->temp_pr_id)->get();
								
								foreach($cb as $row){
									$row->deleteDetail();
									$row->delete();
								}
								
								#start notif
								$role = array('1','2','3','4','5','6','7','9','10','11');
								$title = 'Project has been updated!';
								$description = 'Project '.$query->code.' with details project return code '.$projectreturn->code.' has been edited by '.session('bo_name').'.';
								$link = '#';
								Notification::sendNotif($role,$title,$description,$link);
								#end notif
								
								$this->addProjectLog(session('bo_id'),$query->id,'project_sale_returns',$projectreturn->id,0,'update',$request->edit_reason_pr);
							}else{
								return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-17=1#step-17')
								->withErrors('Please describe your reason.');
							}	

						}else{
							$projectreturn = ProjectSaleReturn::create([
								'user_id'				=> session('bo_id'),
								'project_id'     		=> $query->id,
								'project_sale_id'		=> $request->sor_id,
								'project_return_memo_id'=> $request->return_memo,
								'date_return'			=> $request->sale_return_date,
								'warehouse_id'			=> $request->warehousereturn_id,
								'address'				=> $request->address,
								'code'					=> ProjectSaleReturn::generateCode(),
								'image'      	 		=> $request->file('file')->store('public/project'),
								'note'         			=> $request->note,
								'type'					=> $request->return_type,
								'approved_by'			=> 0
							]);
						}
                     
						
						foreach($request->product_id as $key => $pi) {
							ProjectSaleReturnProduct::create([
								'project_sale_return_id' 		=> $projectreturn->id,
								'product_id'  	 				=> $request->product_id[$key],
								'qty'  							=> $request->product_qty[$key],
								'unit'							=> $request->product_unit[$key]
							]);
							
							#updatestock
							$cek = Stock::where('product_id',$request->product_id[$key])->where('warehouse_id',$request->warehousereturn_id)->where('branch',$projectreturn->projectSale->sales->branch)->first();
							if($cek){
								$cek->update([
									'qty' 	=> $cek->qty + $request->product_qty[$key],
									'unit'	=> $request->product_unit[$key]
								]);
							}else{
								Stock::create([
									'product_id'	=> $request->product_id[$key],
									'warehouse_id'	=> $request->warehousereturn_id,
									'qty'			=> $request->product_qty[$key],
									'unit'			=> $request->product_unit[$key],
									'branch'		=> $projectreturn->projectSale->sales->branch
								]);
							}
                        }
						
						ProjectSaleReturn::find($projectreturn->id)->updateGrandtotal();
						
						if(count($projectreturn->projectSale->projectDelivery) > 0){
							$totalSale = $projectreturn->getTotal();
							$totalPurchase = $projectreturn->getPurchase();
							
							if(date('Y-m-d',strtotime($projectreturn->projectSale->created_at)) < '2022-04-01'){
								$persenppn = 0.1;
								$ppnpembagi = 1.1;
							}else{
								$persenppn = 0.11;
								$ppnpembagi = 1.11;
							}
							
							$debet1 = 31;
							// kalau cekimbang positif artinya kelebihan bayar
							$kredit2 = $cekimbang >= 0 ? 67 : 27; //cust deposit or AR
							$kredit3 = 67; //customer deposit
							$kredit1 = 122;
							$debet2 = 118;
							
							$type = '3';
							$description = 'Project with delivery code '.$projectreturn->code;
							
							$debetnominal1 = $totalPurchase;
							$kreditnominal1 = $totalPurchase;
							$debetnominal2 = $totalSale;
							// kalau kredit2 = customer deposit artinya cekimbang bernilai positif/ kelebihan bayar, dan sudah pasti nominal retur mengembalikan uang customer/ customer punya deposit di kita
							// jika kredit2 = AR, DAN JIKA NILAI RETUR DITAMBAH DENGN CEK IMBANG NILAINYA POSITIF ARTINYA ADA SEBAGIAN YANG SUDAH DIBAYARKAN DAN MENJADI CUST DEPOSIT KALAU NILAINYA NEGTIF ARTINYA BELUM ADA PEMBAYARAN/ SAAT RETUR JURNAL HANYA AR SAJA
							$kreditnominal2 =  $cekimbang >= 0 ?  $totalSale : (($totalSale + $cekimbang) > 0 ? abs($cekimbang) : $totalSale); 
							// $kreditnominal2 = $cekimbang >= 0 ? $totalSale  : ($projectreturn->grandtotal > 0  ?  abs($cekimbang) : 0 );
							
							
							// kalau saat retur, AR sudah tidak ada maka akan jadi cust deposit di $kreditnominal3
							$kreditnominal3 = $cekimbang >= 0 ? 0 : ($totalSale + $cekimbang); /* ditambah karena nilai cekimbang negatif sehingga sama saja dengan pengurangan */
							
							if($projectreturn->projectSale->project->ppn == '1'){
								$debet3 = 71;
								$debetnominal2 = $totalSale / $ppnpembagi;
								$debetnominal3 = $totalSale - ($totalSale / $ppnpembagi);
							}
							
							$cb = CashBank::create([
								'user_id'     		=> session('bo_id'),
								'lookable_type'     => 'project_sale_returns',
								'lookable_id'		=> $projectreturn->id,
								'code'        		=> strtoupper(Str::random(15)),
								'date'        		=> $request->sale_return_date,
								'type'        		=> '3',
								'description' 		=> 'Project Sales Return code '.$projectreturn->code
							]);
							
							if($cb){
								
								// if($request->return_type == '1'){
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $debet1,
									'branch'		=> $projectreturn->projectSale->sales->branch,
									'type'       	=> '1',
									'nominal'      	=> round($debetnominal1,0),
									'note'         	=> ''
								]);
								
								Journal::insert([
									'date_transaction' => $request->sale_return_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $debet1,
									'branch'		   => $projectreturn->projectSale->sales->branch,
									'type'	           => '1',
									'nominal'          => round($debetnominal1,0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $kredit1,
									'branch'		=> $projectreturn->projectSale->sales->branch,
									'type'       	=> '2',
									'nominal'      	=> round($kreditnominal1,0),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => $request->sale_return_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $kredit1,
									'branch'		   => $projectreturn->projectSale->sales->branch,
									'type'	           => '2',
									'nominal'          => round($kreditnominal1,0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								// }
								
								#LLL
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $debet2,
									'branch'		=> $projectreturn->projectSale->sales->branch,
									'type'       	=> '1',
									'nominal'      	=> round($debetnominal2,0),
									'note'         	=> ''
								]);
								
								Journal::insert([
									'date_transaction' => $request->sale_return_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $debet2,
									'branch'		   => $projectreturn->projectSale->sales->branch,
									'type'	           => '1',
									'nominal'          => round($debetnominal2,0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $kredit2,
									'branch'		=> $projectreturn->projectSale->sales->branch,
									'type'       	=> '2',
									'nominal'      	=> round($kreditnominal2,0),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => $request->sale_return_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $kredit2,
									'branch'		   => $projectreturn->projectSale->sales->branch,
									'type'	           => '2',
									'nominal'          => round($kreditnominal2,0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								// JIKA AR TERPENUHI DAN MASIH ADA SISA UANG RETUR MAKA JADI CUST DEPOSIT
								if($kredit2 == 27 && $kreditnominal3 > 0){
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kredit3,
										'branch'		=> $projectreturn->projectSale->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> round($kreditnominal3,0),
										'note'         	=> ''
									]);
	
									Journal::insert([
										'date_transaction' => $request->sale_return_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kredit3,
										'branch'		   => $projectreturn->projectSale->sales->branch,
										'type'	           => '2',
										'nominal'          => round($kreditnominal3,0),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
								
								if(isset($debet3)){
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $debet3,
										'branch'		=> $projectreturn->projectSale->sales->branch,
										'type'       	=> '1',
										'nominal'      	=> round($debetnominal3,0),
										'note'         	=> ''
									]);
									
									Journal::insert([
										'date_transaction' => $request->sale_return_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $debet3,
										'branch'		   => $projectreturn->projectSale->sales->branch,
										'type'	           => '1',
										'nominal'          => round($debetnominal3,0),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
							}
							
							if($projectreturn->projectSale->sales->branch == '2'){
								$fee_pta = round($debetnominal1 * 0.05);
								
								if($cb){
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> 332,
										'branch'		=> $projectreturn->projectSale->sales->branch,
										'type'       	=> '1',
										'nominal'      	=> $fee_pta,
										'note'         	=> 'Fee PTA from Sales Return Code '.$projectreturn->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->sale_return_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => 332,
										'branch'		   => $projectreturn->projectSale->sales->branch,
										'type'	           => '1',
										'nominal'          => $fee_pta,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> 139,
										'branch'		=> $projectreturn->projectSale->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $fee_pta,
										'note'         	=> 'Fee PTA from Sales Return Code '.$projectreturn->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->sale_return_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => 139,
										'branch'		   => $projectreturn->projectSale->sales->branch,
										'type'	           => '2',
										'nominal'          => $fee_pta,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
									#balik
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> 209,
										'branch'		=> '1',
										'type'       	=> '1',
										'nominal'      	=> $fee_pta,
										'note'         	=> 'Fee PTA from Sales Return Code '.$projectreturn->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->sale_return_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => 209,
										'branch'		   => '1',
										'type'	           => '1',
										'nominal'          => $fee_pta,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> 27,
										'branch'		=> '1',
										'type'       	=> '2',
										'nominal'      	=> $fee_pta,
										'note'         	=> 'Fee PTA from Sales Return Code '.$projectreturn->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->sale_return_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => 27,
										'branch'		   => '1',
										'type'	           => '2',
										'nominal'          => $fee_pta,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
								
								$debet4 = 281;
								$kredit4 = 296;
								$debitnominal4 = 0.01 * $debetnominal2;
								$kreditnominal4 = $debitnominal4;
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $debet4,
									'branch'		=> $projectreturn->projectSale->sales->branch,
									'type'       	=> '1',
									'nominal'      	=> $debitnominal4,
									'note'         	=> 'Sales Commission Retur from SR Code '.$projectreturn->code
								]);
								
								Journal::insert([
									'date_transaction' => $request->sale_return_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $debet4,
									'branch'		   => $projectreturn->projectSale->sales->branch,
									'type'	           => '1',
									'nominal'          => $debitnominal4,
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $kredit4,
									'branch'		=> $projectreturn->projectSale->sales->branch,
									'type'       	=> '2',
									'nominal'      	=> $kreditnominal4,
									'note'         	=> 'Sales Commission Retur from SR Code '.$projectreturn->code
								]);
								
								Journal::insert([
									'date_transaction' => $request->sale_return_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $kredit4,
									'branch'		   => $projectreturn->projectSale->sales->branch,
									'type'	           => '2',
									'nominal'          => $kreditnominal4,
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
							}
						}

						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details sales code '.$projectreturn->projectSale->code.' has been returned with sales return code '.$projectreturn->code.'.';
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						#send approval
						$roleapproval = array('5');
						Approval::sendApproval($roleapproval,'project_sale_returns',$projectreturn->id,'approved_by',session('bo_id'));
						#end approval
						
						SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Sales Return No. '.$projectreturn->code.' Project No. '.$projectreturn->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						
						ProductCogs::updateCogs($request->sale_return_date,$projectreturn->projectSale->sales->branch);
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 17)');
						
						break; 
					
					case 'step-18':
						$query->update([
                            'progress' => $query->progress < 85 ? 85 : $query->progress
                        ]);

                        $projectpay = ProjectPay::create([
							'user_id'				=> session('bo_id'),
                            'project_id'     		=> $query->id,
							'project_sale_id'		=> $request->sop_id,
							'project_delivery_id'	=> $request->do_id_full,
							'project_bill_id'		=> $request->bill_id_full,
							'code'					=> ProjectPay::generateCode(),
                            'image'          		=> $request->file('file_full')->store('public/project'),
                            'date'           		=> $request->date_create_full,
							'nominal'        		=> round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
                            'payment_method' 		=> $request->payment_method_full,
							'coa_id' 				=> $request->bank_id_full,
							'giro' 					=> $request->giro_full,
							'giro_code'				=> $request->giro_code_full,
							'giro_date'				=> $request->giro_date_full,
							'note'					=> $request->note_full,
							'marketing_id'			=> 0,
							'approved_id'			=> 0
                        ]);
						
						$pp = ProjectSale::find($request->sop_id);
						
						$projectdetail = $pp->id;
						$reference = '1'; #is sale
						$type = '1'; #journal
						$description = 'Project payment with sales code '.$pp->code;
						
						#updatecashbank
						$bh = BalanceHistory::create([
							'user_id' 		=> session('bo_id'),
							'nominal'		=> round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
							'type'			=> 'IN',
							'cash_or_bank'	=> $request->giro_full == '0' ? 'BANK' : 'CASH',
							'coa_id'		=> $request->bank_id_full,
							'branch'		=> $pp->sales->branch,
							'note'			=> $description,
							'date'			=> $request->date_create_full,
							'image'			=> $request->file('file_full') ? $request->file('file_full')->store('public/cashbank') : ''
						]);
						
						#START
						
						//$cekbill = $('')
						
						if($request->giro == '1'){
							
							$debetcb = 80;
							$kreditcb = 27;

							$cb = CashBank::create([
								'user_id'     		=> session('bo_id'),
								'lookable_type'  	=> 'project_pays',
								'lookable_id'		=> $projectpay->id,
								'code'        		=> strtoupper(Str::random(15)),
								'date'        		=> $request->date_create_full,
								'type'        		=> $type,
								'description' 		=> $description
							]);
							
							if($cb){
									
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $debetcb,
									'branch'		=> $pp->sales->branch,
									'type'       	=> '1',
									'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => $request->date_create_full,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $debetcb,
									'branch'		   => $pp->sales->branch,
									'type'	           => '1',
									'nominal'          => round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $kreditcb,
									'branch'		=> $pp->sales->branch,
									'type'       	=> '2',
									'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'note'         	=> ''
								]);
								
								Journal::insert([
									'date_transaction' => $request->date_create_full,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $kreditcb,
									'branch'		   => $pp->sales->branch,
									'type'	           => '2',
									'nominal'          => round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
							}
							
							$debetcb = $request->bank_id_full;
							$kreditcb = 80;
							
							$cb = CashBank::create([
								'user_id'     		=> session('bo_id'),
								'lookable_type'  	=> 'project_pays',
								'lookable_id'		=> $projectpay->id,
								'code'        		=> strtoupper(Str::random(15)),
								'date'        		=> $request->giro_date,
								'type'        		=> $type,
								'description' 		=> $description
							]);
							
							if($cb){
									
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $debetcb,
									'branch'		=> $pp->sales->branch,
									'type'       	=> '1',
									'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => $request->giro_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $debetcb,
									'branch'		   => $pp->sales->branch,
									'type'	           => '1',
									'nominal'          => round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $kreditcb,
									'branch'		=> $pp->sales->branch,
									'type'       	=> '2',
									'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'note'         	=> ''
								]);
								
								Journal::insert([
									'date_transaction' => $request->giro_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $kreditcb,
									'branch'		   => $pp->sales->branch,
									'type'	           => '2',
									'nominal'          => round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
							}
							
						}else{
							
							$debetcb = $request->bank_id_full;
							$kreditcb = 27;
							
							$projectdelivery = ProjectDelivery::find($request->do_id_full);
							
							$sisa = round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0) - round(($projectdelivery->getTotal()['totaldelivery'] + $projectdelivery->getServiceCost()),0);
							
							$cb = CashBank::create([
								'user_id'     		=> session('bo_id'),
								'lookable_type'  	=> 'project_pays',
								'lookable_id'		=> $projectpay->id,
								'code'        		=> 'BPC-'.$bh->id,
								'date'        		=> $request->date_create_full,
								'type'        		=> $type,
								'description' 		=> $description
							]);
							
							if($cb){
								
								/* if($request->bill_id_full){
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $debetcb,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '1',
										'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->nominal)),0),
										'note'         	=> ''
									]);

									Journal::insert([
										'date_transaction' => $request->date_create,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $debetcb,
										'branch'		   => $pp->sales->branch,
										'type'	           => '1',
										'nominal'          => round(str_replace(',','.',str_replace('.','',$request->nominal)),0),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								} */
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $debetcb,
									'branch'		=> $pp->sales->branch,
									'type'       	=> '1',
									'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => $request->date_create_full,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $debetcb,
									'branch'		   => $pp->sales->branch,
									'type'	           => '1',
									'nominal'          => round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
								if($sisa > 1){
								
									$kreditcb2 = 48;
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> round(($projectdelivery->getTotal()['totaldelivery'] + $projectdelivery->getServiceCost()),0),
										'note'         	=> ''
									]);
									
									Journal::insert([
										'date_transaction' => $request->date_create_full,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $pp->sales->branch,
										'type'	           => '2',
										'nominal'          => round(($projectdelivery->getTotal()['totaldelivery'] + $projectdelivery->getServiceCost()),0),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb2,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $sisa,
										'note'         	=> ''
									]);
									
									Journal::insert([
										'date_transaction' => $request->date_create_full,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb2,
										'branch'		   => $pp->sales->branch,
										'type'	           => '2',
										'nominal'          => $sisa,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
								}else{
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
										'note'         	=> ''
									]);
									
									Journal::insert([
										'date_transaction' => $request->date_create_full,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $pp->sales->branch,
										'type'	           => '2',
										'nominal'          => round(str_replace(',','.',str_replace('.','',$request->nominal_full)),0),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
							}
						}

						#END
						
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details payment sales code '.$pp->code.' and invoice '.$projectpay->code.' has been updated by '.session('bo_name').' in Full Payment Sales Form.';
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						$cekapproval = 0;
					
						foreach(ProjectSale::find($request->sop_id)->projectSalePay as $rowpay){
							$cekapproval = Approval::where('approvalable_type','project_pays')->where('approvalable_id',$rowpay->id)->count();
						}
						
						if($cekapproval == 0){
							#send approval
							$roleapproval = array('3');
							Approval::sendApproval($roleapproval,'project_pays',$projectpay->id,'checked_id',session('bo_id'));
							$roleapproval = array('5');
							Approval::sendApproval($roleapproval,'project_pays',$projectpay->id,'marketing_id',session('bo_id'));
							$roleapproval = array('4');
							Approval::sendApproval($roleapproval,'project_pays',$projectpay->id,'approved_id',session('bo_id'));
							#end approval
							
							SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Sales Payment No. '.$projectpay->code.' Project No. '.$projectpay->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
							
							SendMessage::send(env('FINANCE_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Sales Payment No. '.$projectpay->code.' Project No. '.$projectpay->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
							
							SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Sales Payment No. '.$projectpay->code.' Project No. '.$projectpay->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						}else{
							$rowapproval = [];
							
							foreach(ProjectSale::find($request->sop_id)->projectSalePay as $rowpay){
								$rowapproval[] = Approval::where('approvalable_type','project_pays')->where('approvalable_id',$rowpay->id)->first();
							}
							
							if(count($rowapproval) > 0){
								foreach(ProjectSale::find($request->sop_id)->projectSalePay as $rowpay){
									$rowpay->update([
										'checked_id' 	=> $rowapproval[0]->checked_id,
										'marketing_id'	=> $rowapproval[0]->marketing_id,
										'approved_id'	=> $rowapproval[0]->approved_id
									]);
								}
							}
						}
						
						$ceklunas = Project::find($query->id)->getBalance();
						
						if($ceklunas <= 0){
							#send approval
							$roleapproval = array('1');
							Approval::sendApproval($roleapproval,'projects',$query->id,'',session('bo_id'));
							#end approval
							
							SendMessage::send(env('OWNER_PHONE'),'Halo pak/bu. Mohon dibantu approve Penutupan Project No. '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						}
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 18)');
                        break;
					
                    case 'step-19':
						
						/* return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-19=1#step-19')
										->withErrors($query->getBalance()); */
					
                        $query->update([
                            'progress' => $query->progress < 90 ? 90 : $query->progress
                        ]);
						
						ProjectTroubleshooting::create([
							'user_id'			=> session('bo_id'),
                            'project_id'     	=> $query->id,
                            'image'          	=> $request->file('file') ? $request->file('file')->store('public/project') : '',
                            'date_trouble'     	=> $request->date_trouble,
							'note'				=> $request->note_trouble
                        ]);
						
						#start notif
						$role = array('1');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' with details troubleshooting information has been updated by '.session('bo_name');
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						#send approval
						$roleapproval = array('1');
						Approval::sendApproval($roleapproval,'projects',$query->id,'',session('bo_id'));
						#end approval
						
						SendMessage::send(env('OWNER_PHONE'),'Halo pak/bu. Mohon dibantu approve Troubleshooting Project No. '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Add troubleshooting to project ' . $query->name . ' (Step 19)');
                        break;
						
                    case 'step-20':
                        $query->update([
                            'progress' => $query->progress < 100 ? 100 : $query->progress
                        ]);
							
						#start notif
						$role = array('1','2','3','4','5','6','7','9','10','11');
						$title = 'Project has been updated!';
						$description = 'Project '.$query->code.' has been closed successfully by '.session("bo_name");
						$link = '#';
						Notification::sendNotif($role,$title,$description,$link);
						#end notif
						
						$user = UserRole::whereIn('role',$role)->groupBy('user_id')->get();
						
						Approval::where('approvalable_type', 'projects')
							->where('approvalable_id', $query->id)
							->update([
								'seen' => true,
								'approved_by' => session('bo_id')
							]);

						foreach($user as $u){
							if($u->user->status == '1'){
								$payload = [
									'email'   	=> $u->email,
									'name'    	=> $u->name,
									'closed_by' => session('bo_name'),
									'project'  	=> $query,
									'view'    	=> 'project_closing',
									'subject' 	=> 'SMB | Project ' . $query->code
								];
								
								dispatch(new EmailProcess($payload));
							}
						}
						
                        activity()
                            ->performedOn(new Project())
                            ->causedBy(session('bo_id'))
                            ->log('Change data project ' . $query->name . ' (Step 20)');
                        break; 
                }
				
				if(explode('-',$step)[1] < 20){
					$nextangka = explode('-',$step)[1] + 1;
				}
				
                return redirect('admin/'.$uri[2].'/project/progress/' . $id . '?step-' . $nextangka . '=1#step-' . $nextangka)
                    ->with(['success' => 'Data successfully saved.']);
            }
        } else {
			
			$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$uri = explode('/', $uri_path);
			
			if($uri[2] == 'sales'){
				if(date('Y-m-d',strtotime($query->created_at)) < '2022-04-01'){
					$persenppn = 0.1;
					$ppnpembagi = 1.1;
				}else{
					$persenppn = 0.11;
					$ppnpembagi = 1.11;
				}
				// $test = ProjectProduct::whereHas('product', function ($query){
				// 	$query->whereDoesnthave('type');
				// })->get();
				// foreach ($test as $val) {
				// 	echo '<pre>' . var_export($val->product_id, true) . '</pre>';
				// 	echo '<pre>' . var_export($val->product_id, true) . '</pre>';
					
				// }
				// dd($val->product_id);
				// $breakthecode = Product::find(7279);
				// dd($breakthecode->type_id);
				// $term_id = isset($query->projectQuotation) ? $query->projectQuotation()->orderBy('created_at', 'desc')->first()->terms_cons_id :'
				// <li>Kami hanya menyuplai bahan kualitas no 1.</li>
				// '.($query->ppn == 1 ? '<li>Nilai total sudah termasuk'.$persenppn * 100 .'% PPN.</li>': '') .'
				// <li>Harga sudah termasuk sampai (<i>onsite</i>) di '.($query->city_franco ? $query->city_franco->name : '').' (Diatas Truk).</li>
				// <li>Jika stok ada, pengiriman adalah 3-7 hari .</li>
				// <li>Jika barang perlu diproduksi, waktu tunggu 8-24 minggu.</li>
				// <li>Pembayaran menggunakan '.$query->paymentMethodIdn() . '-' . $query->paymentTermIdn() .'.</li>
				// <li>Harga tidak termasuk pemasangan.</li>
				// ';
				// $term_en = isset($query->projectQuotation) ? $query->projectQuotation()->orderBy('created_at', 'desc')->first()->terms_cons_en :'
				// <li>We only supply 1st quality goods</li>
				// '.($query->ppn == 1 ? '	<li>Grandtotal already include '.$persenppn * 100 .'% VAT.</li>': '') .'
				// <li>Prices already included franco to '.($query->city_franco ? $query->city_franco->name : '').' (On Truck).</li>
				// <li>Normal delivery 3-7 days. If the items are not ready, please wait within 8-24 weeks.</li>
				// <li>Payment '.$query->paymentMethod() . '-' . $query->paymentTerm() .'.</li>
				// <li>Prices do not include installation.</li>
				// ';

				$default_term_id = '<ol>
				<li>Kami hanya menyuplai bahan kualitas no 1.</li>
				'.($query->ppn == 1 ? '<li>Nilai total sudah termasuk PPN '.$persenppn * 100 .'%.</li>': '') .'
				<li>Harga sudah termasuk sampai (<i>onsite</i>) di '.($query->city_franco ? $query->city_franco->name : '').' (Diatas Truk).</li>
				<li>Jika stok ada, pengiriman adalah 3-7 hari .</li>
				<li>Jika barang perlu diproduksi, waktu tunggu 8-24 minggu.</li>
				<li>Pembayaran menggunakan '.$query->paymentMethodIdn() . '-' . $query->paymentTermIdn() .'.</li>
				<li>Harga tidak termasuk pemasangan.</li>
				</ol>';

				$default_term_en = '<ol>
				<li>We only supply 1st quality goods</li>
				'.($query->ppn == 1 ? '	<li>Grandtotal already include '.$persenppn * 100 .'% VAT.</li>': '') .'
				<li>Prices already included franco to '.($query->city_franco ? $query->city_franco->name : '').' (On Truck).</li>
				<li>Normal delivery 3-7 days. If the items are not ready, please wait within 8-24 weeks.</li>
				<li>Payment '.$query->paymentMethod() . '-' . $query->paymentTerm() .'.</li>
				<li>Prices do not include installation.</li>
				</ol>';


				$latestQuotation_id = $query->projectQuotation()->orderBy('created_at', 'desc')->first();
				$term_id = isset($latestQuotation_id->terms_cons_id) ? $latestQuotation_id->terms_cons_id : $default_term_id;

				$latestQuotation_en = $query->projectQuotation()->orderBy('created_at', 'desc')->first();
				$term_en = isset($latestQuotation_en->terms_cons_id) ? $latestQuotation_en->terms_cons_en : $default_term_en;

				$letter_head = isset( $query->projectQuotation()->orderBy('created_at', 'desc')->first()->letter_head) ? $query->projectQuotation()->orderBy('created_at', 'desc')->first()->letter_head : '1';

				$data = [
					'title'   		=> 'Progress Data Project',
					'vendor' 		=> Vendor::where('status', 1)->get(),
					'country' 		=> Country::where('status', 1)->get(),
					'city'    		=> City::all(),
					'note'    		=> ProjectNote::where('notable_type','projects')->where('notable_id',$query->id)->get(),
					'project' 		=> $query,
					'bank' 			=> Coa::where('parent_id', 8)->where('status', 1)->get(),
					'dropshipper' 	=> Dropshipper::where('status', 1)->get(),
					'currency' 		=> Currency::where('status', 1)->get(),
					'term_id' 		=> $term_id,
					'term_en' 		=> $term_en,
					'letter_head' 	=> $letter_head,
					'content'		=> 'admin.sales.project_progress'
				];
			}elseif($uri[2] == 'purchase_order'){
				$data = [
					'title'   		=> 'Progress Data Project',
					'vendor' 		=> Vendor::where('status', 1)->get(),
					'country' 		=> Country::where('status', 1)->get(),
					'city'    		=> City::all(),
					'project' 		=> $query,
					'bank' 			=> Coa::where('parent_id', 8)->where('status', 1)->get(),
					'dropshipper' 	=> Dropshipper::where('status', 1)->get(),
					'coa_id' 		=> Coa::where('status', 1)->where('parent_id',340)->get(),
					'purchaseforstock' => ProjectPurchase::whereDoesntHave('project')->get(),
					'content'		=> 'admin.purchase_order.project_progress'
				];
			}elseif($uri[2] == 'delivery_order'){

				if(date('Y-m-d',strtotime($query->created_at)) < '2022-04-01'){
					$persenppn = 0.1;
					$ppnpembagi = 1.1;
				}else{
					$persenppn = 0.11;
					$ppnpembagi = 1.11;
				}

				if(count($query->projectSale()->get()) > 0){
					$projectSale = ProjectSale::where('project_id', $query->id)->orderBy('created_at', 'DESC')->first();

					$nominal_sales_order = $query->ppn == '1' ? $projectSale->grandtotal_product + ($projectSale->grandtotal_product * $persenppn) : $projectSale->grandtotal_product;
					$nominal_service_real = $projectSale->grandtotal_service;
				}else{
					$nominal_sales_order = 0;
					$nominal_service_real = 0;
				}
				
				$data = [
					'title'   				=> 'Deliver Data Project',
					'vendor' 				=> Vendor::where('status', 1)->get(),
					'country' 				=> Country::where('status', 1)->get(),
					'city'    				=> City::all(),
					'project' 				=> $query,
					'nominal_so_real' 		=> $nominal_sales_order,
					'nominal_service_real'  => $nominal_service_real,
					'bank' 					=> Coa::all(),
					'dropshipper' 			=> Dropshipper::where('status', 1)->get(),
					'branch' 			    => $projectSale->sales->branch,
					'content'				=> 'admin.delivery_order.project_progress'
				];
			}elseif($uri[2] == 'invoice'){
				$data = [
					'title'   		=> 'Invoice Project',
					'vendor' 		=> Vendor::where('status', 1)->get(),
					'country' 		=> Country::where('status', 1)->get(),
					'city'    		=> City::all(),
					'project' 		=> $query,
					'bank' 			=> Coa::where('parent_id', 8)->where('status', 1)->get(),
					'dropshipper' 	=> Dropshipper::where('status', 1)->get(),
					'content'		=> 'admin.invoice.project_progress'
				];
			}

            return view('admin.layouts.index', ['data' => $data]);
        }
    }


	private function stripCss($html) {
		$html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
		$html = preg_replace('/style=("|\')(.*?)("|\')/is', '', $html);
		return $html;
	}

    public function print(Request $request, $param, $id)
    {      
        if($param == 'quotation_order'){
			$project_id = base64_decode($id);
			$project    = ProjectQuotation::find($project_id);
			$detail = ProjectQuotationProduct::where('project_quotation_id',$project_id)->orderBy('area')->orderBy('id')->get();
			
			if(!$project) {
				abort(404);
			}
				
			if($request->la == 'idn'){
				$param = $param.'_idn';
			}

			$html = view('admin.pdf.project.' . $param, [
				'project' => $project,
				'detail'	=> $detail
			])->render();
			
			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' 	=> $project,
			// 		'detail'	=> $detail
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => 'A4-P',
			// 	  'orientation' => 'P'
			// 	]
			// );
		}else if($param == 'pick_up_memo'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchase::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'pick_up_memo_delivery'){
			$project_id = base64_decode($id);
			$project    = ProjectDelivery::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'return_memo'){
			$project_id = base64_decode($id);
			$project    = ProjectReturnMemo::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'warehouse_receive'){
			$warehouse_id = base64_decode($id);
			$project = ProjectWarehouse::find($warehouse_id);
			
			if(!$warehouse_id) {
				abort(404);
			}

			$html = view('admin.pdf.project.' . $param, [
				'project' => $project,
			])->render();
			
			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' => $project
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => 'A4-P',
			// 	  'orientation' => 'P'
			// 	]
			// );
			
		}else if($param == 'negotiation_order'){
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A3-L',
				  'orientation' => 'L'
				]
			);
		}else if($param == 'sample_order'){
			$project_id = base64_decode($id);
			$project    = ProjectSample::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'purchase_order'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchase::find($project_id);

			if(!$project) {
				abort(404);
			}

			$html = view('admin.pdf.project.' . $param, [
				'project' => $project
			])->render();
			
			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' => $project
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => 'A4-P',
			// 	  'orientation' => 'P'
			// 	]
			// );
		}else if($param == 'delivery_order'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$project) {
				abort(404);
			}
			$html = view('admin.pdf.project.' . $param, [
				'project' => $project
			])->render();
			
			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' => $project
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => 'A4-P',
			// 	  'orientation' => 'P'
			// 	]
			// );
		
		}else if($param == 'sales_proforma'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			

			$html = view('admin.pdf.project.' . $param, [
				'project' => $project
			])->render();

			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' => $project
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => 'A4-P',
			// 	  'orientation' => 'P'
			// 	]
			// );
		}else if($param == 'sales_proforma_other'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'sales_return'){
			$project_id = base64_decode($id);
			$project    = ProjectSaleReturn::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$html = view('admin.pdf.project.' . $param, [
				'project' => $project
			])->render();
			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' => $project
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => 'A4-P',
			// 	  'orientation' => 'P'
			// 	]
			// );
		}else if($param == 'purchase_return'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchaseReturn::find($project_id);

			if(!$project) {
				abort(404);
			}
			$html = view('admin.pdf.project.' . $param, [
				'project' => $project
			])->render();
			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' => $project
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => 'A4-P',
			// 	  'orientation' => 'P'
			// 	]
			// );
		}else if($param == 'sales_invoice'){
			$project_id = base64_decode($id);
			$project    = ProjectPay::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			if($request->la == 'idn'){
				$param = $param.'_idn';
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => [210,320],
				  'orientation' => 'P'
				]
			);
			
		}else if($param == 'sales_invoice_delivery'){
			$project_id = base64_decode($id);
			$project    = ProjectPay::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$html = view('admin.pdf.project.' . $param, [
				'project' => $project
			])->render();

			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' => $project
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => [210,290],
			// 	  'orientation' => 'P'
			// 	]
			// );
			
		}else if($param == 'sales_invoice_other'){
			$project_id = base64_decode($id);
			$project    = ProjectPay::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => [210,320],
				  'orientation' => 'P'
				]
			);
			
		}else if($param == 'sales_order'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}

			$html = view('admin.pdf.project.' . $param, [
				'project' => $project,
			])->render();
			
			// $pdf = PDF::loadView('admin.pdf.project.' . $param, [
			// 		'project' => $project
			// 	],
			// 	[],
			// 	[ 
			// 	  'format' => 'A4-P',
			// 	  'orientation' => 'P'
			// 	]
			// );
		}else if($param == 'so_do_news'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'sales_bill'){
			$project_id = base64_decode($id);
			$project    = ProjectBill::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			if($request->la == 'idn'){
				$param = $param.'_idn';
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'sales_cost'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'sales_news'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			//return view('admin.pdf.project.'.$param, ['project' => $project]);
			
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'sales_news_final'){
			$project_id = base64_decode($id);
			$project    = ProjectDelivery::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'request_quotation'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchaseQuotation::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
					'project' => $project
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else{
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.project.' . $param, [
				'project' => $project
			]);
		}
		
		$filename = str_replace('/', '-', $project->code);
		
        //return $pdf->stream('TJS Document '.$filename.'.pdf');
		// return $pdf->stream('tjs.pdf');
		return $html;

    }
	
	public function printHtml($param, $id)
    {
        if($param == 'quotation_order'){
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}
		
		}else if($param == 'warehouse_receive'){
			$warehouse_id = base64_decode($id);
			$project = ProjectWarehouse::find($warehouse_id);
			
			if(!$warehouse_id) {
				abort(404);
			}
			
		}else if($param == 'negotiation_order'){
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		}else if($param == 'sample_order'){
			$project_id = base64_decode($id);
			$project    = ProjectSample::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		
		}else if($param == 'purchase_order'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchase::find($project_id);

			if(!$project) {
				abort(404);
			}
		
		}else if($param == 'delivery_order'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			
		
		}else if($param == 'sales_proforma'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			
		}else if($param == 'sales_proforma_other'){
			$do_id = base64_decode($id);
			$project = ProjectDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			
		}else if($param == 'sales_return'){
			$project_id = base64_decode($id);
			$project    = ProjectSaleReturn::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
		}else if($param == 'purchase_return'){
			$project_id = base64_decode($id);
			$project    = ProjectPurchaseReturn::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
		}else if($param == 'sales_invoice'){
			$project_id = base64_decode($id);
			$project    = ProjectPay::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		
			
		}else if($param == 'sales_invoice_other'){
			$project_id = base64_decode($id);
			$project    = ProjectPay::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
			
		}else if($param == 'sales_order'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
		}else if($param == 'sales_bill'){
			$project_id = base64_decode($id);
			$project    = ProjectBill::find($project_id);

			if(!$project) {
				abort(404);
			}
			
			
		}else if($param == 'sales_cost'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		}else if($param == 'sales_news'){
			$project_id = base64_decode($id);
			$project    = ProjectSale::find($project_id);

			if(!$project) {
				abort(404);
			}
			
		}else{
			$project_id = base64_decode($id);
			$project    = Project::find($project_id);

			if(!$project) {
				abort(404);
			}

		}

        return view('admin.pdf.project.'.$param, ['project' => $project]);
    }
	
	public function approval(Request $request)
    {
		$pqid = $request->id;
		$approvalke = $request->approvalKe;
		$mode = $request->mode;
		
		if($mode == 'quotation'){
			$pq = ProjectQuotation::find($pqid);
			if($approvalke == 1){
				$pq->approved_by_1 = session('bo_id');
			}else if($approvalke == 2){
				$pq->approved_by_2 = session('bo_id');
			}
			$pq->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$pq->project->code.' quotation revision '.$pq->revision.' has been approved '.$approvalke.' by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectQuotation())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project quotation');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'sales_return'){
			$pq = ProjectSaleReturn::find($pqid);
			if($approvalke == 1){
				$pq->approved_by = session('bo_id');
			}
			
			$pq->save();
			
			activity()
                    ->performedOn(new ProjectSaleReturn())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project sale return');
					
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'sales_invoice'){
			$pq = ProjectPay::find($pqid);
			if($approvalke == 1){
				$pq->marketing_id = session('bo_id');
			}elseif($approvalke == 2){
				$pq->approved_id = session('bo_id');
			}elseif($approvalke == 3){
				$pq->checked_id = session('bo_id');
			}
			
			$pq->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = '';
			if($approvalke == 1){
				$description = 'Project '.$pq->projectSale->project->code.' with details sales code '.$pq->projectSale->code.' has been approved by '.session('bo_name');
			}elseif($approvalke == 2){
				$description = 'Project '.$pq->projectSale->project->code.' with details sales code '.$pq->projectSale->code.' has been acknowledged by '.session('bo_name');
			}elseif($approvalke == 3){
				$description = 'Project '.$pq->projectSale->project->code.' with details sales code '.$pq->projectSale->code.' has been checked by '.session('bo_name');
			}
			
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectPay())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project sales invoice');
					
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'purchase_return'){
			$pq = ProjectPurchaseReturn::find($pqid);
			if($approvalke == 1){
				$pq->approved_by = session('bo_id');
			}
			
			$pq->save();
			
			activity()
                    ->performedOn(new ProjectPurchaseReturn())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project purchase return');
					
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'delivery'){
			$pq = ProjectDelivery::find($pqid);
			if($approvalke == 1){
				$pq->approved_by = session('bo_id');
			}elseif($approvalke == 2){
				$pq->acknowledged_by = session('bo_id');
			}
			
			$pq->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$pq->project->code.' with details delivery order code '.$pq->code.' has been approved by '.session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectDelivery())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project delivery');
					
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'sample'){
			$pq = ProjectSample::find($pqid);
			if($approvalke == 1){
				$pq->approved_by_1 = session('bo_id');
			}else if($approvalke == 2){
				$pq->approved_by_2 = session('bo_id');
			}
			$pq->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$pq->project->code.' with details sample code '.$pq->code.' has been approved by '.session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectSample())
                    ->causedBy(session('bo_id'))
                    ->withProperties($pq)
                    ->log('Add approval to project sample');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'sale'){
			$ps = ProjectSale::find($pqid);
			if($approvalke == 1){
				$ps->marketing_id = session('bo_id');
			}else if($approvalke == 2){
				$ps->approved_id = session('bo_id');
			}
			$ps->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$ps->project->code.' with details sales code '.$ps->code.' has been approved '.$approvalke.' by '.session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectSale())
                    ->causedBy(session('bo_id'))
                    ->withProperties($ps)
                    ->log('Add approval to project sale');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}elseif($mode == 'purchase'){
			$ps = ProjectPurchase::find($pqid);
			if($approvalke == 1){
				$ps->checked_by = session('bo_id');
			}else if($approvalke == 2){
				$ps->approved_by = session('bo_id');
			}
			$ps->save();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$ps->project->code.' with details purchase code '.$ps->code.' has been approved '.$approvalke.' by '.session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectPurchase())
                    ->causedBy(session('bo_id'))
                    ->withProperties($ps)
                    ->log('Add approval to project purchase');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];

			return response()->json($response);
		}
	}
	
	public function getSalesProduct(Request $request)
    {
        $projectsale = ProjectSaleProduct::where('project_sale_id',$request->idprojectsale)->get();
		$project = ProjectSale::find($request->idprojectsale);
		
		$data = [];
		
        foreach($projectsale as $ps){
			$m2 = (( $ps->product->type->length * $ps->product->type->width ) / 10000) * $ps->product->carton_pcs;
			
			$unitother = 1;
			
			if($m2 < 1.1 && $ps->product->type->category->parent()->id !== 18){
				$countbox = ceil($ps->qty);
				$unitother = ceil($m2);
			}else{
				if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $ps->product->type->category->parent()->id == 18){
					if($ps->unit == '2' || $ps->unit == '3'){
						$countbox = ceil($ps->qty);
						$unitother = ceil($m2);
					}
				}else{
					if($ps->unit == '2' || $ps->unit == '3'){
						$countbox = ceil(round($ps->qty / $m2,2));
						$unitother = $m2;
					}
				}
			}
			
			$projectpurchase = ProjectPurchase::where('project_sale_id',$request->idprojectsale)->get();
			
			$tot = 0;
			foreach($projectpurchase as $pp){
				foreach($pp->projectPurchaseProduct as $ppd){
					if($ppd->product_id == $ps->product_id){
						$tot += $ppd->qty;
					}
				}
			}
			
			$projectdelivery = ProjectDelivery::where('project_sale_id',$request->idprojectsale)->get();
			
			$totdelivery = 0;
			$qtyDeduction = 0;
			foreach($projectdelivery as $pp){
				foreach($pp->projectDeliveryProduct as $ppd){
					if($ppd->product_id == $ps->product_id){
						$totdelivery += $ppd->qty;
					}
				}
			}
			
			$shading = '';
			
			foreach($ps->projectSale->projectSaleShading()->where('product_id',$ps->product_id)->get() as $pss){
				$shading = 'Code : '.$pss->code.' Qty : '.round($ps->qty,0).', ';
			}
			
			$stock = 'None';
			
			if(Stock::where('product_id',$ps->product_id)->where('branch',$ps->projectSale->sales->branch)->count() > 0){
				$stock = '';
			}
			
			$totalstok = 0;
			
			foreach(Stock::where('product_id',$ps->product_id)->where('branch',$ps->projectSale->sales->branch)->get() as $rowstock){
				if ($rowstock->unit != '2' || $rowstock->unit != '3' || $rowstock->unit == $ps->unit) {
					$stock .= 'Warehouse ' . ($rowstock->warehouse ? $rowstock->warehouse->name : '') . ' (' . $rowstock->warehouse->code . ') qty : ' . $rowstock->qty . ', ';
					$totalstok += $rowstock->qty;
				}				
			}
			
			$latestpurchaseprice = ProjectPurchaseProduct::where('product_id',$ps->product_id)->latest()->first();
			
			$listpurchaseproduct = ProjectPurchase::whereHas('projectWarehouse', function($query) use ($ps){
				$query->whereHas('projectWarehouseProduct', function($query) use ($ps) {
					$query->where('product_id',$ps->product_id);
				});
			})->get();
			
			$listpo = [];
			
			foreach($listpurchaseproduct as $rowpo){
				$listpo[] = [
					'id'		=> $rowpo->id,
					'text'		=> $rowpo->project_id ? $rowpo->code : $rowpo->code.' - Stock',
					'price'		=> number_format($rowpo->projectPurchaseProduct->where('product_id',$ps->product_id)->first()->price,0,',','.')
				];
			}
			
			if($ps->unit == '3' || $ps->unit == '2'){
				$data[] = [
					'product_id'		=> $ps->product_id,
					'product_name'		=> $ps->product->name(),
					'product_code'		=> $ps->product->type->code,
					'qty'          		=> $countbox,
					'qty_left'			=> $countbox - $tot - $ps->getCountFromStock(),
					'unit'          	=> 'Box',
					'unitraw'          	=> $ps->unit,
					'unitconvert'		=> $ps->unit == 3 ? 2 : $ps->unit,
					'm2'				=> $m2,
					'price'				=> number_format($ps->best_price ? $ps->best_price : $ps->recommended_price ,0,',','.'),
					'budget_price'		=> number_format($ps->priceBudget(),0,',','.'),
					'countbox'			=> $countbox,
					'qty_left_deliver'	=> $countbox - $totdelivery,
					'shading'			=> $shading,
					'stock'				=> $stock,
					'nominalstock'		=> $totalstok,
					'latestpurchaseprice'	=> $latestpurchaseprice ? number_format($latestpurchaseprice->price,0,',','.') : 0,
					'listpurchaseproduct'	=> $listpo,
					'qtyfromstock'		=> $ps->getCountFromStock(),
					'unitother'			=> $unitother,
					'qtydeduction'		=> $qtyDeduction,
				];
			}else{
				$data[] = [
					'product_id'		=> $ps->product_id,
					'product_name'		=> $ps->product->name(),
					'product_code'		=> $ps->product->type->code,
					'qty'          		=> $ps->qty,
					'qty_left'			=> $ps->qty - $tot - $ps->getCountFromStock(),
					'unit'          	=> $ps->unit(),
					'unitraw'          	=> $ps->unit,
					'unitconvert'		=> $ps->unit,
					'm2'				=> 1,
					'price'				=> number_format($ps->best_price ? $ps->best_price : $ps->recommended_price,0,',','.'),
					'budget_price'		=> number_format($ps->priceBudget(),0,',','.'),
					'countbox'			=> 0,
					'qty_left_deliver'	=> $ps->qty - $totdelivery,
					'shading'			=> $shading,
					'stock'				=> $stock,
					'nominalstock'		=> $totalstok,
					'latestpurchaseprice'	=> $latestpurchaseprice ? number_format($latestpurchaseprice->price,0,',','.') : 0,
					'listpurchaseproduct'	=> $listpo,
					'qtyfromstock'		=> $ps->getCountFromStock(),
					'unitother'			=> $unitother,
					'qtydeduction'		=> $qtyDeduction,
				];
			}
        }

        return response()->json($data);
    }
	
	public function getPurchaseProduct(Request $request)
    {
        $projectpurchase = ProjectPurchaseProduct::where('project_purchase_id',$request->idpo)->get();
		
		$data = [];
		$product_location = null;
		foreach($projectpurchase as $pp){
			$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
			
			$projectshipment = ProjectShipment::where('project_purchase_id',$request->idpo)->get();
			
			$tot = 0;
			foreach($projectshipment as $ps){
				foreach($ps->projectShipmentProduct as $psp){
					if($psp->product_id == $pp->product_id){
						$tot += $psp->qty;
					}
				}
			}

			foreach(Stock::where('product_id', $pp->product_id)->where('branch', $pp->projectPurchase->sales->branch)->get() as $stock){
				$product_location .= 'Warehouse : '.$stock->warehouse->name.' qty : '.$stock->qty.'<br>';
			}
			
			$data[] = [
				'product_id'		=> $pp->product_id,
				'product_name'		=> $pp->product->name(),
				'qty'				=> $pp->qty,
				'unit'				=> $pp->unit == 3 ? 'Box' : $pp->unit(),
				'convertunit'		=> $pp->unit == 3 ? 2 : $pp->unit,
				'qty_left'			=> $pp->qty - $tot,
				'qty_sent'			=> $tot,
				'm2'				=> $pp->m2,
				'fixunit'			=> $m2 < 1.1 && $pp->product->type->category->parent()->id !== 18 ? round($pp->qty * $m2,2) : $pp->qty,
				'product_location'	=> $product_location
			];
		}
		
		return response()->json($data);
	}
	
	public function getPurchaseInfo(Request $request)
	{
		$projectpurchase = ProjectPurchaseProduct::where('project_purchase_id',$request->purchaseid)->get();
		$project = ProjectPurchase::find($request->purchaseid);
		$projectproduction = ProjectProduction::where('project_purchase_id',$request->purchaseid)->get();
		
		$total = 0;
		$totalpaid = 0;
		$progressleft = 100;
		$progresstotal = 0;
		$datapayment = [];
		
		foreach($projectproduction as $ppc){
			$progresstotal += $ppc->progress;
		}
		
		foreach($projectpurchase as $pp){
			$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
			
			if($pp->unit == '3' || $pp->unit == '2'){
				if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
					$total += $pp->price * $pp->qty;
				}else{
					if($m2 < 1.1 && date('Y-m',strtotime($project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
						$total += $pp->price * $pp->qty;
					}else{
						$total += $pp->price * $pp->qty * $m2;
					}
					
				}
				
			}else{
				$total += $pp->price * $pp->qty;
			}
        }
		
		foreach($project->projectPurchasePayment as $ppp){
			$totalpaid += $ppp->nominal;
			$datapayment[] = [
				'date'		=> $ppp->date,
				'bank'		=> $ppp->coa->name,
				'nominal'	=> $ppp->projectPurchase->currency->symbol.' '.number_format($ppp->nominal,2,',','.'),
				'status'	=> $ppp->status()
			];
		}
		
		$project['supplier_name'] = $project->supplier->name;
		$project['customer_name'] = $project->customer ?$project->customer->name : '';
		$project['country_name'] = $project->country->name;
		$project['city_name'] = $project->city->name;
		$project['currency_name'] = $project->currency->name;
		$project['sales_name'] = $project->sales->name;
		$project['so_code'] = $project->projectSale ? $project->projectSale->code. ' - ' .$project->projectSale->project->code : '';
		
		$purchaseproduct = [];
		
		foreach($project->projectPurchaseProduct()->orderBy('id')->get() as $ppp){
			if($ppp->unit == '3' || $ppp->unit == '2'){
				$purchaseproduct[] = [
					'product_id'		=> $ppp->product_id,
					'product_name'		=> $ppp->product->name(),
					'product_code'		=> $ppp->product->type->code,
					'qty'          		=> round($ppp->qty,0),
					'qty_left'			=> round($ppp->qty,0),
					'unit'          	=> 'Box',
					'unitraw'          	=> $ppp->unit,
					'remark'			=> $ppp->remark,
					'price'				=> number_format($ppp->price,2,',','.'),
					'budget_price'		=> number_format($ppp->priceBudget(),0,',','.'),
					'sell_price'		=> number_format($ppp->realSellPrice(),2,',','.'),
					'm2'				=> (( $ppp->product->type->length * $ppp->product->type->width ) / 10000) * $ppp->product->carton_pcs
				];
			}else{
				$purchaseproduct[] = [
					'product_id'		=> $ppp->product_id,
					'product_name'		=> $ppp->product->name(),
					'product_code'		=> $ppp->product->type->code,
					'qty'          		=> $ppp->qty,
					'qty_left'			=> $ppp->qty,
					'unit'          	=> $ppp->unit(),
					'unitraw'          	=> $ppp->unit,
					'remark'			=> $ppp->remark,
					'price'				=> number_format($ppp->price,2,',','.'),
					'budget_price'		=> number_format($ppp->priceBudget(),0,',','.'),
					'sell_price'		=> number_format($ppp->realSellPrice(),2,',','.'),
					'm2'				=> 1,
				];
			}
		}
		
		if($project->project){
			return response()->json([
				'purchase'		=> $project,
				'purchaseproduct' => $purchaseproduct,
				'datapayment'	=> $datapayment,
				'progress_left' => $progressleft - $progresstotal,
				'supplier_name' => $project->supplier->name,
				'total' => $project->currency->symbol.' '.number_format($total,2,',','.'),
				'totalraw' => $total,
				'totalpaid' => $project->currency->symbol.' '.number_format($totalpaid,2,',','.'),
				'totalleft' => number_format($total - $totalpaid,2,',','.'),
				'status' => '422',
				'message' => 'Sorry, this purchase was created under Project. You must edit on project page.'
			]);
		}else{
			return response()->json([
				'purchase'		=> $project,
				'purchaseproduct' => $purchaseproduct,
				'datapayment'	=> $datapayment,
				'progress_left' => $progressleft - $progresstotal,
				'supplier_name' => $project->supplier->name,
				'total' => $project->currency->symbol.' '.number_format($total,2,',','.'),
				'totalraw' => $total,
				'totalpaid' => $project->currency->symbol.' '.number_format($totalpaid,2,',','.'),
				'totalleft' => number_format($total - $totalpaid,2,',','.'),
			]);
		}
	}
	
	public function getSalesInfo(Request $request)
	{
		$projectsale = ProjectSale::find($request->idprojectsale);
		$sales_id = $projectsale->sales_id;
		$customer_id = $projectsale->project->customer_id;
		$projectdelivery = ProjectDelivery::where('project_sale_id',$request->idprojectsale)->get();
		$warehouse = [];
		
		if(count($projectsale->project->projectWarehouse) > 0){
			$warehouse['id'] = $projectsale->project->projectWarehouse()->first()->warehouse->id;
			$warehouse['name'] = $projectsale->project->projectWarehouse()->first()->warehouse->name;
		}
		
		$totalpaid = 0;
		$total = 0;
		$grandtotal = 0;
		
		foreach($projectsale->projectSalePay as $psp){
			$totalpaid += $psp->nominal;
		}
		
		$arrDelivery = [];
		
		foreach($projectdelivery as $pd){
			
			if($pd->grandtotal_product + $pd->grandtotal_service - $pd->totalPay() > 1){
				$arrDelivery[] = [
					'id'			=> $pd->id,
					'code'			=> $pd->code,
					'proforma_code'	=> $pd->proforma_code,
					//'balance'		=> $pd->getBalance()
					'balance'		=> number_format($pd->grandtotal_product + $pd->grandtotal_service - $pd->totalPay(),0,',','.')
				];
			}
		}
		
		foreach($projectsale->projectSaleProduct as $pp){
			if($pp->unit == '3' || $pp->unit == '2'){
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				
				if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
					$countbox = $pp->qty;
					$total += $pp->best_price * $countbox;
				}else{
					if($m2 < 1.1 && date('Y-m',strtotime($projectsale->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
						$countbox = $pp->qty;
						$total += $pp->best_price * $countbox;
					}else{
						$countbox = ceil(round($pp->qty / $m2,2));
						$total += $pp->best_price * $m2 * $countbox;
					}
					
				}
			}else if($pp->unit == '1' || $pp->unit == '4'){
				$total += $pp->best_price * $pp->qty;
			}
		}
		
		if(date('Y-m-d',strtotime($projectsale->created_at)) < '2022-04-01'){
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		}else{
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}
		
		$other = $projectsale->ppn_cost == '1' ? ($projectsale->delivery_cost + $projectsale->cutting_cost + $projectsale->misc_cost) + ($persenppn * $projectsale->delivery_cost + $projectsale->cutting_cost + $projectsale->misc_cost) : $projectsale->delivery_cost + $projectsale->cutting_cost + $projectsale->misc_cost;
		
		$ppn = 0;
		
		if($projectsale->project->ppn == '1'){
			$ppn = $persenppn * $total;
		}
		
		$grandtotal = $ppn + $total + $other;
		
		return response()->json([
			'sales_id' => $sales_id,
			'sales_address' => $projectsale->address,
			'sales_note' => $projectsale->note,
			'sales_manager' => $projectsale->project->manager,
			'ppn_cost' => $projectsale->ppn_cost,
			'delivery_cost' => number_format($projectsale->delivery_cost,2,',','.'),
			'cutting_cost' => number_format($projectsale->cutting_cost,2,',','.'),
			'misc_cost' => number_format($projectsale->misc_cost,2,',','.'),
			'mid_yes_no' => $projectsale->mid_yes_no,
			'mid_type' => $projectsale->mid_type,
			'mid_fee' => number_format($projectsale->mid_fee,2,',','.'),
			'mid_note' => $projectsale->mid_note,
			'currency_id' => $projectsale->currency_id,
			'currency_rate' => number_format($projectsale->currency_rate,2,',','.'),
			'sales_name' => $projectsale->sales->name,
			'customer_id' => $customer_id,
			'customer_name' => $projectsale->project->customer->name,
			'customer_email' => $projectsale->project->customer->email,
			'customer_phone' => $projectsale->project->customer->phone,
			'city_id' => $projectsale->project->city_id,
			'city_name' => $projectsale->project->city->name,
			'created_at' => date('Y-m-d',strtotime($projectsale->created_at)),
			'payment_total' => number_format($grandtotal,2,',','.'),
			'payment_paid' => number_format($totalpaid,2,',','.'),
			'payment_left' => number_format($grandtotal - $totalpaid,2,',','.'),
			'do_list'	=> $arrDelivery,
			'warehouse' => $warehouse
		]);
	}
	
	public function getPaymentInfo(Request $request)
	{
		$projectpay = ProjectPay::find($request->idprojectpayment);
		
		return response()->json([
			'sale_code' 		=> $projectpay->projectSale->code,
			'sale_id'			=> $projectpay->projectSale->id,
			'delivery_id'		=> $projectpay->project_delivery_id,
			'bill_id'			=> $projectpay->project_bill_id,
			'image'				=> $projectpay->attachment(),
			'date'				=> $projectpay->date,
			'payment_method'	=> $projectpay->payment_method,
			'nominal'			=> number_format(round($projectpay->nominal,0),0,',','.'),
			'coa_id'			=> $projectpay->coa_id,
			'note'				=> $projectpay->note,
			'giro'				=> $projectpay->giro,
			'giro_code'			=> $projectpay->giro_code,
			'giro_date'			=> $projectpay->giro_date
		]);
	}
	
	public function getPurchasePaymentInfo(Request $request)
	{
		$projectpay = ProjectPayment::find($request->idpay);
		
		return response()->json([
			'purchase_code' 	=> $projectpay->projectPurchase->code,
			'purchase_id'		=> $projectpay->projectPurchase->id,
			'date'				=> $projectpay->date,
			'nominal'			=> number_format(round($projectpay->nominal,0),0,',','.'),
			'bank'				=> $projectpay->bank,
			'status'			=> $projectpay->status,
			'giro'				=> $projectpay->giro,
			'giro_code'			=> $projectpay->giro_code,
			'giro_date'			=> $projectpay->giro_date
		]);
	}
	
	public function getSupplierCurrency(Request $request)
	{
		$supplier = Supplier::find($request->idsupp);
		
		$data = [];
		
		foreach($supplier->supplierCurrency as $sc){
			$data[] = [
				'id' 		=> $sc->currency->id,
				'code' 		=> $sc->currency->code,
				'name'		=> $sc->currency->name,
				'symbol'	=> $sc->currency->symbol,
				'up'		=> 'Mr/Mrs. '.$supplier->pic.' Phone. '.$supplier->phone
			];
		}
		
		return response()->json($data);
	}
	
	public function getShipmentInfo(Request $request)
	{
		$projectshipment = ProjectShipment::where('project_purchase_id',$request->idpo)->get();
		
		
		$listshipment = [];
		
		foreach($projectshipment as $ps){
			$listshipment[] = [
				'po_code' 				=> $ps->projectPurchase->code,
				'shipment_id' 			=> $ps->id,
				'loading_date' 			=> $ps->loading_date,
				'departure_date' 	    => $ps->departure_date,
				'from_port' 			=> $ps->from_port,
				'to_port' 				=> $ps->to_port,
				'eta' 				    => $ps->eta,
				'delivery_method' 	    => $ps->delivery_method,
				'note' 				    => $ps->note,
				'proof' 				=> $ps->attachment(),
				'shipment_code'			=> $ps->shipment_code
			];
		}
		
		return response()->json([
			'shipment_list'		=> $listshipment,
		]);
	}
	
	public function getShipmentEdit(Request $request)
	{
		$projectshipment = ProjectShipment::find($request->id);
		
		$detail = [];
		
		foreach($projectshipment->projectShipmentProduct as $row){
			$detail[] = [
				'product_id'	=> $row->product_id,
				'unit'			=> $row->unit(),
				'unitraw'		=> $row->unit,
				'qty'			=> $row->qty,
				'product'		=> $row->product->name()
			];
		}
		
		return response()->json([
			'main'		=> $projectshipment,
			'po_code'   => $projectshipment->projectPurchase->code,
			'detail'	=> $detail
		]);
	}
	
	public function getWarehouseEdit(Request $request)
	{
		$projectwarehouse = ProjectWarehouse::find($request->id);
		
		$main = [
			'project_purchase_id'	=> $projectwarehouse->project_purchase_id,
			'shipment_id'			=> $projectwarehouse->project_shipment_id,
			'shipment_code'			=> $projectwarehouse->projectShipment->shipment_code,
			'code'					=> $projectwarehouse->code,
			'person'				=> $projectwarehouse->person,
			'date_receive'			=> str_replace(' ','T',$projectwarehouse->date_receive),
			'warehouse_id'			=> $projectwarehouse->warehouse_id,
			'warehouse_name'		=> $projectwarehouse->warehouse->code.' - '.$projectwarehouse->warehouse->name
		];
		
		$detail = [];
		
		foreach($projectwarehouse->projectWarehouseProduct as $row){
			$detail[] = [
				'product_id'		=> $row->product_id,
				'unit'				=> $row->unit(),
				'unitraw'			=> $row->unit,
				'qty'				=> $row->qty,
				'qty_broken'		=> $row->qty_broken,
				'unit_broken'		=> $row->unit_broken(),
				'unit_broken_raw'	=> $row->unit_broken,
				'product_name'			=> $row->product->name()
			];
		}
		
		return response()->json([
			'main'		=> $main,
			'detail'	=> $detail
		]);
	}
	
	public function getShipmentProduct(Request $request)
	{
		$projectshipmentproduct = ProjectShipmentProduct::where('project_shipment_id',$request->idshipment)->get();
		
		$listproduct = [];
		
		foreach($projectshipmentproduct as $psp){
			$listproduct [] = [
				'product_id'	=> $psp->product_id,
				'product_name'	=> $psp->product->name(),
				'qty'			=> $psp->qty,
				'unitraw'		=> $psp->unit,
				'unit'			=> $psp->unit()
			];
		}
		
		return response()->json([
			'shipment_product'	=> $listproduct
		]);
	}
	
	public function getShadingProduct(Request $request){
		$productshading = ProductShading::where('product_id',$request->val)->orderByDesc('qty')->get();
		$productname =	Product::find($request->val);
		
		$shading = [];
		$product_name = $productname->name();
		
		foreach($productshading as $ps){
			$shading[] = [
				'id'					=> $ps->id,
				'product_id'			=> $ps->product_id,
				'warehouse_code'		=> $ps->warehouse_code,
				'warehouse_name'		=> $ps->warehouse->name,
				'stock_code'			=> $ps->stock_code,
				'code'					=> $ps->code,
				'qty'					=> $ps->qty,
				'unit'					=> $ps->product->type->stockUnit->name
			];
		}
		
		return response()->json([
			'shading'		=> $shading,
			'product_name'	=> $product_name
		]);
	}
	
	public function updateStatusSample(Request $request){
		$projectsample = ProjectSample::find($request->id);
		$projectsample->status = $request->val;
		
		if($request->val == '1' || $request->val == '3'){
			$projectsample->returned_at = null;
		}elseif($request->val == '2'){
			$projectsample->returned_at = now();
			
			/* $productdesc = '';
			
			foreach($projectsample->projectSampleProduct as $key => $spi) {
				$productdesc .= '. Detail products : '.$spi->product->name().', Qty : '.$spi->qty.' '.$spi->unit().', Size : '.$spi->size().'<br>';
			}
			
			$debetcb = 332;
								
			$kreditcb = 341;
			
			$cb = CashBank::create([
				'user_id'     		=> session('bo_id'),
				'lookable_type'     => 'project_samples',
				'lookable_id'		=> $projectsample->id,
				'code'        		=> strtoupper(Str::random(15)),
				'date'        		=> date('Y-m-d',strtotime($projectsample->returned_at)),
				'type'        		=> '2',
				'description' 		=> 'Project Sample Code '.$projectsample->code.' in Project code '.$projectsample->project->code.' with Customer '.$projectsample->project->customer->name.' '.$productdesc.' has been returned.'
			]);
			
			if($cb){
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $debetcb,
					'branch'		=> '1',
					'type'       	=> '1',
					'nominal'      	=> 1,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d',strtotime($projectsample->returned_at)),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $debetcb,
					'branch'		   => '1',
					'type'	           => '1',
					'nominal'          => 1,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $kreditcb,
					'branch'		=> '1',
					'type'       	=> '2',
					'nominal'      	=> 1,
					'note'         	=> ''
				]);

				Journal::insert([
					'date_transaction' => date('Y-m-d',strtotime($projectsample->returned_at)),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $kreditcb,
					'branch'		   => '1',
					'type'	           => '2',
					'nominal'          => 1,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
			} */
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$projectsample->project->code.' with details sample code '.$projectsample->code.' products has been returned by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
		}
		
		$projectsample->save();
		
		activity()
			->performedOn(new ProjectSample())
			->causedBy(session('bo_id'))
			->withProperties($projectsample)
			->log('Change status sample product');
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function skipForm(Request $request){
		$project = Project::find($request->project);
		
		$step = $request->step;
		
		if($step == 5){
			$project->progress = $project->progress < 30 ? 30 : $project->progress;
		}elseif($step == 6){
			$project->progress = $project->progress < 35 ? 35 : $project->progress;
		}elseif($step == 8){
			$project->progress = $project->progress < 40 ? 40 : $project->progress;
		}elseif($step == 11){
			$project->progress = $project->progress < 48 ? 48 : $project->progress;
		}elseif($step == 13){
			$project->progress = $project->progress < 55 ? 55 : $project->progress;
		}elseif($step == 2){
			$project->progress = $project->progress < 15 ? 15 : $project->progress;
		}elseif($step == 3){
			$project->progress = $project->progress < 20 ? 20 : $project->progress;
		}elseif($step == 4){
			if(count($project->projectQuotation) == 0){
				return response()->json([
					'status'  => 422,
					'message' => 'Ups, this project must be at least has 1 quotation.'
				]);
			}else{
				$project->progress = $project->progress < 25 ? 25 : $project->progress;
			}
		}else{
			$project->progress = $project->progress;
		}
	
		$project->save();
		
		#start notif
		$role = array('1');
		$title = 'Project has been updated!';
		$description = 'Project '.$project->code.' with step '.$step.' has been skipped by '.session('bo_name');
		$link = '#';
		Notification::sendNotif($role,$title,$description,$link);
		#end notif
		
		activity()
			->performedOn(new Project())
			->causedBy(session('bo_id'))
			->withProperties($project)
			->log('Skip project Step '.$step);
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function addShipmentTracking(Request $request){
		
		$trackshipment = ProjectShipmentTrack::create([
			'user_id'				=> session('bo_id'),
			'project_shipment_id'	=> $request->id,
			'note'         			=> $request->note
		]);
		
		$shipmenttrack = ProjectShipmentTrack::where('project_shipment_id',$request->id)->get();
		
		activity()
			->performedOn(new ProjectShipmentTrack())
			->causedBy(session('bo_id'))
			->withProperties($trackshipment)
			->log('Add tracking to shipment');
		
		return response()->json($shipmenttrack);
	}
	
	public function deleteShipmentTracking(Request $request){
		
		$trackingshipment = ProjectShipmentTrack::find($request->id)->delete();
		
		activity()
			->performedOn(new ProjectShipmentTrack())
			->causedBy(session('bo_id'))
			->withProperties($trackingshipment)
			->log('Delete tracking to shipment');
			
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function getShipmentTracking(Request $request){
		$shipmenttrack = ProjectShipmentTrack::where('project_shipment_id',$request->id)->get();
		
		return response()->json($shipmenttrack);
	}

	public function getDeliveryTracking(Request $request){
		$deliverytrack = ProjectDeliveryTrack::where('project_delivery_id',$request->id)->get();
		
		$data = [];
		
		foreach($deliverytrack as $row){
			$data[] = [
				'id'					=> $row->id,
				'user'					=> $row->user->name,
				'project_delivery_id'	=> $row->project_delivery_id,
				'note'					=> $row->note,
				'image'					=> $row->image ? '<a href="' . $row->image() . '" data-lightbox="' . $row->projectDelivery->code . '" data-title="' . $row->projectDelivery->code . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>',
				'created_at'			=> $row->created_at
			];
		}
		
		return response()->json($data);
	}
	
	public function addDeliveryTracking(Request $request){
		
		$trackdelivery = ProjectDeliveryTrack::create([
			'user_id'				=> session('bo_id'),
			'project_delivery_id'	=> $request->id,
			'note'         			=> $request->note,
			'image'					=> $request->file('file') ? $request->file('file')->store('public/project') : ''
		]);
		
		$deliverytrack = ProjectDeliveryTrack::where('project_delivery_id',$request->id)->get();
		
		$data = [];
		
		foreach($deliverytrack as $row){
			$data[] = [
				'id'					=> $row->id,
				'user'					=> $row->user->name,
				'project_delivery_id'	=> $row->project_delivery_id,
				'note'					=> $row->note,
				'image'					=> $row->image ? '<a href="' . $row->image() . '" data-lightbox="' . $row->projectDelivery->code . '" data-title="' . $row->projectDelivery->code . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>',
				'created_at'			=> $row->created_at
			];
		}
		
		activity()
			->performedOn(new ProjectDeliveryTrack())
			->causedBy(session('bo_id'))
			->withProperties($trackdelivery)
			->log('Add tracking to delivery');
		
		return response()->json($data);
	}
	
	public function addReceivedProof(Request $request){
		$purchasedelivery = ProjectDelivery::find($request->id);
		$due_date_tt = $request->due_date_tt;
		
		if($request->hasFile('file')) {
			if(Storage::exists($purchasedelivery->image)) {
				Storage::delete($purchasedelivery->image);
			}

			$image = $request->file('file')->store('public/receipt');
		} else {
			$image = $purchasedelivery->image;
		}
		
		if($request->hasFile('file2')) {
			if(Storage::exists($purchasedelivery->image_tt)) {
				Storage::delete($purchasedelivery->image_tt);
			}

			$image2 = $request->file('file2')->store('public/receipt');
		} else {
			$image2 = $purchasedelivery->image_tt;
		}
		
		$purchasedelivery->update([
			'image'			=> $image,
			'due_date_tt'	=> $due_date_tt,
			'image_tt'		=> $image2,
		]);
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function addReceivedDate(Request $request){
		
		$purchasedelivery = ProjectDelivery::find($request->id);
		
		if(CheckCutOff::check($purchasedelivery->projectSale->sales->branch,substr($request->date,0,7))){
			
			$budgetingProject = $purchasedelivery->project->budgetingProject()->first();
			$nominalreserved =	$budgetingProject->budgetingProjectDetail()->where('coa_id',333333)->first()->nominal;
		
				
			if($nominalreserved > 0 && $budgetingProject->branch == '2'){
				$cekpr = PurchaseRequest::where('link_type','budgeting_projects')->where('link_id',$budgetingProject->id)->first();
				
				if($cekpr){
					
					$cb = CashBank::where('lookable_id',$cekpr->id)->where('lookable_type','purchase_requests')->get();
			
					foreach($cb as $row){
						$row->deleteDetail();
						$row->delete();
					}
					
					$cekpr->deleteFile();
					$cekpr->delete();
				}
				
				$pr = PurchaseRequest::create([
					'bill_to'					=> 'Bu Shanti PTA',
					'title'						=> 'RESERVER PROFIT PURCHASE REQUEST',
					'date'	     				=> date('Y-m-d'),
					'due_date'	     			=> date('Y-m-d'),
					'user_id'					=> $budgetingProject->user_id,
					'branch'					=> $budgetingProject->branch,
					'item'						=> 'BUDGETING PROJECT PURCHASE REQUEST PJ NO '.$budgetingProject->project->code.' - RESERVED PROFIT BY SYSTEM SW.',
					'total_nominal'				=> $nominalreserved,
					'status'					=> 'PEND',
					'link_type'					=> 'budgeting_projects',
					'link_id'					=> $budgetingProject->id,
					'coa_id'					=> 332
				]);
				
				#send approval
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval,'purchase_requests',$pr->id,'approved_by',session('bo_id'));
				
				SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$pr->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			}

			if(!$request->has('file')) {
				return response()->json([
					'status'  => 500,
					'message' => 'Error. Please choose file sir/madam.'
				]);
			}
			
			if($request->arrProduct){
				foreach($request->arrProduct as $key => $ar){
					$purchasedeliveryproduct = ProjectDeliveryProduct::where('project_delivery_id',$request->id)->where('product_id',$ar)->update(['qty' => $request->arrQty[$key]]);
				}

				ProjectDelivery::find($purchasedelivery->id)->updateGrandtotal();
			}
			
			if($request->has('file')) {
				if(Storage::exists($purchasedelivery->image)) {
					Storage::delete($purchasedelivery->image);
				}

				$image = $request->file('file')->store('public/project');
			} else {
				$image = $purchasedelivery->image;
			}
			
			$purchasedelivery->update([
				'received_date'	=> $request->date,
				'due_date'		=> $request->duedate,
				'image'			=> $image
			]);
			
			$arr = $purchasedelivery->getTotal();
			
			if($purchasedelivery->is_sales == '2'){
				$debet1 = 341;
			}else{
				$debet1 = $purchasedelivery->project->in_store == '0' ? 122 : 356;
			}
			
			$type = '3';
			$description = 'Project with delivery code '.$purchasedelivery->code;
			
			$debetnominal1 = round($arr['totalpurchase']);
			$kreditnominal1 = round($arr['totalpurchase']);
			$debetnominal2 = round($arr['totaldelivery']);
			$kreditnominal2 = round($arr['totaldelivery']);
			
			$kredit1 = 31;
			
			#cek kalao ada bill gak
			if(count($purchasedelivery->project->projectBill) > 0){
				$dp = 0;
					
				foreach($purchasedelivery->project->projectBill as $pb){
					$dp += $pb->nominal + $pb->nominal_service;
				}
				
				foreach($purchasedelivery->projectSale->projectDelivery()->where('id','<>',$purchasedelivery->id)->where('id','<',$purchasedelivery->id)->whereNotNull('received_date')->get() as $otherdelivery){
					$dp -= ($otherdelivery->grandtotal_product + $otherdelivery->grandtotal_service);
				}
				
				$totaldelivery = round($arr['totaldelivery']);
				
				if($dp >= $totaldelivery){
					$debet2 = 67;
				}else{
					$debet2 = 27; //AR
					$debetnominal2 = $dp > 0  ? $totaldelivery - $dp : $totaldelivery;
					$debet3 = 67; //CUST DEPOSIT
					$debetnominal3 = $dp > 0 ? $dp : 0;
				}
			}else{
				
				$dp = 0;
					
				foreach($purchasedelivery->projectSale->projectSalePay as $ppp){
					if($ppp->payment_method == '1'){
						$dp += $ppp->nominal;
					}
				}
				
				$totaldelivery = round($arr['totaldelivery']);
				
				if($dp >= $totaldelivery){
					$debet2 = 67; //CUST DEPO
				}else{
					$debet2 = 27; //AR
				}
			}
			
			if($purchasedelivery->project->ppn == '1'){
				
				if(date('Y-m-d',strtotime($purchasedelivery->projectSale->created_at)) < '2022-04-01'){
					$ppnpembagi = 1.1;
				}else{
					$ppnpembagi = 1.11;
				}
				if($purchasedelivery->project->in_store == '1'){
					$kredit2 = 354;
				}else{
					$kredit2 = 284;
				}
				
				$kreditnominal2 = round($kreditnominal2 / $ppnpembagi,0);
				$ppnkeluaran = 71;
				$ppnnominal = round($arr['totaldelivery'] - ($arr['totaldelivery'] / $ppnpembagi),0);
			}else{
				if($purchasedelivery->project->in_store == '1'){
					$kredit2 = 355;
				}else{
					$kredit2 = 285;
				}
			}
			
			$othercost = $purchasedelivery->getServiceCost();
			
			if($othercost > 0){
				if($purchasedelivery->projectSale->ppn_cost == '1'){
					
					if($request->date < '2022-04-01'){
						$persenppn = 0.1;
					}else{
						$persenppn = 0.11;
					}
					
					$kredit3 = 286;
					$kreditnominal3 = $othercost;
					$kredit4 = 71;
					$kreditnominal4 = $othercost * $persenppn;
					$debetnominal2 += $kreditnominal3 + $kreditnominal4;
				}else{
					$kredit3 = 287;
					$kreditnominal3 = $othercost;
					$debetnominal2 += $kreditnominal3;
				}
			}
			
			if($purchasedelivery->projectSale->sales->branch == '2'){
				$debit6 = 296;
				$kredit6 = 281;
				$debitnominal6 = 0.01 * $kreditnominal2;
				$kreditnominal6 = $debitnominal6;
			}
			
			#START
			
			if($purchasedelivery->is_sales == '2'){
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'lookable_type'  	=> 'project_deliveries',
					'lookable_id'		=> $purchasedelivery->id,
					'code'        		=> strtoupper(Str::random(15)),
					'date'        		=> $request->date,
					'type'        		=> $type,
					'description' 		=> $description
				]);
				
				if($cb){
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debet1,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> round($debetnominal1,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debet1,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => round($debetnominal1,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kredit1,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> round($kreditnominal1,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kredit1,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => round($kreditnominal1,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
			}else{
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'lookable_type'  	=> 'project_deliveries',
					'lookable_id'		=> $purchasedelivery->id,
					'code'        		=> strtoupper(Str::random(15)),
					'date'        		=> $request->date,
					'type'        		=> $type,
					'description' 		=> $description
				]);
				
				if($cb){
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debet1,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> round($debetnominal1,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debet1,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => round($debetnominal1,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kredit1,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> round($kreditnominal1,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kredit1,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => round($kreditnominal1,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debet2,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> round($debetnominal2,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debet2,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => round($debetnominal2,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					if(isset($debet3)){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debet3,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '1',
							'nominal'      	=> round($debetnominal3,0),
							'note'         	=> ''
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debet3,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '1',
							'nominal'          => round($debetnominal3,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kredit2,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> round($kreditnominal2,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kredit2,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => round($kreditnominal2,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					if(isset($kredit3)){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kredit3,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> round($kreditnominal3,0),
							'note'         	=> ''
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kredit3,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => round($kreditnominal3,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
					
					if(isset($kredit4)){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kredit4,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> round($kreditnominal4,0),
							'note'         	=> ''
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kredit4,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => round($kreditnominal4,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
					
					if($purchasedelivery->project->ppn == '1'){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $ppnkeluaran,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> round($ppnnominal,0),
							'note'         	=> ''
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $ppnkeluaran,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => round($ppnnominal,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
				
				if($purchasedelivery->projectSale->mid_yes_no == '1' && $purchasedelivery->is_sales !== '2' && count($purchasedelivery->projectSale->projectDelivery) == 1){
					$debetcb5 = 299;
					$kreditcb5 = 281;
					
					$totalmidfee = round(str_replace(',','.',str_replace('.','',$purchasedelivery->getTotalMiddleman())));
					
					$rowpr = PurchaseRequest::where('link_type','project_sales')->where('link_id',$purchasedelivery->projectSale->id)->get();
					
					if($rowpr){
						foreach($rowpr as $pay){
							$totalmidfee -= $pay->total_nominal;
						}
					}
					
					if($totalmidfee > 0){
						$debetnominal5 = $totalmidfee;
						$query = PurchaseRequest::create([
							'date'	     			=> $request->date,
							'bill_to'				=> $purchasedelivery->project->customer->name,
							'title'					=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code,
							'item'					=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code.' SO NO. '.$purchasedelivery->projectSale->code.' with Customer : '.$purchasedelivery->project->customer->name,
							'user_id'				=> $purchasedelivery->projectSale->user_id,
							'branch'				=> $purchasedelivery->projectSale->sales->branch,
							'total_nominal'			=> $debetnominal5,
							'status'				=> 'APPR',
							'image'					=> null,
							'term'					=> null,
							'supplier_id'			=> 132,
							'term_days'				=> 7,
							'due_date'				=> date('Y-m-d',strtotime($request->date.' + 7 days')),
							'link_type' 			=> 'project_sales',
							'link_id'				=> $purchasedelivery->projectSale->id,
							'approved_by'			=> 7,
							'coa_id'				=> 281
						]);
					
			
						$kreditnominal5 = $debetnominal5;
						
						$cb = CashBank::create([
							'user_id'     		=> session('bo_id'),
							'lookable_type'  	=> 'projects',
							'lookable_id'		=> $purchasedelivery->project_id,
							'code'        		=> 'PR-'.$query->id,
							'date'        		=> $request->date,
							'type'        		=> '2',
							'description' 		=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code.' SO NO. '.$purchasedelivery->projectSale->code.' with Customer : '.$purchasedelivery->project->customer->name
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debetcb5,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '1',
							'nominal'      	=> $debetnominal5,
							'note'         	=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code.' SO NO. '.$purchasedelivery->projectSale->code.' with Customer : '.$purchasedelivery->project->customer->name
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debetcb5,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '1',
							'nominal'          => $debetnominal5,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kreditcb5,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> $kreditnominal5,
							'note'         	=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code.' SO NO. '.$purchasedelivery->projectSale->code.' with Customer : '.$purchasedelivery->project->customer->name
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kreditcb5,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => $kreditnominal5,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
					}
				}
			}
			
			if($purchasedelivery->project->in_store == '1'){
				$fee_pta = $debetnominal1 * 0.05;
				
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'code'        		=> 'FEE-SMB-'.strtoupper(Str::random(10)),
					'lookable_type'		=> 'projects',
					'lookable_id'		=> $purchasedelivery->project->id,
					'customer_id'		=> 1959,
					'supplier_id'		=> 155,
					'date'        		=> $request->date,
					'type'        		=> '3',
					'description' 		=> 'Fee SMB from DO Code '.$purchasedelivery->code
				]);
				
				if($cb){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 351,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee SMB from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 351,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 332,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee SMB from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 332,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					#balik
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 27,
						'branch'		=> '2',
						'type'       	=> '1',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee SMB from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 27,
						'branch'		   => '2',
						'type'	           => '1',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 209,
						'branch'		=> '2',
						'type'       	=> '2',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee SMB from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 209,
						'branch'		   => '2',
						'type'	           => '2',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
			}
			
			// FEE PTA
			if($purchasedelivery->projectSale->sales->branch == '2'){
				$fee_pta = round($debetnominal1 * 0.05);
				
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'code'        		=> 'FEE-PTA-'.strtoupper(Str::random(10)),
					'lookable_type'		=> 'projects',
					'lookable_id'		=> $purchasedelivery->project->id,
					'customer_id'		=> 9,
					'supplier_id'		=> 132,
					'date'        		=> $request->date,
					'type'        		=> '3',
					'description' 		=> 'Fee PTA from DO Code '.$purchasedelivery->code
				]);
				
				if($cb){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 139,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee PTA from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 139,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 332,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee PTA from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 332,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					#balik
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 27,
						'branch'		=> '1',
						'type'       	=> '1',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee PTA from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 27,
						'branch'		   => '1',
						'type'	           => '1',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 209,
						'branch'		=> '1',
						'type'       	=> '2',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee PTA from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 209,
						'branch'		   => '1',
						'type'	           => '2',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
				
				if(isset($debit6)){
					
					$pr = PurchaseRequest::create([
						'date'	     			=> $request->date,
						'bill_to'				=> 'Bu Shanti - PTA',
						'title'					=> 'Sales Commission Project '.$purchasedelivery->project->code.' DO. '.$purchasedelivery->code,
						'item'					=> 'Sales Commission Project '.$purchasedelivery->project->code.' DO. '.$purchasedelivery->code.' Cust. '.$purchasedelivery->project->customer->name,
						'user_id'				=> 7,
						'coa_id'				=> 281,
						'branch'				=> $purchasedelivery->projectSale->sales->branch,
						'total_nominal'			=> $debitnominal6,
						'total_cash_advance'	=> 0,
						'status'				=> 'APPR',
						'term'					=> '0',
						'supplier_id'			=> 136,
						'term_days'				=> 0,
						'due_date'				=> $request->date,
						'link_type' 			=> 'project_deliveries',
						'link_id'				=> $purchasedelivery->id
					]);
					
					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'code'        		=> 'PR-'.$pr->id,
						'lookable_type'		=> 'projects',
						'lookable_id'		=> $purchasedelivery->project->id,
						'date'        		=> $request->date,
						'type'        		=> '3',
						'description' 		=> 'Sales Commission from DO Code '.$purchasedelivery->code
					]);
					
					if($cb){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debit6,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '1',
							'nominal'      	=> $debitnominal6,
							'note'         	=> 'Sales Commission from DO Code '.$purchasedelivery->code
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debit6,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '1',
							'nominal'          => $debitnominal6,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kredit6,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> $kreditnominal6,
							'note'         	=> 'Sales Commission from DO Code '.$purchasedelivery->code
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kredit6,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => $kreditnominal6,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
			}
			
			#END
			
			#updatestock
			$delivery = ProjectDelivery::find($request->id);
			
			foreach($delivery->projectDeliveryProduct as $row){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$delivery->warehouse_id)->where('branch',$delivery->projectSale->sales->branch)->first();
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty - $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $delivery->warehouse_id,
						'qty'			=> $row->qty * -1,
						'unit'			=> $row->unit,
						'branch'		=> $delivery->projectSale->sales->branch
					]);
				}
			}
			
			$ceklunas = Project::find($purchasedelivery->project->id)->getBalance();
							
			if($ceklunas <= 0){
				Project::find($purchasedelivery->project->id)->update([
					'progress' => 85
				]);
				
				#send approval
				$roleapproval = array('1');
				Approval::sendApproval($roleapproval,'projects',$purchasedelivery->project->id,'',session('bo_id'));
				#end approval
				
				SendMessage::send(env('OWNER_PHONE'),'Halo pak/bu. Mohon dibantu approve Penutupan Project No. '.$purchasedelivery->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			}
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project delivery has been updated!';
			$description = 'Project '.$purchasedelivery->project->code.' with details delivery code '.$purchasedelivery->code.' has been received by the customer.';
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			#update cogs
							
			ProductCogs::updateCogs($request->date,$purchasedelivery->projectSale->sales->branch);
			
			activity()
				->performedOn(new ProjectDelivery())
				->causedBy(session('bo_id'))
				->withProperties($purchasedelivery)
				->log('Update delivery received date.');
			
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
			
		}else{
			return response()->json([
				'status'  => 503,
				'message' => 'You cannot add/edit. The journal for this month was already closed.'
			]);
		}
	}

	public function unreceivedDo(Request $request){
		$purchasedelivery = ProjectDelivery::find($request->id);

		if(CheckCutOff::check($purchasedelivery->projectSale->sales->branch,substr($request->date,0,7))){
			
			$budgetingProject = $purchasedelivery->project->budgetingProject()->first();
			$nominalreserved =	$budgetingProject->budgetingProjectDetail()->where('coa_id',333333)->first()->nominal;
		
				
			if($nominalreserved > 0 && $budgetingProject->branch == '2'){
				$cekpr = PurchaseRequest::where('link_type','budgeting_projects')->where('link_id',$budgetingProject->id)->first();
				
				if($cekpr){
					
					$cb = CashBank::where('lookable_id',$cekpr->id)->where('lookable_type','purchase_requests')->get();
			
					foreach($cb as $row){
						$row->deleteDetail();
						$row->delete();
					}
					
					$cekpr->deleteFile();
					$cekpr->delete();
				}
				
				$pr = PurchaseRequest::create([
					'bill_to'					=> 'Bu Shanti PTA',
					'title'						=> 'RESERVER PROFIT PURCHASE REQUEST',
					'date'	     				=> date('Y-m-d'),
					'due_date'	     			=> date('Y-m-d'),
					'user_id'					=> $budgetingProject->user_id,
					'branch'					=> $budgetingProject->branch,
					'item'						=> 'BUDGETING PROJECT PURCHASE REQUEST PJ NO '.$budgetingProject->project->code.' - RESERVED PROFIT BY SYSTEM SW.',
					'total_nominal'				=> $nominalreserved,
					'status'					=> 'PEND',
					'link_type'					=> 'budgeting_projects',
					'link_id'					=> $budgetingProject->id,
					'coa_id'					=> 332
				]);
				
				#send approval
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval,'purchase_requests',$pr->id,'approved_by',session('bo_id'));
				
				SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$pr->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			}

			if(!$request->has('file')) {
				return response()->json([
					'status'  => 500,
					'message' => 'Error. Please choose file sir/madam.'
				]);
			}
			
			if($request->arrProduct){
				foreach($request->arrProduct as $key => $ar){
					$purchasedeliveryproduct = ProjectDeliveryProduct::where('project_delivery_id',$request->id)->where('product_id',$ar)->update(['qty' => $request->arrQty[$key]]);
				}

				ProjectDelivery::find($purchasedelivery->id)->updateGrandtotal();
			}
			
			if($request->has('file')) {
				if(Storage::exists($purchasedelivery->image)) {
					Storage::delete($purchasedelivery->image);
				}

				$image = $request->file('file')->store('public/project');
			} else {
				$image = $purchasedelivery->image;
			}
			
			$purchasedelivery->update([
				'received_date'	=> $request->date,
				'due_date'		=> $request->duedate,
				'image'			=> $image
			]);
			
			$arr = $purchasedelivery->getTotal();
			
			if($purchasedelivery->is_sales == '2'){
				$debet1 = 341;
			}else{
				$debet1 = $purchasedelivery->project->in_store == '0' ? 122 : 356;
			}
			
			$type = '3';
			$description = 'Project with delivery code '.$purchasedelivery->code;
			
			$debetnominal1 = round($arr['totalpurchase']);
			$kreditnominal1 = round($arr['totalpurchase']);
			$debetnominal2 = round($arr['totaldelivery']);
			$kreditnominal2 = round($arr['totaldelivery']);
			
			$kredit1 = 31;
			
			#cek kalao ada bill gak
			if(count($purchasedelivery->project->projectBill) > 0){
				$dp = 0;
					
				foreach($purchasedelivery->project->projectBill as $pb){
					$dp += $pb->nominal + $pb->nominal_service;
				}
				
				foreach($purchasedelivery->projectSale->projectDelivery()->where('id','<>',$purchasedelivery->id)->where('id','<',$purchasedelivery->id)->whereNotNull('received_date')->get() as $otherdelivery){
					$dp -= ($otherdelivery->grandtotal_product + $otherdelivery->grandtotal_service);
				}
				
				$totaldelivery = round($arr['totaldelivery']);
				
				if($dp >= $totaldelivery){
					$debet2 = 67;
				}else{
					$debet2 = 27; //AR
					$debetnominal2 = $dp > 0  ? $totaldelivery - $dp : $totaldelivery;
					$debet3 = 67; //CUST DEPO
					$debetnominal3 = $dp > 0 ? $dp : 0;
				}
			}else{
				
				$dp = 0;
					
				foreach($purchasedelivery->projectSale->projectSalePay as $ppp){
					if($ppp->payment_method == '1'){
						$dp += $ppp->nominal;
					}
				}
				
				$totaldelivery = round($arr['totaldelivery']);
				
				if($dp >= $totaldelivery){
					$debet2 = 67; //CUST DEPO
				}else{
					$debet2 = 27; //AR
				}
			}
			
			if($purchasedelivery->project->ppn == '1'){
				
				if(date('Y-m-d',strtotime($purchasedelivery->projectSale->created_at)) < '2022-04-01'){
					$ppnpembagi = 1.1;
				}else{
					$ppnpembagi = 1.11;
				}
				if($purchasedelivery->project->in_store == '1'){
					$kredit2 = 354;
				}else{
					$kredit2 = 284;
				}
				
				$kreditnominal2 = round($kreditnominal2 / $ppnpembagi,0);
				$ppnkeluaran = 71;
				$ppnnominal = round($arr['totaldelivery'] - ($arr['totaldelivery'] / $ppnpembagi),0);
			}else{
				if($purchasedelivery->project->in_store == '1'){
					$kredit2 = 355;
				}else{
					$kredit2 = 285;
				}
			}
			
			$othercost = $purchasedelivery->getServiceCost();
			
			if($othercost > 0){
				if($purchasedelivery->projectSale->ppn_cost == '1'){
					
					if($request->date < '2022-04-01'){
						$persenppn = 0.1;
					}else{
						$persenppn = 0.11;
					}
					
					$kredit3 = 286;
					$kreditnominal3 = $othercost;
					$kredit4 = 71;
					$kreditnominal4 = $othercost * $persenppn;
					$debetnominal2 += $kreditnominal3 + $kreditnominal4;
				}else{
					$kredit3 = 287;
					$kreditnominal3 = $othercost;
					$debetnominal2 += $kreditnominal3;
				}
			}
			
			if($purchasedelivery->projectSale->sales->branch == '2'){
				$debit6 = 296;
				$kredit6 = 281;
				$debitnominal6 = 0.01 * $kreditnominal2;
				$kreditnominal6 = $debitnominal6;
			}
			
			#START
			
			if($purchasedelivery->is_sales == '2'){
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'lookable_type'  	=> 'project_deliveries',
					'lookable_id'		=> $purchasedelivery->id,
					'code'        		=> strtoupper(Str::random(15)),
					'date'        		=> $request->date,
					'type'        		=> $type,
					'description' 		=> $description
				]);
				
				if($cb){
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debet1,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> round($debetnominal1,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debet1,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => round($debetnominal1,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kredit1,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> round($kreditnominal1,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kredit1,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => round($kreditnominal1,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
			}else{
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'lookable_type'  	=> 'project_deliveries',
					'lookable_id'		=> $purchasedelivery->id,
					'code'        		=> strtoupper(Str::random(15)),
					'date'        		=> $request->date,
					'type'        		=> $type,
					'description' 		=> $description
				]);
				
				if($cb){
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debet1,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> round($debetnominal1,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debet1,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => round($debetnominal1,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kredit1,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> round($kreditnominal1,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kredit1,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => round($kreditnominal1,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debet2,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> round($debetnominal2,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debet2,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => round($debetnominal2,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					if(isset($debet3)){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debet3,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '1',
							'nominal'      	=> round($debetnominal3,0),
							'note'         	=> ''
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debet3,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '1',
							'nominal'          => round($debetnominal3,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kredit2,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> round($kreditnominal2,0),
						'note'         	=> ''
					]);

					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kredit2,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => round($kreditnominal2,0),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					if(isset($kredit3)){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kredit3,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> round($kreditnominal3,0),
							'note'         	=> ''
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kredit3,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => round($kreditnominal3,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
					
					if(isset($kredit4)){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kredit4,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> round($kreditnominal4,0),
							'note'         	=> ''
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kredit4,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => round($kreditnominal4,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
					
					if($purchasedelivery->project->ppn == '1'){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $ppnkeluaran,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> round($ppnnominal,0),
							'note'         	=> ''
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $ppnkeluaran,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => round($ppnnominal,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
				
				if($purchasedelivery->projectSale->mid_yes_no == '1' && $purchasedelivery->is_sales !== '2' && count($purchasedelivery->projectSale->projectDelivery) == 1){
					$debetcb5 = 299;
					$kreditcb5 = 281;
					
					$totalmidfee = round(str_replace(',','.',str_replace('.','',$purchasedelivery->getTotalMiddleman())));
					
					$rowpr = PurchaseRequest::where('link_type','project_sales')->where('link_id',$purchasedelivery->projectSale->id)->get();
					
					if($rowpr){
						foreach($rowpr as $pay){
							$totalmidfee -= $pay->total_nominal;
						}
					}
					
					if($totalmidfee > 0){
						$debetnominal5 = $totalmidfee;
						$query = PurchaseRequest::create([
							'date'	     			=> $request->date,
							'bill_to'				=> $purchasedelivery->project->customer->name,
							'title'					=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code,
							'item'					=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code.' SO NO. '.$purchasedelivery->projectSale->code.' with Customer : '.$purchasedelivery->project->customer->name,
							'user_id'				=> $purchasedelivery->projectSale->user_id,
							'branch'				=> $purchasedelivery->projectSale->sales->branch,
							'total_nominal'			=> $debetnominal5,
							'status'				=> 'APPR',
							'image'					=> null,
							'term'					=> null,
							'supplier_id'			=> 132,
							'term_days'				=> 7,
							'due_date'				=> date('Y-m-d',strtotime($request->date.' + 7 days')),
							'link_type' 			=> 'project_sales',
							'link_id'				=> $purchasedelivery->projectSale->id,
							'approved_by'			=> 7,
							'coa_id'				=> 281
						]);
					
			
						$kreditnominal5 = $debetnominal5;
						
						$cb = CashBank::create([
							'user_id'     		=> session('bo_id'),
							'lookable_type'  	=> 'projects',
							'lookable_id'		=> $purchasedelivery->project_id,
							'code'        		=> 'PR-'.$query->id,
							'date'        		=> $request->date,
							'type'        		=> '2',
							'description' 		=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code.' SO NO. '.$purchasedelivery->projectSale->code.' with Customer : '.$purchasedelivery->project->customer->name
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debetcb5,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '1',
							'nominal'      	=> $debetnominal5,
							'note'         	=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code.' SO NO. '.$purchasedelivery->projectSale->code.' with Customer : '.$purchasedelivery->project->customer->name
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debetcb5,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '1',
							'nominal'          => $debetnominal5,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kreditcb5,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> $kreditnominal5,
							'note'         	=> 'MIDDLEMAN FEE PROJECT '.$purchasedelivery->project->name.' NO. '.$purchasedelivery->project->code.' DO NO. '.$purchasedelivery->code.' SO NO. '.$purchasedelivery->projectSale->code.' with Customer : '.$purchasedelivery->project->customer->name
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kreditcb5,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => $kreditnominal5,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
					}
				}
			}
			
			if($purchasedelivery->project->in_store == '1'){
				$fee_pta = $debetnominal1 * 0.05;
				
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'code'        		=> 'FEE-SMB-'.strtoupper(Str::random(10)),
					'lookable_type'		=> 'projects',
					'lookable_id'		=> $purchasedelivery->project->id,
					'customer_id'		=> 1959,
					'supplier_id'		=> 155,
					'date'        		=> $request->date,
					'type'        		=> '3',
					'description' 		=> 'Fee SMB from DO Code '.$purchasedelivery->code
				]);
				
				if($cb){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 351,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee SMB from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 351,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 332,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee SMB from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 332,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					#balik
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 27,
						'branch'		=> '2',
						'type'       	=> '1',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee SMB from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 27,
						'branch'		   => '2',
						'type'	           => '1',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 209,
						'branch'		=> '2',
						'type'       	=> '2',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee SMB from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 209,
						'branch'		   => '2',
						'type'	           => '2',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
			}
			
			// FEE PTA
			if($purchasedelivery->projectSale->sales->branch == '2'){
				$fee_pta = round($debetnominal1 * 0.05);
				
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'code'        		=> 'FEE-PTA-'.strtoupper(Str::random(10)),
					'lookable_type'		=> 'projects',
					'lookable_id'		=> $purchasedelivery->project->id,
					'customer_id'		=> 9,
					'supplier_id'		=> 132,
					'date'        		=> $request->date,
					'type'        		=> '3',
					'description' 		=> 'Fee PTA from DO Code '.$purchasedelivery->code
				]);
				
				if($cb){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 139,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '1',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee PTA from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 139,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '1',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 332,
						'branch'		=> $purchasedelivery->projectSale->sales->branch,
						'type'       	=> '2',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee PTA from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 332,
						'branch'		   => $purchasedelivery->projectSale->sales->branch,
						'type'	           => '2',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					#balik
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 27,
						'branch'		=> '1',
						'type'       	=> '1',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee PTA from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 27,
						'branch'		   => '1',
						'type'	           => '1',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 209,
						'branch'		=> '1',
						'type'       	=> '2',
						'nominal'      	=> $fee_pta,
						'note'         	=> 'Fee PTA from DO Code '.$purchasedelivery->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 209,
						'branch'		   => '1',
						'type'	           => '2',
						'nominal'          => $fee_pta,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
				
				if(isset($debit6)){
					
					$pr = PurchaseRequest::create([
						'date'	     			=> $request->date,
						'bill_to'				=> 'Bu Shanti - PTA',
						'title'					=> 'Sales Commission Project '.$purchasedelivery->project->code.' DO. '.$purchasedelivery->code,
						'item'					=> 'Sales Commission Project '.$purchasedelivery->project->code.' DO. '.$purchasedelivery->code.' Cust. '.$purchasedelivery->project->customer->name,
						'user_id'				=> 7,
						'coa_id'				=> 281,
						'branch'				=> $purchasedelivery->projectSale->sales->branch,
						'total_nominal'			=> $debitnominal6,
						'total_cash_advance'	=> 0,
						'status'				=> 'APPR',
						'term'					=> '0',
						'supplier_id'			=> 136,
						'term_days'				=> 0,
						'due_date'				=> $request->date,
						'link_type' 			=> 'project_deliveries',
						'link_id'				=> $purchasedelivery->id
					]);
					
					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'code'        		=> 'PR-'.$pr->id,
						'lookable_type'		=> 'projects',
						'lookable_id'		=> $purchasedelivery->project->id,
						'date'        		=> $request->date,
						'type'        		=> '3',
						'description' 		=> 'Sales Commission from DO Code '.$purchasedelivery->code
					]);
					
					if($cb){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debit6,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '1',
							'nominal'      	=> $debitnominal6,
							'note'         	=> 'Sales Commission from DO Code '.$purchasedelivery->code
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debit6,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '1',
							'nominal'          => $debitnominal6,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kredit6,
							'branch'		=> $purchasedelivery->projectSale->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> $kreditnominal6,
							'note'         	=> 'Sales Commission from DO Code '.$purchasedelivery->code
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kredit6,
							'branch'		   => $purchasedelivery->projectSale->sales->branch,
							'type'	           => '2',
							'nominal'          => $kreditnominal6,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
			}
			
			#END
			
			#updatestock
			$delivery = ProjectDelivery::find($request->id);
			
			foreach($delivery->projectDeliveryProduct as $row){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$delivery->warehouse_id)->where('branch',$delivery->projectSale->sales->branch)->first();
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty - $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $delivery->warehouse_id,
						'qty'			=> $row->qty * -1,
						'unit'			=> $row->unit,
						'branch'		=> $delivery->projectSale->sales->branch
					]);
				}
			}
			
			$ceklunas = Project::find($purchasedelivery->project->id)->getBalance();
							
			if($ceklunas <= 0){
				Project::find($purchasedelivery->project->id)->update([
					'progress' => 85
				]);
				
				#send approval
				$roleapproval = array('1');
				Approval::sendApproval($roleapproval,'projects',$purchasedelivery->project->id,'',session('bo_id'));
				#end approval
				
				SendMessage::send(env('OWNER_PHONE'),'Halo pak/bu. Mohon dibantu approve Penutupan Project No. '.$purchasedelivery->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			}
			
			#update cogs
							
			ProductCogs::updateCogs($request->date,$purchasedelivery->projectSale->sales->branch);
			
			activity()
				->performedOn(new ProjectDelivery())
				->causedBy(session('bo_id'))
				->withProperties($purchasedelivery)
				->log('Cancel delivery received date.');
			
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
			
		}else{
			return response()->json([
				'status'  => 503,
				'message' => 'You cannot add/edit. The journal for this month was already closed.'
			]);
		}
	}
	
	public function addProjectNote(Request $request){
		
		ProjectNote::create([
			'user_id'				=> session('bo_id'),
			'notable_type'			=> $request->mode,
			'notable_id'			=> $request->id,
			'note'         			=> $request->note,
			'image'					=> $request->file('file') ? $request->file('file')->store('public/project') : ''
		]);
		
		$pn = ProjectNote::where('notable_type',$request->mode)->where('notable_id',$request->id)->get();
		
		$data = [];
		
		foreach($pn as $row){
			$data[] = [
				'id'					=> $row->id,
				'user'					=> $row->user->name,
				'code'					=> $row->notable->code,
				'note'					=> $row->note ? $row->note : '',
				'image'					=> $row->image() ? '<a href="' . $row->image() . '" target="_blank" class="btn btn-info"><i class="icon-search4"></i></a>' : '<span class="badge badge-secondary">None</span>',
				'created_at'			=> $row->created_at,
				'is_public'				=> $row->is_public
			];
		}
		
		activity()
			->performedOn(new ProjectNote())
			->causedBy(session('bo_id'))
			->withProperties($pn)
			->log('Add note to project purchase');
		
		return response()->json($data);
	}


	public function createProjectTrip(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'date_trip'             => 'required',
            'note_trip'             => 'required',
        ], [
            'date_trip.required'    => 'Please select a date.',
            'note_trip.array'       => 'Note cannot empty.',

        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->temp_id){
                $fieldtrip = FieldTrip::find($request->temp_id);

                if ($request->hasFile('proof')) {
                    if (Storage::exists($fieldtrip->proof)) {
                        Storage::delete($fieldtrip->proof);
                    }

                    $proof = $request->file('proof')->store('public/field_trip');
                } else {
                    $proof =  $fieldtrip->proof;
                }

                $query = FieldTrip::where('id', $request->temp_id)->update([
                    'user_id'       => session('bo_id'),
                    'project_id'    => $request->project_id,
                    'date'          => date("Y-m-d", strtotime($request->date_trip)),
                    'proof'         => $proof,
                    'note'          => $request->note_trip,
                ]);
            }else{
                $query = FieldTrip::create([
                    'user_id'       => session('bo_id'),
                    'project_id'    => $request->project_id,
                    'date'          => date("Y-m-d", strtotime($request->date_trip)),
                    'proof'         => $request->file('proof') ?  $request->file('proof')->store('public/field_trip') : $request->file('proof'),
                    'note'          => $request->note_trip,
                ]);

            }

            if ($query) {
                $response = [
                    'status'  => 200,
                    'message' => 'Data created successfully'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data failed to create'
                ];
            }
        }

        activity()
            ->performedOn(new FieldTrip())
            ->causedBy(session('bo_id'))
            ->log('Add field trip by ' . session('bo_name'));

        return response()->json($response);
    }

	
	public function showProjectTrip(Request $request)
    {
        $result = FieldTrip::where('id', $request->id)->first();

		$response = [
			'status'  => 200,
			'message' => 'Success',
			'note'    => $result->note,
			'date'    => $result->date,
		];

        return response()->json($response);
    }

	public function getProjectTrip()
	{		
		$data = FieldTrip::all();

		$result = [];

		foreach($data as $row){
			$result[] = [
				'id'			=> $row->id,
				'note'			=> $row->note,
				'date'			=> $row->date,
				'day'			=> date('d', strtotime($row->date)),
				'name'			=> $row->project->code.'-'.$row->project->customer->name,
				'proof'			=> $row->attachment(),
				'progress'	    => $row->project->progress,
			];
		}

		$response = [
			'status' => 200,
			'data'   => $result
		];

		return response()->json($response);
	}

	public function datatableProjectTrip(Request $request)
    {
        $column = [
            'id',
            'user_id',
            'date',
            'note',
            'proof',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = FieldTrip::where('project_id', $request->project_id)->count();

        $query_data = FieldTrip::where('project_id', $request->project_id)
		->where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = FieldTrip::where('project_id', $request->project_id)
		->where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
        

            foreach ($query_data as $val) {
                $proof = '<td class="text-center"><a href="'.$val->attachment().'" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>';

                $btnAction = '<button class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="showProjectTrip(' . $val->id . ')"><i class="icon-pencil5"></i></button>';

                $response['data'][] = [
                    $val->date,
					$val->note,
					$proof,
                    $btnAction,
                ];
            }
        }

        $response['recordsTotal'] = 0;
        if ($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if ($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

	
	public function deleteDeliveryTracking(Request $request)
	{
		$trackingdelivery = ProjectDeliveryTrack::find($request->id);
		
		if(Storage::exists($trackingdelivery->image)) {
			Storage::delete($trackingdelivery->image);
        } 
		
		$trackingdelivery->delete();
		
		activity()
			->performedOn(new ProjectDeliveryTrack())
			->causedBy(session('bo_id'))
			->withProperties($trackingdelivery)
			->log('Delete tracking to delivery project');
			
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function trackingShipment(Request $request)
	{
		
		$shipment = ProjectShipment::where('id',$request->id)
									->where('shipment_code',$request->code)
									->first();
		
		if(!$shipment) {
			abort(404);
		}
		
		$data = [
			'title'   	=> 'Detail Tracking '.$request->code,
			'track' 	=> $shipment->projectShipmentTrack,
			'finish'	=> $shipment->projectShipmentWarehouse
		];
		
		return view('admin.tracking.shipment', $data);
	}
	
	public function trackingDelivery(Request $request)
	{
		
		$delivery = ProjectDelivery::where('id',$request->id)
									->where('code',str_replace('-','/',$request->code))
									->first();
		
		if(!$delivery) {
			abort(404);
		}
		
		$data = [
			'title'   	=> 'Detail Tracking '.str_replace('-','/',$request->code),
			'track' 	=> $delivery->projectDeliveryTrack
		];
		
		return view('admin.tracking.delivery', $data);
	}
	
	public function emailTrackingShipment(Request $request){
		$idshipment = $request->idshipment;
		
		$shipment = ProjectShipment::find($idshipment);
		
		$payload = [
			'email'   	=> $delivery->project->customer->email,
			'name'    	=> $shipment->projectPurchase->projectSale->project->customer->name,
			'shipment'  => $shipment,
			'link'    	=> url('/project/tracking/shipment/'.$idshipment.'/'.$shipment->shipment_code),
			'view'    	=> 'project_tracking',
			'subject' 	=> 'SMB | Tracking ' . $shipment->shipment_code,
			'mode'		=> 'shipment'
		];
		
		/* Mail::send('emails.' . $payload['view'], $payload, function($mail) use ($payload) {
            $mail->to($payload['email'], $payload['name']);
            $mail->subject($payload['subject']);
            $mail->from(config('mail.mailers.smtp.username'), 'Smart Marble And Bath');
        }); */
		
		dispatch(new EmailProcess($payload));
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function emailTrackingDelivery(Request $request){
		$iddelivery = $request->iddelivery;
		
		$delivery = ProjectDelivery::find($iddelivery);
		
		$payload = [
			'email'   	=> $delivery->project->customer->email,
			'name'    	=> $delivery->project->customer->name,
			'shipment'  => $delivery,
			'link'    	=> url('/project/tracking/delivery/'.$iddelivery.'/'.str_replace('/','-',$delivery->code)),
			'view'    	=> 'project_tracking',
			'subject' 	=> 'SMB | Tracking ' . $delivery->code,
			'mode'		=> 'delivery'
		];
		
		/* Mail::send('emails.' . $payload['view'], $payload, function($mail) use ($payload) {
            $mail->to($payload['email'], $payload['name']);
            $mail->subject($payload['subject']);
            $mail->from(config('mail.mailers.smtp.username'), 'Smart Marble And Bath');
        }); */
		
		dispatch(new EmailProcess($payload));
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
	
	public function getShadingVentura(Request $request)
	{
		/* $kode_item = $request->kode_item; */
		$gudang = $request->gudang;
		$perpage = $request->per_page;
		
		$stock = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
			'gudang'    => $gudang,
			'per_page'	=> $perpage
		]));
		
		$data = ProductShading::where('warehouse_code',$gudang)->get();
		
		if($stock->result->total_data > 0) {
			foreach($data as $row){
				$stok = 0;
				foreach($stock->result->data as $s) {
					if(trim($s->kode_item) == $row->stock_code){
						$stok += $s->stok;
					}
				}
				ProductShading::where('warehouse_code',$gudang)->where('stock_code',$row->stock_code)->update(['qty' => $stok]);
			}
		}
	}
	
	public function rowDetailPurchase(Request $request)
    {
        $data   = Project::find($request->id);
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Code</th>
							<th>SO No.</th>
							<th>Supplier</th>
							<th width="15%">Total</th>
							<th width="15%">Paid</th>
							<th width="15%">Balance</th>
						</tr>
					</thead>
					<tbody>';
		if(count($data->projectPurchase) > 0){
			foreach($data->projectPurchase as $pp) {
				$balance = floatval(str_replace(',','.',str_replace('.','',$pp->getTotal()))) - floatval(str_replace(',','.',str_replace('.','',$pp->getPaid())));
				
				$string .= '
					<tr>
						<td class="text-center">'.$pp->code.'</td>
						<td class="text-center">'.$pp->projectSale->code.'</td>
						<td class="text-center">'.$pp->supplier->name.'</td>
						<td class="text-right">'.$pp->currency->symbol.' '.$pp->getTotal().'</td>
						<td class="text-right">'.$pp->currency->symbol.' '.$pp->getPaid().'</td>
						<td class="text-right">'.$pp->currency->symbol.' '.number_format($balance,0,",",".").'</td>
					</tr>
				';
				
				if(count($pp->project->projectPayment) > 0){
					$string .= '
						<tr>
							<th colspan="6" class="text-center bg-info">Detail Purchase Payments</th>
						</tr>
						<tr class="text-center">
							<th colspan="2"></th>
							<th>Date</th>
							<th>Bank</th>
							<th>Nominal</th>
							<th></th>
						</tr>
					';
				}
				
				foreach($pp->project->projectPayment as $ppp){
					$string .= '
						<tr>
							<td colspan="2"></td>
							<td>'.$ppp->date.'</td>
							<td>'.$ppp->coa->name.'</td>
							<td class="text-right">'.$ppp->projectPurchase->currency->symbol.' '.number_format($ppp->nominal,0,',','.').'</td>
							<td></td>
						</tr>
					';
				}
			}
		}else{
			$string .= '
				<tr>
					<td colspan="6"><div class="alert bg-warning text-white alert-styled-left alert-dismissible">There is no PO data.</div></td>
				</tr>
			';
		}

        $string .= '</tbody></table>';
		
        return response()->json($string);
    }
	
	public function rowDetailSales(Request $request)
    {
        $data   = Project::find($request->id);
        $string = '<h3 class="text-center bg-primary">All Sales</h3><table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>SO No.</th>
							<th>Sales</th>
							<th>Date</th>
							<th>Total</th>
							<th>Paid</th>
							<th>Balance</th>
						</tr>
					</thead>
					<tbody>';
		if(count($data->projectSale) > 0){
			foreach($data->projectSale as $pp) {
				$balance = floatval(str_replace(',','.',str_replace('.','',$pp->getTotal()))) - floatval(str_replace(',','.',str_replace('.','',$pp->getPaid())));
				
				$string .= '
					<tr>
						<td class="text-center">'.$pp->code.'</td>
						<td class="text-center">'.$pp->sales->name.'</td>
						<td class="text-center">'.date('d M Y',strtotime($pp->created_at)).'</td>
						<td class="text-right">'.$pp->getTotal().'</td>
						<td class="text-right">'.$pp->getPaid().'</td>
						<td class="text-right">'.number_format($balance,2,",",".").'</td>
					</tr>
				';
				
				if(count($pp->projectSalePay) > 0){
					$string .= '
						<tr>
							<th colspan="6" class="text-center bg-info">Detail Sales Payments</th>
						</tr>
						<tr class="text-center">
							<th></th>
							<th>Code</th>
							<th>Date</th>
							<th>Note</th>
							<th>Nominal</th>
							<th></th>
						</tr>
					';
				}
				
				foreach($pp->projectSalePay as $ppp){
					$string .= '
						<tr>
							<td></td>
							<td class="text-center">'.$ppp->code.'</td>
							<td class="text-center">'.$ppp->date.'</td>
							<td class="text-center">'.$ppp->note.'</td>
							<td class="text-right">'.number_format($ppp->nominal,2,',','.').'</td>
							<td></td>
						</tr>
					';
				}
				
				if(count($pp->projectSaleProduct) > 0){
					$string .= '
						<tr>
							<th colspan="6" class="text-center bg-info">Detail Sales Products</th>
						</tr>
						<tr class="text-center">
							<th></th>
							<th>Code</th>
							<th>Brand</th>
							<th>Size</th>
							<th>Qty</th>
							<th></th>
						</tr>
					';
				}
				
				foreach($pp->projectSaleProduct as $ppp){
					if($ppp->unit == '2' || $ppp->unit == '3'){
						$m2 = (( $ppp->product->type->length * $ppp->product->type->width ) / 10000) * $ppp->product->carton_pcs;
						if($m2 < 1.1 && $ppp->product->type->category->parent()->id !== 18){
							$countbox = $ppp->qty;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($pp->project->created_at)) < '2022-06' && $ppp->product->type->category->parent()->id == 18){
								$countbox = $ppp->qty;
							}else{
								$countbox = ceil(round($ppp->qty / $m2,2));
							}
						}
						
						$string .= '
							<tr>
								<td></td>
								<td class="text-center">'.$ppp->product->type->code.'</td>
								<td class="text-center">'.$ppp->product->brand->name.'</td>
								<td class="text-center">'.$ppp->product->type->length.'x'.$ppp->product->type->width.'</td>
								<td class="text-center">'.$countbox.'</td>
								<td></td>
							</tr>
						';
					}else{
						$countbox = $ppp->qty;
						
						$string .= '
							<tr>
								<td></td>
								<td class="text-center">'.$ppp->product->type->code.'</td>
								<td class="text-center">'.$ppp->product->brand->name.'</td>
								<td class="text-center">'.$ppp->product->type->length.'x'.$ppp->product->type->width.'</td>
								<td class="text-center">'.$countbox.'</td>
								<td></td>
							</tr>
						';
					}
				}
			}
		}else{
			$string .= '
				<tr>
					<td colspan="6"><div class="alert bg-warning text-white alert-styled-left alert-dismissible">There is no SO data.</div></td>
				</tr>
			';
		}
		
		$string .= '</tbody></table>';
		
		if(count($data->projectDelivery) > 0){
			$string .= '<h3 class="text-center mt-3 bg-primary">All Delivery</h3><table class="mt-3 table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>SO No.</th>
							<th>DO No.</th>
							<th>Warehouse</th>
							<th>Receiver</th>
							<th>Dropship</th>
						</tr>
					</thead>
					<tbody>';
			
			foreach($data->projectDelivery as $psi) {
				$string .= '
					<tr>
						<td class="text-center">'.$psi->projectSale->code.'</td>
						<td class="text-center">'.$psi->code.'</td>
						<td class="text-center">'.$psi->warehouse->name.' - '.$psi->warehouse->code.'</td>
						<td class="text-right">'.$psi->receiver_name.'</td>
						<td class="text-center">'.$psi->isDropshipper().'</td>
					</tr>
				';
				
				if(count($psi->projectDeliveryProduct) > 0){
					$string .= '
						<tr>
							<th colspan="5" class="text-center bg-info">Detail Delivery Products</th>
						</tr>
						<tr class="text-center">
							<th colspan="2">Name</th>
							<th>Finishing</th>
							<th>Spec</th>
							<th>Qty</th>
						</tr>
					';
				}
				
				foreach($psi->projectDeliveryProduct as $ps){
					$string .= '
						<tr class="text-center">
							<td colspan="2">'.$ps->product->name().'</td>
							<td>'.$ps->product->type->surface->name.'</td>
							<td>'.$ps->product->type->length.'x'.$ps->product->type->width.'</td>
							<td>'.$ps->qty.' '.$ps->unit().'</td>
						</tr>
					';
				}
			}
			
			$string .= '</tbody></table>';
		}

        return response()->json($string);
    }
	
	public function deletePurchase(Request $request)
	{
		$pp = ProjectPurchase::find($request->idpo);
		$reason = $request->reason;
		
		//if(count($pp->projectWarehouse) == 0){
		
			ProjectPurchaseProduct::where('project_purchase_id',$request->idpo)->delete();
			
			Approval::where('approvalable_type','project_purchases')->where('approvalable_id',$request->idpo)->delete();
			
			$this->addProjectLog(session('bo_id'),$pp->project->id,'project_purchases',$pp->id,0,'delete',$reason);
			
			$note = ProjectNote::where('notable_type','project_purchases')->where('notable_id',$pp->id)->get();
			
			foreach($note as $n){
				$n->deleteFile();
				$n->delete();
			}
			
			$purchaserequest = PurchaseRequest::where('link_type','project_purchases')->where('link_id',$pp->id)->get();
			
			foreach($purchaserequest as $row){
				$row->deleteFile();
				$row->delete();
			}
			
			foreach($pp->projectPurchaseReturn as $purchasereturn){
				$cb = CashBank::where('lookable_type','project_purchase_returns')->where('lookable_id',$purchasereturn->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$purchasereturn->projectPurchaseReturnProduct()->delete();
				$purchasereturn->deleteFile();
				$purchasereturn->delete();
			}
			
			$pc = PurchaseCost::where('project_purchase_id',$pp->id)->first();
			
			if($pc){
				$pc->purchaseCostDetail()->delete();
				$pc->delete();
			}
			
			foreach($pp->projectProforma as $purchaseproforma){
				$purchaseproforma->deleteFile();
				$purchaseproforma->delete();
			}
			
			foreach($pp->projectPurchaseBill as $purchasebill){
				$purchasebill->deleteFile();
				$purchasebill->delete();
			}
			
			foreach($pp->projectPurchasePayment as $purchasePayment){
				$purchasePayment->deleteFile();
				$cb = CashBank::where('lookable_type','project_payments')->where('lookable_id',$purchasePayment->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$purchasePayment->delete();
			}
			
			foreach($pp->projectPurchaseProduction as $production){
				$production->deleteFile();
				$production->delete();
			}
			
			foreach($pp->projectShipment as $shipment){
				$shipment->projectShipmentProduct()->delete();
				$shipment->projectShipmentTrack()->delete();
				$shipment->deleteFile();
				$shipment->delete();
			}
			
			foreach($pp->projectWarehouse as $warehouse){
				$cb = CashBank::where('lookable_type','project_warehouses')->where('lookable_id',$warehouse->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$warehouse->deleteFile();
				$warehouse->projectWarehouseProduct()->delete();
				$warehouse->delete();
			}
			
			$pp->delete();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project purchase order ' . $pp->code . ' has been deleted!';
			$description = 'Project purchase order '.$pp->code.' has been deleted by ' .session("bo_name"). ' with reason : '.$reason;
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new ProjectPurchase())
				->causedBy(session('bo_id'))
				->withProperties($pp)
				->log('Delete project purchase');
				
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
			
			
		/* }else{
			return response()->json([
				'status'  => 400,
				'message' => 'PO already has Warehouse Receive, please contact developer.'
			]);
		} */
	}
	
	public function deletePurchaseReturn(Request $request)
	{
		$pp = ProjectPurchaseReturn::find($request->id);
		$reason = $request->reason;
		
		if($pp){
			if($pp->project_warehouse_id){
				$datapr = PurchaseRequest::where('link_type','project_purchases')->where('link_id',$pp->projectPurchase->id)->where('project_warehouse_id',$pp->project_warehouse_id)->get();
			
				if($datapr){
					foreach($datapr as $rowpr){
						PurchaseRequest::find($rowpr->id)->update([
							'total_nominal' => $rowpr->total_nominal + $pp->getTotal()
						]);
					}
				}
			}else{
				$datapr = PurchaseRequest::where('link_type','project_purchases')->where('link_id',$pp->projectPurchase->id)->whereNotNull('project_purchase_bill_id')->get();
			
				if($datapr){
					foreach($datapr as $rowpr){
						PurchaseRequest::find($rowpr->id)->update([
							'total_nominal' => $rowpr->total_nominal + $pp->getTotal()
						]);
					}
				}
			}
			
			$cb = CashBank::where('lookable_type','project_purchase_returns')->where('lookable_id',$pp->id)->get();
			
			foreach($cb as $c){
				$c->deleteDetail();
				$c->delete();
			}
			
			foreach($pp->projectPurchaseReturnProduct as $row){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$pp->warehouse_id)->where('branch',$pp->projectPurchase->sales->branch)->first();
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty + $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $pp->warehouse_id,
						'qty'			=> $row->qty,
						'unit'			=> $row->unit,
						'branch'		=> $pp->projectPurchase->sales->branch
					]);
				}
			}
			
			$date = $pp->date;
			$branch = $pp->projectPurchase->sales->branch;
			
			$pp->projectPurchaseReturnProduct()->delete();
			$pp->deleteFile();
			$pp->delete();
			
			ProductCogs::updateCogs($date,$branch);
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project purchase return no ' . $pp->code . ' has been deleted!';
			$description = 'Project purchase order '.$pp->code.' has been deleted by ' .session("bo_name"). ' with reason : '.$reason;
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new ProjectPurchase())
				->causedBy(session('bo_id'))
				->withProperties($pp)
				->log('Delete project purchase return');
				
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
		}
	}

	public function deleteSample(Request $request){
		$query = ProjectSample::find($request->id);

		Approval::where('approvalable_type','project_samples')->where('approvalable_id',$query->id)->delete();
		$query->deleteFile();
		$query->deleteDetail();
		$query->delete();


		return response()->json([
			'status' => 200,
			'message' => 'Successfully deleted'
		]);
	}

	public function deleteSales(Request $request)
	{
		$pp = ProjectSale::find($request->idso);
		$reason = $request->reason;
		
		if(count($pp->projectPurchase) == 0){
			$pp->projectSaleProduct()->delete();
		
			$pp->projectSaleShading()->delete();
			
			$pp->deleteFile();
			
			$pp->delete();
			
			Approval::where('approvalable_type','project_sales')->where('approvalable_id',$request->idso)->delete();
			
			$this->addProjectLog(session('bo_id'),$pp->project->id,'project_sales',$pp->id,0,'delete',$reason);
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project sales ' . $pp->code . ' has been deleted!';
			$description = 'Project sales '.$pp->code.' has been deleted by ' .session("bo_name"). ' with reason : '.$reason;
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new ProjectSale())
				->causedBy(session('bo_id'))
				->withProperties($pp)
				->log('Delete project sales');
				
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
			
		}else{
			return response()->json([
				'status'  => 400,
				'message' => 'SO already has PO, please contact developer.'
			]);
		}
		
	}
	
	public function deleteProject(Request $request)
	{
		$project = Project::find($request->id);
		$reason = $request->reason;
		
		if($project->progress <= 40){
			$project->projectProduct()->delete();
			
			$cb = CashBank::where('lookable_type','projects')->where('lookable_id',$project->id)->get();
			foreach($cb as $c){
				$c->deleteDetail();
				$c->delete();
			}
			
			$project->projectConsultantMeeting()->delete();
			
			foreach($project->projectQuotation as $quotation){
				$quotation->projectQuotationProduct()->delete();
				$quotation->delete();
			}
			
			foreach($project->projectSample as $sample){
				$sample->projectSampleProduct()->delete();
				$sample->delete();
			}
			
			foreach($project->projectPicture as $picture){
				$picture->deleteFile();
				$picture->delete();
			}
			
			foreach($project->projectNegotiation as $negotiation){
				$negotiation->delete();
			}
			
			foreach($project->projectSale as $sale){
				$sale->deleteFile();
				
				$sale->projectSaleProduct()->delete();
				$sale->projectSaleShading()->delete();
				$pr = PurchaseRequest::where('link_id',$sale->id)->where('link_type','projet_sales')->get();
				
				if($pr){
					foreach($pr as $row){
						$row->deleteFile();
						$row->delete();
					}
				}
				
				$pst = ProjectSaleTemp::find($sale->id);
				if($pst){
					$pst->projectSaleProductTemp()->delete();
					$pst->projectSaleShadingTemp()->delete();
					$pst->delete();
				}
				
				$sale->delete();
			}
			
			foreach($project->projectFromStock as $pfs){
				$pfs->projectFromStockProduct()->delete();
				$pfs->delete();
			}
			
			foreach($project->projectPay as $pay){
				$pay->deleteFile();
				$cb = CashBank::where('lookable_type','project_pays')->where('lookable_id',$pay->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$pay->delete();
			}
			
			foreach($project->projectBill as $pb){
				$cb = CashBank::where('lookable_type','project_bills')->where('lookable_id',$pb->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$pb->delete();
			}
			
			foreach($project->projectPurchase as $purchase){
				$purchase->projectPurchaseProduct()->delete();
				$purchase->projectPurchaseSplit()->delete();
				$purchase->projectPurchaseBill()->delete();
				$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$purchase->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				
				$note = ProjectNote::where('notable_type','project_purchases')->where('notable_id',$purchase->id)->get();
				
				foreach($note as $n){
					$n->deleteFile();
					$n->delete();
				}
				
				$pr = PurchaseRequest::where('link_id',$purchase->id)->where('link_type','project_purchases')->get();
				
				if($pr){
					foreach($pr as $rowpr){
						$rowpr->deleteFile();
						$rowpr->delete();
					}
				}
				
				$pc = PurchaseCost::where('project_purchase_id',$purchase->id)->first();
			
				if($pc){
					$pc->purchaseCostDetail()->delete();
					$pc->delete();
				}
				
				$purchase->delete();
			}
			
			foreach($project->projectPurchaseReturn as $purchasereturn){
				$cb = CashBank::where('lookable_type','project_purchase_returns')->where('lookable_id',$purchasereturn->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$purchasereturn->projectPurchaseReturnProduct()->delete();
				$purchasereturn->deleteFile();
				$purchasereturn->delete();
			}
			
			foreach($project->projectProforma as $purchaseproforma){
				$purchaseproforma->deleteFile();
				$purchaseproforma->delete();
			}
			
			foreach($project->projectPayment as $purchasePayment){
				$purchasePayment->deleteFile();
				$cb = CashBank::where('lookable_type','project_payments')->where('lookable_id',$purchasePayment->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$purchasePayment->delete();
			}
			
			foreach($project->projectProduction as $production){
				$production->deleteFile();
				$production->delete();
			}
			
			foreach($project->projectShipment as $shipment){
				$shipment->projectShipmentProduct()->delete();
				$shipment->projectShipmentTrack()->delete();
				$shipment->deleteFile();
				$shipment->delete();
			}
			
			foreach($project->projectWarehouse as $warehouse){
				$cb = CashBank::where('lookable_type','project_warehouses')->where('lookable_id',$warehouse->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$warehouse->deleteFile();
				$warehouse->projectWarehouseProduct()->delete();
				$warehouse->delete();
			}
			
			foreach($project->projectDelivery as $delivery){
				$cb = CashBank::where('lookable_type','project_deliveries')->where('lookable_id',$delivery->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				$delivery->deleteFile();
				$delivery->projectDeliveryProduct()->delete();
				$delivery->projectDeliveryTrack()->delete();
				$delivery->delete();
			}
			
			foreach($project->projectSaleReturn as $salesreturn){
				$salesreturn->deleteFile();
				$salesreturn->projectSaleReturnProduct()->delete();
				
				$cb = CashBank::where('lookable_type','project_sale_returns')->where('lookable_id',$salesreturn->id)->get();
				foreach($cb as $c){
					$c->deleteDetail();
					$c->delete();
				}
				
				$salesreturn->delete();
			}
			
			foreach($project->projectTroubleshooting as $trouble){
				$trouble->deleteFile();
				$trouble->delete();
			}
			
			foreach($project->projectLog as $log){
				$log->delete();
			}
			
			$note = ProjectNote::where('notable_type','projects')->where('notable_id',$project->id)->get();
				
			foreach($note as $n){
				$n->deleteFile();
				$n->delete();
			}
			
			$project->delete();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project ' . $project->code . ' has been canceled!';
			$description = 'Project '.$project->code.' has been canceled by ' .session("bo_name"). ' with reason : '.$reason;
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new Project())
				->causedBy(session('bo_id'))
				->withProperties($project)
				->log('Delete project code '.$project->code);
				
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
		}else{
			return response()->json([
				'status'  => 400,
				'message' => 'This project already has PO, please contact developer.'
			]);
		}
	}
	
	public function rowDetailInvoice(Request $request)
    {
        $data   = Project::find($request->id);
        $string = '<h3 class="text-center bg-primary">All Sales</h3><table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>SO No.</th>
							<th>Sales</th>
							<th>Date</th>
							<th>Total</th>
							<th>Paid</th>
							<th>Balance</th>
						</tr>
					</thead>
					<tbody>';
		if(count($data->projectSale) > 0){
			foreach($data->projectSale as $pp) {
				$balance = floatval(str_replace(',','.',str_replace('.','',$pp->getTotal()))) - floatval(str_replace(',','.',str_replace('.','',$pp->getPaid())));
				
				$string .= '
					<tr>
						<td class="text-center">'.$pp->code.'</td>
						<td class="text-center">'.$pp->sales->name.'</td>
						<td class="text-center">'.date('Y-m-d',strtotime($pp->created_at)).'</td>
						<td class="text-right">'.$pp->getTotal().'</td>
						<td class="text-right">'.$pp->getPaid().'</td>
						<td class="text-right">'.number_format($balance,2,",",".").'</td>
					</tr>
				';
				
				if(count($pp->projectSalePay) > 0){
					$string .= '
						<tr>
							<th colspan="6" class="text-center bg-info">Detail Sales Payments</th>
						</tr>
						<tr class="text-center">
							<th>Code</th>
							<th>Proforma</th>
							<th>Date</th>
							<th>Note</th>
							<th>Nominal</th>
							<th></th>
						</tr>
					';
				}
				
				foreach($pp->projectSalePay as $ppp){
					if(isset($ppp->projectDelivery->proforma_code)){
						$proforma = $ppp->projectDelivery->proforma_code;
					}else{
						$proforma = '-';
					}
					$string .= '
						<tr>
							<td class="text-center">'.$ppp->code.'</td>
							<td class="text-center">'.$proforma.'</td>
							<td class="text-center">'.$ppp->date.'</td>
							<td class="text-center">'.$ppp->note.'</td>
							<td class="text-right">'.number_format($ppp->nominal,2,',','.').'</td>
							<td></td>
						</tr>
					';
				}
			}
		}else{
			$string .= '
				<tr>
					<td colspan="6"><div class="alert bg-warning text-white alert-styled-left alert-dismissible">There is no SO data.</div></td>
				</tr>
			';
		}
		
		$string .= '</tbody></table>';

        return response()->json($string);
    }
	
	public function rowDetailDelivery(Request $request)
    {
        $data   = Project::find($request->id);
		
		$string = '<h3 class="text-center mt-3 bg-primary">All Delivery</h3><table class="mt-3 table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>SO No.</th>
							<th>DO No.</th>
							<th>Warehouse</th>
							<th>Receiver</th>
							<th>Dropship</th>
							<th>Received</th>
						</tr>
					</thead>
					<tbody>';
		
		if(count($data->projectDelivery) > 0){
			foreach($data->projectDelivery as $psi) {
				$string .= '
					<tr>
						<td class="text-center">'.$psi->projectSale->code.'</td>
						<td class="text-center">'.$psi->code.'</td>
						<td class="text-center">'.$psi->warehouse->name.' - '.$psi->warehouse->code.'</td>
						<td class="text-right">'.$psi->receiver_name.'</td>
						<td class="text-center">'.$psi->isDropshipper().'</td>
						<td class="text-center">'.($psi->received_date ? date("d M Y",strtotime($psi->received_date)) : 'Not yet').'</td>
					</tr>
				';
				
				$string .= '
					<tr>
						<th colspan="6" class="text-center bg-info">Proforma Payment</th>
					</tr>
					<tr class="text-center">
						<th colspan="3">Code</th>
						<th>Date</th>
						<th>Note</th>
						<th>Nominal</th>
					</tr>
				';
				
				if(count($psi->projectPay) > 0){
					foreach($psi->projectPay as $pp){
						$string .= '
							<tr class="text-center">
								<td colspan="3">'.$pp->code.'</td>
								<td>'.$pp->date.'</td>
								<td>'.$pp->note.'</td>
								<td>'.number_format($pp->nominal,2,',','.').'</td>
							</tr>
						';
					}
				}else{
					$string .= '
						<tr class="text-center bg-danger">
							<th colspan="6">No payment proforma</th>
						</tr>
					';
				}
				
				if(count($psi->projectDeliveryProduct) > 0){
					$string .= '
						<tr>
							<th colspan="6" class="text-center bg-info">Detail Delivery Products</th>
						</tr>
						<tr class="text-center">
							<th colspan="3">Name</th>
							<th>Finishing</th>
							<th>Spec</th>
							<th>Qty</th>
						</tr>
					';
				}
				
				foreach($psi->projectDeliveryProduct as $ps){
					$string .= '
						<tr class="text-center">
							<td colspan="3">'.$ps->product->name().'</td>
							<td>'.$ps->product->type->surface->name.'</td>
							<td>'.$ps->product->type->length.'x'.$ps->product->type->width.'</td>
							<td>'.$ps->qty.' '.$ps->unit().'</td>
						</tr>
					';
				}
			}
			
			$string .= '</tbody></table>';
		}else{
			$string .= '
				<tr class="text-center bg-danger">
					<th colspan="5">No delivery found on this project</th>
				</tr>
			';
		}

        return response()->json($string);
    }
	
	public function addReturnPurchase(Request $request){
		
		$purchase = ProjectPurchase::find($request->id);
		$isExist = false;
		
		if(CheckCutOff::check($purchase->sales->branch,substr($request->date,0,7))){
			
			foreach($request->arrProduct as $key => $ar){
				$countQty = Stock::where('product_id', $ar)->where('warehouse_id', $request->warehouse)->sum('qty');
				if($countQty > 0){
					$isExist = true;
				}
			}
	
			if($isExist){
				if(count($purchase->projectWarehouse) > 0){
			
					$image = '';
					
					if($request->has('file')) {
						$image = $request->file('file')->store('public/project');
					}
					
					$return = ProjectPurchaseReturn::create([
						'user_id'				=> session('bo_id'),
						'project_id'			=> $purchase->project ? $purchase->project->id : 0,
						'project_purchase_id'	=> $request->id,
						'project_warehouse_id'	=> $request->project_warehouse_id,
						'code'         			=> ProjectPurchaseReturn::generateCode(),
						'date'					=> $request->date,
						'warehouse_id'			=> $request->warehouse,
						'image'					=> $image,
						'note'					=> $request->note,
						'approved_by'			=> 0
					]);
					
					if($request->arrProduct){
						foreach($request->arrProduct as $key => $ar){
							ProjectPurchaseReturnProduct::create([
								'project_purchase_return_id'	=> $return->id,
								'product_id'					=> $ar,
								'qty'							=> $request->arrQty[$key],
								'unit'							=> $request->arrUnit[$key]
							]);
							
							#updatestock
							$cek = Stock::where('product_id',$ar)->where('warehouse_id',$request->warehouse)->where('branch',$return->projectPurchase->sales->branch)->first();
							if($cek){
								$cek->update([
									'qty' 	=> $cek->qty - $request->arrQty[$key],
									'unit'	=> $request->arrUnit[$key]
								]);
							}else{
								Stock::create([
									'product_id'	=> $ar,
									'warehouse_id'	=> $request->warehouse,
									'qty'			=> 0 - $request->arrQty[$key],
									'unit'			=> $request->arrUnit[$key],
									'branch'		=> $return->projectPurchase->sales->branch
								]);
							}
						}
					}
					
					$total = round(ProjectPurchaseReturn::find($return->id)->getTotal());
					$realinventory = round(ProjectPurchaseReturn::find($return->id)->getRealInventory());
					
					$kreditcb = 31;
					$debitcb = (floatval(str_replace(',','.',str_replace('.','',$purchase->getPaid()))) - $total) >= 0 ? 24 : 332;
					$debitnominal = $total;
					
					if(date('Y-m-d',strtotime($return->projectPurchase->created_at)) < '2022-04-01'){
						$persenppn = 0.1;
						$ppnpembagi = 1.1;
					}else{
						$persenppn = 0.11;
						$ppnpembagi = 1.11;
					}
					
					if($purchase->ppn == '1'){
						$kreditnominal = round($debitnominal / $ppnpembagi,0);
						$ppnmasukan = 50;
						$ppnnominal = round($debitnominal - ($debitnominal / $ppnpembagi),0);
					}else{
						$kreditnominal = $debitnominal;
					}
					
					$balance = $kreditnominal - $realinventory;
					
					$kreditnominal = $realinventory;
					
					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'  	=> 'project_purchase_returns',
						'lookable_id'		=> $return->id,
						'code'        		=> strtoupper(Str::random(15)),
						'date'        		=> $request->date,
						'type'        		=> '3',
						'description' 		=> 'Purchase Return code '.$return->code.' to supplier '.$purchase->supplier->name.' with PO Supplier code '.$purchase->code
					]);
					
					if($cb){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debitcb,
							'branch'		=> $purchase->sales->branch,
							'type'       	=> '1',
							'nominal'      	=> round($debitnominal,0),
							'note'         	=> ''
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debitcb,
							'branch'		   => $purchase->sales->branch,
							'type'	           => '1',
							'nominal'          => round($debitnominal,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kreditcb,
							'branch'		=> $purchase->sales->branch,
							'type'       	=> '2',
							'nominal'      	=> round($kreditnominal,0),
							'note'         	=> ''
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kreditcb,
							'branch'		   => $purchase->sales->branch,
							'type'	           => '2',
							'nominal'          => round($kreditnominal,0),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						if($balance > 0){
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> 328,
								'branch'		=> $purchase->sales->branch,
								'type'       	=> '2',
								'nominal'      	=> round($balance,0),
								'note'         	=> ''
							]);
							
							Journal::insert([
								'date_transaction' => $request->date,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => 328,
								'branch'		   => $purchase->sales->branch,
								'type'	           => '2',
								'nominal'          => round($balance,0),
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
						}
						
						if($balance < 0){
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> 209,
								'branch'		=> $purchase->sales->branch,
								'type'       	=> '1',
								'nominal'      	=> round(abs($balance),0),
								'note'         	=> ''
							]);
							
							Journal::insert([
								'date_transaction' => $request->date,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => 209,
								'branch'		   => $purchase->sales->branch,
								'type'	           => '1',
								'nominal'          => round(abs($balance),0),
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
						}
						
						if($purchase->ppn == '1'){
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> $ppnmasukan,
								'branch'		=> $purchase->sales->branch,
								'type'       	=> '2',
								'nominal'      	=> round($ppnnominal,0),
								'note'         	=> ''
							]);
							
							Journal::insert([
								'date_transaction' => $request->date,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => $ppnmasukan,
								'branch'		   => $purchase->sales->branch,
								'type'	           => '2',
								'nominal'          => round($ppnnominal,0),
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
						}
						
						if($request->project_warehouse_id){
							$datapr = PurchaseRequest::where('link_type','project_purchases')->where('link_id',$purchase->id)->where('project_warehouse_id',$request->project_warehouse_id)->get();
						
							if($datapr){
								foreach($datapr as $rowpr){
									PurchaseRequest::find($rowpr->id)->update([
										'total_nominal' => $rowpr->total_nominal - round($return->getTotal(),0)
									]);
								}
							}
						}else{
							$datapr = PurchaseRequest::where('link_type','project_purchases')->where('link_id',$purchase->id)->whereNotNull('project_purchase_bill_id')->get();
						
							if($datapr){
								foreach($datapr as $rowpr){
									PurchaseRequest::find($rowpr->id)->update([
										'total_nominal' => $rowpr->total_nominal - round($return->getTotal(),0)
									]);
								}
							}
						}
					}
					
					#start notif
					$role = array('1','2','3','4','5','6','7','9','10','11');
					$title = 'Purchase Order ' . $purchase->code . ' has been returned with Purchase Return code ' . $return->code . '!';
					$description = 'Project Order '.$purchase->code.' with Purchase Return code ' .$return->code. ' has been returned by ' .session("bo_name"). ' with reason : '.$request->note;
					$link = '#';
					Notification::sendNotif($role,$title,$description,$link);
					#end notif
					
					#send approval
					$roleapproval = array('5');
					Approval::sendApproval($roleapproval,'project_purchase_returns',$return->id,'approved_by',session('bo_id'));
					
					SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Return No. '.$return->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
					
					ProductCogs::updateCogs($request->date,$purchase->sales->branch);
					
					activity()
						->performedOn(new ProjectPurchaseReturn())
						->causedBy(session('bo_id'))
						->withProperties($return)
						->log('Add new project purchase return code '.$return->code);
					
					return response()->json([
						'status'  => 200,
						'message' => 'Successfully saved'
					]);
				
				}else{
					return response()->json([
						'status'  => 400,
						'message' => 'This PO did not have warehouse receive yet!'
					]);
				}
			}else{
				return response()->json([
					'status'  => 400,
					'message' => 'The following items do not exist in the selected warehouse'
				]);
			}
		}else{
			return response()->json([
				'status'  => 503,
				'message' => 'You cannot add/edit. The journal for this month was already closed.'
			]);
		}
	}
	
	public function addProjectLog($user,$project,$table,$table_id,$approval,$type,$description){
		$projectlog = ProjectLog::create([
			'user_id'			=> $user,
			'project_id'		=> $project,
			'project_table'		=> $table,
			'project_table_id'	=> $table_id,
			'approval'			=> $approval,
			'type'				=> $type,
			'description'		=> $description
		]);
	}
	
	public function deletePayment(Request $request)
	{
		$pp = ProjectPay::find($request->idpay);
		$reason = $request->reason;
		
		//if($pp->project->progress <= 40){
			
			$pp->deleteFile();
			
			$pp->delete();
			
			Approval::where('approvalable_type','project_pays')->where('approvalable_id',$request->idpay)->delete();
			
			$cb = CashBank::where('lookable_type','project_pays')->where('lookable_id',$request->idpay)->get();
			
			foreach($cb as $row){
				$bh = BalanceHistory::find(explode('-',$cb->code))->first();
				if($bh){
					$bh->deleteFile();
					$bh->delete();
				}
				
				$row->deleteFile();
				$row->deleteDetail();
				$row->delete();
			}
			
			$this->addProjectLog(session('bo_id'),$pp->project->id,'project_pays',$pp->id,0,'delete',$reason);
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project sales payment with code ' . $pp->code . ' has been deleted!';
			$description = 'Project sales payment with code '.$pp->code.' has been deleted by ' .session("bo_name"). ' with reason : '.$reason;
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new ProjectSale())
				->causedBy(session('bo_id'))
				->withProperties($pp)
				->log('Delete project sales payment');
				
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
			
		/* }else{
			return response()->json([
				'status'  => 400,
				'message' => 'SO already has PO, please contact developer.'
			]);
		}*/ 
		
	}
	
	public function deletePurchasePayment(Request $request)
	{
		$pp = ProjectPayment::find($request->idpay);
		$reason = $request->reason;
		
		//if($pp->project->progress <= 48){
			
			$pp->deleteFile();
			
			$pp->delete();
			
			Approval::where('approvalable_type','project_payments')->where('approvalable_id',$request->idpay)->delete();
			
			$cb = CashBank::where('lookable_type','project_payments')->where('lookable_id',$request->idpay)->get();
			
			foreach($cb as $row){
				$row->deleteFile();
				$row->deleteDetail();
				$row->delete();
			}
			
			$this->addProjectLog(session('bo_id'),$pp->project->id,'project_payments',$pp->id,0,'delete',$reason);
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project purchase payment with purchase order code ' . $pp->projectPurchase->code . ' has been deleted!';
			$description = 'Project purchase payment with code '.$pp->projectPurchase->code.' has been deleted by ' .session("bo_name"). ' with reason : '.$reason;
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new ProjectSale())
				->causedBy(session('bo_id'))
				->withProperties($pp)
				->log('Delete project purchase payment');
				
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
			
		/* }else{
			return response()->json([
				'status'  => 400,
				'message' => 'PO already has Warehouse Receive, please contact developer.'
			]);
		} */
		
	}
	
	public function getProject(Request $request)
	{
		$project = Project::find($request->id);
		
		/* if($project->progress >= 40){
			return response()->json([
				'status'  => 400
			]);
		}else{ */
			$project['status'] = 200;
			$project['sales_name'] = isset($project->sales) ? $project->sales->name : '';
			
			return $project;
		//}
	}
	
	public function addProjectBill(Request $request)
	{
		$tempedit = $request->temp_bill_edit;
		$id = $request->id;
		$date = $request->date;
		$branch = $request->branch;
		$address = $request->address;
		$due_date = $request->due_date;
		$nominal = str_replace(',','.',str_replace('.','',$request->nominal));
		$nominal_service = str_replace(',','.',str_replace('.','',$request->nominal_service));
		$note = $request->note;
		
		$project = Project::find($id);
		
		$akuntingkan = true;
		

		// $projectSale = ProjectSale::where('project_id', $project->id)->orderBy('created_at', 'DESC')->first();
		// //if(count($project->projectQuotation) > 0 || $project->in_store == '1'){


		// 	if(date('Y-m-d',strtotime($projectSale->created_at)) < '2022-04-01'){
		// 		$persenppn = 0.1;
		// 		$ppnpembagi = 1.1;
		// 	}else{
		// 		$persenppn = 0.11;
		// 		$ppnpembagi = 1.11;
		// 	}
		// 	$nominal_sales_order = $project->ppn == '1' ? ($nominal > $projectSale->grandtotal_product + ($projectSale->grandtotal_product * $persenppn) || $nominal_service > $projectSale->grandtotal_service) : ($nominal > $projectSale->grandtotal_product || $nominal_service > $projectSale->grandtotal_service);
			
		// 	if($nominal_sales_order){
		// 		return response()->json([
		// 			'status' => 500,
		// 			'message'  => 'Hayoo Nominal Bill lebih besar dari Grandtotal SO'
		// 		]); 
		// 	}

			if($tempedit){
				$sb = ProjectBill::find($tempedit);
				
				$cekcb = CashBank::where('lookable_type','project_bills')->where('lookable_id',$sb->id)->first();
				
				if($cekcb){
					/* if(substr($cekcb->date,0,7) !== date('Y-m')){
						$akuntingkan = false;
						
						$selisih = ($sb->nominal + $sb->nominal_service) - ($nominal + $nominal_service);
						
						if($selisih > 0){
							$debetcb = 27;
							$kreditcb = 67;
							
							$cb = CashBank::create([
								'user_id'     		=> session('bo_id'),
								'lookable_type'  	=> 'project_bills',
								'lookable_id'		=> $sb->id,
								'code'        		=> strtoupper(Str::random(15)),
								'date'        		=> date('Y-m-d'),
								'type'        		=> 3,
								'description' 		=> 'Project bill number '.$sb->code.' has been created from Project number '.$project->code
							]);
							
							if($cb){
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $debetcb,
									'branch'		=> $branch,
									'type'       	=> '1',
									'nominal'      	=> floatval($selisih),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => date('Y-m-d'),
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $debetcb,
									'branch'		   => $branch,
									'type'	           => '1',
									'nominal'          => floatval($selisih),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $kreditcb,
									'branch'		=> $branch,
									'type'       	=> '2',
									'nominal'      	=> floatval($selisih),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => date('Y-m-d'),
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $kreditcb,
									'branch'		   => $branch,
									'type'	           => '2',
									'nominal'          => floatval($selisih),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
							}
						}elseif($selisih < 0){
							$selisih = abs($selisih);
							
							$debetcb = 67;
							$kreditcb = 27;
							
							$cb = CashBank::create([
								'user_id'     		=> session('bo_id'),
								'lookable_type'  	=> 'project_bills',
								'lookable_id'		=> $sb->id,
								'code'        		=> strtoupper(Str::random(15)),
								'date'        		=> date('Y-m-d'),
								'type'        		=> 3,
								'description' 		=> 'Project bill number '.$sb->code.' has been created from Project number '.$project->code
							]);
							
							if($cb){
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $debetcb,
									'branch'		=> $branch,
									'type'       	=> '1',
									'nominal'      	=> floatval($selisih),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => date('Y-m-d'),
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $debetcb,
									'branch'		   => $branch,
									'type'	           => '1',
									'nominal'          => floatval($selisih),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
								
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> $kreditcb,
									'branch'		=> $branch,
									'type'       	=> '2',
									'nominal'      	=> floatval($selisih),
									'note'         	=> ''
								]);

								Journal::insert([
									'date_transaction' => date('Y-m-d'),
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => $kreditcb,
									'branch'		   => $branch,
									'type'	           => '2',
									'nominal'          => floatval($selisih),
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
							}
						}
					}else{ */
						if($cekcb){
							$cekcb->deleteDetail();
							$cekcb->delete();
						}
					//}
				}
				
				$sb->user_id 				= session('bo_id');
				$sb->project_id 			= $project->id;
				$sb->branch 				= $branch;
				$sb->date					= $date;
				$sb->due_date				= $due_date;
				$sb->nominal				= $nominal;
				$sb->nominal_service		= $nominal_service;
				$sb->address				= $address;
				$sb->note					= $note;
				
				$sb->update();
				
				$approval = Approval::where('approvalable_type','project_bills')->where('approvalable_id',$sb->id)->delete();
			}else{
				$sb = ProjectBill::create([
					'user_id' 				=> session('bo_id'),
					'project_id' 			=> $project->id,
					'branch' 				=> $branch,
					'code'					=> ProjectBill::generateCode(),
					'date'					=> $date,
					'due_date'				=> $due_date,
					'nominal'				=> $nominal,
					'nominal_service'		=> $nominal_service,
					'address'				=> $address,
					'note'					=> $note
				]);
			}
			
			// if($akuntingkan == true){
			// 	//if(round($project->getTotalProject()) <= (floatval($nominal) + floatval($nominal_service))){
			// 		$debetcb = 27;
			// 		$kreditcb = 67;
					
			// 		$cb = CashBank::create([
			// 			'user_id'     		=> session('bo_id'),
			// 			'lookable_type'  	=> 'project_bills',
			// 			'lookable_id'		=> $sb->id,
			// 			'code'        		=> strtoupper(Str::random(15)),
			// 			'date'        		=> $date,
			// 			'type'        		=> 3,
			// 			'description' 		=> 'Project bill number '.$sb->code.' has been created from Project number '.$project->code
			// 		]);
					
			// 		if($cb){
						
			// 			CashBankDetail::create([
			// 				'cash_bank_id' 	=> $cb->id,
			// 				'coa_id'       	=> $debetcb,
			// 				'branch'		=> $branch,
			// 				'type'       	=> '1',
			// 				'nominal'      	=> floatval($nominal) + floatval($nominal_service),
			// 				'note'         	=> ''
			// 			]);

			// 			Journal::insert([
			// 				'date_transaction' => $date,
			// 				'journalable_type' => 'cash_banks',
			// 				'journalable_id'   => $cb->id,
			// 				'coa_id'           => $debetcb,
			// 				'branch'		   => $branch,
			// 				'type'	           => '1',
			// 				'nominal'          => floatval($nominal) + floatval($nominal_service),
			// 				'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
			// 				'updated_at'       => date('Y-m-d H:i:s')
			// 			]);
						
			// 			CashBankDetail::create([
			// 				'cash_bank_id' 	=> $cb->id,
			// 				'coa_id'       	=> $kreditcb,
			// 				'branch'		=> $branch,
			// 				'type'       	=> '2',
			// 				'nominal'      	=> floatval($nominal) + floatval($nominal_service),
			// 				'note'         	=> ''
			// 			]);

			// 			Journal::insert([
			// 				'date_transaction' => $date,
			// 				'journalable_type' => 'cash_banks',
			// 				'journalable_id'   => $cb->id,
			// 				'coa_id'           => $kreditcb,
			// 				'branch'		   => $branch,
			// 				'type'	           => '2',
			// 				'nominal'          => floatval($nominal) + floatval($nominal_service),
			// 				'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
			// 				'updated_at'       => date('Y-m-d H:i:s')
			// 			]);
						
			// 		}
			// 	//}
			// }
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Project '.$project->code.' with details payment bill code '.$sb->code.' has been updated by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			#send approval
			if($sb->project->user->branch == '2'){
				$roleapproval = 16;
				Approval::sendApproval($roleapproval,'project_bills',$sb->id,'checked_id',session('bo_id'));
				
				SendMessage::send(env('SALES_MANAGER_JAKARTA_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Bill No. '.$sb->code.' Project No. '.$project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			}elseif($sb->project->user->branch == '1'){
				$roleapproval = array('5');
				Approval::sendApproval($roleapproval,'project_bills',$sb->id,'checked_id',session('bo_id'));
				
				SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Bill No. '.$sb->code.' Project No. '.$project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			}
			
			$roleapproval = array('4');
			Approval::sendApproval($roleapproval,'project_bills',$sb->id,'approved_id',session('bo_id'));
			#end approval
			
			SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Bill No. '.$sb->code.' Project No. '.$project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			
			activity()
				->performedOn(new ProjectBill())
				->causedBy(session('bo_id'))
				->withProperties($sb)
				->log('Change data project ' . $project->name . ' Project Bill');
			
			return response()->json([
					'status'  => 200,
					'message' => 'Data added successfully.'
				]);
		/* }else{
			return response()->json([
					'status'  => 500,
					'message' => 'This project did not have Quotation yet.'
				]);
		} */
	}
	
	public function addPurchaseBill(Request $request){
		
		$projectpurchase = ProjectPurchase::find($request->id);
		
		$totalbill = 0;
		$totalpo = str_replace(',','.',str_replace('.','',$projectpurchase->getTotal()));
		
		foreach($projectpurchase->projectPurchaseBill as $row){
			$totalbill += $row->nominal;
		}
		
		$totalbill += str_replace(',','.',str_replace('.','',$request->nominal));
		
		/*if($totalbill > $totalpo){
			return response()->json([
				'status'  => 500,
				'message' => 'Total bill has exceeded total Purchase Order.'
			]);
		}*/
		
		$ppb = ProjectPurchaseBill::create([
			'user_id'				=> session('bo_id'),
			'project_id'			=> $projectpurchase->project->id,
			'project_purchase_id'	=> $request->id,
			'no_document'			=> $request->no,
			'method'				=> $request->method,
			'date'					=> $request->date,
			'due_date'				=> $request->duedate,
			'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal)),
			'note'         			=> $request->note,
			'image'					=> $request->file('file') ? $request->file('file')->store('public/project') : ''
		]);
		
		$projectpurchase->update([
			'payment_due_date' => $request->duedate
		]);
		
		$query = PurchaseRequest::create([
			'date'	     				=> $request->date,
			'user_id'					=> session('bo_id'),
			//'item'					=> $projectpurchase->project->code.' - '.$projectpurchase->supplier->name.' - '.$projectpurchase->projectSale->code.' Link : https://smartmarbleandbath.com/admin/purchase_order/project/print/purchase_order/'.base64_encode($projectpurchase->id),
			'branch'					=> $projectpurchase->sales->branch,
			'coa_id'					=> 332,
			'item'						=> 'PROJECT PURCHASE PAYMENT REQUEST NUMBER '.$projectpurchase->project->code.' - '.$projectpurchase->supplier->name.' - '.$projectpurchase->projectSale->code.' with Note : '.$request->note.' with PO No. '.$projectpurchase->code,
			'image'						=> $request->file('file') ? $request->file('file')->store('public/purchase') : '',
			'total_nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal)),
			'status'					=> 'PEND',
			'link_type'					=> 'project_purchases',
			'link_id'					=> $projectpurchase->id,
			'project_purchase_bill_id'	=> $ppb->id,
			'due_date'					=> $request->duedate ? $request->duedate : NULL
		]);
		
		#send approval
		$roleapproval = array('4');
		Approval::sendApproval($roleapproval,'purchase_requests',$query->id,'approved_by',session('bo_id'));
		#end approval
		
		SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. '.$query->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
		
		activity()
			->performedOn(new ProjectPurchaseBill())
			->causedBy(session('bo_id'))
			->withProperties($ppb)
			->log('Add project purchase Bill by '.session('bo_name'));
		
		return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
	}
	
	public function getPurchaseBillInfo(Request $request)
	{
		$bill = ProjectPurchaseBill::find($request->bill);
		
		return response()->json([
			'nominal' => number_format($bill->nominal,0,',','.')
		]);
	}
	
	public function getReport(Request $request)
	{
		
		$htmlPaid = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>SO Number</th>
							<th>PJ Number</th>
							<th>Customer</th>
							<th>Sales</th>
							<th>Project</th>
							<th>Term</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>';
		
		$htmlUnpaid = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>SO Number</th>
							<th>PJ Number</th>
							<th>Customer</th>
							<th>Sales</th>
							<th>Project</th>
							<th>Term</th>
							<th>Date Received</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>';
					
		$nopaid = 1;
		$nounpaid = 1;
		
		$totalunpaid = 0;
		$totalagingbill = 0;
		
		if($request->mode == 'paid' || $request->mode == 'unpaid'){
			foreach(ProjectSale::all() as $rowsale){
				$deliverypaid = 0;
				$totalpaid = 0;
				$customer = $rowsale->project->customer_id;
				$project_id = $rowsale->project->id;
				
				$textDate = '';
				
				foreach($rowsale->projectDelivery as $key => $rowdelivery){
					if($key == 0){
						$deliverypaid += $rowdelivery->subtotal_product + $rowdelivery->subtotal_service;
						$totalpaid += $rowdelivery->subtotal_product + $rowdelivery->subtotal_service;
					}else{
						$deliverypaid += $rowdelivery->subtotal_product;
						$totalpaid += $rowdelivery->subtotal_product;
					}
					
					$textDate .= $rowdelivery->received_date.'<br>';
				}
				
				foreach($rowsale->projectSalePay as $rowpay){
					$deliverypaid -= $rowpay->nominal;
				}
				
				foreach($rowsale->projectSaleReturn as $rowreturn){
					$deliverypaid -= $rowreturn->grandtotal;
				}
				
				$otherpayment = CashBankDetail::whereHas('cashBank', function($query) use($customer,$project_id){
					$query->where('customer_id',$customer)
					->where('lookable_type','projects')
					->where('lookable_id',$project_id);
				})
				->where('coa_id',27)
				->where('type','2')
				->sum('nominal');
				
				$deliverypaid -= $otherpayment;
				
				if($rowsale->project->progress >= 80){
					if(round($deliverypaid) <= 0){
						$htmlPaid .= '
							<tr class="'.($rowsale->getTaxDocument() ? 'bg-success' : 'bg-warning').'">
								<td class="text-center">'.$nopaid.'</td>
								<td class="text-center">'.$rowsale->code.'</td>
								<td class="text-center">'.$rowsale->project->code.'</td>
								<td class="text-center">'.$rowsale->project->customer->name.'</td>
								<td class="text-center">'.$rowsale->sales->name.'</td>
								<td class="text-center">'.$rowsale->project->name.'</td>
								<td class="text-center">'.$rowsale->project->paymentTerm().'</td>
								<td class="text-center">'.number_format($totalpaid,2,',','.').'</td>
							</tr>
						';
						$nopaid++;
					}else{
						$htmlUnpaid .= '
							<tr>
								<td class="text-center">'.$nounpaid.'</td>
								<td class="text-center">'.$rowsale->code.'</td>
								<td class="text-center">'.$rowsale->project->code.'</td>
								<td class="text-center">'.$rowsale->project->customer->name.'</td>
								<td class="text-center">'.$rowsale->sales->name.'</td>
								<td class="text-center">'.$rowsale->project->name.'</td>
								<td class="text-center">'.$rowsale->project->paymentTerm().'</td>
								<td class="text-center">'.$textDate.'</td>
								<td class="text-center">'.number_format($deliverypaid,2,',','.').'</td>
							</tr>
						';
						$nounpaid++;
					}
				}
			}
		
		}
		
		$htmlPaid .= '
				</tbody>
			</table>
		</div>
		';
		
		$htmlUnpaid .= '
				</tbody>
			</table>
		</div>
		';
		
		$noundelivered = 1;
		
		$htmlUndelivered = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th colspan="7">Undelivered</th>
						</tr>
						<tr class="text-center">
							<th>No.</th>
							<th>PJ Number</th>
							<th>Customer</th>
							<th>Sales</th>
							<th>Project</th>
							<th>Progress</th>
							<th>Delivery Needed</th>
						</tr>
					</thead>
					<tbody>';
					
		$nounreceived = 1;
		
		$htmlUnreceived = '<table class="table table-bordered mt-3">
					<thead class="table-secondary">
						<tr class="text-center">
							<th colspan="7">Unreceived</th>
						</tr>
						<tr class="text-center">
							<th>No.</th>
							<th>DO No.</th>
							<th>PJ No.</th>
							<th>Customer</th>
							<th>Sales</th>
							<th>Project</th>
							<th>Progress</th>
						</tr>
					</thead>
					<tbody>';
		
		$totalUndelivered = 0;
		
		if($request->mode == 'undelivered'){
			foreach(Project::where('progress','>=',65)->get() as $val){
				$balanceItems = 0;
				
				foreach($val->projectSale()->whereNull('is_closed')->get() as $ps){
					
					if(!$ps->projectFromStock){
						foreach($ps->projectPurchase as $pp){
					
							foreach($pp->projectWarehouse as $pw){
								foreach($pw->projectWarehouseProduct as $pwp){
									$balanceItems += $pwp->qty;
								}
							}
							
							foreach($pp->projectPurchaseReturn as $ppr){
								foreach($ppr->projectPurchaseReturnProduct as $pprp){
									$balanceItems -= $pprp->qty;
								}
							}
						}
					}
					
					foreach($ps->projectFromStock as $pfs){
						foreach($pfs->projectFromStockProduct as $pfsp){
							$balanceItems += $pfsp->qty;
						}
					}
					
					foreach($ps->projectDelivery as $pd){
						foreach($pd->projectDeliveryProduct as $pdp){
							$balanceItems -= $pdp->qty;
						}
						
						if($pd->received_date == NULL){
							$htmlUnreceived .= '
								<tr>
									<td class="text-center">'.$nounreceived.'</td>
									<td class="text-center">'.$pd->code.'</td>
									<td class="text-center">'.$pd->project->code.'</td>
									<td class="text-center">'.$pd->projectSale->project->customer->name.'</td>
									<td class="text-center">'.$pd->projectSale->sales->name.'</td>
									<td class="text-center">'.$pd->project->name.'</td>
									<td class="text-center">'.$pd->project->progress.'%</td>
								</tr>
							';
							
							$nounreceived++;
						}
					}
					
					foreach($ps->projectSaleReturn() as $psr){
						// JIKA TIDAK RETURN SEBAGAI COST, MAKA HARUS DIKIRIM ULANG
						if($psr->type !== 2){
							foreach($pd->projectSaleReturnProduct as $psrp){
								$balanceItems += $psrp->qty;
							}
						}
					
					}
				}
				
				if(round($balanceItems) > 0){
					$htmlUndelivered .= '
						<tr>
							<td>'.$noundelivered.'</td>
							<td class="text-center">'.$val->code.'</td>
							<td class="text-center">'.$val->customer->name.'</td>
							<td class="text-center">'.$val->projectSale->first()->sales->name.'</td>
							<td class="text-center">'.$val->name.'</td>
							<td class="text-center">'.$val->progress.'%</td>
							<td class="text-center">'.$balanceItems.'</td>
						</tr>
					';
					$noundelivered++;
				}
			}
		}
		
		$htmlUndelivered .= '
				</tbody>
			</table>
		</div>
		';
		
		$htmlUnreceived .= '
				</tbody>
			</table>
		</div>
		';
		
		##
		
		$nounmatch = 1;
		
		$htmlUnmatch = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>PJ Number</th>
							<th>Customer</th>
							<th>Sales</th>
							<th>Project</th>
							<th>Progress</th>
							<th>Unmatch Qty</th>
						</tr>
					</thead>
					<tbody>';
		
		$totalUnmatch = 0;
		
		if($request->mode == 'unmatch'){
			foreach(Project::where('progress','>=',65)->whereHas('projectPurchase')->get() as $val){
				$balanceItems = 0;
				
				foreach($val->projectSale as $ps){
					
					foreach($ps->projectPurchase as $pp){
					
						foreach($pp->projectWarehouse as $pw){
							foreach($pw->projectWarehouseProduct as $pwp){
								$balanceItems += $pwp->qty;
							}
						}
						
						foreach($pp->projectPurchaseReturn as $ppr){
							foreach($ppr->projectPurchaseReturnProduct as $pprp){
								$balanceItems -= $pprp->qty;
							}
						}
					}
					
					foreach($ps->projectDelivery as $pd){
						foreach($pd->projectDeliveryProduct as $pdp){
							$balanceItems -= $pdp->qty;
						}
					}
					
					foreach($ps->projectSaleReturn() as $psr){
						foreach($pd->projectSaleReturnProduct as $psrp){
							$balanceItems += $psrp->qty;
						}
					}
				}
				
				if(round($balanceItems) > 0){
					$htmlUnmatch .= '
						<tr>
							<td>'.$nounmatch.'</td>
							<td>'.$val->code.'</td>
							<td>'.$val->customer->name.'</td>
							<td>'.$val->projectSale->first()->sales->name.'</td>
							<td>'.$val->name.'</td>
							<td class="text-center">'.$val->progress.'%</td>
							<td class="text-center">'.$balanceItems.'</td>
						</tr>
					';
					$nounmatch++;
				}
			}
		}
		
		$htmlUnmatch .= '
				</tbody>
			</table>
		</div>
		';
		
		##
		
		$noempty = 1;
		
		$htmlEmpty = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>PJ Number</th>
							<th>Supplier</th>
							<th>Sales</th>
							<th>Project</th>
							<th>Progress</th>
						</tr>
					</thead>
					<tbody>';
		
		$totalEmptyBill = 0;
		
		if($request->mode == 'emptybill'){
			foreach(ProjectPurchase::all() as $val){
				if(count($val->projectPurchaseBill) == 0){
					$htmlEmpty .= '
						<tr>
							<td>'.$noempty.'</td>
							<td>'.($val->project ? $val->project->code : 'None').'</td>
							<td>'.$val->supplier->name.'</td>
							<td>'.($val->projectSale ? $val->projectSale->first()->sales->name : 'None').'</td>
							<td>'.($val->project ? $val->project->name : 'For Stock').'</td>
							<td class="text-center">'.($val->project ? $val->project->progress.'%' : 'None').'</td>
						</tr>
					';
					$noempty++;
				}
			}
		}
		
		$htmlEmpty .= '
				</tbody>
			</table>
		</div>
		';
		
		##
		
		$nounmatchso = 1;
		
		$htmlUnmatchSo = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>PJ Number</th>
							<th>Customer</th>
							<th>Sales</th>
							<th>Project</th>
							<th>Progress</th>
							<th>Unmatch Qty</th>
						</tr>
					</thead>
					<tbody>';
		
		$totalUnmatchSo = 0;
		
		if($request->mode == 'unmatchso'){
			foreach(Project::whereHas('projectSale',function($query){ $query->whereNull('is_closed'); })->where('progress','>=',40)->get() as $val){
				$balanceItems = 0;
				
				$notes = '';
				
				foreach($val->projectSale as $ps){
					
					foreach(ProjectNote::where('notable_type','project_sales')->where('notable_id',$ps->id)->get() as $rownote){
						$notes .= $rownote->note;
					}
					
					$arrproduct = [];
					
					foreach($ps->projectSaleProduct as $psp){
						
						$countproduct = 0;
						
						if($psp->unit == '2' || $psp->unit == '3'){
							$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
							
							if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
								$balanceItems += $psp->qty;
								$countproduct += $psp->qty;
							}else{
								if($m2 < 1.1 && date('Y-m',strtotime($ps->project->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
									$balanceItems += $psp->qty;
									$countproduct += $psp->qty;
								}else{
									$balanceItems += ceil(round($psp->qty / $m2,2));
									$countproduct += ceil(round($psp->qty / $m2,2));
								}
							}
						}else{
							$balanceItems += $psp->qty;
							$countproduct += $psp->qty;
						}
						
						foreach($ps->projectPurchase as $pp){
							foreach($pp->projectPurchaseProduct->where('product_id',$psp->product_id) as $ppp){
								$countproduct -= $ppp->qty;
							}
							
							foreach($pp->projectPurchaseReturn as $ppr){
								foreach($ppr->projectPurchaseReturnProduct->where('product_id',$psp->product_id) as $pprp){
									$countproduct += $pprp->qty;
								}
							}
						}
						
						foreach($ps->projectFromStock as $pfs){
							foreach($pfs->projectFromStockProduct->where('product_id',$psp->product_id) as $pfsp){
								$countproduct -= $pfsp->qty;
							}
						}
						
						if($countproduct > 0.9){
							$psp['qty_balance'] = $countproduct;
							$arrproduct[] = $psp;
						}
					}
					
					foreach($ps->projectPurchase as $pp){
						foreach($pp->projectPurchaseProduct as $ppp){
							$balanceItems -= $ppp->qty;
						}
						
						foreach($pp->projectPurchaseReturn as $ppr){
							foreach($ppr->projectPurchaseReturnProduct as $pprp){
								$balanceItems += $pprp->qty;
							}
						}
					}
					
					foreach($ps->projectFromStock as $pfs){
						foreach($pfs->projectFromStockProduct as $pfsp){
							$balanceItems -= $pfsp->qty;
						}
					}
				}
				
				if($balanceItems > 0.9){
					$htmlUnmatchSo .= '
						<tr>
							<td rowspan="2">'.$nounmatchso.'</td>
							<td>'.$val->code.'</td>
							<td>'.$val->customer->name.'</td>
							<td>'.$val->projectSale->first()->sales->name.'</td>
							<td>'.$val->name.'</td>
							<td class="text-center">'.$val->progress.'%</td>
							<td class="text-center">'.round($balanceItems,2).'</td>
						</tr>
					';
					
					$htmlUnmatchSo .= '<tr>
							<td colspan="6">
								Notes from Sales : '.$notes.'
								<br><br>
								Details Product: 
								<p><ol>';
								
					foreach($arrproduct as $rowpp){
						$htmlUnmatchSo .= '
							<li>
								'.$rowpp->product->name().' - Qty. '.$rowpp->qty_balance.'
							</li>
						';
					}
						
					$htmlUnmatchSo .= '
									</ol>
								</p>
							</td>
						</tr>
					';
					$nounmatchso++;
				}
			}
		}
		
		$htmlUnmatchSo .= '
				</tbody>
			</table>
		</div>
		';
		
		##
		
		$nounmatchpowr = 1;
		
		$htmlUnmatchPoWr = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>PJ Number</th>
							<th>Customer</th>
							<th>Sales</th>
							<th>Project</th>
							<th>Progress</th>
							<th>PO Qty > WR Qty</th>
							<th>Details Product</th>
						</tr>
					</thead>
					<tbody>';
		
		$totalunmatchpowr = 0;
		
		if($request->mode == 'unmatchpowr'){
			foreach(Project::whereHas('projectPurchase')->get() as $val){
				$balanceItems = 0;
				
				$arrProduct = [];
				$tempArrPp = [];
				$tempArrPw = [];
				$tempArrPr = [];
				
				foreach($val->projectSale as $ps){
					foreach($ps->projectPurchase as $pp){
						foreach($pp->projectPurchaseProduct as $ppp){
							
							$ada = false;
							foreach($tempArrPp as $key => $rowcek){
								if($rowcek->product_id == $ppp->product_id){
									$ada = true;
									$index = $key;
								}
							}
							
							if($ada == false){
								$tempArrPp[] = $ppp;
							}else{
								$tempArrPp[$index]['qty'] += $ppp->qty;
							}
							
							$balanceItems += $ppp->qty;
						}
						
						foreach($pp->projectWarehouse as $pw){
							foreach($pw->projectWarehouseProduct as $pwp){
								$tempArrPw[] = $pwp;
								$balanceItems -= $pwp->qty;
							}
						}
						
						foreach($pp->projectPurchaseReturn as $ppr){
							foreach($ppr->projectPurchaseReturnProduct as $pprp){
								$tempArrPr[] = $pprp;
								$balanceItems -= $pprp->qty;
							}
						}
					}
				}
				
				foreach($tempArrPp as $row){
					$balanceqty = $row->qty;
					foreach($tempArrPw as $rowpw){
						if($rowpw->product_id == $row->product_id){
							$balanceqty -= $rowpw->qty;
						}
					}
					foreach($tempArrPr as $rowpr){
						if($rowpr->product_id == $row->product_id){
							$balanceqty -= $rowpr->qty;
						}
					}
					if($balanceqty > 0.9){
						$row['balanceqty'] = $balanceqty;
						$arrProduct[] = $row;
					}
				}
				
				$resultproduct = '<ol>';
				
				foreach($arrProduct as $key => $row){
					$resultproduct .= '<li>'.$row->product->name().' Qty. '.$row->balanceqty.'</li>';
				}
				
				$resultproduct .= '</ol>';
				
				if($balanceItems > 0.9){
					$htmlUnmatchPoWr .= '
						<tr>
							<td>'.$nounmatchpowr.'</td>
							<td>'.$val->code.'</td>
							<td>'.$val->customer->name.'</td>
							<td>'.$val->projectSale->first()->sales->name.'</td>
							<td>'.$val->name.'</td>
							<td class="text-center">'.$val->progress.'%</td>
							<td class="text-center">'.round($balanceItems,2).'</td>
							<td>'.$resultproduct.'</td>
						</tr>
					';
					$nounmatchpowr++;
				}
			}
		}
		
		$htmlUnmatchPoWr .= '
				</tbody>
			</table>
		</div>
		';
		
		##
		
		return response()->json([
			'contentPaid' 			=> $htmlPaid,
			'contentUnpaid'			=> $htmlUnpaid,
			'contentUndelivered'	=> $htmlUndelivered.$htmlUnreceived,
			'contentUnmatch'		=> $htmlUnmatch,
			'contentUnmatchSo'		=> $htmlUnmatchSo,
			'contentEmptyBill'		=> $htmlEmpty,
			'contentUnmatchPoWr'	=> $htmlUnmatchPoWr
		]);
	}
	
	public function getPurchaseBill(Request $request)
	{
		##
		$no = 1;
		
		$html = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>PJ Number</th>
							<th>Customer</th>
							<th>Supplier</th>
							<th>PO No.</th>
							<th>Document No.</th>
							<th>Nominal</th>
							<th width="7%">Req Date</th>
							<th width="7%">Due Date</th>
							<th>Note</th>
						</tr>
					</thead>
					<tbody>';
		
		
		foreach(ProjectPurchaseBill::orderBy('project_purchase_id')->get() as $val){
			$datenow = strtotime(date('Y-m-d'));
			$duedate = strtotime($val->due_date);
			$diff = round(($duedate - $datenow) / 86400);
			
			$data = PurchaseRequest::where('project_purchase_bill_id',$val->id)->get();
			
			$balance = 0;
			foreach($data as $row){
				$balance += $row->total_nominal;
				foreach($row->purchaseRequestPayment as $rowpay){
					$balance -= $rowpay->nominal;
				}
			}
			
			$colorDueDate = '';
			
			if($balance <= 0){
				$colorDueDate = 'bg-primary';
			}else{
				if($diff > 14){
					$colorDueDate = 'bg-success';
				}elseif($diff > 7){
					$colorDueDate = 'bg-warning';
				}elseif($diff <= 0){
					$colorDueDate = 'bg-danger';
				}
			}
			
			$html .= '
				<tr>
					<td>'.$no.'</td>
					<td>'.($val->project_id == 0 ? 'For Stock' : $val->project->code).'</td>
					<td>'.($val->project_id == 0 ? 'For Stock' : $val->project->customer->name).'</td>
					<td>'.$val->projectPurchase->supplier->name.'</td>
					<td>'.$val->projectPurchase->code.'</td>
					<td>'.$val->no_document.'</td>
					<td class="text-right">'.number_format($val->nominal,0,',','.').'</td>
					<td>'.date('d M Y', strtotime($val->date)).'</td>
					<td class="'.$colorDueDate.'">'.date('d M Y', strtotime($val->due_date)).'</td>
					<td>'.$val->note.'</td>
				</tr>
			';
			$no++;
		}
		
		$html .= '
				</tbody>
			</table>
		';
		
		##
		
		return response()->json([
			'content' => $html
		]);
	}
	
	public function addSampleProof(Request $request){
		
		$validation = Validator::make($request->all(), [
			'return_proof'  	=> 'required',
			'tempsample'  		=> 'required'
		], [
			'return_proof.required'    	=> 'Proof cannot be empty.',
			'tempsample.required'   	=> 'Sample information cannot be empty.'
		]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$projectsample = ProjectSample::find($request->tempsample);
			
			if($request->has('return_proof')) {
				if(Storage::exists($projectsample->return_proof)) {
					Storage::delete($projectsample->return_proof);
				}

				$image = $request->file('return_proof')->store('public/project');
			} else {
				$image = $projectsample->return_proof;
			}
			
			$projectsample->update([
				'return_proof' => $image
			]);
			
			activity()
				->performedOn(new ProjectSample())
				->causedBy(session('bo_id'))
				->withProperties($projectsample)
				->log('Edit project sample add proof '.session('bo_name'));
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
		}
		
		return response()->json($response);
	}
	
	public function addPictures(Request $request){
		
		$count = ProjectPicture::where('project_id',$request->id)->count();
		
		if($count >= 5){
			return response()->json([
				'status'		=> 422,
				'message'		=> 'You have reached maximum file uploads for this project.'
			]);
		}else{
			$query = ProjectPicture::create([
				'project_id'	=> $request->id,
				'image'			=> $request->file('file') ? $request->file('file')->store('public/project_pictures') : ''
			]);
			
			return response()->json([
				'status'		=> 200,
				'message'		=> 'You have successfully upload the file.'
			]);
		}
	}
	
	public function getPictures(Request $request){
		$data = ProjectPicture::where('project_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$result[] = [
				'id'	=> $row->id,
				'image'	=> $row->attachment(),
				'name'	=> $row->project->name.' - No. '.$row->project->code
			];
		}
		
		return response()->json($result);
	}
	
	public function deletePictures(Request $request){
		$data = ProjectPicture::find($request->id);
		
		$data->deleteFile();
		
		$data->delete();
		
		if($data){
			return response()->json([
				'status'	=> 200,
				'message'	=> 'Picture successfully deleted.' 
			]);
		}else{
			return response()->json([
				'status'	=> 422,
				'message'	=> 'Picture not found.'
			]);
		}
	}
	
	public function getItems(Request $request)
	{
		
		$no = 1;
		
		if($request->customer_id){
			
			$data = ProjectDeliveryProduct::whereHas('projectDelivery', function($query) use($request){
					$query->whereHas('project', function($query) use($request){
						$query->where('customer_id',$request->customer_id);
					});
				})
				->get();
			
			$html = '<div class="table-responsive">
						<table class="table table-bordered">
							<thead class="table-secondary">
								<tr class="text-center">
									<th>No.</th>
									<th>Project</th>
									<th>Delivery</th>
									<th>Product</th>
									<th>Qty</th>
									<th>Price@</th>
								</tr>
							</thead>
							<tbody>';
			
			foreach($data as $row){
				$html .= '
					<tr>
						<td>'.$no.'</td>
						<td class="text-center">'.$row->projectDelivery->project->code.'</td>
						<td class="text-center">'.$row->projectDelivery->code.'</td>
						<td class="text-center">'.$row->product->name().' '.$row->product->type->length.'x'.$row->product->type->width.'</td>
						<td class="text-center">'.$row->qty.'</td>
						<td class="text-center">'.$row->salePrice().'</td>
					</tr>
				';
				$no++;
			}
		
		}
		
		if($request->product_id){
			
			$data = ProjectDeliveryProduct::where('product_id',$request->product_id)->get();
			
			$html = '<div class="table-responsive">
						<table class="table table-bordered">
							<thead class="table-secondary">
								<tr class="text-center">
									<th>No.</th>
									<th>Customer</th>
									<th>Project</th>
									<th>Delivery</th>
									<th>Product</th>
									<th>Qty</th>
									<th>Price@</th>
								</tr>
							</thead>
							<tbody>';
			
			foreach($data as $row){
				$html .= '
					<tr>
						<td class="text-center">'.$no.'.</td>
						<td class="text-center">'.$row->projectDelivery->project->customer->name.'</td>
						<td class="text-center">'.$row->projectDelivery->project->code.'</td>
						<td class="text-center">'.$row->projectDelivery->code.'</td>
						<td class="text-center">'.$row->product->name().' '.$row->product->type->length.'x'.$row->product->type->width.'</td>
						<td class="text-center">'.$row->qty.'</td>
						<td class="text-center">'.$row->salePrice().'</td>
					</tr>
				';
				$no++;
			}
		
		}
		
		$html .= '
				</tbody>
			</table>
		</div>
		';
		
		##
		
		return response()->json([
			'content' 				=> $html
		]);
	}
	
	public function getPurchaseReturn(Request $request){
		$id = $request->idpo;
		
		$result = [];
		
		$data = ProjectPurchaseReturn::where('project_purchase_id',$id)->get();
		
		foreach($data as $row){
			$result[] = [
				'id'		=> $row->id,
				'no'		=> $row->code,
				'po_no'		=> $row->projectPurchase->code,
				'supplier'	=> $row->projectPurchase->supplier->name,
				'approved'	=> isset($row->approve->name) ? $row->approve->name : 'Waiting',
				'link'		=> url('admin/purchase_order/project/print/purchase_return/' . base64_encode($row->id)),
				'proof'		=> $row->attachment()
			];
		}
		
		return response()->json($result);
	}
	
	public function getWarehouseReceive(Request $request){
		$id = $request->idpo;
		
		$result = [];
		
		$data = ProjectWarehouse::where('project_purchase_id',$id)->get();
		
		foreach($data as $row){
			$result[] = [
				'id'			=> $row->id,
				'no'			=> $row->code,
				'po_no'			=> $row->projectPurchase->code,
				'shipment_no'	=> $row->projectPurchase->code,
				'person'		=> $row->person,
				'warehouse'		=> $row->warehouse->name.' - '.$row->warehouse->code,
				'date'			=> $row->date_receive,
				'link'			=> url('admin/purchase_order/project/print/warehouse_receive/' . base64_encode($row->id)),
				'proof'			=> $row->attachment()
			];
		}
		
		return response()->json($result);
	}
	
	public function skipPurchaseOrder(Request $request){
		$id = $request->projectid;
		$po_id = $request->purchase_id;
 		
		$data = Project::find($id);
		
		$data->update([
			'progress'			=> 65,
			'purchase_order_id'	=> $po_id
		]);
		
		activity()
			->performedOn(new Project())
			->causedBy(session('bo_id'))
			->log('Update data project ' . $data->name . ' (Step 9) skip purchase project and took from stock.');
		
		return response()->json([
			'status'	=> 200,
			'url'		=> url('admin/purchase_order/project/progress/' . $id . '?step-15=1#step-15')
		]);
	}
	
	public function getBillEdit(Request $request){
		$data = ProjectBill::find($request->id);
		
		$data['nominal'] = number_format($data->nominal,0,',','.');
		$data['nominal_service'] = number_format($data->nominal_service,0,',','.');
		
		return response()->json($data);
	}
	
	function enkripsi($anu){
		if($anu == ''){
			$val = "";
		}else{
			$val = implode('-',str_split(str_replace('=','',base64_encode($anu)),5));
		}
		
		return $val;
	}

	public function getReturnMemo(Request $request){
		$project_return_memo = ProjectReturnMemo::find($request->id_return_memo);

		return response()->json([
			'delivery_id' => $project_return_memo->projectDelivery->id,
			'delivery_code' => $project_return_memo->projectDelivery->code,
			'reason' => $project_return_memo->reason,
			'date' => $project_return_memo->date,
		]);
	}
	
	public function addReturnMemo(Request $request){
		
		$validation = Validator::make($request->all(), [
			'delivery_id'  			=> 'required',
			'date'  				=> 'required',
			'reason'				=> 'required',
			'delivery_product_id'	=> 'required|array',
			'delivery_product_qty'	=> 'required|array',
			'delivery_product_unit'	=> 'required|array',
		], [
			'delivery_id.required'    			=> 'Delivery order cannot be empty.',
			'date.required'   					=> 'Date information cannot be empty.',
			'reason.required'					=> 'Reason cannot be empty.',
			'delivery_product_id.required'      => 'Product cannot empty.',
			'delivery_product_id.array'         => 'Product must be array.',
			'delivery_product_qty.required'     => 'Product qty cannot empty.',
			'delivery_product_qty.array'        => 'Product qty must be array.',
			'delivery_product_unit.required'    => 'Product unit cannot empty.',
			'delivery_product_unit.array'       => 'Product unit must be array.'
		]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			$delivery = ProjectDelivery::find($request->delivery_id);

			
			if($request->temp_return_memo_id){
				$query = ProjectReturnMemo::find($request->temp_return_memo_id);
				
				$query ->update([
					'user_id'				=> session('bo_id'),
					'date'					=> $request->date,
					'project_id'			=> $delivery->project->id,
					'project_delivery_id'	=> $delivery->id,
					'reason'				=> $request->reason,
					'image'					=> $request->has('return_memo_proof') ? $request->file('return_memo_proof')->store('public/project') : NULL
				]);

				$query->projectReturnMemoDetail()->delete();
			}else{
				$query = ProjectReturnMemo::create([
					'user_id'				=> session('bo_id'),
					'date'					=> $request->date,
					'project_id'			=> $delivery->project->id,
					'project_delivery_id'	=> $delivery->id,
					'reason'				=> $request->reason,
					'image'					=> $request->has('return_memo_proof') ? $request->file('return_memo_proof')->store('public/project') : NULL
				]);
			}
			
			
			foreach($request->delivery_product_id as $key => $product){
				ProjectReturnMemoDetail::create([
					'project_return_memo_id'	=> $query->id,
					'product_id'				=> $product,
					'qty'						=> $request->delivery_product_qty[$key],
					'unit'						=> $request->delivery_product_unit[$key]
				]);
			}
			
			activity()
				->performedOn(new ProjectReturnMemo())
				->causedBy(session('bo_id'))
				->withProperties($query)
				->log('Add project return memo by '.session('bo_name'));
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
		}
		
		return response()->json($response);
	}

	public function destroyReturnMemo(Request $request){

		$projectReturnMemo = ProjectReturnMemo::where('id', $request->id)->first();

		if(!$projectReturnMemo->projectDelivery){
			$projectReturnMemo->projectReturnMemoDetail->delete();

			$query = $projectReturnMemo->delete();
		}


		if($query){
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
		}else{
			$response = [
				'status'  => 500,
				'message' => 'Data added successfully.'
			];
		}

		activity()
		->performedOn(new ProjectReturnMemo())
		->causedBy(session('bo_id'))
		->withProperties($query)
		->log('Delete project return memo by '.session('bo_name'));


		return response()->json($response);

	}
	
	public function getDeliveryInfo(Request $request){
		$id = $request->id;
		
		$product = [];
		
		$data = ProjectDelivery::find($id);
		$data['city_name'] = $data->city->name;
		
		foreach($data->projectDeliveryProduct as $row){
			
			/* $totalstok = 0;
			
			foreach(Stock::where('product_id',$row->product_id)->get() as $rowstock){
				$totalstok += $rowstock->qty;
			} */
			
			$product[] = [
				'product_id'			=> $row->product_id,
				'product_name'			=> $row->product->name(),
				'qty'					=> $row->qty,
				'shading'				=> $row->shading,
				'unit'					=> $row->unit(),
				'unitraw'				=> $row->unit,
				'nominalstock'			=> 0,
				'qtydeduction'			=> $row->qty_deduction ? $row->qty_deduction  : 0,
			];
		}
		
		return response()->json([
			'data'				=> $data,
			'warehouse_name'	=> $data->warehouse->name.' - '.$data->warehouse->code,
			'product'			=> $product
		]);
	}
	
	public function updateStatusNote(Request $request){
		
		$note = ProjectNote::find($request->id)->update([
			'is_public'	=> $request->nilai
		]);
		
		activity()
			->performedOn(new ProjectNote())
			->causedBy(session('bo_id'))
			->withProperties($note)
			->log('Edit status project purchase note by '.session('bo_name'));
		
		$response = [
			'status'  => 200,
			'message' => 'Data added successfully.'
		];
		
		return response()->json($response);
	}
	
	public function getPreProjectNote(Request $request){
		$id = $request->id;
		
		$notes = [];
		
		$data = ProjectNote::where('notable_type','pre_purchase')->where('notable_id',$id)->get();
		$code = Project::find($id)->code;
		
		foreach($data as $row){
			$notes[] = [
				'id'					=> $row->id,
				'is_public'				=> $row->is_public,
				'created_at'			=> $row->created_at,
				'code'					=> $code,
				'note'					=> $row->note,
				'image'					=> $row->image ? (explode('.',$row->image)[1] == 'pdf' ? '<a href="' .$row->image() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->note.'" data-group="a" href="' .$row->image() . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>' ) : '',
			];
		}
		
		return response()->json($notes);
	}
	
	public function addPreProjectNote(Request $request){
		
		$pnp = ProjectNote::create([
			'user_id'				=> session('bo_id'),
			'notable_type'			=> $request->mode,
			'notable_id'			=> $request->id,
			'note'         			=> $request->note,
			'image'					=> $request->file('file') ? $request->file('file')->store('public/project') : ''
		]);
		
		$pn = ProjectNote::where('notable_type',$request->mode)->where('notable_id',$request->id)->get();
		$code = Project::find($request->id)->code;
		
		$data = [];
		
		foreach($pn as $row){
			$data[] = [
				'id'					=> $row->id,
				'created_at'			=> $row->created_at,
				'is_public'				=> $row->is_public,
				'code'					=> $code,
				'note'					=> $row->note,
				'image'					=> $row->image ? (explode('.',$row->image)[1] == 'pdf' ? '<a href="' .$row->image() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->note.'" data-group="a" href="' .$row->image() . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>' ) : '',
			];
		}
		
		activity()
			->performedOn(new ProjectNote())
			->causedBy(session('bo_id'))
			->withProperties($pnp)
			->log('Add note to pre project purchase');
		
		return response()->json($data);
	}
	
	public function addReturnProof(Request $request){
		
		$psr = ProjectSaleReturn::find($request->id);
		
		if($request->has('file')) {
			if(Storage::exists($psr->image)) {
				Storage::delete($psr->image);
			}

			$image = $request->file('file')->store('public/project');
			$return_date = $request->return_date;
			
			$psr->update([
				'image'			=> $image,
				'date_return'	=> $return_date
			]);
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Ups, please choose file.'
			];
		}
		
		activity()
			->performedOn(new ProjectSaleReturn())
			->causedBy(session('bo_id'))
			->withProperties($psr)
			->log('Update proof on Sales Return');
		
		return response()->json($response);
	}
	
	public function addNewCoa(Request $request)
	{
		$prefix = $request->prefix;
		
		$query = Coa::create([
			'code'			=> Coa::getNewCodeInventory(),
			'name'			=> 'WIP - '.$prefix,
			'parent_id'		=> 340,
			'status'		=> '1'
		]);
		
		$data = Coa::where('parent_id',340)->get();
		
		if($query){
			activity()
			->performedOn(new Coa())
			->causedBy(session('bo_id'))
			->withProperties($query)
			->log('Add new coa for inventory.');
		}
		
		return response()->json([
			'status' 	=> '200',
			'data'		=> $data
		]);
	}
	
	public function resetQuotation(Request $request){
		
		$project = Project::find($request->id);
		
		if(count($project->projectDelivery) > 0){
			return response()->json([
				'status' 	=> 	'400',
				'message'	=>	'Ups, this project either already has SO or Delivery. Please contact developer.'
			]);
		}
		
		$psr = ProjectQuotation::where('project_id',$request->id)->get();
		
		foreach($psr as $row){
			$row->projectQuotationProduct()->delete();
			$row->delete();
		}
			
		$response = [
			'status'  => 200,
			'message' => 'Data resetted successfully.'
		];
		
		activity()
			->performedOn(new ProjectQuotation())
			->causedBy(session('bo_id'))
			->log('Reset Project Quotation');
		
		return response()->json($response);
	}
	
	public function saveFromStock(Request $request){
		
		$validation = Validator::make($request->all(), [
			'sofr_id'  				=> 'required',
			'from_stock_memo'  		=> 'required',
			'product_id'			=> 'required|array',
			'product_unit'			=> 'required|array',
			'product_qty'			=> 'required|array'
		], [
			'sofr_id.required'    				=> 'Sales order cannot be empty.',
			'from_stock_memo.required'   		=> 'Date information cannot be empty.',
			'product_id.required'      			=> 'Product cannot empty.',
			'product_id.array'         			=> 'Product must be array.',
			'product_unit.required'     		=> 'Product unit cannot empty.',
			'product_unit.array'        		=> 'Product unit must be array.',
			'product_qty.required'   			=> 'Product qty cannot empty.',
			'product_qty.array'      			=> 'Product qty must be array.'
		]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$stokkurang = false;
			$isDiffBranch = false;

			$projectsale = ProjectSale::find($request->sofr_id);
			$branch = $projectsale->sales->branch;

			foreach($request->product_id as $key => $pi) {
				$stock = Stock::where('product_id',$pi)->where('branch', $branch)->sum('qty');

				if($stock < $request->product_qty[$key]){
					$stokkurang = true;
					$isDiffBranch = true;
				}
			}
			
			if($stokkurang == false || $isDiffBranch == false){
				
				
				$query = ProjectFromStock::create([
					'user_id'			=> session('bo_id'),
					'project_id'		=> $projectsale->project_id,
					'project_sale_id'	=> $projectsale->id,
					'note'				=> $request->from_stock_memo
				]);
				
				if($query){
					foreach($request->product_id as $key => $pi) {
						ProjectFromStockProduct::create([
							'project_from_stock_id'	=> $query->id,
							'product_id'			=> $pi,
							'qty'					=> $request->product_qty[$key],
							'unit'					=> $request->product_unit[$key]
						]);
					}
				}
				
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval,'project_from_stocks',$query->id,'approved_by',session('bo_id'));
				
				SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Purchase From Stock dengan Project No. '.$query->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				
				activity()
					->performedOn(new ProjectFromStock())
					->causedBy(session('bo_id'))
					->withProperties($query)
					->log('Add project return memo by '.session('bo_name'));
				
				$response = [
					'status'  => 200,
					'message' => 'Data added successfully.'
				]; 
			}else{
				$response = [
					'status'  => 300,
					'message' => 'Stock is not available or product chosen is from different branch.'
				];
			}
		}
		
		return response()->json($response);
	}
	
	public function deletePurchaseBill(Request $request)
	{
		$pp = ProjectPurchaseBill::find($request->id);
		
		$cek = PurchaseRequest::where('project_purchase_bill_id',$request->id)->get();
		
		if($cek){
			foreach($cek as $row){
				$row->deleteFile();
				$row->delete();
			}
		}
		
		$pp->deleteFile();
		
		$pp->delete();
		
		$project_id = $pp->project ? $pp->project->id : 0;
		
		$this->addProjectLog(session('bo_id'),$project_id,'project_purchase_bills',$pp->id,0,'delete','Delete purchase bill.');
		
		#start notif
		$role = array('1','2','3','4','5','6','7','9','10','11');
		$title = 'Project purchase bill with purchase order code ' . $pp->projectPurchase->code . ' has been deleted!';
		$description = 'Project purchase bill with purchase order code '.$pp->projectPurchase->code.' has been deleted by ' .session("bo_name"). ' with reason : Delete purchase bill.';
		$link = '#';
		Notification::sendNotif($role,$title,$description,$link);
		#end notif
		
		activity()
			->performedOn(new ProjectPurchaseBill())
			->causedBy(session('bo_id'))
			->withProperties($pp)
			->log('Delete project purchase bill');
			
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
		
	}
	
	public function deleteFromStock(Request $request)
	{
		$pfs = ProjectFromStock::find($request->id);
		
		if($pfs){
			$pfs->projectFromStockProduct()->delete();
			$pfs->delete();
		}
		
		$this->addProjectLog(session('bo_id'),$pfs->project_id,'project_from_stocks',$pfs->id,0,'delete','Delete purchase bill.');
		
		#start notif
		$role = array('1','2','3','4','5','6','7','9','10','11');
		$title = 'Project From Stock with sales order code ' . $pfs->projectSale->code . ' has been deleted!';
		$description = 'Project From Stock with sales order code '.$pfs->projectSale->code.' has been deleted by ' .session("bo_name"). ' with reason : Delete sales from stock.';
		$link = '#';
		Notification::sendNotif($role,$title,$description,$link);
		#end notif
		
		activity()
			->performedOn(new ProjectFromStock())
			->causedBy(session('bo_id'))
			->withProperties($pfs)
			->log('Delete project sales from stock');
			
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
		
	}
	
	public function getCustomerDeposit(Request $request){
		
		$date = $request->filter_date;
		$branch = $request->filter_branch;
		
		$arrresult = [];
		
		foreach(CashBank::whereHas('cashBankDetail',function($query) use($branch){ $query->where('coa_id',67)->where('branch',$branch); })->whereRaw("DATE(date) <= '$date'")->get() as $rowcb){
			foreach($rowcb->cashBankDetail->where('coa_id',67)->where('type','1') as $rowcbdetail){
				if($rowcb->lookable_type == 'project_pays' || $rowcb->lookable_type == 'project_deliveries' || $rowcb->lookable_type == 'project_sale_returns' || $rowcb->lookable_type == 'project_bills'){
					$ada = false;
					$index = -1;
					foreach($arrresult as $key => $row){
						if($row['customer_id'] == $rowcb->lookable->project->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$arrresult[] = [
							'customer_id'	=> $rowcb->lookable->project->customer_id,
							'customer_name'	=> $rowcb->lookable->project->customer->name,
							'total'			=> '-'.number_format(round($rowcbdetail->nominal),2,',','.'),
							'branch'		=> $rowcb->lookable->project->user->branch,
							'detail'		=> array(
								[
									'date'			=> $rowcb->date,
									'type'			=> 'd',
									'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
									'description'	=> $rowcb->lookable->project->code.' - '.$rowcb->description
								]
							)
						];
					}else{
						$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
						$arrresult[$index]['detail'][] = [
							'date'			=> $rowcb->date,
							'type'			=> 'd',
							'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
							'description'	=> $rowcb->lookable->project->code.' - '.$rowcb->description
						];
					}
				}
				
				if($rowcb->lookable_type == 'projects' || $rowcb->lookable_type == 'project_main_payments'){
					$ada = false;
					$index = -1;
					$addinfo = $rowcb->lookable->code;
					foreach($arrresult as $key => $row){
						if($row['customer_id'] == ($rowcb->customer_id && $rowcb->customer_id != 0 ? $rowcb->customer_id : $rowcb->lookable->customer_id)){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$arrresult[] = [
							'customer_id'	=> ($rowcb->customer_id && $rowcb->customer_id != 0 ? $rowcb->customer_id : $rowcb->lookable->customer_id),
							'customer_name'	=> $rowcb->lookable->customer->name,
							'total'			=> '-'.number_format(round($rowcbdetail->nominal),2,',','.'),
							'branch'		=> $rowcb->lookable_type == 'project_main_payments' ? $rowcb->lookable->projectPay()->first()->project->user->branch : $rowcb->lookable->user->branch,
							'detail'		=> array(
								[
									'date'			=> $rowcb->date,
									'type'			=> 'd',
									'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
									'description'	=> $addinfo.' - '.$rowcb->description
								]
							)
						];
					}else{
						$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
						$arrresult[$index]['detail'][] = [
							'date'			=> $rowcb->date,
							'type'			=> 'd',
							'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
							'description'	=> $addinfo.' - '.$rowcb->description
						];
					}
				}
				
				if($rowcb->lookable_type == 'purchase_request_payments'){
					$ada = false;
					$index = -1;
					foreach($arrresult as $key => $row){
						if($row['customer_id'] == $rowcb->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$arrresult[] = [
							'customer_id'	=> $rowcb->customer_id,
							'customer_name'	=> $rowcb->customer->name,
							'total'			=> '-'.number_format(round($rowcbdetail->nominal),2,',','.'),
							'branch'		=> $rowcbdetail->branch,
							'detail'		=> array(
								[
									'date'			=> $rowcb->date,
									'type'			=> 'd',
									'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
									'description'	=> $rowcb->description
								]
							)
						];
					}else{
						$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
						$arrresult[$index]['detail'][] = [
							'date'			=> $rowcb->date,
							'type'			=> 'd',
							'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
							'description'	=> $rowcb->description
						];
					}
				}
				
				if($rowcb->lookable_type == NULL){
					if($rowcb->customer_id){
						$ada = false;
						$index = -1;
						foreach($arrresult as $key => $row){
							if($row['customer_id'] == $rowcb->customer_id){
								$ada = true;
								$index = $key;
							}
						}
						if($ada == false){
							$arrresult[] = [
								'customer_id'	=> $rowcb->customer_id,
								'customer_name'	=> $rowcb->customer->name,
								'total'			=> '-'.number_format(round($rowcbdetail->nominal),2,',','.'),
								'branch'		=> $rowcbdetail->branch,
								'detail'		=> array(
									[
										'date'			=> $rowcb->date,
										'type'			=> 'd',
										'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
										'description'	=> $rowcb->description
									]
								)
							];
						}else{
							$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
							$arrresult[$index]['detail'][] = [
								'date'			=> $rowcb->date,
								'type'			=> 'd',
								'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
								'description'	=> $rowcb->description
							];
						}
					}else{
						$ada = false;
						$index = -1;
						foreach($arrresult as $key => $row){
							if($row['customer_id'] == 0 && $row['branch'] == $rowcbdetail->branch){
								$ada = true;
								$index = $key;
							}
						}
						if($ada == false){
							$arrresult[] = [
								'customer_id'	=> 0,
								'customer_name'	=> 'Unknown Customer',
								'total'			=> '-'.number_format(round($rowcbdetail->nominal),2,',','.'),
								'branch'		=> $rowcbdetail->branch,
								'detail'		=> array(
									[
										'date'			=> $rowcb->date,
										'type'			=> 'd',
										'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
										'description'	=> $rowcb->description
									]
								)
							];
						}else{
							$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
							$arrresult[$index]['detail'][] = [
								'date'			=> $rowcb->date,
								'type'			=> 'd',
								'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
								'description'	=> $rowcb->description
							];
						}
					}
				}
			}
			
			foreach($rowcb->cashBankDetail->where('coa_id',67)->where('type','2') as $rowcbdetail){
				if($rowcb->lookable_type == 'project_pays' || $rowcb->lookable_type == 'project_deliveries' || $rowcb->lookable_type == 'project_sale_returns' || $rowcb->lookable_type == 'project_bills'){
					$ada = false;
					$index = -1;
					foreach($arrresult as $key => $row){
						if($row['customer_id'] == $rowcb->lookable->project->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$arrresult[] = [
							'customer_id'	=> $rowcb->lookable->project->customer_id,
							'customer_name'	=> $rowcb->lookable->project->customer->name,
							'total'			=> number_format(round($rowcbdetail->nominal),2,',','.'),
							'branch'		=> $rowcb->lookable->project->user->branch,
							'detail'		=> array(
								[
									'date'			=> $rowcb->date,
									'type'			=> 'k',
									'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
									'description'	=> $rowcb->lookable->project->code.' - '.$rowcb->description
								]
							)
						];
					}else{
						$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) + $rowcbdetail->nominal,2,',','.');
						$arrresult[$index]['detail'][] = [
							'date'			=> $rowcb->date,
							'type'			=> 'k',
							'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
							'description'	=> $rowcb->lookable->project->code.' - '.$rowcb->description
						];
					}
				}
				
				if($rowcb->lookable_type == 'projects' || $rowcb->lookable_type == 'project_main_payments'){
					$ada = false;
					$index = -1;
					$addinfo = $rowcb->lookable->code;
					foreach($arrresult as $key => $row){
						if($row['customer_id'] == $rowcb->lookable->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$arrresult[] = [
							'customer_id'	=> $rowcb->lookable->customer_id,
							'customer_name'	=> $rowcb->lookable->customer->name,
							'total'			=> number_format(round($rowcbdetail->nominal),2,',','.'),
							'branch'		=> $rowcb->lookable_type == 'project_main_payments' ? $rowcb->lookable->projectPay()->first()->project->user->branch : $rowcb->lookable->user->branch,
							'detail'		=> array(
								[
									'date'			=> $rowcb->date,
									'type'			=> 'k',
									'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
									'description'	=> $addinfo.' - '.$rowcb->description
								]
							)
						];
					}else{
						$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) + $rowcbdetail->nominal,2,',','.');
						$arrresult[$index]['detail'][] = [
							'date'			=> $rowcb->date,
							'type'			=> 'k',
							'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
							'description'	=> $addinfo.' - '.$rowcb->description
						];
					}
				}
				
				if($rowcb->lookable_type == NULL){
					if($rowcb->customer_id){
						$ada = false;
						$index = -1;
						foreach($arrresult as $key => $row){
							if($row['customer_id'] == $rowcb->customer_id){
								$ada = true;
								$index = $key;
							}
						}
						if($ada == false){
							$arrresult[] = [
								'customer_id'	=> $rowcb->customer_id,
								'customer_name'	=> $rowcb->customer->name,
								'total'			=> number_format(round($rowcbdetail->nominal),2,',','.'),
								'branch'		=> $rowcbdetail->branch,
								'detail'		=> array(
									[
										'date'			=> $rowcb->date,
										'type'			=> 'k',
										'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
										'description'	=> $rowcb->description
									]
								)
							];
						}else{
							$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) + $rowcbdetail->nominal,2,',','.');
							$arrresult[$index]['detail'][] = [
								'date'			=> $rowcb->date,
								'type'			=> 'k',
								'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
								'description'	=> $rowcb->description
							];
						}
					}else{
						$ada = false;
						$index = -1;
						foreach($arrresult as $key => $row){
							if($row['customer_id'] == 0 && $row['branch'] == $rowcbdetail->branch){
								$ada = true;
								$index = $key;
							}
						}
						if($ada == false){
							$arrresult[] = [
								'customer_id'	=> 0,
								'customer_name'	=> 'Unknown Customer',
								'total'			=> number_format(round($rowcbdetail->nominal),2,',','.'),
								'branch'		=> $rowcbdetail->branch,
								'detail'		=> array(
									[
										'date'			=> $rowcb->date,
										'type'			=> 'k',
										'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
										'description'	=> $rowcb->description
									]
								)
							];
						}else{
							$arrresult[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$arrresult[$index]['total'])) + $rowcbdetail->nominal,2,',','.');
							$arrresult[$index]['detail'][] = [
								'date'			=> $rowcb->date,
								'type'			=> 'k',
								'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
								'description'	=> $rowcb->description
							];
						}
					}
				}
			}
		}
		
		$html = '<div class="table-responsive" style="font-size:20px !important;">
					<table class="table table-bordered" width="100%">
						<thead class="table-secondary">
							<tr class="text-center">
								<th>No.</th>
								<th>Customer</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>';
		
		$no = 1;
		
		$grandtotal = 0;
		
		foreach($arrresult as $key => $row){
			
			$collection = collect($row['detail'])->sortBy('date');
			
			if(str_replace(',','.',str_replace('.','',$row['total'])) > 0 || str_replace(',','.',str_replace('.','',$row['total'])) < 0){
				$html .= '
					<tr data-toggle="collapse" data-target="#collapse-button-'.$key.'" class="'.( $row['customer_id'] == 0 && $row['branch'] == '1' ? 'bg-primary' : ( $row['customer_id'] == 0 && $row['branch'] == '2' ? 'bg-info' : ($row["branch"] == '1' ? 'bg-success' : 'bg-danger')) ).'">
						<td class="text-center">'.$no.'.</td>
						<td class="text-center">'.$row['customer_name'].' - '.(SMB::branch($branch)).'</td>
						<td class="text-right">'.$row['total'].'</td>
					</tr>
				';
				
				$html .= '
					<tr class="collapse" id="collapse-button-'.$key.'">
						<td colspan="3">
							<div class="table-responsive" style="font-size:20px !important;">
								<table class="table-bordered" width="100%">
									<thead>
										<tr>
											<th>Date</th>
											<th>Description</th>
											<th>Debit</th>
											<th>Credit</th>
											<th>Balance</th>
										</tr>
									<thead>
									<tbody>
				';
				
				$balance = 0;
				
				foreach($collection as $detail){
					
					if($detail['type'] == 'k'){
						$balance += str_replace(',','.',str_replace('.','',$detail['nominal']));
					}else{
						$balance -= str_replace(',','.',str_replace('.','',$detail['nominal']));
					}
					
					$html .= '<tr class="">
						<td class="text-center">'.$detail['date'].'</td>
						<td class="text-center">'.$detail['description'].'</td>
						<td class="text-right">'.($detail['type'] == 'd' ? $detail['nominal'] : 0).'</td>
						<td class="text-right">'.($detail['type'] == 'k' ? $detail['nominal'] : 0).'</td>
						<td class="text-right">'.number_format($balance,2,',','.').'</td>
					</tr>';
				}
				
				$html .= '
									</tbody>
								</table>
							</div>
						</td>
					</tr>
				';
				
				$no++;
				
				$grandtotal += str_replace(',','.',str_replace('.','',$row['total']));
			}
			
		}
			
		$html .= '
					<tr>
						<td colspan="2" align="right">TOTAL</td>
						<td align="right">'.number_format($grandtotal,2,",",".").'</td>
					</tr>
				</tbody>
			</table>
		</div>
		';
		
		return response()->json([
			'content' 				=> $html
		]);
	}
	
	public function getCustomerDelivery(Request $request){
		
		$result = [];
		
		$data = ProjectDelivery::whereHas('project',function($query) use($request){
			$query->where('customer_id',$request->filter_customer);
		})->get();
		
		foreach($data as $row){
			$result[] = [
				'do_id'		=> $row->id,
				'do_code'	=> $row->code,
				'so_code'	=> $row->projectSale->code,
				'pj_code'	=> $row->project->code.' - '.$row->project->name,
				'nominal'	=> number_format($row->grandtotal_product + $row->grandtotal_service,2,',','.')
			];
		}
		
		return response()->json([
			'data' 				=> $result
		]);
	}
	
	function repairBalance(){
		$data = Project::whereHas('projectSale')->get();
		
		Customer::query()->update([
			'balance' => 0
		]);
		
		foreach($data as $project){
			
			$total = 0;
			
			$customer = $project->customer_id;
			
			foreach($project->projectBill as $bill){
				
				$total += $bill->balance();
				
			}
			
			foreach($project->projectDelivery as $delivery){
				$total -= $delivery->getTotal()['totaldelivery'];
			}
			
			if($total > 0){
				$update = Customer::find($customer)->increment('balance', round($total));
			}
		}
		
	}
	
	public function getSalesNote(Request $request){
		$id = $request->id;
		
		$notes = [];
		
		$data = ProjectNote::where('notable_type','project_sales')->where('notable_id',$id)->get();
		$code = ProjectSale::find($id)->code;
		
		foreach($data as $row){
			$notes[] = [
				'id'					=> $row->id,
				'is_public'				=> $row->is_public,
				'created_at'			=> $row->created_at,
				'code'					=> $code,
				'note'					=> $row->note,
				'image'					=> $row->image ? (explode('.',$row->image)[1] == 'pdf' ? '<a href="' .$row->image() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->note.'" data-group="a" href="' .$row->image() . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>' ) : '',
			];
		}
		
		return response()->json($notes);
	}
	
	public function getDeliveryNote(Request $request){
		$id = $request->id;
		
		$notes = [];
		
		$data = ProjectNote::where('notable_type','project_deliveries')->where('notable_id',$id)->get();
		$code = ProjectDelivery::find($id)->code;
		
		foreach($data as $row){
			$notes[] = [
				'id'					=> $row->id,
				'is_public'				=> $row->is_public,
				'created_at'			=> $row->created_at,
				'code'					=> $code,
				'note'					=> $row->note,
				'image'					=> $row->image ? (explode('.',$row->image)[1] == 'pdf' ? '<a href="' .$row->image() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->note.'" data-group="a" href="' .$row->image() . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>' ) : '',
			];
		}
		
		return response()->json($notes);
	}
	
	public function addSalesNote(Request $request){
		
		$pnp = ProjectNote::create([
			'user_id'				=> session('bo_id'),
			'notable_type'			=> $request->mode,
			'notable_id'			=> $request->id,
			'note'         			=> $request->note,
			'image'					=> $request->file('file') ? $request->file('file')->store('public/project') : ''
		]);
		
		$pn = ProjectNote::where('notable_type',$request->mode)->where('notable_id',$request->id)->get();
		if($request->mode == 'project_sales'){
			$code = ProjectSale::find($request->id)->code;
		}elseif($request->mode == 'project_deliveries'){
			$code = ProjectDelivery::find($request->id)->code;
		}
		
		$data = [];
		
		foreach($pn as $row){
			$data[] = [
				'id'					=> $row->id,
				'created_at'			=> $row->created_at,
				'code'					=> $code,
				'note'					=> $row->note,
				'image'					=> $row->image ? (explode('.',$row->image)[1] == 'pdf' ? '<a href="' .$row->image() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->note.'" data-group="a" href="' .$row->image() . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>' ) : '',
			];
		}
		
		activity()
			->performedOn(new ProjectNote())
			->causedBy(session('bo_id'))
			->withProperties($pnp)
			->log('Add note to project sale');
		
		return response()->json($data);
	}
	
	public function printSalesReport(Request $request){
		
		if(session('bo_branch') == '1'){
			$branch = '1';
		}elseif(session('bo_branch') == '2'){
			$branch = '2';
		}
		
		$dataquotation = Project::where('progress','>=',25)
			->whereHas('user', function($query) use($branch) {
				$query->where('branch',$branch);
			})
			->whereDoesntHave('projectSale')
			->whereHas('projectQuotation')
			->get();
			
		$data = Project::where('progress','>=',37)
			->whereHas('user', function($query) use($branch) {
				$query->where('branch',$branch);
			})
			->whereHas('projectSale')
			->get();
			
		/* $pdf = PDF::loadView('admin.pdf.project.sales_report', [
				'data'  			=> $data,
				'dataquotation'  	=> $dataquotation,
				'branch'			=> $branch
			],
			[],
			[ 
			  'format' => 'A3-P',
			  'orientation' => 'P'
			]
		);
		
		return $pdf->stream('TJS Sales Report.pdf'); */
		
		$data = [
			'data'  			=> $data,
			'dataquotation'  	=> $dataquotation,
		];

		return view('admin.pdf.project.sales_report', $data);
	}
	
	public function printSalesReport2(Request $request){
		return view('admin.report.project.sales_report_2');
	}
	
	public function printSalesReport3(Request $request){

		if($request->mode == '1'){
			if(session('bo_branch') == '1'){
				$branch = '1';
			}elseif(session('bo_branch') == '2'){
				$branch = '2';
			}
			
			$dataquotation = Project::where('progress','>=',25)
				->whereHas('user', function($query) use($branch) {
					$query->where('branch',$branch);
				})
				->whereDoesntHave('projectSale')
				->whereHas('projectQuotation')
				->get();
				
			$data = Project::where('progress','>=',37)
				->whereHas('user', function($query) use($branch) {
					$query->where('branch',$branch);
				})
				->whereHas('projectSale')
				->get();
				
			
			$data = [
				'data'  			=> $data,
				'dataquotation'  	=> $dataquotation,
				'mode' 				=> $request->mode
			];
		}else{
			$sales = $request->sales;

			$project = Project::where('progress','>=',37)
				->whereHas('user')
				->whereHas('projectSale', function($query) use($sales) {
					$query->where('sales_id', $sales);
				})
				->groupBy('customer_id')
				->get();

			$data = [
				'data' => $project,
				'mode' => $request->mode
			];
		}
		
		
		return view('admin.report.project.sales_report_3', $data);
	}

	public function addTaxDocument(Request $request){
		
		$query = ProjectTaxDocument::create([
			'user_id'		=> session('bo_id'),
			'lookable_type'	=> $request->type,
			'lookable_id'	=> $request->id,
			'image'			=> $request->file('file') ? $request->file('file')->store('public/document') : '',
			'date'			=> $request->date,
			'no'			=> $request->no,
			'nominal'		=> str_replace(',','.',str_replace('.','',$request->nominal))
		]);
		
		$data = ProjectTaxDocument::where('lookable_type',$request->type)->where('lookable_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$result[] = [
				'id'		=> $row->id,
				'image'		=> $row->attachment(),
				'name'		=> '',
				'extension'	=> $row->image ? explode('.',$row->image)[1] : asset('website/empty.jpg'),
				'date'		=> $row->date ? $row->date : '',
				'nominal'	=> $row->nominal ? number_format($row->nominal,2,',','.') : '',
				'no'		=> $row->no ? $row->no : ''
			];
		}
		
		return response()->json([
			'status'		=> 200,
			'message'		=> 'You have successfully upload the file.',
			'data'			=> $result
		]);
	}
	
	public function updateTaxDocument(Request $request){
		
		$ptd = ProjectTaxDocument::find($request->id);

		if($request->has('file')) {
			if(Storage::exists($ptd->image)) {
				Storage::delete($ptd->image);
			}

			$image = $request->file('file')->store('public/document');
		} else {
			$image = $ptd->image;
		}

		$ptd->update([
			'user_id'		=> session('bo_id'),
			'date'			=> $request->date,
			'no'			=> $request->no,
			'image'			=> $image,
			'nominal'		=> str_replace(',','.',str_replace('.','',$request->nominal))
		]);
		
		
		return response()->json([
			'status'		=> 200,
			'message'		=> 'You have successfully change the data.'
		]);
	}
	
	public function getTaxDocument(Request $request){
		
		$data = ProjectTaxDocument::where('lookable_type',$request->type)->where('lookable_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$result[] = [
				'id'		=> $row->id,
				'image'		=> $row->attachment(),
				'name'		=> '',
				'extension'	=> $row->image ? explode('.',$row->image)[1] : asset('website/empty.jpg'),
				'date'		=> $row->date ? $row->date : '',
				'nominal'	=> $row->nominal ? number_format($row->nominal,2,',','.') : '',
				'no'		=> $row->no ? $row->no : ''
			];
		}
		
		return response()->json($result);
	}
	
	public function deleteTaxDocument(Request $request){
		$data = ProjectTaxDocument::find($request->id);
		
		$data->deleteFile();
		
		$data->delete();
		
		if($data){
			return response()->json([
				'status'	=> 200,
				'message'	=> 'Data successfully deleted.' 
			]);
		}else{
			return response()->json([
				'status'	=> 422,
				'message'	=> 'Data not found.'
			]);
		}
	}
	
	public function getPurchaseProforma(Request $request){
		$data = ProjectProforma::find($request->id);
		
		return response()->json($data);
	}
	
	public function deleteDelivery(Request $request)
	{
		$pd = ProjectDelivery::find($request->id);
		
		if($pd->received_date){
			return response()->json([
				'status'  => 400,
				'message' => 'Sorry, This delivery already received by customer.'
			]);
		}else{
			
			$cb = CashBank::where('lookable_type','project_deliveries')->where('lookable_id',$pd->id)->get();
			
			foreach($cb as $c){
				$c->deleteDetail();
				$c->delete();
			}
			
			/* if($pd->projectSale->sales->branch == '2'){
				$pr = PurchaseRequest::where('link_type','project_deliveries')->where('link_id',$pd->id)->get();
				foreach($pr as $rowpr){
					$rowpr->delete();
				}
			} */
			
			$pd->deleteFile();
			$pd->projectDeliveryProduct()->delete();
			$pd->projectDeliveryTrack()->delete();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project delivery order ' . $pd->code . ' has been deleted!';
			$description = 'Project delivery order '.$pd->code.' has been deleted by ' .session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new ProjectDelivery())
				->causedBy(session('bo_id'))
				->withProperties($pd)
				->log('Delete project delivery');
			
			$pd->delete();
				
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
			
		}
	}
	
	public function addMultiNotes(Request $request)
	{
		$validation = Validator::make($request->all(), [
			'detail_id'  	=> 'required|array',
			'detail_notes'  => 'required|array',
		], [
			'detail_id.required'     	=> 'Id cannot be empty.',
			'detail_id.array'        	=> 'Id must be array.',
			'detail_notes.required'     => 'Note cannot be empty.',
			'detail_notes.array'        => 'Note must be array.',
		]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			foreach($request->detail_id as $key => $row){
				if($request->detail_notes[$key]){
					$pnp = ProjectNote::create([
						'user_id'				=> session('bo_id'),
						'notable_type'			=> 'project_deliveries',
						'notable_id'			=> $row,
						'note'         			=> $request->detail_notes[$key]
					]);
				}
			}
			
			activity()
				->performedOn(new ProjectNote())
				->causedBy(session('bo_id'))
				->log('Add multi delivery note by '.session('bo_name'));
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
		}
		
		return response()->json($response);
	}
	
	public function getSalesOrder(Request $request){
		$projectid = $request->id;
		$arrsales = [];
		
		foreach(Project::find($projectid)->projectSale as $row){
			$arrsales[] = [
				'id'			=> $row->id,
				'code'			=> $row->code,
				'sales'			=> $row->sales->name,
				'customer'		=> $row->project->customer->name,
				'nominal'		=> number_format($row->grandtotal_product + $row->grandtotal_service,2,',','.'),
				'is_closed'		=> $row->is_closed ? $row->is_closed : '0',
				'reason_closed'	=> $row->reason_closed,
				'approved'		=> $row->approveClose()->exists() ? $row->approveClose->name : 'Waiting'
			];
		}
		
		return response()->json($arrsales);
	}
	
	public function closeSalesOrder(Request $request){
		$idsales = $request->id;
		$note = $request->note;
		
		$query = ProjectSale::find($idsales)->update([
			'is_closed'		=> '1',
			'date_closed'	=> date('Y-m-d'),
			'reason_closed'	=> $note,
		]);
		
		$projectsale = ProjectSale::find($idsales);
		
		$roleapproval = array('4');
		Approval::sendApproval($roleapproval,'project_sales',$idsales,'approved_closed',session('bo_id'));
		
		SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Penutupan Sales Project No '.$projectsale->code.' Project No. '.$projectsale->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
		
		#start notif
		$role = array('1','2','3','4','5','6','7','9','10','11');
		$title = 'Project Sales Order ' . $projectsale->code . ' has been closed!';
		$description = 'Project Sales Order '.$projectsale->code.' has been closed by ' .session("bo_name");
		$link = '#';
		Notification::sendNotif($role,$title,$description,$link);
		#end notif
		
		activity()
			->performedOn(new ProjectSale())
			->causedBy(session('bo_id'))
			->withProperties($query)
			->log('Delete project delivery');
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data closed successfully.'
		]);
	}
	
	public function getReportTax(Request $request)
	{
		
		$date_from = $request->date_from;
		$date_to = $request->date_to;
		$mode = $request->mode;
		
		$html = '';
		
		if($mode == 'tax_out'){
			$html = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>SO Number</th>
							<th>PJ Number</th>
							<th>Customer</th>
							<th>Date</th>
							<th>Tax No.</th>
							<th>Nominal</th>
						</tr>
					</thead>
					<tbody>';
			$total = 0;
			foreach(ProjectTaxDocument::where('lookable_type','project_sales')->whereRaw("date BETWEEN '$date_from' AND '$date_to'")->get() as $key => $row){
				$html .= '<tr>
						<td class="text-center">'.($key + 1).'</td>
						<td class="text-center">'.$row->lookable->code.'</td>
						<td class="text-center">'.$row->lookable->project->code.'</td>
						<td class="text-center">'.$row->lookable->project->customer->name.'</td>
						<td class="text-center">'.date("d M Y",strtotime($row->date)).'</td>
						<td class="text-center">'.$row->no.'</td>
						<td class="text-right">'.number_format($row->nominal,2,',','.').'</td>
					</tr>
				';
				
				$total += $row->nominal;
			}
					
			$html .= '<tr>
					<td class="text-right" colspan="6"><h3>TOTAL</h3></td>
					<td class="text-right"><h3>'.number_format($total,2,',','.').'</h3></td>
			</tr></tbody></table>';
		}elseif($mode == 'tax_in'){
			$html = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>PO Number</th>
							<th>Supplier</th>
							<th>Date</th>
							<th>Tax No.</th>
							<th>Nominal</th>
						</tr>
					</thead>
					<tbody>';
			$total = 0;
			foreach(ProjectTaxDocument::where('lookable_type','project_purchases')->whereRaw("date BETWEEN '$date_from' AND '$date_to'")->get() as $key => $row){
				$html .= '<tr>
						<td class="text-center">'.($key + 1).'</td>
						<td class="text-center">'.$row->lookable->code.'</td>
						<td class="text-center">'.$row->lookable->supplier->name.'</td>
						<td class="text-center">'.date("d M Y",strtotime($row->date)).'</td>
						<td class="text-center">'.$row->no.'</td>
						<td class="text-right">'.number_format($row->nominal,2,',','.').'</td>
					</tr>
				';
				$total += $row->nominal;
			}
					
			$html .= '<tr>
					<td class="text-right" colspan="5"><h3>TOTAL</h3></td>
					<td class="text-right"><h3>'.number_format($total,2,',','.').'</h3></td>
			</tr></tbody></table>';
		}
					
		return response()->json([
			'content' 			=> $html,
		]);
	}

	public function getSalesRetur(Request $request){
		
		$result = [];
		
		$data = ProjectSaleReturn::find($request->id);
		
		$result = [
			'project_sale_id'		 => $data->project_sale_id,
			'project_return_memo_id' => $data->project_return_memo_id,
			'warehouse_id'			 => $data->warehouse_id,
			'date_return'			 => $data->date_return,
			'type'			 		 => $data->type,
			'address'			 	 => $data->address,
			'note'			 	     => $data->note,
			'warehouse_name'		 => $data->warehouse->code.' - '.$data->warehouse->name
		];

		$detail = [];
		
		foreach($data->projectSaleReturnProduct as $row){
			$detail[] = [
				'product_id'   => $row->product_id,
				'product_name' => $row->product->name(),
				'qty'  		   => $row->qty,
				'unit'		   => $row->unit,
			];
		}
		
		return response()->json([
			'main'		=> $result,
			'detail'	=> $detail
		]);
	}

	public function createBudgetingProject(Request $request){

	}
}
