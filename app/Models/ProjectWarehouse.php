<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectWarehouse extends Model {

    use HasFactory;

    protected $table      = 'project_warehouses';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'user_id',
        'project_id',
		'project_purchase_id',
		'project_shipment_id',
		'code',
		'person',
        'image',
        'date_receive',
        'warehouse_id',
		'include_cost',
		'coa_id',
		'subtotal',
		'tax',
		'grandtotal'
    ];
	
	public function includeCost(){
        switch($this->include_cost) {
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
	
	public static function generateCode()
    {
        $query = ProjectWarehouse::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'WR/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse','warehouse_id','id');
    }
	
	public function projectWarehouseProduct()
    {
        return $this->hasMany('App\Models\ProjectWarehouseProduct');
    }
	
	public function projectPurchase()
    {
        return $this->belongsTo('App\Models\ProjectPurchase', 'project_purchase_id', 'id');
    }
	
	public function projectShipment()
    {
        return $this->belongsTo('App\Models\ProjectShipment', 'project_shipment_id', 'id');
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
	
	public function getTotal(){
		$project = ProjectWarehouse::find($this->id);
		
		$totalpurchase = 0;
		
		foreach($project->projectWarehouseProduct as $key => $ps){
			if($ps->unit == '2' || $ps->unit == '3'){

				// Mencari item di dalam PO dengan nama sama tetapi dengan qty dan harga berbeda
				$findDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id',$ps->product_id)->where('qty', $ps->qty)->first();

				$findNonDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id',$ps->product_id)->first();

				$psp = $findDuplicateItems ? $findDuplicateItems : $findNonDuplicateItems;
				
				$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
				
				if($project->projectPurchase->currency_id !== '5'){
					if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
						$totalpurchase += $ps->qty * $psp->price * $project->projectPurchase->currency_rate;
					}else{
						if($m2 < 1.1 && date('Y-m',strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
							$totalpurchase += $ps->qty * $psp->price * $project->projectPurchase->currency_rate;
						}else{
							$totalpurchase += $ps->qty * $m2 * $psp->price * $project->projectPurchase->currency_rate;
						}
						
					}
				}else{
					if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
						$totalpurchase += $ps->qty * $psp->price;
					}else{
						if($m2 < 1.1 && date('Y-m',strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
							$totalpurchase += $ps->qty * $psp->price;
						}else{
							$totalpurchase += $ps->qty * $m2 * $psp->price;
						}
						
					}
				}
			}
			
			if($ps->unit == '1' || $ps->unit == '4'){
				// Mencari item di dalam PO dengan nama sama tetapi dengan qty dan harga berbeda
				$findDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id',$ps->product_id)->where('qty', $ps->qty)->first();

				$findNonDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id',$ps->product_id)->first();

				$psp = $findDuplicateItems ? $findDuplicateItems : $findNonDuplicateItems;
				
				if($project->projectPurchase->currency_id !== '5'){
					$totalpurchase += $ps->qty * $psp->price * $project->projectPurchase->currency_rate;
				}else{
					$totalpurchase += $ps->qty * $psp->price;
				}
			}
		}
		
		$arr = [
			'totalpurchase' => $totalpurchase
		];
		
		return $arr;
	}
	
	public function getTotalInventory(){
		$project = ProjectWarehouse::find($this->id);
		
		$totalpurchase = 0;
		
		if($project->projectPurchase->ppn == '1'){
			if(date('Y-m-d',strtotime($project->projectPurchase->created_at)) < '2022-04-01'){
				$ppnpembagi = 1.1;
			}else{
				$ppnpembagi = 1.11;
			}
		}else{
			$ppnpembagi = 1;
		}
		
		foreach($project->projectWarehouseProduct as $key => $ps){
			if($ps->unit == '2' || $ps->unit == '3'){
				// Mencari item di dalam PO dengan nama sama tetapi dengan qty dan harga berbeda
				$findDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id',$ps->product_id)->where('qty', $ps->qty)->first();

				$findNonDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id',$ps->product_id)->first();

				$psp = $findDuplicateItems ? $findDuplicateItems : $findNonDuplicateItems;
				
				$m2 = (( $psp->product->type->length * $psp->product->type->width ) / 10000) * $psp->product->carton_pcs;
				
				if($project->projectPurchase->currency_id !== '5'){
					if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
						$totalpurchase += $ps->qty * $psp->price * $project->projectPurchase->currency_rate;
					}else{
						if($m2 < 1.1 && date('Y-m',strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
							$totalpurchase += $ps->qty * $psp->price * $project->projectPurchase->currency_rate;
						}else{
							$totalpurchase += $ps->qty * $m2 * $psp->price * $project->projectPurchase->currency_rate;
						}
					}
				}else{
					if($m2 < 1.1 && $psp->product->type->category->parent()->id !== 18){
						$totalpurchase += $ps->qty * $psp->price;
					}else{
						if($m2 < 1.1 && date('Y-m',strtotime($project->projectPurchase->created_at)) < '2022-06' && $psp->product->type->category->parent()->id == 18){
							$totalpurchase += $ps->qty * $psp->price;
						}else{
							$totalpurchase += $ps->qty * $m2 * $psp->price;
						}
					}
				}
			}
			
			if($ps->unit == '1' || $ps->unit == '4'){
				// Mencari item di dalam PO dengan nama sama tetapi dengan qty dan harga berbeda
				$findDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id',$ps->product_id)->where('qty', $ps->qty)->first();

				$findNonDuplicateItems = $project->projectPurchase->projectPurchaseProduct->where('product_id',$ps->product_id)->first();

				$psp = $findDuplicateItems ? $findDuplicateItems : $findNonDuplicateItems;
				
				if($project->projectPurchase->currency_id !== '5'){
					$totalpurchase += $ps->qty * $psp->price * $project->projectPurchase->currency_rate;
				}else{
					$totalpurchase += $ps->qty * $psp->price;
				}
			}
		}
		
		$totalpurchase = $totalpurchase / $ppnpembagi;
		
		return $totalpurchase;
	}
	
	public function purchaseRequest(){
		return $this->hasOne('App\Models\PurchaseRequest');
	}
	
	public function updateGrandtotal(){
			
		if($this->projectPurchase->ppn == '1'){
			if(date('Y-m-d',strtotime($this->projectPurchase->created_at)) < '2022-04-01'){
				$ppnpembagi = 1.1;
			}else{
				$ppnpembagi = 1.11;
			}
		}else{
			$ppnpembagi = 1;
		}
		
		$grandtotal = $this->getTotal()['totalpurchase'];
		
		$subtotal = $this->getTotal()['totalpurchase'] / $ppnpembagi;
		
		$tax = $grandtotal - $subtotal;
		
		ProjectWarehouse::find($this->id)->update([
			'subtotal' 				=> round($subtotal),
			'tax'					=> round($tax),
			'grandtotal'			=> round($grandtotal)
		]);
	}


}
