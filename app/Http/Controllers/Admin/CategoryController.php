<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    => 'Category',
            'category' => Category::where('type', 1)->get(),
            'content'  => 'admin.master_data.product.category'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'name',
            'parent_id',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Category::where('type', 1)
            ->count();
        
        $query_data = Category::where('type', 1)
            ->where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }         
                
                if($request->status) {
                    $query->where('status', $request->status);
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Category::where('type', 1)
            ->where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
                }       
                
                if($request->status) {
                    $query->where('status', $request->status);
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
                    $val->parent() ? $val->parent()->name : 'Is Parent',
                    $val->status(),
                    '
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
            'name'      => 'required',
            'parent_id' => 'required',
            'status'    => 'required'
        ], [
            'name.required'      => 'Name cannot be empty.',
            'parent_id.required' => 'Please select a parent.',
            'status.required'    => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Category::create([
                'name'      => $request->name,
                'slug'      => Str::slug($request->name, '-'),
                'parent_id' => $request->parent_id,
                'type'      => 1,
                'status'    => $request->status
            ]);

            if($query) {
				$category = [];
				
				$categoryall = Category::where('status', 1)->where('parent_id', 0)->oldest('name')->get();
				
				foreach($categoryall as $c){
					$sub_1 = Category::where('parent_id', $c->id)->where('status', 1)->oldest('name')->get();
					if($sub_1->count() > 0){
						foreach($sub_1 as $s1){
							$sub_2 = Category::where('parent_id', $s1->id)->where('status', 1)->oldest('name')->get();
							if($sub_2->count() > 0){
								foreach($sub_2 as $s2){
									$category[] = [
										'category_id'	=> $s2->id,
										'category_text'	=> $c->name.' &rarr; '.$s1->name.' &rarr; '.$s2->name
									];
								}
							}else{
								$category[] = [
									'category_id'	=> $s1->id,
									'category_text'	=> $c->name.' &rarr; '.$s1->name
								];
							}
						}
					}else{
						$category[] = [
							'category_id'	=> $c->id,
							'category_text'	=> $c->name
						];
					}
				}
				
                activity()
                    ->performedOn(new Category())
                    ->causedBy(session('bo_id'))
                    ->withProperties($query)
                    ->log('Add master category data');

                $response = [
                    'status'  	=> 200,
					'category'	=> $category,
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

    public function show(Request $request)
    {
        $data = Category::find($request->id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'required',
            'parent_id' => 'required',
            'status'    => 'required'
        ], [
            'name.required'      => 'Name cannot be empty.',
            'parent_id.required' => 'Please select a parent.',
            'status.required'    => 'Please select a status.'
        ]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Category::where('id', $id)->update([
                'name'      => $request->name,
                'slug'      => Str::slug($request->name, '-'),
                'parent_id' => $request->parent_id,
                'status'    => $request->status
            ]);

            if($query) {
                activity()
                    ->performedOn(new Category())
                    ->causedBy(session('bo_id'))
                    ->log('Change the category master data');

                $response = [
                    'status'  => 200,
                    'message' => 'Data updated successfully.'
                ];
            } else {
                $response = [
                    'status'  => 500,
                    'message' => 'Data failed to update.'
                ];
            }
        }

        return response()->json($response);
    }

    public function destroy(Request $request) 
    {
        $query = Category::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new Category())
                ->causedBy(session('bo_id'))
                ->log('Delete the category master data');

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

        return response()->json($response);
    }

}
