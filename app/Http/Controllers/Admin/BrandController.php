<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Brand',
            'content' => 'admin.master_data.product.brand'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'image',
            'code',
            'name',
            'order',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Brand::count();
        
        $query_data = Brand::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%");
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

        $total_filtered = Brand::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%");
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
                    $image,
                    $val->code,
                    $val->name,
                    $val->order,
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
            'image'  => 'image|mimes:jpg,jpeg,png|max:150|dimensions:max_width=320,max_height=320',
            'code'   => 'required|unique:brands,code',
            'name'   => 'required',
            'order'  => 'required',
            'status' => 'required'
        ], [
            'image.image'      => 'File must be an image.',
            'image.mimes'      => 'Image must have an extension jpg, jpeg, png.',
            'image.max'        => 'Image max 150KB.',
            'image.dimensions' => 'Image max size 320x320.',
            'name.required'    => 'Name cannot be empty.',
            'order.required'   => 'Order cannot be empty.',
            'code.required'    => 'Code cannot be empty.',
            'code.unique'      => 'Code already exists.',
            'status.required'  => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Brand::create([
                'image'  => $request->has('image') ? $request->file('image')->store('public/brand') : null,
                'code'   => $request->code,
                'name'   => $request->name,
                'order'  => $request->order,
                'status' => $request->status
            ]);

            if($query) {
				
				$brand = Brand::all();
				
                activity()
                    ->performedOn(new Brand())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master brand data');

                $response = [
                    'status'  => 200,
					'brand' => $brand,
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
        $data = Brand::find($request->id);
        return response()->json([
            'image'  => Storage::exists($data->image) ? asset(Storage::url($data->image)) : asset('website/empty.jpg'),
            'code'   => $data->code,
            'name'   => $data->name,
            'order'  => $data->order,
            'status' => $data->status
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'image'  => 'image|mimes:jpg,jpeg,png|max:150|dimensions:max_width=320,max_height=320',
            'code'   => ['required', Rule::unique('brands', 'code')->ignore($id)],
            'name'   => 'required',
            'status' => 'required'
        ], [
            'image.image'      => 'File must be an image.',
            'image.mimes'      => 'Image must have an extension jpg, jpeg, png.',
            'image.max'        => 'Image max 150KB.',
            'image.dimensions' => 'Image max size 320x320.',
            'name.required'    => 'Name cannot be empty.',
            'order.required'   => 'Order cannot be empty.',
            'code.required'    => 'Code cannot be empty.',
            'code.unique'      => 'Code already exists.',
            'name.required'    => 'Name cannot be empty.',
            'status.required'  => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Brand::find($id);
            
            if($request->has('image')) {
                if(Storage::exists($query->image)) {
                    Storage::delete($query->image);
                }

                $image = $request->file('image')->store('public/brand');
            } else {
                $image = $query->image;
            }

            $query->update([
                'image'  => $image,
                'code'   => $request->code,
                'name'   => $request->name,
                'order'  => $request->order,
                'status' => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Brand())
                    ->causedBy(session('bo_id'))
                    ->log('Change the brand master data');

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
        $query = Brand::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Brand())
                ->causedBy(session('bo_id'))
                ->log('Delete the brand master data');

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
