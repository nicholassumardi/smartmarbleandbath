<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Approval;
use App\Models\Coa;
use App\Models\User;
use App\Models\ProjectSale;
use App\Models\ProjectPurchaseBill;
use App\Models\ProjectPayment;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Journal;
use App\Models\BalanceHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestPayment;
use App\Models\PurchaseRequestMainPayment;
use App\Models\ProjectPurchase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\SendMessage;
use App\Helper\CheckCutOff;
use App\Models\BudgetingProject;
use App\Models\ProjectWarehouse;
use App\Models\PurchaseCost;
use App\Models\Stock;
use App\Models\UserRole;

class PurchaseRequestController extends Controller {

    public function index()
    {	
		// $cekpr = CashBank::where('code','like','PR-2847')->first();
		// if($cekpr){
		// 	dd($cekpr);
		// }
        $data = [
			'projectpurchase' 	=> ProjectPurchase::all(),
			'coa'     			=> Coa::where('status', 1)->oldest('code')->get(),
			'user'				=> User::where('status','1')->get(),
            'title'   			=> 'Purchase Request',
            'content' 			=> 'admin.finance.purchase_request'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
    
	
	public function datatable(Request $request) 
    {
        $column = [
			/* 'detail', */
            'id',
            'user_id',
            'date',
			'due_date',
			'branch',
			'bill_to',
            'item',
            'total_nominal',
            'status',
            'image',
            'action'
        ];

        $start  = $request->start;
        $length = $request->length < 0 ? 999999999999999 : $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = PurchaseRequest::count();
        
        $query_data = PurchaseRequest::where(function($query) use ($search, $request) {
				
				
				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date', '>=', $request->start_date)
                        ->whereDate('date', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }
			
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
                            ->orWhere('item', 'like', "%$search%")
							->orWhere('title', 'like', "%$search%")
							->orWhere('bill_to', 'like', "%$search%")
							->orWhere('total_cash_advance', 'like', "%$search%")
							->orWhere('total_nominal', 'like', "%$search%")
							->orWhere('status', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    });
                }
				
				if($request->type) {
                    $query->where('status', $request->type);
                }
				
				if($request->user_id) {
                    $query->where('user_id', $request->user_id);
                }
				
				if($request->branch) {
                    $query->where('branch', $request->branch);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = PurchaseRequest::where(function($query) use ($search, $request) {
				
				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date', '>=', $request->start_date)
                        ->whereDate('date', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }
				
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
                            ->orWhere('item', 'like', "%$search%")
							->orWhere('title', 'like', "%$search%")
							->orWhere('bill_to', 'like', "%$search%")
							->orWhere('total_cash_advance', 'like', "%$search%")
							->orWhere('total_nominal', 'like', "%$search%")
							->orWhere('status', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    });
                }
				
				if($request->type) {
                    $query->where('status', $request->type);
                }
				
				if($request->user_id) {
                    $query->where('user_id', $request->user_id);
                }
				
				if($request->branch) {
                    $query->where('branch', $request->branch);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
			
            foreach($query_data as $val) {
				
				$btnaction = '';
				$po = '';
				$budgeting =''; 
				if($val->image){
					if(explode('.',$val->image)[1] == 'pdf'){
						$photo = '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$val->item.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}else{
					$photo = '<span class="badge badge-danger">Empty</span>';
				}
				
				if($val->link_type == 'project_sales'){
				    $project_sale = ProjectSale::find($val->link_id);
					$budgeting_project = BudgetingProject::where('project_id', $project_sale->project_id)->first();
					$so = '<a href="'.url('admin/sales/project/print/so_do_news/'. base64_encode($val->link_id)).'" class="btn btn-sm btn-warning" target="_blank">SO</a>';
					if($budgeting_project){
					   $budgeting = '<a href="'.url('admin/sales/budgeting_project/detail/'.$budgeting_project->id).'" class="btn btn-sm btn-secondary" target="_blank" title="Budgeting Detail"><i class="icon-file-eye"></i></a>';
					}
				
				}elseif($val->link_type == 'project_purchases'){
					$so = '';
					$po = '';
					$budgeting ='';
					
					$purchase = ProjectPurchase::find($val->link_id);
					$budgeting_project = BudgetingProject::where('project_id', $purchase->project_id)->first();

					if($purchase){
						if($purchase->project){
							$so = '';
							foreach($purchase->project->projectSale as $rowsale){
								$so .= '<a href="'.url('admin/sales/project/print/so_do_news/'. base64_encode($rowsale->id)).'" class="btn btn-sm btn-warning" target="_blank">SO</a>';
							}
							$po .='<a href="'. url('admin/purchase_order/project/print/purchase_order/' . base64_encode($purchase->id)).'" class="btn btn-sm btn-success" target="_blank">PO</a>';
							if($budgeting_project){
							  $budgeting = '<a href="'.url('admin/sales/budgeting_project/detail/'.$budgeting_project->id).'" class="btn btn-sm btn-secondary" target="_blank" title="Budgeting Detail"><i class="icon-file-eye"></i></a>';
							}
						}elseif($purchase->project_id == 0){
							$so = '';
							$so .= '<span class="badge badge-danger">FOR STOCK</span>';
							$po .='<a href="'. url('admin/purchase_order/project/print/purchase_order/' . base64_encode($purchase->id)).'" class="btn btn-sm btn-success" target="_blank">PO</a>';
						}
						
						if($val->getPurchaseReturnTotal() > 0){
		$so .= '<a href="'.url('admin/purchase_order/project/print/purchase_return/' .($val->getPurchaseReturn() ? base64_encode($val->getPurchaseReturn()->id) : '')).'" target="_blank"><span class="badge badge-success">RETURN</span></a>';
						}
					}
				}else{
					$so = '';
					$po = '';
					$budgeting = '';
				}
				
				if(in_array(1, session('bo_role')) || in_array(3, session('bo_role'))){
					$btnaction = '
					<a href="javascript:void(0);" class="btn btn-success btn-update btn-sm mr-1" data-id="'.$val->id.'" data-item="'.$val->item.'" data-user="'.$val->user->name.'" data-date="'.$val->date.'" data-total="'.number_format($val->total_nominal,2,',','.').'" data-status="'.$val->status.'" data-link="'.$val->link_type.'" data-linkid="'.$val->link_id.'" data-branch="'.$val->branch.'" data-coa="'.$val->coa_id.'" data-bill="'.$val->bill_to.'" data-popup="tooltip" title="Update Status Purchase Request"><i class="icon-upload"></i></a> 
					
					<a href="javascript:void(0);" class="btn btn-primary btn-pay btn-sm mr-1" data-id="'.$val->id.'" data-item="'.$val->item.'" data-user="'.$val->user->name.'" data-date="'.$val->date.'" data-total="'.number_format($val->total_nominal,2,',','.').'" data-status="'.$val->status.'" data-purchase="'.($val->link_type == 'project_purchases' ? $val->link_id : '').'" data-branch="'.$val->branch.'" data-popup="tooltip" title="Add Payment" onclick="getJournalInfo('.$val->id.')" data-cashbon="'.number_format($val->total_cash_advance,2,',','.').'" data-paid="'.number_format($val->totalPayment(),2,',','.').'"><i class="icon-cash"></i></a>';
				}
				
				if(in_array(1, session('bo_role')) || in_array(4, session('bo_role'))){
					$btnaction .= '<a href="javascript:void(0);" class="btn btn-secondary btn-sm" onclick="showJournal('.$val->id.')" data-popup="tooltip" title="Show Journal"><i class="icon-tree6"></i></a>'; 
				}
				
				$statuspembayaran = '';
				
				if($val->due_date){
					$bayar = $val->purchaseRequestPayment()->sum('nominal');
					
					if($bayar >= $val->total_nominal){
						$statuspembayaran = '<span class="badge badge-success">'.date('d M Y',strtotime($val->due_date)).'</span>';
					}else{
						if($val->due_date <= date('Y-m-d')){
							$statuspembayaran = '<span class="badge badge-danger">'.date('d M Y',strtotime($val->due_date)).'</span>';
						}else{
							$statuspembayaran = date('d M Y',strtotime($val->due_date));
						}
					}
					
				}else{
					$statuspembayaran = 'Empty';
				}
				
                $response['data'][] = [
					/* '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>', */
					'<span class="pick">'.$val->id.'</span>',
                    $val->user->name,
                    date('d M Y',strtotime($val->date)),
					$statuspembayaran,
					$val->branch().' '.($val->coa_id ? '<br>'.$val->coa->name : ''),
					$val->bill_to,
                    ($val->title == null || $val->title == '' ? (strlen($val->item) > 25 ? substr($val->item,0,25) : $val->item) : $val->title).'<a href="#collapse-link'.$val->id.'" class="font-weight-semibold" data-toggle="collapse"><b style="font-size:25px;"><i class="icon-info22"></i></b></a><div class="collapse" id="collapse-link'.$val->id.'"><div class="mt-3">'.$val->item.'</div></div>',
					number_format($val->total_cash_advance,2,',','.'),
					number_format($val->total_nominal,2,',','.'),
                    $val->status == 'RJCT' ? $val->status.' <a href="#collapse-link-reject'.$val->id.'" class="font-weight-semibold" data-toggle="collapse" style="color: #e3180e;"><b style="font-size:25px;"><i class="icon-info22" style="top: 3px;"></i></b></a><div class="collapse" id="collapse-link-reject'.$val->id.'"><div class="mt-3">'.$val->reject_reason.'</div></div>' : $val->status,
					$val->getPercentPay(),
                    $so.$po.$budgeting.$photo,
                    $btnaction
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
        $data   = PurchaseRequest::find($request->id);
        $string = '<div class="row">';

		$string .= '
			
		';

        $string .= '</div>';
		
        return response()->json($string);
    }

	public function getUserCustomerDeposit(){
		$customerdeposit = [];

		$date = date('Y-m-d');

		foreach(CashBank::whereHas('cashBankDetail',function($query){ $query->where('coa_id',67); })->whereRaw("DATE(date) <= '$date'")->get() as $rowcb){
			foreach($rowcb->cashBankDetail->where('coa_id',67)->where('type','1') as $rowcbdetail){
				if($rowcb->lookable_type == 'project_pays' || $rowcb->lookable_type == 'project_deliveries' || $rowcb->lookable_type == 'project_sale_returns' || $rowcb->lookable_type == 'project_bills'){
					$ada = false;
					$index = -1;
					foreach($customerdeposit as $key => $row){
						if($row['customer_id'] == $rowcb->lookable->project->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$customerdeposit[] = [
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
						$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
						$customerdeposit[$index]['detail'][] = [
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
					foreach($customerdeposit as $key => $row){
						if($row['customer_id'] == $rowcb->lookable->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$customerdeposit[] = [
							'customer_id'	=> $rowcb->lookable->customer_id,
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
						$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
						$customerdeposit[$index]['detail'][] = [
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
					foreach($customerdeposit as $key => $row){
						if($row['customer_id'] == $rowcb->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$customerdeposit[] = [
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
						$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
						$customerdeposit[$index]['detail'][] = [
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
						foreach($customerdeposit as $key => $row){
							if($row['customer_id'] == $rowcb->customer_id){
								$ada = true;
								$index = $key;
							}
						}
						if($ada == false){
							$customerdeposit[] = [
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
							$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
							$customerdeposit[$index]['detail'][] = [
								'date'			=> $rowcb->date,
								'type'			=> 'd',
								'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
								'description'	=> $rowcb->description
							];
						}
					}else{
						$ada = false;
						$index = -1;
						foreach($customerdeposit as $key => $row){
							if($row['customer_id'] == 0 && $row['branch'] == $rowcbdetail->branch){
								$ada = true;
								$index = $key;
							}
						}
						if($ada == false){
							$customerdeposit[] = [
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
							$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) - $rowcbdetail->nominal,2,',','.');
							$customerdeposit[$index]['detail'][] = [
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
					foreach($customerdeposit as $key => $row){
						if($row['customer_id'] == $rowcb->lookable->project->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$customerdeposit[] = [
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
						$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) + $rowcbdetail->nominal,2,',','.');
						$customerdeposit[$index]['detail'][] = [
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
					foreach($customerdeposit as $key => $row){
						if($row['customer_id'] == $rowcb->lookable->customer_id){
							$ada = true;
							$index = $key;
						}
					}
					if($ada == false){
						$customerdeposit[] = [
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
						$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) + $rowcbdetail->nominal,2,',','.');
						$customerdeposit[$index]['detail'][] = [
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
						foreach($customerdeposit as $key => $row){
							if($row['customer_id'] == $rowcb->customer_id){
								$ada = true;
								$index = $key;
							}
						}
						if($ada == false){
							$customerdeposit[] = [
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
							$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) + $rowcbdetail->nominal,2,',','.');
							$customerdeposit[$index]['detail'][] = [
								'date'			=> $rowcb->date,
								'type'			=> 'k',
								'nominal'		=> number_format(round($rowcbdetail->nominal),2,',','.'),
								'description'	=> $rowcb->description
							];
						}
					}else{
						$ada = false;
						$index = -1;
						foreach($customerdeposit as $key => $row){
							if($row['customer_id'] == 0 && $row['branch'] == $rowcbdetail->branch){
								$ada = true;
								$index = $key;
							}
						}
						if($ada == false){
							$customerdeposit[] = [
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
							$customerdeposit[$index]['total'] = number_format(str_replace(',','.',str_replace('.','',$customerdeposit[$index]['total'])) + $rowcbdetail->nominal,2,',','.');
							$customerdeposit[$index]['detail'][] = [
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

		return $customerdeposit;
	}
	
	public function user()
    {

		$resultcb = [];
		$resultfee = [];

		$branch = session('bo_branch');
		
		$projectsale = ProjectSale::where('mid_yes_no','1')->whereHas('user',function($query) use($branch){
			$query->where('branch',$branch);
		})->get();
		
		$total = 0;
		
		foreach($projectsale as $row){
			$total = str_replace(',','.',str_replace('.','',$row->getTotalMiddleman()));
			$totalrequest = $total;
			
			$rowpay = PurchaseRequest::where('link_type','project_sales')->where('link_id',$row->id)->get();
			
			if($rowpay){
				foreach($rowpay as $pay){
					$total -= $pay->totalPayment();
					$totalrequest -= $pay->total_nominal;
				}
			}
			
			if($total > 0 && $totalrequest > 0){
				$row['request'] = $totalrequest;
				$row['balance'] = $total;
				$resultcb[] = $row;
			}
		}
		
		$listfee = CashBank::where('code','like','FEE-PTA%')->get();
		
		foreach($listfee as $rowfee){
			$total_return = 0;

			if(count($rowfee->lookable->projectSaleReturn) > 0){
				foreach ($rowfee->lookable->projectSaleReturn as $row) {
					foreach (CashBank::whereHas('cashBankDetail')
					->where('lookable_type', 'like', '%project_sale_returns%')
					->where('lookable_id', $row->id)
					->get() as $rowcb) {
						foreach ($rowcb->cashBankDetail->where('coa_id', 332)->where('type', 1) as $rowcbd) {
							$total_return += $rowcbd->nominal;
						}
					}
				}
			}

			$total =  $rowfee->cashBankDetail()->first()->nominal - $total_return;

			foreach(PurchaseRequest::where('link_type','fee_pta')->where('link_id',$rowfee->id)->get() as $row){
				$total -= $row->total_nominal;
			}
			
			if($total > 0){
				$first = $rowfee->cashBankDetail()->first();
				$first['nominal'] = $total;
				$resultfee[] = $first;
			}
		}
		
		$totalPaid = 0;
		$totalUnpaid = 0;
		$totalRequest = 0;
		
		$pr = PurchaseRequest::where('user_id',session('bo_id'))->get();
		
		foreach($pr as $row){
			if($row->status == 'DONE' || $row->closed_by_acc){
				$totalPaid += $row->total_nominal;
			}elseif($row->status !== 'RJCT'){
				$totalUnpaid += $row->total_nominal;
			}
			
			if($row->status !== 'RJCT'){
				$totalRequest += $row->total_nominal;
			}
		}


	
		
        $data = [
			'totalPaid'			=> $totalPaid,
			'totalUnpaid'		=> $totalUnpaid,
			'totalRequest'		=> $totalRequest,
			'apCommission' 		=> $resultcb,
			'listFee'			=> $resultfee,
			'customerdeposit'   => $this->getUserCustomerDeposit(),
			'wip_coa' 			=> Coa::where('status', 1)->where('parent_id',340)->get(),	
            'title'   			=> 'Purchase Request',
            'content' 			=> 'admin.purchase_request'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function userAdd(Request $request)
    {
		if($request->middleman){
			$validation = Validator::make($request->all(), [
				'title'   		=> 'required',
				'item'   		=> 'required',
				'date'  		=> 'required',
				'nominal'   	=> 'required',
				'middle_list'	=> 'required'
			], [
				'title.required'    	=> 'Title cannot be empty.',
				'item.required'    		=> 'Note cannot be empty.',
				'date.required'   		=> 'Date cannot be empty.',
				'nominal.required'    	=> 'Nominal cannot be empty.',
				'middle_list.required'  => 'Middleman project cannot be empty.'
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'bill_to'   => 'required',
				'title'   		=> 'required',
				'date'  => 'required',
				'nominal'   => 'required',
				'supplier_id' => 'required',
				'termcount' => 'required',
				'due_date' => 'required'
			], [
				'bill_to.required'    => 'Bill to cannot be empty.',
				'title.required'    	=> 'Title cannot be empty.',
				'date.required'   => 'Date cannot be empty.',
				'nominal.required'    => 'Nominal cannot be empty.',
				'supplier_id.required'    => 'Supplier cannot be empty.',
				'termcount.required'	=> 'Term days cannot be empty.',
				'due_date.required'	=> 'Due date purchase payment cannot be empty.'
			]);
		}

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if(CheckCutOff::check($request->branch,substr($request->date,0,7))){
			
				if($request->due_date < $request->date){
					$response = [
						'status'  => 500,
						'message' => 'You cannot set due date below date request.'
					];

					return response()->json($response);
				}
				
				$query = PurchaseRequest::create([
					'date'	     			=> $request->date,
					'bill_to'				=> $request->bill_to,
					'title'					=> $request->title,
					'item'					=> $request->item,
					'user_id'				=> session('bo_id'),
					'branch'				=> $request->branch,
					'total_nominal'			=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'total_cash_advance'	=> str_replace(',','.',str_replace('.','',$request->cash_advance)),
					'status'				=> str_replace(',','.',str_replace('.','',$request->cash_advance)) > 0 ? 'PAID' : 'PEND',
					'image'					=> $request->has('file') ? $request->file('file')->store('public/purchase') : null,
					'term'					=> $request->term,
					'supplier_id'			=> $request->supplier_id,
					'term_days'				=> $request->termcount,
					'due_date'				=> $request->due_date
				]);
				
				if($request->middleman){
					$update = PurchaseRequest::find($query->id);
					
					$update->update([
						'link_type' => 'project_sales',
						'link_id'	=> $request->middle_list,
						'coa_id'	=> 281
					]);
				}
				
				if($request->fee_pta){
					$update = PurchaseRequest::find($query->id);
					
					$update->update([
						'link_type' => 'fee_pta',
						'link_id'	=> $request->fee_pta_list
					]);
				}

				if($request->customer_deposit){
					$update = PurchaseRequest::find($query->id);
					
					$update->update([
						'link_type' => 'customer_deposit',
						'link_id'	=> $request->customer_deposit_list,
						'coa_id'	=> 67
					]);
				}

				if($request->is_wip){
					$update = PurchaseRequest::find($query->id);
					
					$update->update([
						'wip_coa_id'	=> $request->wip_coa_id
					]);
				}

				if($query) {
					
					if(str_replace(',','.',str_replace('.','',$request->cash_advance)) <= 0){
					
						#send approval
						$roleapproval = array('4');
						Approval::sendApproval($roleapproval,'purchase_requests',$query->id,'approved_by',session('bo_id'));
						
						SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$query->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						
						if(session('bo_branch') == '2'){
							$roleapproval = 16;
							Approval::sendApproval($roleapproval,'purchase_requests',$query->id,'checked_by',session('bo_id'));
							
							SendMessage::send(env('SALES_MANAGER_JAKARTA_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$query->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						}
						#end approval
						
					}
					
					activity()
						->performedOn(new PurchaseRequest())
						->causedBy(session('bo_id'))
						->withProperties($query)
						->log('Add purchase request by user '.session('bo_name'));

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
			}else{
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
        }

        return response()->json($response);
    }
	
	public function userUpdate(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
			'title'   => 'required',
            'item'   => 'required',
            'date'  => 'required',
            'nominal'   => 'required'
        ], [
			'title.required'    => 'Title cannot be empty.',
            'item.required'    => 'Note cannot be empty.',
            'date.required'   => 'Date cannot be empty.',
            'nominal.required'    => 'Nominal cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = PurchaseRequest::find($id);
            
			if(CheckCutOff::check($query->branch,substr($query->date,0,7))){
			
				if($request->has('file')) {
					if(Storage::exists($query->image)) {
						Storage::delete($query->image);
					}

					$image = $request->file('file')->store('public/purchase');
				} else {
					$image = $query->image;
				}
				
				if($query->status == 'DONE' || ($query->link_type == 'project_purchases' && $query->link_id)){
					$query->update([
						'image'					=> $image,
					]);
				}else{
					
					$query->update([
						'date'	     			=> $request->date,
						'bill_to'				=> $request->bill_to,
						'title'					=> $request->title,
						'item'					=> $request->item,
						'total_nominal'			=> str_replace(',','.',str_replace('.','',$request->nominal)),
						'total_cash_advance'	=> str_replace(',','.',str_replace('.','',$request->cash_advance)),
						'image'					=> $image,
						'branch'				=> $request->branch,
						'term'					=> $request->term,
						'supplier_id'			=> $request->supplier_id,
						'term_days'				=> $request->termcount,
						'due_date'				=> $request->due_date,
						'status'				=> str_replace(',','.',str_replace('.','',$request->cash_advance)) > 0 && 
						str_replace(',','.',str_replace('.','',$request->nominal)) <= 0 ? 'PAID' : (str_replace(',','.',str_replace('.','',$request->nominal)) <= 0 ? 'APPR' : 'PEND'),
						'approved_by'			=> NULL
					]);
					
					$cek = PurchaseRequest::find($id);
				
					if($cek->link_type == 'project_purchases'){
						if($cek->project_purchase_bill_id){
							$ppb = ProjectPurchaseBill::find($cek->project_purchase_bill_id)->update([
								'date'		=> $cek->date,
								'due_date'	=> $cek->due_date,
								'nominal'	=> $cek->total_nominal
							]);
						}
					}
				}
				
				if($query) {
					
					if($query->status !== 'DONE'){
						if(str_replace(',','.',str_replace('.','',$request->nominal)) > 0){
						
							#send approval
							Approval::where('approvalable_type','purchase_requests')->where('approvalable_id',$query->id)->delete();
							
							$cb = CashBank::where('lookable_type','purchase_requests')->where('lookable_id',$query->id)->get();
							
							$cb1 = CashBank::where('code','PR-'.$query->id)->get();
							
							if(count($cb) > 0){
								foreach($cb as $row){
									$row->deleteDetail();
									$row->deleteFile();
									$row->delete();
								}
							}
							
							if(count($cb1) > 0){
								foreach($cb1 as $row){
									$row->deleteDetail();
									$row->deleteFile();
									$row->delete();
								}
							}
							
							$roleapproval = array('4');
							Approval::sendApproval($roleapproval,'purchase_requests',$query->id,'approved_by',session('bo_id'));
							#end approval
							
							SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$query->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
							
							if(session('bo_branch') == '2'){
								$roleapproval = 16;
								Approval::sendApproval($roleapproval,'purchase_requests',$query->id,'checked_by',session('bo_id'));
								
								SendMessage::send(env('SALES_MANAGER_JAKARTA_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$query->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
							}
						
						}
					}
					
					activity()
						->performedOn(new PurchaseRequest())
						->causedBy(session('bo_id'))
						->withProperties($query)
						->log('Change the customer purchase request');

					$response = [
						'status'  => 200,
						'message' => 'Data updated successfully.'
					];
				} else {
					$response = [
						'status'  => 500,
						'message' => 'Data failed to update.'
					];
				}
			}else{
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
        }

        return response()->json($response);
    }
	
	public function userDatatable(Request $request) 
    {
        $column = [
			'id',
            'date',
			'due_date',
			'bill_to',
            'item',
			'total_cash_advance',
            'total_nominal',
			'total_paid',
            'status',
            'image',
			'proof',
            'action'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = PurchaseRequest::count();
        $userID = UserRole::select('user_id')->where('role', session('bo_role'))->get();

		if(in_array(1, session('bo_role')) || in_array(9, session('bo_role'))){
			$query_data = PurchaseRequest::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
							->orWhere('bill_to', 'like', "%$search%")
                            ->orWhere('item', 'like', "%$search%")
							->orWhere('title', 'like', "%$search%")
							->orWhere('total_cash_advance', 'like', "%$search%")
							->orWhere('total_nominal', 'like', "%$search%")
							->orWhere('status', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }

                if($request->filter_type) {
					if($request->filter_type == '1'){
						$query->whereNull('image')->orWhere('image','');
					}elseif($request->filter_type == '2'){
						$query->whereHasMorph(
							'link',
							[ProjectPurchase::class],
							function (Builder $query) {
								$query->where('ppn','1');
							}
						);
					}
                }

				if($request->filter_status) {
					$query->where('status',$request->filter_status);
                }
				
				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date', '>=', $request->start_date)
                        ->whereDate('date', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }
				
            })->whereIn('user_id',$userID)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = PurchaseRequest::where(function($query) use ($search, $request) {
                 if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
							->orWhere('bill_to', 'like', "%$search%")
                            ->orWhere('item', 'like', "%$search%")
							->orWhere('title', 'like', "%$search%")
							->orWhere('total_cash_advance', 'like', "%$search%")
							->orWhere('total_nominal', 'like', "%$search%")
							->orWhere('status', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }

                if($request->filter_type) {
                    if($request->filter_type == '1'){
						$query->whereNull('image')->orWhere('image','');
					}elseif($request->filter_type == '2'){
						$query->whereHasMorph(
							'link',
							[ProjectPurchase::class],
							function (Builder $query) {
								$query->where('ppn','1');
							}
						);
					}
                }

				if($request->filter_status) {
					$query->where('status',$request->filter_status);
                }
				
				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date', '>=', $request->start_date)
                        ->whereDate('date', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }
            })->whereIn('user_id',$userID)
            ->count();
		}else{
			$query_data = PurchaseRequest::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
							->orWhere('bill_to', 'like', "%$search%")
                            ->orWhere('item', 'like', "%$search%")
							->orWhere('title', 'like', "%$search%")
							->orWhere('total_cash_advance', 'like', "%$search%")
							->orWhere('total_nominal', 'like', "%$search%")
							->orWhere('status', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }

                if($request->filter_type) {
					if($request->filter_type == '1'){
						$query->whereNull('image')->orWhere('image','');
					}elseif($request->filter_type == '2'){
						$query->whereHasMorph(
							'link',
							[ProjectPurchase::class],
							function (Builder $query) {
								$query->where('ppn','1');
							}
						);
					}
                }
				
				if($request->filter_status) {
					$query->where('status',$request->filter_status);
                }
				
				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date', '>=', $request->start_date)
                        ->whereDate('date', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }
				
            })->where('user_id',session('bo_id'))
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        	$total_filtered = PurchaseRequest::where(function($query) use ($search, $request) {
                 if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
							->orWhere('bill_to', 'like', "%$search%")
                            ->orWhere('item', 'like', "%$search%")
							->orWhere('title', 'like', "%$search%")
							->orWhere('total_cash_advance', 'like', "%$search%")
							->orWhere('total_nominal', 'like', "%$search%")
							->orWhere('status', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }

                if($request->filter_type) {
                    if($request->filter_type == '1'){
						$query->whereNull('image')->orWhere('image','');
					}elseif($request->filter_type == '2'){
						$query->whereHasMorph(
							'link',
							[ProjectPurchase::class],
							function (Builder $query) {
								$query->where('ppn','1');
							}
						);
					}
                }

				if($request->filter_status) {
					$query->where('status',$request->filter_status);
                }
				
				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date', '>=', $request->start_date)
                        ->whereDate('date', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }
            })->where('user_id',session('bo_id'))
            ->count();
		}
      

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
			
            foreach($query_data as $val) {
				
				if($val->image){
					if(explode('.',$val->image)[1] == 'pdf'){
						$photo = '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$val->item.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
					}
				}else{
					$photo = '<span class="badge badge-danger">Empty</span>';
				}
				
				$cb = CashBank::where('code','PRF-'.$val->id)->first();
				
				if($cb){
					$btnaction = '<span class="badge badge-success">Transferred</span>';
				}else{
					if($val->status == 'PEND' || $val->status == 'APPR'){
						if($val->status == 'PEND'){
							$btnaction = '
								<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
								<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
							';
						}elseif($val->status == 'APPR'){
							/* $btnaction = '
								<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
							'; */
							$btnaction = '
								<span class="badge badge-info">Approved</span>
							';
						}
					}else{
						/* if($val->status == 'DONE'){
							$btnaction = '
								<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
							';
						}else*/if($val->status == 'RJCT'){
							$btnaction = '
								<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
								<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
							';
						}else{
							$btnaction = '
								<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
							';
							//$btnaction = $val->status;
						}
					}
				}
				
				$paid_proof = '<span class="badge badge-danger">None</span>';
				
				$cekPaidProof = $val->purchaseRequestPayment()->latest()->first();
				
				if($cekPaidProof){
					$paid_proof = '';
				}
				
				foreach($val->purchaseRequestPayment as $key => $rowproof){
					if($rowproof->image){
						if(explode('.',$rowproof->image)[1] == 'pdf'){
							$paid_proof .= ($key + 1).'.<a href="' .$rowproof->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a> ';
						}else{
							$paid_proof .= ($key + 1).'.<a data-magnify="gallery" data-src="" data-caption="'.$rowproof->item.'" data-group="a" href="' .$rowproof->attachment() . '"><img src="' . $rowproof->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a> ';
						}
					}
				}
				
                $response['data'][] = [
					$val->id,
					$val->user->name,
                    date('d M Y',strtotime($val->date)),
					$val->due_date ? date('d M Y',strtotime($val->due_date)) : '-',
					$val->bill_to,
                    strtoupper($val->title).' - '.strtoupper($val->item),
					number_format($val->total_cash_advance,2,',','.'),
                    number_format($val->total_nominal,2,',','.'),
					number_format($val->totalPayment(),2,',','.'),
                    $val->status == 'RJCT' ? $val->status.' <a href="#collapse-link-reject'.$val->id.'" class="font-weight-semibold" data-toggle="collapse" style="color: #e3180e;"><b style="font-size:25px;"><i class="icon-info22" style="top: 3px;"></i></b></a><div class="collapse" id="collapse-link-reject'.$val->id.'"><div class="mt-3">'.strtoupper($val->reject_reason).'</div></div>' : $val->status,
                    $photo,
					$paid_proof,
                    $btnaction
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
	
	public function userShow(Request $request)
    {
        $data = PurchaseRequest::find($request->id);
        return response()->json([
			'bill_to' 		=> $data->bill_to,
			'title'			=> $data->title,
			'item' 			=> $data->item,
			'branch' 		=> $data->branch,
			'total_nominal'	=> number_format($data->total_nominal,0,',','.'),
			'cash_advance'	=> number_format($data->total_cash_advance,0,',','.'),
			'date'			=> $data->date,
			'term'			=> $data->term,
			'supplier_id'	=> $data->supplier_id,
			'supplier_name'	=> $data->supplier ? $data->supplier->name : '',
			'termcount'		=> $data->term_days,
			'due_date'		=> $data->due_date,
			'status'		=> $data->status
		]);
    }
	
	public function userDestroy(Request $request) 
    {
        $query = PurchaseRequest::find($request->id);
		
		if(CheckCutOff::check($query->branch,substr($query->date,0,7))){
		
			if($query->status == 'DONE'){
				$response = [
					'status'  => 500,
					'message' => 'You cannot add payment because this Purchase Request is closed/done.'
				];

				return response()->json($response);
			}
			
			foreach($query->purchaseRequestPayment as $rowpay){
				$rowpay->deleteFile();
				$rowpay->delete();
			}
			
			if($query->project_purchase_bill_id){
				$ppb = ProjectPurchaseBill::find($query->project_purchase_bill_id);
				if($ppb){
					$ppb->deleteFile();
					$ppb->delete();
				}
			}
			
			Approval::where('approvalable_type','purchase_requests')->where('approvalable_id',$request->id)->delete();
			
			$query->deleteFile();
			$query->delete();
			
			$cb = CashBank::where('lookable_id',$request->id)->where('lookable_type','purchase_requests')->get();
				
			foreach($cb as $row){
				$row->deleteDetail();
				$row->delete();
			}
			
			if($query) {
				activity()
					->performedOn(new PurchaseRequest())
					->causedBy(session('bo_id'))
					->log('Delete the Purchase request data');

				$response = [
					'status'  => 200,
					'message' => 'Data deleted successfully.'
				];
			} else {
				$response = [
					'status'  => 500,
					'message' => 'Data failed to delete.'
				];
			}
			
		}else{
			$response = [
				'status'  => 503,
				'message' => 'You cannot add/edit. The journal for this month was already closed.'
			];
		}

        return response()->json($response);
    }
	
	public function updateStatus(Request $request, $id)
    {
		if($request->status == 'RJCT'){
			$validation = Validator::make($request->all(), [
				'nominal'   	=> 'required',
				'reject_reason' => 'required'
			], [
				'nominal.required'    		=> 'Nominal cannot be empty.',
				'reject_reason.required'    => 'Please describe your reason.'
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'nominal'   	=> 'required',
			], [
				'nominal.required'    	=> 'Nominal cannot be empty.',
			]);
		}

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$query = PurchaseRequest::find($id);
			$purchase_request_payment = PurchaseRequestPayment::where('purchase_request_id', $query->id)->orderBy('date_paid','DESC')->first();
			$date_paid = $purchase_request_payment ? $purchase_request_payment->date_paid : $query->date;
			
			// if(CheckCutOff::check($query->branch,substr($query->date,0,7))){
			if(CheckCutOff::check($query->branch,substr($date_paid,0,7)) || $request->status == 'DONE' || $request->status == 'RJCT'){
			
				if($query->status == 'DONE' || $query->status == 'RJCT'){
					$response = [
						'status'  => 500,
						'message' => 'You cannot change this status because this Purchase Request is closed/done.'
					];

					return response()->json($response);
				}
				
				if(in_array($query->status, array('APPR','PAID','RCVD','SUBM','RJCT'))){
				
					$query->update([
						'item'					=> $request->item,
						'date'					=> $request->date_request,
						'branch'				=> $request->branch,
						/* 'coa_id'				=> $request->coa_update ? $request->coa_update : NULL, */
						'status'				=> $request->status ? $request->status : 'APPR',
						'reject_reason'			=> $request->reject_reason ? $request->reject_reason : '',
						'approved_by'			=> $request->status == 'PEND' ? NULL : $query->approved_by,
						'checked_by'			=> $request->status == 'PEND' ? NULL : $query->checked_by,
						'fixed_cost'			=> $request->fixed_cost ? '1' : NULL,
						'fixed_month'			=> $request->fixed_cost ? $request->fixed_month : NULL
					]);
					
					if($request->fixed_cost){
						$date = $query->date;
						
						for($i=0;$i<$request->fixed_month;$i++){
							$date = date('Y-m-'.str_pad($request->fixed_date, 2, '0', STR_PAD_LEFT), strtotime('+1 months', strtotime($date)));
							
							$query2 = PurchaseRequest::create([
								'date'	     			=> $date,
								'bill_to'				=> $query->bill_to,
								'title'					=> $query->title,
								'item'					=> $query->item,
								'user_id'				=> session('bo_id'),
								'branch'				=> $query->branch,
								'total_nominal'			=> $query->total_nominal,
								'total_cash_advance'	=> $query->total_cash_advance,
								'status'				=> $query->total_cash_advance > 0 ? 'PAID' : 'PEND',
								'term'					=> $query->term,
								'supplier_id'			=> $query->supplier_id,
								'term_days'				=> $query->term_days,
								'due_date'				=> $date,
								'fixed_ref'				=> $query->id,
								'link_type' 			=> $query->link_type,
								'link_id'				=> $query->link_id
							]);
							
							if($query2) {
								
								if($query2->total_cash_advance <= 0){
								
									#send approval
									$roleapproval = array('4');
									Approval::sendApproval($roleapproval,'purchase_requests',$query2->id,'approved_by',session('bo_id'));
									
									SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$query2->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
									
									if(session('bo_branch') == '2'){
										$roleapproval = 16;
										Approval::sendApproval($roleapproval,'purchase_requests',$query2->id,'checked_by',session('bo_id'));
										
										SendMessage::send(env('SALES_MANAGER_JAKARTA_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$query2->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
									}
									#end approval
									
								}
								
								activity()
									->performedOn(new PurchaseRequest())
									->causedBy(session('bo_id'))
									->withProperties($query2)
									->log('Add purchase request by user '.session('bo_name'));
									
							}
						}
					}
					
					$cb = CashBank::where('lookable_id',$id)->where('lookable_type','purchase_requests')->first();
					
					if(!$cb){
						$cb = CashBank::where('code','PR-'.$id)->first();
					}
					
					if($request->status == 'RJCT' || $request->status == 'PEND'){
						/* if($cb){
							$cb->deleteDetail();
							$cb->deleteFile();
							$cb->delete();
						} */
						
						if($cb){
							$cb->update([
								'code'			=> $cb->code.'-RJCT',
								'lookable_type'	=> $cb->lookable_type == "project_deliveries" ?  $cb->lookable_type : NULL,
								'lookable_id'	=> $cb->lookable_type == "project_deliveries" ? $cb->lookable_id : NULL
							]);
							
							if($query->link_type !== 'fee_pta'){
								if($query->link_type == "project_purchases"){
								}else{
									if($query->link_type != "project_sales" && count($cb->cashBankDetail()->where('type','1')->get()) > 1 || $query->link_type != "project_sales" && count($cb->cashBankDetail()->where('type','2')->get()) > 1){
										$cbRJCT = CashBank::create([
											'user_id'     			=> session('bo_id'),
											'supplier_id'			=> $query->supplier_id,
											'request_date'			=> $query->date,
											'due_date'				=> $query->due_date,
											'code'        			=> 'PR-'.$query->id.'-RJCT-CLOSE',
											'date'        			=> date('Y-m-d'),
											'type'        			=> '3',
											'description' 			=> $query->title.' - '.$query->item
										]);
										if($cbRJCT){
											foreach ($cb->cashBankDetail()->where('type','2')->get() as $key => $rowdb) {
												CashBankDetail::create([
													'cash_bank_id' 	=> $cbRJCT->id,
													'coa_id'       	=> $rowdb->coa_id,
													'branch'		=> $rowdb->branch,
													'type'       	=> '1',
													'nominal'      	=> $rowdb->nominal,
													'note'         	=> $query->title.' - '.$query->item
												]);
												
												Journal::insert([
													'date_transaction' => date('Y-m-d'),
													'journalable_type' => 'cash_banks',
													'journalable_id'   => $cbRJCT->id,
													'coa_id'           => $rowdb->coa_id,
													'branch'		   => $rowdb->branch,
													'type'	           => '1',
													'nominal'          => $rowdb->nominal,
													'created_at'       => date('Y-m-d', strtotime($cbRJCT->date)) . ' ' . date('H:i:s'),
													'updated_at'       => date('Y-m-d H:i:s')
												]);
											}
											foreach ($cb->cashBankDetail()->where('type','1')->get() as $key => $rowcr) {
												CashBankDetail::create([
													'cash_bank_id' 	=> $cbRJCT->id,
													'coa_id'       	=> $rowcr->coa_id,
													'branch'		=> $query->branch,
													'type'       	=> '2',
													'nominal'      	=> $rowcr->nominal,
													'note'         	=> $query->title.' - '.$query->item
												]);
		
												Journal::insert([
													'date_transaction' => date('Y-m-d'),
													'journalable_type' => 'cash_banks',
													'journalable_id'   => $cbRJCT->id,
													'coa_id'           => $rowcr->coa_id,
													'branch'		   => $rowcr->branch,
													'type'	           => '2',
													'nominal'          => $rowcr->nominal,
													'created_at'       => date('Y-m-d', strtotime($cbRJCT->date)) . ' ' . date('H:i:s'),
													'updated_at'       => date('Y-m-d H:i:s')
												]);
											}
										}
									}else{
										
										if($query->link_type == "project_sales"){
											// AP Comission
											$debetcb = 281;
											// Middleman & project commision 
											$kreditcb = 299;
										}else{
											$debetcb = $query->coa_id ? $query->coa_id : 332;
											$kreditcb = $cb->cashBankDetail()->where('type','1')->first()->coa_id ? $cb->cashBankDetail()->where('type','1')->first()->coa_id : 48;
										}
										
										
										$cb = CashBank::create([
											'user_id'     			=> session('bo_id'),
											'supplier_id'			=> $query->supplier_id,
											'request_date'			=> $query->date,
											'due_date'				=> $query->due_date,
											'code'        			=> 'PR-'.$query->id.'-RJCT-CLOSE',
											'date'        			=> date('Y-m-d'),
											'type'        			=> '3',
											'description' 			=> $query->title.' - '.$query->item
										]);
										
										if($cb){
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $query->branch,
												'type'       	=> '1',
												'nominal'      	=> $query->total_nominal,
												'note'         	=> $query->title.' - '.$query->item
											]);
											
											Journal::insert([
												'date_transaction' => date('Y-m-d'),
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $debetcb,
												'branch'		   => $query->branch,
												'type'	           => '1',
												'nominal'          => $query->total_nominal,
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
											
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $kreditcb,
												'branch'		=> $query->branch,
												'type'       	=> '2',
												'nominal'      	=> $query->total_nominal,
												'note'         	=> $query->title.' - '.$query->item
											]);
	
											Journal::insert([
												'date_transaction' => date('Y-m-d'),
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $kreditcb,
												'branch'		   => $query->branch,
												'type'	           => '2',
												'nominal'          => $query->total_nominal,
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
									}
								}
							}
									
							if($request->status == 'PEND'){
								Approval::where('approvalable_type','purchase_requests')->where('approvalable_id',$query->id)->update([
									'approved_by'	=> NULL,
									'seen'			=> 0,
								]);
							}
						}else{
							if($query->link_type !== 'fee_pta'){
								if($query->link_type == "project_purchases"){

									$cbRJCTPP = CashBank::where('lookable_id', $query->project_warehouse_id)->where('lookable_type', 'project_warehouses')->first();

									$isExist =  CashBank::where('code','like', "%$cbRJCTPP->code-RJCT%")->where('code','not like', '%CLOSE%')->where('lookable_type', 'project_warehouses')->get();

									if($cbRJCTPP){

										$cbRJCTPP->update([
											'code'			=> count($isExist) > 0 ?  $cbRJCTPP->code.'-RJCT-'.count($isExist) + 1 : $cbRJCTPP->code.'-RJCT',
											'lookable_type'	=> NULL,
											'lookable_id'	=> NULL
										]);

										$projectwarehouse = ProjectWarehouse::find($query->project_warehouse_id);

										foreach($projectwarehouse->projectWarehouseProduct as $row){
											#updatestock
											$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$projectwarehouse->warehouse_id)->where('branch',$projectwarehouse->projectPurchase->sales->branch)->first();
											if($cek){
												$cek->update([
													'qty' 	=> $cek->qty - $row->qty,
													'unit'	=> $row->unit
												]);
											}else{
												Stock::create([
													'product_id'	=> $row->product_id,
													'warehouse_id'	=> $projectwarehouse->warehouse_id,
													'qty'			=> -$row->qty,
													'unit'			=> $row->unit,
													'branch'		=> $projectwarehouse->projectPurchase->sales->branch
												]);
											}
											
											$row->update([
												'qty' 	=> $row->qty - $row->qty,
												'unit'	=> $row->unit
											]);
										}

										$pp = ProjectPurchase::find($projectwarehouse->project_purchase_id);
										// $totalPurchase = $projectwarehouse->getTotal()['totalpurchase'];
										$type = '2';
										$description = 'Warehouse receive '.$projectwarehouse->code.' From PO Code '.$pp->code.'-RJCT-CLOSE';
										

										$cb = CashBank::create([
											'user_id'     		=> session('bo_id'),
											'lookable_type'  	=> 'project_warehouses',
											'lookable_id'		=> $projectwarehouse->id,
											'supplier_id'		=> $projectwarehouse->projectPurchase->supplier_id,
											'code'        		=> count($isExist) > 0 ? $cbRJCTPP->code.'-CLOSE-'.count($isExist)+ 1 : $cbRJCTPP->code.'-CLOSE',
											'date'        		=> date('Y-m-d'),
											'type'        		=> $type,
											'description' 		=> $description
										]);
										
										if($cb){
											
											foreach ($cbRJCTPP->cashBankDetail()->where('type','2')->get() as $key => $rowdb) {
												CashBankDetail::create([
													'cash_bank_id' 	=> $cb->id,
													'coa_id'       	=> $rowdb->coa_id,
													'branch'		=> $rowdb->branch,
													'type'       	=> '1',
													'nominal'      	=> $rowdb->nominal,
													'note'         	=> $description
												]);
												
												Journal::insert([
													'date_transaction' => date('Y-m-d'),
													'journalable_type' => 'cash_banks',
													'journalable_id'   => $cb->id,
													'coa_id'           => $rowdb->coa_id,
													'branch'		   => $rowdb->branch,
													'type'	           => '1',
													'nominal'          => $rowdb->nominal,
													'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
													'updated_at'       => date('Y-m-d H:i:s')
												]);
											}
											foreach ($cbRJCTPP->cashBankDetail()->where('type','1')->get() as $key => $rowcr) {
												CashBankDetail::create([
													'cash_bank_id' 	=> $cb->id,
													'coa_id'       	=> $rowcr->coa_id,
													'branch'		=> $query->branch,
													'type'       	=> '2',
													'nominal'      	=> $rowcr->nominal,
													'note'         	=> $description
												]);
		
												Journal::insert([
													'date_transaction' => date('Y-m-d'),
													'journalable_type' => 'cash_banks',
													'journalable_id'   => $cb->id,
													'coa_id'           => $rowcr->coa_id,
													'branch'		   => $rowcr->branch,
													'type'	           => '2',
													'nominal'          => $rowcr->nominal,
													'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
													'updated_at'       => date('Y-m-d H:i:s')
												]);
											}
										}

										if($pp->projectPurchasePayment){
											foreach ($pp->projectPurchasePayment as $ppp) {
												// cb di update jadi rjct terus buat cb baru di jurnal balik
												$cbPPP = CashBank::where('lookable_id', $ppp->id)->where('lookable_type', 'project_payments')->first();
								
												$isExist_2 =  CashBank::where('code','like', "%$cbPPP->code-RJCT%")->where('code','not like', '%CLOSE%')->where('lookable_type', 'project_payments')->get();

												if($cbPPP){
													$cbPPP->update([
														'code'			=> count($isExist_2) > 0 ?  $cbPPP->code.'-RJCT-'. count($isExist_2) + 1 : $cbPPP->code.'-RJCT',
														'lookable_type'	=> NULL,
														'lookable_id'	=> NULL
													]);

													$cb = CashBank::create([
														'user_id'     		=> session('bo_id'),
														'lookable_type'  	=> 'project_payments',
														'lookable_id'		=> $ppp->id,
														'supplier_id'		=> $cbPPP->supplier_id,
														'code'        		=> count($isExist_2) > 0 ? $cbPPP->code.'-CLOSE-' . count($isExist_2) + 1 : $cbPPP->code.'-CLOSE',
														'date'        		=> date('Y-m-d'),
														'type'        		=> $type,
														'description' 		=> $description
													]);
	
													foreach ($cbPPP->cashBankDetail()->where('type','2')->get() as $key => $rowdb) {
														CashBankDetail::create([
															'cash_bank_id' 	=> $cb->id,
															'coa_id'       	=> $rowdb->coa_id,
															'branch'		=> $rowdb->branch,
															'type'       	=> '1',
															'nominal'      	=> $rowdb->nominal,
															'note'         	=> $description
														]);
														
														Journal::insert([
															'date_transaction' => date('Y-m-d'),
															'journalable_type' => 'cash_banks',
															'journalable_id'   => $cb->id,
															'coa_id'           => $rowdb->coa_id,
															'branch'		   => $rowdb->branch,
															'type'	           => '1',
															'nominal'          => $rowdb->nominal,
															'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
															'updated_at'       => date('Y-m-d H:i:s')
														]);
													}
	
													foreach ($cbPPP->cashBankDetail()->where('type','1')->get() as $key => $rowcr) {
														CashBankDetail::create([
															'cash_bank_id' 	=> $cb->id,
															'coa_id'       	=> $rowcr->coa_id,
															'branch'		=> $rowcr->branch,
															'type'       	=> '1',
															'nominal'      	=> $rowcr->nominal,
															'note'         	=> $description
														]);
														
														Journal::insert([
															'date_transaction' => date('Y-m-d'),
															'journalable_type' => 'cash_banks',
															'journalable_id'   => $cb->id,
															'coa_id'           => $rowcr->coa_id,
															'branch'		   => $rowcr->branch,
															'type'	           => '1',
															'nominal'          => $rowcr->nominal,
															'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
															'updated_at'       => date('Y-m-d H:i:s')
														]);
													}
												}

											}
										}
									}
								}
							}
						}
					}else{
						if($request->status == 'DONE'){
							
							if($query->link_type !== 'project_purchases'){
								
								$totalnominal = $query->total_nominal;
								if($query->purchaseRequestPayment != null){
									foreach($query->purchaseRequestPayment as $key => $rowprp){
									
										$totalnominal -= $rowprp->nominal;
										
										$cekkuy = null;
										
										$cekkuy = CashBank::where('code','PRP-'.$rowprp->id)->first();
										// FEE PTA, KARENA TIDAK PUNYA JURNAL AWAL PR MAKA PELUNASAN DIBUAT SAAT SUDAH DONE
										if(!$cekkuy && !$rowprp->purchase_request_main_payment_id){
											
											$cb2 = CashBank::create([
												'user_id'     			=> session('bo_id'),
												'lookable_id'			=> $rowprp->id,
												'lookable_type'			=> 'purchase_request_payments',
												'supplier_id'			=> $query->supplier_id,
												'request_date'			=> $query->date,
												'due_date'				=> $query->due_date,
												'code'        			=> 'PRP-'.$rowprp->id,
												'date'        			=> $rowprp->date_paid,
												'type'        			=> '2',
												'image'					=> $rowprp->image,
												'description' 			=> $query->item.' '.$query->title.' '.$rowprp->note
											]);
											
											$debetcb = $query->coa_id ? $query->coa_id : 332;
																
											$kreditcb = $rowprp->coa_id;
											
											$cb_ref = $cb2->id;
											
											if($cb2){
												CashBankDetail::create([
													'cash_bank_id' 	=> $cb2->id,
													'coa_id'       	=> $debetcb,
													'branch'		=> $query->branch,
													'type'       	=> '1',
													'nominal'      	=> $totalnominal > 0 ? $rowprp->nominal : ($rowprp->nominal + $totalnominal),
													'note'         	=> $query->title.' '.$query->item.' '.$rowprp->note
												]);
												
												Journal::insert([
													'date_transaction' => $rowprp->date_paid,
													'journalable_type' => 'cash_banks',
													'journalable_id'   => $cb2->id,
													'coa_id'           => $debetcb,
													'branch'		   => $query->branch,
													'type'	           => '1',
													'nominal'          => $totalnominal > 0 ? $rowprp->nominal : ($rowprp->nominal + $totalnominal),
													'created_at'       => date('Y-m-d', strtotime($cb2->date)) . ' ' . date('H:i:s'),
													'updated_at'       => date('Y-m-d H:i:s')
												]);
												
												CashBankDetail::create([
													'cash_bank_id' 	=> $cb2->id,
													'coa_id'       	=> $kreditcb,
													'branch'		=> $rowprp->branch,
													'type'       	=> '2',
													'nominal'      	=> $rowprp->nominal,
													'note'         	=> $query->title.' '.$query->item.' '.$rowprp->note
												]);
	
												Journal::insert([
													'date_transaction' => $rowprp->date_paid,
													'journalable_type' => 'cash_banks',
													'journalable_id'   => $cb2->id,
													'coa_id'           => $kreditcb,
													'branch'		   => $rowprp->branch,
													'type'	           => '2',
													'nominal'          => $rowprp->nominal,
													'created_at'       => date('Y-m-d', strtotime($cb2->date)) . ' ' . date('H:i:s'),
													'updated_at'       => date('Y-m-d H:i:s')
												]);
												
												if(isset($cb2) && $key == (count($query->purchaseRequestPayment) - 1) && $totalnominal < 0){
													CashBankDetail::create([
														'cash_bank_id' 	=> $cb2->id,
														'coa_id'       	=> 48,
														'branch'		=> $rowprp->branch,
														'type'       	=> '1',
														'nominal'      	=> $totalnominal * -1,
														'note'         	=> $query->title.' '.$query->item.' '.$rowprp->note
													]);
	
													Journal::insert([
														'date_transaction' => $rowprp->date_paid,
														'journalable_type' => 'cash_banks',
														'journalable_id'   => $cb2->id,
														'coa_id'           => 48,
														'branch'		   => $rowprp->branch,
														'type'	           => '1',
														'nominal'          => $totalnominal * -1,
														'created_at'       => date('Y-m-d', strtotime($cb2->date)) . ' ' . date('H:i:s'),
														'updated_at'       => date('Y-m-d H:i:s')
													]);
												}
											}
											
											BalanceHistory::create([
												'user_id' 					=> session('bo_id'),
												'nominal'					=> $rowprp->nominal,
												'type'						=> 'OUT',
												'cash_or_bank'				=> in_array($rowprp->coa_id,array(2,5,345)) ? 'CASH' : 'BANK',
												'coa_id'					=> $rowprp->coa_id,
												'cash_bank_reference'		=> $cb2->id,
												'branch'					=> $rowprp->branch,
												'note'						=> $rowprp->purchaseRequest->title.' - '.$rowprp->purchaseRequest->item.' - '.$rowprp->note,
												'date'						=> $rowprp->date_paid
											]);
										}
									}
								}
								
								
								if($totalnominal > 0){
									
									$query = PurchaseRequest::find($id);

									$prp = PurchaseRequestPayment::create([
										'user_id'	     		=> session('bo_id'),
										'purchase_request_id'	=> $query->id,
										'date_paid'				=> date('Y-m-d'),
										'branch'				=> $query->branch,
										'coa_id'				=> 230,
										'nominal'				=> $totalnominal,
										'image'					=> NULL,
										'code'					=> NULL,
										'due_date'				=> date('Y-m-d'),
										'note'					=> 'Auto pay, because Finance DONE this Purchase Request.'
									]);
									
									if($query->link_type == "project_sales"){
										$debetcb = 281;
									}else{
										$debetcb = $query->coa_id ? $query->coa_id : 332;
									}
									$kreditcb = 230;
									
									$cbclosing = CashBank::create([
										'user_id'     		=> session('bo_id'),
										'lookable_type'		=> 'purchase_request_payments',
										'lookable_id'		=> $prp->id,
										'code'        		=> 'PRP-'.$prp->id,
										'date'        		=> date('Y-m-d'),
										'type'        		=> '3',
										'description' 		=> 'Auto pay, because Finance DONE this Purchase Request.'
									]);
									
									if($cbclosing){

										$cekpr = CashBank::where('code','like','PR-'.$query->id)->first();
										#debit
										if($cekpr && count($cekpr->cashBankDetail()->where('type','2')->get()) > 1){
											$remaining_payment = $totalnominal;
											foreach ($cekpr->cashBankDetail()->where('type','2')->get() as $key => $rowdb) {
												if($remaining_payment > 0  && !$this->checkPr($rowdb->coa_id, $rowdb->nominal,$rowdb->branch, $query->id)['isValid']){

													$remaining_bill = $this->checkPr($rowdb->coa_id, $rowdb->nominal,$rowdb->branch, $query->id)['remainingBill'];

													CashBankDetail::create([
														'cash_bank_id' 	=> $cbclosing->id,
														'coa_id'       	=> $rowdb->coa_id,
														'branch'		=> $rowdb->branch,
														'type'       	=> '1',
														'nominal'      	=> $remaining_payment - $remaining_bill > 0 ? $remaining_bill : $remaining_payment,
														'note'         	=> 'Auto pay, because Finance DONE this Purchase Request.'
													]);
													
													Journal::insert([
														'date_transaction' => date('Y-m-d'),
														'journalable_type' => 'cash_banks',
														'journalable_id'   => $cb->id,
														'coa_id'           => $rowdb->coa_id,
														'branch'		   => $rowdb->branch,
														'type'	           => '1',
														'nominal'          => $remaining_payment - $remaining_bill > 0 ? $remaining_bill : $remaining_payment,
														'created_at'       => date('Y-m-d', strtotime($cbclosing->date)) . ' ' . date('H:i:s'),
														'updated_at'       => date('Y-m-d H:i:s')
													]);

													$remaining_payment -= $remaining_bill;
												}
											}
										}else{
											CashBankDetail::create([
												'cash_bank_id' 	=> $cbclosing->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $query->branch,
												'type'       	=> '1',
												'nominal'      	=> $totalnominal,
												'note'         	=> 'Auto pay, because Finance DONE this Purchase Request.'
											]);

											Journal::insert([
												'date_transaction' => date('Y-m-d'),
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cbclosing->id,
												'coa_id'           => $debetcb,
												'branch'		   => $query->branch,
												'type'	           => '1',
												'nominal'          => $totalnominal,
												'created_at'       => date('Y-m-d', strtotime($cbclosing->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
										#kredit
										
										CashBankDetail::create([
											'cash_bank_id' 	=> $cbclosing->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $query->branch,
											'type'       	=> '2',
											'nominal'      	=> $totalnominal,
											'note'         	=> 'Auto pay, because Finance DONE this Purchase Request.'
										]);
										
										Journal::insert([
											'date_transaction' => date('Y-m-d'),
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cbclosing->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $query->branch,
											'type'	           => '2',
											'nominal'          => $totalnominal,
											'created_at'       => date('Y-m-d', strtotime($cbclosing->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}
							}
						}
						
						if($cb){
							$cb->update([
								'date' => $request->date_request
							]);
							
							foreach($cb->cashBankDetail as $row){
								CashBankDetail::find($row->id)->update([
									/* 'branch' => $request->branch, */
									/* 'nominal' => str_replace(',','.',str_replace('.','',$request->nominal)) */
								]);
							}
							
							$jurnal = Journal::where('journalable_type','cash_banks')->where('journalable_id',$cb->id)->get();
							
							foreach($jurnal as $row){
								Journal::find($row->id)->update([
									'date_transaction'	=> $request->date_request,
									/* 'branch' => $request->branch, */
									/* 'nominal' => str_replace(',','.',str_replace('.','',$request->nominal)) */
								]);
							}
						}
					}
					
					if($query) {
						activity()
							->performedOn(new PurchaseRequest())
							->causedBy(session('bo_id'))
							->withProperties($query)
							->log('Change the customer purchase request from Finance');

						$response = [
							'status'  => 200,
							'message' => 'Data updated successfully.'
						];
					} else {
						$response = [
							'status'  => 500,
							'message' => 'Data failed to update.'
						];
					}
					
				}else{
					$response = [
						'status'  => 500,
						'message' => 'You cannot change this status before approved by Accounting.'
					];
				}
			}else{
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
        }

        return response()->json($response);
    }
	
	public function getPayment(Request $request)
	{
		$data = PurchaseRequestPayment::where('purchase_request_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$image = '';
				
			if($row->image){
				$image = explode('.',$row->image)[1] == 'pdf' ? '<a href="' .$row->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->item.'" data-group="a" href="' .$row->attachment() . '"><img src="' . $row->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
			}else{
				if($row->purchaseRequestMainPayment()->exists()){
					$image = explode('.',$row->purchaseRequestMainPayment->image)[1] == 'pdf' ? '<a href="' .$row->purchaseRequestMainPayment->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->item.'" data-group="a" href="' .$row->purchaseRequestMainPayment->attachment() . '"><img src="' . $row->purchaseRequestMainPayment->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
				}
			}
			
			$result[] = [
				'id'		=> $row->id,
				'date'		=> date('d M Y',strtotime($row->date_paid)),
				'branch'	=> $row->branch(),
				'source'	=> $row->coa ? $row->coa->name : '',
				'nominal'	=> number_format($row->nominal,'2',',','.'),
				'proof'		=> $image,
				'code'		=> $row->code ? $row->code : '-',
				'due_date'	=> $row->due_date ? date('d M Y',strtotime($row->due_date)) : '-',
				'note'		=> $row->note ? $row->note : '-'		
			];
		}
		
		return response()->json($result);
	}
	
	public function addMultiPayment(Request $request)
	{
		$validation = Validator::make($request->all(), [
			'source_date' 				=> 'required',
			'source_file' 				=> 'required',
			'source_branch'				=> 'required',
			'source_coa' 				=> 'required',
			'source_nominal' 			=> 'required',
			'source_note'				=> 'required',
			'purchase_detail' 			=> 'required|array',
			'purchase_nominal_detail'	=> 'required|array',
		], [
			'source_date.required'   				=> 'Date cannot be empty.',
			'source_file.required'   				=> 'Proof file cannot be empty.',
			'source_branch.required'   				=> 'Source branch cannot be empty.',
			'source_coa.required'   				=> 'Source coa cannot be empty.',
			'source_nominal.required'   			=> 'Source nominal cannot be empty.',
			'source_note.required'   				=> 'Source note cannot be empty.',
			'purchase_detail.required'				=> 'Purchase request cannot be empty.',
			'purchase_detail.array'					=> 'Purchase request must be an array.',
			'purchase_nominal_detail.required'		=> 'Purchase request nominal cannot be empty.',
			'purchase_nominal_detail.array'			=> 'Purchase request nominal must be an array.',
		]);

        if($validation->fails()) { 
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if(CheckCutOff::check($request->source_branch,substr($request->source_date,0,7))){
			
				$prmp = PurchaseRequestMainPayment::create([
					'user_id'		=> session('bo_id'),
					'date_paid'		=> $request->source_date,
					'branch'		=> $request->source_branch,
					'coa_id'		=> $request->source_coa,
					'nominal'		=> str_replace(',','.',str_replace('.','',$request->source_nominal)),
					'image'			=> $request->has('source_file') ? $request->file('source_file')->store('public/purchase') : null,
					'code'			=> $request->source_code,
					'due_date'		=> $request->source_due_date,
					'note'			=> $request->source_note
				]);
				
				$cb = CashBank::create([
					'user_id'     		=> session('bo_id'),
					'lookable_type'     => 'purchase_payment_main_payments',
					'lookable_id'		=> $prmp->id,
					'supplier_id'		=> 132,
					'code'        		=> 'PRMP-'.$prmp->id,
					'date'        		=> $request->source_date,
					'type'        		=> '2',
					'description' 		=> 'Purchase Request Multi Payment ID - '.$prmp->id
				]);
				
				if($cb){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $request->source_due_date ? 30 : $request->source_coa,
						'branch'		=> $request->source_branch,
						'type'       	=> '2',
						'nominal'      	=> str_replace(',','.',str_replace('.','',$request->source_nominal)),
						'note'         	=> 'Purchase Request Multi Payment ID - '.$prmp->id
					]);

					Journal::insert([
						'date_transaction' => $request->source_date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $request->source_due_date ? 30 : $request->source_coa,
						'branch'		   => $request->source_branch,
						'type'	           => '2',
						'nominal'          => str_replace(',','.',str_replace('.','',$request->source_nominal)),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					$cb_ref = $cb->id;
				}
				
				if($request->source_due_date){
					$debetcb = 30;
									
					$kreditcb = $request->source_coa;
					
					$cb2 = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'     => 'purchase_request_main_payments',
						'lookable_id'		=> $prmp->id,
						'supplier_id'		=> 132,
						'code'        		=> 'PRMP-'.$prmp->id,
						'date'        		=> $request->source_due_date,
						'type'        		=> '2',
						'description' 		=> 'Purchase Request Multi Payment ID - '.$prmp->id
					]);
					
					$cb_ref = $cb2->id;
					
					if($cb2){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb2->id,
							'coa_id'       	=> $debetcb,
							'branch'		=> $prmp->branch,
							'type'       	=> '1',
							'nominal'      	=> str_replace(',','.',str_replace('.','',$request->source_nominal)),
							'note'         	=> 'Purchase Request Multi Payment ID - '.$prmp->id
						]);
						
						Journal::insert([
							'date_transaction' => $request->source_due_date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb2->id,
							'coa_id'           => $debetcb,
							'branch'		   => $prmp->branch,
							'type'	           => '1',
							'nominal'          => str_replace(',','.',str_replace('.','',$request->source_nominal)),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb2->id,
							'coa_id'       	=> $kreditcb,
							'branch'		=> $prmp->branch,
							'type'       	=> '2',
							'nominal'      	=> str_replace(',','.',str_replace('.','',$request->source_nominal)),
							'note'         	=> ''
						]);

						Journal::insert([
							'date_transaction' => $request->source_due_date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb2->id,
							'coa_id'           => $kreditcb,
							'branch'		   => $prmp->branch,
							'type'	           => '2',
							'nominal'          => str_replace(',','.',str_replace('.','',$request->source_nominal)),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
				
				$totalall = str_replace(',','.',str_replace('.','',$request->source_nominal));
				
				foreach($request->purchase_detail as $key => $rowpurchase){
					$cek = PurchaseRequest::find($rowpurchase);
					
					$sisautang = $cek->total_nominal - $cek->totalPayment();
					
					$query = PurchaseRequestPayment::create([
						'user_id'	     					=> session('bo_id'),
						'purchase_request_id'				=> $rowpurchase,
						'purchase_request_main_payment_id'	=> $prmp->id,
						'date_paid'							=> $request->source_date,
						'branch'							=> $request->source_branch,
						'coa_id'							=> $request->source_coa,
						'nominal'							=> str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
						'image'								=> NULL,
						'code'								=> $request->source_code,
						'due_date'							=> $request->source_due_date,
						'note'								=> $request->source_note.' - '.$cek->title.' - '.$cek->item
					]);
					
					if($query) {
						
						$pr = PurchaseRequest::find($rowpurchase);
						
						if($pr->link_type == 'project_purchases'){
						
							$pp = ProjectPurchase::find($pr->link_id);
							
							$balance = str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])) - str_replace(',','.',str_replace('.','',$pp->getTotal())) - str_replace(',','.',str_replace('.','',$pp->getPaid()));
							 
							$ppp = ProjectPayment::create([
								'project_id' 				=> $pp->project_id > 0 ? $pp->project_id : NULL,
								'project_purchase_id'		=> $pr->link_id,
								'image'      				=> NULL,
								'date'       				=> $request->source_date,
								'nominal'    				=> str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
								'bank'       				=> $request->source_coa,
								'giro'						=> $request->source_due_date ? '1' : '0',
								'giro_code'					=> $request->source_code,
								'giro_date'					=> $request->source_due_date,
								'status'     				=> '2',
								'checked_by'     			=> session('bo_id')
							]);
						
							if(count($pp->projectWarehouse) == 0){
							
								$projectdetail = $ppp->projectPurchase->id;
								$reference = '2';
								$type = '2';
								$description = 'Purchase payment code '.$ppp->projectPurchase->code; 
								
								$debetcb = 24;
								
								if($cb){
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $debetcb,
										'branch'		=> $pp->sales->branch,
										'type'       	=> '1',
										'nominal'      	=> str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
										'note'         	=> $cek->title.' - '.$cek->item
									]);
									
									Journal::insert([
										'date_transaction' => $request->source_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $debetcb,
										'branch'		   => $pp->sales->branch,
										'type'	           => '1',
										'nominal'          => str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
								}
								
							}else{
								
								$totalPurchase = str_replace(',','.',str_replace('.','',$pp->getReceived()));
								$totalUnder = $totalPurchase - str_replace(',','.',str_replace('.','',$pp->getPaid()));
								$rest = $totalUnder - str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key]));
								
								$projectdetail = $pp->id;
								$reference = '2'; #is sale
								$type = '2'; #cash_bank
								$description = 'Purchase payment code '.$pp->code;
								
								if(count($pp->projectWarehouse) > 0){
									$debetcb = 332;
									
									if($cb){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $debetcb,
											'branch'		=> $ppp->projectPurchase->sales->branch,
											'type'       	=> '1',
											'nominal'      	=> $balance > 0 ? round($totalPurchase,0) : str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
											'note'         	=> $cek->title.' - '.$cek->item
										]);

										Journal::insert([
											'date_transaction' => $request->source_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $debetcb,
											'branch'		   => $ppp->projectPurchase->sales->branch,
											'type'	           => '1',
											'nominal'          => $balance > 0 ? round($totalPurchase,0) : str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}else{
									
									$debetcb = 332;
									
									if($cb){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $debetcb,
											'branch'		=> $ppp->projectPurchase->sales->branch,
											'type'       	=> '1',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
											'note'         	=> $cek->title.' - '.$cek->item
										]);
										
										Journal::insert([
											'date_transaction' => $request->source_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $debetcb,
											'branch'		   => $ppp->projectPurchase->sales->branch,
											'type'	           => '1',
											'nominal'          => round(str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),0),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}
							}
							
						}else{
							
							if(in_array($cek->status,array('APPR','RCVD','SUBM','PAID','DONE'))){
							
								$totalpr = $pr->total_nominal;
								$totalpay = $pr->totalPayment();
								
								if($pr->link_type == 'project_sales'){
								
									$debetcb = 281;
									
									if($cb){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $debetcb,
											'branch'		=> $pr->branch,
											'type'       	=> '1',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
											'note'         	=> $cek->title.' - '.$cek->item
										]);
										
										Journal::insert([
											'date_transaction' => $request->source_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $debetcb,
											'branch'		   => $pr->branch,
											'type'	           => '1',
											'nominal'          => str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
										
									}
								}else{
									$cekpr = CashBank::where('code','like','PR-'.$rowpurchase)->first();
								
									if($cekpr){
										
										$debetcb = $pr->coa_id ? $pr->coa_id : 332;
															
										if($cb){
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $cek->branch,
												'type'       	=> '1',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
												'note'         	=> $pr->title.' '.$pr->item.' '.$request->source_note
											]);
											
											Journal::insert([
												'date_transaction' => $request->source_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $debetcb,
												'branch'		   => $cek->branch,
												'type'	           => '1',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key])),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
											
										}
									}
								}
							}
						}
					}
					
					$totalall -= str_replace(',','.',str_replace('.','',$request->purchase_nominal_detail[$key]));
				}
				
				if($totalall > 0 && isset($cb)){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 48,
						'branch'		=> $prmp->branch,
						'type'       	=> '1',
						'nominal'      	=> $totalall,
						'note'         	=> $prmp->note
					]);

					Journal::insert([
						'date_transaction' => $request->source_date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 48,
						'branch'		   => $prmp->branch,
						'type'	           => '1',
						'nominal'          => $totalall,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
				
				if($totalall < 0 && isset($cb)){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 209,
						'branch'		=> $prmp->branch,
						'type'       	=> '2',
						'nominal'      	=> abs($totalall),
						'note'         	=> $prmp->note
					]);

					Journal::insert([
						'date_transaction' => $request->source_date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 209,
						'branch'		   => $prmp->branch,
						'type'	           => '2',
						'nominal'          => abs($totalall),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
				
				if($cb_ref > 0){
					BalanceHistory::create([
						'user_id' 					=> session('bo_id'),
						'nominal'					=> $prmp->nominal,
						'type'						=> 'OUT',
						'cash_or_bank'				=> in_array($prmp->coa_id,array(2,5,345)) ? 'CASH' : 'BANK',
						'coa_id'					=> $prmp->coa_id,
						'cash_bank_reference'		=> $cb_ref,
						'branch'					=> $prmp->branch,
						'note'						=> $prmp->note,
						'date'						=> $request->source_date,
						'image'						=> NULL
					]);
				}
				
				activity()
					->performedOn(new PurchaseRequestMainPayment())
					->causedBy(session('bo_id'))
					->withProperties($prmp)
					->log('Add purchase request main payment by user '.session('bo_name'));

				$response = [
					'status'  => 200,
					'message' => 'Data added successfully.'
				];
			}else{
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
		}
		
		return response()->json($response);
	}
	
	public function addPayment(Request $request)
    {
		$validation = Validator::make($request->all(), [
			'temppay'   		=> 'required',
			'pay_date' 			=> 'required',
			'pay_branch'   		=> 'required',
			'pay_coa' 			=> 'required',
			'pay_nominal' 		=> 'required',
			//'pay_file' 			=> 'required'
		], [
			'temppay.required'   		=> 'Purchase Request Information cannot be empty.',
			'pay_date.required'   		=> 'Date cannot be empty.',
			'pay_branch.required'   	=> 'Branch cannot be empty.',
			'pay_coa.required'    		=> 'Source cannot be empty.',
			'pay_nominal.required'		=> 'Nominal cannot be empty.',
			//'pay_file.required'		=> 'Proof payment cannot be empty.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if(CheckCutOff::check($request->pay_branch,substr($request->date_paid,0,7))){
			
			$cek = PurchaseRequest::find($request->temppay);
			
			/* if($cek->status == 'DONE'){
				$response = [
					'status'  => 500,
					'message' => 'You cannot add payment because this Purchase Request is closed/done.'
				];

				return response()->json($response);
			} */
			
			$sisautang = $cek->total_nominal - $cek->totalPayment();
			
				if((!$cek->approved_by && $cek->total_cash_advance == 0 && $cek->link_type != 'project_deliveries') || $cek->status == 'RJCT'){
					$response = [
						'status'  => 500,
						'message' => 'Ups! Hayo, the purchase request has not been approved yet or status is rejected!'
					];
				/* }elseif($cek->date > $request->pay_date){
					$response = [
						'status'  => 500,
						'message' => 'Ups! Hayo, the purchase request date is more than purchase payment date, please change it!'
					]; */
				}else{
					
					$query = PurchaseRequestPayment::create([
						'user_id'	     		=> session('bo_id'),
						'purchase_request_id'	=> $request->temppay,
						'date_paid'				=> $request->pay_date,
						'branch'				=> $request->pay_branch,
						'coa_id'				=> $request->pay_coa,
						'nominal'				=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
						'image'					=> $request->has('pay_file') ? $request->file('pay_file')->store('public/purchase') : null,
						'code'					=> $request->pay_code,
						'due_date'				=> $request->pay_due_date,
						'note'					=> $request->pay_note.' - '.$cek->title.' - '.$cek->item
					]);
					
					if($query) {
						
						$image = '';
						
						if($query->image){
							$image = explode('.',$query->image)[1] == 'pdf' ? '<a href="' .$query->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$query->item.'" data-group="a" href="' .$query->attachment() . '"><img src="' . $query->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
						}
						
						$result = [
							'id'		=> $query->id,
							'date'		=> $query->date_paid,
							'branch'	=> $query->branch(),
							'source'	=> $query->coa ? $query->coa->name : '',
							'nominal'	=> number_format($query->nominal,'2',',','.'),
							'proof'		=> $image,
							'code'		=> $query->code ? $query->code : '-',
							'due_date'	=> $query->due_date ? $query->due_date : '-',
							'note'		=> $query->note ? $query->note : '-'		
						];
						
						
						$pr = PurchaseRequest::find($request->temppay);
					
						$cb_ref = 0;
						
						if($request->purchase_id){
						
							$pp = ProjectPurchase::find($request->purchase_id);
							
							$balance = str_replace(',','.',str_replace('.','',$request->pay_nominal)) - round($pp->getTotalReceive()) - str_replace(',','.',str_replace('.','',$pp->getPaid()));
							 
							$ppp = ProjectPayment::create([
								'project_id' 				=> $pp->project_id > 0 ? $pp->project_id : NULL,
								'project_purchase_id'		=> $request->purchase_id,
								'image'      				=> $request->file('pay_file') ? $request->file('pay_file')->store('public/project') : '',
								'date'       				=> $request->pay_date,
								'nominal'    				=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
								'bank'       				=> $request->pay_coa,
								'giro'						=> $request->giro,
								'giro_code'					=> $request->pay_code,
								'giro_date'					=> $request->pay_due_date,
								'status'     				=> $request->type,
								'checked_by'     			=> session('bo_id')
							]);
						
							if(count($pp->projectWarehouse) == 0){
							
								$projectdetail = $ppp->projectPurchase->id;
								$reference = '2';
								$type = '2';
								$description = 'Purchase payment code '.$ppp->projectPurchase->code; 
								
								if($request->giro == '1'){
									$debetcb = 24;
									
									$kreditcb = 30;
									
									$cb = CashBank::create([
										'user_id'     		=> session('bo_id'),
										'lookable_type'     => 'project_payments',
										'lookable_id'		=> $ppp->id,
										'code'        		=> 'PRP-'.$query->id,
										'date'        		=> $request->pay_date,
										'type'        		=> $type,
										'description' 		=> $description
									]);
									
									if($cb){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $debetcb,
											'branch'		=> $ppp->projectPurchase->sales->branch,
											'type'       	=> '1',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$pp->getTotal())),
											'note'         	=> ''
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $debetcb,
											'branch'		   => $ppp->projectPurchase->sales->branch,
											'type'	           => '1',
											'nominal'          => str_replace(',','.',str_replace('.','',$pp->getTotal())),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
										
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $ppp->projectPurchase->sales->branch,
											'type'       	=> '2',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'note'         	=> ''
										]);

										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $ppp->projectPurchase->sales->branch,
											'type'	           => '2',
											'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
									
									$debetcb = 30;
									
									$kreditcb = $request->pay_coa;
									
									$cb = CashBank::create([
										'user_id'     		=> session('bo_id'),
										'lookable_type'     => 'project_payments',
										'lookable_id'		=> $ppp->id,
										'code'        		=> 'PRP-'.$query->id,
										'date'        		=> $request->pay_due_date,
										'type'        		=> $type,
										'description' 		=> $description
									]);
									
									$cb_ref = $cb->id;
									
									if($cb){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $debetcb,
											'branch'		=> $ppp->projectPurchase->sales->branch,
											'type'       	=> '1',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'note'         	=> ''
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_due_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $debetcb,
											'branch'		   => $ppp->projectPurchase->sales->branch,
											'type'	           => '1',
											'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
										
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $request->pay_branch,
											'type'       	=> '2',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'note'         	=> ''
										]);

										Journal::insert([
											'date_transaction' => $request->pay_due_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $request->pay_branch,
											'type'	           => '2',
											'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}else{
									$debetcb = 24;
									
									$kreditcb = $request->pay_coa;
									
									$cb = CashBank::create([
										'user_id'     		=> session('bo_id'),
										'lookable_type'     => 'project_payments',
										'lookable_id'		=> $pp->id,
										'code'        		=> 'PRP-'.$query->id,
										'date'        		=> $request->pay_date,
										'type'        		=> $type,
										'description' 		=> $description
									]);
									
									$cb_ref = $cb->id;
									
									if($cb){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $debetcb,
											'branch'		=> $pp->sales->branch,
											'type'       	=> '1',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'note'         	=> ''
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $debetcb,
											'branch'		   => $pp->sales->branch,
											'type'	           => '1',
											'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
										
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $request->pay_branch,
											'type'       	=> '2',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'note'         	=> ''
										]);

										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $request->pay_branch,
											'type'	           => '2',
											'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}
								
							}else{
								
								$totalPurchase = str_replace(',','.',str_replace('.','',$pp->getReceived()));
								$totalUnder = $totalPurchase - str_replace(',','.',str_replace('.','',$pp->getPaid()));
								$rest = $totalUnder - str_replace(',','.',str_replace('.','',$request->pay_nominal));
								
								$projectdetail = $pp->id;
								$reference = '2'; #is sale
								$type = '2'; #cash_bank
								$description = 'Purchase payment code '.$pp->code;
								
								if(count($pp->projectWarehouse) > 0){
											
									if($request->giro == '1'){
										
										if($rest < 0){
											$debetcb = 24;
										}else{
											$debetcb = 332;
										}
									
										$kreditcb = 30;
										
										$cb = CashBank::create([
											'user_id'     		=> session('bo_id'),
											'lookable_type'     => 'project_payments',
											'lookable_id'		=> $ppp->id,
											'code'        		=> 'PRP-'.$query->id,
											'date'        		=> $request->pay_date,
											'type'        		=> $type,
											'description' 		=> $description
										]);
										
										if($cb){
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $ppp->projectPurchase->sales->branch,
												'type'       	=> '1',
												'nominal'      	=> $balance > 0 ? round($totalPurchase,0) : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);
											
											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $debetcb,
												'branch'		   => $ppp->projectPurchase->sales->branch,
												'type'	           => '1',
												'nominal'          => $balance > 0 ? round($totalPurchase,0) : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
											
											if($balance > 0){
												CashBankDetail::create([
													'cash_bank_id' 	=> $cb->id,
													'coa_id'       	=> 328,
													'branch'		=> $ppp->projectPurchase->sales->branch,
													'type'       	=> '1',
													'nominal'      	=> $balance,
													'note'         	=> ''
												]);
												
												Journal::insert([
													'date_transaction' => $request->pay_date,
													'journalable_type' => 'cash_banks',
													'journalable_id'   => $cb->id,
													'coa_id'           => 328,
													'branch'		   => $ppp->projectPurchase->sales->branch,
													'type'	           => '1',
													'nominal'          => $balance,
													'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
													'updated_at'       => date('Y-m-d H:i:s')
												]);
											}
											
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $kreditcb,
												'branch'		=> $ppp->projectPurchase->sales->branch,
												'type'       	=> '2',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);

											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $kreditcb,
												'branch'		   => $ppp->projectPurchase->sales->branch,
												'type'	           => '2',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
										
										$debetcb = 30;
										
										$kreditcb = $request->pay_coa;
										
										$cb = CashBank::create([
											'user_id'     		=> session('bo_id'),
											'lookable_type'     => 'project_payments',
											'lookable_id'		=> $ppp->id,
											'code'        		=> 'PRP-'.$query->id,
											'date'        		=> $request->pay_due_date,
											'type'        		=> $type,
											'description' 		=> $description
										]);
										
										$cb_ref = $cb->id;
										
										if($cb){
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $ppp->projectPurchase->sales->branch,
												'type'       	=> '1',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);
											
											Journal::insert([
												'date_transaction' => $request->pay_due_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $debetcb,
												'branch'		   => $ppp->projectPurchase->sales->branch,
												'type'	           => '1',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
											
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $kreditcb,
												'branch'		=> $request->pay_branch,
												'type'       	=> '2',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);

											Journal::insert([
												'date_transaction' => $request->pay_due_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $kreditcb,
												'branch'		   => $request->pay_branch,
												'type'	           => '2',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
										
									}else{
										$kreditcb = $request->pay_coa;
										
										$debetcb = 332;
										
										$cb = CashBank::create([
											'user_id'     		=> session('bo_id'),
											'lookable_type'		=> 'project_payments',
											'lookable_id'		=> $ppp->id,
											'code'        		=> 'PRP-'.$query->id,
											'date'        		=> $request->pay_date,
											'type'        		=> $type,
											'description' 		=> $description
										]);
										
										$cb_ref = $cb->id;
										
										if($cb){
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $ppp->projectPurchase->sales->branch,
												'type'       	=> '1',
												'nominal'      	=> $balance > 0 ? round($totalPurchase,0) : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);

											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $debetcb,
												'branch'		   => $ppp->projectPurchase->sales->branch,
												'type'	           => '1',
												'nominal'          => $balance > 0 ? round($totalPurchase,0) : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
											
											#kredit
											
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $kreditcb,
												'branch'		=> $request->pay_branch,
												'type'       	=> '2',
												'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
												'note'         	=> ''
											]);
											
											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $kreditcb,
												'branch'		   => $request->pay_branch,
												'type'	           => '2',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
									}
								}else{
									if($request->giro == '1'){
										$debetcb = 24;
										
										$kreditcb = 30;
										
										$cb = CashBank::create([
											'user_id'     		=> session('bo_id'),
											'lookable_type'     => 'project_payments',
											'lookable_id'		=> $ppp->id,
											'code'        		=> 'PRP-'.$query->id,
											'date'        		=> $request->pay_date,
											'type'        		=> $type,
											'description' 		=> $description
										]);
										
										if($cb){
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $ppp->projectPurchase->sales->branch,
												'type'       	=> '1',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);
											
											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $debetcb,
												'branch'		   => $ppp->projectPurchase->sales->branch,
												'type'	           => '1',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
											
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $kreditcb,
												'branch'		=> $ppp->projectPurchase->sales->branch,
												'type'       	=> '2',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);

											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $kreditcb,
												'branch'		   => $ppp->projectPurchase->sales->branch,
												'type'	           => '2',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
										
										$debetcb = 30;
										
										$kreditcb = $request->pay_coa;
										
										$cb = CashBank::create([
											'user_id'     		=> session('bo_id'),
											'lookable_type'     => 'project_payments',
											'lookable_id'		=> $ppp->id,
											'code'        		=> 'PRP-'.$query->id,
											'date'        		=> $request->pay_due_date,
											'type'        		=> $type,
											'description' 		=> $description
										]);
										
										$cb_ref = $cb->id;
										
										if($cb){
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $ppp->projectPurchase->sales->branch,
												'type'       	=> '1',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);
											
											Journal::insert([
												'date_transaction' => $request->pay_due_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $debetcb,
												'branch'		   => $ppp->projectPurchase->sales->branch,
												'type'	           => '1',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
											
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $kreditcb,
												'branch'		=> $request->pay_branch,
												'type'       	=> '2',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);

											Journal::insert([
												'date_transaction' => $request->pay_due_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $kreditcb,
												'branch'		   => $request->pay_branch,
												'type'	           => '2',
												'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
									}else{
										$debetcb = 332;
										
										$kreditcb = $request->pay_coa;
										
										$cb = CashBank::create([
											'user_id'     		=> session('bo_id'),
											'lookable_type'     => 'project_payments',
											'lookable_id'		=> $ppp->id,
											'code'        		=> 'PRP-'.$query->id,
											'date'        		=> $request->pay_date,
											'type'        		=> $type,
											'description' 		=> $description
										]);
										
										$cb_ref = $cb->id;
										
										if($cb){
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $debetcb,
												'branch'		=> $ppp->projectPurchase->sales->branch,
												'type'       	=> '1',
												'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
												'note'         	=> ''
											]);
											
											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $debetcb,
												'branch'		   => $ppp->projectPurchase->sales->branch,
												'type'	           => '1',
												'nominal'          => round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
											
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $kreditcb,
												'branch'		=> $request->pay_branch,
												'type'       	=> '2',
												'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
												'note'         	=> ''
											]);

											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $kreditcb,
												'branch'		   => $request->pay_branch,
												'type'	           => '2',
												'nominal'          => round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
									}
								}
							}
							
							if($balance > 0){
								CashBankDetail::create([
									'cash_bank_id' 	=> $cb->id,
									'coa_id'       	=> 48,
									'branch'		=> $ppp->projectPurchase->sales->branch,
									'type'       	=> '1',
									'nominal'      	=> $balance,
									'note'         	=> 'Project payment purchase order no . '.$pp->code
								]);
								
								Journal::insert([
									'date_transaction' => $request->pay_date,
									'journalable_type' => 'cash_banks',
									'journalable_id'   => $cb->id,
									'coa_id'           => 48,
									'branch'		   => $ppp->projectPurchase->sales->branch,
									'type'	           => '1',
									'nominal'          => $balance,
									'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
									'updated_at'       => date('Y-m-d H:i:s')
								]);
							}
						}else{
							
							if(in_array($cek->status,array('APPR','RCVD','SUBM','DONE'))){
							
								$totalpr = $pr->total_nominal;
								$totalpay = $pr->totalPayment();
								
								if($pr->link_type == 'project_sales'){
								
									$debetcb = 281;
												
									$kreditcb = $request->pay_coa;
									
									$cb = CashBank::create([
										'user_id'     		=> session('bo_id'),
										'lookable_type'     => 'purchase_request_payments',
										'lookable_id'		=> $query->id,
										'code'        		=> 'PRP-'.$query->id,
										'date'        		=> $request->pay_date,
										'type'        		=> '3',
										'description' 		=> $pr->title.' '.$pr->item.' '.$request->pay_note
									]);
									
									$cb_ref = $cb->id;
									
									if($cb){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $debetcb,
											'branch'		=> $query->branch,
											'type'       	=> '1',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'note'         	=> ''
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $debetcb,
											'branch'		   => $query->branch,
											'type'	           => '1',
											'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
										
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $request->pay_branch,
											'type'       	=> '2',
											'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
											'note'         	=> ''
										]);

										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $request->pay_branch,
											'type'	           => '2',
											'nominal'          => round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}elseif($pr->link_type == 'customer_deposit'){
									$debetcb = $pr->coa_id;
												
									$kreditcb = $request->pay_coa;
									
									$cb = CashBank::create([
										'user_id'     		=> session('bo_id'),
										'lookable_type'     => 'purchase_request_payments',
										'lookable_id'		=> $query->id,
										'customer_id'		=> $pr->link_id,
										'code'        		=> 'PRP-'.$query->id,
										'date'        		=> $request->pay_date,
										'type'        		=> '3',
										'description' 		=> $pr->title.' '.$pr->item.' '.$request->pay_note
									]);
									
									$cb_ref = $cb->id;
									
									if($cb){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $debetcb,
											'branch'		=> $query->branch,
											'type'       	=> '1',
											'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'note'         	=> ''
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $debetcb,
											'branch'		   => $query->branch,
											'type'	           => '1',
											'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
										
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $request->pay_branch,
											'type'       	=> '2',
											'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
											'note'         	=> ''
										]);

										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $request->pay_branch,
											'type'	           => '2',
											'nominal'          => round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}else{
									$cekpr = CashBank::where('code','like','PR-'.$request->temppay)->first();
								
									if($cekpr){
										$cb = CashBank::create([
											'user_id'     			=> session('bo_id'),
											'lookable_id'			=> $query->id,
											'lookable_type'			=> 'purchase_request_payments',
											'supplier_id'			=> $pr->supplier_id,
											'request_date'			=> $pr->date,
											'due_date'				=> $pr->due_date,
											'code'        			=> 'PRP-'.$query->id,
											'date'        			=> $request->pay_date,
											'type'        			=> '2',
											'image'					=> $request->has('pay_file') ? $request->file('pay_file')->store('public/cashbank') : '',
											'description' 			=> $pr->item.' '.$pr->title.' '.$request->pay_note
										]);
										
										$debetcb = $pr->coa_id ? $pr->coa_id : 332;
															
										$kreditcb = $query->coa_id;
										
										$cb_ref = $cb->id;

										$remaining_payment = str_replace(',','.',str_replace('.','',$request->pay_nominal));

										if($cb){
											// Untuk payable beda branch cth payable PTA & SMB
											if(count($cekpr->cashBankDetail()->where('type','2')->get()) > 1){
												foreach ($cekpr->cashBankDetail()->where('type','2')->groupBy('branch')->get() as $key => $rowdb) {
													if($remaining_payment > 0  && !$this->checkPr($rowdb->coa_id, $rowdb->nominal,$rowdb->branch, $request->temppay)['isValid']){

														$remaining_bill = $this->checkPr($rowdb->coa_id, $rowdb->nominal,$rowdb->branch, $request->temppay)['remainingBill'];

														CashBankDetail::create([
															'cash_bank_id' 	=> $cb->id,
															'coa_id'       	=> $rowdb->coa_id,
															'branch'		=> $rowdb->branch,
															'type'       	=> '1',
															// jika pembayaran lebih besar daripada bill/ tagihan maka tagihan yang menjadi nilai utama, karena nanti kelebihan bayar akan dikurangi lagi dengan bill untuk branch/ coa lain
															'nominal'      	=> $remaining_payment - $remaining_bill > 0 ? $remaining_bill : $remaining_payment,
															'note'         	=> $pr->title.' '.$pr->item.' '.$request->pay_note
														]);
														
														Journal::insert([
															'date_transaction' => $request->pay_date,
															'journalable_type' => 'cash_banks',
															'journalable_id'   => $cb->id,
															'coa_id'           => $rowdb->coa_id,
															'branch'		   => $rowdb->branch,
															'type'	           => '1',
															'nominal'          => $remaining_payment - $remaining_bill > 0 ? $remaining_bill : $remaining_payment,
															'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
															'updated_at'       => date('Y-m-d H:i:s')
														]);

														$remaining_payment -= $remaining_bill;
													}
												}
											}else{
												CashBankDetail::create([
													'cash_bank_id' 	=> $cb->id,
													'coa_id'       	=> $debetcb,
													'branch'		=> $cek->branch,
													'type'       	=> '1',
													'nominal'      	=> str_replace(',','.',str_replace('.','',$request->pay_nominal)) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
													'note'         	=> $pr->title.' '.$pr->item.' '.$request->pay_note
												]);
												
												Journal::insert([
													'date_transaction' => $request->pay_date,
													'journalable_type' => 'cash_banks',
													'journalable_id'   => $cb->id,
													'coa_id'           => $debetcb,
													'branch'		   => $cek->branch,
													'type'	           => '1',
													'nominal'          => str_replace(',','.',str_replace('.','',$request->pay_nominal)) - $sisautang > 0 ? $sisautang : str_replace(',','.',str_replace('.','',$request->pay_nominal)),
													'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
													'updated_at'       => date('Y-m-d H:i:s')
												]);
											}
											
											
											CashBankDetail::create([
												'cash_bank_id' 	=> $cb->id,
												'coa_id'       	=> $kreditcb,
												'branch'		=> $query->branch,
												'type'       	=> '2',
												'nominal'      	=> $query->nominal,
												'note'         	=> $pr->title.' '.$pr->item.' '.$request->pay_note
											]);

											Journal::insert([
												'date_transaction' => $request->pay_date,
												'journalable_type' => 'cash_banks',
												'journalable_id'   => $cb->id,
												'coa_id'           => $kreditcb,
												'branch'		   => $query->branch,
												'type'	           => '2',
												'nominal'          => $query->nominal,
												'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
												'updated_at'       => date('Y-m-d H:i:s')
											]);
										}
									}
								}
								
								if(isset($cb) && $totalpay - $totalpr > 0){
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> 48,
										'branch'		=> $query->branch,
										'type'       	=> '1',
										'nominal'      	=> $totalpay - $totalpr,
										'note'         	=> $pr->title.' '.$pr->item.' '.$request->pay_note
									]);

									Journal::insert([
										'date_transaction' => $request->pay_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => 48,
										'branch'		   => $query->branch,
										'type'	           => '1',
										'nominal'          => $totalpay - $totalpr,
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
							}
						}
						
						if($cb_ref > 0){
							BalanceHistory::create([
								'user_id' 					=> session('bo_id'),
								'nominal'					=> $query->nominal,
								'type'						=> 'OUT',
								'cash_or_bank'				=> in_array($query->coa_id,array(2,5,345)) ? 'CASH' : 'BANK',
								'coa_id'					=> $query->coa_id,
								'cash_bank_reference'		=> $cb_ref,
								'branch'					=> $query->branch,
								'note'						=> $pr->title.' - '.$pr->item.' - '.$request->pay_note,
								'date'						=> $request->pay_date,
								'image'						=> $request->has('pay_file') ? $request->file('pay_file')->store('public/balance') : ''
							]);
						}
						
						$countpay = PurchaseRequestPayment::where('purchase_request_id',$query->purchase_request_id)->count();
						
						activity()
							->performedOn(new PurchaseRequestPayment())
							->causedBy(session('bo_id'))
							->withProperties($query)
							->log('Add purchase request payment by user '.session('bo_name'));

						$response = [
							'status'  => 200,
							'message' => 'Data added successfully.',
							'result'  => $result,
							'count'	  => $countpay
						];
					} else {
						$response = [
							'status'  => 500,
							'message' => 'Data failed to add.'
						];
					}
				}
			}else{
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
        }

        return response()->json($response);
    }

	function checkPr($coa_id, $nominal, $branch, $id){
		$isValid = false;

		$nominal_real = 0;
		$nominal_payment = 0;

		$pr = PurchaseRequest::find($id);
		$cekpr = CashBank::where('code','like','PR-'.$id)->first();

		
		foreach ($cekpr->cashBankDetail()->where('type','2')->where('branch', $branch)->get() as $val_pr) {
			$nominal_real += $val_pr->nominal;
		}


		foreach ($pr->purchaserequestPayment as $val) {

			$cb = CashBank::where('code','PRP-'.$val->id)->first();
			
			$cbd = $cb->cashBankDetail()->where('coa_id', $coa_id)->where('branch', $branch)->where('nominal', 'like',  "%$nominal%")->first();


			foreach ($cb->cashBankDetail()->where('coa_id', $coa_id)->where('branch', $branch)->get() as $val_cbd) {
				$nominal_payment += $val_cbd->nominal;
			}
				
		}

		$remaining_bill = round($nominal_real, 0) - round($nominal_payment, 0);

		if($cbd || round($nominal_real,0) == round($nominal_payment,0)){
			$isValid = true;
		}

		$data = [
			'isValid' 		=> $isValid,
			'remainingBill' => $remaining_bill
		];


		return $data;

	}

	function checkPaymentPr($id){
		// $nominal = 0;

		// $pr = PurchaseRequest::find($id);

		// foreach ($pr->purchaserequestPayment as $val) {

		// 	$cb = CashBank::where('code','PRP-'.$val->id)->first();
			
		// 	foreach ($cb->cashBankDetail()->where('type', 1)->get() as $val) {
		// 		$nominal = $val->nominal;
		// 	}
				
		// }

		// return $nominal;

	}
	
	public function deletePayment(Request $request)
    {
		// $cek = CashBank::where('lookable_id',$request->id)->where('lookable_type','purchase_request_payments')->get();
		
		// if(count($cek) > 0){
			// $response = [
				// 'status'  => 500,
				// 'message' => 'Data already in Cash & Bank. Please contact Accounting.'
			// ];
		// }else{
			$query = PurchaseRequestPayment::find($request->id);
			$purchase_request = PurchaseRequest::find($query->purchase_request_id);
			
			if(CheckCutOff::check($query->branch,substr($query->date_paid,0,7)) || $purchase_request->status == 'DONE'){
			
				if($query->image){
					$query->deleteFile();
				}
				$query->delete();
				
				$cb = CashBank::where('lookable_id',$request->id)->where('lookable_type','purchase_request_payments')->get();
				$cb2 = CashBank::where('code','PRP-'.$query->id)->get();
				
				foreach($cb as $row){
					$row->deleteDetail();
					BalanceHistory::where('cash_bank_reference',$row->id)->delete();
					$row->delete();
				}
				
				foreach($cb2 as $row){
					if($row->lookable_type == 'project_payments'){
						$projectpayment = ProjectPayment::find($row->lookable_id);
						if($projectpayment){
							$projectpayment->deleteFile();
							$projectpayment->delete();
						}
					}
					$row->deleteDetail();
					BalanceHistory::where('cash_bank_reference',$row->id)->delete();
					$row->delete();
				}
				
				$countpay = PurchaseRequestPayment::where('purchase_request_id',$query->purchase_request_id)->count();
				
				if($query) {
					activity()
						->performedOn(new PurchaseRequestPayment())
						->causedBy(session('bo_id'))
						->log('Delete the payment purchase request data');

					$response = [
						'status'  => 200,
						'count'	  => $countpay,
						'message' => 'Data deleted successfully.'
					];
				} else {
					$response = [
						'status'  => 500,
						'message' => 'Data failed to delete.'
					];
				}
			}else{
				$response = [
					'status'  => 503,
					'message' => 'You cannot add/edit. The journal for this month was already closed.'
				];
			}
		// }

        return response()->json($response);
    }
	
	public function approve(Request $request)
    {
		
		/* $query = PurchaseRequest::find($request->id);
		if($query->status == 'DONE'){
			$query->update([
				'approved_by' => session('bo_id')
			]);
		}else{
			
			$debetcb = 48;
			$kreditcb = 332;
			$cb = CashBank::create([
				'user_id'     			=> $query->user_id,
				'lookable_id'			=> $query->id,
				'lookable_type'			=> 'purchase_requests',
				'supplier_id'			=> $query->supplier_id,
				'request_date'			=> $query->date,
				'due_date'				=> $query->due_date,
				'code'        			=> 'PR-'.$query->id,
				'date'        			=> $query->date,
				'type'        			=> '3',
				'description' 			=> $query->item
			]);
			if($cb){
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $debetcb,
					'branch'		=> $query->branch,
					'type'       	=> '1',
					'nominal'      	=> str_replace(',','.',str_replace('.','',$query->total_nominal)),
					'note'         	=> $query->item
				]);
				
				Journal::insert([
					'date_transaction' => $query->date,
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $debetcb,
					'branch'		   => $query->branch,
					'type'	           => '1',
					'nominal'          => str_replace(',','.',str_replace('.','',$query->total_nominal)),
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $kreditcb,
					'branch'		=> $query->branch,
					'type'       	=> '2',
					'nominal'      	=> str_replace(',','.',str_replace('.','',$query->total_nominal)),
					'note'         	=> $request->item
				]);

				Journal::insert([
					'date_transaction' => $query->date,
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $kreditcb,
					'branch'		   => $query->branch,
					'type'	           => '2',
					'nominal'          => str_replace(',','.',str_replace('.','',$query->total_nominal)),
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
			}
			
			
			$query->update([
				'approved_by' => session('bo_id'),
				'status' => 'APPR'
			]);
		}
		
		Approval::where('approvalable_type','purchase_requests')->where('approvalable_id',$request->id)->update([
			'approved_by' => session('bo_id'),
			'seen' => 1
		]);
		
		if($query) {
			activity()
				->performedOn(new PurchaseRequest())
				->withProperties($query)
				->causedBy(session('bo_id'))
				->log('Update purchase request approval : approved');

			$response = [
				'status'  => 200,
				'message' => 'Data deleted successfully.'
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Data failed to delete.'
			];
		}
		
		return response()->json($response);*/
	}
	
	public function reject(Request $request)
    {
		
		$query = PurchaseRequest::find($request->id);
		
		if(CheckCutOff::check($query->branch,substr($query->date,0,7))){
			$query->update([
				'approved_by' => session('bo_id'),
				'status' => 'RJCT'
			]);
			
			Approval::where('approvalable_type','purchase_requests')->where('approvalable_id',$request->id)->update([
				'approved_by' => session('bo_id'),
				'seen' => 1,
				'status' => '2'
			]);
			
			if($query) {
				activity()
					->performedOn(new PurchaseRequest())
					->withProperties($query)
					->causedBy(session('bo_id'))
					->log('Update purchase request approval : rejected');

				$response = [
					'status'  => 200,
					'message' => 'Data deleted successfully.'
				];
			} else {
				$response = [
					'status'  => 500,
					'message' => 'Data failed to delete.'
				];
			}
		}else{
			$response = [
				'status'  => 503,
				'message' => 'You cannot add/edit. The journal for this month was already closed.'
			];
		}
		
		return response()->json($response);
	}
	
	public function showInformation(Request $request)
    {
		$type = $request->type;
		
		$content = '<div class="table-responsive">
						<table class="table table-bordered table-striped w-100 table-hover">
							<thead class="bg-dark">
								<tr class="text-center">
									<th>No</th>
									<th>Bill To</th>
									<th>Note/Item</th>
									<th>Nominal</th>
								</tr>
							</thead>
							<tbody>';
		
		if($type == 'paid'){
			$data = PurchaseRequest::where('user_id',session('bo_id'))->get();
			$no = 1;
			$total = 0;
			foreach($data as $row){
				if($row->status == 'DONE' || $row->closed_by_acc){
					$total += $row->total_nominal;
					$content .= 	'<tr>
									<td class="text-center">'.$no.'</td>
									<td class="text-center">'.$row->bill_to.'</td>
									<td class="text-center">'.$row->item.'</td>
									<td class="text-right">'.number_format($row->total_nominal,0,',','.').'</td>
								</tr>';
								
					$no++;
				}
			}
		}elseif($type == 'unpaid'){
			$data = PurchaseRequest::where('user_id',session('bo_id'))->get();
			$no = 1;
			$total = 0;
			foreach($data as $row){
				if($row->status !== 'DONE' && $row->status !== 'RJCT'){
					$total += $row->total_nominal;
					$content .= 	'<tr>
									<td class="text-center">'.$no.'</td>
									<td class="text-center">'.$row->bill_to.'</td>
									<td class="text-center">'.$row->item.'</td>
									<td class="text-right">'.number_format($row->total_nominal,0,',','.').'</td>
								</tr>';
								
					$no++;
				}
			}
		}elseif($type == 'request'){
			$data = PurchaseRequest::where('user_id',session('bo_id'))->get();
			$no = 1;
			$total = 0;
			foreach($data as $row){
				if($row->status !== 'RJCT'){
					$total += $row->total_nominal;
					$content .= 	'<tr>
									<td class="text-center">'.$no.'</td>
									<td class="text-center">'.$row->bill_to.'</td>
									<td class="text-center">'.$row->item.'</td>
									<td class="text-right">'.number_format($row->total_nominal,0,',','.').'</td>
								</tr>';
								
					$no++;
				}
			}
		}
		
		$content .= '</tbody>
					<tfoot>
					<tr>
						<th class="text-right" colspan="3">Total</th>
						<th class="text-right">'.number_format($total,2,',','.').'</th>
					</tr>
				  </tfoot>
				  </table>
				</div>';
		
		return response()->json([
			'status'	=> 200,
			'content'	=> $content
		]);
	}
	
	public function getJournal(Request $request){
		
		$data = CashBankDetail::whereHas('cashBank', function($query) use ($request) {
			$query->where('lookable_id',$request->id)
			->where('lookable_type','purchase_requests');
		})
		->where('coa_id',332)
		->get();
		
		$arr = [];
		
		foreach($data as $row){
			$arr[] = [
				'coa'		=> $row->coa->name,
				'branch'	=> $row->branch(),
				'type'		=> $row->type == '1' ? 'Debit' : 'Credit',
				'nominal'	=> number_format($row->nominal,0,',','.')
			];
 		}
		
		return response()->json($arr);
	}
	
	public function getTotal(Request $request)
	{
		$id = $request->id;
		
		$pr = PurchaseRequest::find($id);
		
		$total = $pr->totalBalance();
		
		return response()->json([
			'total'	=> number_format($total,2,',','.')
		]);
	}
	
	public function repairPurchaseRequest(){
		$data = PurchaseRequest::where('status','DONE')->where('link_type','<>','project_purchases')->get();
		
		foreach($data as $row){
			$cek = null;
			$cek = CashBank::where('code','PR-'.$row->id)->get();
			if(!$cek){
				foreach($cek as $rowdetail){
					if($rowdetail->link_type == "project_sales"){
						$kreditcb = 281;
					}else{
						$kreditcb = 332;
					}
					
					$debetcb = 48;
					
					$cb = null;
					
					$cb = CashBank::create([
						'user_id'     			=> $rowdetail->user_id,
						'lookable_id'			=> $rowdetail->id,
						'lookable_type'			=> 'purchase_requests',
						'supplier_id'			=> $rowdetail->supplier_id,
						'request_date'			=> $rowdetail->date,
						'due_date'				=> $rowdetail->due_date,
						'code'        			=> 'PR-'.$rowdetail->id,
						'date'        			=> $rowdetail->date,
						'type'        			=> '3',
						'description' 			=> $rowdetail->title.' - '.$rowdetail->item
					]);
					
					if($cb){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $debetcb,
							'branch'		=> $rowdetail->branch,
							'type'       	=> '1',
							'nominal'      	=> $rowdetail->total_nominal,
							'note'         	=> $rowdetail->title.' - '.$rowdetail->item
						]);
						
						Journal::insert([
							'date_transaction' => $rowdetail->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $debetcb,
							'branch'		   => $rowdetail->branch,
							'type'	           => '1',
							'nominal'          => $rowdetail->total_nominal,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $kreditcb,
							'branch'		=> $rowdetail->branch,
							'type'       	=> '2',
							'nominal'      	=> $rowdetail->total_nominal,
							'note'         	=> $rowdetail->title.' - '.$rowdetail->item
						]);

						Journal::insert([
							'date_transaction' => $rowdetail->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => $kreditcb,
							'branch'		   => $rowdetail->branch,
							'type'	           => '2',
							'nominal'          => $rowdetail->total_nominal,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
			}
		}
	}
	
	public function print(Request $request){
		
		$start = $request->filter_start_date;
		$finish = $request->filter_finish_date;
		$arrId = explode(',',$request->filter_temp);
		
		$result = PurchaseRequest::whereIn('id',$arrId)->orderBy('date')->get();

       $pdf = PDF::loadView('admin.pdf.report.finance.purchase_request', [
				'start_date'	=> $start,
				'finish_date'	=> $finish,
				'data'			=> $result,
				'title'			=> 'TJS Report Purchase Request',
			],
			[],
			[ 
			  'format' => 'A4-P',
			  'orientation' => 'P'
			]
		);
		
		return $pdf->stream('TJS Report Balance Cash & Bank.pdf');
	}
	
	public function updatePayment(Request $request)
    {
		
		$query = PurchaseRequest::find($request->id);
		
		if($query->totalPayment() > 0){
			if(count($query->purchaseRequestPayment) == 1){
				foreach($query->purchaseRequestPayment as $row){
					$row->update([
						'nominal' => $query->total_nominal
					]);
				}
			}else{
				return response()->json([
					'status'  => 500,
					'message' => 'This purchase request had more than 1 payment.'
				]);
			}
		}else{
			return response()->json([
				'status'  => 500,
				'message' => 'This purchase request did not have payment.'
			]);
		}
		
		if($query) {
			activity()
				->performedOn(new PurchaseRequest())
				->withProperties($query)
				->causedBy(session('bo_id'))
				->log('Update purchase request change payment');

			$response = [
				'status'  => 200,
				'message' => 'Data updated successfully.'
			];
		}
		
		return response()->json($response);
	}
	
	public function showJournal(Request $request){
		$id = $request->id;
		
		$data = PurchaseRequest::find($id);
		
		$html = '<div class="table-responsive mt-5">
					<table class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>Journal/Cash&Bank Code</th>
							<th>Description</th>
							<th width="20%">Coa</th>
							<th width="10%">Debit</th>
							<th width="10%">Credit</th>
						 </tr>
					  </thead><tbody>';
					
		
		if($data){
			if($data->link_type == 'project_purchases'){
				$cbmain = CashBank::where('lookable_id', $data->project_warehouse_id)->where('lookable_type', 'project_warehouses')->first();
				$totaldebit = 0;
				$totalcredit = 0;
				if($cbmain){
					$rowspan = count($cbmain->cashBankDetail);
					$html .= '<tr>';
					$html .= '<td class="text-center" rowspan="'.$rowspan.'">'.$cbmain->code.'</td>';
					$html .= '<td class="text-center" rowspan="'.$rowspan.'">'.$cbmain->description.' (Journal Warehouse Receive)</td>';
					foreach($cbmain->cashBankDetail()->orderBy('id')->get() as $row){	
						$html .= '<td>'.$row->coa->name.'</td>';
						if($row->type == '1'){
							$html .= '<td class="text-right">'.number_format($row->nominal,2,',','.').'</td><td></td>';
							$totaldebit += $row->nominal;
						}elseif($row->type == '2'){
							$html .= '<td></td><td class="text-right">'.number_format($row->nominal,2,',','.').'</td>';
							$totalcredit += $row->nominal;
						}
						$html .= '</tr>';
					}
				}
	
				if($data->purchaseRequestPayment()->exists()){
					foreach($data->purchaseRequestPayment as $prp){
						$cb = CashBank::where('lookable_type','project_payments')->where('code','PRP-'.$prp->id)->first();
						if($cb){
							$rowspan = count($cb->cashBankDetail);
							$html .= '<tr>';
							$html .= '<td class="text-center" rowspan="'.$rowspan.'">'.$cb->code.'</td>';
							$html .= '<td class="text-center" rowspan="'.$rowspan.'">'.$cb->description.'</td>';
							foreach($cb->cashBankDetail()->orderBy('id')->get() as $row){
								$html .= '<td>'.$row->coa->name.'</td>';
								if($row->type == '1'){
									$html .= '<td class="text-right">'.number_format($row->nominal,2,',','.').'</td><td></td>';
									$totaldebit += $row->nominal;
								}elseif($row->type == '2'){
									$html .= '<td></td><td class="text-right">'.number_format($row->nominal,2,',','.').'</td>';
									$totalcredit += $row->nominal;
								}
								$html .= '</tr>';
							}
						}
					}
					
					$html .= '
						<tr class="font-weight-bold font-size-lg">
							<td colspan="3" class="text-right">Grandtotal</td>
							<td class="text-right">'.number_format($totaldebit,2,',','.').'</td>
							<td class="text-right">'.number_format($totalcredit,2,',','.').'</td>
						</tr>
					';
				}else{
					$html .= '<tr class="text-center"><td colspan="5"><h3>Payment not found.</h3></td></tr>';
				}
			}else{
				$cbmain = CashBank::where('code','PR-'.$data->id)->first();
				$totaldebit = 0;
				$totalcredit = 0;
				if($cbmain){
					$rowspan = count($cbmain->cashBankDetail);
					$html .= '<tr>';
					$html .= '<td class="text-center" rowspan="'.$rowspan.'">'.$cbmain->code.'</td>';
					$html .= '<td class="text-center" rowspan="'.$rowspan.'">'.$cbmain->description.'</td>';
					foreach($cbmain->cashBankDetail()->orderBy('id')->get() as $row){	
						$html .= '<td>'.$row->coa->name.'</td>';
						if($row->type == '1'){
							$html .= '<td class="text-right">'.number_format($row->nominal,2,',','.').'</td><td></td>';
							$totaldebit += $row->nominal;
						}elseif($row->type == '2'){
							$html .= '<td></td><td class="text-right">'.number_format($row->nominal,2,',','.').'</td>';
							$totalcredit += $row->nominal;
						}
						$html .= '</tr>';
					}
				}
				if($data->purchaseRequestPayment()->exists()){
					foreach($data->purchaseRequestPayment as $prp){
						$cb = CashBank::where('lookable_type','purchase_request_payments')->where('lookable_id',$prp->id)->first();
						if($cb){
							$rowspan = count($cb->cashBankDetail);
							$html .= '<tr>';
							$html .= '<td class="text-center" rowspan="'.$rowspan.'">'.$cb->code.'</td>';
							$html .= '<td class="text-center" rowspan="'.$rowspan.'">'.$cb->description.'</td>';
							foreach($cb->cashBankDetail()->orderBy('id')->get() as $row){
								$html .= '<td>'.$row->coa->name.'</td>';
								if($row->type == '1'){
									$html .= '<td class="text-right">'.number_format($row->nominal,2,',','.').'</td><td></td>';
									$totaldebit += $row->nominal;
								}elseif($row->type == '2'){
									$html .= '<td></td><td class="text-right">'.number_format($row->nominal,2,',','.').'</td>';
									$totalcredit += $row->nominal;
								}
								$html .= '</tr>';
							}
						}
					}
					
					$html .= '
						<tr class="font-weight-bold font-size-lg">
							<td colspan="3" class="text-right">Grandtotal</td>
							<td class="text-right">'.number_format($totaldebit,2,',','.').'</td>
							<td class="text-right">'.number_format($totalcredit,2,',','.').'</td>
						</tr>
					';
				}else{
					$html .= '<tr class="text-center"><td colspan="5"><h3>Payment not found.</h3></td></tr>';
				}
			}
		}
		
		$html .= '</tbody></table>
				</div>';
				
		return response()->json([
			'content'	=> $html
		]);
	}
	
	public function showPayableReport(Request $request){
		
		$branch = $request->branch;
		
		$coa = Coa::where(function($query){
			$query->where('parent_id',77)->orWhere('parent_id',81);
		})->where('id','<>',80)->orderBy('code')->get();
		
		$html = '<div class="table-responsive mt-5">
					<table class="table table-bordered table-striped">
					  <thead class="bg-dark">
						 <tr class="text-center">
							<th>Coa</th>
							<th>Nominal</th>
						 </tr>
					  </thead><tbody>';
					  
		$totalbalance = 0;
	
		foreach($coa as $row){
			$total = $row->nominalPurchaseRequest($branch);
			$html .= '<tr>';
			$html .= '<td class="text-center">'.$row->name.'</td>';
			$html .= '<td class="text-right">'.number_format($total,2,',','.').'</td>';
			$html .= '</tr>';
			
			$totalbalance += $total;
		}
		
		$html .= '
					<tr>
						<td class="text-right"><h3>TOTAL</h3></td>
						<td class="text-right"><h3>'.number_format($totalbalance,2,',','.').'</h3></td>
					</tr>
				</tbody></table>
				</div>';
				
		return response()->json([
			'content'	=> $html
		]);
	}
	
	public function getBalanceCashBank(Request $request){
		
		$branch = $request->branch;
		$coa_id = $request->coa_id;
		
		$totalIn = BalanceHistory::where('type','IN')->where('coa_id',$coa_id)->where('branch',$branch)->sum('nominal');
		$totalOut = BalanceHistory::where('type','OUT')->where('coa_id',$coa_id)->where('branch',$branch)->sum('nominal');
				
		return response()->json([
			'nominal'	=> number_format($totalIn - $totalOut,2,',','.'),
			'coa_id'    => $coa_id
		]);
	}
}
