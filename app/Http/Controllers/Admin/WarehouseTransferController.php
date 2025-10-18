<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Approval;
use App\Models\ProjectPurchase;
use App\Models\ProjectPurchaseProduct;
use App\Models\Stock;
use App\Models\Transfer;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Journal;
use App\Models\TransferProduct;
use App\Models\ProductCogs;
use App\Helper\CheckCutOff;

class WarehouseTransferController extends Controller {

    public function index(Request $request)
    {
		
        $data = [
            'title'   		=> 'Warehouse Transfer',
            'content' 		=> 'admin.inventory.warehouse_transfer'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
			'detail',
            'id',
            'code',
			'note',
            'from_warehouse_id',
            'to_warehouse_id',
			'image'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Transfer::count();
        
        $query_data = Transfer::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
						$query->whereHas('warehouseFrom', function($query) use ($search) {
							$query->where('code', 'like', "%$search%")
							->orWhere('name','like',"%$search%");
						})
						->orWhereHas('warehouseTo', function($query) use ($search){
							$query->where('code', 'like', "%$search%")
							->orWhere('name','like',"%$search%");
						})
						->orWhere('code','like',"%$search%")
						->orWhere('note','like',"%$search%");
					});
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Transfer::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
						$query->whereHas('warehouseFrom', function($query) use ($search) {
							$query->where('code', 'like', "%$search%")
							->orWhere('name','like',"%$search%");
						})
						->orWhereHas('warehouseTo', function($query) use ($search){
							$query->where('code', 'like', "%$search%")
							->orWhere('name','like',"%$search%");
						})
						->orWhere('code','like',"%$search%")
						->orWhere('note','like',"%$search%");
					});
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$proof = '';
				
				if($val->image){
					if(explode('.',$val->image)[1] == 'pdf'){
						$proof = '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$proof = '<a data-magnify="gallery" data-src="" data-caption="'.$val->item.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}
				
				$sampleStatus = '<select class="form-control status-sample" data-id="'.$val->id.'" data-old="'.$val->status.'">
					<option value="">-- Choose one if sample --</option>
					<option value="1" '.($val->status == "1" ? "selected" : "").'>Sample not returned not pay</option>
					<option value="2" '.($val->status == "2" ? "selected" : "").'>Sample not returned but pay</option>
					<option value="3" '.($val->status == "3" ? "selected" : "").'>Sample returned to supplier</option>
				</select>';
				
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $val->id,
					$val->code,
					$val->note,
					$val->warehouseFrom ? $val->warehouseFrom->name : 'None',
					$val->warehouseTo ? $val->warehouseTo->name : 'None',
					$proof,
					$sampleStatus,
					'
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    ',
					'<a onclick="openLink(`'.url('admin/inventory/transfer/print/'. base64_encode($val->id)).'`)" href="javascript:void(0);" class="btn bg-info"><i class="icon-file-pdf"></i></a>'
                ];

                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }
	
	public function create(Request $request)
    {
		if($request->for_starting){
			$validation = Validator::make($request->all(), [
				'note'					=> 'required',
				'date'					=> 'required',
				'to_warehouse_id'		=> 'required',
				'nominal'				=> 'required'
			], [
				'note.required' 				=> 'Note cannot empty.',
				'date.required'    				=> 'Date cannot empty.',
				'to_warehouse_id.required'    	=> 'Warehouse destination cannot empty.',
				'nominal.required'    			=> 'Nominal cannot empty.'
			]);
		}elseif($request->for_in_transfer){
			$validation = Validator::make($request->all(), [
				'note'					=> 'required',
				'date'					=> 'required',
				'to_warehouse_id'		=> 'required',
			], [
				'note.required' 				=> 'Note cannot empty.',
				'date.required'    				=> 'Date cannot empty.',
				'to_warehouse_id.required'    	=> 'Warehouse destination cannot empty.',
			]);
		}elseif($request->for_customer){
			$validation = Validator::make($request->all(), [
				'note'					=> 'required',
				'date'					=> 'required',
				'from_warehouse_id'		=> 'required',
				'customer_id'			=> 'required',
				'pic'					=> 'required',
				'address'				=> 'required',
				'branch'				=> 'required'
			], [
				'note.required' 				=> 'Note cannot empty.',
				'date.required'    				=> 'Date cannot empty.',
				'from_warehouse_id.required'    => 'Warehouse origin cannot empty.',
				'customer_id.required'    		=> 'Customer cannot empty.',
				'pic.required'    				=> 'PIC cannot empty.',
				'address.required'				=> 'Address cannot empty.',
				'branch.required'    			=> 'Branch cannot empty.'
			]);
		}elseif($request->for_correction || $request->for_out_transfer || $request->for_broken){
			$validation = Validator::make($request->all(), [
				'note'					=> 'required',
				'date'					=> 'required',
				'from_warehouse_id'		=> 'required',
			], [
				'note.required' 				=> 'Note cannot empty.',
				'date.required'    				=> 'Date cannot empty.',
				'from_warehouse_id.required'    => 'Warehouse origin cannot empty.',
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'note'					=> 'required',
				'date'					=> 'required',
				'from_warehouse_id'		=> 'required',
				'to_warehouse_id'		=> 'required'
			], [
				'note.required' 				=> 'Note cannot empty.',
				'date.required'    				=> 'Date cannot empty.',
				'from_warehouse_id.required'    => 'Warehouse origin cannot empty.',
				'to_warehouse_id.required'    	=> 'Warehouse destination cannot empty.'
			]);
		}
		
        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			// if(CheckCutOff::check($request->branch,substr($request->date,0,7))){
			
				if($request->temp){
					
					$tp = TransferProduct::where('transfer_id',$request->temp)->get();
					
					foreach($tp as $row){
						
						if($row->transfer->from_warehouse_id){
							$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->transfer->from_warehouse_id)->where('branch',$row->transfer->branch)->first();
						
							if($cek){
								$cek->update([
									'qty' 	=> $cek->qty + $row->qty
								]);
							}else{
								Stock::create([
									'product_id'	=> $row->product_id,
									'warehouse_id'	=> $row->transfer->from_warehouse_id,
									'qty'			=> $row->qty,
									'unit'			=> $row->unit,
									'branch'		=> $row->transfer->branch
								]);
							}
						}
						
						if($row->transfer->to_warehouse_id){
							$cek = Stock::where('product_id',$row->product_id)->where('warehouse_id',$row->transfer->to_warehouse_id)->where('branch',$row->transfer->branch)->first();
						
							if($cek){
								$cek->update([
									'qty' 	=> $cek->qty - $row->qty
								]);
							}else{
								Stock::create([
									'product_id'	=> $row->product_id,
									'warehouse_id'	=> $row->transfer->to_warehouse_id,
									'qty'			=> 0 - $row->product_qty,
									'unit'			=> $row->unit,
									'branch'		=> $row->transfer->branch
								]);
							}
						}
						
						$row->delete();
					}
					
					$cb = CashBank::where('lookable_type','transfers')->where('lookable_id',$request->temp)->get();
					
					foreach($cb as $row){
						$row->deleteFile();
						$row->deleteDetail();
						$row->delete();
					}
					
					$transfer = Transfer::find($request->temp);
					
					$transfer->update([
						'user_id' 				=> session('bo_id'),
						'code'					=> Transfer::generateCode(),
						'note'					=> $request->note,
						'date'					=> $request->date,
						'from_warehouse_id'		=> $request->from_warehouse_id,
						'to_warehouse_id'		=> $request->to_warehouse_id,
						'image'					=> $request->file('file') ? $request->file('file')->store('public/transfer') : '',
						'for_starting'			=> $request->for_starting ? '1' : NULL,
						'for_customer'			=> $request->for_customer ? '1' : NULL,
						'for_correction'		=> $request->for_correction ? '1' : NULL,
						'for_out_transfer'		=> $request->for_out_transfer ? '1' : NULL,
						'for_in_transfer'		=> $request->for_in_transfer ? '1' : NULL,
						'for_broken'			=> $request->for_broken ? '1' : NULL,
						'customer_id'			=> $request->customer_id,
						'project_purchase_id'	=> $request->project_purchase_id,
						'customer_address'		=> $request->address,
						'customer_pic'			=> $request->pic,
						'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal)),
						'branch'				=> $request->branch,
						'status'				=> NULL
					]);
					
					$app = Approval::where('approvalable_type','transfers')->where('approvalable_id',$transfer->id)->get();
					
					if($app){
						foreach($app as $row){
							$row->delete();
						}
					}
					
				}else{
					$transfer = Transfer::create([
						'user_id' 				=> session('bo_id'),
						'code'					=> Transfer::generateCode(),
						'note'					=> $request->note,
						'date'					=> $request->date,
						'from_warehouse_id'		=> $request->from_warehouse_id,
						'to_warehouse_id'		=> $request->to_warehouse_id,
						'image'					=> $request->file('file') ? $request->file('file')->store('public/transfer') : '',
						'for_starting'			=> $request->for_starting ? '1' : NULL,
						'for_customer'			=> $request->for_customer ? '1' : NULL,
						'for_correction'		=> $request->for_correction ? '1' : NULL,
						'for_out_transfer'		=> $request->for_out_transfer ? '1' : NULL,
						'for_in_transfer'		=> $request->for_in_transfer ? '1' : NULL,
						'for_broken'			=> $request->for_broken ? '1' : NULL,
						'customer_id'			=> $request->customer_id,
						'project_purchase_id'	=> $request->project_purchase_id,
						'customer_address'		=> $request->address,
						'customer_pic'			=> $request->pic,
						'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal)),
						'branch'				=> $request->branch
					]);
				}
				
				$totalcogs = 0;
				
				foreach($request->product_id as $key => $pi) {
					
					$warehouse = '';
					
					if($request->for_starting || $request->for_in_transfer){
						$tp = TransferProduct::create([
							'transfer_id' 	=> $transfer->id,
							'product_id'	=> $pi,
							'qty'			=> $request->product_qty[$key],
							'code'			=> $request->product_shading[$key],
							'unit'			=> $request->product_unit[$key],
							'price'			=> str_replace(',','.',str_replace('.','',$request->product_price[$key]))
						]);
					}else{
						$tp = TransferProduct::create([
							'transfer_id' 	=> $transfer->id,
							'product_id'	=> $pi,
							'qty'			=> $request->product_qty[$key],
							'code'			=> $request->product_shading[$key],
							'unit'			=> $request->product_unit[$key]
						]);
					}
					
					if($request->from_warehouse_id){
						$cek = Stock::where('product_id',$request->product_id[$key])->where('warehouse_id',$request->from_warehouse_id)->where('branch',$transfer->branch)->first();
					
						if($cek){
							$cek->update([
								'qty' 	=> $cek->qty - $request->product_qty[$key]
							]);
						}else{
							Stock::create([
								'product_id'	=> $request->product_id[$key],
								'warehouse_id'	=> $request->from_warehouse_id,
								'qty'			=> 0 - $request->product_qty[$key],
								'unit'			=> $request->product_unit[$key],
								'branch'		=> $transfer->branch
							]);
						}
						
						$warehouse = $request->from_warehouse_id;
					}
					
					if($request->to_warehouse_id){
						$cek = Stock::where('product_id',$request->product_id[$key])->where('warehouse_id',$request->to_warehouse_id)->where('branch',$transfer->branch)->first();
					
						if($cek){
							$cek->update([
								'qty' 	=> $cek->qty + $request->product_qty[$key]
							]);
						}else{
							Stock::create([
								'product_id'	=> $request->product_id[$key],
								'warehouse_id'	=> $request->to_warehouse_id,
								'qty'			=> $request->product_qty[$key],
								'unit'			=> $request->product_unit[$key],
								'branch'		=> $transfer->branch
							]);
						}
						
						$warehouse = $request->to_warehouse_id;
					}
					
					$price = Stock::where('product_id',$request->product_id[$key])->where('warehouse_id',$warehouse)->where('branch',$transfer->branch)->first();
					
					$totalcogs +=  $tp->price() * $request->product_qty[$key];
				}
				

				if($request->for_starting || $request->for_in_transfer){
					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'  	=> 'transfers',
						'lookable_id'		=> $transfer->id,
						'code'        		=> strtoupper(Str::random(15)),
						'date'        		=> $request->date,
						'type'        		=> '3',
						'description' 		=> 'Opening balance inventory exchange '.$transfer->code.'.',
						'image'				=> $request->file('file') ? $request->file('file')->store('public/cashbank') : ''
					]);
					
					if($cb){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 31,
							'branch'		=> $request->branch,
							'type'       	=> '1',
							'nominal'      	=> str_replace(',','.',str_replace('.','',$request->nominal)),
							'note'         	=> ''
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 31,
							'branch'		   => $request->branch,
							'type'	           => '1',
							'nominal'          => str_replace(',','.',str_replace('.','',$request->nominal)),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 122,
							'branch'		=> $request->branch,
							'type'       	=> '2',
							'nominal'      	=> str_replace(',','.',str_replace('.','',$request->nominal)),
							'note'         	=> ''
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 122,
							'branch'		   => $request->branch,
							'type'	           => '2',
							'nominal'          => str_replace(',','.',str_replace('.','',$request->nominal)),
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
				
				if($request->for_correction){
					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'  	=> 'transfers',
						'lookable_id'		=> $transfer->id,
						'code'        		=> strtoupper(Str::random(15)),
						'date'        		=> $request->date,
						'type'        		=> '3',
						'description' 		=> 'Stock correction warehouse transfer no. '.$transfer->code.'.',
						'image'				=> $request->file('file') ? $request->file('file')->store('public/cashbank') : ''
					]);
					
					if($cb){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 122,
							'branch'		=> $request->branch,
							'type'       	=> '1',
							'nominal'      	=> $totalcogs,
							'note'         	=> ''
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 122,
							'branch'		   => $request->branch,
							'type'	           => '1',
							'nominal'          => $totalcogs,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 31,
							'branch'		=> $request->branch,
							'type'       	=> '2',
							'nominal'      	=> $totalcogs,
							'note'         	=> ''
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 31,
							'branch'		   => $request->branch,
							'type'	           => '2',
							'nominal'          => $totalcogs,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
				
				if($request->for_broken){
					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'  	=> 'transfers',
						'lookable_id'		=> $transfer->id,
						'code'        		=> strtoupper(Str::random(15)),
						'date'        		=> $request->date,
						'type'        		=> '3',
						'description' 		=> 'Stock correction (broken) warehouse transfer no. '.$transfer->code.'.',
						'image'				=> $request->file('file') ? $request->file('file')->store('public/cashbank') : ''
					]);
					
					if($cb){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 40,
							'branch'		=> $request->branch,
							'type'       	=> '1',
							'nominal'      	=> $totalcogs,
							'note'         	=> 'Stock correction (broken) warehouse transfer no. '.$transfer->code.'.',
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 40,
							'branch'		   => $request->branch,
							'type'	           => '1',
							'nominal'          => $totalcogs,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 31,
							'branch'		=> $request->branch,
							'type'       	=> '2',
							'nominal'      	=> $totalcogs,
							'note'         	=> 'Stock correction (broken) warehouse transfer no. '.$transfer->code.'.',
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 31,
							'branch'		   => $request->branch,
							'type'	           => '2',
							'nominal'          => $totalcogs,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
				
				if($request->for_out_transfer){
					$cb = CashBank::create([
						'user_id'     		=> session('bo_id'),
						'lookable_type'  	=> 'transfers',
						'lookable_id'		=> $transfer->id,
						'code'        		=> strtoupper(Str::random(15)),
						'date'        		=> $request->date,
						'type'        		=> '3',
						'description' 		=> 'Stock out to another branch warehouse transfer no. '.$transfer->code.'.',
						'image'				=> $request->file('file') ? $request->file('file')->store('public/cashbank') : ''
					]);
					
					if($cb){
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> $request->branch == '1' ? 233 : 336,
							'branch'		=> $request->branch,
							'type'       	=> '1',
							'nominal'      	=> $totalcogs,
							'note'         	=> 'Stock out to another branch warehouse transfer no. '.$transfer->code.'.',
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'       	   => $request->branch == '1' ? 233 : 336,
							'branch'		   => $request->branch,
							'type'	           => '1',
							'nominal'          => $totalcogs,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
						
						CashBankDetail::create([
							'cash_bank_id' 	=> $cb->id,
							'coa_id'       	=> 31,
							'branch'		=> $request->branch,
							'type'       	=> '2',
							'nominal'      	=> $totalcogs,
							'note'         	=> 'Stock out to another branch warehouse transfer no. '.$transfer->code.'.',
						]);
						
						Journal::insert([
							'date_transaction' => $request->date,
							'journalable_type' => 'cash_banks',
							'journalable_id'   => $cb->id,
							'coa_id'           => 31,
							'branch'		   => $request->branch,
							'type'	           => '2',
							'nominal'          => $totalcogs,
							'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
							'updated_at'       => date('Y-m-d H:i:s')
						]);
					}
				}
				
				$roleapproval = 7;
				Approval::sendApproval($roleapproval,'transfers',$transfer->id,'approved_by',session('bo_id'));
				
				ProductCogs::updateCogs($request->date,$request->branch);
				
				activity()
						->performedOn(new Transfer())
						->causedBy(session('bo_id'))
						->withProperties($transfer)
						->log('Add / edit warehouse exchange data');
				
				$response = [
					'status'  => 200,
					'message' => 'Data added successfully.'
				];
			// }else{
			// 	$response = [
			// 		'status'  => 503,
			// 		'message' => 'You cannot add/edit. The journal for this month was already closed.'
			// 	];
			// }
        }

        return response()->json($response);
    }
	
	public function getProduct(Request $request)
    {
        $data  = Product::find($request->id);
        $image = '<a href="' . $data->type->image() . '" data-lightbox="' . $data->name() . '" data-title="' . $data->name() . '"><img src="' . $data->type->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>';
		$stok = Stock::where('product_id',$data->id)->where('warehouse_id',$request->warehouse)->where('branch',$request->branch)->first();

		return response()->json([
			'id'      		=> $data->id,
			'product' 		=> $image . '<div><a href="javascript:void(0);" onclick="getShading('.$data->id.')">' . $data->name() . '</a></div><div>' . $data->type->length . 'x' . $data->type->width . '</div>',
			'stock'			=> $stok ? $stok->qty : 0,
			'unit'			=> $stok ? $stok->unit : $data->type->selling_unit_id
		]);
    }
	
	public function rowDetail(Request $request)
    {
        $data   = TransferProduct::where('transfer_id',$request->id)->get();
		
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.</th>
							<th>Product</th>
							<th>Shading</th>
							<th>Qty</th>
							<th>Unit</th>
							<th>Price</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>';
		$no = 1;
		$total = 0;
		foreach($data as $val){
			$total += $val->price() * $val->qty;
			$string .= '
				<tr>
					<td>'.$no.'</td>
					<td>'.$val->product->name().'</td>
					<td>'.$val->code.'</td>
					<td class="text-center">'.$val->qty.'</td>
					<td class="text-center">'.$val->unit().'</td>
					<td class="text-right">Rp '.number_format($val->price(),2,',','.').'</td>
					<td class="text-right">Rp '.number_format($val->price() * $val->qty,2,',','.').'</td>
				</tr>
			';
			$no++;
		}

        $string .= '
				</tbody>
				<tfoot>
					<th colspan="6" class="text-right">Total</th>
					<th class="text-right">'.number_format($total,2,',','.').'</th>
				</tfoot>
			</table>
		';
		
        return response()->json($string);
    }
	
	public function destroy(Request $request) 
    {
        $query = Transfer::find($request->id);
		
		foreach($query->transferProduct as $rowtransfer){
			
			if($query->from_warehouse_id){
				$cek = Stock::where('product_id',$rowtransfer->product_id)->where('warehouse_id',$query->from_warehouse_id)->where('branch',$query->branch)->first();
			
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty + $rowtransfer->qty
					]);
				}
			}
			
			if($query->to_warehouse_id){
				$cek = Stock::where('product_id',$rowtransfer->product_id)->where('warehouse_id',$query->to_warehouse_id)->where('branch',$query->branch)->first();
			
				if($cek){
					$cek->update([
						'qty' 	=> $cek->qty - $rowtransfer->qty
					]);
				}
			}
			
			$rowtransfer->delete();
		}
		
		$cb = CashBank::where('lookable_id',$request->id)->where('lookable_type','transfers')->get();
			
		foreach($cb as $row){
			$row->deleteFile();
			$row->deleteDetail();
			$row->delete();
		}
		
		$query->deleteFile();
		$query->delete();
		
        if($query) {
            activity()
                ->performedOn(new Transfer())
				->withProperties($query)
                ->causedBy(session('bo_id'))
                ->log('Delete the Purchase request data');

            $response = [
                'status'  => 200,
                'message' => 'Data deleted successfully.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data failed to delete.'
            ];
        }

        return response()->json($response);
    }
	
	public function print($id)
    {
		
		$transfer_id = base64_decode($id);
		$transfer    = Transfer::find($transfer_id);

		if(!$transfer) {
			abort(404);
		}
		
		$pdf = PDF::loadView('admin.pdf.inventory.transfer', [
				'data' => $transfer
			],
			[],
			[ 
			  'format' => 'A4-P',
			  'orientation' => 'P'
			]
		);

        return $pdf->stream('TJS Document ' . str_replace('/', '-', $transfer->code) . '.pdf');
    }
	
	public function show(Request $request)
    {
        $data = Transfer::find($request->id);
		
		if($data->project_purchase_id){
			$purchase = ProjectPurchase::find($data->project_purchase_id);
		}
        
		$detail = [];

        foreach($data->transferProduct as $tp) {
			$image = '<a href="' . $tp->product->type->image() . '" data-lightbox="' . $tp->product->name() . '" data-title="' . $tp->product->name() . '"><img src="' . $tp->product->type->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>';
			
			if($data->for_starting || $data->for_in_transfer){
				$detail[] = [
					'product_id'    => $tp->product_id,
					'qty'  			=> $tp->qty,
					'code'			=> $tp->code,
					'unit'			=> $tp->unit,
					'price'			=> $tp->price,
					'product_name' 	=> $image.'<div>'.$tp->product->name().'</div>'
				];
			}else{
				$detail[] = [
					'product_id'    => $tp->product_id,
					'qty'  			=> $tp->qty,
					'code'			=> $tp->code,
					'unit'			=> $tp->unit,
					'product_name' 	=> $image.'<div>'.$tp->product->name().'</div>'
				];
			}
            
        }
		
		$data['from_warehouse_name'] = $data->warehouseFrom ? $data->warehouseFrom->name : '';
		$data['to_warehouse_name'] = $data->warehouseTo ? $data->warehouseTo->name : '';

        return response()->json([
			'data'					=> $data,
			'detail'				=> $detail,
			'purchase'				=> isset($purchase) ? $purchase : '0'
        ]);
    }
	
	public function changeStatusSample(Request $request)
    {
        $data = Transfer::find($request->id);
		
		$ppnmasukan = 0;
		
		if($data->project_purchase_id){
			$purchase = ProjectPurchase::find($data->project_purchase_id);
			$totalPurchase = str_replace(',','.',str_replace('.','',$purchase->getTotal()));
			if($purchase->ppn == '1'){
				$inventory = round($totalPurchase/ 1.1,2);
				$ppnmasukan = 50;
				$ppnnominal = round($totalPurchase - ($totalPurchase / 1.1),2);
			}else{
				$inventory = $totalPurchase;
			}
			$hutang = $totalPurchase;
		}else{
			
			$jumlah = 0;
			$totalpurchase = 0;
			
			foreach($data->transferProduct as $tp){
				$jumlah = 0;
				$totalpo = 0;
				if($tp->unit == '2' || $tp->unit == '3'){
					foreach(ProjectPurchaseProduct::where('product_id',$tp->product_id)->get() as $ppp){
						$m2 = (( $ppp->product->type->length * $ppp->product->type->width ) / 10000) * $ppp->product->carton_pcs;
						
						if($ppp->projectPurchase->ppn == '1'){
							$totalpo += ($ppp->qty * $m2 * $ppp->price) / 1.1;
						}else{
							$totalpo += $ppp->qty * $m2 * $ppp->price;
						}
						$jumlah += $ppp->qty;
					}
					$pricepurchase = round($totalpo / $jumlah,2);
					$totalpurchase += $pricepurchase * $tp->qty;
				}
				
				if($tp->unit == '1' || $tp->unit == '4'){
					foreach(ProjectPurchaseProduct::where('product_id',$tp->product_id)->get() as $ppp){
						if($ppp->projectPurchase->ppn == '1'){
							$totalpo += ($ppp->qty * $ppp->price) / 1.1;
						}else{
							$totalpo += $ppp->qty * $ppp->price;
						}
						$jumlah += $ppp->qty;
					}
					$pricepurchase = round($totalpo / $jumlah,2);
					$totalpurchase += $pricepurchase * $tp->qty;
				}
			}
			$inventory = $totalpurchase;
			$hutang = $totalpurchase;
		}
		
		if($request->val == '1'){
			$cb = CashBank::create([
				'user_id'     		=> session('bo_id'),
				'lookable_type'  	=> 'transfers',
				'lookable_id'		=> $data->id,
				'code'        		=> strtoupper(Str::random(15)),
				'date'        		=> date('Y-m-d'),
				'type'        		=> '3',
				'description' 		=> 'Transfer product to customer '.$data->customer->name.' no return without payment in Warehouse Exchange Number '.$data->code.'.'
			]);
			
			if($cb){
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 341,
					'branch'		=> $data->branch,
					'type'       	=> '1',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 341,
					'branch'		   => $data->branch,
					'type'	           => '1',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 31,
					'branch'		=> $data->branch,
					'type'       	=> '2',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 31,
					'branch'		   => $data->branch,
					'type'	           => '2',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 332,
					'branch'		=> $data->branch,
					'type'       	=> '1',
					'nominal'      	=> $hutang,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 332,
					'branch'		   => $data->branch,
					'type'	           => '1',
					'nominal'          => $hutang,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 341,
					'branch'		=> $data->branch,
					'type'       	=> '2',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 341,
					'branch'		   => $data->branch,
					'type'	           => '2',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				if($ppnmasukan){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $ppnmasukan,
						'branch'		=> $data->branch,
						'type'       	=> '2',
						'nominal'      	=> $ppnnominal,
						'note'         	=> ''
					]);
					
					Journal::insert([
						'date_transaction' => date('Y-m-d'),
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $ppnmasukan,
						'branch'		   => $data->branch,
						'type'	           => '2',
						'nominal'          => $ppnnominal,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
			}
		}elseif($request->val == '2'){
			$cb = CashBank::create([
				'user_id'     		=> session('bo_id'),
				'lookable_type'  	=> 'transfers',
				'lookable_id'		=> $data->id,
				'code'        		=> strtoupper(Str::random(15)),
				'date'        		=> date('Y-m-d'),
				'type'        		=> '3',
				'description' 		=> 'Transfer product to customer '.$data->customer->name.' no return without payment in Warehouse Exchange Number '.$data->code.'.'
			]);
			
			if($cb){
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 341,
					'branch'		=> $data->branch,
					'type'       	=> '1',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 341,
					'branch'		   => $data->branch,
					'type'	           => '1',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 31,
					'branch'		=> $data->branch,
					'type'       	=> '2',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 31,
					'branch'		   => $data->branch,
					'type'	           => '2',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
			}
		}elseif($request->val == '3'){
			
			$cb = CashBank::create([
				'user_id'     		=> session('bo_id'),
				'lookable_type'  	=> 'transfers',
				'lookable_id'		=> $data->id,
				'code'        		=> strtoupper(Str::random(15)),
				'date'        		=> date('Y-m-d'),
				'type'        		=> '3',
				'description' 		=> 'Transfer product to customer '.$data->customer->name.' no return without payment in Warehouse Exchange Number '.$data->code.'.'
			]);
			
			if($cb){
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 341,
					'branch'		=> $data->branch,
					'type'       	=> '1',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 341,
					'branch'		   => $data->branch,
					'type'	           => '1',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 31,
					'branch'		=> $data->branch,
					'type'       	=> '2',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 31,
					'branch'		   => $data->branch,
					'type'	           => '2',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				#2
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 31,
					'branch'		=> $data->branch,
					'type'       	=> '1',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 31,
					'branch'		   => $data->branch,
					'type'	           => '1',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 341,
					'branch'		=> $data->branch,
					'type'       	=> '2',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 341,
					'branch'		   => $data->branch,
					'type'	           => '2',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				#3
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 332,
					'branch'		=> $data->branch,
					'type'       	=> '1',
					'nominal'      	=> $hutang,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 332,
					'branch'		   => $data->branch,
					'type'	           => '1',
					'nominal'          => $hutang,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				CashBankDetail::create([
					'cash_bank_id' 	=> $cb->id,
					'coa_id'       	=> 31,
					'branch'		=> $data->branch,
					'type'       	=> '2',
					'nominal'      	=> $inventory,
					'note'         	=> ''
				]);
				
				Journal::insert([
					'date_transaction' => date('Y-m-d'),
					'journalable_type' => 'cash_banks',
					'journalable_id'   => $cb->id,
					'coa_id'           => 31,
					'branch'		   => $data->branch,
					'type'	           => '2',
					'nominal'          => $inventory,
					'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				]);
				
				if($ppnmasukan){
					CashBankDetail::create([
						'cash_bank_id' 	=> $cb->id,
						'coa_id'       	=> $ppnmasukan,
						'branch'		=> $data->branch,
						'type'       	=> '2',
						'nominal'      	=> $ppnnominal,
						'note'         	=> ''
					]);
					
					Journal::insert([
						'date_transaction' => date('Y-m-d'),
						'journalable_type' => 'cash_banks',
						'journalable_id'   => $cb->id,
						'coa_id'           => $ppnmasukan,
						'branch'		   => $data->branch,
						'type'	           => '2',
						'nominal'          => $ppnnominal,
						'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
						'updated_at'       => date('Y-m-d H:i:s')
					]);
				}
			}
		}
		
		$data->update([
			'status'	=> $request->val
		]);

        return response()->json([
			'status'	=> 200
        ]);
    }
	
	public function getPurchaseProduct(Request $request)
    {
		$data = ProjectPurchase::find($request->id);
		
		$result = [];
		
		foreach($data->projectWarehouse as $row){
			foreach($row->projectWarehouseProduct as $pw){
				$image = '<a href="' . $pw->product->type->image() . '" data-lightbox="' . $pw->product->name() . '" data-title="' . $pw->product->name() . '"><img src="' . $pw->product->type->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>';
				
				$result[] = [
					'id'		=> $pw->product_id,
					'product'	=> $image.'<br>'.$pw->product->name(),
					'qty'		=> $pw->qty
				];
			}
		}
		
		if(count($result) > 0){
			return response()->json([
				'status'	=> 200,
				'products'	=> $result
			]);
		}else{
			return response()->json([
				'status'	=> 422,
				'message'	=> 'Ups! There is no warehouse receive in this PO.'
			]);
		}
	}
}