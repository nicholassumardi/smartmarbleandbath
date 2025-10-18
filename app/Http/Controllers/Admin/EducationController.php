<?php

namespace App\Http\Controllers\Admin;

use App\Models\Education;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller {
    
    public function index(Request $request)
    {
        $data = [
			'id'		=> $request->id,
			'name'		=> User::find(base64_decode($request->id))->name,
            'title'   	=> 'Education',
            'content' 	=> 'admin.hrd.education'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'grade',
			'institution',
			'majors',
			'score',
			'start_year',
			'end_year'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$employee_id = base64_decode($request->id);

        $total_data = Education::count();
        
        $query_data = Education::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('institution', 'like', "%$search%")
						->orWhere('majors', 'like', "%$search%")
						->orWhere('score', 'like', "%$search%")
						->orWhere('start_year', 'like', "%$search%")
						->orWhere('end_year', 'like', "%$search%");
                    });
                }
            })
			->where('employee_id',$employee_id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Education::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('institution', 'like', "%$search%")
						->orWhere('majors', 'like', "%$search%")
						->orWhere('score', 'like', "%$search%")
						->orWhere('start_year', 'like', "%$search%")
						->orWhere('end_year', 'like', "%$search%");
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
                    $val->grade(),
                    $val->institution,
					$val->majors,
					$val->score,
					$val->start_year.' - '.$val->end_year,
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
			'grade'  		=> 'required',
			'institution'  	=> 'required',
			'majors'  		=> 'required',
			'score'   		=> 'required',
			'year_start' 	=> 'required',
			'year_end' 		=> 'required',
		], [
			'grade.required'  			=> 'Grade cannot be empty.',
			'institution.required'  	=> 'Institution no cannot be empty.',
			'majors.required'   		=> 'Majors cannot be empty.',
			'score.required'    		=> 'Score cannot be empty.',
			'year_start.required'  		=> 'Year start cannot be empty.',
			'year_end.required'			=> 'Year end cannot be empty.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = Education::find($request->temp);
				
				$query->update([
					'user_id'	    => session('bo_id'),
					'employee_id'	=> base64_decode($request->tempUser),
					'grade'  		=> $request->grade,
					'institution'  	=> $request->institution,
					'majors'  		=> $request->majors,
					'score'   		=> $request->score,
					'start_year' 	=> $request->year_start,
					'end_year' 		=> $request->year_end,
				]);
				
			}else{
				$query = Education::create([
					'user_id'	    => session('bo_id'),
					'employee_id'	=> base64_decode($request->tempUser),
					'grade'  		=> $request->grade,
					'institution'  	=> $request->institution,
					'majors'  		=> $request->majors,
					'score'   		=> $request->score,
					'start_year' 	=> $request->year_start,
					'end_year' 		=> $request->year_end,
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Education())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit education data by user '.session('bo_name').' to employee '.$query->employee->name);

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
        $data = Education::find($request->id);
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = Education::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new Education())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Education Member data');

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