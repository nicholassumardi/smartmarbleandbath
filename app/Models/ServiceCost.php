<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceCost extends Model
{
    use HasFactory;
    protected $table      = 'service_costs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_id',
        'user_id',
        'branch',
        'code',
        'delivery_cost',
		'cutting_cost',
		'misc_cost',
		'note',
		'is_ppn',
		'is_cancel',
		'tax_service',
        'grandtotal_service',
        'image',
        'approved_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function serviceCostPayment()
    {
        return $this->hasMany('App\Models\ServiceCostPayment');
    }

    public static function generateCode()
    {
        $query = ServiceCost::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '000001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SC/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public function updateGrandTotalService(){
		$query = ServiceCost::find($this->id);
		$tax_service = 0;

        if (date('Y-m-d', strtotime($this->created_at)) < '2022-04-01') {
			$persenppn = 0.1;
		} else {
			$persenppn = 0.11;
		}

		$subtotal_service = $query->delivery_cost + $query->cutting_cost + $query->misc_cost;

        if($this->is_ppn == 1){
            $tax_service = $subtotal_service * $persenppn;
        }

        $grandtotal_service = $subtotal_service + $tax_service;
		
		$query->update([
            'tax_service'          => round($tax_service,2),
			'grandtotal_service'   => round($grandtotal_service,2)
		]);
	}

    public function approved()
	{
		return $this->belongsTo('App\Models\User', 'approved_id', 'id');
	}

    public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
		
		if(Storage::exists($this->image_paid)) {
            Storage::delete($this->image_paid);
        }
	}

    public function getPaid()
    {
        $query = ServiceCostPayment::where('service_cost_id', $this->id)->get();
        $total_paid = 0;

        foreach ($query as $row) {
            $total_paid += $row->nominal;
        }

        return $total_paid;
    }

    public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $attachment = asset(Storage::url($this->image));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	

    public function getPercentPay()
	{
		$total = $this->grandtotal_service;
		$totalpaid = 0;
		
		foreach($this->serviceCostPayment as $row){
			$totalpaid += $row->nominal;
		}
		
		$percent = $total ? round(($totalpaid / $total) * 100,0) : 0;
		
		if($percent >= 100){
			$progress = '
				<div class="progress" style="height:0.875rem;">
					<div class="progress-bar progress-bar-striped bg-teal" style="width:100%">
						<span class="font-weight-bold text-uppercase">
							<span style="font-size:13px;">' . $percent . '%</span>
						</span>
					</div>
				</div>
			';
		}else{
			$progress = '
				<div class="progress" style="height:0.875rem;">
					<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
						<span class="font-weight-bold text-uppercase">
							<span style="font-size:13px;">' . $percent . '%</span>
						</span>
					</div>
				</div>
			';
		}
		
		return $progress;
	}
	

}
