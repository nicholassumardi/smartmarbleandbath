<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectDelivery;
use Illuminate\Http\Request;

class DeliveryStatusController extends Controller
{
    public function index()
    {
        $data = [
            'title'           => 'Delivery Status',
            'content'         => 'admin.delivery_order.delivery_status'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }


    public function datatable(Request $request)
    {
        $column = [
            'id',
            'created_at',
            'user_id',
            'code',
            'code',

        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ProjectDelivery::count();

        $query_data = ProjectDelivery::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('created_at', 'like', "%$search%");
                });
            }

            if ($request->month) {
                $query->whereRaw("LEFT(DATE(created_at), 7) = '$request->month'");
            }

            if ($request->branch) {
                $query->whereHas('projectSale', function ($query) use ($request) {
                    $query->whereHas('sales', function ($query) use ($request) {
                        $query->where('branch', $request->branch);
                    });
                });
            }
        })->orderBy('created_at', 'DESC')
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectDelivery::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('created_at', 'like', "%$search%");
                });
            }
            
            if ($request->month) {
                $query->whereRaw("LEFT(DATE(created_at), 7) = '$request->month'");
            }

            if ($request->branch) {
                $query->whereHas('projectSale', function ($query) use ($request) {
                    $query->whereHas('sales', function ($query) use ($request) {
                        $query->where('branch', $request->branch);
                    });
                });
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;

            foreach ($query_data as $val) {
                
                $button = '<a href="' . url('admin/delivery_order/project/print/sales_proforma/' . base64_encode($val->id)) . '" target="blank" class="btn bg-primary" data-popup="tooltip" title="Redirect to project"><i class="icon-file-pdf"></i></a>';

                $button_other = '<a href="' . url('admin/delivery_order/project/print/sales_proforma_other/' . base64_encode($val->id)) . '" target="blank" class="btn bg-primary" data-popup="tooltip" title="Redirect to project"><i class="icon-file-pdf"></i></a>';


              
                $button_redirect = '<a href="' . url('admin/delivery_order/project/progress/' . $val->project->id . '#step-16') . '" target="blank" class="btn bg-success" data-popup="tooltip" title="Redirect to project"><i class="icon-forward"></i></a>';


                $response['data'][] = [
                    $nomor,
                    date('M Y', strtotime($val->created_at)),
                    $val->project->code,
                    $val->code,
                    $val->project->user->name,
                    $val->project->customer->name,
                    $button,
                    $button_other,
                    $val->received_date ? '<span class="badge badge-success">Delivered</span>' : '<span class="badge badge-warning">Pending</span>',
                    $button_redirect
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
}
