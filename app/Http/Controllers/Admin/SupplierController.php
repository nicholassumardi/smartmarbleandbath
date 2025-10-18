<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SupplierCurrency;
use App\Http\Controllers\Controller;
use App\Models\ProjectPurchase;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    => 'Supplier',
            'country'  => Country::where('status', 1)->get(),
            'currency' => Currency::where('status', 1)->get(),
            'content'  => 'admin.master_data.product.supplier'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'code',
            'name',
            'country_id',
            'limit_credit',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Supplier::count();
        
        $query_data = Supplier::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhereHas('country', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
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

        $total_filtered = Supplier::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhereHas('country', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
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
                    $val->country->name,
                    number_format($val->limit_credit, 2, ',', '.'),
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
            'country_id'      => 'required',
            'name'            => 'required',
            //'email'           => 'required|email|unique:suppliers,email',
            //'phone'           => 'required|numeric|unique:suppliers,phone',
            'pic'             => 'required',
            'limit_credit'    => 'required',
            'term_of_payment' => 'required',
            'currency_id'     => 'required',
			'ppn'          	  => 'required',
            'status'          => 'required'
        ], [
            'country_id.required'      => 'Please select a country.',
            'name.required'            => 'Name cannot be empty.',
            //'email.required'           => 'Email cannot be empty.',
            //'email.email'              => 'Email not valid.',
            //'email.unique'             => 'Code already exists.',
            //'phone.required'           => 'Phone cannot be empty.',
            //'phone.numeric'            => 'Phone must be a number.',
            //'phone.unique'             => 'Phone already exists.',
            'pic.required'             => 'PIC cannot be empty.',
            'limit_credit.required'    => 'Limit credit cannot be empty.',
            'term_of_payment.required' => 'Term of payment cannot be empty.',
            'currency_id.required'     => 'please choose one currency.',
			'ppn.required'             => 'Please select a ppn.',
            'status.required'          => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Supplier::create([
                'country_id'      => $request->country_id,
                'code'            => Supplier::generateCode(),
                'name'            => $request->name,
                'email'           => $request->email,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'pic'             => $request->pic,
                'limit_credit'    => str_replace(',', '', $request->limit_credit),
                'term_of_payment' => $request->term_of_payment,
				'isppn'           => $request->ppn,
                'status'          => $request->status
            ]);

            if($query) {
                if($request->currency_id) {
                    foreach($request->currency_id as $c) {
                        SupplierCurrency::create([
                            'supplier_id' => $query->id,
                            'currency_id' => $c
                        ]);
                    }
                }
					
				$supplier = Supplier::all();
				
                activity()
                    ->performedOn(new Supplier())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master supplier data');

                $response = [
                    'status'  	=> 200,
					'supplier'	=> $supplier,
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
        $data     = Supplier::find($request->id);
        $currency_id = [];

        foreach($data->supplierCurrency as $c) {
            $currency_id[] = $c->currency_id;
        } 

        return response()->json([
            'country_id'      => $data->country_id,
            'code'            => $data->code,
            'name'            => $data->name,
            'email'           => $data->email,
            'phone'           => $data->phone,
            'address'         => $data->address,
            'pic'             => $data->pic,
            'limit_credit'    => $data->limit_credit,
            'term_of_payment' => $data->term_of_payment,
            'status'          => $data->status,
			'ppn'          	  => $data->isppn,
            'currency_id'     => $currency_id
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'country_id'      => 'required',
            'name'            => 'required',
            //'email'           => ['required', 'email', Rule::unique('suppliers', 'email')->ignore($id)],
            //'phone'           => ['required', 'numeric', Rule::unique('suppliers', 'phone')->ignore($id)],
            'pic'             => 'required',
            'limit_credit'    => 'required',
            'term_of_payment' => 'required',
            'currency_id'     => 'required',
			'ppn'          	  => 'required',
            'status'          => 'required'
        ], [
            'country_id.required'      => 'Please select a country.',
            'name.required'            => 'Name cannot be empty.',
            //'email.required'           => 'Email cannot be empty.',
            //'email.email'              => 'Email not valid.',
            //'email.unique'             => 'Code already exists.',
            //'phone.required'           => 'Phone cannot be empty.',
            //'phone.numeric'            => 'Phone must be a number.',
            //'phone.unique'             => 'Phone already exists.',
            'pic.required'             => 'PIC cannot be empty.',
            'limit_credit.required'    => 'Limit credit cannot be empty.',
            'term_of_payment.required' => 'Term of payment cannot be empty.',
            'currency_id.required'     => 'please choose one currency.',
			'ppn.required'             => 'Please select a ppn.',
            'status.required'          => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Supplier::where('id', $id)->update([
                'country_id'      => $request->country_id,
                'name'            => $request->name,
                'email'           => $request->email,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'pic'             => $request->pic,
                'limit_credit'    => str_replace(',', '', $request->limit_credit),
                'term_of_payment' => $request->term_of_payment,
				'isppn' 		  => $request->ppn,
                'status'          => $request->status
            ]);

            if($query) {
                SupplierCurrency::where('supplier_id', $id)->delete();
                if($request->currency_id) {
                    foreach($request->currency_id as $c) {
                        SupplierCurrency::create([
                            'supplier_id' => $id,
                            'currency_id' => $c
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Supplier())
                    ->causedBy(session('bo_id'))
                    ->log('Change the supplier master data');

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
        $projectPurchase = ProjectPurchase::where('supplier_id', $request->id)->count();
        
        if($projectPurchase > 0){
            $response = [
                'status'  => 500,
                'message' => 'Data failed to delete, please change supplier on PO first before delete this data.'
            ];
        }else{
            $query = Supplier::where('id', $request->id)->delete();

            if($query) {
                activity()
                    ->performedOn(new Supplier())
                    ->causedBy(session('bo_id'))
                    ->log('Delete the supplier master data');
    
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
        }
       

        return response()->json($response);
    }

}
