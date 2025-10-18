<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Banner',
            'content' => 'admin.master_data.digital.banner'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'image',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Banner::count();
        
        $query_data = Banner::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('image', 'like', "%$search%");
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

        $total_filtered = Banner::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('image', 'like', "%$search%");
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
                $image = '<a href="' . $val->image() . '" data-lightbox="' . $val->image() . '" data-title="' . $val->image() . '"><img src="' . $val->image() . '" style="max-width:170px;" class="img-fluid img-thumbnail"></a>';

                $response['data'][] = [
                    $nomor,
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
            //'image'  => 'required|image|mimes:jpg,jpeg,png|max:500|dimensions:width=1920,height=800',
            'status' => 'required'
        ], [
            /* 'image.required'   => 'Image cannot be empty.',
            'image.image'      => 'File must be an image.',
            'image.mimes'      => 'Image must have an extension jpg, jpeg, png.',
            'image.max'        => 'Image max 500KB.',
            'image.dimensions' => 'Image size must be 1920x800.', */
            'status.required'  => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Banner::create([
                'image'  => $request->has('image') ? $request->file('image')->store('public/banner') : null,
                'status' => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Banner())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master banner data');

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
        $data = Banner::find($request->id);
        return response()->json([
            'image'  => $data->image(),
            'status' => $data->status
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            //'image'  => 'image|mimes:jpg,jpeg,png|max:500|dimensions:width=1920,height=800',
            'status' => 'required'
        ], [
            /* 'image.image'      => 'File must be an image.',
            'image.mimes'      => 'Image must have an extension jpg, jpeg, png.',
            'image.max'        => 'Image max 500KB.',
            'image.dimensions' => 'Image size must be 1920x800.', */
            'status.required'  => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Banner::find($id);
            
            if($request->has('image')) {
                if(Storage::exists($query->image)) {
                    Storage::delete($query->image);
                }

                $image = $request->file('image')->store('public/banner');
            } else {
                $image = $query->image;
            }

            $query->update([
                'image'  => $image,
                'status' => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Banner())
                    ->causedBy(session('bo_id'))
                    ->log('Change the banner master data');

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
        $query = Banner::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Banner())
                ->causedBy(session('bo_id'))
                ->log('Delete the banner master data');

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
