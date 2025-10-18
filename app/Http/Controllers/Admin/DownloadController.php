<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadController extends Controller {
	
    public function __construct()
    {
        //$this->middleware('admin.role:1,2');
    }

    public function index(Request $request)
    {
	
        $data = [
            'title'   		=> 'Downloads',
            'content' 		=> 'admin.downloads'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
    
}
