<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use App\Models\Brand;
use App\Models\Grade;
use App\Models\HsCode;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ProductShading;
use App\Http\Controllers\Controller;
use App\Models\ProjectDeliveryProduct;
use App\Models\ProjectProduct;
use App\Models\ProjectSaleProduct;
use App\Models\ProjectWarehouseProduct;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CodeController extends Controller
{

    public function index()
    {
        $data = [
            'title'     => 'Product Code',
            'company'   => Company::all(),
            'hs_code'   => HsCode::all(),
            'brand'     => Brand::all(),
            'country'   => Country::all(),
            'supplier'  => Supplier::all(),
            'grade'     => Grade::all(),
            'warehouse' => Warehouse::all(),
            'currency' => Currency::where('status', 1)->get(),
            'content'   => 'admin.master_data.product.product_code'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'shading',
            'code',
            'name',
            'check',
            'stock',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Product::count();

        $query_data = Product::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('type', function ($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('category', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('surface', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('color', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('pattern', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                })
                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                    })
                    ->orWhereHas('country', function ($query) use ($search) {
                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                    })
                    ->orWhereHas('productShading', function ($query) use ($search) {
                        $query->where('stock_code', 'like', "%$search%");
                    });
            }

            if ($request->length_param) {
                $query->where(function ($query) use ($request) {
                    $query->whereHas('type', function ($query) use ($request) {
                        $query->where('length', $request->length_param);
                    });
                });
            }

            if ($request->width_param) {
                $query->where(function ($query) use ($request) {
                    $query->whereHas('type', function ($query) use ($request) {
                        $query->where('width', $request->width_param);
                    });
                });
            }

            if ($request->brand_id) {
                $query->where('brand_id', $request->brand_id);
            }

            if ($request->country_id) {
                $query->where('country_id', $request->country_id);
            }

            if ($request->stock) {
                if ($request->stock == 'not_available') {
                    $query->doesnthave('productShading')
                        ->orWhereHas('productShading', function ($query) use ($request) {
                            $query->havingRaw('SUM(qty) <= ?', [0]);
                        });
                } else {
                    $query->whereHas('productShading', function ($query) use ($request) {
                        if ($request->stock == 'limited') {
                            $query->havingRaw('SUM(qty) > ?', [0])
                                ->havingRaw('SUM(qty) <= ?', [18]);
                        } else if ($request->stock == 'ready') {
                            $query->havingRaw('SUM(qty) > ?', [18]);
                        }
                    });
                }
            }

            if ($request->shading) {
                if ($request->shading == 'has_shading') {
                    $query->has('productShading');
                } else if ($request->shading == 'no_shading') {
                    $query->doesntHave('productShading');
                }
            }

            if ($request->check) {
                $query->where('check', $request->check);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->groupBy('id')
            ->get();

        $total_filtered = Product::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('type', function ($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('category', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('surface', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('color', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('pattern', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                })
                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                    })
                    ->orWhereHas('country', function ($query) use ($search) {
                        $query->whereRaw("MATCH(name) AGAINST('$search' IN BOOLEAN MODE)");
                    })
                    ->orWhereHas('productShading', function ($query) use ($search) {
                        $query->where('stock_code', 'like', "%$search%");
                    });
            }

            if ($request->length_param) {
                $query->where(function ($query) use ($request) {
                    $query->whereHas('type', function ($query) use ($request) {
                        $query->where('length', $request->length_param);
                    });
                });
            }

            if ($request->width_param) {
                $query->where(function ($query) use ($request) {
                    $query->whereHas('type', function ($query) use ($request) {
                        $query->where('width', $request->width_param);
                    });
                });
            }

            if ($request->brand_id) {
                $query->where('brand_id', $request->brand_id);
            }

            if ($request->country_id) {
                $query->where('country_id', $request->country_id);
            }

            if ($request->stock) {
                if ($request->stock == 'not_available') {
                    $query->doesnthave('productShading')
                        ->orWhereHas('productShading', function ($query) use ($request) {
                            $query->havingRaw('SUM(qty) <= ?', [0]);
                        });
                } else {
                    $query->whereHas('productShading', function ($query) use ($request) {
                        if ($request->stock == 'limited') {
                            $query->havingRaw('SUM(qty) > ?', [0])
                                ->havingRaw('SUM(qty) <= ?', [18]);
                        } else if ($request->stock == 'ready') {
                            $query->havingRaw('SUM(qty) > ?', [18]);
                        }
                    });
                }
            }

            if ($request->shading) {
                if ($request->shading == 'has_shading') {
                    $query->has('productShading');
                } else if ($request->shading == 'no_shading') {
                    $query->doesntHave('productShading');
                }
            }

            if ($request->check) {
                $query->where('check', $request->check);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach ($query_data as $val) {
                if ($val->productShading->count() > 0) {
                    $shading = '<span class="text-success"><i class="icon-check"></i></span>';
                } else {
                    $shading = '<span class="text-danger"><i class="icon-cross3"></i></span>';
                }

                $image = '<a href="' . $val->type->image() . '" data-lightbox="' . $val->type->code . '" data-title="' . $val->type->code . '"><img src="' . $val->type->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';

                $availability = '
                    <div class="mb-0">' . $val->availability()->stock . '</div>
                    <span class="badge ' . $val->availability()->color . '">' . $val->availability()->status . '</span>
                ';

                $response['data'][] = [
                    $nomor,
                    $shading,
                    $val->code(),
                    $val->name() . '<br>' . $image,
                    $availability,
                    $val->check(),
                    $val->status(),
                    '
                        <a href="' . url('admin/master_data/product/product_code/detail/' . $val->id) . '" class="btn bg-info btn-sm" data-popup="tooltip" title="Detail"><i class="icon-info22"></i></a>
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
                    '
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

    public function generateCode(Request $request)
    {
        $code    = '';
        $brand   = Brand::find($request->brand_id);
        $country = Country::find($request->country_id);
        $type    = Type::find($request->type_id);
        $grade   = Grade::find($request->grade_id);

        if ($type) {
            if ($type->division) {
                $code .= $type->division->code;
            }
        }

        if ($brand) {
            $code .= $brand->code;
        }

        if ($country) {
            $code .= $country->code;
        }

        if ($type) {
            $code .= $type->code;
        }

        if ($grade) {
            $code .= $grade->code;
        }

        return response()->json($code);
    }

    public function formula(Request $request)
    {
        $type = Type::find($request->type_id);
        if ($type) {
            $carton_sqm  = ($type->length / 100) * ($type->width / 100) * $request->carton_pcs;
            if (strpos($type->category->name, 'tile') !== false) {
                $cubic_meter = null;
            } else {
                $cubic_meter = ($type->length / 100) * ($type->width / 100) * $type->thickness * $request->carton_pcs;
            }
        } else {
            $carton_sqm  = 0;
            $cubic_meter = null;
        }

        return response()->json([
            'carton_sqm'  => round($carton_sqm, 2),
            'cubic_meter' => $cubic_meter ? round($cubic_meter, 2) : $cubic_meter
        ]);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'type_id'             => ['required', Rule::unique('products', 'type_id')],
            'company_id'          => 'required',
            'hs_code_id'          => 'required',
            'brand_id'            => 'required',
            'country_id'          => 'required',
            'supplier_id'         => 'required',
            'grade_id'            => 'required',
            'container_standart'  => 'required',
            'container_stock'     => 'required',
            'container_max_stock' => 'required',
            'description'         => 'required',
            'status'              => 'required'
        ], [
            'type_id.required'             => 'Please select a type.',
            'type_id.unique'               => 'Type already exists.',
            'company_id.required'          => 'Please select a company.',
            'hs_code_id.required'          => 'Please select a hs code.',
            'brand_id.required'            => 'Please select a brand.',
            'country_id.required'          => 'Please select a country.',
            'supplier_id.required'         => 'Please select a supplier.',
            'grade_id.required'            => 'Please select a grade.',
            'container_standart.required'  => 'Please select a standart container.',
            'container_stock.required'     => 'Container stock cannot be empty.',
            'container_max_stock.required' => 'Container max stock cannot be empty.',
            'description.required'         => 'Description cannot be empty.',
            'status.required'              => 'Please select a status.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Product::create([
                'type_id'             => $request->type_id,
                'company_id'          => $request->company_id,
                'hs_code_id'          => $request->hs_code_id,
                'brand_id'            => $request->brand_id,
                'country_id'          => $request->country_id,
                'supplier_id'         => $request->supplier_id,
                'grade_id'            => $request->grade_id,
                'carton_pallet'       => $request->carton_pallet,
                'carton_pcs'          => $request->carton_pcs,
                'container_standart'  => $request->container_standart,
                'container_stock'     => $request->container_stock,
                'container_max_stock' => $request->container_max_stock,
                'description'         => $request->description,
                'design'               => $request->file('file') ? $request->file('file')->store('public/product') : NULL,
                'status'              => $request->status
            ]);

            if ($query) {
                if ($request->shading_warehouse_code) {
                    foreach ($request->shading_warehouse_code as $key => $swc) {
                        $total_stock = 0;
                        $stock       = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
                            'kode_item' => $request->shading_stock_code[$key],
                            'gudang'    => $swc,
                            'per_page'    => 1000
                        ]));

                        if ($stock->result->total_data > 0) {
                            foreach ($stock->result->data as $s) {
                                $kodeitem = trim($s->kode_item);
                                if ($kodeitem == $request->shading_stock_code[$key] && $s->kode_gudang == $swc) {
                                    $total_stock += $s->stok;
                                }
                            }
                        }

                        ProductShading::create([
                            'product_id'     => $query->id,
                            'warehouse_code' => $swc,
                            'stock_code'     => $request->shading_stock_code[$key],
                            'code'           => $request->shading_code[$key],
                            'qty'            => $total_stock
                        ]);
                    }
                }

                activity()
                    ->performedOn(new Product())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add product code data');

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
        $data    = Product::find($request->id);
        $shading = [];

        if ($data->productShading) {
            foreach ($data->productShading as $pd) {
                $shading[] = [
                    'warehouse_code' => $pd->warehouse_code,
                    'stock_code'     => $pd->stock_code,
                    'code'           => $pd->code,
                    'qty'            => $pd->qty
                ];
            }
        }

        return response()->json([
            'code'                => $data->name(),
            'type_id'             => $data->type_id,
            'type_code'           => $data->type->code,
            'company_id'          => $data->company_id,
            'hs_code_id'          => $data->hs_code_id,
            'brand_id'            => $data->brand_id,
            'country_id'          => $data->country_id,
            'supplier_id'         => $data->supplier_id,
            'grade_id'            => $data->grade_id,
            'carton_pallet'       => $data->carton_pallet,
            'carton_pcs'          => $data->carton_pcs,
            'container_standart'  => $data->container_standart,
            'container_stock'     => $data->container_stock,
            'container_max_stock' => $data->container_max_stock,
            'description'         => $data->description,
            'design'               => '<img src="' . $data->attachment() . '" class="thumb-image" width="300px">',
            'check'               => $data->check,
            'status'              => $data->status,
            'shading'             => $shading
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'type_id'             => ['required', Rule::unique('products', 'type_id')->ignore($id)],
            'company_id'          => 'required',
            'hs_code_id'          => 'required',
            'brand_id'            => 'required',
            'country_id'          => 'required',
            'supplier_id'         => 'required',
            'grade_id'            => 'required',
            'container_standart'  => 'required',
            'container_stock'     => 'required',
            'container_max_stock' => 'required',
            'description'         => 'required',
            'status'              => 'required'
        ], [
            'type_id.required'             => 'Please select a type.',
            'type_id.unique'               => 'Type already exists.',
            'company_id.required'          => 'Please select a company.',
            'hs_code_id.required'          => 'Please select a hs code.',
            'brand_id.required'            => 'Please select a brand.',
            'country_id.required'          => 'Please select a country.',
            'supplier_id.required'         => 'Please select a supplier.',
            'grade_id.required'            => 'Please select a grade.',
            'container_standart.required'  => 'Please select a standart container.',
            'container_stock.required'     => 'Container stock cannot be empty.',
            'container_max_stock.required' => 'Container max stock cannot be empty.',
            'description.required'         => 'Description cannot be empty.',
            'status.required'              => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			$cek = Product::find($id);
			
			if($request->has('file')) {
				if(Storage::exists($cek->design)) {
					Storage::delete($cek->design);
				}

				$image = $request->file('file')->store('public/product');
			} else {
				$image = $cek->design;
			}
			
            $query = Product::where('id', $id)->update([
                'type_id'             => $request->type_id,
                'company_id'          => $request->company_id,
                'hs_code_id'          => $request->hs_code_id,
                'brand_id'            => $request->brand_id,
                'country_id'          => $request->country_id,
                'supplier_id'         => $request->supplier_id,
                'grade_id'            => $request->grade_id,
                'carton_pallet'       => $request->carton_pallet,
                'carton_pcs'          => $request->carton_pcs,
                'container_standart'  => $request->container_standart,
                'container_stock'     => $request->container_stock,
                'container_max_stock' => $request->container_max_stock,
                'description'         => $request->description,
				'design'         	  => $image,
				'check'               => $request->check,
                'status'              => $request->status
            ]);

            if($query) {
                ProductShading::where('product_id', $id)->delete();
                if($request->shading_warehouse_code) {
                    foreach($request->shading_warehouse_code as $key => $swc) {
                        $total_stock = 0;
                        $stock       = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
                            'kode_item' => $request->shading_stock_code[$key],
                            'gudang'    => $swc,
                            'per_page'  => 1000
                        ]));

                        if($stock->result->total_data > 0) {
                            foreach($stock->result->data as $s) {
								$kodeitem = trim($s->kode_item);
								if($kodeitem == $request->shading_stock_code[$key] && $s->kode_gudang == $swc){
									$total_stock += $s->stok;
								}
                            }
                        }
						
						ProductShading::create([
							'product_id'     => $id,
							'warehouse_code' => $swc,
							'stock_code'     => $request->shading_stock_code[$key],
							'code'           => $request->shading_code[$key],
							'qty'            => $total_stock
						]);
                    }
                }

                activity()
                    ->performedOn(new Product())
                    ->causedBy(session('bo_id'))
                    ->log('Change the product code data');

                $response = [
                    'status'  => 200,
                    'message' => 'Data updated successfully.'
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

    public function destroy(Request $request)
    {
        $projectProduct     = ProjectProduct::where('product_id', $request->id)->first();
        $projectSaleProduct = ProjectSaleProduct::where('product_id', $request->id)->first();
        $projectWarehouseProduct = ProjectWarehouseProduct::where('product_id', $request->id)->first();
        $projectDeliveryProduct = ProjectDeliveryProduct::where('product_id', $request->id)->first();
        
        if ($projectProduct || $projectSaleProduct || $projectWarehouseProduct || $projectDeliveryProduct) {
            $response = [
                'status'  => 500,
                'message' => 'Data failed to delete.'
            ];
        } else {
            $query = Product::where('id', $request->id)->delete();
            if ($query) {
                ProductShading::where('product_id', $request->id)->delete();
                activity()
                    ->performedOn(new Product())
                    ->causedBy(session('bo_id'))
                    ->log('Delete the product type data');

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
        }


        return response()->json($response);
    }

    public function detail($id)
    {
        $data = [
            'title'   => 'Detail Product Code',
            'product' => Product::find($id),
            'content' => 'admin.master_data.product.product_code_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function repairShading()
    {
        ProductShading::chunk(100, function ($query) {
            foreach ($query as $q) {
                $total_stock = 0;

                $stock = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
                    'kode_item' => $q->stock_code,
                    'gudang'    => $q->warehouse_code,
                    'per_page'  => 4200
                ]));

                if ($stock->result->total_data > 0) {
                    foreach ($stock->result->data as $s) {
                        $kodeitem = trim($s->kode_item);
                        if ($kodeitem == $q->stock_code && $s->kode_gudang == $q->warehouse_code) {
                            $total_stock += $s->stok;
                        }
                    }

                    $q->update([
                        'qty' => $total_stock
                    ]);
                } else {
                    $q->delete();
                }
            }
        });
    }
}
