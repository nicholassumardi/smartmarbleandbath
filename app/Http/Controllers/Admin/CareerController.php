<?php

namespace App\Http\Controllers\Admin;

use App\Models\Career;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CareerController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Career',
            'content' => 'admin.master_data.digital.career'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'title',
            'deadline',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Career::count();
        
        $query_data = Career::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('title', 'like', "%$search%");
                    });
                }       
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Career::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('title', 'like', "%$search%");
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
                    $val->title,
                    date('d F Y', strtotime($val->deadline)),
                    $val->status(),
                    '
                        <a href="' . url('admin/master_data/digital/career/detail/' . $val->id) . '" class="btn bg-info btn-sm" data-popup="tooltip" title="Detail"><i class="icon-info22"></i></a>
                        <a href="' . url('admin/master_data/digital/career/update/' . $val->id) . '" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit"><i class="icon-pencil7"></i></a>
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

    public function create(Request $request)
    {
        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'title'        => 'required',
                'description'  => 'required',
                'requirements' => 'required',
                'deadline'     => 'required'
            ], [
                'title.required'        => 'Title cannot be empty.',
                'description.required'  => 'Description cannot be empty.',
                'requirements.required' => 'Requirements cannot be empty.',
                'deadline.required'     => 'Deadline cannot be empty.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $query = Career::create([
                    'title'        => $request->title,
                    'description'  => $request->description,
                    'requirements' => $request->requirements,
                    'deadline'     => $request->deadline
                ]);

                if($query) {
                    activity()
                        ->performedOn(new Career())
                        ->causedBy(session('bo_id'))
                        ->withProperties($query)
                        ->log('Add manage career data');

                    return redirect()->back()->with(['success' => 'Data added successfully.']);
                } else {
                    return redirect()->back()->withInput()->with(['failed' => 'Data failed to added.']);
                }
            }
        } else {
            $data = [
                'title'   => 'Create New Career',
                'content' => 'admin.master_data.digital.career_create'
            ];

            return view('admin.layouts.index', ['data' => $data]);
        }
    }

    public function update(Request $request, $id)
    {
        $query = Career::find($id);
        if(!$query) {
            abort(404);
        }
        
        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'title'        => 'required',
                'description'  => 'required',
                'requirements' => 'required',
                'deadline'     => 'required'
            ], [
                'title.required'        => 'Title cannot be empty.',
                'description.required'  => 'Description cannot be empty.',
                'requirements.required' => 'Requirements cannot be empty.',
                'deadline.required'     => 'Deadline cannot be empty.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $query->update([
                    'title'        => $request->title,
                    'description'  => $request->description,
                    'requirements' => $request->requirements,
                    'deadline'     => $request->deadline
                ]);

                if($query) {
                    activity()
                        ->performedOn(new Career())
                        ->causedBy(session('bo_id'))
                        ->log('Change the manage career data');

                    return redirect()->back()->with(['success' => 'Data updated successfully.']);
                } else {
                    return redirect()->back()->withInput()->with(['failed' => 'Data failed to update..']);
                }
            }
        } else {
            $data = [
                'title'   => 'Update Career',
                'career'  => $query,
                'content' => 'admin.master_data.digital.career_update'
            ];

            return view('admin.layouts.index', ['data' => $data]);
        }
    }

    public function destroy(Request $request) 
    {
        $query = Career::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Career())
                ->causedBy(session('bo_id'))
                ->log('Delete the manage career data');

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

    public function detail($id)
    {
        $data = [
            'title'   => 'Detail Career',
            'career'  => Career::find($id),
            'content' => 'admin.master_data.digital.career_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

}
