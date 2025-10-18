<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InventoryExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
        return view('admin.excel.report.inventory.product_by_warehouse',  $this->data);
    }
}
