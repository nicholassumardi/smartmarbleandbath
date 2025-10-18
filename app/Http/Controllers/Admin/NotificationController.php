<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller {
    
    public function index()
    {
        $data = [
            'title'        => 'Notification',
            'notification' => Notification::where('user_id', session('bo_id'))->orderBy('created_at','desc')->paginate(10),
            'content'      => 'admin.notification'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request) 
    {
        $column = [
            'title',
            'description',
            'date',
        ];

        $start  = $request->start;
        $length = $request->length;
        $search = $request->input('search.value');

        $total_data = Notification::where('user_id', session('bo_id'))->count();
        
        $query_data = Notification::where('user_id', session('bo_id'))->where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('title', 'like', "%$search%")
							->orWhere('description', 'like', "%$search%");
                    });
                }         
                
                if($request->status) {
                    $query->where('seen', $request->status);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy('created_at', 'desc')
            ->get();

        $total_filtered = Notification::where('user_id', session('bo_id'))->where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('title', 'like', "%$search%")
							->orWhere('description', 'like', "%$search%");
                    });
                }         
                
                if($request->status) {
                    $query->where('seen', $request->status);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            foreach($query_data as $val) {
				if(date('Y-m-d',strtotime($val->created_at)) == date('Y-m-d')){
					$response['data'][] = [
						'<span class="font-weight-black">'.$val->title.'</span>',
						'<span class="font-weight-black">'.$val->description.'</span>',
						'<span class="font-weight-black">'.date('Y-m-d H:i', strtotime($val->created_at)).'</span>'
					];
				}else{
					$response['data'][] = [
						$val->title,
						$val->description,
						date('Y-m-d H:i', strtotime($val->created_at))
					];
				}
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
