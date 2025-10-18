<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FieldTrip;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldTripController extends Controller
{
    public function index()
    {
        $data = [
            'title'               => 'Field Trips',
            'customer'           =>  Customer::all(),
            'content'             => 'admin.sales.field_trip'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }


    public function datatable(Request $request)
    {
        $column = [
            'id',
            'user_id',
            'date',
            'note',
            'proof',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = FieldTrip::where('project_id', $request->project_id)->count();

        $query_data = FieldTrip::where('project_id', $request->project_id)
        ->where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = FieldTrip::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;

            foreach ($query_data as $val) {
                $btnAction = '<a href="' . url('admin/sales/sample/detail/' . $val->id) . '" class="btn bg-info btn-sm"><i class="icon-info22"></i></a>
                <button class="btn bg-danger btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-trash"></i></button>';

                $progress = '
                <div class="progress" style="height:0.875rem;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:100%;">
                        <span class="font-weight-bold text-uppercase">
                            <span style="font-size:13px;">' . $val->progress . '%</span>
                        </span>
                    </div>
                </div>
                ';
                $response['data'][] = [
                    $nomor,
                    $val->user->name,
                    $val->date,
                    $val->customer->name,
                    $progress,
                    $btnAction,
                ];

                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if ($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if ($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'customer_id'    => 'required',
            'progress'         => 'required',
            'date'             => 'required',
            'note'             => 'required',
        ], [
            'customer_id.required'  => 'Customer name cannot empty.',
            'progress.required'       => 'progress cannot empty.',
            'date.required'           => 'Please select a date.',
            'note.array'           => 'Note cannot empty.',

        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if ($request->temp_id) {
                $fieldtrip = FieldTrip::find($request->temp_id);

                if ($request->hasFile('proof')) {
                    if (Storage::exists($fieldtrip->proof)) {
                        Storage::delete($fieldtrip->proof);
                    }

                    $proof = $request->file('proof')->store('public/field_trip');
                } else {
                    $proof =  $fieldtrip->proof;
                }

                $query = FieldTrip::where('id', $request->temp_id)->update([
                    'user_id'       => session('bo_id'),
                    'project_id'    => session('bo_id'),
                    'customer_id'   => $request->customer_id,
                    'progress'      => $request->progress,
                    'date'          => date("Y-m-d", strtotime($request->date)),
                    'proof'         => $proof,
                    'note'          => $request->note,
                ]);
            }else{
                $query = FieldTrip::create([
                    'user_id'       => session('bo_id'),
                    'project_id'    => session('bo_id'),
                    'customer_id'   => $request->customer_id,
                    'progress'      => $request->progress,
                    'date'          => date("Y-m-d", strtotime($request->date)),
                    'proof'         => $request->file('proof')->store('public/field_trip'),
                    'note'          => $request->note,
                ]);

            }

            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => 'Data created successfully'
                ];
            } else {
                $response = [
                    'status' => 500,
                    'message' => 'Data failed to create'
                ];
            }

            return response()->json($response);
        }

        activity()
            ->performedOn(new FieldTrip())
            ->causedBy(session('bo_id'))
            ->log('Add field trip by ' . session('bo_name'));

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $result = FieldTrip::find($request->id);

        if ($result) {
            $response = [
                'status'  => 200,
                'message' => 'Success',
                'data'    => $result,
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Failed to show data',
            ];
        }

        return response()->json($response);
    }


    public function updateReminder()
    {
    }
}
