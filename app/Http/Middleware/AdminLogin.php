<?php

namespace App\Http\Middleware;

use Cookie;
use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id = session('bo_id');
        $role    = [];
        $user    = User::where('id', $user_id)
            ->whereNotNull('verification')
            ->where('status', 1)
            ->has('userRole')
            ->first();
        
        $token = '';

        if($user_id && $user) {
            /* if(Cookie::get('temp')){
                $token = Cookie::get('temp');
            }
    
            if($token == $user->token_device){ */

                foreach($user->userRole as $ur) {
                    $role[] = $ur->role;
                }
                
                session([
                    'bo_id'           => $user->id,
                    'bo_photo'        => $user->photo ? asset(Storage::url($user->photo)) : asset('website/user.png'),
                    'bo_name'         => $user->name,
                    'bo_email'        => $user->email,
                    'bo_branch'       => $user->branch,
                    'bo_role'         => $role
                ]);
    
                return $next($request);
            /* }else{
                session()->flush();
                return redirect('admin/login');
            }  */          
        } else {
            session()->flush();
            return redirect('admin/login');
        }
    }
}
