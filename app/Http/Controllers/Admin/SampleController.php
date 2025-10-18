<?php

namespace App\Http\Controllers\Admin;

use App\Helper\CheckCutOff;
use App\Helper\SendMessage;
use PDF;
use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\CashBank;
use App\Models\CashBankDetail;
use App\Models\Customer;
use App\Models\CustomerSample;
use App\Models\Dropshipper;
use App\Models\Journal;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Sample;
use App\Models\SampleDelivery;
use App\Models\SampleDeliveryProduct;
use App\Models\SampleDeliveryTrack;
use App\Models\SampleNote;
use App\Models\SampleProduct;
use App\Models\SampleProductShading;
use App\Models\SampleProforma;
use App\Models\SamplePurchase;
use App\Models\SamplePurchaseProduct;
use App\Models\SamplePurchaseReturn;
use App\Models\SamplePurchaseReturnProduct;
use App\Models\SampleReturn;
use App\Models\SampleReturnMemo;
use App\Models\SampleReturnMemoDetail;
use App\Models\SampleReturnProduct;
use App\Models\SampleShipment;
use App\Models\SampleShipmentProduct;
use App\Models\SampleShipmentTrack;
use App\Models\SampleWarehouse;
use App\Models\SampleWarehouseProduct;
use App\Models\StockSample;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SampleController extends Controller
{
    public function index()
    {
        $data = [
            'title'     => 'Sample',
            'customer'  => Customer::all(),
            'content'   => 'admin.sales.sample'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request)
    {
        $column = [
            'id',
            'user_id',
            'customer_id',
            'code',
            'progress',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $salesbranch = User::find(session('bo_id'))->branch;

        $total_data = CustomerSample::whereHas('user', function($query) use ($salesbranch){
            if($salesbranch != 1){
                $query->where('branch', '2');
            }
        })->count();

        $query_data = CustomerSample::where(function ($query) use ($search, $salesbranch, $request) {
            if($salesbranch == '1'){
                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('customer', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('user', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('sample', function ($query) use ($search){
                                $query->whereHas('sampleProduct', function($query) use ($search){
                                    $query->whereHas('product', function($query) use ($search) {
                                        $query->whereHas('type', function($query) use ($search) {
                                            $query->where('code', 'like', "%$search%");
                                        });
                                    });
                                });
                            });
                    });
                }
            }else{
                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('customer', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('user', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('sample', function ($query) use ($search){
                                $query->whereHas('sampleProduct', function($query) use ($search){
                                    $query->whereHas('product', function($query) use ($search) {
                                        $query->whereHas('type', function($query) use ($search) {
                                            $query->where('code', 'like', "%$search%");
                                        });
                                    });
                                });
                            });
                    });
                }
                
                $query->whereHas('user', function($query) {
                    $query->where('branch','2');
                });
            }
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->groupBy('id')
            ->get();

        $total_filtered = CustomerSample::where(function ($query) use ($search, $salesbranch, $request) {
            if($salesbranch == '1'){
                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('customer', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('user', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('sample', function ($query) use ($search){
                                $query->whereHas('sampleProduct', function($query) use ($search){
                                    $query->whereHas('product', function($query) use ($search) {
                                        $query->whereHas('type', function($query) use ($search) {
                                            $query->where('code', 'like', "%$search%");
                                        });
                                    });
                                });
                            });
                    });
                }
            }else{
                if ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhereHas('customer', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('user', function ($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            }) 
                            ->orWhereHas('sample', function ($query) use ($search){
                                $query->whereHas('sampleProduct', function($query) use ($search){
                                    $query->whereHas('product', function($query) use ($search) {
                                        $query->whereHas('type', function($query) use ($search) {
                                            $query->where('code', 'like', "%$search%");
                                        });
                                    });
                                });
                            });
                    });
                }
                $query->whereHas('user', function($query) {
                    $query->where('branch','2');
                });
            }
        })
            ->count();

        $response['data'] = [];
        if ($query_data <> FALSE) {
            $nomor = $start + 1;
     

            foreach ($query_data as $val) {
                $progress = '
                <div class="progress" style="height:0.875rem;">
                    <div class="progress-bar progress-bar-striped bg-teal" style="width:100%">
                        <span class="font-weight-bold text-uppercase">
                            <span style="font-size:13px;">' . $val->progress . '%</span>
                        </span>
                    </div>
                </div>';
                $btnAction = '<a href="' . url('admin/sales/sample/detail/' . $val->id) . '" class="btn bg-info btn-sm"><i class="icon-info22"></i></a>
                <button class="btn bg-danger btn-sm" data-popup="tooltip" title="Delete" onclick="destroy(' . $val->id . ')"><i class="icon-trash"></i></button>';
                $response['data'][] = [
                    $nomor,
                    $val->code,
                    $val->user->name,
                    $val->customer->name,
                    $progress,
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

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'sample_sent_date'    => 'required',
            'sample_return_date'  => 'required',
            'sample_product_id'   => 'required|array',
            'sample_qty'          => 'required|array',
            'sample_unit'         => 'required|array',
            'sample_size'         => 'required|array'
        ], [
            'sample_sent_date.required'      => 'Sample sent date cannot empty.',
            'sample_return_date.required'    => 'Sample return date cannot empty.',
            'sample_product_id.required'     => 'Please select a product.',
            'sample_product_id.array'        => 'Product id must be array.',
            'sample_qty.required'            => 'Qty cannot empty.',
            'sample_qty.array'               => 'Qty must be array.',
            'sample_unit.required'           => 'Unit cannot empty.',
            'sample_unit.array'              => 'Unit must be array.',
            'sample_size.required'           => 'Size cannot empty.',
            'sample_size.array'              => 'Size must be array.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status'   => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = CustomerSample::create([
                'user_id'       => session('bo_id'),
                'customer_id'   => $request->customer_id,
                'code'          => CustomerSample::generateCode(),
                'progress'      => 25,
            ]);

            if ($query) {
                $sample = Sample::create([
                    'customer_sample_id'   => $query->id,
                    'sales_id'             => $request->sales_so,
                    'sent_date'            => $request->sample_sent_date,
                    'return_date'          => $request->sample_return_date,
                    'note'                 => $request->sample_note,
                    'code'                 => Sample::generateCode(),
                    'status'               => '1',
                    'approved_by_1'        => 0,
                    'approved_by_2'        => 0,
                ]);
                foreach ($request->sample_product_id as $key => $pi) {
                    $sample_product =  SampleProduct::create([
                        'sample_id'          => $sample->id,
                        'product_id'         => $pi,
                        'qty'                => $request->sample_qty[$key],
                        'unit'               => $request->sample_unit[$key],
                        'size'               => $request->sample_size[$key]
                    ]);


                    $qty = 0;

                    if ($sample_product->unit == '2' || $sample_product->unit == '3') {
                        $m2 = (($sample_product->product->type->length * $sample_product->product->type->width) / 10000) * $sample_product->product->carton_pcs;

                        if ($m2 < 1.1) {
                            $countbox = $sample_product->qty;
                        } else {
                            $countbox = ceil(round($sample_product->qty / $m2, 2));
                        }

                        $qty = $countbox;
                    } else {
                        $qty = $sample_product->qty;
                    }

                    $temprest = 0;
                    $rest = $qty;
                    if (count($sample_product->product->productShading) > 0) {
                        foreach ($sample_product->product->productShading()->orderByDesc('qty')->get() as $shading) {

                            if ($rest >= $shading->qty) {
                                $used = $shading->qty;
                            } else {
                                $used = $rest;
                            }

                            if ($rest >= 0 && $used > 0) {
                                SampleProductShading::create([
                                    'sample_id' => $sample->id,
                                    'product_id' => $shading->product_id,
                                    'warehouse_code' => $shading->warehouse_code,
                                    'stock_code' => $shading->stock_code,
                                    'code' => $shading->code,
                                    'qty' => $used,
                                ]);
                            }

                            if ($rest >= $shading->qty) {
                                $rest -= $shading->qty;
                                $temprest = $rest;
                            } else {
                                $rest = -1;
                            }
                        }
                    }

                    #start notif
                    $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
                    $title = 'Sample has been created';
                    $description = 'Sample ' . $query->code . ' products has been created by ' . session('bo_name');
                    $link = '#';
                    Notification::sendNotif($role, $title, $description, $link);
                    #end notif

                    #send approval
                    $roleapproval = array('4');
                    Approval::sendApproval($roleapproval, 'samples', $sample->id, 'approved_by_1', session('bo_id'));
                    $roleapproval = array('5');
                    Approval::sendApproval($roleapproval, 'samples', $sample->id, 'approved_by_2', session('bo_id'));
                    #end approval

                    activity()
                        ->performedOn(new Sample())
                        ->causedBy(session('bo_id'))
                        ->log('Create New Sample' . $query->name . '');

                    $response = [
                        'status' => 200,
                        'message' => 'Sample created successfully'
                    ];
                }
            } else {
                $response = [
                    'status' => 500,
                    'message' => 'Sample failed to create'
                ];
            }
        };

        return response()->json($response);
    }

    public function detail(Request $request, $id)
    {
        // $sample = Sample::find(22);
        // $sampleProduct = SampleProduct::where('sample_id', $sample->id)->get();
        // $data = [];

        // $shading = '';

        // foreach ($sampleProduct as $sp) {
        //   dd($sp->product->name());
        // }

        $sample = CustomerSample::find($id);
        if (!$sample) {
            abort(404);
        }

        if ($request->has('_token') && session()->token() == $request->_token) {
        } else {
            $data = [
                'title'           => 'Sample Details',
                'sample'          => Sample::where('customer_sample_id', $id)->get(),
                'customer_sample' => CustomerSample::where('id', $id)->first(),
                'vendor'          => Vendor::all(),
                'dropshipper'     => Dropshipper::all(),
                'content'         => 'admin.sales.sample_detail'
            ];
            return view('admin.layouts.index', ['data' => $data]);
        }
    }

    public function addSampleProof(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'return_proof'           => 'required',
            'tempsample'             => 'required'
        ], [
            'return_proof.required'  => 'Proof cannot be empty.',
            'tempsample.required'    => 'Sample information cannot be empty.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $sample = Sample::find($request->tempsample);

            if ($request->has('return_proof')) {
                if (Storage::exists($sample->return_proof)) {
                    Storage::delete($sample->return_proof);
                }

                $image = $request->file('return_proof')->store('public/sample');
            } else {
                $image = $sample->return_proof;
            }

            $sample->update([
                'return_proof' => $image
            ]);

            activity()
                ->performedOn(new Sample())
                ->causedBy(session('bo_id'))
                ->withProperties($sample)
                ->log('Edit sample add proof ' . session('bo_name'));

            $response = [
                'status'  => 200,
                'message' => 'Data added successfully.'
            ];
        }

        return response()->json($response);
    }

    public function edit(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'sample_sent_date'    => 'required',
            'sample_return_date'  => 'required',
            'sample_product_id'   => 'required|array',
            'sample_qty'          => 'required|array',
            'sample_unit'         => 'required|array',
            'sample_size'         => 'required|array'
        ], [
            'sample_sent_date.required'      => 'Sample sent date cannot empty.',
            'sample_return_date.required'    => 'Sample return date cannot empty.',
            'sample_product_id.required'     => 'Please select a product.',
            'sample_product_id.array'        => 'Product id must be array.',
            'sample_qty.required'            => 'Qty cannot empty.',
            'sample_qty.array'               => 'Qty must be array.',
            'sample_unit.required'           => 'Unit cannot empty.',
            'sample_unit.array'              => 'Unit must be array.',
            'sample_size.required'           => 'Size cannot empty.',
            'sample_size.array'              => 'Size must be array.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status'   => 422,
                'error'  => $validation->errors()
            ];
        } else {


            if($request->temp_sample_id){
              $query =  Sample::find($request->temp_sample_id);


               $query->update([
                    'customer_sample_id'   => $id,
                    'sales_id'             => $request->sales_so,
                    'sent_date'            => $request->sample_sent_date,
                    'return_date'          => $request->sample_return_date,
                    'note'                 => $request->sample_note,
                    'status'               => '1',
                    'approved_by_1'        => 0,
                    'approved_by_2'        => 0
                ]);

                $query->deleteFile();
                $query->deleteDetail();
                $query->deleteApproval();

            }else{
                $query = Sample::create([
                    'customer_sample_id'   => $id,
                    'sales_id'             => $request->sales_so,
                    'sent_date'            => $request->sample_sent_date,
                    'return_date'          => $request->sample_return_date,
                    'note'                 => $request->sample_note,
                    'code'                 => Sample::generateCode(),
                    'status'               => '1',
                    'approved_by_1'        => 0,
                    'approved_by_2'        => 0
                ]);
            }

            if ($query) {
                foreach ($request->sample_product_id as $key => $pi) {
                    $sample_product =  SampleProduct::create([
                        'sample_id'          => $query->id,
                        'product_id'         => $pi,
                        'qty'                => $request->sample_qty[$key],
                        'unit'               => $request->sample_unit[$key],
                        'size'               => $request->sample_size[$key]
                    ]);

                    $qty = 0;

                    if ($sample_product->unit == '2' || $sample_product->unit == '3') {
                        $m2 = (($sample_product->product->type->length * $sample_product->product->type->width) / 10000) * $sample_product->product->carton_pcs;

                        if ($m2 < 1.1) {
                            $countbox = $sample_product->qty;
                        } else {
                            $countbox = ceil(round($sample_product->qty / $m2, 2));
                        }

                        $qty = $countbox;
                    } else {
                        $qty = $sample_product->qty;
                    }

                    $temprest = 0;
                    $rest = $qty;
                    if (count($sample_product->product->productShading) > 0) {
                        foreach ($sample_product->product->productShading()->orderByDesc('qty')->get() as $shading) {

                            if ($rest >= $shading->qty) {
                                $used = $shading->qty;
                            } else {
                                $used = $rest;
                            }

                            if ($rest >= 0 && $used > 0) {
                                SampleProductShading::create([
                                    'sample_id' => $query->id,
                                    'product_id' => $shading->product_id,
                                    'warehouse_code' => $shading->warehouse_code,
                                    'stock_code' => $shading->stock_code,
                                    'code' => $shading->code,
                                    'qty' => $used,
                                ]);
                            }

                            if ($rest >= $shading->qty) {
                                $rest -= $shading->qty;
                                $temprest = $rest;
                            } else {
                                $rest = -1;
                            }
                        }
                    }

                    $response = [
                        'status' => 200,
                        'message' => 'Sample created successfully'
                    ];

                    #start notif
                    $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
                    $title = 'Sample has been created';
                    $description = 'Sample ' . $query->code . ' products has been created by ' . session('bo_name');
                    $link = '#';
                    Notification::sendNotif($role, $title, $description, $link);
                    #end notif

                    #send approval
                    $roleapproval = array('4');
                    Approval::sendApproval($roleapproval, 'samples', $query->id, 'approved_by_1', session('bo_id'));
                    $roleapproval = array('5');
                    Approval::sendApproval($roleapproval, 'samples', $query->id, 'approved_by_2', session('bo_id'));
                    #end approval

                    activity()
                        ->performedOn(new Sample())
                        ->causedBy(session('bo_id'))
                        ->log('Create New Sample' . $query->name . '');
                }
            }

           
        }
        return response()->json($response);
    }

    public function showSampleEdit(Request $request, $id){
        $query = SampleProduct::where('sample_id', $id)->get();
        $sample = Sample::find($id);
        $data = [];

        foreach ($query as $val) {
            $data[] = [
                'product_name'  => $val->product->name(),
                'convert_unit'  => $val->unit(),
                'convert_size'  => $val->size(),
                'qty'           => $val->qty,
                'unit'          => $val->unit,
                'size'          => $val->size,
                'product_id'    => $val->product_id,
            ];
        }

       $response = [
        'status'      => 200,
        'sample'      => $sample,
        'sales_name'  => $sample->sales->name,
        'data'        => $data,
       ];
        
        return response()->json($response);
        
    }

    public function getProduct(Request $request)
    {
        $data  = Product::find($request->id);
        $price = $data->cogs;
        $image = '<a href="' . $data->type->image() . '" data-lightbox="' . $data->name() . '" data-title="' . $data->name() . '"><img src="' . $data->type->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>';

        $salesinfo = User::find(session('bo_id'));

        $m2 = (($data->type->length * $data->type->width) / 10000) * $data->carton_pcs;

        if ($data->type->category->parent()->exists()) {
            if ($m2 < 1.1 && $data->type->category->parent()->id !== 18) {
                $m2 = 1;
            }
        }

        if ($salesinfo->branch == '1') {
            return response()->json([
                'id'              => $data->id,
                'product'         => $image . '<div><a href="javascript:void(0);" onclick="getShading(' . $data->id . ')">' . $data->name() . '</a></div><div>' . $data->type->length . 'x' . $data->type->width . '</div>',
                'price'           => isset($price) ? $data->pricingPolicy->price_list : 0,
                'cogs'            => isset($price) ? $price->formula()->cogs_pta_idr : 0,
                'bottom'          => isset($data->pricingPolicy->bottom_price) ? $data->pricingPolicy->bottom_price : 0,
                'surface'         => $data->type->surface->name,
                'sell_unit'       => $data->type->selling_unit_id,
                'carton_pcs'      => $data->carton_pcs,
                'carton_sqm'      => (($data->type->length * $data->type->width) / 10000) * $data->carton_pcs . ' M<sup>2</sup>',
                'sqm'             => $m2
            ]);
        } elseif ($salesinfo->branch == '2') {
            return response()->json([
                'id'              => $data->id,
                'product'         => $image . '<div><a href="javascript:void(0);" onclick="getShading(' . $data->id . ')">' . $data->name() . '</a></div><div>' . $data->type->length . 'x' . $data->type->width . '</div>',
                'price'           => $price ? $data->pricingPolicy->price_list : 0,
                'cogs'            => $price ? $price->formula()->cogs_smb_idr : 0,
                'bottom'          => isset($data->pricingPolicy->bottom_price) ? $data->pricingPolicy->bottom_price : 0,
                'surface'         => $data->type->surface->name,
                'sell_unit'       => $data->type->selling_unit_id,
                'carton_pcs'      => $data->carton_pcs,
                'carton_sqm'      => (($data->type->length * $data->type->width) / 10000) * $data->carton_pcs . ' M<sup>2</sup>',
                'sqm'             => $m2
            ]);
        }
    }

    public function updateStatusSample(Request $request)
    {
        $sample = Sample::find($request->id);
        $sample->status = $request->val;

        if ($request->val == '1' || $request->val == '3') {
            $sample->returned_at = null;
        } elseif ($request->val == '2') {
            $sample->returned_at = now();

           $productdesc = '';
			
			foreach($sample->SampleProduct as $key => $spi) {
				$productdesc .= '. Detail products : '.$spi->product->name().', Qty : '.$spi->qty.' '.$spi->unit().', Size : '.$spi->size().'<br>';
			}
			
			// $debetcb = 332;
								
			// $kreditcb = 341;
			
			// $cb = CashBank::create([
			// 	'user_id'     		=> session('bo_id'),
			// 	'lookable_type'     => '_samples',
			// 	'lookable_id'		=> $sample->id,
			// 	'code'        		=> strtoupper(Str::random(15)),
			// 	'date'        		=> date('Y-m-d',strtotime($sample->returned_at)),
			// 	'type'        		=> '2',
			// 	'description' 		=> ' Sample Code '.$sample->code.' in  code '.$sample->code.' with Customer '.$sample->customer->name.' '.$productdesc.' has been returned.'
			// ]);
			
			// if($cb){
			// 	CashBankDetail::create([
			// 		'cash_bank_id' 	=> $cb->id,
			// 		'coa_id'       	=> $debetcb,
			// 		'branch'		=> '1',
			// 		'type'       	=> '1',
			// 		'nominal'      	=> 1,
			// 		'note'         	=> ''
			// 	]);
				
			// 	Journal::insert([
			// 		'date_transaction' => date('Y-m-d',strtotime($sample->returned_at)),
			// 		'journalable_type' => 'cash_banks',
			// 		'journalable_id'   => $cb->id,
			// 		'coa_id'           => $debetcb,
			// 		'branch'		   => '1',
			// 		'type'	           => '1',
			// 		'nominal'          => 1,
			// 		'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
			// 		'updated_at'       => date('Y-m-d H:i:s')
			// 	]);
				
			// 	CashBankDetail::create([
			// 		'cash_bank_id' 	=> $cb->id,
			// 		'coa_id'       	=> $kreditcb,
			// 		'branch'		=> '1',
			// 		'type'       	=> '2',
			// 		'nominal'      	=> 1,
			// 		'note'         	=> ''
			// 	]);

			// 	Journal::insert([
			// 		'date_transaction' => date('Y-m-d',strtotime($sample->returned_at)),
			// 		'journalable_type' => 'cash_banks',
			// 		'journalable_id'   => $cb->id,
			// 		'coa_id'           => $kreditcb,
			// 		'branch'		   => '1',
			// 		'type'	           => '2',
			// 		'nominal'          => 1,
			// 		'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
			// 		'updated_at'       => date('Y-m-d H:i:s')
			// 	]);
			// } 

            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Sample has been updated!';
            $description = ' ' . $sample->code . ' with details sample code ' . $sample->code . ' products has been returned by ' . session('bo_name');
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif
        }

        $sample->save();

        activity()
            ->performedOn(new Sample())
            ->causedBy(session('bo_id'))
            ->withProperties($sample)
            ->log('Change status sample product');

        return response()->json([
            'status'  => 200,
            'message' => 'Data added successfully.'
        ]);
    }

    public function addShipmentTracking(Request $request)
    {

        $trackshipment = SampleShipmentTrack::create([
            'user_id'                => session('bo_id'),
            'sample_shipment_id'     => $request->id,
            'note'                   => $request->note
        ]);

        $shipmenttrack = SampleShipmentTrack::where('sample_shipment_id', $request->id)->get();

        activity()
            ->performedOn(new SampleShipmentTrack())
            ->causedBy(session('bo_id'))
            ->withProperties($trackshipment)
            ->log('Add tracking to shipment');

        return response()->json($shipmenttrack);
    }

    public function deleteShipmentTracking(Request $request)
    {

        $trackingshipment = SampleShipmentTrack::find($request->id)->delete();

        activity()
            ->performedOn(new SampleShipmentTrack())
            ->causedBy(session('bo_id'))
            ->withProperties($trackingshipment)
            ->log('Delete tracking to shipment');

        return response()->json([
            'status'  => 200,
            'message' => 'Data added successfully.'
        ]);
    }

    public function getShipmentTracking(Request $request)
    {
        $shipmenttrack = SampleShipmentTrack::where('sample_shipment_id', $request->id)->get();

        return response()->json($shipmenttrack);
    }

    public function getDeliveryTracking(Request $request)
    {
        $deliverytrack = SampleDeliveryTrack::where('sample_id', $request->id)->get();

        $data = [];

        foreach ($deliverytrack as $row) {
            $data[] = [
                'id'                      => $row->id,
                'user'                    => $row->user->name,
                'sample_id'               => $row->sample_id,
                'note'                    => $row->note,
                'image'                   => $row->image ? '<a href="' . $row->image() . '" data-lightbox="' . $row->sample->customerSample->code . '" data-title="' . $row->sample->customerSample->code . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>',
                'created_at'              => $row->created_at
            ];
        }

        return response()->json($data);
    }

    public function addDeliveryTrackingNote(Request $request)
    {
        $trackdelivery = SampleDeliveryTrack::create([
            'user_id'      => session('bo_id'),
            'sample_id'    => $request->id,
            'note'         => $request->note,
            'image'        => $request->file('file') ? $request->file('file')->store('public/sample') : ''
        ]);

        $deliverytrack = SampleDeliveryTrack::where('sample_id', $request->id)->get();

        $data = [];

        foreach ($deliverytrack as $row) {
            $data[] = [
                'id'                      => $row->id,
                'user'                    => $row->user->name,
                'sample_id'               => $row->sample_id,
                'note'                    => $row->note,
                'image'                   => $row->image ? '<a href="' . $row->image() . '" data-lightbox="' . $row->sample->customerSample->code . '" data-title="' . $row->sample->customerSample->code . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail mb-2"></a>' : '<span class="badge badge-secondary">None</span>',
                'created_at'              => $row->created_at
            ];
        }

        activity()
            ->performedOn(new SampleDeliveryTrack())
            ->causedBy(session('bo_id'))
            ->withProperties($trackdelivery)
            ->log('Add tracking to delivery sample');

        return response()->json($data);
    }


    public function createPOSupplier(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'sales_po'             => 'required',
            'supplier_id'          => 'required',
            'factory_name'         => 'required',
            'on_behalf'            => 'required',
            'delivery_address'     => 'required',
            'courier_method'       => 'required',
            'country_id'           => 'required',
            'city_id'              => 'required',
            'pic_name'             => 'required',
            'pic_number'           => 'required',
            'payment_method'       => 'required',
            'payment_due_date'     => 'required',
            'price'                => 'required',
            'currency'             => 'required',
            'currency_rate'        => 'required',
            'product_id'           => 'required|array',
            'product_unit'         => 'required|array',
            'product_qty'          => 'required|array',
            'product_price'        => 'required|array',
        ], [
            'sales_po.required'                 => 'Sales cannot empty.',
            'supplier_id.required'              => 'Supplier cannot empty.',
            'factory_name.required'             => 'Factory name cannot empty.',
            'on_behalf.required'                => 'On behalf info cannot empty.',
            'delivery_address.required'         => 'On behalf address cannot empty.',
            'courier_method.required'           => 'Courier info cannot empty.',
            'country_id.required'               => 'Country cannot empty.',
            'city_id.required'                  => 'City cannot empty.',
            'pic_name.required'                 => 'PIC Name cannot empty.',
            'pic_number.required'               => 'PIC Number cannot empty.',
            'city_id.required'                  => 'City cannot empty.',
            'payment_method.required'           => 'Payment method cannot empty.',
            'payment_due_date.required'         => 'Payment due date cannot empty.',
            'price.required'                    => 'Price cannot empty.',
            'currency.required'                 => 'Currency cannot empty.',
            'currency_rate.required'            => 'Currency rate cannot empty.',
            'product_id.required'               => 'Product cannot empty.',
            'product_id.array'                  => 'Product must be array.',
            'product_unit.required'             => 'Product unit cannot empty.',
            'product_unit.array'                => 'Product unit must be array.',
            'product_qty.required'              => 'Product qty cannot empty.',
            'product_qty.array'                 => 'Product qty must be array.',
            'product_price.required'            => 'Product price cannot empty.',
            'product_price.array'               => 'Product qty must be array.',
        ]);

        if ($validation->fails()) {
            $response = [
                'status'   => 422,
                'error'  => $validation->errors()
            ];
        } else {

            $query = CustomerSample::find($id);

            $query->update([
                'progress' => $query->progress < 55 ? 55 : $query->progress
            ]);

            if ($request->temp_po_id) {

                if ($request->edit_reason_po) {

                    $samplepurchase = SamplePurchase::find($request->temp_po_id);

                    $samplepurchase->user_id = session('bo_id');
                    $samplepurchase->customer_sample_id = $id;
                    $samplepurchase->sample_id = $request->sample_id;
                    $samplepurchase->ppn = '0';
                    $samplepurchase->note = $request->sales_note;
                    $samplepurchase->supplier_id = $request->supplier_id;
                    $samplepurchase->production_lead_time = $request->production_lead_time;
                    $samplepurchase->estimated_delivery = $request->est_delivery_date;
                    $samplepurchase->estimated_arrival = $request->est_arrival_date;
                    $samplepurchase->factory_name = $request->factory_name;
                    $samplepurchase->customer_id = $request->customer_id;
                    $samplepurchase->sales_id = $request->sales_po;
                    $samplepurchase->on_behalf = $request->on_behalf;
                    $samplepurchase->delivery_address    = $request->delivery_address;
                    $samplepurchase->country_id = $request->country_id;
                    $samplepurchase->city_id    = $request->city_id;
                    $samplepurchase->courier_method = $request->courier_method;
                    $samplepurchase->pic = $request->pic_name;
                    $samplepurchase->pic_no = $request->pic_number;
                    $samplepurchase->payment_method = $request->payment_method;
                    $samplepurchase->payment_due_date = $request->payment_due_date;
                    $samplepurchase->price = $request->price;
                    $samplepurchase->currency_id = $request->currency;
                    $samplepurchase->currency_rate = str_replace(',', '.', str_replace('.', '', $request->currency_rate));
                    $samplepurchase->brand_on_box = $request->brand;
                    $samplepurchase->sni = $request->sni;
                    $samplepurchase->is_wip = '0';
                    $samplepurchase->has_memo_item = $request->has_memo_item;
                    $samplepurchase->memo_address_item = $request->memo_address_item;
                    $samplepurchase->memo_up = $request->memo_up;
                    $samplepurchase->checked_by = 0;
                    $samplepurchase->created_at = $request->purchase_date . ' ' . date('H:i:s');
                    $samplepurchase->approved_by = 0;

                    $samplepurchase->update();


                    #start notif
                    $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
                    $title = 'Sample has been edited!';
                    $description = 'Sample ' . $samplepurchase->customerSample->code . ' with details Sample purchase code ' . $samplepurchase->code . ' has been edited by ' . session('bo_name') . ' with reason : ' . $request->edit_reason_po;
                    $link = '#';
                    Notification::sendNotif($role, $title, $description, $link);
                    #end notif
                    //}

                } else {
                    return redirect('admin/sales/sample/detail/' . $id . '?step-2=1#step-2')
                        ->withErrors('Please describe your reason.');
                }
            } else {
                $samplepurchase = SamplePurchase::create([
                    'user_id'                    => session('bo_id'),
                    'customer_sample_id'         => $id,
                    'sample_id'                  => $request->sample_id,
                    'ppn'                        => '0',
                    'code'                       => SamplePurchase::generateCode(),
                    'note'                       => $request->sales_note,
                    'supplier_id'                => $request->supplier_id,
                    'production_lead_time'       => $request->production_lead_time,
                    'estimated_delivery'         => $request->est_delivery_date,
                    'estimated_arrival'          => $request->est_arrival_date,
                    'factory_name'               => $request->factory_name,
                    'customer_id'                => $request->customer_id,
                    'sales_id'                   => $request->sales_po,
                    'on_behalf'                  => $request->on_behalf,
                    'delivery_address'           => $request->delivery_address,
                    'country_id'                 => $request->country_id,
                    'city_id'                    => $request->city_id,
                    'courier_method'             => $request->courier_method,
                    'pic'                        => $request->pic_name,
                    'pic_no'                     => $request->pic_number,
                    'payment_method'             => $request->payment_method,
                    'payment_due_date'           => $request->payment_due_date,
                    'price'                      => $request->price,
                    'currency_id'                => $request->currency,
                    'currency_rate'              => str_replace(',', '.', str_replace('.', '', $request->currency_rate)),
                    'brand_on_box'               => $request->brand,
                    'sni'                        => $request->sni,
                    'is_wip'                     => '0',
                    'has_memo_item'              => $request->has_memo_item,
                    'memo_address_item'          => $request->memo_address_item,
                    'memo_up'                    => $request->memo_up,
                    'checked_by'                 => 0,
                    'approved_by'                => 0,
                    'created_at'                 => $request->purchase_date . ' ' . date('H:i:s')
                ]);

            }

            SamplePurchase::find($samplepurchase->id)->updateGrandtotal();

            SamplePurchaseProduct::where('sample_purchase_id', $request->temp_po_id)->delete();

            if ($samplepurchase) {
                foreach ($request->product_id as $key => $pi) {
                    SamplePurchaseProduct::create([
                        'sample_purchase_id'       => $samplepurchase->id,
                        'product_id'               => $request->product_id[$key],
                        'qty'                      => $request->product_qty[$key],
                        'unit'                     => $request->product_unit[$key],
                        'price'                    => round(str_replace(',', '.', str_replace('.', '', str_replace('IDR ', '', $request->product_price[$key]))), 2),
                        'remark'                   => $request->product_remark[$key]
                    ]);
                }


                $roleapproval = array('5');
                Approval::sendApproval($roleapproval, 'sample_purchases', $samplepurchase->id, 'checked_by', session('bo_id'));
                $roleapproval = array('4');
                Approval::sendApproval($roleapproval, 'sample_purchases', $samplepurchase->id, 'approved_by', session('bo_id'));

                activity()
                    ->performedOn(new SamplePurchase())
                    ->causedBy(session('bo_id'))
                    ->withProperties($samplepurchase)
                    ->log('Add sample purchase');

                $response = [
                    'status' => 200,
                    'message' => 'Sample purchase successfully created'
                ];
            }

           
        }

        return response()->json($response);
    }

    public function deletePurchase(Request $request)
    {
        $pp = SamplePurchase::find($request->idpo);
        $reason = $request->reason;

        //if(count($pp->sampleWarehouse) == 0){
        Approval::where('approvalable_type', 'sample_purchases')->where('approvalable_id', $request->idpo)->delete();
        SamplePurchaseProduct::where('sample_purchase_id', $request->idpo)->delete();

        foreach ($pp->samplePurchaseReturn as $purchasereturn) {
            $cb = CashBank::where('lookable_type', 'sample_purchase_returns')->where('lookable_id', $purchasereturn->id)->get();
            foreach ($cb as $c) {
                $c->deleteDetail();
                $c->delete();
            }
            $purchasereturn->samplePurchaseReturnProduct()->delete();
            $purchasereturn->deleteFile();
            $purchasereturn->delete();
        }

        foreach ($pp->sampleProforma as $purchaseproforma) {
            $purchaseproforma->deleteFile();
            $purchaseproforma->delete();
        }

        foreach ($pp->sampleShipment as $shipment) {
            $shipment->sampleShipmentProduct()->delete();
            $shipment->sampleShipmentTrack()->delete();
            $shipment->deleteFile();
            $shipment->delete();
        }

        foreach ($pp->sampleWarehouse as $warehouse) {
            $cb = CashBank::where('lookable_type', 'sample_warehouses')->where('lookable_id', $warehouse->id)->get();
            foreach ($cb as $c) {
                $c->deleteDetail();
                $c->delete();
            }
            $warehouse->deleteFile();
            $warehouse->sampleWarehouseProduct()->delete();
            $warehouse->delete();
        }

        $pp->delete();

        #start notif
        $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
        $title = 'Sample purchase order ' . $pp->code . ' has been deleted!';
        $description = 'Sample purchase order ' . $pp->code . ' has been deleted by ' . session("bo_name") . ' with reason : ' . $reason;
        $link = '#';
        Notification::sendNotif($role, $title, $description, $link);
        #end notif

        activity()
            ->performedOn(new SamplePurchase())
            ->causedBy(session('bo_id'))
            ->withProperties($pp)
            ->log('Delete Sample purchase');

        return response()->json([
            'status'  => 200,
            'message' => 'Data added successfully.'
        ]);

        // $note = SampleNote::where('notable_type','sample_purchases')->where('notable_id',$pp->id)->get();

        // foreach($note as $n){
        // 	$n->deleteFile();
        // 	$n->delete();
        // }

        // $purchaserequest = PurchaseRequest::where('link_type','project_purchases')->where('link_id',$pp->id)->get();

        // foreach($purchaserequest as $row){
        // 	$row->deleteFile();
        // 	$row->delete();
        // }


        // $pc = PurchaseCost::where('project_purchase_id',$pp->id)->first();

        // if($pc){
        // 	$pc->purchaseCostDetail()->delete();
        // 	$pc->delete();
        // }


        // foreach($pp->samplePurchasePayment as $purchasePayment){
        // 	$purchasePayment->deleteFile();
        // 	$cb = CashBank::where('lookable_type','project_payments')->where('lookable_id',$purchasePayment->id)->get();
        // 	foreach($cb as $c){
        // 		$c->deleteDetail();
        // 		$c->delete();
        // 	}
        // 	$purchasePayment->delete();
        // }


        /* }else{
			return response()->json([
				'status'  => 400,
				'message' => 'PO already has Warehouse Receive, please contact developer.'
			]);
		} */
    }

    public function deletePurchaseReturn(Request $request)
    {
        $pp = SamplePurchaseReturn::find($request->id);
        $reason = $request->reason;


        $cb = CashBank::where('lookable_type', 'sample_purchase_returns')->where('lookable_id', $pp->id)->get();

        foreach ($cb as $c) {
            $c->deleteDetail();
            $c->delete();
        }

        foreach ($pp->samplePurchaseReturnProduct as $row) {
            $cek = StockSample::where('product_id', $row->product_id)->where('warehouse_id', $pp->warehouse_id)->where('branch', $pp->samplePurchase->sales->branch)->first();
            if ($cek) {
                $cek->update([
                    'qty'     => $cek->qty + $row->qty,
                    'unit'    => $row->unit
                ]);
            } else {
                StockSample::create([
                    'product_id'    => $row->product_id,
                    'warehouse_id'    => $pp->warehouse_id,
                    'qty'            => $row->qty,
                    'unit'            => $row->unit,
                    'branch'        => $pp->samplePurchase->sales->branch
                ]);
            }
        }

        $pp->samplePurchaseReturnProduct()->delete();
        $pp->deleteFile();
        $pp->delete();


        #start notif
        $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
        $title = 'Sample purchase return no ' . $pp->code . ' has been deleted!';
        $description = 'Sample purchase order ' . $pp->code . ' has been deleted by ' . session("bo_name") . ' with reason : ' . $reason;
        $link = '#';
        Notification::sendNotif($role, $title, $description, $link);
        #end notif

        activity()
            ->performedOn(new SamplePurchase())
            ->causedBy(session('bo_id'))
            ->withProperties($pp)
            ->log('Delete sample purchase return');

        return response()->json([
            'status'  => 200,
            'message' => 'Data added successfully.'
        ]);
    }

    public function addReturnPurchase(Request $request)
    {

        $purchase = SamplePurchase::find($request->id);

        if (CheckCutOff::check($purchase->sales->branch, substr($request->date, 0, 7))) {

        if (count($purchase->sampleWarehouse) > 0) {

            $image = '';

            if ($request->has('file')) {
                $image = $request->file('file')->store('public/sample');
            }

            $return = SamplePurchaseReturn::create([
                'user_id'               => session('bo_id'),
                'customer_sample_id'    => $purchase->customerSample ? $purchase->customerSample->id : 0,
                'sample_purchase_id'    => $request->id,
                'sample_warehouse_id'   => $request->warehouse,
                'code'                  => SamplePurchaseReturn::generateCode(),
                'date'                  => $request->date,
                'warehouse_id'          => $request->warehouse,
                'image'                 => $image,
                'note'                  => $request->note,
                'approved_by'           => 0
            ]);

            if ($request->arrProduct) {
                $nominal = 0;

                foreach ($request->arrProduct as $key => $ar) {
                    SamplePurchaseReturnProduct::create([
                        'sample_purchase_return_id'     => $return->id,
                        'product_id'                    => $ar,
                        'qty'                           => $request->arrQty[$key],
                        'unit'                          => $request->arrUnit[$key]
                    ]);


                    $spp = SamplePurchaseProduct::where('sample_purchase_id', $request->id)->where('product_id', $ar)->first();

                    $nominal += $request->arrQty[$key] * $spp->price;

                    #updatestock
                    $cek = StockSample::where('product_id', $ar)->where('warehouse_id', $request->warehouse)->where('branch', $return->samplePurchase->sales->branch)->first();
                    if ($cek) {
                        $cek->update([
                            'qty'     => $cek->qty - $request->arrQty[$key],
                            'unit'    => $request->arrUnit[$key]
                        ]);
                    } else {
                        StockSample::create([
                            'product_id'     => $ar,
                            'warehouse_id'   => $request->warehouse,
                            'qty'            => 0 - $request->arrQty[$key],
                            'unit'           => $request->arrUnit[$key],
                            'branch'         => $return->samplePurchase->sales->branch
                        ]);
                    }
                }

                $debetcb = 332;

                $kreditcb = 339;

                $cb = CashBank::create([
                    'user_id'             => session('bo_id'),
                    'lookable_type'       => 'sample_purchase_returns',
                    'lookable_id'         => $return->id,
                    'code'                => strtoupper(Str::random(15)),
                    'date'                => $request->date,
                    'type'                => '2',
                    'description'         => 'Sample return Code ' . $return->code
                ]);

                if ($cb) {
                    CashBankDetail::create([
                        'cash_bank_id'     => $cb->id,
                        'coa_id'           => $debetcb,
                        'branch'           => '1',
                        'type'             => '1',
                        'nominal'          => $nominal,
                        'note'             => ''
                    ]);

                    Journal::insert([
                        'date_transaction' => $request->date,
                        'journalable_type' => 'cash_banks',
                        'journalable_id'   => $cb->id,
                        'coa_id'           => $debetcb,
                        'branch'           => '1',
                        'type'             => '1',
                        'nominal'          => $nominal,
                        'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                        'updated_at'       => date('Y-m-d H:i:s')
                    ]);

                    CashBankDetail::create([
                        'cash_bank_id'     => $cb->id,
                        'coa_id'           => $kreditcb,
                        'branch'           => '1',
                        'type'             => '2',
                        'nominal'          => $nominal,
                        'note'             => ''
                    ]);

                    Journal::insert([
                        'date_transaction' => $request->date,
                        'journalable_type' => 'cash_banks',
                        'journalable_id'   => $cb->id,
                        'coa_id'           => $kreditcb,
                        'branch'           => '1',
                        'type'             => '2',
                        'nominal'          => $nominal,
                        'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                        'updated_at'       => date('Y-m-d H:i:s')
                    ]);
                }
            }

            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Purchase Order ' . $purchase->code . ' has been returned with Purchase Return code ' . $return->code . '!';
            $description = 'Sample Order ' . $purchase->code . ' with Purchase Return code ' . $return->code . ' has been returned by ' . session("bo_name") . ' with reason : ' . $request->note;
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif

            #send approval
            $roleapproval = array('5');
            Approval::sendApproval($roleapproval, 'sample_purchase_returns', $return->id, 'approved_by', session('bo_id'));

            SendMessage::send(env('SALES_MANAGER_PHONE'), 'Halo pak/bu. Mohon dibantu approve Purchase Return No. ' . $return->code . '. Berikut linknya : https://smartmarbleandbath.com/admin/approval. Terima kasih.');


            activity()
                ->performedOn(new SamplePurchaseReturn())
                ->causedBy(session('bo_id'))
                ->withProperties($return)
                ->log('Add new sample purchase return code ' . $return->code);

            return response()->json([
                'status'  => 200,
                'message' => 'Successfully saved'
            ]);
        } else {
            return response()->json([
                'status'  => 400,
                'message' => 'This PO did not have warehouse receive yet!'
            ]);
        }
        } else {
            return response()->json([
                'status'  => 503,
                'message' => 'You cannot add/edit. The journal for this month was already closed.'
            ]);
        }
    }

    public function createProforma(Request $request, $id)
    {

        $query = CustomerSample::find($id);

        $query->update([
            'progress' => $query->progress < 60 ? 60 : $query->progress
        ]);

        if ($request->temp_proforma) {
            $data = SampleProforma::find($request->temp_proforma);

            if ($request->has('file')) {
                if (Storage::exists($data->image)) {
                    Storage::delete($data->image);
                }

                $image = $request->file('file')->store('public/sample');
            } else {
                $image = $data->image;
            }

            $data->update([
                'customer_sample_id'     => $id,
                'sample_purchase_id'     => $request->sample_purchase_id,
                'image'                  => $image,
                'date'                   => $request->date,
                'supplier_name'          => $request->supplier_name,
                'supplier_warehouse'     => $request->supplier_warehouse,
                'note'                   => $request->note
            ]);
        } else {
            $data = SampleProforma::create([
                'customer_sample_id'     => $id,
                'sample_purchase_id'     => $request->sample_purchase_id,
                'image'                  => $request->file('file') ? $request->file('file')->store('public/sample') : '',
                'date'                   => $request->date,
                'supplier_name'          => $request->supplier_name,
                'supplier_warehouse'     => $request->supplier_warehouse,
                'note'                   => $request->note
            ]);
        }


        activity()
            ->performedOn(new SampleProforma())
            ->causedBy(session('bo_id'))
            ->withProperties($data)
            ->log('Add sample proforma');

        $response = [
            'status' => 200,
            'message' => 'Sample proforma successfully created'
        ];


        return response()->json($response);
    }

    public function createDeliveryShipment(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'sample_purchase_id' => 'required',
            'shipment_code'      => 'required',
            'loading_date'       => 'required',
            'departure_date'     => 'required',
            'from_port'          => 'required',
            'to_port'            => 'required',
            'eta'                => 'required',
            'product_id'         => 'required|array',
            'product_unit'       => 'required|array',
            'product_qty'        => 'required|array'
        ], [
            'sample_purchase_id.required'  => 'Purchase cannot empty.',
            'shipment_code.required'       => 'Shipment code cannot empty.',
            'loading_date.required'        => 'Loading date cannot empty.',
            'departure_date.required'      => 'Departure date cannot empty.',
            'from_port.required'           => 'from port cannot empty.',
            'to_port.required'             => 'to port cannot empty.',
            'eta.required'                 => 'ETA cannot empty.',
            'product_id.required'          => 'Product cannot empty.',
            'product_id.array'             => 'Product must be array.',
            'product_unit.required'        => 'Product unit cannot empty.',
            'product_unit.array'           => 'Product unit must be array.',
            'product_qty.required'         => 'Product qty cannot empty.',
            'product_qty.array'            => 'Product qty must be array.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status'   => 422,
                'error'  => $validation->errors()
            ];

            return response()->json($response);
        } else {

            $query = CustomerSample::find($id);

            $query->update([
                'progress' => $query->progress < 65 ? 65 : $query->progress
            ]);

            if ($request->temp_shipment_id) {

                if ($request->edit_reason_shipment) {

                    $sampleShipment = SampleShipment::find($request->temp_shipment_id);

                    if ($request->has('file')) {
                        if (Storage::exists($sampleShipment->image)) {
                            Storage::delete($sampleShipment->image);
                        }

                        $image = $request->file('file')->store('public/sample');
                    } else {
                        $image = $sampleShipment->image;
                    }

                    $sampleShipment->sample_purchase_id     = $request->sample_purchase_id;
                    $sampleShipment->shipment_code          = $request->shipment_code;
                    $sampleShipment->loading_date           = $request->loading_date;
                    $sampleShipment->departure_date         = $request->departure_date;
                    $sampleShipment->from_port              = $request->from_port;
                    $sampleShipment->to_port                = $request->to_port;
                    $sampleShipment->eta                    = $request->eta;
                    $sampleShipment->delivery_method        = $request->delivery_method;
                    $sampleShipment->note                   = $request->note;
                    $sampleShipment->image                   = $image;

                    $sampleShipment->update();

                    foreach ($sampleShipment->sampleShipmentProduct as $row) {
                        $row->delete();
                    }

                    #start notif
                    $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
                    $title = 'Sample has been edited!';
                    $description = 'Sample ' . $query->code . ' with details shipment code ' . $request->shipment_code . ' has been edited by ' . session('bo_name') . ' with reason : ' . $request->edit_reason_shipment;
                    $link = '#';
                    Notification::sendNotif($role, $title, $description, $link);
                    #end notif


                } else {
                    $response = [
                        'status'   => 422,
                        'error'  => "Please describe your reason"
                    ];
        
                    return response()->json($response);
                }
            } else {

                if ($request->has('file')) {
                    $image = $request->file('file')->store('public/sample');
                } else {
                    $image = '';
                }

                $sampleShipment = SampleShipment::create([
                    'customer_sample_id'     => $id,
                    'sample_purchase_id'     => $request->sample_purchase_id,
                    'shipment_code'          => $request->shipment_code,
                    'loading_date'           => $request->loading_date,
                    'departure_date'         => $request->departure_date,
                    'from_port'              => $request->from_port,
                    'to_port'                => $request->to_port,
                    'eta'                    => $request->eta,
                    'delivery_method'        => $request->delivery_method,
                    'note'                   => $request->note,
                    'image'                  => $image
                ]);

                #start notif
                $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
                $title = 'Sample has been updated!';
                $description = 'Sample ' . $query->code . ' with details shipment code ' . $request->shipment_code . ' in purchase code ' . $sampleShipment->samplePurchase->code . ' has been updated by ' . session('bo_name');
                $link = '#';
                Notification::sendNotif($role, $title, $description, $link);
                #end notif

            }

            foreach ($request->product_id as $key => $pi) {
                SampleShipmentProduct::create([
                    'sample_shipment_id'       => $sampleShipment->id,
                    'product_id'               => $request->product_id[$key],
                    'qty'                      => $request->product_qty[$key],
                    'unit'                     => $request->product_unit[$key]
                ]);
            }

            activity()
                ->performedOn(new CustomerSample())
                ->causedBy(session('bo_id'))
                ->withProperties($sampleShipment)
                ->log('Change data sample ' . $query->name . ' (Step 4)');

            $response = [
                'status' => 200,
                'message' => 'Success',
            ];

            return response()->json($response);
        }
    }

    public function createWarehouseReceived(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'sample_purchase_id'    => 'required',
            'shipment_id'           => 'required',
            'person'                => 'required',
            'date_receive'          => 'required',
            'warehouse_id'          => 'required',
            'product_id'            => 'required|array',
            'product_unit'          => 'required|array',
            'product_qty'           => 'required|array',
        ], [
            'sample_purchase_id.required'   => 'Purchase cannot empty.',
            'shipment_id.required'          => 'Shipment cannot empty.',
            'person.required'               => 'Person who is responsible cannot empty.',
            'date_receive.required'         => 'Date received cannot empty.',
            'warehouse_id.required'         => 'Warehouse cannot empty.',
            'product_id.required'           => 'Product cannot empty.',
            'product_id.array'              => 'Product must be array.',
            'product_unit.required'         => 'Product unit cannot empty.',
            'product_unit.array'            => 'Product unit must be array.',
            'product_qty.required'          => 'Product qty cannot empty.',
            'product_qty.array'             => 'Product qty must be array.',
        ]);
        if ($validation->fails()) {
             $response = [
                'status'   => 422,
                'error'  => $validation->errors()
            ];

            return response()->json($response);
        } else {
            $customersample = CustomerSample::find($id);

            $customersample->update([
                'progress' => $customersample->progress < 75 ? 75 : $customersample->progress
            ]);

            if ($request->temp_warehouse_id) {

                if ($request->edit_reason_warehouse) {

                    $samplewarehouse = SampleWarehouse::find($request->temp_warehouse_id);

                    if ($request->has('file')) {
                        if (Storage::exists($samplewarehouse->image)) {
                            Storage::delete($samplewarehouse->image);
                        }

                        $image = $request->file('file')->store('public/sample');
                    } else {
                        $image = $samplewarehouse->image;
                    }

                    $samplewarehouse->user_id                = session('bo_id');
                    $samplewarehouse->sample_purchase_id     = $request->sample_purchase_id;
                    $samplewarehouse->sample_shipment_id     = $request->shipment_id;
                    $samplewarehouse->person                 = $request->person;
                    $samplewarehouse->date_receive           = $request->date_receive;
                    $samplewarehouse->warehouse_id           = $request->warehouse_id;
                    // $samplewarehouse->include_cost           = $request->include_cost;
                    $samplewarehouse->image                  = $image;

                    $samplewarehouse->update();

                    foreach ($samplewarehouse->sampleWarehouseProduct as $row) {
                        #updatestock
                        $cek = StockSample::where('product_id', $row->product_id)->where('warehouse_id', $samplewarehouse->warehouse_id)->where('branch', $samplewarehouse->samplePurchase->sales->branch)->first();
                        if ($cek) {
                            $cek->update([
                                'qty'     => $cek->qty - $row->qty,
                                'unit'    => $row->unit
                            ]);
                        } else {
                            StockSample::create([
                                'product_id'    => $row->product_id,
                                'warehouse_id'  => $samplewarehouse->warehouse_id,
                                'qty'           => $row->qty,
                                'unit'          => $row->unit,
                                'branch'        => $samplewarehouse->samplePurchase->sales->branch
                            ]);
                        }

                        $row->delete();
                    }

                    $cb = CashBank::where('lookable_type', 'sample_warehouses')->where('lookable_id', $request->temp_warehouse_id)->get();

                    foreach ($cb as $row) {
                        $row->deleteDetail();
                        $row->delete();
                    }

                    // $prcek = PurchaseRequest::where('project_warehouse_id', $request->temp_warehouse_id)->get();

                    // foreach ($prcek as $rowcek) {
                    //     foreach ($rowcek->purchaseRequestPayment() as $rowpay) {
                    //         $rowpay->deleteFile();
                    //         $rowpay->delete();
                    //     }
                    //     $rowcek->deleteFile();
                    //     $rowcek->delete();
                    // }

                    #start notif
                    $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
                    $title = 'Sample has been updated!';
                    $description = 'Sample ' . $customersample->code . ' with details warehouse code ' . $samplewarehouse->code . ' has been edited by ' . session('bo_name') . '.';
                    $link = '#';
                    Notification::sendNotif($role, $title, $description, $link);
                    #end notif
                } else {
                    $response = [
                        'status'   => 422,
                        'error'  => "Please Describe your reason"
                    ];
        
                    return response()->json($response);
                }
            } else {

                $samplewarehouse = SampleWarehouse::create([
                    'user_id'                => session('bo_id'),
                    'customer_sample_id'     => $id,
                    'sample_purchase_id'     => $request->sample_purchase_id,
                    'sample_shipment_id'     => $request->shipment_id,
                    'code'                   => SampleWarehouse::generateCode(),
                    'image'                  => $request->file('file')->store('public/sample'),
                    'date_receive'           => $request->date_receive,
                    'warehouse_id'           => $request->warehouse_id,
                    'person'                 => $request->person,
                    // 'include_cost'           => $request->include_cost,
                ]);

                #start notif
                $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
                $title = 'Sample has been updated!';
                $description = 'Sample ' . $customersample->code . ' with details purchase code ' .  $samplewarehouse->samplePurchase->code . ' has been received by ' . $request->person . ' with warehouse receive code ' . $samplewarehouse->code . '.';
                $link = '#';
                Notification::sendNotif($role, $title, $description, $link);
                #end notif
            }

           

            $nominal = 0;
            foreach ($request->product_id as $key => $pi) {
                SampleWarehouseProduct::create([
                    'sample_warehouse_id'     => $samplewarehouse->id,
                    'product_id'              => $request->product_id[$key],
                    'qty'                     => $request->product_qty[$key],
                    'unit'                    => $request->product_unit[$key],
                    'qty_broken'              => $request->product_qty_broken[$key],
                    'unit_broken'             => $request->product_unit[$key],
                ]);


                $spp = SamplePurchaseProduct::where('sample_purchase_id', $request->sample_purchase_id)->where('product_id', $request->product_id[$key])->first();

                $nominal += $request->product_qty[$key] * $spp->price;



                #updateStockSample
                $cek = StockSample::where('product_id', $request->product_id[$key])->where('warehouse_id', $request->warehouse_id)->where('branch', $samplewarehouse->samplePurchase->sales->branch)->first();
                if ($cek) {
                    $cek->update([
                        'qty'     => $cek->qty + $request->product_qty[$key],
                        'unit'    => $request->product_unit[$key]
                    ]);
                } else {
                    StockSample::create([
                        'product_id'     => $request->product_id[$key],
                        'warehouse_id'   => $request->warehouse_id,
                        'qty'            => $request->product_qty[$key],
                        'unit'           => $request->product_unit[$key],
                        'branch'         => $samplewarehouse->samplePurchase->sales->branch
                    ]);
                }
            }

            SampleWarehouse::find($samplewarehouse->id)->updateGrandtotal();
            
            $samplepurchase = SamplePurchase::find($request->sample_purchase_id);

            $productdesc = '';

            foreach ($samplepurchase->sample->sampleProduct as $sp) {
                $productdesc .= '. Detail products : ' . $sp->product->name() . ', Qty : ' . $sp->qty . ' ' . $sp->unit() . ', Size :' . $sp->size() . ' <br>';
            }

            $debetcb = 339;
            $kreditcb = 332;
            $branch = $samplepurchase->sales->branch;

            $cb = CashBank::create([
                'user_id'             => session('bo_id'),
                'lookable_type'       => 'sample_warehouses',
                'lookable_id'         => $samplewarehouse->id,
                'code'                => strtoupper(Str::random(15)),
                'date'                => $request->date_receive,
                'type'                => '2',
                'description'         => 'Warehouse Receive Code '.$samplewarehouse->code. ' Sample Code ' . $samplepurchase->code . ' in code ' . $samplepurchase->customerSample->code . ' with Customer ' . $samplepurchase->customerSample->customer->name . ' ' . $productdesc . ''
            ]);

            if ($cb) {
                CashBankDetail::create([
                    'cash_bank_id'     => $cb->id,
                    'coa_id'           => $debetcb,
                    'branch'           => $branch,
                    'type'             => '1',
                    'nominal'          => $nominal,
                    'note'             => ''
                ]);

                Journal::insert([
                    'date_transaction' => $request->date_receive,
                    'journalable_type' => 'cash_banks',
                    'journalable_id'   => $cb->id,
                    'coa_id'           => $debetcb,
                    'branch'           => $branch,
                    'type'             => '1',
                    'nominal'          => $nominal,
                    'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s')
                ]);

                CashBankDetail::create([
                    'cash_bank_id'     => $cb->id,
                    'coa_id'           => $kreditcb,
                    'branch'           => $branch,
                    'type'             => '2',
                    'nominal'          => $nominal,
                    'note'             => ''
                ]);

                Journal::insert([
                    'date_transaction' => $request->date_receive,
                    'journalable_type' => 'cash_banks',
                    'journalable_id'   => $cb->id,
                    'coa_id'           => $kreditcb,
                    'branch'           => $branch,
                    'type'             => '2',
                    'nominal'          => $nominal,
                    'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s')
                ]);
            }

            $response = [
                'status'  => 200,
                'message' => 'Successfully Added'
            ];

            return response()->json($response);
        }
    }

    public function deleteDeliveryTracking(Request $request)
    {
        $trackingdelivery = SampleDeliveryTrack::find($request->id);

        if (Storage::exists($trackingdelivery->image)) {
            Storage::delete($trackingdelivery->image);
        }

        $trackingdelivery->delete();

        activity()
            ->performedOn(new SampleDeliveryTrack())
            ->causedBy(session('bo_id'))
            ->withProperties($trackingdelivery)
            ->log('Delete tracking to delivery sample');

        return response()->json([
            'status'  => 200,
            'message' => 'Data added successfully.'
        ]);
    }

    public function approval(Request $request)
    {
        $mode =  $request->mode;
        if ($mode == "sample") {
            $sample_id = $request->id;
            $approvalke = $request->approvalKe;

            $sample = Sample::find($sample_id);
            if ($approvalke == 1) {
                $sample->approved_by_1 = session('bo_id');
            } else if ($approvalke == 2) {
                $sample->approved_by_2 = session('bo_id');
            }
            $sample->save();

            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Sample has been approved';
            $description = 'Sample ' . $sample->customerSample->code . ' products has been approved by ' . session('bo_name');
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif

            activity()
                ->performedOn(new Sample())
                ->causedBy(session('bo_id'))
                ->withProperties($sample)
                ->log('Add approval to sample');

            $response = [
                'status'  => 200,
                'message' => 'Data added successfully.'
            ];
        } else if ($mode == 'purchase') {

            $purchase_id = $request->id;
            $approvalke = $request->approvalKe;

            $sample = SamplePurchase::find($purchase_id);
            if ($approvalke == 1) {
                $sample->checked_by = session('bo_id');
            } else if ($approvalke == 2) {
                $sample->approved_by = session('bo_id');
            }
            $sample->save();

            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Sample PO has been approved';
            $description = 'Sample PO ' . $sample->code . ' products has been approved by ' . session('bo_name');
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif

            activity()
                ->performedOn(new SamplePurchase())
                ->causedBy(session('bo_id'))
                ->withProperties($sample)
                ->log('Add approval to sample PO');

            $response = [
                'status'  => 200,
                'message' => 'Data added successfully.'
            ];
        } else if ($mode == 'delivery') {
            $delivery_id = $request->id;
            $approvalke = $request->approvalKe;

            $sample = SampleDelivery::find($delivery_id);
            if ($approvalke == 1) {
                $sample->approved_by = session('bo_id');
            } else if ($approvalke == 2) {
                $sample->acknowledged_by = session('bo_id');
            }
            $sample->save();

            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Sample DO has been approved';
            $description = 'Sample DO ' . $sample->code . ' products has been approved by ' . session('bo_name');
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif

            activity()
                ->performedOn(new SamplePurchase())
                ->causedBy(session('bo_id'))
                ->withProperties($sample)
                ->log('Add approval to sample DO');

            $response = [
                'status'  => 200,
                'message' => 'Data added successfully.'
            ];
        } else if ($mode == 'return') {
            $return_id = $request->id;
            $approvalke = $request->approvalKe;

            $sample = SampleReturn::find($return_id);

            $sample->approved_by = session('bo_id');

            $sample->save();

            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Sample SR has been approved';
            $description = 'Sample SR ' . $sample->code . ' products has been approved by ' . session('bo_name');
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif

            activity()
                ->performedOn(new SampleReturn())
                ->causedBy(session('bo_id'))
                ->withProperties($sample)
                ->log('Add approval to sample SR');

            $response = [
                'status'  => 200,
                'message' => 'Data added successfully.'
            ];
        }else if ($mode == 'purchase_return') {
            $return_id = $request->id;
            $approvalke = $request->approvalKe;

            $sample = SamplePurchaseReturn::find($return_id);

            $sample->approved_by = session('bo_id');

            $sample->save();

            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Sample SR has been approved';
            $description = 'Sample PR ' . $sample->code . ' products has been approved by ' . session('bo_name');
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif

            activity()
                ->performedOn(new SampleReturn())
                ->causedBy(session('bo_id'))
                ->withProperties($sample)
                ->log('Add approval to sample PR');

            $response = [
                'status'  => 200,
                'message' => 'Data added successfully.'
            ];
        }


        return response()->json($response);
    }

    public function print(Request $request, $param, $id)
    {
        set_time_limit(300);
        if ($param == 'sample') {
            $sample_id = base64_decode($id);
            $sample    = Sample::find($sample_id);

            if (!$sample) {
                abort(404);
            }

            $pdf = PDF::loadView(
                'admin.pdf.sample.' . $param,
                [
                    'sample' => $sample
                ],
                [],
                [
                    'format' => 'A4-P',
                    'orientation' => 'P'
                ]
            );
        }else if($param == 'pick_up_memo'){
			$sample_id = base64_decode($id);
			$sample    = SamplePurchase::find($sample_id);

			if(!$sample) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'pick_up_memo_delivery'){
			$sample_id = base64_decode($id);
			$sample    = SampleDelivery::find($sample_id);

			if(!$sample) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'return_memo'){
			$sample_id = base64_decode($id);
			$sample    = SampleReturnMemo::find($sample_id);

			if(!$sample) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'warehouse_receive'){
			$warehouse_id = base64_decode($id);
			$sample = SampleWarehouse::find($warehouse_id);
			
			if(!$warehouse_id) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
			
		}else if($param == 'purchase_order'){
			$sample_id = base64_decode($id);
			$sample    = SamplePurchase::find($sample_id);

			if(!$sample) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'delivery_order'){
			$do_id = base64_decode($id);
			$sample = SampleDelivery::find($do_id);
			
			if(!$sample) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		
		}else if($param == 'sales_proforma'){
			$do_id = base64_decode($id);
			$sample = SampleDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'sales_proforma_other'){
			$do_id = base64_decode($id);
			$sample = SampleDelivery::find($do_id);
			
			if(!$do_id) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'sales_return'){
			$sample_id = base64_decode($id);
			$sample    = SampleReturn::find($sample_id);

			if(!$sample) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}else if($param == 'purchase_return'){
			$sample_id = base64_decode($id);
			$sample    = SamplePurchaseReturn::find($sample_id);

			if(!$sample) {
				abort(404);
			}
			
			$pdf = PDF::loadView('admin.pdf.sample.' . $param, [
					'sample' => $sample
				],
				[],
				[ 
				  'format' => 'A4-P',
				  'orientation' => 'P'
				]
			);
		}

        return $pdf->stream('tjs.pdf');
    }

    public function destroy(Request $request)
    {

        $customer_sample = CustomerSample::find($request->id);
        $reason = $request->reason;

        if($customer_sample->sampleDelivery->first()->received_date){
			return response()->json([
				'status'  => 400,
				'message' => 'Sorry, This delivery already received by customer.'
			]);
		}else{
            foreach ($customer_sample->sample as $s) {
                Approval::where('approvalable_type', 'samples')->where('approvalable_id', $s->id)->delete();
                SampleDeliveryTrack::where('sample_id', $s->id)->delete();
    
                foreach ($s->sampleProduct as $sp) {
                    $sp->delete();
                }
                foreach ($s->sampleProductShading as $sps) {
                    $sps->delete();
                }
    
                $s->delete();
            }
    
    
            foreach ($customer_sample->samplePurchase as $pp) {
                Approval::where('approvalable_type', 'sample_purchases')->where('approvalable_id', $pp->id)->delete();
                SamplePurchaseProduct::where('sample_purchase_id', $pp->id)->delete();
    
                foreach ($pp->samplePurchaseReturn as $purchasereturn) {
                    $cb = CashBank::where('lookable_type', 'sample_purchase_returns')->where('lookable_id', $purchasereturn->id)->get();
                    foreach ($cb as $c) {
                        $c->deleteDetail();
                        $c->delete();
                    }
                    $purchasereturn->samplePurchaseReturnProduct()->delete();
                    $purchasereturn->deleteFile();
                    $purchasereturn->delete();
                }
    
                foreach ($pp->sampleProforma as $purchaseproforma) {
                    $purchaseproforma->deleteFile();
                    $purchaseproforma->delete();
                }
    
                foreach ($pp->sampleShipment as $shipment) {
                    $shipment->sampleShipmentProduct()->delete();
                    $shipment->sampleShipmentTrack()->delete();
                    $shipment->deleteFile();
                    $shipment->delete();
                }
    
                foreach ($pp->sampleWarehouse as $warehouse) {
                    $cb = CashBank::where('lookable_type', 'sample_warehouses')->where('lookable_id', $warehouse->id)->get();
                    foreach ($cb as $c) {
                        $c->deleteDetail();
                        $c->delete();
                    }
                    $warehouse->deleteFile();
                    $warehouse->sampleWarehouseProduct()->delete();
                    $warehouse->delete();
                }
            }
    
    
            foreach ($customer_sample->sampleDelivery as $pd) {
                Approval::where('approvalable_type', 'sample_deliveries')->where('approvalable_id', $pd->id)->delete();
                $cb = CashBank::where('lookable_type','sample_deliveries')->where('lookable_id',$pd->id)->get();
            
                foreach($cb as $c){
                    $c->deleteDetail();
                    $c->delete();
                }
                
                $pd->deleteFile();
                $pd->sampleDeliveryProduct()->delete();
                $pd->sampleDeliveryTrack()->delete();
            }
            
    
    
            $customer_sample->delete();
    
            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Sample ' . $customer_sample->code . ' has been canceled!';
            $description = 'Sample ' . $customer_sample->code . ' has been canceled by ' . session("bo_name") . ' with reason : ' . $reason;
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif
    
            activity()
                ->performedOn(new CustomerSample())
                ->causedBy(session('bo_id'))
                ->withProperties($customer_sample)
                ->log('Delete sample code ' . $customer_sample->code);
    
            return response()->json([
                'status'  => 200,
                'message' => 'Data added successfully.'
            ]);
        }
    }


    public function getSampleProduct(Request $request)
    {
        $sample = Sample::find($request->id_sample);
        $sampleProduct = SampleProduct::where('sample_id', $sample->id)->get();
        $data = [];

        $shading = '';

        foreach ($sampleProduct as $sp) {
            $m2 = (($sp->product->type->length * $sp->product->type->width) / 10000) * $sp->product->carton_pcs;

            $unitother = 1;

            if ($m2 < 1.1 && $sp->product->type->category->parent()->id !== 18) {
                $countbox = ceil($sp->qty);
                $unitother = ceil($m2);
            } else {
                if ($m2 < 1.1 && date('Y-m', strtotime($sample->customerSample->created_at)) < '2022-06' && $sp->product->type->category->parent()->id == 18) {
                    if ($sp->unit == '2' || $sp->unit == '3') {
                        $countbox = ceil($sp->qty);
                        $unitother = ceil($m2);
                    }
                } else {
                    if ($sp->unit == '2' || $sp->unit == '3') {
                        $countbox = ceil(round($sp->qty / $m2, 2));
                        $unitother = $m2;
                    }
                }
            }
            $samplePurchase = SamplePurchase::where('sample_id', $sample->id)->get();

            $tot = 0;
            foreach ($samplePurchase as $sph) {
                foreach ($sph->samplePurchaseProduct as $spp) {
                    if ($spp->product_id == $sp->product_id) {
                        $tot += $spp->qty;
                    }
                }
            }

            foreach ($sp->sample->sampleProductShading()->where('product_id', $sp->product_id)->get() as $sps) {
                $shading = 'Code : ' . $sps->code . ' Qty : ' . round($sps->qty, 0) . ', ';
            }

            $stock = 'None';

            if (StockSample::where('product_id', $sp->product_id)->where('branch', $sp->sample->sales->branch)->count() > 0) {
                $stock = '';
            }

            $totalstok = 0;

            foreach (StockSample::where('product_id', $sp->product_id)->where('branch', $sp->sample->sales->branch)->get() as $rowstock) {
                $stock .= 'Warehouse ' . $rowstock->warehouse->name . ' (' . $rowstock->warehouse->code . ')' . ' qty : ' . $rowstock->qty . ', ';
                $totalstok += $rowstock->qty;
            }

            $sampleDelivery = SampleDelivery::where('sample_id', $request->id_sample)->get();

            $totdelivery = 0;
            foreach ($sampleDelivery as $pp) {
                foreach ($pp->sampleDeliveryProduct as $ppd) {
                    if ($ppd->product_id == $sp->product_id) {
                        $totdelivery += $ppd->qty;
                    }
                }
            }

            if ($sp->unit == '3' || $sp->unit == '2') {
                $data[] = [
                    'product_id'           => $sp->product_id,
                    'product_name'         => $sp->product->name(),
                    'qty'                  => $countbox,
                    'qty_left'             => $countbox -  $tot,
                    'unit'                 => $sp->unit(),
                    'unitraw'              => $sp->unit,
                    'unitconvert'          => $sp->unit,
                    'unitother'            => $unitother,
                    'price'                => 1,
                    'shading'              => $shading,
                    'nominalstock'         => $totalstok,
                    'qty_left_deliver'     => $countbox - $totdelivery,
                    'stock'                => $stock,

                ];
            } else {
                $data[] = [
                    'product_id'          => $sp->product_id,
                    'product_name'        => $sp->product->name(),
                    'qty'                 => $sp->qty,
                    'qty_left'            => $sp->qty -  $tot,
                    'unit'                => $sp->unit(),
                    'unitraw'             => $sp->unit,
                    'unitconvert'         => $sp->unit,
                    'unitother'           => $unitother,
                    'price'               => 1,
                    'shading'             => $shading,
                    'nominalstock'        => $totalstok,
                    'qty_left_deliver'    => $sp->qty - $totdelivery,
                    'stock'               => $stock,
                ];
            }
        }

        return response()->json($data);
    }

    public function getSalesInfo(Request $request)
    {
        $sample = Sample::find($request->id_sample);
        $sales_id = $sample->customerSample->user_id;
        $customer_id = $sample->customerSample->customer_id;
        $warehouse = [];

        if (count($sample->customerSample->sampleWarehouse) > 0) {
            $warehouse['id'] = $sample->customerSample->sampleWarehouse()->first()->warehouse->id;
            $warehouse['name'] = $sample->customerSample->sampleWarehouse()->first()->warehouse->name;
        }

        return response()->json([
            'sales_id'       => $sales_id,
            'customer_id'    => $customer_id,
            'customer_name'  => $sample->customerSample->customer->name,
            'sales_name'     => $sample->customerSample->user->name,
            'sales_note'     => $sample->note,
            'customer_phone' => $sample->customerSample->customer->phone,
            'warehouse'      => $warehouse

        ]);
    }

    public function getSupplierCurrency(Request $request)
    {
        $supplier = Supplier::find($request->idsupp);

        $data = [];

        foreach ($supplier->supplierCurrency as $sc) {
            $data[] = [
                'id'        => $sc->currency->id,
                'code'      => $sc->currency->code,
                'name'      => $sc->currency->name,
                'symbol'    => $sc->currency->symbol,
                'up'        => 'Mr/Mrs. ' . $supplier->pic . ' Phone. ' . $supplier->phone
            ];
        }

        return response()->json($data);
    }

    public function getPurchaseInfo(Request $request)
    {
        $samplePurchaseProduct = SamplePurchaseProduct::where('sample_purchase_id', $request->purchaseid)->get();
        $samplePurchase = SamplePurchase::find($request->purchaseid);


        $total = 0;
        $totalpaid = 0;
        $datapayment = [];


        foreach ($samplePurchaseProduct as $spp) {
            $m2 = (($spp->product->type->length * $spp->product->type->width) / 10000) * $spp->product->carton_pcs;

            if ($spp->unit == '3' || $spp->unit == '2') {
                if ($m2 < 1.1 && $spp->product->type->category->parent()->id !== 18) {
                    $total += $spp->price * $spp->qty;
                } else {
                    if ($m2 < 1.1 && date('Y-m', strtotime($samplePurchaseProduct->created_at)) < '2022-06' && $spp->product->type->category->parent()->id == 18) {
                        $total += $spp->price * $spp->qty;
                    } else {
                        $total += $spp->price * $spp->qty * $m2;
                    }
                }
            } else {
                $total += $spp->price * $spp->qty;
            }
        }

        // foreach ($samplePurchase->samplePurchasePayment as $ppp) {
        //     $totalpaid += $ppp->nominal;
        //     $datapayment[] = [
        //         'date'        => $ppp->date,
        //         'bank'        => $ppp->coa->name,
        //         'nominal'     => $ppp->samplePurchase->currency->symbol . ' ' . number_format($ppp->nominal, 2, ',', '.'),
        //         'status'      => $ppp->status()
        //     ];
        // }

        $samplePurchase['supplier_name'] = $samplePurchase->supplier->name;
        $samplePurchase['country_name'] = $samplePurchase->country->name;
        $samplePurchase['city_name'] = $samplePurchase->city->name;
        $samplePurchase['currency_name'] = $samplePurchase->currency->name;
        $samplePurchase['sales_name'] = $samplePurchase->sales->name;

        $purchaseproduct = [];

        foreach ($samplePurchase->samplePurchaseProduct as $ppp) {
            if ($ppp->unit == '3' || $ppp->unit == '2') {
                $purchaseproduct[] = [
                    'product_id'         => $ppp->product_id,
                    'product_name'       => $ppp->product->name(),
                    'product_code'       => $ppp->product->type->code,
                    'qty'                => round($ppp->qty, 0),
                    'qty_left'           => round($ppp->qty, 0),
                    'unit'               => 'Box',
                    'unitraw'            => $ppp->unit,
                    'remark'             => $ppp->remark,
                    'price'              => number_format($ppp->price, 2, ',', '.'),
                    'm2'                 => (($ppp->product->type->length * $ppp->product->type->width) / 10000) * $ppp->product->carton_pcs
                ];
            } else {
                $purchaseproduct[] = [
                    'product_id'        => $ppp->product_id,
                    'product_name'      => $ppp->product->name(),
                    'product_code'      => $ppp->product->type->code,
                    'qty'               => $ppp->qty,
                    'qty_left'          => $ppp->qty,
                    'unit'              => $ppp->unit(),
                    'unitraw'           => $ppp->unit,
                    'remark'            => $ppp->remark,
                    'price'             => number_format($ppp->price, 2, ',', '.'),
                    'm2'                => 1,
                ];
            }
        }

        if ($samplePurchase->sample) {
            return response()->json([
                'purchase'        => $samplePurchase,
                'purchaseproduct' => $purchaseproduct,
                // 'datapayment'     => $datapayment,
                'supplier_name'   => $samplePurchase->supplier->name,
                'total'           => $samplePurchase->currency->symbol . ' ' . number_format($total, 2, ',', '.'),
                'totalraw'        => $total,
                'totalpaid'       => $samplePurchase->currency->symbol . ' ' . number_format($totalpaid, 2, ',', '.'),
                'totalleft'       => number_format($total - $totalpaid, 2, ',', '.'),
                'status'          => '422',
                'message'         => 'Sorry, this purchase was created under sample. You must edit on sample page.'
            ]);
        } else {
            return response()->json([
                'purchase'        => $samplePurchase,
                'purchaseproduct' => $purchaseproduct,
                // 'datapayment'     => $datapayment,
                'supplier_name'   => $samplePurchase->supplier->name,
                'total'           => $samplePurchase->currency->symbol . ' ' . number_format($total, 2, ',', '.'),
                'totalraw'        => $total,
                'totalpaid'       => $samplePurchase->currency->symbol . ' ' . number_format($totalpaid, 2, ',', '.'),
                'totalleft'       => number_format($total - $totalpaid, 2, ',', '.'),
            ]);
        }
    }

    public function getPurchaseProduct(Request $request)
    {
        $samplePurchase = SamplePurchaseProduct::where('sample_purchase_id', $request->idpo)->get();

        $data = [];

        foreach ($samplePurchase as $sp) {
            $m2 = (($sp->product->type->length * $sp->product->type->width) / 10000) * $sp->product->carton_pcs;

            $sampleShipment = SampleShipment::where('sample_purchase_id', $request->idpo)->get();

            $tot = 0;
            foreach ($sampleShipment as $ss) {
                foreach ($ss->sampleShipmentProduct as $ssp) {
                    if ($ssp->product_id == $sp->product_id) {
                        $tot += $ssp->qty;
                    }
                }
            }

            $data[] = [
                'product_id'     => $sp->product_id,
                'product_name'   => $sp->product->name(),
                'qty'            => $sp->qty,
                'unit'           => $sp->unit == 3 ? 'Box' : $sp->unit(),
                'convertunit'    => $sp->unit == 3 ? 2 : $sp->unit,
                'qty_left'       => $sp->qty -  $tot,
                'qty_sent'       =>  $tot,
                'm2'             => $sp->m2,
                'fixunit'        => $m2 < 1.1 && $sp->product->type->category->parent()->id !== 18 ? round($sp->qty * $m2, 2) : $sp->qty
            ];
        }

        return response()->json($data);
    }

    public function getPurchaseProforma(Request $request)
    {
        $data = SampleProforma::find($request->id);

        return response()->json($data);
    }

    public function getShipmentEdit(Request $request)
    {
        $sampleshipment = SampleShipment::find($request->id);

        $detail = [];

        foreach ($sampleshipment->sampleShipmentProduct as $row) {
            $detail[] = [
                'product_id'     => $row->product_id,
                'unit'           => $row->unit(),
                'unitraw'        => $row->unit,
                'qty'            => $row->qty,
                'product'        => $row->product->name()
            ];
        }

        return response()->json([
            'main'      => $sampleshipment,
            'detail'    => $detail
        ]);
    }

    public function getShipmentInfo(Request $request)
    {
        $sampleshipment = SampleShipment::where('sample_purchase_id', $request->idpo)->get();


        $listshipment = [];

        foreach ($sampleshipment as $sp) {
            $listshipment[] = [
                'shipment_id'     => $sp->id,
                'shipment_code'   => $sp->shipment_code
            ];
        }

        return response()->json([
            'shipment_list'        => $listshipment,
        ]);
    }

    public function getShipmentProduct(Request $request)
    {
        $sampleshipmentproduct = SampleShipmentProduct::where('sample_shipment_id', $request->idshipment)->get();

        $listproduct = [];

        foreach ($sampleshipmentproduct as $ssp) {
            $listproduct[] = [
                'product_id'    => $ssp->product_id,
                'product_name'  => $ssp->product->name(),
                'qty'           => $ssp->qty,
                'unitraw'       => $ssp->unit,
                'unit'          => $ssp->unit()
            ];
        }

        return response()->json([
            'shipment_product'    => $listproduct
        ]);
    }

    public function getWarehouseEdit(Request $request)
    {
        $sampleWarehouse = SampleWarehouse::find($request->id);

        $main = [
            'sample_purchase_id'     => $sampleWarehouse->sample_purchase_id,
            'shipment_id'            => $sampleWarehouse->sample_shipment_id,
            'shipment_code'          => $sampleWarehouse->sampleShipment->shipment_code,
            'code'                   => $sampleWarehouse->code,
            'person'                 => $sampleWarehouse->person,
            'date_receive'           => str_replace(' ', 'T', $sampleWarehouse->date_receive),
            'warehouse_id'           => $sampleWarehouse->warehouse_id,
            'warehouse_name'         => $sampleWarehouse->warehouse->code . ' - ' . $sampleWarehouse->warehouse->name
        ];

        $detail = [];

        foreach ($sampleWarehouse->sampleWarehouseProduct as $row) {
            $detail[] = [
                'product_id'         => $row->product_id,
                'unit'               => $row->unit(),
                'unitraw'            => $row->unit,
                'qty'                => $row->qty,
                'qty_broken'         => $row->qty_broken,
                'unit_broken'        => $row->unit_broken(),
                'unit_broken_raw'    => $row->unit_broken,
                'product_name'       => $row->product->name()
            ];
        }

        return response()->json([
            'main'      => $main,
            'detail'    => $detail
        ]);
    }

    public function createSampleDelivery(Request $request, $id)
    {
        $sample = Sample::find($request->sample_id);

        $query = CustomerSample::find($id);

        $query->update([
            'progress' => $query->progress < 90 ? 90 : $query->progress
        ]);
        if ($request->temp_delivery_id) {
            if($request->edit_reason_delivery){

                $sampledelivery =  SampleDelivery::find($request->temp_delivery_id);

                if ($sampledelivery->received_date) {
                    $response = [
                        'status'   => 422,
                        'message'  => "Sample already Received"
                    ];

                    return response()->json($response);
                }

                if ($request->has('file')) {
                    if ($sampledelivery->image) {
                        if (Storage::exists($sampledelivery->image)) {
                            Storage::delete($sampledelivery->image);
                        }

                        $image = $request->file('file')->store('public/sample');
                    } else {
                        $image = '';
                    }
                } else {
                    $image = $sampledelivery->image;
                }
          
               SampleDelivery::find($request->temp_delivery_id)->update([
                    'user_id'               => session('bo_id'),
                    'customer_sample_id'    => $id,
                    'sample_id'             => $request->sample_id,
                    'city_id'               => $request->city_id2,
                    'receiver_name'         => $request->receiver_name,
                    'delivery_date'         => $request->delivery_date,
                    'email'                 => $request->email,
                    'phone'                 => $request->phone,
                    'is_dropshipper'        => $request->dropshipper,
                    'dropshipper_id'        => $request->dropshipper_id ? $request->dropshipper_id : 0,
                    'address'               => $request->address,
                    'warehouse_id'          => $request->warehousedeliver_id,
                    'vendor_id'             => $request->expedition_id,
                    'image'                 => $image,
                    'is_sales'              => '2',
                    'pick_up_name'          => $request->pick_up_name,
                    'pick_up_plat'          => $request->pick_up_plat,
                    'pick_up_vehicle'       => $request->pick_up_vehicle,
                    'service_note'          => $request->service_note,
                    'note'                  => $request->note,
                ]);

                SampleDeliveryProduct::where('sample_delivery_id', $request->temp_delivery_id)->delete();
            }else{
                $response = [
                    'status'   => 422,
                    'message'  => "Please Describe your reason"
                ];

                return response()->json($response);
            }
        } else {

            $stokkurang = false;

            foreach ($request->product_id as $key => $pi) {

                $cek = StockSample::where('product_id', $request->product_id[$key])->where('warehouse_id', $request->warehousedeliver_id)->where('branch', $sample->sales->branch)->get();

                if (count($cek) > 0) {
                    foreach ($cek as $row) {
                        if ($row->qty < $request->product_qty[$key]) {
                            $stokkurang = true;
                        }
                    }
                } else {
                    $stokkurang = true;
                }
            }

            foreach ($request->product_id as $key => $pi) {
                if ($request->product_stock[$key] < $request->product_qty[$key]) {
                    $stokkurang = true;
                }
            }

            if ($stokkurang == true) {
                $response = [
                    'status'   => 422,
                    'message'  => "Your items are not same with choosen product and warehouse origin. Please check again."
                ];
            }

            $sampledelivery = SampleDelivery::create([
                'user_id'               => session('bo_id'),
                'customer_sample_id'    => $id,
                'sample_id'             => $request->sample_id,
                'code'                  => SampleDelivery::generateCode(),
                'city_id'               => $request->city_id2,
                'receiver_name'         => $request->receiver_name,
                'delivery_date'         => $request->delivery_date,
                'email'                 => $request->email,
                'phone'                 => $request->phone,
                'is_dropshipper'        => $request->dropshipper,
                'dropshipper_id'        => $request->dropshipper_id ? $request->dropshipper_id : 0,
                'address'               => $request->address,
                'warehouse_id'          => $request->warehousedeliver_id,
                'vendor_id'             => $request->expedition_id,
                'approved_by'           => 0,
                'acknowledged_by'       => 0,
                'image'                 => $request->file('file') ? $request->file('file')->store('public/sample') : '',
                'proforma_code'         => SampleDelivery::generateCodeProforma(),
                'is_sales'              => '2',
                'pick_up_name'          => $request->pick_up_name,
                'pick_up_plat'          => $request->pick_up_plat,
                'pick_up_vehicle'       => $request->pick_up_vehicle,
                'service_note'          => $request->service_note,
                'note'                  => $request->note,
            ]);
        }

        if($sampledelivery){
            foreach ($request->product_id as $key => $pi) {
                SampleDeliveryProduct::create([
                    'sample_delivery_id'    => $sampledelivery->id,
                    'product_id'            => $request->product_id[$key],
                    'qty'                   => $request->product_qty[$key],
                    'unit'                  => $request->product_unit[$key],
                    'shading'               => $request->product_shading[$key]
                ]);
            }
    
            SampleDelivery::find($sampledelivery->id)->updateGrandtotal();
    
            #start notif
            $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
            $title = 'Sample has been updated!';
            $description = 'Sample ' . $sample->code . ' with details sales code ' . $sampledelivery->sample->code . ' has been sent to ' . $request->receiver_name . ' with delivery Sample code ' . $sampledelivery->code . '.';
            $link = '#';
            Notification::sendNotif($role, $title, $description, $link);
            #end notif
    
            #send approval
            $roleapproval = array('5');
            Approval::sendApproval($roleapproval, 'sample_deliveries', $sampledelivery->id, 'acknowledged_by', session('bo_id'));
            $roleapproval = array('4');
            Approval::sendApproval($roleapproval, 'sample_deliveries', $sampledelivery->id, 'approved_by', session('bo_id'));
            #end approval
    
            activity()
                ->performedOn(new CustomerSample())
                ->causedBy(session('bo_id'))
                ->log('Change data sample ' . $sample->name . ' (Step 6)');
    
            $response = [
                'status'  => 200,
                'message' => 'Successfully created',
            ];
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Failed to create',
            ];
        }
      

        return response()->json($response);
    }

    public function createSampleReturn(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'sample_id'             => 'required',
            'warehousereturn_id'    => 'required',
            'return_memo'           => 'required',
            'address'               => 'required',
            'note'                  => 'required',
            'file'                  => 'required|mimes:jpg,jpeg,png,pdf',
            'product_id'            => 'required|array',
            'product_unit'          => 'required|array',
            'product_qty'           => 'required|array',
        ], [
            'sample_id.required'             => 'Sales cannot empty.',
            'warehousereturn_id.required'    => 'Warehouse cannot empty.',
            'note.required'                  => 'Note cannot empty.',
            'return_memo.required'           => 'Return memo cannot empty.',
            'address.required'               => 'Address cannot empty.',
            'file.required'                  => 'File cannot empty.',
            'file.mimes'                     => 'File must have an extension jpg, jpeg, png, and pdf.',
            'product_id.required'            => 'Product cannot empty.',
            'product_id.array'               => 'Product must be array.',
            'product_unit.required'          => 'Product unit cannot empty.',
            'product_unit.array'             => 'Product unit must be array.',
            'product_qty.required'           => 'Product qty cannot empty.',
            'product_qty.array'              => 'Product qty must be array.',
        ]);
        if ($validation->fails()) {
            $response = [
                'status'   => 422,
                'error'  => $validation->errors()
            ];

            return response()->json($response);
        } else {

            $query = CustomerSample::find($id);

            $query->update([
                'progress' => $query->progress < 95 ? 95 : $query->progress
            ]);

            $samplereturn = SampleReturn::create([
                'user_id'                => session('bo_id'),
                'customer_sample_id'     => $id,
                'sample_id'              => $request->sample_id,
                'sample_return_memo_id'  => $request->return_memo,
                'date_return'            => $request->sale_return_date,
                'warehouse_id'           => $request->warehousereturn_id,
                'address'                => $request->address,
                'code'                   => SampleReturn::generateCode(),
                'image'                  => $request->file('file')->store('public/sample'),
                'note'                   => $request->note,
                'type'                   => $request->return_type,
                'approved_by'            => 0
            ]);

            foreach ($request->product_id as $key => $pi) {
                SampleReturnProduct::create([
                    'sample_return_id'   => $samplereturn->id,
                    'product_id'         => $request->product_id[$key],
                    'qty'                => $request->product_qty[$key],
                    'unit'               => $request->product_unit[$key]
                ]);

                #updatestock
                $cek = StockSample::where('product_id', $request->product_id[$key])->where('warehouse_id', $request->warehousereturn_id)->where('branch', $samplereturn->sample->sales->branch)->first();
                if ($cek) {
                    $cek->update([
                        'qty'     => $cek->qty + $request->product_qty[$key],
                        'unit'    => $request->product_unit[$key]
                    ]);
                } else {
                    StockSample::create([
                        'product_id'    => $request->product_id[$key],
                        'warehouse_id'  => $request->warehousereturn_id,
                        'qty'           => $request->product_qty[$key],
                        'unit'          => $request->product_unit[$key],
                        'branch'        => $samplereturn->customerSample->user->branch
                    ]);
                }
            }
        }

        $arr = $samplereturn->getTotal();

        $debetnominal1 = round($arr['totalpurchase']);
        $kreditnominal1 = round($arr['totalpurchase']);

        $debet1 = 339;
        $kredit1 = 341;

        #START

        $cb = CashBank::create([
            'user_id'             => session('bo_id'),
            'lookable_type'       => 'sample_returns',
            'lookable_id'         => $samplereturn->id,
            'code'                => strtoupper(Str::random(15)),
            'date'                => $request->sale_return_date,
            'type'                => '3',
            'description'         => 'Sample Return code ' . $samplereturn->code
        ]);

        if ($cb) {
            CashBankDetail::create([
                'cash_bank_id'     => $cb->id,
                'coa_id'           => $debet1,
                'branch'           => $samplereturn->sample->sales->branch,
                'type'             => '1',
                'nominal'          => round($debetnominal1, 0),
                'note'             => ''
            ]);

            Journal::insert([
                'date_transaction' => $request->sale_return_date,
                'journalable_type' => 'cash_banks',
                'journalable_id'   => $cb->id,
                'coa_id'           => $debet1,
                'branch'           => $samplereturn->sample->sales->branch,
                'type'             => '1',
                'nominal'          => round($debetnominal1, 0),
                'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s')
            ]);

            CashBankDetail::create([
                'cash_bank_id'     => $cb->id,
                'coa_id'           => $kredit1,
                'branch'           => $samplereturn->sample->sales->branch,
                'type'             => '2',
                'nominal'          => round($kreditnominal1, 0),
                'note'             => ''
            ]);

            Journal::insert([
                'date_transaction' => $request->sale_return_date,
                'journalable_type' => 'cash_banks',
                'journalable_id'   => $cb->id,
                'coa_id'           => $kredit1,
                'branch'           => $samplereturn->sample->sales->branch,
                'type'             => '2',
                'nominal'          => round($kreditnominal1, 0),
                'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s')
            ]);
        }

        $response = [
            'status'  => 200,
            'message' => "Successfully added"
        ];


        return response()->json($response);
    }

    public function getDeliveryProduct(Request $request)
    {
        $delivery      = SampleDeliveryProduct::where('sample_delivery_id', $request->id)->get();

        $result = [];

        foreach ($delivery as $d) {
            $result[] = [
                'product_name'    => $d->product->name(),
                'product_id'      => $d->product_id,
                'qty'             => $d->qty,
                'unit'            => $d->unit(),
                'unitraw'         => $d->unit
            ];
        }

        return response()->json($result);
    }

    public function addReturnMemo(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'delivery_id'              => 'required',
            'date'                  => 'required',
            'reason'                => 'required',
            'delivery_product_id'    => 'required|array',
            'delivery_product_qty'    => 'required|array',
            'delivery_product_unit'    => 'required|array',
        ], [
            'delivery_id.required'                => 'Delivery order cannot be empty.',
            'date.required'                       => 'Date information cannot be empty.',
            'reason.required'                    => 'Reason cannot be empty.',
            'delivery_product_id.required'      => 'Product cannot empty.',
            'delivery_product_id.array'         => 'Product must be array.',
            'delivery_product_qty.required'     => 'Product qty cannot empty.',
            'delivery_product_qty.array'        => 'Product qty must be array.',
            'delivery_product_unit.required'    => 'Product unit cannot empty.',
            'delivery_product_unit.array'       => 'Product unit must be array.'
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {

            $delivery = SampleDelivery::find($request->delivery_id);

            $query = SampleReturnMemo::create([
                'user_id'               => session('bo_id'),
                'date'                  => $request->date,
                'customer_sample_id'    => $delivery->customerSample->id,
                'sample_delivery_id'    => $delivery->id,
                'reason'                => $request->reason,
                'image'                 => $request->has('return_memo_proof') ? $request->file('return_memo_proof')->store('public/sample') : NULL
            ]);

            foreach ($request->delivery_product_id as $key => $product) {
                SampleReturnMemoDetail::create([
                    'sample_return_memo_id'     => $query->id,
                    'product_id'                => $product,
                    'qty'                       => $request->delivery_product_qty[$key],
                    'unit'                      => $request->delivery_product_unit[$key]
                ]);
            }

            activity()
                ->performedOn(new SampleReturnMemo())
                ->causedBy(session('bo_id'))
                ->withProperties($query)
                ->log('Add Sample return memo by ' . session('bo_name'));

            $response = [
                'status'  => 200,
                'message' => 'Data added successfully.'
            ];
        }

        return response()->json($response);
    }

    public function getDeliveryInfo(Request $request)
    {
        $id = $request->id;

        $product = [];

        $data = SampleDelivery::find($id);
        $data['city_name'] = $data->city->name;

        foreach ($data->sampleDeliveryProduct as $row) {

             $totalstok = 0;
			
			foreach(StockSample::where('product_id',$row->product_id)->get() as $rowstock){
				$totalstok += $rowstock->qty;
			}

            $product[] = [
                'product_id'            => $row->product_id,
                'product_name'          => $row->product->name(),
                'qty'                   => $row->qty,
                'shading'               => $row->shading,
                'unit'                  => $row->unit(),
                'unitraw'               => $row->unit,
                'nominalstock'          => $totalstok
            ];
        }

        return response()->json([
            'data'              => $data,
            'warehouse_name'    => $data->warehouse->name . ' - ' . $data->warehouse->code,
            'product'           => $product
        ]);
    }

    public function getDeliveryNote(Request $request)
    {
        $id = $request->id;

        $notes = [];

        $data = SampleNote::where('notable_type', 'sample_deliveries')->where('notable_id', $id)->get();
        $code = SampleDelivery::find($id)->code;

        foreach ($data as $row) {
            $notes[] = [
                'id'                    => $row->id,
                'is_public'             => $row->is_public,
                'created_at'            => $row->created_at,
                'code'                  => $code,
                'note'                  => $row->note,
                'image'                 => $row->image ? (explode('.', $row->image)[1] == 'pdf' ? '<a href="' . $row->image() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="' . $row->note . '" data-group="a" href="' . $row->image() . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>') : '',
            ];
        }

        return response()->json($notes);
    }

    public function addSalesNote(Request $request)
    {

        $pnp = SampleNote::create([
            'user_id'                => session('bo_id'),
            'notable_type'           => $request->mode,
            'notable_id'             => $request->id,
            'note'                   => $request->note,
            'image'                  => $request->file('file') ? $request->file('file')->store('public/sample') : ''
        ]);

        $pn = SampleNote::where('notable_type', $request->mode)->where('notable_id', $request->id)->get();
        if ($request->mode == 'sample') {
            $code = Sample::find($request->id)->code;
        } elseif ($request->mode == 'sample_deliveries') {
            $code = SampleDelivery::find($request->id)->code;
        }

        $data = [];

        foreach ($pn as $row) {
            $data[] = [
                'id'                   => $row->id,
                'created_at'           => $row->created_at,
                'code'                 => $code,
                'note'                 => $row->note,
                'image'                => $row->image ? (explode('.', $row->image)[1] == 'pdf' ? '<a href="' . $row->image() . '" class="btn btn-sm btn-info" target="_blank"><i class="icon-search4"></i></a>' : '<a data-magnify="gallery" data-src="" data-caption="' . $row->note . '" data-group="a" href="' . $row->image() . '"><img src="' . $row->image() . '" style="max-width:70px;" class="img-fluid img-thumbnail"></a>') : '',
            ];
        }

        activity()
            ->performedOn(new SampleNote())
            ->causedBy(session('bo_id'))
            ->withProperties($pnp)
            ->log('Add note to sample');

        return response()->json($data);
    }

    public function addReceivedDate(Request $request)
    {

        $sampledelivery = SampleDelivery::find($request->id);

        if(CheckCutOff::check($sampledelivery->sample->sales->branch,substr($request->date,0,7))){

        if (!$request->has('file')) {
            return response()->json([
                'status'  => 500,
                'message' => 'Error. Please choose file sir/madam.'
            ]);
        }

        if ($request->arrProduct) {
            foreach ($request->arrProduct as $key => $ar) {
                $sampledeliveryproduct = SampleDeliveryProduct::where('sample_delivery_id', $request->id)->where('product_id', $ar)->update(['qty' => $request->arrQty[$key]]);
            }
        }

        if ($request->has('file')) {
            if (Storage::exists($sampledelivery->image)) {
                Storage::delete($sampledelivery->image);
            }

            $image = $request->file('file')->store('public/sample');
        } else {
            $image = $sampledelivery->image;
        }

        $sampledelivery->update([
            'received_date'    => $request->date,
            'due_date'         => $request->duedate,
            'image'            => $image
        ]);

        $type = '3';
        $description = 'Sample with delivery code ' . $sampledelivery->code;


        $arr = $sampledelivery->getTotal();

        $debetnominal1 = round($arr['totalpurchase']);
        $kreditnominal1 = round($arr['totalpurchase']);


        if ($sampledelivery->is_sales == '2') {
            $debet1 = 341;
        }

        $kredit1 = 339;

        #START

        if ($sampledelivery->is_sales == '2') {
            $cb = CashBank::create([
                'user_id'             => session('bo_id'),
                'lookable_type'       => 'sample_deliveries',
                'lookable_id'         => $sampledelivery->id,
                'code'                => strtoupper(Str::random(15)),
                'date'                => $request->date,
                'type'                => $type,
                'description'         => $description
            ]);

            if ($cb) {
                CashBankDetail::create([
                    'cash_bank_id'     => $cb->id,
                    'coa_id'           => $debet1,
                    'branch'           => $sampledelivery->sample->sales->branch,
                    'type'             => '1',
                    'nominal'          => round($debetnominal1, 0),
                    'note'             => ''
                ]);

                Journal::insert([
                    'date_transaction' => $request->date,
                    'journalable_type' => 'cash_banks',
                    'journalable_id'   => $cb->id,
                    'coa_id'           => $debet1,
                    'branch'           => $sampledelivery->sample->sales->branch,
                    'type'             => '1',
                    'nominal'          => round($debetnominal1, 0),
                    'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s')
                ]);

                CashBankDetail::create([
                    'cash_bank_id'     => $cb->id,
                    'coa_id'           => $kredit1,
                    'branch'           => $sampledelivery->sample->sales->branch,
                    'type'             => '2',
                    'nominal'          => round($kreditnominal1, 0),
                    'note'             => ''
                ]);

                Journal::insert([
                    'date_transaction' => $request->date,
                    'journalable_type' => 'cash_banks',
                    'journalable_id'   => $cb->id,
                    'coa_id'           => $kredit1,
                    'branch'           => $sampledelivery->sample->sales->branch,
                    'type'             => '2',
                    'nominal'          => round($kreditnominal1, 0),
                    'created_at'       => date('Y-m-d', strtotime($cb->date)) . ' ' . date('H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s')
                ]);
            }
        }

        #END

        #updatestock
        $delivery = SampleDelivery::find($request->id);

        foreach ($delivery->sampleDeliveryProduct as $row) {
            $cek = StockSample::where('product_id', $row->product_id)->where('warehouse_id', $delivery->warehouse_id)->where('branch', $delivery->sample->sales->branch)->first();
            if ($cek) {
                $cek->update([
                    'qty'     => $cek->qty - $row->qty,
                    'unit'    => $row->unit
                ]);
            } else {
                StockSample::create([
                    'product_id'     => $row->product_id,
                    'warehouse_id'   => $delivery->warehouse_id,
                    'qty'            => $row->qty * -1,
                    'unit'           => $row->unit,
                    'branch'         => $delivery->sample->sales->branch
                ]);
            }
        }

        #start notif
        $role = array('1', '2', '3', '4', '5', '6', '7', '9', '10', '11');
        $title = 'Sample delivery has been updated!';
        $description = 'Sample ' . $sampledelivery->sample->code . ' with details delivery code ' . $sampledelivery->code . ' has been received by the customer.';
        $link = '#';
        Notification::sendNotif($role, $title, $description, $link);
        #end notif

        #update cogs


        activity()
            ->performedOn(new SampleDelivery())
            ->causedBy(session('bo_id'))
            ->withProperties($sampledelivery)
            ->log('Update delivery received date.');

        return response()->json([
            'status'  => 200,
            'message' => 'Data added successfully.'
        ]);

        }else{
        	return response()->json([
        		'status'  => 503,
        		'message' => 'You cannot add/edit. The journal for this month was already closed.'
        	]);
        }
    }


    public function addReceivedProof(Request $request)
    {
        $purchasedelivery = SampleDelivery::find($request->id);
        $due_date_tt = $request->due_date_tt;

        if ($request->hasFile('file')) {
            if (Storage::exists($purchasedelivery->image)) {
                Storage::delete($purchasedelivery->image);
            }

            $image = $request->file('file')->store('public/sample');
        } else {
            $image = $purchasedelivery->image;
        }

        if ($request->hasFile('file2')) {
            if (Storage::exists($purchasedelivery->image_tt)) {
                Storage::delete($purchasedelivery->image_tt);
            }

            $image2 = $request->file('file2')->store('public/sample');
        } else {
            $image2 = $purchasedelivery->image_tt;
        }

        $purchasedelivery->update([
            'image'          => $image,
            'due_date_tt'    => $due_date_tt,
            'image_tt'       => $image2,
        ]);

        return response()->json([
            'status'  => 200,
            'message' => 'Data added successfully.'
        ]);
    }

    public function addReturnProof(Request $request)
    {

        $psr = SampleReturn::find($request->id);

        if ($request->has('file')) {
            if (Storage::exists($psr->image)) {
                Storage::delete($psr->image);
            }

            $image = $request->file('file')->store('public/sample');
            $return_date = $request->return_date;

            $psr->update([
                'image'          => $image,
                'date_return'    => $return_date
            ]);

            $response = [
                'status'  => 200,
                'message' => 'Data added successfully.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Ups, please choose file.'
            ];
        }

        activity()
            ->performedOn(new SampleReturn())
            ->causedBy(session('bo_id'))
            ->withProperties($psr)
            ->log('Update proof on Sample Return');

        return response()->json($response);
    }


    public function repairupdateGrandtotalWr(){
        $sample = SampleWarehouse::all();

       foreach ($sample as $row) {
         $row->updateGrandtotal();
       }
    }

    public function deleteDelivery(Request $request)
	{
		$pd = SampleDelivery::find($request->id);
		
		if($pd->received_date){
			return response()->json([
				'status'  => 400,
				'message' => 'Sorry, This delivery already received by customer.'
			]);
		}else{
			
			$cb = CashBank::where('lookable_type','sample_deliveries')->where('lookable_id',$pd->id)->get();
			
			foreach($cb as $c){
				$c->deleteDetail();
				$c->delete();
			}
			
			$pd->deleteFile();
			$pd->sampleDeliveryProduct()->delete();
			$pd->sampleDeliveryTrack()->delete();
			
			#start notif
			$role = array('1','2','3','4','5','6','7','9','10','11');
			$title = 'Project delivery order ' . $pd->code . ' has been deleted!';
			$description = 'Project delivery order '.$pd->code.' has been deleted by ' .session("bo_name");
			$link = '#';
			Notification::sendNotif($role,$title,$description,$link);
			#end notif
			
			activity()
				->performedOn(new SampleDelivery())
				->causedBy(session('bo_id'))
				->withProperties($pd)
				->log('Delete project delivery');
			
			$pd->delete();
				
			return response()->json([
				'status'  => 200,
				'message' => 'Data added successfully.'
			]);
			
		}
	}


    public function repairBranchCashBankWarehouseReceive(){
        $sample = SampleWarehouse::all();

        foreach ($sample as $val) {
            foreach (CashBank::where('lookable_type', 'sample_warehouses')->where('lookable_id', $val->id)->get() as $key => $row) {
                foreach ($row->cashBankDetail as $rowcbd) {
                        $rowcbd->update([
                            'branch'  => $val->samplePurchase->sales->branch
                        ]);
                }
                foreach ($row->journalDetail as $rowj) {
                    $rowj->update([
                        'branch'  => $val->samplePurchase->sales->branch
                    ]);
                }
            }
        }
    }
}
