<?php

namespace App\Http\Controllers;

use App\Models\CogsCalculator;
use Illuminate\Http\Request;

class CogsCalculatorController extends Controller
{
    public function index()
    {

        $data = [
            'title'           => 'COGS Calculator',
            // 'cogs_calculator' => CogsCalculator::all(),
            'content'         => 'cogs_calculator'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){

    }


    public function create(Request $request){
        if($request->has('_token')) {

        }else{
            $data = [
                'title'           => 'COGS Calculator Create',
                'content'         => 'cogs_calculator_create'
            ];
            return view('admin.layouts.index', ['data' => $data]);
        }
    }
}
