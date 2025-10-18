<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlIncome;
use App\Models\AlInvoice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helper\SMB;

class AlFinanceIncomeController extends Controller
{
    public function index()
    {
		
		$resultinvoice = [];
			
		foreach(AlInvoice::all() as $row){
			$resultinvoice[] = [
				'id'				=> $row->id,
				'al_project_id'		=> $row->al_project_id,
				'al_sph_id'			=> $row->al_sph_id,
				'code'				=> $row->code,
				'date'				=> date('d M Y', strtotime($row->date)),
				'nominal'			=> number_format($row->nominal,0,',','.'),
				'customer'			=> $row->alProject->alCustomer->name,
				'project_name'		=> $row->alProject->name,
				'ppn_info'			=> $row->alProject->is_ppn == '1'? 'Termasuk ppn 11%' : '',
				'contract_no'		=> $row->alSph->contract_no ? $row->alSph->contract_no : 'Kosong',
				'contract_date'		=> $row->alSph->contract_date ? SMB::tgl_indo($row->alSph->contract_date) : 'Kosong'
			];
		}
		
        $data = [
            'title'   		=> 'AL Finance Pemasukan',
			'proyek'		=> AlProject::all(),
			'invoice'		=> $resultinvoice,
            'content' 		=> 'admin.al.finance.pemasukan',
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
			'code',
            'al_project_id',
            'from_person',
            'date',
			'note',
			'nominal',
			'image'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlIncome::count();
        
        $query_data = AlIncome::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('from_person', 'like', "%$search%")
                            ->orWhere('date', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->where('code', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlIncome::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('from_person', 'like', "%$search%")
                            ->orWhere('date', 'like', "%$search%")
							->orWhere('code', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%")
							->orWhereHas('alProject', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%")
								->where('code', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				if($val->image){
					if(explode('.',$val->image)[1] == 'pdf'){
						$photo = '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$val->note.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}else{
					$photo = '<span class="badge badge-danger">Empty</span>';
				}
				
                $response['data'][] = [
                    $nomor,
					$val->code,
                    $val->alProject->code.' - '.$val->alProject->name,
                    $val->from_person,
                    date('d M Y',strtotime($val->date)),
					$val->note,
					number_format($val->nominal + $val->nominal_other,0,',','.'),
					$photo,
                    '
						<a href="' . url('admin/al/finance/pemasukan/print/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Print" target="_blank"><i class="icon-printer"></i></a>
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
	
	public function create(Request $request)
    {
		$validation = Validator::make($request->all(), [
			'al_project_id'   			=> 'required',
			'from_person'   			=> 'required',
			'date'  					=> 'required',
			'nominal'   				=> 'required',
		], [
			'al_project_id.required'    => 'Project cannot be empty.',
			'from_person.required'    	=> 'From person cannot be empty.',
			'date.required'  			=> 'Date cannot be empty.',
			'nominal.required'    		=> 'Nominal cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$cek = AlIncome::find($request->temp);
				
				if($request->has('file')) {
					if(Storage::exists($cek->image)) {
						Storage::delete($cek->image);
					}

					$image = $request->file('file')->store('public/al_income');
				} else {
					$image = $cek->image;
				}
				
				$query = $cek->update([
					'user_id'	     		=> session('bo_id'),
					'al_project_id'			=> $request->al_project_id,
					'al_invoice_id'			=> $request->al_invoice_id ? $request->al_invoice_id : NULL,
					'from_person'			=> $request->from_person,
					'date'					=> $request->date,
					'image'					=> $image,
					'note'					=> $request->note,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'nominal_other'		    => str_replace(',','.',str_replace('.','',$request->nominal_other)),
				]);
			}else{
				$query = AlIncome::create([
					'user_id'	     		=> session('bo_id'),
					'code'					=> AlIncome::generateCode(),
					'al_project_id'			=> $request->al_project_id,
					'al_invoice_id'			=> $request->al_invoice_id ? $request->al_invoice_id : NULL,
					'from_person'			=> $request->from_person,
					'date'					=> $request->date,
					'image'					=> $request->has('file') ? $request->file('file')->store('public/al_income') : null,
					'note'					=> $request->note,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'nominal_other'		    => str_replace(',','.',str_replace('.','',$request->nominal_other)),
				]);
			}
			
            if($query) {
                activity()
                    ->performedOn(new AlIncome())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add/Update Al Income by user '.session('bo_name'));

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
        }

        return response()->json($response);
    }
	
	public function print(Request $request){
		$data = AlIncome::find($request->id);
		
		return view('admin.al.print.income', [
			'data' 		=> $data
		]);
	}
	
	public function show(Request $request){
		$data = AlIncome::find($request->id);
		$data['nominal'] = number_format($data->nominal,0,',','.');
		
		return response()->json([
			'data'		=> $data
		]);
	}
	
	public function destroy(Request $request) 
    {
        $query = AlIncome::find($request->id);
		
		$query->deleteFile();
		$query->delete();
		
        if($query) {
            activity()
                ->performedOn(new AlIncome())
                ->causedBy(session('bo_id'))
                ->log('Delete the Al Income data');

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
}