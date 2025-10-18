<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class CheckStockController extends Controller
{
    public function index()
    {
        $data = [
            'title'     => 'Product Code',
            // 'company'   => Company::all(),
            // 'hs_code'   => HsCode::all(),
            // 'brand'     => Brand::all(),
            // 'country'   => Country::all(),
            // 'supplier'  => Supplier::all(),
            // 'grade'     => Grade::all(),
            // 'warehouse' => Warehouse::all(),
            // 'currency' => Currency::where('status', 1)->get(),
            'stock' => Stock::all(),
            'content'   => 'admin.master_data.product_code'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'photo',
            'name',
            'email',
            'phone',
            'type',
            'verification',
            'created_at'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Stock::count();

        $query_data = Stock::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            }

            if ($request->type) {
                $query->where('type', $request->type);
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Stock::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            }

            if ($request->type) {
                $query->where('type', $request->type);
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach ($query_data as $val) {
                $photo = '<a href="' . $val->photo() . '" data-lightbox="' . $val->name . '" data-title="' . $val->name . '"><img src="' . $val->photo() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>';

                $response['data'][] = [
                    $nomor,
                    $photo,
                    $val->name,
                    $val->email,
                    $val->phone,
                    $val->type(),
                    $val->verification ? date('d F Y', strtotime($val->created_at)) : 'Not Verified',
                    date('d F Y', strtotime($val->created_at)),
                    '
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
}
