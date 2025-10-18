<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SampleWarehouse extends Model
{
    use HasFactory;
    protected $table      = 'sample_warehouses';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'customer_sample_id',
        'sample_purchase_id',
        'sample_shipment_id',
        'code',
        'person',
        'image',
        'date_receive',
        'warehouse_id',
        'include_cost',
        'subtotal',
        'tax',
        'grandtotal'
    ];

    public function includeCost()
    {
        switch ($this->include_cost) {
            case '1':
                $include_cost = 'Yes';
                break;
            case '0':
                $include_cost = 'No';
                break;
            default:
                $include_cost = 'Invalid';
                break;
        }

        return $include_cost;
    }

    public function attachment()
    {
        if (Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }

    public function deleteFile()
    {
        if (Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
    }

    public static function generateCode()
    {
        $query = SampleWarehouse::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if ($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SMWR/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public function getTotal(){
		$sample = SampleWarehouse::find($this->id);
		
		$totalpurchase = 0;
		
		foreach($sample->sampleWarehouseProduct as $key => $ps){
            $psp = $sample->samplePurchase->samplePurchaseProduct->where('product_id',$ps->product_id)->first();
            
            $totalpurchase += $ps->qty * $psp->price;
            
		}
		
		$arr = [
			'totalpurchase' => $totalpurchase
		];
		
		return $arr;
	}


    public function updateGrandtotal(){
		
		$grandtotal = $this->getTotal()['totalpurchase'];
		
		$subtotal = $this->getTotal()['totalpurchase'];
		
		$tax = $grandtotal - $subtotal;
		
		SampleWarehouse::find($this->id)->update([
			'subtotal' 				=> round($subtotal),
			'tax'					=> round($tax),
			'grandtotal'			=> round($grandtotal)
		]);
	}

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    }

    public function sampleWarehouseProduct()
    {
        return $this->hasMany('App\Models\SampleWarehouseProduct');
    }

    public function samplePurchase()
    {
        return $this->belongsTo('App\Models\SamplePurchase');
    }

    public function sampleShipment()
    {
        return $this->belongsTo('App\Models\SampleShipment');
    }

    public function customerSample()
    {
        return $this->belongsTo('App\Models\CustomerSample');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
