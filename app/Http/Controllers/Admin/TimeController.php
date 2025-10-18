<?php

namespace App\Http\Controllers\Admin;

use App\Models\Schedule;
use App\Models\Holiday;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\CompanyEntity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TimeController extends Controller {

    public function index()
    {
        $data = [
			'title'   => 'Time Management',
			'content' => 'admin.hrd.time_management'
		];

		return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatableSchedule(Request $request){
		$column = [
            'id',
			'branch',
			'day',
			'in_time',
			'out_time'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Schedule::count();
        
        $query_data = Schedule::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('in_time', 'like', "%$search%")
						->orWhere('out_time', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Schedule::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('in_time', 'like', "%$search%")
						->orWhere('out_time', 'like', "%$search%");
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
					$val->branch(),
                    $val->day(),
                    $val->in_time,
					$val->out_time,
					'
						<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="showSchedule(' . $val->id . ')"><i class="icon-pencil7"></i></button>
						<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroySchedule(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
	
	public function datatableHoliday(Request $request){
		$column = [
            'id',
			'date',
			'branch',
			'description',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Holiday::count();
        
        $query_data = Holiday::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
						->orWhere('description', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Holiday::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
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
					date('d M Y', strtotime($val->date)),
					$val->branch(),
                    $val->description,
					'
						<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="showHoliday(' . $val->id . ')"><i class="icon-pencil7"></i></button>
						<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyHoliday(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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

    public function datatableCompany(Request $request){
		$column = [
            'id',
			'name',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = CompanyEntity::count();
        
        $query_data = CompanyEntity::where(function($query) use ($search, $request) {
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

        $total_filtered = CompanyEntity::where(function($query) use ($search, $request) {
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
					'
						<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="showCompany(' . $val->id . ')"><i class="icon-pencil7"></i></button>
						<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyCompany(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
	
	public function createSchedule(Request $request){
		
		$validation = Validator::make($request->all(), [
			'branch'  		=> 'required',
			'day'  			=> 'required',
			'in_time'  		=> 'required',
			'out_time'   	=> 'required',
		], [
			'branch.required'  			=> 'Branch cannot be empty.',
			'day.required'  			=> 'Day cannot be empty.',
			'in_time.required'   		=> 'In time cannot be empty.',
			'out_time.required'   		=> 'Out time cannot be empty.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->tempSchedule){
				$query = Schedule::find($request->tempSchedule);
				
				$query->update([
					'branch'  		=> $request->branch,
					'day'  			=> $request->day,
					'in_time'  		=> $request->in_time,
					'out_time'  	=> $request->out_time
				]);
				
			}else{
				$query = Schedule::create([
					'branch'	    => $request->branch,
					'day'			=> $request->day,
					'in_time'  		=> $request->in_time,
					'out_time'  	=> $request->out_time,
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Schedule())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit schedule data by user '.session('bo_name'));

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

    public function createCompany(Request $request){
		
		$validation = Validator::make($request->all(), [
			'name'  		=> 'required',
		], [
			'name.required'  			=> 'Name cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			if($request->tempCompany){
				$query = CompanyEntity::find($request->tempSchedule);
				
				$query->update([
					'name'  		=> $request->name,
				]);
				
			}else{
				$query = CompanyEntity::create([
					'branch'	    => $request->branch,
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new CompanyEntity())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit schedule data by user '.session('bo_name'));

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
	
	public function showSchedule(Request $request){
		$data = Schedule::find($request->id);
		
        return response()->json($data);
	}
	
	public function showCompany(Request $request){
		$data = CompanyEntity::find($request->id);
		
        return response()->json($data);
	}
	
	public function createHoliday(Request $request){
		
		$validation = Validator::make($request->all(), [
			'date'  			=> 'required',
			'holiday_branch'	=> 'required',
			'description'  		=> 'required',
		], [
			'date.required'  			=> 'Date cannot be empty.',
			'holiday_branch.required'	=> 'Branch cannot be empty.',
			'description.required'  	=> 'Description cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->tempHoliday){
				$query = Holiday::find($request->tempHoliday);
				
				$query->update([
					'date'  		=> $request->date,
					'branch'		=> $request->holiday_branch,
					'description'  	=> $request->description,
				]);
				
			}else{
				$query = Holiday::create([
					'date'  		=> $request->date,
					'branch'		=> $request->holiday_branch,
					'description'  	=> $request->description,
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Holiday())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit holiday data by user '.session('bo_name'));

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
	
	public function showHoliday(Request $request){
		$data = Holiday::find($request->id);
		
        return response()->json($data);
	}
	
	public function destroySchedule(Request $request) 
    {
        $query = Schedule::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new Schedule())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Schedule data');

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
	
	public function destroyHoliday(Request $request) 
    {
        $query = Holiday::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new Holiday())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Holiday data');

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

	public function destroyCompany(Request $request) 
    {
        $query = CompanyEntity::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new CompanyEntity())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the company data');

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