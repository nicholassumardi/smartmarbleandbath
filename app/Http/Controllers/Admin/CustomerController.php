<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Customer',
            'content' => 'admin.master_data.customer'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'photo',
            'name',
            'email',
            'phone',
            'type',
            'verification',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Customer::count();
        
        $query_data = Customer::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%");
                    });
                }

                if($request->type) {
                    $query->where('type', $request->type);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Customer::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%");
                    });
                }
                
                if($request->type) {
                    $query->where('type', $request->type);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $photo = '<a href="' . $val->photo() . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . $val->photo() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';

                $response['data'][] = [
                    $nomor,
                    $photo,
                    $val->name,
                    $val->email,
                    $val->phone,
                    $val->type(),
                    $val->verification ? date('d F Y', strtotime($val->created_at)) : 'Not Verified',
                    date('d F Y', strtotime($val->created_at)),
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
            'image'  => 'image|mimes:jpg,jpeg,png|max:100|dimensions:max_width=400,max_height=400',
            //'email'   => 'required|unique:customers,email',
            'name'   => 'required',
			'address'=> 'required',
            'phone'  => 'required|min:8',
			'finance_hp'  => 'required|min:8',
            'type'   => 'required'
        ], [
            'image.image'      => 'File must be an image.',
            'image.mimes'      => 'Image must have an extension jpg, jpeg, png.',
            'image.max'        => 'Image max 100KB.',
            'image.dimensions' => 'Image max size 400x400.',
            'name.required'    => 'Name cannot be empty.',
            /* 'email.required'   => 'Email cannot be empty.',
            'email.unique'     => 'Email has already been used.', */
			'address.required' => 'Address cannot be empty.',
            'phone.required'   => 'Phone number cannot be empty.',
			'phone.min'   		=> 'Phone number minimal 8 characters.',
			'finance_hp.required'   => 'Finance phone number cannot be empty.',
			'finance_hp.min'   		=> 'Finance phone number minimal 8 characters.',
            'type.required'    => 'Please select a type of user (online / offline).'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Customer::create([
                'city_id'  => $request->city_id,
                'photo'  => $request->has('image') ? $request->file('image')->store('public/customer') : null,
                'name'   => $request->name,
                'constructor'   => $request->constructor,
                'email'  => $request->email,
                'phone'  => $request->phone,
                'password' => Hash::make($request->password),
                'type'   => $request->type,
				'address'	=> $request->address,
				'npwp'		=> $request->npwp,
				'address_npwp' => $request->address_npwp,
				'finance_name' => $request->finance_name,
				'finance_hp' => $request->finance_hp,
                'points' => 0,
				'photo_ktp'	=> $request->has('image_ktp') ? $request->file('image_ktp')->store('public/customer') : null,
				'photo_npwp' => $request->has('image_npwp') ? $request->file('image_npwp')->store('public/customer') : null,
				'balance' => 0,
            ]);

            if($query) {
                activity()
                    ->performedOn(new Customer())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master customer data');

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
        $data = Customer::find($request->id);
        return response()->json([
            'city_id'  => $data->city_id ? $data->city_id : '',
            'city_name'  =>$data->city_id ? $data->city->name : '',
            'photo'  => Storage::exists($data->photo) ? asset(Storage::url($data->photo)) : asset('website/user.png'),
            'name'   => $data->name,
            'constructor'   => $data->constructor,
            'email'   => $data->email,
            'phone'  => $data->phone,
            'type' => $data->type,
			'address' => $data->address,
			'npwp' => $data->npwp,
			'address_npwp' => $data->address_npwp,
			'finance_name' => $data->finance_name,
			'finance_hp' => $data->finance_hp
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'image'  => 'image|mimes:jpg,jpeg,png|max:100|dimensions:max_width=400,max_height=400',
            //'email'   => ['required', Rule::unique('customers', 'email')->ignore($id)],
            'name'   => 'required',
			'address'=> 'required',
            'phone'  => 'required|min:8',
            'type'   => 'required'
        ], [
            'image.image'      => 'File must be an image.',
            'image.mimes'      => 'Image must have an extension jpg, jpeg, png.',
            'image.max'        => 'Image max 100KB.',
            'image.dimensions' => 'Image max size 400x400.',
            'name.required'    => 'Name cannot be empty.',
            /* 'email.required'   => 'Email cannot be empty.',
            'email.unique'     => 'Email has already been used.', */
            'phone.required'   => 'Phone number cannot be empty.',
			'phone.min'   		=> 'Phone number minimal 8 characters.',
			'address.required' => 'Address cannot be empty.',
            'type.required'    => 'Please select a type of user (online / offline).'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Customer::find($id);
            
            if($request->has('image')) {
                if(Storage::exists($query->photo)) {
                    Storage::delete($query->photo);
                }

                $image = $request->file('image')->store('public/customer');
            } else {
                $image = $query->photo;
            }
			
			if($request->has('image_ktp')) {
                if(Storage::exists($query->photo_ktp)) {
                    Storage::delete($query->photo_ktp);
                }

                $imagektp = $request->file('image_ktp')->store('public/customer');
            } else {
                $imagektp = $query->photo_ktp;
            }
			
			if($request->has('image_npwp')) {
                if(Storage::exists($query->photo_npwp)) {
                    Storage::delete($query->photo_npwp);
                }

                $imagenpwp = $request->file('image_npwp')->store('public/customer');
            } else {
                $imagenpwp = $query->photo_npwp;
            }

            if($request->password){
                $query->update([
                    'city_id'  => $request->city_id,
                    'photo'  => $image,
                    'name'   => $request->name,
                    'constructor'   => $request->constructor,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'password' => Hash::make($request->password),
                    'type'   => $request->type,
					'address'	=> $request->address,
					'npwp'		=> $request->npwp,
					'address_npwp' => $request->address_npwp,
					'finance_name' => $request->finance_name,
					'finance_hp' => $request->finance_hp,
					'photo_ktp'	=> $imagektp,
					'photo_npwp' => $imagenpwp,
                ]);
            }else{
                $query->update([
                    'city_id'  => $request->city_id,
                    'photo'  => $image,
                    'name'   => $request->name,
                    'constructor'   => $request->constructor,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'type'   => $request->type,
					'address'	=> $request->address,
					'npwp'		=> $request->npwp,
					'address_npwp' => $request->address_npwp,
					'finance_name' => $request->finance_name,
					'finance_hp' => $request->finance_hp,
					'photo_ktp'	=> $imagektp,
					'photo_npwp' => $imagenpwp,
                ]);
            }

            if($query) {
                activity()
                    ->performedOn(new Customer())
                    ->causedBy(session('bo_id'))
					->withProperties($query)
                    ->log('Change the customer master data');

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
        $query = Customer::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Customer())
                ->causedBy(session('bo_id'))
                ->log('Delete the customer master data');

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
	
	public function export(Request $request){
		$search = $request->search ? $request->search : '';
		$numrow = $request->numrow;
		$start = $request->start;
		$type = $request->type ? $request->type : '';
		
		return Excel::download(new CustomersExport($search, $numrow, $start, $type), 'customers.xlsx');
	}
	
	public function print(Request $request)
    {
		$search = $request->search ? $request->search : '';
		$numrow = $request->numrow;
		$start = $request->start;
		$type = $request->type ? $request->type : '';
		
		$customer = Customer::where('name','like','%'.$search.'%')
				->where('type','like','%'.$type.'%')
				->offset($start)
				->limit($numrow)
				->get();

		if(!$customer) {
			abort(404);
		}
		
		$pdf = PDF::loadView('admin.pdf.master_data.customer', [
				'customer' => $customer
			],
			[],
			[ 
			  'format' => 'A4-P',
			  'orientation' => 'P'
			]
		);

        return $pdf->stream('customer_report.pdf');
    }
	
	public function repairBalance(){
		$data = Project::whereHas('projectSale')->get();
		
		Customer::query()->update([
			'balance' => 0
		]);
		
		foreach($data as $project){
			
			$total = 0;
			
			$customer = $project->customer_id;
			
			foreach($project->projectBill as $bill){
				
				$total += $bill->balance();
				
			}
			
			foreach($project->projectSale as $sale){
				foreach($sale->projectSalePay->where('coa_id',67) as $pay){
					$total -= $pay->nominal;
				}
			}
			
			if($total > 0){
				$update = Customer::find($customer)->increment('balance', round($total));
			}
		}
		
	}
}
