<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class CommissionController extends Controller {

    public function index()
    {
		
        $data = [
            'title'   			=> 'Comission',
            'content' 			=> 'admin.hrd.commission'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
}