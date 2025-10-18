<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmployeeLoan;
use App\Models\EmployeeLoanPayment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller {
    
    public function index(Request $request)
    {
        $data = [
			'id'		=> $request->id,
			'user'		=> User::find(base64_decode($request->id)),
            'title'   	=> 'Employee Loan',
            'content' 	=> 'admin.hrd.loan'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'date_borrow',
			'interest_type',
			'interest_percent',
			'top',
			'nominal',
			'note'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$employee_id = base64_decode($request->id);

        $total_data = EmployeeLoan::count();
        
        $query_data = EmployeeLoan::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date_borrow', 'like', "%$search%")
						->orWhere('interest_percent', 'like', "%$search%")
						->orWhere('top', 'like', "%$search%")
						->orWhere('nominal', 'like', "%$search%")
						->orWhere('note', 'like', "%$search%");
                    });
                }
            })
			->where('employee_id',$employee_id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = EmployeeLoan::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date_borrow', 'like', "%$search%")
						->orWhere('interest_percent', 'like', "%$search%")
						->orWhere('top', 'like', "%$search%")
						->orWhere('nominal', 'like', "%$search%")
						->orWhere('note', 'like', "%$search%");
                    });
                }
            })
			->where('employee_id',$employee_id)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
                $response['data'][] = [
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    date('d M Y',strtotime($val->date_borrow)),
					$val->interestType(),
					$val->interest_percent.' %',
					$val->top.' times',
					number_format($val->nominal,2,',','.'),
					$val->note,
					$val->percentProgress(),
					'	
						<button type="button" class="btn bg-info btn-sm" data-popup="tooltip" title="Pay" onclick="showPay(' . $val->id . ')"><i class="icon-cash"></i></button>
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
			'date_borrow'  		=> 'required',
			'interest_type'  	=> 'required',
			'interest_percent'	=> 'required',
			'top'				=> 'required',
			'nominal'			=> 'required',
			'note'  			=> 'required',
		], [
			'date_borrow.required'  		=> 'Date cannot be empty.',
			'interest_type.required'  		=> 'Interest type cannot be empty.',
			'interest_percent.required'  	=> 'Interest percent cannot be empty.',
			'top.required'					=> 'Term of payment cannot be empty.',
			'nominal.required'				=> 'Nominal cannot be empty.',
			'note.required'   				=> 'Note cannot be empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = EmployeeLoan::find($request->temp);
				
				$query->update([
					'user_id'	    	=> session('bo_id'),
					'employee_id'		=> base64_decode($request->tempUser),
					'date_borrow'  		=> $request->date_borrow,
					'interest_type'  	=> $request->interest_type,
					'interest_percent'	=> $request->interest_percent,
					'top'				=> $request->top,
					'nominal'			=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'note'  			=> $request->note
				]);
				
			}else{
				$query = EmployeeLoan::create([
					'user_id'	    	=> session('bo_id'),
					'employee_id'		=> base64_decode($request->tempUser),
					'date_borrow'  		=> $request->date_borrow,
					'interest_type'  	=> $request->interest_type,
					'interest_percent'	=> $request->interest_percent,
					'top'				=> $request->top,
					'nominal'			=> str_replace(',','.',str_replace('.','',$request->nominal)),
					'note'  			=> $request->note
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new EmployeeLoan())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit loan employee data by user '.session('bo_name').' to employee '.$query->employee->name);

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
        $data = EmployeeLoan::find($request->id);
		
		$data['nominal'] = number_format($data->nominal,0,',','.');
		
        return response()->json($data);
    }
	
	public function destroy(Request $request) 
    {
        $query = EmployeeLoan::find($request->id);
		
        if($query) {
			if(count($query->employeeLoanPayment) > 0){
				$response = [
					'status'  => 500,
					'message' => 'Data failed to delete, because it is already paid.'
				];
				
				return response()->json($response);
			}
			
			$query->delete();
			
            activity()
                ->performedOn(new EmployeeLoan())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Loan Member data');

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
	
	public function rowDetail(Request $request,$user)
    {
        $data   = EmployeeLoanPayment::where('employee_loan_id', $request->id)->orderBy('id')->get();
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th width="5%">No</th>
							<th>Name</th>
							<th>Date</th>
							<th>Nominal</th>
							<th>Note</th>
						</tr>
					</thead>
					<tbody>';

        foreach($data as $key => $d) {
			$string .= '
				<tr>
					<td class="text-center">'.($key + 1).'.</td>
					<td>'.$d->user->name.'</td>
					<td class="text-center">'.date('d M Y',strtotime($d->date_paid)).'</td>
					<td class="text-right">'.number_format($d->nominal,2,',','.').'</td>
					<td class="text-center">'.$d->note.'</td>
				</tr>
			';
        }

        $string .= '</tbody></table>';
		
        return response()->json($string);
    }
	
	public function destroyPayment(Request $request) 
    {
        $query = EmployeeLoanPayment::find($request->id);
		
        if($query) {
			
			$query->delete();
			
            activity()
                ->performedOn(new EmployeeLoanPayment())
                ->causedBy(session('bo_id'))
				->withProperties($query)
                ->log('Delete the Loan Payment');

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