<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Token;
use App\Models\UserRole;
use App\Jobs\EmailProcess;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\CompanyEntities;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    
    public function index()
    {			
        $data = [
            'title'     => 'User',
            'content'   => 'admin.setting.user'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'detail',
            'id',
            'photo',
            'name',
            'branch',
            'verification',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = User::count();
        
        $query_data = User::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
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

        $total_filtered = User::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
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
                if(Storage::exists($val->photo)) {
                    $photo = '<a href="' . asset(Storage::url($val->photo)) . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . asset(Storage::url($val->photo)) . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
                } else {
                    $photo = '<a href="' . asset('website/user.png') . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . asset('website/user.png') . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
                }

                $response['data'][] = [
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $nomor,
                    $photo,
                    $val->name,
                    $val->branch(),
                    $val->verification ? date('d F Y', strtotime($val->verification)) : 'Not Verified',
                    $val->status(),
                    '
                        <button type="button" class="btn bg-success btn-sm" data-popup="tooltip" title="Reset Password" onclick="resetPassword(' . $val->id . ')"><i class="icon-lock"></i></button>
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

    public function rowDetail(Request $request)
    {
        $data = User::find($request->id);
        $role = [];

        foreach($data->userRole as $ur) {
            $role[] = $ur->role();
        }
        return response()->json([
            'email' => $data->email,
            'role'  => $role
        ]);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'photo'     		=> 'image|max:200|mimes:jpg,jpeg,png',
            'name'      		=> 'required',
            'email'     		=> 'required|unique:users,email',
            'password'  		=> 'required',
            'branch'    		=> 'required',
			'pob'				=> 'required',
			'dob'				=> 'required',
			'gender'			=> 'required',
			'marital_status'	=> 'required',
			'blood_type'		=> 'required',
			'religion'			=> 'required',
			'id_card'			=> 'required',
			'id_number'			=> 'required',
			'postcode'			=> 'required',
			'address_id'		=> 'required',
			'address_residence'	=> 'required',
			'ispkp'				=> 'required',
			'account_number'	=> 'required',
			'account_bank'		=> 'required',
			'account_name'		=> 'required',
            'role'      		=> 'required|array',
            'status'    		=> 'required',
			'phone'    			=> 'required'
        ], [
            'photo.image'        => 'Photo must be image.',
            'photo.max'          => 'Photo max 200KB.',
            'photo.mimes'        => 'Photo extension must be jpg, jpeg, png.',
            'name.required'      => 'Name cannot be empty.',
            'email.required'     => 'Email cannot be empty.',
            'email.unique'       => 'Email already exists.',
            'password.required'  => 'Password cannot be empty.',
            'branch.required'    => 'Please select a branch.',
			'pob.required'		 => 'Please enter place of birth.',
			'dob.required'		 => 'Please enter date of birth.',
			'gender.required'	 => 'Please gender.',
			'marital_status.required'	 => 'Please select marital status.',
			'blood_type.required'=> 'Please enter blood_type.',
			'religion.required'	 => 'Please select a religion.',
			'id_card.required'	 => 'Please select id card.',
			'id_number.required' => 'Please enter id card number.',
			'postcode.required'	 => 'Please enter post code.',
			'address_id.required'=> 'Please enter address as shown on id card.',
			'address_residence.required' => 'Please enter address now.',
			'ispkp.required' => 'Please select whether he/she is taxpayer or no.',
			'account_number.required' => 'Please enter account number.',
			'account_bank.required' => 'Please enter account bank.',
			'account_name.required' => 'Please enter account owner name.',
            'role.required'      => 'Please select a role.',
            'role.array'         => 'Role must be array.',
            'status.required'    => 'Please select a status.',
			'phone.required'    => 'Phone cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->has('photo')) {
                $photo = $request->file('photo')->store('public/user');
            } else {
                $photo = null;
            }

            $query = User::create([
                'photo'    			=> $photo,
                'name'     			=> $request->name,
                'email'    			=> $request->email,
                'password' 			=> bcrypt($request->password),
                'branch'   			=> $request->branch,
				'place_of_birth'	=> $request->pob,
				'date_of_birth'		=> $request->dob,
				'gender'			=> $request->gender,
				'marital_status'	=> $request->marital_status,
				'blood_type'		=> $request->blood_type,
				'religion'			=> $request->religion,
				'id_type'			=> $request->id_card,
				'id_no'				=> $request->id_number,
				'postcode'			=> $request->postcode,
				'address_id'		=> $request->address_id,
				'address_residence'	=> $request->address_residence,
				'npwp'				=> $request->npwp,
				'ispkp'				=> $request->ispkp,
				'ptkp_type'			=> $request->ptkp_type,
				'tax_type'			=> $request->tax_type,
				'account_number'	=> $request->account_number,
				'account_bank'		=> $request->account_bank,
				'account_name'		=> $request->account_name,
                'status'   			=> $request->status,
				'phone'   			=> $request->phone
            ]);

            if($query) {
                foreach($request->role as $ur) {
                    UserRole::create([
                        'user_id' => $query->id,
                        'role'    => $ur
                    ]);
                }

                activity()
                    ->performedOn(new User())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add setting user data');

                /* $token = Token::create([
                    'tokenable_type' => 'users',
                    'tokenable_id'   => $query->id,
                    'token'          => Str::random(45),
                    'type'           => 1,
                    'valid'          => date('Y-m-d H:i:s', strtotime('+1 day')),
                    'status'         => 1
                ]);

                $payload = [
                    'name'    => $query->name,
                    'email'   => $query->email,
                    'link'    => url('admin/verification?token=' . base64_encode($token->token)),
                    'view'    => 'verification',
                    'subject' => 'SMB Admin | Verification Account'
                ];

				Mail::send('emails.' . $payload['view'], $payload, function($mail) use ($payload) {
					$mail->to($payload['email'], $payload['name']);
					$mail->subject($payload['subject']);
					$mail->from(config('mail.mailers.smtp.username'), 'Smart Marble And Bath');
				}); */
		
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
        $data = User::find($request->id);
        $role = [];

        foreach($data->userRole as $ur) {
            $role[] = $ur->role;
        }

        return response()->json([
            'photo'     		=> $data->photo ? asset(Storage::url($data->photo)) : asset('website/user.png'),
            'name'      		=> $data->name,
            'email'     		=> $data->email,
            'password'  		=> $data->password,
            'branch'    		=> $data->branch,
			'place_of_birth'	=> $data->place_of_birth,
			'date_of_birth'		=> $data->date_of_birth,
			'gender'			=> $data->gender,
			'marital_status'	=> $data->marital_status,
			'blood_type'		=> $data->blood_type,
			'religion'			=> $data->religion,
			'id_type'			=> $data->id_type,
			'id_no'				=> $data->id_no,
			'postcode'			=> $data->postcode,
			'address_id'		=> $data->address_id,
			'address_residence'	=> $data->address_residence,
			'npwp'				=> $data->npwp,
			'ispkp'				=> $data->ispkp,
			'ptkp_type'			=> $data->ptkp_type,
			'tax_type'			=> $data->tax_type,
			'account_number'	=> $data->account_number,
			'account_bank'		=> $data->account_bank,
			'account_name'		=> $data->account_name,
            'status'   			=> $data->status,
			'phone'   			=> $data->phone,
            'role'      		=> $role
        ]);
    }

    public function update(Request $request, $id)
    {
        $user       = User::find($id);
        $validation = Validator::make($request->all(), [
            'photo'     		=> 'image|max:200|mimes:jpg,jpeg,png',
            'name'      		=> 'required',
            'email'     		=> ['required', Rule::unique('users', 'email')->ignore($id)],
            'branch'    		=> 'required',
			'pob'				=> 'required',
			'dob'				=> 'required',
			'gender'			=> 'required',
			'marital_status'	=> 'required',
			'blood_type'		=> 'required',
			'religion'			=> 'required',
			'id_card'			=> 'required',
			'id_number'			=> 'required',
			'postcode'			=> 'required',
			'address_id'		=> 'required',
			'address_residence'	=> 'required',
			'ispkp'				=> 'required',
			'account_number'	=> 'required',
			'account_bank'		=> 'required',
			'account_name'		=> 'required',
            'role'      		=> 'required|array',
            'status'    		=> 'required',
			'phone'	    		=> 'required'
        ], [
            'photo.image'        => 'Photo must be image.',
            'photo.max'          => 'Photo max 200KB.',
            'photo.mimes'        => 'Photo extension must be jpg, jpeg, png.',
            'name.required'      => 'Name cannot be empty.',
            'email.required'     => 'Email cannot be empty.',
            'email.unique'       => 'Email already exists.',
            'branch.required'    => 'Please select a branch.',
			'pob.required'		 => 'Please enter place of birth.',
			'dob.required'		 => 'Please enter date of birth.',
			'gender.required'	 => 'Please gender.',
			'marital_status.required'	 => 'Please select marital status.',
			'blood_type.required'=> 'Please enter blood_type.',
			'religion.required'	 => 'Please select a religion.',
			'id_card.required'	 => 'Please select id card.',
			'id_number.required' => 'Please enter id card number.',
			'postcode.required'	 => 'Please enter post code.',
			'address_id.required'=> 'Please enter address as shown on id card.',
			'address_residence.required' => 'Please enter address now.',
			'ispkp.required' => 'Please select whether he/she is taxpayer or no.',
			'account_number.required' => 'Please enter account number.',
			'account_bank.required' => 'Please enter account bank.',
			'account_name.required' => 'Please enter account owner name.',
            'role.required'      => 'Please select a role.',
            'role.array'         => 'Role must be array.',
            'status.required'    => 'Please select a status.',
			'phone.required'    => 'Phone cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->has('photo')) {
                if(Storage::exists($user->photo)) {
                    Storage::delete($user->photo);
                }

                $photo = $request->file('photo')->store('public/user');
            } else {
                $photo = $user->photo;
            }

            $user->update([
                'photo'    			=> $photo,
                'name'     			=> $request->name,
                'email'    			=> $request->email,
                'branch'   			=> $request->branch,
				'place_of_birth'	=> $request->pob,
				'date_of_birth'		=> $request->dob,
				'gender'			=> $request->gender,
				'marital_status'	=> $request->marital_status,
				'blood_type'		=> $request->blood_type,
				'religion'			=> $request->religion,
				'id_type'			=> $request->id_card,
				'id_no'				=> $request->id_number,
				'postcode'			=> $request->postcode,
				'address_id'		=> $request->address_id,
				'address_residence'	=> $request->address_residence,
				'npwp'				=> $request->npwp,
				'ispkp'				=> $request->ispkp,
				'ptkp_type'			=> $request->ptkp_type,
				'tax_type'			=> $request->tax_type,
				'account_number'	=> $request->account_number,
				'account_bank'		=> $request->account_bank,
				'account_name'		=> $request->account_name,
                'status'   			=> $request->status,
				'phone'   			=> $request->phone
            ]);

            if($user) {
                UserRole::where('user_id', $id)->delete();
                foreach($request->role as $ur) {
                    UserRole::create([
                        'user_id' => $id,
                        'role'    => $ur
                    ]);
                }

                activity()
                    ->performedOn(new User())
                    ->causedBy(session('bo_id'))
                    ->log('Change the user setting data');

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
        $query = User::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new User())
                ->causedBy(session('bo_id'))
                ->log('Delete the user setting data');

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

    public function resetPassword(Request $request) 
    {
        $query = User::where('id', $request->id)->update(['password' => bcrypt('smartmarble')]);
        if($query) {
            activity()
                ->performedOn(new User())
                ->causedBy(session('bo_id'))
                ->log('Reset password user setting data');

            $response = [
                'status'  => 200,
                'message' => 'Password successfully reset.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Password failed to reset.'
            ];
        }

        return response()->json($response);
    }

}
