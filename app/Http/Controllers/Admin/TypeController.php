<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use App\Models\Unit;
use App\Models\Color;
use App\Models\Pattern;
use App\Models\Surface;
use App\Models\Category;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Models\LoadingLimit;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\ProjectSaleProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller {
    
    public function index()
    {
        $data = [
            'title'         => 'Product Type',
            'category'      => Category::where('status', 1)->where('parent_id', 0)->oldest('name')->get(),
            'division'      => Division::where('status', 1)->get(),
            'surface'       => Surface::where('status', 1)->get(),
            'color'         => Color::where('status', 1)->get(),
            'pattern'       => Pattern::where('status', 1)->get(),
            'loading_limit' => LoadingLimit::where('status', 1)->get(),
            'unit'          => Unit::where('status', 1)->get(),
            'content'       => 'admin.master_data.product.product_type'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'updated_at',
            'image',
            'code',
            'category_id',
            'thickness',
            'surface_id',
            'color_id',
            'pattern_id',
            'status',
			'show_in_store'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Type::count();
        
        $query_data = Type::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('category', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('surface', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('color', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('pattern', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }         

                if($request->status) {
                    $query->where('status', $request->status);
                }
				
				if($request->image) {
					if($request->image == '1'){
						$query->whereNull('image');
					}elseif($request->image == '2'){
						$query->whereNull('image')
						->whereHas('product', function($query){
							$query->whereHas('productShading', function($query){
								$query->havingRaw('SUM(qty) > ?', [0]);
							});
						});
					}elseif($request->image == '3'){
						$query->whereNull('image')
						->whereHas('product', function($query){
							$query->doesntHave('productShading');
						});
					}
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Type::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('category', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('surface', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('color', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('pattern', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }       
                
                if($request->status) {
                    $query->where('status', $request->status);
                }
				
				if($request->image) {
					if($request->image == '1'){
						$query->whereNull('image');
					}elseif($request->image == '2'){
						$query->whereNull('image')
						->whereHas('product', function($query){
							$query->whereHas('productShading', function($query){
								$query->havingRaw('SUM(qty) > ?', [0]);
							});
						});
					}elseif($request->image == '3'){
						$query->whereNull('image')
						->whereHas('product', function($query){
							$query->doesntHave('productShading');
						});
					}
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $image = '<a href="' . $val->image() . '" data-lightbox="' . $val->code . '" data-title="' . $val->code . '"><img src="' . $val->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';
				
				$check = '';
				
				if($val->show_in_store == '1'){
					$check = 'checked';
				}

                $response['data'][] = [
                    $nomor,
                    $image,
                    $val->code,
                    $val->category->name,
                    $val->thickness . ' mm',
                    $val->surface ? $val->surface->name : '',
                    $val->color->name,
                    $val->pattern->name,
                    $val->status(),
					//$val->show(),
					'<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" class="form-check-input check-show" '.$check.' data-product="'.$val->id.'" style="margin-top:.125rem;margin-left: -1.3rem;">
							Store
						</label>
					</div>',
                    '
                        <a href="' . url('admin/master_data/product/product_type/detail/' . $val->id) . '" class="btn bg-info btn-sm" data-popup="tooltip" title="Detail"><i class="icon-info22"></i></a>
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category_id'      => 'required',
            'division_id'      => 'required',
            'color_id'         => 'required',
			'surface_id'       => 'required',
            'pattern_id'       => 'required',
            'loading_limit_id' => 'required',
            'buy_unit_id'      => 'required',
            'stock_unit_id'    => 'required',
            'selling_unit_id'  => 'required',
            'image'            => 'mimes:jpg,jpeg,png|max:512',
            'code'             => 'required|unique:types,code',
            'material'         => 'required',
            'weight'           => 'required',
            'conversion'       => 'required',
            'stockable'        => 'required',
            'min_stock'        => 'required',
            'max_stock'        => 'required',
            'small_stock'      => 'required',
            'status'           => 'required'
        ], [
            'category_id.required'      => 'Please select a category.',
            'division_id.required'      => 'Please select a division.',
            'color_id.required'         => 'Please select a color.',
			'surface_id.required'       => 'Please select a surface.',
            'pattern_id.required'       => 'Please select a pattern.',
            'loading_limit_id.required' => 'Please select a loading limit.',
            'buy_unit_id.required'      => 'Please select a buy unit.',
            'stock_unit_id.required'    => 'Please select a stock unit.',
            'selling_unit_id.required'  => 'Please select a selling unit.',
            'image.image'               => 'File must be an image.',
            'image.mimes'               => 'Image must have an extension jpg, jpeg, png.',
            'image.max'                 => 'Image max 512KB.',
            'code.required'             => 'Code cannot be empty.',
            'code.unique'               => 'Code already exists.',
            'material.required'         => 'Please select a material.',
            'weight.required'           => 'Weight cannot be empty.',
            'conversion.required'       => 'Conversion cannot be empty.',
            'stockable.required'        => 'Please select a need stock.',
            'min_stock.required'        => 'Min stock cannot be empty.',
            'max_stock.required'        => 'Max stock cannot be empty.',
            'small_stock.required'      => 'Small stock cannot be empty.',
            'status.required'           => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Type::create([
                'category_id'      => $request->category_id,
                'division_id'      => $request->division_id,
                'surface_id'       => $request->surface_id,
                'color_id'         => $request->color_id,
                'pattern_id'       => $request->pattern_id,
                'loading_limit_id' => $request->loading_limit_id,
                'buy_unit_id'      => $request->buy_unit_id,
                'stock_unit_id'    => $request->stock_unit_id,
                'selling_unit_id'  => $request->selling_unit_id,
                'image'            => $request->has('image') ? $request->file('image')->store('public/product') : null,
                'code'             => $request->code,
                'material'         => $request->material,
                'faces'            => $request->faces,
                'length'           => $request->length,
                'width'            => $request->width,
                'height'           => $request->height,
                'weight'           => $request->weight,
                'thickness'        => $request->thickness,
                'conversion'       => $request->conversion,
                'stockable'        => $request->stockable,
                'min_stock'        => $request->min_stock,
                'max_stock'        => $request->max_stock,
                'small_stock'      => $request->small_stock,
                'status'           => $request->status,
				'show_in_store'    => $request->show_in_store,
				'best_seller'      => $request->best_seller,
				'other_dimension'  => $request->other_dimension
            ]);

            if($query) {
                activity()
                    ->performedOn(new Type())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add product type data');

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
        $data = Type::find($request->id);
        return response()->json([
            'category_id'      => $data->category_id,
            'division_id'      => $data->division_id,
            'surface_id'       => $data->surface_id,
            'color_id'         => $data->color_id,
            'pattern_id'       => $data->pattern_id,
            'loading_limit_id' => $data->loading_limit_id,
            'buy_unit_id'      => $data->buy_unit_id,
            'stock_unit_id'    => $data->stock_unit_id,
            'selling_unit_id'  => $data->selling_unit_id,
            'image'            => $data->image(),
            'code'             => $data->code,
            'material'         => $data->material,
            'faces'            => $data->faces,
            'lengths'          => $data->length,
            'width'            => $data->width,
            'height'           => $data->height,
            'weight'           => $data->weight,
            'thickness'        => $data->thickness,
            'conversion'       => $data->conversion,
            'stockable'        => $data->stockable,
            'small_stock'      => $data->small_stock,
            'min_stock'        => $data->min_stock,
            'max_stock'        => $data->max_stock,
            'status'           => $data->status,
			'show_in_store'    => $data->show_in_store,
			'best_seller'      => $data->best_seller,
			'other_dimension'  => $data->other_dimension
        ]);
    }

    public function update(Request $request, $id)
    {
        $query = Type::find($id);
        $validation = Validator::make($request->all(), [
            'category_id'      => 'required',
            'division_id'      => 'required',
            'color_id'         => 'required',
			'surface_id'       => 'required',
            'pattern_id'       => 'required',
            'loading_limit_id' => 'required',
            'buy_unit_id'      => 'required',
            'stock_unit_id'    => 'required',
            'selling_unit_id'  => 'required',
            'image'            => 'image|mimes:jpg,jpeg,png|max:512',
            'code'             => ['required', Rule::unique('types', 'code')->ignore($id)],
            'material'         => 'required',
            'weight'           => 'required',
            'conversion'       => 'required',
            'stockable'        => 'required',
            'min_stock'        => 'required',
            'max_stock'        => 'required',
            'small_stock'      => 'required',
            'status'           => 'required'
        ], [
            'category_id.required'      => 'Please select a category.',
            'division_id.required'      => 'Please select a division.',
            'color_id.required'         => 'Please select a color.',
			'surface_id.required'       => 'Please select a surface.',
            'pattern_id.required'       => 'Please select a pattern.',
            'loading_limit_id.required' => 'Please select a loading limit.',
            'buy_unit_id.required'      => 'Please select a buy unit.',
            'stock_unit_id.required'    => 'Please select a stock unit.',
            'selling_unit_id.required'  => 'Please select a selling unit.',
            'image.image'               => 'File must be an image.',
            'image.mimes'               => 'Image must have an extension jpg, jpeg, png.',
            'image.max'                 => 'Image max 512KB.',
            'code.required'             => 'Code cannot be empty.',
            'code.unique'               => 'Code already exists.',
            'material.required'         => 'Please select a material.',
            'weight.required'           => 'Weight cannot be empty.',
            'conversion.required'       => 'Conversion cannot be empty.',
            'stockable.required'        => 'Please select a need stock.',
            'min_stock.required'        => 'Min stock cannot be empty.',
            'max_stock.required'        => 'Max stock cannot be empty.',
            'small_stock.required'      => 'Small stock cannot be empty.',
            'status.required'           => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            if($request->has('image')) {
                if(Storage::exists($query->image)) {
                    Storage::delete($query->image);
                }

                $image = $request->file('image')->store('public/product');
            } else {
                $image = $query->image;
            }

            $query->update([
                'category_id'      => $request->category_id,
                'division_id'      => $request->division_id,
                'surface_id'       => $request->surface_id,
                'color_id'         => $request->color_id,
                'pattern_id'       => $request->pattern_id,
                'loading_limit_id' => $request->loading_limit_id,
                'buy_unit_id'      => $request->buy_unit_id,
                'stock_unit_id'    => $request->stock_unit_id,
                'selling_unit_id'  => $request->selling_unit_id,
                'image'            => $image,
                'code'             => $request->code,
                'material'         => $request->material,
                'faces'            => $request->faces,
                'length'           => $request->length,
                'width'            => $request->width,
                'height'           => $request->height,
                'weight'           => $request->weight,
                'thickness'        => $request->thickness,
                'conversion'       => $request->conversion,
                'stockable'        => $request->stockable,
                'min_stock'        => $request->min_stock,
                'max_stock'        => $request->max_stock,
                'small_stock'      => $request->small_stock,
                'status'           => $request->status,
				'show_in_store'    => $request->show_in_store,
				'best_seller'      => $request->best_seller,
				'other_dimension'  => $request->other_dimension
            ]);

            if($query) {
                activity()
                    ->performedOn(new Type())
                    ->causedBy(session('bo_id'))
                    ->log('Change the product type data');

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

        $product  = Type::whereHas('product')
        ->where('id', $request->id)
        ->first();

        $projectProduct =  Type::whereHas('product', function($query){
            $query->whereHas('projectSaleProduct');
        })->where('id',  $request->id)->first();

        $projectSaleProduct =  Type::whereHas('product', function($query){
            $query->whereHas('projectSaleProduct');
        })->where('id',  $request->id)->first();


        $sampleWarehouseProduct =  Type::whereHas('product', function($query){
            $query->whereHas('sampleWarehouseProduct');
        })->where('id',  $request->id)->first();



        if($product || $projectProduct || $projectSaleProduct || $sampleWarehouseProduct){
            $response = [
                'status'  => 500,
                'message' => 'Data failed to delete, this type linked to project already.'
            ];
        }else{
            $query = Type::where('id', $request->id)->delete();

            if($query) {
                activity()
                    ->performedOn(new Type())
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
	
	public function showInStore(Request $request) 
    {
        $query = Type::find($request->id)->update([
			'show_in_store'	=> $request->value
		]);
		
        if($query) {
            activity()
                ->performedOn(new Type())
                ->causedBy(session('bo_id'))
                ->log('Show in store product type');

            $response = [
                'status'  => 200,
                'message' => 'This product type has successfully updated.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data failed to delete.'
            ];
        }

        return response()->json($response);
    }

    public function detail($id) 
    {
        $data = [
            'title'   => 'Detail Product Type',
            'type'    => Type::find($id),
            'content' => 'admin.master_data.product.product_type_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

}
