<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlProduct extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'al_products';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'code',
        'name',
		'al_product_parent_id',
        'al_category_id',
        'al_supplier_id',
        'description',
        'buy_price',
        'sell_price',
        'unit'
    ];

    public static function generateCode()
    {
        $query = AlProduct::selectRaw('RIGHT(code, 6) as code')
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = (int)$query[0]->code + 1;
        } else {
            $code = '000001';
        }

        return 'PR-' . str_pad($code, 6, 0, STR_PAD_LEFT);
    }

    public function unit() 
    {
        switch($this->unit) {
            case '1':
                $unit = 'Buah';
                break;
            case '2':
                $unit = 'Kotak';
                break;
			case '3':
				$unit = 'Meter';
				break;
			case '4':
				$unit = 'Set';
				break;
			case '5':
				$unit = 'Roll';
				break;
			case '6':
				$unit = 'Coil';
				break;
            default:
                $status = 'Invalid';
                break;
        }

        return $unit;
    }

    public function alCategory()
    {
        return $this->belongsTo('App\Models\AlCategory');
    }
	
	public function alProductParent()
    {
        return $this->belongsTo('App\Models\AlProductParent');
    }
	
	public function alSupplier()
    {
        return $this->belongsTo('App\Models\AlSupplier');
    }

    public function alProductPicture()
    {
        return $this->hasMany('App\Models\AlProductPicture');
    }
	
	public function latestDateSellPrice(){
		$price = AlSphProduct::where('al_product_id',$this->id)->orderByDesc('id')->limit(1)->get();
		if(count($price) > 0){
			return date('d M Y',strtotime($price[0]->updated_at));
		}else{
			return 'Empty';
		}
	}
	
	public function latestDateBuyPrice(){
		$price = AlPurchaseProduct::where('al_product_id',$this->id)->orderByDesc('id')->limit(1)->get();
		if(count($price) > 0){
			return date('d M Y',strtotime($price[0]->updated_at));
		}else{
			return 'Empty';
		}
	}

}
