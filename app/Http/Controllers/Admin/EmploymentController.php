<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmploymentController extends Controller {
    
    public function index(Request $request)
    {
        $data = [
			'id'		=> $request->id,
			'name'		=> User::find(base64_decode($request->id))->name,
            'title'   	=> 'Employment',
            'content' 	=> 'admin.hrd.employment'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'employee_no',
			'document_no',
			'status',
			'branch',
			'start_date',
			'end_date',
			'image',
			'active',
			'resign_date'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$employee_id = base64_decode($request->id);

        $total_data = Employment::count();
        
        $query_data = Employment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('employee_no', 'like', "%$search%")
						->orWhere('document_no', 'like', "%$search%")
						->orWhere('start_date', 'like', "%$search%")
						->orWhere('end_date', 'like', "%$search%")
						->orWhere('resign_date', 'like', "%$search%");
                    });
                }
            })
			->where('employee_id',$employee_id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Employment::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('employee_no', 'like', "%$search%")
						->orWhere('document_no', 'like', "%$search%")
						->orWhere('start_date', 'like', "%$search%")
						->orWhere('end_date', 'like', "%$search%")
						->orWhere('resign_date', 'like', "%$search%");
                    });
                }
            })
			->where('employee_id',$employee_id)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                if(Storage::exists($val->image)) {
					if(in_array(explode('.',$val->image)[1],array('pdf','doc','docx','xls','xlsx'))){
						$photo = '<a href="'.asset(Storage::url($val->image)).'" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photo = '<a href="' . asset(Storage::url($val->image)) . '" data-lightbox="' . $val->employee->name . '" data-title="' . $val->employee->name . '"><img src="' . asset(Storage::url($val->image)) . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
					}
                    
                } else {
                    $photo = '<a href="' . asset('website/empty.jpg') . '" data-lightbox="' . $val->employee->name . '" data-title="' . $val->employee->name . '"><img src="' . asset('website/empty.jpg') . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
                }
				
                $response['data'][] = [
                    $nomor,
                    $val->employee_no,
                    $val->document_no,
					$val->status(),
					$val->branch(),
					date('d M Y',strtotime($val->start_date)),
					date('d M Y',strtotime($val->end_date)),
					$photo,
					$val->active(),
					$val->resign_date ? date('d M Y',strtotime($val->resign_date)) : '-',
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
			'employee_no'  	=> 'required',
			'document_no'   => 'required',
			'status'  		=> 'required',
			'branch'   		=> 'required',
			'start_date' 	=> 'required',
			'end_date' 		=> 'required',
		], [
			'employee_no.required'  => 'Employee no to cannot be empty.',
			'document_no.required'  => 'Document no cannot be empty.',
			'status.required'   	=> 'Status cannot be empty.',
			'branch.required'    	=> 'Branch cannot be empty.',
			'start_date.required'  	=> 'Start date cannot be empty.',
			'end_date.required'		=> 'End date cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = Employment::find($request->temp);
				
				if($request->has('file')) {
					if(Storage::exists($query->image)) {
						Storage::delete($query->image);
					}

					$image = $request->file('file')->store('public/employment');
				} else {
					$image = $query->image;
				}
				
				$query->update([
					'user_id'	     		=> session('bo_id'),
					'employee_no'			=> $request->employee_no,
					'document_no'			=> $request->document_no,
					'status'				=> $request->status,
					'branch'				=> $request->branch,
					'start_date'			=> $request->start_date,
					'end_date'				=> $request->end_date,
					'image'					=> $image,
					'active'				=> $request->active ? '1' : '2',
					'resign_date'			=> $request->resign_date
				]);
				
			}else{
				$query = Employment::create([
					'user_id'	     		=> session('bo_id'),
					'employee_id'			=> base64_decode($request->tempUser),
					'employee_no'			=> $request->employee_no,
					'document_no'			=> $request->document_no,
					'status'				=> $request->status,
					'branch'				=> $request->branch,
					'start_date'			=> $request->start_date,
					'end_date'				=> $request->end_date,
					'image'					=> $request->has('file') ? $request->file('file')->store('public/employment') : NULL,
					'active'				=> $request->active ? '1' : '2',
					'resign_date'			=> $request->resign_date
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Employment())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit employment data by user '.session('bo_name').' to employee '.$query->employee->name);

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
        $data = Employment::find($request->id);
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = Employment::find($request->id);
		
        if($query) {
			$query->deleteFile();
			$query->delete();
			
            activity()
                ->performedOn(new Employment())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Employment data');

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