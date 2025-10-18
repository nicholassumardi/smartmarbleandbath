<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Brand;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderDelivery;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderDoController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'DO Retail',
            'order'   => Order::whereIn('status', [2, 3, 4])->get(),
            'content' => 'admin.delivery_order.retail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'delivery_order',
            'order_id',
            'status',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = OrderDelivery::count();
        
        $query_data = OrderDelivery::where(function($query) use ($search, $request) {
                if($search) {
                    $query->whereHas('order', function($query) use ($search) {
                            $query->where('number', 'like', "%$search%");
                        })
                        ->orWhere('delivery_order', 'like', "%$search%");
                }   
                
                if($request->order_id) {
                    $query->where('order_id', $request->order_id);
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = OrderDelivery::where(function($query) use ($search, $request) {
                if($search) {
                    $query->whereHas('order', function($query) use ($search) {
                            $query->where('number', 'like', "%$search%");
                        })
                        ->orWhere('delivery_order', 'like', "%$search%");
                }   
                
                if($request->order_id) {
                    $query->where('order_id', $request->order_id);
                }

                if($request->start_date && $request->finish_date) {
                    $query->whereDate('created_at', '>=', $request->start_date)
                        ->whereDate('created_at', '<=', $request->finish_date);
                } else if($request->start_date) {
                    $query->whereDate('created_at', $request->start_date);
                } else if($request->finish_date) {
                    $query->whereDate('created_at', $request->finish_date);
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->delivery_order,
                    $val->order->number,
                    $val->order->status(),
                    date('d F Y', strtotime($val->created_at)),
                    '
                        <a href="' . url('admin/delivery_order/retail/print/' . $val->id) . '" class="btn bg-success btn-sm" data-popup="tooltip" title="Print"><i class="icon-printer2"></i></a>
                        <button type="button" class="btn bg-info btn-sm" data-popup="tooltip" title="Information" onclick="information(' . $val->id . ')"><i class="icon-info22"></i></button>
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

    public function information(Request $request)
    {
        $order_delivery = OrderDelivery::find($request->id);
        $order_detail   = [];

        foreach($order_delivery->order->orderDetail as $od) {
            $order_detail[] = [
                'image'   => $od->product->type->image(),
                'product' => $od->product->name(),
                'qty'     => $od->qty
            ];
        }

        return response()->json([
            'order_delivery' => ['code' => $order_delivery->delivery_order, 'status' => $order_delivery->order->status()],
            'order_detail'   => $order_detail,
            'order_shipping' => [
                'name'    => $order_delivery->order->orderShipping->receiver_name,
                'email'   => $order_delivery->order->orderShipping->email,
                'phone'   => $order_delivery->order->orderShipping->phone,
                'city'    => $order_delivery->order->orderShipping->city->name,
                'fleet'   => $order_delivery->order->orderShipping->delivery->transport->fleet,
                'vendor'  => $order_delivery->order->orderShipping->delivery->vendor->name,
                'address' => $order_delivery->order->orderShipping->address
            ]
        ]);
    }

    public function print($id)
    {
        $delivery_order = OrderDelivery::find($id);
        $pdf            = PDF::loadView('admin.pdf.retail.delivery_order', [
            'delivery_order' => $delivery_order
        ]);

        return $pdf->stream('Delivery Order Retail ' . str_replace('/', '-', $delivery_order->delivery_order) . '.pdf');
    }

}
