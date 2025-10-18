<?php

namespace App\Http\Controllers\Admin;

use App\Models\Allowance;
use App\Models\AllowanceRule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class AllowanceController extends Controller {

    public function index()
    {
		
        $data = [
            'title'   			=> 'Allowance',
            'content' 			=> 'admin.master_data.hrd.allowance'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'name',
			'type',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Allowance::count();
        
        $query_data = Allowance::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Allowance::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
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
                    $val->name,
                    $val->type(),
					'	
                        <button type="button" class="btn bg-success btn-sm" data-popup="tooltip" title="Add/Edit Rules of Cutting" onclick="rule(' . $val->id . ',`'.$val->name.'`)"><i class="icon-percent"></i><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.(count($val->allowanceRule) ? count($val->allowanceRule) : "0").'</span></button>
					',
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
	
	public function create(Request $request){
		
		$validation = Validator::make($request->all(), [
			'name'  				=> 'required',
			'type'  				=> 'required',
		], [
			'name.required'  				=> 'Name cannot be empty.',
			'type.required'  				=> 'Type cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = Allowance::find($request->temp);
				
				$query->update([
					'user_id'	    		=> session('bo_id'),
					'name'					=> $request->name,
					'type'					=> $request->type,
				]);
				
			}else{
				$query = Allowance::create([
					'user_id'	    		=> session('bo_id'),
					'name'					=> $request->name,
					'type'					=> $request->type,
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Allowance())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit work allowance data by user '.session('bo_name'));

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
	
	public function createRule(Request $request){
		
		$validation = Validator::make($request->all(), [
			'temp_rule'  			=> 'required',
			'type_rule'  			=> 'required',
			'percentage_cutting'	=> 'required',
		], [
			'temp_rule.required'  				=> 'Allowance cannot be empty.',
			'type_rule.required'  				=> 'Type cannot be empty.',
			'percentage_cutting.required'  		=> 'Percentage cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$query = AllowanceRule::create([
				'allowance_id'	    	=> $request->temp_rule,
				'type_rule'				=> $request->type_rule,
				'sign_rule'				=> $request->sign_rule,
				'unit_rule'				=> $request->unit_rule,
				'number_rule'			=> $request->number_rule,
				'percentage_cutting'	=> $request->percentage_cutting,
			]);

            if($query) {
                activity()
                    ->performedOn(new AllowanceRule())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit work allowance rules data by user '.session('bo_name'));

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
	
	public function show(Request $request)
    {
        $data = Allowance::find($request->id);
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = Allowance::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new Allowance())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Allowance data');

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
	
	public function destroyRule(Request $request) 
    {
        $query = AllowanceRule::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new AllowanceRule())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Allowance Rule data');

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
	
	public function showRules(Request $request){
		$data = AllowanceRule::where('allowance_id',$request->id)->get();
		
		$result = [];
		foreach($data as $row){
			$result[] = [
				'id'		=> $row->id,
				'type'		=> $row->typeRule(),
				'rule'		=> $row->sign_rule ? $row->sign_rule : 'Not Set',
				'number'	=> $row->number_rule ? $row->number_rule : 'Not Set',
				'unit'		=> $row->unitRule() ? $row->unitRule() : 'Not Set',
				'percent'	=> number_format($row->percentage_cutting,2,',','.')
			];
		}
		
		return response()->json([
			'list'	=> $result
		]);
	}
}