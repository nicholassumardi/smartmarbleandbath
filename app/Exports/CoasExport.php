<?php

namespace App\Exports;

use App\Models\Coa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class CoasExport implements FromCollection, WithTitle, WithHeadings, WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    */
	public function __construct(string $search, int $numrow, int $start, string $status)
    {
        $this->search = $search ? $search : '';
        $this->numrow = $numrow;
		$this->start = $start;
		$this->status = $status ? $status : '';
    }
	
	private $headings = [
        'Code', 
        'Name',
        'Parent',
        'Status',
		'Created',
		'Updated'
    ];
	
    public function collection()
    {
        return Coa::where('name','like','%'.$this->search.'%')
				->where('status','like','%'.$this->status.'%')
				->offset($this->start)
				->limit($this->numrow)
				->get();
    }
	
	public function headings() : array
	{
		return $this->headings;
	}
	
	public function title(): string
    {
        return 'Coa Report';
    }
	
	public function startCell(): string
    {
        return 'A2';
    }
}
