<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\PaymentRequest;
use App\Models\PurchaseRequest;
use App\Models\Journal;
use App\Models\CashBank;
use App\Models\BalanceHistory;
use App\Models\CashBankDetail;
use App\Models\PaymentRequestDetail;
use App\Models\PaymentRequestSource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentRequestController extends Controller {

    public function index()
    {
		
        $data = [
			'coa'     => Coa::where('status', 1)->oldest('code')->get(),
            'title'   => 'Payment Request',
            'content' => 'admin.finance.payment_request'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
			'detail',
			'code',
            'user_id',
			'checked_by',
            'approved_by',
            'date',
            'note'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = PaymentRequest::count();
        
        $query_data = PaymentRequest::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
                            ->orWhere('title', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }

				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date', '>=', $request->start_date)
                        ->whereDate('date', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }
				
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = PaymentRequest::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
                            ->orWhere('title', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					});
                }

				if($request->start_date && $request->finish_date) {
                    $query->whereDate('date', '>=', $request->start_date)
                        ->whereDate('date', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('date', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('date', $request->finish_date);
                }
				
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
			
            foreach($query_data as $val) {
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
					$val->code,
                    $val->user->name,
					$val->check ? '<span class="badge badge-success" style="font-size:12px;">'.$val->check->name.'</span>' : '<span class="badge badge-danger" style="font-size:12px;">Empty</span>',
                    $val->approve ? '<span class="badge badge-success" style="font-size:12px;">'.$val->approve->name.'</span>' : '<span class="badge badge-danger" style="font-size:12px;">Empty</span>',
                    date('d M Y',strtotime($val->date)),
					$val->note,
                    '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
	
	public function create(Request $request)
	{
		$validation = Validator::make($request->all(), [
			//'title' 	=> 'required',
			'date' 		=> 'required',
			'note' 		=> 'required'
		], [
			//'title.required'  	=> 'Title cannot empty.',
			'date.required' 	=> 'Date cannot empty.',
			'note.required' 	=> 'Note cannot empty.'
		]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$pr = PaymentRequest::create([
				'code' 				=> PaymentRequest::generateCode(),
				'user_id'			=> session('bo_id'),
				'date'				=> $request->date,
				//'title'				=> $request->title,
				'note'				=> $request->note
			]);
			
			foreach($request->purchase_arr as $key => $row){
				PaymentRequestDetail::create([
					'payment_request_id' 	=> $pr->id,
					'purchase_request_id'	=> $row,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal_arr[$key]))
				]);
			}
			
			foreach($request->coa_arr as $key => $row){
				PaymentRequestSource::create([
					'payment_request_id' 	=> $pr->id,
					'coa_id'				=> $row,
					'branch'				=> $request->branch_source_arr[$key],
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal_source_arr[$key])),
					'code'					=> $request->code_arr[$key],
					'due_date'				=> $request->due_date_arr[$key]
				]);
			}
			
			#send approval
			$roleapproval = array('1');
			Approval::sendApproval($roleapproval,'payment_requests',$pr->id,'approved_by',session('bo_id'));
			$roleapproval = array('4');
			Approval::sendApproval($roleapproval,'payment_requests',$pr->id,'checked_by',session('bo_id'));
			#end approval
			
			activity()
				->performedOn(new PaymentRequest())
				->causedBy(session('bo_id'))
				->log('Add new payment request.');

			$response = [
				'status'  => 200,
				'message' => 'Data updated successfully.'
			];
			
		}
		
		return response()->json($response);
	}
	
	public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
			//'title' 	=> 'required',
			'date' 		=> 'required',
			'note' 		=> 'required'
		], [
			//'title.required'  	=> 'Title cannot empty.',
			'date.required' 	=> 'Date cannot empty.',
			'note.required' 	=> 'Note cannot empty.'
		]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			$pr = PaymentRequest::find($id);
			
			$pr->update([
				'user_id'	=> session('bo_id'),
				'date'		=> $request->date,
				'note'		=> $request->note
			]);
			
			$pr->paymentDetail()->delete();
			$pr->paymentSource()->delete();
			
			foreach($request->purchase_arr as $key => $row){
				PaymentRequestDetail::create([
					'payment_request_id' 	=> $pr->id,
					'purchase_request_id'	=> $row,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal_arr[$key]))
				]);
			}
			
			foreach($request->coa_arr as $key => $row){
				PaymentRequestSource::create([
					'payment_request_id' 	=> $pr->id,
					'coa_id'				=> $row,
					'branch'				=> $request->branch_source_arr[$key],
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal_source_arr[$key])),
					'code'					=> $request->code_arr[$key],
					'due_date'				=> $request->due_date_arr[$key]
				]);
			}
			
			activity()
				->performedOn(new PaymentRequest())
				->causedBy(session('bo_id'))
				->log('Change existing payment request.');

			$response = [
				'status'  => 200,
				'message' => 'Data updated successfully.'
			];
			
		}
		
		return response()->json($response);
	}
	
	public function rowDetail(Request $request)
    {
        $data   = PaymentRequest::find($request->id);
        $string = '<div class="row">';

		$string .= '
			<div class="col-md-4">
				<dl class="row mb-0">
					<dd class="col-sm-3">Note</dd>
					<dt class="col-sm-9">'.$data->note.'</dt>
				</dl>
			</div>
		';
		
		$string .= '
			<div class="col-md-12">
				<h5 class="card-title text-center"><b>List of All Purchase Requests</b></h5>
				<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>Purchase Request</th>
							<th width="15%">Nominal</th>
						</tr>
					</thead>
					<tbody>
		';
		
		$no = 1;
		$totalallrequest = 0;
		
		foreach($data->paymentDetail as $row){
			$string .= '
				<tr>
					<td>'.$no.'</td>
					<td>'.ucwords($row->purchaseRequest->item).'</td>
					<td class="text-right">IDR '.number_format($row->nominal,2,',','.').'</td>
				</tr>
			';
			
			$totalallrequest += $row->nominal;
			
			$no++;
		}
					
		$string .= '	<tr>
							<td colspan="2" class="text-right">Total</td>
							<td class="text-right">'.number_format($totalallrequest,2,',','.').'</td>
						</tr>
					</tbody>
				</table>
			</div>
		';
		
		$string .= '
			<div class="col-md-12">
				<h5 class="card-title text-center"><b>List of All Source</b></h5>
				<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>Cash & Bank From</th>
							<th width="15%">Branch</th>
							<th width="15%">BG Number</th>
							<th width="15%">Due Date</th>
							<th width="15%">Nominal</th>
						</tr>
					</thead>
					<tbody>
		';
		
		$no = 1;
		
		$totalallsource = 0;
		
		foreach($data->paymentSource as $key => $row){
			$string .= '
				<tr>
					<td>'.$no.'</td>
					<td>'.$row->coa->name.'</td>
					<td class="text-center">'.$row->branch().'</td>
					<td class="text-center">'.($row->code ? $row->code : '-').'</td>
					<td class="text-center">'.($row->due_date ? $row->due_date : '-').'</td>
					<td class="text-right">IDR '.number_format($row->nominal,2,',','.').'</td>
				</tr>
			';
			
			$totalallsource += $row->nominal;
			
			$no++;
		}
					
		$string .= '
						<tr>
							<td colspan="5" class="text-right">Total</td>
							<td class="text-right">'.number_format($totalallsource,2,',','.').'</td>
						</tr>
					</tbody>
				</table>
			</div>
		';

        $string .= '</div>';
		
        return response()->json($string);
    }
	
	public function show(Request $request)
    {
        $data = PaymentRequest::find($request->id);
		
		if($data->approved_by){
			/* return response()->json([
				'error' 	=> 422
			]); */
		}
		
		$detailrequest = [];
		
		foreach($data->paymentDetail as $row){
			$detailrequest[] = [
				'id'			=> $row->purchase_request_id,
				'item' 			=> $row->purchaseRequest->date.'-'.$row->purchaseRequest->item.'-'.$row->purchaseRequest->rev_nominal,
				'nominal'		=> $row->purchaseRequest->rev_nominal,
				/* 'coa_id'		=> $row->coa_id,
				'coa_name'		=> $row->coa->name,
				'branch'		=> $row->branch,
				'branchname'	=> $row->branch(), */
				'nominal'		=> $row->nominal,
				'nominalview'	=> number_format($row->nominal,0,',','.')
			];
		}

		foreach($data->paymentSource as $row){
			$detailsource[] = [
				'id'			=> $row->coa_id,
				'name'			=> $row->coa->name,
				'branch'		=> $row->branch,
				'branchname'	=> $row->branch(),
				'nominal'		=> $row->nominal,
				'nominalview'	=> number_format($row->nominal,0,',','.'),
				'code'			=> $row->code == NULL ? '' : $row->code,
				'due_date'		=> $row->due_date == NULL ? '' : $row->due_date
			];
		}
		
		$data['detailrequest'] = $detailrequest;
		$data['detailsource'] = $detailsource;
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = PaymentRequest::find($request->id);
		
		if($query->approved_by){
			/* return response()->json([
				'error' 	=> 422,
				'message'	=> 'Sorry this Payment Request has been approved. Contact developer.'
			]); */
		}
		
		$query->paymentDetail()->delete();
		foreach($query->paymentSource as $rowdel){
			$rowdel->deleteFile();
		}
		$query->paymentSource()->delete();
		
		$query->delete();
		
		Approval::where('approvalable_type','payment_requests')->where('approvalable_id',$request->id)->where('column_name','approved_by')->delete();
		
        if($query) {
            activity()
                ->performedOn(new PaymentRequest())
                ->causedBy(session('bo_id'))
                ->log('Delete the Payment request data');

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
	
	public function uploadProof(Request $request){
		
		$id = $request->id;
		$idpayment = $request->idpayment;
		
		$query = PurchaseRequest::find($id);
		$cek = PaymentRequestDetail::find($idpayment);
            
		if($request->has('file')) {
			if(Storage::exists($query->image_paid)) {
				Storage::delete($query->image_paid);
			}

			$image = $request->file('file')->store('public/purchase');
		} else {
			$image = $query->image_paid;
		}
		
		if($query->link_type == 'project_purchases'){
			$response = [
                'status'  => 400,
                'message' => 'This Purchase request must be completed via Purchase Request Form.'
            ];
		}else{
			
			$query->update([
				'rev_nominal'			=> str_replace(',','.',str_replace('.','',$cek->nominal)),
				'image_paid'			=> $image,
				'branch'				=> $cek->branch,
				'coa_id'				=> $cek->coa_id,
				'status'				=> 'DONE'
			]);
			
			/* $cekcb = CashBank::where('lookable_type','purchase_requests')->where('lookable_id',$request->id)->first();
			
			if($cekcb){
				$debetcb = 332;
								
				$kreditcb = $cek->coa_id;
				
				$cb = CashBank::create([
					'user_id'     			=> session('bo_id'),
					'lookable_id'			=> $query->id,
					'lookable_type'			=> 'purchase_requests',
					'supplier_id'			=> $cekcb->supplier_id,
					'request_date'			=> $cek->paymentRequest->date,
					'due_date'				=> $cek->due_date,
					'code'        			=> 'PRF-'.$query->id,
					'date'        			=> $cek->paymentRequest->date,
					'type'        			=> '5',
					'description' 			=> $query->item
				]);
				
				if($cb){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $debetcb,
						'branch'		=> $query->branch,
						'type'       	=> '1',
						'nominal'      	=> str_replace(',','.',str_replace('.','',$cek->nominal)),
						'note'         	=> $query->item
					]);
					
					Journal::insert([
						'date_transaction' => $query->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $debetcb,
						'branch'		   => $query->branch,
						'type'	           => '1',
						'nominal'          => str_replace(',','.',str_replace('.','',$cek->nominal)),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
					
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $kreditcb,
						'branch'		=> $query->branch,
						'type'       	=> '2',
						'nominal'      	=> str_replace(',','.',str_replace('.','',$cek->nominal)),
						'note'         	=> $query->item
					]);

					Journal::insert([
						'date_transaction' => $query->date,
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $kreditcb,
						'branch'		   => $query->branch,
						'type'	           => '2',
						'nominal'          => str_replace(',','.',str_replace('.','',$cek->nominal)),
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
			} */
			
			$response = [
                'status'  => 200,
                'message' => 'Uploaded'
            ];
			
		}
		
		return response()->json($response);
		
	}
	
	
	public function uploadProofSource(Request $request){
		
		$idsource = $request->idsource;
		
		$cek = PaymentRequestSource::find($idsource);
            
		if($request->has('file')) {
			$image = $request->file('file')->store('public/payment');
		}
		
		$cek->update([
			'image'	=> $image
		]);
		
		/* BalanceHistory::create([
			'user_id' 		=> session('bo_id'),
			'nominal'		=> $cek->nominal,
			'type'			=> 'OUT',
			'cash_or_bank'	=> 'CASH',
			'coa_id'		=> $cek->coa_id,
			'branch'		=> $cek->branch,
			'note'			=> $cek->paymentRequest->note,
			'date'			=> date('Y-m-d'),
			'image'			=> $image
		]); */
		
		$response = [
			'status'  => 200,
			'message' => 'Uploaded'
		];
			
		
		return response()->json($response);
		
	}
}
