<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TrackingProjectController extends Controller {
    
	public function index()
    {
        $data = [
            'title'   		=> 'Tracking Project',
            'content' 		=> 'information.tracking'
        ];

        return view('layouts.index', ['data' => $data]);
    }
	
    public function trackingProgress(Request $request) 
    {
		$code = $this->dekripsi($request->code);
		
		$project = Project::where('code',$code)->first();
		
		if($project){
			$data = [
				'title'   	=> 'Tracking Project',
				'project'	=> $project,
				'content' 	=> 'project_progress'
			];
		}else{
			abort(404);
		}

        return view('layouts.tracking', ['data' => $data]);
    }
	
	public function convertUrl(Request $request){
		$no = $request->no;
		
		$response = [];
		
		$response['status'] = 200;
		$response['url'] = url('information/tracking/progress/'.$this->enkripsi($no));

        return response()->json($response);
	}
	
	function enkripsi($anu){
		if($anu == ''){
			$val = "";
		}else{
			$val = implode('-',str_split(str_replace('=','',base64_encode($anu)),5));
		}
		
		return $val;
	}
	
	function dekripsi($una){
		$val = base64_decode(str_replace('-','',$una));
		return $val;
	}
}