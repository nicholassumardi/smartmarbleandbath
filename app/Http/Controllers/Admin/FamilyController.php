<?php

namespace App\Http\Controllers\Admin;

use App\Models\Family;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FamilyController extends Controller {
    
    public function index(Request $request)
    {
        $data = [
			'id'		=> $request->id,
			'name'		=> User::find(base64_decode($request->id))->name,
            'title'   	=> 'Family',
            'content' 	=> 'admin.hrd.family'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'fullname',
			'relationship',
			'hp',
			'address',
			'id_number',
			'gender',
			'birthday',
			'religion',
			'marital_status',
			'job',
			'emergency'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$employee_id = base64_decode($request->id);

        $total_data = Family::count();
        
        $query_data = Family::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('fullname', 'like', "%$search%")
						->orWhere('hp', 'like', "%$search%")
						->orWhere('address', 'like', "%$search%")
						->orWhere('id_number', 'like', "%$search%")
						->orWhere('birthday', 'like', "%$search%")
						->orWhere('job', 'like', "%$search%");
                    });
                }
            })
			->where('employee_id',$employee_id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Family::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('fullname', 'like', "%$search%")
						->orWhere('hp', 'like', "%$search%")
						->orWhere('address', 'like', "%$search%")
						->orWhere('id_number', 'like', "%$search%")
						->orWhere('birthday', 'like', "%$search%")
						->orWhere('job', 'like', "%$search%");
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
                    $val->fullname,
                    $val->relationship(),
					$val->hp,
					$val->address,
					$val->id_number,
					$val->gender(),
					date('d M Y',strtotime($val->birthday)),
					$val->religion(),
					$val->marital_status(),
					$val->job,
					$val->emergency ? 'Yes' : 'No',
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
			'fullname'  		=> 'required',
			'relationship'   	=> 'required',
			'hp'  				=> 'required',
			'address'   		=> 'required',
			'id_number' 		=> 'required',
			'gender' 			=> 'required',
			'birthday'			=> 'required',
			'religion'			=> 'required',
			'marital_status'	=> 'required',
			'job'				=> 'required'
		], [
			'fullname.required'  		=> 'Fullname cannot be empty.',
			'relationship.required'  	=> 'Relationship no cannot be empty.',
			'hp.required'   			=> 'HP cannot be empty.',
			'address.required'    		=> 'Address cannot be empty.',
			'id_number.required'  		=> 'ID number cannot be empty.',
			'gender.required'			=> 'Gender cannot be empty.',
			'birthday.required'			=> 'Birth date cannot be empty.',
			'religion.required'			=> 'Religion cannot be empty.',
			'marital_status.required'	=> 'Marital status cannot be empty.',
			'job.required'				=> 'Job cannot be empty.'	
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = Family::find($request->temp);
				
				$query->update([
					'user_id'	     		=> session('bo_id'),
					'employee_id'			=> base64_decode($request->tempUser),
					'fullname'				=> $request->fullname,
					'relationship'			=> $request->relationship,
					'hp'					=> $request->hp,
					'address'				=> $request->address,
					'id_number'				=> $request->id_number,
					'gender'				=> $request->gender,
					'birthday'				=> $request->birthday,
					'religion'				=> $request->religion,
					'marital_status'		=> $request->marital_status,
					'job'					=> $request->job,
					'emergency'				=> $request->emergency ? '1' : NULL
				]);
				
			}else{
				$query = Family::create([
					'user_id'	     		=> session('bo_id'),
					'employee_id'			=> base64_decode($request->tempUser),
					'fullname'				=> $request->fullname,
					'relationship'			=> $request->relationship,
					'hp'					=> $request->hp,
					'address'				=> $request->address,
					'id_number'				=> $request->id_number,
					'gender'				=> $request->gender,
					'birthday'				=> $request->birthday,
					'religion'				=> $request->religion,
					'marital_status'		=> $request->marital_status,
					'job'					=> $request->job,
					'emergency'				=> $request->emergency ? '1' : NULL
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Family())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit family data by user '.session('bo_name').' to employee '.$query->employee->name);

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
        $data = Family::find($request->id);
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = Family::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new Family())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Family Member data');

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