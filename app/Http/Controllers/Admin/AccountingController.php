<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SMB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingAccounting;
use App\Models\SettingAccountingDaily;
use Illuminate\Support\Facades\Validator;

class AccountingController extends Controller {

    public function index(Request $request)
    {
		
		$data = [
			'title'   				=> 'Setting - Accounting',
			'content' 				=> 'admin.setting.accounting'
		];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
            'user_id',
			'branch',
			'month',
			'note',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = SettingAccounting::count();
        
        $query_data = SettingAccounting::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    })->orWhere('note', 'like', "%$search%");
                }  
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = SettingAccounting::where(function($query) use ($search, $request) {
                 if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    })->orWhere('note', 'like', "%$search%");
                }  
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->user->name,
					$val->branch(),
                    date('M Y',strtotime($val->month)),
                    $val->note,
					/* '<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="custom_checkbox_stacked_unchecked" '.($val->status == "1" ? "checked" : "").' onclick="updateStatus('.$val->id.',this)">
						<label class="custom-control-label" for="custom_checkbox_stacked_unchecked"></label>
					</div>',*/
                    $val->id == $val->lastButton() ? '
                        <!-- <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button> -->
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    ' : ''
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

    public function datatableDaily(Request $request) 
    {
        $column = [
            'id',
            'user_id',
			'branch',
			'date',
			'note',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = SettingAccountingDaily::count();
        
        $query_data = SettingAccountingDaily::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    })->orWhere('note', 'like', "%$search%");
                }  
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = SettingAccountingDaily::where(function($query) use ($search, $request) {
                 if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('user', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    })->orWhere('note', 'like', "%$search%");
                }  
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->user->name,
					$val->branch(),
                    date('d/m/Y H:i',strtotime($val->date)),
                    $val->note,
                    $val->id == $val->lastButton() ? '
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroyDaily(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    ' : ''
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
            'branch'    => 'required',
            'month'    	=> 'required',
            'note'		=> 'required',
        ], [
            'branch.required'    => 'Branch cannot be empty.',
            'month.required'     => 'Month cannot be empty.',
            'note.required'      => 'Note cannot be empty.',
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $adabefore = true;
			$double = false;
			
            if($request->temp){
				
				$cek = SettingAccounting::find($request->temp);
				
				if($cek->branch == $request->branch){
					$query = SettingAccounting::find($request->temp)->update([
						'user_id'   	=> session('bo_id'),
						'branch'   		=> $request->branch,
						'month'    		=> $request->month,
						'note'          => $request->note
					]);
				}else{
					
					$monthbefore = date('Y-m',strtotime('-1 months',strtotime($request->month)));
					$data = SettingAccounting::where('branch',$request->branch)->where('month',$monthbefore)->count();
					if($data == 0){
						$adabefore = false;
					}else{
						$data2 = SettingAccounting::where('branch',$request->branch)->where('month',$request->month)->count();
						
						if($data2 > 0){
							$double = true;
						}else{
							$query = SettingAccounting::find($request->temp)->update([
								'user_id'   	=> session('bo_id'),
								'branch'   		=> $request->branch,
								'month'    		=> $request->month,
								'note'          => $request->note
							]);
						}
					}
				}
            }else{
				$cek = SettingAccounting::where('branch',$request->branch)->count();
				
				if($cek > 0){
					$monthbefore = date('Y-m',strtotime('-1 months',strtotime($request->month)));
					$data = SettingAccounting::where('branch',$request->branch)->where('month',$monthbefore)->count();
					if($data == 0){
						$adabefore = false;
					}else{
						$data2 = SettingAccounting::where('branch',$request->branch)->where('month',$request->month)->count();
						
						if($data2 > 0){
							$double = true;
						}else{
							$query = SettingAccounting::create([
								'user_id'   	=> session('bo_id'),
								'branch'   		=> $request->branch,
								'month'    		=> $request->month,
								'note'          => $request->note,
								'status'		=> '1'
							]);
						}
					}
				}else{
					$query = SettingAccounting::create([
						'user_id'   	=> session('bo_id'),
						'branch'   		=> $request->branch,
						'month'    		=> $request->month,
						'note'          => $request->note,
						'status'		=> '1'
					]);
				}
            }

            if(isset($query)) {
				activity()
                    ->performedOn(new SettingAccounting())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit accounting cut off.');
				
                $response = [
                    'status'  => 200,
                    'message' => 'Account cut off successfully saved.'
                ];
			} else if($adabefore == false){
				$response = [
					'status'  => 505,
					'message' => 'Cut off data month '.date('M Y',strtotime($monthbefore)).' is not available, please add it first.'
				];
			} else if($double == true){
				$response = [
					'status'  => 505,
					'message' => 'Cut off data month is already there, choose another month.'
				];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Ups, error.'
                ];
            }
        }

        return response()->json($response);
    }

    public function createDaily(Request $request){
        $validation = Validator::make($request->all(), [
            'branch'    => 'required',
            'date'    	=> 'required',
            'note'		=> 'required',
        ], [
            'branch.required'    => 'Branch cannot be empty.',
            'date.required'     => 'Date cannot be empty.',
            'note.required'      => 'Note cannot be empty.',
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			$double = false;
			
            if($request->temp){
				
				$cek = SettingAccountingDaily::find($request->temp);
				
				if($cek->branch == $request->branch){
					$query = SettingAccountingDaily::find($request->temp)->update([
						'user_id'   	=> session('bo_id'),
						'branch'   		=> $request->branch,
						'date'    		=> $request->date,
						'note'          => $request->note
					]);
				}else{
                    $data2 = SettingAccountingDaily::where('branch',$request->branch)->where('date',$request->date)->count();
                    
                    if($data2 > 0){
                        $double = true;
                    }else{
                        $query = SettingAccountingDaily::find($request->temp)->update([
                            'user_id'   	=> session('bo_id'),
                            'branch'   		=> $request->branch,
                            'date'    		=> $request->date,
                            'note'          => $request->note
                        ]);
                    }
					
				}
            }else{
				$cek = SettingAccountingDaily::where('branch',$request->branch)->count();
				
				if($cek > 0){
                    $data2 = SettingAccountingDaily::where('branch',$request->branch)->where('date',$request->date)->count();
                    
                    if($data2 > 0){
                        $double = true;
                    }else{
                        $query = SettingAccountingDaily::create([
                            'user_id'   	=> session('bo_id'),
                            'branch'   		=> $request->branch,
                            'date'    		=> $request->date,
                            'note'          => $request->note,
                            'status'		=> '1'
                        ]);
                    }
					
				}else{
					$query = SettingAccountingDaily::create([
						'user_id'   	=> session('bo_id'),
						'branch'   		=> $request->branch,
						'date'    		=> $request->date,
						'note'          => $request->note,
						'status'		=> '1'
					]);
				}
            }

            if(isset($query)) {
				activity()
                    ->performedOn(new SettingAccountingDaily())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add / edit accounting cut off.');
				
                $response = [
                    'status'  => 200,
                    'message' => 'Account cut off successfully saved.'
                ];
			}else if($double == true){
				$response = [
					'status'  => 505,
					'message' => 'Cut off data date is already there, choose another date.'
				];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Ups, error.'
                ];
            }
        }

        return response()->json($response);
    }
	
	public function show(Request $request)
    {
        $sa = SettingAccounting::find($request->id);
				
        return response()->json($sa);
    }
    
    public function showDaily(Request $request)
    {
        $setting_accounting_dailies = SettingAccountingDaily::find($request->id);
				
        return response()->json($setting_accounting_dailies);
    }
	
	public function destroy(Request $request) 
    {
        $query = SettingAccounting::find($request->id);
		
        if($query->delete()) {
            activity()
                ->performedOn(new SettingAccounting())
                ->causedBy(session('bo_id'))
                ->log('Delete the accounting cut off project data');

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

    public function destroyDaily(Request $request) 
    {
        $query = SettingAccountingDaily::find($request->id);
		
        if($query->delete()) {
            activity()
                ->performedOn(new SettingAccountingDaily())
                ->causedBy(session('bo_id'))
                ->log('Delete the accounting cut off daily project data');

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
	
	public function updateStatus(Request $request) 
    {
        $query = SettingAccounting::find($request->id)->update([
			'status' => $request->val
		]);
		
        if($query) {
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

    public function getLatestMonth(Request $request){
        $branch = $request->branch ?  $request->branch : '1';

        $query = SettingAccounting::where('branch',  $branch)->orderBy('month', 'DESC')->first();
        
        return response()->json(
            [
                'month' => $query ? date('Y-m',strtotime('+1 months',strtotime($query->month))) : ''
            ]
        );
    }
}