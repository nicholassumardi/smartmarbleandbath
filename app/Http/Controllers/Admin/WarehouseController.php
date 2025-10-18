<?php

namespace App\Http\Controllers\Admin;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Warehouse',
            'content' => 'admin.master_data.product.warehouse'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'code',
            'name',
            'status',
			'type'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Warehouse::count();
        
        $query_data = Warehouse::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%");
                    });
                }         
                
                if($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Warehouse::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%");
                    });
                }       
                
                if($request->status) {
                    $query->where('status', $request->status);
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
                    $val->status(),
					$val->type(),
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
		if($request->tempWarehouse){
			$validation = Validator::make($request->all(), [
				'code'             	=> ['required', Rule::unique('warehouses', 'code')->ignore($request->tempWarehouse)],
				'name'				=> 'required',
				'status'            => 'required'
			], [
				'code.required'     => 'Warehouse code cannot be empty.',
				'code.unique'       => 'Warehouse code already exists.',
				'name.required'		=> 'Warehouse name cannot be empty.',
				'status.required'	=> 'Please select a status.',
				'type.required'		=> 'Please select a type.'
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'code'             	=> 'required|unique:warehouses,code',
				'name'				=> 'required',
				'status'            => 'required'
			], [
				'code.required'     => 'Warehouse code cannot be empty.',
				'code.unique'       => 'Warehouse code already exists.',
				'name.required'		=> 'Warehouse name cannot be empty.',
				'status.required'	=> 'Please select a status.',
				'type.required'		=> 'Please select a type.'
			]);
		}

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->tempWarehouse){
				$query = Warehouse::find($request->tempWarehouse);
				
				$query->update([
					'code'		=> $request->code,
					'name'		=> $request->name,
					'status'	=> $request->status,
					'type'		=> $request->type
				]);
			}else{
				$query = Warehouse::create([
					'code'		=> $request->code,
					'name'		=> $request->name,
					'status'	=> $request->status,
					'type'		=> $request->type
				]);
			}
		
            if($query) {
                activity()
                    ->performedOn(new Warehouse())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit new warehouse data');

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
        $data = Warehouse::find($request->id);
        return response()->json([
            'code'		=> $data->code,
			'name'		=> $data->name,
            'status'	=> $data->status,
			'type'		=> $data->type,
        ]);
    }
	
	public function destroy(Request $request) 
    {
        $query = Warehouse::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Warehouse())
                ->causedBy(session('bo_id'))
                ->log('Delete the warehouse data');

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
