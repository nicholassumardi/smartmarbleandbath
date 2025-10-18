<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asset;
use App\Models\Coa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller {
    
    public function index(Request $request)
    {
        $data = [
            'title'   	=> 'Asset',
			'coa'		=> Coa::where('parent_id',61)->get(),
            'content' 	=> 'admin.master_data.hrd.asset'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'name',
			'type',
			'starting_value',
			'percent_depreciation',
			'date',
			'image',
			'coa_id'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Asset::count();
        
        $query_data = Asset::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
						->orWhere('starting_value', 'like', "%$search%")
						->orWhere('percent_depreciation', 'like', "%$search%")
						->orWhere('date', 'like', "%$search%")
						->orWhereHas('coa', function($query) use($search,$request){
							$query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%");
						});
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Asset::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
						->orWhere('starting_value', 'like', "%$search%")
						->orWhere('percent_depreciation', 'like', "%$search%")
						->orWhere('date', 'like', "%$search%")
						->orWhereHas('coa', function($query) use($search,$request){
							$query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%");
						});
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				if(Storage::exists($val->image)) {
					if(in_array(explode('.',$val->image)[1],array('pdf','doc','docx','xls','xlsx'))){
						$photo = '<a href="'.asset(Storage::url($val->image)).'" class="btn bg-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photo = '<a href="' . asset(Storage::url($val->image)) . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . asset(Storage::url($val->image)) . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
					}
                    
                } else {
                    $photo = '<a href="' . asset('website/empty.jpg') . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . asset('website/empty.jpg') . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
                }
				
                $response['data'][] = [
                    $nomor,
                    $val->name,
                    $val->type(),
					number_format($val->starting_value,2,',','.'),
					$val->percent_depreciation.'%',
					date('d M Y',strtotime($val->date)),
					$photo,
					$val->coa->name,
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
			'name'  				=> 'required',
			'type'  				=> 'required',
			'starting_value'  		=> 'required',
			'percent_depreciation'  => 'required',
			'date'					=> 'required',
		], [
			'name.required'  				=> 'Name cannot be empty.',
			'type.required'  				=> 'Type cannot be empty.',
			'starting_value.required'  		=> 'Value cannot be empty.',
			'percent_depreciation.required' => 'Percent dpr. cannot be empty.',
			'date.required'  				=> 'Date cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = Asset::find($request->temp);
				
				if($request->has('file')) {
					if(Storage::exists($query->image)) {
						Storage::delete($query->image);
					}

					$image = $request->file('file')->store('public/asset');
				} else {
					$image = $query->image;
				}
				
				$query->update([
					'user_id'	    		=> session('bo_id'),
					'name'					=> $request->name,
					'type'					=> $request->type,
					'starting_value'  		=> str_replace(',','.',str_replace('.','',$request->starting_value)),
					'percent_depreciation'  => $request->percent_depreciation,
					'date'  				=> $request->date,
					'image'					=> $image,
					'coa_id'				=> $request->coa_id
				]);
				
			}else{
				$query = Asset::create([
					'user_id'	    		=> session('bo_id'),
					'name'					=> $request->name,
					'type'					=> $request->type,
					'starting_value'  		=> str_replace(',','.',str_replace('.','',$request->starting_value)),
					'percent_depreciation'  => $request->percent_depreciation,
					'date'  				=> $request->date,
					'image'					=> $request->has('file') ? $request->file('file')->store('public/asset') : NULL,
					'coa_id'				=> $request->coa_id
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new Asset())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit work asset data by user '.session('bo_name'));

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
        $data = Asset::find($request->id);
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = Asset::find($request->id);
		
        if($query) {
			$query->deleteFile();
			$query->delete();
			
            activity()
                ->performedOn(new Asset())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Asset data');

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