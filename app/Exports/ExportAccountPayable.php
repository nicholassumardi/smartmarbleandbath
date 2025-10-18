<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportAccountPayable implements FromView
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
        return view('admin.excel.report.finance.outstanding_a_p',  $this->data);
    }
}
