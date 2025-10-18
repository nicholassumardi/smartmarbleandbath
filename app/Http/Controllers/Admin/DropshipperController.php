<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dropshipper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DropshipperController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Dropshipper',
            'content' => 'admin.master_data.delivery.dropshipper'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'name',
            'address',
			'phone',
			'pic',
			'email',
			'image',
			'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Dropshipper::count();
        
        $query_data = Dropshipper::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('address', 'like', "%$search%")
							->orWhere('phone', 'like', "%$search%")
							->orWhere('pic', 'like', "%$search%");
                    });
                }         
                
                if($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Dropshipper::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('address', 'like', "%$search%")
							->orWhere('phone', 'like', "%$search%")
							->orWhere('pic', 'like', "%$search%");
                    });
                }       
                
                if($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				$image = '<a href="' . $val->image() . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . $val->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
				
                $response['data'][] = [
                    $nomor,
                    $val->name,
                    $val->address,
					$val->phone,
					$val->pic,
					$val->email,
					$image,
                    $val->status(),
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'   => 'required',
            'status' => 'required'
        ], [
            'name.required'   => 'Name cannot be empty.',
            'status.required' => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Dropshipper::create([
                'name'   	=> $request->name,
                'email'   	=> $request->email,
				'phone'  	=> $request->phone,
				'address'   => $request->address,
				'pic'		=> $request->pic,
				'image'  	=> $request->has('image') ? $request->file('image')->store('public/dropshipper') : null,
                'status' 	=> $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Dropshipper())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master dropshipper data');

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
        $data = Dropshipper::find($request->id);
        
		return response()->json([
            'image'  	=> Storage::exists($data->image) ? asset(Storage::url($data->image)) : asset('website/empty.jpg'),
            'name'   	=> $data->name,
            'email'  	=> $data->email,
            'phone'  	=> $data->phone,
			'address'  	=> $data->address,
			'pic'  		=> $data->pic,
            'status' 	=> $data->status
        ]);
    }

    public function update(Request $request, $id)
    {
		$validation = Validator::make($request->all(), [
            'name'   => 'required',
            'status' => 'required'
        ], [
            'name.required'   => 'Name cannot be empty.',
            'status.required' => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Dropshipper::find($id);
            
            if($request->has('image')) {
                if(Storage::exists($query->image)) {
                    Storage::delete($query->image);
                }

                $image = $request->file('image')->store('public/dropshipper');
            } else {
                $image = $query->image;
            }

            $query->update([
                'name'   	=> $request->name,
                'email'   	=> $request->email,
				'phone'  	=> $request->phone,
				'address'   => $request->address,
				'pic'		=> $request->pic,
				'image'  	=> $image,
                'status' 	=> $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Dropshipper())
                    ->causedBy(session('bo_id'))
                    ->log('Change the dropshipper master data');

                $response = [
                    'status'  => 200,
                    'message' => 'Data updated successfully.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data failed to update.'
                ];
            }
        }

        return response()->json($response);
    }

    public function destroy(Request $request) 
    {
        $query = Dropshipper::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Dropshipper())
                ->causedBy(session('bo_id'))
                ->log('Delete the dropshipper master data');

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
