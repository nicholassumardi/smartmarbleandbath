<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Career',
            'career'  => Career::all(),
            'content' => 'career'
        ];

        return view('layouts.index', ['data' => $data]);
    }
    
}
