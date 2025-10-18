<?php

namespace App\Http\Controllers\Admin;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller {
    
    public function index(Request $request)
    {
        $data = [
			'id'		=> $request->id,
			'name'		=> User::find(base64_decode($request->id))->name,
            'title'   	=> 'Experience',
            'content' 	=> 'admin.hrd.experience'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'company',
			'position',
			'month_start',
			'month_end',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$employee_id = base64_decode($request->id);

        $total_data = Experience::count();
        
        $query_data = Experience::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('company', 'like', "%$search%")
						->orWhere('position', 'like', "%$search%")
						->orWhere('month_start', 'like', "%$search%")
						->orWhere('month_end', 'like', "%$search%");
                    });
                }
            })
			->where('employee_id',$employee_id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Experience::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('company', 'like', "%$search%")
						->orWhere('position', 'like', "%$search%")
						->orWhere('month_start', 'like', "%$search%")
						->orWhere('month_end', 'like', "%$search%");
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
                    $val->company,
                    $val->position,
					date('M Y',strtotime($val->month_start)),
					date('M Y',strtotime($val->month_end)),
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
			'company'  		=> 'required',
			'position'  	=> 'required',
			'month_start'  	=> 'required',
			'month_end'   	=> 'required',
		], [
			'company.required'  		=> 'Company cannot be empty.',
			'position.required'  		=> 'Position no cannot be empty.',
			'month_start.required'   	=> 'Month start cannot be empty.',
			'month_end.required'   		=> 'Month end cannot be empty.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = Experience::find($request->temp);
				
				$query->update([
					'user_id'	    => session('bo_id'),
					'employee_id'	=> base64_decode($request->tempUser),
					'company'  		=> $request->company,
					'position'  	=> $request->position,
					'month_start'  	=> $request->month_start,
					'month_end'  	=> $request->month_end
				]);
				
			}else{
				$query = Experience::create([
					'user_id'	    => session('bo_id'),
					'employee_id'	=> base64_decode($request->tempUser),
					'company'  		=> $request->company,
					'position'  	=> $request->position,
					'month_start'  	=> $request->month_start,
					'month_end'  	=> $request->month_end
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Experience())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit work experience data by user '.session('bo_name').' to employee '.$query->employee->name);

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
        $data = Experience::find($request->id);
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = Experience::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new Experience())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Experience Member data');

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