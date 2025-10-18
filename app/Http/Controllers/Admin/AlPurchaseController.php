<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlSph;
use App\Models\AlProduct;
use App\Models\AlPurchase;
use App\Models\AlPurchaseProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AlPurchaseController extends Controller
{
    public function index()
    {
		$project = AlProject::all();
		
		$resultsph = [];
			
		foreach(AlSph::all() as $row){
			$resultsph[] = [
				'id'				=> $row->id,
				'al_project_id'		=> $row->al_project_id,
				'code'				=> $row->code,
				'revision'			=> $row->revision,
				'date'				=> date('d M Y', strtotime($row->date)),
				'total'				=> number_format($row->total,0,',','.'),
				'ppn'				=> number_format($row->ppn,0,',','.'),
				'grandtotal'		=> number_format($row->grandtotal,0,',','.')
			];
		}
		
        $data = [
            'title'   		=> 'AL Purchase Data',
            'content' 		=> 'admin.al.pembelian',
			'proyek'		=> $project,
			'barang' 		=> AlProduct::all(),
			'sph'			=> $resultsph,
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function getSupplierSph(Request $request){
		$sph_id = $request->id;
		
		$data = [];
		$supplier = [];
		
		foreach(AlSph::find($sph_id)->alSphProduct as $row){
			$supplier[] = [
				'al_supplier_id'	=> $row->alProduct->al_supplier_id,
				'al_supplier_name'	=> $row->alProduct->alSupplier->name,
				'al_supplier_code'	=> $row->alProduct->alSupplier->code
			];
			
			$data[] = [
				'al_supplier_id'	=> $row->alProduct->al_supplier_id,
				'al_product_id'		=> $row->al_product_id,
				'al_product_name'	=> $row->alProduct->name,
				'al_product_spec'	=> $row->alProduct->description,
				'qty'				=> $row->qty,
				'unit'				=> $row->alProduct->unit(),
				'buy_price'			=> number_format($row->buy_price,0,',','.'),
				'total'				=> number_format($row->qty * $row->buy_price,0,',','.'),
			];
 		}
		
		$collection = collect($supplier);
		
		$unique = $collection->unique('al_supplier_id')->values()->all();
		
		return response()->json([
			'data'		=> $data,
			'supplier'	=> $unique
		]);
	}
	
	public function create(Request $request){
		$validation = Validator::make($request->all(), [
            'al_project_id' 				=> 'required',
			'al_sph_id'						=> 'required',
			'al_supplier_id'				=> 'required',
			'date'							=> 'required',
			'is_ppn'						=> 'required',
			'al_product_id'					=> 'required|array',
			'al_product_qty'				=> 'required|array',
			'al_product_price'				=> 'required|array',
        ], [
			'al_project_id.required'	=> 'Project cannot be empty.',
			'al_sph_id.required'		=> 'SPH cannot be empty.',
			'al_supplier_id.required'	=> 'Supplier cannot be empty.',
            'date.required'				=> 'Date cannot be empty.',
			'is_ppn.required'			=> 'PPN cannot be empty.',
			'al_product_id.required'	=> 'Product cannot be empty.',
			'al_product_id.array'		=> 'Product must be array.',
			'al_product_qty.required'	=> 'Product qty cannot be empty.',
			'al_product_qty.array'		=> 'Product qty must be array.',
			'al_product_price.required'	=> 'Product price cannot be empty.',
			'al_product_price.array'	=> 'Product price must be array.',
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
			
			$ppn = $request->is_ppn == '1' ? $total * 0.11 : 0;
			
			$grandtotal = $total + $ppn;
			
			if($request->temp){
				$query = AlPurchase::find($request->temp);
				
				$query->update([
					'user_id'			=> session('bo_id'),
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id,
					'al_supplier_id'	=> $request->al_supplier_id,
					'date'				=> $request->date,
					'is_ppn'			=> $request->is_ppn,
					'total'				=> $total,
					'ppn'				=> $ppn,
					'grandtotal'		=> $grandtotal,
					'note'				=> $request->note
				]);
				
				$query->alPurchaseProduct()->delete();
				
			}else{
				$query = AlPurchase::create([
					'user_id'			=> session('bo_id'),
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id,
					'al_supplier_id'	=> $request->al_supplier_id,
					'code'				=> AlPurchase::generateCode(),
					'date'				=> $request->date,
					'is_ppn'			=> $request->is_ppn,
					'total'				=> $total,
					'ppn'				=> $ppn,
					'grandtotal'		=> $grandtotal,
					'note'				=> $request->note
				]);
			}
			
			if($query){
				foreach($request->al_product_id as $key => $rowid){
					$alproduk = AlProduct::find($rowid);
					
					$alproduk->update([
						'buy_price'		=> str_replace(',','.',str_replace('.','',$request->al_product_price[$key])),
						'description'	=> $request->al_product_description[$key]
					]);
					
					$totalrow = $request->al_product_qty[$key] * floatval(str_replace(',','.',str_replace('.','',$request->al_product_price[$key])));
					
					AlPurchaseProduct::create([
						'al_purchase_id'	=> $query->id,
						'al_product_id'		=> $rowid,
						'qty'				=> $request->al_product_qty[$key],
						'buy_price'			=> str_replace(',','.',str_replace('.','',$request->al_product_price[$key])),
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
	
	public function datatable(Request $request){
		$column = [
            'id',
            'code',
            'al_project_id',
            'al_sph_id',
			'date',
			'al_supplier_id',
			'grandtotal'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlPurchase::count();
        
        $query_data = AlPurchase::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhere('total','like', "%$search%")
							->orWhere('grandtotal','like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%")
								->orWhereHas('alCustomer', function($query) use ($search) {
									 $query->where('name', 'like', "%$search%");
								});
                            })
							->orWhereHas('alSph', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlPurchase::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhere('total','like', "%$search%")
							->orWhere('grandtotal','like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%")
								->orWhereHas('alCustomer', function($query) use ($search) {
									 $query->where('name', 'like', "%$search%");
								});
                            })
							->orWhereHas('alSph', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->alProject->name,
					$val->alSph->code,
                    date('d M Y',strtotime($val->date)),
					$val->alSupplier->name,
					'Rp '.number_format($val->grandtotal,0,',','.'),
                    '
						<a href="' . url('admin/al/po/print_no_price/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Print tanpa harga" target="_blank"><i class="icon-printer"></i></a>
						<a href="' . url('admin/al/po/print/' . $val->id) . '" class="btn bg-info btn-sm" data-popup="tooltip" title="Print dengan harga" target="_blank"><i class="icon-printer"></i></a>
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
	
	public function print(Request $request){
		$data = AlPurchase::find($request->id);
		
		return view('admin.al.print.pembelian', [
			'data' 		=> $data
		]);
	}
	
	public function printNoPrice(Request $request){
		$data = AlPurchase::find($request->id);
		
		return view('admin.al.print.pembelian_tanpa_harga', [
			'data' 		=> $data
		]);
	}
	
	public function show(Request $request){
		$data = AlPurchase::find($request->id);

		$product = [];
		$supplier = [];
		
		foreach($data->alPurchaseProduct as $row){
			$supplier[] = [
				'al_supplier_id'	=> $row->alProduct->al_supplier_id,
				'al_supplier_name'	=> $row->alProduct->alSupplier->name,
				'al_supplier_code'	=> $row->alProduct->alSupplier->code
			];
			
			$product[] = [
				'al_supplier_id'	=> $row->alProduct->al_supplier_id,
				'al_product_id'		=> $row->al_product_id,
				'al_product_name'	=> $row->alProduct->name,
				'al_product_spec'	=> $row->alProduct->description,
				'qty'				=> $row->qty,
				'unit'				=> $row->alProduct->unit(),
				'buy_price'			=> number_format($row->buy_price,2,',','.'),
				'total'				=> number_format($row->qty * $row->buy_price,2,',','.'),
			];
 		}
		
		$collection = collect($supplier);
		
		$unique = $collection->unique('al_supplier_id')->values()->all();
		
		return response()->json([
			'data'		=> $data,
			'detail'	=> $product,
			'supplier'	=> $unique
		]);
	}
	
	public function destroy(Request $request){
		$purchase = AlPurchase::find($request->id);
		
		if($purchase){
			$purchase->alPurchaseProduct()->delete();
		}
		
		if($purchase->delete()){
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
	