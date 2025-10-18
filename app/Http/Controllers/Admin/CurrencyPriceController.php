<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\CurrencyPrice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CurrencyPriceController extends Controller {

    public function index()
    {
        $data = [
            'title'    => 'Purchase Price',
            'currency' => Currency::where('status', 1)->get(),
            'content'  => 'admin.master_data.cogs_master.purchase_price'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'created_at',
            'product_id',
            'currency_id',
            'price'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = CurrencyPrice::count();
        
        $query_data = CurrencyPrice::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('product', function($query) use ($search) {
                                $query->whereHas('type', function($query) use ($search) {
                                     $query->where('code', 'like', "%$search%")
										->orWhereHas('category', function($query) use ($search) {
											$query->where('name', 'like', "%$search%");
										})
										->orWhereHas('surface', function($query) use ($search) {
											$query->where('name', 'like', "%$search%");
										})
										->orWhereHas('color', function($query) use ($search) {
											$query->where('name', 'like', "%$search%");
										})
										->orWhereHas('pattern', function($query) use ($search) {
											$query->where('name', 'like', "%$search%");
										});
                                })
                                ->orWhereHas('brand', function($query) use ($search) {
                                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                                    })
                                ->orWhereHas('country', function($query) use ($search) {
                                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                                    });
                            })
                            ->orWhereHas('currency', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }    
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = CurrencyPrice::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('product', function($query) use ($search) {
                                $query->whereHas('type', function($query) use ($search) {
                                     $query->where('code', 'like', "%$search%")
										->orWhereHas('category', function($query) use ($search) {
											$query->where('name', 'like', "%$search%");
										})
										->orWhereHas('surface', function($query) use ($search) {
											$query->where('name', 'like', "%$search%");
										})
										->orWhereHas('color', function($query) use ($search) {
											$query->where('name', 'like', "%$search%");
										})
										->orWhereHas('pattern', function($query) use ($search) {
											$query->where('name', 'like', "%$search%");
										});
                                })
                                ->orWhereHas('brand', function($query) use ($search) {
                                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                                    })
                                ->orWhereHas('country', function($query) use ($search) {
                                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                                    });
                            })
                            ->orWhereHas('currency', function($query) use ($search) {
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
				if(isset($val->product)){
					$response['data'][] = [
						$nomor,
						date('d F Y, H:i', strtotime($val->created_at)),
						$val->product->name(),
						$val->currency->code,
						number_format($val->price, 2, ',', '.'),
						'
							<button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
							<button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
						'
					];

					$nomor++;
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'product_id'  => 'required',
            'currency_id' => 'required',
            'price'  => 'required'
        ], [
            'product_id.required'  => 'Please select a product.',
            'currency_id.required' => 'Please select a currency.',
            'price.required'       => 'Price cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = CurrencyPrice::create([
                'product_id'  => $request->product_id,
                'currency_id' => $request->currency_id,
                'price'       => str_replace(',', '', $request->price)
            ]);

            if($query) {
                activity()
                    ->performedOn(new CurrencyPrice())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add cogs price data');

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
        $data = CurrencyPrice::find($request->id);
        return response()->json([
            'product_id'   => $data->product_id,
            'product_code' => $data->product->name(),
            'currency_id'  => $data->currency_id,
            'price'        => $data->price
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'product_id'  => 'required',
            'currency_id' => 'required',
            'price'  => 'required'
        ], [
            'product_id.required'  => 'Please select a product.',
            'currency_id.required' => 'Please select a currency.',
            'price.required'       => 'Price cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = CurrencyPrice::where('id', $id)->update([
                'product_id'  => $request->product_id,
                'currency_id' => $request->currency_id,
                'price'       => str_replace(',', '', $request->price)
            ]);

            if($query) {
                activity()
                    ->performedOn(new CurrencyPrice())
                    ->causedBy(session('bo_id'))
                    ->log('Change the cogs price data');

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
        $query = CurrencyPrice::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new CurrencyPrice())
                ->causedBy(session('bo_id'))
                ->log('Delete the cogs price data');

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
