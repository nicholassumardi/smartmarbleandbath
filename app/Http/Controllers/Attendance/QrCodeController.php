<?php

namespace App\Http\Controllers\Attendance;

use App\Models\AttendanceCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class QrCodeController extends Controller {

	public function code()
    {
        $data = [
            'title'   	=> 'Generate QR Code',
            'content' 	=> 'attendance.code'
        ];

        return view('attendance.layouts.index', ['data' => $data]);
    }
	
	public function generate(Request $request){
		
		$addcode = '';
		
		if($request->leave){
			$addcode = $request->leave;
		}
		
		$newcode = strtoupper(Str::random(15).'_'.strtotime(date('Y-m-d H:i:s'))).($addcode ? '_'.$addcode : '');
		
		$hourlimit = date('H');
		
		if($hourlimit < 12){
			$mode = 'IN';
		}else{
			$mode = 'OUT';
		}
		
		AttendanceCode::find(1)->update([
			'code'	=> $newcode,
			'mode'	=> $mode
		]);
		
		$response = [
			'status'  	=> 200,
			'code' 		=> $newcode
		];

        return response()->json($response);
	}
}