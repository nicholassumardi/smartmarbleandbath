<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBill extends Model
{

	use HasFactory;

	protected $table      = 'project_bills';
	protected $primaryKey = 'id';
	protected $fillable   = [
		'id',
		'user_id',
		'project_id',
		'project_sale_id',
		'coa_id',
		'branch',
		'code',
		'date',
		'due_date',
		'nominal',
		'nominal_service',
		'address',
		'note',
		'checked_id',
		'approved_id'
	];

	public function check()
	{
		return $this->belongsTo('App\Models\User', 'checked_id', 'id');
	}

	public function journal()
	{
		$ada = false;

		$cb = CashBank::where('lookable_type', 'project_bills')->where('lookable_id', $this->id)->first();

		if ($cb) {
			$ada = true;
		}

		return $ada;
	}

	public function cashFlow()
	{
		$cf = CashFlow::where('type', 'project_bills')->where('type_id', $this->id)->first();

		if ($cf) {
			return $cf;
		} else {
			return '';
		}
	}

	public function coa()
	{
		return $this->belongsTo('App\Models\Coa');
	}

	public function approved()
	{
		return $this->belongsTo('App\Models\User', 'approved_id', 'id');
	}

	public function balance()
	{
		$total = $this->nominal + $this->nominal_service;

		$pays = 0;

		foreach (ProjectPay::where('project_bill_id', $this->id)->get() as $row) {
			$pays += $row->nominal;
		}

		$totalcb = 0;

		$cb = CashBank::where('lookable_type', 'projects')->where('code', 'not like', "FEE-PTA%")->where('code', 'not like', "FEE-SMB%")->where('lookable_id', $this->project->id)->get();

		if (count($cb) > 0) {
			foreach ($cb as $rowcb) {
				foreach ($rowcb->cashBankDetail()->where('coa_id', 27)->get() as $cbcb) {
					if ($cbcb->type == '2') {
						$totalcb += $cbcb->nominal;
					}
				}
			}
		}

		return round($total - $pays - $totalcb);
	}

	public function balancePeriod($filter)
	{
		$total = $this->nominal + $this->nominal_service;

		$pays = 0;

		$whereRaw = strlen($filter) == 7 ? "LEFT(date, 7) <= '$filter'" : "date <= '$filter'";
		$whereRaw2 = strlen($filter) == 7 ? "LEFT(date_return, 7) <= '$filter'" : "date_return <= '$filter'";

		foreach (ProjectPay::where('project_bill_id', $this->id)->whereRaw($whereRaw)->get() as $row) {
			$pays += $row->nominal;
		}

		/* foreach($this->project->projectSaleReturn()->whereRaw($whereRaw2)->get() as $rsr){
			$pays += round($rsr->grandtotal);
		} */

		$totalcb = 0;

		$cb = CashBank::where('lookable_type', 'projects')->where('code', 'not like', "FEE-PTA%")->where('code', 'not like', "FEE-SMB%")->where('lookable_id', $this->project->id)->whereRaw($whereRaw)->get();

		if (count($cb) > 0) {
			foreach ($cb as $rowcb) {
				foreach ($rowcb->cashBankDetail()->where('coa_id', 27)->get() as $cbcb) {
					if ($cbcb->type == '2') {
						$totalcb += $cbcb->nominal;
					}
				}
			}
		}

		return round($total - $pays - $totalcb);
	}

	public function paidPeriod($date)
	{
		$pays = 0;

		foreach (ProjectPay::where('project_bill_id', $this->id)->whereRaw("date <= '$date'")->get() as $row) {
			$pays += $row->nominal;
		}

		return round($pays);
	}

	public function paidAR()
	{
		$pays = 0;
		$isExist = array();
		$coa_name = '';

		foreach (ProjectPay::where('project_bill_id', $this->id)->get() as $row) {
			$pays += $row->nominal;

			if(!in_array($row->coa_id, $isExist)){
				$coa_name .= $row->coa->name.'<br>';
				array_push($isExist, $row->coa_id);
			}
		}

		$arrResult = [
			'pays'      => round($pays),
			'coa_name'   => $coa_name
		];

		return $arrResult;
	}

	public function paid()
	{
		$pays = 0;

		foreach (ProjectPay::where('project_bill_id', $this->id)->get() as $row) {
			$pays += $row->nominal;
		}

		return round($pays);
	}

	public function balanceJournal()
	{
		$total = 0;

		$cb = CashBank::where('lookable_type', 'project_bills')->where('lookable_id', $this->id)->get();

		foreach ($cb as $c) {
			foreach ($c->cashBankDetail()->where('coa_id', 67)->get() as $rowcb) {
				if ($rowcb->type == '2') {
					$total += $rowcb->nominal;
				} elseif ($rowcb->type == '1') {
					$total -= $rowcb->nominal;
				}
			}
		}

		return $total;
	}

	public static function generateCode()
	{
		$query = ProjectBill::selectRaw("RIGHT(code, 6) as code")
			->orderByRaw('RIGHT(code, 6) DESC')
			->limit(1)
			->get();

		if ($query->count() > 0) {
			$number = (int)$query[0]->code + 1;
		} else {
			$number = '0001';
		}

		$code = str_pad($number, 6, 0, STR_PAD_LEFT);
		return 'BI/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
	}

	public function projectPay()
    {
        return $this->hasMany('App\Models\ProjectPay');
    }

	public function projectSale()
	{
		return $this->belongsTo('App\Models\ProjectSale', 'project_sale_id', 'id');
	}

	public function project()
	{
		return $this->belongsTo('App\Models\Project', 'project_id', 'id');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}
}
