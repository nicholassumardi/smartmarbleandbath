<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use App\Models\NewsTags;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function index(Request $request)
    {
        $news = News::where(function ($query) use ($request) {
            if ($request->category) {
                $query->whereHas('category', function ($query) use ($request) {
                    $query->where('slug', 'like', "%$request->category%");
                });
            }

            if ($request->tags) {
                $query->whereHas('newsTags', function ($query) use ($request) {
                    $query->where('tags', 'like', "%$request->tags%");
                });
            }
        })
            ->orderBy('created_at', 'DESC')
            ->where('status', 1)
            ->paginate(4)
            ->appends($request->except('page'));

        $data = [
            'title'    => 'News',
            'category' => Category::where('type', 2)->where('status', 1)->get(),
            'news'     => $news,
            'tags'     => NewsTags::groupBy('tags')->get(),
            'content'  => 'news'
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function detail(Request $request, $slug)
    {
        $news = News::where('slug', $slug)->first();
        if (!$news) {
            abort(404);
        }

        $related_news = News::where('slug', '!=', $slug)
            ->where('category_id', $news->category_id)
            ->where('status', 1)
            ->limit(4)
            ->inRandomOrder()
            ->get();

        $data = [
            'title'        => $news->title,
            'news'         => $news,
            'related_news' => $related_news,
            'content'      => 'news_detail'
        ];

        return view('layouts.index', ['data' => $data]);
    }
}
