<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Coa;
use App\Models\Approval;
use App\Models\Project;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Journal;
use App\Models\ProjectLog;
use App\Models\ProjectPay;
use App\Models\ProjectPurchase;
use App\Models\PurchaseRequest;
use App\Models\PurchaseCost;
use App\Models\PurchaseCostDetail;
use App\Models\ProjectPurchaseBill;
use App\Models\ProjectNote;
use App\Models\ProjectSale;
use App\Models\ProjectShipment;
use App\Models\ProjectShipmentProduct;
use App\Models\ProjectWarehouse;
use App\Models\ProjectWarehouseProduct;
use App\Models\ProjectDelivery;
use App\Models\ProjectDeliveryProduct;
use App\Models\ProjectSaleReturn;
use App\Models\ProjectSaleReturnProduct;
use App\Models\ProjectPurchaseReturnProduct;
use App\Models\ProjectProforma;
use App\Models\ProjectProduction;
use App\Models\Notification;
use App\Models\ProjectPurchaseProduct;
use App\Models\BalanceHistory;
use App\Models\PurchaseRequestPayment;
use App\Models\Stock;
use App\Models\Transfer;
use App\Models\TransferProduct;
use App\Models\ProductCogs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Helper\SendMessage;
use Carbon\Carbon;
use App\Helper\CheckCutOff;
use App\Models\ProjectPurchaseQuotation;
use App\Models\ProjectPurchaseQuotationProduct;
use App\Models\ProjectPurchaseReturn;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isNull;

class PurchaseController extends Controller {

    public function index(Request $request)
    {

        $data = [
            'title'   		=> 'Purchase Inventory',
			'coa_id' 		=> Coa::where('status', 1)->where('parent_id',340)->get(),
            'content' 		=> 'admin.inventory.purchase'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
			'detail',
			'customer_id',
            'id',
            'code',
            'so_code',
            'supplier_id',
            'checked_by',
			'approved_by',
			'created_at',
            'image',
            'action'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ProjectPurchase::count();
        
        $query_data = ProjectPurchase::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
						->orWhere('created_at','like', "%$search%")
						->orWhereHas('projectSale', function($query) use ($search) {
							$query->where('code', 'like', "%$search%");
						})
						->orWhereHas('project', function($query) use ($search) {
							$query->where('code', 'like', "%$search%");
						})
						->orWhereHas('projectPurchaseProduct', function($query) use ($search) {
							$query->whereHas('product', function($query) use ($search) {
								$query->whereHas('type', function($query) use ($search) {
									$query->where('code', 'like', "%$search%");
								});
							});
						})
						->orWhereHas('projectWarehouse', function($query) use ($search) {
							$query->where('code', 'like', "%$search%");
			
						})
						->orWhereHas('supplier', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						})
						->orWhereHas('customer', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						});
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy('id', 'DESC')
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectPurchase::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
						->orWhere('created_at','like', "%$search%")
						->orWhereHas('projectSale', function($query) use ($search) {
							$query->where('code', 'like', "%$search%");
						})
						->orWhereHas('project', function($query) use ($search) {
							$query->where('code', 'like', "%$search%");
						})
						->orWhereHas('projectPurchaseProduct', function($query) use ($search) {
							$query->whereHas('product', function($query) use ($search) {
								$query->whereHas('type', function($query) use ($search) {
									$query->where('code', 'like', "%$search%");
								});
							});
						})
						->orWhereHas('projectWarehouse', function($query) use ($search) {
									$query->where('code', 'like', "%$search%");
					
						})
						->orWhereHas('supplier', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						})
						->orWhereHas('customer', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						});
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$colorProforma = 'bg-primary';
				
				if(count($val->projectProforma)){
					$colorProforma = 'bg-success';
				}
				
				$colorBill = 'bg-primary';
				
				if(count($val->projectPurchaseBill)){
					$colorBill = 'bg-success';
				}
				
				$colorProduction = 'bg-primary';
				
				if(count($val->projectPurchaseProduction)){
					$colorProduction = 'bg-success';
				}
				
				$colorDelivery = 'bg-primary';
				
				if(count($val->projectShipment)){
					$colorDelivery = 'bg-success';
				}
				
				$colorWarehouse = 'bg-primary';
				
				if(count($val->projectWarehouse)){
					$colorWarehouse = 'bg-success';
				}
				
				$colorCost = 'bg-primary';
				
				if($val->purchaseCost()->exists()){
					$colorCost = 'bg-success';
				}
				
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $nomor,
                    $val->code,
                    isset($val->projectSale->code) ? $val->projectSale->code : '<span class="badge badge-danger">For Stock</span>',
                    isset($val->customer->name) ? $val->customer->name : '<span class="badge badge-danger">For Stock</span>',
                    $val->supplier->name,
					isset($val->checked->name) ? '<button type="button" class="btn bg-success-400 btn-icon rounded-round btn-sm"><i class="icon-checkmark4"></i></button>' : '<button type="button" class="btn bg-warning-400 btn-icon rounded-round btn-sm"><i class="icon-hour-glass2"></i></button>',
					isset($val->approved->name) ? '<button type="button" class="btn bg-success-400 btn-icon rounded-round btn-sm"><i class="icon-checkmark4"></i></button>' : '<button type="button" class="btn bg-warning-400 btn-icon rounded-round btn-sm"><i class="icon-hour-glass2"></i></button>',
					date('d M Y',strtotime($val->created_at)),
                    '
						<a onclick="openLink(`'.url('admin/purchase_order/project/print/purchase_order/'.base64_encode($val->id)).'`)" href="javascript:void(0);" class="btn bg-info btn-sm"><i class="icon-file-pdf"></i></a>
					',
                    '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    ',
					'<button type="button" class="btn '.$colorCost.' btn-sm" data-popup="tooltip" title="Update Additional Cost" onclick="updateCost('.$val->id.',`'.$val->code.'`,`'.$val->supplier->name.'`)"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.($val->purchaseCost ? '1' : '0').'</span><i class="icon-calculator"></i></button>',
					'<button type="button" class="btn '.$colorProforma.' btn-sm" data-popup="tooltip" title="Update Proforma" onclick="updateProforma('.$val->id.',`'.$val->code.'`,`'.$val->supplier->name.'`)"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->projectProforma).'</span><i class="icon-file-text3"></i></button>',
					'<button type="button" class="btn '.$colorBill.' btn-sm" data-popup="tooltip" title="Update Payment / Request Bill" onclick="updatePayment(' . $val->id . ',`'.$val->code.'`,`'.$val->supplier->name.'`)"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->projectPurchaseBill).'</span><i class="icon-cash2"></i></button>',
					'<button type="button" class="btn '.$colorProduction.' btn-sm" data-popup="tooltip" title="Update Production" onclick="updateProduction(' . $val->id . ',`'.$val->code.'`,`'.$val->supplier->name.'`)"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->projectPurchaseProduction).'</span><i class="icon-spinner9"></i></button>',
					'<button type="button" class="btn '.$colorDelivery.' btn-sm" data-popup="tooltip" title="Update Delivery" onclick="updateDelivery(' . $val->id . ',`'.$val->code.'`,`'.$val->supplier->name.'`)"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->projectShipment).'</span><i class="icon-boat"></i></button>',
					'<button type="button" class="btn '.$colorWarehouse.' btn-sm" data-popup="tooltip" title="Update Warehouse Receive" onclick="updateWarehouseReceive(' . $val->id . ',`'.$val->code.'`,`'.$val->supplier->name.'`)"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->projectWarehouse).'</span><i class="icon-home7"></i></button>',
					'<button type="button" class="btn btn-danger btn-sm" data-popup="tooltip" title="Return Purchase Order" onclick="updateReturnPurchase(' . $val->id . ',`'.$val->code.'`);getWarehouseReceive('.$val->id.');"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->projectPurchaseReturn).'</span><i class="icon-esc"></i></button>',
					'<a href="javascript:void(0);" onclick="addTaxDocument('.$val->id.')" class="btn bg-secondary btn-sm">
						<i class="icon-file-spreadsheet"></i>
						<span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.$val->countTaxDocument().'</span>
					</a>'
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
    
    	public function datatableQuotation(Request $request) 
    {
        $column = [
			'detail',
            'id',
            'code',
            'so_code',
            'supplier_id',
            'checked_by',
			'approved_by',
			'created_at',
            'action'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ProjectPurchaseQuotation::count();
        
        $query_data = ProjectPurchaseQuotation::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
						->orWhere('created_at','like', "%$search%")
						->orWhereHas('project', function($query) use ($search) {
							$query->where('code', 'like', "%$search%");
						})
						->orWhereHas('projectPurchaseQuotationProduct', function($query) use ($search) {
							$query->whereHas('product', function($query) use ($search) {
								$query->whereHas('type', function($query) use ($search) {
									$query->where('code', 'like', "%$search%");
								});
							});
						})
						->orWhereHas('supplier', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						});
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectPurchaseQuotation::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
						->orWhere('created_at','like', "%$search%")
						->orWhereHas('project', function($query) use ($search) {
							$query->where('code', 'like', "%$search%");
						})
						->orWhereHas('projectPurchaseQuotationProduct', function($query) use ($search) {
							$query->whereHas('product', function($query) use ($search) {
								$query->whereHas('type', function($query) use ($search) {
									$query->where('code', 'like', "%$search%");
								});
							});
						})
						->orWhereHas('supplier', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						});
                    });
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
                    $val->code,
                    isset($val->project->code) ? $val->project->code : '<span class="badge badge-danger">Non Project</span>',
                    $val->supplier->name,
					isset($val->approved->name) ? '<button type="button" class="btn bg-success-400 btn-icon rounded-round btn-sm"><i class="icon-checkmark4"></i></button>' : '<button type="button" class="btn bg-warning-400 btn-icon rounded-round btn-sm"><i class="icon-hour-glass2"></i></button>',
					date('d M Y',strtotime($val->created_at)),
                    '
					<a onclick="openLink(`'.url('admin/request_quotation/project/print/request_quotation/'.base64_encode($val->id)).'`)" href="javascript:void(0);" class="btn bg-info btn-sm"><i class="icon-file-pdf"></i></a>
					',
                    '
					<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="showQuotation(' . $val->id . ')"><i class="icon-pencil7"></i></button>
					<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyQuotation(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    '
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
        $data   = ProjectPurchase::find($request->id);
        $string = '<div class="row" style="background-color:#80ffff;">';
	
		$ppnpembagi = 1;
		
		if($data->ppn == '1'){
			if(date('Y-m-d',strtotime($data->created_at)) < '2022-04-01'){
				$ppnpembagi = 1.1;
			}else{
				$ppnpembagi = 1.11;
			}
		}

		$string .= '
			<div class="col-md-12 p-3">
				<h3>List of All Products</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
								<th style="color:white;"><center>NO</center></th>
								<th style="color:white;"><center>CODE</center></th>
								<th style="color:white;"><center>SHADE</center></th>
								<th style="color:white;"><center>TOTAL (box)</center></th>
								<th style="color:white;"><center>TOTAL (sqm)</center></th>
								<th style="color:white;"><center>PRICE/M<sup>2</sup></center> (Before Tax)</th>
								<th style="color:white;"><center>TOTAL</center></th>
							</tr>
						 </thead>
						 <tbody>';
		$no = 1;
		foreach($data->projectPurchaseProduct as $pp){
			
			if($pp->unit == '2' || $pp->unit == '3'){
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				
				$string .= '<tr class="text-center">
						<td class="align-middle">'.$no.'.</td>
						<td class="align-middle">'.$pp->product->type->code.'</a></td>
						<td class="align-middle">'.$pp->remark.'</td>
						<td class="align-middle">'.number_format($pp->qty,0,',','.').'</td>
						<td class="align-middle">'.number_format($pp->qty*$m2,2,',','.').'</td>
						<td class="align-middle">'.number_format( $data->ppn == '1' ? $pp->price / $ppnpembagi : $pp->price, 2, ',', '.').'</td>
						<td class="align-middle">'.number_format($data->ppn == '1' ? ($pp->price / $ppnpembagi) * $pp->qty * $m2 : $pp->price * $pp->qty * $m2, 0, ',', '.').'</td>
				   </tr>';
			}elseif($pp->unit == '1' || $pp->unit == '4'){
				$string .= '<tr class="text-center">
						<td class="align-middle">'.$no.'.</td>
						<td class="align-middle">'.$pp->product->type->code.'</a></td>
						<td class="align-middle">'.$pp->remark.'</td>
						<td class="align-middle">'.number_format($pp->qty,0,',','.').'</td>
						<td class="align-middle">'.number_format($pp->qty,0,',','.').'</td>
						<td class="align-middle">'.number_format($data->ppn == '1' ? round(($pp->price / $ppnpembagi)) : $pp->price, 2, ',', '.').'</td>
						<td class="align-middle">'.number_format($data->ppn == '1' ? round(($pp->price / $ppnpembagi)) * $pp->qty : $pp->price * $pp->qty, 2, ',', '.').'</td>
				   </tr>';
			}
			   
			$no++;
		}
						 
		$string .='</tbody>
					  </table>
				</div>
			</div>
		';

		$string .= '
			<div class="col-md-12 p-3">
				<h3>Proof of Proforma</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>PO Code</th>
							   <th>SO No.</th>
							   <th>Date</th>
							   <th>Supplier</th>
							   <th>Warehouse</th>
							   <th>Proof</th>
							</tr>
						 </thead>
						 <tbody>';
		
		foreach($data->projectProforma as $pp){
			$string .= '<tr class="text-center">
				  <td class="align-middle">'.($pp->projectPurchase ? $pp->projectPurchase->code : '').'</a></td>
				  <td class="align-middle">'.($pp->projectPurchase->projectSale ? $pp->projectPurchase->projectSale->code : '').'</td>
				  <td class="align-middle">'.date('d M Y',strtotime($pp->date)).'</td>
				  <td class="align-middle">'.$pp->supplier_name.'</td>
				  <td class="align-middle">'.$pp->supplier_warehouse.'</td>
				  <td class="align-middle">
					<a href="'.$pp->attachment().'" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
				  </td>
			   </tr>';
		}
						 
		$string .='</tbody>
					  </table>
				</div>
			</div>
		';
		
		$string .= '
			<div class="col-md-12 p-3">
				<h3>Progress Production</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>PO No.</th>
							   <th>SO No.</th>
							   <th>Start</th>
							   <th>Finish</th>
							   <th>Note</th>
							   <th>Progress</th>
							   <th>Proof</th>
							</tr>
						 </thead>
						 <tbody>';
		$total = 0;
		$temppo = 0;
		foreach($data->projectPurchaseProduction()->orderBy('project_purchase_id')->get() as $pp){
			if($temppo == 0 || $temppo == $pp->project_purchase_id){
				$total += $pp->progress;
			}else{
				$total = $pp->progress;
			}
			$temppo = $pp->project_purchase_id;
			$string .= '<tr class="text-center">
			  <td class="align-middle">
				'.($pp->projectPurchase ? $pp->projectPurchase->code : '').'
			  </div>
			  <td class="align-middle">
				'.($pp->projectPurchase->projectSale ? $pp->projectPurchase->projectSale->code : '').'
			  </div>
			  <td class="align-middle">
				'.date('d M Y',strtotime($pp->start_date)).'
			  </div>
			  <td class="align-middle">
				'.date('d M Y',strtotime($pp->finish_date)).'
			  </div>
			  <td class="align-middle">
				'.$pp->note.'
			  </div>
			  <td class="align-middle">
				'.$total.'%
			  </td>
			  <td class="align-middle">
				 <a href="'.$pp->attachment().'" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
			  </td>
		   </tr>';
		}
						 
		$string .='</tbody>
					  </table>
				</div>
			</div>
		';
		
		$string .= '
			<div class="col-md-12 p-3">
				<h3>Purchase Bill</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>PO</th>
							   <th>Document Number</th>
							   <th>Date</th>
							   <th>Due Date</th>
							   <th>Method</th>
							   <th>Nominal</th>
							   <th>Note</th>
							   <th>Proof</th>
							   <th>Delete</th>
							</tr>
						 </thead>
						 <tbody>';
				
		foreach($data->projectPurchaseBill as $rowdetail){
				$string .= '<tr class="text-center">
								<td>'.$data->code.'</td>
								<td>'.$rowdetail->no_document.'</td>
								<td>'.$rowdetail->date.'</td>
								<td>'.$rowdetail->due_date.'</td>
								<td>'.$rowdetail->method().'</td>
								<td>'.number_format($rowdetail->nominal,0,',','.').'</td>
								<td>'.$rowdetail->note.'</td>
								<td>'.($rowdetail->image ? '<a href="' . $rowdetail->attachment() . '" target="_blank" class="btn btn-info"><i class="icon-search4"></i></a>' : '<span class="badge badge-secondary">None</span>').'</td>
								<td>
									<a href="javascript:void(0);" onclick="deletePurchaseBill('.$rowdetail->id.')" class="btn bg-danger"><i class="icon-trash"></i></a>
								</td>
							</tr>';
		}
	
		$string .='</tbody>
					  </table>
				</div>
			</div>
		';				 
		
		
		$string .= '
			<div class="col-md-12 p-3">
				<h3>Purchase Payment</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>PO Code</th>
							   <th>SO No.</th>
							   <th>Date</th>
							   <th>Bank</th>
							   <th>Nominal</th>
							   <th>Checked By</th>
							   <th>Proof</th>
							</tr>
						 </thead>
						 <tbody>';
		$totalpayment = 0;
		foreach($data->projectPurchasePayment()->orderBy("project_purchase_id")->get() as $pp){
			$string .= '<tr class="text-center">
			  <td class="align-middle">'.($pp->projectPurchase ? $pp->projectPurchase->code : '').'</td>
			  <td class="align-middle">'.($pp->projectPurchase->projectSale ? $pp->projectPurchase->projectSale->code : '').'</td>
			  <td class="align-middle">'.date('d M Y',strtotime($pp->date)).'</td>
			  <td class="align-middle">'.$pp->coa->name.'</td>
			  <td class="align-middle text-right">'.$pp->projectPurchase->currency->symbol.' '.number_format($pp->nominal,2,',','.').'</td>
			  <td class="align-middle">';
				
			if(isset($pp->check->name)){
				$string .= $pp->check->name;
			}else{
				$string .= '<span class="badge badge-danger d-block">Waiting</span>';
			}
			
			$string .='
			  </td>
			  <td class="align-middle">
				<a href="'.$pp->attachment().'" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
			  </td>
		   </tr>';
		   
			$totalpayment += $pp->nominal;
		}
						 
		$string .='
						<tr>
							<td colspan="4" class="text-right">Total</td>
							<td class="text-right">'.number_format($totalpayment,2,',','.').'</td>
							<td colspan="2"></td>
						</tr>
					</tbody>
					  </table>
				</div>
			</div>
		';
		
		$string .= '
			<div class="col-md-12 p-3">
				<h3>Delivery Document Number</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
					 <thead class="table-secondary">
						<tr class="text-center">
						   <th>PO Code</th>
						   <th>Shipment Code</th>
						   <th>Loading</th>
						   <th>Departure</th>
						   <th>From Port</th>
						   <th>To Port</th>
						   <th>ETA</th>
						   <th>Note</th>
						   <th>Proof</th>
						</tr>
					 </thead>
					 <tbody>';
						 
		foreach($data->projectShipment()->orderBy('project_purchase_id')->get() as $ps){
			$string .= '<tr class="text-center">
			  <td class="align-middle">'.$ps->projectPurchase->code.'</td>
			  <td class="align-middle">'.$ps->shipment_code.'</td>
			  <td class="align-middle">'.date('d M Y',strtotime($ps->loading_date)).'</td>
			  <td class="align-middle">'.date('d M Y',strtotime($ps->departure_date)).'</td>
			  <td class="align-middle">'.$ps->from_port.'</td>
			  <td class="align-middle">'.$ps->to_port.'</td>
			  <td class="align-middle">'.$ps->eta.'</td>
			  <td class="align-middle">'.$ps->note.'</td>
			  <td class="align-middle"><a href="'.$ps->attachment().'" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a></td>
			</tr>';
		}
						 
		$string .='</tbody>
					  </table>
				</div>
			</div>
		';
		
		$string .= '
			<div class="col-md-12 p-3">
				<h3>Warehouse Receive</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
							   <th>PO Code</th>
							   <th>Shipment</th>
							   <th>Code</th>
							   <th>Warehouse</th>
							   <th>Person</th>
							   <th>Date & Time Received</th>
							   <th>Proof</th>
							   <th><i class="icon-printer2"></i></th>
							</tr>
						 </thead>
						 <tbody>';
						 
		foreach($data->projectWarehouse()->orderBy('project_purchase_id')->get() as $pw){
			$string .= '<tr class="text-center">
				  <td class="align-middle">'.$pw->projectPurchase->code.'</td>
				  <td class="align-middle">'.$pw->projectShipment->shipment_code.'</td>
				  <td class="align-middle">'.$pw->code.'</td>
				  <td class="align-middle">'.$pw->warehouse->name.' - '.$pw->warehouse->code.'</td>
				  <td class="align-middle">'.$pw->person.'</td>
				  <td class="align-middle">'.date('d M Y',strtotime($pw->date_receive)).'</td>
				  <td class="align-middle">
					<a href="'.$pw->attachment().'" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>
				  </td>
				  <td class="align-middle">
					<a onclick="openLink(`'.url('admin/purchase_order/project/print/warehouse_receive/' . base64_encode($pw->id)).'`)" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>
				  </td>
			   </tr>';
		}
						 
		$string .='</tbody>
					  </table>
				</div>
			</div>
		';

        $string .= '</div>';
		
        return response()->json($string);
    }
    
    	public function rowDetailQuotation(Request $request)
    {
        $data   = ProjectPurchaseQuotation::find($request->id);
        $string = '<div class="row" style="background-color:#80ffff;">';

		$string .= '
			<div class="col-md-12 p-3">
				<h3>List of All Products</h3>
				<hr>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						 <thead class="table-secondary">
							<tr class="text-center">
								<th style="color:white;"><center>NO</center></th>
								<th style="color:white;"><center>CODE</center></th>
								<th style="color:white;"><center>SHADE</center></th>
							</tr>
						 </thead>
						 <tbody>';
		$no = 1;
		foreach($data->projectPurchaseQuotationProduct as $pp){
			
			if($pp->unit == '2' || $pp->unit == '3'){				
				$string .= '<tr class="text-center">
						<td class="align-middle">'.$no.'.</td>
						<td class="align-middle">'.$pp->product->type->code.'</a></td>
						<td class="align-middle">'.$pp->remark.'</td>
				   </tr>';
			}elseif($pp->unit == '1' || $pp->unit == '4'){
				$string .= '<tr class="text-center">
						<td class="align-middle">'.$no.'.</td>
						<td class="align-middle">'.$pp->product->type->code.'</a></td>
						<td class="align-middle">'.$pp->remark.'</td>
				   </tr>';
			}
			   
			$no++;
		}
						 
		$string .='</tbody>
					  </table>
				</div>
			</div>';
	
        return response()->json($string);
    }
	
	public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
			'supplier_id' 		=> 'required',
			'purchase_date' 	=> 'required',
			'sales_po'			=> 'required',
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
			'product_price'		=> 'required|array'
		], [
			'supplier_id.required' 				=> 'Supplier cannot empty.',
			'purchase_date.required' 			=> 'Purchase date cannot empty.',
			'sales_po.required' 				=> 'Sales cannot empty.',
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
			'product_price.array'				=> 'Product qty must be array.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			if($request->temp){
				
				$projectpurchase = ProjectPurchase::find($request->temp);
				
				if($projectpurchase->project){
					$response = [
						'status' => 422,
						'error'  => [
							'Invalid' => 'Sorry, this purchase was created under Project. You must edit on project page.'
						]
					];
					
					return response()->json($response);
				}
				
				$projectpurchase->user_id = session('bo_id');
				$projectpurchase->customer_id = $request->customer_id ? $request->customer_id  : NULL ;
				$projectpurchase->project_id = optional(ProjectSale::find($request->so_id))->project_id ?? 0;
				$projectpurchase->project_sale_id =  $request->so_id ? $request->so_id : 0;
				$projectpurchase->ppn = $request->ppn;
				$projectpurchase->note = $request->sales_note;
				$projectpurchase->fee_pta = $request->fee_pta;
				$projectpurchase->percent_fee_pta = $request->percent_fee_pta;
				$projectpurchase->supplier_id = $request->supplier_id;
				$projectpurchase->production_lead_time = $request->production_lead_time;
				$projectpurchase->estimated_delivery = $request->est_delivery_date;
				$projectpurchase->estimated_arrival = $request->est_arrival_date;
				$projectpurchase->factory_name = $request->factory_name;
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
				$projectpurchase->currency_rate = $request->currency_rate;
				$projectpurchase->brand_on_box = $request->brand;
				$projectpurchase->sni = $request->sni;
				$projectpurchase->is_wip = $request->is_wip;
				$projectpurchase->letter_head = $request->letter_head;
				$projectpurchase->checked_by = 0;
				$projectpurchase->approved_by = 0;
				$projectpurchase->created_at = $request->purchase_date.' '.date('H:i:s');
				
				$projectpurchase->update();
				
				#start notif
				$role = array('1','2','3','4','5','6','7','9','10','11');
				$title = 'Purchase For Stock has been edited!';
				$description = 'Purchase for stock with details project purchase code '.$projectpurchase->code.' has been edited by '.session('bo_name');
				$link = '#';
				Notification::sendNotif($role,$title,$description,$link);
				#end notif
				
			}else{
				$projectpurchase = ProjectPurchase::create([
					'user_id'					=> session('bo_id'),
					'customer_id'				=> $request->customer_id ? $request->customer_id  : NULL ,
					'project_id'				=> optional(ProjectSale::find($request->so_id))->project_id ?? 0,
					'project_sale_id'			=> $request->so_id ? $request->so_id : 0,
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
					'letter_head'			    => $request->letter_head,
					'checked_by'				=> 0,
					'approved_by'				=> 0,
					'created_at' 				=> $request->purchase_date.' '.date('H:i:s')
				]);
			}
			
			ProjectPurchaseProduct::where('project_purchase_id',$request->temp)->delete();
						
			foreach($request->product_id as $key => $pi) {
				ProjectPurchaseProduct::create([
					'project_purchase_id' 	=> $projectpurchase->id,
					'product_id'  	 		=> $request->product_id[$key],
					'qty'  					=> $request->product_qty[$key],
					'unit'					=> $request->product_unit[$key] == '2' ? '3' : $request->product_unit[$key],
					'price'					=> round(str_replace(',','.',str_replace('.','',str_replace('IDR ','',$request->product_price[$key]))),2),
					'remark'				=> $request->product_remark[$key]
				]);
			}
			
			if($request->is_wip == '0'){
				#send approval
				$roleapproval = array('5');
				Approval::sendApproval($roleapproval,'project_purchases',$projectpurchase->id,'checked_by',session('bo_id'));
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval,'project_purchases',$projectpurchase->id,'approved_by',session('bo_id'));
				#end approval
				
				SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Order No. '.$projectpurchase->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Order No. '.$projectpurchase->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			}
			
			ProjectPurchase::find($projectpurchase->id)->updateGrandtotal();
			
			activity()
                    ->performedOn(new ProjectPurchase())
					->withProperties($projectpurchase)
                    ->causedBy(session('bo_id'))
                    ->log('Add / edit Project Purchase');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
        }

        return response()->json($response);
    }
    
    
	public function createQuotation(Request $request){
		$validation = Validator::make($request->all(), [
			'supplier_id' 		=> 'required',
			'sales_id'			=> 'required',
			'product_id'		=> 'required|array',
			'product_unit'		=> 'required|array',
		], [
			'supplier_id.required' 				=> 'Supplier cannot empty.',
			'sales_id.required' 				=> 'Sales cannot empty.',
			'product_id.array'					=> 'Product must be array.',
			'product_unit.required'				=> 'Product unit cannot empty.',
			'product_unit.array'				=> 'Product unit must be array.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			if($request->temp_quotation_id){
				
				$projectpurchasequotation = projectpurchasequotation::find($request->temp);
				
				if($projectpurchasequotation->project){
					$response = [
						'status' => 422,
						'error'  => [
							'Invalid' => 'Sorry, this quotation was created under Project. You must edit on project page.'
						]
					];
					
					return response()->json($response);
				}
				
				$projectpurchasequotation->user_id = session('bo_id');
				$projectpurchasequotation->note = $request->note;
				$projectpurchasequotation->supplier_id = $request->supplier_id;
				$projectpurchasequotation->sales_id = $request->sales_id;
				$projectpurchasequotation->approved_by = 0;
				
				$projectpurchasequotation->update();
				
				#start notif
				$role = array('1','2','3','4','5','6','7','9','10','11');
				$title = 'Request quotation has been edited!';
				$description = 'Request quotation with details project Request quotation code '.$projectpurchasequotation->code.' has been edited by '.session('bo_name');
				$link = '#';
				Notification::sendNotif($role,$title,$description,$link);
				#end notif
				
			}else{
				$projectpurchasequotation = ProjectPurchaseQuotation::create([
					'user_id'					=> session('bo_id'),
					'code'						=> ProjectPurchaseQuotation::generateCode(),
					'note' 		 				=> $request->note,
					'supplier_id'				=> $request->supplier_id,
					'sales_id'					=> $request->sales_id,
					'approved_by'				=> 0,
				]);
			}
			
			ProjectPurchaseQuotationProduct::where('project_purchase_quotation_id',$request->temp)->delete();
						
			foreach($request->product_id as $key => $pi) {
				ProjectPurchaseQuotationProduct::create([
					'project_purchase_quotation_id' 	=> $projectpurchasequotation->id,
					'product_id'  	 					=> $request->product_id[$key],
					'unit'								=> $request->product_unit[$key] == '2' ? '3' : $request->product_unit[$key],
					'remark'							=> $request->product_remark[$key],
				]);
			}
			
		
			#send approval
			$roleapproval = array('4');
			Approval::sendApproval($roleapproval,'project_purchase_quotations',$projectpurchasequotation->id,'approved_by',session('bo_id'));
			#end approval
			
			SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Request QUotation No. '.$projectpurchasequotation->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
		
			
			activity()
                    ->performedOn(new ProjectPurchaseQuotation())
					->withProperties($projectpurchasequotation)
                    ->causedBy(session('bo_id'))
                    ->log('Add / edit Project Purchase quotation');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
        }

        return response()->json($response);
	}
	
	public function createProforma(Request $request)
    {
        $validation = Validator::make($request->all(), [
			'tempProforma'			=> 'required',
			'date'    				=> 'required',
			'supplier_name'    		=> 'required',
			'supplier_warehouse' 	=> 'required'
		], [
			'tempProforma.required'			=> 'Purchase information cannot empty',
			'date.required'    				=> 'Date cannot empty.',
			'supplier_name.required'    	=> 'Supplier name cannot empty.',
			'supplier_warehouse.required' 	=> 'Supplier warehouse cannot empty.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$cekpo = ProjectPurchase::find($request->tempProforma);
			
			if($cekpo->project_id){
				$project = Project::find($cekpo->project_id);
				
				$project->update([
					'progress'	=> $project->progress < 45 ? 45 : $project->progress
				]);
			}
			
			$projectproforma = ProjectProforma::create([
				'project_id'			=> $cekpo->project_id,
				'project_purchase_id' 	=> $request->tempProforma,
				'image'      			=> $request->file('file') ? $request->file('file')->store('public/project') : '',
				'date'       			=> $request->date,
				'supplier_name'    		=> $request->supplier_name,
				'supplier_warehouse'    => $request->supplier_warehouse,
				'note'					=> $request->note
			]);
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project has been updated!';
			$description = 'Purchase product with details proforma from '.$request->supplier_name.' has been updated by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
                    ->performedOn(new ProjectProforma())
					->withProperties($projectproforma)
                    ->causedBy(session('bo_id'))
                    ->log('Add Project Purchase Proforma');
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
        }

        return response()->json($response);
    }
	
	public function createBill(Request $request)
    {
        $validation = Validator::make($request->all(), [
			'tempBill'					=> 'required',
			'purchase_bill_doc'    		=> 'required',
			'purchase_bill_method'  	=> 'required',
			'purchase_bill_date' 		=> 'required',
			'purchase_bill_due_date'	=> 'required',
			'purchase_bill_nominal'		=> 'required',
			'purchase_bill_note'		=> 'required',
			'purchase_bill_file'		=> 'required|mimes:jpg,jpeg,png,pdf'
		], [
			'tempBill.required'					=> 'Purchase information cannot empty',
			'purchase_bill_doc.required'    	=> 'Purchase Bill Document Number cannot empty.',
			'purchase_bill_method.required' 	=> 'Method cannot empty.',
			'purchase_bill_date.required' 		=> 'Date cannot empty.',
			'purchase_bill_due_date.required'	=> 'Due date cannot empty',
			'purchase_bill_nominal.required'	=> 'Nominal cannot empty',
			'purchase_bill_note.required'		=> 'Note cannot empty',
			'purchase_bill_file.required'		=> 'File cannot empty',
			'purchase_bill_file.mimes'          => 'File must have an extension jpg, jpeg, png, and pdf.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$projectpurchase = ProjectPurchase::find($request->tempBill);
			
			$ppb = ProjectPurchaseBill::create([
				'user_id'				=> session('bo_id'),
				'project_id'			=> $projectpurchase->project_id,
				'project_purchase_id'	=> $request->tempBill,
				'no_document'			=> $request->purchase_bill_doc,
				'method'				=> $request->purchase_bill_method,
				'date'					=> $request->purchase_bill_date,
				'due_date'				=> $request->purchase_bill_due_date,
				'nominal'				=> str_replace(',','.',str_replace('.','',$request->purchase_bill_nominal)),
				'note'         			=> $request->purchase_bill_note,
				'image'					=> $request->file('purchase_bill_file') ? $request->file('purchase_bill_file')->store('public/project') : ''
			]);
			
			$projectpurchase->update([
				'payment_due_date' => $request->purchase_bill_due_date
			]);
			
			$query = PurchaseRequest::create([
				'date'	     				=> $request->purchase_bill_date,
				'due_date'	     			=> $request->purchase_bill_due_date,
				'user_id'					=> session('bo_id'),
				'branch'					=> $projectpurchase->sales->branch,
				'item'						=> 'PURCHASE PAYMENT REQUEST NUMBER '.$projectpurchase->code.' - '.$projectpurchase->supplier->name.' For Stock with Note : '.$request->purchase_bill_note,
				'image'						=> $request->file('purchase_bill_file') ? $request->file('purchase_bill_file')->store('public/purchase') : '',
				'total_nominal'				=> str_replace(',','.',str_replace('.','',$request->purchase_bill_nominal)),
				'status'					=> 'PEND',
				'link_type'					=> 'project_purchases',
				'link_id'					=> $projectpurchase->id,
				'project_purchase_bill_id'	=> $ppb->id,
			]);
			
			#send approval
			$roleapproval = array('4');
			Approval::sendApproval($roleapproval,'purchase_requests',$query->id,'approved_by',session('bo_id'));
			#end approval
			
			SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$query->id.', untuk Purchase Order No. '.$projectpurchase->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			
			activity()
				->performedOn(new ProjectPurchaseBill())
				->causedBy(session('bo_id'))
				->withProperties($ppb)
				->log('Add project purchase Bill by '.session('bo_name'));
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
        }

        return response()->json($response);
    }
	
	public function createCost(Request $request)
    {
        $validation = Validator::make($request->all(), [
			'tempCost'						=> 'required',
			'cost_wip'    					=> 'required',
			'cost_fee_mkj'			 		=> 'required',
			// 'purchase_request_id'			=> 'required|array',
			// 'purchase_request_nominal'		=> 'required|array',
			'project_purchase_product_id'	=> 'required|array',
			'final_price'					=> 'required|array'
		], [
			'tempCost.required'						=> 'Purchase information cannot empty',
			'cost_wip.required'    					=> 'WIP Coa cannot empty.',
			'cost_fee_mkj.required'					=> 'Fee MKJ in percentage cannot empty.',
			// 'purchase_request_id.required'			=> 'Purchase request cannot empty.',
			// 'purchase_request_id.array'				=> 'Purchase request must be array.',
			// 'purchase_request_nominal.required'		=> 'Purchase request percentage cannot empty.',
			// 'purchase_request_nominal.array'		=> 'Purchase request percentage must be array.',
			'project_purchase_product_id.required'	=> 'Project purchase product cannot empty.',
			'project_purchase_product_id.array'		=> 'Project purchase product must be array.',
			'final_price.required'					=> 'Final price cannot empty.',
			'final_price.array'						=> 'Final price must be array.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$pc = PurchaseCost::where('project_purchase_id',$request->tempCost)->first();
			
			if($pc){
				$pc->purchaseCostDetail()->delete();
				$pc->delete();
			}
			
			ProjectPurchase::find($request->tempCost)->update([
				'currency_id'	=> 5,
				'currency_rate'	=> 1
			]);
			
			$pp = ProjectPurchase::find($request->tempCost);
			
			if($pp){
				foreach($pp->projectWarehouse as $pw){
					$pw->update([
						'include_cost' => '1'
					]);
				}
			}
	
			if($request->purchase_request_id){
				$pc = PurchaseCost::create([
					'user_id'				=> session('bo_id'),
					'project_purchase_id'	=> $request->tempCost,
					'coa_id'				=> $request->cost_wip,
					'percent_fee_mkj'		=> str_replace(',','.',str_replace('.','',$request->cost_fee_mkj)),
				]);

				foreach($request->purchase_request_id as $key => $row){
					PurchaseCostDetail::create([
						'purchase_cost_id'		=> $pc->id,
						'purchase_request_id'	=> $row,
						'is_ppn'				=> $request->purchase_is_ppn[$key],
						'percent_ppn'			=> str_replace(',','.',str_replace('.','',$request->purchase_percent_ppn[$key])),
						'nominal'				=> str_replace(',','.',str_replace('.','',$request->purchase_request_nominal[$key]))
					]);
				}	
			}
			
			foreach($request->project_purchase_product_id as $key => $row){
				ProjectPurchaseProduct::find($row)->update([
					'price'	=> 	str_replace(',','.',str_replace('.','',$request->final_price[$key]))
				]);
			}
			
			Approval::where('approvalable_type','project_purchases')->where('approvalable_id',$pp->id)->delete();
			
			#send approval
			$roleapproval = array('5');
			Approval::sendApproval($roleapproval,'project_purchases',$pp->id,'checked_by',session('bo_id'));
			$roleapproval = array('4');
			Approval::sendApproval($roleapproval,'project_purchases',$pp->id,'approved_by',session('bo_id'));
			#end approval
			
			SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Order No. '.$pp->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Order No. '.$pp->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
			
			activity()
				->performedOn(new PurchaseCost())
				->causedBy(session('bo_id'))
				->withProperties($pc)
				->log('Add Purchase Cost by '.session('bo_name'));
			
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.',
				//'data'	  => $pc
			];
        }

        return response()->json($response);
    }
	
	public function createProduction(Request $request)
    {
		$validation = Validator::make($request->all(), [
			'tempProduction'		=> 'required',
			'start_date'  			=> 'required',
			'finish_date'			=> 'required',
			'progress_production'	=> 'required',
			'note'        			=> 'required'
		], [
			'tempProduction.required'			=> 'Purchase information cannot empty',
			'pr_id.required'					=> 'Purchase cannot empty.',
			'start_date.required'  				=> 'Start date cannot empty.',
			'finish_date.required' 				=> 'Finish date cannot empty.',
			'note.required'        				=> 'Note cannot empty.',
			'progress_production.required'   	=> 'Progress production cannot empty.'
		]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$projectpurchase = ProjectPurchase::find($request->tempProduction);
			
			if($projectpurchase->project_id){
				$project = Project::find($projectpurchase->project_id);
				
				$project->update([
					'progress'	=> $project->progress < 50 ? 50 : $project->progress
				]);
			}
			
			$pp = ProjectProduction::create([
				'project_id'			=> $projectpurchase->project_id,
				'project_purchase_id'	=> $request->tempProduction,
				'image'       			=> $request->file('file') ? $request->file('file')->store('public/project') : '',
				'start_date'  			=> $request->start_date,
				'finish_date' 			=> $request->finish_date,
				'note'        			=> $request->note,
				'progress'	  			=> $request->progress_production
			]);
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Purchase progress production has been updated!';
			$description = 'Purchase code '.$pp->projectPurchase->code.' with details production progress has been updated by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new ProjectProduction())
				->causedBy(session('bo_id'))
				->withProperties($pp)
				->log('Change data purchase code '.$pp->projectPurchase->code);
				
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
							
		}
		
		return response()->json($response);
	}
	
	public function createDelivery(Request $request)
    {
		
		$validation = Validator::make($request->all(), [
			'tempDelivery'   => 'required',
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
			'tempDelivery.required'	  => 'Purchase cannot empty.',
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
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			if($request->temp_shipment_id){		
				$projectshipment = ProjectShipment::find($request->temp_shipment_id);
				
				if($request->has('file')) {
					if(Storage::exists($projectshipment->image)) {
						Storage::delete($projectshipment->image);
					}

					$image = $request->file('file')->store('public/project');
				} else {
					$image = $projectshipment->image;
				}
				
				$projectshipment->project_purchase_id	= $request->tempDelivery;
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
				$description = $request->shipment_code.' has been edited by '.session('bo_name');
				$link = '#';
				Notification::sendNotif($role,$title,$description,$link);
				#end notif				
			}else{
				$projectpurchase = ProjectPurchase::find($request->tempDelivery);
		
				if($projectpurchase->project_id){
					$project = Project::find($projectpurchase->project_id);
					
					$project->update([
						'progress'	=> $project->progress < 60 ? 60 : $project->progress
					]);
				}
			
				if($request->has('file')) {
					$image = $request->file('file')->store('public/project');
				}else{
					$image = '';
				}
				
				$projectshipment = ProjectShipment::create([
					'project_id'			=> $projectpurchase->project_id,
					'project_purchase_id'	=> $request->tempDelivery,
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
			}
			
			
			foreach($request->product_id as $key => $pi) {
				ProjectShipmentProduct::create([
					'project_shipment_id' 	=> $projectshipment->id,
					'product_id'  	 		=> $request->product_id[$key],
					'qty'  					=> $request->product_qty[$key],
					'unit'					=> $request->product_unit[$key]
				]);
			}
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Purchase has been updated!';
			$description = 'Purchase with details shipment code '.$request->shipment_code.' in purchase code '.$projectshipment->projectPurchase->code.' has been updated by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new ProjectShipment())
				->causedBy(session('bo_id'))
				->withProperties($projectshipment)
				->log('Purchase shipment code '.$request->shipment_code);
				
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
							
		}
		
		return response()->json($response);
	}
	
	public function createWarehouse(Request $request)
    {
		
		$validation = Validator::make($request->all(), [
			'tempWarehouse'   		=> 'required',
			'shipment_id'   		=> 'required',
			'person'   				=> 'required',
			'date_receive' 			=> 'required',
			'warehouse_id'      	=> 'required',
			'file'   				=> 'required|mimes:jpg,jpeg,png,pdf',
			'product_id'	 		=> 'required|array',
			'product_unit'	 		=> 'required|array',
			'product_qty'	 		=> 'required|array',
		], [
			'tempWarehouse.required'		=> 'Purchase cannot empty.',
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
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$projectpurchase = ProjectPurchase::find($request->tempWarehouse);

			if(CheckCutOff::check($projectpurchase->sales->branch,substr($request->date_receive,0,7))){
				// $customError = [];
				// $isExceeded = false;
				// foreach($request->product_id as $key => $pi) {
				// 	$customError[] = [
				// 		'error' => 'The quantity of '.$projectpurchase->isQuantityExceeded($pi->product_id, $pi->qty)['productName'].'ordered exceeds. Only '.$projectpurchase->isQuantityExceeded($pi->product_id, $pi->qty)['remainingQty'].' are needed'
				// 	];

				// 	$isExceeded = $projectpurchase->isQuantityExceeded($pi->product_id, $pi->qty)['isEisExceeded'];
				// }
				// checkpoint
				

				if($projectpurchase->project_id){
					$project = Project::find($projectpurchase->project_id);
					
					$project->update([
						'progress'	=> $project->progress < 65 ? 65 : $project->progress
					]);
				}


				if($request->temp_warehouse_id){
							
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
					$projectwarehouse->project_purchase_id	= $request->tempWarehouse;
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
					$description = 'with details warehouse code '.$projectwarehouse->code.' has been edited by '.session('bo_name').'.';
					$link = '#';
					Notification::sendNotif($role,$title,$description,$link);
					#end notif
					
				}else{
					$projectwarehouse = ProjectWarehouse::create([
						'user_id'				=> session('bo_id'),
						'project_id'			=> $projectpurchase->project_id,
						'project_purchase_id'	=> $request->tempWarehouse,
						'project_shipment_id'	=> $request->shipment_id,
						'code'					=> ProjectWarehouse::generateCode(),
						'image'      	 		=> $request->file('file')->store('public/project'),
						'date_receive' 	 		=> $request->date_receive,
						'warehouse_id'  	 	=> $request->warehouse_id,
						'person'         		=> $request->person,
						'include_cost'			=> $request->include_cost,
						'coa_id'				=> $request->include_cost == '0' ? $request->wr_coa : null
					]);
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
					
					$cek = Stock::where('product_id',$pi)->where('warehouse_id',$request->warehouse_id)->where('branch',$projectwarehouse->projectPurchase->sales->branch)->first();
					
					if($cek){
						$cek->update([
							'qty' 	=> $cek->qty + $request->product_qty[$key],
							'unit'	=> $request->product_unit[$key]
						]);
					}else{
						Stock::create([
							'product_id'	=> $pi,
							'warehouse_id'	=> $request->warehouse_id,
							'qty'			=> 0 + $request->product_qty[$key],
							'unit'			=> $request->product_unit[$key],
							'branch'		=> $projectwarehouse->projectPurchase->sales->branch
						]);
					}
				}
				
				$pp = ProjectPurchase::find($request->tempWarehouse);
				
				if(date('Y-m-d',strtotime($pp->created_at)) < '2022-04-01'){
					$persenppn = 0.1;
					$ppnpembagi = 1.1;
				}else{
					$persenppn = 0.11;
					$ppnpembagi = 1.11;
				}
							
				$totalPurchase = $projectwarehouse->getTotal()['totalpurchase'];
				$totalUnder = 0;
				
				$projectdetail = $pp->id;
				$reference = '2'; #is purchase
				$type = '2'; #journal
				$description = 'Warehouse receive '.$projectwarehouse->code.' From PO Code '.$pp->code;
				
				$debetcb = 31; #COA ID Inventory
				
				if($pp->ppn == '1'){
					$debetnominal = round($totalPurchase/ $ppnpembagi,0);
					$ppnmasukan = 50;
					$ppnnominal = round($totalPurchase - ($totalPurchase / $ppnpembagi),0);
				}else{
					$debetnominal = $totalPurchase;
				}
				
				ProjectWarehouse::find($projectwarehouse->id)->updateGrandtotal();
				
				#START
				
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'lookable_type'  	=> 'project_warehouses',
					'lookable_id'		=> $projectwarehouse->id,
					'supplier_id'		=> $projectwarehouse->projectPurchase->supplier_id,
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
						'nominal'      	=> round($debetnominal),
						'note'         	=> ''
					]);
					
					Journal::insert([
						'date_transaction' => $request->date_receive,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debetcb,
						'branch'		   => $pp->sales->branch,
						'type'	           => '1',
						'nominal'          => round($debetnominal),
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
				
				#END
				
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
							'item'						=> 'PROJECT PURCHASE PAYMENT REQUEST NUMBER '.($pp->project ? $pp->project->code : '').' - '.$pp->supplier->name.' - '.($pp->projectSale ? $pp->projectSale->code : '').' with PO NO. : '.$pp->code.' with WR NO. '.$projectwarehouse->code.'AUTOMATICALLY BY SYSTEM TJS.',
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
						
						SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$pr->id.', untuk Purchase Order No. '.$pp->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						
						#END
					}
				}
				
				#update cogs
							
				ProductCogs::updateCogs(substr($projectwarehouse->date_receive,0,10),$pp->sales->branch);
				
				#start notif
				$role = array('1','2','3','4','5','6','7','9','10','11');
				$title = 'Project has been updated!';
				$description = 'Purchase code '.$pp->code.' has been received by '.$request->person.' with warehouse receive code '.$projectwarehouse->code.'.';
				$link = '#';
				Notification::sendNotif($role,$title,$description,$link);
				#end notif

				activity()
					->performedOn(new ProjectWarehouse())
					->causedBy(session('bo_id'))
					->withProperties($projectwarehouse)
					->log('Add purchase warehouse receive code '.$pp->code.' with warehouse receive code '.$projectwarehouse->code);
					
				$response = [
					'status'  => 200,
					'message' => 'Data added successfully.'
				];
			}else{
				$response = [
					'status'  => 503,
					'message' => 'The journal for this month was already closed.'
				];
			}		
		}
		
		return response()->json($response);
	}
	
	public function destroy(Request $request)
	{
		$pp = ProjectPurchase::find($request->id);
		$reason = $request->reason;
		
		if($pp->project){
			return response()->json([
				'status'  => 400,
				'message' => 'This PO is on Project, you must delete inside project purchase.'
			]);
		}else{
		
			ProjectPurchaseProduct::where('project_purchase_id',$request->id)->delete();
			
			$purchaserequest = PurchaseRequest::where('link_type','project_purchases')->where('link_id',$request->id)->get();
			
			foreach($purchaserequest as $row){
				$row->deleteFile();
				$row->delete();
			}
			
			Approval::where('approvalable_type','project_purchases')->where('approvalable_id',$request->id)->delete();
			
			$pp->projectPurchaseBill()->delete();
			$cb = CashBank::where('lookable_type','project_purchases')->where('lookable_id',$pp->id)->get();
			foreach($cb as $c){
				$c->deleteDetail();
				$c->delete();
			}
			
			$note = ProjectNote::where('notable_type','project_purchases')->where('notable_id',$pp->id)->get();
			
			foreach($note as $n){
				$n->deleteFile();
				$n->delete();
			}
			
			$pc = PurchaseCost::where('project_purchase_id',$pp->id)->first();
			
			if($pc){
				$pc->purchaseCostDetail()->delete();
				$pc->delete();
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
			
			foreach($pp->projectProforma as $purchaseproforma){
				$purchaseproforma->deleteFile();
				$purchaseproforma->delete();
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
				
				$prcek = PurchaseRequest::where('project_warehouse_id',$warehouse->id)->get();
				foreach($prcek as $rowcek){
					foreach($rowcek->purchaseRequestPayment() as $rowpay){
						$rowpay->deleteFile();
						$rowpay->delete();
					}
					$rowcek->deleteFile();
					$rowcek->delete();
				}
				
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
		}
	}
	
	public function destroyQuotation(Request $request)
	{
		$pp = ProjectPurchaseQuotation::find($request->id);
		$reason = $request->reason;
		
		if($pp->project){
			return response()->json([
				'status'  => 400,
				'message' => 'This RQ is on Project, you must delete inside project purchase.'
			]);
		}else{
		
			ProjectPurchaseQuotationProduct::where('project_purchase_quotation_id',$request->id)->delete();
			
			Approval::where('approvalable_type','project_purchases')->where('approvalable_id',$request->id)->delete();					
			
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
		}
	}
	
	public function getPurchaseCost(Request $request)
    {
		$dataproduct = ProjectPurchaseProduct::where('project_purchase_id',$request->idpo)->get();
		
		$datacost = PurchaseCost::where('project_purchase_id',$request->idpo)->first();
		
		$arrpc = [];
		
		if($datacost){
			foreach($datacost->purchaseCostDetail as $rowcost){
				$arrpc[] = [
					'purchase_request_id'	=> $rowcost->purchase_request_id,
					'to'					=> $rowcost->purchaseRequest->to,
					'is_ppn'				=> $rowcost->is_ppn,
					'tax'					=> $rowcost->percent_ppn,
					'detail'				=> $rowcost->purchaseRequest->date.'-'.$rowcost->purchaseRequest->user->name.'-'.$rowcost->purchaseRequest->title.'-'.$rowcost->purchaseRequest->item.'-'.number_format($rowcost->purchaseRequest->total_nominal,2,',','.'),
					'nominal'				=> number_format($rowcost->nominal,0,',','.'),
					'nominaltax'			=> $rowcost->is_ppn == '1' ? number_format(($rowcost->nominal / (($rowcost->percent_ppn + 100) / 100)) * ($rowcost->percent_ppn / 100),0,',','.') : 0,
					'nominalbtax'			=> $rowcost->is_ppn == '1' ? number_format(($rowcost->nominal / (($rowcost->percent_ppn + 100) / 100)),0,',','.') : number_format($rowcost->nominal,0,',','.')
				];
			}
		}
		
		$purchase = ProjectPurchase::find($request->idpo);
		
		$arrProduct = [];
		
		$grandtotal = 0;
		
		foreach($dataproduct as $row){
			$m2 = (( $row->product->type->length * $row->product->type->width ) / 10000) * $row->product->carton_pcs;
			
			$qty = $row->unit == '2' || $row->unit == '3' ? $row->qty * $m2 : $row->qty;
			
			$total = $row->price * $qty;
			
			$arrProduct[] = [
				'id'				=> $row->id,
				'product_name'		=> $row->product->name(),
				'product_qty'		=> round($qty,2),
				'product_price'		=> number_format($row->price,2,',','.'),
				'product_total'		=> number_format($total,2,',','.')
			];
			
			$grandtotal += $total;
		}
		
		$purchase['grandtotal'] = number_format($grandtotal,2,',','.');
		
		return response()->json([
			'purchase'	=> $purchase,
			'product'	=> $arrProduct,
			'maincost'	=> $datacost,
			'cost'		=> $arrpc
		]);
    }
    
    public function getPurchaseQuotation(Request $request){

		$data = [];

		$quotations = ProjectPurchaseQuotation::find($request->id);

		foreach($quotations->projectPurchaseQuotationProduct as $row){
			$data [] = [
				'product_id'   => $row->product_id,
				'product_name' => $row->product->name(),
				'unit' 		   => $row->unit,
				'remark' 	   => $row->remark,
			];
		}

		$arrayResult = [
			'sales_id' 		     => $quotations->sales_id,
			'sales_name' 		 => $quotations->sales->name,
			'supplier_id' 		 => $quotations->supplier_id,
			'supplier_name' 	 => $quotations->supplier->name,
			'note' 				 => $quotations->note,
			'quotation_products' => $data,
		];

		return response()->json($arrayResult);

	}
	

	// repair stock
	// public function repair(Request $request){
	// 	$rowIn = ProjectWarehouseProduct::all();
		
	// 	Stock::truncate();
		
	// 	foreach($rowIn as $row){
	// 		$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectWarehouse->warehouse_id)->where('branch',$row->projectWarehouse->projectPurchase->sales->branch)->first();
			
	// 		if($cek){
	// 			$cek->update([
	// 				'qty' 	=> $cek->qty + $row->qty,
	// 				'unit'	=> $row->unit
	// 			]);
	// 		}else{
	// 			Stock::create([
	// 				'product_id'	=> $row->product_id,
	// 				'warehouse_id'	=> $row->projectWarehouse->warehouse_id,
	// 				'qty'			=> $row->qty,
	// 				'unit'			=> $row->unit,
	// 				'branch'		=> $row->projectWarehouse->projectPurchase->sales->branch
	// 			]);
	// 		}
	// 	}
		
	// 	$rowOut = ProjectDeliveryProduct::all();
		
	// 	foreach($rowOut as $row){
	// 		if($row->projectDelivery->received_date){
	// 			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectDelivery->warehouse_id)->where('branch',$row->projectDelivery->projectSale->sales->branch)->first();
				
	// 			if($cek){
	// 				$cek->update([
	// 					'qty' 	=> $cek->qty - $row->qty,
	// 					'unit'	=> $row->unit
	// 				]);
	// 			}else{
	// 				Stock::create([
	// 					'product_id'	=> $row->product_id,
	// 					'warehouse_id'	=> $row->projectDelivery->warehouse_id,
	// 					'qty'			=> 0 - $row->qty,
	// 					'unit'			=> $row->unit,
	// 					'branch'		=> $row->projectDelivery->projectSale->sales->branch
	// 				]);
	// 			}
	// 		}
	// 	}
		
	// 	$rowInReturn = ProjectSaleReturnProduct::all();
		
	// 	foreach($rowInReturn as $row){
	// 		$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectSaleReturn->warehouse_id)->where('branch',$row->projectSaleReturn->projectSale->sales->branch)->first();
			
	// 		if($cek){
	// 			$cek->update([
	// 				'qty' 	=> $cek->qty + $row->qty,
	// 				'unit'	=> $row->unit
	// 			]);
	// 		}else{
	// 			Stock::create([
	// 				'product_id'	=> $row->product_id,
	// 				'warehouse_id'	=> $row->projectSaleReturn->warehouse_id,
	// 				'qty'			=> $row->qty,
	// 				'unit'			=> $row->unit,
	// 				'branch'		=> $row->projectSaleReturn->projectSale->sales->branch
	// 			]);
	// 		}
	// 	}
		
	// 	$rowOutReturn = ProjectPurchaseReturnProduct::all();
		
	// 	foreach($rowOutReturn as $row){
	// 		$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectPurchaseReturn->warehouse_id)->where('branch',$row->projectPurchaseReturn->projectPurchase->sales->branch)->first();
			
	// 		if($cek){
	// 			$cek->update([
	// 				'qty' 	=> $cek->qty - $row->qty,
	// 				'unit'	=> $row->unit
	// 			]);
	// 		}else{
	// 			Stock::create([
	// 				'product_id'	=> $row->product_id,
	// 				'warehouse_id'	=> $row->projectPurchaseReturn->warehouse_id,
	// 				'qty'			=> 0 - $row->qty,
	// 				'unit'			=> $row->unit,
	// 				'branch'		=> $row->projectPurchaseReturn->projectPurchase->sales->branch
	// 			]);
	// 		}
	// 	}
		
	// 	$rowTransfer = TransferProduct::all();
		
	// 	foreach($rowTransfer as $row){
	// 		if($row->transfer->from_warehouse_id){
	// 			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->transfer->from_warehouse_id)->where('branch',$row->transfer->branch)->first();
				
	// 			if($cek){
	// 				$cek->update([
	// 					'qty' 	=> $cek->qty - $row->qty,
	// 					'unit'	=> $row->unit
	// 				]);
	// 			}else{
	// 				Stock::create([
	// 					'product_id'	=> $row->product_id,
	// 					'warehouse_id'	=> $row->transfer->from_warehouse_id,
	// 					'qty'			=> 0 - $row->qty,
	// 					'unit'			=> $row->unit,
	// 					'branch'		=> $row->transfer->branch
	// 				]);
	// 			}
	// 		}
			
	// 		if($row->transfer->to_warehouse_id){
	// 			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->transfer->to_warehouse_id)->where('branch',$row->transfer->branch)->first();
				
	// 			if($cek){
	// 				$cek->update([
	// 					'qty' 	=> $cek->qty + $row->qty,
	// 					'unit'	=> $row->unit
	// 				]);
	// 			}else{
	// 				Stock::create([
	// 					'product_id'	=> $row->product_id,
	// 					'warehouse_id'	=> $row->transfer->to_warehouse_id,
	// 					'qty'			=> 0 + $row->qty,
	// 					'unit'			=> $row->unit,
	// 					'branch'		=> $row->transfer->branch
	// 				]);
	// 			}
	// 		}
	// 	}
	// }
	
	// public function repair(Request $request)
    // {
		/* $rowIn = ProjectWarehouseProduct::all();
		
		Stock::truncate();
		
		foreach($rowIn as $row){
			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectWarehouse->warehouse_id)->where('branch',$row->projectWarehouse->projectPurchase->sales->branch)->first();
			
			if($cek){
				$cek->update([
					'qty' 	=> $cek->qty + $row->qty,
					'unit'	=> $row->unit
				]);
			}else{
				Stock::create([
					'product_id'	=> $row->product_id,
					'warehouse_id'	=> $row->projectWarehouse->warehouse_id,
					'qty'			=> $row->qty,
					'unit'			=> $row->unit,
					'branch'		=> $row->projectWarehouse->projectPurchase->sales->branch
				]);
			}
		}
		
		$rowOut = ProjectDeliveryProduct::all();
		
		foreach($rowOut as $row){
			if($row->projectDelivery->received_date){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectDelivery->warehouse_id)->where('branch',$row->projectDelivery->projectSale->sales->branch)->first();
				
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty - $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $row->projectDelivery->warehouse_id,
						'qty'			=> 0 - $row->qty,
						'unit'			=> $row->unit,
						'branch'		=> $row->projectDelivery->projectSale->sales->branch
					]);
				}
			}
		}
		
		$rowInReturn = ProjectSaleReturnProduct::all();
		
		foreach($rowInReturn as $row){
			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectSaleReturn->warehouse_id)->where('branch',$row->projectSaleReturn->projectSale->sales->branch)->first();
			
			if($cek){
				$cek->update([
					'qty' 	=> $cek->qty + $row->qty,
					'unit'	=> $row->unit
				]);
			}else{
				Stock::create([
					'product_id'	=> $row->product_id,
					'warehouse_id'	=> $row->projectSaleReturn->warehouse_id,
					'qty'			=> $row->qty,
					'unit'			=> $row->unit,
					'branch'		=> $row->projectSaleReturn->projectSale->sales->branch
				]);
			}
		}
		
		$rowOutReturn = ProjectPurchaseReturnProduct::all();
		
		foreach($rowOutReturn as $row){
			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectPurchaseReturn->warehouse_id)->where('branch',$row->projectPurchaseReturn->projectPurchase->sales->branch)->first();
			
			if($cek){
				$cek->update([
					'qty' 	=> $cek->qty - $row->qty,
					'unit'	=> $row->unit
				]);
			}else{
				Stock::create([
					'product_id'	=> $row->product_id,
					'warehouse_id'	=> $row->projectPurchaseReturn->warehouse_id,
					'qty'			=> 0 - $row->qty,
					'unit'			=> $row->unit,
					'branch'		=> $row->projectPurchaseReturn->projectPurchase->sales->branch
				]);
			}
		}
		
		$rowTransfer = TransferProduct::all();
		
		foreach($rowTransfer as $row){
			if($row->transfer->from_warehouse_id){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->transfer->from_warehouse_id)->where('branch',$row->transfer->branch)->first();
				
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty - $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $row->transfer->from_warehouse_id,
						'qty'			=> 0 - $row->qty,
						'unit'			=> $row->unit,
						'branch'		=> $row->transfer->branch
					]);
				}
			}
			
			if($row->transfer->to_warehouse_id){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->transfer->to_warehouse_id)->where('branch',$row->transfer->branch)->first();
				
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty + $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $row->transfer->to_warehouse_id,
						'qty'			=> 0 + $row->qty,
						'unit'			=> $row->unit,
						'branch'		=> $row->transfer->branch
					]);
				}
			}
		} */
		
		/* $datasale = ProjectSale::all();
		
		foreach($datasale as $row){
			$subtotal_product = str_replace(',','.',str_replace('.','',$row->getTotalRaw()));
			
			if(date('Y-m-d',strtotime($row->created_at)) < '2022-04-01'){
				$persenppn = 0.1;
			}else{
				$persenppn = 0.11;
			}
			
			$tax_product = $row->project->ppn == '1' ? ($subtotal_product * $persenppn) : 0;
			
			$grandtotal_product = $subtotal_product + $tax_product;
			
			$subtotal_service = $row->delivery_cost + $row->cutting_cost + $row->misc_cost;
			
			$tax_service = $row->ppn_cost == '1' ? ($subtotal_service * $persenppn) : 0;
			
			$grandtotal_service = $subtotal_service + $tax_service;
			
			ProjectSale::find($row->id)->update([
				'subtotal_product' 		=> round($subtotal_product,2),
				'tax_product'			=> round($tax_product,2),
				'grandtotal_product'	=> round($grandtotal_product,2),
				'subtotal_service'		=> round($subtotal_service,2),
				'tax_service'			=> round($tax_service,2),
				'grandtotal_service'	=> round($grandtotal_service,2)
			]);
		} 
		
		$datadelivery = ProjectDelivery::all();
		
		foreach($datadelivery as $row){
			$subtotal_product = $row->getTotalRaw();
			
			if(date('Y-m-d',strtotime($row->projectSale->created_at)) < '2022-04-01'){
				$persenppn = 0.1;
			}else{
				$persenppn = 0.11;
			}
			
			$tax_product = $row->project->ppn == '1' ? ($subtotal_product * $persenppn) : 0;
			
			$grandtotal_product = $subtotal_product + $tax_product;
			
			$subtotal_service = $row->getServiceCost();
			
			$tax_service = $row->projectSale->ppn_cost == '1' ? ($subtotal_service * $persenppn) : 0;
			
			$grandtotal_service = $subtotal_service + $tax_service;
			
			ProjectDelivery::find($row->id)->update([
				'subtotal_product' 		=> round($subtotal_product,2),
				'tax_product'			=> round($tax_product,2),
				'grandtotal_product'	=> round($grandtotal_product,2),
				'subtotal_service'		=> round($subtotal_service,2),
				'tax_service'			=> round($tax_service,2),
				'grandtotal_service'	=> round($grandtotal_service,2)
			]);
		} */
		
		
		/* $data = ProjectSaleReturn::all();
		
		foreach($data as $row){
			$grandtotal = $row->getTotal();
			
			ProjectSaleReturn::find($row->id)->update([
				'grandtotal'	=> round($grandtotal,2)
			]);
		} */
		
		/* foreach(ProjectPurchase::all() as $row){
			$row->updateGrandtotal();
		} */
		
		/* $pr = PurchaseRequest::create([
			'date'	     			=> '2022-01-31',
			'bill_to'				=> 'Bu Shanti - PTA',
			'title'					=> '',
			'item'					=> '',
			'user_id'				=> 7,
			'coa_id'				=> 85,
			'branch'				=> '1',
			'total_nominal'			=> 0,
			'total_cash_advance'	=> 0,
			'status'				=> 'APPR',
			'term'					=> '0',
			'supplier_id'			=> 132,
			'term_days'				=> 0,
			'due_date'				=> '2022-01-31',
			'approved_by'			=> 7,
		]); */
		
		/* $rowIn = ProjectWarehouseProduct::all();
		
		Stock::truncate();
		
		foreach($rowIn as $row){
			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectWarehouse->warehouse_id)->where('branch',$row->projectWarehouse->projectPurchase->sales->branch)->first();
			
			if($cek){
				$cek->update([
					'qty' 	=> $cek->qty + $row->qty,
					'unit'	=> $row->unit
				]);
			}else{
				Stock::create([
					'product_id'	=> $row->product_id,
					'warehouse_id'	=> $row->projectWarehouse->warehouse_id,
					'qty'			=> $row->qty,
					'unit'			=> $row->unit,
					'branch'		=> $row->projectWarehouse->projectPurchase->sales->branch
				]);
			}
		}
		
		$rowOut = ProjectDeliveryProduct::all();
		
		foreach($rowOut as $row){
			if($row->projectDelivery->received_date){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectDelivery->warehouse_id)->where('branch',$row->projectDelivery->projectSale->sales->branch)->first();
				
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty - $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $row->projectDelivery->warehouse_id,
						'qty'			=> 0 - $row->qty,
						'unit'			=> $row->unit,
						'branch'		=> $row->projectDelivery->projectSale->sales->branch
					]);
				}
			}
		}
		
		$rowInReturn = ProjectSaleReturnProduct::all();
		
		foreach($rowInReturn as $row){
			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectSaleReturn->warehouse_id)->where('branch',$row->projectSaleReturn->projectSale->sales->branch)->first();
			
			if($cek){
				$cek->update([
					'qty' 	=> $cek->qty + $row->qty,
					'unit'	=> $row->unit
				]);
			}else{
				Stock::create([
					'product_id'	=> $row->product_id,
					'warehouse_id'	=> $row->projectSaleReturn->warehouse_id,
					'qty'			=> $row->qty,
					'unit'			=> $row->unit,
					'branch'		=> $row->projectSaleReturn->projectSale->sales->branch
				]);
			}
		}
		
		$rowOutReturn = ProjectPurchaseReturnProduct::all();
		
		foreach($rowOutReturn as $row){
			$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->projectPurchaseReturn->warehouse_id)->where('branch',$row->projectPurchaseReturn->projectPurchase->sales->branch)->first();
			
			if($cek){
				$cek->update([
					'qty' 	=> $cek->qty - $row->qty,
					'unit'	=> $row->unit
				]);
			}else{
				Stock::create([
					'product_id'	=> $row->product_id,
					'warehouse_id'	=> $row->projectPurchaseReturn->warehouse_id,
					'qty'			=> 0 - $row->qty,
					'unit'			=> $row->unit,
					'branch'		=> $row->projectPurchaseReturn->projectPurchase->sales->branch
				]);
			}
		}
		
		$rowTransfer = TransferProduct::all();
		
		foreach($rowTransfer as $row){
			if($row->transfer->from_warehouse_id){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->transfer->from_warehouse_id)->where('branch',$row->transfer->branch)->first();
				
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty - $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $row->transfer->from_warehouse_id,
						'qty'			=> 0 - $row->qty,
						'unit'			=> $row->unit,
						'branch'		=> $row->transfer->branch
					]);
				}
			}
			
			if($row->transfer->to_warehouse_id){
				$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->transfer->to_warehouse_id)->where('branch',$row->transfer->branch)->first();
				
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty + $row->qty,
						'unit'	=> $row->unit
					]);
				}else{
					Stock::create([
						'product_id'	=> $row->product_id,
						'warehouse_id'	=> $row->transfer->to_warehouse_id,
						'qty'			=> 0 + $row->qty,
						'unit'			=> $row->unit,
						'branch'		=> $row->transfer->branch
					]);
				}
			}
		} */
		
		/* $data = ProjectWarehouse::all();
		
		foreach($data as $row){
			$row->updateGrandtotal();
		} */
		
		/* foreach(CashBank::whereHas('cashBankDetail',function($query){ $query->where('coa_id',67); })->whereRaw("DATE(date) <= '2022-12-29'")->get() as $rowcb){
			foreach($rowcb->cashBankDetail->where('coa_id',67)->where('type','2') as $rowcbdetail){
				if($rowcb->lookable_type == 'project_pays' || $rowcb->lookable_type == 'project_deliveries' || $rowcb->lookable_type == 'project_sale_returns' || $rowcb->lookable_type == 'project_bills'){
					if(!$rowcb->lookable()->exists()){
						echo $rowcb->code.',';
					}
				}
			}
		} */
		
		/* $branch = '1';
		
		$productcogs = ProjectDelivery::whereHas('projectSale',function($query) use ($branch){
			$query->whereHas('sales',function($query) use ($branch){
				$query->where('branch',$branch);
			});
		})->whereNotNull('received_date')->orderBy('received_date')->get(['received_date']);
		
		var_dump($productcogs); */
		// set_time_limit(300);
		// // ProductCogs::truncate();
		
		// $branch = '4';
		
		// $startDate = new Carbon('2024-04-01'); 
		// $endDate = new Carbon('2024-04-30');
		
	// 	while ($startDate->lte($endDate)){
	// 		$date = $startDate->toDateString();
	// 		ProductCogs::where('branch',$branch)->where('date','=',$date)->delete();

	// 		$dataIn = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use($date,$branch){
	// 			$query->whereHas('projectPurchase',function($query) use($date,$branch){
	// 				$query->whereHas('sales',function($query) use($date,$branch){
	// 					$query->where('branch',$branch);
	// 				});
	// 			})->whereDate('date_receive',$date);
	// 		})->get();
			
	// 		foreach($dataIn as $row){
	// 			$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
	// 			$pricenow = str_replace(',','.',str_replace('.','',$row->purchaseReal()));
				
	// 			if($cek){
					
	// 				$totalold = $cek->total_final;
	// 				$totalnew = $row->qty * $pricenow;
	// 				$priceperqty = $cek->qty_final + $row->qty > 0 ? round(($totalold + $totalnew) / ($cek->qty_final + $row->qty),2) : $totalnew;
	// 				$pricein = round(($cek->total_in + ($pricenow * $row->qty)) / ($cek->qty_in + $row->qty),2);
					
	// 				if($cek->date == $date){
	// 					$cek->update([
	// 						'qty_in'		=> $cek->qty_in + $row->qty,
	// 						'price_in'		=> $pricein,
	// 						'total_in'		=> ($cek->qty_in + $row->qty) * $pricein,
	// 						'qty_final'		=> $cek->qty_final + $row->qty,
	// 						'price_final'	=> $priceperqty,
	// 						'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty)
	// 					]);
	// 				}else{
	// 					ProductCogs::create([
	// 						'product_id'	=> $row->product_id,
	// 						'branch'		=> $branch,
	// 						'qty_in'		=> $row->qty,
	// 						'price_in'		=> $pricenow,
	// 						'total_in'		=> $row->qty * $pricenow,
	// 						'qty_final'		=> $cek->qty_final + $row->qty,
	// 						'price_final'	=> $priceperqty,
	// 						'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty),
	// 						'date'			=> $date,
	// 						'type'			=> 'WR'
	// 					]);
	// 				}
	// 			}else{
	// 				ProductCogs::create([
	// 					'product_id'	=> $row->product_id,
	// 					'branch'		=> $branch,
	// 					'qty_in'		=> $row->qty,
	// 					'price_in'		=> $pricenow,
	// 					'total_in'		=> $row->qty * $pricenow,
	// 					'qty_final'		=> $row->qty,
	// 					'price_final'	=> $pricenow,
	// 					'total_final'	=> $row->qty * $pricenow,
	// 					'date'			=> $date,
	// 					'type'			=> 'WR'
	// 				]);
	// 			}
	// 		}
			
	// 		$dataInTp = TransferProduct::whereHas('transfer', function($query) use ($date,$branch) {
	// 			$query->where('branch',$branch)->whereNotNull('for_starting')->whereDate('date',$date);
	// 		})->orWhereHas('transfer', function($query) use ($date,$branch) {
	// 			$query->where('branch',$branch)->whereNotNull('for_in_transfer')->whereDate('date',$date);
	// 		})->get();
			
	// 		foreach($dataInTp as $row){
	// 			$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
	// 			$pricenow = round($row->price / $row->qty,2);
				
	// 			if($cek){
					
	// 			$totalold = $cek->total_final;
	// 			$totalnew = $row->qty * $pricenow;
	// 			$priceperqty = $cek->qty_final + $row->qty > 0 ? round(($totalold + $totalnew) / ($cek->qty_final + $row->qty),2) : $totalnew;
	// 			$pricein = round(($cek->total_in + $row->price) / ($cek->qty_in + $row->qty),2);
					
	// 				if($cek->date == $date){
	// 					$cek->update([
	// 						'qty_in'		=> $cek->qty_in + $row->qty,
	// 						'price_in'		=> $pricein,
	// 						'total_in'		=> ($cek->qty_in + $row->qty) * $pricein,
	// 						'qty_final'		=> $cek->qty_final + $row->qty,
	// 						'price_final'	=> $priceperqty,
	// 						'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty)
	// 					]);
	// 				}else{
	// 					ProductCogs::create([
	// 						'product_id'	=> $row->product_id,
	// 						'branch'		=> $branch,
	// 						'qty_in'		=> $row->qty,
	// 						'price_in'		=> $pricenow,
	// 						'total_in'		=> $row->qty * $pricenow,
	// 						'qty_final'		=> $cek->qty_final + $row->qty,
	// 						'price_final'	=> $priceperqty,
	// 						'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty),
	// 						'date'			=> $date,
	// 						'type'			=> 'WE-IN'
	// 					]);
	// 				}
	// 			}else{
	// 				ProductCogs::create([
	// 					'product_id'	=> $row->product_id,
	// 					'branch'		=> $branch,
	// 					'qty_in'		=> $row->qty,
	// 					'price_in'		=> $pricenow,
	// 					'total_in'		=> $row->qty * $pricenow,
	// 					'qty_final'		=> $row->qty,
	// 					'price_final'	=> $pricenow,
	// 					'total_final'	=> $row->qty * $pricenow,
	// 					'date'			=> $date,
	// 					'type'			=> 'WE-IN'
	// 				]);
	// 			}
	// 		}
			
	// 		$dataOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use($date,$branch){
	// 			$query->whereHas('projectSale',function($query) use($date,$branch){
	// 				$query->whereHas('sales',function($query) use($date,$branch){
	// 					$query->where('branch',$branch);
	// 				});
	// 			})->whereRaw("DATE(received_date) = '$date'");
	// 		})->get();
			
	// 		foreach($dataOut as $row){
	// 			$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
	// 			if($cek){
	// 				$pricenow = $cek->price_final;
					
	// 				ProductCogs::create([
	// 					'product_id'	=> $row->product_id,
	// 					'branch'		=> $branch,
	// 					'qty_out'		=> $row->qty,
	// 					'price_out'		=> $pricenow,
	// 					'total_out'		=> $row->qty * $pricenow,
	// 					'qty_final'		=> $cek->qty_final - $row->qty,
	// 					'price_final'	=> $pricenow,
	// 					'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
	// 					'date'			=> $date,
	// 					'type'			=> 'DO'
	// 				]);
	// 			}else{
	// 				$purchase = ProjectPurchase::whereHas('projectPurchaseProduct', function($query) use($row){
	// 					$query->where('product_id', $row->product_id);
	// 				})
	// 				->where('project_id', $row->projectDelivery->project_id)
	// 				->first();
					
	// 				if($purchase){
	// 					$purchase_product = ProjectPurchaseProduct::where('project_purchase_id',$purchase->id)->where('product_id', $row->product_id)->first();

	// 					$real_price = str_replace(',','.',str_replace('.','', $purchase_product->purchaseReal()));

	// 					ProductCogs::create([
	// 						'product_id'	=> $row->product_id,
	// 						'branch'		=> $branch,
	// 						'qty_out'		=> $row->qty,
	// 						'price_out'		=> $real_price,
	// 						'total_out'		=> $row->qty * $real_price,
	// 						'qty_final'		=> -$row->qty,
	// 						'price_final'	=> $real_price,
	// 						'total_final'	=> $row->qty * $real_price,
	// 						'date'			=> $date,
	// 						'type'			=> 'DO'
	// 					]);
	// 				}
					
	// 			}
	// 		}
			
	// 		$dataInRt = ProjectSaleReturnProduct::whereHas('projectSaleReturn', function($query) use ($branch) {
	// 			$query->whereHas('projectSale', function($query) use ($branch) {
	// 				$query->whereHas('sales', function($query) use ($branch) {
	// 					$query->where('branch',$branch);
	// 				});
	// 			});
	// 		})->whereHas('projectSaleReturn',function($query) use($date,$branch){
	// 			$query->where('date_return',$date);
	// 		})
	// 		->get();
			
	// 		foreach($dataInRt as $row){
	// 			$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$row->getDateDelivery())->orderByDesc('id')->first();
	// 			$cek2 = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
	// 			if($cek){
	// 				$pricenow = $cek->price_final;
	// 				$pricenew = $cek2->qty_final + $row->qty > 0 ? ($cek2->total_final + ($row->qty * $pricenow)) / ($cek2->qty_final + $row->qty) : $row->qty * $pricenow;
					
	// 				ProductCogs::create([
	// 					'product_id'	=> $row->product_id,
	// 					'branch'		=> $branch,
	// 					'qty_in'		=> $row->qty,
	// 					'price_in'		=> $pricenow,
	// 					'total_in'		=> $row->qty * $pricenow,
	// 					'qty_final'		=> $cek2->qty_final + $row->qty,
	// 					'price_final'	=> $pricenew,
	// 					'total_final'	=> ($cek2->qty_final + $row->qty) * $pricenew,
	// 					'date'			=> $date,
	// 					'type'			=> 'SR'
	// 				]);
	// 			}
	// 		}
			
	// 		$dataOutRt = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn', function($query) use ($branch) {
	// 			$query->whereHas('projectPurchase', function($query) use ($branch) {
	// 				$query->whereHas('sales', function($query) use ($branch) {
	// 					$query->where('branch',$branch);
	// 				});
	// 			});
	// 		})->whereHas('projectPurchaseReturn',function($query) use($date){
	// 			$query->where('date',$date);
	// 		})
	// 		->get();
			
	// 		foreach($dataOutRt as $row){
	// 			$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
	// 			if($cek){
	// 				$pricenow = $cek->price_final;
					
	// 				ProductCogs::create([
	// 					'product_id'	=> $row->product_id,
	// 					'branch'		=> $branch,
	// 					'qty_out'		=> $row->qty,
	// 					'price_out'		=> $pricenow,
	// 					'total_out'		=> $row->qty * $pricenow,
	// 					'qty_final'		=> $cek->qty_final - $row->qty,
	// 					'price_final'	=> $pricenow,
	// 					'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
	// 					'date'			=> $date,
	// 					'type'			=> 'PR'
	// 				]);
	// 			}
	// 		}
			
	// 		$dataOutTp = TransferProduct::whereHas('transfer', function($query) use ($branch,$date) {
	// 			$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','3')->whereDate('date',$date);
	// 		})->orWhereHas('transfer', function($query) use ($branch,$date) {
	// 			$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','2')->whereDate('date',$date);
	// 		})->orWhereHas('transfer', function($query) use ($branch,$date) {
	// 			$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','1')->whereDate('date',$date);
	// 		})->orWhereHas('transfer', function($query) use ($branch,$date) {
	// 			$query->where('branch',$branch)->whereNotNull('for_correction')->whereDate('date',$date);
	// 		})->orWhereHas('transfer', function($query) use ($branch,$date) {
	// 			$query->where('branch',$branch)->whereNotNull('for_out_transfer')->whereDate('date',$date);
	// 		})->orWhereHas('transfer', function($query) use ($branch,$date) {
	// 			$query->where('branch',$branch)->whereNotNull('for_broken')->whereDate('date',$date);
	// 		})
	// 		->get();
		
	// 		foreach($dataOutTp as $row){
	// 			$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
	// 			if($cek){
	// 				$pricenow = $cek->price_final;
					
	// 				ProductCogs::create([
	// 					'product_id'	=> $row->product_id,
	// 					'branch'		=> $branch,
	// 					'qty_out'		=> $row->qty,
	// 					'price_out'		=> $pricenow,
	// 					'total_out'		=> $row->qty * $pricenow,
	// 					'qty_final'		=> $cek->qty_final - $row->qty,
	// 					'price_final'	=> $pricenow,
	// 					'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
	// 					'date'			=> $date,
	// 					'type'			=> 'WE-OUT'
	// 				]);
	// 			}
	// 		}
			
	// 		$startDate->addDay();
	// 	}
	// 	echo '<table border="1" style="border-collapse: collapse;">
	// 			<tr>
	// 				<td rowspan="2" align="center">Product</td>
	// 				<td rowspan="2" align="center">Branch</td>
	// 				<td rowspan="2" align="center">Date</td>
	// 				<td rowspan="2" align="center">Type</td>
	// 				<td colspan="3" align="center">IN</td>
	// 				<td colspan="3" align="center">OUT</td>
	// 				<td colspan="3" align="center">FINAL</td>
	// 			</tr>
	// 			<tr>
	// 				<td align="center">Qty</td>
	// 				<td align="center">Price</td>
	// 				<td align="center">Total</td>
	// 				<td align="center">Qty</td>
	// 				<td align="center">Price</td>
	// 				<td align="center">Total</td>
	// 				<td align="center">Qty</td>
	// 				<td align="center">Price</td>
	// 				<td align="center">Total</td>
	// 			</tr>
	// 	';
		
	// 	foreach(ProductCogs::where('branch',$branch)->orderBy('date')->get() as $row){
	// 		echo '<tr>';
	// 			echo '<td>'.$row->product->name().' | '.$row->product_id.'</td>';
	// 			echo '<td>'.$row->branch.'</td>';
	// 			echo '<td>'.date("d M Y",strtotime($row->date)).'</td>';
	// 			echo '<td>'.$row->type.'</td>';
	// 			echo '<td align="center">'.$row->qty_in.'</td>';
	// 			echo '<td align="right">'.number_format($row->price_in,2,'.',',').'</td>';
	// 			echo '<td align="right">'.number_format($row->total_in,2,'.',',').'</td>';
	// 			echo '<td align="center">'.$row->qty_out.'</td>';
	// 			echo '<td align="right">'.number_format($row->price_out,2,'.',',').'</td>';
	// 			echo '<td align="right">'.number_format($row->total_out,2,'.',',').'</td>';
	// 			echo '<td align="center">'.$row->qty_final.'</td>';
	// 			echo '<td align="right">'.number_format($row->price_final,2,'.',',').'</td>';
	// 			echo '<td align="right">'.number_format($row->total_final,2,'.',',').'</td>';
	// 		echo '</tr>';
	// 	}
		
	// 	echo '</table>';
		
	// }

	public function repair(Request $request)
    {
		set_time_limit(300);
		// ProductCogs::truncate();
		
		$branch = '4';
		
		$startDateReal = new Carbon('2024-07-01'); 
		$endDateReal = new Carbon('2024-010-29');
		
		$startDate =  new Carbon('2024-01-01'); 
		$endDate = new Carbon('2024-02-29');
		
		while ($startDate->lte($endDate)){
			$date = $startDate->toDateString();
			ProductCogs::where('branch',$branch)->where('date','=',$date)->delete();

			$dataIn = ProjectWarehouseProduct::whereHas('projectWarehouse',function($query) use($date,$branch){
				$query->whereHas('projectPurchase',function($query) use($date,$branch){
					$query->whereHas('sales',function($query) use($date,$branch){
						$query->where('branch',$branch);
					});
				})->whereDate('date_receive',$date);
			})->get();
			
			foreach($dataIn as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				$pricenow = str_replace(',','.',str_replace('.','',$row->purchaseReal()));
				
				if($cek){
					
					$totalold = $cek->total_final;
					$totalnew = $row->qty * $pricenow;
					$priceperqty = $cek->qty_final + $row->qty > 0 ? round(($totalold + $totalnew) / ($cek->qty_final + $row->qty),2) : $totalnew;
					$qty_now = $cek->qty_in + $row->qty ? $cek->qty_in + $row->qty : 1;
					$pricein = round(($cek->total_in + ($pricenow * $row->qty)) / $qty_now,2);
					
					if($cek->date == $date){
						$cek->update([
							'qty_in'		=> $cek->qty_in + $row->qty,
							'price_in'		=> $pricein,
							'total_in'		=> ($cek->qty_in + $row->qty) * $pricein,
							'qty_final'		=> $cek->qty_final + $row->qty,
							'price_final'	=> $priceperqty,
							'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty)
						]);
					}else{
						ProductCogs::create([
							'product_id'	=> $row->product_id,
							'branch'		=> $branch,
							'qty_in'		=> $row->qty,
							'price_in'		=> $pricenow,
							'total_in'		=> $row->qty * $pricenow,
							'qty_final'		=> $cek->qty_final + $row->qty,
							'price_final'	=> $priceperqty,
							'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty),
							'date'			=> $date,
							'type'			=> 'WR'
						]);
					}
				}else{
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_in'		=> $row->qty,
						'price_in'		=> $pricenow,
						'total_in'		=> $row->qty * $pricenow,
						'qty_final'		=> $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> $row->qty * $pricenow,
						'date'			=> $date,
						'type'			=> 'WR'
					]);
				}
			}
			
			$dataInTp = TransferProduct::whereHas('transfer', function($query) use ($date,$branch) {
				$query->where('branch',$branch)->whereNotNull('for_starting')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($date,$branch) {
				$query->where('branch',$branch)->whereNotNull('for_in_transfer')->whereDate('date',$date);
			})->get();
			
			foreach($dataInTp as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				$pricenow = round($row->price / $row->qty,2);
				
				if($cek){
					
				$totalold = $cek->total_final;
				$totalnew = $row->qty * $pricenow;
				$priceperqty = $cek->qty_final + $row->qty > 0 ? round(($totalold + $totalnew) / ($cek->qty_final + $row->qty),2) : $totalnew;
				$qty_now = $cek->qty_in + $row->qty ? $cek->qty_in + $row->qty : 1;
				$pricein = round(($cek->total_in + $row->price) / ($cek->qty_in + $row->qty),2);
					
					if($cek->date == $date){
						$cek->update([
							'qty_in'		=> $cek->qty_in + $row->qty,
							'price_in'		=> $pricein,
							'total_in'		=> ($cek->qty_in + $row->qty) * $pricein,
							'qty_final'		=> $cek->qty_final + $row->qty,
							'price_final'	=> $priceperqty,
							'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty)
						]);
					}else{
						ProductCogs::create([
							'product_id'	=> $row->product_id,
							'branch'		=> $branch,
							'qty_in'		=> $row->qty,
							'price_in'		=> $pricenow,
							'total_in'		=> $row->qty * $pricenow,
							'qty_final'		=> $cek->qty_final + $row->qty,
							'price_final'	=> $priceperqty,
							'total_final'	=> $priceperqty * ($cek->qty_final + $row->qty),
							'date'			=> $date,
							'type'			=> 'WE-IN'
						]);
					}
				}else{
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_in'		=> $row->qty,
						'price_in'		=> $pricenow,
						'total_in'		=> $row->qty * $pricenow,
						'qty_final'		=> $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> $row->qty * $pricenow,
						'date'			=> $date,
						'type'			=> 'WE-IN'
					]);
				}
			}
			
			$dataOut = ProjectDeliveryProduct::whereHas('projectDelivery',function($query) use($date,$branch){
				$query->whereHas('projectSale',function($query) use($date,$branch){
					$query->whereHas('sales',function($query) use($date,$branch){
						$query->where('branch',$branch);
					});
				})->whereRaw("DATE(received_date) = '$date'");
			})->get();
			
			foreach($dataOut as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				if($cek){
					$pricenow = $cek->price_final;
					
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_out'		=> $row->qty,
						'price_out'		=> $pricenow,
						'total_out'		=> $row->qty * $pricenow,
						'qty_final'		=> $cek->qty_final - $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
						'date'			=> $date,
						'type'			=> 'DO'
					]);
				}else{
					$purchase = ProjectPurchase::whereHas('projectPurchaseProduct', function($query) use($row){
						$query->where('product_id', $row->product_id);
					})
					->where('project_id', $row->projectDelivery->project_id)
					->first();
					
					if($purchase){
						$purchase_product = ProjectPurchaseProduct::where('project_purchase_id',$purchase->id)->where('product_id', $row->product_id)->first();

						$real_price = str_replace(',','.',str_replace('.','', $purchase_product->purchaseReal()));

						ProductCogs::create([
							'product_id'	=> $row->product_id,
							'branch'		=> $branch,
							'qty_out'		=> $row->qty,
							'price_out'		=> $real_price,
							'total_out'		=> $row->qty * $real_price,
							'qty_final'		=> -$row->qty,
							'price_final'	=> $real_price,
							'total_final'	=> $row->qty * $real_price,
							'date'			=> $date,
							'type'			=> 'DO'
						]);
					}
					
				}
			}
			
			$dataInRt = ProjectSaleReturnProduct::whereHas('projectSaleReturn', function($query) use ($branch) {
				$query->whereHas('projectSale', function($query) use ($branch) {
					$query->whereHas('sales', function($query) use ($branch) {
						$query->where('branch',$branch);
					});
				});
			})->whereHas('projectSaleReturn',function($query) use($date,$branch){
				$query->where('date_return',$date);
			})
			->get();
			
			foreach($dataInRt as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$row->getDateDelivery())->orderByDesc('id')->first();
				$cek2 = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				if($cek){
					$pricenow = $cek->price_final;
					$pricenew = $cek2->qty_final + $row->qty > 0 ? ($cek2->total_final + ($row->qty * $pricenow)) / ($cek2->qty_final + $row->qty) : $pricenow;
					
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_in'		=> $row->qty,
						'price_in'		=> $pricenow,
						'total_in'		=> $row->qty * $pricenow,
						'qty_final'		=> $cek2->qty_final + $row->qty,
						'price_final'	=> $pricenew,
						'total_final'	=> ($cek2->qty_final + $row->qty) * $pricenew,
						'date'			=> $date,
						'type'			=> 'SR'
					]);
				}
			}
			
			$dataOutRt = ProjectPurchaseReturnProduct::whereHas('projectPurchaseReturn', function($query) use ($branch) {
				$query->whereHas('projectPurchase', function($query) use ($branch) {
					$query->whereHas('sales', function($query) use ($branch) {
						$query->where('branch',$branch);
					});
				});
			})->whereHas('projectPurchaseReturn',function($query) use($date){
				$query->where('date',$date);
			})
			->get();
			
			foreach($dataOutRt as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				if($cek){
					$pricenow = $cek->price_final;
					
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_out'		=> $row->qty,
						'price_out'		=> $pricenow,
						'total_out'		=> $row->qty * $pricenow,
						'qty_final'		=> $cek->qty_final - $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
						'date'			=> $date,
						'type'			=> 'PR'
					]);
				}
			}
			
			$dataOutTp = TransferProduct::whereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','3')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','2')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_customer')->where('status','1')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_correction')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_out_transfer')->whereDate('date',$date);
			})->orWhereHas('transfer', function($query) use ($branch,$date) {
				$query->where('branch',$branch)->whereNotNull('for_broken')->whereDate('date',$date);
			})
			->get();
		
			foreach($dataOutTp as $row){
				$cek = ProductCogs::where('product_id',$row->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
				
				if($cek){
					$pricenow = $cek->price_final;
					
					ProductCogs::create([
						'product_id'	=> $row->product_id,
						'branch'		=> $branch,
						'qty_out'		=> $row->qty,
						'price_out'		=> $pricenow,
						'total_out'		=> $row->qty * $pricenow,
						'qty_final'		=> $cek->qty_final - $row->qty,
						'price_final'	=> $pricenow,
						'total_final'	=> ($cek->qty_final - $row->qty) * $pricenow,
						'date'			=> $date,
						'type'			=> 'WE-OUT'
					]);
				}
			}
			
			$startDate->addDay();
		}
		echo '<table border="1" style="border-collapse: collapse;">
				<tr>
					<td rowspan="2" align="center">Product</td>
					<td rowspan="2" align="center">Branch</td>
					<td rowspan="2" align="center">Date</td>
					<td rowspan="2" align="center">Type</td>
					<td colspan="3" align="center">IN</td>
					<td colspan="3" align="center">OUT</td>
					<td colspan="3" align="center">FINAL</td>
				</tr>
				<tr>
					<td align="center">Qty</td>
					<td align="center">Price</td>
					<td align="center">Total</td>
					<td align="center">Qty</td>
					<td align="center">Price</td>
					<td align="center">Total</td>
					<td align="center">Qty</td>
					<td align="center">Price</td>
					<td align="center">Total</td>
				</tr>
		';
		
		foreach(ProductCogs::where('branch', $branch)
		->whereBetween('date', [$startDateReal->format('Y-m-d'), $endDateReal->format('Y-m-d')])
		->orderBy('date')
		->get() as $row){
			echo '<tr>';
				echo '<td>'.$row->product->name().' | '.$row->product_id.'</td>';
				echo '<td>'.$row->branch.'</td>';
				echo '<td>'.date("d M Y",strtotime($row->date)).'</td>';
				echo '<td>'.$row->type.'</td>';
				echo '<td align="center">'.$row->qty_in.'</td>';
				echo '<td align="right">'.number_format($row->price_in,2,'.',',').'</td>';
				echo '<td align="right">'.number_format($row->total_in,2,'.',',').'</td>';
				echo '<td align="center">'.$row->qty_out.'</td>';
				echo '<td align="right">'.number_format($row->price_out,2,'.',',').'</td>';
				echo '<td align="right">'.number_format($row->total_out,2,'.',',').'</td>';
				echo '<td align="center">'.$row->qty_final.'</td>';
				echo '<td align="right">'.number_format($row->price_final,2,'.',',').'</td>';
				echo '<td align="right">'.number_format($row->total_final,2,'.',',').'</td>';
			echo '</tr>';
		}
		
		echo '</table>';
		
	}

	
	public function RepairCBInventory(){
		set_time_limit(300);
		$branch = '4';
		
		$startDate = new Carbon('2024-01-01');
		$endDate = new Carbon('2024-02-29');
		
		while ($startDate->lte($endDate)){
			$date = $startDate->toDateString();
			
			$projectPurchaseReturns = ProjectPurchaseReturn::where('date', $date)->whereHas('projectPurchase', function($query) use($branch){
				$query->whereHas('sales', function($query) use ($branch){
					$query->where('branch', $branch);
				});
			})->get();
			
			$projectSaleReturns = ProjectSaleReturn::where('date_return', $date)
			->whereHas('projectSale', function($query) use($branch){
				$query->whereHas('sales', function($query) use ($branch){
					$query->where('branch', $branch);
				});
			})->get();
			
			$projectDeliveries = ProjectDelivery::where('received_date', $date)->whereHas('projectSale', function($query) use($branch){
				$query->whereHas('sales', function($query) use ($branch){
					$query->where('branch', $branch);
				});
			})->whereNotNull('received_date')
			->get();


			foreach ($projectDeliveries as $projectDelivery) {
				$price_final = 0;
				
				foreach ($projectDelivery->projectDeliveryProduct as $product) {
					$product_cogs = ProductCogs::where('product_id',$product->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
					$price_final += $product_cogs ? $product_cogs->price_final * $product->qty : 0;
			
				}

				if($product_cogs){
					$cbd = CashBankDetail::whereHas('cashBank', function ($query) use ($projectDelivery) {
						$query->where('lookable_type', 'project_deliveries')
							->where('lookable_id', $projectDelivery->id);
					})
					->whereIn('coa_id', [31, 122])
					->get();
					
					$cashBankIds = $cbd->pluck('cash_bank_id')->first();
				
			
					$cbd->each(function ($detail) use ($price_final) {
						$detail->update(['nominal' => round($price_final)]);
					});
	
	
					Journal::where('journalable_type', 'cash_banks')
						->where('journalable_id', $cashBankIds)
						->whereIn('coa_id', [31, 122])
						->update(['nominal' => round($price_final)]);
				}

			}

			foreach ($projectPurchaseReturns as $projectPurchaseReturn) {
				$price_final = 0;

				foreach ($projectPurchaseReturn->projectPurchaseReturnProduct as $product) {
				$product_cogs = ProductCogs::where('product_id',$product->product_id)->where('branch',$branch)->where('date','<=',$date)->orderByDesc('id')->first();
					$price_final += $product_cogs->price_final * $product->qty;
				}

				// Step 1: Retrieve CashBankDetail records and collect their cash_bank_id values
				$cbd = CashBankDetail::whereHas('cashBank', function ($query) use ($projectPurchaseReturn) {
					$query->where('lookable_type', 'project_purchase_returns')
						->where('lookable_id', $projectPurchaseReturn->id);
				})->get();

				// Collect unique cash_bank_id values
				$cashBankIds = $cbd->pluck('cash_bank_id')->unique();

				
				$persenppn  = date('Y-m-d', strtotime($projectPurchaseReturn->date)) < '2022-04-01' ? 0.1 : 0.11;
				$getDebitExceptPayable = 0;
				
				if ($projectPurchaseReturn->projectPurchase->ppn == '1') {
					$ppn = $price_final * $persenppn ;

					
					$cbd->where('coa_id', 50)->each(function ($detail) use ($ppn) {
						$detail->update(['nominal' => round($ppn)]);
					});

				
					Journal::where('journalable_type', 'cash_banks')
						->where('journalable_id', $cashBankIds)
						->where('coa_id', 50)
						->update(['nominal' => $ppn]);

				
					$cbd->where('coa_id', 31)->each(function ($detail) use ($price_final) {
						$detail->update(['nominal' => $price_final]);
					});

					Journal::where('journalable_type', 'cash_banks')
						->where('journalable_id', $cashBankIds)
						->where('coa_id', 31)
						->update(['nominal' => $price_final]);

					$cbd->where('coa_id', '!=', 332)->where('type', 1)->each(function ($detail) use (&$getDebitExceptPayable) {
						$getDebitExceptPayable += $detail->nominal;
					});
	
	
					$cbd->where('coa_id', 332)->each(function ($detail) use ($ppn, $price_final, $getDebitExceptPayable) {
						$detail->update(['nominal' => round(($ppn + $price_final) - $getDebitExceptPayable)]);
					});
	
					Journal::where('journalable_type', 'cash_banks')
						->where('journalable_id', $cashBankIds)
						->where('coa_id', 332)
						->update(['nominal' => round(($ppn + $price_final) - $getDebitExceptPayable)]);
				} else {
				
					$cbd->where('coa_id', 31)->each(function ($detail) use ($price_final) {
						$detail->update(['nominal' => round($price_final)]);
					});

					Journal::where('journalable_type', 'cash_banks')
						->where('journalable_id', $cashBankIds)
						->where('coa_id', 31)
						->update(['nominal' => round($price_final)]);


						$cbd->where('coa_id', '!=', 332)->where('type', 1)->each(function ($detail) use (&$getDebitExceptPayable) {
							$getDebitExceptPayable += $detail->nominal;
						});
		
		
						$cbd->where('coa_id', 332)->each(function ($detail) use ($price_final, $getDebitExceptPayable) {
							$detail->update(['nominal' => round($price_final - $getDebitExceptPayable)]);
						});
		
						Journal::where('journalable_type', 'cash_banks')
							->where('journalable_id', $cashBankIds)
							->where('coa_id', 332)
							->update(['nominal' => round($price_final - $getDebitExceptPayable)]);
		
				}

			
				
				
			}

			foreach ($projectSaleReturns as $projectSaleReturn) {
				$price_final = 0;

				foreach ($projectSaleReturn->projectSaleReturnProduct as $product) {
					$product_cogs = ProductCogs::where('product_id',$product->product_id)->where('branch',$branch)->where('date','<=',$product->getDateDelivery())->orderByDesc('id')->first();
					$price_final += $product_cogs->price_final * $product->qty;
				}

				$cbd = CashBankDetail::whereHas('cashBank', function ($query) use ($projectSaleReturn) {
					$query->where('lookable_type', 'project_sale_returns')
						  ->where('lookable_id', $projectSaleReturn->id); // Changed to lookable_id for correct filtering
				})
				->whereIn('coa_id', [31, 122])
				->get();
				
				$cashBankIds = $cbd->pluck('cash_bank_id')->first();
			
				$cbd->each(function ($detail) use ($price_final) {
					$detail->update(['nominal' => round($price_final)]);
				});


				Journal::where('journalable_type', 'cash_banks')
					->where('journalable_id', $cashBankIds)
					->whereIn('coa_id', [31, 122])
					->update(['nominal' => round($price_final)]);

			}

	
					
			$startDate->addDay();
		}
		
		
		echo 'Done';

	}
	
	public function getWarehouseReceive(Request $request){
		$idpo = $request->id;
		
		$purchase = ProjectPurchase::find($idpo);
		
		$result = [];
		
		foreach($purchase->projectWarehouse as $row){
			$pr = '';
											
			if($row->purchaseRequest()->exists()){
				$pr = number_format($row->purchaseRequest->total_nominal,2,',','.');
			}
			
			$result[] = [
				'id' 	=> $row->id,
				'name'	=> $row->code.' - PO '.$row->projectPurchase->code.' Supp.'.$row->projectPurchase->supplier->name.' '.$pr
			];
		}
		
		return response()->json($result);
	}
	
	public function getProforma(Request $request){
		$idpo = $request->idpo;
		
		$proforma = ProjectProforma::where('project_purchase_id',$idpo)->get();
		
		$result = [];
		
		foreach($proforma as $row){
			$result[] = [
				'purchase_code' 		=> $row->projectPurchase->code,
				'sales_code'			=> $row->projectSale ? $row->projectSale->code : '',
				'date'					=> date('d M Y',strtotime($row->date)),
				'supplier_name'			=> $row->supplier_name,
				'supplier_warehouse'	=> $row->supplier_warehouse,
				'attachment'			=> $row->attachment(),
			];
		}
		
		return response()->json($result);
	}
	
	public function getBill(Request $request){
		$idpo = $request->idpo;
		
		$bill = ProjectPurchaseBill::where('project_purchase_id',$idpo)->get();
		
		$result = [];
		
		foreach($bill as $row){
			$result[] = [
				'id'					=> $row->id,
				'purchase_code' 		=> $row->projectPurchase->code,
				'no_document'			=> $row->no_document,
				'date'					=> date('d M Y',strtotime($row->date)),
				'due_date'				=> date('d M Y',strtotime($row->due_date)),
				'method'				=> $row->method(),
				'nominal'				=> number_format($row->nominal,2,',','.'),
				'note'					=> $row->note,
				'attachment'			=> $row->attachment(),
			];
		}
		
		return response()->json($result);
	}

	public function getPurchaseRequestFromCoa(Request $request){
		$purchaseRequests = PurchaseRequest::where('wip_coa_id', $request->wip_coa)->get();
		$data = [];

		foreach ($purchaseRequests as $purchase_request) {
			$data[] = [
				'id' 	  => $purchase_request->id,
				'detail'  => $purchase_request->item,
				'nominal' => $purchase_request->totalPayment() > 0 ? $purchase_request->totalPayment() : $purchase_request->total_nominal,
				'to' 	  => $purchase_request->bill_to,
			];
		}

		$response = [
			'status'  => 200,
			'message' => 'Success',
			'data'	  => $data,
		];

		return response()->json($response);
	}


	public function deleteCost(Request $request){
		
		$pcd = PurchaseCostDetail::where('purchase_request_id', $request->purchase_request_id)->first();
		$pc = PurchaseCost::find($pcd->purchase_cost_id);
		$pcd->delete();
		
		if(count($pc->purchaseCostDetail()->get()) == 0){
			$pc->delete();
		}

		$response = [
			'status'  => 200,
			'message' => "Successfully deleted"
		];

		return response()->json($response);
	}
}
