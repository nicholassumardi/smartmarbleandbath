<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlSph;
use App\Models\AlProduct;
use App\Models\AlDelivery;
use App\Models\AlDeliveryProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AlDeliveryController extends Controller
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
            'title'   		=> 'AL Delivery Data',
            'content' 		=> 'admin.al.surat_jalan',
			'proyek'		=> $project,
			'sph'			=> $resultsph,
        ];

        return view('admin.layouts.index', ['data' => $data]);
	}
	
	public function datatable(Request $request){
		$column = [
            'id',
            'code',
            'al_project_id',
            'al_sph_id',
			'date_sent',
			'date_received',
			'to_whom'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlDelivery::count();
        
        $query_data = AlDelivery::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
							->orWhere('to_whom', 'like', "%$search%")
							->orWhere('date_sent','like', "%$search%")
							->orWhere('date_received','like', "%$search%")
							->orWhere('driver','like', "%$search%")
							->orWhere('vehicle_type','like', "%$search%")
							->orWhere('vehicle_no','like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%")
								->orWhereHas('alCustomer', function($query) use ($search) {
									 $query->where('name', 'like', "%$search%");
								});
                            })
							->orWhereHas('alSph', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%");
                            })
							->orWhereHas('city', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlDelivery::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
							->orWhere('to_whom', 'like', "%$search%")
							->orWhere('date_sent','like', "%$search%")
							->orWhere('date_received','like', "%$search%")
							->orWhere('driver','like', "%$search%")
							->orWhere('vehicle_type','like', "%$search%")
							->orWhere('vehicle_no','like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%")
								->orWhereHas('alCustomer', function($query) use ($search) {
									 $query->where('name', 'like', "%$search%");
								});
                            })
							->orWhereHas('alSph', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%");
                            })
							->orWhereHas('city', function($query) use ($search) {
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
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->alProject->name,
					$val->alSph->code,
                    date('d M Y',strtotime($val->date_sent)),
					$val->date_received ? '<a href="'.$val->attachment().'" target="_blank" class="btn bg-success">'.date('d M Y',strtotime($val->date_received)).'</a>' : '<a class="btn bg-danger blink-notification" href="javascript:void(0);" onclick="updateReceivedProof('.$val->id.',`'.$val->code.'`)"><i class="icon-file-upload"></i></a>',
					$val->to_whom,
                    '
						<a href="' . url('admin/al/surat_jalan/print/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Print" target="_blank"><i class="icon-printer"></i></a>
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
	
	public function getSphProduct(Request $request){
		$sph_id = $request->id;
		
		$data = [];
		
		foreach(AlSph::find($sph_id)->alSphProduct as $row){
			$data[] = [
				'al_supplier_id'	=> $row->alProduct->al_supplier_id,
				'al_product_id'		=> $row->al_product_id,
				'al_product_name'	=> $row->alProduct->name,
				'al_product_spec'	=> $row->alProduct->description,
				'qty'				=> $row->qty,
				'unit'				=> $row->alProduct->unit(),
			];
 		}
		
		return response()->json([
			'data'		=> $data,
		]);
	}
	
	public function create(Request $request){
		$validation = Validator::make($request->all(), [
            'al_project_id' 				=> 'required',
			'al_sph_id'						=> 'required',
			'to_whom'						=> 'required',
			'driver'						=> 'required',
			'vehicle_type'					=> 'required',
			'vehicle_no'					=> 'required',
			'city_id'						=> 'required',
			'date_sent'						=> 'required',
			'al_product_id'					=> 'required|array',
			'al_product_qty'				=> 'required|array',
			'al_product_note'				=> 'required|array',
        ], [
			'al_project_id.required'	=> 'Project cannot be empty.',
			'al_sph_id.required'		=> 'SPH cannot be empty.',
			'to_whom.required'			=> 'Kepada cannot be empty.',
            'driver.required'			=> 'Supir cannot be empty.',
			'vehicle_type.required'		=> 'Vehicle type cannot be empty.',
			'vehicle_no.required'		=> 'Vehicle no cannot be empty.',
			'city_id.required'			=> 'City cannot be empty.',
			'date_sent.required'		=> 'Date sent cannot be empty.',
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
			
			if($request->temp){
				$query = AlDelivery::find($request->temp);
				
				$query->update([
					'user_id'			=> session('bo_id'),
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id,
					'to_whom'			=> $request->to_whom,
					'date_sent'			=> $request->date_sent,
					'driver'			=> $request->driver,
					'vehicle_type'		=> $request->vehicle_type,
					'vehicle_no'		=> $request->vehicle_no,
					'city_id'			=> $request->city_id,
					'note'				=> $request->note
				]);
				
				$query->alDeliveryProduct()->delete();
				
			}else{
				$query = AlDelivery::create([
					'user_id'			=> session('bo_id'),
					'code'				=> AlDelivery::generateCode(),
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id,
					'to_whom'			=> $request->to_whom,
					'date_sent'			=> $request->date_sent,
					'driver'			=> $request->driver,
					'vehicle_type'		=> $request->vehicle_type,
					'vehicle_no'		=> $request->vehicle_no,
					'city_id'			=> $request->city_id,
					'note'				=> $request->note
				]);
			}
			
			if($query){
				foreach($request->al_product_id as $key => $rowid){
					AlDeliveryProduct::create([
						'al_delivery_id'	=> $query->id,
						'al_product_id'		=> $rowid,
						'qty'				=> $request->al_product_qty[$key],
						'note'				=> $request->al_product_note[$key],
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
	
	public function print(Request $request){
		$data = AlDelivery::find($request->id);
		
		return view('admin.al.print.surat_jalan', [
			'data' 		=> $data
		]);
	}
	
	public function show(Request $request){
		$data = AlDelivery::find($request->id);
		$data['city_name'] = $data->city->name;

		$product = [];
		
		foreach($data->AlDeliveryProduct as $row){
			$product[] = [
				'al_product_id'		=> $row->al_product_id,
				'al_product_name'	=> $row->alProduct->name,
				'al_product_spec'	=> $row->alProduct->description,
				'qty'				=> $row->qty,
				'unit'				=> $row->alProduct->unit(),
				'note'				=> $row->note
			];
 		}
		
		return response()->json([
			'data'		=> $data,
			'detail'	=> $product
		]);
	}
	
	public function destroy(Request $request){
		$delivery = AlDelivery::find($request->id);
		
		if($delivery){
			$delivery->deleteFile();
			$delivery->alDeliveryProduct()->delete();
		}
		
		if($delivery->delete()){
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
	
	public function addReceivedDate(Request $request){
		if(!$request->has('file') || !$request->date) {
			return response()->json([
				'status'  => 500,
				'message' => 'Error. Please choose file/date sir/madam.'
			]);
		}
		
		$purchasedelivery = AlDelivery::find($request->id);
		
		if($request->has('file')) {
			if(Storage::exists($purchasedelivery->image)) {
				Storage::delete($purchasedelivery->image);
			}

			$image = $request->file('file')->store('public/al_delivery_order');
		} else {
			$image = $purchasedelivery->image;
		}
		
		$purchasedelivery->update([
			'date_received'	=> $request->date,
			'image'			=> $image
		]);
		
		activity()
			->performedOn(new AlDelivery())
			->causedBy(session('bo_id'))
			->withProperties($purchasedelivery)
			->log('Update delivery received date.');
		
		return response()->json([
			'status'  => 200,
			'message' => 'Data added successfully.'
		]);
	}
}