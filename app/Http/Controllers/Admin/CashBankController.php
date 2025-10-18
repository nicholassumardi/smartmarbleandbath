<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coa;
use App\Models\Approval;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\ProjectWarehouse;
use App\Models\ProjectPayment;
use App\Models\ProjectPay;
use App\Models\ProjectDelivery;
use App\Models\Journal;
use App\Models\Project;
use App\Models\BudgetingProject;
use App\Models\BalanceHistory;
use App\Models\CashBank;
use App\Models\ProjectSale;
use App\Models\Transfer;
use App\Models\PurchaseRequest;
use App\Models\ProjectPurchase;
use App\Models\ReceivablePayment;
use App\Models\PurchaseRequestPayment;
use Illuminate\Http\Request;
use App\Models\CashBankDetail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helper\CheckCutOff;
use App\Models\SampleDelivery;
use App\Models\SampleWarehouse;

class CashBankController extends Controller {
    
    public function index(Request $request)
    {
		$edit = $request->mode_edit ? $request->mode_edit : '';
		$id = $request->id ? $request->id : '';
		$totalunmatch = CashBank::where(function($query) { 
							$query->has('cashBankDetail', '=', 2)
							->whereHas('cashBankDetail', function($query) {
								$query->where('branch','1');
							})
							->whereHas('cashBankDetail', function($query) {
								$query->where('branch','2');
							}); 
						})->orWhere(function($query){
							$query->has('cashBankDetail', '=', 3)
							->whereHas('cashBankDetail', function($query) {
								$query->where('branch','1');
							})
							->whereHas('cashBankDetail', function($query) {
								$query->where('branch','2');
							});
						})->count();
		
        $data = [
            'title'   => 'Cash & Bank',
			'project' => Project::all(),
			'unmatch' => $totalunmatch,
			'purchase' => PurchaseRequest::all(),
			'projectreal' => Project::all(),
            'user'    => User::all(),
			'supplier'=> Supplier::all(),
			'customer'=> Customer::all(),
            'coa'     => Coa::where('status', 1)->oldest('code')->get(),
			'edit'	  => $edit,
			'id'	  => $id,
            'content' => 'admin.finance.cash_bank'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'detail',
            'id',
            'user_id',
			'customer_id',
            'code',
            'total',
            'date',
            'description'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = CashBank::count();
        
		if($request->mode){
			$query_data = CashBank::where(function($query) { 
				$query->has('cashBankDetail', '=', 2)
					->whereHas('cashBankDetail', function($query) {
						$query->where('branch','1');
					})
					->whereHas('cashBankDetail', function($query) {
						$query->where('branch','2');
					}); 
				})->orWhere(function($query){
					$query->has('cashBankDetail', '=', 3)
					->whereHas('cashBankDetail', function($query) {
						$query->where('branch','1');
					})
					->whereHas('cashBankDetail', function($query) {
						$query->where('branch','2');
					});
				})
				->get();

			$total_filtered = CashBank::where(function($query) { 
				$query->has('cashBankDetail', '=', 2)
					->whereHas('cashBankDetail', function($query) {
						$query->where('branch','1');
					})
					->whereHas('cashBankDetail', function($query) {
						$query->where('branch','2');
					}); 
				})->orWhere(function($query){
					$query->has('cashBankDetail', '=', 3)
					->whereHas('cashBankDetail', function($query) {
						$query->where('branch','1');
					})
					->whereHas('cashBankDetail', function($query) {
						$query->where('branch','2');
					});
				})->count();
		}else{
			$query_data = CashBank::where(function($query) use ($search, $request) {
					if($search) {
						$query->where(function($query) use ($search) {
							$query->where('code', 'like', "%$search%")
								->orWhereHas('user', function($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('customer', function($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('cashBankDetail', function($query) use ($search) {
									$query->where('nominal', 'like', "%$search%");
								})
								->orWhere('description', 'like', "%$search%");
						});
					}
					
					if($request->user_id) {
						$query->where('user_id', $request->user_id);
					}

					if($request->start_date && $request->finish_date) {
						$query->whereDate('date', '>=', $request->start_date)
							->whereDate('date', '<=', $request->finish_date);
					} else if($request->start_date) {
						$query->whereDate('date', $request->start_date);
					} else if($request->finish_date) {
						$query->whereDate('date', $request->finish_date);
					}

					if($request->type) {
						$query->where('type', $request->type);
					}
				})
				->offset($start)
				->limit($length)
				->orderBy($order, $dir)
				->get();

			$total_filtered = CashBank::where(function($query) use ($search, $request) {
					if($search) {
						$query->where(function($query) use ($search) {
							$query->where('code', 'like', "%$search%")
								->orWhereHas('user', function($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('customer', function($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								})
								->orWhereHas('cashBankDetail', function($query) use ($search) {
									$query->where('nominal', 'like', "%$search%");
								})
								->orWhere('description', 'like', "%$search%");
						});
					}
					
					if($request->user_id) {
						$query->where('user_id', $request->user_id);
					}

					if($request->start_date && $request->finish_date) {
						$query->whereDate('date', '>=', $request->start_date)
							->whereDate('date', '<=', $request->finish_date);
					} else if($request->start_date) {
						$query->whereDate('date', $request->start_date);
					} else if($request->finish_date) {
						$query->whereDate('date', $request->finish_date);
					}

					if($request->type) {
						$query->where('type', $request->type);
					}
				})
				->count();
		}
        

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				$customer = '';
				
				$proof = '';
				$ref = '';
				
				if($val->lookable){
					if($val->lookable->getTable() !== 'projects'){
						if($val->lookable_type == 'purchase_requests' || $val->lookable_type == 'purchase_request_payments' || $val->lookable_type == 'receivable_payments' || $val->lookable_type == 'purchase_request_main_payments'){
							if($val->lookable_type == 'purchase_requests'){
								$customer = $val->lookable->supplier ? $val->lookable->supplier->name : '';
							}
							if($val->lookable_type == 'purchase_request_payments'){
								$customer = $val->lookable->purchaseRequest->supplier ? $val->lookable->purchaseRequest->supplier->name : '';
							}
							
							if($val->lookable_type == 'receivable_payments'){
								$customer = $val->lookable->customer ? $val->lookable->customer->name : '';
							}
							
							if($val->lookable_type == 'purchase_request_main_payments'){
								$customer = '';
							}
						}else{
							if($customer = $val->lookable->project){
								$customer = $val->lookable->project->customer->name;
							}else{
								if($customer = $val->lookable_type == 'transfers'){
									$customer = '';
								}else{
									if($val->lookable_type == 'project_main_payments'){
										$customer = $val->lookable->customer ? $val->lookable->customer->name : '';
									}else{
										$customer = isset($val->lookable->projectPurchase->supplier) ? $val->lookable->projectPurchase->supplier->name : '';
									}
								}
							}
						}
						
					}else{
						$customer = $val->lookable->customer->name;
					}
				}elseif($val->customer){
					$customer = $val->customer->name;
				}
				
				if(substr($val->code,0,3) == 'PR-' && count(explode('-',$val->code)) == 2){
					$prf = PurchaseRequest::find(intval(explode('-',$val->code)[1]));
					
					if(isset($prf->image)){
						if(explode('.',$prf->image)[1] == 'pdf'){
							$ref .= '<a href="' .$prf->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
						}else{
							$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$prf->item.'" data-group="a" href="' .$prf->attachment() . '"><img src="' . $prf->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
						}
					}
				}
				
				if(substr($val->code,0,4) == 'BPC-' && count(explode('-',$val->code)) == 2){
					$bh = BalanceHistory::where('cash_bank_reference',$val->id)->first();
					
					if($bh){
						if(isset($bh->image) && $bh->image !== ''){
							if(explode('.',$bh->image)[1] == 'pdf'){
								$ref .= '<a href="' .$bh->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$bh->item.'" data-group="a" href="' .$bh->attachment() . '"><img src="' . $bh->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
				
				if(substr($val->code,0,4) == 'BPC-' && count(explode('-',$val->code)) == 2){
					$bh = BalanceHistory::find(intval(explode('-',$val->code)[1]));
					
					if(isset($bh->image) && $bh->image !== ''){
						if(explode('.',$bh->image)[1] == 'pdf'){
							$ref .= '<a href="' .$bh->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
						}else{
							$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$bh->item.'" data-group="a" href="' .$bh->attachment() . '"><img src="' . $bh->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
						}
					}
				}
				
				if(substr($val->code,0,4) == 'PRP-' && count(explode('-',$val->code)) == 2){
					$prp = PurchaseRequestPayment::find(explode('-',$val->code)[1]);
					
					if($prp->purchaseRequest()->exists()){
						if($prp->purchaseRequest->image){
							if(explode('.',$prp->purchaseRequest->image)[1] == 'pdf'){
								$ref .= '<a href="' .$prp->purchaseRequest->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$prp->purchaseRequest->item.'" data-group="a" href="' .$prp->purchaseRequest->attachment() . '"><img src="' . $prp->purchaseRequest->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
				
				if($val->lookable_type == 'project_warehouses'){
					$pw = ProjectWarehouse::find($val->lookable_id);
					
					if(isset($pw)){
						if($pw->image){
							if(explode('.',$pw->image)[1] == 'pdf'){
								$ref .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
				
				if($val->lookable_type == 'sample_warehouses'){
					$pw = SampleWarehouse::find($val->lookable_id);
					
					if(isset($pw)){
						if($pw->image){
							if(explode('.',$pw->image)[1] == 'pdf'){
								$ref .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}

				if($val->lookable_type == 'sample_deliveries'){
					$pw = SampleDelivery::find($val->lookable_id);
					
					if($pw){
						if($pw->image){
							if(explode('.',$pw->image)[1] == 'pdf'){
								$ref .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
				
				if($val->lookable_type == 'project_payments'){
					$pw = ProjectPayment::find($val->lookable_id);
					
					if($pw){
						if($pw->image){
							if(explode('.',$pw->image)[1] == 'pdf'){
								$ref .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
				
				if($val->lookable_type == 'project_deliveries'){
					$pw = ProjectDelivery::find($val->lookable_id);
					
					if($pw){
						if($pw->image){
							if(explode('.',$pw->image)[1] == 'pdf'){
								$ref .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
			
				
				if($val->lookable_type == 'project_pays'){
					$pw = ProjectPay::find($val->lookable_id);
					
					if($pw){
						if($pw->image){
							if(explode('.',$pw->image)[1] == 'pdf'){
								$ref .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
				
				if($val->lookable_type == 'transfers'){
					$pw = Transfer::find($val->lookable_id);
					
					if($pw){
						if($pw->image){
							if(explode('.',$pw->image)[1] == 'pdf'){
								$ref .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
				
				if($val->lookable_type == 'receivable_payments'){
					$pw = ReceivablePayment::find($val->lookable_id);
					
					if($pw){
						if($pw->image){
							if(explode('.',$pw->image)[1] == 'pdf'){
								$ref .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
							}else{
								$ref .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
							}
						}
					}
				}
				
				if($val->image){
					if(explode('.',$val->image)[1] == 'pdf'){
						$proof .= '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$val->code.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
                
				$response['data'][] = [
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $val->id,
                    $val->user->name,
                    $customer,
					$val->code,
                    number_format($val->cashBankDetail->where('type','1')->sum('nominal'), 2, ',', '.'),
                    date('d M Y', strtotime($val->date)),
                    $val->description,
					$ref.'',
					$proof.'',
					in_array(4, session('bo_role')) || in_array(1, session('bo_role')) ? 
                    '
						<a href="' . url('admin/finance/cash_bank/print/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Print"><i class="icon-printer"></i></a>
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    ' : 
					'
						<a href="' . url('admin/finance/cash_bank/print/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Print"><i class="icon-printer"></i></a>
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

    public function suggestCode(Request $request)
    {
        $response = [];
        $data     = CashBank::where('code', 'like', "%$request->search%")
            ->limit(15)
            ->get();

        foreach($data as $d) {
            $response[] = $d->code;
        }

        return response()->json($response);
    }

    public function rowDetail(Request $request)
    {
        $data   = CashBankDetail::where('cash_bank_id', $request->id)->orderBy('id')->get();
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Coa</th>
							<th>Debit</th>
							<th>Kredit</th>
							<th>Note</th>
						</tr>
					</thead>
					<tbody>';

        foreach($data as $d) {
			if($d->type == '1'){
				$string .= '
					<tr>
						<td>'.$d->coa->name.' - '.$d->branch().'</td>
						<td class="text-center">'.number_format($d->nominal,2,',','.').'</td>
						<td class="text-center">-</td>
						<td>'.$d->note.'</td>
					</tr>
				';
			}elseif($d->type == '2'){
				$string .= '
					<tr>
						<td>'.$d->coa->name.' - '.$d->branch().'</td>
						<td class="text-center">-</td>
						<td class="text-center">'.number_format($d->nominal,2,',','.').'</td>
						<td>'.$d->note.'</td>
					</tr>
				';
			}
        }

        $string .= '</tbody></table>';
		
        return response()->json($string);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code'           => 'required|unique:cash_banks,code',
            'coa_detail'	 => 'required',
            'type_detail' 	 => 'required',
            'nominal_detail' => 'required',
            'date'           => 'required',
            'type'           => 'required',
            'description'    => 'required'
        ], [
            'code.required'           => 'Code cannot be a empty.',
            'code.unique'             => 'Code already exists.',
            'coa_detail.required'     => 'Coa transaction cannot be a empty.',
            'type_detail.required' 	  => 'Type transaction cannot be a empty.',
            'nominal_detail.required' => 'Detail transaction cannot be a empty.',
            'date.required'           => 'Date cannot be empty.',
            'type.required'           => 'Please select a type.',
            'description.required'    => 'Description cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            
			if(CheckCutOff::check($request->branch_detail[0],substr($request->date,0,7))){
			
				if($request->is_payment_purchase){
					
					$prp = PurchaseRequestPayment::find(explode('-',$request->code)[1]);
					
					$query = CashBank::create([
						'user_id'     			=> session('bo_id'),
						'lookable_id'			=> $prp->id,
						'lookable_type'			=> 'purchase_request_payments',
						'supplier_id'			=> $request->supplier_id,
						'customer_id'			=> $request->customer_id,
						'no_nota'				=> $request->receipt,
						'request_date'			=> $request->request_date,
						'due_date'				=> $request->due_date,
						'image'					=> $request->file('file') ? $request->file('file')->store('public/cashbank') : '',
						'code'        			=> $request->code,
						'date'        			=> $request->date,
						'type'        			=> $request->type,
						'description' 			=> $request->description,
						'purchase_request_ref'	=> $request->purchase_request_id ? $request->purchase_request_id : NULL
					]);
					
					if($request->balance == '1'){
						$cekbh = BalanceHistory::where('nominal',$prp->nominal)->where('type','OUT')->where('date',$prp->date_paid)->where('coa_id',$prp->coa_id)->first();
						
						if($cekbh){
							$cekbh->update([
								'cash_bank_reference'		=> $query->id
							]);
						}else{
							BalanceHistory::create([
								'user_id' 					=> session('bo_id'),
								'nominal'					=> $prp->nominal,
								'type'						=> 'OUT',
								'cash_or_bank'				=> in_array($prp->coa_id,array(2,5,345)) ? 'CASH' : 'BANK',
								'coa_id'					=> $prp->coa_id,
								'cash_bank_reference'		=> $query->id,
								'branch'					=> $prp->branch,
								'note'						=> $prp->purchaseRequest->item.' '.$prp->note,
								'date'						=> $prp->date_paid
							]);
						}
					
					}
				}elseif($request->is_receivable_payment){
						
					$prp = ReceivablePayment::find(explode('-',$request->code)[1]);
					
					$query = CashBank::create([
						'user_id'     			=> session('bo_id'),
						'lookable_id'			=> $prp->id,
						'lookable_type'			=> 'receivable_payments',
						'customer_id'			=> $prp->customer_id,
						'code'        			=> $request->code,
						'date'        			=> $request->date,
						'type'        			=> $request->type,
						'description' 			=> $request->description
					]);
					
					if($request->include_balance == '1'){
						BalanceHistory::create([
							'user_id' 					=> session('bo_id'),
							'nominal'					=> $prp->nominal,
							'type'						=> 'IN',
							'cash_or_bank'				=> in_array($prp->coa_id,array(2,5,345)) ? 'CASH' : 'BANK',
							'coa_id'					=> $prp->coa_id,
							'cash_bank_reference'		=> $query->id,
							'branch'					=> $request->branch_detail[0],
							'note'						=> $prp->note,
							'date'						=> $prp->date
						]);
					}
				}else{
					
					$lookable_id = NULL;
					$lookable_type = NULL;
					
					if($request->project_id !== 'null'){
						$lookable_id = $request->project_id;
						$lookable_type = 'projects';
					}elseif($request->purchase_id !== 'null'){
						$lookable_id = $request->purchase_id;
						$lookable_type = 'project_purchases';
					}
					
					$query = CashBank::create([
						'user_id'     			=> session('bo_id'),
						'lookable_id'			=> $lookable_id,
						'lookable_type'			=> $lookable_type,
						'supplier_id'			=> $request->supplier_id,
						'customer_id'			=> $request->customer_id,
						'no_nota'				=> $request->receipt,
						'request_date'			=> $request->request_date,
						'due_date'				=> $request->due_date,
						'image'					=> $request->file('file') ? $request->file('file')->store('public/cashbank') : '',
						'code'        			=> $request->code,
						'date'        			=> $request->date,
						'type'        			=> $request->type,
						'description' 			=> $request->description
					]);
				}

				if($query) {
					foreach($request->coa_detail as $key => $dd) {
						CashBankDetail::create([
							'cash_bank_id' 	=> $query->id,
							'coa_id'       	=> $dd,
							'branch'		=> $request->branch_detail[$key],
							'type'       	=> $request->type_detail[$key],
							'nominal'      	=> str_replace(',','.',str_replace('.','',$request->nominal_detail[$key])),
							'note'         	=> $request->note_detail[$key]
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $query->id,
							'coa_id'           => $dd,
							'branch'		   => $request->branch_detail[$key],
							'type'	           => $request->type_detail[$key],
							'nominal'          => str_replace(',','.',str_replace('.','',$request->nominal_detail[$key])),
							'created_at'       => date('Y-m-d', strtotime($query->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}

					activity()
						->performedOn(new CashBank())
						->causedBy(session('bo_id'))
						->withProperties($query)
						->log('Add accounting cash & bank data');

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

    public function show(Request $request)
    {
        $data             = CashBank::find($request->id);
        $cash_bank_detail = [];
		
		$proof = '';
		
		$prcoa = 0;
		
		if(substr($data->code,0,3) == 'PR-' && count(explode('-',$data->code)) == 2){
			$prf = PurchaseRequest::find(intval(explode('-',$data->code)[1]));
			
			if(isset($prf->image)){
				if(explode('.',$prf->image)[1] == 'pdf'){
					$proof .= '<a href="' .$prf->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
				}else{
					$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$prf->item.'" data-group="a" href="' .$prf->attachment() . '"><img src="' . $prf->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
				}
			}
			
			if($prf->link_type == 'project_sales'){
				$rowsale = ProjectSale::find($prf->link_id);
				if($rowsale){
					$proof .= '<a href="'.url('admin/sales/project/print/so_do_news/'. base64_encode($rowsale->id)).'" class="btn btn-sm btn-warning" target="_blank">SO</a>';
				}
			}
			
			$prcoa = $prf->coa_id;
		}
		
		if(substr($data->code,0,4) == 'BPC-' && count(explode('-',$data->code)) == 2){
			$bh = BalanceHistory::where('cash_bank_reference',$data->id)->first();
			
			if($bh){
				if(isset($bh->image) && $bh->image !== ''){
					if(explode('.',$bh->image)[1] == 'pdf'){
						$proof .= '<a href="' .$bh->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$bh->item.'" data-group="a" href="' .$bh->attachment() . '"><img src="' . $bh->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}
		
		if(substr($data->code,0,4) == 'BPC-' && count(explode('-',$data->code)) == 2){
			$bh = BalanceHistory::find(intval(explode('-',$data->code)[1]));
			
			if(isset($bh->image) && $bh->image !== ''){
				if(explode('.',$bh->image)[1] == 'pdf'){
					$proof .= '<a href="' .$bh->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
				}else{
					$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$bh->item.'" data-group="a" href="' .$bh->attachment() . '"><img src="' . $bh->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
				}
			}
		}
		
		if(substr($data->code,0,4) == 'PRP-' && count(explode('-',$data->code)) == 2){
			$prp = PurchaseRequestPayment::find(explode('-',$data->code)[1]);
			
			if($prp->image){
				if(explode('.',$prp->image)[1] == 'pdf'){
					$proof .= '<a href="' .$prp->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
				}else{
					$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$prp->item.'" data-group="a" href="' .$prp->attachment() . '"><img src="' . $prp->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
				}
			}
			
			if($prp->purchaseRequest()->exists()){
				if($prp->purchaseRequest->image){
					if(explode('.',$prp->purchaseRequest->image)[1] == 'pdf'){
						$proof .= '<a href="' .$prp->purchaseRequest->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$prp->purchaseRequest->item.'" data-group="a" href="' .$prp->purchaseRequest->attachment() . '"><img src="' . $prp->purchaseRequest->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}
		
		if($data->lookable_type == 'project_warehouses'){
			$pw = ProjectWarehouse::find($data->lookable_id);
			
			if(isset($pw)){
				if($pw->image){
					if(explode('.',$pw->image)[1] == 'pdf'){
						$proof .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}

		if($data->lookable_type == 'sample_warehouses'){
			$pw = SampleWarehouse::find($data->lookable_id);
			
			if(isset($pw)){
				if($pw->image){
					if(explode('.',$pw->image)[1] == 'pdf'){
						$proof .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}
		
		if($data->lookable_type == 'project_payments'){
			$pw = ProjectPayment::find($data->lookable_id);
			
			if($pw){
				if($pw->image){
					if(explode('.',$pw->image)[1] == 'pdf'){
						$proof .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}
		
		if($data->lookable_type == 'project_deliveries'){
			$pw = ProjectDelivery::find($data->lookable_id);
			
			if($pw){
				if($pw->image){
					if(explode('.',$pw->image)[1] == 'pdf'){
						$proof .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}
		
		if($data->lookable_type == 'project_pays'){
			$pw = ProjectPay::find($data->lookable_id);
			
			if($pw){
				if($pw->image){
					if(explode('.',$pw->image)[1] == 'pdf'){
						$proof .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}
		
		if($data->lookable_type == 'transfers'){
			$pw = Transfer::find($data->lookable_id);
			
			if($pw){
				if($pw->image){
					if(explode('.',$pw->image)[1] == 'pdf'){
						$proof .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}
		
		if($data->lookable_type == 'receivable_payments'){
			$pw = ReceivablePayment::find($data->lookable_id);
			
			if($pw){
				if($pw->image){
					if(explode('.',$pw->image)[1] == 'pdf'){
						$proof .= '<a href="' .$pw->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$pw->code.'" data-group="a" href="' .$pw->attachment() . '"><img src="' . $pw->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
			}
		}
		
		if($data->image){
			if(explode('.',$data->image)[1] == 'pdf'){
				$proof .= '<a href="' .$data->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
			}else{
				$proof .= '<a data-magnify="gallery" data-src="" data-caption="'.$data->code.'" data-group="a" href="' .$data->attachment() . '"><img src="' . $data->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
			}
		}
		

        foreach($data->cashBankDetail as $cbd) {
            $cash_bank_detail[] = [
                'coa_id'    	=> $cbd->coa_id,
                'coa_info'  	=> '[' . $cbd->coa->code . '] ' . $cbd->coa->name,
				'branch_id'		=> $cbd->branch,
				'branch_name'	=> $cbd->branch(),
                'type'   		=> $cbd->type,
                'nominal'     	=> number_format($cbd->nominal,2,',','.'),
                'note'       	=> $cbd->note,
				'checked'		=> $cbd->coa_id == $prcoa ? 'checked' : ''
            ];
        }

        return response()->json([
			'id'					=> $data->id,
			'user_id'				=> $data->user_id,
			'budgeting_project_id'	=> $data->budgeting_project_id ? $data->budgeting_project_id : '',
			'lookable_type'			=> $data->lookable_type ? $data->lookable_type : '',
			'lookable_id'			=> $data->lookable_id ? $data->lookable_id : '',
			'supplier_id'			=> $data->supplier_id,
			'supplier_name'			=> $data->supplier ? $data->supplier->name : '',
			'customer_id'			=> $data->customer_id,
			'customer_name'			=> $data->customer ? $data->customer->name : '',
			'no_nota'				=> $data->no_nota,
			'request_date'			=> $data->request_date,
			'due_date'				=> $data->due_date,
			'code'					=> $data->code,
			'date'					=> $data->date,
			'type'					=> $data->type,
			'description'			=> $data->description,
			'purchase_request_ref'	=> $data->purchase_request_ref,
            'cash_bank_detail' 		=> $cash_bank_detail,
			'proof'					=> $proof,
			'purchase_info'			=> $data->lookable_type == 'project_purchases' ? ProjectPurchase::find($data->lookable_id)->code : '',
			'project_info'			=> $data->lookable_type == 'projects' ? Project::find($data->lookable_id)->code : '',
			'purchase_request_info'	=> $data->purchase_request_ref ? PurchaseRequest::find($data->purchase_request_ref)->title : '',
        ]);
    }

    public function update(Request $request, $id)
    {

		// foreach($request->credit_checkbox as $key => $dd){

		// 	if($request->credit_checkbox[$key]){
		// 		echo '<pre>' . var_export("true", true) . '</pre>';
		// 	}else{
		// 		echo '<pre>' . var_export('false', true) . '</pre>';
		// 	}

		// 	echo '<pre>' . var_export($key, true) . '</pre>';
		// 	echo '<pre>' . var_export($dd, true) . '</pre>';
			
		// }


		// dd($request->coa_detail);
        $query      = CashBank::find($id);
		$validation = Validator::make($request->all(), [
            'code'           => ['required', Rule::unique('cash_banks', 'code')->ignore($id)],
            'coa_detail'	 => 'required',
            'type_detail' 	 => 'required',
            'nominal_detail' => 'required',
            'date'           => 'required',
            'type'           => 'required',
            'description'    => 'required'
        ], [
            'code.required'           => 'Code cannot be a empty.',
            'code.unique'             => 'Code already exists.',
            'coa_detail.required'     => 'Coa transaction cannot be a empty.',
            'type_detail.required' 	  => 'Type transaction cannot be a empty.',
            'nominal_detail.required' => 'Detail transaction cannot be a empty.',
            'date.required'           => 'Date cannot be empty.',
            'type.required'           => 'Please select a type.',
            'description.required'    => 'Description cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			// if(CheckCutOff::check($request->branch_detail[0],substr($request->date,0,7))){
			
				if($request->has('file')) {
					if(Storage::exists($query->image)) {
						Storage::delete($query->image);
					}

					$image = $request->file('file')->store('public/cashbank');
				} else {
					$image = $query->image;
				}
				
				if($request->project_id !== 'null'){
					$lookable_id = $request->project_id;
					$lookable_type = 'projects';
				}elseif($request->purchase_id !== 'null'){
					$lookable_id = $request->purchase_id;
					$lookable_type = 'project_purchases';
				}
				
				$query->update([
					'user_id'     			=> session('bo_id'),
					'lookable_id'			=> isset($lookable_id) ? $lookable_id : $query->lookable_id,
					'lookable_type'			=> isset($lookable_type) ? $lookable_type : $query->lookable_type,
					'supplier_id'			=> $request->supplier_id,
					'customer_id'			=> $request->customer_id,
					'no_nota'				=> $request->receipt,
					'request_date'			=> $request->request_date,
					'due_date'				=> $request->due_date,
					'image'					=> $image,
					'code'        			=> $request->code,
					'date'        			=> $request->date,
					'type'       			=> $request->type,
					'description' 			=> $request->description,
					'purchase_request_ref'	=> $request->purchase_request_id ? $request->purchase_request_id : NULL
				]);

				if($query) {
					CashBankDetail::where('cash_bank_id', $query->id)->delete();
					DB::table('journals')
						->where('journalable_type', 'cash_banks')
						->where('journalable_id', $query->id)
						->delete();
					
					$temp_branch = '';
					$temp_nominal = 0;
					$temp_coa = 0;
					
					$counthutang = 0;
					$counthutangkredit = 0;
					$counthutangkreditreal = 0;
					$counthutangdebit = 0;
					$countcredit = 1;
					
					foreach($request->coa_detail as $key => $dd) {
						
						if($dd == 332){
							$counthutang++;
						}
						
						// if($request->credit_checkbox[$key]){
						// 	$counthutangkredit += str_replace(',','.',str_replace('.','',$request->nominal_detail[$key]));
						// }
						
						if($request->type_detail[$key] == '1'){
							$counthutangdebit += str_replace(',','.',str_replace('.','',$request->nominal_detail[$key]));
						}
						
						if($request->type_detail[$key] == '2'){
							
							if($countcredit == 1){
								$temp_coa = $dd;
								$temp_branch = $request->branch_detail[$key];
							}
							
							$countcredit++;
						}
						
						$temp_nominal = str_replace(',','.',str_replace('.','',$request->nominal_detail[$key]));
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $query->id,
							'coa_id'       	=> $dd,
							'branch'		=> $request->branch_detail[$key],
							'type'       	=> $request->type_detail[$key],
							'nominal'      	=> str_replace(',','.',str_replace('.','',$request->nominal_detail[$key])),
							'note'         	=> $request->note_detail[$key]
						]);

						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $query->id,
							'coa_id'           => $dd,
							'branch'		   => $request->branch_detail[$key],
							'type'	           => $request->type_detail[$key],
							'nominal'          => str_replace(',','.',str_replace('.','',$request->nominal_detail[$key])),
							'created_at'       => date('Y-m-d', strtotime($query->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}

					$checkboxes = $request->debit_checkbox ?: $request->credit_checkbox;

					if (count($checkboxes) > 0) {
						foreach ($checkboxes as $val) {
							$counthutangkreditreal += str_replace(',', '.', str_replace('.', '', $val));
						}
					}
					
					
					if($query->lookable_type == 'purchase_requests'){
						if(count($request->coa_detail) > 2){
							
							$updatepr = PurchaseRequest::find($query->lookable_id)->update([
								'coa_id'			=> $temp_coa,
								'branch'			=> $temp_branch,
								'total_nominal'		=> $counthutangkreditreal > 0 ? $counthutangkreditreal : $temp_nominal,
								'date'				=> $request->date
								//'status'			=> 'DONE'
							]);
							
						}else{
							$updatepr = PurchaseRequest::find($query->lookable_id)->update([
								'coa_id'			=> $temp_coa,
								'branch'			=> $temp_branch,
								'total_nominal'		=> $temp_nominal,
								'date'				=> $request->date
							]);
						}
					}
					
					if(count(explode('-',$query->code)) == 2 && str_contains($query->code,'PRP')){
						$updateprp = PurchaseRequestPayment::find(explode('-',$query->code)[1])->update([
							//'nominal' => $counthutangdebit,
							'date_paid'	=> $request->date
						]);
					}

					activity()
						->performedOn(new CashBank())
						->causedBy(session('bo_id'))
						->log('Change the accounting cash bank data');

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
			// }else{
			// 	$response = [
			// 		'status'  => 503,
			// 		'message' => 'You cannot add/edit. The journal for this month was already closed.'
			// 	];
			// }
        }

        return response()->json($response);
    }
	
	public function print($id)
    {
        $data = [
            'title'     => 'Cash & Bank Print',
            'cash_bank' => CashBank::find($id),
            'content'   => 'admin.finance.cash_bank_print'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function destroy(Request $request) 
    {
        $query = CashBank::find($request->id);
		
		// if(CheckCutOff::check($query->cashBankDetail()->first()->branch,substr($query->date,0,7))){
		
			$query->deleteFile();
			if($query->delete()) {
				CashBankDetail::where('cash_bank_id',$request->id)->delete();
				
				DB::table('journals')
					->where('journalable_type', 'cash_banks')
					->where('journalable_id', $query->id)
					->delete();
				
				foreach(BalanceHistory::where('cash_bank_reference',$query->id)->get() as $bh){
					$bh->update([
						'cash_bank_reference' => NULL
					]);
					/* $bh->deleteFile();
					$bh->delete(); */
				}
				
				if(count(explode('-',$query->code)) > 0){
					if(explode('-',$query->code)[0] == 'PR'){
						$approval = Approval::where('approvalable_type','purchase_requests')->where('approvalable_id',explode('-',$query->code)[1])->first();
						if($approval){
							$approval->update([
								'approved_by'	=> NULL,
								'seen'			=> 0
							]);
						}else{
							Approval::create([
								'user_id'				=> 7,
								'approvalable_type'		=> 'purchase_requests',
								'approvalable_id'		=> intval(explode('-',$query->code)[1]),
								'column_name'			=> 'approved_by',
								'reference'				=> session('bo_id'),
								'seen'					=> 0,
								'status'				=> 1
							]);
						}
						
						$pr = PurchaseRequest::find(intval(explode('-',$query->code)[1]));
						
						if($pr){
							$pr->update([
								'status'	=> 'PEND'
							]);
						}
					}
				}
						
				activity()
					->performedOn(new CashBank())
					->causedBy(session('bo_id'))
					->log('Delete the accounting cash bank data');

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
			
		// }else{
		// 	$response = [
		// 		'status'  => 503,
		// 		'message' => 'You cannot add/edit. The journal for this month was already closed.'
		// 	];
		// }

        return response()->json($response);
    }
	
	public function getProject(Request $request){
		$reference = $request->reference;
		
		if($reference == '1'){
			$data = ProjectSale::where('project_id',$request->id)->get();
		}elseif($reference == '2'){
			$data = ProjectPurchase::where('project_id',$request->id)->get();
		}
		
		$result = [];
		
		foreach($data as $row){
			$result[] = [
				'id'	=> $row->id,
				'code' 	=> $row->code,
				'total'	=> $row->getTotal()
			];
		}
		
		return response()->json($result);
	}
	
	public function createPrf(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'login_android'	 => 'required',
            'date_android' 	 => 'required',
            'item_android' 	 => 'required',
            'total_android'  => 'required'
        ], [
            'login_android.required'     	=> 'User cannot be a empty.',
            'date_android.required' 	  	=> 'Date cannot be a empty.',
            'item_android.required' 		=> 'Item cannot be a empty.',
            'total_android.required'        => 'Total cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = PurchaseRequest::create([
                'tgl'	     			=> $request->date_android,
				'bulan'				 	=> date('F',strtotime($request->date_android)),
				'tahun'					=> date('Y',strtotime($request->date_android)),
				'nama'					=> $request->login_android,
				'item'					=> $request->item_android,
				'total'					=> str_replace(',','.',str_replace('.','',$request->total_android)),
				'status'				=> 'PEND'
            ]);

            if($query) {
                activity()
                    ->performedOn(new PurchaseRequest())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add purchase request via TJS');

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
	
	public function getCode(Request $request){
		$val = $request->val;
		
		$query = CashBank::selectRaw("RIGHT(code, 3) as code")
			->where('code','like',"$val%")
            ->orderByDesc('code')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '001';
        }

        $code = str_pad($number, 3, 0, STR_PAD_LEFT);
		
		$result = [
			'code' 	=> $val."-".$code,
		];
		
		return response()->json($result);
	}
}
