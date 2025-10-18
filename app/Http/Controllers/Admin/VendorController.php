<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;

class VendorController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Delivery Company',
            'content' => 'admin.master_data.delivery.delivery_company'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'code',
            'name',
            'pic',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Vendor::count();
        
        $query_data = Vendor::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
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

        $total_filtered = Vendor::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
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
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->name,
                    $val->pic,
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
            'email'  => 'required|email|unique:vendors,email',
            'phone'  => 'required|min:9|numeric|unique:vendors,phone',
            'pic'    => 'required',
            'status' => 'required'
        ], [
            'name.required'   => 'Name cannot be empty.',
            'email.required'  => 'Email cannot be empty',
            'email.email'     => 'Email not valid',
            'email.unique'    => 'Email exists',
            'phone.required'  => 'Phone cannot be empty',
            'phone.min'       => 'Phone must be at least 9 characters long',
            'phone.numeric'   => 'Phone must be number',
            'phone.unique'    => 'Phone exists',
            'pic.required'    => 'PIC cannot be empty.',
            'status.required' => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Vendor::create([
                'code'    => Vendor::generateCode(),
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
                'pic'     => $request->pic,
                'status'  => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Vendor())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add delivery vendor data');

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
        $data = Vendor::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'   => 'required',
            'email'  => ['required', 'email', Rule::unique('vendors', 'email')->ignore($id)],
            'phone'  => ['required', 'min:9', 'numeric', Rule::unique('vendors', 'phone')->ignore($id)],
            'pic'    => 'required',
            'status' => 'required'
        ], [
            'name.required'   => 'Name cannot be empty.',
            'email.required'  => 'Email cannot be empty',
            'email.email'     => 'Email not valid',
            'email.unique'    => 'Email exists',
            'phone.required'  => 'Phone cannot be empty',
            'phone.min'       => 'Phone must be at least 9 characters long',
            'phone.numeric'   => 'Phone must be number',
            'phone.unique'    => 'Phone exists',
            'pic.required'    => 'PIC cannot be empty.',
            'status.required' => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Vendor::where('id', $id)->update([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
                'pic'     => $request->pic,
                'status'  => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Vendor())
                    ->causedBy(session('bo_id'))
                    ->log('Change the vendor delivery data');

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
        $query = Vendor::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Vendor())
                ->causedBy(session('bo_id'))
                ->log('Delete the vendor delivery data');

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
