<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProjectSaleTemp extends Model {

    use HasFactory;

    protected $table      = 'project_sales_temps';
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
		'reason',
		'approved_by',
		'status',
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
	
	public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }
	
	public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_id', 'id');
    }
	
	public function approved_by()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }
	
	public function status() 
    {
        switch($this->status) {
            case '1':
                $status = 'Approved';
                break;
            case Null:
                $status = 'Waiting';
                break;
            default:
                $status = 'Invalid';
                break;
        }

        return $status;
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
	
	public function projectSaleProductTemp()
    {
        return $this->hasMany('App\Models\ProjectSaleProductTemp','project_sale_id','id');
    }
	
	public function projectSaleShadingTemp()
    {
        return $this->hasMany('App\Models\ProjectSaleShadingTemp','project_sale_id','id');
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
	
	public function projectDelivery(){
		return $this->hasMany('App\Models\ProjectDelivery');
	}
	
	public function getTotalRawPlusService(){
		$project = ProjectSaleTemp::find($this->id);
		
		$total = 0;
		
		foreach($project->projectSaleProductTemp as $key => $pp){
			if($pp->unit == '2' || $pp->unit == '3'){
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				
				if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
					$countbox = $pp->qty;
					$total += $pp->best_price * $countbox;
				}else{
					if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
						$countbox = $pp->qty;
						$total += $pp->best_price * $countbox;
					}else{
						$countbox = ceil(round($pp->qty / $m2,2));
						$total += $pp->best_price * $m2 * $countbox;
					}
				}
				
			}
			
			if($pp->unit == '1' || $pp->unit == '4'){
				$total += $pp->best_price * $pp->qty;
			}
		}
		
		if($project->project->discount){
			$total -= $project->project->discount;
		}
		
		$total += ($project->delivery_cost + $project->cutting_cost + $project->misc_cost);
		
		return number_format($total,0,',','.');
	}
	
	public function getTotalRaw(){
		$project = ProjectSale::find($this->id);
		
		$total = 0;
		
		foreach($project->projectSaleProduct as $key => $pp){
			if($pp->unit == '2' || $pp->unit == '3'){
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				
				if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
					$countbox = $pp->qty;
					$total += $pp->best_price * $countbox;
				}else{
					if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
						$countbox = $pp->qty;
						$total += $pp->best_price * $countbox;
					}else{
						$countbox = ceil(round($pp->qty / $m2,2));
						$total += $pp->best_price * $m2 * $countbox;
					}
					
				}
				
			}
			
			if($pp->unit == '1' || $pp->unit == '4'){
				$total += $pp->best_price * $pp->qty;
			}
		}
		
		return number_format($total - $project->project->discount,0,',','.');
	}
	
	public function getTotalMiddleman(){
		$project = ProjectSaleTemp::find($this->id);
		
		$total = 0;
		
		if($this->mid_type == '1'){
			$total = ($this->mid_fee * str_replace(',','.',str_replace('.','',$project->getTotalRaw()))) / 100;
		}elseif($this->mid_type == '2'){
			foreach($project->projectSaleProductTemp as $key => $pp){
				if($pp->unit == '2' || $pp->unit == '3'){
					$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
					if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
						$countbox = $pp->qty;
						$total += $this->mid_fee * $countbox;
					}else{
						if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
							$countbox = $pp->qty;
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
			
			$total -= $project->project->discount;
		}
		
		return number_format($total,0,',','.');
	}
	
	public function getTotal()
	{
		$project = ProjectSaleTemp::find($this->id);
		
		if(date('Y-m-d',strtotime($project->created_at)) < '2022-04-01'){
			$persenppn = 0.1;
			$ppnpembagi = 1.1;
		}else{
			$persenppn = 0.11;
			$ppnpembagi = 1.11;
		}
		
		$total = 0;
		$cost = 0;
		
		foreach($project->projectSaleProductTemp as $key => $pp){
			if($pp->unit == '2' || $pp->unit == '3'){
				$m2 = (( $pp->product->type->length * $pp->product->type->width ) / 10000) * $pp->product->carton_pcs;
				
				if($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18){
					$countbox = $pp->qty;
					$total += $pp->best_price * $countbox;
				}else{
					if($m2 < 1.1 && date('Y-m',strtotime($project->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18){
						$countbox = $pp->qty;
						$total += $pp->best_price * $countbox;
					}else{
						$countbox = ceil(round($pp->qty / $m2,2));
						$total += $pp->best_price * $m2 * $countbox;
					}
					
				}
				
			}
			
			if($pp->unit == '1' || $pp->unit == '4'){
				$total += $pp->best_price * $pp->qty;
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
		$project = ProjectSaleTemp::find($this->id);
		
		$totalpaid = 0;
		
		foreach($project->projectSalePayTemp as $psp){
			$totalpaid += $psp->nominal;
		}
		
		return number_format($totalpaid,0,',','.');
	}
	
	public function getTotalDelivered()
	{
		$pd = ProjectDelivery::where('project_sale_id',$this->id)->get();
		
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
		$project = ProjectSaleTemp::find($this->id);
		
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
}
