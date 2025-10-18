<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPo;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\CustomerPoint;

class WebHookController extends Controller {
    
    public function xendit(Request $request)
    {
        $order     = Order::where('number', $request->external_id)->first();
        $pointable = $order->customer->points;

        if($request->status == 'PAID') {
            Order::find($order->id)->update([
                'invoice' => Order::generateCode('INV', 'invoice'),
                'payment' => $request->amount,
                'status'  => 2
            ]);

            OrderPo::create([
                'order_id'       => $order->id,
                'purchase_order' => OrderPo::generateCode(),
                'status'         => 1
            ]);

            OrderPayment::create([
                'order_id' => $order->id,
                'method'   => $request->payment_method,
                'channel'  => $request->payment_channel
            ]);

            if($order->voucher) {
                if($order->voucher->points > 0) {
                    $order->customer->update(['points' => $pointable + $order->voucher->points]);
                    CustomerPoint::create([
                        'customer_id' => $order->customer_id,
                        'order_id'    => $order->id,
                        'points'      => $order->voucher->points
                    ]);
                }
            }
        } else if($request->status == 'PENDING') {
            Order::where('number', $request->external_id)->update([
                'status' => 1
            ]);
        } else if($request->status == 'EXPIRED') {
            Order::where('number', $request->external_id)->update([
                'status' => 5
            ]);

            CustomerPoint::where('customer_id', $order->customer_id)->where('order_id', $order->id)->delete();
            if($order->points > 0) {
                $restore_points = $pointable + $order->points;
                $order->customer->update(['points' => $restore_points]);
            }
        }
    }

}
