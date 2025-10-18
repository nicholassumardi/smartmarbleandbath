<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PricingPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PricingPolicyController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Pricing Sales',
            'content' => 'admin.master_data.cogs_master.pricing_sales'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'detail',
            'id',
            'image',
            'product_id',
            'size',
            'rental_cost',
            'fixed_cost',
            'marketing_cost',
            'price_list'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = PricingPolicy::count();
        
        $query_data = PricingPolicy::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('product', function($query) use ($search) {
                                $query->whereHas('type', function($query) use ($search) {
                                    $query->whereRaw("code LIKE '%$search%'")
                                        ->orWhereHas('category', function($query) use ($search) {
                                                $query->whereRaw("name LIKE '%$search%'");
                                            })
                                        ->orWhereHas('color', function($query) use ($search) {
                                                $query->whereRaw("name LIKE '%$search%'");
                                            });
                                })
                                ->orWhereHas('brand', function($query) use ($search) {
                                        $query->whereRaw("name LIKE '%$search%'");
                                    })
                                ->orWhereHas('country', function($query) use ($search) {
                                        $query->whereRaw("name LIKE '%$search%'");
                                    });
                            });
                    });
                }    
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = PricingPolicy::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('product', function($query) use ($search) {
                                $query->whereHas('type', function($query) use ($search) {
                                    $query->whereRaw("code LIKE '%$search%'")
                                        ->orWhereHas('category', function($query) use ($search) {
                                                $query->whereRaw("name LIKE '%$search%'");
                                            })
                                        ->orWhereHas('color', function($query) use ($search) {
                                                $query->whereRaw("name LIKE '%$search%'");
                                            });
                                })
                                ->orWhereHas('brand', function($query) use ($search) {
                                        $query->whereRaw("name LIKE '%$search%'");
                                    })
                                ->orWhereHas('country', function($query) use ($search) {
                                        $query->whereRaw("name LIKE '%$search%'");
                                    });
                            });
                    });
                }       
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				if(isset($val->product->type)){
					$image = '<a href="' . $val->product->type->image() . '" data-lightbox="' . $val->product->name() . '" data-title="' . $val->product->name() . '"><img src="' . $val->product->type->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';

					$response['data'][] = [
						'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
						$nomor,
						$image,
						$val->product->name(),
						$val->product->type->length . 'x' . $val->product->type->width,
						number_format($val->rental_cost, 2, ',', '.'),
						number_format($val->fixed_cost, 2, ',', '.'),
						number_format($val->marketing_cost, 2, ',', '.'),
						number_format($val->price_list, 2, ',', '.'),
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

    public function rowDetail(Request $request)
    {
        $data = PricingPolicy::find($request->id);
        return response()->json([
            'Rental Cost'            		=> number_format($data->rental_cost, 2, ',', '.'),
			'Fixed Cost'               		=> number_format($data->fixed_cost, 2, ',', '.'),
			'Interest on Buying Capital'    => number_format($data->interest_buying, 2, ',', '.'),
			'Savings'                   	=> number_format($data->saving, 2, ',', '.'),
			'Rsv Profit'              		=> number_format($data->rsv_profit, 2, ',', '.'),
			'Sales Commission'         		=> number_format($data->sales_commission, 2, ',', '.'),
			'Interest in Payment Method'    => number_format($data->interest_payment, 2, ',', '.'),
            'Sales Travel Cost'        		=> number_format($data->sales_travel_cost, 2, ',', '.'),
            'Marketing Cost'           		=> number_format($data->marketing_cost, 2, ',', '.'),
            'Middleman Fee'               	=> number_format($data->middleman_fee, 2, ',', '.'),
            'Project Commission'            => number_format($data->project_commission, 2, ',', '.'),
            'On Site Cost'             		=> number_format($data->on_site_cost, 2, ',', '.'),
            'Storage Cost'             		=> number_format($data->storage_cost, 2, ',', '.'),
            'Bottom Price'             		=> number_format($data->bottom_price, 2, ',', '.'),
            'Price List'               		=> number_format($data->price_list, 2, ',', '.'),
			'Recommended Price'            	=> number_format($data->recommended_price, 2, ',', '.'),
            'Store Price List'         		=> number_format($data->store_price_list, 2, ',', '.'),
            'Discount Retail Sales'    		=> number_format($data->discount_retail_sales, 2, ',', '.'),
            'Discount Retail Manager'  		=> number_format($data->discount_retail_manager, 2, ',', '.'),
            'Discount Retail Director' 		=> number_format($data->discount_retail_director, 2, ',', '.')
        ]);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'product_id'               	=> 'required',
            'rental_cost'            	=> 'required',
			'fixed_cost'               	=> 'required',
			'interest_buying'           => 'required',
			'saving'                   	=> 'required',
			'rsv_profit'              	=> 'required',
			'sales_commission'         	=> 'required',
			'interest_payment'          => 'required',
            'sales_travel_cost'        	=> 'required',
            'marketing_cost'           	=> 'required',
            'middleman_fee'             => 'required',
            'project_commission'        => 'required',
            'on_site_cost'             	=> 'required',
            'storage_cost'             	=> 'required',
            'bottom_price'             	=> 'required',
            'price_list'               	=> 'required',
			'recommended_price'         => 'required',
            'store_price_list'         	=> 'required',
            'discount_retail_sales'    	=> 'required',
            'discount_retail_manager'  	=> 'required',
            'discount_retail_director' 	=> 'required'
        ], [
            'product_id.required'               => 'Please select a product.',
            'rental_cost.required'            	=> 'Rental cost cannot be empty.',
			'fixed_cost.required'               => 'Fixed cost cannot be empty.',
			'interest_buying.required'          => 'Interest on buying capital cannot be empty.',
			'saving.required'                   => 'Savings cannot be empty.',
			'rsv_profit.required'              	=> 'Rsv profit cannot be empty.',
			'sales_commission.required'         => 'Sales commission cannot be empty.',
			'interest_payment.required'         => 'Interest in payment method cannot be empty.',
            'sales_travel_cost.required'        => 'Sales travel cost cannot be empty.',
            'marketing_cost.required'           => 'Marketing cost cannot be empty.',
            'middleman_fee.required'            => 'Middleman fee cannot be empty.',
            'project_commission.required'       => 'Project commission cannot be empty.',
            'on_site_cost.required'             => 'On site cost cannot be empty.',
            'storage_cost.required'             => 'Storage cost cannot be empty.',
            'bottom_price.required'             => 'Bottom price cannot be empty.',
			'price_list.required'               => 'Price list cannot be empty.',
            'recommended_price.required'        => 'Recommended price cannot be empty.',
            'store_price_list.required'         => 'Store price list cannot be empty.',
            'discount_retail_sales.required'    => 'Discount retail sales cannot be empty.',
            'discount_retail_manager.required'  => 'Discount retail manager cannot be empty.',
            'discount_retail_director.required' => 'Discount retail director cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = PricingPolicy::create([
                'product_id'               => $request->product_id,
                'rental_cost'              => $request->rental_cost,
				'fixed_cost'               => $request->fixed_cost,
				'interest_buying'          => $request->interest_buying,
				'saving'                   => $request->saving,
				'rsv_profit'               => $request->rsv_profit,
				'sales_commission'         => $request->sales_commission,
				'interest_payment'         => $request->interest_payment,
                'sales_travel_cost'        => $request->sales_travel_cost,
                'marketing_cost'           => $request->marketing_cost,
                'middleman_fee'            => $request->middleman_fee,
                'project_commission'       => $request->project_commission,
                'on_site_cost'             => $request->on_site_cost,
                'storage_cost'             => $request->storage_cost,
                'bottom_price'             => $request->bottom_price,
				'price_list'               => $request->price_list,
                'recommended_price'        => $request->recommended_price,
                'store_price_list'         => $request->store_price_list,
                'discount_retail_sales'    => $request->discount_retail_sales,
                'discount_retail_manager'  => $request->discount_retail_manager,
                'discount_retail_director' => $request->discount_retail_director
            ]);

            if($query) {
                activity()
                    ->performedOn(new PricingPolicy())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add price pricing policy data');

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
        $data = PricingPolicy::find($request->id);
        return response()->json([
            'product_id'               => $data->product_id,
            'product_code'             => $data->product->name(),
            'rental_cost'              => $data->rental_cost,
			'fixed_cost'               => $data->fixed_cost,
			'interest_buying'          => $data->interest_buying,
			'saving'                   => $data->saving,
			'rsv_profit'               => $data->rsv_profit,
			'sales_commission'         => $data->sales_commission,
			'interest_payment'         => $data->interest_payment,
            'sales_travel_cost'        => $data->sales_travel_cost,
            'marketing_cost'           => $data->marketing_cost,
            'middleman_fee'            => $data->middleman_fee,
            'project_commission'       => $data->project_commission,
            'on_site_cost'             => $data->on_site_cost,
            'storage_cost'             => $data->storage_cost,
            'bottom_price'             => $data->bottom_price,
			'price_list'               => $data->price_list,
            'recommended_price'  => $data->recommended_price,
            'store_price_list'         => $data->store_price_list,
            'discount_retail_sales'    => $data->discount_retail_sales,
            'discount_retail_manager'  => $data->discount_retail_manager,
            'discount_retail_director' => $data->discount_retail_director
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'product_id'               	=> 'required',
            'rental_cost'            	=> 'required',
			'fixed_cost'               	=> 'required',
			'interest_buying'           => 'required',
			'saving'                   	=> 'required',
			'rsv_profit'              	=> 'required',
			'sales_commission'         	=> 'required',
			'interest_payment'          => 'required',
            'sales_travel_cost'        	=> 'required',
            'marketing_cost'           	=> 'required',
            'middleman_fee'             => 'required',
            'project_commission'        => 'required',
            'on_site_cost'             	=> 'required',
            'storage_cost'             	=> 'required',
            'bottom_price'             	=> 'required',
            'price_list'               	=> 'required',
			'recommended_price'         => 'required',
            'store_price_list'         	=> 'required',
            'discount_retail_sales'    	=> 'required',
            'discount_retail_manager'  	=> 'required',
            'discount_retail_director' 	=> 'required'
        ], [
            'product_id.required'               => 'Please select a product.',
            'rental_cost.required'            	=> 'Rental cost cannot be empty.',
			'fixed_cost.required'               => 'Fixed cost cannot be empty.',
			'interest_buying.required'          => 'Interest on buying capital cannot be empty.',
			'saving.required'                   => 'Savings cannot be empty.',
			'rsv_profit.required'              	=> 'Rsv profit cannot be empty.',
			'sales_commission.required'         => 'Sales commission cannot be empty.',
			'interest_payment.required'         => 'Interest in payment method cannot be empty.',
            'sales_travel_cost.required'        => 'Sales travel cost cannot be empty.',
            'marketing_cost.required'           => 'Marketing cost cannot be empty.',
            'middleman_fee.required'            => 'Middleman fee cannot be empty.',
            'project_commission.required'       => 'Project commission cannot be empty.',
            'on_site_cost.required'             => 'On site cost cannot be empty.',
            'storage_cost.required'             => 'Storage cost cannot be empty.',
            'bottom_price.required'             => 'Bottom price cannot be empty.',
			'price_list.required'               => 'Price list cannot be empty.',
            'recommended_price.required'        => 'Recommended price cannot be empty.',
            'store_price_list.required'         => 'Store price list cannot be empty.',
            'discount_retail_sales.required'    => 'Discount retail sales cannot be empty.',
            'discount_retail_manager.required'  => 'Discount retail manager cannot be empty.',
            'discount_retail_director.required' => 'Discount retail director cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = PricingPolicy::where('id', $id)->update([
                'product_id'               => $request->product_id,
                'rental_cost'              => $request->rental_cost,
				'fixed_cost'               => $request->fixed_cost,
				'interest_buying'          => $request->interest_buying,
				'saving'                   => $request->saving,
				'rsv_profit'               => $request->rsv_profit,
				'sales_commission'         => $request->sales_commission,
				'interest_payment'         => $request->interest_payment,
                'sales_travel_cost'        => $request->sales_travel_cost,
                'marketing_cost'           => $request->marketing_cost,
                'middleman_fee'            => $request->middleman_fee,
                'project_commission'       => $request->project_commission,
                'on_site_cost'             => $request->on_site_cost,
                'storage_cost'             => $request->storage_cost,
                'bottom_price'             => $request->bottom_price,
				'price_list'               => $request->price_list,
                'recommended_price'        => $request->recommended_price,
                'store_price_list'         => $request->store_price_list,
                'discount_retail_sales'    => $request->discount_retail_sales,
                'discount_retail_manager'  => $request->discount_retail_manager,
                'discount_retail_director' => $request->discount_retail_director
            ]);

            if($query) {
                activity()
                    ->performedOn(new PricingPolicy())
                    ->causedBy(session('bo_id'))
                    ->log('Change the price pricing policy data');

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
        $query = PricingPolicy::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new PricingPolicy())
                ->causedBy(session('bo_id'))
                ->log('Delete the price pricing policy data');

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
