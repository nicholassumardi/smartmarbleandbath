<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\TypeLeave;
use App\Models\Approval;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LeaveRequestController extends Controller {

    public function user()
    {
        $data = [
            'title'   			=> 'Leave Request User',
			'type'     			=> TypeLeave::where('status',1)->get(),
            'content' 			=> 'admin.leave_request'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function userDatatable(Request $request) 
    {
        $column = [
            'id',
			'type_leave_id',
			'category',
			'note',
            'start_date',
            'start_hour'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = LeaveRequest::count();
        
        $query_data = LeaveRequest::where(function($query) use ($search) {
                if($search) {
                    $query->where('note', 'like', "%$search%");
                }
            })
			->where('user_id',session('bo_id'))
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = LeaveRequest::where(function($query) use ($search) {
                if($search) {
                    $query->where('note', 'like', "%$search%");
                }
            })
			->where('user_id',session('bo_id'))
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				if($val->proof){
					if(explode('.',$val->proof)[1] == 'pdf'){
						$photo = '<a href="' .$val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
					}else{
						$photo = '<a data-magnify="gallery" data-src="" data-caption="'.$val->item.'" data-group="a" href="' .$val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
					}
				}else{
					$photo = '<span class="badge badge-danger">Empty</span>';
				}
				
				if($val->checked_by || $val->approved_by){
					$btn = 'Closed';
				}else{
					$btn = '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    ';
				}
                
                $response['data'][] = [
                    $nomor,
					$val->typeLeave->name,
					$val->category(),
					$val->note,
                    $val->start_date ? '<b class="badge badge-success">'.date('d M Y', strtotime($val->start_date)).'</b> to <b class="badge badge-success">'.date('d M Y', strtotime($val->finish_date)).'</b>' : '<b class="badge badge-danger">None</b>',
                    $val->date_hour ? '<b class="badge badge-success">'.date('d M Y', strtotime($val->date_hour)).' '.$val->start_hour.'</b> - <b class="badge badge-success">'.$val->finish_hour.'</b>' : '<b class="badge badge-danger">None</b>',
					$photo,
					$val->approved ? ($val->reject_approved ? '<b class="badge badge-danger">Rejected</b> by '.$val->approved->name.' with reason : <b>'.$val->reject_approved.'</b>' : '<b class="badge badge-success">Approved</b> by '.$val->approved->name) : 'waiting',
					$val->checked_by ? ($val->reject_checked ? '<b class="badge badge-danger">Rejected</b> by '.$val->checked->name.' with reason : <b>'.$val->reject_checked.'</b>' : '<b class="badge badge-success">Approved</b> by '.$val->checked->name) : 'waiting',
                    $btn
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
	
	public function userAdd(Request $request)
    {
		if($request->category == '1'){
			$validation = Validator::make($request->all(), [
				'start_date'	=> 'required',
				'finish_date'	=> 'required',
				'note' 			=> 'required'
			], [
				'start_date.required'	=> 'Start date cannot be empty.',
				'finish_date.required'	=> 'Finish date cannot be empty.',
				'note.required'			=> 'Note / description cannot be empty.'
			]);
		}elseif($request->category == '2'){
			$validation = Validator::make($request->all(), [
				'date_hour'		=> 'required',
				'start_hour'	=> 'required',
				'finish_hour'	=> 'required',
				'note' 			=> 'required'
			], [
				'date_hour.required'	=> 'Date hour cannot be empty.',
				'start_hour.required'	=> 'Start hour cannot be empty.',
				'finish_hour.required'	=> 'Finish hour cannot be empty.',
				'note.required'			=> 'Note / description cannot be empty.'
			]);
		}
		

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->leave_id){
				$query = LeaveRequest::find($request->leave_id);
				
				if($request->has('proof')) {
					if(Storage::exists($query->proof)) {
						Storage::delete($query->proof);
					}

					$proof = $request->file('proof')->store('public/leave_request');
				} else {
					$proof = $query->proof;
				}
				
				$query->update([
					'user_id'	     		=> session('bo_id'),
					'type_leave_id'			=> $request->type_leave,
					'category'				=> $request->category,
					'note'					=> $request->note,
					'start_date'			=> $request->category == '1' ? ($request->start_date ? $request->start_date : null) : null,
					'finish_date'			=> $request->category == '1' ? ($request->finish_date ? $request->finish_date : null) : null,
					'date_hour'				=> $request->category == '2' ? ($request->date_hour ? $request->date_hour : null) : null,
					'start_hour'			=> $request->category == '2' ? ($request->start_hour ? $request->start_hour : null) : null,
					'finish_hour'			=> $request->category == '2' ? ($request->finish_hour ? $request->finish_hour : null) : null,
					'proof'					=> $proof
				]);
			}else{
				$query = LeaveRequest::create([
					'code'	     			=> strtoupper(Str::random(25)),
					'user_id'	     		=> session('bo_id'),
					'type_leave_id'			=> $request->type_leave,
					'category'				=> $request->category,
					'note'					=> $request->note,
					'start_date'			=> $request->start_date ? $request->start_date : null,
					'finish_date'			=> $request->finish_date ? $request->finish_date : null,
					'date_hour'				=> $request->date_hour ? $request->date_hour : null,
					'start_hour'			=> $request->start_hour ? $request->start_hour : null,
					'finish_hour'			=> $request->finish_hour ? $request->finish_hour : null,
					'proof'					=> $request->has('proof') ? $request->file('proof')->store('public/leave_request') : null
				]);
			}
			
            if($query) {
				
				#send approval
				$roleapproval = 10;
				Approval::sendApproval($roleapproval,'leave_requests',$query->id,'checked_by',session('bo_id'));
				$roleapproval = 27;
				Approval::sendApproval($roleapproval,'leave_requests',$query->id,'approved_by',session('bo_id'));
				#end approval
				
                activity()
                    ->performedOn(new LeaveRequest())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add leave request by user '.session('bo_name'));
				
				#start notif
				$role = array('1','3','14');
				$title = 'New leave request has been created by '.session("bo_name").'!';
				$description = 'New leave request with note '.$request->note.'.';
				$link = '#';
				Notification::sendNotif($role,$title,$description,$link);
				#end notif

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
	
	public function getLeave(Request $request){
		$data = LeaveRequest::find($request->id);
		
        return response()->json($data);
	}
	
	public function userDestroy(Request $request) 
    {
        $query = LeaveRequest::find($request->id);
		
		$query->deleteFile();
		$query->delete();
		
		Approval::where('approvalable_type','leave_requests')->where('approvalable_id',$request->id)->delete();
	
        if($query) {
            activity()
                ->performedOn(new LeaveRequest())
                ->causedBy(session('bo_id'))
                ->log('Delete the Leave request data');

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