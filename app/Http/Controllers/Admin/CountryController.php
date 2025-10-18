<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Country',
            'content' => 'admin.master_data.product.country'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'code',
            'name',
            'phone_code',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Country::count();
        
        $query_data = Country::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('phone_code', 'like', "%$search%");
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

        $total_filtered = Country::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('phone_code', 'like', "%$search%");
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
                    $val->phone_code,
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
            'code'       => 'required|unique:countries,code',
            'name'       => 'required',
            'phone_code' => 'required|unique:countries,code',
            'status'     => 'required'
        ], [
            'code.required'       => 'Code cannot be empty.',
            'code.unique'         => 'Code already exists.',
            'name.required'       => 'Name cannot be empty.',
            'phone_code.required' => 'Phone code cannot be empty.',
            'phone_code.unique'   => 'Phone code already exists.',
            'status.required'     => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Country::create([
                'code'       => $request->code,
                'name'       => $request->name,
                'phone_code' => $request->phone_code,
                'status'     => $request->status
            ]);

            if($query) {
				$country = Country::all();
				
                activity()
                    ->performedOn(new Country())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master country data');

                $response = [
                    'status'  	=> 200,
					'country'	=> $country,
                    'message' 	=> 'Data added successfully.'
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
        $data = Country::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'code'       => ['required', Rule::unique('countries', 'code')->ignore($id)],
            'name'       => 'required',
            'phone_code' => ['required', Rule::unique('countries', 'phone_code')->ignore($id)],
            'status'     => 'required'
        ], [
            'code.required'       => 'Code cannot be empty.',
            'code.unique'         => 'Code already exists.',
            'name.required'       => 'Name cannot be empty.',
            'phone_code.required' => 'Phone code cannot be empty.',
            'phone_code.unique'   => 'Phone code already exists.',
            'status.required'     => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Country::where('id', $id)->update([
                'code'       => $request->code,
                'name'       => $request->name,
                'phone_code' => $request->phone_code,
                'status'     => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Country())
                    ->causedBy(session('bo_id'))
                    ->log('Change the country master data');

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
        $query = Country::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Country())
                ->causedBy(session('bo_id'))
                ->log('Delete the country master data');

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
