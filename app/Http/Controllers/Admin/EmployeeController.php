<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class EmployeeController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Employee',
            'content' => 'admin.hrd.employee'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'id',
			'name',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = User::count();
        
        $query_data = User::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
			->where('status','1')
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = User::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
			->where('status','1')
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                if(Storage::exists($val->photo)) {
                    $photo = '<a href="' . asset(Storage::url($val->photo)) . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . asset(Storage::url($val->photo)) . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
                } else {
                    $photo = '<a href="' . asset('website/user.png') . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . asset('website/user.png') . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
                }
				
				$arrRole = [];
				
				foreach($val->userRole as $ur){
					$arrRole[] = $ur->role();
				}
				
				$colorprofile = 'badge-success';
				
				$d1 = Carbon::parse(date('Y-m-d'));
				$d2 = Carbon::parse(date('Y-m-d',strtotime($val->updated_at)));;
				$totalDiff = $d2->diffInMonths($d1);
				
				if($totalDiff > 6){
					$colorprofile = 'badge-danger';
				}

                $response['data'][] = [
                    $nomor,
                    $photo.'<br>'.$val->name.'<br><span class="badge '.$colorprofile.'"><i>Last updated</i> : '.date('Y-m-d',strtotime($val->updated_at)).'</span>',
                    $val->branch(),
                    implode(', ',$arrRole),
                    '<a href="'.url('admin/hrd/employee/employment').'/'.base64_encode($val->id).'" class="btn btn-sm btn-info" data-popup="tooltip" title="Add/Edit Employment Status"><i class="icon-vcard"></i><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->employment).'</span></a>',
					'<a href="'.url('admin/hrd/employee/family').'/'.base64_encode($val->id).'" class="btn btn-sm btn-primary" data-popup="tooltip" title="Add/Edit Family Member"><i class="icon-users4"></i><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->family).'</span></a>',
					'<a href="'.url('admin/hrd/employee/education').'/'.base64_encode($val->id).'" class="btn btn-sm btn-success" data-popup="tooltip" title="Add/Edit Educational Background"><i class="icon-graduation2"></i><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->education).'</span></a>',
					'<a href="'.url('admin/hrd/employee/experience').'/'.base64_encode($val->id).'" class="btn btn-sm btn-secondary" data-popup="tooltip" title="Add/Edit Work Experience"><i class="icon-user-tie"></i><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->experience).'</span></a>',
                    '<a href="'.url('admin/hrd/employee/asset').'/'.base64_encode($val->id).'" class="btn btn-sm btn-warning" data-popup="tooltip" title="Add/Edit Asset"><i class="icon-laptop"></i><span class="badge badge-info badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->asset).'</span></a>',
					 '<a href="'.url('admin/hrd/employee/loan').'/'.base64_encode($val->id).'" class="btn btn-sm bg-pink-600" data-popup="tooltip" title="Add/Edit Employee Loan"><i class="icon-cash"></i><span class="badge badge-warning badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->employeeLoan).'</span></a>'
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
}
