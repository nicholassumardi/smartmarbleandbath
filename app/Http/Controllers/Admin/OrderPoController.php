<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderPo;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\OrderDelivery;
use App\Http\Controllers\Controller;

class OrderPoController extends Controller {

    public function index()
    {
        $data = [
            'title'   => 'PO Retail',
            'content' => 'admin.purchase_order.retail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'order_id',
            'vendor',
            'purchase_order',
            'grandtotal',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = OrderPo::count();
        
        $query_data = OrderPo::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('purchase_order', 'like', "%$search%")
                        ->orWhereHas('order', function($query) use ($search) {
                            $query->where('number', 'like', "%$search%");
                        });
                }   

                if($request->status) {
                    $query->where('status', $request->status);
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

        $total_filtered = OrderPo::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('purchase_order', 'like', "%$search%")
                        ->orWhereHas('order', function($query) use ($search) {
                            $query->where('number', 'like', "%$search%");
                        });
                }   

                if($request->status) {
                    $query->where('status', $request->status);
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
                $bottom_price = $val->order->orderDetail->sum('bottom_price') * $val->order->orderDetail->sum('qty');
                $grandtotal   = $bottom_price + $val->order->shipping;

                if($val->status == 1) {
                    $btn = '<a href="' . url('admin/purchase_order/retail/detail/' . $val->id) . '" class="btn bg-info btn-sm"><i class="icon-info22"></i> Process</a>';
                } else {
                    $btn = '<a href="' .url('admin/purchase_order/retail/detail/' . $val->id) . '" class="btn bg-success btn-sm"><i class="icon-check"></i> View</a>';
                }

                $response['data'][] = [
                    $nomor,
                    $val->order->number,
                    'Karya Modern',
                    $val->purchase_order,
                    'Rp ' . number_format($grandtotal, 2, ',', '.'),
                    $val->status(),
                    date('d F Y', strtotime($val->created_at)),
                    $btn
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

    public function detail(Request $request, $id) 
    {
        $purchase_order = OrderPo::find($id);
        if(!$purchase_order) {
            abort(404);
        }

        $order    = Order::find($purchase_order->order_id);
        $shipping = 0;

        if($request->has('_token') && session()->token() == $request->_token) {
            $purchase_order->update([
                'status' => $request->has('status') ? $request->status : 1
            ]);

            if($request->has('status')) {
                OrderDelivery::create([
                    'order_id'       => $purchase_order->order_id,
                    'delivery_order' => OrderDelivery::generateCode()
                ]);

                $order->update(['status' => 3]);
            }

            foreach($request->order_detail_id as $key => $odi) {
                OrderDetail::find($odi)->update([
                    'bottom_price' => $request->order_detail_bottom_price[$key]
                ]);
            }

            return redirect()->back()->with(['success' => 'Data has been processed!']);
        }

        if($order->voucher) {
            if($order->voucher->type == 2) {
                $total_discount = ($voucher->percentage / 100) * $order->shipping;
                if($total_discount >= $order->shipping || $total_discount < 0 || $total_discount > $order->voucher->maximum) {
                    $shipping = $order->shipping;
                } else {
                    $shipping = $total_discount;
                }
            }
        }

        $data = [
            'title'          => 'Detail PO Retail',
            'purchase_order' => $purchase_order,
            'shipping'       => $order->shipping - $shipping,
            'discount'       => $shipping,
            'brand'          => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT'])->get(),
            'content'        => 'admin.purchase_order.retail_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function print($id)
    {
        $purchase_order = OrderPo::find($id);
        $order          = Order::find($purchase_order->order_id);
        $shipping       = 0;

        if($order->voucher) {
            if($order->voucher->type == 2) {
                $total_discount = ($voucher->percentage / 100) * $order->shipping;
                if($total_discount >= $order->shipping || $total_discount < 0 || $total_discount > $order->voucher->maximum) {
                    $shipping = $order->shipping;
                } else {
                    $shipping = $total_discount;
                }
            }
        }


        $pdf = PDF::loadView('admin.pdf.retail.purchase_order', [
            'purchase_order' => $purchase_order,
            'shipping'       => $order->shipping - $shipping,
            'discount'       => $shipping
        ]);

        return $pdf->stream('Purchase Order Retail ' . str_replace('/', '-', $purchase_order->purchase_order) . '.pdf');
    }

}
