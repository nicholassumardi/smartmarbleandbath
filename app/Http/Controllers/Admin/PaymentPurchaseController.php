<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coa;
use App\Models\BalanceHistory;
use App\Models\PurchaseRequestPayment;
use App\Models\PurchaseRequestMainPayment;
use App\Models\CashBank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentPurchaseController extends Controller {

    public function index()
    {
        $data = [
			'coa'     			=> Coa::where('status', 1)->oldest('code')->get(),
            'title'   			=> 'Purchase Request',
            'content' 			=> 'admin.finance.payment_purchase'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
			'detail',
			'id',
            'user_id',
			'purchase_request_id',
            'date_paid',
            'branch',
            'coa_id',
			'nominal',
			'note',
			'ref',
			'image'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = PurchaseRequestPayment::count();
        
        $query_data = PurchaseRequestPayment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date_paid', 'like', "%$search%")
                            ->orWhere('nominal', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('purchaseRequest', function($query) use ($search) {
						$query->where('item','like',"%$search%");
					})->orWhereHas('coa', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }
				
				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date_paid', '>=', $request->start_date)
                        ->whereDate('date_paid', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date_paid', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date_paid', $request->finish_date);
                }
				
				if($request->transfer){
					if($request->transfer == '1'){
						$query->whereRaw('id IN (SELECT SUBSTRING_INDEX(code,"-",-1) FROM cash_banks WHERE code LIKE "PRP-%")');
					}elseif($request->transfer == '2'){
						$query->whereRaw('id NOT IN (SELECT SUBSTRING_INDEX(code,"-",-1) FROM cash_banks WHERE code LIKE "PRP-%")');
					}
				}
			
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = PurchaseRequestPayment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date_paid', 'like', "%$search%")
                            ->orWhere('nominal', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('purchaseRequest', function($query) use ($search) {
						$query->where('item','like',"%$search%");
					})->orWhereHas('coa', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }
				
				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date_paid', '>=', $request->start_date)
                        ->whereDate('date_paid', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date_paid', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date_paid', $request->finish_date);
                }
				
				if($request->transfer){
					if($request->transfer == '1'){
						$query->whereRaw('id IN (SELECT SUBSTRING_INDEX(code,"-",-1) FROM cash_banks WHERE code LIKE "PRP-%")');
					}elseif($request->transfer == '2'){
						$query->whereRaw('id NOT IN (SELECT SUBSTRING_INDEX(code,"-",-1) FROM cash_banks WHERE code LIKE "PRP-%")');
					}
				}
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
			
            foreach($query_data as $val) {
				$btn = '';
				$btnedit = '';
				$btnbalance = '';
			
				$cb = CashBank::where('code','PRP-'.$val->id)->first();
				
				if($val->purchaseRequestMainPayment()->exists()){
					$cb = CashBank::where('code','PRMP-'.$val->purchaseRequestMainPayment->id)->first();
				}
				
				if($cb){
					$btn = '<button class="btn btn-success btn-sm btn-icon rounded-round" data-popup="tooltip" title="Added to Journal & Cash Banks"><i class="icon-check"></i></button>';
					$btnedit = '';
					
					$databalance = BalanceHistory::where('cash_bank_reference',$cb->id)->get();
					
					if(count($databalance) > 0){
						$btnbalance = '<button class="btn btn-success btn-sm btn-icon rounded-round" data-popup="tooltip" title="Added to Balance Cash & Banks"><i class="icon-check"></i></button>';
					}else{
						$btnbalance = '<button type="button" class="btn-sm btn btn-primary btn-icon" onclick="addBalanceCashBank('.$val->id.','.$cb->id.')"><i class="icon-floppy-disk" data-popup="tooltip" title="Add to Balance Cash & Bank"></i></button>';
					}
				}else{
					if(in_array(1, session('bo_role')) || in_array(4, session('bo_role'))){
						$btn = '<a href="javascript:void(0);" class="btn btn-info btn-pindah btn-sm" data-nominal="'.$val->nominal.'" data-id="'.$val->id.'" data-tgl="'.$val->date_paid.'" data-item="'.$val->purchaseRequest->item.'" data-branch="'.$val->branch.'" data-coa="'.($val->coa_id ? $val->coa_id : '').'" data-coaname="'.($val->coa_id ? $val->coa->name : '').'" data-branchname="'.($val->branch ? $val->branch() : '').'" data-popup="tooltip" title="Add to Journal & Cash Bank"><i class="icon-task"></i></a>';
						$btnedit = '<button type="button" class="btn btn-sm bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyPay(' . $val->id . ')"><i class="icon-trash-alt"></i></button>';
					}
				}
				
				if($val->purchaseRequest->image){
					if(explode('.',$val->purchaseRequest->image)[1] == 'pdf'){
						$photoref = '<a href="' .$val->purchaseRequest->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photoref = '<a data-magnify="gallery" data-src="" data-caption="'.$val->purchaseRequest->item.'" data-group="a" href="' .$val->purchaseRequest->attachment() . '"><img src="' . $val->purchaseRequest->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}else{
					$photoref = '<span class="badge badge-danger">Empty</span>';
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
				
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
					$val->id,
                    $val->user->name,
					(strlen(strtoupper($val->purchaseRequest->item)) > 25 ? $val->purchaseRequest->title.' - '.substr(strtoupper($val->purchaseRequest->item),0,25) : $val->purchaseRequest->title.' - '.strtoupper($val->purchaseRequest->item)).'<a href="#collapse-link'.$val->id.'" class="font-weight-semibold" data-toggle="collapse"><b style="font-size:25px;"><i class="icon-info22"></i></b></a><div class="collapse" id="collapse-link'.$val->id.'"><div class="mt-3">'.strtoupper($val->purchaseRequest->item).'</div></div>',
					date('d M Y',strtotime($val->date_paid)),
					$val->branch(),
					$val->coa ? $val->coa->name : '<span class="badge badge-danger">Empty</span>',
					number_format($val->nominal,2,',','.'),
					$val->note,
					$photoref,
					$photo,
					$btn,
					$btnbalance,
					$btnedit
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
	
	public function datatableMulti(Request $request) 
    {
        $column = [
			'detail',
			'id',
            'user_id',
            'date_paid',
            'branch',
            'coa_id',
			'nominal',
			'note',
			'image',
			'code',
			'due_date'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = PurchaseRequestMainPayment::count();
        
        $query_data = PurchaseRequestMainPayment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date_paid', 'like', "%$search%")
                            ->orWhere('nominal', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('purchaseRequestPayment', function($query) use ($search) {
						$query->whereHas('purchaseRequest',function($query) use ($search){
							$query->where('item','like',"%$search%");
						});
					})->orWhereHas('coa', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = PurchaseRequestMainPayment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date_paid', 'like', "%$search%")
                            ->orWhere('nominal', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhere('id', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('purchaseRequestPayment', function($query) use ($search) {
						$query->whereHas('purchaseRequest',function($query) use ($search){
							$query->where('item','like',"%$search%");
						});
					})->orWhereHas('coa', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
			
            foreach($query_data as $val) {
				
				if($val->image){
					if(explode('.',$val->image)[1] == 'pdf'){
						$photo = '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$val->item.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}else{
					$photo = '<span class="badge badge-danger">Empty</span>';
				}
				
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
					$val->id,
                    $val->user->name,
					date('d M Y',strtotime($val->date_paid)),
					$val->branch(),
					$val->coa ? $val->coa->name : '<span class="badge badge-danger">Empty</span>',
					number_format($val->nominal,2,',','.'),
					$val->note,
					$photo,
					$val->code,
					$val->due_date,
					'
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="alert(`Coming soon!`)"><i class="icon-trash-alt"></i></button>
                    ',
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
	
	public function deletePayment(Request $request)
    {
		$query = PurchaseRequestPayment::find($request->id);
		$query->deleteFile();
		$query->delete();
		
		$cb = CashBank::where('lookable_id',$request->id)->where('lookable_type','purchase_request_payments')->get();
		
		foreach($cb as $row){
			$row->deleteDetail();
			BalanceHistory::where('cash_bank_reference',$row-id)->delete();
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

        return response()->json($response);
    }
	
	public function addBalance(Request $request)
    {
		$prp = PurchaseRequestPayment::find($request->idprp);
		$cb = CashBank::find($request->idcb);
		
		BalanceHistory::where('cash_bank_reference',$cb->id)->delete();
		
		$data = BalanceHistory::create([
			'user_id' 					=> session('bo_id'),
			'nominal'					=> $prp->nominal,
			'type'						=> 'OUT',
			'cash_or_bank'				=> in_array($prp->coa_id,array(2,5,345)) ? 'CASH' : 'BANK',
			'coa_id'					=> $prp->coa_id,
			'cash_bank_reference'		=> $cb->id,
			'branch'					=> $prp->branch,
			'note'						=> $prp->purchaseRequest->item.' '.$prp->note,
			'date'						=> $prp->date_paid
		]);
		
		if($data){
			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
		}else{
			$response = [
				'status'  => 500,
				'message' => 'Data failed to add.'
			];
		}

        return response()->json($response);
    }
	
	public function rowDetail(Request $request)
    {
        $data   = PurchaseRequestMainPayment::find($request->id);
		
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Bill To</th>
							<th>Title</th>
							<th>Item</th>
							<th>Total</th>
							<th>Paid</th>
						</tr>
					</thead>
					<tbody>';

        foreach($data->purchaseRequestPayment as $d) {
			$string .= '
				<tr>
					<td>'.$d->purchaseRequest->bill_to.'</td>
					<td>'.$d->purchaseRequest->title.'</td>
					<td>'.$d->purchaseRequest->item.'</td>
					<td class="text-right">'.number_format($d->purchaseRequest->total_nominal,2,',','.').'</td>
					<td class="text-right">'.number_format($d->nominal,2,',','.').'</td>
				</tr>
			';
        }

        $string .= '</tbody></table>';
		
        return response()->json($string);
    }
}
