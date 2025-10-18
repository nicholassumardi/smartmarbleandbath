<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Illuminate\Http\Request;
use App\Exports\StocksExport;
use App\Models\ProductShading;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class StockController extends Controller {

    public function index(Request $request)
    {

        $per_page = 20;
        $page     = $request->has('page') ? $request->page: 1;
		
		$stock    = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
			'tipe_item' => $request->search,
			'kode_item'	=> $request->code,
			'gudang'	=> $request->warehouse,
			'page'      => $page,
			'per_page'  => $per_page
		]));

        $result       = $stock->result->data;
        $total_result = $stock->result->total_data;
        $items        = new LengthAwarePaginator($result, $total_result, $per_page, $page, [
            'path'  => $request->url(),
            'query' => $request->query()
        ]);

        $data = [
            'title'   		=> 'Stock',
            'search'  		=> $request->search,
			'warehouse'  	=> $request->warehouse,
			'code'  		=> $request->code,
            'items'   		=> $items,
            'content' 		=> 'admin.inventory.ventura'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function export(Request $request){
		$search = $request->search ? $request->search : '';
		$warehouse = $request->warehouse ? $request->warehouse : '';
		$code = $request->code ? $request->code : '';
		
		return Excel::download(new StocksExport($search,$warehouse,$code), 'stocks.xlsx');
	}
	
	public function print(Request $request)
    {
		$page     = $request->has('page') ? $request->page: 1;
		
		$stock = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
            'tipe_item' => $request->search,
			'kode_item' => $request->code,
			'gudang'	=> $request->warehouse,
			'page'      => $page,
            'per_page'  => 10
        ]));
		
		$result = $stock->result;

		if(!$result) {
			abort(404);
		}
		
		$pdf = PDF::loadView('admin.pdf.master_data.stock', [
				'result' => $result
			],
			[],
			[ 
			  'format' => 'A4-P',
			  'orientation' => 'P'
			]
		);

        return $pdf->stream('stock_report.pdf');
    }
	
	public function check()
    {
		/* $stock = ProductShading::all();
		
		$ventura    = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
			'per_page'  => 4200
		]));

        $result       = $ventura->result->data;
        $total_result = $ventura->result->total_data;
		
		$new = array();
		$arrindex = array();
		
		foreach($result as $key => $row){
			foreach($stock as $dps){
				if($row->kode_gudang == $dps->warehouse_code && trim($row->kode_item) == $dps->stock_code){
					$arrindex[] = $key;
				}
			}
		}
		
		foreach($arrindex as $id){
			array_splice($result, $id, 1);
		}
		
		print_r($result);
		
		$data = [
            'title'   		=> 'Stock Check Ventura',
            'items'   		=> $result,
			'total'			=> $total_result,
            'content' 		=> 'admin.master_data.product.stock_check'
        ];

        return view('admin.layouts.index', ['data' => $data]); */
	}
}
