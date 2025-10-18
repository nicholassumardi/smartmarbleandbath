<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamplePurchase extends Model
{
    use HasFactory;

    protected $table      = 'sample_purchases';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'customer_sample_id',
        'sample_id',
        'ppn',
        'code',
        'note',
        'supplier_id',
        'production_lead_time',
        'estimated_delivery',
        'estimated_arrival',
        'factory_name',
        'customer_id',
        'sales_id',
        'on_behalf',
        'delivery_address',
        'country_id',
        'city_id',
        'courier_method',
        'pic',
        'pic_no',
        'payment_method',
        'payment_due_date',
        'price',
        'currency_id',
        'currency_rate',
        'brand_on_box',
        'sni',
        'is_wip',
        'has_memo_item',
        'memo_address_item',
        'memo_up',
        'checked_by',
        'approved_by',
        'subtotal',
        'tax',
        'grandtotal'
    ];

    public function ppn()
    {
        switch ($this->ppn) {
            case '1':
                $ppn = 'Yes';
                break;
            case '0':
                $ppn = 'No';
                break;
            default:
                $ppn = 'Invalid';
                break;
        }

        return $ppn;
    }

    public function has_memo()
    {
        switch ($this->has_memo_item) {
            case '1':
                $has_memo = 'Yes';
                break;
            case '0':
                $has_memo = 'No';
                break;
            default:
                $has_memo = 'Invalid';
                break;
        }

        return $has_memo;
    }

    public function fee_pta()
    {
        switch ($this->fee_pta) {
            case '1':
                $fee = 'Yes';
                break;
            case '0':
                $fee = 'No';
                break;
            default:
                $fee = 'Invalid';
                break;
        }

        return $fee;
    }

    public function courier()
    {
        switch ($this->courier_method) {
            case '1':
                $method = 'FCL';
                break;
            case '2':
                $method = 'LCL';
                break;
            default:
                $method = 'Invalid';
                break;
        }

        return $method;
    }

    public function price()
    {
        switch ($this->price) {
            case '1':
                $price = 'FOB';
                break;
            case '2':
                $price = 'EXW';
                break;
            case '3':
                $price = 'Franco';
                break;
            case '4':
                $price = 'CIF';
                break;
            default:
                $price = 'Invalid';
                break;
        }

        return $price;
    }

    public function is_wip()
    {
        switch ($this->is_wip) {
            case '0':
                $wip = 'Normal PO';
                break;
            case '1':
                $wip = 'WIP PO';
                break;
            default:
                $wip = 'Invalid';
                break;
        }

        return $wip;
    }

    public function checked()
    {
        return $this->belongsTo('App\Models\User', 'checked_by', 'id');
    }

    public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    public function sales()
    {
        return $this->belongsTo('App\Models\User', 'sales_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city_id', 'id');
    }

    public function sample()
    {
        return $this->belongsTo('App\Models\Sample');
    }

    public function sampleProforma()
    {
        return $this->hasMany('App\Models\SampleProforma');
    }

    public function sampleShipment()
    {
        return $this->hasMany('App\Models\SampleShipment');
    }

    public function purchaseCost()
    {
        return $this->hasOne('App\Models\PurchaseCost');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function customerSample()
    {
        return $this->belongsTo('App\Models\CustomerSample');
    }

    public static function generateCode()
    {
        $query = SamplePurchase::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if ($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '000001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SMPO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }

    public function samplePurchaseProduct()
    {
        return $this->hasMany('App\Models\SamplePurchaseProduct');
    }


    public function sampleWarehouse()
    {
        return $this->hasMany('App\Models\SampleWarehouse');
    }

    public function transfer()
    {
        return $this->hasMany('App\Models\Transfer', 'project_purchase_id', 'id');
    }

    public function projectPurchaseBill()
    {
        return $this->hasMany('App\Models\ProjectPurchaseBill');
    }

    public function samplePurchaseReturn()
    {
        return $this->hasMany('App\Models\SamplePurchaseReturn');
    }

    public function getTotal()
    {
        $sample = SamplePurchase::find($this->id);

        $total = 0;

        foreach ($sample->samplePurchaseProduct as $key => $pp) {

            if ($sample->currency_id !== '5') {
                if ($pp->unit == '2' || $pp->unit == '3') {
                    $m2 = (($pp->product->type->length * $pp->product->type->width) / 10000) * $pp->product->carton_pcs;
                    if ($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18) {
                        $total += $pp->price * $sample->currency_rate * $pp->qty;
                    } else {
                        if ($m2 < 1.1 && date('Y-m', strtotime($sample->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18) {
                            $total += $pp->price * $sample->currency_rate * $pp->qty;
                        } else {
                            $total += $pp->price * $sample->currency_rate * $pp->qty * $m2;
                        }
                    }
                }

                if ($pp->unit == '1' || $pp->unit == '4') {
                    $total += $pp->price * $sample->currency_rate * $pp->qty;
                }
            } else {
                if ($pp->unit == '2' || $pp->unit == '3') {
                    $m2 = (($pp->product->type->length * $pp->product->type->width) / 10000) * $pp->product->carton_pcs;
                    if ($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18) {
                        $total += $pp->price * $pp->qty;
                    } else {
                        if ($m2 < 1.1 && date('Y-m', strtotime($sample->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18) {
                            $total += $pp->price * $pp->qty;
                        } else {
                            $total += $pp->price * $pp->qty * $m2;
                        }
                    }
                }

                if ($pp->unit == '1' || $pp->unit == '4') {
                    $total += $pp->price * $pp->qty;
                }
            }
        }

        return number_format($total, 0, ',', '.');
    }


    public function updateGrandtotal()
    {
        $subtotal = str_replace(',', '.', str_replace('.', '', $this->getTotal()));

        $persenppn = 0;

        if ($this->ppn == '1') {
            if (date('Y-m-d', strtotime($this->created_at)) < '2022-04-01') {
                $persenppn = 0.1;
            } else {
                $persenppn = 0.11;
            }
        }

        $tax = $this->ppn == '1' ? ($subtotal * $persenppn) : 0;

        $grandtotal = $subtotal + $tax;

        SamplePurchase::find($this->id)->update([
            'subtotal'       => round($subtotal, 2),
            'tax'            => round($tax, 2),
            'grandtotal'     => round($grandtotal, 2)
        ]);
    }
}
