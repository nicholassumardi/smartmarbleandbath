<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Vendor;
use App\Models\Delivery;
use App\Models\Transport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PriceController extends Controller {

    public function index()
    {
        $data = [
            'title'     => 'Delivery Cost',
            'vendor'    => Vendor::where('status', 1)->get(),
            'transport' => Transport::all(),
            'city'      => City::all(),
            'content'   => 'admin.master_data.delivery.delivery_cost'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'vendor_id',
            'transport_id',
            'destination_id',
            'capacity',
            'price_per_kg',
            'price_per_meter'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Delivery::count();
        
        $query_data = Delivery::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->whereHas('transport', function($query) use ($search) {
                                $query->where('fleet', 'like', "%$search%");
                            })
                            ->whereHas('destination', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }     
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Delivery::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('vendor', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->whereHas('transport', function($query) use ($search) {
                                $query->where('fleet', 'like', "%$search%");
                            })
                            ->whereHas('destination', function($query) use ($search) {
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
                    $val->vendor->name,
                    $val->transport->fleet,
                    $val->destination->name,
                    number_format($val->capacity, 2, ',', '.') . ' Kg',
                    number_format($val->price_per_kg, 2, ',', '.'),
                    number_format($val->price_per_meter, 2, ',', '.'),
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
            'vendor_id'       => 'required',
            'transport_id'    => 'required',
            'destination_id'  => 'required',
            'capacity'        => 'required',
            'price_per_kg'    => 'required',
            'price_per_meter' => 'required'
        ], [
            'vendor_id.required'       => 'Please select a vendor',
            'transport_id.required'    => 'Please select a transport',
            'destination_id.required'  => 'Please select a destination',
            'capacity.required'        => 'Capacity cannot be empty',
            'price_per_kg.required'    => 'Price per kg cannot be empty',
            'price_per_meter.required' => 'Price per meter cannot be empty'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Delivery::create([
                'vendor_id'       => $request->vendor_id,
                'transport_id'    => $request->transport_id,
                'destination_id'  => $request->destination_id,
                'capacity'        => $request->capacity,
                'price_per_kg'    => $request->price_per_kg,
                'price_per_meter' => $request->price_per_meter
            ]);

            if($query) {
                activity()
                    ->performedOn(new Delivery())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add delivery price data');

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
        $data = Delivery::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'vendor_id'       => 'required',
            'transport_id'    => 'required',
            'destination_id'  => 'required',
            'capacity'        => 'required',
            'price_per_kg'    => 'required',
            'price_per_meter' => 'required'
        ], [
            'vendor_id.required'       => 'Please select a vendor',
            'transport_id.required'    => 'Please select a transport',
            'destination_id.required'  => 'Please select a destination',
            'capacity.required'        => 'Capacity cannot be empty',
            'price_per_kg.required'    => 'Price per kg cannot be empty',
            'price_per_meter.required' => 'Price per meter cannot be empty'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Delivery::where('id', $id)->update([
                'vendor_id'       => $request->vendor_id,
                'transport_id'    => $request->transport_id,
                'destination_id'  => $request->destination_id,
                'capacity'        => $request->capacity,
                'price_per_kg'    => $request->price_per_kg,
                'price_per_meter' => $request->price_per_meter
            ]);

            if($query) {
                activity()
                    ->performedOn(new Delivery())
                    ->causedBy(session('bo_id'))
                    ->log('Change the price delivery data');

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
        $query = Delivery::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Delivery())
                ->causedBy(session('bo_id'))
                ->log('Delete the price delivery data');

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
