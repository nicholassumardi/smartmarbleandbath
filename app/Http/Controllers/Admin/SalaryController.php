<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Allowance;
use App\Models\Salary;
use App\Models\PurchaseRequest;
use App\Models\EmployeeLoan;
use App\Models\EmployeeLoanPayment;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Journal;
use App\Models\SalaryDetail;
use App\Models\EmployeeAllowance;
use App\Models\EmployeeAllowanceDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Jobs\EmailProcess;
use Illuminate\Support\Facades\Mail;
use App\Helper\SendMessage;
use App\Models\Approval;

class SalaryController extends Controller {

    public function index()
    {
		
        $data = [
            'title'   			=> 'Salary',
            'content' 			=> 'admin.hrd.salary'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
			'code',
			'branch',
			'date_generate',
			'month',
			'total_allowance',
			'total_addition',
			'total_cutting',
			'total_loan',
			'grandtotal',
			'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Salary::count();
        
        $query_data = Salary::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
						->orWhere('date_generate', 'like', "%$search%")
						->orWhere('month', 'like', "%$search%")
						->orWhere('total_allowance', 'like', "%$search%")
						->orWhere('total_addition', 'like', "%$search%")
						->orWhere('total_cutting', 'like', "%$search%")
						->orWhere('total_loan', 'like', "%$search%")
						->orWhere('grandtotal', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Salary::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
						->orWhere('date_generate', 'like', "%$search%")
						->orWhere('month', 'like', "%$search%")
						->orWhere('total_allowance', 'like', "%$search%")
						->orWhere('total_addition', 'like', "%$search%")
						->orWhere('total_cutting', 'like', "%$search%")
						->orWhere('total_loan', 'like', "%$search%")
						->orWhere('grandtotal', 'like', "%$search%");
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
                    $val->branch(),
                    date('d M Y',strtotime($val->date_generate)),
					date('M Y',strtotime($val->month)),
					number_format($val->total_allowance,2,',','.'),
					number_format($val->total_addition,2,',','.'),
					number_format($val->total_cutting,2,',','.'),
					number_format($val->total_loan,2,',','.'),
					number_format($val->grandtotal,2,',','.'),
					'
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
	
	public function indexEmployeeAllowance()
    {
		
        $data = [
			'user'				=> User::where('status','1')->get(),
			'allowance'			=> Allowance::all(),
            'title'   			=> 'Employee Allowance',
            'content' 			=> 'admin.hrd.employee_allowance'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatableEmployeeAllowance(Request $request){
		$column = [
            'id',
			'employee_id',
			'start_month',
			'end_month',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = EmployeeAllowance::count();
        
        $query_data = EmployeeAllowance::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('start_month', 'like', "%$search%")
							->orWhere('end_month', 'like', "%$search%")
							->orWhereHas('employee',function($query) use ($search){
								$query->where('name','like',"%$search%");
							});
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = EmployeeAllowance::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('start_month', 'like', "%$search%")
							->orWhere('end_month', 'like', "%$search%")
							->orWhereHas('employee',function($query) use ($search){
								$query->where('name','like',"%$search%");
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
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
					$val->employee->name,
					$val->paymentType(),
					date('M Y',strtotime($val->start_month)),
					date('M Y',strtotime($val->end_month)),
					'
						<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
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
			'payment_type'  		=> 'required',
			'month'  				=> 'required',
			'date_start'  			=> 'required',
			'date_end'  			=> 'required',
			'date_generate'			=> 'required',
			'branch'				=> 'required',
			'arr_id'				=> 'required|array',
			'arr_allowance'			=> 'required|array',
			'arr_addition'			=> 'required|array',
			'arr_cutting'			=> 'required|array',
			'arr_loan'				=> 'required|array',
			'arr_total'				=> 'required|array',
		], [
			'payment_type.required'   		=> 'Type cannot empty.',
			'month.required'     			=> 'Month cannot empty.',
			'date_start.required'     		=> 'Date start cannot empty.',
			'date_end.required'     		=> 'Date end cannot empty.',
			'date_generate.array'			=> 'Allowance must be array.',
			'branch.required'     			=> 'Branch cannot empty.',
			'arr_id.required'     			=> 'ID cannot empty.',
			'arr_id.array'					=> 'ID must be array.',
			'arr_allowance.required'     	=> 'Allowance cannot empty.',
			'arr_allowance.array'			=> 'Allowance must be array.',
			'arr_addition.required'     	=> 'Addition cannot empty.',
			'arr_addition.array'			=> 'Addition must be array.',
			'arr_cutting.required'     		=> 'Cutting cannot empty.',
			'arr_cutting.array'				=> 'Cutting must be array.',
			'arr_loan.required'     		=> 'Loan cannot empty.',
			'arr_loan.array'				=> 'Loan must be array.',
			'arr_total.required'     		=> 'Total cannot empty.',
			'arr_total.array'				=> 'Total must be array.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = Salary::find($request->temp);
				
				$query->update([
					'user_id'	    		=> session('bo_id'),
					'month'					=> $request->month,
					'branch'				=> $request->branch,
					'date_generate'			=> $request->date_generate,
					'date_start'			=> $request->date_start,
					'date_end'				=> $request->date_end,
					'total_allowance'		=> str_replace(',','.',str_replace('.','',$request->total_allowance)),
					'total_addition'		=> str_replace(',','.',str_replace('.','',$request->total_addition)),
					'total_cutting'			=> str_replace(',','.',str_replace('.','',$request->total_cutting)),
					'total_loan'			=> str_replace(',','.',str_replace('.','',$request->total_loan)),
					'grandtotal'			=> str_replace(',','.',str_replace('.','',$request->total_allowance)) + str_replace(',','.',str_replace('.','',$request->total_addition)) - str_replace(',','.',str_replace('.','',$request->total_cutting)) - str_replace(',','.',str_replace('.','',$request->total_loan))
				]);
				
				$sd = SalaryDetail::where('salary_id',$query->id)->get();
				
				foreach($sd as $rowsd){
					$rowsd->deleteEmployeeLoanPayment();
					$rowsd->delete();
				}
				
				$pr = PurchaseRequest::find($query->purchase_request_id);
				
				if($pr){
					$cb = CashBank::where('code','PR-'.$pr->id)->get();
					
					if($cb){
						foreach($cb as $rowcb){
							$rowcb->deleteDetail();
							$rowcb->deleteFile();
							$rowcb->delete();
						}
					}
				}
				
			}else{
				$query = Salary::create([
					'user_id'	    		=> session('bo_id'),
					'code'					=> Salary::generateCode(),
					'month'					=> $request->month,
					'branch'				=> $request->branch,
					'date_generate'			=> $request->date_generate,
					'date_start'			=> $request->date_start,
					'date_end'				=> $request->date_end,
					'total_allowance'		=> str_replace(',','.',str_replace('.','',$request->total_allowance)),
					'total_addition'		=> str_replace(',','.',str_replace('.','',$request->total_addition)),
					'total_cutting'			=> str_replace(',','.',str_replace('.','',$request->total_cutting)),
					'total_loan'			=> str_replace(',','.',str_replace('.','',$request->total_loan)),
					'grandtotal'			=> str_replace(',','.',str_replace('.','',$request->total_allowance)) + str_replace(',','.',str_replace('.','',$request->total_addition)) - str_replace(',','.',str_replace('.','',$request->total_cutting)) - str_replace(',','.',str_replace('.','',$request->total_loan))
				]);
			}
			
			foreach($request->arr_id as $key => $row){
				$querydetail = SalaryDetail::create([
					'salary_id'						=> $query->id,
					'employee_allowance_id'			=> $row,
					'allowance'						=> str_replace(',','.',str_replace('.','',$request->arr_allowance[$key])),
					'addition'						=> str_replace(',','.',str_replace('.','',$request->arr_addition[$key])),
					'cutting'						=> str_replace(',','.',str_replace('.','',$request->arr_cutting[$key])),
					'loan'							=> str_replace(',','.',str_replace('.','',$request->arr_loan[$key])),
					'total'							=> str_replace(',','.',str_replace('.','',$request->arr_total[$key])),
				]);
				
				$payload = [
					'name'    	=> $querydetail->employeeAllowance->user->name,
					'email'   	=> $querydetail->employeeAllowance->user->email,
					'view'    	=> 'salary',
					'subject' 	=> 'SMB Salary Slip | '.date('F Y',strtotime($request->month)),
					'data'		=> $querydetail
				];
				
				if(count(explode('|',$request->arr_employee_loan[$key])) > 0){
					$arr = explode('|',$request->arr_employee_loan[$key]);
					$arrid = explode(',',$arr[0]);
					$arrnominal = explode(',',$arr[1]);
					for($i=0;$i<count($arrid);$i++){
						if($arrid[$i]){
							$queryloan = EmployeeLoan::find($arrid[$i]);
							
							EmployeeLoanPayment::create([
								'user_id'			=> session('bo_id'),
								'employee_loan_id'	=> $queryloan->id,
								'salary_detail_id'	=> $querydetail->id,
								'date_paid'			=> $request->date_generate,
								'nominal'			=> $arrnominal[$i],
								'note'				=> 'From salary slip month '.date('F Y',strtotime($request->month))
							]);
						}
					}
				}
				
				Mail::send('emails.' . $payload['view'], $payload, function($mail) use ($payload) {
					$mail->to($payload['email'], $payload['name']);
					$mail->subject($payload['subject']);
					$mail->from(config('mail.mailers.smtp.username'), 'Smart Marble And Bath');
				});
			}

            if($query) {
				
				$pr = PurchaseRequest::create([
					'date'	     			=> $request->date_generate,
					'bill_to'				=> 'Director/Accounting/Finance',
					'title'					=> 'GAJI BULANAN '.strtoupper($query->branch()).' BLN '.date('F Y',strtotime($request->month)),
					'item'					=> 'PENGAJUAN PEMBAYARAN GAJI BULANAN '.strtoupper($query->branch()).' BLN '.date('F Y',strtotime($request->month)),
					'user_id'				=> session('bo_id'),
					'branch'				=> $query->branch,
					'total_nominal'			=> $query->grandtotal,
					'total_cash_advance'	=> 0,
					'status'				=> 'PEND',
					'image'					=> NULL,
					'term'					=> NULL,
					'supplier_id'			=> 132,
					'term_days'				=> 0,
					'due_date'				=> $request->date_generate
				]);
				
				Salary::find($query->id)->update([
					'purchase_request_id'	=> $pr->id
				]);
				
				#send approval
				$roleapproval = array('4');
				Approval::sendApproval($roleapproval,'purchase_requests',$pr->id,'approved_by',session('bo_id'));
				
				SendMessage::send(env('ACCOUNTING_PHONE'),'Halo pak/bu. Mohon dibantu approve Purchase Request No. PR-'.$pr->id.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
				
                activity()
                    ->performedOn(new Salary())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit employee salary data by user '.session('bo_name'));

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
	
	public function createEmployeeAllowance(Request $request){
		$validation = Validator::make($request->all(), [
			'employee_id'  			=> 'required',
			'start_month'  			=> 'required',
			'end_month'  			=> 'required',
			'allowance'				=> 'required|array',
			'nominal'				=> 'required|array'
		], [
			'employee_id.required'   		=> 'Employee cannot empty.',
			'start_month.required'     		=> 'Start month cannot empty.',
			'end_month.required'     		=> 'End month cannot empty.',
			'allowance.required'     		=> 'Allowance cannot empty.',
			'allowance.array'				=> 'Allowance must be array.',
			'nominal.required'     			=> 'Nominal cannot empty.',
			'nominal.array'					=> 'Nominal must be array.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = EmployeeAllowance::find($request->temp);
				
				$query->update([
					'user_id'	    		=> session('bo_id'),
					'employee_id'			=> $request->employee_id,
					'payment_type'			=> $request->payment_type,
					'start_month'			=> $request->start_month,
					'end_month'				=> $request->end_month,
				]);
				
				EmployeeAllowanceDetail::where('employee_allowance_id',$query->id)->delete();
				
			}else{
				$query = EmployeeAllowance::create([
					'user_id'	    		=> session('bo_id'),
					'employee_id'			=> $request->employee_id,
					'payment_type'			=> $request->payment_type,
					'start_month'			=> $request->start_month,
					'end_month'				=> $request->end_month,
				]);
			}
			
			foreach($request->allowance as $key => $row){
				EmployeeAllowanceDetail::create([
					'employee_allowance_id'	=> $query->id,
					'allowance_id'			=> $row,
					'nominal'				=> str_replace(',','.',str_replace('.','',$request->nominal[$key]))
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new EmployeeAllowance())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit employee allowance data by user '.session('bo_name'));

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
	
	public function rowDetailEmployeeAllowance(Request $request)
    {
        $data   = EmployeeAllowanceDetail::where('employee_allowance_id', $request->id)->orderBy('id')->get();
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th width="5%">No</th>
							<th>Name</th>
							<th>Type</th>
							<th>Nominal</th>
						</tr>
					</thead>
					<tbody>';

        foreach($data as $key => $d) {
			$string .= '
				<tr>
					<td class="text-center">'.($key + 1).'.</td>
					<td>'.$d->allowance->name.'</td>
					<td class="text-center">'.$d->allowance->type().'</td>
					<td class="text-right">'.number_format($d->nominal,2,',','.').'</td>
				</tr>
			';
        }

        $string .= '</tbody></table>';
		
        return response()->json($string);
    }
	
	public function showEmployeeAllowance(Request $request){
		$data = EmployeeAllowance::find($request->id);

		$detail = [];
		foreach($data->employeeAllowanceDetail as $row){
			$row['allowance_name'] = $row->allowance->name;
			$row['allowance_type'] = $row->allowance->type();
			$row['nominal'] = number_format($row->nominal,2,',','.');
			$detail[] = $row;
		}
		
        return response()->json([
			'data'		=> $data,
			'detail'	=> $detail
		]);
	}
	
	public function getEmployeeSalary(Request $request){
		$month = $request->month;
		$date_start = $request->date_start;
		$date_end = $request->date_end;
		$branch = $request->branch;
		$payment = $request->payment;
		
		$employee = User::where('status','1')->where('branch',$branch)->get();
		
		$result = [];
		
		$grandtotal = 0;
		
		foreach($employee as $row){
			$cek = $row->checkPayment($payment,$month);
			if($cek == '1'){
				$totalallowance = $row->getAllowance($month,$date_start,$date_end);
				$totalcutting = $row->getCutting($month,$date_start,$date_end);
				$totalloan = $row->getLoanCredit();
				$arr_loan = $row->getLoanArr();
				
				$total = str_replace(',','.',str_replace('.','',$totalallowance)) - str_replace(',','.',str_replace('.','',$totalcutting)) - str_replace(',','.',str_replace('.','',$totalloan));
				
				$grandtotal += $total;
				
				$result[] = [
					'id'				=> $row->checkPayrollUser($payment,$month)->id,
					'employee'			=> $row->name,
					'allowance'			=> '<a href="javascript:void(0);" onclick="showAllowance('.$row->id.',`'.$month.'`,`'.$date_start.'`,`'.$date_end.'`)">'.$totalallowance.'</a>',
					'value_allowance'	=> $totalallowance,
					'addition'			=> '',
					'cutting'			=> '<a href="javascript:void(0);" onclick="showCutting('.$row->id.',`'.$month.'`,`'.$date_start.'`,`'.$date_end.'`)">'.$totalcutting.'</a>',
					'value_cutting'		=> $totalcutting,
					'loan'				=> '<a href="javascript:void(0);" onclick="showLoanCredit('.$row->id.')">'.$totalloan.'</a>',
					'value_loan'		=> $totalloan,
					'arr_loan'			=> $arr_loan,
					'total'				=> number_format($total,2,',','.')
				];
			}
		}
		
		return response()->json([
			'grandtotal'	=> number_format($grandtotal,2,',','.'),
			'list'			=> $result
		]);
	}
	
	public function show(Request $request){
		$data = Salary::find($request->id);

		$detail = [];
		foreach($data->salaryDetail as $row){
			$row['id'] = $row->employee_allowance_id;
			$row['employee'] = $row->employeeAllowance->employee->name;
			$row['value_allowance']	= number_format($row->allowance,2,",",".");
			$row['allowance'] = '<a href="javascript:void(0);" onclick="showAllowance('.$row->employeeAllowance->employee_id.',`'.$row->salary->month.'`,`'.$row->salary->date_start.'`,`'.$row->salary->date_end.'`)">'.number_format($row->allowance,2,",",".").'</a>';
			$row['addition'] = number_format($row->addition,2,",",".");
			$row['value_cutting'] = number_format($row->cutting,2,",",".");
			$row['cutting'] = '<a href="javascript:void(0);" onclick="showCutting('.$row->employeeAllowance->employee_id.',`'.$row->salary->month.'`,`'.$row->salary->date_start.'`,`'.$row->salary->date_end.'`)">'.number_format($row->cutting,2,",",".").'</a>';
			$row['value_loan'] = number_format($row->loan,2,",",".");
			$row['loan'] = '<a href="javascript:void(0);" onclick="showLoanCredit('.$row->employeeAllowance->employee_id.')">'.number_format($row->loan,2,",",".").'</a>';
			$row['arr_loan'] = $row->arrLoan();
			$row['total'] = number_format($row->total,2,',','.');
			$detail[] = $row;
		}
		
        return response()->json([
			'data'		=> $data,
			'detail'	=> $detail
		]);
	}
	
	public function getInfoAllowance(Request $request){
		$user = $request->user;
		$month = $request->month;
		$branch = User::find($user)->branch;
		$date_start = $request->date_start;
		$date_end = $request->date_end;
		
		
		$content = '<h3>Period '.date('d M Y',strtotime($date_start)).' - '.date("d M Y",strtotime($date_end)).'</h3><table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Name</th>
							<th>Type</th>
							<th>Nominal</th>
							<th>Qty</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>';
		
		
		
		$allowance = EmployeeAllowance::where('employee_id',$user)->where('start_month','<=',"$month")->where('end_month','>=',"$month")->latest()->first();
		
		$total = 0;
		
		if($allowance){
			foreach($allowance->employeeAllowanceDetail as $row){
				$totalrow = $row->getNominal($user,$branch,$month,$date_start,$date_end);
				
				$content .= '
					<tr class="text-center">
						<td>'.$row->allowance->name.'</td>
						<td>'.$row->allowance->type().'</td>
						<td class="text-right">'.number_format($row->nominal,2,',','.').'</td>
						<td>'.$row->getQty($branch,$date_start,$date_end).'</td>
						<td class="text-right">'.number_format($totalrow,2,',','.').'</td>
					</tr>
				';
				$total += $totalrow;
			}
		}
		
		$content .= '<tr class="text-center" style="font-size:20px !important;">
						<td colspan="4" class="text-right">Total</td>
						<td class="text-right"><b>'.number_format($total,2,',','.').'</b></td>
					</tr></tbody></table>';
		
		return response()->json([
			'content'	=> $content
		]);
	}
	
	public function getInfoCutting(Request $request){
		$user = $request->user;
		$month = $request->month;
		$branch = User::find($user)->branch;
		$date_start = $request->date_start;
		$date_end = $request->date_end;
		
		
		$content = '<h3>Period '.date('d M Y',strtotime($date_start)).' - '.date("d M Y",strtotime($date_end)).'</h3><table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Name</th>
							<th>Type</th>
							<th>Nominal</th>
							<th>Qty</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>';
		
		
		
		$allowance = EmployeeAllowance::where('employee_id',$user)->where('start_month','<=',"$month")->where('end_month','>=',"$month")->latest()->first();
		
		$total = 0;
		
		if($allowance){
			foreach($allowance->employeeAllowanceDetail as $row){
				$totalrow = $row->getNominalCutting($user,$branch,$month,$date_start,$date_end);
				
				$content .= '
					<tr class="text-center">
						<td>'.$row->allowance->name.'</td>
						<td>'.$row->allowance->type().'</td>
						<td class="text-right">'.number_format($row->nominal,2,',','.').'</td>
						<td>'.$row->getQtyCutting($user,$branch,$date_start,$date_end).'</td>
						<td class="text-right">'.number_format($totalrow,2,',','.').'</td>
					</tr>
				';
				$total += $totalrow;
			}
		}
		
		$content .= '<tr class="text-center" style="font-size:20px !important;">
						<td colspan="4" class="text-right">Total</td>
						<td class="text-right"><b>'.number_format($total,2,',','.').'</b></td>
					</tr></tbody></table>';
		
		return response()->json([
			'content'	=> $content
		]);
	}
	
	public function getInfoLoanCredit(Request $request){
		$content = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No</th>
							<th>Date Borrow</th>
							<th>Total Borrow</th>
							<th>Credits</th>
							<th>Must Be Paid</th>
						</tr>
					</thead>
					<tbody>';
		
		$data = EmployeeLoan::where('employee_id',$request->user)->get();
		
		$no = 1;
		$total = 0;
		foreach($data as $row){
			if($row->balance() > 0){
				$content .= '
					<tr class="text-center">
						<td>'.$no.'</td>
						<td>'.date('d M Y',strtotime($row->date_borrow)).'</td>
						<td class="text-right">'.number_format($row->nominal,0,',','.').'</td>
						<td>'.$row->payment().' / '.$row->top.'</td>
						<td class="text-right">'.number_format(round($row->mustPay()),0,',','.').'</td>
					</tr>
				';
				$total += round($row->mustPay());
				$no++;
			}
		}
		
		$content .= '<tr class="text-center" style="font-size:20px !important;">
						<td colspan="4" class="text-right">Total</td>
						<td class="text-right"><b>'.number_format($total,0,',','.').'</b></td>
					</tr></tbody></table>';
		
		return response()->json([
			'content'	=> $content
		]);
	}
}