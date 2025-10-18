<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPurchaseQuotation extends Model
{
    use HasFactory;
    protected $table      = 'project_purchase_quotations';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'user_id',
		'project_id',
		'code',
		'note',
		'supplier_id',
		'sales_id',
		'approved_by',
	];


	public static function generateCode()
	{
		$query = ProjectPurchaseQuotation::selectRaw("RIGHT(code, 6) as code")
			->orderByRaw('RIGHT(code, 6) DESC')
			->limit(1)
			->get();

		if ($query->count() > 0) {
			$number = (int)$query[0]->code + 1;
		} else {
			$number = '000001';
		}

		$code = str_pad($number, 6, 0, STR_PAD_LEFT);
		return 'RQ/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
	}

	public function project(){
		return $this->belongsTo('App\Models\Project', 'project_id', 'id');
	}

	public function supplier(){
		return $this->belongsTo('App\Models\Supplier');
	}

	public function user(){
		return $this->belongsTo('App\Models\User');
	}

	public function sales(){
		return $this->belongsTo('App\Models\User', 'sales_id', 'id');
	}

	public function projectPurchaseQuotationProduct(){
		return $this->hasMany('App\Models\ProjectPurchaseQuotationProduct');
	}
}
