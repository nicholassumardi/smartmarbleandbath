<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlCustomer;
use App\Models\AlInstitute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use PDF;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AlCustomerController extends Controller
{
    public function index()
    {
        $data = [
            'title'   		=> 'AL Customer',
			'institute'   	=> AlInstitute::all(),
            'content' 		=> 'admin.al.master_data.customer'
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

        $total_data = AlCustomer::count();
        
        $query_data = AlCustomer::where(function($query) use ($search, $request) {
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

        $total_filtered = AlCustomer::where(function($query) use ($search, $request) {
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
	
	public function datatableInstitute(Request $request) 
    {
        $column = [
            'id',
            'name',
            'initial'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlInstitute::count();
        
        $query_data = AlInstitute::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('initial', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlInstitute::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('initial', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->name,
                    $val->initial,
                    '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="showCategory(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyCategory(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
            'name'   => 'required',
			'initial'=> 'required|unique:al_customers,initial',
			'pic'	 => 'required',
			'address'=> 'required',
            'phone'  => 'required',
            'type'   => 'required'
        ], [
            'image.image'      => 'File must be an image.',
            'image.mimes'      => 'Image must have an extension jpg, jpeg, png.',
            'image.max'        => 'Image max 100KB.',
            'image.dimensions' => 'Image max size 400x400.',
            'name.required'    => 'Name cannot be empty.',
			'initial.required' => 'Initial name cannot be empty.',
			'initial.unique'   => 'Initial already used.',
			'pic.required'	   => 'PIC name cannot be empty.',
			'address.required' => 'Address cannot be empty.',
            'phone.required'   => 'Phone number cannot be empty.',
            'type.required'    => 'Please select a type of user (online / offline).'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = AlCustomer::create([
                'photo'  => $request->has('image') ? $request->file('image')->store('public/customer') : null,
                'name'   => $request->name,
                'pic'   => $request->pic,
				'pic_birthday'   => $request->pic_birthday,
				'pic_position'   => $request->pic_position,
				'pic_office'   => $request->pic_office,
				'initial'   => $request->initial,
				'al_institute_id'   => $request->institute_id,
                'email'  => $request->email,
                'phone'  => $request->phone,
                'password' => Hash::make($request->password),
                'type'   => $request->type,
				'address'	=> $request->address,
				'npwp'		=> $request->npwp,
				'address_npwp' => $request->address_npwp,
				'finance_name' => $request->finance_name,
				'finance_hp' => $request->finance_hp,
                'points' => 0
            ]);

            if($query) {
                activity()
                    ->performedOn(new AlCustomer())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master al customer data');

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
        $data = AlCustomer::find($request->id);
        return response()->json([
            'photo'  => Storage::exists($data->photo) ? asset(Storage::url($data->photo)) : asset('website/user.png'),
            'name'   => $data->name,
            'pic'   => $data->pic,
			'pic_birthday'   => $data->pic_birthday,
			'pic_position'   => $data->pic_position,
			'pic_office'   => $data->pic_office,
			'initial' => $data->initial,
			'al_institute_id'	=> $data->al_institute_id,
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
            'initial'=> ['required', Rule::unique('al_customers', 'initial')->ignore($id)],
            'name'   => 'required',
			'pic'	 => 'required',
			'address'=> 'required',
            'phone'  => 'required',
            'type'   => 'required'
        ], [
            'image.image'      => 'File must be an image.',
            'image.mimes'      => 'Image must have an extension jpg, jpeg, png.',
            'image.max'        => 'Image max 100KB.',
            'image.dimensions' => 'Image max size 400x400.',
            'name.required'    => 'Name cannot be empty.',
			'initial.required' => 'Initial name cannot be empty.',
			'initial.unique'   => 'Initial name already used.',
			'pic.required'	   => 'PIC name cannot be empty.',
            'phone.required'   => 'Phone number cannot be empty.',
			'address.required' => 'Address cannot be empty.',
            'type.required'    => 'Please select a type of user (online / offline).'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = AlCustomer::find($id);
            
            if($request->has('image')) {
                if(Storage::exists($query->photo)) {
                    Storage::delete($query->photo);
                }

                $image = $request->file('image')->store('public/customer');
            } else {
                $image = $query->photo;
            }

            if($request->password){
                $query->update([
                    'photo'  => $image,
                    'name'   => $request->name,
					'pic'   => $request->pic,
					'pic_birthday'   => $request->pic_birthday,
					'pic_position'   => $request->pic_position,
					'pic_office'   => $request->pic_office,
					'initial'   => $request->initial,
					'al_institute_id'   => $request->institute_id,
                    'constructor'   => $request->constructor,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'password' => Hash::make($request->password),
                    'type'   => $request->type,
					'address'	=> $request->address,
					'npwp'		=> $request->npwp,
					'address_npwp' => $request->address_npwp,
					'finance_name' => $request->finance_name,
					'finance_hp' => $request->finance_hp
                ]);
            }else{
                $query->update([
                    'photo'  => $image,
                    'name'   => $request->name,
					'pic'   => $request->pic,
					'pic_birthday'   => $request->pic_birthday,
					'pic_position'   => $request->pic_position,
					'pic_office'   => $request->pic_office,
					'initial'   => $request->initial,
					'al_institute_id'   => $request->institute_id,
                    'constructor'   => $request->constructor,
                    'email'  => $request->email,
                    'phone'  => $request->phone,
                    'type'   => $request->type,
					'address'	=> $request->address,
					'npwp'		=> $request->npwp,
					'address_npwp' => $request->address_npwp,
					'finance_name' => $request->finance_name,
					'finance_hp' => $request->finance_hp
                ]);
            }

            if($query) {
                activity()
                    ->performedOn(new AlCustomer())
                    ->causedBy(session('bo_id'))
					->withProperties($query)
                    ->log('Change the AL customer master data');

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
        $query = AlCustomer::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new AlCustomer())
                ->causedBy(session('bo_id'))
                ->log('Delete the AL customer master data');

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
		
		return Excel::download(new AlCustomersExport($search, $numrow, $start, $type), 'customers.xlsx');
	}
	
	public function print(Request $request)
    {
		$search = $request->search ? $request->search : '';
		$numrow = $request->numrow;
		$start = $request->start;
		$type = $request->type ? $request->type : '';
		
		$customer = AlCustomer::where('name','like','%'.$search.'%')
				->where('type','like','%'.$type.'%')
				->offset($start)
				->limit($numrow)
				->get();

		if(!$customer) {
			abort(404);
		}
		
		$pdf = PDF::loadView('admin.pdf.al.master_data.customer', [
				'customer' => $customer
			],
			[],
			[ 
			  'format' => 'A4-P',
			  'orientation' => 'P'
			]
		);

        return $pdf->stream('al_customer_report.pdf');
    }
	
	public function createInstitute(Request $request){
		if($request->tempInstansi){
			$validation = Validator::make($request->all(), [
				'name_institute'      			=> 'required',
				'initial_institute'				=> ['required', Rule::unique('al_institutes', 'initial')->ignore($request->tempInstansi)],
			], [
				'name_institute.required'		=> 'Category name cannot be empty.',
				'initial_institute.required'	=> 'Initial cannot be empty.',
				'initial_institute.unique'		=> 'Initial already used.'
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'name_institute'      			=> 'required',
				'initial_institute'				=> 'required|unique:al_institutes,initial',
			], [
				'name_institute.required'		=> 'Category name cannot be empty.',
				'initial_institute.required'	=> 'Initial cannot be empty.',
				'initial_institute.unique'		=> 'Initial already used.'
			]);
		}
		

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->tempInstansi){
				$query = AlInstitute::find($request->tempInstansi);
				
				$query->update([
					'name'          => $request->name_institute,
					'initial'		=> $request->initial_institute	
				]);
			}else{
				$query = AlInstitute::create([
					'name'          => $request->name_institute,
					'initial'		=> $request->initial_institute
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new AlInstitute())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master AL institute data');

                $response = [
                    'status'  	=> 200,
					'data'		=> AlInstitute::all(),
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
	
	public function showInstitute(Request $request){
		$institute = AlInstitute::find($request->id);
		
		return response()->json($institute);
	}
}