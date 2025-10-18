<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\Category;
use App\Models\NewsTags;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller {
    
    public function index()
    {
        $data = [
            'title'   => 'News',
            'content' => 'admin.master_data.digital.news'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request) 
    {
        $column = [
            'id',
            'category_id',
            'user_id',
            'title',
            'status'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = News::count();
        
        $query_data = News::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('category', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhere('title', 'like', "%$search%");
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

        $total_filtered = News::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->whereHas('category', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhere('title', 'like', "%$search%");
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
                    $val->category->name,
                    $val->user->name,
                    $val->title,
                    $val->status(),
                    '
                        <a href="' . url('admin/master_data/digital/news/detail/' . $val->id) . '" class="btn bg-info btn-sm" data-popup="tooltip" title="Detail"><i class="icon-info22"></i></a>
                        <a href="' . url('admin/master_data/digital/news/update/' . $val->id) . '" class="btn bg-warning btn-sm" data-popup="tooltip" title="Edit"><i class="icon-pencil7"></i></a>
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
        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'image'       => 'required|image|mimes:jpg,jpeg,png|max:100|dimensions:width=1920,height=850',
                'category_id' => 'required',
                'title'       => 'required',
                'description' => 'required'
            ], [
                'image.required'       => 'Image cannot be empty.',
                'image.image'          => 'File must be an image.',
                'image.mimes'          => 'Image must have an extension jpg, jpeg, png.',
                'image.max'            => 'Image max 100KB.',
                'image.dimensions'     => 'Image size must be 1920x850.',
                'category_id.required' => 'Please select a category.',
                'title.required'       => 'Title cannot be empty.',
                'description.required' => 'Description cannot be empty.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                $query = News::create([
                    'category_id' => $request->category_id,
                    'user_id'     => session('bo_id'),
                    'image'       => $request->file('image')->store('public/news'),
                    'title'       => $request->title,
                    'slug'        => Str::slug($request->title, '-'),
                    'description' => $request->description,
                    'status'      => $request->status
                ]);

                if($query) {
                    if($request->tags) {
                        foreach($request->tags as $t) {
                            NewsTags::create([
                                'news_id' => $query->id,
                                'tags'    => $t
                            ]);
                        }
                    }

                    activity()
                        ->performedOn(new News())
                        ->causedBy(session('bo_id'))
                        ->withProperties($query)
                        ->log('Add news data');

                    return redirect()->back()->with(['success' => 'Data added successfully.']);
                } else {
                    return redirect()->back()->withInput()->with(['failed' => 'Data failed to added.']);
                }
            }
        } else {
            $data = [
                'title'    => 'Create New News',
                'category' => Category::where('type', 2)->get(),
                'content'  => 'admin.master_data.digital.news_create'
            ];

            return view('admin.layouts.index', ['data' => $data]);
        }
    }

    public function update(Request $request, $id)
    {
        $query = News::find($id);
        if(!$query) {
            abort(404);
        }
        
        if($request->has('_token') && session()->token() == $request->_token) {
            $validation = Validator::make($request->all(), [
                'image'       => 'image|mimes:jpg,jpeg,png|max:100|dimensions:width=1920,height=850',
                'category_id' => 'required',
                'title'       => 'required',
                'description' => 'required'
            ], [
                'image.image'          => 'File must be an image.',
                'image.mimes'          => 'Image must have an extension jpg, jpeg, png.',
                'image.max'            => 'Image max 100KB.',
                'image.dimensions'     => 'Image size must be 1920x850.',
                'category_id.required' => 'Please select a category.',
                'title.required'       => 'Title cannot be empty.',
                'description.required' => 'Description cannot be empty.'
            ]);

            if($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            } else {
                if($request->has('image')) {
                    if(Storage::exists($query->image)) {
                        Storage::delete($query->image);
                    }

                    $image = $request->file('image')->store('public/news');
                } else {
                    $image = $query->image;
                }

                $query->update([
                    'category_id' => $request->category_id,
                    'user_id'     => session('bo_id'),
                    'image'       => $image,
                    'title'       => $request->title,
                    'slug'        => Str::slug($request->title, '-'),
                    'description' => $request->description,
                    'status'      => $request->status
                ]);

                if($query) {
                    NewsTags::where('news_id', $id)->delete();
                    if($request->tags) {
                        foreach($request->tags as $t) {
                            NewsTags::create([
                                'news_id' => $id,
                                'tags'    => $t
                            ]);
                        }
                    }

                    activity()
                        ->performedOn(new News())
                        ->causedBy(session('bo_id'))
                        ->log('Change the news data');

                    return redirect()->back()->with(['success' => 'Data updated successfully.']);
                } else {
                    return redirect()->back()->withInput()->with(['failed' => 'Data failed to update..']);
                }
            }
        } else {
            $data = [
                'title'    => 'Update News',
                'news'     => $query,
                'category' => Category::where('type', 2)->get(),
                'content'  => 'admin.master_data.digital.news_update'
            ];

            return view('admin.layouts.index', ['data' => $data]);
        }
    }

    public function destroy(Request $request) 
    {
        $query = News::where('id', $request->id)->delete();
        if($query) {
            activity()
                ->performedOn(new News())
                ->causedBy(session('bo_id'))
                ->log('Delete the news data');

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

    public function detail($id)
    {
        $data = [
            'title'   => 'Detail News',
            'news'    => News::find($id),
            'content' => 'admin.master_data.digital.news_detail'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

}
