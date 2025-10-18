<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coa;
use App\Models\Budgeting;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;

class BudgetingController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'Budgeting',
			'project' => Project::all(),
            'coa'     => Coa::oldest('code')->get(),
            'content' => 'admin.accounting.budgeting'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {

		$branch = $request->branch ? "branch = '".$request->branch."'" : "";

        $total_data = Budgeting::count();
        
		if($branch){
			$query_data = Budgeting::select("branch", "month", DB::raw("sum(nominal) as total"))
							->whereRaw($branch)
							->groupBy('month')
							->orderBy('month')
							->get();
			
			$total_filtered = Budgeting::select("branch", "month", DB::raw("sum(nominal) as total"))
							->whereRaw($branch)
							->groupBy('month')
							->orderBy('month')
							->get()
							->count();
		}else{
			$query_data = Budgeting::select("branch", "month", DB::raw("sum(nominal) as total"))
							->groupBy('month')
							->orderBy('month')
							->get();
			
			$total_filtered = Budgeting::select("branch", "month", DB::raw("sum(nominal) as total"))
							->groupBy('month')
							->orderBy('month')
							->get()
							->count();
		}
		
		$start = 0;
		
        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-month="' . $val->month . '" data-branch="' . $val->branch . '"><i class="icon-plus3"></i></span>',
                    $nomor,
					$val->branch(),
                    date('F Y', strtotime($val->month)),
                    number_format($val->total, 2, ',', '.'),
                    '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(`' . $val->month . '`,' . $val->branch . ')"><i class="icon-pencil7"></i></button>
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

	 public function yearly(Request $request)
    {
        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'year'      => 'required',
                'coa_id.*'    => 'required',
                'month.*'   => 'required',
                'nominal.*' => 'required'
            ], [
                'year.required'      => 'Year cannot be empty.',
                'coa_id.*.required'    => 'Coa must be filled all.',
                'month.*.required'   => 'Month must be filled all.',
                'nominal.*.required' => 'Nominal must be filled all.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                foreach($request->month as $key => $m) {
					$query = Budgeting::where('coa_id',$request->coa_id[$key])->where('month',date('Y-m', strtotime($request->year . '-' . $m)))->where('branch',$request->branch)->first();
					
					if($query !== null){
						$query->update([
							'nominal' => str_replace(',','.',str_replace('.','',$request->nominal[$key]))
						]);
					}else{
						$query = Budgeting::create([
							'coa_id'  => $request->coa_id[$key],
							'branch'  => $request->branch,
							'month'   => date('Y-m', strtotime($request->year . '-' . $m)),
							'nominal' => str_replace(',','.',str_replace('.','',$request->nominal[$key]))
						]);
					}

                    activity()
                        ->performedOn(new Budgeting())
                        ->causedBy(session('bo_id'))
                        ->withProperties($query)
                        ->log('Add yearly accounting budgeting data');
                }

                return redirect()->back()->with(['success' => 'Data added successfully.']);
            }
        } else {
            $data = [
                'title'   => 'Create New Budgeting',
                'coa'     => Coa::oldest('code')->get(),
                'content' => 'admin.accounting.budgeting_yearly'
            ];

            return view('admin.layouts.index', ['data' => $data]);
        }
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'coa_id.*'		=> 'required',
			'branch'		=> 'required',
            'startmonth'   	=> 'required',
			'endmonth'   	=> 'required',
            'nominal.*' 	=> 'required'
        ], [
            'coa_id.*.required'    	=> 'Coa must be filled all.',
			'branch.required'    	=> 'Branch cannot be empty.',
            'startmonth.required'   => 'Start month cannot be a empty.',
			'endmonth.required'   	=> 'End month cannot be a empty.',
            'nominal.*.required' 	=> 'Nominal must be filled all.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$branch = $request->branch;
			
			$startmonth = $request->startmonth;
			$endmonth = $request->endmonth;
			
			$month = strtotime($startmonth);
			$end = strtotime($endmonth);
			while($month <= $end)
			{
				foreach($request->coa_id as $key => $m) {
					$find = Budgeting::where('month',date('Y-m', $month))->where('branch',$branch)->where('coa_id',$request->coa_id[$key])->first();
					
					if($find){
						$query = $find->update([
							'project_id'	=> $request->project_id,
							'coa_id'  		=> $request->coa_id[$key],
							'branch'  		=> $branch,
							'month'   		=> date('Y-m', $month),
							'nominal' 		=> str_replace(',','.',str_replace('.','',$request->nominal[$key]))
						]);
					}else{
						$query = Budgeting::create([
							'project_id'	=> $request->project_id,
							'coa_id'  		=> $request->coa_id[$key],
							'branch'  		=> $branch,
							'month'   		=> date('Y-m', $month),
							'nominal'		=> str_replace(',','.',str_replace('.','',$request->nominal[$key]))
						]);
					}
					
					
					
					activity()
						->performedOn(new Budgeting())
						->causedBy(session('bo_id'))
						->withProperties($query)
						->log('Add accounting budgeting data');
				}
				$month = strtotime("+1 month", $month);
			}

			$response = [
				'status'  => 200,
				'message' => 'Data added successfully.'
			];
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $month = $request->month;
		$branch = $request->branch;
		
		$main = Budgeting::where('month',$month)->where('branch',$branch)->get();
		
		$rowdetail = [];
		
		foreach($main as $row){
			$rowdetail[] = [
				'coa_id'	=> $row->coa_id,
				'coa_name'	=> $row->coa->name,
				'coa_code'	=> $row->coa->code,
				'nominal'	=> number_format($row->nominal,0,',','.')
			];
		}
		
		$data = [
			'month' 	=> $month,
			'branch'	=> $branch,
			'data'		=> $rowdetail
		];
		
        return response()->json($data);
    }

    /* public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'coa_id'  		=> 'required',
			'branch_detail' => 'required',
            'monthdetail'   => 'required',
            'nominaldetail' => 'required'
        ], [
            'coa_id.required'  			=> 'Please select a coa.',
			'branch_detail.required'  	=> 'Please select a branch.',
            'monthdetail.required'  	=> 'Month cannot be a empty.',
            'nominaldetail.required' 	=> 'Nominal cannot be a empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Budgeting::where('id', $id)->update([
				'project_id' 	=> $request->project_id_detail,
                'coa_id'  		=> $request->coa_id,
				'branch'  		=> $request->branch_detail,
                'month'   		=> $request->monthdetail,
                'nominal' 		=> str_replace(',','.',str_replace('.','',$request->nominaldetail))
            ]);

            if($query) {
                activity()
                    ->performedOn(new Budgeting())
                    ->causedBy(session('bo_id'))
                    ->log('Change the accounting budgeting data');

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
    } */

    public function destroy(Request $request) 
    {
        $query = Budgeting::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Budgeting())
                ->causedBy(session('bo_id'))
                ->log('Delete the accounting budgeting data');

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
	
    public function rowDetail(Request $request)
    {
		$month = $request->month;
		$branch = $request->branch;
		
        $data   = Budgeting::where('branch',$branch)->where('month',$month)->get();
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>COA</th>
							<th>Branch</th>
							<th>Month</th>
							<th>Nominal</th>
						</tr>
					</thead>
					<tbody>';
		foreach($data as $row){
			$string .= '
					<tr>
						<td>'.$row->coa->name.'</td>
						<td>'.$row->branch().'</td>
						<td>'.date('F Y', strtotime($row->month)).'</td>
						<td class="text-right">'.number_format($row->nominal,2,',','.').'</td>
					</tr>
			';
		}
		
		$string .= '</tbody></table>';
		
		return response()->json($string);
	}
}
