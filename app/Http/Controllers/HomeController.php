<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller {
    
    public function index()
    {
        $product_cheapest = Product::select('products.*', 'pricing_policies.price_list')
            ->whereHas('productShading', function($query) {
                $query->havingRaw('SUM(qty) > ?', [18]);
            })
			->whereHas('type', function($query) {
				$query->where('show_in_store','1')
				->where('best_seller','1');
			})
            ->where('products.status', 1)
            ->leftJoin('pricing_policies', 'products.id', '=', 'pricing_policies.product_id')
            ->orderBy('pricing_policies.price_list', 'asc')
            ->limit(12)
            ->get();

        $product_new = Product::whereHas('productShading', function($query) {
                $query->havingRaw('SUM(qty) > ?', [18]);
            })
			->whereHas('type', function($query) {
				$query->where('show_in_store','1');
			})
            ->where('status', 1)
            ->latest()
            ->limit(12)
            ->get();

        $product_limited = Product::whereHas('productShading', function($query) {
                $query->havingRaw('SUM(qty) > ?', [0])
                    ->havingRaw('SUM(qty) <= ?', [18]);
            })
			->whereHas('type', function($query) {
				$query->where('show_in_store','1');
			})
            ->where('status', 1)
            ->latest()
            ->limit(12)
            ->get();

        $category = Category::where('status', 1)
            ->whereHas('type', function($query) {
                    $query->whereHas('product', function($query) {
                            $query->whereHas('productShading', function($query) {
                                    $query->havingRaw('SUM(qty) > ?', [0]);
                                });
                        });
                })
            ->limit(5)
            ->inRandomOrder()
            ->get();

        $data = [
            'title'            => 'SMB',
            'banner'           => Banner::where('status', 1)->orderByDesc('created_at')->get(),
            'product_cheapest' => $product_cheapest,
            'product_limited'  => $product_limited,
            'product_new'      => $product_new,
            'category'         => $category,
            'brand'            => Brand::where('status', 1)->whereNotNull('image')->orderBy('order', 'asc')->get(),
            'content'          => 'home'
        ];

        return view('layouts.index', ['data' => $data]);
    }

}
