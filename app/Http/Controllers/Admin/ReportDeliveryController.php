<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ProjectDelivery;
use App\Models\ProjectMainPayment;
use App\Models\ProjectPay;
use Illuminate\Http\Request;

class ReportDeliveryController extends Controller
{

    public function invoiceDeliveryStatus()
    {
        $data = [
            'title'           => 'Invoice & Delivery Status',
            'customer'        => Customer::all(),
            'content'         => 'admin.report.delivery_order.invoice_delivery_status'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }


    public function datatableInvoice(Request $request)
    {
        $column = [
            'id',
            'created_at',
            'user_id',
            'code',
            'code',

        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = ProjectDelivery::count();

        $query_data = ProjectDelivery::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('created_at', 'like', "%$search%");
                })->orWhereHas('project', function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%");
                });
            }

            if ($request->month) {
                $query->whereRaw("LEFT(DATE(created_at), 7) = '$request->month'");
            }

            if ($request->branch) {
                $query->whereHas('projectSale', function ($query) use ($request) {
                    $query->whereHas('sales', function ($query) use ($request) {
                        $query->where('branch', $request->branch);
                    });
                });
            }

            if ($request->customer) {
                $query->whereHas('project', function ($query) use ($request) {
                    $query->whereHas('customer', function ($query) use ($request) {
                        $query->where('id', $request->customer);
                    });
                });
            }

            if ($request->sales) {
                $query->whereHas('project', function ($query) use ($request) {
                    $query->whereHas('sales', function ($query) use ($request) {
                        $query->where('id', $request->sales);
                    });
                });
            }
        })->orderBy('created_at', 'DESC')
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectDelivery::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('created_at', 'like', "%$search%");
                })->orWhereHas('project', function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%");
                });
            }

            if ($request->month) {
                $query->whereRaw("LEFT(DATE(created_at), 7) = '$request->month'");
            }

            if ($request->branch) {
                $query->whereHas('projectSale', function ($query) use ($request) {
                    $query->whereHas('sales', function ($query) use ($request) {
                        $query->where('branch', $request->branch);
                    });
                });
            }

            if ($request->customer) {
                $query->whereHas('project', function ($query) use ($request) {
                    $query->whereHas('customer', function ($query) use ($request) {
                        $query->where('id', $request->customer);
                    });
                });
            }

            if ($request->sales) {
                $query->whereHas('project', function ($query) use ($request) {
                    $query->whereHas('sales', function ($query) use ($request) {
                        $query->where('id', $request->sales);
                    });
                });
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;

            foreach ($query_data as $val) {

                $button = '<a href="' . url('admin/delivery_order/project/print/sales_proforma/' . base64_encode($val->id)) . '" target="blank" class="btn bg-primary" data-popup="tooltip" title="Redirect to project"><i class="icon-file-pdf"></i></a>';

                $button_other = '<a href="' . url('admin/delivery_order/project/print/sales_proforma_other/' . base64_encode($val->id)) . '" target="blank" class="btn bg-primary" data-popup="tooltip" title="Redirect to project"><i class="icon-file-pdf"></i></a>';

                $response['data'][] = [
                    '<span data-id="' . $val->id . '">' . $nomor . '</span>',
                    date('d M Y', strtotime($val->created_at)),
                    $val->project->code,
                    $val->code,
                    $val->projectSale()->count() > 0 ? strtoupper($val->projectSale()->first()->sales->name) : '',
                    $val->project->customer->name,
                    $button,
                    $button_other,
                    $val->received_date ? '<span class="badge badge-success">Delivered</span>' : '<span class="badge badge-warning">Pending</span>',
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



    public function projectPaymentReport(Request $request)
    {

        $filter = $request->filter ? $request->filter : date('Y-m');

        $data = [
            'title'           => 'Report Customer Payment Monthly',
            'content'         => 'admin.report.delivery_order.payment',
            'filter'          => $filter
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }


    public function datatable(Request $request)
    {
        $column = [
            'detail',
            'id',
            'user_id',
            'code',
            'date',
            'coa_id',
            'customer_id',
            'nominal',
            'note',
            'image',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->filter ? $request->filter : date('Y-m');

        $total_data = ProjectMainPayment::whereRaw('SUBSTR(date, 1, 7) = "' . $filter . '"')
            ->orderBy('DATE', 'ASC')->count();

        $query_data = ProjectMainPayment::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('date', 'like', "%$search%")
                        ->orWhere('code', 'like', "%$search%")
                        ->orWhere('nominal', 'like', "%$search%")
                        ->orWhere('note', 'like', "%$search%");
                })->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })->orWhereHas('coa', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            }
        })
            ->whereRaw('SUBSTR(date, 1, 7) = "' . $filter . '"')
            ->orderBy('DATE', 'ASC')
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = ProjectMainPayment::where(function ($query) use ($search, $request) {
            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('date', 'like', "%$search%")
                        ->orWhere('code', 'like', "%$search%")
                        ->orWhere('nominal', 'like', "%$search%")
                        ->orWhere('note', 'like', "%$search%");
                })->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })->orWhereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })->orWhereHas('coa', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            }
        })
            ->whereRaw('SUBSTR(date, 1, 7) = "' . $filter . '"')
            ->orderBy('DATE', 'ASC')
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;

            foreach ($query_data as $val) {

                if ($val->image) {
                    if (explode('.', $val->image)[1] == 'pdf') {
                        $photo = '<a href="' . $val->attachment() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>';
                    } else {
                        $photo = '<a data-magnify="gallery" data-src="" data-caption="' . $val->item . '" data-group="a" href="' . $val->attachment() . '"><img src="' . $val->attachment() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a></a>';
                    }
                } else {
                    $photo = '<span class="badge badge-danger">Empty</span>';
                }


                $response['data'][] = [
                    '<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $nomor,
                    $val->user->name,
                    date('d M Y', strtotime($val->date)),
                    $val->coa->name,
                    $val->customer->name,
                    number_format($val->nominal, 0, ',', '.'),
                    $val->note,
                    $photo,
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

    public function rowDetail(Request $request)
    {
        $data   = ProjectMainPayment::find($request->id);

        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>Project</th>
							<th>SO No.</th>
							<th>DO No.</th>
							<th>Bill No.</th>
							<th>Total</th>
							<th>Approved By</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody>';

        foreach ($data->projectPay as $row) {
            $string .= '
				<tr>
					<td class="text-center">' . $row->project->name . '</td>
					<td class="text-center">' . $row->projectSale->code . '</td>
					<td class="text-center">' . ($row->projectDelivery ? $row->projectDelivery->code : 'None') . '</td>
					<td class="text-center">' . ($row->projectBill ? $row->projectBill->code : 'None') . '</td>
					<td class="text-right">' . number_format($row->nominal, 0, ',', '.') . '</td>
					<td class="text-center">' . (isset($row->approved) ? '<span class="badge badge-success">' . ($row->approved->name) . '</span>' : '<span class="badge badge-warning">Waiting</span>') . '</td>
					<td class="text-center">' . (isset($row->projectDelivery) ? '<a href="javascript:void(0);" onclick="showDetailProduct(this, ' . $row->id . ')">' . $row->projectDelivery->code . '</a>' : '<a href="javascript:void(0);" onclick="showDetailProduct(this, ' . $row->id . ')">' . $row->projectBill->project->code . '</a>') . '</td>
				</tr>
			';
        }

        $string .= '</tbody></table>';

        return response()->json($string);
    }

    public function showDetailproduct(Request $request)
    {

        $query = ProjectPay::where('id', $request->id)->first();
        $data = [];
        $totalqtybox = 0;
        $totalqtytile = 0;
        $total = 0;
        $totaltile = 0;

        if (isset($query->projectDelivery)) {
            foreach ($query->projectDelivery->projectDeliveryProduct as $val) {
                $data[] = [
                    'product_id'   => $val->product_id,
                    'product_name' => $val->product->name(),
                    'qty'          => $val->qty,
                    'price'        => $val->salePrice()
                ];
            }
        } else {
            foreach ($query->projectBill->project->projectProduct as $pp) {
                if ($pp->unit == '2' || $pp->unit == '3') {
                    $price = $pp->best_price > 0 ? $pp->best_price : ($pp->recommended_price > 0 ? $pp->recommended_price : $pp->price);
                    $m2 = (($pp->product->type->length * $pp->product->type->width) / 10000) * $pp->product->carton_pcs;

                    if ($m2 < 1.1 && $pp->product->type->category->parent()->id !== 18) {
                        $countbox = ceil($pp->qty);
                        $total += $price * ($countbox);
                        $totaltile += $price * ($countbox);
                    } else {
                        if ($m2 < 1.1 && date('Y-m', strtotime($pp->project->created_at)) < '2022-06' && $pp->product->type->category->parent()->id == 18) {
                            $countbox = ceil($pp->qty);
                            $total += $price * ($countbox);
                            $totaltile += $price * ($countbox);
                        } else {
                            $countbox = ceil(round($pp->qty / $m2, 2));
                            $total += $price * $m2 * ($countbox);
                            $totaltile += $price * $m2 * ($countbox);
                        }
                    }

                    $totalqtybox += $countbox;
                    $totalqtytile += $pp->qty;


                    $data[] = [
                        'product_id'   => $pp->product_id,
                        'product_name' => $pp->product->name(),
                        'qty'          => $countbox,
                        'price'        => number_format($price, 0, ',', '.')
                    ];
                } else {
                    $price = $pp->best_price > 0 ? $pp->best_price : ($pp->recommended_price > 0 ? $pp->recommended_price : $pp->price);

                    $data[] = [
                        'product_id'   => $pp->product_id,
                        'product_name' => $pp->product->name(),
                        'qty'          => $pp->qty,
                        'price'        => number_format($price, 0, ',', '.')
                    ];
                }
            }
        }

        $response = [
            'data'          => $data,
            'letter_way'     => isset($query->projectDelivery) ? url('admin/report/project/details/print/delivery_order/' . base64_encode($query->projectDelivery->id)) : '',
            'invoice'        => isset($query->projectDelivery) ? url('admin/delivery_order/project/print/sales_proforma/' . base64_encode($query->projectDelivery->id)) : '',
            'invoice_other' => isset($query->projectDelivery) ? url('admin/delivery_order/project/print/sales_proforma_other/' . base64_encode($query->projectDelivery->id)) : '',
            'sales_bill' => isset($query->projectBill) ? url('admin/delivery_order/project/print/sales_bill/' . base64_encode($query->projectBill->id) . '?la=idn') : ''
        ];

        return response()->json($response);
    }

    public function print(Request $request)
    {
        $arrId = explode(',', $request->id);
        $result = ProjectDelivery::whereIn('id', $arrId)->orderBy('id')->get();


        $pdf = PDF::loadView(
            'admin.pdf.report.delivery_order.invoice_delivery',
            [
                'data'        => $result,
                'title'       => 'Invoice List Customer',
            ],
            [],
            [
                'format'      => 'A4-P',
                'orientation' => 'P'
            ]
        );

        return $pdf->stream('Invoice.pdf');
    }
}
