<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlSph;
use App\Models\AlCustomer;
use App\Models\AlProject;
use App\Models\AlBudgeting;
use App\Models\AlBudgetingExpense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\AlIncome;
use Illuminate\Support\Facades\Validator;

class AlBudgetingController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    	=> 'RAB',
			'proyek'	=> AlProject::all(),
			'customer'	=> AlCustomer::all(),
            'content'  	=> 'admin.al.budgeting'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function getCustomerSph(Request $request){
		$sph = AlProject::where('al_customer_id',$request->id)->get();
		
		$result = [];
		
		foreach($sph as $row){
			$data = $row->alSph()->latest()->first();
			if($data){
				$result[] = [
					'id'			=> $data->id,
					'name'			=> $data->code.' Tgl. '.date('d M Y',strtotime($data->date)).' Rp '.number_format($data->grandtotal,0,',','.'),
					'ppn'			=> number_format($data->ppn, 0,',','.'),
					'pph'			=> $data->pph,
					'hpp'			=> $data->totalHpp(),
					'dk'			=> $data->totalDk(),
					'admin'			=> $data->totalAdmin(),
					'grandtotal'	=> number_format($data->grandtotal,0,',','.')
				];
			}
		}
		
		return $result;
	}
	
	public function datatable(Request $request){
		$column = [
            'id',
            'code',
            'name',
            'al_customer_id',
			'date',
			'date_disbursement'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlBudgeting::count();
        
        $query_data = AlBudgeting::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhere('date_disbursement', 'like', "%$search%")
							->orWhereHas('alCustomer', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlBudgeting::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhere('date_disbursement', 'like', "%$search%")
							->orWhereHas('alCustomer', function($query) use ($search) {
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
                    $nomor,
                    $val->code,
                    $val->name,
					$val->alProject->code.' - '.$val->alProject->name,
					$val->alCustomer->name,
                    date('d M Y',strtotime($val->date)),
					date('d M Y',strtotime($val->date_disbursement)),
                    '
						<a href="'.url("admin/al/rab/analysis/").'/'.$val->id.'" class="btn bg-info btn-sm" data-popup="tooltip" title="Analysis"><i class="icon-newspaper"></i></a>
						<!-- <a href="'.url("admin/al/rab/print/").'/'.$val->id.'" class="btn bg-success btn-sm" data-popup="tooltip" title="Print" target="_blank"><i class="icon-file-pdf"></i></a> -->
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Hapus" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
	
	public function create(Request $request){
		$validation = Validator::make($request->all(), [
			'al_project_id'				=> 'required',
            'name' 						=> 'required',
			'al_customer_id'			=> 'required',
			'date'						=> 'required',
			'date_disbursement'			=> 'required',
			'tipe_rab'					=> 'required',
			'note'						=> 'required',
			'total_claim'				=> 'required',
			'total_deposit'				=> 'required',
			'total_profit'				=> 'required',
			'total_admin'				=> 'required',
			'total_dk'					=> 'required',
        ], [
			'al_project_id.required'		=> 'Project cannot be empty.',
            'name.required'					=> 'Name budgeting cannot be empty.',
			'al_customer_id.required'		=> 'Customer cannot be empty.',
			'date.required'					=> 'Date cannot be empty.',
			'date_disbursement.required'	=> 'Date disbursement cannot be empty.',
			'tipe_rab.required'				=> 'Budgeting type cannot be empty.',
			'note.required'					=> 'Note cannot be empty.',
			'total_claim.required'			=> 'Total claim cannot be empty.',
			'total_deposit.required'		=> 'Total deposit cannot be empty.',
			'total_profit.required'			=> 'Total profit cannot be empty.',
			'total_admin.required'			=> 'Total admin cannot be empty.',
			'total_dk.required'				=> 'Total dk cannot be empty.'
        ]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = AlBudgeting::find($request->temp);
				
				$query->update([
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id ? $request->al_sph_id : NULL,
					'al_customer_id'	=> $request->al_customer_id,
					'name'				=> $request->name,
					'total_claim'		=> str_replace(',','.',str_replace('.','',$request->total_claim)),
					'total_profit'		=> str_replace(',','.',str_replace('.','',$request->total_profit)),
					'total_deposit'		=> str_replace(',','.',str_replace('.','',$request->total_deposit)),
					'total_admin'		=> str_replace(',','.',str_replace('.','',$request->total_admin)),
					'total_dk'			=> str_replace(',','.',str_replace('.','',$request->total_dk)),
					'total_other'	    => str_replace(',','.',str_replace('.','',$request->total_other)),
					'note'				=> $request->note,
					'date'				=> $request->date,
					'date_disbursement'	=> $request->date_disbursement,
					'type'				=> $request->tipe_rab
				]);
				
			}else{
				$query = AlBudgeting::create([
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id ? $request->al_sph_id : NULL,
					'al_customer_id'	=> $request->al_customer_id,
					'name'				=> $request->name,
					'code'				=> AlBudgeting::generateCode($request->al_customer_id),
					'total_claim'		=> str_replace(',','.',str_replace('.','',$request->total_claim)),
					'total_profit'		=> str_replace(',','.',str_replace('.','',$request->total_profit)),
					'total_deposit'		=> str_replace(',','.',str_replace('.','',$request->total_deposit)),
					'total_admin'		=> str_replace(',','.',str_replace('.','',$request->total_admin)),
					'total_dk'			=> str_replace(',','.',str_replace('.','',$request->total_dk)),
					'total_other'	    => str_replace(',','.',str_replace('.','',$request->total_other)),
					'note'				=> $request->note,
					'date'				=> $request->date,
					'date_disbursement'	=> $request->date_disbursement,
					'type'				=> $request->tipe_rab
				]);
			}
			
			if($query){
				activity()
					->performedOn(new AlBudgeting())
					->causedBy(session('bo_id'))
					->withProperties($query)
					->log('Add Budgeting AL data');

				$response = [
					'status'  	=> 200,
					'message' 	=> 'Data added successfully.'
				];
			}else{
				$response = [
					'status'  => 500,
					'message' => 'Data failed to add.'
				];
			}
		}
		
		return response()->json($response);
	}
	
	public function show(Request $request){
		$data = AlBudgeting::find($request->id);
		
		return response()->json([
			'data'		=> $data,
		]);
	}
	
	public function destroy(Request $request){
		$budgeting = AlBudgeting::find($request->id);
		
		if($budgeting->delete()){
			$response = [
                'status' 	=> 200,
                'message'  	=> 'Data deleted successfully.'
            ];
		}else{
			$response = [
                'status' 	=> 422,
                'message'  	=> 'Ups Error.'
            ];
		}
		
		return response()->json($response);
	}
	
	public function print(Request $request, $id){
		$data = AlBudgeting::find($request->id);
		
		return view('admin.al.print.budgeting', ['data' => $data]);
	}
	
	public function analysis(Request $request, $id){
		$data = AlBudgeting::find($request->id);
		
		$data = [
            'title'    	=> 'RAB '.$data->name,
			'data'		=> $data,
            'content'  	=> 'admin.al.budgeting_analysis'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}

	public function updateTax(Request $request){
		
		$data = AlBudgeting::find($request->id);

		$query_budgeting = AlBudgeting::where('id',$request->id)->update([
			'budget_ppn' =>  str_replace(',','.',str_replace('.','',$request->budget_ppn)),
			'budget_pph' =>  str_replace(',','.',str_replace('.','',$request->budget_pph)),
			'real_ppn' =>  str_replace(',','.',str_replace('.','',$request->real_ppn)),
			'real_pph' =>  str_replace(',','.',str_replace('.','',$request->real_pph)),
		]);


		
			$query_sph = $data->alSph->update([
				'is_pph' => $request->budget_pph == 0 ? 0 : 1,
				'pph' =>  str_replace(',','.',str_replace('.','',$request->budget_pph)),
				'ppn' =>  str_replace(',','.',str_replace('.','',$request->budget_ppn)),
			]);
	
		

		if($query_budgeting && $query_sph){
			$data = [
				'status'=>200,
				'message' => 'Success'
			];
		}else{
			$data = [
				'status'=>500,
				'message' => 'Failed'
			];
		}
		
		return response()->json($data);
	}

}