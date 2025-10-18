<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderPo;
use App\Models\Customer;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\CustomerPoint;
use App\Models\OrderDelivery;
use App\Http\Controllers\Controller;

class OrderInvoiceController extends Controller {

    public function index()
    {
        $data = [
            'title'    => 'Invoice Retail',
            'customer' => Customer::whereNotNull('verification')->get(),
            'content'  => 'admin.invoice.retail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'customer_id',
            'invoice',
            'payment',
            'grandtotal',
            'change',
            'status',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Order::whereNotNull('invoice')
            ->count();
        
        $query_data = Order::whereNotNull('invoice')
            ->where(function($query) use ($search, $request) {
                if($search) {
                    $query->whereHas('customer', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhere('invoice', 'like', "%$search%");
                }   
                
                if($request->customer_id) {
                    $query->where('customer_id', $request->customer_id);
                }

                if($request->nominal) {
                    if($request->nominal == 1) {
                        $query->where('grandtotal', '<=', 999999);
                    } else if($request->nominal == 2) {
                        $query->where(function($query) {
                                $query->where('grandtotal', '>', 999999)
                                    ->where('grandtotal', '<=', 999999999);
                            });
                    } else if($request->nominal == 3) {
                        $query->where(function($query) {
                                $query->where('grandtotal', '>', 999999999)
                                    ->where('grandtotal', '<=', 999999999999);
                            });
                    }
                }

                if($request->status) {
                    if($request->status == 'unpaid') {
                        $query->where('payment', 0)->orWhereNull('payment');
                    } else {
                        $query->whereRaw('payment >= grandtotal');
                    }
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

        $total_filtered = Order::whereNotNull('invoice')
            ->where(function($query) use ($search, $request) {
                if($search) {
                    $query->whereHas('customer', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhere('invoice', 'like', "%$search%");
                }   
                
                if($request->customer_id) {
                    $query->where('customer_id', $request->customer_id);
                }

                if($request->nominal) {
                    if($request->nominal == 1) {
                        $query->where('grandtotal', '<=', 999999);
                    } else if($request->nominal == 2) {
                        $query->where(function($query) {
                                $query->where('grandtotal', '>', 999999)
                                    ->where('grandtotal', '<=', 999999999);
                            });
                    } else if($request->nominal == 3) {
                        $query->where(function($query) {
                                $query->where('grandtotal', '>', 999999999)
                                    ->where('grandtotal', '<=', 999999999999);
                            });
                    }
                }

                if($request->status) {
                    if($request->status == 'unpaid') {
                        $query->where('payment', 0)->orWhereNull('payment');
                    } else {
                        $query->whereRaw('payment >= grandtotal');
                    }
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
				if(isset($val->customer->name)){
				
					if($val->payment == 0 || $val->payment == null) {
						$status = 'Unpaid';
						$btn    = '<a href="' . url('admin/invoice/retail/detail/' . $val->id) . '" class="btn bg-info btn-sm"><i class="icon-info22"></i> Process</a>';
					} else if($val->payment >= $val->grandtotal) {
						$status = 'Full Payment';
						$btn    = '<a href="' . url('admin/invoice/retail/detail/' . $val->id) . '" class="btn bg-success btn-sm"><i class="icon-check"></i> View</a>';
					} else {
						$status = 'Down Payment';
						$btn    = '<a href="' . url('admin/invoice/retail/detail/' . $val->id) . '" class="btn bg-success btn-sm"><i class="icon-check"></i> View</a>';
					}

					if($val->payment > $val->grandtotal) {
						$change = $val->payment - $val->grandtotal;
					} else {
						$change = 0;
					}
					
					$response['data'][] = [
						$nomor,
						$val->customer->name,
						$val->invoice,
						'Rp ' . number_format($val->payment, 2, ',', '.'),
						'Rp ' . number_format($val->grandtotal, 2, ',', '.'),
						'Rp ' . number_format($val->change, 2, ',', '.'),
						$status,
						date('d F Y', strtotime($val->created_at)),
						$btn
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

    public function detail(Request $request, $id) 
    {
        $order = Order::find($id);
        if(!$order) {
            abort(404);
        }
        
        if($request->has('_token') && session()->token() == $request->_token) {
            if($request->payment) {
                if($order->status == 1 || $order->status == 5) {
                    $status = 2;
                    
                    if($order->voucher) {
                        if($order->voucher->points > 0) {
                            $pointable = $order->customer->points;
                            $order->customer->update(['points' => $pointable + $order->voucher->points]);
                            
                            CustomerPoint::create([
                                'customer_id' => $order->customer_id,
                                'order_id'    => $order->id,
                                'points'      => $order->voucher->points
                            ]);
                        }
                    }

                    OrderPo::create([
                        'order_id'       => $order->id,
                        'purchase_order' => OrderPo::generateCode(),
                        'status'         => 1
                    ]);

                    OrderPayment::create([
                        'order_id' => $order->id,
                        'method'   => 'Cash',
                        'channel'  => 'Smart Marble'
                    ]);
                } else {
                    $status = $order->status;
                }
            } else {
                $status = 1;
            }

            $order->update([
                'payment' => $request->payment,
                'status'  => $status
            ]);

            return redirect()->back()->with(['success' => 'Data has been processed!']);
        }

        $data  = [
            'title'   => 'Detail Invoice Retail',
            'brand'   => Brand::whereIn('code', ['TR', 'FI', 'SM', 'BT'])->get(),
            'order'   => $order,
            'content' => 'admin.invoice.retail_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function print($id)
    {
        $order = Order::find($id);
        $pdf   = PDF::loadView('admin.pdf.retail.invoice', [
            'order' => $order
        ]);

        return $pdf->stream('Invoice Retail ' . str_replace('/', '-', $order->invoice) . '.pdf');
    }

}
