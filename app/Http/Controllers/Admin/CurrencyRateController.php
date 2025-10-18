<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Currency;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CurrencyRateController extends Controller {

    public function index()
    {
        $data = [
            'title'    => 'Buy Exchange Rate',
            'currency' => Currency::where('status', 1)->get(),
            'company'  => Company::where('status', 1)->get(),
            'content'  => 'admin.master_data.cogs_master.buy_exchange_rate'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'created_at',
            'currency_id',
            'company_id',
            'conversion'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = CurrencyRate::count();
        
        $query_data = CurrencyRate::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('currency', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('company', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }    
            })
            ->offset($start)
            ->limit($length)
			->orderByDesc('created_at')
            ->get();

        $total_filtered = CurrencyRate::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('currency', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('company', function($query) use ($search) {
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
                    date('d F Y, H:i', strtotime($val->created_at)),
                    $val->currency->code,
                    $val->company->name,
                    'IDR ' . number_format($val->conversion, 2, ',', '.'),
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
            'currency_id' => 'required',
            'company_id'  => 'required',
            'conversion'  => 'required'
        ], [
            'currency_id.required' => 'Please select a currency.',
            'company_id.required'  => 'Please select a company.',
            'conversion.required'  => 'Conversion cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = CurrencyRate::create([
                'currency_id' => $request->currency_id,
                'company_id'  => $request->company_id,
                'conversion'  => str_replace(',', '', $request->conversion)
            ]);

            if($query) {
                activity()
                    ->performedOn(new CurrencyRate())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add cogs rate data');

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
        $data = CurrencyRate::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'currency_id' => 'required',
            'company_id'  => 'required',
            'conversion'  => 'required'
        ], [
            'currency_id.required' => 'Please select a currency.',
            'company_id.required'  => 'Please select a company.',
            'conversion.required'  => 'Conversion cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = CurrencyRate::where('id', $id)->update([
                'currency_id' => $request->currency_id,
                'company_id'  => $request->company_id,
                'conversion'  => str_replace(',', '', $request->conversion)
            ]);

            if($query) {
                activity()
                    ->performedOn(new CurrencyRate())
                    ->causedBy(session('bo_id'))
                    ->log('Change the cogs rate data');

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
        $query = CurrencyRate::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new CurrencyRate())
                ->causedBy(session('bo_id'))
                ->log('Delete the cogs rate data');

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
