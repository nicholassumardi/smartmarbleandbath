<?php

namespace App\Exports;

use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class StocksExport implements FromArray, WithTitle, WithHeadings, WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    */
	public function __construct(string $search, string $warehouse, string $code)
    {
        $this->search = $search ? $search : '';
		$this->warehouse = $warehouse ? $warehouse : '';
		$this->code = $code ? $code : '';
    }
	
	private $headings = [
		'Code',
		'Warehouse',
		'Stock',
		'Name',
		'Shading',
        'Type',
		'Group Merk',
        'Warehouse Name'
    ];
	
    public function array(): array
    {
		$stock = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
            'tipe_item' => $this->search,
			'kode_item' => $this->code,
			'gudang' => $this->warehouse,
            'per_page'  => 5000
        ]));
		
		$result = $stock->result->data;
		
        return $result;
    }
	
	public function headings() : array
	{
		return $this->headings;
	}
	
	public function title(): string
    {
        return 'Stock Report';
    }
	
	public function startCell(): string
    {
        return 'A2';
    }
}
