<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helper\SMB;
use Illuminate\Support\Collection;
use App\Models\PurchaseRequest;
use App\Models\CashFlow;
use Illuminate\Support\Str;

class CashFlowController extends Controller {
    
    public function index(Request $request)
    {
		$month = $request->month ? $request->month : date('Y-m');
		$branch = $request->branch ? $request->branch : '1';
		$before = date('Y-m', strtotime($month." -1 month"));
		$after = date('Y-m', strtotime($month." +1 month"));
		
		$data = SMB::getCashFlow($month,$branch);
		
        $data = [
            'title'   			=> 'Budgeting Cash Flow',
            'content' 			=> 'admin.finance.cash_flow',
			'datadebit'			=> collect($data['resultdebit'])->sortBy('date')->toArray(),
			'datadebitbh'		=> collect($data['resultdebitbh'])->sortBy('date')->toArray(),
			'datacredit'		=> collect($data['resultcredit'])->sortBy(function ($credit, $key) {
                        return $credit['fixedcost'].$credit['date'];
                    })->toArray(),
			'datacreditbh'		=> collect($data['resultcreditbh'])->sortBy(function ($credit, $key) {
                        return $credit['date'];
                    })->toArray(),
			'weeks'				=> $data['weeks'],
			'balance_cash_bank'	=> $data['balance_cash_bank'],
			'month'				=> $month,
			'before'			=> $before,
			'after'				=> $after,
			'branch'			=> $branch
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function changeDate(Request $request){
		$validation = Validator::make($request->all(), [
            'date'				=> 'required',
        ], [
            'date.required'     => 'Date cannot be empty.',
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            
			foreach($request->data_id as $key => $row){
				$cf = CashFlow::where('type',$request->data_type[$key])->where('type_id',$row)->first();
				$cf2 = CashFlow::where('code',$row)->first();
					
				if($cf){
					$cf->delete();
				}

				if($cf2){
					$cf2->update([
						'date'		=> $request->date,
					]);
				}else{
					CashFlow::create([
						'code'		=> strtoupper(Str::random(15)),
						'user_id'	=> session('bo_id'),
						'date'		=> $request->date,
						'type'		=> $request->data_type[$key],
						'type_id'	=> $row,
						'nominal'	=> str_replace(',','.',str_replace('.','',$request->data_nominal[$key]))
					]);
				}
			}

			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
        }

        return response()->json($response);
	}
	
	public function save(Request $request){
		
		$pr = PurchaseRequest::find($request->id);
		$cf = CashFlow::where('code',$request->id)->first();
		
		if($cf){
			if(($cf->nominal - str_replace(',','.',str_replace('.','',$request->nominal))) < 0){
				return response()->json([
					'status'  		=> 500,
					'message' 		=> 'Data you input is equal or more than allowed PR total.',
				]);
			}
			
			if(str_replace(',','.',str_replace('.','',$request->nominal)) == 0){
				$cf->delete();
			}else{
				if(($cf->nominal - str_replace(',','.',str_replace('.','',$request->nominal))) > 0){
					CashFlow::create([
						'code'		=> strtoupper(Str::random(15)),
						'user_id'	=> session('bo_id'),
						'date'		=> $request->date,
						'type'		=> 'purchase_requests',
						'type_id'	=> $cf->type_id,
						'nominal'	=> $cf->nominal - str_replace(',','.',str_replace('.','',$request->nominal))
					]);
				}
				
				$cf->update([
					'user_id'	=> session('bo_id'),
					'date'		=> $request->date,
					'nominal'	=> str_replace(',','.',str_replace('.','',$request->nominal))
				]);
			}

		}else{
			
			if(($pr->total_nominal - str_replace(',','.',str_replace('.','',$request->nominal))) < 0){
				return response()->json([
					'status'  		=> 500,
					'message' 		=> 'Data you input is equal or more than allowed PR total.',
				]);
			}
			
			$cf1 = CashFlow::create([
				'code'		=> strtoupper(Str::random(15)),
				'user_id'	=> session('bo_id'),
				'date'		=> $request->date,
				'type'		=> 'purchase_requests',
				'type_id'	=> $request->id,
				'nominal'	=> str_replace(',','.',str_replace('.','',$request->nominal))
			]);
			
			if($pr){
				if(($pr->total_nominal - str_replace(',','.',str_replace('.','',$request->nominal))) > 0){
					$cf2 = CashFlow::create([
						'code'		=> strtoupper(Str::random(15)),
						'user_id'	=> session('bo_id'),
						'date'		=> $request->date,
						'type'		=> 'purchase_requests',
						'type_id'	=> $pr->id,
						'nominal'	=> $pr->total_nominal - str_replace(',','.',str_replace('.','',$request->nominal))
					]);
				}
			}
		}		

		$response = [
			'status'  		=> 200,
			'message' 		=> 'Data added successfully.',
			'cashflow1'		=> isset($cf1) ? $cf1 : '',
			'cashflow2'		=> isset($cf2) ? $cf2 : ''
		];

        return response()->json($response);
	}
	
	public function saveDebit(Request $request){
		
		$cf = CashFlow::where('code',$request->id)->first();
		//$cf = CashFlow::where('type',$request->type)->where('type_id',$request->id)->first();
		
		if($cf){
			$cf->update([
				'user_id'	=> session('bo_id'),
				'date'		=> $request->date,
				'nominal'	=> str_replace(',','.',str_replace('.','',$request->nominal))
			]);
		}else{
			CashFlow::create([
				'code'		=> strtoupper(Str::random(15)),
				'user_id'	=> session('bo_id'),
				'date'		=> $request->date,
				'type'		=> $request->type,
				'type_id'	=> $request->id,
				'nominal'	=> str_replace(',','.',str_replace('.','',$request->nominal))
			]);
		}
		
		$response = [
			'status'  		=> 200,
			'message' 		=> 'Data added successfully.'
		];

        return response()->json($response);
	}
}
