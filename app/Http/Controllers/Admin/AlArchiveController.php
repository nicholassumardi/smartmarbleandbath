<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlArchiveSupplier;
use App\Models\AlArchiveSupplierDetail;
use App\Models\AlProject;
use App\Models\AlPurchase;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AlProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AlArchiveController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    	=> 'Al Arsip Supplier',
			'proyek'	=> AlProject::all(),
            'content'  	=> 'admin.al.archive.supplier'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
			'code',
            'al_project_id',
            'al_supplier_id',
            'berkas',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlArchiveSupplier::count();
        
        $query_data = AlArchiveSupplier::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%")
								->orWhereHas('alCustomer', function($query) use ($search) {
									 $query->where('name', 'like', "%$search%");
								});
                            })
							->orWhereHas('AlSupplier', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%")
									->orWhere('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlArchiveSupplier::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%")
								->orWhereHas('alCustomer', function($query) use ($search) {
									 $query->where('name', 'like', "%$search%");
								});
                            })
							->orWhereHas('AlSupplier', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%")
									->orWhere('name', 'like', "%$search%");
                            });
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
                    $val->code,
                    $val->alProject->code.' - '.$val->alProject->name,
					$val->alSupplier->name,
					'<a href="javascript:void(0);" data-popup="tooltip" title="Upload berkas supplier Proyek '.$val->alProject->name.'" onclick="addPictures('.$val->id.')" class="btn bg-success btn-sm"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->alArchiveSupplierDetail).'</span><i class="icon-images3"></i></a>',
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
	
	public function getSupplier(Request $request){
		$data = AlPurchase::where('al_project_id',$request->id)->get();

		$supplier = [];
		
		foreach($data as $row){
			$supplier[] = [
				'al_supplier_id'	=> $row->al_supplier_id,
				'al_supplier_name'	=> $row->alSupplier->name,
				'al_supplier_code'	=> $row->alSupplier->code
			];
 		}
		
		$collection = collect($supplier);
		
		$unique = $collection->unique('al_supplier_id')->values()->all();
		
		return response()->json([
			'supplier'	=> $unique
		]);
	}
	
	public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'al_project_id' 	=> 'required',
			'al_supplier_id' 	=> 'required'
        ], [
            'al_project_id.required' 	=> 'Al Project cannot be empty.',
			'al_supplier_id.required'	=> 'Al Supplier cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = AlArchiveSupplier::find($request->temp)->update([
					'user_id'			=> session('bo_id'),
					'al_project_id' 	=> $request->al_project_id,
					'al_supplier_id'	=> $request->al_supplier_id
				]);
			}else{
				$query = AlArchiveSupplier::create([
					'user_id'			=> session('bo_id'),
					'code'				=> strtoupper(Str::random(15)),
					'al_project_id' 	=> $request->al_project_id,
					'al_supplier_id'	=> $request->al_supplier_id
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new AlArchiveSupplier())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add Archive Project Supplier data');

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
        $data = AlArchiveSupplier::find($request->id);
		
        return response()->json([
			'data'					=> $data
        ]);
    }
	
	public function getPictures(Request $request){
		$data = AlArchiveSupplierDetail::where('al_archive_supplier_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$result[] = [
				'id'			=> $row->id,
				'filename'		=> $row->attachment(),
				'name'			=> $row->name
			];
		}
		
		return response()->json($result);
	}
	
	public function addPictures(Request $request){
		
		$count = AlArchiveSupplierDetail::where('al_archive_supplier_id',$request->id)->count();
		
		if($count >= 5){
			return response()->json([
				'status'		=> 422,
				'message'		=> 'You have reached maximum file uploads for this project.'
			]);
		}else{
			
			$custom_file_name = strtolower(Str::random(10).'_'.str_replace(' ','_',$request->file('file')->getClientOriginalName()));
			
			$query = AlArchiveSupplierDetail::create([
				'al_archive_supplier_id'	=> $request->id,
				'name'						=> $request->file('file')->getClientOriginalName(),
				'filename'					=> $request->file('file')->storeAs('public/al_archive/supplier', $custom_file_name)
			]);
			
			return response()->json([
				'status'		=> 200,
				'message'		=> 'You have successfully upload the file.'
			]);
		}
	}
	
	public function deletePictures(Request $request){
		$data = AlArchiveSupplierDetail::find($request->id);
		
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