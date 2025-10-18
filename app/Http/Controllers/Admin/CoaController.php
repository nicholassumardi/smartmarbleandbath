<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Coa;
use Illuminate\Http\Request;
use App\Exports\CoasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CoaController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'COA',
            'parent'  => Coa::all(),    
            'content' => 'admin.master_data.finance_accounting.coa'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'code',
            'name',
            'parent_id',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Coa::count();
        
        $query_data = Coa::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%");
                    });
                }         
                
                if($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Coa::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%");
                    });
                }       
                
                if($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				if($val->parent()){
					if(strlen($val->code) == 8 || strlen($val->code) == 9){
						$response['data'][] = [
							$nomor,
							'<ul style="margin-left:15px;" class="list list-unstyled mb-0"><li>'.$val->code.'</li></ul>',
							'<ul style="margin-left:15px;" class="list list-unstyled mb-0"><li>'.$val->name.'</li></ul>',
							$val->parent() ? $val->parent()->name : 'Is Parent',
							$val->status(),
							'
								<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
								<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
							'
						];
					}elseif(strlen($val->code) == 11 || strlen($val->code) == 12){
						$response['data'][] = [
							$nomor,
							'<ul style="margin-left:30px;" class="list list-unstyled mb-0"><li>'.$val->code.'</li></ul>',
							'<ul style="margin-left:30px;" class="list list-unstyled mb-0"><li>'.$val->name.'</li></ul>',
							$val->parent() ? $val->parent()->name : 'Is Parent',
							$val->status(),
							'
								<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
								<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
							'
						];
					}elseif(strlen($val->code) == 14 || strlen($val->code) == 15){
						$response['data'][] = [
							$nomor,
							'<ul style="margin-left:45px;" class="list list-unstyled mb-0"><li>'.$val->code.'</li></ul>',
							'<ul style="margin-left:45px;" class="list list-unstyled mb-0"><li>'.$val->name.'</li></ul>',
							$val->parent() ? $val->parent()->name : 'Is Parent',
							$val->status(),
							'
								<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
								<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
							'
						];
					}
				}else{
					$response['data'][] = [
						$nomor,
						$val->code,
						$val->name,
						$val->parent() ? $val->parent()->name : 'Is Parent',
						$val->status(),
						'
							<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
							<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
						'
					];
				}

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
            'code'      => 'required|unique:coas,code',
            'name'      => 'required',
            'parent_id' => 'required',
            'status'    => 'required'
        ], [
            'code.required'      => 'Code cannot be empty.',
            'code.unique'        => 'Code already exists.',
            'name.required'      => 'Name cannot be empty.',
            'parent_id.required' => 'Please select a parent.',
            'status.required'    => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Coa::create([
                'code'      => str_replace(',', '.', $request->code),
                'name'      => $request->name,
                'parent_id' => $request->parent_id,
                'status'    => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Coa())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add accounting coa data');

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

    public function show(Request $request)
    {
        $data = Coa::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            #'code'      => ['required', Rule::unique('coas', 'code')->ignore($id)],
			'code'      => ['required', Rule::unique('brands', 'code')->ignore($id)],
            'name'      => 'required',
            'parent_id' => 'required',
            'status'    => 'required'
        ], [
            'code.required'      => 'Code cannot be empty.',
            'code.unique'        => 'Code already exists.',
            'name.required'      => 'Name cannot be empty.',
            'parent_id.required' => 'Please select a parent.',
            'status.required'    => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Coa::where('id', $id)->update([
                'code'      => str_replace(',', '.', $request->code),
                'name'      => $request->name,
                'parent_id' => $request->parent_id,
                'status'    => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Coa())
                    ->causedBy(session('bo_id'))
                    ->log('Change the accounting coa data');

                $response = [
                    'status'  => 200,
                    'message' => 'Data updated successfully.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data failed to update.'
                ];
            }
        }

        return response()->json($response);
    }

    public function destroy(Request $request) 
    {
        $query = Coa::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Coa())
                ->causedBy(session('bo_id'))
                ->log('Delete the accounting coa data');

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
    
	public function export(Request $request){
		$search = $request->search ? $request->search : '';
		$numrow = $request->numrow;
		$start = $request->start;
		$status = $request->status ? $request->status : '';
		
		return Excel::download(new CoasExport($search, $numrow, $start, $status), 'coas.xlsx');
	}
	
	public function print(Request $request)
    {
		$search = $request->search ? $request->search : '';
		$numrow = $request->numrow;
		$start = $request->start;
		$status = $request->status ? $request->status : '';
		
		$coa = Coa::where('name','like','%'.$search.'%')
				->where('status','like','%'.$status.'%')
				->orderBy('code','asc')
				->offset($start)
				->limit($numrow)
				->get();

		if(!$coa) {
			abort(404);
		}
		
		$pdf = PDF::loadView('admin.pdf.master_data.coa', [
				'coa' => $coa
			],
			[],
			[ 
			  'format' => 'A4-P',
			  'orientation' => 'P'
			]
		);

        return $pdf->stream('coa_report.pdf');
    }
}
