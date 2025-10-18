<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectQuotationProduct extends Model {

    use HasFactory;

    protected $table      = 'project_quotation_products';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'project_quotation_id',
        'product_id',
		'area',
		'price',
        'recommended_price',
        'best_price',
		'discount',
		'qty',
		'unit'
    ];
	
	public function siteArea()
	{
		$project = $this->projectQuotation->project;
		
		$area = '';
		
		foreach($project->projectProduct->where('product_id',$this->product_id) as $row){
			$area = $row->area;
		}
		
		return $area;
	}
	
	public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
	
	public function projectQuotation()
    {
        return $this->belongsTo('App\Models\ProjectQuotation','project_quotation_id','id');
    }
	
	public function spec()
    {
        $product = ProjectProduct::where('product_id',$this->product_id)->where('project_id',$this->projectQuotation->project_id)->latest()->first();
		
		if($product){
			return $product->spec;
		}else{
			return '-';
		}
    }
	
	public function unit()
    {
        switch($this->unit) {
            case '1':
                $unit = 'Pcs';
                break;
            case '2':
                $unit = 'Box';
                break;
            case '3':
                $unit = 'Meter';
                break;
            case '4':
                $unit = 'Meter (Custom)';
                break;
            default:
                $unit = 'Invalid';
                break;
        }

        return $unit;
    }
}
