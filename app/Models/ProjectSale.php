<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProjectSale extends Model {

    use HasFactory;

    protected $table      = 'project_sales';
    protected $primaryKey = 'id';
    protected $fillable   = [
		'id',
		'user_id',
        'project_id',
		'sales_id',
        'code',
		'address',
		'note',
		'so_file',
        'marketing_id',
        'approved_id',
		'delivery_cost',
		'cutting_cost',
		'misc_cost',
		'misc_note',
		'ppn_cost',
		'mid_yes_no',
		'mid_type',
		'mid_fee',
		'mid_director',
		'mid_note',
		'currency_id',
		'currency_rate',
		'is_closed',
		'date_closed',
		'reason_closed',
		'approved_closed',
		'subtotal_product',
		'tax_product',
		'grandtotal_product',
		'subtotal_service',
		'tax_service',
		'grandtotal_service',
		'created_at',
		'letter_head',
    ];
	
	public function attachment() 
    {
        if(Storage::exists($this->so_file)) {
            $attachment = asset(Storage::url($this->so_file));
        } else {
            $attachment = asset('website/empty.jpg');
        }

        return $attachment;
    }
	
	public function deleteFile(){
		if(Storage::exists($this->so_file)) {
            Storage::delete($this->so_file);
        }
	}
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
	
	public function sales()
    {
        return $this->belongsTo('App\Models\User', 'sales_id', 'id');
    }
	
	public function marketing()
    {
        return $this->belongsTo('App\Models\User', 'marketing_id', 'id');
    }
	
	public function approveClose()
    {
        return $this->belongsTo('App\Models\User', 'approved_closed', 'id');
    }
	
	public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_id', 'id');
    }
	
	public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }
	
	public static function generateCode()
    {
        $query = ProjectSale::selectRaw("RIGHT(code, 6) as code")
            ->orderByRaw('RIGHT(code, 6) DESC')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $number = (int)$query[0]->code + 1;
        } else {
            $number = '0001';
        }

        $code = str_pad($number, 6, 0, STR_PAD_LEFT);
        return 'SO/' . date('y') . '/' . date('m') . '/' . date('d') . '/' . $code;
    }
	
	public function projectSaleProduct()
    {
        return $this->hasMany('App\Models\ProjectSaleProduct');
    }
	
	public function projectSaleShading()
    {
        return $this->hasMany('App\Models\ProjectSaleShading');
    }
	
	public function projectSalePay()
    {
        return $this->hasMany('App\Models\ProjectPay');
    }
	
	public function projectSaleReturn()
    {
        return $this->hasMany('App\Models\ProjectSaleReturn');
    }
	
	public function projectPurchase()
    {
        return $this->hasMany('App\Models\ProjectPurchase');
    }
	
	public function projectFromStock()
    {
        return $this->hasMany('App\Models\ProjectFromStock');
    }
	
	public function projectDelivery(){
		return $this->hasMany('App\Models\ProjectDelivery');
	}
	
	public function getTotalRawPlusService(){
		$project = ProjectSale::find($this->id);
		
		$total = $project->subtotal_product + $project->subtotal_service;
		
		return number_format($total,0,',','.');
	}
	
	public function getTotalRaw(){
		$project = ProjectSale::find($this->id);
		
		$total = 0;
		
		foreach($project->projectSaleProduct as $key => $pp){
			$price = 0;
				
			if($pp->best_price){
				$price = $pp->best_price;
			}elseif($pp->recommended_price){
				$price = $pp->recommended_price;
			}
			
			if($pp->unit == '2' || $pp->unit == '3'){
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				
				
				
				if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
					$countbox = ceil($pp->qty);
					$total += $price * $countbox;
				}else{
					if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
						$countbox = ceil($pp->qty);
						$total += $price * $countbox;
					}else{
						$countbox = ceil(round($pp->qty / $m2,2));
						$total += $price * $m2 * $countbox;
					}
					
				}
				
			}
			
			if($pp->unit == '1' || $pp->unit == '4'){
				$total += $price * $pp->qty;
			}
		}
		
		return number_format($total - $project->project->discount,2,',','.');
	}
	
	public function getTotalMiddleman(){
		$project = ProjectSale::find($this->id);
		
		$total = 0;
		
		if($this->mid_type == '1'){
			$total = ($this->mid_fee * $project->subtotal_product) / 100;
		}elseif($this->mid_type == '2'){
			foreach($project->projectSaleProduct as $key => $pp){
				if($pp->unit == '2' || $pp->unit == '3'){
					$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
					if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
						$countbox = ceil($pp->qty);
						$total += $this->mid_fee * $countbox;
					}else{
						if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
							$countbox = ceil($pp->qty);
							$total += $this->mid_fee * $countbox;
						}else{
							$countbox = ceil(round($pp->qty / $m2,2));
							$total += $this->mid_fee * $m2 * $countbox;
						}
						
					}
					
				}
				
				if($pp->unit == '1' || $pp->unit == '4'){
					$total += $this->mid_fee * $pp->qty;
				}
			}
			
		}elseif($this->mid_type == '3'){
			$total =  $this->mid_fee;
		}
		
		return number_format($total,0,',','.');
	}
	
	public function getTotal()
	{
		$project = ProjectSale::find($this->id);
		
		if(date('Y-m-d',strtotime($project->created_at)) < '2022-04-01'){
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		}else{
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}
		
		$total = 0;
		$cost = 0;
		
		foreach($project->projectSaleProduct as $key => $pp){
			$price = $pp->best_price ? $pp->best_price : $pp->recommended_price;
			
			if($pp->unit == '2' || $pp->unit == '3'){
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				
				if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
					$countbox = ceil($pp->qty);
					$total += $price * $countbox;
				}else{
					if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
						$countbox = ceil($pp->qty);
						$total += $price * $countbox;
					}else{
						$countbox = ceil(round($pp->qty / $m2,2));
						$total += $price * $m2 * $countbox;
					}
					
				}
				
			}
			
			if($pp->unit == '1' || $pp->unit == '4'){
				$total += $price * $pp->qty;
			}
		}
		
		if($project->project->discount){
			$total -= $project->project->discount;
		}
		
		$total = $project->project->ppn == '1' ? $total + ($persenppn * $total) : $total;
		
		$cost = $project->ppn_cost == 1 ? ($project->delivery_cost + $project->cutting_cost + $project->misc_cost) + ($persenppn * ($project->delivery_cost +$project->cutting_cost + $project->misc_cost)) : ($project->delivery_cost + $project->cutting_cost + $project->misc_cost);
		
		return number_format($total + $cost,0,',','.');
	}
	
	public function getPaid()
	{
		$project = ProjectSale::find($this->id);
		
		$totalpaid = 0;
		
		foreach($project->projectSalePay as $psp){
			$totalpaid += $psp->nominal;
		}
		
		return number_format($totalpaid,0,',','.');
	}
	
	public function getTotalDelivered()
	{
		$pd = ProjectDelivery::where('project_sale_id',$this->id)->whereNotNull('received_date')->get();
		
		$totaldelivered = 0;
		$totalmiddlemandelivered = 0;
		
		foreach($pd as $row){
			$totaldelivered += $row->getTotal()['totaldelivery'];
			$totalmiddlemandelivered += str_replace(',','.',str_replace('.','',$row->getTotalMiddleman()));
		}
		
		$arr['totaldelivered'] = $totaldelivered;
		$arr['totalmiddlemandelivered'] = $totalmiddlemandelivered;
		
		return $arr;
	}
	
	
	public function getTotalService()
	{
		$project = ProjectSale::find($this->id);
		
		$total = $project->delivery_cost + $project->cutting_cost + $project->misc_cost;
		
		return $total;
	}
	
	public function ppn_cost() 
    {
        switch($this->ppn_cost) {
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
	
	public function mid_yes_no() 
    {
        switch($this->mid_yes_no) {
            case '1':
                $mid_yes_no = 'Yes';
                break;
            case '0':
                $mid_yes_no = 'No';
                break;
            default:
                $mid_yes_no = 'Invalid';
                break;
        }

        return $mid_yes_no;
    }
	
	public function mid_type() 
    {
        switch($this->mid_type) {
            case '1':
                $mid_type = 'Percentage';
                break;
            case '2':
                $mid_type = 'Nominal';
                break;
            default:
                $mid_type = 'Invalid';
                break;
        }

        return $mid_type;
    }
	
	public function director()
    {
        return $this->belongsTo('App\Models\User', 'mid_director', 'id');
    }
	
	public function getTaxDocument(){
		$data = ProjectTaxDocument::where('lookable_type','project_sales')->where('lookable_id',$this->id)->get();
		
		$html = '';
		
		foreach($data as $key => $row){
			$html .= '<a href="' .$row->attachment() . '" class="btn btn-sm btn-info" target="_blank">File-'.($key + 1).'</a>, ';
		}
		
		return $html;
	}
	
	public function countTaxDocument(){
		$data = ProjectTaxDocument::where('lookable_type','project_sales')->where('lookable_id',$this->id)->get();
		
		$count = 0;
		
		if($data){
			$count = count($data);
		}
		
		return $count;
	}
	
	public function updateGrandtotal(){
		$subtotal_product = str_replace(',','.',str_replace('.','',$this->getTotalRaw()));
			
		if(date('Y-m-d',strtotime($this->created_at)) < '2022-04-01'){
			$persenppn = 0.1;
		}else{
			$persenppn = 0.11;
		}
		
		$tax_product = $this->project->ppn == '1' ? ($subtotal_product * $persenppn) : 0;
		
		$grandtotal_product = $subtotal_product + $tax_product;
		
		$subtotal_service = $this->delivery_cost + $this->cutting_cost + $this->misc_cost;
		
		$tax_service = $this->ppn_cost == '1' ? ($subtotal_service * $persenppn) : 0;
		
		$grandtotal_service = $subtotal_service + $tax_service;
		
		ProjectSale::find($this->id)->update([
			'subtotal_product' 		=> round($subtotal_product,2),
			'tax_product'			=> round($tax_product,2),
			'grandtotal_product'	=> round($grandtotal_product,2),
			'subtotal_service'		=> round($subtotal_service,2),
			'tax_service'			=> round($tax_service,2),
			'grandtotal_service'	=> round($grandtotal_service,2)
		]);
	}
	
	public function checkTaxDocument(){
		return $this->hasMany(ProjectTaxDocument::class, 'lookable_id', 'id')->where('lookable_type', 'project_sales')->whereNull('date');
	}
	
	public function checkAvailableTax(){
		return $this->hasMany(ProjectTaxDocument::class, 'lookable_id', 'id')->where('lookable_type', 'project_sales');
	}

	public function checkExceedReturn($product_id, $return_qty){
		$isExceed = false;
		$deliveredProduct = $this->projectDelivery->map(function($delivery) use ($product_id) {
			return $delivery->projectDeliveryProduct->where('product_id', $product_id)->sum('qty');
		})->sum();
		
		$returnProduct = $this->projectSaleReturn->map(function($return) use ($product_id) {
			return $return->projectSaleReturnProduct->where('product_id', $product_id)->sum('qty');
		})->sum();
		
		$netQtyDelivered = $deliveredProduct - $returnProduct;
		
		// jika qty yang mau diretur lebih banyak daripada barang yang terkirim/ yang sudah ter retur sebelumnya
		// barang harus sudah terkirim untuk bisa di retur
		if(!isset($deliveredProduct) || $return_qty > $netQtyDelivered){
			$isExceed = true;
		}

		return $isExceed;
	}
}
