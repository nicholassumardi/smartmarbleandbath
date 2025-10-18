<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Models\ProductShading;
use App\Models\Coa;
use App\Models\Journal;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\UserBalance;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('stock', function(Request $request) {
    // If the Content-Type and Accept headers are set to 'application/json', 
    // this will return a JSON structure. This will be cleaned up later.
	if($request->code == 'smbjayaselalu'){
		return ProductShading::all();
	}else{
		$response = [
			'error' => 'Code access not found.'
		];
		
		return $response;
	}
});

Route::get('ventura', function(Request $request) {
    // If the Content-Type and Accept headers are set to 'application/json', 
    // this will return a JSON structure. This will be cleaned up later.
	$stock    = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
		'per_page'  => 5000
	]));
	
	if($request->code == 'smbjayaselalu'){
		 return $stock->result->data;
	}else{
		$response = [
			'error' => 'Code access not found.'
		];
		
		return $response;
	}
});

/* Route::get('get_user', function(Request $request) {
	if($request->code == 'smbjayaselalu'){
		$query = UserLogin::all();
		
		if(count($query) > 0){
			
			$arr = [];
			
			foreach($query as $row){
				$arr[] = [
					'id'		=> $row->id,
					'loginable' => $row->loginable,
					'name'		=> $row->user->name,
					'email'		=> $row->user->email
				];
			}
			
			return $arr;
		}else{
			$response = [
				'result' => 'Data is empty.'
			];
			
			return $response;
		}
	}else{
		$response = [
			'error' => 'Code access not found.'
		];
		
		return $response;
	}
});

Route::post('update_user', function(Request $request) {
	if($request->code == 'smbjayaselalu'){
		$loginable = '1';
		
		$query = User::where('email',$request->email)
				->whereHas('userLogin')->whereHas('userLogin',function ($query) use ($loginable){
					$query->where('loginable',$loginable);
				})
				->first();
				
		if($query){
			if($request->password) {
				$query->update([
					'name'  	=> $request->name,
					'email' 	=> $request->email,
					'password'	=> bcrypt($request->password)
				]);
			}else{
				$query->update([
					'name'  => $request->name,
					'email' => $request->email
				]);
			}
			
			$response = [
				'result' => 'Successfully updated.'
			];
		}else{
			$response = [
				'result' => 'User not found.'
			];
		}
		
		return $response;
	}else{
		$response = [
			'error' => 'Code access not found.'
		];
		
		return $response;
	}
});

Route::post('update_balance', function(Request $request) {
	if($request->code == 'smbjayaselalu'){
		$loginable = '1';
		
		if($request->has('description') && $request->has('balance')){
		
			$kueri = User::where('email',$request->email)
					->whereHas('userLogin')->whereHas('userLogin',function ($query) use ($loginable){
						$query->where('loginable',$loginable);
					})
					->first();
			
			if($kueri){
				UserBalance::create([
					'user_id'  		=> $kueri->id,
					'description' 	=> $request->description,
					'balance'		=> $request->balance ? $request->balance : 0
				]);
				
				$response = [
					'result' => 'Successfully inserted.'
				];
			}else{
				$response = [
					'result' => 'User not found.'
				];
			}
		}else{
			$response = [
				'result' => 'Required parameter is not valid.'
			];
		}
		
		return $response;
	}else{
		$response = [
			'error' => 'Code access not found.'
		];
		
		return $response;
	}
});

Route::get('get_balance', function(Request $request) {
	if($request->code == 'smbjayaselalu'){
		$loginable = '1';
		
		$kueri = User::where('email',$request->email)
					->whereHas('userLogin')->whereHas('userLogin',function ($query) use ($loginable){
						$query->where('loginable',$loginable);
					})
					->latest()->first();
		if($kueri){
			
			if(count($kueri->userBalance) > 0){
				$arr = [];
				
				foreach($kueri->userBalance as $row){
					$arr[] = [
						'description' 	=> $row->description,
						'balance'		=> $row->balance,
						'created_at'	=> $row->created_at,
						'updated_at'	=> $row->updated_at
					];
				}
				
				return $arr;
			}else{
				$response = [
					'result' => 'Balance is empty. Please add one.'
				];
			}
		}else{
			$response = [
				'result' => 'User not found.'
			];
		}
		
		return $response;
	}else{
		$response = [
			'error' => 'Code access not found.'
		];
		
		return $response;
	}
}); */