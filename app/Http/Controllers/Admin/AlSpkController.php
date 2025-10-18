<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AlProject;
use App\Models\AlProjectSpk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AlSpkController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    => 'Al Arsip SPK TNI',
            'content'  => 'admin.al.archive.spk'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
            'al_project_id',
			'customer',
            'berkas',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlProject::count();
        
        $query_data = AlProject::where(function($query) use ($search, $request) {
                if($search) {
					$query->where('name', 'like', "%$search%")
						->orWhere('code', 'like', "%$search%")
						->orWhereHas('alCustomer', function($query) use ($search) {
							$query->where('name', 'like', "%$search%");
						});
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlProject::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('name', 'like', "%$search%")
						->orWhere('code', 'like', "%$search%")
						->orWhereHas('alCustomer', function($query) use ($search) {
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
                    $val->code.' - '.$val->name,
					$val->alCustomer->name,
					'<a href="javascript:void(0);" data-popup="tooltip" title="Upload berkas supplier Proyek '.$val->name.'" onclick="addPictures('.$val->id.')" class="btn bg-success btn-sm"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->alProjectSpk).'</span><i class="icon-images3"></i></a>'
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
	
	public function getPictures(Request $request){
		$data = AlProjectSpk::where('al_project_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$result[] = [
				'id'			=> $row->id,
				'filename'		=> $row->attachment(),
				'picture'		=> $row->picture(),
				'name'			=> $row->name
			];
		}
		
		return response()->json($result);
	}
	
	public function addPictures(Request $request){
		
		$count = AlProjectSpk::where('al_project_id',$request->id)->count();
		
		if($count >= 5){
			return response()->json([
				'status'		=> 422,
				'message'		=> 'You have reached maximum file uploads for this project.'
			]);
		}else{
			
			$custom_file_name = strtolower(Str::random(10).'_'.str_replace(' ','_',$request->file('file')->getClientOriginalName()));
			
			$query = AlProjectSpk::create([
				'user_id'			=> session('bo_id'),
				'al_project_id'		=> $request->id,
				'name'				=> $request->file('file')->getClientOriginalName(),
				'filename'			=> $request->file('file')->storeAs('public/al_archive/spk_tni', $custom_file_name)
			]);
			
			return response()->json([
				'status'		=> 200,
				'message'		=> 'You have successfully upload the file.'
			]);
		}
	}
	
	public function deletePictures(Request $request){
		$data = AlProjectSpk::find($request->id);
		
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

}