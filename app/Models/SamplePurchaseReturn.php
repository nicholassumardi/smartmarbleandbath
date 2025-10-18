<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SamplePurchaseReturn extends Model
{
    use HasFactory;


    protected $table      = 'sample_purchase_returns';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'customer_sample_id',
        'sample_purchase_id',
        'sample_warehouse_id',
        'code',
        'date',
        'warehouse_id',
        'image',
        'note',
        'approved_by'
    ];

    public function approve()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }

    public function customerSample()
    {
        return $this->belongsTo('App\Models\CustomerSample');
    }

    public static function generateCode()
    {
        $query = SamplePurchaseReturn::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if ($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'PR/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    }

    public function samplePurchase()
    {
        return $this->belongsTo('App\Models\SamplePurchase');
    }

    public function samplePurchaseReturnProduct()
    {
        return $this->hasMany('App\Models\SamplePurchaseReturnProduct');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
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

    public function getTotal(){
		$totalPurchase = 0;
		
		$samplepurchase = samplePurchase::find($this->samplePurchase->id);
		
		foreach($this->samplePurchaseReturnProduct as $key => $pi) {
			foreach($samplepurchase->samplePurchaseProduct->where('product_id',$pi->product_id) as $psp){
				if($psp->unit == '2' || $psp->unit == '3'){
					$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
					if($samplepurchase->currency_id !== '5'){
						if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
							$totalPurchase += $psp->price * $pi->qty * $samplepurchase->currency_rate;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($this->samplePurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$totalPurchase += $psp->price * $pi->qty * $samplepurchase->currency_rate;
							}else{
								$totalPurchase += $psp->price * $pi->qty * $m2 * $samplepurchase->currency_rate;
							}
						}
					}else{
						if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
							$totalPurchase += $psp->price * $pi->qty;
						}else{
							if($m2 < 1.1 && date('Y-m',strtotime($this->samplePurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
								$totalPurchase += $psp->price * $pi->qty;
							}else{
								$totalPurchase += $psp->price * $pi->qty * $m2;
							}
						}
					}
				}else{
					if($samplepurchase->currency_id !== '5'){
						$totalPurchase += $psp->price * $pi->qty * $samplepurchase->currency_rate;
					}else{
						$totalPurchase += $psp->price * $pi->qty;
					}
				}
			}
		}
		
		return $totalPurchase;
	}
}
