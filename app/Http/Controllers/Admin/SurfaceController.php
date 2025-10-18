<?php

namespace App\Http\Controllers\Admin;

use App\Models\Surface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SurfaceController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Surface',
            'content' => 'admin.master_data.product.surface'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'code',
            'name',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Surface::count();
        
        $query_data = Surface::where(function($query) use ($search, $request) {
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

        $total_filtered = Surface::where(function($query) use ($search, $request) {
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
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->name,
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
            'code'   => 'required|unique:surfaces,code',
            'name'   => 'required',
            'status' => 'required'
        ], [
            'code.required'   => 'Code cannot be empty.',
            'code.unique'     => 'Code already exists.',
            'name.required'   => 'Name cannot be empty.',
            'status.required' => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Surface::create([
                'code'   => $request->code,
                'name'   => $request->name,
                'status' => $request->status
            ]);

            if($query) {
				$surface = Surface::where('status', 1)->get();
				
                activity()
                    ->performedOn(new Surface())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master surface data');

                $response = [
                    'status'  => 200,
					'surface' => $surface,
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
        $data = Surface::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'code'   => ['required', Rule::unique('surfaces', 'code')->ignore($id)],
            'name'   => 'required',
            'status' => 'required'
        ], [
            'code.required'   => 'Code cannot be empty.',
            'code.unique'     => 'Code already exists.',
            'name.required'   => 'Name cannot be empty.',
            'status.required' => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Surface::where('id', $id)->update([
                'code'   => $request->code,
                'name'   => $request->name,
                'status' => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Surface())
                    ->causedBy(session('bo_id'))
                    ->log('Change the surface master data');

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
        $query = Surface::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Surface())
                ->causedBy(session('bo_id'))
                ->log('Delete the surface master data');

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
