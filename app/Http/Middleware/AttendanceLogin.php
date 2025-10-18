<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AttendanceCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceLogin
{
    public function handle(Request $request, Closure $next)
    {
        $user_id = session('val');
        $user    = AttendanceCode::where('id',1)->where('logged_in',1)->first();
        
		if($user) {
			if(session('val')) {
				$user->update([
					'logged_in' => 1
				]);
				
				session([
					'val' => 1
				]);

				return $next($request);
			}
		}
		
		if(session('val')) {
			session()->flush();
		}
		
		/* AttendanceCode::find(1)->update([
			'logged_in' => 0
		]); */
		
		return redirect('attendance/login');
    }
}
