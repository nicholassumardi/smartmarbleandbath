<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlProductPicture;
use App\Models\AlProductParent;
use App\Models\AlSupplier;
use App\Models\AlCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AlProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AlProductController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    => 'Al Barang',
            'supplier' => AlSupplier::all(),
			'category' => AlCategory::all(),
			'parent'   => AlProductParent::all(),
            'content'  => 'admin.al.master_data.barang'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
            'code',
            'name',
            'al_category_id',
            'al_supplier_id',
            'description',
			'buy_price',
			'sell_price',
			'unit',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlProduct::count();
        
        $query_data = AlProduct::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
							->orWhere('description', 'like', "%$search%")
							->orWhere('buy_price', 'like', "%$search%")
							->orWhere('sell_price', 'like', "%$search%")
                            ->orWhereHas('alCategory', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
							 ->orWhereHas('alSupplier', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlProduct::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
							->orWhere('description', 'like', "%$search%")
							->orWhere('buy_price', 'like', "%$search%")
							->orWhere('sell_price', 'like', "%$search%")
                            ->orWhereHas('alCategory', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
							 ->orWhereHas('alSupplier', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->name,
                    $val->alCategory->name,
					$val->alSupplier->name,
					$val->description,
                    number_format($val->buy_price,0,',','.').'<br><span style="font-size:9px;"><i>'.$val->latestDateBuyPrice().'</i></span>',
					number_format($val->sell_price,0,',','.').'<br><span style="font-size:9px;"><i>'.$val->latestDateSellPrice().'</i></span>',
					$val->unit(),
                    '
						<a href="javascript:void(0);" data-popup="tooltip" title="Gambar Produk" onclick="addPictures('.$val->id.')" class="btn bg-success btn-sm"><i class="icon-images3"></i><span class="badge badge-primary badge-pill" style="position:absolute;top:-10px;right:-10px;z-index:999;">'.count($val->alProductPicture).'</span></a>
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="show(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Hapus" onclick="destroy(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
	
	public function datatableCategory(Request $request){
		$column = [
            'id',
            'name',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlCategory::count();
        
        $query_data = AlCategory::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlCategory::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->name,
                    $val->parentName() ? $val->parentName()->name : 'IsParent',
                    '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="showCategory(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Hapus" onclick="destroyCategory(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
	
	public function datatableParent(Request $request){
		$column = [
            'id',
            'name',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlProductParent::count();
        
        $query_data = AlProductParent::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlProductParent::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
                $response['data'][] = [
                    $nomor,
                    $val->name,
                    '
                        <button type="button" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit" onclick="showInduk(' . $val->id . ')"><i class="icon-pencil7"></i></button>
                        <button type="button" class="btn bg-danger btn-sm" data-popup="tooltip" title="Hapus" onclick="destroyInduk(' . $val->id . ')"><i class="icon-trash-alt"></i></button>
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
	
	public function create(Request $request){
		$validation = Validator::make($request->all(), [
            'name'      		=> 'required',
            'al_supplier_id'    => 'required',
            'al_category_id'    => 'required',
			'description'    	=> 'required',
            'buy_price'    		=> 'required',
            'sell_price' 		=> 'required',
            'unit'     			=> 'required'
        ], [
            'name.required'            => 'Name cannot be empty.',
            'al_supplier_id.required'  => 'Supplier cannot be empty.',
            'al_category_id.required'  => 'Category cannot be empty.',
			'description.required'     => 'Description cannot be empty.',
            'buy_price.required' 	   => 'Buy price cannot be empty.',
            'sell_price.required'      => 'Sell price cannot be empty.',
			'unit.required'            => 'Unit cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->temp){
				$query = AlProduct::find($request->temp);
				
				$query->update([
					'name'           		=> $request->name,
					'al_product_parent_id'	=> $request->al_product_parent_id,
					'al_category_id'  		=> $request->al_category_id,
					'al_supplier_id'  		=> $request->al_supplier_id,
					'description'	  		=> $request->description,
					'buy_price'       		=> str_replace(',','.',str_replace('.','',$request->buy_price)),
					'sell_price'      		=> str_replace(',','.',str_replace('.','',$request->sell_price)),
					'unit'			  		=> $request->unit
				]);
			}else{
				$query = AlProduct::create([
					'code'            		=> AlProduct::generateCode(),
					'name'            		=> $request->name,
					'al_product_parent_id'	=> $request->al_product_parent_id,
					'al_category_id'  		=> $request->al_category_id,
					'al_supplier_id'  		=> $request->al_supplier_id,
					'description'	  		=> $request->description,
					'buy_price'       		=> str_replace(',','.',str_replace('.','',$request->buy_price)),
					'sell_price'      		=> str_replace(',','.',str_replace('.','',$request->sell_price)),
					'unit'			  		=> $request->unit
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new AlProduct())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master AL product data');

                $response = [
                    'status'  	=> 200,
                    'message' 	=> 'Data added successfully.'
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
	
	public function show(Request $request){
		$product = AlProduct::find($request->id);
		
		$product['buy_price'] = number_format($product->buy_price,0,',','.');
		$product['sell_price'] = number_format($product->sell_price,0,',','.');
		
		return response()->json($product);
	}
	
	public function showCategory(Request $request){
		$category = AlCategory::find($request->id);
		
		return response()->json($category);
	}
	
	public function showParent(Request $request){
		$parent = AlProductParent::find($request->id);
		
		return response()->json($parent);
	}
	
	public function destroy(Request $request){
		$product = AlProduct::find($request->id);
		
		foreach($product->alProductPicture as $rowpicture){
			$rowpicture->deleteFile();
			$rowpicture->delete();
		}
		
		$product->delete();
		
		return response()->json([
			'status'	=> 200,
			'message'	=> 'Data deleted successfully.'
		]);
	}
	
	public function destroyCategory(Request $request){
		
        $isExists = AlProduct::where('al_category_id', $request->id)->count();
        if($isExists > 0){
            return response()->json([
                'status'	=> 500,
                'message'	=> 'Failed category has Product, delete the product first.'
            ]);
        }
        
     
        $category = AlCategory::find($request->id);
		
		$category->delete();
		
		return response()->json([
			'status'	=> 200,
			'message'	=> 'Data deleted successfully.'
		]);
	}
	
	public function destroyParent(Request $request){
		$parent = AlProductParent::find($request->id);
		
		$parent->delete();
		
		return response()->json([
			'status'	=> 200,
			'message'	=> 'Data deleted successfully.'
		]);
	}
	
	public function getPictures(Request $request){
		$data = AlProductPicture::where('al_product_id',$request->id)->get();
		
		$result = [];
		
		foreach($data as $row){
			$result[] = [
				'id'	=> $row->id,
				'image'	=> $row->image(),
				'name'	=> $row->alProduct->name.' - Code. '.$row->alProduct->code
			];
		}
		
		return response()->json($result);
	}
	
	public function addPictures(Request $request){
		
		$count = AlProductPicture::where('al_product_id',$request->id)->count();
		
		if($count >= 3){
			return response()->json([
				'status'		=> 422,
				'message'		=> 'You have reached maximum file uploads for this project.'
			]);
		}else{
			$query = AlProductPicture::create([
				'al_product_id'	=> $request->id,
				'image'			=> $request->file('file') ? $request->file('file')->store('public/al_product') : ''
			]);
			
			return response()->json([
				'status'		=> 200,
				'message'		=> 'You have successfully upload the file.'
			]);
		}
	}
	
	public function deletePictures(Request $request){
		$data = AlProductPicture::find($request->id);
		
		$data->deleteFile();
		
		$data->delete();
		
		if($data){
			return response()->json([
				'status'	=> 200,
				'message'	=> 'Picture successfully deleted.' 
			]);
		}else{
			return response()->json([
				'status'	=> 422,
				'message'	=> 'Picture not found.'
			]);
		}
	}
	
	public function createCategory(Request $request){
		$validation = Validator::make($request->all(), [
            'name_category'      		=> 'required'
        ], [
            'name_category.required'	=> 'Category name cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->tempKategori){
				$query = AlCategory::find($request->tempKategori);
				
				$query->update([
					'name'            => $request->name_category,
                    'parent_id'       => $request->al_category_parent
				]);
			}else{
				$query = AlCategory::create([
					'name'            => $request->name_category,
                    'parent_id'       => $request->al_category_parent
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new AlCategory())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master AL category data');

                $response = [
                    'status'  	=> 200,
					'data'		=> AlCategory::all(),
                    'message' 	=> 'Data added successfully.'
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
	
	public function createParent(Request $request){
		$validation = Validator::make($request->all(), [
            'name_induk'      		=> 'required'
        ], [
            'name_induk.required'	=> 'Parent name cannot be empty.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
			
			if($request->tempInduk){
				$query = AlProductParent::find($request->tempInduk);
				
				$query->update([
					'name'            => $request->name_induk
				]);
			}else{
				$query = AlProductParent::create([
					'name'            => $request->name_induk
				]);
			}

            if($query) {
                activity()
                    ->performedOn(new AlProductParent())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master AL category data');

                $response = [
                    'status'  	=> 200,
					'data'		=> AlProductParent::all(),
                    'message' 	=> 'Data added successfully.'
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
}