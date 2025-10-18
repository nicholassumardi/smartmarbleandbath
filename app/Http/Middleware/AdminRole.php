<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $idrole)
    {
        $role = session('bo_role');
			
		$arrRole = explode('|',$idrole);
        
		$allow = false;
		
		foreach($arrRole as $r){
			if(in_array($r,$role)){
				$allow = true;
			}
		}
		
		if($allow){
			return $next($request);
		}else{
			abort(401, 'This action is unauthorized.');
		}
    }
}
