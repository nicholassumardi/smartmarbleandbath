<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Transfer extends Model {

    use HasFactory;

    protected $table      = 'transfers';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'code',
		'note',
		'date',
		'from_warehouse_id',
		'to_warehouse_id',
		'image',
		'for_starting',
		'for_customer',
		'for_correction',
		'for_out_transfer',
		'for_in_transfer',
		'for_broken',
		'customer_id',
		'project_purchase_id',
		'customer_address',
		'customer_pic',
		'nominal',
		'status',
		'branch',
		'approved_by'
    ];
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }
	
	public function transferProduct()
    {
        return $this->hasMany('App\Models\TransferProduct');
    }
	
	public static function generateCode()
    {
        $query = Transfer::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'WE/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	public function warehouseFrom()
    {
        return $this->belongsTo('App\Models\Warehouse','from_warehouse_id','id');
    }
	
	public function warehouseTo()
    {
        return $this->belongsTo('App\Models\Warehouse','to_warehouse_id','id');
    }
	
	public function projectPurchase()
    {
        return $this->belongsTo('App\Models\ProjectPurchase','project_purchase_id','id');
    }
	
	public function attachment() 
    {
        if(Storage::exists($this->image)) {
            $image = asset(Storage::url($this->image));
        } else {
            $image = asset('website/empty.jpg');
        }

        return $image;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->image)) {
            Storage::delete($this->image);
        }
	}
	
	public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
	
	public function status()
    {
        switch($this->status) {
            case '1':
                $status = 'Sample not returned without pay.';
                break;
            case '2':
                $status = 'Sample not returned but pay.';
                break;
            case '3':
                $status = 'Sample returned to supplier.';
                break;
			default:
                $status = 'Invalid';
                break;
        }

        return $status;
    }
	
	public function branch()
    {
        switch($this->branch) {
            case '1':
                $branch = 'PTA';
                break;
            case '2':
                $branch = 'SMB';
                break;
             case '3':
                $branch = 'MKJ';
                break;
            case '4':
                $branch = 'PSI';
                break;
			default:
                $branch = 'Invalid';
                break;
        }

        return $branch;
    }
	
	public function getTotal(){
		$total = 0;
		
		if($this->status == '1' || $this->status == '3'){
			$purchase = ProjectPurchase::find($this->project_purchase_id);
			if($purchase){
				$total = str_replace(',','.',str_replace('.','',$purchase->getTotal()));
			}
		}
		
		return $total;
	}
}
