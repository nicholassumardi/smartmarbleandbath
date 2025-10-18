<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'type_id',
        'company_id',
        'hs_code_id',
        'brand_id',
        'country_id',
        'supplier_id',
        'grade_id',
        'carton_pallet',
        'carton_pcs',
        'container_standart',
        'container_stock',
        'container_max_stock',
        'description',
		'design',
		'check',
        'status'
    ];

	public function attachment() 
    {
        if(Storage::exists($this->design)) {
            $attachment = asset(Storage::url($this->design));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }

    public function code()
    {
        $division_code = $this->type->division->code;
        $brand_code    = $this->brand->code;
        $country_code  = $this->country->code;
        $type_code     = $this->type->code;

        return $division_code . $brand_code . $country_code . $type_code;
    }

    public function size(){
        $size =  $this->type ? $this->type->length.' cm'.' x ' . $this->type->width.' cm' : 'None';
        
        return $size;
    }

    public function m2Size(){
        if($this->type->selling_unit_id == '1' || $this->type->selling_unit_id == '4'){
            $size = (($this->type->length * $this->type->width ) / 10000) * $this->carton_pcs;
        } else{
            $size = 'Non Tiles';
        }

        return $size;
    }

    public function name()
    {
        $type_code     = $this->type->code ? $this->type->code : 'tak punya type';
        $category_name = $this->type->category->name ? ucwords(strtoupper($this->type->category->name)) : '';
        $brand_name    = $this->brand->name ? ucwords(strtoupper($this->brand->name)) : '';

        //return $type_code . ' ' . $brand_name . ' ' . $category_name;
		return $type_code . ' ' . $brand_name;
    }

	public function check()
    {
        switch($this->check) {
            case '1':
                $check = 'Not Checked';
                break;
            case '2':
                $check = 'Already Checked';
                break;
            default:
                $check = 'Invalid';
                break;
        }

        return $check;
    }

    public function status()
    {
        switch($this->status) {
            case '1':
                $status = '<span class="text-success font-weight-bold">Active</span>';
                break;
            case '2':
                $status = '<span class="text-danger font-weight-bold">Not Active</span>';
                break;
            default:
                $status = '<span class="text-warning font-weight-bold">Invalid</span>';
                break;
        }

        return $status;
    }

    public function containerStandart()
    {
        switch($this->container_standart) {
            case '1':
                $container_standart = '20 Feet';
                break;
            case '2':
                $container_standart = '40 Feet';
                break;
            default:
                $container_standart = 'Invalid';
                break;
        }

        return $container_standart;
    }

    public function price()
    {
        $data = $this->pricingPolicy;
        if($data) {
            // $price = $data->price_list;
            $price = $data->store_price_list;
        } else {
            $price = 0;
        }

        return $price;
    }

    public function availability()
    {
        $data  = $this->productShading;
        $stock = 0;

        if($data) {
            $stock = $data->sum('qty');
        }

        if($stock > 18) {
            $color  = 'badge-success';
            $status = 'Ready';
        } else if($stock > 0 && $stock <= 18) {
            $color  = 'badge-warning';
            $status = 'Limited';
        } else {
            $color  = 'badge-danger';
            $status = 'Ready in 8 weeks';
        }

        return (object)[
            'color'  => $color,
            'status' => $status,
            'stock'  => $stock
        ];
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function hsCode()
    {
        return $this->belongsTo('App\Models\HsCode');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier');
    }

    public function grade()
    {
        return $this->belongsTo('App\Models\Grade');
    }

    public function review($param = null)
    {
        $max_rate   = 5;
        $star       = [];

        if($param) {
            $total_rate = $param;
        } else {
            $total_rate = $this->productReview ? $this->productReview->avg('rate') : 0;
        }

        if($this->orderDetail) {
            $sold = $this->orderDetail()->whereHas('order', function($query) {
                    $query->where('status', 4);
                })
                ->count();
        } else {
            $sold = 0;
        }

        if($total_rate > 0) {
            if($total_rate >= $max_rate) {
                for($i = 1; $i <= $max_rate; $i++) {
                    $star[] = '<i class="icon-star3 text-warning" style="margin-right:2px;"></i>';
                }
            } else {
                if(is_float($total_rate)) {
                    $rating    = floor($total_rate);
                    $rate_half = 1;
                } else {
                    $rating    = $total_rate;
                    $rate_half = 0;
                }

                $rate_active = $rating % $max_rate;
                $rate_empty  = $max_rate - $rate_active - $rate_half;

                for($i = 1; $i <= $rate_active; $i++) {
                    $star[] = '<i class="icon-star3 text-warning" style="margin-right:2px;"></i>';
                }

                for($i = 1; $i <= $rate_half; $i++) {
                    $star[] = '<i class="icon-star-half-full text-warning" style="margin-right:2px;"></i>';
                }

                for($i = 1; $i <= $rate_empty; $i++) {
                    $star[] = '<i class="icon-star-empty text-warning" style="margin-right:2px;"></i>';
                }
            }
        } else {
            for($i = 1; $i <= $max_rate; $i++) {
                $star[] = '<i class="icon-star-empty text-warning" style="margin-right:2px;"></i>';
            }
        }

        return (object)[
            'star' => $star,
            'sold' => $sold
        ];
    }

    public function productShading()
    {
        return $this->hasMany('App\Models\ProductShading');
    }

    public function productReview()
    {
        return $this->hasMany('App\Models\ProductReview');
    }

    public function currencyPrice()
    {
        return $this->hasMany('App\Models\CurrencyPrice');
    }

    public function currencyRate()
    {
        return $this->hasMany('App\Models\CurrencyRate');
    }

    public function orderDetail()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }

    public function projectProduct(){
        return $this->hasMany('App\Models\ProjectProduct');
    }

    public function projectSaleProduct(){
        return $this->hasMany('App\Models\ProjectSaleProduct');
    }

    public function sampleWarehouseProduct(){
        return $this->hasMany('App\Models\ProjectSaleProduct');
    }

    public function pricingPolicy()
    {
        return $this->hasOne('App\Models\PricingPolicy');
    }

    public function cogs()
    {
        return $this->hasOne('App\Models\Cogs');
    }

	public function checkCogs()
	{
		$check = Cogs::where('product_id',$this->id)->first();
		
		if($check){
			return '<button type="button" class="btn bg-info btn-sm" data-popup="tooltip" title="Info" onclick="show(' . $check->id . ')"><i class="icon-info22"></i></button>';
		}else{
			return '<button type="button" class="btn bg-primary btn-sm" data-popup="tooltip" title="Add" onclick="add(' . $this->id . ',`' . $this->name() . '`)"><i class="icon-plus3"></i></button>';
		}
	}

    public function wishlist()
    {
        return $this->hasMany('App\Models\Wishlist');
    }
	
	public function getConvertPrice($currency_id){
		
		$cek = CurrencyRate::where('currency_id',$currency_id)->where('company_id',$this->company_id)->orderBy('id','DESC')->first();
		
		if($cek){
			$rate = $cek->conversion;
			return $rate;
		}else{
			return 0;
		}
	}
}
