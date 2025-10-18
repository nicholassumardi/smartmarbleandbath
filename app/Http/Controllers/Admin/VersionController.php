<?php

namespace App\Http\Controllers\Admin;

use App\Models\Version;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VersionController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Version',
            'content' => 'admin.setting.version'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
			'version',
			'released_date',
			'changelog'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Version::count();
        
        $query_data = Version::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('version', 'like', "%$search%")
						->orWhere('released_date', 'like', "%$search%")
						->orWhere('changelog', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Version::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('version', 'like', "%$search%")
						->orWhere('released_date', 'like', "%$search%")
						->orWhere('changelog', 'like', "%$search%");
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
                    $val->version,
                    date('d M Y',strtotime($val->released_date)),
					$val->changelog,
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
			'version'  		=> 'required',
			'released_date'	=> 'required',
			'changelog'  	=> 'required',
		], [
			'version.required'  			=> 'Version cannot be empty.',
			'released_date.required'		=> 'Released date cannot be empty.',
			'changelog.required'  			=> 'Changelog cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = Version::find($request->temp);
				
				$query->update([
					'user_id'		=> session('bo_id'),
					'version'  		=> $request->version,
					'released_date'	=> $request->released_date,
					'changelog'  	=> $request->changelog,
				]);
				
			}else{
				$query = Version::create([
					'user_id'		=> session('bo_id'),
					'version'  		=> $request->version,
					'released_date'	=> $request->released_date,
					'changelog'  	=> $request->changelog
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Version())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit version data by user '.session('bo_name'));

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
	
	public function show(Request $request){
		$data = Version::find($request->id);
		
        return response()->json($data);
	}
	
	public function destroy(Request $request) 
    {
        $query = Version::find($request->id);
		
        if($query) {
			$query->delete();
			
            activity()
                ->performedOn(new Version())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Version data');

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
	
	public function getLatestVersion(Request $request){
		$data = Version::where('released_date','<=',date('Y-m-d'))->orderByDesc('version')->get();
		
		$changelog = '<div class="timeline timeline-right"><div class="timeline-container">';
		
		foreach($data as $row){
			$changelog .= '<div class="timeline-date text-muted">
								<i class="icon-history mr-2"></i> <span class="font-weight-semibold">'.date("l",strtotime($row->released_date)).'</span>, '.date("F j, Y",strtotime($row->released_date)).'
							</div>';
			$changelog .= '
				<div class="timeline-row">
					<div class="timeline-icon">
						<div class="bg-info-400">
							<i class="icon-versions"></i>
						</div>
					</div>

					<div class="card">
						<div class="card-header header-elements-inline" style="background-color:white !important;">
							<h6 class="card-title">V.'.$row->version.'</h6>
							<div class="header-elements">
								
							</div>
						</div>

						<div class="card-body" style="background-color:white !important;">
							'.$row->changelog.'
						</div>
					</div>
				</div>
			';
		}
		
		$changelog .= '</div></div>';
		
		$result = [
			'status'	=> 200,
			'version'	=> $data ? $data[0]->version : 'Empty',
			'changelog'	=> $changelog
		];
		
        return response()->json($result);
	}
}