<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportApOther implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
        return view('admin.excel.report.finance.outstanding_a_p_other',  $this->data);
    }
}
