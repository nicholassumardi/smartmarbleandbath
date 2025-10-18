<?php

namespace App\Http\Controllers\Attendance;

use Cookie;
use App\Models\Token;
use App\Models\AttendanceCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CodeController extends Controller {

    public function login(Request $request)
    {
        if(session('val')) {
            return redirect('attendance/code');
        }

        if($request->has('_token')) {
            $user = AttendanceCode::where('id',1)->where('logged_in',0)->first();
			if($user){
				if(Hash::check($request->password, $user->pass)) {
					$user->update([
						'logged_in' => 1
					]);
					
					session([
						'val' => 1
					]);

					return redirect('attendance/code');
				} else {
					return redirect()->back()->with(['failed' => 'Ups, wrong bro!']);
				}
			}else{
				return redirect()->back()->with(['failed' => 'Ups, already logged in!']);
			}
        } else {
			/* AttendanceCode::find(1)->update([
				'logged_in' => 0
			]); */
            return view('attendance.login');
        }
    }
	
	public function logout()
    {
		AttendanceCode::find(1)->update([
			'logged_in' => 0
		]);
        session()->flush();
        return redirect('attendance/login')->with(['success' => 'You successfully logout.']);
    }
}