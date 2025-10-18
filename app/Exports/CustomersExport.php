<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class CustomersExport implements FromCollection, WithTitle, WithHeadings, WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    */
	public function __construct(string $search, int $numrow, int $start, string $type)
    {
        $this->search = $search ? $search : '';
        $this->numrow = $numrow;
		$this->start = $start;
		$this->type = $type ? $type : '';
    }
	
	private $headings = [
        'Customer ID', 
        'Profile Photo',
        'Name',
        'Constructor',
        'Email',
		'Phone',
		'Password',
		'Type',
		'Point',
		'Verification',
		'Created',
		'Updated'
    ];
	
    public function collection()
    {
        return Customer::where('name','like','%'.$this->search.'%')
				->where('type','like','%'.$this->type.'%')
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
        return 'Customer Report';
    }
	
	public function startCell(): string
    {
        return 'A2';
    }
}
