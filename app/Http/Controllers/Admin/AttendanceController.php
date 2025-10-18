<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Cookie;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Holiday;
use App\Models\AttendanceCode;
use App\Models\LeaveRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helper\SendMessage;
use App\Models\Approval;

class AttendanceController extends Controller {

    public function index()
    {
		$getdayoff = Schedule::where('branch',session('bo_branch'))->pluck('day')->toArray();
		
		$arrdayoff = [];
		for($i=1;$i<=7;$i++){
			if(!in_array(strval($i),$getdayoff)){
				$arrdayoff[] = $i == 7 ? 0 : $i;
			}
		}
		
		$workday = 0;
		$arrworkday = [];
		
		$thisyear = date('Y');
		$getholiday = Holiday::where('date','like',"$thisyear%")->where('branch',session('bo_branch'))->get();
		$exceptholiday = Holiday::where('date','like',"$thisyear%")->where('branch',session('bo_branch'))->pluck('date')->toArray();
		$totalattendance = Attendance::where('date','like',"$thisyear%")->where('user_id',session('bo_id'))->whereNotIn('date',$exceptholiday)->count();
		
		for($i=1;$i<=12;$i++){
			$month = str_pad($i, 2, '0', STR_PAD_LEFT);
			for($j=1;$j<=date('t',strtotime(date('Y').'-'.$month));$j++){
				$date = date('Y-'.$month.'-'.str_pad($j, 2, '0', STR_PAD_LEFT));
				$inholiday = false;
				$inweekend = false;
				
				if(in_array($date,$exceptholiday)){
					$inholiday = true;
				}
				
				if(in_array(date('w',strtotime($date)),$arrdayoff)){
					$inweekend = true;
				}
				
				if($inholiday == false && $inweekend == false){
					$workday++;
					$arrworkday[] = $date;
				}
			}
		}
		
        $data = [
			'title'   		=> 'Attendance',
			'arrdayoff'		=> $arrdayoff,
			'arrholiday'	=> $getholiday,
			'countAtt'		=> $totalattendance,
			'countWork'		=> $workday,
			'arrworkday'	=> $arrworkday,
			'content' 		=> 'admin.auth.attendance'
		];

		return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function scan(Request $request){
		$content = $request->content;
		
		$data = AttendanceCode::where('code',$content)->first();
		$datalate = Schedule::where('branch',session('bo_branch'))->where('day',date('N'))->first();
		
		$already_logged_in = false;
		
		if($data){
			$cek = Attendance::where('date',date('Y-m-d'))->where('user_id',session('bo_id'))->first();
			
			$arrcek = explode('_',$content);
			
			$folderPath = Storage::path('public/individual_attendance/');
			$image_parts = explode(";base64,", $request->image);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			
			if(count($arrcek) > 2){
				if($cek){
					if($arrcek[2] == 'IN'){
						if($cek->leave_in_code || $cek->leave_out_code == NULL){
							$already_logged_in = true;
						}else{
							$cek->update([
								'leave_in_code'	=> $content,
								'leave_in_time'	=> date('H:i:s'),
							]);
							
							$response = [
								'status'  	=> 200,
								'message' 	=> 'leave request in'
							];
						}
					}elseif($arrcek[2] == 'OUT'){
						if($cek->leave_out_code){
							$already_logged_in = true;
						}else{
							$cek->update([
								'leave_out_code'	=> $content,
								'leave_out_time'	=> date('H:i:s'),
							]);
							
							$response = [
								'status'  	=> 200,
								'message' 	=> 'checked out'
							];
						}
					}
				}else{
					if($arrcek[2] == 'IN'){
						$query = Attendance::create([
							'user_id'		=> session('bo_id'),
							'date'			=> date('Y-m-d'),
							'leave_in_code'	=> $content,
							'leave_in_time'	=> date('H:i:s'),
						]);
						
						$response = [
							'status'  	=> 200,
							'message' 	=> 'checked in'
						];
					}elseif($arrcek[2] == 'OUT'){
						$query = Attendance::create([
							'user_id'			=> session('bo_id'),
							'date'				=> date('Y-m-d'),
							'leave_out_code'	=> $content,
							'leave_out_time'	=> date('H:i:s'),
						]);
						$response = [
							'status'  	=> 200,
							'message' 	=> 'checked out'
						];
					}
				}
				
				if($already_logged_in == true){
					$response = [
						'status'  	=> 300,
						'code' 		=> 'Sorry already leaver request out / in.'
					];
				}
			}else{
				if($cek){
					if(date('H') < 12){
						if($cek->in_code){
							$already_logged_in = true;
						}else{
							$newname = session('bo_id').'_'.$content.'.'.$image_type;
							$file = $folderPath.$newname;
							$this->compressImage($request->image,$file,$image_type,25);
							
							$cek->update([
								'in_image'	=> 'public/individual_attendance/'.$newname,
								'in_code'	=> $content,
								'in_time'	=> date('H:i:s'),
								'in_rule'	=> $datalate ? $datalate->in_time.':00' : '00:00:00'
							]);
							
							$response = [
								'status'  	=> 200,
								'message' 	=> 'checked in'
							];
						}
					}else{
						if($cek->out_code){
							$already_logged_in = true;
						}else{
							$newname = session('bo_id').'_'.$content.'.'.$image_type;
							$file = $folderPath.$newname;
							$this->compressImage($request->image,$file,$image_type,25);
							
							$cek->update([
								'out_image'	=> 'public/individual_attendance/'.$newname,
								'out_code'	=> $content,
								'out_time'	=> date('H:i:s'),
								'out_rule'	=> $datalate ? $datalate->out_time.':00' : '00:00:00'
							]);
							
							$response = [
								'status'  	=> 200,
								'message' 	=> 'checked out'
							];
						}
					}
				}else{
					if(date('H') < 12){
						$newname = session('bo_id').'_'.$content.'.'.$image_type;
						$file = $folderPath.$newname;
						file_put_contents($file, $image_base64);
						
						$query = Attendance::create([
							'user_id'	=> session('bo_id'),
							'date'		=> date('Y-m-d'),
							'in_code'	=> $content,
							'in_time'	=> date('H:i:s'),
							'in_rule'	=> $datalate ? $datalate->in_time.':00' : '00:00:00',
							'in_image'	=> 'public/individual_attendance/'.$newname
						]);
						
						$response = [
							'status'  	=> 200,
							'message' 	=> 'checked in'
						];
					}else{
						$newname = session('bo_id').'_'.$content.'.'.$image_type;
						$file = $folderPath.$newname;
						file_put_contents($file, $image_base64);
						
						$query = Attendance::create([
							'user_id'	=> session('bo_id'),
							'date'		=> date('Y-m-d'),
							'out_code'	=> $content,
							'out_time'	=> date('H:i:s'),
							'out_rule'	=> $datalate ? $datalate->out_time.':00' : '00:00:00',
							'out_image'	=> 'public/individual_attendance/'.$newname,
						]);
						$response = [
							'status'  	=> 200,
							'message' 	=> 'checked out'
						];
					}
				}
				
				if($already_logged_in == true){
					$response = [
						'status'  	=> 300,
						'code' 		=> 'Sorry already checked in / out.'
					];
				}
			}
			
			
		}else{
			$response = [
				'status'  	=> 400,
				'code' 		=> 'Sorry the code is not found.'
			];
		}

        return response()->json($response);
	}
	
	public function datatable(Request $request){
		$column = [
            'id',
			'date',
			'in_time',
			'out_time'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
		$user_id = session('bo_id');

        $total_data = Attendance::count();
        
        $query_data = Attendance::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
						->orWhere('in_time', 'like', "%$search%")
						->orWhere('out_time', 'like', "%$search%");
                    });
                }
            })
			->where('user_id',$user_id)
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Attendance::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
						->orWhere('in_time', 'like', "%$search%")
						->orWhere('out_time', 'like', "%$search%");
                    });
                }
            })
			->where('user_id',$user_id)
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$inNote = '';
				$outNote = '';
				
				if($val->in_note){
					$inNote .= '<br>'.$val->in_note;
					
					if($val->in_approved_by){
						$inNote .= '<br><span class="badge badge-success">Approved By '.$val->inApprove->name.'</span>';
					}else{
						$inNote .= '<br><span class="badge badge-warning">Waiting Approval</span>';
					}
				}
				
				if($val->out_note){
					$outNote .= '<br>'.$val->out_note;
					
					if($val->out_approved_by){
						$outNote .= '<br><span class="badge badge-success">Approved By '.$val->outApprove->name.'</span>';
					}else{
						$outNote .= '<br><span class="badge badge-warning">Waiting Approval</span>';
					} 
				}
				
                $response['data'][] = [
                    $nomor,
                    date('d M Y',strtotime($val->date)),
                    $val->in_time ? $val->in_time.' ('.$val->getMinLateIn().' min late)' : 'Empty',
					($val->in_image ? '<a data-magnify="gallery" data-src="" data-caption="'.$val->in_code.'" data-group="a" href="' .$val->inImage() . '"><img src="' . $val->inImage() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>'.($val->in_latitude && $val->in_longitude ? '<br><a href="https://maps.google.com/maps?q='. $val->in_latitude .','. $val->in_longitude .'" class="btn btn-info btn-sm" target="_blank">See location</a>' : '') : '-').$inNote,
					$val->out_time ? $val->out_time.' ('.$val->getMinFastOut().' min faster)' : 'Empty',
					($val->out_image ? '<a data-magnify="gallery" data-src="" data-caption="'.$val->out_code.'" data-group="a" href="' .$val->outImage() . '"><img src="' . $val->outImage() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>'.($val->out_latitude && $val->out_longitude ? '<br><a href="https://maps.google.com/maps?q='. $val->out_latitude .','. $val->out_longitude .'" class="btn btn-info btn-sm" target="_blank">See location</a>' : '') : '-').$outNote,
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
			'employee_attendance' 		=> 'required',
			'date_attendance' 			=> 'required',
			'in_time_attendance'		=> 'required',
			'out_time_attendance'		=> 'required',
		], [
			'employee_attendance.required'	=> 'Employee cannot empty.',
			'date_attendance.required'		=> 'Date cannot empty.',
			'in_time_attendance.required'	=> 'In time cannot empty.',
			'out_time_attendance.required'	=> 'Out time cannot empty.',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			$code = strtoupper(Str::random(15).'_'.strtotime(date('Y-m-d H:i:s'))).'_BY_HRD';
		
			foreach($request->employee_attendance as $key => $row){
				$user = User::find($row);
				$cek = Attendance::where('date',$request->date_attendance[$key])->where('user_id',$row)->first();
				$datalate = Schedule::where('branch',$user->branch)->where('day',date('N',strtotime($request->date_attendance[$key])))->first();
				
				if($cek){
					if($request->in_time_attendance[$key]){
						Attendance::find($cek->id)->update([
							'in_code'	=> $code,
							'in_time' 	=> $request->in_time_attendance[$key].':00',
							'in_rule'	=> $datalate ? $datalate->in_time.':00' : '00:00:00'
						]);
					}
					
					if($request->out_time_attendance[$key]){
						Attendance::find($cek->id)->update([
							'out_code'	=> $code,
							'out_time' 	=> $request->out_time_attendance[$key].':00',
							'out_rule'	=> $datalate ? $datalate->out_time.':00' : '00:00:00'
						]);
					}
				}else{
					if($request->in_time_attendance[$key]){
						Attendance::create([
							'user_id'	=> $row,
							'date'		=> $request->date_attendance[$key],
							'in_code'	=> $code,
							'in_time'	=> $request->in_time_attendance[$key].':00',
							'in_rule'	=> $datalate ? $datalate->in_time.':00' : '00:00:00',
						]);
					}
					
					if($request->out_time_attendance[$key]){
						$cek2 = Attendance::where('date',$request->date_attendance[$key])->where('user_id',$row)->first();
						
						if($cek2){
							$cek2->update([
								'out_code'	=> $code,
								'out_time' 	=> $request->out_time_attendance[$key].':00',
								'out_rule'	=> $datalate ? $datalate->out_time.':00' : '00:00:00'
							]);
						}else{
							Attendance::create([
								'user_id'	=> $row,
								'date'		=> $request->date_attendance[$key],
								'out_code'	=> $code,
								'out_time' 	=> $request->out_time_attendance[$key].':00',
								'out_rule'	=> $datalate ? $datalate->out_time.':00' : '00:00:00'
							]);
						}
					}
				}
			}
			
			$response = [
				'status'  	=> 200,
				'message' 	=> 'Successfully changed.'
			];
		}
		
        return response()->json($response);
	}
	
	public function createRule(Request $request){
		
		$validation = Validator::make($request->all(), [
			'type' 			=> 'required',
			'date_from' 	=> 'required',
			'date_to'		=> 'required',
			'time'			=> 'required',
			'branch'		=> 'required',
		], [
			'type.required'			=> 'Type cannot empty.',
			'date_from.required'	=> 'Date from cannot empty.',
			'date_to.required'		=> 'Date to cannot empty.',
			'time.required'			=> 'Rule time cannot empty.',
			'branch.required'		=> 'Branch cannot empty.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			$type = $request->type;
			$date_from = $request->date_from;
			$date_to = $request->date_to;
			$time = $request->time;
			$branch = $request->branch;
			
			$data = Attendance::whereHas('user', function($query) use($branch){
				$query->where('branch',$branch);
			})->whereRaw("date BETWEEN '$date_from' AND '$date_to'")->get();
			
			foreach($data as $row){
				if($type == 'IN'){
					$row->update([
						'in_rule'	=> $time.':00'
					]);
				}elseif($type == 'OUT'){
					$row->update([
						'out_rule'	=> $time.':00'
					]);
				}
			}
			
			$response = [
				'status'  	=> 200,
				'message' 	=> 'Successfully change rule time : '.$type
			];
		}
		
        return response()->json($response);
	}
	
	public function getAttendance(Request $request){
		$thisyear = date('Y');
		
		$getdayoff = Schedule::where('branch',session('bo_branch'))->pluck('day')->toArray();
		
		$arrdayoff = [];
		for($i=1;$i<=7;$i++){
			if(!in_array(strval($i),$getdayoff)){
				$arrdayoff[] = $i == 7 ? 0 : $i;
			}
		}
		
		$exceptholiday = Holiday::where('date','like',"$thisyear%")->where('branch',session('bo_branch'))->pluck('date')->toArray();
		
		$arrleave = [];
		$getleaverequest = [];
		
		$dataleave = LeaveRequest::where('user_id',session('bo_id'))->where('category','1')->whereNotNull('approved_by')->whereNotNull('checked_by')->get();
		
		foreach($dataleave as $rowleave){
			$startDate = new Carbon($rowleave->start_date);
			$endDate = new Carbon($rowleave->finish_date);
			while ($startDate->lte($endDate)){
				$arrleave[] = $startDate->toDateString();
				
				if(substr($startDate->toDateString(),0,4) == $thisyear && !in_array($startDate->toDateString(),$exceptholiday) && !in_array(date('w',strtotime($startDate->toDateString())),$arrdayoff)){
					$getleaverequest[] = [
						'description' 	=> $rowleave->note,
						'date'			=> $startDate->toDateString()
					];
				}
				
				$startDate->addDay();
			}
		}
		
		$arrWorkDay = [];
		
		for($i=1;$i<=date('n');$i++){
			$arr = $this->getWorkDay($thisyear.'-'.str_pad($i, 2, '0', STR_PAD_LEFT),session('bo_branch'));
			foreach($arr as $row){
				if($row <= date('Y-m-d')){
					$arrWorkDay[] = $row;
				}
 			}
		}
		
		$getattendance = Attendance::where('date','like',"$thisyear%")->where('user_id',session('bo_id'))->whereNotIn('date',$exceptholiday)->whereNotIn('date',$arrleave)->get();
		
		$arrattendance = [];
		
		foreach($getattendance as $row){
			if(!in_array(date('w',strtotime($row->date)),$arrdayoff)){
				
				$index = array_search($row->date, $arrWorkDay);
				
				if($index >= 0){
					array_splice($arrWorkDay, $index, 1);
				}
				
				$arrattendance[] = [
					'date'		=> $row->date,
					'in_time'	=> $row->in_time,
					'out_time'	=> $row->out_time,
					'in_late'	=> $row->getMinLateIn().' min late.',
					'out_faster'=> $row->getMinFastOut().' min faster.'
				];
			}
		}
		
		$data = [
			'attendance' 	=> $arrattendance,
			'leave_request'	=> $getleaverequest,
			'countAtt'		=> count($getattendance),
			'arrWorkDay' 	=> $arrWorkDay
		];
		
        return response()->json($data);
	}
	
	public function hrdIndex()
    {
        $data = [
			'title'   		=> 'Attendance',
			'user'			=> User::where('status','1')->get(),
			'content' 		=> 'admin.hrd.attendance'
		];

		return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function hrdDatatable(Request $request){
		$column = [
			'id',
            'id',
			'user_id',
			'date',
			'in_time',
			'in_code',
			'out_time',
			'out_code'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Attendance::count();
        
        $query_data = Attendance::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
						->orWhere('in_time', 'like', "%$search%")
						->orWhere('out_time', 'like', "%$search%");
                    })->orWhereHas('user',function($query) use($search){
						$query->where('name','like',"%$search%");
					});
                }
				
				if($request->month){
					$query->where('date','like',"$request->month%");
				}
				
				if($request->user_id){
					$query->where('user_id',$request->user_id);
				}
				
				if($request->branch){
					$query->whereHas('user',function($query) use ($request){
						$query->where('branch',$request->branch);
					});
				}
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Attendance::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('date', 'like', "%$search%")
						->orWhere('in_time', 'like', "%$search%")
						->orWhere('out_time', 'like', "%$search%");
                    })->orWhereHas('user',function($query) use($search){
						$query->where('name','like',"%$search%");
					});
                }
				
				if($request->month){
					$query->where('date','like',"$request->month%");
				}
				
				if($request->user_id){
					$query->where('user_id',$request->user_id);
				}
				
				if($request->branch){
					$query->whereHas('user',function($query) use ($request){
						$query->where('branch',$request->branch);
					});
				}
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$inNote = $val->in_note ? $val->in_note : '';
				$outNote = $val->out_note ? $val->out_note : '';
				
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $nomor,
					$val->user->name,
                    date('d M Y',strtotime($val->date)),
                    $val->in_time.' ('.$val->getMinLateIn().' min late)',
					$val->in_code,
					($val->in_image ? '<a data-magnify="gallery" data-src="" data-caption="'.$val->in_code.'" data-group="a" href="' .$val->inImage() . '"><img src="' . $val->inImage() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>'.($val->in_latitude && $val->in_longitude ? '<br><a href="https://maps.google.com/maps?q='. $val->in_latitude .','. $val->in_longitude .'" class="btn btn-info btn-sm" target="_blank">See location</a>' : '') : '-').$inNote,
					$val->out_time.' ('.$val->getMinFastOut().' min faster)',
					$val->out_code,
					($val->out_image ? '<a data-magnify="gallery" data-src="" data-caption="'.$val->out_code.'" data-group="a" href="' .$val->outImage() . '"><img src="' . $val->outImage() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>'.($val->out_latitude && $val->out_longitude ? '<br><a href="https://maps.google.com/maps?q='. $val->out_latitude .','. $val->out_longitude .'" class="btn btn-info btn-sm" target="_blank">See location</a>' : '') : '-').$outNote,
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
	
	public function hrdRowDetail(Request $request)
    {
        $data   = Attendance::find($request->id);
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Leave Request Out</th>
							<th>Leave Request In</th>
							<th>Difference (Hour)</th>
						</tr>
					</thead>
					<tbody>';

		$leaveOutTime = $data->leave_out_time ? $data->leave_out_time : '';
		$leaveInTime = $data->leave_in_time ? $data->leave_in_time : '';
		$difference = '-';
		
		if($leaveOutTime && $leaveInTime){
			$difference = round((strtotime($leaveInTime) - strtotime($leaveOutTime)) / 3600,2);
		}

		$string .= '
			<tr>
				<td class="text-center">'.($leaveOutTime ? $leaveOutTime : '-').'</td>
				<td class="text-center">'.($leaveInTime ? $leaveInTime : '-').'</td>
				<td class="text-center">'.$difference.'</td>
			</tr>
		';

        $string .= '</tbody></table>';
		
        return response()->json($string);
    }
	
	function getWorkDay($month,$branch){
		$arrworkday = [];
		
		$getdayoff = Schedule::where('branch',$branch)->pluck('day')->toArray();
		
		$arrdayoff = [];
		for($i=1;$i<=7;$i++){
			if(!in_array(strval($i),$getdayoff)){
				$arrdayoff[] = $i == 7 ? 0 : $i;
			}
		}
		
		$thismonth = $month;
		$exceptholiday = Holiday::where('date','like',"$thismonth%")->where('branch',$branch)->pluck('date')->toArray();
		
		for($j=1;$j<=date('t',strtotime($thismonth));$j++){
			$date = $thismonth.'-'.str_pad($j, 2, '0', STR_PAD_LEFT);
			$inholiday = false;
			$inweekend = false;
			
			if(in_array($date,$exceptholiday)){
				$inholiday = true;
			}
			
			if(in_array(date('w',strtotime($date)),$arrdayoff)){
				$inweekend = true;
			}
			
			if($inholiday == false && $inweekend == false){
				$arrworkday[] = $date;
			}
		}
		
		return $arrworkday;
	}
	
	public function print(Request $request)
    {
		$mode = $request->mode;
		
		if($mode == 'monthbranch'){
			$month = $request->month ? $request->month : date('Y-m');
			$branch = $request->branch ? $request->branch : '1';
			$title = 'Attendance Report Month : '.date('F Y',strtotime($month)).' - Branch : '.($branch == '1' ? 'PTA' : 'SMB');
			$arrWorkDay = $this->getWorkDay($month,$branch);
			$listEmployee = User::where('branch',$branch)->where('status','1')->get();
			$result = [
				'month'			=> $month,
				'arrWorkDay' 	=> $arrWorkDay,
				'listEmployee'	=> $listEmployee
			];
			
		}elseif($mode == 'monthemployee'){
			$user = $request->user_id;
			$employee = User::find($user);
			$month = $request->month ? $request->month : date('Y-m');
			$branch = $employee->branch;
			
			$getdayoff = Schedule::where('branch',$employee->branch)->pluck('day')->toArray();
		
			$arrdayoff = [];
			for($i=1;$i<=7;$i++){
				if(!in_array(strval($i),$getdayoff)){
					$arrdayoff[] = $i == 7 ? 0 : $i;
				}
			}
			
			$title = 'Attendance Employee Report<br> '.$employee->name.'<br><i>'.date('F Y',strtotime($month)).'</i>';
			
			$exceptholiday = Holiday::where('date','like',"$month%")->where('branch',$employee->branch)->pluck('date')->toArray();
		
			$arrleave = [];
			$getleaverequest = [];
			
			$dataleave = LeaveRequest::where('user_id',$employee->id)->where('category','1')->whereNotNull('approved_by')->whereNotNull('checked_by')->get();
			
			foreach($dataleave as $rowleave){
				$startDate = new Carbon($rowleave->start_date);
				$endDate = new Carbon($rowleave->finish_date);
				while ($startDate->lte($endDate)){
					$arrleave[] = $startDate->toDateString();
					
					if(substr($startDate->toDateString(),0,7) == $month && !in_array($startDate->toDateString(),$exceptholiday) && !in_array(date('w',strtotime($startDate->toDateString())),$arrdayoff)){
						$getleaverequest[] = [
							'date'			=> $startDate->toDateString(),
							'description' 	=> $rowleave->note,
						];
					}
					
					$startDate->addDay();
				}
			}
			
			$filteredleaverequest = collect($getleaverequest);
			
			$getattendance = Attendance::where('date','like',"$month%")->where('user_id',$employee->id)->whereNotIn('date',$exceptholiday)->whereNotIn('date',$arrleave)->get();
			
			$arrWorkDay = $this->getWorkDay($month,$employee->branch);
			
			$result = [
				'arrWorkDay' 	=> $arrWorkDay,
				'arrLeave'		=> $arrleave,
				'attendance'	=> $getattendance,
				'leaveRequest'	=> $filteredleaverequest
			];
		}
		
        $data = [
			'title'		=> $title,
			'mode'		=> $mode,
			'result'	=> $result
		];

		return view('admin.report.hrd.attendance', $data);
    }
	
	function compressImage($source, $destination, $type, $quality) {
		
		if ($type == 'jpeg' || $type == 'jpg'){ 
			$image = imagecreatefromjpeg($source);
		}elseif($type == 'gif'){
			$image = imagecreatefromgif($source);
		}elseif($type == 'png'){
			$image = imagecreatefrompng($source);
		}elseif($type == 'webp'){
			$image = imagecreatefromwebp($source);
		}
		
		imagejpeg($image, $destination, $quality);
	}
	
	public function selfie(Request $request){
		
		$validation = Validator::make($request->all(), [
			'image' 	=> 'required',
			'lat' 		=> 'required',
			'lon'		=> 'required',
			'note'		=> 'required',
		], [
			'image.required'	=> 'Image cannot empty.',
			'lat.required' 		=> 'Position latitude not found.',
			'lon.required' 		=> 'Position longitude not found.',
			'note.required'		=> 'Note cannot be empty.'
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			$folderPath = Storage::path('public/attendance/');
			
			$lat = $request->lat;
			$lon = $request->lon;
			$note = $request->note;
			
			$image_parts = explode(";base64,", $request->image);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			
			$newname = Str::random(40).'.'.$image_type;
			
			$file = $folderPath.$newname;
			
			$datalate = Schedule::where('branch',session('bo_branch'))->where('day',date('N'))->first();
			
			$cek = Attendance::where('date',date('Y-m-d'))->where('user_id',session('bo_id'))->first();
			
			$already_logged_in = false;
			
			if($cek){
				if(date('H') < 12){
					if($cek->in_code){
						$already_logged_in = true;
					}
				}else{
					if($cek->out_code){
						$already_logged_in = true;
					}else{
						
						$this->compressImage($request->image,$file,$image_type,60);
						
						$cek->update([
							'out_code'		  => strtoupper(Str::random(15).'_'.strtotime(date('Y-m-d H:i:s'))),
							'out_time'		  => date('H:i:s'),
							'out_rule'		  => $datalate ? $datalate->out_time.':00' : '00:00:00',
							'out_image'		  => 'public/attendance/'.$newname,
							'out_latitude'	  => $lat,
							'out_longitude'	  => $lon,
							'out_note'		  => $note,
                            'out_approved_by' => 10
						]);
						
						#send approval
						// $roleapproval = array('14');
						// Approval::sendApproval($roleapproval,'attendances',$cek->id,'out_approved_by',session('bo_id'));
						
						// SendMessage::send(env('FINANCE_PHONE'),'Halo pak/bu. Mohon dibantu approve Attendance Check Out melalui selfie pada tanggal '.date("d M Y",strtotime($cek->date)).' atas nama'.$cek->user->name.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
						
						$response = [
							'status'  	=> 200,
							'message' 	=> 'Successfully checked out.'
						];
					}
				}
			}else{
				if(date('H') < 12){
					
					$this->compressImage($request->image,$file,$image_type,60);
					
					$query = Attendance::create([
						'user_id'		 => session('bo_id'),
						'date'			 => date('Y-m-d'),
						'in_code'		 => strtoupper(Str::random(15).'_'.strtotime(date('Y-m-d H:i:s'))),
						'in_time'		 => date('H:i:s'),
						'in_rule'		 => $datalate ? $datalate->in_time.':00' : '00:00:00',
						'in_image'		 => 'public/attendance/'.$newname,
						'in_latitude'	 => $lat,
						'in_longitude'	 => $lon,
						'in_note'		 => $note,
                        'in_approved_by' => 10
					]);
					
					#send approval
					// $roleapproval = array('14');
					// Approval::sendApproval($roleapproval,'attendances',$query->id,'in_approved_by',session('bo_id'));
					
					// SendMessage::send(env('FINANCE_PHONE'),'Halo pak/bu. Mohon dibantu approve Attendance Check In melalui selfie pada tanggal '.date("d M Y",strtotime($query->date)).' atas nama'.$query->user->name.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
					
					$response = [
						'status'  	=> 200,
						'message' 	=> 'Successfully checked in.'
					];
					
				}else{
					
					$this->compressImage($request->image,$file,$image_type,60);
					
					$query = Attendance::create([
						'user_id'		  => session('bo_id'),
						'date'			  => date('Y-m-d'),
						'out_code'		  => strtoupper(Str::random(15).'_'.strtotime(date('Y-m-d H:i:s'))),
						'out_time'		  => date('H:i:s'),
						'out_rule'		  => $datalate ? $datalate->out_time.':00' : '00:00:00',
						'out_image'		  => 'public/attendance/'.$newname,
						'out_latitude'	  => $lat,
						'out_longitude'	  => $lon,
						'out_note'		  => $note,
                        'out_approved_by' => 10
					]);
					
					#send approval
					// $roleapproval = array('14');
					// Approval::sendApproval($roleapproval,'attendances',$query->id,'out_approved_by',session('bo_id'));
					
					// SendMessage::send(env('FINANCE_PHONE'),'Halo pak/bu. Mohon dibantu approve Attendance Check Out melalui selfie pada tanggal '.date("d M Y",strtotime($query->date)).' atas nama'.$query->user->name.'. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');
					
					$response = [
						'status'  	=> 200,
						'message' 	=> 'Successfully checked out.'
					];
				}
			}
			
			if($already_logged_in == true){
				$response = [
					'status'  	=> 400,
					'message' 	=> 'Sorry already checked in / out.'
				];
			}
		}
		
		return response()->json($response);
	}
}