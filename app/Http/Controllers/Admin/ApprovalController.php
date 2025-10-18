<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Transfer;
use App\Models\BalanceHistory;
use App\Models\BudgetingProject;
use App\Models\Coa;
use App\Models\Project;
use App\Models\ProjectPurchaseBill;
use App\Models\ProjectLog;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Journal;
use App\Models\PaymentRequest;
use App\Models\ReceivablePayment;
use App\Models\PurchaseRequest;
use App\Models\ProjectSale;
use App\Models\ProjectSaleProduct;
use App\Models\ProjectSaleShading;
use App\Models\ProjectSaleTemp;
use App\Models\ProjectSample;
use App\Models\ProjectPay;
use App\Models\ProjectMainPayment;
use App\Models\ProjectBill;
use App\Models\ProjectQuotation;
use App\Models\ProjectPayment;
use App\Models\ProjectFromStock;
use App\Models\ProjectPurchase;
use App\Models\ProjectDelivery;
use App\Models\ProjectPurchaseReturn;
use App\Models\ProjectSaleReturn;
use App\Models\Approval;
use App\Models\LeaveRequest;
use App\Models\OrderDetail;
use App\Models\Notification;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\SendMessage;
use App\Models\ProjectPurchaseQuotation;
use App\Models\Sample;
use App\Models\SampleDelivery;
use App\Models\SamplePurchase;
use App\Models\SamplePurchaseReturn;
use App\Models\ServiceCost;
use App\Models\ServiceCostPayment;
use Illuminate\Support\Str;

class ApprovalController extends Controller {

    public function index()
    {
		$app = Approval::where('user_id',session('bo_id'))->get();
		
		foreach($app as $approve){
			if($approve->approvalable){
				
			}else{
				$approve->delete();
			}
		}
		
        $data = [
            'title'   => 'Approval',
            'content' => 'admin.approval'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'approvalable_type',
            'ref',
            'created_at',
            'approved_by'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		if(in_array(1, session('bo_role'))){
			$total_data = Approval::count();
			
			$query_data = Approval::where(function($query) use ($search, $request) {
					if($search) {
						$query->whereHas('approvedBy', function($query) use ($search) {
								$query->where('name', 'like', "%$search%");
							})
							->orWhereHas('references', function($query) use ($search){
									$query->where('name', 'like', "%$search%");
							})
							->orWhere('approvalable_type', 'like', "%$search%")
							->orWhereHasMorph('approvalable',
								[Project::class, ProjectSale::class, ProjectPurchase::class, Transfer::class, ProjectMainPayment::class, ProjectBill::class, ProjectPay::class,ProjectDelivery::class],
								function (Builder $query) use ($search) {
									$query->where('code','like',"%$search%");
								});
					}   
					
					if($request->type) {
						$query->where('approvalable_type', $request->type);
					}

					if($request->start_date && $request->finish_date) {
						$query->whereDate('created_at', '>=', $request->start_date)
							->whereDate('created_at', '<=', $request->finish_date);
					} else if($request->start_date) {
						$query->whereDate('created_at', $request->start_date);
					} else if($request->finish_date) {
						$query->whereDate('created_at', $request->finish_date);
					}
				})
				->offset($start)
				->limit($length)
				->orderBy('approved_by','asc')
				->orderBy('created_at','desc')
				//->orderBy($order, $dir)
				->get();

			$total_filtered = Approval::where(function($query) use ($search, $request) {
					if($search) {
						$query->whereHas('approvedBy', function($query) use ($search) {
								$query->where('name', 'like', "%$search%");
							})
							->orWhereHas('references', function($query) use ($search){
								$query->where('name', 'like', "%$search%");
							})
							->orWhere('approvalable_type', 'like', "%$search%")
							->orWhereHasMorph('approvalable',
								[Project::class, ProjectSale::class, ProjectPurchase::class, Transfer::class, ProjectMainPayment::class, ProjectBill::class, ProjectPay::class,ProjectDelivery::class],
								function (Builder $query) use ($search) {
									$query->where('code','like',"%$search%");
								});
					}   
					
					if($request->type) {
						$query->where('approvalable_type', $request->type);
					}

					if($request->start_date && $request->finish_date) {
						$query->whereDate('created_at', '>=', $request->start_date)
							->whereDate('created_at', '<=', $request->finish_date);
					} else if($request->start_date) {
						$query->whereDate('created_at', $request->start_date);
					} else if($request->finish_date) {
						$query->whereDate('created_at', $request->finish_date);
					}
				})
				->count();
		}else{
			$total_data = Approval::where('user_id', session('bo_id'))
            ->count();
        
			$query_data = Approval::where('user_id', session('bo_id'))
				->where(function($query) use ($search, $request) {
					if($search) {
						$query->where('approvalable_id', 'like', "%$search%")
							->orWhereHas('approvedBy', function($query) use ($search) {
								$query->where('name', 'like', "%$search%");
							})
							->orWhereHas('references', function($query) use ($search){
									$query->where('name', 'like', "%$search%");
							})
							->orWhere('approvalable_type', 'like', "%$search%")
							->orWhereHasMorph('approvalable',
								[Project::class, ProjectSale::class, ProjectPurchase::class, Transfer::class, ProjectMainPayment::class, ProjectBill::class, ProjectPay::class,ProjectDelivery::class],
								function (Builder $query) use ($search) {
									$query->where('code','like',"%$search%");
								});
					}   
					
					if($request->type) {
						$query->where('approvalable_type', $request->type);
					}

					if($request->start_date && $request->finish_date) {
						$query->whereDate('created_at', '>=', $request->start_date)
							->whereDate('created_at', '<=', $request->finish_date);
					} else if($request->start_date) {
						$query->whereDate('created_at', $request->start_date);
					} else if($request->finish_date) {
						$query->whereDate('created_at', $request->finish_date);
					}
				})
				->offset($start)
				->limit($length)
				->orderBy('approved_by','asc')
				->orderBy('created_at','desc')
				//->orderBy($order, $dir)
				->get();

			$total_filtered = Approval::where('user_id', session('bo_id'))
				->where(function($query) use ($search, $request) {
					if($search) {
						$query->where('approvalable_id', 'like', "%$search%")
							->orWhereHas('approvedBy', function($query) use ($search) {
								$query->where('name', 'like', "%$search%");
							})
							->orWhereHas('references', function($query) use ($search){
								$query->where('name', 'like', "%$search%");
							})
							->orWhere('approvalable_type', 'like', "%$search%")
							->orWhereHasMorph('approvalable',
								[Project::class, ProjectSale::class, ProjectPurchase::class, Transfer::class, ProjectMainPayment::class, ProjectBill::class, ProjectPay::class,ProjectDelivery::class],
								function (Builder $query) use ($search) {
									$query->where('code','like',"%$search%");
								});
					}   
					
					if($request->type) {
						$query->where('approvalable_type', $request->type);
					}

					if($request->start_date && $request->finish_date) {
						$query->whereDate('created_at', '>=', $request->start_date)
							->whereDate('created_at', '<=', $request->finish_date);
					} else if($request->start_date) {
						$query->whereDate('created_at', $request->start_date);
					} else if($request->finish_date) {
						$query->whereDate('created_at', $request->finish_date);
					}
				})
				->count();
		}
        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				$check = Approval::where('approvalable_type', $val->approvalable_type)
                    ->where('approvalable_id', $val->approvalable_id)
					->where('column_name', $val->column_name)
                    ->whereNotNull('approved_by')
                    ->first();
				
                if($check) {
                    $approved_by = $check->approvedBy->name;
                    $btn         = '<a href="'.url('admin/approval/detail/').'/'.$val->id.'" class="btn bg-info btn-sm">Done</a>';
                } else {
                    $approved_by = '-';
					$btn = '<a href="'.url('admin/approval/detail/').'/'.$val->id.'" class="btn bg-info btn-sm"><i class="icon-info22"></i> Process</a>';
                }
				
				$customername = '';
				
				if($val->approvalable_type == 'payment_requests'){
					
				}else{
					if(isset($val->approvalable->project->customer->name)){
						$customername = $val->approvalable->project->customer->name;
					}elseif(isset($val->approvalable->customer->name)){
						$customername = $val->approvalable->customer->name;
					}elseif(isset($val->approvalable->customerSample->customer->name)){
						$customername = $val->approvalable->customerSample->customer->name;
					}
				}
				
				$code = '';
				$color = '';
				
				if($val->approvalable_type == 'purchase_requests'){
					$code = 'PR-'.$val->approvalable->id;
					if(($val->approvalable->image == NULL && $val->approvalable->link_type !== 'project_sales') || ($val->approvalable->image == '' && $val->approvalable->link_type !== 'project_sales')){
						$color = 'bg-danger';
					}
				}else{
					if(isset($val->approvalable->code)){
						$code = $val->approvalable->code;
					}else{
						if($val->approvalable_type !== 'receivable_payments'){
							if(isset($val->approvalable->project->code)){
								$code = $val->approvalable->project->code;
							}elseif(isset($val->approvalable->customerSample->code)){
								$code = $val->approvalable->customerSample->code;
							}else{
								$code = 'EMPTY';
							}
						}
					}
				}
				
				$statuspick = true;
				
				if(in_array($val->approvalable_type,array('purchase_requests','project_from_stocks','project_main_payments','receivable_payments','service_costs', 'service_cost_payments'))){
					$statuspick = false;
				}

                $response['data'][] = [
                    '<span class="pick" data-id="'.($statuspick ? $val->id : '').'">'.$nomor.'</span>',
                    $val->type(),
					$code,
                    date('d M Y', strtotime($val->created_at)),
                    $approved_by,
                    strtoupper($customername),
					'<img src="'.$val->references->photo().'" class="rounded-circle mr-2" height="34" alt=""><br>'.$val->references->name,
                    $btn,
					$color,
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

    public function detail(Request $request, $id) 
    {
		$approval = Approval::find($id);
		$coa = Coa::all();
		$link2 = '';
		
        if(!$approval) {
            abort(404);
        }
		
        if($approval->approvalable_type == 'project_quotations') {
			
			$project = ProjectQuotation::find($approval->approvalable_id);
			$param = 'quotation_order';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Quotation';
			$subtitle = 'Project Quotation '.$approval->approvalable->project->code;
			$id_approval  = $id;
			$link = url('admin/sales/project/print/'.$param.'/'.base64_encode($project->id));
			
		}elseif($approval->approvalable_type == 'project_samples') {
			
			$project = ProjectSample::find($approval->approvalable_id);
			$param = 'sample_order';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Sample';
			$subtitle = 'Project Sample '.$approval->approvalable->code;
			$id_approval  = $id;
			
			if(in_array(9, session('bo_role'))){
				$link = url('admin/purchase_order/project/print/'.$param.'/'.base64_encode($project->id));
			}else{
				$link = url('admin/sales/project/print/'.$param.'/'.base64_encode($project->id));
			}
			
		}elseif($approval->approvalable_type == 'attendances') {
			
			$project = Attendance::find($approval->approvalable_id);
			$param = 'attendance';

			if(!$project) {
				abort(404);
			}
			
			$type = $approval->column_name == 'in_approved_by' ? 'Check In' : 'Check Out';
			
            $title = 'Approval Attendance Selfie';
			$subtitle = 'Attendance employee '.$approval->approvalable->user->name.' Date '.date('d M Y',strtotime($approval->approvalable->date)).' - Type - '.$type;
			$id_approval  = $id;
			$link = '';
			
		}elseif($approval->approvalable_type == 'attendances') {
			
			$project = Attendance::find($approval->approvalable_id);
			$param = 'attendance';

			if(!$project) {
				abort(404);
			}
			
			$type = $approval->column_name == 'in_approved_by' ? 'Check In' : 'Check Out';
			
            $title = 'Approval Attendance Selfie';
			$subtitle = 'Attendance employee '.$approval->approvalable->user->name.' Date '.date('d M Y',strtotime($approval->approvalable->date)).' - Type - '.$type;
			$id_approval  = $id;
			$link = '';
			
		}elseif($approval->approvalable_type == 'samples') {
			$project = Sample::find($approval->approvalable_id);
			$param = 'sample';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Sample';
			$subtitle = 'Sample '.$approval->approvalable->customerSample->code;
			$id_approval  = $id;
			
		    $link = url('admin/sales/sample/print/'.$param.'/'.base64_encode($project->id));
		}elseif($approval->approvalable_type == 'sample_purchases') {
			$project = SamplePurchase::find($approval->approvalable_id);
			$param = 'purchase_order';
			
			if(count($project->sampleWarehouse) > 0){
				foreach($project->sampleWarehouse as $warehouse){
					$cb = CashBank::where('lookable_type','sample_warehouses')->where('lookable_id',$warehouse->id)->get();
				}
			}

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Sample Purchase Order';

			$subtitle = 'Sample Purchase Order '.$project->code.' and Sample Order '.$project->sample->code;
			$link2= url('admin/sales/sample/print/sample/'.base64_encode($project->sample_id));
		
			$id_approval  = $id;
			$link = url('admin/sales/sample/print/'.$param.'/'.base64_encode($project->id));
		}elseif($approval->approvalable_type == "sample_purchase_returns"){
			$project = SamplePurchaseReturn::find($approval->approvalable_id);
			$param = 'purchase_return';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Sample Purchase Return';
			$subtitle = 'Sample Purchase Return '.$project->code;
			$id_approval  = $id;
			$link = url('admin/sales/sample/print/'.$param.'/'.base64_encode($project->id));
		}elseif($approval->approvalable_type == "sample_deliveries"){
			$project = SampleDelivery::find($approval->approvalable_id);
			$param = 'delivery_order';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Sample Delivery Order & Proforma Invoice';
			$subtitle = 'Sample Delivery Order '.$project->code.' & Proforma Invoice '.$project->proforma_code;
			$id_approval  = $id;
			$link = url('admin/sales/sample/print/delivery_order/'.base64_encode($project->id));
			$link2 = url('admin/sales/sample/print/sales_proforma/'.base64_encode($project->id));
		}elseif($approval->approvalable_type == 'project_sales') {
			
			$project = ProjectSale::find($approval->approvalable_id);
			$param = 'sales_order';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Sales Order';
			$subtitle = 'Project Sales Order '.$project->code;
			$id_approval  = $id;
			if(in_array(1, session('bo_role'))){
				$link = url('admin/sales/project/printHtml/'.$param.'/'.base64_encode($project->id));
				$link2 = url('admin/sales/project/printHtml/sales_cost/'.base64_encode($project->id));
				$link3 = url('admin/sales/project/printHtml/sales_news/'.base64_encode($project->id));
			}else{
				$link = url('admin/sales/project/print/'.$param.'/'.base64_encode($project->id));
				$link2 = url('admin/sales/project/print/sales_cost/'.base64_encode($project->id));
				$link3 = url('admin/sales/project/print/sales_news/'.base64_encode($project->id));
			}
			
		}
		elseif($approval->approvalable_type == 'project_sales_temps') {
			
			$project = ProjectSale::find($approval->approvalable_id);
			$projectTemp = ProjectSaleTemp::find($approval->approvalable_id);
			$param = 'sales_order_revision';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Sales Order Revision';
			$subtitle = 'Project Sales Order '.$project->code.' Revision After Delivery';
			$id_approval  = $id;
			if(in_array(1, session('bo_role'))){
				$link = url('admin/sales/project/printHtml/'.$param.'/'.base64_encode($project->id));
				$link2 = url('admin/sales/project/printHtml/sales_cost/'.base64_encode($project->id));
				$link3 = url('admin/sales/project/printHtml/sales_news/'.base64_encode($project->id));
			}else{
				$link = url('admin/sales/project/print/'.$param.'/'.base64_encode($project->id));
				$link2 = url('admin/sales/project/print/sales_cost/'.base64_encode($project->id));
				$link3 = url('admin/sales/project/print/sales_news/'.base64_encode($project->id));
			}
			
		}elseif($approval->approvalable_type == 'project_from_stocks') {
			
			$project = ProjectFromStock::find($approval->approvalable_id);
			$param = 'project_from_stock';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Sales From Stock';
			$subtitle = 'Project From Stock For Sales Order '.$project->projectSale->code.' and Project No. '.$project->project->code;
			$id_approval  = $id;
			// $link = url('admin/sales/project/print/sales_order/'.base64_encode($project->projectSale->id));
			$link = 'facebook.com';
		
		}elseif($approval->approvalable_type == 'project_pays') {
			
			$project = ProjectPay::find($approval->approvalable_id);
			$param = 'sales_invoice';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Sales Invoice';
			$subtitle = 'Project Sales Invoice '.$project->code;
			$id_approval  = $id;
			$link = url('admin/invoice/project/print/'.$param.'/'.base64_encode($project->id));
			
		}elseif($approval->approvalable_type == 'project_main_payments') {
			
			$project = ProjectMainPayment::find($approval->approvalable_id);
			$param = 'project_main_payment';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Payment Sales Invoice';
			$subtitle = 'Multi Project Payment Sales Invoice '.$project->code;
			$id_approval  = $id;
			$link = '';
		
		}elseif($approval->approvalable_type == 'project_bills') {
			
			$project = ProjectBill::find($approval->approvalable_id);
			$param = 'sales_bill';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Bill';
			$subtitle = 'Project Bill Number '.$project->code;
			$id_approval  = $id;
			$link = url('admin/delivery_order/project/print/'.$param.'/'.base64_encode($project->id));
			
		}elseif($approval->approvalable_type == 'project_payments') {
			
			$pp = ProjectPayment::find($approval->approvalable_id);
			
			$param = 'purchase_payments';
			
			$title = 'Approval Project Purchase Payments';
			$subtitle = 'Project Purchase Payments Code '.$pp->projectPurchase->code;
			$id_approval  = $id;
			$link = url('admin/purchase_order/project/print/purchase_order/'.base64_encode($pp->projectPurchase->id));
			$link2 = count($pp->projectPurchase->projectProforma) > 0 ? ($pp->projectPurchase->projectProforma()->where('project_purchase_id',$pp->projectPurchase->id)->orderBy('created_at','desc')->first()->attachment() !== '' ? $pp->projectPurchase->projectProforma()->where('project_purchase_id',$pp->projectPurchase->id)->orderBy('created_at','desc')->first()->attachment() : '') : '';
		
		}elseif($approval->approvalable_type == 'project_purchases') {
			
			$project = ProjectPurchase::find($approval->approvalable_id);
			$param = 'purchase_order';
			
			$revisi = ProjectLog::where('project_table','project_purchases')->where('project_table_id',$project->id)->orderBy('id')->get()->first();
			
			if(count($project->projectWarehouse) > 0){
				foreach($project->projectWarehouse as $warehouse){
					$cb = CashBank::where('lookable_type','project_warehouses')->where('lookable_id',$warehouse->id)->get();
				}
			}

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Purchase Order & Sales Order';
			if($project->projectSale){
				$subtitle = 'Project Purchase Order '.$project->code.' and Sales Order '.$project->projectSale->code;
				$link2 = url('admin/sales/project/print/sales_order/'.base64_encode($project->projectSale->id));
				$link3 = url('admin/sales/project/print/sales_news/'.base64_encode($project->projectSale->id));
			}else{
				$subtitle = 'Project Purchase Order '.$project->code.' for stock';
			}
			
			$id_approval  = $id;
			$link = url('admin/purchase_order/project/print/'.$param.'/'.base64_encode($project->id));
			
		}elseif($approval->approvalable_type == 'project_deliveries') {
			
			$project = ProjectDelivery::find($approval->approvalable_id);
			$param = 'delivery_order';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Delivery Order & Proforma Invoice';
			$subtitle = 'Project Delivery Order '.$project->code.' & Proforma Invoice '.$project->proforma_code;
			$id_approval  = $id;
			$link = url('admin/delivery_order/project/print/delivery_order/'.base64_encode($project->id));
			$link2 = url('admin/delivery_order/project/print/sales_proforma/'.base64_encode($project->id));
			
		}elseif($approval->approvalable_type == 'project_purchase_returns') {
			
			$project = ProjectPurchaseReturn::find($approval->approvalable_id);
			$param = 'purchase_return';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Purchase Return';
			$subtitle = 'Project Purchase Return '.$project->code;
			$id_approval  = $id;
			$link = url('admin/invoice/project/print/'.$param.'/'.base64_encode($project->id));
		
		}elseif($approval->approvalable_type == 'project_sale_returns') {
			
			$project = ProjectSaleReturn::find($approval->approvalable_id);
			$param = 'sales_return';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project Sales Return';
			$subtitle = 'Project Sales Return '.$project->code;
			$id_approval  = $id;
			$link = url('admin/delivery_order/project/print/'.$param.'/'.base64_encode($project->id));
		
		}elseif($approval->approvalable_type == 'projects') {
			$project = Project::find($approval->approvalable_id);
			$param = 'report';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Project';
			$subtitle = 'Project '.$project->code;
			$id_approval  = $id;
			$link = url('admin/report/project/details/progress/'.$project->id);
			
		}elseif($approval->approvalable_type == 'payment_requests') {
			
			$project = PaymentRequest::find($approval->approvalable_id);
			$param = 'payment_request';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Payment Request';
			$subtitle = 'Payment Request number '.$project->code;
			$id_approval  = $id;
			$link = '';
		}elseif($approval->approvalable_type == 'transfers') {
			
			$project = Transfer::find($approval->approvalable_id);
			$param = 'transfers';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Warehouse Transfer/For Balance';
			$subtitle = 'Warehouse Transfer '.$project->code;
			$id_approval  = $id;
			$link = url('admin/inventory/transfer/print/'. base64_encode($project->id));
			
		}elseif($approval->approvalable_type == 'purchase_requests') {
			
			$project = PurchaseRequest::find($approval->approvalable_id);
			$param = 'purchase_request';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Purchase Request';
			$subtitle = 'Purchase Request number '.$project->id;
			$id_approval  = $id;
			$link = '';
		
		}elseif($approval->approvalable_type == 'budgeting_projects') {
			
			$project = BudgetingProject::find($approval->approvalable_id);
			$param = 'budgeting_projects';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Budgeting Project';
			$subtitle = 'Budgeting Project '.$project->name.' Rev-'.$project->revision_counter.' Est-'.$project->estimation_counter;
			$id_approval  = $id;
			$link = '';
		
		}elseif($approval->approvalable_type == 'receivable_payments') {
			
			$project = ReceivablePayment::find($approval->approvalable_id);
			$param = 'receivable_payment';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Receivable Payment';
			$subtitle = 'Receivable Payment no. '.$project->id;
			$id_approval  = $id;
			$link = '';
			
		} elseif($approval->approvalable_type == 'leave_requests') {
			
			$project = LeaveRequest::find($approval->approvalable_id);
			$param = 'leave_request';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Leave Request';
			$subtitle = 'Leave request number '.$project->code;
			$id_approval  = $id;
			$link = '';
        }elseif($approval->approvalable_type == 'service_costs') {

			$project = ServiceCost::find($approval->approvalable_id);
			$param = 'service_costs';

			if(!$project) {
				abort(404);
			}
			
            $title = 'Approval Service Charge';
			$subtitle = 'Service char no'.$project->code;
			$id_approval  = $id;
			$link = url('admin/sales/service_cost/print/sales_cost/'. base64_encode($project->id));
        }elseif($approval->approvalable_type == 'service_cost_payments') {
			
			$project= ServiceCostPayment::find($approval->approvalable_id);
			
			$param = 'service_cost_payments';
			
			$title = 'Approval Service Charge Payments';
			$subtitle = 'Service Charge Payments Code '.$project->code;
			$id_approval  = $id;
			$link = url('admin/sales/service_cost/print/sales_cost/'. base64_encode($project->serviceCost->id));
        }elseif($approval->approvalable_type == 'project_purchase_quotations') {
			
			$project= ProjectPurchaseQuotation::find($approval->approvalable_id);
			
			$param = 'project_purchase_quotations';
			
			$title = 'Approval Request Quotations';
			$subtitle = 'Request Quotations Code '.$project->code;
			$id_approval  = $id;
			$link = url('admin/purchase/project/print/request_quotation/'. base64_encode($project->id));
        }


        $data  = [
			'coa'			=> $coa,
			'project'		=> isset($project) ? $project : '',
			'projectTemp'	=> isset($projectTemp) ? $projectTemp : '',
            'title'    		=> $title,
			'subtitle'    	=> $subtitle,
            'id_approval' 	=> $id_approval,
			'link'			=> $link,
			'link2'			=> $link2 ? $link2 : '',
			'link3'			=> isset($link3) ? $link3 : '',
			'approval'		=> $approval,
			'param'			=> $param,
			'type'			=> isset($type) ? $type : '',
			'cb'			=> isset($cb) ? $cb : null,
			'revisi'		=> isset($revisi) ? $revisi : null,
			'data'			=> isset($pp) ? $pp : '',
            'content'  		=> 'admin.approval_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function project(Request $request)
    {
		$id = $request->val;
		
		$approval = Approval::find($id);
		$approval->approved_by = session('bo_id');
		$approval->save();
		
		if($approval->approvalable_type == 'projects'){
			$p = $approval->approvalable;
			$p->update([
				'progress' => 100
			]);
			
			Approval::where('approvalable_type', $approval->approvalable_type)
					->where('approvalable_id', $approval->approvalable_id)
					->update([
						'seen' => true,
						'approved_by' => session('bo_id')
					]);
		}else{
			if($approval->approvalable_type == 'purchase_requests' && $approval->column_name == 'approved_by'){
				if($approval->approvalable->link_type == 'customer_deposit'){
					$pr = PurchaseRequest::find($approval->approvalable_id)->update([
						'branch'			=> $request->branchpr,
						'title'				=> $request->titlepr,
						'item'				=> $request->notepr,
						'total_nominal' 	=> str_replace(',','.',str_replace('.','',$request->nominalpr))
					]);
				}else{
					$pr = PurchaseRequest::find($approval->approvalable_id)->update([
						'coa_id'			=> $request->coapr,
						'branch'			=> $request->branchpr,
						'title'				=> $request->titlepr,
						'item'				=> $request->notepr,
						'total_nominal' 	=> str_replace(',','.',str_replace('.','',$request->nominalpr))
					]);
				}
				
				
				$prq = PurchaseRequest::find($approval->approvalable_id);
				
				if($prq->project_purchase_bill_id){
					ProjectPurchaseBill::find($prq->project_purchase_bill_id)->update([
						'date'		=> $prq->date,
						'due_date'	=> $prq->due_date,
						'nominal'	=> $prq->total_nominal
					]);
				}
			}
			
			$p = $approval->approvalable;
			$p->update([
				$approval->column_name => session('bo_id')
			]);
			
			Approval::where('approvalable_type', $approval->approvalable_type)
					->where('approvalable_id', $approval->approvalable_id)
					->where('column_name', $approval->column_name)
					->update([
						'seen' => true,
						'approved_by' => session('bo_id')
					]);
					
			if($approval->approvalable_type == 'project_from_stocks'){
				Project::find($p->project_id)->update([
					'progress'		=> 65
				]);
			}
			
			if($approval->approvalable_type == 'project_main_payments'){
				
				$tanggal = $request->tanggal;
				$coa = $request->coa;
				$nominal = $request->nominal;
				
				$pmp = $approval->approvalable;
				
				$pmpedit = ProjectMainPayment::find($approval->approvalable_id);
				
				$pmpedit->update([
					'date'		=> $tanggal,
					'coa_id'	=> $coa,
					'nominal'	=> str_replace(',','.',str_replace('.','',$nominal))
				]);
				
				$cbcek = CashBank::where('lookable_type','project_main_payments')->where('lookable_id',$approval->approvalable_id)->get();
				
				if($cbcek){
					foreach($cbcek as $rowcb){
						foreach($rowcb->cashBankDetail as $rowdetail){
							if($rowdetail->type == '1'){
								$updatecb = CashBankDetail::find($rowdetail->id)->update([
									'nominal'	=> str_replace(',','.',str_replace('.','',$nominal)),
									'coa_id'	=> $coa
								]);
							}
						}
						
						foreach(Journal::where('journalable_id',$rowcb->id)->get() as $rowjournal){
							if($rowjournal->type == '1'){
								$updatejournal = Journal::find($rowjournal->id)->update([
									'nominal'			=> str_replace(',','.',str_replace('.','',$nominal)),
									'coa_id'			=> $coa,
									'date_transaction'	=> $tanggal
								]);
							}
							if($rowjournal->type == '2'){
								$updatejournal = Journal::find($rowjournal->id)->update([
									'date_transaction'	=> $tanggal
								]);
							}
						}
						
						$rowcb->update([
							'date'	=> $tanggal
						]);
					}
					
					$bh = BalanceHistory::find(intval(explode('-',$cbcek)[1]));
					
					if($bh){
						$bh->update([
							'nominal'			=> str_replace(',','.',str_replace('.','',$nominal)),
							'coa_id'			=> $coa,
							'date'				=> $tanggal
						]);
					}
				}
				
				if($pmp){
					foreach($pmp->projectPay as $rowpay){
						ProjectPay::find($rowpay->id)->update([
							$approval->column_name 	=> session('bo_id'),
							'date'					=> $tanggal,
							'coa_id'				=> $coa
						]);
					}
				}
			}
			
			if($approval->approvalable_type == 'receivable_payments'){
				$date = $request->date;
				$coa = $request->coa;
				$nominal = str_replace(',','.',str_replace('.','',$request->nominal));
				$note = $request->note;
				$branch_rp = $request->branch_rp;
				
				$rp = ReceivablePayment::find($approval->approvalable_id);
				
				$rp->update([
					'coa_id'	=> $coa,
					'date'		=> $date,
					'note'		=> $note,
					'branch'	=> $branch_rp,
					'nominal'	=> $nominal
				]);
			}
		}
		
		#start notif
		$role = array('1','2','3','4','5','6','7','9','10','11');
		$title = 'Project has been updated!';
		
		if($approval->approvalable_type == 'project_quotations'){
			$description = 'Project '.$p->project->code.' quotation revision '.$p->revision.' has been approved by '.session('bo_name');
		}elseif($approval->approvalable_type == 'project_samples'){
			$description = 'Project '.$p->project->code.' with details sample code '.$p->code.' products has been approved by '.session('bo_name');
		}elseif($approval->approvalable_type == 'project_sales'){
			$description = 'Project '.$p->project->code.' with details project sale '.$p->code.' and products has been approved by '.session('bo_name');
			
			if($approval->approvalable->marketing_id){
				if($approval->approvalable->project->progress < 40){
					Project::find($approval->approvalable->project_id)->update([
						'progress'	=> 40
					]);
				}
			}
			if($approval->column_name == "approved_closed"){
				ProjectSale::find($approval->approvalable->id)->update([
					'date_closed'	=> date('Y-m-d'),
				]);
			}

		}elseif($approval->approvalable_type == 'project_pays'){
			$description = 'Project '.$p->project->code.' - '.$p->projectSale->code.' with details payment invoice code '.$p->code.' has been approved by '.session('bo_name');
			
			
			
		}elseif($approval->approvalable_type == 'project_bills'){
			$description = 'Project '.$p->project->code.' with details bill number '.$p->code.' has been approved by '.session('bo_name');
			
			
				$debetcb = 27;
				$kreditcb = 67;
				
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'lookable_type'  	=> 'project_bills',
					'lookable_id'		=> $approval->approvalable->id,
					'code'        		=> strtoupper(Str::random(15)),
					'date'        		=> $approval->approvalable->date,
					'type'        		=> 3,
					'description' 		=> 'Project bill number '.$approval->approvalable->code.' has been created from Project number '.$approval->approvalable->project->code
				]);
				
				if($cb){
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debetcb,
						'branch'		=> $approval->approvalable->branch,
						'type'       	=> '1',
						'nominal'      	=> floatval($approval->approvalable->nominal) + floatval($approval->approvalable->nominal_service),
						'note'         	=> ''
					]);
	
					Journal::insert([
						'date_transaction' => $approval->approvalable->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debetcb,
						'branch'		   => $approval->approvalable->branch,
						'type'	           => '1',
						'nominal'          => floatval($approval->approvalable->nominal) + floatval($approval->approvalable->nominal_service),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kreditcb,
						'branch'		=> $approval->approvalable->branch,
						'type'       	=> '2',
						'nominal'      	=> floatval($approval->approvalable->nominal) + floatval($approval->approvalable->nominal_service),
						'note'         	=> ''
					]);
	
					Journal::insert([
						'date_transaction' => $approval->approvalable->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kreditcb,
						'branch'		   => $approval->approvalable->branch,
						'type'	           => '2',
						'nominal'          => floatval($approval->approvalable->nominal) + floatval($approval->approvalable->nominal_service),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
				}
			
			

		}elseif($approval->approvalable_type == 'project_purchases'){
			if($p->project){
				$description = 'Project '.$p->project->code.' with details purchase code '.$p->code.' has been approved by '.session('bo_name');
			}else{
				$description = 'Purchase for stock with details purchase code '.$p->code.' has been approved by '.session('bo_name');
			}
		}elseif($approval->approvalable_type == 'project_deliveries'){
			$description = 'Project '.$p->project->code.' with details sales code '.$p->projectSale->code.' has been approved/checked/acknowledge by '.session("bo_name").' with delivery project code '.$p->code.'.';
		}elseif($approval->approvalable_type == 'project_sale_returns'){
			$description = 'Project '.$p->code.' with details sales code '.$p->projectSale->code.' has been returned with sales return code '.$p->code.'.';
		}elseif($approval->approvalable_type == 'project_purchase_returns'){
			$description = 'Project '.$p->project->code.' with details purchase code '.$p->projectPurchase->code.' has been returned with purchase return code '.$p->code.'.';
		}elseif($approval->approvalable_type == 'projects'){
			$description = 'Project '.$p->code.' has been closed successfully by '.session("bo_name");
		}elseif($approval->approvalable_type == 'payment_requests'){
			$description = 'Payment Request '.$p->code.' has been approved by '.session("bo_name");
		}elseif($approval->approvalable_type == 'project_main_payments'){
			$description = 'Project payment '.$p->code.' has been approved by '.session("bo_name");
		}elseif($approval->approvalable_type == 'leave_requests'){
			$description = 'Leave request '.$p->code.' has been approved/checked by '.session("bo_name");
		}elseif($approval->approvalable_type == 'service_costs'){
			$description = 'Service Charge '.$p->code.' has been approved/checked by '.session("bo_name");

			$query = ServiceCost::find($approval->approvalable_id);

			$description = 'Service charge with code. '.$query->code;
			$coa_debit = '27'; // ACCOUNT RECEIVABLE
			$coa_credit = $query->is_ppn == '1' ? 286 : 287;  // SALES SERVICE PPN/ NON PPN
			$coa_credit_ppn = 71;  // PPN KELUARAN

			$cb = CashBank::create([
				'user_id'     		=> session('bo_id'),
				'lookable_type'  	=> 'service_costs',
				'lookable_id'		=> $query->id,
				'code'        		=> strtoupper(Str::random(15)),
				'date'        		=> $query->created_at,
				'type'        		=> '3',
				'description' 		=> $description,
				'image'				=> $query->image
			]);
			
			if($cb){
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $coa_debit,
					'branch'		=> $query->branch,
					'type'       	=> '1',
					'nominal'      	=> round($query->grandtotal_service, 0),
					'note'         	=> ''
				]);

				Journal::insert([
					'date_transaction' => $query->created_at,
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $coa_debit,
					'branch'		   => $query->branch,
					'type'	           => '1',
					'nominal'          => round($query->grandtotal_service, 0),
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);

				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $coa_credit,
					'branch'		=> $query->branch,
					'type'       	=> '2',
					'nominal'      	=> round($query->grandtotal_service - $query->tax_service, 0),
					'note'         	=> ''
				]);

				Journal::insert([
					'date_transaction' => $query->created_at,
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $coa_credit,
					'branch'		   => $query->branch,
					'type'	           => '2',
					'nominal'          => round($query->grandtotal_service - $query->tax_service, 0),
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);

                if($query->is_ppn == '1'){
                    CashBankDetail::create([
                        'cash_bank_id' 	=> $cb->id,
                        'coa_id'       	=> $coa_credit_ppn,
                        'branch'		=> $query->branch,
                        'type'       	=> '2',
                        'nominal'      	=> round($query->tax_service, 0),
                        'note'         	=> ''
                    ]);
    
                    Journal::insert([
                        'date_transaction' => $query->created_at,
                        'journalable_type' => 'cash_banks',
                        'journalable_id'   => $cb->id,
                        'coa_id'           => $coa_credit_ppn,
                        'branch'		   => $query->branch,
                        'type'	           => '2',
                        'nominal'          => round($query->tax_service, 0),
                        'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                        'updated_at'       => date('Y-m-d H:i:s')
                    ]);
                }
			}

			$query->update([
				'approved_id' => $approval->approved_by
			]); 
		}elseif($approval->approvalable_type == 'service_cost_payments'){
			$description = 'Service Charge Payments'.$p->code.' has been approved/checked by '.session("bo_name");
		}elseif($approval->approvalable_type == 'attendances'){
			$type = $approval->column_name == 'in_approved_by' ? 'Check In' : 'Check Out';
			$description = 'Attendance '.$type.' date '.date("d M Y",strtotime($p->date)).' has been approved/checked by '.session("bo_name");
		}elseif($approval->approvalable_type == 'samples'){
			$type = $p->customerSample->code;
			$description = 'Samples'. $type .' date '.date("d M Y",strtotime($p->created_at)).' has been approved/checked by '.session("bo_name");
		}elseif($approval->approvalable_type == "sample_purchases"){
			$description = 'Sample '.$p->customerSample->code.' with details purchase code '.$p->code.' has been approved by '.session('bo_name');
		}elseif($approval->approvalable_type == "sample_purchase_returns"){
			$description = 'Sample '.$p->customerSample->code.' has been returned with purchase return code '.$p->code.'.';
		}elseif($approval->approvalable_type == "sample_deliveries"){
			$title = 'Sample has been updated!';
			$description = 'Sample '.$p->customerSample->code.' with details sales code '.$p->sample->code.' has been approved/checked/acknowledge by '.session("bo_name").' with delivery sample code '.$p->code.'.';
		}elseif($approval->approvalable_type == 'project_from_stocks'){
			$description = 'Sales From Stock '.$p->projectSale->code.' has been approved/checked by '.session("bo_name");
		}elseif($approval->approvalable_type == 'transfers') {
			$description = 'Warehouse transfer '.$p->code.' has been approved/checked by '.session("bo_name");
		}elseif($approval->approvalable_type == 'budgeting_projects') {
			$description = 'Budgeting project '.$p->name.' for project no. '.$p->project->code.' has been approved/checked by '.session("bo_name");
			
			if($approval->column_name == 'checked_by'){
				foreach($p->project->projectSale->whereNull('is_closed') as $ps){
					$roleapproval = array('5');
					Approval::sendApproval($roleapproval,'project_sales',$ps->id,'marketing_id',$p->user_id);
					$roleapproval = array('4');
					Approval::sendApproval($roleapproval,'project_sales',$ps->id,'approved_id',$p->user_id);
					
					SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Sales No. '.$ps->code.' Project No. '.$ps->project->code.'. Terima kasih.');
					SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Sales No. '.$ps->code.' Project No. '.$ps->project->code.'. Terima kasih.');
				}
				
				// $roleapproval = array('4');
				// Approval::sendApproval($roleapproval,'budgeting_projects',$p->id,'approved_by',$p->user_id);
				
				// SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak Ryan. Mohon dibantu approve Budgeting Project Nomor '.$p->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				BudgetingProject::where('id', $p->id)->update([
					'approved_by' => 7
				]);
				
		
			}
			
		}elseif($approval->approvalable_type == 'project_sales_temps'){
			$description = 'Revision Sales No. '.$p->code.' has been approved/checked by '.session("bo_name");
			
			$ps = ProjectSaleTemp::find($approval->approvalable_id);
			$psedit = ProjectSale::find($approval->approvalable_id);
			$psedit->projectSaleProduct()->delete();
			$psedit->projectSaleShading()->delete();
			
			foreach($ps->projectSaleProductTemp as $row){
				ProjectSaleProduct::create([
					'project_sale_id'	=> $row->project_sale_id,
					'product_id'		=> $row->product_id,
					'area'				=> $row->area,
					'spec'				=> $row->spec,
					'qty'				=> $row->qty,
					'cogs'				=> $row->cogs,
					'price'				=> $row->price,
					'recommended_price'	=> $row->recommended_price,
					'best_price'		=> $row->best_price,
					'discount'			=> $row->discount,
					'unit'				=> $row->unit
				]);
			}
			
			foreach($ps->projectSaleShadingTemp as $row){
				ProjectSaleShading::create([
					'project_sale_id'	=> $row->project_sale_id,
					'product_id'		=> $row->product_id,
					'warehouse_code'	=> $row->warehouse_code,
					'stock_code'		=> $row->stock_code,
					'code'				=> $row->code,
					'qty'				=> $row->qty
				]);
			}
			
			$psedit->update([
				'user_id' 			=> $ps->user_id,
				'project_id'		=> $ps->project_id,
				'sales_id'			=> $ps->sales_id,
				'code'				=> $ps->code,
				'address'			=> $ps->address,
				'note'				=> $ps->note,
				'so_file'			=> $ps->so_file,
				'marketing_id'		=> $ps->marketing_id,
				'approved_id'		=> $ps->approved_id,
				'delivery_cost'		=> $ps->delivery_cost,
				'cutting_cost'		=> $ps->cutting_cost,
				'misc_cost'			=> $ps->misc_cost,
				'misc_note'			=> $ps->misc_note,
				'ppn_cost'			=> $ps->ppn_cost,
				'mid_yes_no'		=> $ps->mid_yes_no,
				'mid_type'			=> $ps->mid_type,
				'mid_fee'			=> $ps->mid_fee,
				'mid_director'		=> $ps->mid_director,
				'mid_note'			=> $ps->mid_note,
				'currency_id'		=> $ps->currency_id,
				'currency_rate'		=> $ps->currency_rate,
			]);
			
			ProjectSale::find($psedit->id)->updateGrandtotal();
			
			foreach(ProjectSale::find($psedit->id)->projectDelivery as $row){
				$row->updateGrandtotal();
			}
			
			foreach(ProjectSale::find($psedit->id)->projectSaleReturn as $row){
				$row->updateGrandtotal();
			}
			
			$ps->update([
				'status'	=> '1'
			]);

			if(count($psedit->project->projectBill)>0){
				SendMessage::send(env('AR_PHONE'),'Halo pak/bu. Ada perubahan pada Sales Order No. SO-'.$psedit->code.'. pada PJ-'.$psedit->project->code. 'mohon di cek ulang nominal SO dan SO Service apakah sudah sesuai dengan bill. Terima kasih.');
			}
			
		}elseif($approval->approvalable_type == 'receivable_payments'){
			
			$description = 'Other receivable payment '.$p->id.' has been approved by '.session("bo_name");
			
			if($approval->approvalable->approved_by){
			
				$branch = $branch_rp;
				
				$bh = BalanceHistory::create([
					'user_id' 		=> session('bo_id'),
					'nominal'		=> $nominal,
					'type'			=> 'IN',
					'cash_or_bank'	=> 'BANK',
					'coa_id'		=> $coa,
					'branch'		=> $branch,
					'note'			=> $note,
					'date'			=> $date,
					'image'			=> $approval->approvalable->image ? $approval->approvalable->image : ''
				]);
				
				$kreditcb = 27;
				
				$debetcb = $approval->approvalable->coa_id;
				
				$cb = CashBank::create([
					'user_id'     			=> session('bo_id'),
					'lookable_id'			=> $p->id,
					'lookable_type'			=> 'receivable_payments',
					'customer_id'			=> $approval->approvalable->customer_id,
					'request_date'			=> $date,
					'due_date'				=> $date,
					'code'        			=> 'BPC-'.$bh->id,
					'date'        			=> $date,
					'type'        			=> '1',
					'description' 			=> $note
				]);
				
				if($cb){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debetcb,
						'branch'		=> $branch,
						'type'       	=> '1',
						'nominal'      	=> $nominal,
						'note'         	=> $note
					]);
					
					Journal::insert([
						'date_transaction' => $date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debetcb,
						'branch'		   => $branch,
						'type'	           => '1',
						'nominal'          => $nominal,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kreditcb,
						'branch'		=> $branch,
						'type'       	=> '2',
						'nominal'      	=> $nominal,
						'note'         	=> $note
					]);
					
					Journal::insert([
						'date_transaction' => $date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kreditcb,
						'branch'		   => $branch,
						'type'	           => '2',
						'nominal'          => $nominal,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					#balik jkt
					
					/* CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 332,
						'branch'		=> '2',
						'type'       	=> '1',
						'nominal'      	=> $nominal,
						'note'         	=> $note
					]);
					
					Journal::insert([
						'date_transaction' => $date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 332,
						'branch'		   => '2',
						'type'	           => '1',
						'nominal'          => $nominal,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 230,
						'branch'		=> '2',
						'type'       	=> '2',
						'nominal'      	=> $nominal,
						'note'         	=> $note
					]);
					
					Journal::insert([
						'date_transaction' => $date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 230,
						'branch'		   => '2',
						'type'	           => '2',
						'nominal'          => $nominal,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]); */
				}
			}
			
		}elseif($approval->approvalable_type == 'purchase_requests'){
			
			$title = 'Purchase Request is updated.';
			$description = 'Purchase Request number '.$p->id.' has been approved by '.session("bo_name");
			
			if($approval->column_name == 'approved_by'){
				if($p->link_type !== 'fee_pta' && $p->link_type !== 'customer_deposit'){
					if($p->link_type == "project_purchases"){
						
					}else{
						
						$debetcb = 48;
						
						if($p->link_type == "project_sales"){
							$kreditcb = 281;
							$debetcb = 299;
							
							PurchaseRequest::find($p->id)->update([
								'coa_id'	=> 281
							]);
							
							$cb = CashBank::create([
								'user_id'     			=> $p->user_id,
								'lookable_id'			=> ProjectSale::find($p->link_id)->project_id,
								'lookable_type'			=> 'projects',
								'request_date'			=> $p->date,
								'due_date'				=> $p->due_date,
								'code'        			=> 'PR-'.$p->id,
								'date'        			=> $p->date,
								'type'        			=> '3',
								'description' 			=> $p->title.' - '.$p->item
							]);
						}else{
							$kreditcb = $p->coa_id ? $p->coa_id : 332;
							
							$cb = CashBank::create([
								'user_id'     			=> $p->user_id,
								'lookable_id'			=> $p->id,
								'lookable_type'			=> 'purchase_requests',
								'supplier_id'			=> $p->supplier_id,
								'request_date'			=> $p->date,
								'due_date'				=> $p->due_date,
								'code'        			=> 'PR-'.$p->id,
								'date'        			=> $p->date,
								'type'        			=> '3',
								'description' 			=> $p->title.' - '.$p->item
							]);
						}
						
						if($cb){
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> $debetcb,
								'branch'		=> $p->branch,
								'type'       	=> '1',
								'nominal'      	=> $p->total_nominal,
								'note'         	=> $p->title.' - '.$p->item
							]);
							
							Journal::insert([
								'date_transaction' => $p->date,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => $debetcb,
								'branch'		   => $p->branch,
								'type'	           => '1',
								'nominal'          => $p->total_nominal,
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
							
							CashBankDetail::create([
								'cash_bank_id' 	=> $cb->id,
								'coa_id'       	=> $kreditcb,
								'branch'		=> $p->branch,
								'type'       	=> '2',
								'nominal'      	=> $p->total_nominal,
								'note'         	=> $p->title.' - '.$p->item
							]);

							Journal::insert([
								'date_transaction' => $p->date,
								'journalable_type' => 'cash_banks',
								'journalable_id'   => $cb->id,
								'coa_id'           => $kreditcb,
								'branch'		   => $p->branch,
								'type'	           => '2',
								'nominal'          => $p->total_nominal,
								'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								'updated_at'       => date('Y-m-d H:i:s')
							]);
						}
					}
				}
				
				PurchaseRequest::find($p->id)->update([
					'status'	=> 'APPR',
					'due_date'	=> $p->due_date < date('Y-m-d') ? date('Y-m-d') : $p->due_date
				]);
			}
		}elseif($approval->approvalable_type == 'project_purchase_quotations'){
			$title = 'Request Quotation is updated.';
			$description = 'Request Quotation number '.$p->code.' has been approved by '.session("bo_name");
		}
		
		$link = '#';
		Notification::sendNotif($role,$title,$description,$link);
		#end notif
		
		$response = [
			'status'  	=> 200,
			'message' 	=> 'Data added successfully.',
			'cb'		=> $approval->approvalable_type == 'purchase_requests' && isset($cb) ? $cb->id : ''
		];

		return response()->json($response);
	}
	
	public function multiApprove(Request $request){
		
		$row = explode(',',$request->val);
		
		#start notif
		$role = array('1','2','3','4','5','6','7','9','10','11');
		$title = 'Project has been updated!';
		
		foreach($row as $id){
			$approval = Approval::find($id);
			$approval->approved_by = session('bo_id');
			$approval->save();
			
			if($approval->approvalable_type == 'projects'){
				$p = $approval->approvalable;
				$p->update([
					'progress' => 100
				]);
			}else{
				$p = $approval->approvalable;
				$p->update([
					$approval->column_name => session('bo_id')
				]);
				
				Approval::where('approvalable_type', $approval->approvalable_type)
						->where('approvalable_id', $approval->approvalable_id)
						->where('column_name', $approval->column_name)
						->update([
							'seen' => true,
							'approved_by' => session('bo_id')
						]);
			}
			
			#start notif
			
			if($approval->approvalable_type == 'project_quotations'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->project->code.' quotation revision '.$p->revision.' has been approved by '.session('bo_name');
			}elseif($approval->approvalable_type == 'project_samples'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->project->code.' with details sample code '.$p->code.' products has been approved by '.session('bo_name');
			}elseif($approval->approvalable_type == 'project_sales'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->project->code.' with details project sale '.$p->code.' and products has been approved by '.session('bo_name');
				if($approval->approvalable->marketing_id && $approval->approvalable->approved_id){
					if($approval->approvalable->project->progress < 40){
						Project::find($approval->approvalable->project_id)->update([
							'progress'	=> 40
						]);
					}
				}

				if($approval->column_name == "approved_closed"){
					ProjectSale::find($approval->approvalable->id)->update([
						'date_closed'	=> date('Y-m-d'),
					]);
				}		
			}elseif($approval->approvalable_type == 'project_pays'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->project->code.' - '.$p->projectSale->code.' with details payment invoice code '.$p->code.' has been approved by '.session('bo_name');
			}elseif($approval->approvalable_type == 'project_bills'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->project->code.' with details bill number '.$p->code.' has been approved by '.session('bo_name');


				if($approval->approved_by == "7"){
					$debetcb = 27;
					$kreditcb = 67;
					
					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'  	=> 'project_bills',
						'lookable_id'		=> $approval->approvalable->id,
						'code'        		=> strtoupper(Str::random(15)),
						'date'        		=> $approval->approvalable->date,
						'type'        		=> 3,
						'description' 		=> 'Project bill number '.$approval->approvalable->code.' has been created from Project number '.$approval->approvalable->project->code
					]);
					
					if($cb){
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debetcb,
							'branch'		=> $approval->approvalable->branch,
							'type'       	=> '1',
							'nominal'      	=> floatval($approval->approvalable->nominal) + floatval($approval->approvalable->nominal_service),
							'note'         	=> ''
						]);
	
						Journal::insert([
							'date_transaction' => $approval->approvalable->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debetcb,
							'branch'		   => $approval->approvalable->branch,
							'type'	           => '1',
							'nominal'          => floatval($approval->approvalable->nominal) + floatval($approval->approvalable->nominal_service),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kreditcb,
							'branch'		=> $approval->approvalable->branch,
							'type'       	=> '2',
							'nominal'      	=> floatval($approval->approvalable->nominal) + floatval($approval->approvalable->nominal_service),
							'note'         	=> ''
						]);
	
						Journal::insert([
							'date_transaction' => $approval->approvalable->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kreditcb,
							'branch'		   => $approval->approvalable->branch,
							'type'	           => '2',
							'nominal'          => floatval($approval->approvalable->nominal) + floatval($approval->approvalable->nominal_service),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
					}
				}
				
				
			}elseif($approval->approvalable_type == 'project_purchases'){
				$title = 'Project has been updated!';
				if($p->project){
					$description = 'Project '.$p->project->code.' with details purchase code '.$p->code.' has been approved by '.session('bo_name');
				}else{
					$description = 'Purchase for stock with details purchase code '.$p->code.' has been approved by '.session('bo_name');
				}
			}elseif($approval->approvalable_type == 'project_deliveries'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->project->code.' with details sales code '.$p->projectSale->code.' has been approved/checked/acknowledge by '.session("bo_name").' with delivery project code '.$p->code.'.';
			}elseif($approval->approvalable_type == 'project_sale_returns'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->code.' with details sales code '.$p->projectSale->code.' has been returned with sales return code '.$p->code.'.';
			}elseif($approval->approvalable_type == 'project_purchase_returns'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->project->code.' with details purchase code '.$p->projectPurchase->code.' has been returned with purchase return code '.$p->code.'.';
			}elseif($approval->approvalable_type == 'projects'){
				$title = 'Project has been updated!';
				$description = 'Project '.$p->code.' has been closed successfully by '.session("bo_name");
			}elseif($approval->approvalable_type == 'payment_requests'){
				$title = 'Payment request been updated!';
				$description = 'Payment Request '.$p->code.' has been approved by '.session("bo_name");
			}elseif($approval->approvalable_type == 'leave_requests'){
				$title = 'Leave request has been updated!';
				$description = 'Leave request '.$p->code.' has been approved/checked by '.session("bo_name");
			}elseif($approval->approvalable_type == 'transfers') {
				$title = 'Project has been updated!';
				$description = 'Warehouse transfer '.$p->code.' has been approved/checked by '.session("bo_name");
			}elseif($approval->approvalable_type == 'budgeting_projects') {
				$title = 'Budgeting project has been updated!';
				$description = 'Budgeting project '.$p->name.' for project no. '.$p->project->code.' has been approved/checked by '.session("bo_name");
			}elseif($approval->approvalable_type == 'project_sales_temps'){
				$title = 'Project has been updated!';
				$description = 'Revision Sales No. '.$p->code.' has been approved/checked by '.session("bo_name");
				
				$ps = ProjectSaleTemp::find($approval->approvalable_id);
				$psedit = ProjectSale::find($approval->approvalable_id);
				
				$psedit->projectSaleProduct()->delete();
				$psedit->projectSaleShading()->delete();
				
				foreach($ps->projectSaleProductTemp as $row){
					ProjectSaleProduct::create([
						'project_sale_id'	=> $row->project_sale_id,
						'product_id'		=> $row->product_id,
						'area'				=> $row->area,
						'spec'				=> $row->spec,
						'qty'				=> $row->qty,
						'cogs'				=> $row->cogs,
						'price'				=> $row->price,
						'recommended_price'	=> $row->recommended_price,
						'best_price'		=> $row->best_price,
						'discount'			=> $row->discount,
						'unit'				=> $row->unit
					]);
				}
				
				foreach($ps->projectSaleShadingTemp as $row){
					ProjectSaleShading::create([
						'project_sale_id'	=> $row->project_sale_id,
						'product_id'		=> $row->product_id,
						'warehouse_code'	=> $row->warehouse_code,
						'stock_code'		=> $row->stock_code,
						'code'				=> $row->code,
						'qty'				=> $row->qty
					]);
				}
				
				$psedit->update([
					'user_id' 			=> $ps->user_id,
					'project_id'		=> $ps->project_id,
					'sales_id'			=> $ps->sales_id,
					'code'				=> $ps->code,
					'address'			=> $ps->address,
					'note'				=> $ps->note,
					'so_file'			=> $ps->so_file,
					'marketing_id'		=> $ps->marketing_id,
					'approved_id'		=> $ps->approved_id,
					'delivery_cost'		=> $ps->delivery_cost,
					'cutting_cost'		=> $ps->cutting_cost,
					'misc_cost'			=> $ps->misc_cost,
					'misc_note'			=> $ps->misc_note,
					'ppn_cost'			=> $ps->ppn_cost,
					'mid_yes_no'		=> $ps->mid_yes_no,
					'mid_type'			=> $ps->mid_type,
					'mid_fee'			=> $ps->mid_fee,
					'mid_director'		=> $ps->mid_director,
					'mid_note'			=> $ps->mid_note,
					'currency_id'		=> $ps->currency_id,
					'currency_rate'		=> $ps->currency_rate
				]);
				
				$ps->update([
					'status'	=> '1'
				]);

				if(count($psedit->project->projectBill)>0){
					SendMessage::send(env('AR_PHONE'),'Halo pak/bu. Ada perubahan pada Sales Order No. SO-'.$psedit->code.'. pada PJ-'.$psedit->project->code. 'mohon di cek ulang nominal SO dan SO Service apakah sudah sesuai bill. Terima kasih.');
				}
			}elseif($approval->approvalable_type == 'sample_purchases'){
				$title = 'Sample has been updated!';

				$description = 'Sample '.$p->customerSample->code.' with details purchase code '.$p->code.' has been approved by '.session('bo_name');
				
			}elseif($approval->approvalable_type == 'sample_purchase_returns'){
				$title = 'Sample has been updated!';

				$description = 'Sample '.$p->customerSample->code.' with details purchase code '.$p->code.' has been approved by '.session('bo_name');
				
			}
			elseif($approval->approvalable_type == 'sample_deliveries'){
				$title = 'Sample has been updated!';
				$description = 'Sample '.$p->customerSample->code.' with details sales code '.$p->sample->code.' has been approved/checked/acknowledge by '.session("bo_name").' with delivery project code '.$p->code.'.';
				
			}
			
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
		}
		
		$response = [
			'status'  => 200,
			'message' => 'Data added successfully.'
		];

		return response()->json($response);
	}
	
	public function reject(Request $request)
    {
		$id = $request->val;
		
		$approval = Approval::find($id);
		$approval->approved_by = session('bo_id');
		$approval->save();
		
		if($approval->approvalable_type == 'projects'){
			$p = $approval->approvalable;
			$p->update([
				'reject_reason'	=> $request->reason
			]);
			
			Approval::where('approvalable_type', $approval->approvalable_type)
					->where('approvalable_id', $approval->approvalable_id)
					->where('column_name', $approval->column_name)
					->update([
						'seen' => true,
						'approved_by' => session('bo_id'),
						'status' => '2'
					]);
		}else{
			$p = $approval->approvalable;
			$p->update([
				$approval->column_name => session('bo_id')
			]);
			
			Approval::where('approvalable_type', $approval->approvalable_type)
					->where('approvalable_id', $approval->approvalable_id)
					->where('column_name', $approval->column_name)
					->update([
						'seen' => true,
						'approved_by' => session('bo_id'),
						'status' => '2'
					]);
		}
		
		#start notif
		$role = array('1','2','3','4','5','6','7','9','10','11');
		$title = 'Project has been updated!';
		
		if($approval->approvalable_type == 'project_quotations'){
			$description = '';
		}elseif($approval->approvalable_type == 'project_samples'){
			$description = '';
		}elseif($approval->approvalable_type == 'project_sales'){
			$description = '';
		}elseif($approval->approvalable_type == 'project_pays'){
			$description = '';
		}elseif($approval->approvalable_type == 'project_bills'){
			$description = '';
		}elseif($approval->approvalable_type == 'project_purchases'){
			if($p->project){
				$description = '';
			}else{
				$description = '';
			}
		}elseif($approval->approvalable_type == 'project_deliveries'){
			$description = '';
		}elseif($approval->approvalable_type == 'project_sale_returns'){
			$description = '';
		}elseif($approval->approvalable_type == 'project_purchase_returns'){
			$description = '';
		}elseif($approval->approvalable_type == 'projects'){
			$description = 'Project No. '.$p->code.' had been rejected by '.session('bo_name').' with detail reason : '.$request->reason;
		}elseif($approval->approvalable_type == 'payment_requests'){
			$description = '';
		}elseif($approval->approvalable_type == 'purchase_requests'){
			$description = 'Purchase Request number '.$p->id.' has been rejected by '.session("bo_name");
			
			$p->update([
				'status'	=> 'RJCT'
			]);
		}elseif($approval->approvalable_type == 'leave_requests'){
			$title = 'Leave request has been updated!';
			$description = 'Your leave request with note <b>'.$p->note.'</b> has been rejected by '.session("bo_name").' reason : <b>'.$request->reason.'</b>.';
			
			if($approval->column_name == 'approved_by'){
				$p->update([
					'reject_approved'	=> $request->reason
				]);
			}elseif($approval->column_name == 'checked_by'){
				$p->update([
					'reject_checked'	=> $request->reason
				]);
			}
			
			$role = $p->user_id;
		}
		
		$link = '#';
		Notification::sendNotif($role,$title,$description,$link);
		#end notif
		
		$response = [
			'status'  => 200,
			'message' => 'Data added successfully.'
		];

		return response()->json($response);
	}
}
