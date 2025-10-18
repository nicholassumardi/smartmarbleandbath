<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CityCurrencyWarehouseController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'City, Currency, & Warehouse',
            'content' => 'admin.master_data.product.city_currency_warehouse'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
}