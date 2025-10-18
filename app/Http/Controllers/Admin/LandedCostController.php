<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Currency;
use App\Models\City;
use App\Models\Country;
use App\Models\Freight;
use App\Models\Emkl;
use App\Models\Import;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LandedCostController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Landed Cost',
			'currency' => Currency::where('status', 1)->get(),
            'company'  => Company::where('status', 1)->get(),
			'country' => Country::where('status', 1)->get(),
            'city'    => City::all(),
			'import'  => Import::all(),
            'content' => 'admin.master_data.cogs_master.landed_cost'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
}