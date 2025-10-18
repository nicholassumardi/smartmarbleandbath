<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Emkl;
use App\Models\Import;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmklController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Import Estimation Rate',
            'company' => Company::where('status', 1)->get(),
            'import'  => Import::all(),
            'country' => Country::where('status', 1)->get(),
            'city'    => City::all(),
            'content' => 'admin.master_data.cogs_master.import_estimation_rate'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'company_id',
            'import_id',
            'country_id',
            'city_id',
            'container',
            'cost'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Emkl::count();
        
        $query_data = Emkl::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('company', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('import', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('country', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('city', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }    
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Emkl::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('company', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('import', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('country', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('city', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
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
                    $nomor,
                    $val->company->name,
                    $val->import->name,
                    $val->country->name,
                    $val->city->name,
                    $val->container(),
                    'Rp ' . number_format($val->cost, 2, ',', '.'),
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'company_id' => 'required',
            'import_id'  => 'required',
            'country_id' => 'required',
            'city_id'    => 'required',
            'container'  => 'required',
            'cost'       => 'required'
        ], [
            'company_id.required' => 'Please select a company.',
            'import_id.required'  => 'Please select a import.',
            'country_id.required' => 'Please select a port of discharge.',
            'city_id.required'    => 'Please select a dstination port.',
            'container.required'  => 'Please select a container.',
            'cost.required'       => 'Cost cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Emkl::create([
                'company_id' => $request->company_id,
                'import_id'  => $request->import_id,
                'country_id' => $request->country_id,
                'city_id'    => $request->city_id,
                'container'  => $request->container,
                'cost'       => str_replace(',', '', $request->cost)
            ]);

            if($query) {
                activity()
                    ->performedOn(new Emkl())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add cogs emkl data');

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
        $data = Emkl::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'company_id' => 'required',
            'import_id'  => 'required',
            'country_id' => 'required',
            'city_id'    => 'required',
            'container'  => 'required',
            'cost'       => 'required'
        ], [
            'company_id.required' => 'Please select a company.',
            'import_id.required'  => 'Please select a import.',
            'country_id.required' => 'Please select a port of discharge.',
            'city_id.required'    => 'Please select a dstination port.',
            'container.required'  => 'Please select a container.',
            'cost.required'       => 'Cost cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Emkl::where('id', $id)->update([
                'company_id' => $request->company_id,
                'import_id'  => $request->import_id,
                'country_id' => $request->country_id,
                'city_id'    => $request->city_id,
                'container'  => $request->container,
                'cost'       => str_replace(',', '', $request->cost)
            ]);

            if($query) {
                activity()
                    ->performedOn(new Emkl())
                    ->causedBy(session('bo_id'))
                    ->log('Change the cogs emkl data');

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
    }

    public function destroy(Request $request) 
    {
        $query = Emkl::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Emkl())
                ->causedBy(session('bo_id'))
                ->log('Delete the cogs emkl data');

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
