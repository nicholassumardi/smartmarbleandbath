<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\FolderContent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Str;

class FolderController extends Controller {

    public function index()
    {
        $data = [
            'title'     => 'My Folder',
            'content'   => 'admin.folder'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'name'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Folder::count();
        
        $query_data = Folder::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name','like',"%$search%");
                    })->orWhereHas('folderContents', function($query) use ($search){
						$query->where('filename','like',"%$search%");
					});
                }
            })
			->where('user_id',session('bo_id'))
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Folder::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name','like',"%$search%");
                    })->orWhereHas('folderContents', function($query) use ($search){
						$query->where('filename','like',"%$search%");
					});
                }
            })
			->where('user_id',session('bo_id'))
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $val->name,
                    '
						<a href="'.url('admin/folder/detail/').'/'.base64_encode($val->id).'" class="btn bg-info btn-sm" data-popup="tooltip" title="Add Files To Folder"><i class="icon-file-plus"></i></a>
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ',`'.$val->name.'`)"><i class="icon-pencil7"></i></button>
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
            'folder_name'		=> 'required'
        ], [
            'folder_name.required' 	=> 'Folder name is required.'
        ]);
		
		if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			if($request->folder_id){
				$query = Folder::find($request->folder_id)->update([
					'user_id'	=> session('bo_id'),
					'name'		=> $request->folder_name
				]);
			}else{
				$query = Folder::create([
					'user_id'	=> session('bo_id'),
					'code'		=> strtoupper(Str::random(15)),	
					'name'		=> $request->folder_name
				]);
			}
			
            if($query) {
                activity()
                    ->performedOn(new Folder())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add product code data');

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
	
	public function detail(Request $request, $id) 
    {
		$folder = Folder::find(base64_decode($id));
		
		if(!$folder){
			abort(404);
		}
		
		$data = [
            'title'     => 'My Folder Detail',
			'id'		=> base64_decode($id),
            'content'   => 'admin.folder_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function addFiles(Request $request){
		
		$custom_file_name = strtolower(Str::random(10).'_'.str_replace(' ','_',$request->file('file')->getClientOriginalName()));
		
		$query = FolderContent::create([
			'folder_id'		=> $request->id,
			'filename'		=> $request->file('file')->getClientOriginalName(),
			'filestore'		=> $request->file('file')->storeAs('public/file', $custom_file_name)
		]);
		
		return response()->json([
			'status'		=> 200,
			'message'		=> 'You have successfully upload the file.'
		]);
	}
	
	public function datatableDetail(Request $request) 
    {
		$id = $request->id;
		
        $column = [
			'filename',
			'filestore',
			'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = FolderContent::count();
        
        $query_data = FolderContent::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('filename','like',"%$search%");
                    });
                }
            })
			->where('folder_id',$id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = FolderContent::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('filename','like',"%$search%");
                    });
                }
            })
			->where('folder_id',$id)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $val->filename,
                    '<a href="'.$val->attachment().'" class="btn bg-success btn-sm" data-popup="tooltip" title="See File"><i class="icon-eye"></i></a>
					<a href="'.url('admin/folder/'.base64_encode($val->id).'/download').'" class="btn bg-info btn-sm" data-popup="tooltip" title="Download File" target="_blank"><i class="icon-download4"></i></a>',
					date('j M y',strtotime($val->created_at)),
                    '
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
	
	public function deleteFile(Request $request){
		$data = FolderContent::find($request->id);
		
		$data->deleteFile();
		
		$data->delete();
		
		if($data){
			return response()->json([
				'status'	=> 200,
				'message'	=> 'Picture successfully deleted.' 
			]);
		}else{
			return response()->json([
				'status'	=> 422,
				'message'	=> 'Picture not found.'
			]);
		}
	}
	
	public function deleteFolder(Request $request){
		$data = Folder::find($request->id);
		
		foreach($data->folderContents as $row){
			$row->deleteFile();
			$row->delete();
		}
		
		$data->delete();
		
		if($data){
			return response()->json([
				'status'	=> 200,
				'message'	=> 'Folder successfully deleted.' 
			]);
		}else{
			return response()->json([
				'status'	=> 422,
				'message'	=> 'Folder not found.'
			]);
		}
	}
	
	public function rowDetail(Request $request)
    {
        $data   = FolderContent::where('folder_id',$request->id)->get();
		
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>File Name</th>
							<th>Created At</th>
							<th>See & Download</th>
						</tr>
					</thead>
					<tbody>';
		$no = 1;
		
		foreach($data as $val){
			$string .= '
				<tr>
					<td>'.$no.'.</td>
					<td class="text-center">'.$val->filename.'</td>
					<td class="text-center">'.date('j M y',strtotime($val->created_at)).'</td>
					<td class="text-center">
						<a href="'.$val->attachment().'" class="btn bg-success btn-sm" data-popup="tooltip" title="See File"><i class="icon-eye"></i></a>
						<a href="'.url('admin/folder/'.base64_encode($val->id).'/download').'" class="btn bg-info btn-sm" data-popup="tooltip" title="Download File" target="_blank"><i class="icon-download4"></i></a>
					</td>
				</tr>
			';
			$no++;
		}

        $string .= '
				</tbody>
			</table>
		';
		
        return response()->json($string);
    }
	
	public function hrdIndex()
    {
        $data = [
            'title'     => 'Employee Files',
            'content'   => 'admin.hrd.files'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function hrdDatatable(Request $request) 
    {
        $column = [
            'id',
			'user_id',
			'department',
			'name',
			'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Folder::count();
        
        $query_data = Folder::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name','like',"%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('folderContents', function($query) use ($search){
						$query->where('filename','like',"%$search%");
					});
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Folder::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name','like',"%$search%");
                    })->orWhereHas('user', function($query) use ($search) {
						$query->where('name','like',"%$search%");
					})->orWhereHas('folderContents', function($query) use ($search){
						$query->where('filename','like',"%$search%");
					});
                }
            })
			->where('user_id',session('bo_id'))
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$role = '';
				
				foreach($val->user->userRole as $key => $row){
					$role .= ($key + 1).'. '.$row->role().'<br>';
				}
				
                $response['data'][] = [
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
					$val->user->name,
					$role,
                    $val->name,
					date('d M Y, H:i:s',strtotime($val->created_at)),
					'
						<a href="'.url('admin/hrd/files/detail/').'/'.base64_encode($val->id).'" class="btn bg-info btn-sm" data-popup="tooltip" title="Add Files To Folder"><i class="icon-file-plus"></i></a>
					'
                    /* '
						<a href="'.url('admin/folder/detail/').'/'.base64_encode($val->id).'" class="btn bg-info btn-sm" data-popup="tooltip" title="Add Files To Folder"><i class="icon-file-plus"></i></a>
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ',`'.$val->name.'`)"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    ' */
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
	
	public function hrdRowDetail(Request $request)
    {
        $data   = FolderContent::where('folder_id',$request->id)->get();
		
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>File Name</th>
							<th>Created At</th>
							<th>See & Download</th>
						</tr>
					</thead>
					<tbody>';
		$no = 1;
		
		foreach($data as $val){
			$string .= '
				<tr>
					<td>'.$no.'.</td>
					<td class="text-center">'.$val->filename.'</td>
					<td class="text-center">'.date('j M y',strtotime($val->created_at)).'</td>
					<td class="text-center">
						<a href="'.$val->attachment().'" class="btn bg-success btn-sm" data-popup="tooltip" title="See File"><i class="icon-eye"></i></a>
						<a href="'.url('admin/hrd/files/'.base64_encode($val->id).'/download').'" class="btn bg-info btn-sm" data-popup="tooltip" title="Download File" target="_blank"><i class="icon-download4"></i></a>
					</td>
				</tr>
			';
			$no++;
		}

        $string .= '
				</tbody>
			</table>
		';
		
        return response()->json($string);
    }
	
	public function hrdDetail(Request $request, $id) 
    {
		$folder = Folder::find(base64_decode($id));
		
		if(!$folder){
			abort(404);
		}
		
		$data = [
            'title'     => 'My Folder Detail',
			'id'		=> base64_decode($id),
            'content'   => 'admin.hrd.files_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function hrdDatatableDetail(Request $request) 
    {
		$id = $request->id;
		
        $column = [
			'filename',
			'filestore',
			'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = FolderContent::count();
        
        $query_data = FolderContent::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('filename','like',"%$search%");
                    });
                }
            })
			->where('folder_id',$id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = FolderContent::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('filename','like',"%$search%");
                    });
                }
            })
			->where('folder_id',$id)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $val->filename,
                    '<a href="'.$val->attachment().'" class="btn bg-success btn-sm" data-popup="tooltip" title="See File"><i class="icon-eye"></i></a>
					<a href="'.url('admin/hrd/files/'.base64_encode($val->id).'/download').'" class="btn bg-info btn-sm" data-popup="tooltip" title="Download File" target="_blank"><i class="icon-download4"></i></a>',
					date('j M y',strtotime($val->created_at))
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
	
	public function downloadFile($id) 
    {
		$file = FolderContent::find(base64_decode($id));
		
		if(!$file){
			abort(404);
		}
		
		$pathToFile = storage_path('app/'.$file->filestore);
		return response()->download($pathToFile);
	}
}