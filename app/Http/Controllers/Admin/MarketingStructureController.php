<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\MarketingStructure;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MarketingStructureController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'Marketing Cost',
            'company' => Company::where('status', 1)->get(),
            'content' => 'admin.master_data.cogs_master.marketing_cost'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'detail',
            'id',
            'company_id',
            'fixed_cost',
            'nett_profit',
            'marketing_cost'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = MarketingStructure::count();
        
        $query_data = MarketingStructure::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('company', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }    
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = MarketingStructure::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('company', function($query) use ($search) {
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
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $nomor,
                    $val->company->code,
                    number_format($val->fixed_cost, 2, ',', '.'),
                    number_format($val->nett_profit, 2, ',', '.'),
                    number_format($val->marketing_cost, 2, ',', '.'),
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

    public function rowDetail(Request $request)
    {
        $data = MarketingStructure::find($request->id);
        return response()->json([
            'Rental Cost'           => number_format($data->rental_cost, 2, ',', '.'),
            'Travel Sales Cost'     => number_format($data->travel_sales_cost, 2, ',', '.'),
            'Marketing Cost'        => number_format($data->marketing_cost, 2, ',', '.'),
            'On Site Cost'          => number_format($data->on_site_cost, 2, ',', '.'),
            'Storage Cost'          => number_format($data->storage_cost, 2, ',', '.'),
            'Fixed Cost'            => number_format($data->fixed_cost, 2, ',', '.'),
            'Interest In Payment'   => number_format($data->interest_in_payment, 2, ',', '.'),
            'Nett Profit'           => number_format($data->nett_profit, 2, ',', '.'),
            'Saving'                => number_format($data->saving, 2, ',', '.'),
            'Sales Commission'      => number_format($data->sales_commission, 2, ',', '.'),
            'Middlemant Commission' => number_format($data->middlemant_commission, 2, ',', '.'),
            'Project Commission'    => number_format($data->project_commission, 2, ',', '.'),
			'Interest On Buying'    => number_format($data->interest_on_buying, 2, ',', '.')
        ]);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'company_id'            => 'required',
            'rental_cost'           => 'required',
            'travel_sales_cost'     => 'required',
            'marketing_cost'        => 'required',
            'on_site_cost'          => 'required',
            'storage_cost'          => 'required',
            'fixed_cost'            => 'required',
            'interest_in_payment'   => 'required',
            'nett_profit'           => 'required',
            'saving'                => 'required',
            'sales_commission'      => 'required',
            'middlemant_commission' => 'required',
            'project_commission'    => 'required',
			'interest_on_buying'    => 'required'
        ], [
            'company_id.required'            => 'Please select a company.',
            'rental_cost.required'           => 'Rental cost cannot be empty.',
            'travel_sales_cost.required'     => 'Travel sales cost cannot be empty.',
            'marketing_cost.required'        => 'Marketing cost cannot be empty.',
            'on_site_cost.required'          => 'On site cost cannot be empty.',
            'storage_cost.required'          => 'Storage cost cannot be empty.',
            'fixed_cost.required'            => 'Fixed cost cannot be empty.',
            'interest_in_payment.required'   => 'Interest in payment cannot be empty.',
            'nett_profit.required'           => 'Nett profit cannot be empty.',
            'saving.required'                => 'Saving cannot be empty.',
            'sales_commission.required'      => 'Sales commission cannot be empty.',
            'middlemant_commission.required' => 'Middlemant commission cannot be empty.',
            'project_commission.required'    => 'Project commission cannot be empty.',
			'interest_on_buying.required'    => 'Interest on buying cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = MarketingStructure::create([
                'company_id'            => $request->company_id,
                'rental_cost'           => str_replace(',', '', $request->rental_cost),
                'travel_sales_cost'     => str_replace(',', '', $request->travel_sales_cost),
                'marketing_cost'        => str_replace(',', '', $request->marketing_cost),
                'on_site_cost'          => str_replace(',', '', $request->on_site_cost),
                'storage_cost'          => str_replace(',', '', $request->storage_cost),
                'fixed_cost'            => str_replace(',', '', $request->fixed_cost),
                'interest_in_payment'   => str_replace(',', '', $request->interest_in_payment),
                'rental_cost'           => str_replace(',', '', $request->rental_cost),
                'nett_profit'           => str_replace(',', '', $request->nett_profit),
                'saving'                => str_replace(',', '', $request->saving),
                'sales_commission'      => str_replace(',', '', $request->sales_commission),
                'middlemant_commission' => str_replace(',', '', $request->middlemant_commission),
                'project_commission'    => str_replace(',', '', $request->project_commission),
				'interest_on_buying'    => str_replace(',', '', $request->interest_on_buying)
            ]);

            if($query) {
                activity()
                    ->performedOn(new MarketingStructure())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add cogs marketing structure data');

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
        $data = MarketingStructure::find($request->id);
        return response()->json([
            'company_id'            => $data->company_id,
            'rental_cost'           => $data->rental_cost,
            'travel_sales_cost'     => $data->travel_sales_cost,
            'marketing_cost'        => $data->marketing_cost,
            'on_site_cost'          => $data->on_site_cost,
            'storage_cost'          => $data->storage_cost,
            'fixed_cost'            => $data->fixed_cost,
            'interest_in_payment'   => $data->interest_in_payment,
            'nett_profit'           => $data->nett_profit,
            'saving'                => $data->saving,
            'sales_commission'      => $data->sales_commission,
            'middlemant_commission' => $data->middlemant_commission,
            'project_commission'    => $data->project_commission,
			'interest_on_buying'    => $data->interest_on_buying
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'company_id'            => 'required',
            'rental_cost'           => 'required',
            'travel_sales_cost'     => 'required',
            'marketing_cost'        => 'required',
            'on_site_cost'          => 'required',
            'storage_cost'          => 'required',
            'fixed_cost'            => 'required',
            'interest_in_payment'   => 'required',
            'nett_profit'           => 'required',
            'saving'                => 'required',
            'sales_commission'      => 'required',
            'middlemant_commission' => 'required',
            'project_commission'    => 'required',
			'interest_on_buying'    => 'required'
        ], [
            'company_id.required'            => 'Please select a company.',
            'rental_cost.required'           => 'Rental cost cannot be empty.',
            'travel_sales_cost.required'     => 'Travel sales cost cannot be empty.',
            'marketing_cost.required'        => 'Marketing cost cannot be empty.',
            'on_site_cost.required'          => 'On site cost cannot be empty.',
            'storage_cost.required'          => 'Storage cost cannot be empty.',
            'fixed_cost.required'            => 'Fixed cost cannot be empty.',
            'interest_in_payment.required'   => 'Interest in payment cannot be empty.',
            'nett_profit.required'           => 'Nett profit cannot be empty.',
            'saving.required'                => 'Saving cannot be empty.',
            'sales_commission.required'      => 'Sales commission cannot be empty.',
            'middlemant_commission.required' => 'Middlemant commission cannot be empty.',
            'project_commission.required'    => 'Project commission cannot be empty.',
			'interest_on_buying.required'    => 'Interest on buying cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = MarketingStructure::where('id', $id)->update([
                'company_id'            => $request->company_id,
                'rental_cost'           => str_replace(',', '', $request->rental_cost),
                'travel_sales_cost'     => str_replace(',', '', $request->travel_sales_cost),
                'marketing_cost'        => str_replace(',', '', $request->marketing_cost),
                'on_site_cost'          => str_replace(',', '', $request->on_site_cost),
                'storage_cost'          => str_replace(',', '', $request->storage_cost),
                'fixed_cost'            => str_replace(',', '', $request->fixed_cost),
                'interest_in_payment'   => str_replace(',', '', $request->interest_in_payment),
                'rental_cost'           => str_replace(',', '', $request->rental_cost),
                'nett_profit'           => str_replace(',', '', $request->nett_profit),
                'saving'                => str_replace(',', '', $request->saving),
                'sales_commission'      => str_replace(',', '', $request->sales_commission),
                'middlemant_commission' => str_replace(',', '', $request->middlemant_commission),
                'project_commission'    => str_replace(',', '', $request->project_commission),
				'interest_on_buying'    => str_replace(',', '', $request->interest_on_buying)
            ]);

            if($query) {
                activity()
                    ->performedOn(new MarketingStructure())
                    ->causedBy(session('bo_id'))
                    ->log('Change the cogs marketing structure data');

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
        $query = MarketingStructure::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new MarketingStructure())
                ->causedBy(session('bo_id'))
                ->log('Delete the cogs marketing structure data');

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
