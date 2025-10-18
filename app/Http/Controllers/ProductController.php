<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Type;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Pattern;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductShading;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller {

    public function index(Request $request)
    {
        $size = Type::select('length', 'width')
            ->where([
                ['length', '!=', null],
                ['length', '>', 0]
            ])
            ->where([
                ['width', '!=', null],
                ['width', '>', 0]
            ])
            ->where('status', 1)
            ->groupBy('length', 'width')
            ->get();

        if($request->category) {
            if(is_array($request->category)) {
                foreach($request->category as $c) {
                    $filter['category'][] = $c;
                }
            } else {
                $filter['category'][] = $request->category;
            }
        } else {
            $filter['category'] = [];
        }

        if($request->brand) {
            if(is_array($request->brand)) {
                foreach($request->brand as $b) {
                    $filter['brand'][] = $b;
                }
            } else {
                $filter['brand'][] = $request->brand;
            }
        } else {
            $filter['brand'] = [];
        }

        if($request->size) {
            if(is_array($request->size)) {
                foreach($request->size as $s) {
                    $explode                    = explode('x', $s);
                    $filter['size'][]           = $s;
                    $filter['size']['length'][] = $explode[0];
                    $filter['size']['width'][]  = $explode[1];
                }
            } else {
                $filter['size'][] = $request->size;
            }
        } else {
            $filter['size'] = [];
        }

        if($request->color) {
            if(is_array($request->color)) {
                foreach($request->color as $c) {
                    $filter['color'][] = $c;
                }
            } else {
                $filter['color'][] = $request->color;
            }
        } else {
            $filter['color'] = [];
        }

        if($request->pattern) {
            if(is_array($request->pattern)) {
                foreach($request->pattern as $p) {
                    $filter['pattern'][] = $p;
                }
            } else {
                $filter['pattern'][] = $request->pattern;
            }
        } else {
            $filter['pattern'] = [];
        }

        if($request->search) {
            $filter['other']['search'] = $request->search;
        } else {
            $filter['other']['search'] = null;
        }

        if($request->show) {
            $filter['other']['show'] = $request->show;
        } else {
            $filter['other']['show'] = 24;
        }

        if($request->stock) {
            $filter['other']['stock'] = $request->stock;
        } else {
            $filter['other']['stock'] = null;
        }

        if($request->sort) {
            $filter['other']['sort'] = $request->sort;
        } else {
            $filter['other']['sort'] = null;
        }
		
		/* $product = Product::whereHas('type', function($query) {
				$query->where('show_in_store','1');
			}); */

        $product = Product::where(function($query) use ($filter) {
                if($filter['other']['search']) {
                    $search = $filter['other']['search'];
                    $query->whereHas('type', function($query) use ($search) {
                            $query->whereRaw("MATCH(code) AGAINST('$search' IN BOOLEAN MODE)")
                                ->orWhereHas('category', function($query) use ($search) {
                                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                                    })
                                ->orWhereHas('color', function($query) use ($search) {
                                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                                    });
                        })
                        ->orWhereHas('brand', function($query) use ($search) {
                                $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                            })
                        ->orWhereHas('country', function($query) use ($search) {
                                $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                            });
                }

                if($filter['other']['stock']) {
                    $query->whereHas('productShading', function($query) use ($filter) {
                            if($filter['other']['stock'] == 'ready') {
                                $query->havingRaw('SUM(qty) > ?', [18]);
                            } else if($filter['other']['stock'] == 'limited') {
                                $query->havingRaw('SUM(qty) > ?', [0])
                                    ->havingRaw('SUM(qty) <= ?', [18]);
                            }
                        });
                }

                if($filter['category']) {
                    $query->whereHas('type', function($query) use ($filter) {
                            $query->whereHas('category', function($query) use ($filter) {
                                    $query->whereIn('slug', $filter['category']);
                                });
                        });
                }

                if($filter['size']) {
                    $query->whereHas('type', function($query) use ($filter) {
                            $query->whereIn('length', $filter['size']['length']);
                            $query->whereIn('width', $filter['size']['width']);
                        });
                }

                if($filter['brand']) {
                    $query->whereHas('brand', function($query) use ($filter) {
                            $query->whereIn('code', $filter['brand']);
                        });
                }

                if($filter['color']) {
                    $query->whereHas('type', function($query) use ($filter) {
                            $query->whereHas('color', function($query) use ($filter) {
                                    $query->whereIn('code', $filter['color']);
                                });
                        });
                }

                if($filter['pattern']) {
                    $query->whereHas('type', function($query) use ($filter) {
                            $query->whereHas('pattern', function($query) use ($filter) {
                                    $query->whereIn('code', $filter['pattern']);
                                });
                        });
                }
            })
			->whereHas('type', function($query) {
				$query->where('show_in_store','1');
			})
            ->where('status', 1);
            /* ->whereHas('productShading', function($query) {
                    $query->havingRaw('SUM(qty) > ?', [0]);
                }); */

        if($filter['other']['sort']) {
            if($filter['other']['sort'] == 'latest') {
                $product->latest();
            } else {
                $product->select('products.*', 'pricing_policies.price_list')
                    ->leftJoin('pricing_policies', 'products.id', '=', 'pricing_policies.product_id')
                    ->orderBy('price_list', $filter['other']['sort'] == 'low_to_high' ? 'asc' : 'desc');
            }
        }
		
		

        $data = [
            'title'    => 'Product',
            'brand'    => Brand::where('status', 1)->get(),
            'category' => Category::where('type', 1)->where('parent_id', 0)->where('status', 1)->orderBy('name', 'desc')->get(),
            'color'    => Color::where('status', 1)->get(),
            'pattern'  => Pattern::where('status', 1)->get(),
            'size'     => $size,
            'product'  => $product->groupBy('products.id')->paginate($filter['other']['show'])->appends($request->except('page')),
            'filter'   => $filter,
            'content'  => 'product'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function detail(Request $request, $param)
    {
        $product_id = base64_decode($request->q);
        $product    = Product::find($product_id);
        $slug       = Str::slug($product->name());

        if(!$product) {
            abort(404);
        } else if($param != $slug) {
            abort(404);
        }

        $voucher = Voucher::where(function($query) use ($product) {
                $query->where(function($query) {
                        $query->whereDate('start_date', '>=', date('Y-m-d'))
                            ->whereDate('finish_date', '<=', date('Y-m-d'));
                    })
                    ->orWhere(function($query) use ($product) {
                        $query->where('voucherable_type', 'brands')
                            ->where('voucherable_id', $product->brand_id);
                    })
                    ->orWhere(function($query) use ($product) {
                        $query->where('voucherable_type', 'categories')
                            ->where('voucherable_id', $product->type->category_id);
                    })
                    ->orWhere(function($query) use ($product) {
                        $query->whereNull('voucherable_type')
                            ->whereNull('voucherable_id');
                    });
            })
            ->whereHas('order', function($query) {
                $query->where('status', '!=', 6)
                    ->havingRaw('COUNT(*) <= quota');
            })
            ->get();

        $related_product = Product::where('id', '!=', $product_id)
            ->where(function($query) {
                    $query->whereHas('productShading', function($query) {
                            $query->havingRaw('SUM(qty) > ?', [0]);
                        });
                })
            ->where(function($query) use ($product) {
                $query->where('brand_id', $product->brand_id)
                    ->orWhereHas('type', function($query) use ($product) {
                            $query->where('type_id', $product->type->category_id)
                                ->orWhere('category_id', $product->type->category_id)
                                ->orWhere(function($query) use ($product) {
                                        $query->where('length', $product->type->length)
                                            ->where('width', $product->type->width);
                                    });
                        });
            })
			->whereHas('type', function($query) {
				$query->where('show_in_store','1');
			})
            ->where('status', 1)
            ->limit(9)
            ->inRandomOrder()
            ->get();

        $product_grade = Product::where('type_id', $product->type_id)
            ->with(['grade' => function($query) {
                return $query->orderBy('name', 'asc');
            }])
            ->where('status', 1)
            ->get();

        $data = [
            'title'            => $product->name(),
            'product'          => $product,
            'voucher'          => $voucher,
            'related_product'  => $related_product,
            'product_grade'    => $product_grade,
            'meta_title'       => $product->name(),
            'meta_description' => $product->description,
            'meta_image'       => $product->type->image(),
            'meta_brand'       => $product->brand->name,
            'meta_category'    => $product->type->category->name,
            'meta_stock'       => $product->availability()->stock > 0 ? 'in stock' : 'out of stock',
            'meta_price'       => $product->price(),
            'meta_id'          => $request->q,
            'content'          => 'product_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function checkStock(Request $request)
    {
        $product_id    = base64_decode($request->product_id);
        $data_shading  = ProductShading::where('product_id', $product_id)->orderBy('qty', 'asc')->get();
        $total_stock   = ProductShading::where('product_id', $product_id)->sum('qty');
        $total_request = abs($request->qty);
        $shading       = [];

        foreach($data_shading as $ds) {
            $minus     = $ds->qty - $total_request;
            $shading[] = [
                'shading'       => $ds->code,
                'initial_stock' => $ds->qty,
                'last_stock'    => $minus > 0 ? $minus : 0
            ];

            if($total_request > 0) {
                $ds->qty - $total_request;
            }
        }

        return response()->json([
            'total_stock'   => $total_stock,
            'total_request' => $total_request,
            'data_shading'  => $shading
        ]);
    }

    public function addToCart(Request $request)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        $product_id = base64_decode($request->product_id);
        $product    = Product::find($product_id);
        $cart       = Cart::where('customer_id', session('fo_id'))
            ->where('product_id', $product_id)
            ->first();

        if($request->qty > 0 && $request->qty <= $product->availability()->stock) {
            if($cart) {
                Cart::where('customer_id', session('fo_id'))
                    ->where('product_id', $product_id)
                    ->update(['qty' => $cart->qty + $request->qty]);
            } else {
                Cart::create([
                    'customer_id' => session('fo_id'),
                    'product_id'  => $product_id,
                    'qty'         => $request->qty
                ]);
            }
        }

        return redirect()->back();
    }

    public function cartQty(Request $request)
    {
        if(!session('fo_id')) {
            return response()->json('Unauthorized');
        }

        $id            = $request->id;
        $product_id    = base64_decode($request->product_id);
        $total_request = abs($request->qty);
        $cart_customer = Cart::where('customer_id', session('fo_id'))->get();
        $cart_row      = Cart::find($id);
        $total_price   = $cart_row->product->price() * $total_request;
        $grandtotal    = 0;
        $total_stock   = $cart_row->product->productShading->sum('qty');

        foreach($cart_customer as $cc) {
            if($cc->id == $id) {
                $grandtotal += $cc->product->price() * $total_request;
            } else {
                $grandtotal += $cc->product->price() * $cc->qty;
            }
        }

        $cart_row->update(['qty' => $total_request]);
        return response()->json([
            'total_price' => 'Rp ' . number_format($total_price, 0, ',', '.'),
            'grandtotal'  => 'Rp ' . number_format($grandtotal, 0, ',', '.')
        ]);
    }

    public function cartDestroy($id)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        Cart::find(base64_decode($id))->delete();
        return redirect('account/cart');
    }

    public function addToWishlist(Request $request)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        $product_id = base64_decode($request->product_id);
        $wishlist   = Wishlist::where('customer_id', session('fo_id'))
            ->where('product_id', $product_id)
            ->first();

        if(!$wishlist) {
            Wishlist::create([
                'customer_id' => session('fo_id'),
                'product_id'  => $product_id
            ]);
        }

        return redirect()->back();
    }

    public function wishlistToCart($id)
    {
        if(!session('fo_id')) {
            return redirect()->back();
        }

        $id       = base64_decode($id);
        $wishlist = Wishlist::find($id);

        Cart::create([
            'customer_id' => session('fo_id'),
            'product_id'  => $wishlist->product_id,
            'qty'         => 1
        ]);

        $wishlist->delete();
        return redirect()->back();
    }

    public function wishlistDestroy($id)
    {
        if(!session('fo_id')) {
            return redirect('account/login');
        }

        Wishlist::find(base64_decode($id))->delete();
        return redirect('account/wishlist');
    }
	
	public function karyaModern(Request $request)
    {
        $per_page = 20;
        $page     = $request->has('page') ? $request->page : 1;
		
		$stock    = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock_km', [
			'tipe_item' => $request->search,
			'kode_item'	=> $request->code,
			'gudang'	=> $request->warehouse,
			'page'      => $page,
			'per_page'  => $per_page
		]));

        $result       = $stock->result->data;
        $total_result = $stock->result->total_data;
        $items        = new LengthAwarePaginator($result, $total_result, $per_page, $page, [
            'path'  => $request->url(),
            'query' => $request->query()
        ]);

        $data = [
            'title'   		=> 'Karya Modern Stock',
			'warehouselist'	=> Warehouse::where('status','1')->get(),
            'search'  		=> $request->search ? $request->search : '',
			'warehouse'  	=> $request->warehouse ? $request->warehouse : '',
			'code'  		=> $request->code ? $request->code : '',
            'items'   		=> $items,
            'content' 		=> 'karya_modern'
        ];

        return view('layouts.km', ['data' => $data]);
    }
}
