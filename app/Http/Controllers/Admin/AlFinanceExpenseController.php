<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProject;
use App\Models\AlBudgeting;
use App\Models\AlExpense;
use App\Models\AlExpenseDocument;
use App\Models\AlExpensePay;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlFinanceExpenseController extends Controller
{
    public function index()
    {
		
        $data = [
            'title'   		=> 'AL Finance Pengeluaran',
			'proyek'		=> AlProject::all(),
            'content' 		=> 'admin.al.finance.pengeluaran',
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function detailIndex(Request $request, $id)
    {
		$proyek = AlProject::find($id);
		
        $data = [
            'title'   		=> 'AL Finance Pengeluaran '.$proyek->name,
			'proyek'		=> $proyek,
			'po'			=> $proyek->alPurchase,
            'content' 		=> 'admin.al.finance.pengeluaran_detail',
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
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->name,
					$val->field_of_work,
					$val->location,
					$val->alCustomer->name,
                    date('d M Y',strtotime($val->date)),
					$val->is_ppn(),
					'<a href="'.url("admin/al/finance/pengeluaran/detail/".$val->id).'" class="btn bg-info btn-sm" data-popup="tooltip" title="Tambah/Edit Pengeluaran Proyek '.$val->name.'"><i class="icon-zoomin3"></i></a>'
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
	
	public function detailDatatable(Request $request,$id){
		$column = [
            'id',
            'al_project_id',
            'to_person',
            'date',
			'type',
			'note',
			'nominal',
			'image'
        ];

        $start  = $request->start;
        $length = $request->length < 0 ? 999999999999999 : $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlExpense::count();
        
        $query_data = AlExpense::where(function($query) use ($search, $request, $id) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('to_person', 'like', "%$search%")
                            ->orWhere('date', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhere('title', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%");
                    });
                }
				
				$query->where('al_project_id',$id);
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlExpense::where(function($query) use ($search, $request, $id) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('to_person', 'like', "%$search%")
                            ->orWhere('date', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhere('title', 'like', "%$search%")
							->orWhere('nominal', 'like', "%$search%");
                    });
                }
				
				$query->where('al_project_id',$id);
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
						$photo = '<a data-magnify="gallery" data-src="" data-caption="" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
					}
				}else{
					$photo = '<span class="badge badge-danger">Empty</span>';
				}
				
                $response['data'][] = [
                    $nomor,
                    $val->alProject->code.' - '.$val->alProject->name,
                    $val->to_person,
                    date('d M Y',strtotime($val->date)),
					$val->type(),
					$val->title.'<br>'.$val->note,
					number_format($val->nominal,0,',','.'),
					$photo,
					$val->getProgressPayment(),
					'<button type="button" class="btn bg-info btn-sm" data-popup="tooltip" title="Bayar" onclick="showPay(' . $val->id . ',`'.$val->to_person.'`,`'.$val->alProject->code.' - '.$val->alProject->name.'`,`'.number_format($val->nominal,0,',','.').'`,`'.$val->date.'`)"><i class="icon-cash"></i></button>',
                    '
                        <a href="javascript:void(0);" data-popup="tooltip" title="Upload berkas pengeluaran '.$val->alProject->name.'" onclick="addPictures('.$val->id.')" class="btn bg-success btn-sm"><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->alExpenseDocument).'</span><i class="icon-images3"></i></a>
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
		if($request->al_project_id_rab){
			$validation = Validator::make($request->all(), [
			
			], [
			
			]);
		}else{
			$validation = Validator::make($request->all(), [
				'al_project_id'   			=> 'required',
				'to_person'   				=> 'required',
				'date'  					=> 'required',
				'nominal'   				=> 'required',
			], [
				'al_project_id.required'    => 'Project cannot be empty.',
				'to_person.required'    	=> 'To person cannot be empty.',
				'date.required'  			=> 'Date cannot be empty.',
				'nominal.required'    		=> 'Nominal cannot be empty.',
			]);
		}

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$cek = AlExpense::find($request->temp);
				
				if($request->has('file')) {
					if(Storage::exists($cek->image)) {
						Storage::delete($cek->image);
					}

					$image = $request->file('file')->store('public/al_expense');
				} else {
					$image = $cek->image;
				}
				
				$table = '';
					
				if($request->detail_keterangan){
					$table .= '<table cellspacing="0" style="border-collapse:collapse;">';
					foreach($request->detail_keterangan as $key => $row){
						$table .= '<tr>
								<td align="center">'.($key + 1).'.</td>
								<td>'.$row.'</td>
								<td align="right">'.number_format(str_replace(',','.',str_replace('.','',$request->detail_harga[$key])),0,',','.').'</td>
							</tr>
						';
					}
					$table .= '</table>';
				}
				
				$query = $cek->update([
					'user_id'	     		=> session('bo_id'),
					'al_project_id'			=> $request->al_project_id,
					'to_person'				=> $request->to_person,
					'type'					=> $request->al_type,
					'date'					=> $request->date,
					'image'					=> $image,
					'title'					=> $request->title,
					'note'					=> $request->note.'<br>'.$table,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal)),
				]);
			}else{
				
				if($request->al_project_id_rab){
					foreach($request->detail_to as $key => $row){
						$query = AlExpense::create([
							'user_id'	     		=> session('bo_id'),
							'al_project_id'			=> $request->al_project_id_rab,
							'to_person'				=> $row,
							'type'					=> $request->al_type,
							'date'					=> $request->detail_date[$key],
							'image'					=> NULL,
							'title'					=> $request->detail_title[$key],
							'note'					=> $request->detail_note[$key],
							'nominal'				=> str_replace(',','.',str_replace('.','',$request->detail_total[$key])),
						]);
					}
				}else{
					
					$table = '';
					
					if($request->detail_keterangan){
						$table .= '<table cellspacing="0" style="border-collapse:collapse;">';
						foreach($request->detail_keterangan as $key => $row){
							$table .= '<tr>
									<td align="center">'.($key + 1).'.</td>
									<td>'.$row.'</td>
									<td align="right">'.$request->detail_harga[$key].'</td>
								</tr>
							';
						}
						$table .= '</table>';
					}
					
					$query = AlExpense::create([
						'user_id'	     		=> session('bo_id'),
						'al_project_id'			=> $request->al_project_id,
						'to_person'				=> $request->to_person,
						'type'					=> $request->al_type,
						'date'					=> $request->date,
						'image'					=> $request->has('file') ? $request->file('file')->store('public/al_expense') : null,
						'title'					=> $request->title,
						'note'					=> $request->note.'<br>'.$table,
						'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal)),
					]);
				}
			}
			
            if($query) {
                activity()
                    ->performedOn(new AlExpense())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add/Update Al Expense by user '.session('bo_name'));

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
	
	public function getPictures(Request $request){
		$data = AlExpenseDocument::where('al_expense_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$result[] = [
				'id'			=> $row->id,
				'filename'		=> $row->attachment(),
				'picture'		=> $row->picture(),
				'name'			=> $row->document_name
			];
		}
		
		return response()->json($result);
	}
	
	public function addPictures(Request $request){
		
		$count = AlExpenseDocument::where('al_expense_id',$request->id)->count();
		
		if($count >= 5){
			return response()->json([
				'status'		=> 422,
				'message'		=> 'You have reached maximum file uploads for this project.'
			]);
		}else{
			
			$custom_file_name = strtolower(Str::random(10).'_'.str_replace(' ','_',$request->file('file')->getClientOriginalName()));
			
			$query = AlExpenseDocument::create([
				'user_id'			=> session('bo_id'),
				'al_expense_id'		=> $request->id,
				'document_name'		=> $request->file('file')->getClientOriginalName(),
				'file_name'			=> $request->file('file')->storeAs('public/al_expense', $custom_file_name)
			]);
			
			return response()->json([
				'status'		=> 200,
				'message'		=> 'You have successfully upload the file.'
			]);
		}
	}
	
	public function deletePictures(Request $request){
		$data = AlExpenseDocument::find($request->id);
		
		$data->deleteFile();
		
		$data->delete();
		
		if($data){
			return response()->json([
				'status'	=> 200,
				'message'	=> 'Picture successfully deleted.' 
			]);
		}else{
			return response()->json([
				'status'	=> 422,
				'message'	=> 'Picture not found.'
			]);
		}
	}
	
	public function createPay(Request $request)
    {
		
		$validation = Validator::make($request->all(), [
			'temp_pay'   				=> 'required',
			'pay_receiver'   			=> 'required',
			'pay_date'  				=> 'required',
			'pay_nominal'				=> 'required',
		], [
			'temp_pay.required'    		=> 'Expense identity cannot be empty.',
			'pay_receiver.required'    	=> 'Receiver cannot be empty.',
			'pay_date.required'  		=> 'Date payment cannot be empty.',
			'pay_nominal.required'    	=> 'Nominal cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$query = AlExpensePay::create([
				'user_id'	     		=> session('bo_id'),
				'al_expense_id'			=> $request->temp_pay,
				'receiver'				=> $request->pay_receiver,
				'date'					=> $request->pay_date,
				'image'					=> $request->has('pay_file') ? $request->file('pay_file')->store('public/al_expense') : NULL,
				'note'					=> $request->pay_note,
				'nominal'				=> str_replace(',','.',str_replace('.','',$request->pay_nominal)),
			]);
			
			if($query->image){
				if(explode('.',$query->image)[1] == 'pdf'){
					$photo = '<a href="' .$data->attachment() . '" class="btn btn-sm btn-info" target="_blank" id="proofPay'.$query->id.'"><i class="icon-search4"></i></a>';
				}else{
					$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$query->receiver.'" data-group="a" href="' .$query->attachment() . '" id="proofPay'.$query->id.'"><img src="' . $query->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
				}
				
				$photo .= '<input type="file" name="filepay'.$query->id.'" id="filepay'.$query->id.'" onchange="uploadPayProof(this.value,'.$query->id.')" accept="image/jpeg,image/gif,image/png,application/pdf">';
			}else{
				$photo = '<input type="file" name="filepay'.$query->id.'" id="filepay'.$query->id.'" onchange="uploadPayProof(this.value,'.$query->id.')" accept="image/jpeg,image/gif,image/png,application/pdf">';
			}
			
			$query['attachment'] = $photo;
			$query['date'] = date('d M Y',strtotime($query->date));
			$query['note'] = $query->note ? $query->note : '';
			$query['nominal'] = number_format($query->nominal,0,',','.');
			
            if($query) {
                activity()
                    ->performedOn(new AlExpense())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add/Update Al Expense by user '.session('bo_name'));

                $response = [
                    'status'  	=> 200,
					'data'		=> $query,
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
		$data = AlExpense::find($request->id);
		$data['nominal'] = number_format($data->nominal,0,',','.');
		
		return response()->json([
			'data'		=> $data
		]);
	}
	
	public function destroy(Request $request)
    {
        $query = AlExpense::find($request->id);
		
		$query->deleteFile();
		$query->delete();
		
        if($query) {
            activity()
                ->performedOn(new AlExpense())
                ->causedBy(session('bo_id'))
                ->log('Delete the Al Expense data');

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
	
	public function getBudgetingExpense(Request $request){
		$cek = AlBudgeting::whereHas('alSph', function($query) use ($request) {
			$query->where('al_project_id',$request->id);
		})->get();
		
		$data = [];
		
		foreach($cek as $row){
			foreach($row->alBudgetingExpense as $rowdetail){
				$rowdetail['to_whom'] = $rowdetail->to_whom ? $rowdetail->to_whom : '-';
				$data[] = $rowdetail;
			}
		}
		
		return response()->json([
			'data'	=> $data
		]);
	}
	
	public function getPayment(Request $request){
		$query = AlExpense::find($request->id);
		
		$data = [];

		foreach($query->alExpensePay()->orderBy('date')->get() as $row){
			if($row->image){
				if(explode('.',$row->image)[1] == 'pdf'){
					$photo = '<a href="' .$row->attachment() . '" class="btn btn-sm btn-info" target="_blank" id="proofPay'.$row->id.'"><i class="icon-search4"></i></a>';
				}else{
					$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$row->receiver.'" data-group="a" href="' .$row->attachment() . '" id="proofPay'.$row->id.'"><img src="' . $row->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
				}
				
				$photo .= '<input type="file" name="filepay'.$row->id.'" id="filepay'.$row->id.'" onchange="uploadPayProof(this.value,'.$row->id.')" accept="image/jpeg,image/gif,image/png,application/pdf">';
			}else{
				$photo = '<input type="file" name="filepay'.$row->id.'" id="filepay'.$row->id.'" onchange="uploadPayProof(this.value,'.$row->id.')" accept="image/jpeg,image/gif,image/png,application/pdf">';
			}
			
			$row['attachment'] = $photo;
			$row['dateraw'] = $row->date;
			$row['date'] = date('d M Y',strtotime($row->date));
			$row['note'] = $row->note ? $query->note : '';
			$row['nominal'] = number_format($row->nominal,0,',','.');
			
			$data[] = $row;
		}
		
		return response()->json($data);
	}
	
	public function print(Request $request){
		if($request->mode == 'all'){
			$data = AlExpense::All();
			$title = 'KESELURUHAN';
		}else{
			$data = AlExpense::where('al_project_id',$request->mode)->get();
			$title = AlProject::find($request->mode)->name;
		}
		
		return view('admin.al.print.expense', [
			'data' 		=> $data,
			'title'		=> $title
		]);
	}
	
	public function deletePayment(Request $request){
		
		$query = AlExpensePay::find($request->id);
		
		if($query->image){
			$query->deleteFile();
		}
		
		$query->delete();
		
		$countpay = AlExpensePay::where('al_expense_id',$query->al_expense_id)->count();
		
		if($query) {
			activity()
				->performedOn(new AlExpensePay())
				->causedBy(session('bo_id'))
				->log('Delete the payment AL expense request data');

			$response = [
				'status'  => 200,
				'count'	  => $countpay,
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
	
	public function updatePayment(Request $request){
		
		$query = AlExpensePay::find($request->id)->update([
			'date'	=> $request->tgl
		]);
		
		if($query) {
			activity()
				->performedOn(new AlExpensePay())
				->causedBy(session('bo_id'))
				->log('Update payment data AlExpensePay');

			$response = [
				'status'  => 200,
				'message' => 'Data updated successfully.'
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Data failed to delete.'
			];
		}

        return response()->json($response);
	}
	
	public function uploadPayProof(Request $request)
    {
		$id = $request->id;
		
		$query = AlExpensePay::find($id);
		
		if($request->has('file')) {
			if(Storage::exists($query->image)) {
				Storage::delete($query->image);
			}

			$image = $request->file('file')->store('public/al_expense');
		} else {
			$image = $query->image;
		}
		
		$query->update([
			'image'	=> $image
		]);
		
		$data = AlExpensePay::find($query->id);
		
		if($data->image){
			if(explode('.',$data->image)[1] == 'pdf'){
				$photo = '<a href="' .$data->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
			}else{
				$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$data->receiver.'" data-group="a" href="' .$data->attachment() . '"><img src="' . $data->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
			}
		}else{
			$photo = '<input type="file" name="filepay'.$data->id.'" id="filepay'.$data->id.'" onchange="uploadPayProof(this.value,'.$data->id.')">';
		}
		
		if($query) {
			activity()
				->performedOn(new AlExpensePay())
				->causedBy(session('bo_id'))
				->withProperties($query)
				->log('Add/Update Al Expense Payment by user '.session('bo_name'));

			$response = [
				'status'  	=> 200,
				'data'		=> $query,
				'result'	=> $photo,
				'message' 	=> 'Data added successfully.'
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