<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SampleDelivery extends Model
{
    use HasFactory;
    protected $table      = 'sample_deliveries';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'id',
        'user_id',
        'customer_sample_id',
        'sample_id',
        'code',
        'city_id',
        'receiver_name',
        'delivery_date',
        'received_date',
        'due_date',
        'due_date_tt',
        'image_tt',
        'email',
        'phone',
        'is_dropshipper',
        'dropshipper_id',
        'address',
        'warehouse_id',
        'vendor_id',
        'approved_by',
        'acknowledged_by',
        'image',
        'proforma_code',
        'is_sales',
        'pick_up_name',
        'pick_up_plat',
        'pick_up_vehicle',
        'subtotal_product',
        'tax_product',
        'grandtotal_product',
        'subtotal_service',
        'tax_service',
        'grandtotal_service',
        'service_note',
        'note',
    ];

    public function approve()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }

    public function acknowledge()
    {
        return $this->belongsTo('App\Models\User', 'acknowledged_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function isDropshipper()
    {
        switch ($this->is_dropshipper) {
            case '2':
                $status = 'Yes';
                break;
            case '1':
                $status = 'No';
                break;
            default:
                $status = 'Invalid';
                break;
        }

        return $status;
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function dropshipper()
    {
        return $this->belongsTo('App\Models\Dropshipper', 'dropshipper_id', 'id');
    }

    public function sample()
    {
        return $this->belongsTo('App\Models\Sample');
    }

    public function customerSample()
    {
        return $this->belongsTo('App\Models\CustomerSample');
    }

    public function sampleDeliveryProduct()
    {
        return $this->hasMany('App\Models\SampleDeliveryProduct');
    }

    public function sampleDeliveryTrack()
    {
        return $this->hasMany('App\Models\SampleDeliveryTrack');
    }

    public static function generateCode()
    {
        $query = SampleDelivery::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if ($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SMDO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public static function generateCodeProforma()
    {
        $query = SampleDelivery::selectRaw("RIGHT(proforma_code, 6) as proforma_code")
            ->orderByRaw('RIGHT(proforma_code, 6) DESC')
            ->limit(1)
            ->get();

        if ($query->count() > 0) {
            $number = (int)$query[0]->proforma_code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SMINV/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
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

    public function attachment2()
    {
        if (Storage::exists($this->image_tt)) {
            $image = asset(Storage::url($this->image_tt));
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

        if (Storage::exists($this->image_tt)) {
            Storage::delete($this->image_tt);
        }
    }

    public function getTotal()
    {
        $sample = SampleDelivery::find($this->id);
        $samplepurchase = SamplePurchase::where('sample_id', $this->sample_id)->orderBy('created_at', 'desc')->first();
        $totalpurchase = 0;

        foreach ($sample->sampleDeliveryProduct as $key => $ps) {
            $ssp = SamplePurchaseProduct::where('sample_purchase_id', $samplepurchase->id)->where('product_id', $ps->product_id)->first();
            $totalpurchase += $ps->qty * $ssp->price;
        }

        $arr = [
            'totalpurchase' => $totalpurchase
        ];

        return $arr;
    }

    public function getTotalRaw()
    {
        $sample = SampleDelivery::find($this->id);
        $samplepurchase = SamplePurchase::where('sample_id', $this->sample_id)->orderBy('created_at', 'desc')->first();

        $total = 0;
        foreach ($sample->sampleDeliveryProduct as $key => $ps) {
            $ssp = SamplePurchaseProduct::where('sample_purchase_id', $samplepurchase->id)->where('product_id', $ps->product_id)->first();
            if ($ps->unit == '2' || $ps->unit == '3') {
                $m2 = (($ps->product->type->length * $ps->product->type->width) / 10000) * $ps->product->carton_pcs;


                if ($m2 < 1.1 && $ps->product->type->category->parent()->id !== 18) {
                    $countbox = $ps->qty;
                    $total += $ssp->price * $ps->qty;
                } else {
                    if ($m2 < 1.1 && $ps->product->type->category->parent()->id == 18 && date('Y-m', strtotime($sample->sample->created_at)) < '2022-06') {
                        $countbox = $ps->qty;
                        $total += $ssp->price * $ps->qty;
                    } else {
                        $countbox = ceil(round($ps->qty / $m2, 2));
                        $total += $ssp->price * $m2 * $ps->qty;
                    }
                }
            }

            if ($ps->unit == '1' || $ps->unit == '4') {
                $total += $ssp->price * $ps->qty;
            }
        }

        return $total;
    }

    public function updateGrandtotal()
    {
        $subtotal_product = $this->getTotalRaw();



        $grandtotal_product = $subtotal_product;


        SampleDelivery::find($this->id)->update([
            'subtotal_product'      => round($subtotal_product, 2),
            'grandtotal_product'    => round($grandtotal_product, 2),
        ]);
    }

    public function isFirstDelivery()
    {
        $status = false;

        if (count($this->sample->sampleDelivery) > 0) {
            if ($this->id == $this->sample->sampleDelivery()->first()->id) {
                if ($this->grandtotal_service) {
                    $status = true;
                }
            }
        }

        return $status;
    }
}
