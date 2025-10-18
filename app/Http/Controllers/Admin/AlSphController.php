<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlSph;
use App\Models\AlSphProduct;
use App\Models\AlProduct;
use App\Models\AlCustomer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AlSphController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    => 'SPH & DKH',
			'customer' => AlCustomer::all(),
            'content'  => 'admin.al.sph_dkh'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
            'code',
            'name',
            'date',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlProject::count();
        
        $query_data = AlProject::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhereHas('alCustomer', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlProject::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhereHas('alCustomer', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$notif = '';
				
				if(count($val->alSph) == 0){
					$notif = 'blink-notification';
				}
				
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->name,
					$val->field_of_work,
					$val->location,
					$val->alCustomer->name,
                    date('d M Y',strtotime($val->date)),
					$val->is_ppn(),
					'<a href="'.url("admin/al/sph_dkh/edit_sph/".$val->id).'" class="btn bg-info btn-sm '.$notif.'" data-popup="tooltip" title="Add/Edit SPH & DKH"><i class="icon-zoomin3"></i></a>',
                    '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Hapus" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    '
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
	
	public function create(Request $request){
		$validation = Validator::make($request->all(), [
            'name' 				=> 'required',
			'al_customer_id'	=> 'required',
			'date'				=> 'required',
			'city_id'			=> 'required',
			'field_of_work'		=> 'required',
			'location'			=> 'required'
        ], [
            'name.required'				=> 'Name cannot be empty.',
			'al_customer_id.required'	=> 'Customer cannot be empty.',
			'date.required'				=> 'Date cannot be empty.',
			'city_id.required'			=> 'City cannot be empty.',
			'field_of_work.required'	=> 'Field of work cannot be empty.',
			'location'					=> 'Project Location cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = AlProject::find($request->temp);
				
				$query->update([
					'user_id'			=> session('bo_id'),
					'al_customer_id'	=> $request->al_customer_id,
					'city_id'			=> $request->city_id,
					'name'            	=> $request->name,
					'date'				=> $request->date,
					'is_ppn'			=> $request->ppn,
					'note'				=> $request->note,
					'field_of_work'		=> $request->field_of_work,
					'location'			=> $request->location
				]);
			}else{
				$query = AlProject::create([
					'user_id'			=> session('bo_id'),
					'code'				=> AlProject::generateCode($request->al_customer_id),
					'al_customer_id'	=> $request->al_customer_id,
					'city_id'			=> $request->city_id,
					'name'            	=> $request->name,
					'date'				=> $request->date,
					'is_ppn'			=> $request->ppn,
					'note'				=> $request->note,
					'progress'			=> 0,
					'field_of_work'		=> $request->field_of_work,
					'location'			=> $request->location
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new AlProject())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add Project AL data');

                $response = [
                    'status'  	=> 200,
                    'message' 	=> 'Data added successfully.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data failed to add.'
                ];
            }
        }

        return response()->json($response);
	}
	
	public function show(Request $request){
		$data = AlProject::find($request->id);
		
		$data['city_name'] = $data->city->name;
		
		return response()->json([
			'data'		=> $data
		]);
	}
	
	public function destroy(Request $request){
		$project = AlProject::find($request->id);
		
		if($project){
			
			$project->alDocumentProject()->delete();
			$project->alApplicationLetter()->delete();
			
			foreach($project->alSph as $row){
				if($row->delete()){
					$row->alSphProduct()->delete();
				}
			}
			
			foreach($project->alPurchase as $row){
				if($row->delete()){
					$row->alPurchaseProduct()->delete();
				}
			}
			
			foreach($project->alChecklistProject as $row){
				$row->delete();
			}
			
			foreach($project->alIncome as $row){
				$row->deleteFile();
				$row->delete();
			}
			
			foreach($project->alExpense as $row){
				if($row->delete()){
					$row->deleteFile();
					foreach($row->alExpensePay as $rowexpense){
						$rowexpense->deleteFile();
						$rowexpense->delete();
					}
				}
			}
			
			foreach($project->alBudgeting as $row){
				if($row->delete()){
					foreach($row->alBudgeting as $rowbudget){
						$rowbudget->alBudgetingExpense()->delete();
					}
				}
			}
			
			foreach($project->alInvoice as $row){
				$row->delete();
			}
		}
		
		if($project->delete()){
			$response = [
                'status' 	=> 200,
                'message'  	=> 'Data deleted successfully.'
            ];
		}else{
			$response = [
                'status' 	=> 422,
                'message'  	=> 'Ups Error.'
            ];
		}
		
		return response()->json($response);
	}
	
	public function editSph(Request $request, $id){
		$project = AlProject::find($id);
		
		$data = [
            'title'    	=> 'Add/Edit SPH & DKH',
			'proyek'	=> $project,
			'barang' 	=> AlProduct::all(),
            'content'  	=> 'admin.al.edit_sph'
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function showSph(Request $request){
		$data = AlSph::find($request->id);
		
		$parent = $data->alProductParent();
		
		$html = '<div class="table-responsive"><table class="table table-bordered" style="width:1500px !important;">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Produk</th>
							<th>Spesifikasi</th>
							<th>Qty</th>
							<th>Unit</th>
							<th>H.Jual/Unit</th>
							<th>Total</th>
							<th>H.Beli/Unit</th>
							<th data-popup="tooltip" title="Dari harga beli di master data.">HPP</th>
							<th>DK '.$data->dk.'%</th>
							<th>Admin '.$data->admin.'%</th>
							<th>Profit (Rp)</th>
							<th>Profit (%)</th>
							<th>Supplier</th>
						</tr>
					</thead>
					<tbody>';
		
		$noparent = 'A';
		$nograndparent = 1;
		$totalhpp = 0;
		$totaldk = 0;
		$totaladmin = 0;
		$totalprofit = 0;
		
		foreach($parent as $rowparent){
			
			foreach($data->alSphProduct()->whereHas('alProduct', function($query) use ($rowparent){$query->where('al_product_parent_id',$rowparent['id'])->groupBy('al_category_id'); })->get() as $key => $row){
				if($key == 0){
					$html .= '	<tr>
					<td style="vertical-align:center;" colspan="14">
						<b>'. $nograndparent.'. '.$row->alProduct->alCategory->name .'</b>
					</td>
				</tr>';
				}

				if($key == 0){
					$html .= '<tr>
						<td style="vertical-align:center;" colspan="14">
							<b>'.($rowparent["name"] ? $noparent.". ".$rowparent["name"] : $noparent.". "."Lain-lain").'</b>
						</td>
					</tr>';
				}
				
				$hpp = $row->buy_price * $row->qty;
				$dk = $row->total * $data->dk / 100;
				$admin = $row->total * $data->admin / 100;
				$profit = $row->total - $hpp - $dk - $admin;
				$totalhpp += $hpp;
				$totaldk += $dk;
				$totaladmin += $admin;
				$totalprofit += $profit;
				$html .= '
					<tr>
						<td class="text-center">'.$row->alProduct->name.'</td>
						<td class="text-center">'.$row->alProduct->description.'</td>
						<td class="text-center">'.$row->qty.'</td>
						<td class="text-center">'.$row->alProduct->unit().'</td>
						<td class="text-right">Rp '.number_format($row->sell_price,0,",",".").'</td>
						<td class="text-right">Rp '.number_format($row->total,0,",",".").'</td>
						<td class="text-right">Rp '.number_format($row->buy_price,0,",",".").'</td>
						<td class="text-right">Rp '.number_format($hpp,0,",",".").'</td>
						<td class="text-right">Rp '.number_format($dk,0,",",".").'</td>
						<td class="text-right">Rp '.number_format($admin,0,",",".").'</td>
						<td class="text-right">Rp '.number_format($profit,0,",",".").'</td>
						<td class="text-right">'.($row->total > 0 ? number_format($profit / $row->total * 100,2,",",".") : 0).'%</td>
						<td class="text-center">'.$row->alProduct->alSupplier->name.'</td>
					</tr>
				';
			}
			
			$noparent++;
			$nograndparent++;
		}

        $html .= '<tr class="font-weight-bold">
						<td colspan="5" class="text-right">Total</td>
						<td class="text-right">Rp '.number_format($data->total,0,",",".").'</td>
						<td style="vertical-align:center;" align="right">
							
						</td>
						<td style="vertical-align:center;" align="right">
							'.number_format($totalhpp,0,",",".").'
						</td>
						<td style="vertical-align:center;" align="right">
							'.number_format($totaldk,0,",",".").'
						</td>
						<td style="vertical-align:center;" align="right">
							'.number_format($totaladmin,0,",",".").'
						</td>
						<td style="vertical-align:center;" align="right">
							'.number_format($totalprofit,0,",",".").'
						</td>
						<td colspan="2"></td>
					</tr>
					<tr class="font-weight-bold">
						<td colspan="5" class="text-right">PPN</td>
						<td class="text-right">Rp '.number_format($data->ppn,0,",",".").'</td>
						<td style="vertical-align:center;" align="right">
							Prosentase
						</td>
						<td style="vertical-align:center;" align="right">
							'.number_format($totalhpp / $data->total * 100,2,",",".").'%
						</td>
						<td style="vertical-align:center;" align="right">
							'.number_format($totaldk / $data->total * 100,2,",",".").'%
						</td>
						<td style="vertical-align:center;" align="right">
							'.number_format($totaladmin / $data->total * 100,2,",",".").'%
						</td>
						<td style="vertical-align:center;" align="right">
							'.number_format($totalprofit / $data->total * 100,2,",",".").'%
						</td>
						<td colspan="2"></td>
					</tr>
					<tr class="font-weight-bold">
						<td colspan="5" class="text-right">PPH</td>
						<td class="text-right">Rp '.number_format($data->pph,0,",",".").'</td>
						<td colspan="7"></td>
					</tr>
					<tr class="font-weight-bold">
						<td colspan="5" class="text-right">Grandtotal</td>
						<td class="text-right">Rp '.number_format($data->grandtotal,0,",",".").'</td>
						<td colspan="7"></td>
					</tr>
		</tbody></table></div>';
		
		return response()->json([
			'html'	=> $html
		]);
	}
	
	public function createSph(Request $request){
		$validation = Validator::make($request->all(), [
            'date' 				=> 'required',
			'kode'				=> 'required',
			'al_product_id'		=> 'required|array',
			'source'			=> 'required'
        ], [
            'date.required'				=> 'Date cannot be empty.',
			'kode.required'				=> 'Kode cannot be empty.',
			'al_product_id.required'	=> 'Product cannot be empty.',
			'al_product_id.array'		=> 'Product must be array.',
			'source.required'			=> 'Source cannot be empty.',
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$total = 0;
				
			foreach($request->al_product_qty as $key => $rowqty){
				$total += $rowqty * floatval(str_replace(',','.',str_replace('.','',$request->al_product_price[$key])));
			}
			
			$ppn = AlProject::find($request->project_id)->is_ppn == '1' ? $total * 0.11 : 0;
			$pph = $request->pph == '1' ? $total * 0.015 : 0;
			
			$grandtotal = $total + $ppn + $pph;
			
			if($request->temp){
				$query = AlSph::find($request->temp);
				
				$query->update([
					'user_id'			=> session('bo_id'),
					'al_project_id'		=> $request->project_id,
					'code'				=> $request->kode,
					'date'				=> $request->date,
					'total'				=> $total,
					'ppn'				=> $ppn,
					'is_pph'			=> $request->pph,
					'pph'				=> $pph,
					'grandtotal'		=> $grandtotal,
					'dk'				=> $request->dk,
					'admin'				=> $request->admin,
					'note'				=> $request->note,
					'period'			=> $request->period,
					'source'			=> $request->source,
					'contract_no'		=> $request->contract_no,
					'contract_date'		=> $request->contract_date
				]);
				
				$query->alSphProduct()->delete();
				
			}else{
				$count = AlSph::where('al_project_id',$request->project_id)->count();
				
				if($count > 0){
					$count = AlSph::where('al_project_id',$request->project_id)->max('revision') + 1;
				}
				
				$query = AlSph::create([
					'user_id'			=> session('bo_id'),
					'al_project_id'		=> $request->project_id,
					'code'				=> $request->kode,
					'date'				=> $request->date,
					'revision'			=> $count,
					'total'				=> $total,
					'ppn'				=> $ppn,
					'is_pph'			=> $request->pph,
					'pph'				=> $pph,
					'grandtotal'		=> $grandtotal,
					'dk'				=> $request->dk,
					'admin'				=> $request->admin,
					'note'				=> $request->note,
					'period'			=> $request->period,
					'source'			=> $request->source,
					'contract_no'		=> $request->contract_no,
					'contract_date'		=> $request->contract_date
				]);
			}
			
			if($query){
				foreach($request->al_product_id as $key => $rowid){
					$alproduk = AlProduct::find($rowid);
					
					$alproduk->update([
						'sell_price'	=> str_replace(',','.',str_replace('.','',$request->al_product_price[$key])),
						'buy_price'		=> str_replace(',','.',str_replace('.','',$request->al_product_buy_price[$key]))
					]);
					
					$totalrow = $request->al_product_qty[$key] * floatval(str_replace(',','.',str_replace('.','',$request->al_product_price[$key])));
					
					AlSphProduct::create([
						'al_sph_id'			=> $query->id,
						'al_product_id'		=> $rowid,
						'qty'				=> $request->al_product_qty[$key],
						'buy_price'			=> $alproduk->buy_price,
						'sell_price'		=> str_replace(',','.',str_replace('.','',$request->al_product_price[$key])),
						'total'				=> $totalrow
					]);
				}
			}
			
			$response = [
                'status' 	=> 200,
                'message'  	=> 'Data saved successfully.'
            ];
		}
		
		return response()->json($response);
	}
	
	public function getListSph(Request $request){
		
		$data = AlSph::where('al_project_id',$request->id)->orderBy('id','DESC')->get();
		
		return response()->json($data);
	}
	
	public function showEditSph(Request $request){
		$data = AlSph::find($request->id);
		$parent = $data->alProductParent();
		$dataproduct = [];
		

		foreach ($parent as $rowparent) {
			foreach($data->alSphProduct()->whereHas('alProduct', function($query) use ($rowparent){$query->where('al_product_parent_id',$rowparent['id'])->groupBy('al_category_id'); })->get() as $key => $row){
				if ($key == 0) {
					$row['parent_name']	= $rowparent['name'];
				}
				$row['product_name'] = $row->alproduct->name;
				$row['product_description'] = strip_tags($row->alproduct->description);
				$row['product_unit'] = $row->alproduct->unit();
				$dataproduct[] = $row;
			}
		}
		
		
		return response()->json([
			'data'		=> $data,
			'detail'	=> $dataproduct
		]);
	}
	
	public function printSph(Request $request){
		
		$mode = $request->mode ? $request->mode : '';
		
		$data = AlSph::find($request->id);
		
		$parent = $data->alProductParent();
		
		return view('admin.al.print.sph', [
			'data' 		=> $data,
			'mode'		=> $mode,
			'parent'	=> $parent
		]);
	}
	
	public function printSphProfit(Request $request){
		$data = AlSph::find($request->id);
		
		$parent = $data->alProductParent();
		$grand_parent = $data->alProductParent2();
		
		return view('admin.al.print.sph_profit', [
			'data' 		=> $data,
			'parent'	=> $parent,
			'grand_parent'	=> $grand_parent,
		]);
	}
	
	public function printSphPembelian(Request $request){
		$data = AlSph::find($request->id);
		
		$parent = $data->alProductParent();
		
		return view('admin.al.print.sph_pembelian', [
			'data' 		=> $data,
			'parent'	=> $parent
		]);
	}
	
	public function destroySph(Request $request){
		$sph = AlSph::find($request->id);
		$sph->alApplicationLetter()->delete();
		
		if($sph){
			$sph->alSphProduct()->delete();
		}
		
		if($sph->delete()){
			$response = [
                'status' 	=> 200,
                'message'  	=> 'Data deleted successfully.'
            ];
		}else{
			$response = [
                'status' 	=> 422,
                'message'  	=> 'Ups Error.'
            ];
		}
		
		return response()->json($response);
	}
}