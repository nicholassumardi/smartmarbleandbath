<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalMemo;
use Illuminate\Http\Request;

class InternalMemoController extends Controller
{
    public function index()
    {
        $data = [
            'title'   			=> 'Internal Memo',
            'content' 			=> 'admin.inventory.internal_memo'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'user_id',
            'code',
            'note',
            'proof',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = InternalMemo::count();

        $query_data = InternalMemo::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->groupBy('id')
            ->get();

        $total_filtered = InternalMemo::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;

            foreach ($query_data as $val) {
                $btnAction = '<a href="' . url('admin/sales/sample/detail/' . $val->id) . '" class="btn bg-info btn-sm"><i class="icon-info22"></i></a>
                <button class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash"></i></button>';
                $response['data'][] = [
                    $nomor,
                    $val->user->name,
                    $val->code,
                    $val->note,
                    $val->proof,
                    $btnAction,
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
}
