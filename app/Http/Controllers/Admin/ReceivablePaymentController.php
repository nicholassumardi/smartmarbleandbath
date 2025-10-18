<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coa;
use App\Models\Approval;
use App\Models\BalanceHistory;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\ReceivablePayment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReceivablePaymentController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Other Receivable Payment',
			'coa'     => Coa::where('status', 1)->oldest('code')->get(),
            'content' => 'admin.delivery_order.receivable_payment'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

	public function financeIndex()
    {
        $data = [
            'title'   => 'Other Receivable Payment',
			'coa'     => Coa::where('status', 1)->oldest('code')->get(),
            'content' => 'admin.finance.receivable_payment'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
			'customer_id',
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
            })
			->whereHas('cashBankDetail', function($query){
				$query->where('coa_id', 27)
				->where('type', '1');
			})
			->whereNotNull('customer_id')
			->where('customer_id','<>','0')
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
            })
			->whereHas('cashBankDetail', function($query){
				$query->where('coa_id', 27)
				->where('type', '1');
			})
			->whereNotNull('customer_id')
			->where('customer_id','<>','0')
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {

				$total_return = 0;

				if(isset($val->lookable->projectSaleReturn)) {
					if(count($val->lookable->projectSaleReturn) > 0){
						foreach ($val->lookable->projectSaleReturn as $row) {
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
				}
				
				$nominal = $val->cashBankDetail->where('type','1')->where('coa_id',27)->sum('nominal') - $total_return;
				
				

				$totalpaid = 0;
				
				foreach(ReceivablePayment::where('cash_bank_id',$val->id)->get() as $row){
					$totalpaid += $row->nominal;
				}
				
				$percent = $totalpaid ? round(($totalpaid / $nominal) * 100,0) : 0;
				
				if($percent >= 100){
					$progress = '
						<div class="progress" style="height:0.875rem;">
							<div class="progress-bar progress-bar-striped bg-teal" style="width:100%">
								<span class="font-weight-bold text-uppercase">
									<span style="font-size:13px;">' . $percent . '%</span>
								</span>
							</div>
						</div>
					';
				}else{
					$progress = '
						<div class="progress" style="height:0.875rem;">
							<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
								<span class="font-weight-bold text-uppercase">
									<span style="font-size:13px;">' . $percent . '%</span>
								</span>
							</div>
						</div>
					';
				}
				
				$response['data'][] = [
                    $nomor,
                    $val->customer->name,
                    number_format($nominal, 2, ',', '.'),
                    date('d M Y', strtotime($val->date)),
                    $val->description,
					$progress,
					'
                        <button type="button" class="btn bg-info btn-sm btn-pay" data-popup="tooltip" title="Pay" data-cb="'.$val->id.'" data-nominal="'.$nominal.'" data-item="'.$val->description.'" data-nominalacc="'.number_format($nominal, 2, ",", ".").'"><i class="icon-cash"></i></button>
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
	
	public function financeDatatable(Request $request) 
    {
        $column = [
            'id',
			'customer_id',
            'total',
            'date',
            'description'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ReceivablePayment::count();
        
        $query_data = ReceivablePayment::where(function($query) use ($search) {
                if($search) {
					$query->WhereHas('customer', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						})
						->orWhereHas('coa', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						})
						->orWhere('date', 'like', "%$search%")
						->orWhere('nominal', 'like', "%$search%")
						->orWhere('note', 'like', "%$search%");
                }     
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ReceivablePayment::where(function($query) use ($search) {
                if($search) {
					$query->WhereHas('customer', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						})
						->orWhereHas('coa', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						})
						->orWhere('date', 'like', "%$search%")
						->orWhere('nominal', 'like', "%$search%")
						->orWhere('note', 'like', "%$search%");
                }     
            })
            ->count();

        $response['data'] = [];
		
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$cek = CashBank::where('lookable_type','receivable_payments')->where('lookable_id',$val->id)->first();
				
				$button = '';
				$button2 = '<span class="badge badge-warning">Waiting</span>';
				
				if($cek){
					$button = '<span class="badge badge-success">Transferred</span>';
					
					$cekbalance = BalanceHistory::find(explode('-',$cek->code)[1]);
					
					if($cekbalance){
						$button2 = '<span class="badge badge-success">Available</span>';
					}
				}else{
					$button = '
                        <a href="javascript:void(0);" class="btn btn-info btn-pindah btn-sm" data-nominal="'.$val->nominal.'" data-id="'.$val->id.'" data-tgl="'.$val->date.'" data-note="'.$val->note.'" data-coa="'.($val->coa_id ? $val->coa_id : '').'" data-coaname="'.($val->coa_id ? $val->coa->name : '').'"><i class="icon-task"></i></a>
					';
				}
				
				$response['data'][] = [
                    $nomor,
                    $val->customer->name,
                    number_format($val->nominal, 2, ',', '.'),
                    date('d F Y', strtotime($val->date)),
                    $val->note,
					$button,
					$button2
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

	public function addPayment(Request $request)
    {
		$validation = Validator::make($request->all(), [
			'temppay'   		=> 'required',
			'pay_date' 			=> 'required',
			'pay_coa' 			=> 'required',
			'pay_nominal' 		=> 'required',
			'pay_file' 			=> 'required'
		], [
			'temppay.required'   		=> 'Purchase Request Information cannot be empty.',
			'pay_date.required'   		=> 'Date cannot be empty.',
			'pay_coa.required'    		=> 'Source cannot be empty.',
			'pay_nominal.required'		=> 'Nominal cannot be empty.',
			'pay_file.required'			=> 'Proof payment cannot be empty.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$cek = CashBank::find($request->temppay);
			
			if($cek){
				$rp = ReceivablePayment::create([
					'cash_bank_id' 	=> $request->temppay,
					'customer_id' 	=> $cek->customer_id,
					'coa_id'		=> $request->pay_coa,
					'branch'		=> $request->branch,
					'date'			=> $request->pay_date,
					'note'			=> $request->pay_note,
					'nominal'		=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
					'image'			=> $request->file('pay_file') ? $request->file('pay_file')->store('public/receivable') : ''
				]);
				
				$image = '';
					
				if($rp->image){
					$image = explode('.',$rp->image)[1] == 'pdf' ? '<a href="' .$rp->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$rp->note.'" data-group="a" href="' .$rp->attachment() . '"><img src="' . $rp->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
				}
				
				if($rp){
					#send approval
					$roleapproval = array('4');
					Approval::sendApproval($roleapproval,'receivable_payments',$rp->id,'approved_by',session('bo_id'));
					$roleapproval = array('3');
					Approval::sendApproval($roleapproval,'receivable_payments',$rp->id,'checked_by',session('bo_id'));
					#end approval
				}
				
				$result = [
					'id'		=> $rp->id,
					'date'		=> $rp->date,
					'source'	=> $rp->coa ? $rp->coa->name : '',
					'branch'	=> $rp->branch ? $rp->branch : '',
					'nominal'	=> number_format($rp->nominal,'2',',','.'),
					'proof'		=> $image,
					'note'		=> $rp->note ? $rp->note : '-'		
				];
				
				activity()
					->performedOn(new ReceivablePayment())
					->causedBy(session('bo_id'))
					->withProperties($rp)
					->log('Add receivable payment by user '.session('bo_name'));

				$response = [
					'status'  	=> 200,
					'message' 	=> 'Data added successfully.',
					'result'	=> $result,
					'count'		=> 1
				];
			}else{
				$response = [
					'status'  => 500,
					'message' => 'Data not found.'
				];
			}
        }

        return response()->json($response);
    }
	
	public function getPayment(Request $request)
	{
		$data = ReceivablePayment::where('cash_bank_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$image = '';
				
			if($row->image){
				$image = explode('.',$row->image)[1] == 'pdf' ? '<a href="' .$row->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="'.$row->item.'" data-group="a" href="' .$row->attachment() . '"><img src="' . $row->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
			}
			
			$cek = CashBank::where('lookable_type','receivable_payments')->where('lookable_id',$row->id)->first();
			
			$result[] = [
				'id'		=> $row->id,
				'date'		=> $row->date,
				'source'	=> $row->coa ? $row->coa->name : '',
				'nominal'	=> number_format($row->nominal,'2',',','.'),
				'proof'		=> $image,
				'note'		=> $row->note ? $row->note : '-',
				'check'		=> $cek ? '1' : '0'
			];
		}
		
		return response()->json($result);
	}
	
	public function deletePayment(Request $request)
    {
		/* $cek = CashBank::where('lookable_id',$request->id)->where('lookable_type','receivable_payments')->get();
		
		if(count($cek) > 0){
			$response = [
				'status'  => 500,
				'message' => 'Data already in Cash & Bank. Please contact Accounting.'
			];
		}else{ */
			$query = ReceivablePayment::find($request->id);
			if($query->image){
				$query->deleteFile();
			}
			$query->delete();
			
			$cb = CashBank::where('lookable_id',$request->id)->where('lookable_type','receivable_payments')->get();
			
			foreach($cb as $row){
				$row->deleteDetail();
				BalanceHistory::find(explode('-',$row->code)[1])->delete();
				$row->delete();
			}
			
			$countpay = ReceivablePayment::where('cash_bank_id',$query->cash_bank_id)->count();
			
			if($query) {
				activity()
					->performedOn(new ReceivablePayment())
					->causedBy(session('bo_id'))
					->log('Delete the payment of receivable data');

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
		//}

        return response()->json($response);
    }
}
