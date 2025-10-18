<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlChecklist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AlProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AlChecklistController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    => 'Al Checklist',
            'content'  => 'admin.al.master_data.checklist'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
			'order_data',
            'name',
            'description'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlChecklist::count();
        
        $query_data = AlChecklist::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('description', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlChecklist::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('description', 'like', "%$search%");
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
					$val->order_data,
                    $val->name,
                    $val->description,
                    '
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
		
		if($request->temp){
			$validation = Validator::make($request->all(), [
				'name'      		=> 'required',
				'order'				=> ['required', Rule::unique('al_checklists', 'order_data')->ignore($request->temp)],
				'description'    	=> 'required'
			], [
				'name.required'            => 'Name cannot be empty.',
				'order.required'           => 'Order Number cannot be a empty.',
				'order.unique'             => 'Order Number already exists.',
				'description.required'     => 'Description cannot be empty.'
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'name'      		=> 'required',
				'order'				=> 'required|unique:al_checklists,order_data',
				'description'    	=> 'required'
			], [
				'name.required'            => 'Name cannot be empty.',
				'order.required'           => 'Order Number cannot be a empty.',
				'order.unique'             => 'Order Number already exists.',
				'description.required'     => 'Description cannot be empty.'
			]);
		}
		

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = AlChecklist::find($request->temp);
				
				$query->update([
					'order_data'			=> $request->order,
					'user_id'				=> session('bo_id'),
					'name'           		=> $request->name,
					'description'			=> $request->description
				]);
			}else{
				$query = AlChecklist::create([
					'order_data'			=> $request->order,
					'user_id'				=> session('bo_id'),
					'name'           		=> $request->name,
					'description'			=> $request->description
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new AlChecklist())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master AL checklist data');

                $response = [
                    'status'  	=> 200,
                    'message' 	=> 'Data added successfully.'
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
	
	public function show(Request $request){
		$data = AlChecklist::find($request->id);
		
		return response()->json($data);
	}
	
	public function destroy(Request $request){
		$data = AlChecklist::find($request->id);
		
		$data->delete();
		
		return response()->json([
			'status'	=> 200,
			'message'	=> 'Data deleted successfully.'
		]);
	}
}