<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    public function index()
    {
        $data = [
            'title'   => 'Bank',
            'parent'  => Bank::all(),
            'content' => 'admin.master_data.finance_accounting.bank'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'code',
            'name',
            'address',
			'branch',
            'account_number',
			'bank_swift_code',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Bank::count();
        
        $query_data = Bank::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('address', 'like', "%$search%")
							->orWhere('branch', 'like', "%$search%")
                            ->orWhere('account_number', 'like', "%$search%")
							->orWhere('bank_swift_code', 'like', "%$search%");
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

        $total_filtered = Bank::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('address', 'like', "%$search%")
							->orWhere('branch', 'like', "%$search%")
                            ->orWhere('account_number', 'like', "%$search%")
							->orWhere('bank_swift_code', 'like', "%$search%");
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
                    $val->address,
					$val->branch,
                    $val->account_number,
					$val->bank_swift_code,
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
            'code'              => 'required|unique:banks,code',
            'name'              => 'required',
            'address'           => 'required',
			'branch'           	=> 'required',
            'account_number'    => 'required|numeric',
			'bank_swift_code'   => 'required',
            'status'            => 'required'
        ], [
            'code.required'             => 'Code cannot be empty.',
            'code.unique'               => 'Code already exists.',
            'name.required'             => 'Name cannot be empty.',
            'address.required'          => 'Address cannot be empty.',
			'branch.required'          	=> 'Branch cannot be empty.',
            'account_number.required'   => 'Account number cannot be empty.',
            'account_number.numeric'    => 'Account number only accepts numeric characters.',
			'bank_swift_code.required'  => 'Bank Swift Code cannot be empty.',
            'status.required'           => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Bank::create([
                'code'      => str_replace(',', '.', $request->code),
                'name'      => $request->name,
                'address'   => $request->address,
				'branch'   => $request->branch,
                'account_number' => $request->account_number,
				'bank_swift_code'   => $request->bank_swift_code,
                'status'    => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Bank())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add accounting bank data');

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
        $data = Bank::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'code'              => ['required', Rule::unique('banks', 'code')->ignore($id)],
            'name'              => 'required',
            'address'           => 'required',
			'branch'           	=> 'required',
            'account_number'    => 'required|numeric',
			'bank_swift_code'   => 'required',
            'status'            => 'required'
        ], [
            'code.required'             => 'Code cannot be empty.',
            'code.unique'               => 'Code already exists.',
            'name.required'             => 'Name cannot be empty.',
            'address.required'          => 'Address cannot be empty.',
			'branch.required'          	=> 'Branch cannot be empty.',
            'account_number.required'   => 'Account number cannot be empty.',
            'account_number.numeric'    => 'Account number only accepts numeric characters.',
			'bank_swift_code.required'  => 'Bank Swift Code cannot be empty.',
            'status.required'           => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Bank::where('id', $id)->update([
                'code'      => str_replace(',', '.', $request->code),
                'name'      => $request->name,
                'address'   => $request->address,
				'branch'   => $request->branch,
                'account_number' => $request->account_number,
				'bank_swift_code' => $request->bank_swift_code,
                'status'    => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Bank())
                    ->causedBy(session('bo_id'))
                    ->log('Change the accounting bank data');

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
        $query = Bank::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Bank())
                ->causedBy(session('bo_id'))
                ->log('Delete the accounting bank data');

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
