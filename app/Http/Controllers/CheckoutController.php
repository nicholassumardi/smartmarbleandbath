<?php

namespace App\Http\Controllers;

use QrCode;
use Xendit\Invoice;
use App\Models\Cart;
use App\Models\City;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\Customer;
use App\Models\Delivery;
use App\Jobs\EmailProcess;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerPoint;
use App\Models\OrderShipping;
use App\Models\ProductShading;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller {
    
    public function index(Request $request)
    {
        $param    = $request->param;
        $customer = Customer::find(session('fo_id'));

        if(!session('fo_id') || !$customer) {
            return redirect('account/login');
        } else if($customer->cart->count() < 1) {
            return redirect('product');
        }

        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'receiver_name' => 'required',
                'email'         => 'required|email',
                'phone'         => 'required|min:9|numeric',
                'city_id'       => 'required',
                'address'       => 'required',
                'delivery_id'   => 'required'
            ], [
                'receiver_name.required' => 'Receiver name cannot be empty.',
                'email.required'         => 'Email cannot be empty.',
                'email.email'            => 'Email not valid.',
                'phone.required'         => 'Phone cannot be empty',
                'phone.min'              => 'Phone must be at least 9 characters long',
                'phone.numeric'          => 'Phone must be number',
                'city_id.required'       => 'Please select a city.',
                'address.required'       => 'Address cannot be empty.',
                'delivery_id.required'   => 'Please select a transport.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $total_checkout = 0;
                $total_weight   = 0;
                $param_code     = $param == 'cash' ? 'CH' : 'CS';
                $order          = Order::create([
                    'customer_id' => session('fo_id'),
                    'number'      => Order::generateNumber($param_code),
                    'invoice'     => $param == 'cash' ? Order::generateCode('INV', 'invoice') : null,
                    'description' => $request->description,
                    'type'        => $param == 'cash' ? 1 : 2,
                    'status'      => 1
                ]);

                foreach($customer->cart as $c) {
                    $formula         = $c->product->cogs;
                    $pricing         = $c->product->pricingPolicy;
                    $showroom_cost   = $pricing ? $pricing->showroom_cost : 0;
                    $marketing_cost  = $pricing ? $pricing->marketing_cost : 0;
                    $bottom_price    = $pricing ? $pricing->bottom_price : 0;
                    $fixed_cost      = $pricing ? $pricing->fixed_cost : 0;
                    $cogs_idr        = $formula ? $formula->cogs_idr : 0;
                    $cogs_pta_idr    = $formula ? $formula->cogs_pta_idr : 0;
                    $cogs_smb_idr    = $formula ? $formula->cogs_smb_idr : 0;
                    $subtotal        = $c->product->price() * $c->qty;
                    $total_checkout += $subtotal;
                    $total_weight   += $c->product->type->weight * $c->qty;
                    
                    OrderDetail::create([
                        'order_id'         => $order->id,
                        'product_id'       => $c->product_id,
                        'showroom_cost'    => $showroom_cost,
                        'marketing_cost'   => $marketing_cost,
                        'bottom_price'     => $bottom_price,
                        'fixed_cost'       => $fixed_cost,
                        'price_list'       => $c->product->price(),
                        'target_price'     => $subtotal,
                        'cogs_perwira'     => $cogs_pta_idr,
                        'cogs_smartmarble' => $cogs_smb_idr,
                        'profit'           => $subtotal - $cogs_idr,
                        'qty'              => $c->qty,
                        'total'            => $subtotal
                    ]);
                }

                $voucher    = Voucher::where('code', $request->voucher_id)->first();
                $discount   = 0;
                $voucher_id = $voucher ? $voucher->id : null;

                if($request->pointable) {
                    $pointable = $customer->points;
                    $customer->update(['points' => $customer->points - $pointable]);
                    
                    CustomerPoint::create([
                        'customer_id' => $customer->id,
                        'order_id'    => $order->id,
                        'points'      => -1 * $pointable
                    ]);
                } else {
                    $pointable = 0;
                }

                if($voucher) {
                    $current_date = strtotime(date('Y-m-d'));
                    $check_used   = Order::where('voucher_id', $voucher_id)
                        ->where('customer_id', $customer->id)
                        ->where('status', '!=', 6)
                        ->count();
                    
                    if($check_used < 1) {
                        if($current_date >= strtotime($voucher->start_date) && $current_date <= strtotime($voucher->finish_date)) {
                            if($total_checkout >= $voucher->minimum) {
                                $total_discount = ($voucher->percentage / 100) * $total_checkout;
                                if($total_discount <= $voucher->maximum) {
                                    $discount = $total_discount;
                                } else {
                                    $discount = $voucher->maximum;
                                }
                            }
                        }
                    }
                }

                Cart::where('customer_id', session('fo_id'))->delete();
                Order::find($order->id)->update([
                    'voucher_id' => $voucher_id,
                    'discount'   => $discount,
                    'points'     => $pointable,
                    'subtotal'   => $total_checkout,
                    'grandtotal' => $total_checkout - ($discount + $pointable)
                ]);

                $delivery     = Delivery::find($request->delivery_id);
                $shipping_fee = $delivery->price_per_kg * $total_weight;
                $grandtotal   = ($total_checkout - ($discount + $pointable)) + $shipping_fee;

                OrderShipping::create([
                    'order_id'      => $order->id,
                    'city_id'       => $request->city_id,
                    'delivery_id'   => $request->delivery_id,
                    'receiver_name' => $request->receiver_name,
                    'email'         => $request->email,
                    'phone'         => $request->phone,
                    'address'       => $request->address
                ]);

                Order::find($order->id)->update([
                    'shipping'   => $shipping_fee,
                    'grandtotal' => $grandtotal
                ]);

                if($param == 'cash') {
                    $generate = QrCode::format('png')->merge('/public/website/icon.png')->size(200)->generate($order->number);
                    $qr_code  = 'public/order/SMB-QrCode-' . str_replace('/', '', $order->number) . '.png';

                    Storage::put($qr_code, $generate);
                    Order::find($order->id)->update(['qr_code' => $qr_code]);

                    $payload = [
                        'email'   => $customer->email,
                        'name'    => $customer->name,
                        'order'   => Order::find($order->id),
                        'link'    => url('account/history_order/detail/' . base64_encode($order->id)),
                        'view'    => 'order_cash',
                        'subject' => 'SMB | Order ' . $order->number
                    ];

                    dispatch(new EmailProcess($payload));
                    return redirect('checkout/notif/success?number=' . base64_encode($order->number));
                } else {
                    $param_invoice = [
                        'external_id'          => $order->number,
                        'payer_email'          => $request->email,
                        'description'          => $request->description ? $request->description : 'No Description',
                        'amount'               => $grandtotal,
                        'should_send_email'    => true,
                        'success_redirect_url' => url('checkout/notif/success?number=' . base64_encode($order->number)),
                        'failure_redirect_url' => url('checkout/notif/failed?number=' . base64_encode($order->number)),
                        'currency'             => 'IDR'
                    ];

                    $generate_invoice = Invoice::create($param_invoice);
                    Order::find($order->id)->update([
                        'xendit' => json_encode([
                            'id'  => $generate_invoice['id'], 
                            'url' => $generate_invoice['invoice_url']
                        ])
                    ]);

                    return redirect($generate_invoice['invoice_url']);
                }
            }
        } else {
            $data = [
                'title'    => 'Checkout',
                'customer' => $customer,
                'city'     => City::orderBy('name', 'asc')->get()
            ];

            return view('checkout', $data);
        }
    }

    public function getDelivery(Request $request)
    {
        $data     = [];
        $city_id  = $request->city_id;
        $weight   = (double)$request->weight;
        $delivery = Delivery::where('destination_id', $city_id)
            ->where('capacity', '>=', $weight)
            ->orderBy('capacity', 'asc')
            ->groupBy('transport_id')
            ->get();

        foreach($delivery as $d) {
            $data[] = [
                'id'             => $d->id,
                'price'          => 'Rp ' . number_format($d->price_per_kg * $weight, '0', ',', '.'),
                'transport_name' => $d->transport->fleet
            ];
        }

        return response()->json($data);
    }

    public function grandtotal(Request $request)
    {
        $subtotal     = (double)$request->subtotal;
        $weight       = (double)$request->weight;
        $delivery     = Delivery::find($request->delivery_id);
        $shipping_fee = $delivery ? $delivery->price_per_kg * $weight : 0;
        $transport    = $delivery ? $delivery->transport->fleet : 'Not Selected';
        $grandtotal   = $subtotal + $shipping_fee;
        $voucher      = Voucher::where('code', $request->voucher_id)->first();
        $voucher_id   = $voucher ? $voucher->id : null;
        $discount     = 0;
        $voucher_name = '';
        $voucher_type = '';
        $notif_type   = 'warning';
        $notif_text   = 'Voucher cannot be used';
        $code         = 500;
        $pointable    = $request->pointable ? Customer::find(session('fo_id'))->points : 0;

        if($voucher) {
            $current_date = strtotime(date('Y-m-d'));
            $check_used   = Order::where('voucher_id', $voucher_id)
                ->where('customer_id', session('fo_id'))
                ->where('status', '!=', 6)
                ->count();

            if($check_used < 1) {
                if($current_date < strtotime($voucher->start_date)) {
                    $notif_type = 'warning';
                    $notif_text = 'Voucher cannot be used';
                    $code       = 500;
                } else if($current_date > strtotime($voucher->finish_date)) {
                    $notif_type = 'warning';
                    $notif_text = 'Voucher has expired';
                    $code       = 419;
                } else if($current_date >= strtotime($voucher->start_date) && $current_date <= strtotime($voucher->finish_date)) {
                    if($subtotal >= $voucher->minimum) {
                        $voucher_name   = $voucher->name;
                        $total_discount = ($voucher->percentage / 100) * $subtotal;

                        if($total_discount <= $voucher->maximum) {
                            $discount = $total_discount;
                        } else {
                            $discount = $voucher->maximum;
                        }

                        if($voucher->type == 1) {
                            $voucher_type = 'Congrats you get 20% discount for purchases.';
                        } else {
                            $voucher_type = 'Congrats you get 20% discount for shipping.';
                        }

                        $notif_type   = 'success';
                        $notif_text   = 'Voucher used successfully';
                        $code         = 200;
                    } else {
                        $notif_type = 'info';
                        $notif_text = 'Your total order is less than the minimum order voucher requirement';
                        $code       = 500;
                    }
                }
            } else {
                $notif_type = 'info';
                $notif_text = 'Voucher has been used';
                $code       = 404;
            }
        } else {
            $notif_type = 'info';
            $notif_text = 'Voucher not found';
            $code       = 404;
        }

        return response()->json([
            'notif_type'   => $notif_type,
            'notif_text'   => $notif_text,
            'code'         => $code,
            'voucher'      => $voucher_name,
            'discount'     => '- Rp ' . number_format($discount, 0, ',', '.'),
            'notification' => $voucher_type,
            'transport'    => $transport,
            'shipping_fee' => 'Rp ' . number_format($shipping_fee, '0', ',', '.'),
            'grandtotal'   => 'Rp ' . number_format($grandtotal - ($discount + $pointable), '0', ',', '.')
        ]);
    }

    public function notif(Request $request, $param)
    {
        $number = base64_decode($request->number);
        $order  = Order::where('number', $number)->first();

        if(!session('fo_id')) {
            return redirect('account/login');
        } else if(!$order) {
            abort(404);
        }

        $data = [
            'title'   => 'Order Notification',
            'order'   => $order,
            'content' => $param
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
