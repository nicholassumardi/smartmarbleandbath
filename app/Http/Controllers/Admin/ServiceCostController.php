<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SendMessage;
use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\BalanceHistory;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Coa;
use App\Models\Customer;
use App\Models\Journal;
use App\Models\Notification;
use App\Models\ServiceCost;
use App\Models\ServiceCostPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;

class ServiceCostController extends Controller
{
    public function index()
    {
        $data = [
            'title'     => 'Service Cost',
            'customer'  => Customer::all(),
            'coa'       => Coa::where('status', 1)->oldest('code')->get(),
            'content'   => 'admin.sales.service_cost',
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'user_id',
            'customer_id',
            'code',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ServiceCost::count();

        $query_data = ServiceCost::where(function ($query) use ($search, $request) {
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
            ->groupBy('id')
            ->get();

        $total_filtered = ServiceCost::where(function ($query) use ($search, $request) {
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
            $nomor = $start + 1;
            foreach ($query_data as $val) {
                $btnPrint = '<a onclick="openLink(`'.url('admin/sales/service_cost/print/sales_cost/'.base64_encode($val->id)).'`)" href="javascript:void(0);" class="btn bg-success btn-sm"><i class="icon-file-pdf"></i></a>';

   
                $btnAction = '<button onclick="show(' . $val->id . ')" class="btn bg-info btn-sm" data-popup="tooltip" title="Edit"><i class="icon-pencil5"></i></button>
                <button class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash"></i></button>';
                if($val->image){
					if(explode('.',$val->image)[1] == 'pdf'){
						$proof = '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof = '<a data-magnify="gallery" data-src="" data-caption="'.$val->item.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}else{
					$proof = '<span class="badge badge-danger">Empty</span>';
				}
                if (in_array(1, session('bo_role')) || in_array(3, session('bo_role')) ||in_array(4, session('bo_role'))  || in_array(11, session('bo_role'))) {
                    $btnAction = '<button onclick="show(' . $val->id . ')" class="btn bg-info btn-sm" data-popup="tooltip" title="Edit"><i class="icon-pencil5"></i></button>
                    <a href="javascript:void(0);" title="Payment" class="btn btn-primary btn-pay btn-sm" data-toggle="modal" data-target="#modal_form_pay"  onclick="getServiceCostPayment(' . $val->id . ')"><i class="icon-cash"></i></a>
                    <button class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash"></i></button>';    
                };

                if (in_array(1, session('bo_role')) || in_array(3, session('bo_role')) || in_array(4, session('bo_role')) || in_array(11, session('bo_role'))) {
                    $response['data'][] = [
                        $nomor,
                        $val->code,
                        $val->user->name,
                        $val->customer->name,
                        $btnPrint,
                        $proof,
                        $val->getPercentPay(),
                        $btnAction,
                    ];
                }else{
                    $response['data'][] = [
                        $nomor,
                        $val->code,
                        $val->user->name,
                        $val->customer->name,
                        $btnPrint,
                        $proof,
                        $btnAction,
                    ];
                }
              

                $nomor++;
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'customer_id'  => 'required'
        ], [
            'customer_id.required'   => 'Customer cannot be empty.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if ($request->temp_scid) {
                $query =  ServiceCost::find($request->temp_scid);

                	
				if($request->has('file')) {
					$image = $request->file('file')->store('public/project');
				}else{
					$image = $query->image;
				}

                $query->update([
                    'user_id'                => session('bo_id'),
                    'customer_id'            => $request->customer_id,
                    'branch'                 => $request->branch,
                    'delivery_cost'          => floatval(str_replace(',', '.', str_replace('.', '', $request->delivery_cost))),
                    'cutting_cost'           => floatval(str_replace(',', '.', str_replace('.', '', $request->cutting_cost))),
                    'misc_cost'              => floatval(str_replace(',', '.', str_replace('.', '', $request->misc_cost))),
                    'note'                   => $request->note,
                    'is_ppn'                 => $request->is_ppn,
                    'image'                  => $image,
                ]);


                $cekcb = CashBank::where('lookable_type','service_costs')->where('lookable_id',$query->id)->first();
				
				if($cekcb){
					$cekcb->deleteFile();
					$cekcb->deleteDetail();
					$cekcb->delete();
				}
                
            } else {

                $image = $request->has('file') ? $request->file('file')->store('public/project') : null;

                $query =  ServiceCost::create([
                    'user_id'               => session('bo_id'),
                    'customer_id'           => $request->customer_id,
                    'branch'                 => $request->branch,
                    'code'                  => ServiceCost::generateCode(),
                    'delivery_cost'         => floatval(str_replace(',', '.', str_replace('.', '', $request->delivery_cost))),
                    'cutting_cost'          => floatval(str_replace(',', '.', str_replace('.', '', $request->cutting_cost))),
                    'misc_cost'             => floatval(str_replace(',', '.', str_replace('.', '', $request->misc_cost))),
                    'note'                  => $request->note,
                    'is_ppn'                => $request->is_ppn,
                    'image'                 => $image,
                ]);
                
            }

            ServiceCost::find($query->id)->updateGrandTotalService();

            $roleapproval = array('4');
			Approval::sendApproval($roleapproval,'service_costs',$query->id,'approved_id',session('bo_id'));
			#end approval
			
			SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Service Charge No. '.$query->code.'Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');

            activity()
                ->performedOn(new ServiceCost())
                ->causedBy(session('bo_id'))
                ->log('Create New Service Cost' . session('bo_name') . '');

            if ($query) {
                $response = [
                    'status'  => 200,
                    'message' => 'Successfully saved'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Server Error'
                ];
            }
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $service_cost = ServiceCost::find($request->id);

        $data = [
            'customer_id'   =>  $service_cost->customer_id,
            'branch'        =>  $service_cost->branch,
            'delivery_cost' =>  $service_cost->delivery_cost ? $service_cost->delivery_cost : 0,
            'cutting_cost'  =>  $service_cost->cutting_cost ? $service_cost->cutting_cost : 0,
            'misc_cost'     =>  $service_cost->misc_cost ? $service_cost->misc_cost : 0,
            'note'          =>  $service_cost->note ? $service_cost->note : '',
            'is_ppn'        =>  $service_cost->is_ppn,
        ];

        return response()->json($data);
    }

    public function createServiceCostPayment(Request $request){
		if($request->temp_pay){
			$validation = Validator::make($request->all(), [
				'pay_date' 			=> 'required',
				'pay_coa' 			=> 'required',
				'pay_nominal' 		=> 'required',
			], [
				'pay_date.required'   		=> 'Date cannot be empty.',
				'pay_coa.required'    		=> 'Cash & bank destination cannot be empty.',
				'pay_nominal.required'		=> 'Nominal cannot be empty.',
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'pay_date' 			=> 'required',
				'pay_coa' 			=> 'required',
				'pay_nominal' 		=> 'required',
				'pay_file' 			=> 'required'
			], [
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
            $service_cost = ServiceCost::find($request->service_cost_id);
            $branch = $service_cost->branch;

			if($request->temp_pay){
				$query = ServiceCostPayment::find($request->temp_pay);
				
				if($request->has('pay_file')) {
					$image = $request->file('pay_file')->store('public/project');
				}else{
					$image = $query->image;
				}
				
				$query->update([
					'user_id'	     		=> session('bo_id'),
					'service_cost_id'	    => $service_cost->id,
					'date_paid'				=> $request->pay_date,
					'coa_id'				=> $request->pay_coa,
					'customer_id'			=> $service_cost->customer_id,
					'branch'			    => $branch,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
					'note'					=> $request->pay_note,
					'image'					=> $image
				]);
				
				$cekcb = CashBank::where('lookable_type','service_cost_payments')->where('lookable_id',$query->id)->first();
				
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
				
			}else{	
				$image = $request->has('pay_file') ? $request->file('pay_file')->store('public/project') : null;
				
				$query = ServiceCostPayment::create([
					'user_id'	     		=> session('bo_id'),
					'service_cost_id'	    => $service_cost->id,
					'code'					=> ServiceCostPayment::generateCode(),
					'date_paid'				=> $request->pay_date,
					'coa_id'				=> $request->pay_coa,
					'customer_id'			=> $service_cost->customer_id,
                    'branch'			    => $branch,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
					'note'					=> $request->pay_note,
					'image'					=> $image
				]);
			}
			
			$coa_credit = '27';
            $coa_credit_customer_deposit = '67';
            $overpayment = str_replace(',','.',str_replace('.','',$request->pay_nominal)) - $service_cost->grandtotal_service;
			$description = 'Service charge payment with code. '.$query->code;
			
			if($request->pay_coa !== 67){
				#updatecashbank
				$bh = BalanceHistory::create([
					'user_id' 		=> session('bo_id'),
					'nominal'		=> round(str_replace(',','.',str_replace('.','',$request->pay_nominal)),0),
					'type'			=> 'IN',
					'cash_or_bank'	=> $request->payment_method == '0' ? 'BANK' : 'CASH',
					'coa_id'		=> $request->pay_coa,
					'branch'		=> $branch,
					'note'			=> $description,
					'date'			=> $request->pay_date,
					'image'			=> $request->file('pay_file') ? $request->file('pay_file')->store('public/cashbank') : ''
				]);
			}
			
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
			
            if($query && $cb) {
                // DEBIT
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
				
                // CREDIT
                CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> $coa_credit,
					'branch'		=> $branch,
					'type'       	=> '2',
					'nominal'      	=> round($service_cost->grandtotal_service,0),
					'note'         	=> ''
				]);

				Journal::insert([
					'date_transaction' => $request->pay_date,
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => $coa_credit,
					'branch'		   => $branch,
					'type'	           => '2',
					'nominal'          => round($service_cost->grandtotal_service,0),
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);

                // JIKA NOMINAL PEMBAYARAN LEBIH BESAR DARI TAGIHAN MAKA TIMBUL (customer deposit)
                if($overpayment > 0){
                    CashBankDetail::create([
                        'cash_bank_id' 	=> $cb->id,
                        'coa_id'       	=> $coa_credit_customer_deposit,
                        'branch'		=> $branch,
                        'type'       	=> '2',
                        'nominal'      	=> round($overpayment,0),
                        'note'         	=> ''
                    ]);
    
                    Journal::insert([
                        'date_transaction' => $request->pay_date,
                        'journalable_type' => 'cash_banks',
                        'journalable_id'   => $cb->id,
                        'coa_id'           => $coa_credit_customer_deposit,
                        'branch'		   => $branch,
                        'type'	           => '2',
                        'nominal'          => round($overpayment,0),
                        'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                        'updated_at'       => date('Y-m-d H:i:s')
                    ]);
                }

				#send approval
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval,'service_cost_payments',$query->id,'approved_id',session('bo_id'));
                $roleapproval = array('5');
				Approval::sendApproval($roleapproval,'service_cost_payments',$query->id,'marketing_id',session('bo_id'));
				#end approval
				
				SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Multi Payment Project No. '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				SendMessage::send(env('FINANCE_PHONE'),'Halo pak/bu. Mohon dibantu approve Multi Payment Project No. '.$query->code.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				
				ServiceCostPayment::find($query->id)->update([
					'cash_bank_id' => $cb->id
				]);
				
				activity()
					->performedOn(new ServiceCostPayment())
					->causedBy(session('bo_id'))
					->withProperties($query)
					->log('Add service charge payment by user '.session('bo_name'));

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

    public function print(Request $request, $param, $id)
    {
        if ($param == 'sales_cost') {
            $sc_id = base64_decode($id);
            $service    = ServiceCost::find($sc_id);

            if (!$service) {
                abort(404);
            }

            $pdf = PDF::loadView(
                'admin.pdf.service_cost.' . $param,
                [
                    'service' => $service
                ],
                [],
                [
                    'format' => 'A4-P',
                    'orientation' => 'P'
                ]
            );
        }

        return $pdf->stream('tjs.pdf');
    }


    public function getServiceCostJournal(Request $request){
      $data = [];

      $cb = CashBankDetail::whereHas('cashBank', function ($query) use($request){
            $query->where('lookable_type', 'service_costs')->where('lookable_id', $request->id);
      })
      ->where('coa_id', 27)
      ->get();
      
    foreach ($cb as $rowcbd) {
        if($rowcbd->type == 1){
            $data [] = [
                'date'     => date('d M Y', strtotime($rowcbd->cashBank->date)),
                'coa_name' => $rowcbd->coa->name,
                'branch'   => $rowcbd->branch(),
                'type'     => $rowcbd->type == 1 ? 'Debit' : 'Credit',
                'nominal'  => number_format($rowcbd->nominal,0,',','.'),
            ];
        }
    }
      
      $arrayResult = [
        'data' => $data
      ];
     
      return response()->json($arrayResult);
    }

    public function getServiceCostPayment(Request $request){
        $data = [];

        foreach (ServiceCostPayment::where('id', $request->id)->get() as $row) {

            if($row->image){
                if(explode('.',$row->image)[1] == 'pdf'){
                    $proof = '<a href="' .$row->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
                }else{
                    $proof = '<a data-magnify="gallery" data-src="" data-caption="'.$row->item.'" data-group="a" href="' .$row->attachment() . '"><img src="' . $row->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
                }
            }else{
                $proof = '<span class="badge badge-danger">Empty</span>';
            }

            $data [] =[
                'id'        => $row->id,
                'date'      => $row->date_paid,
                'branch'    => $row->branch(),
                'coa'       => $row->coa->name,
                'nominal'   => number_format($row->nominal,0,',','.'),
                'proof'     => $proof,
                'code'      => $row->code ? $row->code : '-',
                'note'      => $row->note,
            ];
        }


    
        $arrayResult = [
            'data' => $data
          ];

        return response()->json($arrayResult);
    }


    public function destroy(Request $request){
       $query = ServiceCost::find($request->id);
       $query_payment = ServiceCostPayment::where('service_cost_id', $query->id)->get();

     
        
        $cb_cost = CashBank::where('lookable_id',$request->id)->where('lookable_type','service_costs')->get();
        
        foreach($cb_cost as $row){
            $row->deleteDetail();
            BalanceHistory::where('cash_bank_reference',$row->id)->delete();
            $row->delete();
        }
        
        foreach ($query_payment as $row) {
            foreach (CashBank::where('lookable_id',$row->id)->where('lookable_type','service_cost_payments')->get() as $rowcb) {
                $rowcb->deleteDetail();
                BalanceHistory::where('cash_bank_reference',$rowcb->id)->delete();
                $rowcb->delete();
            }
        }

        foreach ($query_payment as $row) {
            if($row->image){
               $row->deleteFile();
            }
            $row->delete();
        }

        if($query->image){
            $query->deleteFile();
        }
        $query->delete();

        if($query) {
            activity()
                ->performedOn(new ServiceCost())
                ->causedBy(session('bo_id'))
                ->log('Delete the service charge');

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

    public function destroyPayment(Request $request){
        $query = ServiceCostPayment::find($request->id);

        if($query->image){
            $query->deleteFile();
        }
        $query->delete();
        
        $cb = CashBank::where('lookable_id',$request->id)->where('lookable_type','service_costs')->get();
        
        foreach($cb as $row){
            $row->deleteDetail();
            BalanceHistory::where('cash_bank_reference',$row->id)->delete();
            $row->delete();
        }
        
        if($query) {
            activity()
                ->performedOn(new ServiceCostPayment())
                ->causedBy(session('bo_id'))
                ->log('Delete the payment purchase request data');

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
}
