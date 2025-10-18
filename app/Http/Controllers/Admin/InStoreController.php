<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coa;
use App\Models\City;
use App\Models\User;
use App\Models\Project;
use App\Models\Country;
use App\Models\Approval;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Notification;
use App\Models\ProjectProduct;
use App\Models\ProjectSale;
use App\Models\ProjectSaleProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Helper\SendMessage;

class InStoreController extends Controller {

    public function index(Request $request)
    {
	
        $data = [
			'bank' 			=> Coa::where('id', 8)->where('status', 1)->get(),
			'country' 		=> Country::where('status', 1)->get(),
			'city'    		=> City::all(),
            'title'   		=> 'Retail Store Transaction',
            'content' 		=> 'admin.sales.in_store'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function create(Request $request){
		
		if($request->customer_id){
			$customer = Customer::find($request->customer_id);
		}elseif($request->name){
			$customer = Customer::create([
                'photo'  		=> null,
                'name'   		=> $request->name,
                'constructor'   => $request->name,
                'email'  		=> $request->email ? $request->email : Str::random(15).'@smb.com',
                'phone'  		=> $request->phone,
                'password' 		=> Hash::make('123'),
                'type'   		=> '2',
				'address'		=> $request->address,
				'npwp'			=> null,
				'address_npwp' 	=> null,
				'finance_name' 	=> null,
				'finance_hp' 	=> null,
                'points' 		=> 0
            ]);
		}
		
		$query = Project::create([
			'user_id'        => session('bo_id'),
			'country_id'     => $request->country_id,
			'city_id'        => $request->city_id,
			'city_franco_id' => $request->city_id,
			'code'           => $request->type == '1' ? Project::generateCodeInStore() : Project::generateCode(),
			'name'           => 'IN STORE SALES BY MR/MRS '.$customer->name,
			'customer_id'    => $request->customer_id ? $request->customer_id : $customer->id,
			'timeline'       => date('Y-m-d'),
			'manager'        => $customer->name,
			'consultant'     => $customer->name,
			'owner'          => $customer->name,
			'coa_id' 		 => $request->bank_id,
			'payment_method' => $request->detail_payment,
			'term_payment'   => $request->term_payment,
			'supply_method'  => '1',
			'ppn'            => $request->isppn,
			'progress'       => 37,
			'discount'       => str_replace(',','.',str_replace('.','.',$request->discount)),
			'in_store'		 => '0'
			// 'in_store'		 => $request->type,
		]);
		
		ProjectProduct::where('project_id',$query->id)->whereNotIn('product_id',$request->product_id)->delete();
						
		$salesinfo = User::find($request->user_id);
		
		foreach($request->product_id as $key => $pi) {
			$product = Product::find($pi);
			$cogs    = 0;
			
			if($product->pricingPolicy) {
				if($salesinfo->branch == '1'){
					$cogs = isset($product->cogs) ? $product->cogs->formula()->cogs_pta_idr : 0;
				}elseif($salesinfo->branch == '2'){
					$cogs = isset($product->cogs) ? $product->cogs->formula()->cogs_smb_idr : 0;
				}
			}
			
			$count = ProjectProduct::where('project_id',$query->id)->where('product_id',$pi)->count();
			
			if($count > 0){
				ProjectProduct::where(['project_id' => $query->id, 'product_id' => $pi])->update([
					'area'         => $request->product_area[$key],
					'spec'         => $request->product_spec[$key],
					'qty'          => str_replace(',','.',str_replace('.','.',$request->product_qty[$key])),
					'cogs'         => $cogs,
					'price'        => str_replace(',','.',str_replace('.','',$request->product_price_new[$key])),
					'best_price'   => str_replace(',','.',str_replace('.','',$request->product_price_new[$key])),
					'unit'         => $request->product_unit[$key]
				]);
			}else{
				ProjectProduct::create([
					'project_id'   => $query->id,
					'product_id'   => $pi,
					'area'   	   => $request->product_area[$key],
					'spec'   	   => $request->product_spec[$key],
					'qty'          => str_replace(',','.',str_replace('.','.',$request->product_qty[$key])),
					'cogs'         => $cogs,
					'price'        => str_replace(',','.',str_replace('.','',$request->product_price_new[$key])),
					'best_price'   => str_replace(',','.',str_replace('.','',$request->product_price_new[$key])),
					'unit'         => $request->product_unit[$key]
				]);
			}
			
		}
		
		$projectSale = ProjectSale::create([
			'user_id'		=> session('bo_id'),
			'project_id' 	=> $query->id,
			'sales_id'		=> $request->user_id,
			'code' 		 	=> ProjectSale::generateCode(),
			'address'		=> $request->address,
			'note'			=> $request->note,
			'so_file'		=> $request->file('file') ? $request->file('file')->store('public/project') : '',
			'marketing_id' 	=> 0,
			'approved_id' 	=> 7,
			'delivery_cost'	=> $request->delivery_cost ? str_replace(',','.',str_replace('.','',$request->delivery_cost)) : 0,
			'cutting_cost'	=> $request->cutting_cost ? str_replace(',','.',str_replace('.','',$request->cutting_cost)) : 0,
			'misc_cost'		=> $request->misc_cost ? str_replace(',','.',str_replace('.','',$request->misc_cost)) : 0,
			'ppn_cost'		=> '0',
			'mid_yes_no'	=> $request->mid_yes_no,
			'mid_type'		=> $request->mid_type,
			'mid_fee'		=> str_replace(',','.',str_replace('.','',$request->mid_fee)),
			'mid_director'	=> 0,
			'mid_note'		=> $request->mid_note,
			'currency_id'	=> 5,
			'currency_rate'	=> 1,
			'created_at'	=> date('Y-m-d H:i:s')
		]);
		
		$dataProduct = ProjectProduct::where('project_id', $query->id)->get();
						
		foreach($dataProduct as $ps) {
			ProjectSaleProduct::create([
				'project_sale_id'   => $projectSale->id,
				'product_id'   		=> $ps->product_id,
				'area'   	   		=> $ps->area,
				'spec'   	   		=> $ps->spec,
				'qty'          		=> $ps->qty,
				'cogs'        		=> $ps->cogs,
				'price'        		=> $ps->price,
				'recommended_price'	=> $ps->price,
				'best_price'		=> $ps->price,
				'discount'			=> $ps->discount,
				'unit'         		=> $ps->unit
			]);
		}
		
		ProjectSale::find($projectSale->id)->updateGrandtotal();
		
		if($query) {
			
			#send approval
			// $roleapproval = array('5');
			// Approval::sendApproval($roleapproval,'project_sales',$projectSale->id,'marketing_id',session('bo_id'));
			// $roleapproval = array('4');
			// Approval::sendApproval($roleapproval,'project_sales',$projectSale->id,'approved_id',session('bo_id'));
			
			// SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Sales No. '.$projectSale->code.' Project No. '.$projectSale->project->code.'. Terima kasih.');
			// SendMessage::send(env('SALES_MANAGER_PHONE'),'Halo pak/bu. Mohon dibantu approve Project Sales No. '.$projectSale->code.' Project No. '.$projectSale->project->code.'. Terima kasih.');
		
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'New project has been created from In Store!';
			$description = 'New project '.$query->code.' has been created by '.session('bo_name');
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			if($request->mid_yes_no == '1'){
				$roleapproval = array('1');
				Approval::sendApproval($roleapproval,'project_sales',$projectSale->id,'mid_director',session('bo_id'));
			}

			activity()
				->performedOn(new Project())
				->causedBy(session('bo_id'))
				->withProperties($query)
				->log('Add project data');

			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Data failed to add.'
			];
		}

        return response()->json($response);
		
	}
}
