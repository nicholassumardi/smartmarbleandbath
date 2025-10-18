<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlInvoice;
use App\Models\AlSph;
use App\Models\AlSphProduct;
use App\Models\AlProduct;
use App\Models\AlCustomer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AlInvoiceController extends Controller {
    
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
            'title'    		=> 'Al Surat Permohonan',
			'proyek'		=> $project,
			'sph'			=> $resultsph,
            'content'  		=> 'admin.al.faktur_barang'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
			'code',
            'al_project_id',
            'al_sph_id',
			'date',
            'nominal'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlInvoice::count();
        
        $query_data = AlInvoice::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('date', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%")
								->orWhereHas('alCustomer', function($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								});
                            })->orWhereHas('alSph', function($query) use ($search) {
                                $query->where('code', 'like', "%$search%");
							});
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlInvoice::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
						$query->where('code', 'like', "%$search%")
                            ->orWhere('date', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->orWhere('code', 'like', "%$search%")
								->orWhereHas('alCustomer', function($query) use ($search) {
									$query->where('name', 'like', "%$search%");
								});
                            })->orWhereHas('alSph', function($query) use ($search) {
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
                    $val->alProject->code.' '.$val->alProject->name,
                    $val->alSph->code,
					date('d M Y',strtotime($val->date)),
					number_format($val->nominal,0,',','.'),
					'
						<a href="' . url('admin/al/faktur_barang/print/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Print" target="_blank"><i class="icon-printer"></i></a>
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
            'al_project_id' 				=> 'required',
			'al_sph_id'						=> 'required',
			'date'							=> 'required',
			'nominal'						=> 'required',
			'sign_name'						=> 'required',
			'sign_position'					=> 'required'
        ], [
			'al_project_id.required'	=> 'Project cannot be empty.',
			'al_sph_id.required'		=> 'SPH cannot be empty.',
			'date.required'				=> 'Date cannot be empty.',
			'nominal.required'			=> 'Nominal cannot be empty.',
			'sign_name'					=> 'Sign name cannot be empty.',
			'sign_position'				=> 'Sign position cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = AlInvoice::find($request->temp);
				
				$query->update([
					'user_id'			=> session('bo_id'),
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id,
					'date'				=> $request->date,
					'nominal'			=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'note'				=> $request->note,
					'sign_name'			=> $request->sign_name,
					'sign_position'		=> $request->sign_position
				]);
				
			}else{
				$query = AlInvoice::create([
					'user_id'			=> session('bo_id'),
					'code'				=> AlInvoice::generateCode(),
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id,
					'date'				=> $request->date,
					'nominal'			=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'note'				=> $request->note,
					'sign_name'			=> $request->sign_name,
					'sign_position'		=> $request->sign_position
				]);
			}
			
			$response = [
                'status' 	=> 200,
                'message'  	=> 'Data saved successfully.'
            ];
		}
		
		return response()->json($response);
	}
	
	public function show(Request $request){
		$data = AlInvoice::find($request->id);
		$data['nominal'] = number_format($data->nominal,0,',','.');

		return response()->json([
			'data'		=> $data
		]);
	}
	
	public function destroy(Request $request){
		$invoice = AlInvoice::find($request->id);
		
		if(count($invoice->alIncome) > 0){
			foreach($invoice->alIncome as $row){
				$row->deleteFile();
				$row->delete();
			}
		} 
		
		
		if($invoice->delete()){
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
	
	public function print(Request $request){
		$data = AlInvoice::find($request->id);
		
		return view('admin.al.print.faktur_barang', [
			'data' 		=> $data
		]);
	}
}