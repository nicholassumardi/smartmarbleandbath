<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Token;
use App\Models\Voucher;
use App\Models\Customer;
use App\Models\Wishlist;
use App\Jobs\EmailProcess;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerPoint;
use App\Models\ProductReview;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller {

    public function login(Request $request)
    {
        if(session('fo_id')) {
            return redirect('/');
        }

        if($request->has('_token') && session()->token() == $request->_token) {
            $account = Customer::where('email', $request->email)->first();
            if($account) {
                if($account->verification) {
                    if(Hash::check($request->password, $account->password)) {
                        session([
                            'fo_id'           => $account->id,
                            'fo_photo'        => $account->photo(),
                            'fo_name'         => $account->name,
                            'fo_email'        => $account->email,
                            'fo_phone'        => $account->phone,
                            'fo_verification' => $account->verification
                        ]);

                        return redirect('/');
                    } else {
                        return redirect()->back()->with(['failed' => 'Account not found']);
                    }
                } else {
                    return redirect()->back()->with(['info' => 'Account not verified']);
                }
            } else {
                return redirect()->back()->with(['failed' => 'Account not found']);
            }
        } else {
            $data = [
                'title'   => 'Login Customer',
                'content' => 'account.login'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function register(Request $request)
    {
        if(session('fo_id')) {
            return redirect('/');
        }

        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'name'     => 'required|string|min:10|max:50',
                'email'    => 'required|email|unique:customers,email',
                'phone'    => 'required|min:9|numeric',
                'password' => 'required'
            ], [
                'name.required'     => 'Name cannot be empty',
				'name.string'     	=> 'Name must be string',
				'name.min'			=> 'Name min 10',
				'name.max'			=> 'Name max 50',
                'email.required'    => 'Email cannot be empty',
                'email.email'       => 'Email not valid',
                'email.unique'      => 'Email exists',
                'phone.required'    => 'Phone cannot be empty',
                'phone.min'         => 'Phone must be at least 9 characters long',
                'phone.numeric'     => 'Phone must be number',
                'password.required' => 'Password cannot be empty'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $query = Customer::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'phone'    => $request->phone,
                    'password' => bcrypt($request->password),
                    'points'   => 0
                ]);

                $token = Token::create([
                    'tokenable_type' => 'customers',
                    'tokenable_id'   => $query->id,
                    'token'          => Str::random(45),
                    'type'           => 1,
                    'valid'          => date('Y-m-d H:i:s', strtotime('+1 day')),
                    'status'         => 1
                ]);

                $payload = [
                    'name'    => $query->name,
                    'email'   => $query->email,
                    'link'    => url('account/verification?token=' . base64_encode($token->token)),
                    'view'    => 'verification',
                    'subject' => 'SMB | Verification Account'
                ];

                dispatch(new EmailProcess($payload));
                return redirect('account/login')->with(['success' => 'Please check email for account verification, valid 1 day']);
            }
        } else {
            $data = [
                'title'   => 'Registrasi New Customer',
                'content' => 'account.register'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function verification(Request $request)
    {
        session()->flush();
        if(session('fo_id')) {
            return redirect('/');
        }

        $token = base64_decode($request->token);
        $data  = Token::where('token', $token)
            ->where('tokenable_type', 'customers')
            ->where('type', 1)
            ->where('status', 1)
            ->first();

        if($data) {
            if(date('Y-m-d H:i:s') <= $data->valid) {
                Token::find($data->id)->update(['status' => 2]);
                Customer::find($data->tokenable_id)->update(['verification' => date('Y-m-d H:i:s')]);
                return redirect('account/login')->with(['success' => 'Your account is verified']);
            }
        }

        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $account = Customer::where('email', $request->email)->whereNotNull('verification')->first();
        if($account) {
            Token::where('tokenable_type', 'customers')
                ->where('tokenable_id', $account->id)
                ->where('type', 2)
                ->update(['status' => 2]);

            $token = Token::create([
                'tokenable_type' => 'customers',
                'tokenable_id'   => $account->id,
                'token'          => Str::random(45),
                'type'           => 2,
                'valid'          => date('Y-m-d H:i:s', strtotime('+1 day')),
                'status'         => 1
            ]);

            $payload = [
                'name'    => $account->name,
                'email'   => $account->email,
                'link'    => url('account/reset_password?token=' . base64_encode($token->token)),
                'view'    => 'reset_password',
                'subject' => 'SMB | Reset Password'
            ];

            dispatch(new EmailProcess($payload));

            $response = [
                'status'  => true,
                'message' => 'The password reset link has been successfully sent to your email, valid 1 day'
            ];
        } else {
            $response = [
                'status'  => false,
                'message' => 'Email not registered'
            ];
        }

        return response()->json($response);
    }

    public function resetPassword(Request $request)
    {
        $token    = base64_decode($request->token);
        $customer = Token::where('token', $token)
            ->where('tokenable_type', 'customers')
            ->where('type', 2)
            ->where('status', 1)
            ->first();

        if($customer) {
            if($request->has('_token') && session()->token() == $request->_token) {
                $validation = Validator::make($request->all(), [
                    'password'              => 'required',
                    'password_confirmation' => 'required|same:password'
                ], [
                    'password.required'              => 'Password cannot be empty',
                    'password_confirmation.required' => 'Password confirmation cannot be empty',
                    'password_confirmation.same'     => 'Password confirmation not match with password',
                ]);

                if($validation->fails()) {
                    return redirect()->back()->withErrors($validation);
                } else {
                    $query = Customer::find($customer->tokenable_id)->update([
                        'password' => bcrypt($request->password)
                    ]);

                    return redirect('account/login')->with(['success' => 'Password successfully reset']);
                }
            } else {
                $data = [
                    'title'   => 'Reset Password',
                    'content' => 'account.reset_password'
                ];

                return view('layouts.index', ['data' => $data]);
            }
        }

        return redirect('/');
    }

    public function loginSocialMedia(Request $request)
    {
        return Socialite::driver($request->submit)->redirect();
    }

    public function loginSocialMediaCallback(Request $request, $param)
    {
        if($param) {
            if($param == 'google' || $param == 'facebook') {
                $data         = Socialite::driver($param)->user();
                $account      = Customer::where('email', $data->getEmail())->first();
                $verification = date('Y-m-d H:i:s');

                if($account) {
                    if(!$account->verification) {
                        Customer::find($account->id)->update(['verification' => $verification]);
                        $account->verification = $verification;
                    }

                    session([
                        'fo_id'           => $account->id,
                        'fo_photo'        => $account->photo(),
                        'fo_name'         => $account->name,
                        'fo_email'        => $account->email,
                        'fo_verification' => $account->verification
                    ]);
                } else {
                    $account = Customer::create([
                        'photo'        => $data->getAvatar(),
                        'name'         => $data->getName(),
                        'email'        => $data->getEmail(),
                        'verification' => date('Y-m-d H:i:s'),
                        'points'       => 0
                    ]);

                    session([
                        'fo_id'           => $account->id,
                        'fo_photo'        => $account->photo(),
                        'fo_name'         => $account->name,
                        'fo_email'        => $account->email,
                        'fo_verification' => $verification
                    ]);
                }

                return redirect('/');
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }

    public function cart(Request $request)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        $total_cart = 0;
        $cart       = Cart::where('customer_id', session('fo_id'));

        foreach($cart->get() as $c) {
            $total_cart += $c->product->price() * $c->qty;
        }

        $data = [
            'title'      => 'Cart',
            'cart'       => $cart->paginate(10),
            'total_cart' => $total_cart,
            'content'    => 'account.cart'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function wishlist(Request $request)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        $data = [
            'title'    => 'Wishlist',
            'wishlist' => Wishlist::where('customer_id', session('fo_id'))->paginate(10),
            'content'  => 'account.wishlist'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function historyOrder(Request $request)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        $order = Order::where('customer_id', session('fo_id'))
            ->where(function($query) use ($request) {
                    if($request->search) {
                        $query->where('number', 'like', "%$request->search%");
                    }

                    if($request->type) {
                        $query->where('type', $request->type);
                    }
                });

        if($request->status) {
            $order->where('status', $request->status);
        } else {
            $order->whereNotNull('status');
        }

        $data = [
            'title'   => 'History Order',
            'order'   => $order->latest()->paginate(5)->appends($request->except('page')),
            'status'  => $request->status,
            'type'    => $request->type,
            'search'  => $request->search,
            'content' => 'account.history_order'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function historyOrderDetail(Request $request, $id)
    {
        $order_id = base64_decode($id);
        $order    = Order::find($order_id);

        if(!session('fo_id')) {
            return redirect('account/login');
        } else if(!$order) {
            abort(404);
        }

        $data = [
            'title'   => 'Order #' . $order->number,
            'order'   => $order,
            'content' => 'account.history_order_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function historyOrderReview(Request $request)
    {
        $order_detail = OrderDetail::find($request->id);
        $check_review = $order_detail->whereHas('order', function($query) {
                $query->where('customer_id', session('fo_id'));
            })
            ->whereHas('productReview', function($query) {
                $query->where('customer_id', session('fo_id'));
            })
            ->first();

        if($check_review) {
            $product_review = ProductReview::where('order_detail_id', $order_detail->id)->first();
        } else {
            $product_review = null;
        }

        if($request->header('x-csrf-token') && $request->header('x-csrf-token') == csrf_token()) {
            if($check_review) {
                $query = ProductReview::find($product_review->id)->update([
                    'review' => $request->review,
                    'rate'   => $request->rate
                ]);
            } else {
                $query = ProductReview::create([
                    'order_detail_id' => $order_detail->id,
                    'product_id'      => $order_detail->product_id,
                    'customer_id'     => session('fo_id'),
                    'review'          => $request->review,
                    'rate'            => $request->rate
                ]);
            }

            if($query) {
                $response = [
                    'status' => 200,
                    'message' => 'Your review has been saved'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Your review failed to saved'
                ];
            }
        } else {
            if($check_review) {
                $rate   = $product_review->rate;
                $review = $product_review->review;
            } else {
                $rate   = null;
                $review = null;
            }

            $response = [
                'rate'   => $rate,
                'review' => $review
            ];
        }

        return response()->json($response);
    }

    public function confirmationDelivery(Request $request)
    {
        $query = Order::find($request->id)->update(['status' => 4]);
        if($query) {
            $response = [
                'status'  => 200,
                'message' => 'Order has been arrived.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data failed to delete.'
            ];
        }

        return response()->json($response);
    }

    public function profile(Request $request)
    {
        $profile = Customer::find(session('fo_id'));
        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'photo'    => 'mimes:jpg,jpeg,png|max:500',
                'name'     => 'required',
                'email'    => ['required', 'email', Rule::unique('customers', 'email')->ignore($profile->id)],
                'phone'    => 'required|min:9|numeric'
            ], [
                'photo.mimes'       => 'Type photo must be jpg, jpeg or png',
                'photo.max'         => 'Photo max 500KB',
                'name.required'     => 'Name cannot be empty',
                'email.required'    => 'Email cannot be empty',
                'email.email'       => 'Email not valid',
                'email.unique'      => 'Email exists',
                'phone.required'    => 'Phone cannot be empty',
                'phone.min'         => 'Phone must be at least 9 characters long',
                'phone.numeric'     => 'Phone must be number'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                if($request->has('photo')) {
                    if(Storage::exists($profile->photo)) {
                        Storage::delete($profile->photo);
                    }

                    $photo = $request->file('photo')->store('public/customer');
                } else {
                    $photo = $profile->photo;
                }

                $profile->update([
                    'photo'    => $photo,
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'phone'    => $request->phone
                ]);

                session([
                    'fo_id'           => $profile->id,
                    'fo_photo'        => $profile->photo(),
                    'fo_name'         => $profile->name,
                    'fo_email'        => $profile->email,
                    'fo_phone'        => $profile->phone,
                    'fo_verification' => $profile->verification
                ]);

                return redirect()->back()->with(['success' => 'Profile successfully updated']);
            }
        } else {
            $data = [
                'title'   => 'Edit Profile',
                'profile' => $profile,
                'content' => 'account.profile'
            ];

            return view('layouts.index', ['data' => $data]);
        }
    }

    public function voucher(Request $request)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        $voucher = Voucher::where(function($query) use ($request) {
                if($request->type == 'used') {
                    $query->whereExists(function($query) {
                            $query->selectRaw(1)
                                ->from('orders')
                                ->where('orders.customer_id', session('fo_id'))
                                ->where('status', '!=', 6)
                                ->where('customer_id', session('fo_id'))
                                ->whereColumn('orders.voucher_id', 'vouchers.id');
                        });
                } else if($request->type == 'will_be_end') {
                    $query->whereDate('start_date', '>=', date('Y-m-d'))
                        ->whereDate('finish_date', '=', date('Y-m-d'));

                    $query->whereHas('order', function($query) {
                            $query->where('status', '!=', 6)
                                ->havingRaw('COUNT(*) <= quota');
                        });
                } else if($request->type == 'expired') {
                    $query->whereDate('finish_date', '<', date('Y-m-d'))
                        ->whereNotExists(function($query) {
                            $query->selectRaw(1)
                                ->from('orders')
                                ->where('orders.customer_id', session('fo_id'))
                                ->whereColumn('orders.voucher_id', 'vouchers.id');
                        })
                        ->whereHas('order', function($query) {
                            $query->where('status', '!=', 6)
                                ->havingRaw('COUNT(*) <= quota');
                        });
                } else {
                    $query->whereNotExists(function($query) {
                            $query->selectRaw(1)
                                ->from('orders')
                                ->where('orders.customer_id', session('fo_id'))
                                ->whereColumn('orders.voucher_id', 'vouchers.id');
                        })
                        ->whereHas('order', function($query) {
                            $query->where('status', '!=', 6)
                                ->havingRaw('COUNT(*) <= quota');
                        });
                }
            });

        $data = [
            'title'   => 'Voucher',
            'voucher' => $voucher->paginate(10),
            'type'    => $request->type,
            'content' => 'account.voucher'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function points(Request $request)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        $data = [
            'title'   => 'Points',
            'points'  => CustomerPoint::where('customer_id', session('fo_id'))->paginate(10),
            'type'    => $request->type,
            'content' => 'account.points'
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
