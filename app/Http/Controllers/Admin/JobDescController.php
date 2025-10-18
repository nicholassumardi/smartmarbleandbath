<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobDesc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobDescController extends Controller {
    
    public function index()
    {
        $data = [
            'title'        => 'Job Desc',
            'job_desc_sby' => JobDesc::where('branch', 1)->get(),
            'job_desc_jkt' => JobDesc::where('branch', 2)->get(),
            'content'      => 'admin.hrd.job_desc'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

}
