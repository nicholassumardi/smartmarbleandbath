<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlSph;
use App\Models\AlApplicationLetter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AlApplicationLetterController extends Controller {
    
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
            'content'  		=> 'admin.al.surat_permohonan'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
            'al_project_id',
            'al_sph_id',
			'to_whom',
            'company',
			'address',
			'city_id',
			'nominal',
			'date',
			'period',
			'finish',
			'no_rekening'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlApplicationLetter::count();
        
        $query_data = AlApplicationLetter::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('to_whom', 'like', "%$search%")
                            ->orWhere('company', 'like', "%$search%")
							->orWhere('address', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhere('period', 'like', "%$search%")
							->orWhere('no_rekening', 'like', "%$search%")
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

        $total_filtered = AlApplicationLetter::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('to_whom', 'like', "%$search%")
                            ->orWhere('company', 'like', "%$search%")
							->orWhere('address', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhere('period', 'like', "%$search%")
							->orWhere('no_rekening', 'like', "%$search%")
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
                    $val->alProject->code.' '.$val->alProject->name,
                    $val->alSph->code,
					$val->code,
					$val->to_whom,
					$val->company,
					$val->address,
					$val->city->name,
					number_format($val->nominal,0,',','.'),
                    date('d M Y',strtotime($val->date)),
					$val->period,
					date('d M Y', strtotime($val->date . " + ".$val->period." days")),
					$val->no_rekening,
					'
						<a href="' . url('admin/al/surat_permohonan/print/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Print" target="_blank"><i class="icon-printer"></i></a>
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
			'title'							=> 'required',
			'to_whom'						=> 'required',
			'company'						=> 'required',
			'address'						=> 'required',
			'city_id'						=> 'required',
			'nominal'						=> 'required',
			'date'							=> 'required',
			'period'						=> 'required',
			'no_rekening'					=> 'required',
			'bank'							=> 'required',
			'contract_no'					=> 'required',
			'type_bg'						=> 'required',
			'for_bg'					 	=> 'required',
        ], [
			'al_project_id.required'	=> 'Project cannot be empty.',
			'al_sph_id.required'		=> 'SPH cannot be empty.',
			'title.required'			=> 'Title cannot be empty.',
			'to_whom.required'			=> 'Kepada cannot be empty.',
            'company.required'			=> 'Supir cannot be empty.',
			'address.required'			=> 'Vehicle type cannot be empty.',
			'city_id.required'			=> 'Vehicle no cannot be empty.',
			'nominal.required'			=> 'City cannot be empty.',
			'date.required'				=> 'Date sent cannot be empty.',
			'period.required'			=> 'Product cannot be empty.',
			'no_rekening.required'		=> 'Product qty cannot be empty.',
			'bank.required'				=> 'Product price cannot be empty.',
			'contract_no.required'		=> 'Contract no cannot be empty.',
			'type_bg.required'			=> 'Type BG cannot be empty.',
			'for_bg.required'			=> 'For BG cannot be empty.',
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = AlApplicationLetter::find($request->temp);
				
				$query->update([
					'user_id'			=> session('bo_id'),
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id,
					'title'				=> $request->title,
					'to_whom'			=> $request->to_whom,
					'company'			=> $request->company,
					'address'			=> $request->address,
					'city_id'			=> $request->city_id,
					'nominal'			=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'date'				=> $request->date,
					'period'			=> $request->period,
					'no_rekening'		=> $request->no_rekening,
					'bank'				=> $request->bank,
					'contract_no'		=> $request->contract_no,
					'type_bg'			=> $request->type_bg,
					'for_bg'			=> $request->for_bg
				]);
				
			}else{
				$query = AlApplicationLetter::create([
					'user_id'			=> session('bo_id'),
					'code'				=> AlApplicationLetter::generateCode(),
					'al_project_id'		=> $request->al_project_id,
					'al_sph_id'			=> $request->al_sph_id,
					'title'				=> $request->title,
					'to_whom'			=> $request->to_whom,
					'company'			=> $request->company,
					'address'			=> $request->address,
					'city_id'			=> $request->city_id,
					'nominal'			=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'date'				=> $request->date,
					'period'			=> $request->period,
					'no_rekening'		=> $request->no_rekening,
					'bank'				=> $request->bank,
					'contract_no'		=> $request->contract_no,
					'type_bg'			=> $request->type_bg,
					'for_bg'			=> $request->for_bg
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
		$data = AlApplicationLetter::find($request->id);
		$data['city_name'] = $data->city->name;
		$data['nominal'] = number_format($data->nominal,0,',','.');

		return response()->json([
			'data'		=> $data
		]);
	}
	
	public function print(Request $request){
		$data = AlApplicationLetter::find($request->id);
		
		return view('admin.al.print.surat_permohonan', [
			'data' 		=> $data
		]);
	}
	
	public function destroy(Request $request){
		$application = AlApplicationLetter::find($request->id);
		
		if($application->delete()){
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