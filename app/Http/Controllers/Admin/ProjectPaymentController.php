<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coa;
use App\Models\Project;
use App\Models\BalanceHistory;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Journal;
use App\Models\ProjectSale;
use App\Models\ProjectBill;
use App\Models\ProjectPay;
use App\Models\ProjectDelivery;
use App\Models\ProjectMainPayment;
use App\Models\Notification;
use App\Models\Approval;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helper\SendMessage;

class ProjectPaymentController extends Controller {
    
    public function index(Request $request)
    {
		$customerdeposit = [];
		$date = date('Y-m-d');
		$filter = $request->filter ? $request->filter : date('Y-m');

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

        $data = [
            'title'   			 => 'Project Payment',
            'filter'   			 => $filter,
            'customerdeposit'    => $customerdeposit,
			'coa'     			 => Coa::where('status', 1)->oldest('code')->get(),
            'content'			 => 'admin.delivery_order.project_payment'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
			'detail',
            'id',
            'user_id',
            'code',
			'date',
			'coa_id',
            'customer_id',
            'nominal',
            'note',
            'image',
            'action'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');


        $total_data = ProjectMainPayment::where(function ($query) use ($request){
			if($request->filter){
				$query->whereRaw('SUBSTR(date, 1, 7) = "' . $request->filter . '"')
					->orderBy('DATE', 'ASC');
			}
		})->count();
        
        $query_data = ProjectMainPayment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
                            ->orWhere('code', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('customer', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('coa', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }

				if($request->filter){
					$query->whereRaw('SUBSTR(date, 1, 7) = "' . $request->filter . '"')
          	  		->orderBy('DATE', 'ASC');
				}
            })
			
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectMainPayment::where(function($query) use ($search, $request) {
                if($search) {
                   $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
                            ->orWhere('code', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('customer', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('coa', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }
				if($request->filter){
					$query->whereRaw('SUBSTR(date, 1, 7) = "' . $request->filter . '"')
          	  		->orderBy('DATE', 'ASC');
				}
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
			
            foreach($query_data as $val) {
				
				$approved = false;
				
				foreach($val->projectPay as $row){
					if($row->checked_id || $row->marketing_id || $row->approved_id){
						$approved = true;
					}
				}
				
				if($val->image){
					if(explode('.',$val->image)[1] == 'pdf'){
						$photo = '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$val->item.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}else{
					$photo = '<span class="badge badge-danger">Empty</span>';
				}
				
				$buttons = $approved == false ? '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
					' : '<span class="badge badge-success">Approved</span>';
				
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
					$val->id,
                    $val->user->name,
                    $val->code,
					date('d M Y',strtotime($val->date)),
					$val->coa->name,
					$val->customer->name,
					number_format($val->nominal,0,',','.'),
					$val->note,
					$photo,
					$buttons 
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
	
	public function getBill(Request $request){
		$so_id = $request->so_id;
		
		$project = ProjectSale::find($so_id)->project;
		$projectdelivery = ProjectDelivery::where('project_sale_id',$so_id)->get();
		
		$resultbill = [];
		$resultdelivery = [];
		
		foreach($project->projectBill as $row){
			if($row->nominal + $row->nominal_service - $row->paid() > 0){
				$resultbill[] = [
					'id'	=> $row->id,
					'text'	=> 'Bill No. '.$row->code.' PJ No. '.$row->project->code.' Rp. '.number_format($row->nominal + $row->nominal_service - $row->paid(),0,',','.')
				];
			}		
		}
		
		foreach($projectdelivery as $row){
			$resultdelivery[] = [
				'id'	=> $row->id,
				'text'	=> 'Delivery No. '.$row->code.' PJ No. '.$row->project->code.' Total Rp '.number_format($row->getTotal()['totaldelivery'] + $row->getServiceCost() - $row->totalPay() - $row->getTotalReturn(),0,',','.'),
				'total' => number_format($row->getTotal()['totaldelivery'] + $row->getServiceCost() - $row->totalPay() - $row->getTotalReturn(),0,',','.'),
			];
		}
		
		return response()->json([
			'bill'		=> $resultbill,
			'delivery'	=> $resultdelivery
		]);
	}
	
	public function create(Request $request)
    {
		if($request->temp_pay){
			$validation = Validator::make($request->all(), [
				'customer_id'   	=> 'required',
				'pay_date' 			=> 'required',
				'pay_coa' 			=> 'required',
				'pay_nominal' 		=> 'required',
			], [
				'customer_id.required'   	=> 'Customer information cannot be empty.',
				'pay_date.required'   		=> 'Date cannot be empty.',
				'pay_coa.required'    		=> 'Cash & bank destination cannot be empty.',
				'pay_nominal.required'		=> 'Nominal cannot be empty.',
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'customer_id'   	=> 'required',
				'pay_date' 			=> 'required',
				'pay_coa' 			=> 'required',
				'pay_nominal' 		=> 'required',
				'pay_file' 			=> 'required'
			], [
				'customer_id.required'   	=> 'Customer information cannot be empty.',
				'pay_date.required'   		=> 'Date cannot be empty.',
				'pay_coa.required'    		=> 'Cash & bank destination cannot be empty.',
				'pay_nominal.required'		=> 'Nominal cannot be empty.',
				'pay_file.required'			=> 'File proof cannot be empty.',
			]);
		}

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$image = '';
			
			if($request->temp_pay){
				
				$query = ProjectMainPayment::find($request->temp_pay);
				
				if($request->has('pay_file')) {
					$image = $request->file('pay_file')->store('public/project');
				}else{
					$image = $query->image;
				}
				
				$query->update([
					'user_id'	     		=> session('bo_id'),
					'code'					=> ProjectMainPayment::generateCode(),
					'date'					=> $request->pay_date,
					'coa_id'				=> $request->pay_coa,
					'customer_id'			=> $request->customer_id,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
					'note'					=> $request->pay_note,
					'payment_method'		=> $request->payment_method,
					'giro_code'				=> $request->giro_code_detail,
					'giro_date'				=> $request->giro_date_detail,
					'image'					=> $image
				]);
				
				$query = ProjectMainPayment::find($request->temp_pay);
				
				$cekcb = CashBank::where('lookable_type','project_main_payments')->where('lookable_id',$query->id)->first();
				
				if($cekcb){
					$bh = BalanceHistory::find(explode('-',$cekcb->code)[1]);
					
					if($bh){
						$bh->deleteFile();
						$bh->delete();
					}
					
					$cekcb->deleteFile();
					$cekcb->deleteDetail();
					$cekcb->delete();
				}
				
				foreach($query->projectPay as $row){
					$this->deletePayment($row->id);
				}
				
			}else{
				
				$image = $request->has('pay_file') ? $request->file('pay_file')->store('public/project') : null;
				
				$query = ProjectMainPayment::create([
					'user_id'	     		=> session('bo_id'),
					'code'					=> ProjectMainPayment::generateCode(),
					'date'					=> $request->pay_date,
					'coa_id'				=> $request->pay_coa,
					'customer_id'			=> $request->customer_id,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
					'note'					=> $request->pay_note,
					'payment_method'		=> $request->payment_method,
					'giro_code'				=> $request->giro_code_detail,
					'giro_date'				=> $request->giro_date_detail,
					'image'					=> $image
				]);
			}
			
			$branch = '';
			
			foreach($request->arr_sales_id as $key => $pps) {
				$ps = ProjectSale::find($pps);
				$branch = $ps->sales->branch;
			}
			
			$description = 'Multi so project payment with project main payment no. '.$query->code.' Customer : '. $query->customer->name;

			
			$cb = CashBank::create([
				'user_id'     		=> session('bo_id'),
				'lookable_type'  	=> 'project_main_payments',
				'customer_id'  		=> $request->customer_deposit_list,
				'lookable_id'		=> $query->id,
				'code'        		=> isset($bh) ? 'BPC-'.$bh->id : strtoupper(Str::random(15)),
				'date'        		=> $request->pay_date,
				'type'        		=> '1',
				'description' 		=> $description,
				'image'				=> $bh->image
			]);
			
			if($cb){
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $request->pay_coa,
					'branch'		=> $branch,
					'type'       	=> '1',
					'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
					'note'         	=> ''
				]);

				Journal::insert([
					'date_transaction' => $request->pay_date,
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $request->pay_coa,
					'branch'		   => $branch,
					'type'	           => '1',
					'nominal'          => round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
			}
			
			if($query) {

							
				if($request->pay_coa !== 67){
					#updatecashbank
					$bh = BalanceHistory::create([
						'user_id' 			  => session('bo_id'),
						'nominal'			  => round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
						'type'				  => 'IN',
						'cash_or_bank'		  => $request->payment_method == '0' ? 'BANK' : 'CASH',
						'cash_bank_reference' => $cb->id,
						'coa_id'			  => $request->pay_coa,
						'branch'			  => $branch,
						'note'				  => $description,
						'date'				  => $request->pay_date,
						'image'				  => $request->file('pay_file') ? $request->file('pay_file')->store('public/cashbank') : ''
					]);
				}
			

				$totalsale = 0;
				
				foreach($request->arr_sales_id as $key => $pps) {
					
					$totalsale += round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0);
					
					$ps = ProjectSale::find($pps);
					
					$projectpay = ProjectPay::create([
						'user_id'				=> session('bo_id'),
						'project_id'     		=> $ps->project->id,
						'project_sale_id'		=> $pps,
						'project_delivery_id'	=> $request->arr_delivery_id[$key],
						'project_bill_id'		=> $request->arr_bill_id[$key],
						'project_main_payment_id'	=> $query->id,
						'code'					=> ProjectPay::generateCode(),
						'image'          		=> $request->file('pay_file') ? $request->file('pay_file')->store('public/project') : $image,
						'date'           		=> $request->pay_date,
						'nominal'        		=> round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
						'payment_method' 		=> $request->arr_type[$key],
						'coa_id' 				=> $request->pay_coa,
						'giro' 					=> $request->payment_method,
						'giro_code'				=> $request->giro_code_detail,
						'giro_date'				=> $request->giro_date_detail,
						'note'					=> $request->pay_note,
						'marketing_id'			=> 0,
						'approved_id'			=> 0
					]);
						
					$projectdetail = $ps->id;
					$reference = '1'; #is sale
					$type = '1'; #journal
					$description = 'Project payment with sales code '.$ps->code;
					
					#START
					// Jika Payment Method Down Payment
					if($request->arr_type[$key] == '1'){
						// SCRIPT GOES HERE pelunasan BILL SELALU BANK PADA AR

						// if($request->arr_bill_id[$key]){
						// 	$kreditcb = 27; //AR
						// }else{
						// 	$kreditcb = 27; //CUST DEPO
						// }

						$kreditcb = 27; //AR

						$sisa = 0;
				
						foreach($ps->projectDelivery as $row){
							$sisa -= $row->grandtotal_product + $row->grandtotal_service;
						}
						
						foreach($ps->project->projectBill as $row){
							$sisa += $row->balanceJournal();
						}
						
						foreach($ps->projectSalePay->whereNull('project_bill_id') as $pp){
							$sisa += $pp->nominal;
						}

						// $sisa = round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) - round($ps->grandtotal_product + $ps->grandtotal_service,0);
						
						if($cb){
							// Jika ada Bill
							if($request->arr_bill_id[$key]){
								
								$bill = ProjectBill::find($request->arr_bill_id[$key]);
								$payment_nominal = round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0);
								if($bill){
									// $payment_nominal ada karena bill->paid (projectPays) tergenerate di script atas, sehingga sistem dibawah akan membaca bahwa sudah ada bill yang terbayar. Agar nominal variable $totalbill sesuai dengan jumlah yang diinputkan user makae ada payment nominal.
									$totalbill = ($bill->nominal + $bill->nominal_service - $bill->paid() + $payment_nominal) > 0 ? ($bill->nominal + $bill->nominal_service - $bill->paid() + $payment_nominal) : 0 ;

									$balancebill = round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) - $totalbill;
									
									if($balancebill > 0){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> 67,
											'branch'		=> $ps->sales->branch,
											'type'       	=> '2',
											'nominal'      	=> $balancebill,
											'note'         	=> 'Project payment invoice no. '.$projectpay->code
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => 67,
											'branch'		   => $ps->sales->branch,
											'type'	           => '2',
											'nominal'          => $balancebill,
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);


										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $ps->sales->branch,
											'type'       	=> '2',
											'nominal'      	=> round(str_replace(',','.',str_replace('.','',$totalbill)),0),
											'note'         	=> 'Project payment invoice no. '.$projectpay->code
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $ps->sales->branch,
											'type'	           => '2',
											'nominal'          => round(str_replace(',','.',str_replace('.','',$totalbill)),0),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}else{
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $ps->sales->branch,
											'type'       	=> '2',
											'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
											'note'         	=> 'Project payment invoice no. '.$projectpay->code
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $ps->sales->branch,
											'type'	           => '2',
											'nominal'          => round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}
								
								
								// CashBankDetail::create([
								// 	'cash_bank_id' 	=> $cb->id,
								// 	'coa_id'       	=> $kreditcb,
								// 	'branch'		=> $ps->sales->branch,
								// 	'type'       	=> '2',
								// 	'nominal'      	=> $sisa,
								// 	'note'         	=> 'Project payment invoice no. '.$projectpay->code
								// ]);
								
								// Journal::insert([
								// 	'date_transaction' => $request->pay_date,
								// 	'journalable_type' => 'cash_banks',
								// 	'journalable_id'   => $cb->id,
								// 	'coa_id'           => $kreditcb,
								// 	'branch'		   => $ps->sales->branch,
								// 	'type'	           => '2',
								// 	'nominal'          => $sisa,
								// 	'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								// 	'updated_at'       => date('Y-m-d H:i:s')
								// ]);
								
							}else{
								if($sisa > 1){
							
									$kreditcb2 = 67;
									$nominal_payment  = $sisa - round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0);

									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $ps->sales->branch,
										'type'       	=> '2',
										'nominal'      	=>  $nominal_payment >= 0 ? 0 : abs(round($nominal_payment, 0)),
										'note'         	=> 'Project payment invoice no. '.$projectpay->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->pay_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $ps->sales->branch,
										'type'	           => '2',
										'nominal'          => $nominal_payment >= 0 ? 0 : abs(round($nominal_payment, 0)),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb2,
										'branch'		=> $ps->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $nominal_payment >= 0 ? round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) : round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) -  abs(round($nominal_payment, 0)),
										'note'         	=> 'Project payment invoice no. '.$projectpay->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->pay_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb2,
										'branch'		   => $ps->sales->branch,
										'type'	           => '2',
										'nominal'          => $nominal_payment >= 0 ? round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) : round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) -  abs(round($nominal_payment, 0)),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
								}else{
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $ps->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
										'note'         	=> 'Project payment invoice no. '.$projectpay->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->pay_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $ps->sales->branch,
										'type'	           => '2',
										'nominal'          => round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
							}
						}
					}else{
						
						$sisa = 0;
				

						$emptyDelivery = true;
						foreach($ps->projectDelivery->whereNotNull('received_date') as $row){
							$sisa -= $row->grandtotal_product + $row->grandtotal_service;
							$emptyDelivery = false;
						}
						
						foreach($ps->project->projectBill as $row){
							$sisa += $row->balanceJournal();
						}
						
						foreach($ps->projectSalePay->whereNull('project_bill_id') as $pp){
							$sisa += $pp->nominal;
						}
						// SCRIPT GOES HERE pelunasan BILL SELALU BANK PADA AR
						if($emptyDelivery){
							if($request->arr_bill_id[$key]){
								$kreditcb = 27;
							}else{
								$kreditcb = 67;
							}
						}else{
							$kreditcb = 27;
						}
						// $kreditcb = 27;
						if($cb){
							
							if($request->arr_bill_id[$key]){
								
								$bill = ProjectBill::find($request->arr_bill_id[$key]);
								$payment_nominal = round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0);
								
								if($bill){
									$totalbill = ($bill->nominal + $bill->nominal_service - $bill->paid() + $payment_nominal) > 0 ? ($bill->nominal + $bill->nominal_service - $bill->paid() + $payment_nominal) : 0 ;

									$balancebill = round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) - $totalbill;
									
									if($balancebill > 0){
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> 67,
											'branch'		=> $ps->sales->branch,
											'type'       	=> '2',
											'nominal'      	=> $balancebill,
											'note'         	=> 'Project payment invoice no. '.$projectpay->code
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => 67,
											'branch'		   => $ps->sales->branch,
											'type'	           => '2',
											'nominal'          => $balancebill,
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);


										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $ps->sales->branch,
											'type'       	=> '2',
											'nominal'      	=> round(str_replace(',','.',str_replace('.','',$totalbill)),0),
											'note'         	=> 'Project payment invoice no. '.$projectpay->code
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $ps->sales->branch,
											'type'	           => '2',
											'nominal'          => round(str_replace(',','.',str_replace('.','',$totalbill)),0),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}else{
										CashBankDetail::create([
											'cash_bank_id' 	=> $cb->id,
											'coa_id'       	=> $kreditcb,
											'branch'		=> $ps->sales->branch,
											'type'       	=> '2',
											'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
											'note'         	=> 'Project payment invoice no. '.$projectpay->code
										]);
										
										Journal::insert([
											'date_transaction' => $request->pay_date,
											'journalable_type' => 'cash_banks',
											'journalable_id'   => $cb->id,
											'coa_id'           => $kreditcb,
											'branch'		   => $ps->sales->branch,
											'type'	           => '2',
											'nominal'          => round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
											'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
											'updated_at'       => date('Y-m-d H:i:s')
										]);
									}
								}
								// if($bill){
								// 	$totalbill = $bill->nominal + $bill->nominal_service - $bill->paid();
								// 	$balancebill = round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) - $totalbill;
									
								// 	if($balancebill > 0){
								// 		CashBankDetail::create([
								// 			'cash_bank_id' 	=> $cb->id,
								// 			'coa_id'       	=> $kreditcb,
								// 			'branch'		=> $ps->sales->branch,
								// 			'type'       	=> '2',
								// 			'nominal'      	=> $balancebill,
								// 			'note'         	=> 'Project payment invoice no. '.$projectpay->code
								// 		]);
										
								// 		Journal::insert([
								// 			'date_transaction' => $request->pay_date,
								// 			'journalable_type' => 'cash_banks',
								// 			'journalable_id'   => $cb->id,
								// 			'coa_id'           => $kreditcb,
								// 			'branch'		   => $ps->sales->branch,
								// 			'type'	           => '2',
								// 			'nominal'          => $balancebill,
								// 			'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								// 			'updated_at'       => date('Y-m-d H:i:s')
								// 		]);
								// 	}
								// }
								
								// CashBankDetail::create([
								// 	'cash_bank_id' 	=> $cb->id,
								// 	'coa_id'       	=> 67,
								// 	'branch'		=> $ps->sales->branch,
								// 	'type'       	=> '2',
								// 	'nominal'      	=> $sisa,
								// 	'note'         	=> 'Project payment invoice no. '.$projectpay->code
								// ]);
								
								// Journal::insert([
								// 	'date_transaction' => $request->pay_date,
								// 	'journalable_type' => 'cash_banks',
								// 	'journalable_id'   => $cb->id,
								// 	'coa_id'           => 67,
								// 	'branch'		   => $ps->sales->branch,
								// 	'type'	           => '2',
								// 	'nominal'          => $sisa,
								// 	'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								// 	'updated_at'       => date('Y-m-d H:i:s')
								// ]);
								// 	if($balancebill > 0){
								// 		CashBankDetail::create([
								// 			'cash_bank_id' 	=> $cb->id,
								// 			'coa_id'       	=> 67,
								// 			'branch'		=> $ps->sales->branch,
								// 			'type'       	=> '2',
								// 			'nominal'      	=> $balancebill,
								// 			'note'         	=> 'Project payment invoice no. '.$projectpay->code
								// 		]);
										
								// 		Journal::insert([
								// 			'date_transaction' => $request->pay_date,
								// 			'journalable_type' => 'cash_banks',
								// 			'journalable_id'   => $cb->id,
								// 			'coa_id'           => 67,
								// 			'branch'		   => $ps->sales->branch,
								// 			'type'	           => '2',
								// 			'nominal'          => $balancebill,
								// 			'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								// 			'updated_at'       => date('Y-m-d H:i:s')
								// 		]);
								// 	}
								// }
								
								// CashBankDetail::create([
								// 	'cash_bank_id' 	=> $cb->id,
								// 	'coa_id'       	=> $kreditcb,
								// 	'branch'		=> $ps->sales->branch,
								// 	'type'       	=> '2',
								// 	'nominal'      	=> $sisa,
								// 	'note'         	=> 'Project payment invoice no. '.$projectpay->code
								// ]);
								
								// Journal::insert([
								// 	'date_transaction' => $request->pay_date,
								// 	'journalable_type' => 'cash_banks',
								// 	'journalable_id'   => $cb->id,
								// 	'coa_id'           => $kreditcb,
								// 	'branch'		   => $ps->sales->branch,
								// 	'type'	           => '2',
								// 	'nominal'          => $sisa,
								// 	'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
								// 	'updated_at'       => date('Y-m-d H:i:s')
								// ]);
								
							}else{
								if($sisa > 1){
									$projectdelivery = ProjectDelivery::find($request->delivery_id);
								
									$kreditcb2 = 67;

									$nominal_payment  = $sisa - round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0);

									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $ps->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> $nominal_payment >= 0 ? 0 : abs(round($nominal_payment, 0)),
										'note'         	=> 'Project payment invoice no. '.$projectpay->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->pay_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $ps->sales->branch,
										'type'	           => '2',
										'nominal'          => $nominal_payment >= 0 ? 0 : abs(round($nominal_payment, 0)),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb2,
										'branch'		=> $ps->sales->branch,
										'type'       	=> '2',
										'nominal'      	=>  $nominal_payment >= 0 ? round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0)  : round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) -  abs(round($nominal_payment, 0)),
										'note'         	=> 'Project payment invoice no. '.$projectpay->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->pay_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb2,
										'branch'		   => $ps->sales->branch,
										'type'	           => '2',
										'nominal'          =>  $nominal_payment >= 0 ? round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) : round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0) -  abs(round($nominal_payment, 0)),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
									
								}else{
									CashBankDetail::create([
										'cash_bank_id' 	=> $cb->id,
										'coa_id'       	=> $kreditcb,
										'branch'		=> $ps->sales->branch,
										'type'       	=> '2',
										'nominal'      	=> round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
										'note'         	=> 'Project payment invoice no. '.$projectpay->code
									]);
									
									Journal::insert([
										'date_transaction' => $request->pay_date,
										'journalable_type' => 'cash_banks',
										'journalable_id'   => $cb->id,
										'coa_id'           => $kreditcb,
										'branch'		   => $ps->sales->branch,
										'type'	           => '2',
										'nominal'          => round(str_replace(',','.',str_replace('.','',$request->arr_nominal[$key])),0),
										'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
										'updated_at'       => date('Y-m-d H:i:s')
									]);
								}
							}
						}
					}
					
					#start notif
					$role = array('1','2','3','4','5','6','7','9','10','11');
					$title = 'Project payment has been updated!';
					$description = 'Project '.$ps->project->code.' with details payment sales code '.$ps->code.' and invoice '.$projectpay->code.' has been updated by '.session('bo_name').' in Project Payment Sales Form.';
					$link = '#';
					Notification::sendNotif($role,$title,$description,$link);
					#end notif
					
					$ceklunas = Project::find($ps->project->id)->getBalance();
					
					if($ceklunas <= 0){
						/* Project::find($ps->project->id)->update([
							'progress' => 85
						]);
						
						#send approval
						$roleapproval = array('1');
						Approval::sendApproval($roleapproval,'projects',$ps->project->id,'',session('bo_id'));
						#end approval
						
						SendMessage::send(env('OWNER_PHONE'),'Halo pak/bu. Mohon dibantu approve Penutupan Project No. '.$ps->project->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.'); */
					}
				}
				
				if((round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0) - $totalsale) > 0 && round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0) > $totalsale){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> 67,
						'branch'		=> $branch,
						'type'       	=> '2',
						'nominal'      	=> (round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0) - $totalsale),
						'note'         	=> 'Project payment invoice no. '.$projectpay->code
					]);
					
					Journal::insert([
						'date_transaction' => $request->pay_date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => 67,
						'branch'		   => $branch,
						'type'	           => '2',
						'nominal'          => (round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0) - $totalsale),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
				
				#send approval
				$roleapproval = array('3');
				Approval::sendApproval($roleapproval,'project_main_payments',$query->id,'checked_id',session('bo_id'));
				$roleapproval = array('5');
				Approval::sendApproval($roleapproval,'project_main_payments',$query->id,'marketing_id',session('bo_id'));
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval,'project_main_payments',$query->id,'approved_id',session('bo_id'));
				#end approval
				
				// SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Multi Payment Project No. '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				// SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Multi Payment Project No. '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				// SendMessage::send(env('FINANCE_PHONE'),'Halo pak/bu. Mohon dibantu approve Multi Payment Project No. '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				
				$update = ProjectMainPayment::find($query->id)->update([
					'cash_bank_id' => $cb->id
				]);
				
				activity()
					->performedOn(new ProjectMainPayment())
					->causedBy(session('bo_id'))
					->withProperties($query)
					->log('Add project payment by user '.session('bo_name'));

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

        return response()->json($response);
    }
	
	public function rowDetail(Request $request)
    {
        $data   = ProjectMainPayment::find($request->id);
		
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Project</th>
							<th>SO No.</th>
							<th>DO No.</th>
							<th>Bill No.</th>
							<th>Total</th>
							<th>Checked By</th>
							<th>Marketing</th>
							<th>Approved By</th>
						</tr>
					</thead>
					<tbody>';

        foreach($data->projectPay as $row) {
			$string .= '
				<tr>
					<td class="text-center">'.$row->project->name.'</td>
					<td class="text-center">'.$row->projectSale->code.'</td>
					<td class="text-center">'.($row->projectDelivery ? $row->projectDelivery->code : 'None').'</td>
					<td class="text-center">'.($row->projectBill ? $row->projectBill->code : 'None').'</td>
					<td class="text-right">'.number_format($row->nominal,0,',','.').'</td>
					<td class="text-center">'.(isset($row->check) ? '<span class="badge badge-success">Done</span>' : '<span class="badge badge-warning">Waiting</span>').'</td>
					<td class="text-center">'.(isset($row->marketing) ? '<span class="badge badge-success">Done</span>' : '<span class="badge badge-warning">Waiting</span>').'</td>
					<td class="text-center">'.(isset($row->approved) ? '<span class="badge badge-success">Done</span>' : '<span class="badge badge-warning">Waiting</span>').'</td>
				</tr>
			';
        }

        $string .= '</tbody></table>';
		
        return response()->json($string);
    }
	
	public function show(Request $request)
    {
        $data = ProjectMainPayment::find($request->id);
		$data['customer_name'] = $data->customer->name;
		$data['nominalconvert'] = number_format($data->nominal,0,',','.');
		$data['proof_file'] = $data->attachment();
		
		$detail = [];
		
		foreach($data->projectPay as $row){
			$detail[] = [
				'project_id' 				=> $row->project_id,
				'project_sale_id'			=> $row->project_sale_id,
				'project_sale_code'			=> $row->projectSale->code,
				'project_delivery_id'		=> $row->project_delivery_id,
				'project_delivery_code'		=> $row->projectDelivery ? $row->projectDelivery->code : '',
				'project_bill_id'			=> $row->project_bill_id,
				'project_bill_code'			=> $row->projectBill ? $row->projectBill->code : '',
				'project_main_payment_id'	=> $row->project_main_payment_id,
				'nominal'					=> number_format($row->nominal,0,',','.'),
				'date'						=> $row->date,
				'type'						=> $row->payment_method,
				'type_name'					=> $row->paymentMethod(),
				'giro'						=> $row->giro,
				'giro_name'					=> $row->giro(),
				'giro_code'					=> $row->giro_code,
				'giro_date'					=> $row->giro_date ? $row->giro_date : '',
				'note'						=> $row->note
			];
		}
		
        return response()->json([
			'data'		=> $data,
			'detail'	=> $detail
		]);
    }
	
	public function deletePayment($idpay)
	{
		$pp = ProjectPay::find($idpay);
		$pp->deleteFile();
		$pp->delete();
	}
	
	public function destroy(Request $request) 
    {
        $query = ProjectMainPayment::find($request->id);
		
		$query->deleteFile();
		
        if($query->delete()) {
			
			$cekcb = CashBank::where('lookable_type','project_main_payments')->where('lookable_id',$query->id)->first();
				
			if($cekcb){
				$bh = BalanceHistory::find(explode('-',$cekcb->code)[1]);
				
				if($bh){
					$bh->deleteFile();
					$bh->delete();
				}
				
				$cekcb->deleteFile();
				$cekcb->deleteDetail();
				$cekcb->delete();
			}
			
			foreach($query->projectPay as $row){
				$this->deletePayment($row->id);
			}
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project payment has been deleted!';
			$description = 'Project '.$query->code.' has been deleted.';
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
                    
            activity()
                ->performedOn(new CashBank())
                ->causedBy(session('bo_id'))
                ->log('Delete the payment data');

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

        return response()->json($response);
    }
	
	public function getBalanceCustomer(Request $request)
    {
        $data = Project::where('customer_id',$request->customer)->get();
		
		$result = [];
		
		$balance = 0;
		
		foreach($data as $project){
			
			foreach($project->projectSale as $row){
				
				if((str_replace(',','.',str_replace('.','',$row->getPaid())) - str_replace(',','.',str_replace('.','',$row->getTotal()))) > 0){
					$balance += str_replace(',','.',str_replace('.','',$row->getPaid())) - str_replace(',','.',str_replace('.','',$row->getTotal()));
				}else{
					$balance += 0;
				}
				
				$result[] = [
					'project'			=> $project->code,
					'project_sale'		=> $row->code,
					'total_sales'		=> number_format(str_replace(',','.',str_replace('.','',$row->getTotal())),0,',','.'),
					'total_payment'		=> number_format(str_replace(',','.',str_replace('.','',$row->getPaid())),0,',','.'),
					'balance'			=> number_format($balance,0,',','.')
				];
				
			}
		}
		
        return response()->json($result);
    }

	function getPaymentDate(Request $request){
		if($request->delivery_id){
			$project_delivery = ProjectDelivery::find($request->delivery_id);
		}

		if($request->bill_id){
			$project_bill = ProjectBill::find($request->bill_id);
		}

		return response()->json([
			'project_delivery_date' => $request->delivery_id ? $project_delivery->received_date: '',
			'project_bill_date'     => $request->bill_id ? $project_bill->date : '',
		]);
	}
}
