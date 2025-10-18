<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asset;
use App\Models\AssetEmployee;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AssetEmployeeController extends Controller {
    
    public function index(Request $request)
    {
        $data = [
			'id'		=> $request->id,
			'name'		=> User::find(base64_decode($request->id))->name,
			'asset'		=> Asset::all(),
            'title'   	=> 'Employee Asset',
            'content' 	=> 'admin.hrd.asset'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'name',
			'date_borrow',
			'date_return',
			'status',
			'note',
			'note_return'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$employee_id = base64_decode($request->id);

        $total_data = AssetEmployee::count();
        
        $query_data = AssetEmployee::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date_borrow', 'like', "%$search%")
						->orWhere('date_return', 'like', "%$search%")
						->orWhere('note', 'like', "%$search%")
						->orWhere('note_return', 'like', "%$search%");
                    })->orWhereHas('asset',function($query) use($search, $request){
						$query->where('name', 'like', "%$search%")
						->orWhere('starting_value', 'like', "%$search%");
					});
                }
            })
			->where('employee_id',$employee_id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AssetEmployee::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date_borrow', 'like', "%$search%")
						->orWhere('date_return', 'like', "%$search%")
						->orWhere('note', 'like', "%$search%")
						->orWhere('note_return', 'like', "%$search%");
                    })->orWhereHas('asset',function($query) use($search, $request){
						$query->where('name', 'like', "%$search%")
						->orWhere('starting_value', 'like', "%$search%");
					});
                }
            })
			->where('employee_id',$employee_id)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
                $response['data'][] = [
                    $nomor,
                    $val->asset->name,
                    date('d M Y',strtotime($val->date_borrow)),
					$val->date_return ? date('d M Y',strtotime($val->date_return)) : '-',
					$val->status(),
					$val->note,
					$val->note_return ? $val->note_return : '-',
					'	
						<button type="button" class="btn bg-success btn-sm" data-popup="tooltip" title="Return" onclick="dateReturn(' . $val->id . ')"><i class="icon-paste3"></i></button>
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
			'asset_id'  		=> 'required',
			'date_borrow'  		=> 'required',
			'note'  			=> 'required',
		], [
			'asset_id.required'  		=> 'Asset cannot be empty.',
			'date_borrow.required'  	=> 'Date borrow no cannot be empty.',
			'note.required'   			=> 'Note cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = AssetEmployee::find($request->temp);
				
				$query->update([
					'user_id'	    => session('bo_id'),
					'employee_id'	=> base64_decode($request->tempUser),
					'asset_id'  	=> $request->asset_id,
					'date_borrow'  	=> $request->date_borrow,
					'note'  		=> $request->note
				]);
				
			}else{
				$query = AssetEmployee::create([
					'user_id'	    => session('bo_id'),
					'employee_id'	=> base64_decode($request->tempUser),
					'asset_id'  	=> $request->asset_id,
					'date_borrow'  	=> $request->date_borrow,
					'note'  		=> $request->note,
					'status'		=> '1'
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new AssetEmployee())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit Asset data by user '.session('bo_name').' to employee '.$query->employee->name);

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
	
	public function updateReturn(Request $request){
		
		$validation = Validator::make($request->all(), [
			'date_return'  		=> 'required',
			'note_return'  		=> 'required',
		], [
			'date_return.required'  	=> 'Date return cannot be empty.',
			'note_return.required'  	=> 'Note return cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$query = AssetEmployee::find($request->tempReturn);
			
			$query->update([
				'date_return'  	=> $request->date_return,
				'note_return'  	=> $request->note_return,
				'status'  		=> '2'
			]);
				
            if($query) {
                activity()
                    ->performedOn(new AssetEmployee())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add return date by user '.session('bo_name').' to employee '.$query->employee->name);

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
        $data = AssetEmployee::find($request->id);
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = AssetEmployee::find($request->id);
		
        if($query) {
			if($query->date_return){
				$response = [
					'status'  => 500,
					'message' => 'Data failed to delete, because it is already returned.'
				];
				
				return response()->json($response);
			}
			
			$query->delete();
			
            activity()
                ->performedOn(new AssetEmployee())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Employee Asset Member data');

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